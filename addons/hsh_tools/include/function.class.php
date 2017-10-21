<?php

if (!function_exists("urlencodeForArray")) {

    function urlencodeForArray($data) {
        if (is_array($data)) {
            return array_map('urlencodeForArray', $data);
        }
        return urlencode($data);
    }

}
if (!function_exists("htmlspecialchars_decodeForArray")) {

    function htmlspecialchars_decodeForArray($data) {
        if (is_array($data)) {
            return array_map('htmlspecialchars_decodeForArray', $data);
        }
        return htmlspecialchars_decode($data);
    }

}
if (!function_exists("returnJSON")) {

    function returnJSON($data = array(), $callback = "callback", $autoEcho = true, $type = 'cleartext') {
        if ($callback == "") {
            $callback = "callback";
        }
        if ($type != 'unicode') {
            $data = urlencodeForArray($data);
        }
        $json = json_encode($data);
        $echoSTR = "";
        if ($callback == "none") {
            $echoSTR = urldecode($json);
        } else {
            $echoSTR = $callback . "(" . urldecode($json) . ")";
        }
        if ($autoEcho) {
            echo $echoSTR;
        }
        return $echoSTR;
    }

}
if (!function_exists("toolsCheckMoble")) {

    function toolsCheckMoble($tel) {
        if (preg_match('/^(13|14|15|18|17)\d{9}$/', $tel)) {
            return true;
        } else {
            return false;
        }
    }

}