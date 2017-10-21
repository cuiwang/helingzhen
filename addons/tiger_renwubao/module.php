<?php
/**
 * 模块定义
 *
 * @author 老虎
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('OB_ROOT', IA_ROOT . '/attachment/tiger_renwubao');
class tiger_renwubaoModule extends WeModule {
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
        $token=pdo_fetch('select * from '.tablename($this->modulename."_token")." where weid='{$_W['uniacid']}'");
        if(pdo_tableexists('ewei_shop_member_group')){
          $ewgroup=pdo_fetchall('select * from '.tablename("ewei_shop_member_group")." where uniacid='{$_W['uniacid']}'");
        }else{
          $ewgroup['error']='人人分销商城不存在！您未安装人人商城';
        }
        

        load ()->func ( 'tpl' );     
		if(checksubmit()) {
            //echo '<pre>';
            //print_r($_GPC);
            //exit;
             load()->func('file');
             mkdirs(OB_ROOT . '/cert/'.$_W['uniacid']);
             $r=true;
            if(!empty($_GPC['cert'])) { 
                $ret = file_put_contents(OB_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
                $r = $r && $ret;               
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(OB_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(OB_ROOT.'/cert/'.$_W['uniacid'].'/rootca.pem', trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /attachment/tiger_renwubao/cert/ 目录可写');
            }
            $payment = pdo_fetch("SELECT payment FROM " . tablename('uni_settings') . " WHERE uniacid= '{$_W['uniacid']}'");
			$payment = unserialize($payment['payment']);
            $appid = $_W['account']['key'];
		    $secret = $_W['account']['secret'];
			$cfg = array(
                //'contact'=>htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['contact']),ENT_QUOTES),
                'rwappid'=>$_GPC['rwappid'],
                'rwsecret'=>$_GPC['rwsecret'],
                'rwmchid'=>$_GPC['rwmchid'],
                'rwapikey'=>$_GPC ['rwapikey'],
                'rwip'=>$_GPC['rwip'],
                    'hbsl'=>$_GPC['hbsl'],
                    'sextype' => $_GPC ['sextype'],//性别
                    'dqtype' => $_GPC ['dqtype'],//地区
                    'province' => $_GPC['birth']['province'],
                    'city' => $_GPC ['birth']['city'],
                    'district' => $_GPC ['birth']['district'],
                'yzbq' => $_GPC ['yzbq'],
                'rrbq' => $_GPC ['rrbq'],
                         'bjtp' => $_GPC ['bjtp'],
                         'bjys' => $_GPC ['bjys'],
                         'bttitle' => $_GPC ['bttitle'],
                  'rwlx' => $_GPC ['rwlx'],
                    'cardid' => $_GPC ['cardid'],
                    'rwmbtxid' => $_GPC ['rwmbtxid'],
                    'rwfirstdata1' => $_GPC ['rwfirstdata1'],
                    'rwremarkdata1' => $_GPC ['rwremarkdata1'],
                    'rwfirstdata2' => $_GPC ['rwfirstdata2'],
                    'rwremarkdata2' => $_GPC ['rwremarkdata2'],
                    'rwlink' => $_GPC ['rwlink'],
                        'rwmb' => $_GPC ['rwmb'],
                        'rwbcolor1' => $_GPC ['rwbcolor1'],
                        'rwbcolor2' => $_GPC ['rwbcolor2'],
                        'rwbcolor3' => $_GPC ['rwbcolor3'],
                        'rwbcolor4' => $_GPC ['rwbcolor4'],
                        'starttime' => strtotime($_GPC ['starttime']),
                        'endtime' => strtotime($_GPC ['endtime']),
                    'rwgzmsg'=>htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['rwgzmsg']),ENT_QUOTES),
                    'rwsjmsg'=>htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['rwsjmsg']),ENT_QUOTES),
                    'cfinfo'=>htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['cfinfo']),ENT_QUOTES),
                        
			);
			if ($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
			
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}