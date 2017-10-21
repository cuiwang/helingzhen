<?php
/**
 * [WECHAT 2017]
 
 */
 defined('IN_IA') or exit('Access Denied');
        global $_W, $_GPC;
        $uid     = $_GPC['uid'];
        $op      = $_GPC['op'];
        $gzh     = pdo_fetchall("SELECT * FROM " . tablename('uni_account_users') . "where uid=$uid");
        $modules = pdo_fetchall("SELECT * FROM " . tablename('modules'));
		$user    = pdo_get('users',array('uid' =>$uid));
        if (checksubmit('submit')) {
            $credit  = $_GPC['credit'];
            $weid    = $_GPC['weid'];
            $module  = $_GPC['module'];
            $endtime = $_GPC['endtime'];
			$credit  +=$user['credit2'];
			pdo_update('users', array(
                        'credit2' => $credit
                    ), array(
                        'uid' => $uid
                    ));
//            if (!empty($credit)) {
//                $member = pdo_fetch("SELECT * FROM " . tablename('buymod_members') . " where uid=:uid", array(
//                    ':uid' => $uid
//                ));
//                if (empty($member)) {
//                    pdo_insert('buymod_members', array(
//                        'uid' => $uid,
 //                       'credit' => $credit
//                    ));
//                } else {
//                    $credit = $member['credit'] + $credit;
//                    pdo_update('buymod_members', array(
//                        'credit' => $credit
//                    ), array(
//                        'uid' => $uid
//                    ));
//                }
//           }
            if (!empty($weid) && !empty($endtime) && !empty($module)) {
                $items     = pdo_fetch("SELECT * FROM " . tablename('modules') . "where name=:name", array(
                    ':name' => $module
                ));
                $moduley   = pdo_fetch("SELECT * FROM " . tablename('buymod_mbuy') . " where weid=:weid and module=:module", array(
                    ':weid' => $weid,
                    ':module' => $module
                ));
                $buymodule = pdo_fetch("SELECT * FROM " . tablename('uni_group') . " WHERE uniacid = :uniacid", array(
                    ':uniacid' => $weid
                ));
                if (empty($buymodule)) {
                    $moduleset[] .= $module;
                } else {
                    $moduleall = unserialize($buymodule['modules']);
                    $i         = 0;
                    foreach ($moduleall as $m) {
                        $moduleset[] .= $m;
                        $i = $i++;
                    }
                    $moduleset[] .= $module;
                }
                $data   = array(
                    'modules' => iserializer($moduleset),
                    'name' => ''
                );
                $record = array(
                    'weid' => $weid,
                    'uid' => $uid,
                    'module' => $module,
                    'price' => 0,
                    'name' => $items['title'],
                    'starttime' => TIMESTAMP,
                    'endtime' => strtotime($endtime)
                );
                $data1  = array(
                    'weid' => $weid,
                    'uid' => $uid,
                    'module' => $module,
                    'status' => '1',
                    'name' => $items['title'],
                    'starttime' => TIMESTAMP,
                    'endtime' => strtotime($endtime)
                );
                if (empty($moduley)) {
                    if (empty($buymodule)) {
                        pdo_insert('uni_group', $data);
                    } else {
                        pdo_update('uni_group', $data, array(
                            'id' => $buymodule['id']
                        ));
                    }
                    pdo_insert('buymod_mbuy', $data1);
                } else {
                    if ($moduley['status'] == '1') {
                        pdo_update('buymod_mbuy', $data1, array(
                            'module' => $module
                        ));
                    } else {
                        pdo_update('uni_group', $data, array(
                            'id' => $buymodule['id']
                        ));
                        pdo_update('buymod_mbuy', $data1, array(
                            'module' => $module
                        ));
                    }
                }
                cache_delete("unisetting:{$weid}");
                cache_delete("unimodules:{$weid}:1");
                cache_delete("unimodules:{$weid}:");
                cache_delete("uniaccount:{$weid}");
                load()->model('module');
                module_build_privileges();
                pdo_insert('buymod_record', $record);
            }
            message('设置成功！', referer, 'sucess');
        }
        template('shop/user');