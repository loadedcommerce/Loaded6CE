<?php
/*
  $Id: column_left.php,v 2.1 2015/06/11 00:18:17 Kiran Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $SideBoxFiles = array(array('catalog', 'catalog.php', BOX_HEADING_CATALOG,'fa-tasks'),
                        array('configuration', 'configuration.php', BOX_HEADING_CONFIGURATION,'fa-gear'),
                        array('customers', 'customers.php' , BOX_HEADING_CUSTOMERS,'fa-users'),
                        array('marketing', 'marketing.php', BOX_HEADING_MARKETING,'fa-signal'),
                        array('gv_admin', 'gv_admin.php' , BOX_HEADING_GV_ADMIN,'fa-gift'),
                        array('reports', 'reports.php' , BOX_HEADING_REPORTS,'fa-bar-chart-o'),
                        array('data', 'data.php' , BOX_HEADING_DATA,'fa-database'),
                        array('information', 'information.php', BOX_HEADING_INFORMATION,'fa-info'),
                        array('articles', 'articles.php' , BOX_HEADING_ARTICLES,'fa-book'),
                        array('design_controls' , 'design_controls.php' , BOX_HEADING_DESIGN_CONTROLS,'fa-archive'),
                        array('links', 'links.php' , BOX_HEADING_LINKS,'fa-external-link-square'),
                        array('modules', 'modules.php' , BOX_HEADING_MODULES,'fa-cubes'),
                        array('taxes', 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES,'fa-bank'),
                        array('localization', 'localization.php' , BOX_HEADING_LOCALIZATION,'fa-language'),
                        array('tools','tools.php',BOX_HEADING_TOOLS,'fa-wrench')
                        );
  ?>
    <!-- begin #sidebar -->
    <div id="sidebar" class="sidebar">
      <!-- begin sidebar scrollbar -->
      <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav">
          <li class="nav-profile">
            <div class="image">
              <a href="javascript:;"><img src="assets/img/user-11.jpg" alt="" /></a>
            </div>
            <div class="info">
              <?php echo $store_admin_name;?>
            </div>
          </li>
        </ul>
        <!-- end sidebar user -->
        <!-- begin sidebar nav -->
        <ul class="nav">
          <li class="nav-header">Navigation</li>
          <li>
            <a href="<?php echo tep_href_link(FILENAME_INDEX,'','SSL');?>"><i class="fa fa-laptop"></i> <span>Dashboard</span></a>
          </l>
          <?php
          foreach($SideBoxFiles as $item_menu) {
              if (tep_admin_check_boxes($item_menu[1]) == true) {
                  echo '<li class="has-sub">
                        <a href="javascript:;">
                            <i class="fa '.$item_menu[3]. '"></i> 
                            <b class="caret pull-right"></b>
                            <span>'.$item_menu[2]. '</span> 
                        </a>';
                  require(DIR_WS_BOXES . $item_menu[1]);
              }
          }
          ?>
          <!-- begin sidebar minify button -->
          <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
          <!-- end sidebar minify button -->
        </ul>
        <!-- end sidebar nav -->
      </div>
      <!-- end sidebar scrollbar -->
    </div>
    <div class="sidebar-bg"></div>
    <!-- end #sidebar -->