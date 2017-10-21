<?php
global $_W, $_GPC;
$categories = pdo_fetchall(
    'select id,xmt_fl,xmt_fltime from '.tablename('wx_xinmeiti_pubcla').' where uniacid = :uniacid order by id asc',
    array(':uniacid' => $_W['uniacid'])
    );
$xmtlist= pdo_fetchall(
    'select id,xmt_fl,xmt_name,xmt_biz,xmt_img from '.tablename('wx_xinmeiti_pubnum'). 'WHERE uniacid = :uniacid',
    array(':uniacid' => $_W['uniacid'])
    );
$count=count($categories);
include_once($this->template(THEME . '/index'));
?>
        
        