<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_ZJP", "mon_zjp");
define("MON_ZJP_RES", "../addons/" . MON_ZJP . "/");
require_once IA_ROOT . "/addons/" . MON_ZJP . "/dbutil.class.php";

class Mon_ZjpModule extends WeModule
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
            $reply = CRUD::findUnique(CRUD::$table_zjp, array(":rid" => $rid));

            $reply['starttime'] = date("Y-m-d  H:i", $reply['starttime']);
            $reply['endtime'] = date("Y-m-d  H:i", $reply['endtime']);


            $prizes=pdo_fetchall("select * from ".tablename(CRUD::$table_zjp_prize)." where zid=:zid order by sort asc,createtime asc ",array(":zid"=>$reply['id']));

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


        $zid = $_GPC['zid'];
        $data = array(
            'rid' => $rid,
            'weid' => $this->weid,
            'title' => $_GPC['title'],
            'starttime' => strtotime($_GPC['starttime']),
            'endtime' => strtotime($_GPC['endtime']),
            'index_pic' => $_GPC['index_pic'],
            'prize_intro' => htmlspecialchars_decode($_GPC['prize_intro']),
            'rule_intro' => htmlspecialchars_decode($_GPC['rule_intro']),
            'share_award_enable' => $_GPC['share_award_enable'],
            'share_award_count' => $_GPC['share_award_count'],
            'share_award_time' => $_GPC['share_award_time'],
            'u_award_count' => $_GPC['u_award_count'],
            'play_count' => $_GPC['play_count'],
            'follow_url' => $_GPC['follow_url'],
            'copyright' => $_GPC['copyright'],
            'new_title' => $_GPC['new_title'],
            'new_icon' => $_GPC['new_icon'],
            'new_content' => $_GPC['new_content'],
            'share_title' => $_GPC['share_title'],
            'share_icon' => $_GPC['share_icon'],
            'share_content' => $_GPC['share_content'],
            'banner_ad_pic' => $_GPC['banner_ad_pic'],
            'dialog_tips' => $_GPC['dialog_tips'],
            'success_award_tips' => $_GPC['success_award_tips'],
            'fail_award_tips' => $_GPC['fail_award_tips'],
            'lock_tip' => $_GPC['lock_tip'],
            'prize_sharebtn_name' => $_GPC['prize_sharebtn_name'],
            'luck_sharebtn_name' => $_GPC['luck_sharebtn_name'],
            'day_play_count'=>$_GPC['day_play_count'],
            'createtime' => TIMESTAMP

        );



        if (empty($zid)) {
            CRUD::create(CRUD::$table_zjp, $data);
            $zid = pdo_insertid();

        } else {

            CRUD::updateById(CRUD::$table_zjp, $data, $zid);
        }

        //奖品
        $prizids = array();
         $pids = $_GPC['pids'];
            $pnames = $_GPC['pname'];
          $psorts = $_GPC['sort'];
          $psummarys = $_GPC['psummary'];
         $pcounts = $_GPC['count'];
         $punits = $_GPC['unit'];
         $ppercents = $_GPC['percent'];
        $picons = $_GPC['picon'];

        if (is_array($pids)) {
            foreach ($pids as $key => $value) {
                $value = intval($value);
                $d = array(
                    "zid" => $zid,
                    "pname" => $pnames[$key],
                    "sort" => $psorts[$key],
                    "psummary" => $psummarys[$key],
                    "count" => $pcounts[$key],
                    "percent" => $ppercents[$key],
                    "picon" => $picons[$key],
                    "unit" => $punits[$key],
                    "createtime"=>TIMESTAMP
                );

                if (empty($value)) {
                    CRUD::create(CRUD::$table_zjp_prize, $d);
                    $prizids[] = pdo_insertid();
                } else {

                    CRUD::updateById(CRUD::$table_zjp_prize, $d, $value);
                    $prizids[] = $value;
                }

            }


            if (count($prizids) > 0) {

                pdo_query("delete from " . tablename(CRUD::$table_zjp_prize) . " where zid='{$zid}' and id not in (" . implode(",", $prizids) . ")");

            } else {

                pdo_query("delete from " . tablename(CRUD::$table_zjp_prize) . " where zid='{$zid}'");

            }

        }






        return true;
    }

    public function ruleDeleted($rid)
    {

        $zjp = CRUD::findUnique(CRUD::$table_zjp, array(":rid" => $rid));


        pdo_delete(CRUD::$table_zjp_record, array("zid" => $zjp['id']));

        pdo_delete(CRUD::$table_zjp_user, array("zid" => $zjp['id']));


        pdo_delete(CRUD::$table_zjp_prize, array("zid" => $zjp['id']));

        pdo_delete(CRUD::$table_zjp, array("id" => $zjp['id']));


    }


    public function p_img($index)
    {

        $imgName = "p" . $index . ".png";


        return MON_JGG_RES . "images/" . $imgName;


    }


    public function  defaultImg($type)
    {

        switch ($type) {
            case 0:
                $imgName = "slide01-bg.png";
                break;
            case 1:
                $imgName = "slide02-prize01.png";
                break;

            case 2://宣传背景图
                $imgName="slide01-top.png";
                break;
        }

        return MON_ZJP_RES . "images/" . $imgName;

    }


}