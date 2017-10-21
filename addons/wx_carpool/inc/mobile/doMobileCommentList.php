<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2017/3/5
 * Time: 18:05
 */

require_once(dirname(__FILE__) . "/../../model/comment.php");
global $_GPC;
$abc_id_abc=$_GPC['id'];
$abc_num_abc=$_GPC['num'];
$abc_data_abc=commentModel::allComment($abc_id_abc,$abc_num_abc);
$abc_comments_abc=$abc_data_abc[1];
$abc_count_abc=$abc_data_abc[0];
include $this->template('commentList');
