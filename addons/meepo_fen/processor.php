<?php
/**
 * 关注有礼【高级版】模块处理程序
 *
 * @author meepo
 *  http://www.5kym.com 悟空源码网
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_fenModuleProcessor extends WeModuleProcessor {
	public function M($name){
		static $model = array();
		if(empty($model[$name])) {
			include IA_ROOT.'/addons/meepo_fen/model/'.$name.'.php';
			$model[$name] = new $name();
		}
		return $model[$name];
	}
	public function respond() {
		global $_W;
		load()->model('mc');
		load()->model('activity');

		checkauth();

		$this->new = $this->M('setting')->getSetting('new');
		$this->old = $this->M('setting')->getSetting('old');

		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		//print_r($this->message);
		$openid = $this->message['from'];
		if(empty($_W['openid'])){
			$_W['openid'] = $openid;
		}
		$member = $this->M('member')->getInfo($_W['openid']);
		$uid = mc_openid2uid($_W['openid']);

		if(empty($member)){
			//新会员
			$member = $this->M('member')->update();
			$welcome = $this->new['welcome'];
			$welcome = str_replace('[会员UID]',$member['uid'],$welcome);
			$welcome = str_replace('[昵称]',$member['nickname'],$welcome);
			$welcome = str_replace('[赠送积分]',$this->new['credit1'],$welcome);
			$welcome = str_replace('[赠送余额]',$this->new['credit2'],$welcome);
			$welcome = str_replace('[赠送红包]',$this->new['redpage'],$welcome);
			$coupon = $this->new['coupon'];


			$coupontitle  = "";
			if(!empty($coupon)){
				foreach ($coupon as $coup){
					$c = $this->M('activity_coupon')->getInfo($coup);
					if($c['type'] == 1){
						activity_coupon_grant($uid, $coup, $module = 'meepo_fen', $remark = '关注赠送【'.$c['title'].'】');
					}else{
						activity_token_grant($uid, $coup, $module = 'meepo_fen', $remark = '关注赠送【'.$c['title'].'】');
					}
					$coupontitle .= "【".$c['title']."】";
					$welcome = str_replace('[卡券名]',$coupontitle,$welcome);
				}
			}else{
				$welcome = str_replace('[卡券名]',$coupontitle,$welcome);
			}
			$exchange = $this->new['coupon'];
			$exchangetitle  = "";
			if(!empty($exchange)){
				foreach ($exchange as $coup){
					$c = $this->M('activity_exchange')->getInfo($coup);
					activity_goods_grant($uid, $coup);
					$exchangetitle .= "【".$c['title']."】";
					$welcome = str_replace('[礼品名]',$exchangetitle,$welcome);
				}
			}else{
				$welcome = str_replace('[礼品名]',$exchangetitle,$welcome);
			}
			return $this->respText($welcome);
			$credit1 = floatval($this->new['credit1']);
			//卡券
			if($credit1>0){
				mc_credit_update($uid,'credit1',$credit1,array($uid,'关注赠送积分'.$credit1,0,0));
			}
			$credit2 = floatval($this->new['credit2']);
			if($credit2>0){
				mc_credit_update($uid,'credit2',$credit2,array($uid,'关注赠送余额'.$credit2,0,0));
			}
			return $this->respText($welcome);
		}else{
			//老会员
			$welcome = $this->old['welcome'];
			$welcome = str_replace('[会员UID]',$member['uid'],$welcome);
			$welcome = str_replace('[昵称]',$member['nickname'],$welcome);
			return $this->respText($welcome);
		}

		return $this->respText($openid.$content);
	}
}
