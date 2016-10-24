<?php
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
$arr_addons = lc_get_all_directory('addons/');
foreach($arr_addons as $addon_dir)
{
	if(file_exists('addons/'.$addon_dir.'/catalog/functions'))
	{
		$arr_addons_func_files = lc_get_all_files('addons/'.$addon_dir.'/catalog/functions/');
		foreach($arr_addons_func_files as $functions)
		{
			include('addons/'.$addon_dir.'/catalog/functions/'.$functions);
		}
	}
}
function lc_addon_init()
{
	if(function_exists('addon_modules_init'))
		addon_modules_init();
}

function lc_check_addon_class($filename)
{
	if(file_exists('addons/'. SYSTEM_ADDON .'/catalog/classes/'.$filename))
		require('addons/'. SYSTEM_ADDON .'/catalog/classes/'.$filename);
	else
		require(DIR_FS_CLASSES . $filename);
}
function lc_check_addon_modules($filename)
{
	if(file_exists('addons/'. SYSTEM_ADDON .'/catalog/modules/'.$filename))
		require('addons/'. SYSTEM_ADDON .'/catalog/modules/'.$filename);
	else
		require(DIR_WS_MODULES . $filename);
}
function lc_check_addon_core_include($filename)
{
	if(file_exists('addons/'. SYSTEM_ADDON .'/catalog/'.$filename))
		require('addons/'. SYSTEM_ADDON .'/catalog/'.$filename);
}

?>