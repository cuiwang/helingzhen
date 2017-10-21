<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

class FlashExcelHelper
{
    /**
     * @param $header array('column'=>'name',...)
     * @param $data
     */
    public static function dump($header, $data = null, $filename = '')
    {
        goto JqA_s;
        VCaw9:
        foreach ($data as $d) {
            goto N9Z6r;
            N9Z6r:
            $tmp_data = array();
            goto ax_O8;
            xCd3f:
            xc6nQ:
            goto zvqDA;
            zvqDA:
            $tableData[] = $tmp_data;
            goto YDr4V;
            YDr4V:
            Xs8Xj:
            goto Wqyhk;
            ax_O8:
            foreach ($header as $column => $name) {
                $tmp_data[] = $d[$column];
                rohAr:
            }
            goto xCd3f;
            Wqyhk:
        }
        goto rTe2p;
        Ut26X:
        $excel = new PHPExcel();
        goto Z8wX0;
        pzyLY:
        if (!($i <= count($tableData) + 1)) {
            goto Cj5st;
        }
        goto SnPY6;
        FGd3U:
        header("\103\141\143\150\145\x2d\103\157\x6e\x74\162\x6f\x6c\x3a\155\x75\x73\164\55\x72\x65\166\141\154\x69\144\x61\x74\x65\x2c\40\x70\x6f\163\x74\x2d\143\x68\x65\x63\153\x3d\60\x2c\x20\160\162\145\55\x63\x68\145\143\153\x3d\60");
        goto kvif7;
        o_A4x:
        header("\x43\157\x6e\164\145\x6e\164\55\x44\x69\163\x70\x6f\163\151\164\x69\157\x6e\72\x61\164\164\x61\x63\x68\155\145\156\164\x3b\x66\151\154\145\x6e\x61\x6d\x65\x3d\42" . $filename . "\56\x63\x73\x76\42");
        goto SWwSR;
        NrPg5:
        QbT2I:
        goto Hp1h2;
        sZoGt:
        $i = 2;
        goto KI8m3;
        Hp1h2:
        $i++;
        goto bE0Ut;
        PsVsr:
        $write->save("\160\150\160\x3a\57\x2f\157\x75\x74\160\x75\164");
        goto mkiow;
        qHdR8:
        header("\x50\162\x61\147\x6d\141\72\x20\x70\165\x62\154\151\x63");
        goto D5VWG;
        HX11y:
        Cj5st:
        goto Vre86;
        BugN9:
        header("\103\x6f\156\x74\x65\x6e\x74\55\x54\171\x70\145\72\x61\160\x70\154\x69\143\x61\x74\151\157\156\57\x76\x6e\x64\56\x6d\x73\55\x65\x78\x65\143\x6c");
        goto OzWCG;
        D5VWG:
        header("\x45\170\160\x69\162\x65\163\72\x20\60");
        goto FGd3U;
        JqA_s:
        require dirname(__FILE__) . "\x2f\x2e\56\x2f\56\x2e\x2f\x2e\x2e\x2f\146\x72\141\x6d\145\167\157\162\153\57\x6c\x69\x62\162\x61\x72\171\57\x70\150\x70\145\170\143\x65\154\57\120\x48\120\x45\170\x63\x65\x6c\x2e\160\150\x70";
        goto Ut26X;
        yrFyu:
        nohbe:
        goto eZx82;
        t8973:
        goto aVA3G;
        goto HX11y;
        h8Kae:
        $write = new PHPExcel_Writer_CSV($excel);
        goto qCKZb;
        BE15M:
        if (!($i < count($tableheader))) {
            goto T0cjj;
        }
        goto l06B5;
        KI8m3:
        aVA3G:
        goto pzyLY;
        QKOZQ:
        $tableheader = array();
        goto HeH0k;
        iYYiA:
        T0cjj:
        goto p33CU;
        u9ZVU:
        header("\103\x6f\x6e\x74\145\x6e\x74\55\x54\171\160\145\72\141\x70\x70\154\151\143\x61\x74\x69\157\156\x2f\144\157\167\x6e\x6c\x6f\x61\x64");
        goto o_A4x;
        eZx82:
        fdznl:
        goto Cour8;
        SO9UM:
        $filename = $filename . date("\131\55\155\x2d\x64\x20\x68\55\151\x2d\x73");
        goto qHdR8;
        Cour8:
        $i++;
        goto t8973;
        SnPY6:
        $j = 0;
        goto ya6pu;
        HeH0k:
        foreach ($header as $column => $name) {
            $tableheader[] = $name;
            BEoCV:
        }
        goto fGFML;
        pfQKO:
        $tableData = array();
        goto VCaw9;
        l06B5:
        $excel->getActiveSheet()->setCellValue("{$letter[$i]}\61", "{$tableheader[$i]}");
        goto NrPg5;
        OzWCG:
        header("\x43\x6f\x6e\164\145\x6e\164\x2d\124\171\x70\x65\72\x61\160\160\x6c\x69\x63\141\x74\151\x6f\156\57\x6f\x63\164\x65\164\55\x73\x74\x72\x65\x61\x6d");
        goto u9ZVU;
        SWwSR:
        header("\103\x6f\156\164\145\x6e\x74\x2d\x54\162\141\156\x73\146\145\162\55\x45\x6e\143\157\144\151\156\147\72\142\151\156\x61\162\x79");
        goto PsVsr;
        qCKZb:
        ob_end_clean();
        goto SO9UM;
        fGFML:
        Gm0UN:
        goto g702s;
        bE0Ut:
        goto NV8Jo;
        goto iYYiA;
        p33CU:
        if (is_null($data)) {
            goto mGAqY;
        }
        goto pfQKO;
        Z8wX0:
        $letter = self::getLetters($header);
        goto QKOZQ;
        Vre86:
        mGAqY:
        goto h8Kae;
        kvif7:
        header("\x43\157\156\164\x65\156\164\x2d\124\171\160\145\x3a\x61\x70\x70\x6c\151\x63\141\x74\151\157\156\x2f\x66\157\162\143\x65\55\x64\x6f\x77\x6e\x6c\157\141\144");
        goto BugN9;
        A1x22:
        NV8Jo:
        goto BE15M;
        g702s:
        $i = 0;
        goto A1x22;
        ya6pu:
        foreach ($tableData[$i - 2] as $key => $value) {
            goto oE2oL;
            GgA6y:
            JRd1R:
            goto l8M1z;
            oE2oL:
            $excel->getActiveSheet()->setCellValue("{$letter[$j]}{$i}", "{$value}");
            goto ieSiy;
            ieSiy:
            $j++;
            goto GgA6y;
            l8M1z:
        }
        goto yrFyu;
        rTe2p:
        V4qFw:
        goto sZoGt;
        mkiow:
    }
    public static function excel2array($filePath = '', $sheet = 0)
    {
        goto VWrn3;
        tM_9y:
        $PHPReader = new PHPExcel_Reader_Excel5();
        goto Qo967;
        CqBFP:
        return $data;
        goto ll8_A;
        QMECs:
        $PHPReader = new PHPExcel_Reader_Excel2007();
        goto EkTG0;
        A48NK:
        die("\xe4\270\x8d\346\x98\xaf\145\170\143\x65\x6c\346\x96\x87\xe4\xbb\xb6");
        goto sDSEo;
        m4sBU:
        $currentSheet = $PHPExcel->getSheet($sheet);
        goto pVoxR;
        pheFP:
        $cell = $cell->__toString();
        goto RJc1C;
        LcjW_:
        De0Gv:
        goto c1kNO;
        HBVBN:
        SFYco:
        goto Q8jLx;
        KUFuR:
        $addr = $colIndex . $rowIndex;
        goto U7iIa;
        Yrq5l:
        $colIndex++;
        goto QWahr;
        G2UTI:
        if (!$cell instanceof PHPExcel_RichText) {
            goto xJtQE;
        }
        goto pheFP;
        x5RgR:
        BMu2P:
        goto t9mym;
        OZTBo:
        if ($PHPReader->canRead($filePath)) {
            goto jFLYv;
        }
        goto A48NK;
        AXmU5:
        $data[$rowIndex][$colIndex] = $cell;
        goto zO4o7;
        t9mym:
        ErVlq:
        goto Ggmet;
        L1e2C:
        $allRow = $currentSheet->getHighestRow();
        goto AdBuL;
        aCU_e:
        $PHPReader = new PHPExcel_Reader_CSV();
        goto OZTBo;
        RJc1C:
        xJtQE:
        goto AXmU5;
        dL561:
        JPGXD:
        goto QMECs;
        XAjmA:
        $colIndex = "\x41";
        goto LcjW_;
        ddOZj:
        goto SFYco;
        goto D9qFK;
        fHWW9:
        $rowIndex = 1;
        goto HBVBN;
        Qo967:
        if ($PHPReader->canRead($filePath)) {
            goto hQKRM;
        }
        goto aCU_e;
        pVoxR:
        $allColumn = $currentSheet->getHighestColumn();
        goto L1e2C;
        AdBuL:
        $data = array();
        goto fHWW9;
        Q8jLx:
        if (!($rowIndex <= $allRow)) {
            goto kVSMZ;
        }
        goto XAjmA;
        QWahr:
        goto De0Gv;
        goto x5RgR;
        zO4o7:
        WYeed:
        goto Yrq5l;
        EkTG0:
        if ($PHPReader->canRead($filePath)) {
            goto CNlp4;
        }
        goto tM_9y;
        c1kNO:
        if (!($colIndex <= $allColumn)) {
            goto BMu2P;
        }
        goto KUFuR;
        sDSEo:
        jFLYv:
        goto PQF4Y;
        PQF4Y:
        hQKRM:
        goto sSBeN;
        U7iIa:
        $cell = $currentSheet->getCell($addr)->getValue();
        goto G2UTI;
        nMdLt:
        die("\x66\151\x6c\145\40\156\157\164\40\x65\x78\151\163\x74\163");
        goto dL561;
        sSBeN:
        CNlp4:
        goto CqqaN;
        D9qFK:
        kVSMZ:
        goto CqBFP;
        VWrn3:
        require dirname(__FILE__) . "\x2f\x2e\x2e\57\x2e\x2e\57\56\56\x2f\x66\x72\x61\x6d\x65\x77\157\x72\153\x2f\x6c\x69\x62\162\141\162\x79\57\160\x68\160\145\170\x63\x65\x6c\57\x50\x48\x50\x45\x78\x63\145\x6c\x2e\x70\x68\160";
        goto PBFEI;
        PBFEI:
        if (!(empty($filePath) or !file_exists($filePath))) {
            goto JPGXD;
        }
        goto nMdLt;
        CqqaN:
        $PHPExcel = $PHPReader->load($filePath);
        goto m4sBU;
        Ggmet:
        $rowIndex++;
        goto ddOZj;
        ll8_A:
    }
    public static function excel2clean($data, $template)
    {
        goto scitj;
        U4wfl:
        Gg1Yw:
        goto DNnak;
        yIb5O:
        $templateIndex = array();
        goto Bwfda;
        uNDVt:
        k2UrG:
        goto b2yer;
        d6wZp:
        $arr = array();
        goto aQTjo;
        T2SZz:
        if (!($i <= sizeof($data))) {
            goto k2UrG;
        }
        goto LMGbb;
        b2yer:
        return $resultData;
        goto zh7Mn;
        gvm7a:
        $letterFiledMap = array();
        goto y6FMw;
        DDCzP:
        foreach ($data[1] as $letter => $title) {
            $headerIndex[$title] = $letter;
            yY8kR:
        }
        goto bQZDO;
        scitj:
        $header = $data[1];
        goto hb_7B;
        LMGbb:
        $tmpData = $data[$i];
        goto d6wZp;
        hb_7B:
        $headerIndex = array();
        goto DDCzP;
        julCm:
        foreach ($headerIndex as $key => $letter) {
            goto oXGjg;
            LPev9:
            die("\xe4\270\x8a\xe4\xbc\xa0\346\x96\207\344\xbb\xb6\344\xb8\x8e\xe6\226\207\xe4\273\266\xe6\xa8\241\xe6\235\277\xe4\270\215\xe4\xb8\200\xe8\x87\264\xef\xbc\x8c\xe8\257\267\xe4\275\xbf\347\224\250\xe4\xb8\213\350\xbd\xbd\xe6\250\xa1\xe6\235\277\xe5\xaf\271\xe7\x85\xa7\xe4\270\215\xe4\xb8\200\xe8\x87\xb4\347\232\204\xe5\234\xb0\346\226\271\357\274\x8c\xe4\xbf\256\346\x94\271\xe5\x90\216\xe9\207\x8d\346\226\260\xe4\xb8\x8a\344\xbc\xa0" . $key);
            goto uaK46;
            oXGjg:
            $exits = array_key_exists($key, $templateIndex);
            goto ivX17;
            Nv7UE:
            q1xtP:
            goto bn4D_;
            uaK46:
            ejq4C:
            goto Nv7UE;
            ivX17:
            if ($exits) {
                goto ejq4C;
            }
            goto LPev9;
            bn4D_:
        }
        goto Ma7bC;
        cD9i9:
        l9IMn:
        goto T2SZz;
        RwYg0:
        $resultData[] = $arr;
        goto GGGCM;
        aQTjo:
        foreach ($tmpData as $tmpLetter => $tmpValue) {
            $arr[$letterFiledMap[$tmpLetter]] = $tmpValue;
            hiBs5:
        }
        goto UTfyf;
        Bwfda:
        foreach ($template as $field => $title) {
            $templateIndex[$title] = $field;
            RnCaa:
        }
        goto B1QWy;
        iryKd:
        $i = 2;
        goto cD9i9;
        zzlHA:
        goto l9IMn;
        goto uNDVt;
        GGGCM:
        GsXhb:
        goto Wz6G1;
        y6FMw:
        foreach ($headerIndex as $title => $letter) {
            $letterFiledMap[$letter] = $templateIndex[$title];
            BXY9V:
        }
        goto U4wfl;
        Wz6G1:
        $i++;
        goto zzlHA;
        Ma7bC:
        maus_:
        goto gvm7a;
        DNnak:
        $resultData = array();
        goto iryKd;
        B1QWy:
        PFo32:
        goto julCm;
        UTfyf:
        altok:
        goto RwYg0;
        bQZDO:
        Pe7cc:
        goto yIb5O;
        zh7Mn:
    }
    private static function getLetters($header)
    {
        goto qKUKZ;
        TE9_N:
        return array_slice($letters, 0, $count);
        goto bqpdm;
        qKUKZ:
        $letters = array("\101", "\102", "\x43", "\104", "\x45", "\106", "\x47", "\110", "\111", "\x4a", "\113", "\114", "\x4d", "\x4e", "\117", "\120", "\x51", "\122", "\123", "\124", "\125", "\126", "\x57", "\x58", "\131", "\x5a");
        goto ljPof;
        ljPof:
        $count = sizeof($header);
        goto TE9_N;
        bqpdm:
    }
}
