<?php
/**
 * 招商加盟模块
 */
function autoload($class_name) 
{
	$file = MODULE_ROOT . "class/{$class_name}
.class.php";
if (is_file($file)) 
{
	require $file;
}
return false;
}
function inputRaw($jsonDecode = true) 
{
$post = file_get_contents('php://input');
if ($jsonDecode) 
{
	$post = @json_decode($post, true);
}
return $post;
}
function coll_key($ds, $key) 
{
if (!empty($ds) && !empty($key)) 
{
	$ret = array();
	foreach ($ds as $row) 
	{
		$ret[$row[$key]] = $row;
	}
	return $ret;
}
return array();
}
function coll_neaten($ds, $key) 
{
if (!empty($ds) && !empty($key)) 
{
	$ret = array();
	foreach ($ds as $row) 
	{
		$ret[] = $row[$key];
	}
	return $ret;
}
return array();
}
function coll_walk($ds, $callback, $key = null) 
{
if (!empty($ds) && is_callable($callback)) 
{
	$ret = array();
	if (!empty($key)) 
	{
		foreach ($ds as $k => $row) 
		{
			$ret[$k] = call_user_func($callback, $row[$key]);
		}
	}
	else 
	{
		foreach ($ds as $k => $row) 
		{
			$ret[$k] = call_user_func($callback, $row);
		}
	}
	return $ret;
}
return array();
}
function coll_elements($keys, $src, $default = false) 
{
$return = array();
if (!is_array($keys)) 
{
	$keys = array($keys);
}
foreach ($keys as $key) 
{
	if (isset($src[$key])) 
	{
		$return[$key] = $src[$key];
	}
	else 
	{
		$return[$key] = $default;
	}
}
return $return;
}
function util_limit($num, $downline, $upline, $returnNear = true) 
{
$num = intval($num);
$downline = intval($downline);
$upline = intval($upline);
if ($num < $downline) 
{
	return empty($returnNear) ? false : $downline;
}
elseif ($num > $upline) 
{
	return empty($returnNear) ? false : $upline;
}
else 
{
	return empty($returnNear) ? true : $num;
}
}
function util_json($val) 
{
header('Content-Type: application/json');
echo json_encode($val);
}
function dump($vars, $label = '', $return = false) 
{
if (ini_get('html_errors')) 
{
	$content = "<pre>\n";
	if ($label != '') 
	{
		$content .= "<strong>{$label}
	:</strong>\n";
}
$content .= htmlspecialchars(print_r($vars, true));
$content .= "\n</pre>\n";
}
else 
{
$content = $label . " :\n" . print_r($vars, true);
}
if ($return) 
{
return $content;
}
echo $content;
return null;
}
function my_tablename($name, $flag = 1) 
{
if ($flag == 1) 
{
return tablename(MODULE_NAME . '_' . $name);
}
else 
{
return MODULE_NAME . '_' . $name;
}
}
function is_development() 
{
if ($_SERVER['SERVER_ADDR'] == '127.0.0.1' || substr($_SERVER['SERVER_ADDR'], 0, 7) == '192.168') 
{
return true;
}
return false;
}

?>