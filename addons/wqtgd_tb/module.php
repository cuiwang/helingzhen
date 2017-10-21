<?php

/**
 * 淘宝红包返现模块定义
 *
 * @author 3354988381
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wqtgd_tbModule extends WeModule {
    private function randomFloat($min = 0, $max = 1) {
        $price = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return round($price, 2);
    }

    public function settingsDisplay($settings) {
        global $_W, $_GPC;
        $id = $_W['uniacid'];
        //点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
        //在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
        if (checksubmit()) {
            //证书上传 require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
            if (!empty($_FILES['fhbpaycert']['tmp_name'])) {
                load()->func('file');
                require_once IA_ROOT . '/addons/wqtgd_tb/lib/PHPZIP.php';
                $ext = pathinfo($_FILES['fhbpaycert']['name'], PATHINFO_EXTENSION);
                if (strtolower($ext) != "zip") {
                    message("[文件格式错误]请上传您的微信支付证书哦~", referer(), 'error');
                }

                $wxcertdir = IA_ROOT . "/web/{$id}";
                if (!is_dir($wxcertdir)) {
                    mkdir($wxcertdir);
                }
                if (is_dir($wxcertdir)) {
                    if (!is_writable($wxcertdir)) {
                        message("请保证目录：[" . $wxcertdir . "]可写");
                    }
                }
                $save_file = $wxcertdir . "/" . $id . "." . $ext;
                file_move($_FILES['fhbpaycert']['tmp_name'], $save_file);
                $archive = new PHPZIP();
                $archive->unzip($save_file, $wxcertdir); // 把zip中的文件解压到目录中
                file_delete($save_file);
                unlink($save_file);
            }
            $dat = array(
                'fhb_mchid' => $_GPC['fhb_mchid'],
                'fhb_appid' => $_GPC['fhb_appid'],
                'fhb_secret' => $_GPC['fhb_secret'],
                'fhb_send_name' => $_GPC['fhb_send_name'],
                'fhb_nick_name' => $_GPC['fhb_nick_name'],
                'fhb_wishing' => $_GPC['fhb_wishing'],
                'fhb_remark' => $_GPC['fhb_remark'],
                'fhb_act_name' => $_GPC['fhb_act_name'],
                'fhb_send_type' => $_GPC['fhb_send_type'],
                'fhb_total_num' => 1,
                'fhb_send_key' => $_GPC['fhb_send_key'],
                'fhb_user_follow_award' => $_GPC['fhb_user_follow_award'] ? $_GPC['fhb_user_follow_award'] : 'N',
                'openguanzhu' => $_GPC['openguanzhu'] ? $_GPC['openguanzhu'] : 'N',
                'openguanzhu_url' => $_GPC['openguanzhu_url'],
                'start_time' => $_GPC['datelimit']['start'],
                'end_time' => $_GPC['datelimit']['end'],
                //'award_type' => $_GPC['award_type'],
                //'award_type_val' => $_GPC['award_type_val'],
                'share_title' => $_GPC['share_title'],
                'share_photo' => $_GPC['share_photo'],
                'share_desc' => $_GPC['share_desc'],
                'index_title' => $_GPC['index_title'],
                'images_sl' => $_GPC['images_sl'],
            );
            $sql = "select * from " . tablename('wqtgd_fanxi1'). ' limit 1'; //执行的SQL语句
            $band = pdo_fetch($sql); //返回的信息|$res以数组形式包含所以字段
            if (empty($band)) {
                $res = pdo_insert('wqtgd_fanxi1', array('mode' => 0)); //数据库插
            }

            $integral = $_GPC['integral']; //积分
            $balance = $_GPC['balance']; //余额
            $mode = $_GPC['mode']; //奖励方式 【0:积分,1:余额,2:红包】
            if (isset($_GPC['upload'])) {
                $upload = 0; //页面上传好评图片显示设置
            } else {
                $upload = 1;
            }
            if (isset($_GPC['examine'])) {
                $examine = 0;
            } else {
                $examine = 1; //奖励是否需要审核设置
            }
            $dataArr = array(//red frequency altogether share
                'upload' => $upload,//0选1否
                'examine' => $examine,//0选1否
                'integral' => $integral,
                'balance' => $balance,
                'mode' => $mode
            ); //更新内容
            $res = pdo_update('wqtgd_fanxi1', $dataArr); //执行更新，返回更新多少条
            
            $fhb_send_type = $_GPC['fhb_send_type'];
            if ($fhb_send_type == 'f') {//固定金额
                $dat['fhb_send_money'] = round($_GPC['fhb_send_money'], 2) * 100;
                $dat['fhb_min_value'] = $dat['fhb_max_value'] = $dat['fhb_total_amount'] = $_GPC['fhb_send_money'];
            } else {
                $dat['fhb_send_money_from'] = $_GPC['fhb_send_money_from'];
                $dat['fhb_send_money_to'] = $_GPC['fhb_send_money_to'];
                $random_money = $this->randomFloat($dat['fhb_send_money_from'], $dat['fhb_send_money_to']) * 100;
                $dat['fhb_min_value'] = $dat['fhb_max_value'] = $dat['fhb_total_amount'] = $random_money;
            }
            $this->saveSettings($dat);
            message('配置参数更新成功！', referer(), 'success');
        } else {
            $fhb_send_type = $this->module['config']['fhb_send_type'];
            $fhb_send_type = $fhb_send_type ? $fhb_send_type : 'f';
            //是否上传过证书
            $wxcertdir = IA_ROOT . "/web/{$id}/apiclient_cert.pem";
            $wxcertdir_flag = file_exists($wxcertdir);
        }
        load()->func('tpl');
        if (!$settings['start_time']) {
            $settings['start_time'] = time();
            $settings['end_time'] = time() + 3600 * 24 * 30;
        } else {
            $settings['start_time'] = strtotime($settings['start_time']);
            $settings['end_time'] = strtotime($settings['end_time']);
        }
        $sql = "select * from " . tablename('wqtgd_fanxi1'); //奖励设置
        $fanxi = pdo_fetchall($sql);
        $mode = $fanxi[0]['mode']; //奖励方式 0/1/2 积分 余额 红包
        $upload_a = $fanxi[0]['upload']; //页面上传好评图片显示设置
        $examine_a = $fanxi[0]['examine']; //奖励是否需要审核设置
        //这里来展示设置项表单
        include $this->template('setting');
    }

}
