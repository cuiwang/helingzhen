<?php
/**
 * 
 *
 * @url
 */
defined('IN_IA') or exit('Access Denied');
define('APP_PUBLIC', '/source/modules/activity/');
class Mon_WeiShareModule extends WeModule {
 
    public $table_share  = 'weishare';
    
    public $table_share_reply='weishare_reply';
    
    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_share_reply) . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename($this->table_share) . ' WHERE `weid`=:weid AND `id`=:sid';
            
            $share = pdo_fetch($sql, array(':weid' => $_W['weid'], ':sid' => $reply['sid']));
          
        }

        load()->func('tpl');
    
        include $this->template('form');
    }
    
    
    
    
    
    
    public function fieldsFormValidate($rid = 0) {
        global $_W, $_GPC;
        $sid = intval($_GPC['sid']);
        $new_title=$_GPC['new_title'];
        $new_pic=$_GPC['new_pic'];
        $new_desc=$_GPC['new_desc'];
        if(!empty($sid)) {
            $sql = 'SELECT * FROM ' . tablename($this->table_share) . " WHERE `id`=:sid";
            $params = array();
            $params[':sid'] = $sid;
            $activity = pdo_fetch($sql, $params);
         
            if(!empty($activity)) {
                return '';
            }
        }
        if(empty($new_title)){
            return "输入图文标题!";
        }
        
        if(empty($new_pic)){
            return "请上传图片图片";
        }
        
        if(empty($new_desc)){
            
            return "请输入图文说明";
        }
        
        
        return '';
    }
    
    private  function getpicurl($url){
        global $_W;
        if($url){
            return $_W['attachurl'].$url;
        }else{
            return $_W['siteroot'].'source/modules/ebook/images/tuisong.jpg';
        }
    
    
    }
    
    public function fieldsFormSubmit($rid) {
        global $_GPC;
        $sid = intval($_GPC['sid']);
        $record = array();
        $record['sid'] =  $sid;
        $record['rid'] = $rid;
        $record['new_title']=$_GPC['new_title'];
        $record['new_pic']=$_GPC['new_pic'];
        $record['new_desc']=$_GPC['new_desc'];
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_share_reply) . " WHERE rid = :rid", array(':rid' => $rid));
        if($reply) {
            pdo_update($this->table_share_reply, $record, array('id' => $reply['id']));
        } else {
            pdo_insert($this->table_share_reply, $record);
        }
    }
    
    public function ruleDeleted($rid) {
        pdo_delete($this->table_share_reply, array('rid' => $rid));
    }
	

}