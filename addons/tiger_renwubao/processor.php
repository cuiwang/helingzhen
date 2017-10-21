<?php
/**
 * 模块处理程序
 *
 * @author 老虎
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT.'/addons/tiger_renwubao/lib/KdtApiOauthClient.php';
class tiger_renwubaoModuleProcessor extends WeModuleProcessor {
	public function respond() {
        global $_W;
        load()->model('mc');  
        $poster=pdo_fetch ( 'select * from ' . tablename ($this->modulename . "_poster" ) . " where weid='{$_W['uniacid']}' and kword='{$this->message['content']}' limit 1" );  

         //$this->tppostmsg($this->message['from'],$fans,$poster,20);

        file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($this->message),FILE_APPEND);

		$fans = mc_fetch($this->message['from']);
         //if (empty($fans['nickname']) || empty($fans['avatar'])){
                    $openid = $this->message['from'];
					$ACCESS_TOKEN = $this->getAccessToken();
					$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
					load()->func('communication');
					$json = ihttp_get($url);
					$userInfo = @json_decode($json['content'], true);
					$fans['nickname'] = $userInfo['nickname'];
					$fans['avatar'] = $userInfo['headimgurl'];
					//$fans['province'] = $userInfo['province'];
					//$fans['city'] = $userInfo['city'];
                    $fans['sex'] = $userInfo['sex'];//1男  2女
                    $fans['unionid'] = $userInfo['unionid'];                    
				//}

       //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($fans),FILE_APPEND);

       /**地区位置地区限制
       $cfg=$this->module['config'];
       //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".$cfg['province'],FILE_APPEND);
       if(!empty($cfg['dqtype'])){
           $member=pdo_fetch("SELECT * FROM ".tablename($this->modulename."_member")." WHERE weid = :weid and from_user=:from_user", array(':weid' => $_W['uniacid'],':from_user'=>$this->message['from']));//查找当前粉丝
           if(!empty($this->message['location_x'])){
               $add=$this->setadd($this->message['location_x'],$this->message['location_y']);
               //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($this->message),FILE_APPEND);
               //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($add),FILE_APPEND);
               
                 if(!empty($member)){
                     if(!empty($add['province'])){
                          if($cfg['dqtype']==1){//省
                               if(empty($member['province'])){
                                 if($add['province']==$cfg['province']){
                                    //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".$add['province'].$cfg['province'],FILE_APPEND);
                                   pdo_update($this->modulename."_member",array('province'=>$add['province'],'city'=>$add['city'],'district'=>$add['district']), array('id' =>$member['id']));
                                 }else{
                                    //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".$add['province'].'****'.$cfg['province'],FILE_APPEND);
                                   pdo_update($this->modulename."_member",array('helpid'=>0,'province'=>$add['province'],'city'=>$add['city'],'district'=>$add['district']), array('id' =>$member['id']));
                                 }
                               }
                           }elseif($cfg['dqtype']==2){//市
                               if(empty($member['city'])){
                                 if($add['city']==$cfg['city']){
                                   pdo_update($this->modulename."_member",array('province'=>$add['province'],'city'=>$add['city'],'district'=>$add['district']), array('id' =>$member['id']));
                                 }else{
                                   pdo_update($this->modulename."_member",array('helpid'=>0,'province'=>$add['province'],'city'=>$add['city'],'district'=>$add['district']), array('id' =>$member['id']));
                                 }
                               }                     
                           }elseif($cfg['dqtype']==3){//区
                               if(empty($member['district'])){
                                 if($add['district']==$cfg['district']){
                                   pdo_update($this->modulename."_member",array('province'=>$add['province'],'city'=>$add['city'],'district'=>$add['district']), array('id' =>$member['id']));
                                 }else{
                                   pdo_update($this->modulename."_member",array('helpid'=>0,'province'=>$add['province'],'city'=>$add['city'],'district'=>$add['district']), array('id' =>$member['id']));
                                 }
                               }
                           }
                     }                   
                 }
           }else{
              pdo_update($this->modulename."_member",array('helpid'=>0), array('id' =>$member['id']));
           }
       }
       地区结束**/

        if(!empty($poster)){
           if($poster['starttime']>time()){
              return $this->postText($this->message['from'],$poster['nostarttips']);              
           }elseif($poster['endtime']<time()){
              $poster['endtips']="活动已结束";
              return $this->postText($this->message['from'],$poster['endtips']);
           }           
         } //活动结束时间

         //生成海报开始
          if($this->message['msgtype'] == 'text' || $this->message['event'] == 'CLICK'){            
             if($poster['winfo1']){
                $info=str_replace('#时间#',date('Y-m-d H:i',time()+30*24*3600),$poster['winfo1']);
                $this->postText($this->message['from'],$info);
             }
             if ($poster['winfo2']){
                $msg2 = $poster['winfo2'];
                $this->postText($this->message['from'],$msg2);
                
             }
             $img = $this->createPoster($fans,$poster,$this->message['from']);
             //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($img),FILE_APPEND);
             $media_id = $this->uploadImage($img); 
             if ($this->message['checked'] == 'checked'){
                 $this->sendImage($this->message['from'],$media_id);
                 return '';
             }else return $this->respImage($media_id);
             exit;
         }
        //生成海报结束


        //关注扫码奖励
         if ($this->message['msgtype'] == 'event') {
            //$poster=pdo_fetch ( 'select * from ' . tablename ($this->modulename . "_poster" ) . " where weid='{$_W['uniacid']}' limit 1" );
            $cfg=$this->module['config'];
            $scene_id=str_replace('qrscene_','',$this->message['eventkey']);//扫码关注场景ID
            
            if ($this->message['event'] == 'subscribe') { 
              $this->reward($scene_id,$this->message['from'],$cfg,$fans,$poster);
              return $this->PostNews($poster,$fans['nickname']);//关注推送图文
            }            
         }
         //关注扫码奖励结束

         //重复扫码提醒
         
         if ($this->message['event'] == 'SCAN') {
             file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode('scan'),FILE_APPEND);
                $cfg=$this->module['config'];
                $msg1=str_replace('#昵称#',$fans['nickname'], $cfg['cfinfo']);
                $msg1=str_replace('#gzname#',$_W['account']['name'], $msg1);  
             return  $this->postText($this->message['from'],$msg1);
          }
       //重复扫码结束


	}


    public function reward($scene_id,$openid,$cfg,$fans,$poster){
        global $_W;  
        $cfg = $this->module['config'];
        $hmember=pdo_fetch("SELECT * FROM ".tablename($this->modulename."_member")." WHERE weid = :weid and sceneid=:sceneid", array(':weid' => $_W['uniacid'],':sceneid'=>$scene_id));//事件所有者
        //$member=pdo_fetch("SELECT * FROM ".tablename($this->modulename."_member")." WHERE weid = :weid and from_user=:from_user", array(':weid' => $_W['uniacid'],':from_user'=>$openid));//当前用户信息
        $member=pdo_fetch('select * from '.tablename($this->modulename."_member")." where weid='{$_W['uniacid']}' and from_user='{$openid}' and pid='{$poster['id']}'");
        //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($address),FILE_APPEND);
        if (empty($member)){//如果粉丝不存在插入数据奖励
            /**姓别限制**/
            if(!empty($cfg['sextype'])){
                if($cfg['sextype']==$fans['sex']){
                  $helpid=$hmember['id'];
                }else{
                  $helpid=0;
                  $hmember=0;//上级不提示
                }              
            }else{
               $helpid=$hmember['id'];
            }
            /**姓别结束**/
            /**查找整个会员表，如果会员已经存在，就不绑定关系**/
            $member=pdo_fetch('select * from '.tablename($this->modulename."_member")." where weid='{$_W['uniacid']}' and from_user='{$openid}'");
            if(empty($member)){
              $helpid=$hmember['id'];
            }else{
              $helpid=0;
              $hmember=0;//上级不提示
            }
            /**结束**/
   
            pdo_insert($this->modulename."_member",
                    array(
                        'weid' => $_W['uniacid'], 
                        'uid'=>$fans['uid'],
                        'from_user' => $openid, 
                        'unionid' => $fans['unionid'], 
                        'nickname' => $fans['nickname'], 
                        'helpid'=>$helpid,
                'pid'=>$poster['id'],
                        'avatar' => $fans['avatar'], 
                        'sex' => $fans['sex'], 
                        'follow'=>1,
                        'province' => $province, 
                        'city' => $city, 
                        'district'=>$district,
                        'time' => TIMESTAMP,
                        'createtime'=>TIMESTAMP
                    ));
            $share['id'] = pdo_insertid();
            $share = pdo_fetch('select * from '.tablename($this->modulename."_member")." where id='{$share['id']}'");

            //开始时间 $cfg['starttime']
            //结束时间 $cfg['endtime']
           // file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode(TIMESTAMP),FILE_APPEND);
            //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($poster['starttime']),FILE_APPEND);
           // file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($poster['endtime']),FILE_APPEND);
            if(TIMESTAMP>=$poster['starttime'] && TIMESTAMP<=$poster['endtime']){//活动时间内用模版消息
                $this->rwb($hmember,$fans,$share,$poster);//任务宝模式               
            }else{//活动时间结束用客服消息
                //关注提醒
                $rwgzmsg=htmlspecialchars_decode($cfg["rwgzmsg"]);
                $rwgzmsg=str_replace('#昵称#',$fans['nickname'], $rwgzmsg);
                $this->postText($fans['openid'],$rwgzmsg);
                
                //上级提醒
                $rwsjmsg=htmlspecialchars_decode($cfg["rwsjmsg"]);
                $rwsjmsg=str_replace('#昵称#',$fans['nickname'], $rwsjmsg);
                $this->postText($hmember['from_user'],$rwsjmsg);     
            
            }
         
        }

        if ($poster['gztype'] == 1){
           if($poster['winfo1']){
                $info=str_replace('#时间#',date('Y-m-d H:i',time()+30*24*3600),$poster['winfo1']);
                $this->postText($this->message['from'],$info);
             }
             if ($poster['winfo2']){
                $msg2 = $poster['winfo2'];
                $this->postText($this->message['from'],$msg2);
             }
            $img = $this->createPoster($fans,$poster,$this->message['from']);
            $media_id = $this->uploadImage($img); 
            $this->sendImage($this->message['from'],$media_id);
        }
    }


     /**
	 * @param 	$hmember 	上级粉丝信息
	 * @param 	$fans	    当前粉丝信息
	 */
    public function rwb($hmember,$fans,$share,$poster){
        global $_W;
        $cfg = $this->module['config'];
        //统计上级人数
        $sjsum=pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_member')." where weid='{$_W['uniacid']}' and pid='{$poster['id']}' and helpid='{$hmember['id']}'");
        //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($sjsum),FILE_APPEND);
        //关注提醒
        $rwgzmsg=htmlspecialchars_decode($cfg["rwgzmsg"]);
        $rwgzmsg=str_replace('#昵称#',$fans['nickname'], $rwgzmsg);
        $this->postText($this->message['from'],$rwgzmsg);
       // file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($hmember['id']),FILE_APPEND);
        //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($poster),FILE_APPEND);

        if($sjsum<$poster['rwmb']){//任务中
            //上级提醒
            $this->tppostmsg($hmember['from_user'],$fans,$poster,$sjsum,$share);
                                  
        }elseif($sjsum==$poster['rwmb']){//完成任务
            //任务完成添加有赞标签
            //0普通链接 1有赞 2人人
            //$rwlx=1;            
            $rwlx=$poster['rwlx'];
            if($rwlx==1){//有赞
                $token=$this->accesstoken();
                $client = new KdtApiOauthClient();
                $method = 'kdt.users.weixin.follower.tags.add';
                $params = [
                    'weixin_openid'=>$hmember['from_user'],
                    'tags' => $poster['yzbq'],
                ];
                $json=$client->post($token,$method,$params);
            }elseif($rwlx==2){//人人
               if(pdo_tableexists('ewei_shop_member_group')){
                  pdo_update("ewei_shop_member", array('groupid'=>$poster['rrbq']), array('openid' =>$hmember['from_user']));
                }
            }elseif($rwlx==3){//红包
                $pre=$poster['hbsl']*100;
                $msg=$this->post_qyfk($cfg,$hmember['from_user'],$pre,'奖励');//企业零钱付款
                if($msg['message']=='success'){
                    $this->postText($hmember['from_user'],'进入你的微信钱包，查看奖励！'); 
                }else{
                    $this->postText($hmember['from_user'],'红包发放错误、'.$msg['message']); 
                }
            }elseif($rwlx==4){
               //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($hmember['from_user']),FILE_APPEND);
               $this->sendcardpost($hmember['from_user'],$poster['cardid']);
            }
            //有赞标签结束

            /**额外奖励**/
            if(!empty($poster['ewtype'])){
                $sjuid = mc_openid2uid($hmember['from_user']);
                //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($sjuid),FILE_APPEND);
                if($poster['ewtype']==1){//奖励积分     
                    mc_credit_update($sjuid,'credit1',intval($poster['ewjl']),array($sjuid,'任务奖励积分'));                  
                }elseif($poster['ewtype']==2){//奖励余额
                    mc_credit_update($sjuid,'credit2',$poster['ewjl'],array($sjuid,'任务奖励余额'));                  
                }
              
            }
            /**额外结束**/
            $this->tppostmsg1($hmember['from_user'],$fans,$poster,$sjsum,$share);


        }elseif($sjsum>$poster['rwmb']){//任务完成后
            $rwsjmsg=htmlspecialchars_decode($cfg["rwsjmsg"]);
            $rwsjmsg=str_replace('#昵称#',$fans['nickname'], $rwsjmsg);
            $this->postText($hmember['from_user'],$rwsjmsg);          
        }
    }


    public function tppostmsg($openid,$fans,$poster,$sjsum,$share){//任务中模版
        global $_W;
            $first=str_replace('#昵称#',$fans['nickname'],$poster['tp_first1']);
            $first=str_replace('#人气#',$sjsum,$first);
            $first=str_replace('#粉丝编号#',$share['id'],$first);
            $first=str_replace('#换行#',"\r\n", $first);

            $remark=str_replace('#完成#',$sjsum,$poster['tp_remark1']);
            $syrs=$poster['rwmb']-$sjsum;//剩余人数
            $remark=str_replace('#剩余#',$syrs, $remark);
            $remark=str_replace('#粉丝编号#',$share['id'], $remark);
            $remark=str_replace('#换行#',"\r\n", $remark);

            $poster['tp_first1']=$first;
            $poster['tp_remark1']=$remark;


            $tp_value1 = unserialize($poster['tp_value1']);
            $tp_value1=str_replace('#时间#',date('Y-m-d H:i:s',time()),$tp_value1);
            $tp_value1=str_replace('#昵称#',$fans['nickname'],$tp_value1);
            $tp_value1=str_replace('#粉丝编号#',$share['id'],$tp_value1);
            $tp_color1 = unserialize($poster['tp_color1']);
            $tplist1=array(
                          'first' => array(
                          'value' => $poster['tp_first1'],
                          "color" => $poster['firstcolor1']
                          )
            );
            foreach ($tp_value1 as $key => $value) {  
                if(empty($value)){
                  continue;
                }
                //$key++;
                $tplist1['keyword'.$key] = array('value'=>$value,'color'=>$tp_color1[$key]);
            }
            $tplist1['remark']=array(
                               //'value' => '\r\n'.$poster['tp_remark1'],
                                'value' => $poster['tp_remark1'],
                                "color" => $poster['remarkcolor1']
                            );
      //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($this->message),FILE_APPEND);
      $this->sendMsg($openid,$poster['rwmbtxid1'], $tplist1,'客服消息');
    
    
    }


    public function tppostmsg1($openid,$fans,$poster,$sjsum,$share){//任务完在模版
        global $_W;

            $first=str_replace('#昵称#',$fans['nickname'],$poster['tp_first']);
            $first=str_replace('#人气#',$sjsum,$first);
            $first=str_replace('#粉丝编号#',$share['id'],$first);
            $first=str_replace('#换行#',"\r\n", $first);

            $remark=str_replace('#完成#',$sjsum,$poster['tp_remark']);
            $syrs=$poster['rwmb']-$sjsum;//剩余人数
            $remark=str_replace('#剩余#',$syrs, $remark);
            $remark=str_replace('#粉丝编号#',$share['id'], $remark);
            $remark=str_replace('#换行#',"\r\n", $remark);

            $poster['tp_first']=$first;
            $poster['tp_remark']=$remark;


            $tp_value1 = unserialize($poster['tp_value']);
            $tp_value1=str_replace('#时间#',date('Y-m-d H:i:s',time()),$tp_value1);
            $tp_value1=str_replace('#昵称#',$fans['nickname'],$tp_value1);
            $tp_value1=str_replace('#粉丝编号#',$share['id'],$tp_value1);
            $tp_color1 = unserialize($poster['tp_color']);
            $tplist1=array(
                          'first' => array(
                          'value' => $poster['tp_first'],
                          "color" => $poster['firstcolor']
                          )
            );
            foreach ($tp_value1 as $key => $value) {  
                if(empty($value)){
                  continue;
                }
                //$key++;
                $tplist1['keyword'.$key] = array('value'=>$value,'color'=>$tp_color1[$key]);
            }
            $tplist1['remark']=array(
                               //'value' => '\r\n'.$poster['tp_remark1'],
                                'value' => $poster['tp_remark'],
                                "color" => $poster['remarkcolor']
                            );
      //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($this->message),FILE_APPEND);
      $this->sendMsg($openid,$poster['rwmbtxid'], $tplist1,'客服消息',$poster['tp_url']);
    }


     /**
	 * @name 	单发模式
	 * @param 	openid 		粉丝编号
	 * @param 	tplmsgid	模版消息id
	 * @param 	data 		数据包
     * @param 	data1 		客服消息信息
	 * @param 	url 		跳转地址
	 */
	public function sendMsg($openid, $tplmsgid, $data = array(),$data1,$url ="") {
		global $_W;
        $cfg = $this->module['config'];
		if (!empty($data)) {
			//记录存在 | 发送接口
			$account = WeAccount::create($_W['account']['acid']);
			//公号类型
			if (empty($tplmsgid)) {
				//订阅号 | 客服消息
				$this->postText($this->message['from'],$data1);
			} elseif ($_W['account']['level'] == 4) {
				//服务号 | 模板消息
                return $account->sendTplNotice($openid, $tplmsgid, $data, $url);
			}
		}
	}


    public function getnews() {//单图文推送        
        $news=array('title'=>'新会员加入提醒','description'=>'会员：小胡\r\n时间：2016','url'=>'asfadf','picurl'=>'');
        $this->sendNews($news,$this->message['from']);//图文
    }

    public function sendNews($news,$openid) {
        /*$custom = array(
                'touser' => $openid,
				'msgtype' => 'news',
				'news' => array(
                              'articles'=>array(
                                              array(
                                               'title' => '测试标题',
                                               'description' => '内容测试',
                                               'url' => 'http://www.baidu.com',
                                               'picurl' => 'http://cs.wzapi.com/attachment/headimg_2.jpg',
                                              )
                                          )
                               ),
				
			);
        $result =urldecode(json_encode($custom));*/
        $result='{"touser":"'.$openid.'","msgtype":"news","news":{"articles":[{"title":"'.$news['title'].'","description":"'.$news['description'].'","url":"'.$news['url'].'","picurl":"'.$news['picurl'].'"}]}}';
        $access_token=$this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        $ret = ihttp_request($url, $result);
		return $ret;
	}


    private $sceneid = 0;
	private $Qrcode = "/addons/tiger_renwubao/qrcode/mposter#sid#.jpg";
	private function createPoster($fans,$poster,$openid){
		global $_W;
		$bg = $poster['bg'];
		//$share = pdo_fetch('select * from '.tablename($this->modulename."_member")." where weid='{$_W['uniacid']}' and from_user='{$openid}' limit 1");
        $share = pdo_fetch('select * from '.tablename($this->modulename."_member")." where weid='{$_W['uniacid']}' and pid='{$poster['id']}' and from_user='{$openid}' limit 1");
        
		if (empty($share)){
			pdo_insert($this->modulename."_member",
					array(
							'uid'=>$fans['uid'],
							'nickname'=>$fans['nickname'],
							'avatar'=>$fans['avatar'],
							'createtime'=>TIMESTAMP,
							'helpid'=>0,
                'pid'=>$poster['id'],
							'weid'=>$_W['uniacid'],
                            'from_user'=>$this->message['from'],
                            'unionid'=>$fans['unionid'],
                'sex'=>$fans['sex'],
                            'follow'=>1
					));
			$share['id'] = pdo_insertid();
			$share = pdo_fetch('select * from '.tablename($this->modulename."_member")." where id='{$share['id']}'");
		}else pdo_update($this->modulename."_member",array('updatetime'=>time()),array('id'=>$share['id']));


		$qrcode = str_replace('#sid#',$share['id'],IA_ROOT .$this->Qrcode);
		$data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
		include 'func.php';
		set_time_limit(0);
		@ini_set('memory_limit', '256M');
		$size = getimagesize(tomedia($bg));
		$target = imagecreatetruecolor($size[0], $size[1]);
		$bg = imagecreates(tomedia($bg));
		imagecopy($target, $bg, 0, 0, 0, 0,$size[0], $size[1]);
		imagedestroy($bg);

        
		
		foreach ($data as $value) {
			$value = trimPx($value);
			if ($value['type'] == 'qr'){
                $url = $this->getQR($fans,$poster,$share['id']);   
                //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($url),FILE_APPEND);
				if (!empty($url)){
					$img = IA_ROOT ."/addons/tiger_renwubao/qrcode/temp_qrcode.png";
					include "phpqrcode.php";
					$errorCorrectionLevel = "L";
					$matrixPointSize = "4";
					QRcode::png($url, $img, $errorCorrectionLevel, $matrixPointSize,2);
					mergeImage($target,$img,array('left'=>$value['left'],'top'=>$value['top'],'width'=>$value['width'],'height'=>$value['height']));                    
					@unlink($img);
				}
			}elseif ($value['type'] == 'img'){
				$img = saveImage($fans['avatar']);
				mergeImage($target,$img,array('left'=>$value['left'],'top'=>$value['top'],'width'=>$value['width'],'height'=>$value['height']));
				@unlink($img);
			}elseif ($value['type'] == 'name') mergeText($this->modulename,$target,$fans['nickname'],array('size'=>$value['size'],'color'=>$value['color'],'left'=>$value['left'],'top'=>$value['top']),$poster);
		}
		imagejpeg($target, $qrcode);
		imagedestroy($target);
		return $qrcode;
	}

    private function getQR($fans,$poster,$sid){        
		global $_W;
		$pid = $poster['id'];
        
        //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($this->rule),FILE_APPEND);
       // file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($this->sceneid),FILE_APPEND);
		if (empty($this->sceneid)){
           
			$share = pdo_fetch('select * from '.tablename($this->modulename."_member")." where id='{$sid}'");
            
			if (!empty($share['url'])){
				$out = false;  
				if ($poster['rtype']){
					$qrcode = pdo_fetch('select * from '.tablename('qrcode')
						." where uniacid='{$_W['uniacid']}' and qrcid='{$share['id']}' "
						." and name='".$this->modulename."' and ticket='{$share['ticketid']}' and url='{$share['url']}'");
					if($qrcode['createtime'] + $qrcode['expire'] < time()){
						pdo_delete('qrcode',array('id'=>$qrcode['id']));
						$out = true;
					}
				}
				if (!$out){
					$this->sceneid = $share['id'];
					return $share['url'];
				}
			}

            
			
			$this->sceneid = $share['id'];
			//if (empty($this->sceneid)) $this->sceneid = 50000001;
			//else $this->sceneid++;
			$barcode['action_info']['scene']['scene_id'] = $this->sceneid;
			
			load()->model('account');
			$acid = pdo_fetchcolumn('select acid from '.tablename('account')." where uniacid={$_W['uniacid']}");
			$uniacccount = WeAccount::create($acid);
			$time = 0;
			if ($poster['rtype']){
				$barcode['action_name'] = 'QR_SCENE';
				$barcode['expire_seconds'] = 30*24*3600;
				$res = $uniacccount->barCodeCreateDisposable($barcode);
				$time = $barcode['expire_seconds'];
			}else{
				$barcode['action_name'] = 'QR_LIMIT_SCENE';
				$res = $uniacccount->barCodeCreateFixed($barcode);
			}
            //$rid = $this->rule;
            $rid=$poster['rid'];
            $sql = "SELECT * FROM " . tablename('rule_keyword') . " WHERE `rid`=:rid LIMIT 1";
            $row = pdo_fetch($sql, array(':rid' => $rid));
			
			pdo_insert('qrcode',
							array('uniacid'=>$_W['uniacid'],
                            'acid'=>$acid,
                            'qrcid'=>$this->sceneid,
                            'name'=>$this->modulename,
                            'keyword'=>$row['content'],
                            'model'=>1,
                            'ticket'=>$res['ticket'],
                            'expire'=>$time,
                            'createtime'=>time(),
                            'status'=>1,
                            'url'=>$res['url']
							)
			);
			
			pdo_update($this->modulename."_member",array('sceneid'=>$this->sceneid,'ticketid'=>$res['ticket'],'url'=>$res['url'],'nickname'=>$fans['nickname'],'avatar'=>$fans['avatar']),array('id'=>$sid));
			return $res['url'];
		}
		
	}


    private function uploadImage($img) {
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->getAccessToken()."&type=image";
		$post = array('media' => '@' . $img);
		load()->func('communication');
		$ret = ihttp_request($url, $post);
		$content = @json_decode($ret['content'], true);
		return $content['media_id'];
	}


     public function sendImage($openid, $media_id) {
	    $data = array(
	      "touser"=>$openid,
	      "msgtype"=>"image",
	      "image"=>array("media_id"=>$media_id));
	    $ret = $this->postRes($this->getAccessToken(), json_encode($data));
	    return $ret;
	  }

    private function PostNews($poster,$name){//图文
		$stitle = unserialize($poster['stitle']);
		if (!empty($stitle)){
			$thumbs = unserialize($poster['sthumb']);
			$sdesc = unserialize($poster['sdesc']);
			$surl = unserialize($poster['surl']);
			foreach ($stitle as $key => $value) {
				if (empty($value)) continue;
				$response[] = array(
					'title' => str_replace('#昵称#',$name,$value),
					'description' => $sdesc[$key],
					'picurl' => tomedia( $thumbs[$key] ),
					'url' => $this->buildSiteUrl($surl[$key])
				);
			}
			if ($response) return $this->respNews($response);
		}
		return '';
	}

    


   //客服消息
    public function postText($openid, $text) {
		$post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
		$ret = $this->postRes($this->getAccessToken(), $post);
		return $ret;
	}

    private function postRes($access_token, $data) {
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
		load()->func('communication');
		$ret = ihttp_request($url, $data);
		$content = @json_decode($ret['content'], true);
		return $content['errcode'];
	}

    private function getAccessToken() {
		global $_W;
		load()->model('account');
		$acid = $_W['acid'];
		if (empty($acid)) {
			$acid = $_W['uniacid'];
		}
		$account = WeAccount::create($acid);
		//$token = $account->fetch_available_token();
        $token = $account->getAccessToken();
		return $token;
	}


    //企业零钱付款接口
  public function post_qyfk($cfg,$openid,$amount,$desc){
    global $_W;
    $root=IA_ROOT . '/attachment/tiger_renwubao/cert/'.$_W['uniacid'].'/';
    $ret=array();
  	$ret['code']=0;
    $ret['message']="success";    
    
  
    $ret['amount']=$amount;
    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    $pars = array();
    $pars['mch_appid'] =$cfg['rwappid'];
    $pars['mchid'] = $cfg['rwmchid'];
    $pars['nonce_str'] = random(32);
    $pars['partner_trade_no'] = random(10). date('Ymd') . random(3);
    $pars['openid'] =$openid;
    $pars['check_name'] = "NO_CHECK";
    $pars['amount'] =$amount;
    $pars['desc'] = "来自".$_W['account']['name']."的奖励";
    $pars['spbill_create_ip'] =$cfg['rwip']; 
    ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$cfg['rwapikey']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        //$cert=json_decode($cfg['nbfwxpaypath']);
        $extras = array();
        $extras['CURLOPT_CAINFO']= $root.'rootca.pem';
        $extras['CURLOPT_SSLCERT'] =$root.'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY'] =$root.'apiclient_key.pem';
        //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($extras['CURLOPT_CAINFO']),FILE_APPEND);
 
     
        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp['message'];
            $ret['code']=-1;
            $ret['dissuccess']=0;
            $ret['message']="-1:".$procResult;
            return $ret;            
         } else {        	
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['dissuccess']=1;
                    $ret['message']="success";
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['dissuccess']=0;
                    $ret['message']="-2:".$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['dissuccess']=0;
                $ret['message']="error response";
                return $ret;
            }
        }
    
   }


   public function setadd($location_x,$location_y) {//根据坐标获取微信地理位置地区
        global $_W;
        $lat = $location_x;
        $lng = $location_y;
        //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n old:".json_encode($lat.'---'.$lng),FILE_APPEND);
        //$openid=$message['from'];
        /////获取用户位置名称
        $content = file_get_contents("http://api.map.baidu.com/geocoder?location=".$lat.",".$lng."&output=xml&key=B73371660a1f883399dc6486f9a1c069");
        $ob= simplexml_load_string($content);
        $json  = json_encode($ob);
        $configData = json_decode($json, true);
        $address=array();
        $address['province']=$configData['result']['addressComponent']['province']; //省
        $address['city']=$configData['result']['addressComponent']['city'];//用户到所在城市
        $address['district']=$configData['result']['addressComponent']['district'];//用户到所在城市
        Return $address;
   }

   /********************************************有赞免刷新，获取TOKEN******************************************************************/
   public function accesstoken(){//使用TOKEN
       global $_W, $_GPC;
       $tk=pdo_fetch('select * from '.tablename($this->modulename."_token")." where weid='{$_W['uniacid']}'");
       if($tk['endtime']>=TIMESTAMP){//token 过期重新刷新获得
         $t=$this->RefreshToken($tk['refresh_token']);
         $data=array(
             'access_token'=>$t['access_token'],
             'refresh_token'=>$t['refresh_token'],
             'expires_in'=>$t['expires_in'],
             'scope'=>$t['scope'],
             'token_type'=>$t['token_type'],
             'endtime'=>TIMESTAMP+$t['expires_in'],
             'createtime'=>TIMESTAMP,
             
           );
           pdo_update($this->modulename."_token", $data, array('id' =>$tk['id']));
           return $t['access_token'];
       }else{
         return $tk['access_token'];
       }     
   }


    public function doWebYzauth(){//打开授权
          global $_W, $_GPC;
          //$cfg=$this->module['config'];   
          $setting=$_W['config']['setting'];
          if(empty($setting['client_id'])){
            echo '请先联系管理员，设置有赞client_id';
            exit;
          }

          $client_id=$setting['client_id'];
          $weid=$_W['uniacid'];
          $redirect_uri=$_W['siteroot'].'addons/tiger_renwubao/yztoken.php?i='.$_W['uniacid'];
         // $redirect_uri=$_W['siteroot'].'addons/tiger_renwubao/yztoken.php';
          $url="https://open.koudaitong.com/oauth/authorize?client_id=".$client_id."&response_type=code&state=renwubao&redirect_uri=".$redirect_uri."";
          header("location:".$url);//跳转到 获取token页面
          exit;
    }

    public function doMobiletoken(){//获取CODE
         global $_W, $_GPC;
         //第一部打开链接授权 获取CODE
         if(empty($_GPC['code'])){
           echo '授权失败，请查看参数设置，参数不能有空格，是否和有赞的一样，';
           exit;
         }
         $code=$_GPC['code'];
         $tk=$this->token($code);
         if(!empty($tk['error'])){
           echo '错误代码:'.$tk['error'].'<Br>'.$tk['error_description'];
           exit;
         }
         //echo '<pre>';
         //print_r($tk);
         //exit;
         //获取的TOKEN保存
         $token=pdo_fetch('select * from '.tablename($this->modulename."_token")." where weid='{$_W['uniacid']}'");
         if(empty($token)){
           $data=array(
             'weid'=>$_W['uniacid'],
             'access_token'=>$tk['access_token'],
             'refresh_token'=>$tk['refresh_token'],
             'expires_in'=>$tk['expires_in'],
             'scope'=>$tk['scope'],
             'token_type'=>$tk['token_type'],
             'endtime'=>TIMESTAMP+$tk['expires_in'],
             'createtime'=>TIMESTAMP,
             
           );
           pdo_insert($this->modulename."_token", $data);
         }else{
            $data=array(
             'access_token'=>$tk['access_token'],
             'refresh_token'=>$tk['refresh_token'],
             'expires_in'=>$tk['expires_in'],
             'scope'=>$tk['scope'],
             'token_type'=>$tk['token_type'],
             'endtime'=>TIMESTAMP+$tk['expires_in'],
             'createtime'=>TIMESTAMP,
             
           );
            pdo_update($this->modulename."_token", $data, array('id' =>$token['id']));
         }
         message('授权成功！',$_W['siteroot'].'/web/index.php?c=profile&a=module&do=setting&m=tiger_renwubao', 'success');
         
    }

    public function token($code){//获取token 7天有效
        global $_W, $_GPC;
      load()->func('communication');
      //$cfg=$this->module['config']; 
      $setting=$_W['config']['setting'];
      $client_id=$setting['client_id'];
      $client_secret=$setting['client_secret'];
      $weid=$_W['uniacid'];
      $redirect_uri=$_W['siteroot'].'addons/tiger_renwubao/yztoken.php?i='.$_W['uniacid'];
      $tkurl="https://open.koudaitong.com/oauth/token?client_id=".$client_id."&client_secret=".$client_secret."&grant_type=authorization_code&code=".$code."&redirect_uri=".$redirect_uri."";
      $to=ihttp_get($tkurl);
      $auth = @json_decode($to['content'], true); 
      return $auth;
      /*获得TOKNE
         Array
            (
                [access_token] => bb16c620c50f3c1cada1517059a957c9
                [expires_in] => 604800   //7天有效期,过期重新获取
                [refresh_token] => 086abc0160ab30f682d0b8efc5ebe8d9  //过期时间：28 天
                [scope] => item trade trade_virtual user utility shop item_category logistics pay_qrcode coupon present_advanced item_category_advanced
                [token_type] => Bearer
            )
         */
    }


    public function RefreshToken($refresh_token){//token过期后，刷新refresh_token，获得新的token
        global $_W, $_GPC;
      load()->func('communication');
      //$cfg=$this->module['config'];   
      $setting=$_W['config']['setting'];
      $client_id=$setting['client_id'];
      $client_secret=$setting['client_secret'];
      $tkurl="https://open.koudaitong.com/oauth/token?grant_type=refresh_token&refresh_token=".$refresh_token."&client_id=".$client_id."&client_secret=".$client_secret."";
      $to=ihttp_get($tkurl);
      $auth = @json_decode($to['content'], true); 
      return $auth;
      /*
      Array
        (
            [access_token] => b9581b76660730feb1e2577dc1afb6e1
            [expires_in] => 604800
            [refresh_token] => e07f1e25abb6329abe996b6e257d5cb7
            [scope] => item trade trade_virtual user utility shop item_category logistics pay_qrcode coupon present_advanced item_category_advanced
            [token_type] => Bearer
        )*/

    }
