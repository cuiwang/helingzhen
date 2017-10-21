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

		$it = pdo_fetch("SELECT id,tid FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0));
		$school = pdo_fetch("SELECT title,tpic,style3 FROM " . tablename($this->table_index) . " where weid = :weid And id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		$my = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = :id And schoolid = :schoolid ", array(':id' => $it['tid'], ':schoolid' => $schoolid));
		if(!empty($it)){
			$xsqj = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " where schoolid = :schoolid And tid = :tid And sid = 0 And isliuyan = 0 ORDER BY createtime DESC", array(
				':schoolid' => $schoolid,
				':tid' => $it['tid']
			));
			$thisid = 1;
			foreach($xsqj as $key => $row){
				$xsqj[$key]['guanxi'] = "";
				if(!$row['cltid']){
					$teacher = pdo_fetch("SELECT tname,thumb FROM " . tablename($this->table_teachers) . " where id = :id And schoolid = :schoolid And status = :status ", array(':id' => $bzr['tid'], ':schoolid' => $schoolid, ':status' => 2));
					$xsqj[$key]['tname'] = $teacher['tname'];
					$xsqj[$key]['thumb'] = $teacher['thumb'];					
				}else{
					$teacher = pdo_fetch("SELECT tname,thumb FROM " . tablename($this->table_teachers) . " where id = :id And schoolid = :schoolid ", array(':id' => $row['cltid'], ':schoolid' => $schoolid));
					$xsqj[$key]['tname'] = $teacher['tname'];
					$xsqj[$key]['thumb'] = $teacher['thumb'];
				}
					$xsqj[$key]['key'] = $thisid;
				$thisid ++;
			}
			if (empty($_W['setting']['remote']['type'])) {
				$urls = "../addons/fm_jiaoyu/"; 
			} else {
				$urls = "../addons/fm_jiaoyu/"; 
			}
			include $this->template(''.$school['style3'].'/leavelistforteacher');
        }else{
            include $this->template('bangding');
        }        
?>