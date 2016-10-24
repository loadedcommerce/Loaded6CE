<?php
/*
  $Id: articles.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- articles //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_ARTICLES,
                   'link'  => tep_href_link(FILENAME_ARTICLES, 'selected_box=articles'));

  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('articles', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('articles', 'boxesbottom');
  $returned_rci_bottom .= lc_addon_load_side_links('articles');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_ARTICLES,  BOX_TOPICS_ARTICLES, 'SSL','selected_box=articles','2')  .
                                 tep_admin_files_boxes(FILENAME_ARTICLES_CONFIG,  BOX_ARTICLES_CONFIG, 'SSL','selected_box=articles','2')  .
                                 tep_admin_files_boxes(FILENAME_AUTHORS, BOX_ARTICLES_AUTHORS, 'SSL','selected_box=articles','2')  .
                                 tep_admin_files_boxes(FILENAME_ARTICLE_REVIEWS,  BOX_ARTICLES_REVIEWS, 'SSL','selected_box=articles','2')  .
                                 tep_admin_files_boxes(FILENAME_ARTICLES_XSELL,  BOX_ARTICLES_XSELL, 'SSL','selected_box=articles','2') .
                                 $returned_rci_bottom);

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
<!-- articles_eof //-->