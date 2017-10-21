<?php
global $_W, $_GPC;
load()->func('logging');
$pici = $_GPC['pici'];

if (!empty($_GPC['foo'])) {
	try {
		include_once "reader.php";
		$tmp = $_FILES['file']['tmp_name'];
		if (empty($tmp)) {
			echo '请选择要导入的Excel文件！';
			die;
		}
		$file_name = IA_ROOT . "/addons/haoman_dpm/xls/code.xls";
		$uniacid = $_W['uniacid'];

		if (copy($tmp, $file_name)) {
			$xls = new Spreadsheet_Excel_Reader();
			$xls->setOutputEncoding('utf-8');
			$xls->read($file_name);
			$data_values = "";
			$count = $xls->sheets[0]['numRows'];
			for ($i = 1; $i <= $count; $i++) {
				$code = $xls->sheets[0]['cells'][$i][1];

				$data = array(
					'uniacid' => $_W['uniacid'],
					'title' => $code,
					'pici' => $pici,
					'time' => TIMESTAMP,
				);
				$res = pdo_insert('haoman_dpm_pw',$data);
			}
			if ($res) {
				pdo_query("update " . tablename("haoman_dpm_pici") . " set codenum = codenum + {$count} where pici = :pici and uniacid =:uniacid", array(":pici" => $pici, ":uniacid" => $uniacid));
				$url = $this->createWebUrl('code');
				echo '<script>alert(\'导入成功！\')</script>';
				echo "<script>window.location.href= '{$url}'</script>";
			} else {
				$url = $this->createWebUrl('Import', array());
				echo '<script>alert(\'导入失败！\')</script>';
				echo "<script>window.location.href= '{$url}'</script>";
			}
		} else {
			echo '复制失败！';
			die;
		}
	} catch (Exception $e) {
		logging_run($e, '', 'upload_tiku');
	}
} else {
	include $this->template('import');
}