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
		$obid = 2;
		$userss = intval($_GPC['userid']);
		$act = "bjq";
		$user = pdo_fetchall("SELECT * FROM " . tablename($this->table_user) . " where :weid = weid And :openid = openid And :tid = tid", array(
				':weid' => $weid,
				':openid' => $openid,
				':tid' => 0
				));
		$num = count($user);
		$flag = 1;
		if ($num > 1){
			$flag = 2;
		}
		foreach($user as $key => $row){
			$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id=:id ", array(':id' => $row['sid']));
			$bajinam = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid=:sid ", array(':sid' => $student['bj_id']));
			$user[$key]['s_name'] = $student['s_name'];
			$user[$key]['bjname'] = $bajinam['sname'];
			$user[$key]['sid'] = $student['id'];
			$user[$key]['schoolid'] = $student['schoolid'];
		}

		if(!empty($userss)){
			$ite = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And weid = :weid AND id=:id ", array(':schoolid' => $schoolid,':weid' => $weid, ':id' => $userss));
			if(!empty($ite)){
				$_SESSION['user'] = $ite['id'];
			}else{
				$stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('myschool', array('schoolid' => $schoolid));
				header("location:$stopurl");
				exit;
			}			
		}else{
			if(empty($_SESSION['user'])){
				$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :tid = tid LIMIT 0,1 ", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':tid' => 0), 'id');
				$_SESSION['user'] = $userid['id'];
			}
		}
		
        //查询是否用户登录		

		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And weid = :weid AND id=:id ", array(':schoolid' => $schoolid,':weid' => $weid, ':id' => $_SESSION['user']));	
		$school = pdo_fetch("SELECT style2,title,bjqstyle,spic,isopen FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));
		$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $it['sid']));		

        $shenfen = "";
		
		$isopen = 0;
		if ($it['pard'] == 2){
			$shenfen = "母亲";
		}else if($it['pard'] == 3){	
		    $shenfen = "父亲";
		}else if($it['pard'] == 4){	
		    $shenfen = "本人";			
		}else if($it['pard'] == 5){	
		    $shenfen = "家长";			
		}		
        $bj_id = $students['bj_id'];
		$thistime = strtotime($_GPC['limit']);
		if($thistime){
			$condition = " AND createtime < '{$thistime}'";
			$list1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 0 And (isopen = 0 Or uid = '{$it['uid']}') And ( bj_id1 = '{$bj_id}' Or bj_id2 = '{$bj_id}' Or bj_id3 = '{$bj_id}' ) $condition ORDER BY createtime DESC LIMIT 0,10");
			foreach ($list1 as $index => $v) {
				if (!empty($v['sherid'])) {
					$list1[$index]['picurl'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY id ASC", array(':sherid'=>$v['sherid']));
					$list1[$index]['znames'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_dianzan) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY createtime ASC LIMIT 0,4", array(':sherid'=>$v['sherid']));
					$num = count($list1[$index]['zname']);
					$list1[$index]['contents'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type=1 AND sherid =:sherid  ORDER BY createtime ASC", array(':sherid'=>$v['sherid']));
					$list1[$index]['isdianz'] = pdo_fetch("SELECT id FROM " . tablename($this->table_dianzan) . " where :schoolid = schoolid And :weid = weid  And :uid = uid And :sherid = sherid", array(
			          ':weid' => $weid,
                      ':schoolid' => $schoolid,
					  ':uid' => $it['uid'],
					  ':sherid' => $v['id']
					   ));
				} 
				$members = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $weid, ':uid' => $v['uid']));
				$list1[$index]['avatar'] = $members['avatar'];
				$list1[$index]['time'] = sub_day($v['createtime']);
			}
			include $this->template('bjqlist');
		}else{
			if(!empty($it)){
				if($school['bjqstyle'] =='old'){
					$tj = " ORDER BY createtime DESC";
				}
				if($school['bjqstyle'] =='new'){
					$tj = " ORDER BY createtime DESC LIMIT 0,10";
				}
				$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 0 And (isopen = 0 Or uid = '{$it['uid']}') And ( bj_id1 = '{$bj_id}' Or bj_id2 = '{$bj_id}' Or bj_id3 = '{$bj_id}') $tj ");
			
				foreach ($list as $index => $row) {
					 if (!empty($row['sherid'])) {
						$list[$index]['picurl'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY id ASC", array(':sherid'=>$row['sherid']));
						$list[$index]['zname'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_dianzan) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY createtime ASC", array(':sherid'=>$row['sherid']));
						$list[$index]['contents'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type=1 AND sherid =:sherid  ORDER BY createtime ASC", array(':sherid'=>$row['sherid']));
					}
					$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $weid, ':uid' => $row['uid']));
					$list[$index]['avatar'] = $member['avatar'];	
					$list[$index]['time'] = sub_day($row['createtime']);	
				}
			 				
				$this->checkpay($schoolid, $students['id'], $it['id'], $it['uid']);
				$this->checkobjiect($schoolid, $students['id'], $obid);
				if($school['bjqstyle'] =='old'){
					include $this->template(''.$school['style2'].'/sbjq');
				}
				if($school['bjqstyle'] =='new'){
					include $this->template(''.$school['style2'].'/sbjqnew');
				}
			}else{
				session_destroy();
				$stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bjq', array('schoolid' => $schoolid));
				header("location:$stopurl");
				exit;
			}
		}        
?>