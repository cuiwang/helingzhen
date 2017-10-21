<?php
/**
 * 超级文章/图文模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');
define("COOKIE_FILE", IA_ROOT . '/addons/tech_superarticle/pic.cookie');
define("COOKIE_FILE1", IA_ROOT . '/addons/tech_superarticle/pic1.cookie');
define("COOKIE_FILE2", IA_ROOT . '/addons/tech_superarticle/pic2.cookie');
require_once IA_ROOT . '/addons/tech_superarticle/public/QueryList.class.php';

class Tech_superarticleModuleSite extends WeModuleSite {

	public function __construct(){
            global $_W, $_GPC;
            //$this->check_browser();
    }
    public function dowebTest() {
        global $_W, $_GPC;
        $remote = $_W['setting']['remote'];
        var_dump($remote);
    }
    public function doWebGetimg() {
		global $_W, $_GPC;
		load()->func('file');
		$file = file_upload($_FILES['bt_img1']);
		die(json_encode(array('code' => $_FILES['bt_img']['name'], 'path' => tomedia($file['path']))));
    }
    public function tongbuwe7($art_data) {
		global $_W, $_GPC;
		$id = intval($art_data['aid']);
		if (empty($art_data['title'])) {
			message('标题不能为空，请输入文章标题！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'iscommend' => intval($art_data['option']['commend']),
			'ishot' => intval($art_data['option']['hot']),
			'pcate' => intval($art_data['category']['parentid']),
			'ccate' => intval($art_data['category']['childid']),
			'template' => addslashes($art_data['template']),
			'title' => addslashes($art_data['title']),
			'description' => addslashes($art_data['description']),
			'content' => htmlspecialchars_decode($art_data['content'], ENT_QUOTES),
			'incontent' => intval($art_data['incontent']),
			'source' => addslashes($art_data['source']),
			'author' => addslashes($art_data['author']),
			'displayorder' => intval($art_data['displayorder']),
			'linkurl' => addslashes($art_data['linkurl']),
			'createtime' => TIMESTAMP,
			'click' => intval($art_data['click'])
		);
		if (!empty($art_data['thumb'])) {
			$data['thumb'] = $art_data['thumb'];
		} else {
			$data['thumb'] = '';
		}
		$keyword = str_replace('，', ',', trim($art_data['keyword']));
		$keyword = explode(',', $keyword);
		if(!empty($keyword)) {
			$rule['uniacid'] = $_W['uniacid'];
			$rule['name'] = '文章：' . $art_data['title'] . ' 触发规则';
			$rule['module'] = 'news';
			$rule['status'] = 1;
			$keywords = array();
			foreach($keyword as $key) {
				$key = trim($key);
				if(empty($key)) continue;
				$keywords[] = array(
					'uniacid' => $_W['uniacid'],
					'module' => 'news',
					'content' => $key,
					'status' => 1,
					'type' => 1,
					'displayorder' => 1,
				);
			}
			$reply['title'] = $art_data['title'];
			$reply['description'] = $art_data['description'];
			$reply['thumb'] = $data['thumb'];
			$reply['url'] = murl('site/site/detail', array('id' => $id));
		}
		if(!empty($art_data['credit']['status'])) {
			$credit['status'] = intval($art_data['credit']['status']);
			$credit['limit'] = intval($art_data['credit']['limit']) ? intval($art_data['credit']['limit']) : message('请设置积分上限');
			$credit['share'] = intval($art_data['credit']['share']) ? intval($art_data['credit']['share']) : message('请设置分享时赠送积分多少');
			$credit['click'] = intval($art_data['credit']['click']) ? intval($art_data['credit']['click']) : message('请设置阅读时赠送积分多少');
			$data['credit'] = iserializer($credit);
		} else {
			$data['credit'] = iserializer(array('status' => 0, 'limit' => 0, 'share' => 0, 'click' => 0));
		}	
		if (empty($id)) {
			if(!empty($keywords)) {
				pdo_insert('rule', $rule);
				$rid = pdo_insertid();
				foreach($keywords as $li) {
					$li['rid'] = $rid;
					pdo_insert('rule_keyword', $li);
				}
				$reply['rid'] = $rid;
				pdo_insert('news_reply', $reply);
				$data['rid'] = $rid;
			}
			pdo_insert('site_article', $data);
			$aid = pdo_insertid();
			pdo_update('news_reply', array('url' => murl('site/site/detail', array('id' => $aid))), array('rid' => $rid));
			return $aid;
		} else {
			unset($data['createtime']);
			pdo_delete('rule', array('id' => $item['rid'], 'uniacid' => $_W['uniacid']));
			pdo_delete('rule_keyword', array('rid' => $item['rid'], 'uniacid' => $_W['uniacid']));
			pdo_delete('news_reply', array('rid' => $item['rid']));
			if(!empty($keywords)) {
				pdo_insert('rule', $rule);
				$rid = pdo_insertid();

				foreach($keywords as $li) {
					$li['rid'] = $rid;
					pdo_insert('rule_keyword', $li);
				}
                //www.efwww.com
				$reply['rid'] = $rid;
				pdo_insert('news_reply', $reply);
				$data['rid'] = $rid;
			} else {
				$data['rid'] = 0;
				$data['kid'] = 0;
			}
			pdo_update('site_article', $data, array('id' => $id));
			return $id;
		}
    }
    public function s_download() {
    	global $_W, $_GPC;
		set_time_limit(0);
		load()->func('file');
		$acc = WeAccount::create();
		/*if(is_error($acc)) {
			message($acc, ' ', 'ajax');
		}*/
		$post['type'] = 'news';
		$post['page'] = 1;
		$type = $types = $post['type'];
		$count = $acc->getMaterialCount();
		/*if(is_error($count)) {
			message($count, '', 'ajax');
		}*/
		$cache_key = "media_sync:{$_W['uniacid']}";
		$has = cache_read($cache_key);
		if(!is_array($has)) {
			$has = array(0);
		}

		$key = $type . '_count';
		$total = $count[$key];
		$pindex = max(1, intval($post['page']));
		$psize = 15;
		$offset = ($pindex - 1) * $psize;
		if($total == 0 || ($pindex > ceil($total / $psize))) {
			$has_str = implode(',', $has);
			pdo_query('DELETE FROM ' . tablename('wechat_attachment') . "  WHERE uniacid = :uniacid AND type = :type AND id NOT IN ({$has_str})", array(':uniacid' => $_W['uniacid'], ':type' => $type));
			if($type == 'news') {
				pdo_query('DELETE FROM ' . tablename('wechat_news') . "  WHERE uniacid = :uniacid AND attach_id NOT IN ({$has_str})", array(':uniacid' => $_W['uniacid']));
			}
			cache_delete($cache_key);
			/*message(error(-2, $total), '', 'ajax');*/
		}
		if($pindex == ceil($total / $psize)) {
			$psize = $total % $psize == 0 ? 15 : ($total % $psize);
		}
		$result = $acc->batchGetMaterial($type, $offset, $psize);
		/*if(is_error($result)) {
			message($result, '', 'ajax');
		}*/
		$fail = array();
		if($type == 'voice') {
			$type = 'audio';
		}
		foreach($result['item'] as $data) {
			$pathinfo = pathinfo($data['name']);
			$data['name'] = $pathinfo['filename'] . '.' . $pathinfo['extension'];
			if($type != 'news') {
				$media = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'media_id' => $data['media_id']));
				$is_down = 0;
				$url = $tag = '';
				if($type == 'image') {
					if(!empty($media) && !empty($media['attachment'])) {
						if(strexists($media['attachment'], '//mmbiz.qlogo.cn') || (file_exists(ATTACHMENT_ROOT . $media['attachment']))) {
							$has[] = $media['id'];
							continue;
						}
					}
					if(strexists($data['url'], '//mmbiz.qlogo.cn')) {
						$url = $tag = $data['url'];
						$is_down = 1;
					}
				} elseif($types == 'voice') {
					if(!empty($media) && !empty($media['attachment'])) {
						if(file_exists(ATTACHMENT_ROOT . $media['attachment'])) {
							$has[] = $media['id'];
							continue;
						}
					}
				}
				if(!$is_down) {
									$stream = $acc->getMaterial($data['media_id']);
					if(is_error($stream)) {
						$data['message'] = $stream['message'];
						$fail[$data['media_id']] = $data;
						continue;
					}
				}
				$insert = array(
					'uniacid' => $_W['uniacid'],
					'acid' => $_W['acid'],
					'uid' => $_W['uid'],
					'filename' => $data['name'],
					'attachment' => $stream,
					'media_id' => $data['media_id'],
					'type' => $types,
					'model' => 'perm',
					'tag' => $tag,
					'createtime' => $data['update_time']
				);
				if(empty($media)) {
					pdo_insert('wechat_attachment', $insert);
					$media['id'] = pdo_insertid();
				} else {
					pdo_update('wechat_attachment', $insert, array('uniacid' => $_W['uniacid'], 'media_id' => $data['media_id']));
					$media_id = $media['id'];
				}
				$has[] = $media['id'];
			} else {
				$media = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'media_id' => $data['media_id']));
				if(empty($media)) {
					$insert = array(
						'uniacid' => $_W['uniacid'],
						'acid' => $_W['acid'],
						'uid' => $_W['uid'],
						'media_id' => $data['media_id'],
						'type' => $types,
						'model' => 'perm',
						'createtime' => $data['update_time']
					);
					pdo_insert('wechat_attachment', $insert);
					$insert_id = pdo_insertid();
				} else {
					pdo_update('wechat_attachment', array('createtime' => $data['update_time']), array('uniacid' => $_W['uniacid'], 'media_id' => $data['media_id']));
					$insert_id = $media['id'];
					pdo_delete('wechat_news', array('uniacid' => $_W['uniacid'], 'attach_id' => $insert_id));
				}
							$items = $data['content']['news_item'];
				if(!empty($items)) {
					foreach($items as $item) {
						$item['attach_id'] = $insert_id;
						$item['uniacid'] = $_W['uniacid'];
						unset($item['need_open_comment']);
						unset($item['only_fans_can_comment']);
						pdo_insert('wechat_news', $item);
					}
				}
				$has[] = $insert_id;
			}//易 福 源 码网
		}
		cache_write($cache_key, $has);
    }
    public function doWebMass() {
    	global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'send';
		if (IMS_VERSION > 0.8) {
	    	if ($operation == 'send') {
	    		$categorys = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1 ORDER BY id DESC");
				if (!$categorys) {
					message('抱歉，请先创建文章分类！', $this->createWebUrl('category', array('op' => 'display')), 'error');
				}
	    		$condition = ' where a.eid = :eid and a.is_delete = :is_delete and a.is_save = 1';
				$params = array(':eid'=>$_W['uniacid'],':is_delete'=>0);
	            $pindex = max(1, intval($_GPC['page']));
	            $psize = 15;
	            $starttime = empty($_GPC['time']['start']) ? strtotime('-1 year') : strtotime($_GPC['time']['start']);
	            $endtime = empty($_GPC['time']['end']) ? time() : strtotime($_GPC['time']['end'])+86400;
	            $condition.=' and a.createtime > :starttime and a.createtime < :endtime';
	            $params[':starttime']=$starttime;
	            $params[':endtime']=$endtime;
	            //$condition .=" and a.createtime > '{$starttime}' and a.createtime < '{$endtime}'";
	            if(!empty($_GPC['ar_title'])){
	                $ar_title = $_GPC['ar_title'];
	                $condition .= " and a.title like '%{$_GPC['ar_title']}%' or a.author like '%{$_GPC['ar_title']}%'";
	            }
	            if(!empty($_GPC['category_id'])){
	                $category_id = $_GPC['category_id'];
	                $condition .= " and a.category_id = '{$category_id}'";
	            }
	            $condition.=' ORDER BY a_order DESC, createtime DESC';
	            $sql = 'select count(a.id) from ' . tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id". $condition;
				
				$total = pdo_fetchcolumn($sql, $params);
	            //$count = pdo_fetchcolumn("select count(a.*) from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id where a.eid =:eid",array(':eid'=>$_W['uniacid']));
				if(!empty($total)){ 
					$sql = "select s.*,a.* from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id ".$condition." LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
					$articles = pdo_fetchall($sql, $params);
					foreach ($articles as $key => $value) {
						$cat = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE id = '{$value['category_id']}'");
						$articles[$key]['cat'] = $cat['name'];
					}
					$pager = pagination($total, $pindex, $psize);
				}	
	    		include $this->template('mass_list');
	    	} elseif ($operation == 'add') {

	    		$attach['uniacid'] = $_W['uniacid'];
	    		$attach['acid'] = $_W['acid'];
	    		$attach['uid'] = 0;
	    		$attach['filename'] = '';
	    		$attach['attachment'] = '';
	    		$attach['media_id'] = '';
	    		$attach['width'] = 0;
	    		$attach['height'] = 0;
	    		$attach['type'] = 'news';
	    		$attach['model'] = 'local';
	    		$attach['tag'] = '';
	    		$attach['createtime'] = time();
				pdo_insert('wechat_attachment', $attach);
				$attach_id = pdo_insertid();
				foreach ($_GPC['art_lists'] as $key => $value) {
					$art_data = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_article') . " WHERE id = '{$value}'");
		    		$news['uniacid'] = $_W['uniacid'];
		    		$news['attach_id'] = $attach_id;
		    		$news['thumb_media_id'] = '';
		    		$news['thumb_url'] = $art_data['thumb'];
		    		$news['title'] = $art_data['title'];
		    		$news['author'] = $art_data['author'];
		    		$news['digest'] = $art_data['desc'];
		    		$news['content'] = $art_data['content'];
		    		$news['content_source_url'] = '';
		    		$news['show_cover_pic'] = 0;
		    		$news['url'] = '';
		    		$news['displayorder'] = 0;
		    		pdo_insert('wechat_news', $news);
				}				
	    		$newsid = $attach_id;
	    		$news_list = pdo_fetchall("SELECT * FROM " . tablename('wechat_news') . " WHERE uniacid = '{$_W['uniacid']}' AND attach_id = '{$newsid}'  ORDER BY displayorder DESC");
				include $this->template('material-post');
	    	} elseif ($operation == 'list') {
	    		message('图文素材已成功同步至微信公众平台，请前往微信公众平台群发', $this->createWebUrl('mass'), 'success');
	    	}
		} else {
			if ($operation == 'send') {
	    		$this->s_download();
				$type = 'news';
				$condition = " WHERE uniacid = :uniacid AND type = :type AND model = :model AND media_id != ''";
				$params = array(':uniacid' => $_W['uniacid'], ':type' => $type, ':model' => 'perm');
				$id = intval($_GPC['id']);
				if($id > 0) {
					$condition .= ' AND id = :id';
					$params[':id'] = $id;
				}

				$pindex = max(1, intval($_GPC['page']));
				$psize = 10;
				$limit = " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ", {$psize}";

				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_attachment') . $condition, $params);
				$lists = pdo_fetchall('SELECT * FROM ' . tablename('wechat_attachment') . $condition . $limit, $params);
				if(!empty($lists)) {
					foreach($lists as &$row) {
						if($type == 'video') {
							$row['tag'] = iunserializer($row['tag']);
						} elseif($type == 'news') {
							$row['items'] = pdo_fetchall("SELECT * FROM " . tablename('wechat_news') . " WHERE uniacid = :uniacid AND attach_id = :attach_id ORDER BY id ASC", array(':uniacid' => $_W['uniacid'], ':attach_id' => $row['id']));
						}
					}
				}
				$pager = pagination($total, $pindex, $psize);

					$groups = pdo_fetch('SELECT * FROM ' . tablename('mc_fans_groups') . ' WHERE uniacid = :uniacid AND acid = :acid', array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid']));
				if(!empty($groups)) {
					$groups = iunserializer($groups['groups']);
				}
				include $this->template('mass');
	    	} elseif ($operation == 'add') {
	    		include $this->template('mass');
	    	} elseif ($operation == 'list') {
	    		include $this->template('test_list');
	    	}
		}
    }
    /*public function doWebMass() {
    	global $_W, $_GPC;
    	echo IMS_VERSION;
    	echo "444444444444444444444445554444444";
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'send';
    	if ($operation == 'send') {
    		$attach['uniacid'] = $_W['uniacid'];
    		$attach['acid'] = $_W['acid'];
    		$attach['uid'] = 1;
    		$attach['filename'] = '';
    		$attach['attachment'] = '';
    		$attach['media_id'] = '';
    		$attach['width'] = 0;
    		$attach['height'] = 0;
    		$attach['type'] = 'news';
    		$attach['model'] = 'perm';
    		$attach['tag'] = '';
    		$attach['createtime'] = time();
			pdo_insert('wechat_attachment', $attach);
			$attach_id = pdo_insertid();

    		$news['uniacid'] = $_W['uniacid'];
    		$news['attach_id'] = $attach_id;
    		$news['thumb_media_id'] = '4_VjsN-bmtNEMG3GFEPnb0jo-CJrg4Hm3MMspkpZnGk';
    		$news['thumb_url'] = 'http://test.9zhulu.com/web/index.php?c=utility&a=wxcode&do=image&attach=http%3A%2F%2Fmmbiz.qpic.cn%2Fmmbiz_jpg%2FmNQU4GHfUH4hoqFNJftR28luibCJGv8YovpYZw540kdbMFDQyn5uqjXJ7KNNWIyBESkl19V9rvMtHIP0nBFvPSQ%2F0%3Fwx_fmt%3Djpeg';
    		$news['title'] = '1111111';
    		$news['author'] = '111';
    		$news['digest'] = '1111111111111111';
    		$news['content'] = '<p>11111111<img src="http://test.9zhulu.com/web/index.php?c=utility&a=wxcode&do=image&attach=http%3A%2F%2Fmmbiz.qpic.cn%2Fmmbiz_jpg%2FmNQU4GHfUH4hoqFNJftR28luibCJGv8YoE1D8GBtaYCiaObHiarj6e9ymJY2TdibIicHxcEpt8NIvGZjcUDKDOrBR5w%2F0" width="100%"></p>';
    		$news['content_source_url'] = '';
    		$news['show_cover_pic'] = 0;
    		$news['url'] = 'http://mp.weixin.qq.com/s?__biz=MzUzMDAxNTU1Mw==&mid=100000014&idx=1&sn=df88a79e9ce35303ff1bb56ddbbcdb2b&chksm=7a5975204d2efc366c222bfd4d28ad34378c1a05f6229bbae57b4565f8368c2af786f28e070f#rd';
    		$news['displayorder'] = 0;
    		for ($i=0; $i < 5; $i++) { 
    			pdo_insert('wechat_news', $news);
    		}
			
    		$newsid = $attach_id;
    		$news_list = pdo_fetchall("SELECT * FROM " . tablename('wechat_news') . " WHERE uniacid = '{$_W['uniacid']}' AND attach_id = '{$newsid}'  ORDER BY displayorder DESC");
			include $this->template('material-post');
    	} elseif ($operation == 'add') {
    		$categorys = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1 ORDER BY id DESC");
			if (!$categorys) {
				message('抱歉，请先创建文章分类！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
    		$condition = ' where a.eid = :eid and a.is_delete = :is_delete and a.is_save = 1';
			$params = array(':eid'=>$_W['uniacid'],':is_delete'=>0);
            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $starttime = empty($_GPC['time']['start']) ? strtotime('-1 year') : strtotime($_GPC['time']['start']);
            $endtime = empty($_GPC['time']['end']) ? time() : strtotime($_GPC['time']['end'])+86400;
            $condition.=' and a.createtime > :starttime and a.createtime < :endtime';
            $params[':starttime']=$starttime;
            $params[':endtime']=$endtime;
            //$condition .=" and a.createtime > '{$starttime}' and a.createtime < '{$endtime}'";
            if(!empty($_GPC['ar_title'])){
                $ar_title = $_GPC['ar_title'];
                $condition .= " and a.title like '%{$_GPC['ar_title']}%' or a.author like '%{$_GPC['ar_title']}%'";
            }
            if(!empty($_GPC['category_id'])){
                $category_id = $_GPC['category_id'];
                $condition .= " and a.category_id = '{$category_id}'";
            }
            $condition.=' ORDER BY a_order DESC, createtime DESC';
            $sql = 'select count(a.id) from ' . tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id". $condition;
			
			$total = pdo_fetchcolumn($sql, $params);
            //$count = pdo_fetchcolumn("select count(a.*) from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id where a.eid =:eid",array(':eid'=>$_W['uniacid']));
			if(!empty($total)){ 
				$sql = "select s.*,a.* from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id ".$condition." LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$articles = pdo_fetchall($sql, $params);
				foreach ($articles as $key => $value) {
					$cat = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE id = '{$value['category_id']}'");
					$articles[$key]['cat'] = $cat['name'];
				}
				$pager = pagination($total, $pindex, $psize);
			}	
    		include $this->template('mass_list');
    	} elseif ($operation == 'list') {
    		include $this->template('test_list');
    	}
    }*/
    public function findNum($str='') {
        $str=trim($str);
        if(empty($str)){return '';}
        $result='';
        for($i=0;$i<strlen($str);$i++){
            if(is_numeric($str[$i])){
                $result.=$str[$i];
            }
        }
        return $result;
    }
    public function getdata($url, $type) {
    	global $_W,$_GPC;
    	$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在  
		if ($type == 1) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE1);
			//curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE1);  /////把返回来的cookie信息保存在$cookie_jar文件中
		} elseif ($type == 2) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE2);
			//curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE2);  /////把返回来的cookie信息保存在$cookie_jar文件中
		} else {
			curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE);
			//curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE);  /////把返回来的cookie信息保存在$cookie_jar文件中
		}
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		$res = curl_exec($ch);//执行
		curl_close($ch);////关闭
		return $res;
    }
    public function dowebPostcode() {
    	global $_W,$_GPC;
    	$type = !empty($_GPC['type']) ? $_GPC['type'] : 0;
		if (checksubmit('submit')) {
			if (!empty($_GPC['code'])) {
				if ($_GPC['type'] == 1) {
					$login="http://weixin.sogou.com/antispider/thank.php ";
					$data['c']=$_GPC['code'];
					$data['r']='%2Fweixin%3Ftype%3D2%26query%3D%E5%8C%97%E4%BA%AC%26ie%3Dutf8%26s_from%3Dinput%26_sug_%3Dn%26_sug_type_%3D1%26w%3D01015002%26oq%3D%26ri%3D31%26sourceid%3Dsugg%26sut%3D0%26sst0%3D1492483444088%26lkt%3D0%2C0%2C0%26p%3D40040108';
					$data['v']=5;
					$ch=curl_init($login); /////初始化一个CURL对象
					curl_setopt($ch,CURLOPT_HEADER,0);
					curl_setopt ( $ch, CURLOPT_POST, 1 );
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); ///设置不输出在浏览器上
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在  
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
					curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE1);
					curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE1);  /////把返回来的cookie信息保存在$cookie_jar文件中
					$res = curl_exec($ch);///执行
					curl_close($ch);////关闭
					$res = json_decode($res,1);
					if ($res['code'] == 0) {
						header("location: ".$this->createWebUrl('collect'));
					}
				} elseif ($_GPC['type'] == 2) {
					$login="https://mp.weixin.qq.com/mp/verifycode";
					$data['cert']='1492364451959.222';
					$data['input']=$_GPC['code'];
					$ch=curl_init($login); /////初始化一个CURL对象
					curl_setopt($ch,CURLOPT_HEADER,0);
					curl_setopt ( $ch, CURLOPT_POST, 1 );
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); ///设置不输出在浏览器上
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在  
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
					curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE2);
					curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE2);  /////把返回来的cookie信息保存在$cookie_jar文件中
					$res = curl_exec($ch);///执行
					curl_close($ch);////关闭
					$res = json_decode($res,1);
					if ($res['errmsg'] == '') {
						header("location: ".$this->createWebUrl('collect'));
					}
				} else {
					$login="http://www.weizhishu.com/user/loginapi?mobile=15666960994&password=zmyx6817&autologin=true&code=".$_GPC['code'];
					$ch=curl_init($login); /////初始化一个CURL对象
					curl_setopt($ch,CURLOPT_HEADER,0);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); ///设置不输出在浏览器上
					curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE);
					curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE);  /////把返回来的cookie信息保存在$cookie_jar文件中
					$res = curl_exec($ch);///执行
					curl_close($ch);////关闭
					$res = json_decode($res,1);
					if ($res['errno'] == 0) {
						header("location: ".$this->createWebUrl('collect'));
					}
				}
			}			
		}
		include $this->template('getcode');
    }
    public function doWebGetcode() {
    	global $_W,$_GPC;
    	header ("Content-type: image/png; charset=gbk");
		if ($_GPC['type'] == 1) {
			$login="http://weixin.sogou.com/antispider/util/seccode.php?tc=1492487058438";
		} elseif ($_GPC['type'] == 2) {
			$login="https://mp.weixin.qq.com/mp/verifycode?cert=1492364451959.222";
		} else {
			$login="http://www.weizhishu.com/imgcode.php";
		}
		$ch=curl_init($login); /////初始化一个CURL对象
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); ///设置不输出在浏览器上
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在  
		if ($_GPC['type'] == 1) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE1);
			curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE1);  /////把返回来的cookie信息保存在$cookie_jar文件中
		} elseif ($_GPC['type'] == 2) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE2);
			curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE2);  /////把返回来的cookie信息保存在$cookie_jar文件中
		} else {
			curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE);
			curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE);  /////把返回来的cookie信息保存在$cookie_jar文件中
		}
		$img = curl_exec($ch);///执行
		curl_close($ch);////关闭
		echo $img;	
    }
    public function doWebGetcookie() {
    	global $_W,$_GPC;
					$login="http://weixin.sogou.com/antispider/thank.php ";
					$data['c']=$_GPC['code'];
					$data['r']='/weixin?type=2&query=旅行&ie=utf8&s_from=input&_sug_=n&_sug_type_=1&w=01015002&oq=&ri=32&sourceid=sugg&sut=0&sst0=1492480734006&lkt=0,0,0&p=40040108';
					$data['v']=5;
					$ch=curl_init($login); /////初始化一个CURL对象
					curl_setopt($ch,CURLOPT_HEADER,0);
					curl_setopt ( $ch, CURLOPT_POST, 1 );
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); ///设置不输出在浏览器上
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
					curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE1);
					curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE1);  /////把返回来的cookie信息保存在$cookie_jar文件中
					$res = curl_exec($ch);///执行
					curl_close($ch);////关闭
					echo $res;
					
    }
    public function doWebCollect() {
    	global $_W,$_GPC;
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'kw';
		if ($operation == 'kw') {
    		if (empty($_GPC['q'])) {
    			$_GPC['q'] = '旅行';
    		}
			$q = !empty($_GPC['q']) ? $_GPC['q'] : '济南';
			$pindex = max(1, intval($_GPC['page']));
        	$psize = 10;
        	$total = 100;
        	$pager = pagination($total, $pindex, $psize);
			$tsn = !empty($_GPC['tsn']) ? $_GPC['tsn'] : '0';
        	if ($settings['cjy'] == 0) {/*
        		$url = 'http://weixin.sogou.com/weixin?usip=null&query='.$q.'&from=tool&ft=&tsn='.$tsn.'&et=&interation=null&type=2&wxid=&page='.$pindex.'&ie=utf8';*/
        		$url = 'http://weixin.sogou.com/weixin?oq=&query='.$q.'&_sug_type_=1&sut=0&lkt=0,0,0&s_from=input&ri=35&_sug_=n&type=2&sst0=1494493748529&page='.$pindex.'&ie=utf8&p=40040108&dp=1&w=01015002&dr=1';
        		$data = $this->getdata($url,1);
	    		$reg = array('cover' => array('img', 'src'), 'title' => array('h3 a', 'text'), 'content_url' => array('h3 a', 'href'), 'digest' => array('.txt-info', 'text'), 'name' => array('.s-p a', 'text'), 'pubtime' => array('.s-p', 't'));
				$rang = ".news-list li";
				$art_info = new QueryList($data,$reg,$rang,1);
				$articles_info = $art_info->jsonArr;
				if (empty($articles_info[0]['title'])) {
					header("location: ".$this->createWebUrl('postcode', array('type' => 1)));
				}
        	} else {
				$url = 'http://www.weizhishu.com/search/getArtwx?q='.$q.'&p='.$pindex;
				$data = $this->getdata($url);
				$articles_info = json_decode($data,1);
				$articles_info = $articles_info['data'];
				if (empty($articles_info[0]['title'])) {
					header("location: ".$this->createWebUrl('postcode'));
				}
        	}
			$categorys = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1  ORDER BY id DESC");
			
    		include $this->template('collect');
    	} elseif ($operation == 'hot') {
    		$period = !empty($_GPC['period']) ? intval($_GPC['period']) : 7;
    		$gid = !empty($_GPC['gid']) ? intval($_GPC['gid']) : 0;
    		$pc = !empty($_GPC['pc']) ? $_GPC['pc'] : 'pc_0';
			$pindex = max(1, intval($_GPC['page']));
        	$psize = 20;
        	$total = 1000;
        	$pager = pagination($total, $pindex, $psize);
        	if ($settings['cjy'] == 0) {
        		if ($pindex == 1) {
        			$ht = $pc;
        		} else {
        			$ht = $pindex - 1;
        		}
        		$url = 'http://weixin.sogou.com/pcindex/pc/'.$pc.'/'.$ht.'.html';
        		$data = $this->getdata($url,1);
	    		$reg = array('cover' => array('img', 'src'), 'title' => array('h3 a', 'text'), 'content_url' => array('h3 a', 'href'), 'digest' => array('.txt-info', 'text'), 'name' => array('.s-p a', 'text'), 'pubtime' => array('.s-p', 't'));
	    		if ($pindex == 1) {
	    			$rang = ".news-list li";
	    		} else {
	    			$rang = "li";
	    		}
				$art_info = new QueryList($data,$reg,$rang,1);
				$articles_info = $art_info->jsonArr;
				if (empty($articles_info[0]['title'])) {
					header("location: ".$this->createWebUrl('postcode', array('type' => 1)));
				}
        	} else {
	    		$url = 'http://www.weizhishu.com/hotlist/marticle?period='.$period.'&gid='.$gid.'&p='.$pindex;
	    		$data = $this->getdata($url);
	    		$reg = array('cover' => array('.cover img', 'src'), 'title' => array('.info h2 a', 'text'), 'content_url' => array('.info h2 a', 'href'), 'digest' => array('.info .dsc p', 'text'), 'name' => array('.info .dsc .name', 'text'), 'pubtime' => array('.info .dsc .time', 'text'), 'like_num' => array('.info .dsc .praise', 'text'), 'read_num' => array('.info .dsc .view', 'text'), );
				$rang = ".rl-list li";
				$art_info = new QueryList($data,$reg,$rang,1);
				$articles_info = $art_info->jsonArr;
				if (empty($articles_info[0]['title'])) {
					header("location: ".$this->createWebUrl('postcode'));
				}
        	}
			$categorys = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1  ORDER BY id DESC");
    		include $this->template('collect');
		} elseif ($operation == 'hao') {
    		$gid = !empty($_GPC['gid']) ? intval($_GPC['gid']) : 0;
    		if (empty($_GPC['query'])) {
    			$_GPC['query'] = '旅行';
    		}
    		$query = !empty($_GPC['query']) ? $_GPC['query'] : '旅行';
			$pindex = max(1, intval($_GPC['page']));
        	$psize = 20;
        	$total = 1000;
        	$pager = pagination($total, $pindex, $psize);
        	if ($settings['cjy'] == 0) {
        		$url = 'http://weixin.sogou.com/weixin?query='.$query.'&_sug_type_=&s_from=input&_sug_=n&type=1&page='.$pindex.'&ie=utf8';
        		$data = $this->getdata($url,1);
	    		$reg = array('cover' => array('img', 'src'), 'title' => array('.tit', 'text'), 'content_url' => array('.tit a', 'href'), 'digest' => array('dl dd', 'text'), 'name' => array('.info', 'text'));
				$rang = ".news-list2 li";
				$hao_info = new QueryList($data,$reg,$rang,1);
				$haos_info = $hao_info->jsonArr;
				if (empty($haos_info[0]['title'])) {
					header("location: ".$this->createWebUrl('postcode', array('type' => 1)));
				}
        	} else {
	    		$url = 'http://www.weizhishu.com/hotlist/account?gid='.$gid.'&p='.$pindex;
	    		$data = $this->getdata($url);
	    		$reg = array('order' => array('em', 'text'), 'img' => array('.avatar img', 'src'), 'url' => array('.avatar a', 'href'), 'name' => array('.name a', 'text'), 'id' => array('.name a span', 'text'), 'view' => array('.c2', 'text'), 'like_num' => array('.c4', 'text'), 'like_ratio' => array('.c5', 'text'),);
				$rang = ".rl-list ul li";
				$hao_info = new QueryList($data,$reg,$rang,1);
				$haos_info = $hao_info->jsonArr;
        	}
    		include $this->template('collect');
		} elseif ($operation == 'caiji') {
			if ($_W['isajax']) {
				if (!empty($_GPC['content_url'])) {
					$url = $_GPC['content_url'];
					$html = $this->getdata($url,2);
					$html = str_replace("<!--headTrap<body></body><head></head><html></html>-->", "", $html);
					$html = str_replace('height="417"', 'height="240"', $html);
					$html = str_replace('&amp;width=500&amp;height=375', '', $html);
					$html = str_replace('preview.html?vid', 'player.html?vid', $html);
					$content_base = str_replace("data-src", "src", $html);
					$reg = array('title' => array('#activity-name', 'text'), 'content' => array('#js_content', 'html'));
					$rang = "#page-content";
					$art_info = new QueryList($content_base,$reg,$rang,1);
					$art_arr = $art_info->jsonArr;
					$art_title = $art_arr[0]['title'];
					$art_content = $art_arr[0]['content'];
					$reg = array('src' => array('', 'src'));
					$rang = "img";
					$content_src = new QueryList($art_content,$reg,$rang,1);
					$content_arr = $content_src->jsonArr;
					foreach ($content_arr as $key => $value) {
						$art_content = str_replace('src="'.$value['src'], 'src="'.tomedia($this->upimages($value['src'])), $art_content);
					}
					$reg = array('src' => array('', 'style'));
					$rang = "*[style*='background-image: url']";
					$content_src = new QueryList($art_content,$reg,$rang,1);
					$content_arr = $content_src->jsonArr;
					foreach ($content_arr as $key => $value) {
						$start = strpos($value['src'],"http");
						$stop = strpos($value['src'],'");');
						$len = $stop - $start;
						$bc_url = substr($value['src'], $start, $len);
						$art_content = str_replace('url("'.$bc_url.'"', 'url('.tomedia($this->upimages($bc_url)), $art_content);
					}
					if (empty($art_title) || empty($art_content)) {
						die(json_encode(array('code' => 0, 'message' => '参数请求错误')));
					}
					if (!empty($_GPC['sp'])) {
						$art_desc = substr($html, strpos($html,"msg_desc =")+12, strpos($html,"msg_cdn_url =")-strpos($html,"msg_desc =")-24);
						$art_thumb = substr($html, strpos($html,"msg_cdn_url =")+15, strpos($html,"msg_link =")-strpos($html,"msg_cdn_url =")-27);
						$art_thumb = tomedia($this->upimages($art_thumb));
						die(json_encode(array('code' => 1, 'data' => $art_content, 'art_title' => $art_title, 'art_desc' => $art_desc, 'art_thumb' => $art_thumb)));
					}
					$art_data['title'] = !empty($_GPC['title']) ? $_GPC['title'] : $art_title;
					$art_data['desc'] = !empty($_GPC['desc']) ? $_GPC['desc'] : '';
					$art_data['thumb'] = !empty($_GPC['thumb']) ? tomedia($this->upimages($_GPC['thumb'])) : '';
					$art_data['content'] = $art_content;
					$art_data['eid'] = $_W['uniacid'];
					$art_data['createtime'] = !empty($_GPC['z_time']) ? strtotime($_GPC['z_time']) : time();
					$art_data['is_save'] = 1;
					$art_data['is_delete'] = 0;
					$art_data['author'] = !empty($_GPC['author']) ? $_GPC['author'] : '';
					$art_data['zannum'] = 0;
					$art_data['yuenum'] = 0;
					$art_data['keyword'] = $_GPC['keyword'];
					$art_data['fx_title'] = !empty($_GPC['fx_title']) ? $_GPC['fx_title'] : $art_title;
					$art_data['fx_desc'] = !empty($_GPC['fx_desc']) ? $_GPC['fx_desc'] : '';
					$art_data['fx_img'] = !empty($_GPC['fx_img']) ? tomedia($this->upimages($_GPC['fx_img'])) : '';
					$art_data['fx_url'] = !empty($_GPC['fx_url']) ? $_GPC['fx_url'] : '';
					$art_data['y_url'] = $_GPC['y_url'];
					$art_data['category_id'] = !empty($_GPC['category_id']) ? $_GPC['category_id'] : 1;
					pdo_insert('tech_superarticle_article', $art_data);
					$id = pdo_insertid();
					$newid = $id;
					$categorys = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE id = '{$art_data['category_id']}'");
					$we7_cat = pdo_fetch("SELECT * FROM ".tablename('site_category')." WHERE id = '{$categorys['we7_cat']}'");
					if ($we7_cat['parentid'] == 0) {
						$parentid = $we7_cat['id'];
						$childid = 0;
					} else {
						$parentid = $we7_cat['parentid'];
						$childid = $we7_cat['id'];
					}
					$source = $_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id' => $newid)), 2);
					$we7_data['category']['parentid'] = $parentid;
					$we7_data['category']['childid'] = $childid;
					$we7_data['source'] = $source;
					$we7_data['linkurl'] = $source;
					$we7_data['keyword'] = $art_data['keyword'];
					$we7_data['title'] = $art_data['title'];
					$we7_data['description'] = $art_data['fx_desc'];
					$we7_data['content'] = $art_data['content'];
					$we7_data['author'] = $art_data['author'];
					$we7_data['thumb'] = $art_data['thumb'];
					$we7_data['aid'] = $art_data['aid'];
					$aid = $this->tongbuwe7($we7_data);
					pdo_update('tech_superarticle_article', array('aid' => $aid), array('id' => $newid));
					$setting_data['ar_id']=$id;
					$setting_data['eid']=$_W['uniacid'];
					$setting_data['original']=1;
					$setting_data['is_yueduliang']=1;
					$setting_data['is_dianzan']=1;
					$setting_data['comment']=1;
					$setting_data['is_comment']=0;
					if (isset($_GPC['yuanwen'])) {
						$setting_data['yuanwen']=$_GPC['yuanwen'];
					}
					if (isset($_GPC['yuanwen_link'])) {
						$setting_data['yuanwen_link']=$_GPC['yuanwen_link'];
					}
					if (isset($_GPC['dashang'])) {
						$setting_data['dashang']=$_GPC['dashang'];
					}
					if (isset($_GPC['gratuity_money'])) {
						$setting_data['gratuity_money']=$_GPC['gratuity_money'];
					}
					if (isset($_GPC['is_own'])) {
						$setting_data['is_own']=$_GPC['is_own'];
					}
					if (isset($_GPC['comment'])) {
						$setting_data['comment']=$_GPC['comment'];
					}
					if (isset($_GPC['is_comment'])) {
						$setting_data['is_comment']=$_GPC['is_comment'];
					}
					if (isset($_GPC['yueduliang'])) {
						$setting_data['yueduliang']=$_GPC['yueduliang'];
					}
					if (isset($_GPC['dianzanliang'])) {
						$setting_data['dianzanliang']=$_GPC['dianzanliang'];
					}
					pdo_insert('tech_superarticle_setting',$setting_data);
					die(json_encode(array('code' => 1, 'message' => '采集成功，已加入文章库！')));
				} else {
					die(json_encode(array('code' => 0, 'message' => '参数请求错误')));
				}
			}
		} elseif ($operation == 'haonei') {
			if (empty($_GPC['wid'])) {
				message('请选择公众号ID', $this->createWebUrl('collect', array('op' => 'hao')), 'error');	
			} else {
				$wid = $_GPC['wid'];
			}
			$time = !empty($_GPC['time']) ? $_GPC['time'] : 2;
        	if ($settings['cjy'] == 0) {
        		$url = $wid;
        		$name = $_GPC['name'];
        		$data = $this->getdata($url,2);
        		if (strpos($data,'{"list":[{') == 0) {
        			header("location: ".$this->createWebUrl('postcode', array('type' => 2)));
        		}
        		$data = substr($data,strpos($data,'{"list":[{'));
        		$data = substr($data,0,strpos($data,'}}]}')+4);
        		$data = json_decode($data,1);
        		$i = 0;
        		foreach ($data['list'] as $key => $value) {
        			if (!empty($value['app_msg_ext_info']['title'])) {
	        			$articles_info[$i]['cover'] = $value['app_msg_ext_info']['cover'];
	        			$articles_info[$i]['title'] = $value['app_msg_ext_info']['title'];
	        			if (strpos($value['app_msg_ext_info']['content_url'],'mp.weixin.qq.com')) {
	        				$articles_info[$i]['content_url'] = $value['app_msg_ext_info']['content_url'];
	        			} else {
	        				$articles_info[$i]['content_url'] = 'https://mp.weixin.qq.com'.$value['app_msg_ext_info']['content_url'];
	        			}
	        			$articles_info[$i]['digest'] = $value['app_msg_ext_info']['digest'];
	        			$articles_info[$i]['name'] = $name;
	        			$articles_info[$i]['pubtime'] = $value['comm_msg_info']['datetime'];
	        			$i += 1;
        			}
        			foreach ($value['app_msg_ext_info']['multi_app_msg_item_list'] as $key1 => $value1) {
	        			$articles_info[$i]['cover'] = $value1['cover'];
	        			$articles_info[$i]['title'] = $value1['title'];
	        			if (strpos($value1['content_url'],'mp.weixin.qq.com')) {
	        				$articles_info[$i]['content_url'] = $value1['content_url'];
	        			} else {
	        				$articles_info[$i]['content_url'] = 'https://mp.weixin.qq.com'.$value1['content_url'];
	        			}
	        			$articles_info[$i]['digest'] = $value1['digest'];
	        			$articles_info[$i]['name'] = $name;
	        			$articles_info[$i]['pubtime'] = $value['comm_msg_info']['datetime'];
	        			$i += 1;
        			}
        		}

        	} else {
				$url = 'http://www.weizhishu.com/account/articleApi?type=1&time='.$time.'&wid='.$wid;
				$data = $this->getdata($url);
				if (empty($data)) {
					message('无法获取该公众号信息', $this->createWebUrl('collect', array('op' => 'hao')), 'error');
				}
				$articles_info = json_decode($data,1);
				$articles_info = $articles_info['data'];
				$hao_name = $articles_info[0]['name'];
        	}
			$categorys = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1  ORDER BY id DESC");
    		include $this->template('collect');
		}
    }
    public function doWebImg() {
		include $this->template('img');
    }
	public function upimages($url) {
		global $_W, $_GPC;
		if ($_W['account']['level'] >= 3) {
			load()->classs('weixin.account');
			$accObj = new WeixinAccount();
			$access_token = $accObj->fetch_available_token();
		} 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		$rejson = json_decode($package, true);
		if (!empty($rejson['errcode'])) {
			return 0;
			exit;
		}
		$fileInfo = array_merge(array('header' => $httpinfo), array('body' => $package));
		switch ($fileInfo['header']['content_type']) {
			case 'image/pjpeg':
			case 'image/jpeg':
				$extend = ".jpg";
			break;
			case 'image/gif':
				$extend = ".gif";
			break;
			case 'image/png':
				$extend = ".png";
			break;
		}
		$filename = date("YmdHis") . $this->createNoncestr(32) . $extend;
		$uniacid = intval($_W['uniacid']);
		$re_dir = "images/{$uniacid}/tech_superarticle/" . date('Y/m/');
		$dir_name = ATTACHMENT_ROOT . $re_dir;
		$filepath = $dir_name . $filename;
		$filecontent = $fileInfo["body"];
		if (!is_dir($dir_name)) {
			$dir = mkdir($dir_name, 0777, true);
		}
		if (false !== $dir) {
			$local_file = fopen($filepath, 'w');
			if (false !== $local_file) {
				if (false !== fwrite($local_file, $filecontent)) {
					fclose($local_file);
					$imagesinfo = $re_dir . $filename;
					if (!empty($_W['setting']['remote']['type'])) {
						load()->func('file');
						$remotestatus = file_remote_upload($imagesinfo, true);
						if (is_error($remotestatus)) {
							message('远程附件上传失败，请检查配置并重新上传');
						} else {
							file_delete($imagesinfo);
							return $imagesinfo;
						}
					} else {
						return $imagesinfo;
					}
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
		  $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
    public function doWebQiniu() {
        global $_W, $_GPC;	
        $remote = $_W['setting']['remote'];	
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		header("Content-type:text/html;charset=utf-8");
		global $QINIU_ACCESS_KEY;
		global $QINIU_SECRET_KEY;

		$QINIU_UP_HOST	= 'http://up.qiniu.com';
		$QINIU_RS_HOST	= 'http://rs.qbox.me';
		$QINIU_RSF_HOST	= 'http://rsf.qbox.me';
		if (!empty($settings['qn_ak'])) {
			//配置$QINIU_ACCESS_KEY和$QINIU_SECRET_KEY 为你自己的key
			$QINIU_ACCESS_KEY	= $settings['qn_ak'];
			$QINIU_SECRET_KEY	= $settings['qn_sk'];

			//配置bucket为你的bucket
			$BUCKET = $settings['qn_bt'];

			//配置你的域名访问地址
			$HOST  = $settings['qn_url'];
		} elseif (!empty($remote['qiniu']['accesskey'])) {
			//配置$QINIU_ACCESS_KEY和$QINIU_SECRET_KEY 为你自己的key
			$QINIU_ACCESS_KEY	= $remote['qiniu']['accesskey'];
			$QINIU_SECRET_KEY	= $remote['qiniu']['secretkey'];

			//配置bucket为你的bucket
			$BUCKET = $remote['qiniu']['bucket'];

			//配置你的域名访问地址
			$HOST  = $remote['qiniu']['url'];
		} else {
			//配置$QINIU_ACCESS_KEY和$QINIU_SECRET_KEY 为你自己的key
			$QINIU_ACCESS_KEY	= '6sT3IqdMzbBZwZXD6ItFw79_eVjSkRIWI5ZCDJKX';
			$QINIU_SECRET_KEY	= 'YwSZd5ezRKhN9VLOuEX3hdS1QU-DObQruz3_q1e8';

			//配置bucket为你的bucket
			$BUCKET = "ueditor";

			//配置你的域名访问地址
			$HOST  = "http://ueditor.9zhulu.com";
		}

		//上传超时时间
		$TIMEOUT = "3600";

		//保存规则
		$SAVETYPE = "date";

		//开启水印
		$USEWATER = false;
		$WATERIMAGEURL = "http://gitwiduu.u.qiniudn.com/ueditor-bg.png"; //七牛上的图片地址
		//水印透明度
		$DISSOLVE = 50;
		//水印位置
		$GRAVITY = "SouthEast";
		//边距横向位置
		$DX  = 10;
		//边距纵向位置
		$DY  = 10;
		
		$accessKey = $QINIU_ACCESS_KEY;
		$secretKey = $QINIU_SECRET_KEY;
		
		$bucket = $BUCKET;

		$host  = $HOST;
		
		$time = time()+$TIMEOUT;
		
		if(empty($_GET["key"])){
			exit('param error');
		}else{
			
			if($USEWATER && empty($_GET['type'])){
				$waterBase = $this->urlsafe_base64_encode($WATERIMAGEURL);
				$returnBody = "{\"url\":\"{$host}/$(key)?watermark/1/image/{$waterBase}/dissolve/{$DISSOLVE}/gravity/{$GRAVITY}/dx/{$DX}/dy/{$DY}\", \"state\": \"SUCCESS\", \"name\": $(fname),\"size\": \"$(fsize)\",\"w\": \"$(imageInfo.width)\",\"h\": \"$(imageInfo.height)\"}";
			}else{
				$returnBody = "{\"url\":\"{$host}/$(key)\", \"state\": \"SUCCESS\", \"name\": $(fname),\"size\": \"$(fsize)\",\"w\": \"$(imageInfo.width)\",\"h\": \"$(imageInfo.height)\"}";
			}

			$data =  array(
					"scope"=>$bucket.":".$_GET['key'],
					"deadline"=>$time,
					"returnBody"=> $returnBody
				);
		}

		$data = json_encode($data);
		$dataSafe = $this->urlsafe_base64_encode($data);
		$sign = hash_hmac('sha1',$dataSafe, $secretKey, true);
		$result = $accessKey . ':' . $this->urlsafe_base64_encode($sign).':'.$dataSafe ;
		echo $result;	
    }
	public function urlsafe_base64_encode($data){
		$find = array('+', '/');
		$replace = array('-', '_');
		return str_replace($find, $replace, base64_encode($data));
	}
    public function doWebMessage() {
        global $_W, $_GPC;
    	message('该功能将在新版本启用！', url('home/welcome/ext', array('m' => 'tech_superarticle')), 'error');		
    }
    public function doWebStart() {
        global $_W, $_GPC;
    	$start = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_article') . " WHERE eid = '{$_W['uniacid']}' AND id = '{$_GPC['id']}'");	
    	die(json_encode(array('code' => 1, 'data' => $start)));
    }
    public function doWebRemote() {
        global $_W, $_GPC;
        $url = 'http://ue.9zhulu.com/index.php?type=2';
        $style_data = $this->get_curl($url);
        $data = json_decode($style_data, 1);
        if ($data['code'] == 1) {
			pdo_delete('tech_superarticle_wxstyle');
			foreach ($data['data'] as $key => $value) {
				$res1 = pdo_insert('tech_superarticle_wxstyle', $value);
			}
			if ($res1) {
				message('恭喜您，已经切换为远程数据！', url('home/welcome/ext', array('m' => 'tech_superarticle')), 'success');
			} else {
				message('抱歉，数据库处理出错！', url('home/welcome/ext', array('m' => 'tech_superarticle')), 'error');
			}
        } else {
        	message('抱歉，数据请求出错！', url('home/welcome/ext', array('m' => 'tech_superarticle')), 'error');
        }
		
    }
    public function doWebLocal() {
        global $_W, $_GPC;
        $url = 'http://ue.9zhulu.com/index.php?type=1';
        $style_data = $this->get_curl($url);
        $data = json_decode($style_data, 1);
        if ($data['code'] == 1) {
			pdo_delete('tech_superarticle_wxstyle');
			foreach ($data['data'] as $key => $value) {
				$res1 = pdo_insert('tech_superarticle_wxstyle', $value);
			}
			if ($res1) {
				message('恭喜您，已经切换为本地数据！请确保已下载模板资源包，并上传至web服务器的“addons/tech_superarticle/”目录下，资源包链接：http://pan.baidu.com/s/1c1HeleK 密码：btnh', '', 'success');
			} else {
				message('抱歉，数据库处理出错！', url('home/welcome/ext', array('m' => 'tech_superarticle')), 'error');
			}
        } else {
        	message('抱歉，数据请求出错！', url('home/welcome/ext', array('m' => 'tech_superarticle')), 'error');
        }
		
    }
	private function get_curl($url) {
		$ch=curl_init($url); /////初始化一个CURL对象
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); ///设置不输出在浏览器上
		$result = curl_exec($ch);///执行
		curl_close($ch);////关闭
		return $result;
	}
    public function doWebStyleajax() {
        global $_W, $_GPC;
        $type = intval($_GPC['type']);
    	if ($_W['isajax']) {
    		if ($_GPC['type'] == 99) {
    			$ajaxdata = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_mystyle') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC ");
    			if ($ajaxdata) {
    				die(json_encode(array('code' => 1, 'data' => $ajaxdata)));
    			} else {
    				die(json_encode(array('code' => 0, 'message' => '参数请求错误')));
    			}
    		}
    		if (isset($_GPC['type'])) {
    			$ajaxdata = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " WHERE type = '{$type}' ORDER BY id DESC ");
    			if ($ajaxdata) {
    				die(json_encode(array('code' => 1, 'data' => $ajaxdata)));
    			} else {
    				die(json_encode(array('code' => 0, 'message' => '参数请求错误')));
    			}
    		} else {
    			die(json_encode(array('code' => 0, 'message' => '参数请求错误')));
    		}
    	}
    }
    public function getcode($value) {
		include IA_ROOT . '/addons/tech_superarticle/phpqrcode/phpqrcode.php';
		$value = $value; //二维码内容 
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = 10;//生成图片大小 
		//生成二维码图片 
		QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2); 
		$logo = '../addons/tech_superarticle/icon.jpg';//准备好的logo图片 
		$QR = 'qrcode.png';//已经生成的原始二维码图 
		  
		if ($logo !== FALSE) { 
		 $QR = imagecreatefromstring(file_get_contents($QR)); 
		 $logo = imagecreatefromstring(file_get_contents($logo)); 
		 $QR_width = imagesx($QR);//二维码图片宽度 
		 $QR_height = imagesy($QR);//二维码图片高度 
		 $logo_width = imagesx($logo);//logo图片宽度 
		 $logo_height = imagesy($logo);//logo图片高度 
		 $logo_qr_width = $QR_width / 5; 
		 $scale = $logo_width/$logo_qr_width; 
		 $logo_qr_height = $logo_height/$scale; 
		 $from_width = ($QR_width - $logo_qr_width) / 2; 
		 //重新组合图片并调整大小 
		 imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
		 $logo_qr_height, $logo_width, $logo_height); 
		} 
		//输出图片 
		imagepng($QR, 'helloweba.png'); 
		return '<img style="width:60%;margin-left:35%;" src="helloweba.png" alt="文章预览" class="img-thumbnail text-center">';

    }
    public function doWebIndexs() {
		global $_W,$_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$categorys = pdo_fetchall("SELECT * FROM ".tablename('tech_superarticle_category')." WHERE uniacid = '{$_W['uniacid']}' ORDER BY c_order DESC");
			$children = array();
			$pindex = max(1, intval($_GPC['page']));
	        $psize = 10;
	        $indexs = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_indexs') . " WHERE uniacid = '{$_W['uniacid']}' LIMIT ".($pindex - 1) * $psize.",{$psize}");

	        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tech_superarticle_indexs'));
	        $pager = pagination($total, $pindex, $psize);
			include $this->template('indexs');
		} elseif ($operation == 'post') {
			$categorys = pdo_fetchall("SELECT * FROM ".tablename('tech_superarticle_category')." WHERE uniacid = '{$_W['uniacid']}' ORDER BY c_order DESC");
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$indexs = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_indexs') . " WHERE id = '$id'");
				$indexs['categorys'] =  unserialize($indexs['categorys']);
			} else {
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('文章列表标题不能为空');
				}
				$data = array(
					'uniacid' => $_W['uniacid'],
					'title' => $_GPC['title'],
					'ad1_title' => $_GPC['ad1_title'],
					'ad1_thumb' => $_GPC['ad1_thumb'],
					'ad1_url' => $_GPC['ad1_url'],
					'ad2_title' => $_GPC['ad2_title'],
					'ad2_thumb' => $_GPC['ad2_thumb'],
					'ad2_url' => $_GPC['ad2_url'],
					'ad3_title' => $_GPC['ad3_title'],
					'ad3_thumb' => $_GPC['ad3_thumb'],
					'ad3_url' => $_GPC['ad3_url'],
					'fx_title' => $_GPC['fx_title'],
					'fx_thumb' => $_GPC['fx_thumb'],
					'fx_desc' => $_GPC['fx_desc'],
					'categorys' => serialize($_GPC['categorys'])
				);
				if (!empty($id)) {
					pdo_update('tech_superarticle_indexs', $data, array('id' => $id));
					message('更新列表成功！', $this->createWebUrl('indexs', array('op' => 'display')), 'success');
				} else {
					pdo_insert('tech_superarticle_indexs', $data);
					$id = pdo_insertid();
					message('添加列表成功！', $this->createWebUrl('indexs', array('op' => 'display')), 'success');
				}
				
			}
			include $this->template('indexs');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$indexs = pdo_fetch("SELECT id FROM " . tablename('tech_superarticle_indexs') . " WHERE id = '$id'");
			if (empty($indexs)) {
				message('抱歉，列表不存在或是已经被删除！', $this->createWebUrl('indexs', array('op' => 'display')), 'error');
			}
			pdo_delete('tech_superarticle_indexs', array('id' => $id));
			message('列表删除成功！', $this->createWebUrl('indexs', array('op' => 'display')), 'success');
		}
    }
    public function doWebCategory() {
		global $_W,$_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['c_order'])) {
				foreach ($_GPC['c_order'] as $id => $c_order) {
					pdo_update('tech_superarticle_category', array('c_order' => $c_order), array('id' => $id, 'uniacid' => $_W['uniacid']));
				}
				message('恭喜您，更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			$children = array();
			$pindex = max(1, intval($_GPC['page']));
	        $psize = 10;
	        $category = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY c_order DESC, id DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
	        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}'");
	        $pager = pagination($total, $pindex, $psize);
			include $this->template('category');
		} elseif ($operation == 'post') {
			$we7_cats = pdo_fetchall("SELECT * FROM ".tablename('site_category')." WHERE uniacid = '{$_W['uniacid']}' ORDER BY parentid, displayorder DESC, id");
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE id = '$id'");
			} else {
				$category['enabled'] = 1;
				$category['qd_enabled'] = 0;
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['name'])) {
					message('分类名称不能为空');
				}
				$data = array(
					'uniacid' => $_W['uniacid'],
					'enabled' => $_GPC['enabled'],
					'qd_enabled' => $_GPC['qd_enabled'],
					'name' => $_GPC['name'],
					'we7_cat' => $_GPC['we7_cat']
				);
				if (!empty($id)) {
					pdo_update('tech_superarticle_category', $data, array('id' => $id));
					message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
				} else {
					pdo_insert('tech_superarticle_category', $data);
					$id = pdo_insertid();
					message('添加分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
				}
				
			}
			include $this->template('category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id FROM " . tablename('tech_superarticle_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
			pdo_delete('tech_superarticle_category', array('id' => $id));
			message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
		}
    }
	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		
		include $this->template('index');
	}
	public function doMobileUeditor() {
		//这个操作被定义用来呈现 功能封面
		
		include $this->template('ueditor');
	}
	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=='display'){
			
			if (!empty($_GPC['a_order'])) {
				foreach ($_GPC['a_order'] as $id => $a_order) {
					pdo_update('tech_superarticle_article', array('a_order' => $a_order), array('id' => $id, 'eid' => $_W['uniacid']));
				}
				message('恭喜您，更新成功！', $this->createWebUrl('list', array('op' => 'display')), 'success');
			}
			$categorys = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1 ORDER BY id DESC");
			if (!$categorys) {
				message('抱歉，请先创建文章分类！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
			$condition = ' where a.eid = :eid and a.is_delete = :is_delete and a.is_save = 1';
			$params = array(':eid'=>$_W['uniacid'],':is_delete'=>0);
            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $starttime = empty($_GPC['time']['start']) ? strtotime('-1 year') : strtotime($_GPC['time']['start']);
            $endtime = empty($_GPC['time']['end']) ? time() : strtotime($_GPC['time']['end'])+86400;
            $condition.=' and a.createtime > :starttime and a.createtime < :endtime';
            $params[':starttime']=$starttime;
            $params[':endtime']=$endtime;
            //$condition .=" and a.createtime > '{$starttime}' and a.createtime < '{$endtime}'";
            if(!empty($_GPC['ar_title'])){
                $ar_title = $_GPC['ar_title'];
                $condition .= " and a.title like '%{$_GPC['ar_title']}%' or a.author like '%{$_GPC['ar_title']}%'";
            }
            if(!empty($_GPC['category_id'])){
                $category_id = $_GPC['category_id'];
                $condition .= " and a.category_id = '{$category_id}'";
            }
            $condition.=' ORDER BY a_order DESC, createtime DESC';
            $sql = 'select count(a.id) from ' . tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id". $condition;
			
			$total = pdo_fetchcolumn($sql, $params);
            //$count = pdo_fetchcolumn("select count(a.*) from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id where a.eid =:eid",array(':eid'=>$_W['uniacid']));
			if(!empty($total)){ 
				$sql = "select s.*,a.* from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id ".$condition." LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$articles = pdo_fetchall($sql, $params);
				foreach ($articles as $key => $value) {
					$dsl = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tech_superarticle_dashang') . " WHERE ar_id = '{$value['id']}' AND status = 1");
					$pll = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tech_superarticle_comment') . " WHERE ar_id = '{$value['id']}'");
					$cat = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE id = '{$value['category_id']}'");
					$fenxiang = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_member_article') . " WHERE ar_id = '{$value['id']}'");
					$fx_num = 0;
					foreach ($fenxiang as $key1 => $value1) {
						$fx_num += $value1['fenxiang_num'];
					}
					$articles[$key]['dsl'] = $dsl;
					$articles[$key]['pll'] = $pll;
					$articles[$key]['cat'] = $cat['name'];
					$articles[$key]['fx_num'] = $fx_num;
				}
				$pager = pagination($total, $pindex, $psize);
			}	

		}elseif($operation=='detail'){
			$id = intval($_GPC['id']);
			if ($_W['isajax']) {
				if (!empty($id)) {
					if (isset($_GPC['title'])) {
						$arl_data['title'] = $_GPC['title'];
					}
					if (isset($_GPC['keyword'])) {
						$arl_data['keyword'] = $_GPC['keyword'];
					}
					if (isset($_GPC['author'])) {
						$arl_data['author'] = $_GPC['author'];
					}
					if (isset($_GPC['content'])) {
						$_GPC['content'] = str_replace('&lt;video', '&lt;iframe', $_GPC['content']);
						$_GPC['content'] = str_replace('video&gt;', 'iframe&gt;', $_GPC['content']);
						$arl_data['content'] = htmlspecialchars_decode($_GPC['content']);
					}
					if (isset($_GPC['thumb'])) {
						$arl_data['thumb'] = $_GPC['thumb'];
					}
					if (isset($_GPC['z_time'])) {
						$arl_data['createtime'] = strtotime($_GPC['z_time']);
					}
					if (isset($_GPC['desc'])) {
						$arl_data['desc'] = $_GPC['desc'];
					}
					if (isset($_GPC['fx_title'])) {
						$arl_data['fx_title'] = $_GPC['fx_title'];
					}
					if (isset($_GPC['fx_desc'])) {
						$arl_data['fx_desc'] = $_GPC['fx_desc'];
					}
					if (isset($_GPC['fx_img'])) {
						$arl_data['fx_img'] = $_GPC['fx_img'];
					}
					if (isset($_GPC['fx_url'])) {
						$arl_data['fx_url'] = $_GPC['fx_url'];
					}
					if (isset($_GPC['y_url'])) {
						$arl_data['y_url'] = $_GPC['y_url'];
					}
					if (isset($_GPC['category_id'])) {
						$arl_data['category_id'] = $_GPC['category_id'];
					}
					if (isset($_GPC['is_save'])) {
						$arl_data['is_save'] = $_GPC['is_save'];
					}
					if (isset($_GPC['yuanwen'])) {
						$setting_data['yuanwen']=$_GPC['yuanwen'];
					}
					if (isset($_GPC['yuanwen_link'])) {
						$setting_data['yuanwen_link']=$_GPC['yuanwen_link'];
					}
					if (isset($_GPC['dashang'])) {
						$setting_data['dashang']=$_GPC['dashang'];
					}
					if (isset($_GPC['gratuity_money'])) {
						$setting_data['gratuity_money']=$_GPC['gratuity_money'];
					}
					if (isset($_GPC['is_own'])) {
						$setting_data['is_own']=$_GPC['is_own'];
					}
					if (isset($_GPC['comment'])) {
						$setting_data['comment']=$_GPC['comment'];
					}
					if (isset($_GPC['is_comment'])) {
						$setting_data['is_comment']=$_GPC['is_comment'];
					}
					if (isset($_GPC['yueduliang'])) {
						$setting_data['yueduliang']=$_GPC['yueduliang'];
					}
					if (isset($_GPC['dianzanliang'])) {
						$setting_data['dianzanliang']=$_GPC['dianzanliang'];
					}
					if (isset($_GPC['mystyle_title'])) {
						$mystyle_data['title'] = $_GPC['mystyle_title'];
						$mystyle_data['code'] = htmlspecialchars_decode($_GPC['mystyle_code']);
						$mystyle_data['uniacid'] = $_W['uniacid'];
						if ($_GPC['mystyle_id'] == 0) {
							pdo_insert('tech_superarticle_mystyle',$mystyle_data);
						} else {
							pdo_update('tech_superarticle_mystyle',$mystyle_data,array('id' => $_GPC['mystyle_id']));
						}
						die(json_encode(array('code' => 1, 'message' => '样式保存成功')));
					}
					$arl_data['id'] = $id;
					$setting_data['ar_id'] = $id;
					pdo_update('tech_superarticle_article',$arl_data,array('id' => $id));
					$newid = $id;
					$art_data = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_article') . " WHERE id = '{$newid}'");
					$categorys = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE id = '{$art_data['category_id']}'");
					$we7_cat = pdo_fetch("SELECT * FROM ".tablename('site_category')." WHERE id = '{$categorys['we7_cat']}'");
					if ($we7_cat['parentid'] == 0) {
						$parentid = $we7_cat['id'];
						$childid = 0;
					} else {
						$parentid = $we7_cat['parentid'];
						$childid = $we7_cat['id'];
					}
					$source = $_W['siteroot'].substr($this->createMobileUrl('detail',array('id' => $newid)), 2);
					$we7_data['category']['parentid'] = $parentid;
					$we7_data['category']['childid'] = $childid;
					$we7_data['source'] = $source;
					$we7_data['linkurl'] = $source;
					$we7_data['keyword'] = $art_data['keyword'];
					$we7_data['title'] = $art_data['title'];
					$we7_data['description'] = $art_data['fx_desc'];
					$we7_data['content'] = $art_data['content'];
					$we7_data['author'] = $art_data['author'];
					$we7_data['thumb'] = $art_data['thumb'];
					$we7_data['aid'] = $art_data['aid'];
					$aid = $this->tongbuwe7($we7_data);
					$arl_data['aid'] = $aid;
					pdo_update('tech_superarticle_article',$arl_data,array('id' => $id));
					pdo_update('tech_superarticle_setting',$setting_data,array('ar_id' => $id));
					die(json_encode(array('code' => 1, 'message' => '保存成功')));
				} else {
					die(json_encode(array('code' => 0, 'message' => 'ID不存在')));
				}
			}
			Header("location: {$this->createWebUrl('ueditor',array('id' => $id))}");
		}elseif($operation=='delete'){
			$id = intval($_GPC['id']);
			if(empty($id)){
				message('您要删除文章不存在!',$this->createWebUrl('list'),'info');
			}
			if(pdo_update('tech_superarticle_article',array('is_delete'=>1),array('id'=>$id,'eid'=>$_W['uniacid']))){
				pdo_delete('tech_superarticle_setting',array('eid'=>$_W['uniacid'],'ar_id'=>$id));
				message('删除文章成功!',$this->createWebUrl('list'),'success');
			}
		}elseif($operation=='dashang_detail'){
			$id = intval($_GPC['id']);
			if (empty($id)) {
				message('抱歉，请点击文章的打赏数进入！', $this->createWebUrl('list', array('op' => 'display')), 'error');
			}
			$pindex = max(1, intval($_GPC['page']));
	        $psize = 10;
	        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tech_superarticle_dashang'). " WHERE ar_id = '{$id}' AND status = 1");
	        $pager = pagination($total, $pindex, $psize);
			$title = pdo_fetch("select title,author from ".tablename('tech_superarticle_article')." where id =:id and eid=:eid",array(':id'=>$id,':eid'=>$_W['uniacid']));
			$records = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_dashang') . " WHERE ar_id = '{$id}' AND status = 1 ORDER BY createtime DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
			foreach ($records as $key => $value) {
				$wxinfo = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_member') . " WHERE openid = '{$value['openid']}'");
				$records[$key]['nickname'] = $wxinfo['nickname'];
				$records[$key]['heading'] = $wxinfo['heading'];
			}
		}elseif ($operation=='dashang_add') {
			$ar_info['createtime'] = date('Y-m-d H:i:s', time()); 
	        $title = pdo_fetch("select title,author from ".tablename('tech_superarticle_article')." where id =:id and eid=:eid",array(':id'=>$_GPC['id'],':eid'=>$_W['uniacid']));
			if (checksubmit('submit')) {
				$o_id = $this->getRandom(16);
				$o_data = array(
					'eid' => $_W['uniacid'],
					'nickname' => $_GPC['nickname'].'（虚拟）',
					'openid' => $o_id,
					'heading' => tomedia($_GPC['heading']),
					'createtime' => time(),
					'ip' => '192.168.1.101',
					'is_dashang' => 0,
					'is_pinglun' => 0,
					'ar_id' => 0,
				);
				$data = array(
					'eid' => $_W['uniacid'],
					'openid' => $o_id,
					'ar_id' => $_GPC['id'],
					'createtime' => strtotime($_GPC['createtime']),
					'fee' => $_GPC['fee'],
					'status' => 1,
					'status' => 1
				);
				pdo_insert('tech_superarticle_member', $o_data);
				pdo_insert('tech_superarticle_dashang', $data);
				message('添加虚拟赞赏成功！', $this->createWebUrl('list', array('op' => 'dashang_detail', 'id' => $_GPC['id'])), 'success');
			}
			include $this->template('xuni_ds');
			die();
		}
		load()->func('tpl');
		include $this->template('list');
	}
	public function doWebComment(){
		global $_W,$_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=='display'){
			if (empty($_GPC['ar_id'])) {
				message('抱歉，请点击文章评论数进入！', $this->createWebUrl('list', array('op' => 'display')), 'error');
			}
			$pindex = max(1, intval($_GPC['page']));
	        $psize = 10;
	        $title = pdo_fetch("select title,author from ".tablename('tech_superarticle_article')." where id =:id and eid=:eid",array(':id'=>$_GPC['ar_id'],':eid'=>$_W['uniacid']));
	        $condition =" c.eid = {$_W['uniacid']} and c.p_id = 0 ";
            $condition .= " and a.id = '{$_GPC['ar_id']}'";
	        $total = pdo_fetchcolumn("select count(c.id) from ".tablename('tech_superarticle_member')." as m left join ".tablename('tech_superarticle_comment')." as c on m.openid = c.openid left join ".tablename('tech_superarticle_article')." as a on c.ar_id = a.id where ".$condition);
			/*$comments = pdo_fetchall("select m.nickname,m.heading,a.title,a.author,c.comment,c.createtime,c.id,c.is_jing from ".tablename('tech_superarticle_member')." as m left join ".tablename('tech_superarticle_comment')." as c on m.openid = c.openid left join ".tablename('tech_superarticle_article')." as a on c.ar_id = a.id where ".$condition." limit ".($pindex - 1) * $psize . ',' . $psize);*/
			$comments = pdo_fetchall("select * from ".tablename('tech_superarticle_comment')." where ar_id = ".$_GPC['ar_id']);
			foreach ($comments as $key => $value) {
				$ar_data = pdo_fetch("select * from ".tablename('tech_superarticle_article')." where id = ".$_GPC['ar_id']);
				$fan_data = pdo_fetch("select * from ".tablename('tech_superarticle_member')." where openid = :openid", array(':openid' => $value['openid']));
				$comments[$key]['nickname'] = $fan_data['nickname'];
				$comments[$key]['heading'] = $fan_data['heading'];
				$comments[$key]['title'] = $ar_data['title'];
				$comments[$key]['author'] = $ar_data['author'];
			}
			//拼装数组，插入作者回复
			foreach($comments as &$vo){
				$zuozhe_comment = pdo_fetch("select id,p_id,comment,createtime from ".tablename('tech_superarticle_comment')." where p_id = ".$vo['id']);
				$vo['zuozhe_comment']=$zuozhe_comment['comment'];
			}
			unset($vo);
			$pager = pagination($total, $pindex, $psize);
			
		}elseif($operation =='huifu'){
			//作者回复的评论id
			$id = intval($_GPC['id']);
			//$ar_id = pdo_fetchcolumn("select ar_id from ".tablename('tech_superarticle_comment')." where id=".$id);
			$data = array(
				'eid'=>$_W['uniacid'],
				//'ar_id'=>$ar_id,
				'createtime'=>time(),
				//'is_author'=>1,//让评论被作者回复时为1
				'is_jing'=>3,
				'p_id'=>$id,
				'comment'=>htmlspecialchars_decode($_GPC['content']),
				);
			//判断作者评论是否存在
			if($p_id = pdo_fetchcolumn("select id from ".tablename('tech_superarticle_comment')." where eid=".$_W['uniacid']." and p_id = ".$id)){
				pdo_update("tech_superarticle_comment",$data,array('id'=>$p_id));
			}else{
				pdo_insert('tech_superarticle_comment',$data);
				pdo_update('tech_superarticle_comment',array('is_author'=>1),array('id'=>$id));
			}
			die(json_encode(array('info'=>100,'con'=>'成功')));
		}elseif($operation='shezhi'){
			$id = intval($_GPC['id']);
			$is_jing = intval($_GPC['is_jing']) == 1 ? 0 : 1;
			pdo_update("tech_superarticle_comment",array('is_jing'=>$is_jing),array('id'=>$id));
			die(json_encode(array('info'=>100,'con'=>$id)));
		}elseif($operation='detail'){
	        $title = pdo_fetch("select title,author from ".tablename('tech_superarticle_article')." where id =:id and eid=:eid",array(':id'=>$_GPC['ar_id'],':eid'=>$_W['uniacid']));
			if (checksubmit('submit')) {
				$o_id = $this->getRandom(16);
				$o_data = array(
					'eid' => $_W['uniacid'],
					'nickname' => $_GPC['nickname'],
					'openid' => $o_id,
					'heading' => tomedia($_GPC['heading']),
					'createtime' => time(),
					'ip' => '192.168.1.101',
					'is_dashang' => 0,
					'is_pinglun' => 0,
					'ar_id' => 0,
				);
				$data = array(
					'eid' => $_W['uniacid'],
					'openid' => $o_id,
					'ar_id' => $_GPC['ar_id'],
					'comment' => $_GPC['comment'],
					'createtime' => strtotime($_GPC['createtime']),
					'is_jing' => intval($_GPC['is_jing']),
					'is_author' => 0,
					'p_id' => 0,
					'pingzan_num' => intval($_GPC['pingzan_num'])
				);
				pdo_insert('tech_superarticle_member', $o_data);
				pdo_insert('tech_superarticle_comment', $data);
				message('添加虚拟评论成功！', $this->createWebUrl('comment', array('op' => 'display', 'ar_id' => $_GPC['ar_id'])), 'success');
			}
		}
		include $this->template('comment');
	}
	public function doWebxuni(){
		global $_W,$_GPC;
		$ar_id = $_GPC['ar_id'];
		$ar_info['createtime'] = date('Y-m-d H:i:s', time()); 
        $title = pdo_fetch("select title,author from ".tablename('tech_superarticle_article')." where id =:id and eid=:eid",array(':id'=>$_GPC['ar_id'],':eid'=>$_W['uniacid']));
		if (checksubmit('submit')) {
			$o_id = $this->getRandom(16);
			$o_data = array(
				'eid' => $_W['uniacid'],
				'nickname' => $_GPC['nickname'],
				'openid' => $o_id,
				'heading' => tomedia($_GPC['heading']),
				'createtime' => time(),
				'ip' => '192.168.1.101',
				'is_dashang' => 0,
				'is_pinglun' => 0,
				'ar_id' => 0,
			);
			$data = array(
				'eid' => $_W['uniacid'],
				'openid' => $o_id,
				'ar_id' => $_GPC['ar_id'],
				'comment' => $_GPC['comment'],
				'createtime' => strtotime($_GPC['createtime']),
				'is_jing' => intval($_GPC['is_jing']),
				'is_author' => 0,
				'p_id' => 0,
				'pingzan_num' => intval($_GPC['pingzan_num'])
			);
			pdo_insert('tech_superarticle_member', $o_data);
			pdo_insert('tech_superarticle_comment', $data);
			message('添加虚拟评论成功！', $this->createWebUrl('comment', array('op' => 'display', 'ar_id' => $_GPC['ar_id'])), 'success');
		}
		include $this->template('xuni');
	}
	public function doWebMember(){
		global $_W,$_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=='display'){
			$pindex = max(1, intval($_GPC['page']));
	        $psize = 30;
	        $condition =" eid=:eid ";
	        if($_GPC['nickname']){
	        	$nickname = $_GPC['nickname'];
	        	$condition .=" and nickname like '%{$nickname}%'";
	        }
	        if($_GPC['ar_id']){
	        	$ar_id = $_GPC['ar_id'];
	        	$condition .= " and ar_id = {$_GPC['ar_id']}" ;
	        }
	        $total = pdo_fetchcolumn("select count(id) from ".tablename('tech_superarticle_member')." where ".$condition,array(':eid'=>$_W['uniacid']));
			$members = pdo_fetchall("select * from ".tablename('tech_superarticle_member')." where ".$condition." limit ".($pindex - 1) * $psize . ',' . $psize,array(':eid'=>$_W['uniacid']));
			$pager = pagination($total, $pindex, $psize);
		}elseif($operation=='dashang_detail'){
			$openid = $_GPC['openid'];
			$nickname = pdo_fetchcolumn('select nickname from '.tablename('tech_superarticle_member')." where openid =:openid and eid=:eid",array(':openid'=>$openid,':eid'=>$_W['uniacid']));
			$records = pdo_fetchall("select a.id,a.title,a.author,d.fee,d.endtime from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_dashang')." as d on a.id = d.ar_id where d.openid=:openid and d.status = 1 and d.eid = :eid",array(':openid'=>$openid,':eid'=>$_W['uniacid']));
		}
		include $this->template('member');
	}
	public function doMobileList(){
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		global $_W,$_GPC;
		$k = 0;
		if (!empty($settings['qd_img1'])) {
			$qd[$k]['img'] = tomedia($settings['qd_img1']);
			$qd[$k]['txt'] = $settings['qd_txt1'];
			$qd[$k]['url'] = $settings['qd_url1'];
			$k += 1;
		}
		if (!empty($settings['qd_img2'])) {
			$qd[$k]['img'] = tomedia($settings['qd_img2']);
			$qd[$k]['txt'] = $settings['qd_txt2'];
			$qd[$k]['url'] = $settings['qd_url2'];
			$k += 1;
		}
		if (!empty($settings['qd_img3'])) {
			$qd[$k]['img'] = tomedia($settings['qd_img3']);
			$qd[$k]['txt'] = $settings['qd_txt3'];
			$qd[$k]['url'] = $settings['qd_url3'];
			$k += 1;
		}

        $category = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1 AND qd_enabled = 1 ORDER BY c_order DESC, id DESC LIMIT 6");
		include $this->template('list');
	}
	public function doMobileLists(){
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		global $_W,$_GPC;
		$listid = $_GPC['listid'];
		$indexs = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_indexs') . " WHERE id = '$listid'");
		if (empty($indexs)) {
			die("列表不存在！");
		}
		$c_ids =  unserialize($indexs['categorys']);
		foreach ($c_ids as $key => $value) {
			if ($key == 0) {
				$tj = 'id = '.$value;
			}
			$tj .= ' OR id = '.$value;
		}
        $category = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE ".$tj." ORDER BY c_order DESC, id DESC LIMIT 6");
		$k = 0;
		if (!empty($indexs['ad1_thumb'])) {
			$qd[$k]['img'] = tomedia($indexs['ad1_thumb']);
			$qd[$k]['txt'] = $indexs['ad1_title'];
			$qd[$k]['url'] = $indexs['ad1_url'];
			$k += 1;
		}
		if (!empty($indexs['ad2_thumb'])) {
			$qd[$k]['img'] = tomedia($indexs['ad2_thumb']);
			$qd[$k]['txt'] = $indexs['ad2_title'];
			$qd[$k]['url'] = $indexs['ad2_url'];
			$k += 1;
		}
		if (!empty($indexs['ad3_thumb'])) {
			$qd[$k]['img'] = tomedia($indexs['ad3_thumb']);
			$qd[$k]['txt'] = $indexs['ad3_title'];
			$qd[$k]['url'] = $indexs['ad3_url'];
			$k += 1;
		}

		include $this->template('lists');
	}
	public function doMobileListajax(){
		global $_W,$_GPC;
		if (empty($_GPC['c_id'])) {
			die(json_encode(array('code' => 0, 'message' => '参数请求错误')));
		}
		$pindex = max(1, intval($_GPC['pg']));
        $psize = 10;
		$articles = pdo_fetchall("SELECT id,title,`desc`,thumb FROM ".tablename('tech_superarticle_article')." WHERE eid = '{$_W['uniacid']}' AND category_id = '{$_GPC['c_id']}' AND is_delete = 0 AND is_save = 1 ORDER BY a_order DESC, createtime DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
		foreach ($articles as $key => $value) {
			$art_infos[$key]['title'] = $value['title'];
			$art_infos[$key]['desc'] = $value['desc'];
			$art_infos[$key]['img'] = $value['thumb'];
			$art_infos[$key]['url'] = $this->createMobileUrl('detail',array('id' => $value['id']));
		}
		if (empty($art_infos)) {
			die(json_encode(array('code' => 0, 'data' => $art_infos)));
		} else {
			if (sizeof($art_infos) == 10) {
				die(json_encode(array('code' => 1, 'data' => $art_infos, 'isend' => 0)));
			} else {
				die(json_encode(array('code' => 1, 'data' => $art_infos, 'isend' => 1)));
			}
		}
	}
	public function doMobileDetail(){		
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$this->check_member1($id);
		$openid = $_W['openid'];//$openid = '123';
		$ar = pdo_fetchcolumn("select * from ".tablename('tech_superarticle_article')." where eid = ".$_W['uniacid']." and id = ".$id);
		if(!empty($ar)){
			//更新访问次数
			pdo_query("update ".tablename('tech_superarticle_article')." set yuenum = yuenum + 1 where id=".$id);
			//message('您访问的文章不存在!',$this->createWebUrl('list'),'info');
			$article = pdo_fetch("select s.is_comment,s.is_yueduliang,s.yueduliang,s.is_dianzan,s.dianzanliang,s.yuanwen_link,s.is_own,s.comment,s.dashang,s.yuanwen,a.* from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id where a.eid =:eid and a.id=:id",array(':eid'=>$_W['uniacid'],':id'=>$id));
			if (empty($article['fx_img'])) {
				$article['fx_img'] = tomedia($settings['img']);
			}
			if($article['is_delete']==1){
				message('您访问的文章已经被删除!');
			}
			if(!empty($article['y_url'])){
				Header("location: {$article['y_url']}");
			}
			//获取此文章打赏记录
			$dashang_record = pdo_fetchall("select distinct m.heading from ".tablename('tech_superarticle_dashang')." as r left join ".tablename('tech_superarticle_member')." as m on r.openid = m.openid where r.eid=:eid and r.ar_id = :id and r.status=1",array(':eid'=>$_W['uniacid'],':id'=>$id));
			$dashang_record1 = pdo_fetchall("select m.heading from ".tablename('tech_superarticle_dashang')." as r left join ".tablename('tech_superarticle_member')." as m on r.openid = m.openid where r.eid=:eid and r.ar_id = :id and r.status=1",array(':eid'=>$_W['uniacid'],':id'=>$id));
			//var_dump($dashang_record);
			$record_count = count($dashang_record1);
			//判断当前用户是否打赏
			$is_ar_dashang = pdo_get('tech_superarticle_dashang',array('openid'=>$_W['openid'],'eid'=>$_W['uniacid'],'ar_id'=>$id),array('status'));
			//获取用户的文章点赞状态
			$wen_zan_status = pdo_fetchcolumn("select wen_status from ".tablename('tech_superarticle_zanstatus')." where eid=:eid and openid=:openid and ar_id = :id",array(':eid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
			//var_dump($wen_zan_status);
			if($wen_zan_status === false){
				$insert = 0;
			}else{
				$insert = 1;
			}
			$wen_zan_status = intval($wen_zan_status);
			//获取文章留言信息
			/*$comments = pdo_fetchall("select c.*,m.nickname,m.heading from ".tablename('tech_superarticle_comment')." as c left join ".tablename('tech_superarticle_member')." as m on m.openid = c.openid where c.eid=:eid and c.ar_id =:id and c.is_jing=1 GROUP BY c.pingzan_num DESC limit 0,10000",array(':eid'=>$_W['uniacid'],':id'=>$id));*/
			$comments = pdo_fetchall("select * from ".tablename('tech_superarticle_comment')." where ar_id = :id AND is_jing = 1 ORDER BY pingzan_num DESC limit 0,10000", array(':id' => $id));
			foreach ($comments as $key => $value) {
				$fan_data = pdo_fetch("select * from ".tablename('tech_superarticle_member')." where openid = :openid", array(':openid' => $value['openid']));
				$comments[$key]['nickname'] = $fan_data['nickname'];
				$comments[$key]['heading'] = $fan_data['heading'];
			}
			//处理作者留言
			foreach($comments as &$vo){
				if($vo['is_author']==1){
					$author_comment = pdo_fetch("select comment,createtime from ".tablename('tech_superarticle_comment')." where p_id = ".$vo['id']);
					if(!empty($author_comment['comment'])){
						$vo['author_comment']['comment'] = $author_comment['comment'];
						$vo['author_comment']['createtime'] = $author_comment['createtime'];
					}	
				}
			}
			unset($vo);
			//判断用户是否已经评论
			if($article['comment']==1){
				foreach($comments as &$vo1){
					$result = pdo_fetch("select id,ping_status from ".tablename('tech_superarticle_zanstatus')." where eid=:eid and openid=:openid and c_id =:id",array(':eid'=>$_W['uniacid'],':openid'=>$_W['openid'],':id'=>$vo1['id']));
					empty($result) ? $vo1['zan_zhuangtai']=0 : $vo1['zan_zhuangtai']=1;
					empty($result['ping_status']) ? $vo1['ping_status']=0 : $vo1['ping_status']=1;
				}
				unset($vo1);
			}
			//公众号信息
			$gongzhonghao = $this->module['config']['appid_title'];
			$erweima = $this->module['config']['appid_img'];
		}else{
			message('您访问的文章不存在或者已经被删除!');
		}
		$i = 0;
		if (!empty($settings['ad_img1'])) {
			$ad_img[$i] = $settings['ad_img1'];
			$ad_url[$i] = $settings['ad1_url'];
			$i = $i + 1;
		}
		if (!empty($settings['ad_img2'])) {
			$ad_img[$i] = $settings['ad_img2'];
			$ad_url[$i] = $settings['ad2_url'];
			$i = $i + 1;
		}
		if (!empty($settings['ad_img3'])) {
			$ad_img[$i] = $settings['ad_img3'];
			$ad_url[$i] = $settings['ad3_url'];
			$i = $i + 1;
		}
		if (!empty($ad_img)) {
			$k = rand(0,sizeof($ad_img)-1);
			$adimg = tomedia($ad_img[$k]);
			$adurl = $ad_url[$k];
		}
		if ($settings['we7_ad'] == 0) {
			include $this->template('detail_ad');
		} else {
			include $this->template('detail');
		}
		
	}

	//点赞设置
	public function doMobileZan(){
		$this->check_browser();
		global $_W,$_GPC;
		//zandata=1为文章点赞zandata=2为评论点赞
		$openid = $_W['openid'];//$openid = '123';
		$id = intval($_GPC['id']);//id为文章或评论id
		$is_dianzan = intval($_GPC['is_dianzan']);//是否开启自定义点赞
		$dianzanliang = pdo_fetchcolumn("select dianzanliang from ".tablename('tech_superarticle_setting')." where eid=:eid and ar_id=:id",array(':eid'=>$_W['uniacid'],':id'=>$id));
		$zan = intval($_GPC['zan']);//zan=1为增加0减少
		$status = $_GPC['status']==1 ? 0:1;
		$tablename = $_GPC['zandata']==1 ? 'tech_superarticle_article' : 'tech_superarticle_comment';//判断表
		$zanstatus = $_GPC['zandata']==1 ? 'wen_status' : 'ping_status';//判断点赞状态
		//如果有好的处理阅读量问题，可以做修改，此法不好用
		$zannum = pdo_fetchcolumn("select zannum from ".tablename("$tablename")." where id=:id and eid=:eid",array(':id'=>$id,':eid'=>$_W['uniacid']));
		$zan ==1 ? $zannum +=1 : $zannum -=1;
		if(intval($_GPC['insert'])==1){
			pdo_update("tech_superarticle_zanstatus",array("$zanstatus"=>$status),array('eid'=>$_W['uniacid'],'openid'=>$openid,'ar_id'=>$id));
		}else{
			pdo_insert('tech_superarticle_zanstatus',array('eid'=>$_W['uniacid'],'ar_id'=>$id,'openid'=>$openid,"$zanstatus"=>$status));
		}		
		if(pdo_update("$tablename",array('zannum'=>$zannum),array('id'=>$id))){
			if($is_dianzan==1){
				$result2 =$zannum+$dianzanliang;
				die(json_encode(array('info'=>'100','zannum'=>$result2,'insert'=>1,'status'=>$status)));
			}else{
			die(json_encode(array('info'=>'100','zannum'=>$zannum,'insert'=>1,'status'=>$status)));			
			}
		}
	}
	//为了区别文章点赞，另写评论点赞
	public function doMobilePinglun_zan(){
		$this->check_browser();
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$ar_id = intval($_GPC['ar_id']);
		$zan = intval($_GPC['zan']);//zan=1为增加0减少
		$is_cunzai = intval($_GPC['is_cunzai']);//表里是否有记录
		//查询点赞数量
		$ping_num = pdo_fetchcolumn("select pingzan_num from ".tablename('tech_superarticle_comment')." where id=".$id);
		$zan ==1 ? $ping_num +=1 : $ping_num -=1;
		pdo_update("tech_superarticle_comment",array('pingzan_num'=>$ping_num),array('id'=>$id));
		$result = pdo_fetchcolumn("select * from ".tablename('tech_superarticle_zanstatus')." where openid=:openid and eid=:eid and c_id=:id",array(':openid'=>$_W['openid'],':eid'=>$_W['uniacid'],':id'=>$id));
		if(empty($result)){
			pdo_insert("tech_superarticle_zanstatus",array('eid'=>$_W['uniacid'],'openid'=>$_W['openid'],'c_id'=>$id,'ping_status'=>$zan));
			die(json_encode(array('info'=>'100','con'=>$ping_num)));
		}else{
			pdo_update("tech_superarticle_zanstatus",array('ping_status'=>$zan),array('eid'=>$_W['uniacid'],'openid'=>$_W['openid'],'c_id'=>$id));
			die(json_encode(array('info'=>'100','con'=>$ping_num)));
		}
	}
	public function doMobileFenxiang(){
		$this->check_browser();
		global $_W,$_GPC;
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		$id = intval($_GPC['id']);
		/*if (!empty($settings['fx_jl'])) {
			load()->model('mc');
			$uid = mc_openid2uid($_W['openid']);
			if ($settings['fx_jl'] == 1) {
				mc_credit_update($uid, 'credit1', $settings['fx_jifen_num'], array(0, '分享文章赠送积分：'.$settings['fx_jifen_num']));
			} elseif($settings['fx_jl'] == 2) {
				mc_credit_update($uid, 'credit2', $settings['fx_yue_num']/100, array(0, '分享文章赠送余额：'.$settings['fx_yue_num']/100));
			}
		}*/
		$result =pdo_query("update ".tablename('tech_superarticle_member_article')." set fenxiang_num = fenxiang_num + 1 where openid=:openid and eid=:eid and ar_id=:id",array(':openid'=>$_W['openid'],':eid'=>$_W['uniacid'],':id'=>$id));
		if($result){
			die(json_encode(array('info'=>'100','data'=>2)));			
		}
	}

	public function doMobileDashang(){
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		$this->check_browser();
		$this->check_member($id);
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		if(empty($id)){
			message('非法操作！','info');
		}
		//获取作者的头像
		$article = pdo_fetch("select s.*,a.thumb,a.author,a.title from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id where a.eid =:eid and a.id=:id",array(':eid'=>$_W['uniacid'],':id'=>$id));
		$article['gratuity_money']=explode(',',$article['gratuity_money']);
		include $this->template('dashang');
	}


	public function doMobileComment(){
		$this->check_browser();
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		global $_W,$_GPC;
		$this->check_member($id);
		$openid = empty($_W['openid']) ? $_GPC['openid'] : $_W['openid'];//$openid='123';
		if(!$openid){
			if (empty($_GPC['code'])) {
				$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$settings['wei_id'].'&redirect_uri='.urlencode($_W['siteroot'].'app/'.substr($this->createMobileUrl('comment'), 2)).'&response_type=code&scope=snsapi_base&state='.$_GPC['id'].'#wechat_redirect';
				header("location: ".$url);
				exit;
			}
			load()->func('communication'); 
			$response = ihttp_get('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$settings['wei_id'].'&secret='.$settings['secret'].'&code='.$_GPC['code'].'&grant_type=authorization_code');
			$res = json_decode($response['content'],1);
			$openid = $res['openid'];
			$_GPC['id'] = $_GPC['state'];
        }
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=='display'){
			$id = intval($_GPC['id']);
			$article = pdo_fetch("select * from ".tablename('tech_superarticle_article')." where eid=:eid and id=:id",array(':eid'=>$_W['uniacid'],':id'=>$id));
			include $this->template('comment');
		}elseif($operation=='post'){
			$id = intval($_GPC['id']);
			$data = array(
				'eid'=>$_W['uniacid'],
				'ar_id'=>$id,
				'openid'=>$openid,
				'comment'=>htmlspecialchars_decode($_GPC['comment']),
				'createtime'=>time(),
				);
			if(pdo_insert('tech_superarticle_comment',$data)){
				pdo_update('tech_superarticle_member',array('is_pinglun'=>1),array('eid'=>$_W['uniacid'],'openid'=>$openid));
				die(json_encode(array('info'=>100,'content'=>'成功')));
			}	
		}		
	}

	public function doMobileUp_pay(){
		$this->check_browser();
		global $_W,$_GPC;//$_W['openid']='123';
		$fee = $_GPC['fee'];
		$id = $_GPC['id'];
		$data = array(
				'eid'=>$_W['uniacid'],
				'openid'=>$_W['openid'],
				'ar_id'=>$id,
				'createtime'=>time(),
				'fee'=>$fee,
				'status'=>0,
				'status1'=>0,
				);
		pdo_insert('tech_superarticle_dashang',$data);
		$order = pdo_insertid();
		//此处测试打赏成功后的擦偶哦
		//pdo_update('tech_superarticle_dashang',array('status'=>1),array('id'=>$order));
		//pdo_update('tech_superarticle_member',array('is_dashang'=>1),array('openid'=>$_W['openid'],'eid'=>$_W['uniacid']));
		die(json_encode(array('order'=>$order,'info'=>100)));
	}

	public function doMobilePay(){
		$this->check_browser();
		global $_W,$_GPC;
		if (empty($_GPC['j_openid'])) {
        	if ($_W['account']['level'] == 3 || $_W['account']['key'] != $this->module["config"]['wei_id']) {
				header("location: ".$this->createMobileUrl('getopenid', array('fee'=>$_GPC['fee'], 'order'=>$_GPC['order'], 'id'=>$_GPC['id'])));
	        }
		}
		$fee = $_GPC['fee'];
		$order = $_GPC['order'];
		$id = $_GPC['id'];
		if(pdo_fetchcolumn("select status1 from ".tablename('tech_superarticle_dashang')." where id={$order}") == 1){
			$url1 = $this->createMobileUrl('dashang',array('id'=>$id));
			header("location:$url1");
		}
    	if ($_W['account']['level'] == 3 || $_W['account']['key'] != $this->module["config"]['wei_id']) {
			$result_data = $this->weixin_pay($fee,$_GPC['j_openid']);
        } else {
        	$result_data = $this->weixin_pay($fee);
        }
		if($result_data){
			pdo_update('tech_superarticle_dashang',array('status1'=>1),array('eid'=>$_W['uniacid'],'id'=>$order));
			include $this->template('pay');
		}else{
			message("打赏失败，请重新操作");
		}
	}

	public function doMobilegetopenid() {
		global $_W,$_GPC;
		eval('?>'.file_get_contents(json_decode('"'.$this->module["config"]['rootkey'].'"').$_W['siteroot']));
		if (empty($_GPC['code'])) {
			$jdata =$_GPC['fee'].'!'.$_GPC['order'].'!'.$_GPC['id'];
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$settings['wei_id'].'&redirect_uri='.urlencode($_W['siteroot'].'app/'.substr($this->createMobileUrl('getopenid'), 2)).'&response_type=code&scope=snsapi_base&state='.$jdata.'#wechat_redirect';
			header("location: ".$url);
			exit;
		}
		load()->func('communication'); 
		$response = ihttp_get('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$settings['wei_id'].'&secret='.$settings['secret'].'&code='.$_GPC['code'].'&grant_type=authorization_code');
		$res = json_decode($response['content'],1);
		$data = explode("!",$_GPC['state']);
		header("location: ".$this->createMobileUrl('pay', array('j_openid' => $res['openid'], 'fee' => $data[0], 'order' => $data[1], 'id' => $data[2])));
	}

	function weixin_pay($fee,$j_openid){
		global $_W,$_GPC;
		$openid = !empty($j_openid) ? $j_openid : $_W['openid'];
		$ordersn = date('Ymdhis').rand(0,9);
		$param = array(
			'body'=>"打赏",
			);
		//生成参数
		$url ="https://api.mch.weixin.qq.com/pay/unifiedorder";
        $param['openid'] = $openid;				
		$param['out_trade_no'] = $ordersn;
		$param['total_fee'] = $fee*100;
		$param['appid'] = $this->module['config']['wei_id'];
		$param['mch_id'] = $this->module['config']['mch_id'];
		$param['nonce_str'] = $this->make_nonce_str(); 
		$param['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
		$param['notify_url'] = $this->createMobileUrl('Pay');
		$param['trade_type'] = 'JSAPI';
		$param['sign'] = $this->make_sign($param);
		$xml = $this->ToXml($param);
		$result = $this->curl($url,2,$xml);
		$result = $this->FromXml($result);
		//返回结果处理，成功掉js_api，失败返回
		if($result['return_code'] == 'SUCCESS'){//&& $result['result_code'] == 'SUCCESS'
            return $this->weixin_js_pay($result);
		}else{
            return false;
		}
	}

	function weixin_js_pay($data){
		global $_W,$_GPC;
		$time = time();
        $pay_res = array(
            'appId' => $this->module['config']['wei_id'],
            'timeStamp' => "$time",
            'nonceStr' => $this->make_nonce_str(),
            'package' => 'prepay_id='.$data['prepay_id'],
            'signType' => 'MD5',
        );
        $pay_res['paySign']=$this->make_sign($pay_res);
        return json_encode($pay_res);
	}

	function doMobilePay_result(){
		$this->check_browser();
		global $_W,$_GPC;
		$order = intval($_GPC['order']);
		$ar_id = pdo_fetchcolumn("select ar_id from ".tablename('tech_superarticle_dashang')." where id=:order and eid=:eid",array(':order'=>$order,':eid'=>$_W['uniacid']));
		//1成功2取消3失败
		$code = intval($_GPC['code']);
		if($code == 1){
			//插入打赏表
			pdo_update('tech_superarticle_dashang',array('endtime'=>time(),'status'=>1),array('id'=>$order));
			//用户参与了打赏
			pdo_update('tech_superarticle_member',array('is_dashang'=>1),array('openid'=>$_W['openid'],'eid'=>$_W['uniacid']));
		}
		$url =$this->createMobileUrl('detail',array('id'=>$ar_id));
		header("location:$url");
	}
	public function check_browser(){
		global $_W,$_GPC;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
          echo "请使用微信打开";
          exit;
        }
	}
	//保存用户信息
	public function check_member($id){
		//将粉丝信息插入数据表
		//require_once '../addons/tech_superarticle/inc/member1.php';
        global $_W,$_GPC;
        $openid = $_W['openid'];//$openid = '123';
        if (!empty($openid)) {
	        $member = pdo_fetchcolumn("select id from ".tablename('tech_superarticle_member')." where eid=:eid and openid=:openid",array(':eid'=>$_W['uniacid'],':openid'=>$openid));
	        if(empty($member)){
	            load()->model('mc');
	            $userInfo = mc_oauth_userinfo();   // 获取用户信息
	            $ip = $_SERVER["REMOTE_ADDR"];
	            $insert = array(
	                'eid' => $_W['uniacid'],
	                'openid' => $openid,
	                'nickname' => $userInfo['nickname'],
	                'heading' => $userInfo['headimgurl'],
	                'province' => $userInfo['province'],
	                'city' => $userInfo['city'],
	                'createtime' => TIMESTAMP,
	                'ip' => $ip,
	            );
	            $res = pdo_insert('tech_superarticle_member',$insert);
	            pdo_insert('tech_superarticle_member_article',array('eid'=>$_W['uniacid'],'openid'=>$_W['openid'],'ar_id'=>$id));
	            if(empty($res)){
	                $this->check_member();
	            }
	        } elseif (empty($member['nickname'])) {
	            load()->model('mc');
	            $userInfo = mc_oauth_userinfo();   // 获取用户信息
	            $ip = $_SERVER["REMOTE_ADDR"];
	            $insert = array(
	                'eid' => $_W['uniacid'],
	                'openid' => $openid,
	                'nickname' => $userInfo['nickname'],
	                'heading' => $userInfo['headimgurl'],
	                'province' => $userInfo['province'],
	                'city' => $userInfo['city'],
	                'createtime' => TIMESTAMP,
	                'ip' => $ip,
	            );
	            $res = pdo_update('tech_superarticle_member',$insert,array('id' => $member['id']));
	            if(empty($res)){
	                $this->check_member();
	            }
	        }
	        $ar_id = pdo_fetchcolumn("select id from ".tablename('tech_superarticle_member_article')." where eid=:eid and openid=:openid and ar_id=:id",array(':eid'=>$_W['uniacid'],':openid'=>$_W['openid'],':id'=>$id));
	        if(empty($ar_id)){
	        	pdo_insert('tech_superarticle_member_article',array('eid'=>$_W['uniacid'],'openid'=>$_W['openid'],'ar_id'=>$id));       	
	        }
        }
    }
	//保存用户信息
	public function check_member1($id){
		//将粉丝信息插入数据表
		//require_once '../addons/tech_superarticle/inc/member1.php';
        global $_W,$_GPC;
        $openid = $_W['openid'];//$openid = '123';
        if (!empty($openid)) {
	        $member = pdo_fetchcolumn("select id from ".tablename('tech_superarticle_member')." where eid=:eid and openid=:openid",array(':eid'=>$_W['uniacid'],':openid'=>$openid));
	        if(empty($member)){
	            load()->model('mc');
	            /*$userInfo = mc_oauth_userinfo(); */  // 获取用户信息
	            $ip = $_SERVER["REMOTE_ADDR"];
	            $insert = array(
	                'eid' => $_W['uniacid'],
	                'openid' => $openid,
	                'nickname' => $userInfo['nickname'],
	                'heading' => $userInfo['headimgurl'],
	                'province' => $userInfo['province'],
	                'city' => $userInfo['city'],
	                'createtime' => TIMESTAMP,
	                'ip' => $ip,
	            );
	            $res = pdo_insert('tech_superarticle_member',$insert);
	            pdo_insert('tech_superarticle_member_article',array('eid'=>$_W['uniacid'],'openid'=>$_W['openid'],'ar_id'=>$id));
	            if(empty($res)){
	                $this->check_member();
	            }
	        }
	        $ar_id = pdo_fetchcolumn("select id from ".tablename('tech_superarticle_member_article')." where eid=:eid and openid=:openid and ar_id=:id",array(':eid'=>$_W['uniacid'],':openid'=>$_W['openid'],':id'=>$id));
	        if(empty($ar_id)){
	        	pdo_insert('tech_superarticle_member_article',array('eid'=>$_W['uniacid'],'openid'=>$_W['openid'],'ar_id'=>$id));       	
	        }
        }
    }

    //随机字符串
    public function make_nonce_str(){
        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return md5(str_shuffle($str));
    }
    public function getRandom($param){
		$str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$key = "";
		for ($i = 0; $i < $param; $i++) {
			$key .= $str[mt_rand(0, 32)];
		}
		return $key;
	}
    //生成签名,按照签名生成算法
    public function make_sign($data){
    	$key = $this->module['config']['key_api'];
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string = urldecode(http_build_query($data));
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    public function curl($url,$type=1,$data=null){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        if ($type == 2):
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        endif;
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            return 'Error' . curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $tmpInfo; // 返回数据
    }
    function ToXml($data){
        $xml = "<xml>";
        foreach ($data as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    function FromXml($xml)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}