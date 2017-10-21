<?php
        if ($operation == 'display') {
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_slide) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
			foreach($list AS $key => $val){
				$list[$key]['cate'] = $this->get_category($val['cid']);
			}
        } elseif ($operation == 'post') {

            $id = intval($_GPC['id']);
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'title' => $_GPC['title'],
                    'link' => $_GPC['link'],
                    'cid' => $_GPC['cate'],
                    'enabled' => intval($_GPC['enabled']),
                    'displayorder' => intval($_GPC['displayorder'])
                );
                if (!empty($_GPC['thumb'])) {
                    $data['thumb'] = $_GPC['thumb'];
                load()->func('file');
                    file_delete($_GPC['thumb-old']);
                }

                if (!empty($id)) {
                    pdo_update($this->tb_slide, $data, array('id' => $id));
                } else {
                    pdo_insert($this->tb_slide, $data);
                    $id = pdo_insertid();
                }
                message('更新幻灯片成功！', $this->createWebUrl('slide', array('op' => 'display')), 'success');
            }
            $slide = pdo_fetch("select * from " . tablename($this->tb_slide) . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
			$category = pdo_fetchall("select * from ".tablename($this->tb_category)." where weid = :weid ORDER BY id DESC", array(':weid' => $weid));
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $slide = pdo_fetch("SELECT id  FROM " . tablename($this->tb_slide) . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
            if (empty($slide)) {
                message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('slide', array('op' => 'display')), 'error');
            }
            pdo_delete($this->tb_slide, array('id' => $id));
            message('幻灯片删除成功！', $this->createWebUrl('slide', array('op' => 'display')), 'success');
        } else {
            message('请求方式不存在');
        }
        include $this->template('slide', TEMPLATE_INCLUDEPATH, true);
?>