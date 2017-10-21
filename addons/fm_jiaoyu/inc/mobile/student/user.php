<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
		//处理选择借用支付返回丢失缓存和weid的情况
		$weid = $_W['uniacid'];
		$openid = $_W['openid'];	
		$schoolid = intval($_GPC['schoolid']);
		$userss = intval($_GPC['userid']);
		$act = "wd";
		//mload()->model('store');
        //查询是否用户登录
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
		$user = pdo_fetchall("SELECT * FROM " . tablename($this->table_user) . " where :weid = weid And :openid = openid And :tid = tid", array(
				':weid' => $weid,
				':openid' => $openid,
				':tid' => 0
		));
		foreach($user as $key => $row){
			$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id=:id ", array(':id' => $row['sid']));
			$bajinam = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid=:sid ", array(':sid' => $student['bj_id']));
			$user[$key]['s_name'] = $student['s_name'];
			$user[$key]['bjname'] = $bajinam['sname'];
			$user[$key]['sid'] = $student['id'];  
			$user[$key]['schoolid'] = $student['schoolid'];
		}

		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And weid = :weid AND id=:id ", array(':schoolid' => $schoolid,':weid' => $weid, ':id' => $_SESSION['user']));

		$school = pdo_fetch("SELECT title,spic,is_rest,shoucename,is_video,videoname,is_zjh,is_recordmac,style2,userstyle,gonggao FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		
        if(!empty($it)){
			$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id AND schoolid=:schoolid ", array(':weid' => $weid, ':id' => $it['sid'], ':schoolid' => $schoolid));			
			$rest = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_order)." WHERE sid = '{$it['sid']}' And status = '1' ");
			$resttz = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_record)." WHERE sid = '{$it['sid']}' And type = 1 And readtime = 0 And userid = '{$it['id']}' ");
			$restzy = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_record)." WHERE sid = '{$it['sid']}' And type = 2 And readtime = 0 And userid = '{$it['id']}' ");
			$restly = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_leave)." WHERE touserid = '{$it['id']}' And isliuyan = 2 And isread = 1 ");
			$mybanji = pdo_fetch("SELECT sname,qun FROM " . tablename($this->table_classify) . " WHERE :schoolid = schoolid And :sid = sid ", array(':schoolid' => $schoolid, ':sid' => $students['bj_id']));			
			$icons1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = 3 And status = 1 ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid));
			foreach($icons1 as $key => $row){
				$icons1[$key]['ismassges'] = false;
				if(strpos($row['url'],'szuoyelist')){
					$icons1[$key]['ismassges'] = true;
					$icons1[$key]['shengyu'] = $restzy;
				}
				if(strpos($row['url'],'snoticelist')){
					$icons1[$key]['ismassges'] = true;
					$icons1[$key]['shengyu'] = $resttz;
				}
				if(strpos($row['url'],'slylist')){
					$icons1[$key]['ismassges'] = true;
					$icons1[$key]['shengyu'] = $restly;
				}				
			}
			$icons2 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = 4 And status = 1 ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid));
			$icons3 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = 5 And status = 1 ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid));            

			$item = pdo_fetch("SELECT nickname,realname FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid AND uid=:uid ", array(':uid' => $it['uid'], ':uniacid' => $weid)); 

		    $userinfo = iunserializer($it['userinfo']);
		    $this->checkpay($schoolid, $students['id'], $it['id'], $it['uid']);
			include $this->template(''.$school['style2'].'/'.$school['userstyle'].'');
        }else{
			session_destroy();
		    $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('myschool', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
        }
