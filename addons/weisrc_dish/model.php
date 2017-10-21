<?php
defined('IN_IA') or exit('Access Denied');

function getServerIP(){
    return gethostbyname($_SERVER["SERVER_NAME"]);
}
function code_compare($a, $b)
{

}

function findNum($str=''){
    $str=trim($str);
    if(empty($str)){return '';}
    $reg='/(\d{3}(\.\d+)?)/is';//匹配数字的正则表达式
    preg_match_all($reg,$str,$result); if(is_array($result)&&!empty($result)&&!empty($result[1])&&!empty($result[1][0])){
        return $result[1][0];
    }
    return '';
}

