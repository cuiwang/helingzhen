<?php

defined('IN_IA') or die('Access Denied');
define('MD_ROOT', '../addons/cy163_goodssales/static/new');
define('MD_ROOTN', '../addons/cy163_goodssales/static/messi');
define('BEST_ORDER', 'cygoodssale_order');
define('BEST_ORDER_GOODS', 'cygoodssale_order_goods');
define('BEST_GOODS', 'cygoodssale_goods');
define('BEST_ADDRESS', 'cygoodssale_address');
define('BEST_ACCOUNT', 'cygoodssale_memberaccount');
define('BEST_MEMBER', 'cygoodssale_member');
define('BEST_MERCHANT', 'cygoodssale_merchant');
define('BEST_ADV', 'cygoodssale_adv');
class Cy163_goodssalesModuleSite extends WeModuleSite
{
	public $setting = array();
	public function __construct()
	{
		global $_W;
		$this->setting = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_set') . " WHERE weid = {$_W['uniacid']} LIMIT 1");
	}
	public function doMobileMerchantregister()
	{
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$hasmerchant = pdo_fetch("SELECT * FROM " . tablename("cygoodssale_merchant") . " WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND openid != ''");
		$setting = $this->setting;
		$nowtime = TIMESTAMP;
		if ($operation == 'display') {
			$fans = $_W['fans']['tag'];
			include $this->template('register');
		} elseif ($operation == 'do') {
			if ($_W['fans']['from_user'] == '') {
				$result['error'] = 1;
				$result['message'] = '未获取到您的微信信息！';
				echo json_encode($result, true);
				die;
			}
			$merchant_id = intval($_GPC['merchant_id']);
			if ($merchant_id == 0) {
				if (!empty($hasmerchant)) {
					$result['error'] = 1;
					$result['message'] = '请不要重复提交！';
					echo json_encode($result, true);
					die;
				}
			}
			$name = trim($_GPC['name']);
			$telphone = trim($_GPC['telphone']);
			$paytype = intval($_GPC['paytype']);
			if ($paytype > 4 || $paytype < 0) {
				$result['error'] = 1;
				$result['message'] = '请选择正确的商户套餐！';
				echo json_encode($result, true);
				die;
			}
			if ($paytype == 0 && $hasmerchant['hasfree'] == 1) {
				$result['error'] = 1;
				$result['message'] = '您的免费试用已经到期！';
				echo json_encode($result, true);
				die;
			}
			if ($paytype == 0) {
				$hasfree = 1;
				$status = 1;
				$expirationtime = $nowtime + $setting['days'] * 3600 * 24;
			} else {
				$hasfree = !empty($hasmerchant) ? $hasmerchant['hasfree'] : 0;
				$status = !empty($hasmerchant) ? $hasmerchant['status'] : 0;
				$expirationtime = !empty($hasmerchant) ? $hasmerchant['expirationtime'] : 0;
			}
			if (empty($name)) {
				$result['error'] = 1;
				$result['message'] = '请填写商户名称！';
				echo json_encode($result, true);
				die;
			}
			if (!$this->isMobile($telphone)) {
				$result['error'] = 1;
				$result['message'] = '请填写正确的手机号码！';
				echo json_encode($result, true);
				die;
			}
			$data = array('weid' => $_W['uniacid'], 'name' => $name, 'openid' => $_W['fans']['from_user'], 'telphone' => $telphone, 'addtime' => TIMESTAMP, 'status' => $status, 'expirationtime' => $expirationtime, 'hasfree' => $hasfree);
			if ($merchant_id == 0) {
				pdo_insert('cygoodssale_merchant', $data);
				$merchant_id = pdo_insertid();
			} else {
				pdo_update('cygoodssale_merchant', $data, array('id' => $merchant_id));
			}
			if ($paytype > 0) {
				if ($paytype == 1) {
					$mprice = $setting['monthprice'];
				}
				if ($paytype == 2) {
					$mprice = $setting['jiduprice'];
				}
				if ($paytype == 3) {
					$mprice = $setting['bannianprice'];
				}
				if ($paytype == 4) {
					$mprice = $setting['yearprice'];
				}
				$ordersn = date('Ymd') . random(6, 1);
				$datamerchant['merchant_id'] = $merchant_id;
				$datamerchant['weid'] = $_W['uniacid'];
				$datamerchant['paytype'] = $paytype;
				$datamerchant['status'] = 0;
				$datamerchant['price'] = $mprice;
				$datamerchant['ordersn'] = $ordersn;
				$datamerchant['from_user'] = $_W['fans']['tag']['openid'];
				$datamerchant['createtime'] = $nowtime;
				$morderid = pdo_insert('cygoodssale_sqorder', $datamerchant);
			} else {
				$ordersn = '';
			}
			$result['error'] = 0;
			$result['paytype'] = $paytype;
			$result['ordersn'] = $ordersn;
			$result['message'] = '恭喜您，提交成功！';
			echo json_encode($result, true);
			die;
		}
	}
	public function _checkMember($w)
	{
		$member = pdo_fetch("SELECT * FROM " . tablename(BEST_MEMBER) . " WHERE openid = '{$w['fans']['from_user']}' AND weid = {$w['uniacid']}");
		return $member;
	}
	public function doMobileMy()
	{
		global $_GPC, $_W;
		if (empty($_W['fans']['from_user'])) {
			message("请在微信浏览器打开！");
		}
		$merchant_id = intval($_GPC['id']);
		$member = pdo_fetch("SELECT * FROM " . tablename(BEST_MEMBER) . " WHERE openid = '{$_W['fans']['from_user']}' AND weid = {$_W['uniacid']}");
		if (empty($member)) {
			$data['weid'] = $_W['uniacid'];
			$data['openid'] = $_W['fans']['from_user'];
			$data['nickname'] = $_W['fans']['tag']['nickname'];
			$data['avatar'] = $_W['fans']['tag']['avatar'];
			$data['regtime'] = TIMESTAMP;
			pdo_insert(BEST_MEMBER, $data);
			$member = pdo_fetch("SELECT avatar FROM " . tablename(BEST_MEMBER) . " WHERE openid = '{$_W['fans']['from_user']}' AND weid = {$_W['uniacid']}");
		}
		$ordernum1 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_ORDER) . " WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 0");
		$ordernum2 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_ORDER) . " WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 1");
		$ordernum3 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_ORDER) . " WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 2");
		$ordernum4 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_ORDER) . " WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 4");
		include $this->template('mine');
	}
	public function doMobileSqmerchant()
	{
		include $this->template('sqmerchant');
	}
	public function doMobileMyorder()
	{
		include_once 'inc/mobile/myorder.php';
	}
	public function doMobileMyaccount()
	{
		include_once 'inc/mobile/myaccount.php';
	}
	public function doMobileMyaddress()
	{
		include_once 'inc/mobile/myaddress.php';
	}
	public function doMobileGetmedia()
	{
		global $_W, $_GPC;
		include_once '../addons/cy163_goodssales/ImageCrop.class.php';
		$access_token = WeAccount::token();
		$media_id = $_GPC['media_id'];
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $media_id;
		$updir = "../attachment/images/" . $_W['uniacid'] . "/" . date("Y", time()) . "/" . date("m", time()) . "/";
		if (!file_exists($updir)) {
			mkdir($updir, 511, true);
		}
		$randimgurl = "images/" . $_W['uniacid'] . "/" . date("Y", time()) . "/" . date("m", time()) . "/" . date('YmdHis') . rand(1000, 9999) . '.jpg';
		$targetName = "../attachment/" . $randimgurl;
		$ch = curl_init($url);
		$fp = fopen($targetName, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		if (file_exists($targetName)) {
			$resarr['error'] = 0;
			$tarwidth = intval($_GPC['tarwidth']);
			$tarheight = intval($_GPC['tarheight']);
			$ic = new ImageCrop($targetName, $targetName);
			$ic->Crop($tarwidth, $tarheight, 2);
			$ic->SaveImage();
			$ic->destory();
			if (!empty($_W['setting']['remote']['type'])) {
				load()->func('file');
				$remotestatus = file_remote_upload($randimgurl, true);
				if (is_error($remotestatus)) {
					$resarr['error'] = 1;
					$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
					file_delete($randimgurl);
					die(json_encode($resarr));
				} else {
					file_delete($randimgurl);
					$resarr['realimgurl'] = $randimgurl;
					$resarr['imgurl'] = tomedia($randimgurl);
					$resarr['message'] = '上传成功';
					die(json_encode($resarr));
				}
			}
			$resarr['realimgurl'] = $randimgurl;
			$resarr['imgurl'] = tomedia($randimgurl);
			$resarr['message'] = '上传成功';
		} else {
			$resarr['error'] = 1;
			$resarr['message'] = '上传失败';
		}
		echo json_encode($resarr, true);
		die;
	}
	public function doMobileChosekefu()
	{
		global $_W, $_GPC;
		$goodsid = intval($_GPC['goodsid']);
		$cservicelist = pdo_fetchall("SELECT b.*,a.* FROM " . tablename('cygoodssale_goodscservice') . " as a," . tablename('cygoodssale_cservice') . " as b WHERE a.goodsid = {$goodsid} AND a.weid = {$_W['uniacid']} AND a.cserviceid = b.id ORDER BY b.displayorder DESC");
		include $this->template('chosekefu');
	}
	public function doMobileDetail()
	{
		global $_W, $_GPC;
		$shareopenid = trim($_GPC['shareopenid']);
		$goodsid = intval($_GPC['id']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods') . " WHERE id = :id AND weid = :weid", array(':id' => $goodsid, ':weid' => $_W['uniacid']));
		if (empty($goods)) {
			message("不存在该商品！");
		}
		if ($goods['merchant_id']) {
			$merchant = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_merchant') . " WHERE id = {$goods['merchant_id']}");
		}
		$nowtime = TIMESTAMP;
		if ($goods['istime'] == 1 && $nowtime < $goods['timeend']) {
			if ($goods['timestart'] > TIMESTAMP) {
				$djs = '距离开抢还有';
				$djstime = $goods['timestart'] - TIMESTAMP;
			}
			if ($goods['timestart'] < TIMESTAMP && TIMESTAMP < $goods['timeend']) {
				$djs = '后结束';
				$djstime = $goods['timeend'] - TIMESTAMP;
			}
		}
		pdo_query("update " . tablename('cygoodssale_goods') . " set viewcount=viewcount+1 where id={$goodsid} and weid={$_W['uniacid']}");
		$options = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_goods_option') . " WHERE goodsid = {$goodsid} ORDER BY displayorder ASC");
		$piclist = array();
		if ($goods['thumb_url'] != 'N;') {
			$urls = unserialize($goods['thumb_url']);
			if (is_array($urls)) {
				foreach ($urls as $p) {
					$piclist[] = is_array($p) ? $p['attachment'] : $p;
				}
			}
		}
		$piclistdes = array();
		if ($goods['thumbsdes'] != 'N;') {
			$urlsdes = unserialize($goods['thumbsdes']);
			if (is_array($urlsdes)) {
				foreach ($urlsdes as $p) {
					$piclistdes[] = is_array($p) ? $p['attachment'] : $p;
				}
			}
		}
		$cservicelist = pdo_fetchall("SELECT id FROM " . tablename('cygoodssale_goodscservice') . " WHERE weid = {$_W['uniacid']} AND goodsid = {$goodsid}");
		$comments = pdo_fetchall("SELECT a.*,b.nickname,b.avatar FROM " . tablename('cygoodssale_goods_comment') . " as a," . tablename('cygoodssale_member') . " as b WHERE a.from_user = b.openid AND a.goodsid = {$goodsid} LIMIT 10");
		$shareres['title'] = $goods['title'];
		$thumbs = unserialize($goods['thumb_url']);
		$shareres['thumb'] = tomedia($thumbs[0]);
		$shareres['des'] = $goods['description'];
		$shareres['url'] = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl('detail', array('id' => $goodsid, 'shareopenid' => $_W['fans']['from_user'])));
		include $this->template('pro_info');
	}
	public function doMobileGetspecprice()
	{
		global $_W, $_GPC;
		$goodsid = intval($_GPC['id']);
		$optionval = trim($_GPC['optionval']);
		$specs = pdo_fetch("SELECT normalprice,stock FROM " . tablename('cygoodssale_goods_option') . " WHERE id = {$optionval} AND goodsid = {$goodsid}");
		if ($specs) {
			$result['error'] = 0;
			$result['goodsprice'] = $specs['normalprice'];
			$result['stock'] = $specs['stock'];
		} else {
			$result['error'] = 1;
		}
		echo json_encode($result, true);
		die;
	}
	public function doMobileAddcart()
	{
		global $_W, $_GPC;
		$goodsid = intval($_GPC['id']);
		$buynum = intval($_GPC['buynum']);
		$goods = pdo_fetch("SELECT id,deleted,total,normalprice,maxbuy,status,timestart,timeend,istime,xiangounum FROM " . tablename('cygoodssale_goods') . " WHERE id = {$goodsid}");
		if (empty($goods) || $goods['deleted'] == 1) {
			$result['error'] = 1;
			$result['message'] = '抱歉，该商品不存在或是已经被删除！';
			echo json_encode($result, true);
			die;
		}
		if ($goods['status'] == 0) {
			$result['error'] = 1;
			$result['message'] = '抱歉，该商品已经下架！';
			echo json_encode($result, true);
			die;
		}
		if ($goods['istime'] == 1) {
			$nowtime = TIMESTAMP;
			if ($nowtime < $goods['timestart'] || $nowtime > $goods['timeend']) {
				$result['error'] = 1;
				$result['message'] = '抱歉，该商品只能在' . date('Y-m-d H:i:s', $goods['timestart']) . '至' . date('Y-m-d H:i:s', $goods['timeend']) . '时间段内购买！';
				echo json_encode($result, true);
				die;
			}
		}
		if ($goods['xiangounum'] > 0) {
			$hasbuy = pdo_fetchcolumn("SELECT SUM(a.total) FROM " . tablename('cygoodssale_order_goods') . " as a," . tablename('cygoodssale_order') . " as b WHERE a.weid = {$_W['uniacid']} AND a.orderid = b.id AND b.from_user = '{$_W['fans']['from_user']}' AND a.goodsid = {$goodsid}");
			if ($goods['xiangounum'] - $hasbuy < $buynum) {
				$result['error'] = 1;
				$result['message'] = '该商品每人最多购买' . $goods['xiangounum'] . '件啦！';
				echo json_encode($result, true);
				die;
			}
		}
		$options = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_goods_option') . " WHERE goodsid = {$goodsid} ORDER BY displayorder ASC");
		if ($options) {
			$optionval = intval($_GPC['optionval']);
			$seloptions = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods_option') . " WHERE id = {$optionval}");
			if (empty($seloptions)) {
				$result['error'] = 1;
				$result['message'] = '请选择正确的商品规格！';
				echo json_encode($result, true);
				die;
			}
			if ($seloptions['stock'] < $buynum) {
				$result['error'] = 1;
				$result['message'] = '库存不足！';
				echo json_encode($result, true);
				die;
			}
			$goodsprice = $seloptions['normalprice'];
		} else {
			if ($goods['total'] < $buynum) {
				$result['error'] = 1;
				$result['message'] = '库存不足！';
				echo json_encode($result, true);
				die;
			}
			$goodsprice = $goods['normalprice'];
		}
		if ($goodsprice == 0) {
			$result['error'] = 1;
			$result['message'] = '商品价格为0不能购买！';
			echo json_encode($result, true);
			die;
		}
		if ($buynum > $goods['maxbuy'] && $goods['maxbuy'] != 0) {
			$result['error'] = 1;
			$result['message'] = '该商品单次最多购买' . $goods['maxbuy'] . '件！';
			echo json_encode($result, true);
			die;
		}
		$result['error'] = 0;
		$result['message'] = '可以购买！';
		echo json_encode($result, true);
		die;
	}
	public function doMobileBuy()
	{
		global $_GPC, $_W;
		$shareopenid = trim($_GPC['shareopenid']);
		$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			message('请在微信浏览器中打开！', '', 'error');
		}
		$goodsid = intval($_GPC['goodsid']);
		$buynum = intval($_GPC['buynum']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods') . " WHERE id = :id", array(':id' => $goodsid));
		$storeres = pdo_fetch("SELECT name FROM " . tablename(BEST_MERCHANT) . " WHERE id = {$goods['merchant_id']}");
		$member = pdo_fetch("SELECT * FROM " . tablename(BEST_MEMBER) . " WHERE openid = '{$_W['fans']['from_user']}' AND weid = {$_W['uniacid']}");
		$goodsimages = unserialize($goods['thumb_url']);
		$goodsimage = $goodsimages[0];
		if (empty($goods) || $goods['deleted'] == 1) {
			message('抱歉，该商品不存在或是已经被删除！', '', 'error');
		}
		if ($goods['status'] == 0) {
			message('抱歉，该商品已经下架！', '', 'error');
		}
		if ($goods['istime'] == 1) {
			$nowtime = TIMESTAMP;
			if ($nowtime < $goods['timestart'] || $nowtime > $goods['timeend']) {
				message('抱歉，该商品只能在' . date('Y-m-d H:i:s', $goods['timestart']) . '至' . date('Y-m-d H:i:s', $goods['timeend']) . '时间段内购买！', '', 'error');
			}
		}
		if ($goods['xiangounum'] > 0) {
			$hasbuy = pdo_fetchcolumn("SELECT SUM(a.total) FROM " . tablename('cygoodssale_order_goods') . " as a," . tablename('cygoodssale_order') . " as b WHERE a.weid = {$_W['uniacid']} AND a.orderid = b.id AND b.from_user = '{$_W['fans']['from_user']}' AND a.goodsid = {$goodsid}");
			if ($goods['xiangounum'] - $hasbuy < $buynum) {
				message('该商品每人最多购买' . $goods['xiangounum'] . '件啦！', '', 'error');
			}
		}
		$options = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_goods_option') . " WHERE goodsid = {$goodsid} ORDER BY displayorder ASC");
		if ($options) {
			$optionval = intval($_GPC['optionval']);
			$seloptions = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods_option') . " WHERE id = {$optionval}");
			if (empty($seloptions)) {
				message('请选择正确的商品规格！', '', 'error');
			}
			if ($seloptions['stock'] < $buynum) {
				message('库存不足！', '', 'error');
			}
			$goodsprice = $seloptions['normalprice'];
		} else {
			if ($goods['total'] < $buynum) {
				message('库存不足！', '', 'error');
			}
			$goodsprice = $goods['normalprice'];
		}
		if ($goodsprice == 0) {
			message('商品价格为0不能购买！', '', 'error');
		}
		if ($buynum > $goods['maxbuy'] && $goods['maxbuy'] != 0) {
			message('该商品单次最多购买' . $goods['maxbuy'] . '件！', '', 'error');
		}
		$allprice = $goodsprice * $buynum;
		$yunfei = $goods['yunfei'];
		$allallprice = $allprice + $yunfei;
		$address = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_address') . " WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' AND isauto = 1");
		if (empty($address)) {
			$address = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_address') . " WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' LIMIT 1");
		}
		$addresslist = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_address') . " WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' ORDER BY  isauto desc");
		include $this->template('order_sub');
	}
	public function isMobile($mobile)
	{
		if (!is_numeric($mobile)) {
			return false;
		}
		return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
	}
	public function doMobileDobuy()
	{
		global $_GPC, $_W;
		$shareopenid = trim($_GPC['shareopenid']);
		$openid = $_W['fans']['from_user'];
		$goodsid = intval($_GPC['goodsid']);
		$buynum = intval($_GPC['buynum']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods') . " WHERE id = :id", array(':id' => $goodsid));
		$paytype = intval($_GPC['paytype']);
		if ($paytype != 1 && $paytype != 2) {
			$result['error'] = 1;
			$result['message'] = '请选择正确的支付方式！';
			echo json_encode($result, true);
			die;
		}
		if (empty($goods) || $goods['deleted'] == 1) {
			$result['error'] = 1;
			$result['message'] = '抱歉，该商品不存在或是已经被删除！';
			echo json_encode($result, true);
			die;
		}
		if ($goods['status'] == 0) {
			$result['error'] = 1;
			$result['message'] = '抱歉，该商品已经下架！';
			echo json_encode($result, true);
			die;
		}
		if ($goods['istime'] == 1) {
			$nowtime = TIMESTAMP;
			if ($nowtime < $goods['timestart'] || $nowtime > $goods['timeend']) {
				$result['error'] = 1;
				$result['message'] = '抱歉，该商品只能在' . date('Y-m-d H:i:s', $goods['timestart']) . '至' . date('Y-m-d H:i:s', $goods['timeend']) . '时间段内购买！';
				echo json_encode($result, true);
				die;
			}
		}
		if ($goods['xiangounum'] > 0) {
			$hasbuy = pdo_fetchcolumn("SELECT SUM(a.total) FROM " . tablename('cygoodssale_order_goods') . " as a," . tablename('cygoodssale_order') . " as b WHERE a.weid = {$_W['uniacid']} AND a.orderid = b.id AND b.from_user = '{$_W['fans']['from_user']}' AND a.goodsid = {$goodsid}");
			if ($goods['xiangounum'] - $hasbuy < $buynum) {
				$result['error'] = 1;
				$result['message'] = '该商品每人最多购买' . $goods['xiangounum'] . '件啦！';
				echo json_encode($result, true);
				die;
			}
		}
		$realname = trim($_GPC['realname']);
		$telphone = trim($_GPC['telphone']);
		if ($goods['ishexiao'] == 1) {
			if (empty($realname)) {
				$result['error'] = 1;
				$result['message'] = '请填写联系人！';
				echo json_encode($result, true);
				die;
			} else {
				$address = $realname . '|' . $telphone . '||||';
				$newaddress = '核销订单不需要收货地址';
			}
			pdo_update(BEST_MEMBER, array('realname' => $realname, 'telphone' => $telphone), array('weid' => $_W['uniacid'], 'openid' => $_W['fans']['from_user']));
		} else {
			$province = trim($_GPC['province']);
			$city = trim($_GPC['city']);
			$district = trim($_GPC['district']);
			$address = trim($_GPC['address']);
			if (empty($realname) || empty($telphone) || empty($province) || empty($city) || empty($address)) {
				$result['error'] = 1;
				$result['message'] = '请填写完整的收货信息！';
				echo json_encode($result, true);
				die;
			} else {
				if (empty($district)) {
					$address = $realname . '|' . $telphone . '|' . $province . '|' . $city . '|' . $address;
				} else {
					$address = $realname . '|' . $telphone . '|' . $province . '|' . $city . '|' . $district . '|' . $address;
				}
				$newaddress = $province . $city . $district . $_GPC['address'];
			}
		}
		if (!$this->isMobile($telphone)) {
			$result['error'] = 1;
			$result['message'] = '请输入正确的手机号码！';
			echo json_encode($result, true);
			die;
		}
		$options = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_goods_option') . " WHERE goodsid = {$goodsid} ORDER BY displayorder ASC");
		if ($options) {
			$optionval = intval($_GPC['optionval']);
			$seloptions = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods_option') . " WHERE id = {$optionval}");
			if (empty($seloptions)) {
				message('请选择正确的商品规格！', '', 'error');
			}
			if ($seloptions['stock'] < $buynum) {
				message('库存不足！', '', 'error');
			}
			$goodsprice = $seloptions['normalprice'];
			$optionid = $seloptions['id'];
			$optionname = $seloptions['title'];
		} else {
			if ($goods['total'] < $buynum) {
				message('库存不足！', '', 'error');
			}
			$goodsprice = $goods['normalprice'];
		}
		if ($goodsprice == 0) {
			$result['error'] = 1;
			$result['message'] = '商品价格为0不能购买！';
			echo json_encode($result, true);
			die;
		}
		if ($buynum > $goods['maxbuy'] && $goods['maxbuy'] != 0) {
			$result['error'] = 1;
			$result['message'] = '该商品单次最多购买' . $goods['maxbuy'] . '件！';
			echo json_encode($result, true);
			die;
		}
		$allprice = $goodsprice * $buynum;
		$dispatchprice = $goods['yunfei'];
		$ordersn = date('Ymd') . random(4, 1);
		$data = array('weid' => $_W['uniacid'], 'from_user' => $openid, 'ordersn' => $ordersn, 'goodsprice' => $allprice, 'dispatchprice' => $dispatchprice, 'price' => $allprice + $dispatchprice, 'remark' => trim($_GPC['remark']), 'address' => $address, 'status' => 0, 'createtime' => TIMESTAMP, 'paytype' => $paytype, 'ishexiao' => $goods['ishexiao'], 'merchant_id' => $goods['merchant_id']);
		if (!empty($shareopenid) && $goods['fenxiaoprice'] > 0 && $goods['isdistribution'] == 1) {
			$data['shareopenid'] = $shareopenid;
			$data['fenxiaoprice'] = $goods['fenxiaoprice'] * $buynum;
		}
		pdo_insert('cygoodssale_order', $data);
		$orderid = pdo_insertid();
		$d = array('weid' => $_W['uniacid'], 'goodsid' => $goodsid, 'orderid' => $orderid, 'total' => $buynum, 'price' => $goodsprice, 'createtime' => TIMESTAMP, 'optionid' => $optionid, 'optionname' => $optionname);
		pdo_insert('cygoodssale_order_goods', $d);
		$tpllist = pdo_fetch("SELECT id FROM" . tablename('cygoodssale_tplmessage_tpllist') . " WHERE tplbh = 'OPENTM207018253' AND uniacid = {$_W['uniacid']}");
		$setting = $this->setting;
		if (!empty($tpllist) && $setting['istplon'] == 1) {
			$arrmsg = array('openid' => $openid, 'topcolor' => '#980000', 'first' => '恭喜您下单成功', 'firstcolor' => '', 'keyword1' => $ordersn, 'keyword1color' => '', 'keyword2' => $allprice + $dispatchprice, 'keyword2color' => '', 'keyword3' => $realname, 'keyword3color' => '', 'keyword4' => $telphone, 'keyword4color' => '', 'keyword5' => $newaddress, 'keyword5color' => '', 'remark' => '', 'remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("myorder")));
			$this->sendtemmsg($tpllist['id'], $arrmsg);
		}
		$result['error'] = 0;
		$result['message'] = '下单成功-&#25240;&#82;&#32764;&#70;&#22825;&#84;&#20351;&#72;&#36164;&#78;&#28304;&#86;&#31038;&#67;&#21306;&#68;&#25552;&#83;&#20379;！';
		$result['ordersn'] = $ordersn;
		$result['paytype'] = $paytype;
		echo json_encode($result, true);
		die;
	}
	public function getRandChar($length)
	{
		$str = null;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol) - 1;
		for ($i = 0; $i < $length; $i++) {
			$str .= $strPol[rand(0, $max)];
		}
		return $str;
	}
	public function doWebSet()
	{
		global $_W, $_GPC;
		$op = $_GPC['op'];
		if ($op == 'post') {
			$id = intval($_GPC['id']);
			$isfree = intval($_GPC['isfree']);
			if ($isfree == 1) {
				$days = intval($_GPC['days']);
			} else {
				$days = 0;
			}
			$data = array('weid' => $_W['uniacid'], 'txdisaccount' => $_GPC['txdisaccount'], 'istplon' => intval($_GPC['istplon']), 'monthprice' => $_GPC['monthprice'], 'jiduprice' => $_GPC['jiduprice'], 'bannianprice' => $_GPC['bannianprice'], 'yearprice' => $_GPC['yearprice'], 'isfree' => $isfree, 'days' => $days, 'isshenhe' => intval($_GPC['isshenhe']), 'present_money' => $_GPC['present_money'], 'registerthumb' => $_GPC['registerthumb'], 'monthon' => intval($_GPC['monthon']), 'jiduon' => intval($_GPC['jiduon']), 'bannianon' => intval($_GPC['bannianon']), 'yearon' => intval($_GPC['yearon']));
			if (!empty($id)) {
				pdo_update('cygoodssale_set', $data, array('id' => $id, 'weid' => $_W['uniacid']));
			} else {
				pdo_insert('cygoodssale_set', $data);
			}
			message('操作成功！', $this->createWebUrl('set', array('op' => 'display')), 'success');
		} else {
			$setting = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_set') . " WHERE weid = {$_W['uniacid']} LIMIT 1");
			include $this->template('web/set');
		}
	}
	public function doWebMerchant()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$merchant = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_merchant') . " WHERE weid = {$_W['uniacid']} ORDER BY addtime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			foreach ($merchant as $k => $v) {
				$merchant[$k]['orderlist'] = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_sqorder') . " WHERE weid = {$_W['uniacid']} AND from_user = '{$v['openid']}'");
				$merchant[$k]['storeurl'] = $_W['siteroot'] . 'app/' . str_replace('./', '', $this->createMobileUrl('merchantstore', array('id' => $v['id'])));
			}
			$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('cygoodssale_merchant') . " WHERE weid = {$_W['uniacid']}");
			$pager = pagination($total, $pindex, $psize);
			include $this->template('web/merchant');
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			$merchant = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_merchant') . " WHERE id = {$id} AND status = 0");
			if (empty($merchant)) {
				message('抱歉，该商户不存在！', $this->createWebUrl('merchant', array('op' => 'display')), 'error');
			}
			$data = array('status' => 1);
			pdo_update('cygoodssale_merchant', $data, array('id' => $id, 'weid' => $_W['uniacid']));
			message('审核商户成功！', $this->createWebUrl('merchant', array('op' => 'display')), 'success');
			include $this->template('web/merchant');
		} elseif ($operation == 'edit') {
			$id = intval($_GPC['id']);
			$merchant = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_merchant') . " WHERE id = {$id}");
			if (empty($merchant)) {
				message('抱歉，该商户不存在！', $this->createWebUrl('merchant', array('op' => 'display')), 'error');
			}
			$dododo = intval($_GPC['dododo']);
			if ($dododo == 1) {
				$data['name'] = trim($_GPC['name']);
				$data['telphone'] = trim($_GPC['telphone']);
				$data['address'] = trim($_GPC['address']);
				pdo_update('cygoodssale_merchant', $data, array('id' => $id));
				message('编辑商户资料成功！', $this->createWebUrl('merchant', array('op' => 'display')), 'success');
			} else {
				include $this->template('web/editmerchant');
			}
		} elseif ($operation == 'xufei') {
			$id = intval($_GPC['id']);
			$merchant = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_merchant') . " WHERE id = {$id} AND status = 1");
			if (empty($merchant)) {
				message('抱歉，该商户不存在！', $this->createWebUrl('merchant', array('op' => 'display')), 'error');
			}
			$dataexpr['expirationtime'] = strtotime($_GPC['expirationtime']);
			pdo_update('cygoodssale_merchant', $dataexpr, array('id' => $id));
			message('续费成功！', $this->createWebUrl('merchant', array('op' => 'display')), 'success');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$merchant = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_merchant') . " WHERE id = {$id} AND status = 1");
			if (empty($merchant)) {
				message('抱歉，该商户不存在！', $this->createWebUrl('merchant', array('op' => 'display')), 'error');
			}
			pdo_update('cygoodssale_merchant', array('status' => 0), array('id' => $id));
			message('禁用商户成功！', $this->createWebUrl('merchant', array('op' => 'display')), 'success');
		} elseif ($operation == 'delete2') {
			$id = intval($_GPC['id']);
			$merchant = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_merchant') . " WHERE id = {$id}");
			if (empty($merchant)) {
				message('抱歉，该商户不存在！', $this->createWebUrl('merchant', array('op' => 'display')), 'error');
			}
			pdo_delete('cygoodssale_merchant', array('id' => $id));
			message('禁用商户成功！', $this->createWebUrl('merchant', array('op' => 'display')), 'success');
		}
	}
	public function doWebStatistics()
	{
		global $_W, $_GPC;
		$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
		if ($operation == 'display') {
			$j = date(j);
			$start_time = strtotime(date('Y-m-01'));
			$labels = array();
			$datas = array();
			for ($i = 0; $i < $j; $i++) {
				$date = date('Y-m-d', $start_time + $i * 86400);
				$start_day_time = strtotime($date . " 00:00:00");
				$end_day_time = strtotime($date . " 23:59:59");
				$goodsnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('cygoodssale_goods') . " WHERE weid = {$_W['uniacid']} AND createtime >= {$start_day_time} AND createtime<= {$end_day_time}");
				$datas[] = $goodsnum;
				$labels[] = $date;
			}
			$labels = json_encode($labels);
			$datas = json_encode($datas);
		}
		if ($operation == 'order') {
			$j = date(j);
			$start_time = strtotime(date('Y-m-01'));
			$labels = array();
			$datas = array();
			for ($i = 0; $i < $j; $i++) {
				$date = date('Y-m-d', $start_time + $i * 86400);
				$start_day_time = strtotime($date . " 00:00:00");
				$end_day_time = strtotime($date . " 23:59:59");
				$ordersnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('cygoodssale_order') . " WHERE weid = {$_W['uniacid']} AND createtime >= {$start_day_time} AND createtime<= {$end_day_time}");
				$datas[] = $ordersnum;
				$labels[] = $date;
			}
			$labels = json_encode($labels);
			$datas = json_encode($datas);
		}
		if ($operation == 'sale') {
			$j = date(j);
			$start_time = strtotime(date('Y-m-01'));
			$labels = array();
			$datas = array();
			for ($i = 0; $i < $j; $i++) {
				$date = date('Y-m-d', $start_time + $i * 86400);
				$start_day_time = strtotime($date . " 00:00:00");
				$end_day_time = strtotime($date . " 23:59:59");
				$ordersprice = pdo_fetchcolumn("SELECT SUM(price) FROM " . tablename('cygoodssale_order') . " WHERE weid = {$_W['uniacid']} AND createtime >= {$start_day_time} AND createtime<= {$end_day_time}");
				$datas[] = empty($ordersprice) ? 0.0 : $ordersprice;
				$labels[] = $date;
			}
			$labels = json_encode($labels);
			$datas = json_encode($datas);
		}
		if ($operation == 'merchant') {
			$j = date(j);
			$start_time = strtotime(date('Y-m-01'));
			$labels = array();
			$datas = array();
			for ($i = 0; $i < $j; $i++) {
				$date = date('Y-m-d', $start_time + $i * 86400);
				$start_day_time = strtotime($date . " 00:00:00");
				$end_day_time = strtotime($date . " 23:59:59");
				$merchantnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('cygoodssale_merchant') . " WHERE weid = {$_W['uniacid']} AND addtime >= {$start_day_time} AND addtime<= {$end_day_time}");
				$datas[] = $merchantnum;
				$labels[] = $date;
			}
			$labels = json_encode($labels);
			$datas = json_encode($datas);
		}
		include $this->template('web/statistics');
	}
	public function doWebTixian()
	{
		global $_W, $_GPC;
		$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$status = $_GPC['status'];
			if (empty($status)) {
				$status = 0;
			}
			$condition = " weid = {$_W['uniacid']} AND txstatus = {$status} AND merchant_id > 0 AND price < 0";
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('cygoodssale_merchantaccount') . ' WHERE ' . $condition);
			$list = pdo_fetchall("SELECT a.*,b.name FROM " . tablename('cygoodssale_merchantaccount') . " as a," . tablename('cygoodssale_merchant') . " as b WHERE a.weid = {$_W['uniacid']} AND a.txstatus = {$status} AND a.merchant_id > 0 AND a.price < 0 AND a.merchant_id = b.id ORDER BY a.time DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
			$pager = pagination($total, $pindex, $psize);
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			$tixian = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_merchantaccount') . " WHERE id = {$id} AND weid = {$_W['uniacid']}");
			if ($tixian['price'] > 0 || $tixian['txstatus'] != 0) {
				message("该记录不能被提现！", "error");
			}
			$setting = $this->setting;
			$shouxufei = abs($tixian['price']) * $setting['txdisaccount'] / 100;
			$shouxufei = sprintf("%.2f", $shouxufei);
			$shidao = abs($tixian['price']) - $shouxufei;
			$dodo = intval($_GPC['dodo']);
			if ($dodo == 1) {
				if (empty($_GPC['txstatus'])) {
					message("请选择提现操作类型！", $this->createWebUrl('Tixian', array('op' => 'post', 'id' => $id)), "error");
				}
				$udata['txdisaccount'] = $shouxufei;
				$udata['realprice'] = $shidao;
				$udata['txtime'] = TIMESTAMP;
				$udata['txstatus'] = $_GPC['txstatus'];
				pdo_update("cygoodssale_merchantaccount", $udata, array('id' => $id));
				if ($_GPC['txstatus'] == -1) {
					$ndata['merchant_id'] = $tixian['merchant_id'];
					$ndata['price'] = abs($tixian['price']);
					$ndata['time'] = TIMESTAMP;
					$ndata['remark'] = '提现驳回退还';
					$ndata['weid'] = $_W['uniacid'];
					$ndata['txstatus'] = 0;
					pdo_insert("cygoodssale_merchantaccount", $ndata);
				}
				message("操作成功！", $this->createWebUrl('Tixian'), "success");
			}
		}
		include $this->template('web/tixian');
	}
	public function doWebMembertixian()
	{
		global $_W, $_GPC;
		$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$status = $_GPC['status'];
			if (empty($status)) {
				$status = 0;
			}
			$condition = " weid = {$_W['uniacid']} AND txstatus = {$status} AND money < 0";
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('cygoodssale_memberaccount') . ' WHERE ' . $condition);
			$list = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_memberaccount') . " WHERE weid = {$_W['uniacid']} AND txstatus = {$status} AND money < 0 ORDER BY time DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
			$pager = pagination($total, $pindex, $psize);
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			$tixian = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_memberaccount') . " WHERE id = {$id} AND weid = {$_W['uniacid']}");
			if ($tixian['price'] > 0 || $tixian['txstatus'] != 0) {
				message("该记录不能被完成提现！");
			}
			$dodo = intval($_GPC['dodo']);
			if ($dodo == 1) {
				if (empty($_GPC['txstatus'])) {
					message("请选择提现操作类型！", $this->createWebUrl('membertixian', array('op' => 'post', 'id' => $id)), "error");
				}
				$udata['txstatus'] = $_GPC['txstatus'];
				pdo_update("cygoodssale_memberaccount", $udata, array('id' => $id));
				if ($_GPC['txstatus'] == '-1') {
					$ndata['openid'] = $tixian['openid'];
					$ndata['money'] = abs($tixian['money']);
					$ndata['time'] = TIMESTAMP;
					$ndata['explain'] = '提现驳回退还';
					$ndata['weid'] = $_W['uniacid'];
					$ndata['txstatus'] = 0;
					pdo_insert("cygoodssale_memberaccount", $ndata);
				}
				message("操作成功！", $this->createWebUrl('membertixian'), "success");
			}
		}
		include $this->template('web/membertixian');
	}
	public function doWebGoods()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$merchant = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_merchant') . " WHERE weid = {$_W['uniacid']} ORDER BY addtime DESC");
		if ($operation == 'recyclebin') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = ' WHERE `weid` = :weid AND `deleted` = :deleted';
			$params = array(':weid' => $_W['uniacid'], ':deleted' => '1');
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			if (!empty($_GPC['merchant_id'])) {
				$condition .= ' AND `merchant_id` = :merchant_id';
				$params[':merchant_id'] = intval($_GPC['merchant_id']);
			}
			$sql = 'SELECT COUNT(*) FROM ' . tablename('cygoodssale_goods') . $condition;
			$total = pdo_fetchcolumn($sql, $params);
			if (!empty($total)) {
				$sql = 'SELECT * FROM ' . tablename('cygoodssale_goods') . $condition . ' ORDER BY `status` ASC, `displayorder` DESC,
										`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				foreach ($list as $k => $v) {
					if ($v['merchant_id'] != 0) {
						$merchantres = pdo_fetch("SELECT name FROM " . tablename('cygoodssale_merchant') . " WHERE id = {$v['merchant_id']}");
						$list[$k]['merchant_name'] = $merchantres['name'];
					} else {
						$list[$k]['merchant_name'] = '';
					}
				}
				$pager = pagination($total, $pindex, $psize);
			}
		} elseif ($operation == 'post') {
			$cservicelist = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_cservice') . " WHERE weid = '{$_W['uniacid']}' AND merchant_id != 0 ORDER BY displayorder ASC");
			$id = intval($_GPC['id']);
			$goodscservicearr = pdo_fetchall("SELECT cserviceid FROM " . tablename('cygoodssale_goodscservice') . " WHERE weid = '{$_W['uniacid']}' AND goodsid = {$id}");
			$goodscservice = array();
			foreach ($goodscservicearr as $k => $v) {
				$goodscservice[] = $v['cserviceid'];
			}
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods') . " WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('抱歉，商品不存在或是已经删除！', '', 'error');
				}
				$options = pdo_fetchall("select * from " . tablename('cygoodssale_goods_option') . " where goodsid={$id} order by displayorder ASC");
				$piclist1 = unserialize($item['thumb_url']);
				$piclist = array();
				if (is_array($piclist1)) {
					foreach ($piclist1 as $p) {
						$piclist[] = is_array($p) ? $p['attachment'] : $p;
					}
				}
				$piclist2 = unserialize($item['thumbsdes']);
				$piclistdes = array();
				if (is_array($piclist2)) {
					foreach ($piclist2 as $p) {
						$piclistdes[] = is_array($p) ? $p['attachment'] : $p;
					}
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['merchant_id'])) {
					message('请选择商户！');
				}
				if (empty($_GPC['goodsname'])) {
					message('请输入商品名称！');
				}
				$data = array('weid' => intval($_W['uniacid']), 'displayorder' => intval($_GPC['displayorder']), 'title' => $_GPC['goodsname'], 'description' => $_GPC['description'], 'createtime' => TIMESTAMP, 'total' => intval($_GPC['total']), 'normalprice' => $_GPC['normalprice'], 'yunfei' => $_GPC['yunfei'], 'maxbuy' => intval($_GPC['maxbuy']), 'sales' => intval($_GPC['sales']), 'status' => intval($_GPC['status']), 'ishexiao' => intval($_GPC['ishexiao']), 'hexiaocon' => trim($_GPC['hexiaocon']), 'viewcount' => intval($_GPC['viewcount']), 'istime' => intval($_GPC['istime']), 'timestart' => strtotime($_GPC['timestart']), 'timeend' => strtotime($_GPC['timeend']), 'xiangounum' => intval($_GPC['xiangounum']), 'merchant_id' => intval($_GPC['merchant_id']));
				if ($data['total'] === -1) {
					$data['total'] = 0;
				}
				if (empty($_GPC['thumbs'])) {
					$_GPC['thumbs'] = array();
				}
				if (empty($_GPC['thumbsdes'])) {
					$_GPC['thumbsdes'] = array();
				}
				if (is_array($_GPC['thumbs'])) {
					$data['thumb_url'] = serialize($_GPC['thumbs']);
				}
				if (is_array($_GPC['thumbsdes'])) {
					$data['thumbsdes'] = serialize($_GPC['thumbsdes']);
				}
				if (empty($id)) {
					pdo_insert('cygoodssale_goods', $data);
					$id = pdo_insertid();
				} else {
					unset($data['createtime']);
					pdo_update('cygoodssale_goods', $data, array('id' => $id));
					pdo_delete('cygoodssale_goodscservice', array('goodsid' => $id));
				}
				if (!empty($_GPC['cservice'])) {
					foreach ($_GPC['cservice'] as $k => $v) {
						$datacservice['weid'] = $_W['uniacid'];
						$datacservice['goodsid'] = $id;
						$datacservice['cserviceid'] = $v;
						pdo_insert('cygoodssale_goodscservice', $datacservice);
					}
				}
				$totalstocks = 0;
				$option_ids = $_POST['option_id'];
				$option_titles = $_POST['option_title'];
				$option_normalprices = $_POST['option_normalprice'];
				$option_stocks = $_POST['option_stock'];
				$option_displayorders = $_POST['option_displayorder'];
				$len = count($option_ids);
				$optionids = array();
				for ($k = 0; $k < $len; $k++) {
					$option_id = "";
					$get_option_id = $option_ids[$k];
					$a = array("title" => $option_titles[$k], "normalprice" => $option_normalprices[$k], "stock" => $option_stocks[$k], "displayorder" => $k, "goodsid" => $id);
					if (!is_numeric($get_option_id)) {
						pdo_insert("cygoodssale_goods_option", $a);
						$option_id = pdo_insertid();
					} else {
						pdo_update("cygoodssale_goods_option", $a, array('id' => $get_option_id));
						$option_id = $get_option_id;
					}
					$optionids[] = $option_id;
					$totalstocks += $option_stocks[$k];
				}
				if (count($optionids) > 0) {
					pdo_query("delete from " . tablename('cygoodssale_goods_option') . " where goodsid = {$id} and id not in ( " . implode(',', $optionids) . ")");
					pdo_update("cygoodssale_goods", array('total' => $totalstocks), array('id' => $id));
				} else {
					pdo_query("delete from " . tablename('cygoodssale_goods_option') . " where goodsid = {$id}");
				}
				message('操作成功！', $this->createWebUrl('goods', array('op' => 'display', 'id' => $id)), 'success');
			}
		} elseif ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('cygoodssale_goods', array('displayorder' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
				}
				message('商品排序更新成功！', $this->createWebUrl('goods', array('op' => 'display')), 'success');
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = ' WHERE `weid` = :weid AND `deleted` = :deleted';
			$params = array(':weid' => $_W['uniacid'], ':deleted' => '0');
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			if (!empty($_GPC['merchant_id'])) {
				$condition .= ' AND `merchant_id` = :merchant_id';
				$params[':merchant_id'] = intval($_GPC['merchant_id']);
			}
			if (isset($_GPC['status'])) {
				$condition .= ' AND `status` = :status';
				$params[':status'] = intval($_GPC['status']);
			}
			$sql = 'SELECT COUNT(*) FROM ' . tablename('cygoodssale_goods') . $condition;
			$total = pdo_fetchcolumn($sql, $params);
			if (!empty($total)) {
				$sql = 'SELECT * FROM ' . tablename('cygoodssale_goods') . $condition . ' ORDER BY `status` ASC, `displayorder` DESC,
										`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				foreach ($list as $k => $v) {
					if ($v['merchant_id'] != 0) {
						$merchantres = pdo_fetch("SELECT name FROM " . tablename('cygoodssale_merchant') . " WHERE id = {$v['merchant_id']}");
						$list[$k]['merchant_name'] = $merchantres['name'];
					} else {
						$list[$k]['merchant_name'] = '';
					}
				}
				$pager = pagination($total, $pindex, $psize);
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_goods') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}
			pdo_update("cygoodssale_goods", array("deleted" => 1), array('id' => $id));
			message('删除成功，可在商品回收站中恢复！', referer(), 'success');
		} elseif ($operation == 'huifu') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_goods') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}
			pdo_update("cygoodssale_goods", array("deleted" => 0), array('id' => $id));
			message('恢复商品成功！', referer(), 'success');
		}
		include $this->template('web/goods');
	}
	public function doWebSetGoodsProperty()
	{
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('status'))) {
			$data = $data == 1 ? '0' : '1';
			pdo_update("cygoodssale_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}
	public function doWebOption()
	{
		$tag = random(32);
		global $_GPC;
		include $this->template('web/option');
	}
	public function exportexcel($data = array(), $title = array(), $header, $footer, $filename = 'report')
	{
		header("Content-type:application/octet-stream");
		header("Accept-Ranges:bytes");
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=" . $filename . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$header = iconv("UTF-8", "GB2312", $header);
		echo $header;
		if (!empty($title)) {
			foreach ($title as $k => $v) {
				$title[$k] = iconv("UTF-8", "GB2312", $v);
			}
			$title = implode("\t", $title);
			echo "{$title}\n";
		}
		if (!empty($data)) {
			foreach ($data as $key => $val) {
				foreach ($val as $ck => $cv) {
					$data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
				}
				$data[$key] = implode("\t", $data[$key]);
			}
			echo implode("\n", $data);
		}
		$footer = iconv("UTF-8", "GB2312", $footer);
		echo $footer;
	}
	public function doWebCservice()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('cygoodssale_cservice', array('displayorder' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
				}
				message('客服排序更新成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
			}
			$cservicelist = pdo_fetchall("SELECT a.*,b.name as merchantname FROM " . tablename('cygoodssale_cservice') . " as a," . tablename('cygoodssale_merchant') . " as b WHERE a.weid = '{$_W['uniacid']}' AND a.merchant_id = b.id AND a.merchant_id != 0 ORDER BY a.displayorder ASC");
			include $this->template('web/cservice');
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			$merchant = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_merchant') . " WHERE weid = {$_W['uniacid']} ORDER BY addtime DESC");
			if (!empty($id)) {
				$cservice = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_cservice') . " WHERE id = :id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['name'])) {
					message('抱歉，请输入客服名称！');
				}
				if (empty($_GPC['ctype'])) {
					message('抱歉，请选择客服类型！');
				}
				if (empty($_GPC['content'])) {
					message('抱歉，请输入客服内容！');
				}
				if (empty($_GPC['thumb'])) {
					message('抱歉，请上传客服头像！');
				}
				$merchant_id = intval($_GPC['merchant_id']);
				if (empty($merchant_id)) {
					message('抱歉，请选择商户！');
				}
				$data = array('weid' => $_W['uniacid'], 'name' => trim($_GPC['name']), 'ctype' => intval($_GPC['ctype']), 'content' => trim($_GPC['content']), 'thumb' => $_GPC['thumb'], 'displayorder' => intval($_GPC['displayorder']), 'merchant_id' => $merchant_id);
				if (!empty($id)) {
					pdo_update('cygoodssale_cservice', $data, array('id' => $id, 'weid' => $_W['uniacid']));
				} else {
					pdo_insert('cygoodssale_cservice', $data);
				}
				message('操作成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
			}
			include $this->template('web/cservice');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$cservice = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_cservice') . " WHERE id = {$id}");
			if (empty($cservice)) {
				message('抱歉，该客服信息不存在或是已经被删除！', $this->createWebUrl('cservice', array('op' => 'display')), 'error');
			}
			pdo_delete('cygoodssale_cservice', array('id' => $id));
			pdo_delete('cygoodssale_goodscservice', array('cserviceid' => $id));
			message('删除客服信息成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
		}
	}
	public function doWebOrder()
	{
		global $_W, $_GPC;
		$express = array(0 => array('pinyin' => 'yunda', 'value' => '韵达快递'), 1 => array('pinyin' => 'yuantong', 'value' => '圆通速递'), 2 => array('pinyin' => 'shentong', 'value' => '申通速递'), 3 => array('pinyin' => 'shunfeng', 'value' => '顺丰速递'), 4 => array('pinyin' => 'tiantian', 'value' => '天天快递'), 5 => array('pinyin' => 'youzhengguonei', 'value' => '邮政包裹'), 6 => array('pinyin' => 'ems', 'value' => '中通快递'), 7 => array('pinyin' => 'zhongtong', 'value' => 'EMS'), 8 => array('pinyin' => 'quanfengkuaidi', 'value' => '全峰快递'), 9 => array('pinyin' => 'huitongkuaidi', 'value' => '百世快递'));
		$merchant = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_merchant') . " WHERE weid = {$_W['uniacid']} ORDER BY addtime DESC");
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$goodslist = pdo_fetchall("SELECT id,title FROM " . tablename('cygoodssale_goods') . " WHERE weid = {$_W['uniacid']}");
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$status = $_GPC['status'];
			if ($status == '') {
				$status = 100;
			}
			$condition = " o.weid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = TIMESTAMP;
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
			}
			$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
			$paras[':starttime'] = $starttime;
			$paras[':endtime'] = $endtime;
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['merchant_id'])) {
				$condition .= " AND o.merchant_id = {$_GPC['merchant_id']}";
			}
			if (!empty($_GPC['goods_id'])) {
				$goods_id = intval($_GPC['goods_id']);
				$ordergoodslist = pdo_fetchall("SELECT orderid FROM " . tablename('cygoodssale_order_goods') . " WHERE weid = {$_W['uniacid']} AND goodsid = {$goods_id}");
				$orderidarr = '(';
				if (!empty($ordergoodslist)) {
					foreach ($ordergoodslist as $k => $v) {
						$orderidarr .= $v['orderid'] . ",";
					}
				}
				$orderidarr = substr($orderidarr, 0, -1) . ")";
				if (!empty($orderidarr)) {
					$condition .= " AND o.id in {$orderidarr}";
				}
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND o.address LIKE '%{$_GPC['member']}%'";
			}
			if ($status != 100) {
				if ($status == 3) {
					$condition .= " AND o.status = 4";
				} else {
					$condition .= " AND o.status = '" . intval($status) . "'";
				}
			}
			$sql = 'SELECT COUNT(*) FROM ' . tablename('cygoodssale_order') . ' AS `o` WHERE ' . $condition;
			$total = pdo_fetchcolumn($sql, $paras);
			if ($total > 0) {
				if ($_GPC['export'] == '') {
					$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				} else {
					$limit = '';
				}
				$sql = 'SELECT * FROM ' . tablename('cygoodssale_order') . ' AS `o` WHERE ' . $condition . ' ORDER BY `o`.`createtime` DESC ' . $limit;
				$list = pdo_fetchall($sql, $paras);
				$pager = pagination($total, $pindex, $psize);
				if ($_GPC['export'] == 'export') {
					$data = array();
					foreach ($list as $k => $v) {
						$address_arr = explode("|", $v['address']);
						$data[$k]['ordersn'] = $v['ordersn'];
						$data[$k]['realname'] = $address_arr[0];
						$data[$k]['telphone'] = $address_arr[1];
						$data[$k]['price'] = $v['price'];
						$data[$k]['createtime'] = date("Y-m-d H:i:s", $v['createtime']);
						$data[$k]['zipcode'] = $address_arr[2];
						$data[$k]['address'] = $address_arr[3] . $address_arr[4] . $address_arr[5];
					}
					$this->exportexcel($data, $title = array('订单号', '姓名', '电话', '总价', '下单时间', '邮政编码', '收货地址信息'), '', '', $filename = '订单数据');
					die;
				}
				foreach ($list as &$value) {
					list($value['username'], $value['mobile'], $value['zipcode']) = explode('|', $value['address']);
				}
			}
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_order') . " WHERE id = :id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			if (checksubmit('confirmsend')) {
				if (empty($_GPC['expresscom']) || empty($_GPC['express']) || empty($_GPC['expresssn'])) {
					message('请选择选择快递公司并且输入快递单号！');
				}
				if ($item['status'] != 1 && $item['status'] != 2) {
					message('订单当前状态不能使用该操作！');
				}
				pdo_update('cygoodssale_order', array('status' => 2, 'express' => $_GPC['express'], 'expresscom' => $_GPC['expresscom'], 'expresssn' => $_GPC['expresssn']), array('id' => $id));
				$tpllist = pdo_fetch("SELECT id FROM" . tablename('cygoodssale_tplmessage_tpllist') . " WHERE tplbh = 'OPENTM200565259' AND uniacid = {$_W['uniacid']}");
				$setting = $this->setting;
				if (!empty($tpllist) && $setting['istplon'] == 1) {
					$arrmsg = array('openid' => $item['from_user'], 'topcolor' => '#980000', 'first' => '订单发货通知', 'firstcolor' => '', 'keyword1' => $item['ordersn'], 'keyword1color' => '', 'keyword2' => $_GPC['express'], 'keyword2color' => '', 'keyword3' => $_GPC['expresssn'], 'keyword3color' => '', 'remark' => '', 'remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("myorder", array('merchant_id' => $item['merchant_id'], 'status' => 2))));
					$this->sendtemmsg($tpllist['id'], $arrmsg);
				}
				message('操作成功！', referer(), 'success');
			}
			if (checksubmit('finish')) {
				if ($item['status'] != 2) {
					message('订单当前状态不能使用该操作！');
				}
				pdo_update('cygoodssale_order', array('status' => 4), array('id' => $id, 'weid' => $_W['uniacid']));
				if ($item['merchant_id'] != 0) {
					$dataaccount = array('weid' => $_W['uniacid'], 'merchant_id' => $item['merchant_id'], 'price' => $item['price'] - $item['fenxiaoprice'], 'time' => TIMESTAMP, 'remark' => "订单号为" . $item['ordersn'] . "获得");
					pdo_insert('cygoodssale_merchantaccount', $dataaccount);
				}
				if ($item['shareopenid'] != '' && $item['fenxiaoprice'] > 0) {
					$dataaaccount = array('weid' => $_W['uniacid'], 'openid' => $item['shareopenid'], 'money' => $item['fenxiaoprice'], 'time' => TIMESTAMP, 'explain' => "订单号为" . $item['ordersn'] . "分销获得", 'orderid' => $id);
					pdo_insert(BEST_ACCOUNT, $dataaaccount);
				}
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('confrimpay')) {
				if ($item['status'] != 0) {
					message('订单当前状态不能使用该操作！');
				}
				pdo_update('cygoodssale_order', array('status' => 1), array('id' => $id, 'weid' => $_W['uniacid']));
				message('确认订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelorder')) {
				if ($item['status'] != 0) {
					message('订单当前状态不能使用该操作！');
				}
				pdo_update('cygoodssale_order', array('status' => '-1'), array('id' => $item['id']));
				message('订单取消操作成功！', referer(), 'success');
			}
			$goods = pdo_fetchall("SELECT g.*, o.total,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('cygoodssale_order_goods') . " o left join " . tablename('cygoodssale_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$id}'");
			$item['goods'] = $goods;
			$item['user'] = explode("|", $item['address']);
		} elseif ($operation == 'delete') {
			$orderid = intval($_GPC['id']);
			if (pdo_delete('cygoodssale_order', array('id' => $orderid, 'weid' => $_W['uniacid']))) {
				pdo_delete('cygoodssale_order_goods', array('orderid' => $orderid));
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		}
		include $this->template('web/order');
	}
	public function doWebTpllist()
	{
		global $_W;
		$list = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_tplmessage_tpllist') . " WHERE uniacid = {$_W['uniacid']} ORDER BY id ASC");
		include $this->template('web/tpllist');
	}
	public function doWebCreatetpl()
	{
		global $_GPC, $_W;
		$tplbh = trim($_GPC['tplbh']);
		$istplbh = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_tplmessage_tpllist') . " WHERE uniacid = {$_W['uniacid']} AND tplbh = '{$tplbh}'");
		if (!empty($istplbh)) {
			message('您已添加该模板消息！', $this->createWebUrl('Tpllist'), 'error');
		} else {
			$account_api = WeAccount::create();
			$token = $account_api->getAccessToken();
			if (is_error($token)) {
				message('获取access token 失败');
			}
			$url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token={$token}";
			$postdata = array('template_id_short' => $tplbh);
			$response = ihttp_request($url, urldecode(json_encode($postdata)));
			$errarr = json_decode($response['content'], true);
			if ($errarr['errcode'] == 0) {
				$data = array('tplbh' => $tplbh, 'tpl_id' => $errarr['template_id'], 'uniacid' => $_W['uniacid']);
				pdo_insert('cygoodssale_tplmessage_tpllist', $data);
				message('添加模板消息成功！', $this->createWebUrl('Tpllist'), 'success');
				return;
			} else {
				message($errarr['errmsg'], $this->createWebUrl('Tpllist'), 'error');
			}
		}
	}
	public function doWebdeltpl()
	{
		global $_GPC, $_W;
		$tplid = trim($_GPC['tplid']);
		$istplbh = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_tplmessage_tpllist') . " WHERE uniacid = {$_W['uniacid']} AND tpl_id = '{$tpl_id}'");
		if (!empty($istplbh)) {
			message('没有该模板消息！', $this->createWebUrl('Tpllist'), 'error');
		} else {
			$account_api = WeAccount::create();
			$token = $account_api->getAccessToken();
			if (is_error($token)) {
				message('获取access token 失败');
			}
			$url = "https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token={$token}";
			$postjson = '{"template_id":"' . $tplid . '"}';
			$response = ihttp_request($url, $postjson);
			$errarr = json_decode($response['content'], true);
			if ($errarr['errcode'] == 0) {
				pdo_delete('cygoodssale_tplmessage_tpllist', array('tpl_id' => $tplid));
				message('删除模板消息成功！', $this->createWebUrl('Tpllist'), 'success');
				return;
			} else {
				message($errarr['errmsg'], $this->createWebUrl('Tpllist'), 'error');
			}
		}
	}
	public function doWebUpdateTpl()
	{
		global $_W;
		$success = 0;
		$account_api = WeAccount::create();
		$token = $account_api->getAccessToken();
		if (is_error($token)) {
			message('获取access token 失败');
		}
		$url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$token}";
		$response = ihttp_request($url, urldecode(json_encode($data)));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$list = json_decode($response['content'], true);
		if (empty($list['template_list'])) {
			message('访问公众平台接口失败, 错误: 模板列表返回为空');
		}
		foreach ($list['template_list'] as $k => $v) {
			$template_id = $v['template_id'];
			$data['tpl_title'] = $v['title'];
			preg_match_all('/{{(.*?)\.DATA}}/', $v['content'], $key);
			preg_match_all('/}}\n*(.*?){{/', $v['content'], $title);
			$keys = $this->formatTplKey($key[1], $title[1]);
			$data['tpl_key'] = serialize($keys);
			$data['tpl_example'] = $v['example'];
			pdo_update('cygoodssale_tplmessage_tpllist', $data, array('tpl_id' => $template_id));
		}
		message('更新完闭！', $this->createWebUrl('Tpllist'), 'success');
	}
	public function formatTplKey($key, $title)
	{
		$keys = array();
		for ($i = 0; $i < count($key); $i++) {
			if (empty($key[$i])) {
				continue;
			}
			if ($i == 0) {
				$keys[$i]['title'] = "首标题：";
				$keys[$i]['key'] = $key[$i];
				continue;
			}
			if ($i == count($key) - 1) {
				$keys[$i]['title'] = "尾备注：";
				$keys[$i]['key'] = $key[$i];
				continue;
			}
			$keys[$i]['title'] = $title[$i - 1];
			$keys[$i]['key'] = $key[$i];
		}
		return $keys;
	}
	public function doWebSendone()
	{
		global $_W, $_GPC;
		$tpllist = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_tplmessage_tpllist') . " WHERE uniacid = {$_W['uniacid']} ORDER BY id");
		if (empty($tpllist)) {
			message("请先同步模板！", $this->createWebUrl('Tpllist'), 'error');
			die;
		}
		$data['tplid'] = empty($_GPC['tplid']) ? $tpllist[0]['id'] : $_GPC['tplid'];
		$tpldetailed = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_tplmessage_tpllist') . " WHERE id = {$data['tplid']} LIMIT 1");
		$tplkeys = unserialize($tpldetailed['tpl_key']);
		include $this->template('web/sendone');
	}
	public function doWebSendOneSumbit()
	{
		global $_W, $_GPC;
		$account_api = WeAccount::create();
		$token = $account_api->getAccessToken();
		if (is_error($token)) {
			message('获取access token 失败');
		}
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
		$tpldetailed = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_tplmessage_tpllist') . " WHERE id = {$_GPC['tplid']} LIMIT 1");
		$tplkeys = unserialize($tpldetailed['tpl_key']);
		$postData = array();
		$postData['template_id'] = $tpldetailed['tpl_id'];
		$postData['url'] = $_GPC['url'];
		$postData['topcolor'] = $_GPC['topcolor'];
		foreach ($tplkeys as $value) {
			$postData['data'][$value['key']]['value'] = $_GPC[$value['key']];
			$postData['data'][$value['key']]['color'] = $_GPC[$value['key'] . 'color'];
		}
		pdo_insert("cygoodssale_tplmessage_sendlog", array('tpl_id' => $_GPC['tplid'], 'tpl_title' => $tpldetailed['tpl_title'], 'message' => serialize($postData), 'time' => time(), 'uniacid' => $_W['uniacid'], 'target' => implode(",", $_GPC['openid']), 'type' => 1));
		$tid = pdo_insertid();
		if ($tid <= 0) {
			message('抱歉,请求失败', 'referer', 'error');
		}
		$openid = $_GPC['openid'];
		$success = 0;
		$fail = 0;
		$error = "";
		foreach ($openid as $value) {
			$postData['touser'] = $value;
			$res = ihttp_post($url, json_encode($postData));
			$re = json_decode($res['content'], true);
			if ($re['errmsg'] == 'ok') {
				$success++;
			} else {
				$fail++;
				$error .= $value . ",";
			}
		}
		pdo_update('cygoodssale_tplmessage_sendlog', array('success' => $success, 'fail' => $fail, 'error' => $error, 'status' => 1), array('id' => $tid));
		if ($success <= 0) {
			message("发送失败！", 'referer', 'error');
		}
		message("发送成功，总计：" . ($success + $fail) . "人，成功：{$success} 人，失败：{$fail} 人", $this->createWebUrl('SendOnelog'), 'success');
	}
	public function doWebSendOnelog()
	{
		global $_W, $_GPC;
		$page = empty($_GPC['page']) ? 1 : $_GPC['page'];
		$pagesize = 20;
		$total = pdo_fetch("SELECT COUNT(id) AS num FROM " . tablename('cygoodssale_tplmessage_sendlog') . " WHERE type = 1 AND uniacid = {$_W['uniacid']} ");
		$list = pdo_fetchall("SELECT a.id,a.success,a.fail,a.time,a.target,a.status,a.tpl_title as title,a.error FROM " . tablename('cygoodssale_tplmessage_sendlog') . " AS a WHERE a.type = 1 AND a.uniacid = {$_W['uniacid']} ORDER BY time DESC LIMIT " . ($page - 1) * $pagesize . "," . $pagesize);
		$pagination = pagination($total['num'], $page, $pagesize);
		include $this->template("web/sendonelog");
	}
	public function doMobileChat()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			message('请在微信浏览器中打开！', '', 'error');
		}
		$goodsid = intval($_GPC['goodsid']);
		$cserviceopenid = trim($_GPC['cserviceopenid']);
		$cservice = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_cservice') . " WHERE content = '{$cserviceopenid}'");
		if ($openid == $cserviceopenid) {
			message('客服不能发起咨询！', '', 'error');
		}
		$chatcon = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_chat') . " WHERE ((openid = '{$openid}' AND toopenid = '{$cserviceopenid}') OR (openid = '{$cserviceopenid}' AND toopenid = '{$openid}')) AND goodsid = {$goodsid} AND weid = {$_W['uniacid']} ORDER BY time ASC");
		$timestamp = TIMESTAMP;
		include $this->template("chat");
	}
	public function doMobileServicechat()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			message('请在微信浏览器中打开！', '', 'error');
		}
		$cservice = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_cservice') . " WHERE content = '{$openid}'");
		$goodsid = intval($_GPC['goodsid']);
		$useropenid = trim($_GPC['useropenid']);
		$chatcon = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_chat') . " WHERE ((openid = '{$useropenid}' AND toopenid = '{$openid}') OR (openid = '{$openid}' AND toopenid = '{$useropenid}')) AND goodsid = {$goodsid} AND weid = {$_W['uniacid']} ORDER BY time ASC");
		$timestamp = TIMESTAMP;
		include $this->template("servicechat");
	}
	public function doMobileShuaxinchat()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		$useropenid = trim($_GPC['toopenid']);
		$goodsid = intval($_GPC['goodsid']);
		$timestamp = intval($_GPC['timestamp']);
		$type = intval($_GPC['type']);
		$chatcon = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_chat') . " WHERE ((openid = '{$useropenid}' AND toopenid = '{$openid}') OR (openid = '{$openid}' AND toopenid = '{$useropenid}')) AND goodsid = {$goodsid} AND weid = {$_W['uniacid']} AND time >= {$timestamp} ORDER BY time ASC");
		$html = '';
		if (!empty($chatcon)) {
			foreach ($chatcon as $k => $v) {
				if ($v['openid'] == $openid) {
					$messageclass = 'message me';
					$bubble = 'bubble bubble_primary right';
					if ($type == 2) {
						$author_name = $v['nickname'];
						$imgsrc = tomedia($v['avatar']);
					} else {
						$author_name = $v['nickname'] == '' ? '我' : $v['nickname'];
						$imgsrc = $v['avatar'] == '' ? '../addons/cy163_goodssales/static/new/images/kehu.jpg' : $v['avatar'];
					}
				} else {
					$messageclass = 'message';
					$bubble = 'bubble bubble_default left';
					if ($type == 2) {
						$author_name = $v['nickname'] == '' ? '客户' : $v['nickname'];
						$imgsrc = $v['avatar'] == '' ? '../addons/cy163_goodssales/static/new/images/kehu.jpg' : $v['avatar'];
					} else {
						$author_name = $v['nickname'];
						$imgsrc = tomedia($v['avatar']);
					}
				}
				$html .= '<div class="clearfix slideInUp">' . '<div class="' . $messageclass . '">' . '<div class="avatar" data-author-id="me"><img src="' . $imgsrc . '" /></div>' . '<div class="content"><p class="author_name"> ' . $author_name . '<time style="font-size:0.7rem;margin-left:0.5rem;">' . date('Y-m-d H:i:s', $v['time']) . '</time></p>' . '<div class="' . $bubble . '">' . ' <div class="bubble_cont">' . '<div class="plain"><p>' . $v['content'] . '</p>' . '</div>' . '</div>' . '</div>' . '</div>' . '</div>' . '</div>';
			}
		}
		$resArr['error'] = 0;
		$resArr['msg'] = $html;
		$resArr['timestamp'] = TIMESTAMP;
		echo json_encode($resArr);
		die;
	}
	public function doMobileAddchat()
	{
		global $_W, $_GPC;
		$data['openid'] = $_W['fans']['from_user'];
		$data['toopenid'] = trim($_GPC['toopenid']);
		$data['goodsid'] = intval($_GPC['goodsid']);
		$data['time'] = TIMESTAMP;
		$data['content'] = htmlspecialchars($_GPC['content']);
		$data['weid'] = $_W['uniacid'];
		$data['nickname'] = empty($_W['fans']['tag']) ? '' : $_W['fans']['tag']['nickname'];
		$data['avatar'] = empty($_W['fans']['tag']) ? '' : $_W['fans']['tag']['avatar'];
		$useropenid = trim($_GPC['useropenid']);
		$hasliao = pdo_fetch("SELECT id,time FROM " . tablename('cygoodssale_chat') . " WHERE weid = {$_W['uniacid']} AND goodsid = {$data['goodsid']} AND openid = '{$data['openid']}' AND toopenid = '{$data['toopenid']}' ORDER BY time DESC");
		$guotime = TIMESTAMP - $hasliao['time'];
		if (empty($hasliao) || $guotime > 600) {
			$tpllist = pdo_fetch("SELECT id FROM" . tablename('cygoodssale_tplmessage_tpllist') . " WHERE tplbh = 'OPENTM207327169' AND uniacid = {$_W['uniacid']}");
			$setting = $this->setting;
			if (!empty($tpllist) && $setting['istplon'] == 1) {
				$arrmsg = array('openid' => $data['toopenid'], 'topcolor' => '#980000', 'first' => '客服咨询提醒', 'firstcolor' => '', 'keyword1' => date("Y-m-d H:i:s", TIMESTAMP), 'keyword1color' => '', 'keyword2' => 1, 'keyword2color' => '', 'remark' => '咨询内容：' . $data['content'], 'remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("servicechat", array('goodsid' => $data['goodsid'], 'useropenid' => $data['openid']))));
				$this->sendtemmsg($tpllist['id'], $arrmsg);
			}
		}
		pdo_insert("cygoodssale_chat", $data);
		$resArr['error'] = 0;
		$resArr['msg'] = '';
		echo json_encode($resArr);
		die;
	}
	public function doMobileAddchat2()
	{
		global $_W, $_GPC;
		$data['openid'] = $_W['fans']['from_user'];
		$cservice = pdo_fetch("SELECT name,thumb FROM " . tablename('cygoodssale_cservice') . " WHERE weid = {$_W['uniacid']} AND content = '{$data['openid']}'");
		$data['nickname'] = $cservice['name'];
		$data['avatar'] = $cservice['thumb'];
		$data['toopenid'] = trim($_GPC['toopenid']);
		$data['goodsid'] = intval($_GPC['goodsid']);
		$data['time'] = TIMESTAMP;
		$data['content'] = htmlspecialchars($_GPC['content']);
		$data['weid'] = $_W['uniacid'];
		pdo_insert("cygoodssale_chat", $data);
		$resArr['error'] = 0;
		$resArr['msg'] = '';
		echo json_encode($resArr);
		die;
	}
	public function doMobilePay()
	{
		global $_W, $_GPC;
		$sorder = intval($_GPC['sorder']);
		$ordersn = trim($_GPC['ordersn']);
		if ($sorder == 1) {
			$order = pdo_fetch("SELECT * FROM " . tablename("cygoodssale_sqorder") . " WHERE weid = {$_W['uniacid']} AND ordersn = '{$ordersn}' AND ordersn != '' AND status = 0 ORDER BY	createtime DESC");
		} else {
			$order = pdo_fetch("SELECT * FROM " . tablename("cygoodssale_order") . " WHERE weid = {$_W['uniacid']} AND ordersn = '{$ordersn}' AND ordersn != '' AND status = 0 ORDER BY createtime DESC");
		}
		if (empty($order)) {
			message('该订单不能支付！');
		}
		$fee = $order['price'];
		if ($fee <= 0) {
			message('支付错误, 金额小于0！');
		}
		if ($sorder == 1) {
			$params = array('tid' => $order['ordersn'], 'ordersn' => $order['ordersn'], 'title' => '申请商户支付', 'fee' => $fee, 'user' => $order['merchant_id']);
		} else {
			$params = array('tid' => $order['ordersn'], 'ordersn' => $order['ordersn'], 'title' => '订单支付', 'fee' => $fee, 'user' => '');
		}
		$this->pay($params);
	}
	public function payResult($params)
	{
		global $_W;
		$ordersnlen = strlen($params['tid']);
		if ($params['result'] == 'success' && $params['from'] == 'notify') {
			$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `tid`=:tid';
			$pars = array();
			$pars[':tid'] = $params['tid'];
			$log = pdo_fetch($sql, $pars);
			$paydetail = $log['tag'];
			$logtag = unserialize($log['tag']);
			if ($ordersnlen == 14) {
				pdo_update("cygoodssale_sqorder", array('status' => 1, 'transid' => $logtag['transaction_id'], 'paydetail' => $paydetail), array('ordersn' => $params['tid']));
				$sqorderres = pdo_fetch("SELECT paytype,merchant_id FROM " . tablename('cygoodssale_sqorder') . " WHERE weid = {$_W['uniacid']} AND ordersn = '{$params['tid']}'");
				$nowtime = TIMESTAMP;
				if ($sqorderres['paytype'] == 1) {
					$datamerchant['expirationtime'] = $nowtime + 30 * 3600 * 24;
				}
				if ($sqorderres['paytype'] == 2) {
					$datamerchant['expirationtime'] = $nowtime + 90 * 3600 * 24;
				}
				if ($sqorderres['paytype'] == 3) {
					$datamerchant['expirationtime'] = $nowtime + 180 * 3600 * 24;
				}
				if ($sqorderres['paytype'] == 4) {
					$datamerchant['expirationtime'] = $nowtime + 365 * 3600 * 24;
				}
				$datamerchant['status'] = 1;
				pdo_update('cygoodssale_merchant', $datamerchant, array('id' => $sqorderres['merchant_id']));
			} else {
				pdo_update("cygoodssale_order", array('status' => 1, 'transid' => $logtag['transaction_id'], 'paydetail' => $paydetail), array('ordersn' => $params['tid']));
				$orderres = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_order') . " WHERE weid = {$_W['uniacid']} AND ordersn = '{$params['tid']}'");
				$ordergoodsres = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_order_goods') . " WHERE weid = {$_W['uniacid']} AND orderid = {$orderres['id']}");
				if ($ordergoodsres['optionid'] > 0) {
					pdo_query("update " . tablename('cygoodssale_goods_option') . " set stock=stock-:stock where id=:id", array(":stock" => $ordergoodsres['total'], ":id" => $ordergoodsres['optionid']));
				}
				pdo_query("update " . tablename('cygoodssale_goods') . " set total=total-:stock,sales=sales+:stock where id=:id", array(":stock" => $ordergoodsres['total'], ":id" => $ordergoodsres['goodsid']));
			}
		}
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				if ($ordersnlen == 14) {
					message('支付成功！', $this->createMobileUrl('merchantregister'), 'success');
				} else {
					$tpllist = pdo_fetch("SELECT id FROM" . tablename('cygoodssale_tplmessage_tpllist') . " WHERE tplbh = 'TM00015' AND uniacid = {$_W['uniacid']}");
					$orderres = pdo_fetch("SELECT * FROM " . tablename(BEST_ORDER) . " WHERE ordersn = '{$params['tid']}'");
					$setting = $this->setting;
					if (!empty($tpllist) && $setting['istplon'] == 1) {
						$arrmsg = array('openid' => $_W['fans']['from_user'], 'topcolor' => '#980000', 'first' => '恭喜您支付成功', 'firstcolor' => '', 'orderMoneySum' => $params['fee'], 'orderMoneySumcolor' => '', 'orderProductName' => '订单号' . $params['tid'] . '的商品', 'orderProductNamecolor' => '', 'Remark' => '', 'Remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("myorder", array('statuss' => 1))));
						$this->sendtemmsg($tpllist['id'], $arrmsg);
					}
					$tpllistmerchant = pdo_fetch("SELECT id FROM" . tablename('cygoodssale_tplmessage_tpllist') . " WHERE tplbh = 'OPENTM207018253' AND uniacid = {$_W['uniacid']}");
					if (!empty($tpllistmerchant) && $setting['istplon'] == 1) {
						$merchant = pdo_fetch("SELECT openid FROM " . tablename(BEST_MERCHANT) . " WHERE id = {$orderres['merchant_id']}");
						$addressarr = explode("|", $orderres['address']);
						if ($orderres['ishexiao'] == 1) {
							$tpladdress = '核销订单不需要收货地址';
						} else {
							$tpladdress = $addressarr[2] . $addressarr[3] . $addressarr[4] . $addressarr[5];
						}
						$arrmsgmerchant = array('openid' => $merchant['openid'], 'topcolor' => '#980000', 'first' => '用户下单成功通知', 'firstcolor' => '', 'keyword1' => $orderres['ordersn'], 'keyword1color' => '', 'keyword2' => $orderres['price'], 'keyword2color' => '', 'keyword3' => $addressarr[0], 'keyword3color' => '', 'keyword4' => $addressarr[1], 'keyword4color' => '', 'keyword5' => $tpladdress, 'keyword5color' => '', 'remark' => '', 'remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("merchantorder")));
						$this->sendtemmsg($tpllistmerchant['id'], $arrmsgmerchant);
					}
					$locationurl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl('myorder', array('statuss' => 1)));
					header("Location:" . $locationurl);
				}
			} else {
				$locationurl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl('myorder', array('statuss' => 0)));
				header("Location:" . $locationurl);
			}
		}
	}
	public function sendtemmsg($tplid, $arrmsg)
	{
		global $_W, $_GPC;
		$account_api = WeAccount::create();
		$token = $account_api->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
		$tpldetailed = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_tplmessage_tpllist') . " WHERE id = {$tplid} LIMIT 1");
		$tplkeys = unserialize($tpldetailed['tpl_key']);
		$postData = array();
		$postData['template_id'] = $tpldetailed['tpl_id'];
		$postData['url'] = $arrmsg['url'];
		$postData['topcolor'] = $arrmsg['topcolor'];
		foreach ($tplkeys as $value) {
			$postData['data'][$value['key']]['value'] = $arrmsg[$value['key']];
			$postData['data'][$value['key']]['color'] = $arrmsg[$value['key'] . 'color'];
		}
		pdo_insert("cygoodssale_tplmessage_sendlog", array('tpl_id' => $tplid, 'tpl_title' => $tpldetailed['tpl_title'], 'message' => serialize($postData), 'time' => time(), 'uniacid' => $_W['uniacid'], 'target' => $arrmsg['openid'], 'type' => 1));
		$tid = pdo_insertid();
		$success = 0;
		$fail = 0;
		$error = "";
		$postData['touser'] = $arrmsg['openid'];
		$res = ihttp_post($url, json_encode($postData));
		$re = json_decode($res['content'], true);
		if ($re['errmsg'] == 'ok') {
			$success++;
		} else {
			$fail++;
			$error .= $openid;
		}
		pdo_update('cygoodssale_tplmessage_sendlog', array('success' => $success, 'fail' => $fail, 'error' => $error, 'status' => 1), array('id' => $tid));
	}
	public function doMobileMerchantaccount()
	{
		global $_W, $_GPC;
		$merchant = $this->checkmergentauth();
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('cygoodssale_merchantaccount') . " WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']}");
		$allpage = ceil($total / 10) + 1;
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$yongjinlist = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_merchantaccount') . " WHERE merchant_id = {$merchant['id']} AND weid = {$_W['uniacid']} ORDER BY time DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
		$yongjin = pdo_fetchcolumn("SELECT SUM(price) FROM " . tablename('cygoodssale_merchantaccount') . " WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']}");
		$yongjin = empty($yongjin) ? 0.0 : round($yongjin, 2);
		$isajax = intval($_GPC['isajax']);
		if ($isajax == 1) {
			$html = '';
			foreach ($yongjinlist as $k => $v) {
				$html .= '<div class="item">
											<div class="iconfont">&#xe62b;</div>
											<div class="msg">
												<div class="ordersn textellipsis1">' . date('Y-m-d H:i:s', $v['time']) . '</div>
												<div class="status">' . $v['remark'] . '</div>
											</div>
											<div class="yongjin text-c">¥' . $v['price'] . '</div>
										</div>';
			}
			echo $html;
			die;
		} else {
			include $this->template('merchantaccount');
		}
	}
	public function doMobileDomerchantaccount()
	{
		global $_W, $_GPC;
		$merchant = $this->checkmergentauth();
		$alipayfee = $_GPC['money'];
		if ($alipayfee <= 0) {
			$resArr['error'] = 1;
			$resArr['message'] = '提现金额必须大于0元！';
			echo json_encode($resArr);
			die;
		}
		$alipayaccount = trim($_GPC['alipay']);
		$alipayname = trim($_GPC['realname']);
		if (empty($alipayaccount) || empty($alipayname)) {
			$resArr['error'] = 1;
			$resArr['message'] = '请填写您的支付宝账户和真实姓名！';
			echo json_encode($resArr);
			die;
		}
		$account = pdo_fetchcolumn("SELECT sum(price) FROM " . tablename('cygoodssale_merchantaccount') . " WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']}");
		$account = empty($account) ? 0.0 : $account;
		if ($alipayfee > $account) {
			$resArr['error'] = 1;
			$resArr['message'] = '您的账户余额不足！';
			echo json_encode($resArr);
			die;
		}
		$setting = $this->setting;
		if ($setting['present_money'] > $account) {
			$resArr['error'] = 1;
			$resArr['message'] = '提现金额必须满' . $setting['present_money'] . '元才能提现！';
			echo json_encode($resArr);
			die;
		}
		$data = array('weid' => $_W['uniacid'], 'merchant_id' => $merchant['id'], 'price' => -$alipayfee, 'time' => TIMESTAMP, 'remark' => '提现' . $alipayfee . '元', 'txstatus' => 0, 'alipayaccount' => $alipayaccount, 'alipayname' => $alipayname);
		pdo_insert("cygoodssale_merchantaccount", $data);
		$resArr['error'] = 1;
		$resArr['message'] = '恭喜您，提现成功！';
		echo json_encode($resArr);
		die;
	}
	public function doMobileMerchantstore()
	{
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$merchant = pdo_fetch("SELECT * FROM " . tablename(BEST_MERCHANT) . " WHERE weid = {$_W['uniacid']} AND id = {$id}");
		if (empty($merchant)) {
			message("不存在该商家！");
		}
		$advlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_ADV) . " WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} ORDER BY displayorder ASC");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('cygoodssale_goods') . " WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 1 AND deleted = 0");
		$allpage = ceil($total / 10) + 1;
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$goodslist = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_goods') . " WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 1 AND deleted = 0 ORDER BY sales DESC,displayorder ASC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
		foreach ($goodslist as $k => $v) {
			$thumbs = unserialize($v['thumb_url']);
			$goodslist[$k]['thumb'] = $thumbs[0];
		}
		$isajax = intval($_GPC['isajax']);
		if ($isajax == 1) {
			$html = '';
			foreach ($goodslist as $k => $v) {
				$html .= '<a href="' . $this->createMobileUrl('detail', array('id' => $v['id'])) . '">
											<div class="item">
												<div class="img"><img src="' . tomedia($v['thumb']) . '"></div>
												<div class="title textellipsis2">' . $v['title'] . '</div>
												<div class="numitem">
													<div class="price">￥' . $v['normalprice'] . '</div>
													<div class="sales text-r">销量：' . $v['sales'] . '</div>
													<div class="stock text-r">剩余：' . $v['total'] . '</div>
												</div>
											</div>
											</a>';
			}
			echo $html;
			die;
		} else {
			$shareres['title'] = $merchant['name'];
			$shareres['thumb'] = empty($merchant['avatar']) ? MD_ROOT . '/images/autopic.png' : tomedia($merchant['avatar']);
			$shareres['des'] = '地址：' . $merchant['address'];
			$shareres['url'] = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl('merchantstore', array('id' => $merchant['id'])));
			include $this->template('merchantpro_list');
		}
	}
	public function doMobileMerchantcenter()
	{
		global $_GPC, $_W;
		if (empty($_W['fans']['from_user'])) {
			message("请在微信浏览器打开！");
		}
		$merchant = $this->checkmergentauth();
		$nowtime = TIMESTAMP;
		$goodsnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('cygoodssale_goods') . " WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND deleted = 0");
		$start = mktime(0, 0, 0, date("m", $nowtime), date("d", $nowtime), date("Y", $nowtime));
		$end = mktime(23, 59, 59, date("m", $nowtime), date("d", $nowtime), date("Y", $nowtime));
		$todaywin = pdo_fetchcolumn("SELECT sum(price) FROM " . tablename('cygoodssale_order') . " WHERE createtime >= {$start} AND createtime <= {$end} AND weid = {$_W['uniacid']} AND price > 0 AND status >= 1 AND merchant_id = {$merchant['id']}");
		$todaywin = empty($todaywin) ? 0.0 : round($todaywin, 2);
		$yongjin = pdo_fetchcolumn("SELECT sum(fenxiaoprice) FROM " . tablename('cygoodssale_order') . " WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status >=4 AND fenxiaoprice > 0");
		$yongjin = empty($yongjin) ? 0.0 : round($yongjin, 2);
		$allwin = pdo_fetchcolumn("SELECT sum(price) FROM " . tablename('cygoodssale_order') . " WHERE weid = {$_W['uniacid']} AND price > 0 AND status >= 1 AND merchant_id = {$merchant['id']}");
		$allwin = empty($allwin) ? 0.0 : round($allwin, 2);
		$dallwin = pdo_fetchcolumn("SELECT sum(price) FROM " . tablename('cygoodssale_order') . " WHERE weid = {$_W['uniacid']} AND price > 0 AND status >= 1 AND status <4 AND merchant_id = {$merchant['id']}");
		$dallwin = empty($dallwin) ? 0.0 : round($dallwin, 2);
		include $this->template('merchantcenter');
	}
	public function doMobileMerchantorder()
	{
		include_once 'inc/mobile/merchantorder.php';
	}
	public function doMobileMerchantgoods()
	{
		include_once 'inc/mobile/merchantgoods.php';
	}
	public function doMobileMerchantcservice()
	{
		include_once 'inc/mobile/merchantcservice.php';
	}
	public function doMobileMerchantadv()
	{
		include_once 'inc/mobile/merchantadv.php';
	}
	public function doMobileMerchantyongjin()
	{
		include_once 'inc/mobile/merchantyongjin.php';
	}
	public function doMobileMerchantprofile()
	{
		include_once 'inc/mobile/merchantprofile.php';
	}
	private function checkmergentauth()
	{
		global $_W;
		$merchant = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_merchant') . " WHERE openid = '{$_W['fans']['from_user']}' AND weid = {$_W['uniacid']}");
		if (empty($merchant) || $merchant['expirationtime'] < TIMESTAMP || $merchant['status'] == 0) {
			header("Location:" . $this->createMobileUrl('merchantregister'));
		}
		return $merchant;
	}
}