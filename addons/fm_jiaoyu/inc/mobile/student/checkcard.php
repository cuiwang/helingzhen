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
		if ($_GPC['user']){
			$_SESSION['user'] = intval($_GPC['user']);
		}

		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id ", array(':id' => $_SESSION['user']));

		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		
        if(!empty($it)){
			
			$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_idcard) . " where schoolid = :schoolid And sid = :sid ", array(
				':schoolid' => $schoolid,
				':sid' => $it['sid']
			));
			foreach($list as $index => $row){

				$students = pdo_fetch("SELECT ouid,muid,duid FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $row['sid']));
				if($row['pard'] == 1){
					$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $weid, ':uid' => $students['ouid']));
				}
				if($row['pard'] == 2){
					$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $weid, ':uid' => $students['muid']));
				}
				if($row['pard'] == 3){
					$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $weid, ':uid' => $students['duid']));
				}
				$list[$index]['avatar'] = $member['avatar'];
			}
            $num = count($list);
			$checktotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} AND sid ={$it['sid']}");
			
			$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $it['sid']));
												
			include $this->template(''.$school['style2'].'/checkcard');
        }else{
			session_destroy();
            include $this->template('bangding');
        }        
?>