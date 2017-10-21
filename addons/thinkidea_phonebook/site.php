<?php
/**
 * 电话本模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class Thinkidea_phonebookModuleSite extends WeModuleSite {
	//区域表
	private $zone_table = 'thinkidea_phonebook_zone';
	
	//分类表
	private $category_table = 'thinkidea_phonebook_category';
	
	//信息表
	private $info_table = 'thinkidea_phonebook_info';
	
	//weid
	public $weid;
	
	public function __construct(){
		global $_W;
		$this->weid = $_W['uniacid'];
	}
	
	
	//=======================================Mobile===================================
	
	/**
	 * 首页
	 */
	public function doMobileIndex(){
		global $_GPC,$_W;
		
		//=======================区域===================
		//取父类
		$zoneparents = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE  weid = :weid AND parent_id = 0 AND isshow = 1 ORDER BY display ASC", array(":weid" => $this->weid));
		$tmp = array();
		foreach ($zoneparents AS $parent){
			array_push($tmp, $parent['id']);
		}
		$pids = implode(",", $tmp);
		unset($tmp);
		if(!empty($pids)){
			//取子类
			$subs = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
			foreach ($zoneparents AS $key => $parent){
				foreach ($subs AS  $k => $sub){
					if($sub['parent_id'] == $parent['id']){
						$zoneparents[$key]['sub'][$k] = $sub;
					}
				}
			}
		}
		//======================分类=====================
		//取父类
		$parents = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE weid = :weid AND parent_id = 0 AND isshow = 1 ORDER BY display ASC", array(":weid" => $this->weid));
		$tmp = array();
		foreach ($parents AS $parent){
			array_push($tmp, $parent['id']);
		}
		$pids = implode(",", $tmp);
		unset($tmp);
		if(!empty($pids)){
			//取子类
			$subs = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
			foreach ($parents AS $key => $parent){
				foreach ($subs AS  $k => $sub){
					if($sub['parent_id'] == $parent['id']){
						$parents[$key]['sub'][$k] = $sub;
					}
				}
			}
		}
		//一行几列
		$colspan = $this->module['config']['colspan'];
		
		include $this->template('home_index');
	}
	
	/**
	 * 信息列表
	 */
	public function doMobileListInfo(){
		global $_GPC,$_W;
		
		$cid = intval($_GPC['cid']);
		$zoneid = intval($_GPC['zoneid']);
		$keyword = trim($_GPC['keyword']);
		$showkeyword = empty($_GPC['keyword']) ? '' : '"'.$keyword.'"';
		
		$where = '';
	
                if(!empty($_GPC['cid'])){
                    $cid =intval($_GPC['cid']);
                    $cate = pdo_fetch("SELECT * FROM ".tablename($this->category_table)." WHERE id = :cid LIMIT 1", array(":cid" => $cid), 2);
                    if(!empty($cate) && empty($cate['parent_id'])){
                           $childs =  pdo_fetchall("SELECT id FROM ".tablename($this->category_table)." WHERE parent_id = :cid", array(":cid" => $cid));
                            $ids = array($cid);
                            foreach($childs as $c){
                                $ids[] = $c['id'];
                            }
                           $where.=' AND category in ('. implode(',',$ids) .' ) ';
                    }
                    else if(!empty($cate)){
                            $where.=' AND category ='. $cid;
                    }
                   
                }
                if(!empty($_GPC['zoneid'])){
                    $zoneid=intval($_GPC['zoneid']);
                    $zone = pdo_fetch("SELECT * FROM ".tablename($this->zone_table)." WHERE id = :zoneid LIMIT 1", array(":zoneid" => $zoneid), 2);
                    if(!empty($zone) && empty($zone['parent_id'])){
                           $childs =  pdo_fetchall("SELECT id FROM ".tablename($this->zone_table)." WHERE parent_id = :zoneid", array(":zoneid" => $cid));
                           $ids = array($zoneid);
                           foreach($childs as $c){
                               $ids[] = $c['id'];
                           }
                           $where.=' AND zone in ('. implode(',',$ids) .' ) ';
                    }
                    else if(!empty($zone)){
                            $where.=' AND zone ='. $cid;
                    }
                }
                
                //$where .= empty($_GPC['cid']) ? '' : ' AND category ='. intval($_GPC['cid']);
//		$where .= empty($_GPC['zoneid']) ? '' : ' AND zone ='. intval($_GPC['zoneid']);
		
                
                                    $where .= empty($_GPC['keyword']) ? '' : ' AND name LIKE \'%'.trim($_GPC['keyword']).'%\'';
// 		echo $where;
		
		//=======区域名========
		//$zonename = pdo_fetchcolumn("SELECT * FROM ".tablename($this->zone_table)." WHERE id = :zoneid LIMIT 1", array(":zoneid" => $zoneid), 2);
                $zonename = empty($zone['name'])?'':$zone['name'];
		//=======分类名========
		//$cname = pdo_fetchcolumn("SELECT * FROM ".tablename($this->category_table)." WHERE id = :cid LIMIT 1", array(":cid" => $cid), 2);
                $cname = empty($cate['name'])?'':$cate['name'];
		
		//==================主体列表=================
		$lists = pdo_fetchall("SELECT * FROM ".tablename($this->info_table)." WHERE weid = :weid {$where} ", array(":weid" => $this->weid));
		$count = count($lists);
		
		//==================区域=====================
		//取父类
		$zoneparents = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE  weid = :weid AND parent_id = 0 ORDER BY display ASC", array(":weid" => $this->weid));
		$tmp = array();
		foreach ($zoneparents AS $parent){
			array_push($tmp, $parent['id']);
		}
		$pids = implode(",", $tmp);
		unset($tmp);
		if(!empty($pids)){
			//取子类
			$subs = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
			foreach ($zoneparents AS $key => $parent){
				foreach ($subs AS  $k => $sub){
					if($sub['parent_id'] == $parent['id']){
						$zoneparents[$key]['sub'][$k] = $sub;
					}
				}
			}		
		}
		//=====================分类================
		//取父类
		$parents = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE weid = :weid AND parent_id = 0 AND isshow = 1 ORDER BY display ASC", array(":weid" => $this->weid));
		$tmp = array();
		foreach ($parents AS $parent){
			array_push($tmp, $parent['id']);
		}
		$pids = implode(",", $tmp);
		unset($tmp);
		
		if(!empty($pids)){
			//取子类
			$subs = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
			foreach ($parents AS $key => $parent){
				foreach ($subs AS  $k => $sub){
					if($sub['parent_id'] == $parent['id']){
						$parents[$key]['sub'][$k] = $sub;
					}
			}
		}
		}
		include $this->template('home_list');
	}

	
	/**
	 * 信息展示
	 */
	public function doMobileShowInfo(){
		global $_GPC,$_W;

		$info = pdo_fetch("SELECT * FROM ".tablename($this->info_table)." WHERE id = :id LIMIT 1", array(":id" => intval($_GPC['infoid'])));
		$info['dateline'] = date("Y-m-d", $info['dateline']);
		$coordinate = json_decode($info['coordinate']);
		$lat = $coordinate->lat;
		$lng = $coordinate->lng;
		//是否开启地图
		$isopenmap = $this->module['config']['isopenmap'];
		
		include $this->template('show');
	}
	
	
	//=======================================Web管理后台===============================
	
	/**
	 * 区域管理
	 */
	public function doWebZone() {
		global $_GPC,$_W;
		
		if (checksubmit('save_zone')) {
			$data = $_GPC['data'];
			$data['weid'] = $this->weid;
			$data['dateline'] = time();
				
			if(isset($_GPC['cid']) && !empty($_GPC['cid'])){
				$cid = intval($_GPC['cid']);
				if(pdo_update($this->zone_table, $data, array('id' => $cid))){
					message('操作成功',$this->createWebUrl('Zone'),'success');
				}else {
					message('操作失败', $this->createWebUrl('Zone'),'error');
				}
			}else {
				if(pdo_insert($this->zone_table, $data)){
					message('操作成功',$this->createWebUrl('Zone'),'success');
				}else {
					message('操作失败', $this->createWebUrl('Zone'),'error');
				}
			}
		}else {
			$op = isset($_GPC['op']) ? $_GPC['op'] : 'display';
			
			//取父类
			$parents = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE weid = :weid AND parent_id = 0 ORDER BY display ASC", array(":weid" => $this->weid));
			$tmp = array();
			foreach ($parents AS $parent){
				array_push($tmp, $parent['id']);
			}
			$pids = implode(",", $tmp);
			unset($tmp);

			if(!empty($pids)){
				//取子类
				$subs = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
				foreach ($parents AS $key => $parent){
					foreach ($subs AS  $k => $sub){
						if($sub['parent_id'] == $parent['id']){
							$parents[$key]['sub'][$k] = $sub;
						}
					}
				}
			}
				
			//行业分类
			$row = pdo_fetch("SELECT * FROM ".tablename($this->zone_table)." WHERE id = :id", array(":id" => intval($_GPC['id'])));
				
			include $this->template('zone');
		}
	}

	/**
	 * 区域删除
	 * 
	 */
	public function doWebZoneDeleteAjax(){
		global $_GPC,$_W;
		$zoneid = intval($_GPC['zoneid']);
		
		//频道是否存在子栏目
		if(false !== pdo_fetch("SELECT * FROM ".tablename($this->zone_table)." WHERE parent_id = :pid LIMIT 1", array(":pid" => $zoneid))){
			exit('-1');
		}
		pdo_query("DELETE FROM ".tablename($this->zone_table)." WHERE id = :id", array(":id" => intval($_GPC['zoneid'])));
	}
	/**
	 * 分类管理
	 */
	public function doWebCategory() {
		global $_GPC,$_W;
		
		if (checksubmit('save_category')) {
			$data = $_GPC['data'];
			$data['weid'] = $this->weid;
                      	
			if(isset($_GPC['cid']) && !empty($_GPC['cid'])){
				$cid = intval($_GPC['cid']);
				if(pdo_update($this->category_table, $data, array('id' => $cid))){
					message('操作成功',$this->createWebUrl('Category'),'success');
				}else {
					message('操作失败#', $this->createWebUrl('Category'),'error');
				}
			}else {
				if(pdo_insert($this->category_table, $data)){
					message('操作成功',$this->createWebUrl('Category'),'success');
				}else {
					message('操作失败~', $this->createWebUrl('Category'),'error');
				}
			}
		
		}else {
			$op = isset($_GPC['op']) ? $_GPC['op'] : 'display';
		
			//取父类
			$parents = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE weid = :weid AND parent_id = 0 ORDER BY display ASC", array(":weid" => $this->weid));
			$tmp = array();
			foreach ($parents AS $parent){
				array_push($tmp, $parent['id']);
			}
			$pids = implode(",", $tmp);
			unset($tmp);
			
			if(!empty($pids)){
				//取子类
				$subs = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
				foreach ($parents AS $key => $parent){
					foreach ($subs AS  $k => $sub){
						if($sub['parent_id'] == $parent['id']){
							$parents[$key]['sub'][$k] = $sub;
						}
					}
				}
			}
		
			//行业分类
			$row = pdo_fetch("SELECT * FROM ".tablename($this->category_table)." WHERE id = :id", array(":id" => intval($_GPC['id'])));
		
			include $this->template('category');
		}
	}
	
	/**
	 * 分类删除
	 */
	public function doWebCategoryDeleteAjax(){
		global $_GPC,$_W;
		$cid = intval($_GPC['cid']);
		//频道是否存在子栏目
		if(false !== pdo_fetch("SELECT * FROM ".tablename($this->category_table)." WHERE parent_id = :pid LIMIT 1", array(":pid" => $cid))){
			exit('-1');
		}
		pdo_query("DELETE FROM ".tablename($this->category_table)." WHERE id = :id", array(":id" => intval($_GPC['cid'])));
	}
	
	/**
	 * 信息管理
	 */
	public function doWebBookInfo() {
		global $_GPC,$_W;
		if (checksubmit('save_info')) {
			
			$data = $_GPC['data'];
			$data['coordinate'] = json_encode($data['coordinate']);
			
			$data = array_merge($data, array(
				'weid' => $_W['uniacid'],
				'dateline' => time(),
			));

			if(pdo_insert($this->info_table, $data)){
				message("添加成功", 'refresh', 'success');
			}
			
		}else{
			
			//=======================区域======================
			//取父类
			$zoneparents = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE  weid = :weid AND parent_id = 0 ORDER BY display ASC", array(":weid" => $this->weid));
			$tmp = array();
			foreach ($zoneparents AS $parent){
				array_push($tmp, $parent['id']);
			}
			$pids = implode(",", $tmp);
			unset($tmp);
			if(!empty($pids)){
				//取子类
				$subs = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
				foreach ($zoneparents AS $key => $parent){
					foreach ($subs AS  $k => $sub){
						if($sub['parent_id'] == $parent['id']){
							$zoneparents[$key]['sub'][$k] = $sub;
						}
					}
				}			
			}else {
				message("请先添加区域",'referer', 'error');
			}
			//=======================分类======================
			//取父类
			$parents = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE  weid = :weid AND parent_id = 0 ORDER BY display ASC", array(":weid" => $this->weid));
			$tmp = array();
			foreach ($parents AS $parent){
				array_push($tmp, $parent['id']);
			}
			$pids = implode(",", $tmp);
			unset($tmp);
			if(!empty($pids)){
				//取子类
				$subs = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
				foreach ($parents AS $key => $parent){
					foreach ($subs AS  $k => $sub){
						if($sub['parent_id'] == $parent['id']){
							$parents[$key]['sub'][$k] = $sub;
						}
					}
				}
			}else {
				message("请先添加分类",'referer', 'error');
			}
			//======================信息列表===============
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->info_table)." WHERE weid = :weid", array(":weid" => $this->weid));

			$lists = pdo_fetchall("SELECT * FROM ".tablename($this->info_table)." WHERE weid = :weid ORDER BY dateline DESC LIMIT ".($pindex - 1) * $psize.",{$psize}", array(":weid" => $this->weid));
			$pager = pagination($total, $pindex, $psize);
			
			//=======================根据结果取区域、分类=================
			$tmp = $tmp2 = array();
			foreach ($lists AS $key => $val){
				array_unshift($tmp, $val['zone']);
				array_unshift($tmp2, $val['category']);
			}
			
			if(!empty($tmp)){
				//========区域========
				$zone_array = pdo_fetchall("SELECT id,name FROM ".tablename($this->zone_table)." WHERE id IN (".implode(',', $tmp).")");
				$zone_tmp = array();
				foreach ($zone_array AS $key => $zone){
					$zone_tmp[$zone['id']] = $zone;
				}
			}
			//========分类=========
			if(!empty($tmp2)){
				$category_array = pdo_fetchall("SELECT id,name FROM ".tablename($this->category_table)." WHERE id IN (".implode(',', $tmp2).")");
				$category_tmp = array();
				foreach ($category_array AS $key => $cate){
					$category_tmp[$cate['id']] = $cate;
				}
			}
			//===========组装==========
			foreach ($lists AS $key => $val){
				$lists[$key]['cate_name'] = $category_tmp[$val['category']]['name'];
				$lists[$key]['zone_name'] = $zone_tmp[$val['zone']]['name'];
				$lists[$key]['dateline'] = date("Y-m-d H:i:s", $val['dateline']);
			}
			load()->func('tpl');
			include $this->template('info');
		}
	}
	
	/**
	 * AJAX删除信息
	 */
	public function doWebDeleteInfoAjax(){
		global $_GPC,$_W;
 		pdo_query("DELETE FROM ".tablename($this->info_table)." WHERE id = :id", array(":id" => intval($_GPC['infoid'])));
	}
	
	
	/**
	 * 编辑信息
	 */
	public function doWebEditInfo(){
		global $_GPC,$_W;
		if (checksubmit('save_info')) {
			$_GPC['data']['coordinate'] = json_encode($_GPC['data']['coordinate']);
			if(pdo_update($this->info_table, $_GPC['data'], array('id' => intval($_GPC['infoid'])))){
				message("保存成功!", $this->createWebUrl('BookInfo'), 'success');
			}else {
				message("操作失败或没有修改!", $this->createWebUrl('BookInfo'), 'error');
			}
			
		}else{
			//=======================区域======================
			//取父类
			$zoneparents = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE weid = :weid AND parent_id = 0 ORDER BY display ASC", array(":weid" => $this->weid));
			$tmp = array();
			foreach ($zoneparents AS $parent){
				array_push($tmp, $parent['id']);
			}
			$pids = implode(",", $tmp);
			unset($tmp);
			
			if(!empty($pids)){
				//取子类
				$subs = pdo_fetchall("SELECT * FROM ".tablename($this->zone_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
				foreach ($zoneparents AS $key => $parent){
					foreach ($subs AS  $k => $sub){
						if($sub['parent_id'] == $parent['id']){
							$zoneparents[$key]['sub'][$k] = $sub;
						}
					}
				}
			}
			//=======================分类======================
			//取父类
			$parents = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE weid = :weid AND parent_id = 0 ORDER BY display ASC", array(":weid" => $this->weid));
			$tmp = array();
			foreach ($parents AS $parent){
				array_push($tmp, $parent['id']);
			}
			$pids = implode(",", $tmp);
			unset($tmp);
			if(!empty($pids)){
				//取子类
				$subs = pdo_fetchall("SELECT * FROM ".tablename($this->category_table)." WHERE parent_id IN ({$pids}) ORDER BY display ASC");
				foreach ($parents AS $key => $parent){
					foreach ($subs AS  $k => $sub){
						if($sub['parent_id'] == $parent['id']){
							$parents[$key]['sub'][$k] = $sub;
						}
					}
				}	
			}		
			$row = pdo_fetch("SELECT * FROM ".tablename($this->info_table)." WHERE id = :id LIMIT 1", array(":id" => intval($_GPC['infoid'])));
			load()->func('tpl');
			$coordinate = json_decode($row['coordinate']);
			$lat = $coordinate->lat;
			$lng = $coordinate->lng;
			
			include $this->template('info_edit');
		}
	}


}