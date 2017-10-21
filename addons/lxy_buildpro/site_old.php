<?php
/**
 * 微房产
 * QQ：792454007
 *
 * @author 大路货
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Lxy_buildproModuleSite extends WeModuleSite {
	public $headtable='lxy_buildpro_head';
	public $subtable='lxy_buildpro_sub';
	public $housetalbe='lxy_buildpro_house';
	public $felltable='lxy_buildpro_fell';
	public $fellrecordtable='lxy_buildpro_fell_record';
	public $experttable='lxy_buildpro_expert_comment';
	public $albumtable='lxy_buildpro_album';
	public $fullviewtable='lxy_buildpro_full_view';
	public $billtable='lxy_buildpro_bill';
	public $hycard_type=1;
	
	public $verifytable='lxy_module_verify';
	
	/*public function __construct(){
		//ver1.1
		global $_W;
		$modulename='lxybuildpro';
		$remoteserver='http://www.nalian360.com/cmwx/';
		$localhost=base64_encode(authcode($_W['siteroot'], 'ENCODE',$modulename)) ;
		$localhostmd5=md5($_W['siteroot']);
		$info = pdo_fetch("SELECT * FROM " . tablename($this->verifytable) . " WHERE modulename='{$modulename}' and verifyu='{$localhostmd5}'" );
		if(empty($info['verify']))
		{
			//在线验证
	
			$url="{$remoteserver}verify.php?p1={$localhost}&p2={$modulename}";
			$rsp = ihttp_get($url);
			$dat=$rsp['content'];
			if(!empty($dat))
			{
				//获取接口情况
				$ret=explode('|',$dat);
				if(is_array($ret))
				{
					if($ret[0]=="ok")
					{
	
						$hasone=pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->verifytable) . " WHERE modulename='{$modulename}' and verifyu='{$localhostmd5}'" );
						if($hasone>0)
						{
							pdo_update($this->verifytable,array('verify'=>$ret[1]),array('modulename'=>$modulename,'verfiyu'=>$localhostmd5));
						}
						else
						{
							pdo_insert($this->verifytable,array('verify'=>$ret[1],'modulename'=>$modulename,'verifyu'=>$localhostmd5));
						}
						message('大路货恭喜您授权成功！');
					}
	
				}
			}
		}
		else
		{
			//本地验证
			$key=md5($_W['siteroot'].'~!#'.$modulename);
			if($key==$info['verify'])//验证成功
			{
				return ;
			}
			else//验证失败
			{
				die('您的版本尚未授权,请联系大路货QQ:792454007授权认证！');
			}
	
		}
		die('您的版本尚未授权,请联系大路货QQ:792454007授权认证！');
	}
	
	*/

	public function getHomeTiles() {
		global $_W;
		$urls = array();
		$weid=$_W['weid'];
		$fromuser=$_W['fans']['from_user'];
		
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->headtable)." WHERE weid=:weid ", array(':weid'=>$weid));
		if (!empty($list)) {
			foreach ($list as $row) {
				$urls[]=array('title'=>$row['jjname'],'url'=>$this->createMobileurl('buildinfo',array('hid'=>$row['hid'])));
				$urls[]=array('title'=>$row['xcname'],'url'=>$this->createMobileurl('viewbdalbum',array('hid'=>$row['hid'])));
				$urls[]=array('title'=>$row['hxname'],'url'=>$this->createMobileurl('huxing',array('hid'=>$row['hid'])));
				$urls[]=array('title'=>$row['yxname'],'url'=>$this->createMobileurl('review',array('hid'=>$row['hid'])));
				$urls[]=array('title'=>$row['xwname'],'url'=>$row['xwurl']);
				$urls[]=array('title'=>$row['yyname'],'url'=>$row['yyurl']);
				$hyurl="";
				if(empty($row['hyurl']))
				{
					$member = pdo_fetch("SELECT id, cardsn FROM ".tablename('card_members')." WHERE from_user = :from_user", array(':from_user' => $fromuser));
					if($this->hycard_type==0)
					{
						$hyurl= !empty($member) ? create_url('mobile/channel', array('name' => 'home', 'weid' => $_W['weid'])) : create_url('mobile/module/card', array('name' => 'member', 'weid' => $_W['weid']));
					}
					else
					{
						//use icard
						$hyurl= $_W['siteroot'] . "mobile.php?act=module&name=icard&do=wapindex&weid={$weid}&from_user=".base64_encode(authcode($fromuser, 'ENCODE'));
					}					}
				else
				{
					$hyurl=str_replace('{fromuser}', $fromuser, $row['hyurl']);
				}
				
				$urls[]=array('title'=>$row['hyname'],'url'=>$hyurl);
				$urls[]=array('title'=>$row['lxname'],'url'=>"tel:{$row['tel']}");

			}
		}
		return $urls;
	}
	//简介
	public function doWebAdd() {
		global $_GPC, $_W;
		$hid = intval($_GPC['hid']);
		if (!empty($hid)) {
			$item = pdo_fetch("SELECT * FROM ".tablename($this->headtable)." WHERE hid = :hid", array(':hid' => $hid));
			if (empty($item)) {
				message('抱歉，楼盘不存在或是已经删除！', '', 'error');
			}
		}
		
		
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入楼盘标题！');
			}
			$data = array(
					'weid' => $_W['weid'],
					'title' => $_GPC['title'],
					'jianjie' => htmlspecialchars_decode($_GPC['jianjie']),
					'xiangmu' => htmlspecialchars_decode($_GPC['xiangmu']),
					'jiaotong' =>htmlspecialchars_decode($_GPC['jiaotong']),
					'tel' => $_GPC['tel'],
					'addr' => $_GPC['addr'],
					'jw_addr' => $_GPC['jw_addr'],
					'video' =>  $_GPC['video'],					
					'lng' => $_GPC['lng'],
					'lat' => $_GPC['lat'],					
					'province' => $_GPC['resideprovince'],
					'city' => $_GPC['residecity'],
					'dist' => $_GPC['residedist'],							
					'jjname' => $_GPC['jjname'],
					'xcname' => $_GPC['xcname'],
					'hxname' => $_GPC['hxname'],
					'yxname' => $_GPC['yxname'],
					'xwname' => $_GPC['xwname'],
					'yyname' => $_GPC['yyname'],
					'hyname' => $_GPC['hyname'],
					'lxname' => $_GPC['lxname'],		
					'xwurl' => $_GPC['xwurl'],
					'yyurl' => $_GPC['yyurl'],
					'hyurl' => $_GPC['hyurl'],
					
					'createtime' => TIMESTAMP,
			);
			//上传图片
			if (!empty($_FILES['pic']['tmp_name'])) {
				file_delete($_GPC['pic_old']);
				$upload = file_upload($_FILES['pic']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic'] = $upload['path'];				
			}
			
			if (!empty($_FILES['headpic']['tmp_name'])) {
				file_delete($_GPC['headpic_old']);
				$upload = file_upload($_FILES['headpic']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['headpic'] = $upload['path'];
			}
			
			//上传图片
			if (!empty($_FILES['apartpic']['tmp_name'])) {
				file_delete($_GPC['apartpic_old']);
				$upload = file_upload($_FILES['apartpic']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['apartpic'] = $upload['path'];
			}
			

			if (empty($hid))
			 {
				pdo_insert($this->headtable, $data);
			} else {
				unset($data['createtime']);
				pdo_update($this->headtable, $data, array('hid' => $hid));
			}
			message('楼盘信息更新成功！', $this->createWebUrl('buildlists', array()), 'success');
				
		}
		load()->func('tpl');
		include $this->template('add');
	}
	
	public function doWebBuildlists() {
		global $_W,$_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->headtable)." WHERE weid = '{$_W['weid']}' $condition ORDER BY hid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->headtable) . " WHERE weid = '{$_W['weid']}' $condition");
		$pager = pagination($total, $pindex, $psize);
		load()->func('tpl');
		include $this->template('buildlists');
	}
	
	public function doWebBuildsub()
	{
		global $_W,$_GPC;
		$hid = intval($_GPC['hid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		
		$sql="SELECT * FROM ".tablename($this->subtable)." WHERE hid='{$hid}'  and weid = '{$_W['weid']}' $condition ORDER BY sort  DESC LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list = pdo_fetchall($sql);

		$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->subtable) . " WHERE hid = '{$hid}' and weid = '{$_W['weid']}'  $condition");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('buildsub');
	}
	
	public function doWebBuildsubadd () {
		global $_GPC, $_W;
		$sid = intval($_GPC['sid']);
		$hid = intval($_GPC['hid']);
		
		if(empty($hid))
		{
		 message('找不到楼盘信息！','','error');
		}

		if(!empty($sid))
		{
			$item = pdo_fetch("SELECT * FROM ".tablename($this->subtable)." WHERE sid = :sid", array(':sid' => $sid));
		}
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入子楼盘名称！');
			}
			$data = array(
					'hid' => $hid,
					'weid'=>$_W['weid'],
					'title' => $_GPC['title'],
					'sort' => $_GPC['sort'],
					'jianjie' => $_GPC['jianjie'],
			);
			if (empty($sid)) {
				pdo_insert($this->subtable, $data);
			} else {
				unset($data['createtime']);
				pdo_update($this->subtable, $data, array('sid' => $sid));
			}
			message('子楼盘信息更新成功！', $this->createWebUrl('buildsubadd', array('sid'=>$sid,'hid' =>$hid )), 'success');
	
		}
		include $this->template('buildsubadd');
	}
	
	public function doWebHouseadd () {
		global $_GPC, $_W;
		$hsid = intval($_GPC['hsid']);
		$hid = intval($_GPC['hid']);
	
		load()->func('tpl');
		if(empty($hid))
		{
			message('抱歉楼盘不存在或者已经删除！','','error');
		}
	
		if(!empty($hsid))
		{
			$item = pdo_fetch("SELECT * FROM ".tablename($this->housetalbe)." WHERE hsid = :hsid and weid=:weid", array(':hsid' => $hsid,':weid'=>$_W['weid']));
			$piclists = unserialize($item['pic']);
		}
		
		$buildsublist = pdo_fetchall("SELECT sid,title FROM ".tablename($this->subtable)." WHERE hid = :hid and weid=:weid order by sort desc", array(':hid' => $hid,':weid'=>$_W['weid']));
		

		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入子楼盘名称！');
			}
			$data = array(
					'hid' => $hid,
					'weid'=>$_W['weid'],
					'title' => $_GPC['title'],
					'sid'=>$_GPC['sid'],
					'louceng'=>$_GPC['louceng'],
					'mianji'=> $_GPC['mianji'],
					'fang' => $_GPC['fang'],
					'ting' => $_GPC['ting'],
					'sort' => $_GPC['sort'],
					'jianjie' => htmlspecialchars_decode($_GPC['jianjie']),
					'createtime'=>time(),
			);

			if(is_array($_GPC['pic'])){
				$data['pic'] = serialize($_GPC['pic']);
			}
				

			//-o
			if (empty($hsid)) {
				pdo_insert($this->housetalbe, $data);
			} else {
				unset($data['createtime']);
				pdo_update($this->housetalbe, $data, array('hsid' => $hsid));
			}
			message('户型信息更新成功！', $this->createWebUrl('houseadd', array('hsid'=>$hsid,'hid'=>$hid)), 'success');
	
		}
		include $this->template('houseadd');
	}
	
	public function doWebHouselist() {
		global $_W,$_GPC;
		$hid=intval($_GPC['hid']);
		if(empty($hid))
		{
			message('无法找到对应楼盘!','','error');
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
	
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->housetalbe)." WHERE hid='{$hid}' and weid = '{$_W['weid']}' $condition ORDER BY sort DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->headtable) . " WHERE hid='{$hid}' and weid = '{$_W['weid']}' $condition");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('houselist');
	}
	
	
	public function doWebAlbumlist() {
		global $_W,$_GPC;
		$hid=intval($_GPC['hid']);
		if(empty($hid))
		{
			message('无法找到对应相册!','','error');
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
	
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->albumtable)." WHERE hid='{$hid}' and weid = '{$_W['weid']}' $condition ORDER BY sort DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->albumtable) . " WHERE hid='{$hid}' and weid = '{$_W['weid']}' $condition");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('albumlist');
	}
	
	public function doWebdelalbum()
	{
	
		global $_GPC,$_W;
		checklogin();
		$id=$_GPC['id'];
		$hid=$_GPC['hid'];
		$weid=$_W['weid'];
		$item=pdo_fetch('select * from '.tablename($this->albumtable)." where hid='{$hid}'and  weid='{$weid}' and id='{$id}'");
		if(empty($item))
		{
			message('抱歉，您删除的相册不存在或已经删除！');
		}
		$pic_list=json_decode($item['pic'],true);
		foreach ($pic_list as $pic )
		{
			file_delete($pic['src']);
		}
		pdo_delete($this->albumtable,array('id'=>$id));
		message('删除楼盘户型成功！',$this->createWebUrl('albumlist', array('hid'=>$hid,'id'=>$id)),'success');
		
	}
	
	public function doWebAlbumadd () {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$hid = intval($_GPC['hid']);
	
		load()->func('tpl');
		if(empty($hid))
		{
			message('抱歉楼盘不存在或者已经删除！','','error');
		}
	
		if(!empty($id))
		{
			$item = pdo_fetch("SELECT * FROM ".tablename($this->albumtable)." WHERE id = :id and weid=:weid", array(':id' => $id,':weid'=>$_W['weid']));
			$jsonpic=htmlspecialchars($item['pic']);
		}
	
	
	
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入子相册名称！');
			}
			$data = array(
					'hid' => $hid,
					'weid'=>$_W['weid'],
					'sort' => $_GPC['sort'],
					'title' => $_GPC['title'],
					'subtitle' => $_GPC['subtitle'],
					'jianjie' => $_GPC['jianjie'],
					'pic' =>htmlspecialchars_decode($_GPC['hxpiclist']),

			);
			$data['pic']=str_replace($_W['attachurl'],'',$data['pic']);
			
			//-o
			if (empty($id)) {
				pdo_insert($this->albumtable, $data);
			} else {
				pdo_update($this->albumtable, $data, array('id' => $id));
			}
			message('楼盘相册信息更新成功！', $this->createWebUrl('albumlist', array('hid'=>$hid)), 'success');
	
		}
		include $this->template('albumadd');
	}
	
	public function doWebQuery() {
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename($this->headtable) . ' WHERE `weid`=:weid AND `title` LIKE :title';
		$params = array();
		$params[':weid'] = $_W['weid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		foreach($ds as &$row) {
			$r = array();
			$r['title'] = $row['title'];
			$r['description'] = $row['jianjie'];
			$r['thumb'] = $row['pic'];
			$r['mid'] = $row['hid'];
			$row['entry'] = $r;
		}
		include $this->template('query');
	}
	

	public function doWebFelllist() {
		global $_W,$_GPC;
		$hid=intval($_GPC['hid']);
		if(empty($hid))
		{
			message('无法找到对应楼盘!','','error');
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
	
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->felltable)." WHERE hid='{$hid}' and weid = '{$_W['weid']}' $condition ORDER BY sort DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->felltable) . " WHERE hid='{$hid}' and weid = '{$_W['weid']}' $condition");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('felllist');
	}
	
	public function doWebBilladd() {
		global $_GPC, $_W;
		$hid = intval($_GPC['hid']);

		load()->func('tpl');
		
		if (!empty($hid)) {
			$item = pdo_fetch("SELECT * FROM ".tablename($this->billtable)." WHERE hid = :hid and weid=:weid ", array(':hid' => $hid,':weid'=>$_W['weid']));
		}
		else
		{
			message('您的访问链接非法！','','error');
		}
	
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入海报标题！');
			}
			$data = array(
					'hid' => $_GPC['hid'],
					'weid' => $_W['weid'],
					'title' => $_GPC['title'],
			);
			//上传图片
			if (!empty($_FILES['pic']['tmp_name'])) {
				file_delete($_GPC['pic_old']);
				$upload = file_upload($_FILES['pic']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic'] = $upload['path'];
			}
			if (!empty($_FILES['pic1']['tmp_name'])) {
				file_delete($_GPC['pic1_old']);
				$upload = file_upload($_FILES['pic1']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic1'] = $upload['path'];
			}
			if (!empty($_FILES['pic2']['tmp_name'])) {
				file_delete($_GPC['pic2_old']);
				$upload = file_upload($_FILES['pic2']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic2'] = $upload['path'];
			}
			if (!empty($_FILES['pic3']['tmp_name'])) {
				file_delete($_GPC['pic3_old']);
				$upload = file_upload($_FILES['pic3']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic3'] = $upload['path'];
			}
			if (!empty($_FILES['pic4']['tmp_name'])) {
				file_delete($_GPC['pic4_old']);
				$upload = file_upload($_FILES['pic4']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic4'] = $upload['path'];
			}
	
			if (empty($item['hid']))
			{
				pdo_insert($this->billtable, $data);
			} else {
				pdo_update($this->billtable, $data, array('hid' => $hid));
			}
			message('海报信息更新成功！', $this->createWebUrl('billadd', array('hid'=>$hid)), 'success');
	
		}
		
		include $this->template('billadd');
	}
	
	
	public function doWebFelladd () {
		global $_GPC, $_W;
		$yid = intval($_GPC['yid']);
		$hid = intval($_GPC['hid']);
	
		if(empty($hid))
		{
			message('找不到楼盘信息！','','error');
		}
	
		if(!empty($yid))
		{
			$item = pdo_fetch("SELECT * FROM ".tablename($this->felltable)." WHERE yid = :yid", array(':yid' => $yid));
		}
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入印象名称！');
			}
			$data = array(
					'hid' => $hid,
					'weid'=>$_W['weid'],
					'title' => $_GPC['title'],
					'sort' => $_GPC['sort'],
					'yinxiang_number' => $_GPC['yinxiang_number'],
					'isshow' => $_GPC['isshow'],
					'createtime' => time(),
			);
			if (empty($yid)) {
				pdo_insert($this->felltable, $data);
			} else {
				unset($data['createtime']);
				pdo_update($this->felltable, $data, array('yid' => $yid));
			}
			message('印象更新成功！', $this->createWebUrl('felladd', array('yid'=>$yid,'hid' =>$hid )), 'success');
	
		}
		include $this->template('felladd');
	}
	
		public function doWebExpertlist() {
		global $_W,$_GPC;
		$hid=intval($_GPC['hid']);
		if(empty($hid))
		{
			message('无法找到对应楼盘!','','error');
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
	
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->experttable)." WHERE hid='{$hid}' and weid = '{$_W['weid']}' $condition ORDER BY sort DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->experttable) . " WHERE hid='{$hid}' and weid = '{$_W['weid']}' $condition");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('expertlist');
	}
	
	
	public function doWebExpertadd() {
		global $_GPC, $_W;
		$id=intval($_GPC['id']);
		$hid = intval($_GPC['hid']);		
		
		if(empty($hid))
		{
			message('您的访问链接非法！','','error');
		}
		
		if (!empty($id)&& !empty($hid)) {
			$item = pdo_fetch("SELECT * FROM ".tablename($this->experttable)." WHERE hid = :hid", array(':hid' => $hid));
			if (empty($item)) {
				message('抱歉，该专家点评不存在或是已经删除！', '', 'error');
			}
		}
		
	
	
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入点评标题！');
			}
			$data = array(
					'hid' => $_GPC['hid'],
					'weid' => $_W['weid'],
					'title' => $_GPC['title'],
					'expert_name' => $_GPC['expert_name'],
					'zhiwei' => $_GPC['zhiwei'],					
					'sort' => $_GPC['sort'],
					'jianjie' => $_GPC['jianjie'],
					'content' => $_GPC['content'],
			);
			//上传图片
			if (!empty($_FILES['thumb']['tmp_name'])) {
				file_delete($_GPC['thumb_old']);
				$upload = file_upload($_FILES['thumb']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['thumb'] = $upload['path'];
			}
				
		if (empty($id))
			{
				pdo_insert($this->experttable, $data);
			} else {
				pdo_update($this->experttable, $data, array('id' => $id));
			}
			message('专家点评信息更新成功！', $this->createWebUrl('expertlist', array('hid'=>$hid)), 'success');
	
		}
		include $this->template('expertadd');
	}
	
	
	public function doWebFullviewlist() {
		global $_W,$_GPC;
		$hid=$_GPC['hid'];		
		$hsid=$_GPC['hsid'];	
	
		$weid=$_W['weid'];	
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
	
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->fullviewtable)." WHERE hsid='{$hsid}' and weid = '{$weid}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->fullviewtable) . " WHERE  hsid='{$hsid}' and weid = '{$weid}' $condition");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('fullviewlist');
	}
	
	public function doWebFullviewadd() {
		global $_GPC, $_W;
		$id=intval($_GPC['id']);
		$hid=intval($_GPC['hid']);
		$hsid = intval($_GPC['hsid']);
		$weid=$_W['weid'];
	
		load()->func('tpl');
		if(empty($hsid))
		{
			message('您的访问链接非法！','','error');
		}
	
		if (!empty($id)&& !empty($hsid)) {
			$item = pdo_fetch("SELECT * FROM ".tablename($this->fullviewtable)." WHERE id = :id and weid=:weid", array(':id' => $id,':weid'=>$weid));
			if (empty($item)) {
				message('抱歉，该全景不存在或是已经删除！', '', 'error');
			}
		}
		
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入全景标题！');
			}
			$data = array(
					'hsid' => $_GPC['hsid'],
					'weid' => $_W['weid'],
					'title' => $_GPC['title'],
					'sort' => $_GPC['sort'],
					'status' => $_GPC['status'],
					'quanjinglink'=>$_GPC['quanjinglink'],
			);
			//上传图片
			if (!empty($_FILES['pic_qian']['tmp_name'])) {
				file_delete($_GPC['pic_qian_old']);
				$upload = file_upload($_FILES['pic_qian']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic_qian'] = $upload['path'];
			}
	
			//上传图片
			if (!empty($_FILES['pic_hou']['tmp_name'])) {
				file_delete($_GPC['pic_hou_old']);
				$upload = file_upload($_FILES['pic_hou']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic_hou'] = $upload['path'];
			}
			//上传图片
			if (!empty($_FILES['pic_zuo']['tmp_name'])) {
				file_delete($_GPC['pic_zuo_old']);
				$upload = file_upload($_FILES['pic_zuo']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic_zuo'] = $upload['path'];
			}
			//上传图片
			if (!empty($_FILES['pic_you']['tmp_name'])) {
				file_delete($_GPC['pic_you_old']);
				$upload = file_upload($_FILES['pic_you']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic_you'] = $upload['path'];
			}
			//上传图片
			if (!empty($_FILES['pic_shang']['tmp_name'])) {
				file_delete($_GPC['pic_shang_old']);
				$upload = file_upload($_FILES['pic_shang']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic_shang'] = $upload['path'];
			}
			
			//上传图片
			if (!empty($_FILES['pic_xia']['tmp_name'])) {
				file_delete($_GPC['pic_xia_old']);
				$upload = file_upload($_FILES['pic_xia']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['pic_xia'] = $upload['path'];
			}
				
			if (empty($id))
			{
				pdo_insert($this->fullviewtable, $data);
			} else {
				pdo_update($this->fullviewtable, $data, array('id' => $id));
			}
			message('全景图片更新成功！', $this->createWebUrl('fullviewlist', array('hsid'=>$hsid,'hid'=>$hid)), 'success');
	
		}
		include $this->template('fullviewadd');
	}

	public function doWebDelbuild()
	{
		global $_GPC,$_W;
		$hid = intval($_GPC['hid']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->headtable)." WHERE hid = :hid and weid=:weid" , array(':hid' => $hid,':weid'=>$_W['weid']));
		if (empty($item)) {
			message('抱歉，该楼盘不存在或是已经删除！', '', 'error');
		}
		if (!empty($item['pic'])) {
			file_delete($item['pic']);
		}
		if (!empty($item['headpic'])) {
			file_delete($item['headpic']);
		}
		if (!empty($item['apartpic'])) {
			file_delete($item['apartpic']);
		}
		if(pdo_delete($this->headtable, array('hid' => $hid)))
		{
			message('删除成功！', referer(), 'success');
		}
		else
		{
			message('删除失败！', referer(), 'error');
		}

	}
	
	public function doWebDelsubbuild()
	{
		global $_GPC,$_W;
		$sid = intval($_GPC['sid']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->subtable)." WHERE sid = :sid and weid=:weid" , array(':sid' => $sid,':weid'=>$_W['weid']));
		if (empty($item)) {
			message('抱歉，该子楼盘不存在或是已经删除！', '', 'error');
		}
		if(pdo_delete($this->subtable, array('sid' => $sid)))
		{
		message('删除成功！', referer(), 'success');
		}
		else 
		{
		message('删除失败！', referer(), 'error');
		}
	}
	
	
	public function doWebDelfell()
	{
		global $_GPC,$_W;
		$yid = intval($_GPC['yid']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->felltable)." WHERE yid = :yid and weid=:weid" , array(':yid' => $yid,':weid'=>$_W['weid']));
		if (empty($item)) {
			message('抱歉，该印象不存在或是已经删除！', '', 'error');
		}
		if(pdo_delete($this->felltable, array('yid' => $yid)))
		{
			message('删除成功！', referer(), 'success');
		}
		else
		{
			message('删除失败！', referer(), 'error');
		}
	}
	
	public function doWebDelexpert()
	{
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->experttable)." WHERE id = :id and weid=:weid" , array(':id' => $id,':weid'=>$_W['weid']));
		if (empty($item)) {
			message('抱歉，该专家点评不存在或是已经删除！', '', 'error');
		}
		if(!empty($item['thumb']))
		{
			file_delete($item['thumb']);
		}
		if(pdo_delete($this->experttable, array('id' => $id)))
		{
			message('删除成功！', referer(), 'success');
		}
		else
		{
			message('删除失败！', referer(), 'error');
		}
	}
	
	public function doWebDelhouse()
	{
		global $_GPC,$_W;
		$hsid = intval($_GPC['hsid']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->housetalbe)." WHERE hsid = :hsid and weid=:weid" , array(':hsid' => $hsid,':weid'=>$_W['weid']));
		if (empty($item)) {
			message('抱歉，该户型不存在或是已经删除！', '', 'error');
		}
		if(!empty($item['pic']))
		{
			$hspiclist=json_decode($item['pic'],true); 
			foreach ($hspiclist as $pic){
				file_delete($pic['src']);
			}
		}
		
		if(pdo_delete($this->housetalbe, array('hsid' => $hsid)))
		{
			message('删除成功！', referer(), 'success');
		}
		else
		{
			message('删除失败！', referer(), 'error');
		}	
	}
	
	public  function  doMobileAjaxdelhspic()
	{
		global $_GPC;
		$delurl = $_GPC['pic'];
		if(file_delete($delurl))
		{echo 1;}
		else
		{echo 0;}
	}
	
	
	public  function  doMobileAjaxdelalbumpic()
	{
		global $_GPC;
		$delurl = $_GPC['pic'];
		if(file_delete($delurl))
		{echo 1;}
		else
		{echo 0;}
	}
	
	public function doMobileBuildindex()
	{
		global $_GPC, $_W;
		$weid=$_W['weid'];
		$hid = $_GPC['hid'];
		$fromuser=$_W['fans']['from_user'];
		$set = pdo_fetch("SELECT * FROM ".tablename($this->headtable)." WHERE weid=:weid and hid = :hid ", array(':weid'=>$weid,':hid' => $hid));

		if(empty($set)){
			message('抱歉，楼盘不存在或者已删除！','','error');
		}
		
		$hb = pdo_fetch("SELECT * FROM ".tablename($this->billtable)." WHERE weid=:weid and hid = :hid ", array(':weid'=>$weid,':hid' => $hid));
		
		
		$xwurl = $set['xwurl'];
		$yyurl = $set['yyurl'];
		$hyurl = '';
		/*
		if(empty($set['hyurl']))
		{
			$member = pdo_fetch("SELECT id, cardsn FROM ".tablename('card_members')." WHERE from_user = :from_user", array(':from_user' => $fromuser));
			if($this->hycard_type==0)
			{
				$hyurl= !empty($member) ? create_url('mobile/channel', array('name' => 'home', 'weid' => $_W['weid'])) : create_url('mobile/module/card', array('name' => 'member', 'weid' => $_W['weid']));
			}
			else
			{
				//use icard
				$hyurl= $_W['siteroot'] . "mobile.php?act=module&name=icard&do=wapindex&weid={$weid}&from_user=".base64_encode(authcode($fromuser, 'ENCODE'));
			}				
		}
		else 
		{
			$hyurl=str_replace('{fromuser}', $fromuser, $set['hyurl']);
		}
		*/
		include $this->template('buildindex');
	}
	
	public function doMobileBuildinfo()
	{
		global $_GPC, $_W;
		$weid=$_W['weid'];
		$hid = $_GPC['hid'];
		$set = pdo_fetch("SELECT * FROM ".tablename($this->headtable)." WHERE weid=:weid and hid = :hid ", array(':weid'=>$weid,':hid' => $hid));
	
		if(empty($set)){
			message('抱歉，楼盘不存在或者已删除！','','error');
		}
		include $this->template('buildinfo');
	}
	
	public function doMobileHuxing() {
		global $_GPC, $_W;
		$hid = $_GPC['hid'];
		$item = pdo_fetchall("SELECT * FROM ".tablename($this->housetalbe)." WHERE weid=:weid and hid = :hid order by sort desc", array(':weid'=>$_W['weid'],':hid' => $hid));
		$res=array();		
		foreach ($item as $itv)
		{			
			$nowsid=$itv['sid'];
			$res[$nowsid][]=$itv;		
		}
		include $this->template('huxing');
	}
	

	public function doMobileHuxingpic()
	{
		global $_GPC, $_W;
		$type = $_GPC['typ'];
		if($type=='get')
		{
			$hxid=$_GPC['hsid'];
			$hxtype=pdo_fetch('select * from '.tablename($this->housetalbe).' where hsid=:hxid',array(':hxid'=>$hxid));

			if(trim($hxtype['pic'])=='')
			{
				header('Content-type: '.'text/html');				
				echo '{}';			
			}
	
			$pics = json_decode($hxtype['pic'],true);
			$data = array();
			$data['id'] = $hxid;
			$data['name'] = $pics[0]['txt'];
			$data['tit'] = $hxtype['title'];
			$data['desc'] =$this->getsubname($hxtype['sid']);
			$data['rooms'] = $hxtype['fang'].'室'.$hxtype['ting'].'厅';
			$data['area'] = $hxtype['mianji'];
			$data['simg'] = $data['bimg'] =$_W['attachurl'].$pics[0]['src'];
			$data['width'] = $data['height'] = 1600;
			$data['dtitle'] = array($hxtype['title']);
			$data['dlist'] = array($hxtype['jianjie']);

			$picarr = array();
			$isfirst = true;
			foreach ($pics as $pic){
				if($isfirst){
					$isfirst = false;
					continue;
				}
				$picarr[] = array('img'=>$_W['attachurl'].$pic['src'],'width'=>$pic['w']*2,'height'=>$pic['h']*2,'name'=>$pic['txt']);
			}
			$data['pics'] = $picarr;
			$res= array();
			$res['rooms'] = array($data);
			header('Content-type: '.'application/json');						
			echo json_encode($res);
		}
		else 
		{
		include $this->template('huxingpic');
		}

	}

	public  function  doMobileDelalbumpic()
	{
		global $_GPC;
		$delurl = $_GPC['pic'];
		if(file_delete($delurl))
		{echo 1;}
		else
		{echo 0;}
	}
	

	
	public function  doMobileReview()
	{
		global $_GPC, $_W;
		$type = $_GPC['typ'];
		$weid=$_W['weid'];
		$hid=$_GPC['hid'];
		$fromuser=$_W['fans']['from_user'];
		if(empty($fromuser))
		{$fromuser=0;}
		
		if($type)
		{
			$red=pdo_fetch('select * from '.tablename($this->fellrecordtable)." where hid='{$hid}'and  weid='{$weid}' and fromuser='{$fromuser}'");
		
			//查找我的印象
			if($type=='setres'){
				$content = $_GPC['content'];
				$red['fromuser'] = $fromuser;
				$red['hid']=$hid;
				$red['weid'] = $weid;
				$red['title'] = $content;
				if($red['id'])
				{
					pdo_update($this->fellrecordtable,$red);
				}
				else 
				{
					pdo_insert($this->fellrecordtable,$red);
				}
				
			}
		
			 
			//$sum = $yx->sum('yinxiang_number');
			$yxres = pdo_fetchall('select * from '.tablename($this->felltable).' where weid=:weid and isshow=1 order by sort desc,yinxiang_number desc,yid',array(':weid'=>$weid));			 
			$sum=pdo_fetchcolumn('select sum(yinxiang_number) from '.tablename($this->felltable).' where weid=:weid and isshow=1',array(':weid'=>$weid));
			$yx_arr = array();		
			$u_arr = array();
			if($red['id']){
				$u_arr = array('content'=>$red['title'],'count'=>1,'id'=>99998196789);
			}else{
				$u_arr = array('content'=>'','count'=>0,'id'=>-1);
			}
		
			foreach ($yxres as $y){
				if($y['title']==$red['title']){
					$u_arr['id'] = $y['yid'];
					$u_arr['count'] = $y['yinxiang_number'];
				}
				$yx_arr[] = array('content'=>$y['title'],'count'=>$y['yinxiang_number'],'id'=>$y['yid']);
			}
			$res = array('msg'=>'ok','ret'=>'0','user'=>$u_arr,'top'=>$yx_arr,'sum'=>$sum);
			header('Content-type: '.'application/json');
			echo json_encode($res);
			die();
		}
		else if($_GPC['rtyp'])
		{
			$res = array();
			$vres = pdo_fetchall('select * from '.tablename($this->experttable).' where weid=:weid and hid=:hid order by sort desc,id',array(':weid'=>$weid,':hid'=>$hid));
			foreach ($vres as $v){
				$msg = array();
				$msg['name'] = $v['expert_name'];
				$msg['title'] = $v['zhiwei'];
				$msg['photo'] =$_W['attachurl'].$v['thumb'];
				$msg['intro'] = $v['jianjie'];
				$msg['reviewTitle'] = $v['title'];
				$msg['reviewDesc'] = $v['content'];
				$res[] = $msg;
			}
			header('Content-type: '.'application/json');
			echo json_encode($res);
			die();
		}
		include $this->template('review');
	}
	
	public function doMobileViewbdalbum()
	{
		global $_GPC, $_W;
		$type = $_GPC['type'];
		$weid=$_W['weid'];
		$hid=$_GPC['hid'];
		$fromuser=$_W['fans']['from_user'];
		if(empty($fromuser))
		{
			$fromuser=0;
		}
		
		if((!empty($weid)&&(!empty($hid))))
		{
			if($type=='getpic'){

				$xcres = pdo_fetchall('select * from '.tablename($this->albumtable).' where weid=:weid and hid=:hid order by sort desc,id',array(':weid'=>$weid,':hid'=>$hid));
				$resdata = array();
				foreach($xcres as $xc) {
					$pics = json_decode($xc['pic'],true);
					//图片数量
					$picnum = count($pics);
					//每行数量
					$mhsl = intval(($picnum)/2);
		
					$xmdata = array();
					$xmdata['title'] = $xc['title'];
		
					//第一行
					$line1data = array();
					$line1data[] = array('type'=>'title','title'=>$xc['title'],'subTitle'=>$xc['subtitle']);
					for($i=0;$i<$mhsl;$i++){
						$pic = $pics[$i];
						$line1data[] = array('type'=>'img','name'=>$pic['txt'],'img'=>$_W['attachurl'].$pic['src'],'size'=>array($pic['w'],$pic['h']));
					}
					//第二行
					$line2data = array();
					if(isset($pics[$mhsl])){
						$pic = $pics[$mhsl];
						$line2data[] = array('type'=>'img','name'=>$pic['txt'],'img'=>$_W['attachurl'].$pic['src'],'size'=>array($pic['w'],$pic['h']));
					}
					$line2data[] = array('type'=>'text','content'=>$xc['jianjie']);
					for($i=$mhsl+1;$i<$picnum;$i++){
						$pic = $pics[$i];
						$line2data[] = array('type'=>'img','name'=>$pic['txt'],'img'=>$_W['attachurl'].$pic['src'],'size'=>array($pic['w'],$pic['h']));
					}
					$xmdata['ps1'] = $line1data;
					$xmdata['ps2'] = $line2data;
					$resdata[] = $xmdata;
				}
				header('Content-type: '.'application/json');
				echo json_encode($resdata);
				die();
			}
			include $this->template('viewbdalbum');
		
		}
		else
		{
			die();
		}
	}
	
	public function doMobileFullviewstart()
	{
	global $_GPC,$_W;
	$hsid=intval($_GPC['hsid']);
	$weid=$_W['weid'];
		if($hsid && $weid)
		{
			//查找户型
			$hxres = pdo_fetch('select * from '.tablename($this->housetalbe).' where weid=:weid and hsid=:hsid order by sort desc',array(':weid'=>$weid,':hsid'=>$hsid));
			$hxmc = $hxres['title'];
			$lp = pdo_fetch('select * from '.tablename($this->subtable).' where weid=:weid and sid=:sid order by sort desc,sid',array(':weid'=>$weid,':sid'=>$hxres['sid']));
			$lpmc = trim($lp['title']);
			//查找全景
			$qres = pdo_fetchall('select * from '.tablename($this->fullviewtable).' where weid=:weid and hsid=:hsid and status=1 order by sort desc,id',array(':weid'=>$weid,':hsid'=>$hsid));
		}
	else{
			die();
		}
		include $this->template('fullviewstart');
	}
	
	
	public function doMobileFullview()
	{
		global $_GPC,$_W;
		$qid=intval($_GPC['id']);
		include $this->template('fullview');
	}
	
	public function doWebFullviewdel()
	{
		global $_GPC,$_W;
		$hsid = intval($_GPC['hsid']);
		$id=intval($_GPC['id']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->fullviewtable)." WHERE hsid = :hsid and weid=:weid and id=:id" , array(':hsid' => $hsid,':weid'=>$_W['weid'],'id'=>$id));
		if (empty($item)) {
			message('抱歉，该全景不存在或是已经删除！', '', 'error');
		}
		if (!empty($item['pic_qian'])) {
			file_delete($item['pic_qian']);
		}
		if (!empty($item['pic_hou'])) {
			file_delete($item['pic_hou']);
		}
		if (!empty($item['pic_zuo'])) {
			file_delete($item['pic_zuo']);
		}
		if (!empty($item['pic_shang'])) {
			file_delete($item['pic_shang']);
		}
		if (!empty($item['pic_xia'])) {
			file_delete($item['pic_xia']);
		}
		if (!empty($item['pic_you'])) {
			file_delete($item['pic_you']);
		}
		
		
		if(pdo_delete($this->fullviewtable, array('id' => $id)))
		{
			message('删除成功！', referer(), 'success');
		}
		else
		{
			message('删除失败！', referer(), 'error');
		}
	
	}
	
	public function doMobileGetxml()
	{
		global $_GPC,$_W;
		$id=intval($_GPC['id']);
		$weid=$_W['weid'];

		if(!empty($id))
		{
			$full_view = pdo_fetch('select * from '.tablename($this->fullviewtable).' where weid=:weid and id=:id and status=1 ',array(':weid'=>$weid,':id'=>$id));
			$pics = '
	<input tilesize="700" tilescale="1.014285714285714"
	tile0url="%s"
    tile1url="%s"
    tile2url="%s"
    tile3url="%s"
    tile4url="%s"
    tile5url="%s"
    />';
			$hou=$this->getdefaultfullviewpic(2,$full_view['pic_hou']);
			$qian=$this->getdefaultfullviewpic(1,$full_view['pic_qian']);
			$you=$this->getdefaultfullviewpic(4,$full_view['pic_you']);
			$zuo=$this->getdefaultfullviewpic(3,$full_view['pic_zuo']);
			$shang=$this->getdefaultfullviewpic(5,$full_view['pic_shang']);
			$xia=$this->getdefaultfullviewpic(6,$full_view['pic_xia']);
			//$pic_view =  sprintf($pics, $hou, $qian, $you, $zuo, $shang,$xia);
			$pic_view =  sprintf($pics,$qian  , $you,$hou,$zuo , $shang,$xia);
		
			
			$xml_text = '
	<?xml version="1.0" encoding="UTF-8" ?>
<panorama id="" hideabout="1">
    <view fovmode="0" pannorth="0">
        <start pan="5.5" fov="80" tilt="1.5" />
        <min pan="0" fov="80" tilt="-90" />
        <max pan="360" fov="80" tilt="90" />
    </view>
    <userdata title="" datetime="2013:05:23 21:01:02" description="" copyright=""
    tags="" author="" source="" comment="" info="" longitude="" latitude=""
    />
    <hotspots width="180" height="20" wordwrap="1">
        <label width="180" backgroundalpha="1" enabled="1" height="20" backgroundcolor="0xffffff"
        bordercolor="0x000000" border="1" textcolor="0x000000" background="1" borderalpha="1"
        borderradius="1" wordwrap="1" textalpha="1" />
        <polystyle mode="0" backgroundalpha="0.2509803921568627" backgroundcolor="0x0000ff"
        bordercolor="0x0000ff" borderalpha="1" />
    </hotspots>
    <media/>
    '.$pic_view.'
    <autorotate speed="0.200" nodedelay="0.00" startloaded="1" returntohorizon="0.000"
    delay="5.00" />
    <control simulatemass="1" lockedmouse="0" lockedkeyboard="0" dblclickfullscreen="0"
    invertwheel="0" lockedwheel="0" invertcontrol="1" speedwheel="1" sensitivity="8"
    />
</panorama>
	  ';
		
		}
		$xml_text = strval($xml_text);
		header('Content-type: '.'text/xml');
		echo trim($xml_text);
	}
	
	
	//1:主页图片 2：楼盘图片 3：户型图片
	public function geturl($type=1)
	{
		switch ($type)
		{
			case 1:
				$img_url='../addons/lxy_buildpro/template/img/build_home.png';
				break;
			case 2:
				$img_url='../addons/lxy_buildpro/template/img/build_banner.png';
				break;
			case 3:
				$img_url='../addons/lxy_buildpro/template/img/build_house_banner.png';
				break;
			case 4:
				$img_url='../addons/lxy_buildpro/template/img/YouGotMe.mp3';
				break;
			default:
				$img_url='../addons/lxy_buildpro/template/img/art_pic.png';
		}
		return $img_url;			
	}
	
	public function getsubname($sid)
	{
		if(empty($sid))
		{
			return '';			
		}
		else 
		{
			$suname = pdo_fetchcolumn("SELECT title FROM ".tablename($this->subtable)." WHERE sid = :sid", array(':sid' => $sid));
			return $suname;
				
		}
	}
	
	public function getindexpic($picurl)
	{
		global $_W;
		if(empty($picurl))
		{
			$img_url=$_W['siteroot'].'addons/lxy_buildpro/template/img/index.jpg';
		}
		else 
		{
			$img_url=$_W['attachurl'].$picurl;
		}
		return $img_url;
	}
	
	public function getdefaultfullviewpic($type,$picurl)
	{
		global $_W;
		switch ($type)
		{
			case 1:
				if(empty($picurl))
				{
					$img_url=$_W['siteroot'].'addons/lxy_buildpro/template/img/qian.jpg';
				}
				else 
				{
					$img_url=$_W['attachurl'].$picurl;
				}
				break;
			case 2:
				if(empty($picurl))
				{
				$img_url=$_W['siteroot'].'addons/lxy_buildpro/template/img/hou.jpg';
				}
				else 
				{
					$img_url=$_W['attachurl'].$picurl;
				}
				break;
			case 3:
				if(empty($picurl))
				{
				$img_url=$_W['siteroot'].'addons/lxy_buildpro/template/img/zuo.jpg';
				}				
				else
				{
					$img_url=$_W['attachurl'].$picurl;
				}
				break;
			case 4:
				if(empty($picurl))
				{
				$img_url=$_W['siteroot'].'addons/lxy_buildpro/template/img/you.jpg';
				}				
				else
				{
					$img_url=$_W['attachurl'].$picurl;
				}
				break;
			case 5:
				if(empty($picurl))
				{
					$img_url=$_W['siteroot'].'addons/lxy_buildpro/template/img/shang.jpg';
				}				
				else
				{
					$img_url=$_W['attachurl'].$picurl;
				}
				break;
			case 6:
				if(empty($picurl))
				{
					$img_url=$_W['siteroot'].'addons/lxy_buildpro/template/img/xia.jpg';
				}				
				else
				{
					$img_url=$_W['attachurl'].$picurl;
				}
				break;
			default:
				$img_url='';
		}
		return $img_url;
	}
	
	//1:主页图片 2：楼盘图片 3：户型图片
	public function getheadpic($hid,$type)
	{
		global $_W;
		if(!empty($hid))
		{
			$row = pdo_fetch("SELECT * FROM ".tablename($this->headtable)." WHERE hid = :hid", array(':hid' => $hid));
			switch ($type)
			{
				case 1:
					$pictmp=$row['pic'];
					break;
				case 2:
					$pictmp=$row['headpic'];
					break;
				case 3:
					$pictmp=$row['apartpic'];
					break;				
				default:

			}
				
				
		}
		
		if(empty($pictmp))
		{
			$ret=$this->geturl($type);
		}
		else
		{
			$ret=$_W['attachurl'].$pictmp;			
		}
		return $ret;
	}
	
	public function hasqj($hsid)
	{
		$qjnum = pdo_fetchcolumn('select id from '.tablename($this->fullviewtable).' where hsid=:hsid and status=1 ',array(':hsid'=>$hsid));
		return $qjnum;
	}
	public function tplRadionSelect($field, $param, $default, $memo) {
		$html = '';
		foreach ( $param as $key => $value ) {
			if ($default != '' && $default == $key) {
				$html .= "<label class='radio-inline'><input type='radio' name='{$field}' class='isauto_radio' value='{$key}' checked>{$value}</label>";
			} else {
				$html .= "<label class='radio-inline'><input type='radio' name='{$field}' class='isauto_radio' value='{$key}'>{$value}</label>";
			}
		}
		if (! empty ( $memo )) {
			$html .= "<span class='help-block'>{$memo}</span></div>";
		}
	
		return $html;
	}
}
