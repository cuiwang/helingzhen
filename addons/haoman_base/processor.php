<?php
defined('IN_IA') or exit('Access Denied');
 

class haoman_qjbModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "select * from " . tablename('haoman_qjb_reply') . " where `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));

        if ($row == false) {
            return $this->respText("活动已取消...");
        }

        if ($row['isshow'] == 0) {
            return $this->respText("活动暂停，请稍后...");
        }

        if ($row['starttime'] > time()) {
            return $this->respText("活动未开始，请等待...");
        }

        $endtime = $row['endtime'];
        if ( $endtime < time()) {
            return $this->respText("活动已经结束了...");
        } else {
            return $this->respNews(array(
                        'Title' => $row['title'],
                        'description' => $row['description'],
                        'PicUrl' => toimage($row['start_picurl']),
                        'Url' => $this->createMobileUrl('index', array('rid' => $rid)),
            ));
        }
    }

}
