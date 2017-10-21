<?php

defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
load()->func('tpl');
$foo  = 'codeset';
$hbid = 0;
if (checksubmit()) {
    $count = $_GPC['count'];
    $type  = $_GPC['type'];
    $jifen = $_GPC['jifen'];
    $param = array(
        'uniacid' => $_W['uniacid'],
        'hbid' => $hbid,
        'count' => $_GPC['count'],
        'type' => $type,
        'jifen' => $jifen,
        'time' => time('Ymd')
    );
    if (pdo_insert('aki_yzmjf_codenum', $param)) {
        $pcid = pdo_insertid();
        getcode($pcid, $_GPC['count'], $type, $hbid, $jifen);
        message('验证码生成成功', '', 'success');
    }
}
$pindex = max(1, intval($_GPC['page']));
$psize  = 20;
$sql    = 'select * from ' . tablename('aki_yzmjf_codenum') . 'where uniacid = :uniacid LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$prarm  = array(
    ':uniacid' => $_W['uniacid']
);
$list   = pdo_fetchall($sql, $prarm);
$count  = pdo_fetchcolumn('select count(*) from ' . tablename('aki_yzmjf_codenum') . 'where uniacid = :uniacid', $prarm);
$pager  = pagination($count, $pindex, $psize);
include $this->template('codesettings');
function getcode($pcid, $count, $type, $hbid, $jifen)
{
    global $_W;
    if (intval($count) > 0) {
        for ($i = 0; $i < $count; $i++) {
            do {
                $code1 = genkeyword(6);
            } while (pdo_fetchcolumn('select id from ' . tablename('aki_yzmjf_code') . ' where code = :code limit 1', array(
                    ':code' => $code1
                )));
            $code = array(
                'uniacid' => $_W['uniacid'],
                'piciid' => $pcid,
                'yzmjfid' => $hbid,
                'code' => $code1,
                'jifen' => $jifen,
                'status' => '1',
                'type' => $type,
                'time' => time('Ymd')
            );
            if (!pdo_insert('aki_yzmjf_code', $code))
                return false;
        }
    }
}
function genkeyword($length)
{
    $chars    = array(
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9'
    );
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $keys = array_rand($chars, 1);
        $password .= $chars[$keys];
    }
    return $password;
}
