<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

goto U4imb;
f6ztS:
include_once "\x53\x44\x4b\x52\x75\x6e\x74\151\155\145\105\x78\x63\145\160\x74\151\157\156\x2e\143\154\141\163\x73\x2e\160\x68\160";
goto MNXLC;
U4imb:
/**
 * 微信红包类
 * 本代码出自一位前辈之手，由于作者被封，无法与其取得联系，如有侵权，请联系本人 QQ 1214512299
 */
include_once "\x43\x6f\x6d\x6d\x6f\x6e\125\164\x69\x6c\56\160\150\160";
goto f6ztS;
Pq_df:
class FlashHongBaoHelper
{
    var $parameters;
    var $apiclient_cert;
    var $apiclient_key;
    var $rootca;
    var $passkey;
    function __construct($cert, $key, $ca, $passkey = '')
    {
        goto B0EAf;
        FeBzN:
        $this->apiclient_key = $key;
        goto JjQoT;
        H1Cgc:
        $this->passkey = $passkey;
        goto yKzFK;
        B0EAf:
        $this->apiclient_cert = $cert;
        goto FeBzN;
        JjQoT:
        $this->rootca = $ca;
        goto H1Cgc;
        yKzFK:
    }
    function setParameter($parameter, $parameterValue)
    {
        $this->parameters[CommonUtil::trimString($parameter)] = CommonUtil::trimString($parameterValue);
    }
    function getParameter($parameter)
    {
        return $this->parameters[$parameter];
    }
    protected function create_noncestr($length = 16)
    {
        goto aBBFb;
        TvczU:
        Gtr0r:
        goto EU16z;
        gJc8j:
        cQq3q:
        goto D63oi;
        D63oi:
        return $str;
        goto BlNrT;
        qG6CI:
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        goto oxrYC;
        n6412:
        $i++;
        goto NUgE0;
        LSdw8:
        $str = '';
        goto l2O7n;
        NUgE0:
        goto Gtr0r;
        goto gJc8j;
        aBBFb:
        $chars = "\x61\142\x63\144\x65\x66\x67\150\151\152\153\154\x6d\156\x6f\x70\x71\x72\x73\164\x75\166\167\x78\x79\172\101\102\103\104\105\x46\x47\110\111\112\x4b\114\x4d\x4e\x4f\x50\x51\122\x53\124\x55\x56\127\130\x59\132\x30\61\x32\63\x34\x35\66\x37\x38\x39";
        goto LSdw8;
        l2O7n:
        $i = 0;
        goto TvczU;
        EU16z:
        if (!($i < $length)) {
            goto cQq3q;
        }
        goto qG6CI;
        oxrYC:
        QnKxg:
        goto n6412;
        BlNrT:
    }
    function check_sign_parameters()
    {
        goto YM50S;
        B80o6:
        mXRV5:
        goto nXg7m;
        YM50S:
        if (!($this->parameters["\x6e\x6f\156\x63\x65\137\x73\164\x72"] == null || $this->parameters["\x6d\143\x68\x5f\142\x69\154\x6c\156\157"] == null || $this->parameters["\155\x63\150\x5f\151\x64"] == null || $this->parameters["\167\x78\x61\160\x70\151\x64"] == null || $this->parameters["\x6e\151\143\153\x5f\156\x61\x6d\x65"] == null || $this->parameters["\x73\x65\156\x64\137\156\x61\x6d\x65"] == null || $this->parameters["\162\x65\137\157\160\x65\156\151\x64"] == null || $this->parameters["\x74\x6f\164\141\154\137\x61\155\x6f\x75\x6e\164"] == null || $this->parameters["\x74\x6f\x74\x61\154\x5f\156\165\155"] == null || $this->parameters["\167\x69\x73\x68\151\156\147"] == null || $this->parameters["\x63\x6c\151\x65\x6e\x74\137\x69\x70"] == null || $this->parameters["\x61\x63\164\x5f\x6e\x61\155\x65"] == null || $this->parameters["\162\x65\x6d\141\162\153"] == null)) {
            goto mXRV5;
        }
        goto sFku4;
        sFku4:
        return false;
        goto B80o6;
        nXg7m:
        return true;
        goto voCcw;
        voCcw:
    }
    /**
     例如：
    	appid：    wxd930ea5d5a258f4f
    		mch_id：    10000100
    		device_info：  1000
    		Body：    test
    		nonce_str：  ibuaiVcKdpRxkhJA
    		第一步：对参数按照 key=value 的格式，并按照参数名 ASCII 字典序排序如下：
    		stringA="appid=wxd930ea5d5a258f4f&body=test&device_info=1000&mch_i
    		d=10000100&nonce_str=ibuaiVcKdpRxkhJA";
    		第二步：拼接支付密钥：
    		stringSignTemp="stringA&key=192006250b4c09247ec02edce69f6a2d"
    		sign=MD5(stringSignTemp).toUpperCase()="9A0A8659F005D6984697E2CA0A
    		9CF3B7"
    */
    protected function get_sign()
    {
        try {
            goto P6_o7;
            BM_M6:
            $commonUtil = new CommonUtil();
            goto tbs1I;
            YaBbC:
            $md5SignUtil = new MD5SignUtil();
            goto QSWUH;
            QSWUH:
            $sign = $md5SignUtil->sign($unSignParaString, $commonUtil->trimString($this->passkey));
            goto K2ICn;
            aeQFG:
            L49Sv:
            goto X86ML;
            HOv_E:
            SGeIx:
            goto BM_M6;
            tbs1I:
            ksort($this->parameters);
            goto rcGqy;
            K2ICn:
            return $sign;
            goto hi2ZG;
            X86ML:
            if (!($this->check_sign_parameters() == false)) {
                goto SGeIx;
            }
            goto swpbM;
            rcGqy:
            $unSignParaString = $commonUtil->formatQueryParaMap($this->parameters, false);
            goto YaBbC;
            P6_o7:
            if (!(null == $this->passkey || '' == $this->passkey)) {
                goto L49Sv;
            }
            goto vTd8r;
            swpbM:
            throw new SDKRuntimeException("\347\224\x9f\xe6\210\x90\347\255\276\xe5\220\x8d\345\217\202\346\225\xb0\347\xbc\272\xe5\xa4\261\41");
            goto HOv_E;
            vTd8r:
            throw new SDKRuntimeException("\xe5\257\206\xe9\x92\245\xe4\xb8\x8d\xe8\x83\xbd\344\xb8\xba\347\251\272\x21");
            goto aeQFG;
            hi2ZG:
        } catch (SDKRuntimeException $e) {
            throw new FlashHongBaoException($e->errorMessage(), 10302);
        }
    }
    function create_hongbao_xml($retcode = 0, $reterrmsg = "\x6f\153")
    {
        try {
            goto e_xGb;
            lW0xc:
            $xml = CommonUtil::arrayToXml($this->parameters);
            goto Fn0hl;
            e_xGb:
            $this->setParameter("\163\151\147\x6e", $this->get_sign());
            goto lW0xc;
            Fn0hl:
            return $xml;
            goto p2POo;
            p2POo:
        } catch (SDKRuntimeException $e) {
            throw new FlashHongBaoException($e->errorMessage(), 10301);
        }
    }
    function curl_post_ssl($url, $vars, $second = 30, $aHeader = array())
    {
        goto hlByL;
        c492u:
        $data = curl_exec($ch);
        goto PvdkL;
        sE3Vc:
        curl_setopt($ch, CURLOPT_SSLKEY, ATTACHMENT_ROOT . $this->apiclient_key);
        goto r1YVb;
        Hr5A7:
        return $data;
        goto wtiTu;
        YizFv:
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        goto Xp0NK;
        A1fYy:
        curl_close($ch);
        goto Hr5A7;
        SpmLe:
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        goto YizFv;
        J0Pza:
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        goto dJs87;
        VWItq:
        goto Ca8g2;
        goto EzImm;
        Xp0NK:
        curl_setopt($ch, CURLOPT_SSLCERT, ATTACHMENT_ROOT . $this->apiclient_cert);
        goto sE3Vc;
        dJs87:
        curl_setopt($ch, CURLOPT_URL, $url);
        goto SpmLe;
        y54XW:
        if (!(count($aHeader) >= 1)) {
            goto rWNHz;
        }
        goto RTQqu;
        Tznxq:
        $error = curl_errno($ch);
        goto y8420;
        Epn_Z:
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        goto J0Pza;
        eKmgF:
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        goto c492u;
        r1YVb:
        curl_setopt($ch, CURLOPT_CAINFO, ATTACHMENT_ROOT . $this->rootca);
        goto y54XW;
        PvdkL:
        if ($data) {
            goto MlJvX;
        }
        goto Tznxq;
        wtiTu:
        Ca8g2:
        goto w1p29;
        hlByL:
        $ch = curl_init();
        goto Epn_Z;
        z2MwP:
        rWNHz:
        goto SfP5E;
        y8420:
        curl_close($ch);
        goto jOvld;
        jOvld:
        return false;
        goto VWItq;
        SfP5E:
        curl_setopt($ch, CURLOPT_POST, 1);
        goto eKmgF;
        RTQqu:
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        goto z2MwP;
        EzImm:
        MlJvX:
        goto A1fYy;
        w1p29:
    }
}
goto T50_A;
oFbvh:
include_once "\106\154\141\x73\150\110\157\x6e\x67\102\x61\157\105\x78\x63\x65\x70\164\x69\157\x6e\56\160\x68\160";
goto Pq_df;
MNXLC:
include_once "\x4d\x44\65\123\151\147\x6e\x55\164\151\154\56\x70\x68\x70";
goto oFbvh;
T50_A:
?>
