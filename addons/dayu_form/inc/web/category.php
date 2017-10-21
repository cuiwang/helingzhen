<?php
		if ($operation == 'display') {
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$category = pdo_fetchall("select * from ".tablename($this->tb_category)." where weid = :weid ORDER BY id DESC", array(':weid' => $weid));
			$total = pdo_fetchcolumn("SELECT count(id) FROM ".tablename($this->tb_category)." where weid = :weid ORDER BY id DESC", array(':weid' => $weid));
			$pager = pagination($total, $pindex, $psize);
			foreach($category AS $key => $val){
				$category[$key]['link'] = murl('entry', array('do' => 'index', 'id' =>$val['id'], 'm' => 'dayu_form'), true, true);
				$category[$key]['color'] = !empty($val['color']) ? iunserializer($val['color']) : '';
			}
		
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if(!empty($id)) {
				$cate = pdo_get($this->tb_category, array('weid' => $weid, 'id' => $id), array());
				$color = !empty($cate['color']) ? iunserializer($cate['color']) : '';
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $weid,
					'title' => $_GPC['title'],
					'list' => $_GPC['list'],
				);
				if (!empty($_GPC['thumb'])) {
					$data['icon'] = $_GPC['thumb'];
					load()->func('file');
					file_delete($_GPC['thumb-old']);
				}
				$color	= array(
                    'nav_index'	=> $_GPC['nav_index'],
                    'nav_page'	=> $_GPC['nav_page'],
                    'nav_btn'	=> $_GPC['nav_btn']
				);
				$data['color'] = iserializer($color);
				if (!empty($id)) {
					pdo_update($this->tb_category, $data, array('id' => $id));
				} else {
					pdo_insert($this->tb_category, $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT * FROM ".tablename($this->tb_category)." WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
			if (pdo_delete($this->tb_category, array('id' => $id)) === false) {
				message('删除分类失败, 请稍后重试.');
				exit();
			}		
			message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
		}
		include $this->template('category');
?>