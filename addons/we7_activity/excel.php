<?php require(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('vote'); 


 header("Content-type:application/vnd.ms-excel"); 
 header("Content-Disposition:attachment;filename=test_data.xls"); 
 $tx='用户表';
 $tx = iconv("UTF-8", "GB2312", $tx);

 echo  $tx."\n";   

 //输出内容如下：   

 echo  iconv("UTF-8", "GB2312", "微信id"."\t");
 echo    iconv("UTF-8", "GB2312", "姓名"."\t");
 echo    iconv("UTF-8", "GB2312", "电话"."\t");
 echo     iconv("UTF-8", "GB2312", "性别"."\t"); 
 echo     iconv("UTF-8", "GB2312", "年龄"."\t"); 
 echo     iconv("UTF-8", "GB2312", "体态"."\t"); 
 echo     iconv("UTF-8", "GB2312", "健康状态"."\t"); 
 echo     iconv("UTF-8", "GB2312", "联系地址"."\t"); 
 echo  "\n";   
if(empty($keywords))
		{
		$sql = "SELECT * FROM `v_user` ";
		}
		else
		{
		$sql = "SELECT * FROM `v_user` where tname = '$keywords' or  utel = '$keywords'";
		}
		$dopage->GetPage($sql);
		while($row = $dosql->GetArray())
		{		
		if($row['sex'] == 1)
		     $sexs = "男";
		  elseif($row['sex'] == 0)
		     $sexs =  "女";
		  else
		     $sexs =  "未填写"; 	
 echo  iconv("UTF-8", "GB2312", $row['wx_id']."\t");
 echo    iconv("UTF-8", "GB2312", $row['tname']."\t");
 echo     iconv("UTF-8", "GB2312", $row['utel']."\t"); 
 echo  iconv("UTF-8", "GB2312", $sexs."\t");
 echo    iconv("UTF-8", "GB2312", $row['age']."\t");
 echo     iconv("UTF-8", "GB2312", $row['titai']."\t"); 
 echo     iconv("UTF-8", "GB2312", $row['healths']."\t"); 
 echo     iconv("UTF-8", "GB2312", $row['uadd']."\t"); 
 echo  "\n"; 
 
}

 ?> 