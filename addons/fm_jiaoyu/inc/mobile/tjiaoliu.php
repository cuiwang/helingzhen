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
		$leaveid = $_GPC['id'];
		$lid = $_GPC['leaveid'];
        
        //查询是否用户登录		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $userid['id']));	
		$techer = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :openid = openid And :schoolid = schoolid And :weid = weid", array(':openid' => $it['openid'],':schoolid' => $schoolid,':weid' => $weid));
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :id = id", array(':id' => $leaveid));
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');			
        $school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));
	   if(!empty($userid['id'])){
            
			$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $_W ['uniacid'], ':id' => $leave['sid']));
			$member = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid AND uid = :uid", array(':uniacid' => $_W ['uniacid'], ':uid'=> $leave['uid']));
			$members = pdo_fetchall("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid']), 'uid');

		    $jiaz = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :tid = tid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $leave['openid'], ':tid' => 0), 'id');
		    $itj = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $jiaz['id']));			
			
			$userinfo = iunserializer($itj['userinfo']);
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " WHERE weid = :weid AND schoolid =:schoolid AND bj_id = :bj_id AND leaveid = :leaveid And :isliuyan = isliuyan ORDER BY createtime DESC", array(
					':weid' => $_W['uniacid'],
					':schoolid' => $schoolid,
					':bj_id' => $leave['bj_id'],
					':leaveid' => $lid,
					':isliuyan' => 1
					));
				foreach ($list as $index => $row) {
				$member = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['uid']));
				$members = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['tuid']));
				$list[$index]['avatar'] = $member['avatar'];
				$list[$index]['nickname'] = $member['nickname'];
				$list[$index]['tavatar'] = $members['avatar'];
				$list[$index]['tnickname'] = $members['nickname'];
				}					

            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = :id ", array(':id' => $id));
			
		 include $this->template(''.$school['style3'].'/tjiaoliu');
          }else{
         include $this->template('bangding');
          }        
?>