<?php

function emailVerify($mysqli, $email) {
    if (!isset($email)) {
        return false;
    }
    if ($stmt = $mysqli->prepare("select * from Users where email = ?")) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->num_rows < 1) {
                return false;
            } else return true;
        } finally {
            $stmt->close();
        }
    }
}

function userVerify($mysqli, $email, $password) {
    if (!isset($email) || !isset($password)) {
        return false;
    }
    if ($stmt = $mysqli->prepare("select * from Users where email = ? and password = ?")) {
        $stmt->bind_param("ss", $email,$password);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->num_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e){
            echo $e;
        }
        finally {
            $stmt->close();
        }
    }
}


function getNameByUid($mysqli, $email) {
    if (!isset($email)) {
        return "";
    }
    if ($stmt = $mysqli->prepare("select username from Users where email = ?")) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($username);
        try {
            while ($stmt->fetch()) {
                return $username;
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}


function savetoDB($pid, $content) {
    $mysqli = new mysqli("localhost", "root", "123456", "project", 3306);
    if ($mysqli->connect_errno) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    $did = "d0001";
    date_default_timezone_set("America/New_York");
    try {
        if ($stmt = $mysqli->prepare("insert into details values(?,?,?,?)")) {
            $time = date("Y-m-d H:i:s");
            $stmt->bind_param("ssss", $did, $pid, $time, $content);
            $stmt->execute();
            return "rows inserted: $stmt->affected_rows, $did, $pid, $time";
        }
        return "fail";
    } finally {
        $mysqli->close();
    }

}
// Check keyword
function testgetcontent()
{
    $mysqli = new mysqli("localhost", "root", "123456", "project", 3306);
    if ($mysqli->connect_errno) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    try {
        if ($stmt = $mysqli->prepare("select dcontent from details where did = ?")) {
            $did= "d0001";
            $stmt->bind_param("s", $did);
            $stmt->execute();
            $stmt->bind_result($content);
            while ($stmt->fetch()) {
                return $content;
            }
        }
    } finally {
        $stmt->close();
        $mysqli->close();
    }
}
?>
