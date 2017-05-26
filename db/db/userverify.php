<html>
<body>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'verification.inc';

$mysqli = new mysqli("localhost", "root", "123456", "project", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if (isset($_POST)) {
    $email = $_POST["email"];
    $password = sha1($_POST["password"]);
}
if (isset($_SESSION["email"]))
    unset($_SESSION["email"]);

// verify the user
if (!emailVerify($mysqli, $email)) {
    $_SESSION["no_email_msg"] = "Email does not exist";
    // Relocate to the home page
    header("Location: login.php");
    exit;
} else {
    $_SESSION["email"]=$_POST["email"];
    if (!userVerify($mysqli, $email, $password)) {
        $_SESSION["wrong_pwd_msg"] = "Invalid password";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION["username"]=getNameByUid($mysqli, $email);
        setcookie("user", $_SESSION["email"], time()+3600);
        setcookie("password", $_POST["password"], time()+3600);
        header("Location: index.php");
        exit;
    }
}

$mysqli->close();



?>
</body></html>

