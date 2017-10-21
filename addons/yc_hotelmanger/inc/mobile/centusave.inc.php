<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;
$uid = $_POST['uid'];
$data['realname'] = $_POST['realname'];
$data['mobile'] = $_POST['mobile'];
load()->model("mc");
$result = mc_update($uid, $data);
echo json_encode("编辑成功");
