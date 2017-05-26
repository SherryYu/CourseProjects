<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$func = explode("=", $_SERVER["QUERY_STRING"])[1];
?>
<html>
<body>
<?php
require "helperfunction.inc";

$mysqli = new mysqli("localhost", "root", "123456", "", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$pid = 0;
switch ($func) {
    case "project":
        $pid = getCurPID($mysqli);
        insertProject($mysqli, $pid);
        insertCatalogue($mysqli, $pid);
        insertTags($mysqli, $pid);
        break;
    case "user":
        updateUser($mysqli, $_SESSION["email"]);
        break;
    case "comment":
        insertComment($mysqli,$_SESSION["email"]);
        break;
    case "card":
        insertCards($mysqli,$_SESSION["email"]);
        break;
    case "pledge":
        insertPledge($mysqli, $_SESSION["email"]);
        break;
    case "detail":
        insertDetail($mysqli);
        break;
    case "rate":
        insertRate($mysqli, $_SESSION["email"]);
        break;
    default:
        break;
}
$mysqli->close();


function insertProject($mysqli, $pid) {
    date_default_timezone_set("America/New_York");
    if (isset($_POST)) {
        $pname = trim($_POST["pname"]);
        $pdes = trim($_POST["pdes"]);
        $min = $_POST["min"];
        $max = $_POST["max"];
        $post = date('Y-m-d H:i:s');
        $end = $_POST["end"];
        $planned = $_POST["planned"];
        $cardno = $_POST["creditcard"];
        if ((($_FILES["pfile"]["type"] == "image/png") || ($_FILES["pfile"]["type"] == "image/jpeg")) && ($_FILES["pfile"]["size"] < 2000000)) {
            if ($_FILES["pfile"]["error"] <= 0) {
                $filename = "userupload/" . strval($pid) . ".".explode(".",$_FILES["pfile"]["name"])[1];
                if (file_exists("userupload/" . $_FILES["pfile"]["name"])) {
                    unlink("userupload/" . $_FILES["pfile"]["name"]);
                }
                move_uploaded_file($_FILES["pfile"]["tmp_name"], $filename);
                if (!saveProject($mysqli, $pname, $pdes, $_SESSION["email"],$min, $max, $post, $end, $planned,$cardno,$filename))
                    echo "something goes wrong";
            }
        }

    }
}

function getCurPID($mysqli) {
    $auto = 0;
    if ($stmt = $mysqli->prepare("select AUTO_INCREMENT from information_schema.tables where table_schema = 'project' and table_name='projects'")) {
        $stmt->execute();
        $stmt->bind_result($auto);
        try {
            while($stmt->fetch()) {
                return $auto;
            }
        } catch (mysqli_sql_exception $e){
            echo $e;
        }
        finally {
            $stmt->close();
        }
        return 0;
    }
}

function insertCatalogue($mysqli, $pid) {
    if (isset($_POST)) {
        $catalogue = $_POST["catalogue"];
        echo "cat:", $catalogue;
        if ($pid ==0 || !saveCatalogued($mysqli, $catalogue, $pid))
            echo "something goes wrong";
        else echo "oh";
    }
}

function insertTags($mysqli, $pid) {
    if (isset($_POST)) {
        $tags = $_POST["tags"];
        foreach ($tags as $val) {
            if ($pid ==0 || !saveTagged($mysqli, $val, $pid))
                echo "something goes wrong";
            else header("Location: myprojects.php");
        }
    }
}

function updateUser($mysqli, $uid) {
    if (isset($_POST)) {
        $username = $_POST["username"];
        $des = $_POST["des"];
        $addr = $_POST["addr"];
        if (!saveUser($mysqli, $uid, $username, $des, $addr))
            echo "something goes wrong";
        else header("Location: userprofile?id=".$uid);
    }
}

function insertCards($mysqli, $uid) {
    if (isset($_POST)) {
        $cardno = $_POST["cardno"];
        $owner = trim($_POST["owner"]);
        $m = $_POST["month"];
        $year = explode("-", $m)[0];
        $month = explode("-", $m)[1];
        $type = $_POST["type"];
        $service = $_POST["service"];
        $cvv = $_POST["cvv"];
        if (!saveCards($mysqli, $uid, $cardno, $owner, $year, $month, $cvv, $type,$service))
            echo "something goes wrong";
        else
            header("Location: addCard.php");
    }
}

function insertComment($mysqli,$uid) {
    if (isset($_POST)) {
        $pid = $_POST["pid"];
        $comment = trim($_POST["newcomment"]);
        date_default_timezone_set("America/New_York");
        $ctime = date('Y-m-d H:i:s');
        if (!saveComment($mysqli,$uid, $pid, $ctime, $comment))
            echo "something goes wrong";
    }
}

function insertPledge($mysqli, $uid) {
    date_default_timezone_set('America/New_York');
    $pltime = $post = date('Y-m-d H:i:s');
    if (isset($_POST)) {
        $pid = $_POST["pid"];
        $amount = $_POST["amount"];
        $cnumber = $_POST["cnumber"];
        if (!savePledge($mysqli,$uid, $cnumber, $pid, $pltime, $amount))
            echo "something goes wrong";
        else header("Location: pledgedprojects.php");
    }
}

function insertDetail($mysqli) {
    date_default_timezone_set("America/New_York");
    if (isset($_POST)) {
        $pid = $_POST["pid"];
        $title = trim($_POST["title"]);
        $dtime = date('Y-m-d H:i:s');
        $did = strval($pid) . $dtime;
        $content = $_POST["content"];
        if (!saveDetail($mysqli, $did, $pid, $title, $dtime, $content))
            echo "something goes wrong";
        else
            header("Location: projectdetail.php?id=".$pid);
    }
}

function insertRate($mysqli, $uid) {
    if (isset($_POST)) {
        $pid = $_POST["pid"];
        $star = $_POST["star"];
        if (!saveRate($mysqli, $uid, $pid, $star))
            echo "something goes wrong";
        else
            header("Location: pledgedprojects.php");
    }
}


?>

</body>
</html>
