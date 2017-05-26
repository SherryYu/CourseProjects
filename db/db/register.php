<?php
/**
 * Created by PhpStorm.
 * User: yatingyu
 * Date: 4/19/17
 * Time: 9:41 PM
 */
session_start();
require 'verification.inc';
require "helperfunction.inc";

$mysqli = new mysqli("localhost", "root", "123456", "project", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if (isset($_POST)) {
    $fullname = $_POST["firstname"] . " " . $_POST["lastname"];
    $email = $_POST["email"];
    $password = sha1($_POST["password"]);

    echo $email, $password;
    // verify the user
    if (!emailVerify($mysqli, $email)) {
        if(insertUserAccount($mysqli,$email,$fullname,$password)) {
            $_SESSION["email"] = $email;
            $_SESSION["username"] = $fullname;
            setcookie("user", $_SESSION["email"], time()+3600);
            setcookie("password", $_POST["password"], time()+3600);
            header("Location: addcard.php");
            exit;
        }
    }
}
echo $_SESSION["email"];
$_SESSION["wrong_create"] = "Error, try again";
header("Location: login.php?#signup");
exit();

