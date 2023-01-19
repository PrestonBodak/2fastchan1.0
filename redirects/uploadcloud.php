<?php
move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], ("/cloud/" . $_FILES["fileToUpload"]["name"]));
header("Location: ../cloud.php");
