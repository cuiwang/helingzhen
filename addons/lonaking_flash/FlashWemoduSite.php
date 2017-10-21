<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 16/1/23
 * Time: 下午7:06
 */
class FlashWemoduSite extends WeModuleSite
{
    protected function pay($params = array())
    {
        goto Nq_Q6;
        tFu3D:
        message("\xe8\277\x99\344\270\xaa\350\256\xa2\345\x8d\225\345\xb7\262\347\273\x8f\xe6\x94\xaf\344\273\230\346\210\x90\xe5\x8a\x9f\54\40\xe4\xb8\x8d\xe9\x9c\x80\350\246\x81\351\x87\x8d\xe5\xa4\215\xe6\224\xaf\xe4\xbb\x98\56");
        goto JrGHF;
        PSvC_:
        include $this->template("\x63\x6f\x6d\x6d\157\156\x2f\160\141\x79\143\x65\x6e\x74\145\x72");
        goto zciAY;
        bJ3qV:
        $credtis = mc_credit_fetch($_W["\x6d\x65\x6d\x62\145\x72"]["\x75\x69\x64"]);
        goto U3Ulj;
        I2OsL:
        dHUBT:
        goto IYfBU;
        iFtgo:
        if ($this->inMobile) {
            goto dmiV6;
        }
        goto D_3oo;
        jAHKq:
        checkauth();
        goto rL1ql;
        bU_03:
        YoKks:
        goto QFiTa;
        v76wc:
        $pars["\72\155\157\x64\x75\154\145"] = $params["\155\157\x64\165\x6c\145"];
        goto V0aO_;
        YS21F:
        die($site->{$method}($pars));
        goto cVBiY;
        bDoaW:
        if (!empty($_W["\x6d\145\155\x62\145\162"]["\x75\x69\144"])) {
            goto UiRDs;
        }
        goto jAHKq;
        zK7y1:
        $pars["\146\162\x6f\155"] = "\162\145\164\165\162\156";
        goto cO47c;
        DHmq_:
        if (!(!empty($log) && $log["\x73\164\141\164\165\x73"] == "\x31")) {
            goto L8uz7;
        }
        goto tFu3D;
        Nq_Q6:
        global $_W;
        goto iFtgo;
        mtbv4:
        $pars["\164\171\x70\x65"] = "\141\x6c\151\x70\141\x79";
        goto sIXIG;
        JrGHF:
        L8uz7:
        goto zU2Hx;
        zU2Hx:
        $setting = uni_setting($_W["\x75\156\151\x61\x63\151\x64"], array("\x70\x61\x79\155\x65\156\x74", "\143\x72\145\x64\x69\x74\142\145\x68\x61\x76\151\x6f\x72\x73"));
        goto WOYnO;
        lqXxm:
        if (!method_exists($site, $method)) {
            goto tlX9t;
        }
        goto YS21F;
        sIXIG:
        $pars["\164\x69\x64"] = $params["\164\x69\x64"];
        goto zGQGN;
        TzpRc:
        if (empty($pay["\x63\162\x65\x64\x69\164"]["\163\x77\151\164\143\x68"])) {
            goto e00H_;
        }
        goto bJ3qV;
        RKQu_:
        if (!($params["\146\145\145"] <= 0)) {
            goto YoKks;
        }
        goto zK7y1;
        uDM2V:
        $pars["\x3a\165\156\x69\x61\143\151\x64"] = $_W["\x75\x6e\151\141\143\x69\x64"];
        goto v76wc;
        FRepH:
        $params["\x6d\x6f\144\x75\x6c\145"] = $this->module["\156\x61\155\145"];
        goto Dzw9K;
        zGQGN:
        $site = WeUtility::createModuleSite($pars["\x3a\x6d\x6f\144\x75\x6c\145"]);
        goto I1dtY;
        kKM1h:
        $log = pdo_fetch($sql, $pars);
        goto DHmq_;
        IYfBU:
        $pay = $setting["\160\x61\171\155\x65\156\164"];
        goto TzpRc;
        cVBiY:
        tlX9t:
        goto bU_03;
        D_3oo:
        message("\xe6\x94\xaf\xe4\273\x98\xe5\212\237\350\x83\xbd\345\217\xaa\350\203\xbd\345\x9c\xa8\xe6\211\213\xe6\234\272\344\270\x8a\xe4\xbd\xbf\xe7\224\xa8");
        goto nvNGy;
        nvNGy:
        dmiV6:
        goto bDoaW;
        QFiTa:
        $sql = "\123\x45\114\105\103\x54\40\52\40\x46\122\x4f\x4d\40" . tablename("\x63\x6f\162\145\x5f\x70\141\171\x6c\157\x67") . "\40\127\110\x45\122\105\40\140\165\156\151\x61\143\x69\x64\140\75\72\x75\156\151\x61\143\151\x64\x20\101\116\104\x20\140\155\157\x64\165\x6c\x65\140\x3d\x3a\155\157\x64\x75\x6c\x65\x20\x41\x4e\104\40\140\164\x69\144\x60\75\72\x74\151\x64";
        goto kKM1h;
        wT3U4:
        message("\346\262\xa1\346\x9c\211\346\234\x89\346\x95\210\347\x9a\204\346\x94\257\xe4\xbb\x98\346\226\xb9\xe5\274\x8f\x2c\x20\350\257\xb7\xe8\x81\x94\347\xb3\xbb\347\xbd\221\347\xab\x99\xe7\256\xa1\xe7\220\x86\345\x91\230\56");
        goto I2OsL;
        rL1ql:
        UiRDs:
        goto FRepH;
        V0aO_:
        $pars["\72\x74\151\x64"] = $params["\x74\151\144"];
        goto RKQu_;
        cO47c:
        $pars["\x72\145\x73\165\154\164"] = "\163\x75\x63\x63\x65\163\163";
        goto mtbv4;
        Dzw9K:
        $pars = array();
        goto uDM2V;
        U3Ulj:
        e00H_:
        goto PSvC_;
        WOYnO:
        if (is_array($setting["\x70\141\x79\x6d\x65\156\164"])) {
            goto dHUBT;
        }
        goto wT3U4;
        I1dtY:
        $method = "\160\x61\x79\x52\145\163\165\154\x74";
        goto lqXxm;
        zciAY:
    }
    public function payResult($ret)
    {
        goto ENWpt;
        ENWpt:
        global $_W;
        goto sj5n8;
        lnWth:
        jnR07:
        goto C8tmf;
        sj5n8:
        if (!($ret["\x66\162\157\155"] == "\162\x65\164\165\162\x6e")) {
            goto jnR07;
        }
        goto pUqcc;
        ABePo:
        goto Fs__r;
        goto pyykK;
        eW61x:
        message("\xe5\xb7\xb2\347\273\217\346\210\x90\345\212\237\346\x94\xaf\xe4\273\230", url("\x6d\x6f\142\151\154\145\57\x63\x68\x61\156\156\145\154", array("\x6e\141\x6d\x65" => "\x69\x6e\144\x65\x78", "\167\145\x69\144" => $_W["\167\x65\151\144"])));
        goto yZckc;
        pUqcc:
        if ($ret["\x74\x79\x70\145"] == "\x63\x72\145\144\x69\164\x32") {
            goto pXaYi;
        }
        goto Jyj4e;
        pyykK:
        pXaYi:
        goto eW61x;
        Jyj4e:
        message("\345\267\262\xe7\xbb\x8f\346\210\220\xe5\212\237\346\x94\257\xe4\273\230", "\56\x2e\x2f\x2e\56\x2f" . url("\155\157\142\151\x6c\145\57\143\150\141\x6e\x6e\x65\x6c", array("\x6e\141\155\x65" => "\x69\x6e\x64\x65\170", "\x77\x65\x69\144" => $_W["\x77\145\x69\x64"])));
        goto ABePo;
        yZckc:
        Fs__r:
        goto lnWth;
        C8tmf:
    }
}
