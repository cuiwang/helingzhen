<?php
/**
 * 极速龙舟模块微站定义
 * 易福源码网 www.efwww.com
 */
defined('IN_IA') or exit('Access Denied');
function hidetel($phone){
    $IsWhat = preg_match('/(0[0-9]{2,3}[-]?[2-9][0-9]{6,7}[-]?[0-9]?)/i',$phone); 
    if($IsWhat == 1){
        return preg_replace('/(0[0-9]{2,3}[-]?[2-9])[0-9]{3,4}([0-9]{3}[-]?[0-9]?)/i','$1****$2',$phone);
    }else{
        return  preg_replace('/(1[3587]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
    }
}
function is_weixin() {
	$agent = $_SERVER ['HTTP_USER_AGENT'];
	if (! strpos ( $agent, "icroMessenger" )) {
		return false;
	}
	return true;
}
class Yoby_jisuModuleSite extends WeModuleSite {
public $mod_name = "yoby_jisu";
	public $title = "极速龙舟";
   	public $t_fans;
    public $t_num;
    public $t_reply;
    public $t_top;
    public $weid;
    public $yobyurl;
	public function __construct(){
		global $_GPC, $_W;
		$this->t_fans = $this->mod_name."_fans";
		$this->t_num = $this->mod_name."_num";
		$this->t_reply = $this->mod_name."_reply";
		$this->t_top = $this->mod_name."_top";
		$this->weid = $_W['uniacid'];
		$this->yobyurl =$_W['siteroot']."addons/".$this->mod_name."/template/mobile/";
		load()->model('mc');
		$openid = $_W['openid'];
		$id = intval($_GPC['id']);
		  
		$isreg= pdo_fetchcolumn("select headimgurl from ".tablename($this->t_fans)."  where  rid=:rid and openid=:openid", array(':rid' => $id,':openid'=>$openid));
    
      if(empty($isreg) && !empty($openid)){
      	 $userinfo = mc_oauth_userinfo();
          $nickname=$userinfo['nickname'];
	$headimgurl=$userinfo['avatar'];
	$isreg1=pdo_fetchcolumn("select headimgurl from ".tablename($this->t_fans)."  where  rid=:rid and openid=:openid", array(':rid' => $id,':openid'=>$openid));
if(empty($isreg1)){	pdo_query("insert ignore into ".tablename($this->t_fans)."(rid,weid,openid,nickname,headimgurl) values($id,{$this->weid},'$openid','$nickname','$headimgurl')");}
	
      }	
		}
   public function doMobileindex() {//游戏首页
        global $_GPC, $_W;
        $openid = $_W['openid'];
        $id = intval($_GPC['id']);
        
        $row = pdo_fetch("SELECT * FROM " . tablename($this->t_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));
       $data = unserialize($row['data']);
       
      
      
		if (empty($id)) {message('id参数错误了！', '', 'error');}
    if(!is_weixin() ){die('<script type="text/javascript">alert("调皮,怎么在电脑上打开呢!");</script>');}
        $now = time();
        if($row['end_time'] < $now)	{
		 die('<script type="text/javascript">alert("活动已结束!");location.href="'.$this->createMobileUrl('rank',array('id'=>$id)).'"
</script>');
	}
	  if($row['isok'] ==2)	{
		 die('<script type="text/javascript">alert("活动暂停中等待开启!");location.href="'.$this->createMobileUrl('rank',array('id'=>$id)).'"
</script>');
	}
     if($row['isok'] ==0)	{
		 die('<script type="text/javascript">alert("活动未开始或已经提前结束!");location.href="'.$this->createMobileUrl('rank',array('id'=>$id)).'"
</script>');
	}  	
     	 $day_num =  intval($row['day_num']);//每天最多次数
     	 $max_num = intval($row['max_num']);//总次数
     	 $sharenum = intval($row['sharenum']);
     	 $today = date('Y-m-d',time());    	 
      $daynum = pdo_fetchcolumn("select day_num from ".tablename($this->t_num)."  where   openid='$openid'  and  createtime='$today'  and rid = '$id' ");//查询当天次数
      $playnum= pdo_fetchcolumn("select play_num from ".tablename($this->t_fans)."  where  openid=:openid and rid=:rid ", array(':openid'=>$openid,':rid' => $id));//记录所玩次数
      if($playnum<$max_num){
        if($daynum>($day_num-1)){
        die('<script>alert("今天'.$day_num.'次机会已经用完了,分享游戏到朋友圈可以多玩'.$sharenum.'次哦!");location.href="'.$this->createMobileUrl('rank',array('id'=>$id)).'"</script>'); 
        }
      
      }else{
      die('<script>alert("总'.$max_num.'次机会已经用完了,不能再参加本次活动了!");location.href="'.$this->createMobileUrl('rank',array('id'=>$id)).'"</script>');   
      }

   
    include $this->template('index');   
        }
public function doMobileOk(){
	echo '{"status":1,"message":"\u64cd\u4f5c\u6210\u529f\uff01"}';
}


    public function doMobileshare() {//分享增加机会
  global $_GPC, $_W;
        $id = intval($_GPC['id']); 
         $openid = $_GPC['openid'];
         $today = date('Y-m-d',time());
         $n = (int)pdo_fetchcolumn("SELECT sharenum FROM " . tablename($this->t_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));
         $is_share= pdo_fetch("select id,is_share,day_num from ".tablename($this->t_num)."  where  rid=$id and openid='$openid' and createtime='$today' ");//今天是否分享
         if(!empty($is_share)){
          if($is_share['is_share']==1){//分享过
         echo 0;
         }else{
         pdo_query("UPDATE ".tablename($this->t_num)." SET is_share=1,day_num=day_num-$n WHERE id=".$is_share['id']);//改分享状态
         echo 1;
         }
         
         }else{
         	$data1 = array(
	'weid'=>$_W['uniacid'],
		'rid'=>$id,
	'openid'=>$openid,
	'createtime'=>$today,
	'day_num'=>1,
	);
	pdo_insert($this->t_num,$data1);	
	$ids = pdo_insertid();
  pdo_query("UPDATE ".tablename($this->t_num)." SET is_share=1,day_num=day_num-$n WHERE id=".$ids);//改分享状态       
  echo 1;       
         }
         
         
         
         
        
 }
 
       public function doMobilesaveindex() {//注册会员
        global $_GPC, $_W;
        $id = intval($_GPC['id']);    
        	if (empty($_W['openid'])) {
		echo json_encode(array(
		'msg'=>'openid获取出错'
		));
		}
		$openid = $_W['openid'];
 $true_name = $_GPC['true_name'];
 $phone = $_GPC['phone'];
  $row = pdo_fetch("select id,phone from ".tablename($this->t_fans)."  where  rid=:rid and openid=:openid", array(':rid' => $id,':openid'=>$openid)); 
    	if (empty($row['phone'])) {
		$data = array(
	'names'=>$true_name,
	'phone'=>$phone,
	);
	pdo_update($this->t_fans,$data,array('id'=>$row['id']));
	echo json_encode(array(
		'done'=>'true'
		));
		}else{
		
		echo json_encode(array(
		'msg'=>'调皮,已注册过了,不再需要注册信息'
		));	
		}
        }
  
    public function doMobilerule() {//规则
        global $_GPC, $_W;
        $id = intval($_GPC['id']);    
       $modname = $this->mod_name;
	$row = pdo_fetch("SELECT * FROM " . tablename($this->t_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));	
		
    include $this->template('rule');    
        }
       public function doMobilerank() {//排名
        global $_GPC, $_W;
        $weid = $this->weid;
        $id = intval($_GPC['id']);
        $openid = $_W['openid'];
        $modname = $this->mod_name;
        $row = pdo_fetch("SELECT * FROM " . tablename($this->t_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));
         $z = pdo_fetchcolumn("select max(fen) from ".tablename($this->t_top)."  where openid='$openid' and rid=$id ");//最多分数
       $isreg= pdo_fetchcolumn("select phone from ".tablename($this->t_fans)."  where  openid=:openid and rid=:rid ", array(':openid'=>$openid,':rid' => $id));//是否注册
       
        $tp_sql="select * from (SELECT @rank:=@rank+1 as rank,openid,rid,id FROM " . tablename($this->t_fans) . " where  rid=$id and  openid!='' ORDER BY max_fen DESC,id asc) as A where rid=$id  and openid='$openid'";
         $s1="set @rank=0";
         pdo_query($s1);
         $top = pdo_fetchcolumn($tp_sql);//排名        
        $n = pdo_fetchcolumn("select count(*) from ".tablename($this->t_fans)."  where  rid=$id and  openid!=''");//参数人数
        $n = ($row['c_num']==0)?$n:$row['c_num'];//虚假人数
         $s1="set @rank=0";
         pdo_query($s1);
      $list= pdo_fetchall("select @rank:=@rank+1 as rank,names,phone,nickname,headimgurl,max_fen,play_num from ".tablename($this->t_fans)."  where  rid=$id  and  openid!=''   order   by max_fen desc,id asc  limit ".$row['pagenum']);   

    include $this->template('rank');    
        }      
    public function doMobileajaxrec() {//ajax 写入积分记录
        global $_GPC, $_W;
        $id = intval($_GPC['id']);    
		$openid = $_W['openid'];
	
 		$score = $_GPC['score'];

 $row = pdo_fetch("select * from ".tablename($this->t_fans)."  where  rid=:rid and openid=:openid", array(':rid' => $id,':openid'=>$openid));
 if(!empty($row)){
	$data = array(
	'rid'=>$id,
	'weid'=>$this->weid,
	'openid'=>$openid,
	'fen'=> $score,
	'createtime'=>time(),
	);
		$today = date('Y-m-d',time());
		$nn =  pdo_fetchcolumn("select id  from ".tablename($this->t_num)."  where   openid='$openid'  and  createtime='$today'   ");
		if($nn>0){
	pdo_query("UPDATE ".tablename($this->t_num)." SET day_num =day_num+1 WHERE id=".$nn);		
		}else{
			$data1 = array(
	'weid'=>$this->weid,
		'rid'=>$id,
	'openid'=>$openid,
	'createtime'=>$today,
	'day_num'=>1,
	);
	pdo_insert($this->t_num,$data1);		
		}

	
	pdo_insert($this->t_top,$data);
	
	
	$max_fen = pdo_fetchcolumn("select max(fen) from ".tablename($this->t_top)."  where openid='$openid' and rid=$id ");//最高分
	
	pdo_query("UPDATE ".tablename($this->t_fans)." SET play_num = play_num+1,max_fen=$max_fen  WHERE  openid='$openid' and  rid=$id ");//更新最高成绩和所玩次数
  
}
        }      
 		
	public function doWebGl() {//管理活动
		global $_GPC, $_W;
		
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->t_reply)." WHERE weid = ".$this->weid." ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->t_reply) . " WHERE weid = ".$this->weid);
		$pager = pagination($total, $pindex, $psize);
		include $this->template('gl');
	}
	public function doWebTop() {//pc 排行榜
		global $_GPC, $_W;
		
		
		$id = intval($_GPC['rid']);
		if (checksubmit('delete')) {
			pdo_delete($this->t_fans, " id  IN  ('".implode("','", $_GPC['delete'])."')");
			message('删除成功！', $this->createWebUrl('top', array('rid' => $id, 'page' => $_GPC['page'])));
		}
	
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
				$condition .= " AND (names LIKE '%".$_GPC['keyword']."%'   or  phone  LIKE '%".$_GPC['keyword']."%')  ";
			}		
			$s1="set @rank=0";
    pdo_query($s1);
			$list = pdo_fetchall("SELECT @rank:=@rank+1 as rank,names,phone,nickname,headimgurl,max_fen,play_num,id  FROM ".tablename($this->t_fans) ." WHERE rid=$id  and  openid!='' and weid =". $this->weid.$condition."  ORDER BY max_fen desc,id asc LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->t_fans) ." WHERE rid=$id and  openid!='' and  weid =". $this->weid.$condition);
	
		$pager = pagination($total, $pindex, $psize);
		
		include $this->template('top');
	}
public function doWebDaodata(){//导出CSV数据
		global $_W,$_GPC;
		
		$id = intval($_GPC['rid']);
		$s1="set @rank=0";
    pdo_query($s1);
         
		$list = pdo_fetchall("SELECT @rank:=@rank+1 as rank,names,phone,nickname,headimgurl,max_fen,play_num FROM ".tablename($this->t_fans)." where weid='".$this->weid."' and  rid=$id    and  openid!=''  order by max_fen desc,id asc limit 200");//查询
$export_str="姓名,手机号,排名,所玩次数,成绩\n";
$export_str= mb_convert_encoding($export_str, "gb2312",'auto');
    foreach($list as $v){

    
    if(empty($v['names'])){
					
						$name =  $v['nickname'];
						}else{
					$name =$v['names'];
						}

				 
				  $name= mb_convert_encoding($name, "gb2312",'auto');
				  $name1 =preg_replace("/\s+/","",$name);	
     $export_str.=$name1.','.$v['phone'].','.$v['rank'].','.$v['play_num'].','.$v['max_fen']."\n";
      }
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$this->title.".csv");
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
 
 
  echo $export_str;
	}  






}