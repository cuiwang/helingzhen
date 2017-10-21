<?php 
if(!defined('IN_IA')){
	exit('Access Denied');
}

class Welian_Indiana_Codes{
	/***************夺宝码分配入口*************/
	public function code($openid='',$tid = '',$uniacid=0,$machine_time = ''){
		global $_W;
		load()->func('communication');
		$myCart = self::getCart($openid,$uniacid);	//购物车数据
		/*********判断进入路径*********/
		$h = $_SERVER['HTTP_REFERER'];
		$app = explode('/app', $h);
		$web = explode('/web', $h);
		$weisite = explode('/weisite', $h);

		$app_web = '/web';
		if($app[0] != ''){
			$app_web = '/app';
		}elseif($web[0] != ''){
			$app_web = '/web';
		}elseif($weisite[0] != ''){
			$app_web = '/weisite';
		}
		
		if(strstr($openid,'machine') == ''){
			//判断是否是真实用户。并且判断是否非法访问
			/****************检索购买夺宝码开始*****************/
			$record = pdo_fetch("SELECT * FROM " . tablename('weliam_indiana_rechargerecord') . " WHERE ordersn ='{$tid}'");//获取商品ID
			$num_money = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where uniacid = '{$_W['uniacid']}' and openid = '{$openid}'");
			$money = 0;
			foreach($num_money as $key =>$value){
				$goodsid = pdo_fetchcolumn("select goodsid from".tablename('weliam_indiana_period')."where period_number = '{$value['period_number']}'");
				$init_money = pdo_fetchcolumn("select init_money from".tablename('weliam_indiana_goodslist')."where id = '{$goodsid}'");
				$money = $money+$init_money*$value['num'];
			}
			if($record['num'] < 1 || $record['num'] != $money || $record['num'] == '' || $record['type'] == 1){
				return 'false';		//非法操作返回false
			}
			/****************检索购买夺宝码结束****************/
		}

		foreach ($myCart as $key => $value) {
			$flag = 1; 	//定义flag标记
			//清算购物车中数据
			m('log')->WL_log('check_codes','检测参数',$openid.'||'.$value['num'].'||'.$value['period_number'],$uniacid);
			$left_money = self::get_codes($value['period_number'],$value['num'],$openid,$value['ip'],$value['ipaddress']);
			if($left_money == 'false' || $left_money == FALSE){//判断是否分码错误
				m('log')->WL_log('codes','错误取码错误次数',$flag,$uniacid);
				$left_money = self::get_codes($value['period_number'],$value['num'],$openid,$value['ip'],$value['ipaddress']);
				if($left_money == 'false' || $left_money == FALSE){
					//二次验证
					$url_file_b = $_SERVER["REQUEST_URI"];
					$url_o = explode($app_web, $url_file_b);
					$url = 'http://'.$_SERVER['SERVER_NAME'].$url_o[0].'/addons'.'/weliam_indiana/core/api/checkcodes.api.php';			//路径组合
					$http = ihttp_request($url, array('uniacid' => $_W['uniacid'],'period_number'=>$value['period_number']),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
					m('log')->WL_log('check_codes',$url.'||'.'检测回传数据',$http,$uniacid);
					return FALSE;exit;
				}
			}
			if($left_money == 'true_A'){
				$left_money = 0;
			}
			//判定消费金额
			$data_period = array(
				':uniacid' => $_W['uniacid'],
				':period_number' => $value['period_number']
			);
			$result_period = pdo_fetch("select id,goodsid,shengyu_codes,period_number,status from ".tablename('weliam_indiana_period')." where uniacid = :uniacid and period_number = :period_number",$data_period);
			$goodsid = $result_period['goodsid'];
			$result_goodslist = m('goods')->getGoods($goodsid);
			$init_money = $result_goodslist['init_money'];
			$periods = $result_goodslist['periods'];
			$allmoney = ($value['num'] - $left_money) * $init_money;	//总共需要金钱
			m('credit')->updateCredit2($value['openid'],$_W['uniacid'],-$allmoney,'购买商品余额操作');
			self::deleteCart($value['id']);		//清除购物车中商品记录
			
			if($result_period['shengyu_codes'] < 1 && $result_period['status'] == 1){
				//本期商品结束进入开奖阶段再次检查
				$data_periods_check = array(
						':uniacid'=>$_W['uniacid'],
						':periods'=>$periods,
						':goodsid'=>$goodsid
					);
				$result_periods_check = pdo_fetch("select shengyu_codes,period_number from".tablename('weliam_indiana_period')."where uniacid = :uniacid and periods = :periods and goodsid = :goodsid",$data_periods_check);
				if($result_periods_check == FALSE){//在检测一遍
					$result_periods_check = pdo_fetch("select shengyu_codes,period_number from".tablename('weliam_indiana_period')."where uniacid = ".$_W['uniacid']." and periods = ".$periods." and goodsid = ".$goodsid);
				}
				if($result_periods_check['shengyu_codes']  < 1 && !empty($result_periods_check['period_number'])){
					//加强监测
					self::createtime_winer($result_period['id'],$result_period['period_number']);
					if($result_goodslist['periods'] < $result_goodslist['maxperiods']){
						//判断商品是否有下一期
						$data_periods_one = array(
							':uniacid'=>$_W['uniacid'],
							':periods'=>$periods,
							':goodsid'=>$goodsid
						);
						$result_periods_one = pdo_fetch("select shengyu_codes from".tablename('weliam_indiana_period')."where uniacid = :uniacid and periods = :periods and goodsid = :goodsid",$data_periods_one);
						if($result_periods_one['shengyu_codes'] < 1){
							m('log')->WL_log('codes','检测开出新的一期goodsid为【'.$goodsid.'】',$result_periods_one);
							self::create_newgoods($result_goodslist['id']);
						}
					}else{
						//判断是否期数已满
						pdo_update('weliam_indiana_goodslist',array('status'=>0),array('id'=>$goodsid));
					}
				}
			}elseif($result_period['shengyu_codes'] < 0){
				//剩余码不正确
				return 'false';
			}
		}
		return TRUE;

	}
	/**********计算新的一期商品夺宝码或者创建新的商品********/
	public function create_newgoods($goodsid = ''){
		global $_W;
		if($goodsid != ''){
			//判断是否已经有正在进行的期数
			$sql_check = "select id,status from".tablename('weliam_indiana_period')." where uniacid=:uniacid and goodsid=:goodsid and status=:status";
			$data_check = array(
				':uniacid'=>$_W['uniacid'],
				':goodsid'=>$goodsid,
				':status'=>1
			);
			$result_check = pdo_fetchall($sql_check,$data_check);
			m('log')->WL_log('codes','goodsid为【'.$goodsid.'】检测是否有相同期在上架中',$result_check);
			if(!empty($result_check)){
				return 'false';
			}
			//创建新的一期商品
			$select_goodslist = " canyurenshu,id,maxperiods,periods,price,jiexiao_time,couponid,uniacid,init_money,next_init_money,sort ";
			$sql_goodslist = "select ".$select_goodslist." from ".tablename('weliam_indiana_goodslist')." where uniacid = :uniacid and id = :id";
			$data_goodslist = array(
				':uniacid' => $_W['uniacid'],
				':id' => $goodsid
			);

			$result_goodslist = pdo_fetch($sql_goodslist,$data_goodslist);
			if($result_goodslist['maxperiods'] <= $result_goodslist['periods']){
				//判断是否期数已满
				pdo_update('weliam_indiana_goodslist',array('status'=>0),array('id'=>$result_goodslist['id']));
			}
	
			if($result_goodslist['next_init_money'] != 0){
				//判定是否修改了商品专区价格
				$result_goodslist['init_money'] = $result_goodslist['next_init_money'];
				pdo_update('weliam_indiana_goodslist',array('init_money'=>$result_goodslist['next_init_money'],'next_init_money'=>0),array('id'=>$result_goodslist['id']));
			}

			$codes_num = intval($result_goodslist['price'])/$result_goodslist['init_money'];	//夺宝码数量
			$allcodes = self::create_codes_group($codes_num);		//获取压缩夺宝码段
			$new_period['canyurenshu'] = 0;
			$new_period['scale'] = 0;
			$new_period['goodsid'] = $goodsid;
			$new_period['periods'] = intval($result_goodslist['periods']) + 1;
			$new_period['jiexiao_time'] = $result_goodslist['jiexiao_time'];
			$new_period['uniacid'] = $_W['uniacid'];
			$new_period['shengyu_codes'] = $codes_num;
			$new_period['zong_codes'] = $codes_num;
			$new_period['status'] = 1;
			$new_period['scale'] = 0;
			$new_period['createtime'] = time();
			$new_period['sort'] = $result_goodslist['sort'];
			$new_period['allcodes'] = $allcodes;
			$new_period['period_number'] = date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));

			$result_insert = pdo_insert('weliam_indiana_period',$new_period);		//新数据生成插入
			pdo_update("weliam_indiana_goodslist",array('periods'=>$new_period['periods']),array('uniacid'=>$_W['uniacid'],'id'=>$goodsid));
			self::create_code($new_period['period_number']);
			return $new_period['period_number'];
		}
	}

	/**********计算新的压缩字段********/
	public function create_codes_group($codes_number = 0){
		global $_W;
		$codes_ervery = 5;		//设置每组大小
		$codes_group = intval($codes_number/$codes_ervery);		//夺宝码组数
		$codes_group_last = intval($codes_number%$codes_ervery);	//夺宝码最后一组个数
		if($codes_group_last != 0){
			$codes_group++;		//有余数组数加1
		}

		$codes_group_new = array();
		for($i = 0;$i < $codes_group;$i++){
			if($codes_group_last != 0 && $i == $codes_group-1){
				$codes_group_new[$i] = $i*$codes_ervery.':'.($i*$codes_ervery+$codes_group_last); //最后一个区段
			}else{
				$codes_group_new[$i] = $i*$codes_ervery.':'.($i+1)*$codes_ervery;		//创建区段
			}
		}
		shuffle($codes_group_new);			//打乱数组
		$allcodes = serialize($codes_group_new);		//压缩数组
		return $allcodes;
	}

	/***************计算夺宝码****************/
	public function create_code($period_number = '',$flag = 0){
		global $_W;
		$sql_period = "select id,period_number,codes,shengyu_codes,allcodes,canyurenshu from ".tablename('weliam_indiana_period')." where uniacid = :uniacid and period_number = :period_number";
		$data_period = array(
				':uniacid' => $_W['uniacid'],
				':period_number' => $period_number
			);

		$result_period = pdo_fetch($sql_period,$data_period);
		$group_number  = 40;		//	取得区间组数
		$codes_ervery = 5;		//夺宝码区间个数
		$allcodes = unserialize($result_period['allcodes']);		//解压所有码段
		$needcodes = array();			//设置夺宝码数组
		if($result_period['shengyu_codes'] < ($group_number * $codes_ervery)){
			$need_groupnum = sizeof($allcodes);
			$left_codes = '';
		}else{
			$need_groupnum = $group_number;
			$left_codes = array_slice($allcodes,$need_groupnum,sizeof($allcodes)-$need_groupnum);
			if(!is_array($left_codes)){
				if($flag == 1){
					return 'false';
				}else{
					self::create_code($period_number,1);
				}						//检测剩余码
			}
			$left_codes = serialize($left_codes);		//压缩剩余夺宝码段
		}
		//剩余码小于单次取得数
		for($i = 0;$i < $need_groupnum ; $i++){
			$codes_ervery_group = array_slice($allcodes,$i,1);		//从第0个取值取一个
			$arr = explode(':', $codes_ervery_group[0]);		//分隔字符串
			for($j = intval($arr[0]) ; $j < intval($arr[1]) ; $j++){
				//合成夺宝码
				$num = $j;		//次序
				$needcodes[$num] = 1000001+$num;	//夺宝码合成
			}
		}
		shuffle($needcodes);			//打乱夺宝码
		if(!is_array($needcodes)){		//检测生成码
				if($flag == 1){
					return 'false';
				}else{
					self::create_code($period_number,1);
				}					
			}
		$needcodes = serialize($needcodes);
		pdo_update('weliam_indiana_period',array('codes'=>$needcodes,'allcodes'=>$left_codes),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number));
	}

	/***************取出夺宝码**************/
	public function get_codes($period_number = '',$codes_number = 0,$openid = '',$ip = '',$ipaddress = ''){
		global $_W;
		$sql_period = "select id,goodsid,period_number,codes,shengyu_codes,allcodes,canyurenshu,zong_codes from ".tablename('weliam_indiana_period')." where uniacid = :uniacid and period_number = :period_number";
		$sql_record = "select * from ".tablename('weliam_indiana_record')." where uniacid = :uniacid and openid = :openid and period_number = :period_number";
		$data_period = array(
				':uniacid' => $_W['uniacid'],
				':period_number' => $period_number
			);
		$data_record = array(
				':uniacid' => $_W['uniacid'],
				':openid' => $openid,
				':period_number' => $period_number
			);

		$result_period = pdo_fetch($sql_period,$data_period);		//检索商品信息
		$result_record = pdo_fetch($sql_record,$data_record);
		if($result_period == FALSE){
			//判断是否查询错误
			m('log')->WL_log('check_false','检查错误位置','查询错误',$uniacid);
			return 'false';
		}
		
		$sql_goodslist_new = "select init_money from ".tablename('weliam_indiana_goodslist')." where uniacid = :uniacid and id = :id";
		$data_goodslist_new = array(
				':uniacid' => $_W['uniacid'],
				':id' => $result_period['goodsid']
			);		//检索购买该商品记录
		$result_goodslist_init_money = pdo_fetchcolumn($sql_goodslist_new,$data_goodslist_new);	//检索商品的init_money
		
		/*********记录信息参数开始*********/
		$ordersn=date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
		$record['openid'] = $openid;
		$record['uniacid'] = $_W['uniacid'];
		$record['ordersn'] = $ordersn;
		$record['status'] = 1;
		$record['goodsid'] = $result_period['goodsid'];
		$record['createtime'] = time();
		$record['period_number'] = $period_number;
		/*********记录信息参数结束*********/
		$consumerecord['openid'] = $openid;
		$consumerecord['uniacid'] = $_W['uniacid'];
		$consumerecord['status'] = 1;
		$consumerecord['period_number'] = $period_number;
		$consumerecord['ip'] = $ip;
		$consumerecord['ipaddress'] = $ipaddress;
		$consumerecord['createtime'] = time();
		$codes = unserialize($result_period['codes']);
		$codes_num = sizeof($codes);		//检测现有code的数量
		$buy_codes = array();
		if($codes_number == 0){
			return 'true_A';
		}
		if($codes_num == 0 && ($result_period['allcodes'] == 'a:0:{}' ||  $result_period['allcodes'] == '')){
			return 'false';
		}
		if($result_period['shengyu_codes'] > 0 && ($result_period['codes'] == 'a:0:{}' || $result_period['codes'] == '') && ($result_period['allcodes'] == 'a:0:{}' ||  $result_period['allcodes'] == '')){
			return 'false';
		}
		if($codes_number <= $codes_num){
			//现有生成夺宝码足够不用再次生成新的夺宝码
			$buy_codes = array_slice($codes,0,$codes_number);
			if(!is_array($buy_codes)){	//判断分码正确
				return 'false';
			}
			$left_codes = array_slice($codes,$codes_number,$codes_num-$codes_number);
			if(!is_array($left_codes)){	//判断分码正确
				return 'false';
			}
			$left_codes = serialize($left_codes);
			$shengyu_codes = $result_period['shengyu_codes'] - $codes_number;
			$canyurenshu = $result_period['canyurenshu'] + $codes_number;
			$scale = intval($canyurenshu*100/$result_period['zong_codes']);
			
			pdo_update('weliam_indiana_period',array('codes'=>$left_codes,'shengyu_codes'=>$shengyu_codes,'canyurenshu'=>$canyurenshu,'scale'=>$scale),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number));
			if(empty($result_record)){
				//未曾有购买记录
				$record['code'] = serialize($buy_codes);		//购买码
				$record['count'] = $codes_number;	//购买个数
				pdo_insert('weliam_indiana_record',$record);
			}else{
				$new_code = array_merge(unserialize($result_record['code']),$buy_codes);	//组合两个数组
				$new_count = $codes_number + $result_record['count'];
				$result = pdo_update('weliam_indiana_record',array('code' => serialize($new_code),'count' => $new_count),array('uniacid'=>$_W['uniacid'],'period_number' => $period_number,'openid' => $openid));
			}
			$consumerecord['num'] = $codes_number*$result_goodslist_init_money;		//计算实际购买数量
			/************判定是否有重复的消费记录开始***********/
			if($consumerecord['num'] > 0){
				$consumerecord['createtime'] = time();
				pdo_insert("weliam_indiana_consumerecord",$consumerecord);						//产生消费记录
			}
			if($shengyu_codes > 0 && $left_codes == 'a:0:{}'){						//判断是否重新生成夺宝码
				self::create_code($period_number);
			}
			return 'true_A';
		}else{
			//现有生成夺宝码不够,需要再次生成新的夺宝码
			$left_codes_number = $codes_number;
			$left_shengyu_codes = $result_period['shengyu_codes'];		//赋值剩余code数量
			for($i = $codes_number; $i > 0 && $left_shengyu_codes > 0;){
				$sql_period_new = "select id,goodsid,period_number,codes,shengyu_codes,allcodes,canyurenshu,zong_codes from ".tablename('weliam_indiana_period')." where uniacid = :uniacid and period_number = :period_number";
				$sql_record_new = "select * from ".tablename('weliam_indiana_record')." where uniacid = :uniacid and openid = :openid and period_number = :period_number";
				$data_period_new = array(
					':uniacid' => $_W['uniacid'],
					':period_number' => $period_number
				);
				$data_record_new = array(
					':uniacid' => $_W['uniacid'],
					':openid' => $openid,
					':period_number' => $period_number
				);

				$result_period_new = pdo_fetch($sql_period_new,$data_period_new);		//检索实时数据
				$result_record_new = pdo_fetch($sql_record_new,$data_record_new);		//检索购买该商品记录
				if($result_period_new == FALSE){
					return 'false';
				}

				$codes = unserialize($result_period_new['codes']);		//解压codes
				$new_codes_num = sizeof($codes);						//计算codes大小

				if($result_period_new['shengyu_codes'] > 0 && $i > 0){
					if($new_codes_num <= $i){
						//当前拥有的夺宝码少于或者等于需要夺宝码
						$buy_codes = array_slice($codes,0,$new_codes_num);
						if(!is_array($buy_codes)){	//判断分码正确
							return 'false';
						}
						$left_codes = '';
						$left_codes = serialize($left_codes);
						$shengyu_codes = $result_period_new['shengyu_codes'] - $new_codes_num;
						$canyurenshu = $result_period_new['canyurenshu'] + $new_codes_num;
						$scale = intval($canyurenshu*100/$result_period_new['zong_codes']);

						pdo_update('weliam_indiana_period',array('codes'=>$left_codes,'shengyu_codes'=>$shengyu_codes,'canyurenshu'=>$canyurenshu,'scale'=>$scale),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number));
						$i = $i - $new_codes_num;		//剩余未购买夺宝码数量
						if(empty($result_record_new)){
							//第一次购买
							$record['code'] = serialize($buy_codes);		//购买码
							$record['count'] = $new_codes_num;	//购买个数
							pdo_insert('weliam_indiana_record',$record);
						}else{
							//多次购买
							if(unserialize($result_record_new['code']) == ''){
								$new_code = $buy_codes;
							}else{
								$new_code = array_merge(unserialize($result_record_new['code']),$buy_codes);	//组合两个数组
							}
							$new_count = $new_codes_num + $result_record_new['count'];
							pdo_update('weliam_indiana_record',array('code' => serialize($new_code),'count' => $new_count),array('uniacid'=>$_W['uniacid'],'period_number' => $period_number,'openid' => $openid));
						}
						$left_shengyu_codes = $shengyu_codes;		//重新赋值剩余数量
						if($shengyu_codes > 0){
							self::create_code($period_number);
						}

					}else{
						//当前拥有的夺宝码大于需要夺宝码
						$buy_codes = array_slice($codes,0,$i);
						if(!is_array($buy_codes)){	//判断分码正确
							return 'false';
						}
						$left_codes = array_slice($codes,$i,$new_codes_num-$i);
						if(!is_array($left_codes)){	//判断分码正确
							return 'false';
						}
						$left_codes = serialize($left_codes);
						$shengyu_codes = $result_period_new['shengyu_codes'] - $i;
						$canyurenshu = $result_period_new['canyurenshu'] + $i;
						$scale = intval($canyurenshu*100/$result_period_new['zong_codes']);
						
						pdo_update('weliam_indiana_period',array('codes'=>$left_codes,'shengyu_codes'=>$shengyu_codes,'canyurenshu'=>$canyurenshu,'scale'=>$scale),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number));
						
						$new_code = array_merge(unserialize($result_record_new['code']),$buy_codes);	//组合两个数组
						$new_count = $i + $result_record_new['count'];
						$i = 0;
						pdo_update('weliam_indiana_record',array('code' => serialize($new_code),'count' => $new_count),array('uniacid'=>$_W['uniacid'],'period_number' => $period_number,'openid' => $openid));
						
						$left_shengyu_codes = $shengyu_codes;		//重新赋值剩余数量
						if($left_codes == '' && $shengyu_codes > 0){
							self::create_code($period_number);		//再次分配夺宝码
						}
						
					}
				}
				$left_codes_number = $i;	//计算是否还有剩余夺宝码未购买成功
			}
			$consumerecord['num'] = ($codes_number-$left_codes_number)*$result_goodslist_init_money;		//计算实际购买数量
			if($consumerecord['num'] > 0){
				pdo_insert("weliam_indiana_consumerecord",$consumerecord);	
			}
			if($left_codes_number == 0){
				return 'true_A';
			}else{
				return $left_codes_number;
			}
		}

	}

	/***********开奖计算*********/
	public function createtime_winer($periodid = '',$period_number = ''){
		global $_W;
		$src = 'http://f.apiplus.cn/cqssc.json';
		$src .= '?_='.time();
		$json = file_get_contents(urldecode($src));
		$json = json_decode($json);
		$periods = $json->data[0]->expect;

		$s_record = pdo_fetchall("SELECT openid,createtime FROM " . tablename('weliam_indiana_consumerecord') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY `id` DESC LIMIT 0 , 20");//获取商品所有交易记录
		foreach ($s_record as $key => $value) {
			$nickname = pdo_fetchcolumn("select nickname from".tablename('weliam_indiana_member')."where uniacid = '{$_W['uniacid']}' and openid = '{$value['openid']}'");
			$s_record[$key]['nickname'] =  base64_encode($nickname);
		}


		$numa = floatval(0);
		$arecord = array();
		foreach ($s_record as $key => $value) {
			$sourceNumber = rand(0, 999);
    		$microtime = substr(strval($sourceNumber+1000),1,3);
			$arecord[$key]['createtime'] = $value['createtime'];
			$arecord[$key]['nickname'] = $value['nickname'];
			$arecord[$key]['microtime'] = $microtime;
			$numa = $numa + intval(date('His', $value['createtime']).$microtime);
		}

		$numb = str_replace(array(","),"",$json ->data[0]->opencode);
		$period = pdo_fetch("SELECT id,goodsid,zong_codes,code,jiexiao_time FROM " . tablename('weliam_indiana_period') . " WHERE id ='{$periodid}'");//获取商品详情
		if(empty($period['jiexiao_time'])){
			$goods = pdo_fetch("select jiexiao_time from".tablename('weliam_indiana_goodslist')."where id='{$period['goodsid']}'");
			$jiexiao_time = $goods['jiexiao_time'];
		}else{
			$jiexiao_time = $period['jiexiao_time'];
		}
		$endtime = $jiexiao_time*60 + time();				//计算揭晓时间
		if($period['code']){
			$wincode = $period['code'];
		}else{
			$wincode = fmod(($numa + $numb),$period['zong_codes']) + 1000001;
		}
		$comdata = array(
			'uniacid' => $_W['uniacid'], 
			'numa' => $numa, 
			'numb' => $numb, 
			'periods' => $periods, 
			'pid' => $period['id'], 
			'wincode' => intval($wincode), 
			'arecord' => serialize($arecord), 
			'createtime' => TIMESTAMP
		);
		pdo_insert('weliam_indiana_comcode',$comdata);				//写入中奖计算记录
		pdo_update('weliam_indiana_period',array('code'=>$wincode,'endtime'=>$endtime,'status'=>2),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number));	//写入中奖信息
		self::get_winner($period_number, $periodid, $wincode );		//执行中奖人信息

	}
	/******************获取中奖人信息****************/
	public function get_winner($period_number = '', $periodid = '' , $wincode = ''){
		global $_W;
		//更新完毕，计算获奖信息
		$sql_record_winner = "select * from".tablename('weliam_indiana_record')." where uniacid = :uniacid and period_number = :period_number";
		$data_record_winner = array(
				':uniacid' => $_W['uniacid'],
				':period_number' => $period_number
			);
		$records = pdo_fetchall($sql_record_winner,$data_record_winner);		//查询所有本期商品交易记录
		//计算获奖人
		foreach ($records as$k=> $v) {
			$scodes=unserialize($v['code']);//转换商品code
			for ($i=0; $i < count($scodes) ; $i++) { 
				if ($scodes[$i]==$wincode) {
					$lack_period['openid']=$v['openid'];
					$lack_period['recordid']=$v['id'];
					break;
				}
			}
		}
		if(empty($lack_period['openid'])){
			pdo_delete('weliam_indiana_comcode',array('pid'=>$periodid));
			self::createtime_winer($periodid,$period_number);
		}else{
			$pro_m = m('member')->getInfoByOpenid($lack_period['openid']);//获奖用户信息
			$lack_record = pdo_fetch("select count from".tablename('weliam_indiana_record')."where uniacid='{$_W['uniacid']}' and openid='{$lack_period['openid']}' and period_number='{$period_number}'");
			$lack_period['code']=$wincode;
			$lack_period['nickname']=$pro_m['nickname'];
			$lack_period['avatar']=$pro_m['avatar'];
			$lack_period['partakes']=$lack_record['count'];
			//更新中奖信息到这期数据
			pdo_update('weliam_indiana_period', $lack_period, array('id' => $periodid));
		}
	}
	/**************检索购物车数据**************/
	public function getCart($openid='',$uniacid=0){
		global $_W;
		$myCart = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$uniacid}");	
		return $myCart;
	}
	/***********清除购物车数据***********/
	public function deleteCart($id = ''){
		global $_W;
		pdo_delete('weliam_indiana_cart',array('id'=>$id));		//删除已购买商品
	}
	/***********判断合成取出的夺宝码***********/
	public function checkCodes($codes){
		if(is_array($codes)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/*****************日志打印*******************/
	public function WL_log($param,$filedata){
		$url_log = WELIAM_INDIANA."log/".date('Y-m-d',time())."/codes.log";
		$url_dir = WELIAM_INDIANA."log/".date('Y-m-d',time());
		$this->WL_mkdirs($url_dir);			//检测目录是否存在
		file_put_contents($url_log, var_export('/========================================='.date('Y-m-d H:i:s',time()).'============================================/', true).PHP_EOL, FILE_APPEND);
		file_put_contents($url_log, var_export('******记录'.$param.'*****', true).PHP_EOL, FILE_APPEND);
		file_put_contents($url_log, var_export($filedata, true).PHP_EOL, FILE_APPEND);
	} 
	public function WL_mkdirs($dir){
		if (file_exists($dir)) {   
		 	return 'true';
		} else {
			mkdir($dir);
			return 'false';
		}
	}
}