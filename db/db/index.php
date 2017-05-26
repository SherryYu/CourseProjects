<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mysqli = new mysqli("localhost", "root", "123456", "", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
require "helperfunction.inc";

$pres = getSomeProjects($mysqli, 1);
if (count($pres)>0) {
    list($bannerpid, $bannerpname, $bannerpdes) = $pres[0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fund Me - Home Page</title>

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
	<div class="cs-banner">
		<div class="flexslider">
			<ul class="slides">
				<li>
					<figure>
						<img src="assets/extra-images/2.jpg" alt="#">
						<figcaption>
							<div class="text">
								<h2>Explore projects, anywhere &amp; everywhere! </h2>
								<div class="spreator5"></div>
								<span>Your idea matters. Come and join us now.</span>
								<a href="createproject.php" class="explore-btn cs-bgcolor"> Create Project</a>
							</div>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<img src="assets/extra-images/1.png" alt="#">
						<figcaption>
							<div class="text">
								<h2><?php echo htmlspecialchars($bannerpname);?> </h2>
								<div class="spreator5"></div>
								<span><?php echo htmlspecialchars($bannerpdes);?></span>
								<a href="projectdetail.php?pid=<?php echo $bannerpid;?>" class="explore-btn cs-bgcolor"> View Project</a>
							</div>
						</figcaption>
					</figure>
				</li>
			</ul>
		</div>
	</div>
	<!-- Main Content -->
	<main id="main-content">
		<div class="main-section">
			<div class="page-section">
				<div class="container">
					<div class="row">
						<div class="section-fullwidth col-lg-12">
							<div class="cs-content-holder">
								<div class="row">
									<div class="page-content col-lg-9">
										<div class="listing_grid">

                                                <?php
                                                if (isset($_SESSION["email"])) {
                                                    $nothing = true;
                                                    $viewedpids = getViewedPidByUid($mysqli, $_SESSION["email"],6);
                                                    $count = 1;
                                                    if (count($viewedpids) > 0) {
                                                        $nothing = false;
                                                        echo "<div class=\"row\"><h3 class='titleh3'>RECENTLY VIEWED PROJECTS</h3>";
                                                        foreach ($viewedpids as $pidval) {
                                                            if ($count > 6) break;
                                                            list($pname, $uid, $username, $pdes, $min,$max,$cur,$posttime,$stat) = getProjectDetail($mysqli,$pidval)[0];
                                                            $percent = $cur*100/$min >= 100 ? 100 : intval($cur*100/$min);
                                                            echo "<article class=\"col-lg-6 col-md-6 col-sm-6\"><div class=\"directory-section\"><div class=\"cs_thumbsection\"></div><div class=\"content_info\">";
                                                            echo "<div class=\"title\"><h3><a href=\"projectdetail.php?pid=$pidval\">$pname</a></h3>";
                                                            echo "<span class=\"addr\">".$stat."</span> </div>";
                                                            echo "<div class=\"dr_info\">
																<ul><li> <i class=\"cscolor icon-target5\"></i> $" .$min ." goal </li>
																	<li> <i class=\"cscolor icon-clock7\"></i>".explode(" ",$posttime)[0]. "</li></ul>";
                                                            echo "<span class=\"bar\"><span style=\"width:$percent%;\"></span></span>
																<div class=\"detail\"> <span class=\"fund\">$percent% Funded</span> </div>
															</div></div></div></article>";
                                                            $count++;

                                                        }
                                                        echo "</div>";
                                                    }

                                                    $keyword = getSearchedWordByUid($mysqli, $_SESSION["email"]);
                                                    if ($keyword != "") {
                                                        $res = getSearchedProject($mysqli, $keyword);
                                                        $searchedpids = array_diff($res, $viewedpids);
                                                        $count = 1;
                                                        if (count($searchedpids) > 0) {
                                                            $nothing = false;
                                                            echo "<div class=\"row\"><h3 class='titleh3'>RECENTLY SEARCHED RESULTS</h3>";
                                                            foreach ($searchedpids as $sval) {
                                                                if ($count > 6) break;
                                                                list($pname, $uid, $username, $pdes, $min,$max,$cur,$posttime,$stat) = getProjectDetail($mysqli,$sval)[0];
                                                                $percent = $cur*100/$min >= 100 ? 100 : intval($cur*100/$min);
                                                                echo "<article class=\"col-lg-6 col-md-6 col-sm-6\"><div class=\"directory-section\"><div class=\"cs_thumbsection\"></div><div class=\"content_info\">";
                                                                echo "<div class=\"title\"><h3><a href=\"projectdetail.php?pid=$sval\">$pname</a></h3>";
                                                                echo "<span class=\"addr\">".$stat."</span> </div>";
                                                                echo "<div class=\"dr_info\">
																<ul><li> <i class=\"cscolor icon-target5\"></i> $" .$min ." goal </li>
																	<li> <i class=\"cscolor icon-clock7\"></i>".explode(" ",$posttime)[0]. "</li></ul>";
                                                                echo "<span class=\"bar\"><span style=\"width:$percent%;\"></span></span>
																<div class=\"detail\"> <span class=\"fund\">$percent% Funded</span> </div>
															</div></div></div></article>";
                                                                $count++;

                                                            }
                                                            echo "</div>";
                                                        }
                                                    }

                                                    $viewedtags = getViewedTagsByUid($mysqli, $_SESSION["email"]);
                                                    $count = 1;
                                                    if (count($viewedtags) > 0) {
                                                        echo "<div class=\"row\"><h3 class='titleh3'>RECENTLY VIEWED TAGS</h3>";
                                                        foreach ($viewedtags as $tagval) {
                                                            if ($count > 8) break;
                                                            echo "<article class=\"col-lg-3 col-md-3 col-sm-3\">";
                                                            echo "<div class=\"tags\"><i class=\"icon-tag7 cs-bgcolor\"></i><ul>";
                                                            echo "<li><a href=\"taggedprojects.php?tag=".$tagval."\">".$tagval."</a></li></ul></div></article>";
                                                            $count++;
                                                        }
                                                        echo "</div>";
                                                    }

                                                }
                                                if (!isset($_SESSION["email"]) || $nothing) {
                                                    $projects = getSomeProjects($mysqli, 6);
                                                    echo "<div class=\"row\"><h3 class='titleh3'>RECOMMENDATIONS FOR YOU</h3>";
                                                    foreach ($projects as $p) {
                                                        list($pid, $pname, $pdes, $min,$cur,$posttime,$pstat) = $p;
                                                        $percent = $cur*100/$min >= 100 ? 100 : intval($cur*100/$min);
                                                        echo "<article class=\"col-lg-6 col-md-6 col-sm-6\"><div class=\"directory-section\"><div class=\"cs_thumbsection\"></div><div class=\"content_info\">";
                                                        echo "<div class=\"title\"><h3><a href=\"projectdetail.php?pid=$pid\">$pname</a></h3>";
                                                        echo "<span class=\"addr\">".$pstat."</span> </div>";
                                                        echo "<div class=\"dr_info\">
																<ul><li> <i class=\"cscolor icon-target5\"></i> $" .$min ." goal </li>
																	<li> <i class=\"cscolor icon-clock7\"></i>".explode(" ",$posttime)[0]. "</li></ul>";
                                                        echo "<span class=\"bar\"><span style=\"width:$percent%;\"></span></span>
																<div class=\"detail\"> <span class=\"fund\">$percent% Funded</span> </div>
															</div></div></div></article>";

                                                    }
                                                    echo "</div>";
                                                }
                                                ?>
										</div>
									</div>
									<aside class="page-sidebar col-lg-3">
										<div class="widget cs_directory_categories">
											<div class="widget-section-title">
												<h4><i class="icon-globe4"></i>15 Diverse Categories</h4>
											</div>
											<ul class="menu">
												<li><a href="cataloguedprojects.php?cat=art"><i class="icon-user9 cscolor"></i>Art</a> <span><?php echo getCatCount($mysqli, 'Art')?></span></li>
												<li><a href="cataloguedprojects.php?cat=comic"><i class="icon-heart11 cscolor"></i>Comics</a> <span><?php echo getCatCount($mysqli, 'Comics')?></span></li>
												<li><a href="cataloguedprojects.php?cat=craft"><i class="icon-brush2 cscolor"></i>Craft</a> <span><?php echo getCatCount($mysqli, 'Craft')?></span></li>
												<li><a href="cataloguedprojects.php?cat=design"><i class="icon-key7 cscolor"></i>Design</a> <span><?php echo getCatCount($mysqli, 'Design')?></span></li>
												<li><a href="cataloguedprojects.php?cat=fashion"><i class="icon-sun4 cscolor"></i>Fashion</a> <span><?php echo getCatCount($mysqli, 'Fashion')?></span></li>
												<li><a href="cataloguedprojects.php?cat=movie"><i class="icon-light-bulb cscolor"></i>Movie</a><span><?php echo getCatCount($mysqli, 'Movie')?></span></li>
												<li><a href="cataloguedprojects.php?cat=food"><i class="icon-clipboard6 cscolor"></i>Food</a> <span><?php echo getCatCount($mysqli, 'Food')?></span></li>
												<li><a href="cataloguedprojects.php?cat=game"><i class="icon-archive2 cscolor"></i>Game</a> <span><?php echo getCatCount($mysqli, 'Game')?></span></li>
												<li><a href="cataloguedprojects.php?cat=music"><i class="icon-lock6 cscolor"></i>Music</a> <span><?php echo getCatCount($mysqli, 'Music')?></span></li>
												<li><a href="cataloguedprojects.php?cat=phptpgraphy"><i class="icon-upload10 cscolor"></i>Photography</a> <span><?php echo getCatCount($mysqli, 'Photography')?></span></li>
												<li><a href="cataloguedprojects.php?cat=technology"><i class="icon-sound4 cscolor"></i>Technology</a> <span><?php echo getCatCount($mysqli, 'Technology')?></span></li>
											</ul>
										</div>
									</aside>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
	</main>

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
<script >
	jQuery(window).load(function(){
		jQuery('.flexslider').flexslider({
			animation: "slide",
			controlNav: false,
			prevText:"<em class='icon-arrow-left10'></em>",
			nextText:"<em class='icon-arrow-right10'></em>",
			start: function(slider){
				jQuery('body').removeClass('loading');
			}
		});
	});
</script>
</body>
</html>