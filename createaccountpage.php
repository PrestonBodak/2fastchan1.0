<?php session_start(); ?>

<html>
<style>
    body {
        background-image: url(res/bean.png);
        font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
    }

    #createaccountbox {
        border: 3.5px solid black;
        background-color: grey;
        padding: 10px;
    }
</style>

<head>
    <title>Create a 2fastchan account</title>
    <link rel="icon" href="res/2fastlogo.png">
</head>

<body>
    <table id="createaccountbox">
        <form action="/redirects/createaccount.php" method="POST">
            <tr>
                <td>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password">Password: </label>
                    <input type="password" name="password" id="password">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit">
                </td>
            </tr>
        </form>
        <tr>
            <td><button onclick="location.href = 'index.php';">Back</button></td>
            <td><?php echo $_SESSION['unsuccessfulCreation'] ? "<h4>Username is taken.</h4>" : "";
                unset($_SESSION['unsuccessfulCreation']); ?></td>
        </tr>
    </table>

</body>

</html>