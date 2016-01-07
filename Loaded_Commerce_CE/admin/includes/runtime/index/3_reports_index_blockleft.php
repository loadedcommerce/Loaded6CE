<?php
/*
  $Id: 3_reports_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if(defined('ADMIN_BLOCKS_REPORTS_STATUS') && ADMIN_BLOCKS_REPORTS_STATUS == 'true'){
  ?>
<!-- begin reports -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo BLOCK_TITLE_REPORTS;?></h4>
    </div>
    <div class="panel-body bg-white">
        <ul class="list-unstyled">
          <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_PRODUCTS_VIEWED;?></a></li>
          <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_PRODUCTS_PURCHASED;?></a></li>
          <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_CUSTOMERS,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_CUSTOMER_ORDERS_TOTAL;?></a></li>
          <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_MONTHLY_SALES,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_MONTHLY_SALES_TAX;?></a></li>
        </ul>
    </div>
</div>
<!-- end reports -->
  <?php
}
?>