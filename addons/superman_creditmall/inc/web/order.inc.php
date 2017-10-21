<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebOrder extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '订单管理';
        $act = in_array($_GPC['act'], array('display', 'post', 'delete', 'export'))?$_GPC['act']:'display';
        $order_status = superman_order_status();
        if ($act == 'display') {
            $setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
            $balance_credit_type = $setting&&isset($setting['creditbehaviors']['currency'])?$setting['creditbehaviors']['currency']:'credit2';
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;
            $status = in_array($_GPC['status'], array('-2', '-1', '0', '1', '2', '3', '4', 'all'))?$_GPC['status']:'all';
            $ordersn = $_GPC['ordersn']==''?'':$_GPC['ordersn'];
            $product_title = $_GPC['product_title']==''?'':$_GPC['product_title'];
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            if ($status != 'all') {
                $filter['status'] = $status;
            }
            if ($ordersn) {
                $filter['ordersn'] = $ordersn;
            }
            if (!empty($product_title)) {
                $rows = superman_product_fetchall_by_title($product_title);
                if ($rows) {
                    foreach ($rows as $row) {
                        $filter['product_id'][] = $row['id'];
                    }
                }
            }
            $total = superman_order_count($filter);
            $list = array();
            if ($total > 0) {
                if (isset($_GPC['export'])) {
                    $pagesize = -1;
                }
                $list = superman_order_fetchall($filter, 'ORDER BY id DESC', $start, $pagesize);
                if ($list) {
                    foreach ($list as &$item) {
                        $item['member'] = mc_fetch($item['uid'],array('nickname','avatar'));
                        $product = superman_product_fetch($item['product_id']);
                        $item['title'] = $product?$product['title']:'[商品已删除]';
                        if (!isset($_GPC['export']) && !$product) {
                            $item['title'] = '<span class="label label-danger">'.$item['title'].'</span>';
                        }
                        superman_order_set($item);
                    }
                    unset($item);
                }
                $pager = pagination($total, $pindex, $pagesize);
            }
            if (isset($_GPC['export'])) {
                $this->export_order($list);
            }
        } else if ($act == 'post') {
            $id = intval($_GPC['id']);
            $order = superman_order_fetch($id);
            if (!$order) {
                message('订单不存在',referer(),'error');
            }
            superman_order_set($order);
            $product = superman_product_fetch($order['product_id'], 'title');
            $order['product']['title'] = $product?$product['title']:'<span class="label label-danger">[商品已删除]</span>';
            $order['member'] = mc_fetch($order['uid'], array('nickname', 'avatar'));
            $order['fans'] = mc_fansinfo($order['uid']);

            //查询发放结果
            if (checksubmit('search_result')) {
                if (!strstr($order['extend']['redpack_result'], 'SYSTEMERROR')) {
                    message('该订单不需要查询发放结果', referer(), 'error');
                }
                $ret = $this->checkRedpack($id);
                $new_data = array();
                $new_data['extend'] = $order['extend'];
                $new_data['extend']['redpack_result'] = $ret;
                $new_data['extend'] = iserializer($new_data['extend']);
                if (is_array($ret) && $ret['success'] == true) {
                    //已发送成功，更改订单状态
                    $new_data['status'] = 2;
                }
                $re = M::t('superman_creditmall_order')->update($new_data, array('id' => $id));
                message('查询成功，请返回查看结果', referer(), 'success');
            }

            //重新发送红包
            if (checksubmit('resend_redpack')) {
                if (strstr($order['extend']['redpack_result'], 'success')) {
                    message('红包已成功发放，不需要再次发放', referer(), 'error');
                }
                if ($order['status'] != 0) {
                    message('订单状态有误，无法发放红包', referer(), 'error');
                }
                //检查积分余额是否足够
                $user_credits = mc_credit_fetch($order['uid']);
                if (!$user_credits) {
                    message('该用户不存在', referer(), 'error');
                }
                if ($user_credits[$order['credit_type']] < $order['credit']) {
                    message('该用户积分不足，无法重新发送', referer(), 'error');
                }
                $setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
                $payment = array();
                if ($setting && isset($setting['payment']) && is_array($setting['payment'])) {
                    $payment = $setting['payment'];
                }
                if ($order['price'] > 0) {
                    if (!$payment['credit']['switch']) {
                        message('未开启用于支付现金的积分类型', referer(), 'error');
                    }
                    if ($user_credits[$setting['creditbehaviors']['currency']] < $order['price']) {
                        message('该用户余额不足，无法重新发送', referer(), 'error');
                    }
                }
                $credit_title = superman_credit_type($order['credit_type']);
                $redpack = array(
                    'amount' => $order['extend']['redpack_amount'],
                    'wishing' => superman_redpack_wishing(),
                    'act_name' => $credit_title.'兑换红包',
                );
                $openid = isset($order['extend']['oauth_openid']) && $order['extend']['oauth_openid']?$order['extend']['oauth_openid']:$order['fans']['openid'];
                if (empty($openid)) {
                    WeUtility::logging('trace', 'not found openid, extend='.var_export($order['extend'], true).', fans='.var_export($order['fans'], true));
                }
                $ret = $this->sendRedpack($this->module['config']['redpack']['oauth_uniacid'], $openid, $redpack, $order);
                $new_data = array();
                $new_data['extend'] = $order['extend'];
                $new_data['extend']['redpack_result'] = $ret;
                $new_data['extend'] = iserializer($new_data['extend']);
                $new_data['updatetime'] = TIMESTAMP;
                if (is_array($ret) && $ret['success'] == true) {
                    $new_data['status'] = 2;
                    $new_data['pay_type'] = 1;
                    $new_data['pay_time'] = TIMESTAMP;
                }
                //更新订单状态
                M::t('superman_creditmall_order')->update($new_data, array('id' => $id));
                if (is_array($ret) && $ret['success'] == true) {    //红包发送成功
                    $credit_log = array(
                        $order['uid'],
                        '兑换'.$product['title'],
                        'superman_creditmall',
                    );
                    //扣减积分
                    if ($order['pay_credit'] == 0) {
                        $fee = floatval($order['credit']);
                        $result = mc_credit_update($order['uid'], $order['credit_type'], -$fee, $credit_log);
                        if (is_error($result)) {
                            WeUtility::logging('trace', '红包发送成功，但积分扣除失败，订单号：'.$order['ordersn'].'，错误信息：'.$result['message']);
                            message('红包发送成功，但积分扣减失败，错误信息：'.$result['message'], referer(), 'error');
                        }
                        M::t('superman_creditmall_order')->update(array('pay_credit' => 1), array('id' => $order['id']));
                    }
                    //扣减余额
                    if ($order['price'] > 0 && $order['pay_price'] == 0) {
                        $fee = floatval($order['price']);
                        $result = mc_credit_update($order['uid'], $setting['creditbehaviors']['currency'], -$fee, $credit_log);
                        if (is_error($result)) {
                            WeUtility::logging('trace', '红包发送成功，但余额扣除失败，订单号：'.$order['ordersn'].'，错误信息：'.$result['message']);
                            message('红包发送成功，但余额扣减失败，错误信息：'.$result['message'], referer(), 'error');
                        }
                        M::t('superman_creditmall_order')->update(array('pay_price' => 1), array('id' => $order['id']));
                    }
                    //更新系统支付日志表
                    $condition = array(
                        'uniacid' => $_W['uniacid'],
                        'module' => $this->module['name'],
                        'tid' => $order['id'],
                    );
                    M::t('core_paylog')->update(array('status' => 1), $condition);
                }
                message('操作成功，请返回查看发放结果', referer(), 'success');
            }

            //更改订单状态
            if (checksubmit('submit')) {
                //退钱条件：取消订单 && 已支付
                if ($_GPC['return_credit'] == 1 && $_GPC['status'] == '-1' && $order['status'] > 0) {
                    $ret = $this->returnCredit($order['uniacid'], $order, "取消订单({$order['ordersn']})");
                    if ($ret !== true) {
                        message("取消订单({$order['ordersn']})退积分失败！", '', 'error');
                    }
                }
                $data = array(
                    'express_no' => $_GPC['express_no'],
                    'remark' => trim($_GPC['remark']),
                    'status' => $_GPC['status'],
                );
                if ($_GPC['virtual_key']) {
                    $data['extend'] = $order['extend'];
                    $data['extend']['virtual_result']['key'] = $_GPC['virtual_key'];
                    $data['extend'] = iserializer($data['extend']);
                }
                pdo_update('superman_creditmall_order', $data, array('id' => $id));

                //修改为已发货状态 发送客服消息
                if ($_GPC['status'] == 2 && !isset($order['extend']['virtual_result'])) {
                    $order_url = $_W['siteroot'].'app/'.$this->createMobileUrl('order', array('act' => 'detail', 'orderid' => $id));
                    if ($_W['account']['level'] == 4) { //已认证服务号
                        if ($this->module['config']['template_message']['order_send_id']
                            && $this->module['config']['template_message']['order_send_content']) {
                            $vars = array(
                                '{商品信息}'   => $product['title']."(x{$order['total']}) 订单号：{$order['ordersn']}",
                                '{快递公司}'   => $order['express_title'],
                                '{快递单号}'   => $data['express_no'],
                                '{收货信息}'  => "{$order['username']} {$order['mobile']} {$order['address']}",
                                '{操作时间}'  => date('Y-m-d H:i:s', TIMESTAMP),
                            );
                            $message = array(
                                'uniacid' => $_W['uniacid'],
                                'template_id' => $this->module['config']['template_message']['order_send_id'],
                                'template_variable' => $this->module['config']['template_message']['order_send_content'],
                                'vars' => $vars,
                                'receiver_uid' => $order['uid'],
                                'url' => $order_url,
                            );
                            $this->sendTemplateMessage($message);
                        } else {
                            $this->sendCustomerStatusNotice($order['uid'], $order['ordersn'], $_GPC['status'], $order_url);
                        }
                    } else if ($_W['account']['level'] == 3) { //已认证订阅号
                        $this->sendCustomerStatusNotice($order['uid'], $order['ordersn'], $_GPC['status'], $order_url);
                    }
                }
                message('修改成功！', $this->createWebUrl('order'), 'success');
            }
        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            $ret = pdo_delete('superman_creditmall_order', array('id' => $id));
            if ($ret) {
                message('删除成功！', referer(), 'success');
            }
            message('删除失败！', referer(), 'error');
        }
        include $this->template('web/order');
    }

    private function export_order($list) {
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Writer/Excel5.php';
        $resultPHPExcel = new PHPExcel();
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );
        $style_fill = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => '0xFFFF00')
            ),
        );
        $resultPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray(($styleArray + $style_fill));
        $resultPHPExcel->getActiveSheet()->setCellValue('A1', '订单号');
        $resultPHPExcel->getActiveSheet()->setCellValue('B1', '商品名');
        $resultPHPExcel->getActiveSheet()->setCellValue('C1', '件数');
        $resultPHPExcel->getActiveSheet()->setCellValue('D1', '姓名');
        $resultPHPExcel->getActiveSheet()->setCellValue('E1', '地址');
        $resultPHPExcel->getActiveSheet()->setCellValue('F1', '电话');
        $resultPHPExcel->getActiveSheet()->setCellValue('G1', '配送方式');
        $resultPHPExcel->getActiveSheet()->setCellValue('H1', 'UID');
        $resultPHPExcel->getActiveSheet()->setCellValue('I1', '昵称');
        $resultPHPExcel->getActiveSheet()->setCellValue('J1', '创建时间');
        $resultPHPExcel->getActiveSheet()->setCellValue('K1', '状态');
        $resultPHPExcel->getActiveSheet()->setCellValue('L1', '留言');
        $resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $i = 2;
        foreach ($list as $item) {
            $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $item['ordersn']);
            $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item['title']);
            $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['total']);
            $resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $item['username']);
            $resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item['address']);
            $resultPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item['mobile']);
            $resultPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item['express_title'].'（快递费：'.$item['express_fee']);
            $resultPHPExcel->getActiveSheet()->setCellValue('H' . $i, $item['uid']);
            $resultPHPExcel->getActiveSheet()->setCellValue('I' . $i, $item['member']['nickname']);
            $resultPHPExcel->getActiveSheet()->setCellValue('J' . $i, $item['dateline']);
            $status_title = superman_order_status($item['status']);
            $resultPHPExcel->getActiveSheet()->setCellValue('K' . $i, $status_title);
            $resultPHPExcel->getActiveSheet()->setCellValue('L' . $i, $item['remark']);
            $resultPHPExcel->getActiveSheet()->getStyle('A' . $i . ':L' . $i)->applyFromArray($styleArray);
            $i++;
        }
        $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, '导出订单数：' . count($list));
        $resultPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray(array('font' => array('bold' => true)));

        $outputFileName = 'data'.date('YmdHi').'.xls';
        $xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outputFileName . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save("php://output");
        exit;
    }
}

$obj = new Creditmall_doWebOrder;
$obj->exec();
