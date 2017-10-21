<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 * @function 输出瀑布流队列
 */
defined('IN_IA') or exit('Access Denied');
		$rid = $_GPC['rid'];
		$item_per_page = empty($_GPC['indextpxz']) ? 10 : $_GPC['indextpxz'];
		$page_number = max(1, intval($_GPC['pagesnum']));
		if(!is_numeric($page_number)){
   			header('HTTP/1.1 500 Invalid page number!');
    		exit();
		}
      	//print_r($_GPC['indextpxz']);
		$position = ($page_number-1) * $item_per_page;
		$where = '';
		if (!empty($_GPC['keyword'])) {
				$keyword = $_GPC['keyword'];
				if (is_numeric($keyword))
					$where .= " AND uid = '".$keyword."'";
				else
					$where .= " AND (nickname LIKE '%{$keyword}%' OR realname LIKE '%{$keyword}%' )";

		}

		$where .= " AND status = '1'";


		$tagid = $_GPC['tagid'];
		$tagpid = $_GPC['tagpid'];
		$tagtid = $_GPC['tagtid'];

		if (!empty($tagid)) {
			$where .= " AND tagid = '".$tagid."'";
		}
		if (!empty($tagpid)) {
			$where .= " AND tagpid = '".$tagpid."'";
		}
		if (!empty($tagtid)) {
			$where .= " AND tagtid = '".$tagtid."'";
		}

		if ($_GPC['indexorder'] == '1') {
			$where .= " ORDER BY `istuijian` DESC, `createtime` DESC";
		}elseif ($_GPC['indexorder'] == '11') {
			$where .= " ORDER BY `istuijian` DESC, `createtime` ASC";
		}elseif ($_GPC['indexorder'] == '2') {
			$where .= " ORDER BY `istuijian` DESC, `uid` DESC, `id` DESC";
		}elseif ($_GPC['indexorder'] == '22') {
			$where .= " ORDER BY `istuijian` DESC, `uid` ASC, `id` ASC";
		}elseif ($_GPC['indexorder'] == '3') {
			$where .= " ORDER BY `istuijian` DESC, `photosnum` + `xnphotosnum` DESC";
		}elseif ($_GPC['indexorder'] == '33') {
			$where .= " ORDER BY `istuijian` DESC, `photosnum` + `xnphotosnum` ASC";
		}elseif ($_GPC['indexorder'] == '4') {
			$where .= " ORDER BY `istuijian` DESC, `hits` + `xnhits` DESC";
		}elseif ($_GPC['indexorder'] == '44') {
			$where .= " ORDER BY `istuijian` DESC, `hits` + `xnhits` ASC";
		}elseif ($_GPC['indexorder'] == '5') {
			$where .= " ORDER BY `istuijian` DESC, `vedio` DESC, `music` DESC, `id` DESC";
		}else {
			$where .= " ORDER BY `istuijian` DESC, `id` DESC";
		}


		$userlist = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE rid = :rid '.$where.'  LIMIT ' . $position . ',' . $item_per_page, array(':rid' => $rid) );
		foreach ($userlist as $key => $row) {
			$fmimage = $this->getpicarr($uniacid,$rid, $row['from_user'],1);
			$userlist[$key]['avatar'] = $this->getphotos($fmimage['photos'], $row['avatar'], FM_STATIC_MOBILE . 'public/images/nofoundpic.gif');
			$userlist[$key]['username'] .= $this->getname($rid, $row['from_user'],'8');
			$userlist[$key]['piao'] .= ($_GPC['open_vote_count'] == 1) ? $row['photosnum'] + $row['xnphotosnum'] : '****';
			$userlist[$key]['level'] .= $this->fmvipleavel($rid, $uniacid, $row['from_user']);
			$userlist[$key]['tpan'] .= '投票';
			//$userlist[$key]['photoname'] .= '投票';
			$userlist[$key]['conmenturl'] .= $this->createMobileUrl('comment', array('rid' => $rid, 'tfrom_user'=> $row['from_user']));
			$userlist[$key]['giftlinkurl'] .= $this->createMobileUrl('giftvote', array('rid' => $rid, 'tfrom_user'=> $row['from_user']));
			$userlist[$key]['pnum'] .= $this->getphotosnum($rid, $uniacid,$row['from_user']);
			$tagname = $this -> gettagname($row['tagid'], $row['tagpid'], $row['tagtid'], $rid);
			$userlist[$key]['tagname'] .= empty($tagname) ? '未选择' : $tagname ;

			$userlist[$key]['commentnum'] .= $this->getcommentnum($rid, $uniacid,$row['from_user']);
			if ($_GPC['is_open_jifen'] && $_GPC['giftvote']) {
				$giftanurl = $this->createMobileUrl('giftvote', array('rid'=> $rid, 'tfrom_user'=>$row['from_user']));
				$giftan = '送礼物';
			}else{
				$giftanurl = $this->createMobileUrl('member', array('rid'=> $rid));
				$giftan = '个人中心';
			}
			$userlist[$key]['gifturl'] .= '<a href="'.$giftanurl.'" class="btn" style="width:100px;">♥ '.$giftan.'</a>';
			$userlist[$key]['time'] .= Sec2Time($row['musictime']);
			$voice = empty($row['voice']) ? tomedia($row['music']) : tomedia($row['voice']);
			//<video controls="" autoplay="" name="media"><source src="'.$voice.'" type="audio/mpeg"></video>
			//$userlist[$key]['voice'] .= '<div class="sort"><video id="voice'.$row['uid'].'" controls="" name="media" style="width: 100%;height: 35px;"><source src="'.$voice.'" type="audio/mpeg"></video> 2分15秒</div>';
			$mphotosnum = $this->getphotosnum($rid, $uniacid,$row['from_user']);
			if (!empty($mphotosnum)) {
				$purl = $this->createMobileUrl('tuserphotos', array('rid'=> $rid, 'tfrom_user' => $row['from_user']));
				$gq = '<a href="'.$purl.'" ><span class="rightmedia_pic"><strong class="bnum">查看高清图 '.$mphotosnum.' 张</strong></span></a>';
			}
			if ($row['vedio'] || $row['youkuurl']) {
				$rightmedia = '<span class="rightmedia"><img src="'.FM_STATIC_MOBILE.'public/images/video.png?v=3.0" style="width:20px;"></span>';
				if ($row['vedio']) {
					$userlist[$key]['rightmedia_v'] = $gq . '<video id="videocon" controls width="100%" height="200" poster="'.$userlist[$key]['avatar'].'" webkit-playsinline><source src="'.tomedia($row['vedio']).'" type="video/mp4" /><p class="vjs-no-js">你的浏览器不支持该视频</a></p></video>';
				}elseif ($row['youkuurl']) {
					if (substr($row['youkuurl'],0,7) == 'http://') {
						$ykurl = $row['youkuurl'];
					}else{
						$ykurl = 'http://player.youku.com/embed/' . $row['youkuurl'];
					}
					$userlist[$key]['rightmedia_v'] = $gq . '<iframe  src="'.$ykurl.'" frameborder="0" allowfullscreen="" style="width:100%;min-height: 200px;"></iframe>';
				}
				$userlist[$key]['rightmedia_s'] .= 'vedio';
			}elseif ($row['voice'] || $row['music']) {

				$rightmedia = '<span class="rightmedia"><img src="'.FM_STATIC_MOBILE.'public/images/music.png?v=3.0" style="width:20px;"></span>';
				if ($row['voice']) {
					$userlist[$key]['rightmedia_v'] = $gq . '<img style="width:100%" src="'.$userlist[$key]['avatar'].'">';
				}elseif ($row['music']) {
					$userlist[$key]['rightmedia_v'] = $gq . '<img style="width:100%" src="'.$userlist[$key]['avatar'].'">';
				}
				$userlist[$key]['rightmedia_s'] .= 'music';
			}else{
				$rightmedia = '<span class="rightmedia"><img src="'.FM_STATIC_MOBILE.'public/images/tp.png?v=3.0" style="width:20px;"></span>';
				$userlist[$key]['rightmedia_v'] = $gq.'<img id="bigimg'.$row['uid'].'" style="width:100%" src="'.$userlist[$key]['avatar'].'">';
				$userlist[$key]['rightmedia_s'] .= 'images';
			}
			$userlist[$key]['rightmedia'] .= $rightmedia;
		}

		if (!empty($userlist)) {
			//赞助商
			if ($_GPC['isindex'] == 1) {
				$advs = pdo_fetchall('SELECT advname, thumb, link FROM '.tablename($this->table_advs).' WHERE rid = :rid AND enabled = 1 AND ismiaoxian = 0 AND issuiji = 1  ', array(':rid' => $rid) );
				if (!empty($advs)) {
					$adv  = array_rand($advs);
					$advarr = array();
					$advarr['avatar'] .= toimage($advs[$adv]['thumb']);
					$advarr['username'] .= cutstr($advs[$adv]['advname'], '10');
					$advarr['link'] .= $advs[$adv]['link'];
					$advarr['type'] .= 'adv';
					$userlist[] = $advarr;
				}
			}
		}

    	echo json_encode($userlist);
		exit;