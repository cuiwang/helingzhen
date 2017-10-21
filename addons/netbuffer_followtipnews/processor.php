<?php

defined('IN_IA') or exit('Access Denied');
class Netbuffer_followtipnewsModuleProcessor extends WeModuleProcessor
{
    public static function getWeixinToken($uniacid)
    {
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($uniacid);
        return $accObj->fetch_token();
    }
    public function respond()
    {
        global $_W, $_GPC;
        load()->func('logging');
        $cfg        = $this->module["config"];
        $startcount = ($cfg['nbf_followtipnews_usr_startcount'] == null || $cfg['nbf_followtipnews_usr_startcount'] == "" ? 0 : $cfg['nbf_followtipnews_usr_startcount']);
        $usercount  = pdo_fetch("select count(*) total from" . tablename("mc_mapping_fans") . " where uniacid = :uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        $usercount  = $usercount['total'] + $startcount;
        $msg        = str_replace("usercount", $usercount, htmlspecialchars_decode($cfg["nbf_followtipnews_usr"]));
        $nickname   = "用户";
        $avastar    = "";
        load()->func('communication');
        $user_api_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . Netbuffer_followtipnewsModuleProcessor::getWeixinToken($_W["uniacid"]) . "&openid=" . $this->message['from'];
        $content      = ihttp_get($user_api_url);
        $info         = @json_decode($content['content'], true);
        $nickname     = $info["nickname"];
        $avastar      = $info["headimgurl"];
        $submsg       = str_replace("nick", $nickname, htmlspecialchars_decode($cfg["nbf_followtipnews_usr_subtitle"]));
        $submsg2      = str_replace("nick", $nickname, htmlspecialchars_decode($cfg["nbf_followtipnews_usr_subtitle2"]));
        $msg          = str_replace("nick", $nickname, $msg);
        $news         = array(
            array(
                "title" => $msg,
                "picurl" => $cfg["nbf_followtipnews_imgurl"],
                "url" => $cfg["nbf_followtipnews_url"]
            )
        );
        if (!empty($submsg) && strlen($submsg) > 1) {
            $news[count($news)] = array(
                "title" => $submsg,
                "picurl" => $avastar,
                "url" => $cfg["nbf_followtipnews_usr_subtitle_url"]
            );
        }
        if (!empty($submsg2) && strlen($submsg2) > 1) {
            $news[count($news)] = array(
                "title" => $submsg2,
                "picurl" => "",
                "url" => $cfg["nbf_followtipnews_usr_subtitle_url2"]
            );
        }
        return $this->respNews($news);
    }
}

?>