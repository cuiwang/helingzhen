<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

require_once dirname(__FILE__) . "\57\56\56\57\x46\154\x61\163\150\103\x6f\155\x6d\157\x6e\x53\x65\162\166\x69\x63\x65\x2e\160\x68\160";
class FlashQrcodeService extends FlashCommonService
{
    const TMP_QRCODE = 1;
    const FOREVER_QRCODE = 2;
    public function __construct()
    {
        goto KaLVH;
        BKzIe:
        $this->columns = "\151\x64\x2c\165\156\x69\x61\143\x69\x64\x2c\141\x63\x69\144\x2c\x71\x72\143\x69\x64\54\x6e\141\x6d\145\54\153\x65\x79\x77\157\162\144\x2c\155\157\x64\145\154\54\164\151\x63\153\x65\x74\54\145\170\160\x69\x72\x65\54\163\x75\x62\156\165\155\54\143\162\x65\141\x74\x65\164\151\155\145\54\163\x74\141\x74\x75\163\x2c\x74\171\160\x65\54\x65\x78\164\x72\x61\54\x75\x72\x6c\x2c\x73\x63\145\x6e\x65\x5f\163\164\x72";
        goto WIXyM;
        KaLVH:
        $this->table_name = "\x71\162\x63\157\x64\x65";
        goto BKzIe;
        WIXyM:
        $this->plugin_name = "\154\x6f\x6e\x61\153\x69\156\x67\137\146\x6c\141\163\150";
        goto vZeOk;
        vZeOk:
    }
    public function createTempQrcode($name, $keyword, $expireSeconds = 2592000)
    {
        goto wiC1p;
        wiC1p:
        global $_W;
        goto Wtoi1;
        M9riB:
        if (is_error($qrcode)) {
            goto HL1iv;
        }
        goto E1mli;
        ztYFV:
        throw new Exception("\346\x8a\261\346\255\211\xef\xbc\214\xe7\x94\x9f\xe6\x88\x90\344\272\x8c\xe7\xbb\xb4\347\240\201\345\244\261\350\264\245\357\xbc\214\346\x82\250\347\232\204\345\x85\xac\xe4\xbc\x97\xe5\x8f\267\xe5\x8f\xaf\350\203\xbd\344\xb8\x8d\346\224\xaf\346\x8c\x81\345\217\x82\346\x95\xb0\xe4\xba\x8c\347\273\xb4\347\xa0\x81", 9001);
        goto ef0SP;
        dqW7w:
        return $this->insertData($insert);
        goto VQFY0;
        Rm_rn:
        $qrcid = pdo_fetchcolumn("\x53\x45\x4c\x45\x43\124\40\161\162\x63\151\144\40\x46\x52\x4f\115\x20" . tablename("\161\x72\143\157\144\145") . "\x20\127\x48\105\x52\105\x20\x61\143\x69\144\40\x3d\40\72\141\x63\x69\144\40\101\x4e\104\x20\165\x6e\151\x61\143\151\x64\75\x3a\165\x6e\151\141\143\x69\144\40\x41\x4e\104\40\155\x6f\x64\145\x6c\x20\75\40\x27\x32\47\x20\x4f\122\104\105\x52\40\x42\x59\x20\161\x72\143\x69\144\x20\x44\105\123\x43\40\114\x49\x4d\111\x54\40\x31", array("\72\141\143\151\144" => $acid, "\72\165\156\x69\x61\143\151\144" => $uniacid));
        goto HYIbY;
        Wtoi1:
        $acid = intval($_W["\x61\143\151\x64"]);
        goto jD0az;
        Zr2lw:
        HL1iv:
        goto ztYFV;
        IcL3d:
        $account = $this->createWexinAccount();
        goto lfnAQ;
        ef0SP:
        kXYo3:
        goto KlrpG;
        lfnAQ:
        $qrcode = $account->barCodeCreateDisposable($barcode);
        goto M9riB;
        HYIbY:
        $barcode = array("\x65\x78\x70\151\x72\x65\x5f\x73\145\x63\157\x6e\144\x73" => $expireSeconds, "\x61\143\164\151\157\x6e\x5f\156\141\155\145" => "\x51\x52\x5f\x53\x43\105\x4e\x45", "\141\x63\x74\151\157\x6e\137\151\156\146\x6f" => array("\163\x63\145\156\145" => array("\x73\x63\x65\x6e\x65\x5f\x69\x64" => !empty($qrcid) ? $qrcid + 1 : 100001)));
        goto IcL3d;
        VQFY0:
        goto kXYo3;
        goto Zr2lw;
        jD0az:
        $uniacid = intval($_W["\165\x6e\151\141\143\x69\x64"]);
        goto Rm_rn;
        E1mli:
        $insert = array("\165\156\x69\x61\x63\x69\x64" => $_W["\x75\156\151\x61\x63\151\144"], "\141\143\151\144" => $acid, "\161\x72\x63\151\144" => $barcode["\x61\x63\x74\151\157\156\x5f\151\156\146\157"]["\163\x63\x65\156\145"]["\163\143\145\156\x65\x5f\x69\x64"], "\163\x63\145\156\x65\x5f\x73\164\x72" => '', "\153\145\x79\x77\x6f\162\x64" => $keyword, "\x6e\x61\155\145" => $name, "\155\157\144\x65\x6c" => self::TMP_QRCODE, "\164\x69\x63\x6b\x65\164" => $qrcode["\x74\151\x63\x6b\x65\164"], "\x75\162\x6c" => $qrcode["\x75\162\x6c"], "\145\170\160\x69\162\x65" => $expireSeconds, "\x63\x72\x65\141\164\145\164\x69\155\x65" => time(), "\163\x74\141\164\x75\163" => "\x31", "\164\171\160\145" => "\163\x63\x65\x6e\x65");
        goto dqW7w;
        KlrpG:
    }
    public function createForeverQrcode($name, $keyword)
    {
        goto l3540;
        TjF_4:
        $barcode = array("\x61\x63\164\151\x6f\x6e\x5f\x6e\x61\x6d\x65" => "\121\122\x5f\x4c\111\115\111\x54\137\x53\x43\105\116\x45", "\x61\x63\164\x69\x6f\x6e\x5f\151\x6e\146\x6f" => array("\163\x63\x65\156\145" => array("\x73\143\x65\156\x65\x5f\151\144" => !empty($qrcid) ? $qrcid + 1 : 100001)));
        goto kVN1r;
        l3540:
        global $_W;
        goto eylWH;
        eylWH:
        $acid = intval($_W["\141\143\151\x64"]);
        goto Lvw1C;
        U6m4l:
        if (is_error($qrcode)) {
            goto sHQPn;
        }
        goto fjK0k;
        RzpBC:
        X8utL:
        goto tY36z;
        BKl7_:
        goto X8utL;
        goto huyt6;
        yTd5H:
        $qrcode = $account->barCodeCreateFixed($barcode);
        goto U6m4l;
        UzjUk:
        $qrcid = pdo_fetchcolumn("\123\x45\114\105\x43\x54\40\x71\162\143\x69\x64\x20\106\122\x4f\115\x20" . tablename("\161\162\143\x6f\x64\145") . "\x20\127\110\105\x52\x45\40\141\x63\x69\144\x20\x3d\x20\x3a\141\x63\151\144\x20\x41\x4e\104\x20\x75\x6e\151\141\x63\151\144\x3d\x3a\x75\156\x69\x61\143\x69\x64\40\101\116\104\x20\x6d\x6f\x64\145\154\x20\75\40\47\x32\47\40\117\x52\x44\105\x52\40\x42\131\x20\x71\x72\143\x69\x64\40\x44\105\x53\103\40\114\x49\x4d\111\x54\40\61", array("\x3a\141\143\151\144" => $acid, "\72\x75\x6e\151\x61\x63\x69\x64" => $uniacid));
        goto TjF_4;
        huyt6:
        sHQPn:
        goto JglqZ;
        Lvw1C:
        $uniacid = intval($_W["\165\x6e\x69\141\x63\x69\x64"]);
        goto UzjUk;
        fjK0k:
        $insert = array("\165\156\151\141\x63\x69\x64" => $_W["\165\x6e\151\x61\x63\151\x64"], "\x61\143\151\x64" => $acid, "\161\x72\x63\x69\x64" => $barcode["\141\143\x74\151\157\x6e\137\x69\156\x66\x6f"]["\163\x63\145\156\x65"]["\x73\x63\145\156\145\137\x69\144"], "\x73\x63\x65\x6e\x65\137\x73\x74\162" => '', "\153\x65\x79\167\x6f\162\144" => $keyword, "\156\141\x6d\145" => $name, "\x6d\157\144\x65\154" => self::FOREVER_QRCODE, "\164\151\143\x6b\x65\x74" => $qrcode["\x74\x69\143\x6b\145\x74"], "\x75\162\x6c" => $qrcode["\165\x72\x6c"], "\x65\170\160\x69\162\145" => 0, "\143\162\x65\141\x74\x65\x74\151\x6d\x65" => time(), "\x73\164\x61\164\165\x73" => "\61", "\164\171\160\x65" => "\163\143\x65\x6e\x65");
        goto Frvkj;
        kVN1r:
        $account = $this->createWexinAccount();
        goto yTd5H;
        JglqZ:
        throw new Exception("\346\212\261\346\255\x89\357\274\x8c\xe7\224\237\xe6\210\220\xe4\xba\214\xe7\273\264\xe7\240\201\xe5\244\xb1\xe8\xb4\245\357\xbc\214\xe6\x82\xa8\xe7\x9a\x84\xe5\205\254\xe4\274\x97\345\217\xb7\xe5\217\257\350\203\275\xe4\xb8\x8d\346\224\257\346\214\201\345\217\202\xe6\x95\xb0\xe4\272\x8c\xe7\273\264\347\xa0\201", 9001);
        goto RzpBC;
        Frvkj:
        return $this->insertData($insert);
        goto BKl7_;
        tY36z:
    }
    public function createForeverStrSceneQrcode($name, $keyword, $sceneStr)
    {
        goto eM0Nj;
        wD0ig:
        $account = $this->createWexinAccount();
        goto A0UDV;
        AWVJU:
        goto A89HG;
        goto h7fIa;
        BVtlh:
        $barcode = array("\141\143\x74\151\x6f\156\137\156\x61\155\145" => "\121\122\x5f\114\x49\115\111\x54\x5f\123\124\x52\x5f\123\x43\105\x4e\x45", "\141\143\x74\x69\157\156\x5f\x69\x6e\146\x6f" => array("\163\143\x65\x6e\x65" => array("\x73\143\x65\156\145\x5f\x73\164\162" => $sceneStr)));
        goto wD0ig;
        bZh4R:
        throw new Exception("\346\x8a\xb1\346\xad\211\xef\274\214\347\x94\237\346\210\x90\344\272\214\347\xbb\xb4\347\xa0\x81\xe5\xa4\xb1\350\264\245\357\xbc\x8c\346\x82\250\347\x9a\204\345\x85\xac\344\xbc\227\345\x8f\xb7\xe5\217\257\xe8\203\xbd\xe4\xb8\x8d\xe6\x94\257\xe6\214\x81\345\x8f\x82\346\x95\260\344\272\x8c\xe7\xbb\xb4\xe7\240\201", 9001);
        goto XcLyX;
        rSSbv:
        return $this->insertData($insert);
        goto AWVJU;
        rwXej:
        $acid = intval($_W["\x61\x63\x69\144"]);
        goto JHEqg;
        JHEqg:
        $uniacid = intval($_W["\x75\x6e\151\x61\143\x69\x64"]);
        goto BVtlh;
        A0UDV:
        $qrcode = $account->barCodeCreateFixed($barcode);
        goto Fr0Wf;
        h7fIa:
        vUDO9:
        goto bZh4R;
        svsvA:
        $insert = array("\165\156\151\x61\143\x69\144" => $uniacid, "\141\143\x69\144" => $acid, "\x71\x72\143\x69\144" => "\x30", "\x73\143\x65\x6e\145\x5f\163\164\162" => $sceneStr, "\153\x65\171\167\x6f\162\x64" => $keyword, "\156\x61\155\x65" => $name, "\x6d\157\x64\x65\x6c" => self::FOREVER_QRCODE, "\164\151\143\x6b\145\164" => $qrcode["\x74\x69\143\x6b\145\164"], "\165\162\x6c" => $qrcode["\x75\x72\x6c"], "\x65\170\160\151\x72\145" => 0, "\143\162\145\x61\164\145\164\x69\155\145" => time(), "\x73\164\x61\x74\x75\163" => "\x31", "\164\x79\160\145" => "\163\x63\x65\156\145");
        goto rSSbv;
        Fr0Wf:
        if (is_error($qrcode)) {
            goto vUDO9;
        }
        goto svsvA;
        eM0Nj:
        global $_W;
        goto rwXej;
        XcLyX:
        A89HG:
        goto Vn4Ne;
        Vn4Ne:
    }
}
