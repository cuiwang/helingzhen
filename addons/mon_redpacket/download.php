<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$pid= intval($_GPC['pid']);
if(empty($pid)){
    message('抱歉，传递的参数错误！','', 'error');              
}

  $params = array(':pid' => $pid);
  $list = pdo_fetchall("SELECT * FROM " . tablename('redpacket_user') . " WHERE pid = :pid " . $where . " ORDER BY income DESC ", $params);
 
        $awards = pdo_fetchall("select * from ".tablename('redpacket_award')." where pid=:pid ",array(":pid"=>$pid));
        foreach($list as &$row){
            $awardnames = array();    
            foreach($awards as $award){
                if($row['income']>=$award['point']){
                      $awardnames[] = $award['name'];
                }
            } 
           $row['awardnames'] = $awardnames;
           
            if(!empty($row['awardid'])){
                $row['awardname'] = pdo_fetchcolumn("select name from ".tablename('redpacket_award')." where id=:id limit 1 ",array(":id"=>$row['awardid']));
            }
        }
        unset($row);
        					 
foreach ($list as &$row) {
	if($row['status'] == 0){
		$row['status']='未领取';
	}elseif($row['status'] == 1){
		$row['status']='已中奖';
	}else{
		$row['status']='已领奖';
	}
}
$tableheader = array('ID',  '昵称', '手机号','翻牌数据','金额' ,'状态', '中奖的奖品','领取的奖品' , '领奖时间', '参与时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['id'] . "\t ,";
	 $html .= $value['nickname'] . "\t ,";	
	$html .= $value['tel'] . "\t ,";	
	$html .= $value['helpcount'] . "\t ,";	
	$html .= $value['income'] . "\t ,";
        $html .= $value['status'] . "\t ,";	
        $html .=implode("/",$value['awardnames']) . "\t ,";	
        $html .=$value['awardname'] . "\t ,";	
         
        
	$html .= ($value['awardtime'] == 0 ? '' : date('Y-m-d H:i',$value['awardtime'])) . "\t ,";
        
	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";	
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部数据.csv");

echo $html;
exit();
