<?php
/**
 *
 *
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_JGG", "mon_jgg");
define("MON_JGG_RES", "../addons/" . MON_JGG . "/");
require_once IA_ROOT . "/addons/" . MON_JGG . "/CRUD.class.php";

class Mon_JGGModule extends WeModule
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
            $reply = CRUD::findUnique(CRUD::$table_jgg, array(":rid" => $rid));

            $reply['starttime'] = date("Y-m-d  H:i", $reply['starttime']);
            $reply['endtime'] = date("Y-m-d  H:i", $reply['endtime']);


        }


        load()->func('tpl');


        include $this->template('form');


    }

    public function fieldsFormValidate($rid = 0)
    {
        global $_GPC, $_W;

        $p0=$_GPC['prize_p_0'];
        $p1=$_GPC['prize_p_1'];
        $p2=$_GPC['prize_p_2'];
        $p3=$_GPC['prize_p_3'];
        $p4=$_GPC['prize_p_4'];
        $p5=$_GPC['prize_p_5'];
        $p6=$_GPC['prize_p_6'];
        $p7=$_GPC['prize_p_7'];


        $p_total=$p0+$p1+$p2+$p3+$p4+$p5+$p6+$p7;



        if($p_total!=100){

            // return '请检您设置的奖品概率总和是否为100';
        }



        return '';
    }

    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;


        $jid = $_GPC['jid'];





        $data = array(
            'rid' => $rid,
            'weid' => $this->weid,
            'title' => $_GPC['title'],
            'starttime' => strtotime($_GPC['starttime']),
            'endtime' => strtotime($_GPC['endtime']),
            'rule' => htmlspecialchars_decode($_GPC['rule']),
            'join_intro' =>'',
            'day_play_count' => $_GPC['day_play_count'],
            'follow_btn' => $_GPC['follow_btn'],
            'follow_welbtn' => $_GPC['follow_welbtn'],
            'follow_url' => $_GPC['follow_url'],
            'copyright' => $_GPC['copyright'],
            'prize_name_0' => $_GPC['prize_name_0'],
            'award_count'=>$_GPC['award_count'],
            'prize_img_0' => $_GPC['prize_img_0'],
            'prize_p_0' => $_GPC['prize_p_0'],
            'prize_level_1' => $_GPC['prize_level_1'],
            'prize_name_1' => $_GPC['prize_name_1'],
            'prize_img_1' => $_GPC['prize_img_1'],
            'prize_p_1' => $_GPC['prize_p_1'],
            'prize_num_1' => $_GPC['prize_num_1'],
            'prize_level_2' => $_GPC['prize_level_2'],
            'prize_name_2' => $_GPC['prize_name_2'],
            'prize_img_2' => $_GPC['prize_img_2'],
            'prize_p_2' => $_GPC['prize_p_2'],
            'prize_num_2' => $_GPC['prize_num_2'],
            'prize_level_3' => $_GPC['prize_level_3'],
            'prize_name_3' => $_GPC['prize_name_3'],
            'prize_img_3' => $_GPC['prize_img_3'],
            'prize_p_3' => $_GPC['prize_p_3'],
            'prize_num_3' => $_GPC['prize_num_3'],
            'prize_level_4' => $_GPC['prize_level_4'],
            'prize_name_4' => $_GPC['prize_name_4'],
            'prize_img_4' => $_GPC['prize_img_4'],
            'prize_p_4' => $_GPC['prize_p_4'],
            'prize_num_4' => $_GPC['prize_num_4'],
            'prize_level_5' => $_GPC['prize_level_5'],
            'prize_name_5' => $_GPC['prize_name_5'],
            'prize_img_5' => $_GPC['prize_img_5'],
            'prize_p_5' => $_GPC['prize_p_5'],
            'prize_num_5' => $_GPC['prize_num_5'],
            'prize_level_6' => $_GPC['prize_level_6'],
            'prize_name_6' => $_GPC['prize_name_6'],
            'prize_img_6' => $_GPC['prize_img_6'],
            'prize_p_6' => $_GPC['prize_p_6'],
            'prize_num_6' => $_GPC['prize_num_6'],
            'prize_level_7' => $_GPC['prize_level_7'],
            'prize_name_7' => $_GPC['prize_name_7'],
            'prize_img_7' => $_GPC['prize_img_7'],
            'prize_p_7' => $_GPC['prize_p_7'],
            'prize_num_7' => $_GPC['prize_num_7'],
            'new_title' => $_GPC['new_title'],
            'new_icon' => $_GPC['new_icon'],
            'new_content' => $_GPC['new_content'],
            'share_title' => $_GPC['share_title'],
            'share_icon' => $_GPC['share_icon'],
            'share_content' => $_GPC['share_content'],
            'day_award_count' => $_GPC['day_award_count'],
            'bg' => $_GPC['bg'],
            'bgcolor' => $_GPC['bgcolor'],
            'createtime' => TIMESTAMP
        );

        if (empty($jid)) {
            CRUD::create(CRUD::$table_jgg, $data);
        } else {

            CRUD::updateById(CRUD::$table_jgg, $data, $jid);
        }

        return true;
    }

    public function ruleDeleted($rid)
    {

        $jgg = CRUD::findUnique(CRUD::$table_jgg, array(":rid" => $rid));


        pdo_delete(CRUD::$table_jgg, array("id" => $jgg['id']));


    }


    public function p_img($index)
    {

        $imgName = "p" . $index . ".png";


        return MON_JGG_RES . "images/" . $imgName;


    }


    public  function getItemBg($bgColor) {
        if (empty($bgColor)) {
            return "#DABB82";
        }
        return $bgColor;
    }


}