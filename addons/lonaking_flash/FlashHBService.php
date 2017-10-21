<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

goto te6Ii;
iLyMd:
require_once "\x70\141\171\x2f\106\154\141\163\x68\110\x6f\x6e\x67\102\141\157\105\170\143\145\x70\x74\x69\x6f\x6e\56\160\150\x70";
goto lYcKt;
te6Ii:
/**
 * Created by PhpStorm.
 * User: leon
 * Date: 16/3/16
 * Time: 上午10:57
 */
require_once "\x70\141\x79\57\106\x6c\x61\x73\150\x48\157\156\147\102\141\157\110\145\x6c\160\x65\x72\56\x70\150\160";
goto iLyMd;
lYcKt:
class FlashHBService
{
    private $DS;
    private $SIGNTYPE = "\163\150\141\x31";
    private $APPID;
    private $MCHID;
    private $PASSKEY;
    private $NICK_NAME;
    private $SEND_NAME;
    private $WISHING;
    private $ACT_NAME;
    private $REMARK;
    private $apiclient_cert;
    private $apiclient_key;
    private $rootca;
    private $money;
    private $openid;
    private $client_ip;
    public function __construct($openid, $money, $config)
    {
        goto Dsah5;
        qvqxM:
        $this->SEND_NAME = $config["\x73\x65\156\x64\x5f\x6e\141\155\x65"];
        goto Vj54U;
        l27n0:
        $this->client_ip = $config["\143\154\151\x65\x6e\164\137\x69\160"];
        goto AXdHz;
        Dsah5:
        $this->DS = DIRECTORY_SEPARATOR;
        goto gsgse;
        nh68j:
        $this->ACT_NAME = $config["\141\143\164\137\156\141\x6d\145"];
        goto GmhrZ;
        Vj54U:
        $this->WISHING = $config["\167\151\163\x68\151\x6e\147"];
        goto nh68j;
        L0Wl3:
        $this->apiclient_key = $config["\141\160\x69\143\x6c\x69\x65\156\x74\x5f\x6b\x65\171"];
        goto q4Jnf;
        xiHMX:
        $this->NICK_NAME = $config["\156\151\143\153\x5f\156\141\155\145"];
        goto qvqxM;
        v1k5x:
        $this->apiclient_cert = $config["\x61\x70\x69\x63\154\x69\145\156\x74\x5f\x63\x65\162\164"];
        goto L0Wl3;
        gsgse:
        $this->SIGNTYPE = "\163\x68\x61\x31";
        goto py1K0;
        q4Jnf:
        $this->rootca = $config["\x72\x6f\157\x74\143\x61"];
        goto l27n0;
        DY_De:
        $this->MCHID = $config["\x6d\143\x68\151\x64"];
        goto fM3wp;
        AXdHz:
        $this->money = intval($money * 100);
        goto U_srS;
        fM3wp:
        $this->PASSKEY = $config["\x70\141\163\x73\153\x65\171"];
        goto xiHMX;
        py1K0:
        $this->APPID = $config["\x61\x70\x70\151\x64"];
        goto DY_De;
        GmhrZ:
        $this->REMARK = $config["\162\x65\x6d\x61\162\x6b"];
        goto v1k5x;
        U_srS:
        $this->openid = $openid;
        goto Knk1I;
        Knk1I:
    }
    public function send()
    {
        goto ccAfG;
        JQ0sd:
        goto yWs7r;
        goto w41JC;
        G5Rd7:
        yWs7r:
        goto oXze8;
        JcPct:
        GbQhi:
        goto KNH7K;
        ZYFNK:
        $wxHongBaoHelper->setParameter("\x6e\157\156\143\x65\137\x73\x74\162", $commonUtil->create_noncestr());
        goto ZvF3F;
        V23DH:
        if ($responseObj->err_code == "\x53\131\123\124\x45\115\105\122\122\x4f\x52") {
            goto YVhgN;
        }
        goto uKqky;
        vWJBG:
        GFCtB:
        goto ZkV8Y;
        GK_oW:
        $recordData["\x72\145\163\x70\157\156\x73\145\x5f\x65\162\x72\x5f\x63\157\144\145"] = $responseObjArr["\x65\162\162\x5f\143\157\x64\x65"];
        goto W9S4u;
        slKwE:
        if ($responseObj->err_code == "\124\111\x4d\105\137\114\111\x4d\x49\x54\x45\x44") {
            goto H3gR9;
        }
        goto V23DH;
        mrcBR:
        throw new FlashHongBaoException("\347\x8e\xb0\xe5\234\xa8\351\x9d\x9e\347\xba\xa2\xe5\214\205\xe5\217\221\xe6\224\xbe\346\x97\266\xe9\227\xb4\xef\274\214\350\257\xb7\xe5\x9c\250\345\x8c\x97\344\272\254\346\227\xb6\xe9\227\264\x30\x3a\60\x30\55\x38\x3a\x30\x30\344\xb9\213\xe5\244\x96\xe7\x9a\x84\346\x97\xb6\xe9\x97\xb4\345\211\x8d\346\x9d\245\xe9\xa2\x86\345\x8f\x96", 10402);
        goto uIe1W;
        P2zhq:
        $responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
        goto ehi_5;
        obTrm:
        q531Q:
        goto vpI_D;
        ZcvR_:
        if ($responseObj->err_code == "\x4e\117\124\105\x4e\x4f\x55\x47\110") {
            goto ghpFl;
        }
        goto slKwE;
        ehi_5:
        $responseObj = simplexml_load_string($responseXml, "\x53\x69\x6d\160\x6c\145\130\x4d\x4c\x45\154\x65\155\145\156\164", LIBXML_NOCDATA);
        goto EYPOo;
        bnwgL:
        $wxHongBaoHelper->setParameter("\x72\145\x5f\x6f\x70\x65\156\151\144", $this->openid);
        goto X4wH0;
        HZvKo:
        $wxHongBaoHelper->setParameter("\x72\x65\x6d\x61\162\153", $this->REMARK);
        goto brgkp;
        v8pe2:
        goto jGblv;
        goto uaSRb;
        LgM2G:
        $wxHongBaoHelper->setParameter("\143\x6c\x69\145\x6e\x74\137\151\160", empty($this->client_ip) ? "\x31\x32\67\x2e\60\56\x30\56\x31" : $this->client_ip);
        goto PgGs3;
        zx2BT:
        JNy3n:
        goto xUYbP;
        bJZsz:
        $wxHongBaoHelper->setParameter("\x6e\x69\143\153\137\156\x61\155\x65", $this->NICK_NAME);
        goto wkVe0;
        ygLkd:
        $recordData["\x73\164\141\x74\165\163"] = 1;
        goto vSpIY;
        X4wH0:
        $wxHongBaoHelper->setParameter("\164\x6f\164\x61\x6c\x5f\x61\155\x6f\165\x6e\x74", $this->money);
        goto IsPSb;
        TyjOR:
        if (!($responseObj->err_code == "\123\x45\x43\117\116\x44\137\x4f\x56\x45\122\137\x4c\x49\x4d\111\x54\x45\x44")) {
            goto xLoTx;
        }
        goto wxxVo;
        wRFt6:
        $responseObjText = json_encode($responseObj);
        goto Ng0Z2;
        B6vzq:
        jGblv:
        goto nr7p1;
        I6dYE:
        TlLm1:
        goto vpucY;
        W9S4u:
        $recordData["\162\x65\163\x70\157\156\x73\145\x5f\145\x72\x72\137\x63\157\144\145\x5f\144\x65\163"] = $responseObjArr["\145\x72\162\137\143\x6f\x64\145\137\x64\x65\x73"];
        goto FepI6;
        oFs3b:
        goto dkKlB;
        goto I6dYE;
        FepI6:
        $recordData["\162\145\163\160\157\156\163\x65\x5f\162\145\x74\165\162\156\137\x6d\163\x67"] = $responseObjArr["\162\145\x74\165\162\156\x5f\155\x73\147"];
        goto HfyTu;
        qrd5c:
        goto X3s5m;
        goto obTrm;
        wkVe0:
        $wxHongBaoHelper->setParameter("\x73\145\x6e\x64\137\156\141\155\x65", $this->SEND_NAME);
        goto bnwgL;
        nr7p1:
        goto JNy3n;
        goto RWf0e;
        RWf0e:
        H3gR9:
        goto QVkdU;
        ZkV8Y:
        throw new FlashHongBaoException("\xe6\x82\xa8\xe6\235\xa5\xe8\277\237\344\272\x86\357\xbc\x8c\347\xba\xa2\xe5\214\x85\345\xb7\262\347\273\217\345\x8f\221\xe5\256\214\xef\xbc\x81\xef\274\201\xef\xbc\x81", 10401);
        goto JcPct;
        ccAfG:
        $mch_billno = date("\x59\x6d\144\x48\x69\163") . rand(10, 9999);
        goto qtlmE;
        zV1Rd:
        throw new FlashHongBaoException("\xe4\xbb\212\346\x97\245\347\xba\xa2\345\x8c\205\xe5\xb7\262\350\276\xbe\xe4\xb8\x8a\351\x99\220\xef\274\x8c\xe8\257\267\xe6\230\216\346\x97\xa5\345\x86\x8d\350\257\225\x21", 10404);
        goto UdDmB;
        wxxVo:
        throw new FlashHongBaoException("\xe6\257\217\xe5\x88\x86\xe9\x92\237\xe7\272\242\345\x8c\205\345\xb7\xb2\350\276\xbe\xe4\xb8\212\xe9\x99\220\xef\xbc\x8c\xe8\257\267\347\xa8\x8d\345\220\x8e\345\x86\x8d\xe8\257\x95\41", 10405);
        goto Ji2Jm;
        vpucY:
        throw new FlashHongBaoException("\344\273\212\xe6\227\xa5\347\xba\xa2\xe5\x8c\205\345\267\262\350\276\276\344\xb8\x8a\351\231\x90\357\274\214\xe8\xaf\xb7\346\230\216\xe6\227\245\xe5\x86\215\350\257\225\41", 10404);
        goto lwhOp;
        LUU2C:
        oy9lB:
        goto nC6ZZ;
        Ng0Z2:
        $responseObjArr = json_decode($responseObjText, true);
        goto WDlxV;
        iyGWj:
        pdo_insert("\154\157\x6e\141\x6b\151\x6e\x67\x5f\150\x62\x5f\x72\145\143\157\x72\x64", $recordData);
        goto Gh3lj;
        nC6ZZ:
        if ($result_code == "\x53\125\103\x43\105\123\x53") {
            goto bibg5;
        }
        goto AFCzI;
        UmzwH:
        dCZDr:
        goto DQUlu;
        ccvnB:
        if ($return_code == "\x53\x55\x43\103\x45\123\123") {
            goto oy9lB;
        }
        goto UDyP1;
        C8c73:
        $recordData["\163\x65\x6e\144\x5f\x64\141\164\141"] = $postXml;
        goto wRFt6;
        zDph5:
        throw new FlashHongBaoException("\xe6\257\217\xe5\210\206\351\222\237\347\xba\xa2\xe5\x8c\205\345\267\262\350\xbe\276\xe4\270\x8a\351\x99\x90\xef\274\x8c\350\xaf\267\xe7\xa8\215\345\x90\216\xe5\206\215\xe8\257\x95\41", 10405);
        goto UmzwH;
        l1Q2h:
        $commonUtil = new CommonUtil();
        goto KlafG;
        UDyP1:
        $recordData["\x73\164\x61\164\x75\x73"] = 0;
        goto GK_oW;
        cPa_x:
        $recordData = array("\x74\x6f\137\x6f\x70\145\156\151\144" => $this->openid, "\165\156\151\x61\143\151\144" => $_W["\165\156\151\141\143\x69\144"], "\141\155\x6f\165\156\164" => $this->money, "\163\x74\x61\x74\x75\x73" => 0, "\155\143\x68\137\142\x69\x6c\154\x6e\157" => $mch_billno, "\143\x72\145\141\x74\145\x5f\164\x69\x6d\145" => time(), "\165\160\144\141\164\145\x5f\164\151\155\x65" => time());
        goto l1Q2h;
        YwobO:
        pdo_insert("\x6c\157\x6e\141\x6b\x69\x6e\x67\x5f\150\142\x5f\162\145\x63\x6f\x72\x64", $recordData);
        goto ZcvR_;
        DQUlu:
        goto eykwH;
        goto rJXtR;
        WzZtD:
        $url = "\150\x74\x74\160\x73\x3a\57\x2f\141\x70\x69\56\155\x63\150\56\167\145\151\170\151\156\x2e\x71\161\x2e\143\157\155\x2f\155\x6d\x70\x61\x79\x6d\x6b\164\164\x72\141\156\x73\146\145\162\163\x2f\x73\x65\x6e\x64\162\145\144\x70\x61\143\x6b";
        goto P2zhq;
        KNH7K:
        throw new FlashHongBaoException("\347\xba\xa2\xe5\214\x85\xe5\x8f\x91\xe6\224\276\xe5\xa4\xb1\xe8\264\xa5\357\xbc\x81" . $responseObj->return_msg . "\xef\xbc\201\xe8\xaf\xb7\xe7\250\215\xe5\220\216\xe5\x86\x8d\xe8\257\x95\xef\xbc\x81", 10406);
        goto JQ0sd;
        XpydG:
        Q2J6_:
        goto qgWm1;
        H8BIB:
        ghpFl:
        goto DvVOi;
        A4ErH:
        $recordData["\x72\145\x73\x70\x6f\156\163\x65\x5f\x72\x65\163\165\x6c\164\137\143\x6f\x64\145"] = $responseObjArr["\x72\x65\x73\x75\154\164\x5f\x63\x6f\144\x65"];
        goto ccvnB;
        uIe1W:
        Dvt5E:
        goto sOJM_;
        ZvF3F:
        $wxHongBaoHelper->setParameter("\x6d\x63\x68\137\x62\x69\154\154\156\157", $mch_billno);
        goto QbiGP;
        Ru7pm:
        $recordData["\162\145\x73\x70\157\156\163\x65\137\162\145\164\x75\x72\x6e\x5f\143\x6f\x64\x65"] = $responseObjArr["\162\145\164\165\162\156\x5f\x63\x6f\x64\x65"];
        goto A4ErH;
        L5FPK:
        goto Dvt5E;
        goto eOu9R;
        qtlmE:
        global $_W;
        goto cPa_x;
        xUYbP:
        goto Q2J6_;
        goto H8BIB;
        nbdRM:
        X3s5m:
        goto L5FPK;
        QVkdU:
        throw new FlashHongBaoException("\xe7\x8e\xb0\xe5\234\xa8\xe9\235\x9e\xe7\xba\xa2\xe5\214\205\xe5\x8f\221\xe6\x94\276\346\227\xb6\xe9\227\xb4\xef\274\x8c\350\xaf\267\345\234\250\345\214\x97\344\xba\254\xe6\227\266\xe9\x97\264\60\x3a\x30\60\x2d\70\72\60\x30\344\xb9\213\xe5\244\226\347\x9a\x84\346\227\xb6\351\x97\xb4\345\211\215\346\235\xa5\351\242\x86\345\x8f\x96", 10402);
        goto zx2BT;
        JWEhA:
        if ($responseObj->err_code == "\104\101\131\137\117\x56\105\x52\137\114\x49\x4d\x49\124\x45\104") {
            goto TlLm1;
        }
        goto TyjOR;
        vpI_D:
        throw new FlashHongBaoException("\xe7\263\273\xe7\xbb\x9f\xe7\271\201\xe5\xbf\231\357\xbc\214\xe8\257\xb7\xe7\250\215\xe5\220\x8e\xe5\x86\x8d\xe8\257\225\41" . $responseObj->err_code_des, 10403);
        goto nbdRM;
        YDr7E:
        $wxHongBaoHelper->setParameter("\167\x69\163\x68\151\x6e\x67", $this->WISHING);
        goto LgM2G;
        MX2Kl:
        return "\347\xba\xa2\xe5\x8c\205\xe5\x8f\x91\346\x94\276\346\x88\220\xe5\x8a\237\357\xbc\x81\351\x87\221\351\242\x9d\xe4\270\xba\xef\xbc\232" . $total_amount . "\xe5\x85\x83\357\274\x81";
        goto G5Rd7;
        uKqky:
        if ($responseObj->err_code == "\104\101\131\x5f\x4f\x56\105\122\x5f\x4c\x49\115\111\x54\105\x44") {
            goto n81zg;
        }
        goto CvPJm;
        SGsx_:
        $result_code = $responseObj->result_code;
        goto C8c73;
        hnBje:
        $wxHongBaoHelper->setParameter("\x77\170\x61\x70\160\x69\144", $this->APPID);
        goto bJZsz;
        tBCSY:
        goto y32KQ;
        goto LUU2C;
        hlknu:
        $recordData["\x72\x65\163\160\x6f\x6e\163\x65\137\x65\x72\162\x5f\143\157\x64\145\137\x64\x65\x73"] = $responseObjArr["\145\162\x72\x5f\x63\157\144\145\137\x64\x65\x73"];
        goto eAs0v;
        rJXtR:
        n81zg:
        goto zV1Rd;
        lwhOp:
        dkKlB:
        goto qrd5c;
        eAs0v:
        $recordData["\x72\145\163\160\157\x6e\x73\x65\137\x72\x65\x74\x75\x72\x6e\137\155\x73\x67"] = $responseObjArr["\x72\x65\164\165\162\156\137\155\163\147"];
        goto iyGWj;
        w41JC:
        bibg5:
        goto ygLkd;
        EYPOo:
        $return_code = $responseObj->return_code;
        goto SGsx_;
        DvVOi:
        throw new FlashHongBaoException("\346\202\250\346\235\xa5\350\277\x9f\xe4\xba\206\xef\xbc\214\xe7\272\242\xe5\214\205\345\267\262\347\273\217\xe5\217\221\345\256\214\xef\274\201\xef\xbc\201\357\274\201", 10401);
        goto XpydG;
        ZO3rk:
        throw new FlashHongBaoException("\xe7\xb3\273\347\273\237\347\xb9\x81\xe5\277\231\xef\274\214\xe8\xaf\xb7\xe7\250\215\xe5\x90\x8e\xe5\x86\x8d\xe8\257\225\x21" . $responseObj->err_code_des, 10403);
        goto B6vzq;
        oXze8:
        y32KQ:
        goto VSJG1;
        WDlxV:
        $recordData["\162\145\163\x70\157\156\x73\145\x5f\157\142\x6a\x5f\x74\x65\170\x74"] = $responseObjText;
        goto Ru7pm;
        vSpIY:
        pdo_insert("\x6c\x6f\156\x61\x6b\x69\156\147\137\150\x62\137\162\x65\x63\157\162\x64", $recordData);
        goto Rg9Pg;
        sOJM_:
        goto GbQhi;
        goto vWJBG;
        HfyTu:
        $recordData["\145\x72\162\157\162\x5f\x63\x6f\x64\x65"] = $responseObjArr["\x65\x72\162\157\x72\137\x63\157\x64\145"];
        goto YwobO;
        brgkp:
        $postXml = '';
        goto XEfbR;
        KlafG:
        $wxHongBaoHelper = new FlashHongBaoHelper($this->apiclient_cert, $this->apiclient_key, $this->rootca, $this->PASSKEY);
        goto ZYFNK;
        dfJmt:
        if ($responseObj->err_code == "\x54\x49\x4d\105\137\114\x49\x4d\111\x54\105\104") {
            goto XU37l;
        }
        goto Rd2U0;
        QbiGP:
        $wxHongBaoHelper->setParameter("\x6d\x63\x68\x5f\x69\144", $this->MCHID);
        goto hnBje;
        uaSRb:
        YVhgN:
        goto ZO3rk;
        AFCzI:
        $recordData["\x72\x65\163\160\157\x6e\163\145\137\145\x72\x72\x5f\x63\157\144\x65"] = $responseObjArr["\x65\162\x72\137\x63\157\144\x65"];
        goto hlknu;
        IsPSb:
        $wxHongBaoHelper->setParameter("\x74\157\x74\x61\x6c\137\156\165\155", 1);
        goto YDr7E;
        XEfbR:
        try {
            $postXml = $wxHongBaoHelper->create_hongbao_xml();
        } catch (FlashHongBaoException $e) {
            goto gG3ui;
            Xhrjb:
            throw new FlashHongBaoException("\347\xba\242\xe5\x8c\205\xe5\217\221\346\224\276\345\xa4\261\xe8\xb4\245\357\xbc\201" . $e->getErrorMessage() . "\357\274\201\xe8\xaf\267\xe7\250\215\xe5\220\x8e\345\x86\x8d\350\257\x95\357\xbc\201", 10406);
            goto ktyPK;
            gG3ui:
            $parametersText = json_encode($wxHongBaoHelper->parameters);
            goto Fth0_;
            Fth0_:
            $recordData["\x73\x65\156\x64\x5f\x64\141\x74\141"] = $parametersText;
            goto RGecQ;
            RGecQ:
            pdo_insert("\154\x6f\156\x61\x6b\151\156\x67\x5f\x68\142\x5f\162\x65\x63\x6f\162\144", $recordData);
            goto Xhrjb;
            ktyPK:
        }
        goto WzZtD;
        Ji2Jm:
        xLoTx:
        goto oFs3b;
        qgWm1:
        throw new FlashHongBaoException("\xe7\272\242\xe5\x8c\x85\345\217\221\xe6\224\276\xe5\244\261\350\xb4\xa5\xef\xbc\201" . $responseObj->return_msg . "\357\274\201\xe8\257\xb7\xe7\xa8\x8d\xe5\220\x8e\345\x86\215\xe8\257\x95\357\274\x81", 10406);
        goto tBCSY;
        Gh3lj:
        if ($responseObj->err_code == "\x4e\x4f\x54\105\116\117\125\x47\110") {
            goto GFCtB;
        }
        goto dfJmt;
        PgGs3:
        $wxHongBaoHelper->setParameter("\x61\143\x74\137\x6e\x61\x6d\x65", $this->ACT_NAME);
        goto HZvKo;
        Rg9Pg:
        $total_amount = $responseObj->total_amount * 1.0 / 100;
        goto MX2Kl;
        eOu9R:
        XU37l:
        goto mrcBR;
        Rd2U0:
        if ($responseObj->err_code == "\x53\131\123\124\105\115\105\122\x52\117\122") {
            goto q531Q;
        }
        goto JWEhA;
        CvPJm:
        if (!($responseObj->err_code == "\x53\x45\x43\117\x4e\x44\137\117\x56\105\122\x5f\x4c\x49\115\x49\124\x45\104")) {
            goto dCZDr;
        }
        goto zDph5;
        UdDmB:
        eykwH:
        goto v8pe2;
        VSJG1:
    }
}
