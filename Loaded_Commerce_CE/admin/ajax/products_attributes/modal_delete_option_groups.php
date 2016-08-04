<?php
    chdir('../');
    require('includes/application_top.php');
    $languages = tep_get_languages();
    include_once(DIR_WS_LANGUAGES . $language . '/products_attributes.php');

?>
<div class="row">
    <div class="col-sm-12">

<table class="table table-bordered table-hover dataTable no-footer dtr-inline">
<?php
    $products = tep_db_query("select pov.products_options_values_name, pov.products_options_values_id from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po left join " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov on pov.products_options_values_id = pov2po.products_options_values_id where pov2po.products_options_id = '" . (int)$_GET['delOpID'] . "' and pov.language_id = '" . (int)$languages_id . "'");
    if (tep_db_num_rows($products)) {
?>
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting table-title-cell-bg text-center" style="width:15px;">ID</th>
                                                            <th class="sorting table-title-cell-bg">Option Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
<?php
    $ok_to_delete = false;
      while ($products_values = tep_db_fetch_array($products)) {
?>
                  <tr>
                    <td align="center"><?php echo $products_values['products_options_values_id']; ?></td>
                    <td><?php echo htmlspecialchars($products_values['products_options_values_name']); ?></td>
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
                <a href="javascript:;" class="btn btn-sm btn-white m-r-5" data-dismiss="modal">Close</a> <?php echo '<a class="btn btn-sm btn-danger' . (($ok_to_delete == true) ? ' ' : ' disabled') . '" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $_GET['delOpID'], 'NONSSL') . '">'; ?>Delete</a>
            </div>
        </div>
</div>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>                   