<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/30
 * Time: 13:30
 */
/**
 * used: 
 * User: imeepos
 * Qq: 800083075
 */

class ImeeposOpenSms_tasks
{
    public $table = 'imeepos_runner3_tasks';

    public function __construct()
    {
        $this->install();
    }

    public function getall($params=array()){
        global $_W;
        $params['uniacid'] = $_W['uniacid'];
        $list = pdo_getall($this->table,$params);
        return $list;
    }

    public function delete($id){
        if(empty($id)){
            return '';
        }
        pdo_delete($this->table,array('id'=>$id));
    }
    public function getList($page,$where ="",$params = array()){
        global $_W,$_GPC;
        if(empty($page)){
            $page = 1;
        }
        $psize = 20;
        $params[':uniacid'] = $_W['uniacid'];
        $sql = "SELECT * FROM ".tablename($this->table)." WHERE uniacid = :uniacid {$where} ORDER BY create_time DESC limit ".(($page-1)*$psize).",".$psize;
        $result = array();
        $result['list'] = pdo_fetchall($sql,$params);
        $sql = "SELECT COUNT(*) FROM ".tablename($this->table)." WHERE uniacid = :uniacid {$where} ";
        $total = pdo_fetchcolumn($sql,$params);
        $result['pager'] = pagination($total, $page, $psize);
        if(empty($result)){
            return array();
        }else{
            return $result;
        }
    }

    public function update($data){
        global $_W;
        $data['uniacid'] = $_W['uniacid'];
        if(empty($data['id'])){
            pdo_insert($this->table,$data);
            $data['id'] = pdo_insertid();
        }else{
            //更新
            pdo_update($this->table,$data,array('uniacid'=>$_W['uniacid'],'id'=>$data['id']));
        }
        return $data;
    }
    public function getInfo($id){
        global $_W;
        $task = pdo_get($this->table,array('id'=>$id));
        if(!empty($task)){

        }else{
            $task = array();
        }
        return $task;
    }
    public function install(){
        if(!pdo_tableexists($this->table)){
            $sql = "CREATE TABLE ".tablename($this->table)." (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uniacid` int(11) DEFAULT '0',
              `status` tinyint(2) DEFAULT '1',
              `create_time` int(11) DEFAULT '0',
              `cityid` int(11) DEFAULT '0',
              `media_id` varchar(132) DEFAULT '',
              `openid` varchar(64) DEFAULT '',
              `desc` text,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
            pdo_query($sql);
        }
        if(!pdo_fieldexists($this->table,'total')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `total` float(10,2) DEFAULT '0.00'");
        }
        if(!pdo_fieldexists($this->table,'small_money')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `small_money` float(10,2) DEFAULT '0.00'");
        }
        if(!pdo_fieldexists($this->table,'limit_time')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `limit_time` int(11) DEFAULT '0'");
        }
        if(!pdo_fieldexists($this->table,'address')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `address` varchar(320) DEFAULT ''");
        }
        if(!pdo_fieldexists($this->table,'city')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `city` varchar(32) DEFAULT ''");
        }
        if(!pdo_fieldexists($this->table,'desc')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `desc` text");
        }
        if(!pdo_fieldexists($this->table,'type')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `type` tinyint(4) DEFAULT '0'");
        }
        if(!pdo_fieldexists($this->table,'update_time')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `update_time` int(11) DEFAULT '0'");
        }
        if(!pdo_fieldexists($this->table,'code')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `code` varchar(64) DEFAULT ''");
        }
        if(!pdo_fieldexists($this->table,'qrcode')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `qrcode` text");
        }
        if(!pdo_fieldexists($this->table,'read_num')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `read_num` int(11) DEFAULT '0'");
        }
        if(!pdo_fieldexists($this->table,'share_num')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `share_num` int(11) DEFAULT '0'");
        }
        if(!pdo_fieldexists($this->table,'listen_num')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `listen_num` int(11) DEFAULT '0'");
        }
        if(!pdo_fieldexists($this->table,'message')){
            pdo_query("ALTER TABLE ".tablename($this->table)." ADD COLUMN `message` varchar(320) DEFAULT ''");
        }
    }
}