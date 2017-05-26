<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/**
 * Created by PhpStorm.
 * User: yatingyu
 * Date: 4/30/17
 * Time: 11:21 PM
 */


// helper functions for createproject.php
function getCatalogue() {
    $mysqli = new mysqli("localhost", "root", "123456", "", 3306);
    if ($mysqli->connect_errno) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    $catname = "";
    $res = array();
    if ($stmt = $mysqli->prepare("select catname from Project.Catalogue")) {
        $stmt->execute();
        $stmt->bind_result($catname);
        try {
            while ($stmt->fetch()) {
                array_push($res, $catname);
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
            $mysqli->close();
        }
    }
    return $res;
}

function getTags() {
    $mysqli = new mysqli("localhost", "root", "123456", "", 3306);
    if ($mysqli->connect_errno) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    $tagname = "";
    $res = array();
    if ($stmt = $mysqli->prepare("select tagname from Project.Tags")) {
        $stmt->execute();
        $stmt->bind_result($tagname);
        try {
            while ($stmt->fetch()) {
                array_push($res, $tagname);
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
            $mysqli->close();
        }
        return $res;
    }
}

function saveProject($mysqli, $pname,$pdes,$uid,$min,$max,$post,$end,$planned,$cardno,$filename) {
    if ($stmt = $mysqli->prepare("insert into project.Projects values(null,?,?,?,?,?,0,?,?,?,null,'processing',?,?)")) {
        $stmt->bind_param("sssddsssss",$pname,$pdes,$uid,$min,$max, $post,$end,$planned,$cardno,$filename);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
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

function saveCatalogued($mysqli, $catalogue, $pid) {
    if ($stmt = $mysqli->prepare("insert into project.catalogued values(?,?)")) {
        $stmt->bind_param('si', $catalogue, $pid);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

function saveTagged($mysqli, $tag, $pid) {
    if ($stmt = $mysqli->prepare("insert into project.Tagged values(?,?)")) {
        $stmt->bind_param('si', $tag, $pid);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

// helper functions for myprojects.php
function getProjectsByUid($mysqli, $uid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select pid,pname, pdescription,minfund, curfund, posttime, pstatus 
from Project.Projects where uid = ? order by posttime desc")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($pid, $pname, $pdes, $min,$cur,$posttime,$stat);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $pname, $pdes,$min,$cur,$posttime,$stat));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

// project owner side
function getPledgesByPid($mysqli, $pid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select uid, username, pltime, amount, plstatus from project.pledge natural join project.users where pid=? order by pltime desc")) {
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($uid, $username, $pltime, $amount,$stat);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($uid, $username, $pltime, $amount,$stat));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

function markComplete($mysqli, $pid) {
    date_default_timezone_set('America/New_York');
    $completetime = date('Y-m-d H:i:s');
    if ($stmt = $mysqli->prepare("update project.projects set pstatus='completed', completetime=? where pid=?")) {
        $stmt->bind_param('ss', $completetime,$pid);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows == 1) return true;
            else return false;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}


//helper functions for pledgedprojects.php
function getUserName($mysqli, $uid) {
    if ($stmt = $mysqli->prepare("select username from Project.Users where uid=?")) {
        $stmt->bind_param('s', $uid);
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
//sponser side
//function getPledgesByUid($mysqli, $uid) {
//    $res = array();
//    if ($stmt = $mysqli->prepare("select pledge.pid, pname, pltime, amount, plstatus from pledge join projects on pledge.pid = projects.pid where pledge.uid=? order by pltime desc")) {
//        $stmt->bind_param('s', $uid);
//        $stmt->execute();
//        $stmt->bind_result($pid, $pname, $pltime, $amount,$stat);
//        try {
//            while ($stmt->fetch()) {
//                array_push($res, array($pid, $pname, $pltime, $amount,$stat));
//            }
//        } catch (mysqli_sql_exception $e) {
//            echo $e;
//        } finally {
//            $stmt->close();
//        }
//        return $res;
//    }
//}

function getPledgesByUid($mysqli, $uid) {

    $res = array();
    if ($stmt = $mysqli->prepare("select pid, pname, p.uid, pltime, amount, plstatus, pstatus from Project.Pledge pl join Project.Projects p using (pid) where pl.uid=? order by pltime desc")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($pid, $lpname, $creator, $pltime, $amount, $plstat,$pstatus);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $lpname,$creator, $pltime, $amount, $plstat,$pstatus));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return $res;
}

function getRateByUidPid($mysqli, $uid, $pid) {
    if ($stmt = $mysqli->prepare("select star from project.Rates where uid=? and pid=?")) {
        $stmt->bind_param('si', $uid, $pid);
        $stmt->execute();
        $stmt->bind_result($star);
        try {
            while($stmt->fetch()) {
                return $star;
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
    return 0;
}

function saveRate($mysqli, $uid, $pid, $star) {
    if ($stmt = $mysqli->prepare("insert into project.Rates values(?,?,?)")) {
        $stmt->bind_param('sii', $uid, $pid, $star);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

//helper functions for profilesetting.php
function saveUser($mysqli, $uid, $username, $des, $addr) {
    if ($stmt = $mysqli->prepare("update project.users set username=?, address=?, interests=? where uid=?")) {
        $stmt->bind_param('ssss', $username, $addr, $des, $uid);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

function saveCards($mysqli, $uid, $cardno, $owner, $year, $month,$cvv, $type,$service) {
    if ($stmt = $mysqli->prepare("insert into  project.cards values(?,?,?,?,?,?,?,?)")) {
        $stmt->bind_param('ssssssss', $uid, $cardno, $owner, $year, $month,$cvv, $type,$service);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

//helper functions for projectdetail.php
function isExistPid($mysqli, $pid) {
    if ($stmt = $mysqli->prepare("select pid from project.projects where pid=?")) {
        $stmt->bind_param('i', $pid);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}
function getProjectDetail($mysqli, $pid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select pname, uid, username, pdescription,minfund,maxfund, curfund, posttime, pstatus,filename 
from Project.Projects natural join Project.Users where pid = ?")) {
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($pname, $uid, $username, $pdes, $min,$max,$cur,$posttime,$stat,$filename);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pname, $uid, $username, $pdes,$min,$max,$cur,$posttime,$stat,$filename));
                return $res;
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

function getCommentsByPid($mysqli, $pid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select uid, username, ctime, cdescription from Project.Comments natural join Project.Users where pid = ? order by ctime desc")) {
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($uid, $username, $ctime, $cdes);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($uid, $username, $ctime, $cdes));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

function getTagsByPid($mysqli, $pid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select tagname from Project.Tagged natural join Project.Projects where pid = ?")) {
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($tag);
        try {
            while ($stmt->fetch()) {
                array_push($res, $tag);
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

function getCatalogueByPid($mysqli, $pid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select catname from Project.Catalogued natural join Project.Projects where pid = ?")) {
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($catname);
        try {
            while ($stmt->fetch()) {
                return $catname;
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

function saveComment($mysqli, $uid, $pid, $ctime, $comment) {
    if ($stmt = $mysqli->prepare("insert into project.Comments values(?,?,?,?)")) {
        $stmt->bind_param('siss', $uid, $pid,$ctime,$comment);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

function getDetailsByPid($mysqli, $pid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select pid, dtitle, dtime, dcontent from Project.Details where pid = ? order by dtime desc")) {
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($pid, $title, $time, $content);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $title, $time, $content));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

function getIfLikeByPid($mysqli, $uid, $pid) {
    if ($stmt = $mysqli->prepare("select pid from Project.Likes where uid= ? and pid=?")) {
        $stmt->bind_param('si', $uid, $pid);
        $stmt->execute();
        $stmt->store_result();
        try {
            if($stmt->num_rows==1) return true;
            else return false;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

function insertLike($mysqli, $uid, $pid) {
    if ($stmt = $mysqli->prepare("insert into Project.Likes values(?,?)")) {
        $stmt->bind_param('si', $uid, $pid);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1)
                return false;
            else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

function deleteLike($mysqli, $uid, $pid) {
    $mysqli->query("delete from Project.Likes where uid='$uid' and pid=$pid");
    echo "$mysqli->affected_rows";
    if ($mysqli->affected_rows != 1)
        return false;
    else return true;
}

function getAvgStar($mysqli, $pid) {
    $stars = 0;
    if ($stmt = $mysqli->prepare("select avg(star) from Project.rates where pid=?")) {
        $stmt->bind_param('i', $pid);
        $stmt->execute();
        $stmt->bind_result($stars);
        try {
            while ($stmt->fetch()) {
                return $stars;
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return $stars;
}


// helper functions for pledge.php
function getUserCard($mysqli, $uid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select cowner, cnumber, expireyear, expiremonth, ctype, cservice from Project.Cards where uid=?")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($owner, $cnumber,$year,$mon, $ctype,$cser);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($owner, $cnumber,$year,$mon, $ctype,$cser));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return $res;
}

function getProjectFundByPid($mysqli, $pid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select minfund, maxfund, curfund from Project.Projects where pid=?")) {
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($min, $max,$cur);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($min, $max,$cur));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return $res;
}

function savePledge($mysqli,$uid, $cnumber, $pid, $pltime, $amount) {
    if ($stmt = $mysqli->prepare("insert into project.Pledge values(?,?,?,?,?,'pending')")) {
        $stmt->bind_param('ssisd', $uid, $cnumber, $pid, $pltime, $amount);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

// helper functions for userprofile.php
function isExistUid($mysqli, $uid) {
    if ($stmt = $mysqli->prepare("select uid from project.users where uid=?")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

function getUserProfileByUid($mysqli, $uid) {
    if ($stmt = $mysqli->prepare("select username, email, interests from Project.Users where uid=?")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($username, $uemail, $uinterest);
        try {
            while ($stmt->fetch()) {
                return array($username, $uemail, $uinterest);
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
}

function getByFollowedUid($mysqli, $follower, $followed) {
    if ($stmt = $mysqli->prepare("select follower from Project.Follow where follower= ? and followed=?")) {
        $stmt->bind_param('ss', $follower, $followed);
        $stmt->execute();
        $stmt->store_result();
        try {
            if($stmt->num_rows==1) return true;
            else return false;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

function insertFollow($mysqli, $follower, $followed) {
    if ($stmt = $mysqli->prepare("insert into Project.Follow values(?,?)")) {
        $stmt->bind_param('ss', $follower, $followed);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1)
                return false;
            else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

function deleteFollow($mysqli, $follower, $followed) {
    $mysqli->query("delete from Project.Follow where follower='".$follower."' and followed='".$followed."'");
    if ($mysqli->affected_rows != 1)
        return false;
    else return true;
}

// helper functions for cataloguedprojects.php

function isExistCatname($mysqli, $catname) {
    if ($stmt = $mysqli->prepare("select catname from project.catalogue where catname=?")) {
        $stmt->bind_param('s', $catname);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}
function getCataloguedProjects($mysqli, $catname) {
    $res = array();
    if ($stmt = $mysqli->prepare("select pid, pname, minfund,curfund,posttime,pstatus  from Project.Catalogued natural join Project.projects where catname=?")) {
        $stmt->bind_param('s', $catname);
        $stmt->execute();
        $stmt->bind_result($catpid, $catpname, $min, $cur, $posttime, $pstat);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($catpid, $catpname, $min, $cur, $posttime, $pstat));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

// helper functions for tagprojects.php
function isExistTagname($mysqli, $tagname) {
    if ($stmt = $mysqli->prepare("select tagname from project.tags where tagname=?")) {
        $stmt->bind_param('s', $tagname);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1) {
                return false;
            } else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}
function getTaggedProjects($mysqli, $tagname) {
    $res = array();
    if ($stmt = $mysqli->prepare("select pid, pname, minfund,curfund,posttime,pstatus from Project.Tagged natural join Project.projects where tagname=?")) {
        $stmt->bind_param('s', $tagname);
        $stmt->execute();
        $stmt->bind_result($tagpid, $tagpname, $min,$cur,$posttime,$pstat);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($tagpid, $tagpname, $min,$cur,$posttime,$pstat));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

// helper functions for updateproject.php
function getCreatorIdByPid($mysqli, $pid) {
    if ($stmt = $mysqli->prepare("select uid from project.projects where pid=?")) {
        $stmt->bind_param('i', $pid);
        $stmt->execute();
        $stmt->bind_result($uid);
        try {
            while($stmt->fetch()) {
                return $uid;
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
    return "";
}
function saveDetail($mysqli, $did, $pid, $title, $dtime, $content) {
    echo $content;
    if ($stmt = $mysqli->prepare("insert into project.details values(?,?,?,?,?)")) {
        $stmt->bind_param('sisss', $did, $pid,$title,$dtime,$content);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1)
                return false;
            else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
        }
    }
}


// helper functions for login.php, sign up
function insertUserAccount($mysqli, $email, $name, $pwd) {
    if ($stmt = $mysqli->prepare("insert into Project.Users values(?,?,?,?,null,null)")) {
        $stmt->bind_param('ssss', $email, $name, $email, $pwd);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1)
                return false;
            else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

//helper function for result.php
function searchProject($mysqli, $keyword) {
    $res = array();
    $new_keyword = "%".$keyword."%";
    if ($stmt = $mysqli->prepare("select pid,pname, pdescription,minfund,maxfund, curfund, posttime, pstatus 
from project.Projects where pname like ? or pdescription like ? order by posttime desc")) {
        $stmt->bind_param('ss',$new_keyword,$new_keyword);
        $stmt->execute();
        $stmt->bind_result($pid, $pname, $pdes, $min,$max,$cur,$posttime,$stat);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $pname, $pdes,$min,$max,$cur,$posttime,$stat));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

//helper function for followeduser.php
function getFollowedUsers($mysqli, $follower) {
    $res = array();
    if ($stmt = $mysqli->prepare("select followed, u.username from Project.follow f join Project.users u on u.uid=f.followed  where follower=?")) {
        $stmt->bind_param('s', $follower);
        $stmt->execute();
        $stmt->bind_result($followedid, $followedname);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($followedid, $followedname));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

function getFollowedComments($mysqli, $uid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select c.pid, pname, ctime, cdescription from Project.Comments c join Project.Projects p using (pid) where c.uid = ? order by ctime desc limit 3")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($pid, $pname, $ctime, $cdes);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $pname, $ctime, $cdes));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

function getFollowedLikes($mysqli, $uid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select p.pid, pname from Project.Likes l join Project.Projects p  using (pid) where l.uid = ? limit 3")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($pid, $pname);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $pname));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

function getFollowedRates($mysqli, $uid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select p.pid, pname, star from Project.Rates r join Project.Projects p  using (pid) where r.uid = ? limit 3")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($pid, $pname,$star);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $pname,$star));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}


// helper functions for index.php
function getViewedPidByUid($mysqli, $uid, $num) {
    $res = array();
    if ($stmt = $mysqli->prepare("select pid from
(select pid, max(plogtime) as logtime from Project.Projects p join Project.userprojectlog pl using (pid) where pl.uid =? group by pid) as temp 
order by logtime desc limit ?")) {
        $stmt->bind_param('si',$uid, $num);
        $stmt->execute();
        $stmt->bind_result($plpid);
        try {
            while ($stmt->fetch()) {
                array_push($res, $plpid);
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return $res;
}

function getViewedTagsByUid($mysqli, $uid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select tagname from project.usertaglog where uid=? order by tlogtime desc")) {
        $stmt->bind_param('s',$uid);
        $stmt->execute();
        $stmt->bind_result($tagname);
        try {
            while ($stmt->fetch()) {
                array_push($res, $tagname);
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return array_unique($res);
}

function getSearchedWordByUid($mysqli, $uid) {
    if ($stmt = $mysqli->prepare("select keyword from Project.usersearchlog where uid=? order by slogtime desc limit 1")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($keyword);
        try {
            while ($stmt->fetch()) {
                return $keyword;
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return "";
}

function getSearchedProject($mysqli, $keyword) {
    $res = array();
    $new_keyword = "%".$keyword."%";
    if ($stmt = $mysqli->prepare("select pid from project.Projects where pname like ? or pdescription like ? order by posttime desc")) {
        $stmt->bind_param('ss',$new_keyword,$new_keyword);
        $stmt->execute();
        $stmt->bind_result($pid);
        try {
            while ($stmt->fetch()) {
                array_push($res, $pid);
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return $res;
}

function getSomeProjects($mysqli, $num) {
    $res = array();
    if ($stmt = $mysqli->prepare("select pid,pname, pdescription,minfund, curfund, posttime, pstatus 
from Project.Projects where pstatus <> 'failed' order by posttime desc limit ?")) {
        $stmt->bind_param('i', $num);
        $stmt->execute();
        $stmt->bind_result($pid, $pname, $pdes, $min,$cur,$posttime,$pstat);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $pname, $pdes, $min,$cur,$posttime,$pstat));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
        return $res;
    }
}

function getCatCount($mysqli, $catname) {
    if ($stmt = $mysqli->prepare("select count(*) from Project.catalogued where catname=?")) {
        $stmt->bind_param('s', $catname);
        $stmt->execute();
        $stmt->bind_result($count);
        try {
            while ($stmt->fetch()) {
                return $count;
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return 0;
}


// helper function for likedprojects.php
function getLikedProjects($mysqli, $uid) {
    $res = array();
    if ($stmt = $mysqli->prepare("select pid, pname,pdescription, pl.uid,minfund, curfund, posttime, pstatus from Project.Likes pl join Project.Projects p using (pid) where pl.uid=? order by posttime desc")) {
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($pid, $pname,$pdes,$uid,$min,$cur,$posttime,$stat);
        try {
            while ($stmt->fetch()) {
                array_push($res, array($pid, $pname,$pdes,$uid,$min,$cur,$posttime,$stat));
            }
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->close();
        }
    }
    return $res;
}


// helper function for user log info
function saveSearchLog($mysqli, $uid, $keyword) {
    date_default_timezone_set('America/New_York');
    $logtime = date('Y-m-d H:i:s');
    if ($stmt = $mysqli->prepare("insert into Project.usersearchlog values(?,?,?)")) {
        $stmt->bind_param('sss', $uid, $keyword, $logtime);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1)
                return false;
            else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

function saveViewProjectLog($mysqli, $uid, $pid) {
    date_default_timezone_set('America/New_York');
    $logtime = date('Y-m-d H:i:s');
    if ($stmt = $mysqli->prepare("insert into Project.userprojectlog values(?,?,?)")) {
        $stmt->bind_param('sis', $uid, $pid, $logtime);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1)
                return false;
            else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}

function saveTagLog($mysqli, $uid, $tagname) {
    date_default_timezone_set('America/New_York');
    $logtime = date('Y-m-d H:i:s');
    if ($stmt = $mysqli->prepare("insert into Project.usertaglog values(?,?,?)")) {
        $stmt->bind_param('sss', $uid, $tagname, $logtime);
        $stmt->execute();
        $stmt->store_result();
        try {
            if ($stmt->affected_rows != 1)
                return false;
            else return true;
        } catch (mysqli_sql_exception $e) {
            echo $e;
        } finally {
            $stmt->free_result();
            $stmt->close();
        }
    }
}






