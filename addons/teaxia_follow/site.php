<?php
/**
 * 关注积分赠送模块微站定义
 *
 * @author 茶树虾
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Teaxia_followModuleSite extends WeModuleSite {

	public function doWebSysteam() {
		global $_GPC,$_W;
		$act = htmlspecialchars($_GPC['act']);
		$cts = htmlspecialchars($_GPC['credits']);
		$num = intval($_GPC['num']);
		$focus = htmlspecialchars($_GPC['focus']);
		//这个操作被定义用来呈现 管理中心导航菜单
		checklogin();
		//积分查询
		$list = pdo_fetch("SELECT creditnames FROM ".tablename('uni_settings') . " WHERE `uniacid` = ".$_W['uniacid']."");
		if(!empty($list['creditnames'])) {
			$list = iunserializer($list['creditnames']);
			if(is_array($list)) {
				foreach($list as $k => $v) {
					$credits[$k] = $v;
				}
			}
		}
		
		//判断是否是更新
		$do = pdo_fetch("SELECT * FROM ".tablename('teaxia_follow') . " WHERE `uniacid` = ".$_W['uniacid']."");
		if($do){
			$action = 'updata';
		}else{
			$action = 'save';
		}
		//新增设置
		if($act == 'save'){
			$data=array('credits'=>$cts,'num'=>$num,'focus'=>$focus,'uniacid'=>$_W['uniacid']);
			$res = pdo_insert('teaxia_follow',$data);
			message('增加成功！',$this->createWebUrl('Systeam'),'success');
		}
		//更新设置
		if($act == 'updata'){
			$data=array('credits'=>$cts,'num'=>$num,'focus'=>$focus,'uniacid'=>$_W['uniacid']);
			$res = pdo_update('teaxia_follow',$data,array('uniacid' => $_W['uniacid']));
			message('更新成功-&#25240;&#46;&#32764;&#46;&#22825;&#46;&#20351;&#46;&#36164;&#46;&#28304;&#46;&#31038;&#46;&#21306;&#46;&#25552;&#46;&#20379;！',$this->createWebUrl('Systeam'),'success');
		}
		include $this->template('systeam');
	}

}