<?php
/**
 * 【超人】抢楼活动模块处理程序
 *
 * @author 超人
 * @url
 */
defined('IN_IA') or exit('Access Denied');

include 'common.inc.php';

class Superman_floorModuleProcessor extends WeModuleProcessor {
    public function respond() {
        global $_W;
        $rid = $this->rule;
        //$content = $this->message['content'];
        $openid = $this->message['from'];
        load()->model('mc');
        $uid = mc_openid2uid($openid);

        $row = pdo_fetch("SELECT awardprompt,currentprompt,floorprompt,setting FROM " . tablename('superman_floor') . " WHERE rid=$rid");
        $rule_name = pdo_fetchcolumn("SELECT name FROM " . tablename('rule') . " WHERE id=$rid");
        if ($row) {
            $setting = unserialize($row['setting']);
            if (!$setting['repeat_floor']) {
                $floor = $this->get_floor();
                if ($floor) {
                    $result = str_replace('{RULENAME}', $rule_name, $row['floorprompt']);
                    $result = str_replace('{FLOOR}', $floor['id'], $result);
                    $result = str_replace('{TIME}', date('Y-m-d H:i:s', $floor['dateline']), $result);
                    $winninginfo = $_W['siteroot'].'app/'.$this->createMobileUrl('winninginfo',array('rid' => $rid));
                    //return $this->respText($result);
                    $news = array(
                        array(
                            'title'       => '您已参与过本活动！',
                            'description' => $result,
                            'picurl'      => '',
                            'url'         => $winninginfo,
                        )
                    );
                    return $this->respNews($news);
                }
            }
        }
        $new_data = array(
            'dateline' => $_W['timestamp'],
            'openid' => $openid,
        );
        pdo_insert("superman_floor_$rid", $new_data, false);
        $new_id = pdo_insertid();
        if ($new_id <= 0) {
            return $this->respText('系统异常，请稍后重试！');
        }
        $awards = pdo_fetchall("SELECT * FROM ".tablename('superman_floor_award')." WHERE rid=$rid");
        $prompt = pdo_fetch("SELECT awardprompt,currentprompt,floorprompt,setting FROM " . tablename('superman_floor') . " WHERE rid=$rid");
        if ($awards) {
            foreach ($awards as $item) {
                $floors = explode(',', $item['floors']);
                if (in_array($new_id, $floors)) {
                    $new_data = array(
                        'rid' => $rid,
                        'floor' => $new_id,
                        'uid' => $uid,
                        'uniacid' => $_W['uniacid'],
                        'openid' => $openid,
                        'award_id' => $item['id'],
                        'ip' => $_W['clientip'],
                        'dateline' => $_W['timestamp'],
                    );
                    pdo_insert('superman_floor_winner', $new_data);
                    $winner_id = pdo_insertid();
                    $result = str_replace('{RULENAME}', $rule_name, $row['awardprompt']);
                    $result = str_replace('{FLOOR}', $new_id, $result);
                    $result = str_replace('{AWARD}', $item['title'], $result);
                    $result = str_replace('{DESCRIPTION}', $item['description'], $result);
                    $url = $_W['siteroot'].'app/'.$this->createMobileUrl('infosubmit', array(
                            '_x' => superman_authcode("$rid|$winner_id|$openid", 'ENCODE'), 'rid' => $rid,
                        ));
                    $winninginfo = $_W['siteroot'].'app/'.$this->createMobileUrl('winninginfo',array('rid' => $rid));
                    $news = array(
                        array(
                            'title'       => '恭喜您,获得'.$item['title'].'！',
                            'description' => $result,
                            'picurl'      => '',
                            'url'         => $url,
                        )
                    );
                    return $this->respNews($news);
                }
            }
        }
        $result = str_replace('{RULENAME}', $rule_name, $row['currentprompt']);
        $result = str_replace('{FLOOR}', $new_id, $result);
        $winninginfo = $_W['siteroot'].'app/'.$this->createMobileUrl('winninginfo',array('rid' => $rid));
        $news = array(
            array(
                'title'       => '未中奖，再接再厉！',
                'description' => $result,
                'picurl'      => '',
                'url'         => $winninginfo,
            )
        );
        return $this->respNews($news);
    }

    private function get_floor()
    {
        $rid = $this->rule;
        $openid = $this->message['from'];
        return pdo_fetch("SELECT * FROM " . tablename('superman_floor_'.$rid) . " WHERE openid='$openid' ORDER BY id DESC LIMIT 1");
    }
}
