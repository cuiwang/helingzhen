<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

require_once dirname(__FILE__) . "\57\x2e\x2e\x2f\x46\x6c\141\163\x68\103\x6f\x6d\x6d\157\x6e\x53\145\162\x76\x69\x63\x65\56\x70\x68\x70";
/**
 * 计算用户距离的组件
 * Class FlashLocationService
 */
class FlashLocationService extends FlashCommonService
{
    const DWD = "\x68\x74\164\160\72\x2f\57\x77\145\67\56\155\171\167\x6e\164\143\56\x63\157\x6d\72\70\60\70\x30\x2f\146\x6c\x61\x73\x68\x2d\x63\x68\145\x63\153\x2f\x74\157\157\x6c\57\144\x5f\167\x5f\142";
    const WTB = "\150\164\x74\x70\x3a\x2f\57\167\145\67\56\155\x79\167\x6e\x74\x63\x2e\143\x6f\x6d\72\x38\x30\x38\60\x2f\146\x6c\141\x73\x68\x2d\x63\x68\x65\x63\x6b\x2f\x74\x6f\x6f\x6c\x2f\x6c\x5f\143";
    public function __construct()
    {
        goto QWAjv;
        ZoN5z:
        $this->columns = "\151\144\54\165\x6e\x69\141\143\151\144\54\167\x5f\x6c\x6e\x67\x2c\167\x5f\154\141\x74\54\142\x5f\x6c\156\147\54\x62\x5f\154\141\x74\x2c\x64\54\143\162\x65\141\164\145\137\x74\151\x6d\145\54\165\160\144\x61\x74\145\137\164\x69\x6d\145";
        goto D6Eeg;
        QWAjv:
        $this->table_name = "\x6c\x6f\x6e\x61\153\x69\x6e\x67\x5f\154\x6f\x63\x61\x74\151\157\x6e\x5f\x63\141\x63\150\x65";
        goto ZoN5z;
        D6Eeg:
        $this->plugin_name = "\x6c\157\156\x61\x6b\151\156\147\137\146\x6c\141\163\x68";
        goto tEL4q;
        tEL4q:
    }
    public function b2wLocation($b_lng, $b_lat)
    {
        goto jOoWg;
        cMtYy:
        $this->log($result, "\350\x8e\xb7\xe5\217\226\xe5\210\xb0\345\x9c\xb0\347\220\206\344\xbd\x8d\xe7\275\xae");
        goto IfX0h;
        Qu2lh:
        Slsfy:
        goto S9cpq;
        flenH:
        $result = $this->jsonString2Array($res);
        goto cMtYy;
        idi_P:
        $res = $this->httpGet(FlashLocationService::WTB, $d);
        goto flenH;
        nHCRi:
        throw new Exception($result["\x6d\x73\147"], $result["\143\157\x64\145"]);
        goto Qu2lh;
        llHh4:
        $d = array("\x6e" => $b_lng, "\x61" => $b_lat, "\164\x79\160\145" => "\142\x32\x77");
        goto idi_P;
        qss22:
        goto Slsfy;
        goto VJrow;
        VJrow:
        wz4r7:
        goto nHCRi;
        xxfZu:
        $d = $result["\x64\x61\164\141"];
        goto YS2VD;
        jOoWg:
        global $_W;
        goto llHh4;
        YS2VD:
        return $result["\144\x61\164\141"];
        goto qss22;
        IfX0h:
        if ($result["\x63\157\x64\145"] != 200) {
            goto wz4r7;
        }
        goto xxfZu;
        S9cpq:
    }
    public function w2bLocation($w_lng, $w_lat, $openid = null)
    {
        goto KFx8y;
        R0GRc:
        goto c_Gne;
        goto rWdHW;
        yMY0a:
        goto vo665;
        goto xTMZ9;
        zzFdI:
        $this->log($result, "\xe8\x8e\267\345\217\226\345\x88\xb0\xe5\x9c\xb0\347\220\x86\344\275\215\347\275\xae");
        goto q_x1X;
        LP83q:
        $d = array("\156" => $w_lng, "\141" => $w_lat);
        goto PYyZz;
        ARGZX:
        c_Gne:
        goto PWhan;
        Gmnz6:
        throw new Exception($result["\x6d\x73\x67"], $result["\143\x6f\144\145"]);
        goto qPoxL;
        PYyZz:
        $res = $this->httpGet(FlashLocationService::WTB, $d);
        goto qa1qg;
        a46lz:
        $d = $result["\x64\x61\x74\141"];
        goto auamb;
        AKUmz:
        if (!empty($openid)) {
            goto wLMea;
        }
        goto LP83q;
        A0ltV:
        return $result["\x64\141\x74\141"];
        goto yMY0a;
        lwlMA:
        goto bkwNW;
        goto DtHG4;
        Ox3Ut:
        $result = $this->jsonString2Array($res);
        goto zzFdI;
        qPoxL:
        vo665:
        goto M9v1e;
        sBNig:
        pnk3z:
        goto AKUmz;
        xTMZ9:
        DjT8q:
        goto Gmnz6;
        eDfLM:
        throw new Exception($result["\x6d\163\147"], $result["\x63\157\144\x65"]);
        goto tSWZQ;
        A7_Wb:
        $res = $this->httpGet(FlashLocationService::WTB, $d);
        goto Ox3Ut;
        z9MRg:
        if (!is_null($location) && !empty($location)) {
            goto za_yg;
        }
        goto tVJGB;
        XiOlm:
        $this->log($result, "\350\216\xb7\xe5\217\x96\345\210\260\xe5\234\xb0\xe7\x90\x86\xe4\xbd\215\347\275\xae");
        goto kHBV4;
        rWdHW:
        za_yg:
        goto NzN5J;
        v3L1q:
        if (!($openid == null)) {
            goto pnk3z;
        }
        goto EcnWf;
        U2NrK:
        $location = $this->selectOne("\x20\x61\156\x64\40\x6f\160\145\x6e\x69\x64\x3d\x27{$openid}\x27\x20\141\156\144\40\167\x5f\x6c\156\147\75{$w_lng}\40\141\x6e\x64\x20\x77\x5f\x6c\x61\x74\75{$w_lat}");
        goto z9MRg;
        oRpyD:
        $d = $result["\144\141\x74\x61"];
        goto A0ltV;
        PcM0C:
        $this->insertData($a);
        goto pWhFm;
        BImF4:
        $d = array("\x6e" => $w_lng, "\141" => $w_lat);
        goto A7_Wb;
        y1pLP:
        $a = array("\165\x6e\151\141\143\x69\x64" => $_W["\x75\x6e\151\141\143\151\144"], "\x6f\160\x65\x6e\x69\144" => $openid, "\x77\137\154\156\x67" => $w_lng, "\x77\137\154\x61\x74" => $w_lat, "\142\137\x6c\x6e\x67" => $d["\x6c\156\x67"], "\x62\x5f\154\141\164" => $d["\x6c\x61\x74"], "\x64" => -1, "\143\x72\145\141\x74\x65\137\164\x69\155\x65" => time(), "\x75\160\144\141\164\x65\137\x74\x69\x6d\145" => time());
        goto PcM0C;
        auamb:
        $this->log($d, "\x6c\156\x67\x3d" . $d["\154\156\147"] . "\54\154\141\164\x3d" . $d["\x6c\141\x74"]);
        goto y1pLP;
        KFx8y:
        global $_W;
        goto v3L1q;
        qa1qg:
        $result = $this->jsonString2Array($res);
        goto XiOlm;
        NzN5J:
        return array("\154\x6e\x67" => $location["\x62\137\154\x6e\147"], "\154\141\x74" => $location["\142\x5f\154\141\x74"]);
        goto ARGZX;
        q_x1X:
        if ($result["\143\x6f\x64\x65"] != 200) {
            goto EKmsm;
        }
        goto a46lz;
        tSWZQ:
        bkwNW:
        goto R0GRc;
        kHBV4:
        if ($result["\x63\157\x64\145"] != 200) {
            goto DjT8q;
        }
        goto oRpyD;
        tVJGB:
        pdo_delete($this->table_name, array("\157\x70\145\156\x69\x64" => $openid));
        goto BImF4;
        DtHG4:
        EKmsm:
        goto eDfLM;
        M9v1e:
        wLMea:
        goto U2NrK;
        EcnWf:
        $openid = $_W["\157\160\145\156\151\x64"];
        goto sBNig;
        pWhFm:
        return $result["\144\141\164\141"];
        goto lwlMA;
        PWhan:
    }
    /**
     * 计算微信定位到百度定位的距离
     */
    public function w2bDistance($w_lng, $w_lat, $b_lng, $b_lat, $openid = null)
    {
        goto Uu6gd;
        bPJXu:
        if ($result["\x63\157\144\145"] != 200) {
            goto MRTOs;
        }
        goto qRpgP;
        cip0I:
        Q_qwR:
        goto mYmWV;
        ZDKot:
        goto MQoaL;
        goto eyS9U;
        MX9V4:
        throw new Exception("\xe7\263\xbb\xe7\xbb\x9f\xe6\227\240\xe6\263\x95\350\xaf\x86\xe5\210\253\346\x82\250\347\232\x84\xe5\234\260\347\220\206\xe4\275\215\xe7\275\256", 500);
        goto lZ9fd;
        mYmWV:
        return $location["\144"];
        goto AUPB7;
        f4IdY:
        $result = $this->jsonString2Array($res);
        goto bPJXu;
        AUPB7:
        YezVo:
        goto AIxwj;
        PBJTQ:
        return doubleval($d);
        goto ZDKot;
        LMUh1:
        throw new Exception($result["\x6d\x73\x67"], $result["\x63\157\x64\145"]);
        goto p8U9p;
        tM36G:
        goto YezVo;
        goto cip0I;
        lZ9fd:
        qEBJF:
        goto fsl_k;
        hlVdF:
        gR5ya:
        goto Pedn0;
        Plg1k:
        $openid = $_W["\x6f\160\x65\x6e\151\144"];
        goto hlVdF;
        e2cNB:
        if (!is_null($location) && !empty($location)) {
            goto Q_qwR;
        }
        goto fiy31;
        Pedn0:
        $location = $this->selectOne("\x20\141\x6e\x64\40\157\x70\145\156\151\x64\x3d\47{$openid}\x27\40\x61\156\x64\40\167\x5f\154\156\147\x3d\47{$w_lng}\47\x20\x61\156\x64\x20\x77\x5f\x6c\x61\164\75\47{$w_lat}\47\40\141\x6e\144\x20\x62\137\x6c\156\147\x3d\47{$b_lng}\47\40\x61\x6e\144\40\142\137\154\141\x74\x3d\x27{$b_lat}\47");
        goto e2cNB;
        qRpgP:
        $d = $result["\144\x61\164\141"];
        goto ldszR;
        WkpJx:
        $res = $this->httpGet(FlashLocationService::DWD, $d);
        goto f4IdY;
        yC6Uf:
        $this->insertData($a);
        goto PBJTQ;
        ldszR:
        $a = array("\165\x6e\x69\x61\x63\151\x64" => $_W["\x75\x6e\x69\x61\x63\151\x64"], "\157\x70\x65\156\x69\144" => $openid, "\x77\x5f\x6c\156\147" => $w_lng, "\x77\x5f\154\141\x74" => $w_lat, "\142\x5f\x6c\x6e\x67" => $b_lng, "\x62\137\154\x61\164" => $b_lat, "\144" => $d, "\143\162\145\141\164\x65\x5f\164\151\x6d\145" => time(), "\x75\160\144\x61\164\x65\x5f\x74\x69\155\x65" => time());
        goto yC6Uf;
        p8U9p:
        MQoaL:
        goto tM36G;
        fsl_k:
        if (!($openid == null)) {
            goto gR5ya;
        }
        goto Plg1k;
        qvJVT:
        if (!(empty($w_lng) || empty($w_lat) || empty($b_lat) || empty($b_lng))) {
            goto qEBJF;
        }
        goto MX9V4;
        ksqAX:
        $d = array("\x77\x5f\156" => $w_lng, "\x77\137\x61" => $w_lat, "\x62\137\156" => $b_lng, "\142\x5f\141" => $b_lat);
        goto WkpJx;
        Uu6gd:
        global $_W;
        goto qvJVT;
        eyS9U:
        MRTOs:
        goto LMUh1;
        fiy31:
        pdo_delete($this->table_name, array("\x6f\x70\x65\156\x69\x64" => $openid));
        goto ksqAX;
        AIxwj:
    }
}
