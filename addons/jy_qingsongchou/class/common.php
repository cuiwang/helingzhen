<?php

/**
 * 常用基本类
 * 构造函数
 * 调取系统信息
 * 检测微信授权状态
 */

  defined('IN_IA') or exit('Access Denied');
  !defined('PEMS') && define('PEMS', PEM.$_W['uniacid']); //证书路径 *
  include GARCIA_PATH.'class/wechatapi.php';
  include IA_ROOT.'/framework/library/phpexcel/PHPExcel.php';
  include GARCIA_PATH.'class/emoji.php';
  include GARCIA_PATH.'class/cookie.class.php';
  include GARCIA_PATH.'class/qrcode.class.php';
  class Common extends WeModuleSite{



         public  function  Init(){
             global $_W,$_GPC;

             $this->weid = $_W['uniacid'];
             $this->cookies = new Cookies('jy_qsc_'.$_W['uniacid'],0,GARCIA_MD5);

               if(GARCIA_DEVELOP){
                 include GARCIA_PATH."upgrade.php";
               }

              $this->__ckey = GARCIA_MD5.$this->weid.$_SERVER["REMOTE_ADDR"];
              $this->_GetSysInfo();

              if ($_W['container'] == 'wechat') {
                  $this->modal = 'wechat';

              }else{
                  if ($this->check_wap()&&$_GPC['do']!='api')
                  {
                       $this->modal = 'phone';
                       $this->_checkLogin();
                  }else{
                       $this->modal = 'pc';
                       $this->_checkLogin();
                  }
              }
              $this->_links();
         }

         // check if wap
         private function check_wap(){
           // 先检查是否为wap代理，准确度高
           if(stristr($_SERVER['HTTP_VIA'],"wap")){
             return true;
           }
           // 检查浏览器是否接受 WML.
           elseif(strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0){
             return true;
           }
           //检查USER_AGENT
           elseif(preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])){
             return true;
           }
           else{
             return false;
           }
         }
         protected function _Auth()
         	{
         		global $_GPC, $_W;
         		 $weid=$_W['uniacid'];


         		if ($_W['container'] != 'wechat') {
         			return $this->returnError('请将该页面分享到微信打开！', '', 'error');
         		}
         		if (!isset($_SESSION['jy_openid']) || empty($_SESSION['jy_openid']) || !isset($_SESSION['uid']) || empty($_SESSION['uid'])){
         			unset($uid);
         		}
         		else
         		{
         			$from_user=$_SESSION['jy_openid'];

         			$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
         			if(empty($member_temp['nickname']) || $member_temp['uid']==0)
         			{
         				unset($uid);
         			}
         			else
         			{
         				$uid=$member_temp['uid'];
         				$_W['member']['uid']=$uid;
         				unset($member_temp);
         				$huiyuan_temp=pdo_fetch("SELECT nickname FROM ".tablename('mc_members')." WHERE uniacid=".$weid." AND  uid=".$uid);
         				if(empty($huiyuan_temp['nickname']))
         				{
         					unset($uid);
         				}
         			}
         		}
            $_SESSION['jy_openid']=$_W['openid'];
            $_SESSION['openid']=$_W['openid'];

            //防止循环
            if(empty($uid)){
                $uid = $_SESSION['uid'];
            }
         		if(empty($uid))
         		{

         			if (!empty($_W['openid']) && intval($_W['account']['level']) >= 3) {
         				$accObj = WeiXinAccount::create($_W['account']);
         				$userinfo = $accObj->fansQueryInfo($_W['openid']);
         			}

         			$state = '9yeid-'.$_W['session_id'];
         			$_SESSION['dest_url'] = base64_encode($_SERVER['QUERY_STRING']);
              $op=$_GPC['op'];
              $code = $_GET['code'];
              $from_user=$_W['openid'];

         			if(empty($code)){
         				if($userinfo['subscribe']==0)
         				{
         					$url = $_W['siteroot'] . 'app/index.php?' . $_SERVER['QUERY_STRING'];
         					$callback = urlencode($url);
         					$forward = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_base&state='.$state.'#wechat_redirect';
         					header("Location: ".$forward);
         				}
         				else
         				{

         			  		$weid=$_W['uniacid'];

                   $fans_temp=pdo_fetchall("SELECT uniacid FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' ");

                   $fan_temp = '';
                   if(empty($fans_temp[0])){
                      $_isUnique =  false;
                   }else{
                       $_isUnique = 'not';
                   }

                   foreach ($fans_temp as $key => $value) {
                       if($value['uniacid']==$weid){
                           $fan_temp=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
                           $_isUnique = true;
                       }
                   }


         					if(!empty($userinfo) && !empty($userinfo['headimgurl']) && !empty($userinfo['nickname']))
         					{
         						$userinfo['avatar'] = $userinfo['headimgurl'];
         						unset($userinfo['headimgurl']);

         						//开启了强制注册，自定义注册
         						$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));

         						$data = array(
         							'uniacid' => $_W['uniacid'],
         							'email' => md5($_W['openid']).'@9yetech.com'.$op,
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
         							'uniacid' 		=> $_W['uniacid'],
         							'salt' 			=> random(8),
         							'updatetime' 	=> TIMESTAMP,
         							'nickname' 		=> stripslashes($userinfo['nickname']),
         							'follow' 		=> $userinfo['subscribe'],
         							'followtime' 	=> $userinfo['subscribe_time'],
         							'unfollowtime' 	=> 0,
         							'tag' 			=> base64_encode(iserializer($userinfo))
         						);
         						$record['uid'] = $uid;
         						if(empty($fan_temp)&&$_isUnique!='not'&&!$_isUnique)
         						{
         							pdo_insert('mc_mapping_fans', $record);
         						}
         						else
         						{
         							pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));
         						}
         						$_SESSION['jy_openid']=$_W['openid'];
         						$_SESSION['openid']=$_W['openid'];
         						$_SESSION['uid']=$uid;
                    $_SESSION['subscribe']=$userinfo['subscribe'];
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

         						if(!empty($userinfo) && !empty($userinfo['avatar']) && !empty($userinfo['nickname']))
         						{
         							$weid=$_W['uniacid'];

         							$fan_temp=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
         							//开启了强制注册，自定义注册
         							$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
         							$data = array(
         								'uniacid' => $_W['uniacid'],
         								'email' => md5($_W['openid']).'@9yetech.com'.$op,
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
         								'openid' 		=> $oauth['openid'],
         								'uid' 			=> $uid,
         								'acid' 			=> $_W['acid'],
         								'uniacid' 		=> $_W['uniacid'],
         								'salt' 			=> random(8),
         								'updatetime' 	=> TIMESTAMP,
         								'nickname' 		=> stripslashes($userinfo['nickname']),
         								'follow' 		=> $userinfo['subscribe'],
         								'followtime' 	=> $userinfo['subscribe_time'],
         								'unfollowtime' 	=> 0,
         								'tag' 			=> base64_encode(iserializer($userinfo))
         							);
         							$record['uid'] = $uid;

         							if(empty($fan_temp)&&!$_isUnique)
         							{
         								pdo_insert('mc_mapping_fans', $record);
         							}
         							else if($_isUnique=='not'){

                      }
                      else
         							{
         								$temp=pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));
         							}
         							$_SESSION['jy_openid']=$oauth['openid'];
         							$_SESSION['openid']=$oauth['openid'];
         							$_SESSION['uid']=$uid;

         						}

         				} else {
         					$this->returnError('微信授权获取用户信息失败,请重新尝试: ' . $response['message']);
         				}
         			}
         		}
           $this->GetOauth();

         	}
        /**
         * 调用memcache
         */

         private function _getMemcache(){
           if(extension_loaded('memcache')){
                // $this->memcache = new _Memcache();
           }
         }
         public  function _wapi(){
              global $_W,$_GPC;
             $this->weid = $_W['uniacid'];

            $this->jsonfile = ATTACHMENT_ROOT.'/'.GARCIA_PREFIX.'json_'.$this->weid.'.json';
            $this->tickfile = ATTACHMENT_ROOT.'/'.GARCIA_PREFIX.'jsontttt_'.$this->weid.'.json';

            //调用memcache
            $this->_getMemcache();

            $this->wapi = new WechatAPI($this->sys['verifypeer']);
        }

        /**
         * 判断当前用户时候手机验证了
         */

         public function _checkMobile(){

             if($this->modal=='wechat'){
                    $mid = $this->_gmodaluserid();
             }else{
                $mid = json_decode($this->cookies->get('userDatas'),true);
                $mid =$mid['uid'];
             }



            if(empty($mid)){
              $this->_OauthMobile = false;
            }else{
              $sql = "SELECT tel FROM ".tablename(GARCIA_PREFIX."member")."where weid=".$this->weid." and id=".$mid;
              $tel = pdo_fetchcolumn($sql);

              $mobile = pdo_fetchcolumn("SELECT mobile FROM ".tablename(GARCIA_PREFIX."member")."where weid=".$this->weid." and id=".$mid);

              if(!$tel&&!$mobile){
                 $this->_OauthMobile = false;

              }else{
                 $this->_OauthMobile = true;
              }
            }
         }
         /**
          * 检测oss链接
          */
         public function _Initoss(){
             include GARCIA_OSS."index.php";
           if(!empty($this->sys['oss_access'])&&!empty($this->sys['oss_secret'])&&!empty($this->sys['oss_url'])){
              $this->_ossClient = new _ali($this->sys['oss_access'],$this->sys['oss_secret'],$this->sys['oss_endpoint'],$this->sys['oss_bucket'],$this->sys['oss_url']);
           }
          //  echo $this->sys['oss_url'];
         }
         public function _Initqniu(){
           global $_W;
            include GARCIA_QNIU."index.php";
            $this->_qniuClient = new _qniu($this->sys['qniu_access'],$this->sys['qniu_secret'],$this->sys['qniu_bucket'],$this->sys['qniu_url']);
         }

         public function _qniupload($filePath,$key){
            return $this->_qniuClient->upload($filePath,$key);
         }


         /**
          * 检测你用户状态
          */
          private function _mstatus(){
            $mid = $this->_gmodaluserid();
            if(!empty($mid)){
                 $sql = "SELECT status FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid;
                 $this->status = pdo_fetchcolumn($sql);
            }
          }

        /**
         * 微信图片上传处理
         */
         public function   _GetWxImage($media_id,$token){

             global $_W,$_GPC;


              $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$media_id;
           		$ch = curl_init($url);
           		curl_setopt($ch, CURLOPT_HEADER, 0);
           		curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
           		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           		$package = curl_exec($ch);
           		$httpinfo = curl_getinfo($ch);
           		curl_close($ch);
           		$media = array_merge(array('mediaBody' => $package), $httpinfo);
           		//求出文件格式
           		preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
           		$fileExt = $extmatches[1];
              //bug 获取不了文件
              if(!in_array($fileExt,array('jpg','jpeg','png','gif'))){
                return array('status'=>2);
              }
           		$filename = $_W['uniacid']."_".time().rand(100,999).".{$fileExt}";
              $filename_thumb = $_W['uniacid']."_".time().rand(100,999)."_thumb".".{$fileExt}";
           		$dirname = ATTACHMENT_ROOT."/wxdown".$_W['uniacid']."/";
           		if(!file_exists($dirname)){
           			 mkdir($dirname,0777,true);
           		}
              /**
               * 创建缩略图
               */

           		file_put_contents($dirname.$filename,$media['mediaBody']);

              $this->image_resize($dirname.$filename,$dirname.$filename_thumb,200,200);

              if($this->sys['file_type']==0){
                pdo_insert(GARCIA_PREFIX."photo",
                    array(
                      'weid'=>$this->weid,
                      "pic"=>$_W['siteroot'].'attachment/wxdown'.$_W['uniacid']."/".$filename,
                      'dir'=>$dirname,
                      'thumb'=>$_W['siteroot'].'attachment/wxdown'.$_W['uniacid']."/".$filename_thumb,
                      'upbdate'=>time()
                    )
              );
                $inser_id = pdo_insertid();
                  //系统上传
                  return array(
                    'status'=>1,
                    'imgurl'=>$_W['siteroot'].'attachment/wxdown'.$_W['uniacid']."/".$filename,
                    'imgurl_thumb'=>$_W['siteroot'].'attachment/wxdown'.$_W['uniacid']."/".$filename_thumb,
                    'insert_id' =>$inser_id,
                  );
              }else if($this->sys['file_type']==1){
                  //oss上传
                  $this->_Initoss();
                  $object = "wxdown/".$filename;
                  $object2 = "wxdown/".$filename_thumb;
                  $_result = $this->_ossClient->multiuploadFile($object,$dirname.$filename,array('type'=>1));
                  $_result2 = $this->_ossClient->multiuploadFile($object2,$dirname.$filename_thumb);
                  if($_result['status']==1){
                     pdo_insert(GARCIA_PREFIX."photo",
                         array(
                           'weid'=>$this->weid,
                           "pic"=>$_result['imgurl'],
                           'dir'=>$dirname,
                           'thumb'=>$_result2['imgurl'],
                           'upbdate'=>time()
                         )
                   );
                     $inser_id = pdo_insertid();
                      $data = array(
                        'status'=>1,
                        'imgurl'=>$_result['imgurl'],
                        'imgurl_thumb'=>$_result2['imgurl'],
                        'insert_id' =>$inser_id,
                      );
                      return $data;
                  }else{
                    $data = array(
                      'status'=>0,
                      'imgurl'=>$_result['msg']
                    );
                    return $data;
                  }
              }else if($this->sys['file_type']==2){
                  $object = "wxdown/".$filename;
                  $object2 = "wxdown/".$filename_thumb;
                  $this->_Initqniu();
                  $_result = $this->_qniupload($dirname.$filename,$object);
                  $_result2 = $this->_qniupload($dirname.$filename_thumb,$object2);
                  pdo_insert(GARCIA_PREFIX."photo",
                      array(
                        'weid'=>$this->weid,
                        "pic"=>$_result,
                        'dir'=>$dirname,
                        'thumb'=>$_result2,
                        'upbdate'=>time()
                      )
                );
                  $inser_id = pdo_insertid();
                   $data = array(
                     'status'=>1,
                     'imgurl'=>$_result,
                     'imgurl_thumb'=>$_result2,
                     'insert_id' =>$inser_id,
                   );
                   return $data;
              }

         }


         public function __uploadImg($data){
            global $_W;
            // $fileExt = explode('/',$data['type']);

            $fileExt = substr(strrchr($data['name'], '.'), 1);
            $fileExt = strtolower($fileExt);
           if(!in_array($fileExt,array('jpg','jpeg','png','gif'))){
             return array('status'=>2,'ext'=>$fileExt);
           }
           $filename = $_W['uniacid']."_".time().rand(100,999).".{$fileExt}";
           $filename_thumb = $_W['uniacid']."_".time().rand(100,999)."_thumb".".{$fileExt}";
           $dirname = ATTACHMENT_ROOT."qsc_pic".$_W['uniacid']."/";
           if(!file_exists($dirname)){
              mkdir($dirname,0777,true);
           }

           /**
            * 创建缩略图
            */
            // $dirname.$filename = $data['tmp_name'];
           copy($data['tmp_name'],$dirname.$filename);

           $this->image_resize($dirname.$filename,$dirname.$filename_thumb,200,200);

           if(!file_exists($dirname.$filename_thumb)){
             return array('status'=>2,'msg'=>'上传失败，请重试');
           }

           if($this->sys['file_type']==0){
             pdo_insert(GARCIA_PREFIX."photo",
                 array(
                   'weid'=>$this->weid,
                   "pic"=>$_W['siteroot'].'attachment/qsc_pic'.$_W['uniacid']."/".$filename,
                   'dir'=>$dirname,
                   'thumb'=>$_W['siteroot'].'attachment/qsc_pic'.$_W['uniacid']."/".$filename_thumb,
                   'upbdate'=>time()
                 )
           );
             $inser_id = pdo_insertid();
                @unlink($data['tmp_name']);
               //系统上传
               return array(
                 'status'=>1,
                 'imgurl'=>$_W['siteroot'].'attachment/qsc_pic'.$_W['uniacid']."/".$filename,
                 'imgurl_thumb'=>$_W['siteroot'].'attachment/qsc_pic'.$_W['uniacid']."/".$filename_thumb,
                 'insert_id' =>$inser_id,
               );
           }else if($this->sys['file_type']==1){
               //oss上传
               $this->_Initoss();
               $object = "qsc_pic".$_W['uniacid']."/".$filename;
               $object2 = "qsc_pic".$_W['uniacid']."/".$filename_thumb;
               $_result = $this->_ossClient->multiuploadFile($object,$dirname.$filename,array('type'=>1));
               $_result2 = $this->_ossClient->multiuploadFile($object2,$dirname.$filename_thumb);

               if($_result['status']==1){
                  pdo_insert(GARCIA_PREFIX."photo",
                      array(
                        'weid'=>$this->weid,
                        "pic"=>$_result['imgurl'],
                        'dir'=>$dirname,
                        'thumb'=>$_result2['imgurl'],
                        'upbdate'=>time()
                      )
                );
                  $inser_id = pdo_insertid();
                             @unlink($data['tmp_name']);
                   $data = array(
                     'status'=>1,
                     'imgurl'=>$_result['imgurl'],
                     'imgurl_thumb'=>$_result2['imgurl'],
                     'insert_id' =>$inser_id,
                   );
                   return $data;
               }else{
                 $data = array(
                   'status'=>0,
                   'imgurl'=>$_result['msg']
                 );
                 return $data;
               }
           }else if($this->sys['file_type']==2){

               $object = "qsc_pic".$_W['uniacid']."/".$filename;
               $object2 = "qsc_pic".$_W['uniacid']."/".$filename_thumb;
               $this->_Initqniu();
               $_result = $this->_qniupload($dirname.$filename,$object);
               $_result2 = $this->_qniupload($dirname.$filename_thumb,$object2);

               pdo_insert(GARCIA_PREFIX."photo",
                   array(
                     'weid'=>$this->weid,
                     "pic"=>$_result['img'],
                     'dir'=>$dirname,
                     'thumb'=>$_result2['img'],
                     'upbdate'=>time()
                   )
             );
               $inser_id = pdo_insertid();
                  @unlink($data['tmp_name']);
                $data = array(
                  'status'=>1,
                  'imgurl'=>$_result['img'],
                  'imgurl_thumb'=>$_result2['img'],
                  'insert_id' =>$inser_id,
                );
                return $data;
           }
         }

         /**
          * 分块合成
          */

          /**
           * 获取token
           */
         public function _gtoken(){
           return $this->wapi->getAccessToken($this->jsonfile);
         }

         /**
          * 获取用户微擎余额
          */
          public function _wallet($openid){
              load()->model('mc');
              $platrrom = $this->_gplatfromuser($openid);
              $uid = $platrrom['uid'];
              $wallket = mc_credit_fetch($uid);
              $wallket=$wallket['credit2'];
              $wallket=   max(0.00,$wallket);
              return round($wallket,2);
          }

         /**
          * 获取平台用户信息
          */
          public function _gplatfromuser($openid){
                global $_W,$_GPC;
                load()->model('mc');

                $_res = mc_fansinfo($openid,$_W['acid'],$_W['uniacid']);

                if(empty($_res['uid'])||$_res['uid']==0){

                    $rec  = array();
                    $rec['acid'] = $_W['acid'];
                    $rec['uniacid'] = $_W['uniacid'];
                    $rec['uid'] = 0;
                    $rec['openid'] = $openid;
                    $rec['salt'] = random(8);
                    $rec['follow'] = 1;
                    $rec['followtime'] = time();
                    $rec['unfollowtime'] = 0;
                    $data = array(
                      'uniacid'=>$_W['uniacid'],
                      'email'=>md5($openid)."@we7.cc",
                      'salt'=>random(8),
                      'groupid'=>20,
                      'createtime'=>time(),
                    );
                    $data['password']= md5($openid.$data['salt']);
                    //清除原有数据防止0的方式
                    pdo_delete('mc_members',array('email'=>md5($openid)."@we7.cc"));
                    pdo_insert('mc_members',$data);
                     $rec['uid'] = pdo_insertid();

                   pdo_delete('mc_mapping_fans',array('openid'=>$openid));
                    pdo_insert('mc_mapping_fans',$rec);
                   $uid = mc_openid2uid($openid);
                  $mc  = mc_fetch($uid, array(
                      'realname',
                      'mobile',
                      'avatar',
                      'resideprovince',
                      'residecity',
                      'residedist'
                  ));

                  $_res = mc_fansinfo($openid,$_W['acid'],$_W['uniacid']);

                }
                $mid = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and openid='".$openid."'");
                return  array('uid'=>$_res['uid'],'fanud'=>$_res['fanid'],'follow'=>$_res['follow'],'followtime'=>$_res['followtime'],'mid'=>$mid);
          }
          /**
           * 获取用户表ID
           */
          public function _gmodaluserid(){
              global $_W,$_GPC;
              return  $id = pdo_fetchcolumn("SELECT id FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." AND openid='".$this->memberinfo['openid']."'");
          }

          public function _isManger($mid){
              global $_W,$_GPC;
              return  $id = pdo_fetch("SELECT allow,allows,is_manger FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$_W['uniacid']." AND id=".$mid);
          }
          public function _GetMemberName($id){
            global $_W,$_GPC;
              $id = pdo_fetchcolumn("SELECT nickname FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$_W['uniacid']." AND id=".$id);
              return urldecode($id);
          }
          public function _GetMemberTel($id){
                global $_W,$_GPC;
                $id = pdo_fetchcolumn("SELECT tel FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$_W['uniacid']." AND id=".$id);
                return urldecode($id);
          }
        /**
         * 调取系统信息
         */
        public  function _GetSysInfo(){
            global $_W,$_GPC;
             if(!pdo_tableexists(GARCIA_PREFIX.'setting')){
                $this->sys = null;
             }else{
                $this->sys = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX.'setting')." where weid=".$this->weid);
             }

        }


        /**
         * 检测微信授权状态
         */
        public function _GetOauthStatus(){
          global $_W;
          $_W['fans']['uid'];
            if($_COOKIE['garciawxstatus3']==$this->__ckey){
            $this->isOauth = true;
            //判断是否数据库存在该用户
            $row  = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX.'member')." WHERE openid='".$_COOKIE['wxiopenid']."' AND weid=".$this->weid);

            if(empty($row)){
              $data = array(
                'openid'=>$_COOKIE['wxiopenid'],
                'nickname'=>$_COOKIE['wxinickname'],
                'headimgurl'=>$_COOKIE['wxiheadimgurl'],
                'weid'=>$this->weid,
                );
              pdo_Insert(GARCIA_PREFIX.'member',$data);
            }
            mc_openid2uid($row['openid']);
            //检测变化
            // var_dump($_COOKIE);
            if($_COOKIE['wxinickname']!=$row['nickname']){
                pdo_update(GARCIA_PREFIX.'member',array('nickname'=>$_COOKIE['wxinickname']),array('id'=>$row['id']));
            }
            if($_COOKIE['wxiheadimgurl']!=$row['headimgurl']){
                // pdo_update(GARCIA_PREFIX.'member',array('headimgurl'=>$_COOKIE['wxiheadimgurl']),array('id'=>$row['id']));
            }
            $this->memberinfo = array(
                'openid'=>$row['openid'],
                'nickname'=>$row['nickname'],
                'headimgurl'=>$row['headimgurl']
            );

            if(empty($this->memberinfo['openid'])){
              $this->isOauth = false;
            }

          }else{


            $this->isOauth = false;
          }

        }

        /**
         * 绿色授权
         */
        public function GetOauth(){
           global $_W,$_GPC;
          $userinfo = mc_oauth_userinfo();

            $this->memberinfo = array(
              'openid'=>$userinfo['openid'],
              'nickname'=>urlencode($userinfo['nickname']),
              'headimgurl'=>$userinfo['headimgurl'],
              );
            $id = pdo_fetchcolumn("SELECT id FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." AND openid='".$userinfo['openid']."'");
            if(empty($id)){
                $data = array(
                  'headimgurl'=>$userinfo['headimgurl'],
                  'nickname'=>urlencode($userinfo['nickname']),
                  'openid'=>$userinfo['openid'],
                  'weid'=>$this->weid
                );
                pdo_Insert(GARCIA_PREFIX.'member',$data);
            }
        }

        // 登陆状态
        private function _checkLogin(){
          //  $_userStatus =
           $_keys = sha1($this->__ckey);
           $this->_userStatus = $this->cookies->get('userStatus');
           if(!empty($this->_userStatus)){
               $this->_login = true;
           }else{
                $this->_login = false;
                // header('Location:'.$this->createMobileUrl('login'));
           }


        }
        /**
         * web辅助函数
         */
        public function __web($f_name){
            global $_W,$_GPC;
            $this->Init();
            include_once  GARCIA_PATH.'class/web/'.strtolower(substr($f_name,8)).'.php';
        }

        /**
         * admin 辅助函数
         */
         public function __admin($f_name){
           global $_W,$_GPC;
             $this->Init();
             include_once  GARCIA_PATH.'class/admin/'.strtolower(substr($f_name,5)).'.php';
         }
        /**
         * mobile辅助函数
         */
        public function __mobile($f_name){
          global $_W,$_GPC;
           $funname = strtolower(substr($f_name,8));

          $this->Init();
                    $this->_wapi();
          if($this->sys['is_memache']==1){
                $this->memcache_obj = memcache_connect($this->sys['memcachelink'], $this->sys['memcacheprot']);
          }


          if($this->modal=='pc'&&!GARCIA_PC){
              include  $this->template('bad');
              exit;
          }

          if(!$this->sys['is_h5']&&$this->modal == 'phone'){
              include  $this->template('bad');
              exit;
          }

          if($funname=='api'){
              include_once  GARCIA_PATH.'class/mobile/'.$funname.'.php';
              exit;
          }
          if($this->modal==='wechat'){
                if(GARCIA_OAUTH){
                  if(!$this->isOauth){
                      if($_GPC['machine'] != md5(GARCIA_MACHINE)&&$funname!='api'){
                            $this->_GetOauthStatus();
                            $this->_Auth();
                      }
                  }
                }
                $accObj = WeiXinAccount::create($_W['account']);
                $userinfo = $accObj->fansQueryInfo($_W['openid']);
                $subscribe = $userinfo['subscribe']; //是否有关注
                $this->_mstatus();

                // $this->_checkMobile();
          }

          if($this->modal=='pc'&&GARCIA_PC){
              $udata =  $this->cookies->get('userDatas');
              $udata = json_decode($udata,true);

              $this->conf['menu'] = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and is_pc=1 order by rank asc limit 3");
              if(!empty($udata)){
                   $is_login = true;
                   $this->conf['user']['avatar'] = pdo_fetchcolumn('SELECT headimgurl FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$udata['uid']) ;
                   $this->conf['user']['avatar'] = empty($this->conf['user']['avatar'])?tomedia($this->sys['user_img']):tomedia($this->conf['user']['avatar']);
                   $this->conf['user']['mid']  = $udata['uid'];
                   $this->conf['user']['nickname'] = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$udata['uid']) ;
                   $this->conf['user']['openid'] = pdo_fetchcolumn('SELECT openid FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$udata['uid']) ;
                    $this->conf['user']['bdqrcode'] =  $img =  $this->getBDqrcode($this->conf['user']['mid']);
              }else{
                 $is_login = false;
              }

          }


          load()->func('tpl');
          $this->u = substr($f_name,8);
          foreach ($_GET as $k => $v) {
            $this->uarr[$k]=$v;
          }

          include_once  GARCIA_PATH.'class/mobile/'.$funname.'.php';
        }


     /**
      * 天数差
      */
      public function diffDate($date1,$date2){

        $startdate=strtotime($date1);
        $enddate=strtotime($date2);
        $days=round(($enddate-$startdate)/3600/24) ;
        return  $days; //days为得到的天数;
      }

      public function BroOatuh(){
        global $_W,$_GPC;
        			  $code = $_GET['code'];
        			session_start();
            if(empty($_COOKIE['bro_openid'])){

     						if(empty($code)){
     									$wapi = $this->wapi;
     									$file =  ATTACHMENT_ROOT.GARCIA_DIR.$this->weid.'.json';
     									$token = $wapi->getAccessToken($file,$this->sys['pay_appid'],$this->sys['pay_appsecret']);
                      $url =$this->link ;
                      $callback = urlencode($url);
                     $forward = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->sys['pay_appid'].'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_base&state='.$state.'#wechat_redirect';
                    header("Location: ".$forward);
     						}else{
     							 $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->sys['pay_appid']."&secret=".$this->sys['pay_appsecret']."&code=".$code."&grant_type=authorization_code";
     							load()->func('communication');
     							$response = ihttp_get($url);
     								$oauth = @json_decode($response['content'], true);

     								if (!is_error($response)) {
 										  	setcookie('bro_openid',$oauth['openid'], time()+3600*24*30);
     								}else{
     									  message('微信授权获取用户信息失败,请重新尝试: ' . $response['message'],$this->createMobileUrl('index'),'error');
     								}
     						}
     			 }
      }

     public function _links(){
        global $_W,$_GPC;
        $this->link = $_W['siteroot'] . 'app/index.php?' . $_SERVER['QUERY_STRING'];
     }

      /**
       * 分页转换函数
       * $page 当前页码
       * $psize 分页数量
       * $sql 数据库语句
       */

       public function _pager($page,$psize,$sql){
         $pindex = max(1, intval($page));

         $data['limit'] = " LIMIT ".($pindex - 1) * $psize.",".$psize;
         $total = pdo_fetchcolumn($sql);
         $pager = pagination($total, $pindex, $psize);
         $data['pager'] = $pager;
         $data['total'] = $total;
         return $data;
       }
    protected function pay($params = array())

      {

        global $_W;

        $params['module'] = $this->module['name'];

        $sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';

        $pars = array();

        $pars[':uniacid'] = $_W['uniacid'];

        $pars[':module'] = $params['module'];

        $pars[':tid'] = $params['tid'];


        $log = pdo_fetch($sql, $pars);

        if (!empty($log) && $log['status'] == '1') {

          $this->returnError('这个订单已经支付成功, 不需要重复支付.');

        }
        if (empty($log)) {
    	        $log = array(
    	                'uniacid' => $_W['uniacid'],
    	                'acid' => $_W['acid'],
    	                'openid' => $params['openid'],
    	                'module' => $this->module['name'], //模块名称，请保证$this可用
    	                'tid' => $params['tid'],
                      'uniontid'=>$params['uniontid'],
    	                'fee' => $params['fee'],
    	                'card_fee' => $params['fee'],
    	                'status' => '0',
    	                'is_usecard' => '0',
    	        );

    	        pdo_insert('core_paylog', $log);
    		}

        return $params;

      }



      public function payResult($params)
    	{
          global $_W;

           //互助记录
           if(strstr($params['tid'],'_huzhu')){
             $_result = pdo_fetchall('SELECT  * FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and tid='".$params['tid']."'");
             foreach ($_result as $key => $value) {
                $ids[] = $value['ids'];
             }

            //  var_dump();


             foreach ($ids as $key => $value) {

               $age = pdo_fetchcolumn('SELECT age FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and id=".$value);
               if($age>=18&&$age<=39){
                    $maxday = date('Y-m-d',strtotime('+180 day'));
               }else if($age>=40&&$age<=60){
                     $maxday = date('Y-m-d',strtotime('+360 day'));
               }else{
                    $maxday = date('Y-m-d',strtotime('+360 day'));
               }
                    $maxday = strtotime($maxday);

                  pdo_update(GARCIA_PREFIX."huzhu",array('status'=>'1','transaction_id'=>$params['tag']['transaction_id'],'maxday'=>$maxday,'upbdate'=>time()),array('id'=>$value));
             }
               pdo_update(GARCIA_PREFIX."huzhu_pay",array('status'=>'1','transaction_id'=>$params['tag']['transaction_id']),array('tid'=>$params['tid']));
             $this->_TplHtml('支付成功',$_W['siteroot']."app/".substr($this->createMobileUrl('insurance',array('display'=>'join')),2),'seccuess');
             exit;
           }else if(strstr($params['tid'],'_chargehuzhu')){
              $_result = pdo_fetch('SELECT  * FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and tid='".$params['tid']."'");
              $id = $_result['ids'];
              $money = pdo_fetchcolumn('SELECT moneys FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and id=".$id);
              $_moneys = $money+$_result['mmm'];
              if($_result['status']==0){
                  pdo_update(GARCIA_PREFIX."huzhu",array('moneys'=>$_moneys),array('id'=>$id));
              }

              pdo_update(GARCIA_PREFIX."huzhu_pay",array('status'=>'1','transaction_id'=>$params['tag']['transaction_id']),array('tid'=>$params['tid']));
              $this->_TplHtml('支付成功',$_W['siteroot']."app/".substr($this->createMobileUrl('insurance',array('display'=>'join')),2),'seccuess');
              exit;
              //
           }

          $this->_wapi();
          $this->_GetSysInfo();
          $tid = $params['tid'];
          $acc_json = $this->jsonfile;
          $token = $this->wapi->getAccessToken($acc_json);
          $sql ="SELECT a.*,b.nickname,c.name as t1,d.openid as touser,b.id as mid FROM ".tablename(GARCIA_PREFIX."paylog")." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
          LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." d on c.mid=d.id
          where a.weid=".$this->weid." and a.tid='".$tid."'";
           $config = pdo_fetch($sql);
           //支付后取消删除
           pdo_update(GARCIA_PREFIX."member",array('status'=>0),array('id'=>$config['mid']));
           $fid = $config['fid'];

           $_d = array(
               "{nickname}",
               "{fee}",
               "{time}",
               "{msg}",
               "{name}",
               "{tid}"
           );
           $_m = array(
               urldecode($config['nickname']),
               $config['fee'],
               date('Y-m-d H:i:s',$config['upbdate']),
               $config['msg'],
               $config['t1'],
               $config['tid'],
           );
          if($this->sys['kf_sup_type']==1){
              $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$this->sys['kf_sup_temp']);
               $temp_id = $temp['tempid'];
               $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
               $url =  str_replace("{url}",$_url,$temp['url']);
              $parama = json_decode($temp['parama'],true);

              foreach ($parama as $key => $value) {
                 foreach ($value as $k => $v) {
                      if($k=='value'){
                           $parama[$key][$k] = str_replace($_d,$_m,$v);
                      }else if($k=='color'){
                          if(empty($v)){
                             $parama[$key][$k] = '#333333';
                          }
                      }
                 }
              }
          }else{
              $message = str_replace($_d,$_m,$this->sys['kf_sup_success']);

          }



          if($config['status']==0){

            $fee = $config['fee'];
            $fb = pdo_fetchcolumn('SELECT has_money FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$this->weid." and id=".$fid);
            $_fee = $fb+$fee;
            pdo_update(GARCIA_PREFIX."fabu",array('has_money'=>$_fee),array('id'=>$fid));
            pdo_update(GARCIA_PREFIX."paylog",array('upbdate'=>time(),'status'=>1,'transaction_id'=>$params['tag']['transaction_id']),array('id'=>$config['id']));

            if($this->sys['kf_sup_type']==1){
              $a = $this->wapi->sendTemplate($config['touser'],$temp_id,$url,$token,$parama);
            }else{
              $a = $this->wapi->sendText($config['touser'],urlencode($message),$token);
            }

          }
          /**
           * 通知发布者
           */

          echo "<script language='javascript'
          type='text/javascript'>";
          // echo "window.location.href='"."http://".$_SERVER['SERVER_NAME']."/app/".substr($this->createMobileUrl('fsuccess',array('dopost'=>'pay','fid'=>$fid)),2)."'";
          echo "window.location.href='".$_W['siteroot']."app/".substr($this->createMobileUrl('fsuccess',array('dopost'=>'pay','fid'=>$fid)),2)."'";
          echo "</script>";

      }

      /**
       * 备用支付返回
       */
       public function payResult_bak($params)
      {
           global $_W;

           $tid = $params['tid'];

           $sql ="SELECT * FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$_W['uniacid']." and tid='".$tid."'";
           $config = pdo_fetch($sql);

           $fid = $config['fid'];
           if($config['status']==0){

             $fee = $config['fee'];
             $fb = pdo_fetchcolumn('SELECT has_money FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$_W['uniacid']." and id=".$fid);
             $_fee = $fb+$fee;
             pdo_update(GARCIA_PREFIX."fabu",array('has_money'=>$_fee),array('id'=>$fid));
             pdo_update(GARCIA_PREFIX."paylog",array('status'=>1),array('id'=>$config['id']));
           }

           echo "<script language='javascript'
           type='text/javascript'>";
           echo "window.location.href='"."http://".$_SERVER['SERVER_NAME']."/app/index.php?i=".$_W['uniacid']."&c=entry&do=fsuccess&m=jy_qingsongchou&dopost=pay&fid=".$fid."'";
           echo "</script>";

       }
   //发布时间
    public function _format_date($time){
        $t=time()-$time;
          $f=array(
          '31536000'=>'年',
          '2592000'=>'个月',
          '604800'=>'星期',
          '86400'=>'天',
          '3600'=>'小时',
          '60'=>'分钟',
          '1'=>'秒'
          );
        foreach ($f as $k=>$v)    {
          if (0 !=$c=floor($t/(int)$k)) {
              return $c.$v.'前';
          }
        }
      }

   //导出excel
    public function _pushExcel($title=array(),$data=array(),$name){
        $ichar =  ord("A"); //初始节点头A
        $_file = $name."(编号:".time().").xls";//定义文件名
        $_file = iconv("utf-8", "gb2312", $_file);

        $objPHPExcel = new PHPExcel(); //实例化 phpexcel类
        $objProps = $objPHPExcel->getProperties();
        //设置表头

        foreach($title as $k => $v){
            $colum = chr($ichar);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v['name']);
            $v['width'] = empty($v['width'])?10:$v['width'];
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth($v['width']); //设置宽度
            $ichar += 1;
        }
        //内容列表
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValueExplicit($j.$column, $value, PHPExcel_Cell_DataType::TYPE_STRING);
                $span++;
            }
            $column++;
        }
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        //将输出重定向到一个客户端web浏览器(Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$_file\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;

    }

    /**
     * 下载远程图片
     */
    function httpcopy($url, $timeout=60) {

        global $_W,$_GPC;
           foreach ($url as $key => $value) {
             $sql = "SELECT id FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and pic='".tomedia($value)."'";
             $id = pdo_fetchcolumn($sql);
             if(empty($id)){
                $_to[] = tomedia($value);
             }else{
                // var_dump('ppc');
               $__id[] = $id ;
             }
           }

       $dirname = ATTACHMENT_ROOT."wxdown".$_W['uniacid']."/";
       if(!file_exists($dirname)){
          mkdir($dirname,0777,true);
       }
       if ($url == ""):return false;
        endif;
        //如果$url地址为空，直接退出
      foreach ($_to as $k => $v) {

        // if ($filename == "") {
            //如果没有指定新的文件名
            $ext = strrchr($v, ".");
            //得到$url的图片格式
            if ($ext != ".gif" && $ext != ".jpg"&& $ext != ".png"&& $ext != ".jpeg"):return false;
            endif;
            //如果图片格式不为.gif或者.jpg，直接退出
            $filenames[$k] = $v;
            $filename = $_W['uniacid']."_".time().rand(10000,99999)."_thumb"."{$ext}";
            //用天月面时分秒来命名新的文件名
          // }

          ob_start();//打开输出
          readfile($v);//输出图片文件
          $img = ob_get_contents();//得到浏览器输出
          ob_end_clean();//清除输出并关闭
          $size = strlen($img);//得到图片大小
          $fp2 = @fopen($dirname.$filename, "a");
          fwrite($fp2, $img);//向当前目录写入图片文件，并重新命名
          fclose($fp2);
          $file_root[$k] = $_W['siteroot']."attachment/wxdown".$_W['uniacid']."/".$filename;
          $file_dir[$k]= $dirname.$filename;
          $this->image_resize($dirname.$filename,$dirname.$filename,200,200);
      }

        if($this->sys['file_type']==0){
          foreach ($filenames as $key => $value) {
              pdo_insert(GARCIA_PREFIX."photo",
                  array(
                      'weid'=>$this->weid,
                      "pic"=>$value,
                      'dir'=>$dirname,
                      'thumb'=>$file_root[$key],
                      'upbdate'=>time()
                  )
              );
               $inser_id = array(pdo_insertid());

              if(empty($__id)){
                 $__id = $inser_id;
              }else{
                $__id =array_merge($__id,$inser_id);
              }
              $_tmp[]= array(
               'status'=>1,
               'imgurl'=>$value,
               'imgurl_thumb'=>$file_root[$key],
               'insert_id' =>$inser_id,
             );
      }

          // sleep(1);
            //系统上传
       return $__id;
        }else if($this->sys['file_type']==1){
            //oss上传
              $this->_Initoss();
              $object2 = "wxdown/".$filename;

              if(!empty($file_dir)){
                // $this->sys['oss_bucket']
                foreach ($file_dir as $k => $v) {
                    // $position = $this->_ossClient->appendFile($this->sys['oss_bucket'], $object2, $v, 0);


                            // $_result2 = $this->_ossClient->multiuploadFile($this->sys['oss_bucket'],$object2,$v);
                            // $_result2 = $this->_ossClient->uploadFile($this->sys['oss_bucket'], $object2, $v);
                            // $_result = $this->_ossClient->multiuploadFile($object,$dirname.$filename,array('type'=>1));
                            $_result2 = $this->_ossClient->multiuploadFile($object2,$v,array('type'=>1));

                            if($_result2['status']==1){
                               pdo_insert(GARCIA_PREFIX."photo",
                                   array(
                                     'weid'=>$this->weid,
                                     "pic"=>$filenames[$k],
                                     'dir'=>$dirname,
                                     'thumb'=>$_result2['imgurl'],
                                     'upbdate'=>time()
                                   )
                             );
                             $inser_id = array(pdo_insertid());
                             if(empty($__id)){
                                $__id = $inser_id;
                             }else{
                               $__id =array_merge($__id,$inser_id);
                             }
                                $_tmp[] = array(
                                  'status'=>1,
                                  'imgurl'=>$filenames[$k],
                                  'imgurl_thumb'=>$_result2['imgurl'],
                                  'insert_id' =>$inser_id,
                                );

                            }else{
                              $data = array(
                                'status'=>0,
                                'imgurl'=>$_result['msg']
                              );
                              return $data;
                            }


                    // exit;
                 }
              }


       return $__id;

        }
        else if($this->sys['file_type']==2){

            $this->_Initqniu();
              $object2 = "wxdown/".$filename;
            //

            if(!empty($file_dir)){
                foreach ($file_dir as $k => $v) {


                      if(empty($_utoken)){
                        $_result = $this->_qniupload($v,$object2.$v);
                        //  $_utoken = $_result['token'];
                        // var_dump($_result);
                        // echo '<hr/>';
                      }else{
                        // $_result = '';
                        // if($v)
                        $_result = $this->_qniupload($v,$object2.$v);

                      }
                        $_img = $_result['img'];


                      if(is_array($_result)){
                          if($_result['status']==1){
                                  message('上传过程出错，请重试-七牛!',referer(),'error');
                          }
                      }
                      pdo_insert(GARCIA_PREFIX."photo",
                          array(
                            'weid'=>$this->weid,
                            "pic"=>$filenames[$k],
                            'dir'=>$dirname,
                            'thumb'=>$_img,
                            'upbdate'=>time()
                          )
                    );
                    $inser_id = array(pdo_insertid());
                    if(empty($__id)){
                       $__id = $inser_id;
                    }else{
                      $__id =array_merge($__id,$inser_id);
                    }

                    $_tmp[]= array(
                      'status'=>1,
                      'imgurl'=>$filenames[$k],
                      'imgurl_thumb'=>$_img,
                      'insert_id' =>$inser_id,
                    );
                    //
                }

            }

             return $__id;
          }
    }
  /**
   *$f - 图片来源
   *$t - 图片输出位置
   *$tw - 宽度
   *$th - 高度
   */
    public function image_resize($f, $t, $tw, $th){

          $temp = array(1=>'gif', 2=>'jpeg', 3=>'png',4=>'jpg');
          list($fw, $fh, $tmp) = getimagesize($f);

          if(!$temp[$tmp]){
                  return false;
          }
          $tmp = $temp[$tmp];
          $infunc = "imagecreatefrom$tmp";
          $outfunc = "image$tmp";

          $fimg = $infunc($f);

          if($fw/$tw > $fh/$th){
                  $fw = $tw * ($fh/$th);
          }else{
                  $fh = $th * ($fw/$tw);
          }

          $timg = imagecreatetruecolor($tw, $th);
          imagecopyresampled($timg, $fimg, 0,0, 0,0, $tw,$th, $fw,$fh);
          if($outfunc($timg, $t)){
                  return true;
          }else{
                  return false;
          }
      }

      /**
       * 服务器ip
       */
      function get_server_ip() {
          if (isset($_SERVER)) {
              if($_SERVER['SERVER_ADDR']) {
                  $server_ip = $_SERVER['SERVER_ADDR'];
              } else {
                  $server_ip = $_SERVER['LOCAL_ADDR'];
              }
          } else {
              $server_ip = getenv('SERVER_ADDR');
          }
          return $server_ip;
      }
    /**
         * 获取远程图片的宽高和体积大小
         *
         * @param string $url 远程图片的链接
         * @param string $type 获取远程图片资源的方式, 默认为 curl 可选 fread
         * @param boolean $isGetFilesize 是否获取远程图片的体积大小, 默认false不获取, 设置为 true 时 $type 将强制为 fread
         * @return false|array
         */
        public function myGetImageSize($url, $type = 'curl', $isGetFilesize = false)
        {
            // 若需要获取图片体积大小则默认使用 fread 方式
            $type = $isGetFilesize ? 'fread' : $type;

             if ($type == 'fread') {
                // 或者使用 socket 二进制方式读取, 需要获取图片体积大小最好使用此方法
                $handle = fopen($url, 'rb');

                if (! $handle) return false;

                // 只取头部固定长度168字节数据
                $dataBlock = fread($handle, 168);
            }
            else {
                // 据说 CURL 能缓存DNS 效率比 socket 高
                $ch = curl_init($url);
                // 超时设置
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                // 取前面 168 个字符 通过四张测试图读取宽高结果都没有问题,若获取不到数据可适当加大数值
                curl_setopt($ch, CURLOPT_RANGE, '0-167');
                // 跟踪301跳转
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                // 返回结果
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $dataBlock = curl_exec($ch);

                curl_close($ch);

                if (! $dataBlock) return false;
            }

            // 将读取的图片信息转化为图片路径并获取图片信息,经测试,这里的转化设置 jpeg 对获取png,gif的信息没有影响,无须分别设置
            // 有些图片虽然可以在浏览器查看但实际已被损坏可能无法解析信息
            $size = getimagesize('data://image/jpeg;base64,'. base64_encode($dataBlock));
            if (empty($size)) {
                return false;
            }

            $result['width'] = $size[0];
            $result['height'] = $size[1];

            // 是否获取图片体积大小
            if ($isGetFilesize) {
                // 获取文件数据流信息
                $meta = stream_get_meta_data($handle);
                // nginx 的信息保存在 headers 里，apache 则直接在 wrapper_data
                $dataInfo = isset($meta['wrapper_data']['headers']) ? $meta['wrapper_data']['headers'] : $meta['wrapper_data'];

                foreach ($dataInfo as $va) {
                    if ( preg_match('/length/iU', $va)) {
                        $ts = explode(':', $va);
                        $result['size'] = trim(array_pop($ts));
                        break;
                    }
                }
            }

            if ($type == 'fread') fclose($handle);

            return $result;
        }


        public function _SetQRcode($tid){
            global $_W;
            // include GARCIA_TOOLS."phpqrcode.php";
        // return  GARCIA_TOOLS."phpqrcode.php";
            $value = $_W['siteroot'].'app/'.substr($this->createMobileUrl('pay',array('tid'=>$tid,'dopost'=>'paypc')),2); //二维码内容
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = 6;//生成图片大小
            $_res_img =  ATTACHMENT_ROOT.'/qrcodelist/qrcode_qsc_'.md5($this->weid.'_'.$tid).'.png';
            if(!is_dir(ATTACHMENT_ROOT."/qrcodelist")){
                mkdir(ATTACHMENT_ROOT."/qrcodelist");
                chmod(ATTACHMENT_ROOT."/qrcodelist",777);
            }
            $_res_img_url = "../attachment/qrcodelist/qrcode_qsc_".md5($this->weid."_".$tid).".png";
            //生成二维码图片
             QRcode::png($value,$_res_img, $errorCorrectionLevel, $matrixPointSize, 2);
             $logo = $this->sys['logo'];//准备好的logo图片
            //  $_logo = ATTACHMENT_ROOT.'/qrcode_qsc_logo_'.$this->weid.'.png';
             $img=  file_get_contents(tomedia($logo));
             file_put_contents($_logo,$img);
             $logo = $_logo;
             $QR = $_res_img;//已经生成的原始二维码图
            if ($logo !== FALSE) {
              $QR = imagecreatefromstring(file_get_contents($QR));
                $logo = imagecreatefromstring(file_get_contents($logo));
               $QR_width = imagesx($QR);//二维码图片宽度

              $QR_height = imagesy($QR);//二维码图片高度
              $logo_width = imagesx($logo);//logo图片宽度
              $logo_height = imagesy($logo);//logo图片高度
              $logo_qr_width = $QR_width / 5;
              $scale = $logo_width/$logo_qr_width;
              $logo_qr_height = $logo_height/$scale;
              $from_width = ($QR_width - $logo_qr_width) / 2;
              //重新组合图片并调整大小
              imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
              $logo_qr_height, $logo_width, $logo_height);
            }
            //输出图片
            imagepng($QR,  $_res_img);
            return $_res_img_url;
        }

        public function getFqrcode($tid){
          global $_W;
          $value = $_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$tid)),2); //二维码内容
          $errorCorrectionLevel = 'L';//容错级别
          $matrixPointSize = 6;//生成图片大小
          $_res_img =  ATTACHMENT_ROOT.'/qrcode_qsc_xxm_'.md5($this->weid.'_'.$tid).'.png';
          $_res_img_url = "../attachment/qrcode_qsc_xxm_".md5($this->weid."_".$tid).".png";
          if(file_exists($_res_img)){
              // _success(array('res'=>$value));
              return $_res_img_url;
          }


          //生成二维码图片
           QRcode::png($value,$_res_img, $errorCorrectionLevel, $matrixPointSize, 2);
           $logo = $this->sys['logo'];//准备好的logo图片
          //  $_logo = ATTACHMENT_ROOT.'/qrcode_qsc_logo_'.$this->weid.'.png';
           $img=  file_get_contents(tomedia($logo));
           file_put_contents($_logo,$img);
           $logo = $_logo;
           $QR = $_res_img;//已经生成的原始二维码图
          if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
              $logo = imagecreatefromstring(file_get_contents($logo));
             $QR_width = imagesx($QR);//二维码图片宽度

            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
            $logo_qr_height, $logo_width, $logo_height);
          }
          //输出图片
          imagepng($QR,  $_res_img);
          return $_res_img_url;
        }


        public function getBDqrcode($mid){
          global $_W;
          $value = $_W['siteroot'].'app/'.substr($this->createMobileUrl('bangding',array('uid'=>$mid)),2); //二维码内容
          $errorCorrectionLevel = 'L';//容错级别
          $matrixPointSize = 6;//生成图片大小
          $_res_img =  ATTACHMENT_ROOT.'/qrcode_qsc_bds_'.md5($this->weid.'_'.$mid).'.png';
          $_res_img_url = "../attachment/qrcode_qsc_bds_".md5($this->weid."_".$mid).".png";
          if(file_exists($_res_img)){
              // _success(array('res'=>$value));
              return $_res_img_url;
          }


          //生成二维码图片
           QRcode::png($value,$_res_img, $errorCorrectionLevel, $matrixPointSize, 2);
           $logo = $this->sys['logo'];//准备好的logo图片
          //  $_logo = ATTACHMENT_ROOT.'/qrcode_qsc_logo_'.$this->weid.'.png';
           $img=  file_get_contents(tomedia($logo));
           file_put_contents($_logo,$img);
           $logo = $_logo;
           $QR = $_res_img;//已经生成的原始二维码图
          if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
              $logo = imagecreatefromstring(file_get_contents($logo));
             $QR_width = imagesx($QR);//二维码图片宽度

            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
            $logo_qr_height, $logo_width, $logo_height);
          }
          //输出图片
          imagepng($QR,  $_res_img);
          return $_res_img_url;
        }

        public function returnError($msg){
           $html = '<html>
                         <head>
                         <title>抱歉，出错了</title>
                         <meta charset="utf-8">
                         <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
                         <link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css">
                       </head>
                       <body>
                         <div class="page_msg">
                           <div class="inner">
                             <span class="msg_icon_wrp">
                                  <i class="icon80_smile"></i>
                             </span>
                             <div class="msg_content">
                                <h4>'.$msg.'</h4>
                             </div>
                           </div>
                         </div>
                       </body>
                     </html>';
           echo  $html;
           exit;
        }

      public function _TplHtml($txt,$url,$type){
        global $_W,$_GPC;
        // $accObj = WeiXinAccount::create($_W['account']);
        // $userinfo = $accObj->fansQueryInfo($_W['openid']);
        //  $subscribe = $userinfo['subscribe']; //是否有关注
        // $this->Init();
        include $this->template('public/index');
        exit;
     }


     public function _gManers(){
       //获取管理员列表
       $sql ="SELECT openid FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and is_manger = 1";
       $list =  pdo_fetchall($sql);
       foreach ($list as $key => $value) {
           $_list[] = $value['openid'];
       }
       return $_list;
     }

     /**
      * 单独发送模板消息
      */
     public function _SendTxt($openid,$message){
          $token = $this->_gtoken();
          return $this->wapi->sendText($openid,$message,$token);
     }

     /**
      * 批量发送模板消息
      */

      public function _SendTxts($message,$openids){
            $token = $this->_gtoken();
            foreach ($openids as $key => $openid) {
                $this->wapi->sendText($openid,urlencode($message),$token);
            }
      }


      // public function _SendMTxts($mid,){
      //   /**
      //    * 批量发送管理员消息
      //    */
      //    $openids = $this->_gManers();
      //    $nickname = $this->_GetMemberName($mid);
      //    $message ="系统消息:\nid:[".$mid."],昵称:[".$nickname."]的用户在".date('Y-m-d H:i:s')." 提交了实名审核，请到尽快到后台进行审核!";
      // }



     public function _WebWait($data){
        include $this->template('web/wait/index');
        exit;
     }
     public function pickYKid($url){
       $path =  parse_url($url);
       $host = $path['host'];
       if(strstr($host,'qq')){
            $data['type'] = 1;
       }else if(strstr($host,'youku')){
            $data['type'] = 2;
       }

        $path = $path['path'];
       $path = explode('/',$path);

       foreach ($path as $key => $value) {
             if(strstr($value,'.html')){
                $data['id'] = str_replace(array('.html','id_'),'',$value);
             }
       }
       if(empty($data['id'])){
           $data['id'] = end($path);
       }
       return $data;
     }
     public function h5t($file){
       global $_W,$_GPC;
       $config = $this->config;
        include $this->template('Html5/'.$file);
     }

     public function _getPic($id){
         global $_W;
         if(empty($id)){
           return $id;
         }
         $sql = "SELECT pic FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$_W['uniacid']." and id=".$id;
         return pdo_fetchcolumn($sql);
     }

     public function _getthumb($id){
       global $_W;
       if(empty($id)){
         return $id;
       }
       $sql = "SELECT thumb FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$_W['uniacid']." and id=".$id;
       return pdo_fetchcolumn($sql);
     }

     public function _bankname($code){
         $_banks = array(
             'ccb'=>'建设银行',
             'icbc'=>'工商银行',
             'abchina'=>'农业银行',
             'bankcomm'=>'交通银行',
             'boc'=>'中国银行',
             'psbc'=>'邮政银行',
             'cebbank'=>'光大银行',
             'cmbchina'=>'招商银行',
             'ecitic'=>'中信银行',
             'cmbc'=>'民生银行',
             'cib'=>'兴业银行',
             'cgb'=>'广发银行',
             'spdb'=>'浦发银行',
             'spabank'=>'平安银行',
         );
         return $_banks[$code];
     }

     public function _kuaidi($k){
         $mappings = array(
           '申通' => 'shentong',
           '圆通' => 'yuantong',
           '中通' => 'zhongtong',
           '汇通' => 'huitongkuaidi',
           '韵达' => 'yunda',
           '顺丰' => 'shunfeng',
           'ems' => 'ems',
           '天天' => 'tiantian',
           '宅急送' => 'zhaijisong',
           '邮政' => 'youzhengguonei',
           '德邦' => 'debangwuliu',
           '全峰' => 'quanfengkuaidi'
         );
         return $mappings[$k];
     }

     public function _ppc($keyword){
         $keyword=urlencode($keyword);//将关键字编码
         $keyword=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99)+/",'',$keyword);
         $keyword=urldecode($keyword);//将过滤后的关键字解码
         return $keyword;
     }
  }



 ?>
