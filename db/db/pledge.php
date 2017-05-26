<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require "helperfunction.inc";
$mysqli = new mysqli("localhost", "root", "123456", "", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}
if (isset($_SERVER["QUERY_STRING"])) {
    $pid = explode("=", $_SERVER["QUERY_STRING"])[1];
    if (!isExistPid($mysqli, $pid)) {
        header("Location: 404.php");
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
<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/bootstrap-theme.css" rel="stylesheet">
<link href="assets/css/language-selector-remove-able-css.css" rel="stylesheet">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="assets/css/flexslider.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/plugin.css" rel="stylesheet">
<link href="assets/css/responsive.css" rel="stylesheet">
<link href="assets/css/menu.css" rel="stylesheet">
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
	
	<!-- Header -->
    <?php require "header.php";?>

	<!-- Main Content -->
	<main id="main-content">
		<div class="main-section" style="padding:0;">
			<section class="page-section bg-donate">
				<div class="container">
					<div class="row">
						<div class="section-fullwidth">
							<div class="cs-content-holder">
								<div class="row">
									<div class="col-lg-12">
										<div class="donate-area">
											<ul class="nav nav-tabs" role="tablist">
												<li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home" href="#home">PLEDGE MONEY</a></li>
											</ul>
											<div class="tab-content">
												<div id="home" class="tab-pane active fade in" role="tabpanel">
													<div class="donate-holder">
                                                        <?php
                                                        $res = getProjectFundByPid($mysqli, $pid);
                                                        list($minf, $max, $curf) = $res[0];
                                                        $maxp = $max-$curf > 0 ? $max-$curf : 0;
                                                        ?>
														<h3>Enter Amount</h3>
                                                        <form method="post" action="insert.php?func=pledge" name="pledgeform">
                                                            <div class="form-area">
                                                                <div class="input-area">
                                                                    <input type="number" name="amount" placeholder="0" min="1" max="<?php echo $maxp;?>" required="required">
                                                                    <span>$</span>
                                                                </div>
                                                            </div>
                                                            <h3>Payment Method</h3>
                                                            <div class="form-area">
                                                                <div class="input-area">
                                                                    <select id="cardselect" name="cnumber">
                                                                        <option value="-1">--</option>
                                                                        <?php
                                                                        $res = getUserCard($mysqli, $_SESSION["email"]);
                                                                        foreach ($res as $val) {
                                                                            list($owner, $cardno,$year,$mon, $ctype,$cser) = $val;
                                                                            echo "<option value='$cardno'>".$cardno."</option>";
                                                                        }
                                                                        ?>
                                                                        <option value="addcard">Add Payment Method</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="pid" value="<?php echo $pid;?>">
                                                            <div class="cs-holder">
                                                                <input id="submitbtn" type="submit" value="Confirm Payment">
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
			</section>
		</div>
	</main>
	<!--// Main Content //-->


    <?php require "footer.php";?>

	
</div>

<!-- jQuery (necessary JavaScript) --> 
<script src="assets/scripts/jquery.js"></script> 
<script src="assets/scripts/bootstrap.min.js"></script> 
<script src="assets/scripts/modernizr.js"></script>
<script src="assets/scripts/menu.js"></script> 
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="assets/scripts/functions.js"></script>
  <script>
  $(document).ready(function() {
      $('#cardselect').on('change', function () {
          if ($(this).val() == "addcard") {
              window.location = "addcard.php";
          }
      });
      $('#submitbtn').on('click', function (e) {
          var cardval = $("#cardselect").val();
          if (cardval=="addcard" || cardval=="-1") {
              e.preventDefault();
              alert("Please choose your card");
          }
      });
  });
  </script>


</body>
</html>

<?php $mysqli->close();?>