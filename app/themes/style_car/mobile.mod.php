<?php
/**
 * [Weidongli] Copyright (c) 2014 B2CTUI.COM
 * s.
 */
defined('IN_IA') or exit('Access Denied');

function mobile_styles() {
	global $_W;
	$styles = pdo_fetchall("SELECT variable, content FROM ".tablename('site_styles_vars')." WHERE uniacid = '{$_W['uniacid']}' AND templateid = '{$_W['account']['styleid']}'", array(), 'variable');
	if (!empty($styles)) {
		foreach ($styles as $variable => $value) {
			if (strexists($value['content'], 'images/')) {
				$value['content'] = $_W['attachurl'] . $value['content'];
			}
			if (($variable == 'logo' || $variable == 'indexbgimg' || $variable == 'ucbgimg') && !strexists($value['content'], 'http://')) {
				$value['content'] = $_W['siteroot'] . 'themes/mobile/'.$_W['account']['template'].'/images/' . $value['content'];
			}
			$styles[$variable] = $value['content'];
		}
	}
	return $styles;
}

function mobile_nav($position) {
	global $_W;
	$navs = pdo_fetchall("SELECT name, url, icon, css, position, module,displayorder FROM ".tablename('site_nav')." WHERE (position = '$position' OR position = '3') AND status = 1 AND uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
	if (!empty($navs)) {
		foreach ($navs as $index => &$row) {
			if (!strexists($row['url'], ':') && !strexists($row['url'], 'uniacid=')) {
				$row['url'] .= strexists($row['url'], '?') ?  '&uniacid='.$_W['uniacid'] : '?uniacid='.$_W['uniacid'];
			}
			$row['css'] = unserialize($row['css']);
			if ($row['position'] == '3') {
				unset($row['css']['icon']['font-size']);
			}
			$row['css']['icon']['style'] = "color:{$row['css']['icon']['color']};font-size:{$row['css']['icon']['font-size']}px;";
			$row['css']['name'] = "color:{$row['css']['name']['color']};";
			if ($row['position'] == '3') {
				$quick[] = $row;
				unset($navs[$index]);
			}
		}
		unset($row);
	}
	return $navs;
}