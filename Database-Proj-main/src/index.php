<!DOCTYPE html>
<html lang="en" style="height: 100%;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php
require "db.php";
session_start();
if(isset($_POST["logout"])){
    session_destroy();
}
if (isset($_POST["login"])) {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        if (authenticate($_POST["username"], $_POST["password"]) ==1) {
            $_SESSION["username"]=$_POST["username"];
            header("Location: main.php");
        }else{
            echo '<p style="color:red"> Incorrect username and password </p>';
        }
    }
}

?>

<body style="height: 100%">
    <form action="index.php" method="post" type="submit" style="justify-content: center; align-items: center; display: flex; flex-direction: column; height: 100%;">
        <div style="outline: 2px solid black; padding: 5px;">
            <div style="padding-bottom: 2px;">
                <label for="username">Username: </label>
                <input type="text" name="username" style="width: 200px;">
            </div>

            <div> <label for="password">Password: </label>
                <input type="password" name="password" autocomplete="off" autocorrect="off" spellcheck="off" style="width: 200px; left: 2px;">
            </div>

            <button type="submit" name="login">
                login
            </button>


        </div>
    </form>

</body>

</html>