<?php
defined('IN_IA') or exit('Access Denied');

define('MB_ROOT', IA_ROOT . '/addons/cgt_qyhb');

class Cgt_qyhbModule extends WeModule {
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
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
           
            load()->func('file');
            mkdirs(MB_ROOT . '/cert');
            
            $r=true;
    
            if(!empty($_GPC['cert'])) {
 
                $ret = file_put_contents(MB_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
                $r = $r && $ret;
               
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(MB_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(MB_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /addons/cgt_qyhb/cert/ 目录可写');
            }
            $input = array_elements(array('appid', 'secret', 'mchid', 'password',
             'ip','min_amount','max_amount','tx_thumb','qrcode_thumb','weixinid',
            'shareurl','sharetitle','sharedescription','shareimage','name','notice','tj_amount','iplimit','end','endurl'), $_GPC);
            $input['appid'] = trim($input['appid']);
            $input['secret'] = trim($input['secret']);
            $input['mchid'] = trim($input['mchid']);
            $input['password'] = trim($input['password']);
            $input['ip'] = trim($input['ip']);
            $input['min_amount'] = trim($input['min_amount']);
            $input['max_amount'] = trim($input['max_amount']);
            $input['tx_thumb'] = trim($input['tx_thumb']);
            $input['qrcode_thumb'] = trim($input['qrcode_thumb']);
            $input['weixinid'] = trim($input['weixinid']);
            
            $input['shareurl'] = trim($input['shareurl']);
            $input['sharetitle'] = trim($input['sharetitle']);
            $input['sharedescription'] = trim($input['sharedescription']);
            $input['shareimage'] = trim($input['shareimage']);
            $input['notice'] = trim($input['notice']);
            $input['tj_amount'] = trim($input['tj_amount']);
            
             $input['name'] = trim($input['name']);
             $input['iplimit'] = trim($input['iplimit']); 
			 $input['end'] = trim($input['end']);
             $input['endurl'] = trim($input['endurl']); 
             
            
    
         
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