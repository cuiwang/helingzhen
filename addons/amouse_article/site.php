<?php
defined('IN_IA') or exit('Access Denied');
include_once IA_ROOT . '/addons/amouse_article/model.php';
define("AMOUSE_ARTICLE", "amouse_article");
define('AMOUSE_ARTICLE_ROOT', IA_ROOT . '/addons/amouse_article');
define("RES", "../addons/" . AMOUSE_ARTICLE . "/style/");
load()->model('payment');
function get_timelineauction($pubtime)
{
    $time = time();
    if (idate('Y', $time) != idate('Y', $pubtime)) {
        return date('Y-m-d', $pubtime);
    }
    $seconds = $time - $pubtime;
    $days    = idate('z', $time) - idate('z', $pubtime);
    if ($days == 0) {
        if ($seconds < 3600) {
            if ($seconds < 60) {
                if (3 > $seconds) {
                    return '刚刚';
                } else {
                    return $seconds . '秒前';
                }
            }
            return intval($seconds / 60) . '分钟前';
        }
        return idate('H', $time) - idate('H', $pubtime) . '小时前';
    }
    if ($days == 1) {
        return '昨天 ' . date('H:i', $pubtime);
    }
    if ($days == 2) {
        return '前天 ' . date('H:i', $pubtime);
    }
    if ($days < 7) {
        return $days . '天前';
    }
    return date('n-j H:i', $pubtime);
}
class Amouse_articleModuleSite extends WeModuleSite
{
    public function __construct()
    {
        $oauthuser       = $this->initCheckOauth();
        $this->oauthuser = $oauthuser;
    }
    public function doMobileJubao()
    {
        global $_GPC;
        $aid = $_GPC['artid'];
        include $this->template('report');
    }
    public function doMobileAjaxReport()
    {
        global $_GPC, $_W;
        $aid    = intval($_GPC['aid']);
        $openid = $_W['fans']['from_user'];
        if (empty($openid)) {
            $openid = getip();
        }
        $cate   = $_GPC['cate'];
        $cons   = $_GPC['cons'];
        $insert = array(
            'openid' => $openid,
            'aid' => $aid,
            'cate' => $cate,
            'cons' => $cons,
            'uniacid' => $_W['uniacid']
        );
        pdo_insert('fineness_article_report', $insert);
        die(json_encode(array(
            'result' => 'success'
        )));
    }
    public function doMobileLike()
    {
        global $_W, $_GPC;
        $weid      = $_W['uniacid'];
        $record_id = $_GPC['articleid'];
        $record    = pdo_fetch("SELECT * FROM " . tablename('fineness_article') . " WHERE id= $record_id ");
        if (empty($record)) {
            $res['ret'] = 501;
            return json_encode($res);
        }
        $openid = $_W['fans']['from_user'];
        if (empty($openid)) {
            $openid = getip();
        }
        if (!empty($record_id) && !empty($openid)) {
            $state = pdo_fetch("SELECT * FROM " . tablename('fineness_article_log') . " WHERE openid=:openid and aid=:aid and uniacid=:uniacid limit 1 ", array(
                ':openid' => $openid,
                ':aid' => $record_id,
                ':uniacid' => $weid
            ));
            if (empty($state['like'])) {
                pdo_update('fineness_article', 'zanNum=zanNum+1', array(
                    'id' => $record_id
                ));
                pdo_update('fineness_article_log', array(
                    'like' => $state['like'] + 1
                ), array(
                    'id' => $state['id']
                ));
                die(json_encode(array(
                    'result' => 200
                )));
            } else {
                pdo_update('fineness_article', 'zanNum=zanNum-1', array(
                    'id' => $record_id
                ));
                pdo_update('fineness_article_log', array(
                    'like' => $state['like'] - 1
                ), array(
                    'id' => $state['id']
                ));
                die(json_encode(array(
                    'result' => 201
                )));
            }
        }
    }
    public function doMobileAjaxcomment()
    {
        global $_W, $_GPC;
        $weid       = $_W['uniacid'];
        $aid        = $_GPC['articleid'];
        $set        = pdo_fetch("SELECT iscomment FROM " . tablename('fineness_sysset') . " WHERE weid=:weid limit 1", array(
            ':weid' => $weid
        ));
        $follow_url = $set['guanzhuUrl'];
        $is_follow  = false;
        $record     = pdo_fetch("SELECT * FROM " . tablename('fineness_article') . " WHERE id= $aid ");
        if (empty($record)) {
            $res['code'] = 501;
            $res['msg']  = "文章不存在或者已经被删除。";
            return json_encode($res);
        }
        load()->model('mc');
        $openid   = $_W['fans']['from_user'];
        $acc      = WeiXinAccount::create($_W['acid']);
        $userinfo = $acc->fansQueryInfo($openid);
        if (empty($userinfo) && empty($userinfo['nickname'])) {
            $res['code'] = 202;
            $res['msg']  = "您还没有关注，请关注后参与。";
            return json_encode($res);
        }
        $data = array(
            'weid' => $weid,
            'js_cmt_input' => $_GPC['js_cmt_input'],
            'status' => 0,
            'aid' => $aid,
            'author' => $userinfo['nickname'],
            'thumb' => $userinfo['headimgurl'],
            'openid' => $userinfo['openid'],
            'createtime' => time()
        );
        //if ($set && $set['iscomment'] == 1) {
        //    $data['status'] = 1;
        //}
        pdo_insert('fineness_comment', $data);
        $res['code'] = 200;
        $res['msg']  = "评论成功，由公众帐号筛选后显示！";
        return json_encode($res);
    }
    public function doMobileDelComment()
    {
        global $_W, $_GPC;
        $commentid = $_GPC['commentid'];
        $record    = pdo_fetch("SELECT * FROM " . tablename('fineness_comment') . " WHERE id= $commentid ");
        if (empty($record)) {
            $res['code'] = 501;
            $res['msg']  = "记录不存在或者已经被删除。";
            return json_encode($res);
        }
        $temp        = pdo_delete("fineness_comment", array(
            'id' => $commentid
        ));
        $res['code'] = 200;
        $res['msg']  = '删除成功';
        return json_encode($res);
    }
    public function doMobileAjaxpraise()
    {
        global $_W, $_GPC;
        $commentid = $_GPC['commentid'];
        $weid      = $_W['uniacid'];
        $record    = pdo_fetch("SELECT * FROM " . tablename('fineness_comment') . " WHERE id= $commentid ");
        if (empty($record)) {
            $res['code'] = 501;
            $res['msg']  = "记录不存在或者已经被删除。";
            return json_encode($res);
        }
        $openid = $_W['fans']['from_user'];
        if (empty($openid)) {
            $openid = getip();
        }
        if (!empty($commentid) && !empty($openid)) {
            $state = pdo_fetch("SELECT * FROM " . tablename('fineness_article_log') . " WHERE openid=:openid and aid=:aid and uniacid=:uniacid limit 1 ", array(
                ':openid' => $openid,
                ':aid' => $commentid,
                ':uniacid' => $weid
            ));
            if (empty($state['comment'])) {
                pdo_update('fineness_comment', 'praise_num=praise_num+1', array(
                    'id' => $commentid
                ));
                pdo_update('fineness_article_log', array(
                    'comment' => $state['comment'] + 1
                ), array(
                    'id' => $state['id']
                ));
                die(json_encode(array(
                    'code' => 200
                )));
            } else {
                pdo_update('fineness_comment', 'praise_num=praise_num-1', array(
                    'id' => $commentid
                ));
                pdo_update('fineness_article_log', array(
                    'comment' => $state['comment'] - 1
                ), array(
                    'id' => $state['id']
                ));
                die(json_encode(array(
                    'code' => 201
                )));
            }
        }
    }
    public function doMobileAjaxPay()
    {
        global $_W, $_GPC;
        $price = $_GPC['price'];
        if ($price == 0) {
            $price = 0.01;
        }
        $uniacid = $_W['uniacid'];
        $aid     = $_GPC['aid'];
        $article = pdo_fetch('select * from ' . tablename('fineness_article') . ' where weid=:weid AND id=:id', array(
            ':weid' => $uniacid,
            ':id' => $aid
        ));
        $openid  = $_W['fans']['from_user'];
        if (empty($openid)) {
            $openid               = getip();
            $userInfo['nickname'] = $openid;
            $userInfo['avatar']   = '../app/resource/images/heading.jpg';
        } else {
            load()->model('mc');
            $userInfo = mc_oauth_userinfo();
        }
        if (!empty($article)) {
            $orderno = date('YmdHis') . random(4, 1);
            $data    = array(
                'weid' => $uniacid,
                'ordersn' => $orderno,
                'price' => $price,
                'aid' => $aid,
                'author' => $userInfo['nickname'],
                'thumb' => $userInfo['avatar'],
                'openid' => $userInfo['openid'],
                'status' => 0,
                'createtime' => time()
            );
            if (pdo_insert("fineness_admire", $data)) {
                $oid         = pdo_insertid();
                $res['code'] = 200;
                $res['oid']  = $oid;
                $res['msg']  = 'sucess';
				$params =array(
				    'title' =>'zanshang',
					'uniontid'=>$oid,
					'fee'=>$price
				);
				$setting = uni_setting($_W['uniacid'], array('payment'));
				$options     = $setting['payment']['wechat'];
                $options['appid']  = $_W['account']['key'];
                $options['secret'] = $_W['account']['secret'];
				$wechat = wechat_build($params, $options);
				$res['wechat'] = $wechat;
                return json_encode($res);
            } else {
                $res['code'] = 0;
                $res['msg']  = "提交订单失败";
                return json_encode($res);
            }
        } else {
            $res['code'] = 0;
            $res['msg']  = "你要赞赏的文章不存在,请联系管理员";
            return json_encode($res);
        }
    }
    public function doMobileRrcodeurl()
    {
        global $_W, $_GPC;
        $url = $_GPC['url'];
        require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
        $errorCorrectionLevel = "L";
        $matrixPointSize      = "6";
        QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
        exit();
    }
    public function payResult($params)
    {
        global $_W;
        $uniacid         = $params['uniacid'];
        $data            = array(
            'status' => $params['result'] == 'success' ? 1 : 0
        );
        $paytype         = array(
            'credit' => '1',
            'wechat' => '2',
            'alipay' => '2',
            'delivery' => '3',
            'yunpay' => '4'
        );
        $data['paytype'] = $paytype[$params['type']];
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
        }
        if ($params['type'] == 'delivery') {
            $data['status'] = 1;
        }
        $order = pdo_fetch("SELECT * FROM " . tablename('fineness_admire') . " WHERE tid=:tid ", array(
            ':tid' => $params['tid']
        ));
        if ($params['result'] == 'success' && $params['from'] == 'notify') {
            if ($params['fee'] != $order['price']) {
                exit('用户支付的金额与订单金额不符合或已修改状态。');
            }
            pdo_update('fineness_admire', $data, array(
                'tid' => $params['tid']
            ));
        }
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                $url = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('detail', array(
                    'id' => $order['aid'],
                    'time' => time()
                )), 2);
                $url = str_replace("payment/yunpay/", '', $url);
                header('location:' . $url);
                exit;
            } else {
                $this->returnMessage('支付失败。', '../../app/' . $this->createMobileUrl('detail', array(
                    'id' => $order['aid'],
                    'time' => time()
                )), 'error');
            }
        }
    }
    public function doMobileTuijian()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $cfg  = $this->module['config'];
        $list = pdo_fetchall("SELECT * FROM " . tablename('wx_tuijian') . " WHERE weid=:weid ORDER BY createtime DESC ", array(
            ':weid' => $weid
        ));
        include $this->template('tuijian');
    }
    protected function returnMessage($msg, $redirect = '', $type = '')
    {
        global $_W;
        if ($redirect == 'refresh') {
            $redirect = $_W['script_name'] . '?' . $_SERVER['QUERY_STRING'];
        }
        if ($redirect == 'referer') {
            $redirect = referer();
        }
        if ($redirect == '') {
            $type = in_array($type, array(
                'success',
                'error',
                'info',
                'warn'
            )) ? $type : 'info';
        } else {
            $type = in_array($type, array(
                'success',
                'error',
                'info',
                'warn'
            )) ? $type : 'success';
        }
        if (empty($msg) && !empty($redirect)) {
            header('location: ' . $redirect);
        }
        $label = $type;
        if ($type == 'error') {
            $label = 'warn';
        }
        include $this->template('message');
        die;
    }
    public function get_contents($url)
    {
        $ch      = curl_init();
        $timeout = 100;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
    public function cut($from, $start, $end, $lt = false, $gt = false)
    {
        $str = explode($start, $from);
        if (isset($str['1']) && $str['1'] != '') {
            $str  = explode($end, $str['1']);
            $strs = $str['0'];
        } else {
            $strs = '';
        }
        if ($lt) {
            $strs = $start . $strs;
        }
        if ($gt) {
            $strs .= $end;
        }
        return $strs;
    }
    public function getSysett($weid)
    {
        return pdo_fetch("SELECT * FROM " . tablename('fineness_sysset') . " WHERE weid=:weid limit 1", array(
            ':weid' => $weid
        ));
    }
    public function doWebjiaocheng()
    {
        include $this->template('help');
    }
    public function oauthuniacid()
    {
        global $_W, $_GPC;
        if ($_W['account']['level'] == 4) {
            $uniacid = $_W['uniacid'];
        } elseif ($_W['oauth_account']['level'] == 4) {
            $oauth_acid      = $_W['oauth_account']['acid'];
            $account_wechats = pdo_fetch("SELECT uniacid FROM " . tablename('account_wechats') . " WHERE acid = :acid ", array(
                ':acid' => $oauth_acid
            ));
            $uniacid         = $account_wechats['uniacid'];
        } else {
            $uniacid = $_W['uniacid'];
        }
        return $uniacid;
    }
    private function initCheckOauth()
    {
        global $_GPC, $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        load()->model('mc');
        $openid   = '';
        $nickname = '';
        $avatar   = '';
        $follow   = '';
        if (!empty($_W['member']['uid'])) {
            $member = mc_fetch(intval($_W['member']['uid']), array(
                'avatar',
                'nickname'
            ));
            if (!empty($member)) {
                $avatar   = $member['avatar'];
                $nickname = $member['nickname'];
            }
        } //易 福 源 码 网 
        if (empty($avatar) || empty($nickname)) {
            $fan = mc_fansinfo($_W['openid']);
            if (!empty($fan)) {
                $avatar   = $fan['avatar'];
                $nickname = $fan['nickname'];
                $openid   = $fan['openid'];
                $follow   = $fan['follow'];
            }
        }
        if (empty($avatar) || empty($nickname) || empty($openid) || empty($follow)) {
            $userinfo = mc_oauth_userinfo();
            if ($_W['account']['level'] != 4 && !is_array($userinfo) && empty($userinfo['avatar'])) {
                message('非认证服务号，请至“功能选项”-“借用oAuth权限”-“选择公众号”，借用其他认证服务号权限。', '', 'error');
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['avatar'])) {
                $avatar = $userinfo['avatar'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['nickname'])) {
                $nickname = $userinfo['nickname'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['openid'])) {
                $openid = $userinfo['openid'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['follow'])) {
            }
        }
        $oauthuser             = array();
        $oauthuser['avatar']   = $avatar;
        $oauthuser['nickname'] = $nickname;
        $oauthuser['openid']   = $openid;
        $from_useropenid       = authcode($_COOKIE['from_useropenid'], 'DECODE', $_W['account']['uniaccount']['token']);
        if (empty($_W['fans']['follow']) && $from_useropenid != $openid && !empty($from_useropenid)) {
            $user = pdo_fetch("SELECT id FROM " . tablename($this->table_fans) . " WHERE openid = :openid AND uniacid=:uniacid", array(
                ':openid' => $from_useropenid,
                ':uniacid' => $_W['uniacid']
            ));
            if (!empty($user)) {
                $oauthuser['follow'] = 1;
            } else {
            }
        } else {
            $oauthuser['follow'] = $_W['fans']['follow'];
        }
        if (!empty($from_useropenid) && $from_useropenid != $openid) {
            $oauthuser['from_user'] = $from_useropenid;
        } else {
            $oauthuser['from_user'] = $openid;
        }
        return $oauthuser;
    }
}

?>