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
		$userss = !empty($_GPC['userid']) ? intval($_GPC['userid']) : 1;
		$obid = 2;
		$act = "tx";
        //查询是否用户登录
		if(!$_SESSION['user']){
			mload()->model('user');
			$_SESSION['user'] = check_userlogin($weid,$schoolid,$openid,$userss);
			if ($_SESSION['user'] ==2){
				include $this->template('bangding');
			}	
		}		
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id And openid = :openid ", array(':id' => $_SESSION['user'],':openid' => $openid));
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id AND schoolid=:schoolid ", array(':weid' => $weid, ':id' => $it['sid'], ':schoolid' => $schoolid));
		$this->checkobjiect($schoolid, $student['id'], $obid);
        if(!empty($it)){
            $master = pdo_fetchall("SELECT tname,thumb,mobile,id,status,userid FROM " . tablename($this->table_teachers) . " WHERE weid = :weid AND schoolid = :schoolid AND status = :status ORDER BY id DESC", array(
				':weid' => $weid,
				':schoolid' => $schoolid,
				':status' => 2,
			));			
            $masterCount = count($master);
			$master1 = pdo_fetchall("SELECT tname,thumb,mobile,id,status,userid FROM " . tablename($this->table_teachers) . " WHERE weid = :weid AND schoolid = :schoolid AND status = :status ORDER BY id DESC", array(
				':weid' => $weid,
				':schoolid' => $schoolid,
				':status' => 3,
			));
			foreach($master1 as $key => $row){
				$category = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE tid =  :tid AND type = :type ", array(':tid' => $row['id'], ':type' => 'semester'));
				 $master1[$key]['njname'] = $category['sname'];
			}
            $masterCount1 = count($master1);
			$master2 = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " WHERE weid = :weid AND schoolid = :schoolid AND status = :status AND (bj_id1 =:bj_id1 OR bj_id2 = :bj_id2 OR bj_id3 = :bj_id3) ORDER BY id DESC", array(
				':weid' => $weid,
				':schoolid' => $schoolid,
				':status' => 1,
				':bj_id1' => $student['bj_id'],
				':bj_id2' => $student['bj_id'],
				':bj_id3' => $student['bj_id'],
			));
			foreach($master2 as $key => $row){
				if($row['bj_id1'] == $student['bj_id']){
					$tkm = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And sid = '{$row['km_id1']}' ");
				}
				if($row['bj_id2'] == $student['bj_id']){
					$tkm = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And sid = '{$row['km_id2']}' ");
				}
				if($row['bj_id3'] == $student['bj_id']){
					$tkm = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And sid = '{$row['km_id3']}' ");
				}				
				$master2[$key]['kmname'] = $tkm['sname'];
			}
            $masterCount2 = count($master2);
			if(!empty($student['bj_id'])){
				$bj = pdo_fetch("SELECT sid,sname FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And sid = '{$student['bj_id']}' ");	
				$xs1 = pdo_fetchall("SELECT id,s_name,icon FROM " . tablename($this->table_students) . " WHERE weid = :weid AND schoolid = :schoolid AND bj_id = :bj_id ", array(
					':weid' => $weid,
					':schoolid' => $schoolid,
					':bj_id' => $student['bj_id']
				));
				$bj1count = 0;
				foreach($xs1 as $k => $r){
					$xs1[$k]['sid'] = pdo_fetchall("SELECT userinfo,pard,id,uid,is_allowmsg,sid FROM " . tablename($this->table_user) . " WHERE weid = :weid AND schoolid = :schoolid AND sid = :sid ", array(
						':weid' => $weid,
						':schoolid' => $schoolid,
						':sid' => $r['id']
					));
					foreach($xs1[$k]['sid'] as $key =>$row){
						$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $weid, ':uid' => $row['uid']));
						$xs1[$k]['sid'][$key]['avatar'] = $member['avatar'];
						if ($row['userinfo']){
							$userinfo = iunserializer($row['userinfo']);
							$xs1[$k]['sid'][$key]['name'] = $userinfo['name'];
							$xs1[$k]['sid'][$key]['mobile'] = $userinfo['mobile'];
						}
					$bj1count ++;
					}	
				}
			}						
			include $this->template(''.$school['style2'].'/callbook');
        }else{
		    $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('tongxunlu', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
        }        
?>