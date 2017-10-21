<?php
require '../../framework/bootstrap.inc.php';
global $_W, $_GPC;
$weid = $_W['uniacid'];
ignore_user_abort(true);
set_time_limit(0);
while (1) {
    $starsit   = 1;
    $now       = time();
    $moduleall = pdo_fetchall("SELECT * FROM " . tablename('buymod_mbuy') . "where status=1");
    foreach ($moduleall as $row) {
        if ($row['endtime'] < $now) {
            $buymodule = pdo_fetch("SELECT * FROM " . tablename('uni_group') . " WHERE uniacid = :uniacid", array(
                ':uniacid' => $row['weid']
            ));
            $i         = 0;
            foreach (unserialize($buymodule['modules']) as $m) {
                if ($m != $row['module']) {
                    $modules[] .= $m;
                }
                $i = $i++;
            }
            $data = array(
                'modules' => iserializer($modules),
                'name' => ''
            );
            if (!empty($buymodule)) {
                pdo_update('uni_group', $data, array(
                    'id' => $buymodule['id']
                ));
            }
            pdo_update('buymod_mbuy', array(
                'status' => '2'
            ), array(
                'id' => $row['id']
            ));
            cache_delete("unisetting:{$row['weid']}");
            cache_delete("unimodules:{$row['weid']}:1");
            cache_delete("unimodules:{$row['weid']}:");
            cache_delete("uniaccount:{$row['weid']}");
        }
    }
    sleep(20);
}
?>