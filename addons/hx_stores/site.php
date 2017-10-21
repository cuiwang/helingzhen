<?php
/**
 * 门店导航模块微站定义
 *
 * @author 华轩科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_storesModuleSite extends WeModuleSite {

	public $table_stores = 'hx_stores';

	public function doMobileList() {
		//这个操作被定义用来呈现 功能封面
		global $_W, $_GPC;
		if ($_GPC['search_name']) {
			$where = ' AND title LIKE "%'.$_GPC['search_name'].'%"';
		}
		//直接过来列表
		$total=pdo_fetchcolumn ("SELECT count(id) FROM ".tablename($this->table_stores)." WHERE uniacid = {$_W['uniacid']}" .$where);		
		$totalpage=ceil($total/10);
		$p= max(1, intval($_GPC['p']));
		$start=($p-1)*10;
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_stores)." WHERE uniacid = {$_W['uniacid']}" .$where." limit ".$start.",10" );		
		//注意分页
		include $this->template('list');
	}
	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		if (!empty($_GPC['industry_1'])) {
			$condition .= " AND industry1 = '{$_GPC['industry_1']}'";
		}
		if (!empty($_GPC['industry_2'])) {
			$condition .= " AND industry2 = '{$_GPC['industry_2']}'";
		}
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_stores)." WHERE uniacid = '{$_W['uniacid']}' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_stores) . " WHERE uniacid = '{$_W['uniacid']}' $condition");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('list');	
	}
	public function doWebPost() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		if (!empty($id)) {
			$item = pdo_fetch("SELECT * FROM ".tablename($this->table_stores)." WHERE id = :id" , array(':id' => $id));
			if (empty($item)) {
				message('抱歉，位置不存在或是已经删除！', '', 'error');
			}
		}
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入位置名称！');
			}
			$data = array(
				'uniacid' => $_W['uniacid'],
				'title' => $_GPC['title'],
				'thumb' => $_GPC['thumb'],
				'content' => htmlspecialchars_decode($_GPC['content']),
				'phone' => $_GPC['phone'],
				'qq' => $_GPC['qq'],
				'province' => $_GPC['reside']['province'],
				'city' => $_GPC['reside']['city'],
				'dist' => $_GPC['reside']['district'],
				'address' => $_GPC['address'],
				'lng' => $_GPC['baidumap']['lng'],
				'lat' => $_GPC['baidumap']['lat'],
				'icon' => $_GPC['icon'],
				'industry1' => $_GPC['industry_1'],
				'industry2' => $_GPC['industry_2'],
				'createtime' => TIMESTAMP,
			);
			if (empty($id)) {
				pdo_insert($this->table_stores, $data);
			} else {
				unset($data['createtime']);
				pdo_update($this->table_stores, $data, array('id' => $id));
			}
			message('位置信息更新成功！', $this->createWebUrl('list'), 'success');
		}
		load()->func('tpl');
		include $this->template('post');
	}

	public function doWebDelete() {
		global $_GPC;
		$id = intval($_GPC['id']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->table_stores)." WHERE id = :id" , array(':id' => $id));
		if (empty($item)) {
			message('抱歉，位置不存在或是已经删除！', '', 'error');
		}
		pdo_delete($this->table_stores, array('id' => $item['id']));
		message('删除成功！', referer(), 'success');
	}

	public function doMobileDetail() {
		global $_W, $_GPC;
		$ak = isset($this->module['config']['ak']) ? $this->module['config']['ak'] : 'pyM8Y0Y03esdDSzxneekoReX';
		$id = intval($_GPC['id']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->table_stores)." WHERE id = :id", array(':id' => $id));
		if (empty($item)) {
			message('抱歉，该位置不存在或是已经被删除！');
		}
		include $this->template('detail');
	}

	public function doMobileMap() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->table_stores)." WHERE id = :id", array(':id' => $id));
		if (empty($item)) {
			message('抱歉，该商家不存在或是已经被删除！');
		}
		include $this->template('map');
	}

	public function doMobilearea(){
		global $_W, $_GPC;
		$ak = isset($this->module['config']['ak']) ? $this->module['config']['ak'] : 'pyM8Y0Y03esdDSzxneekoReX';
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_stores)." WHERE uniacid = {$_W['uniacid']}");	
		$str = "";
		foreach ($list as $k=>$v) {
			$str .= "[{$v['lng']},{$v['lat']},\"<h3><a href=\\\"{$this->createMobileUrl('detail',array('id'=>$v['id']))}\\\">{$v['title']}</a></h3><hr />电话：<a href=\\\"tel:{$v['phone']}\\\">{$v['phone']}</a><br />地址：<a href=\\\"{$this->createMobileUrl('detail',array('id'=>$v['id']))}\\\">{$v['address']}</a>\",'{$_W['attachurl']}{$v['icon']}'],";
		}
		include $this->template('area');
	}

}