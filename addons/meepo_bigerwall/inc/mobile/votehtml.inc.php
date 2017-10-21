<?php
include MODULE_ROOT.'/inc/mobile/__init.php';
if($ridwall['tpshow']!='1'){
	message('本次活动未开启投票活动！');
}

if(empty($member)){
	message('错误你的信息不存在或是已经被删除！');
}

	   $class=array('red','blue','green');
		 if(TIMESTAMP<$ridwall['starttime']){
			$msg='投票在'.date('Y-m-d H:i',$ridwall['starttime']).'开始,到时再来哦';
			message($msg);
		 }
		if(TIMESTAMP>$ridwall['endtime']){
			$msg='投票在'.date('Y-m-d H:i',$ridwall['endtime']).'结束啦！';
			message($msg);	
		}
    $votemans =pdo_fetchcolumn("SELECT SUM(res)  FROM ".tablename('weixin_vote')." WHERE  weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
		if(!empty($ridwall['votemam'])){
			if($votemans >= $ridwall['votemam']){
				$msg='投票结束啦，本次投票只准许'.$ridwall['votemam']."个人参与";
				message($msg);	
			}
		}
				$sum =pdo_fetchcolumn("SELECT SUM(res)  FROM ".tablename('weixin_vote')." WHERE  weid = :weid AND rid = :rid",array(':weid'=>$weid,':rid'=>$rid));
				if($sum == 0){$sum = 1;}
				$sql='SELECT * FROM  '.tablename('weixin_vote').' WHERE id!=:id  AND weid=:weid AND rid=:rid ORDER BY res DESC';
				$para2 = array(':id'=>0,':weid'=>$weid,':rid'=>$rid);
				$allvote = pdo_fetchall($sql,$para2);
				if(empty($allvote)){
				   message('本次活动还未添加投票项目！');
				}
				if($member['vote']==0){
					if(is_array($allvote) && !empty($allvote)){
						foreach($allvote as $row){
						 $persent[$row['id']]=sprintf("%.2f", ($row['res']/$sum)*100 );
						}
					}
				}else{
				  if(is_array($allvote) && !empty($allvote)){
				       foreach($allvote as $row){
					    $persent[$row['id']]=sprintf("%.2f", ($row['res']/$sum)*100 );
					   }
				  }
				}
     include $this->template('votehtml');