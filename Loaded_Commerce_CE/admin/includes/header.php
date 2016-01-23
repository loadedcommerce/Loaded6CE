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

    <!-- begin #header -->
    <div id="header" class="header navbar navbar-default navbar-fixed-top">
      <!-- begin container-fluid -->
      <div class="container-fluid">
        <!-- begin mobile sidebar expand / collapse button -->
        <div class="navbar-header">
          <a href="<?php echo tep_href_link(FILENAME_DEFAULT);?>" class="navbar-brand"><span class="logo-sm"><img src="images/logo-sm.jpg" border="0"></span><?php echo PROJECT_VERSION;?></a>
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
          <iframe src="messages.php?s=header" frameborder="0" width="462" height="40" scrolling="No"  allowtransparency="true"></iframe>
          </li>


          <li class="dropdown navbar-user">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
              <!-- img src="assets/img/user-11.jpg" alt="" / -->  
              <span class="hidden-xs"><?php echo $store_admin_name;?></span> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu animated fadeInLeft">
              <li class="arrow"></li>
              <li><a href="<?php echo tep_href_link(ILENAME_ADMIN_ACCOUNT,'','SSL');?>">Edit Profile</a></li>
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