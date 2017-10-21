<?php


defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
load()->func('logging');
$foo  = empty($_GPC['foo']) ? 'sendlist' : $_GPC['foo'];
$op   = empty($_GPC['op']) ? 'sendlist' : $_GPC['op'];
$hbid = 0;
if ($op == 'sendlist') {
    $res   = sendList($hbid);
    $list  = $res['list'];
    $pager = $res['pager'];
    include $this->template('sendlist');
}
function sendList($hbid)
{
    global $_W, $_GPC;
    $content           = "";
    $pindex            = max(1, intval($_GPC['page']));
    $psize             = 15;
    $param             = array();
    $param[':uniacid'] = $_W['uniacid'];
    $content           = ' ';
    $content .= " and yzmjfid = " . $hbid;
    $listSql         = "select * from " . tablename("aki_yzmjf_sendlist") . "  where uniacid = :uniacid   " . $content . " order by time desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
    $list            = pdo_fetchall($listSql, $param);
    $sql             = "select count(*) from " . tablename("aki_yzmjf_sendlist") . "  where uniacid = :uniacid  " . $content;
    $total           = pdo_fetchcolumn($sql, $param);
    $pager           = pagination($total, $pindex, $psize);
    $result          = array();
    $result['list']  = $list;
    $result['pager'] = $pager;
    return $result;
}