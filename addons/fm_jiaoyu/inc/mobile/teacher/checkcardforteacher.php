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

		$it = pdo_fetch("SELECT id,tid FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0));

		$school = pdo_fetch("SELECT title,tpic,style3 FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		$teacher = pdo_fetch("SELECT uid,thumb,tname FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $it['tid']));
        if(!empty($it)){
			
			$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_idcard) . " where schoolid = :schoolid And tid = :tid ", array(
				':schoolid' => $schoolid,
				':tid' => $it['tid']
			));
			foreach($list as $index => $row){

				

				$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $weid, ':uid' => $teacher['uid']));

				$list[$index]['avatar'] = $member['avatar'];
			}
            $num = count($list);
			$checktotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} AND tid ={$it['tid']}");
												
			include $this->template(''.$school['style3'].'/checkcardforteacher');
        }else{
			session_destroy();
            include $this->template('bangding');
        }        
?>