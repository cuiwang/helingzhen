<?php
	
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan isNOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
/* 输入到CSV文件 */
				$html = "\xEF\xBB\xBF";
				/* 输出表头 */
				$filter = array(
					'id' => 'ID',
					'uid' => '会员编号',
					'nickname' => '会员名',
					'credittype' => '积分类型',
					'num' => '积分数量',
					'createtime' => '创建时间',
					'remark' => '备注',
				);
				$filter['createtime'] = date('Y年m月d日 H:i:s',$filter['createtime'])
				foreach ($filter as $key => $value) {
					$html .= $value . "\t,";
				}
				$html .= "\n";

				if (!empty($list)) {
					$status = array('隐藏', '显示');
					foreach ($list as $key => $value) {
						foreach ($filter as $index => $id) {
							if ($index != 'status') {
								$html .= $value[$index] . "\t, ";
							} else {
								$html .= $status[$value[$index]] . "\t, ";
							}
						}
						$html .= "\n";
					}
				}

				/* 输出CSV文件 */
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=全部数据.csv");
				echo $html;
				exit();
