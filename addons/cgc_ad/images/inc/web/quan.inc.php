<?php
	

		global $_W,$_GPC;

		$weid=$_W['uniacid'];

		checklogin();



		load()->func('tpl');

		$op=$_GPC['op'];

		if(empty($op))

		{

			$op='display';

		}

		if($op=='display')

		{

			$list=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0 ");

			if(!empty($list))

			{

				foreach ($list as $key => $value) {

					$list[$key]['user']=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$value['id']);

					$list[$key]['adv']=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_adv')." WHERE weid=".$weid." AND status!=0 AND del=0 AND quan_id=".$value['id']);

					$list[$key]['adv_fee']=pdo_fetchcolumn("SELECT sum(total_amount) FROM ".tablename('cgc_ad_adv')." WHERE weid=".$weid." AND status!=0 AND status!=5 AND del=0 AND quan_id=".$value['id']);

					// 处理活动入口
					$url = $this->createMobileUrl('index', array('quan_id' => $list[$key]['id']));
					$list[$key]['surl'] = $url;
					$url = substr($url, 2);
					$url = $_W['siteroot'] . 'app/' . $url;
					$list[$key]['url'] = $url;
						
				}

			}

			$total=count($list);

		}

		else if($op=='yure_del')

		{

			$quan_id=$_GPC['quan_id'];

			$yureid=intval($_GPC['yureid']);

			$temp=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_yure')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND id=".$yureid);

			if(empty($temp))

			{

				echo 2;

				exit;

			}

			else

			{

				pdo_delete('cgc_ad_yure',array('id'=>$yureid));

				echo 1;

				exit;

			}

		}

		else if($op=='yure')

		{

			$quan_id=$_GPC['quan_id'];

			if(empty($quan_id))

			{

				echo json_encode(array('status'=>5));

				exit;

			}

			else

			{

				$fee=$_GPC['fee'];

				if(empty($fee))

				{

					echo json_encode(array('status'=>4));

					exit;

				}

				else

				{

					$time=intval($_GPC['time']);

					if(empty($time))

					{

						echo json_encode(array('status'=>3));

						exit;

					}

					else

					{

						$yureid=intval($_GPC['yureid']);

						if(empty($yureid))

						{

							$temp=pdo_fetch("SELECT id FROM ".tablename('cgc_ad_yure')." WHERE weid=".$weid." AND fee=".$fee." AND quan_id=".$quan_id);

							if(!empty($temp))

							{

								echo json_encode(array('status'=>2));

								exit;

							}

							else

							{

								$data=array(

									'weid'=>$weid,

									'quan_id'=>$quan_id,

									'fee'=>$fee,

									'time'=>$time,

								);

								pdo_insert("cgc_ad_yure",$data);

								$yureid=pdo_insertid();

								echo json_encode(array('status'=>1,'yureid'=>$yureid));

								exit;

							}

						}

						else

						{

							$temp=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_yure')." WHERE weid=".$weid." AND fee=".$fee." AND quan_id=".$quan_id." AND id <>".$yureid);

							if(!empty($temp))

							{

								echo json_encode(array('status'=>2));

								exit;

							}

							else

							{

								$data=array('fee'=>$fee,'time'=>$time);

								pdo_update('cgc_ad_yure',$data,array('id'=>$yureid));

								echo json_encode(array('status'=>1,'yureid'=>$yureid));

								exit;

							}

						}

					}

				}

			}

		}

		else if($op=='post')

		{

			$id=$_GPC['id'];

			if(!empty($id))

			{

				$item=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND id=".$id);

				$yure=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_yure')." WHERE weid=".$weid." AND quan_id=".$id." ORDER BY fee ASC ");

			}

			if(empty($item))

			{

				$item['sharelogo']='../addons/cgc_ad/icon.jpg';

				$item['aname']='广告投放平台';

				$item['rule']='<section style="border: 0px; margin: 0.8em 0px 0.5em; overflow: hidden; padding: 0px; box-sizing: border-box !important;" ><section style="display: inline-block; font-size: 1em; font-family: inherit; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(249, 110, 87); box-sizing: border-box;" ><section style="display: inline-block; margin: 0px; line-height: 1.4em; padding: 0.3em 0.5em; height: 2em; vertical-align: top; font-size: 1em; font-family: inherit; box-sizing: border-box; background: rgb(249, 110, 87);" ><section  style="box-sizing: border-box;">能发什么</section></section><section style="width: 0.5em; display: inline-block; height: 2em; vertical-align: top; border-bottom-width: 1em; border-bottom-style: solid; border-bottom-color: rgb(249, 110, 87); border-top-width: 1em; border-top-style: solid; border-top-color: rgb(249, 110, 87); font-size: 1em; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important; box-sizing: border-box;" ></section></section><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin-top: 0px; margin-bottom: 0px; clear: both; font-size: 1em; font-family: inherit; text-align: justify; text-decoration: inherit; color: inherit; box-sizing: border-box; padding: 0px;" ><section  style="box-sizing: border-box;"><span style="text-align: justify;">可以发信息，晒心情，秀自己等等，任何一切合法的内容都可以发到圈子里。唯一的要求是，发内容的同时要撒点儿小钱给大家哦~。</span></section><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin: 0.8em 0px 0.5em; overflow: hidden; padding: 0px; box-sizing: border-box !important;" ><section style="display: inline-block; font-size: 1em; font-family: inherit; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(249, 110, 87); box-sizing: border-box;" ><section style="display: inline-block; margin: 0px; line-height: 1.4em; padding: 0.3em 0.5em; height: 2em; vertical-align: top; font-size: 1em; font-family: inherit; box-sizing: border-box; background: rgb(249, 110, 87);" ><section  style="box-sizing: border-box;">规则说明</section></section><section style="width: 0.5em; display: inline-block; height: 2em; vertical-align: top; border-bottom-width: 1em; border-bottom-style: solid; border-bottom-color: rgb(249, 110, 87); border-top-width: 1em; border-top-style: solid; border-top-color: rgb(249, 110, 87); font-size: 1em; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important; box-sizing: border-box;" ></section></section><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin-top: 0px; margin-bottom: 0px; clear: both; font-size: 1em; font-family: inherit; text-align: justify; text-decoration: inherit; color: inherit; box-sizing: border-box; padding: 0px;" ><p style="box-sizing: border-box;"><span style="text-align: justify;">预热：内容发布后即在圈子内展示，经过一段预热时间后，该内容中的金额才能被抢。撒出的金额越高，预热时间越长。</span></p><p style="box-sizing: border-box;"><span style="text-align: justify;"><span style="text-align: justify;">份数：撒出的金额将分成设定的份数，只有在排在份数的前几位才能抢到，且各份金额随机分配。</span></span></p><p style="box-sizing: border-box;"><span style="text-align: justify;"><span style="text-align: justify;"><span style="text-align: justify;">置顶：当撒出的金额达到200元，则该内容会被置顶，处于置顶中的多个内容将按金额高低排序展示。</span></span></span></p><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin: 0.8em 0px 0.5em; overflow: hidden; padding: 0px; box-sizing: border-box !important;" ><section style="display: inline-block; font-size: 1em; font-family: inherit; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(249, 110, 87); box-sizing: border-box;" ><section style="display: inline-block; margin: 0px; line-height: 1.4em; padding: 0.3em 0.5em; height: 2em; vertical-align: top; font-size: 1em; font-family: inherit; box-sizing: border-box; background: rgb(249, 110, 87);" ><section  style="box-sizing: border-box;">服务费用</section></section><section style="width: 0.5em; display: inline-block; height: 2em; vertical-align: top; border-bottom-width: 1em; border-bottom-style: solid; border-bottom-color: rgb(249, 110, 87); border-top-width: 1em; border-top-style: solid; border-top-color: rgb(249, 110, 87); font-size: 1em; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important; box-sizing: border-box;" ></section></section><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin-top: 0px; margin-bottom: 0px; clear: both; font-size: 1em; font-family: inherit; text-align: justify; text-decoration: inherit; color: inherit; box-sizing: border-box; padding: 0px;" ><section  style="box-sizing: border-box;"><span style="text-align: justify;">每次发布内容时，我们将收取总金额的5%作为圈子服务费用，用于圈子的运作成本和持续运营。</span></section><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin: 0.8em 0px 0.5em; overflow: hidden; padding: 0px; box-sizing: border-box !important;" ><section style="display: inline-block; font-size: 1em; font-family: inherit; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(249, 110, 87); box-sizing: border-box;" ><section style="display: inline-block; margin: 0px; line-height: 1.4em; padding: 0.3em 0.5em; height: 2em; vertical-align: top; font-size: 1em; font-family: inherit; box-sizing: border-box; background: rgb(249, 110, 87);" ><section  style="box-sizing: border-box;">特别注意</section></section><section style="width: 0.5em; display: inline-block; height: 2em; vertical-align: top; border-bottom-width: 1em; border-bottom-style: solid; border-bottom-color: rgb(249, 110, 87); border-top-width: 1em; border-top-style: solid; border-top-color: rgb(249, 110, 87); font-size: 1em; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important; box-sizing: border-box;" ></section></section><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin-top: 0px; margin-bottom: 0px; clear: both; font-size: 1em; font-family: inherit; text-align: justify; text-decoration: inherit; color: inherit; box-sizing: border-box; padding: 0px;" ><p style="box-sizing: border-box;"><span style="text-align: justify;">内容发布后，将无法取消，撒出的金额也无法退款撤回，直至被抢完为止。</span></p><p style="box-sizing: border-box;"><span style="text-align: justify;">严禁发布虚假、色情、暴力、欺诈等非法内容，圈子有权随时删除违规内容，相关金额概不退款。</span></p><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin: 0.8em 0px 0.5em; overflow: hidden; padding: 0px; box-sizing: border-box !important;" ><section style="display: inline-block; font-size: 1em; font-family: inherit; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(249, 110, 87); box-sizing: border-box;" ><section style="display: inline-block; margin: 0px; line-height: 1.4em; padding: 0.3em 0.5em; height: 2em; vertical-align: top; font-size: 1em; font-family: inherit; box-sizing: border-box; background: rgb(249, 110, 87);" ><section  style="box-sizing: border-box;">内容声明</section></section><section style="width: 0.5em; display: inline-block; height: 2em; vertical-align: top; border-bottom-width: 1em; border-bottom-style: solid; border-bottom-color: rgb(249, 110, 87); border-top-width: 1em; border-top-style: solid; border-top-color: rgb(249, 110, 87); font-size: 1em; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important; box-sizing: border-box;" ></section></section><section style="width: 0px; height: 0px; clear: both;"></section></section><section style="border: 0px; margin-top: 0px; margin-bottom: 0px; font-size: 1em; font-family: inherit; text-align: justify; text-decoration: inherit; color: inherit; box-sizing: border-box; padding: 0px;" ><section  style="box-sizing: border-box;"><span style="text-align: justify;">内容由发布者承担相关法律责任，平台不支持任何言论。</span></section><section style="width: 0px; height: 0px; clear: both;"></section></section>';

				$item['notice']='【通知】商家点击“我要发广告”即可发布红包！';

				$item['sharetitle']='广告投放平台';

				$item['begin_time']='0';

				$item['over_time']='24';

				$item['cold_time']='120';

				$item['top_line']='100.00';

				$item['total_min']='1.00';

				$item['total_max']='200.00';

				$item['avg_min']='0.01';
				
				$item['total_min2']='1.00';
				
				$item['total_max2']='200.00';
				
				$item['avg_min2']='0.01';
				
				$item['groupmax']='5';
				
				$item['percent']='5';

				$item['tx_min']='2.00';

				$item['status']='1';

				$item['shenhe']='0';

				$item['follow_logo']='../addons/cgc_ad/images/attent.png';
				
				$item['piece_model'] = '1,2,3,4';
				
				$item['up_rob_fee'] = '10';
				
				$item['up_send_fee'] = '10';
				
				$item['total_min4']='1.00';
				
				$item['total_max4']='200.00';
				
				$item['avg_min4']='0.01';
							

			}
			
			if(!empty($item['piece_model'])){
			  $item['piece_model'] = explode(',',$item['piece_model']);
			}


			if(checksubmit()) {

				$data=array(

						'weid'=>$weid,

						'aname'=>$_GPC['aname'],

						'rule'=>$_GPC['rule'],

						'notice'=>$_GPC['notice'],

						'begin_time'=>$_GPC['begin_time'],

						'over_time'=>$_GPC['over_time'],

						'cold_time'=>$_GPC['cold_time'],
						
						'total_min'=>$_GPC['total_min'],

						'total_max'=>$_GPC['total_max'],

						'avg_min'=>$_GPC['avg_min'],
						
						'total_min2'=>$_GPC['total_min2'],
						
						'total_max2'=>$_GPC['total_max2'],
						
						'avg_min2'=>$_GPC['avg_min2'],
						
						'groupmax'=>$_GPC['groupmax'],

						'top_line'=>$_GPC['top_line'],

						'percent'=>$_GPC['percent'],

						'tx_min'=>$_GPC['tx_min'],

						'status'=>$_GPC['status'],

						'shenhe'=>$_GPC['shenhe'],

						'city'=>$_GPC['city'],

						'follow_url'=>$_GPC['follow_url'],

						'follow_logo'=>$_GPC['follow_logo'],

						'sharetitle'=>$_GPC['sharetitle'],
						'sharedesc'=>$_GPC['sharedesc'],

						'sharelogo'=>$_GPC['sharelogo'],

						'update_time'=>TIMESTAMP,
						'is_message'=>$_GPC['is_message'],
						'is_pl'=>$_GPC['is_pl'],
						'is_follow'=>$_GPC['is_follow'],
						
						'up_rob_fee'=>$_GPC['up_rob_fee'],
						
						'up_send_fee'=>$_GPC['up_send_fee'],
						
						'total_min4'=>$_GPC['total_min4'],
						
						'total_max4'=>$_GPC['total_max4'],
						
						 'avg_min4'=>$_GPC['avg_min4'],
						 'tx_follow'=>trim($_GPC['tx_follow']),					
						 'pp_openid' =>trim($_GPC['pp_openid']),
						 'pp_mode' =>trim($_GPC['pp_mode']),
						 'group_guanzhu' =>trim($_GPC['group_guanzhu']),
						
						'task_submit_switch' =>trim($_GPC['task_submit_switch']),
						'hx_switch' =>trim($_GPC['hx_switch']),
							'views' =>trim($_GPC['views']),
						

					);
					
				
				if(!empty($_GPC['piece_model'])){
					$data['piece_model'] = implode(',',$_GPC['piece_model']);
				}

				if(empty($item['id']))

				{

					$data['create_time']=TIMESTAMP;

					pdo_insert("cgc_ad_quan",$data);

				}

				else

				{

					pdo_update("cgc_ad_quan",$data,array('weid'=>$weid,'id'=>$id));

				}

				message("设置成功!",$this->createWebUrl('quan'),'success');

			}
		}

		elseif ($op=='del') {

			$id=$_GPC['id'];

			pdo_update("cgc_ad_quan",array('del'=>1),array('id'=>$id));

			message("删除成功!",$this->createWebUrl('quan'),'success');

		}

		include $this->template('web/quan');