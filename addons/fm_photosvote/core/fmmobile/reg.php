<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
		$regtitlearr = iunserializer($rdisplay['regtitlearr']);
		$photosarrid = pdo_fetch("SELECT id FROM ".tablename($this->table_users_picarr)." WHERE rid = :rid ORDER BY id DESC LIMIT 1", array(':rid' => $rid));
		$mid = $photosarrid['id'] + 1;
		$addmid = $mid + 1;
		$photosarrnum = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
		if ($_GPC['delete']) {
			if ($rshare['subscribe'] && !$follow) {
				$fmdata = array(
					"success" => -1,
					"flag" => 5,
					"msg" => '请先关注',
				);
				echo json_encode($fmdata);
				exit();
			}

			$photo = pdo_fetch("SELECT id,isfm, photoname FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user AND rid = :rid AND id = :id", array(':from_user' => $from_user,':rid' => $rid,':id' => $_GPC['photoid']));
			if (empty($photo)) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '未找到你要删除的图片',
				);
				echo json_encode($fmdata);
				exit();
			}
			if ($photo['isfm']) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '该图片目前作为投票封面使用,请设置其他图片作为投票封面.然后再删除',
				);
				echo json_encode($fmdata);
				exit();
			}
			load()->func('file');
			$updir = '../attachment/images/'.$uniacid.'/'.date("Y").'/'.date("m").'/'.$photo['photoname'];
			file_delete($updir);
			pdo_delete($this->table_users_picarr, array('id' => intval($_GPC['photoid']), 'rid' => $rid, 'from_user' => $from_user));

			$fmdata = array(
				"success" => 1,
				"flag" => 1,
				"lastmid" => $photosarrid,
				"addlastmid" => $mid,
				"photosarrnum" => $photosarrnum,
				"msg" => '删除成功'
			);
			echo json_encode($fmdata);
			exit();
		}
		if ($_GPC['deletev']) {
			if ($rshare['subscribe'] && !$follow) {
				$fmdata = array(
					"success" => -1,
					"flag" => 5,
					"msg" => '请先关注',
				);
				echo json_encode($fmdata);
				exit();
			}
			$vedio = pdo_fetch("SELECT id, vedio FROM ".tablename($this->table_users)." WHERE from_user = :from_user AND rid = :rid AND vedio = :vedio", array(':from_user' => $from_user,':rid' => $rid,':vedio' => $_GPC['vedio']));
			if (empty($vedio)) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '未找到你要删除的视频',
				);
				echo json_encode($fmdata);
				exit();
			}
			load()->func('file');
			$updir = '../attachment/audios/'.$uniacid.'/'.date("Y").'/'.date("m").'/'.$vedio['vedio'];
			file_delete($updir);
			pdo_update($this->table_users, array('vedio' => ''), array('id' => intval($vedio['id']), 'rid' => $rid, 'from_user' => $from_user));

			$fmdata = array(
				"success" => 1,
				"flag" => 1,
				"mid" => $vedio['id'],
				"msg" => '删除成功'
			);
			echo json_encode($fmdata);
			exit();
		}

		if ($_GPC['deletem']) {
			if ($rshare['subscribe'] && !$follow) {
				$fmdata = array(
					"success" => -1,
					"flag" => 5,
					"msg" => '请先关注',
				);
				echo json_encode($fmdata);
				exit();
			}
			$music = pdo_fetch("SELECT id, music FROM ".tablename($this->table_users)." WHERE from_user = :from_user AND rid = :rid AND music = :music", array(':from_user' => $from_user,':rid' => $rid,':music' => $_GPC['music']));
			if (empty($music)) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '未找到你要删除的音频',
				);
				echo json_encode($fmdata);
				exit();
			}
			load()->func('file');
			$updir = '../attachment/audios/'.$uniacid.'/'.date("Y").'/'.date("m").'/'.$music['music'];
			file_delete($updir);
			pdo_update($this->table_users, array('music' => ''), array('id' => intval($music['id']), 'rid' => $rid, 'from_user' => $from_user));

			$fmdata = array(
				"success" => 1,
				"flag" => 1,
				"mid" => $music['id'],
				"msg" => '删除成功'
			);
			echo json_encode($fmdata);
			exit();
		}
		$photosarr = pdo_fetchall("SELECT * FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user and rid = :rid and status = :status ORDER BY id ASC", array(':from_user' => $from_user,':rid' => $rid,':status' => 1));//显示所有图片
		if ($_GPC['setfm']) {
			if ($rshare['subscribe'] && !$follow) {
				$fmdata = array(
					"success" => -1,
					"flag" => 5,
					"msg" => '请先关注',
				);
				echo json_encode($fmdata);
				exit();
			}
			$photo = pdo_fetch("SELECT id,isfm, photoname FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user AND rid = :rid AND id = :id", array(':from_user' => $from_user,':rid' => $rid,':id' => $_GPC['photoid']));

			if (empty($photo)) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '未找到你要设置封面的图片',
				);
				echo json_encode($fmdata);
				exit();
			}
			if ($photo['isfm']) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '该图片已经是投票封面,请设置其他图片作为投票封面。',
				);
				echo json_encode($fmdata);
				exit();
			}
			foreach ($photosarr as $key => $value) {
				if ($value['isfm'] == 1) {
					$delmid = $value['id'];
				}
				pdo_update($this->table_users_picarr,array('isfm' => 0), array('id' => intval($value['id'])));
			}
			pdo_update($this->table_users_picarr,array('isfm' => 1), array('id' => intval($_GPC['photoid']), 'rid' => $rid, 'from_user' => $from_user));

			$fmdata = array(
				"success" => 1,
				"flag" => 1,
				"lastmid" => $photosarrid,
				"addlastmid" => $mid,
				"delmid" => $delmid,
				"msg" => '设置成功！'
			);
			echo json_encode($fmdata);
			exit();
		}
		if ($rdisplay['ipannounce'] == 1) {
			$announce = pdo_fetchall("SELECT nickname,content,createtime,url FROM " . tablename($this->table_announce) . " WHERE rid= '{$rid}' ORDER BY id DESC");
		}
		//赞助商
		if ($rdisplay['isreg'] == 1) {
			$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this -> table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND rid= '{$rid}'");
		}

		//查询是否参与活动
		$where = '';
		$mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
		if ($rvote['regpay']==1) {
			if ($_W['account']['level'] == 4) {
				$u_uniacid = $uniacid;
			}else{
				$u_uniacid = $cfg['u_uniacid'];
			}
			$pays = pdo_fetch("SELECT payment FROM " . tablename('uni_settings') . " WHERE uniacid='{$uniacid}' limit 1");
			$pay = iunserializer($pays['payment']);


			//付款
			$payordersn = pdo_fetch("SELECT id,payyz,ordersn FROM " . tablename($this->table_order) . " WHERE rid='{$rid}' AND from_user = :from_user AND paytype = 3  ORDER BY id DESC,paytime DESC limit 1", array(':from_user'=>$from_user));
			if ($payordersn['ordersn']) {
				$orderid = $payordersn['ordersn'];
			}else{
				$orderid = date('ymdhis') . random(4, 1);
			}

			$params['tid'] = $orderid;
			$params['rid'] = $rid;
			$params['user'] = $from_user;
			$params['fee'] = $rvote['regpayfee'];
			$params['title'] = $rvote['regpaytitle'];
			$params['content'] = $rvote['regpaydes'];
			$params['ordersn'] = $orderid;
			$params['module'] = $_GPC['m'];
			$params['paytype'] = 3;
			$params['payyz'] = random(8);
			//$params['virtual'] = $item['goodstype'] == 2 ? true : false;
			//$pparams = base64_encode(iserializer($params));
			if (!empty($_GPC['paymore'])) {
				$paymore = iunserializer(base64_decode(base64_decode($_GPC['paymore'])));
			}
			//print_r($params);
			$voteordersn = pdo_fetch("SELECT ordersn FROM " . tablename($this->table_users) . " WHERE rid='{$rid}' AND from_user = :from_user ORDER BY id DESC limit 1", array(':from_user'=>$from_user));

		}
		$fmimage = $this->getpicarr($uniacid,$rid, $mygift['from_user'],1);
		load()->func('tpl');
		$tags = pdo_fetchall("SELECT id,parentid,title FROM ".tablename($this->table_tags)." WHERE rid = '{$rid}' ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');

		if (!empty($tags)) {
			$tagid = $mygift['tagid'];
			$ptag = empty($mygift['tagpid']) ? $_GPC['tagpid'] : $mygift['tagpid'];
			$tagtid = $mygift['tagtid'];
			//echo $ptag;
			$tagname = $this->gettagname($tagid,$ptag,$tagtid,$rid);


			$parent = array();
			//$children = array();

			$children = '';
			foreach ($tags as $cid => $cate) {
				$cate['name'] = $cate['title'];
				if (!empty($cate['parentid'])) {
					$children[$cate['parentid']][] = $cate;
				} else {
					$parent[$cate['id']] = $cate;
				}
			}
		}
		$pnametag = pdo_fetchall("SELECT title FROM ".tablename($this->table_pnametag)." WHERE rid = :rid ORDER BY id DESC", array( ':rid' => $rid));
		$source = pdo_fetchall("SELECT id,title FROM ".tablename($this->table_source)." WHERE rid = '{$rid}' ORDER BY displayorder ASC, id ASC ");
		$school = pdo_fetchall("SELECT id,title FROM ".tablename($this->table_school)." WHERE rid = '{$rid}' ORDER BY displayorder ASC, id ASC ");
		//整理数据进行页面显示
		$title = $rshare['sharetitle'] . '报名';
if (!empty($rshare['sharelink'])) {
	$_share['link'] = $rshare['sharelink'];
}else{
		$_share['link'] = $_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'fromuser' => $from_user));//分享URL
}
		 $_share['title'] = $this->get_share($uniacid,$rid,$from_user,$rshare['sharetitle']);
		$_share['content'] =  $this->get_share($uniacid,$rid,$from_user,$rshare['sharecontent']);
		$_share['imgUrl'] = toimage($rshare['sharephoto']);
		if (!empty($rbody)) {
			$rbody_reg = iunserializer($rbody['rbody_reg']);
		}


		$templatename = $rbasic['templates'];
		if ($templatename != 'default' && $templatename != 'stylebase') {
			require FM_CORE. 'fmmobile/tp.php';
		}
		$toye = $this->templatec($templatename,$_GPC['do']);
		include $this->template($toye);

