<?php
/*
  $Id: nag_index_top.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if ((preg_match('/login.php/i', basename($_SERVER['HTTP_REFERER']))) 
     && (isset($_SESSION['is_std']) && $_SESSION['is_std'] == true) 
     && (isset($_SESSION['from_login']) && $_SESSION['from_login'] == true)) {
  tep_redirect(FILENAME_POPUP_GET_LOADED . '?page=login', '', 'SSL');
}
?>