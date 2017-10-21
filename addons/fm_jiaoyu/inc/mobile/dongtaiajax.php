<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','fabu','mfabu','zfabu','fangxue','sxcfb','xcfb','dellimg','savely','hfavely','UpdateTypeByActiveId','SavePlanWeek','GetDetailByWeekDay','SendPlanWeek','DeleteWeekPlanByPlanUid','updatabypic','savedatabypicforplan','GetAttendData','GetAttendDataforTeacher','CheckSign','DoSign','fzqd','fzqdqr','checklogbyid','qingjialog','videodz','videopl','delmypl','getcook','CheckSignForTeacher','DoSignForTeacher') ) ? $_GPC ['op'] : 'default';

    if ($operation == 'default') {
	           die ( json_encode ( array (
			         'result' => false,
			         'msg' => '你是傻逼吗'
	                ) ) );
    }
	if ($operation == 'savely') {
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

			$userid = $_GPC['userid'];

			$touserid = $_GPC['touserid'];

			$weid = $_GPC['weid'];

			$data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'userid' => $userid,
					'touserid' => $touserid,
					'conet' => $_GPC ['content'],
					'isfrist'=>1,
					'isliuyan'=>2,
					'createtime' => time()
			);
			pdo_insert($this->table_leave, $data);
			$leave_id = pdo_insertid();
			pdo_update($this->table_leave, array('leaveid' =>  $leave_id), array('id' =>  $leave_id));
			if ($setting['istplnotice'] == 1 && $setting['liuyan']) {
				$this->sendMobileLyhf($leave_id, $schoolid, $weid);
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
	if ($operation == 'hfavely') {
		$data = explode ( '|', $_GPC ['json'] );
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));

		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！'
		               ) ) );
		}else{
			$schoolid = $_GPC['schoolid'];

			$userid = $_GPC['userid'];

			$touserid = $_GPC['touserid'];

			$weid = $_GPC['weid'];

			$data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'leaveid' =>  $_GPC['id'],
					'userid' => $userid,
					'touserid' => $touserid,
					'conet' => $_GPC['content'],
					'isliuyan'=>2,
					'createtime' => time()
			);
			pdo_insert($this->table_leave, $data);
			$leave_id = pdo_insertid();
			if ($setting['istplnotice'] == 1 && $setting['liuyan']) {
				$this->sendMobileLyhf($leave_id, $schoolid, $weid);
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
	if ($operation == 'fabu') {
		 load()->func('communication');
		 load()->classs('weixin.account');
		 load()->func('file');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
		 $photoUrls = explode ( ',', $_GPC ['photoUrls'] );
		 $data = explode ( '|', $_GPC ['json'] );
		 $school = pdo_fetch("SELECT is_fbnew,txid,txms,logo FROM " . tablename($this->table_index) . " where id = :id", array(':id' => $_GPC['schoolid']));
		 if($_GPC ['photoUrls']){
			 $picurl = array();
			 //if($school['is_fbnew'] == 1){
				$photo = $_GPC ['photoUrls'];
				for ($i = 0; $i <= 9 ; $i++) {
					if(!empty($photo[$i])) {
						$picurl[$i] = $photo[$i];
					}
				}	
			 //}
			 // if($school['is_fbnew'] == 2){
				// for ($i = 0; $i <= 9 ; $i++) {
					// if(!empty($photoUrls[$i])) {
						// $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[$i];
						// $pic_data = ihttp_request($url);
						// $path = "images/fm_jiaoyu/pic/";
						// $picurl[$i] = $path.random(30) .".jpg";
						// file_write($picurl[$i],$pic_data['content']);
							// if (!empty($_W['setting']['remote']['type'])) { //
								// $remotestatus = file_remote_upload($picurl[$i]); //
								// if (is_error($remotestatus)) {
									// message('远程附件上传失败，请检查配置并重新上传');
								// }
							// }
					// }
				// }
			 // }
			$picstr = array('p1' => $picurl[0],'p2' => $picurl[1],'p3' => $picurl[2],'p4' => $picurl[3],'p5' => $picurl[4],'p6' => $picurl[5],'p7' => $picurl[6],'p8' => $picurl[7],'p9' => $picurl[8],);
		 }
		$video = '';
		if(!empty($_GPC['videoMediaId'])){
			$msgtype = 3;//视频
			$HttpUrl="vod.api.qcloud.com";
			$HttpMethod="GET"; 
			$isHttps =true;
			$secretKey=$school['txms'];
			$COMMON_PARAMS = array(
					'Nonce'=> rand(),
					'Timestamp'=>time(NULL),
					'Action'=>'DescribeVodPlayUrls',
					'SecretId'=> $school['txid'],
					'Region' =>'',
			);
			$COMMON_PARAMS1 = array(
					'Nonce'=> rand(),
					'Timestamp'=>time(NULL),
					'Action'=>'DeleteVodFile',
					'SecretId'=> $school['txid'],
					'Region' =>'',
			);
			$PRIVATE_PARAMS = array(
					'fileId'=> trim($_GPC['videoMediaId']),
			);			
			$getfile = CreateRequest($HttpUrl,$HttpMethod,$COMMON_PARAMS,$secretKey, $PRIVATE_PARAMS, $isHttps);
			if($getfile['code'] == 0){
				$video = $getfile['playSet'][0]['url'];	
				$videos = file_get_contents($video);
				$path = "images/fm_jiaoyu/video/";
				$video = $path.random(30).".mp4";
				file_write($video,$videos);
				if(file_exists(IA_ROOT . "/attachment/".$video)){
					if (!empty($_W['setting']['remote']['type'])) {
						$remotestatus = file_remote_upload($video);
						if (is_error($remotestatus)) {
							message('远程附件上传失败，请检查配置并重新上传');
						}
					}
					$PRIVATE_PARAMS1 = array(
							'fileId'=> trim($_GPC['videoMediaId']),
							'priority'=> 0,
					);
					CreateRequest($HttpUrl,$HttpMethod,$COMMON_PARAMS1,$secretKey, $PRIVATE_PARAMS1, $isHttps);
				}						
			}else{
				die ( json_encode ( array (
					'status' => 2,
					'info' => '视频未上传成功！' 
				   ) ) );
			}
		}
		$audios = $_GPC ['audioServerid'];
		
		$audio = $audios[0];
		if($audios){
			
			$mp3name = str_replace('images/bjq/vioce/','',$audios);
			$mp3 = str_replace('.mp3','',$mp3name);
			
		}		
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));

		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！'
		               ) ) );
	    }else{

			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！'
		               ) ) );

		    }else{

				$schoolid = $_GPC['schoolid'];

				$title = $_GPC['title'];

				$weid = $_GPC['weid'];

				$content = $_GPC['content'];

				$tid = $_GPC['tid'];
				
				$uid = $_GPC['uid'];
				
				$openid = $_GPC['openid'];

				$bj_id = $_GPC['bj_id']; //用户组
				
				$audios = $_GPC ['audioServerid'];
				
				$audio = $audios[0];
				
				$audiotimes = $_GPC['audioTime'];
				
				$audiotime = $audiotimes[0];
				
				$tname = $_GPC['tname'];
				
				$shername = $tname;
				
				$is_private = trim($_GPC['is_private']);
				
				if($is_private == 'Y'){
					$bjqdata = array(
						'weid' =>  $weid,
						'schoolid' => $schoolid,
						'uid' => $uid,
						'shername' => $shername,
						'audio' => $audio,
						'audiotime' => $audiotime,
						'content' => $content,
						'video' => $video,
						'bj_id1' => $bj_id,
						'openid'=>$openid,
						'isopen'=>0,
						'is_private'=>'N',
						'createtime' => time(),
						'msgtype'=>7,
						'type'=>0,
					);
													
					pdo_insert($this->table_bjq, $bjqdata);
				
					$bjq_id = pdo_insertid();
					
					$data1 = array(
						'sherid'=>$bjq_id,
					);
					
					pdo_update($this->table_bjq, $data1, array ('id' => $bjq_id) );
					
					if($_GPC ['photoUrls']){
						 $photoUrl = $_GPC ['photoUrls'];
						 $order = 1;
						 foreach($photoUrl as $key => $v){
							if(!empty($v)) {
							   $data = array(
								'weid' =>  $weid,
								'schoolid' => $schoolid,
								'uid' => $uid,
								'picurl' => $v,	
								'bj_id1' => $bj_id,
								'order'=>$order,
								'sherid'=>$bjq_id,
								'createtime' => time(),
							   );
							   pdo_insert($this->table_media, $data);							
							}
							$order++;
						 }
					}
				}
				
				$temp = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'tid' => $tid,
					'tname' => $tname,
					'title' => $title,
					'video' => $video,
					'videopic' => $videoimg,
					'audio' => $audio,
					'audiotime' => $audiotime,					
					'content' => $content,
					'createtime' => time(),
					'type'=>1,
					'bj_id'=>$bj_id,
				);
				$temp['picarr'] = iserializer($picstr);
				
			    pdo_insert($this->table_notice, $temp);

			    $notice_id = pdo_insertid();

			    if ($setting['istplnotice'] == 1 && $setting['bjtz']) {

				   $this->sendMobileBjtz($notice_id, $schoolid, $weid, $tname, $bj_id);

			    }else{
				    die ( json_encode ( array (
                    'result' => false,
                    'msg' => '发送失败，请联系管理员开启模版消息！'
		            ) ) );
			    }
				$data ['status'] = 1;
				
		        $data ['result'] = true;

			    $data ['msg'] = '群发成功，请勿重复操作';

			}
          die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'mfabu') {
		 load()->func('communication');
		 load()->classs('weixin.account');
		 load()->func('file');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
		 $photoUrls = explode ( ',', $_GPC ['photoUrls'] );
		 $data = explode ( '|', $_GPC ['json'] );
		 $school = pdo_fetch("SELECT is_fbnew,txid,txms,logo FROM " . tablename($this->table_index) . " where id = :id", array(':id' => $_GPC['schoolid']));
		 if($_GPC ['photoUrls']){
			 $picurl = array();
			 //if($school['is_fbnew'] == 1){
				$photo = $_GPC ['photoUrls'];
				for ($i = 0; $i <= 9 ; $i++) {
					if(!empty($photo[$i])) {
						$picurl[$i] = $photo[$i];
					}
				}	
			 //}
			 // if($school['is_fbnew'] == 2){
				// for ($i = 0; $i <= 9 ; $i++) {
					// if(!empty($photoUrls[$i])) {
						// $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[$i];
						// $pic_data = ihttp_request($url);
						// $path = "images/fm_jiaoyu/pic/";
						// $picurl[$i] = $path.random(30) .".jpg";
						// file_write($picurl[$i],$pic_data['content']);
							// if (!empty($_W['setting']['remote']['type'])) { //
								// $remotestatus = file_remote_upload($picurl[$i]); //
								// if (is_error($remotestatus)) {
									// message('远程附件上传失败，请检查配置并重新上传');
								// }
							// }
					// }
				// }
			 // }
			$picstr = array('p1' => $picurl[0],'p2' => $picurl[1],'p3' => $picurl[2],'p4' => $picurl[3],'p5' => $picurl[4],'p6' => $picurl[5],'p7' => $picurl[6],'p8' => $picurl[7],'p9' => $picurl[8],);
		 }
		$video = '';
		if(!empty($_GPC['videoMediaId'])){
			$msgtype = 3;//视频
			$HttpUrl="vod.api.qcloud.com";
			$HttpMethod="GET"; 
			$isHttps =true;
			$secretKey=$school['txms'];
			$COMMON_PARAMS = array(
					'Nonce'=> rand(),
					'Timestamp'=>time(NULL),
					'Action'=>'DescribeVodPlayUrls',
					'SecretId'=> $school['txid'],
					'Region' =>'',
			);
			$COMMON_PARAMS1 = array(
					'Nonce'=> rand(),
					'Timestamp'=>time(NULL),
					'Action'=>'DeleteVodFile',
					'SecretId'=> $school['txid'],
					'Region' =>'',
			);
			$PRIVATE_PARAMS = array(
					'fileId'=> trim($_GPC['videoMediaId']),
			);			
			$getfile = CreateRequest($HttpUrl,$HttpMethod,$COMMON_PARAMS,$secretKey, $PRIVATE_PARAMS, $isHttps);
			if($getfile['code'] == 0){
				$video = $getfile['playSet'][0]['url'];	
				$videos = file_get_contents($video);
				$path = "images/fm_jiaoyu/video/";
				$video = $path.random(30).".mp4";
				file_write($video,$videos);
				if(file_exists(IA_ROOT . "/attachment/".$video)){
					if (!empty($_W['setting']['remote']['type'])) {
						$remotestatus = file_remote_upload($video);
						if (is_error($remotestatus)) {
							message('远程附件上传失败，请检查配置并重新上传');
						}
					}
					$PRIVATE_PARAMS1 = array(
							'fileId'=> trim($_GPC['videoMediaId']),
							'priority'=> 0,
					);
					CreateRequest($HttpUrl,$HttpMethod,$COMMON_PARAMS1,$secretKey, $PRIVATE_PARAMS1, $isHttps);
				}						
			}else{
				die ( json_encode ( array (
					'status' => 2,
					'info' => '视频未上传成功！' 
				   ) ) );
			}
		}
		$audios = $_GPC ['audioServerid'];
		
		$audio = $audios[0];
		if($audios){
			
			$mp3name = str_replace('images/bjq/vioce/','',$audios);
			$mp3 = str_replace('.mp3','',$mp3name);
			
		}		
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));

		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！'
		               ) ) );
	    }else{

			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！'
		               ) ) );

		    }else{

				$schoolid = $_GPC['schoolid'];

				$title = $_GPC['title'];

				$weid = $_GPC['weid'];

				$content = $_GPC['content'];

				$tid = $_GPC['tid'];

				$groupid = $_GPC['bj_id']; //用户组
				
				$audios = $_GPC ['audioServerid'];
				
				$audio = $audios[0];
				
				$audiotimes = $_GPC['audioTime'];
				
				$audiotime = $audiotimes[0];
				
				$tname = $_GPC['tname'];
				
				$is_private = trim($_GPC['is_private']);

				$temp = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'tid' => $tid,
					'tname' => $tname,
					'title' => $title,
					'video' => $video,
					'videopic' => $videoimg,
					'audio' => $audio,
					'audiotime' => $audiotime,					
					'content' => $content,
					'createtime' => time(),
					'type'=>2,
					'groupid'=>$groupid,
				);
				$temp['picarr'] = iserializer($picstr);
				if($is_private == 'Y'){
					if($picurl[0]){
						$thumb = $picurl[0];
					}else{
						$thumb = $school['logo'];
					}
					$lastnews = pdo_fetch("SELECT displayorder FROM " . tablename($this->table_news) . " WHERE :schoolid = schoolid And :type = type ORDER BY displayorder DESC LIMIT 1", array(':schoolid' => $schoolid,':type' => 'article'));
					$displayorder = $lastnews['displayorder'] + 1;
					$news = array(
						'weid' => $weid,
						'schoolid' => $schoolid,
						'title' => $title,
						'content' => $content,
						'thumb' => $thumb,
						'description' => $content,
						'author' => $tname,
						'is_display' => 1,
						'is_show_home' => 1,
						'type' => 'article',
						'displayorder' => $displayorder,
						'createtime' => time(),
					);
					pdo_insert($this->table_news, $news);	
				}
			    pdo_insert($this->table_notice, $temp);

			    $notice_id = pdo_insertid();

			    if ($setting['istplnotice'] == 1 && $setting['xxtongzhi']) {

				   $this->sendMobileXytz($notice_id, $schoolid, $weid, $tname, $groupid);

			    }else{
				    die ( json_encode ( array (
                    'result' => false,
                    'msg' => '发送失败，请联系管理员开启模版消息！'
		               ) ) );
			    }
				$data ['status'] = 1;
				
		        $data ['result'] = true;

			    $data ['msg'] = '群发成功，请勿重复操作';

			}
          die ( json_encode ( $data ) );
		}
    }
	if ($operation == 'zfabu') {
		 load()->func('communication');
		 load()->classs('weixin.account');
		 load()->func('file');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
		 $photoUrls = explode ( ',', $_GPC ['photoUrls'] );
		 $data = explode ( '|', $_GPC ['json'] );
		 $school = pdo_fetch("SELECT is_fbnew,txid,txms,logo FROM " . tablename($this->table_index) . " where id = :id", array(':id' => $_GPC['schoolid']));
		 if($_GPC ['photoUrls']){
			 $picurl = array();
			 //if($school['is_fbnew'] == 1){
				$photo = $_GPC ['photoUrls'];
				for ($i = 0; $i <= 9 ; $i++) {
					if(!empty($photo[$i])) {
						$picurl[$i] = $photo[$i];
					}
				}	
			 //}
			 // if($school['is_fbnew'] == 2){
				// for ($i = 0; $i <= 9 ; $i++) {
					// if(!empty($photoUrls[$i])) {
						// $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[$i];
						// $pic_data = ihttp_request($url);
						// $path = "images/fm_jiaoyu/pic/";
						// $picurl[$i] = $path.random(30) .".jpg";
						// file_write($picurl[$i],$pic_data['content']);
							// if (!empty($_W['setting']['remote']['type'])) { //
								// $remotestatus = file_remote_upload($picurl[$i]); //
								// if (is_error($remotestatus)) {
									// message('远程附件上传失败，请检查配置并重新上传');
								// }
							// }
					// }
				// }
			 // }
			$picstr = array('p1' => $picurl[0],'p2' => $picurl[1],'p3' => $picurl[2],'p4' => $picurl[3],'p5' => $picurl[4],'p6' => $picurl[5],'p7' => $picurl[6],'p8' => $picurl[7],'p9' => $picurl[8],);
		 }
		$video = '';
		if(!empty($_GPC['videoMediaId'])){
			$msgtype = 3;//视频
			$HttpUrl="vod.api.qcloud.com";
			$HttpMethod="GET"; 
			$isHttps =true;
			$secretKey=$school['txms'];
			$COMMON_PARAMS = array(
					'Nonce'=> rand(),
					'Timestamp'=>time(NULL),
					'Action'=>'DescribeVodPlayUrls',
					'SecretId'=> $school['txid'],
					'Region' =>'',
			);
			$COMMON_PARAMS1 = array(
					'Nonce'=> rand(),
					'Timestamp'=>time(NULL),
					'Action'=>'DeleteVodFile',
					'SecretId'=> $school['txid'],
					'Region' =>'',
			);
			$PRIVATE_PARAMS = array(
					'fileId'=> trim($_GPC['videoMediaId']),
			);			
			$getfile = CreateRequest($HttpUrl,$HttpMethod,$COMMON_PARAMS,$secretKey, $PRIVATE_PARAMS, $isHttps);
			if($getfile['code'] == 0){
				$video = $getfile['playSet'][0]['url'];	
				$videos = file_get_contents($video);
				$path = "images/fm_jiaoyu/video/";
				$video = $path.random(30).".mp4";
				file_write($video,$videos);
				if(file_exists(IA_ROOT . "/attachment/".$video)){
					if (!empty($_W['setting']['remote']['type'])) {
						$remotestatus = file_remote_upload($video);
						if (is_error($remotestatus)) {
							message('远程附件上传失败，请检查配置并重新上传');
						}
					}
					$PRIVATE_PARAMS1 = array(
							'fileId'=> trim($_GPC['videoMediaId']),
							'priority'=> 0,
					);
					CreateRequest($HttpUrl,$HttpMethod,$COMMON_PARAMS1,$secretKey, $PRIVATE_PARAMS1, $isHttps);
				}						
			}else{
				die ( json_encode ( array (
					'status' => 2,
					'info' => '视频未上传成功！' 
				   ) ) );
			}
		}
		$audios = $_GPC ['audioServerid'];
		
		$audio = $audios[0];
		if($audios){
			
			$mp3name = str_replace('images/bjq/vioce/','',$audios);
			$mp3 = str_replace('.mp3','',$mp3name);
			
		}		
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));

		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！'
		               ) ) );
	    }else{

			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！'
		               ) ) );

		    }else{

				$schoolid = $_GPC['schoolid'];

				$title = $_GPC['title'];

				$weid = $_GPC['weid'];

				$content = $_GPC['content'];

				$tid = $_GPC['tid'];
				
				$uid = $_GPC['uid'];
				
				$openid = $_GPC['openid'];

				$bj_id = $_GPC['bj_id']; 
				
				$km_id = $_GPC['km_id'];
				
				$audios = $_GPC ['audioServerid'];
				
				$audio = $audios[0];
				
				$audiotimes = $_GPC['audioTime'];
				
				$audiotime = $audiotimes[0];
				
				$tname = $_GPC['tname'];
				
				$shername = $tname.'老师';
				
				$is_private = trim($_GPC['is_private']);
								
				$temp = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'tid' => $tid,
					'tname' => $tname,
					'title' => $title,
					'video' => $video,
					'videopic' => $videoimg,
					'audio' => $audio,
					'audiotime' => $audiotime,					
					'content' => $content,
					'createtime' => time(),
					'type'=>3,
					'bj_id'=>$bj_id,
					'km_id'=>$km_id,
				);

				$temp['picarr'] = iserializer($picstr);
				
			    pdo_insert($this->table_notice, $temp);

			    $notice_id = pdo_insertid();

			    if ($setting['istplnotice'] == 1 && $setting['zuoye']) {

				   $this->sendMobileZuoye($notice_id, $schoolid, $weid, $tname, $bj_id);

			    }else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！'
		               ) ) );
			    }
				$data ['status'] = 1;
				
		        $data ['result'] = true;

			    $data ['msg'] = '群发成功，请勿重复操作';

			}
          die ( json_encode ( $data ) );
		}
    }
	
	if ($operation == 'fangxue') {

		 $data = explode ( '|', $_GPC ['json'] );

		 $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));

		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！'
		               ) ) );
	    }else{

			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！'
		               ) ) );

		    }else{

				$schoolid = $_GPC['schoolid'];

				$weid = $_GPC['weid'];

				$bj_id = $_GPC['bj_id']; //班级

				$tname = $_GPC['tname'];

			    if ($setting['istplnotice'] == 1 && $setting['bjtz']) {

				   $this->sendMobileFxtz($schoolid, $weid, $tname, $bj_id);

			    }else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！'
		               ) ) );
			    }

		        $data ['result'] = true;

			    $data ['msg'] = '群发成功，请勿重复发布！';

			}
          die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'sxcfb') {

		 load()->func('communication');
		 load()->classs('weixin.account');
		 load()->func('file');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
		 $photoUrls = explode ( ',', $_GPC ['photoUrls'] );
		 $data = explode ( '|', $_GPC ['json'] );
		 $school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id = :id", array(':weid' => $_GPC ['weid'], ':id' => $_GPC ['schoolid']));
			if($_GPC ['photoUrls']){
				 $picurl = array();
				 for ($i = 0; $i <= 9 ; $i++) {
					if(!empty($photoUrls[$i])) {
						$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[$i];
						$pic_data = ihttp_request($url);
						$path = "images/";
						$picurl[$i] = $path.random(30) .".jpg";
						file_write($picurl[$i],$pic_data['content']);
							if (!empty($_W['setting']['remote']['type'])) { //
								$remotestatus = file_remote_upload($picurl[$i]); //
								if (is_error($remotestatus)) {
									message('远程附件上传失败，请检查配置并重新上传');
								}
							}
					}
				 }
			}

		 $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));


		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！'
		               ) ) );
	    }else{

			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！'
		               ) ) );

		    }else{

				$schoolid = $_GPC['schoolid'];

				$weid = $_GPC['weid'];

				$content = $_GPC['content'];

				$uid = $_GPC['uid'];

				$sid = $_GPC['sid'];

				$bj_id = $_GPC['bj_id'];

				$isfmpic = pdo_fetch("SELECT * FROM " . tablename($this->table_media) . " WHERE :weid = weid And :schoolid = schoolid And :sid = sid And :type = type And :bj_id1 = bj_id1 ORDER BY id ASC LIMIT 0,1 ", array(
						 ':weid' => $weid,
						 ':schoolid' => $schoolid,
						 ':sid' => $sid,
						 ':bj_id1' => $bj_id,
						 ':type' => 1
						 ));

				if (!empty($isfmpic['fmpicurl'])){
					if(!empty($photoUrls[0])) {
					   $data = array(
						'weid' =>  $weid,
						'schoolid' => $schoolid,
						'uid' => $uid,
						'sid' => $sid,
						'picurl' => $picurl[0],
						'bj_id1' => $bj_id,
						'order'=>1,
						'createtime' => time(),
						'type'=>1,
					   );
					   pdo_insert($this->table_media, $data);
					}
				}else{
					if(!empty($photoUrls[0])) {
					   $data = array(
						'weid' =>  $weid,
						'schoolid' => $schoolid,
						'uid' => $uid,
						'sid' => $sid,
						'picurl' => $picurl[0],
						'fmpicurl' => $picurl[0],
						'bj_id1' => $bj_id,
						'order'=>1,
						'createtime' => time(),
						'type'=>1,
						'isfm'=>1
					   );
					   pdo_insert($this->table_media, $data);
					}
				}

				if(!empty($photoUrls[1])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl[1],
					'bj_id1' => $bj_id,
					'order'=>2,
					'createtime' => time(),
					'type'=>1,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[2])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl[2],
					'bj_id1' => $bj_id,
					'order'=>3,
					'createtime' => time(),
					'type'=>1,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[3])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl[3],
					'bj_id1' => $bj_id,
					'order'=>4,
					'createtime' => time(),
					'type'=>1,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[4])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl[4],
					'bj_id1' => $bj_id,
					'order'=>5,
					'createtime' => time(),
					'type'=>1,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[5])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl[5],
					'bj_id1' => $bj_id,
					'order'=>6,
					'createtime' => time(),
					'type'=>1,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[6])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl[6],
					'bj_id1' => $bj_id,
					'order'=>7,
					'createtime' => time(),
					'type'=>1,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[7])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl[7],
					'bj_id1' => $bj_id,
					'order'=>8,
					'createtime' => time(),
					'type'=>1,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[8])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl[8],
					'bj_id1' => $bj_id,
					'order'=>9,
					'createtime' => time(),
					'type'=>1,
			       );
                   pdo_insert($this->table_media, $data);
				}

		        $data ['result'] = true;

			    $data ['msg'] = '发布成功，请勿重复发布！';

			}
          die ( json_encode ( $data ) );
		}
    }
	if ($operation == 'xcfb') {

		 load()->func('communication');
		 load()->classs('weixin.account');
		 load()->func('file');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
		 $photoUrls = explode ( ',', $_GPC ['photoUrls'] );
		 $bjids = explode ( ',', $_GPC ['bj_id'] );
		 $data = explode ( '|', $_GPC ['json'] );
		 $school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id = :id", array(':weid' => $_GPC ['weid'], ':id' => $_GPC ['schoolid']));
				if(!empty($photoUrls[0])) {
					$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[0];
					$pic_data = ihttp_request($url);
					$path = "images/";
					$picurl0 = $path.random(30) .".jpg";
					file_write($picurl0,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl0); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

				if(!empty($photoUrls[1])) {
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[1];
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl1 = $path.random(30) .".jpg";
				file_write($picurl1,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl1); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

				if(!empty($photoUrls[2])) {
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[2];
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl2 = $path.random(30) .".jpg";
				file_write($picurl2,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl2); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

				if(!empty($photoUrls[3])) {
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[3];
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl3 = $path.random(30) .".jpg";
				file_write($picurl3,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl3); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

				if(!empty($photoUrls[4])) {
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[4];
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl4 = $path.random(30) .".jpg";
				file_write($picurl4,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl4); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

				if(!empty($photoUrls[5])) {
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[5];
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl5 = $path.random(30) .".jpg";
				file_write($picurl5,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl5); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

				if(!empty($photoUrls[6])) {
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[6];
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl6 = $path.random(30) .".jpg";
				file_write($picurl6,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl6); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

				if(!empty($photoUrls[7])) {
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[7];
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl7 = $path.random(30) .".jpg";
				file_write($picurl7,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl7); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

				if(!empty($photoUrls[8])) {
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[8];
				$pic_data = ihttp_request($url);
				$path = "images/";
				$picurl8 = $path.random(30) .".jpg";
				file_write($picurl8,$pic_data['content']);
						if (!empty($_W['setting']['remote']['type'])) { //
							$remotestatus = file_remote_upload($picurl8); //
							if (is_error($remotestatus)) {
								message('远程附件上传失败，请检查配置并重新上传');
							}
						}
				}

		 $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));


		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！'
		               ) ) );
	    }else{

			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！'
		               ) ) );

		    }else{

				$schoolid = $_GPC['schoolid'];

				$weid = $_GPC['weid'];

				$content = $_GPC['content'];

				$uid = $_GPC['uid'];

				$sid = $_GPC['sid'];

				$bj_id1 = $bjids[0];

				$bj_id2 = $bjids[1];

				$bj_id3 = $bjids[2];


				if(!empty($photoUrls[0])) {
				   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl0,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>1,
					'createtime' => time(),
					'type'=>2,
				   );
				   pdo_insert($this->table_media, $data);
				}

				if(!empty($photoUrls[1])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl1,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>2,
					'createtime' => time(),
					'type'=>2,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[2])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl2,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>3,
					'createtime' => time(),
					'type'=>2,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[3])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl3,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>4,
					'createtime' => time(),
					'type'=>2,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[4])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl4,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>5,
					'createtime' => time(),
					'type'=>2,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[5])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl5,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>6,
					'createtime' => time(),
					'type'=>2,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[6])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl6,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>7,
					'createtime' => time(),
					'type'=>2,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[7])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl7,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>8,
					'createtime' => time(),
					'type'=>2,
			       );
                   pdo_insert($this->table_media, $data);
				}
				if(!empty($photoUrls[8])) {
                   $data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'uid' => $uid,
					'sid' => $sid,
					'picurl' => $picurl8,
					'bj_id1' => $bj_id1,
					'bj_id2' => $bj_id2,
					'bj_id3' => $bj_id3,
					'order'=>9,
					'createtime' => time(),
					'type'=>2,
			       );
                   pdo_insert($this->table_media, $data);
				}

		        $data ['result'] = true;

			    $data ['msg'] = '发布成功，请勿重复发布！';

			}
          die ( json_encode ( $data ) );
		}
    }
	if ($operation == 'dellimg') {
		$dataid = explode ( ',', $_GPC ['fileids'] );

			if (empty($dataid)){
				   die ( json_encode ( array (
						'result' => false,
						'msg' => '您没有选中任何图片！'
						   ) ) );
			}else{
				foreach ($dataid as $mid => $row) {
				$isfm = pdo_fetch("SELECT * FROM " . tablename($this->table_media) . " where id=:id ", array(':id' => $row));
					if ($isfm['isfm'] == 1){
						die ( json_encode ( array (
							'result' => false,
							'msg' => '您不能删除封面图片！'
								) ) );
					}else{
						pdo_delete($this->table_media, array('id' => $row));
						$data ['result'] = true;
						$data ['msg'] = '删除成功！';
					}
				}

			}
		die ( json_encode ( $data ) );
	}
	if ($operation == 'savedatabypicforplan') {
		$data = file_get_contents('php://input');
		if($data){
			$data = urldecode($data);
			$data = str_replace('JSON=','',$data);
		}
		$data = json_decode($data,true);

		$starttime = strtotime($data['StartDate']);
		$endtime = strtotime($data['EndDate']);
		$checktime = pdo_fetch("SELECT * FROM " . tablename($this->table_zjh) . " where weid = :weid And schoolid = :schoolid And bj_id = :bj_id And type = :type And start < :start And end > :end", array(
			':weid' => $_GPC['weid'],
			':schoolid' => $_GPC['schoolid'],
			':bj_id' => $_GPC['bj_id'],
			':type' => 1,
			':start' => $starttime,
			':end' => $endtime
		));
		if($checktime){
			$msg ['Status'] = 0;
			$msg ['Result'] = '本时间范围内已有周计划';
			die ( json_encode ( $msg ) );
		}else{
		$temp = array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'bj_id' => $_GPC['bj_id'],'start' => $starttime,'end' => $endtime,'type' => 1,'is_on' => 2,'createtime' => time(),'tid' => $_GPC['tid']);

		pdo_update($this->table_zjh, $temp, array('planuid' => $data['PlanUid']));

		$msg ['Status'] = 1;
		$msg ['Result'] = '保存周计划成功';
		}
		die ( json_encode ( $msg ) );
	}
	if ($operation == 'updatabypic') {
		load()->func('communication');
		load()->classs('weixin.account');
		load()->func('file');
		$accObj= WeixinAccount::create($_W['account']['acid']);
		$access_token = $accObj->fetch_token();
		$token2 =  $access_token;
		$photoUrl = $_GPC['serverId'];
		$PlanUid = trim($_GPC['PlanUid']);
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
				$urls = $_W['attachurl'];
			} else {
				$urls = $_W['siteroot'].'attachment/';
			}
		}
		$thisplan = pdo_fetch("SELECT * FROM " . tablename($this->table_zjh) . " where weid = :weid And schoolid = :schoolid And bj_id = :bj_id And type = :type And planuid = :planuid ", array(
			':weid' => $_GPC['weid'],
			':schoolid' => $_GPC['schoolid'],
			':bj_id' => $_GPC['bj_id'],
			':type' => 1,
			':planuid' => $PlanUid
		));
		if (empty($thisplan)) {
			$data = array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'bj_id' => $_GPC['bj_id'],'type' => 1,'planuid' => $PlanUid,'picrul' => $picurl,'createtime' => time(),'tid' => $_GPC['tid']);
			pdo_insert($this->table_zjh, $data);
		}else{
			pdo_update($this->table_zjh, array('picrul' => $picurl,'createtime' => time()), array('planuid' => $PlanUid));
		}
		$msg ['status'] = 1;
		$msg ['data'] = $urls.$picurl;
		die ( json_encode ( $msg ) );
    }

	if ($operation == 'DeleteWeekPlanByPlanUid') {
			if (empty($_GPC['sPlanUid'])){
			   die ( json_encode ( array (
					'Status' => 0,
					'Result' => '出错了！'
					) ) );
			}else{
				pdo_delete($this->table_zjh, array('planuid' => $_GPC['sPlanUid']));
				pdo_delete($this->table_zjhset, array('planuid' => $_GPC['sPlanUid']));
				pdo_delete($this->table_zjhdetail, array('planuid' => $_GPC['sPlanUid']));
				$msg ['Status'] = 1;
				$msg ['Result'] = '删除成功';
			}
		die ( json_encode ( $msg ) );
	}
	if ($operation == 'SendPlanWeek') {
		$data = file_get_contents('php://input');
		if($data){
			$data = urldecode($data);
			$data = str_replace('JSON=','',$data);
		}
		$data = json_decode($data,true);
		if (!$_GPC['weid'] || !$_GPC['schoolid'] || !$_GPC['bj_id']) {
		   die ( json_encode ( array (
				'Status' => 0,
				'Result' => '您不是班主任，无权使用此功能'
			   ) ) );
		}else{
			$check = pdo_fetch("SELECT * FROM " . tablename($this->table_zjh) . " where weid = :weid And schoolid = :schoolid And bj_id = :bj_id And type = :type And planuid = :planuid ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':bj_id' => $_GPC['bj_id'],
				':type' => 2,
				':planuid' => $data['PlanUid']
			));
			$date = array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'bj_id' => $_GPC['bj_id'],'start' => strtotime($data['StartDate']),'end' => strtotime($data['EndDate']),'type' => 2,'is_on' => 2,'planuid' => $data['PlanUid'],'tid' => $_GPC['tid']);
			if (empty($check)){
				pdo_insert($this->table_zjh, $date);
			}else{
				pdo_update($this->table_zjh, $date, array('planuid' => $data['PlanUid']));
			}
				//pdo_delete($this->table_zjhset, array('weid' => $data['weid'],'schoolid' => $data['schoolid'],'planuid' => $data['PlanUid']));
			$detail = $data['lstPMPlanType'];
				foreach ($detail as $mid => $row) {
					$thisset = pdo_fetch("SELECT * FROM " . tablename($this->table_zjhset) . " where weid = :weid And schoolid = :schoolid And activetypeid = :activetypeid And type = :type And planuid = :planuid ", array(
						':weid' => $data['weid'],
						':schoolid' => $data['schoolid'],
						':activetypeid' => $row['id'],
						':type' => $row['timeType'],
						':planuid' => $data['PlanUid']
					));
					if (!empty($thisset)){
						pdo_update($this->table_zjhset, array('activetypename' => $row['itemName']), array('id' => $thisset['id']));
					}else{
						$ActiveTypeId = getRandomString(8).'-'.getRandomString(4).'-'.getRandomString(4).'-'.getRandomString(4).'-'.getRandomString(12);
						$temp = array(
							'weid' => $data['weid'],
							'schoolid' => $data['schoolid'],
							'activetypeid' => !empty($row['id']) ? $row['id'] : $ActiveTypeId,
							'activetypename' => $row['itemName'],
							'type' => $row['timeType'],
							'planuid' => $data['PlanUid']
						);
						pdo_insert($this->table_zjhset, $temp);
					}
				}
				$msg ['Status'] = 1;
				$msg ['PlanUid'] = $data['PlanUid'];
				$msg ['Result'] = '修改成功';
		}
		die ( json_encode ( $msg ) );
	}
	if ($operation == 'UpdateTypeByActiveId') {
		$data = file_get_contents('php://input');
		if($data){
			$data = urldecode($data);
			$data = str_replace('JSON=','',$data);
		}
		$data = json_decode($data,true);
		if (!$data['weid'] || !$data['schoolid'] || !$data['bj_id']) {
		   die ( json_encode ( array (
				'Status' => 0,
				'Result' => '您不是班主任，无权使用此功能'
			   ) ) );
		}else{
			$check = pdo_fetch("SELECT * FROM " . tablename($this->table_zjh) . " where weid = :weid And schoolid = :schoolid And bj_id = :bj_id And type = :type And planuid = :planuid ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':bj_id' => $_GPC['bj_id'],
				':type' => 2,
				':planuid' => $data['PlanUid']
			));
			$date = array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'bj_id' => $_GPC['bj_id'],'start' => strtotime($data['StartDate']),'end' => strtotime($data['EndDate']),'type' => 2,'planuid' => $data['PlanUid']);
			if (empty($check)){
				pdo_insert($this->table_zjh, $date);
			}else{
				pdo_update($this->table_zjh, $date, array('planuid' => $data['PlanUid']));
			}
				//pdo_delete($this->table_zjhset, array('weid' => $data['weid'],'schoolid' => $data['schoolid'],'planuid' => $data['PlanUid']));
			$detail = $data['lstPlanDetail'];
				foreach ($detail as $mid => $row) {
					$thisset = pdo_fetch("SELECT * FROM " . tablename($this->table_zjhset) . " where weid = :weid And schoolid = :schoolid And activetypeid = :activetypeid And type = :type And planuid = :planuid ", array(
						':weid' => $data['weid'],
						':schoolid' => $data['schoolid'],
						':activetypeid' => $row['ActiveTypeId'],
						':type' => $row['ActiveTypeIcon'],
						':planuid' => $data['PlanUid']
					));
					if (!empty($thisset)){
						pdo_update($this->table_zjhset, array('activetypename' => $row['ActiveTypeName']), array('id' => $thisset['id']));
					}else{
						$ActiveTypeId = getRandomString(8).'-'.getRandomString(4).'-'.getRandomString(4).'-'.getRandomString(4).'-'.getRandomString(12);
						$temp = array(
							'weid' => $data['weid'],
							'schoolid' => $data['schoolid'],
							'activetypeid' => !empty($row['ActiveTypeId']) ? $row['ActiveTypeId'] : $ActiveTypeId,
							'activetypename' => $row['ActiveTypeName'],
							'type' => $row['ActiveTypeIcon'],
							'planuid' => $data['PlanUid']
						);
						pdo_insert($this->table_zjhset, $temp);
					}
				}
				$msg ['Status'] = 1;
				$msg ['PlanUid'] = $data['PlanUid'];
				$class = pdo_fetchall("SELECT activetypeid,type FROM " . tablename($this->table_zjhset) . " WHERE weid = '{$data['weid']}' And schoolid = {$data['schoolid']} And planuid = '{$data['PlanUid']}' ORDER BY id ASC");
				foreach ($class as $key => $item) {
					if ($item['type'] == 'AM'){
						$msg['AMActiveId'] .= $item['activetypeid'].",";
					}
					if ($item['type'] == 'PM'){
						$msg['PMActiveId'] .= $item['activetypeid'].",";
					}
				}
				//$msg = $class[$key];
				$msg ['Result'] = '修改成功';
		}
		die ( json_encode ( $msg ) );
	}
	if ($operation == 'SavePlanWeek') {
		$data = file_get_contents('php://input');
		if($data){
			$data = urldecode($data);
			$data = str_replace('JSON=','',$data);
		}
		$data = json_decode($data,true);
		if (!$_GPC['weid'] || !$_GPC['schoolid'] || !$_GPC['bj_id']) {
		   die ( json_encode ( array (
				'Status' => 0,
				'Result' => '您不是班主任，无权使用此功能',
			   ) ) );
		}else{
			$check = pdo_fetch("SELECT * FROM " . tablename($this->table_zjh) . " where weid = :weid And schoolid = :schoolid And bj_id = :bj_id And type = :type And planuid = :planuid ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':bj_id' => $_GPC['bj_id'],
				':type' => 2,
				':planuid' => $data['PlanUid']
			));
			$date = array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'bj_id' => $_GPC['bj_id'],'start' => strtotime($data['StartDate']),'end' => strtotime($data['EndDate']),'type' => 2,'planuid' => $data['PlanUid']);
			if (empty($check)){
				pdo_insert($this->table_zjh, $date);
			}else{
				pdo_update($this->table_zjh, $date, array('planuid' => $data['PlanUid']));
			}
			$thisset = pdo_fetch("SELECT * FROM " . tablename($this->table_zjhset) . " where weid = :weid And schoolid = :schoolid And planuid = :planuid ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':planuid' => $data['PlanUid']
			));
			if (empty($thisset)){
				pdo_insert($this->table_zjhset, array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'activetypeid' => 'morning_activity','activetypename' => '晨间活动','type' => 'AM','planuid' => $data['PlanUid']));
				pdo_insert($this->table_zjhset, array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'activetypeid' => 'teach_activity','activetypename' => '教学活动','type' => 'AM','planuid' => $data['PlanUid']));
				pdo_insert($this->table_zjhset, array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'activetypeid' => 'out_activity','activetypename' => '户外活动','type' => 'PM','planuid' => $data['PlanUid']));
				pdo_insert($this->table_zjhset, array('weid' => $_GPC['weid'],'schoolid' => $_GPC['schoolid'],'activetypeid' => 'game_activity','activetypename' => '游戏活动','type' => 'PM','planuid' => $data['PlanUid']));
			}
			$thisdetail = pdo_fetch("SELECT * FROM " . tablename($this->table_zjhdetail) . " where weid = :weid And schoolid = :schoolid And curactiveid = :curactiveid And week = :week And planuid = :planuid ", array(
				':weid' => $_GPC['weid'],
				':schoolid' => $_GPC['schoolid'],
				':curactiveid' => $data['CurActiveId'],
				':week' => $data['WeekDay'],
				':planuid' => $data['PlanUid']
			));
			if (empty($thisdetail['detailuid'])){ //如果上午下午项目为空 则写入一行set规则
				$detail = $data['lstPlanDetail'];//遍历前端输入的数组
					$DetailUid = getRandomString(8).'-'.getRandomString(4).'-'.getRandomString(4).'-'.getRandomString(4).'-'.getRandomString(12); //
					foreach ($detail as $mid => $row) {
						$temp1 .= $row['ActiveDesc']."\\n";
					}
					$temp = array(
						'weid' => $_GPC['weid'],
						'schoolid' => $_GPC['schoolid'],
						'detailuid' => !empty($data['CurDetailUid']) ? $data['CurDetailUid'] : $DetailUid,
						'curactiveid' => $data['CurActiveId'],
						'curactivename' => $data['CurActiveName'],
						'week' => $data['WeekDay'],
						'planuid' => $data['PlanUid']
					);
					$temp['activedesc'] = $temp1;
					pdo_insert($this->table_zjhdetail, $temp);
					$msg ['Status'] = 1;
					$msg ['ActiveId'] = $data['CurActiveId'];
					$msg ['DetailUid'] = $DetailUid;
					$msg ['PlanUid'] = $data['PlanUid'];
					$msg ['Result'] = '修改成功';
			}else{
				$detail = $data['lstPlanDetail'];//遍历前端输入的数组
				foreach ($detail as $mid => $row) {
					$temp1 .= $row['ActiveDesc']."\\n";
				}
				$temp = array(
					'weid' => $_GPC['weid'],
					'schoolid' => $_GPC['schoolid'],
					'curactiveid' => $data['CurActiveId'],
					'curactivename' => $data['CurActiveName'],
					'week' => $data['WeekDay'],
					'planuid' => $data['PlanUid']
				);
				$temp['activedesc'] = $temp1;
				pdo_update($this->table_zjhdetail, $temp, array('detailuid' => $thisdetail['detailuid']));
				$msg ['Status'] = 1;
				$msg ['ActiveId'] = $data['CurActiveId'];
				$msg ['DetailUid'] = $thisdetail['detailuid'];
				$msg ['PlanUid'] = $data['PlanUid'];
				$msg ['Result'] = '修改成功';
			}
		}
		die ( json_encode ( $msg ) );
	}


