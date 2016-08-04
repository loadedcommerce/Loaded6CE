<!-- #modal-dialog new products attribute -->
<div class="modal fade" id="new-attribute">
    <div class="modal-dialog">
        <form name="attributes" action="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_attributes', 'NONSSL');?>" method="post" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add Product Attribute</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Select Product</label>
                                <div class="col-md-5">
                                    <select class="default-select2 form-control" style="width: 100%;" name="products_id">
                                        <?php
                                            $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' AND products_status = '1' order by pd.products_name");
                                            while ($products_values = tep_db_fetch_array($products)) {
                                                echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Select an Option Group</label>
                                <div class="col-md-5">
                                    <select class="form-control" name="options_id">
                                        <?php
                                            $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where pot.products_options_text_id = po.products_options_id and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
                                            while($options_values = tep_db_fetch_array($options)) {
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
                                    <select class="form-control" name="values_id">
                                        <?php
                                            $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id ='" . (int)$languages_id . "' order by products_options_values_name");
                                            while($values_values = tep_db_fetch_array($values)) {
                                                echo "\n" . '<option name="' . htmlspecialchars($values_values['products_options_values_name']) . '" value="' . $values_values['products_options_values_id'] . '">' . htmlspecialchars($values_values['products_options_values_name']) . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Value Price</label>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input name="value_price" class="form-control" placeholder="" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Price Prefix</label>
                                <div class="col-md-3">
                                    <input name="price_prefix" class="form-control" placeholder="" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Sort Order</label>
                                <div class="col-md-3">
                                    <input name="sort_order" class="form-control" placeholder="" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <fieldset>
                        <legend>Downloadable products</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">File Name</label>
                            <div class="col-md-3">
                                <input name="products_attributes_filename" class="form-control" placeholder="" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Expiry Days</label>
                            <div class="col-md-3">
                                <input name="products_attributes_maxdays" value="<?php echo DOWNLOAD_MAX_DAYS;?>" class="form-control" placeholder="" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Download Count</label>
                            <div class="col-md-3">
                                <input name="products_attributes_maxcount" value="<?php echo DOWNLOAD_MAX_COUNT;?>" class="form-control" placeholder="" type="text">
                            </div>
                        </div>
                    </fieldset>


                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary m-r-5"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- modal new products attribute end-->