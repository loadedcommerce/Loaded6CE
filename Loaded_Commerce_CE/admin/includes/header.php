<?php
/*
  $Id: header.php,v 1.3.0.0 2008/06/09 23:39:42 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$languages = tep_get_languages();
$languages_array = array();
$languages_selected = DEFAULT_LANGUAGE;
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
  $languages_array[] = array('id' => $languages[$i]['code'],
                             'text' => $languages[$i]['name']);
  if ($languages[$i]['directory'] == $language) {
    $languages_selected = $languages[$i]['code'];
  }
}

$my_account_query = tep_db_query ("select admin_id, admin_firstname, admin_lastname from " . TABLE_ADMIN . " where admin_id= " . $_SESSION['login_id']);
$myAccount = tep_db_fetch_array($my_account_query);
$store_admin_name = $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname'];
// RCI top
echo $cre_RCI->get('header', 'top');
?>
<script type="text/javascript">
/* preload images */
var imgs = ['images/button.png', 'images/button-over.png', 'images/button-active.png', 'images/button-submit.png', 'images/button-submit-over.png', 'images/button-submit-active.png'];
for (var i = 0; i < imgs.length; i++) {
  var img = new Image();
  img.src = imgs[i];
}

</script>
<?php require(DIR_WS_INCLUDES . 'warnings.php'); ?>
    <!-- begin #header -->
    <div id="header" class="header navbar navbar-default navbar-fixed-top" style="min-height:78px">
      <!-- begin container-fluid -->
      <div class="container-fluid">
        <!-- begin mobile sidebar expand / collapse button -->
        <div class="navbar-header">
          <a href="<?php echo tep_href_link(FILENAME_DEFAULT);?>" class="navbar-brand"><span class="logo-sm"><img src="images/logo-sm.png" border="0" style="height:64px"></span></a>
          <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <!-- end mobile sidebar expand / collapse button -->

        <!-- begin header navigation right -->
        <ul class="nav navbar-nav navbar-right">
          <li>
          	<div class="hide-on-mobile"><iframe src="messages.php?s=header" frameborder="0" width="462" height="60" scrolling="No"  allowtransparency="true"></iframe></div>
          </li>

			<li class="dropdown open" >
				<a class="dropdown-toggle f-s-14" data-toggle="dropdown" href="javascript:;" aria-expanded="true">
					<i class="fa fa-bell-o"></i>
					<span class="label"><?php echo $messageStack->size('header');?></span>
				</a>
				<ul class="dropdown-menu media-list pull-right animated fadeInDown" style="width:450px">
					<li class="media">
					<div class="media-body" style="width:450px">
						<!-- warnings //-->
						<?php
							if ($messageStack->size('header') > 0) {
							  echo $messageStack->output('header');
							}
							if (isset($_GET['error_message']) && tep_not_null($_GET['error_message'])) {
							?>
							<table border="0" width="100%" cellspacing="0" cellpadding="2" class="table">
							  <tr class="headerError"> <td class="headerError"><?php echo htmlspecialchars(urldecode($_GET['error_message'])); ?></td> </tr>
							</table>
							<?php
							}

							if (isset($_GET['info_message']) && tep_not_null($_GET['info_message'])) {
							?>
							<table border="0" width="100%" cellspacing="0" cellpadding="2" class="table">
							  <tr class="headerInfo"> <td class="headerInfo"><?php echo htmlspecialchars($_GET['info_message']); ?></td> </tr>
							</table>
							<?php
							}
							?>
						<!-- warning_eof //-->
					  </div>
					</li>
				</ul>
			</li>
	<li class="dropdown navbar-user">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
              <!-- img src="assets/img/user-11.jpg" alt="" / -->
              <span class="hidden-xs"><?php echo $store_admin_name;?></span> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu animated fadeInLeft">
              <li class="arrow"></li>
              <li><a href="<?php echo tep_href_link(FILENAME_ADMIN_ACCOUNT,'','SSL');?>">Edit Profile</a></li>
              <li class="divider"></li>
              <li><a href="<?php echo tep_href_link(FILENAME_LOGOFF,'','SSL');?>">Log Out</a></li>
            </ul>
          </li>
        </ul>
        <!-- end header navigation right -->
      </div>
      <!-- end container-fluid -->
    </div>
    <!-- end #header -->
<?php
  if ($messageStack->size('search') > 0) {
      echo '<div class="content"><div class="panel-body">';
      echo $messageStack->output('search');
      echo '</div></div>';
  }
// RCI bottom
echo $cre_RCI->get('header', 'bottom');
?>