<?php
    /*
    $Id: products_attributes.php,v 1.3 2004/03/16 22:36:34 ccwjr Exp $

    osCommerce, Open Source E-Commerce Solutions
    http://www.oscommerce.com

    Copyright (c) 2002 osCommerce

    Released under the GNU General Public License
    */
    require('includes/application_top.php');
    $languages = tep_get_languages();

    $page_info = '';

    if (isset($_GET['action'])) {
        switch($_GET['action']) {
            case 'add_product_options':

                $options_type = $_POST['options_type'];
                $options_length= (int)$_POST['options_length'];
                $products_options_sort_order = (int)$_POST['products_options_sort_order'];
                tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_sort_order, options_type, options_length) values ('" . $_POST['products_options_id'] . "', '" . $products_options_sort_order . "', '" . $options_type . "', '" . $options_length . "')");


                for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
                    $language_id = $languages[$i]['id'];
                    $products_options_name = isset($_POST['option_name'][$language_id]) ? $_POST['option_name'][$language_id] : '';
                    $products_options_instruct = isset($_POST['products_options_instruct'][$language_id]) ? $_POST['products_options_instruct'][$language_id] : '';
                    tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_TEXT . " (products_options_text_id, products_options_name, language_id, products_options_instruct) values ('" . $_POST['products_options_id'] . "', '" . tep_db_input($products_options_name) . "', '" . $language_id . "', '" . tep_db_input($products_options_instruct) . "')");
                }
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
            case 'add_product_option_values':
                $value_name_array = $_POST['value_name'];
                for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
                    $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);
                    tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . $_POST['value_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input($value_name) . "')");
                }
                tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . $_POST['option_id'] . "', '" . $_POST['value_id'] . "')");
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
            case 'add_product_attributes':
                $values_id = isset($_POST['values_id']) ? (int)$_POST['values_id'] : 0;
                $value_price = isset($_POST['value_price']) ? (float)$_POST['value_price'] : 0;
                $price_prefix = isset($_POST['price_prefix']) ? $_POST['price_prefix'] : '+';
                $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
                tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . (int)$_POST['products_id'] . "', '" . (int)$_POST['options_id'] . "', '" . $values_id . "', '" . $value_price . "', '" . $price_prefix . "', '" . $sort_order . "')");
                $products_attributes_id = tep_db_insert_id();
                if (DOWNLOAD_ENABLED == 'true' && isset($_POST['products_attributes_filename']) && $_POST['products_attributes_filename'] != '') {
                    tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . $products_attributes_id . ", '" . $_POST['products_attributes_filename'] . "', '" . $_POST['products_attributes_maxdays'] . "', '" . $_POST['products_attributes_maxcount'] . "')");
                }
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
            case 'update_option_name':
                for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
                    // WebMakers.com Added: Product Options Sort Order
                    $option_name = $_POST['option_name'];
                    $products_options_sort_order = $_POST['products_options_sort_order'];
                    $options_type = $_POST['option_type'];
                    $options_length = $_POST['products_options_length'];
                    $option_id = $_POST['option_id'];
                    $products_options_instruct = $_POST['products_options_instruct'];

                    tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set options_type = '" . $options_type . "', options_length = '" . $options_length . "', products_options_sort_order = '" . $products_options_sort_order . "' where products_options_id = '" . $option_id . "'");
                    tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_TEXT . " set products_options_instruct = '" . tep_db_input($products_options_instruct[$languages[$i]['id']]) . "', products_options_name = '" . tep_db_input($option_name[$languages[$i]['id']]) . "' where  products_options_text_id = '" . $_POST['option_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");

                }
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
            case 'update_value':
                for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
                    $value_name = $_POST['value_name'];
                    tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value_name[$languages[$i]['id']]) . "' where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
                }
                tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . $_POST['option_id'] . "', products_options_values_id = '" . $_POST['value_id'] . "'  where products_options_values_to_products_options_id = '" . $_POST['value_id'] . "'");
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
            case 'update_product_attribute':
                // BOF: WebMakers.com Added: Attribute Sorter
                tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "', products_options_sort_order = '" . $_POST['sort_order'] . "' where products_attributes_id = '" . $_POST['attribute_id'] . "'");
                //        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "', products_options_sort_order = '" . $_POST['sort_order'] . "'");
                // EOF: WebMakers.com Added: Attribute Sorter
                // BOM Mod: allow for the download filename to be added or deleted when doing an edit
                if (DOWNLOAD_ENABLED == 'true') {
                    $download_query_raw ="select products_attributes_filename from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                    where products_attributes_id='" . $_POST['attribute_id'] . "'";
                    $download_query = tep_db_query($download_query_raw);
                    if (tep_db_num_rows($download_query) > 0) {
                        $download_attribute_found = true;
                    } else {
                        $download_attribute_found = false;
                    }
                    if ($_POST['products_attributes_filename'] != '') {
                        if ($download_attribute_found) {
                            tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                                set products_attributes_filename='" . $_POST['products_attributes_filename'] . "',
                                products_attributes_maxdays='" . $_POST['products_attributes_maxdays'] . "',
                                products_attributes_maxcount='" . $_POST['products_attributes_maxcount'] . "'
                                where products_attributes_id = '" . $_POST['attribute_id'] . "'");
                        } else {
                            tep_db_query("insert " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                                set products_attributes_id = '" . $_POST['attribute_id'] . "',
                                products_attributes_filename='" . $_POST['products_attributes_filename'] . "',
                                products_attributes_maxdays='" . $_POST['products_attributes_maxdays'] . "',
                                products_attributes_maxcount='" . $_POST['products_attributes_maxcount'] . "'");
                        }
                    } else {
                        if ($download_attribute_found) {
                            tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                                where products_attributes_id = '" . $_POST['attribute_id'] . "'");
                        }
                    }
                }
                // EOM Mod:
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
            case 'delete_option':
                tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_TEXT . " where products_options_text_id = '" . $_GET['option_id'] . "'");
                tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $_GET['option_id'] . "'");
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
            case 'delete_value':
                tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "'");
                tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "'");
                tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . $_GET['value_id'] . "'");
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
            case 'delete_attribute':
                tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $_GET['attribute_id'] . "'");
                // Added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
                tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . $_GET['attribute_id'] . "'");
                tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
                break;
        }
    }
