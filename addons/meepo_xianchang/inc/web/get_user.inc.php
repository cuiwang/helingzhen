<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$award_id = intval($_GPC['award_id']);
if($_W['isajax']){
	$where = '';
	$luck_user = pdo_fetchall("SELECT `user_id` FROM ".tablename($this->lottory_user_table)." WHERE rid=:rid AND weid=:weid",array(':rid'=>$rid,':weid'=>$weid));
	$luck_user2 = pdo_fetchall("SELECT `nd_id` FROM ".tablename($this->lottory_award_table)." WHERE rid=:rid AND weid=:weid AND nd_id!=:nd_id AND id!=:id",array(':rid'=>$rid,':weid'=>$weid,':nd_id'=>'',':id'=>$award_id));
	if(!empty($luck_user)){
		foreach($luck_user as $row){
			$real_user[] = $row['user_id'];
		}
	}
	if(!empty($luck_user2) && is_array($luck_user2)){
		foreach($luck_user2 as $row){
			if(!strexists($row['nd_id'], ',')){
				$real_user[] = $row['nd_id'];
			}else{
				$temp = explode(",", $row['nd_id']);
				if(!empty($temp) && is_array($temp)){
					foreach($temp as $row2){
						$real_user[] = $row2;
					}
				}
			}
		}
	}
	
	if(!empty($real_user) && is_array($real_user)){
		$where .= "AND id NOT IN  ('".implode("','", $real_user)."')";
	}
	$user = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND can_lottory=:can_lottory AND status=:status {$where}",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':can_lottory'=>'1',':status'=>'1'));
	$html = '<div class="panel panel-default" style="height:400px;overflow: auto;">
					<div class="panel-heading">
						用户列表
					</div>
					<div class="panel-body table-responsive">
						<table class="table table-hover" style="display:auto;">
							<thead class="navbar-inner">
								<tr >
								   <th style="width:5em;text-align:center" >选？</th>
								   <th style="width:10em;text-align:center">粉丝头像</th>
								</tr>
							</thead>
							<tbody>';
	if(!empty($user)){
		foreach($user as $row){
			$html .= '<tr>
						<td><input type="checkbox" name="select[]" value="'.$row['id'].'"></td>
									
						<td class="row-hover" style="text-align:center">
							<img width="50" src="'.$row['avatar'].'" class="avatar" />
							<div class="mainContent">
								<div class="nickname" style="text-align:center">'.$row['nick_name'].'</div>
							</div>
						</td>
					</tr>';
		}
	}
	$html .= '</tbody>
						</table>
					</div>
				</div>';
	$data = array();
	$data['ret'] = 0;
	$data['data'] = $html;
	die(json_encode($data));
}