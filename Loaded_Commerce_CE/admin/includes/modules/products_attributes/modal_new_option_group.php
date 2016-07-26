<!-- #new-option -->
<div class="modal fade" id="new-option">
    <div class="modal-dialog">
        <?php
            $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
            $max_options_id_values = tep_db_fetch_array($max_options_id_query);
            $next_id = $max_options_id_values['next_id'];
        ?>
        <form class="form-horizontal" name="options" action="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options&', 'NONSSL');?>" method="post">
            <input type="hidden" name="products_options_id" value="<?php echo $next_id;?>">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">New Product Option</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Option Name</label>
                        <div class="col-md-5">
                            <?php 
                                for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
                                    echo '<input class="form-control" placeholder="Option Name in ' . $languages[$i]['name'] . '" type="text" name="option_name[' . $languages[$i]['id'] . ']" size="32"><br />';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Comments</label>
                        <div class="col-md-5">
                            <?php 
                                for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
                                    echo '<input class="form-control" name="products_options_instruct[' . $languages[$i]['id'] . ']" size="32" placeholder="Comments in ' . $languages[$i]['name'] . '" type="text"><br>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Option Type</label>
                        <div class="col-md-3">                                  
                            <?php echo draw_optiontype_pulldown('options_type', $options_values['options_type']) ;?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Size</label>
                        <div class="col-md-3">
                            <input class="form-control" name="options_length" placeholder="" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Sort</label>
                        <div class="col-md-3">
                            <input class="form-control" name="products_options_sort_order" placeholder="" type="text">
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
<!-- #new-option -->   