<?php
session_start();
#                       Servername   Username   Password    Database
$conn = mysqli_connect("localhost", "bruhman", "password!", "test");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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

#Make a random 7-digit number sequence, and retry if it exists already
function makeId($conn)
{
    $out = "";

    for ($i = 0; $i < 7; $i++) {
        $out .= rand(0, 9);
    }

    if (count(mysqli_fetch_row(que($conn, "SELECT uid FROM users WHERE uid='" . $out . "';"))) >= 1)
        return makeId($conn);

    return $out;
}

#Check if the username is already taken
$result = que($conn, "SELECT username FROM users WHERE username=\"" . $_POST['username'] . "\";");
$arr = mysqli_fetch_row($result);

if (count($arr) == 1) {
    #Username is taken
    $_SESSION['unsuccessfulCreation'] = true;
    header("Location: ../createaccountpage.php");
} else {
    #Username is available
    que($conn, "INSERT INTO users VALUES(\"" . makeId($conn) . "\", \"" . $_POST['username'] . "\", \"" . hash("sha256", $_POST['password']) . "\");");

    #Sign in after account creation
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['isSignedIn'] = true;
    header("Location: ../index.php");
}
