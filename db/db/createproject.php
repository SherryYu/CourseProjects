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
<link href="assets/css/ui.multiselect.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/plugin.css" rel="stylesheet">
<link href="assets/css/responsive.css" rel="stylesheet">
<link href="assets/css/menu.css" rel="stylesheet">
<link href="assets/css/color.css" rel="stylesheet">
<link href="assets/css/iconmoon.css" rel="stylesheet">
<link href="assets/css/themetypo.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/sol.css">


<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />



<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
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
															<li><a href="myprojects.php">My Projects</a></li>
															<li><a href="pledgedprojects.php">My Pledge</a></li>
                                                            <li><a href="likedprojects.php">Liked Projects</a></li>
															<li><a href="profilesetting.php">Profile Settings</a></li>
                                                            <li><a href="addcard.php">Payment Method</a></li>
															<li class="active"><a href="createproject.php">Create New</a></li>
														</ul>
													<form id="forms" name="projectform" action="insert.php?func=project" method="post" enctype="multipart/form-data">
														<div class="cs-profile-area">
															<div class="cs-title no-border">
																<h3>Create a new project</h3>
															</div>
															<div class="cs-profile-holder">
																<ul class="cs-element-list has-border">
                                                                    <li>
                                                                        <label>Project Image</label>
                                                                        <div class="fields-area">
                                                                            <div class="field-col col-md-12">
                                                                                <input type="file" accept="image/png,image/jpeg" name="pfile" required="required"/>
                                                                            </div>
                                                                        </div>
                                                                    </li>
																	<li>
																		<label>Project Title</label>
																		<div class="fields-area">
																			<div class="field-col col-md-12">
																				<input type="text" name="pname" pattern="[a-zA-Z0-9 ]{5,40}" required="required">
																			</div>
																		</div>
																	</li>
																	<li>
																		<label>Project Description</label>
																		<div class="fields-area">
																			<div class="field-col col-md-12">
																				<textarea name="pdes" required="required"></textarea>
																			</div>
																		</div>
																	</li>
																	<li class="multiselect-holder">
																		<label>Catergories</label>
																		<div class="fields-area">
																			<div class="field-col col-md-6">
																				<select id="catselect" class="multiselect" name="catalogue" required="required">
                                                                                    <?php
                                                                                    $res = getCatalogue();
                                                                                    foreach ($res as $val) {
                                                                                        echo "<option value='" . $val . "'>" . $val. "</option>";
                                                                                    }
                                                                                    ?>
																				</select>	
																			</div>
																		</div>
																	</li>
                                                                    <li>
                                                                        <label>Tags</label>
                                                                        <div class="fields-area">
                                                                            <div class="field-col col-md-6">
                                                                                <select id="tagselect" name="tags[]" multiple="multiple" required="required">
                                                                                    <?php
                                                                                    $res = getTags();
                                                                                    foreach ($res as $val) {
                                                                                        echo "<option value='" . $val . "'>" . $val. "</option>";
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </li>
																</ul>
																<ul class="cs-element-list has-border">
																	<li>
																		<label>Minimum Funds</label>
																		<div class="fields-area">
																			<div class="field-col col-md-6">
																				<input type="number" name="min" min="1" max="100000000" required="required">
																			</div>
																		</div>
																	</li>
                                                                    <li>
                                                                        <label>Maximum Funds</label>
                                                                        <div class="fields-area">
                                                                            <div class="field-col col-md-6">
                                                                                <input type="number" name="max" min="2" max="100000000" required="required">
                                                                            </div>
                                                                        </div>
                                                                    </li>
																	<li>
																		<label>Campaign End</label>
                                                                        <div class="fields-area">
                                                                            <div class='field-col col-sm-6'>
                                                                                <div class='input-group date' id='datetimepicker1'>
                                                                                    <input type='text' class="form-control" name="end"/>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
																	</li>
                                                                    <li>
                                                                        <label>Planned Completion</label>
                                                                        <div class="fields-area">
                                                                            <div class='field-col col-sm-6'>
                                                                                <div class='input-group date' id='datetimepicker2'>
                                                                                    <input type='text' class="form-control" name="planned"/>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
																</ul>
																<ul class="cs-element-list has-border paypal">
																	<li>
																		<label>Payment Setting</label>
																		<div class="fields-area">
																			<div class="field-col col-md-6">
                                                                                <select id="cardselect" name="creditcard">
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
																	</li>
																</ul>
																<ul class="cs-element-list term-condition-area">
																	<li>
																		<label>Terms &amp;<br> Conditions</label>
																		<div class="fields-area">
																			<div class="field-col col-md-12">
																				<p>Once submit, you can never change these.</p>
                                                                                <p>Be careful.</p>
																				<div class="checkbox-area">
																					<input type="checkbox" id="conditions" required="required">
																					<label for="conditions">Accept <a href="#">terms and conditions</a></label>
																				</div>
																			</div>
																		</div>
																	</li>
																</ul>
																<ul class="cs-element-list cs-submit-form">
																	<li>
																		<div class="fields-area">
																			<div class="field-col col-md-4">
																				<input id="submitbtn" class="csbg-color cs-btn" type="submit" value="Create Project">
																			</div>
																		</div>
																	</li>
																</ul>
															</div>
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
	</main>

    <!--// Footer Widget //-->
    <?php require "footer.php";?>
</div>



<!-- jQuery (necessary JavaScript) -->
<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="assets/scripts/modernizr.js"></script>
<script src="assets/scripts/jquery.nanoscroller.js"></script>
<script src="assets/scripts/ui.multiselect.js"></script>
<script src="assets/scripts/functions.js"></script>
<script type="text/javascript" src="assets/scripts/sol.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
        $('#tagselect').searchableOptionList();

        $('#datetimepicker1').datetimepicker({
            minDate: new Date(),
            format: 'YYYY-MM-DD HH:mm:ss',
        });
        $('#datetimepicker2').datetimepicker({
            minDate: new Date(),
            format: 'YYYY-MM-DD HH:mm:ss',
        });

        $('#cardselect').on('change', function () {
            if ($(this).val() == "addcard") {
                window.location = "addcard.php";
            }
        });

        $('#submitbtn').on('click', function (e) {
            var pname = $("input[name='pname']").val();
            if (!$.trim(pname)) {
                e.preventDefault();
                alert("Please enter valid project name");
            }

            var pdes = $("textarea[name='pdes']").val();
            if (!$.trim(pdes)) {
                e.preventDefault();
                alert("Please enter valid project description");
            }

            var min = parseInt($("input[name='min']").val());
            var max = parseInt($("input[name='max']").val());
            if ( min > max) {
                e.preventDefault();
                alert("Max fund must be greater than min fund");
            }

            var end = $("input[name='end']").val();
            var planned = $("input[name='planned']").val();
            if (end > planned) {
                e.preventDefault();
                alert("Please enter valid time");
            }

            var cardval = $("select[name='creditcard']").val();
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