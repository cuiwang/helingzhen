<?php
/**
 * 贺卡模块处理程序
 *
 * @author nuqut
 */

defined('IN_IA') or exit('Access Denied');


include_once IA_ROOT . '/addons/han_sheka/model.php';

class  Han_shekaModule extends WeModule
{

    private $turlar = array(

        '1' => array('id' => 1, 'name' => "生日卡", 'ename' => "shengri"),

        '2' => array('id' => 2, 'name' => "祝贺卡", 'ename' => "zhuhe"),

        '3' => array('id' => 3, 'name' => "爱情卡", 'ename' => "aiqing"),

        '4' => array('id' => 4, 'name' => "亲友卡", 'ename' => "qinyou"),

        '5' => array('id' => 5, 'name' => "心情卡", 'ename' => "xinqing"),

        '6' => array('id' => 6, 'name' => "感谢卡", 'ename' => "ganxie"),

        '7' => array('id' => 7, 'name' => "道歉卡", 'ename' => "daoqian"),

        '8' => array('id' => 8, 'name' => "打气卡", 'ename' => "weiwen"),

        '9' => array('id' => 9, 'name' => "会面卡", 'ename' => "baifang"),

        '10' => array('id' => 10, 'name' => "节日卡", 'ename' => "jieri"),

        '11' => array('id' => 11, 'name' => "商务定制", 'ename' => "dingzhi"),

        '12' => array('id' => 12, 'name' => "其他卡", 'ename' => "qita"),

    );


    public function fieldsFormDisplay($rid = 0)
    {

        global $_W, $_GPC;

        if ($rid) {

            $reply = pdo_fetch("SELECT * FROM " . tablename('han_sheka_reply') . " WHERE rid = :rid and weid = :weid", array(':rid' => $rid, ':weid' => $_W['weid']));

        }

        include $this->template('form');

    }


    public function fieldsFormSubmit($rid)
    {

        global $_W, $_GPC;

        $cid = intval($_GPC['cid']);

        $record = array();

        $record['cid'] = $cid;

        $record['rid'] = $rid;

        $record['weid'] = $_W['weid'];

        $record['title'] = $this->turlar[$cid]['name'];

        $record['is_show'] = $_GPC['is_show'];


        $reply = pdo_fetch("SELECT * FROM " . tablename('han_sheka_reply') . " WHERE rid = :rid", array(':rid' => $rid));

        if ($reply) {

            pdo_update('han_sheka_reply', $record, array('id' => $reply['id']));

        } else {

            pdo_insert('han_sheka_reply', $record);

        }

    }


    public function ruleDeleted($rid)
    {

        pdo_delete('han_sheka_reply', array('rid' => $rid));

    }



    public function settingsDisplay($settings)
    {

        global $_GPC, $_W;

        if (checksubmit()) {

            $cfg = array(

                'name' => $_GPC['name'],

                'logo' => $_GPC['logo'],

            );

            if ($this->saveSettings($cfg)) {

                message('保存成功', 'refresh');

            }

        }

        load()->func('tpl');
        include $this->template('setting');

    }



}

