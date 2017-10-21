<?php
/**
 * 万能机器人助手模块处理程序
 *
 * @author 凡尘爵士
 * @url http://caogenzhi.com
 */
defined('IN_IA') or exit('Access Denied');

class Ykbl_robotModuleProcessor extends WeModuleProcessor {
	public function respond() {
        	global $_GPC, $_W;
		$content = $this->message['content'];
               //获取设置的图灵API
                $tlapi=  $this->module["config"]['api'];
                $tc=$this->module["config"]['close'];
                $welcom=  $this->module["config"]['welcom'];
                $out=  $this->module['config']['out'];
                $apiKey = $tlapi; 
                $apiURL = "http://www.tuling123.com/openapi/api?key=KEY&info=INFO";
                if (!$this->inContext){
                if (empty($welcom)){
                $replay="亲，您已经进入万能机器人模式，请输入您想要知道的事情对机器人进行咨询！谢谢！\n功能指令:例如，讲笑话,打开XX网站,下载XX软件,A到B的火车，明天A到B的飞机,看新闻....\n如果需要退出，请输入【".$tc."】";
                }else{
                $replay=$welcom;    
                }
                $this->beginContext("1800");
                }else{
                 if ($content==$tc){
                        $this->endContext();
                        if (empty($out)){
                        return $this->respText("亲，您已经成功退出了万能机器人模式！") ;
                        }else{
                         return $this->respText($out) ;  
                        }
                 }else{
                           
                $reqInfo=$content; 
                $url = str_replace("INFO", $reqInfo, str_replace("KEY", $apiKey, $apiURL));  
                $res =file_get_contents($url); 
                $ress=json_decode($res,TRUE);
           //    $resss=$this->object_array($ress);
                 $code=$ress['code'];
                 switch ($code){
                 case 100000:
                      $replay=$ress['text'];
                        break;
                 case 200000:
                        $replay = $ress['text'] . "<a href='" . $ress['url'] . "'>请点击这里进入</a>";
                        break;
                 case 302000:
                        $istype=1;
                       $listcount=  count($ress['list']);
                       $news=array();
                       foreach ($ress['list'] AS $key=>$v ){
                          if ($key<9){
                        $news[$key]["title"]=$v['article'];
                        if (empty($v['icon'])){
                              $news[$key]['picurl']="./addons/ykbl_robot/template/image/news.jpg";
                        }else{
                        $news[$key]['picurl']=$v['icon'];
                        }
                        $news[$key]["url"]=$v['detailurl'];
                          }
                       }
                       break;
                      
                 case 304000:      
                       $istype=1;
                       $listcount=  count($ress['list']);
                       $news=array();
                     foreach ($ress['list'] AS $key=>$v ){
                          if ($key<9){
                        $news[$key]["title"]=$v['name']."(".$v['count'].")";
                        if (empty($v['icon'])){
                              $news[$key]['picurl']="./addons/ykbl_robot/template/image/download.jpg";
                        }else{
                        $news[$key]['picurl']=$v['icon'];
                        }
                        $news[$key]["url"]=$v['detailurl'];
                          }
                       }
                       break;
                 case 305000:      
//                       $istype=1;
                       $listcount=  count($ress['list']);
  //                     $news=array();
                     $replay="";
                     foreach ($ress['list'] AS $key=>$v ){
                          if ($key<9){
                          $replay.=$v['trainnum']."\n"."起点:".$v['start']."--终点:".$v['terminal']."\n"."发车时间:".$v['starttime']."\n到达时间:".$v['endtime']."\n---------------\n";
//                        $news[$key]["title"]=$v['name']."(".$v['count'].")";
//                        if (empty($v['icon'])){
//                              $news[$key]['picurl']="./addons/ykbl_robot/template/image/news.jpg";
//                        }else{
//                        $news[$key]['picurl']=$v['icon'];
//                        }
//                        $news[$key]["url"]=$v['detailurl'];
                          }
                       }
                       break;       
                 case 306000:      
//                       $istype=1;
                       $listcount=  count($ress['list']);
  //                     $news=array();
                     $replay="";
                     foreach ($ress['list'] AS $key=>$v ){
                          if ($key<9){
                          $replay.=$v['flight']."\n"."起飞时间:".$v['starttime']."\n到达时间:".$v['endtime']."\n---------------\n";
//                        $news[$key]["title"]=$v['name']."(".$v['count'].")";
//                        if (empty($v['icon'])){
//                              $news[$key]['picurl']="./addons/ykbl_robot/template/image/news.jpg";
//                        }else{
//                        $news[$key]['picurl']=$v['icon'];
//                        }
//                        $news[$key]["url"]=$v['detailurl'];
                          }
                       }
                       break;                             
                 case 40001:
                 case 40003:
                 case 40004:
                 case 40005:
                 case 40006:
                 case 40007:
                     $replay="机器人已经在爆炸中了....错误代码:".$code."，请告诉管理员，或者会有惊喜喔！";
                     break;
                 case 40002:
                     $replay="您什么都没有告诉我，我怎么可以告诉您什么呢？";
                     break;
                 default :
                   $replay="机器人的相关功能正在开发中.....";  
                }
            
                
                 }
                }
                if ($istype==1){
              //      var_dump($news);
             return $this->respNews($news) ;
                }else{
            return $this->respText($replay) ;
                }
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
	}

}