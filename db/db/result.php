<?php
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
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
<link href="assets/css/menu.css" rel="stylesheet">
<link href="assets/css/responsive.css" rel="stylesheet">
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
	
    <?php require "header.php";?>
	<!-- Header --> 
	<div class="breadcrumb-sec" style="background:url(assets/extra-images/banner.jpg) no-repeat; background-size:100% auto; min-height:157px!important;">
		<div class="absolute-sec">
			<div class="container">
				<div class="cs-table">
					<div class="cs-tablerow">
						<div class="pageinfo page-title-align-left">
							<h1 style="color:#fff !important; text-transform:none;">Search Result</h1>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Main Content -->
	<main id="main-content">
		<div class="main-section">
			<div class="page-section">
				<div class="container">
					<div class="row">
						<div class="section-fullwidth">
							<div class="cs-content-holder">
								<div class="row">
									<div class="col-lg-9">
									<div class="cs-profile-holder">
									<?php
									require "helperfunction.inc";
									$res_num = -1;
									if(!empty($_POST["s"])){
										$res=searchProject($mysqli,$_POST["s"]);
										$res_num = count($res);
									}
									?>
                                        <div class="cs-ads-area">
                                            <?php
                                            if($res_num > 0) {
                                                foreach ($res as $val) {
                                                    list($pid, $pname, $pdes, $min,$max,$cur,$posttime,$stat) = $val;
                                                    $percent = $cur*100/$min >= 100 ? 100 : intval($cur*100/$min);
                                                    echo "<article><div class=\"post-main\"><div class=\"detail-area\"><div class=\"ads-title\"><div class=\"text\"><input id='pidholder' type='hidden' value=''/>
														<h3><a href=\"projectdetail.php?id=" . $pid."\">" . $pname. "</a></h3>";
                                                    echo "<ul class=\"post-details\">
														<li><i class=\"cscolor icon-check\"></i> $".$cur." current</li>";
                                                    echo "<li><i class=\"cscolor icon-target5\"></i> $". $min. " goal</li>";
                                                    echo "<li><i class=\"cscolor icon-clock7\"></i>". explode(" ", $posttime)[0] ."</li></ul>";
                                                    echo "<span class=\"bar\"><span style=\"width:".$percent. "%;\"></span></span></div></div></div></div>";
                                                    $plres = getPledgesByPid($mysqli, $pid);
                                                    echo "</article>";
                                                }
                                            }
                                            ?>

                                        </div></div>
										<?php
										if($res_num == 0){
											echo"
											<section class=\"cs-result suggestion\">
										  <div class=\"row\">
											<div class=\"col-gl-12\">
											  <div class=\"content\">
												<h2>No Pages Were Found Containing \"".$_POST["s"]."\"</h2>
												<h3>Suggestions:</h3>
												<ul>
												  <li>Make sure all words are spelled correctly</li>
												  <li>Wildcard searches (using the asterisk *) are not supported</li>
												  <li>Try more general keywords, especially if you are attempting a name</li>
												</ul>
											  </div>
											</div>
										  </div>
										</section>
											";
										} else {
										    if (isset($_SESSION["email"])) {
										        saveSearchLog($mysqli, $_SESSION["email"], $_POST["s"]);
                                            }
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
	</main>
	<!--// Main Content //-->

    <!--// Footer//-->
    <?php require "footer.php"; ?>

</div>


<!-- jQuery (necessary JavaScript) --> 
<script src="assets/scripts/jquery.js"></script> 
<script src="assets/scripts/bootstrap.min.js"></script> 
<script src="assets/scripts/modernizr.js"></script>
<script src="assets/scripts/menu.js"></script> 
<script src="assets/scripts/jquery.flexslider-min.js"></script> 
<script src="assets/scripts/functions.js"></script>
</body>
</html>

<?php $mysqli->close();?>