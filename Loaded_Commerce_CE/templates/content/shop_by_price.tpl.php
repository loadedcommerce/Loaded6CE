<?php 
// RCI code start
 echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('shopbyprice', 'top');
// RCI code eof
?>
 <div class="row">
   <div class="col-sm-12 col-lg-12 large-padding-left margin-top">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
//include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
     if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PRODUCT_LISTING)) {
        require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PRODUCT_LISTING);
    } else {
        require(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
    }
// RCI code start
echo $cre_RCI->get('shopbyprice', 'menu');
// RCI code eof
?>
    </div></div>
<?php 
// RCI code start
echo $cre_RCI->get('shopbyprice', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>