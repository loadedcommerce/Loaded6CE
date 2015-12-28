<?php
/*
  $Id: index.php,v 1.0.0.0 2007/07/24 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require_once('includes/application_top.php');
include_once('includes/functions/rss2html.php');
// RCI top
echo $cre_RCI->get('index', 'top', false); 
// Get admin name 
$my_account_query = tep_db_query ("select admin_id, admin_firstname, admin_lastname from " . TABLE_ADMIN . " where admin_id= " . $_SESSION['login_id']);
$myAccount = tep_db_fetch_array($my_account_query);
$store_admin_name = $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname'];
?>
    <!DOCTYPE html>
    <!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
    <!--[if !IE]><!-->
    <html lang="en">
    <!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>


  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
                                                             <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/style.min.css" rel="stylesheet" />
  <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
  <link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
  <!-- ================== END BASE CSS STYLE ================== -->
  
  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  <link href="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />  
      <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
  <!-- ================== END PAGE LEVEL STYLE ================== -->



<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
</head>
<body>    
<!-- header //-->
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed gradient-enabled">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
      
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
      
    <!-- begin #content -->
    <div id="content" class="content">
      <!-- begin breadcrumb -->
      <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">Home</a></li>
        <li><a href="javascript:;">Tools</a></li>
        <li class="active"><?php echo HEADING_TITLE; ?></li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header"><?php echo HEADING_TITLE; ?></h1>
      <!-- end page-header -->
      

<!-- header_eof //-->
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" style="padding-bottom: 1em;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <?php echo sprintf(TEXT_WELCOME,$store_admin_name); ?>
                </td>
                <td align="right" style="padding-right: 12px;">
                  Version: <?php echo PROJECT_VERSION;?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td valign="top">
            
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td width="50%" valign="top"><?php 
                  // RCI include left admin blocks
                  echo $cre_RCI->get('index', 'blockleft');
                  ?>
                </td>
                <td width="50%" valign="top"><?php 
                  // RCI include right admin blocks
                  echo $cre_RCI->get('index', 'blockright');
                  ?>
                </td>
              </tr>
            </table>
          
          </td>
          <?php /*
          <td width="180" valign="top">
            <?php echo $cre_RCI->get('index', 'rightcolumn'); ?>
            <!-- CRE Forge & Loaded Commerce News -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td class="box-top-left">&nbsp;</td><td class="box-top">&nbsp;</td><td class="box-top-right">&nbsp;</td>
              </tr>
              <tr>
                <td class="box-left">&nbsp;</td><td class="box-content">
                  <div style="font-weight: bold; margin-bottom: .5em; font-size: 12px;">
                  <?php echo  BLOCK_TITLE_CRE_LOADED_ORG?>
                  </div>
                  <a class="adminLink" href="http://www.creloaded.org/index.php?option=com_jmrphpbb&Itemid=56" target="_blank"><?php echo  BLOCK_CONTENT_CRE_ORG_FORUMS;?></a> <br>
                  <a class="adminLink" href="http://www.creloaded.org/index.php?option=com_mtree&Itemid=55" target="_blank"><?php echo  BLOCK_CONTENT_CRE_ORG_EXTENSIONS?></a> <br>
                  
                  <a class="adminLink" href="http://forge.loadedcommerce.com/gf/project/loaded65/tracker/?action=TrackerItemBrowse&tracker_id=23" target="_blank"><?php echo  BLOCK_CONTENT_CRE_FORGE_BUG_TRACKER;?></a><br>
                  
                  <a class="adminLink" href="http://www.loadedcommerce.com/" target="_blank"><?php echo TEXT_PURCHASE_SUPPORT;?></a><br>
                  
                  <a class="adminLink" href="http://www.cresecure.com/from_admin" target="_blank"><?php echo TEXT_CRE_SECURE;?></a><br>
                 
                </td><td class="box-right">&nbsp;</td>
              </tr>
              <tr>
                <td class="box-bottom-left">&nbsp;</td><td class="box-bottom">&nbsp;</td><td class="box-bottom-right">&nbsp;</td>
              </tr>
            </table>
            <?php 
            // RCO override index newsfeed
            if ($cre_RCO->get('index', 'newsfeed') !== true) {  
              ?>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 1em;">
              <tr>
                <td class="box-top-left">&nbsp;</td><td class="box-top">&nbsp;</td><td class="box-top-right">&nbsp;</td>
              </tr>
              <tr>
                <td class="box-left">&nbsp;</td><td class="box-content">
                  <div style="font-weight: bold; margin-bottom: .5em; font-size: 12px;"><?php echo  BLOCK_TITLE_CRE_NEWS?></div>
                  <?php
                  include_once('includes/functions/rss2html.php');
                  parseRDF("http://www.loadedcommerce.com/rss/", 4);
                  ?>
                  <a href="http://www.loadedcommerce.com/articles_new.php" target="_blank">more...</a>
                </td><td class="box-right">&nbsp;</td>
              </tr>
              <tr>
                <td class="box-bottom-left">&nbsp;</td><td class="box-bottom">&nbsp;</td><td class="box-bottom-right">&nbsp;</td>
              </tr>
            </table>
              </div>
              <?php
            }
            // RCO eof
            ?>
          </td>
          */
          ?>
        </tr>
      </table>
      </div>
    </div>
    <!-- end panel -->
    </div>
    <!-- end #content -->
      
      <!-- begin #footer -->
      <div id="footer" class="footer">
        <?php echo FOOTER_TEXT_BODY;?>
      </div>
      <!-- end #footer -->
      
      <!-- begin scroll to top btn -->
      <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top">
        <i class="fa fa-angle-up"></i>
      </a>
      <!-- end scroll to top btn -->
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
    <script src="assets/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <?php
// RCI top
echo $cre_RCI->get('index', 'bottom'); 
?>
    <?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>



</body>
</html>