<!-- #modal-dialog new-option-value -->
<div class="modal fade" id="new-option-value">
    <div class="modal-dialog">
        <?php
            $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
            $max_values_id_values = tep_db_fetch_array($max_values_id_query);
            $next_id = $max_values_id_values['next_id'];
        ?>
        <form name="values" action="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values', 'NONSSL');?>" method="post" class="form-horizontal">
            <input type="hidden" name="value_id" value="<?php echo $next_id; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add Product Option Value</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 m-b-10">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Select Option</label>
                                <div class="col-md-5">
                                    <select class="form-control" name="option_id">
                                        <?php
                                            $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " AS po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " AS pot where po.options_type in (0,2,3,5) and po.products_options_id = pot.products_options_text_id  and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
                                            while ($options_values = tep_db_fetch_array($options)) {
                                                echo "\n" . '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '">' . htmlspecialchars($options_values['products_options_name']) . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Option Value</label>
                                <div class="col-md-5">
                                    <?php 
                                        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
                                            echo '<input class="form-control" placeholder="Option Value Name in ' . $languages[$i]['name'] . '" type="text" name="value_name[' . $languages[$i]['id'] . ']"><br />';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary m-r-5"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- modal end new-option-value-->