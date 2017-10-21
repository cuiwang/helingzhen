<?php
/**
 * [WECHAT 2017]
 
 */
 defined('IN_IA') or exit('Access Denied');
        global $_W, $_GPC;
        $id        = $_GPC['id'];
        $module    = pdo_fetch("SELECT * FROM " . tablename('buymod_mbuy') . " where id=:id", array(
            ':id' => $id
        ));
        $buymodule = pdo_fetch("SELECT * FROM " . tablename('uni_group') . " WHERE uniacid = :uniacid", array(
            ':uniacid' => $module['weid']
        ));
        $moduleall = unserialize($buymodule['modules']);
        $i         = 0;
        foreach ($moduleall as $m) {
            if ($m != $module['module']) {
                $modules[] .= $m;
            }
            $i = $i++;
        }
        $data = array(
            'modules' => iserializer($modules),
            'name' => ''
        );
        if (empty($buymodule)) {
            message('该公众号无此权限！', referer, 'warning');
        } else {
            pdo_update('uni_group', $data, array(
                'id' => $buymodule['id']
            ));
        }
        pdo_update('buymod_mbuy', array(
            'status' => '2'
        ), array(
            'id' => $id
        ));
        cache_delete("unisetting:{$weid}");
        cache_delete("unimodules:{$weid}:1");
        cache_delete("unimodules:{$weid}:");
        cache_delete("uniaccount:{$weid}");
        message('禁用成功！', referer, 'sucess');