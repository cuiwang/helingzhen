<?php
global $_W, $_GPC;
$fan = array();
$fan['openid'] = $_W['openid'];
$action = $_GPC['action'];
$params = @json_decode(htmlspecialchars_decode($_GPC['params']), true);

if($action == 'jscfg') {
    $ret = array();
    $api = new Api($this->module['config']['api'], $this);
    $url = $params['url'];
    $val = $api->shareDataCreate($url);
    if(!is_error($val)) {
        $ret['error'] = 0;
        $ret['cfg']['appId'] = $val['appid'];
        $ret['cfg']['nonceStr'] = $val['nonce'];
        $ret['cfg']['timestamp'] = $val['timestamp'];
        $ret['cfg']['signature'] = $val['signature'];
        $ret['url'] = $url;
    } else {
        $ret['error'] = 1001;
        $ret['error_msg'] = '系统未能初始化';
    }
    util_json($ret);
}

if($action == 'sayhello/get') {
    $id = intval($params['id']);
    $r = new Record();
    $record = $r->getOne($id);
    if(!empty($record)) {
        $r->flowIncrease($id);
        $ret['error'] = 0;
        $ret['isupload'] = 1;
        $ret['mediaid'] = $record['wish']['media'];
        $ret['mediaurl'] = $record['wish']['url'];
        $ret['id'] = $id;
    } else {
        $ret['error'] = 1001;
        $ret['error_msg'] = '错误的录音记录';
    }
    util_json($ret);
}

if($action == 'sayhello/send') {
    $ret = array();
    if($params['isupload'] == '1') {
        $api = new Api($this->module['config']['api'], $this);
        $key = 'mb-voices/' . md5($params['mediaid']) . '.mp3';
        $val = $api->downloadMedia($params['mediaid'], $key);
        if(!is_error($val)) {
            $rec = array();
            $rec['openid'] = $fan['openid'];
            $rec['wish'] = array(
                'media' => $params['mediaid'],
                'url' => $val,
            );
            $rec['wish'] = serialize($rec['wish']);
            $r = new Record();
            $id = $r->create($rec);
            if(!is_error($id)) {
                $ret['error'] = 0;
                $ret['isupload'] = 1;
                $ret['mediaid'] = $params['mediaid'];
                $ret['mediaurl'] = $val;
                $ret['id'] = $id;
            }
            if(empty($ret)) {
                $ret['error'] = 1001;
                $ret['error_msg'] = '未能正确保存录音, 请稍后重试';
            }
        } else {
            $ret['error'] = 1001;
            $ret['error_msg'] = $val['message'];
        }
    } else {
        $rec = array();
        $rec['openid'] = $fan['openid'];
        $rec['wish'] = "voice-{$params['type']}";
        $r = new Record();
        $id = $r->create($rec);
        
        $ret['error'] = 0;
        $ret['isupload'] = 0;
    }
    util_json($ret);
}
