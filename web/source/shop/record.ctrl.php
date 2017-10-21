<?php
/**
 * [WECHAT 2017]
 
 */
 defined('IN_IA') or exit('Access Denied');
        global $_W, $_GPC;
        $dos = array('modlist', 'paylist');
		$do = in_array($do, $dos) ? $do : 'modlist';
        $uid = $_GPC['uid'];
        if (!empty($uid)) {
            $condition .= "where uid=" . $uid;
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        if ($_W['isfounder']) {
            if ($do == 'paylist') {
                $modules = pdo_fetchall("SELECT * FROM " . tablename('buymod_payrecords') . $condition . " order by createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
                $total   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('buymod_payrecords') . $condition);
            } else {
                $modules = pdo_fetchall("SELECT * FROM " . tablename('buymod_record') . $condition . " order by starttime LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
                $total   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('buymod_record') . $condition);
            }
        } else {
            if ($do == 'paylist') {
                $modules = pdo_fetchall("SELECT * FROM " . tablename('buymod_payrecords') . " where uid=:uid and weid=:weid order by createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(
                    ':uid' => $_W['uid'],
                    ':weid' => $_W['uniacid']
                ));
                $total   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('buymod_payrecords') . " WHERE uid=:uid and weid=:weid", array(
                    ':uid' => $_W['uid'],
                    ':weid' => $_W['uniacid']
                ));
            } else {
                $modules = pdo_fetchall("SELECT * FROM " . tablename('buymod_record') . " where uid=:uid and weid=:weid order by starttime LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(
                    ':uid' => $_W['uid'],
                    ':weid' => $_W['uniacid']
                ));
                $total   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('buymod_record') . " WHERE uid=:uid and weid=:weid", array(
                    ':uid' => $_W['uid'],
                    ':weid' => $_W['uniacid']
                ));
            }
        }
        $pager = pagination($total, $pindex, $psize);
        template('shop/record');