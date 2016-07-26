<?php
    require('includes/application_top.php');
    $languages = tep_get_languages();
    $options_query = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " po," . TABLE_PRODUCTS_OPTIONS_TEXT . " pot where pot.products_options_text_id = po.products_options_id and po.products_options_id = '" . $_GET['editOpID'] . "'");
    $options_values = tep_db_fetch_array($options_query);

    $option_name_raw = tep_db_query("select  po.options_type, po.options_length, po.products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " po where po.products_options_id = '" . $options_values['products_options_id'] . "' order by products_options_sort_order");
    $option_name = tep_db_fetch_array($option_name_raw);
    for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $option_name_raw1 = tep_db_query("select pot.products_options_name, pot.products_options_instruct from " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where  pot.products_options_text_id ='" . $options_values['products_options_id'] ."' and pot.language_id = '" . $languages[$i]['id'] . "'");
        $option_name1 = tep_db_fetch_array($option_name_raw1);
        $option_name_input .= '<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="32" value="' . htmlspecialchars($option_name1['products_options_name']) . '" class="form-control"><br />';
        $option_name_instruct .= '<input type="text" name="products_options_instruct[' . $languages[$i]['id'] . ']" size="32" value="' . htmlspecialchars($option_name1['products_options_instruct']) . '" class="form-control"><br />';
    }

    echo '<form name="EditOption" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name', 'NONSSL') . '" method="post">';
?>
<input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">
<div class="row">
    <div class="col-sm-12">

        <div class="form-group">
            <label class="col-md-3 control-label">Option Name</label>
            <div class="col-md-5">
                <?php 
                    echo $option_name_input;
                ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-md-3 control-label">Comments</label>
            <div class="col-md-8">
                <?php 
                    echo $option_name_instruct;
                ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-md-3 control-label">Option Type</label>
            <div class="col-md-3"><?php echo draw_optiontype_pulldown('option_type', $options_values['options_type']);?><br>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-md-3 control-label">Size</label>
            <div class="col-md-3">
                <input type="text" name="products_options_length" size="3" value="<?php echo $options_values['options_length'];?>" class="form-control"><br>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-md-3 control-label">Sort</label>
            <div class="col-md-3">
                <input type="text" name="products_options_sort_order" size="3" value="<?php echo $options_values['products_options_sort_order'];?>" class="form-control"><br>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary m-r-5"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>
</form>         
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>                   