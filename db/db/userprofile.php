<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require "helperfunction.inc";
$mysqli = new mysqli("localhost", "root", "123456", "", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
if (isset($_SERVER["QUERY_STRING"])) {
    $uid = explode("=", $_SERVER["QUERY_STRING"])[1];
    if (!isExistUid($mysqli, $uid)) {
        header("Location: 404.php");
    }
}
list($username, $uemail, $uinterest) = getUserProfileByUid($mysqli, $uid);
if (isset($_SESSION["email"])) {
    $isFollowing = getByFollowedUid($mysqli, $_SESSION["email"], $uid);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fund Me</title>

    <!-- Css (necessary Css) -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-theme.css" rel="stylesheet">
    <link href="assets/css/language-selector-remove-able-css.css" rel="stylesheet">
    <link href="assets/css/flexslider.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugin.css" rel="stylesheet">
    <link href="assets/css/responsive.css" rel="stylesheet">
    <link href="assets/css/menu.css" rel="stylesheet">
    <link href="assets/css/color.css" rel="stylesheet">
    <link href="assets/css/iconmoon.css" rel="stylesheet">
    <link href="assets/css/themetypo.css" rel="stylesheet">
    <link href="assets/css/widget.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <?php require "header.php";?>

    <!-- Main Content -->
    <main id="main-content">
        <div class="main-section">
            <div class="page-section">
                <div class="profile-pages">
                    <div class="container">
                        <div class="row">
                            <div class="section-fullwidth col-lg-12">
                                <div class="cs-content-holder">
                                    <div class="row">
                                        <div class="cause-holder">
                                            <div class="col-lg-12">
                                                <div class="cs-auther">
                                                    <figure>
                                                        <a href="userprofile.php?id=<?php echo $uid;?>"><img src="assets/extra-images/auther1.jpg" alt="#"></a>
                                                    </figure>
                                                    <div class="text">
                                                        <h3><a href="userprofile.php?id=<?php echo $uid;?>"><?php echo $username;?></a></h3>
                                                    </div>
                                                </div>
                                                <?php
                                                if (isset($_SESSION["email"]) && ($uid != $_SESSION["email"])) {
                                                    echo "<div class=\"followopt\"><li><iframe style=\"display: none;\" name=\"followframe\"></iframe><form method=\"post\" action=\"updatetablehelper.php?func=follow\" name=\"followform\" id=\"followformid\" target=\"followframe\">";
                                                    echo "<input type=\"hidden\" id=\"followinput\" name=\"follow\" value=\"" . $isFollowing . "\">";
                                                    echo "<input type=\"hidden\" name=\"followedid\" value=\"" . $uid . "\">";
                                                    echo "<input type=\"hidden\" name=\"followerid\" value=\"" . $_SESSION["email"] . "\">";
                                                    if ($isFollowing) {
                                                        echo "<i id=\"followicon\" class=\"icon-heart6\"></i>";
                                                    } else {
                                                        echo "<i id=\"followicon\" class=\"icon-heart-o\"></i>";
                                                    }
                                                    echo "<a id=\"followbtn\" href=\"#\">Follow</a></form></li></div></div>";
                                                }
                                                ?>
                                            <div class="col-lg-12">
                                                <div class="profile-block">
                                                    <div class="cs-profile-area">
                                                        <div class="cs-title">
                                                            <h4>PROFILE</h4>
                                                        </div>
                                                        <div class="cs-profile-holder">

                                                            <ul class="cs-element-list has-border">
                                                                <li>
                                                                    <label>Name</label><label><?php echo $username;?></label>
                                                                </li>
                                                                <li>
                                                                    <label>Email</label><label><?php echo $uemail;?></label>
                                                                </li>
                                                                <li>
                                                                    <label>Description</label><label><?php echo htmlspecialchars($uinterest);?></label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="cs-title">
                                                            <h4>PROJECTS</h4>
                                                        </div>
                                                        <div class="cs-profile-holder">
                                                            <div class="cs-table-holder">
                                                                <h3></h3>
                                                                <table>
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Project Name</th>
                                                                        <th>Current Fund</th>
                                                                        <th>Goal</th>
                                                                        <th>Post Time</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $prs = getProjectsByUid($mysqli, $uid);
                                                                    $i = 1;
                                                                    foreach ($prs as $val) {
                                                                        list($pid, $pname, $pdes, $min,$cur,$posttime,$pstat) = $val;
                                                                        echo "<tr><td>" . $i. "</td>";
                                                                        echo "<td><a href='projectdetail.php?id=".$pid."'>".$pname ."</a></td>";
                                                                        echo "<td>" . $cur . "</td>";
                                                                        echo "<td>" . $min . "</td>";
                                                                        echo "<td>" . $posttime . "</td>";
                                                                        echo "<td>" . $pstat . "</td></tr>";
                                                                        $i = $i + 1;
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="cs-title">
                                                            <h4>PLEDGES</h4>
                                                        </div>
                                                        <div class="cs-profile-holder">
                                                            <div class="cs-table-holder">
                                                                <h3></h3>
                                                                <table>
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Project Name</th>
                                                                        <th>Pledge Time</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $pls = getPledgesByUid($mysqli, $uid);
                                                                    $i = 1;
                                                                    foreach ($pls as $val) {
                                                                        list($lpid, $lpname, $creator,$pltime, $lamount, $plstat) = $val;
                                                                        echo "<tr><td>" . $i. "</td>";
                                                                        echo "<td><a href='projectdetail.php?id=".$lpid."'>".$lpname ."</a></td>";
                                                                        echo "<td>" . $pltime . "</td>";
                                                                        echo "<td>" . $lamount . "</td>";
                                                                        echo "<td>" . $plstat . "</td>";
                                                                        echo "</tr>";
                                                                        $i = $i + 1;
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--// Main Content //-->

    <!--// Footer Widget //-->
    <?php require "footer.php";?>

</div>

<!-- jQuery (necessary JavaScript) -->
<!--<script src="assets/scripts/jquery-3.2.1.min.js"></script>-->
<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
<script src="assets/scripts/bootstrap.min.js"></script>
<script src="assets/scripts/modernizr.js"></script>
<script src="assets/scripts/menu.js"></script>
<script src="assets/scripts/jquery.flexslider-min.js"></script>
<script src="assets/scripts/functions.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.1/js/star-rating.min.js"></script>

<script>
    $(document).ready(function () {
        $("input[name='month']").change(function () {
            alert($("input[name='month']").val());

        });
        $("#starinput").rating();

        $('#followbtn').click(function (event){
            event.preventDefault();
            if ($('#followinput').val()==1) {
                $(this).parent().find('i').removeClass('icon-heart6');
                $(this).parent().find('i').addClass('icon-heart-o');
                $('#followbtn').html('Follow');
                $('#followinput').val(0);

            } else {
                $(this).parent().find('i').addClass('icon-heart6');
                $(this).parent().find('i').removeClass('icon-heart-o');
                $('#followbtn').html('Unfollow');
                $('#followinput').val(1);
            }
            $('#followformid').submit();
        });
    });
</script>
</body>
</html>
<?php
//if($createrid == $_SESSION["email"]) {
//    echo "<script>
//$('#pledge-btn').hide();
//</script>";
//}
if(isset($_SESSION["email"]) && $isFollowing) {
    echo "<script>$(document).ready(function() { $('#followbtn').html('Unfollow'); }); </script>";
}
?>



<?php $mysqli->close();?>