<?php

class setting
{
    public $table = 'imeepos_fen_setting';

    public function __construct()
    {
        $this->install();
    }

    public function getSetting($code){
        $item = $this->getInfo($code);
        if(!empty($item)){
            return iunserializer($item['value']);
        }else{
            return array();
        }
    }
    /**
    删除某设置
     */
    public function delete($code){
        global $_W;
        if(empty($code)){
            return '';
        }
        pdo_delete($this->table,array('uniacid'=>$_W['uniacid'],'codename'=>$code));
    }

    public function update($data){
        global $_W;
        $item = $this->getInfo($data['codename']);
        $data['uniacid'] = $_W['uniacid'];
        if(empty($item)){
            pdo_insert($this->table,$data);
        }else{
            pdo_update($this->table,$data,array('uniacid'=>$_W['uniacid'],'codename'=>$data['codename']));
        }
    }
    public function getInfo($codename){
        global $_W;
        $sql = "SELECT * FROM ".tablename($this->table)." WHERE uniacid = :uniacid AND codename = :codename";
        $params = array(':uniacid'=>$_W['uniacid'],':codename'=>$codename);
        $item = pdo_fetch($sql,$params);
        return $item;
    }
    public function install(){
        if(!pdo_tableexists($this->table)){
            $sql = "CREATE TABLE ".tablename($this->table)." (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `codename` varchar(32) DEFAULT '',
              `value` text,
              `uniacid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
            pdo_query($sql);
        }
    }
}