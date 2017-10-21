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
		
		$it = pdo_fetch("SELECT id,tid FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0));
	
        $school = pdo_fetch("SELECT title,is_wxsign,is_recordmac,style3 FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));
		
		$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = :id AND schoolid = :schoolid ", array(':id' => $it['tid'], ':schoolid' => $schoolid));
		if(!empty($it)){
			if (empty($_W['setting']['remote']['type'])) { 
				$urls = "../addons/fm_jiaoyu/"; 
			} else {
				$urls = "../addons/fm_jiaoyu/"; 
			}
			include $this->template(''.$school['style3'].'/tcalendar');
        }else{
            include $this->template('bangding');
        }        
?>