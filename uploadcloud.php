
<?php
$filename = $_FILES["fileToUpload"]["tmp_name"];
$dir = ("cloud/" . basename($_FILES["fileToUpload"]["name"]));

if (!file_exists($dir))
    move_uploaded_file($filename, $dir);
header("Location: http://2fastchan.com/cloud.php");
?>