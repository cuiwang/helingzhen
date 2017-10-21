<?php
/**
 * 微募捐模块微站定义
 *
 * @author 刘靜
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('MODULE_NAME','jing_donation');
define('CSS_PATH', '../addons/'.MODULE_NAME.'/template/style/css/');
define('JS_PATH', '../addons/'.MODULE_NAME.'/template/style/js/');
define('IMG_PATH', '../addons/'.MODULE_NAME.'/template/style/images/');
class Jing_donationModuleSite extends WeModuleSite {
    public $t_adv = 'jing_donation_adv';
	public $t_donation = 'jing_donation';
    public $t_dynamic = 'jing_donation_dynamic';
    public $t_invitation = 'jing_donation_invitation';
    public $t_order = 'jing_donation_order';
    public $t_user = 'jing_donation_user';
    public $t_yxz = 'jing_donation_yxz';

    public function checkuser(){
        global $_W,$_GPC;
        $user = pdo_fetch("SELECT * FROM ".tablename($this->t_user)." WHERE uniacid=:uniacid AND openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$_W['openid']));
        if (empty($user) && $_GPC['do'] != 'Oauth' && $_GPC['c'] != 'site') {
            $fromurl = $_W['siteurl'];
            $url = (!empty($unisetting['oauth']['host']) ? ($unisetting['oauth']['host'] . $sitepath . '/') : $_W['siteroot'] . 'app/') . 'index.php?i='.$_W['uniacid'].'&c=entry&fromurl='.base64_encode($fromurl).'&do=Oauth&m=jing_donation';
            $callback = urlencode($url);
            $oauth_account = WeAccount::create($_W['account']['oauth']);
            $forward = $oauth_account->getOauthUserInfoUrl($callback, 'stat');
            header('Location: ' . $forward);
            exit();
        }
    }

    public function getDonationTiles() {
        global $_W;
        $urls = array();
        $donation = pdo_fetchall("SELECT id, title FROM " . tablename($this->t_donation) . " WHERE  enabled=1 AND uniacid = '{$_W['uniacid']}'");
        if (!empty($donation)) {
            foreach ($donation as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('detail', array('id' => $row['id'])));
            }
        }
        return $urls;
    }
    public function doMobileOauth(){
        global $_W,$_GPC;
        if (!empty($_GPC['code'])) {
            $code = $_GPC['code'];
            $oauth_account = WeAccount::create($_W['account']['oauth']);
            $oauth = $oauth_account->getOauthInfo($code);
            $userinfo = $oauth_account->getOauthUserInfo($oauth['access_token'], $oauth['openid']);
            if (!empty($userinfo['openid'])) {
                if (!empty($userinfo['headimgurl'])) {
                    $userinfo['headimgurl'] = rtrim($userinfo['headimgurl'], '0') . 132;
                }
                $insert = array(
                    'uniacid' => $_W['uniacid'],
                    'openid' => $userinfo['openid'],
                    'unionid' => $userinfo['unionid'],
                    'nickname' => stripcslashes($userinfo['nickname']),
                    'sex' => $userinfo['sex'],
                    'avatar' => $userinfo['headimgurl'],
                    'country' => $userinfo['country'],
                    'province' => $userinfo['province'],
                    'city' => $userinfo['city'],
                    );
                pdo_insert($this->t_user, $insert);
                $fromurl = base64_decode($_GPC['fromurl']);
                header('Location: ' . $fromurl);
            }else{
                message('非法访问.');
            }
        }else{
            message('非法访问.');
        }
    }
	public function doWebQuery() {
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename($this->t_donation) . ' WHERE `uniacid`=:uniacid AND `title` LIKE :title and enabled=1';
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':title'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['id'] = $row['id'];
            $r['title'] = $row['title'];
            $r['description'] = cutstr($row['description'], 30, '...');
            $r['thumb'] = toimage( $row['thumb'] );
            $row['entry'] = $r;
        }
        include $this->template('query');
    }
    public function gettime($time_s,$time_n){
		$strtime = '';
		$time = $time_n-$time_s;
		$day=floor($time/(3600*24));
		return $day.' 天';
	}

    public function payResult($params) {
        global $_W;
        $setting = pdo_fetchcolumn("SELECT settings FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid", array(':module' => 'jing_donation', ':uniacid' => $_W['uniacid']));
        $config = iunserializer($setting);
        if ($params['result'] == 'success' && $params['from'] == 'notify') {
            $fee = intval($params['fee']);
            $data = array('status' => $params['result'] == 'success' ? 1 : 0);
            $paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
            $data['paytype'] = $paytype[$params['type']];
            if ($params['type'] == 'wechat') {
                $data['transid'] = $params['tag']['transaction_id'];
            }
            $order = pdo_fetch("SELECT * FROM " . tablename($this->t_order) . " WHERE id = '{$params['tid']}'");
            if ($order['status'] != 1) {
                pdo_update($this->t_order, $data, array('id' => $params['tid']));
                $yxz = pdo_fetchcolumn("SELECT id FROM ".tablename($this->t_yxz)." WHERE uniacid=:uniacid AND did=:did AND openid=:openid",array(':uniacid'=>$order['uniacid'],':did'=>$order['did'],':openid'=>$order['openid']));
                $yxz_money = pdo_fetchcolumn("SELECT money FROM ".tablename($this->t_donation)." WHERE id=:id",array(':id'=>$order['did']));
                if (empty($yxz)) {
                    $insert = array(
                        'uniacid' => $order['uniacid'],
                        'did' => $order['did'],
                        'openid' => $order['openid'],
                        'yxz' => floor($order['price']/$yxz_money),
                        );
                    pdo_insert($this->t_yxz, $insert);
                }else{
                    $mymoney = $this->mymoney($order['did'],$order['openid']);
                    pdo_update($this->t_yxz, array('yxz'=>floor($mymoney/$yxz_money)), array('id'=>$yxz));
                }
                if (!empty($config['tpl1'])) {
                    $item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id",array(':id'=>$order['did']));
                    $mfirst = '慈善心相悦，公益心相印';
                    $mfoot = '爱心接力，牵起爱心的手';
                    $murl = '';
                    $mdata = array(
                        'first' => array(
                            'value' => $mfirst,
                            'color' => '#ff510'
                        ),
                        'keyword1' => array(
                            'value' => $item['title'],
                            'color' => '#ff510'
                        ),
                        'keyword2' => array(
                            'value' => date('Y-m-d H:i:s',time()),
                            'color' => '#ff510'
                        ),
                        'keyword3' => array(
                            'value' => $order['price'] . '元',
                            'color' => '#ff510'
                        ),
                        'remark' => array(
                            'value' => $mfoot ,
                            'color' => '#ff510'
                        ),
                    );
                    $acc = WeAccount::create();
                    $acc->sendTplNotice($order['openid'], $config['tpl1'], $mdata, $murl, $topcolor = '#FF683F');
                }
                if (!empty($config['tpl2']) && !empty($config['admin_openid'])) {
                    $item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id",array(':id'=>$order['did']));
                    $user = pdo_get($this->t_user, array('openid'=>$order['openid'], 'uniacid' => $order['uniacid']));
                    $mfirst = '您收到一个新的爱心捐赠';
                    $mfoot = '进入后台查看详情';
                    $murl = '';
                    $mdata = array(
                        'first' => array(
                            'value' => $mfirst,
                            'color' => '#ff510'
                        ),
                        'keyword1' => array(
                            'value' => $item['title'],
                            'color' => '#ff510'
                        ),
                        'keyword2' => array(
                            'value' => date('Y-m-d H:i:s',time()),
                            'color' => '#ff510'
                        ),
                        'keyword3' => array(
                            'value' => $user['nickname'] . '捐赠金额' . $order['price'] . '元',
                            'color' => '#ff510'
                        ),
                        'remark' => array(
                            'value' => $mfoot ,
                            'color' => '#ff510'
                        ),
                    );
                    $acc = WeAccount::create();
                    $acc->sendTplNotice($config['admin_openid'], $config['tpl2'], $mdata, $murl, $topcolor = '#FF683F');
                }
            }
            
        }
        if ($params['from'] == 'return') {
            $order = pdo_fetch("SELECT * FROM " . tablename($this->t_order) . " WHERE id = '{$params['tid']}'");
            if ($params['type'] == $credit) {
                message('支付成功！', $this->createMobileUrl('info',array('id'=>$order['did'])), 'success');
            } else {
                message('支付成功！', '../../app/' . $this->createMobileUrl('info',array('id'=>$order['did'])), 'success');
            }
        }
    }

    public function totalnum($did){
        $total_num = pdo_fetchcolumn("select count(*) from " . tablename($this->t_order) . " where did='{$did}' AND status=1");
        $total_num = isset($total_num) ? $total_num : 0;
        return $total_num;
    }

    public function totalusernum($did){
        $total_num = pdo_fetchall("select count(openid) from " . tablename($this->t_order) . " where did='{$did}' AND status=1 GROUP BY `openid`");
        $total_num = isset($total_num) ? count($total_num) : 0;
        return $total_num;
    }

    public function totalmoney($did){
        $total_money = pdo_fetchcolumn("select sum(price) from " . tablename($this->t_order) . " where did='{$did}' AND status=1");
        $total_money = isset($total_money) ? $total_money : 0.00;
        return $total_money;
    }

    public function mynum($did,$openid){
        $total_num = pdo_fetchcolumn("select count(*) from " . tablename($this->t_order) . " where did='{$did}' AND openid='{$openid}' AND status=1");
        $total_num = isset($total_num) ? $total_num : 0;
        return $total_num;
    }

    public function mymoney($did,$openid){
        $total_money = pdo_fetchcolumn("select sum(price) from " . tablename($this->t_order) . " where did='{$did}' AND openid='{$openid}' AND status=1");
        $total_money = isset($total_money) ? $total_money : 0.00;
        return $total_money;
    }

    public function totalinvitationnum($did){
        $total_num = pdo_fetchcolumn("select count(*) from " . tablename($this->t_invitation) . " where did='{$did}' AND status=1");
        $total_num = isset($total_num) ? $total_num : 0;
        return $total_num;
    }

    public function getYx($did,$openid){
        $yxz = pdo_fetchcolumn("SELECT yxz FROM ".tablename($this->t_yxz)." WHERE did=:did AND openid=:openid",array(':did'=>$did,':openid'=>$openid));
        $yxz = isset($yxz) ? $yxz : 0;
        return $yxz;
    }

    public function getYxNo($did,$openid){
        $yxz = pdo_fetchcolumn("SELECT yxz FROM ".tablename($this->t_yxz)." WHERE did=:did AND openid=:openid",array(':did'=>$did,':openid'=>$openid));
        $yxzno = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->t_yxz)." WHERE did=:did AND openid=:openid and yxz>=:yxz",array(':did'=>$did,':openid'=>$openid,':yxz'=>$yxz));
        return $yxzno;
    }
}