?>   
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <?php include('includes/html_head.php');?>
        <!-- ================== BEGIN PAGE LEVEL JS ================== -->
        <link href="assets/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" />   
        <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
        <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />    
        <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />


        <!-- ================== END PAGE LEVEL JS ================== -->      
        <script language="javascript"><!--
            function go_option() {
                if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
                    location = "<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . ($_GET['option_page'] ? $_GET['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
                }
            }
            var options_obj = new Object();
            <?php
                $values_query = tep_db_query("select povtpo.products_options_id, pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " povtpo,  " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pov.products_options_values_id = povtpo.products_options_values_id and language_id = '" . (int)$languages_id . "' order by povtpo.products_options_id");
                $notFirstTime = False;
                $last_option = '';
                while ($values = tep_db_fetch_array($values_query)) {
                    if ( $values['products_options_id'] != $last_option ) {
                        if ( $notFirstTime ) {      
                            $option_str .= ']; options_obj["' . $values['products_options_id'] . '"] = [' . $values['products_options_values_id'];
                        } else {
                            $option_str .= ' options_obj["' . $values['products_options_id'] . '"] = [' . $values['products_options_values_id'];
                        }
                        $last_option = $values['products_options_id'];
                    } else {
                        $option_str .= ', ' . $values['products_options_values_id'];
                    }
                    $notFirstTime = true;
                }
                $option_str.= "]; \n";

                echo $option_str;

            ?>
            var values_obj = new Object();
            <?php
                $values_query = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$languages_id . "' order by products_options_values_id");
                while ($values = tep_db_fetch_array($values_query)) {
                    $value_str .= ' values_obj["' . $values['products_options_values_id'] . '"] = \'' . addslashes($values['products_options_values_name']) . '\'; ';
                }

                echo $value_str . "\n";

            ?>
            function setvalues(form) {
                opt = document[form].options_id.options[document[form].options_id.selectedIndex].value;
                document[form].values_id.options.length = 0;
                if ( options_obj[opt] instanceof Array ) {
                    for ( var v in options_obj[opt] ) {
                        if(gisInteger(v)) {        
                            document[form].values_id.options[document[form].values_id.options.length] = new Option( values_obj[options_obj[opt][v]], options_obj[opt][v] );
                        }
                    }
                } else {
                    document[form].values_id.options[document[form].values_id.options.length] = new Option( '<?php echo JAVASCRIPT_TEXT_OPTION_TYPE_TEXT; ?>', 0 );
                }

            }
            function gisInteger (s)
            {
                var i;

                if (gisEmpty(s))
                    if (gisInteger.arguments.length == 1) return 0;
                    else return (gisInteger.arguments[1] == true);

                for (i = 0; i < s.length; i++)
                {
                    var c = s.charAt(i);

                    if (!gisDigit(c)) return false;
                }

                return true;
            }

            function gisEmpty(s)
            {
                return ((s == null) || (s.length == 0))
            }

            function gisDigit (c)
            {
                return ((c >= "0") && (c <= "9"))
            }
        //--></script>                                               

    </head>
    <body>
        <!-- begin #page-loader -->
        <div id="page-loader" class="fade in"><span class="spinner"></span></div>
        <!-- end #page-loader --> 

        <!-- begin #page-container -->
        <div id="page-container" class="fade page-sidebar-fixed page-header-fixed"> 
        <?php include('includes/header.php');?> 

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
        <h1 class="page-header">Product Options</h1>
        <!-- end page-header -->                                  
        <!-- begin row -->
        <div class="row"> 
            <!-- begin row -->
            <div class="row"> 
                <!-- begin col-12 -->
                <div class="col-md-12"> 
                    <!-- begin panel -->
                    <ul class="nav nav-tabs no-bg">
                        <li class="active"><a class="f-s-14 f-w-600" aria-expanded="true" href="#options" data-toggle="tab"><i class="fa fa-th-large"></i> Options Groups</a></li>
                        <li class=""><a class="f-s-14 f-w-600" aria-expanded="false" href="#values" data-toggle="tab"><i class="fa fa-th"></i> Options Values</a></li>
                        <li class=""><a class="f-s-14 f-w-600" aria-expanded="false" href="#attributes" data-toggle="tab"><i class="fa fa-list"></i> Product Attributes</a></li>
                    </ul>
                    <div class="panel m-b-10"> 
                        <!--div class="panel-heading">
                        <h4 class="panel-title">&nbsp;</h4>
                        </div -->
                        <div class="panel-body">     
                            <div class="no-footer">

                                <div class="row">
                                    <div class="col-sm-12"> 

                                        <!-- begin tab content -->
                                        <div class="tab-content">
                                            <!-- begin options tab content -->
                                            <div class="tab-pane fade active in" id="options">
                                                <div class="row">
                                                    <div class="col-sm-6 p-b-10">
                                                        <a href="#new-option" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Option Group</a>
                                                    </div>
                                                </div>
                                                <?php
                                                    include('ajax/products_attributes/modal_new_option_group.php');
                                                    $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " po," . TABLE_PRODUCTS_OPTIONS_TEXT . " pot where pot.products_options_text_id = po.products_options_id and pot.language_id = '" . (int)$languages_id . "' order by products_options_id";
                                                    $option_query = tep_db_query($options);
                                                ?>
                                                <table role="grid" id="data-table-options" class="table table-bordered table-hover dataTable no-footer dtr-inline">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting table-title-cell-bg text-center" style="width:15px;"><?php echo TABLE_HEADING_ID; ?></th>
                                                            <th class="sorting table-title-cell-bg"><?php echo TABLE_HEADING_OPT_NAME; ?></th>
                                                            <th class="sorting table-title-cell-bg"><?php echo TABLE_HEADING_OPT_TYPE; ?></th>
                                                            <th class="sorting table-title-cell-bg"><?php echo TABLE_HEADING_OPT_SIZE; ?></th>
                                                            <th class="sorting table-title-cell-bg text-center"><?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?></th>
                                                            <th class="sorting table-title-cell-bg col-xs-2 text-center"><?php echo TABLE_HEADING_ACTION; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $next_id = 1;
                                                            $rows = 0;
                                                            $options = tep_db_query($options);
                                                            while ($options_values = tep_db_fetch_array($options)) {
                                                                $rows++;
                                                            ?>
                                                            <tr role="row">
                                                                <td><?php echo $options_values["products_options_id"]; ?></td>
                                                                <td> <strong class="txt-item-name"><?php echo htmlspecialchars($options_values["products_options_name"]); ?></strong><br/>
                                                                <?php echo $options_values["products_options_instruct"]; ?></td>
                                                                <td><?php echo translate_type_to_name($options_values["options_type"]); ?></td>
                                                                <td><?php echo $options_values["options_length"]; ?></td>
                                                                <td class="text-center"><?php echo $options_values["products_options_sort_order"]; ?></td>
                                                                <td class="text-center"><a onclick="editOptionEntry('<?php echo $options_values["products_options_id"]; ?>')" class="btn btn-info btn-xs editOption"><i class="fa fa-pencil"></i></a> <a onclick="deleteOptionEntry('<?php echo $options_values["products_options_id"]; ?>', '<?php echo htmlspecialchars($options_values["products_options_name"]); ?>')" class="btn btn-danger btn-xs editOption"><i class="fa fa-times"></i></a></td>
                                                            </tr>     
                                                            <?php
                                                            }
                                                        ?>

                                                    </tbody>
                                                </table>
                                                <!-- end tab options content -->
                                            </div>
                                            <!-- end tab content #options -->  

                                            <div class="tab-pane fade" id="values">
                                                <div class="row">
                                                    <div class="col-sm-6 p-b-10">
                                                        <a href="#new-option-value" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Option Value</a>
                                                    </div>
                                                </div>  
                                                <?php include('ajax/products_attributes/modal_new_option_value.php');?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php
                                                            $values = "select distinct pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from
                                                            " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov,
                                                            " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po
                                                            where 
                                                            pov2po.products_options_values_id = pov.products_options_values_id and 
                                                            pov.language_id = '" . (int)$languages_id . "' 
                                                            order by pov.products_options_values_id";
                                                            $values = tep_db_query($values);

                                                        ?>
                                                        <table role="grid" id="data-table-option-value" class="table table-bordered table-hover dataTable no-footer dtr-inline">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting table-title-cell-bg text-center" style="width: 15px;">ID</th>
                                                                    <th class="sorting table-title-cell-bg">Option Name</th>
                                                                    <th class="sorting table-title-cell-bg">Option Value</th>
                                                                    <th class="sorting table-title-cell-bg col-xs-2 text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    while ($values_values = tep_db_fetch_array($values)) {
                                                                        $options_name = tep_options_name($values_values['products_options_id']);
                                                                        $values_name = $values_values['products_options_values_name'];                                                               
                                                                    ?>                                                           
                                                                    <tr role="row">
                                                                        <td><?php echo $values_values["products_options_values_id"]; ?></td>
                                                                        <td><?php echo htmlspecialchars($options_name); ?></td>
                                                                        <td><?php echo htmlspecialchars($values_name); ?></td>
                                                                        <td class="text-center"><a onclick="editOptionValues('<?php echo $values_values["products_options_values_id"]; ?>')" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> <a onclick="deleteOptionValue('<?php echo $values_values['products_options_values_id']; ?>', '<?php echo htmlspecialchars($values_name); ?>')" class="btn btn-danger btn-xs editOption"><i class="fa fa-times"></i></a> </td>
                                                                    </tr>
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- end tab content #attributes values -->

                                            <div class="tab-pane fade" id="attributes">
                                                <div class="row">
                                                    <div class="col-sm-6 p-b-10">
                                                        <a href="#new-attribute" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Attribute</a>
                                                    </div>
                                                </div>
                                                <?php include('ajax/products_attributes/modal_new_attribute.php');?>
                                                <table id="data-table" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Product Name</th>
                                                            <th>File Name</th>
                                                            <th>Option Group</th>
                                                            <th>Option Value</th>
                                                            <th>Sort</th>
                                                            <th>Price</th>
                                                            <th>+ / -</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php 
                                                            $attributes = "select pa.* from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, 
                                                            " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                                            where
                                                            pd.products_id = pa.products_id and 
                                                            pd.language_id = '" . (int)$languages_id . "' 
                                                            order by pd.products_name, pa.products_options_sort_order";
                                                            $attributes = tep_db_query($attributes);
                                                            while ($attributes_values = tep_db_fetch_array($attributes)) {
                                                                $the_download_query = tep_db_query("select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'");
                                                                $the_download= tep_db_fetch_array($the_download_query);
                                                                //  Missing file check
                                                                $filename_is_missing='';
                                                                if ( $the_download['products_attributes_filename'] ) {
                                                                    if ( !file_exists(DIR_FS_DOWNLOAD . $the_download['products_attributes_filename']) ) {
                                                                        $filename_is_missing=' <span class="badge badge-danger" title="'.MISSING.'"><i class="fa fa-times"></i></span>';
                                                                    } else {
                                                                        $filename_is_missing=' <span class="badge badge-success" title="'.GOOD_FILE.'"><i class="fa fa-check"></i></span>';
                                                                    }
                                                                }

                                                            ?><tr>
                                                                <td><?php echo $attributes_values['products_attributes_id']; ?></td>
                                                                <td><?php echo tep_get_products_name($attributes_values['products_id']);?></td>
                                                                <td><?php echo $the_download['products_attributes_filename'] . $filename_is_missing; ?></td>
                                                                <td><?php echo tep_options_name($attributes_values['options_id']);?></td>
                                                                <td><?php echo tep_values_name($attributes_values['options_values_id']);?></td>
                                                                <td><?php echo $attributes_values['products_options_sort_order']; ?></td>
                                                                <td><?php echo $attributes_values['options_values_price']; ?></td>
                                                                <td><?php echo $attributes_values['price_prefix']; ?></td>
                                                                <td class="text-center"><a onclick="editProductAttributes('<?php echo $attributes_values['products_attributes_id']; ?>')" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> <a data-href="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_attribute&attribute_id=' . $attributes_values['products_attributes_id'],'NONSSL');?>" data-toggle="modal" data-target="#confirm-delete-attribute" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>         
                                            </div>
                                        </div>
                                    </div>
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

        <?php include('includes/footer.php');?> 
        <!-- #modal-alert-delete-attribute -->
        <div class="modal fade" id="confirm-delete-attribute">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Confirm Delete Attribute</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger m-b-0">
                            <h4><i class="fa fa-info-circle"></i> Confirm Delete?</h4>
                            <p><?php echo TEXT_WARNING_OF_DELETE; ?></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cancel</a>
                        <a href="javascript:;" class="btn btn-sm btn-danger btn-delete-confirm">Delete</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- #modal-alert-delete-attribute eof -->
        <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
        <script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
        <script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
        <script src="assets/plugins/emodal/eModal.js"></script> 
        <script>
            $(document).ready(function() {
                if ($('#data-table').length !== 0) {
                    $('#data-table').DataTable({
                        responsive: true
                    });
                }

                if ($('#data-table-option-value').length !== 0) {
                    $('#data-table-option-value').DataTable({
                        responsive: true
                    });
                }

                if ($('#data-table-options').length !== 0) {
                    $('#data-table-options').DataTable({
                        responsive: true
                    });
                }
                $(".default-select2").select2();

            });

            /**** Edit and delete for Options Groups ****/
            function editOptionEntry(id) {
                var params = {
                    buttons: false,
                    loadingHtml: '<span class="fa fa-circle-o-notch fa-spin fa-3x text-primary"></span><span class="h4">Loading</span>',
                    title: 'Edit Product Option',
                    url: './ajax/products_attributes/modal_edit_option_groups.php?editOpID='+id,
                };

                return eModal.ajax(params);
            }
            
            function deleteOptionEntry(id, opname) {
                var params = {
                    buttons: false,   
                    loadingHtml: '<span class="fa fa-circle-o-notch fa-spin fa-3x text-primary"></span><span class="h4">Loading</span>',
                    title: 'Delete Product Option Group '+opname,
                    url: './ajax/products_attributes/modal_delete_option_groups.php?delOpID='+id,
                };

                return eModal.ajax(params);
            }

            /**** Edit and delete for Options Values ****/
            function editOptionValues(id) {
                var params = {
                    buttons: false,
                    loadingHtml: '<span class="fa fa-circle-o-notch fa-spin fa-3x text-primary"></span><span class="h4">Loading</span>',
                    title: 'Edit Product Option Value',
                    url: './ajax/products_attributes/modal_edit_option_values.php?editOpValID='+id,
                };

                return eModal.ajax(params);
            }
            
            function deleteOptionValue(id, opvname) {
                var params = {
                    buttons: false,   
                    loadingHtml: '<span class="fa fa-circle-o-notch fa-spin fa-3x text-primary"></span><span class="h4">Loading</span>',
                    title: 'Delete Product Option Value '+opvname,
                    url: './ajax/products_attributes/modal_delete_option_values.php?delOpValID='+id,
                };

                return eModal.ajax(params);
            }
            
            /**** Edit attributes ****/
            function editProductAttributes(id) {
                var params = {
                    buttons: false,
                    loadingHtml: '<span class="fa fa-circle-o-notch fa-spin fa-3x text-primary"></span><span class="h4">Loading</span>',
                    title: 'Edit Product Attributes',
                    url: './ajax/products_attributes/modal_edit_attributes.php?editAttributelID='+id,
                };

                return eModal.ajax(params);
            }
            $('#confirm-delete-attribute').on('show.bs.modal', function(e) {
                $(this).find('.btn-delete-confirm').attr('href', $(e.relatedTarget).data('href'));
            });

        </script>
    </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>