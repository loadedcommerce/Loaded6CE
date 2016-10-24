<?php
/*
  $Id: product_info.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productinfotabs', 'top');
// RCI code eof
echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action', 'products_id', 'id')) . 'action=add_product' . '&' . $params), 'post', 'enctype="multipart/form-data"');
?>

<div class="row" id="content">
<?php
  if ($messageStack->size('cart_quantity') > 0) {
?>
<div class="message-stack-container alert alert-danger margin-bottom mid-margin-right with-padding">

<?php echo $messageStack->output('cart_quantity'); ?>
</div>


<?php
  }
  // added for CDS CDpath support
  $params = (isset($_SESSION['CDpath'])) ? 'CDpath=' . $_SESSION['CDpath'] : '';
  if ($product_check['total'] < 1) {
    ?>
<?php  new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?>
<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $params) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
  <?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

// sub product start
if (STOCK_ALLOW_CHECKOUT =='false') {
  $allowcriteria = "";
}
// get sort order
$csort_order = tep_db_fetch_array(tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'SUB_PRODUCTS_SORT_ORDER'"));
$select_order_by = '';
switch (strtoupper($csort_order['configuration_value'])) {
  case 'MODEL':
	$select_order_by .= 'sp.products_model';
	break;
  case 'NAME':
	$select_order_by .= 'spd.products_name';
	break;
  case 'PRICE':
	$select_order_by .= 'sp.products_price';
	break;
  case 'QUANTITY':
	$select_order_by .= 'sp.products_quantity';
	break;
  case 'WEIGHT':
	$select_order_by .= 'sp.products_weight';
	break;
  case 'SORT ORDER':
	$select_order_by .= 'sp.sort_order';
	break;
  case 'LAST ADDED':
	$select_order_by .= 'sp.products_date_added';
	break;
  default:
	$select_order_by .= 'sp.products_model';
	break;
}
$sub_products_query = tep_db_query("select sp.products_id, sp.products_quantity, sp.products_price, sp.products_tax_class_id, sp.products_image, spd.products_name, spd.products_blurb, sp.products_model from " . TABLE_PRODUCTS . " sp, " . TABLE_PRODUCTS_DESCRIPTION . " spd where sp.products_quantity > 0 and sp.products_parent_id = " . (int)$product_info['products_id'] . " and spd.products_id = sp.products_id and spd.language_id = " . (int)$languages_id . " order by " . $select_order_by);

   /*if (tep_not_null($product_info['products_model'])) {
      $products_name = '<h1>' . $product_info['products_name'] . '</h1>&nbsp;<span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = '<h1 class="pageHeading">' . $product_info['products_name'] . '</h1>';
    }*/
    $products_name = '<h1 class="pageHeading">' . $product_info['products_name'] . '</h1>';
    if ($product_has_sub > '0'){ // if product has sub products
      $products_price ='';// if you like to show some thing in place of price add here
    } else {
      $pf->loadProduct($product_info['products_id'],$languages_id);
      $products_price = $pf->getPriceStringShort();
    } // end sub product check
      ?>




<?php
              if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
                echo '<div class="col-sm-12 col-lg-12 clearfix" ><span class="main alert-success">' . sprintf(TEXT_DATE_AVAILABLE . '<br>', tep_date_long($product_info['products_date_available'])) . '</span></div>';
              }
              ?>
  <?php
    // RCI code start
    echo $cre_RCI->get('productinfotabs', 'underpriceheading');
    // RCI code eof
  ?>

