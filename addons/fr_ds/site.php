<?php

/**
 * 通用微信赞赏模块微站定义
 * @url #
 */
defined('IN_IA') or exit('Access Denied');

class Fr_dsModuleSite extends WeModuleSite {

    public function payResult($res) {
        global $_W;
        include MODULE_ROOT . '/inc/common.php';
        if ($res['result'] == 'success' && $res['from'] == 'notify') {//通知
            //fr_update('record', array('status' => 1), array('tid' => $res['tid']));
        }
        if ($res['from'] == 'return') {
            $result = iunserializer($_SESSION['__fr_ds_session']);
            if ($res['result'] == 'success') {
                $row = getRow('record', " AND tid = '{$res['tid']}'");
                if (empty($row) && !empty($result)) {
                    $result['tid'] = $res['tid']; 
                    $result['createtime'] = TIMESTAMP; 
                    $result['status'] = 1;
                    $result['uniacid'] = $_W['uniacid']; 
                    $result['money'] = $res['fee']; 
                    fr_insert('record', $result);
                }
                //message('赞赏成功！', $result['referer'], 'success');
            } else {
                //message('赞赏失败！', $result['referer'], 'error');
            }
            redirect($result['referer']);
        }
    }

}
