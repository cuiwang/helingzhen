<?php
defined('IN_IA') or exit('Access Denied');
load()->func('tpl');
class lwx_loveModuleSite extends WeModuleSite
{
    public function doMobilelist()
    {
        global $_GPC, $_W;
        include $this->template('index');
    }
    public function doMobileszj()
    {
        global $_GPC, $_W;
        $name = $_GPC['name'];
        $do   = "szj";
        $op   = $_GPC['op'];
        if (empty($name)) {
            include $this->template('szj');
        } else {
            if ($op == "show") {
                include $this->template('item');
                exit();
            } else {
                header('content-type:image/jpeg');
                $im = imagecreatetruecolor(640, 1138);
                $bg = imagecreatefromjpeg('../addons/lwx_love/template/mobile/img/szj.jpg');
                imagecopy($im, $bg, 0, 0, 0, 0, 640, 1138);
                imagedestroy($bg);
                $black = imagecolorallocate($im, 255, 255, 255);
                $name  = mb_convert_encoding($name, 'html-entities', 'UTF-8');
                $text  = $name;
                $font  = '../addons/lwx_love/template/mobile/css/boyan.ttf';
                imagettftext($im, 19, 0, 379, 786, $black, $font, $text);
                imagejpeg($im);
                imagedestroy($im);
            }
        }
    }
    public function doMobilely()
    {
        global $_GPC, $_W;
        $id   = $_GPC['id'];
        $name = $_GPC['name'];
        $do   = "ly";
        $op   = $_GPC['op'];
        if (empty($name)) {
            include $this->template('ly');
        } else {
            if ($op == "show") {
                include $this->template('item');
                exit();
            } else {
                header('content-type:image/jpeg');
                mb_internal_encoding('UTF-8');
                $id = 1;
                $im = imagecreatetruecolor(640, 847);
                $bg = imagecreatefromjpeg('../addons/lwx_love/template/mobile/img/ly.jpg');
                imagecopy($im, $bg, 0, 0, 0, 0, 640, 847);
                imagedestroy($bg);
                $black  = imagecolorallocate($im, 160, 160, 119);
                $blacka = imagecolorallocate($im, 10, 10, 10);
                if ($id == '1') {
                    $ida = "嫁给我吧";
                } else if ($id == '2') {
                    $ida = "来娶我吧";
                } else if ($id == '3') {
                    $ida = "我爱你！";
                } else if ($id == '4') {
                    $ida = "我喜欢你";
                } else {
                    echo '条件不满足';
                }
                $text = $name . "," . $ida;
                $font = '../addons/lwx_love/template/mobile/css/boyan.ttf';
                function autowrap($fontsize, $angle, $fontface, $string, $width)
                {
                    $content = "";
                    for ($i = 0; $i < mb_strlen($string); $i++) {
                        $letter[] = mb_substr($string, $i, 1);
                    }
                    foreach ($letter as $l) {
                        $teststr = $content . " " . $l;
                        $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
                        if (($testbox[2] > $width) && ($content !== "")) {
                            $content .= "\n";
                        }
                        $content .= $l;
                    }
                    return $content;
                }
                $texta = autowrap(1, 0, $font, $text, 0);
                imagettftext($im, 32, -7, 245, 170, $black, $font, $texta);
                $textaa = "—  —  —  —  —  —  —  —";
                imagettftext($im, 25, -97, 246, 136, $blacka, $font, $textaa);
                imagettftext($im, 25, -97, 253, 138, $blacka, $font, $textaa);
                imagettftext($im, 25, -97, 262, 140, $blacka, $font, $textaa);
                imagettftext($im, 25, -97, 271, 142, $blacka, $font, $textaa);
                imagejpeg($im);
            }
        }
    }
    public function doMobilehf()
    {
        global $_GPC, $_W;
        $name = $_GPC['name'];
        $id   = $_GPC['id'];
        $do   = "hf";
        $op   = $_GPC['op'];
        if (empty($name)) {
            include $this->template('hf');
        } else {
            if ($op == "show") {
                include $this->template('item');
                exit();
            } else {
                header('content-type:image/jpeg');
                $im = imagecreatetruecolor(640, 426);
                $bg = imagecreatefromjpeg('../addons/lwx_love/template/mobile/img/hf.jpg');
                imagecopy($im, $bg, 0, 0, 0, 0, 640, 426);
                imagedestroy($bg);
                $black = imagecolorallocate($im, 220, 220, 220);
                $name  = mb_convert_encoding($name, 'html-entities', 'UTF-8');
                if ($id == '1') {
                    $ida = "嫁给我吧!";
                } else if ($id == '2') {
                    $ida = "来娶我吧!";
                } else if ($id == '3') {
                    $ida = "我爱你!";
                } else if ($id == '4') {
                    $ida = "我喜欢你!";
                } else if ($id == '5') {
                    $ida = "分手快乐!";
                } else {
                    echo '条件不满足';
                }
                $ida  = mb_convert_encoding($ida, 'html-entities', 'UTF-8');
                $text = $name . " , " . $ida;
                $font = '../addons/lwx_love/template/mobile/css/boyan.ttf';
                imagettftext($im, 32, 0, 122, 225, $black, $font, $text);
                imagejpeg($im);
                imagedestroy($im);
            }
        }
    }
    public function doMobilezj()
    {
        global $_GPC, $_W;
        $name = $_GPC['name'];
        $do   = "zj";
        $op   = $_GPC['op'];
        if (empty($name)) {
            include $this->template('zj');
        } else {
            if ($op == "show") {
                include $this->template('item');
                exit();
            } else {
                header('content-type:image/jpeg');
                $im = imagecreatetruecolor(640, 734);
                $bg = imagecreatefromjpeg('../addons/lwx_love/template/mobile/img/zj.jpg');
                imagecopy($im, $bg, 0, 0, 0, 0, 640, 734);
                imagedestroy($bg);
                $black     = imagecolorallocate($im, 30, 30, 30);
                $font_file = '../addons/lwx_love/template/mobile/css/boyan.ttf';
                $name      = mb_convert_encoding($name, 'html-entities', 'UTF-8');
                $txt       = $name . ":";
                imagettftext($im, 23, 0, 82, 230, $black, $font_file, $txt);
                imagejpeg($im);
                imagedestroy($im);
            }
        }
    }
}
