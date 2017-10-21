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
        
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :tid = tid", array(':tid' => 0, ':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));	
		
		$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $it['sid']));
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :sid = sid And :uid = uid And :bj_id = bj_id And :isliuyan = isliuyan ORDER BY createtime ASC LIMIT 1", array(
		         ':weid' => $weid,
				 ':schoolid' => $schoolid,
				 ':bj_id' => $students['bj_id'],
				 ':uid' => $it['uid'],
				 ':isliuyan' => 1,
				 ':sid' => $it['sid']
				 )); 
		
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
		
        $tid = $category[$students['bj_id']]['tid'];
		
		$techers = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY id ASC, id DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'id');
		
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " WHERE weid = :weid AND schoolid =:schoolid AND bj_id = :bj_id AND leaveid = :leaveid And :isliuyan = isliuyan ORDER BY createtime DESC", array(':isliuyan' => 1, ':weid' => $_W['uniacid'], ':schoolid' => $schoolid, ':bj_id' => $students['bj_id'], ':leaveid' => $leave['id']));
		foreach ($list as $index => $row) {
		$member = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['uid']));
		$members = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['tuid']));
		$list[$index]['avatar'] = $member['avatar'];
		$list[$index]['nickname'] = $member['nickname'];
		$list[$index]['tavatar'] = $members['avatar'];
		$list[$index]['tnickname'] = $members['nickname'];
		}
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = :id ", array(':id' => $id));
						
        if(!empty($openid)){
        include $this->template(''.$school['style2'].'/jiaoliu');
		}else{
		include $this->template('bangding');	
		}
?>