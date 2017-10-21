<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W['uniacid'];

        $action = 'school';
        $title = '学校管理';
        $url = $this->createWebUrl($action, array('op' => 'display'));
		$city = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = '{$weid}' And type = 'city' ORDER BY ssort DESC", array(':weid' => $weid));
        $area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = '{$weid}' And type = '' ORDER BY ssort DESC", array(':weid' => $weid));
        $schooltype = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = '{$weid}' ORDER BY ssort DESC", array(':weid' => $weid));
        $set = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $weid)); 
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['ssort'])) {
                    foreach ($_GPC['ssort'] as $id => $val) {
                        $data = array('ssort' => intval($_GPC['ssort'][$id]));
                        pdo_update($this->table_index, $data, array('id' => $id));
                    }
                }
                message('操作成功!', $url);
            }
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
            }

            if (!empty($_GPC['areaid'])) {
                $areaid = $_GPC['areaid'];
                $condition .= " AND areaid = '{$areaid}'";
            }

            if (!empty($_GPC['typeid'])) {
                $typeid = $_GPC['typeid'];
                $condition .= " AND typeid = '{$typeid}'";
            }			
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
						
			if($_W['isfounder'] || $_W['role'] == 'owner') {
				$where = "WHERE weid = '{$weid}'";
			}else{
				$uid = $_W['user']['uid'];	
				$where = "WHERE weid = '{$weid}' And uid = '{$uid}' And is_show = 1 ";		
			}
			
			$where1 = "WHERE weid = '{$weid}' And schoolid = '{$id}'";

			$schoollist = pdo_fetchall("SELECT * FROM " . tablename($this->table_index) . " $where $condition   order by ssort desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            
			if (!empty($schoollist)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_index) . " $where $condition");
				$shumu = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_students) . " $where1 ");
                $pager = pagination($total, $pindex, $psize);
            }
			foreach($schoollist as $key => $row){
				$shoptype = pdo_fetch("SELECT name FROM " . tablename($this->table_type) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['typeid']));
				$citys = pdo_fetch("SELECT name FROM " . tablename($this->table_area) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['cityid']));
				$quyu = pdo_fetch("SELECT name FROM " . tablename($this->table_area) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['areaid']));
				$schoollist[$key]['leixing'] = $shoptype['name'];
				$schoollist[$key]['city'] = $citys['name'];
				$schoollist[$key]['qujian'] = $quyu['name'];
			}
			
        } elseif ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']); 
            $reply = pdo_fetch("select * from " . tablename($this->table_index) . " where id=:id and weid =:weid", array(':id' => $id, ':weid' => $weid));
			$quyu = pdo_fetch("select name from " . tablename($this->table_area) . " where id=:id and weid =:weid", array(':id' => $reply['areaid'], ':weid' => $weid));
			$user = pdo_fetchall("SELECT * FROM " . tablename('users') . " where status = 2 ORDER BY uid DESC");
			$groups = pdo_fetchall("SELECT id, name FROM ".tablename('users_group')." ORDER BY id ASC");
            $sign = unserialize($reply['signset']);
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($weid),
					'uid' => intval($_GPC['uid']),
					'cityid' => intval($_GPC['cityid']),
                    'areaid' => intval($_GPC['area']),
                    'typeid' => intval($_GPC['type']),
                    'title' => trim($_GPC['title']),
                    'info' => trim($_GPC['info']),
                    'content' => trim($_GPC['content']),
					'zhaosheng' => trim($_GPC['zhaosheng']),
                    'tel' => trim($_GPC['tel']),
                    'gonggao' => trim($_GPC['gonggao']),
                    'logo' => trim($_GPC['logo']),
					'thumb' => trim($_GPC['thumb']),
					'qroce' => trim($_GPC['qroce']),
                    'address' => trim($_GPC['address']),
                    'location_p' => trim($_GPC['location_p']),
                    'location_c' => trim($_GPC['location_c']),
                    'location_a' => trim($_GPC['location_a']),
                    'lng' => trim($_GPC['baidumap']['lng']),
                    'lat' => trim($_GPC['baidumap']['lat']),
                    'password' => trim($_GPC['password']),
                    'recharging_password' => trim($_GPC['recharging_password']),
                    'is_show' => intval($_GPC['is_show']),
					'is_rest' => intval($_GPC['is_rest']),
                    'is_sms' => intval($_GPC['is_sms']),
                    'is_hot' => intval($_GPC['is_hot']),
                    'is_cost' => intval($_GPC['is_cost']),
					'is_video' => intval($_GPC['is_video']),
					'is_sign' => intval($_GPC['is_sign']),
					'isopen' => intval($_GPC['isopen']),
					'wqgroupid' => intval($_GPC['wqgroupid']),
					'issale' => intval($_GPC['issale']),
					'userstyle' => 'user',
					'bjqstyle' => 'old',
					'style1' => trim($_GPC['style1']),
					'style2' => trim($_GPC['style2']),
					'style3' => trim($_GPC['style3']),
					'ssort' => intval($_GPC['ssort']),
                    'dateline' => TIMESTAMP,
                );
                if (!$data['wqgroupid']) {
                    message('请为学校独立账户分配一个默认微赞用户组.', '', 'error');
                }
                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }
                if (istrlen($data['tel']) == 0) {
//                    message('没有输入联系电话.', '', 'error');
                }
                if (istrlen($data['address']) == 0) {
                    //message('请输入地址。', '', 'error');
                }

                if ($_GPC['is_sign'] == 1) {
					$temp = array(
						'is_idcard' => $_GPC['is_idcard'],
						'is_nj' => $_GPC['is_nj'],
						'is_bj' => $_GPC['is_bj'],
						'is_bir' => $_GPC['is_bir'],
						'is_bd' => $_GPC['is_bd']
						);
                    $data['signset'] = serialize($temp);
                }else{
					$data['signset'] = '';
				}
                if (!empty($id)) {
                    unset($data['dateline']);
                    pdo_update($this->table_index, $data, array('id' => $id, 'weid' => $weid));
					$icon = pdo_fetch("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid", array(
						':weid' => $weid,
						':schoolid' => $id,
					));
					if(empty($icon)){
						$icon1 = array('weid' => $weid,'schoolid' => $id,'name' =>'学校简介','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc1.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('jianjie', array('schoolid' => $id)),'place' => 1,'ssort' => 1,'status' => 1,);
						$icon2 = array('weid' => $weid,'schoolid' => $id,'name' =>'教师风采','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc2.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('teachers', array('schoolid' => $id)),'place' => 1,'ssort' => 2,'status' => 1,);
						$icon3 = array('weid' => $weid,'schoolid' => $id,'name' =>'招生简介','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc4.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('zhaosheng', array('schoolid' => $id)),'place' => 1,'ssort' => 3,'status' => 1,);
						$icon4 = array('weid' => $weid,'schoolid' => $id,'name' =>'本周食谱','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc3.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('cooklist', array('schoolid' => $id)),'place' => 1,'ssort' => 4,'status' => 1,);
						$icon5 = array('weid' => $weid,'schoolid' => $id,'name' =>'微信绑定','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc7.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $id)),'place' => 1,'ssort' => 5,'status' => 1,);
						$icon6 = array('weid' => $weid,'schoolid' => $id,'name' =>'课程列表','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc8.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('kc', array('schoolid' => $id)),'place' => 1,'ssort' => 6,'status' => 1,);
						$icon7 = array('weid' => $weid,'schoolid' => $id,'name' =>'报名申请','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc9.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('signup', array('schoolid' => $id)),'place' => 1,'ssort' => 7,'status' => 1,);
						$icon8 = array('weid' => $weid,'schoolid' => $id,'name' =>'教师中心','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc10.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('myschool', array('schoolid' => $id)),'place' => 1,'ssort' => 8,'status' => 1,);
						pdo_insert($this->table_icon, $icon1);
						pdo_insert($this->table_icon, $icon2);
						pdo_insert($this->table_icon, $icon3);
						pdo_insert($this->table_icon, $icon4);
						pdo_insert($this->table_icon, $icon5);
						pdo_insert($this->table_icon, $icon6);
						pdo_insert($this->table_icon, $icon7);
						pdo_insert($this->table_icon, $icon8);
					}
                } else {
                    pdo_insert($this->table_index, $data);
					$schoolid = pdo_insertid();
					$icon1 = array('weid' => $weid,'schoolid' => $schoolid,'name' =>'学校简介','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc1.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('jianjie', array('schoolid' => $schoolid)),'place' => 1,'ssort' => 1,'status' => 1,);
					$icon2 = array('weid' => $weid,'schoolid' => $schoolid,'name' =>'教师风采','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc2.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('teachers', array('schoolid' => $schoolid)),'place' => 1,'ssort' => 2,'status' => 1,);
					$icon3 = array('weid' => $weid,'schoolid' => $schoolid,'name' =>'招生简介','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc4.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('zhaosheng', array('schoolid' => $schoolid)),'place' => 1,'ssort' => 3,'status' => 1,);
					$icon4 = array('weid' => $weid,'schoolid' => $schoolid,'name' =>'本周食谱','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc3.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('cooklist', array('schoolid' => $schoolid)),'place' => 1,'ssort' => 4,'status' => 1,);
					$icon5 = array('weid' => $weid,'schoolid' => $schoolid,'name' =>'微信绑定','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc7.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $schoolid)),'place' => 1,'ssort' => 5,'status' => 1,);
					$icon6 = array('weid' => $weid,'schoolid' => $schoolid,'name' =>'课程列表','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc8.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('kc', array('schoolid' => $schoolid)),'place' => 1,'ssort' => 6,'status' => 1,);
					$icon7 = array('weid' => $weid,'schoolid' => $schoolid,'name' =>'报名申请','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc9.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('signup', array('schoolid' => $schoolid)),'place' => 1,'ssort' => 7,'status' => 1,);
					$icon8 = array('weid' => $weid,'schoolid' => $schoolid,'name' =>'教师中心','icon' => '../addons/fm_jiaoyu/public/mobile/img/ioc10.png','url' => $_W['siteroot'] .'app/'.$this->createMobileUrl('myschool', array('schoolid' => $schoolid)),'place' => 1,'ssort' => 8,'status' => 1,);
					pdo_insert($this->table_icon, $icon1);
					pdo_insert($this->table_icon, $icon2);
					pdo_insert($this->table_icon, $icon3);
					pdo_insert($this->table_icon, $icon4);
					pdo_insert($this->table_icon, $icon5);
					pdo_insert($this->table_icon, $icon6);
					pdo_insert($this->table_icon, $icon7);
					pdo_insert($this->table_icon, $icon8);										
                }
                message('操作成功!', $url);
            }
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $store = pdo_fetch("SELECT id FROM " . tablename($this->table_index) . " WHERE id = '$id'");
            if (empty($store)) {
                message('抱歉，不存在或是已经被删除！', $this->createWebUrl('school', array('op' => 'display')), 'error');
            }
            pdo_delete($this->table_index, array('id' => $id, 'weid' => $weid));
            message('删除成功！', $this->createWebUrl('school', array('op' => 'display')), 'success');
        }
        include $this->template ( 'web/school' );
?>