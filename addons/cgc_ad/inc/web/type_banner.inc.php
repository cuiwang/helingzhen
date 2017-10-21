<?php

		global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		
		if(empty($_GPC['type_id'])){
			message('抱歉，类别数据出错！');
		}
		
		$type_id = $_GPC['type_id'];
		$cgc_ad_info_type = new cgc_ad_info_type();
		$info_type = $cgc_ad_info_type->getOne($type_id); 

	    if ($operation == 'display') {

	        if (!empty($_GPC['displayorder'])) {
	            foreach ($_GPC['displayorder'] as $id => $displayorder) {
	                pdo_update('cgc_ad_type_banner', array('displayorder' => $displayorder), array('id' => $id));
	            }
	            message('Banner更新成功！', $this->createWebUrl('type_banner', array('op' => 'display','type_id' => $_GPC['type_id'],'quan_id'=> $_GPC['quan_id'])), 'success');
	            
	        }

	        if(!empty($_GPC['quan_id']))
	        {
	        	$condition.=" AND a.quan_id= ".intval($_GPC['quan_id']);
	        }

	        if(!empty($_GPC['keyword']))
	        {
	        	$condition.=" AND a.title LIKE '%{$_GPC['keyword']}%' ";
	        }
	        
	        $condition.=" and a.type_id=$type_id ";

	        $quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

	        $category = pdo_fetchall("SELECT a.*,b.aname FROM " . tablename('cgc_ad_type_banner') . " as a left join ".tablename('cgc_ad_quan')." as b on a.quan_id=b.id WHERE a.weid = '{$_W['weid']}' ".$condition." ORDER BY a.displayorder DESC,a.id ASC");

	        include $this->template('web/type_banner');

	    } elseif ($operation == 'post') {

	    	load()->func('tpl');

	    	$quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

	        $id = intval($_GPC['id']);

	        if (!empty($id)) {

	            $category = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_type_banner') . " WHERE id = '$id'");

	        } else {

	            $category = array(

	                'displayorder' => 0,

	                'enabled' =>0,

	                'status' =>1,

	            );

	        }



	        if (checksubmit('submit')) {

	            if (empty($_GPC['catename'])) {

	                message('抱歉，请输入Banner名称！');

	            }

	            $link=$_GPC['url'];

				if(!empty($link)){

					if (!preg_match("/^(http|ftp):/", $link)){

					   $link='http://'.$link;

					}

				}

				if($this->text_len($link)>500){

					message("链接内容超长啦！", $this->createWebUrl('type_banner', array('op' => 'display','type_id' => $_GPC['type_id'],'quan_id'=> $_GPC['quan_id'])), 'success');

				}

	            $data = array(

	                'weid' => $_W['weid'],

	                'title' => $_GPC['catename'],

	                'thumb' => $_GPC['thumb'],
	                
	                'type_id' => $type_id,

	                'quan_id' => intval($_GPC['quan_id']),

	                'content' => $_GPC['content'],

	                'enabled' => $_GPC['enabled'],

	                'url' => $link,

	                'displayorder' => intval($_GPC['displayorder']),

	                'status' => intval($_GPC['status']),

	            );



	            if (!empty($id)) {

	                pdo_update('cgc_ad_type_banner', $data, array('id' => $id));

	            } else {

	            	$data['createtime']=TIMESTAMP;

	                pdo_insert('cgc_ad_type_banner', $data);

	                $id = pdo_insertid();

	            }

	            message('更新Banner成功！', $this->createWebUrl('type_banner', array('op' => 'display','type_id' => $_GPC['type_id'],'quan_id'=> $_GPC['quan_id'])), 'success');

	        }

	        include $this->template('web/type_banner');

	    } elseif ($operation == 'delete') {

	        $id = intval($_GPC['id']);

	        $category = pdo_fetch("SELECT id FROM " . tablename('cgc_ad_type_banner') . " WHERE id = '$id'");

	        if (empty($category)) {

	            message('抱歉，Banner不存在或是已经被删除！', $this->createWebUrl('type_banner', array('op' => 'display','type_id' => $_GPC['type_id'],'quan_id'=> $_GPC['quan_id'])), 'error');

	        }

	        pdo_delete('cgc_ad_type_banner', array('id' => $id));

	        message('Banner删除成功！', $this->createWebUrl('type_banner', array('op' => 'display','type_id' => $_GPC['type_id'],'quan_id'=> $_GPC['quan_id'])), 'success');

	    }