<div class="col-sm-12 col-lg-12 clearfix" ><h1 class="no-margin-top"><?php echo $product_info['products_name']; ?></h1></div>
<div class="clearfix"></div>
   <div class="col-sm-6 product-left">

        <div class="product-info">
           <div class="left product-image thumbnails">
		<?php
		                    if ($product_info['products_image_med']!='') {
		                      $new_image = $product_info['products_image_med'];
		                      $image_height = SMALL-IMAGE-HEIGHT;
		                    } else {
		                      $new_image = $product_info['products_image'];
		                      $image_height = SMALL-IMAGE-HEIGHT;
                    }
               ?>
   	  <!-- Megnor Cloud-Zoom Image Effect Start -->
   	  	<div class="image"><a rel="<?php echo $product_info['products_name'] ;?>" href="<?php echo DIR_WS_IMAGES . $new_image ;?>" class="thumbnail fancybox"><?php echo tep_image(DIR_WS_IMAGES . $new_image, $product_info['products_name'],  $image_height,'', 'class="img-responsive"');?></a></div>


   		 <div class="additional-carousel">
   		  		  	<div class="customNavigation">
   				<span class="fa prev fa-chevron-left">&nbsp;</span>
   				<span class="fa next fa-chevron-right">&nbsp;</span>
   			</div>
   		  <div id="additional-carousel" class="image-additional product-carousel">

					<?php
					if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') {
					if ( ($product_info['products_image_sm_1'] != '') || ($product_info['products_image_xl_1'] != '') ||
					($product_info['products_image_sm_2'] != '') || ($product_info['products_image_xl_2'] != '') ||
					($product_info['products_image_sm_3'] != '') || ($product_info['products_image_xl_3'] != '') ||
					($product_info['products_image_sm_4'] != '') || ($product_info['products_image_xl_4'] != '') ||
					($product_info['products_image_sm_5'] != '') || ($product_info['products_image_xl_5'] != '') ||
					($product_info['products_image_sm_6'] != '') || ($product_info['products_image_xl_6'] != '') ) {
					?>
						<?php
					  //include(DIR_WS_MODULES . 'additional_images.php');
					 if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . 'additional_images.php')) {
						require(TEMPLATE_FS_CUSTOM_MODULES . 'additional_images.php');
					} else {
						require(DIR_WS_MODULES . 'additional_images.php');
					}
					   ?>
					<?php
					} }
					?>



       	  </div>

   		  <span class="additional_default_width" style="display:none; visibility:hidden"></span>
   		  </div>
   	<!-- Megnor Cloud-Zoom Image Effect End-->
          </div>
     </div>
    </div><!--product-left finished-->
    <div class="col-sm-6 col-lg-6 clearfix ">
                 <?php if ($product_has_sub > '0') { } else { ?>


                  <?php } if (tep_not_null($product_info['products_model'])) { ?>

                  <?php }
                  if ($product_info['manufacturers_id'] != 0) {
                  ?>

                  <b><?php /*echo TEXT_MANUFACTURER;?></b>
                    <b><?phpecho tep_get_manufacturers_name($product_info['manufacturers_id']); */?></b>

                  <?php } ?>

                  <?php
                    $hide_add_to_cart = hide_add_to_cart();
                    if ($hide_add_to_cart == 'false') {
                      if ($product_has_sub > '0') {
                        echo '&nbsp;';
                      } else {
                  ?>
                    <?php /*(echo TEXT_ENTER_QUANTITY . ':&nbsp;&nbsp;' . tep_draw_input_field('cart_quantity', '1', 'size="4" maxlength="4" id="Qty1" onkeyup="document.getElementById(\'Qty2\').value = document.getElementById(\'Qty1\').value;"  ');*/?>

                  <?php
                      }
                    }
                  ?>

                    <?php
                      $valid_to_checkout= true;
                      if (STOCK_CHECK == 'true') {
                        $stock_check = tep_check_stock((int)$_GET['products_id'], 1);
                        if (tep_not_null($stock_check) && (STOCK_ALLOW_CHECKOUT == 'false')) {
                          $valid_to_checkout = false;
                        }
                      }
                      if ($hide_add_to_cart == 'false') {
                        echo tep_draw_hidden_field('products_id', $product_info['products_id']);
                        if ($valid_to_checkout == true) {

                        }
                      }
                    ?>


    <div class="social-container">
      <div class="social_wrap list-inline no-print">
			<div class="addthis_toolbox addthis_32x32_style addthis_default_style">
				<a class="addthis_button_email"></a>
				<a class="addthis_button_facebook"></a>
				<a class="addthis_button_twitter"></a>
				<a class="addthis_button_google_plusone_share"></a>
				<a class="addthis_button_linkedin"></a>
				<a class="addthis_button_addthis.com"></a>
				<a class="addthis_button_compact"></a>
			</div>
      </div>
    </div>
  <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
  <script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d9b61612caae177"></script>
