<?php
/**
 * 众筹模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('ZC_ROOT', IA_ROOT . '/addons/hx_zhongchou');
class Hx_zhongchouModule extends WeModule {

	public function settingsDisplay($settings) {
        global $_GPC, $_W;
        
        if (checksubmit()) {
            load()->func('file');
            mkdirs(ZC_ROOT . '/cert');
            $r = true;
            $pemname = isset($pemname) ? $pemname : time();
            if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(ZC_ROOT . '/cert/apiclient_cert.pem.' . $pemname, trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(ZC_ROOT . '/cert/apiclient_key.pem.' . $pemname, trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(ZC_ROOT . '/cert/rootca.pem.' . $pemname, trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /addons/hx_zhongchou/cert/ 目录可写');
            }
            $cfg = array(
                'noticeemail' => $_GPC['noticeemail'],
                'kfid' => $_GPC['kfid'],
                'k_templateid' => $_GPC['k_templateid'],
                'kfirst' => $_GPC['kfirst'],
                'kfoot' => $_GPC['kfoot'],
                'm_templateid' => $_GPC['m_templateid'],
                'mfirst' => $_GPC['mfirst'],
                'mfoot' => $_GPC['mfoot'],
                'ispublish' => intval($_GPC['ispublish']),
                'shopname' => $_GPC['shopname'],
                'address' => $_GPC['address'],
                'phone' => $_GPC['phone'],
                'email' => $_GPC['email'],
                'officialweb' => $_GPC['officialweb'],
                'description'=>  htmlspecialchars_decode($_GPC['description']),
                'appid' => trim($_GPC['appid']),
                'secret' => trim($_GPC['secret']),
                'mchid' => trim($_GPC['mchid']),
                'password' => trim($_GPC['password']),
                'ip' => trim($_GPC['ip']),
                'pemname' => $pemname,
                'share_title' => $_GPC['share_title'],
                'share_img' => $_GPC['share_img'],
                'share_description' => $_GPC['share_description'],
            );
            if (!empty($_GPC['logo'])) {
                $cfg['logo'] = $_GPC['logo'];
            }
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
		load()->func('tpl');
		include $this->template('setting');
    }

}