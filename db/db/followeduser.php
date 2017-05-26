<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require "helperfunction.inc";
$mysqli = new mysqli("localhost", "root", "123456", "", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
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
                                                <?php
                                                $followres = getFollowedUsers($mysqli, $_SESSION["email"]);
                                                if (count($followres)==0) echo "<h2>You haven't follow anyone</h2>";
                                                foreach ($followres as $fval) {
                                                    list($fid, $fname) = $fval;
                                                    $prs = getProjectsByUid($mysqli, $fid);
                                                    $commentsres = getFollowedComments($mysqli, $fid);
                                                    $likesres = getFollowedLikes($mysqli, $fid);
                                                    $rateres = getFollowedRates($mysqli, $fid);
                                                    if (count($prs)==0 && count($commentsres)==0 &&count($likesres)==0 && count($rateres)==0) {
                                                        continue;
                                                    }

                                                    echo "<div class=\"col-lg-12\"><div class=\"profile-block\"><div class=\"cs-profile-area\">";
                                                    echo "<div class=\"cs-title\"><i class='icon-user2'></i><a class='followedname' href='userprofile.php?uid=$fid'>$fname</a></div>";

                                                    if (count($prs)!=0) {
                                                        echo "<div class=\"cs-title\"><h4>PROJECTS</h4></div>";
                                                        echo "<div class=\"cs-profile-holder\"><div class=\"cs-table-holder\"><table><thead><tr><th>#</th><th>Project Name</th><th>Current Fund</th><th>Goal</th><th>Post Time</th><th>Status</th></tr></thead><tbody>";
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
                                                        echo "</tbody></table></div></div>";
                                                    }

                                                    if (count($commentsres)!=0) {
                                                        echo "<div class=\"cs-title\"><h4>COMMENTS</h4></div>";
                                                        echo "<div class=\"cs-profile-holder\"><div class=\"cs-table-holder\"><table><thead><tr><th>#</th><th>Project Name</th><th>Post Time</th><th>Content</th></tr></thead><tbody>";

                                                        $i = 1;
                                                        foreach ($commentsres as $commentval) {
                                                            list($compid, $compname, $comtime, $comdes) = $commentval;
                                                            echo "<tr><td>" . $i . "</td>";
                                                            echo "<td><a href='projectdetail.php?id=" . $compid . "'>" . $compname . "</a></td>";
                                                            echo "<td>" . $comtime . "</td>";
                                                            echo "<td>" . $comdes . "</td></tr>";
                                                            $i = $i + 1;
                                                        }
                                                        echo "</tbody></table></div></div>";
                                                    }

                                                    if (count($likesres)!=0) {
                                                        echo "<div class=\"cs-title\"><h4>Likes</h4></div>";
                                                        echo "<div class=\"cs-profile-holder\"><div class=\"cs-table-holder\"><table><thead></thead><tbody>";

                                                        foreach ($likesres as $likeval) {
                                                            list($likepid, $likepname) = $likeval;
                                                            echo "<tr><td style='text-align: left; width: 100%;'><b><a href='userprofile.php?uid=$fid'>" . $fname . "</a></b> liked ";
                                                            echo "<b><a href='projectdetail.php?id=" . $likepid . "'>" . $likepname . "</a></b></td></tr>";
                                                        }
                                                        echo "</tbody></table></div></div>";
                                                    }

                                                    if (count($rateres)!=0) {
                                                        echo "<div class=\"cs-title\"><h4>Rates</h4></div>";
                                                        echo "<div class=\"cs-profile-holder\"><div class=\"cs-table-holder\"><table><thead></thead><tbody>";

                                                        foreach ($rateres as $rateval) {
                                                            list($ratepid, $ratepname, $ratestar) = $rateval;
                                                            echo "<tr><td style='text-align: left; width: 100%;'><b><a href='userprofile.php?uid=$fid'>" . $fname . "</a></b> rated ";
                                                            echo "<b><a href='projectdetail.php?id=" . $ratepid . "'>" . $ratepname . "</a></b>";
                                                            echo " with " . $ratestar . " stars</td></tr>";
                                                        }
                                                        echo "</tbody></table></div></div>";
                                                    }
                                                    echo "</div></div></div>";

                                                }
                                                ?>
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

<?php $mysqli->close();?>