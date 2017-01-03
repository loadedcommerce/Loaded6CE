<?php
    chdir('../../');
    require('includes/application_top.php');
    $languages = tep_get_languages();
    include_once(DIR_WS_LANGUAGES . $language . '/products_attributes.php');

?>
<div class="row">
    <div class="col-sm-12">

<table class="table table-bordered table-hover dataTable no-footer dtr-inline">
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, pot.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and pot.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . $_GET['delOpValID'] . "' and po.products_options_id = pa.options_id and pa.options_id = pot.products_options_text_id   order by pd.products_name, pa.products_options_sort_order, po.products_options_sort_order");
    if (tep_db_num_rows($products)) {
?>
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting table-title-cell-bg text-center" style="width:15px;">ID</th>
                                                            <th class="sorting table-title-cell-bg">Product</th>
                                                            <th class="sorting table-title-cell-bg">Options Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
<?php
    $ok_to_delete = false;
      while ($products_values = tep_db_fetch_array($products)) {
?>
                  <tr>
                    <td align="center"><?php echo $products_values['products_id']; ?></td>
                    <td><?php echo htmlspecialchars($products_values['products_name']); ?></td>
                    <td><?php echo htmlspecialchars($products_values['products_options_name']); ?></td>
                  </tr>
<?php
      }
      $ok_to_delete = false;
?>
                  <tr>
                    <td colspan="3" class="alert alert-danger"><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
<?php
    } else {
        $ok_to_delete = true;
?>
                  <tr>
                    <td colspan="3"><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
<?php
    }
?>
</tbody>
</table>
    </div>
    <div class="col-sm-12">
            <div class="col-md-10">
                <a href="javascript:;" class="btn btn-sm btn-white m-r-5" data-dismiss="modal">Close</a> <?php echo '<a class="btn btn-sm btn-danger' . (($ok_to_delete == true) ? ' ' : ' disabled') . '" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $_GET['delOpValID'], 'NONSSL') . '">'; ?>Delete</a>
            </div>
        </div>
</div>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>                   