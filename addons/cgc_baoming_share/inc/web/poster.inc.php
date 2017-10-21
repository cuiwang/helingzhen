<?php
		global $_W,$_GPC;

		$uniacid=$_W['uniacid'];

		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

	    if ($op == 'display') {

	        if(!empty($_GPC['activity_id']))
	        {
	        	$condition.=" AND a.activity_id= ".intval($_GPC['activity_id']);
	        }

	        if(!empty($_GPC['keyword']))
	        {
	        	$condition.=" AND a.keyword LIKE '%{$_GPC['keyword']}%' ";
	        }

	        $pindex = max(1, intval($_GPC['page']));

			$psize = 20;

	        $activity=pdo_fetchall("SELECT id,title FROM ".tablename('cgc_baoming_activity')." WHERE uniacid=".$uniacid." ");

	        $list=pdo_fetchall("SELECT a.* FROM ".tablename('cgc_baoming_poster')." a WHERE a.uniacid=$uniacid $condition LIMIT ".($pindex - 1) * $psize.",{$psize}");
			
			$total=pdo_fetchcolumn("SELECT count(a.id) FROM ".tablename('cgc_baoming_poster')." as a WHERE a.uniacid=$uniacid $condition ");

			$pager = pagination($total, $pindex, $psize);

	        include $this->template('poster');

	    } elseif ($op == 'post') {

	    	load()->func('tpl');
			
	    	$activity=pdo_fetchall("SELECT id,title FROM ".tablename('cgc_baoming_activity')." WHERE uniacid=".$uniacid." ");

	        $posterId = intval($_GPC['id']);

	        if (!empty($posterId)) {
	        	
	            $poster = pdo_fetch("SELECT * FROM " . tablename('cgc_baoming_poster') . " WHERE id = '$posterId' AND uniacid=".$uniacid." ");
	            
	            if(empty($poster))
	            {
	            	message("该海报不存在，返回重试");
	            }
	            else{
	            	$data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
	            }
	            
	        } 

	        if (checksubmit('submit')) {

	            if (empty($_GPC['activity_id'])) {
	                message('抱歉，请选择所属活动！');
	            }

				//判断该平台是否已经有海报
				$condition;
				if (!empty($posterId)) {
					$condition = " AND id <> ".$posterId;
				}
				
				$the = pdo_fetch("SELECT * FROM " . tablename('cgc_baoming_poster') . " WHERE  activity_id=".$_GPC['activity_id']." AND uniacid=$uniacid $condition");
				
				if(!empty($the)){
					 message('抱歉，'.$_GPC['activity_name'].'已经有一份海报！');
				}
				//判断该平台是否已经有海报
				
				$data2 = array(
		            'uniacid' => $_W['uniacid'],
		            'activity_id'=>intval($_GPC['activity_id']),
		            'keyword' => trim($_GPC['activity_name']),
		            'bg' => trim($_GPC['bg']), 
		            'data' => htmlspecialchars_decode($_GPC['data']),
		            'createtime' => time(),
		            'oktext' => trim($_GPC['oktext']),
		            'waittext' => trim($_GPC['waittext']),
		            'entrytext' => trim($_GPC['entrytext'])
		        );
		        
		        if (!empty($posterId)) {
		            pdo_update('cgc_baoming_poster', $data2, array('id' => $posterId, 'uniacid' => $_W['uniacid']));
		        } else {
		            pdo_insert('cgc_baoming_poster', $data2);
		            $id = pdo_insertid();
		        }
				
	            message('更新海报数据成功！', $this->createWebUrl('poster', array('op' => 'display')), 'success');

	        }

	        include $this->template('poster');

	    } elseif ($op == 'del') {

	        $id = intval($_GPC['id']);

			$temp=pdo_fetch("SELECT * FROM ".tablename('cgc_baoming_poster')." WHERE uniacid=".$uniacid." AND id=".$id);

			if(empty($temp))
			{

				echo 2;
				exit;
			}
			else
			{
				pdo_delete('cgc_baoming_poster', array('id' => $id));
				echo 1;
				exit;
			}

	    }