<?php
/*
  $Id: footer.php,v 1.1.1.1 2004/03/04 23:39:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Chain Reaction Works, Inc
  
  Copyright &copy; 2003-2006
  
  Last Modified By : $Author$
  Last Modified On : $LastChangeDate$
  Latest Revision :  $Revision: 3529 $
  
*/
// RCI top
echo $cre_RCI->get('footer', 'top');
?>
<div id="foot">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="foot-content">
  <tr>
    <td align="center" valign="top"><a href="http://www.loadedcommerce.com/" target="_blank"><?php echo tep_image(DIR_WS_IMAGES . 'LoadedCommerceSiteLogo.png', 'Loaded Commerce') ?></a></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="foot-content">
  <tr>
    <td align="center" class="smallText"><?php echo FOOTER_TEXT_BODY;?>
    </td>
  </tr>
</table>
</div>
<?php
// RCI bottom
echo $cre_RCI->get('footer', 'bottom');
?>
<!-- warnings //-->
<div style="position:absolute; top:0; width:100%;">
<?php require(DIR_WS_INCLUDES . 'warnings.php'); ?>
</div>
<!-- warning_eof //-->