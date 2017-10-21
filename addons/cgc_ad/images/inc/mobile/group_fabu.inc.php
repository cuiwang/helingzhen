<?php

    //组团抢红包页面
    global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']); 
    $quan=$this->get_quan();   
    $member=$this->get_member();
    $from_user=$member['openid'];

    $config=$this->settings;
    $mid=$member['id'];
    $op=empty($_GPC['op'])?"display":$_GPC['op'];
    
    
     if($op=='add'){
                   $content = $_GPC['content'];
                   $link = $_GPC['link'];
                   $total_num = intval($_GPC['total_num']); // 红包数（个）
                   $total_amount = floatval($_GPC['total_amount']);  // 红包总额（元）
                   $hot_time = intval($_GPC['hot_time']);  // 预热时间（秒）
                   $fee = floatval($_GPC['fee']);  // 服务费（元）
                   $total_pay = floatval($_GPC['total_pay']);// 应付总额（元）
                   $group_size = intval($_GPC['group_size']);  // 组团人数
				   // 内容验证
                   if(empty($content)){
                     $this->returnError('请说点儿什么吧~');
                   }
                   if($this->text_len($content)>5000){
                     $this->returnError('内容不能超过5000字哦~');
                   }
				   //验证组团人数
				   if(empty($group_size) || $group_size<=0){
				     $this->returnError('请填组团人数');
				   }
							
				   if($group_size<2||$group_size>$quan['groupmax']){
				     $this->returnError('组团人数应为2~'.$quan['groupmax'].'人');
				   }
                   
                   if($this->text_len($content)>5000){
                     $this->returnError('内容不能超过5000字哦~');
                   }
				    // link格式化
                  if(!empty($link)){

								if (!preg_match("/^(http|ftp):/", $link)){

								   $link='http://'.$link;

								}

							}

							if($this->text_len($link)>500){

								$this->returnError('链接内容超长啦！');

							}



							// 广告总额验证

							if(empty($total_amount) || $total_amount<=0){

								$this->returnError('请填广告金额');

							}

							if($total_amount<$quan['total_min2']){

								$this->returnError('广告金额不能低于'.$quan['total_min2'].$config['uni_text']);

							}

							if($total_amount>$quan['total_max2']){

								$this->returnError('广告金额不能超过'.$quan['total_max2'].$config['uni_text']);

							}



							// 红包个数验证

							if(empty($total_num) || $total_num<1){

								$this->returnError('请填写份数');

							}

							if($total_num>intval($total_amount/$quan['avg_min2'])){

								$this->returnError($total_amount.$config['uni_text'].'最多可分'.intval($total_amount/$quan['avg_min2']).'份');

							}



							$least_amount=max($quan['total_min2'], $total_num*$quan['avg_min2']);

							if($total_amount<$least_amount){

								$this->returnError('红包总金额不能低于'.$least_amount.$config['uni_text']);

							}





							// 服务费验证

							$the_fee = intval($total_amount*$quan['percent'])/100;

							if($the_fee!=$fee){

								$this->returnError('服务费已变化，请刷新后重新发布');

							}



							// 应付总额验证

							$the_pay=intval(($total_amount+$the_fee)*100)/100;

							if($the_pay!=$total_pay){

								$this->returnError($the_pay.'金额计算出错，请刷新后重新发布'.$total_pay);

							}


							// 如果广告总额大于等于置顶线，则需要设置置顶级别

							$top_level=0;

							if($quan['top_line']>0 && ($total_amount)>=$quan['top_line']){

								$top_level=$total_amount*100;

							}



							// 处理图片

							$images=$_GPC['images'];

							// $this->returnError('图片上传失败:'."SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);

							$sitem=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_setting')." WHERE weid=".$weid);

							// 从微信服务器下载用户上传的图片

							if(!empty($images) && count($images)>0){

								load()->func('file');

								$down_images=array();

								// 从微信服务器下载图片

								$WeiXinAccountService = WeiXinAccount::create($_W['acid']);

								foreach($images as $imgid){

									$ret=$WeiXinAccountService->downloadMedia(array(

										'media_id'=>$imgid,

										'type'=>'image'

									));



									if(is_error($ret)){
								      $this->returnError('图片上传失败:'.$ret['message']);
									}

									if($sitem['is_qniu']==1  && empty($_W['setting']['remote']['type'])){
                                      $ret=$this->VP_IMAGE_SAVE($ret);
                                      if(!empty($ret['error'])){
                                         $this->returnError('上传图片失败:'.$ret['error']);
                                       }

										$down_images[]=$ret['image'];

									}else{

										$down_images[]=$ret;

									}


								}

								$images = iserializer($down_images);

							}
							
						    if($_GPC['allocation_way']=='1'){
						    	//生成平均分配方案
						    	$plan = $this -> red_average_plan($total_amount, $total_num);
						    }
						    else{
								// 生成随机分配方案
							    $plan = $this -> red_plan($total_amount, $total_num, $quan['avg_min']);
						    }
						    $plan = implode(',', $plan);


							$data=array(

								'weid'=>$weid,

								'quan_id'=>$quan['id'],

								'mid'=>$mid,

								//'title'=>$title,

								'content'=>$content,

								'images'=>$images,

								'link'=>$link,

								'total_num'=>$total_num,

								'total_amount'=>$total_amount,

								'hot_time'=>$hot_time,

								'top_level'=>$top_level,

								'fee'=>$fee,

								'total_pay'=>$the_pay,

								'status'=>0,

								'views'=>0,

								'links'=>0,

								'rob_plan'=>$plan,

								'rob_amount'=>0,

								'rob_users'=>0,

								'create_time'=>time(),
								
								'openid'=>$member['openid'],

								'nickname'=>$member['nickname'],

								'headimgurl'=>$member['headimgurl'],
						
								'model'=>2,

								'group_size'=>$group_size,

								'allocation_way'=> $_GPC['allocation_way'],

							);

							pdo_insert('cgc_ad_adv', $data);

							$quan_id = pdo_insertid();

							if($quan_id>0){

								$tid=TIMESTAMP.$quan_id;



								// 生成订单和支付参数

								$params = array(

									'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码

									'ordersn' => $quan_id,  //收银台中显示的订单号

									'title' => $quan['aname'].'投放广告',          //收银台中显示的标题

									'fee' => $the_pay,      //收银台中显示需要支付的金额,只能大于 0

								//	'user' => $user['nickname'],     //付款用户, 付款的用户名(选填项)

									'openid' => $from_user,
										
									'pay_type' => $this->settings['pay_type']

								);
								
								//调用pay方法

								$params=$this->pay($params);
								
								if($this->settings['pay_type']==1){
									$this->returnSuccess('',json_encode($params));
								}



								$this->returnSuccess('',base64_encode(json_encode($params)));

							}else{

								$this->returnError('发表失败，请重试');

							}

						}

						else

						{

							$yure=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_yure')." WHERE weid=".$weid." AND quan_id=".$quan_id." ORDER BY fee ASC ");

							$yure_arr=array();

							if(!empty($yure))

							{

								foreach ($yure as $key => $value) {

									$yure_arr[$value['fee']]=$value['time'];

								}

							}

							include $this->template('group_fabu');

						}

					



