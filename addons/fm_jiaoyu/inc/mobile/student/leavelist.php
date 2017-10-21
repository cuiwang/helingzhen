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
		$school = pdo_fetch("SELECT title,tpic,style2 FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		$student = pdo_fetch("SELECT s_name,bj_id FROM " . tablename($this->table_students) . " where id = :id AND schoolid = :schoolid ", array(':id' => $it['sid'], ':schoolid' => $schoolid));
		$bzr = pdo_fetch("SELECT tid FROM " . tablename($this->table_classify) . " where sid = :sid And type = 'theclass' ", array(':sid' => $student['bj_id']));
		if(!empty($it)){
			$xsqj = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " where schoolid = :schoolid AND sid = :sid And tid = 0 And isliuyan = 0 ORDER BY createtime DESC", array(
				':schoolid' => $schoolid,
				':sid' => $it['sid']
			));
			$thisid = 1;
			foreach($xsqj as $key => $row){
				$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where uid = :uid And openid = :openid And sid = :sid ", array(':uid' => $row['uid'],':openid' => $row['openid'],':sid' => $row['sid']));
				if($user['pard'] ==2){
					$xsqj[$key]['guanxi'] = "妈妈";
				}
				if($user['pard'] ==3){
					$xsqj[$key]['guanxi'] = "爸爸";
				}
				if($user['pard'] ==4){
					$xsqj[$key]['guanxi'] = "本人";
				}
				if($user['pard'] ==5){
					$xsqj[$key]['guanxi'] = "家长";
				}
				if(!$row['cltid']){
					$teacher = pdo_fetch("SELECT tname,thumb FROM " . tablename($this->table_teachers) . " where id = :id AND schoolid = :schoolid ", array(':id' => $bzr['tid'], ':schoolid' => $schoolid));
					$xsqj[$key]['tname'] = $teacher['tname'];
					$xsqj[$key]['thumb'] = $teacher['thumb'];					
				}else{
					$teacher = pdo_fetch("SELECT tname,thumb FROM " . tablename($this->table_teachers) . " where id = :id AND schoolid = :schoolid ", array(':id' => $row['cltid'], ':schoolid' => $schoolid));
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
			include $this->template(''.$school['style2'].'/leavelist');
        }else{
            include $this->template('bangding');
        }        
?>