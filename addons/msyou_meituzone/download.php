<?php
	
/**
 * 合体红包
 *
 * @author ewei qq:22185157
 * @url 
 */
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}

  $params = array(':rid' => $rid);

  //序号
  $bh="(select ifnull(count(1),0)+1 from " . tablename('msyou_meituzone_lists') . " WHERE a.createtime>createtime and rid=".$_GPC['rid'].") bh ";
  //排名
  $pm="(select ifnull(count(1),0)+1 from " . tablename('msyou_meituzone_lists') . " WHERE zancount*".$reply['zanx']."+sharecount*".$reply['sharex']."+viewcount*".$reply['viewx'].">a.zancount*".$reply['zanx']."+a.sharecount*".$reply['sharex']."+a.viewcount*".$reply['viewx']." and rid=".$_GPC['rid'].") pm ";
  //得分
  $df="(zancount*".$reply['zanx']."+sharecount*".$reply['sharex']."+viewcount*".$reply['viewx'].") sumcount ";

  $list = pdo_fetchall("select b.nickname, b.mobile, aa.* from (SELECT ".$bh.",".$pm.",".$df.",a.* FROM " . tablename('msyou_meituzone_lists') . " a WHERE rid = :rid) aa left join " . tablename('mc_members') . " b on b.uid=aa.fanid ORDER BY aa.pm desc " , $params);
  //$list = pdo_fetchall("SELECT * FROM " . tablename('msyou_facedoubi_lists') . " WHERE rid = :rid ORDER BY createtime desc ", $params);
  foreach ($list as &$row) {
	if($row['jiang'] == 0){
		$row['jiang']='';
	}else{
		$row['jiang']='已发奖';
	}
}
$tableheader = array('RID','uniacid','ID', 'fanid','昵称','手机号', '首图','得分', '点赞', '分享数', '浏览数', '参与时间', '奖品');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['RID'] . "\t ,";
	$html .= $value['uniacid'] . "\t ,";
	$html .= $value['id'] . "\t ,";
	 $html .= $value['fanid'] . "\t ,";	
	$html .= $value['nickname'] . "\t ,";	
	$html .= $value['phonenum'] . "\t ,";	
        $html .= $value['imgurl'] . "\t ,";	
        $html .= $value['sumcount'] . "\t ,";	
        $html .= $value['zancount'] . "\t ,";	
        $html .= $value['sharecount'] . "\t ,";	
        $html .= $value['viewcount'] . "\t ,";	
        $html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";	
        $html .= $value['jiang'] . "\n";	
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部用户数据.csv");

echo $html;
exit();
