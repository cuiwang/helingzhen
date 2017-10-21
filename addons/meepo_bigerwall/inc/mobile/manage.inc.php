<?php
  
		global $_GPC, $_W;
		$weid = $_W['uniacid']; 
		
		$id = intval($_GPC['id']);
		if(empty($id)){
		   message('错误、规则不存在！');
		}
		
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid LIMIT 1", array(':rid'=>$id));
		
		if(isset($_COOKIE["Meepo".$id]) && $_COOKIE["Meepo".$id] ==$ridwall['loginpass'] ){
		}elseif(isset($_COOKIE["Meepo".$id]) && $_COOKIE["Meepo".$id] =='meepoceshi'){
		} else {
			$forward =$_W['siteroot']."app/".$this->createMobileurl('login',array('rid'=>$id));
			$forward = str_replace('./','', $forward);
			header('location: ' .$forward);
			exit;
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		if($_GPC['type']=='delete' && $_GPC['del']=='all'){
		    
			pdo_delete('weixin_wall', array('weid' =>$weid,'rid'=>$id));
			pdo_update('weixin_wall_num',array('num'=>1),array('weid'=>$weid,'rid'=>$id));
			message('清除成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='delete' && $_GPC['del']=='allperson'){
		    
			pdo_delete('weixin_flag', array('weid' =>$weid,'rid'=>$id));
			pdo_delete('weixin_signs', array('weid' =>$weid,'rid'=>$id));
			message('清除成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}

		if($_GPC['type']=='delete' && $_GPC['del']=='yyy'){
		    pdo_update("weixin_wall_reply",array('isopen'=>1),array('weid'=>$weid,'rid'=>$id));
			pdo_delete('weixin_shake_toshake', array('weid' =>$weid,'rid'=>$id));
			pdo_delete('weixin_shake_data', array('weid' =>$weid,'rid'=>$id));
			message('清除摇一摇成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='reset' && $_GPC['del']=='yyy'){
		    pdo_update("weixin_wall_reply",array('isopen'=>1),array('weid'=>$weid,'rid'=>$id));
			pdo_update('weixin_shake_toshake',array('point'=>0), array('weid' =>$weid,'rid'=>$id));
			pdo_delete('weixin_shake_data', array('weid' =>$weid,'rid'=>$id));
			message('重置摇一摇成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		
		if($_GPC['type']=='reset' && $_GPC['del']=='vote'){
		   pdo_update('weixin_vote',array('res'=>0),array('weid'=>$weid,'rid'=>$id));
		   pdo_update('weixin_flag',array('vote'=>0),array('weid'=>$weid,'rid'=>$id));
			
			message('清除成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='reset' && $_GPC['del']=='cj'){
		   pdo_update('weixin_awardlist',array('num'=>0),array('weid'=>$weid,'luckid'=>$id));
		   pdo_delete('weixin_luckuser',array('weid'=>$weid,'rid'=>$id));
			 pdo_update('weixin_flag',array('award_id'=>'meepo'),array('weid'=>$weid,'rid'=>$id));
			message('清除成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		
		$isshow = isset($_GPC['isshow']) ? intval($_GPC['isshow']) : 0;
		$op = $_GPC['op'];
		$keyword = $_GPC['keyword'];
		$mobile = $_GPC['mobile'];
		$changeisshow = $_GPC['changeisshow'];
		if (!empty($changeisshow) && $changeisshow=='修改') {
						$isshowstatus = intval($_GPC['isshowstatus']);
						if($isshowstatus == 2){
						   $isshowstatus = 0;
						}else{
						   $isshowstatus = 1;
						}
						pdo_update('weixin_wall_reply',array('isshow'=>$isshowstatus),array('rid'=>$id,'weid'=>$weid));
            message('修改成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
		}
		if($_GPC['docon']=='delete'){
				    $news_id = intval($_GPC['news_id']);
						if($news_id){
							$sql = 'DELETE FROM'.tablename('weixin_wall')." WHERE rid=:rid AND weid=:weid AND id=:id";
							pdo_query($sql, array(':rid' => $id,':weid'=>$weid,':id'=>$news_id));
							message('删除成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
					  }else{
							message('删除失败！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'error');
						}
		}
		if(!$op){
					if (checksubmit('verify') && !empty($_GPC['select'])) {
						
						foreach ($_GPC['select'] as $row) {
              $row = intval($row);
							pdo_update('weixin_wall',array('isshow'=>1),array('id'=>$row,'rid'=>$id,'weid'=>$_W['uniacid']));
							
						}
						
						message('审核成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
					}
					if (checksubmit('delete') && !empty($_GPC['select'])) {
						$sql = 'DELETE FROM'.tablename('weixin_wall')." WHERE rid=:rid AND weid=:weid AND id  IN  ('".implode("','", $_GPC['select'])."')";
						pdo_query($sql, array(':rid' => $id,':weid'=>$weid));
            message('删除成功！', $this->createMobileUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
					}
					
					$condition = '';
					$condition .= "AND nickname != ''";
					if($isshow == 0) {
						$condition .= 'AND isshow = '.$isshow;
					} else {
						$condition .= 'AND isshow > 0';
					}
					if (!empty($keyword)) {
				        $condition .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
		      }
					if (!empty($mobile)) {
				        $condition .= " AND mobile LIKE '%{$_GPC['mobile']}%'";
		      }
					$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE rid = '{$id}' AND weid = '{$weid}' {$condition} ORDER BY createtime DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
					if (!empty($list)) {
						foreach ($list as &$row) {
							if ($row['type'] == 'link') {
								$row['content'] = iunserializer($row['content']);
								$row['content'] = '<a href="'.$row['content']['link'].'" target="_blank" title="'.$row['content']['description'].'">'.$row['content']['title'].'</a>';
							} elseif ($row['type'] == 'image') {
								$row['content'] = '<img src="'.$row['image'].'" width=50px height=50px/>';
							} else {
								$row['content'] = emotion(emo($row['content']));
							}		
						}
						unset($row);	
					}
					$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_wall') . " WHERE rid = '{$id}' AND weid = '{$weid}' {$condition}");
				  $pager = pagination($total, $pindex, $psize);
		}else{
		      $condition = '';
					if (!empty($keyword)) {
				        $condition .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
		      }
					if (!empty($mobile)) {
				        $condition .= " AND mobile LIKE '%{$_GPC['mobile']}%'";
		      }
          $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE rid = '{$id}' AND weid = '{$weid}' {$condition}");
				  $pager = pagination($total, $pindex, $psize);
					$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE rid = '{$id}' AND weid = '{$weid}' {$condition} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
					if(is_array($list)){
					    foreach($list as &$row){
						  $num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_wall') . " WHERE rid = '{$id}' AND weid = '{$weid}' AND openid = '{$row['openid']}'");
							$row['num'] = $num;
							if($row['cjstatu'] > 0){
							    $cj = pdo_fetch('SELECT tag_name,luck_name FROM ' . tablename('weixin_awardlist') . " WHERE id = '{$row['cjstatu']}' AND weid = '{$weid}'");
								$row['cj'] = "已内定为".$cj['tag_name'];
							}
							 $sign_status = pdo_fetchcolumn('SELECT `status` FROM ' . tablename('weixin_signs') . " WHERE rid = '{$id}' AND weid = '{$weid}' AND openid = '{$row['openid']}'");
							 $row['sign'] = $sign_status;
						}
						unset($row);
					}
		   if (checksubmit('download')){
				if (PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');	
				$tableheader = array('ID', '微信昵称','性别', '真实姓名', '手机号码');
				$html = "\xEF\xBB\xBF";
				foreach ($tableheader as $value) {
					$html .= $value . "\t ,";
				}
				$html .= "\n";
				$messageids = $_GPC['messageid'];
				
				if(is_array($messageids)){
					foreach ($messageids as &$row) {
								$row = intval($row);
					}			
					$sql = "select * from ".tablename('weixin_flag')." where weid=:weid AND rid=:rid AND id  IN  ('".implode("','", $messageids)."') ORDER BY id DESC";
					$listdown = pdo_fetchall($sql,array(':weid'=>$_W['uniacid'],':rid'=>$id));
				}else{
				   $sql = "select * from ".tablename('weixin_flag')." where weid=:weid AND rid=:rid ORDER BY id DESC";
					$listdown = pdo_fetchall($sql,array(':weid'=>$_W['uniacid'],':rid'=>$id));
				}
				foreach ($listdown as $value) {
					if($value['sex'] == '1'){
					   $value['sex']  = '男';
					}elseif($value['sex'] == '2'){
					   $value['sex']  = '女';
					}else{
					   $value['sex'] = '未知';
					}
					$html .= $value['id'] . "\t ,";
					$html .= $value['nickname'] . "\t ,";
					$html .= $value['sex'] . "\t ,";
					$html .= (empty($value['realname']) ? '未录入' : $value['realname']) . "\t ,";
					$html .= (empty($value['mobile']) ? '未录入' : $value['mobile']) . "\t ,";
					$html .=  "\n";
				}
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=本次活动人员全部数据.csv");
				echo $html;
				exit();
		   }
		}
		include $this->template('manage');
		
	