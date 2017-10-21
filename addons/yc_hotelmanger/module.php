<?php

defined("IN_IA") || exit("Access Denied");

class Yc_hotelmangerModule extends WeModule {
    public $menu='';
    public function fieldsFormDisplay($rid = 0) {
        
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        $menu=pdo_fetchall('SELECT * FROM ' . tablename('modules_bindings') . ' where entry = :menu and  module = :module  order by eid ',array(':menu'=>'menu',':module'=>'yc_hotelmanger'));
        $this->menu=$menu;
        if (checksubmit()) {
            $cfg = array(
                'toplogo' => $_GPC['toplogo'],
                'hotelname' => $_GPC['hotelname'],
                'music' => $_GPC['music'],
                'hotelsname' => $_GPC['hotelsname'], 
                'indeximg' => $_GPC['indeximg'], 
                'shopname' => $_GPC['shopname'],
                'shoplink' => $_GPC['shoplink'],
                'shoplogo' => $_GPC['shoplogo'],
                'yhname' => $_GPC['yhname'],
                'yhlink' => $_GPC['yhlink'],
                'yhlogo' => $_GPC['yhlogo'],
                'shoptel' => $_GPC['shoptel'],
                'topimg1' => $_GPC['topimg1'],
                'topurl1' => $_GPC['topurl1'],
                'topimg2' => $_GPC['topimg2'],
                'topurl2' => $_GPC['topurl2'],
                'copyright' => $_GPC['copyright'], 
                'sharetitle' => $_GPC['sharetitle'],
                'sharedesp' => $_GPC['sharedesp'],
                'shareimg' => $_GPC['shareimg'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }

    public function fieldsFormSubmit($rid) {
        //规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
       
    }
      public function welcomeDisplay() {
        $menu=pdo_fetchall('SELECT * FROM ' . tablename('modules_bindings') . ' where entry = :menu and  module = :module order by eid ',array(':menu'=>'menu',':module'=>'yc_hotelmanger'));
        header("location:".$_W["siteroot"].url("site/entry/eid",array('eid'=>$menu[0]['eid'])));
        exit();
    }

}

?>