<?php
 
function filterEmptyData($result)
{
	foreach ($result as $k => $v) {
		if ((empty($v) && is_array($v)) || ($v === NULL)) {
			unset($result[$k]);
			continue;
		}

		if (is_array($v)) {
			$result[$k] = filterEmptyData($v);
		}
	}

	return $result;
}

function app_error($errcode = 0, $message = '')
{
	global $iswxapp;
	global $openid;
	$res = array('error' => $errcode, 'message' => empty($message) ? AppError::getError($errcode) : $message);
	exit(json_encode($res));
}

function app_json($result = NULL)
{
	global $iswxapp;
	global $openid;
	$ret = array();

	if (!is_array($result)) {
		$result = array();
	}

	$ret['error'] = 0;
	if (!empty($result) && !$iswxapp) {
		$result = filteremptydata($result);
	}

	exit(json_encode(array_merge($ret, $result)));
}

function jsonFormat($data, $indent = NULL)
{
	array_walk_recursive($data, 'jsonFormatProtect');
	$data = json_encode($data);
	$data = urldecode($data);
	$ret = '';
	$pos = 0;
	$length = strlen($data);
	$indent = (isset($indent) ? $indent : '    ');
	$newline = "\n";
	$prevchar = '';
	$outofquotes = true;
	$i = 0;

	while ($i <= $length) {
		$char = substr($data, $i, 1);
		if (($char == '"') && ($prevchar != '\\')) {
			$outofquotes = !$outofquotes;
		}
		else {
			if ((($char == '}') || ($char == ']')) && $outofquotes) {
				$ret .= $newline;
				--$pos;
				$j = 0;

				while ($j < $pos) {
					$ret .= $indent;
					++$j;
				}
			}
		}

		$ret .= $char;
		if ((($char == ',') || ($char == '{') || ($char == '[')) && $outofquotes) {
			$ret .= $newline;
			if (($char == '{') || ($char == '[')) {
				++$pos;
			}

			$j = 0;

			while ($j < $pos) {
				$ret .= $indent;
				++$j;
			}
		}

		$prevchar = $char;
		++$i;
	}

	return $ret;
}

function jsonFormatProtect(&$val)
{
	if (($val !== true) && ($val !== false) && ($val !== NULL)) {
		$val = urlencode($val);
	}
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'app/core/error_code.php';
$iswxapp = false;
$openid = '';
class AppMobilePage extends PluginMobilePage
{
	protected $member;
	protected $iswxapp = false;

	public function __construct()
	{
		global $_GPC;
		global $_W;
		global $iswxapp;
		global $openid;
		$this->model = m('plugin')->loadModel($GLOBALS['_W']['plugin']);
		$this->set = $this->model->getSet();
		if ((($_GPC['r'] != 'app.cacheset') && strexists($_GPC['openid'], 'sns_wa')) || (isset($_GPC['comefrom']) && ($_GPC['comefrom'] == 'wxapp'))) {
			$iswxapp = true;
			$this->iswxapp = true;
		}

		$member = m('member')->getMember($_GPC['openid']);
		$this->member = $member;

		if (p('commission')) {
			p('commission')->checkAgent($member['openid']);
		}

		$GLOBALS['_W']['openid'] = $_W['openid'] = $member['openid'];

		if ($this->iswxapp) {
			$GLOBALS['_W']['openid'] = $_W['openid'] = 'sns_wa_' . $member['openid_wa'];
		}

		$global_set = m('cache')->getArray('globalset', 'global');

		if (empty($global_set)) {
			$global_set = m('common')->setGlobalSet($_W['uniacid']);
		}

		if (!is_array($global_set)) {
			$global_set = array();
		}

		empty($global_set['trade']['credittext']) && $global_set['trade']['credittext'] = '积分';
		empty($global_set['trade']['moneytext']) && $global_set['trade']['moneytext'] = '余额';
		$GLOBALS['_W']['shopset'] = $global_set;
	}

	public function logging($message = '')
	{
		$filename = IA_ROOT . '/data/logs/' . date('Ymd') . '.php';
		load()->func('file');
		mkdirs(dirname($filename));
		$content = date('Y-m-d H:i:s') . " \n------------\n";
		if (is_string($message) && !in_array($message, array('post', 'get'))) {
			$content .= "String:\n" . $message . "\n";
		}

		if (is_array($message)) {
			$content .= "Array:\n";

			foreach ($message as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}

		if ($message === 'get') {
			$content .= "GET:\n";

			foreach ($_GET as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}

		if ($message === 'post') {
			$content .= "POST:\n";

			foreach ($_POST as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}

		$content .= "\n";
		$filename = IA_ROOT . '/data/logs/' . date('Ymd') . '.log';
		$fp = fopen($filename, 'a+');
		fwrite($fp, $content);
		fclose($fp);
	}
}

?>
