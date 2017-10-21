<?php
/**
 * 火眼金睛
 *
 * @author 优企网络
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Tiger_huoyanModuleSite extends WeModuleSite {

    public $reply = 'Tiger_huoyan_reply';

    public function getHomeTiles() {
        global $_W;
        $urls = array();
        $list = pdo_fetchall("SELECT name, id FROM " . tablename('rule') . " WHERE uniacid = '{$_W['uniacid']}' AND module = 'Tiger_huoyan'");
        if (!empty($list)) {
            foreach ($list as $row) {
                $urls[] = array('title' => $row['name'], 'url' => $this->createMobileUrl('index', array('id' => $row['id'])));
            }
        }
        return $urls;
    }

    public function doMobileindex() {
        global $_GPC, $_W;
        $id = intval($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM ".tablename($this->reply). "WHERE rid = '{$id}'");
        if (empty($reply)) {
            message('抱歉，非法访问');
        }
        //echo "<pre>";
        //print_r($reply);
        //exit;
        include $this->template('index');
    }

}
