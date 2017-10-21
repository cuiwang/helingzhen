<?php

/**
 * 拍大白
 * 
 * @author ewei QQ: 22185157
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_takephotoModule extends WeModule {
  
   
  public function fieldsFormDisplay($rid = 0) {
        global $_W;

        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_takephoto_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $awards = unserialize($reply['awards']);
            $items = unserialize($reply['items']);
        }
        if (!$reply) {
            $now = time();
            $reply = array(
                "starttime" => $now,
                "endtime" =>  $now + 86400,
                "bgimg"=> '../addons/ewei_takephoto/style/bg.jpg',
                "shareimg"=> '../addons/ewei_takephoto/style/share.png',
                "titleimg"=> '../addons/ewei_takephoto/style/title.png',
                "cameraimg"=> '../addons/ewei_takephoto/style/camera.png',
                "helpimg"=> '../addons/ewei_takephoto/style/help.png',
                "numberimg"=> '../addons/ewei_takephoto/style/number.png',
            );
            $items = array(
               array("thumb"=>'../addons/ewei_takephoto/style/item.png',"score"=>1),
               array("thumb"=>'../addons/ewei_takephoto/style/item1.png',"score"=>2)
            );
        }

        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }
 

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        
        $insert = array(
            'rid' => $rid,
            'uniacid' =>$_W['uniacid'],
            'title' => $_GPC['title'],
            'thumb' => $_GPC['thumb'],
            'description' => $_GPC['description'],
            'starttime'=>strtotime($_GPC['datelimit']['start']),
            'endtime'=>strtotime($_GPC['datelimit']['end']),
            'bgimg' => $_GPC['bgimg'],
            'shareimg' => $_GPC['shareimg'],
            'titleimg' => $_GPC['titleimg'],
            'helpimg' => $_GPC['helpimg'],
            'cameraimg' => $_GPC['cameraimg'],
            'numberimg' => $_GPC['numberimg'],
           "follow_url"=>$_GPC['follow_url'],
            "follow_button"=>$_GPC['follow_button'],
           "share_url"=>$_GPC['share_url'],
           "share_title"=>$_GPC['share_title'],
           "share_icon"=>$_GPC['share_icon'],
           "share_desc"=>$_GPC['share_desc'],
            
        );
        
           //物品
        $item_ids= $_GPC['item_id'];
        $items = array();
        if(is_array($item_ids)){
            foreach($item_ids as $key =>$value){
                $d = array(
                    "id"=>$value,
                    "score"=>$_GPC['item_score_'.$value],
                    "rate"=>$_GPC['item_rate_'.$value],
                    "name"=>$_GPC['item_name_'.$value],
                    "thumb"=>$_GPC['item_thumb_'.$value],
                );
                $items[] = $d;
            }
        }
        $insert['items'] = serialize($items);
      
        
        if (empty($id)) {
            $insert['createtime'] = time();
            if ($insert['starttime'] <= time()) {
                $insert['status'] = 1;
            } else {
                $insert['status'] = 0;
            }
            $id = pdo_insert('ewei_takephoto_reply', $insert);
        } else {
            pdo_update('ewei_takephoto_reply', $insert, array('id' => $id));
            
        }
        return true;
    }

    public function ruleDeleted($rid) {
        pdo_delete("ewei_takephoto_reply", array("rid" => $rid));
    }
}
