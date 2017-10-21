<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
$item_per_page = intval($_GPC['pagesnum']);
$page_number = intval($_GPC['page']);
if (!is_numeric($page_number)) {
	header('HTTP/1.1 500 Invalid page number!');
	exit();
}
$position = ($page_number * $item_per_page);

$m = $position + 2;

$where = '';
//$where .= " AND status = '1'";

$comments = pdo_fetchall('SELECT * FROM ' . tablename($this -> table_bbsreply) . ' WHERE rid = :rid AND tfrom_user = :tfrom_user AND is_del = 0 AND status = 1' . $where . ' ORDER BY `createtime` ASC LIMIT ' . $position . ',' . $item_per_page, array(':rid' => $rid, ':tfrom_user' => $tfrom_user));

//output results from database
if (!empty($comments)) {

	foreach ($comments as $mid => $row) {
		if ($row['realname']) {
			$usernames = cutstr($row['realname'], '10');
		} elseif ($row['nickname']) {
			$usernames = cutstr($row['nickname'], '10');
		} else {
			$usernames = cutstr($row['from_user'], '10');
		}
		$user = pdo_fetch('SELECT uid FROM ' . tablename($this -> table_users) . ' WHERE rid = :rid AND from_user = :from_user LIMIT 1', array(':rid' => $rid, ':from_user' => $row['from_user']));
		if (!empty($user)) {
			$turl = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('tuserphotos', array('rid' => $rid, 'tfrom_user' => $row['from_user']));
		} else {
			$turl = 'javascript::;';
		}
		$mid = $mid + $m;
		$level = $this -> fmvipleavel($rid, $uniacid, $row['from_user']);
		if (empty($level)) {
			$level = 1;
		}
		if ($row['zan']) {
			$content = '赞了一个 ~~~';
		} else {
			$content = $this -> emotion($row['content']);
		}
		if ($tfrom_user == $row['from_user']) {
			$display = 'inline-block';
		} else {
			$display = 'none';
		}

		$result = $result . '
			<li itid="' . $row['from_user'] . '" fn="' . $row['uid'] . '" class="list_item post_list_item default_feedback " is_inner_floor="0">
				<div class="list_item_wrapper">
					<div class="list_main">
						<div class="list_item_top clearfix">
							<div class="list_item_top_avatar">
								<a href="' . $turl . '" target="_blank"><span><img src="' . $this -> getphotos($row['photo'], $row['avatar'], $rbasic['picture']) . '" alt="头像" width="36" height="36" class="user_img"></span></a>
							</div>
							<div class="list_item_top_name">
								<span class="user_name"><a href="' . $turl . '" class="user_name" target="_blank">' . $usernames . '</a></span><span class="level level_' . $level . '">' . $level . '</span><span class="j_floor_lz floor_lz_icon" style="display:' . $display . '">楼主</span><br>
								<span class="list_item_floor_num">' . $mid . '楼</span><span class="list_item_time">' . date('Y年m月d日', $row['createtime']) . '</span>
							</div>
						</div>
						<div class="content" lz="0" id="content_smile_display">' . $content . '</div>
						<div class="fr_list j_floor_panel" style="display: none;" data-list-count="0">
						</div>
					</div>
				</div></li>';
	}
}
print_r($result);
