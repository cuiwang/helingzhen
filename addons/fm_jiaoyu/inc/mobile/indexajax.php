<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','useredit','jiaoliu','bdxs','bdls','unboundls','qingjia','agree','defeid','sagree','sdefeid','savemsg','xsqingjia','savesmsg','getbjlist','signup','txshbm','xgxsinfo','tgsq','jjsq', 'bangdingcardjl', 'jbidcard', 'changeimg', 'changePimg', 'changeimgt','showchecklog','liaotian','jzjb','getkcbiao','bangdingcardjforteacher') ) ? $_GPC ['op'] : 'default';

     if ($operation == 'default') {
	           die ( json_encode ( array (
			         'result' => false,
			         'msg' => '你是傻逼吗！'
	                ) ) );
     }			
     if ($operation == 'useredit') {
	     $data = explode ( '|', $_GPC ['json'] );
	      
            if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
	       	       
		   $user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE id = :id ', array (':id' => $_GPC ['userid']));
	       	      
           if (empty($user)) {
		        die ( json_encode ( array (
				  'result' => false,
				  'msg' => '非法请求！' 
		              ) ) );
	        } else {
		        		         
				if ($user ['status'] == 1) {
		     	     
					$data ['result'] = false; // 
					 
			        $data ['msg'] = '抱歉您的帐号被锁定，请联系校方！';
		         
				} else {
					
					$info = array ('name' => $_GPC ['name'],'mobile' => $_GPC ['mobile']);
			        
					$temp['is_allowmsg'] = trim($_GPC ['is_allowmsg']);			
                    $temp['userinfo'] = iserializer($info);					
					
			        pdo_update ( $this->table_user, $temp, array ('id' => $user ['id']) );
				 							
			        $data ['result'] = true;
			
			        $data ['msg'] = '修改成功！';
		        }
		      die ( json_encode ( $data ) );
	        }
    }
     if ($operation == 'liaotian') {
	     $data = explode ( '|', $_GPC ['json'] );
	      
            if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
	       	       
		   $user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE id = :id ', array (':id' => $_GPC ['userid']));
	       	      
           if (empty($user)) {
		        die ( json_encode ( array (
				  'result' => false,
				  'msg' => '非法请求！' 
		              ) ) );
	        } else {
		        		         
				if ($user ['status'] == 1) {
		     	     
					$data ['result'] = false; // 
					 
			        $data ['msg'] = '抱歉您的帐号被锁定，请联系校方！';
		         
				} else {
			        
					$temp['is_allowmsg'] = trim($_GPC ['is_allowmsg']);							
					
			        pdo_update ( $this->table_user, $temp, array ('id' => $user ['id']) );
				 							
			        $data ['result'] = true;
			
			        $data ['msg'] = '修改成功！';
		        }
		      die ( json_encode ( $data ) );
	        }
    }
	if ($operation == 'jzjb') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :id = id", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':id'=>$_GPC ['sid']
				  ));

		if (empty($user['id'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求，没找你的学生信息！' 
		               ) ) );
		}				  
				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			if($_GPC['pard'] == 2){
				$temp = array( 
				    'mom' => 0,
					'muserid' => 0,
					'muid'=> 0
				    );
			}
			if($_GPC['pard'] == 3){
				$temp = array(
				    'dad' => 0,
					'duserid' => 0,
					'duid'=> 0
				    );
			}
			if($_GPC['pard'] == 4){
				$temp = array(
				    'own' => 0,
					'ouserid' => 0,
					'ouid'=> 0
				    );
			}
			if($_GPC['pard'] == 5){
				$temp = array(
				    'other' => 0,
					'otheruserid' => 0,
					'otheruid'=> 0
				    );
			}
           pdo_update($this->table_students, $temp, array('id' => $_GPC['sid']));			   
           pdo_delete($this->table_user, array('id' => $_GPC['userid']));	
			
			$data ['result'] = true;
			
			$data ['msg'] = '解绑成功！';

		 die ( json_encode ( $data ) );
		}
    }	
	if ($operation == 'bdxs') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_W ['openid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		$subjectId = trim($_GPC['subjectId']);

		$sid = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :s_name = s_name And :mobile = mobile", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':s_name'=>$_GPC ['s_name'],
				 ':mobile'=>$_GPC ['mobile']
				  ));
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And weid = :weid AND sid=:sid And uid =:uid ", array(
		         ':weid' => $_GPC ['weid'],
                 ':schoolid' => $_GPC ['schoolid'],				 
		         ':sid' => $sid['id'],
				 ':uid' => $_GPC['uid'],
	           	  ));				  
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And weid = :weid AND id=:id ", array(
		         ':weid' => $_GPC ['weid'],
                 ':schoolid' => $_GPC ['schoolid'],				 
		         ':id' => $sid['id']
	           	  ));
		if(!empty($user)){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '您已绑定本学生,不可重复绑定！' 
		          ) ) );
		}				  
		if(empty($sid['id'])){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '没有找到该生信息！' 
		          ) ) );
		}
		if($subjectId == 2){	
			if (!empty($item['mom'])){
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生母亲已经绑定了其他微信号！' 
		          ) ) );
			}	  
        }
		if($subjectId == 3){
			if (!empty($item['dad'])){
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生父亲已经绑定了其他微信号！' 
		          ) ) );
			}
        }
		if($subjectId == 4){
			if (!empty($item['own'])){
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生本人已经绑定了其他微信号！' 
		          ) ) );
			}
        }
		if($subjectId == 5){
			if (!empty($item['other'])){
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生家长已经绑定了其他微信号！' 
		          ) ) );
			}
        }		
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
		   pdo_insert($this->table_user, array (
					'sid' => trim($sid['id']),
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_W ['openid'],
					'pard' => $subjectId,
					'uid' => $_GPC['uid']
			));			
			$userid = pdo_insertid();
			if($subjectId == 2){
				$temp = array( 
				    'mom' => $_GPC['openid'],
					'muserid' => $userid,
					'muid'=> $_GPC['uid']
				    );
			}
			if($subjectId == 3){
				$temp = array(
				    'dad' => $_GPC['openid'],
					'duserid' => $userid,
					'duid'=> $_GPC['uid']
				    );
			}
			if($subjectId == 4){
				$temp = array(
				    'own' => $_GPC['openid'],
					'ouserid' => $userid,
					'ouid'=> $_GPC['uid']
				    );
			}
			if($subjectId == 5){
				$temp = array(
				    'other' => $_GPC['openid'],
					'otheruserid' => $userid,
					'otheruid'=> $_GPC['uid']
				    );
			}			

            pdo_update($this->table_students, $temp, array('id' => $sid['id']));
            		   			
			$data ['result'] = true;
			
			$data ['msg'] = '绑定成功！';

		 die ( json_encode ( $data ) );
		}
    }
	
	if ($operation == 'bdls') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_W ['openid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$tid = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :tname = tname And :code = code", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':tname'=>$_GPC ['tname'],
				 ':code'=>$_GPC ['code']
				  ), 'id');
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id ORDER BY id DESC", array(
		         ':weid' => $_GPC ['weid'], 
		         ':id' => $tid['id']
	           	  ));

		$user = pdo_fetch("SELECT id FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':openid'=>$_GPC ['openid']
				  ));

		if ($user['id']) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉,你已经绑定了其他教师信息！' 
		               ) ) );
		}				  
				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}
		
		if(empty($tid['id'])){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '姓名或绑定码输入有误！' 
		          ) ) );
		}
		if(!empty($item['openid'])){
		   
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此教师已经绑定了其他微信号！' 
		          ) ) );
		    
        }else{
  	         		   
		    pdo_insert($this->table_user, array (
					'tid' => trim($tid['id']),
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_W ['openid'],
					'uid' => $_GPC['uid']
			));
			$userid = pdo_insertid();
			$temp = array('openid' => $_GPC ['openid'], 'uid' => $_GPC['uid'], 'userid' => $userid);
		    pdo_update($this->table_teachers, $temp, array('id' => $tid['id']));
			
			$data ['result'] = true;
			
			$data ['msg'] = '绑定成功！';

		 die ( json_encode ( $data ) );
		}
    }	

	if ($operation == 'unboundls') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':openid'=>$_GPC ['openid']
				  ), 'id');

		if (empty($user['id'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求，没找你的老师信息！' 
		               ) ) );
		}				  
				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			
			$temp = array(
			        'openid' => '',
		           	'uid'    => 0
			       );
           pdo_update($this->table_teachers, $temp, array('id' => $_GPC['tid']));			   
           pdo_delete($this->table_user, array('id' => $_GPC['user']));	
			
			$data ['result'] = true;
			
			$data ['msg'] = '解绑成功！';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'qingjia') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':openid'=>$_GPC ['openid']
				  ), 'id');
				  
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :tid = tid ORDER BY id DESC LIMIT 1", array(
		         ':weid' => $_GPC['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':tid' => $_GPC ['tid']
				 )); 
				 
		if ((time() - $leave['createtime']) <  200) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您请假太频繁了，请待会再试！' 
		               ) ) );
		}		 
		 
		if (empty($user['id'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求，没找你的老师信息！' 
		               ) ) );
		}				  
				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$teacher = pdo_fetch("SELECT openid FROM " . tablename($this->table_teachers) . " where id = :id AND schoolid = :schoolid ", array(':id' => $_GPC['totid'], ':schoolid' => $schoolid));
			
			$toopenid = $teacher['openid'];
			
			$data = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_GPC ['openid'],
					'tid' => $_GPC ['tid'],
					'type' => $_GPC ['type'],
					'startime1' => strtotime($_GPC ['startTime']),
					'endtime1' => strtotime($_GPC ['endTime']),
					'conet' => $_GPC ['content'],
					'uid' => $_GPC['uid'],
					'createtime' => time(),
			);
				
			pdo_insert($this->table_leave, $data);
   
			$leave_id = pdo_insertid();
			
			if ($setting['istplnotice'] == 1 && $setting['jsqingjia']) {
				
				$this->sendMobileJsqj($leave_id, $schoolid, $weid, $toopenid);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			
			$data ['result'] = true;
			
			$data ['msg'] = '申请成功，请勿重复申请！';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'agree') {
		$data = explode ( '|', $_GPC ['json'] );
			
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$leaveid = $_GPC['id'];
			
			$shname = trim($_GPC['shname']);
			
			$data = array(
					'cltid' =>  $_GPC['tid'],
					'reconet' =>  trim($_GPC['reconet']),
			        'cltime' =>  time(),
					'status' =>  1,
			);
				
            pdo_update($this->table_leave, $data, array('id' => $leaveid));	

			if ($setting['istplnotice'] == 1 && $setting['jsqjsh']) {
				
				$this->sendMobileJsqjsh($leaveid, $schoolid, $weid, $shname);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}			
						
			$data ['result'] = true;
			
			$data ['msg'] = '审核成功！';
			
		 die ( json_encode ( $data ) );
		
    }

	if ($operation == 'defeid') {
		$data = explode ( '|', $_GPC ['json'] );
			
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$leaveid = $_GPC['id'];
			
			$shname = trim($_GPC['shname']);
			
			$data = array(
				'reconet' =>  trim($_GPC['reconet']),
				'cltid' =>  $_GPC['tid'],
				'cltime' =>  time(),
				'status' =>  2,
			);
				
            pdo_update($this->table_leave, $data, array('id' => $leaveid));	

			if ($setting['istplnotice'] == 1 && $setting['jsqjsh']) {
				
				$this->sendMobileJsqjsh($leaveid, $schoolid, $weid, $shname);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			$data ['result'] = true;
			$data ['msg'] = '审核成功！';
		 die ( json_encode ( $data ) );
    }

	if ($operation == 'sagree') {
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));		
		$schoolid = $_GPC['schoolid'];		
		$weid = $_GPC['weid'];
		$leaveid = $_GPC['id'];
		$tname = $_GPC['tname'];
		if($_GPC['agreetype'] == 'agree'){
			$status = 1;
		}else{
			$status = 2;
		}
		$data = array(
			'reconet' =>  $_GPC['content'],
			'cltid' =>  $_GPC['tid'],
			'cltime' =>  time(),
			'status' =>  $status
		);
		pdo_update($this->table_leave, $data, array('id' => $leaveid));	

		if ($setting['istplnotice'] == 1 && $setting['xsqjsh']) {
			
			$this->sendMobileXsqjsh($leaveid, $schoolid, $weid, $tname);
			
		}else{
			  die ( json_encode ( array (
			  'result' => false,
			  'msg' => '发送失败，请联系管理员开启模版消息！' 
				   ) ) );
		}					
		$reulset ['status'] = 1;
		$reulset ['info'] = '审核成功,已通知请假人！';	
	 die ( json_encode ( $reulset ) );
    }

	if ($operation == 'sdefeid') {
		$data = explode ( '|', $_GPC ['json'] );
			
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$leaveid = $_GPC['id'];
			
			$tname = $_GPC['tname'];
			
			$data = array(
				'reconet' =>  $_GPC['reconet'],
				'cltid' =>  $_GPC['tid'],			
				'cltime' =>  time(),
				'status' =>  2,
			);
				
            pdo_update($this->table_leave, $data, array('id' => $leaveid));	

			if ($setting['istplnotice'] == 1 && $setting['xsqjsh']) {
				
				$this->sendMobileXsqjsh($leaveid, $schoolid, $weid, $tname);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}			
						
			$data ['result'] = true;
			
			$data ['msg'] = '审核成功！';
			
		 die ( json_encode ( $data ) );
		
    }	

	if ($operation == 'savemsg') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
						  
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :sid = sid  And :openid = openid And :isliuyan = isliuyan And :uid = uid And :bj_id = bj_id ORDER BY createtime ASC LIMIT 1", array(
		         ':weid' => $_GPC['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':openid' => $_GPC ['openid'],
				 ':bj_id' => $_GPC ['bj_id'],
				 ':uid' => $_GPC ['uid'],
				 ':isliuyan' => 1,
				 ':sid' => $_GPC ['sid']
				 )); 
				 
		$time = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :sid = sid And :uid = uid And :bj_id = bj_id ORDER BY createtime DESC LIMIT 1", array(
		         ':weid' => $_GPC['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':bj_id' => $_GPC ['bj_id'],
				 ':uid' => $_GPC ['uid'],
				 ':sid' => $_GPC ['sid']
				 ));				 
		  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else if (!empty($leave['id'])) {
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$uid = $_GPC['uid'];
			
			$bj_id = $_GPC['bj_id'];
			
			$sid = $_GPC['sid'];
			
			$tid = $_GPC['tid'];
			
			$data = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_GPC ['openid'],
					'sid' => $_GPC ['sid'],
					'conet' => $_GPC ['content'],
					'bj_id' => $_GPC['bj_id'],
					'uid' => $_GPC['uid'],
					'leaveid'=>$leave['id'],
					'isliuyan'=>1,
					'createtime' => time(),
			);
				
			pdo_insert($this->table_leave, $data);
   
			$leave_id = pdo_insertid();
			
			if ($setting['istplnotice'] == 1 && $setting['liuyan']) {
				
				$this->sendMobileJzly($leave_id, $schoolid, $weid, $uid, $bj_id, $sid, $tid);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			
			$data ['result'] = true;
			
			$data ['msg'] = '成功发送留言信息，请勿重复发送！';	
			
          die ( json_encode ( $data ) );
		  
		}else{
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$uid = $_GPC['uid'];
			
			$bj_id = $_GPC['bj_id'];
			
			$sid = $_GPC['sid'];
			
			$tid = $_GPC['tid'];
			
			$data = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_GPC ['openid'],
					'sid' => $_GPC ['sid'],
					'conet' => $_GPC ['content'],
					'bj_id' => $_GPC['bj_id'],
					'uid' => $_GPC['uid'],
					'leaveid'=>$leave['id'],
					'isliuyan'=>1,
					'isfrist'=>1,
					'createtime' => time(),
			);
				
			pdo_insert($this->table_leave, $data);
   
			$leave_id = pdo_insertid();
			
			$data1 = array(
					'leaveid'=>$leave_id,
			);
			
			pdo_update($this->table_leave, $data1, array('id' => $leave_id));	
			
			if ($setting['istplnotice'] == 1 && $setting['liuyan']) {
				
				$this->sendMobileJzly($leave_id, $schoolid, $weid, $uid, $bj_id, $sid, $tid);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			
			$data ['result'] = true;
						
			$data ['msg'] = '成功发送留言信息，请勿重复发送！';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'xsqingjia') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
				  
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :sid = sid And :tid = tid And :isliuyan = isliuyan ORDER BY id DESC LIMIT 1", array(
		         ':weid' => $_GPC['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':tid' => 0,
				 ':isliuyan' => 0,
				 ':sid' => $_GPC ['sid']
				 )); 
				 
		if ((time() - $leave['createtime']) <  100) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您请假太频繁了，请待会再试！' 
		               ) ) );
		}		 
		 			  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$tid = $_GPC['tid'];
			
			$data = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_GPC ['openid'],
					'sid' => $_GPC ['sid'],
					'type' => $_GPC ['type'],
					'startime1' => strtotime($_GPC ['startTime']),
					'endtime1' => strtotime($_GPC ['endTime']),
					'conet' => $_GPC ['content'],
					'uid' => $_GPC['uid'],
					'bj_id' => $_GPC['bj_id'],
					'createtime' => time(),
			);
				
			pdo_insert($this->table_leave, $data);
   
			$leave_id = pdo_insertid();
			
			if ($setting['istplnotice'] == 1 && $setting['xsqingjia']) {
				
				$this->sendMobileXsqj($leave_id, $schoolid, $weid, $tid);
				
			}
			
			$data ['result'] = true;
			
			$data ['msg'] = '申请成功，请勿重复申请！';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'savesmsg') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
						  
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		 		 			  				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$schoolid = $_GPC['schoolid'];
			
			$topenid = $_GPC['topenid'];
			
			$weid = $_GPC['weid'];
			
			$uid = $_GPC['uid'];
			
			$tuid = $_GPC['tuid'];
			
			$bj_id = $_GPC['bj_id'];
			
			$sid = $_GPC['sid'];
			
			$tid = $_GPC['tid'];
			
			$itemid = $_GPC['itemid'];
			
			$tname = $_GPC['tname'];
			
			$leaveid = $_GPC['leaveid'];
			
			$data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'openid' => $topenid,
					'sid' => $_GPC ['sid'],
					'conet' => $_GPC ['content'],
					'bj_id' => $bj_id,
					'uid' => $uid,
					'teacherid' => $tid,
					'tuid' => $tuid,
					'leaveid'=>$leaveid,
					'isliuyan'=>1,
					'createtime' => time(),
					'status' =>  2,
			);
			
			$data1 = array(
			        'cltime' =>  time(),
					'status' =>  2,
			);			
				
			pdo_insert($this->table_leave, $data);
			
			$leave_id = pdo_insertid();
			
			pdo_update($this->table_leave, $data1, array('id' => $itemid));	
   
			if ($setting['istplnotice'] == 1 && $setting['liuyanhf']) {
				
				$this->sendMobileJzlyhf($leave_id, $schoolid, $weid, $topenid, $sid, $tname);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			
			$data ['result'] = true;
			
			$data ['msg'] = '成功发送留言信息，请勿重复发送！';	
			
          die ( json_encode ( $data ) );
		  
		}
    }
	if ($operation == 'getbjlist')  {
		if (! $_GPC ['schoolid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$data = array();
			$bjlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where schoolid = '{$_GPC['schoolid']}' And weid = '{$_W['uniacid']}' And parentid = '{$_GPC['gradeId']}' And type = 'theclass' ORDER BY ssort ASC");
   			$data ['bjlist'] = $bjlist;
			$data ['result'] = true;
			$data ['msg'] = '成功获取！';
			
          die ( json_encode ( $data ) );
		  
		}
    }
	if ($operation == 'signup')  {
		if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		$check1 = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :weid = weid And :schoolid = schoolid And :s_name = s_name And :mobile = mobile And :xq_id = xq_id ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':s_name' => trim($_GPC['s_name']),
				':xq_id' => $_GPC['njid'],
				':mobile' => $_GPC['mobile']
				)); 
		if (!empty($check1)){
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该生已录入学校,无需重复报名！' 
		               ) ) );			
		}		
		$check2 = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " WHERE :weid = weid And :schoolid = schoolid And :name = name And :mobile = mobile And :sex = sex And :nj_id = nj_id ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':name' => trim($_GPC['s_name']),
				':sex' => $_GPC['sex'],
				':nj_id' => $_GPC['njid'],
				':mobile' => $_GPC['mobile']
				));
		if (!empty($check2)){
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该生已通过微信端报名,请勿重复报名！' 
		               ) ) );			
		}	
		if (empty($_GPC ['openid']))  {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{ 	
			
			$iscost = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE :sid = sid ", array(':sid' => $_GPC['bjid']));
			
			$njinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE :sid = sid ", array(':sid' => $_GPC['njid']));
			
			$njzr = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE :id = id ", array(':id' => $njinfo['tid']));
			
			$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			$school = pdo_fetch("SELECT signset FROM " . tablename($this->table_index) . " WHERE :id = id ", array(':id' => $_GPC['schoolid']));
			$sign = unserialize($school['signset']);
			$temp = array(
				'weid' => $_GPC['weid'],
				'schoolid' => $_GPC['schoolid'],
				'name' => trim($_GPC['s_name']),
				'sex' => $_GPC['sex'],
				'mobile' => $_GPC['mobile'],
				'nj_id' => $_GPC['njid'],
				'bj_id' => $_GPC['bjid'],
				'idcard' => $_GPC['idcard'],
				'numberid' => trim($_GPC['numberid']),
				'birthday' => strtotime($_GPC['birthday']),
				'uid' => $_GPC['uid'],
				'openid' => $_GPC['openid'],
				'createtime' => time(),
				'cost' => $iscost['cost'],
				'pard' => $_GPC['pard'],
				'status' => 1,
			);
				
			pdo_insert($this->table_signup, $temp);
			$signup_id = pdo_insertid();
			if (!empty($iscost['cost'])){
				$temp1 = array(
								'weid' =>  $_GPC['weid'],
								'schoolid' => $_GPC['schoolid'],
								'type' => 4,
								'status' => 1,
								'uid' => $_GPC['uid'],
								'cose' => $iscost['cost'],
								'orderid' => time(),
								'signid' => $signup_id,
								'payweid' => $sign['payweid'],
								'createtime' => time(),
							);
				pdo_insert($this->table_order, $temp1);
				$order_id = pdo_insertid();
				pdo_update($this->table_signup, array('orderid' => $order_id), array('id' =>$signup_id));
			}
			
			if ($setting['istplnotice'] == 1 && $setting['bjqshtz']) {				
				$this->sendMobileBmshtz($signup_id, $_GPC['schoolid'], $_GPC['weid'], $njzr['openid'], $_GPC['s_name']);		
			}			
			
			$data ['result'] = true;
			$data ['msg'] = '提交成功！';
			
          die ( json_encode ( $data ) );
		  
		}
    }
	if ($operation == 'txshbm')  {
		if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		$signup_id = $_GPC['id'];
		$check1 = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :weid = weid And :schoolid = schoolid And :s_name = s_name And :mobile = mobile ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':s_name' => trim($_GPC['s_name']),
				':mobile' => $_GPC['mobile']
				));
		$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where signid = :signid ", array(':signid' => $signup_id));
		$iscost = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE :sid = sid ", array(':sid' => $_GPC['bjid']));
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " where :id = id", array(':id' => $signup_id));
		$nowtime = time();
		$lasttime = $nowtime - $item['lasttime'];
		
		if ($lasttime <= 180){
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉,您提醒的频率过高,请稍后再试!' 
		               ) ) );			
		}
		if (!empty($check1)){
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该生已录入学校系统,无需重复报名!' 
		               ) ) );			
		}
		if (!empty($iscost['cost'])){
			if ($order['status'] == 1){
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉!您尚未付费,请您先支付报名费!' 
		               ) ) );				
			}	
		}		
		if (empty($_GPC ['openid']))  {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE :id = id ", array(':id' => $_GPC['schoolid']));
						
			$njinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE :sid = sid ", array(':sid' => $_GPC['njid']));
			
			$njzr = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE :id = id ", array(':id' => $njinfo['tid']));
			
			$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			pdo_update($this->table_signup, array('lasttime' => time()), array('id' => $signup_id));
			
				
			if ($setting['istplnotice'] == 1 && $setting['bjqshtz']) {						
				$this->sendMobileBmshtz($signup_id, $_GPC['schoolid'], $_GPC['weid'], $njzr['openid'], $_GPC['s_name']);		
			}			
				
			$data ['result'] = true;
			$data ['msg'] = '提醒成功,请勿频繁操作！';
			
          die ( json_encode ( $data ) );
		  
		}
    }
	
	if ($operation == 'xgxsinfo')  {
		if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		$check = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :weid = weid And :schoolid = schoolid And :s_name = s_name And :mobile = mobile And :xq_id = xq_id ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':s_name' => trim($_GPC['s_name']),
				':xq_id' => $_GPC['njid'],
				':mobile' => $_GPC['mobile']
				)); 
		if (!empty($check)){
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该生已录入学校,无需重复审核！' 
		               ) ) );			
		}			
		if (empty($_GPC ['openid']))  {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{ 	
			
			$iscost = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE :sid = sid ", array(':sid' => $_GPC['bjid']));
			
			$njinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE :sid = sid ", array(':sid' => $_GPC['njid']));
			
			$njzr = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE :id = id ", array(':id' => $njinfo['tid']));
						
			$item = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " WHERE :id = id", array(':id' => $_GPC['id'])); 
			
			$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE :id = id", array(':id' => $item['orderid']));
			
			$temp = array(
				'weid' => $_GPC['weid'],
				'schoolid' => $_GPC['schoolid'],
				'name' => trim($_GPC['s_name']),
				'numberid' => $_GPC['numberid'],
				'sex' => $_GPC['sex'],
				'mobile' => $_GPC['mobile'],
				'nj_id' => $_GPC['njid'],
				'bj_id' => $_GPC['bjid'],
				'idcard' => $_GPC['idcard'],
				'pard' => $_GPC['pard'],
				'birthday' => strtotime($_GPC['birthday']),
				'cost' => $iscost['cost'],
			);
				
			pdo_update($this->table_signup, $temp, array('id' => $_GPC['id']));

			if (!empty($iscost['cost'])){
				if (empty($order)) {
					$temp1 = array(
									'weid' =>  $_GPC['weid'],
									'schoolid' => $_GPC['schoolid'],
									'type' => 4,
									'status' => 1,
									'uid' => $item['uid'],
									'cose' => $iscost['cost'],
									'orderid' => time(),
									'signid' => $_GPC['id'],
									'createtime' => time(),
								);
					pdo_insert($this->table_order, $temp1);
					$order_id = pdo_insertid();
					pdo_update($this->table_signup, array('orderid' => $order_id), array('id' =>$_GPC['id']));
					$this->sendMobileBmshjg($_GPC['id'], $_GPC['schoolid'], $_GPC['weid'], $item['openid'], $_GPC['s_name']);
				}else{
					$this->sendMobileBmshjg($_GPC['id'], $_GPC['schoolid'], $_GPC['weid'], $item['openid'], $_GPC['s_name']);
				}
			}else{
				$this->sendMobileBmshjg($_GPC['id'], $_GPC['schoolid'], $_GPC['weid'], $item['openid'], $_GPC['s_name']);
			}	
			
			$data ['result'] = true;
			$data ['msg'] = '信息修改成功！';
			
          die ( json_encode ( $data ) ); 
		}
    }

	if ($operation == 'tgsq')  {
		if (empty($_GPC ['openid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$item = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " WHERE :id = id", array(':id' => $_GPC['id']));
			$school = pdo_fetch("SELECT signset FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $item['schoolid']));
			$temp = array(
				'weid' => $item['weid'],
				'schoolid' => $item['schoolid'],
				's_name' => $item['name'],
				'sex' => $item['sex'],
				'numberid' => $item['numberid'],
				'mobile' => $item['mobile'],
				'xq_id' => $item['nj_id'],
				'bj_id' => $item['bj_id'],
				'note' => $item['idcard'],
				'birthdate' => $item['birthday'],
				'seffectivetime' => time(),
				'createdate' => time()
			);			
			
		    pdo_insert($this->table_students, $temp);
		   
		    $studentid = pdo_insertid();
			$singset = iunserializer($school['signset']);
			if($singset['is_bd'] ==1){
				if(!empty($item['pard'])){
					if($item['pard'] == 2){
						$data = array( 
							'numberid' => $item['numberid'],
							'mom' => $item['openid'],
							'muid'=> $item['uid']
						);
						$info = array ('name' => '','mobile' => $item ['mobile']);
					}
					if($item['pard'] == 3){
						$data = array(
							'numberid' => $item['numberid'],
							'dad' => $item['openid'],
							'duid'=> $item['uid']
						);
						$info = array ('name' => '','mobile' => $item ['mobile']);
					}
					if($item['pard'] == 4){
						$data = array(
							'numberid' => $item['numberid'],
							'own' => $item['openid'],
							'ouid'=> $item['uid']
						);
						$info = array ('name' => $item ['name'],'mobile' => $item ['mobile']);
					}
					pdo_update($this->table_students, $data, array('id' => $studentid));
					$temp2 = array(
						'sid' => $studentid,
						'weid' =>  $item ['weid'],
						'schoolid' => $item ['schoolid'],
						'openid' => $item ['openid'],
						'pard' => $item['pard'],
						'uid' => $item['uid']				
					);	
					$temp2['userinfo'] = iserializer($info);	
					pdo_insert($this->table_user, $temp2);			   
				}
			}

			$temp1 = array(
				'status' => 2,
				'passtime' => time()
			);
			
			pdo_update($this->table_signup, $temp1, array('id' => $_GPC['id']));			
			$this->sendMobileBmshjgtz($_GPC['id'], $item['schoolid'], $item['weid'], $item['openid'], $item['name']);
			$data ['result'] = true;
			$data ['msg'] = '审核成功,已录入学生系统！';
			
          die ( json_encode ( $data ) );
		  
		}
    }

	if ($operation == 'jjsq')  {
		if (empty($_GPC ['openid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$item = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " WHERE :id = id", array(':id' => $_GPC['id']));
						
			$temp = array(
				'status' => 3,
				'passtime' => time()
			);			
			
			pdo_update($this->table_signup, $temp, array('id' => $_GPC['id']));		
			$this->sendMobileBmshjgtz($_GPC['id'], $item['schoolid'], $item['weid'], $item['openid'], $item['name']);
			$data ['result'] = true;
			$data ['msg'] = '已拒绝该生申请,您还可以执行通过操作！';
			
          die ( json_encode ( $data ) );
		  
		}
    }
	if ($operation == 'bangdingcardjforteacher')  {
		if (empty($_GPC ['openid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }		
		$checkcard = pdo_fetch("SELECT pard,id FROM " . tablename($this->table_idcard) . " WHERE :weid = weid And :schoolid = schoolid And :idcard = idcard", array(
			':weid' => $_GPC['weid'],
			':schoolid' => $_GPC['schoolid'],
			':idcard' => $_GPC['idcard']
		));
		

		$school = pdo_fetch("SELECT is_cardlist FROM " . tablename($this->table_index) . " WHERE id = :id ", array(':id' => $_GPC['schoolid']));
		if ($school['is_cardlist'] ==1){
			if (empty($checkcard)) {
				   die ( json_encode ( array (
						'result' => false,
						'msg' => '抱歉,本校无此卡号！' 
						   ) ) );
			}
		}
		if (!empty($checkcard['pard'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '本卡已被绑定！' 
		               ) ) );
	    }else{
			$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE id = :id ", array(':id' => $_GPC['tid']));
			$temp = array(
				'weid' => $_GPC['weid'],
				'schoolid' => $_GPC['schoolid'],
				'idcard' => $_GPC['idcard'],
				'tid' => $_GPC['tid'],
				'pname' => $teacher['tname'],
				'pard' => 1,
				'usertype' => 1,
				'is_on' => 1,
				'createtime' => time(),
				'severend' => 4114507889,
			);
			if ($school['is_cardlist'] ==1){
				pdo_update($this->table_idcard, $temp, array('id' =>$checkcard['id']));
			}else{
				pdo_insert($this->table_idcard, $temp);
			}
			$data ['result'] = true;
			$data ['msg'] = '绑定成功！';
			
          die ( json_encode ( $data ) );
		  
		}
    }	
	if ($operation == 'bangdingcardjl')  {
		if (empty($_GPC ['openid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }		
		$checkcard = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE :weid = weid And :schoolid = schoolid And :idcard = idcard", array(
			':weid' => $_GPC['weid'],
			':schoolid' => $_GPC['schoolid'],
			':idcard' => $_GPC['idcard']
		));
		if (empty($_GPC['username'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '请输入持卡人姓名！' 
		               ) ) );
	    }		
		if (!empty($checkcard['pard'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '本卡已被绑定！' 
		               ) ) );
	    }
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE id = :id ", array(':id' => $_GPC['schoolid']));
		if ($school['is_cardlist'] ==1){
			if (empty($checkcard)) {
				   die ( json_encode ( array (
						'result' => false,
						'msg' => '抱歉,本校无此卡号！' 
						   ) ) );
			}
		}
		$pard = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE :weid = weid And :schoolid = schoolid And :idcard = idcard And :sid = sid And :pard = pard", array(
			':weid' => $_GPC['weid'],
			':schoolid' => $_GPC['schoolid'],
			':idcard' => $_GPC['idcard'],
			':sid' => $_GPC['sid'],
			':pard' => $_GPC['pard']
		));		
		if (!empty($pard)) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '你选择的关系已经绑定其他卡！' 
		               ) ) );
	    }else{
			if($school['is_cardpay'] == 1){
				$card = unserialize($school['cardset']);
					if($card['cardtime'] == 1){
						if($checkcard['is_frist'] ==1){
							$severend = $card['endtime1'] * 86400 + time();
						}else{
							$severend = time();
						}
					}else{
						$severend = $card['endtime2'];
					}
					$temp = array(
						'weid' => $_GPC['weid'],
						'schoolid' => $_GPC['schoolid'],
						'idcard' => $_GPC['idcard'],
						'sid' => $_GPC['sid'],
						'bj_id' => $_GPC['bj_id'],
						'pname' => $_GPC['username'],
						'pard' => $_GPC['pard'],
						'usertype' => 0,
						'is_on' => 1,
						'createtime' => time(),
						'severend' => $severend,
					);
					if ($school['is_cardlist'] ==1){
					    pdo_update($this->table_idcard, $temp, array('id' =>$checkcard['id']));
					}else{
						pdo_insert($this->table_idcard, $temp);
					}			
			}else{
				$temp2 = array(
					'weid' => $_GPC['weid'],
					'schoolid' => $_GPC['schoolid'],
					'idcard' => $_GPC['idcard'],
					'sid' => $_GPC['sid'],
					'bj_id' => $_GPC['bj_id'],
					'pard' => $_GPC['pard'],
					'pname' => $_GPC['username'],
					'usertype' => 0,
					'is_on' => 1,
					'createtime' => time()
				);
				if ($school['is_cardlist'] ==1){
					pdo_update($this->table_idcard, $temp2, array('id' =>$checkcard['id']));
				}else{
					pdo_insert($this->table_idcard, $temp2);
				}
			}		
			
			$data ['result'] = true;
			$data ['msg'] = '绑定成功！';
			
          die ( json_encode ( $data ) );
		  
		}
    }	
	if ($operation == 'jbidcard')  {
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE :id = id", array(':id' => $_GPC['id']));
		if (empty($item)) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '无此卡！' 
		               ) ) );
	    }else{
			$temp = array(
			        'sid' => 0,
		           	'tid' => 0,
					'pard'=> 0,
					'bj_id'=> 0,
					'is_on'=> 0,
					'usertype'=> 3,
					//'createtime'=> '',
					'pname'=> '',
					//'severend'=> '',
					'spic'=> '',
					'tpic'=> '',
			       );
			pdo_update($this->table_idcard, $temp, array('id' => $_GPC['id']));						
			$data ['result'] = true;
			$data ['msg'] = '解绑成功！';
			
          die ( json_encode ( $data ) );
		  
		}
    }
	
	if ($operation == 'changeimg') {
		load()->func('communication');
		load()->classs('weixin.account');
		load()->func('file');
		$accObj= WeixinAccount::create($_W['account']['acid']);
		$access_token = $accObj->fetch_token();
		$token2 =  $access_token;
		$photoUrl = $_GPC ['bigImage'];
		$data = explode ( '|', $_GPC ['json'] );
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :id = id", array(':id' => $_GPC['sid']));

		
		if (empty($student)) {
			die ( json_encode ( array (
				'result' => false,
				'msg' => '没找到本学生！' 
			) ) );
		}else{
			
			if(!empty($photoUrl)) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrl;
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl = $path.random(30) .".jpg";
				file_write($picurl,$pic_data['content']);
				if (!empty($_W['setting']['remote']['type'])) { // 
					$remotestatus = file_remote_upload($picurl); //
					if (is_error($remotestatus)) {
						message('远程附件上传失败，请检查配置并重新上传');					
					}
				}
			}
				
			pdo_update($this->table_students, array('icon' => $picurl), array('id' => $student['id']));	
			$data ['result'] = true;
			$data ['msg'] = '修改头像成功';

			die ( json_encode ( $data ) );

		}
    }

	if ($operation == 'changeimgt') {
		load()->func('communication');
		load()->classs('weixin.account');
		load()->func('file');
		$accObj= WeixinAccount::create($_W['account']['acid']);
		$access_token = $accObj->fetch_token();
		$token2 =  $access_token;
		$photoUrl = $_GPC ['bigImage'];
		$data = explode ( '|', $_GPC ['json'] );
		$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE :id = id", array(':id' => $_GPC['tid']));

		
		if (empty($teacher)) {
			die ( json_encode ( array (
				'result' => false,
				'msg' => '没找到该教师信息！' 
			) ) );
		}else{			
			if(!empty($photoUrl)) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrl;
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl = $path.random(30) .".jpg";
				file_write($picurl,$pic_data['content']);
				if (!empty($_W['setting']['remote']['type'])) { // 
					$remotestatus = file_remote_upload($picurl); //
					if (is_error($remotestatus)) {
						message('远程附件上传失败，请检查配置并重新上传');					
					}
				}
			}			
			pdo_update($this->table_teachers, array('thumb' => $picurl), array('id' => $_GPC['tid']));	
			$data ['result'] = true;
			$data ['msg'] = '修改头像成功';

			die ( json_encode ( $data ) );

		}
    }
	
	if ($operation == 'changePimg') {
		load()->func('communication');
		load()->classs('weixin.account');
		load()->func('file');
		$accObj= WeixinAccount::create($_W['account']['acid']);
		$access_token = $accObj->fetch_token();
		$token2 =  $access_token;
		$photoUrl = $_GPC ['bigImage'];
		$data = explode ( '|', $_GPC ['json'] );
		$user = pdo_fetch("SELECT id FROM " . tablename($this->table_idcard) . " WHERE :id = id", array(':id' => $_GPC['id']));

		
		if (empty($user['id'])) {
			die ( json_encode ( array (
				'result' => false,
				'msg' => '没找到本学生！' 
			) ) );
		}else{
			
			if(!empty($photoUrl)) {	
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrl;
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl = $path.random(30) .".jpg";
				file_write($picurl,$pic_data['content']);
				if (!empty($_W['setting']['remote']['type'])) { // 
					$remotestatus = file_remote_upload($picurl); //
					if (is_error($remotestatus)) {
						message('远程附件上传失败，请检查配置并重新上传');					
					}
				}
			}
				
			pdo_update($this->table_idcard, array('spic' => $picurl), array('id' => $user['id']));	
			$data ['result'] = true;
			$data ['msg'] = '修改头像成功';

			die ( json_encode ( $data ) );

		}
    }
	
	if ($operation == 'showchecklog') {
		
		$data = explode ( '|', $_GPC ['json'] );
		
		$log = pdo_fetch("SELECT * FROM " . tablename($this->table_checklog) . " WHERE :id = id", array(':id' => $_GPC['id']));
		
		$mac = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE schoolid = '{$log['schoolid']}' And id = '{$log['macid']}' ");

		// if($mac['macname'] == 3)	{
			// if (preg_match('/(http:\/\/)|(https:\/\/)/i', $log['pic'])) {
				// if(!empty($log['pic'])) {
					// load()->func('file');
					// load()->func('communication');
					// $pic_data = file_get_contents($log['pic']);
					// $path = "images/";
					// $picurl = $path.random(30) .".jpg";
					// $pic_data = $this->getImg($log['pic'],$picurl);
					// file_write($picurl,$pic_data);
						// if (!empty($_W['setting']['remote']['type'])) { // 
							// $remotestatus = file_remote_upload($picurl); //
							// if (is_error($remotestatus)) {
								// message('远程附件上传失败，请检查配置并重新上传');
							// }
						// }
				// pdo_update($this->table_checklog, array('pic' => $picurl), array('id' => $_GPC['id']));			
				// }	
			// }
		// }
		if (empty($log)) {
			die ( json_encode ( array (
				'result' => false,
				'msg' => 'no data' 
			) ) );
		}else{
							
			$data ['result'] = true;
			$data ['ret']['code'] = 200;
			$data ['data'] = $log;
			//$data ['data']['picurl'] = $log['pic'];
			$data ['data']['macname'] = $mac['name'];
			$data ['data']['mactype'] = $mac['macname'];
			$data ['data']['msg'] = '获取记录成功';
			pdo_update($this->table_checklog, array('isread' => 2), array('id' => $_GPC['id']));	
			die ( json_encode ( $data ) );

		}
    }	
