<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$zid= intval($_GPC['zid']);
if(empty($zid)){
    message('抱歉，传递的参数错误！','', 'error');              
}



$list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_zjp_user) . " WHERE zid =:zid   ORDER BY createtime DESC ", array(":zid"=>$zid));




$tableheader = array('openID',  iconv("UTF-8", "GB2312", '昵称' ), iconv("UTF-8", "GB2312", '手机号' ),iconv("UTF-8", "GB2312", '注册时间' ));


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['openid'] . "\t ,";
	 $html .=iconv("UTF-8", "GB2312", $value['nickname'] )  . "\t ,";

    if(!empty($value['tel'])){
        $html .= $value['tel'] . "\t ,";
    }else{
        $html .=  iconv("UTF-8", "GB2312", '未绑定' ) . "\t ,";
    }


	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\n";

}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参加用户数据.xls");

echo $html;
exit();
