<?php
/**
 * [MicroEngine Mall] Copyright (c) 2014 012wz.com
 * MicroEngine Mall is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

$url = $_GPC['url'];
require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
$errorCorrectionLevel = "L";
$matrixPointSize = "5";
QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
exit();
