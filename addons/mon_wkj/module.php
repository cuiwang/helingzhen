<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_WKJ", "mon_wkj");
define("MON_WKJ_RES", "../addons/" . MON_WKJ . "/");
require_once IA_ROOT . "/addons/" . MON_WKJ . "/dbutil.class.php";

class Mon_WkjModule extends WeModule
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
            $reply = DBUtil::findUnique(DBUtil::$TABLE_WKJ, array(":rid" => $rid));

            $reply['starttime'] = date("Y-m-d  H:i", $reply['starttime']);
            $reply['endtime'] = date("Y-m-d  H:i", $reply['endtime']);

            $rule_items=unserialize($reply['kj_rule']);


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
        $kid = $_GPC['kid'];

        $kj_rules=array();
        $rule_ids=$_GPC['rule_id'];
        $rule_prices=$_GPC['rule_pice'];
        $rule_start=$_GPC['rule_start'];
        $rule_end = $_GPC['rule_end'];

        if(is_array($rule_ids)){

            foreach($rule_ids as $key=>$value){
                $d=array(
                    'rule_pice'=>$rule_prices[$key],
                    'rule_start'=>$rule_start[$key],
                    'rule_end'=>$rule_end[$key]
                );
                $kj_rules[]=$d;
            }

        }
        $data = array(
            'rid' => $rid,
            'weid' => $this->weid,
            'title' => $_GPC['title'],
            'starttime' => strtotime($_GPC['starttime']),
            'endtime' => strtotime($_GPC['endtime']),
            'p_name' => $_GPC['p_name'],
            'p_kc' => $_GPC['p_kc'],
            'p_y_price' => $_GPC['p_y_price'],
            'p_low_price' => $_GPC['p_low_price'],
            'p_pic' => $_GPC['p_pic'],
            'p_preview_pic' => $_GPC['p_preview_pic'],
            'yf_price'=>$_GPC['yf_price'],
            'follow_url' => $_GPC['follow_url'],
            'copyright' => $_GPC['copyright'],
            'new_title' => $_GPC['new_title'],
            'new_icon' => $_GPC['new_icon'],
            'new_content' => $_GPC['new_content'],
            'share_title' => $_GPC['share_title'],
            'share_icon' => $_GPC['share_icon'],
            'share_content' => $_GPC['share_content'],
            'p_url' => $_GPC['p_url'],
            'copyright_url' => $_GPC['copyright_url'],
            'hot_tel'=>$_GPC['hot_tel'],
            'p_intro'=>htmlspecialchars_decode($_GPC['p_intro']),
            'kj_dialog_tip'=>$_GPC['kj_dialog_tip'],
            'u_fist_tip'=>$_GPC['u_fist_tip'],
            'u_already_tip'=>$_GPC['u_already_tip'],
            'rank_tip'=>$_GPC['rank_tip'],
            'fk_fist_tip'=>$_GPC['fk_fist_tip'],
            'fk_already_tip'=>$_GPC['fk_already_tip'],
            'pay_type'=>$_GPC['pay_type'],
            'p_model'=>$_GPC['p_model'],
            'kj_rule'=>serialize($kj_rules),
            'createtime' => TIMESTAMP,
            'friend_help_limit' => $_GPC['friend_help_limit']
        );

        if (empty($kid)) {

           DBUtil::create(DBUtil::$TABLE_WKJ,$data);

        } else {
            DBUtil::updateById(DBUtil::$TABLE_WKJ,$data,$kid);
        }

        return true;
    }

    public function ruleDeleted($rid)
    {

        $wkj = DBUtil::findUnique(DBUtil::$TABLE_WKJ, array(":rid" => $rid));


        pdo_delete(DBUtil::$TABLE_WKJ_FIREND, array("kid" => $wkj['id']));

        pdo_delete(DBUtil::$TABLE_WKJ_USER, array("kid" => $wkj['id']));


    }


}