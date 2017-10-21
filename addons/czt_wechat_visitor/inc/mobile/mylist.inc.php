<?php

global $_W, $_GPC;
$fan              = get_fan_info();
$sql              = 'SELECT * FROM ' . tablename('czt_wechat_visitor_lists') . ' WHERE `fanid`=:fanid and `uniacid`=:uniacid order by `listid` desc';
$pars             = array();
$pars[':fanid']   = $fan['fanid'];
$pars[':uniacid'] = $_W['uniacid'];
$lists            = pdo_fetchall($sql, $pars);
include $this->template('mylist');