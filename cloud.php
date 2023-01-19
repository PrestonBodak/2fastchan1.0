<html>
<?php
#Formatting function stolen from stackoverflow
function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body class="p-3 mb-2 bg-dark text-white">
    <table class="table table-striped table-dark">
        <tr>
            <!-- Headers -->
            <th><strong>Filename</strong></th>
            <th><strong>Filesize</strong></th>
            <th><strong>Last Modified</strong></th>
        </tr>
        <?php
        $iterator = new FilesystemIterator("/var/www/html/cloud");

        foreach ($iterator as $fileinfo) {
            #Only echo non-folders
            if (is_dir($fileinfo) != 1) {
                $size = (filesize($fileinfo) / 1024 / 1024);
                echo "<tr>";
                #Display filename with download link to file
                echo "<td><a href=\"" . substr($fileinfo, strpos($fileinfo, "cloud")) . "\"download>" . substr($fileinfo, strrpos($fileinfo, "/")) . "</a></td>";

                #Display file size in appropriate unit
                echo "<td>" . formatBytes(filesize($fileinfo), 3) . "</td>";

                #Display last file modification date as: 01/01/2000 20:58:05
                echo "<td>" . str_replace(" ", "/", date("m d Y", filemtime($fileinfo))) . " " . date("H:i:s", filemtime($fileinfo))  . "</td>";
                echo "</tr>";
            }
        }
        ?>
	<tr>
		<td></td>
	</tr>
    </table>

    <!-- Available space + back button -->
    <table style="margin-top: 10px; position: fixed; bottom: -17px;" class="table table-dark">
        <tr>
            <td>Available space: <?php echo formatBytes(disk_free_space("/var/www/html/cloud"), 3) ?></td>
            <form action="uploadcloud.php" method="POST" enctype="multipart/form-data">
                <td><input type="file" name="fileToUpload" id="fileToUpload"></td>
                <td><input type="submit" value="Upload File"></td>
            </form>
            <td><input type="button" onclick="location.href='http://2fastchan.com';" value="Back"></td>
	</tr>
    </table>

</body>

</html>
