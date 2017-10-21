<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$data = array();
$openid = $_W['openid'];
if($_W['isajax']){
	$rotate_id = intval($_GPC['rotate_id']);
	$rotate = pdo_fetch("SELECT * FROM ".tablename($this->redpack_rotate_table)." WHERE weid = :weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$rotate_id));
	if($rotate['status']==3){
		$data = error(-1,'game over');
		die(json_encode($data));
	}
	if($rotate_id){
		$user =  pdo_fetch("SELECT `nick_name`,`avatar` FROM ".tablename($this->user_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid' =>$weid,':rid'=>$rid,':openid'=>$openid));
		$true_gailv = intval($rotate['gailv']);
		$true_no_gailv = 100 - $true_gailv;
		$arr = array($true_no_gailv,$true_gailv);
		
		$redpack_config = pdo_fetch('SELECT `all_nums` FROM '.tablename($this->redpack_config_table).' WHERE weid=:weid AND rid=:rid',array(':weid'=>$weid,':rid'=>$rid));
		if($redpack_config['all_nums'] > 0 ){
			$had_get_nums = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($this->redpack_user_table)." WHERE openid=:openid AND weid=:weid AND rid=:rid AND money!=:money",array(':openid'=>$openid,':weid'=>$weid,':rid'=>$rid,':money'=>'0.0'));
			if($had_get_nums >= $redpack_config['all_nums']){
				$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
				die(json_encode($data));
			}
		}
		if($rotate['get_num']>0){
			$rotate_had_nums = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($this->redpack_user_table)." WHERE openid=:openid AND weid=:weid AND rid=:rid AND money!=:money AND rotate_id=:rotate_id",array(':openid'=>$openid,':weid'=>$weid,':rid'=>$rid,':money'=>'0.0',':rotate_id'=>$rotate_id));
			if($rotate_had_nums >= $rotate['get_num']){
				$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
				die(json_encode($data));
			}
		}
		if($rotate['redpack_num']>0){
			$all_rotate_had_nums = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($this->redpack_user_table)." WHERE  weid=:weid AND rid=:rid AND money!=:money AND rotate_id=:rotate_id",array(':weid'=>$weid,':rid'=>$rid,':money'=>'0.0',':rotate_id'=>$rotate_id));
			if($all_rotate_had_nums >= $rotate['redpack_num']){
				$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
				die(json_encode($data));
			}
		}
		$rand = $this->get_rand($arr);
		if($rand==0){
			$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
			die(json_encode($data));
		}else{
		  if($rotate['type']==1){
				$money = $rotate['money'];
		  }else{
				$all_rotate_had_money = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->redpack_user_table)." WHERE  weid=:weid AND rid=:rid AND  rotate_id=:rotate_id",array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
				if($all_rotate_had_money >= $rotate['all_money']){
					$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
					die(json_encode($data));
				}
				$fen_money = rand($rotate['min']*100,$rotate['max']*100);
				$can_get  = ($rotate['all_money']-$all_rotate_had_money)*100;
				if($fen_money>=$can_get && $can_get>0){
					$money = $can_get/100;
				}else{
					$money = $fen_money/100;
				}
				if($money<=0 || $money<$rotate['min']){
					$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
					die(json_encode($data));
				}
		  }
		  if(empty($_GPC['Meepo'.$weid])){
				$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
				die(json_encode($data));
		  }else{
			  $result = pdo_insert($this->redpack_user_table,array('rid'=>$rid,'weid'=>$weid,'openid'=>$openid,'rotate_id'=>$rotate_id,'nick_name'=>$user['nick_name'],'avatar'=>$user['avatar'],'money'=>$money,'createtime'=>time()));
			  if(!empty($result)){
				  $result_id = pdo_insertid();
				  $procResult = $this->_sendpack($_GPC['Meepo'.$weid],$money,$rid);
				  if($procResult['errno']==0){
						$data = array('errno'=>0,'data'=>array('money'=>$money));
						die(json_encode($data));
				  }else{
						pdo_delete($this->redpack_user_table,array('rid'=>$rid,'id'=>$result_id));
						if(strexists($procResult['error'],'帐号余额不足')){
							$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
							die(json_encode($data));
						}else{
							$data = error(-2,$procResult['error']);
							die(json_encode($data));
						}
				  }
			  }else{
					$data = array('errno'=>1,'data'=>array('pic'=>'../addons/meepo_xianchang/template/mobile/app/images/redpack/app/money_redenvelop_top.png','show_msg'=>'空空如也'));
					die(json_encode($data));
			  }
		  }
		}
	}else{
		$data = error(-3,'error');
		die(json_encode($data));
	}
	
}
