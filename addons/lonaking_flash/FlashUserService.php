<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/2
 * Time: 下午4:46
 * Exception 5开头
 */
class FlashUserService
{
    /**
     * 增加用户积分数量
     * @param $score
     * @param $openid
     */
    public function addUserScore($score, $openid, $log = '')
    {
        $this->updateUserScore($score, $openid, $log);
    }
    /**
     * 减少用户数量
     * @param $score
     * @param $openid
     */
    public function reduceUserScore($score, $openid, $log = '')
    {
        goto b6jJN;
        iPS03:
        $this->updateUserScore($score, $openid, $log);
        goto d4vCZ;
        d4vCZ:
        P0i3a:
        goto q0yh1;
        TTT6h:
        rLq0r:
        goto iPS03;
        lj0Yz:
        goto P0i3a;
        goto TTT6h;
        b6jJN:
        if ($score < 0) {
            goto rLq0r;
        }
        goto BBban;
        BBban:
        $this->updateUserScore($score * -1, $openid, $log);
        goto lj0Yz;
        q0yh1:
    }
    /**
     * 更新用户积分
     * @param $score
     * @param $openid
     */
    public function updateUserScore($score, $openid, $log = '')
    {
        $this->updateUserCredit("\143\x72\145\144\x69\164\61", $score, $openid, $log);
    }
    public function updateUserMoney($money, $openid, $log = '')
    {
        $this->updateUserCredit("\143\x72\145\144\151\x74\x32", $money, $openid, $log);
    }
    public function addUserMoney($money, $openid, $log = '')
    {
        goto dhRx8;
        bkKY2:
        $this->updateUserMoney($money, $openid, $log);
        goto cvH8L;
        fSfTl:
        $money = $money * -1;
        goto vuc8x;
        dhRx8:
        if (!($money < 0)) {
            goto re0aa;
        }
        goto fSfTl;
        vuc8x:
        re0aa:
        goto bkKY2;
        cvH8L:
    }
    public function reduceUserMoney($money, $openid, $log = '')
    {
        goto CfLpe;
        bOt5T:
        goto GbAkC;
        goto vW8sm;
        CfLpe:
        if ($money < 0) {
            goto Px1p2;
        }
        goto q0nQu;
        XE4dy:
        GbAkC:
        goto H0Mla;
        vW8sm:
        Px1p2:
        goto LuNZo;
        q0nQu:
        $this->updateUserMoney($money * -1, $openid, $log);
        goto bOt5T;
        LuNZo:
        $this->updateUserMoney($money, $openid, $log);
        goto XE4dy;
        H0Mla:
    }
    private function updateUserCredit($type = "\x63\x72\145\144\x69\164\x31", $value, $openid, $log = '')
    {
        goto GUv_b;
        EVNIY:
        $log_arr[2] = '';
        goto LJoBw;
        xKOgW:
        $log_arr[1] = $log == '' ? "\346\234\xaa\xe8\xae\260\xe5\xbd\x95" : $log;
        goto EVNIY;
        FHvoz:
        $uid = mc_openid2uid($openid);
        goto zwmi7;
        zwmi7:
        $log_arr = array();
        goto p7gAh;
        GUv_b:
        load()->model("\155\x63");
        goto FHvoz;
        LJoBw:
        mc_credit_update($uid, $type, $value, $log_arr);
        goto jJNIu;
        p7gAh:
        $log_arr[0] = $uid;
        goto xKOgW;
        jJNIu:
    }
    /**
     * 获取用户积分
     * @param $openid
     */
    public function fetchUserScore($openid)
    {
        goto VCoEf;
        Gyi1r:
        $uid = mc_openid2uid($openid);
        goto KD0wh;
        KD0wh:
        $credits = mc_credit_fetch($uid, array("\x63\x72\x65\x64\151\x74\61"));
        goto SoRKo;
        VCoEf:
        load()->model("\155\x63");
        goto Gyi1r;
        SoRKo:
        return $credits["\x63\x72\145\144\x69\164\x31"];
        goto cdmus;
        cdmus:
    }
    /**
     * 获取用户余额
     * @param $openid
     * @return mixed
     */
    public function fetchUserMoney($openid)
    {
        goto HDPQj;
        hrqRC:
        return $credits["\x63\162\x65\144\151\164\x32"];
        goto xiMsl;
        XtZtV:
        $credits = mc_credit_fetch($uid, array("\x63\162\x65\144\x69\164\62"));
        goto hrqRC;
        tyWDS:
        $uid = mc_openid2uid($openid);
        goto XtZtV;
        HDPQj:
        load()->model("\155\x63");
        goto tyWDS;
        xiMsl:
    }
    /**
     * 获取用户积分，得到一个数组
     * @param $openid
     * @return array
     */
    public function fetchUserCredit($openid)
    {
        goto TfZWW;
        xq9gj:
        return $credits;
        goto gdPwE;
        TfZWW:
        load()->model("\x6d\x63");
        goto C0slE;
        C0slE:
        $uid = mc_openid2uid($openid);
        goto uhAUX;
        uhAUX:
        $credits = mc_credit_fetch($uid);
        goto xq9gj;
        gdPwE:
    }
    /**
     * 获取粉丝信息，包含粉丝的积分 mc_fansinfo
     * @param $openid
     * @return bool
     */
    public function fetchFansInfo($openid)
    {
        goto pUc25;
        YBuW3:
        if (!empty($user)) {
            goto Ey0UZ;
        }
        goto CGS4a;
        aTUt2:
        $user["\155\157\x6e\x65\x79"] = $user["\143\162\x65\x64\x69\x74"]["\143\162\145\x64\x69\x74\x32"];
        goto ComYy;
        pUc25:
        global $_W;
        goto AMLfP;
        N2SQG:
        $user = mc_fansinfo($openid, $_W["\141\x63\x63\157\x75\x6e\x74"]["\x61\143\151\x64"]);
        goto YBuW3;
        gCCqV:
        return $this->fetchFansInfo($openid);
        goto j4MF0;
        j4MF0:
        Ey0UZ:
        goto NqKkJ;
        ComYy:
        return $user;
        goto lcXmq;
        CGS4a:
        $this->oauthFansInfo();
        goto gCCqV;
        AMLfP:
        load()->model("\x6d\x63");
        goto N2SQG;
        NqKkJ:
        $user["\x63\x72\x65\144\151\x74"] = $this->fetchUserCredit($openid);
        goto PKcP_;
        PKcP_:
        $user["\163\x63\157\x72\x65"] = intval($user["\x63\x72\145\144\x69\164"]["\x63\x72\145\x64\x69\164\x31"]);
        goto aTUt2;
        lcXmq:
    }
    /**
    * 通过授权获取用户信息
    * @return array|bool {
                   "subscribe": 1,
                   "openid": "dsfsdajlfsjldfjsadk",
                   "nickname": "abc",
                   "sex": 1,
                   "language": "zh_CN",
                   "city": "昌平",
                   "province": "北京",
                   "country": "中国",
                   "subscribe_time": 1449370178,
                   "remark": "",
                   "groupid": 0,
                   "avatar": "http://wx.qlogo.cn/mmopen//0"
                   }
    *
    * 过期 请勿使用
    */
    public function authFansInfo()
    {
        goto gvEYq;
        SznY5:
        $user = $this->fetchFansInfo($_W["\x6f\160\145\x6e\151\144"]);
        goto T0ddT;
        gvEYq:
        global $_W;
        goto Yue8i;
        T0ddT:
        if (!(is_null($user) || empty($user["\164\141\x67"]["\x6e\x69\143\153\156\141\155\x65"]))) {
            goto hzmHP;
        }
        goto fLH3P;
        gOibk:
        $user["\x74\x61\x67"] = $tagUserInfo;
        goto VVSW1;
        Yue8i:
        load()->model("\x6d\x63");
        goto SznY5;
        fLH3P:
        $tagUserInfo = mc_oauth_userinfo();
        goto Wf1qW;
        OBeDm:
        $user["\x63\162\145\144\x69\x74"] = $this->fetchUserCredit($_W["\157\x70\x65\x6e\151\144"]);
        goto TD903;
        xoniH:
        return $user;
        goto qC6sC;
        VVSW1:
        hzmHP:
        goto OBeDm;
        Wf1qW:
        $user = $this->fetchFansInfo($_W["\x6f\160\x65\x6e\x69\144"]);
        goto gOibk;
        TD903:
        $user["\163\x63\x6f\162\x65"] = intval($user["\x63\x72\145\x64\151\x74"]["\143\x72\145\x64\x69\164\61"]);
        goto E4yym;
        E4yym:
        $user["\x6d\x6f\x6e\x65\171"] = $user["\143\162\x65\144\151\x74"]["\x63\162\145\144\x69\x74\x32"];
        goto xoniH;
        qC6sC:
    }
    /**
    * 请使用此方法
    * @return array|bool{
               "subscribe": 1,
               "openid": "dsfsdajlfsjldfjsadk",
               "nickname": "abc",
               "sex": 1,
               "language": "zh_CN",
               "city": "昌平",
               "province": "北京",
               "country": "中国",
               "subscribe_time": 1449370178,
               "remark": "",
               "groupid": 0,
               "avatar": "http://wx.qlogo.cn/mmopen//0"
               }
    */
    public function oauthFansInfo()
    {
        goto OImlF;
        nLg2T:
        load()->model("\x6d\x63");
        goto tcoF3;
        HMzoI:
        return $user;
        goto gJtxS;
        OImlF:
        global $_W;
        goto nLg2T;
        tcoF3:
        $user = mc_oauth_userinfo();
        goto HMzoI;
        gJtxS:
    }
    /**
     * 获取用户的uid
     * @param $openid
     * @return array|bool
     */
    public function fetchUid($openid)
    {
        goto MjN23;
        qj0pZ:
        return $uid;
        goto CTZmQ;
        MjN23:
        load()->model("\x6d\x63");
        goto Nn8ky;
        Nn8ky:
        $uid = mc_openid2uid($openid);
        goto qj0pZ;
        CTZmQ:
    }
}
