<?php

/**
 * Created by PhpStorm.
 * User: stevezheng
 * Date: 16/4/12
 * Time: 00:33
 */
define('YIKE_TEMPLATE', 'huiyigou');

class Yike_Red_packet {
    public function __construct() {
        $this->module_name = 'yike_red';
    }

    /**
     * 进行返利操作
     * @param $user
     * @param $is_upgrade
     * @param $tplname
     * @return bool
     */
    function payback($user, $is_upgrade, $tplname) {
        global $_W;
        //查询返利配置
        $settings = pdo_getall('yike_red_packet_level', array('uniacid' => $_W['uniacid']));

        //用户升级的等级
        $user_level = intval($user['inviter_level']);

        $user_list = [
            $user['level1'],
            $user['level2'],
            $user['level3'],
            $user['level4'],
            $user['level5'],
            $user['level6'],
            $user['level7'],
            $user['level8'],
//            $user['level9']
        ];

        $money = 0;
        if ($user_level == 1) {
            $level_setting = $settings[0];
            $money = floatval($level_setting['other_money']);
        }

        if ($user_level == 2) {
            $level_setting = $settings[1];
            $money = floatval($level_setting['other_money']);
        }

        if ($user_level == 3) {
            $level_setting = $settings[2];
            $money = floatval($level_setting['other_money']);
        }

        if (YIKE_TEMPLATE == 'huiyigou') {
            foreach ($user_list as $key => $now_user) {
                if ($now_user != '0') {
                    if ($money > 0 && $key >= $user_level) {
                        $_tmp = pdo_get('yike_red_packet_user', array('uniacid'=>$_W['uniacid'], 'uid'=>$now_user));
                        $_tmp_level = $_tmp['inviter_level'];
                        if (intval($_tmp_level) >= $user_level){
                            $this->rechargeEweiShop($now_user, $money);
                        }
                    }
                }
            }
        } else {
            foreach ($user_list as $key => $now_user) {
                if ($now_user != '0') {
                    if ($money > 0 && $key >= $user_level) {
                        $result1 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and uid = :uid and inviter_level >= :user_level', array(':uniacid' => $_W['uniacid'], ':uid' => $now_user, ':money' => $money, ':user_level' => $user_level));
                        if ($result1) {
                            $_result1 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $now_user, 'money' => floatval($money), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 1));
                        }
                    }
                }
            }
        }

