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
                                                        <li><a href="profilesetting.php">Profile Settings</a></li>
                                                        <li class="active"><a href="addcard.php">Payment Method</a></li>
                                                        <li><a href="createproject.php">Create New</a></li>
                                                    </ul>
                                                    <form action="insert.php?func=card" name="submitform" method="post">
                                                        <div class="cs-profile-area">
                                                            <div class="cs-title">
                                                                <h4>Saved Payment Method</h4>
                                                            </div>
                                                            <div class="cs-profile-holder">
                                                                <div class="cs-table-holder">
                                                                    <table>
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Name</th>
                                                                            <th>Card Number</th>
                                                                            <th>Expire</th>
                                                                            <th>Type</th>
                                                                            <th>Service</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $res = getUserCard($mysqli, $_SESSION["email"]);
                                                                        $i = 1;
                                                                        foreach ($res as $val) {
                                                                            list($owner, $cardno,$year,$mon, $ctype,$cser) = $val;
                                                                            echo "<tr><td>" . $i. "</td>";
                                                                            echo "<td>".$owner ."</a></td>";
                                                                            echo "<td>" . $cardno . "</td>";
                                                                            echo "<td>" . $year ."/". $mon. "</td>";
                                                                            echo "<td>" . $ctype . "</td>";
                                                                            echo "<td>" . $cser . "</td></tr>";
                                                                            $i = $i + 1;
                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="cs-title">
                                                                <h3>Add Payment Method</h3>
                                                            </div>
                                                            <div class="cs-profile-holder">

                                                                <h4>Payment Method</h4>
                                                                <ul class="cs-element-list has-border">
                                                                    <li>
                                                                        <label>Card Number</label>
                                                                        <div class="fields-area">
                                                                            <div class="field-col col-md-6">
                                                                                <input type="text" name="cardno" pattern="[0-9]{13,16}" title="13-16 numbers only" placeholder="Enter Valid Card Number" required="required">
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Name on card</label>
                                                                        <div class="fields-area">
                                                                            <div class="field-col col-md-6">
                                                                                <input type="text" name="owner" pattern="[A-Za-z A-Za-z]{5,20}" title="Contains 5-20 letters only"  placeholder="Name" required="required">
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Expire</label>
                                                                        <div class="fields-area">
                                                                            <div class="field-col col-md-6">
                                                                                <input type="month" name="month" placeholder="Month" required="required">
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Type</label>
                                                                        <div class="fields-area">
                                                                            <div class="field-col col-md-3">
                                                                                <select  name="type" required="required">
                                                                                    <option>Debit</option>
                                                                                    <option selected>Credit</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="field-col col-md-3">
                                                                                <select name="service" required="required">
                                                                                    <option selected>Visa</option>
                                                                                    <option>Master Card</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>CVV</label>
                                                                        <div class="fields-area">
                                                                            <div class="field-col col-md-6">
                                                                                <input type="text" name="cvv" pattern="[0-9]{3}" placeholder="CVV" required="required">
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
    $('#submitbtn').on('click', function (e) {
        var name = $("input[name='owner']").val();

        if (!$.trim(name)) {
            e.preventDefault();
            alert("Please enter valid name");
        }
    });
</script>
</body>
</html>
<?php $mysqli->close();?>