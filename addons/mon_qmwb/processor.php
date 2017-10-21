<?php

defined('IN_IA') or exit('Access Denied');
define("MON_QMWB", "mon_qmwb");
require_once IA_ROOT . "/addons/" . MON_QMWB . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_QMWB . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_QMWB . "/value.class.php";

class Mon_QmwbModuleProcessor extends WeModuleProcessor {


    public function respond() {
        $rid = $this->rule;
        $qmwb=pdo_fetch("select * from ".tablename(DBUtil::$TABLE_QMWB)." where rid=:rid",array(":rid"=>$rid));

        if(!empty($qmwb)){

                $news = array ();
                $news [] = array ('title' => $qmwb['new_title'], 'description' =>$qmwb['new_content'], 'picurl' => MonUtil::getpicurl($qmwb ['new_icon'] ), 'url' => $this->createMobileUrl ( 'auth',array('qid'=>$qmwb['id'],'au'=>Value::$REDIRECT_USER_INDEX))  );
                return $this->respNews ( $news );
        }else{

          return   $this->respText("活动删除或不存在");

        }

        return null;


    }



}
