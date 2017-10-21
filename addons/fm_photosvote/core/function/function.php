<?php

function sortArrByField(&$array, $field, $desc = true){
  $fieldArr = array();
  foreach ($array as $k => $v) {
    $fieldArr[$k] = $v[$field];
  }
  $sort = $desc == false ? SORT_ASC : SORT_DESC;
  array_multisort($fieldArr, $sort, $array);
}
function fm_file_upload($file, $type = 'image', $name = '') {
	$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');
	if (empty($file)) {
		return error(-1, '没有上传内容');
	}
	if (!in_array($type, array('image', 'thumb', 'music', 'voice', 'video', 'vedio', 'audio'))) {
		return error(-2, '未知的上传类型');
	}

	global $_W;
	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
	$ext = strtolower($ext);
	switch($type){
		case 'image':
		case 'thumb':
			$allowExt = array('gif', 'jpg', 'jpeg', 'bmp', 'png');
			$limit = 10 * 1024;
			break;
		case 'voice':
		case 'music':
		case 'audio':
			$allowExt = array('mp3', 'wma', 'wav', 'amr', '3gp','mov','mp4','avi','flv');
			$limit = 20 * 1024;
			break;
		case 'vedio':
		case 'video':
			$allowExt = array('rm', 'rmvb', 'wmv', 'avi', 'mpg', 'mpeg', 'mp4', 'mov', 'flv', '3gp', '4gp', 'mkv', 'f4v', 'm4v');
			$limit = 50 * 1024;
			break;
	}
	if (!in_array(strtolower($ext), $allowExt) || in_array(strtolower($ext), $harmtype)) {
		return error(-3, '不允许上传此类文件');
	}
	if (!empty($limit) && $limit * 1024 < filesize($file['tmp_name'])) {
		return error(-4, "上传的文件超过大小限制，请上传小于 {$limit}k 的文件");
	}
	$result = array();
	if (empty($name) || $name == 'auto') {
		$uniacid = intval($_W['uniacid']);
		$path = "{$type}s/{$uniacid}/" . date('Y/m/');
		mkdirs(ATTACHMENT_ROOT . '/' . $path);
		$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, $ext);

		$result['path'] = $path . $filename;
	} else {
		mkdirs(dirname(ATTACHMENT_ROOT . '/' . $name));
		if (!strexists($name, $ext)) {
			$name .= '.' . $ext;
		}
		$result['path'] = $name;
	}

	if (!file_move($file['tmp_name'], ATTACHMENT_ROOT . '/' . $result['path'])) {
		return error(-1, '保存上传文件失败');
	}
	$result['success'] = true;
	return $result;
}

