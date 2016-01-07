<?php
/*
  $Id: 4_reviews_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_REVIEWS_STATUS') && ADMIN_BLOCKS_REVIEWS_STATUS == 'true'){
  //Review Count Code
  $review_query = tep_db_query("select count(reviews_id) as reviewcnt from " . TABLE_REVIEWS);
  $reviewcount = tep_db_fetch_array($review_query);
  //define('REVIEW_COUNT',$reviewcount['reviewcnt']);
  ?>
  <!-- begin reviews -->
  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title"><?php echo BLOCK_TITLE_REVIEWS;?></h4>
      </div>
      <div class="panel-body bg-white">
          <ul class="list-unstyled">
              <li><?php echo BLOCK_CONTENT_REVIEWS_TOTAL_REVIEWS.' : '.$reviewcount['reviewcnt'];?></li>
              <!-- li><?php echo BLOCK_CONTENT_REVIEWS_WAITING_APPROVAL;?>: 2 </li -->
          </ul>
      </div>
  </div>
  <!-- end reviews -->
  <?php
}
?>