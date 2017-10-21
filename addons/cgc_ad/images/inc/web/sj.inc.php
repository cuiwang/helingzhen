<?php
	

		global $_W,$_GPC;

		$weid=$_W['uniacid'];

		checklogin();



		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

	    if ($operation == 'display') {

	        if(!empty($_GPC['quan_id']))

	        {

	        	$condition.=" AND a.quan_id= ".intval($_GPC['quan_id']);

	        }

	        if(!empty($_GPC['keyword']))
	        {

	        	$condition.=" AND a.nickname LIKE '%{$_GPC['keyword']}%' ";

	        }

	        $pindex = max(1, intval($_GPC['page']));

			$psize = 20;

	        $quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

	        $category = pdo_fetchall("SELECT a.*,b.aname FROM " . tablename('cgc_ad_member') . " as a left join ".tablename('cgc_ad_quan')." as b on a.quan_id=b.id WHERE a.weid = '{$_W['weid']}' AND (a.type=3 or a.type=4 )  ".$condition." LIMIT ".($pindex - 1) * $psize.",{$psize}");

	        $total = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('cgc_ad_member') . " as a WHERE a.weid = '{$_W['weid']}' AND (a.type=3 or a.type=4 ) ".$condition);

	        $pager = pagination($total, $pindex, $psize);

	        include $this->template('web/sj');

	    } elseif ($operation == 'post') {

	    	load()->func('tpl');



	    	$quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

	        $id = intval($_GPC['id']);

	        if (!empty($id)) {

	            $category = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_member') . " WHERE id = '$id' AND ( type=3 or type=4 ) ");

	            if(empty($category))

	            {

	            	message("该用户不存在或者不是商家用户，返回重试");

	            }

	        } else {

	            $category = array(
                    'type' =>3,
	                'status' =>1,

	            );

	        }



	        if (checksubmit('submit')) {

	            if (empty($_GPC['nickname'])) {

	                message('抱歉，请输入商家昵称！');

	            }

	            if (empty($_GPC['quan_id'])) {

	                message('抱歉，请选择商家所属平台！');

	            }

	            if (empty($_GPC['headimgurl'])) {

	                message('抱歉，请上传商家头像！');

	            }

	            $data = array(

	                'weid' => $_W['weid'],

	            /*    'nicheng' => $_GPC['nicheng'],

	                'thumb' => $_GPC['thumb'],*/
	                
	                'nickname' => $_GPC['nickname'],

	                'headimgurl' => $_GPC['headimgurl'],

	                'quan_id' => intval($_GPC['quan_id']),

	                'status' => intval($_GPC['status']),
                     'openid' => ($_GPC['openid']),
	            );

	          $data['type']=$_GPC['type'];

	        


	            if (!empty($id)) {

	                pdo_update('cgc_ad_member', $data, array('id' => $id));

	            } else {

	            	$data['fabu']=0;

	            	$data['rob']=0;

	            	$data['credit']=0;

	            	$data['createtime']=TIMESTAMP;

	                pdo_insert('cgc_ad_member', $data);

	                $id = pdo_insertid();

	            }

	            message('更新商家用户数据成功！', $this->createWebUrl('sj', array('op' => 'display')), 'success');

	        }

	        include $this->template('web/sj');

	    } elseif ($operation == 'del') {

	        $id = intval($_GPC['id']);



			$temp=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND id=".$id);

			if(empty($temp))

			{

				echo 2;

				exit;

			}

			else

			{

				if($temp['type']!=3 && $temp['type']!=4 )

				{

					echo 3;

					exit;

				}

				else

				{

					$fabu=pdo_fetch("SELECT id FROM ".tablename('cgc_ad_adv')." WHERE weid=".$weid." AND mid=".$id." AND status!=0 LIMIT 1 ");

					if(!empty($fabu))

					{

						echo 4;

						exit;

					}

					else

					{

						pdo_delete('cgc_ad_member', array('id' => $id));

						echo 1;

						exit;

					}

				}

			}

	    }