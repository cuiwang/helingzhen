<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

/**
 * 本代码出自一位前辈之手，由于作者被封，无法与其取得联系，如有侵权，请联系本人 QQ 1214512299
 */
class CommonUtil
{
    function genAllUrl($toURL, $paras)
    {
        goto UXgsI;
        EpVQP:
        return $allUrl;
        goto shA2G;
        cFjXH:
        $allUrl = $toURL . "\x26" . $paras;
        goto A2kJP;
        UXgsI:
        $allUrl = null;
        goto CQGKt;
        A2kJP:
        goto O9O3T;
        goto rE2vv;
        HIpWR:
        O9O3T:
        goto EpVQP;
        C0EVU:
        if (strripos($toURL, "\77") == '') {
            goto aPQlV;
        }
        goto cFjXH;
        K1Lvf:
        $allUrl = $toURL . "\x3f" . $paras;
        goto HIpWR;
        r8tvE:
        die("\164\x6f\125\122\x4c\40\151\x73\x20\156\x75\154\x6c");
        goto MNoN8;
        rE2vv:
        aPQlV:
        goto K1Lvf;
        MNoN8:
        s18xo:
        goto C0EVU;
        CQGKt:
        if (!(null == $toURL)) {
            goto s18xo;
        }
        goto r8tvE;
        shA2G:
    }
    function create_noncestr($length = 16)
    {
        goto pXk1v;
        BHd1E:
        B3KZ8:
        goto H7cyz;
        Vl_3h:
        if (!($i < $length)) {
            goto B3KZ8;
        }
        goto BsX_8;
        pXk1v:
        $chars = "\x61\142\143\144\x65\x66\x67\x68\x69\152\153\154\155\156\157\x70\161\162\163\x74\x75\166\x77\170\x79\172\101\x42\x43\x44\105\106\x47\x48\111\x4a\x4b\x4c\x4d\x4e\x4f\x50\x51\x52\123\x54\x55\126\127\x58\x59\132\60\61\x32\63\x34\x35\x36\x37\70\x39";
        goto iv6wV;
        yb3o8:
        $i++;
        goto Ag1CW;
        QoLJS:
        $i = 0;
        goto uGtvA;
        Ag1CW:
        goto jeAQY;
        goto BHd1E;
        iv6wV:
        $str = '';
        goto QoLJS;
        uGtvA:
        jeAQY:
        goto Vl_3h;
        N28Lq:
        ZakCu:
        goto yb3o8;
        BsX_8:
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        goto N28Lq;
        H7cyz:
        return $str;
        goto kYuHe;
        kYuHe:
    }
    /**
     * 
     * 
     * @param src
     * @param token
     * @return
     */
    function splitParaStr($src, $token)
    {
        goto Zdhyl;
        aG2W5:
        return $resMap;
        goto K2R61;
        RsJL8:
        p32Vm:
        goto aG2W5;
        YAHzz:
        foreach ($items as $item) {
            goto uIdMK;
            uIdMK:
            $paraAndValue = explode("\75", $item);
            goto mt1ra;
            mt1ra:
            if (!($paraAndValue != '')) {
                goto gP8nW;
            }
            goto yq1fa;
            grIHb:
            gP8nW:
            goto OlP9d;
            yq1fa:
            $resMap[$paraAndValue[0]] = $parameterValue[1];
            goto grIHb;
            OlP9d:
            RBSkF:
            goto cGSVe;
            cGSVe:
        }
        goto RsJL8;
        sHQfL:
        $items = explode($token, $src);
        goto YAHzz;
        Zdhyl:
        $resMap = array();
        goto sHQfL;
        K2R61:
    }
    /**
     * trim 
     * 
     * @param value
     * @return
     */
    static function trimString($value)
    {
        goto vbwe1;
        oE_GD:
        return $ret;
        goto bQD3P;
        Z31Ud:
        XeEqm:
        goto NuOFQ;
        GJe8x:
        if (!(strlen($ret) == 0)) {
            goto XeEqm;
        }
        goto NjEYY;
        NuOFQ:
        WbFcz:
        goto oE_GD;
        xlNYO:
        $ret = $value;
        goto GJe8x;
        NjEYY:
        $ret = null;
        goto Z31Ud;
        TvsLk:
        if (!(null != $value)) {
            goto WbFcz;
        }
        goto xlNYO;
        vbwe1:
        $ret = null;
        goto TvsLk;
        bQD3P:
    }
    function formatQueryParaMap($paraMap, $urlencode)
    {
        goto bmi2E;
        Xw1gd:
        foreach ($paraMap as $k => $v) {
            goto uXGt1;
            vG11q:
            $v = urlencode($v);
            goto OlTFg;
            QOm03:
            if (!$urlencode) {
                goto ZfOYb;
            }
            goto vG11q;
            uXGt1:
            if (!(null != $v && "\156\x75\154\x6c" != $v && "\163\151\x67\x6e" != $k)) {
                goto DbudZ;
            }
            goto QOm03;
            K5eEg:
            $buff .= $k . "\75" . $v . "\46";
            goto XOIJP;
            RzrPY:
            nRwx2:
            goto hh2HH;
            OlTFg:
            ZfOYb:
            goto K5eEg;
            XOIJP:
            DbudZ:
            goto RzrPY;
            hh2HH:
        }
        goto eiKPE;
        fJTS3:
        ksort($paraMap);
        goto Xw1gd;
        maSOo:
        if (!(strlen($buff) > 0)) {
            goto lSvKs;
        }
        goto YekQC;
        Y1Bfd:
        $reqPar;
        goto maSOo;
        YekQC:
        $reqPar = substr($buff, 0, strlen($buff) - 1);
        goto zGmX4;
        eiKPE:
        Hx2EV:
        goto Y1Bfd;
        Dpj_3:
        return $reqPar;
        goto MQRaD;
        bmi2E:
        $buff = '';
        goto fJTS3;
        zGmX4:
        lSvKs:
        goto Dpj_3;
        MQRaD:
    }
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        goto fmn6N;
        IeTFD:
        $reqPar;
        goto rgRd_;
        fmn6N:
        $buff = '';
        goto JSoio;
        yUvRt:
        Esv8e:
        goto JdUHR;
        CvEm6:
        UxulO:
        goto IeTFD;
        JSoio:
        ksort($paraMap);
        goto BHQ3w;
        rgRd_:
        if (!(strlen($buff) > 0)) {
            goto Esv8e;
        }
        goto wDg12;
        wDg12:
        $reqPar = substr($buff, 0, strlen($buff) - 1);
        goto yUvRt;
        JdUHR:
        return $reqPar;
        goto LJdxX;
        BHQ3w:
        foreach ($paraMap as $k => $v) {
            goto nvZji;
            GBDnq:
            SLkE4:
            goto vPPmC;
            ak10S:
            nGQxb:
            goto E6v_H;
            uvuOH:
            $v = urlencode($v);
            goto GBDnq;
            vPPmC:
            $buff .= strtolower($k) . "\75" . $v . "\x26";
            goto ak10S;
            nvZji:
            if (!$urlencode) {
                goto SLkE4;
            }
            goto uvuOH;
            E6v_H:
        }
        goto CvEm6;
        LJdxX:
    }
    static function arrayToXml($arr)
    {
        goto p31kZ;
        qF5_Z:
        return $xml;
        goto rUPQk;
        BMHR7:
        foreach ($arr as $key => $val) {
            goto AdbHO;
            hp7ep:
            F33Yb:
            goto gC9qA;
            LW3cI:
            goto F33Yb;
            goto X79Q1;
            X79Q1:
            RHQo8:
            goto YXC3k;
            AdbHO:
            if (is_numeric($val)) {
                goto RHQo8;
            }
            goto tGx1i;
            tGx1i:
            $xml .= "\x3c" . $key . "\x3e\74\41\133\x43\104\x41\124\101\133" . $val . "\135\135\76\74\x2f" . $key . "\76";
            goto LW3cI;
            gC9qA:
            dG3p4:
            goto qqQUG;
            YXC3k:
            $xml .= "\x3c" . $key . "\76" . $val . "\74\57" . $key . "\x3e";
            goto hp7ep;
            qqQUG:
        }
        goto QThnY;
        teaMa:
        $xml .= "\x3c\x2f\170\x6d\154\76";
        goto qF5_Z;
        QThnY:
        dytZp:
        goto teaMa;
        p31kZ:
        $xml = "\74\x78\x6d\154\76";
        goto BMHR7;
        rUPQk:
    }
}
?>
