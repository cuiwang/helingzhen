<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

		$starttime=mktime(0,0,0);//当天：00：00：00
		$endtime = mktime(23,59,59);//当天：23：59：59
		$times = '';
		$times .= ' AND createtime >=' .$starttime;
		$times .= ' AND createtime <=' .$endtime;

			//查询自己是否参与活动
		if(!empty($from_user)) {
			$mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
		}

		//查询是否参与活动
		if(!empty($tfrom_user)) {
		    $user = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $tfrom_user,':rid' => $rid));
		    if (!empty($user)) {
			   // pdo_update($this->table_users, array('hits' => $user['hits']+1), array('rid' => $rid, 'from_user' => $tfrom_user));
		    }else{
				$url = $_W['siteroot'] .'app/'.$this->createMobileUrl('photosvote', array('rid' => $rid));
				$fmdata = array(
							"success" => 3,
							"linkurl" => $url,
							"msg" => '！',
						);
						echo json_encode($fmdata);
						exit();

				//header("location:$url");
				//exit;
			}

		}

		$bbsreply = pdo_fetchall("SELECT * FROM ".tablename($this->table_bbsreply)." WHERE tfrom_user = :tfrom_user AND rid = :rid AND is_del = 0 order by `id` desc ",  array(':tfrom_user' => $tfrom_user,':rid' => $rid));

		if ($rvote['tmreply'] == 1) {//开启评论
			if ($_GPC['tmreply'] == 1) {//开启评论
				$tid = $user['uid'];
				$content = $_GPC['msgstr'];
				//$reply_id = $user['uid'];
				//print_r($rvote);
				if ($rvote['bbsreply_status']) {
					$status = '0';
				}else{
					$status = '1';
				}
				$rdata = array(
					'uniacid' => $uniacid,
					'rid' => $rid,
					'avatar' => $avatar,
					'nickname' => $nickname,
					'tfrom_user' => $tfrom_user,//帖子作者的openid
					'tid' => $tid,//帖子的ID
					'from_user' => $from_user,//回复评论帖子的openid
					//'reply_id' => $reply_id,//回复评论帖子的ID
					//'rfrom_user' => $rfrom_user,//被回复的评论的作者的openid
					//'to_reply_id' => $to_reply_id,//回复评论的id
					'content' => $content,//评论回复内容
					'status' => $status,//绝对楼层
					'ip' => getip(),
					'createtime' => time(),

				);
				$rdata['iparr'] = getiparr($rdata['ip']);
				pdo_insert($this->table_bbsreply, $rdata);
				$reply_id = pdo_insertid();
				pdo_update($this->table_bbsreply, array('storey' => $reply_id), array('rid' => $rid, 'id' => $reply_id ));
				if ($rhuihua['msgtemplate']) {
					$this->sendMobileMsgtx($from_user, $rid, $uniacid);
				}
			}
		}



		if ($rvote['isbbsreply'] == 1) {//开启评论
			if ($_GPC['isbbsreply'] == 1) {
				if (empty($tfrom_user)) {
					$msg = '被投票人不存在！';
					//message($msg,$turl,'error');
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit();
				}
				if (empty($_GPC['content'])) {
					$msg = '你还没有评论哦';
					//message($msg,$turl,'error');
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit();
				}

				$tid = $user['uid'];
				$content = $_GPC['content'];
				//$reply_id = $user['uid'];
				if ($rvote['bbsreply_status']) {
					$status = '0';
				}else{
					$status = '1';
				}
				$rdata = array(
					'uniacid' => $uniacid,
					'rid' => $rid,
					'avatar' => $avatar,
					'nickname' => $nickname,
					'tfrom_user' => $tfrom_user,//帖子作者的openid
					'tid' => $tid,//帖子的ID
					'from_user' => $from_user,//回复评论帖子的openid
					//'reply_id' => $reply_id,//回复评论帖子的ID
					//'rfrom_user' => $rfrom_user,//被回复的评论的作者的openid
					//'to_reply_id' => $to_reply_id,//回复评论的id
					'content' => htmlspecialchars_decode($content),//评论回复内容
					//'storey' => $storey,//绝对楼层
					'status' => $status,
					'ip' => getip(),
					'createtime' => time(),

				);
				$rdata['iparr'] = getiparr($rdata['ip']);
				pdo_insert($this->table_bbsreply, $rdata);
				$reply_id = pdo_insertid();
				pdo_update($this->table_bbsreply, array('storey' => $reply_id), array('rid' => $rid, 'id' => $reply_id ));
				if ($rhuihua['msgtemplate']) {
					$this->sendMobileMsgtx($from_user, $rid, $uniacid);
				}
				$msg = '评论成功！';
				//message($msg,$turl,'error');
				$fmdata = array(
					"success" => 1,
					"msg" => $msg,
				);
				echo json_encode($fmdata);
				exit();
				//message('评论成功！', referer(), 'success');

			}
		}
