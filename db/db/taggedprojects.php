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
    $tagname = explode("=", $_SERVER["QUERY_STRING"])[1];
    if(isExistTagname($mysqli, $tagname)) {
        $tagres = getTaggedProjects($mysqli, $tagname);
        if(isset($_SESSION["email"])){
            saveTagLog($mysqli, $_SESSION["email"],$tagname);
        }
    } else header("Location:404.php");
} else header("Location:404.php");
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
                                                <div class="profile-block">
                                                    <div class="cs-profile-area">
                                                        <div class="cs-title">
                                                            <h4>PROJECTS WITH TAG <?php echo $tagname;?></h4>
                                                        </div>
                                                        <div class="cs-profile-holder">
                                                            <div class="cs-table-holder">
                                                                <table>
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Project Name</th>
                                                                        <th>Current Fund</th>
                                                                        <th>Goal</th>
                                                                        <th>Post Time</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $i = 1;
                                                                    foreach ($tagres as $val) {
                                                                        list($tagpid, $tagpname, $min,$cur,$posttime,$pstat) = $val;
                                                                        echo "<tr><td>" . $i. "</td>";
                                                                        echo "<td><a href='projectdetail.php?id=".$tagpid."'>".$tagpname ."</a></td>";
                                                                        echo "<td>" . $cur . "</td>";
                                                                        echo "<td>" . $min . "</td>";
                                                                        echo "<td>" . $posttime . "</td>";
                                                                        echo "<td>" . $pstat . "</td></tr>";
                                                                        $i = $i + 1;
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
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

<script>
    $(document).ready(function () {
        $("input[name='month']").change(function () {
            alert($("input[name='month']").val());

        })
    });
</script>
</body>
</html>

<?php $mysqli->close();?>