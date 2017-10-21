<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

class HttpJsonRequest
{
    public static function get($url, $params = array(), $header = '')
    {
        goto WORlO;
        wrkEk:
        AHze3:
        goto cl0vD;
        cCzUB:
        $first = $normal = strpos($url, "\77");
        goto y3IUl;
        WORlO:
        if (empty($params)) {
            goto ep4TR;
        }
        goto cCzUB;
        cl0vD:
        ep4TR:
        goto eSWOK;
        eSWOK:
        return self::uCurl($url, "\x47\105\x54", array(), $header);
        goto St6Kw;
        y3IUl:
        foreach ($params as $key => $value) {
            goto bctTx;
            cZ3gK:
            jFdt9:
            goto djvXu;
            JSneM:
            goto YxpOG;
            goto Iw8pn;
            bctTx:
            if ($first == false) {
                goto his_x;
            }
            goto Bgy6C;
            icwI1:
            YxpOG:
            goto cZ3gK;
            Iw8pn:
            his_x:
            goto oms5q;
            VZFjM:
            $first = true;
            goto icwI1;
            oms5q:
            $url .= "\x3f" . $key . "\x3d" . $value;
            goto VZFjM;
            Bgy6C:
            $url .= "\46" . $key . "\x3d" . $value;
            goto JSneM;
            djvXu:
        }
        goto wrkEk;
        St6Kw:
    }
    public static function post($url, $data = array(), $header = '')
    {
        return self::uCurl($url, "\x50\x4f\x53\124", $data, $header);
    }
    private static function uCurl($url, $method, $params = array(), $header = '')
    {
        goto uvTTD;
        AvI20:
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        goto UQ0x0;
        q8Nud:
        $data = curl_exec($curl);
        goto S9cJ4;
        vY_3T:
        return false;
        goto QL_28;
        jlBjx:
        YKiLS:
        goto HuhTd;
        Uvvu9:
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        goto lAmdh;
        GjTqg:
        curl_close($curl);
        goto B7FGZ;
        dkLSc:
        NqQgj:
        goto q8Nud;
        uGYlZ:
        return $res;
        goto CPTIn;
        HS4yG:
        curl_setopt($curl, CURLOPT_HEADER, false);
        goto LiX_U;
        XgQx_:
        if ($header == '') {
            goto GZM8K;
        }
        goto EmrWk;
        UQ0x0:
        JkAFE:
        goto Uvvu9;
        sd0S4:
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        goto bNLJM;
        lAmdh:
        switch ($method) {
            case "\x47\x45\124":
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                goto NqQgj;
            case "\x50\117\x53\x54":
                goto YpbkZ;
                ywcXz:
                goto NqQgj;
                goto jMBqe;
                eTqJH:
                curl_setopt($curl, CURLOPT_NOBODY, true);
                goto o7oMU;
                o7oMU:
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                goto ywcXz;
                YpbkZ:
                curl_setopt($curl, CURLOPT_POST, true);
                goto eTqJH;
                jMBqe:
            case "\x50\x55\x54":
                goto H0lXu;
                Byvyk:
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
                goto ryJqd;
                H0lXu:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "\120\125\124");
                goto Byvyk;
                ryJqd:
                goto NqQgj;
                goto mWUWB;
                mWUWB:
            case "\104\x45\x4c\105\x54\105":
                goto S4KqT;
                aq_W4:
                goto NqQgj;
                goto A9hos;
                iyB1o:
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                goto aq_W4;
                S4KqT:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "\104\105\114\105\124\x45");
                goto iyB1o;
                A9hos:
        }
        goto CfE7r;
        Jh0dQ:
        curl_setopt($curl, CURLOPT_URL, $url);
        goto HS4yG;
        B7FGZ:
        if ($status == 200) {
            goto YKiLS;
        }
        goto vY_3T;
        bNLJM:
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        goto XgQx_;
        XOLAm:
        $timeout = 15;
        goto Jh0dQ;
        QL_28:
        goto odIwE;
        goto jlBjx;
        CfE7r:
        qggDK:
        goto dkLSc;
        CPTIn:
        odIwE:
        goto SMd4c;
        SpaAO:
        goto JkAFE;
        goto BJHIH;
        S9cJ4:
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        goto GjTqg;
        EmrWk:
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        goto SpaAO;
        uvTTD:
        $curl = curl_init();
        goto XOLAm;
        Wdujj:
        $header[] = "\x41\x63\143\x65\160\164\55\x4c\141\x6e\147\x75\141\x67\145\x3a\40\x7a\x68\x2d\103\116\73\161\75\x30\x2e\70";
        goto AvI20;
        BJHIH:
        GZM8K:
        goto Wdujj;
        LiX_U:
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        goto sd0S4;
        HuhTd:
        $res = json_decode($data, true);
        goto uGYlZ;
        SMd4c:
    }
}
