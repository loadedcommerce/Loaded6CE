<?php
/*
  $Id: 4_customers_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if(defined('ADMIN_BLOCKS_CUSTOMERS_STATUS') && ADMIN_BLOCKS_CUSTOMERS_STATUS == 'true'){
  //Customer Count Code
  $customer_query = tep_db_query("select count(customers_id) as customercnt from " . TABLE_CUSTOMERS);
  $customercount = tep_db_fetch_array($customer_query);
  define('CUSTOMER_COUNT',$customercount['customercnt']);
  //Customer Subscribed Count Code
  $customer_query = tep_db_query("select count(customers_id) as customercnt from " . TABLE_CUSTOMERS." where customers_newsletter=1");
  $customercount = tep_db_fetch_array($customer_query);
  define('CUSTOMER_SUBSCRIBED_COUNT',$customercount['customercnt']);
  ?>
  <!-- begin customers -->
  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title"><?php echo BLOCK_TITLE_CUSTOMERS;?></h4>
      </div>
      <div class="panel-body bg-white">
         <ul class="list-unstyled">
          <li><?php echo BLOCK_CONTENT_CUSTOMERS_TOTAL.' : '.CUSTOMER_COUNT;?></li>
          <li><?php echo BLOCK_CONTENT_CUSTOMERS_SUBSCRIBED.' : '.CUSTOMER_SUBSCRIBED_COUNT;?></li>
        </ul>
      </div>
  </div>
  <!-- end customers -->
  <?php
}
?>