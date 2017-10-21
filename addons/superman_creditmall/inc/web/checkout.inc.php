<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebCheckout extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }
    public function exec() {
        global $_W, $_GPC;
        $title = '线下核销';
        $act = in_array($_GPC['act'], array('display', 'qrcode', 'oneself'))?$_GPC['act']:'display';
        if ($act == 'display') {
            //核销记录
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = isset($_GPC['export'])?-1:20;
            $start = ($pindex - 1) * $pagesize;
            $starttime = $_GPC['dateline']['start'] ? strtotime($_GPC['dateline']['start']) : strtotime('-1week');
            $endtime = $_GPC['dateline']['end'] ? strtotime($_GPC['dateline']['end'])+86399 : strtotime(date('Y-m-d 23:59:59'));
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            //筛选
            $filter['dateline#1'] = '#>='.$starttime;
            $filter['dateline#2'] = '#<='.$endtime;

            if (isset($_GPC['ordersn']) && $_GPC['ordersn']) {
                $filter['ordersn'] = '# LIKE "%'.$_GPC['ordersn'].'%"';
            }
            $orderby = ' ORDER BY dateline DESC';
            $list = M::t('superman_creditmall_checkout_log')->fetchall($filter, $orderby, $start, $pagesize);
            if ($list) {
                foreach ($list as &$li) {
                    $product_id = pdo_fetchcolumn("SELECT product_id FROM ".tablename('superman_creditmall_order')." WHERE id=:id", array(
                        'id' => $li['orderid'],
                    ));
                    $li['product'] = M::t('superman_creditmall_product')->fetch($product_id);
                    $li['member'] = mc_fetch($li['uid'], array('avatar', 'nickname'));
                    $li['dateline'] = date('Y-m-d H:i:s', $li['dateline']);
                    if ($li['type'] == 1) {
                        $li['type_title'] = '扫码核销';
                        $li['user'] = mc_fetch($li['checkout'], array('nickname', 'avatar'));
                        $remark = pdo_fetchcolumn("SELECT remark FROM ".tablename('superman_creditmall_checkout_user')." WHERE uid=:uid", array(
                            'uid' => $li['checkout'],
                        ));
                    } else {
                        $li['type_title'] = '自助核销';
                        $li['code'] = $li['checkout'];
                        $remark = pdo_fetchcolumn("SELECT remark FROM ".tablename('superman_creditmall_checkout_code')." WHERE code=:code", array(
                            'code' => $li['checkout'],
                        ));
                    }
                    $li['checkout_remark'] = $remark;
                    unset($li, $checkout);
                }
            }
            $total = M::t('superman_creditmall_checkout_log')->count($filter);
            $pager = pagination($total, $pindex, $pagesize);
            //导出
            if (isset($_GPC['export']) && $_GPC['export'] == 'yes') {
                $this->export_checkout_log($list);
            }
        } else if ($act == 'qrcode') {
            //扫码核销
            if ($_GPC['op'] == 'post') {
                //添加，编辑核销员
                $id = intval($_GPC['id']);
                if ($id > 0) {
                    $row = superman_checkout_user_fetch($id);
                    if (!$row) {
                        $id = 0;
                    }
                }
                if (checksubmit('submit')) {
                    $openid = $_GPC['openid'];
                    $remark = $_GPC['remark'];
                    //查重
                    $filter = array(
                        'openid' => $openid
                    );
                    if ($openid) {
                        $member = mc_fansinfo($openid);
                    }
                    if (isset($member['follow']) && $member['follow'] == 1) {
                        $data = array(
                            'uid' => $member['uid'],
                            'openid' => $openid,
                            'remark' => $remark,
                        );
                        if ($id > 0) {
                            $ret = pdo_update('superman_creditmall_checkout_user', $data, array('id' => $id));
                        } else {
                            $count = superman_checkout_user_count($filter);
                            if ($count > 0) {
                                message('该用户已添加为核销员，请勿重复添加', referer(), 'info');
                            }
                            $data['uniacid'] = $_W['uniacid'];
                            $data['dateline'] = TIMESTAMP;
                            pdo_insert('superman_creditmall_checkout_user', $data);
                            $new_id = pdo_insertid();
                            if ($new_id) {
                                $ret = true;
                            } else {
                                $ret = false;
                            }
                        }
                        if ($ret !== false) {
                            message('操作成功！', $this->createWebUrl('checkout', array('act' => 'qrcode')), 'success');
                        } else {
                            message('数据库出错，请稍后重试', referer(), 'error');
                        }
                    } else {
                        message('粉丝编号输入有误，请重新输入', referer(), 'error');
                    }
                }
            } else if ($_GPC['op'] == 'delete') {
                //删除核销员
                $id = intval($_GPC['id']);
                if ($id > 0) {
                    $ret = pdo_delete('superman_creditmall_checkout_user', array('id' => $id));
                    if ($ret !== false) {
                        message('删除成功！', referer(), 'success');
                    } else {
                        message('删除失败！请返回重试', referer(), 'error');
                    }
                } else {
                    message('该核销员不存在或已删除');
                }
            } else {
                //更新排序
                if(checksubmit('orderby_submit')) {
                    $displayorder = $_GPC['displayorder'];
                    if ($displayorder) {
                        foreach ($displayorder as $id=>$val) {
                            pdo_update('superman_creditmall_checkout_user', array('displayorder' => $val), array('id' => $id));
                        }
                        message('操作成功！', referer(), 'success');
                    }
                }

                //核销员列表页
                $pindex = max(1, intval($_GPC['page']));
                $pagesize = isset($_GPC['export'])?-1:20;
                $start = ($pindex - 1) * $pagesize;
                $filter = array(
                    'uniacid' => $_W['uniacid']
                );
                $orderby = ' ORDER BY displayorder DESC, id ASC';
                $list = superman_checkout_user_fetchall($filter, $orderby, $start, $pagesize);
                if ($list) {
                    foreach ($list as &$li) {
                        $member = mc_fetch($li['uid'], array('nickname', 'avatar'));
                        $li['nickname'] = $member['nickname'];
                        $li['avatar'] = $member['avatar'];
                        unset($li, $member);
                    }
                }
                $total = superman_checkout_user_count($filter);
                $pager = pagination($total, $pindex, $pagesize);
            }
        } else if ($act == 'oneself') {
            //自助核销
            if ($_GPC['op'] == 'post') {
                //添加，编辑验证码
                $id = intval($_GPC['id']);
                if ($id > 0) {
                    $row = superman_checkout_code_fetch($id);
                    if (!$row) {
                        $id = 0;
                    }
                }
                if (checksubmit('submit')) {
                    $title = trim($_GPC['title']);
                    $code = trim($_GPC['code']);
                    $remark = $_GPC['remark'];
                    if ($title != '' && $code != '') {
                        $data = array(
                            'title' => $title,
                            'code' => $code,
                            'remark' => $remark,
                        );
                        if ($id > 0) {
                            $ret = pdo_update('superman_creditmall_checkout_code', $data, array('id' => $id));
                        } else {
                            $data['uniacid'] = $_W['uniacid'];
                            $data['dateline'] = TIMESTAMP;
                            pdo_insert('superman_creditmall_checkout_code', $data);
                            $new_id = pdo_insertid();
                            if ($new_id) {
                                $ret = true;
                            } else {
                                $ret = false;
                            }
                        }
                        if ($ret !== false) {
                            message('操作成功！', $this->createWebUrl('checkout', array('act' => 'oneself')), 'success');
                        } else {
                            message('数据库出错，请稍后重试', referer(), 'error');
                        }
                    } else {
                        message('标题或验证码不能为空或0', referer(), 'error');
                    }
                }
            } else if ($_GPC['op'] == 'delete') {
                //删除验证码
                $id = intval($_GPC['id']);
                if ($id > 0) {
                    $ret = pdo_delete('superman_creditmall_checkout_code', array('id' => $id));
                    if ($ret !== false) {
                        message('删除成功！', referer(), 'success');
                    } else {
                        message('删除失败！请返回重试', referer(), 'error');
                    }
                } else {
                    message('该验证码不存在或已删除');
                }
            } else {
                //验证码列表页
                $pindex = max(1, intval($_GPC['page']));
                $pagesize = isset($_GPC['export'])?-1:20;
                $start = ($pindex - 1) * $pagesize;
                $filter = array(
                    'uniacid' => $_W['uniacid']
                );
                $orderby = ' ORDER BY dateline DESC';
                $list = superman_checkout_code_fetchall($filter, $orderby, $start, $pagesize);
                if ($list) {
                    foreach ($list as &$li) {
                        $li['dateline'] = date('Y-m-d H:i:s', $li['dateline']);
                        unset($li);
                    }
                }
                $total = superman_checkout_code_count($filter);
                $pager = pagination($total, $pindex, $pagesize);
            }
        }
        include $this->template('web/checkout');
    }

    private function export_checkout_log($list) {
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
        $resultPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(($styleArray + $style_fill));
        $resultPHPExcel->getActiveSheet()->setCellValue('A1', '订单号');
        $resultPHPExcel->getActiveSheet()->setCellValue('B1', '商品名称');
        $resultPHPExcel->getActiveSheet()->setCellValue('C1', '数量');
        $resultPHPExcel->getActiveSheet()->setCellValue('D1', '订单金额');
        $resultPHPExcel->getActiveSheet()->setCellValue('E1', '支付方式');

        $resultPHPExcel->getActiveSheet()->setCellValue('F1', '会员id');
        $resultPHPExcel->getActiveSheet()->setCellValue('G1', '昵称');
        $resultPHPExcel->getActiveSheet()->setCellValue('H1', '收货人');
        $resultPHPExcel->getActiveSheet()->setCellValue('I1', '手机');
        $resultPHPExcel->getActiveSheet()->setCellValue('J1', '收货地址');
        $resultPHPExcel->getActiveSheet()->setCellValue('K1', '订单留言');
        $resultPHPExcel->getActiveSheet()->setCellValue('L1', '下单时间');
        $resultPHPExcel->getActiveSheet()->setCellValue('M1', '快递公司');
        $resultPHPExcel->getActiveSheet()->setCellValue('N1', '快递单号');

        $resultPHPExcel->getActiveSheet()->setCellValue('O1', '核销方式');
        $resultPHPExcel->getActiveSheet()->setCellValue('P1', '核销员(备注)/核销码(备注)');
        $resultPHPExcel->getActiveSheet()->setCellValue('Q1', '核销备注');
        $resultPHPExcel->getActiveSheet()->setCellValue('R1', '核销时间');
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
        $resultPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $i = 2;
        foreach ($list as $li) {
            $order = M::t('superman_creditmall_order')->fetch($li['orderid']);
            superman_order_set($order);
            $product = M::t('superman_creditmall_product')->fetch($order['product_id']);

            $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $li['ordersn']);
            $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $product['title']);
            $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $order['total']);
            if ($order['price']>0) {
                $real_price = $order['credit'].$order['credit_title'].'+'.$order['price'].'元';
            } else {
                $real_price = $order['credit'] . $order['credit_title'];
            }
            $resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $real_price);
            $resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $order['pay_type_title']);

            $resultPHPExcel->getActiveSheet()->setCellValue('F' . $i, $li['uid']);
            $resultPHPExcel->getActiveSheet()->setCellValue('G' . $i, $li['member']['nickname']);
            $resultPHPExcel->getActiveSheet()->setCellValue('H' . $i, $order['username']);
            $resultPHPExcel->getActiveSheet()->setCellValue('I' . $i, $order['mobile']);
            $resultPHPExcel->getActiveSheet()->setCellValue('J' . $i, $order['address']);
            $resultPHPExcel->getActiveSheet()->setCellValue('K' . $i, $order['remark']);
            $resultPHPExcel->getActiveSheet()->setCellValue('L' . $i, $order['dateline']);
            $resultPHPExcel->getActiveSheet()->setCellValue('M' . $i, $order['express_title']);
            $resultPHPExcel->getActiveSheet()->setCellValue('N' . $i, $order['express_no']);
            $resultPHPExcel->getActiveSheet()->setCellValue('O' . $i, $li['type_title']);
            if ($li['type'] == 1) {
                $resultPHPExcel->getActiveSheet()->setCellValue('P' . $i, $li['user']['nickname']."({$li['checkout_remark']})");
            } else {
                $resultPHPExcel->getActiveSheet()->setCellValue('P' . $i, $li['checkout']."({$li['checkout_remark']})");
            }
            $resultPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $li['remark']);
            $resultPHPExcel->getActiveSheet()->setCellValue('R' . $i, $li['dateline']);
            $resultPHPExcel->getActiveSheet()->getStyle('A' . $i . ':R' . $i)->applyFromArray($styleArray);
            $i++;
        }
        $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, '导出记录数：' . count($list));
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
$obj = new Creditmall_doWebCheckout;
$obj->exec();