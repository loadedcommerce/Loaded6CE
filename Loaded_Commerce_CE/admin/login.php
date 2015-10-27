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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>
<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
    //alert('You are running a local copy of jQuery!');
    document.write(unescape("%3Cscript src='includes/javascript/jquery-1.6.2.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<style type="text/css">
body {

  width: 100%;
  height: 100%;
   background: #fff url(images/login_header.png) repeat-x;
}
a {
  color: #15c;
  text-decoration: none;
}
a:active {
  color: #d14836;
}
a:hover {
  text-decoration: underline;
}
h1, h2, h3, h4, h5, h6 {
  color: #222;
  font-size: 1.54em;
  font-weight: normal;
  line-height: 24px;
  margin: 0 0 .46em;
}
p {
  line-height: 17px;
  margin: 0 0 1em;
}
ol, ul {
  list-style: none;
  line-height: 17px;
  margin: 0 0 1em;
}
li {
  margin: 0 0 .5em;
}


.announce-bar {
  position: absolute;
  bottom: 35px;
  height: 33px;
  z-index: 2;
  width: 100%;
  background: #f9edbe;
  border-top: 1px solid #efe1ac;
  border-bottom: 1px solid #efe1ac;
  overflow: hidden;
}
.announce-bar .message {
  font-size: .85em;
  line-height: 33px;
  margin: 0;
}
.announce-bar a {
  margin: 0 0 0 1em;
}
.clearfix:after {
  visibility: hidden;
  display: block;
  font-size: 0;
  content: '.';
  clear: both;
  height: 0;
}
* html .clearfix {
  zoom: 1;
}
*:first-child+html .clearfix {
  zoom: 1;
}
input[type=email],
input[type=password],
input[type=text],
input[type=url] {
  display: inline-block;
  height: 29px;
  line-height: 29px;
  margin: 0;
  padding-left: 8px;
  background: #fff;
  border: 1px solid #d9d9d9;
  border-top: 1px solid #c0c0c0;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-border-radius: 1px;
  -moz-border-radius: 1px;
  border-radius: 1px;
}
input[type=email]:hover,
input[type=password]:hover,
input[type=text]:hover,
input[type=url]:hover {
  border: 1px solid #b9b9b9;
  border-top: 1px solid #a0a0a0;
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
}
input[type=email]:focus,
input[type=password]:focus,
input[type=text]:focus,
input[type=url]:focus {
  outline: none;
  border: 1px solid #4d90fe;
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
}
input[type=email][disabled=disabled],
input[type=password][disabled=disabled],
input[type=text][disabled=disabled],
input[type=url][disabled=disabled] {
  border: 1px solid #e5e5e5;
  background: #f5f5f5;
}
input[type=email][disabled=disabled]:hover,
input[type=password][disabled=disabled]:hover,
input[type=text][disabled=disabled]:hover,
input[type=url][disabled=disabled]:hover {
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
  box-shadow: none;
}
input[type=checkbox],
input[type=radio] {
  -webkit-appearance: none;
  appearance: none;
  width: 13px;
  height: 13px;
  margin: 0;
  cursor: pointer;
  vertical-align: bottom;
  background: #fff;
  border: 1px solid #dcdcdc;
  -webkit-border-radius: 1px;
  -moz-border-radius: 1px;
  border-radius: 1px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  position: relative;
  width: 22px;
  height: 22px;
  border: 0;
}
input[type=checkbox]:active,
input[type=radio]:active {
  border-color: #c6c6c6;
  background: #ebebeb;
}
input[type=checkbox]:hover {
  border-color: #c6c6c6;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.1);
  -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.1);
  box-shadow: inset 0 1px 1px rgba(0,0,0,0.1);
}
input[type=radio] {
  -webkit-border-radius: 1em;
  -moz-border-radius: 1em;
  border-radius: 1em;
  width: 15px;
  height: 15px;
}
input[type=checkbox]:checked,
input[type=radio]:checked {
  background: #fff;
}
input[type=radio]:checked::after {
  content: '';
  display: block;
  position: relative;
  top: 3px;
  left: 3px;
  width: 7px;
  height: 7px;
  background: #666;
  -webkit-border-radius: 1em;
  -moz-border-radius: 1em;
  border-radius: 1em;
}
input[type=checkbox]:checked::after {
  display: block;
  position: absolute;
  top: -6px;
  left: -5px;
}
input[type=checkbox]:focus {
  outline: none;
  border-color:#4d90fe;
}
.log-in-wrapper {
  width: 450px;
  margin: 14px auto;
}

.log-in {
  width: 450px;
  margin-top: 65px;
}
.log-in-box{
  margin: 12px 0 0;
  padding: 12px 25px 15px;
  background-color: #f1f1f1;
  border: 1px solid #e1e1e1;
}

.log-in-box h2 {
  font-size: 16px;
  line-height: 16px;
  height: 16px;
  margin: 0 0 1.2em;
  position: relative;
}
.log-in-box h2 strong {
  display: inline-block;
  position: absolute;
  right: 0;
  top: 1px;
  height: 19px;
  width: 52px;
}
.log-in-box label {
  display: block;
  margin: 0 0 1.5em;
}
.log-in-box input[type=text],
.log-in-box input[type=password] {
  width: 100%;
  height: 32px;
  line-height: 32px;
  font-size: 15px;
}
.log-in-box .email-label,
.log-in-box .passwd-label {
  font-weight: bold;
  margin: 0 0 .5em;
  display: block;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}
.log-in-box .reauth {
  display: inline-block;
  font-size: 15px;
  height: 29px;
  line-height: 29px;
  margin: 0;
}
.log-in-box label.remember {
  display: inline-block;
  position: relative;
  top: -1px;
}
.log-in-box .remember-label {
  font-weight: normal;
  color: #666;
  line-height: 17px;
  position: relative;
  top: 2px;
  padding: 0 0 0 .4em;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}
.log-in-box .g-button img {
  opacity: 1;
}
.log-in-box input[type=submit] {
  margin: 0 1.5em 1.2em 0;
  height: 32px;
  font-size: 13px;
}
.log-in-box ul {
  margin: 0;
}

</style>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body onload="document.getElementById('email_address').focus()">

  <div class="log-in-wrapper">
  <img src="images/lclogo_login.png">
   <?php
            if (isset($_POST['login']) && $_POST['login'] == 'fail') {
               $info_message = TEXT_LOGIN_ERROR;
            }
            if (isset($info_message)) {
              ?>
              <p style="margin-bottom:-50px;"><br /><br /><?php echo tep_image(DIR_WS_ICONS . 'warning.gif', 'Warning') . '&nbsp;' . $info_message; ?></p>
              <?php
            }
            echo tep_draw_form('login', FILENAME_LOGIN, '', 'post', '', 'SSL') . tep_draw_hidden_field("action","process"); 
            ?>
  <div class="log-in">
<div class="log-in-box">
  <h2>Admin Login</h2>
<label>
  <strong class="email-label"><?php echo ENTRY_EMAIL_ADDRESS; ?></strong>
  <input type="text" name="email_address" id="email_address" value="">
</label>
<label>
  <strong class="passwd-label"><?php echo ENTRY_PASSWORD; ?></strong>
  <input type="password" name="password" id="password">
</label>
            <?php
                  echo '<input type="submit" name="button" id="button" class="cssButtonSubmit" value="Login" />';
             ?>

  </form>
  <p>      <?php
                  echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>';
                  ?></p>
</div>
</div>
<p style="font-size:11px; color:#444; width:50%; float:left;">&nbsp;<a href="http://www.loadedcommerce.com/" target="_blank"><?php echo PROJECT_VERSION; ?></a></p>
<p style="font-size:11px; color:#444; width:50%; float:right; text-align:right;"> <a href="<?php echo HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG; ?>" target="_blank"><?php echo TEXT_VIEW_CATALOG; ?></a>&nbsp;</p>
</div>

<?php
require('includes/application_bottom.php');
?>
</body>
</html>