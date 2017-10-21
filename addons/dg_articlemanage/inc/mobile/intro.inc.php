<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/24
 * Time: 17:05
 */
global $_W,$_GPC;
load()->func('tpl');
$cfg=$this->module['config'];
$cfg['center_intro']= empty($cfg['center_intro']) ? "成为会员可以免费阅读需要付费的文章":$cfg['center_intro'];

$intro=htmlspecialchars_decode($cfg['center_intro']);
include $this->template('intro');