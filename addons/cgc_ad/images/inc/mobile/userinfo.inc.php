<?php
	global $_W,$_GPC;

	     load()->classs('weixin.account');    

			if (!empty($_W['openid'])) {

				if($_W['account']['level']>3)
				{        							                
					$accObj = WeiXinAccount::create($_W['account']);
					$userinfo = $accObj->fansQueryInfo($_W['openid']);
				}
				else
				{
					if($_W['oauth_account']['level']>3)
					{
                       load()->classs('weixin.account');
						$accObj = WeiXinAccount::create($_W['oauth_account']);
						$userinfo = $accObj->fansQueryInfo($_W['openid']);
					}

				}

			}



	
			$op=$_GPC['op'];



			$code = $_GET['code'];



			if(empty($code)){

				if($userinfo['subscribe']==0)

				{

					$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('userinfo',array('op'=>$op,'id'=>$_GPC['id'],'pid'=>$_GPC['pid'],'foo'=>$_GPC['foo'],'quan_id'=>$_GPC['quan_id']));

					$callback = urlencode($url);

					$forward = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_userinfo&state=jiuye#wechat_redirect';



					header("Location: ".$forward);

				}

				else

				{

					//用户已经关注改公众号了

					$weid=$_W['uniacid'];

					$from_user=$_W['openid'];

					$fan_temp=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);



					if(!empty($userinfo) && !empty($userinfo['nickname']))

					{

						$userinfo['avatar'] = $userinfo['headimgurl'];

						unset($userinfo['headimgurl']);

						//

						$_SESSION['userinfo'] = base64_encode(iserializer($userinfo));

						$_SESSION['openid'] = $from_user;

						$_SESSION['jy_openid'] = $from_user;


						//开启了强制注册，自定义注册

						$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $weid));



						$data = array(

							'uniacid' => $weid,

							'salt' => random(8),

							'groupid' => $default_groupid,

							'createtime' => TIMESTAMP,

							'nickname' 		=> stripslashes($userinfo['nickname']),

							'avatar' 		=> $userinfo['avatar'],

							'gender' 		=> $userinfo['sex'],

							'nationality' 	=> $userinfo['country'],

							'resideprovince'=> $userinfo['province'] . '省',

							'residecity' 	=> $userinfo['city'] . '市',

						);

						$data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);



						if(empty($fan_temp))

						{

							pdo_insert('mc_members', $data);

							$uid = pdo_insertid();

						}

						else

						{

							pdo_update('mc_members' ,$data ,array('uid'=>$fan_temp['uid']));

							$uid=$fan_temp['uid'];

						}







						$record = array(

							'openid' 		=> $_W['openid'],

							'uid' 			=> $uid,

							'acid' 			=> $_W['acid'],

							'uniacid' 		=> $weid,

							'salt' 			=> random(8),

							'updatetime' 	=> TIMESTAMP,

							'nickname' 		=> $userinfo['nickname'],

							'follow' 		=> $userinfo['subscribe'],

							'followtime' 	=> $userinfo['subscribe_time'],

							'unfollowtime' 	=> 0,

							'tag' 			=> base64_encode(iserializer($userinfo))

						);

						$record['uid'] = $uid;

						if(empty($fan_temp))

						{

							pdo_insert('mc_mapping_fans', $record);

						}

						else

						{

							pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));

						}



					}

				}

			}

			else

			{

				//未关注，通过网页授权

				$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$_W['oauth_account']['key']."&secret=".$_W['oauth_account']['secret']."&code=".$code."&grant_type=authorization_code";

				load()->func('communication');

				$response = ihttp_get($url);

				$oauth = @json_decode($response['content'], true);



				$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$oauth['access_token']}&openid={$oauth['openid']}&lang=zh_CN";

				$response = ihttp_get($url);





				if (!is_error($response)) {



					$userinfo = array();

					$userinfo = @json_decode($response['content'], true);



					$userinfo['avatar'] = $userinfo['headimgurl'];

					unset($userinfo['headimgurl']);



					$_SESSION['userinfo'] = base64_encode(iserializer($userinfo));

					$_SESSION['openid'] = $oauth['openid'];

					$_SESSION['jy_openid'] = $oauth['openid'];

					$from_user=$oauth['openid'];



						if(!empty($userinfo) && !empty($userinfo['nickname']))

						{

							$acid=$_W['oauth_account']['acid'];

							$weid=$_W['uniacid'];

							// if($acid==$_W['account']['acid'])

							// {

							// 	$weid=$_W['account']['uniacid'];

							// }

							// else

							// {

							// 	$temp_uniacid=pdo_fetch("SELECT uniacid FROM ".tablename('account')." WHERE acid=".$acid);

							// 	$weid = $temp_uniacid['uniacid'];

							// }



							$fan_temp=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);

							//开启了强制注册，自定义注册

							$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $weid));

							$data = array(

								'uniacid' => $weid,

								'salt' => random(8),

								'groupid' => $default_groupid,

								'createtime' => TIMESTAMP,

								'nickname' 		=> stripslashes($userinfo['nickname']),

								'avatar' 		=> rtrim($userinfo['avatar'], '0') . 132,

								'gender' 		=> $userinfo['sex'],

								'nationality' 	=> $userinfo['country'],

								'resideprovince'=> $userinfo['province'] . '省',

								'residecity' 	=> $userinfo['city'] . '市',

							);

							$data['password'] = md5($oauth['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);



							if(empty($fan_temp))

							{

								pdo_insert('mc_members', $data);

								$uid = pdo_insertid();

							}

							else


							{

								pdo_update('mc_members' ,$data ,array('uid'=>$fan_temp['uid']));

								$uid=$fan_temp['uid'];

							}



							$record = array(

								'openid' 		=> $oauth['openid'],

								'uid' 			=> $uid,

								'acid' 			=> $acid,

								'uniacid' 		=> $weid,

								'salt' 			=> random(8),

								'updatetime' 	=> TIMESTAMP,

								'nickname' 		=> $userinfo['nickname'],

								'follow' 		=> $userinfo['subscribe'],

								'followtime' 	=> $userinfo['subscribe_time'],

								'unfollowtime' 	=> 0,

								'tag' 			=> base64_encode(iserializer($userinfo))

							);

							$record['uid'] = $uid;



							if(empty($fan_temp))

							{

								pdo_insert('mc_mapping_fans', $record);

							}

							else

							{

								$temp=pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));

							}



						}



				} else {

					message('微信授权获取用户信息失败,请重新尝试: ' . $response['message']);

				}

			}





		echo "<script>

					window.location.href = '".$this->createMobileUrl($op,array('id'=>$_GPC['id'],'foo'=>$_GPC['foo'],'pid'=>$_GPC['pid'],'quan_id'=>$_GPC['quan_id']))."';

				</script>";

	
