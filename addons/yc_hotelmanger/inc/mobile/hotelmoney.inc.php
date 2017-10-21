<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;
$mid = $_POST['myid'];
$sintdate = $_POST['sintdate'];
$soutdate = $_POST['soutdate'];
$sql = 'SELECT m.id as mid,m.motitle as mtitme,m.cprice,m.hotelid ,m.roomid ,r.* FROM ' . tablename($this->momy) . ' m left join ' . tablename($this->room) . ' r on r.id = m.roomid  WHERE m.status= 1 and m.id= ' . $mid;
$list = pdo_fetch($sql);
$url = $this->createMobileUrl('order', array('mid' => $list['mid'], 'sintdate' => $sintdate, 'soutdate' => $soutdate, 'hotelid' => $list['hotelid'], 'roomid' => $list['roomid']));
//echo json_encode($url);
echo $url;