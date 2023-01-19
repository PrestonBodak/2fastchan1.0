<?php
session_start();
#Used to actually print what's wrong instead of ambiguous google error messages
ini_set('display_errors', 1);
error_reporting(-1);

#                       Servername   Username   Password    Database
$conn = mysqli_connect("localhost", "bruhman", "password!", "test");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

#IP without . chars
$ip = str_replace(".", "", $_SERVER['REMOTE_ADDR']);

#Shuffled UID
$userid = shuffleIP($ip);

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
    <link rel="stylesheet" href="inboxstyling.css">>
</head>

<body>
    <header id="directform">
        <h1> Direct Messaging </h1>
        <form action="redirects/submitmessage.php" method="POST">
            <label for="recipient">To: </label>
            <input placeholder="Recipient username/UID" maxlength="20" name="recipient" id="recipient" type="text">
            <p>Message:</p>
            <textarea name="message" id="message" style="resize: none;" cols="63" rows="9"></textarea>
            <table>
                <tr>
                    <td>
                        <input class="button" type="button" onclick="location.href='index.php';" value="Back">
                    </td>
                    <td><button id="messagesend" type="submit">Send</button></td>
                </tr>
            </table>
        </form>
    </header>

    <form id="markasread" action="redirects/markread.php">
        <input class="button" type="submit" value="Mark all as read">
    </form>

    <div id="messagestack">
        <table>
            <?php
            $arr = mysqli_fetch_all(que($conn, "SELECT source, message, date FROM messages WHERE (recipient=\"" . $userid . (array_key_exists("username", $_SESSION) ? "\" OR recipient=\"" . $_SESSION['username'] : "") . "\") ORDER BY date DESC"));

            foreach ($arr as $row) {
                $from = $row[0];
                $message = $row[1];
                $date = $row[2];

                echo "<div class=\"messages\"><tr> <td> <h4 class=\"messageheaders\">Message From: " . $from . " at " . $date . ": </h4> </td> </tr>";
                echo "<tr> <td> <p class=\"messagebodies\"> " . htmlspecialchars($message) . "</p> </td> </tr> </div>";
            }
            ?>
        </table>
    </div>

</body>

</html>