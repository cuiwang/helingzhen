<?php
/**
 * 
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Mon_redpacketModule extends WeModule {

   
    
   

    public $table_redpacket="redpacket";
            
    public $table_redpacket_reply="redpacket_reply";
    
    
    
    
    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_reply) . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename($this->table_redpacket) . ' WHERE `weid`=:weid AND `id`=:pid';
            
            $redpacket = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':pid' => $reply['pid']));
          
        }

        load()->func('tpl');
    
        include $this->template('form');
    }
    
    
    
    
    
    
    public function fieldsFormValidate($rid = 0) {
        global $_W, $_GPC;
        $pid = intval($_GPC['pid']);
        $new_title=$_GPC['new_title'];
        $new_pic=$_GPC['new_pic'];
        $new_desc=$_GPC['new_desc'];
        if(!empty($pid)) {
            $sql = 'SELECT * FROM ' . tablename($this->table_redpacket) . " WHERE `id`=:pid";
            $params = array();
            $params[':pid'] = $pid;
            $redpacket = pdo_fetch($sql, $params);
         
            if(!empty($redpacket)) {
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
    
   
    
    public function fieldsFormSubmit($rid) {
        global $_GPC;
        $pid = intval($_GPC['pid']);
        $record = array();
        $record['pid'] =  $pid;
        $record['rid'] = $rid;
        $record['new_title']=$_GPC['new_title'];
        $record['new_pic']=$_GPC['new_pic'];
        $record['new_desc']=$_GPC['new_desc'];
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_reply) . " WHERE rid = :rid", array(':rid' => $rid));
        if($reply) {
            pdo_update($this->table_redpacket_reply, $record, array('id' => $reply['id']));
        } else {
            pdo_insert($this->table_redpacket_reply, $record);
        }
    }
    
    public function ruleDeleted($rid) {
        pdo_delete($this->table_redpacket_reply, array('rid' => $rid));
    }
	

}