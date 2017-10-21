<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_BATON", "mon_baton");
define("MON_BATON_RES", "../addons/" . MON_BATON . "/");
require_once IA_ROOT . "/addons/" . MON_BATON . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_BATON . "/monUtil.class.php";

class Mon_BatonModule extends WeModule
{

    public $weid;

    public function __construct()
    {
        global $_W;
        $this->weid = IMS_VERSION < 0.6 ? $_W['weid'] : $_W['uniacid'];
    }

    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;

        if (!empty($rid)) {
            $reply = DBUtil::findUnique(DBUtil::$TABLE_BATON, array(":rid" => $rid));

            $reply['starttime'] = date("Y-m-d  H:i", $reply['starttime']);
            $reply['endtime'] = date("Y-m-d  H:i", $reply['endtime']);
        }


        load()->func('tpl');


        include $this->template('form');


    }

    public function fieldsFormValidate($rid = 0)
    {
        global $_GPC, $_W;


        return '';
    }

    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        $bid = $_GPC['bid'];
        $data = array(
            'rid' => $rid,
            'weid' => $this->weid,
            'title' => $_GPC['title'],
            'starttime' => strtotime($_GPC['starttime']),
            'endtime' => strtotime($_GPC['endtime']),
            'follow_url' => $_GPC['follow_url'],
            'copyright' => $_GPC['copyright'],
            'copyright_url' => $_GPC['copyright_url'],
            'index_banner' => $_GPC['index_banner'],
            'my_banner' => $_GPC['my_banner'],
            'ry_banner' => $_GPC['ry_banner'],
            'sucess_banner'=>$_GPC['sucess_banner'],
            'default_logo' => $_GPC['default_logo'],
            'default_name' => $_GPC['default_name'],
            'end_dialog_tip' => $_GPC['end_dialog_tip'],
            'hd_intro' => htmlspecialchars_decode($_GPC['hd_intro']),
            'rule_intro' => htmlspecialchars_decode($_GPC['rule_intro']),
            'prize_intro' => htmlspecialchars_decode($_GPC['prize_intro']),
            'add_intro' => htmlspecialchars_decode($_GPC['add_intro']),
            'join_fans_enable' => $_GPC['join_fans_enable'],
            'new_title' => $_GPC['new_title'],
            'new_icon' => $_GPC['new_icon'],
            'new_content' => $_GPC['new_content'],
            'share_title' => $_GPC['share_title'],
            'share_icon' => $_GPC['share_icon'],
            'share_content' => $_GPC['share_content'],
            'speak'=>$_GPC['speak'],
            'follow_dialog_tip'=>$_GPC['follow_dialog_tip'],
			'follow_btn'=>$_GPC['follow_btn'],
            'updatetime' => TIMESTAMP
        );

        if (empty($bid)) {
            $data['createtime'] = TIMESTAMP;
            DBUtil::create(DBUtil::$TABLE_BATON, $data);
        } else {
            DBUtil::updateById(DBUtil::$TABLE_BATON, $data, $bid);
        }

        return true;
    }

    public function ruleDeleted($rid)
    {
        $baton = DBUtil::findUnique(DBUtil::$TABLE_BATON, array(":rid" => $rid));
        pdo_delete(DBUtil::$TABLE_BATON_USER, array("bid" => $baton['id']));
    }


}