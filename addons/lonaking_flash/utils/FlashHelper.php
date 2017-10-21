<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/17
 * Time: 下午2:29
 */
class FlashHelper
{
    /**
     *
     */
    public static function fetchModelArrayIds($arr, $columns_name = "\151\144")
    {
        goto U80NO;
        n0zwn:
        return $result;
        goto c8V3W;
        GS2wa:
        jVFOI:
        goto n0zwn;
        U80NO:
        $result = array();
        goto zf7hc;
        zf7hc:
        foreach ($arr as $a) {
            $result[] = $a[$columns_name];
            RGpFm:
        }
        goto GS2wa;
        c8V3W:
    }
    public static function fetchColumnArray($arr, $columns_name, $kill_null = false, $kill_repeat = false)
    {
        goto xaccI;
        NXG1V:
        yZrbk:
        goto XvvoF;
        Mdrd3:
        goto RMiyn;
        goto jGcYY;
        FNWnP:
        foreach ($arr as $a) {
            goto MMP8E;
            bgjYf:
            Zeeaj:
            goto k6TXp;
            GE_xd:
            $result[] = $a[$columns_name];
            goto JzTW1;
            oke2U:
            f2TuX:
            goto hWsho;
            zG9wE:
            $result[] = $a[$columns_name];
            goto KZe20;
            KZe20:
            goto aE6_q;
            goto oke2U;
            uvt46:
            aE6_q:
            goto bgjYf;
            MMP8E:
            if ($kill_null) {
                goto f2TuX;
            }
            goto zG9wE;
            hWsho:
            if (empty($a[$columns_name])) {
                goto sPr1i;
            }
            goto GE_xd;
            JzTW1:
            sPr1i:
            goto uvt46;
            k6TXp:
        }
        goto NXG1V;
        uRfX7:
        return array_unique($result);
        goto pRIfK;
        XvvoF:
        if ($kill_repeat) {
            goto nzslU;
        }
        goto XD_Nw;
        pRIfK:
        RMiyn:
        goto UpA8k;
        XD_Nw:
        return $result;
        goto Mdrd3;
        jGcYY:
        nzslU:
        goto uRfX7;
        xaccI:
        $result = array();
        goto FNWnP;
        UpA8k:
    }
    public static function array2Map($arr, $key = "\151\x64")
    {
        goto WMCso;
        FDqP1:
        return $result;
        goto kEEJb;
        Oh8f8:
        foreach ($arr as $data) {
            $result[$data[$key]] = $data;
            pqWRB:
        }
        goto DW_3U;
        DW_3U:
        arLlc:
        goto FDqP1;
        WMCso:
        $result[] = array();
        goto Oh8f8;
        kEEJb:
    }
}
