<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
                                                        <li class="active"><a href="profilesetting.php">Profile Settings</a></li>
                                                        <li><a href="addcard.php">Payment Method</a></li>
                                                        <li><a href="createproject.php">Create New</a></li>
                                                    </ul>
													<form id="submitform" action="insert.php?func=user" name="submitform" method="post">
														<div class="cs-profile-area">
															<div class="cs-title">
																<h3>Profile Settings</h3>
															</div>
															<div class="cs-profile-holder">
																<h4>About Me</h4>
																<ul class="cs-element-list has-border">
																	<li>
																		<label>Username</label>
																		<div class="fields-area">
																			<div class="field-col col-md-6">
																				<input type="text" name="username" pattern="[A-Za-z A-Za-z]{5,20}" title="Contains 5-20 letters or numbers" required="required" value="<?php echo $_SESSION["username"];?>">
																			</div>
																		</div>
																	</li>
																	<li>
																		<label>Description</label>
																		<div class="fields-area">
																			<div class="field-col col-md-12">
																				<textarea name="des" required="required"></textarea>
																			</div>
																		</div>
																	</li>
																</ul>
																<h4>Contact Information</h4>
																<ul class="cs-element-list has-border">
																	<li>
																		<label>Email Address</label>
																		<div class="fields-area">
																			<div class="field-col col-md-6">
																				<input type="email" value="<?php echo $_SESSION["email"];?>" readonly>
																			</div>
																		</div>
																	</li>
																	<li>
																		<label>Complete Address</label>
																		<div class="fields-area">
																			<div class="field-col col-md-12">
																				<input type="text" name="addr" pattern="[A-Za-z0-9, A-Za-z0-9,]{5,50}" required="required">
																			</div>
																		</div>
																	</li>
																</ul>
																<ul class="cs-element-list cs-submit-form">
																	<li>
																		<div class="fields-area">
																			<div class="field-col col-md-3">
																				<input id="submitbtn" class="csbg-color cs-btn" type="submit" value="Submit Changes">
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

<script>
    $(document).ready(function () {
       $('#submitbtn').on('click', function (e) {
           var string = $("input[name='addr']").val();
           if (!$.trim(string)) {
               e.preventDefault();
               alert("Please enter valid address");
           }
       });
    });
</script>
</body>
</html>