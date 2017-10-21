<?php
/**
 * 微城市模块处理程序
 *
 * @author 小义
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Enjoy_cityModuleProcessor extends WeModuleProcessor {
	public function respond() {
	    global $_W;
	    $uniacid=$_W['uniacid'];
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		$openid = $this -> message['from'];
		$rid=$this->rule;
		$firm=pdo_fetch("select id,img from ".tablename('enjoy_city_firm')." where rid='".$rid."' and uniacid=".$uniacid);
		
	//	return $this->respText($openid.'=='.$rid."select id from ".tablename('enjoy_city_firm')." where rid='".$rid."' and uniacid=".$uniacid."");
		//找到openid对应的uid
		//$fid=pdo_fetchcolumn("select id from ".tablename('enjoy_city_firm')." where rid='".$rid."' and uniacid=".$uniacid."");
		$count=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_city_firmfans')." where openid='".$openid."' and rid=".$rid." and uniacid=".$uniacid."
		    and flag=1");
		if($count>0){
		    //说明已经是本店的粉丝了
		    $title="您已经是本店粉丝了";
		}else{
		    //插入条记录
// 		    $data=array(
// 		      'uniacid'=>$uniacid,
// 		        'rid'=>$rid,
// 		        'fid'=>$firm['id'],
// 		        'openid'=>$openid,
// 		        'createtime'=>TIMESTAMP
// 		    );
// 		   $res= pdo_insert('enjoy_city_firmfans',$data);
$ffid=pdo_fetchcolumn("select id from ".tablename('enjoy_city_firmfans')." where rid=".$rid." and openid='".$openid."' and uniacid=".$uniacid." order by id desc limit 1");
//更新粉丝状态
$update=pdo_update('enjoy_city_firmfans',array('flag'=>1),array('id'=>$ffid));
	if($update>0){
	    $title="恭喜您已经成为本店粉丝";
	}else{
	    $title="打开页面点击成为本店粉丝哦";
	}
		
		}
		
		//return $this->respText($openid.'=='.$rid.$res);
		if(empty($firm['img'])){
		   $img=pdo_fetchcolumn("select banner from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
		}else{
		    $img=$firm['img'];
		}
		$news = array(
		    array('title' => $title,
		        'description' => '点击我成为粉丝后还可以评价哦',
		        'picurl' => tomedia($img),
		        //'url' =>$this->createMobileUrl("index",array('pid'=>1,'code'=>'test4hfi1ycb2v'))
		        'url' =>$_W['siteroot'] . 'app/index.php?c=entry&do=firm&m=enjoy_city&fid='.$firm[id].'&i=' . $_W['uniacid']
// 		        'url' =>$_W['siteroot'] . 'app/index.php?c=entry&do=index&m=enjoy_fix&'.$Codearr.'&i=' . $_W['uniacid']
		    ));
		return	$this->respNews($news);
		
	}
}