<hr class="small-margin-top small-margin-bottom">
<div class="well large-margin-top margin-bottom">
<div class="content-products-info-price-container clearfix">
    	            <!-- attributes -->
		            <?php
		              $products_id_tmp = $product_info['products_id'];
		            if(tep_subproducts_parent($products_id_tmp)){
		              $products_id_query = tep_subproducts_parent($products_id_tmp);
		            } else {
		              $products_id_query = $products_id_tmp;
		            }
		            if($product_has_sub > '0') {
		              if ((defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'False')) {
		                // 2.a) PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES = False
		                //        -- Show attributes to main product only
		                $load_attributes_for = $products_id_query;
		                //$load_attributes_for = $products_id_query;
		                include(DIR_WS_MODULES . 'product_info/product_attributes.php');
		              } else {
		                // 2.b) PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES = True
		                //        -- Show attributes to sub product only
		              }
		            } else {
		              // show attributes for parent only
		              $load_attributes_for = $products_id_query;
		              include(DIR_WS_MODULES . 'product_info/product_attributes.php');
		            }
		            ?>
		            <!-- attributes eof -->

</div>
<div class="content-products-info-price-container clearfix">
<span class="content-products-info-price pull-left lt-blue"><?php echo $products_price; ?></span>
    </div>
      <div class="content-products-info-reviews-container large-margin-bottom">
       <label class="content-products-info-reviews-rating-label with-padding-no-top-bottom">Model:&nbsp;<?php echo $product_info['products_model']; ?></label>
       <label class="content-products-info-reviews-rating-label with-padding-no-top-bottom">Manufacturers:&nbsp;<?php echo tep_get_manufacturers_name($product_info['manufacturers_id']); ?></label>
	  </div>
                  <?php
                    $hide_add_to_cart = hide_add_to_cart();
                    if ($hide_add_to_cart == 'false') {
                      if ($product_has_sub > '0') {
                      } else {
                  ?>
            <label class="content-products-info-qty-label"><?php echo TEXT_ENTER_QUANTITY,':'; ?></label>

                    <?php echo  tep_draw_input_field('cart_quantity', '1', 'class="form-control" style="width:69px;" class="content-products-info-qty-input mid-margin-right" maxlength="4" id="Qty1" onkeyup="document.getElementById(\'Qty2\').value = document.getElementById(\'Qty1\').value;"  ');?>
					<i onclick="setQty('up');" style="position:relative; right:-71px; top:-37px; opacity:.3; cursor:pointer;" class="fa fa-plus-square-o fa-lg"></i>
					<i onclick="setQty('dn');" style="position:relative; right:-53px; top:-15px; opacity:.3; cursor:pointer;" class="fa fa-minus-square-o fa-lg"></i>

                  <?php
                      }
                    }
                    if($product_has_sub <= 0) {
                  ?>
	               <p class="margin-top" style=""><button id="btn-buy-now" class="btn btn-block btn-lg btn-success"><?php echo IMAGE_BUTTON_IN_CART; ?></button></p>
				<?php
				   }
				   else
				   {
					 if( (defined('PRODUCT_INFO_SUB_PRODUCT_DISPLAY') && PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'Drop Down'))
					 {
			            echo '<div class="content-products-info-reviews-container large-margin-bottom">';
			            include(DIR_WS_MODULES . 'product_info/sub_products_dropdown.php');
			            echo '</div>';
?>
	               <p class="margin-top" style=""><button id="btn-buy-now" class="btn btn-block btn-lg btn-success"><?php echo IMAGE_BUTTON_IN_CART; ?></button></p>
<?
					 }
				   }
				?>
   </div>
