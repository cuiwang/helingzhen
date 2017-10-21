<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Goods_EweiShopV2Page extends PluginWebPage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$page = max(1, intval($_GPC['page']));
		$psize = 20;
		$pstart = $psize * ($page - 1);
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) >' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid ORDER BY id DESC LIMIT ' . $pstart . ',' . $psize, array(':uniacid' => $_W['uniacid']));
		$allEnd = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) <' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$allStart = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) >' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$allNostart = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE  unix_timestamp(starttime) >' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$pager = pagination($allStart, $page, $psize);
		include $this->template();
	}
	public function nostart() 
	{
		global $_W;
		global $_GPC;
		$page = max(1, intval($_GPC['page']));
		$psize = 20;
		$pstart = $psize * ($page - 1);
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(starttime) >' . time() . ' AND mode = 1 AND uniacid=:uniacid ORDER BY id DESC LIMIT ' . $pstart . ',' . $psize, array(':uniacid' => $_W['uniacid']));
		$allEnd = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) <' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$allStart = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) >' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$allNostart = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE  unix_timestamp(starttime) >' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$pager = pagination($allNostart, $page, $psize);
		include $this->template();
	}
	public function end() 
	{
		global $_W;
		global $_GPC;
		$page = max(1, intval($_GPC['page']));
		$psize = 20;
		$pstart = $psize * ($page - 1);
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) <' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid ORDER BY id DESC LIMIT ' . $pstart . ',' . $psize, array(':uniacid' => $_W['uniacid']));
		$allEnd = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) <' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$allStart = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) >' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$allNostart = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE  unix_timestamp(starttime) >' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$pager = pagination($allEnd, $page, $psize);
		include $this->template();
	}
	public function dno() 
	{
		global $_W;
		global $_GPC;
		$page = max(1, $_GPC['page']);
		@session_start();
		$psize = intval($_GPC['psize']);
		if (!(empty($psize))) 
		{
			$_SESSION['psize'] = $psize;
		}
		else if (!(empty($_SESSION['psize']))) 
		{
			$psize = $_SESSION['psize'];
		}
		else 
		{
			$psize = 100;
		}
		$pstart = $psize * ($page - 1);
		$id = $_GPC['id'];
		$res = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' ' . "\r\r" . '            WHERE id = :id AND uniacid = :uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 1 AND unix_timestamp(endtime)>' . time() . ' ORDER BY id DESC LIMIT ' . $pstart . ',' . $psize, array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dno = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 1 AND unix_timestamp(endtime)>' . time(), array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dyet = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 2', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dend = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status!=2 AND unix_timestamp(endtime)<=' . time(), array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$pager = pagination($dno, $page, $psize);
		include $this->template();
	}
	public function dyet() 
	{
		global $_W;
		global $_GPC;
		$page = max(1, $_GPC['page']);
		@session_start();
		$psize = intval($_GPC['psize']);
		if (!(empty($psize))) 
		{
			$_SESSION['psize'] = $psize;
		}
		else if (!(empty($_SESSION['psize']))) 
		{
			$psize = $_SESSION['psize'];
		}
		else 
		{
			$psize = 100;
		}
		$pstart = $psize * ($page - 1);
		$id = $_GPC['id'];
		$res = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' ' . "\r\r" . '            WHERE id = :id AND uniacid = :uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 2 ORDER BY id DESC LIMIT ' . $pstart . ',' . $psize, array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dno = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 1 AND unix_timestamp(endtime)>' . time(), array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dyet = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 2', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dend = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status!=2 AND unix_timestamp(endtime)<=' . time(), array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$pager = pagination($dyet, $page, $psize);
		include $this->template();
	}
	public function dend() 
	{
		global $_W;
		global $_GPC;
		$page = max(1, $_GPC['page']);
		@session_start();
		$psize = intval($_GPC['psize']);
		if (!(empty($psize))) 
		{
			$_SESSION['psize'] = $psize;
		}
		else if (!(empty($_SESSION['psize']))) 
		{
			$psize = $_SESSION['psize'];
		}
		else 
		{
			$psize = 100;
		}
		$pstart = $psize * ($page - 1);
		$id = $_GPC['id'];
		$res = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' ' . "\r\r" . '            WHERE id = :id AND uniacid = :uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status != 2 AND unix_timestamp(endtime)<' . time() . ' ORDER BY id DESC LIMIT ' . $pstart . ',' . $psize, array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dno = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 1 AND unix_timestamp(endtime)>' . time(), array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dyet = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 2', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dend = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status!=2 AND unix_timestamp(endtime)<=' . time(), array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$pager = pagination($dend, $page, $psize);
		include $this->template();
	}
	public function delete() 
	{
		global $_W;
		global $_GPC;
		$id = (int) $_GPC['id'];
		$ids = $_GPC['ids'];
		if (is_array($ids)) 
		{
			foreach ($ids as $value ) 
			{
				$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid=:id AND status < 2 AND unix_timestamp(endtime)>' . time(), array(':id' => $value));
				if (!(empty($count))) 
				{
					show_json(0, '删除失败！<br>必须先将本组【未兑换】的兑换码全部删除');
				}
			}
			$value = NULL;
			foreach ($ids as $value ) 
			{
				pdo_delete('ewei_shop_exchange_group', array('id' => $value, 'uniacid' => $_W['uniacid']));
			}
			show_json(1, '删除成功!');
		}
		else 
		{
			$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid=:id AND status < 2 AND unix_timestamp(endtime)>' . time(), array(':id' => $id));
			if (empty($count)) 
			{
				pdo_delete('ewei_shop_exchange_group', array('id' => $id, 'uniacid' => $_W['uniacid']));
				show_json(1, '删除成功');
			}
			else 
			{
				show_json(0, '删除失败！<br>必须先将本组【未兑换】的兑换码全部删除');
			}
		}
	}
	public function stock() 
	{
		global $_W;
		global $_GPC;
		$stock = intval($_GPC['value']);
		$goodsid = intval($_GPC['goodsid']);
		$optionid = intval($_GPC['optionid']);
		if (!(empty($goodsid))) 
		{
			pdo_update('ewei_shop_goods', array('exchange_stock' => $stock), array('id' => $goodsid));
		}
		else if (!(empty($optionid))) 
		{
			pdo_update('ewei_shop_goods_option', array('exchange_stock' => $stock), array('id' => $optionid));
		}
		else 
		{
			show_json(0, '参数错误');
		}
		show_json(1, '修改成功');
	}
	public function postage() 
	{
		global $_W;
		global $_GPC;
		$postage = intval($_GPC['value']);
		$goodsid = intval($_GPC['goodsid']);
		$optionid = intval($_GPC['optionid']);
		if (!(empty($goodsid))) 
		{
			pdo_update('ewei_shop_goods', array('exchange_postage' => $postage), array('id' => $goodsid));
		}
		else if (!(empty($optionid))) 
		{
			pdo_update('ewei_shop_goods_option', array('exchange_postage' => $postage), array('id' => $optionid));
		}
		else 
		{
			show_json(0, '参数错误');
		}
		show_json(1, '修改成功');
	}
	public function goodspost() 
	{
		global $_W;
		global $_GPC;
		$id = $_GPC['id'];
		$all = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE uniacid = :uniacid AND deleted = 0 ' . "\r\r" . '        AND hasoption = 0', array(':uniacid' => $_W['uniacid']));
		$hasoption = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE uniacid = :uniacid AND deleted = 0 ' . "\r\r" . '        AND hasoption = 1', array(':uniacid' => $_W['uniacid']));
		$i = 0;
		foreach ($hasoption as $e => $item ) 
		{
			$option[$i] = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods_option') . ' WHERE goodsid = :id', array(':id' => $item['id']));
			$option[$i][0]['gt'] = $item['title'];
			$option[$i][0]['pic'] = $item['thumb'];
			++$i;
		}
		$loop = count($option);
		if (!(empty($id))) 
		{
			$setting = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE id=:id AND uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
			$goods = json_decode($setting['goods'], true);
		}
		include $this->template();
	}
	public function qrcode() 
	{
		global $_GPC;
		global $_W;
		$key = $_GPC['key'];
		$type = $_GPC['type'];
		$id = intval($_GPC['id']);
		$res = pdo_fetch('SELECT * FROM ' . tablename('qrcode') . ' WHERE keyword = :keyword AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':keyword' => md5($key)));
		$qrcode_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $res['ticket'];
		include $this->template();
	}
	public function add() 
	{
		global $_W;
		global $_GPC;
		if ($_W['ispost']) 
		{
			$showcount = intval($_GPC['showcount']);
			$binding = $_GPC['binding'];
			if ($binding == 'on') 
			{
				$binding = 1;
			}
			else 
			{
				$binding = 0;
			}
			$postagetype = intval($_GPC['postagetype']);
			if (!(empty($postagetype))) 
			{
				$postage = 0;
			}
			else 
			{
				$postage = floatval($_GPC['postage']);
			}
			$no_checkbox = $_GPC['no_checkbox'];
			$has_checkbox = $_GPC['has_checkbox'];
			if (($no_checkbox == false) && ($has_checkbox == false)) 
			{
				show_json(0, '请至少选择一个商品');
			}
			$goods['goods'] = $no_checkbox;
			$array = array();
			if ($has_checkbox != false) 
			{
				foreach ($has_checkbox as $k => $v ) 
				{
					$temp = explode('_', $v);
					if (is_null($temp[1])) 
					{
						continue;
					}
					if (!(array_key_exists($temp[0], $array))) 
					{
						$array[$temp[0]][0] = $temp[1];
					}
					else 
					{
						$jianzhi = count($array[$temp[0]]);
						$array[$temp[0]][$jianzhi] = $temp[1];
					}
				}
				$goods['option'] = $array;
			}
			$json_goods = json_encode($goods);
			$title = $_GPC['title'];
			$endtime = $_GPC['endtime'];
			$starttime = $_GPC['starttime'];
			$type = (int) $_GPC['type'];
			$max = (int) $_GPC['max'];
			$value = (double) $_GPC['value'];
			if (!(empty($title)) && !(empty($endtime)) && (0 <= $max) && !(empty($type)) && !(empty($starttime)) && (!(empty($no_checkbox)) || !(empty($has_checkbox)) || !(empty($value)))) 
			{
				$data = array('title' => $title, 'endtime' => $endtime, 'type' => $type, 'max' => $max, 'mode' => 1, 'status' => 1, 'uniacid' => $_W['uniacid'], 'status' => 1, 'value' => $value, 'starttime' => $starttime, 'goods' => $json_goods, 'img' => '../addons/ewei_shopv2/plugin/exchange/static/img/exchange.jpg', 'title_reply' => '商品兑换', 'content' => '欢迎来到兑换中心,点击进入兑换', 'code_type' => intval($_GPC['code_type']), 'binding' => $binding, 'postage' => $postage, 'postage_type' => $postagetype, 'showcount' => $showcount);
				pdo_insert('ewei_shop_exchange_group', $data);
				$insert_id = pdo_insertid();
				if (!(empty($insert_id))) 
				{
					show_json(1, array('url' => webUrl('exchange/goods/setting', array('id' => $insert_id), 1)));
				}
			}
			else 
			{
				show_json(0, '保存失败,参数不全');
			}
		}
	}
	public function edit() 
	{
		global $_W;
		global $_GPC;
		$id = $_GPC['id'];
		if ($_W['ispost']) 
		{
			$showcount = intval($_GPC['showcount']);
			$binding = $_GPC['binding'];
			if ($binding == 'on') 
			{
				$binding = 1;
			}
			else 
			{
				$binding = 0;
			}
			$postagetype = intval($_GPC['postagetype']);
			if (!(empty($postagetype))) 
			{
				$postage = 0;
			}
			else 
			{
				$postage = floatval($_GPC['postage']);
			}
			$no_checkbox = $_GPC['no_checkbox'];
			$has_checkbox = $_GPC['has_checkbox'];
			if (($no_checkbox == false) && ($has_checkbox == false)) 
			{
				show_json(0, '请至少选择一个商品');
			}
			$goods['goods'] = $no_checkbox;
			$array = array();
			if ($has_checkbox != false) 
			{
				foreach ($has_checkbox as $k => $v ) 
				{
					$temp = explode('_', $v);
					if (is_null($temp[1])) 
					{
						continue;
					}
					if (!(array_key_exists($temp[0], $array))) 
					{
						$array[$temp[0]][0] = $temp[1];
					}
					else 
					{
						$jianzhi = count($array[$temp[0]]);
						$array[$temp[0]][$jianzhi] = $temp[1];
					}
				}
			}
			$goods['option'] = $array;
			$json_goods = json_encode($goods);
			$title = $_GPC['title'];
			$endtime = $_GPC['endtime'];
			$starttime = $_GPC['starttime'];
			$type = (int) $_GPC['type'];
			$max = (int) $_GPC['max'];
			$value = (double) $_GPC['value'];
			if (!(empty($title)) && !(empty($endtime)) && (0 <= $max) && !(empty($type)) && !(empty($starttime)) && (!(empty($no_checkbox)) || !(empty($has_checkbox)) || !(empty($value)))) 
			{
				$data = array('title' => $title, 'endtime' => $endtime, 'type' => $type, 'max' => $max, 'mode' => 1, 'status' => 1, 'uniacid' => $_W['uniacid'], 'value' => $value, 'starttime' => $starttime, 'goods' => $json_goods, 'binding' => $binding, 'postage' => $postage, 'postage_type' => $postagetype, 'showcount' => $showcount);
				pdo_update('ewei_shop_exchange_group', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
				show_json(1, '保存成功');
			}
			else 
			{
				show_json(0, '保存失败,参数不全');
			}
		}
	}
	public function creat() 
	{
		global $_GPC;
		global $_W;
		$id = (int) $_GPC['id'];
		$res = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' ' . "\r\r" . '            WHERE id = :id AND uniacid = :uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		@session_start();
		if ((0 < intval($_GPC['ajax'])) && (0 < intval($_GPC['loop']))) 
		{
			$dir_pre = intval($_GPC['dir_pre']);
			if ($_SESSION['dir_prev'] == $dir_pre) 
			{
				$dir_next = $_SESSION['fileMD5'];
			}
			else 
			{
				$dir_next = $this->getRandChar(10);
				$dir_next = md5($dir_next);
				$_SESSION['fileMD5'] = $dir_next;
				$_SESSION['dir_prev'] = $dir_pre;
			}
			$dir_pre .= $dir_next;
			$shuzi = intval($_GPC['shuzi']);
			$daxie = intval($_GPC['daxie']);
			$xiaoxie = intval($_GPC['xiaoxie']);
			$qianzhui = trim($_GPC['qianzhui']);
			$length = intval($_GPC['length']);
			$num = (int) $_GPC['num'];
			$date = (int) $_GPC['date'];
			if ($res['code_type'] == 0) 
			{
				$date = max(1, $date);
				$date = min($date, 30);
			}
			$endtime = ($date * 24 * 60 * 60) + time();
			$endtime = date('Y-m-d H:i:s', $endtime);
			echo intval($_GPC['loop']) . '&' . $dir_pre;
			while (0 < $num) 
			{
				if ($res['code_type'] === 1) 
				{
					$endtime = '2037-12-30 00:00:00';
				}
				pdo_insert('ewei_shop_exchange_code', array('groupid' => $id, 'uniacid' => $_W['uniacid'], 'endtime' => $endtime, 'type' => $res['code_type']));
				$rand_id = pdo_insertid();
				$rand = $this->getRandChar($length, $shuzi, $daxie, $xiaoxie);
				$key = $qianzhui . $rand;
				$serial = 'DH' . date('Ymd', time()) . $rand_id;
				if (empty($res['code_type'])) 
				{
					$scene = rand(100001, 2147483647);
				}
				else 
				{
					$scene = rand(1, 100000);
				}
				$exist = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('qrcode') . ' WHERE qrcid = :qrcid AND uniacid = :uniacid', array(':qrcid' => $scene, ':uniacid' => $_W['uniacid']));
				while (!(empty($exist))) 
				{
				}
				pdo_update('ewei_shop_exchange_code', array('key' => $key, 'scene' => $scene, 'serial' => $serial), array('id' => $rand_id));
				$insert = array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shopv2:exchange:goods', 'module' => 'reply', 'displayorder' => $rand_id, 'status' => 1);
				if (IMS_VERSION == '1.0') 
				{
					$insert['containtype'] = (($res == '0' ? 'news' : 'basic'));
					$insert['module'] = (($res == '0' ? 'news' : 'basic'));
				}
				pdo_insert('rule', $insert);
				$rid = pdo_insertid();
				pdo_query('UPDATE ' . tablename('ewei_shop_exchange_group') . ' SET total = total + 1 WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
				if ($res['reply_type'] == 0) 
				{
					$description = trim($res['content']);
					$description = str_replace('[兑换码]', $res['serial'], $description);
					$module = 'news';
					$arr = array('rid' => $rid, 'title' => trim($res['title_reply']), 'author' => 'EWEI_SHOP_V2', 'description' => $description, 'thumb' => trim($res['img']), 'content' => '', 'displayorder' => $rand_id, 'incontent' => 1, 'createtime' => time(), 'url' => mobileUrl('exchange/index', array('key' => $key, 'codetype' => 1, 'id' => $res['id']), true));
					pdo_insert('news_reply', $arr);
				}
				else 
				{
					$module = 'basic';
					$content = htmlspecialchars_decode($res['basic_content']);
					$content = str_replace('[兑换链接]', mobileUrl('exchange', array('key' => $key, 'codetype' => 1, 'id' => $res['id']), 1), $content);
					$arr = array('rid' => $rid, 'content' => $content);
					pdo_insert('basic_reply', $arr);
				}
				if (IMS_VERSION == '1.0') 
				{
					$module = 'reply';
				}
				pdo_insert('rule_keyword', array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'module' => $module, 'content' => md5($key), 'type' => 1, 'displayorder' => $rand_id, 'status' => 1));
				if ($res['code_type'] < 2) 
				{
					$expire = $date * 24 * 60 * 60;
					$token = WeAccount::token();
					$customMessageSendUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $token;
					if ($res['code_type'] == 0) 
					{
						$postJosnData = '{"expire_seconds": ' . $expire . ', "action_name": "QR_SCENE", ' . "\r\r" . '                    "action_info": {"scene": {"scene_id": ' . $scene . '}}}';
					}
					else 
					{
						$postJosnData = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": ' . $scene . '}}}';
					}
					$ch = curl_init($customMessageSendUrl);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postJosnData);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
					$data = curl_exec($ch);
					$ticket = json_decode($data, true);
					$qr_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket['ticket'];
					$dirname = '../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre;
					load()->func('file');
					mkdirs($dirname);
					$fileContents = file_get_contents($qr_url);
					file_put_contents($dirname . '/' . $key . '.png', $fileContents);
					fopen('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.zip', 'wr');
					$zip = new ZipArchive();
					$res = $zip->open('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.zip', ZipArchive::OVERWRITE);
					if ($res === true) 
					{
						$this->addFileToZip('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre, $zip);
						$zip->close();
					}
					else 
					{
						switch ($res) 
						{
							case ZipArchive: exit('File already exists.');
							break;
							case ZipArchive: exit('Zip archive inconsistent.');
							break;
							case ZipArchive: exit('Malloc failure.');
							break;
							case ZipArchive: exit('No such file.');
							break;
							case ZipArchive: exit('Not a zip archive.');
							break;
							case ZipArchive: exit('Can\'t open file.');
							break;
							case ZipArchive: exit('Read error.');
							break;
							case ZipArchive: exit('Seek error.');
							break;
							default: exit('Unknow Error');
							break;
						}
					}
					if (intval($_GPC['end']) == 1) 
					{
						$this->delDirAndFile($dirname);
					}
					pdo_update('ewei_shop_exchange_code', array('qrcode_url' => $qr_url), array('key' => $key, 'uniacid' => $_W['uniacid']));
				}
				else 
				{
					$qr_url = webUrl('exchange/goods/qr', array('key' => $key), 1);
					pdo_update('ewei_shop_exchange_code', array('qrcode_url' => $qr_url), array('key' => $key, 'uniacid' => $_W['uniacid']));
					if ($res['code_type'] == 2) 
					{
						$content_url = mobileUrl('exchange', array('key' => $key, 'codetype' => 1, 'id' => $res['id']), 1);
						$dirname = '../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre;
						load()->func('file');
						mkdirs($dirname);
						require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
						QRcode::png($content_url, $dirname . '/' . $serial . '.png', QR_ECLEVEL_L, 10, 3);
						fopen('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.zip', 'wr');
						$zip = new ZipArchive();
						if ($zip->open('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.zip', ZipArchive::OVERWRITE) === true) 
						{
							$this->addFileToZip('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre, $zip);
							$zip->close();
						}
						if (intval($_GPC['end']) == 1) 
						{
							$this->delDirAndFile($dirname);
						}
					}
					else 
					{
						$br = "\r\n";
						if (intval($_GPC['end']) == 1) 
						{
							$br = "\r";
						}
						if (!(file_exists('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt'))) 
						{
							$text = fopen('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt', 'w');
							fwrite($text, $key . '_' . $serial . $br);
							fclose($text);
						}
						else 
						{
							$text = fopen('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt', 'a+');
							fwrite($text, $key . '_' . $serial . $br);
							fclose($text);
						}
						if (intval($_GPC['end']) == 1) 
						{
							require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
							require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Writer/Excel5.php';
							$excel = new PHPExcel();
							$writer = new PHPExcel_Writer_Excel5($excel);
							$excel->getActiveSheet()->setCellValue('A1', '兑换码');
							$excel->getActiveSheet()->setCellValue('B1', '编号');
							$excel->getActiveSheet()->setCellValue('C1', '所属兑换活动');
							$file = fopen('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt', 'r');
							$line = 2;
							if (empty($file)) 
							{
								exit();
							}
							while (!(feof($file))) 
							{
								$v0 = fgets($file);
								$v = explode('_', $v0);
								$excel->getActiveSheet()->setCellValue('A' . $line, ' ' . $v[0])->setCellValue('B' . $line, $v[1])->setCellValue('C' . $line, $res['title']);
								++$line;
							}
							fclose($file);
							$writer->save('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.xls');
							unlink('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt');
						}
					}
				}
				$qrcode = array('uniacid' => $_W['uniacid'], 'acid' => $_W['uniacid'], 'type' => 'scene', 'extra' => 0, 'qrcid' => $scene, 'name' => '商品兑换', 'keyword' => md5($key), 'model' => 1, 'ticket' => $ticket['ticket'], 'url' => $ticket['url'], 'expire' => $ticket['expire_seconds'], 'subnum' => 0, 'createtime' => time(), 'status' => 1, 'scene_str' => '');
				pdo_insert('qrcode', $qrcode);
				$key = NULL;
				$num -= 1;
			}
		}
		else 
		{
			include $this->template();
		}
	}
	private function getRandChar($length, $shuzi = 0, $daxie = 1, $xiaoxie = 0) 
	{
		$str = NULL;
		$strPol = '';
		if (!(empty($shuzi))) 
		{
			$strPol = '0123456789';
		}
		if (!(empty($daxie))) 
		{
			$strPol .= 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
		}
		if (!(empty($xiaoxie))) 
		{
			$strPol .= 'abcdefghijklmnopqrstuvwxyz';
		}
		$max = strlen($strPol) - 1;
		$i = 0;
		while ($i < $length) 
		{
			$str .= $strPol[rand(0, $max)];
			++$i;
		}
		return $str;
	}
	public function status() 
	{
		global $_W;
		global $_GPC;
		$status = intval($_GPC['status']);
		$newstatus = intval($_GPC['newstatus']);
		$status2 = ($status * -1) + 1;
		$id = intval($_GPC['id']);
		$key = trim($_GPC['key']);
		$ajax = intval($_GPC['ajax']);
		$res = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE `key`=:key AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid'], ':key' => $key));
		if (empty($key) && !(empty($id))) 
		{
			pdo_update('ewei_shop_exchange_group', array('status' => $status2), array('id' => $id, 'uniacid' => $_W['uniacid'], 'status' => $status));
			show_json(1, '成功');
		}
		else if (!(empty($key)) && empty($id) && !(empty($ajax))) 
		{
			pdo_update('ewei_shop_exchange_code', array('status' => $newstatus), array('key' => $key, 'status' => $status));
			if ($newstatus == 1) 
			{
				pdo_update('ewei_shop_exchange_record', array('key' => $key . 'plus' . time()), array('key' => $key, 'uniacid' => $_W['uniacid']));
				$group = pdo_fetch('SELECT groupid FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE `key`= :key AND uniacid = :uniacid', array(':key' => $key, ':uniacid' => $_W['uniacid']));
				pdo_query('UPDATE ' . tablename('ewei_shop_exchange_group') . ' SET `total` = `total`+1 WHERE id = :id AND uniacid = :uniacid', array(':id' => $group['groupid'], ':uniacid' => $_W['uniacid']));
			}
			show_json(1, '保存成功');
		}
		else 
		{
			include $this->template();
		}
	}
	public function destroy() 
	{
		global $_GPC;
		global $_W;
		$id = intval($_GPC['id']);
		$ids = $_GPC['ids'];
		if (is_array($ids)) 
		{
		}
		else 
		{
			$ids[0] = $id;
		}
		foreach ($ids as $k => $value ) 
		{
			$res_arr = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE id = :id AND uniacid = :uniacid', array(':id' => $value, ':uniacid' => $_W['uniacid']));
			$key = $res_arr['key'];
			if (!(empty($key)) && !(empty($value))) 
			{
				$a = pdo_delete('ewei_shop_exchange_code', array('id' => $value, 'key' => $key, 'uniacid' => $_W['uniacid']));
				$t1 = tablename('qrcode');
				$t2 = tablename('qrcode_stat');
				$res = pdo_query('DELETE ' . $t1 . ',' . $t2 . ' FROM ' . $t1 . ' LEFT JOIN ' . $t2 . ' ON ' . $t1 . '.id = ' . $t2 . '.qid ' . "\r\r" . '                    WHERE ' . $t1 . '.keyword = :key', array(':key' => md5($key)));
				$b = pdo_delete('rule', array('displayorder' => $value, 'uniacid' => $_W['uniacid']));
				$c = pdo_delete('rule_keyword', array('content' => $key, 'uniacid' => $_W['uniacid']));
				$d = pdo_delete('news_reply', array('displayorder' => $value));
				if ($res_arr['status'] == 1) 
				{
					pdo_query('UPDATE ' . tablename('ewei_shop_exchange_group') . ' SET `total` = `total` - 1 WHERE id = :id AND uniacid = :uniacid', array(':id' => $res_arr['groupid'], ':uniacid' => $_W['uniacid']));
				}
			}
		}
		show_json(1, '删除成功');
	}
	public function setting() 
	{
		global $_W;
		global $_GPC;
		$id = $_GPC['id'];
		if ($_W['ispost']) 
		{
			$reply_type = intval($_GPC['reply']);
			if ($reply_type == 0) 
			{
				$title = trim($_GPC['balancetitle']);
				$img = trim($_GPC['balanceimg']);
				$content = trim($_GPC['balancecontent']);
				$data = array('title_reply' => $title, 'img' => $img, 'content' => $content, 'reply_type' => $reply_type);
			}
			else 
			{
				$basic_content = trim($_GPC['content']);
				$data = array('basic_content' => $basic_content, 'reply_type' => $reply_type);
			}
			pdo_update('ewei_shop_exchange_group', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
			show_json(1, '保存成功');
		}
		else 
		{
			$all = array();
			$hasoption = array();
			if (!(empty($id))) 
			{
				$setting = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE id=:id AND uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
				$goods = json_decode($setting['goods'], true);
				$banner = json_decode($setting['banner'], 1);
				if (!(empty($banner))) 
				{
					foreach ($banner as $k => $v ) 
					{
						$banner[$k] = urldecode($v);
					}
				}
				if ($goods['goods'] != false) 
				{
					foreach ($goods['goods'] as $k => $v ) 
					{
						$all[$k] = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE uniacid = :uniacid AND deleted = 0 AND hasoption = 0 AND id = :id', array(':id' => $v, ':uniacid' => $_W['uniacid']));
					}
				}
				if ($goods['option'] != false) 
				{
					foreach ($goods['option'] as $ke => $v ) 
					{
						$hasoption[$ke] = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE uniacid = :uniacid AND deleted = 0 AND hasoption = 1 AND id = :id', array(':id' => $ke, ':uniacid' => $_W['uniacid']));
					}
				}
			}
			if (p('diypage')) 
			{
				$exchangePages = p('diypage')->getPageList('allpage', ' and type=8 ');
				$exchangePages = $exchangePages['list'];
			}
		}
		include $this->template();
	}
	public function down() 
	{
		global $_W;
		global $_GPC;
		$ids = $_GPC['ids'];
		$key = $_GPC['key'];
		$res = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE `key`=:key AND `uniacid`=:uniacid', array(':key' => $key, ':uniacid' => $_W['uniacid']));
		$filename = $res['qrcode_url'];
		header('Content-type:  application/octet-stream ');
		$date = date('Ymd-H:i:m');
		header('Cache-Control: max-age=0');
		header('Content-Disposition:  attachment;  filename= ' . $res['serial'] . '.jpg');
		$size = readfile($filename);
		header('Accept-Length: ' . $size);
	}
	public function search() 
	{
		global $_W;
		global $_GPC;
		$page = max(1, intval($_GPC['page']));
		$psize = 20;
		$pstart = $psize * ($page - 1);
		$like = '';
		$keyword = trim($_GPC['keyword']);
		$like = 'AND `title` LIKE \'%' . $keyword . '%\'';
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE mode = 1 AND uniacid=:uniacid  ' . $like . ' ORDER BY id DESC LIMIT ' . $pstart . ',' . $psize, array(':uniacid' => $_W['uniacid']));
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE mode = 1 AND uniacid=:uniacid  ' . $like, array(':uniacid' => $_W['uniacid']));
		$allEnd = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) <' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$allStart = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE unix_timestamp(endtime) >' . time() . ' AND unix_timestamp(starttime) <' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$allNostart = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE  unix_timestamp(starttime) >' . time() . ' AND mode = 1 AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		$pager = pagination($count, $page, $psize);
		include $this->template();
	}
	public function codesearch() 
	{
		global $_W;
		global $_GPC;
		$page = max(1, $_GPC['page']);
		@session_start();
		$psize = intval($_GPC['psize']);
		if (!(empty($psize))) 
		{
			$_SESSION['psize'] = $psize;
		}
		else if (!(empty($_SESSION['psize']))) 
		{
			$psize = $_SESSION['psize'];
		}
		else 
		{
			$psize = 100;
		}
		$pstart = $psize * ($page - 1);
		$id = $_GPC['id'];
		$keyword = trim($_GPC['keyword']);
		$start = trim($_GPC['start']);
		$end = trim($_GPC['end']);
		if (!(empty($keyword))) 
		{
			$condition = 'AND `serial` LIKE \'%' . $keyword . '%\'';
		}
		if (!(empty($start)) && !(empty($end))) 
		{
			$start = strtotime($start . ' 00:00:00');
			$end = strtotime($end . ' 23:59:59');
			$condition .= ' AND unix_timestamp(endtime) >=' . $start . ' AND unix_timestamp(endtime) <=' . $end;
		}
		$res = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . 'WHERE id = :id AND uniacid = :uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid ' . $condition . ' ORDER BY id DESC LIMIT ' . $pstart . ',' . $psize, array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid ' . $condition, array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dno = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 1 AND unix_timestamp(endtime)>' . time(), array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dyet = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status = 2', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$dend = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE groupid = :id AND uniacid=:uniacid AND status!=2 AND unix_timestamp(endtime)<=' . time(), array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$pager = pagination($count, $page, $psize);
		include $this->template();
	}
	public function qr() 
	{
		global $_W;
		global $_GPC;
		$key = trim($_GPC['key']);
		$id = intval($_GPC['id']);
		$url = mobileUrl('exchange', array('key' => $key, 'codetype' => 1, 'id' => $id), 1);
		require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
		QRcode::png($url, false, QR_ECLEVEL_L, 10, 3);
		exit();
	}
	public function ajaxlist() 
	{
		global $_W;
		global $_GPC;
		$keyword = trim($_GPC['keyword']);
		if (empty($keyword)) 
		{
			$keyword = NULL;
		}
		$id = intval($_GPC['id']);
		$all = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE uniacid = :uniacid AND deleted = 0 ' . "\r\r" . '        AND hasoption = 0', array(':uniacid' => $_W['uniacid']));
		$hasoption = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE uniacid = :uniacid AND deleted = 0 ' . "\r\r" . '        AND hasoption = 1', array(':uniacid' => $_W['uniacid']));
		$s = 0;
		$res = array();
		foreach ($all as $k => $v ) 
		{
			$jian = strpos($v['title'], $keyword);
			if ($jian !== false) 
			{
				$res[$s] = $v;
				unset($all[$k]);
				++$s;
			}
		}
		$t = 0;
		$res2 = array();
		foreach ($hasoption as $k => $v ) 
		{
			$jian = strpos($v['title'], $keyword);
			if ($jian !== false) 
			{
				$res2[$t] = $v;
				unset($hasoption[$k]);
				++$t;
			}
		}
		$i = 0;
		foreach ($hasoption as $e => $item ) 
		{
			$option[$i] = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods_option') . ' WHERE goodsid = :id', array(':id' => $item['id']));
			$option[$i][0]['gt'] = $item['title'];
			$option[$i][0]['pic'] = $item['thumb'];
			++$i;
		}
		$loop = count($option);
		$j = 0;
		foreach ($res2 as $e => $item ) 
		{
			$option2[$j] = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods_option') . ' WHERE goodsid = :id', array(':id' => $item['id']));
			$option2[$j][0]['gt'] = $item['title'];
			$option2[$j][0]['pic'] = $item['thumb'];
			++$j;
		}
		$loop2 = count($option2);
		if (!(empty($id))) 
		{
			$setting = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE id=:id AND uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
			$goods = json_decode($setting['goods'], true);
		}
		include $this->template();
	}
	public function addFileToZip($path, $zip) 
	{
		$handler = opendir($path);
		while (($filename = readdir($handler)) !== false) 
		{
			if (($filename != '.') && ($filename != '..')) 
			{
				if (is_dir($path . '/' . $filename)) 
				{
					$this->addFileToZip($path . '/' . $filename, $zip);
				}
				else 
				{
					$zip->addFile($path . '/' . $filename);
				}
			}
		}
		@closedir($path);
	}
	public function delDirAndFile($dirName) 
	{
		if ($handle = opendir($dirName)) 
		{
			while (false !== $item = readdir($handle)) 
			{
				if (($item != '.') && ($item != '..')) 
				{
					if (is_dir($dirName . '/' . $item)) 
					{
						delDirAndFile($dirName . '/' . $item);
					}
					else 
					{
						unlink($dirName . '/' . $item);
					}
				}
			}
			@closedir($handle);
			rmdir($dirName);
		}
	}
	public function delzip() 
	{
		global $_GPC;
		global $_W;
		$filename = trim($_GPC['filename']);
		if (unlink(IA_ROOT . '/addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $filename . '.zip')) 
		{
			exit('1');
		}
	}
	public function code_type() 
	{
		global $_GPC;
		global $_W;
		$id = intval($_GPC['id']);
		$res = pdo_fetch('SELECT code_type FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE id = :id AND uniacid = :uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		echo $res['code_type'];
	}
	public function deltxt() 
	{
		global $_GPC;
		global $_W;
		$filename = trim($_GPC['filename']);
		$title = trim($_GPC['title']);
		if (unlink(IA_ROOT . '/addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $filename . '.xls')) 
		{
			exit('1');
		}
	}
	public function banner() 
	{
		global $_GPC;
		global $_W;
		$banner = $_GPC['banner'];
		$rule = $_GPC['rule'];
		$id = intval($_GPC['id']);
		if (6 < count($banner)) 
		{
			show_json(0, '最多可以设置6张幻灯片');
		}
		if (empty($banner)) 
		{
			$jsonBanner = '';
		}
		else 
		{
			foreach ($banner as $k => $v ) 
			{
				$banner[$k] = urlencode($v);
			}
			$jsonBanner = json_encode($banner);
		}
		pdo_update('ewei_shop_exchange_group', array('banner' => $jsonBanner, 'rule' => $rule, 'diypage' => intval($_GPC['diypage'])), array('uniacid' => $_W['uniacid'], 'id' => $id));
		show_json(1, '保存成功');
	}
	public function selector() 
	{
		global $_GPC;
		global $_W;
		$id = intval($_GPC['id']);
		$page = intval($_GPC['page']);
		$page = max(1, $page);
		$pstart = ($page * 8888) - 8888;
		$keyword = trim($_GPC['keyword']);
		if (!(empty($keyword))) 
		{
			$condition = ' AND title LIKE \'%' . $keyword . '%\' ';
		}
		$res = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE uniacid = :uniacid AND deleted = 0 ' . $condition . ' LIMIT ' . $pstart . ',8888', array(':uniacid' => $_W['uniacid']));
		if (p('seckill')) 
		{
			foreach ($res as $k => $v ) 
			{
				if (p('seckill')->getSeckill($v['id'])) 
				{
					unset($res[$k]);
				}
			}
		}
		include $this->template();
	}
	public function optionset() 
	{
		global $_GPC;
		global $_W;
		$id = intval($_GPC['id']);
		$gtype = intval($_GPC['gtype']);
		$groupid = intval($_GPC['groupid']);
		$checkbox = $_GPC['checkbox'];
		$selected = trim($_GPC['selected']);
		$select = array();
		if (!(empty($selected))) 
		{
			$select = explode('_', $selected);
			$select = array_filter($select);
		}
		if ($gtype == 1) 
		{
			$res = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods_option') . ' WHERE goodsid = :goodsid AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':goodsid' => $id));
		}
		else 
		{
			$res = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE id = :id AND uniacid = :uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		}
		include $this->template();
	}
	public function addreply() 
	{
		global $_GPC;
		global $_W;
		$groupid = intval($_GPC['id']);
		$group = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE id = :id AND uniacid = :uniacid ', array(':id' => $groupid, ':uniacid' => $_W['uniacid']));
		if (empty($group)) 
		{
			show_json(0, '保存失败,兑换组不存在');
		}
		$url = mobileUrl('exchange.index', array('codetype' => 1, 'id' => $groupid), 1);
		$reply['keyword'] = trim($_GPC['keyword']);
		if (empty($reply['keyword'])) 
		{
			show_json(0, '触发关键字不能为空!');
		}
		else 
		{
			$is_exist = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('rule_keyword') . ' WHERE content = :content AND uniacid = :uniacid AND content != :keyword', array(':content' => $reply['keyword'], ':uniacid' => $_W['uniacid'], ':keyword' => $group['reply_keyword']));
			if (!(empty($is_exist))) 
			{
				show_json(0, '保存失败，这个触发关键字已经存在,请更换触发关键字！');
			}
		}
		$reply['title'] = trim($_GPC['balancetitle']);
		$reply['thumb'] = trim($_GPC['balanceimg']);
		$reply['description'] = trim($_GPC['balancecontent']);
		$reply['replystatus'] = intval($_GPC['replystatus']);
		if (!(empty($group['keyword_reply']))) 
		{
			$rule = pdo_fetch('SELECT id FROM ' . tablename('rule') . ' WHERE id = :id AND uniacid = :uniacid', array(':id' => $group['keyword_reply'], ':uniacid' => $_W['uniacid']));
			if (empty($rule['id'])) 
			{
				pdo_insert('rule', array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shopv2:exchange', 'module' => 'ewei_shopv2', 'status' => $reply['replystatus']));
				$rid = pdo_insertid();
				pdo_insert('rule_keyword', array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'module' => 'news', 'content' => $reply['keyword'], 'type' => 1, 'status' => 1));
				pdo_insert('news_reply', array('rid' => $rid, 'title' => $reply['title'], 'author' => 'EWEI_SHOP_V2', 'description' => $reply['description'], 'thumb' => $reply['thumb'], 'url' => $url, 'incontent' => 1, 'createtime' => time(), 'parent_id' => 0));
				pdo_update('ewei_shop_exchange_group', array('keyword_reply' => $rid, 'title_reply' => $reply['title'], 'img' => $reply['thumb'], 'content' => $reply['description'], 'reply_keyword' => $reply['keyword'], 'reply_status' => $reply['replystatus']), array('id' => $groupid, 'uniacid' => $_W['uniacid']));
			}
			else 
			{
				pdo_update('rule_keyword', array('module' => 'news', 'content' => $reply['keyword'], 'type' => 1, 'status' => $reply['replystatus']), array('uniacid' => $_W['uniacid'], 'rid' => $group['keyword_reply']));
				pdo_update('news_reply', array('title' => $reply['title'], 'author' => 'EWEI_SHOP_V2', 'description' => $reply['description'], 'thumb' => $reply['thumb'], 'url' => $url, 'incontent' => 1, 'createtime' => time(), 'parent_id' => 0), array('rid' => $group['keyword_reply']));
				pdo_update('ewei_shop_exchange_group', array('title_reply' => $reply['title'], 'img' => $reply['thumb'], 'content' => $reply['description'], 'reply_keyword' => $reply['keyword'], 'reply_status' => $reply['replystatus']), array('id' => $groupid, 'uniacid' => $_W['uniacid']));
			}
		}
		else 
		{
			pdo_insert('rule', array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shopv2:exchange', 'module' => 'ewei_shopv2', 'status' => $reply['replystatus']));
			$rid = pdo_insertid();
			pdo_insert('rule_keyword', array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'module' => 'news', 'content' => $reply['keyword'], 'type' => 1, 'status' => 1));
			pdo_insert('news_reply', array('rid' => $rid, 'title' => $reply['title'], 'author' => 'EWEI_SHOP_V2', 'description' => $reply['description'], 'thumb' => $reply['thumb'], 'url' => $url, 'incontent' => 1, 'createtime' => time(), 'parent_id' => 0));
			pdo_update('ewei_shop_exchange_group', array('keyword_reply' => $rid, 'title_reply' => $reply['title'], 'img' => $reply['thumb'], 'content' => $reply['description'], 'reply_keyword' => $reply['keyword'], 'reply_status' => $reply['replystatus']), array('id' => $groupid, 'uniacid' => $_W['uniacid']));
		}
		show_json(1, '保存成功');
	}
	public function import() 
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		if ($_W['ispost']) 
		{
			if ($_FILES['f2']['error']) 
			{
				show_json(0, '文件上传失败');
			}
			else 
			{
				if ($_FILES['f2']['type'] != 'application/vnd.ms-excel') 
				{
					show_json(0, '只支持xls文件，请勿上传其他类型的文件');
				}
				else if ($_FILES['f2']['size'] <= 0) 
				{
					show_json(0, '文件是空的,上传失败');
				}
				$path = '../addons/ewei_shopv2/data/upload/exchange/' . $_W['uniacid'] . '/' . time() . md5($_FILES['f2']['tmp_name']) . '.xls';
				load()->func('file');
				file_move($_FILES['f2']['tmp_name'], $path);
				require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
				$reader = PHPExcel_IOFactory::createReader('Excel5');
				$PHPExcel = $reader->load($path);
				$sheet = $PHPExcel->getSheet(0);
				$highestRow = $sheet->getHighestRow();
				if (1000 < $highestRow) 
				{
					show_json(0, '你的兑换码超过了1000条,请单次上传不要超过1000条!');
				}
				$endtime = trim($_GPC['endtime']);
				$dir_pre = time() . md5($this->getRandChar(10));
				$res = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_group') . ' WHERE id =:id AND uniacid =:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
				if (empty($res)) 
				{
					show_json(0, '兑换组不存在');
				}
				$i = 1;
				while ($i <= $highestRow) 
				{
					$key = $sheet->getCell('A' . $i)->getValue();
					$key = trim($key);
					$exist = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_exchange_code') . ' WHERE `key`= :key', array(':key' => $key));
					$pattern = '/^[0-9a-zA-Z_]+$/';
					if (!(preg_match($pattern, $key))) 
					{
						continue;
					}
					if (!(empty($exist))) 
					{
						continue;
					}
					pdo_insert('ewei_shop_exchange_code', array('groupid' => $res['id'], 'uniacid' => $_W['uniacid'], 'endtime' => $endtime, 'type' => $res['code_type'], 'key' => $key));
					$rand_id = pdo_insertid();
					$serial = 'DH' . date('Ymd', time()) . $rand_id;
					pdo_update('ewei_shop_exchange_code', array('serial' => $serial), array('key' => $key, 'uniacid' => $_W['uniacid']));
					pdo_query('UPDATE ' . tablename('ewei_shop_exchange_group') . ' SET total = total + 1 WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
					$br = "\r\n";
					if (!(file_exists('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt'))) 
					{
						$text = fopen('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt', 'w');
						fwrite($text, $key . '_' . $serial . $br);
						fclose($text);
					}
					else 
					{
						$text = fopen('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt', 'a+');
						fwrite($text, $key . '_' . $serial . $br);
						fclose($text);
					}
					++$i;
				}
				require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
				require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Writer/Excel5.php';
				$excel = new PHPExcel();
				$writer = new PHPExcel_Writer_Excel5($excel);
				$excel->getActiveSheet()->setCellValue('A1', '兑换码');
				$excel->getActiveSheet()->setCellValue('B1', '编号');
				$excel->getActiveSheet()->setCellValue('C1', '所属兑换活动');
				if (is_file('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt')) 
				{
					$file = fopen('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt', 'r');
				}
				$line = 2;
				if (empty($file)) 
				{
					exit();
				}
				while (!(feof($file))) 
				{
					$v0 = fgets($file);
					$v = explode('_', $v0);
					$excel->getActiveSheet()->setCellValue('A' . $line, ' ' . $v[0])->setCellValue('B' . $line, $v[1])->setCellValue('C' . $line, $res['title']);
					++$line;
				}
				fclose($file);
				$writer->save('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.xls');
				unlink('../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/exchange/' . $dir_pre . '.txt');
				show_json(1, '上传成功');
			}
		}
		else 
		{
			include $this->template();
		}
	}
}
?>