<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
if ($op=="index") {
	$id = $_GPC['id'];
	if ($id) {
		$list = pdo_fetch("select * from ".tablename('dy_dy')." where uniacid=:uniacid and id=:id", $params = array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		
		$list = array_merge($list,unserialize($list['content1']));
		$taocan=explode('#',$list['taocan']);
		$yanse=explode('#',$list['yanse']);
		$chicun=explode('#',$list['chicun']);
		if ($_POST) {
			$tid=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
			$num = !empty($_GPC['num']) ? $_GPC['num'] : '1';
			$log = array(
                        'tid'=>$tid,
                        'goods_id'=>$id,
                        'name'=>$_GPC['name'],
                        'tel'=>$_GPC['tel'],
                        'money'=>$_GPC['price']*$num,
                        'num'=>$num,
                        'stat'=>0,
                        'kuaidi'=>0,
                        'kehubeizhu'=>$_GPC['guest'],
                        'chanpin'=>$taocan[$_GPC['product']],
                        'sheng'=>$_GPC['province'],
                        'shi' => $_GPC['city'],
                        'qu' =>$_GPC['area'],
                        'address'=>$_GPC['address'],
                        'openid'=>$_W['fans']['openid'],
                        'createtime'=>time(),
                        'uniacid' => $_W['uniacid']
                    );
            pdo_insert('dy_order', $log);
            message('下单成功。',$this->createMobileUrl('index',array('id'=>$id)));
            die();
		}
		include $this->template('index/index');
	}else{
      /*******读取首页配置********/
      $list = pdo_get('dy_index', array('uniacid' => $_W['uniacid']));
      $list['huandeng']=explode("#", $list['huandeng']);
      unset($list['huandeng'][count($list['huandeng'])-1]);
      $list['url'] = explode("#", $list['url']);
      /*******查询首页推荐*******/
      $goods = pdo_fetchall("select * from ".tablename('dy_dy')." where uniacid=:uniacid and chanpin>0 order by chanpin desc",array(':uniacid'=>$_W['uniacid']));

      include $this->template('index/shouye');            
      }

}