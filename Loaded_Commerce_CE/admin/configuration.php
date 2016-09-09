<?php
    /*
    $Id: configuration.php,v 2.0 2008/05/05 00:36:41 datazen Exp $

    CRE Loaded, Commerical Open Source eCommerce
    http://www.creloaded.com

    Copyright (c) 2008 CRE Loaded
    Copyright (c) 2003 osCommerce

    Released under the GNU General Public License
    */
    Header("Cache-control: private, no-cache");
    Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); # Past date
    Header("Pragma: no-cache");
    require('includes/application_top.php');

    // RCI for global and individual top
    echo $cre_RCI->get('global', 'top', false);
    echo $cre_RCI->get('configuration', 'top', false); 

    // local dir to the template directory where you are uploading the company logo
    $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
    $template = tep_db_fetch_array($template_query);
    $CURR_TEMPLATE = $template['configuration_value'] . '/';
    $upload_fs_dir = DIR_FS_TEMPLATES.$CURR_TEMPLATE.DIR_WS_IMAGES;
    $upload_ws_dir = DIR_WS_TEMPLATES.$CURR_TEMPLATE.DIR_WS_IMAGES;

    $action = (isset($_GET['action']) ? $_GET['action'] : '');

    if (tep_not_null($action)) {
        switch ($action) {
            case 'save':
                $configuration_value = tep_db_prepare_input($_POST['configuration_value']);
                $cID = tep_db_prepare_input($_GET['cID']);
                $error = false;
                $configuration_key = tep_db_prepare_input($_POST['configuration_key']);
                // check if configuration key is admin session lifetime and greater than zero
                if ($configuration_key == 'MYSESSION_LIFETIME') {
                    if ((int)$configuration_value < 60) {
                        $error = true;
                        $messageStack->add_session('search',CONFIG_ADMIN_SESSION_ERROR, 'error');
                        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cID . '&action=edit'));
                    }
                }
                // added for password length validation for pci.
                if ($configuration_key == 'ENTRY_PASSWORD_MIN_LENGTH') {
                    if ((int)$configuration_value < 8) {
                        $error = true;
                        $messageStack->add_session('search', CONFIG_ADMIN_PASSWORD_ERROR, 'error');
                        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cID . '&action=edit'));
                    }
                }
                if ($error == false) {
                    if (is_array($configuration_value)) {
                        $configuration_value_new = '';
                        foreach ($configuration_value as $value) {
                            $configuration_value_new .= $value . ',';
                        }
                        $configuration_value_new = substr($configuration_value_new, 0, strlen($configuration_value_new) - 1);//B# 3836
                        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value_new) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");
                    } else {
                        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");
                    }
                    tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cID));
                }
                break;
        }
    }
    $gID = (isset($_GET['gID'])) ? $_GET['gID'] : 1;
    $cfg_group_query = tep_db_query("select configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$gID . "'");
    $cfg_group = tep_db_fetch_array($cfg_group_query);
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <?php include('html_head.php');?>
    </head>
    <body>
        <!-- ================== BEGIN PAGE LEVEL JS ================== -->
        <link href="assets/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" />   
        <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
        <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />    
        <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />


        <!-- ================== PAGE LEVEL JS ================== -->

        <!-- ================== END PAGE LEVEL JS ================== -->
        <!-- begin #page-loader -->
        <div id="page-loader" class="fade in"><span class="spinner"></span></div>
        <!-- end #page-loader --> 

        <!-- begin #page-container -->
        <div id="page-container" class="fade page-sidebar-fixed page-header-fixed"> 
        <?php include('header.php');?> 

        <?php
            include('includes/column_left.php');
        ?>

        <!-- begin #content -->
        <div id="content" class="content"> 
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li>Create &nbsp; <a title="Create Account" href="create_account.php" class="btn btn-xs btn-header"><i class="fa fa-user"></i><span class="label">+</span></a> <a title="Create Order" href="create_order.php" class="btn btn-xs btn-header"><i class="fa fa-shopping-cart"></i><span class="label">+</span></a></li>
                <li>Search &nbsp; <a title="" data-original-title="" href="javascript:;" class="btn btn-header btn-xs header-popover" id="ProductsPopover">Products</a> <a title="" data-original-title="" href="javascript:;" class="btn btn-header btn-xs header-popover" id="CustomerPopover">Customers</a> <a title="" data-original-title="" href="javascript:;" class="btn btn-header btn-xs header-popover" id="OrdersPopover">Orders</a> <a title="" data-original-title="" href="javascript:;" class="btn btn-header btn-xs header-popover" id="PagesPopover">Pages</a></li>
            </ol>
            <!-- end breadcrumb --> 
            <!-- begin page-header -->
            <h1 class="page-header">Configuration</h1>
            <!-- end page-header --> 
            <!-- begin row -->
            <div class="row"> 
                <!-- begin configuration-left -->
                <div class="col-md-2">
                    <!--h5 class="">&nbsp;</h5 -->
                    <ul class="nav nav-pills nav-stacked nav-inbox">
                        <?php
                            $configuration_groups_query = tep_db_query("select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_CONFIGURATION_GROUP . " where visible = '1' order by sort_order");
                            $active_class = '';
                            while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
                                if($gID == $configuration_groups['cgID']){
                                    $active_class = ' class="active"';
                                    $active_class_link = ' style="background: #00acac !important;"';
                                }
                                echo '<li' . $active_class . '><a' . $active_class_link . ' href="' . tep_href_link(FILENAME_CONFIGURATION,  'gID=' . $configuration_groups['cgID'] . '&selected_box=configuration','NONSSL') . '">' . $configuration_groups['cgTitle'] . '</a></li>';
                                $active_class = '';
                                $active_class_link = '';
                            }
                        ?>
                    </ul>

                </div>
                <!-- end configuration-left  --> 
                <!-- begin col-12 -->
                <div class="col-md-10"> 
                    <!-- h5 class="f-w-600"><?php echo $cfg_group['configuration_group_title']; ?></h5 -->
                    <div class="panel m-b-10"> 
                        <div class="panel-body">     
                        <h4 class="f-s-18 f-w-600 page-header f-s-18" style="color: #242a30;"><?php echo $cfg_group['configuration_group_title']; ?></h4>
                            <div class="no-footer">
                                <div class="row">       
                                    <!-- begin configuration-right -->
                                    <div class="col-md-12">

                                        <form action="" class="form-horizontal">

                                            <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table table-bordered table-hover f-s-13">
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-3 table-title-cell-bg f-s-15">Configuration Title</th>
                                                        <th class="col-md-8 table-title-cell-bg f-s-15">Value</th>
                                                        <th class="col-md-1 text-center table-title-cell-bg f-s-15">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "' order by sort_order");
                                                        while ($configuration = tep_db_fetch_array($configuration_query)) {
                                                            if (tep_not_null($configuration['use_function'])) {
                                                                $use_function = $configuration['use_function'];
                                                                if (preg_match('/->/', $use_function)) {
                                                                    $class_method = explode('->', $use_function);
                                                                    if (!isset(${$class_method[0]}) || !is_object(${$class_method[0]})) {
                                                                        include(DIR_WS_CLASSES . $class_method[0] . '.php');
                                                                        ${$class_method[0]} = new $class_method[0]();
                                                                    }
                                                                    $cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
                                                                } else {
                                                                    $cfgValue = tep_call_function($use_function, $configuration['configuration_value']);
                                                                }
                                                            } else {
                                                                $cfgValue = $configuration['configuration_value'];
                                                            }
                                                            if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $configuration['configuration_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                                                                $cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
                                                                $cfg_extra = tep_db_fetch_array($cfg_extra_query);
                                                                $cInfo_array = array_merge((array)$configuration, (array)$cfg_extra);
                                                                $cInfo = new objectInfo($cInfo_array);
                                                            }
                                                            if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
                                                                if($cInfo->set_function == 'file_upload'){
                                                                    echo '<tr id="defaultSelected" role="row">' . "\n";
                                                                } else {   
                                                                    echo '<tr id="defaultSelected" role="row">' . "\n";
                                                                }
                                                            } else {
                                                                echo '<tr role="row">' . "\n";
                                                            }
                                                        ?>
                                                        <td><span class="f-w-600" style="color:#242a30"><?php echo $configuration['configuration_title']; ?></span>
                                                        </td>
                                                        <td><?php 
                                                            if ($_GET['gID']== '450' && $configuration['configuration_title'] == 'Download Order Statuses') {
                                                                $s1 = tep_db_query("SELECT * FROM `orders_status` WHERE `orders_status_id` IN ( ".htmlspecialchars($cfgValue)." ) and language_id = '".$languages_id."'");
                                                                $s2 = '';
                                                                while($r1 = tep_db_fetch_array($s1)) {
                                                                    $s2 .= $r1['orders_status_name']. ", ";
                                                                }
                                                                echo substr($s2,0,strlen($s2)-2);
                                                            } else {
                                                                echo htmlspecialchars($cfgValue); 
                                                                //echo '<a href="#" id="configuration' . $configuration["configuration_id"] . '" data-type="text" data-pk="1" >' . htmlspecialchars($cfgValue) . '</a>';
                                                            }
                                                        ?></td>
                                                        <td class="text-center" style="padding-top:8px !important; padding-bottom:5px !important;"><a style="text-decoration: none" onclick="editConfiguration('<?php echo $configuration["configuration_id"]; ?>','<?php echo htmlspecialchars($configuration['configuration_title']);?>')" href="#" title="" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                    ?> 
                                                </tbody>  
                                            </table>


                                        </form>
                                    </div>
                                    <!-- end configuration right -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end panel --> 

                </div>
                <!-- end col-12 --> 
            </div>
            <!-- end row --> 
        </div>
        <!-- end #content --> 
        <?php include('footer.php');?> 
        <!-- ================== BEGIN PAGE LEVEL JS ================== -->
        <script src="assets/plugins/emodal/eModal.js"></script> 
        <script>
            function editConfiguration(id, title) {
                var params = {
                    buttons: false,
                    loadingHtml: '<span class="fa fa-circle-o-notch fa-spin fa-3x text-primary"></span><span class="h4">Loading</span>',
                    title: 'Edit - '+title,
                    url: './ajax/configuration/modal_edit_configuration.php?editConfigurationlID='+id,
                };

                return eModal.ajax(params);
            }
            
        </script>

    </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>