function fm_form_category_2level($name, $parents, $children, $parentid, $childid, $threec,$autosave = false){
	$html = '
		<script type="text/javascript">
			window._' . $name . ' = ' . json_encode($children) . ';
		</script>';
			if (!defined('TPL_INIT_CATEGORY')) {
				$html .= '
		<script type="text/javascript">
			function renderCategory(obj, name){
				var index = obj.options[obj.selectedIndex].value;

					$selectChild = $(\'#\'+name+\'_child\');
					var html = \'<option value="0">请选择二级分类</option>\';
					if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
						$selectChild.html(html);
						return false;
					}
					for(var i=0; i< window[\'_\'+name][index].length; i++){
						html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
					}
					$selectChild.html(html);

			}
			function renderCategory_t(obj, name){
				var index = obj.options[obj.selectedIndex].value;

					$selectChild = $(\'#\'+name+\'_threec\');
					var html = \'<option value="0">请选择三级分类</option>\';
					if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
						$selectChild.html(html);
						return false;
					}
					for(var i=0; i< window[\'_\'+name][index].length; i++){
						html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
					}
					$selectChild.html(html);

			}
		</script>
					';
				define('TPL_INIT_CATEGORY', true);
			}

			$html .=
				'<div class="row row-fix tpl-category-container">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">';
			if ($autosave == true) {
				$html .='<select class="form-control tpl-category-parent" id="' . $name . '_parent" name="' . $name . '[parentid]" onchange="renderCategory(this,\'' . $name . '\');autosave(\'' . $name . '_parent\');">';
			}else{
				$html .='<select class="form-control tpl-category-parent" id="' . $name . '_parent" name="' . $name . '[parentid]" onchange="renderCategory(this,\'' . $name . '\')">';
			}
			$html .='<option value="0">请选择一级分类</option>';

			$ops = '';
			foreach ($parents as $row) {
				$html .= '
					<option value="' . $row['id'] . '" ' . (($row['id'] == $parentid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
			}
			$html .= '
				</select>
			</div>';


			//二级
			if (!empty($parentid) || !empty($children)) {

					$html .='<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">';
					if ($autosave == true) {
						$html .= '<select class="form-control tpl-category-child" id="' . $name . '_child" name="' . $name . '[childid]" onchange="renderCategory_t(this,\'' . $name . '\');autosave(\'' . $name . '_child\');">';
					}else{
						$html .= '<select class="form-control tpl-category-child" id="' . $name . '_child" name="' . $name . '[childid]" onchange="renderCategory_t(this,\'' . $name . '\')">';
					}

					$html .= '<option value="0">请选择二级分类</option>';
					if (!empty($parentid) && !empty($children[$parentid])) {
						foreach ($children[$parentid] as $row) {
							$html .= '
							<option value="' . $row['id'] . '"' . (($row['id'] == $childid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
						}
					}
					$html .= '
						</select>
					</div>';
			}
		//三级
		if (!empty($childid) || !empty($children)) {
			$html .= '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">';
			if ($autosave == true) {
				$html .= '<select class="form-control tpl-category-child" id="' . $name . '_threec" name="' . $name . '[threecs]" onchange="autosave(\'' . $name . '_threec\');">';
			}else{
				$html .= '<select class="form-control tpl-category-child" id="' . $name . '_threec" name="' . $name . '[threecs]" >';
			}
			$html .= '<option value="0">请选择三级分类</option>';
			if (!empty($childid) && !empty($children[$childid])) {
				foreach ($children[$childid] as $row) {
					$html .= '
					<option value="' . $row['id'] . '"' . (($row['id'] == $threec) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
				}
			}
			$html .= '
				</select>
			</div>';
		}
			////
		$html .= '</div>';
	return $html;
}
function fm_mc_credit_update($uid, $credittype, $creditval = 0,$up = true, $log = array()) {
	global $_W;
	$credittype = trim($credittype);
	$credittypes = mc_credit_types();
	if (!in_array($credittype, $credittypes)) {
		return error('-1', "指定的用户积分类型 “{$credittype}”不存在.");
	}

	$creditval = floatval($creditval);
	if (empty($creditval)) {
		return true;
	}
	$value = pdo_fetchcolumn("SELECT $credittype FROM " . tablename('mc_members') . " WHERE `uid` = :uid", array(':uid' => $uid));
	if ($creditval > 0 || ($value + $creditval >= 0) || $credittype == 'credit6') {
		if ($up == 'js') {
			pdo_update('mc_members', array($credittype => $value - $creditval), array('uid' => $uid));
		}elseif ($up == 'add') {
			pdo_update('mc_members', array($credittype => $value + $creditval), array('uid' => $uid));
		}else{
			if ($up) {
				pdo_update('mc_members', array($credittype => $value + $creditval), array('uid' => $uid));
			}else{
				pdo_update('mc_members', array($credittype => $creditval), array('uid' => $uid));
			}
		}


	} else {
		return error('-1', "积分类型为“{$credittype}”的积分不够，无法操作。");
	}
		if (empty($log) || !is_array($log)) {
		$log = array($uid, '未记录', 0, 0);
	}
	$clerk_type = intval($log[5]) ? intval($log[5]) : 1;
	$data = array(
		'uid' => $uid,
		'credittype' => $credittype,
		'uniacid' => $_W['uniacid'],
		'num' => $creditval,
		'createtime' => TIMESTAMP,
		'operator' => intval($log[0]),
		'module' => trim($log[2]),
		'clerk_id' => intval($log[3]),
		'store_id' => intval($log[4]),
		'clerk_type' => $clerk_type,
		'remark' => $log[1],
	);
	pdo_insert('mc_credits_record', $data);

	return true;
}
function getmobilenames($names) {
	switch ($names) {
	  case 'photosvote.html':
	    $name = '投票首页';
	    break;
	  case 'tuser.html':
	    $name = '投票详情页';
	    break;
	  case 'tuserphotos.html':
	    $name = '投票相册展示页';
	    break;
	  case 'reg.html':
	    $name = '注册报名页';
	    break;
	  case 'paihang.html':
	    $name = '排行榜页';
	    break;
	  case 'des.html':
	    $name = '活动详情页';
	    break;

	  default:
	    $name = '女神来了';
	    break;
	}
	return $name;
}

function update_tags_piaoshu ($rid) {
	$tags = pdo_fetchall("SELECT * FROM ".tablename('fm_photosvote_tags')." WHERE rid = :rid", array(':rid' => $rid));
	foreach ($tags as $key => $row) {
		if ($row['icon'] == 1) {
			$photosnum = pdo_fetchcolumn('SELECT SUM(photosnum) FROM '.tablename('fm_photosvote_provevote').' WHERE rid = :rid AND tagpid = :tagpid AND status = 1', array(':rid' => $rid,':tagpid' => $row['id']));
			$xnphotosnum = pdo_fetchcolumn('SELECT SUM(xnphotosnum) FROM '.tablename('fm_photosvote_provevote').' WHERE rid = :rid AND tagpid = :tagpid  AND status = 1', array(':rid' => $rid,':tagpid' => $row['id']));
			//pdo_update($this->table_tags, array('piaoshu' => $photosnum + $xnphotosnum), array('rid' => $rid, 'id' => $row['id']));
		}elseif ($row['icon'] == 2) {
			$photosnum = pdo_fetchcolumn('SELECT SUM(photosnum) FROM '.tablename('fm_photosvote_provevote').' WHERE rid = :rid AND tagid = :tagid AND status = 1', array(':rid' => $rid,':tagid' => $row['id']));
			$xnphotosnum = pdo_fetchcolumn('SELECT SUM(xnphotosnum) FROM '.tablename('fm_photosvote_provevote').' WHERE rid = :rid AND tagid = :tagid  AND status = 1', array(':rid' => $rid,':tagid' => $row['id']));
			//pdo_update($this->table_tags, array('piaoshu' => $photosnum + $xnphotosnum), array('rid' => $rid, 'id' => $row['id']));
		}elseif ($row['icon'] == 3) {
			$photosnum = pdo_fetchcolumn('SELECT SUM(photosnum) FROM '.tablename('fm_photosvote_provevote').' WHERE rid = :rid AND tagtid = :tagtid AND status = 1', array(':rid' => $rid,':tagtid' => $row['id']));
			$xnphotosnum = pdo_fetchcolumn('SELECT SUM(xnphotosnum) FROM '.tablename('fm_photosvote_provevote').' WHERE rid = :rid AND tagtid = :tagtid  AND status = 1', array(':rid' => $rid,':tagtid' => $row['id']));
		}
		pdo_update('fm_photosvote_tags', array('piaoshu' => $photosnum + $xnphotosnum), array('rid' => $rid, 'id' => $row['id']));
	}
}


if(!function_exists('paginationm')) {
	/**
	 * 生成分页数据
	 * @param int $currentPage 当前页码
	 * @param int $totalCount 总记录数
	 * @param string $url 要生成的 url 格式，页码占位符请使用 *，如果未写占位符，系统将自动生成
	 * @param int $pageSize 分页大小
	 * @return string 分页HTML
	 */
	function paginationm($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => '')) {
		global $_W;
		$pdata = array(
			'tcount' => 0,
			'tpage' => 0,
			'cindex' => 0,
			'findex' => 0,
			'pindex' => 0,
			'nindex' => 0,
			'lindex' => 0,
			'options' => ''
		);
		if($context['ajaxcallback']) {
			$context['isajax'] = true;
		}

		$pdata['tcount'] = $tcount;
		$pdata['tpage'] = ceil($tcount / $psize);
		if($pdata['tpage'] <= 1) {
			return '';
		}
		$cindex = $pindex;
		$cindex = min($cindex, $pdata['tpage']);
		$cindex = max($cindex, 1);
		$pdata['cindex'] = $cindex;
		$pdata['findex'] = 1;
		$pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
		$pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
		$pdata['lindex'] = $pdata['tpage'];

		if($context['isajax']) {
			if(!$url) {
				$url = $_W['script_name'] . '?' . http_build_query($_GET);
			}
			$pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
		} else {
			if($url) {
				$pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
				$pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
				$pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
				$pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
			} else {
				$_GET['page'] = $pdata['findex'];
				$pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['pindex'];
				$pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['nindex'];
				$pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['lindex'];
				$pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			}
		}

		$html = '<div class="pagination pagination-centered"><ul class="pagination pagination-centered">';
		if($pdata['cindex'] > 1) {
			$html .= "<li><a {$pdata['faa']} class=\"pager-nav\">首页</a></li>";
			$html .= "<li><a {$pdata['paa']} class=\"pager-nav\">&laquo;上一页</a></li>";
		}
		//页码算法：前5后4，不足10位补齐
		if(!$context['before'] && $context['before'] != 0) {
			$context['before'] = 5;
		}
		if(!$context['after'] && $context['after'] != 0) {
			$context['after'] = 4;
		}

		if($context['after'] != 0 && $context['before'] != 0) {
			$range = array();
			$range['start'] = max(1, $pdata['cindex'] - $context['before']);
			$range['end'] = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
			if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
				$range['end'] = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
				$range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
			}
			for ($i = $range['start']; $i <= $range['end']; $i++) {
				if($context['isajax']) {
					$aa = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', ' . $context['ajaxcallback'] . ')"';
				} else {
					if($url) {
						$aa = 'href="?' . str_replace('*', $i, $url) . '"';
					} else {
						$_GET['page'] = $i;
						$aa = 'href="?' . http_build_query($_GET) . '"';
					}
				}
				$html .= ($i == $pdata['cindex'] ? '<li class="active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>');
			}
		}

		if($pdata['cindex'] < $pdata['tpage']) {
			$html .= "<li><a {$pdata['naa']} class=\"pager-nav\">下一页&raquo;</a></li>";
			$html .= "<li><a {$pdata['laa']} class=\"pager-nav\">尾页</a></li>";
		}
		$html .= '</ul></div>';
		return $html;
	}
}


