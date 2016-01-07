<?php
/*
  $Id: 3_products_index_blockright.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_PRODUCTS_STATUS') && ADMIN_BLOCKS_PRODUCTS_STATUS == 'true'){
  //Category Count Code
  $category_query = tep_db_query("select count(categories_id) as catcnt from " . TABLE_CATEGORIES);
  $categorycount = tep_db_fetch_array($category_query);
  define('CATEGORY_COUNT',$categorycount['catcnt']);
  //Product Count Code
  $product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS);
  $productcount = tep_db_fetch_array($product_query);
  define('PRODUCT_COUNT',$productcount['productcnt']);
  //Product Out of Stock Count Code
  $product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS." where products_quantity <= 0");
  $productcount = tep_db_fetch_array($product_query);
  define('PRODUCT_OUT_OF_STOCK_COUNT',$productcount['productcnt']);
  //ActiveProduct Count Code
  $product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS." where products_status = 1");
  $productcount = tep_db_fetch_array($product_query);
  define('ACTIVE_PRODUCT_COUNT',$productcount['productcnt']);
  ?>
<!-- begin products -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo BLOCK_TITLE_PRODUCTS;?></h4>
    </div>
    <div class="panel-body bg-white">
         <ul class="list-unstyled">
          <li><?php echo BLOCK_CONTENT_PRODUCTS_CATEGORIES.' : '.CATEGORY_COUNT;?></li>
          <li><?php echo BLOCK_CONTENT_PRODUCTS_TOTAL_PRODUCTS.' : '.PRODUCT_COUNT;?></li>
          <li><?php echo BLOCK_CONTENT_PRODUCTS_ACTIVE.' : '.ACTIVE_PRODUCT_COUNT;?></li>
          <li><?php echo BLOCK_CONTENT_PRODUCTS_NOSTOCK.' : '.PRODUCT_OUT_OF_STOCK_COUNT;?></li>
        </ul>
    </div>
</div>
<!-- end products -->
  <?php
  }
?>