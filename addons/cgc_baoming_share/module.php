<?php
/**
 * 报名朋友圈分享模块定义
 *
 * @author 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Cgc_baoming_shareModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		$uniacid=$_W['uniacid'];
		load()->func('tpl');
	    
	    $activity = pdo_fetchall("SELECT id as activity_id,title FROM " . tablename('cgc_baoming_activity') . 
            " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		
		if($rid){
			$reply = pdo_fetch("SELECT type, activity_id,activity_name as title,pic_thumb,pic_title,pic_desc FROM " . tablename('cgc_baoming_reply') . 
        " WHERE uniacid = :uniacid and rid=$rid", array(':uniacid' => $_W['uniacid']));
        
		}
		
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		global $_GPC;
		
		if (empty($_GPC['pic_title'])){
			 return '请先添加标题';
		}
		
		if (empty($_GPC['pic_desc'])){
			 return '请先添加描述';
		}
		
		
		if (empty($_GPC['activity_id'])){
		  return '请先添加报名管理的项目';
		} else {
		  return "";
		}
	}
	
	
	


	public function fieldsFormSubmit($rid) {
		global $_GPC,$_W;
	    $uniacid=$_W['uniacid'];
		$activity_id = intval($_GPC['activity_id']);
		$type = $_GPC['type'];
		$record = array();
		$record['rid'] = $rid;
		$record['activity_id'] = $activity_id;
	    $record['type'] = $type;
	    
	    $record['pic_thumb'] = $_GPC['pic_thumb'];
		$record['pic_title'] = $_GPC['pic_title'];
	    $record['pic_desc'] = $_GPC['pic_desc'];
	   
	    
	    $record['createtime'] = time();
	    
		$record['uniacid'] = $uniacid;
		$reply = pdo_fetch("SELECT * FROM " . tablename('cgc_baoming_reply') . " WHERE rid = :id", array(':id' => $rid));
		
		$title = pdo_fetchcolumn("SELECT title FROM " . 
		tablename('cgc_baoming_activity') . " WHERE id = :id", array(':id' => $activity_id));
		

        $record['activity_name'] = $title;		
		if($reply) {
			pdo_update('cgc_baoming_reply', $record, array('rid' => $rid));
		} else {
		   pdo_insert('cgc_baoming_reply', $record);
		}
	
	}
	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		  pdo_delete('cgc_baoming_reply', array('rid' => $rid));
	}
	
	

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
        load()->func('tpl');
		if(checksubmit()) {    
            load()->func('file');
			$_W['uploadsetting'] = array ();
			$_W['uploadsetting']['image']['folder'] = 'images/' . $_W['uniacid'];
			$_W['setting']['upload']['image']['extentions'] = array_merge($_W['setting']['upload']['image']['extentions'], array (
				"pem"
			));
			$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];

			if (!empty ($_FILES['apiclient_cert_file']['name'])) {
				$file = file_upload($_FILES['apiclient_cert_file']);
				if (is_error($file)) {
					message('apiclient_cert证书保存失败, 请保证目录可写' . $file['message']);
				} else {
					$_GPC['apiclient_cert'] = empty ($file['path']) ? trim($_GPC['apiclient_cert']) : ATTACHMENT_ROOT . '/' . $file['path'];
				}
			}

			if (!empty ($_FILES['apiclient_key_file']['name'])) {
				$file = file_upload($_FILES['apiclient_key_file']);
				if (is_error($file)) {
					message('apiclient_key证书保存失败, 请保证目录可写' . $file['message']);
				} else {
					$_GPC['apiclient_key'] = empty ($file['path']) ? trim($_GPC['apiclient_key']) : ATTACHMENT_ROOT . '/' . $file['path'];
				}
			}

			if (!empty ($_FILES['rootca_file']['name'])) {
				$file = file_upload($_FILES['rootca_file']);
				if (is_error($file)) {
					message('rootca证书保存失败, 请保证目录可写' . $file['message']);
				} else {
					$_GPC['rootca'] = empty ($file['path']) ? trim($_GPC['rootca']) : ATTACHMENT_ROOT . '/' . $file['path'];
				}
			}
                      
            $input =array();          
            $input['appid'] = trim($_GPC['appid']);
            $input['secret'] = trim($_GPC['secret']);      
            $input['mchid'] = trim($_GPC['mchid']);
            $input['password'] = trim($_GPC['password']);
            $input['apiclient_cert'] = trim($_GPC['apiclient_cert']);
			$input['apiclient_key'] = trim($_GPC['apiclient_key']);
			$input['rootca'] = trim($_GPC['rootca']);
            $input['ip'] = trim($_GPC['ip']);
           	$input['iplimit'] = trim($_GPC['iplimit']);
            $input['locationtype'] = trim($_GPC['locationtype']);
            $input['join_num'] = trim($_GPC['join_num']);
            $input['zdyurl'] = trim($_GPC['zdyurl']);
            $input['xl'] = trim($_GPC['xl']);
            $input['copyright'] = trim($_GPC['copyright']);
          	$input['zdy_domain'] = trim($_GPC['zdy_domain']);
           	$input['domain'] = trim($_GPC['domain']);
          	$input['valid_time'] = trim($_GPC['valid_time']);
          	$input['share_guide'] = trim($_GPC['share_guide']);
          	$input['share_guide_info'] = trim($_GPC['share_guide_info']);
          	$input['share_type'] = trim($_GPC['share_type']);   
          	$input['ali_smstemplate'] = trim($_GPC['ali_smstemplate']);
          	$input['ali_appkey'] = trim($_GPC['ali_appkey']);
          	$input['ali_smssign'] = trim($_GPC['ali_smssign']);
          	$input['ali_secretkey'] = trim($_GPC['ali_secretkey']);  
          	$input['valid_sms'] = trim($_GPC['valid_sms']);
          	$input['product'] = trim($_GPC['product']);
          	$input['logo_url'] = trim($_GPC['logo_url']);
          	$input['index_logo'] = trim($_GPC['index_logo']);     
          	$input['debug_mode'] = trim($_GPC['debug_mode']);    
          	$input['more_activity'] = trim($_GPC['more_activity']);    
          	$input['hx_status'] = trim($_GPC['hx_status']);    
          	$input['jmsq'] = trim($_GPC['jmsq']);      
          	$input['ewei_shop'] = trim($_GPC['ewei_shop']);        
          	$input['main_domain'] = trim($_GPC['main_domain']);          
            $input['hx_openid'] = trim($_GPC['hx_openid']);  
            $input['sys_sq'] = trim($_GPC['sys_sq']);  
            $input['sendtype'] = trim($_GPC['sendtype']); 
            $input['kefu_tel'] = trim($_GPC['kefu_tel']); 
            $input['kefu_qrcode'] = trim($_GPC['kefu_qrcode']);  
              $input['duli'] = trim($_GPC['duli']);  
            
             $input['top_page_name'] = trim($_GPC['top_page_name']);  
            
             
                                                                                                                      
            if($this->saveSettings($input)) {
                message('&#20445;&#23384;&#21442;&#25968;&#25104;&#21151;', 'refresh');
            }
        }
 
   
        include $this->template('setting');
	}

}