<!DOCTYPE html>

<?php
session_start();
#If the user is returning from clicking the logout button, refresh the session data
if (isset($_POST['logout']))
  session_destroy();

#Used to actually print what's wrong instead of ambiguous google error messages
ini_set('display_errors', 1);
error_reporting(-1);

#IP without . chars
$ip = str_replace(".", "", $_SERVER['REMOTE_ADDR']);

#Shuffled UID
$userid = shuffleIP($ip);

#Set default page number to 1
if (!array_key_exists("pagenumber", $_GET))
  $_GET['pagenumber'] = 1;


#Identify if user is signed in
if (array_key_exists('isSignedIn', $_SESSION))
  $isSignedIn = true;
else
  $isSignedIn = false;

#Create a connection to the database
#                       Servername   Username   Password    Database
$conn = mysqli_connect("localhost", "bruhman", "password!", "test");

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function shuffleIP($ip)
{
  $out = "";

  for ($i = 0; $i < strlen($ip); $i++) {
    $out .= $ip[$i];
    $ip = substr($ip, 0, $i) . substr($ip, $i + 1);
  }

  return $out . $ip;
}

#Make a query while also printing out error messages, if any
function que($conn, $sql)
{
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo "Error: " . mysqli_error($conn);
    return false;
  } else {
    return $result;
  }
}
?>

<html>

<head>
  <title>2fastchan</title>

  <link rel="icon" href="res/2fastlogo.png">
  <link rel="stylesheet" href="homestyling.css">

  <meta name="robots" content="index,follow" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <meta name="description" content="A 2fastman forum open for discussion of any topics; whether they are related to 2fastman or not is up to you." />

  <!-- Adsense support -->
  <script data-ad-client="ca-pub-5923560273399718" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>

<body>
  <header>
    <table>
      <tr>
        <td>
          <img height="100px" width="100px" src="res/2fastlogo.png" alt="Logo">
        </td>
        <td>
          <table>
            <tr>
              <td>
                <h1 id="welcome"> Welcome to 2fastchan!</h1>
              </td>
            </tr>
            <tr>
              <td>
                <!-- Display guest UID or username, depending on sign-in status -->
                <?php echo "<h3 id='iddesc'>" . ($isSignedIn ? ("Currently signed in as: " . $_SESSION['username']) : ("Your guest UID is: " . $userid)) . "</h3>"; ?>
              </td>
            </tr>
            <tr>
              <td>
                <?php
                if (!$isSignedIn) {
                  #Display sign in /create account buttons
                  echo "
                  <button class=\"button\" onclick=\"location.href='signinpage.php';\">Sign In</button>
                  <button
                   class=\"button\" onclick=\"location.href='createaccountpage.php';\">Create Account</button>";
                } else {
                  #Display log out button
                  echo "<form method=\"POST\">
                  <input style=\"position: relative; right: -90px; padding-left: 30px; padding-right: 30px;\"class=\"button\" type=\"submit\" name=\"logout\" value=\"Log out\">
                  </form>";
                }
                ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <!-- Text input / post creator-->
    <div id="postplace">
      <h3>Compose a post:</h3>
      <form id="postform" action="redirects/submitpost.php" method="POST">
        <textarea maxlength="300" style="resize: none;" name="textbox" id="textbox" cols="63" rows="9"></textarea>
        <input type="submit">
        <!-- Set the post creator as the user ID if they are not signed in -->
        <input type="hidden" name="username" value=<?php echo "\"" . ($isSignedIn ? $_SESSION['username'] : $userid) . "\""; ?>>
      </form>
    </div>
  </header>

  <!-- Mail button + alerts -->
  <section id=subbuttons>
    <table>
      <tr>
        <td>
          <?php
          $readarr = mysqli_fetch_all(que($conn, "SELECT beenRead FROM messages WHERE (recipient=\"" . $userid . (array_key_exists("username", $_SESSION) ? "\" OR recipient=\"" . $_SESSION['username'] : "") . "\") AND beenRead=0"));

          echo "<div style=\"" . (count($readarr) == 0 ? "height: 75px;" : "") . "\" id=\"mail\">";

          #Display alert icon and message if the user has unread posts
          if (count($readarr) > 0) {
            echo "<table id=\"unread\"><tr><td>" . count($readarr) . " unread messages!</td>";
            echo "<td><img id=\"alertico\" width=\"55px\" height=\"50px\" src=\"res\\alert.png\" alt=\"alerticon\"\\></td></tr></table>";
          }
          ?>

          <img src="" alt="">

          <table>
            <tr>
              <!-- Using a div to edit here doesn't work for some reason -->
              <!-- Edit the styling of the mail box based on if the user has unread posts-->
              <td <?php echo "style=\"position: relative; " . (count($readarr) == 0 ? "top: -17px;" : "top: -10px") . "\"" ?>>
                <strong>Mail:</strong>
              </td>
              <td <?php echo "style=\"position: relative; " . (count($readarr) == 0 ? "top: -17px;" : "top: -10px") . "\"" ?>>
                <button class="button" onclick="location.href='inbox.php';">View Inbox</button>
              </td>
            </tr>
          </table>
          </div>
        </td>
      </tr>
    </table>

  </section>

  <!-- Display posts -->
  <div id="contentstack">
    <table>
      <?php
      #Retrieve a 2D array of 12 posts with UID and dates the post was made based upon the current pagenumber
      $result = que($conn, "SELECT uid, post, date FROM posts ORDER BY date DESC LIMIT " . ((intval($_GET['pagenumber']) - 1) * 12) . ", " . 12);

      $arr = mysqli_fetch_all($result);

      #Print each row in the array as a post
      foreach ($arr as $row) {
        $uid = $row[0];
        $post = $row[1];
	
	#Don't display empty posts
	if(strlen($post) == 0)
		continue;

	#Format the date because the version stored in the database is ugly
	$dateTime = new DateTime($row[2]);
        $date = $dateTime->format("g:i a m-d-y");

        #Echo a row for the user info and a row with the post text (HTML-sanitized) 
        echo "<div class=\"posts\"><tr> <td> <h4 class=\"postheaders\"> User " . $uid . " at " . $date . " said: </h4> </td> </tr>";
        echo "<tr> <td> <p class=\"postbodies\"> " . htmlspecialchars($post) . "</p> </td> </tr> </div>";
      }
      ?>

      <!-- Row with page buttons -->
      <tr>
        <td>
          <form action="index.php" , method="GET">
            <?php
            #Get the number of posts divided by 12 (display 12 posts per page)
            $length = floor(mysqli_fetch_row(que($conn, "SELECT count(*) FROM posts"))[0] / 12);

            #Echo a button per page
            for ($i = 0; $i < $length; $i++)
              echo "<input style=\"padding: 8px;\" type=\"submit\" name=\"pagenumber\" value=\"" . ($i + 1) . "\"/>";
            ?>
          </form>
        </td>
      </tr>
    </table>

    <footer>
      <p>Copyright lololol | Current version: Beta | Subject to bugs</p>
    </footer>
</body>

</html>
