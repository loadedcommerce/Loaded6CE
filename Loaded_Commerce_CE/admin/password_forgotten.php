<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $log_times = (isset($_POST['log_times']) ? $_POST['log_times']+1 : 1);
  if ( $log_times >= 4 ) {
      $_SESSION['password_forgotten'] = true;
    }

// Check if email exists
    $check_admin_query = tep_db_query("select admin_id as check_id, admin_firstname as check_firstname, admin_lastname as check_lastname, admin_email_address as check_email_address from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
      $_GET['login'] = 'fail';
    } else {
      $check_admin = tep_db_fetch_array($check_admin_query);
      $_GET['login'] = 'success';
      $makePassword = tep_create_hard_pass();

      tep_mail($check_admin['check_firstname'] . ' ' . $check_admin['admin_lastname'], $check_admin['check_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $check_admin['check_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $check_admin['check_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      tep_db_query("update " . TABLE_ADMIN . " set admin_password = '" . tep_encrypt_password($makePassword) . "' where admin_id = '" . $check_admin['check_id'] . "'");
      }
    }

    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  if  (isset($_GET['login']) && $_GET['login'] == 'success' ) {
    $success_message = TEXT_FORGOTTEN_SUCCESS;
  } elseif  (isset($_GET['login']) && $_GET['login'] == 'fail' ) {
    $info_message = TEXT_FORGOTTEN_ERROR;
  }
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title><?php echo TITLE; ?> | Login Page</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="noindex" name="index" />
    <meta content="" name="author" />

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link href="assets/css/style.min.css" rel="stylesheet" />
    <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
    <link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->

</head>
<body onload="document.getElementById('email_address').focus()">
   <body class="pace-top">
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <!-- end #page-loader -->

    <div class="login-cover">
        <div class="login-cover-image"><img src="assets/img/login-bg/bg-1.jpg" data-id="login-cover-image" alt="" /></div>
        <div class="login-cover-bg"></div>
    </div>
    <!-- begin #page-container -->
    <div id="page-container" class="fade">
        <!-- begin login -->
        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
            <!-- begin brand -->
            <div class="login-header">
                <div class="brand">
                    <span class="logo-sm"><img src="images/logo-sm.png" border="0"></span> Admin Login
                    <small><a href="http://www.loadedcommerce.com/" target="_blank"><?php echo PROJECT_VERSION; ?></a> | <a href="<?php echo HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG; ?>" target="_blank"><?php echo TEXT_VIEW_CATALOG; ?></a></small>
                </div>
                <div class="icon">
                    <i class="fa fa-sign-in"></i>
                </div>
            </div>
            <!-- end brand -->
            <div class="login-content">
            <?php echo tep_draw_form('login', FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'post', 'class="margin-bottom-0"', 'SSL') . tep_draw_hidden_field("action","process"); ?>
<?php
        if (isset($_SESSION['password_forgotten'])) {
          ?>
          <div class="form-group m-b-20"><?php echo TEXT_FORGOTTEN_FAIL; ?></div>

          <?php
          $success_message = '';
        } elseif (isset($success_message)) {
          $success_message = TEXT_FORGOTTEN_SUCCESS . '<br><br><a href="' . tep_href_link(FILENAME_LOGIN, '' , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
        } else {
          if (isset($info_message)) {
            echo '<tr><td colspan="2"><div class="message">' . $info_message . '</div></td></tr>' . tep_draw_hidden_field('log_times', $log_times);
          } else {
            echo tep_draw_hidden_field('log_times', '0');
          }
        }
        if (!isset($success_message) && !isset($_SESSION['password_forgotten'])){
          ?>

                    <div class="form-group m-b-20">
                        <?php echo TEXT_FORGOTTEN_SUPPORT_MESSAGE; ?>
                    </div>
                   <div class="m-t-20">
                        <h3><?php echo TEXT_PASSWORD_FORGOTTEN_TITLE;?></h3>
                        <p><?php echo TEXT_FORGOTTEN_USER_MESSAGE;?></p>
                    </div>
                    <div class="form-group m-b-20">
                        <input name="email_address" id="email_address" type="text" class="form-control input-lg" placeholder="Email Address" />
                    </div>


                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg"><?php echo IMAGE_SEND;?></button>
                    </div>
                    <?php
        } else {
          ?>
          <div class="m-t-20">
              <?php echo $success_message; ?>
            </div>
          <?php
        }
        ?>



                </form>
            </div>
        </div>
        <!-- end login -->

        <!--ul class="login-bg-list">
            <li class="active"><a href="#" data-click="change-bg"><img src="assets/img/login-bg/bg-1.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="assets/img/login-bg/bg-2.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="assets/img/login-bg/bg-3.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="assets/img/login-bg/bg-4.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="assets/img/login-bg/bg-5.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="assets/img/login-bg/bg-6.jpg" alt="" /></a></li>
        </ul-->

    </div>
    <!-- end page container -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="assets/crossbrowserjs/html5shiv.js"></script>
        <script src="assets/crossbrowserjs/respond.min.js"></script>
        <script src="assets/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="assets/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>

<?php
require('includes/application_bottom.php');
?>
</body>
</html>