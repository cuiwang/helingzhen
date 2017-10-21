<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
        $schoolid = intval($_GPC['schoolid']);
        $openid = $_W['openid'];
		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid), 'id');
		
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
        $title = $item['title'];
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		$ite = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $user['id']));	
		$isxz = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $ite['tid']));
		//查询科目设置
	    $km = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'subject' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'subject', ':schoolid' => $schoolid));
	    $it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
        if (empty($item)) {
            message('没有找到该学校，请联系管理员！');
        }
		
		$list1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_news) . " where :schoolid = schoolid And :weid = weid And :type = type ORDER BY displayorder DESC LIMIT 0,4", array(
		         ':weid' => $_W['uniacid'],
				 ':schoolid' => $schoolid,
				 ':type' => 'article'
				 ));
		$list2 = pdo_fetchall("SELECT * FROM " . tablename($this->table_news) . " where :schoolid = schoolid And :weid = weid And :type = type ORDER BY displayorder DESC LIMIT 0,4", array(
		         ':weid' => $_W['uniacid'],
				 ':schoolid' => $schoolid,
				 ':type' => 'news'
				 ));
		$list3 = pdo_fetchall("SELECT * FROM " . tablename($this->table_news) . " where :schoolid = schoolid And :weid = weid And :type = type ORDER BY displayorder DESC LIMIT 0,4", array(
		         ':weid' => $_W['uniacid'],
				 ':schoolid' => $schoolid,
				 ':type' => 'wenzhang'
				 ));				 

		
        $item1 = pdo_fetch("SELECT * FROM " . tablename($this->table_news) . " WHERE id = :id ", array(':id' => $id));		

		$banners = pdo_fetchall("SELECT * FROM " . tablename($this->table_banners) . " WHERE enabled = 1 AND weid = '{$_W['uniacid']}' ORDER BY leixing DESC, displayorder ASC");
		$barr = array();
		foreach ($banners as $key => $banner) {
			if ($banner['leixing'] == 1) {
				$uniarr = explode(',',$banner['arr']);
				$is = $this->uniarr($uniarr,$schoolid);
				if ($is && TIMESTAMP >= $banner['begintime'] && TIMESTAMP < $banner['endtime']) {
					$barr[$banner['leixing'].$key] = $banner;
				}
			}else{
				if ($banner['schoolid'] == $schoolid) {
					$barr[$banner['leixing'].$key] = $banner;
				}
			}
		}
		arsort($barr);
        include $this->template(''.$item['style1'].'/detail');
?>