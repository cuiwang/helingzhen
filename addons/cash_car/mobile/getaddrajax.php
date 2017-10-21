<?php
/**
 * ajax获取地址信息
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  

/*腾讯地图经纬度转换为百度地图经纬度*/    	
$txurl = 'http://api.map.baidu.com/geoconv/v1/?coords='.$_GPC['lat'].','.$_GPC['lng'].'&ak=uszridA8UmehmbHreas4aV14&output=json';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $txurl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
$r = curl_exec($ch);
curl_close($ch);

$txres = json_decode($r);
$txres=$this->object_array($txres);


$baiduurl = 'http://api.map.baidu.com/geocoder/v2/?ak=uszridA8UmehmbHreas4aV14&location='.$txres['result'][0]['y'].','.$txres['result'][0]['x'].'&output=json&pois=0';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baiduurl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
$r = curl_exec($ch);
curl_close($ch);

$bdres = json_decode($r);
$bdres=$this->object_array($bdres);

//echo $bdres['result']['formatted_address'];exit;

$result['address'] = $bdres['result']['formatted_address'];
$result['lng'] = $txres['result'][0]['x']; //经度
$result['lat'] = $txres['result'][0]['y']; //纬度
message($result, '', 'ajax');