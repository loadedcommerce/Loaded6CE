<!-- begin #sidebar -->
<?php
//Workaround for menu display.
    if('MENU_DHTML' != 'True'){
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = 'True', last_modified = now() where configuration_key = 'MENU_DHTML'");
    }
?>
<div id="sidebar" class="sidebar">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav">
            <li class="nav-profile">
                <!--div class="image">
                <a href="javascript:;"><i class="fa fa-user"></i></a>
                </div -->
                <div class="info"> <a target="_blank" href="<?php echo tep_catalog_href_link();?>" style="text-decoration: none; color: #fff;">
                    <?php echo STORE_NAME ;?>
                    <small>[View Store]</small></a>
                </div>
            </li>
        </ul>
        <!-- end sidebar user -->
        <!-- begin sidebar nav -->
        <ul class="nav">
            <!-- li class="nav-header">Navigation</li -->
            <li>
                <a href="<?php echo tep_href_link(FILENAME_DEFAULT,'','SSL');?>"><i class="fa fa-laptop"></i> <span>Dashboard</span></a>
            </li>
            <?php
                $menu_active = '';
                $box_files_list = array(  
                    array('catalog','catalog.php', BOX_HEADING_CATALOG,'fa-tasks'),
                    array('customers', 'customers.php' , BOX_HEADING_CUSTOMERS,'fa-users'),
                    array('gv_admin', 'gv_admin.php' , BOX_HEADING_GV_ADMIN,'fa-gift'),
                    array('marketing', 'marketing.php', BOX_HEADING_MARKETING,'fa-signal'),
                    array('information', 'information.php', BOX_HEADING_INFORMATION,'fa-info'),
                    array('articles', 'articles.php' , BOX_HEADING_ARTICLES,'fa-book'),
                    array('reports', 'reports.php' , BOX_HEADING_REPORTS,'fa-bar-chart-o'),
                    array('data', 'data.php' , BOX_HEADING_DATA,'fa-database'),
                    array('links', 'links.php' , BOX_HEADING_LINKS,'fa-external-link-square'),
                    array('administrator','administrator.php', BOX_HEADING_ADMINISTRATOR, 'fa-user'),
                    array('configuration', 'configuration.php', BOX_HEADING_CONFIGURATION,'fa-gear'),
                    array('modules', 'modules.php' , BOX_HEADING_MODULES,'fa-cubes'),
                    array('design_controls' , 'design_controls.php' , BOX_HEADING_DESIGN_CONTROLS,'fa-archive'),
                    array('tools','tools.php',BOX_HEADING_TOOLS,'fa-wrench'),
                    array('taxes', 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES,'fa-bank'),
                    array('localization', 'localization.php' , BOX_HEADING_LOCALIZATION,'fa-language')
                );

                if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') {
                    $box_files_list = array_merge($box_files_list, array(array('fdm_library', 'fdm_library.php' , BOX_HEADING_LIBRARY,'fa-file-text')));
                }
                if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') {
                    $box_files_list = array_merge($box_files_list, array(array('ticket', 'ticket.php', BOX_HEADING_TICKET,'fa-ticket')));
                }
                if (defined('MODULE_ADDONS_CTM_STATUS') && MODULE_ADDONS_CTM_STATUS == 'True') {
                    $box_files_list = array_merge($box_files_list, array(array('testimonials', 'testimonials.php', BOX_HEADING_TESTIMONIALS,'fa-comments')));
                }

                //      echo '<!-- ' . $_SESSION['selected_box'] . ' -->';
                foreach($box_files_list as $item_menu) {
                    if (tep_admin_check_boxes($item_menu[1]) == true) {
                        if ($_SESSION['selected_box'] == $item_menu[0]){
                            $menu_active = ' active'; 
                        } else {
                            $menu_active = '';
                        }
                        echo '<li class="has-sub' . $menu_active . '">
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