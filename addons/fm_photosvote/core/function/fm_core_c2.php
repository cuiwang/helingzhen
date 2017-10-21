<?php
defined('IN_IA') or exit('Access Denied');
class FmCoreC2 extends FmCoreC1 {
	public function downloadqrcodeimg($ticket, $filename) {
		//下载图片
		global $_W;
		$uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
		load() -> func('file');
		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
		$fileInfo = $this -> downloadWeixinFile($url);
		$updir = '../attachment/images/fm_photosvote_qrcode/' . $uniacid . '/' . date("Y") . '/' . date("m") . '/';
		if (!is_dir($updir)) {
			mkdirs($updir);
		}
		$filename = $updir . $filename . ".jpg";
		$this -> saveWeixinFile($filename, $fileInfo["body"]);
		return $filename;
	}
	public function downloadurl2qr($url, $filename) {
		//下载图片
		global $_W;
		require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
		$errorCorrectionLevel = "L";
		$matrixPointSize = "5";
		$filename = empty($filename) ? date("YmdHis") . '' . random(10) :$filename;

		$dfileurl = 'attachment/images/fm_photosvote_qrcode/' . $uniacid . '/' . date("Y") . '/' . date("m") . '/';
		$fileurl = '../' . $dfileurl;
		load() -> func('file');
		if (!is_dir($fileurl)) {
			mkdirs($fileurl);
		}
		$fileurl = $fileurl . '/' . $filename . '.png';

		QRcode::png($url, $fileurl, $errorCorrectionLevel, $matrixPointSize);
		return $fileurl;
	}
	public function doMobileQrcode() {
		global $_GPC,$_W;
		$rid = $_GPC['rid'];
		$uid = $_GPC['uid'];
		$tfrom_user = $_GPC['tfrom_user'];

		$user = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user AND rid = :rid", array(':from_user' => $tfrom_user,':rid' => $rid));
		$fmimage = $this->getpicarr($_W['uniacid'],$rid, $tfrom_user,1);
		if (empty($fmimage)) {
			$fmdata = array(
				"success" => -1,
				"msg" => '请上传并设置封面',
			);
			echo json_encode($fmdata);
			exit;
		}
		$ewm = $this->qrcodecreate($_GPC['code']);
		$ticket = UrlEncode($ewm['ticket']);
		$qrname = $tfrom_user . $rid.'_'.$uid;
		$ewmurl = $this->downloadurl2qr($ewm['url'],$qrname);
		if ($ewmurl) {

			if (!empty($ewm['ticket'])) {
				$rhuihua = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_huihua)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
				$qrcode = pdo_fetch("SELECT * FROM ".tablename($this->table_qrcode)." WHERE tfrom_user = :tfrom_user AND ticket = :ticket AND rid = :rid", array(':tfrom_user' => $tfrom_user,':ticket' => $ewm['ticket'],':rid' => $rid));

				$barcode = iunserializer(base64_decode($_GPC['code']));
				if (empty($qrcode)) {
					$insert = array(
						'uniacid' => $_W['uniacid'],
						'rid' => $rid,
						'tfrom_user' => $tfrom_user,
						'qrcid' => $barcode['action_info']['scene']['scene_id'],
						'scene_str' => $barcode['action_info']['scene']['scene_str'],
						'keyword' =>$rhuihua['tcommand'].$uid,
						'name' => $this->getname($rid, $tfrom_user,'8'),
						'model' => '2',
						'ticket' => $ewm['ticket'],
						'url' => $ewm['url'],
						'imgurl' => $ewmurl,
						'expire' => $ewm['expire_seconds'],
						'createtime' => TIMESTAMP,
						'status' => '1',
						'type' => 'scene',
					);
					pdo_insert($this->table_qrcode, $insert);
				}else{
					$insert = array(
						'uniacid' => $_W['uniacid'],
						'qrcid' => $barcode['action_info']['scene']['scene_id'],
						'scene_str' => $barcode['action_info']['scene']['scene_str'],
						'keyword' =>$rhuihua['tcommand'].$uid,
						'name' => $this->getname($rid, $tfrom_user,'8'),
						'model' => '2',
						'tfrom_user' => $tfrom_user,
						'ticket' => $ewm['ticket'],
						'url' => $ewm['url'],
						'imgurl' => $ewmurl,
						'expire' => $ewm['expire_seconds'],
						'createtime' => TIMESTAMP,
					);
					pdo_update($this->table_qrcode, $insert, array('rid' => $rid, 'tfrom_user' => $tfrom_user, 'ticket' => $ewm['ticket']));
				}
			}

		//print_r($ewmurl);

			$fmimage = $this->getpicarr($_W['uniacid'],$rid, $tfrom_user,1);
			load() -> func('file');

			$cfg_markpicurl = IA_ROOT . str_replace("..", "", $ewmurl);
			$cfg_markpicurl = resize($cfg_markpicurl,$cfg_markpicurl,'',200,200,100);
			$cfg_markpicurl = IA_ROOT . str_replace("..", "", $ewmurl);
			//print_r($cfg_markpicurl);
			$groundimage = IA_ROOT . str_replace("..", "", $fmimage['imgpath']);
			//print_r($cfg_markpicurl);
			//print_r($groundimage);
			$filename = $fmimage['photoname'] . ".jpg";
			$save_dir = '../addons/fm_photosvote/haibao/' . $rid . '/' . date("Y") . '/' . date("m") . '/';
			if (!is_dir($save_dir)) {
				mkdirs($save_dir);
			}
			$rdisplay = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_display)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			if (!empty($rdisplay['qrset'])) {
				$qrset = iunserializer($rdisplay['qrset']);
			}
			$save_dir = $save_dir.$filename;
			$cfg_markminwidth = '200';
			$cfg_markminheight = '200';
			$cfg_markwhere = empty($qrset['qr_where']) ? "7" : $qrset['qr_where'];
			$cfg_markwheret = empty($qrset['font_where']) ? "9" : $qrset['font_where'];
			$n = "\n";
			$o = '我是：'.$this->getname($rid, $tfrom_user,'8');
			$a = '编号：'.$user['uid'];
			$b = cutstr($user['photoname'], '6');
			$cfg_marktext = $o.$n.$a.$n.$b.$n;
			$cfg_marksize = empty($qrset['font_size']) ? "20" : $qrset['font_size'];
			$cfg_fontfamily = empty($qrset['font_path']) ? FM_STATIC_MOBILE . 'fonts/msyh.ttf' : FM_STATIC_MOBILE . 'fonts/' . $qrset['font_path'];
			$cfg_markcolor = empty($qrset['font_color']) ? '#0000FF' : $qrset['font_color'];
			$cfg_marktype = '0';
			$cfg_marktypet = '0';

