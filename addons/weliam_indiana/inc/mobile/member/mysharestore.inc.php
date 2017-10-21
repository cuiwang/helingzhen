<?php
	global $_W,$_GPC;
	load()->classs('weixin.account');
	load()->func('file');
	$access_token = WeAccount::token();
	$openid = m('user') -> getOpenid();
	$period_number = $_GPC['period_number'];
	
	if(empty($openid)){
		
		message('请从微信登陆', '', 'error');
		
	}
	
	//下载图片到服务器开始
			$mediaid1 = $_GPC['mediaid1'];
			$mediaid2 = $_GPC['mediaid2'];
			$mediaid3 = $_GPC['mediaid3'];
			$filename1 = '/attachment/weliam_indiana/storeimage/'.$_W['uniacid'].'/'.$openid."".time()."1.jpg";
			$filename2 = '/attachment/weliam_indiana/storeimage/'.$_W['uniacid'].'/'.$openid."".time()."2.jpg";
			$filename3 = '/attachment/weliam_indiana/storeimage/'.$_W['uniacid'].'/'.$openid."".time()."3.jpg";
			$length = $_GPC['length'];
			
			if($length == 1){
				
				$url1 = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid1";
				$saveinfo1 = $this->downloadWeiXinFile($url1);
				mkdirs(IA_ROOT.'/attachment/weliam_indiana/storeimage/'.$_W['uniacid']);
				$result1 = $this->saveWeiXinFile(IA_ROOT.$filename1,$saveinfo1);
				$thumb = array($filename1);
								
			}else if($length == 2){
				
				$url1 = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid1";
				$saveinfo1 = $this->downloadWeiXinFile($url1);
				mkdirs(IA_ROOT.'/attachment/weliam_indiana/storeimage/'.$_W['uniacid']);
				$result1 = $this->saveWeiXinFile(IA_ROOT.$filename1,$saveinfo1);
					$url2 = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid2";
					$saveinfo2 = $this->downloadWeiXinFile($url2);
					$result2 = $this->saveWeiXinFile(IA_ROOT.$filename2,$saveinfo2);
						$thumb = array( $filename1,$filename2);
				
			}else if($length == 3){
				
				$url1 = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid1";
				$saveinfo1 = $this->downloadWeiXinFile($url1);
				mkdirs(IA_ROOT.'/attachment/weliam_indiana/storeimage/'.$_W['uniacid']);
				$result1 = $this->saveWeiXinFile(IA_ROOT.$filename1,$saveinfo1);
					$url2 = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid2";
					$saveinfo2 = $this->downloadWeiXinFile($url2);
					$result2 = $this->saveWeiXinFile(IA_ROOT.$filename2,$saveinfo2);
						$url3 = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid3";
						$saveinfo3 = $this->downloadWeiXinFile($url3);
						$result3 = $this->saveWeiXinFile(IA_ROOT.$filename3,$saveinfo3);
							$thumb = array($filename1,$filename2,$filename3);
			}
			
			//数组序列化
			$thumbs = serialize($thumb);
			
	//下载图片到服务器结束
	
	
	$data = array(
		'goodsid' => $_GPC['goodsid'],
		'uniacid' => $_W['uniacid'],
		'openid' => $openid,
		'title' => $_GPC['title'],
		'detail' => $_GPC['detail'],
		'period_number' => $_GPC['period_number'],
		'createtime' => time(),
		'status' => '1',
		'goodstitle' => $_GPC['goodstitle'],
		'thumbs' => $thumbs
	);
	
	$result = pdo_insert("weliam_indiana_showprize",$data);
	
	pdo_update('weliam_indiana_period',array('status' => 7),array('period_number' => $period_number,'uniacid' => $_W['uniacid']));
	
	if(!empty($result)){
		echo 'true';
	}
	
	?>