<?php
/**
 * Created by PhpStorm.
 * User: yike
 * Date: 2016/5/25
 * Time: 12:01
 */
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'task';
$setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$set = unserialize($setdata['sets']);
if (empty($set)) {
    $set = array();
}
$sec = pdo_fetch("select sec from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
if (empty($sec)) {
    $sec = array();
}
if (empty($setdata)) {
    pdo_insert('yike_guess_sysset', array('uniacid' => $_W['uniacid'], 'sec' => '', 'sets' => ''));
}

$rule_keyword = pdo_get('rule_keyword', array('uniacid'=>$_W['uniacid'], 'content'=>'YIKE_GUESS'));

if (!$rule_keyword) {
    m('qrcode')->createRule();
}

$uniacid = $_W['uniacid'];
if ($op == 'pay') {
    if (checksubmit()) {
        $set['pay'] = is_array($_GPC['pay']) ? $_GPC['pay'] : array();
        $sec['cert'] = upload_cert('weixin_cert_file');
        $sec['key'] = upload_cert('weixin_key_file');
        $sec['root'] = upload_cert('weixin_root_file');
        if (empty($sec['cert']) || empty($sec['key']) || empty($sec['root'])) {
        }
        $result = pdo_update('yike_guess_sysset', array(
            'sec' => iserializer($sec)
        ), array(
            'uniacid' => $_W['uniacid']
        ));
    }
    include $this->template('web/pay');
}else if ($op == 'share') {
    if (checksubmit()) {
        // var_dump($_GPC['bet_name']);die;
        $set['bet_name']['bet_name'] = $_GPC['bet_name'];
        $set['shop']['name'] = $_GPC['name'];
        // $set['money']['money'] = $_GPC['money'];
        $set['share'] = $_GPC['share'];
        $set['follow']['href'] = $_GPC['href'];
        $data = array(
            'sets'=>serialize($set)
        );
        pdo_update('yike_guess_sysset', $data, array(
            'uniacid' => $uniacid
        ));
        message('平台信息更新成功！', $this->createWebUrl('setting', array(
            'op' => 'share'
        )), 'success');
    }
    load()->func('tpl');
    include $this->template('web/share');
}if ($op == 'task'){
    if (checksubmit()) {
        $use=$_GPC['use'];
        $sign=$_GPC['sign'];
        $firstf=$_GPC['firstf'];
        $second=$_GPC['second'];
        $thirdly=$_GPC['thirdly'];
        $guessing=$_GPC['guessing'];
        $set['task']['integral'] = '';
        if ($use==1) {
            $set['task']['integral']['open'] = $_GPC['open'];
        }
        if ($sign==1) {
            $set['task']['integral']['integral'] = $_GPC['integral'];
        } 
        if ($firstf==1) {
            $set['task']['integral']['one'] = $_GPC['one'];
        } 
        if ($second==1) {
            $set['task']['integral']['two'] = $_GPC['two'];
        } 
        if ($thirdly==1) {
            $set['task']['integral']['three'] = $_GPC['three'];
        } 
        if ($guessing==1) {
            $set['task']['integral']['ones'] = $_GPC['ones'];
        }

        $set['on_off']['task'] = $_GPC['on_off'];
        if ($set['on_off']['task']==0) {
            $set['task']['integral'] = '';
        }
        $data = array(
            'sets'=>serialize($set),
        );
        $task=pdo_update('yike_guess_sysset', $data, array(
            'uniacid'=>$uniacid
        ));
        message('任务更新成功！', $this->createWebUrl('setting', array(
            'op' => 'task'
        )), 'success');
    }
    load()->func('tpl');
    include $this->template('web/task');
}else if($op == 'signin'){
    if (checksubmit()) {
        $one=$_GPC['one'];
        $two=$_GPC['two'];
        $three=$_GPC['three'];
        $four=$_GPC['four'];
        $five=$_GPC['five'];
        $set['sets']['report'] = '';
        if ($one==1) {
            $set['sets']['report']['report1']=$_GPC['report1'];
        }
        if ($two==1) {
            $set['sets']['report']['report2']=$_GPC['report2'];
        }
        if ($three==1) {
            $set['sets']['report']['report3']=$_GPC['report3'];
        }
        if ($four==1) {
            $set['sets']['report']['report4']=$_GPC['report4'];
        }
        if ($five==1) {
            $set['sets']['report']['report5']=$_GPC['report5'];
        }
        $set['on_off']['signin'] = '';
        $set['on_off']['signin'] = $_GPC['on_off'];
        if ($set['on_off']['signin']==0) {
                $set['sets']['report'] = '';
            }
            // var_dump($set);die;
        $data = array(
            'sets'=>serialize($set),
        );
        $task=pdo_update('yike_guess_sysset', $data, array(
            'uniacid'=>$uniacid
        ));
        message('任务更新成功！', $this->createWebUrl('setting', array(
            'op' => 'signin'
        )), 'success');
    }
    include $this->template('web/signin');
}else if($op == 'poster'){
    if (checksubmit()) {
        $set['poster'] = $_GPC['thumb'];
        $set['poster_text'] = $_GPC['poster_text'];
        $set['qr_code'] = $_GPC['qr_code'];
        $data = array(
            'sets'=>serialize($set)
        );
        pdo_update('yike_guess_sysset', $data, array(
            'uniacid' => $uniacid
        ));
        message('更新海报成功！', $this->createWebUrl('setting', array(
            'op' => 'poster'
        )), 'success');
    }
    load()->func('tpl');
    include $this->template('web/poster');
}else if ($op == 'clean_poster') {
    if (checksubmit()) {
        load()->func('file');
        @rmdirs(IA_ROOT . '/addons/yike_guess/data/poster/' . $_W['uniacid']);
        @rmdirs(IA_ROOT . '/addons/yike_guess/data/qrcode/' . $_W['uniacid']);
        $result = pdo_delete('yike_guess_qr', array('acid'=>$_W['acid']));
        message('清空海报缓存成功！', $this->createWebUrl('setting', array(
            'op' => 'poster'
        )), 'success');
    }
}

function upload_cert($fileinput) {
    global $_W;
    $path = IA_ROOT . "/addons/yike_guess/cert";
    load()->func('file');
    mkdirs($path, '0777');
    $f = $fileinput . '_' . $_W['uniacid'] . '.pem';
    $outfilename = $path . "/" . $f;
    $filename = $_FILES[$fileinput]['name'];
    $tmp_name = $_FILES[$fileinput]['tmp_name'];
    if (!empty($filename) && !empty($tmp_name)) {
        $ext = strtolower(substr($filename, strrpos($filename, '.')));
        if ($ext != '.pem') {
            message('证书文件格式错误: ' . $fileinput . "!", '', 'error');
        }
        return file_get_contents($tmp_name);
    }
    return "";
}