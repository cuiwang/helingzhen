<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
        global $_W, $_GPC;
        $id = $_GPC['d'] + 0;
        if ($id) {
            $sql = 'SELECT `token` FROM ' . tablename('wxauth_list') . ' WHERE `is_delete` = 0 AND `id` = :id AND status = 1';
            $token = pdo_fetchcolumn($sql, array(
                ':id' => $id
            ));
            if ($token) {
                define('MP_TOKEN', $token);
                if (checkSignatureTo()) {
                    if (strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
                        pdo_update('wxauth_list', array(
                            'is_yz' => 1
                        ) , array(
                            'is_delete' => 0,
                            'id' => $id
                        ));
                        die($_GPC['echostr']);
                    }
                    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                        $postStr = @file_get_contents('php://input');
                        if ($postStr) {
                            exec_data($postStr);
                            die;
                        }
                    }
                }
            }
        }
        echo '';
function checkSignature($info) {
        $tmpArr = $info;
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        return $tmpStr;
    }
function checkSignatureTo() {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = MP_TOKEN;
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
function exec_data($data) {
        global $_W, $_GPC;
        $id = $_GPC['d'] + 0;
        $sql = 'SELECT`id`, `name`, `url`, `desc`, `status`, `create_time`, `m_id`, `sort`, `token`FROM' . tablename('wxauth_app') . 'WHERE`m_id` = : id AND `is_delete` = 0 AND `status` = 1ORDERBYsortdesc, create_timedesc';
        $app_list = pdo_fetchall($sql, array(
            'id' => $id
        ));
        $res_data = '';
        $add_data = array(
            'm_id' => $id,
            'from_data' => $data,
            'time' => time() ,
        );
        if (!empty($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes') {
            $MpInfo = getMpInfo($id);
            $add_data['from_data'] = decryptMsg($MpInfo, $data);
        }
        foreach ($app_list as $k => $v) {
            $url = make_url($v['url'], $v['token'], false);
            $res_data = Curl($url, $add_data['from_data'], array(
                CURLOPT_HTTPHEADER => array(
                    'Content - Type : text / plain'
                )
            ) , false);
            if ($res_data && $res_data != 'success') {
                $res_arr = FromXml($res_data);
                $add_data['a_id'] = $v['id'];
                $add_data['send_data'] = $res_data;
                break;
            }
        }
        addToLog($add_data);
        echo $res_data ? $res_data : '';
    }
template('fournet/wxauth');