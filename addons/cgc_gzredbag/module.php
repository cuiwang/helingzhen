<?php
/**
 * 关注送红包模块处理程序
 * 鬼 狐 源 码 社 区 www.guifox.com
 */
defined('IN_IA') or exit('Access Denied');

define('MB_ROOT', IA_ROOT . '/addons/cgc_gzredbag');

class cgc_gzredbagModule extends WeModule {
	
	
  function file_upload($file,$path = '') {
	$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');
	if (empty($file)) {
		return error(-1, '没有上传内容');
	}

	global $_W;
	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
	$ext = strtolower($ext);

	if (in_array(strtolower($ext), $harmtype)) {
		return error(-3, '不允许上传此类文件');
	}
	if (!empty($limit) && $limit * 1024 < filesize($file['tmp_name'])) {
		return error(-4, "上传的文件超过大小限制，请上传小于 {$limit}k 的文件");
	}
	
	if (!file_move($file['tmp_name'], $path)) {
		return error(-1, '保存上传文件失败');
	}
	$result['success'] = true;
	return $result;
}
	
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
       
		$settings['time'] = array(
          'start' => date('Y-m-d H:i:s', empty($settings['starttime'])?time():$settings['starttime']),
          'end' => date('Y-m-d H:i:s', empty($settings['endtime'])?time():$settings['endtime'])
        );
        
 /*             load()->func('file');
            mkdirs(IA_ROOT . '/cert');
            $r = true;
            $md5=md5("{$_W['uniacid']}{$_W['config']['setting']['authkey']}");          
            if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(IA_ROOT . '/cert/apiclient_cert.pem.'.$md5, trim($_GPC['cert']));
                $r = $r && $ret;
            }

            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(IA_ROOT . '/cert/apiclient_key.pem.' . $md5, trim($_GPC['key']));
                $r = $r && $ret;
            }

            if(!empty($_GPC['ca'])) {

                $ret = file_put_contents(IA_ROOT . '/cert/rootca.pem.' .$md5, trim($_GPC['ca']));

                $r = $r && $ret;

            }*/
      
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {    
            load()->func('file');
			$_W['uploadsetting'] = array();
			$_W['uploadsetting']['image']['folder'] = 'images/' . $_W['uniacid'];
			$_W['setting']['upload']['image']=array_merge($_W['setting']['upload']['image'],array("pem"));
			$_W['setting']['upload']['image']['extentions'] = array_merge($_W['setting']['upload']['image']['extentions'],array("pem"));
			$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
		    $md5=md5("{$_W['uniacid']}{$_W['config']['setting']['authkey']}");  
		
			
			
			
		    if (!empty($_FILES['apiclient_cert_file']['name'])) {
             // $path=IA_ROOT . $this->modulename.'/cert/apiclient_cert.pem.'.$md5;
	          $file = file_upload($_FILES['apiclient_cert_file']);
			  if (is_error($file)) {
				message('apiclient_cert证书保存失败, 请保证目录可写'. $file['message']);
		      } else {
				$_GPC['apiclient_cert']= empty($file['path'])? trim($_GPC['apiclient_cert']):ATTACHMENT_ROOT.'/'.$file['path'];
			  }
			 
		    } 
		    
		    if (!empty($_FILES['apiclient_key_file']['name'])) {
	          $file = file_upload($_FILES['apiclient_key_file']);
			  if (is_error($file)) {
				message('apiclient_key证书保存失败, 请保证目录可写'. $file['message']);
		      } else {
				$_GPC['apiclient_key']= empty($file['path'])? trim($_GPC['apiclient_key']):ATTACHMENT_ROOT.'/'.$file['path'];
			  }
		    } 
		    
		    if (!empty($_FILES['rootca_file']['name'])) {
	          $file = file_upload($_FILES['rootca_file']);
			  if (is_error($file)) {
				message('rootca证书保存失败, 请保证目录可写'. $file['message']);
		      } else {
				$_GPC['rootca']= empty($file['path'])? trim($_GPC['rootca']):ATTACHMENT_ROOT.'/'.$file['path'];
			  }
		    } 
		    
		    
        
		    
		 
		
            
            $input =array();
            
            $input['starttime']= strtotime($_GPC['time']['start']);
            $input['endtime']=strtotime($_GPC['time']['end']);
             
            $input['apiclient_cert'] = trim($_GPC['apiclient_cert']);
            $input['apiclient_key'] = trim($_GPC['apiclient_key']);  
            $input['rootca'] = trim($_GPC['rootca']); 
 
            $input['total_money'] = trim($_GPC['total_money']);
    
            $input['appid'] = trim($_GPC['appid']);
            $input['secret'] = trim($_GPC['secret']);
            $input['mchid'] = trim($_GPC['mchid']);
            $input['password'] = trim($_GPC['password']);
            $input['ip'] = trim($_GPC['ip']);
            $input['min_money'] = trim($_GPC['min_money']);
            $input['max_money'] = trim($_GPC['max_money']);
            $input['probability'] = trim($_GPC['probability']);
            $input['total_money'] = trim($_GPC['total_money']);
         /*   $input['starttime'] = trim($_GPC['starttime']);  
            $input['endtime'] = trim($_GPC['endtime']); */

            $input['offline'] = trim($_GPC['offline']);
            $input['sendtype'] = trim($_GPC['sendtype']);
            $input['act_name'] = trim($_GPC['act_name']);  
            $input['send_name'] = trim($_GPC['send_name']); 

            $input['remark'] = trim($_GPC['remark']);
            $input['answer_type'] = trim($_GPC['answer_type']); 
            $input['title'] = trim($_GPC['title']); 
            $input['desc'] = trim($_GPC['desc']); 
            $input['thumb'] = trim($_GPC['thumb']); 
            $input['url'] = trim($_GPC['url']); 
            
            $input['wks_title'] = trim($_GPC['wks_title']); 
            $input['wks_desc'] = trim($_GPC['wks_desc']); 
            $input['wks_thumb'] = trim($_GPC['wks_thumb']); 
            $input['wks_url'] = trim($_GPC['wks_url']); 
           
            $input['addr_error'] = trim($_GPC['addr_error']); 

            $input['addr'] = trim($_GPC['addr']);      
            
            $input['gz_url'] = trim($_GPC['gz_url']);      
           
            $input['end_url'] = trim($_GPC['end_url']);   
            
            $input['start_hour'] = trim($_GPC['start_hour']);      
           
            $input['end_hour'] = trim($_GPC['end_hour']);   
               
            $input['total_count'] = trim($_GPC['total_count']);     
           
               
                                    
            if($this->saveSettings($input)) {
                message('保存参数成功', 'refresh');
            }
        }
 
        if(empty($settings['ip'])) {
            $settings['ip'] = $_SERVER['SERVER_ADDR'];
        }
        
        include $this->template('setting');
	}

}