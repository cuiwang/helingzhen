<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */
 defined('IN_IA') or exit('Access Denied');
        global $_W, $_GPC;
        load()->func('tpl');
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $dos = array('list', 'post', 'mlist');
		$do = in_array($do, $dos) ? $do : 'list';
        $user   = pdo_fetch("SELECT * FROM " . tablename('users') . " where uid=:uid", array(
            ':uid' => $_W['uid']
        ));
        $member = pdo_fetch("SELECT * FROM " . tablename('buymod_members') . " where uid=:uid", array(
            ':uid' => $_W['uid']
        ));
        if (empty($member)) {
            pdo_insert('buymod_members', array(
                'uid' => $_W['uid']
            ));
        }
        if ($_W['isfounder']) {
            if ($do == 'mlist') {
                $list  = pdo_fetchall("SELECT * FROM " . tablename('users'));
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('users'));
            } else {
                $modules = pdo_fetchall("SELECT * FROM " . tablename('buymod_mbuy') . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
                $total   = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('buymod_mbuy'));
            }
        } else {
            $modules = pdo_fetchall("SELECT * FROM " . tablename('buymod_mbuy') . " where uid=:uid and weid=:weid LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(
                ':uid' => $_W['uid'],
                ':weid' => $_W['uniacid']
            ));
            $total   = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('buymod_mbuy') . " where uid=:uid and weid=:weid", array(
                ':uid' => $_W['uid'],
                ':weid' => $_W['uniacid']
            ));
        }
        $pager = pagination($total, $pindex, $psize);
        if ($do == 'post') {
        }
        template('members/user');