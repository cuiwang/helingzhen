<?php

if (!function_exists('curl_init')) {
  throw new Exception('Pingpp needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Pingpp needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('Pingpp needs the Multibyte String PHP extension.');
}

// Pingpp singleton
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Pingpp.php');

// Utilities
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Util/Util.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Util/Set.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Util/RequestOptions.php');

// Errors
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Error/Base.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Error/Api.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Error/ApiConnection.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Error/Authentication.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Error/InvalidRequest.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Error/RateLimit.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Error/Channel.php');

// Plumbing
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/JsonSerializable.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/PingppObject.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/ApiRequestor.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/ApiResource.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/SingletonApiResource.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/AttachedObject.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Collection.php');

// Pingpp API Resources
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Charge.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Refund.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/RedEnvelope.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Event.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Transfer.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Customer.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Source.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Card.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/Token.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/CardInfo.php');
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/SmsCode.php');

// wx_pub OAuth 2.0 method
require(IA_ROOT. '/addons/weliam_indiana/pay' . '/lib/WxpubOAuth.php');
