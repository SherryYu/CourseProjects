<?php
session_start();
session_destroy();
//setcookie("user", $_SESSION["email"], time()-3600);
//setcookie("password", $_POST["password"], time()-3600);
header("Location: login.php");
exit();
