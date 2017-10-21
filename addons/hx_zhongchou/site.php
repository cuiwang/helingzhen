<?php
/**
 * 众筹模块微站定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('ZC_ROOT', IA_ROOT . '/addons/hx_zhongchou');
define('CSS_PATH', '../addons/hx_zhongchou/template/style/css/');
define('JS_PATH', '../addons/hx_zhongchou/template/style/js/');
define('IMG_PATH', '../addons/hx_zhongchou/template/style/images/');
class Hx_zhongchouModuleSite extends WeModuleSite {

	public function __construct(){
		global $_W;
		
	}

	public function doMobileList() {
		global $_W,$_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('hx_zhongchou_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('hx_zhongchou_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
		$rpindex = max(1, intval($_GPC['rpage']));
		$rpsize = 6;
		$condition = ' and isrecommand=1';
		$rlist = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE weid = '{$_W['uniacid']}' AND status >= '2' and isrecommand = '1' $condition ORDER BY displayorder DESC, finish_price DESC LIMIT " . ($rpindex - 1) * $rpsize . ',' . $rpsize);
		$carttotal = $this->getCartTotal();
		$moduleconfig = $this->module['config'];
		$title = !empty($moduleconfig['shopname']) ? $moduleconfig['shopname'].' - 首页' : '众筹首页';
		include $this->template('list');
	}
	public function doMobilePublish(){
		global $_W,$_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'choose';
		$settings = $this->module['config'];
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		if ($operation == 'post1') {
			$project_id = intval($_GPC['project_id']);
			if (!empty($project_id)) {
				$project = pdo_fetch("SELECT * FROM ".tablename('hx_zhongchou_project')." WHERE id=:id AND from_user=:from_user",array(':id'=>$project_id,':from_user'=>$_W['openid']));
				if (empty($project)) {
					unset($project_id);
				}
			}
			if ($_GPC['ajax'] == 1) {
				if ($settings['ispublish'] != 1) {
					die(json_encode(array('status'=>0,'info'=>'本站暂未开放众筹项目发布','jump' => $this->createMobileUrl('list'))));
				}
				$insert = array(
					'weid' => $_W['uniacid'],
					'from_user' => $_W['openid'],
					'displayorder' => 0,
					'pcate' => intval($_GPC['cate_id']),
					'title' => $_GPC['name'],
					'limit_price' => intval($_GPC['limit_price']),
					'deal_days' => intval($_GPC['deal_days']),
					'thumb' => $_GPC['image'],
					'brief' => $_GPC['brief'],
					'content' => $_GPC['descript'],
					'lianxiren' => $_GPC['lianxiren'],
					'qq' => $_GPC['qq'],
					'status' => 0,
					'createtime' => time(),
					);
				if (!empty($project_id)) {
					unset($insert['createtime']);
					unset($insert['status']);
					pdo_update('hx_zhongchou_project', $insert, array('id'=>$project_id));
				}else{
					pdo_insert('hx_zhongchou_project',$insert);
					$project_id = pdo_insertid();
				}
				die(json_encode(array('status'=>1,'info'=>$project_id,'jump' => $this->createMobileUrl('Publish',array('op'=>'post2','project_id'=>$project_id)))));
			}
		}elseif ($operation == 'post2') {
			$project_id = intval($_GPC['project_id']);
			$project = pdo_fetch("SELECT * FROM ".tablename('hx_zhongchou_project')." WHERE id=:id AND from_user=:from_user",array(':id'=>$project_id,':from_user'=>$_W['openid']));
			if (empty($project)) {
				message('抱歉，项目不存在，无法添加回报！');
			}
			$item_list = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE weid=:weid AND pid=:pid ORDER BY displayorder DESC",array(':weid'=>$_W['uniacid'],':pid'=>$project_id));
			$item_id = intval($_GPC['item_id']);
			if (!empty($item_id)) {
				$pitem = pdo_fetch("SELECT * FROM ".tablename('hx_zhongchou_project_item')." WHERE id=:id AND pid=:pid",array(':id'=>$item_id,':pid'=>$project_id));
				if (empty($pitem)) {
					unset($item_id);
				}
			}
			if ($_GPC['ajax'] == 1) {
				if ($settings['ispublish'] != 1) {
					die(json_encode(array('status'=>0,'info'=>'本站暂未开放众筹项目发布','jump' => $this->createMobileUrl('list'))));
				}
				$insert = array(
					'weid' => $_W['uniacid'],
					'pid' => $project_id,
					'displayorder' => 0,
					'price' => $_GPC['price'],
					'description' => $_GPC['description'],
					'thumb' => $_GPC['image'][0],
					'limit_num' => intval($_GPC['limit_user']),
					'return_type' => $_GPC['is_delivery'] == 0 ? 2:1,
					'delivery_fee' => intval($_GPC['delivery_fee']),
					'repaid_day' => intval($_GPC['repaid_day']),
					'createtime' => time(),
					);
				if (!empty($item_id)) {
					unset($insert['createtime']);
					pdo_update('hx_zhongchou_project_item', $insert, array('id'=>$item_id));
				}else{
					pdo_insert('hx_zhongchou_project_item',$insert);
					$item_id = pdo_insertid();
				}
				die(json_encode(array('status'=>1,'info'=>"保存成功",'jump' => $this->createMobileUrl('Publish',array('op'=>'post2','project_id'=>$project_id)))));
			}
		}elseif ($operation == 'delete_item') {
			$project_id = intval($_GPC['project_id']);
			$project = pdo_fetch("SELECT * FROM ".tablename('hx_zhongchou_project')." WHERE id=:id AND from_user=:from_user",array(':id'=>$project_id,':from_user'=>$_W['openid']));
			if (empty($project)) {
				die(json_encode(array('status'=>0,'info'=>'操作无权限','jump' => $this->createMobileUrl('list'))));
			}
			$item_id = intval($_GPC['item_id']);
			$pitem = pdo_fetch("SELECT * FROM ".tablename('hx_zhongchou_project_item')." WHERE id=:id AND pid=:pid",array(':id'=>$item_id,':pid'=>$project_id));
			if (!empty($pitem)) {
				pdo_delete('hx_zhongchou_project_item', array('id'=>$item_id));
				die(json_encode(array('status'=>1,'info'=>'删除成功！','jump' =>$this->createMobileUrl('Publish',array('op'=>'post2','project_id'=>$project_id)))));
			}else{
				die(json_encode(array('status'=>0,'info'=>'项目不存在！','jump' => $this->createMobileUrl('Publish',array('op'=>'post2','project_id'=>$project_id)))));
			}
		}elseif ($operation == 'post3') {
			$project_id = intval($_GPC['project_id']);
			$project = pdo_fetch("SELECT * FROM ".tablename('hx_zhongchou_project')." WHERE id=:id AND from_user=:from_user",array(':id'=>$project_id,':from_user'=>$_W['openid']));
			if (empty($project)) {
				die(json_encode(array('info'=>'项目不存在！','jump' =>$this->createMobileUrl('Publish',array('op'=>'post2','project_id'=>$project_id)))));
			}
			$item_num = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('hx_zhongchou_project_item') . " WHERE weid=:weid AND pid=:pid ORDER BY displayorder DESC",array(':weid'=>$_W['uniacid'],':pid'=>$project_id));
			if ($item_num == 0) {
				die(json_encode(array('info'=>'您至少需要发布一个回报','jump' =>$this->createMobileUrl('Publish',array('op'=>'post2','project_id'=>$project_id)))));
			}else{
				pdo_update('hx_zhongchou_project', array('status'=>1), array('id'=>$project_id));
				die(json_encode(array('status'=>1,'info'=>'提交成功，管理员正在审核中','jump' => $this->createMobileUrl('list'))));
			}
		}
		include $this->template('publish');
	}
	public function doMobileCategory(){
		global $_GPC,$_W;
		$category = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		$pagetitle = "全部分类";
		include $this->template('category');
	}
	public function doMobileTip(){
		global $_GPC, $_W;
		$moduleconfig = $this->module['config'];
		$title = !empty($moduleconfig['shopname']) ? $moduleconfig['shopname'] : '众筹';
		include $this->template('tip');
	}
	
	public function doWebNews(){
		global $_W,$_GPC;
		load()->func('tpl');
		if (!function_exists('filter_url')) {
			function filter_url($params) {
				global $_W;
				if(empty($params)) {
					return '';
				}
				$query_arr = array();
				$parse = parse_url($_W['siteurl']);
				if(!empty($parse['query'])) {
					$query = $parse['query'];
					parse_str($query, $query_arr);
				}
				$params = explode(',', $params);
				foreach($params as $val) {
					if(!empty($val)) {
						$data = explode(':', $val);
						$query_arr[$data[0]] = trim($data[1]);
					}
				}
				$query_arr['page'] = 1;
				$query = http_build_query($query_arr);
				return './index.php?' . $query;
			}
		}
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$new = pdo_fetch('SELECT * FROM ' . tablename('hx_zhongchou_news') . ' WHERE id = :id', array(':id' => $id));
			if(empty($new)) {
				$new = array(
					'is_display' => 1,
				);
			}
			if(checksubmit()) {
				$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('新闻标题不能为空', '', 'error');
				$cateid = intval($_GPC['cateid']) ? intval($_GPC['cateid']) : message('新闻分类不能为空', '', 'error');
				$content = trim($_GPC['content']) ? trim($_GPC['content']) : message('新闻内容不能为空', '', 'error');
				$data = array(
					'uniacid' => $_W['uniacid'],
					'title' => $title,
					'cateid' => $cateid,
					'content' => htmlspecialchars_decode($content),
					'source' => trim($_GPC['source']),
					'author' => trim($_GPC['author']),
					'displayorder' => intval($_GPC['displayorder']),
					'click' => intval($_GPC['click']),
					'is_display' => intval($_GPC['is_display']),
					'createtime' => TIMESTAMP,
				);
				if (!empty($_GPC['thumb'])) {
					$data['thumb'] = $_GPC['thumb'];
				} elseif (!empty($_GPC['autolitpic'])) {
					$match = array();
					preg_match('/attachment\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $data['content'], $match);
					if (!empty($match[1])) {
						$data['thumb'] = $match[1].$match[2];
					}
				} else {
					$data['thumb'] = '';
				}

				if(!empty($new['id'])) {
					pdo_update('hx_zhongchou_news', $data, array('id' => $id));
				} else {
					pdo_insert('hx_zhongchou_news', $data);
				}
				message('编辑文章成功', $this->createWebUrl('news',array('op'=>'display')), 'success');
			}
			$categorys = pdo_fetchall('SELECT * FROM ' . tablename('hx_zhongchou_news_category') . ' WHERE uniacid = :uniacid ORDER BY displayorder DESC', array(':uniacid' => $_W['uniacid']));
		}elseif ($operation == 'display') {
			$condition = ' WHERE 1';
			$cateid = intval($_GPC['cateid']);
			$createtime = intval($_GPC['createtime']);
			$title = trim($_GPC['title']);

			$params = array();
			if($cateid > 0) {
				$condition .= ' AND cateid = :cateid';
				$params[':cateid'] = $cateid;
			}
			if($createtime > 0) {
				$condition .= ' AND createtime >= :createtime';
				$params[':createtime'] = strtotime("-{$createtime} days");
			}
			if(!empty($title)) {
				$condition .= " AND title LIKE :title";
				$params[':title'] = "%{$title}%";
			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$sql = 'SELECT * FROM ' . tablename('hx_zhongchou_news') . $condition . " ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
			$news = pdo_fetchall($sql, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_zhongchou_news') . $condition, $params);
			$pager = pagination($total, $pindex, $psize);

			$categorys = pdo_fetchall('SELECT * FROM ' . tablename('hx_zhongchou_news_category') . ' WHERE uniacid = :uniacid ORDER BY displayorder DESC', array(':uniacid' => $_W['uniacid']), 'id');
		}elseif($operation == 'batch_post'){
			if(checksubmit()) {
				if(!empty($_GPC['ids'])) {
					foreach($_GPC['ids'] as $k => $v) {
						$data = array(
							'title' => trim($_GPC['title'][$k]),
							'displayorder' => intval($_GPC['displayorder'][$k]),
							'click' => intval($_GPC['click'][$k]),
						);
						pdo_update('hx_zhongchou_news', $data, array('id' => intval($v)));
					}
					message('编辑新闻列表成功', referer(), 'success');
				}
			}
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('hx_zhongchou_news', array('id' => $id));
			message('删除文章成功', referer(), 'success');
		}
		include $this->template('news');
	}
	public function doWebNews_category(){
		global $_W,$_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			if(checksubmit('submit')) {
				$i = 0;
				if(!empty($_GPC['title'])) {
					foreach($_GPC['title'] as $k => $v) {
						$title = trim($v);
						if(empty($title)) {
							continue;
						}
						$data = array(
							'uniacid' => $_W['uniacid'],
							'title' => $title,
							'displayorder' => intval($_GPC['displayorder'][$k]),
						);
						pdo_insert('hx_zhongchou_news_category', $data);
						$i++;
					}
				}
				message('修改文章分类成功', $this->createWebUrl('news_category',array('op'=>'display')), 'success');
			}
		}elseif ($operation == 'display') {
			if(checksubmit('submit')) {
				if(!empty($_GPC['ids'])) {
					foreach($_GPC['ids'] as $k => $v) {
						$data = array(
							'uniacid' => $_W['uniacid'],
							'title' => trim($_GPC['title'][$k]),
							'displayorder' => intval($_GPC['displayorder'][$k])
						);
						pdo_update('hx_zhongchou_news_category', $data, array('id' => intval($v)));
					}
					message('修改新闻分类成功', referer(), 'success');
				}
			}
			$data = pdo_fetchall('SELECT * FROM ' . tablename('hx_zhongchou_news_category') . ' WHERE uniacid = :uniacid ORDER BY displayorder DESC', array(':uniacid' => $_W['uniacid']));
		}elseif($operation == 'delete'){
			$id = intval($_GPC['id']);
			pdo_delete('hx_zhongchou_news_category', array('id' => $id));
			pdo_delete('hx_zhongchou_news', array('cateid' => $id));
			message('删除分类成功', referer(), 'success');
		}
		include $this->template('news_category');
	}
	public function doMobileNews(){
		global $_W,$_GPC;
		$cateid = intval($_GPC['cateid']);
		$condition = " WHERE uniacid = '{$_W['uniacid']}' ";
		if (!empty($cateid)) {
			$condition .= " AND cateid = '{$cateid}'";
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = 'SELECT * FROM ' . tablename('hx_zhongchou_news') . $condition . " ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
		$news = pdo_fetchall($sql, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_zhongchou_news') . $condition, $params);
		$pager = pagination($total, $pindex, $psize);
		$category = pdo_fetchall('SELECT * FROM ' . tablename('hx_zhongchou_news_category') . ' WHERE uniacid = :uniacid ORDER BY displayorder DESC', array(':uniacid' => $_W['uniacid']));
		$title = "新闻资讯";
		$pagetitle = "新闻资讯";
		include $this->template('news_list');
	}
	public function doMobileNews_detail(){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		if (empty($id)) {
			message('参数错误',$this->createMobileUrl('news'),'error');
		}
		$article = pdo_fetch("SELECT * FROM ".tablename('hx_zhongchou_news')." WHERE id=:id",array(':id'=>$id));
		$title = $article['title'] . ' - '. $this->getnewscategory($article['cateid']);
		$pagetitle = $article['title'];
		include $this->template('news_detail');
	}
	public function doMobilelist2() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC["page"]));
		$psize = 10;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('hx_zhongchou_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$sort = empty($_GPC['sort']) ? 0 : $_GPC['sort'];
		$sortfield = "displayorder asc";
		$sortb0 = empty($_GPC['sortb0']) ? "desc" : $_GPC['sortb0'];
		$sortb1 = empty($_GPC['sortb1']) ? "desc" : $_GPC['sortb1'];
		$sortb2 = empty($_GPC['sortb2']) ? "desc" : $_GPC['sortb2'];
		$sortb3 = empty($_GPC['sortb3']) ? "asc" : $_GPC['sortb3'];
		if ($sort == 0) {
			$sortb00 = $sortb0 == "desc" ? "asc" : "desc";
			$sortfield = "createtime " . $sortb0;
			$sortb11 = "desc";
			$sortb22 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 1) {
			$sortb11 = $sortb1 == "desc" ? "asc" : "desc";
			$sortfield = "donenum " . $sortb1;
			$sortb00 = "desc";
			$sortb22 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 2) {
			$sortb22 = $sortb2 == "desc" ? "asc" : "desc";
			$sortfield = "donenum " . $sortb2;
			$sortb00 = "desc";
			$sortb11 = "desc";
			$sortb33 = "asc";
		}
		$sorturl = $this->createMobileUrl('list2', array("keyword" => $_GPC['keyword'], "pcate" => $_GPC['pcate'], "ccate" => $_GPC['ccate']), true);
		if (!empty($_GPC['ishot'])) {
			$condition .= " AND ishot = 1";
			$sorturl.="&ishot=1";
		}
		if (!empty($_GPC['isdiscount'])) {
			$condition .= " AND isdiscount = 1";
			$sorturl.="&isdiscount=1";
		}
		if (!empty($_GPC['istime'])) {
			$condition .= " AND istime = 1 and " . time() . ">=timestart and " . time() . "<=timeend";
			$sorturl.="&isyure=1";
		}
		if (!empty($_GPC['isyure'])) {
			$condition .= " AND " . time() . "<=starttime ";
			$sorturl.="&istime=1";
		}
		if (!empty($_GPC['isnew'])) {
			$orderby .= "starttime DESC,";
			$sorturl.="&isnew=1";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		$list = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE weid = '{$_W['uniacid']}'   AND status >= '2' $condition ORDER BY $orderby$sortfield LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_zhongchou_project') . " WHERE weid = '{$_W['uniacid']}'    AND status >= '2' $condition");
		$pager = pagination($total, $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
		$carttotal = $this->getCartTotal();
		$title = '众筹首页';
		include $this->template('list2');
	}
	public function doMobileDetail(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = :id", array(':id' => $id));
		if (empty($item)) {
			message("抱歉，项目不存在!", referer(), "error");
		}
		$favournum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_zhongchou_cart') . " WHERE weid = '{$_W['uniacid']}' AND projectid = '{$id}'");
		$isfavour = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_cart') . " WHERE projectid = '{$id}' AND from_user = '{$_W['fans']['from_user']}'");
		$items = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE weid = '{$_W['uniacid']}'   AND pid = '{$id}' ORDER BY displayorder DESC");
		$carttotal = $this->getCartTotal();
		$title = $item['title'];
		$moduleconfig = $this->module['config'];
		$sql = "select * from ".tablename('hx_zhongchou_order_ws')." WHERE weid='{$_W['uniacid']}' AND pid='{$id}' AND status=1 LIMIT 10";
		$wslist = pdo_fetchall($sql);
		//print_r($wslist);
		$pagetitle = "项目展示";
		include $this->template('detail');
	}
	public function getproject($id) {
		$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = :id", array(':id' => $id));
		return $item;
	}
	public function getprojectorder($pid) {
		$item = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('hx_zhongchou_order') . " WHERE pid = :pid", array(':pid' => $pid));
		return $item;
	}
	public function getprojectorder_ws($pid) {
		$item = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('hx_zhongchou_order_ws') . " WHERE pid = :pid AND status=1", array(':pid' => $pid));
		return $item;
	}
	public function getnewscategory($id) {
		$item = pdo_fetchcolumn("SELECT title FROM " . tablename('hx_zhongchou_news_category') . " WHERE id = :id", array(':id' => $id));
		return $item;
	}
	public function getitem($id) {
		$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE id = :id", array(':id' => $id));
		return $item;
	}
	public function doMobileDetail_more(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$detail = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = :id", array(':id' => $id));
		if (empty($detail)) {
			message("抱歉，项目不存在!", referer(), "error");
		}
		$title = $detail['title'];
		$pagetitle = "项目详细说明";
		include $this->template('detail_more');
	}
	public function doMobileWsconfirm(){
		global $_W, $_GPC;
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$id = intval($_GPC['id']);
		$project = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = :id", array(':id' => $id));
		if (empty($project)) {
			message("抱歉，该项目不存在!", referer(), "error");
		}
		if (time() <= $project['starttime']) {
			message("抱歉，该项目尚未开始!", referer(), "error");
		}elseif (time() > $project['starttime'] + $project['deal_days'] * 86400) {
			message("抱歉，该项目已经结束!", referer(), "error");
		}
		if (empty($_GPC['pay_money'])) {
			message("请输入支持的金额!", referer(), "error");
		}
		$data = array(
			'weid' => $_W['uniacid'],
			'from_user' => $_W['fans']['from_user'],
			'nickname' => $_W['fans']['tag']['nickname'],
			'avatar' => $_W['fans']['tag']['avatar'],
			'ordersn' => date('md') . random(4, 1),
			'price' => $_GPC['pay_money'],
			'status' => 0,
			'remark' => $_GPC['pay_remark'],
			'pid' => $id,
			'createtime' => TIMESTAMP,
			);
		pdo_insert('hx_zhongchou_order_ws', $data);
		$orderid = pdo_insertid();
		header("Location:" . $this->createMobileUrl('pay', array('orderid' => $orderid,'type'=>'ws')));
	}
	public function doMobileConfirm() {
		global $_W, $_GPC;
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$id = intval($_GPC['id']);
		$project = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = :id", array(':id' => $id));
		if (empty($project)) {
			message("抱歉，该项目不存在!", referer(), "error");
		}
		if ($project['status'] != 3) {
			message("抱歉，该项目尚未开始!", referer(), "error");
		}
		if (time() <= $project['starttime']) {
			message("抱歉，该项目尚未开始!", referer(), "error");
		}elseif (time() > $project['starttime'] + $project['deal_days'] * 86400) {
			message("抱歉，该项目已经结束!", referer(), "error");
		}
		$item_id = intval($_GPC['item_id']);
		$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE id = :id", array(':id' => $item_id));
		if (empty($item)) {
			message("抱歉，该回报不存在!", referer(), "error");
		}
		if ($item['limit_num'] != 0 && $item['limit_num'] <= $item['donenum']) {
			message('该回报以筹集完毕，请选择其他回报');
		}
		$returnurl = $this->createMobileUrl("confirm", array("id" => $id, "item_id" => $item_id));
		$dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight from " . tablename("hx_zhongchou_dispatch") . " WHERE weid = {$_W['uniacid']} order by displayorder desc");
		foreach ($dispatch as &$d) {
			$weight = 0;
			$weight = $item['weight'];
			$price = 0;
			if ($weight <= $d['firstweight']) {
				$price = $d['firstprice'];
			} else {
				$price = $d['firstprice'];
				$secondweight = $weight - $d['firstweight'];
				if ($secondweight % $d['secondweight'] == 0) {
					$price+= (int) ( $secondweight / $d['secondweight'] ) * $d['secondprice'];
				} else {
					$price+= (int) ( $secondweight / $d['secondweight'] + 1 ) * $d['secondprice'];
				}
			}
			$d['price'] = $price;
		}
		unset($d);
		if (checksubmit('submit')) {
			$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
			if (empty($address)) {
				message('抱歉，请您填写收货地址！',$this->createMobileUrl('address', array('from'=>'confirm','returnurl'=>urlencode($returnurl))),'error');
			}
			//项目回报价格
			$item_price = $item['price'];
			//运费
			$dispatchid = intval($_GPC['dispatch']);
			$dispatchprice = 0;
			foreach ($dispatch as $d) {
				if ($d['id'] == $dispatchid) {
					$dispatchprice = $d['price'];
					$sendtype = $d['dispatchtype'];
				}
			}
			$data = array(
				'weid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'ordersn' => date('md') . random(4, 1),
				'price' => $item_price + $dispatchprice,
				'dispatchprice' => $dispatchprice,
				'item_price' => $item_price,
				'status' => 0,
				'sendtype' =>intval($sendtype),
				'dispatch' => $dispatchid,
				'return_type' => intval($item['return_type']),
				'remark' => $_GPC['remark'],
				'addressid' => $address['id'],
				'pid' => $id,
				'item_id' => $item_id,
				'createtime' => TIMESTAMP,
			);
			pdo_insert('hx_zhongchou_order', $data);
			$orderid = pdo_insertid();
			message('提交订单成功,现在跳转到付款页面...',$this->createMobileUrl('pay', array('orderid' => $orderid)),'success');
		}
		$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'nickname', 'mobile'));
		$row = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['member']['uid']));
		$carttotal = $this->getCartTotal();
		$pagetitle = "结算";
		include $this->template('confirm');
	}
	public function doMobileMyCart() {
		global $_W, $_GPC;
		if (empty($_W['openid'])) {
			exit(json_encode(array('status'=>0)));
		}
		$op = $_GPC['op'];
		if ($op == 'add') {
			$pid = intval($_GPC['pid']);
			$project = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = :id", array(':id' => $pid));
			if (empty($project)) {
				exit(json_encode(array('status'=>3,'info'=>"抱歉，该项目不存在或是已经被删除！")));
			}
			$row = pdo_fetch("SELECT id FROM " . tablename('hx_zhongchou_cart') . " WHERE from_user = :from_user AND weid = '{$_W['uniacid']}' AND projectid = :pid", array(':from_user' => $_W['fans']['from_user'], ':pid' => $pid));
			if ($row == false) {
				//不存在
				$data = array(
					'weid' => $_W['uniacid'],
					'projectid' => $pid,
					'from_user' => $_W['fans']['from_user'],
				);
				pdo_insert('hx_zhongchou_cart', $data);
				$type = 'add';
				die(json_encode(array('status'=>1)));
			} else {
				pdo_delete('hx_zhongchou_cart', array('id' => $row['id']));
				$type = 'del';
				die(json_encode(array('status'=>2)));
			}
		} else if ($op == 'clear') {
			pdo_delete('hx_zhongchou_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid']));
			die(json_encode(array("result" => 1)));
		} else if ($op == 'remove') {
			$id = intval($_GPC['id']);
			pdo_delete('hx_zhongchou_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid'], 'id' => $id));
			die(json_encode(array("result" => 1, "cartid" => $id)));
		} else {
			$list = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			include $this->template('cart');
		}
	}
	public function doMobilePay() {
		global $_W, $_GPC;
		$this->checkAuth();
		$orderid = intval($_GPC['orderid']);
		if ($_GPC['type'] == 'ws') {
			$order = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_order_ws') . " WHERE id = :id", array(':id' => $orderid));
			if ($order['status'] != '0') {
				message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');
			}
			$params['tid'] = 'ws-'.$orderid;
			$params['user'] = $_W['fans']['from_user'];
			$params['fee'] = $order['price'];
			$params['title'] = $_W['account']['name'];
			$params['ordersn'] = $order['ordersn'];
			$params['virtual'] = true;
		}else{
			$order = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_order') . " WHERE id = :id", array(':id' => $orderid));
			if ($order['status'] != '0') {
				message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');
			}
			$params['tid'] = $orderid;
			$params['user'] = $_W['fans']['from_user'];
			$params['fee'] = $order['price'];
			$params['title'] = $_W['account']['name'];
			$params['ordersn'] = $order['ordersn'];
			$params['virtual'] = $order['return_type'] == 2 ? true : false;
		}
		include $this->template('pay');
	}
	public function payResult($params) {
		global $_W;
		if ($params['result'] == 'success' && $params['from'] == 'notify') {
			$fee = intval($params['fee']);
			$data = array('status' => $params['result'] == 'success' ? 1 : 0);
			$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
			$data['paytype'] = $paytype[$params['type']];
			if ($params['type'] == 'wechat') {
				$data['transid'] = $params['tag']['transaction_id'];
			}
			if ($params['type'] == 'delivery') {
				$data['status'] = 1;
			}
			if (substr($params['tid'], 0,3) == 'ws-') {
				$params['tid'] = str_replace('ws-', '', $params['tid']);
				$order = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_order_ws') . " WHERE id = '{$params['tid']}'");
				if ($order['status'] != 1) {
					pdo_update('hx_zhongchou_order_ws', $data, array('id' => $params['tid']));
					$pid = $order['pid'];
					$project = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = '{$pid}'");
					pdo_update('hx_zhongchou_project',array('finish_price'=>$project['finish_price'] + $order['item_price']),array('id'=>$pid));
				}
			}else{
				$order = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_order') . " WHERE id = '{$params['tid']}'");
				if ($order['status'] != 1) {
					pdo_update('hx_zhongchou_order', $data, array('id' => $params['tid']));
					$pid = $order['pid'];
					$item_id = $order['item_id'];
					$project = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = '{$pid}'");
					$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE id = '{$item_id}'");
					pdo_update('hx_zhongchou_project',array('finish_price'=>$project['finish_price'] + $order['item_price'],'donenum'=>$project['donenum']+1),array('id'=>$pid));
					pdo_update('hx_zhongchou_project_item',array('donenum'=>$item['donenum']+1),array('id'=>$item_id));
				}
				//模板消息
				$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$settings = $this->module['config'];
				if (!empty($settings['kfid']) && !empty($settings['k_templateid'])) {
					$kfirst = empty($settings['kfirst']) ? '您有一个新的众筹订单' : $settings['kfirst'];
					$kfoot = empty($settings['kfoot']) ? '请及时处理，点击可查看详情' : $settings['kfoot'];
					$kurl = '';
					$kdata = array(
						'first' => array(
							'value' => $kfirst,
							'color' => '#ff510'
						),
						'keyword1' => array(
							'value' => $order['ordersn'],
							'color' => '#ff510'
						),
						'keyword2' => array(
							'value' => '众筹:' . $project['title'],
							'color' => '#ff510'
						),
						'keyword3' => array(
							'value' => $order['price'] . '元',
							'color' => '#ff510'
						),
						'keyword4' => array(
							'value' => $address['username'],
							'color' => '#ff510'
						),
						'keyword5' => array(
							'value' => $params['type'],
							'color' => '#ff510'
						),
						'remark' => array(
							'value' => $kfoot ,
							'color' => '#ff510'
						),
					);
					$acc = WeAccount::create();
					$acc->sendTplNotice($settings['kfid'], $settings['k_templateid'], $kdata, $kurl, $topcolor = '#FF683F');
				}
				if (!empty($settings['m_templateid'])) {
					$mfirst = empty($settings['mfirst']) ? '众筹支付成功通知' : $settings['mfirst'];
					$mfoot = empty($settings['mfoot']) ? '点击查看订单详情' : $settings['mfoot'];
					$murl = $_W['siteroot'] . 'app' . str_replace('./', '/', $this->createMobileUrl('myorder',array('op'=>'detail','orderid'=>$order['id'])));
					$mdata = array(
						'first' => array(
							'value' => $mfirst,
							'color' => '#ff510'
						),
						'keyword1' => array(
							'value' => $address['username'],
							'color' => '#ff510'
						),
						'keyword2' => array(
							'value' => $order['ordersn'],
							'color' => '#ff510'
						),
						'keyword3' => array(
							'value' => $order['price'] . '元',
							'color' => '#ff510'
						),
						'keyword4' => array(
							'value' => '众筹:' . $project['title'],
							'color' => '#ff510'
						),
						'remark' => array(
							'value' => $mfoot ,
							'color' => '#ff510'
						),
					);
					$acc = WeAccount::create();
					$acc->sendTplNotice($order['from_user'], $settings['m_templateid'], $mdata, $murl, $topcolor = '#FF683F');
				}
				//积分变更
				//$this->setOrderCredit($params['tid']);
				//邮件提醒
				if (!empty($this->module['config']['noticeemail'])) {
					
					$body = "<h3>购买众筹项目详情</h3> <br />";
					$body .= "名称：{$project['title']} <br />";
					$body .= "<br />支持金额：{$order['price']}元 （已付款）<br />";
					$body .= "<h3>购买用户详情</h3> <br />";
					$body .= "真实姓名：{$address['username']} <br />";
					$body .= "地区：{$address['province']} - {$address['city']} - {$address['district']}<br />";
					$body .= "详细地址：{$address['address']} <br />";
					$body .= "手机：{$address['mobile']} <br />";
            	    load()->func('communication');
					ihttp_email($this->module['config']['noticeemail'], '众筹订单提醒', $body);
				}
				$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
				$credit = $setting['creditbehaviors']['currency'];
			}
			
		}
		if ($params['from'] == 'return') {
			if ($params['type'] == $credit) {
				message('支付成功！', $this->createMobileUrl('myorder'), 'success');
			} else {
				message('支付成功！', '../../app/' . $this->createMobileUrl('myorder'), 'success');
			}
		}
	}
	public function doMobileAddress() {
		global $_W, $_GPC;
		$from = $_GPC['from'];
		$returnurl = urldecode($_GPC['returnurl']);
		$this->checkAuth();
		$carttotal = $this->getCartTotal();
		$operation = $_GPC['op'];
		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$data = array(
				'uniacid' => $_W['uniacid'],
				'uid' => $_W['member']['uid'],
				'username' => $_GPC['username'],
				'mobile' => $_GPC['mobile'],
				'province' => $_GPC['province'],
				'city' => $_GPC['city'],
				'district' => $_GPC['district'],
				'address' => $_GPC['address'],
			);
			if (empty($_GPC['username']) || empty($_GPC['mobile']) || empty($_GPC['address'])) {
				message('请输完善您的资料！');
			}
			if (!empty($id)) {
				unset($data['uniacid']);
				unset($data['uid']);
				pdo_update('mc_member_address', $data, array('id' => $id));
				message($id, '', 'ajax');
			} else {
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
				$data['isdefault'] = 1;
				pdo_insert('mc_member_address', $data);
				$id = pdo_insertid();
				if (!empty($id)) {
					message($id, '', 'ajax');
				} else {
					message(0, '', 'ajax');
				}
			}
		} elseif ($operation == 'default') {
			$id = intval($_GPC['id']);
			$address = pdo_fetch("select isdefault from " . tablename('mc_member_address') . " where id='{$id}' and uniacid='{$_W['uniacid']}' and uid='{$_W['member']['uid']}' limit 1 ");
			if(!empty($address) && empty($address['isdefault'])){
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
				pdo_update('mc_member_address', array('isdefault' => 1), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid'], 'id' => $id));
			}
			message(1, '', 'ajax');
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id, username, mobile, province, city, district, address FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $id));
			message($row, '', 'ajax');
		} elseif ($operation == 'remove') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$address = pdo_fetch("select isdefault from " . tablename('mc_member_address') . " where id='{$id}' and uniacid='{$_W['uniacid']}' and uid='{$_W['member']['uid']}' limit 1 ");
				if (!empty($address)) {
					pdo_delete("mc_member_address",  array('id'=>$id, 'uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
					if ($address['isdefault'] == 1) {
						//如果删除的是默认地址，则设置是新的为默认地址
						$maxid = pdo_fetchcolumn("select max(id) as maxid from " . tablename('mc_member_address') . " where uniaicd='{$_W['uniacid']}' and uid='{$_W['member']['uid']}' limit 1 ");
						if (!empty($maxid)) {
							pdo_update('mc_member_address', array('isdefault' => 1), array('id' => $maxid, 'uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
							die(json_encode(array("result" => 1, "maxid" => $maxid)));
						}
					}
				}
			}
			die(json_encode(array("result" => 1, "maxid" => 0)));
		} else {
			$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
			$address = pdo_fetchall("SELECT * FROM " . tablename('mc_member_address') . " WHERE uid = :uid", array(':uid' => $_W['member']['uid']));
			$carttotal = $this->getCartTotal();
			$title = $pagetitle = "信息维护";
			include $this->template('address');
		}
	}
	private function checkAuth() {
		global $_W;
		if (empty($_W['openid'])) {
			if (!empty($_W['account']['subscribeurl'])) {
				message('请先关注公众号'.$_W['account']['name'].'('.$_W['account']['account'].')',$_W['account']['subscribeurl'],'error');
			}else{
				exit('请先关注公众号'.$_W['account']['name'].'('.$_W['account']['account'].')');
			}	
		}
	}
	public function getCartTotal() {
		global $_W;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_zhongchou_cart') . " WHERE weid = '{$_W['uniacid']}'  AND from_user = '{$_W['fans']['from_user']}'");
		return empty($total) ? 0 : $total;
	}
	public function doWebWcorder(){
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$project_id = intval($_GPC['project_id']);
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$sql = "select * from ".tablename('hx_zhongchou_order_ws')." WHERE weid='{$_W['uniacid']}' AND pid='{$project_id}' AND status=1 LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql,$paras);
			$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'danger','name' => '余额支付'),
					'2' => array('css' => 'info', 'name' => '在线支付'),
					'3' => array('css' => 'warning', 'name' => '货到付款')
				);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hx_zhongchou_order_ws')." WHERE weid='{$_W['uniacid']}' AND pid='{$project_id}' AND status=1");
			$pager = pagination($total, $pindex, $psize);
			include $this->template('wcorder');
		}
	}
	public function doWebOrder() {
		global $_W, $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$projects = pdo_fetchall("SELECT id AS id,title AS name FROM " . tablename('hx_zhongchou_project') . " WHERE weid='{$_W['uniacid']}' ORDER BY id DESC");
		if (!empty($projects)) {
			$pitems = '';
			foreach ($projects as $key => $value) {
				$pitems[$value['id']] = pdo_fetchall("SELECT id AS id,price AS name FROM " . tablename('hx_zhongchou_project_item') . " WHERE weid='{$_W['uniacid']}' AND pid='{$value['id']}' ORDER BY id DESC");
			}
		}
		//print_r($projects);
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = $_GPC['status'];
			$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
			$condition = " o.weid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (empty($starttime) || empty($endtime)) {
				$starttime =  strtotime('-1 month');
				$endtime = time();
			}
			//print_r($_GPC);
			if ($_GPC['project']['parentid'] != 0) {
				$condition .= " AND o.pid=:pid ";
				$paras[':pid'] = intval($_GPC['project']['parentid']);
				$pid = intval($_GPC['project']['parentid']);
			}
			if ($_GPC['project']['parentid'] != 0 && $_GPC['project']['childid'] != 0) {
				$condition .= " AND o.item_id=:iid ";
				$paras[':iid'] = intval($_GPC['project']['childid']);
				$iid = intval($_GPC['project']['childid']);
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}
			if (!empty($_GPC['paytype'])) {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			} elseif ($_GPC['paytype'] === '0') {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND (a.username LIKE '%{$_GPC['member']}%' or a.mobile LIKE '%{$_GPC['member']}%')";
			}
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}
			if (!empty($sendtype)) {
				$condition .= " AND o.sendtype = '" . intval($sendtype) . "' AND status != '3'";
			}
			if ($_GPC['out_put'] == 'output') {
				$sql = "select o.* , a.username,a.mobile from ".tablename('hx_zhongchou_order')." o"
					." left join ".tablename('mc_member_address')." a on o.addressid = a.id "
					. " where $condition ORDER BY o.status DESC, o.createtime DESC ";
				$list = pdo_fetchall($sql,$paras);
				$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'danger','name' => '余额支付'),
					'2' => array('css' => 'info', 'name' => '在线支付'),
					'3' => array('css' => 'warning', 'name' => '货到付款')
				);
				$orderstatus = array (
					'-1' => array('css' => 'default', 'name' => '已取消'),
					'0' => array('css' => 'danger', 'name' => '待付款'),
					'1' => array('css' => 'info', 'name' => '待发货'),
					'2' => array('css' => 'warning', 'name' => '待收货'),
					'3' => array('css' => 'success', 'name' => '已完成')
				);
				foreach ($list as &$value) {
					$s = $value['status'];
					$value['statuscss'] = $orderstatus[$value['status']]['css'];
					$value['status'] = $orderstatus[$value['status']]['name'];
					if ($s < 1) {
						$value['css'] = $paytype[$s]['css'];
						$value['paytype'] = $paytype[$s]['name'];
						continue;
					}
					$value['css'] = $paytype[$value['paytype']]['css'];
					if ($value['paytype'] == 2) {
						if (empty($value['transid'])) {
							$value['paytype'] = '支付宝支付';
						} else {
							$value['paytype'] = '微信支付';
						}
					} else {
						$value['paytype'] = $paytype[$value['paytype']]['name'];
					}
				}
				if (!empty($list)) {
					foreach ($list as &$row) {
						// !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
						$row['dispatch'] = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_dispatch') . " WHERE id = :id", array(':id' => $row['dispatch']));
					}
					unset($row);
				}
				if (!empty($list)) {
					foreach ($list as &$row) {
						// !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
						$row['address'] = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $row['addressid']));
					}
					unset($row);
				}
				$i = 0;
				foreach ($list as $key => $value) {
					$project = $this->getproject($value['pid']);
					$pitem = $this->getitem($value['item_id']);
					$arr[$i]['ordersn'] = $value['ordersn'];
					$arr[$i]['title'] = $project['title'];
					$arr[$i]['item_price'] = $pitem['price'];
					$arr[$i]['status'] = $value['status'];
					$arr[$i]['username'] = $value['username'];
					$arr[$i]['mobile'] = "'".$value['mobile'];
					$arr[$i]['address'] = $value['address']['province'].'-'.$value['address']['city'].'-'.$value['address']['distinct'].'-'.$value['address']['address'];
					$arr[$i]['createtime'] = "'".date('Y-m-d H:i:s',$value['createtime']);
					$arr[$i]['dispatchname'] = $value['dispatch']['dispatchname'];
					$i ++;
					unset($project);unset($pitem);
				}
				//print_r($list);
				$this->exportexcel($arr,array('订单号','项目名称','支持金额','状态','真实姓名','电话号码','地址','时间','邮寄方式'),time());
				exit();
			}
			$sql = "select o.* , a.username,a.mobile from ".tablename('hx_zhongchou_order')." o"
					." left join ".tablename('mc_member_address')." a on o.addressid = a.id "
					. " where $condition ORDER BY o.status DESC, o.createtime DESC "
					. "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql,$paras);
			$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'danger','name' => '余额支付'),
					'2' => array('css' => 'info', 'name' => '在线支付'),
					'3' => array('css' => 'warning', 'name' => '货到付款')
			);
			$orderstatus = array (
					'-1' => array('css' => 'default', 'name' => '已取消'),
					'0' => array('css' => 'danger', 'name' => '待付款'),
					'1' => array('css' => 'info', 'name' => '待发货'),
					'2' => array('css' => 'warning', 'name' => '待收货'),
					'3' => array('css' => 'success', 'name' => '已完成')
			);
			foreach ($list as &$value) {
				$s = $value['status'];
				$value['statuscss'] = $orderstatus[$value['status']]['css'];
				$value['status'] = $orderstatus[$value['status']]['name'];
				if ($s < 1) {
					$value['css'] = $paytype[$s]['css'];
					$value['paytype'] = $paytype[$s]['name'];
					continue;
				}
				$value['css'] = $paytype[$value['paytype']]['css'];
				if ($value['paytype'] == 2) {
					if (empty($value['transid'])) {
						$value['paytype'] = '支付宝支付';
					} else {
						$value['paytype'] = '微信支付';
					}
				} else {
					$value['paytype'] = $paytype[$value['paytype']]['name'];
				}
			}
			$total = pdo_fetchcolumn(
						'SELECT COUNT(*) FROM ' . tablename('hx_zhongchou_order') . " o "
						." left join ".tablename('mc_member_address')." a on o.addressid = a.id "
						." WHERE $condition", $paras);
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					// !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
					$row['dispatch'] = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_dispatch') . " WHERE id = :id", array(':id' => $row['dispatch']));
				}
				unset($row);
			}
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_order') . " WHERE id = :id", array(':id' => $id));
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			if (checksubmit('confirmsend')) {
				if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
					message('请输入快递单号！');
				}
				$item = pdo_fetch("SELECT transid FROM " . tablename('hx_zhongchou_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 1);
				}
				pdo_update(
					'hx_zhongchou_order',
					array(
						'status' => 2,
						'remark' => $_GPC['remark'],
						'express' => $_GPC['express'],
						'expresscom' => $_GPC['expresscom'],
						'expresssn' => $_GPC['expresssn'],
					),
					array('id' => $id)
				);
				message('发货操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelsend')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('hx_zhongchou_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
				}
				pdo_update(
					'hx_zhongchou_order',
					array(
						'status' => 1,
						'remark' => $_GPC['remark'],
					),
					array('id' => $id)
				);
				message('取消发货操作成功！', referer(), 'success');
			}
			if (checksubmit('finish')) {
				pdo_update('hx_zhongchou_order', array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancel')) {
				pdo_update('hx_zhongchou_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelpay')) {
				pdo_update('hx_zhongchou_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				message('取消订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('confrimpay')) {
				pdo_update('hx_zhongchou_order', array('status' => 1, 'paytype' => 2, 'remark' => $_GPC['remark']), array('id' => $id));


				$order = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_order') . " WHERE id = '{$id}'");
				$pid = $order['pid'];
				$item_id = $order['item_id'];
				$project = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = '{$pid}'");
				$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE id = '{$item_id}'");
				pdo_update('hx_zhongchou_project',array('finish_price'=>$project['finish_price'] + $order['item_price'],'donenum'=>$project['donenum']+1),array('id'=>$pid));
				pdo_update('hx_zhongchou_project_item',array('donenum'=>$item['donenum']+1),array('id'=>$item_id));
				message('确认订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('close')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('hx_zhongchou_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['reson']);
				}
				if ($_GPC['tuikuan'] == 1) {
					$result = $this->sendMoney($item['from_user'],$item['price'],'众筹失败退款');
					if ($result['code'] != 'SUCCESS') {
						message('退款失败，失败原因'.$result['msg'],referer(),'error');
					}
				}
				pdo_update('hx_zhongchou_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单关闭操作成功！', referer(), 'success');
			}
			if (checksubmit('open')) {
				pdo_update('hx_zhongchou_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				message('开启订单操作成功！', referer(), 'success');
			}
			$dispatch = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
			if (!empty($dispatch) && !empty($dispatch['express'])) {
				$express = pdo_fetch("select * from " . tablename('hx_zhongchou_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
			}
			$item['user'] = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = {$item['addressid']}");
		} elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			if (pdo_delete('hx_zhongchou_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		}
		include $this->template('order');
	}
	
	public function doWebProject() {
		global $_GPC, $_W;
		load()->func('tpl');
		$category = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		$dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight from " . tablename("hx_zhongchou_dispatch") . " WHERE weid = {$_W['uniacid']} order by displayorder desc");
		if (!empty($category)) {
			$children = '';
			foreach ($category as $cid => $cate) {
				if (!empty($cate['parentid'])) {
					$children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
				}
			}
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$item_id = intval($_GPC['item_id']);
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('抱歉，项目不存在或是已经删除！', '', 'error');
				}
			}
			if (empty($category)) {
				message('抱歉，请您先添加项目分类！', $this->createWebUrl('category', array('op' => 'post')), 'error');
			}
			$step = intval($_GPC['step']) ? intval($_GPC['step']) : 1;
			if ($step == 1) {
					
			}elseif($step == 2) {
				$items = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE weid = '{$_W['uniacid']}' AND pid = '{$id}' ORDER BY id ASC");
				//print_r($items);
				if ($item_id) {
					$item_info = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE id = :id", array(':id' => $item_id));
				}
				if (checksubmit('submit')) {
					if (empty($_GPC['title'])) {
						message('项目名称必填，请返回修改');
					}
					$data = array(
						'weid' => $_W['uniacid'],
						'displayorder' => intval($_GPC['displayorder']),
						'title' => $_GPC['title'],
						'limit_price' => intval($_GPC['limit_price']),
						'deal_days' => intval($_GPC['deal_days']),
						'isrecommand' => intval($_GPC['isrecommand']),
						'pcate' => intval($_GPC['pcate']),
						'ccate' => intval($_GPC['ccate']),
						'thumb' => $_GPC['thumb'],
						'video' => $_GPC['video'],
						'brief' => htmlspecialchars_decode($_GPC['brief']),
						'content' => htmlspecialchars_decode($_GPC['content']),
						'url' => $_GPC['url'],
						'nosubuser' => intval($_GPC['nosubuser']),
						'subsurl' => trim($_GPC['subsurl']),
						'starttime' => strtotime($_GPC['starttime']),
						'show_type' => intval($_GPC['show_type']),
						'type' => intval($_GPC['type']),
						'lianxiren' => $_GPC['lianxiren'],
						'qq' => $_GPC['qq'],
						'status' => 3,
						'createtime' => TIMESTAMP,
						);
					//print_r($data);exit();
					if (empty($id)) {
						pdo_insert('hx_zhongchou_project', $data);
						$id = pdo_insertid();
					} else {
						unset($data['createtime']);
						pdo_update('hx_zhongchou_project', $data, array('id' => $id));
					}
					message('保存成功,即将进入下一步',$this->createWebUrl('project',array('id'=>$id,'op'=>'post','step'=>'2')),'success');
				}
			}elseif($step == 3) {
				if (checksubmit('display')) {
					if (!empty($_GPC['displayorder'])) {
						foreach ($_GPC['displayorder'] as $item_id => $displayorder) {
							pdo_update('hx_zhongchou_project_item', array('displayorder' => $displayorder), array('id' => $item_id));
						}
						message('排序更新成功！', $this->createWebUrl('project',array('id'=>$id,'op'=>'post','step'=>'2')), 'success');
					}
				}
				if (checksubmit('submit')) {
					$insert = array(
						'weid' => $_W['uniacid'],
						'pid' => intval($_GPC['id']),
						'displayorder' => intval($_GPC['displayorder']),
						'price' => $_GPC['price'],
						'description' => htmlspecialchars_decode($_GPC['description']),
						'thumb' => $_GPC['thumb'],
						'limit_num' => intval($_GPC['limit_num']),
						'repaid_day' => intval($_GPC['repaid_day']),
						'return_type' => intval($_GPC['return_type']),
						'dispatch' => $_GPC['dispatch'],
						'createtime' => TIMESTAMP,
						);
					if (empty($item_id)) {
						pdo_insert('hx_zhongchou_project_item', $insert);
					} else {
						unset($insert['createtime']);
						pdo_update('hx_zhongchou_project_item', $insert, array('id' => $item_id));
					}
					message('保存成功,继续添加',$this->createWebUrl('project',array('id'=>$id,'op'=>'post','step'=>'2')),'success');
				}
				$items = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_project_item') . " WHERE weid = '{$_W['uniacid']}' AND pid = '{$id}' ORDER BY id ASC");
				if (empty($items)) {
					message('您尚未添加项目回报，请返回添加',$this->createWebUrl('project',array('id'=>$id,'op'=>'post','step'=>'2')),'error');
				}
			}elseif ($step == 4) {
				if (checksubmit('finish')) {
					pdo_update('hx_zhongchou_project', array('status' => 3), array('id' => $id));
					message('恭喜您，活动已经成功开始！',$this->createWebUrl('project',array('op'=>'display')),'success');
				}else{
					message('活动保存成功！',$this->createWebUrl('project',array('op'=>'display')),'success');
				}
			}
		} elseif ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['cate_2'])) {
				$cid = intval($_GPC['cate_2']);
				$condition .= " AND ccate = '{$cid}'";
			} elseif (!empty($_GPC['cate_1'])) {
				$cid = intval($_GPC['cate_1']);
				$condition .= " AND pcate = '{$cid}'";
			}
			if (isset($_GPC['status'])) {
				$condition .= " AND status = '" . intval($_GPC['status']) . "'";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_project') . " WHERE weid = '{$_W['uniacid']}'  $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_zhongchou_project') . " WHERE weid = '{$_W['uniacid']}'  $condition");
			$pager = pagination($total, $pindex, $psize);
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id, thumb FROM " . tablename('hx_zhongchou_project') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，项目不存在或是已经被删除！');
			}
			pdo_delete('hx_zhongchou_project', array('id' => $id));
			pdo_delete('hx_zhongchou_project_item', array('pid' => $id));
			message('删除成功！', referer(), 'success');
		} elseif ($operation == 'itemdelete') {
			$id = intval($_GPC['id']);
			$item_id = intval($_GPC['item_id']);
			$row = pdo_fetch("SELECT id, thumb FROM " . tablename('hx_zhongchou_project_item') . " WHERE id = :id", array(':id' => $item_id));
			if (empty($row)) {
				message('抱歉，项目不存在或是已经被删除！');
			}
			pdo_delete('hx_zhongchou_project_item', array('id' => $item_id));
			message('删除成功！', $this->createWebUrl('project',array('id'=>$id,'op'=>'post','step'=>'2')), 'success');
		}
		include $this->template('project');
	}
	public function doWebSetProjectProperty() {
		global $_GPC, $_W;
		if ($_GPC['op'] == 'checkproject') {
			$project_id = intval($_GPC['project_id']);
			$status = intval($_GPC['status']);
			$reson = $_GPC['reson'];
			pdo_update("hx_zhongchou_project", array('status'=>$status,'reason'=>$reson,'starttime'=>time()), array("id" => $project_id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1,'project_id'=>$project_id)));
		}else{
			$id = intval($_GPC['id']);
			$type = $_GPC['type'];
			$data = intval($_GPC['data']);
			if (in_array($type, array('hot', 'recommand'))) {
				$data = ($data==1?'0':'1');
				pdo_update("hx_zhongchou_project", array("is" . $type => $data), array("id" => $id, "weid" => $_W['uniacid']));
				die(json_encode(array("result" => 1, "data" => $data)));
			}
			if (in_array($type, array('status'))) {
				  $data = ($data==1?'0':'1');
			   pdo_update("hx_zhongchou_project", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			   die(json_encode(array("result" => 1, "data" => $data)));
			}
			die(json_encode(array("result" => 0)));
		}
		
	}
	public function doMobileContactUs() {
		global $_W;
		$cfg = $this->module['config'];
		include $this->template('contactus');
	}
	public function doWebCategory() {
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('hx_zhongchou_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('category');
		} elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_category') . " WHERE id = '$id'");
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM " . tablename('hx_zhongchou_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['catename'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'thumb' => $_GPC['thumb'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'description' => $_GPC['description'],
					'parentid' => intval($parentid),
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('hx_zhongchou_category', $data, array('id' => $id));
				} else {
					pdo_insert('hx_zhongchou_category', $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			include $this->template('category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('hx_zhongchou_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
			pdo_delete('hx_zhongchou_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
		}
	}
	public function doWebDispatch() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_dispatch') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'displayorder' => intval($_GPC['displayorder']),
					'dispatchtype' => intval($_GPC['dispatchtype']),
					'dispatchname' => $_GPC['dispatchname'],
					'express' => $_GPC['express'],
					'firstprice' => $_GPC['firstprice'],
					'firstweight' => $_GPC['firstweight'],
					'secondprice' => $_GPC['secondprice'],
					'secondweight' => $_GPC['secondweight'],
					'description' => $_GPC['description']
				);
				if (!empty($id)) {
					pdo_update('hx_zhongchou_dispatch', $data, array('id' => $id));
				} else {
					pdo_insert('hx_zhongchou_dispatch', $data);
					$id = pdo_insertid();
				}
				message('更新配送方式成功！', $this->createWebUrl('dispatch', array('op' => 'display')), 'success');
			}
			//修改
			$dispatch = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_dispatch') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
			$express = pdo_fetchall("select * from " . tablename('hx_zhongchou_express') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$dispatch = pdo_fetch("SELECT id  FROM " . tablename('hx_zhongchou_dispatch') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($dispatch)) {
				message('抱歉，配送方式不存在或是已经被删除！', $this->createWebUrl('dispatch', array('op' => 'display')), 'error');
			}
			pdo_delete('hx_zhongchou_dispatch', array('id' => $id));
			message('配送方式删除成功！', $this->createWebUrl('dispatch', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('dispatch', TEMPLATE_INCLUDEPATH, true);
	}
	public function doWebExpress() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_express') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				if (empty($_GPC['express_name'])) {
					message('抱歉，请输入物流名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'displayorder' => intval($_GPC['displayorder']),
					'express_name' => $_GPC['express_name'],
					'express_url' => $_GPC['express_url'],
					'express_area' => $_GPC['express_area'],
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('hx_zhongchou_express', $data, array('id' => $id));
				} else {
					pdo_insert('hx_zhongchou_express', $data);
					$id = pdo_insertid();
				}
				message('更新物流成功！', $this->createWebUrl('express', array('op' => 'display')), 'success');
			}
			//修改
			$express = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_express') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$express = pdo_fetch("SELECT id  FROM " . tablename('hx_zhongchou_express') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($express)) {
				message('抱歉，物流方式不存在或是已经被删除！', $this->createWebUrl('express', array('op' => 'display')), 'error');
			}
			pdo_delete('hx_zhongchou_express', array('id' => $id));
			message('物流方式删除成功！', $this->createWebUrl('express', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('express', TEMPLATE_INCLUDEPATH, true);
	}
	public function doWebAdv() {
		global $_W, $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb']
				);
				if (!empty($id)) {
					pdo_update('hx_zhongchou_adv', $data, array('id' => $id));
				} else {
					pdo_insert('hx_zhongchou_adv', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('hx_zhongchou_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id  FROM " . tablename('hx_zhongchou_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
			}
			pdo_delete('hx_zhongchou_adv', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('adv', TEMPLATE_INCLUDEPATH, true);
	}

	public function doMobileMyOrder() {
		global $_W, $_GPC;
		$this->checkAuth();
		$carttotal = $this->getCartTotal();
		$op = $_GPC['op'];
		if ($op == 'confirm') {
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_order') . " WHERE id = :id AND from_user = :from_user", array(':id' => $orderid, ':from_user' => $_W['fans']['from_user']));
			if (empty($order)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
			}
			pdo_update('hx_zhongchou_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['fans']['from_user']));
			message('确认收货完成！', $this->createMobileUrl('myorder'), 'success');
		} else if ($op == 'detail') {
			$orderid = intval($_GPC['orderid']);
			$item = pdo_fetch("SELECT * FROM " . tablename('hx_zhongchou_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' and id='{$orderid}' limit 1");
			if (empty($item)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
			}
			$address = pdo_fetch("select * from " . tablename('mc_member_address') . " where id=:id limit 1", array(":id" => $item['addressid']));
			//print_r($address);
			$dispatch = pdo_fetch("select id,dispatchname from " . tablename('hx_zhongchou_dispatch') . " where id=:id limit 1", array(":id" => $item['dispatch']));
			include $this->template('order_detail');
		} else {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = intval($_GPC['status']);
			$where = " weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'";
			if ($status == 2) {
				$where.=" and ( status=1 or status=2 )";
			} else {
				$where.=" and status=$status";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('hx_zhongchou_order') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_zhongchou_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$pager = pagination($total, $pindex, $psize);
			include $this->template('order');
		}
	}

	protected function exportexcel($data=array(),$title=array(),$filename='report'){
    	header("Content-type:application/octet-stream");
    	header("Accept-Ranges:bytes");
    	header("Content-type:application/vnd.ms-excel");  
    	header("Content-Disposition:attachment;filename=".$filename.".xls");
    	header("Pragma: no-cache");
    	header("Expires: 0");
    	//导出xls 开始
    	if (!empty($title)){
    	    foreach ($title as $k => $v) {
    	        $title[$k]=iconv("UTF-8", "GB2312",$v);
    	    }
    	    $title= implode("\t", $title);
    	    echo "$title\n";
    	}
    	if (!empty($data)){
    	    foreach($data as $key=>$val){
    	        foreach ($val as $ck => $cv) {
    	            $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
    	        }
    	        $data[$key]=implode("\t", $data[$key]);
    	        
    	    }
    	    echo implode("\n",$data);
    	}
 	}

 	public function getFinishPrice($pid){
 		$project = pdo_fetch("SELECT * FROM ".tablename('hx_zhongchou_project')." WHERE id=:id",array(':id'=>$pid));
 		$wc = pdo_fetchcolumn("SELECT SUM(price) FROM ".tablename('hx_zhongchou_order_ws')." WHERE pid='{$pid}' AND status=1");
 		return $project['finish_price'] + $wc;
 	}

 	private function sendMoney($openid,$money,$desc = '退款') {
		global $_W;
		$uniacid = $_W['uniacid'];
		$api = $this->module['config'];
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
		$pars = array();
		$pars['mch_appid'] = $api['appid'];
		$pars['mchid'] = $api['mchid'];
		$pars['nonce_str'] = random(32);
		$pars['partner_trade_no'] = date('Ymd') . rand(1,100);
        $pars['openid'] = $openid;
        $pars['check_name'] = 'NO_CHECK';
        $pars['amount'] = $money;
        $pars['desc'] = $desc;
        $pars['spbill_create_ip'] = $api['ip'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$api['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = ZC_ROOT . '/cert/rootca.pem.' . $api['pemname'];
        $extras['CURLOPT_SSLCERT'] = ZC_ROOT . '/cert/apiclient_cert.pem.' . $api['pemname'];
        $extras['CURLOPT_SSLKEY'] = ZC_ROOT . '/cert/apiclient_key.pem.' . $api['pemname'];

        load()->func('communication');
        $procResult = null;
        $response = ihttp_request($url, $xml, $extras);
        if ($response['code'] == 200) {
			$responseObj = simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
			$responseObj = (array)$responseObj;
			$return['code'] =  $responseObj['return_code'];
			$return['err_code'] =  $responseObj['err_code'];
			$return['msg'] =  $responseObj['return_msg'];
			return $return;
		}
	}

}