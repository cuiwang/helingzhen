<?php
global $_W,$_GPC;
if ($_W['ispost']){
	$op = $_GPC['op'];
	$id = $_GPC['wid'];
	if ($op == 'done'){
		$data['type'] = $_GPC['secret'];
		$data['find'] = 0;
		if ($data['type'] == 2){
			$data['psw'] = md5($_GPC['password']);
		}else{
			$data['psw'] = '';
			if ($data['type']==0){
				$data['find'] = 1;
			}
		}
	}elseif ($op == 'add'){
		$mem = pdo_fetch("select id,nickname,avatar,openid from ".tablename($this->modulename.'_member')." where openid='{$_W['openid']}' and weid='{$_W['uniacid']}'");
		$sid = pdo_fetchcolumn("select id from ".tablename($this->modulename.'_style')." where weid='{$_W['uniacid']}' and status=1 and price=0 order by sort desc, id asc limit 1");
		if (empty($sid)) die(json_encode(array('error'=>1,'msg'=>'请先导入模板！')));
		$mid = pdo_fetchcolumn("select id from ".tablename($this->modulename.'_music')." where weid='{$_W['uniacid']}' and status=1 order by sort desc, id asc limit 1");
		if (empty($mid)) die(json_encode(array('error'=>1,'msg'=>'请先导入音乐！')));
		$data = array('weid'=>$_W['uniacid'],'openid'=>$mem['openid'],'nickname'=>$mem['nickname'],'avatar'=>$mem['avatar'],'createtime'=>time(),'styleid'=>$sid,'musicid'=>$mid,'cover'=>$_GPC['cover']);
		$data['imgs'] = serialize($_GPC['pics']);
		pdo_insert($this->modulename.'_works',$data);
		die(json_encode(array('error'=>0,'msg'=>base64_encode(pdo_insertid()))));
	}elseif ($op == 'edit'){
		$upPath = IA_ROOT."/attachment";
		$imgs = $_GPC['pics'];
		$cover = $_GPC['cover'];
		$data['title'] = $_GPC['title'];
		$data['musicid'] = $_GPC['music'];
		$data['cover'] = $cover;
		$data['imgs'] = serialize($imgs);
	}elseif ($op == 'setDownType'){
		$data['special']= $_GPC['downtype'];
	}elseif ($op == 'style'){
		$sid = intval($_GPC['styleid']);
		$data['styleid'] = $sid;
		$style = pdo_fetch('select id,price from '.tablename($this->modulename."_style")." where id='{$sid}'");
		if ($style['price'] > 0){
			$buy_styleid = pdo_fetchcolumn('select buy_styleid from '.tablename($this->modulename."_member")." where openid='{$_W['openid']}'");
			if ($buy_styleid){
				$buy_styleid = explode(',', $buy_styleid);
				if (!in_array($sid, $buy_styleid)){  
					$data['preview'] = $sid;
					unset($data['styleid']);
				}
			}else{
				$data['preview'] = $sid;
				unset($data['styleid']);
			}
		}
	}
	pdo_update($this->modulename.'_works',$data,array('id'=>$id));
}