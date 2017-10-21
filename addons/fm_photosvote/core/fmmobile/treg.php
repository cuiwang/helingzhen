<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
		//$qiniu = iunserializer($reply['qiniu']);
			if ($_W['account']['level'] == 4) {
				$u_uniacid = $uniacid;
			}else{
				$u_uniacid = $cfg['u_uniacid'];
			}
		$now= time();
			//查询自己是否参与活动
		if(!empty($from_user)) {
			$mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
			$username = pdo_fetch("SELECT * FROM ".tablename($this->table_users_name)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
		}else{
			$fmdata = array(
				"success" => -1,
				"msg" => '获取用户openid失败，请关闭重试',
			);
			echo json_encode($fmdata);
			exit();
		}
		if($now <= $rbasic['bstart_time'] || $now >= $rbasic['bend_time']) {

			if ($now <= $rbasic['bstart_time']) {
				$fmdata = array(
					"success" => -1,
					"msg" => $rbasic['btipstart'],
				);
				echo json_encode($fmdata);
				exit();
			}
			if ($now >= $rbasic['bend_time']) {
				$fmdata = array(
					"success" => -1,
					"msg" => $rbasic['btipend'],
				);
				echo json_encode($fmdata);
				exit();
			}
		}
		if (empty($mygift)) {
			$uid = pdo_fetch("SELECT uid FROM ".tablename($this->table_users)." WHERE rid = :rid ORDER BY uid DESC, id DESC LIMIT 1", array(':rid' => $rid));
			$insertdata = array(
				'rid'       => $rid,
				'uid'       => $uid['uid'] + 1,
				'uniacid'      => $uniacid,
				'from_user' => $from_user,
				'unionid' => $unionid,
				'avatar'    => $avatar,
				'nickname'  => $nickname,
				'sex'  => $sex,
				'photosnum'  => '0',
				'xnphotosnum'  => '0',
				'hits'  => '1',
				'xnhits'  => '1',
				'yaoqingnum'  => '0',
				'createip' => getip(),
				'lastip' => getip(),
				'status'  => 3,
				'sharetime' => $now,
				'createtime'  => $now,
			);
			$insertdata['iparr'] = getiparr($insertdata['lastip']);
			pdo_insert($this->table_users, $insertdata);
			pdo_update($this->table_reply_display, array('csrs_total' => $rdisplay['csrs_total']+1), array('rid' => $rid));

		}

		if ($_GPC['autosave'] == 1) {
			$name = $_GPC['value_name'];
			$value = $_GPC['value_val'];
			$regtitlearr = iunserializer($rdisplay['regtitlearr']);
			if (!empty($value)) {
				$mygift = pdo_fetch("SELECT realname,mobile,status FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
				if($mygift['status'] == '1') {
					$msg = '您已经审核通过，报名资料无法修改！';
								$fmdata = array(
									"success" => -1,
									"flag" => 2,
									"msg" => $msg,
								);
								echo json_encode($fmdata);
								exit();
				}
				if($name == 'realname'){
					if ($mygift['realname']) {
						if ($mygift['realname'] == $value) {

						}else {
							$realname = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE realname = :realname and rid = :rid", array(':realname' => $value,':rid' => $rid));
							if (!empty($realname)) {
								//message('您的真实姓名已经参赛，请重新填写！');
								$msg = '您的真实姓名已经参赛，请重新填写！';
								$fmdata = array(
									"success" => -1,
									"flag" => 2,
									"msg" => $msg,
								);
								echo json_encode($fmdata);
								exit();
							}
						}

					}

				}
				if ($name == 'mobile') {
					if(!preg_match(REGULAR_MOBILE, $value)) {
						$msg = '手机号格式为 11 位数字。';
						$fmdata = array(
							"success" => -1,
							"flag" => 2,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit();
					}
					if ($mygift['mobile']) {
						if ($mygift['mobile'] == $value) {

						}else {
							$ymobile = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE mobile = :mobile and rid = :rid", array(':mobile' => $value,':rid' => $rid));
							if(!empty($ymobile)) {
								$msg = '非常抱歉，此手机号码已经被注册，你需要更换注册手机号！';
								$fmdata = array(
									"success" => -1,
									"flag" => 2,
									"msg" => $msg,
								);
								echo json_encode($fmdata);
								exit();
							}
						}
					}
				}
				$pname = preg_match('/_parent/i', $name);
				$cname = preg_match('/_child/i', $name);
				$tname = preg_match('/_threec/i', $name);
				if ($pname) {
					$name = 'tagpid';
				}
				if ($cname) {
					$name = 'tagid';
				}
				if ($tname) {
					$name = 'tagtid';
				}
				if ($mygift['status'] != '' && $mygift['status'] != 3) {
					$status = $mygift['status'];
				}else{
					$status = $rvote['tpsh'] == 1 ? '2' : '1';
				}
				$data = array();
				$data[$name] = $value;
				$data['status'] = $status;

				pdo_update($this->table_users, $data , array('rid' => $rid, 'from_user' => $from_user));
				$fmdata = array(
					"success" => 1,
					"msg" => '自动保存成功',
				);
				echo json_encode($fmdata);
				exit();
			}else{
				$fmdata = array(
					"success" => -1,
					"flag" => 1,
					"msg" => '不能为空',
				);
				if ($name == 'realname') {
					$fmdata['isopenname'] = $rdisplay['is'.$name];
				}else{
					$fmdata['isopenname'] = $regtitlearr['open_'.$name];
				}
				echo json_encode($fmdata);
				exit();
			}


		}


		load()->func('file');
		if ($_GPC['upphotosone'] == 'start') {
			if($mygift['status'] == '1') {
					$msg = '您已经审核通过，报名资料无法修改！';
								$fmdata = array(
									"success" => -1,
									"flag" => 2,
									"msg" => $msg,
								);
								echo json_encode($fmdata);
								exit();
				}
			$base64=file_get_contents("php://input"); //获取输入流
			$base64=json_decode($base64,1);
			$data = $base64['base64'];

			if($data){
				$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');

				preg_match("/data:image\/(.*?);base64/",$data,$res);
				$ext = $res[1];
				$setting = $_W['setting']['upload']['image'];
				if (!in_array(strtolower($ext), $setting['extentions']) || in_array(strtolower($ext), $harmtype)) {
					$fmdata = array(
						"success" => -1,
						"msg" => '系统不支持您上传的文件（扩展名为：'.$ext.'）,请上传正确的图片文件',
					);
					echo json_encode($fmdata);
					die;
				}

				$photoname = 'FMFetchi'.date('YmdHis').random(16);
				$nfilename = $photoname.'.'.$ext;
				$updir = '../attachment/images/'.$uniacid.'/'.date("Y").'/'.date("m").'/';
				mkdirs($updir);

				//$data = preg_replace("/^data:image\/(.*);base64,/","",$data);
				$darr = explode("base64,", $data,30);
				$data = end($darr);
				if (!$data) {
					$fmdata = array(
						"success" => -1,
						"msg" => $data.'当前图片宽度大于3264px,系统无法识别为其生成！',
					);
					echo json_encode($fmdata);
					exit;
				}

				if (file_put_contents($updir.$nfilename,base64_decode($data))===false) {
					$fmdata = array(
						"success" => -1,
						"msg" => '上传错误',
					);
					echo json_encode($fmdata);
					exit;
				}else{
					$mid = $_GPC['mid'];

					$photosarrnum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
					$username = pdo_fetch("SELECT photoname,photos FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid AND rid = :rid AND photoname =:photoname LIMIT 1", array(':uniacid' => $uniacid,':rid' => $rid,':photoname' => $_GPC['photoname']));


					if (!$qiniu['isqiniu']) {
						$picurl = $updir.$nfilename;
						if (!empty($username['photoname'])) {
							file_delete($username['photos']);
							//file_delete($updir.$nfilename);
							$insertdata = array(
								'photoname' => $photoname,
								'createtime' => $now,
								"mid" => $mid,
								"photos" => $picurl,
								'imgpath' => $picurl,
							);
							pdo_update($this->table_users_picarr, $insertdata, array('rid' => $rid,'from_user' => $from_user, 'id'=>$mid));
							$lastmid = $mid;
						}else{
							if ($photosarrnum >= $rvote['tpxz']) {
								$fmdata = array(
									"success" => -1,
									"msg" => '抱歉，你只能上传 '.$rvote['tpxz'].' 张图片。',
								);
								echo json_encode($fmdata);
								exit;
							}
							$insertdata = array(
								'rid'       => $rid,
								'uniacid'      => $uniacid,
								'from_user' => $from_user,
								'photoname' => $photoname,
								'status' => 1,
								'createtime' => $now,
								'imgpath' => $picurl,
							);
							if ($_GPC['photoname'] == 'fm') {
								$insertdata['isfm'] = 1;
							}else{
								$insertdata['isfm'] = 0;
							}

							$insertdata['photos'] = $picurl;
							pdo_insert($this->table_users_picarr, $insertdata);
							$lastmid = pdo_insertid();
							pdo_update($this->table_users_picarr, array('mid' => $lastmid), array('rid' => $rid,'from_user' => $from_user, 'id'=>$lastmid));
						}

						$addlastmid = $lastmid + 1;
						$photosarrnum = $photosarrnum + 1;

						$fmdata = array(
							"success" => 1,
							"lastmid" => $lastmid,
							"addlastmid" => $addlastmid,
							"photosarrnum" => $photosarrnum,
							"msg" => '上传成功！',
							"imgurl" => $picurl,
						);
						echo json_encode($fmdata);
						exit();
					}else {
						$qiniu['upurl'] = $_W['siteroot'].'attachment/images/'.$uniacid.'/'.date("Y").'/'.date("m").'/'.$nfilename;
						$picurl = $updir.$nfilename;
						$username['type'] = '3';
						$qiniuimages = $this->fmqnimages($nfilename, $qiniu, $mid, $username);
						if ($qiniuimages['success'] == '-1') {
							$fmdata = array(
								"success" => -1,
								"msg" => $qiniuimages['msg'],
							);
							echo json_encode($fmdata);
							exit();
						}else {

							if (!empty($username['photoname']) && $_GPC['photoname'] != 'fm') {

								file_delete($updir.$username['photoname']);
								file_delete($updir.$nfilename);
								$insertdata = array(
									'imgpath' => $picurl,
									'photoname' => $photoname,
									'createtime' => $now,
									"mid" => $mid,
									"photos" => $qiniuimages['picarr_'.$mid],
								);
								pdo_update($this->table_users_picarr, $insertdata, array('rid' => $rid,'from_user' => $from_user, 'id' => $mid));
								$lastmid = $mid;
							}else{
								if ($photosarrnum >= $rvote['tpxz']) {
									$fmdata = array(
										"success" => -1,
										"msg" => '抱歉，你只能上传 '.$rvote['tpxz'].' 张图片。',
									);
									echo json_encode($fmdata);
									exit;
								}
								$insertdata = array(
									'rid'       => $rid,
									'uniacid'      => $uniacid,
									'from_user' => $from_user,
									'photoname' => $photoname,
									'photos' => $qiniuimages['picarr_'.$mid],
									'imgpath' => $picurl,
									'status' => 1,
									'createtime' => $now,
								);
								if ($_GPC['photoname'] == 'fm') {
									$insertdata['isfm'] = 1;
								}else{
									$insertdata['isfm'] = 0;
								}
								pdo_insert($this->table_users_picarr, $insertdata);
								//更新mid
								$lastmid = pdo_insertid();
								pdo_update($this->table_users_picarr, array('mid' => $lastmid), array('rid' => $rid,'from_user' => $from_user, 'id'=>$lastmid));

								//file_delete($updir.$nfilename);
							}
							$addlastmid = $lastmid + 1;
							$photosarrnum = $photosarrnum + 1;

							$fmdata = array(
								"success" => 1,
								"lastmid" => $lastmid,
								"addlastmid" => $addlastmid,
								"photosarrnum" => $photosarrnum,
								"msg" => $qiniuimages['msg'],
								"imgurl" => $insertdata['photos'],
							);
							echo json_encode($fmdata);
							exit();


						}
					}
				}

			}else{
				$fmdata = array(
					"success" => -1,
					"msg" =>'没有发现上传图片',
				);
				echo json_encode($fmdata);
				exit();
			}
		}
		if ($_GPC['upaudios'] == 'start') {
			if($mygift['status'] == '1') {
					$msg = '您已经审核通过，报名资料无法修改！';
								$fmdata = array(
									"success" => -1,
									"flag" => 2,
									"msg" => $msg,
								);
								echo json_encode($fmdata);
								exit();
				}
			$audiotype = $_GPC['audiotype'];
			$upmediatmp = $_FILES['files']["tmp_name"];


			if ($qiniu['videologo']) {
				$qiniu['videologo'] = toimage($qiniu['videologo']);
			}

			if($upmediatmp){
				$ext = $_FILES['files']["type"];
				$nfilename = 'FM'.date('YmdHis').random(8).$_FILES['files']["name"];

				$updir = '../attachment/audios/'.$uniacid.'/'.date("Y").'/'.date("m").'/';
				mkdirs($updir);
				if ($mygift[$audiotype]) {
					file_delete($mygift[$audiotype]);
				}
				$music = fm_file_upload($_FILES['files'], $audiotype);
				if (!$music['success']) {
					$fmdata = array(
						"success" => $music['errno'],
						"msg" => $music['message'],
					);
					echo json_encode($fmdata);
					exit();
				}

				$videopath = $music['path'];

				if ($qiniu['isqiniu']) {	//开启七牛存储

					$upmediatmp = $_W['siteroot'].'attachment/'.$videopath;
					$qiniuaudios = $this->fmqnaudios($nfilename, $qiniu, $upmediatmp, $audiotype, $username);

					$nfilenamefop = $qiniuaudios['nfilenamefop'];
					if ($qiniuaudios['success'] == '-1') {
					//	var_dump($err);
						$fmdata = array(
							"success" => -1,
							"msg" => $qiniuaudios['msg'],
						);
						echo json_encode($fmdata);
						exit();
					} else {
						$insertdata = array();

						if ($qiniuaudios['success'] == '-2') {
							//var_dump($err);
							$fmdata = array(
									"success" => -1,
									"msg" => $err,
								);
								echo json_encode($fmdata);
								exit();
						} else {
							$insertdata[$audiotype] = $qiniuaudios[$audiotype];
							pdo_update($this->table_users, $insertdata, array('from_user'=>$from_user, 'rid' => $rid));
							if ($username) {
								$insertdataname = array();
								$insertdataname[$audiotype.'name'] = $nfilename;
								$insertdataname[$audiotype.'namefop'] = $nfilenamefop;
								pdo_update($this->table_users_name, $insertdataname, array('from_user'=>$from_user, 'rid' => $rid));
							}else {
								$insertdataname = array(
									'rid'       => $rid,
									'uniacid'      => $uniacid,
									'from_user' => $from_user,
								);
								$insertdataname[$audiotype.'name'] = $nfilename;
								$insertdataname[$audiotype.'namefop'] = $nfilenamefop;
								pdo_insert($this->table_users_name, $insertdataname);
							}
							$fmimage = $this->getpicarr($uniacid,$rid, $mygift['from_user'],1);
							$pimage = $this->getphotos($fmimage['photos'], $mygift['avatar'], $rbasic['picture']);
							$fmdata = array(
								"success" => 1,
								"pimage" => $pimage,
								"imgurl" => $insertdata[$audiotype],
								"msg" => '上传成功！',

							);
							echo json_encode($fmdata);
							exit();

						}
					}
				}else {
					$insertdata = array();
					$insertdata[$audiotype] = $music['path'];

					pdo_update($this->table_users, $insertdata, array('from_user'=>$from_user, 'rid' => $rid));
					$fmimage = $this->getpicarr($uniacid,$rid, $mygift['from_user'],1);
					$pimage = $this->getphotos($fmimage['photos'], $mygift['avatar'], $rbasic['picture']);
					$fmdata = array(
						"success" => 1,
						"pimage" => $pimage,
						"imgurl" => $insertdata[$audiotype],
						"msg" => '上传成功！',
					);
					echo json_encode($fmdata);
					exit();
				}
			}else{

				if ($_GPC[$audiotype] && stristr($username[$audiotype.'namefop'],$_GPC[$audiotype])) {
					if ($qiniu['isqiniu']) {	//开启七牛存储

						$upurl = $_GPC[$audiotype];
						$qiniuaudios = $this->fmqnaudios($nfilename, $qiniu, $upurl,$audiotype, $username);
						$nfilenamefop = $qiniuaudios['nfilenamefop'];
						if ($qiniuaudios['success'] == '-1') {
							//	var_dump($err);
								$fmdata = array(
									"success" => -1,
									"msg" => $qiniuaudios['msg'],
								);
								echo json_encode($fmdata);
								exit();
							} else {
								if ($qiniuaudios['success'] == '-2') {
									//var_dump($err);
									$fmdata = array(
										"success" => -1,
										"msg" => $err,
									);
									echo json_encode($fmdata);
									exit();
								} else {
									//var_dump($ret);
									$insertdata[$audiotype] = $qiniuaudios[$audiotype];
									pdo_update($this->table_users, $insertdata, array('from_user'=>$from_user, 'rid' => $rid));
									if ($username) {
										$insertdataname = array();
										$insertdataname[$audiotype.'name'] = $nfilename;
										$insertdataname[$audiotype.'namefop'] = $nfilenamefop;
										pdo_update($this->table_users_name, $insertdataname, array('from_user'=>$from_user, 'rid' => $rid));
									}else {
										$insertdataname = array(
											'rid'       => $rid,
											'uniacid'      => $uniacid,
											'from_user' => $from_user,
										);
										$insertdataname[$audiotype.'name'] = $nfilename;
										$insertdataname[$audiotype.'namefop'] = $nfilenamefop;
										pdo_insert($this->table_users_name, $insertdataname);
									}
									$fmimage = $this->getpicarr($uniacid,$rid, $mygift['from_user'],1);
									$pimage = $this->getphotos($fmimage['photos'], $mygift['avatar'], $rbasic['picture']);
									$fmdata = array(
										"success" => 1,
										"pimage" => $pimage,
										"imgurl" => $insertdata[$audiotype],
										"msg" => '上传成功！',
									);
									echo json_encode($fmdata);
									exit();

								}
							}
					}else {
						$insertdata = array();
						$insertdata[$audiotype] = $_GPC[$audiotype];
						pdo_update($this->table_users, $insertdata, array('from_user'=>$from_user, 'rid' => $rid));
						$fmimage = $this->getpicarr($uniacid,$rid, $mygift['from_user'],1);
						$pimage = $this->getphotos($fmimage['photos'], $mygift['avatar'], $rbasic['picture']);
						$fmdata = array(
							"success" => 1,
							"pimage" => $pimage,
							"imgurl" => $_GPC[$audiotype],
							"msg" => '上传成功！',
						);
						echo json_encode($fmdata);
						exit();
					}




				}else {
					if ($audiotype == 'music') {
						$msg = '请上传音频或者填写远程音频地址';
					}elseif ($audiotype == 'vedio') {
						$msg = '请上传视频或者填写远程视频地址';
					}

					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					die;
				}
			}
		}

		if ($_GPC['treg'] == 1) {
			$mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
			if($mygift['status'] == '1') {
					$msg = '您已经审核通过，报名资料无法修改！';
								$fmdata = array(
									"success" => -1,
									"msg" => $msg,
								);
								echo json_encode($fmdata);
								exit();
				}
			if($rdisplay['isrealname']){
				if (empty($_GPC['realname'])) {
					//message('您的真实姓名没有填写，请填写！');
					$msg = '您的真实姓名没有填写，请填写！';
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit();
				}

				if ($mygift['realname']) {
					if ($mygift['realname'] == $_GPC['realname']) {

					}else {
						$realname = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE realname = :realname and rid = :rid", array(':realname' => $_GPC['realname'],':rid' => $rid));
						if (!empty($realname)) {
							//message('您的真实姓名已经参赛，请重新填写！');
							$msg = '您的真实姓名已经参赛，请重新填写！';
							$fmdata = array(
								"success" => -1,
								"msg" => $msg,
							);
							echo json_encode($fmdata);
							exit();
						}
					}

				}

			}
			$regtitlearr = iunserializer($rdisplay['regtitlearr']);
			if($regtitlearr['open_mobile']){
				if(!preg_match(REGULAR_MOBILE, $_GPC['mobile'])) {
					//message('必须输入手机号，格式为 11 位数字。');
					$msg = '必须输入手机号，格式为 11 位数字。';
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit();
				}
				if ($mygift['mobile']) {
					if ($mygift['mobile'] == $_GPC['mobile']) {

					}else {
						$ymobile = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE mobile = :mobile and rid = :rid", array(':mobile' => $_GPC['mobile'],':rid' => $rid));
						if(!empty($ymobile)) {
							$msg = '非常抱歉，此手机号码已经被注册，你需要更换注册手机号！';
							$fmdata = array(
								"success" => -1,
								"msg" => $msg,
							);
							echo json_encode($fmdata);
							exit();
						}
					}
				}
			}
			if ($rvote['mediatype']) {
				$photos = pdo_fetch("SELECT id FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
			    if (empty($photos)) {
					$msg = '请上传图片！';
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit();
				}
			}
			$mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
			if ($rvote['mediatypem']) {
				if ($rvote['ismediatypem']) {
					if (empty($mygift['voice']) && empty($mygift['music'])) {
						$msg = '请上传或者录制好声音！';
						$fmdata = array(
							"success" => -1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit();
					}
				}
			}
			if ($rvote['mediatypev']) {
				if ($rvote['ismediatypev']) {
					if (empty($mygift['vedio']) && empty($_GPC['youkuurl'])) {
						$msg = '请上传视频或者填写网络视频地址！';
						$fmdata = array(
							"success" => -1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit();
					}
				}
			}

		    $now = time();
				preg_match('/[a-zA-z]+:\/\/[^\s]*/', $_GPC["youkuurl"], $matchs);
				$tyurl = str_replace("&quot;", '', $matchs[0]);
				if ($mygift['status'] != '' && $mygift['status'] != 3) {
					$status = $mygift['status'];
				}else{
					$status = $rvote['tpsh'] == 1 ? '2' : '1';
				}
				$udata = array(
					//'avatar'    => $avatar,
					//'nickname'  => $nickname,
					'unionid' => $unionid,
					'sex'  => $sex,
					'description'  =>  htmlspecialchars_decode($_GPC['description']),
					'photoname'  => htmlspecialchars_decode($_GPC["photoname"]),
					'youkuurl'  => $tyurl,
					'realname'  => htmlspecialchars_decode($_GPC["realname"]),
					'mobile'  => $_GPC["mobile"],
					'weixin'  => htmlspecialchars_decode($_GPC["weixin"]),
					'qqhao'  => $_GPC["qqhao"],
					'email'  => $_GPC["email"],
					'job'  => htmlspecialchars_decode($_GPC["job"]),
					'xingqu'  => htmlspecialchars_decode($_GPC["xingqu"]),
					'address'  => htmlspecialchars_decode($_GPC["address"]),
					//'tagid' => $_GPC['tagid'],
					'tagpid' => empty($_GPC['tagpid']) ? $mygift['tagpid'] : $_GPC['tagpid'],
					//'tagtid' => $_GPC['tagtid'],
					'schoolid' => $_GPC['school'],
					'sourceid' => $_GPC['source'],
					'status'  => $status,
					'lastip' => getip(),
					'lasttime' => $now,
				);

				load()->func('communication');
				if (!empty($mygift['music'])) {
					$geitime = ihttp_get($mygift['music'].'?avinfo');
					$t = @json_decode($geitime['content'], true);
					$udata['musictime'] = $t['format']['duration'];
				}

				if (!empty($mygift['voice'])) {
					$geitime = ihttp_get($mygift['voice'].'?avinfo');
					$t = @json_decode($geitime['content'], true);
					$udata['voicetime'] = $t['format']['duration'];
				}
				if (!empty($mygift['vedio'])) {
					$geitime = ihttp_get($mygift['vedio'].'?avinfo');
					$t = @json_decode($geitime['content'], true);
					$udata['vediotime'] = $t['format']['duration'];
				}



				//$insertdata[$audiotype.'time'] = $t['format']['duration'];
				$udata['iparr'] = getiparr($udata['lastip']);

				pdo_update($this->table_users, $udata , array('rid' => $rid, 'from_user' => $from_user));
			    if($rvote['isfans']){
			        if($avatar){
				        fans_update($from_user, array(
					        'avatar' => $avatar,
		                ));
				    }
					if($mynickname){
				        fans_update($from_user, array(
					        'nickname' => $mynickname,
		                ));
				    }
					if($rdisplay['isrealname']){
				        fans_update($from_user, array(
					        'realname' => $realname,
		                ));
				    }
				    if($rdisplay['ismobile']){
				        fans_update($from_user, array(
					        'mobile' => $mobile,
		                ));
				    }
				    if($rdisplay['isqqhao']){
				        fans_update($from_user, array(
					        'qq' => $qqhao,
		                ));
				    }
				    if($rdisplay['isemail']){
				        fans_update($from_user, array(
					        'email' => $email,
		                ));
				    }
				    if($rdisplay['isaddress']){
				        fans_update($from_user, array(
					        'address' => $address,
		                ));
				    }
			    }
				if ($_W['account']['level'] == 4) {
					$u_uniacid = $uniacid;
				}else{
					$u_uniacid = $cfg['u_uniacid'];
				}

				if (empty($mygift['realname'])) {
					if ($rvote['tpsh'] == 1) {
						$msg = '恭喜你报名成功，现在进入审核';
					}else {
						$msg = '恭喜你报名成功！';
						//增加积分
						$this->addjifen($rid, $from_user, $tfrom_user,array($nickname,$avatar,$sex),array($uniacid),'reg');
					}
					if ($rvote['regpay'] == 1 && $_GPC['regpay'] == 1 && empty($mygift['ordersn'])) {

						//付款
						$params = $_GPC['params'];
						$datas = array(
							'uniacid' => $uniacid,
							'weid' => $uniacid,
							'rid' => $rid,
							'from_user' => $params['user'],
							'tfrom_user' => $tfrom_user,
							'ordersn' => $params['ordersn'],
							'payyz' => '',
							'title' => $params['title'],
							'price' => $params['fee'],
							'realname' => $nickname,
							'status' => '0',
							'paytype' => '3',
							'createtime' => time(),
						);
						pdo_insert($this->table_order, $datas);
						$log = pdo_get('core_paylog', array('uniacid' => $uniacid, 'module' => $params['module'], 'tid' => $params['tid']));
						//在pay方法中，要检测是否已经生成了paylog订单记录，如果没有需要插入一条订单数据
						//未调用系统pay方法的，可以将此代码放至自己的pay方法中，进行漏洞修复
						if (empty($log)) {
					        $log = array(
					                'uniacid' => $uniacid,
					                'acid' => $_W['acid'],
					                'openid' => $params['user'],
					                'module' => $params['module'], //模块名称，请保证$this可用
					                'tid' => $params['tid'],
					                'fee' => $params['fee'],
					                'card_fee' => $params['fee'],
					                'status' => '0',
					                'is_usecard' => '0',
					        );
					        pdo_insert('core_paylog', $log);
						}
						$msg = '保存成功，请支付报名费用';

					}else{
						if ($_W['account']['level'] == 4){
							$this->sendMobileRegMsg($from_user, $rid, $uniacid);
						}
					}



				}else {

					if ($mygift['status'] == 1) {
						$msg = '保存成功';
					}else{
						$msg = '您的申请已提交成功，等待审核';
					}
					$payordersn = pdo_fetch("SELECT ordersn FROM " . tablename($this->table_order) . " WHERE rid='{$rid}' AND from_user = :from_user AND paytype = 3  ORDER BY id DESC,paytime DESC limit 1", array(':from_user'=>$from_user));
					if ($rvote['regpay'] == 1 && $_GPC['regpay'] == 1 && empty($mygift['ordersn']) && empty($payordersn['ordersn'])) {
						//付款
						$params = $_GPC['params'];
						$datas = array(
							'uniacid' => $uniacid,
							'weid' => $uniacid,
							'rid' => $rid,
							'from_user' => $params['user'],
							'tfrom_user' => $tfrom_user,
							'ordersn' => $params['ordersn'],
							'payyz' => '',
							'title' => $params['title'],
							'price' => $params['fee'],
							'realname' => $nickname,
							'status' => '0',
							'paytype' => '3',
							'createtime' => time(),
						);
						pdo_insert($this->table_order, $datas);

						$log = pdo_get('core_paylog', array('uniacid' => $uniacid, 'module' => $params['module'], 'tid' => $params['tid']));
						//在pay方法中，要检测是否已经生成了paylog订单记录，如果没有需要插入一条订单数据
						//未调用系统pay方法的，可以将此代码放至自己的pay方法中，进行漏洞修复
						if (empty($log)) {
					        $log = array(
					                'uniacid' => $uniacid,
					                'acid' => $_W['acid'],
					                'openid' => $params['user'],
					                'module' => $params['module'], //模块名称，请保证$this可用
					                'tid' => $params['tid'],
					                'fee' => $params['fee'],
					                'card_fee' => $params['fee'],
					                'status' => '0',
					                'is_usecard' => '0',
					        );
					        pdo_insert('core_paylog', $log);
						}
						$msg = '保存成功，请支付报名费用';
					}

				}
				if ($_GPC['templates'] == 'stylebase') {
					$linkurl = $_W['siteroot'].'app/'.$this->createMobileUrl('photosvote', array('rid' => $rid,'tfrom_user' => $from_user));
				}else {
					$linkurl = $_W['siteroot'].'app/'.$this->createMobileUrl('tuser', array('rid' => $rid,'tfrom_user' => $from_user));
				}
				$fmdata = array(
					"success" => 1,
					"msg" => $msg,
					"linkurl" => $linkurl,
				);
				echo json_encode($fmdata);
				exit();
		}
