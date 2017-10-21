<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

goto TkXdB;
UVFal:
function array2map($arr, $key = "\x69\x64")
{
    goto soWmL;
    soWmL:
    $result = array();
    goto Zi9Ak;
    lLqdv:
    return $result;
    goto TmQZ7;
    Zi9Ak:
    foreach ($arr as $data) {
        $result[$data[$key]] = $data;
        IB_wo:
    }
    goto czZ3X;
    czZ3X:
    bLnnw:
    goto lLqdv;
    TmQZ7:
}
goto yT3OQ;
TkXdB:
/**
 * 公用的方法
 */
function array2columnarray($arr, $columns_name, $kill_null = false, $kill_repeat = false)
{
    goto Fam1Q;
    RW7d_:
    $result = $newResult;
    goto dY04z;
    Fam1Q:
    $result = array();
    goto rgTmM;
    rgTmM:
    foreach ($arr as $a) {
        goto goXKN;
        Y9XLr:
        F5pti:
        goto CCU_X;
        CUYHn:
        $result[] = $a[$columns_name];
        goto Y9XLr;
        goXKN:
        $isNull = empty($a[$columns_name]) || is_null($a[$columns_name]);
        goto WUeRa;
        WUeRa:
        if (!($kill_null && $isNull == false || !$kill_null)) {
            goto F5pti;
        }
        goto CUYHn;
        CCU_X:
        U8mYw:
        goto j5frE;
        j5frE:
    }
    goto RkDDV;
    dY04z:
    I2sGQ:
    goto rPgbl;
    AfpCx:
    brMrJ:
    goto RW7d_;
    nGSkS:
    $newResult = array();
    goto oVh07;
    oVh07:
    foreach ($result as $item) {
        goto pwrLE;
        c_Mm4:
        $newResult[] = $item;
        goto sTnEH;
        sTnEH:
        XwNYG:
        goto Xt3fx;
        Xt3fx:
        q3dgK:
        goto Oj3W2;
        pwrLE:
        if (in_array($item, $newResult)) {
            goto XwNYG;
        }
        goto c_Mm4;
        Oj3W2:
    }
    goto AfpCx;
    rPgbl:
    return $result;
    goto gEPS2;
    npSdC:
    if (!$kill_repeat) {
        goto I2sGQ;
    }
    goto nGSkS;
    RkDDV:
    k0zpd:
    goto npSdC;
    gEPS2:
}
goto Fw3EA;
rFsTG:
function looptomedia($list, $name = null)
{
    goto Smdv7;
    HGKoa:
    FFpKq:
    goto w2kbA;
    HZXNx:
    return array();
    goto xFp54;
    ZkSvb:
    foreach ($list as $data) {
        goto NJTKD;
        NJTKD:
        foreach ($name as $n) {
            $data[$n] = tomedia($data[$n]);
            UqXzv:
        }
        goto XKqx1;
        XKqx1:
        doTn0:
        goto IC1Sq;
        IC1Sq:
        $result[] = $data;
        goto x3hIz;
        x3hIz:
        GUD15:
        goto WHFxg;
        WHFxg:
    }
    goto HGKoa;
    kYb8G:
    jFSp4:
    goto cYb46;
    ldTJN:
    if (!empty($list)) {
        goto XNR9q;
    }
    goto HZXNx;
    qRRSy:
    if (!(is_string($name) && is_array($list))) {
        goto jFSp4;
    }
    goto u2x7L;
    cYb46:
    if (!(is_array($name) && is_array($list))) {
        goto sM8hz;
    }
    goto ZkSvb;
    u2x7L:
    foreach ($list as $data) {
        goto l8K22;
        l8K22:
        $data[$name] = tomedia($data[$name]);
        goto yBJHm;
        AZ16X:
        fB_4c:
        goto quEoF;
        yBJHm:
        $result[] = $data;
        goto AZ16X;
        quEoF:
    }
    goto IeDi7;
    IeDi7:
    jGktN:
    goto kYb8G;
    xFp54:
    XNR9q:
    goto qRRSy;
    AOTQs:
    foreach ($list as $data) {
        $result[] = tomedia($data);
        BD0SB:
    }
    goto hVpxf;
    woyC5:
    if (!(is_null($name) && is_array($list))) {
        goto IK1yu;
    }
    goto AOTQs;
    hVpxf:
    sd60m:
    goto ZnUD1;
    w2kbA:
    sM8hz:
    goto woyC5;
    ZnUD1:
    IK1yu:
    goto El0Rz;
    Smdv7:
    $result = array();
    goto ldTJN;
    El0Rz:
    return $result;
    goto LFMPf;
    LFMPf:
}
goto NgkAs;
e6f2N:
function get_param_from_url($url, $param)
{
    goto RLsKv;
    lfS1E:
    $parameter = explode("\46", end(explode("\77", $url)));
    goto VX0TI;
    AY0G2:
    return $data[$param];
    goto mM_1l;
    VX0TI:
    foreach ($parameter as $val) {
        goto IsPIh;
        UgFvQ:
        $data[$tmp[0]] = $tmp[1];
        goto xUiYh;
        IsPIh:
        $tmp = explode("\75", $val);
        goto UgFvQ;
        xUiYh:
        hG31r:
        goto hekZU;
        hekZU:
    }
    goto vT1df;
    RLsKv:
    $data = array();
    goto lfS1E;
    vT1df:
    SFtS6:
    goto AY0G2;
    mM_1l:
}
goto rFsTG;
Fw3EA:
function array2maps($arr, $key = "\151\x64")
{
    goto sKqwS;
    JwSrg:
    return $result;
    goto hjdWv;
    TjIYe:
    nQPom:
    goto JwSrg;
    sKqwS:
    $result = array();
    goto Dhxmz;
    Dhxmz:
    foreach ($arr as $data) {
        $result[$data[$key]][] = $data;
        qAQF2:
    }
    goto TjIYe;
    hjdWv:
}
goto UVFal;
NgkAs:
function del_file_or_dir($dirName)
{
    goto Rzj01;
    hW1xy:
    if (is_dir("{$dirName}\x2f{$item}")) {
        goto gTJW3;
    }
    goto F5278;
    fmv2B:
    e4xoP:
    goto Rqwvu;
    f9yko:
    Gmkcg:
    goto nEwWL;
    CvRyb:
    delDirAndFile("{$dirName}\57{$item}");
    goto SaAf6;
    rYKBR:
    goto yth_a;
    goto gwu2I;
    VhcZW:
    WT3Ha:
    goto XFbJh;
    YQpi2:
    echo "\346\210\x90\345\x8a\237\345\210\xa0\xe9\231\xa4\346\226\x87\xe4\273\266\xef\274\x9a\x20{$dirName}\x2f{$item}\x3c\142\x72\40\x2f\x3e\156";
    goto ytDYK;
    XFbJh:
    tlCJ2:
    goto VxbFp;
    nEwWL:
    if (!(false !== ($item = readdir($handle)))) {
        goto e4xoP;
    }
    goto BBVis;
    aqFEI:
    return false;
    goto F2v52;
    ytDYK:
    lOfUJ:
    goto rYKBR;
    gwu2I:
    gTJW3:
    goto CvRyb;
    Rzj01:
    if (!($handle = opendir($dirName))) {
        goto tlCJ2;
    }
    goto f9yko;
    BBVis:
    if (!($item != "\56" && $item != "\x2e\56")) {
        goto MgJ2w;
    }
    goto hW1xy;
    jNTpH:
    if (rmdir($dirName)) {
        goto OmOoB;
    }
    goto aqFEI;
    nvbIv:
    goto Gmkcg;
    goto fmv2B;
    F2v52:
    goto WT3Ha;
    goto zkBh7;
    SaAf6:
    yth_a:
    goto Ohfcr;
    Rqwvu:
    closedir($handle);
    goto jNTpH;
    Ohfcr:
    MgJ2w:
    goto nvbIv;
    zkBh7:
    OmOoB:
    goto nAEQi;
    nAEQi:
    return true;
    goto VhcZW;
    F5278:
    if (!unlink("{$dirName}\57{$item}")) {
        goto lOfUJ;
    }
    goto YQpi2;
    VxbFp:
}
goto YbDxQ;
yglYh:
function uuid()
{
    goto uvnUY;
    jA0GP:
    Tzn_4:
    goto Qrtnh;
    H2Ml1:
    goto Gmq37;
    goto jA0GP;
    msPXD:
    $uuid = '' . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
    goto O8A18;
    jzNGL:
    $hyphen = chr(45);
    goto msPXD;
    O0pxy:
    $charid = strtoupper(md5(uniqid(rand(), true)));
    goto jzNGL;
    uvnUY:
    if (function_exists("\x63\x6f\155\x5f\x63\x72\145\141\164\145\137\x67\x75\x69\x64")) {
        goto Tzn_4;
    }
    goto Ka7uc;
    O8A18:
    return $uuid;
    goto H2Ml1;
    Ka7uc:
    mt_srand((double) microtime() * 10000);
    goto O0pxy;
    Qrtnh:
    return com_create_guid();
    goto GwutS;
    GwutS:
    Gmq37:
    goto AKHwq;
    AKHwq:
}
goto HNy8Q;
yT3OQ:
function distance_text($list, $name = "\144\x69\163\164\x61\156\143\x65", $time = false)
{
    goto nddve;
    okenN:
    l3k6L:
    goto K6mt0;
    ZbnFH:
    $v_drive = 1000;
    goto Rx5Qe;
    n61AU:
    if (!is_array($name)) {
        goto KVxFl;
    }
    goto e1ljv;
    Mkgbi:
    return $result;
    goto juG_c;
    Rx5Qe:
    $result = array();
    goto mFUPp;
    DQwUB:
    foreach ($list as $data) {
        goto gNOWE;
        z8fkH:
        i2EBM:
        goto Vh70y;
        VGlZA:
        $data["\x74\151\x6d\145\x5f\x74\145\170\x74"] = $time_text;
        goto SB2aI;
        RdsKn:
        $tmp = $distance / $v_bu;
        goto YopJe;
        ibkCc:
        $tmp = floatval($distance / $v_drive);
        goto WFlVs;
        BV8vX:
        $result[] = $data;
        goto sj28I;
        a8hVl:
        tp3wY:
        goto WsuoP;
        p3ea5:
        aOf9G:
        goto VGlZA;
        RTRon:
        $data["\144\151\163\164\x61\x6e\143\x65\x5f\144\145\163\143"] = $desc;
        goto BV8vX;
        YopJe:
        $time_text = intval($tmp) + 1 . "\345\210\x86";
        goto p3ea5;
        RsGdJ:
        goto K8tmE;
        goto z8fkH;
        C6l6Q:
        $text = intval($data[$name]) . "\xe7\xb1\xb3";
        goto b0Wek;
        Tm0Ew:
        $text = round($data[$name] / 1000, 2) . "\xe5\215\203\347\261\263";
        goto TVf_H;
        O59DA:
        $time_text = '';
        goto CaIwl;
        V2E27:
        $desc = "\344\271\x98\xe8\xbd\xa6";
        goto ibkCc;
        Vh70y:
        $time_text = intval($tmp) + 1 . "\xe5\210\x86";
        goto t0mO_;
        dxjVr:
        $data["\144\151\163\x74\x61\156\143\x65\137\x74\145\x78\x74"] = $text;
        goto N8M67;
        gNOWE:
        $text = '';
        goto Ts6l3;
        l14ad:
        i4MG5:
        goto Tm0Ew;
        b0Wek:
        goto PU5Jz;
        goto l14ad;
        sj28I:
        GA3OY:
        goto Mg9aT;
        CaIwl:
        if ($distance <= 2000) {
            goto tp3wY;
        }
        goto V2E27;
        t0mO_:
        K8tmE:
        goto p3e6T;
        D3gkj:
        if ($distance >= 1000) {
            goto i4MG5;
        }
        goto C6l6Q;
        p3e6T:
        goto aOf9G;
        goto a8hVl;
        N8M67:
        if (!($time == true)) {
            goto YYzhY;
        }
        goto O59DA;
        WsuoP:
        $desc = "\xe6\xad\245\xe8\241\214";
        goto RdsKn;
        SB2aI:
        YYzhY:
        goto RTRon;
        akxqt:
        $time_text = round($tmp / 60, 1) . "\xe5\260\x8f\xe6\227\266";
        goto RsGdJ;
        TVf_H:
        PU5Jz:
        goto dxjVr;
        WFlVs:
        if ($tmp < 60) {
            goto i2EBM;
        }
        goto akxqt;
        Ts6l3:
        $desc = '';
        goto Sn2S6;
        Sn2S6:
        $distance = intval($data[$name]);
        goto D3gkj;
        Mg9aT:
    }
    goto xyGgY;
    xyGgY:
    XwuX0:
    goto H6DhM;
    nddve:
    $v_bu = 100;
    goto ZbnFH;
    H6DhM:
    I36Kx:
    goto n61AU;
    K6mt0:
    KVxFl:
    goto Mkgbi;
    e1ljv:
    foreach ($list as $data) {
        goto gUz1J;
        umUQy:
        djq0P:
        goto m3whG;
        mLSpw:
        $result[] = $data;
        goto umUQy;
        gUz1J:
        foreach ($name as $n) {
            goto QZi2G;
            KjEmO:
            EBZmY:
            goto crlsB;
            k7Y21:
            Lv2wN:
            goto g6Wd1;
            PTkhy:
            if (!$time) {
                goto bsB29;
            }
            goto C1TD9;
            RBWlH:
            uM3b4:
            goto UmKIV;
            Ljd5b:
            $time_text = round($tmp / 60, 1) . "\xe5\260\217\346\x97\xb6";
            goto Qg6zC;
            QZi2G:
            $text = '';
            goto CrxK9;
            LZYea:
            goto KPukh;
            goto RBWlH;
            uVDJy:
            goto b7lNN;
            goto KjEmO;
            tnfNE:
            b7lNN:
            goto LZYea;
            crlsB:
            $time_text = intval($tmp) . "\xe5\210\x86";
            goto tnfNE;
            C1TD9:
            $time_text = '';
            goto siQIc;
            CrxK9:
            if (intval($data[$n]) >= 1000) {
                goto Lv2wN;
            }
            goto RGIyA;
            JguD6:
            KPukh:
            goto lSrYs;
            lSrYs:
            $data["\x74\151\x6d\145\137\x74\145\170\x74"] = $time_text;
            goto aVJ3Z;
            sS05J:
            $time_text = round($tmp / 60, 1) . "\xe5\260\217\346\x97\xb6";
            goto uVDJy;
            i0Laq:
            goto uvrBG;
            goto k7Y21;
            dm3Tg:
            uvrBG:
            goto PTkhy;
            XFXSM:
            ba2jB:
            goto F8DOj;
            I3ZLQ:
            $data["\144\x69\x73\164\141\x6e\x63\145\x5f\x74\145\x78\164"] = $text;
            goto XFXSM;
            aVJ3Z:
            bsB29:
            goto I3ZLQ;
            g6Wd1:
            $text = round($data[$n] / 1000, 2) . "\xe5\x8d\203\xe7\261\263";
            goto dm3Tg;
            UmKIV:
            $tmp = $data[$n] / 2;
            goto Ysisb;
            cmMw2:
            if ($tmp < 60) {
                goto EBZmY;
            }
            goto sS05J;
            v0HYM:
            $tmp = $data[$n] / 9;
            goto cmMw2;
            Ysisb:
            if ($tmp < 60) {
                goto r2TgP;
            }
            goto Ljd5b;
            uR2_7:
            r2TgP:
            goto au6EC;
            siQIc:
            if ($data[$n] >= 1500) {
                goto uM3b4;
            }
            goto v0HYM;
            au6EC:
            $time_text = intval($tmp) . "\xe5\210\x86";
            goto OC5wI;
            OC5wI:
            dMloQ:
            goto JguD6;
            Qg6zC:
            goto dMloQ;
            goto uR2_7;
            RGIyA:
            $text = intval($data[$n]) . "\xe7\261\xb3";
            goto i0Laq;
            F8DOj:
        }
        goto Hqq8o;
        Hqq8o:
        nOtQ8:
        goto mLSpw;
        m3whG:
    }
    goto okenN;
    mFUPp:
    if (!is_string($name)) {
        goto I36Kx;
    }
    goto DQwUB;
    juG_c:
}
goto yglYh;
HNy8Q:
function distance($lat1, $lng1, $lat2, $lng2)
{
    goto NCWIz;
    NCWIz:
    $earthRadius = 6367000;
    goto waxod;
    ZkOAW:
    $lng2 = $lng2 * pi() / 180;
    goto TmI53;
    TmI53:
    $calcLongitude = $lng2 - $lng1;
    goto nlHuy;
    dz_ys:
    $lng1 = $lng1 * pi() / 180;
    goto vbTcD;
    MtM2e:
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    goto vXQHJ;
    vXQHJ:
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    goto wLrr6;
    wLrr6:
    $calculatedDistance = $earthRadius * $stepTwo;
    goto p3Hqh;
    waxod:
    $lat1 = $lat1 * pi() / 180;
    goto dz_ys;
    vbTcD:
    $lat2 = $lat2 * pi() / 180;
    goto ZkOAW;
    p3Hqh:
    return round($calculatedDistance);
    goto YDzZv;
    nlHuy:
    $calcLatitude = $lat2 - $lat1;
    goto MtM2e;
    YDzZv:
}
goto e6f2N;
YbDxQ:
function is_write_able($file)
{
    goto QGVyG;
    Jmojv:
    aAAmv:
    goto uQmkx;
    Wz0AB:
    t5bla:
    goto j95nU;
    uQmkx:
    @fclose($fp);
    goto AUF5r;
    G2e7W:
    @unlink("{$dir}\57\x74\x65\163\164\55{$num}\56\x74\x78\x74");
    goto fBpow;
    j95nU:
    $dir = $file;
    goto s1tPJ;
    Eqmas:
    if ($fp = @fopen($file, "\141\53")) {
        goto aAAmv;
    }
    goto Zq5D7;
    fBpow:
    $writeable = 1;
    goto LprF4;
    t4PIv:
    goto IJ22E;
    goto Jmojv;
    AUF5r:
    $writeable = 1;
    goto zLONs;
    QGVyG:
    if (is_dir($file)) {
        goto t5bla;
    }
    goto Eqmas;
    LprF4:
    SxEuv:
    goto H0BDi;
    zLONs:
    IJ22E:
    goto PFnaF;
    H0BDi:
    S9Yzz:
    goto Dvbv0;
    udkUl:
    $writeable = 0;
    goto NRLL1;
    TWW0h:
    @fclose($fp);
    goto G2e7W;
    NRLL1:
    goto SxEuv;
    goto Ua2oy;
    PFnaF:
    goto S9Yzz;
    goto Wz0AB;
    Ua2oy:
    lw58r:
    goto TWW0h;
    Dvbv0:
    return $writeable;
    goto kZq2V;
    IDQ3i:
    if ($fp = @fopen("{$dir}\x2f\x74\x65\163\x74\x2d{$num}\56\x74\x78\164", "\x77")) {
        goto lw58r;
    }
    goto udkUl;
    Zq5D7:
    $writeable = 0;
    goto t4PIv;
    s1tPJ:
    $num = rand(10000, 99999);
    goto IDQ3i;
    kZq2V:
}
