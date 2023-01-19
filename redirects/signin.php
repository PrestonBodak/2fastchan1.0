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

$result = que($conn, "SELECT * FROM users WHERE username=\"" . $_POST['username'] . "\";");
$arr = mysqli_fetch_row($result);
$uid = $arr[0];
$username = $arr[1];
$password = $arr[2];

if (hash("sha256", $_POST['password']) == $password) {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['isSignedIn'] = true;
    header("Location: ../index.php");
} else {
    $_SESSION['unsuccessfulLog'] = true;
    header("Location: ../signinpage.php");
}
