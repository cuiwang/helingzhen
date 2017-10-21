<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
$status= $_GPC['status'];
$award= $_GPC['award'];
$fans = $_GPC['fans'];
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}
$reply = pdo_fetch("SELECT isrealname,ismobile,isqq,isemail,isaddress,isgender,istelephone,isidcard,iscompany,isoccupation,isposition,isfansname FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
$isfansname = explode(',',$reply['isfansname']);
if($fans=='branch'){
    if (!empty($_GPC['districtid'])) {     
            $where.=' and districtid='.$_GPC['districtid'].'';
    }elseif(!empty($_GPC['pcate'])){
		    $districts = pdo_fetchall("SELECT id FROM " . tablename('stonefish_branch_business') . "  WHERE districtid=:districtid and  uniacid=:uniacid ORDER BY id DESC", array('districtid' =>$_GPC['pcate'],'uniacid' =>$_W['uniacid']), 'districtid');
			$districtid = '';
            foreach ($districts as $districtss) {
                $districtid .= $districtss['id'].',';
            }
			$districtid = substr($districtid,0,strlen($districtid)-1);
			$where.=' and districtid in('.$districtid.')';
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename('stonefish_branch_doings')."  WHERE rid = :rid and uniacid=:uniacid and module=:module ".$where." ORDER BY id DESC" , array(':rid' => $rid,':uniacid'=>$_W['uniacid'],':module'=>'stonefish_redenvelope'));
    //查询区域以及商家 以及中奖情况
	foreach ($list as &$row) {
	    $row['shangjia'] = pdo_fetchcolumn("SELECT title FROM " . tablename('stonefish_branch_business') . "  WHERE id = :id", array(':id' => $row['districtid']));
		$districtid = pdo_fetchcolumn("SELECT districtid FROM " . tablename('stonefish_branch_business') . "  WHERE id = :id", array(':id' => $row['districtid']));
		$row['quyu'] = pdo_fetchcolumn("SELECT title FROM " . tablename('stonefish_branch_district') . "  WHERE id = :id", array(':id' => $districtid));
		$fansID = pdo_fetchcolumn("SELECT fansID FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE mobile = :mobile and rid=:rid and uniacid=:uniacid", array(':mobile' => $row['mobile'],':rid' => $rid,':uniacid' => $_W['uniacid']));
		$row['awardinfo']='';
		$awards = pdo_fetchall("SELECT name FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and fansID=:fansID", array(':rid' => $rid,':fansID' => $fansID));
		if(!empty($awards)){
			foreach ($awards as &$awardid) {
				$row['awardinfo'] = $row['awardinfo'].$awardid['name'].';';
			}
		}
    }
    $tableheader = array('ID', '手机号', '抽奖次数', '使用次数', '商家区域', '商家', '商家ID', '中奖情况', '添加时间');
    $html = "\xEF\xBB\xBF";
    foreach ($tableheader as $value) {
	    $html .= $value . "\t ,";
    }
    $html .= "\n";
    foreach ($list as $value) {
	    $html .= $value['id'] . "\t ,";
	    $html .= $value['mobile'] . "\t ,";	
	    $html .= $value['awardcount'] . "\t ,";	
	    $html .= $value['usecount'] . "\t ,";	
	    $html .= $value['quyu'] . "\t ,";	
	    $html .= $value['shangjia'] . "\t ,";	
		$html .= $value['districtid'] . "\t ,";	
	    $html .= $value['awardinfo'] . "\t ,";	
	    $html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";		
    }
}elseif($fans==1){
    if(!empty($status)){        
	    if($status == 1){
		    $statustitle='已中奖用户';
			$where.=' and zhongjiang>=1';
	    }elseif($status == 2){
		    $statustitle='已兑换用户';
			$where.=' and zhongjiang=2';
		}elseif($status == 3){
		    $statustitle='未兑换用户';
			$where.=' and zhongjiang=1';
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
	$list = pdo_fetchall("SELECT * FROM ".tablename('stonefish_redenvelope_fans')."  WHERE rid = :rid and uniacid=:uniacid ".$where." ORDER BY id DESC" , array(':rid' => $rid,':uniacid'=>$_W['uniacid']));
	//中奖情况
	foreach ($list as &$lists) {
		$lists['awardinfo']='';
		$awards = pdo_fetchall("SELECT name FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and fansID=:fansID", array(':rid' => $rid,':fansID' => $lists['fansID']));
		if(!empty($awards)){
			foreach ($awards as &$awardid) {
				$lists['awardinfo'] = $lists['awardinfo'].$awardid['name'].';';
			}
		}
		$lists['status']='';
		if($lists['zhongjiang']==0){
		    $lists['status']='未中奖';
	    }elseif($lists['zhongjiang']==1){
		    $lists['status']='未兑奖';
		}elseif($lists['zhongjiang']==2){
		    $lists['status']='已兑奖';
		}
		if($lists['xuni']==0){
		    $lists['status'].='/真实';
		}else{
		    $lists['status'].='/虚拟';
		}
	}
	//中奖情况
	$tableheader = array('ID', '粉丝ID', '奖项', '状态');
	$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
	$k = 0;
	foreach ($ziduan as $ziduans) {
		if($reply['is'.$ziduans]){
			$tableheader[]=$isfansname[$k];
		}
		$k++;
	}
	$tableheader[]='中奖者微信码';
	$tableheader[]='分享量';
	$tableheader[]='参与时间';
    $html = "\xEF\xBB\xBF";
    foreach ($tableheader as $value) {
	    $html .= $value . "\t ,";
    }
    $html .= "\n";
    foreach ($list as $value) {
	    $html .= $value['id'] . "\t ,";
	    $html .= $value['fansID'] . "\t ,";	
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
	    $html .= $value['from_user'] . "\t ,";	
		$html .= $value['sharenum'] . "\t ,";	
	    $html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";
    }
	
}else{
    if(isset($status)){
        $params = 'and a.status='.$status.'';
	    if($status == 0){
		    $statustitle='被取消';
	    }elseif($status == 1){
		    $statustitle='未兑奖';
	    }else{
		    $statustitle='已兑奖';
	    }
    }else{
        $statustitle='全部';
    }
    if(!empty($award)){
        $params = $params." and a.name='".$award."'";
    }
    $list = pdo_fetchall("SELECT a.*,b.realname,b.mobile,b.qq,b.email,b.address,b.gender,b.telephone,b.idcard,b.company,b.occupation,b.position FROM ".tablename('stonefish_redenvelope_award')." as a  left join ".tablename('stonefish_redenvelope_fans')." 
						as b on a.rid=b.rid and  a.from_user=b.from_user  WHERE a.rid = :rid and a.uniacid=:uniacid ".$params." ORDER BY a.id DESC
						" , array(':rid' => $rid,':uniacid'=>$_W['uniacid']));
    foreach ($list as &$row) {
	    if($row['status'] == 0){
		    $row['status']='被取消';
	    }elseif($row['status'] == 1){
		    $row['status']='未兑奖';
	    }else{
		    $row['status']='已兑奖';
	    }
		if($row['xuni']==0){
		    $row['status'].='/真实';
		}else{
		    $row['status'].='/虚拟';
		}
    }
    $tableheader = array('ID', 'sn码', '奖项', '奖品名称', '状态');
    $ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
	$k=0;
	foreach ($ziduan as $ziduans) {
		if($reply['is'.$ziduans]){
			$tableheader[]=$isfansname[$k];
		}
		$k++;
	}
	$tableheader[]='中奖者微信码';
	$tableheader[]='中奖时间';
	$tableheader[]='使用时间';
	$html = "\xEF\xBB\xBF";
    foreach ($tableheader as $value) {
	    $html .= $value . "\t ,";
    }
    $html .= "\n";
    foreach ($list as $value) {
	    $html .= $value['id'] . "\t ,";
	    $html .= $value['award_sn'] . "\t ,";	
	    $html .= $value['name'] . "\t ,";	
	    $html .= $value['description'] . "\t ,";	
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
	    $html .= $value['from_user'] . "\t ,";	
	    $html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";	
	    $html .= ($value['consumetime'] == 0 ? '未使用' : date('Y-m-d H:i',$value['consumetime'])) . "\n";		
    }

}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=".$statustitle.$award."数据_".$rid.".csv");

echo $html;
exit();

