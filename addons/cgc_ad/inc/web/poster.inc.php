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

	        	$condition.=" AND a.keyword LIKE '%{$_GPC['keyword']}%' ";

	        }

	        $pindex = max(1, intval($_GPC['page']));

			$psize = 20;

	        $quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

	        $list=pdo_fetchall("SELECT a.* FROM ".tablename('cgc_ad_poster')." a WHERE a.weid=$weid $condition LIMIT ".($pindex - 1) * $psize.",{$psize}");
			
			$total=pdo_fetchcolumn("SELECT count(a.id) FROM ".tablename('cgc_ad_poster')." as a WHERE a.weid=$weid $condition ");

			$pager = pagination($total, $pindex, $psize);

	        include $this->template('web/poster');

	    } elseif ($operation == 'post') {

	    	load()->func('tpl');
			
	    	$quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

	        $posterId = intval($_GPC['id']);

	        if (!empty($posterId)) {
	        	
	            $poster = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_poster') . " WHERE id = '$posterId' AND weid=".$weid." ");
	            
	            if(empty($poster))
	            {
	            	message("该海报不存在，返回重试");
	            }
	            else{
	            	$data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
	            }
	            
	        } 

	        if (checksubmit('submit')) {

	            if (empty($_GPC['quan_id'])) {
	                message('抱歉，请选择商家所属平台！');
	            }

	          /*  if (empty($_GPC['keyword'])) {
	                message('抱歉，请输入二维码关键词！');
	            }*/
				//echo tomedia($_GPC['bg']);
				
				//判断该平台是否已经有海报
				$condition;
				if (!empty($posterId)) {
					$condition = " AND id <> ".$posterId;
				}
				
				$the = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_poster') . " WHERE  quan_id=".$_GPC['quan_id']." AND weid=$weid $condition");
				
				if(!empty($the)){
					 message('抱歉，'.$_GPC['quan_name'].'已经有一份海报！');
				}
				//判断该平台是否已经有海报
				
				$data2 = array(
		            'weid' => $_W['weid'],
		            'quan_id'=>intval($_GPC['quan_id']),
		            'quan_name'=>trim($_GPC['quan_name']),
		            'keyword' => trim($_GPC['keyword']),
		            'bg' => trim($_GPC['bg']), 
		            'data' => htmlspecialchars_decode($_GPC['data']),
		            'createtime' => time(),
		            'oktext' => trim($_GPC['oktext']),
		            'waittext' => trim($_GPC['waittext']),
		            'entrytext' => trim($_GPC['entrytext'])
		        );
		        
		        if (!empty($posterId)) {
		            pdo_update('cgc_ad_poster', $data2, array('id' => $posterId, 'weid' => $_W['weid']));
		        } else {
		            pdo_insert('cgc_ad_poster', $data2);
		            $id = pdo_insertid();
		        }
				
	            message('更新海报数据成功！', $this->createWebUrl('poster', array('op' => 'display')), 'success');

	        }

	        include $this->template('web/poster');

	    } elseif ($operation == 'del') {

	        $id = intval($_GPC['id']);

			$temp=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_poster')." WHERE weid=".$weid." AND id=".$id);

			if(empty($temp))
			{

				echo 2;
				exit;
			}
			else
			{
				pdo_delete('cgc_ad_poster', array('id' => $id));
				echo 1;
				exit;
			}

	    }