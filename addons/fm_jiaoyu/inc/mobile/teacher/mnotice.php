<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
		$schoolid = intval($_GPC['schoolid']);
		$openid = $_W['openid'];
		$leaveid = intval($_GPC['id']);
		$record_id = intval($_GPC['record_id']);
        
        //查询是否用户登录		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $userid['id']));
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_notice) . " where :id = id", array(':id' => $leaveid));
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$weid}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');		
		
        $bzrtid = $category[$leave['bj_id']]['tid'];
		$bjname = $category[$leave['bj_id']]['sname'];
		
        if(!empty($userid['id'])){
            
			$member = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid AND uid = :uid", array(':uniacid' => $weid, ':uid'=> $leave['uid']));
			$isbzr = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $it['tid']));
			$picarr = iunserializer($leave['picarr']);
			$thisteacher = pdo_fetch("SELECT thumb FROM " . tablename($this->table_teachers) . " where schoolid = :schoolid AND id = :id", array(':schoolid' => $schoolid, ':id' => $leave['tid']));
			
			$record = pdo_fetch("SELECT * FROM " . tablename($this->table_record) . " where id = :id", array(':id' => $record_id));
			
			if(empty($record['readtime'])){
				$date = array(
					'readtime' =>time()
				);
				pdo_update($this->table_record, $date, array('id' => $record_id));				
			}			
		    include $this->template(''.$school['style3'].'/mnotice');
        }else{
            include $this->template('bangding');
        }        
?>