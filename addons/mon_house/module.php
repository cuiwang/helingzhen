<?php
/**
 * 
 *
 * @author  codeMonkey
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Mon_houseModule extends WeModule {
	private $table_hosue="mon_house";
	private $table_house_item="mon_house_item";
	private $table_house_type="mon_house_type";
	public $weid;
	public function __construct() {
		global $_W;
		$this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];
	}
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		if (! empty ( $rid )) {
			$reply = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_hosue ) . " WHERE rid = :rid ", array (
					':rid' => $rid
			) );
		}
		if (!empty($reply)) {
				
			$reply['kptime'] = date("Y-m-d", $reply['kptime']);
			$reply['rztime'] = date("Y-m-d", $reply['rztime']);
			


			$house_items=pdo_fetchall("select * from ".tablename($this->table_house_item)." where rid=:rid order by sort asc",array(":rid"=>$rid));

			$house_types=pdo_fetchall("select * from ".tablename($this->table_house_type)." where rid=:rid order by sort asc",array(":rid"=>$rid));

		}

		if(IMS_VERSION>=0.6){

			load()->func('tpl');
		}
		$version = IMS_VERSION<0.6?'':'_advance';
		include $this->template('form'.$version);


	}
	public function fieldsFormValidate($rid = 0) {
		// 规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}
	public function fieldsFormSubmit($rid) {
		global $_GPC, $_W;
		$id = intval ( $_GPC ['reply_id'] );


	
		$insert = array (
				'rid' => $rid,
				'weid' =>$this->weid,
				'title' => $_GPC ['title'],
				'kptime' => strtotime($_GPC ['kptime']),
				'rztime' =>strtotime( $_GPC['rztime']) ,
				'kfs'=>$_GPC['kfs'],
				'price'=>$_GPC['price'],
				'lpaddress'=>$_GPC['lpaddress'],
				'sltel'=>$_GPC['sltel'],
				'zxtel'=>$_GPC['zxtel'],
				'news_title'=>$_GPC['news_title'],
				'news_icon'=>$_GPC['news_icon'],
				'news_content'=>$_GPC['news_content'],
				'share_title'=>$_GPC['share_title'],
				'share_icon'=>$_GPC['share_icon'],
				'share_content'=>$_GPC['share_content'],
				'order_title'=>$_GPC['order_title'],
				'order_remark'=>$_GPC['order_remark'],
				'cover_img'=>$_GPC['cover_img'],
				'overview_img'=>$_GPC['overview_img'],
				'intro_img'=>$_GPC['intro_img'],
			    'dt_img' => $_GPC['dt_img'],
				'dt_intro' => htmlspecialchars_decode($_GPC['dt_intro']),
				'intro'=>htmlspecialchars_decode($_GPC['intro']),
				'createtime'=>TIMESTAMP
		);
	


		if (empty($id)) {
	
			$id=pdo_insert($this->table_hosue, $insert);
	
	
		} else {
			pdo_update($this->table_hosue, $insert, array('id' => $id));
	
		}



		///说明项处理
		$house_ids = $_GPC ['house_ids'];
		$house_names = $_GPC ['house_iname'];
		$house_conents = $_GPC ['house_icontent'];
		
		$house_sorts=$_GPC['house_sort'];
		
			
		pdo_query ( "delete from " . tablename ( $this->table_house_item ) . " where hid=:hid",array(":hid"=>$id) );
			
		if (is_array ( $house_ids )) {
			foreach ( $house_ids as $key => $value ) {
				$value = intval ( $value );
				$d = array (
						"rid"=>$rid,
						"hid" => $id,
						"iname" => $house_names [$key],
						"icontent" => $house_conents[$key],
				        "sort"=>$house_sorts[$key]
				);
					
				pdo_insert ( $this->table_house_item, $d );
			}
		}
		
		
	
		return true;
	}
	public function ruleDeleted($rid) {
		pdo_delete ( $this->table_hosue, array (
		'rid' => $rid
		) );
		
		
	
	}
    
    
    
   

}