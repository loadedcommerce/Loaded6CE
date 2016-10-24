<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_UPGRADE_PRODUCT);
$error = (isset($_SESSION['validate_error']) && $_SESSION['validate_error'] == true) ? true : false;
$error_message = ($error == true) ? $_SESSION['validate_error']['error_message'] : '';
$action = (isset($_GET['action']) && ($_GET['action'] != '')) ? $_GET['action'] : ''; 
$_SESSION['force_registration'] = true;
$paction = (isset($_POST['action']) && ($_POST['action'] != '')) ? $_POST['action'] : '';
$serial = (isset($_POST['serial']) && ($_POST['serial'] != '')) ? tep_db_prepare_input($_POST['serial']) : '';
if (($paction == 'validate') || $serial && !$error) {
  // check for blank serial
    if ($serial == '') {
    $error = true;
    $error_message = TEXT_ERROR_BLANK_SERIAL;    
  }
  // validation logic  
  if (!$error) {

	$data = file_get_contents('https://api.loadedcommerce.com/lc6_addon_api/?serial='.$serial);
	if (preg_match('/<status>/', $data)) {
		preg_match_all("'<addondata[^>]*?>(.*?)</addondata>'", $data, $filedata);
		$fp = fopen('../addons/temp/pro.zip', 'w');
		fwrite($fp, base64_decode($filedata[1][0]));
		fclose($fp);

		$command = 'unzip ../addons/temp/pro.zip loaded6-pro-addon-master/addon_pro/* -d ../addons/temp/';
		exec($command);
		$command = 'mkdir ../addons/addon_pro/';
		exec($command);
		$command = 'mv ../addons/temp/loaded6-pro-addon-master/addon_pro/ ../addons/';
		exec($command);
		$command = 'rm -rf ../addons/temp/*';
		exec($command);
		$error = false;
		preg_match_all("'<sucessmessage>(.*?)</sucessmessage>'", $data, $sucessmessage);
		$success_message = $sucessmessage[1][0];   
	}
	else
	{
		preg_match_all("'<error>(.*?)</error>'", $data, $filedata);
		$error = true;
		$info_message = $filedata[1][0];   
	}
/*
    // instantiate the serial class
    require_once(DIR_WS_CLASSES . 'sss_verify.php');
    $sss = new sss_verify;
    $verify_array = $sss->verifySerial($serial);
    print_r($verify_array);
    exit();
    if ($verify_array['verified'] == true) {
      if (isset($_SESSION['new_registration']) && $_SESSION['new_registration'] == true) {
        unset($_SESSION['new_registration']);
        if (isset($_SESSION['force_registration'])) unset($_SESSION['force_registration']);
        $_SESSION['verify_array'] = $verify_array;
        tep_redirect(FILENAME_SSS_REGISTER . '?action=confirm', '', 'SSL');
      } else {
        $_SESSION['continue'] = true;
        tep_redirect(FILENAME_DEFAULT, '', 'SSL');  
      }
    } else {
      $error = true;
      $error_message = $verify_array['error_message'];   
    }    
*/
  }

}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title><?php echo TITLE; ?> | Upgrade Your Product</title>
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
                    <span class="logo-sm"><img src="images/logo-sm.png" border="0"></span> Upgrade Your Product
                    <small><a href="http://www.loadedcommerce.com/" target="_blank"><?php echo PROJECT_VERSION; ?></a> | <a href="<?php echo HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG; ?>" target="_blank"><?php echo TEXT_VIEW_CATALOG; ?></a></small>
                </div>
                <div class="icon">
                    <i class="fa fa-sign-in"></i>
                </div>
            </div>
            <!-- end brand -->
            <div class="login-content">
            <?php echo tep_draw_form('login', FILENAME_UPGRADE_PRODUCT, 'action=process', 'post', 'class="margin-bottom-0"', 'SSL') . tep_draw_hidden_field("action","process"); ?>
<?php
		if (isset($success_message)) {
          $success_message .= '<br><br><a href="' . tep_href_link(FILENAME_UPGRADE_PRODUCT, '' , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
        } else {
          if (isset($info_message)) {
            echo '<tr><td colspan="2"><div class="message">' . $info_message . '</div></td></tr>' . tep_draw_hidden_field('log_times', $log_times);
          } else {
            echo tep_draw_hidden_field('log_times', '0');
          }
        }
        if (!isset($success_message) && !isset($_SESSION['password_forgotten'])){
          ?>

                   <div>
                        <p><?php echo TEXT_ENTER_SERIAL_KEY;?></p>
                    </div>
                    <div class="form-group m-b-20">
                        <input name="serial" id="serial" type="text" class="form-control input-lg" placeholder="Serial Key" />
                    </div>


                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg"><?php echo IMAGE_BUTTON_VALIDATE;?></button>
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