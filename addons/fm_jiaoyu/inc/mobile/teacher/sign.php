<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W ['uniacid'];
		$schoolid = intval($_GPC['schoolid']);
		$openid = $_W['openid'];
		$time = $_GPC['time'];
		$bj_id = trim($_GPC['bj_id']);
		$sid = trim($_GPC['sid']);

		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $userid['id']));
		$school = pdo_fetch("SELECT spic,style3,title FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		if(!empty($it)){
			if($sid){
				$student = pdo_fetch("SELECT s_name,id FROM " . tablename($this->table_students) . " where id = :id AND schoolid = :schoolid ", array(':id' => $sid, ':schoolid' => $schoolid));
			}else{
				$student = pdo_fetch("SELECT s_name,id FROM " . tablename($this->table_students) . " where bj_id = :bj_id AND schoolid = :schoolid ORDER BY id ASC LIMIT 0,1", array(':bj_id' => $bj_id, ':schoolid' => $schoolid));
			}
			$students = pdo_fetchall("SELECT s_name,id,icon FROM " . tablename($this->table_students) . " where bj_id = :bj_id AND schoolid = :schoolid ", array(':bj_id' => $bj_id, ':schoolid' => $schoolid));
			if (empty($_W['setting']['remote']['type'])) { 
				$urls = "../addons/fm_jiaoyu/"; 
			} else {
				$urls = "../addons/fm_jiaoyu/"; 
			}			
			include $this->template(''.$school['style3'].'/sign');
        }else{
            include $this->template('bangding');
        }        
?>