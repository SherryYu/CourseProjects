<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require "helperfunction.inc";
$mysqli = new mysqli("localhost", "root", "123456", "", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$func = explode("=", $_SERVER["QUERY_STRING"])[1];

switch ($func) {
    case "like":
        updateLike($mysqli);
        break;
    case "follow":
        updateFollow($mysqli);
        break;
    case "complete":
        updateProject($mysqli);
        break;
    default:
        break;
}
$mysqli->close();

function updateLike($mysqli) {
    if(isset($_POST)) {
        $like = $_POST["like"];
        $uid = $_POST["uid"];
        $pid = $_POST["pid"];
    }
    echo "like:", $like;
    if ($like==1) insertLike($mysqli, $uid, $pid);
    else deleteLike($mysqli, $uid, $pid);
}

function updateFollow($mysqli) {
    if(isset($_POST)) {
        $follow = $_POST["follow"];
        $follower = $_POST["followerid"];
        $followed = $_POST["followedid"];
    }
    if ($follow==1) insertFollow($mysqli, $follower, $followed);
    else deleteFollow($mysqli, $follower, $followed);
}

function updateProject($mysqli) {
    if(isset($_POST)) {
        $pid = $_POST["pid"];
    }
    markComplete($mysqli, $pid);
    header("Location: myprojects.php");
}
?>