if ($operation == 'GetDetailByWeekDay') {
    $data = file_get_contents('php://input');
    if ($data) {
        $data = urldecode($data);
    }
    $data = json_decode($data, true);
    if (!$_GPC['weid'] || !$_GPC['schoolid'] || !$_GPC['bj_id']) {
        die (json_encode(array('Status' => 0, 'Result' => '您不是班主任，无权使用此功能',)));
    } else {
        $planuid = $_GPC['sPlanUid'];
        $weekday = $_GPC['nWeekDay'];
        $shangwu = pdo_fetchall("SELECT planuid as PlanUid, activetypeid as ActiveTypeId, activetypename as ActiveTypeName, type as ActiveTypeIcon FROM " . tablename($this->table_zjhset) . " where weid = :weid And schoolid = :schoolid And type = :type And planuid = :planuid ORDER BY id ASC", array(
            ':weid'     => $_GPC['weid'],
            ':schoolid' => $_GPC['schoolid'],
            ':type'     => 'AM',
            ':planuid'  => $_GPC['sPlanUid']
        ));
        foreach ($shangwu as $key => $row) {
            $shangwu[$key]['WeekDay'] = "";
            $detail = pdo_fetchall("SELECT curactiveid,detailuid,activedesc,week,curactivename  FROM " . tablename($this->table_zjhdetail) . " WHERE weid = '{$_GPC['weid']}' And schoolid = '{$_GPC['schoolid']}' And curactiveid = '{$row['ActiveTypeId']}' And planuid = '{$_GPC['sPlanUid']}'  And week = '{$_GPC['nWeekDay']}' ");
            $shangwu[$key]['WeekDay'] = "";
            $shangwu[$key]['ActiveDesc'] = "";
            foreach ($detail as $k => $r) {
                $shangwu[$key]['DetailUid'] = $r['detailuid'];
                $shangwu[$key]['ActiveDesc'] = empty($r['activedesc']) ? "" : $r['activedesc'];
                $shangwu[$key]['WeekDay'] = $r['week'];
            }
        }
        $xiawu = pdo_fetchall("SELECT planuid as PlanUid, activetypeid as ActiveTypeId, activetypename as ActiveTypeName, type as ActiveTypeIcon FROM " . tablename($this->table_zjhset) . " where weid = :weid And schoolid = :schoolid And type = :type And planuid = :planuid ORDER BY id ASC", array(
            ':weid'     => $_GPC['weid'],
            ':schoolid' => $_GPC['schoolid'],
            ':type'     => 'PM',
            ':planuid'  => $_GPC['sPlanUid']
        ));
        foreach ($xiawu as $key => $row) {
            $xiawu[$key]['WeekDay'] = "";
            $xiawu[$key]['ActiveDesc'] = "";
            $detail = pdo_fetchall("SELECT curactiveid,detailuid,activedesc,week,curactivename  FROM " . tablename($this->table_zjhdetail) . " WHERE weid = '{$_GPC['weid']}' And schoolid = '{$_GPC['schoolid']}' And curactiveid = '{$row['ActiveTypeId']}' And planuid = '{$_GPC['sPlanUid']}'  And week = '{$_GPC['nWeekDay']}' ");
            foreach ($detail as $k => $r) {
                $xiawu[$key]['DetailUid'] = $r['detailuid'];
                $xiawu[$key]['ActiveDesc'] = empty($r['activedesc']) ? "" : $r['activedesc'];
                $xiawu[$key]['WeekDay'] = $r['week'];
            }
        }
        $msg ['Status'] = 1;
        $msg ['lstPlanDetail'] = empty($shangwu) ? array() : $shangwu;//上午项目和详细内容
        $msg ['lstPMPlanDetail'] = empty($xiawu) ? array() : $xiawu;//下午项目和详细内容
        $msg ['lstPMPlanType'] = array();
        $msg ['PlanUid'] = $_GPC['sPlanUid'];
        $msg ['Result'] = '修改成功';
    }
    die (json_encode($msg));
}

