<?php

defined('IN_IA') or exit('Access Denied');
define("MON_WKJ", "mon_wkj");
require_once IA_ROOT . "/addons/" . MON_WKJ . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_WKJ . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_WKJ . "/value.class.php";

class Mon_WkjModuleProcessor extends WeModuleProcessor {

    private $sae=false;

    public function respond() {
        $rid = $this->rule;




        $wkj=pdo_fetch("select * from ".tablename(DBUtil::$TABLE_WKJ)." where rid=:rid",array(":rid"=>$rid));

        if(!empty($wkj)){

                $news = array ();
                $news [] = array ('title' => $wkj['new_title'], 'description' =>$wkj['new_content'], 'picurl' => MonUtil::getpicurl( $wkj ['new_icon'] ), 'url' => $this->createMobileUrl ( 'auth',array('kid'=>$wkj['id'],'au'=>Value::$REDIRECT_USER_INDEX))  );
                return $this->respNews ( $news );
        }else{

          return   $this->respText("砍价活动不存在或删除");

        }

        return null;


    }



}
