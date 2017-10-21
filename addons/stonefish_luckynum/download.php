<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
$status= $_GPC['status'];
$fans = $_GPC['fans'];
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}
$reply = pdo_fetch("select isrealname,ismobile,isqq,isemail,isaddress,isgender,istelephone,isidcard,iscompany,isoccupation,isposition,isfansname from " . tablename('stonefish_luckynum') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$isfansname = explode(',',$reply['isfansname']);
    if(!empty($status)){        
	    if($status == 1){
		    $statustitle='已中奖用户';
			$where.=' and zhongjiang>=1';
	    }elseif($status == 2){
		    $statustitle='已提交用户';
			$where.=' and zhongjiang=2';
		}elseif($status == 6){
		    $statustitle='已兑换用户';
			$where.=' and zhongjiang=3';
		}elseif($status == 3){
		    $statustitle='未兑换用户';
			$where.=' and (zhongjiang=1 or zhongjiang=2)';
		}elseif($status == 4){
		    $statustitle='未中奖用户';
			$where.=' and zhongjiang=0';
	    }elseif($status == 5){
		    $statustitle='虚拟奖用户';
			$where.=' and xuni=1';
		}
    }else{
        $statustitle='全部用户';
    }
	$list = pdo_fetchall("select * from ".tablename('stonefish_luckynum_fans')."  where rid = :rid and uniacid=:uniacid ".$where." order by id desc" , array(':rid' => $rid,':uniacid'=>$_W['uniacid']));
	//中奖情况
	foreach ($list as &$lists) {
		$lists['awardinfo']='';
		$awards = pdo_fetch("select title from " . tablename('stonefish_luckynum_award') . " where rid = :rid and id=:award_id", array(':rid' => $rid,':award_id' => $lists['award_id']));
		if(!empty($awards)){
			$lists['awardinfo'] = $lists['awardinfo'].$awards['title'].';';
		}
		$lists['status']='';
		if($lists['zhongjiang']==0){
		    $lists['status']='未中奖';
	    }elseif($lists['zhongjiang']==1){
		    $lists['status']='未兑奖';
		}elseif($lists['zhongjiang']==2){
		    $lists['status']='已提交';
		}elseif($lists['zhongjiang']==3){
		    $lists['status']='提兑奖';
		}
		if($lists['xuni']==0){
		    $lists['status'].='/真实';
		}else{
		    $lists['status'].='/虚拟';
		}
	}
	//中奖情况
	$tableheader = array('ID', '粉丝ID', '幸运数字', '奖项', '状态');
	$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
	$k = 0;
	foreach ($ziduan as $ziduans) {
		if($reply['is'.$ziduans]){
			$tableheader[]=$isfansname[$k];
		}
		$k++;
	}
	$tableheader[]='参与时间';
	$tableheader[]='兑奖时间';
    $html = "\xEF\xBB\xBF";
    foreach ($tableheader as $value) {
	    $html .= $value . "\t ,";
    }
    $html .= "\n";
    foreach ($list as $value) {
	    $html .= $value['id'] . "\t ,";
	    $html .= $value['from_user'] . "\t ,";	
		$html .= $value['number'] . "\t ,";	
	    $html .= $value['awardinfo'] . "\t ,";
	    $html .= $value['status'] . "\t ,";	
	    foreach ($ziduan as $ziduans) {
			if($reply['is'.$ziduans]){
				if($ziduans=='gender'){
					if($value[$ziduans]==0){
						$html .= "保密\t ,";	
					}
					if($value[$ziduans]==1){
						$html .= "男\t ,";	
					}
					if($value[$ziduans]==2){
						$html .= "女\t ,";	
					}
				}else{
					$html .= $value[$ziduans] . "\t ,";	
				}
			}
		}	
	    $html .= date('Y-m-d H:i:s', $value['dateline']) . "\t ,";
		if($value['consumetime']){
			$html .= date('Y-m-d H:i:s', $value['consumetime']) . "\n";
		}else{
			$html .= "未兑奖\n";
		}
		
    }
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=".$statustitle.$award."数据_".$rid.".csv");

echo $html;
exit();

