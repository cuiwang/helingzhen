<?php
global $_W, $_GPC;
$settings         = $this->module['config'];
$fan              = get_fan_info();
$sql              = 'SELECT * FROM ' . tablename('czt_wechat_visitor_lists') . 'as a left join ' . tablename('czt_wechat_visitor_fans') . 'as b on a.fanid=b.fanid  WHERE a.`listid`=:listid and a.`uniacid`=:uniacid';
$pars             = array();
$pars[':listid']  = intval($_GPC['listid']);
$pars[':uniacid'] = $_W['uniacid'];
$list             = pdo_fetch($sql, $pars);
if (!empty($list)) {
    if ($fan['fanid'] != $list['fanid']) {
        $data                = array();
        $data['create_time'] = TIMESTAMP;
        $data['listid']      = intval($_GPC['listid']);
        $data['fanid']       = $fan['fanid'];
        $ret                 = pdo_insert('czt_wechat_visitor_visitors', $data);
        if (empty($ret)) {
            exit('pdo_insert error!');
        }
    }
    $sql             = 'SELECT fanid,COUNT(*) as `count`,MAX(create_time) as `create_time` FROM ' . tablename('czt_wechat_visitor_visitors') . ' WHERE `listid`=:listid GROUP by fanid';
    $pars            = array();
    $pars[':listid'] = intval($_GPC['listid']);
    $visitors        = array();
    $visitors        = pdo_fetchall($sql, $pars);
    $total           = 0;
    $fanids          = '';
    $fans_count      = array();
    foreach ($visitors as $key => $value) {
        $fans_count[$value['fanid']]['count']       = $value['count'];
        $fans_count[$value['fanid']]['create_time'] = $value['create_time'];
        $fanids .= $value['fanid'] . ',';
        $total += $value['count'];
    }
    $fans = array();
    if ($fanids) {
        $fanids = rtrim($fanids, ",");
        $sql    = 'SELECT * FROM ' . tablename('czt_wechat_visitor_fans') . ' WHERE `fanid` in (' . $fanids . ') order by fanid desc';
        $fans   = pdo_fetchall($sql);
        foreach ($fans as $key => $value) {
            $fans[$key]['count']       = $fans_count[$value['fanid']]['count'];
            $fans[$key]['create_time'] = $fans_count[$value['fanid']]['create_time'];
        }
    }
    if ($fan['fanid'] == $list['fanid']) {
        include $this->template('index_1');
    } else {
        include $this->template('index_2');
    }
}