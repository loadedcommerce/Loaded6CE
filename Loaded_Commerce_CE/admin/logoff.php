<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
//tep_session_destroy();
unset($_SESSION['login_id']);
unset($_SESSION['login_firstname']);
unset($_SESSION['login_groups_id']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
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
<style>
body {

  width: 100%;
  height: 100%;
   background: #fff url(images/login_header.png) repeat-x;
}
.logo {
  width: 450px;
  margin: 14px auto;
}
</style>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body onload="document.getElementById('email_address').focus()">
<p class="logo"><img src="images/lclogo_login.png"></p>
<table border="0" cellpadding="0" cellspacing="0" width="400" style="margin: 45px auto;">
  <tr>
    <td class="box-top-left">&nbsp;</td>
    <td class="box-top">&nbsp;</td>
    <td class="box-top-right">&nbsp;</td>
  </tr>
  <tr>
    <td class="box-left">&nbsp;</td>
    <td class="box-content">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td style="padding-bottom: 1em;" valign="top"><h2 style="font-size:16px;font-weight:normal;margin: 0;">Admin Login</h2></td>
          <td style="padding-bottom: 1em;" rowspan="2" align="right"></td>
        </tr>
        <tr>
          <td style="padding-bottom: 1em;" valign="top"><?php echo TEXT_MAIN; ?></td>
        </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td align="left"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . TEXT_RELOGIN . '</a>'; ?></td>
          <td align="right"><?php echo '<a href="../index.php">' . TEXT_VIEW_CATALOG . '</a>'; ?></td>
        </tr>
      </table>
    </td>
    <td class="box-right">&nbsp;</td>
  </tr>
  <tr>
    <td class="box-bottom-left">&nbsp;</td>
    <td class="box-bottom">&nbsp;</td>
    <td class="box-bottom-right">&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td style="font-size: 11px; color: #444;" align="left"><a href="http://www.loadedcommerce.com/" target="_blank"><?php echo PROJECT_VERSION;?></a></td>
    <td></td>
  </tr>
</table>
<?php
require('includes/application_bottom.php');
?>
</body>
</html>