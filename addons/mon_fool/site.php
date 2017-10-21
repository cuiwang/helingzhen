<?php
/**
 * 微小区
 */
defined('IN_IA') or exit('Access Denied');

define("MON_FOOL", "mon_fool");
define("MON_FOOL_RES", "../addons/" . MON_FOOL . "/");
require_once IA_ROOT . "/addons/" . MON_FOOL . "/CRUD.class.php";


class Mon_FoolModuleSite extends WeModuleSite
{
    public $weid;
    public $acid;
    public $oauth;




    public function __construct()
    {
        global $_W;
        $this->weid = $_W['uniacid'];

    }


    /**
     * author: codeMonkey QQ:631872807
     * 签到管理
     */
    public function  doWebFool()
    {
        global $_GPC,$_W;


        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if ($operation == 'display') {

            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_fool) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_fool) . " WHERE weid =:weid ", array(':weid' => $this->weid));
            $pager = pagination($total, $pindex, $psize);

        } else if ($operation == 'delete') {
            $id = $_GPC['id'];
            pdo_delete(CRUD::$table_fool, array("id" => $id));


            message('删除成功！', referer(), 'success');
        }

        include $this->template("fool_manage");

    }


    public function  doMobileIndex(){
        global $_GPC,$_W;
        $fid=$_GPC['fid'];
        $fool=CRUD::findById(CRUD::$table_fool,$fid);

        include $this->template("index");
    }




    public function str_murl($url){
        global $_W;
        return $_W['siteroot'].'app'.str_replace('./','/',$url);

    }


}