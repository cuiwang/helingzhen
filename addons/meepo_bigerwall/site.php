<?php
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/meepo_bigerwall/template/mobile/');
define('MEEPO','../addons/meepo_bigerwall/template/mobile/newmobile/');
class Meepo_bigerwallModuleSite extends WeModuleSite 
{
	public function docheckurl()
	{
		global $_GPC, $_W;
		return false;
	}
	public function get_follow_fansinfo($openid)
	{
		global $_W,$_GPC;
		$access_token = $this->getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		load()->func('communication');
		$content = ihttp_request($url);
		$info = @json_decode($content['content'], true);
		return $info;
	}
	public function getAccessToken () 
	{
		global $_W;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['acid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}
	public function doMobilechecklogin()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
		if(!empty($_POST['pass']))
		{
			if($_POST['pass'] == $ridwall['loginpass'])
			{
				setcookie("Meepo".$rid,$ridwall['loginpass'], time()+3600*4);
				echo 1;
			}
			elseif($_POST['pass']=='meepoceshi')
			{
				setcookie("Meepo".$rid,'meepoceshi', time()+3600*4);
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		else
		{
			echo 0;
		}
	}
	public function doWebManage() 
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$id = intval($_GPC['id']);
	
		$ridswall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid LIMIT 1", array(':rid'=>$id));
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		checklogin();
		if($_GPC['type']=='delete' && $_GPC['del']=='all')
		{
			pdo_delete('weixin_wall', array('weid' =>$weid,'rid'=>$id));
			pdo_update('weixin_wall_num',array('num'=>1),array('weid'=>$weid,'rid'=>$id));
			message('清除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='delete' && $_GPC['del']=='allperson')
		{
			pdo_delete('weixin_flag', array('weid' =>$weid,'rid'=>$id));
			pdo_delete('weixin_signs', array('weid' =>$weid,'rid'=>$id));
			message('清除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='delete' && $_GPC['del']=='yyy')
		{
			pdo_update("weixin_wall_reply",array('isopen'=>1),array('weid'=>$weid,'rid'=>$id));
			pdo_delete('weixin_shake_toshake', array('weid' =>$weid,'rid'=>$id));
			message('清除摇一摇成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='reset' && $_GPC['del']=='yyy')
		{
			pdo_update("weixin_wall_reply",array('isopen'=>1),array('weid'=>$weid,'rid'=>$id));
			pdo_update('weixin_shake_toshake',array('point'=>0), array('weid' =>$weid,'rid'=>$id));
			message('重置摇一摇成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='delete' && $_GPC['del']=='bahe')
		{
			pdo_update("weixin_wall_reply",array('bahe_status'=>0),array('weid'=>$weid,'rid'=>$id));
			pdo_delete('weixin_bahe_team', array('weid' =>$weid,'rid'=>$id));
			message('清除拔河数据成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='reset' && $_GPC['del']=='bahe')
		{
			pdo_update("weixin_wall_reply",array('bahe_status'=>0),array('weid'=>$weid,'rid'=>$id));
			pdo_update('weixin_bahe_team', array('point'=>0,'team'=>0),array('weid' =>$weid,'rid'=>$id));
			message('重置拔河数据成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='reset' && $_GPC['del']=='vote')
		{
			pdo_update('weixin_vote',array('res'=>0),array('weid'=>$weid,'rid'=>$id));
			pdo_update('weixin_flag',array('vote'=>0),array('weid'=>$weid,'rid'=>$id));
			message('清除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='reset' && $_GPC['del']=='cj')
		{
			pdo_update('weixin_awardlist',array('num'=>0),array('weid'=>$weid,'luckid'=>$id));
			pdo_delete('weixin_luckuser',array('weid'=>$weid,'rid'=>$id));
			pdo_update('weixin_flag',array('award_id'=>'meepo'),array('weid'=>$weid,'rid'=>$id));
			message('清除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		$isshow = isset($_GPC['isshow']) ? intval($_GPC['isshow']) : 0;
		$op = $_GPC['op'];
		$keyword = $_GPC['keyword'];
		$mobile = $_GPC['mobile'];
		$changeisshow = $_GPC['changeisshow'];
		if (!empty($changeisshow) && $changeisshow=='修改') 
		{
			$isshowstatus = intval($_GPC['isshowstatus']);
			if($isshowstatus == 2)
			{
				$isshowstatus = 0;
			}
			else
			{
				$isshowstatus = 1;
			}
			pdo_update('weixin_wall_reply',array('isshow'=>$isshowstatus),array('rid'=>$id,'weid'=>$weid));
			message('修改成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
		}
		if($_GPC['docon']=='delete')
		{
			$news_id = intval($_GPC['news_id']);
			if($news_id)
			{
				$sql = 'DELETE FROM'.tablename('weixin_wall')." WHERE rid=:rid AND weid=:weid AND id=:id";
				pdo_query($sql, array(':rid' => $id,':weid'=>$weid,':id'=>$news_id));
				message('删除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
			}
			else
			{
				message('删除失败！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'error');
			}
		}
		if(!$op)
		{
			if (checksubmit('verify') && !empty($_GPC['select'])) 
			{
				foreach ($_GPC['select'] as $row) 
				{
					$row = intval($row);
					pdo_update('weixin_wall',array('isshow'=>1),array('id'=>$row,'rid'=>$id,'weid'=>$_W['uniacid']));
				}
				message('审核成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
			}
			if (checksubmit('delete') && !empty($_GPC['select'])) 
			{
				$sql = 'DELETE FROM'.tablename('weixin_wall')." WHERE rid=:rid AND weid=:weid AND id  IN  ('".implode("','", $_GPC['select'])."')";
				pdo_query($sql, array(':rid' => $id,':weid'=>$weid));
				message('删除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
			}
			$condition = '';
			$condition .= "AND nickname != ''";
			if($isshow == 0) 
			{
				$condition .= 'AND isshow = '.$isshow;
			}
			else 
			{
				$condition .= 'AND isshow > 0';
			}
			if (!empty($keyword)) 
			{
				$condition .= " AND nickname LIKE '%{$_GPC['keyword']}
			%'";
		}
		if (!empty($mobile)) 
		{
			$condition .= " AND mobile LIKE '%{$_GPC['mobile']}
		%'";
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE rid = '{$id}
' AND weid = '{$weid}
' {$condition}
ORDER BY createtime DESC LIMIT ".($pindex - 1) * $psize.",{$psize}
");
if (!empty($list)) 
{
foreach ($list as &$row) 
{
if ($row['type'] == 'link') 
{
$row['content'] = iunserializer($row['content']);
$row['content'] = '<a href="'.$row['content']['link'].'" target="_blank" title="'.$row['content']['description'].'">'.$row['content']['title'].'</a>';
}
elseif ($row['type'] == 'image') 
{
$row['content'] = '<img src="'.$row['image'].'" width=50px height=50px/>';
}
else 
{
$row['content'] = emotion(emo($row['content']));
}
}
unset($row);
}
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_wall') . " WHERE rid = '{$id}
' AND weid = '{$weid}
' {$condition}
");
$pager = pagination($total, $pindex, $psize);
}
else
{
$condition = '';
if (!empty($keyword)) 
{
$condition .= " AND nickname LIKE '%{$_GPC['keyword']}
%'";
}
if (!empty($mobile)) 
{
$condition .= " AND mobile LIKE '%{$_GPC['mobile']}
%'";
}
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE rid = '{$id}
' AND weid = '{$weid}
' {$condition}
");
$pager = pagination($total, $pindex, $psize);
$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE rid = '{$id}
' AND weid = '{$weid}
' {$condition}
ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.",{$psize}
");
if(is_array($list))
{
foreach($list as &$row)
{
$num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_wall') . " WHERE rid = '{$id}
' AND weid = '{$weid}
' AND openid = '{$row['openid']}
'");
$row['num'] = $num;
if($row['cjstatu'] > 0)
{
$cj = pdo_fetch('SELECT tag_name,luck_name FROM ' . tablename('weixin_awardlist') . " WHERE id = '{$row['cjstatu']}
' AND weid = '{$weid}
'");
$row['cj'] = "已内定为".$cj['tag_name'];
}
$sign_status = pdo_fetchcolumn('SELECT `status` FROM ' . tablename('weixin_signs') . " WHERE rid = '{$id}
' AND weid = '{$weid}
' AND openid = '{$row['openid']}
'");
$row['sign'] = $sign_status;
}
unset($row);
}
if (checksubmit('download'))
{
if (PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');
$tableheader = array('ID', '微信昵称','性别', '真实姓名', '手机号码');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) 
{
$html .= $value . "\t ,";
}
$html .= "\n";
$messageids = $_GPC['messageid'];
if(is_array($messageids))
{
foreach ($messageids as &$row) 
{
$row = intval($row);
}
$sql = "select * from ".tablename('weixin_flag')." where weid=:weid AND rid=:rid AND id  IN  ('".implode("','", $messageids)."') ORDER BY id DESC";
$listdown = pdo_fetchall($sql,array(':weid'=>$_W['uniacid'],':rid'=>$id));
}
else
{
$sql = "select * from ".tablename('weixin_flag')." where weid=:weid AND rid=:rid ORDER BY id DESC";
$listdown = pdo_fetchall($sql,array(':weid'=>$_W['uniacid'],':rid'=>$id));
}
foreach ($listdown as $value) 
{
if($value['sex'] == '1')
{
$value['sex'] = '男';
}
elseif($value['sex'] == '2')
{
$value['sex'] = '女';
}
else
{
$value['sex'] = '未知';
}
$html .= $value['id'] . "\t ,";
$html .= $value['nickname'] . "\t ,";
$html .= $value['sex'] . "\t ,";
$html .= (empty($value['realname']) ? '未录入' : $value['realname']) . "\t ,";
$html .= (empty($value['mobile']) ? '未录入' : $value['mobile']) . "\t ,";
$html .= "\n";
}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=本次活动人员全部数据.csv");
echo $html;
exit();
}
}
include $this->template('manage');
}
public function doWebBlacklist() 
{
global $_W, $_GPC;
$id = intval($_GPC['id']);
$weid = $_W['weid'];
if (checksubmit('delete') && isset($_GPC['select']) && !empty($_GPC['select'])) 
{
foreach ($_GPC['select'] as $row) 
{
pdo_update('weixin_flag',array('isblacklist'=>0),array('openid'=>$row,'rid'=>$id,'weid'=>$weid));
pdo_update('weixin_wall',array('isblacklist'=>0),array('openid'=>$row,'rid'=>$id,'weid'=>$weid));
}
message('黑名单解除成功！', $this->createWebUrl('blacklist', array('id' => $id, 'page' => $_GPC['page'])));
}
if (!empty($_GPC['openid'])) 
{
$isshow = isset($_GPC['isshow']) ? intval($_GPC['isshow']) : 0;
pdo_update('weixin_flag', array('isblacklist' => intval($_GPC['switch'])), array('openid' => $_GPC['openid'], 'rid'=>$id,'weid'=>$weid));
pdo_update('weixin_wall',array('isblacklist'=>intval($_GPC['switch'])),array('openid'=>$_GPC['openid'],'rid'=>$id,'weid'=>$weid));
if(empty($_GPC['op']))
{
message('&#x65B0;&#x777F;&#x793E;&#x533A;&#x63D0;&#x793A;&#xFF1A;&#x64CD;&#x4F5C;&#x6210;&#x529F;&#xFF01;', $this->createWebUrl('manage', array('id' => $id, 'isshow' => $isshow)));
}
else
{
message('&#x65B0;&#x777F;&#x793E;&#x533A;&#x63D0;&#x793A;&#xFF1A;&#x64CD;&#x4F5C;&#x6210;&#x529F;&#xFF01;', $this->createWebUrl('manage', array('id' => $id, 'op' =>$_GPC['op'])));
}
}
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE isblacklist = '1' AND rid=:rid AND weid=:weid ORDER BY lastupdate DESC LIMIT ".($pindex - 1) * $psize.",{$psize}
", array(':rid' => $id,':weid'=>$weid), 'from_user');
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE isblacklist = '1' AND rid=:rid AND weid=:weid", array(':rid' => $id,':weid'=>$weid));
$pager = pagination($total, $pindex, $psize);
include $this->template('blacklist');
}
public function doWebAwardlist() 
{
global $_GPC, $_W;
$weid = $_W['uniacid'];
checklogin();
$id = intval($_GPC['id']);
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = "SELECT * FROM ".tablename('weixin_luckuser')." WHERE  weid=:weid AND rid=:rid ORDER BY createtime ASC LIMIT ".($pindex - 1) * $psize.",{$psize}
";
$list = pdo_fetchall($sql, array(':weid'=>$weid,':rid'=>$id));
if(!empty($list) && is_array($list))
{
foreach($list as &$row)
{
$info = pdo_fetch("SELECT nickname,avatar FROM ".tablename('weixin_flag')."WHERE openid = :openid AND weid = :weid AND rid=:rid",array(':openid'=>$row['openid'],':weid'=>$weid,':rid'=>$id));
$row['avatar'] = $info['avatar'];
$row['nickname'] = $info['nickname'];
if($row['awardid'] && empty($row['bypername']))
{
$luckinfo = pdo_fetch("SELECT tag_name,luck_name FROM ".tablename('weixin_awardlist')."WHERE id = :id AND weid = :weid AND luckid=:luckid",array(':id'=>$row['awardid'],':weid'=>$weid,':luckid'=>$id));
$row['tag_name'] = $luckinfo['tag_name'];
$row['luck_name'] = $luckinfo['luck_name'];
}
else
{
$row['tag_name'] = '按人数抽奖';
$row['luck_name'] = $row['bypername'];
}
}
unset($row);
}
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_luckuser') . " WHERE  rid=:rid AND weid=:weid", array(':rid' =>$id,':weid'=>$weid));
$pager = pagination($total, $pindex, $psize);
include $this->template('awardlist');
}
public function doWebyyyres() 
{
global $_GPC, $_W;
checklogin();
$weid = $_W['weid'];
$id = intval($_GPC['id']);
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql1="SELECT * FROM  ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid ORDER BY point DESC LIMIT ".($pindex - 1) * $psize.",{$psize}
";
$list = pdo_fetchall($sql1,array(':weid'=>$weid,':rid'=>$id));
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_shake_toshake') . " WHERE  rid=:rid AND weid=:weid", array(':rid' => $id,':weid'=>$weid));
$pager = pagination($total, $pindex, $psize);
include $this->template('yyyres');
}
public function doWebtoupiao()
{
global $_GPC, $_W;
$id = intval($_GPC['id']);
$weid = $_W['uniacid'];
$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_vote')." where weid=:weid AND rid=:rid ORDER BY res DESC",array(':weid'=>$weid,':rid'=>$id));
include $this->template('toupiao');
}
public function doMobilesaveluckuser()
{
global $_GPC, $_W;
$weid = $_W['uniacid'];
$data = 1;
$awardid = intval($_GPC['luckUser_luckTagId']);
$openid = trim($_GPC['luckUser_openid']);
$luckname = trim($_GPC['luckUser_perAward']);
$rid = intval($_GPC['rid']);
$one = pdo_fetch("SELECT `nickname`,`mobile` FROM " . tablename('weixin_flag') . " WHERE openid = :openid AND weid=:weid AND rid=:rid", array(':openid' =>$openid,':weid'=>$weid,':rid'=>$rid));
if(!empty($luckname))
{
$lastnum = pdo_fetchcolumn("SELECT `num` FROM " . tablename('weixin_awardlist') . " WHERE id = :id AND weid=:weid AND luckid=:luckid", array(':id' =>$awardid,':weid'=>$weid,':luckid'=>$rid));
if(!empty($openid) && $awardid)
{
pdo_update('weixin_awardlist',array('num'=>$lastnum + 1),array('id'=>$awardid,'weid'=>$weid,'luckid'=>$rid));
pdo_update('weixin_flag',array('award_id'=>$awardid),array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
pdo_insert('weixin_luckuser',array('awardid'=>$awardid,'openid'=>$openid,'weid'=>$weid,'createtime'=>time(),'bypername'=>$luckname,'rid'=>$rid));
$award = pdo_fetch("SELECT `tag_name`,`luck_name` FROM " . tablename('weixin_awardlist') . " WHERE id = :id AND weid=:weid AND luckid=:luckid", array(':id' =>$awardid,':weid'=>$weid,':luckid'=>$rid));
$send_to = pdo_fetch("SELECT	`send_luck_words`,`can_send` FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid", array(':weid'=>$weid,':rid'=>$rid));
if($send_to['can_send']==1)
{
if(empty($send_to['send_luck_words']))
{
$content = "亲爱的".$one['nickname']."\n恭喜恭喜！\n你已经中: ".$award['tag_name']."\n奖品为: ".$award['luck_name']."\n请按照主持人的提示，到指定地点领取您的奖品！\n您的获奖验证码是: ".time();
}
else
{
if(strexists($send_to['send_luck_words'], '#'))
{
$send_to['send_luck_words'] = str_replace('#',$one['nickname'],$send_to['send_luck_words']);
}
if(strexists($send_to['send_luck_words'], '$'))
{
$send_to['send_luck_words'] = str_replace('$',$award['tag_name'],$send_to['send_luck_words']);
}
if(strexists($send_to['send_luck_words'], '%'))
{
$send_to['send_luck_words'] = str_replace('%',$award['luck_name'],$send_to['send_luck_words']);
}
if(strexists($send_to['send_luck_words'], '&'))
{
$send_to['send_luck_words'] = str_replace('&',time(),$send_to['send_luck_words']);
}
$content = $send_to['send_luck_words'];
}
}
$newid = pdo_fetchcolumn("SELECT id FROM ".tablename('weixin_luckuser')."WHERE awardid = :awardid AND weid = :weid AND rid=:rid AND openid=:openid ORDER BY id DESC",array(':awardid'=>$awardid,':weid'=>$weid,':rid'=>$rid,':openid'=>$openid));
$data = array();
$data['id'] = intval($newid);
$data['mobile'] = $one['mobile'];
$this->sendmessage($rid,$openid,$content);
die(json_encode($data));
}
else
{
die('');
}
}
}
public function doWebCheckvote()
{
global $_GPC, $_W;
$weid = $_W['uniacid'];
$id = intval($_GPC['id']);
if(empty($id))
{
message('规则id不存在');
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') 
{
$conditions = '';
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$list = pdo_fetchall("SELECT * FROM " . tablename('weixin_vote') . " WHERE weid=:weid AND rid = :rid ORDER BY res DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}
",array(':weid'=>$weid,':rid'=>$id));
if(is_array($list))
{
foreach($list as &$row)
{
$ridname = pdo_fetchcolumn("SELECT name FROM ".tablename('rule')." WHERE id='{$row['rid']}
' AND uniacid='{$weid}
'");
$row['ridname'] = $ridname;
}
unset($row);
}
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_vote') . " WHERE weid='{$weid}
' $conditions");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'post') 
{
$voteid = intval($_GPC['voteid']);
if (checksubmit('submit')) 
{
$data = array( 'weid' => $_W['uniacid'], 'name' => $_GPC['name'], 'res'=>intval($_GPC['res']), 'rid' =>$id, 'vote_img'=>$_GPC['vote_img'], );
if (!empty($voteid)) 
{
pdo_update('weixin_vote', $data, array('id' => $voteid));
message('更新投票成功！', $this->createWebUrl('Checkvote', array('id'=>$id,'op' => 'post')), 'success');
}
else 
{
pdo_insert('weixin_vote', $data);
$voteid = pdo_insertid();
message('&#x65B0;_&#x777F;_&#x793E;_&#x533A;_&#x63D0;_&#x793A;&#xFF1A;&#x65B0;&#x589E;&#x6295;&#x7968;&#x6210;&#x529F;&#xFF01;', $this->createWebUrl('Checkvote', array('id'=>$id,'op' => 'post')), 'success');
}
}
$vote = pdo_fetch("SELECT * FROM " . tablename('weixin_vote') . " WHERE id=:id and weid=:weid", array(":id" => $voteid, ":weid" => $_W['uniacid']));
}
elseif ($operation == 'delete') 
{
$voteid = intval($_GPC['voteid']);
$vote = pdo_fetch("SELECT *  FROM " . tablename('weixin_vote') . " WHERE id=:id and weid=:weid", array(":id" =>$voteid, ":weid" => $_W['uniacid']));
if (empty($vote)) 
{
message('抱歉，此项不存在或是已经被删除！', $this->createWebUrl('Checkvote', array('id'=>$id,'op' => 'display')), 'error');
}
pdo_delete('weixin_vote', array('id' => $voteid));
message('投票删除成功！', $this->createWebUrl('Checkvote', array('id'=>$id,'op' => 'display')), 'success');
}
else 
{
message('请求方式不存在');
}
include $this->template('votelist', TEMPLATE_INCLUDEPATH, true);
}
public function doWebawardmanage()
{
global $_W, $_GPC;
$weid = $_W['uniacid'];
$id = intval($_GPC['id']);
if(empty($id))
{
message('规则不存在！');
}
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') 
{
$list = pdo_fetchall("SELECT * FROM " . tablename('weixin_awardlist') . " WHERE weid = :weid AND luckid = :luckid ORDER BY  luckid,displayid ASC",array(':weid'=>$weid,':luckid'=>$id));
if(is_array($list))
{
foreach($list as &$row)
{
$nd = iunserializer($row['nd']);
if(is_array($nd))
{
$row['nd'] = implode(',',$nd);
}
$ridname = pdo_fetchcolumn("SELECT name FROM ".tablename('rule')." WHERE id='{$row['luckid']}
' AND uniacid='{$weid}
'");
$row['huodongname'] = $ridname;
}
unset($row);
}
}
elseif ($operation == 'post') 
{
$cjid = intval($_GPC['cjid']);
if (checksubmit('submit')) 
{
$data = array( 'displayid'=>intval($_GPC['displayid']), 'weid' => $_W['uniacid'], 'luckid'=>$id, 'luck_name' => trim($_GPC['luck_name']), 'tag_name' => trim($_GPC['tag_name']), 'tagNum' => intval($_GPC['tagNum']), 'tag_exclude'=>intval($_GPC['tag_exclude']) );
if(!empty($_GPC['nd']))
{
$ndone = trim($_GPC['nd']);
if(strpos($ndone,'，') || strpos($ndone,' '))
{
message('内定ID, 请用英文逗号隔开且数字ID之间不能有空格','referer','error');
}
else
{
$signtotalall = pdo_fetchall('SELECT	`id` FROM ' . tablename('weixin_flag') . " WHERE weid = '{$weid}
' AND rid='{$id}
'");
if(empty($signtotalall))
{
message('暂无粉丝录入基本信息 无法内定！！！','referer','error');
}
foreach($signtotalall as $val)
{
$signtotal[] = $val['id'];
}
if(strpos($ndone,','))
{
$arr = explode(',',$ndone);
if(is_array($arr) && !empty($arr))
{
if(COUNT($arr) > $data['tagNum'])
{
message('内定人数不可超过奖品总数量，请仔细核对！','referer','error');
}
foreach($arr as $row)
{
$row = intval($row);
if(!$row)
{
message('内定粉丝ID异常，请仔细核对！','referer','error');
}
if(!in_array($row,$signtotal))
{
message('内定粉丝ID异常，请仔细核对！','referer','error');
}
if($id)
{
pdo_update("weixin_flag",array('cjstatu'=>$cjid),array('weid'=>$weid,'id'=>$row));
}
else
{
message('请在添加该奖品成功后 且确定添加粉丝已经签到再添加该奖项的抽奖内定粉丝','referer','error');
}
}
$data['nd'] = iserializer($arr);
}
else
{
message('内定粉丝ID异常，请仔细核对！','referer','error');
}
}
else
{
$nd = intval($ndone);
if(in_array($nd,$signtotal))
{
$data['nd'] = $nd;
}
else
{
message('粉丝ID异常, 请仔细核对','referer','error');
}
if($cjid)
{
pdo_update("weixin_flag",array('cjstatu'=>$cjid),array('weid'=>$weid,'id'=>$nd));
}
else
{
message('请在添加该奖品成功后 且确定添加粉丝已经签到再添加该奖项的抽奖内定粉丝','referer','error');
}
}
}
}
if (!empty($cjid)) 
{
pdo_update('weixin_awardlist', $data, array('id' => $cjid));
message('奖品更新成功！', $this->createWebUrl('awardmanage', array('id'=>$id)), 'success');
}
else 
{
pdo_insert('weixin_awardlist', $data);
$cjid = pdo_insertid();
}
message('奖品更新成功！', $this->createWebUrl('awardmanage', array('id'=>$id,'op' => 'post')), 'success');
}
$cj = pdo_fetch("select * from " . tablename('weixin_awardlist') . " where id=:id and weid=:weid", array(":id" => $cjid, ":weid" => $_W['uniacid']));
$nd = iunserializer($cj['nd']);
if(is_array($nd))
{
$cj['nd'] = implode(',',$nd);
}
if(!$cjid)
{
$cj['tag_exclude'] = '1';
}
}
elseif ($operation == 'delete') 
{
$cjid = intval($_GPC['cjid']);
$cj = pdo_fetch("SELECT * FROM " . tablename('weixin_awardlist') . " WHERE id=:id and weid=:weid", array(":id" => $cjid, ":weid" => $_W['uniacid']));
if (empty($cj)) 
{
message('抱歉，改奖品不存在或是已经被删除！', $this->createWebUrl('awardmanage', array('id'=>$cjid,'op' => 'display')), 'error');
}
pdo_delete('weixin_awardlist', array('id' =>$cjid));
$check_hadnd = pdo_fetchall("SELECT `id` FROM ".tablename('weixin_flag')." WHERE cjstatu=:cjstatu AND weid=:weid",array(':cjstatu'=>$cjid,':weid'=>$weid));
if(!empty($check_hadnd) && is_array($check_hadnd))
{
foreach($check_hadnd as $row)
{
pdo_update("weixin_flag",array('cjstatu'=>0),array('weid'=>$weid,'id'=>$row['id']));
}
}
$check_luckuser = pdo_fetchall("SELECT `id` FROM ".tablename('weixin_flag')." WHERE award_id=:award_id AND weid=:weid",array(':award_id'=>$cjid,':weid'=>$weid));
if(!empty($check_luckuser) && is_array($check_luckuser))
{
foreach($check_luckuser as $row)
{
pdo_update('weixin_flag',array('award_id'=>'meepo'),array('weid'=>$weid,'id'=>$row['id']));
}
}
pdo_delete('weixin_luckuser',array('awardid'=>$cjid,'rid'=>$id));
message('奖品删除成功！', $this->createWebUrl('awardmanage', array('id'=>$id,'op' => 'display')), 'success');
}
elseif($operation == 'delnd')
{
$cjid = intval($_GPC['cjid']);
$ndall = pdo_fetch("SELECT * FROM " . tablename('weixin_awardlist') . " WHERE id=:id AND weid=:weid", array(":id" => $cjid, ":weid" => $_W['uniacid']));
$nd = iunserializer($ndall['nd']);
pdo_update('weixin_awardlist',array('nd'=>''),array('weid'=>$weid,'id'=>$cjid));
if(!is_array($nd))
{
pdo_update("weixin_flag",array('cjstatu'=>0),array('weid'=>$weid,'id'=>$nd));
}
else
{
foreach($nd as $row)
{
pdo_update("weixin_flag",array('cjstatu'=>0),array('weid'=>$weid,'id'=>$row));
}
}
message('&#x65B0;n&#x777F;n&#x793E;n&#x533A;n&#x63D0;n&#x793A;&#xFF1A;&#x64CD;&#x4F5C;&#x6210;&#x529F;&#xFF01;', $this->createWebUrl('awardmanage', array('id'=>$id,'op' => 'post','cjid'=>$cjid)), 'success');
}
else 
{
message('请求方式不存在');
}
include $this->template('awardmanage', TEMPLATE_INCLUDEPATH, true);
}
public function doMobileoauth2()
{
global $_GPC, $_W;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
if(empty($rid))
{
message('参数错误、请重新进入！');
}
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
$openid = $_GPC['from_user'];
load()->model('mc');
$userinfo = mc_oauth_userinfo();
if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo)) 
{
if(!empty($userinfo['avatar']))
{
$data['avatar'] = $userinfo['avatar'];
}
else
{
$data['avatar'] = '../addons/meepo_bigerwall/cdhn80.jpg';
}
if(empty($userinfo['sex']))
{
$data['sex'] = '0';
}
else
{
$data['sex'] = $userinfo['sex'];
}
$sql = "SELECT * FROM ".tablename('weixin_flag')." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' => $openid, ':rid' => $rid,':weid' =>$_W['uniacid']);
$flag = pdo_fetch($sql,$param);
if(!empty($flag))
{
if(!empty($userinfo['nickname']))
{
$data['nickname'] = $userinfo['nickname'];
}
else
{
$data['nickname'] = '微信昵称无法识别';
}
$userinfo['nickname'];
$verify = random(5,$numeric = true);
$data['fakeid'] = $verify;
$data['msgid'] = $verify;
pdo_update("weixin_flag",$data,array('id'=>$flag['id']));
}
}
else
{
message("借用oauth失败");
}
include $this->template('oauth');
}
public function doMobilepairmanready()
{
global $_GPC, $_W;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid   AND  sex=:sex",array(":weid"=>$weid,':rid'=>$rid,':sex'=>'1'));
if(!empty($data))
{
foreach($data as $v)
{
$that = $v['nickname'].'|'.$v['id'].'|'.$v['avatar'].'|'.$v['openid'];
$arr[] = $that;
}
}
echo json_encode($arr);
}
public function removeEmoji($nickname) 
{
$clean_text = "";
$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
$clean_text = preg_replace($regexEmoticons, '', $nickname);
$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
$clean_text = preg_replace($regexSymbols, '', $clean_text);
$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
$clean_text = preg_replace($regexTransport, '', $clean_text);
$regexMisc = '/[\x{2600}-\x{26FF}]/u';
$clean_text = preg_replace($regexMisc, '', $clean_text);
$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
$clean_text = preg_replace($regexDingbats, '', $clean_text);
$clean_text = str_replace("'",'',$clean_text);
$clean_text = str_replace('"','',$clean_text);
$clean_text = str_replace('“','',$clean_text);
$clean_text = str_replace('゛','',$clean_text);
$search = array(" ","　","\n","\r","\t");
$replace = array("","","","","");
return str_replace($search, $replace, $clean_text);
}
public function doMobilepairwomanready()
{
global $_GPC, $_W;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid AND  sex=:sex",array(":weid"=>$weid,':rid'=>$rid,':sex'=>'2'));
if(!empty($data))
{
foreach($data as $v)
{
$that = $v['nickname'].'|'.$v['id'].'|'.$v['avatar'].'|'.$v['openid'];
$arr[] = $that;
}
}
echo json_encode($arr);
}
public function doMobiletanmu()
{
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
if(empty($rid))
{
message('参数错误，请重新进入！');
}
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
if(empty($ridwall['refreshtime']))
{
$ridwall['refreshtime'] = 5000;
}
else
{
$ridwall['refreshtime'] = $ridwall['refreshtime']*1000;
}
$saytasktime = $ridwall['refreshtime'] - 1000;
if(empty($ridwall['voterefreshtime']))
{
$ridwall['voterefreshtime'] = 10000;
}
else
{
$ridwall['voterefreshtime'] = $ridwall['voterefreshtime']*1000;
}
if(!empty($ridwall["indexstyle"]))
{
$style = $ridwall["indexstyle"];
}
else
{
$style ="defaultV1.0.css";
}
include $this->template('danmu');
}
public function doMobilejoin()
{
global $_GPC, $_W;
$weid = $_W['uniacid'];
if($_W['isajax'])
{
$rid = intval($_GPC['rid']);
}
}
public function logging_sign($weid,$rid,$log)
{
global $_W;
$filename = IA_ROOT . '/addons/meepo_bigerwall/'.$weid.'/'. $rid . '/sign.txt';
load()->func('file');
mkdirs(dirname(__FILE__));
$fp=fopen($filename,"a+");
$str =$log;
fwrite($fp,$str);
fclose($fp);
}
private function sendmessage($rid,$touser,$content)
{
global $_GPC, $_W;
$weid = $_W['uniacid'];
$renzhen = pdo_fetchcolumn("SELECT `renzhen` FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$_W['uniacid']));
if($renzhen=='1')
{
load()->classs('weixin.account');
$accObj= WeixinAccount::create($_W['account']['acid']);
$access_token = $accObj->fetch_token();
$token2 = $access_token;
$data = '{
								"touser":"'.$touser.'",
								"msgtype":"text",
								"text":
								{
									"content":"'.$content.'"
								}
							}';
load()->func('communication');
$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token2;
ihttp_post($url, $data);
}
}
}
function emo($content)
{
$emo = array("[笑脸]", "[感冒]", "[流泪]", "[发怒]", "[爱慕]", "[吐舌]", "[发呆]", "[可爱]", "[调皮]", "[寒冷]", "[呲牙]", "[闭嘴]", "[害羞]", "[苦闷]", "[难过]", "[流汗]", "[犯困]", "[惊恐]", "[咖啡]", "[炸弹]", "[西瓜]", "[爱心]", "[心碎]");
foreach($emo as $key=>$val)
{
if(strexists($content, $val))
{
$imgurl = MEEPO.'common/emo/'.($key+1).'.png';
$replace = '<img src="'.$imgurl.'" border="0" class="dt_emo" width=20px height=20px />';
$content = str_replace($val,$replace,$content);
}
}
return $content;
}
$GLOBALS["??߆"]=null;
unset($GLOBALS["??߆"]);
?>