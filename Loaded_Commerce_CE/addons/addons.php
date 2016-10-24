<?php
//Get the Installed Addon
function lc_addon_concat($base_string, $concat_param)
{
	if($concat_param != '') {
		$arr_concat_param = explode('|', $concat_param);
		$concat_func = $arr_concat_param[0];
		$arguments = array();
		if(count($arr_concat_param) > 1) {
			unset($arr_concat_param[0]);
			$arguments = $arr_concat_param;
		}
		if(function_exists($concat_func)) {
			$base_string .= '&nbsp;&nbsp;'.$concat_func($arguments);
		}
	}
	return $base_string;
}
function lc_get_all_directory($directory)
{
	$arr_addons = array();
	if ($handle = opendir($directory)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				if(is_dir($directory.$entry))
					$arr_addons[] = $entry;
			}
		}
		closedir($handle);
	}
	return $arr_addons;
}

function lc_get_all_files($directory)
{
	$arr_addons = array();
	if ($handle = opendir($directory)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				if(!is_dir($directory.$entry))
					$arr_addons[] = $entry;
			}
		}
		closedir($handle);
	}
	return $arr_addons;
}

//Load the installed addon functions
$arr_addons = lc_get_all_directory(DIR_FS_CATALOG.'addons/');
foreach($arr_addons as $addon_dir)
{
	if(file_exists(DIR_FS_CATALOG.'addons/'.$addon_dir.'/functions'))
	{
		$arr_addons_func_files = lc_get_all_files(DIR_FS_CATALOG.'addons/'.$addon_dir.'/functions/');
		foreach($arr_addons_func_files as $functions)
		{
			include(DIR_FS_CATALOG.'addons/'.$addon_dir.'/functions/'.$functions);
		}
	}
}

function lc_addon_init()
{
	if(function_exists('addon_modules_init'))
		addon_modules_init();
}

function lc_addon_post_init()
{
	global $arr_boxes, $arr_addons;
	if(function_exists('version_check'))
		version_check();

	$arr_boxes = array();
	foreach($arr_addons as $addon_dir)
	{
		if(file_exists(DIR_FS_CATALOG.'addons/'.$addon_dir.'/boxes/'))
		{
			$arr_addons_boxes_files = lc_get_all_files(DIR_FS_CATALOG.'addons/'.$addon_dir.'/boxes/');
			foreach($arr_addons_boxes_files as $boxes_file)
			{
				include(DIR_FS_CATALOG.'addons/'.$addon_dir.'/boxes/'.$boxes_file);
				$arr_boxes[substr($boxes_file, 0, -4)] = $left_menu;
			}
		}
	}
}

function lc_load_addons($module)
{
	global $language;
	$arr_addons = lc_get_all_directory(DIR_FS_CATALOG.'addons/');

	if(isset($_GET['routes'])) {
		$arr_routes = explode('/', $_GET['routes']);
		if(isset($arr_routes[0]) && isset($arr_routes[1])) {
			include(DIR_FS_CATALOG.'addons/'. $arr_routes[0] .'/languages/'.$language.'.php');
			include(DIR_FS_CATALOG.'addons/'.$arr_routes[0].'/languages/'.$language.'/'.$arr_routes[1].'.php');
		}
	}
 
	if(trim($module) != "" && file_exists(DIR_FS_CATALOG.'addons/'. SYSTEM_ADDON .'/'.$module))
		require(DIR_FS_CATALOG.'addons/'. SYSTEM_ADDON .'/'.$module);
}
function lc_check_addons($module, $mod_segment)
{
	$retval = 0;
	if(function_exists($mod_segment))
	{
		$retval = 1;
		echo $mod_segment();
	}
	else
		$retval = 0;
	return $retval;
}
function lc_load_addon_action($module, $action)
{
	$function_name = strtolower($module.'_'.$action);
	if(function_exists($function_name))
		$function_name();
}
function lc_load_addon_function($function_name, $func_arguments)
{
	if(function_exists('lc_get_func_replacer'))
	{
		$func_name = lc_get_func_replacer($function_name);
		if(function_exists($func_name))
			return $func_name($func_arguments);
	}
}
function lc_addon_load_side_links($box, $sub_box='')
{
	global $arr_boxes;
	if(isset($arr_boxes[$box]))
	{
		if($sub_box == '')
			return $arr_boxes[$box];
		else
		{
			if(isset($arr_boxes[$box][$sub_box]))
				return (isset($arr_boxes[$box][$sub_box])?$arr_boxes[$box][$sub_box]:'');
		}		
	}
}
function lc_check_addon_class($filename)
{
	echo SYSTEM_ADDON;
	/*
	if(trim($module) != "" && file_exists(DIR_FS_CATALOG.'addons/'. SYSTEM_ADDON .'/'.$module))
		require(DIR_FS_CATALOG.'addons/'. SYSTEM_ADDON .'/'.$module);
		*/

}
?>