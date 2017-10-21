<?php

/**
 * 微信排号
 * 
 * @author 小丸子  3066560445
 */
defined('IN_IA') or exit('Access Denied');

class Xwz_queueModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch('SELECT * FROM ' . tablename('xwz_queue_reply') . ' WHERE rid = :rid limit 1', array(':rid' => $rid));
            $types = pdo_fetchall('SELECT * FROM ' . tablename('xwz_queue_type') . ' WHERE rid = :rid order by id asc', array(':rid' => $rid));
        }
        load()->func('tpl');
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        return '';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);

        $insert = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'thumb' => $_GPC['thumb'],
            'description' => $_GPC['description'],
            'heading' => $_GPC['heading'],
            'smallheading' => $_GPC['smallheading'],
            'followurl' => $_GPC['followurl'],
            'tel' => $_GPC['tel'],
            'num' => intval($_GPC['num']),
            'beforenum' => intval($_GPC['beforenum']),
            'intro' => $_GPC['intro'],
            'screenbg' => $_GPC['screenbg'],
            'templateid' => $_GPC['templateid'],
            'qrcode' => $_GPC['qrcode'],
            'qrcodetype' => intval($_GPC['qrcodetype']),
            'status' => 1,
        );

        if (empty($id)) {
            pdo_insert('xwz_queue_reply', $insert);
        } else {
            pdo_update('xwz_queue_reply', $insert, array('id' => $id));
        }


        //自定义字段
        $type_ids = $_GPC['type_id'];
        $type_tags = $_GPC['type_tag'];
        $type_titles = $_GPC['type_title'];
        $type_nums = $_GPC['type_num'];
        $type_status = $_GPC['type_status'];
        $typeids = array();
        if (is_array($type_ids)) {
            foreach ($type_ids as $key => $value) {
                $d = array(
                    'uniacid' => $_W['uniacid'],
                    'rid' => $rid,
                    'tag' => $type_tags[$key],
                    'title' => $type_titles[$key],
                    'num' => $type_nums[$key],
                    'status' => $type_status[$key],
                );
                if (empty($value)) {
                    pdo_insert('xwz_queue_type', $d);
                    $typeids[] = pdo_insertid();
                } else {
                    pdo_update('xwz_queue_type', $d,array('id'=>$value));
                    $typeids[] = $value;
                }
            }
        }
        if (count($typeids) > 0) {
            pdo_query('delete from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and rid=:rid and id not in (' . implode(',', $typeids) . ')', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        } else {
            pdo_query('delete from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and rid=:rid', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        }
        //管理二维码


        $path = IA_ROOT . '/addons/xwz_queue/mqrcode';
        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path);
        }
        $file = $path . '/qrcode_' . $_W['uniacid'] . '_' . $rid . '.png';
        require_once IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
        $url = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('manage', array('rid' => $rid)), 2);

        QRcode::png($url, $file);
        return true;
    }
 
    public function ruleDeleted($rid) {
        global $_W;
        pdo_delete("xwz_queue_reply", array("rid" => $rid));
        pdo_delete("xwz_queue_type", array("rid" => $rid));
        pdo_delete("xwz_queue_fans", array("rid" => $rid));
        pdo_delete("xwz_queue_data", array("rid" => $rid));

        $file = IA_ROOT . '/addons/xwz_queue/mqrcode/qrcode_' . $_W['uniacid'] . '_' . $rid . '.png';
        @unlink($file);
    }

}
