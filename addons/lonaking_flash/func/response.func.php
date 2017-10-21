<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

goto OiG5O;
iqebN:
function error_msg($msg, $redirect = '', $type = '')
{
}
goto cjSFq;
yJUmD:
function return_success($data)
{
    die(json_encode(array("\163\164\141\164\x75\163" => 200, "\x6d\x65\x73\163\141\147\145" => "\163\x75\143\x63\x65\x73\x73", "\x64\141\164\141" => $data)));
}
goto GKpdX;
Fe9jz:
function return_redirect($url, $message = "\345\x8d\263\345\260\206\350\267\263\350\275\254")
{
    die(json_encode(array("\x73\164\141\x74\165\163" => 200, "\155\x65\x73\163\x61\147\145" => $message, "\144\x61\x74\141" => '', "\162\145\144\151\x72\x65\x63\164" => $url)));
}
goto iqebN;
GKpdX:
function return_json($data = null, $message = "\x73\165\143\143\x65\163\163", $status = 200)
{
    die(json_encode(array("\163\164\141\x74\165\163" => $status, "\x6d\145\x73\x73\x61\147\x65" => $message, "\144\x61\x74\x61" => $data)));
}
goto Fe9jz;
OiG5O:
function return_error($status, $message)
{
    die(json_encode(array("\x73\164\141\164\x75\163" => $status, "\x6d\x65\163\163\x61\x67\x65" => $message, "\144\x61\x74\141" => null)));
}
goto yJUmD;
cjSFq:
function success_msg($msg, $redirect = '', $type = '')
{
}
