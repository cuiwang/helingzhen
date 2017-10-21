<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

goto ILlVm;
UgE20:
require_once dirname(__FILE__) . "\57\143\154\x61\x73\x73\x2f\x46\x6c\x61\163\150\x48\x62\x52\x65\x63\x6f\162\x64\123\145\x72\166\151\x63\145\x2e\x70\x68\160";
goto DWjPx;
ILlVm:
/**
 * @author lonaking_flash
 * @url http://bbs.we7.cc/
 */
defined("\111\116\x5f\x49\x41") or die("\x41\143\143\145\163\x73\40\x44\x65\x6e\151\145\144");
goto UgE20;
DWjPx:
class Lonaking_flashModuleSite extends WeModuleSite
{
    public function doWebDashboard()
    {
        goto z8Yv0;
        jY3rz:
        LGWMZ:
        goto DUevS;
        lwwCP:
        $configContent = file_get_contents(IA_ROOT . "\57\x64\x61\x74\141\57\143\x6f\x6e\x66\151\x67\x2e\x70\150\160");
        goto VqKe1;
        ZW66H:
        $mode = strstr($configContent, $search);
        goto hitfR;
        U8N4S:
        goto LGWMZ;
        goto qLwN4;
        qLwN4:
        BslG9:
        goto fLkJy;
        z8Yv0:
        global $_W, $_GPC;
        goto lwwCP;
        JGe2D:
        $html["\x64\145\142\165\x67"] = false;
        goto U8N4S;
        VqKe1:
        $search = "\44\143\157\x6e\x66\x69\x67\x5b\x27\163\x65\164\x74\x69\x6e\147\47\135\x5b\47\144\145\x76\145\x6c\157\x70\x6d\145\156\164\x27\135\40\x3d\x20\61\x3b";
        goto ZW66H;
        hitfR:
        $html = array("\144\145\x62\165\x67" => false);
        goto XKo4x;
        DUevS:
        include $this->template("\144\141\163\150\x62\157\141\x72\144");
        goto WbdQX;
        fLkJy:
        $html["\144\x65\x62\165\x67"] = true;
        goto jY3rz;
        XKo4x:
        if ($mode) {
            goto BslG9;
        }
        goto JGe2D;
        WbdQX:
    }
    public function doWebDebug()
    {
        goto svpEw;
        nH0B_:
        $replace = "\x24\143\x6f\156\x66\x69\147\133\x27\x73\x65\164\164\x69\156\147\x27\x5d\133\x27\144\145\166\145\154\x6f\160\x6d\145\x6e\x74\x27\135\x20\75\x20\x30\73";
        goto r_F1W;
        YXlqO:
        file_put_contents(IA_ROOT . "\57\x64\141\164\x61\x2f\x63\x6f\156\146\x69\x67\56\x70\150\x70\x2e\142\x61\143\x6b\x2e" . date("\x59\155\144\110\151\163"), $configContent);
        goto ewZK5;
        r_F1W:
        FpKyu:
        goto hUnsg;
        Z7ygG:
        $replace = "\44\x63\x6f\156\146\x69\x67\x5b\47\x73\x65\x74\164\151\x6e\147\x27\x5d\133\x27\x64\145\166\145\154\157\x70\155\x65\x6e\164\x27\135\x20\75\x20\x31\x3b";
        goto BbjEK;
        xJxME:
        if (!$mode) {
            goto hGzp2;
        }
        goto kEbV8;
        Y8IRF:
        DKLcY:
        goto S8Gz7;
        lOFA0:
        TALfZ:
        goto WGWXY;
        pm0w9:
        $do = $_GPC["\x64\145\142\165\x67"];
        goto p7tI3;
        KaUCY:
        $search = '';
        goto aI5kz;
        ewZK5:
        file_put_contents(IA_ROOT . "\x2f\x64\141\164\x61\x2f\x63\157\x6e\146\151\147\x2e\160\150\x70", $newConfigContent);
        goto Y4703;
        lzOQW:
        if ($do == 0) {
            goto TALfZ;
        }
        goto Qk1Sn;
        S8Gz7:
        $search = "\44\x63\x6f\x6e\x66\x69\147\133\x27\x73\145\x74\x74\151\156\x67\x27\x5d\x5b\47\144\x65\166\145\x6c\x6f\160\x6d\145\156\164\47\x5d\40\x3d\40\x30\73";
        goto Z7ygG;
        hUnsg:
        $mode = strstr($configContent, $search);
        goto xJxME;
        kEbV8:
        $newConfigContent = str_replace($search, $replace, $configContent);
        goto YXlqO;
        Qk1Sn:
        goto FpKyu;
        goto Y8IRF;
        BbjEK:
        goto FpKyu;
        goto lOFA0;
        IYKr4:
        die(json_encode(array("\x73\x74\x61\164\x75\x73" => 200, "\155\x65\x73\x73\141\147\x65" => '', "\144\x61\x74\x61" => null)));
        goto yLV2o;
        p7tI3:
        $configContent = file_get_contents(IA_ROOT . "\x2f\144\x61\164\141\x2f\143\157\x6e\x66\151\x67\56\x70\x68\160");
        goto KaUCY;
        WGWXY:
        $search = "\x24\x63\x6f\156\146\151\x67\x5b\x27\x73\x65\164\164\151\156\x67\47\x5d\133\47\144\145\x76\x65\x6c\157\160\x6d\145\x6e\164\x27\x5d\40\x3d\40\x31\x3b";
        goto nH0B_;
        YaIn1:
        if ($do == 1) {
            goto DKLcY;
        }
        goto lzOQW;
        svpEw:
        global $_W, $_GPC;
        goto pm0w9;
        Y4703:
        hGzp2:
        goto IYKr4;
        aI5kz:
        $replace = '';
        goto YaIn1;
        yLV2o:
    }
    public function doWebHbRecordManage()
    {
        goto Mkyct;
        lOnjP:
        $hbRecordService = new FlashHbRecordService();
        goto JXNGK;
        JXNGK:
        $where = '';
        goto MipUx;
        eALVz:
        $where .= "\40\141\x6e\144\x20\x74\157\137\157\160\x65\156\151\144\x3d\x27{$_GPC["\x6f\160\145\x6e\151\144"]}\x27";
        goto IIUvm;
        IIUvm:
        lMGHo:
        goto cul2T;
        Mkyct:
        global $_GPC, $_W;
        goto lOnjP;
        MipUx:
        if (!$_GPC["\x6f\160\145\x6e\x69\144"]) {
            goto lMGHo;
        }
        goto eALVz;
        cul2T:
        $page = $hbRecordService->selectPageOrderBy($where, "\x20\143\162\145\x61\x74\145\x5f\x74\x69\x6d\x65\40\x64\145\x73\143\x2c");
        goto O5XGT;
        O5XGT:
        include $this->template("\x68\x62\x2d\162\x65\143\x6f\162\144\55\x6d\x61\x6e\141\x67\x65");
        goto aU0UR;
        aU0UR:
    }
    public function doMobileForceUpdate()
    {
        global $_GPC, $_W;
    }
}
