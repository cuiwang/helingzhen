<?php
defined('IN_IA') or exit('Access Denied');
class Xiaof_toupiaoModule extends WeModule
{
    public function fieldsFormDisplay($l11ll11l1111ll111lll1l1lll1l1ll = 0)
    {
        global $_W;
        $l1l1l1ll111l1llll1l1ll1l11l11ll = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_acid') . " WHERE `acid` = :acid", array(
            ":acid" => $_W['uniacid']
        ));
        $l1lll11l11111ll11111l1l1ll11111 = array();
        foreach ($l1l1l1ll111l1llll1l1ll1l11l11ll as $ll111111l1ll11lll1l1l1111lll11l) {
            $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['sid']);
        }
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid OR `id` IN ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')", array(
            ":uniacid" => $_W['uniacid']
        ));
        if ($l11ll11l1111ll111lll1l1lll1l1ll != 0) {
            $llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename('xiaof_toupiao_rule') . " WHERE `rid` = '" . $l11ll11l1111ll111lll1l1lll1l1ll . "' limit 1");
            $lll1lll1lll1l11ll1ll1l1ll1l1lll = $llll1l11l11111l1l111l11l1ll1111['sid'];
            $l111l1ll111l11l11ll1lll1lllll11 = $llll1l11l11111l1l111l11l1ll1111['action'];
        }
        include $this->template("rule");
    }
    public function fieldsFormSubmit($l11ll11l1111ll111lll1l1lll1l1ll)
    {
        global $_W, $_GPC;
        $l11ll1111l1l11llll111l111l111ll = json_decode(htmlspecialchars_decode($_GPC['keywords']), true);
        $llll11lllll111l1l1l1l11111ll111 = $_GPC['action'] == 3 ? $l11ll1111l1l11llll111l111l111ll[0]['content'] : md5($l11ll1111l1l11llll111l111l111ll[0]['content']);
        if ($llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename('xiaof_toupiao_rule') . " WHERE `rid` = '" . $l11ll11l1111ll111lll1l1lll1l1ll . "' limit 1")) {
            pdo_update("xiaof_toupiao_rule", array(
                "sid" => $_GPC['sid'],
                "uniacid" => $_W['uniacid'],
                "action" => $_GPC['action'],
                "keyword" => $llll11lllll111l1l1l1l11111ll111
            ), array(
                "rid" => $l11ll11l1111ll111lll1l1lll1l1ll
            ));
        } else {
            pdo_insert("xiaof_toupiao_rule", array(
                "rid" => $l11ll11l1111ll111lll1l1lll1l1ll,
                "sid" => $_GPC['sid'],
                "uniacid" => $_W['uniacid'],
                "action" => $_GPC['action'],
                "keyword" => $llll11lllll111l1l1l1l11111ll111
            ));
        }
    }
    public function ruleDeleted($l11ll11l1111ll111lll1l1lll1l1ll)
    {
        pdo_query("DELETE FROM " . tablename('xiaof_toupiao_rule') . " WHERE `rid` = '" . $l11ll11l1111ll111lll1l1lll1l1ll . "'");
    }
    public function settingsDisplay($llll1l111llll1l1ll111111ll1ll1l)
    {
        global $_W, $_GPC;
        $l11l1lll1l1l11111l1l1ll1l1l1lll = pdo_fieldexists("mc_mapping_fans", "unionid");
        if (checksubmit()) {
            $ll11l1111l1ll1111lll1lll1l1llll['openweixin']  = $_GPC['openweixin'];
            $ll11l1111l1ll1111lll1lll1l1llll['fuzzysearch'] = $_GPC['fuzzysearch'];
            if ($_W['account']['level'] < 3) {
                $ll11l1111l1ll1111lll1lll1l1llll['openweixin'] = 0;
            }
            $ll11l1111l1ll1111lll1lll1l1llll['smsipnum']       = $_GPC['smsipnum'];
            $ll11l1111l1ll1111lll1lll1l1llll['smsphonenum']    = $_GPC['smsphonenum'];
            $ll11l1111l1ll1111lll1lll1l1llll['dayuak']         = $_GPC['dayuak'];
            $ll11l1111l1ll1111lll1lll1l1llll['dayusk']         = $_GPC['dayusk'];
            $ll11l1111l1ll1111lll1lll1l1llll['dayusign']       = $_GPC['dayusign'];
            $ll11l1111l1ll1111lll1lll1l1llll['dayumoduleid']   = $_GPC['dayumoduleid'];
            $ll11l1111l1ll1111lll1lll1l1llll['dayuname']       = $_GPC['dayuname'];
            $ll11l1111l1ll1111lll1lll1l1llll['imagesaveqiniu'] = $_GPC['imagesaveqiniu'];
            $ll11l1111l1ll1111lll1lll1l1llll['qiniuak']        = $_GPC['qiniuak'];
            $ll11l1111l1ll1111lll1lll1l1llll['qiniusk']        = $_GPC['qiniusk'];
            $ll11l1111l1ll1111lll1lll1l1llll['qiniuzone']      = $_GPC['qiniuzone'];
            $ll11l1111l1ll1111lll1lll1l1llll['qiniudomain']    = $_GPC['qiniudomain'];
            $ll11l1111l1ll1111lll1lll1l1llll['qiniupipeline']  = $_GPC['qiniupipeline'];
            $ll11l1111l1ll1111lll1lll1l1llll['baidumapak']     = $_GPC['baidumapak'];
            if ($ll11l1111l1ll1111lll1lll1l1llll['openweixin'] === 1 && !$l11l1lll1l1l11111l1l1ll1l1l1lll) {
                pdo_query("ALTER TABLE " . tablename('mc_mapping_fans') . " ADD  `unionid` VARCHAR( 50 ) NOT NULL");
            }
            $this->saveSettings($ll11l1111l1ll1111lll1lll1l1llll);
            message('配置参数更新成功！', referer(), 'success');
        }
        include $this->template('setting');
    }
}