if ($operation == 'GetAttendDataforTeacher') {
	if($_GPC['sDate']){
		$thistime = strtotime($_GPC['sDate']);
		$start_time = strtotime(date('Y-m-01',$thistime));
		$nowstart = strtotime(date('Y-m-01'));
		if($start_time == $nowstart){
			$j = date(j);
		}else{
			$j = date('t',$thistime);
		}
	}
	$array = array();
	for($i=0;$i<$j;$i++){
		$array[] = array(
				'date' => date('Y-m-d',$start_time+$i*86400),//每隔一天赋值给数组
				'day' => $i +1
		);
	}
	$result['lstAttendInfoOfTeacher'] = "";
	$days = 0;
		$nowtime = date('Y-m-d');
		foreach($array as $key => $row){
			$date = explode ( '-', $row['date']);
			$starttime = mktime(0,0,0,$date[1],$date[2],$date[0]);
			$endtime = $starttime + 86399;
			$condition = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
			$condition1 = " AND (startime1 < '{$starttime}' AND endtime1 > '{$endtime}' OR startime1 > '{$starttime}' AND endtime1 < '{$endtime}')";
			$log = pdo_fetch("SELECT id FROM " . tablename($this->table_checklog) . " where schoolid = :schoolid AND tid = :tid And isconfirm = 1 $condition ", array(
				':schoolid' => $_GPC['schoolid'],
				':tid' => $_GPC['tid']
			));
			$xsqj = pdo_fetch("SELECT startime1 FROM " . tablename($this->table_leave) . " where schoolid = '{$_GPC['schoolid']}' AND tid = '{$_GPC['tid']}' And sid = 0 And isliuyan = 0 And status = 1 $condition1");
			if($log['id'] || $xsqj['startime1'] > 0){
				if(!$xsqj){
					$list[$key]['Type'] = "wx";
					$days++;
				}else{
					$list[$key]['Type'] = "leave";
				}
				$list[$key]['Date'] = $row['day'];
				$list[$key]['Uid'] = $_GPC['tid'];
				$list[$key]['Name'] = "";
				$list[$key]['Time'] = "";
				$list[$key]['Start'] = "";
				$list[$key]['End'] = "";
				$list[$key]['Url'] = "";
			}else{
				$list[$key]['Type'] = "skip";
				$list[$key]['Date'] = $row['day'];
				$list[$key]['Uid'] = "skip";
				$list[$key]['Name'] = "";
				$list[$key]['Time'] = "";
				$list[$key]['Start'] = "";
				$list[$key]['End'] = "";
				$list[$key]['Url'] = "";
			}
			$list[$key]['end'] = date('Y-m-d H:i:s',$endtime);
			$list[$key]['start'] = date('Y-m-d H:i:s',$starttime);
			$list[$key]['line'] = $xsqj;
		}
	$result['lstAttendInfo'] = $list;
	$result['TeacherName'] = $_GPC['tid'];
	$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = :id AND schoolid = :schoolid ", array(':id' => $_GPC['tid'], ':schoolid' => $_GPC['schoolid']));
	$result['StuName'] = $teacher['tname'];
	$result['AttendanceCount'] = $days;
	die ( json_encode ( $result ) );
}

