<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mysqli = new mysqli("localhost", "root", "123456", "", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
require "helperfunction.inc";
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
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
<!--    <link href="assets/css/bootstrap.min.css" rel="stylesheet">-->
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
    <link href="assets/css/jquery-ui.min.css" rel="stylesheet">
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
    <?php require "header.php" ?>
    <!-- Header -->

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
                                                        <a href="userprofile.php?id=<?php echo $_SESSION["email"];?>"><img src="assets/extra-images/auther1.jpg" alt="#"></a>
                                                    </figure>
                                                    <div class="text">
                                                        <h3><a href="userprofile.php?id=<?php echo $_SESSION["email"];?>"><?php echo $_SESSION["username"];?></a></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="profile-block">
                                                    <ul class="scroll-nav">
                                                        <li><a href="myprojects.php">My Projects</a></li>
                                                        <li><a href="pledgedprojects.php">My Pledge</a></li>
                                                        <li class="active"><a href="likedprojects.php">Liked Projects</a></li>
                                                        <li><a href="profilesetting.php">Profile Settings</a></li>
                                                        <li><a href="addcard.php">Payment Method</a></li>
                                                        <li><a href="createproject.php">Create New</a></li>
                                                    </ul>
                                                    <div class="cs-profile-area">
                                                        <?php $lres = getLikedProjects($mysqli,$_SESSION["email"]); ?>
                                                        <div class="cs-title no-border">
                                                            <h4><?php echo count($lres);?> Likes</h4>
                                                            <?php
                                                            if (count($lres)>0) {
                                                                echo "<a href='savexls/savelikexls.php?uid=".$_SESSION["email"]."'>Download Projects Information</a>";
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="cs-profile-holder">
                                                            <div class="cs-table-holder">
                                                                <table>
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Project Name</th>
                                                                        <th>Creator</th>
                                                                        <th>Current Fund</th>
                                                                        <th>Goal</th>
                                                                        <th>Post Time</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody valign="middle">
                                                                    <?php

                                                                    $i = 1;
                                                                    foreach ($lres as $val) {
                                                                        list($likepid, $pname,$pdes,$creator,$min,$cur,$posttime,$stat) = $val;
                                                                        $uname = getUserName($mysqli, $creator);
                                                                        echo "<tr><td>" . $i . "</td>";
                                                                        echo "<td><a href=\"projectdetail.php?id=" . $likepid . "\">" . $pname . "</a></td>";
                                                                        echo "<td><a href=\"userprofile.php?uid=" . $creator . "\">" . $uname . "</a></td>";
                                                                        echo "<td> $".$cur."</td>";
                                                                        echo "<td> $".$min."</td>";
                                                                        echo "<td>" .$posttime . "</td>";
                                                                        echo "<td>" . $stat . "</td>";
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

    <!--// Footer Widget //-->
    <?php require "footer.php" ?>
    <!--// Footer Widget //-->

</div>


<!-- jQuery (necessary JavaScript) -->

<script src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script src="assets/scripts/bootstrap.min.js"></script>
<script src="assets/scripts/modernizr.js"></script>
<script src="assets/scripts/menu.js"></script>
<script src="assets/scripts/jquery.flexslider-min.js"></script>
<script src="assets/scripts/functions.js"></script>
<script>
    jQuery(document).ready(function(){
    });

</script>

</body>
</html>
