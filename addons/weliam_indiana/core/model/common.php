<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Common {
function level($name, $parents, $children, $parentid, $childid){
	$html = '
		<script type="text/javascript">
			window._' . $name . ' = ' . json_encode($children) . ';
		</script>';
			if (!defined('TPL_INIT_CATEGORY3')) {
				$html .= '
		<script type="text/javascript">
			function renderCategory2(obj, name){
				var index = obj.options[obj.selectedIndex].value;
				require([\'jquery\', \'util\'], function($, u){
					$selectChild = $(\'#\'+name+\'_child\');
					var html = \'<option value="0">选择优惠卷</option>\';
					if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
						$selectChild.html(html);
						return false;
					}
					for(var i=0; i< window[\'_\'+name][index].length; i++){
						html += \'<option value="\'+window[\'_\'+name][index][i][\'couponid\']+\'">\'+window[\'_\'+name][index][i][\'title\']+\'</option>\';
					}
					$selectChild.html(html);
				});
			}
		</script>
					';
				define('TPL_INIT_CATEGORY3', true);
			}

			$html .=
				'<div class="row row-fix tpl-category-container">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<select class="form-control tpl-category-parent" id="' . $name . '_parent" name="' . $name . '[parentid]" onchange="renderCategory2(this,\'' . $name . '\')">
					<option value="0">选择商家</option>';
			$ops = '';
			foreach ($parents as $row) {
				$html .= '
					<option value="' . $row['id'] . '" ' . (($row['id'] == $parentid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
			}
			$html .= '
				</select>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<select class="form-control tpl-category-child" id="' . $name . '_child" name="' . $name . '[childid]">
					<option value="0">选择优惠卷</option>';
			if (!empty($parentid) && !empty($children[$parentid])) {
				foreach ($children[$parentid] as $row) {
					$html .= '
					<option value="' . $row['couponid'] . '"' . (($row['couponid'] == $childid) ? 'selected="selected"' : '') . '>' . $row['title'] . '</option>';
				}
			}
			$html .= '
				</select>
			</div>
		</div>
	';
	return $html;
	}
	public function sendTplNotice($touser, $template_id, $postdata, $url = '', $account = null) {
		global $_W;
		load() -> model('account');
		if (!$account) {
			if (!empty($_W['acid'])) {
				$account= WeAccount :: create($_W['acid']);
			} else {
				$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $_W['uniacid']));
				$account= WeAccount :: create($acid);
			} 
		} 
		if (!$account) {
			return;
		} 
		return $account -> sendTplNotice($touser, $template_id, $postdata, $url);
	} 
}