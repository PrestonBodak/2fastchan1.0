<?php
session_start();
#                       Servername   Username   Password    Database
$conn = mysqli_connect("localhost", "bruhman", "password!", "test");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

#IP without . chars
$ip = str_replace(".", "", $_SERVER['REMOTE_ADDR']);

#Shuffled UID
$userid = shuffleIP($ip);

#Send an SQL-sanitzed message if the original content is longer than 0 characters long
if (strlen($_POST['message']) > 0) {                                                                                         #HTML-sanitizing occurs on display
    $sql = "INSERT INTO messages (recipient, source, message) VALUES (\"" . $_POST['recipient'] . "\", \"" . (array_key_exists('username', $_SESSION) ? $_SESSION['username'] : $userid) . "\", \"" . mysqli_real_escape_string($conn, $_POST['message']) . "\")";
    que($conn, $sql);
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

#Redirect back to inbox page
header('Location: ../inbox.php');
