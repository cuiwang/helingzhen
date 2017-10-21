<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

/**
 * 断言:该类所有的方法用法都相同,每个方法调用都可以传入一个message和code 当断言出错则抛出相应提示和错误码的异常信息
 * User: leon
 * Date: 15/9/4
 * Time: 上午1:05
 */
require_once dirname(__FILE__) . "\x2f\56\56\x2f\x65\x78\x63\x65\160\164\151\157\156\x2f\101\x73\163\145\x72\x74\x45\170\143\x65\160\x74\x69\157\x6e\x2e\160\x68\x70";
class FlashAssert
{
    public static function not_empty($data, $message = '', $code = 0)
    {
        goto M3ifE;
        qoKVe:
        $message = empty($message) ? "\346\x95\xb0\346\215\xae\344\xb8\272\347\xa9\272" : $message;
        goto bJ0Op;
        JEeKY:
        vgo0O:
        goto Wlk5D;
        bJ0Op:
        throw new AssertException($message, $code);
        goto JEeKY;
        M3ifE:
        if (!(empty($data) || is_null($data))) {
            goto vgo0O;
        }
        goto qoKVe;
        Wlk5D:
    }
    public static function is_number($data, $message = '', $code = 0)
    {
        goto noiXx;
        v_Efp:
        $message = empty($message) ? "\344\270\215\xe6\x98\xaf\346\x95\xb0\345\255\227" : $message;
        goto FX21U;
        FX21U:
        throw new AssertException($message, $code);
        goto HkoGc;
        HkoGc:
        gtadx:
        goto zZITA;
        noiXx:
        if (!is_numeric($data)) {
            goto gtadx;
        }
        goto v_Efp;
        zZITA:
    }
    public static function is_mobile($data, $message = '', $code = 0)
    {
        goto NqsXK;
        k05ad:
        throw new AssertException($message, $code);
        goto Cp321;
        Cp321:
        i0owF:
        goto zpqnB;
        t1Xsi:
        $message = empty($message) ? "\350\257\267\350\xbe\223\345\x85\245\xe6\xad\xa3\347\241\xae\347\x9a\x84\346\x89\x8b\xe6\x9c\xba\345\217\xb7\xe7\240\201" : $message;
        goto k05ad;
        NqsXK:
        if (preg_match("\57\x31\133\63\x34\x35\x38\x5d\x7b\x31\x7d\134\x64\x7b\71\x7d\x24\57", $data)) {
            goto i0owF;
        }
        goto t1Xsi;
        zpqnB:
    }
    public static function not_null($data, $message = '', $code = 0)
    {
        goto R_Hr0;
        R_Hr0:
        if (!($data == null)) {
            goto Iv18s;
        }
        goto sAlz8;
        Cu33d:
        throw new AssertException($message, $code);
        goto vLYEk;
        sAlz8:
        $message = "\xe5\x86\x85\345\256\xb9\xe4\270\272\347\251\272";
        goto Cu33d;
        vLYEk:
        Iv18s:
        goto zP0Vk;
        zP0Vk:
    }
}
