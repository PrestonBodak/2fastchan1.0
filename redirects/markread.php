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

que($conn, "UPDATE messages SET beenRead=1 WHERE (recipient=\"" . $userid . "\" OR recipient=\"" . $_SESSION['username'] . "\");");

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

header('Location: ../inbox.php');
