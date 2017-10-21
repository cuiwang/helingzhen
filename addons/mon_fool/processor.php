<?php



defined('IN_IA') or exit('Access Denied');
define("MON_FOOL", "mon_fool");

require_once IA_ROOT . "/addons/" . MON_FOOL . "/CRUD.class.php";
class Mon_FoolModuleProcessor extends WeModuleProcessor {

    private $sae=false;

    public function respond() {
        $rid = $this->rule;
		
        $fool=pdo_fetch("select * from ".tablename(CRUD::$table_fool)." where rid=:rid",array(":rid"=>$rid));


        $from=$this->message['from'];
        $news = array ();
        $news [] = array ('title' => $fool['new_title'], 'description' =>$fool['new_content'], 'picurl' => $this->getpicurl ( $fool ['new_icon'] ), 'url' => $this->createMobileUrl ( 'index',array('openid'=>$from,'fid'=>$fool['id']))  );
        return $this->respNews ( $news );



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
