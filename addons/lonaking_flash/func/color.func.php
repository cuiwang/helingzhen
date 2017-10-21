<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

function rgb2hex($rgb)
{
    goto CDsSZ;
    rCL2l:
    kLYW6:
    goto qUvje;
    vaTQf:
    $c = $match[$i];
    goto ktwNf;
    uHSLj:
    uQjyn:
    goto V4gYw;
    U9MTL:
    array_push($hexAr, $hex[$r]);
    goto pvIOj;
    Oqx9u:
    $re = array_shift($match);
    goto ZgbtI;
    VaA3F:
    $item = implode('', $ret);
    goto Ju1bT;
    KFHwB:
    $i = 0;
    goto uHSLj;
    qUvje:
    return $hexColor;
    goto I6_g2;
    ZgbtI:
    $hexColor = "\43";
    goto kUlI3;
    pvIOj:
    goto XtJVJ;
    goto uqEqv;
    uqEqv:
    fbpvS:
    goto cYOkw;
    RFoVb:
    $r = $c % 16;
    goto lTs_Y;
    BbW3Q:
    H_uYC:
    goto gibz_;
    kUlI3:
    $hex = array("\x30", "\x31", "\62", "\x33", "\x34", "\65", "\66", "\67", "\70", "\x39", "\101", "\102", "\103", "\x44", "\105", "\x46");
    goto KFHwB;
    gibz_:
    $i++;
    goto SPHSa;
    SPHSa:
    goto uQjyn;
    goto rCL2l;
    Ju1bT:
    $item = str_pad($item, 2, "\60", STR_PAD_LEFT);
    goto fd_83;
    QK3Hg:
    XtJVJ:
    goto XqBux;
    CDsSZ:
    $regexp = "\x2f\136\162\147\142\x5c\50\x28\x5b\x30\x2d\x39\135\173\60\54\63\175\51\x5c\54\x5c\x73\52\x28\x5b\60\55\x39\x5d\173\x30\x2c\63\x7d\51\134\x2c\x5c\163\52\50\133\60\x2d\71\x5d\x7b\60\54\x33\x7d\51\x5c\x29\57";
    goto qSDA2;
    PX8XB:
    $r = null;
    goto vaTQf;
    ktwNf:
    $hexAr = array();
    goto QK3Hg;
    fd_83:
    $hexColor .= $item;
    goto BbW3Q;
    qSDA2:
    $re = preg_match($regexp, $rgb, $match);
    goto Oqx9u;
    XqBux:
    if (!($c > 16)) {
        goto fbpvS;
    }
    goto RFoVb;
    V4gYw:
    if (!($i < 3)) {
        goto kLYW6;
    }
    goto PX8XB;
    k5pHR:
    $ret = array_reverse($hexAr);
    goto VaA3F;
    lTs_Y:
    $c = $c / 16 >> 0;
    goto U9MTL;
    cYOkw:
    array_push($hexAr, $hex[$c]);
    goto k5pHR;
    I6_g2:
}
/**
 * 十六进制 转 RGB
 */
function hex2rgb($hexColor, $opacity = 1, $return = "\x73\164\162")
{
    goto pabN3;
    FNbfb:
    return "\x72\x67\x62\x61\50" . $rgb["\162"] . "\x2c" . $rgb["\x67"] . "\54" . $rgb["\142"] . "\54" . $opacity . "\x29";
    goto ind_k;
    mSxaX:
    WZPPQ:
    goto it39C;
    XjFIg:
    $color = $hexColor;
    goto E24Gp;
    laULY:
    return $rgb;
    goto lKk_b;
    u8jzB:
    goto WZPPQ;
    goto ppRq3;
    sKJ26:
    $b = substr($color, 2, 1) . substr($color, 2, 1);
    goto rPm28;
    ind_k:
    qwXrY:
    goto xa5LS;
    ACjIo:
    $g = substr($color, 1, 1) . substr($color, 1, 1);
    goto sKJ26;
    rPm28:
    $rgb = array("\162" => hexdec($r), "\147" => hexdec($g), "\142" => hexdec($b));
    goto u8jzB;
    E24Gp:
    $r = substr($color, 0, 1) . substr($color, 0, 1);
    goto ACjIo;
    pabN3:
    $color = str_replace("\x23", '', $hexColor);
    goto UlF7w;
    lKk_b:
    goto qwXrY;
    goto SUKZZ;
    it39C:
    if ($return == "\163\x74\x72") {
        goto euBdz;
    }
    goto laULY;
    UlF7w:
    if (strlen($color) > 3) {
        goto agZDt;
    }
    goto XjFIg;
    SUKZZ:
    euBdz:
    goto FNbfb;
    Iw1cg:
    $rgb = array("\162" => hexdec(substr($color, 0, 2)), "\147" => hexdec(substr($color, 2, 2)), "\x62" => hexdec(substr($color, 4, 2)));
    goto mSxaX;
    ppRq3:
    agZDt:
    goto Iw1cg;
    xa5LS:
}
