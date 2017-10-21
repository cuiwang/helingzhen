<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:2463619823
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_QMWB", "mon_qmwb");
define("MON_QMWB_RES", "../addons/" . MON_QMWB . "/");
require_once IA_ROOT . "/addons/" . MON_QMWB . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_QMWB . "/monUtil.class.php";

class Mon_QMwbModule extends WeModule
{

    public $weid;

    public function __construct()
    {
        global $_W;
        $this->weid = IMS_VERSION < 0.6 ? $_W['weid'] : $_W['uniacid'];
    }


    public function fieldsFormDisplay($rid = 0) {
        global $_W;

        if (!empty($rid)) {
            $reply = DBUtil::findUnique(DBUtil::$TABLE_QMWB, array(":rid" => $rid));
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
        $qid = $_GPC['qid'];

        $data = array(
            'rid' => $rid,
            'weid' => $this->weid,
            'title' => $_GPC['title'],
            'starttime' => strtotime($_GPC['starttime']),
            'endtime' => strtotime($_GPC['endtime']),
            'hd_intro' => htmlspecialchars_decode($_GPC['hd_intro']),
            'dj_intro' => htmlspecialchars_decode($_GPC['dj_intro']),
            'bg_img' => $_GPC['bg_img'],
            'bg_color' => $_GPC['bg_color'],
            'bg_music' => $_GPC['bg_music'],
            'cp_text' => $_GPC['cp_text'],
            'ppt1' => $_GPC['ppt1'],
            'ppt2' => $_GPC['ppt2'],
            'bottom_ad' => $_GPC['bottom_ad'],
            'follow_url' => $_GPC['follow_url'],
            'copyright' => $_GPC['copyright'],
            'new_title' => $_GPC['new_title'],
            'new_icon' => $_GPC['new_icon'],
            'new_content' => $_GPC['new_content'],
            'share_title' => $_GPC['share_title'],
            'share_icon' => $_GPC['share_icon'],
            'share_content' => $_GPC['share_content'],
            'join_follow_enable'=>$_GPC['join_follow_enable'],
            'help_follow_enable'=> $_GPC['help_follow_enable'],
            'follow_btn_name'=>$_GPC['follow_btn_name'],
            'follow_dlg_tip'=>$_GPC['follow_dlg_tip'],
            'user_limit' => $_GPC['user_limit'],
            'user_award_limit' => $_GPC['user_award_limit'],
            'share_bg'=>$_GPC['share_bg'],
            'bottom_ad_url' => $_GPC['bottom_ad_url'],
            'index_show_win' => $_GPC['index_show_win'],
            'exchangeEnable' => $_GPC['exchangeEnable'],
            'tmpId' => $_GPC['tmpId'],
            'tmp_enable' => $_GPC['tmp_enable']
        );
        if (empty($qid)) {
           DBUtil::create(DBUtil::$TABLE_QMWB,$data);

        } else {
            DBUtil::updateById(DBUtil::$TABLE_QMWB,$data,$qid);
        }

        return true;
    }

    public function ruleDeleted($rid)
    {

        $qmwb = DBUtil::findUnique(DBUtil::$TABLE_QMWB, array(":rid" => $rid));

        pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("qid" => $qmwb['id']));
        pdo_delete(DBUtil::$TABLE_QMWB_RECORD, array("qid" => $qmwb['id']));
        pdo_delete(DBUtil::$TABLE_QMWB_USER, array("qid" => $qmwb['id']));
        pdo_delete(DBUtil::$TABLE_QMWB_PRIZE, array("qid" => $qmwb['id']));
        pdo_delete(DBUtil::$TABLE_QMWB_ADDRESS, array("qid" => $qmwb['id']));
        pdo_delete(DBUtil::$TABLE_QMWB, array("id" => $qmwb['id']));


    }

    public  function getItemBg($bgColor) {
        if (empty($bgColor)) {
            return "#DABB82";
        }
        return $bgColor;
    }
}