<?php
/*
  $Id: TemplateCDSmenu_applicationtop_bottom.php,v 1.1.1.1 2016/03/04 23:40:38 kiran Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

    //Recursive function for generating the child categories and pages menu
    //Language id is set to 1 -> English Language
    function fetch_children($parent) {
        global $languages_id;
        //Query for fetching the categories id, categories name, childcategories, childpages  
        $result = tep_db_query("SELECT DISTINCT pc.categories_id, pcd.categories_name, (

            SELECT count( subpc.categories_id )
            FROM " . TABLE_PAGES_CATEGORIES . " subpc
            WHERE subpc.categories_parent_id = pc.categories_id
            ) AS childelement, (

            SELECT count( ptc.pages_id )
            FROM " . TABLE_PAGES_TO_CATEGORIES . " ptc
            WHERE ptc.categories_id = pc.categories_id
            ) AS childpages

            FROM " . TABLE_PAGES_CATEGORIES . " pc, " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " pcd
            WHERE pcd.categories_id = pc.categories_id
            AND pc.categories_status = '1' 
            AND pc.categories_parent_id = '".(int)$parent. "'
            AND pcd.language_id = '" . (int)$languages_id . "' ORDER BY pc.categories_sort_order");


        //Generating Menus/Submenus  
        if(tep_db_num_rows($result) > 0){
            while($row = tep_db_fetch_array($result)) {
                //Subcategories.   
                if($row[2] > 0){
                    echo '<li><a href="" class="dropdown-toggle" data-toggle="dropdown">'.$row['categories_name'] .'<b class="caret"></b></a>';
                    echo '<ul class="dropdown-menu">';           
                    //Pages    
                }elseif($row[3] > 0){
                    echo '<li><a href="" class="dropdown-toggle" data-toggle="dropdown">'.$row['categories_name'] .'<b class="caret"></b></a>';
                    echo '<ul class="dropdown-menu">';
                    fetch_pages($row[0]);
                    echo '</ul>'."\n";
                }else {
                    echo '<li>' . generate_category_link($row['categories_id']);
                }

                //Recursive call for generating categories/pages sub menu   
                fetch_children($row['categories_id']);
                //In case if we have both pages and categories at same level   
                if($row[2] > 0 && $row[3] > 0){
                    fetch_pages($row[0]);
                }

                //Closing Menu Structure
                if($row[2] > 0){
                    echo '</li></ul>'."\n";
                }else{
                    echo '</li>'."\n";
                }
            }
        }
    }

    //Function for fetching the pages
    function fetch_pages($catid){
        global $languages_id;
        $result = tep_db_query("SELECT ptc.pages_id, pd.pages_title
            FROM " . TABLE_PAGES_TO_CATEGORIES . " ptc, " . TABLE_PAGES_DESCRIPTION . " pd
            WHERE pd.pages_id = ptc.pages_id
            AND pd.language_id = '" . (int)$languages_id . "'
            AND ptc.categories_id = '" . $catid . "'");
        $list = array();

        while($row = tep_db_fetch_array($result)) {
            echo '<li><a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $row['pages_id'] . '&amp;CDpath=' . cre_get_cds_page_path($row['pages_id'])) .'">'.$row['pages_title'] .'</a></li>';
        }
    }

    // Build link
    function generate_category_link($id) {
        global $languages_id;
        // build the SQL
        $category_info_query = tep_db_query("SELECT ic.categories_id as 'ID', ic.categories_parent_id as 'parentID', ic.categories_sort_order as 'sort', icd.categories_name as 'name', ic.categories_url_override as 'url', ic.categories_url_override_target as 'target', ic.category_append_cdpath as 'append', 'c' as 'type' 
            FROM " . TABLE_PAGES_CATEGORIES . " ic 
            LEFT JOIN " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd 
            ON (ic.categories_id = icd.categories_id) 
            WHERE icd.language_id = '" . (int)$languages_id . "'
            AND ic.categories_id = '" . $id . "'");
        $val = tep_db_fetch_array($category_info_query);

        $this_box_link = '';
        $this_box_string = '';

        if ($val['type'] == 'c') {
            if ($val['url'] != '') {
                $separator = (strpos($val['url'], '?')) ? '&amp;' : '?';
                $this_box_link = ($val['append'] == true) ? $val['url'] . $separator . 'CDpath=' . cre_get_cds_category_path($val['ID']) : $val['url'];       
                $this_box_target = ($val['target'] != '') ? ' target="' . $val['target'] . '"' : '';
            } else {
                $this_box_link = tep_href_link(FILENAME_PAGES,'CDpath=' . cre_get_cds_category_path($val['ID']));
                $this_box_target = '';
            }
        } else {
            $this_box_link = tep_href_link(FILENAME_PAGES, 'pID=' . $val['ID'] . '&amp;CDpath=' . cre_get_cds_page_path($val['ID']));
            $this_box_target = '';
        }
        $this_box_string .= '<a href="' . $this_box_link . '"' . $this_box_target . '>';
        $this_box_string .= $val['name'];
        $this_box_string .= '</a>';


        return $this_box_string;
    }



    function generate_cds_menu($id, $dropdown = true){
        global $languages_id;       
        
     $dropdown_code = '';
     $dropdown_menu_code = '';
     if($dropdown == true){
        $dropdown_code = 'class="dropdown-toggle" data-toggle="dropdown"';
        $dropdown_menu_code = ' class="dropdown-menu"';
     }  
        //Fetch all the parent items
        $result = tep_db_query("SELECT DISTINCT pc.categories_id, pcd.categories_name, (

            SELECT count( subpc.categories_id )
            FROM " . TABLE_PAGES_CATEGORIES . " subpc
            WHERE subpc.categories_parent_id = pc.categories_id
            ) AS childelement, (

            SELECT count( ptc.pages_id )
            FROM " . TABLE_PAGES_TO_CATEGORIES . " ptc
            WHERE ptc.categories_id = pc.categories_id
            ) AS childpages

            FROM " . TABLE_PAGES_CATEGORIES . " pc, " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " pcd
            WHERE pcd.categories_id = pc.categories_id
            AND pc.categories_status = '1' 
            AND pc.categories_parent_id = '" . $id . "'
            AND pcd.language_id = '" . (int)$languages_id . "' ORDER BY pc.categories_sort_order"
        );
        //Parent Navigation menu
        while($row =  mysqli_fetch_row($result)){        
            //Generate Categories sub menu
            if($row[2] > 0){
                echo '<li><a href="" ' . $dropdown_code . '>'.$row['1'] .'<b class="caret"></b></a>'."\n";
                echo '<ul class="dropdown-menu">';   
                //Generate Pages sub menu    
            }elseif($row[3] > 0){
                echo '<li><a href="" ' . $dropdown_code . '>'.$row['1'] .'<b class="caret"></b></a>';
                echo '<ul ' . $dropdown_menu_code . '>';
                fetch_pages($row[0]);
                echo '</ul>'."\n";
                //Generate Categories menu       
            }else{           
                echo '<li>' . generate_category_link($row[0]);  
            }
            //Function to generate internal menus
            fetch_children($row[0]);

            //Closing menu
            if($row[2] > 0){
                echo '</ul>' . "\n";
            }else{
                echo '</li>'."\n";
            }
        }            
    }// function generate_cds_menu

?>