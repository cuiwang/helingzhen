<?php
	
/**
 * 对联猜猜
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
  $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_couplet_fans') . " WHERE rid = :rid and status>=1 and sim=0 " . $where . " ORDER BY createtime asc ", $params);
  foreach ($list as &$row) {
	if($row['status'] == 0){
		$row['status']='';
	}elseif($row['status'] == 1){
		$row['status']='已中奖';
	}elseif($row['status'] == 2){
		$row['status']='已兑奖';
	}
}
$tableheader = array('ID', '微信码','用户','真实姓名','手机', '对联' ,'下联字数','被帮助次数','参与时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['id'] . "\t ,";
	 $html .= $value['openid'] . "\t ,";	
	$html .= $value['nickname'] . "\t ,";
        $html .= $value['realname'] . "\t ,";
        $html .= $value['mobile'] . "\t ,";
        $text_str = "";
        $uptext = unserialize($value['uptext']);
        foreach($uptext as $ut){
            $text_str.=$ut['char'];
        }
        $text_str.="  " ;
        $downtext = unserialize($value['downtext']);
        foreach($downtext as $dt){
            $text_str.=$dt['char'];
        }
        $text_str.=" (";
         foreach($downtext as $dt){
            if(!empty($dt['bingo'])){
               $text_str.= $dt['char'];    
            }
            else{
                $text_str.= "X";
            }
        }
        $text_str.=")";
        $html .= $text_str ."\t ,";
        $html .= $value['num'] . "\t ,";	
        $html .= $value['helps'] . "\t ,";	
        $html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";	
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部用户数据.csv");

echo $html;
exit();
