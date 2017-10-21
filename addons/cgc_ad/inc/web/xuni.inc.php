<?php


		global $_W,$_GPC;

		$weid=$_W['uniacid'];



		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

	    if ($operation == 'display') {

	        if(!empty($_GPC['quan_id']))

	        {

	        	$condition.=" AND a.quan_id= ".intval($_GPC['quan_id']);

	        }

	        if(!empty($_GPC['keyword']))

	        {

	        	$condition.=" AND a.nicheng LIKE '%{$_GPC['keyword']}%' ";

	        }

	        $pindex = max(1, intval($_GPC['page']));

			$psize = 20;

	        $quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

	        $category = pdo_fetchall("SELECT a.*,b.aname FROM " . tablename('cgc_ad_member') . " as a left join ".tablename('cgc_ad_quan')." as b on a.quan_id=b.id WHERE a.weid = '{$_W['weid']}' AND a.type=2 ".$condition." LIMIT ".($pindex - 1) * $psize.",{$psize}");

	        $total = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('cgc_ad_member') . " as a WHERE a.weid = '{$_W['weid']}' AND a.type=2 ".$condition);

	        $pager = pagination($total, $pindex, $psize);

	        include $this->template('web/xuni');

	    } elseif ($operation == 'post') {

	    	load()->func('tpl');



	    	$quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

	        $id = intval($_GPC['id']);

	        if (!empty($id)) {

	            $category = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_member') . " WHERE id = '$id' AND type=2 ");

	            if(empty($category))

	            {

	            	message("该用户不存在或者不是机器人用户，返回重试");

	            }

	        } else {

	            $category = array(

	                'status' =>1,

	                'fabu'=>0,

	                'credit'=>0,

	                'rob'=>0,

	            );

	        }



	        if (checksubmit('submit')) {

	            if (empty($_GPC['nickname'])) {

	                message('抱歉，请输入机器人的昵称！');

	            }

	            if (empty($_GPC['headimgurl'])) {

	                message('抱歉，请选择机器人所属平台！');

	            }

	      

	            $data = array(

	                'weid' => $_W['weid'],

	               
	                'nickname' => $_GPC['nickname'],
	                
                    'headimgurl' => $_GPC['headimgurl'],

	                'rob' => $_GPC['rob'],

	                'fabu' => $_GPC['fabu'],

	                'credit' => $_GPC['credit'],

	                'quan_id' => intval($_GPC['quan_id']),

	                'type' => 2,

	                'status' => intval($_GPC['status']),

	            );



	            if (!empty($id)) {

	                pdo_update('cgc_ad_member', $data, array('id' => $id));

	            } else {

	            	$data['createtime']=TIMESTAMP;

	                pdo_insert('cgc_ad_member', $data);

	                $id = pdo_insertid();

	            }

	            message('更新机器人用户数据成功！', $this->createWebUrl('xuni', array('op' => 'display')), 'success');

	        }

	        include $this->template('web/xuni');

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

				if($temp['type']!=2)

				{

					echo 3;

					exit;

				}

				else

				{

					$rob=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND mid=".$id);

					if($rob>0)

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

	    }else if ($operation=='import') {
	  	     
	  	     $file = $_FILES["file"];
	  	      
	  	     if (end(explode('.', $file['name']))!="csv"){
	  	     	message('请导入csv文件！', referer(), 'error');
	  	     }
	  	     
	  	     $file = fopen($file['tmp_name'],'r');
	  	     while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
	  	     	$member_list[] = $data;
	  	     }
	  	     array_splice($member_list,0,1);
	  	     
	  	     if(!empty($_GPC['quan_id'])){
               $condition.=" AND id= ".intval($_GPC['quan_id']);
             }
	  	     
	  	     $quan_list = pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0 $condition");
	  	     $encode = mb_detect_encoding($arr[1], array("ASCII","UTF-8","GB2312","GBK")); 
	  
         
	  	     foreach ($quan_list as $quan){
	  	     	foreach ($member_list as $arr){
	  	     		$openid = TIMESTAMP.rand(0,1000);
	  	     		 if ($encode!= "UTF-8"){ 
                       $arr[0] = iconv("GBK","UTF-8",$arr[0]); 
                    } 
	  	     		$data = array(
	  	     				'weid' => $weid,
	  	     				'nicheng' => $arr[0],
	  	     				'thumb' => $arr[1],
	  	     				'nickname' => trim($arr[0]),
	  	     				'headimgurl' => trim($arr[1]),
	  	     
	  	     				'openid'=>$openid,
	  	     				'rob' => 0,
	  	     				'fabu' => 0,
	  	     				'credit' => 0,
	  	     				'quan_id' => $quan['id'],
	  	     				'type' => 2,
	  	     				'status' => 1,
	  	     				'createtime'=>TIMESTAMP
	  	     		);
	  	     		pdo_insert('cgc_ad_member', $data);
	  	     	}
	  	     }
	  	     
	  	     fclose($file);
	  	     message('导入成功！', referer(), 'success');
		}  elseif ($operation == 'delete_all') {
			if(!empty($_GPC['quan_id']))

	        {

	        	$condition.=" AND id= ".intval($_GPC['quan_id']);

	        }
			$temp=pdo_query("delete FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid."  $condition and type=2 ");
	        message('删除成功！', referer(), 'success'); 
	    }