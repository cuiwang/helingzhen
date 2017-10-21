<?php



defined('IN_IA') or exit('Access Denied');
define("MON_ZJP", "mon_zjp");
require_once IA_ROOT . "/addons/" . MON_ZJP . "/dbutil.class.php";

class Mon_ZjpModuleProcessor extends WeModuleProcessor {

    private $sae=false;

    public function respond() {
        $rid = $this->rule;




        $zjp=pdo_fetch("select * from ".tablename(CRUD::$table_zjp)." where rid=:rid",array(":rid"=>$rid));

        if(!empty($zjp)){

            if(TIMESTAMP<$zjp['starttime']){

                return   $this->respText("活动未开始");

            }else{

                $from=$this->message['from'];

                $news = array ();
                $news [] = array ('title' => $zjp['new_title'], 'description' =>$zjp['new_content'], 'picurl' => $this->getpicurl ( $zjp ['new_icon'] ), 'url' => $this->createMobileUrl ( 'index',array('openid'=>$from,'zid'=>$zjp['id']))  );
                return $this->respNews ( $news );
            }


        }else{

          return   $this->respText("抓奖品活动删除或不存在");

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
