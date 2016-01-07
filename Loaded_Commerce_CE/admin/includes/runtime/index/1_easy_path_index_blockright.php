<?php
/*
  $Id: 1_easy_path_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_EASY_PATH') && ADMIN_BLOCKS_EASY_PATH == 'true'){
  ?>
  <!-- begin easypath -->
  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title"><?php echo BLOCK_TITLE_EASY_PATH;?></h4>
      </div>
      <div class="panel-body bg-white">
        <a href="<?php echo tep_href_link(REMOVE_EASY_PATH_LINK, 'gID=23&selected_box=configuration&cID=10013', 'SSL'); ?>" style="font-size:smaller"> Click to remove this block</a> 
      </div>
  </div>
  <!-- end easypath -->
  <?php
}
?>