<?php
/**
 * 广告自主投放平台模块定义
 *
 * @author Michael Hu
 * @url http://bbs.9ye.cc/
 */
defined('IN_IA') or exit('Access Denied');

class cgc_adModule extends WeModule {

	public function fieldsFormDisplay($rid = 0) {
		// 要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		load ()->func ( 'tpl' );
		if (! empty ( $rid )) {
			$reply = pdo_fetch ( "SELECT a.*,b.aname FROM " . tablename ( "cgc_ad_rule" ) . " as a left join ".tablename('cgc_ad_quan')." as b on a.quan_id=b.id WHERE ruleid = :rid ", array (
					':rid' => $rid 
			) );
		}
		$quan = pdo_fetchall ( "SELECT * FROM " . tablename ( "cgc_ad_quan" ) . " WHERE weid = :weid AND del=0 ORDER BY `id` DESC", array (
				':weid' => $_W ['uniacid'] 
		) );
		include $this->template ( 'display' );
	}
	
	public function fieldsFormValidate($rid = 0) {
	  // 规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
	  return '';
	}
	
	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');

		if(checksubmit()) {    
            load()->func('file');	          
            $input =array();             
           /* $input['debug_mode'] = trim($_GPC['debug_mode']);            
            $input['enter_control'] = trim($_GPC['enter_control']); 
            $input['hide_wxmenu'] = trim($_GPC['hide_wxmenu']); */
            $input=$_GPC['settings'];   
                                                                                                                                                                      
            if($this->saveSettings($input)) {
                message('保存参数成功', 'refresh');
            }
        }  
        include $this->template('setting');
	}
	
	public function fieldsFormSubmit($rid) {
		// 规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		if(empty($_GPC['quan_id']))
		{
			message("请选择所属广告平台");
		}
		if(empty($_GPC['news_title']))
		{
			message("请选择图文标题");
		}
		$data = array (
				'ruleid' => $rid,
				'weid' => $_W ['uniacid'],
				'news_title'=>$_GPC['news_title'],
				'news_thumb'=>$_GPC['news_thumb'],
				'news_content'=>$_GPC['news_content'],
				'quan_id' => $_GPC ['quan_id'] 
		);
		if ($_GPC ['id']) {
			pdo_update ( "cgc_ad_rule", $data, array (
					'id' => $_GPC ['id'] 
			) );
		} else {
			pdo_insert ( "cgc_ad_rule", $data );
		}
	}
	public function ruleDeleted($rid) {
		// 删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete ( "cgc_ad_rule", array (
				'ruleid' => $rid 
		) );
	}
}