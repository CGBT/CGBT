<?php
if ('127.0.0.1' == $_SERVER['SERVER_ADDR'])
{
	ini_set('display_errors', '1');
	error_reporting(E_ALL | E_STRICT);
}
date_default_timezone_set('Asia/Shanghai');

header("content-type:text/html;charset=utf-8");

$dict_ext = explode(",", "mysqli,iconv,mcrypt,curl,mbstring,json");

foreach ($dict_ext as $ext)
{
	if (!check_ext_loaded($ext))
	{
		echo "miss ext: $ext <br />\n";
	}
}

function check_ext_loaded($ext)
{
	$all_exts = get_loaded_extensions();
	if (in_array($ext, $all_exts) !== false)
	{
		return true;
	}
	return false;
}
?>