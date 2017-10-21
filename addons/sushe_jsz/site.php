<?php
defined('IN_IA') or exit('Access Denied');
class sushe_jszModuleSite extends WeModuleSite
{
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        include $this->template('index');
    }
    public function doMobilePic()
    {
        global $_GPC, $_W;
        $name    = $_GPC['name'];
        $ferrari = pdo_fetchall("select * from " . tablename('sushe_jsz') . ' where uniacid = ' . $_W['uniacid']);
        if ($ferrari) {
            for ($i = 0; $i < count($ferrari); $i++) {
                $ferrari[$i]['pic'] = $_W['attachurl'] . $ferrari[$i]['pic'];
            }
        }
        if (!$ferrari) {
            $ferrari = array(
              //  array(
                //    'pic' => MODULE_URL . '/template/mobile/01.jpg'
                //),
                //array(
                  //  'pic' => MODULE_URL . '/template/mobile/02.jpg'
                //),
                
            );
        }
        include $this->template('pic');
    }
    public function doMobileTupian()
    {
        global $_GPC;
        $name = $_GPC['name'];
        $this->pic($name);
    }
    public function pic($name)
    {
        $background_pic_path = MODULE_ROOT . '/template/mobile/tupian.png';
        $im                  = imagecreatefrompng($background_pic_path);
        $font                = MODULE_ROOT . '/template/fonts/song.TTF';
        $timefont            = MODULE_ROOT . '/template/fonts/song.TTF';
        $color               = imagecolorallocate($im, 20, 24, 42);
      //  imagefttext($im, 18, 0, 176, 910, $color, $font, '本人已确认以上配置');
        if (mb_strlen($name, 'utf-8') == 2) {
            imagefttext($im, 12, 0, 180, 243, $color, $font, $name);
            imagefttext($im, 12, 0, 206, 364, $color, $timefont, date('Y-m-d'));
        } else if (mb_strlen($name, 'utf-8') == 3) {
            imagefttext($im, 12, 0, 180, 243, $color, $font, $name);
            imagefttext($im, 12, 0, 206, 364, $color, $timefont, date('Y-m-d'));
        } else if (mb_strlen($name, 'utf-8') == 4) {
            imagefttext($im, 12, 0, 180, 243, $color, $font, $name);
            imagefttext($im, 12, 0, 206, 364, $color, $timefont, date('Y-m-d'));
        } else if (mb_strlen($name, 'utf-8') > 4) {
            $name = '名字过长';
            imagefttext($im, 12, 0, 180, 243, $color, $font, $name);
            imagefttext($im, 12, 0, 206, 364, $color, $timefont, date('Y-m-d'));
        }
        header('Content-type: image/png');
        $result = imagepng($im);
        imagedestroy($im);
    }
    public function doWebCheck()
    {
        $gd    = function_exists('imagefttext');
        $font1 = file_exists(MODULE_ROOT . '/template/fonts/msyh.ttf');
        $font2 = file_exists(MODULE_ROOT . '/template/fonts/msyh.ttf');
        if ($gd && $font1 && $font2) {
            message('你的环境支持运行[飞机驾照装逼神器]');
        } else {
            if (!$gd)
                error('你的环境不支持运行，未安装gd库，自行百度 PHP中开启GD库支持 或 联系作者!');
            if (!$font1 || !$font2)
                error('你的环境不支持运行，字体不全, 打开 http://pan.baidu.com/s/1i4fR8TR 下载字体后解压覆盖到 addons/sushe_jsz/template/fonts 或 联系作者!');
        }
    }
    public function doWebSet()
    {
        global $_GPC, $_W;
        $op = $_GPC['op'];
        if (!$op || $op == 'list') {
            $pagesize  = 10;
            $pageindex = max(intval($_GPC['page']), 1);
            $where     = ' WHERE uniacid=:uniacid';
            $params    = array(
                ':uniacid' => $_W['uniacid']
            );
            $sql       = 'SELECT COUNT(*) FROM ' . tablename('sushe_jsz') . " {$where}";
            $total     = pdo_fetchcolumn($sql, $params);
            $pager     = pagination($total, $pageindex, $pagesize);
            $sql       = 'SELECT * FROM ' . tablename('sushe_jsz') . " {$where} ORDER BY sort desc LIMIT " . (($pageindex - 1) * $pagesize) . ',' . $pagesize;
            $data      = pdo_fetchall($sql, $params);
        }
        if ($op == 'add') {
            if (checksubmit('submit')) {
                $count = pdo_fetchcolumn("select count(*) from " . tablename('sushe_jsz') . ' where `uniacid`=:uniacid', array(
                    ':uniacid' => $_W['uniacid']
                ));
                if ($count <= 5) {
                    $data = array(
                        'pic' => $_GPC['pic'],
                        'sort' => $_GPC['sort'],
                        'create_time' => TIMESTAMP,
                        'update_time' => TIMESTAMP
                    );
                    unset($data['id']);
                    $data   = array_merge($data, array(
                        'uniacid' => $_W['uniacid']
                    ));
                    $result = pdo_insert('sushe_jsz', $data);
                    !$result ? error('修改出错/或者未修改数据') : message('添加成功', '', 'success');
                } else {
                    error('展示图最多添加5张');
                }
            }
        }
        if ($op == 'modify') {
            $info = pdo_fetch('select * from ' . tablename('sushe_jsz') . ' where `id`=:id', array(
                ':id' => $_GPC['id']
            ));
            if (checksubmit('submit')) {
                if ($info) {
                    $data   = array(
                        'pic' => $_GPC['pic'],
                        'sort' => $_GPC['sort'],
                        'update_time' => TIMESTAMP
                    );
                    $where  = array(
                        'id' => $_GPC['id']
                    );
                    $result = pdo_update('sushe_jsz', $data, $where);
                    !$result ? error('修改出错/或者未修改数据') : message('修改成功', '', 'success');
                } else {
                    error('你修改的展示图不存在哦');
                }
            }
        }
        if ($op == 'del') {
            $info = pdo_fetch('select id from ' . tablename('sushe_jsz') . ' where `id`=:id', array(
                ':id' => $_GPC['id']
            ));
            if ($info) {
                $where  = array(
                    'id' => $_GPC['id']
                );
                $result = pdo_delete('sushe_jsz', $where);
                !$result ? error('删除出错') : message('删除成功', '', 'success');
            } else {
                error('你删除的展示图不存在哦');
            }
        }
        include $this->template('set');
    }
    public function doWebIndex()
    {
        $url = "http://" . $_SERVER['HTTP_HOST'] . '/app' . ltrim($this->createMobileUrl('index'), '.');
        include $this->template('index');
    }
}