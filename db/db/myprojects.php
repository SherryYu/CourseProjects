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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <!--<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>-->
      <!--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
    <![endif]-->
</head>
<body>
<!-- Header -->


<div class="wrapper">
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
                                                        <li class="active"><a href="myprojects.php">My Projects</a></li>
                                                        <li><a href="pledgedprojects.php">My Pledge</a></li>
                                                        <li><a href="likedprojects.php">Liked Projects</a></li>
                                                        <li><a href="profilesetting.php">Profile Settings</a></li>
                                                        <li><a href="addcard.php">Payment Method</a></li>
                                                        <li><a href="createproject.php">Create New</a></li>
                                                    </ul>
                                                    <div class="cs-profile-area">
                                                        <div class="cs-title no-border">
                                                            <?php
                                                            $res=getProjectsByUid($mysqli, $_SESSION["email"]);
                                                            ?>
                                                            <h3 id="count-prj"><?php echo count($res);?> Projects</h3>
                                                            <?php
                                                            if (count($res)>0) {
                                                                echo "<a href='savexls/saveminexls.php?uid=".$_SESSION["email"]."'>Download Projects Information</a>";
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="cs-profile-holder">
                                                            <div class="cs-ads-area">
                                                                <?php
                                                                foreach ($res as $val) {
                                                                    list($pid, $pname, $pdes, $min,$cur,$posttime,$pstat) = $val;
                                                                    $percent = $cur*100/$min >= 100 ? 100 : intval($cur*100/$min);
                                                                    echo "<article>
                                                                    <div class=\"post-main\">
                                                                        <div class=\"detail-area\">
                                                                            <div class=\"ads-title\">
                                                                                <div class=\"text\">
                                                                                    <input id='pidholder' type='hidden' value=''/>
                                                                                    <h3><a href=\"projectdetail.php?id=" . $pid."\">" . $pname. "</a></h3>";
                                                                    echo "<ul class=\"post-details\">
                                                                                        <li><i class=\"cscolor icon-check-square-o\"></i> $".$cur." current</li>";
                                                                    echo "<li><i class=\"cscolor icon-target5\"></i> $". $min. " goal</li>";
                                                                    echo "<li><i class=\"cscolor icon-clock7\"></i>". explode(" ", $posttime)[0] ."</li></ul>";
                                                                    echo "<span class=\"bar\"><span style=\"width:".$percent. "%;\"></span></span></div></div></div></div>";

                                                                    $plres = getPledgesByPid($mysqli, $pid);
                                                                    echo "
                                                                    <div class=\"edit-area\">
                                                                        <a href=\"\" class=\"coll\">". count($plres)." Donations</a>
                                                                        <ul class=\"edit-opts\">
                                                                            <li class=\"active-ad\">". $pstat."</li>";
                                                                    if ($pstat == 'processing'||$pstat == 'funded' ||$pstat == 'closed') {
                                                                        echo "<li class=\"edit-ad\"><a href=\"updateproject.php?id=".$pid."\">Update</a></li>";
                                                                    }
                                                                    if ($pstat == 'funded' ||$pstat == 'closed') {
                                                                        echo "
                                                                            <li class=\"completeli\">
                                                                            <form method=\"post\" action=\"updatetablehelper.php?func=complete\" name=\"completeform\" id=\"completeid\">                                           
                                                                                <input type=\"hidden\" name=\"pid\" value=\"". $pid."\">
                                                                                <i id='completeicon' class='icon-task'></i>
                                                                                <a id=\"completebtn\" href=\"#\">Mark as Completed</a>
                                                                            </form></li>";
                                                                    }
                                                                    echo "</ul>
                                                                        <div id= \"pledgeinfo\"  class=\"cs-profile-holder\">
                                                                        <div class=\"cs-table-holder\">
                                                                            <table>
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th>Sponser</th>
                                                                                    <th>Amount</th>
                                                                                    <th>Date</th>
                                                                                    <th>Status</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>";

                                                                    $j = 1;
                                                                    foreach ($plres as $pl) {
                                                                        list($uid, $username, $pltime, $amount,$plstat) = $pl;
                                                                        echo "<tr><td>" .$j."</td>
                                                                              <td><a href='userprofile.php?uid=".$uid. "'>". $username ."</a></td>
                                                                              <td>". $amount."</td>
                                                                              <td>" . explode(" ",$pltime)[0]."</td>
                                                                              <td>". $plstat ."</td></tr>";
                                                                        $j = $j +1;
                                                                    }
                                                                    echo "</tbody></table></div></div></div></article>";
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
<script src="assets/scripts/jquery-3.2.1.min.js"></script>
<script src="assets/scripts/bootstrap.min.js"></script> 
<script src="assets/scripts/modernizr.js"></script>
<script src="assets/scripts/menu.js"></script>
<script src="assets/scripts/jquery.flexslider-min.js"></script> 
<script src="assets/scripts/functions.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.edit-area').find('.cs-table-holder').hide();
        jQuery('.edit-area').on('click', '.coll', function(e){
            e.preventDefault();
            var target = jQuery(this).parents('.edit-area').find('.cs-table-holder');
            var active = jQuery(this);
            if(active.hasClass('active')){
                active.removeClass('active');
                target.slideUp();
            }else{
                active.addClass('active');
                target.slideDown();
            }
        });
        $('#completebtn').click(function (event){
            event.preventDefault();
            $('#completeid').submit();
        });
    });
</script>
</body>
</html>

<?php $mysqli->close();?>
