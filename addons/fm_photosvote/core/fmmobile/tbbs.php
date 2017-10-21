<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

		$rb = array();

		//评论
		$bbsreply = pdo_fetchall("SELECT * FROM ".tablename($this->table_bbsreply)." WHERE tfrom_user = :tfrom_user AND rid = :rid AND is_del = 0 AND status = 1 order by `id` desc ",  array(':tfrom_user' => $tfrom_user,':rid' => $rid));
		if (empty($bbsreply)) {
			//预设
			if ($rvote['tmyushe'] == 1) {
				//预设
				$ybbsreply = pdo_fetchall("SELECT * FROM ".tablename($this->table_bbsreply)." WHERE rid = :rid AND status = '9' order by `id` desc ",  array(':rid' => $rid));
				if (empty($ybbsreply)) {
					$rb[] .= '为Ta做第一个留下弹幕的人吧';
				}else{
					foreach ($ybbsreply as $r) {
						$rb[] .= $r['nickname'] . ' : ' . cutstr($r['content'], '15');
					}
				}

			}
		} else {

			if ($rvote['tmyushe'] == 1) {
				//预设
				$ybbsreply = pdo_fetchall("SELECT * FROM ".tablename($this->table_bbsreply)." WHERE rid = :rid AND status = '9' order by `id` desc ",  array(':rid' => $rid));
				if (!empty($ybbsreply)) {

					foreach ($ybbsreply as $r) {
						$rb[] .= $r['nickname'] . ' : ' . cutstr($r['content'], '15');
					}
				}

			}
			foreach ($bbsreply as $r) {
				$name = empty($r['nickname']) ? $this->getname($rid, $r['from_user']) : $r['nickname'];
				$avatar = $this->getname($rid, $r['from_user'],'', 'avatar') ;
				$rb[] .= '<img src="'.tomedia($avatar).'" width="30" height="30" style="border-radius:30px;">  ' . $name . ' : ' . cutstr($r['content'], '15');
			}
		}

		echo json_encode($rb);
		exit();