<?php if($product_has_sub <= 0) { ?>
<div class="relative clear-both clearfix buy-btn-div">
<div class="display-inline">
 <div class="col-xs-6 col-sm-4 col-lg-4 no-padding-left mid-margin-top"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params() . $params) . '" class="btn btn-sm cursor-pointer no-margin-left btn-success">' .  IMAGE_BUTTON_REVIEWS . '</a>'; ?></div>
        <div class="col-xs-6 col-sm-8 col-lg-8  text-right no-padding-right mid-margin-top">
          <div class="form-group">
                    <?php
                      if ($product_check['total'] > 0) {
                        if (DESIGN_BUTTON_WISHLIST == 'true') {
                          echo '<script type="text/javascript"><!--' . "\n";
                          echo 'function addwishlist() {' . "\n";
                          echo 'document.cart_quantity.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCT_INFO, 'action=add_wishlist' . '&' . $params)) . '\';' . "\n";
                          echo 'document.cart_quantity.submit();' . "\n";
                          echo '}' . "\n";
                          echo '--></script>' . "\n";
                          echo '<a href="javascript:addwishlist()" class="btn btn-sm cursor-pointer  btn-success">' . IMAGE_BUTTON_ADD_WISHLIST . '</a>';
                        }
                      } // if products_check
                    ?>
          </div>
        </div>

      </div>
    </div>
    <?php }
				   else
				   {
					 if( (defined('PRODUCT_INFO_SUB_PRODUCT_DISPLAY') && PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'Drop Down'))
					 {
?>
<div class="relative clear-both clearfix buy-btn-div">
<div class="display-inline">
 <div class="col-xs-6 col-sm-4 col-lg-4 no-padding-left mid-margin-top"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params() . $params) . '" class="btn btn-sm cursor-pointer no-margin-left btn-success">' .  IMAGE_BUTTON_REVIEWS . '</a>'; ?></div>
        <div class="col-xs-6 col-sm-8 col-lg-8  text-right no-padding-right mid-margin-top">
          <div class="form-group">
                    <?php
                      if ($product_check['total'] > 0) {
                        if (DESIGN_BUTTON_WISHLIST == 'true') {
                          echo '<script type="text/javascript"><!--' . "\n";
                          echo 'function addwishlist() {' . "\n";
                          echo 'document.cart_quantity.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCT_INFO, 'action=add_wishlist' . '&' . $params)) . '\';' . "\n";
                          echo 'document.cart_quantity.submit();' . "\n";
                          echo '}' . "\n";
                          echo '--></script>' . "\n";
                          echo '<a href="javascript:addwishlist()" class="btn btn-sm cursor-pointer  btn-success">' . IMAGE_BUTTON_ADD_WISHLIST . '</a>';
                        }
                      } // if products_check
                    ?>
          </div>
        </div>

      </div>
    </div>
<?
					 }
				   }
    
    ?>
    </div>

    <div class="col-sm-12 col-lg-12 no-padding-left no-padding-right large-margin-bottom"><!--newtabstart12div-->

		            <!-- sub products -->
		            <?php
		    if ( tep_db_num_rows($sub_products_query) > 0 ) {
		        if(defined('PRODUCT_INFO_SUB_PRODUCT_DISPLAY') && PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'In Listing'){
		            lc_check_addon_modules('product_info/sub_products_listing.php');
		        }
		    }
		    // sub product_eof
		    ?>
		            <!-- sub products eof -->
		            <?php
                    if($product_has_sub > 0 && (defined('PRODUCT_INFO_SUB_PRODUCT_DISPLAY') && PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'In Listing')) {
                  ?>
	  <div class="col-xs-12 col-sm-8 pull-right"><button id="btn-buy-now" class="btn btn-block btn-lg btn-success"><?php echo IMAGE_BUTTON_IN_CART; ?></button></div>
			<div class="col-xs-6 col-sm-2 small-margin-top pull-right">
			  <div class="form-group" style="float:right;">
						<?php
						  if ($product_check['total'] > 0) {
							if (DESIGN_BUTTON_WISHLIST == 'true') {
							  echo '<script type="text/javascript"><!--' . "\n";
							  echo 'function addwishlist() {' . "\n";
							  echo 'document.cart_quantity.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCT_INFO, 'action=add_wishlist' . '&' . $params)) . '\';' . "\n";
							  echo 'document.cart_quantity.submit();' . "\n";
							  echo '}' . "\n";
							  echo '--></script>' . "\n";
							  echo '<a href="javascript:addwishlist()" class="btn btn-sm cursor-pointer  btn-success">' . IMAGE_BUTTON_ADD_WISHLIST . '</a>';
							}
						  } // if products_check
						?>
			  </div>
			</div>
	  <div class="col-xs-6 col-sm-2 small-margin-top pull-right"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params() . $params) . '" class="btn btn-sm cursor-pointer no-margin-left btn-success">' .  IMAGE_BUTTON_REVIEWS . '</a>'; ?></div>
	  <div style="clear:both"></div>
