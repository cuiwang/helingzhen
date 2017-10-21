<?php
/**
 * 婚庆定制活动
 *
 * @author  wanglu
 * 
 * @url
 */
defined('IN_IA') or exit('Access Denied');
define('APP_PUBLIC', '/source/modules/brand/');
class MON_BrandModule extends WeModule {

    
    public $table_brand  = 'brand';
    public $table_brand_reply  = 'brand_reply';
    
    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_reply) . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename($this->table_brand ) . ' WHERE `weid`=:weid AND `id`=:bid';
            $brand = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':bid' => $reply['bid']));
           
          
        }

        load()->func('tpl');
    
        include $this->template('form');
    }
    
    
    
    
    
    
    public function fieldsFormValidate($rid = 0) {
        global $_W, $_GPC;
        $bid = intval($_GPC['bid']);
        $new_pic=$_GPC['new_pic'];
        $news_content=$_GPC['news_content'];
        
        
        if(!empty($bid)) {
            $sql = 'SELECT * FROM ' . tablename($this->table_brand) . " WHERE `id`=:bid";
            $params = array();
            $params[':bid'] = $bid;
            $brand = pdo_fetch($sql, $params);
            return ;
            if(!empty($brand)) {
                return '';
            }
        }
        
        if(empty($new_pic)){
            return "请上传图片图片";
        }
        
        if(empty($news_content)){
        
            return "图文说明不能为空!";
        }
        
        return '没有选择合适的选产品牌';
    }
    
    private  function getpicurl($url){
        global $_W;
        return $_W['attachurl'].$url;

    }
    
    public function fieldsFormSubmit($rid) {
        global $_GPC;
        $bid = intval($_GPC['bid']);
        $record = array();
        $record['bid'] =  $bid;
        $record['rid'] = $rid;
        $record['new_pic']=$_GPC['new_pic'];
        $record['news_content']=$_GPC['news_content'];
        
        
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_reply) . " WHERE rid = :rid", array(':rid' => $rid));
        if($reply) {
            pdo_update($this->table_brand_reply, $record, array('id' => $reply['id']));
        } else {
            pdo_insert($this->table_brand_reply, $record);
        }
    }
    
    public function ruleDeleted($rid) {
        pdo_delete($this->table_brand_reply, array('rid' => $rid));
    }
	
   

}