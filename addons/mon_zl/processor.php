<?php
/**
 */
defined('IN_IA') or exit('Access Denied');
define("MON_ZL", "mon_zl");
require_once IA_ROOT . "/addons/" . MON_ZL . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_ZL . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_ZL . "/value.class.php";

class Mon_ZLModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $rid = $this->rule;
        
        
        $zl = pdo_fetch("select * from " . tablename(DBUtil::$TABLE_ZL) . " where rid=:rid", array(
            ":rid" => $rid
        ));
        if (!empty($zl)) {
            if (TIMESTAMP < $zl['starttime']) {
                return $this->respText("助力活动还未开始哦!");
            }
            $news   = array();
            $news[] = array(
                'title' => $zl['new_title'],
                'description' => $zl['new_content'],
                'picurl' => MonUtil::getpicurl($zl['new_icon']),
                'url' => $this->createMobileUrl('Auth', array(
                    'zid' => $zl['id'],
                    'au' => Value::$REDIRECT_INDEX
                ))
            );
            return $this->respNews($news);
        } else {
            return $this->respText("摇一摇活动不存在");
        }
        
        return null;
        
        
    }
    
    
}
