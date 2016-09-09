<?php
    chdir('../../');
    require('includes/application_top.php');
    $languages = tep_get_languages();
    
    $cfg_extra_query = tep_db_query("select * from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$_GET['editConfigurationlID'] . "'");
    $cInfo = tep_db_fetch_array($cfg_extra_query);
?>
<form name="EditConfigurationValues" action="<?php echo tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $cInfo['configuration_group_id'] . '&cID=' . (int)$cInfo['configuration_id'] . '&action=save');?>" method="post">
    <?php 
        echo tep_draw_hidden_field('configuration_key', $cInfo['configuration_key']);
     ?>
    <div class="row f-s-13">
        <div class="col-sm-12">

            <div class="form-group">
                <label class="col-md-12 control-label p-l-0"><p><?php echo $cInfo['configuration_description'];?></p></label>
                <div class="col-md-12">
                        <?php
                            if ($cInfo['set_function']) {
                              eval('$value_field = ' . $cInfo['set_function'] . '"' . htmlspecialchars($cInfo['configuration_value']) . '");');
                            } else {
                              $value_field = tep_draw_input_field('configuration_value', $cInfo['configuration_value']);
                            }
                              echo $value_field;
                        ?>
        
                </div>
            </div>
        </div>

        <div class="modal-footer col-sm-12 m-t-15 p-t-15">
            <div class="form-group">
                <div class="col-md-12 text-right">
                    <a href="javascript:;" class="btn btn btn-white m-r-5" data-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
</form>         
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>                   