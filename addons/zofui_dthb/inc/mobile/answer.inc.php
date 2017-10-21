<?php 
	global $_W;
	global $_GPC;
	
 	if(empty($_W['openid'])){
		die('请在微信中打开');
	} 
 	load() -> model('mc');
	$userinfo = mc_oauth_userinfo($_W['uniacid']);

 	$answertimes = pdo_fetchcolumn("select COUNT(id) from" . tablename('zofui_dthb_answerlog') . "where uniacid ='{$_W['uniacid']}' AND openid = '{$_W['openid']}' AND date_format(from_UNIXTIME(`time`),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')");
	
 	$hadprize = pdo_fetchall("select id from" . tablename('zofui_dthb_hblog') . "where uniacid ='{$_W['uniacid']}' AND openid = '{$_W['openid']}' AND number != 0");
	
	
	$questionnum = intval($this->module['config']['questionnum']);
	$questiones = pdo_fetchall("select * from" . tablename('zofui_dthb_question') . "where uniacid ='{$_W['uniacid']}' ORDER BY RAND() LIMIT {$questionnum}"); 
	
	$num = 1;
	$isright = '';
	if(checksubmit('submit')){
	 	if(intval($answertimes) >= $this->module['config']['answernum']){
			message('你今天答题次数太多了，明天再来吧', $this -> createMobileUrl('answer'), 'error');
		}
		if($hadprize){
			message('你已经领取过红包了', $this -> createMobileUrl('answer'), 'error');
		}
	
		for($i=0;$i<count($questiones);$i++){
			$id = intval($_GPC['quesid'][$i]);
			$quesinfo = pdo_fetch("select * from" . tablename('zofui_dthb_question') . "where uniacid ={$_W['uniacid']} AND id = {$id}");
			
			if($_GPC['answer'][$i] == $quesinfo['right']){
				$isright[] = 1;
			}else{
				$isright[] = 0;
			}
		}
		if(array_sum($isright) == count($questiones)){		
			$answerer = array();
			$answerer['uniacid'] = $_W['uniacid'];
			$answerer['openid'] = $_W['openid'];
			$answerer['nickname'] = $userinfo['nickname'];
			$answerer['time'] = time();
			$answerer['isright'] = 1;
			pdo_insert('zofui_dthb_answerlog', $answerer);
			$newid = pdo_insertid();
			if($newid){
				$fee = $this->module['config']['fee'];//金额
				$arr['openid'] = $_W['openid'];
				$arr['hbname'] = '答题红包';
				$arr['body'] = "答题红包";
				$arr['fee'] = $fee;
				$res = $this->sendhongbaoto($arr);
				if($res['result_code']=='SUCCESS'){
					$intodb = array();
					$intodb['uniacid'] = $_W['uniacid'];
					$intodb['openid'] = $_W['openid'];
					$intodb['nickname'] = $userinfo['nickname'];
					$intodb['time'] = time();
					$intodb['number'] = $fee;
					pdo_insert('zofui_dthb_hblog',$intodb);
					message('恭喜你回答正确，红包已发送，请注意查收', $this -> createMobileUrl('answer'), 'success');
				}else{
					$intodb = array();
					$intodb['uniacid'] = $_W['uniacid'];
					$intodb['openid'] = $_W['openid'];
					$intodb['nickname'] = $userinfo['nickname'];
					$intodb['time'] = time();
					$intodb['number'] = 0;
					pdo_insert('zofui_dthb_hblog',$intodb);
					//var_dump($res);
					message('红包发送失败了,商户参数未设置好', $this -> createMobileUrl('answer'), 'error');					
				}
			}
			
		}else{
			$answerer = array();
			$answerer['uniacid'] = $_W['uniacid'];
			$answerer['openid'] = $_W['openid'];
			$answerer['nickname'] = $userinfo['nickname'];
			$answerer['time'] = time();
			$answerer['isright'] = 2;
			pdo_insert('zofui_dthb_answerlog', $answerer);
			message('回答错误，再试试吧');
		}
	} 

include $this->template('answer');


?>