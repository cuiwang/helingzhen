<?php

   		global $_W,$_GPC;
		$weid=$_W['uniacid'];
       
        $config = pdo_fetch("SELECT * FROM ".tablename('cgc_ad_setting')." WHERE weid=".$weid);
       
        if(empty($config)){
        	$config = array();
        }
        
        if(empty($config['rush_text'])){
        	$config['rush_text'] = '抢钱';
        }
        
        if(empty($config['unit_text'])){
        	$config['unit_text'] = '元';
        }

        if(checksubmit()) {

            load()->func('file');

            mkdirs(IA_ROOT . '/cert');

            $r = true;

            if(!empty($_GPC['cert'])) {



                $ret = file_put_contents(IA_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));

                $r = $r && $ret;

            }

            if(!empty($_GPC['key'])) {

                $ret = file_put_contents(IA_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));

                $r = $r && $ret;

            }

            if(!empty($_GPC['ca'])) {

                $ret = file_put_contents(IA_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));

                $r = $r && $ret;

            }

            if(!$r) {

                message('证书保存失败, 请保证 /addons/cgc_ad/cert/ 目录可写');

            }

            $input = array_elements(array('task_template_id','secret','appid',  'mchid', 'password','qq','kf_openid','tuisong','is_type','template_id','ip', 'bd_ak', 'qn_ak', 'qn_sk', 'pay_type','yunpay_no','yunpay_pid','yunpay_key','qn_bucket', 'qn_api','is_qniu','rush_text','unit_text'), $_GPC);

            $input['appid'] = trim($input['appid']);
            
            $input['secret'] = trim($input['secret']); 
            

            $input['mchid'] = trim($input['mchid']);

			$input['qq'] = trim($input['qq']);

            $input['password'] = trim($input['password']);

            $input['ip'] = trim($input['ip']);

            $input['bd_ak'] = trim($input['bd_ak']);

            $input['qn_ak'] = trim($input['qn_ak']);

            $input['qn_sk'] = trim($input['qn_sk']);

            $input['qn_bucket'] = trim($input['qn_bucket']);

            $input['qn_api'] = trim($input['qn_api']);
            
            $input['pay_type'] = trim($input['pay_type']);
            
            $input['yunpay_no'] = trim($input['yunpay_no']);
            
            $input['yunpay_pid'] = trim($input['yunpay_pid']);
            
            $input['yunpay_key'] = trim($input['yunpay_key']);

            $input['tuisong'] = trim($input['tuisong']);

            $input['kf_openid'] = trim($input['kf_openid']);

            $input['is_type'] = trim($input['is_type']);

            $input['template_id'] = trim($input['template_id']);

			$input['is_qniu'] = trim($input['is_qniu']);
			
			$input['rush_text'] = trim($input['rush_text']);
			
			$input['unit_text'] = trim($input['unit_text']);		    
		    $input['task_template_id'] = trim($input['task_template_id']);
      	
            //这里不判断id会报错,前面$config['rush_text'] = '抢钱'  这句有了赋值了。
            if(!empty($config['id']))

            {
          
            	pdo_update("cgc_ad_setting",$input,array('weid'=>$weid));

            }

            else

            {

            	$input['weid']=$weid;
            
            	pdo_insert('cgc_ad_setting',$input);

            }

            message('保存参数成功', 'refresh');



        }



        if(empty($config['ip'])) {

            $config['ip'] = $_SERVER['SERVER_ADDR'];

        }

        include $this->template('web/setting');
