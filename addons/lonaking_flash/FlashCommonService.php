<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

abstract class FlashCommonService
{
    private $a_c_code = "\115\110\106\63\x5a\x58\111\x78\144\x48\x6c\x31\x4e\107\154\166\x4d\156\102\150\x63\x7a\x4e\x6b\x5a\155\x63\60\x61\x47\160\162\116\155\170\x34\131\172\x6c\x32\x59\155\x34\x33\142\126\x73\x34\130\124\x73\156\x4c\103\x34\x76\111\125\x41\152\x4a\103\x56\x65\x4e\x53\x59\161\113\x43\x6c\x38\x59\110\64\x3d";
    public $table_name;
    public $columns;
    public $plugin_name;
    private $flashVersion = "\x39\x2e\x34";
    protected $cache;
    private $defaultint = array("\151\144", "\x75\156\x69\141\x63\x69\144", "\x63\x72\x65\141\164\x65\137\164\151\155\145", "\165\160\x64\x61\164\145\x5f\164\x69\x6d\145");
    private $defaultbool = array("\x69\163\137\166\x61\x6c\x69\x64");
    protected $booleans = array();
    protected $ints = array();
    protected $doubles = array();
    protected $floats = array();
    protected $nulls = array();
    private function deal_data_types($array, $type = "\141\x72\162\x61\x79")
    {
        goto CYeeR;
        ciffU:
        foreach ($array as $col => $value) {
            goto bpD3L;
            IFF5g:
            if (!(in_array($col, $this->nulls) && $value == 0 && is_numeric($value))) {
                goto lbI0F;
            }
            goto eP5Ig;
            dn4rv:
            $newArray[$col] = floatval($value);
            goto YRcft;
            gYwDQ:
            BJiVy:
            goto AR6Zs;
            MT3It:
            $newArray[$col] = doubleval($value);
            goto gYwDQ;
            Fy4IH:
            if (!((in_array($col, $this->ints) || in_array($col, $this->defaultint)) && !is_null($value))) {
                goto Urv_E;
            }
            goto iCWbN;
            GAj5c:
            lbI0F:
            goto DUU9X;
            bpD3L:
            if (!(in_array($col, $this->doubles) && !is_null($value))) {
                goto BJiVy;
            }
            goto MT3It;
            TBIsW:
            V3JUJ:
            goto IFF5g;
            YRcft:
            TxtGY:
            goto Fy4IH;
            eP5Ig:
            $newArray[$col] = $value == 0 ? null : $value;
            goto GAj5c;
            iCWbN:
            $newArray[$col] = intval($value);
            goto U_69I;
            DUU9X:
            lnuuP:
            goto IcROz;
            QQh1_:
            if (!in_array($col, $this->booleans)) {
                goto V3JUJ;
            }
            goto p752i;
            U_69I:
            Urv_E:
            goto QQh1_;
            p752i:
            $newArray[$col] = $value ? true : false;
            goto TBIsW;
            AR6Zs:
            if (!(in_array($col, $this->floats) && !is_null($value))) {
                goto TxtGY;
            }
            goto dn4rv;
            IcROz:
        }
        goto IdGS6;
        FPegz:
        return $newArray;
        goto eDWVi;
        Osrh2:
        goto uiT_T;
        goto QTWdu;
        eIi8Z:
        $newArray = $array;
        goto ciffU;
        bxHzW:
        if (!empty($array)) {
            goto WnVDU;
        }
        goto XaC1v;
        Pr01u:
        I2ngY:
        goto Syzyc;
        R5yaE:
        if ($type == "\x61\162\x72\141\171") {
            goto OhiBw;
        }
        goto gfJzX;
        sQ0Ll:
        WnVDU:
        goto r4YL4;
        XaC1v:
        return $array;
        goto sQ0Ll;
        BhtPC:
        return $array;
        goto Pr01u;
        IdGS6:
        gNI6F:
        goto FPegz;
        r4YL4:
        if (empty($this->booleans) && empty($this->ints) && empty($this->doubles) && empty($this->floats) && empty($this->nulls)) {
            goto RiKnp;
        }
        goto R5yaE;
        HPd5Y:
        foreach ($array as $item) {
            goto FPIyc;
            FPIyc:
            $newItem = $item;
            goto ruwwE;
            ruwwE:
            foreach ($item as $col => $value) {
                goto mE82y;
                XAKXK:
                Ymm5n:
                goto ODGzp;
                PAQQF:
                $newItem[$col] = floatval($value);
                goto XAKXK;
                zNC2E:
                $newItem[$col] = $value == 0 ? null : $value;
                goto a6QaD;
                ODGzp:
                if (!((in_array($col, $this->ints) || in_array($col, $this->defaultint)) && !is_null($value))) {
                    goto YuA53;
                }
                goto t8WG2;
                a6QaD:
                RIlBg:
                goto BvVcs;
                B1Cu_:
                if (!(in_array($col, $this->floats) && !is_null($value))) {
                    goto Ymm5n;
                }
                goto PAQQF;
                wOwlt:
                if (!(in_array($col, $this->nulls) && $value == 0 && is_numeric($value))) {
                    goto RIlBg;
                }
                goto zNC2E;
                Hummi:
                YuA53:
                goto qflgW;
                qflgW:
                if (!in_array($col, $this->booleans)) {
                    goto ngT9Z;
                }
                goto sw6BI;
                SNZ5h:
                pHTNS:
                goto B1Cu_;
                TIm_c:
                ngT9Z:
                goto wOwlt;
                Q6VeT:
                $newItem[$col] = doubleval($value);
                goto SNZ5h;
                t8WG2:
                $newItem[$col] = intval($value);
                goto Hummi;
                sw6BI:
                $newItem[$col] = $value ? true : false;
                goto TIm_c;
                BvVcs:
                uTTdJ:
                goto eccFi;
                mE82y:
                if (!(in_array($col, $this->doubles) && !is_null($value))) {
                    goto pHTNS;
                }
                goto Q6VeT;
                eccFi:
            }
            goto bQs9y;
            bQs9y:
            aYCGE:
            goto EzkeT;
            zMiAg:
            cv9eS:
            goto Q2ynn;
            EzkeT:
            $newArray[] = $newItem;
            goto zMiAg;
            Q2ynn:
        }
        goto Zvgya;
        TyLwl:
        return $newArray;
        goto cVkUD;
        gfJzX:
        if ($type == "\154\x69\x73\x74") {
            goto FNciS;
        }
        goto Osrh2;
        QTWdu:
        OhiBw:
        goto eIi8Z;
        FmN_3:
        $newArray = array();
        goto HPd5Y;
        D7X0t:
        RiKnp:
        goto BhtPC;
        IgWGf:
        goto I2ngY;
        goto D7X0t;
        Zvgya:
        GeIKa:
        goto TyLwl;
        eDWVi:
        goto uiT_T;
        goto Cox1K;
        CYeeR:
        $columns = explode("\x2c", $this->columns);
        goto bxHzW;
        cVkUD:
        uiT_T:
        goto IgWGf;
        Cox1K:
        FNciS:
        goto FmN_3;
        Syzyc:
    }
    public function getByIdOrObj($objOrId)
    {
        goto I9aAB;
        lKqs5:
        goto uyHPa;
        goto EdIgS;
        bQdRn:
        return $this->selectById($objOrId);
        goto y090m;
        XPr14:
        Jf8cu:
        goto lKqs5;
        y090m:
        uyHPa:
        goto SoUGu;
        I9aAB:
        if (is_numeric($objOrId)) {
            goto c4ko6;
        }
        goto X4vX2;
        EdIgS:
        c4ko6:
        goto bQdRn;
        X4vX2:
        if (!is_array($objOrId)) {
            goto Jf8cu;
        }
        goto wV7va;
        wV7va:
        return $objOrId;
        goto XPr14;
        SoUGu:
    }
    /**
     * 根据id查询一条数据
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function selectById($id, $isValid = true)
    {
        goto erbgO;
        tBoew:
        return $result;
        goto uE_SJ;
        M3jcF:
        $sql .= "\40\141\156\x64\40\x69\163\x5f\x76\141\x6c\x69\144\75\x31";
        goto DXA_W;
        LtErA:
        echo "\75\x3d\x3d";
        goto xDew1;
        PjA7e:
        if ($isValid === "\141\x6c\154") {
            goto oPfUn;
        }
        goto hVg_Q;
        e0kge:
        SDf7F:
        goto M3jcF;
        DXA_W:
        fRXhX:
        goto n0pf3;
        gkohN:
        $result = pdo_fetch($sql);
        goto HIsLF;
        bznJt:
        rTeoJ:
        goto gkohN;
        HIsLF:
        $result = $this->deal_data_types($result);
        goto tBoew;
        xDew1:
        RuUKo:
        goto bznJt;
        GwH6U:
        oPfUn:
        goto LtErA;
        n0pf3:
        goto RuUKo;
        goto GwH6U;
        XgbKc:
        $sql = "\123\105\114\x45\x43\124\x20" . $this->columns . "\x20\106\122\x4f\115\40" . tablename($this->table_name) . "\40\127\110\x45\122\105\x20\151\144\x3d\47{$id}\47";
        goto Zn4AZ;
        erbgO:
        global $_W;
        goto Gir8D;
        Zn4AZ:
        if (!in_array("\151\163\x5f\x76\141\154\151\x64", $columns)) {
            goto rTeoJ;
        }
        goto PjA7e;
        hVg_Q:
        if ($isValid === true || $isValid === 1) {
            goto SDf7F;
        }
        goto oPNyO;
        oPNyO:
        $sql .= "\x20\x61\156\144\40\151\163\137\x76\141\x6c\x69\x64\x3d\x30";
        goto jJYdE;
        jJYdE:
        goto fRXhX;
        goto e0kge;
        Gir8D:
        $columns = explode("\54", $this->columns);
        goto XgbKc;
        uE_SJ:
    }
    /**
     * 根据id数组来查询数据
     * @param $ids
     * @return array
     * @throws Exception
     */
    public function selectByIds($ids)
    {
        goto LQ7yW;
        gp7Zo:
        throw new Exception("\345\x8f\x82\xe6\x95\260\xe4\270\272\xe7\251\272", 404);
        goto iL7YZ;
        If4N8:
        $in = "\x28" . $idsStr . "\x29";
        goto Et15G;
        mnEYJ:
        BIu8D:
        goto C42Jr;
        q9893:
        $ids = array_unique($ids);
        goto UCcQ0;
        FxsrV:
        throw new Exception("\xe6\237\245\350\257\xa2\xe5\x8f\x82\346\225\xb0\345\xbc\x82\xe5\270\xb8", 404);
        goto mnEYJ;
        UCcQ0:
        $idsStr = implode("\54", $ids);
        goto If4N8;
        iL7YZ:
        URbuv:
        goto q9893;
        PUtM8:
        return $data_list;
        goto R25vX;
        LQ7yW:
        if (is_array($ids)) {
            goto BIu8D;
        }
        goto FxsrV;
        C42Jr:
        if (!(sizeof($ids) <= 0)) {
            goto URbuv;
        }
        goto gp7Zo;
        Et15G:
        $data_list = pdo_fetchall("\123\x45\114\105\x43\124\40" . $this->columns . "\x20\x46\x52\117\115\40" . tablename($this->table_name) . "\x20\x57\110\105\x52\105\x20\151\x64\40\x69\x6e\40{$in}");
        goto PUtM8;
        R25vX:
    }
    /**
     * 批量查询
     * @param $column
     * @param $list
     * @param string $where
     * @return array
     * @throws Exception
     */
    public function selectAllIn($column, $list, $where = '')
    {
        goto ZGL6S;
        R0U7z:
        Jx23Z:
        goto y_23R;
        ieqSo:
        throw new Exception("\xe6\x9f\245\xe8\xaf\242\xe5\x8f\x82\346\225\260\xe5\xbc\x82\xe5\xb8\xb8", 404);
        goto R0U7z;
        mgwg8:
        throw new Exception("\345\217\x82\xe6\225\260\344\270\272\347\xa9\272", 404);
        goto dcO2d;
        Ll7jE:
        $this->log($list, "\x73\x65\154\x65\x63\x74\x20\141\154\x6c\x20\151\156\40\x73\161\154\x20\151\x73\40\x3a{$sql}");
        goto Vcbu1;
        dcO2d:
        nd39z:
        goto jC5tN;
        BlNYT:
        $i++;
        goto u38GK;
        mQLX0:
        AEaQf:
        goto BlNYT;
        q99me:
        $in .= "\x27" . $list[$i] . "\47";
        goto mRnl4;
        GkAf8:
        $in .= "\54\x27" . $list[$i] . "\47";
        goto a0yMu;
        XBduW:
        YpODA:
        goto ipkQo;
        RxoAD:
        ejNJs:
        goto q4p6_;
        BAb2x:
        $list = array_unique($list);
        goto bA_Bj;
        irqKJ:
        throw new Exception("\xe4\270\215\345\xad\x98\xe5\x9c\xa8\xe7\232\204\345\261\236\346\200\xa7", 404);
        goto Iiwdh;
        wc5Yy:
        LwoFn:
        goto BAb2x;
        u38GK:
        goto ejNJs;
        goto XBduW;
        aDJ64:
        if (in_array($column, $columnArr)) {
            goto t0_S1;
        }
        goto irqKJ;
        e3nI7:
        $column = "\151\144";
        goto wc5Yy;
        dDjUl:
        $i = 0;
        goto RxoAD;
        nLUOw:
        yqt5y:
        goto q99me;
        jC5tN:
        $columnArr = explode("\x2c", $this->columns);
        goto aDJ64;
        y_23R:
        if (!(sizeof($list) <= 0)) {
            goto nd39z;
        }
        goto mgwg8;
        Vcbu1:
        $data_list = pdo_fetchall($sql);
        goto KEuve;
        mRnl4:
        BEUWc:
        goto mQLX0;
        KEuve:
        return $data_list;
        goto V8XlX;
        bA_Bj:
        if (is_array($list)) {
            goto Jx23Z;
        }
        goto ieqSo;
        BOeG2:
        $sql = "\x53\105\x4c\x45\103\124\40" . $this->columns . "\x20\x46\122\117\115\40" . tablename($this->table_name) . "\x20\127\110\x45\122\105\40\165\156\151\x61\143\x69\144\75\x27{$_W["\x75\156\151\141\x63\151\144"]}\47\40\x61\156\144\40{$column}\x20\151\156\x20{$in}\x20\141\x6e\x64\x20\x31\x3d\61\x20{$where}";
        goto Ll7jE;
        q_08f:
        $in = "\50";
        goto dDjUl;
        ipkQo:
        $in .= "\51";
        goto BOeG2;
        Iiwdh:
        t0_S1:
        goto q_08f;
        ZGL6S:
        global $_W;
        goto QIkGO;
        a0yMu:
        goto BEUWc;
        goto nLUOw;
        QIkGO:
        if (!empty($column)) {
            goto LwoFn;
        }
        goto e3nI7;
        eYjyc:
        if ($i == 0) {
            goto yqt5y;
        }
        goto GkAf8;
        q4p6_:
        if (!($i < sizeof($list))) {
            goto YpODA;
        }
        goto eYjyc;
        V8XlX:
    }
    /**
     * get all data from database
     * @param string $where
     * @param boolean $uniacid 是否过滤uniacid筛选 默认是
     * @return array
     */
    public function selectAll($where = '', $uniacid = true)
    {
        goto f49Yz;
        t6yZL:
        $uniacid = $_W["\x75\x6e\151\141\143\151\144"];
        goto duGVi;
        fzJaf:
        return $data_list;
        goto GtiL6;
        tA4D6:
        $this->log(null, "\146\145\x74\x63\150\40" . sizeof($data_list) . "\40\x69\x74\x65\155");
        goto pC4Zn;
        gYDJT:
        TT9l6:
        goto sypjd;
        pC4Zn:
        $data_list = $this->deal_data_types($data_list, "\154\151\163\164");
        goto fzJaf;
        duGVi:
        $where = "\x20\101\x4e\x44\40\x75\x6e\x69\x61\143\151\144\75{$uniacid}\x20{$where}";
        goto gYDJT;
        kx9Eb:
        $data_list = pdo_fetchall($sql);
        goto tA4D6;
        f49Yz:
        global $_W;
        goto m7r0i;
        NmtvX:
        $this->log($sql, "\x73\x65\x6c\145\x63\x74\40\x61\x6c\x6c\40\x73\x71\x6c\x20\x69\x73\x20\72");
        goto kx9Eb;
        m7r0i:
        if (!$uniacid) {
            goto TT9l6;
        }
        goto t6yZL;
        sypjd:
        $sql = "\x53\x45\114\105\x43\x54\x20" . $this->columns . "\40\x46\122\x4f\x4d\40" . tablename($this->table_name) . "\x20\x57\x48\x45\122\105\40\61\x3d\61\40{$where}";
        goto NmtvX;
        GtiL6:
    }
    public function selectAllJoin($on, $joinService, $where = '')
    {
        goto Z35AK;
        bnTA5:
        $sql = "\x73\145\154\145\x63\164\x20{$joinColumns}\x20\x66\162\157\x6d\40" . tablename($this->table_name) . "\40{$this->table_name}\x20{$join}\x20\152\157\151\156\40" . tablename($joinService->table_name) . "\x20{$joinTableAlis}\x20\157\156\40{$this->table_name}\x2e\x75\x6e\151\141\143\x69\144\x3d{$joinTableAlis}\x2e\165\x6e\151\141\143\x69\x64\40\x61\156\144\40{$on}\x20\x77\150\x65\162\145\40\x31\x3d\x31\x20{$where}";
        goto MVenJ;
        U6W3M:
        $sql = '';
        goto Cn3Xu;
        b1AL1:
        unset($_GET["\x6a\157\151\x6e"]);
        goto jxSaX;
        jkqR4:
        if (!($joinService->table_name == $this->table_name)) {
            goto oXELm;
        }
        goto WuLmb;
        E4mkY:
        return $all;
        goto cm0Ui;
        NBDo5:
        $joinTableAlis = $joinService->table_name;
        goto jkqR4;
        pI1wQ:
        oXELm:
        goto bnTA5;
        XmNsb:
        $joinColumns = $this->makeJoinColumns($joinService);
        goto NBDo5;
        MVenJ:
        $all = pdo_fetchall($sql);
        goto b1AL1;
        jxSaX:
        $all = $this->deal_data_types($all, "\x6c\x69\x73\x74");
        goto E4mkY;
        Z35AK:
        global $_GPC;
        goto U6W3M;
        WuLmb:
        $joinTableAlis = $this->table_name . "\x32";
        goto pI1wQ;
        Cn3Xu:
        $join = $_GPC["\152\x6f\x69\x6e"];
        goto XmNsb;
        cm0Ui:
    }
    /**
     * 返回key=id的数据
     * @param $where
     * @return array
     */
    public function selectAllMap($where = '')
    {
        goto vXV3q;
        vXV3q:
        $all = $this->selectAll($where);
        goto PnfLR;
        q2PIA:
        return $newAll;
        goto ZBmNi;
        PnfLR:
        $newAll = array();
        goto l6L7y;
        kaO5T:
        azc7p:
        goto q2PIA;
        l6L7y:
        foreach ($all as $d) {
            $newAll[$d["\x69\144"]] = $d;
            ahHxx:
        }
        goto kaO5T;
        ZBmNi:
    }
    /**
     * 查询一条记录
     * @param string $where
     * @return bool
     */
    public function selectOne($where = '')
    {
        goto cHPji;
        mwxa5:
        $result = pdo_fetch($sql);
        goto wegau;
        iFx9l:
        $result = $this->deal_data_types($result);
        goto riIb5;
        sJGwp:
        $this->log($sql, "\x73\145\x6c\145\143\x74\117\x6e\145\40\x73\x71\154");
        goto mwxa5;
        RyZAb:
        $sql = "\x53\x45\114\105\x43\124\x20" . $this->columns . "\40\106\122\x4f\x4d\x20" . tablename($this->table_name) . "\40\127\x48\x45\x52\105\x20\165\x6e\151\x61\x63\x69\x64\75{$uniacid}\40\x41\x4e\x44\x20\61\75\61\x20{$where}";
        goto sJGwp;
        wegau:
        $this->log($result, "\163\x65\x6c\x65\x63\x74\x4f\x6e\145\40\x72\x65\x73\165\x6c\164");
        goto iFx9l;
        riIb5:
        return $result;
        goto Dh5aP;
        SRZe_:
        $uniacid = $_W["\x75\x6e\x69\141\143\151\x64"];
        goto RyZAb;
        cHPji:
        global $_W;
        goto SRZe_;
        Dh5aP:
    }
    public function selectOneJoin($where = '', $on, $joinService)
    {
        goto x7tyg;
        vZVrs:
        $sql = "\163\145\154\145\x63\x74\x20{$joinColumns}\40\x66\x72\157\x6d\40" . tablename($this->table_name) . "\x20{$this->table_name}\x20\x6a\157\151\x6e\40" . tablename($joinService->table_name) . "\40{$joinService->table_name}\x20\157\x6e\x20{$on}\x20\167\x68\145\162\145\x20\61\75\x31\x20{$where}";
        goto GyhOp;
        Jzih8:
        return $one;
        goto M7jkI;
        x7tyg:
        $joinColumns = $this->makeJoinColumns($joinService);
        goto vZVrs;
        D8K2i:
        $one = $this->deal_data_types($one);
        goto Jzih8;
        GyhOp:
        $one = pdo_fetch($sql);
        goto D8K2i;
        M7jkI:
    }
    /**
     * 条件查询 指定排序规则
     * @param string $where
     * @param string $order_by
     * @return array
     */
    public function selectAllOrderBy($where = '', $order_by = '')
    {
        goto Jwc0F;
        LpQzH:
        $data_list = $this->deal_data_types($data_list, "\x6c\151\x73\x74");
        goto weGx8;
        vc2U0:
        $data_list = pdo_fetchall("\123\105\114\105\x43\124\40" . $this->columns . "\40\106\x52\117\x4d\x20" . tablename($this->table_name) . "\x20\127\x48\x45\122\x45\x20\61\75\x31\x20\x41\116\104\40\x75\x6e\x69\x61\x63\x69\x64\x3d{$uniacid}\x20{$where}\x20\x4f\122\x44\105\122\40\102\x59\x20{$order_by}\x69\x64\40\x41\123\x43");
        goto LpQzH;
        Jwc0F:
        global $_W;
        goto Rrdq5;
        Rrdq5:
        $uniacid = $_W["\165\156\151\x61\143\151\144"];
        goto vc2U0;
        weGx8:
        return $data_list;
        goto ZE2MS;
        ZE2MS:
    }
    /**
     * 根据ID删除
     */
    public function deleteById($id)
    {
        goto dZ3vZ;
        gjpsw:
        a1nn1:
        goto HG0NL;
        HG0NL:
        pdo_delete($this->table_name, array("\151\x64" => $id));
        goto ytQsR;
        KJtK7:
        if (!empty($item)) {
            goto a1nn1;
        }
        goto wKCCg;
        dZ3vZ:
        $item = $this->selectById($id);
        goto KJtK7;
        har6a:
        return $item;
        goto QUy29;
        ytQsR:
        $this->afterDeleteById($item);
        goto har6a;
        wKCCg:
        throw new Exception("\346\x97\240\xe6\xb3\x95\345\210\240\351\231\xa4\xef\274\214\345\x9b\240\xe4\270\272\xe8\xbf\x99\346\x9d\xa1\xe6\225\260\xe6\215\xae\xe4\xb8\215\345\255\x98\xe5\x9c\250", 402);
        goto gjpsw;
        QUy29:
    }
    protected function afterDeleteById($item)
    {
    }
    public function softDeleteById($id)
    {
        goto jFeeC;
        d6BWA:
        throw new Exception("\xe6\x97\xa0\xe6\xb3\225\345\210\xa0\xe9\x99\244\xef\274\x8c\345\233\240\344\270\xba\350\xbf\x99\xe6\x9d\241\xe6\225\xb0\346\x8d\256\344\270\215\345\255\x98\345\234\xa8", 402);
        goto d21k3;
        r3rXK:
        pdo_update($this->table_name, array("\x69\163\x5f\166\x61\x6c\x69\x64" => 0), array("\151\144" => $id));
        goto O5gLi;
        jFeeC:
        $item = $this->selectById($id);
        goto lx8Vs;
        lx8Vs:
        if (!empty($item)) {
            goto kDxF7;
        }
        goto d6BWA;
        d21k3:
        kDxF7:
        goto r3rXK;
        O5gLi:
    }
    public function deleteByWhere($where)
    {
        pdo_query("\x64\145\154\145\164\145\40\146\x72\157\x6d\x20" . tablename($this->table_name) . "\40\x77\150\145\162\x65\40\61\75\61\x20{$where}");
    }
    public function deleteByIds($ids)
    {
        goto GG7_O;
        B4dGB:
        pdo_query($sql);
        goto g0456;
        MavnA:
        $in = "\x28" . $idsStr . "\51";
        goto SrVYM;
        SrVYM:
        $sql = "\144\x65\x6c\145\x74\x65\x20\x66\x72\157\155\40" . tablename($this->table_name) . "\x20\167\x68\x65\162\145\40\151\144\40\151\x6e\40{$in}";
        goto B4dGB;
        GG7_O:
        $ids = array_unique($ids);
        goto YER4Y;
        YER4Y:
        $idsStr = implode("\54", $ids);
        goto MavnA;
        g0456:
    }
    /**
     * 插入一条数据
     * @param $param
     * @return bool
     * @throws Exception
     */
    public function insertData($param)
    {
        goto G5qDX;
        G5qDX:
        pdo_insert($this->table_name, $param);
        goto fNyMm;
        fNyMm:
        $param["\151\x64"] = pdo_insertid();
        goto B_UX5;
        B_UX5:
        return $this->selectById($param["\x69\144"]);
        goto Julo8;
        Julo8:
    }
    /**
     * 更新一条数据
     * @param $param
     * @return bool
     * @throws Exception
     */
    public function updateData($param)
    {
        goto DmzWG;
        JHVNu:
        return $this->selectById($id);
        goto Dnvr8;
        iSPT5:
        $this->log(array($param, $data), "\346\233\264\346\226\xb0\xe5\xa4\261\xe8\264\xa5\357\xbc\x8c\xe6\x95\260\346\215\xae\xe4\xb8\x8d\xe5\xad\230\xe5\x9c\xa8");
        goto j2aPV;
        Xo1sh:
        if (!empty($data)) {
            goto ig_K9;
        }
        goto iSPT5;
        vOe_n:
        ig_K9:
        goto iJ1zJ;
        OOgAO:
        $data = $this->selectById($id);
        goto Xo1sh;
        DmzWG:
        $id = $param["\151\x64"];
        goto OOgAO;
        iJ1zJ:
        pdo_update($this->table_name, $param, array("\151\144" => $id));
        goto JHVNu;
        j2aPV:
        throw new Exception("\xe6\x9b\xb4\xe6\226\xb0\xe5\xa4\261\xe8\264\245\x2c\xe6\x95\xb0\xe6\215\xae\xe4\xb8\x8d\xe5\xad\230\345\234\xa8", 403);
        goto vOe_n;
        Dnvr8:
    }
    public function updateColumn($column_name, $value, $id)
    {
        goto bYOen;
        enshv:
        dYLyg:
        goto cO7yp;
        EPTl5:
        throw new Exception("\xe8\xa1\xa8\xe4\xb8\215\xe5\255\x98\xe5\234\250\x5b" . $column_name . "\135\345\261\236\346\x80\xa7", 405);
        goto l1Enf;
        l1Enf:
        goto JRsoW;
        goto enshv;
        CS1Ad:
        JRsoW:
        goto XiPaU;
        bYOen:
        if (pdo_fieldexists($this->table_name, $column_name)) {
            goto dYLyg;
        }
        goto EPTl5;
        cO7yp:
        pdo_update($this->table_name, array($column_name => $value), array("\151\144" => $id));
        goto CS1Ad;
        XiPaU:
    }
    /**
     * 根据条件更新某个字段
     * @param $column_name
     * @param $value
     * @param string $where
     * @throws Exception
     */
    public function updateColumnByWhere($column_name, $value, $where = '')
    {
        goto Dgrll;
        xb80E:
        throw new Exception("\xe8\xa1\250\xe4\270\215\345\255\x98\xe5\x9c\250\133" . $column_name . "\x5d\345\xb1\x9e\346\200\xa7", 405);
        goto RW6K2;
        LgibJ:
        pdo_query($sql);
        goto O1Ul7;
        bRuee:
        $setSql = "{$column_name}\x3d\x27{$value}\47";
        goto USuXk;
        hmQgy:
        K5aR6:
        goto v1mDz;
        hhr1_:
        $this->log(null, "\xe6\xa0\271\346\x8d\256\346\x9d\xa1\xe4\xbb\xb6\xe6\x9b\xb4\346\226\260\346\x9f\x90\xe4\270\252\xe5\xad\227\346\xae\xb5\xef\274\x8c\x20\163\x71\x6c\40\151\163\x20\x3a\x20{$sql}");
        goto LgibJ;
        Dgrll:
        global $_W;
        goto bRuee;
        CgfP8:
        if (pdo_fieldexists($this->table_name, $column_name)) {
            goto K5aR6;
        }
        goto xb80E;
        ztLdy:
        $setSql = "{$column_name}\x3d\x6e\165\154\154";
        goto Ckz2c;
        USuXk:
        if (!($value == NULL || $value == null)) {
            goto XCWSo;
        }
        goto ztLdy;
        O1Ul7:
        NCyhK:
        goto T1SSk;
        RW6K2:
        goto NCyhK;
        goto hmQgy;
        v1mDz:
        $sql = "\125\x50\x44\101\x54\x45\40" . tablename($this->table_name) . "\x20\x53\x45\124\x20{$setSql}\40\x57\110\105\122\x45\x20\x75\156\151\141\x63\151\x64\75{$_W["\x75\x6e\x69\141\x63\x69\x64"]}\x20\x41\x4e\x44\40\x31\x3d\x31\x20{$where}";
        goto hhr1_;
        Ckz2c:
        XCWSo:
        goto CgfP8;
        T1SSk:
    }
    /**
     * 给某个int类型的字段 增长值
     * @param $column_name
     * @param $add_count
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function columnAddCount($column_name, $add_count, $id)
    {
        goto djbjn;
        QeJHR:
        $data = $this->selectById($id);
        goto d0T72;
        tgGh0:
        goto Zc51K;
        goto SuwX7;
        DiF28:
        I_n9x:
        goto LQpOs;
        SuwX7:
        AyFh_:
        goto QeJHR;
        d0T72:
        if (!empty($data)) {
            goto I_n9x;
        }
        goto zgSvm;
        LQpOs:
        $data[$column_name] = $data[$column_name] + $add_count;
        goto S8PMn;
        djbjn:
        if (pdo_fieldexists($this->table_name, $column_name)) {
            goto AyFh_;
        }
        goto pstMS;
        S8PMn:
        $new_data = $this->updateData($data);
        goto vx3tr;
        vx3tr:
        return $new_data;
        goto x7iEf;
        pstMS:
        throw new Exception("\xe8\xa1\250\xe4\270\215\xe5\255\x98\xe5\x9c\xa8\x5b" . $column_name . "\x5d\xe5\261\236\xe6\x80\xa7", 405);
        goto tgGh0;
        x7iEf:
        Zc51K:
        goto J4SJC;
        zgSvm:
        throw new Exception("\xe6\x9b\264\346\226\xb0\xe5\244\261\350\xb4\xa5\54\346\225\260\xe6\215\256\344\270\215\xe5\xad\230\345\x9c\xa8", 403);
        goto DiF28;
        J4SJC:
    }
    /**
     * 给某个字段减少数量
     * @param $column_name
     * @param $reduce_count
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function columnReduceCount($column_name, $reduce_count, $id)
    {
        goto tlN7Z;
        fL_ZC:
        $data[$column_name] = $data[$column_name] - $reduce_count;
        goto b2Kmw;
        LpHzM:
        return $new_data;
        goto bDS5d;
        D82Bk:
        $data = $this->selectById($id);
        goto phKAk;
        Xtl1E:
        goto vU5rV;
        goto Eh9fR;
        tlN7Z:
        if (pdo_fieldexists($this->table_name, $column_name)) {
            goto aW0L4;
        }
        goto cTjwh;
        RHaHq:
        osC3R:
        goto fL_ZC;
        bDS5d:
        vU5rV:
        goto e_6rg;
        SMEQw:
        throw new Exception("\xe6\233\264\346\226\260\345\244\xb1\350\xb4\245\54\346\x95\260\346\215\xae\344\270\215\xe5\255\230\345\234\250", 403);
        goto RHaHq;
        b2Kmw:
        $new_data = $this->updateData($data);
        goto LpHzM;
        phKAk:
        if (!empty($data)) {
            goto osC3R;
        }
        goto SMEQw;
        cTjwh:
        throw new Exception("\350\241\xa8\344\xb8\x8d\345\xad\230\345\x9c\xa8\133" . $column_name . "\x5d\345\261\x9e\xe6\200\xa7", 405);
        goto Xtl1E;
        Eh9fR:
        aW0L4:
        goto D82Bk;
        e_6rg:
    }
    /**
     * 更新或者插入一条数据 当传入的参数中存在id的话则为更新 如果没有id则为插入
     */
    public function insertOrUpdate($param)
    {
        goto lhsEV;
        HLjJh:
        v6FU2:
        goto iSmrc;
        TZAIm:
        return $this->insertData($param);
        goto bqxAD;
        lhsEV:
        if ($param["\151\x64"]) {
            goto TaqAK;
        }
        goto TZAIm;
        fmXZg:
        return $this->updateData($param);
        goto HLjJh;
        bqxAD:
        goto v6FU2;
        goto c1oIy;
        c1oIy:
        TaqAK:
        goto fmXZg;
        iSmrc:
    }
    public function count($where = '', $uniacid = true)
    {
        goto gCqij;
        x6c55:
        $count = intval($count);
        goto ssKD_;
        EUVQS:
        PeruQ:
        goto QQYph;
        gCqij:
        global $_W;
        goto okez8;
        ssKD_:
        return $count;
        goto eu0ln;
        P8I7F:
        $uniacid = $_W["\165\x6e\x69\x61\143\x69\x64"];
        goto b_gMg;
        WD7oQ:
        $this->log($sql, "\xe6\237\xa5\350\257\xa2\x63\x6f\x75\156\164\40\123\x71\x6c\350\257\xad\xe5\x8f\245\346\x98\xaf");
        goto JwurG;
        okez8:
        if (!$uniacid) {
            goto PeruQ;
        }
        goto P8I7F;
        QQYph:
        $sql = "\x53\x45\x4c\x45\103\x54\40\103\x4f\125\x4e\x54\x28\61\x29\40\106\122\x4f\x4d\40" . tablename($this->table_name) . "\40\127\110\x45\122\105\40\61\x3d\x31\x20{$where}";
        goto WD7oQ;
        JwurG:
        $count = pdo_fetchcolumn($sql);
        goto x6c55;
        b_gMg:
        $where = "\40\x61\156\144\x20\165\x6e\x69\x61\x63\151\x64\x3d{$uniacid}\40{$where}";
        goto EUVQS;
        eu0ln:
    }
    public function selectPageAdmin($where = '', $page_index = '', $page_size = '', $uniacid = true)
    {
        $this->checkRegister();
        return $this->selectPage($where, $page_index, $page_size, $uniacid);
    }
    public function selectPageAdminJoin($where = '', $on, $joinService, $page_index = '', $page_size = '', $uniacid = true)
    {
        $this->checkRegister();
        return $this->selectPageJoin($where, $on, $joinService, $page_index, $page_size, $uniacid);
    }
    /**
     * select the data page
     */
    public function selectPage($where = '', $page_index = '', $page_size = '', $uniacid = true)
    {
        goto irnEh;
        wyZce:
        A7UXX:
        goto o8grd;
        YAZE8:
        if (!empty($page_index)) {
            goto A7UXX;
        }
        goto AojkS;
        o8grd:
        if (!empty($page_size)) {
            goto sf8Vg;
        }
        goto I1oEc;
        AojkS:
        $page_index = max(1, intval($_GPC["\160\x61\x67\145"]));
        goto wyZce;
        wPB95:
        $data = $this->selectAll($where, $uniacid);
        goto mPg0E;
        VTI3_:
        sf8Vg:
        goto NsuT4;
        q4o1I:
        return array("\144\x61\x74\141" => $data, "\x63\x6f\165\x6e\x74" => intval($count), "\x70\141\x67\x65\x72" => $pager, "\x70\x61\147\x65\137\151\x6e\144\x65\170" => $page_index, "\160\x61\147\145\137\163\x69\x7a\145" => $page_size);
        goto qnaEc;
        rqVBd:
        $pager = pagination($count, $page_index, $page_size);
        goto q4o1I;
        NsuT4:
        $count_where = $where;
        goto cAoEx;
        cAoEx:
        $where = $where . "\40\114\x49\115\x49\124\x20" . ($page_index - 1) * $page_size . "\54" . $page_size;
        goto wPB95;
        irnEh:
        global $_W, $_GPC;
        goto YAZE8;
        I1oEc:
        $page_size = is_null($_GPC["\163\x69\x7a\x65"]) || $_GPC["\x73\x69\172\x65"] <= 0 ? 20 : $_GPC["\163\x69\x7a\x65"];
        goto VTI3_;
        mPg0E:
        $count = $this->count($count_where, $uniacid);
        goto rqVBd;
        qnaEc:
    }
    public function selectPageJoin($where = '', $on = '', $joinService, $page_index = '', $page_size = '', $uniacid = true)
    {
        goto yjP8B;
        YthZu:
        KGLk1:
        goto CG1JD;
        n19LM:
        $this->log(null, "\123\x45\114\105\x43\x54\x20\x53\121\x4c\x20\111\123\357\274\232" . $sql);
        goto NNkdP;
        m4awi:
        $join = $_GPC["\152\x6f\151\156"];
        goto t5P43;
        BhuQe:
        bO01l:
        goto VWrzk;
        lBtbe:
        $this->log(null, "\103\117\125\116\x54\40\x53\121\x4c\40\x49\x53\357\xbc\232" . $countSql);
        goto izE_8;
        odwdY:
        if (!$uniacid) {
            goto Oao9E;
        }
        goto jHqV3;
        kXjTv:
        $joinColumns = $this->makeJoinColumns($joinService);
        goto tjbVZ;
        yjP8B:
        global $_W, $_GPC;
        goto m4awi;
        GwA3k:
        $where = $where . "\40\114\x49\115\x49\124\40" . ($page_index - 1) * $page_size . "\54" . $page_size;
        goto kXjTv;
        UEQ3l:
        QAZYN:
        goto odwdY;
        O7CF_:
        $page_index = max(1, intval($_GPC["\160\x61\147\x65"]));
        goto P4cl9;
        NNkdP:
        $countSql = "\x53\x45\x4c\105\103\x54\40\x43\x4f\x55\116\124\x28\x31\51\x20\x46\122\117\115\x20" . tablename($this->table_name) . "\x20{$this->table_name}\40{$join}\x20\x6a\157\151\156\40" . tablename($joinService->table_name) . "\x20{$joinTableAlis}\x20\x6f\156\40{$this->table_name}\56\165\x6e\151\141\143\x69\144\75{$joinTableAlis}\56\165\x6e\x69\141\143\151\144\40\141\x6e\x64\40{$on}\40\x57\x48\105\x52\x45\x20\x31\x3d\x31\40{$count_where}";
        goto lBtbe;
        blVcx:
        $joinTableAlis = $this->table_name . "\x32";
        goto YthZu;
        ZGTTt:
        unset($_GPC["\152\157\151\x6e"]);
        goto xSfMe;
        P4cl9:
        tQPJz:
        goto kUWXN;
        kUWXN:
        if (!empty($page_size)) {
            goto QAZYN;
        }
        goto pZUAx;
        xSfMe:
        return array("\x64\141\x74\141" => $data, "\x63\x6f\x75\x6e\x74" => $count, "\160\141\x67\x65\162" => $pager, "\160\141\x67\x65\137\x69\x6e\x64\x65\x78" => $page_index, "\x70\141\147\145\x5f\x73\x69\172\145" => $page_size);
        goto FWcIZ;
        VWrzk:
        if (!empty($page_index)) {
            goto tQPJz;
        }
        goto O7CF_;
        tjbVZ:
        $joinTableAlis = $joinService->table_name;
        goto rFBbc;
        lntoE:
        $join = $_GPC["\152\x6f\151\156"];
        goto BhuQe;
        pSiog:
        $where = "\40\101\116\104\x20{$this->table_name}\56\165\156\x69\x61\x63\151\144\75{$uniacid}\x20{$where}";
        goto Z9rXQ;
        xbp2I:
        $count = pdo_fetchcolumn($countSql);
        goto KS_D3;
        pZUAx:
        $page_size = is_null($_GPC["\x73\151\x7a\x65"]) || $_GPC["\163\151\x7a\145"] <= 0 ? 20 : $_GPC["\163\151\x7a\145"];
        goto UEQ3l;
        rFBbc:
        if (!($joinService->table_name == $this->table_name)) {
            goto KGLk1;
        }
        goto blVcx;
        Z9rXQ:
        Oao9E:
        goto VCAP3;
        CG1JD:
        $sql = "\163\x65\154\x65\143\164\40{$joinColumns}\40\146\162\x6f\x6d\40" . tablename($this->table_name) . "\40{$this->table_name}\40{$join}\40\x6a\x6f\x69\156\40" . tablename($joinService->table_name) . "\40{$joinTableAlis}\x20\x6f\156\x20{$this->table_name}\56\x75\156\151\x61\x63\x69\144\x3d{$joinTableAlis}\56\x75\156\x69\141\143\x69\144\40\x61\x6e\x64\40{$on}\x20\167\150\145\162\145\x20\61\x3d\x31\x20{$where}";
        goto n19LM;
        jHqV3:
        $uniacid = $_W["\x75\156\x69\141\x63\x69\144"];
        goto pSiog;
        izE_8:
        $data = pdo_fetchall($sql);
        goto xbp2I;
        t5P43:
        if (!($join == "\154\x65\x66\164" || $join == "\x72\x69\147\x68\164")) {
            goto bO01l;
        }
        goto lntoE;
        VCAP3:
        $count_where = $where;
        goto GwA3k;
        KS_D3:
        $pager = pagination($count, $page_index, $page_size);
        goto ZGTTt;
        FWcIZ:
    }
    public function joinColumns($alias = '')
    {
        goto hhV6T;
        NIanF:
        puFeC:
        goto sv2AA;
        C6kQr:
        $alias = $this->table_name;
        goto NIanF;
        uad1g:
        foreach ($columns as $field) {
            goto Yyo9f;
            lgp22:
            ZmJvT:
            goto xssLm;
            xssLm:
            azeR7:
            goto R7AaC;
            GRsKY:
            $joinColumnsArr[] = $alias . "\x2e" . $field;
            goto lgp22;
            Yyo9f:
            if (empty($field)) {
                goto ZmJvT;
            }
            goto GRsKY;
            R7AaC:
        }
        goto L3_jb;
        Wa9rd:
        $joinColumnsArr = array();
        goto uad1g;
        L3_jb:
        QncNs:
        goto o8QPd;
        hhV6T:
        if (!($alias == '')) {
            goto puFeC;
        }
        goto C6kQr;
        o8QPd:
        return implode("\54", $joinColumnsArr);
        goto jculn;
        sv2AA:
        $columns = explode("\x2c", $this->columns);
        goto Wa9rd;
        jculn:
    }
    private function makeJoinColumns($joinService)
    {
        goto xn2Nl;
        xn2Nl:
        $columns = explode("\54", $this->columns);
        goto c9NEC;
        m45m6:
        if (!($joinTable == $this->table_name)) {
            goto PAAN9;
        }
        goto HSCOf;
        HSCOf:
        $joinTable = $this->table_name . "\62";
        goto ExdLi;
        cGfKH:
        return implode("\54", $joinColumnsArr);
        goto xp0wT;
        uy9oK:
        $joinTable = $joinService->table_name;
        goto m45m6;
        f3pdD:
        AXRuQ:
        goto USk2y;
        sZ918:
        hOUsW:
        goto cGfKH;
        GAVUU:
        foreach ($joinColumns as $field) {
            goto xtdTD;
            rrOgh:
            r1BQl:
            goto Mh1my;
            xtdTD:
            if (empty($field)) {
                goto UY2SW;
            }
            goto YahYz;
            a6SxZ:
            ODDkz:
            goto Y37Fs;
            U44ni:
            QEiAd:
            goto TmNjZ;
            Mh1my:
            $joinColumnsArr[] = "{$joinTable}\x2e{$field}";
            goto U44ni;
            YahYz:
            if (!in_array($field, $columns)) {
                goto r1BQl;
            }
            goto Ld4LF;
            Ld4LF:
            $joinColumnsArr[] = "{$joinTable}\56{$field}\x20\x20\x61\163\x20{$joinTable}\x5f{$field}";
            goto v5cjm;
            TmNjZ:
            UY2SW:
            goto a6SxZ;
            v5cjm:
            goto QEiAd;
            goto rrOgh;
            Y37Fs:
        }
        goto sZ918;
        c9NEC:
        $joinColumnsArr = array();
        goto MkTeZ;
        USk2y:
        $joinColumns = explode("\x2c", $joinService->columns);
        goto uy9oK;
        MkTeZ:
        foreach ($columns as $field) {
            goto rNOOJ;
            CGw99:
            yM26M:
            goto ZiUoB;
            Blj72:
            I0rQI:
            goto CGw99;
            tj5MM:
            $joinColumnsArr[] = $this->table_name . "\56" . $field;
            goto Blj72;
            rNOOJ:
            if (empty($field)) {
                goto I0rQI;
            }
            goto tj5MM;
            ZiUoB:
        }
        goto f3pdD;
        ExdLi:
        PAAN9:
        goto GAVUU;
        xp0wT:
    }
    public function rankOne($id, $where = '', $referToColumn = '')
    {
        goto KS0VM;
        dpFND:
        $result = pdo_fetch("\163\145\154\145\x63\x74\x20{$rColumnsString}\x2c\162\56\x72\x61\x6e\x6b\40\x66\x72\157\x6d\x20\50\x73\x65\x6c\145\143\164\40{$aColumnsString}\x2c\50\100\162\157\167\116\165\155\x3a\75\100\162\x6f\x77\x4e\165\x6d\x2b\61\x29\x20\x61\163\40\x72\x61\156\x6b\x20\146\162\157\x6d\x20" . tablename($this->table_name) . "\x20\141\54\50\x73\145\x6c\145\x63\x74\x20\50\x40\x72\x6f\167\116\x75\155\x20\x3a\x3d\x30\x29\51\40\x62\x20\167\x68\145\162\145\x20\61\x3d\x31\40{$where}\51\x20\141\163\40\162\x20\x77\x68\145\x72\145\40{$baseWhere}\x20\101\116\104\40\x31\75\61\x20");
        goto DzfYN;
        fPw5p:
        WJg7k:
        goto GaViq;
        Vqjej:
        $rColumnsArr = array();
        goto Ys8O_;
        PI3ee:
        if (empty($referToColumn)) {
            goto WJg7k;
        }
        goto Q_JP1;
        DzfYN:
        return $result["\162\141\x6e\153"];
        goto Zw_2H;
        So64s:
        $rColumnsString = implode("\x2c", $rColumnsArr);
        goto qSm1M;
        KS0VM:
        $baseWhere = "\162\x2e\151\x64\x3d{$id}";
        goto PI3ee;
        Q_JP1:
        $baseWhere = "\x72\x2e{$referToColumn}\x3d{$id}";
        goto fPw5p;
        qSm1M:
        $aColumnsString = implode("\54", $aColumnsArr);
        goto dpFND;
        loxt6:
        YWkmz:
        goto So64s;
        Ys8O_:
        $aColumnsArr = array();
        goto ZYk4V;
        GaViq:
        $columnsArr = explode("\x2c", $this->columns);
        goto Vqjej;
        ZYk4V:
        foreach ($columnsArr as $f) {
            goto hrPxa;
            BAe7o:
            $aColumnsArr[] = "\141\56" . $f;
            goto z3LcR;
            z3LcR:
            eygMB:
            goto FIcZs;
            hrPxa:
            $rColumnsArr[] = "\162\56" . $f;
            goto BAe7o;
            FIcZs:
        }
        goto loxt6;
        Zw_2H:
    }
    public function selectPageOrderByAdmin($where = '', $order_by = '', $page_index = '', $page_size = '', $uniacid = true)
    {
        $this->checkRegister();
        return $this->selectPageOrderBy($where, $order_by, $page_index, $page_size, $uniacid);
    }
    public function selectPageOrderByJoinAdmin($where = '', $order_by = '', $on = '', $joinService = '', $page_index = '', $page_size = '', $uniacid = true)
    {
        $this->checkRegister();
        return $this->selectPageOrderByJoin($where, $order_by, $on, $joinService, $page_index, $page_size, $uniacid);
    }
    /**
     * selectPage order by param
     * orderby create_time Desc,uniacid ASC,
     */
    public function selectPageOrderBy($where = '', $order_by = '', $page_index = '', $page_size = '', $uniacid = true)
    {
        goto Xvya6;
        yz12X:
        BFJcf:
        goto YdZyo;
        YdZyo:
        $count_where = $where;
        goto OLm4H;
        G1EIZ:
        $page_index = max(1, intval($_GPC["\x70\141\x67\x65"]));
        goto b3Vob;
        X0x2i:
        if (!(substr($order_by, -1) == "\54")) {
            goto jBk0M;
        }
        goto UHUgS;
        b3Vob:
        nAusi:
        goto OhRu2;
        vAXGq:
        z7ReH:
        goto kgmaE;
        q7M33:
        if (empty($order_by)) {
            goto z7ReH;
        }
        goto X0x2i;
        KMful:
        jBk0M:
        goto vAXGq;
        c_fJV:
        return array("\x64\x61\x74\141" => $data, "\x63\x6f\x75\156\164" => intval($count), "\x70\141\147\x65\x72" => $pager, "\160\141\x67\x65\137\151\156\x64\x65\170" => intval($page_index), "\x70\x61\147\145\137\163\151\172\145" => intval($page_size));
        goto h52SD;
        UHUgS:
        $order_by = substr($order_by, 0, strlen($order_by) - 1);
        goto KMful;
        kgmaE:
        if (!empty($page_index)) {
            goto nAusi;
        }
        goto G1EIZ;
        bIQGQ:
        $page_size = is_null($_GPC["\163\x69\172\145"]) || $_GPC["\163\151\x7a\145"] <= 0 ? 20 : $_GPC["\163\x69\x7a\x65"];
        goto yz12X;
        jl1xy:
        $pager = pagination($count, $page_index, $page_size);
        goto c_fJV;
        OhRu2:
        if (!empty($page_size)) {
            goto BFJcf;
        }
        goto bIQGQ;
        vJ5NL:
        $count = $this->count($count_where, $uniacid);
        goto jl1xy;
        OLm4H:
        $where = $where . "\40\117\x52\x44\x45\x52\x20\102\131\x20{$order_by}\40\114\111\115\111\124\40" . ($page_index - 1) * $page_size . "\54" . $page_size;
        goto FfPOL;
        FfPOL:
        $data = $this->selectAll($where, $uniacid);
        goto vJ5NL;
        Xvya6:
        global $_W, $_GPC;
        goto q7M33;
        h52SD:
    }
    public function selectPageOrderByJoin($where = '', $order_by = '', $on = '', $joinService, $page_index = '', $page_size = '', $uniacid = true)
    {
        goto aJU6P;
        HA0To:
        $page_size = is_null($_GPC["\x73\151\x7a\145"]) || $_GPC["\163\x69\172\x65"] <= 0 ? 20 : $_GPC["\163\x69\x7a\145"];
        goto zQQVd;
        NwxfO:
        $join = $_GPC["\x6a\x6f\x69\156"];
        goto npNCs;
        jI2uS:
        if (!(substr($order_by, -1) == "\54")) {
            goto KmJNH;
        }
        goto sKqVX;
        bUKPa:
        if (!empty($page_size)) {
            goto jpstm;
        }
        goto HA0To;
        dmYl7:
        uXWqE:
        goto uBg0J;
        iFPsW:
        $where = $where . "\x20\117\x52\104\105\x52\40\x42\131\x20{$order_by}\40\x4c\x49\x4d\111\124\x20" . ($page_index - 1) * $page_size . "\x2c" . $page_size;
        goto qloK6;
        VZUxY:
        $uniacid = $_W["\165\x6e\x69\x61\x63\x69\x64"];
        goto miGxa;
        WZYPh:
        KmJNH:
        goto ogemc;
        miGxa:
        $where = "\x20\x41\116\x44\40{$this->table_name}\x2e\x75\x6e\151\x61\x63\151\x64\x3d{$uniacid}\40{$where}";
        goto th0F3;
        zFSaV:
        if (!empty($page_index)) {
            goto a0Dif;
        }
        goto jCdaK;
        rnCX_:
        if (!($join == "\x6c\x65\146\x74" || $join == "\162\x69\x67\150\164")) {
            goto giJgg;
        }
        goto NwxfO;
        KXYHx:
        $count = pdo_fetchcolumn($countSql);
        goto C0dhD;
        h5pDs:
        if (!($joinService->table_name == $this->table_name)) {
            goto uXWqE;
        }
        goto c849G;
        mVGJC:
        $joinColumns = $this->makeJoinColumns($joinService);
        goto cY1pX;
        onzEX:
        $pager = pagination($count, $page_index, $page_size);
        goto A7DvZ;
        G1lSG:
        $data = pdo_fetchall($sql);
        goto bf3Lo;
        jCdaK:
        $page_index = max(1, intval($_GPC["\160\x61\147\x65"]));
        goto BQSdL;
        xirRy:
        $countSql = "\123\x45\114\105\103\124\x20\x43\x4f\125\116\x54\50\x31\51\40\x46\122\117\x4d\x20" . tablename($this->table_name) . "\40{$this->table_name}\x20{$join}\x20\x6a\x6f\x69\x6e\40" . tablename($joinService->table_name) . "\40{$joinTableAlis}\x20\157\156\x20{$on}\x20\x57\110\105\122\105\40\x31\x3d\61\40{$count_where}";
        goto Kl2kI;
        Kl2kI:
        $this->log(null, "\103\157\x75\156\164\40\346\x9f\245\350\257\xa2\xe7\x9a\x84\x73\x71\154\350\257\xad\xe5\x8f\xa5\346\230\257\72{$sql}");
        goto qAGjr;
        c849G:
        $joinTableAlis = $this->table_name . "\x32";
        goto dmYl7;
        qloK6:
        $joinColumns = $this->makeJoinColumns($joinService);
        goto yBQbi;
        zQQVd:
        jpstm:
        goto WN2xb;
        ogemc:
        GTYcM:
        goto zFSaV;
        WPxoE:
        return array("\x64\141\164\141" => $data, "\x63\x6f\x75\x6e\x74" => intval($count), "\160\x61\147\x65\x72" => $pager, "\160\141\x67\145\x5f\151\156\144\x65\170" => intval($page_index), "\160\141\147\x65\137\163\x69\172\145" => intval($page_size));
        goto hLAZk;
        sKqVX:
        $order_by = substr($order_by, 0, strlen($order_by) - 1);
        goto WZYPh;
        BQSdL:
        a0Dif:
        goto bUKPa;
        WN2xb:
        if (!$uniacid) {
            goto f9uO6;
        }
        goto VZUxY;
        P44_K:
        $this->log(null, "\123\x65\x6c\145\x63\x74\40\xe6\x9f\245\xe8\xaf\xa2\347\232\204\163\x71\x6c\350\257\255\345\x8f\245\346\230\xaf\72{$sql}");
        goto xirRy;
        C0dhD:
        $this->log(null, "\40\143\157\165\x6e\x74\40\xe7\xbb\223\346\236\234{$count}\40\xe9\x9c\200\350\xa6\201\347\232\x84\346\227\266\351\x97\xb4\xe6\x98\257\x3a" . (time() - $startCount));
        goto onzEX;
        bf3Lo:
        $this->log(null, "\x20\146\145\143\150\x61\x6c\154" . sizeof($data) . "\40\351\x9c\x80\350\xa6\201\347\232\204\xe6\227\266\xe9\227\264\xe6\x98\257\x3a" . (time() - $startAll));
        goto ubvl9;
        th0F3:
        f9uO6:
        goto mVGJC;
        D8N74:
        $join = $_GPC["\152\x6f\x69\156"];
        goto rnCX_;
        qAGjr:
        $startAll = time();
        goto G1lSG;
        uBg0J:
        $count_where = $where;
        goto iFPsW;
        yBQbi:
        $sql = "\163\x65\154\145\x63\164\40{$joinColumns}\40\x66\x72\157\155\40" . tablename($this->table_name) . "\x20{$this->table_name}\x20{$join}\40\x6a\x6f\151\x6e\x20" . tablename($joinService->table_name) . "\40{$joinTableAlis}\40\157\x6e\40{$on}\40\167\x68\x65\162\145\x20\x31\x3d\x31\40{$where}";
        goto P44_K;
        npNCs:
        giJgg:
        goto p6Sgs;
        aJU6P:
        global $_W, $_GPC;
        goto D8N74;
        A7DvZ:
        unset($_GPC["\152\157\151\156"]);
        goto WPxoE;
        ubvl9:
        $startCount = time();
        goto KXYHx;
        p6Sgs:
        if (empty($order_by)) {
            goto GTYcM;
        }
        goto jI2uS;
        cY1pX:
        $joinTableAlis = $joinService->table_name;
        goto h5pDs;
        hLAZk:
    }
    public function loopSetColumnObjToArray($list, $column, $objName = "\157\142\152")
    {
        goto G35dm;
        tMLGs:
        $targets = $this->selectByIds($columnIds);
        goto UNo34;
        Hmaok:
        if (!empty($columnIds)) {
            goto c5HSM;
        }
        goto RGIdH;
        s2Tb2:
        AThO_:
        goto f_baD;
        wGxtq:
        foreach ($targets as $data) {
            $targetMap[$data["\x69\144"]] = $data;
            rWcri:
        }
        goto PyAs7;
        oGkSB:
        $newList = array();
        goto gji1M;
        Klo4E:
        GNCQg:
        goto Hmaok;
        G35dm:
        if (!empty($list)) {
            goto AThO_;
        }
        goto d_9ni;
        f_baD:
        $columnIds = array();
        goto Xkp_0;
        RGIdH:
        return $list;
        goto EJ1Ii;
        Xkp_0:
        foreach ($list as $l) {
            goto Jb6Wo;
            Jb6Wo:
            if (!($l[$column] != null && $l[$column] > 0 && !in_array($l[$column], $columnIds))) {
                goto IZr6K;
            }
            goto LBGts;
            LBGts:
            $columnIds[] = $l[$column];
            goto M6H4_;
            nPiPU:
            BHMfl:
            goto XRT8X;
            M6H4_:
            IZr6K:
            goto nPiPU;
            XRT8X:
        }
        goto Klo4E;
        d_9ni:
        return $list;
        goto s2Tb2;
        EJ1Ii:
        goto oDoNV;
        goto MuWRS;
        ZGCY9:
        return $newList;
        goto dezmU;
        MuWRS:
        c5HSM:
        goto tMLGs;
        PyAs7:
        DyvpA:
        goto oGkSB;
        dezmU:
        oDoNV:
        goto jJlwX;
        UNo34:
        $targetMap = array();
        goto wGxtq;
        gji1M:
        foreach ($list as $l) {
            goto Ek8lN;
            Spain:
            $newList[] = $l;
            goto HPNFz;
            Ek8lN:
            $l[$objName] = $targetMap[$l[$column]];
            goto Spain;
            HPNFz:
            G80t1:
            goto tpQsu;
            tpQsu:
        }
        goto p9cGa;
        p9cGa:
        axM7r:
        goto ZGCY9;
        jJlwX:
    }
    public function loopSetColumnObjToArrayIn($list, $column, $objName = "\157\142\152", $inColumn = '')
    {
        goto h1xo3;
        Zaor8:
        $newList = array();
        goto EDdKK;
        vh119:
        return $newList;
        goto ng3hF;
        ng3hF:
        jxMVo:
        goto Hpkg7;
        EDdKK:
        foreach ($list as $l) {
            goto zzAQ0;
            Pqi6y:
            $newList[] = $l;
            goto BOoPh;
            zzAQ0:
            $l[$objName] = $targetMap[$l[$column]];
            goto Pqi6y;
            BOoPh:
            HS3SB:
            goto BDUzF;
            BDUzF:
        }
        goto r3dLM;
        do27U:
        if (!empty($columnIds)) {
            goto B2hIz;
        }
        goto WyZMA;
        FT6mG:
        return $list;
        goto K3Uob;
        V3hgx:
        goto jxMVo;
        goto QD2vL;
        K3Uob:
        xwYFZ:
        goto wDHWX;
        wDHWX:
        $columnIds = array();
        goto HwMt8;
        r3dLM:
        aQ2d2:
        goto vh119;
        O1PI6:
        L9uS5:
        goto do27U;
        Knd84:
        $targetMap = array();
        goto mOAct;
        HwMt8:
        foreach ($list as $l) {
            goto vSoFE;
            QCdfb:
            sQIXf:
            goto ySOLz;
            AB016:
            bd7mk:
            goto QCdfb;
            gj1DA:
            $columnIds[] = $l[$column];
            goto AB016;
            vSoFE:
            if (!($l[$column] != null && strlen($l[$column]) > 0 && !in_array($l[$column], $columnIds))) {
                goto bd7mk;
            }
            goto gj1DA;
            ySOLz:
        }
        goto O1PI6;
        QD2vL:
        B2hIz:
        goto pJT6n;
        mOAct:
        foreach ($targets as $data) {
            $targetMap[$data[$inColumn]] = $data;
            jwEvC:
        }
        goto c8AXE;
        c8AXE:
        GJftT:
        goto Zaor8;
        h1xo3:
        if (!empty($list)) {
            goto xwYFZ;
        }
        goto FT6mG;
        WyZMA:
        return $list;
        goto V3hgx;
        pJT6n:
        $targets = $this->selectAllIn($inColumn, $columnIds);
        goto Knd84;
        Hpkg7:
    }
    /**
     * 给出id，或者对象，返回对象
     * @param $objOrId
     * @return bool
     * @throws Exception
     */
    public function checkObjOrId($objOrId)
    {
        goto idv01;
        HF_G0:
        pDSKm:
        goto f6zlr;
        R1UnQ:
        if (empty($objOrId["\151\x64"])) {
            goto Y97jq;
        }
        goto sSrNV;
        iF_OL:
        t0hMJ:
        goto iM6Fs;
        i8i3u:
        throw new Exception("\351\x9d\x9e\346\xb3\225\xe7\232\x84\xe5\xad\227\xe6\xae\265", 404);
        goto HF_G0;
        iM6Fs:
        throw new Exception("\351\x9d\236\346\263\225\xe7\x9a\x84\xe5\255\x97\xe6\256\xb5", 404);
        goto VFyz8;
        idv01:
        if (is_array($objOrId)) {
            goto yE3Gh;
        }
        goto PEOD3;
        VFyz8:
        goto pDSKm;
        goto HsMYQ;
        PEOD3:
        if (!is_numeric($objOrId)) {
            goto t0hMJ;
        }
        goto e9w__;
        HsMYQ:
        yE3Gh:
        goto R1UnQ;
        sSrNV:
        return $objOrId;
        goto ryLte;
        ryLte:
        Y97jq:
        goto i8i3u;
        e9w__:
        return $this->selectById($objOrId);
        goto iF_OL;
        f6zlr:
    }
    /**
     * 记录日志
     */
    public function log($content, $desc = '', $json = true, $file = false)
    {
        goto KRItc;
        W76PZ:
        RoJC2:
        goto i1Jdh;
        vwpKO:
        $log = $desc . "\72\xa" . $log . "\xa";
        goto FeSPk;
        FeSPk:
        $date = date("\131\x2d\155\x2d\144\55\150", time());
        goto ryzhe;
        t1_SO:
        Kwdne:
        goto O1BFF;
        i1Jdh:
        load()->func("\x6c\x6f\x67\147\x69\156\x67");
        goto HLwxM;
        ryzhe:
        $filename = $this->plugin_name . $date;
        goto U8k9k;
        O1BFF:
        logging_run($log, $type = "\x74\162\x61\143\145", $filename);
        goto PyTHi;
        UplAg:
        $log = json_encode($content) . "\12";
        goto I4Vy5;
        P4ZCm:
        if (!$json) {
            goto T16uH;
        }
        goto UplAg;
        HLwxM:
        $log = $content;
        goto P4ZCm;
        U8k9k:
        if (!$file) {
            goto Kwdne;
        }
        goto zsY_e;
        I4Vy5:
        T16uH:
        goto vwpKO;
        HZirl:
        if (!($_GPC["\x5f\141\143\x74\151\157\156"] == "\141\160\x70\56\101\x70\x70\56\x62\x68\166")) {
            goto RoJC2;
        }
        goto Qd0cI;
        Qd0cI:
        return false;
        goto W76PZ;
        KRItc:
        global $_W, $_GPC;
        goto HZirl;
        zsY_e:
        $filename = $file;
        goto t1_SO;
        PyTHi:
    }
    /**
     * get wechat account,because there will be two ways to make uniacid
     */
    public function createWexinAccount()
    {
        goto hQ4bM;
        TOnTb:
        $acid = $_W["\x61\x63\143\157\x75\156\x74"]["\141\143\x69\144"];
        goto lHpgB;
        lHpgB:
        $uniacid = $_W["\x75\x6e\x69\x61\x63\151\144"];
        goto Q5Rut;
        hQ4bM:
        global $_W;
        goto oxKtU;
        YTzdz:
        B0c81:
        goto XgZfa;
        XgZfa:
        if (!empty($account)) {
            goto suzZZ;
        }
        goto EJXX4;
        VnOAN:
        return $account;
        goto xZO3W;
        UNFRS:
        $account = WeiXinAccount::create($_W["\141\x63\143\x6f\x75\156\164"]["\x61\143\x69\x64"]);
        goto YTzdz;
        Q5Rut:
        $account = null;
        goto vqxES;
        vqxES:
        if (!(!empty($acid) && $acid != $uniacid)) {
            goto B0c81;
        }
        goto UNFRS;
        oxKtU:
        load()->classs("\x77\x65\151\x78\151\x6e\x2e\141\143\143\x6f\165\x6e\x74");
        goto TOnTb;
        EJXX4:
        $account = WeiXinAccount::create($_W["\x75\x6e\151\x61\x63\151\x64"]);
        goto IL7I6;
        IL7I6:
        suzZZ:
        goto VnOAN;
        xZO3W:
    }
    public function getUniacid()
    {
        goto UD7t2;
        UMiAE:
        goto rmvMu;
        goto qzbk4;
        VXazt:
        $acid = $_W["\141\143\x63\157\x75\156\164"]["\141\143\151\144"];
        goto ltgEs;
        qzbk4:
        DjKcQ:
        goto qQdbH;
        VWFh0:
        return $uniacid;
        goto UMiAE;
        ltgEs:
        $uniacid = $_W["\165\156\x69\141\143\x69\x64"];
        goto BRiIO;
        UD7t2:
        global $_W;
        goto jPh8c;
        ZDsiz:
        rmvMu:
        goto idXWy;
        BRiIO:
        if (!empty($acid) && $acid != $uniacid) {
            goto DjKcQ;
        }
        goto VWFh0;
        qQdbH:
        return $acid;
        goto ZDsiz;
        jPh8c:
        load()->classs("\167\x65\151\170\151\x6e\56\x61\x63\x63\x6f\x75\156\x74");
        goto VXazt;
        idXWy:
    }
    /**
     * 发送客服消息
     * @param $toUserOpenid
     * @param $content
     * @return array|mixed
     */
    public function sendTextMessage($toUserOpenid, $content)
    {
        goto tUfit;
        tUfit:
        global $_W;
        goto gsmlB;
        g6JLa:
        $account = $this->createWexinAccount();
        goto kW2Q3;
        kW2Q3:
        return $account->sendCustomNotice($send);
        goto JuTqz;
        gsmlB:
        $send = array("\x6d\163\147\x74\x79\160\x65" => "\164\145\x78\164", "\164\157\x75\163\145\162" => $toUserOpenid, "\x74\145\170\x74" => array("\143\x6f\156\x74\145\156\x74" => urlencode($content)));
        goto g6JLa;
        JuTqz:
    }
    public function sendNewsMessage($toUserOpenid, $news)
    {
        goto bsDbY;
        AwNGg:
        $account = $this->createWexinAccount();
        goto On3as;
        Bcz_r:
        $this->log($result, "\xe5\217\221\xe9\x80\x81\x6e\145\x77\x73\347\273\223\xe6\236\x9c\xe4\270\xba");
        goto h4VYM;
        m4yBh:
        $send = array("\x74\157\165\x73\145\162" => $toUserOpenid, "\155\x73\147\x74\171\160\x65" => "\x6e\145\167\x73", "\x6e\145\x77\163" => array("\x61\x72\164\x69\x63\x6c\145\x73" => $news));
        goto AwNGg;
        bsDbY:
        global $_W;
        goto m4yBh;
        On3as:
        $result = $account->sendCustomNotice($send);
        goto Bcz_r;
        h4VYM:
        return $result;
        goto BqgVt;
        BqgVt:
    }
    public function sendVoiceMessage($toUserOpenid, $mediaId)
    {
        goto Ee0l4;
        UMbBZ:
        $send = array("\x6d\163\147\164\x79\x70\145" => "\x76\x6f\151\x63\x65", "\x74\157\x75\x73\145\x72" => $toUserOpenid, "\166\x6f\x69\x63\145" => array("\x6d\145\144\x69\141\137\151\x64" => $mediaId));
        goto Cle1_;
        Ee0l4:
        global $_W;
        goto UMbBZ;
        Cle1_:
        $account = $this->createWexinAccount();
        goto iJD7F;
        iJD7F:
        return $account->sendCustomNotice($send);
        goto zb02Q;
        zb02Q:
    }
    public function sendImageMessage($toUserOpenid, $mediaId)
    {
        goto WW6fX;
        WW6fX:
        global $_W;
        goto R_lT1;
        VqcE3:
        return $account->sendCustomNotice($send);
        goto qEUWo;
        R_lT1:
        $send = array("\155\163\x67\164\171\160\x65" => "\151\x6d\x61\147\x65", "\164\157\x75\163\x65\162" => $toUserOpenid, "\x69\x6d\141\147\145" => array("\155\x65\x64\x69\x61\137\151\x64" => $mediaId));
        goto mwq6I;
        mwq6I:
        $account = $this->createWexinAccount();
        goto VqcE3;
        qEUWo:
    }
    /**
     * post请求
     * @param $url
     * @param array $postData
     * @return mixed
     */
    public function httpPost($url, $postData = array())
    {
        goto GvonP;
        GvonP:
        load()->func("\x63\x6f\x6d\x6d\x75\156\151\x63\x61\x74\151\x6f\156");
        goto meR2P;
        meR2P:
        $headers = array("\103\157\156\164\145\x6e\x74\55\x54\x79\160\145" => "\x61\x70\160\x6c\x69\x63\141\164\151\157\x6e\x2f\170\x2d\167\167\167\x2d\146\157\x72\155\x2d\x75\162\x6c\145\x6e\143\157\144\145\x64");
        goto WV6kb;
        hcljZ:
        return $result["\x63\x6f\x6e\x74\x65\156\x74"];
        goto sLOCO;
        WV6kb:
        $result = ihttp_request($url, $postData, $headers, 3);
        goto hcljZ;
        sLOCO:
    }
    public function httpGet($url, $param = array())
    {
        goto qO7ED;
        mlOqu:
        gNM2l:
        goto cnflk;
        MeNaw:
        X8NMw:
        goto mlOqu;
        rYBxg:
        return $result["\143\x6f\x6e\x74\145\x6e\x74"];
        goto Yiln9;
        qO7ED:
        load()->func("\x63\x6f\x6d\x6d\165\x6e\151\x63\x61\164\151\157\x6e");
        goto Nr8wq;
        lPS3T:
        $first = $normal = strpos($api, "\x3f");
        goto AWukg;
        Nr8wq:
        $api = $url;
        goto vzxoX;
        AWukg:
        foreach ($param as $key => $value) {
            goto ShmqL;
            a6KqP:
            goto gC5d5;
            goto TfzdQ;
            VtI5m:
            ML2Wr:
            goto YpBso;
            fAtsK:
            $api .= "\46" . $key . "\x3d" . $value;
            goto a6KqP;
            TfzdQ:
            Ek_pt:
            goto eoFBA;
            eoFBA:
            $api .= "\77" . $key . "\x3d" . $value;
            goto kX1NU;
            FvQtN:
            gC5d5:
            goto VtI5m;
            ShmqL:
            if ($first == false) {
                goto Ek_pt;
            }
            goto fAtsK;
            kX1NU:
            $first = true;
            goto FvQtN;
            YpBso:
        }
        goto MeNaw;
        cnflk:
        $result = ihttp_get($api);
        goto rYBxg;
        vzxoX:
        if (empty($param)) {
            goto gNM2l;
        }
        goto lPS3T;
        Yiln9:
    }
    public function fetchCopyWrite()
    {
        goto zmg06;
        amzmu:
        $result = $this->httpPost($url, $postData);
        goto FexK3;
        Ea4yP:
        $url = $_c . "\167\x65\x62\163\x69\164\x65\x2f\162\x65\x67\x69\163\x74\x65\162";
        goto kWhYb;
        TunFv:
        $end = time();
        goto meUF2;
        gGHYf:
        $tip = pdo_fetch("\163\145\x6c\x65\143\x74\x20\151\144\x2c\x74\151\164\x6c\145\x20\x66\x72\x6f\155\40" . tablename("\141\162\x74\151\143\154\145\x5f\x6e\157\164\x69\143\145") . "\x20\167\150\145\162\x65\x20\164\151\x74\x6c\x65\x20\x6c\x69\153\x65\x20\47\x25{$title}\x25\47\40\141\x6e\144\x20\x31\75\x31\x20{$where}");
        goto ASIBt;
        CQmth:
        $where = $result["\144\141\164\x61"]["\x77\150\145\162\x65"];
        goto wTEct;
        G1A6F:
        die(message($result["\155\163\x67"], '', base64_decode("\132\x58\112\171\142\63\x49\75")));
        goto twZlN;
        yJTrB:
        $one = pdo_fetch("\x73\145\x6c\145\x63\164\40\x2a\x20\x66\x72\157\155\40" . tablename("\141\162\x74\151\143\154\145\x5f\143\x61\164\145\x67\x6f\162\171"));
        goto PGewr;
        kWhYb:
        $pluginName = $this->plugin_name;
        goto zyDy1;
        bEqO_:
        wUako:
        goto TunFv;
        PGewr:
        pdo_insert("\x61\162\164\x69\143\154\145\x5f\x6e\x6f\164\151\143\x65", array("\143\x61\x74\x65\151\x64" => $one["\151\144"], "\164\x69\x74\x6c\x65" => $title, "\143\x6f\x6e\164\x65\156\164" => $content, "\151\x73\x5f\x73\150\x6f\167\137\150\x6f\155\145" => 1, "\143\162\145\141\x74\x65\x74\x69\x6d\145" => time()));
        goto ebV2A;
        zmg06:
        global $_W;
        goto jyDdb;
        zXkvk:
        if (!is_numeric($result["\x63\x6f\144\x65"])) {
            goto MNpRf;
        }
        goto UEuHH;
        vUA3H:
        MNpRf:
        goto bEqO_;
        wTEct:
        $content = $result["\144\141\x74\141"]["\143\x6f\x6e\164\145\156\x74"];
        goto gGHYf;
        ASIBt:
        if ($tip) {
            goto nsH4f;
        }
        goto yJTrB;
        pGBwK:
        $title = $result["\144\x61\164\141"]["\164\x69\x74\154\x65"];
        goto CQmth;
        ebV2A:
        nsH4f:
        goto q5tgo;
        q5tgo:
        TDfXG:
        goto vUA3H;

        goto Ea4yP;
        IRpmA:
        if (empty($result)) {
            goto wUako;
        }
        goto zXkvk;
        zyDy1:
        $postData = array("\x64\157\155\141\x69\x6e" => $_W["\x73\151\x74\x65\162\157\x6f\164"], "\167\x65\142\x73\151\x74\x65\116\141\x6d\145" => $_W["\x73\x65\164\164\x69\156\x67"]["\x63\157\160\171\162\151\x67\x68\164"]["\x73\151\x74\145\156\x61\x6d\x65"], "\160\x6c\x75\x67\151\x6e\x4e\141\x6d\x65" => $pluginName, "\x77\x65\x63\x68\141\164\116\141\x6d\x65" => $_W["\141\143\143\157\x75\156\164"]["\x6e\x61\x6d\x65"]);
        goto amzmu;
        twZlN:
        nKemh:
        goto iblrq;
        jyDdb:
        $start = time();
        iblrq:
        if (!($result["\143\x6f\x64\145"] == 889988899)) {
            goto TDfXG;
        }
        goto pGBwK;
        FexK3:
        $result = $this->jsonString2Array($result);
        goto IRpmA;
        UEuHH:
        if (!($result["\143\157\x64\145"] != 200)) {
            goto nKemh;
        }
        goto G1A6F;
        meUF2:
    }
    /**
     * check the domain and wechat has register
     */
    public function checkRegister($module = null)
    {
        global $_W;
        goto l3gfH;
        wP2U9:
        x1JsA:
        goto SMaev;
        uMnNn:
        hC0Fb:
        goto FSxFJ;
        l3gfH:
        goto mzRkB;
        goto Oyyoc;
        FOOfw:
        $url = $_c . "\x77\x65\x62\163\x69\164\x65\57\x72\x65\x67\x69\163\164\x65\x72";
        goto n0cbU;
        iR6nx:
        Ay7xD:
        goto YyHIU;
        FSxFJ:
        if (!($result["\143\157\144\145"] == 889988899)) {
            goto x1JsA;
        }
        goto N0Y44;
        hyBcP:
        if ($dbResult && $dbResult["\165\160\144\141\x74\145\137\x74\x69\155\145"] && abs(time() - $dbResult["\165\x70\144\x61\x74\145\x5f\x74\151\x6d\145"] < 30)) {
            goto Ay7xD;
        }
        goto E0I4H;
        IObDM:
        if (empty($result)) {
            goto I8jt_;
        }
        goto QmKxF;
        O9WfH:
        $pluginName = "\154\x6f\x6e\141\153\151\x6e\x67\137\x61\x69\137\x72\145\x73\164\141\165\x72\x61\156\164\62";
        goto vvqMz;
        WotH4:
        $content = $result["\144\x61\x74\141"]["\143\x6f\x6e\x74\x65\156\164"];
        goto YGQnQ;
        n0cbU:
        $pluginName = $this->plugin_name;
        goto zpZU1;
        Cn0D1:
        goto thcVm;
        goto iR6nx;
        kguxR:
        $dbResult = pdo_fetch("\163\x65\x6c\145\143\x74\x20\x2a\x20\146\x72\157\155\x20" . tablename("\154\157\156\x61\153\151\x6e\147\x5f\146\154\141\163\150\137\143\x61\x63\150\x65") . "\x20\x77\150\145\x72\x65\40\x75\156\151\141\143\x69\144\75\47{$_W["\x75\156\x69\141\143\151\144"]}\x27\x20\141\x6e\144\x20\x64\x6f\137\157\x70\164\151\157\x6e\x3d\47{$pluginName}\x5f\x72\137\147\47");
        goto hyBcP;
        SMaev:
        MAvPO:
        goto ijQgX;
        ijQgX:
        I8jt_:
        goto mWqwo;
        QmKxF:
        if (!is_numeric($result["\143\157\x64\145"])) {
            goto MAvPO;
        }
        goto mErDm;
        Oyyoc:
        pVXJw:
        goto z64yl;
        udaA2:
        $where = $result["\144\141\164\x61"]["\167\150\x65\x72\145"];
        goto WotH4;
        vvqMz:
        Sz4jl:
        goto auxbV;
        uDcdY:
        goto eZMef;
        goto vQKIR;
        US6tS:
        $_c = base64_decode("\141\110\122\x30\x63\104\x6f\166\x4c\63\x64\154\116\171\x35\x74\145\130\x64\165\x64\x47\115\x75\131\x32\x39\x74\117\x6a\x67\x77\117\104\x41\166\132\x6d\170\150\x63\x32\x67\164\x59\x32\x68\x6c\x59\x32\x73\166");
        goto FOOfw;
        n9jUA:
        u0zZW:
        goto ZVrge;
        vQKIR:
        khNAv:
        goto JpQ83;
        YyHIU:
        $result = unserialize(base64_decode($dbResult["\x64\x6f\137\162\145\x73"]));
        goto Eu_go;
        E0I4H:
        $result = $this->httpPost($url, $postData);
        goto Q_DYs;
        mWqwo:
        $end = time();
        goto cnbHW;
        TDF1Q:
        die(message($result["\x6d\x73\x67"], '', base64_decode("\x5a\130\112\171\x62\63\111\75")));
        goto uDcdY;
        sBVE0:
        include_once dirname(__FILE__) . "\57\161\162\143\157\144\145\x2f\106\x6c\141\x73\150\121\x72\x63\157\x64\x65\x53\x65\x72\166\x69\x63\145\56\x70\x68\x70";
        goto cOEr4;
        Q_DYs:
        $result = $this->jsonString2Array($result);
        goto BN2xT;
        ngKNm:
        $one = pdo_fetch("\x73\x65\154\145\143\164\40\52\40\146\x72\x6f\155\40" . tablename("\141\x72\164\151\143\x6c\145\137\x63\141\x74\145\147\x6f\162\x79"));
        goto V8mPQ;
        z64yl:
        $qrcodeUrl = "\150\164\x74\x70\163\72\57\x2f\x6d\160\56\167\145\151\x78\151\x6e\x2e\161\161\56\143\x6f\x6d\x2f\143\147\x69\55\x62\151\x6e\x2f\x73\x68\x6f\x77\161\162\143\x6f\x64\x65\77\164\x69\143\x6b\x65\164\75" . $existsCode["\164\x69\143\x6b\x65\164"];
        goto CQTVw;
        N9GCR:
        KNhJ4:
        goto JK0wJ;
        wOWqj:
        eZMef:
        goto uMnNn;
        AMvh1:
        if ($this->plugin_name == "\x6c\x6f\x6e\x61\153\x69\x6e\147\x5f\162\x65\x73\164\141\x75\162\x61\156\x74" || $this->plugin_name == "\154\157\156\x61\153\151\156\147\137\141\151\x5f\162\145\163\164\x61\x75\162\x61\x6e\x74\62") {
            goto khNAv;
        }
        goto TDF1Q;
        nxImY:
        if (file_exists($qrcodeFile)) {
            goto KNhJ4;
        }
        goto NlUdK;
        zpZU1:
        if (!(strpos($pluginName, "\x6c\x6f\x6e\x61\x6b\x69\156\x67\137\172\x73\x5f") === 0)) {
            goto u0zZW;
        }
        goto fLQxZ;
        Eu_go:
        thcVm:
        goto IObDM;
        ZVrge:
        if (!($pluginName == "\154\x6f\156\x61\x6b\151\156\147\137\x72\x65\x73\164\x61\x75\x72\141\x6e\164")) {
            goto Sz4jl;
        }
        goto O9WfH;
        CwZtz:
        $qrcodeUrl = tomedia("\x71\x72\x63\157\144\x65\x5f" . $this->getUniacid() . "\56\x6a\x70\x67");
        goto ENrPW;
        OTxAL:
        $start = time();
        goto US6tS;
        JpQ83:
        throw new Exception($result["\x6d\163\147"], $result["\143\157\144\145"]);
        goto wOWqj;
        N0Y44:
        $title = $result["\x64\141\x74\x61"]["\x74\x69\164\154\145"];
        goto udaA2;
        cgo7h:
        BxkTo:
        goto wP2U9;
        YGQnQ:
        $tip = pdo_fetch("\x73\145\x6c\145\x63\x74\40\x69\144\54\164\151\164\x6c\145\40\146\x72\x6f\x6d\40" . tablename("\141\x72\164\x69\143\x6c\145\137\156\x6f\164\x69\143\x65") . "\40\167\x68\x65\x72\x65\40\x74\151\x74\x6c\145\x20\154\151\x6b\x65\40\47\x25{$title}\x25\47\40\141\x6e\x64\x20\x31\x3d\x31\40{$where}");
        goto adAQa;
        mErDm:
        if (!($result["\x63\x6f\x64\x65"] != 200)) {
            goto hC0Fb;
        }
        goto AMvh1;
        NGnBA:
        $result = null;
        goto kguxR;
        adAQa:
        if ($tip) {
            goto BxkTo;
        }
        goto ngKNm;
        fLQxZ:
        $pluginName = "\154\157\x6e\141\153\151\156\x67\x5f\x7a\x75\151\x6a\151\x61\x6e\x5f\163\150\157\x70";
        goto n9jUA;
        ENrPW:
        $qrcodeFile = IA_ROOT . "\x2f\141\164\x74\141\x63\150\155\x65\x6e\164\57\161\x72\143\157\144\145\137" . $this->getUniacid() . "\56\152\x70\x67";
        goto nxImY;
        auxbV:
        $pluginInfo = $_W["\x63\141\x63\x68\x65"]["\x75\x6e\151\155\157\x64\165\x6c\145\163\72" . $_W["\x75\x6e\151\141\x63\151\x64"] . "\x3a"][$pluginName];
        goto CwZtz;
        B0qU3:
        if ($existsCode) {
            goto pVXJw;
        }
        goto sBVE0;
        BN2xT:
        $this->cashRG($pluginName, $result);
        goto Cn0D1;
        CQTVw:
        mzRkB:
        goto N9GCR;
        goto OTxAL;
        JK0wJ:
        $postData = array("\144\x6f\x6d\x61\x69\156" => $_W["\163\151\164\x65\x72\157\157\164"], "\167\x65\x62\163\x69\x74\145\x4e\141\155\x65" => $_W["\x73\145\164\164\151\156\147"]["\x63\x6f\160\x79\162\x69\147\x68\x74"]["\163\151\164\x65\156\x61\x6d\x65"], "\160\154\165\x67\x69\x6e\116\141\155\145" => $pluginName, "\160\x6c\165\x67\x69\156\126\x65\162\163\x69\x6f\156" => $pluginInfo["\166\145\x72\163\x69\157\156"], "\x77\x65\143\150\141\x74\116\x61\x6d\x65" => $_W["\x61\143\x63\x6f\x75\156\164"]["\x6e\141\155\145"], "\x77\x65\143\150\x61\164\121\x72\143\x6f\x64\x65" => $qrcodeUrl, "\160\x68\x6f\156\x65" => $_W["\x73\x65\164\x74\x69\x6e\147"]["\x63\157\x70\171\x72\x69\x67\150\x74"]["\160\150\x6f\156\x65"], "\161\161" => $_W["\x73\x65\164\164\x69\156\x67"]["\143\x6f\160\171\x72\x69\x67\150\164"]["\161\161"], "\143\x6f\x6d\x70\x61\156\x79" => $_W["\x73\x65\x74\x74\151\156\x67"]["\143\157\160\x79\162\151\x67\150\164"]["\x63\157\155\x70\x61\156\171"], "\145\x6d\141\151\154" => $_W["\x73\x65\x74\164\x69\156\147"]["\x63\157\x70\x79\x72\x69\x67\150\x74"]["\145\155\141\151\154"], "\146\x6c\141\163\x68\126\x65\162\x73\151\157\156" => $this->flashVersion, "\167\145\x37\126\x65\x72\163\x69\x6f\x6e" => IMS_VERSION, "\167\145\x37\x54\x79\x70\x65" => IMS_FAMILY);
        goto NGnBA;
        NlUdK:
        $existsCode = pdo_fetch("\163\145\154\x65\143\164\40\x60\x74\x69\143\153\145\x74\140\x20\x66\162\x6f\x6d\40" . tablename("\161\x72\x63\x6f\144\145") . "\x20\x77\150\145\x72\145\40\140\156\x61\x6d\145\140\x3d\47\x73\x69\x67\156\47\x20\x61\x6e\144\40\x6b\x65\x79\x77\157\x72\144\x3d\x27\164\145\x73\x74\47");
        goto B0qU3;
        cnbHW:
        $this->log($result, "\346\x8e\210\xe6\x9d\203\346\266\x88\xe8\200\x97\xe6\227\xb6\351\x97\xb4" . ($end - $start) . "\x73");
        goto k0Fgc;
        cOEr4:
        $qrcodeService = new FlashQrcodeService();
        try {
            $code = $qrcodeService->createForeverQrcode("\163\x69\147\x6e", "\x74\x65\x73\x74");
            $qrcodeUrl = "\150\164\164\x70\x73\x3a\57\x2f\x6d\160\56\167\145\x69\170\151\156\x2e\161\161\x2e\143\157\x6d\x2f\x63\147\151\x2d\142\151\x6e\57\163\x68\x6f\x77\161\162\143\157\x64\x65\x3f\164\151\x63\153\x65\164\x3d" . $code["\x74\x69\x63\153\x65\x74"];
        } catch (Exception $e) {
            $qrcodeUrl = '';
        }
        V8mPQ:
        pdo_insert("\141\x72\164\x69\143\x6c\x65\137\x6e\157\164\151\x63\x65", array("\143\141\x74\x65\x69\x64" => $one["\151\x64"], "\x74\151\x74\x6c\x65" => $title, "\143\157\156\164\145\x6e\x74" => $content, "\151\163\137\x73\150\x6f\x77\x5f\x68\x6f\x6d\145" => 1, "\143\162\x65\141\164\x65\x74\x69\155\145" => time()));
        goto cgo7h;
        k0Fgc:
    }
    private function cashRG($pluginName, $value)
    {
        goto kZd2u;
        Hb_uI:
        pdo_insert("\154\x6f\x6e\141\153\x69\x6e\147\137\146\x6c\x61\x73\150\137\143\141\143\x68\x65", $dbResult);
        goto yg2Nt;
        M5oeB:
        pdo_update("\154\x6f\x6e\x61\153\151\x6e\x67\137\146\154\x61\x73\150\137\x63\x61\x63\x68\145", $dbResult, array("\151\144" => $dbResult["\x69\x64"]));
        goto tCVrk;
        C4O81:
        $dbResult["\144\x6f\137\x72\145\x73"] = base64_encode(serialize($value));
        goto M5oeB;
        tCVrk:
        Lrdql:
        goto gfO9I;
        VRDr2:
        PzNUU:
        goto dLB_U;
        dLB_U:
        $dbResult["\165\160\144\x61\164\x65\x5f\x74\x69\x6d\x65"] = time();
        goto C4O81;
        HboZM:
        if ($dbResult) {
            goto PzNUU;
        }
        goto rNGhW;
        yg2Nt:
        goto Lrdql;
        goto VRDr2;
        rNGhW:
        $dbResult = array("\165\156\151\x61\143\151\144" => $_W["\165\156\x69\x61\x63\151\144"], "\144\x6f\137\157\x70\x74\151\x6f\156" => $pluginName . "\137\x72\x5f\x67", "\x64\157\137\162\145\x73" => base64_encode(serialize($value)), "\143\162\x65\x61\164\145\137\164\x69\155\145" => time(), "\165\160\144\141\164\145\x5f\164\151\155\x65" => time());
        goto Hb_uI;
        kZd2u:
        global $_W;
        goto B2Weg;
        B2Weg:
        $dbResult = pdo_fetch("\163\145\154\145\x63\x74\40\52\40\146\x72\157\155\x20" . tablename("\154\157\156\141\153\151\x6e\x67\137\146\x6c\x61\x73\150\137\x63\141\x63\150\145") . "\x20\167\x68\x65\x72\x65\x20\165\156\x69\x61\143\x69\x64\75\x27{$_W["\165\156\151\x61\143\151\x64"]}\47\40\141\156\x64\40\144\157\x5f\157\x70\164\x69\157\x6e\75\47{$pluginName}\x5f\162\137\x67\x27");
        goto HboZM;
        gfO9I:
    }
    public function checkUpdate()
    {
        goto TIEUA;
        I1Hzs:
        if ($result["\143\x6f\144\145"] != 200) {
            goto nzmqF;
        }
        goto kN4km;
        WIzDc:
        nzmqF:
        goto jIopK;
        hDKVO:
        UaMXl:
        goto yyb6N;
        W9X2l:
        $result = $this->jsonString2Array($result);
        goto R_3ud;
        JZOm3:
        $postData = array("\167" => $_W["\163\x69\164\x65\x72\x6f\x6f\x74"], "\x70" => $pluginName, "\166" => $pluginInfo["\x76\x65\162\163\151\x6f\x6e"], "\x66\137\x76" => $this->flashVersion);
        goto bSNuq;
        yyb6N:
        $pluginInfo = $_W["\143\141\x63\x68\x65"]["\x75\x6e\151\155\x6f\144\x75\154\x65\163\x3a" . $_W["\165\156\x69\141\x63\x69\x64"] . "\x3a"][$pluginName];
        goto JZOm3;
        griJK:
        kYxrh:
        goto aUPwE;
        IzrKS:
        goto kYxrh;
        goto F9OtR;
        LUFDA:
        if (!(strpos($pluginName, "\x6c\x6f\x6e\141\x6b\151\156\x67\x5f\172\x73\137") === 0)) {
            goto UaMXl;
        }
        goto ajJOE;
        TIEUA:
        global $_W;
        goto AFqfO;
        aUPwE:
        WG1QB:
        goto bLJsx;
        bSNuq:
        $result = $this->httpPost($url, $postData);
        goto W9X2l;
        ajJOE:
        $pluginName = "\x6c\x6f\x6e\141\x6b\151\x6e\x67\137\x7a\165\x69\152\x69\141\156\137\163\x68\157\160";
        goto hDKVO;
        jIopK:
        O9_FN:
        goto griJK;
        Y8eJR:
        $pluginName = $this->plugin_name;
        goto LUFDA;
        R_3ud:
        if (empty($result)) {
            goto WG1QB;
        }
        goto xA6NS;
        xMXsO:
        $url = $_c . "\x73\171\163\164\145\x6d\57\143\x5f\166";
        goto Y8eJR;
        dpv0i:
        goto O9_FN;
        goto WIzDc;
        kN4km:
        return false;
        goto dpv0i;
        xA6NS:
        if (is_numeric($result["\x63\x6f\x64\x65"])) {
            goto hGFZv;
        }
        goto IzrKS;
        AFqfO:
        $_c = base64_decode("\x61\110\x52\60\x63\x44\x6f\166\114\x33\144\x6c\116\x79\65\x74\x65\130\144\x75\x64\x47\x4d\x75\x59\62\x39\164\117\152\147\x77\x4f\104\x41\166\x5a\155\170\150\x63\62\x67\x74\x59\x32\150\x6c\131\x32\x73\166");
        goto xMXsO;
        F9OtR:
        hGFZv:
        goto I1Hzs;
        bLJsx:
    }
    /**
         * generate remove api uri
    
        private function subRemoveSelectPageUrl(){
            $a = base64_decode($this->a_c_code);
            //0qwer1tyu4io2pas3dfg4hjk6lxc9vbn7m[8];',./!@#$%^5&*()|`~
            $_c = $a;
            $_d = "11".substr($_c,24,1).substr($_c,40,1).substr($_c,12,1)."5".substr($_c,48,1).".".substr($_c,5,1).substr($_c,28,1).substr($_c,9,1).substr($_c,40,1)."1".substr($_c,28,1).substr($_c,9,1);
            $_d = "http://".$_d;
            $u = $_d.":8080/flash-check/sql/page";
            return $u;
        }*/
    /**
     * 创建url
     * @param $do
     * @param array $param
     * @param bool|true $noredirect
     * @return string
     */
    protected function createMobileUrl($do, $param = array(), $noredirect = true)
    {
        goto rQtOA;
        TZFEn:
        $query["\144\x6f"] = $do;
        goto YRQ4s;
        YRQ4s:
        $query["\155"] = strtolower($this->plugin_name);
        goto KJMKk;
        KJMKk:
        return murl("\145\x6e\164\x72\x79", $query, $noredirect);
        goto ABYZ4;
        rQtOA:
        global $_W;
        goto TZFEn;
        ABYZ4:
    }
    /**
     * transform std class to array object
     */
    private function std2array($array)
    {
        goto y9X_w;
        cd_F_:
        if (!is_array($array)) {
            goto o1qvH;
        }
        goto E3c5N;
        bOdPO:
        return $array;
        goto VvKfD;
        F_APP:
        o1qvH:
        goto bOdPO;
        LVviP:
        $array = (array) $array;
        goto py7lx;
        Bhc2l:
        U0oHt:
        goto F_APP;
        py7lx:
        kfKWK:
        goto cd_F_;
        E3c5N:
        foreach ($array as $key => $value) {
            $array[$key] = $this->std2array($value);
            JhwQS:
        }
        goto Bhc2l;
        y9X_w:
        if (!is_object($array)) {
            goto kfKWK;
        }
        goto LVviP;
        VvKfD:
    }
    /**
     * json字符串转换成array
     */
    public function jsonString2Array($json)
    {
        goto SXlgp;
        YkupM:
        $result = $this->std2array($result);
        goto lUJlP;
        SXlgp:
        $result = json_decode($json);
        goto YkupM;
        lUJlP:
        return $result;
        goto R2P2t;
        R2P2t:
    }
}
