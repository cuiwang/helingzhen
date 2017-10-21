<?php
echo CLIENT_IP . "==";
echo $serverip = $this->getServerIP() . '==';
echo $_SERVER['HTTP_CLIENT_IP'];
function getRealIpAddr()
{
    if (!emptyempty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!emptyempty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}