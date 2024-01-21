<html>
<?php
require "db.php";
session_start();

?>
<div style="border: 2px solid black; max-width: 600px; margin: auto; padding: 20px; box-sizing: border-box;">
    <form method="POST" action="registration.php">
        <p>Please fill out the details below to create an account:</p>
        <p>First Name:
            <input type="text" name="first_name">
        </p>
        <p>Last Name:
            <input type="text" name="last_name">
        </p>
        <p>Email:
            <input type="text" name="email">
        </p>
        <p>Username:
            <input type="text" name="create_username">
        </p>
        <p>Password:
            <input type="text" name="create_password">
        </p>
        <p>Password Again:
            <input type="text" name="create_password2">
        </p>
        <p>Address:
            <input type="text" name="address">
        </p>
        <div style="justify-content: space-around;">
            <input type="submit" value="Submit" name="Submit">
            <input type="submit" value="Home" name="Home">
        </div>
    </form>
</div>

<?php

if (isset($_POST["Submit"])) {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $create_username = $_POST["create_username"];
    $create_password = $_POST["create_password"];
    $create_password2 = $_POST["create_password2"];
    $address = $_POST["address"];
    $response = createAccount($create_username, $create_password, $create_password2, $first_name, $last_name, $address, $email);
    echo $response;
}

if (isset($_POST["Home"])){
    header("Location: main.php");
}

?>
</html>