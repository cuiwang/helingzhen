<?php

defined('IN_IA') or exit('Access Denied');

class Tim_cowModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$users = pdo_fetchall("SELECT * FROM ".tablename('tim_cowuser'). " WHERE uniacid = :uniacid"." ORDER BY user_score DESC LIMIT 0,10", array(':uniacid' => $uniacid));
		$info = pdo_fetch("SELECT * FROM ".tablename('tim_cowsetting'). " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		include $this->template('index');
	}
	public function doMobileCover1() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$users = pdo_fetchall("SELECT * FROM ".tablename('tim_cowuser'). " WHERE uniacid = :uniacid"." ORDER BY user_score DESC LIMIT 0,10", array(':uniacid' => $uniacid));
		$info = pdo_fetch("SELECT * FROM ".tablename('tim_cowsetting'). " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		include $this->template('index');
	}
	public function doWebUser() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		load()->func('tpl');
		if('post' == $op){//添加或修改
			$user_id = intval($_GPC['user_id']);
			if(!empty($user_id)){
			$item = pdo_fetch("SELECT * FROM ".tablename('tim_cowuser')." where user_id=$user_id and uniacid=$uniacid");
			empty($item)?message('亲,数据不存在！', '', 'error'):"";	
			}
			if(checksubmit('submit')){
				empty ($_GPC['user_name'])?message('亲,标题不能为空'):$user_name=$_GPC['user_name'];
			$user_photo =$_GPC['user_photo'];
			$user_score =$_GPC['user_score'];
			$user_phone =$_GPC['user_phone'];
				$data = array(
					'user_id'=>$user_id,
					'uniacid'=>$uniacid,
					'user_name' =>$user_name,
					'user_photo'=>$user_photo,
					'user_score'=>$user_score,
					'user_phone' =>$user_phone,
					
				);
				
				if(empty($user_id)){
						pdo_insert('tim_cowuser', $data);//添加数据
						message('数据添加成功！', $this->createWebUrl('user', array('op' => 'display')), 'success');
				}else{
						pdo_update('tim_cowuser', $data, array('user_id' => $user_id));
						message('数据更新成功！', $this->createWebUrl('user', array('op' => 'display')), 'success');
				}
				
			}else{
				include $this->template('user_data');
			}
			
		}else if('del' == $op){//删除
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				$sqls = "delete from  ".tablename('tim_cowuser')."  where user_id in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}
			$user_id = intval($_GPC['user_id']);
			$row = pdo_fetch("SELECT user_id FROM ".tablename('tim_cowuser')." WHERE user_id = :user_id", array(':user_id' => $user_id));
			if (empty($row)) {
				//dump($_GPC);
				message('抱歉，数据不存在或是已经被删除！', $this->createWebUrl('user', array('op' => 'display')), 'error');
			}
			pdo_delete('tim_cowuser', array('user_id' => $user_id));
			message('删除成功！', referer(), 'success');
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			
				$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " WHERE uniacid=$uniacidand  AND user_name LIKE '%".$_GPC['keyword']."%'  ";
			}
			
			$list = pdo_fetchall("SELECT *  FROM ".tablename('tim_cowuser') ." $condition  ORDER BY user_score DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tim_cowuser')." $condition" );
			$pager = pagination($total, $pindex, $psize);
			include $this->template('user_data');
		}
		
	}

	public function doWebSet() {
		global $_W,$_GPC;
		$uniacid = $_W['uniacid'];
		load()->func('tpl');
		$info = pdo_fetch("SELECT * FROM ".tablename('tim_cowsetting'). " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		if(checksubmit('submit')){ 
				$title = $_GPC['title'];
				$logo = $_GPC['logo'];
				$index_intro = $_GPC['index_intro'];
				$counttime = $_GPC['counttime'];
				$goods = $_GPC['goods'];
				$tips = $_GPC['tips'];
				$rules = $_GPC['rules'];
				$share_content = $_GPC['share_content'];
				$share_title = $_GPC['share_title'];
				$share_icon = $_GPC['share_icon'];
				$infos = array(
					'uniacid' => $uniacid,
					'title' => $title,
					'logo' => $logo,
					'index_intro' => $index_intro,
					'counttime' => $counttime,
					'goods' => $goods,
					'tips' => $tips, 
					'rules' => $rules,  
					'share_content' => $share_content, 
					'share_title' => $share_title, 
					'share_icon' => $share_icon
				);
				if(empty($info)){
						pdo_insert('tim_cowsetting', $infos);//添加数据
						message('数据添加成功！', $this->createWebUrl('set'), 'success');
				}else{
						$id = $info['id'];
						pdo_update('tim_cowsetting', $infos, array('id' => $id));
						message('数据更新成功！', $this->createWebUrl('set'), 'success');
				}	
		}
		include $this->template('param_set');
	}

	public function doMobileSave_user() { 
		global $_W,$_GPC;
		$openid = $_W['openid'];
		$acid = $_W['acid'];
		load()->classs('weixin.account');
		$accObj= WeixinAccount::create($acid);
		$access_token = $accObj->fetch_token();
		$userInfo = $this->getUserInfo ($access_token, $openid);

		$uniacid    = $_W['uniacid'];
		$user_name  = $userInfo['nickname'];
		$user_photo = $userInfo['headimgurl'];
		$user_score = $_GPC['user_score'];
		$user_phone = $_GPC['user_phone'];
		$info = pdo_fetch("SELECT * FROM ".tablename('tim_cowuser'). " WHERE user_name = :user_name", array(':user_name' => $user_name));
		if(empty($info)){
			$data = array(
				'uniacid'=>$uniacid,
				'user_name' =>$user_name,
				'user_photo'=>$user_photo,
				'user_score'=>$user_score,
				'user_phone' =>$user_phone
			);
			pdo_insert('tim_cowuser', $data);
			echo '{"msg" : "success"}';
		} elseif(intval($user_score) > intval($info['user_score'])) { 
			$user_id = $info['user_id'];
			$data = array(
				'user_id' =>$user_id,
				'uniacid'=>$uniacid,
				'user_name' =>$user_name,
				'user_photo'=>$user_photo,
				'user_score'=>$user_score,
				'user_phone' =>$user_phone
			);
			pdo_update('tim_cowuser', $data, array('user_id' => $user_id));
			echo '{"msg" : "You have played"}';
		}

	}
	private function  getUserInfo ($access_token, $openid) 
	{
		load()->func('communication');

        $api_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";

        $content = ihttp_get($api_url);

        $userInfo = @json_decode($content['content'], true);

        return $userInfo;

	}

	public function doMobileGet_top() { 
		$users = pdo_fetchall("SELECT * FROM ".tablename('tim_cowuser')." ORDER BY user_score DESC LIMIT 0,5");
		
		if(!empty($users)) {
			return json_encode($users);
		}
	}
	

}
