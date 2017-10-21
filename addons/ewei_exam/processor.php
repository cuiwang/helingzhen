<?php

/**
 * 微考试
 *
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_examModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $rid = $this->rule;
        $reply = pdo_fetchall("SELECT * FROM " . tablename('ewei_exam_reply') . " WHERE rid = :rid", array(':rid' => $this->rule));
        if (!empty($reply)) {
            foreach ($reply as $row) {
                $paperids[$row['id']] = $row['paperid'];
            }
            $papers = pdo_fetchall("SELECT id, title, description FROM " . tablename('ewei_exam_paper') . " WHERE id IN (" . implode(',', $paperids) . ")", array(), 'id');
            $response = array();
            foreach ($reply as $row) {
                $row = $papers[$row['paperid']];
                $response[] = array(
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'picurl' => '',
                    'url' => $this->createMobileUrl('ready', array('id' => $row['id']))
                );
            }
            return $this->respNews($response);
        }
    }

}
