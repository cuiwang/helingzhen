<?php
defined('IN_IA') or exit('Access Denied');


function input_csv($handle) {
	$out = array ();
	$n = 0;
	while ($data = fgetcsv($handle, 10000)) {
		$num = count($data);
		for ($i = 0; $i < $num; $i++) {
			$out[$n][$i] = $data[$i];
		}
		$n++;
	}
	return $out;
}
function replacestr($str)
{
	$result = str_replace('=','',$str);
	$result = str_replace('"','',$result);
	$result = str_replace('\'','',$result);

	return $result;
}