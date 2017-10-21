<?php
    global $_GPC, $_W;
    $rid = intval($_GPC['rid']);
    if (empty($rid)) 
    {
        message('抱歉，坐标不存在或是已经删除！', '', 'error');
    }

    $item = pdo_fetch("SELECT * FROM ".tablename('yhc_onenavi')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
    if (empty($item))
    {
        message('抱歉，坐标不存在或是已经删除！', '', 'error');
    }

    $url = "http://api.map.baidu.com/marker?location=".$item["lat"].",".$item["lng"]."&title=".$item['title']."&output=html";


    header('Location: '. $url);
    exit;

?>
