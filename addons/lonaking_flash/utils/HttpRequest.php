<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

class HttpRequest
{
    public static function get($url, $params = array(), $header = '')
    {
        goto q10xy;
        mJ0y7:
        foreach ($params as $key => $value) {
            goto e_UYE;
            ddeXh:
            $url .= "\77" . $key . "\75" . $value;
            goto mSZcK;
            BzD9j:
            fXwxr:
            goto o2fup;
            mSZcK:
            $first = true;
            goto mYRaW;
            mYRaW:
            VApH4:
            goto BzD9j;
            XnHaC:
            MAkwY:
            goto ddeXh;
            vosn5:
            goto VApH4;
            goto XnHaC;
            LyVbj:
            $url .= "\x26" . $key . "\x3d" . $value;
            goto vosn5;
            e_UYE:
            if ($first == false) {
                goto MAkwY;
            }
            goto LyVbj;
            o2fup:
        }
        goto ywKmJ;
        fM3b_:
        $first = $normal = strpos($url, "\77");
        goto mJ0y7;
        ywKmJ:
        GDhal:
        goto rwTLV;
        q10xy:
        if (empty($params)) {
            goto so7ln;
        }
        goto fM3b_;
        yshgw:
        return self::uCurl($url, "\107\105\124", array(), $header);
        goto BwMY2;
        rwTLV:
        so7ln:
        goto yshgw;
        BwMY2:
    }
    public static function post($url, $data = array(), $header = '')
    {
        return self::uCurl($url, "\x50\x4f\x53\x54", $data, $header);
    }
    private static function uCurl($url, $method, $params = array(), $header = '')
    {
        goto kWZjh;
        h4nyO:
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        goto sW8vf;
        Bs048:
        I1e1p:
        goto d0Xdp;
        Rqk2Z:
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        goto ydbE5;
        tOMTf:
        switch ($method) {
            case "\107\105\124":
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                goto BiZD7;
            case "\120\117\x53\124":
                goto x3m0z;
                vCaVv:
                goto BiZD7;
                goto vHoM2;
                zi8Yo:
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                goto vCaVv;
                dVcrW:
                curl_setopt($curl, CURLOPT_NOBODY, true);
                goto zi8Yo;
                x3m0z:
                curl_setopt($curl, CURLOPT_POST, true);
                goto dVcrW;
                vHoM2:
            case "\x50\x55\x54":
                goto Qc_1e;
                Qc_1e:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "\x50\125\124");
                goto M56gu;
                M56gu:
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
                goto AjGJ1;
                AjGJ1:
                goto BiZD7;
                goto gTIfJ;
                gTIfJ:
            case "\104\x45\x4c\105\x54\x45":
                goto SZUp0;
                SZUp0:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "\104\105\x4c\105\124\x45");
                goto Nje9k;
                Nje9k:
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                goto hiuia;
                hiuia:
                goto BiZD7;
                goto XuNw7;
                XuNw7:
        }
        goto XHsA8;
        ydbE5:
        goto oQ_6r;
        goto Eg1xu;
        xxoai:
        return $res;
        goto lD19n;
        hjb2A:
        $data = curl_exec($curl);
        goto WW3TM;
        D0tRr:
        return false;
        goto J03n1;
        OlQVq:
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        goto tOMTf;
        sW8vf:
        if ($header == '') {
            goto gP8T7;
        }
        goto Rqk2Z;
        TG2a7:
        oQ_6r:
        goto OlQVq;
        rkhZq:
        curl_setopt($curl, CURLOPT_URL, $url);
        goto Ej54v;
        LYnej:
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        goto TG2a7;
        BCplp:
        $header[] = "\x41\x63\x63\x65\160\164\55\114\x61\x6e\x67\165\141\x67\145\x3a\40\172\150\55\103\116\73\x71\75\x30\56\x38";
        goto LYnej;
        Eg1xu:
        gP8T7:
        goto BCplp;
        bxfZA:
        curl_close($curl);
        goto x_Zpq;
        Ej54v:
        curl_setopt($curl, CURLOPT_HEADER, false);
        goto Jf2Tq;
        Jf2Tq:
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        goto g6kpp;
        d0Xdp:
        $res = $data;
        goto xxoai;
        g6kpp:
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        goto h4nyO;
        JH9S_:
        BiZD7:
        goto hjb2A;
        uneVK:
        $timeout = 15;
        goto rkhZq;
        XHsA8:
        fcG2G:
        goto JH9S_;
        lD19n:
        Nse10:
        goto myOjp;
        J03n1:
        goto Nse10;
        goto Bs048;
        x_Zpq:
        if ($status == 200) {
            goto I1e1p;
        }
        goto D0tRr;
        kWZjh:
        $curl = curl_init();
        goto uneVK;
        WW3TM:
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        goto bxfZA;
        myOjp:
    }
}