/**
*求两个已知经纬度之间的距离,单位为米
*@param lng1,lng2 经度
*@param lat1,lat2 纬度
*@return float 距离，单位千米
*@author www.012wz.com
**/
if(!function_exists('getDistance')) {
	function getDistance($lng1,$lat1,$lng2,$lat2){
		//将角度转为狐度
		$radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
		$radLat2=deg2rad($lat2);
		$radLng1=deg2rad($lng1);
		$radLng2=deg2rad($lng2);
		$a=$radLat1-$radLat2;
		$b=$radLng1-$radLng2;
		$s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
		return sprintf("%.2f", $s/1000);
	}
}
if(!function_exists('mobilelimit')) {
	function mobilelimit($mobile){
		$phone = $mobile;
		$mphone = substr($phone,3,4);
		$lphone = str_replace($mphone,"****",$phone);
		return $lphone;
	}
}
if(!function_exists('getrealip')) {
	function getrealip(){
		$arr_ip_header = array(
		    "HTTP_CLIENT_IP",
		    "HTTP_X_FORWARDED_FOR",
		    "REMOTE_ADDR",
		    "HTTP_CDN_SRC_IP",
		    "HTTP_PROXY_CLIENT_IP",
		    "HTTP_WL_PROXY_CLIENT_IP"
		);
		$client_ip = 'unknown';
		foreach ($arr_ip_header as $key) {
		    if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != "unknown") {
		        $client_ip = $_SERVER[$key];
		        break;
		    }
		}
		if ($pos = strpos($client_ip,',')){
			$client_ip = substr($client_ip,$pos+1);
		}
		return $client_ip;
	}
}
/**
 * 获取客户端IP地址
 *
 * @param boolean $s_type ip类型[ip|long]
 * @return string $ip
 */
 if(!function_exists('getip')) {
	function getip($b_ip = true){
		$arr_ip_header = array(
		    "HTTP_CLIENT_IP",
		    "HTTP_X_FORWARDED_FOR",
		    "REMOTE_ADDR",
		    "HTTP_CDN_SRC_IP",
		    "HTTP_PROXY_CLIENT_IP",
		    "HTTP_WL_PROXY_CLIENT_IP"
		);
		$client_ip = 'unknown';
		foreach ($arr_ip_header as $key) {
		    if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != "unknown") {
		        $client_ip = $_SERVER[$key];
		        break;
		    }
		}
		if ($pos = strpos($client_ip,',')){
			$client_ip = substr($client_ip,$pos+1);
		}
		return $client_ip;
	}
 }