			$this->WaterPoster($save_dir,$groundimage, $cfg_markpicurl, $cfg_markminwidth, $cfg_markminheight, $cfg_markwhere,$cfg_markwheret,$cfg_marktext, $cfg_fontfamily, $cfg_marksize, $cfg_markcolor, $cfg_marktype, $cfg_marktypet);

			pdo_update($this->table_users, array('ewm' => $ewmurl, 'haibao' => $save_dir), array('rid'=>$rid, 'from_user' => $tfrom_user));
			$fmdata = array(
				"success" => 1,
				"linkurl" => tomedia($save_dir).'?v='.time(),
				"msg" => '生成成功',
			);
		}else {
			$fmdata = array(
				"success" => -1,
				"msg" => '生成失败',
			);
		}
		echo json_encode($fmdata);
		exit;
	}
	public function doMobileewmimagehc() {
		global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$tfrom_user=$_GPC['tfrom_user'];
		$user = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $tfrom_user,':rid' => $rid));
		//$fmimage = $this->getpicarr($_W['uniacid'],$rid, $tfrom_user,1);

	}
	public function doMobileDhgift() {
		global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$from_user = $_GPC['from_user'];
		$id = $_GPC['id'];
		$jifen = $_GPC['jifen'];
		$userjf = $this->cxjifen($rid, $from_user);
		if ($jifen > $userjf) {
			$data = array(
				'success' => -1,
				'msg' => '您当前没有足够的积分兑换该礼物'
			);
			echo json_encode($data);
			exit ;
		}
		$item = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ', array(':id' => $id));
		if (empty($item)) {
			$data = array(
				'success' => -1,
				'msg' => '没有找到您要兑换的礼物，请兑换其他礼物'
			);
			echo json_encode($data);
			exit ;
		}
		$usergift = pdo_fetch("SELECT * FROM " . tablename($this -> table_user_gift) . ' WHERE giftid = :giftid AND from_user = :from_user AND rid = :rid AND status = 1 ', array(':giftid' => $id,':from_user' => $from_user,':rid' => $rid));
		if (empty($usergift)) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'rid' => $rid,
				'giftid' => $id,
				'giftnum' => 1,
				'status' => 1,
				'from_user' => $from_user,
				'lasttime' => time(),
				'createtime' => time(),
			);
			pdo_insert($this->table_user_gift, $data);
		}else{
			pdo_update($this->table_user_gift, array('giftnum' => $usergift['giftnum'] + 1, 'lasttime' => time()), array('rid' => $rid,'giftid' => $id, 'from_user'=>$from_user));//
		}
		pdo_update($this->table_jifen_gift, array('dhnum' => $item['dhnum'] + 1), array('rid' => $rid,'id' => $id));
		$this->jsjifen($rid, $from_user, $jifen,$item['gifttitle']);
		$data = array(
			'success' => 1,
			'msg' => '兑换成功！'
		);
		echo json_encode($data);
		exit ;
	}
	public function doMobilePagedata() {
		global $_GPC;
		require_once FM_CORE.'fmmobile/pagedata.php';
	}
	public function doMobilePagedatab() {
		global $_GPC;
		require_once FM_CORE.'fmmobile/pagedatab.php';
	}
	public function doMobilePay() {
		global $_GPC,$_W;
		require_once FM_PHOTOSVOTE_PAYMENT."wechat/pay.php";

	}
	public function doWebdownload(){
		require_once FM_CORE.'fmweb/download.php';
	}
	public function doWebtpdownload(){
		require_once FM_CORE.'fmweb/tpdownload.php';
	}
	public function doWebdownloadph(){
		require_once FM_CORE.'fmweb/downloadph.php';
	}
	public function doWebSettuijian() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$data = intval($_GPC['data']);

		$type = $_GPC['type'];
		if (in_array($type, array('tuijian'))) {
			$data = ($data==1?'0':'1');
			pdo_update($this->table_users, array('istuijian' => $data), array("id" => $id));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('limitsd'))) {

			pdo_update($this->table_users, array('limitsd' => $data), array("id" => $id));
			die(json_encode(array("result" => 1, "data" => $data)));
		}

		if (in_array($type, array('menu'))) {
			pdo_update($this->table_reply, array('menuid' => $data), array("rid" => $id));
			$menu = pdo_fetch('select menuname from ' . tablename($this->table_designer_menu) . ' where id=:id', array(':id' => $data));
			die(json_encode(array("result" => 1, "data" => $data, "menuname" => $menu['menuname'])));
		}

		if (in_array($type, array('qfstatus'))) {
			$data = ($data==1?'0':'1');
			pdo_update($this->table_qunfa, array('status' => $data), array("id" => $id));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('shenhe'))) {
			$data = ($data==1?'0':'1');
			pdo_update($this->table_bbsreply, array('status' => $data), array("id" => $id));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}
	public function doWebShuapiao() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$shuapiao = intval($_GPC['shuapiao']);
		$data = $_GPC['data'];
		$ip = $_GPC['ip'];
		$ua = $_GPC['ua'];
		$type = $_GPC['type'];
		if (in_array($type, array('shuapiao'))) {
			if ($shuapiao == 1) {
				pdo_delete($this->table_shuapiao, array('rid' => $rid, 'from_user'=>$data));
				pdo_delete($this->table_shuapiao, array('rid' => $rid, 'ip'=>$ip));
				pdo_delete($this->table_shuapiao, array('rid' => $rid, 'ua'=>$ua));
				pdo_update($this->table_log, array('shuapiao' => 0),array( 'from_user'=>$data, 'rid'=>$rid));
				pdo_update($this->table_log, array('shuapiao' => 0),array( 'ip'=>$ip, 'rid'=>$rid));
				pdo_update($this->table_log, array('shuapiao' => 0),array( 'mobile_info'=>$ua, 'rid'=>$rid));
			}else{
				pdo_insert($this->table_shuapiao, array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'from_user'=>$data, 'ip'=>$ip,'ua'=>$ua, 'createtime' => time()));
				pdo_update($this->table_log, array('shuapiao' => 1),array( 'from_user'=>$data, 'rid'=>$rid));
				pdo_update($this->table_log, array('shuapiao' => 1),array( 'ip'=>$ip, 'rid'=>$rid));
				pdo_update($this->table_log, array('shuapiao' => 1),array( 'mobile_info'=>$ua, 'rid'=>$rid));
			}
			die(json_encode(array("result" => 1, "data" => 1)));
		}
		die(json_encode(array("result" => 0)));
	}

}
