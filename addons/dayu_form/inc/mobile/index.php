<?php
		$id = intval($_GPC['id']);
		if($id){
			$cate = pdo_get($this->tb_category, array('weid' => $weid, 'id' => $id), array());
			$color = !empty($cate['color']) ? iunserializer($cate['color']) : '';
			$nav_index = $color['nav_index'];
			$nav_page = $color['nav_page'];
			$nav_btn = $color['nav_btn'];
			$title = $cate['title'];
			$where.=" and cid='{$id}'";
			$list_num = !empty($cate['list']) ? $cate['list'] : 1;
			if ($cate['list']=='3'){
				$list_num = '4';
			}elseif ($cate['list']=='4'){
				$list_num = '3';
			}	
		}else{
			$title = $setting['title'];
			$nav_index = $setting['color']['nav_index'];
			$nav_page = $setting['color']['nav_page'];
			$nav_btn = $setting['color']['nav_btn'];
			$list_num = !empty($setting['list_num']) ? $setting['list_num'] : 1;
			if ($setting['list_num']=='3'){
				$list_num = '4';
			}elseif ($setting['list_num']=='4'){
				$list_num = '3';
			}
		}
			$is_list .= " AND list = 1";
		$category = pdo_fetchall("select * from ".tablename($this->tb_category)." where weid = :weid ORDER BY id", array(':weid' => $weid));
		
		$slide = pdo_fetchall("SELECT * FROM " . tablename($this->tb_slide) . " WHERE weid = :weid $where ORDER BY displayorder DESC", array(':weid' => $weid));
        foreach($slide AS $key => $val){
            $slide[$key]['thumb'] = tomedia($val['thumb']);	
        }
		
		$form = pdo_fetchAll("SELECT * FROM".tablename($this->tb_form)." WHERE status=1 AND weid='{$weid}' AND list = 1 $where order by createtime DESC,reid DESC");
        foreach($form AS $index => $v){
			$form[$index]['par'] = iunserializer($v['par']);
            $form[$index]['link'] = $this->createMobileUrl('dayu_form',array('id' => $v['reid']));
			$form[$index]['subtitle'] = !empty($form[$index]['par']['subtitle']) ? $form[$index]['par']['subtitle'] : $v['title'];
			$form[$index]['icon'] = !empty($form[$index]['par']['icon']) ? tomedia($form[$index]['par']['icon']) : tomedia('headimg_'.$_W['acid'].'.jpg');
        }
		$jquery=1;
		include $this->template('index');
?>