if(!function_exists('GetIpLookup')) {
	function GetIpLookup($ip = ''){
		if(empty($ip)){
			$ip = getip();
		}
		$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
		if(empty($res)){ return false; }
		$jsonMatches = array();
		preg_match('#\{.+?\}#', $res, $jsonMatches);
		if(!isset($jsonMatches[0])){ return false; }
		$json = json_decode($jsonMatches[0], true);
		if(isset($json['ret']) && $json['ret'] == 1){
			$json['ip'] = $ip;
			unset($json['ret']);
		}else{
			return false;
		}
		return $json;
	}
}
if(!function_exists('getiparr')) {
	function getiparr($ip) {
		$ip = GetIpLookup($ip);
		$iparr = array();
		$iparr['country'] .= $ip['country'];
		$iparr['province'] .= $ip['province'];
		$iparr['city'] .= $ip['city'];
		$iparr['district'] .= $ip['district'];
		$iparr['ist'] .= $ip['ist'];
		$iparr = iserializer($iparr);
		return $iparr;
	}
}
if(!function_exists('getuserlocal')) {
	function getuserlocal($key, $getip='') {
		global $_W;
		load()->func('communication');
		if (empty($getip)) {
			//$ip = CLIENT_IP;
		}else{
			$ip = $getip;
		}
		$getipurl = "http://apis.map.qq.com/ws/location/v1/ip?ip=".$ip."&key=".$key;
		//$getipurl = "http://apis.map.qq.com/ws/district/v1/search?&keyword=海门&key=".$key;
		$content = ihttp_get($getipurl);
		$info = @json_decode($content['content'], true);
		return $info['result']['ad_info'];
	}
}
if(!function_exists('getoauth')) {
	function getoauth() {
		global $_W;
		$setting = setting_load('site');
		$id = isset($setting['site']['key']) ? $setting['site']['key'] : '0';
		$onlyoauth = pdo_fetch("SELECT * FROM ".tablename('fm_photosvote_onlyoauth')." WHERE siteid = :siteid", array(':siteid' => $id));
		return $onlyoauth['fmauthtoken'];
	}
}

