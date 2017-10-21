<?php

defined('IN_IA') or exit('Access Denied');

include "../addons/ewei_hotel/model.php";

class Ewei_hotelModule extends WeModule {

    public $_img_url = '../addons/ewei_hotel/template/style/img/';
    public $_css_url = '../addons/ewei_hotel/template/style/css/';
    public $_script_url = '../addons/ewei_hotel/template/style/js/';
    public $_hotel_level_config = array(5 => '五星级酒店', 4 => '四星级酒店', 3 => '三星级酒店', 2 => '两星级以下', 15 => '豪华酒店', 14 => '高档酒店', 13 => '舒适酒店', 12 => '经济型酒店',);

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('hotel2_reply') . " WHERE weid=:weid and rid = :rid limit 1", array(':weid' => $_W['uniacid'], ':rid' => $rid));
            $sql = 'SELECT id,title,description,thumb FROM ' . tablename('hotel2') . ' WHERE `weid`=:weid AND `id`=:hotelid';
            $hotel = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':hotelid' => $reply['hotelid']));
        }
        include $this->template('form');
    }
  public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            if (empty($_GPC['sendmail']) || empty($_GPC['senduser']) || empty($_GPC['sendpwd'])) {
                message('请完整填写邮件配置信息', 'refresh', 'error');
            }
            if ($_GPC['host'] == 'smtp.qq.com' || $_GPC['host'] == 'smtp.gmail.com') {
                $secure = 'ssl';
                $port = '465';
            } else {
                $secure = 'tls';
                $port = '25';
            }
            $result = $this->sendmail($_GPC['host'], $secure, $port, $_GPC['sendmail'], $_GPC['senduser'], $_GPC['sendpwd'], $_GPC['sendmail']);
            $cfg = array(
                'host' => $_GPC['host'],
                'secure' => $secure,
                'port' => $port,
                'sendmail' => $_GPC['sendmail'],
                'senduser' => $_GPC['senduser'],
                'sendpwd' => $_GPC['sendpwd'],
                'status' => $result
            );
            if ($result == 1) {
                $this->saveSettings($cfg);
                message('邮箱配置成功', 'refresh');
            } else {
                message('邮箱配置信息有误', 'refresh', 'error');
            }
        }
        include $this->template('setting');
    }
    
    public function fieldsFormValidate($rid = 0) {
        global $_GPC;
        $hotelid = intval($_GPC['hotel']);
        if ($hotelid) {
            $sql = 'SELECT * FROM ' . tablename('hotel2') . " WHERE `id`=:hotelid";
            $params = array();
            $params[':hotelid'] = $hotelid;
            $hotel = pdo_fetch($sql, $params);
            if (!empty($hotel)) {
                return '';
            }
        }
        return '没有选择合适的酒店';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
        $hotelid = intval($_GPC['hotel']);
        $record = array();
        $record['hotelid'] = $hotelid;
        $record['rid'] = $rid;
        $record['weid'] = $_W['uniacid'];
        $reply = pdo_fetch("SELECT * FROM " . tablename('hotel2_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
        if ($reply) {
            pdo_update('hotel2_reply', $record, array('id' => $reply['id']));
        } else {
            pdo_insert('hotel2_reply', $record);
        }
    }

    public function ruleDeleted($rid) {
        pdo_delete('hotel2_reply', array('rid' => $rid));
    }

 

}
