<?php
/**
 * 家政服务模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class Zm_housekeepnewModule extends WeModule {

    protected $_sett = 'xk_housekeepsetting';

	public function settingsDisplay($settings)
    {
        global $_W,$_GPC;

        load()->func('tpl');
        $item = pdo_fetch("SELECT * FROM ".tablename($this->_sett)." WHERE wid = '".$_W['uniacid']."'");

        if (checksubmit()) {
            load()->func('file');
            if (!empty($_FILES['apiclient_cert_file']['name'])) {

                $apiclient = file_move($_FILES['apiclient_cert_file']['tmp_name'],MODULE_ROOT.'/images/apiclient_cert'.$_W['uniacid'].'.pem');

            }
            if (!empty($_FILES['apiclient_key_file']['name'])) {

                $apiclientkey = file_move($_FILES['apiclient_key_file']['tmp_name'],MODULE_ROOT.'/images/apiclient_key'.$_W['uniacid'].'.pem');

            }
            if (!empty($_FILES['rootca_file']['name'])) {

                $rootca = file_move($_FILES['rootca_file']['tmp_name'],MODULE_ROOT.'/images/rootca'.$_W['uniacid'].'.pem');

            }
            $data['mchid'] = $_GPC['mchid'];
            $data['password'] = $_GPC['password'];
            $data['fwssxf'] = $_GPC['fwssxf'];
            $data['tgysxf'] = $_GPC['tgysxf'];
            $data['txje'] = $_GPC['txje'];
//            $data['day'] = $_GPC['day'];
            $data['qd'] = $_GPC['qd'];
            $data['fws'] = $_GPC['fws'];
            $data['tgy'] = $_GPC['tgy'];
            $data['ptcc'] = $_GPC['ptcc'];
            $data['tt'] = $_GPC['tt'];
            $data['tgytc'] = $_GPC['tgytc'];
            $data['tgytxje'] = $_GPC['tgytxje'];
            if ($apiclient&&$apiclientkey&&$rootca){
                if ($item == null){
                    $dd['apiclient_cert'] = MODULE_ROOT.'/images/apiclient_cert'.$_W['uniacid'].'.pem';
                    $dd['apiclient_key'] = MODULE_ROOT.'/images/apiclient_key'.$_W['uniacid'].'.pem';
                    $dd['rootca'] = MODULE_ROOT.'/images/rootca'.$_W['uniacid'].'.pem';
                    $dd['wid'] = $_W['uniacid'];
                    $dd['addtime'] = TIMESTAMP;
                    pdo_insert($this->_sett,$dd);
                    $succ = $this->saveSettings($data);
                    if($succ) message('提交成功',$this->createWebUrl('setting',array('c'=>'profile','a'=>'module')),'success');
                }else{
                    $dd['apiclient_cert'] = MODULE_ROOT.'/images/apiclient_cert'.$_W['uniacid'].'.pem';
                    $dd['apiclient_key'] = MODULE_ROOT.'/images/apiclient_key'.$_W['uniacid'].'.pem';
                    $dd['rootca'] = MODULE_ROOT.'/images/rootca'.$_W['uniacid'].'.pem';
                    $dd['addtime'] = TIMESTAMP;
                    pdo_update($this->_sett,$dd,array('wid'=>$_W['uniacid']));
                    $succ = $this->saveSettings($data);
                    if($succ) message('修改成功',$this->createWebUrl('setting',array('c'=>'profile','a'=>'module')),'success');
                }
            }
            $succ = $this->saveSettings($data);
            if ($succ) message('提交成功',$this->createWebUrl('setting',array('c'=>'profile','a'=>'module')),'success');
            //点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
            //在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
            //字段验证, 并获得正确的数据$dat
            //这里来展示设置项表单 易福 源码  
        }
        include $this->template('setting');
    }
}