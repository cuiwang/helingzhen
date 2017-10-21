<?php
/**
 * 俄罗斯方块模块定义
 *
 * @author 800083075
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class wdl_cubeModule extends WeModule {


    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        $data = $_GPC['data'];
        // 点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
        // 在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
        if (checksubmit()) {
            // 字段验证, 并获得正确的数据$dat
            $flag = $this->saveSettings($data);
            if ($flag) {
                message("信息保存成功", "", "success");
            } else {
                message("信息保存失败", "", "error");
            }
        }
        // 这里来展示设置项表单
        load()->func('tpl');
        include $this->template('setting');
        echo 'nihao';
    }
}