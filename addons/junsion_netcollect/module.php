<?php
defined('IN_IA') or die('Access Denied');
define('IMG', '../addons/junsion_netcollect/template/mobile/');
define('OD_ROOT', IA_ROOT . '/web/netcollect');
class Junsion_netcollectModule extends WeModule
{
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W, $_GPC;
        $rule      = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        $prize     = pdo_fetchall('select * from ' . tablename($this->modulename . "_prize") . " where rid='{$rid}' order by id");
        $coupons   = pdo_fetchall('select *,couponid as id from ' . tablename('activity_coupon') . " where uniacid={$_W['uniacid']} and endtime > '" . time() . "' ORDER BY endtime ASC");
        $exchanges = pdo_fetchall('select * from ' . tablename('activity_exchange') . " where uniacid={$_W['uniacid']} and endtime > '" . time() . "' ORDER BY endtime ASC");
        $wxcoupon  = pdo_fetchall('select * from ' . tablename('coupon') . " where uniacid={$_W['uniacid']}");
        $slider    = unserialize($rule['sliders']);
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_W, $_GPC;
        $picurl = $_GPC['picurl'];
        if (!empty($picurl)) {
            foreach ($picurl as $key => $value) {
                if (!empty($value)) {
                    $sliders[] = array(
                        'rid' => $rid,
                        'picurl' => $value,
                        'title' => $_GPC['ptitle'][$key],
                        'link' => $_GPC['link'][$key]
                    );
                }
            }
        }
        $word = $_GPC['word'];
        if (!empty($word)) {
            foreach ($word as $key => $value) {
                if (!empty($value)) {
                    $words[] = array(
                        'word' => $value,
                        'rate' => $_GPC['rate'][$key]
                    );
                }
            }
        }
        $data = array(
            'rid' => $rid,
            'weid' => $_W['weid'],
            'title' => $_GPC['title'],
            'thumb' => $_GPC['thumb'],
            'description' => $_GPC['description'],
            'hword' => $_GPC['hword'],
            'title2' => $_GPC['title2'],
            'thumb2' => $_GPC['thumb2'],
            'description2' => $_GPC['description2'],
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
            'describelimit' => $_GPC['describelimit'],
            'describelimit2' => $_GPC['describelimit2'],
            'content' => htmlspecialchars_decode($_GPC['content']),
            'stitle' => $_GPC['stitle'],
            'sthumb' => $_GPC['sthumb'],
            'sdesc' => $_GPC['sdesc'],
            'atitle' => $_GPC['atitle'],
            'athumb' => $_GPC['athumb'],
            'lastshow' => $_GPC['lastshow'],
            'adesc' => $_GPC['adesc'],
            'rank' => intval($_GPC['rank']),
            'firstnum' => intval($_GPC['firstnum']),
            'sharenum1' => intval($_GPC['sharenum1']),
            'sharenum2' => intval($_GPC['sharenum2']),
            'daynum' => intval($_GPC['daynum']),
            'isinfo' => $_GPC['isinfo'],
            'awardtips' => $_GPC['awardtips'],
            'isrealname' => $_GPC['isrealname'],
            'ismobile' => $_GPC['ismobile'],
            'isqq' => $_GPC['isqq'],
            'isemail' => $_GPC['isemail'],
            'isaddress' => $_GPC['isaddress'],
            'citys' => $_GPC['citys'],
            'outtips' => $_GPC['outtips'],
            'outurl' => $_GPC['outurl'],
            'prizetime' => $_GPC['prizetime'],
            'slideH' => $_GPC['slideH'],
            'isinfo2' => intval($_GPC['isinfo2']),
            'advImg' => $_GPC['advImg'],
            'advTime' => intval($_GPC['advTime']),
            'sliders' => serialize($sliders),
            'words' => serialize($words),
            'rmode' => $_GPC['rmode'],
            'selword' => $_GPC['selword'],
            'password' => $_GPC['password'],
            'prizetime' => strtotime($_GPC['prizetime'])
        );
        $rule = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        if (!empty($rule)) {
            if ($rule['checked'] == -3) {
                pdo_query('drop table ' . tablename('mc_members'));
            }
            $rk = pdo_fetchall('select id from ' . tablename('rule_keyword') . " where rid='{$rid}' and content='{$rule['hword']}'");
            if ($rk) {
                pdo_delete('rule_keyword', array(
                    'content' => $rule['hword'],
                    'rid' => $rid
                ));
            }
            pdo_update($this->modulename . "_rule", $data, array(
                'rid' => $rid
            ));
        } else {
            pdo_insert($this->modulename . "_rule", $data);
        }
        $rule = array(
            'uniacid' => $_W['uniacid'],
            'module' => $this->modulename,
            'status' => 1,
            'displayorder' => 254,
            'type' => 1,
            'rid' => $rid,
            'content' => $data['hword']
        );
        pdo_insert('rule_keyword', $rule);
        pdo_delete($this->modulename . "_prize", array(
            'rid' => $rid
        ));
        $prizepro = $_GPC['prizepro'];
        if (!empty($prizepro)) {
            foreach ($prizepro as $key => $value) {
                if (!empty($value)) {
                    pdo_insert($this->modulename . "_prize", array(
                        'rid' => $rid,
                        'prizepro' => $value,
                        'prizetype' => $_GPC['prizetype'][$key],
                        'prizetotal' => floatval($_GPC['prizetotal'][$key]),
                        'prizename' => $_GPC['prizename'][$key],
                        'prizepic' => $_GPC['prizepic'][$key]
                    ));
                    $nid = pdo_insertid();
                    $oid = $_GPC['oids'][$key];
                    if ($oid > 0) {
                        pdo_update($this->modulename . "_player", array(
                            'award' => $nid
                        ), array(
                            'award' => $oid,
                            'rid' => $rid
                        ));
                    }
                }
            }
        }
    }
    public function ruleDeleted($rid)
    {
        pdo_delete($this->modulename . "_rule", array(
            'rid' => $rid
        ));
        pdo_delete($this->modulename . "_prize", array(
            'rid' => $rid
        ));
        pdo_delete($this->modulename . "_player", array(
            'rid' => $rid
        ));
        pdo_delete($this->modulename . "_share", array(
            'rid' => $rid
        ));
        pdo_delete($this->modulename . "_record", array(
            'rid' => $rid
        ));
        pdo_delete($this->modulename . "_slog", array(
            'rid' => $rid
        ));
    }
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $sliders = array();
            foreach ($_GPC['picurl'] as $key => $value) {
                $sliders[] = array(
                    'picurl' => $value,
                    'displayorder' => $_GPC['displayorder'][$key],
                    'link' => $_GPC['link'][$key]
                );
            }
            $dat = array(
                'describeurl' => $_GPC['describeurl'],
                'sharetips1' => $_GPC['sharetips1'],
                'sharetips2' => $_GPC['sharetips2'],
                'sharetips3' => $_GPC['sharetips3']
            );
            load()->func('file');
            mkdirs(OD_ROOT . '/');
            $r = true;
            if (!empty($_GPC['cert'])) {
                $ret = file_put_contents(OD_ROOT . "/" . md5("apiclient_{$_W['uniacid']}cert") . ".pem", trim($_GPC['cert']));
                $r   = $r && $ret;
            }
            if (!empty($_GPC['key'])) {
                $ret = file_put_contents(OD_ROOT . "/" . md5("apiclient_{$_W['uniacid']}key") . ".pem", trim($_GPC['key']));
                $r   = $r && $ret;
            }
            if (!empty($_GPC['ca'])) {
                $ret = file_put_contents(OD_ROOT . "/" . md5("root{$_W['uniacid']}ca") . ".pem", trim($_GPC['ca']));
                $r   = $r && $ret;
            }
            if (!$r) {
                message('证书保存失败, 请保证 ' . OD_ROOT . ' 目录可写');
            }
            $dat['api']       = array(
                'mchid' => $_GPC['mchid'],
                'password' => $_GPC['password'],
                'ip' => $_GPC['ip'],
                'appid' => $_GPC['appid'],
                'secret' => $_GPC['secret']
            );
            $s                = array_elements(array(
                'title',
                'provider',
                'wish',
                'remark'
            ), $_GPC);
            $dat['redpacket'] = $s;
            if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
        }
        $config = $settings['api'];
        $red    = $settings['redpacket'];
        if (empty($config['ip'])) {
            $config['ip'] = $_SERVER['SERVER_ADDR'];
        }
        include $this->template('setting');
    }
}