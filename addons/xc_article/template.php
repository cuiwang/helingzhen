<?php defined('IN_IA') or exit('Access Denied');
include_once INC_PHP . 'openauth.php';

class WeModuleSiteMore extends OpenAuth
{
	protected function tpl_res($filename, $subdir)
	{
		global $_W;
		$mn = strtolower($this->modulename);
		$path = $_W['siteroot'] . "/addons/quicktemplate/xc_article/{$_W['account']['template']}/{$subdir}/{$filename}";
		return $path;
	}

	protected function resource($filename, $subdir)
	{
		global $_W;
		$mn = strtolower($this->modulename);
		if ($this->inMobile) {
			$source = INC_PHP . "template/mobile/themes/{$_W['account']['template']}/{$subdir}/{$filename}";
			if (!is_file($source)) {
				$source = INC_PHP . "template/mobile/{$subdir}/{$filename}";
			}
		} else {
			$source = INC_PHP . "template/web/themes/{$_W['account']['template']}/{$subdir}/{$filename}";
			if (!is_file($source)) {
				$source = INC_PHP . "template/{$subdir}/{$filename}";
			}
		}
		if (!is_file($source)) {
			exit("Error: template source {$source} '{$subdir}' '{$filename}' is not exist!");
		}
		return $source;
	}

	protected function xxxtemplate($filename)
	{
		global $_W;
		$name = strtolower($this->modulename);
		$defineDir = dirname($this->__define);
		if (defined('IN_SYS')) {
			$source = IA_ROOT . "/web/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/{$filename}.tpl.php";
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$name}/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = $defineDir . "/template/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/{$_W['template']}/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$filename}.html";
			}
		} else {
			$source = IA_ROOT . "/app/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/app/{$_W['template']}/{$name}/{$filename}.tpl.php";
			if (!is_file($source)) {
				$source = IA_ROOT . "/app/themes/default/{$name}/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = $defineDir . "/template/mobile/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = IA_ROOT . "/app/themes/{$_W['template']}/{$filename}.html";
			}
			if (!is_file($source)) {
				if (in_array($filename, array('header', 'footer', 'slide', 'toolbar', 'message'))) {
					$source = IA_ROOT . "/app/themes/default/common/{$filename}.html";
				} else {
					$source = IA_ROOT . "/app/themes/default/{$filename}.html";
				}
			}
		}
		if (!is_file($source)) {
			exit("Error: template source '{$filename}' is not exist!");
		}
		$paths = pathinfo($compile);
		$compile = str_replace($paths['filename'], $_W['uniacid'] . '_' . $paths['filename'], $compile);
		if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
			template_compile($source, $compile, true);
		}
		return $compile;
	}

	protected function template($filename)
	{
		global $_W;
		$name = strtolower($this->modulename);
		$defineDir = dirname($this->__define);
		if (defined('IN_SYS')) {
			return parent::template($filename);
		} else {
			$source = THEME_DIR . "{$_W['account']['template']}/{$filename}.html";
			$compile = THEME_COMPILE_DIR . "{$name}/{$_W['account']['template']}/{$filename}.tpl.php";
			if (!is_file($source)) {
				$source = DEFAULT_THEME_DIR . "{$filename}.html";
			}
			if (!is_file($source)) {
				if (in_array($filename, array('header', 'footer', 'slide', 'toolbar', 'message'))) {
					$source = IA_ROOT . "/app/themes/default/common/{$filename}.html";
				} else {
					$source = IA_ROOT . "/app/themes/default/{$filename}.html";
				}
			}
		}
		if (!is_file($source)) {
			exit("Error: template source {$source}  - filename  '{$filename}' - name {$name} is not exist!");
		}
		if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
			template_compile($source, $compile, true);
		}
		return $compile;
	}

	private function ext_template_manifest($tpl)
	{
		$mn = strtolower($this->modulename);
		$manifest = array();
		$filename = THEME_DIR . $tpl . '/manifest.xml';
		if (!file_exists($filename)) {
			return array();
		}
		$xml = str_replace(array('&'), array('&amp;'), file_get_contents($filename));
		$xml = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		if (empty($xml)) {
			return array();
		}
		$manifest['name'] = strval($xml->identifie);
		if (empty($manifest['name']) || $manifest['name'] != $tpl) {
			return array();
		}
		$manifest['title'] = strval($xml->title);
		if (empty($manifest['title'])) {
			return array();
		}
		$manifest['description'] = strval($xml->description);
		$manifest['author'] = strval($xml->author);
		$manifest['url'] = strval($xml->url);
		if ($xml->settings->item) {
			foreach ($xml->settings->item as $msg) {
				$attrs = $msg->attributes();
				$manifest['settings'][trim(strval($attrs['variable']))] = trim(strval($attrs['content']));
			}
		}
		$manifest['category'] = array();
		if ($xml->category->item) {
			foreach ($xml->category->item as $item) {
				$manifest['category'][] = $item;
			}
		}
		$manifest['article'] = array();
		if ($xml->article->item) {
			foreach ($xml->article->item as $item) {
				$manifest['article'][] = $item;
			}
		}
		return $manifest;
	}
}