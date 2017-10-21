<?php
/**
 * Created by PhpStorm.
 * User: stevezheng
 * Date: 16/3/14
 * Time: 18:13
 */
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'notice';
$setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$set     = unserialize($setdata['sets']);
	if (checksubmit()) {
        if ($op == 'notice') {
            $set['notice'] = is_array($_GPC['notice']) ? $_GPC['notice'] : array();
            if (is_array($_GPC['openids'])) {
                $set['notice']['openid'] = implode(",", $_GPC['openids']);
            }
        }
        $data = array(
            'uniacid' => $_W['uniacid'],
            'sets' => iserializer($set)
        );
        if (empty($setdata)) {
            pdo_insert('yike_guess_sysset', $data);
        } else {
            pdo_update('yike_guess_sysset', $data, array(
                'uniacid' => $_W['uniacid']
            ));
        }

        $setdata   = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
            ':uniacid' => $_W['uniacid']
        ));
        $path      = IA_ROOT . "/addons/yike_guess/data/sysset";
        $cachefile = $path . "/sysset_" . $_W['uniacid'];
        if (!is_dir($path)) {
            load()->func('file');
            @mkdirs($path);
        }
        file_put_contents($cachefile, iserializer($setdata));
        message('设置保存成功！', $this->createWebUrl('template', array(
            'op' => 'notice'
        )), 'success');
    }
include $this->template('web/template');