<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  if ($session_started == false) {
    echo 'session not started';
  }
  $error = false;
  if ( (isset($_POST['action']) && ($_POST['action'] == 'process')) || (isset($_POST['password']) && isset($_POST['email_address'])) ) {
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $password = tep_db_prepare_input($_POST['password']);
    // Check if email exists
    $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
      $_POST['login'] = 'fail';
    } else {
      $check_admin = tep_db_fetch_array($check_admin_query);
      // Check that password is good
      if (!tep_validate_password($password, $check_admin['login_password'])) {
        $_POST['login'] = 'fail';
      } else {
        if (isset($_SESSION['password_forgotten'])) {
          unset($_SESSION['password_forgotten']);
        }
        $login_email_address = $check_admin['login_email_address'];
        $login_logdate = $check_admin['login_logdate'];
        $login_lognum = $check_admin['login_lognum'];
        $login_modified = $check_admin['login_modified'];
        $_SESSION['login_id'] = $check_admin['login_id'];
        $_SESSION['login_groups_id'] = $check_admin['login_groups_id'];
        $_SESSION['login_firstname'] = $check_admin['login_firstname'];
        //$date_now = date('Ymd');
        tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $_SESSION['login_id'] . "'");
        $_SESSION['from_login'] = true;
        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'SSL'));
        }
      }
    }
  }
  $password = (isset($_GET['password'])) ? $_GET['password'] : '';
  $email_address = (isset($_GET['email_address'])) ? $_GET['email_address'] : '';
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
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->
</head>
<body onload="document.getElementById('email_address').focus()">
   <body class="pace-top">
   <?php
    if (isset($_POST['login']) && $_POST['login'] == 'fail') {
        $info_message = TEXT_LOGIN_ERROR;
    }
    if (isset($info_message)) {
        echo '<p style="margin-bottom:-50px;"><br /><br />' . tep_image(DIR_WS_ICONS . 'warning.gif', 'Warning') . '&nbsp;' . $info_message . '</p>';
    }
    ?>
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
                <?php echo tep_draw_form('login', FILENAME_LOGIN, 'class="margin-bottom-0"', 'post', '', 'SSL') . tep_draw_hidden_field("action","process"); ?>
                    <div class="form-group m-b-20">
                        <input name="email_address" id="email_address" type="text" class="form-control input-lg" placeholder="Email Address" />
                    </div>
                    <div class="form-group m-b-20">
                        <input name="password" id="password" type="password" class="form-control input-lg" placeholder="Password" />
                    </div>
                    <div class="checkbox m-b-20">
                            
                    </div>
                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Sign me in</button>
                    </div>
                    <div class="m-t-20">
                          <?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>';?>
                    </div>
                </form>
            </div>
        </div>
        <!-- end login -->
        
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
    <script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="assets/js/login-v2.demo.min.js"></script>
    <script src="assets/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
            LoginV2.init();
        });
    </script>

<?php
require('includes/application_bottom.php');
?>
</body>
</html>