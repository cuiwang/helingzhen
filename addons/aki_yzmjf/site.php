<?php

defined('IN_IA') or exit('Access Denied');
class Aki_yzmjfModuleSite extends WeModuleSite
{
    public function doWebSendlist()
    {
        require 'inc/web/sendlist.inc.php';
    }
    public function doWebCodeset()
    {
        require 'inc/web/codeset.inc.php';
    }
    private function getcode($num, $no)
    {
        $time = time('Ymd');
    }
    public function doWebExport()
    {
        global $_W, $_GPC;
        $acid = intval($_W['account']['uniacid']);
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        $result    = array();
        $piciid    = $_GPC['piciid'];
        $condition = "";
        $condition .= " and c.piciid = " . $piciid;
        $condition .= " and c.yzmjfid = 0 ";
        $list = pdo_fetchall("select code,status,jifen from " . tablename("aki_yzmjf_code") . " where uniacid = :uniacid and piciid = :piciid", array(
            ":uniacid" => $_W['uniacid'],
            ':piciid' => $piciid
        ));
        foreach ($list as $k => $v) {
            $status = $v['status'];
            if ($status == '1') {
                $list[$k]['status1'] = "未使用";
            } else if ($status == '2') {
                $list[$k]['status1'] = "已使用";
            }
        }
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator('http://www.ideak.cn')->setLastModifiedBy('http://www.ideak.cn')->setTitle('Office 2007 XLSX Document')->setSubject('Office 2007 XLSX Document')->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('Result file');
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('验证码');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '批次');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', '验证码');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', '验证码');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', '状况');
        $i = 2;
        foreach ($list as $k => $v) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $piciid);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $v['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $v['jifen']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $v['status1']);
            $i++;
        }
        $filename = '验证码数据' . '_' . date('Y-m-d');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    public function doWebImport()
    {
        global $_W, $_GPC;
        load()->func('logging');
        $piciid = $_GPC['piciid'];
        $type   = pdo_fetchcolumn("select type from " . tablename("aki_yzmjf_codenum") . " where id = :id", array(
            ":id" => $piciid
        ));
        if (!empty($_GPC['foo'])) {
            try {
                include_once("reader.php");
                $tmp = $_FILES['file']['tmp_name'];
                if (empty($tmp)) {
                    echo '请选择要导入的Excel文件！';
                    exit;
                }
                $file_name = IA_ROOT . "/addons/aki_yzmjf/xls/code.xls";
                $uniacid   = $_W['uniacid'];
                $type      = pdo_fetchcolumn("select type from " . tablename("aki_yzmjf_codenum") . " where id = :id", array(
                    ":id" => $piciid
                ));
                if (copy($tmp, $file_name)) {
                    $xls = new Spreadsheet_Excel_Reader();
                    $xls->setOutputEncoding('utf-8');
                    $xls->read($file_name);
                    $data_values = "";
                    $count       = $xls->sheets[0]['numRows'];
                    for ($i = 1; $i <= $count; $i++) {
                        $code  = $xls->sheets[0]['cells'][$i][1];
                        $jifen = $xls->sheets[0]['cells'][$i][2];
                        $time  = time();
                        $data_values .= "('$uniacid','$code',0,'$piciid','$type','$time','1','$jifen'),";
                    }
                    $data_values = substr($data_values, 0, -1);
                    $query       = pdo_query("insert into `ims_aki_yzmjf_code`(uniacid,code,yzmjfid,piciid,type,time,status,jifen) values $data_values", array());
                    if ($query) {
                        pdo_query("update " . tablename("aki_yzmjf_codenum") . " set count = count + $count where id = :id and uniacid =:uniacid", array(
                            ":id" => $piciid,
                            ":uniacid" => $uniacid
                        ));
                        $url = $this->createWebUrl('codeset');
                        echo "<script>alert('导入成功！')</script>";
                        echo "<script>window.location.href= '$url'</script>";
                    } else {
                        $url = $this->createWebUrl('Import', array());
                        echo "<script>alert('导入失败！')</script>";
                        echo "<script>window.location.href= '$url'</script>";
                    }
                } else {
                    echo '复制失败！';
                    exit;
                }
            }
            catch (Exception $e) {
                logging_run($e, '', 'upload_tiku');
            }
        } else {
            include $this->template('import');
        }
    }
    public function doWebShowcode()
    {
        global $_W, $_GPC;
        $result    = array();
        $piciid    = $_GPC['piciid'];
        $condition = " ";
        $condition .= " and c.piciid = " . $piciid;
        $condition .= " and c.yzmjfid = 0 ";
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $list   = pdo_fetchall("select code,status,jifen from " . tablename("aki_yzmjf_code") . " where uniacid = :uniacid and piciid = :piciid LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(
            ":uniacid" => $_W['uniacid'],
            ':piciid' => $piciid
        ));
        $total  = pdo_fetchcolumn("select count(*) from " . tablename("aki_yzmjf_code") . " where uniacid = :uniacid and piciid = :piciid ", array(
            ":uniacid" => $_W['uniacid'],
            ':piciid' => $piciid
        ));
        foreach ($list as $k => $v) {
            $status = $v['status'];
            if ($status == '2') {
                $list[$k]['status1'] = "已使用";
            } else {
                $list[$k]['status1'] = "未使用";
            }
        }
        $pager = pagination($total, $pindex, $psize);
        include $this->template("showcode");
    }
}
