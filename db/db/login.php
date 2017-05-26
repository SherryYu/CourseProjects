<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <meta charset="UTF-8">
    <title>Fund Me - Login</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/other.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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

    <script>
        function signUp() {
            $("a[href='#signup']").addClass("active");
            $("a[href='#signin']").removeClass("active");
            $(".navs-slider").attr("data-active-index", "0");
            $(".view-signin").css("display", "none");
            $(".view-signup").css("display", "block");
        }

        function reloadCheck() {
            var url = window.location.href.split("?")[1];
            console.log('Debug Objects: ' + url );
            if (url == "#signup") {
                signUp();
            }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("a[href='#signin']").click(function(){
                $(".navs-slider").attr("data-active-index", "1");
                $("a[href='#signin']").addClass("active");
                $("a[href='#signup']").removeClass("active");
                $(".view-signin").css("display", "block");
                $(".view-signup").css("display", "none");
            });
            $("a[href='#signup']").click(function(){
                $("a[href='#signup']").addClass("active");
                $("a[href='#signin']").removeClass("active");
                $(".navs-slider").attr("data-active-index", "0");
                $(".view-signin").css("display", "none");
                $(".view-signup").css("display", "block");
            });
            $("input[name='email']").click(function(){
                if ($("#email-label").value != "") {
                    $("#email-label").hide();
                }
            });
            $("input[name='password']").click(function(){
                if ($("#pwd-label").value != "") {
                    $("#pwd-label").hide();
                }
            });
        });
    </script>
</head>

<body onload="reloadCheck()">
<div class="wrapper">
    <?php require "header.php"?>
<main id="main-content">
    <div class="index-main">
            <div class="index-main-body">
                <div class="sign-flow">
                    <div class="index-tab-navs">
                        <div class="navs-slider" data-active-index="1">
                            <a href="#signup" class="">Sign Up</a>
                            <a href="#signin" class="active">Log In</a>
                            <span class="navs-slider-bar"></span>
                        </div>
                    </div>
                    <div class="view view-signin" style="display: block;">
                        <form method="POST"  action="userverify.php">
                            <div class="group-inputs">
                                <div class="account input-wrapper">
                                    <input type="email" name="email" aria-label="email" placeholder="email" required="" value="
                                    <?php if (isset($_SESSION["email"]))
                                        echo $_SESSION["email"];
                                    else if(isset($_COOKIE["user"]))
                                        echo $_COOKIE["user"];?>">
                                    <label id="email-label" class="error is-visible">
                                        <?php
                                        if (isset($_SESSION["no_email_msg"]))
                                            echo $_SESSION["no_email_msg"];
                                        unset($_SESSION["no_email_msg"]);
                                        ?>
                                    </label>
                                </div>
                                <div class="verification input-wrapper">
                                    <input type="password" name="password" aria-label="password" placeholder="password" required="" value="<?php if(isset($_COOKIE["password"])) echo $_COOKIE["password"];?>">
                                    <label id="pwd-label" class="error is-visible">
                                        <?php
                                        if (isset($_SESSION["wrong_pwd_msg"]))
                                            echo $_SESSION["wrong_pwd_msg"];
                                        unset($_SESSION["wrong_pwd_msg"]);
                                        ?>
                                    </label>
                                </div>
                            </div>
                            <div class="button-wrapper command">
                                <button class="sign-button submit" type="submit">Log In</button>
                            </div>
                        </form>
                    </div>
                    <div class="view view-signup selected" data-za-module="SignUpForm" style="display: none;">
                        <form class="zu-side-login-box" action="register.php" id="sign-form-1" autocomplete="off" method="POST">
                            <input type="password" hidden="">
                            <div class="group-inputs">
                                <div class="name input-wrapper">
                                    <input required="" type="text" name="firstname" pattern="[A-Za-z]{2,15}" title="Contains 2-15 letters only" aria-label="name" placeholder="First Name">
                                </div>
                                <div class="name input-wrapper">
                                    <input required="" type="text" name="lastname" pattern="[A-Za-z]{2,15}" title="Contains 2-15 letters only" aria-label="name" placeholder="Last Name">
                                </div>
                                <div class="email input-wrapper">
                                    <input required="" type="email" class="email" name="email" pattern="[A-Za-z0-9_]+@[A-Za-z0-9]+\.[a-z]{2,4}$" aria-label="email" placeholder="Email">
                                </div>
                                <div class="input-wrapper">
                                    <input required="" type="password" name="password" pattern="\w{6,18}" placeholder="Password" autocomplete="off">
                                    <label id="pwd-label" class="error is-visible">
                                        <?php
                                        if (isset($_SESSION["wrong_create"]))
                                            echo $_SESSION["wrong_create"];
                                        unset($_SESSION["wrong_create"]);
                                        ?>
                                    </label>
                                </div>

                            </div>
                            <div class="button-wrapper command">
                                <button class="sign-button submit" type="submit">Sign Up</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</main>
    <?php require "footer.php"?>
</div>
<div id="particles">
    <canvas class="particles-js-canvas-el" style="width: 100%; height: 100%;"></canvas>
</div>
<script src="js/particles.js"> </script>
<script src="js/app.js"> </script>



<script src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script src="assets/scripts/bootstrap.min.js"></script>
<script src="assets/scripts/modernizr.js"></script>
<script src="assets/scripts/menu.js"></script>
<script src="assets/scripts/jquery.flexslider-min.js"></script>
<script src="assets/scripts/functions.js"></script>
</body>

</html>