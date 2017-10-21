<?php
/**
 * 最牛积分商城模块微站定义
 *
 * @author lonaking
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require_once dirname(__FILE__).'/class/gift/GiftShopGiftService.php';//礼品service
require_once dirname(__FILE__).'/class/ad/GiftShopAdService.php';//礼品service
require_once dirname(__FILE__).'/class/order/GiftShopGiftOrderService.php';
require_once dirname(__FILE__).'/class/gift/GiftShopGiftAdminService.php';
require_once dirname(__FILE__).'/exception/WechatBrowserException.php';
require_once dirname(__FILE__).'/pay/WxHongBaoException.php';
require_once dirname(__FILE__).'/pay/WxHongBaoHelper.php';
require_once dirname(__FILE__).'/pay/CommonUtil.php';
require_once dirname(__FILE__).'/../lonaking_flash/user/credit/FlashCreditService.php';

class Lonaking_gift_shopModuleSite extends WeModuleSite {

	private $giftService;
	private $adService;
	private $giftOrderService;
	private $giftAdminService;
	private $tplConfigService;
	private $userService;
	private $creditService;

	/**
	 *
     */
	public function __construct()
	{
		$this->giftService = new GiftShopGiftService();
		$this->adService = new GiftShopAdService();
		$this->giftOrderService = new GiftShopGiftOrderService();
		$this->giftAdminService = new GiftShopGiftAdminService();
		$this->tplConfigService = new GiftShopTplConfigService();
		$this->userService = new FlashUserService();
		$this->creditService = new FlashCreditService();
	}
	/**
	 * 礼品管理
	 */
	public function doWebGiftManage(){
		global $_W, $_GPC;
		$uniacid=$_W["uniacid"];
		$where="";
		$status = -1;
		if(!is_null($_GPC['s'])){
			$status = $_GPC['s'];
		}
		if (!is_null($_GPC['s'])) {
			$where .= "AND status = {$status}";
		}
		$orderby = "";
		if(!is_null($_GPC['orderby'])){
			$sort =  (is_null($_GPC['sort'])) ? 'DESC' : $_GPC['sort'];
			$orderby = $_GPC['orderby'] ." ".$sort.", ";
		}
		$page_index = max(1, intval($_GPC['page']));
		$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		$gifts = pdo_fetchall("SELECT ".$this->giftService->columns ." FROM ". tablename($this->giftService->table_name) ." WHERE uniacid ='{$uniacid}' AND del = false AND 1=1  {$where} ORDER BY {$orderby} id DESC LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ". tablename($this->giftService->table_name) ." WHERE uniacid='{$uniacid}' AND del = false AND 1=1  {$where}");

		$pager = pagination($total, $page_index, $page_size);
		load()->func('tpl');
		include $this->template('gifts');
	}
	/**
	 * 礼品详情/添加/修改礼品页面
	 */
	public function doWebGift(){
		global $_GPC, $_W;
		checkaccount();//
		$id = $_GPC['id'];
		if (!empty($_GPC['submit'])) {//提交表单
			$gift = $_GPC['gift'];
			$gift['uniacid'] = $_W['uniacid'];
			try{
				$gift['description'] = htmlspecialchars_decode($gift['description']);
				$this->giftService->insertOrUpdate($gift);
				return message("信息保存成功", "", "success");
			}catch (Exception $e){
				return message("信息保存失败", "", "error");
			}
		}else{
			$gift = null;
			$option = "礼品添加";
			if(!is_null($id)){
				$gift = $this->giftService->selectById($id);
				$gift['description'] = htmlspecialchars_decode($gift['description']);
				$option = "礼品信息修改";
			}
			$gift_type = explode(",",$this->module['config']['gift_type']);
			load()->func('tpl');
			include $this->template('gift_edit');
		}
	}
	/**
	 * 商品删除
	 */
	public function doWebGiftRemove(){
		global $_GPC, $_W;
		checkaccount();
		$id = $_GPC['id'];
		try{
			$this->giftService->updateColumn('del',1,$id);
			return $this->return_json(200,'删除成功',null);
		}catch (Exception $e){
			return message("删除失败:".$e->getMessage(),"","danger");
			return $this->return_json(400,'删除失败:'.$e->getMessage().'danger',null);
		}
	}
	/**
	 * 礼品兑换记录
	 */
	public function doWebGiftOrderManage(){
		global $_W, $_GPC;
		$uniacid=$_W["uniacid"];
		$where="";
		$status = -1;
		if(!is_null($_GPC['s'])){
			$status = $_GPC['s'];
		}
		if (!is_null($_GPC['s'])) {
			$where .= "AND o.status = {$status}";
		}

		$page_index = max(1, intval($_GPC['page']));
		$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		$gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.openid openid,o.gift gift ,o.status status,o.name real_name,o.mobile mobile,o.raffle_status,o.remark,o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,o.order_num,o.send_price,o.order_price,o.order_mode,o.order_hongbao_money,g.name gift_name,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address FROM ". tablename($this->giftOrderService->table_name) ." o LEFT JOIN ". tablename($this->giftService->table_name) ." g ON o.gift=g.id WHERE o.uniacid='{$uniacid}' AND 1=1  {$where} ORDER BY {$orderby} o.createtime DESC LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ". tablename($this->giftOrderService->table_name) ." o WHERE o.uniacid='{$uniacid}' AND 1=1  {$where}");
		$pager = pagination($total, $page_index, $page_size);
		load()->func('tpl');
		include $this->template('giftorders');
	}

	/**
	 * 核销记录
	 */
	public function doWebCheckRecordManage(){
		global $_W, $_GPC;
		$uniacid=$_W["uniacid"];
		$where="";//核销成功 并且是自领的
		$page_index = max(1, intval($_GPC['page']));
		$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		$gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.openid openid,o.gift gift ,o.status status,o.name real_name,o.mobile mobile,o.raffle_status,o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,o.order_num,o.send_price,o.order_price,o.order_mode,o.order_hongbao_money,g.name gift_name,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address FROM ". tablename($this->giftOrderService->table_name) ." o LEFT JOIN ". tablename($this->giftService->table_name) ." g ON o.gift=g.id WHERE o.uniacid='{$uniacid}' AND 1=1  {$where} AND o.status=5 AND g.mode=4 ORDER BY {$orderby} o.createtime DESC LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
		$total = pdo_fetchcolumn("SELECT count(1) FROM ". tablename($this->giftOrderService->table_name) ." o LEFT JOIN ". tablename($this->giftService->table_name) ." g ON o.gift=g.id WHERE o.uniacid={$uniacid} AND 1=1  {$where} AND o.status=5 AND g.mode=4");
		$pager = pagination($total, $page_index, $page_size);
		load()->func('tpl');
		include $this->template('check_record');
	}

	/**
	 * 导出话费
	 */
	public function doWebDumpGiftOrderMobileFee(){
		$mobile_fee_orders = $this->giftOrderService->selectAllGiftOrders("AND g.mode=2 AND o.status=0");
		include $this->template('dump_mobile_fee');
	}

	/**
	 * 新接口 切换礼品状态
	 */
	public function doWebOptionGiftOrder(){
		global $_W, $_GPC;
		checkaccount();
		$order_id = $_GPC['id'];
		$option = $_GPC['opt'];
		$opts = array('refuse','ok');
		$remark = $_GPC['remark'];
		try{
			if($option == 'ok'){
				$this->accessGiftOrder($order_id);
			}elseif($option == 'refuse'){
				$this->giftOrderService->refuseOrder($order_id,$remark);
			}
			return $this->return_json(200,'success',null);
		}catch(Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage(),null);
		}
	}

	/**
	 * access a order
	 */
	private function accessGiftOrder($order_id){

		try{
			$giftOrder = $this->giftOrderService->selectById($order_id);
			$this->giftOrderService->accessOrder($order_id);
			$gift = $this->giftService->selectById($giftOrder['gift']);
			$gift_mode = $gift['mode'];
			//根据不同的礼品模式来进行不同的操作，拒绝不考虑 1微信红包 2充值 3实物礼品 4自领礼品
			if($gift_mode == 1){
				//1微信红包 发红包
				$hongbaoMoney = $giftOrder['order_hongbao_money'];
				$toOpenid = $giftOrder['openid'];
				$this->sendRedpack($toOpenid,$hongbaoMoney,false);//TODO 传入参数
			}elseif($gift_mode == 2){
				//2充值
			}elseif($gift_mode == 3){
				//3实物礼品 准备快递单号之类的数据
			}elseif($gift_mode == 4){
				//4自领礼品 核销
			}
		}catch(Exception $e){
			throw new Exception($e->getMessage(),$e->getCode());
		}
	}

	/**
	 * refuse
	 * @param $order_id
	 * @param string $remark
	 * @throws Exception
	 */
	private function refuseGiftOrder($order_id,$remark=""){
		$this->giftOrderService->refuseOrder($order_id,$remark);
	}
	/**
	 * 模板消息配置
	 */
	public function doWebTplNoticeConfig(){
		global $_GPC, $_W;
		checkaccount();//
		$form = $_GPC['tpl_config'];
		$uniacid = $_W['uniacid'];
		if (!empty($_GPC['submit'])) {//提交表单
			try{
				$form['uniacid'] = $uniacid;
				$this->tplConfigService->updateTplConfigByUniacit($form);
				return message("信息保存成功", "", "success");
			}catch (Exception $e){
				return message("信息保存失败", "", "error");
			}
		}else{
			$tpl_config = $this->tplConfigService->checkConfigByUniacid($uniacid);
			include $this->template('tpl_config');
		}
	}

	/**
	 * 更新快递单号
	 */
	public function doWebUpdateTransNum(){
		global $_W, $_GPC;
		$num = $_GPC['num'];
		$id = $_GPC['order_id'];
		if(!empty($num)){
			try{
				$this->giftOrderService->updateColumn('trans_num',$num,$id);
				$giftOrder = $this->giftOrderService->selectGiftOrdersDetail($id);
				$this->tplConfigService->sendGiftSendUpTplNotice($giftOrder,$_W['siteroot'].'app'.substr($this->createMobileUrl('Index',array('go'=>$_W['order_detail'],'id'=>$giftOrder['id'])),1));
				return $this->return_json(200,'更新成功',null);
			}catch (Exception $e){
				return $this->return_json(400,'错误',null);
			}
		}
	}
	/**
	 * 广告管理
	 * @throws Exception
	 */
	public function doWebAdManage(){
		global $_W,$_GPC;
		try{
			$ads = $this->adService->selectAll();
			$html = array(
				'ads' => $ads,
			);
			include $this->template('ads');
		}catch (Exception $e){
			include $this->template('ads');
		}
	}

	/**
	 * 广告添加或者修改
	 * @throws Exception
	 */
	public function doWebAd(){
		global $_GPC, $_W;
		checkaccount();//
		$id = $_GPC['id'];
		if (!empty($_GPC['submit'])) {//提交表单
			$ad = $_GPC['ad'];
			$ad['uniacid'] = $_W['uniacid'];
			try{
				$this->adService->insertOrUpdate($ad);
				return message("信息保存成功", "", "success");
			}catch (Exception $e){
				return message("信息保存失败", "", "error");
			}
		}else{
			$ad = null;
			$option = "广告添加";
			if(!is_null($id)){
				$ad = $this->adService->selectById($id);
				$option = "广告信息修改";
			}
			load()->func('tpl');
			include $this->template('ad_edit');
		}
	}

	/**
	 * 删除一个广告
	 */
	public function doWebAdRemove(){
		global $_GPC, $_W;
		checkaccount();//
		$id = $_GPC['id'];
		try{
			$this->adService->deleteById($id);
			return $this->return_json(200,"删除成功",null);
		}catch (Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage(),null);
		}

	}

	//						下面的开始就是手机端的api了
	//                            _ooOoo_
	//                           o8888888o
	//                           88" . "88
	//                           (| -_- |)
	//                            O\ = /O
	//                        ____/`---'\____
	//                      .   ' \\| |// `.
	//                       / \\||| : |||// \
	//                     / _||||| -:- |||||- \
	//                       | | \\\ - /// | |
	//                     | \_| ''\---/'' | |
	//                      \ .-\__ `-` ___/-. /
	//                   ___`. .' /--.--\ `. . __
	//                ."" '< `.___\_<|>_/___.' >'"".
	//               | | : `- \`.;`\ _ /`;.`/ - ` : | |
	//                 \ \ `-. \_ __\ /__ _/ .-` / /
	//         ======`-.____`-.___\_____/___.-`____.-'======
	//                            `=---='
	//


	public function doMobileIndex()
	{
		global $_GPC, $_W;
		$openid = $_W['openid'];
		if(!$this->module['config']['open']){
			return message('积分商城已经关闭','','error');
		}
		//进行安全校验，不允许屏幕大的终端访问，不允许非微信浏览器访问、不允许无cookie的终端访问、不允许
		try{
			//安全检测
			$this->safecheck();
			//检测是否已经存在该用户
			$user = $this->userService->fetchFansInfo($openid);
			//获取在售的礼品
			$gifts = $this->giftService->selectAll("AND del = false AND status = 1 AND hide=0 ORDER BY price ASC");
			//获取历史兑换记录
			$gift_orders = $this->giftOrderService->getHistoryGiftOrders($openid);
			$success_order_times = 0;
			foreach ($gift_orders as $o){
				if($o['status'] == 1) $success_order_times++;
			}
			$ads = $this->adService->selectAll(" AND type=1");
			// 准备url
			$urls = array(
				'index' => $this->createMobileUrl('center'),
				'show' =>$this->createMobileUrl('show'),
				'center' => $this->createMobileUrl('center'),
				'api' => $this->createMobileUrl('urlsapi'),
				'gift_order_api' => $this->createMobileUrl('giftOrderApi'),
				'follow_url' => $this->module['config']['follow_url'],
				'my_gifts_api_url' => $this->createMobileUrl('myGifts'),
				'my_gift_order_detail_api' => $this->createMobileUrl('myGiftOrderDetail'),
				'score_record_url' => $this->createMobileUrl("ScoreRecordPage"),
			);
			$html = array(
				'config' => $this->module['config'],
				'user' => $user,
				'gifts' => $gifts,
				'gift_orders' => $gift_orders,
				'account_name' => $_W['uniaccount']['name'],
				'jsconfig' => $_W['account']['jssdkconfig'],
				'ads' => $ads,
				'follow' => $user['follow']
			);
			$share = array(
				'share_title' => $this->module['config']['share_title'],
				'share_logo' => $_W['attachurl'].$this->module['config']['share_logo'],
				'share_url' => $_W['siteroot'].'app'.substr($this->createMobileUrl('Index',array('openid'=>$_W['openid'])),1),
				'share_description' => $this->module['config']['share_description']
			);
			//返回模板
			include $this->template('index');
		}catch (WechatBrowserException $e){
			//用户是非微信浏览器
			return $this->return_json($e->getCode(),$e->getMessage(),null);
		}
	}

	/**
	 * 商品兑换api
	 */
	public function doMobileGiftOrderApi(){
		global $_GPC, $_W;
		$openid = $_W['openid'];
		if(!$this->module['config']['open']){
			return $this->return_json(400,'积分商城已经关闭,暂时无法兑换',null);
		}
		if(empty($openid)){
			return $this->return_json(400,'您还没有关注['.$_W['account']['name'].']',null);
		}

		$user = $this->userService->fetchFansInfo($_W['openid']);
		if($user['follow'] == 0){
			return $this->return_json(400,'您还没有关注['.$_W['account']['name'].']',null);
		}

		$gift = $this->giftService->selectById($_GPC['gift_id']);
		//存数据到数据库
		if($gift['num'] <= 0){
			return $this->return_json(400,$gift['name']."存货不足！");
		}
		//检查是否兑换过
		$extra_gift_orders = $this->giftOrderService->getCustomGiftOrder($gift['id']);
		if(sizeof($extra_gift_orders) >= $gift['limit_num'] && $gift['limit_num'] != 0){
			return $this->return_json(400,"该礼品每人仅允许兑换{$gift['limit_num']}次,您已经兑换了{$gift['limit_num']}次",null);
		}
		if($gift['price'] > $user['score']){
			return $this->return_json(400,"积分不足");
		}
		if($gift['uniacid'] != $_W['uniacid']){
			return $this->return_json(400,"非法操作");
		}
		$order_info = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $_W['openid'],
			'order_num' => time().rand(0,100),
			'gift' => $_GPC['gift_id'],
			'status' => 0,//状态 0进行中 1成功 2失败
			'name' =>$_GPC['name'],
			'mobile' =>$_GPC['mobile'],
			'target' => $_GPC['target'],//送货地址
			'createtime' => time(),
			'updatetime' => time(),
			'pay_method' => $_GPC['pay_method'],
			'pay_status' => 0,//默认邮费未支付
			'trans_num' => 0,//默认运单号为0
			'send_price' => $gift['send_price'],
			'order_price' => $gift['price'],
			'order_mode' => $gift['raffle']
		);
		if($gift['mode'] == 1){
			//微信红包
			$order_info['target'] = '';
			$order_info['name'] = $user['name'];
			$order_info['mobile'] = '';
			$order_info['pay_method'] = $_GPC['pay_method'];
			$order_info['order_hongbao_money'] = $this->getRedpockMoney($gift);
			$this->validate_post_data($order_info,array('gift'=>"非法操作"));
		}elseif($gift['mode'] == 2){
			//话费充值
			$order_info['target'] = '';
			$this->validate_post_data($order_info,array('gift'=>"非法操作",'mobile'=>'手机号不能为空'));
		}elseif($gift['mode'] == 3){
			//实物礼品
			$this->validate_post_data($order_info,array('gift'=>"非法操作",'name'=>"姓名不能为空",'mobile'=>'手机号不能为空','target'=>'领奖信息不能为空'));

		}elseif($gift['mode'] == 4){
			//自领礼品
			$order_info['target'] = '';
			$this->validate_post_data($order_info,array('gift'=>"非法操作",'name'=>"姓名不能为空",'mobile'=>'手机号不能为空'));
			//生成核销二维码
			$this->makeQrcodeFile($order_info['order_num']);
		}
		pdo_begin();
		try {
			$order_info = $this->giftOrderService->insertData($order_info);
			//增加已售出数量
			$this->giftService->columnAddCount('sold',1,$order_info['gift']);
			if($gift['raffle']){
				//进行抽奖
				if(!$this->randomRaffle($gift)){
					//没抽中奖
					pdo_commit();
					$order_info['raffle_status'] == 0;
					$this->giftOrderService->updateData($order_info);
					return $this->return_json(400,"很遗憾，您没有抽到",null);
				}else{
					//抽中奖 修改抽奖状态
					$order_info['raffle_status'] == 1;
					$this->giftOrderService->updateColumn('raffle_status',1,$order_info['id']);
				}
			}
			//减少礼品数量
			$this->giftService->columnReduceCount('num',1,$order_info['gift']);

			pdo_commit();

			//扣除用户积分
			$this->userService->updateUserScore($gift['price']*-1,$openid,'兑换礼品('.$gift['name'].')');
			$result = array(
				'order' => $order_info
			);
			if($gift['mode'] == 3 && $order_info['pay_method'] == 1){
				$result['pay_redirect'] = $_W['siteroot'].'app'.substr($this->createMobileUrl('giftOrderPay',array('order_num'=>$order_info['order_num'])),1);
			}
			//通知要用到
			$order_info = $this->giftOrderService->selectGiftOrdersDetail($order_info['id']);
			if($gift['mode'] == 1 || $gift['mode'] == 2 || $gift['mode'] == 4){

				$this->tplConfigService->sendGetGiftSuccessTplNotice($order_info,$_W['siteroot'].'app'.substr($this->createMobileUrl('Index',array('go'=>$_W['order_detail'],'id'=>$order_info['id'])),1));
			}elseif($gift['mode'] == 3){
				if($order_info['pay_method'] == 2){
					$this->tplConfigService->sendGetGiftSuccessTplNotice($order_info,$_W['siteroot'].'app'.substr($this->createMobileUrl('Index',array('go'=>$_W['order_detail'],'id'=>$order_info['id'])),1));
				}
			}
			//自动审核则继续通知审核通过
			if($gift['auto_success'] == 1){
				$this->accessGiftOrder($order_info['id']);
			}
			if($gift['raffle']){
				return $this->return_json(200, "恭喜您中奖啦,管理员审核后将会为您发放礼品", $result);
			}else{
				return $this->return_json(200, "兑换成功,管理员审核后将会发放礼品", $result);
			}
		} catch (Exception $e) {
			pdo_rollback();
			return $this->return_json(400, '兑换失败，未知异常，请联系管理员',null);
		}
	}

	/**
	 * 随机抽奖
	 * @param $gift
	 * @return bool
	 */
	private function randomRaffle($gift){
		$send_num = $gift['raffle_send_num'];
		$sendArr= explode(',',$send_num);
		$rand=rand($gift['raffle_min'],$gift['raffle_max']);
		$isInclude=in_array($rand,$sendArr);
		if($isInclude){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 支付方法
	 */
	public function doMobileGiftOrderPay(){
		global $_W, $_GPC;
		$order_num = $_GPC['order_num'];
		$order_info = $this->giftOrderService->selectByOrderNum($order_num);
		if(empty($order_info)){
			return message("非法操作，订单不存在");
		}
		if($order_info['pay_method'] == 1){
			$pay_data = array(
				'tid' => $order_info['order_num'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
				'ordersn' => $order_info['order_num'],  //收银台中显示的订单号
				'title' => '礼品的运费',           //收银台中显示的标题
				'fee' => $order_info['send_price'],      //收银台中显示需要支付的金额,只能大于 0
				'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
			);
			$this->giftOrderPay($pay_data);
		}

	}

	/**
	 * 支付方法
	 * @param array $params
	 */
	private function giftOrderPay($params = array()) {
		global $_W;
		if(!$this->inMobile) {
			message('支付功能只能在手机上使用');
		}
		if (empty($_W['member']['uid'])) {
			checkauth();
		}

		$params['module'] = $this->module['name'];
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];

		if($params['fee'] <= 0) {
			$pars['from'] = 'return';
			$pars['result'] = 'success';
			$pars['type'] = 'alipay';
			$pars['tid'] = $params['tid'];
			$site = WeUtility::createModuleSite($pars[':module']);
			$method = 'payResult';
			if (method_exists($site, $method)) {
				exit($site->$method($pars));
			}
		}

		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
		$log = pdo_fetch($sql, $pars);
		if(!empty($log) && $log['status'] == '1') {
			message('这个订单已经支付成功, 不需要重复支付.');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if(!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.');
		}
		$pay = $setting['payment'];
		if (!empty($pay['credit']['switch'])) {
			$credtis = mc_credit_fetch($_W['member']['uid']);
		}
		include $this->template('common/paycenter');
	}

	/**
	 * 支付回调
	 */
	public function payResult($params){
		/*
         * $params 结构
         *
         * weid 公众号id 兼容低版本
         * uniacid 公众号id
         * result 支付是否成功 failed/success
         * type 支付类型 credit 积分支付 alipay 支付宝支付 wechat 微信支付  delivery 货到付款
         * tid 订单号
         * user 用户id
         * fee 支付金额
         *
         * 注意：货到付款会直接返回支付失败，请在订单中记录货到付款的订单。然后发货后收取货款
         */
		global $_W;
		$fee = intval($params['fee']);
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		//如果是微信支付，需要记录transaction_id。
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		try{
			$order_info = $this->giftOrderService->selectByOrderNum($params['tid']);
			//支付后将订单支付状态改成成功
			$this->giftOrderService->updateColumn('pay_status',1,$order_info['id']);
			//支付后自动通过
			$this->giftOrderService->updateColumn('status',1,$order_info['id']);
			$gift = $this->giftService->selectById($order_info['gift']);
			if($gift['mode'] == 4){
				$this->tplConfigService->sendGetGiftSuccessTplNotice($order_info,$_W['siteroot'].'app'.substr($this->createMobileUrl('Index',array('go'=>$_W['order_detail'],'id'=>$order_info['id'])),1));
			}
			message('支付成功！', $this->createMobileUrl('Index'), 'success');
		}catch (Exception $e){
			message($e->getMessage(),$this->createMobileUrl('index1'), 'error');
		}
	}

	/**
	 * 查看我的礼品
	 */
	public function doMobileMyGiftOrderDetail(){
		global $_GPC, $_W;
		$id = $_GPC['id'];
		$gift_order = $this->giftOrderService->selectGiftOrdersDetail($id);
		if(empty($gift_order)){
			return $this->return_json(400,'不存在该礼品',null);
		}
		$gift_order['status_text'] = $this->giftOrderService->getGiftOrderStatusText($gift_order);
		$gift_order['create_time_text'] = date('m月d日 H:i',$gift_order['createtime']);
		$gift_order['pay_status_text'] = $gift_order['pay_status'] == 1 ? '已支付' : '未支付';
		$gift_order['hongbao_money'] = $gift_order['order_hongbao_money']/100;
		if($gift_order['pay_method'] == 1){
			$gift_order['pay_method_text'] = '微信支付';
		}elseif($gift_order['pay_method'] == 2){
			$gift_order['pay_method_text'] = '货到付款';
		}
		if($gift_order['mode'] == 4 && $gift_order['status'] == 1){//自领礼品 已经同意
			$gift_order['check_qrcode'] = $this->getCheckQrcodeUrl($gift_order['order_num']);
		}
		if($gift_order['mode'] == 3 && $gift_order['status'] == 0){
			$gift_order['pay_url'] =  $_W['siteroot'].'app'.substr($this->createMobileUrl('giftOrderPay',array('order_num'=>$gift_order['order_num'])),1);
		}
		return $this->return_json(200,'success',$gift_order);
	}

	/**
	 * 绑定管理员信息接口
	 */
	public function doMobileBindGiftAdmin(){
		global $_W, $_GPC;
		$password = $_GPC['password'];
		$openid = $_GPC['openid'];
		$this->validate_post_data($_GPC,array('password'=>"密码不能为空",'openid'=>"非法操作"));
		$gift = $this->giftService->selectByCheckPassword($password);
		if(empty($gift)){
			return $this->return_json(400,'不存在此核销密码,请检查是否输错',null);
		}else{
			//更新
			$admin = array(
				//id,uniacid,openid,shop_id
				'uniacid' => $_W['uniacid'],
				'openid' => $openid,
				'gift_id' => $gift['id']
			);
			//pdo_insert(LonakingCouponSQLHelper::$table['admin']['name'],$admin);
			$admin = $this->giftAdminService->insertData($admin);
			return $this->return_json(200,'您已成功绑定成为['. $gift['name']. ']的核销管理员',null);
		}
	}

	/**
	 * 核销页面
	 */
	public function doMobileGiftOrderCheckPage(){
		global $_W, $_GPC;
		$openid = $_W['openid'];
		$bind = $_GPC['bind'];
		$admins = $this->giftAdminService->selectByOpenid($openid);
		$html = array(
			'bind_gift_url' => $this->createMobileUrl('bindGiftAdmin'),
			'use_url' => $this->createMobileUrl('zilingGiftOrderCheck'),
			'jsconfig' => $_W['account']['jssdkconfig'],
			'openid' => $_W['openid'],
			'sao' => true
		);
		if($admins){
			$html['admins'] = $admins;
		}
		if($_W['account']['level'] != 4){
			$html['sao'] = false;
		}
		if($admins && empty($bind)){
			include $this->template('scan');
		}else{
			include $this->template('bind-admin');
		}
	}
	/**
	 * 核销一个自领礼品
	 */
	public function doMobileZilingGiftOrderCheck(){
		global $_W, $_GPC;
		$gift_order_num = $_GPC['order_num'];
		$admin_openid = $_GPC['openid'];
		if(empty($admin_openid)){
			return $this->return_json(400,'非法操作',"admin_openid can not be none!");
		}
		$gift_order = $this->giftOrderService->selectByOrderNum($gift_order_num);
		if(empty($gift_order)){
			return $this->return_json(400,'非法操作,不存在此条兑换记录',null);
		}
		if($gift_order['status'] == 5){
			return $this->return_json(400,'核销失败:该兑换记录已经被核销','');
		}
		$admin = $this->giftAdminService->selectByOpenidAndGiftId($admin_openid,$gift_order['gift']);
		if(empty($admin)){
			return $this->return_json(400,'非法操作,你并不是管理员',null);
		}
		//4 检验核销管理员是否对此商铺有权限操作
		if($admin['gift_id'] != $gift_order['gift']){
			return $this->return_json(400,'无权限,您无法核销此条兑换记录',$admin);
		}
		//5. 检验完毕,通过检验 进行消费
		try{
			$this->giftOrderService->updateColumn('status',5,$gift_order['id']);
			return $this->return_json(200,'核销成功',null);
		}catch(Exception $e){
			return $this->return_json(400,'系统异常',null);
		}
	}


	/**
	 * 我的礼品 用户查看自己兑换的礼品列表
	 */
	public function doMobileMyGifts(){
		global $_GPC, $_W;
		date_default_timezone_set('Asia/Shanghai');
		$openid = $_W['openid'];
		$my_gift_orders = $this->giftOrderService->selectMyOrdersWithGiftInfo($openid);
		if(empty($my_gift_orders)){
			return $this->return_json(5004,'你还没有兑换任何礼品',null);
		}
		$result = array();
		$new_result = array();
		$succes_result = array();
		for($i=0;$i<sizeof($my_gift_orders);$i++){
			$tmp_gift_order = $my_gift_orders[$i];
			$status_text = $this->giftOrderService->getGiftOrderStatusText($tmp_gift_order);
			$tmp_gift_order['status_text'] = $status_text;
			$tmp_gift_order['create_time_text'] = date('m月d日 H:i',$tmp_gift_order['createtime']);
			if($tmp_gift_order['status'] == 0 || ($tmp_gift_order['mode'] ==4 && $tmp_gift_order['status'] == 1) || ($tmp_gift_order['mode'] == 3 && $tmp_gift_order['trans_num'] == '0')){
				//新的
				$new_result[] = $tmp_gift_order;
			}elseif(($tmp_gift_order['status'] == 1 && $tmp_gift_order['mode'] !=4 ) || $tmp_gift_order['status'] == 5 || $tmp_gift_order['status'] == 2 || ($tmp_gift_order['mode'] == 3 && $tmp_gift_order['trans_num'] != '0')){
				//完成
				$succes_result[] = $tmp_gift_order;
			}
		}
		$result['new_gifts'] = $new_result;
		if(empty($new_result)){
			$result['new_gifts'] = null;
		}
		$result['success_gifts'] = $succes_result;
		if(empty($succes_result)){
			$result['success_gifts'] = null;
		}
		return $this->return_json(200,'success',$result);
	}

	public function doMobileScoreRecordPage(){
		global $_GPC,$_W;
		if(empty($_W['openid'])){
			return message("非法访问","","error");
		}
		$user = $this->userService->fetchFansInfo($_W['openid']);
		$html = array(
			'user' => $user
		);
		$urls = array(
			'score_record' => $this->createMobileUrl("ScoreRecord"),
			'gift_shop_url' => $this->createMobileUrl("Index"),
			'gift_order_url' => $this->createMobileUrl("Index",array('go'=>'mygifts'))
		);
		include $this->template("score-record");
	}

	public function doMobileScoreRecord(){
		global $_GPC,$_W;
		$openid = $_W['openid'];
		$pageIndex = $_GPC['page'];
		$pageSize = $_GPC['size'];
		$page = $this->creditService->fetchUserCreditRecordPage($openid,$pageIndex,$pageSize);
		return $this->return_json(200,'success',$page);
	}
	/**
	 * 安全检测
	 */
	private function safecheck(){
		global $_GPC, $_W;
		//1. 判断useragent
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($user_agent, 'MicroMessenger') == false){
			//设置cookie 危险
			setcookie("__d","1",time()+(3600*24*30),"/");
			//throw new Exception("请在微信浏览器中打开",5040);
			throw new WechatBrowserException();
			//exit(json_encode(array('status' => 500,'message' =>"请在微信浏览器中打开", 'data'=>"your mobile phone maybe Nokia or Lumia,drop it ,now!")));
		}
		//2 .判断cookie
		$danger = $_COOKIE['__d'];
		if($danger == 1){
			//throw new Exception("请在微信浏览器中打开",5040);
			throw new WechatBrowserException();
			//exit(json_encode(array('status' => 500,'message' =>"请在微信浏览器中打开", 'data'=>"please open this page in wechat browser")));
		}
		//3. 判断是否是该用户
		//if(empty($_W['openid']) || empty($_W['fans']['fanid']))
		//    exit(json_encode(array('status'=>400,'message'=>'请在微信浏览器中打开','data'=>"please open this page in wechat browser")));
	}
	/**
	 * 校验 表单的内容是否为空，效率一般
	 */
	private function validate_post_data($validate_info, $validate_options){
		foreach ($validate_info as $info_key => $info_value) {//key : 表单字段名 $value:值
			foreach ($validate_options as $opt_key => $opt_message) {//$opt_key : 要验证的字段名 $opt_value:提示
				if($info_key == $opt_key){
					if(empty($info_value)){
						exit(json_encode(array('status' => 400,'message' =>$opt_message,'data'=>null)));
					}
				}
			}
		}
	}

	/**
	 * return json to web
	 * @param int $status
	 * @param string $message
	 * @param null $data
	 */
	private function return_json($status = 200,$message = 'success',$data = null){
		exit(json_encode(
			array(
				'status' => $status,
				'message' => $message,
				'data' => $data
			)
		)
		);
	}

	/**
	 * 随机红包取出一个随机数
	 */
	private function getRedpockMoney($gift){
		if($gift['hongbao_mode'] == 1){//定额红包
			return $gift['hongbao_money'];
		}elseif($gift['hongbao_mode'] == 2){//随机红包
			$send_num = $gift['hongbao_send_num'];
			$sendArr= explode(',',$send_num);
			$rand=rand($gift['hongbao_min'],$gift['hongbao_max']);
			$isInclude=in_array($rand,$sendArr);
			if($isInclude){
				return $gift['hongbao_money'] + $rand;
			}else{
				return $gift['hongbao_money'];//没有抢到红包
			}
		}
	}

	/**
	 * 发放红包
	 * @param $param_openid
	 * @return string
	 */
	private function sendRedpack($param_openid,$money = 0,$is_random = false){
		$old_money = $money;
		define('DS', DIRECTORY_SEPARATOR);
		define('SIGNTYPE', "sha1");
		define('APPID',$this->module['config']['appid']);
		define('MCHID',$this->module['config']['mchid']);
		define('PARTNERKEY',$this->module['config']['partner']);
		define('NICK_NAME',$this->module['config']['nick_name']);
		define('SEND_NAME',$this->module['config']['send_name']);
		define('WISHING',$this->module['config']['wishing']);//祝福语
		define('ACT_NAME',$this->module['config']['act_name']);
		define('REMARK',$this->module['config']['remark']);

		define('apiclient_cert',$this->module['config']['apiclient_cert']);
		define('apiclient_key',$this->module['config']['apiclient_key']);
		define('rootca',$this->module['config']['rootca']);//证书

		define('money',$this->module['config']['money']);
		define('money_extra',$this->module['config']['money_extra']);
		define('min',$this->module['config']['randmin']);
		define('max',$this->module['config']['randmax']);
		define('sendNum',$this->module['config']['sendnum']);
		// 1. 随机红包金额 {固定金额 随即红包}
		$isInclude = false;

		if($is_random){
			//随机红包
			$money=money+rand(0,money_extra);
			$min=min;
			$max=max;
			$sendNum=sendnum;
			$sendArr= explode(',',sendNum);
			$rand=rand(min,max);
			$isInclude=in_array($rand,$sendArr);
		}else{
			//固定红包
			$money = $old_money;

		}
		if($isInclude || !$is_random){

			$mch_billno=MCHID.date('YmdHis').rand(1000, 9999);//订单号
			$commonUtil = new CommonUtil();
			$wxHongBaoHelper = new WxHongBaoHelper();
			$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
			$wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
			$wxHongBaoHelper->setParameter("mch_id", MCHID);//商户号
			$wxHongBaoHelper->setParameter("wxappid", APPID);
			$wxHongBaoHelper->setParameter("nick_name",NICK_NAME);//提供方名称
			$wxHongBaoHelper->setParameter("send_name", SEND_NAME);//红包发送者名称
			$wxHongBaoHelper->setParameter("re_openid", $param_openid);//相对于医脉互通的openid
			$wxHongBaoHelper->setParameter("total_amount", $money);//付款金额，单位分
			$wxHongBaoHelper->setParameter("min_value", $money);//最小红包金额，单位分
			$wxHongBaoHelper->setParameter("max_value", $money);//最大红包金额，单位分
			$wxHongBaoHelper->setParameter("total_num", 1);//红包发放总人数
			$wxHongBaoHelper->setParameter("wishing",WISHING );//红包祝福诧
			$wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
			$wxHongBaoHelper->setParameter("act_name", ACT_NAME);//活劢名称
			$wxHongBaoHelper->setParameter("remark",REMARK);//备注信息

			$postXml = $wxHongBaoHelper->create_hongbao_xml();

			$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

			$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
			$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
			$return_code=$responseObj->return_code;
			$result_code=$responseObj->result_code;

			if($return_code=='SUCCESS'){
				if($result_code=='SUCCESS'){
					$total_amount=$responseObj->total_amount*1.0/100;
					return "红包发放成功！金额为：".$total_amount."元！";
				}else{


					if($responseObj->err_code=='NOTENOUGH'){
						//return "您来迟了，红包已经发完！！！";
						throw new WxHongBaoException('您来迟了，红包已经发完！！！',10401);
					}else if($responseObj->err_code=='TIME_LIMITED'){
						//return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
						throw new WxHongBaoException('现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取',10402);
					}else if($responseObj->err_code=='SYSTEMERROR'){
						//return "系统繁忙，请稍后再试！";
						throw new WxHongBaoException('系统繁忙，请稍后再试!',10403);
					}else if($responseObj->err_code=='DAY_OVER_LIMITED'){
						//return "今日红包已达上限，请明日再试！";
						throw new WxHongBaoException('今日红包已达上限，请明日再试!',10404);
					}else if($responseObj->err_code=='SECOND_OVER_LIMITED'){
						//return "每分钟红包已达上限，请稍后再试！";
						throw new WxHongBaoException('每分钟红包已达上限，请稍后再试!',10405);
					}

					//return "红包发放失败！".$responseObj->return_msg."！请稍后再试！";
					throw new WxHongBaoException("红包发放失败！".$responseObj->return_msg."！请稍后再试！",10406);
				}
			}else{

				if($responseObj->err_code=='NOTENOUGH'){
					//return "您来迟了，红包已经发放完！！!";
					throw new WxHongBaoException('您来迟了，红包已经发完！！！',10401);
				}else if($responseObj->err_code=='TIME_LIMITED'){
					//return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
					throw new WxHongBaoException('现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取',10402);
				}else if($responseObj->err_code=='SYSTEMERROR'){
					//return "系统繁忙，请稍后再试！";
					throw new WxHongBaoException('系统繁忙，请稍后再试!',10403);
				}else if($responseObj->err_code=='DAY_OVER_LIMITED'){
					//return "今日红包已达上限，请明日再试！";
					throw new WxHongBaoException('今日红包已达上限，请明日再试!',10404);
				}else if($responseObj->err_code=='SECOND_OVER_LIMITED'){
					//return "每分钟红包已达上限，请稍后再试！";
					throw new WxHongBaoException('每分钟红包已达上限，请稍后再试!',10405);
				}
				//return "红包发放失败！".$responseObj->return_msg."！请稍后再试！";
				throw new WxHongBaoException("红包发放失败！".$responseObj->return_msg."！请稍后再试！",10406);
			}
		}else{
			//return "很遗憾，您没有抢到红包！感谢您的参与！";
			throw new WxHongBaoException("很遗憾，您没有抢到红包！感谢您的参与！",10407);
		}
	}

	/**
	 * 生成二维码
	 * @param $coupon_record
	 * @return array|bool
	 */
	private function makeQrcodeFile($order_num){
		global $_GPC, $_W;
		require (IA_ROOT.'/framework/library/qrcode/phpqrcode.php');
		//判断文件夹是否存在
		load()->func('file');
		if(!file_exists(ATTACHMENT_ROOT.'/lonaking_gift_shop')){
			mkdirs(ATTACHMENT_ROOT.'/lonaking_gift_shop');
		}
		$filename = ATTACHMENT_ROOT.'/lonaking_gift_shop/'.$order_num.'.png';
		//生成二维码
		QRcode::png($order_num,$filename,'L',6,2);
		//$qrcode_url = $_W['attachurl'].$filename;
	}

	/**
	 * 获取验证二维码地址
	 * @param $order_num
	 * @return string
	 */
	private function getCheckQrcodeUrl($order_num){
		global $_W;
		return $_W['attachurl'].'/lonaking_gift_shop/'.$order_num.'.png';
	}

	/**
	 * 系统页面
	 */
	public function doWebUpdatePage(){
		global $_GPC, $_W;
		$urls = array(
			'update_url' => $this->createWebUrl('updateLonaking'),
		);
		include $this->template('update_page');
	}
	/**
	 * 手动更新接口
	 */
	public function doWebUpdateLonaking(){

		if(!pdo_fieldexists('lonaking_gift_shop_gift','hide')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `hide` tinyint(1) default '0' COMMENT '是否隐藏 1隐藏 0不隐藏'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','sold')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `sold` int(11) default '0' COMMENT '已售出数量'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','limit_num')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `limit_num` int(11) default '0' COMMENT '限制领取次数'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','raffle')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `raffle` tinyint(1) default '0' COMMENT '是否是抽奖:0普通模式 1抽奖'");
		}

		if(!pdo_fieldexists('lonaking_gift_shop_gift_order','raffle_status')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift_order') . " ADD `raffle_status` tinyint(1) default '0' COMMENT '是否中奖:0未中奖 1中奖'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','hongbao_mode')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `hongbao_mode` tinyint(1) default '1' COMMENT '1定额红包 2随机红包'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift_order','order_mode')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift_order') . " ADD `order_mode` tinyint(1) default '0' COMMENT '0.默认正常模式 1抽奖模式'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift_order','order_hongbao_money')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift_order') . " ADD `order_hongbao_money` int(11) default '0' COMMENT '红包金额'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','hongbao_min')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `hongbao_min` int(11) default '0' COMMENT '红包随机下限'");
		}

		if(!pdo_fieldexists('lonaking_gift_shop_gift','hongbao_max')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `hongbao_max` int(11) default '0' COMMENT '红包随机上限'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','hongbao_send_num')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `hongbao_send_num` varchar(255) default '' COMMENT '随机红包命中随机数'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','raffle_min')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `raffle_min` int(11) default '0' COMMENT '随机下限'");
		}

		if(!pdo_fieldexists('lonaking_gift_shop_gift','raffle_max')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `raffle_max` int(11) default '0' COMMENT '随机上限'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','raffle_send_num')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `raffle_send_num` varchar(255) default '' COMMENT '中奖号码'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift','auto_success')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift') . " ADD `auto_success` tinyint(1) default '0' COMMENT '是否自动审核 0:否 1:是'");
		}
		if(!pdo_fieldexists('lonaking_gift_shop_gift_order','remark')){
			pdo_query("ALTER TABLE " . tablename ('lonaking_gift_shop_gift_order') . " ADD `remark` varchar(255) default '' COMMENT '备注，拒绝理由等'");
		}
		return $this->return_json(200,'更新成功',null);
	}

}