function resize($srcImage,$toFile,$newName,$maxWidth = 100,$maxHeight = 100,$imgQuality=100)
{

    list($width, $height, $type, $attr) = getimagesize($srcImage);
    if($width < $maxWidth  || $height < $maxHeight) return ;
    switch ($type) {
    case 1: $img = imagecreatefromgif($srcImage); break;
    case 2: $img = imagecreatefromjpeg($srcImage); break;
    case 3: $img = imagecreatefrompng($srcImage); break;
    }
    $scale = min($maxWidth/$width, $maxHeight/$height); //求出绽放比例

    if($scale < 1) {
    $newWidth = floor($scale*$width);
    $newHeight = floor($scale*$height);
    $newImg = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    $toFile = preg_replace("/(.gif|.jpg|.jpeg|.png)/i","",$toFile);

    switch($type) {
        case 1: if(imagegif($newImg, "$toFile$newName.gif", $imgQuality))
        return "$newName.gif"; break;
        case 2: if(imagejpeg($newImg, "$toFile$newName.jpg", $imgQuality))
        return "$newName.jpg"; break;
        case 3: if(imagepng($newImg, "$toFile$newName.png", $imgQuality))
        return "$newName.png"; break;
        default: if(imagejpeg($newImg, "$toFile$newName.jpg", $imgQuality))
        return "$newName.jpg"; break;
    }
    imagedestroy($newImg);
    }
    imagedestroy($img);
    return false;
}

function des_encode($key, $text){
	$y = pkcs5_pad($text);
	$td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, ''); //使用MCRYPT_DES算法,cbc模式
	$ks = mcrypt_enc_get_key_size($td);

	mcrypt_generic_init($td, $key, $key); //初始处理
	$encrypted = mcrypt_generic($td, $y); //解密
	mcrypt_generic_deinit($td); //结束
	mcrypt_module_close($td);
	return base64_encode($encrypted);
}
function pkcs5_pad($text, $block = 8){
	$pad = $block - (strlen($text) % $block);
	return $text . str_repeat(chr($pad), $pad);
}

function Sec2Time($time){
    if(is_numeric($time)){
    $value = array(
      "years" => 0, "days" => 0, "hours" => 0,
      "minutes" => 0, "seconds" => 0,
    );
    if($time >= 31556926){
      $value["years"] = floor($time/31556926);
      $time = ($time%31556926);
    }
    if($time >= 86400){
      $value["days"] = floor($time/86400);
      $time = ($time%86400);
    }
    if($time >= 3600){
      $value["hours"] = floor($time/3600);
      $time = ($time%3600);
    }
    if($time >= 60){
      $value["minutes"] = floor($time/60);
      $time = ($time%60);
    }
    $value["seconds"] = floor($time);
    //return (array) $value;
    $t = '';
    if (!empty($value["years"])) {
    		$t .= $value["years"] ."年";
    }
    if (!empty($value["days"])) {
    		$t .= $value["days"] ."天";
    }
    if (!empty($value["hours"])) {
    		$t .= $value["hours"] ."小时";
    }
    if (!empty($value["minutes"])) {
    		$t .= $value["minutes"] ."分";
    }
    $t .= $value["seconds"]."秒";
    Return $t;

     }else{
    return (bool) FALSE;
    }
 }