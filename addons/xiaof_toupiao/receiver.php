<?php
defined('IN_IA') or exit('Access Denied');
class Xiaof_toupiaoModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        global $_W;
        $l11111l11ll111ll1l11lll11lll11l = $this->message['type'];
        if ($l11111l11ll111ll1l11lll11lll11l == "unsubscribe") {
            $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT `id` as sid FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
                ":uniacid" => $_W['uniacid']
            ));
            $l1lll11l11111ll11111l1l1ll11111 = $ll11l11llllll11l1111111l11llll1 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = $ll111111l1ll11lll1l1l1111lll11l['sid'];
            }
            $lll1l1ll111l11l111l11lll1111ll1 = pdo_fetchall("SELECT `sid` FROM " . tablename('xiaof_toupiao_acid') . " WHERE `acid` = :acid", array(
                ":acid" => $_W['uniacid']
            ));
            foreach ($lll1l1ll111l11l111l11lll1111ll1 as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = $ll111111l1ll11lll1l1l1111lll11l['sid'];
            }
            $l1lll11l11111ll11111l1l1ll11111 = array_unique($l1lll11l11111ll11111l1l1ll11111);
            $ll1111ll11111111l111l1l1l1111ll = pdo_fetchall("SELECT `id` as sid, `data` FROM " . tablename('xiaof_toupiao_setting') . " WHERE `id` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "') AND `unfollow` = '1'");
            foreach ($ll1111ll11111111l111l1l1l1111ll as $ll111111l1ll11lll1l1l1111lll11l) {
                $l11ll11lllllll1l1llll11ll11ll1l = iunserializer($ll111111l1ll11lll1l1l1111lll11l['data']);
                if (strtotime($l11ll11lllllll1l1llll11ll11ll1l['end']) > time()) {
                    $ll11l11llllll11l1111111l11llll1[] = $ll111111l1ll11lll1l1l1111lll11l['sid'];
                }
            }
            if (count($ll11l11llllll11l1111111l11llll1) >= 1) {
                $lll1l111l111l11ll1l11l1l1llllll = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_log') . " WHERE  `sid` in ('" . implode("','", $ll11l11llllll11l1111111l11llll1) . "') AND `valid` = '1' AND `openid` = '" . $this->message['from'] . "'");
                $ll1l11ll11lllll11l11ll11111l1l1 = array();
                foreach ($lll1l111l111l11ll1l11l1l1llllll as $ll111111l1ll11lll1l1l1111lll11l) {
                    if (!isset($ll1l11ll11lllll11l11ll11111l1l1[$ll111111l1ll11lll1l1l1111lll11l['pid']])) {
                        $ll1l11ll11lllll11l11ll11111l1l1[$ll111111l1ll11lll1l1l1111lll11l['pid']] = 1;
                    } else {
                        $ll1l11ll11lllll11l11ll11111l1l1[$ll111111l1ll11lll1l1l1111lll11l['pid']] = $ll1l11ll11lllll11l11ll11111l1l1[$ll111111l1ll11lll1l1l1111lll11l['pid']] + 1;
                    }
                    pdo_query("UPDATE " . tablename("xiaof_toupiao_log") . " SET `valid` = '2' WHERE `id` = '" . $ll111111l1ll11lll1l1l1111lll11l['id'] . "'");
                }
                foreach ($ll1l11ll11lllll11l11ll11111l1l1 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                    $l1l1lll11l111l111ll1l11l1111ll1 = "`good` = good-" . $ll111111l1ll11lll1l1l1111lll11l;
                    pdo_query("UPDATE " . tablename("xiaof_toupiao") . " SET " . $l1l1lll11l111l111ll1l11l1111ll1 . " WHERE `id` = '" . $l1lll11llll11ll1lllllllll1l1l11 . "'");
                }
            }
        } elseif ($l11111l11ll111ll1l11lll11lll11l == "subscribe") {
            if (isset($this->module['config']['openweixin']) && $this->module['config']['openweixin'] == "1") {
                load()->classs('weixin.account');
                $l11l11l1111111l1l11l1lll1l11l11 = WeixinAccount::create($_W['acid']);
                $l11ll1l1ll11l1l1l111111l1111ll1 = $l11l11l1111111l1l11l1lll1l11l11->fetch_token();
                $ll1lll1llll1ll1ll1lll111l111ll1 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $l11ll1l1ll11l1l1l111111l1111ll1 . "&openid=" . $this->message['from'] . "&lang=zh_CN";
                $l1ll1ll11ll1ll11111ll1lllllll11 = file_get_contents($ll1lll1llll1ll1ll1lll111l111ll1);
                $l1ll1ll11ll1ll11111ll1lllllll11 = substr(str_replace('\"', '"', json_encode($l1ll1ll11ll1ll11111ll1lllllll11)), 1, -1);
                $l1l111l11l1llllll1lll1l11ll1ll1 = @json_decode($l1ll1ll11ll1ll11111ll1lllllll11, true);
                if (isset($l1l111l11l1llllll1lll1l11ll1ll1['unionid'])) {
                    pdo_update("mc_mapping_fans", array(
                        "unionid" => $l1l111l11l1llllll1lll1l11ll1ll1['unionid']
                    ), array(
                        "openid" => $this->message['from']
                    ));
                }
            }
        }
    }
}