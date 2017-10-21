<?php
defined('IN_IA') or exit('Access Denied');
define('DS', DIRECTORY_SEPARATOR);
class zmcn_fwModule extends WeModule
{
    function __construct()
    {
        include_once dirname(__FILE__) . DS . 'plugin' . DS . '3fdgdrhy.php';
    }
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC, $codeca, $unicredi;
        load()->func('tpl');
        load()->func('communication');
        load()->model('mc');
        $shoplis               = array(
            'ewei_shopping' => '1',
            'ewei_shop' => '2',
            'quickshop' => '3',
            'hawk_surpershop' => '4',
            'weliam_indiana' => '5',
            'superman_mall' => '6',
            'feng_fightgroups' => '7',
            'ewei_shopv2' => '14'
        );
        $settings              = $this->module['config'];
        $settings['iscodeset'] = empty($settings['iscodeset']) ? '0' : $settings['iscodeset'];
        $settings['luck']      = empty($settings['luck']) ? '0' : $settings['luck'];
        $codeset               = array();
        $mk                    = array();
        $mm                    = array();
        $codeo                 = array();
        $set                   = array();
        $set                   = pdo_fetch("SELECT * FROM " . tablename('zmcn_fw_set') . " WHERE uniacid = :uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        if (empty($set['id'])) {
            $insert = array(
                'uniacid' => $_W['uniacid']
            );
            pdo_insert('zmcn_fw_set', $insert);
            $set['id'] = pdo_insertid();
        }
        $codeset = pdo_fetch("SELECT * FROM " . tablename('zmcn_fw_codeset') . " WHERE uniacid = :uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        if (empty($codeset['id'])) {
            $codeset['k']   = 'a:3:{s:1:"k";a:5:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";}s:1:"m";a:5:{i:1;s:2:"FW";i:2;s:1:"7";i:3;s:1:"6";i:4;s:1:"5";i:5;s:2:"20";}s:1:"s";i:0;}';
            $codeset['rid'] = '0';
            $insert         = array(
                'uniacid' => $_W['uniacid'],
                'k' => $codeset['k'],
                'rid' => '0'
            );
            pdo_insert('zmcn_fw_codeset', $insert);
            $codeset['id'] = pdo_insertid();
        }
        $rid = empty($codeset['rid']) ? '0' : $codeset['rid'];
        if (is_serialized($set['luck'])) {
            $luck = iunserializer($set['luck']);
        } else {
            $luck = array();
        }
        if (is_serialized($codeset['k'])) {
            $codeo = iunserializer($codeset['k']);
        } else {
            $codeset['k'] = 'a:3:{s:1:"k";a:5:{i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";}s:1:"m";a:5:{i:1;s:2:"FW";i:2;s:1:"7";i:3;s:1:"6";i:4;s:1:"5";i:5;s:2:"20";}s:1:"s";i:0;}';
            $codeo        = iunserializer($codeset['k']);
        }
        if (checksubmit()) {
            if ($settings['iscodeset'] != '1' && ($_W['role'] == "manager" || $_W['role'] == "founder")) {
                if (strlen($_GPC['m1']) != '2') {
                    $mmm1 = $codeo['m']['1'];
                } else {
                    $mmm1 = $_GPC['m1'];
                }
                $mm    = array(
                    '1' => $mmm1,
                    '2' => $_GPC['m2'],
                    '3' => $_GPC['m3'],
                    '4' => $_GPC['m4'],
                    '5' => $_GPC['m5']
                );
                $mk    = array(
                    '1' => strlen($_GPC['k1']) != '10' ? $codeo['k']['1'] : $_GPC['k1'],
                    '2' => strlen($_GPC['k2']) != '10' ? $codeo['k']['2'] : $_GPC['k2'],
                    '3' => strlen($_GPC['k3']) != '10' ? $codeo['k']['3'] : $_GPC['k3'],
                    '4' => strlen($_GPC['k4']) != '10' ? $codeo['k']['4'] : $_GPC['k4'],
                    '5' => strlen($_GPC['k5']) != '10' ? $codeo['k']['5'] : $_GPC['k5']
                );
                $coden = array(
                    'k' => $mk,
                    'm' => $mm,
                    's' => '0'
                );
                if ($rid == '0') {
                    $rules = array(
                        'uniacid' => $_W['uniacid'],
                        'name' => 'zmcn_fw_' . $_GPC['m1'],
                        'module' => 'zmcn_fw',
                        'displayorder' => '254',
                        'status' => '1'
                    );
                    pdo_insert('rule', $rules);
                    $rid    = pdo_insertid();
                    $insert = array(
                        'm1' => $mmm1,
                        'k' => iserializer($coden),
                        'rid' => $rid
                    );
                } else {
                    $insert = array(
                        'm1' => $mmm1,
                        'k' => iserializer($coden)
                    );
                }
                pdo_update('zmcn_fw_codeset', $insert, array(
                    'uniacid' => $_W['uniacid']
                ));
                if ($_GPC['m1'] != $codeo['k']['1'] && $_GPC['m1'] != "") {
                    pdo_delete('rule_keyword', array(
                        'uniacid' => $_W['uniacid'],
                        'rid' => $rid
                    ));
                    $a = array(
                        'rid' => $rid,
                        'uniacid' => $_W['uniacid'],
                        'module' => 'zmcn_fw',
                        'content' => '微防伪',
                        'type' => '1',
                        'displayorder' => '254',
                        'status' => '1'
                    );
                    pdo_insert('rule_keyword', $a);
                    $a = array(
                        'rid' => $rid,
                        'uniacid' => $_W['uniacid'],
                        'module' => 'zmcn_fw',
                        'content' => '进入微防伪',
                        'type' => '1',
                        'displayorder' => '254',
                        'status' => '1'
                    );
                    pdo_insert('rule_keyword', $a);
                    $a = array(
                        'rid' => $rid,
                        'uniacid' => $_W['uniacid'],
                        'module' => 'zmcn_fw',
                        'content' => '^[W|w][F|f][W|w]',
                        'type' => '3',
                        'displayorder' => '254',
                        'status' => '1'
                    );
                    pdo_insert('rule_keyword', $a);
                    $a = ihttp_get($codeca . '&ke=' . $_GPC['m1']);
                    if (strlen($a['content']) == '17') {
                        $a = array(
                            'rid' => $rid,
                            'uniacid' => $_W['uniacid'],
                            'module' => 'zmcn_fw',
                            'content' => $a['content'],
                            'type' => '3',
                            'displayorder' => '254',
                            'status' => '1'
                        );
                        pdo_insert('rule_keyword', $a);
                    }
                }
            }
            if ($settings['isred'] == '1') {
                load()->func('file');
                $apiclient_cert = $_GPC['red']['apicert'];
                $apiclient_key  = $_GPC['red']['apikey'];
                $rootca         = $_GPC['red']['rootca'];
                file_write("./certs/index.html", "");
                if (strlen($apiclient_cert) > '90') {
                    if (empty($settings['red']['apicert'])) {
                        $settings['red']['apicert'] = file_random_name('./', 'pem');
                    }
                    file_write("./certs/" . $settings['red']['apicert'], $apiclient_cert);
                }
                if (strlen($apiclient_key) > '90') {
                    if (empty($settings['red']['apikey'])) {
                        $settings['red']['apikey'] = file_random_name('./', 'pem');
                    }
                    file_write("./certs/" . $settings['red']['apikey'], $apiclient_key);
                }
                if (strlen($rootca) > '90') {
                    if (empty($settings['red']['rootca'])) {
                        $settings['red']['rootca'] = file_random_name('./', 'pem');
                    }
                    file_write("./certs/" . $settings['red']['rootca'], $rootca);
                }
                $settings['redtype']    = empty($_GPC['redtype']) ? "0" : $_GPC['redtype'];
                $settings['redwishing'] = $_GPC['redwishing'];
                $settings['redremark']  = $_GPC['redremark'];
                $settings['redlink']    = $_GPC['redlink'];
                $settings['rednotx']    = $_GPC['rednotx'];
            }
            if ($_GPC['isluck'] == '1') {
                $luck   = $_GPC['luck'];
                $insert = array(
                    'luck' => iserializer($_GPC['luck'])
                );
                pdo_update('zmcn_fw_set', $insert, array(
                    'uniacid' => $_W['uniacid']
                ));
            }
            if ($_GPC['iscodeset'] == '0') {
                $settings['iscodeset'] = '0';
            } else {
                $settings['iscodeset'] = '1';
            }
            if ($_W['role'] == "founder") {
                $settings['wapcss']       = $_GPC['wapcss'];
                $settings['api']          = $_GPC['api'];
                $settings['isccode']      = empty($_GPC['isccode']) ? '0' : (int) $_GPC['isccode'];
                $settings['accountlevel'] = $_GPC['accountlevel'];
            }
            $settings['upperlimit']  = empty($_GPC['upperlimit']) ? '0' : $_GPC['upperlimit'];
            $settings['ishistory']   = empty($_GPC['ishistory']) ? '0' : (int) $_GPC['ishistory'];
            $settings['isint']       = empty($_GPC['isint']) ? '0' : (int) $_GPC['isint'];
            $settings['isluck']      = empty($_GPC['isluck']) ? '0' : (int) $_GPC['isluck'];
            $settings['isapi']       = empty($_GPC['isapi']) ? '0' : (int) $_GPC['isapi'];
            $settings['islink']      = empty($_GPC['islink']) ? '0' : (int) $_GPC['islink'];
            $settings['xiguang']     = empty($_GPC['xiguang']) ? '0' : (int) $_GPC['xiguang'];
            $settings['isred']       = empty($_GPC['isred']) ? '0' : (int) $_GPC['isred'];
            $settings['ischuanhuo']  = empty($_GPC['ischuanhuo']) ? '0' : (int) $_GPC['ischuanhuo'];
            $settings['tongzi']      = trim($_GPC['tongzi']);
            $settings['apikey']      = trim($_GPC['apikey']);
            $settings['inttype']     = (int) $_GPC['inttype'];
            $settings['istixe']      = (int) $_GPC['istixe'];
            $settings['wxmobid']     = $_GPC['wxmobid'];
            $settings['luckname']    = $_GPC['luckname'];
            $settings['link']        = $_GPC['link'];
            $settings['kuaidi']      = $_GPC['kuaidi'];
            $settings['factory']     = $_GPC['factory'];
            $settings['ftel']        = $_GPC['ftel'];
            $settings['dtel']        = $_GPC['dtel'];
            $settings['link']        = $_GPC['link'];
            $settings['logo']        = $_GPC['logo'];
            $settings['banner']      = $_GPC['banner'];
            $settings['suc_one']     = $_GPC['suc_one'];
            $settings['suc_two']     = $_GPC['suc_two'];
            $settings['suc_three']   = $_GPC['suc_three'];
            $settings['con_msg']     = $_GPC['con_msg'];
            $settings['welcome']     = $_GPC['welcome'];
            $settings['suc_one1']    = $_GPC['suc_one1'];
            $settings['suc_two1']    = $_GPC['suc_two1'];
            $settings['suc_three1']  = $_GPC['suc_three1'];
            $settings['myone']       = $_GPC['myone'];
            $settings['mytwo']       = $_GPC['mytwo'];
            $settings['con_msg1']    = $_GPC['con_msg1'];
            $settings['welcome1']    = $_GPC['welcome1'];
            $settings['welcometxt']  = $_GPC['welcometxt'];
            $settings['welcometxt1'] = $_GPC['welcometxt1'];
            $settings['script1']     = $_GPC['script1'];
            $settings['script']      = $_GPC['script'];
            $settings['defaultkey']  = $_GPC['defaultkey'];
            $settings['logocredit1'] = $_GPC['logocredit1'];
            $settings['logocredit2'] = $_GPC['logocredit2'];
            $settings['logocredit3'] = $_GPC['logocredit3'];
            $settings['logocredit4'] = $_GPC['logocredit4'];
            $settings['logocredit5'] = $_GPC['logocredit5'];
            $settings['logocredit6'] = $_GPC['logocredit6'];
            $settings['logocredit7'] = $_GPC['logocredit7'];
            $settings['logocredit8'] = $_GPC['logocredit8'];
            $settings['logocredit9'] = $_GPC['logocredit9'];
            $settings['logocredit0'] = $_GPC['logocredit0'];
            $settings['biggen']      = $_GPC['biggen'];
            $settings['fwbs']        = $_GPC['fwbs'];
            $settings['template_id'] = trim($_GPC['template_id']);
            $settings['profile']     = $_GPC['profile'];
            $settings['hd']          = $_GPC['hd'];
            $settings['ttx']         = $_GPC['ttx'];
            $this->saveSettings($settings);
            $insert = array(
                'settings' => iserializer($settings)
            );
            pdo_update('zmcn_fw_set', $insert, array(
                'uniacid' => $_W['uniacid']
            ));
            $data1 = array(
                'uniacid' => $_W['uniacid'],
                'type' => '1',
                'summary' => 'setting',
                'uid' => $_W['uid'],
                'addtime' => TIMESTAMP,
                'ip' => $_W['clientip'],
                'remark' => '更新系统设置'
            );
            pdo_insert('zmcn_fw_history', $data1);
            message('保存成功', '?c=' . $_GPC['c'] . '&a=' . $_GPC['a'] . '&do=' . $_GPC['do'] . '&m=' . $_GPC['m'] . '&tab=' . $_GPC['tab'], 'success');
        }
        $dirs     = getSubDirs(MODULE_ROOT . '/template/mobile/');
        $shopnane = pdo_fetchall("SELECT name as a , title as b FROM " . tablename('modules') . " WHERE name IN ('" . implode("','", array_keys($shoplis)) . "')");
        include $this->template('setting');
    }
}
function getSubDirs($dir)
{
    $subdirs = array();
    if (!$dh = opendir($dir))
        return $subdirs;
    $i = '0';
    while ($f = readdir($dh)) {
        if ($f == '.' || $f == '..') {
            continue;
        }
        $path        = $f;
        $subdirs[$i] = $path;
        $i++;
    }
    return $subdirs;
}

?>