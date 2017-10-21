<?php

function p($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function is_kefu(){
    global $_W;
    $table = 'tc_guestbook_admin';
    return pdo_fetch("SELECT * FROM ".tablename($table)." WHERE `uniacid`=:uniacid AND `openid`=:openid",array(':uniacid'=>$_W['uniacid'], ':openid'=>$_W['openid']));
}

//根据openId 获取微信用户昵称
function openid2name($openid){
	global $_W;
	$nickname = pdo_fetchcolumn("SELECT `nickname` FROM ".tablename('mc_mapping_fans')." WHERE `uniacid`=:uniacid AND `openid`=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
	return $nickname;
}

function mk_qr($url,$name='',$id){
    global $_W;
    require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
    $errorCorrectionLevel = "L";
    $matrixPointSize = "5";
    if($name == '') $name = time();
    $file_dir = MODULE_ROOT.'/qrcode/'.$id.'/';

    if(!file_exists($file_dir)){
        //目录不存在，创建目录
        mkdir($file_dir,0777,true);
    }
    $file_root = $file_dir . $name.'.png';
    if(!file_exists($file_root)){
        //图片不存在，创建图片
            QRcode::png($url, $file_root, $errorCorrectionLevel, $matrixPointSize);     
    }
    return $id.'/'.$name.'.png';
}