<?php
		}
?>

</div>
    <div class="col-sm-12 col-lg-12 no-padding-left no-padding-right large-margin-bottom"><!--newtabstart12div-->
	<div class="col-sm-12 prod-desc"><!--prod-desc Started-->
	     <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-description"><?php echo TEXT_TAB_PRODUCT_INFO;?></a></li>
<?php
$other_tab = 1;
$extra_desc_tab_content = '';
if (defined('MODULE_ADDONS_TABS_STATUS') && MODULE_ADDONS_TABS_STATUS == 'True') {
$product_tabs_query = tep_db_query("select products_tab_2, products_tab_3, products_tab_4 from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_info['products_id'] . "'");
$product_tab = tep_db_fetch_array($product_tabs_query);
    if(tep_not_null($product_tab['products_tab_2'])){
    	echo '<li><a data-toggle="tab" href="#tab-description2">'. (tep_not_null(TEXT_PRODUCTS_TAB_2_TITLE ) ? TEXT_PRODUCTS_TAB_2_TITLE : ' &nbsp; ') .'</a></li>';
    	$extra_desc_tab_content .= '<div id="tab-description2" class="tab-pane">'. cre_clean_product_description($product_tab['products_tab_2']) .'</div>';
    }
    if(tep_not_null($product_tab['products_tab_3'])){
    	echo '<li><a data-toggle="tab" href="#tab-description3">'. (tep_not_null(TEXT_PRODUCTS_TAB_3_TITLE ) ? TEXT_PRODUCTS_TAB_3_TITLE : ' &nbsp; ') .'</a></li>';
    	$extra_desc_tab_content .= '<div id="tab-description3" class="tab-pane">'. cre_clean_product_description($product_tab['products_tab_3']) .'</div>';
    }
    if(tep_not_null($product_tab['products_tab_4'])){
    	echo '<li><a data-toggle="tab" href="#tab-description4">'. (tep_not_null(TEXT_PRODUCTS_TAB_4_TITLE ) ? TEXT_PRODUCTS_TAB_4_TITLE : ' &nbsp; ') .'</a></li>';
    	$extra_desc_tab_content .= '<div id="tab-description4" class="tab-pane">'. cre_clean_product_description($product_tab['products_tab_4']) .'</div>';
    }
}
?>

            <li><a data-toggle="tab" href="#tab-manufacture"><?php echo TEXT_TAB_PRODUCT_MANUFACTURER;?></a></li>
            <li><a data-toggle="tab" href="#extra_info"><?php echo TEXT_TAB_PRODUCT_EXTRA_INFO;?></a></li>
            <li><a data-toggle="tab" href="#tab-review">Review &nbsp
		 		                <?php
								 $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "'");
								 $reviews = tep_db_fetch_array($reviews_query);

		 		                if ($reviews['count'] > 0) {
		 		                   echo '<span class="main">(' . $reviews['count'] .')</span>';
		 		                 } else {
		 		                   echo '<span class="main">(0)</span><br>';
		 		                 }
								 ?></a></li>
          </ul>
          <div class="tab-content">
            <div id="tab-description" class="tab-pane active">
				  <table cellpadding="0" cellspacing="0" border="0">
					<tr>
					  <td class="main">
					  <?php
				echo '<p>' .  cre_clean_product_description($product_info['products_description']) . '</p>';
			if (tep_not_null($product_info['products_url'])) {
				 echo '<br>' . sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&amp;goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)) . '</br>';
				 }
				 ?>
				 </td>
				 </tr>
          </table>
	     </div>
	     <?php echo $extra_desc_tab_content; ?>
	     <div id="tab-review" class="tab-pane">
	     		            <?php
		 		                 $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "'");
		 		                 $reviews = tep_db_fetch_array($reviews_query);
		 		                 if ($reviews['count'] > 0) {
		 		                   echo '<span class="main">' . TEXT_CURRENT_REVIEWS . ' ' . $reviews['count'];
		 		                   echo '<span class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . (int)$_GET['products_id']) . '">' . BOX_REVIEWS_READ_REVIEW . '</a></span><br>';
		 		                 } else {
		 		                   echo '<span class="main">' . BOX_REVIEWS_NO_REVIEWS . '</span><br>';
		 		                 }
		 		                 echo '<span class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . (int)$_GET['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a></span><br>';
		 		                 echo '<br><br>';?>


	     </div>
         <div id="tab-manufacture" class="tab-pane">
        <?php
        $product_manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$product_info['products_id'] . "' and p.manufacturers_id = m.manufacturers_id");
        if (tep_db_num_rows($product_manufacturer_query)) {
        ?>
        <div class="tab-page" id="product_manufacturer">
          <script type="text/javascript">tp1.addTabPage( document.getElementById( "product_manufacturer" ) );</script>
          <table cellpadding="0" cellspacing="0" border="0" style="width:100%;font-size:16px;">
            <tr>
              <td style="padding-left:8px;"><?php
        while ($manufacturer = tep_db_fetch_array($product_manufacturer_query)) {
        if (tep_not_null($manufacturer['manufacturers_image']))
        echo tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name']) . '<br> <br>';
        echo '<strong>' . BOX_HEADING_MANUFACTURER_INFO . '</strong><br>';
        if (tep_not_null($manufacturer['manufacturers_url'])) {
          echo '<span class="main">&bull; <a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer['manufacturers_id']) . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a></span><br>';
        }
        echo '<span class="main">&bull; <a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . '</span><br>';
        }
        ?>     </td>
            </tr>
          </table>
        </div>
        <?php
        }
        ?>
	    </div>
        <div id="extra_info" class="tab-pane">
		        <div class="tab-page" id="product_extra_info">
		          <script type="text/javascript">tp1.addTabPage( document.getElementById( "product_extra_info" ) );</script>
		          <table cellpadding="0" cellspacing="0" border="0" style="width:100%;font-size:16px;">
		            <tr>
		              <td style="padding-left:8px;"><?php
		                 if (tep_not_null($product_info['products_url'])) {
		                     echo '<span class="main">' . sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT,  'action=url&amp;goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)) . '</span><br>';
		                 }

		                 echo '<span class="main"><strong>' . TAB_EXTRA_INFORMATIONS . '</strong></span><br>';
		                 if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
		                   echo '<span class="main">' . sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])) . '</span>';
		                 } else {
		                   echo '<span class="main">' . sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])) . '</span>';
		                 }
		                 if (isset($_SESSION['customer_id'])) {
		                   $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
		                   $check = tep_db_fetch_array($check_query);
		                   $notification_exists = (($check['count'] > 0) ? true : false);
		                 } else {
		                   $notification_exists = false;
		                 }
		                 if ($notification_exists == true) {
		                   echo '<br><span class="main"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY_REMOVE, tep_get_products_name((int)$_GET['products_id'])) .'</a></span><br>';
		                 } else {
		                   echo '<br><span class="main"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY, tep_get_products_name((int)$_GET['products_id'])) .'</a></span><br>';
		                 }
		                 echo '<span class="main"><a href="' . tep_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . (int)$_GET['products_id'], 'NONSSL') . '">' . BOX_TELL_A_FRIEND_TEXT . '</a></span><br>';
		                 ?>
		              </td>
		            </tr>
		          </table>
		        </div>
        </div>
        </div>

	</div><!--prod-desc finished-->
 </div><!--newtabend12div-->

<div class="clearfix"></div>
<div class="col-sm-12 col-lg-12">
  <?php
    if (SHOW_PRICE_BREAK_TABLE == 'true') {
    ?>
    <?php
      //include_once(DIR_WS_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
        } else {
            require(DIR_WS_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
        }

    }
    if ( (USE_CACHE == 'true') && !SID) {
      echo tep_cache_also_purchased(3600);
//      include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS);
        } else {
            require(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
        }
    } else {
      //include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
        } else {
            require(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
        }

      //include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
        } else {
           // require(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
        }
    }
  }
  // product info page bottom
  echo $cre_RCI->get('productinfotabs', 'bottom');
  ?>

 </div>
</div>
</form>
<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
?>