if ($operation == 'GetAttendData') {
	if($_GPC['sDate']){
		$thistime = strtotime($_GPC['sDate']);
		$start_time = strtotime(date('Y-m-01',$thistime));
		$nowstart = strtotime(date('Y-m-01'));
		if($start_time == $nowstart){
			$j = date(j);
		}else{
			$j = date('t',$thistime);
		}
	}
	$array = array();
	for($i=0;$i<$j;$i++){
		$array[] = array(
				'date' => date('Y-m-d',$start_time+$i*86400),//每隔一天赋值给数组
				'day' => $i +1
		);
	}
	$result['lstAttendInfoOfTeacher'] = "";
	$days = 0;
		$nowtime = date('Y-m-d');
		foreach($array as $key => $row){
			$date = explode ( '-', $row['date']);
			$starttime = mktime(0,0,0,$date[1],$date[2],$date[0]);
			$endtime = $starttime + 86399;
			$condition = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
			$condition1 = " AND (startime1 < '{$starttime}' AND endtime1 > '{$endtime}' OR startime1 > '{$starttime}' AND endtime1 < '{$endtime}')";
			$log = pdo_fetch("SELECT id FROM " . tablename($this->table_checklog) . " where schoolid = :schoolid AND sid = :sid And isconfirm = 1 $condition ", array(
				':schoolid' => $_GPC['schoolid'],
				':sid' => $_GPC['sid']
			));
			$xsqj = pdo_fetch("SELECT startime1 FROM " . tablename($this->table_leave) . " where schoolid = '{$_GPC['schoolid']}' AND sid = '{$_GPC['sid']}' And tid = 0 And isliuyan = 0 And status = 1 $condition1");
			if($log['id'] || $xsqj['startime1'] > 0){
				if(!$xsqj){
					$list[$key]['Type'] = "wx";
					$days++;
				}else{
					$list[$key]['Type'] = "leave";
				}
				$list[$key]['Date'] = $row['day'];
				$list[$key]['Uid'] = $_GPC['sid'];
				$list[$key]['Name'] = "";
				$list[$key]['Time'] = "";
				$list[$key]['Start'] = "";
				$list[$key]['End'] = "";
				$list[$key]['Url'] = "";
			}else{
				$list[$key]['Type'] = "skip";
				$list[$key]['Date'] = $row['day'];
				$list[$key]['Uid'] = "skip";
				$list[$key]['Name'] = "";
				$list[$key]['Time'] = "";
				$list[$key]['Start'] = "";
				$list[$key]['End'] = "";
				$list[$key]['Url'] = "";
			}
			$list[$key]['end'] = date('Y-m-d H:i:s',$endtime);
			$list[$key]['start'] = date('Y-m-d H:i:s',$starttime);
			$list[$key]['line'] = $xsqj;
		}
	$result['lstAttendInfo'] = $list;
	$result['TeacherName'] = $_GPC['sid'];
	$student = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " where id = :id AND schoolid = :schoolid ", array(':id' => $_GPC['sid'], ':schoolid' => $_GPC['schoolid']));
	$result['StuName'] = $student['s_name'];
	$result['AttendanceCount'] = $days;
	die ( json_encode ( $result ) );
}

