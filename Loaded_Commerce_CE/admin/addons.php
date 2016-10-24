<?php
$arr_routes = explode('/', $_GET['routes']);
if(isset($arr_routes[0]) && isset($arr_routes[1]))
{
	$addon_file_path = '../addons/'.$arr_routes[0].'/'.$arr_routes[1].'.php';
	if(file_exists($addon_file_path))
		include($addon_file_path);
}
?>