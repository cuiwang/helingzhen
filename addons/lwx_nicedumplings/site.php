<?php
defined('IN_IA') or exit('Access Denied');
load()->func('tpl');
class lwx_nicedumplingsModuleSite extends WeModuleSite
{
    public function doWebShareSet()
    {
        global $_GPC, $_W;
        $weid      = $_W['uniacid'];
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == "display") {
            $shareInfo = pdo_fetch("select * from " . tablename("lwx_shareset") . " where weid = :id", array(
                ':id' => $weid
            ));
            include $this->template('shareset');
        } elseif ($operation == "post") {
            $shareset = $_GPC['shareset'];
            $id       = $_GPC['id'];
            if (empty($shareset)) {
                message("设置数据为空，请重新设置！");
            }
            if (empty($id)) {
                $shareset['weid'] = $weid;
                $result           = pdo_insert("lwx_shareset", $shareset);
            } else {
                $result = pdo_update("lwx_shareset", $shareset, array(
                    'id' => $id
                ));
            }
            if ($result) {
                message('分享配置成功！', $this->createWebUrl('ShareSet', array(
                    'op' => 'display'
                )), 'success');
            } else {
                message('分享配置失败！请重新配置！', $this->createWebUrl('ShareSet', array(
                    'op' => 'display'
                )), 'success');
            }
        }
    }
    public function doMobileList()
    {
        global $_GPC, $_W;
        $accountName = $_W['uniaccount'];
        $weid        = $_W['uniacid'];
        $shareInfo   = pdo_fetch("select * from " . tablename("lwx_shareset") . " where weid = :id", array(
            ':id' => $weid
        ));
        $authInfo    = $shareInfo['authinfo'];
        include $this->template('index');
    }
    public function doMobileBao()
    {
        global $_GPC, $_W;
        $weid      = $_W['uniacid'];
        $shareInfo = pdo_fetch("select * from " . tablename("lwx_shareset") . " where weid = :id", array(
            ':id' => $weid
        ));
        $authInfo  = $shareInfo['authinfo'];
        include $this->template('item');
    }
    public function doMobileAddInfo()
    {
        global $_GPC, $_W;
        $uniacid         = $_W['uniacid'];
        $openid          = $_W['openid'];
        $content         = $_GPC["content"];
        $data['weid']    = $uniacid;
        $data['openid']  = $openid;
        $data['content'] = $content;
        $result          = pdo_insert("lwx_addwish", $data);
        if ($result) {
            $infoid    = pdo_insertid();
            $uniacid   = $_W['uniacid'];
            $value     = $_W['account']['jssdkconfig'];
            $appId     = $value['appId'];
            $nonceStr  = $value['nonceStr'];
            $timestamp = $value['timestamp'];
            $signature = $value['signature'];
            $shareInfo = pdo_fetch("select * from " . tablename("lwx_shareset") . " where weid = :id", array(
                ':id' => $uniacid
            ));
            $authInfo  = $shareInfo['authinfo'];
            $title     = $shareInfo['title'];
            $thumb     = "http://" . $_SERVER['SERVER_NAME'] . "/attachment/" . $shareInfo["thumb"];
            $desc      = $shareInfo['desc'];
            $ok        = $shareInfo['okalert'];
            $chale     = $shareInfo['chalealert'];
            if (empty($ok)) {
                $ok = "分享成功！";
            }
            if (empty($chale)) {
                $chale = "取消分享!";
            }
            include $this->template('baoZongzi');
        } else {
            $shareInfo = pdo_fetch("select * from " . tablename("lwx_shareset") . " where weid = :id", array(
                ':id' => $uniacid
            ));
            $authInfo  = $shareInfo['authinfo'];
            include $this->template('item');
        }
    }
    public function doMobileGetInfo()
    {
        global $_GPC, $_W;
        $accountName = $_W['uniaccount'];
        $uniacid     = $_W['uniacid'];
        $id          = $_GPC["infoid"];
        $shareInfo   = pdo_fetch("select * from " . tablename("lwx_shareset") . " where weid = :id", array(
            ':id' => $uniacid
        ));
        $iffollow    = intval($shareInfo['iffollow']);
        $authInfo    = $shareInfo['authinfo'];
        if ($iffollow == 2) {
            if (empty($_W['openid']) || $_W['openid'] == "") {
                $openid = $this->doMobileGetOpenid();
                if (empty($openid)) {
                    if ($shareInfo['followurl'] == "") {
                        echo "<script language='javascript' type='text/javascript'>alert('请先关注{$accountName}公众号！');history.go(-1);</script>";
                        exit();
                    } else {
                        echo "<script language='javascript' type='text/javascript'>" . "window.location.href='{$shareInfo['followurl']}'" . "</script>";
                    }
                } else {
                    $fans     = mc_fansinfo($openid);
                    $iffollow = $fans['follow'];
                    if ($iffollow != 1) {
                        if ($shareInfo['followurl'] == "") {
                            echo "<script language='javascript' type='text/javascript'>alert('请先关注{$accountName}公众号！');history.go(-1);</script>";
                            exit();
                        } else {
                            echo "<script language='javascript' type='text/javascript'>" . "window.location.href='{$shareInfo['followurl']}'" . "</script>";
                        }
                    }
                }
            } else {
                $iffollow = $_W['fans']['follow'];
                if (empty($iffollow) || $iffollow == "") {
                    $fans     = mc_fansinfo($_W['openid']);
                    $iffollow = $fans['follow'];
                }
                if ($iffollow != 1) {
                    if ($shareInfo['followurl'] == "") {
                        echo "<script language='javascript' type='text/javascript'>alert('请先关注{$accountName}公众号！');history.go(-1);</script>";
                        exit();
                    } else {
                        echo "<script language='javascript' type='text/javascript'>" . "window.location.href='{$shareInfo['followurl']}'" . "</script>";
                    }
                }
            }
        }
        $info = pdo_fetch("select * from " . tablename("lwx_addwish") . " where weid = :weid and id=:id", array(
            ':weid' => $uniacid,
            ':id' => $id
        ));
        include $this->template('boZongzi');
    }
    public function doMobileGetOpenid()
    {
        global $_GPC, $_W;
        $weid    = $_W['uniacid'];
        $account = pdo_fetch("select * from " . tablename("account_wechats") . " where uniacid=:uniacid", array(
            ":uniacid" => $weid
        ));
        $appid   = $account['key'];
        $secret  = $account['secret'];
        $code    = $_REQUEST["code"];
        if (empty($code)) {
            $url      = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            $callback = urlencode($url);
            $state    = 1;
            $forward  = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
            header('location: ' . $forward);
            exit();
        }
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
        $ch            = curl_init();
        curl_setopt($ch, CURLOPT_URL, $get_token_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $json_obj = curl_exec($ch);
        curl_close($ch);
        $json_obj      = json_decode($json_obj, true);
        $access_token  = $json_obj['access_token'];
        $refresh_token = $json_obj['refresh_token'];
        $openid        = $json_obj['openid'];
        return $openid;
    }
}