//        }

        //银卡
        if ($user_level == 1) {
            $level_setting = $settings[0];
            $other_money = floatval($level_setting['other_money']);
            //1级的奖金
            $level1 = $user['level1'];
            if ($level1 != '0') {
                $user1 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level1']));
                $user1_level = intval($user1['inviter_level']);

                //1级的等级必须大于用户升级的等级
                if ($user1_level >= $user_level) {
                    $count1 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level1=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level1']));

                    $money1 = floatval($level_setting['level1_money']);
                    if ($money1 > 0 && ($count1 < intval($level_setting['level1_count']) || intval($level_setting['level1_count'] == -1))) {
                        $result1 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user1['id'], ':money' => $money1));
                        $_result1 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user1['uid'], 'money' => floatval($money1), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 1));
                    }
                }
            }

            //2级的奖金
            $level2 = $user['level2'];
            if ($level2 != '0') {
                $user2 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level2']));
                $user2_level = intval($user2['inviter_level']);

                //2级的等级必须大于用户升级的等级
                if ($user2_level >= $user_level) {
                    $count2 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level2=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level2']));

                    $money2 = floatval($level_setting['level2_money']);
                    if ($money2 > 0 && ($count2 < intval($level_setting['level2_count']) || intval($level_setting['level2_count'] == -1))) {
                        $result2 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user2['id'], ':money' => $money2));
                        $_result2 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user2['uid'], 'money' => floatval($money2), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 2));
                    }
                }
            }

            //3级的奖金
            $level3 = $user['level3'];
            if ($level3 != '0') {
                $user3 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level3']));
                $user3_level = intval($user3['inviter_level']);

                //1级的等级必须大于用户升级的等级
                if ($user3_level >= $user_level) {
                    $count3 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level3=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level3']));

                    $money3 = floatval($level_setting['level3_money']);
                    if ($money3 > 0 && ($count3 < intval($level_setting['level3_count']) || intval($level_setting['level3_count'] == -1))) {
                        $result3 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user3['id'], ':money' => $money3));
                        $_result3 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user3['uid'], 'money' => floatval($money3), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 3));
                    }
                }
            }
        }

        //金卡
        if ($user_level == 2) {
            $level_setting = $settings[1];
            $other_money = floatval($level_setting['other_money']);
            //1级的奖金
            $level1 = $user['level1'];
            if ($level1 != '0') {
                $user1 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level1']));
                $user1_level = intval($user1['inviter_level']);

                //1级的等级必须大于用户升级的等级
                if ($user1_level >= $user_level) {
                    $count1 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level1=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level1']));

                    $money1 = floatval($level_setting['level1_money']);
                    if ($money1 > 0 && ($count1 < intval($level_setting['level1_count']) || intval($level_setting['level1_count'] == -1))) {
                        $result1 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user1['id'], ':money' => $money1));
                        $_result1 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user1['uid'], 'money' => floatval($money1), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 1));
                    }
                }
            }

            //2级的奖金
            $level2 = $user['level2'];
            if ($level2 != '0') {
                $user2 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level2']));
                $user2_level = intval($user2['inviter_level']);

                //2级的等级必须大于用户升级的等级
                if ($user2_level >= $user_level) {
                    $count2 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level2=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level2']));

                    $money2 = floatval($level_setting['level2_money']);
                    if ($money2 > 0 && ($count2 < intval($level_setting['level2_count']) || intval($level_setting['level2_count'] == -1))) {
                        $result2 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user2['id'], ':money' => $money2));
                        $_result2 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user2['uid'], 'money' => floatval($money2), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 2));
                    }
                }
            }

            //3级的奖金
            $level3 = $user['level3'];
            if ($level3 != '0') {
                $user3 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level3']));
                $user3_level = intval($user3['inviter_level']);

                //1级的等级必须大于用户升级的等级
                if ($user3_level >= $user_level) {
                    $count3 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level3=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level3']));

                    $money3 = floatval($level_setting['level3_money']);
                    if ($money3 > 0 && ($count3 < intval($level_setting['level3_count']) || intval($level_setting['level3_count'] == -1))) {
                        $result3 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user3['id'], ':money' => $money3));
                        $_result3 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user3['uid'], 'money' => floatval($money3), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 3));
                    }
                }
            }

        }

        //钻石
        if ($user_level == 3) {
            $level_setting = $settings[2];
            $other_money = floatval($level_setting['other_money']);
            //1级的奖金
            $level1 = $user['level1'];
            if ($level1 != '0') {
                $user1 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level1']));
                $user1_level = intval($user1['inviter_level']);

                //1级的等级必须大于用户升级的等级
                if ($user1_level >= $user_level) {
                    $count1 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level1=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level1']));

                    $money1 = floatval($level_setting['level1_money']);
                    if ($money1 > 0 && ($count1 < intval($level_setting['level1_count']) || intval($level_setting['level1_count'] == -1))) {
                        $result1 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user1['id'], ':money' => $money1));
                        $_result1 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user1['uid'], 'money' => floatval($money1), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 1));
                    }
                }
            }

            //2级的奖金
            $level2 = $user['level2'];
            if ($level2 != '0') {
                $user2 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level2']));
                $user2_level = intval($user2['inviter_level']);

                //2级的等级必须大于用户升级的等级
                if ($user2_level >= $user_level) {
                    $count2 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level2=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level2']));

                    $money2 = floatval($level_setting['level2_money']);
                    if ($money2 > 0 && ($count2 < intval($level_setting['level2_count']) || intval($level_setting['level2_count'] == -1))) {
                        $result2 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user2['id'], ':money' => $money2));
                        $_result2 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user2['uid'], 'money' => floatval($money2), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 2));
                    }
                }
            }

            //3级的奖金
            $level3 = $user['level3'];
            if ($level3 != '0') {
                $user3 = pdo_get('yike_red_packet_user', array('uniacid' => $_W['uniacid'], 'uid' => $user['level3']));
                $user3_level = intval($user3['inviter_level']);

                //1级的等级必须大于用户升级的等级
                if ($user3_level >= $user_level) {
                    $count3 = pdo_fetchcolumn('select count(*) from ' . tablename('yike_red_packet_user') . ' where uniacid=:uniacid and level3=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $user['level3']));

                    $money3 = floatval($level_setting['level3_money']);
                    if ($money3 > 0 && ($count3 < intval($level_setting['level3_count']) || intval($level_setting['level3_count'] == -1))) {
                        $result3 = pdo_query('update ' . tablename('yike_red_packet_user') . ' set money=money + :money where uniacid=:uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $user3['id'], ':money' => $money3));
                        $_result3 = pdo_insert('yike_red_packet_rebates', array('uniacid' => $_W['uniacid'], 'uid' => $user3['uid'], 'money' => floatval($money3), 'status' => 1, 'created_time' => time(), 'remark' => $user['uid'], 'level' => 3));
                    }
                }
            }
        }
        return true;
    }

    function send($rootca, $key, $cert, $id = 10, $openid = 'oL5Tft0KFMTADBLnBUAL3xOmLCaA', $money = 1.1, $nickname = '公众平台', $wishing = '恭喜发财', $remark = '恭喜发财') {
        global $_W;
        $setting = uni_setting($_W['uniacid'], array(
            'payment'
        ));
        if (!empty($_W['account']['name'])) {
            $nickname = $_W['account']['name'];
        }
        $wechat = $setting['payment']['wechat'];
        $fee = floatval($money) * 100;
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] = date('Ymd') . sprintf('%010d', $id);
        $pars['mch_id'] = $wechat['mchid'];
        $pars['wxappid'] = $_W['account']['key'];
        $pars['nick_name'] = $nickname;
        $pars['send_name'] = $nickname;
        $pars['re_openid'] = $openid;
        $pars['total_amount'] = $fee;
        $pars['min_value'] = $pars['total_amount'];
        $pars['max_value'] = $pars['total_amount'];
        $pars['total_num'] = 1;
        $pars['wishing'] = $wishing;
        $pars['client_ip'] = gethostbyname($_SERVER["HTTP_HOST"]);
        $pars['act_name'] = '现金红包';
        $pars['remark'] = $remark;
        $pars['logo_imgurl'] = $_W['attachurl_local'] . '/headimg_' . $_W['uniacid'] . '.jpg';
        $pars['share_content'] = '谢谢分享';
        $pars['share_imgurl'] = $_W['attachurl_local'] . '/headimg_' . $_W['uniacid'] . '.jpg';
        $pars['share_url'] = $_W['siteroot'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$wechat['apikey']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();

        $certfile = IA_ROOT . "/addons/yike_red/cert/" . random(128);
        file_put_contents($certfile, $cert);
        $keyfile = IA_ROOT . "/addons/yike_red/cert/" . random(128);
        file_put_contents($keyfile, $key);
        $rootfile = IA_ROOT . "/addons/yike_red/cert/" . random(128);
        file_put_contents($rootfile, $rootca);
        $extras['CURLOPT_SSLCERT'] = $certfile;
        $extras['CURLOPT_SSLKEY'] = $keyfile;
        $extras['CURLOPT_CAINFO'] = $rootfile;

        load()->func('communication');
        $procResult = null;
        $resp = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }
        return $procResult;
    }

    public function send_coupon($id, $openids, $send_total) {
        global $_W, $_GPC;
        if (!empty($id)) {
            $coupon = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_coupon') . ' WHERE id=:id and uniacid=:uniacid ', array(
                ':id' => $id,
                ':uniacid' => $_W['uniacid']
            ));
            if (empty($coupon)) {
                message('未找到优惠券!', '', 'error');
            }
        }
        $openids = explode(",", trim($openids));
        $plog = "发放优惠券 ID: {$id} 方式: 指定 OPENID 人数: " . count($openids);
        $mopenids = array();
        foreach ($openids as $openid) {
            $mopenids[] = "'" . str_replace("'", "''", $openid) . "'";
        }
        if (empty($mopenids)) {
            message('未找到发送的会员!', '', 'error');
        }
        $members = pdo_fetchall('select id,openid,nickname from ' . tablename('ewei_shop_member') . ' where openid in (' . implode(',', $mopenids) . ") and uniacid={$_W['uniacid']}");
        if (empty($members)) {
            message('未找到发送的会员!', '', 'error');
        }
        $time = time();
        foreach ($members as $m) {
            for ($i = 1; $i <= $send_total; $i++) {
                $log = array(
                    'uniacid' => $_W['uniacid'],
                    'openid' => $m['openid'],
                    'logno' => m('common')->createEweiShopNO('coupon_log', 'logno', 'CC'),
                    'couponid' => $id,
                    'status' => 1,
                    'paystatus' => -1,
                    'creditstatus' => -1,
                    'createtime' => $time,
                    'getfrom' => 0
                );
                pdo_insert('ewei_shop_coupon_log', $log);
                $logid = pdo_insertid();
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'openid' => $m['openid'],
                    'couponid' => $id,
                    'gettype' => 0,
                    'gettime' => $time,
                    'senduid' => $_W['uid']
                );
                pdo_insert('ewei_shop_coupon_data', $data);
            }
        }
    }

    public function createEweiShopNO($table, $field, $prefix)
    {
        $billno = date('YmdHis') . random(6, true);
        while (1) {
            $count = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_' . $table) . " where {$field}=:billno limit 1", array(
                ':billno' => $billno
            ));
            if ($count <= 0) {
                break;
            }
            $billno = date('YmdHis') . random(6, true);
        }
        return $prefix . $billno;
    }

    public function rechargeEweiShop($uid, $money) {
        global $_W;
        load()->model('mc');
        $result = pdo_get('yike_red_packet_user', array('uniacid'=>$_W['uniacid'], 'uid'=>$uid));
        $openid = $result['openid'];
        $logno = $this->createEweiShopNO("member_log", "logno", "RC");
        $data = array("openid" => $openid, "logno" => $logno, "uniacid" => $_W["uniacid"], "type" => '0', "createtime" => TIMESTAMP, "status" => "1", "title" => "会员返利", "money" => $money, "rechargetype" => "system",);
        pdo_insert("ewei_shop_member_log", $data);
        $update = pdo_query('update '. tablename('mc_members') . ' set credit2 = credit2 + :money where uniacid = :uniacid and uid = :uid', array(':money'=>$money, ':uniacid'=>$_W['uniacid'], ':uid'=>$uid));
    }
}