if ($operation == 'getkcbiao') {
	$date = date('Y-m-d',$_GPC['time']);
	$riqi = explode ('-', $date);
	$starttime = mktime(0,0,0,$riqi[1],$riqi[2],$riqi[0]);
	$endtime = $starttime + 86399;
	$condition = " AND begintime < '{$starttime}' AND endtime > '{$endtime}'";
	$cook = pdo_fetch("SELECT * FROM " . tablename($this->table_timetable) . " WHERE schoolid = :schoolid And bj_id = :bj_id And ishow = 1 $condition", array(':schoolid' => $_GPC['schoolid'],':bj_id' => $_GPC['bj_id']));
	if($cook['monday'] || $cook['tuesday'] || $cook['wednesday'] || $cook['thursday'] || $cook['friday'] || $cook['saturday'] || $cook['sunday']){
		$week = date("w",$endtime);
			if($week ==1){
				if($cook['monday']){
					$thecook = iunserializer($cook['monday']);
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_12_km']}'");
				}
			}
			if($week ==2){
				if($cook['tuesday']){
					$thecook = iunserializer($cook['tuesday']);
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_12_km']}'");
				}		
			}
			if($week ==3){
				if($cook['wednesday']){
					$thecook = iunserializer($cook['wednesday']);	
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_12_km']}'");
				}		
			}
			if($week ==4){
				if($cook['thursday']){
					$thecook = iunserializer($cook['thursday']);
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_12_km']}'");					
				}		
			}
			if($week ==5){
				if($cook['friday']){
					$thecook = iunserializer($cook['friday']);	
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_12_km']}'");
				}		
			}
			if($week ==6){
				if($cook['saturday']){
					$thecook = iunserializer($cook['saturday']);
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_12_km']}'");
				}		
			}
			if($week ==7){
				if($cook['sunday']){
					$thecook = iunserializer($cook['sunday']);	
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_12_km']}'");
				}		
			}
			if($km_1 || $km_2 || $km_3 || $km_4 || $km_5 || $km_6 || $km_7 || $km_8 || $km_9 || $km_10 || $km_11 || $km_12){
				$result['data']['sd_1'] = $sd_1['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_1['sname'];
				$result['data']['sd_2'] = $sd_2['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_2['sname'];
				$result['data']['sd_3'] = $sd_3['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_3['sname'];
				$result['data']['sd_4'] = $sd_4['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_4['sname'];
				$result['data']['sd_5'] = $sd_5['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_5['sname'];
				$result['data']['sd_6'] = $sd_6['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_6['sname'];
				$result['data']['sd_7'] = $sd_7['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_7['sname'];
				$result['data']['sd_8'] = $sd_8['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_8['sname'];
				$result['data']['sd_9'] = $sd_9['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_9['sname'];
				$result['data']['sd_10'] = $sd_10['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_10['sname'];
				$result['data']['sd_11'] = $sd_11['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_11['sname'];
				$result['data']['sd_12'] = $sd_12['sname']."&nbsp;&nbsp;&nbsp;&nbsp;".$km_12['sname'];
				$result['data']['km_1_name'] = $km_1['sname'];
				$result['data']['km_2_name'] = $km_2['sname'];
				$result['data']['km_3_name'] = $km_3['sname'];
				$result['data']['km_4_name'] = $km_4['sname'];
				$result['data']['km_5_name'] = $km_5['sname'];
				$result['data']['km_6_name'] = $km_6['sname'];
				$result['data']['km_7_name'] = $km_7['sname'];
				$result['data']['km_8_name'] = $km_8['sname'];
				$result['data']['km_9_name'] = $km_9['sname'];
				$result['data']['km_10_name'] = $km_10['sname'];
				$result['data']['km_11_name'] = $km_11['sname'];
				$result['data']['km_12_name'] = $km_12['sname'];
				$result['data']['km_1_pic'] = $km_1['icon'];
				$result['data']['km_2_pic'] = $km_2['icon'];
				$result['data']['km_3_pic'] = $km_3['icon'];
				$result['data']['km_4_pic'] = $km_4['icon'];
				$result['data']['km_5_pic'] = $km_5['icon'];
				$result['data']['km_6_pic'] = $km_6['icon'];
				$result['data']['km_7_pic'] = $km_7['icon'];
				$result['data']['km_8_pic'] = $km_8['icon'];
				$result['data']['km_9_pic'] = $km_9['icon'];
				$result['data']['km_10_pic'] = $km_10['icon'];
				$result['data']['km_11_pic'] = $km_11['icon'];
				$result['data']['km_12_pic'] = $km_12['icon'];
				$result['info'] = 1;
			}else{
				$result['info'] = 2;
			}
	}else{
		$result['info'] = 2;
	}
	die ( json_encode ( $result ) );
}	
	
?>