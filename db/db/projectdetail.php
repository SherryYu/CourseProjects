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
    $pid = explode("=", $_SERVER["QUERY_STRING"])[1];
    if (!isExistPid($mysqli, $pid)) {
        header("Location: 404.php");
    }
}


$details = getProjectDetail($mysqli, $pid);
list($pname, $createrid, $creater, $pdes, $min,$max,$cur,$posttime,$stat,$filename) = $details[0];
$percent = $cur*100/$min >= 100 ? 100 : intval($cur*100/$min);

$catalogue = getCatalogueByPid($mysqli, $pid);
$tags = getTagsByPid($mysqli,$pid);

$pledges = getPledgesByPid($mysqli,$pid);
if(isset($_SESSION["email"])) {
    $ifLike = getIfLikeByPid($mysqli, $_SESSION["email"], $pid);
    if ($_SESSION["email"] != getCreatorIdByPid($mysqli, $pid)) {
        saveViewProjectLog($mysqli, $_SESSION["email"],$pid);
    }
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
<link href="assets/css/sumoselect.css" rel="stylesheet">
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
	
	<?php require "header.php"; ?>
	<!-- Main Content -->
	<main id="main-content">
		<div class="main-section">
			<section class="page-section contribute-sec" style="background:#f8f8f8;">
				<div class="container">
					<div class="row">
						<div class="section-fullwidth col-lg-12">
							<div class="cs-content-holder">
								<div class="row">
									<div class="post-detail">
										<div class="main-heading col-lg-12">
											<h1><?php echo $pname;?></h1>
										</div>
										<div class="cause-detail">
											<div class="col-lg-9">
												<figure><img src="<?php echo $filename;?>" alt=""></figure>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6">
												<div class="price-area">
													<span class="price-raised"><span>$ <?php echo $cur;?></span> raised</span>
													<span class="price-goal">$ <?php echo $min;?> goal</span>
												</div>
												<span class="bar"><span style="width:<?php echo $percent;?>%;"></span></span>
												<span class="fund"><?php echo $percent;?>% Funded</span>
                                                <?php
                                                if (isset($_SESSION["email"]) && ($_SESSION["email"])!=$createrid) {
                                                    if($stat == "processing" || $stat=="funded") {
                                                        echo "<a id=\"pledge-btn\" href=\"pledge.php?pid=". $pid."\" class=\"cs-btn\"><span>Contribute now</span></a>";
                                                    }
                                                }
                                                ?>
												<div class="detail-list">
													<ul>
														<li><i class="icon-clock7 cscolor"></i><?php echo explode(" ", $posttime)[0];?></li>
                                                        <li><i class="icon-tasks"></i><?php echo $stat; ?></li>
                                                        <?php
                                                            if ($stat=="completed") {
                                                                echo "<li><i class=\"icon-star\"></i>";
                                                                $stars = getAvgStar($mysqli, $pid);
                                                                echo $stars==0 ? "No rating" : "Average rates: " . round($stars,1);
                                                                echo "</li>";
                                                            }?>
														<li><i class="icon-list7 cscolor"></i><a href="<?php echo "cataloguedprojects.php?id=".$catalogue?>"><?php echo $catalogue;?></a></li>
													</ul>
													<div class="user-info">
														<figure><img alt="" src="assets/extra-images/img-testi.png" draggable="false"></figure>
														<span class="cs-author">
															<span>Created By</span>
                                                            <a href="userprofile.php?uid=<?php echo $createrid;?>"><?php echo $creater;?></a>
														</span>
													</div>
												</div>
                                                <?php
                                                if (isset($_SESSION["email"])) {
                                                    echo "<ul class=\"share-opts\" id=\"likearea\"><li><iframe style=\"display: none;\" name=\"likeframe\"></iframe><form method=\"post\" action=\"updatetablehelper.php?func=like\" name=\"likeform\" id=\"likeformid\" target=\"likeframe\">";
                                                    echo "<input type=\"hidden\" id=\"likeinput\" name=\"like\" value=\"" . $ifLike . "\">";
                                                    echo "<input type=\"hidden\" name=\"pid\" value=\"" . $pid."\">";
                                                    echo "<input type=\"hidden\" name=\"uid\" value=\"". $_SESSION["email"] . "\">";
                                                    if ($ifLike) {
                                                        echo "<i id=\"likeicon\" class=\"icon-heart6\"></i>";
                                                    } else {
                                                        echo "<i id=\"likeicon\" class=\"icon-heart-o\"></i>";
                                                    }
                                                    echo "<a id=\"likebtn\" href=\"#\">Like</a></form></li></ul>";
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
			</section>
			<section class="page-section tab-sec">
				<div class="container">
					<div class="row">
						<div class="section-fullwidth col-lg-12">
							<div class="cs-content-holder">
								<div class="row">
									<div class="page-content col-lg-9">
										<div class="post-sec">
											<div class="row">
												<div class="col-lg-12">
												<div class="detail-tabs">
				
												  <!-- Nav tabs -->
												  <ul class="nav nav-tabs" role="tablist">
													<li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab">Description</a></li>
													<li role="presentation"><a href="#pledge" aria-controls="pledge" role="tab" data-toggle="tab">Pledges</a></li>
													<li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comments</a></li>
													<li role="presentation"><a href="#time-line" aria-controls="settings" role="tab" data-toggle="tab">Updates</a></li>
												  </ul>
												
												  <!-- Tab panes -->
												  <div class="tab-content">
													  <div role="tabpanel" class="tab-pane active" id="description">
														<div class="post-block summary-sec rich_editor_text">
															<h2>Project Description</h2>
															<p><?php echo htmlspecialchars($pdes);?></p>
															<div class="tags">
																<i class="icon-tag7 cs-bgcolor"></i>
																<ul>
                                                                    <?php
                                                                    foreach ($tags as $tag) {
                                                                        echo "<li><a href=\"taggedprojects.php?tag=".$tag. "\">".$tag . "</a></li>";
                                                                    }
                                                                    ?>
																</ul>
															</div>
														</div>
													</div>
													  <div role="tabpanel" class="tab-pane" id="pledge">
                                                          <div class="post-block contribution-sec">
															<h2>Pledges</h2>
															<div class="contributor-list">
                                                                <?php
                                                                $i = 1;
                                                                foreach ($pledges as $pledge) {
                                                                    list($puid, $username, $pltime, $amount,$plstat) = $pledge;
                                                                    echo "<article class=\"col-md-12\">";
                                                                    echo "<span class=\"number\">".$i."</span>";
                                                                    echo "<div class=\"text\">
																		<h4><a href=\"userprofile.php?uid=". $puid. "\">". $username."</a></h4>
																		<span>".$pltime."</span></div>";
                                                                    echo "<span class=\"amount\">$".$amount."</span>";
                                                                    echo "<span class=\"amount\">".$plstat ."</span></article>";
                                                                    $i = $i + 1;
                                                                }
                                                                ?>
															</div>
														</div>
													  </div>
                                                      <?php $comments = getCommentsByPid($mysqli,$pid);?>
                                                      <div role="tabpanel" class="tab-pane" id="comments">
                                                          <div id="comment">
                                                              <div class="cs-section-title"><h2>Comments</h2></div>
                                                              <ul id="commentlist">
                                                                  <?php
                                                                  foreach ($comments as $comment) {
                                                                      list($commentuid, $commentusername, $commenttime, $commentdes) = $comment;
                                                                      echo "<li id=\"li-comment-1\">
                                                                      <div id=\"comment-1\" class=\"thumblist\"><ul><li><div class=\"text-box\">";
                                                                      echo "<h4><a href=\"userprofile.php?uid=".$commentuid."\">" . $commentusername. "</a></h4>";
                                                                      echo "<time>" . $commenttime . "</time>";
                                                                      echo "<p>" . htmlspecialchars($commentdes) ."</p>";
                                                                      echo "</div></li></ul></div></li>";
                                                                  }
                                                                  ?>
                                                              </ul>
                                                          </div>
                                                          <?php
                                                          if (isset($_SESSION["email"])) {
                                                              echo "<div class=\"comment-respond\" id=\"respond\"><h2>Leave us a comment</h2><iframe style=\"display: none\" name=\"formtarget\" src=\"insert.php?func=comment\"></iframe><form name=\"commentform\" id=\"cform\" class=\"comment-form contact-form\" action=\"insert.php?func=comment\" method=\"post\" target=\"formtarget\">";
                                                              echo "<input type=\"hidden\" name=\"pid\" value=\"" . $pid. "\">";
                                                              echo "<input id=\"ctimeholder\" type=\"hidden\" name=\"ctime\" value=\"\">";
                                                              echo "<p class=\"comment-form-comment fullwidt\"><label><i class=\"icon-comments-o\"></i><textarea id=\"commentarea\" name=\"newcomment\" placeholder=\"Enter Message\" required=\"required\"></textarea></label></p>
                                                                  <p class=\"form-submit\"><input id=\"submit-input\" type=\"text\" readonly value=\"Submit Comment\" name=\"submitbtn\" class=\"form-style csbg-color\"></p>";
                                                              echo "</form></div>";
                                                          }
                                                          ?>

                                                      </div>
                                                      <?php $details = getDetailsByPid($mysqli,$pid);?>
													  <div role="tabpanel" class="tab-pane" id="time-line">
														<div class="cs-timeline">
															<div class="cs-section-title">
																<h2><?php echo count($details);?> Project Updates</h2>
															</div>
															<ul>
																<li>
                                                                    <?php
                                                                    foreach ($details as $detail) {
                                                                        list($dpid, $dtitle, $dtime, $dcontent) = $detail;
                                                                        echo "<article><time>" . $dtime . "</time>";
                                                                        echo "<h4>" . $dtitle . "</h4>";
                                                                        echo "<div id=\"contentarea\" class=\"text-box\"><div class=\"info-area\">" .$dcontent. "</div></div>";
                                                                        echo "<a id=\"toggledetail\" href=\"#\" class=\"coll\">Details</a></article>";
                                                                    }
                                                                    ?>
																</li>
															</ul>
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
			</section>
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

<script src="assets/scripts/functions.js"></script>

<script>
    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
$('.detail-tabs').tab('show');
$('.tab-pane').addClass('fade in');


jQuery(document).ready(function(){
    $("div[id='contentarea']").each(function () {
        $(this).hide();
    });

    $('#submit-input').click(function (e) {
        var string = $('#commentarea').val();
        if (!$.trim(string)) {
            e.preventDefault();
            alert("Please enter valid comment");
        } else {
            if($('#commentarea').val() != "") {
                var date = new Date();
                var Y = date.getFullYear() + '-';
                var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
                var D = (date.getDate() < 10 ? '0'+(date.getDate()) : date.getDate()) + ' ';
                var h = (date.getHours() < 10 ? '0'+(date.getHours()) : date.getHours()) + ':';
                var m = (date.getMinutes() < 10 ? '0'+(date.getMinutes()) : date.getMinutes()) + ':';
                var s = (date.getSeconds() < 10 ? '0'+(date.getSeconds()) : date.getSeconds());
                var ctime = Y+M+D+h+m+s;
                $('#ctimeholder').val(ctime);
                var commenthtml = "<li id=\"li-comment-1\"> <div id=\"comment-1\" class=\"thumblist\"><ul><li><div class=\"text-box\"><h4><a href=\"userprofile.php?uid=<?php echo $_SESSION["email"];?>\"><?php echo $_SESSION["username"]?></a></h4><time>"
                commenthtml += $('#ctimeholder').val();
                commenthtml += "</time><p>";
                commenthtml += escapeHtml($('#commentarea').val());
                commenthtml += "</p></div></li></ul></div></li>";
                commenthtml += $('#commentlist').html();
                $('#commentlist').html(commenthtml);
                $('#cform').submit();
            }
            $('#commentarea').val("");
        }
    });
    $("a[id='toggledetail']").each(function (i, el) {
        $(this).click(function(event){
            event.preventDefault();
            $(this).parent().find('#contentarea').toggle();

        });
    });

    $('#likebtn').click(function (event){
        event.preventDefault();
        if ($('#likeinput').val()==1) {
            $(this).parent().find('i').removeClass('icon-heart6');
            $(this).parent().find('i').addClass('icon-heart-o');
            $('#likeinput').val(0);

        } else {
            $(this).parent().find('i').addClass('icon-heart6');
            $(this).parent().find('i').removeClass('icon-heart-o');
            $('#likeinput').val(1);
        }
        $('#likeformid').submit();
    });
});
</script>

<?php
//if($createrid == $_SESSION["email"]) {
//    echo "<script>
//$('#pledge-btn').hide();
//</script>";
//}
if (isset($_SESSION["email"])) {
    if($stat == "completed" || $stat=="failed" || $stat=="closed" || $createrid == $_SESSION["email"]) {
        echo "<script>$(document).ready(function() { $('#pledge-btn').hide();});</script>";
    }
}
else {
    echo "<script>$(document).ready(function() { $('#pledge-btn').hide();});</script>";
}

?>
<?php $mysqli->close();?>

</body>
</html>
