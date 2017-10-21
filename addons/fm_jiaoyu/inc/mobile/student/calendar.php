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
		$logid = trim($_GPC['logid']);	
		if (!empty($_GPC['userid'])){
			$_SESSION['user'] = $_GPC['userid'];
		}
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id And openid = :openid ", array(':id' => $_SESSION['user'],':openid' => $openid));
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		$student = pdo_fetch("SELECT s_name,bj_id FROM " . tablename($this->table_students) . " where id = :id AND schoolid = :schoolid ", array(':id' => $it['sid'], ':schoolid' => $schoolid));
		if(!empty($it)){
			if (empty($_W['setting']['remote']['type'])) { 
				$urls = "../addons/fm_jiaoyu/"; 
			} else {
				$urls = "../addons/fm_jiaoyu/"; 
			}
			include $this->template(''.$school['style2'].'/calendar');
        }else{
            include $this->template('bangding');
        }        
?>