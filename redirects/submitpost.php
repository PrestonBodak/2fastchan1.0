<?php
#                       Servername   Username   Password    Database
$conn = mysqli_connect("localhost", "bruhman", "password!", "test");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$password = "R2d2902020";

#Send an SQL-sanitzed message if the original content is longer than 0 characters long
if (strlen($_POST['textbox']) > 0 && $_POST['textbox'] != $password) {                   #HTML-sanitizing occurs on display
    $sql = "INSERT INTO posts (uid, post) VALUES (\"" . $_POST['username'] . "\", \"" . mysqli_real_escape_string($conn, $_POST['textbox']) . "\")";
    que($conn, $sql);
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

#Redirect to the homepage
if ($_POST['textbox'] == $password)
    header('Location: ../cloud.php');
else
    header('Location: ../index.php');
