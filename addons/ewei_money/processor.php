<?php

/**
 * 疯狂划算
 *
 * @author ewei 012wz.com
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_moneyModuleProcessor extends WeModuleProcessor {

    public function respond() {

        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('ewei_money_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
        if ($row == false) {
            return $this->respText("活动已取消...");
        }
        if ($row['isshow'] == 0) {
            return $this->respText("活动还没开始，请稍后...");
        }
         
        if (!empty($row['starttime']) && $row['starttime'] > time()) {
            return $this->respText("数钱活动还未开始");
        }
        $news = array(
            array(
                'title' => $row['title'],
                'description' => trim(strip_tags($row['description'])),
                'picurl' => tomedia($row['start_picurl']),
                'url' => $this->createMobileUrl('index', array('id' => $rid))
        ));
        return $this->respNews($news);
    }

}
