<?php
/**
 * 全民来植树
 *
 * 作者:迷失卍国度
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');

class weisrc_treeModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT title,description,start_picurl,isshow,starttime,endtime,end_theme,end_instruction,end_picurl FROM " . tablename('weisrc_tree_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
        $from_user = $this->message['from'];

        if ($row == false) {
            return $this->respText("活动已取消...");
        }

        if ($row['isshow'] == 0) {
            return $this->respText("活动暂停，请稍后...");
        }

        if ($row['starttime'] > time()) {
            return $this->respText("活动未开始，请等待...");
        }

        $endtime = $row['endtime'] + 68399;
        if ( $endtime < time()) {
            return $this->respNews(array(
                        'Title' => $row['end_theme'],
                        'Description' => $row['end_instruction'],
                        'PicUrl' => tomedia($row['end_picurl']),
                        'Url' => $this->createMobileUrl('index', array('id' => $rid)),
            ));
        } else {
            return $this->respNews(array(
                        'Title' => $row['title'],
                        'Description' => $row['description'],
                        'PicUrl' => tomedia($row['start_picurl']),
                        'Url' => $this->createMobileUrl('index', array('id' => $rid)),
            ));
        }
    }

}