if ($operation == 'CheckSignForTeacher') {
	if($_GPC['lat'] && $_GPC['lon']){
		$result['status'] = 2;
	}else{
		$result['status'] = 0;
		$result['info'] = "抱歉,您必须允许获取您的位置信息,请开启或退出微信重新进入后允许获取";
	}
	die ( json_encode ( $result ) );
}

if ($operation == 'DoSignForTeacher') {
	$school = pdo_fetch("SELECT is_wxsign FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $_GPC['schoolid']));
	if($school['is_wxsign'] ==1){
		if ($_GPC['type'] ==1){
			$type = "进校";
		}else{
			$type = "离校";
		}
			$data = array(
				'weid' => $_W['uniacid'],
				'schoolid' => $_GPC['schoolid'],
				'tid' => $_GPC['tid'],
				'pard' => 1,
				'checktype' => 2,
				'isconfirm' => 1,
				'isread' => 2,
				'lon' => trim($_GPC['lon']),
				'lat' => trim($_GPC['lat']),				
				'type' => $type,
				'leixing' =>  $_GPC['type'],
				'createtime' => time()
			);
			pdo_insert($this->table_checklog, $data);
			$result['status'] = 1;
			$result['data'] = "long";
			$result['info'] = "签到成功,请勿重复签到";
	}else{
		$result['status'] = 2;
		$result['info'] = "抱歉,本校未启用微信辅助签到功能";
	}

	die ( json_encode ( $result ) );
}

if ($operation == 'CheckSign') {
	$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$endtime = $starttime + 86399;
	$condition = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
	$logjx = pdo_fetch("SELECT createtime FROM " . tablename($this->table_checklog) . " WHERE :schoolid = schoolid And :sid = sid And checktype = 2 And isconfirm = 1 And leixing = 1 $condition ORDER BY id ASC LIMIT 0,1", array(':schoolid' => $_GPC['schoolid'],':sid' => $_GPC['sid']));
	$loglx = pdo_fetch("SELECT createtime FROM " . tablename($this->table_checklog) . " WHERE :schoolid = schoolid And :sid = sid And checktype = 2 And isconfirm = 1 And leixing = 2 $condition ORDER BY id DESC LIMIT 0,1", array(':schoolid' => $_GPC['schoolid'],':sid' => $_GPC['sid']));
	if($logjx || $loglx){
		if($_GPC['type'] ==1){
			$result['status'] = 1;
			$result['data'] = date('H:m:s',$logjx['createtime']);
		}else{
			$result['status'] = 1;
			$result['data'] = date('H:m:s',$loglx['createtime']);
		}
	}else{
		$result['status'] = 2;
	}
	die ( json_encode ( $result ) );
}

if ($operation == 'DoSign') {
	$school = pdo_fetch("SELECT is_signneedcomfim FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $_GPC['schoolid']));
	if($school['is_wxsign'] ==1){
		if ($_GPC['pard'] == 2){
			$pard = 2;
		}
		if ($_GPC['pard'] == 3){
			$pard = 3;
		}
		if ($_GPC['pard'] == 4){
			$pard = 1;
		}
		if ($_GPC['pard'] == 5){
			$pard = 10;
		}
		if ($_GPC['type'] ==1){
			$type = "进校";
		}else{
			$type = "离校";
		}
		if ($school['is_signneedcomfim'] == 1){
			$data = array(
				'weid' => $_W['uniacid'],
				'schoolid' => $_GPC['schoolid'],
				'sid' => $_GPC['sid'],
				'bj_id' => $_GPC['bj_id'],
				'pard' => $pard,
				'checktype' => 2,
				'isconfirm' => 2,
				'type' => $type,
				'leixing' => $_GPC['type'],
				'createtime' => time()
			);
			pdo_insert($this->table_checklog, $data);
			$logid = pdo_insertid();
			$result['status'] = 1;
			$result['data'] = "wait";
			$result['data1'] = $pard;
			$result['info'] = "签到信息发送成功,请等待确认";
			$this ->sendMobileSignshtz($logid);
		}else{
			$data = array(
				'weid' => $_W['uniacid'],
				'schoolid' => $_GPC['schoolid'],
				'sid' => $_GPC['sid'],
				'bj_id' => $_GPC['bj_id'],
				'pard' => $pard,
				'checktype' => 2,
				'isconfirm' => 1,
				'type' => $type,
				'leixing' =>  $_GPC['type'],
				'createtime' => time()
			);
			pdo_insert($this->table_checklog, $data);
			$result['status'] = 1;
			$result['data'] = "long";
			$result['info'] = "签到成功,请勿重复签到";
		}
	}else{
		$result['status'] = 2;
		$result['info'] = "抱歉,本校未启用微信辅助签到功能";
	}

	die ( json_encode ( $result ) );
}

if ($operation == 'fzqdqr') {
	$logids = explode ( ',', $_GPC ['logids'] );
	if($logids){
		foreach($logids as $row){
			if($row >0){
				pdo_update($this->table_checklog, array('isconfirm' => 1), array('id' => $row));
				$this ->sendMobileFzqdshjg($row);
			}
		}
		$result['info'] = "确认成功！";
	}else{
		$result['info'] = "您没有选择学生！";
	}
	die ( json_encode ( $result ) );
}

if ($operation == 'fzqd') {
	$sids = explode ( ',', $_GPC['sids'] );
	if($sids){
		if ($_GPC['TimeType'] ==1){
			$type = "进校";
		}else{
			$type = "离校";
		}
		$rs = 0;
		foreach($sids as $row){
			if($row >0){
				$data = array(
					'weid' => $_W['uniacid'],
					'schoolid' => $_GPC['schoolid'],
					'sid' => $row,
					'bj_id' => $_GPC['bj_id'],
					'pard' => 11,
					'checktype' => 2,
					'isconfirm' => 1,
					'type' => $type,
					'leixing' =>  $_GPC['TimeType'],
					'qdtid' =>  $_GPC['tid'],
					'createtime' => time()
				);
				$pard = 11;
				pdo_insert($this->table_checklog, $data);
				$logid = pdo_insertid();
				$this ->sendMobileFzqdtx($_GPC['schoolid'],$_W['uniacid'],$_GPC['bj_id'],$row,$type,$_GPC['TimeType'],$logid,$pard);
				$rs ++;
			}
		}
		$result['info'] = "成功签到".$rs."个学生";
	}else{
		$result['info'] = "您没有选择学生！";
	}
	die ( json_encode ( $result ) );
}

if ($operation == 'checklogbyid') {
	$date = explode ( '-', $_GPC['time'] );
	$starttime = mktime(0,0,0,$date[1],$date[2],$date[0]);
	$endtime = $starttime + 86399;
	$condition = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
	$condition1 = "";
	if($_GPC['timeType']){
		$condition1 = " AND leixing = '{$_GPC['timeType']}' ";
	}
	if($_GPC['sid']){
		$log = pdo_fetchall("SELECT * FROM " . tablename($this->table_checklog) . " where schoolid = :schoolid AND sid = :sid And isconfirm = 1 $condition1 $condition ORDER BY createtime DESC", array(
			':schoolid' => $_GPC['schoolid'],
			':sid' => $_GPC['sid']
		));
	}else{
		$log = pdo_fetchall("SELECT * FROM " . tablename($this->table_checklog) . " where schoolid = :schoolid AND tid = :tid And isconfirm = 1 $condition1 $condition ORDER BY createtime DESC", array(
			':schoolid' => $_GPC['schoolid'],
			':tid' => $_GPC['tid']
		));		
	}
	if($log){
		foreach($log as $key => $row){
			if($row['checktype'] ==1){
				$log[$key]['Type'] = "card";
			}else{
				$log[$key]['Type'] = "wx";
			}
			if($row['temperature']){
				$log[$key]['Temp'] = $row['temperature']."℃";
			}else{
				$log[$key]['Temp'] = "未测";
			}			
			$log[$key]['Date'] = 0;
			$log[$key]['Uid'] = "";
			if($_GPC['tid']){
				$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = :id AND schoolid = :schoolid ", array(':id' => $_GPC['tid'], ':schoolid' => $_GPC['schoolid']));
				$log[$key]['Name'] = $teacher['tname'];					
			}else{
				$student = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " where id = :id AND schoolid = :schoolid ", array(':id' => $row['sid'], ':schoolid' => $_GPC['schoolid']));
				$pard = getpard($row['pard']);
				$log[$key]['Name'] = $student['s_name'].$pard;					
			}
			
			$log[$key]['Time'] = date('Y-m-d H:i:s',$row['createtime']);
			$log[$key]['Start'] = "";
			$log[$key]['End'] = "";
			$log[$key]['Url'] = $row['pic'];
			$log[$key]['Url2'] = empty($row['pic2'])? '' : $row['pic2'];
			//pdo_update($this->table_checklog, array('isread' => 2), array('id' => $row['id']));
		}
		$result = $log;
	}
	die ( json_encode ( $result ) );
}

if ($operation == 'qingjialog') {
	$date = explode ( '-', $_GPC['time'] );
	$starttime = mktime(0,0,0,$date[1],$date[2],$date[0]);
	$endtime = $starttime + 86399;
	//$condition = " AND startime1 < '{$starttime}' AND endtime1 > '{$endtime}'";
	$condition = " AND (startime1 < '{$starttime}' AND endtime1 > '{$endtime}' OR startime1 > '{$starttime}' AND endtime1 < '{$endtime}')";
	if($_GPC['sid']){
		$xsqj = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where schoolid = :schoolid AND sid = :sid And tid = 0 And isliuyan = 0 And status = 1 $condition", array(
			':schoolid' => $_GPC['schoolid'],
			':sid' => $_GPC['sid']
		));
		if($xsqj){
			$student = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $xsqj['sid']));
			$log['ParentName'] = $student['s_name'];
			$log['StartTime'] = date('m-d H:i',$xsqj['startime1']);
			$log['EndTime'] = date('m-d H:i',$xsqj['endtime1']);
			$log['LeaveContent'] = $xsqj['conet'];
			$log['CreateTime'] = date('Y-m-d H:i',$xsqj['createtime']);
		}		
	}
	if($_GPC['tid']){
		$xsqj = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where schoolid = :schoolid AND tid = :tid And sid = 0 And isliuyan = 0 And status = 1 $condition", array(
			':schoolid' => $_GPC['schoolid'],
			':tid' => $_GPC['tid']
		));
		if($xsqj){
			$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $xsqj['tid']));
			$log['ParentName'] = $teacher['tname'];
			$log['StartTime'] = date('m-d H:i',$xsqj['startime1']);
			$log['EndTime'] = date('m-d H:i',$xsqj['endtime1']);
			$log['LeaveContent'] = $xsqj['conet'];
			$log['CreateTime'] = date('Y-m-d H:i',$xsqj['createtime']);
		}
	}
	die ( json_encode ( $log ) );
}

