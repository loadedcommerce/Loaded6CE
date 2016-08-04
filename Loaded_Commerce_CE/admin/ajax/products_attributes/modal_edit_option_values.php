<?php
    chdir('../');
    require('includes/application_top.php');
    $languages = tep_get_languages();
    
    $values = tep_db_query("select distinct pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from
    " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov,
    " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po
    where 
   pov2po.products_options_values_id = pov.products_options_values_id and 
    pov.language_id = '" . (int)$languages_id . "' and pov.products_options_values_id = '" . $_GET['editOpValID'] . "'");
    $values_values = tep_db_fetch_array($values);


    for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['editOpValID'] . "' and language_id = '" . $languages[$i]['id'] . "'");
        $value_name = tep_db_fetch_array($value_name);
        $option_value_name .= '<input type="text" name="value_name[' . $languages[$i]['id'] . ']" value="' . htmlspecialchars($value_name['products_options_values_name']) . '" class="form-control"><br />';
    }


?>
<form name="EditOptionValues" action="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value', 'NONSSL');?>" method="post">
    <input type="hidden" name="value_id" value="<?php echo $_GET['editOpValID']; ?>">
    <div class="row">
        <div class="col-sm-12">

            <div class="form-group">
                <label class="col-md-3 control-label">Select Option</label>
                <div class="col-md-5">
                    <select class="form-control" name="option_id">
                        <?php
                            $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " AS po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " AS pot where po.options_type in (0,2,3,5) and po.products_options_id = pot.products_options_text_id  and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
                            while ($options_values = tep_db_fetch_array($options)) {
                                echo "\n" . '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '"';
                                if ($values_values['products_options_id'] == $options_values['products_options_id']) {
                                    echo ' selected';
                                }
                                echo '>' . htmlspecialchars($options_values['products_options_name']) . '</option>';
                            } 
                        ?>
                    </select><br />            
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-md-3 control-label">Option Value</label>
                <div class="col-md-5">
                    <?php 
                        echo $option_value_name;
                    ?>
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