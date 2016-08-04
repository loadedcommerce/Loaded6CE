<?php
    chdir('../');
    require('includes/application_top.php');
    $languages = tep_get_languages();

    $attributes = "select pa.* from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, 
      " . TABLE_PRODUCTS_DESCRIPTION . " pd 
      where
      pd.products_id = pa.products_id and 
      pd.language_id = '" . (int)$languages_id . "' and 
      pa.products_attributes_id = '" . $_GET['editAttributelID'] . "'";
  $attributes = tep_db_query($attributes);
  $attributes_values = tep_db_fetch_array($attributes);
    $products_name_only = tep_get_products_name($attributes_values['products_id']);
    $options_name = tep_options_name($attributes_values['options_id']);
    $values_name = tep_values_name($attributes_values['options_values_id']);
  
   echo '<form name="EditAttribute" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_product_attribute', 'NONSSL') . '" method="post">';
?>
<input type="hidden" name="attribute_id" value="<?php echo $attributes_values['products_attributes_id']; ?>">
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-md-5 control-label"><strong>Product Name</strong></label>
                    <div class="col-md-5">
                        <strong><?php echo $products_name_only; ?></strong><input type="hidden" name="products_id" value="<?php echo $attributes_values['products_id']; ?>">
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-md-5 control-label">Select an Option Group</label>
                    <div class="col-md-5">
                        <select class="form-control" name="options_id">
<?php
      $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where pot.products_options_text_id = po.products_options_id and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
      while($options_values = tep_db_fetch_array($options)) {
        if ($attributes_values['options_id'] == $options_values['products_options_id']) {
          echo "\n" . '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '" selected="selected">' . htmlspecialchars($options_values['products_options_name']) . '</option>';
        } else {
          echo "\n" . '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '">' . htmlspecialchars($options_values['products_options_name']) . '</option>';
        }
      }
?>
                        </select><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-md-5 control-label">Option Value</label>
                    <div class="col-md-5">
                        <select class="form-control" name="values_id">
<?php
      $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id ='" . (int)$languages_id . "' order by products_options_values_name");
      while($values_values = tep_db_fetch_array($values)) {
        if ($attributes_values['options_values_id'] == $values_values['products_options_values_id']) {
          echo "\n" . '<option name="' . htmlspecialchars($values_values['products_options_values_name']) . '" value="' . $values_values['products_options_values_id'] . '" selected="selected">' . htmlspecialchars($values_values['products_options_values_name']) . '</option>';
        } else {
          echo "\n" . '<option name="' . htmlspecialchars($values_values['products_options_values_name']) . '" value="' . $values_values['products_options_values_id'] . '">' . htmlspecialchars($values_values['products_options_values_name']) . '</option>';
        }
      }
?>
                        </select><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 m-b-10">
                <div class="form-group">
                    <label class="col-md-5 control-label">Value Price</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input name="value_price" class="form-control" placeholder="" type="text" value="<?php echo $attributes_values['options_values_price']; ?>"><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-md-5 control-label">Price Prefix</label>
                    <div class="col-md-5">
                        <input name="price_prefix" class="form-control" placeholder="" type="text" value="<?php echo $attributes_values['price_prefix']; ?>"><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-md-5 control-label">Sort Order</label>
                    <div class="col-md-5">
                        <input name="sort_order" class="form-control" placeholder="" type="text" value="<?php echo $attributes_values['products_options_sort_order']; ?>"><br>
                    </div>
                </div>
            </div>
        </div>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount
                              from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
        $download_query = tep_db_query($download_query_raw);
        if (tep_db_num_rows($download_query) > 0) {
          $download = tep_db_fetch_array($download_query);
          $products_attributes_filename = $download['products_attributes_filename'];
          $products_attributes_maxdays  = $download['products_attributes_maxdays'];
          $products_attributes_maxcount = $download['products_attributes_maxcount'];
        }
?>
        <fieldset>
            <legend>Downloadable products</legend>
            <div class="form-group">
                <label class="col-md-5 control-label">File Name</label>
                <div class="col-md-5">
                    <input name="products_attributes_filename" class="form-control" placeholder="" type="text" value="<?php echo $products_attributes_filename;?>"> <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-5 control-label">Expiry Days</label>
                <div class="col-md-5">
                    <input name="products_attributes_maxdays" value="<?php echo $products_attributes_maxdays;?>" class="form-control" placeholder="" type="text"><br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-5 control-label">Download Count</label>
                <div class="col-md-5">
                    <input name="products_attributes_maxcount" value="<?php echo $products_attributes_maxcount;?>" class="form-control" placeholder="" type="text"> <br>
                </div>
            </div>
        </fieldset>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-md-5 control-label"></label>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary m-r-5"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
        <?php
      }
        ?>
    </div>
</div>
</form>         
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>