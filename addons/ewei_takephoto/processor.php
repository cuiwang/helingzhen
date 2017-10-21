<?php
/**
 * 拍大白
 *
 * @author ewei QQ:22185157
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_takephotoModuleProcessor extends WeModuleProcessor {

    public function respond() {
         global $_W;
        $rid = $this->rule;
        $sql = "SELECT title,description,thumb,status,starttime,endtime FROM " . tablename('ewei_takephoto_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));

        if ($row == false) {
            return $this->respText("活动已取消...");
        }
        return $this->respNews(array(
                    'Title' => $row['title'],
                    'Description' => $row['description'],
                    'PicUrl' => tomedia($row['thumb']),
                    'Url' => $this->createMobileUrl('index', array('rid' => $rid)),
        ));
    }

}