/********************************************有赞免刷新，获取TOKEN 结束******************************************************************/



 //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>微信卡券开始
   
   public function doMobileCard() {//案例
      //http://wx.youqi18.com/app/index.php?i=3&c=entry&do=card&m=tiger_youzan
      global $_W;
      $this->sendcardpost($_W['openid'],'pozm3txI6W-Fcxndth6AlSONkZqE');
   }

   public function sendcardpost($openid,$cardid){
      global $_W;
      $getticket=$this->getticket();
      $createNonceStr=$this->createNonceStr();
      $signature=$this->signature($getticket,$createNonceStr);
      $account = WeAccount::create();
      $card_ext=array(
                   'openid' => $openid,
                   'timestamp' => strval(TIMESTAMP),
                   'signature' => $signature,
                  );
      $custom = array(
            'touser' => $openid,
            'msgtype' => 'wxcard',
            'wxcard' => array(
                          'card_id'=>$cardid,
                          'card_ext'=>$card_ext
                           ),
            
       );
      $account->sendCustomNotice($custom);     
   }

   public function doMobileCardd(){//二维码领取卡券有效
      $data11 = array(
               'action_name' => "QR_CARD",
			   'expire_seconds' => 1800,
			   'action_info' => array('card' => array('card_id' => "pozm3txI6W-Fcxndth6AlSONkZqE",
													 // // 'code' => "198374613512",
													 // // 'openid' => "oFS7Fjl0WsZ9AMZqrI80nbIq8xrA",
													  'is_unique_code' => false,
													  'outer_id' => 100),
								 ),
			  );
      $result = $this->create_card_qrcode($data11);
      echo '<pre>';
      print_r($result);
      echo "<img src='{$result['show_qrcode_url']}'>";
   }

   //创建二维码接口
    public function create_card_qrcode($data){   
        $access_token=$this->getAccessToken();
        $url = "https://api.weixin.qq.com/card/qrcode/create?access_token=".$access_token;
        $res = $this->http_web_request($url, json_encode($data));
        return json_decode($res, true);
    }

    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    protected function http_web_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
   

   //生成ticket
   public function getticket() {
       global $_W;
       $data=pdo_fetch("SELECT * FROM " . tablename($this->modulename."_ticket") . " WHERE weid = '{$_W['weid']}'");
       if(empty($data)){
             $access_token=$this->getAccessToken();
             //$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
               $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
             $json = ihttp_get($url);
			 $res = @json_decode($json['content'], true);
             if(empty($ticket)){
               $kjdata=array(
                   'weid'=>$_W['uniacid'],
                   'ticket'=>$res['ticket'],
                   'createtime'=>TIMESTAMP + 7000,
               );
               pdo_insert($this->modulename."_ticket",$kjdata);
             } 
            Return $res['ticket'];
       }else{
         if($data['createtime']<time()){
             $access_token=$this->getAccessToken();
             $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
             $json = ihttp_get($url);
			 $res = @json_decode($json['content'], true);
             if(empty($ticket)){
               $kjdata=array(
                   'ticket'=>$ticket,
                   'createtime'=>TIMESTAMP + 7000,
               );
               pdo_update($this->modulename."_ticket",$kjdata,array('weid'=>$_W['uniacid']));
             }
             Return $res['ticket'];
           }else{
             Return $data['ticket'];
           }
       }
       
    
   }

   //生成nonce_str
   private function createNonceStr($length = 16) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $str = "";
      for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      }
      return $str;
    }

    //生成signature
   public function signature($api_ticket,$nonce_str) {
       $obj['api_ticket']          = $api_ticket; 
       $obj['timestamp']           = TIMESTAMP;
       $obj['nonce_str']           = $nonce_str; 
       $signature  = $this->get_card_sign($obj);
       Return $signature;
   }

    //生成签名
    public function get_card_sign($bizObj){
        //字典序排序
        asort($bizObj);
        //URL键值对拼成字符串
        $buff = "";
        foreach ($bizObj as $k => $v){
            $buff .= $v;
        }
        //sha1签名
        return sha1($buff);
    }

   //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>微信卡券结束










}