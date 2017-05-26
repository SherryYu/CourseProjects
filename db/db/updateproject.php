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
    } else {
        if (getCreatorIdByPid($mysqli, $pid) != $_SESSION["email"]) {
            header("Location: noauthorization.php");
        }
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
<!--        <link href="assets/css/bootstrap.min.css" rel="stylesheet">-->
<!--        <link href="assets/css/bootstrap-theme.css" rel="stylesheet">-->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet"/>
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

        <!--rich editor-->
        <!-- Include external CSS. -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css" rel="stylesheet" type="text/css" />

        <!-- Include Editor style. -->
        <link href="assets/css/froala_editor.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/froala_editor.pkgd.css" rel="stylesheet" type="text/css" />



        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

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
                                                                <h4>UPDATE PROJECT</h4>
                                                            </div>
                                                            <?php
                                                            $details = getProjectDetail($mysqli, $pid);
                                                            list($pname, $createrid, $creater, $pdes, $min,$max,$cur,$posttime,$stat) = $details[0];
                                                            ?>
                                                            <div class="cs-profile-holder">
                                                                <a href="projectdetail.php?pid=<?php echo $pid;?>"><h3><?php echo $pname;?></h3></a>
                                                                <div class="posttime"><span><?php echo $posttime;?></span></div>
                                                                <form action="insert.php?func=detail" method="post" name="detailform">
                                                                    <ul class="cs-element-list has-border">
                                                                        <li>
                                                                            <label class="editlabel">Title</label>
                                                                            <div class="fields-area">
                                                                                <div class="field-col col-md-6">
                                                                                    <input type="text" name="title" pattern="[A-Za-z0-9 A-Za-z0-9]{3,100}" title="3-100 letter, number and space only" required="required">
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <label class="editlabel">Content</label>
                                                                            <div class="fields-area">
                                                                                <div id="preview" class="fr-view" style="display: none;"></div>
                                                                                <div id="editdiv" class="edit field-col col-md-12">
                                                                                    <textarea id="myeditor" class="editor" name="content" required="required"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <input type="hidden" name="pid" value="<?php echo $pid;?>"/>
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
                </div>
            </div>
        </main>
        <!--// Main Content //-->


    <!--// Footer Widget //-->
    <?php require "footer.php";?>

    </div>

    <!-- jQuery (necessary JavaScript) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="assets/scripts/bootstrap.min.js"></script>
    <script src="assets/scripts/modernizr.js"></script>
    <script src="assets/scripts/menu.js"></script>
    <script src="assets/scripts/jquery.flexslider-min.js"></script>
    <script src="assets/scripts/functions.js"></script>
    <!-- Include external JS libs. -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>

    <!-- Include Editor JS files. -->
    <script type="text/javascript" src="assets/scripts/froala_editor.pkgd.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#myeditor').froalaEditor({
                heightMin: 300
            });
            $('#myeditor').froalaEditor().on('froalaEditor.contentChanged', function (e, editor) {
                $('#preview').html(editor.html.get());
            });

            $('#submitbtn').on('click', function (e) {
                var title = $("input[name='title']").val();
                var content = $("#preview").html();
                if (!$.trim(title) || !$.trim(content)) {
                    e.preventDefault();
                    alert("Please enter valid project title and content");
                }
            })

        });
    </script>
    </body>
    </html>

<?php $mysqli->close();?>