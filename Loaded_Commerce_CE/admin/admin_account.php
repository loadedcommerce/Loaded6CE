<?php
/*
  $Id: admin_account.php,v 1.1.1.1 2004/03/04 23:38:03 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  $current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;
  
  if (isset($_GET['action']) && $_GET['action']) {
    switch ($_GET['action']) {
      case 'check_password':
        $check_pass_query = tep_db_query("select admin_password as confirm_password from " . TABLE_ADMIN . " where admin_id = '" . $_POST['id_info'] . "'");
        $check_pass = tep_db_fetch_array($check_pass_query);
        
        // Check that password is good
        if (!tep_validate_password($_POST['password_confirmation'], $check_pass['confirm_password'])) {
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=check_account&error=password'));
        } else {
          //$confirm = 'confirm_account';
          $_SESSION['confirm_account']= true;
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process'));
        }

        break;    
      case 'save_account':
        // verify password is hardened password
        if (isset($_POST['admin_password']) && $_POST['admin_password'] != null) {
            $admin_password_length = ( ENTRY_PASSWORD_MIN_LENGTH < 8 ) ? 8 : ENTRY_PASSWORD_MIN_LENGTH;
            if(!preg_match('/^(?=^.{' . $admin_password_length . ',}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/', $_POST['admin_password'])){
                tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process&error=password'));    
          }
        }
        $admin_id = tep_db_prepare_input($_POST['id_info']);
        $admin_email_address = tep_db_prepare_input($_POST['admin_email_address']);
        $stored_email[] = 'NONE';
        $hiddenPassword = '-hidden-';
        
        $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . " where admin_id <> " . $admin_id . "");
        while ($check_email = tep_db_fetch_array($check_email_query)) {
          $stored_email[] = $check_email['admin_email_address'];
        }
        
        if (in_array($_POST['admin_email_address'], $stored_email)) {
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process&error=email'));
        } else {
          $sql_data_array = array('admin_firstname' => tep_db_prepare_input($_POST['admin_firstname']),
                                  'admin_lastname' => tep_db_prepare_input($_POST['admin_lastname']),
                                  'admin_email_address' => tep_db_prepare_input($_POST['admin_email_address']),
                                  'admin_password' => tep_encrypt_password(tep_db_prepare_input($_POST['admin_password'])),
                                  'admin_modified' => 'now()');
        
          tep_db_perform(TABLE_ADMIN, $sql_data_array, 'update', 'admin_id = \'' . $admin_id . '\'');

          tep_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'page=' . $_GET['page'] . '&mID=' . $admin_id));
        }
        break;
    }
  }

?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
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
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
  <!-- ================== END PAGE LEVEL STYLE ================== -->
  <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
  <?php require('includes/account_check.js.php'); ?>
</head>
</head>
<body>
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
        <li>Create &nbsp; <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ACCOUNT;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-user"></i><span class="label">+</span></a> <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ORDER;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ORDER,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-shopping-cart"></i><span class="label">+</span></a></li>
        <li>Search &nbsp; <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="ProductsPopover">Products</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="CustomerPopover">Customers</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="OrdersPopover">Orders</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="PagesPopover">Pages</a></li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header"><?php echo HEADING_TITLE; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse">
<!-- body_text //-->    
    <td valign="top" class="page-container">
      <?php if (isset($_GET['action']) && $_GET['action'] == 'edit_process') { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=save_account', 'post', 'enctype="multipart/form-data"', 'SSL'); } elseif (isset($_GET['action']) && $_GET['action'] == 'check_account') { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_password', 'post', 'enctype="multipart/form-data"', 'SSL'); } else { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_account', 'post', 'enctype="multipart/form-data"', 'SSL'); } ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top">
<?php
  $my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . $_SESSION['login_id'] . " and g.admin_groups_id= " . $_SESSION['login_groups_id'] . "");
  $myAccount = tep_db_fetch_array($my_account_query);
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_ACCOUNT; ?>
                </td>
              </tr>
<?php
    if ( (isset($_GET['action']) && $_GET['action'] == 'edit_process') && (isset($_SESSION['confirm_account'])) ) {
?>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FIRSTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_input_field('admin_firstname', $myAccount['admin_firstname']); ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LASTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_input_field('admin_lastname', $myAccount['admin_lastname']); ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php if ($_GET['error'] == 'email') { echo tep_draw_input_field('admin_email_address', $myAccount['admin_email_address']) . ' <nobr>' . TEXT_EMAIL_ERROR . '</nobr>'; } else { echo tep_draw_input_field('admin_email_address', $myAccount['admin_email_address']); } ?></td>
                    </tr>
              <tr class="dataTableRow">  
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>                                                     
                      <td class="dataTableContent"><?php if ($_GET['error'] == 'password') { echo tep_draw_password_field('admin_password', $myAccount['admin_password']) . ' <nobr>' . TEXT_PASSWORD_ERROR . '</nobr>'; } else { echo tep_draw_password_field('admin_password', $myAccount['admin_password']); } ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD_CONFIRM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_password_field('admin_password_confirm'); ?></td>
                    </tr>
<?php
    } else {
      if (isset($_SESSION['confirm_account'])) {
        unset($_SESSION['confirm_account']);
      }
?>                        
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FULLNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_email_address']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo TEXT_INFO_PASSWORD_HIDDEN; ?></td>
                    </tr>
                    <tr class="dataTableRow dataTableRowSelected">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_GROUP; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_groups_name']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_CREATED; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_created']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGNUM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_lognum']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGDATE; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_logdate']; ?></td>
                    </tr>
<?php
  }
?>                       
              </table>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="smallText" valign="top"><?php echo TEXT_INFO_MODIFIED . $myAccount['admin_modified']; ?></td><td align="right"><?php if (isset($_GET['action']) && $_GET['action'] == 'edit_process') { echo '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ';
                if (isset($_SESSION['confirm_account'])) {
                    echo tep_image_submit('button_save.gif', IMAGE_SAVE, 'onClick="validateForm();return document.returnValue"'); } } elseif (isset($_GET['action']) && $_GET['action'] == 'check_account') { echo '&nbsp;'; } else { echo tep_image_submit('button_page_edit.png', IMAGE_EDIT); } ?></td><tr></table></td>
              </tr>              
            </table>
            </td>
<?php
  $heading = array();
  $contents = array();
  $action = (isset($_GET['action']) ? $_GET['action'] : ''); 
  switch ($action) {
    case 'edit_process':
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');
      
      $contents[] = array('text' => TEXT_INFO_INTRO_EDIT_PROCESS . tep_draw_hidden_field('id_info', $myAccount['admin_id']));
      //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_confirm.gif', IMAGE_CONFIRM, 'onClick="validateForm();return document.returnValue"') . '<br>&nbsp');
      break; 
    case 'check_account':
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_CONFIRM_PASSWORD . '</b>');
      
      $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD . tep_draw_hidden_field('id_info', $myAccount['admin_id']));
      if (isset($_GET['error']) && $_GET['error']) {
        $contents[] = array('text' => '&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR);
      }
      $contents[] = array('align' => 'left', 'text' => '&nbsp;' . tep_draw_password_field('password_confirmation'));
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>' . tep_image_submit('button_confirm.gif', IMAGE_CONFIRM));
      break; 
    default:
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');
      
      $contents[] = array('text' => TEXT_INFO_INTRO_DEFAULT);
      //$contents[] = array('align' => 'center', 'text' => tep_image_submit('button_page_edit.png', IMAGE_EDIT) . '<br>&nbsp');
      if ($myAccount['admin_email_address'] == 'admin@localhost') {
        $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST, $myAccount['admin_firstname']) . '<br>&nbsp');
      } elseif (($myAccount['admin_modified'] == '0000-00-00 00:00:00') || ($myAccount['admin_logdate'] <= 1) ) {
        $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST_TIME, $myAccount['admin_firstname']) . '<br>&nbsp');
      }
      
  }
  
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></form></td>  
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
