<?php



defined('IN_IA') or exit('Access Denied');
define("MON_JGG", "mon_jgg");

require_once IA_ROOT . "/addons/" . MON_JGG . "/CRUD.class.php";
class Mon_JggModuleProcessor extends WeModuleProcessor {

    private $sae=false;

    public function respond() {
        $rid = $this->rule;
		
        $jgg=pdo_fetch("select * from ".tablename(CRUD::$table_jgg)." where rid=:rid",array(":rid"=>$rid));


        if(!empty($jgg)){

            if(TIMESTAMP<$jgg['starttime']){

                return   $this->respText("活动未开始");
            }elseif(TIMESTAMP>$jgg['endtime']){
               return  $this->respText("活动已结束");
            }else{

                $from=$this->message['from'];
                $news = array ();
                $news [] = array ('title' => $jgg['new_title'], 'description' =>$jgg['new_content'], 'picurl' => $this->getpicurl ( $jgg ['new_icon'] ), 'url' => $this->createMobileUrl ( 'index',array('openid'=>$from,'jid'=>$jgg['id']))  );
                return $this->respNews ( $news );
            }


        }else{
          return   $this->respText("幸运九宫格活动删除或不存在");

        }

        return null;
    }




    private function getpicurl($url) {
        global $_W;

        if($this->sae){
            return $url;
        }

        return $_W ['attachurl'] . $url;

    }

}
