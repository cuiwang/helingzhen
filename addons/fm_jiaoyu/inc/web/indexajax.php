<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','checkpass','guanli','getquyulist','getbjlist') ) ? $_GPC ['op'] : 'default';

    if ($operation == 'default') {
	           die ( json_encode ( array (
			         'result' => false,
			         'msg' => '你是傻逼吗'
	                ) ) );
              }			
	if ($operation == 'checkpass') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schooid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$tid = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where :id = id And :weid = weid And :password = password", array(
		         ':id' => $_GPC ['schooid'],
				 ':weid' => $_GPC ['weid'],
				 ':password'=>$_GPC ['password']
				  ), 'id');
				  
		if(empty($tid['id'])){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '密码输入错误！' 
		          ) ) );
		}else{  			
			$data ['result'] = true;
			
			$data ['url'] = $_W['siteroot'] .'web/'.$this->createWebUrl('assess', array('id' => $_GPC ['schooid'], 'schoolid' =>  $_GPC ['schooid']));
			
			$data ['msg'] = '密码正确！';

		 die ( json_encode ( $data ) );
		}
    }
	if ($operation == 'guanli') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schooid1'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$tid = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where :id = id And :weid = weid And :password = password", array(
		         ':id' => $_GPC ['schooid1'],
				 ':weid' => $_GPC ['weid'],
				 ':password'=>$_GPC ['password1']
				  ), 'id');
				  
		if(empty($tid['id'])){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '密码输入错误！' 
		          ) ) );
		}else{  			
			$data ['result'] = true;
			
			$data ['url'] = $_W['siteroot'] .'web/'.$this->createWebUrl('school', array('id' => $_GPC ['schooid1'], 'schoolid' =>  $_GPC ['schooid1'], 'op' => 'post'));
			
			$data ['msg'] = '密码正确！';

		 die ( json_encode ( $data ) );
		}
    }
	if ($operation == 'getquyulist')  {
		if (! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$data = array();
			$bjlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = '{$_GPC['weid']}' And parentid = '{$_GPC['gradeId']}' And type = '' ORDER BY ssort DESC");
   			$data ['bjlist'] = $bjlist;
			$data ['result'] = true;
			$data ['msg'] = '成功获取！';
			
          die ( json_encode ( $data ) );
		  
		}
    }
	if ($operation == 'getbjlist')  {
		if (! $_GPC ['schoolid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$data = array();
			$bjlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where schoolid = '{$_GPC['schoolid']}' And parentid = '{$_GPC['gradeId']}' And type = 'theclass' ORDER BY ssort DESC");
   			$data ['bjlist'] = $bjlist;
			$data ['result'] = true;
			$data ['msg'] = '成功获取！';
			
          die ( json_encode ( $data ) );
		  
		}
    }
		
?>