if ($operation == 'videodz') {
	if($_GPC['thisop'] == 'mybj'){
		$dianz = pdo_fetch("SELECT id FROM " . tablename($this->table_camerapl) . " where schoolid = :schoolid AND bj_id = :bj_id AND userid = :userid AND type = :type", array(
			':schoolid' => $_GPC['schoolid'],
			':bj_id' => $_GPC['bj_id'],
			':userid' => $_GPC['userid'],
			':type' => 1,
		));
		if($dianz){ //有则取消点赞
			pdo_delete($this->table_camerapl, array('id' => $dianz['id']));
		}else{
			$data = array(
				'weid' => $_GPC['weid'],
				'schoolid' => $_GPC['schoolid'],
				'bj_id' => $_GPC['bj_id'],
				'carmeraid' => 0,
				'userid' => $_GPC['userid'],
				'type' => 1,
				'createtime' => time()
			);
			pdo_insert($this->table_camerapl, $data);
		}
		$result['info'] = "点赞成功！";
	}else{
		$dianz = pdo_fetch("SELECT id FROM " . tablename($this->table_camerapl) . " where schoolid = :schoolid AND carmeraid = :carmeraid AND userid = :userid AND type = :type", array(
			':schoolid' => trim($_GPC['schoolid']),
			':carmeraid' => trim($_GPC['videoid']),
			':userid' => trim($_GPC['userid']),
			':type' => 1,
		));
		if($dianz){ //有则取消点赞
			pdo_delete($this->table_camerapl, array('id' => $dianz['id']));
		}else{
			$temp = array(
				'weid' => trim($_GPC['weid']),
				'schoolid' => trim($_GPC['schoolid']),
				'carmeraid' => trim($_GPC['videoid']),
				'bj_id' => 0,
				'userid' => trim($_GPC['userid']),
				'type' => 1,
				'createtime' => time(),
			);
			pdo_insert($this->table_camerapl, $temp);
		}
		$result['info'] = "点赞成功！";
	}
	die ( json_encode ( $result ) );
}
if ($operation == 'videopl') {
	if($_GPC['thisop'] == 'mybj'){
		$data = array(
			'weid' => $_GPC['weid'],
			'schoolid' => $_GPC['schoolid'],
			'bj_id' => $_GPC['bj_id'],
			'carmeraid' => 0,
			'userid' => $_GPC['userid'],
			'conet' => trim($_GPC['commentContext']),
			'type' => 2,
			'createtime' => time()
		);
		pdo_insert($this->table_camerapl, $data);
		$plid = pdo_insertid();
		$result['data'] = $plid;
		$result['info'] = "评论成功！";
	}else{
		$temp = array(
			'weid' => trim($_GPC['weid']),
			'schoolid' => trim($_GPC['schoolid']),
			'carmeraid' => trim($_GPC['videoid']),
			'bj_id' => 0,
			'userid' => trim($_GPC['userid']),
			'conet' => trim($_GPC['commentContext']),
			'type' => 2,
			'createtime' => time(),
		);
		pdo_insert($this->table_camerapl, $temp);
		$plid = pdo_insertid();
		$result['data'] = $plid;
		$result['info'] = "评论成功！";
	}
	die ( json_encode ( $result ) );
}
if ($operation == 'delmypl') {
	$pl = pdo_fetch("SELECT id FROM " . tablename($this->table_camerapl) . " where id = :id ", array(':id' => trim($_GPC['id'])));
	if($pl['id']){
		pdo_delete($this->table_camerapl, array('id' => $pl['id']));
		$result['info'] = "删除成功";
	}else{
		$result['info'] = "非法请求";
	}
	die ( json_encode ( $result ) );
}

