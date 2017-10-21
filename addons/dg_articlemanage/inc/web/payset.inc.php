<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/7
 * Time: 17:07
 */
global $_W,$_GPC;
load()->func('tpl');

$key_path=MODULE_ROOT.'/cert/apiclient_key_'.$_W['uniacid'].'.pem';
$cert_path=MODULE_ROOT.'/cert/apiclient_cert_'.$_W['uniacid'].'.pem';
$root_path=MODULE_ROOT.'/cert/apiclient_root_'.$_W['uniacid'].'.pem';
if($_W['account']['level']<4){
    $extras['CURLOPT_CAINFO'] =MODULE_ROOT.'/cert/apiclient_root_'.$_W['oauth_account']['uniacid'].'.pem';
    $extras['CURLOPT_SSLCERT'] = MODULE_ROOT.'/cert/apiclient_cert_'.$_W['oauth_account']['uniacid'].'.pem';
    $extras['CURLOPT_SSLKEY'] = MODULE_ROOT.'/cert/apiclient_key_'.$_W['oauth_account']['uniacid'].'.pem';
}

$wechat['signcertexists']=file_exists($cert_path);
$wechat['signkeyexists']=file_exists($cert_path);
$wechat['signrootexists']=file_exists($cert_path);
if($_W['ispost']){

    if (empty($_FILES['wechat']['tmp_name']['signcertpath']) && !file_exists($cert_path)) {
        message('请上传微信支付CERT密钥(pem格式).',"","error");
    }
    if (empty($_FILES['wechat']['tmp_name']['signkeypath']) && !file_exists($key_path)) {
        message('请上传微信支付KEY密钥(pem格式).',"","error");
    }
    if (empty($_FILES['wechat']['tmp_name']['signrootpath']) && !file_exists($root_path)) {
        message('请上传微信支付ROOT密钥(pem格式).',"","error");
    }

    load()->func('file');
    $result=mkdirs(MODULE_ROOT.'/cert/');
    if(!$result){
        message("系统没有对本插件文件夹的写权限","",'error');
        return;
    }

    file_put_contents($cert_path, file_get_contents($_FILES['wechat']['tmp_name']['signcertpath']));
    file_put_contents($key_path, file_get_contents($_FILES['wechat']['tmp_name']['signkeypath']));
    file_put_contents($root_path, file_get_contents($_FILES['wechat']['tmp_name']['signrootpath']));

    message("上传成功！");
}



include $this->template('payset');