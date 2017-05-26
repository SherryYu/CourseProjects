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
                                                        <li class="active"><a href="pledgedprojects.php">My Pledge</a></li>
                                                        <li><a href="likedprojects.php">Liked Projects</a></li>
                                                        <li><a href="profilesetting.php">Profile Settings</a></li>
                                                        <li><a href="addcard.php">Payment Method</a></li>
                                                        <li><a href="createproject.php">Create New</a></li>
                                                    </ul>
                                                    <div class="cs-profile-area">
                                                        <div class="cs-title no-border">
                                                            <?php
                                                            $res = getPledgesByUid($mysqli,$_SESSION["email"]);
                                                            echo "<h4>".count($res)." Pledges</h4>";
                                                            ?>

                                                            <?php
                                                            if (count($res)>0) {
                                                                echo "<a href='savexls/savepledgexls.php?uid=".$_SESSION["email"]."'>Download Pledges Information</a>";
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
                                                                        <th>Date</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>
                                                                        <th>My Rate</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody valign="middle">
                                                                    <?php


                                                                    $i = 1;
                                                                    foreach ($res as $val) {
                                                                        list($pid, $pname,$creator, $pltime, $amount, $stat,$pstatus) = $val;
                                                                        $uname = getUserName($mysqli, $creator);
                                                                        $ratestar = getRateByUidPid($mysqli, $_SESSION["email"],$pid);
                                                                        echo "<tr><td>" . $i . "</td>";
                                                                        echo "<td><a href=\"projectdetail.php?id=" . $pid . "\">" . $pname . "</a></td>";
                                                                        echo "<input id=\"pidinput\" type=\"hidden\" value=\"".$pid."\"/>";
                                                                        echo "<input id=\"pnameinput\" type=\"hidden\" value=\"".$pname."\"/>";
                                                                        echo "<td><a href=\"userprofile.php?uid=" . $creator . "\">" . $uname . "</a></td>";
                                                                        echo "<td>" . explode(" ", $pltime)[0] . "</td>";
                                                                        echo "<td>" . $amount . "</td>";
                                                                        echo "<td>" . $stat . "</td>";
                                                                        if ($pstatus == 'completed') {
                                                                            if ($ratestar>0) {
                                                                                echo "<td style='min-width: 100px'>";
                                                                                for ($x = 0; $x < $ratestar; $x++) {
                                                                                    echo "<i class=\"icon-star2\"></i>";
                                                                                }
                                                                                echo "</td>";
                                                                            } else {
                                                                                echo "<td><button type=\"button\" class=\"ratebtn btn btn-secondary\">Rate</button></td>";
                                                                            }
                                                                        }
                                                                        echo "</tr>";
                                                                        $i = $i + 1;
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <form id="rateformid" action="insert.php?func=rate" method="post" name="rateform">
                                                                <div id="rate-area">
                                                                    <input id="pidval" type="hidden" name="pid"/>
                                                                    <input id="starval" type="hidden" name="star" value="1"/>
                                                                    <div class="ratepname"></div>
                                                                    <div class="rating" data-rate-value="1"></div>
                                                                    <button id="submitrate" type="button" class="btn btn-secondary">submit</button>
                                                                </div>
                                                            </form>

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
<script src="assets/scripts/rater.min.js"></script>
<script type="text/javascript">

</script>
<script>
    jQuery(document).ready(function(){
        $("#rate-area").hide();
        $(".rating").rate({
            min_value: 1,
            max_value: 5,
            step_size: 1,

        });
        $(".rating").on("change", function(ev, data){
            $('#starval').val(data.to);
        });
        $('.ratebtn').on('click',function () {
            var id = $(this).closest('tr').children('#pidinput').val();
            var name = $(this).closest('tr').children('#pnameinput').val();
            $('.ratepname').html(name);
            $('#pidval').val(id);
            $("#rate-area").slideDown();
        });
        $('#submitrate').on('click',function () {
            $('#rateformid').submit();
        })
    });

</script>

</body>
</html>
