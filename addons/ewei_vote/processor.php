<?php

defined('IN_IA') or exit('Access Denied');
class Ewei_voteModuleProcessor extends WeModuleProcessor {

    public $name = 'VoteModuleProcessor';
 
    public function respond() {
        global $_W;
        $rid = $this->rule;
        $reply = pdo_fetch("SELECT * FROM " . tablename('vote_reply') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
        if ($reply == false) {
            return $this->respText('活动已经取消...');
        }
        $nowtime = time();
        $endtime = $reply['endtime'] + 86399;

        if ($reply['status'] == 0) {
            return $this->respText("投票已暂停，请等待...");
        }

        if ($reply['votelimit'] == 1) {
            if ($reply['votetotal']>0 && $reply['votenum'] >= $reply['votetotal']) {
                   return $this->respText("投票人数已满，活动结束...");
            }
        } else {
            if ($reply['starttime'] > $nowtime) {
                return $this->respText("投票未开始，请等待...");
            } elseif ($endtime < $nowtime) {
                return $this->respText("投票已结束...");
            } else {
//                if ($reply['status'] != 1) {
//                     return $this->respText("投票已暂停，请等待...");
//                }
            }
        }

            return $this->respNews(array(
                        'Title' => $reply['title'],
                        'Description' => $reply['description'],
                        'PicUrl' => tomedia($reply['thumb']),
                        'Url' => $this->createMobileUrl("index", array("id" => $rid)),
            ));
    }
 
}
