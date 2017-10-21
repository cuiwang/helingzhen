<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;
$weid              = $_W['uniacid'];
$this1             = 'no1';
$action            = 'schoolset';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,is_openht FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$city              = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = '{$weid}' And type = 'city' ORDER BY ssort DESC", array(':weid' => $weid));
$area              = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = '{$weid}' And type = '' ORDER BY ssort DESC", array(':weid' => $weid));
$schooltype        = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = '{$weid}' ORDER BY ssort DESC", array(':weid' => $weid));
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'post';
if($operation == 'post'){
    $id      = intval($_GPC['schoolid']);
    $reply   = pdo_fetch("select * from " . tablename($this->table_index) . " where id=:id and weid =:weid", array(':id' => $schoolid, ':weid' => $weid));
    $quyu    = pdo_fetch("select name from " . tablename($this->table_area) . " where id=:id and weid =:weid", array(':id' => $reply['areaid'], ':weid' => $weid));
    $payweid = pdo_fetchall("SELECT * FROM " . tablename('account_wechats') . " where level = 4 ORDER BY acid ASC");
    $user    = pdo_fetchall("SELECT * FROM " . tablename('users') . " where status = 2 ORDER BY uid DESC");
    $icon    = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = 1 ORDER by ssort ASC", array(':weid' => $weid, ':schoolid' => $schoolid));
    $sign    = unserialize($reply['signset']);
    $card    = unserialize($reply['cardset']);
    if(checksubmit('submit')){
        $data = array(
            'weid'                => intval($weid),
            'uid'                 => intval($_GPC['uid']),
            'cityid'              => intval($_GPC['cityid']),
            'areaid'              => intval($_GPC['area']),
            'typeid'              => intval($_GPC['type']),
            'title'               => trim($_GPC['title']),
            'info'                => trim($_GPC['info']),
            'content'             => trim($_GPC['content']),
            'zhaosheng'           => trim($_GPC['zhaosheng']),
            'tel'                 => trim($_GPC['tel']),
            'gonggao'             => trim($_GPC['gonggao']),
            'logo'                => trim($_GPC['logo']),
            'thumb'               => trim($_GPC['thumb']),
            'qroce'               => trim($_GPC['qroce']),
            'address'             => trim($_GPC['address']),
            'location_p'          => trim($_GPC['location_p']),
            'location_c'          => trim($_GPC['location_c']),
            'location_a'          => trim($_GPC['location_a']),
            'lng'                 => trim($_GPC['baidumap']['lng']),
            'lat'                 => trim($_GPC['baidumap']['lat']),
            'recharging_password' => trim($_GPC['recharging_password']),
            'is_show'             => intval($_GPC['is_show']),
            'is_showew'           => intval($_GPC['is_showew']),
            'is_openht'           => intval($_GPC['is_openht']),
            'is_zjh'              => intval($_GPC['is_zjh']),
            'is_rest'             => intval($_GPC['is_rest']),
            'is_sms'              => intval($_GPC['is_sms']),
            'is_hot'              => intval($_GPC['is_hot']),
            'is_cost'             => intval($_GPC['is_cost']),
            'is_kb'               => intval($_GPC['is_kb']),
            'is_video'            => intval($_GPC['is_video']),
            'is_fbvocie'          => intval($_GPC['is_fbvocie']),
            'is_fbnew'            => intval($_GPC['is_fbnew']),
            'txid'                => trim($_GPC['txid']),
            'txms'                => trim($_GPC['txms']),
            'is_sign'             => intval($_GPC['is_sign']),
            'is_cardpay'          => intval($_GPC['is_cardpay']),
            'is_cardlist'         => intval($_GPC['is_cardlist']),
            'is_recordmac'        => intval($_GPC['is_recordmac']),
            'is_wxsign'           => intval($_GPC['is_wxsign']),
            'is_signneedcomfim'   => intval($_GPC['is_signneedcomfim']),
            'isopen'              => intval($_GPC['isopen']),
            'issale'              => intval($_GPC['issale']),
            'shoucename'          => trim($_GPC['shoucename']),
            'videopic'            => trim($_GPC['videopic']),
            'videoname'           => trim($_GPC['videoname']),
            'jxstart'             => trim($_GPC['jxstart']),
            'jxend'               => trim($_GPC['jxend']),
            'lxstart'             => trim($_GPC['lxstart']),
            'lxend'               => trim($_GPC['lxend']),
            'jxstart1'            => trim($_GPC['jxstart1']),
            'jxend1'              => trim($_GPC['jxend1']),
            'lxstart1'            => trim($_GPC['lxstart1']),
            'lxend1'              => trim($_GPC['lxend1']),
            'jxstart2'            => trim($_GPC['jxstart2']),
            'jxend2'              => trim($_GPC['jxend2']),
            'lxstart2'            => trim($_GPC['lxstart2']),
            'lxend2'              => trim($_GPC['lxend2']),
            'ssort'               => intval($_GPC['ssort']),
            'tpic'                => trim($_GPC['tpic']),
            'spic'                => trim($_GPC['spic']),
            'dateline'            => TIMESTAMP,
        );

        if(istrlen($data['title']) == 0){
            $this->imessage('没有输入标题.', referer(), 'error');
        }
        if(istrlen($data['title']) > 30){
            $this->imessage('标题不能多于30个字。', referer(), 'error');
        }
        if($_GPC['is_rest']['0'] == 1){
            if(!$_GPC['shoucename']){
                $this->imessage('启用评价系统时，必须为评价系统命名', referer(), 'error');
            }
        }
        if($_GPC['is_fbnew'] == 1){
            if(!$_GPC['txid'] || !$_GPC['txms']){
                $this->imessage('启用视频必须设置腾讯云appid与密钥!', referer(), 'error');
            }
        }
        if($_GPC['is_video']['0'] == 1){
            if(!$_GPC['videoname']){
                $this->imessage('启用直播和监控系统时，必须为该系统命名', referer(), 'error');
            }
        }

        if($_GPC['is_sign'] == 1){
            $temp            = array(
                'is_idcard' => $_GPC['is_idcard'],
                'is_nj'     => $_GPC['is_nj'],
                'is_bj'     => $_GPC['is_bj'],
                'is_bir'    => $_GPC['is_bir'],
                'is_bd'     => $_GPC['is_bd'],
                'payweid'   => empty($_GPC['bmpayweid']) ? $weid : $_GPC['bmpayweid']
            );
            $data['signset'] = serialize($temp);
        }else{
            $data['signset'] = '';
        }
        if($_GPC['is_cardpay'] == 1){
            $temp1           = array(
                'cardcost' => $_GPC['cardcost'],
                'cardtime' => $_GPC['cardtime'],
                'endtime1' => intval($_GPC['endtime1']),
                'endtime2' => strtotime($_GPC['endtime2']),
                'payweid'  => empty($_GPC['kqpayweid']) ? $weid : $_GPC['kqpayweid']
            );
            $data['cardset'] = serialize($temp1);
        }else{
            $data['cardset'] = '';
        }
        if(!empty($id)){
            unset($data['dateline']);
            pdo_update($this->table_index, $data, array('id' => $id, 'weid' => $weid));
        }else{
            pdo_insert($this->table_index, $data);
        }
        $this->imessage('操作成功!', referer(), 'success');
    }
}elseif($operation == 'change8'){
    $is_kb = intval($_GPC['is_kb']);

    $data = array('is_kb' => $is_kb);

    pdo_update($this->table_index, $data, array('id' => $schoolid));
}elseif($operation == 'change7'){
    $is_zjh = intval($_GPC['is_zjh']);

    $data = array('is_zjh' => $is_zjh);

    pdo_update($this->table_index, $data, array('id' => $schoolid));
}elseif($operation == 'change6'){
    $is_showew = intval($_GPC['is_showew']);

    $data = array('is_showew' => $is_showew);

    pdo_update($this->table_index, $data, array('id' => $schoolid));
}elseif($operation == 'change5'){
    $status = intval($_GPC['status']);
    $id     = intval($_GPC['id']);
    $data   = array('status' => $status);
    pdo_update($this->table_icon, $data, array('id' => $id));
}elseif($operation == 'change4'){
    $isopen = intval($_GPC['isopen']);

    $data = array('isopen' => $isopen);

    pdo_update($this->table_index, $data, array('id' => $schoolid));
}elseif($operation == 'change3'){
    $is_rest = intval($_GPC['is_rest']);

    $data = array('is_rest' => $is_rest);

    pdo_update($this->table_index, $data, array('id' => $schoolid));
}elseif($operation == 'change2'){
    $is_hot = intval($_GPC['is_hot']);

    $data = array('is_hot' => $is_hot);

    pdo_update($this->table_index, $data, array('id' => $schoolid));
}elseif($operation == 'change1'){
    $is_video = intval($_GPC['is_video']);

    $data = array('is_video' => $is_video);

    pdo_update($this->table_index, $data, array('id' => $schoolid));
}
include $this->template('web/schoolset');
?>