if ($operation == 'getcook') {
	$date = date('Y-m-d',$_GPC['time']);
	$riqi = explode ('-', $date);
	$starttime = mktime(0,0,0,$riqi[1],$riqi[2],$riqi[0]);
	$endtime = $starttime + 86399;
	$condition = " AND begintime < '{$starttime}' AND endtime > '{$endtime}'";
	$cook = pdo_fetch("SELECT * FROM " . tablename($this->table_cook) . " WHERE schoolid = :schoolid AND ishow = 1 $condition", array(':schoolid' => $_GPC['schoolid']));
	if($cook['monday'] || $cook['tuesday'] || $cook['wednesday'] || $cook['thursday'] || $cook['friday'] || $cook['saturday'] || $cook['sunday']){
		$week = date("w",$endtime);
			if($week ==1){
					$thecook = iunserializer($cook['monday']);
					$zc = $thecook['mon_zc'];
					$zcpic = $thecook['mon_zc_pic'];
					$zjc = $thecook['mon_zjc'];
					$zjcpic = $thecook['mon_zjc_pic'];
					$wc = $thecook['mon_wc'];
					$wcpic = $thecook['mon_wc_pic'];
					$wjc = $thecook['mon_wjc'];
					$wjcpic = $thecook['mon_wjc_pic'];
					$wwc = $thecook['mon_wwc'];
					$wwcpic = $thecook['mon_wwc_pic'];
			}
			if($week ==2){
					$thecook = iunserializer($cook['tuesday']);
					$zc = $thecook['tus_zc'];
					$zcpic = $thecook['tus_zc_pic'];
					$zjc = $thecook['tus_zjc'];
					$zjcpic = $thecook['tus_zjc_pic'];
					$wc = $thecook['tus_wc'];
					$wcpic = $thecook['tus_wc_pic'];
					$wjc = $thecook['tus_wjc'];
					$wjcpic = $thecook['tus_wjc_pic'];
					$wwc = $thecook['tus_wwc'];
					$wwcpic = $thecook['tus_wwc_pic'];
			}
			if($week ==3){
					$thecook = iunserializer($cook['wednesday']);
					$zc = $thecook['wed_zc'];
					$zcpic = $thecook['wed_zc_pic'];
					$zjc = $thecook['wed_zjc'];
					$zjcpic = $thecook['wed_zjc_pic'];
					$wc = $thecook['wed_wc'];
					$wcpic = $thecook['wed_wc_pic'];
					$wjc = $thecook['wed_wjc'];
					$wjcpic = $thecook['wed_wjc_pic'];
					$wwc = $thecook['wed_wwc'];
					$wwcpic = $thecook['wed_wwc_pic'];
			}
			if($week ==4){
					$thecook = iunserializer($cook['thursday']);
					$zc = $thecook['thu_zc'];
					$zcpic = $thecook['thu_zc_pic'];
					$zjc = $thecook['thu_zjc'];
					$zjcpic = $thecook['thu_zjc_pic'];
					$wc = $thecook['thu_wc'];
					$wcpic = $thecook['thu_wc_pic'];
					$wjc = $thecook['thu_wjc'];
					$wjcpic = $thecook['thu_wjc_pic'];
					$wwc = $thecook['thu_wwc'];
					$wwcpic = $thecook['thu_wwc_pic'];
			}
			if($week ==5){
					$thecook = iunserializer($cook['friday']);
					$zc = $thecook['fri_zc'];
					$zcpic = $thecook['fri_zc_pic'];
					$zjc = $thecook['fri_zjc'];
					$zjcpic = $thecook['fri_zjc_pic'];
					$wc = $thecook['fri_wc'];
					$wcpic = $thecook['fri_wc_pic'];
					$wjc = $thecook['fri_wjc'];
					$wjcpic = $thecook['fri_wjc_pic'];
					$wwc = $thecook['fri_wwc'];
					$wwcpic = $thecook['fri_wwc_pic'];
			}
			if($week ==6){
					$thecook = iunserializer($cook['saturday']);
					$zc = $thecook['sat_zc'];
					$zcpic = $thecook['sat_zc_pic'];
					$zjc = $thecook['sat_zjc'];
					$zjcpic = $thecook['sat_zjc_pic'];
					$wc = $thecook['sat_wc'];
					$wcpic = $thecook['sat_wc_pic'];
					$wjc = $thecook['sat_wjc'];
					$wjcpic = $thecook['sat_wjc_pic'];
					$wwc = $thecook['sat_wwc'];
					$wwcpic = $thecook['sat_wwc_pic'];
			}
			if($week ==7){
					$thecook = iunserializer($cook['sunday']);
					$zc = $thecook['sun_zc'];
					$zcpic = $thecook['sun_zc_pic'];
					$zjc = $thecook['sun_zjc'];
					$zjcpic = $thecook['sun_zjc_pic'];
					$wc = $thecook['sun_wc'];
					$wcpic = $thecook['sun_wc_pic'];
					$wjc = $thecook['sun_wjc'];
					$wjcpic = $thecook['sun_wjc_pic'];
					$wwc = $thecook['sun_wwc'];
					$wwcpic = $thecook['sun_wwc_pic'];
			}
			if($zcpic || $zjcpic || $wcpic || $wjcpic || $wwcpic){
				$result['data']['zc'] = $zc;
				$result['data']['zcpic'] = $zcpic;
				$result['data']['zjc'] = $zjc;
				$result['data']['zjcpic'] = $zjcpic;
				$result['data']['wc'] = $wc;
				$result['data']['wcpic'] = $wcpic;
				$result['data']['wjc'] = $wjc;
				$result['data']['wjcpic'] = $wjcpic;
				$result['data']['wwc'] = $wwc;
				$result['data']['wwcpic'] = $wwcpic;
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