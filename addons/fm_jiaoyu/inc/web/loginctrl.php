<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

        global $_GPC, $_W;
		$weid = $_W['uniacid'];
		load()->func('tpl');

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';    
        if ($operation == 'display') {
			load()->func('tpl');
			
			$item = pdo_fetch("SELECT htname,bgcolor,banner1,banner2,banner3,banner4 FROM " . tablename($this->table_set) . "");
			
			if(checksubmit('submit')){
				$data = array(
					'htname'   => trim($_GPC['htname']),
					'bgcolor'  => trim($_GPC['bgcolor']),
					'banner1'  => trim($_GPC['banner1']),
					'banner2'  => trim($_GPC['banner2']),
					'banner3'  => trim($_GPC['banner3']),
					'banner4'  => trim($_GPC['banner4']),
				);
				if(empty($data['htname'])){
					$this->imessage('请输入后台系统名称！', referer(), 'error');
				}
				pdo_update($this->table_set, $data);
				
				message('操作成功', '', 'success');
			}
        } else{
			message('操作失败, 非法访问.');
		}			
		
   include $this->template ( 'web/loginctrl' );
?>