<?php

   global $_W, $_GPC;  
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $uniacid=$_W["uniacid"];
   $advid=$_GPC['advid'];  
   $cgc_ad_red = new cgc_ad_red();   
   if ($op=='display') { 		
     $nickname = $_GPC['nickname'];
     $list = $cgc_ad_red->getRedByAdv($uniacid, $advid, $nickname);
     include $this->template('web/red');
  	}else if ($op=='deleteAll') {
	 $cgc_ad_red->deleteAll(" and advid=$advid");
	 message('删除成功！',referer(), 'success');
    } else if ($op=='del') {
	 $id=$_GPC['id'];
	 $cgc_ad_red->delete($id);
	 message('删除成功！',referer(), 'success');
	} 
		
		
	 
