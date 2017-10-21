<?php
/**
 * =site&a=entry&recid=133&threadid=1&do=sendNotifyProcess&m=q_3354988381_notify
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
        $recid = $_GPC['recid'];
        $threadid = $_GPC['threadid'];
        
        $notify_data = pdo_fetch("SELECT * FROM " . tablename("qwx_notify_notify") . " WHERE id= '$recid'");
        
        $lastid = intval($_GPC['lastid']);
        $sql = "SELECT * FROM " . tablename("qwx_notify_son") . " WHERE notify_id= '$recid' and threadid='$threadid' and status=0 and id>$lastid";
            
        $fansInfoAll = pdo_fetchall($sql);
        if (empty($fansInfoAll)) {
            exit("发送完毕，线程结束...");
        }
        $fansInfo = $fansInfoAll[0];
        $noticeStr = "线程{$threadid}，剩余数" . (count($fansInfoAll)-1);
        $targetUrl = $this->createWebUrl("sendnotifyprocess", array("recid" => $recid, "threadid" => $threadid, 'lastid' => $fansInfo['id']), true);
        
        
        $type = strtoupper($notify_data['type']);
        if ($type == 'M') {
            $content_arr = unserialize($notify_data['content']);
        } else if ($type == 'T') {
            $content_arr = $notify_data['tpl_id'];            
        } else if ($type == 'I') {
            $content_arr = $notify_data['info_id'];            
        } else if ($type == 'P') {//info 图文
            $content_arr = $data['pic'];            
        }     
        $ret = $this->sendNotify($fansInfo['openid'], $type, $content_arr, $notify_data['weid']);
        if ($ret['errcode'] == '0' || $ret == 1) {
            $status = 1;
            $res = "成功";
        } else {
            $status = -1;
            $res = "失败:" . $ret['message'];
        }
        pdo_update('qwx_notify_son', array('status' => $status), array('id' => $fansInfo['id']));
    ?>
    <html>   
        <head>
            <title>正在发送中......</title>
            <?php if ($noticeStr) {  ?>
                <meta http-equiv="refresh" content="1;url=<?php echo $targetUrl; ?>" /> 
             <?php } ?>
        </head>   
        <body>   
            <?php echo $noticeStr;?>
        </body> 
    </html>  
    <?php
    exit();
        //$curr_index_url = $this->createWebUrl('notify');
       // message('批量发放完成！通知结果 -> '.$res, $curr_index_url, 'success');     

?>






