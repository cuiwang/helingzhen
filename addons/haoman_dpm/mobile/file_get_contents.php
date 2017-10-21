<?php
global $_GPC,$_W;
// $this->checkFollow;
// $this->checkBowser;
$url=$_W['attachurl'].'images/9/2016/10/geB6m8MzHpNvpQZMMqV28e2VvvEzZO.jpg';
$image_content = file_get_contents($url);
$image = imagecreatefromstring($image_content);
$width = imagesx($image);
$height = imagesy($image);
echo $width.'*'.$height."nr";