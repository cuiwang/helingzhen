<?php
/**
 * 我要上推荐模块微站定义
 *
 * @author 洛杉矶豪哥
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hao_recommendModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		$uniacid = $_W['uniacid'];
        $type = $_GPC['type'];
        //轮播
        $banners = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_banner')." WHERE uniacid = '{$uniacid}' AND display = '0' ");
        //公众号
		if($_GPC['type'] == 0){
           $lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and category = '0' and status = '1' ORDER BY sort");
		}
		//个人微信号
		else if($_GPC['type'] == 1){
		   $lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and category = '1' and status = '1' ORDER BY sort");
		}
		//微信群
		else if($_GPC['type'] == 2){
		   $lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and category = '2' and status = '1' ORDER BY sort");
		}
        else{
		   $lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and category = '0' and status = '1' ORDER BY sort");
	    }
		include $this->template('index');
	}

	public function doMobileForm(){
		global $_GPC, $_W;	
		load()->func('tpl');	
		//如果是认证服务号
		if($_W['account']['level'] == '4'){
			if (empty($_W['fans']['nickname'])) {
		        //mc_oauth_userinfo();
		        load()->model('mc');
                $fans = mc_oauth_userinfo();
	        }
	        $openid = $_W['fans']['openid'];
        }
		include $this->template('form');
	 }

	public function doMobileajaxform(){
	 	//global $_GPC, $_W;	
	 	$data['category'] = $_GET['category'];
		$data['username'] = $_GET['username'];
		$data['phone'] = $_GET['phone'];
		$data['publicname'] = $_GET['publicname'];
		$data['publicdescription'] = $_GET['publicdescription'];
		$data['publicimage'] = $_GET['publicimage'];
		$data['icon'] = $_GET['icon'];
		$data['status'] = '';
		$data['time'] = time();
		$data['hit'] = 0;
		if($_GPC['openid'] != '' || $_GET['openid'] != null ){
		   	  $data['openid'] = $_GET['openid'];
		}else{
		      $data['openid'] = $_W['openid'] ? $_W['openid'] : '';
		}
	    $data['uniacid'] = $_GET['uniacid'];
	    $data['sort'] = 0;
	    $res  = pdo_insert('hao_recommend_list',$data);
        echo json_encode($data);
	}

	public function doMobileUpload(){
		global $_W,$_GPC;
		load()->classs('weixin.account');
        $accObj= WeixinAccount::create($_GET['uniacid']);
        $access_token = $accObj->fetch_token();

		$media_id = $_GET['media_id'];

		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;

        $newfolder= ATTACHMENT_ROOT . 'images' . '/hao_recommend_photos'."/";//文件夹名称
        if (!is_dir($newfolder)) {
		 	mkdir($newfolder, 0777);
		} 
		$picurl = 'images'.'/hao_recommend_photos'."/".date('YmdHis').rand(1000,9999).'.jpg';
        $targetName = ATTACHMENT_ROOT.$picurl;
        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $data['url'] = $picurl;
        echo json_encode($data);
        //echo $picurl;
	} 

	public function doMobileDetail(){
		global $_W,$_GPC;
		$sql = "select * from ".tablename('hao_recommend_list')." where id=".$_GPC['id']." "; 
        $info = pdo_fetch($sql); 
        $hit = intval($info['hit']) + 1;
        $user_data = array(
			'hit' => $hit,
		);
		$result = pdo_update('hao_recommend_list', $user_data, array('id' => $_GPC['id']));

		include $this->template('detail');
	}

	public function doMobileTip(){
		global $_W,$_GPC;
        $sql = "select * from ".tablename('hao_recommend_setting')." where uniacid=".$_W['uniacid']." "; 
        $setting = pdo_fetch($sql); 

		include $this->template('tip');
	}

	public function doMobileSearch(){
        global $_W,$_GPC;
        $uniacid = $_W['uniacid'];
         
        if($_GPC['search']){
           $lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and publicname like '%".$_GPC['search']."%' and status = '1' ORDER BY hit DESC");
           $type = "3";
        }else{
           $lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and category = '0' and status = '1' ORDER BY hit DESC");
           $type = "0";
        } 
        include $this->template('index');
	}

	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$ops = array('all','pass','nopass','novaild');
	    $op = in_array($_GPC['op'],$ops)? $_GPC['op'] : 'all';

	    if($op == 'all'){
	    	$uniacid = $_W['uniacid'];
	    	$pindex = max(1,intval($_GPC['page']));
	    	$psize = 10;
	    	$condition = '';
	    	$lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' ORDER BY hit DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	    	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}'");
	    	$pager = pagination($total,$pindex,$psize);
        }

        if($op == 'pass'){
        	$uniacid = $_W['uniacid'];
	    	$pindex = max(1,intval($_GPC['page']));
	    	$psize = 10;
	    	$condition = '';
	    	$lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and status = '1' ORDER BY hit LIMIT ".($pindex - 1) * $psize.','.$psize);
	    	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}'");
	    	$pager = pagination($total,$pindex,$psize);
        }

        if($op == 'novaild'){
        	$uniacid = $_W['uniacid'];
	    	$pindex = max(1,intval($_GPC['page']));
	    	$psize = 10;
	    	$condition = '';
	    	$lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and status = '0' ORDER BY hit LIMIT ".($pindex - 1) * $psize.','.$psize);
	    	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}'");
	    	$pager = pagination($total,$pindex,$psize);
        }

        if($op == 'nopass'){
            $uniacid = $_W['uniacid'];
	    	$pindex = max(1,intval($_GPC['page']));
	    	$psize = 10;
	    	$condition = '';
	    	$lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}' and status = '2' ORDER BY hit LIMIT ".($pindex - 1) * $psize.','.$psize);
	    	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename('hao_recommend_list')." WHERE uniacid = '{$uniacid}'");
	    	$pager = pagination($total,$pindex,$psize);
        }

		include $this->template('list');
	}
    
    public function doWebEdit(){
    	global $_W,$_GPC;
    	load()->func('tpl');
    	if($_GPC['type'] == '1'){
	    	$custom = pdo_fetch("SELECT * FROM ".tablename('hao_recommend_list')." where uniacid=".$_W['uniacid']." and id = ".$_GPC['id']." ");
	    	if($_W['ispost']){
	    		$data['category'] = $_GPC['category'];
				$data['username'] = $_GPC['username'];
				$data['phone'] = $_GPC['phone'];
				$data['publicname'] = $_GPC['publicname'];
				$data['publicdescription'] = $_GPC['publicdescription'];
				$data['publicimage'] = $_GPC['publicimage'];
				$data['icon'] = $_GPC['icon'];
				$data['sort'] = $_GPC['sort'];
				$result = pdo_update('hao_recommend_list', $data, array('id' => $_GPC['id']));
				if (!empty($result)) {
					message('更新成功',$this->createWebUrl('list'));
				}
	    	}
	    	//var_dump($custom);die;
		    $type = '1';
	    }else{
	    	if($_W['ispost']){
		    	$data['category'] = $_GPC['category'];
				$data['username'] = $_GPC['username'];
				$data['phone'] = $_GPC['phone'];
				$data['publicname'] = $_GPC['publicname'];
				$data['publicdescription'] = $_GPC['publicdescription'];
				$data['publicimage'] = $_GPC['publicimage'];
				$data['icon'] = $_GPC['icon'];
				$data['status'] = '1';
				$data['time'] = time();
				$data['hit'] = 0;
				$data['openid'] ='';
				$data['sort'] = $_GPC['sort'];
			    $data['uniacid'] = $_W['uniacid'];
		    	$type = '0';
		    	$res  = pdo_insert('hao_recommend_list',$data);
		    	if($res){
		    		message('success',$this->createWebUrl('list'));
		    	}
	        }
	    }

    	include $this->template('edit');
    }

	public function doWebvaild(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$status = $_GPC['status'];
		$sql = "select * from ".tablename('hao_recommend_setting')." where uniacid=".$_W['uniacid']." "; 
        $setting = pdo_fetch($sql); 
        //var_dump($setting['tempID']);die;

		if($status == '1'){
			$user_data = array(
				'status' => '1',
			);
			$result = pdo_update('hao_recommend_list', $user_data, array('id' => $id));
			if (!empty($result)) {
				//如果是认证服务号
				if($_W['account']['level'] == '4'){
					$ref = pdo_fetch("SELECT * FROM ".tablename('hao_recommend_list')." WHERE id = :id", array(':id' => $id));
					$openid = $ref['openid'];
					$result = '恭喜您，您的“我要上推荐”审核通了，去看看吧！';
					$color = '#228B22';
					$url = $this->createMobileUrl('index');
					$tempID = $setting['tempID'];
					$this->templetemsg($openid,$result,$color,$url,$tempID);
			    }
				message('操作成功',$this->createWebUrl('list'));
			}else{
				message('操作成功',$this->createWebUrl('list'));
			}
		}
		if($status == '2'){
			$user_data = array(
				'status' => '2',
			);
			$result = pdo_update('hao_recommend_list', $user_data, array('id' => $id));
			if (!empty($result)) {
				//如果是认证服务号
				if($_W['account']['level'] == '4'){
	                $ref = pdo_fetch("SELECT * FROM ".tablename('hao_recommend_list')." WHERE id = :id", array(':id' => $id));
					$openid = $ref['openid'];
					$result = '对不起，您的“我要上推荐”审核失败,请重新提交';
					$color = '#E3170D';
					$url = $this->createMobileUrl('form');
					$tempID = $setting['tempID'];
					$this->templetemsg($openid,$result,$color,$url,$tempID);
				}
				message('操作成功',$this->createWebUrl('list'));
			}else{
				message('操作成功',$this->createWebUrl('list'));
			}
		}
		if($status == '0'){
			$result = pdo_delete('hao_recommend_list', array('id' => $id));
			if (!empty($result)) {
				message('删除成功',$this->createWebUrl('list'));
			}
		}
	}

	public function doWebQrcode(){
		global $_W,$_GPC;
    	load()->func('tpl');

    	if($_W['ispost']){
    		$data['uniacid'] = $_W['uniacid'];
    		$data['image'] = $_GPC['image'];
    		$data['tempID'] = $_GPC['tempID'];
    		$list = pdo_fetch("SELECT * FROM ".tablename('hao_recommend_setting')." WHERE uniacid = ".$_W['uniacid']." ");
    		if(empty($list['uniacid'])){
    			$res = pdo_insert('hao_recommend_setting',$data);
    		}else{
    			$res = pdo_update('hao_recommend_setting',$data,array('uniacid'=>$_W['uniacid']));
    		}
    		message('保存信息成功','','success');     
    	}else{
           $sql = "select * from ".tablename('hao_recommend_setting')." where uniacid=".$_W['uniacid']." "; 
           $setting = pdo_fetch($sql);
        }

    	include $this->template('qrcode');
	}	

    //Curl 请求
	public function wtw_request($url,$data=null){
		 $curl = curl_init(); // 启动一个CURL会话
		 curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
		 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
		 curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		 if($data != null){
		    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
		 }
		 curl_setopt($curl, CURLOPT_TIMEOUT, 300); // 设置超时限制防止死循环
		 curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		 $info = curl_exec($curl); // 执行操作
		 if (curl_errno($curl)) {
		    //echo 'Errno:'.curl_getinfo($curl);//捕抓异常
		    //dump(curl_getinfo($curl));
		}
	    return $info;
	}

	// 模板消息
	public function templetemsg($openid,$result,$color,$url,$tempID){
		//获取ACCESS_TOKEN
		load()->classs('weixin.account');
		$accObj= new WeixinAccount();
		$ACCESS_TOKEN = $accObj->fetch_available_token();
		$msg_url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$ACCESS_TOKEN."";
		$time = date("Y-m-d H:i:s",time());
		$url = $url; //这个链接是点击图文 跳转的链接,换行只能用n 不能用<Br/>
		//请求包为一个json：
		$msg_json= '{
			"touser":"'.$openid.'",
			"template_id":"'.$tempID.'",
			"url":"'.$url.'",
			"topcolor":"#FF0000",
			"data":{
				"first":{
				"value":"你好：“我要上推荐”审核结果来啦！",
				"color":"#FF0000"
				},
				"FBForm":{
				"value":"审核结果通知",
				"color":"#000000"
				},
				"FBNote":{
				"value":"'.$result.'",
				"color":"'.$color.'"
				},
				"FBTime":{
				"value":"'.$time.'",
				"color":"#000000"
				},
				"FBArea":{
				"value":"",
				"color":""
				}
			}
		}' ;
		$result = $this->wtw_request($msg_url,$msg_json);
	}

	public function doWebBanner(){
        global $_GPC,$_W;
        $uniacid = $_W['uniacid'];
        $lists = pdo_fetchall("SELECT * FROM ".tablename('hao_recommend_banner')." WHERE uniacid = '{$uniacid}' ");

        include $this->template('banner');
    }

    public function doWebAddBanner(){
        global $_GPC,$_W;
        $type = $_GPC['type'];
        
        if($_W['ispost']){
            if($type == '0'){
                $data['image'] = $_GPC['image'];
                $data['link'] = $_GPC['link'];
                $data['sort'] = $_GPC['sort'];
                $data['display'] = $_GPC['display'];
                $data['uniacid'] = $_W['uniacid'];

                $result = pdo_insert('hao_recommend_banner',$data);
                if($result){
                    message('操作成功',$this->createWebUrl('banner'),'success');
                }
            }else{
                $id = $_GPC['id'];
                $data['image'] = $_GPC['image'];
                $data['link'] = $_GPC['link'];
                $data['sort'] = $_GPC['sort'];
                $data['display'] = $_GPC['display'];
                $data['uniacid'] = $_W['uniacid'];

                $result = pdo_update('kobe_vote_banner',$data,array('id'=>$id));
                if($result){
                    message('更新成功',$this->createWebUrl('banner'),'success');
                }
            }
        }
        if($_GPC['type'] == '1'){
            $id = $_GPC['id'];
            $banner = pdo_fetch("SELECT * FROM ".tablename('kobe_vote_banner')." WHERE id = '{$id}' ");
        }
        include $this->template('addBanner');
    }

    public function doWebDelBanner(){
        global $_GPC,$_W;

        $id = $_GPC['id'];
        $result = pdo_delete('hao_recommend_banner',array('id'=>$id));
        if($result){
            message('删除成功',$this->createWebUrl('banner'),'success');
        }
    }

}