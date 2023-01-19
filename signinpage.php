<?php session_start(); ?>

<html>
<style>
    body {
        background-image: url(res/bean.png);
        font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
    }

    #signinbox {
        border: 3.5px solid black;
        background-color: grey;
        padding: 10px;
    }
</style>

<head>
    <title>Sign in to 2fastchan</title>
    <link rel="icon" href="res/2fastlogo.png">
</head>

<body>
    <table id="signinbox">
        <form action="/redirects/signin.php" method="POST">
            <tr>
                <td><label for="username">Username:</label>
                    <input type="text" name="username" id="username" maxlength="18"></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label>
                    <input type="password" name="password" id="password"></td>
            </tr>
            <tr>
                <td><input type="submit" name="" id=""></td>
            </tr>

        </form>
        <tr>
            <td><button onclick="location.href = 'index.php';">Back</button></td>
            <td><?php echo $_SESSION['unsuccessfulLog'] ? "<h4>Invalid login.</h4>" : "";
                unset($_SESSION['unsuccessfulLog']); ?></td>
        </tr>
    </table>
</body>

</html>