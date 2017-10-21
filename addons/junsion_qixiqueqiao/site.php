<?php
/**
 * 七夕鹊桥模块微站定义
 *
 * @author junsion
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Junsion_qixiqueqiaoModuleSite extends WeModuleSite {

	public function doWebManage() {
		global $_W,$_GPC;
		$op = $_GPC['op'];
		if (empty($op)) $op = 'list';
		$rid = $_GPC['rid'];
		if ($op == 'list'){
			$list = pdo_fetchall('select m.*,r.name,p.title from '.tablename($this->modulename.'_rule')." m left join "
						.tablename('rule')." r on r.id=m.rid left join ".tablename($this->modulename."_prize")
						." p on m.rid=p.rid where m.weid='{$_W['weid']}' order by rid desc");
			//参与人数
			foreach ($list as $key => $value) {
				$list[$key]['attend'] = pdo_fetchcolumn('select count(id) from '.tablename($this->modulename."_player")." where rid='{$value['rid']}'");
				$count = pdo_fetchcolumn('select count(id) from '.tablename($this->modulename."_player")." where rid='{$value['rid']}' and status>0 order by successtime {$condition}");
				if ($value['prize_mode'] == 1 && $count > $value['prize_limit']) $count = $value['prize_limit'];//最早成功的几人
				$list[$key]['award'] = $count;
			}
		}else if ($op == 'award'){
			$rule = pdo_fetch('select * from '.tablename($this->modulename.'_rule')." where rid='{$rid}'");
			if ($rule['prize_mode'] == 1) $condition = " limit {$rule['prize_limit']} ";//最早成功的几人
			$list = pdo_fetchall('select *,(select sum(birds_num) from '.tablename($this->modulename."_share").' where pid=p.id) bnum from '.tablename($this->modulename.'_player')." p where p.rid='{$rid}' and p.status>0 order by p.successtime {$condition}");
		}else if ($op == 'player'){
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$rule = pdo_fetch('select * from '.tablename($this->modulename.'_rule')." where rid='{$rid}'");
			$list = pdo_fetchall('select *,(select sum(birds_num) from '.tablename($this->modulename."_share").' where pid=p.id) bnum from '.tablename($this->modulename.'_player')." p where p.rid='{$rid}' order by p.createtime  LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_player') . " where rid='{$rid}'");
			$pager = pagination($total, $pindex, $psize);
		}else if ($op == 'friend'){
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from ".tablename($this->modulename."_share")." where pid={$_GPC['pid']}  LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_share') . " where pid={$_GPC['pid']}");
			$pager = pagination($total, $pindex, $psize);
		}else if ($op == 'take'){
			if (pdo_update($this->modulename."_player",array('status'=>2),array('id'=>$_GPC['pid'])) === false){
				message('发奖失败！');
			}else message('发奖成功！',$this->createWebUrl('manage',array('op'=>'award','rid'=>$rid)));
		}
		include $this->template('manage');
	}
	
	
	public function doMobileIndex(){
		global $_W,$_GPC;
		$rid = $_GPC['rid'];
		$rule = pdo_fetch('select * from '.tablename($this->modulename.'_rule')." where rid='{$rid}'");
		if (empty($rule)) message('该活动不存在！');
		$openid = $this->getOpenid();
		
		if (empty($_GPC['op']) && $rule['describe_limit2']){ //op不为空时 表示从分享页面跳转过来的 
			//助力者痕迹 
			$record = pdo_fetch('select * from '.tablename($this->modulename."_record")." where openid='{$openid}' order by id desc limit 1");
			if (!empty($record)){
				$pid = pdo_fetchcolumn('select openid from '.tablename($this->modulename."_player")." where id='{$record['pid']}'");
				header('location:'.$this->createMobileUrl('share',array('pid'=>$pid,'rid'=>$rid)));
				exit;
			}
		}
		
		$player = pdo_fetch('select * from '.tablename($this->modulename.'_player')." where rid='{$rule['rid']}' and openid='{$openid}'");
		//因说话气泡分两段位移，以rate=0.65为界
		$edge = 0.65;//说话气泡的移动边界(当rate为0.65时，气泡前一段位移的rate相当于1)
		$rate = 0;
		if (!empty($player)){
			$count = pdo_fetchcolumn('select sum(birds_num) from '.tablename($this->modulename."_share")." where pid='{$player['id']}'");
			$rate = $count/$rule['birds_success'];
			if ($count >= $rule['birds_success']) $rate = 1;
		}
		if (empty($count)) $count = 0;
		load()->model('mc');
		$fans = mc_fansinfo($openid, $_W['acid'], $_W['uniacid']);
		$follow = $fans['follow'];
		
		$award = 0;
		if ($player['status'] == 1){//已完成
			//判断是否中奖
			if ($rule['prize_mode'] == 1){//最早成功的几人
				$success = pdo_fetchall('select id from '.tablename($this->modulename.'_player')." where rid='{$rule['rid']}' and status>0 order by successtime limit {$rule['prize_limit']}",array(),'id');
				if (in_array($player['id'],array_keys($success))) $award = 1;
			}else $award = 1;
		}else if($player['status'] == 2) $award = 1;//已领奖
		if ($award == 1){
			$prize = pdo_fetch('select * from '.tablename($this->modulename."_prize")." where rid='{$rid}'");
		}
		if ($rule['rank'] > 0){
			$list = pdo_fetchall('select *,(select sum(birds_num) from '.tablename($this->modulename."_share").' where pid=p.id) bnum from '.tablename($this->modulename.'_player')." p where p.rid='{$rid}' order by bnum desc limit {$rule['rank']}");
		}
		include $this->template('index');
	}
	
	public function doMobileInfo(){
		global $_W,$_GPC;
		$openid = $this->getOpenid();
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where rid='{$_GPC['rid']}'");
		$player = pdo_fetch('select * from '.tablename($this->modulename.'_player')." where rid='{$rule['rid']}' and openid='{$openid}'");
		
		if ($_GPC['op'] == 'user'){
			$nl = trim($_GPC['nl']);
			$nz = trim($_GPC['zn']);
			if (empty($nl)){
				$nl = '牛郎';
			}
			if (empty($nz)){
				$nz = '织女';
			}
			$data = array(
					'myname'=>$nl,
					'hname'=>$nz,
			);
			if (pdo_update($this->modulename.'_player',$data,array('id'=>$player['id'])) === false){
				die("0");
			}
			die("1");
		}else{
			if(!empty($_GPC['mobile']) && !preg_match("/^1[34578]\d{9}$/", $_GPC['mobile'])) message('请填写正确的手机号码');
			if (empty($player)){
				$player = $this->createPlayer($rule);
			}
			$data = array(
					'realname'=>$_GPC['realname'],
					'mobile'=>$_GPC['mobile'],
					'qq'=>$_GPC['qq'],
					'email'=>$_GPC['email'],
					'address'=>$_GPC['address'],
			);
			if (pdo_update($this->modulename.'_player',$data,array('id'=>$player['id'])) === false){
				message('保存个人信息失败！');
			}
			if ($rule['isfans']){//更新信息到系统会员表
				load()->model('mc');
				$mc = array();
				if (!empty($data['realname'])) $mc['realname'] = $data['realname'];
				if (!empty($data['mobile'])) $mc['mobile'] = $data['mobile'];
				if (!empty($data['qq'])) $mc['qq'] = $data['qq'];
				if (!empty($data['email'])) $mc['email'] = $data['email'];
				if (!empty($data['address'])) $mc['address'] = $data['address'];
				mc_update($player['openid'],$mc);
			}
			message('保存个人信息成功！',$this->createMobileUrl('index',array('rid'=>$rule['rid'])));
		}
	}
	
	public function doMobileShare(){
		global $_W,$_GPC;
		$pid = $_GPC['pid'];
		$rid = $_GPC['rid'];
		$rule = pdo_fetch('select * from '.tablename($this->modulename.'_rule')." where rid='{$rid}'");
		if (empty($rule)) message('该活动不存在！');
		load()->model('mc');
		$info = mc_oauth_userinfo();
		$openid = $info['openid'];
		$fans = mc_fansinfo($openid, $_W['acid'], $_W['uniacid']);
		$follow = $fans['follow'];
		$player = pdo_fetch('select * from '.tablename($this->modulename.'_player')." where rid='{$rule['rid']}' and openid='{$openid}'");
		if (!empty($player)){
			header('location:'.$this->createMobileUrl('index',array('rid'=>$rid)));
			exit;
		}
		
		$player = pdo_fetch('select * from '.tablename($this->modulename.'_player')." where rid='{$rule['rid']}' and openid='{$pid}'");
		if (!empty($player)){
			$count = pdo_fetchcolumn('select sum(birds_num) from '.tablename($this->modulename."_share")." where pid='{$player['id']}'");
			$rate = $count/$rule['birds_success'];
			if ($count >= $rule['birds_success']) $rate = 1;
		}
		$share = pdo_fetch('select * from '.tablename($this->modulename.'_share')." where rid='{$rule['rid']}' and openid='{$openid}' and pid='{$player['id']}'");
		if (empty($share) && $rule['describe_limit2']){
				//记录下痕迹   用于助力者关注后 点击活动链接时 还能回到分享页面
				$record = pdo_fetch('select * from '.tablename($this->modulename."_record")." where openid='{$openid}' and pid='{$player['id']}' ");
				if (empty($record)){
					pdo_insert($this->modulename."_record",array('openid'=>$openid,'pid'=>$player['id']));
				}
		}
		//因说话气泡分两段位移，以rate=0.65为界
		$edge = 0.65;//说话气泡的移动边界(当rate为0.65时，气泡前一段位移的rate相当于1)
		$rate = 0;
		if (!empty($player)){
			$count = pdo_fetchcolumn('select sum(birds_num) from '.tablename($this->modulename."_share")." where pid='{$player['id']}'");
			$rate = $count/$rule['birds_success'];
			if ($count >= $rule['birds_success']) $rate = 1;
		}
		include $this->template('share');
	}
	
	public function doMobileBless(){
		global $_W,$_GPC;
		$pid = $_GPC['pid'];
		$rid = $_GPC['rid'];
		$rule = pdo_fetch('select * from '.tablename($this->modulename.'_rule')." where rid='{$rid}'");
		$openid = $this->getOpenid();
		$pid = pdo_fetchcolumn('select * from '.tablename($this->modulename.'_player')." where rid='{$rid}' and openid='{$pid}'");
		$share = pdo_fetch('select * from '.tablename($this->modulename.'_share')." where rid='{$rule['rid']}' and openid='{$openid}' and pid='{$pid}'");
		if (empty($share)){
			load()->model('mc');
			$info = mc_oauth_userinfo();
			$count = $rule['birds_limit'];
			$limit = explode(',',$rule['birds_limit']);
			if (count($limit) == 2){
 				$count = intval(mt_rand($limit[0],$limit[1]));
			}
			$data = array(
				'openid'=>$info['openid'],
				'weid'=>$_W['uniacid'],
				'rid'=>$rid,
				'avatar'=>$info['avatar'],
				'nickname'=>$info['nickname'],
				'pid'=>$pid,
				'birds_num'=>$count,
				'createtime'=>time()
			);
			if (pdo_insert($this->modulename.'_share',$data) === false){
				die(json_encode(array('code'=>'0','msg'=>'插入数据失败，请联系管理员！')));
			}else{
				//删除助力痕迹
				pdo_delete($this->modulename."_record",array('openid'=>$info['openid']));
				//若是足够喜鹊了 则修改参与者的中奖状态
				$all = pdo_fetchcolumn('select sum(birds_num) from '.tablename($this->modulename."_share")." where rid='{$rid}' and pid='{$pid}'");
				if ($all >= $rule['birds_success']){
					pdo_update($this->modulename."_player",array('status'=>1,'successtime'=>time()),array('id'=>$pid));
				}
				die(json_encode(array('code'=>'1','count'=>$count)));
			}
		}
	}
	
	private function createPlayer($rule){
		global $_W,$_GPC;
		$openid = $this->getOpenid();
		load()->model('mc');
		$info = mc_oauth_userinfo();
		$data = array(
				'weid'=>$_W['uniacid'],
				'rid'=>$rule['rid'],
				'openid'=>$openid,
				'avatar'=>$info['avatar'],
				'nickname'=>$info['nickname'],
				'status'=>0,
				'createtime'=>time()
		);
		pdo_insert($this->modulename.'_player',$data);
		return pdo_fetch('select * from '.tablename($this->modulename.'_player')." where rid='{$rule['rid']}' and openid='{$openid}'");
	}
	
	private function getOpenid(){
		global $_W;
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if (!strpos($userAgent, 'MicroMessenger')) {
			message('请使用微信浏览器打开！');
		}
		$openid = $_W['fans']['from_user'];
		if (empty($openid)){
			load()->model('mc');
			$info = mc_oauth_userinfo();
			$openid = $info['openid'];
		}
		return $openid;
	}
}