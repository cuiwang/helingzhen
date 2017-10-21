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
		
		$titel = "学生请假消息列表";
        
        //查询是否用户登录		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));	

		$teachers = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $it['tid']));		
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));

        if(!empty($userid['id'])){

			$bj_id = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where weid = :weid AND tid = :tid", array(':weid' => $_W ['uniacid'], ':tid' => $teachers['id']));
		    $leave = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :tid = tid And :bj_id = bj_id And :isliuyan = isliuyan ORDER BY createtime DESC", array(
		         ':weid' => $weid,
				 ':schoolid' => $schoolid,
				 ':bj_id' => $bj_id['sid'],
				 ':tid' => 0,
				 ':isliuyan' => 0
				 ));
				 
			foreach ($leave as $index => $row) {
				$member = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['uid']));
				$student = pdo_fetch("SELECT * FROM " . tablename ($this->table_students) . " where weid = :weid And id = :id ORDER BY id DESC", array(':weid' => $_W ['uniacid'], ':id' => $row['sid']));	
				$leave[$index]['avatar'] = $member['avatar'];
				$leave[$index]['s_name'] = $student['s_name'];
				$leave[$index]['own'] = $student['own'];
				$leave[$index]['mom'] = $student['mom'];
				$leave[$index]['dad'] = $student['dad'];
			}	 
				 
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = :id ", array(':id' => $id));		
			
		 include $this->template(''.$school['style3'].'/smssage');
          }else{
         include $this->template('bangding');
          }        
?>