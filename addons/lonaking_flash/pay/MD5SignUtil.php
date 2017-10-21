<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

/**
 * 本代码出自一位前辈之手，由于作者被封，无法与其取得联系，如有侵权，请联系本人 QQ 1214512299
 */
class MD5SignUtil
{
    function sign($content, $key)
    {
        try {
            goto gevR8;
            SyUBn:
            JXoW9:
            goto rm5oO;
            gevR8:
            if (!(null == $key)) {
                goto FBt2S;
            }
            goto WAJsQ;
            tNmJd:
            if (!(null == $content)) {
                goto JXoW9;
            }
            goto W8g7K;
            rm5oO:
            $signStr = $content . "\x26\x6b\145\x79\75" . $key;
            goto tw0hi;
            BRXZ8:
            FBt2S:
            goto tNmJd;
            tw0hi:
            return strtoupper(md5($signStr));
            goto jkR02;
            W8g7K:
            throw new SDKRuntimeException("\xe7\xad\xbe\xe5\220\x8d\xe5\206\205\345\xae\271\344\xb8\215\xe8\203\xbd\344\xb8\xba\347\xa9\xba" . "\x3c\x62\162\76");
            goto SyUBn;
            WAJsQ:
            throw new SDKRuntimeException("\345\xaf\206\351\x92\xa5\xe4\270\215\xe8\203\275\344\xb8\272\347\xa9\272\xef\274\x81" . "\74\x62\162\76");
            goto BRXZ8;
            jkR02:
        } catch (SDKRuntimeException $e) {
            die($e->errorMessage());
        }
    }
    function verifySignature($content, $sign, $md5Key)
    {
        goto UgMp8;
        uX7dh:
        return $calculateSign == $tenpaySign;
        goto yW26W;
        N2Jaj:
        $tenpaySign = strtolower($sign);
        goto uX7dh;
        AM2Jw:
        $calculateSign = strtolower(md5($signStr));
        goto N2Jaj;
        UgMp8:
        $signStr = $content . "\46\153\145\171\75" . $md5Key;
        goto AM2Jw;
        yW26W:
    }
}
?>
