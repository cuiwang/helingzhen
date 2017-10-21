<?php
/**
 * 通用微信赞赏模块定义
 *
 * @url #
 */
defined('IN_IA') or exit('Access Denied');

class Fr_dsModule extends WeModule {

    public function settingsDisplay($settings) {
        global $_W, $_GPC;
        include MODULE_ROOT . '/inc/common.php';
        //点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
        //在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
        if (checksubmit()) {
            $config = $_GPC['config'];
            
            $allow_fields = array(
                "appreciate"
            );
            $dat = array_elements($allow_fields, $config);

            if (empty($dat['appreciate'])) {
                $dat['appreciate'] = array(
                    "min" => 1,
                    "max" => 256,
                    "quick" => "1,5,10,20,42,64",
                );
            } else {
                $dat['appreciate']['min'] = intval($dat['appreciate']['min']);
                if ($dat['appreciate']['min'] < 1) {
                    $dat['appreciate']['min'] = 1;
                }
                $dat['appreciate']['max'] = intval($dat['appreciate']['max']);
                if ($dat['appreciate']['max'] < $dat['appreciate']['min']) {
                    $dat['appreciate']['max'] = $dat['appreciate']['min'] + 1;
                }

                $quick = array_map(function($money) {
                    return intval($money);
                }, explode(",", $dat['appreciate']['quick']));
                $quick = array_filter(array_unique($quick));
                if (empty($quick)) {
                    $dat['appreciate']['quick'] = "1,5,10,20,42,64";
                } else {
                    asort($quick);
                    $dat['appreciate']['quick'] = implode(",", $quick);
                }
            }
            

            //字段验证, 并获得正确的数据$dat
            if ($this->saveSettings($dat)) {
                message('保存配置成功', 'refresh');
            }
        }
        if (empty($settings['appreciate'])) {
            $settings['appreciate'] = array(
                "min" => 1,
                "max" => 256,
                "quick" => "1,5,10,20,42,64",
            );
        }
        //这里来展示设置项表单
        include $this->template('setting');
    }

}