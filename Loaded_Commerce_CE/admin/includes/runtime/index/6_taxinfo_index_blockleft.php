<?php
/*
  $Id: 6_taxinfo_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_TAX_RATES_STATUS') && ADMIN_BLOCKS_TAX_RATES_STATUS == 'true'){
  //Tax Zone Code
  $zones="";
  $zone_query = tep_db_query("SELECT distinct geo_zone_name, tax_rate,b.geo_zone_id
                                from ".TABLE_ZONES_TO_GEO_ZONES." a, ".TABLE_GEO_ZONES." b, ".TABLE_TAX_RATES." c
                              WHERE a.geo_zone_id = b.geo_zone_id
                                and a.geo_zone_id = tax_zone_id");
  $tax_contents="";
  while ($zone_list = tep_db_fetch_array($zone_query)) {
    $tax_contents.="<li>".$zone_list['geo_zone_name'].' ('.$zone_list['tax_rate'].'%) ';
    //Getting Further Zone Names
    $subzone_query=tep_db_query("SELECT countries_name, zone_name
                                   from ".TABLE_ZONES_TO_GEO_ZONES." a, ".TABLE_COUNTRIES." d, ".TABLE_ZONES." e
                                 WHERE d.countries_id = a.zone_country_id AND e.zone_id = a.zone_id AND geo_zone_id = ".$zone_list['geo_zone_id']."
                                 ORDER BY countries_name, zone_name");
    while ($subzone_list = tep_db_fetch_array($subzone_query)) { 
      $tax_contents.="<br>&nbsp;-".$subzone_list['countries_name'].':'.$subzone_list['zone_name']."\n";
    }
    $tax_contents .="</li>\n";
  }
  ?>
  <!-- begin taxinfo -->
  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title"><?php echo BLOCK_TITLE_TAX_RATES;?></h4>
      </div>
      <div class="panel-body bg-white">
        <ul class="list-unstyled">
          <?php echo $tax_contents;?>
        </ul>
      </div>
  </div>
  <!-- end taxinfo -->
  <?php
}
?>