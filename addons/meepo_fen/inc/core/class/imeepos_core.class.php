<?php
define('IMEEPOS_URL','http://012wz.com.cn/meepo/module/');
class MeModuleSite  extends WeModuleSite {
	public function getWebPageHeader($acts,$op,$id = 'id',$ext=""){
		global $_W,$_GPC;
		$do = $_GPC['do'];
		if(!empty($_GPC['plugin'])){
			$ext ."&plugin=".trim($_GPC['plugin']);
		}
		if(!empty($_GPC['module'])){
			$ext ."&module=".trim($_GPC['module']);
		}
		if(!empty($_GPC['code'])){
			$ext ."&code=".trim($_GPC['code']);
		}
		foreach ($acts as $ac){
			if($op == $ac['act']){
				if($ac['needid'] && $_GPC[$id]>0){
					$header[] = array('href'=>$this->createWebUrl($do,array('act'=>$ac['act'],$id=>$_GPC[$id])).$ext,'title'=>$ac['title'],'active'=>true);
				}else if(!$ac['needid'] && empty($_GPC[$id])){
					$header[] = array('href'=>$this->createWebUrl($do,array('act'=>$ac['act'])).$ext,'title'=>$ac['title'],'active'=>true);
				}else if(!$ac['needid'] && !empty($_GPC[$id])){
					$header[] = array('href'=>$this->createWebUrl($do,array('act'=>$ac['act'])).$ext,'title'=>$ac['title'],'active'=>false);
				}
			}else{
				if($ac['needid'] && $_GPC['id']>0){
					$header[] = array('href'=>$this->createWebUrl($do,array('act'=>$ac['act'],$id=>$_GPC[$id])).$ext,'title'=>$ac['title'],'active'=>false);
				}else if(!$ac['needid']){
					$header[] = array('href'=>$this->createWebUrl($do,array('act'=>$ac['act'])).$ext,'title'=>$ac['title'],'active'=>false);
				}
			}
		}
		return $header;
	}
	public function getWebPluginPageHeader($acts,$op,$id = 'id',$ext=""){
		global $_W,$_GPC;
		$plugin = trim($_GPC['plugin']);
		$code = trim($_GPC['code']);
		foreach ($acts as $ac){
			if($op == $ac['act']){
				if($ac['needid'] && $_GPC[$id]>0){
					$header[] = array('href'=>$this->createWebPluginUrl("{$plugin}/{$code}",array('act'=>$ac['act'],$id=>$_GPC[$id])).$ext,'title'=>$ac['title'],'active'=>true);
				}else if(!$ac['needid'] && empty($_GPC[$id])){
					$header[] = array('href'=>$this->createWebPluginUrl("{$plugin}/{$code}",array('act'=>$ac['act'])).$ext,'title'=>$ac['title'],'active'=>true);
				}else if(!$ac['needid'] && !empty($_GPC[$id])){
					$header[] = array('href'=>$this->createWebPluginUrl("{$plugin}/{$code}",array('act'=>$ac['act'])).$ext,'title'=>$ac['title'],'active'=>false);
				}
			}else{
				if($ac['needid'] && $_GPC['id']>0){
					$header[] = array('href'=>$this->createWebPluginUrl("{$plugin}/{$code}",array('act'=>$ac['act'],$id=>$_GPC[$id])).$ext,'title'=>$ac['title'],'active'=>false);
				}else if(!$ac['needid']){
					$header[] = array('href'=>$this->createWebPluginUrl("{$plugin}/{$code}",array('act'=>$ac['act'])).$ext,'title'=>$ac['title'],'active'=>false);
				}
			}
		}
		return $header;
	}
	public function getPluginList(){
		$module = $this->modulename;
		$sql = "SELECT * FROM ".tablename('meepo_common_plugin')." WHERE module = :module ";
		$params = array(':module'=>$module);
		$list = pdo_fetchall($sql,$params);
		return $list;
	}
	public function getPluginByCode($code){
		global $_W,$_GPC;
		$module = $this->modulename;
		$sql = "SELECT * FROM ".tablename('meepo_common_plugin')." WHERE code = :code AND module = :module";
		$params = array(':code'=>$code,':module'=>$module);
		$item = pdo_fetch($sql,$params);
		return $item;
	}
	public function getMenuByCode($code){
		global $_W,$_GPC;
		$module = $this->modulename;
		$sql = "SELECT * FROM ".tablename('imeepos_module_menu')." WHERE code = :code AND module = :module";
		$params = array(':code'=>$code,':module'=>$module);
		$item = pdo_fetch($sql,$params);
		return $item;
	}
	//自动登录
	public function checkLogin(){
		global $_W;
		if(!empty($_W['member']['uid'])){
			return true;
		}
		if(!empty($_W['openid'])) {
			$uid = mc_openid2uid($_W['openid']);
			if(_mc_login(array('uid' => intval($uid)))) {
				return true;
			}
		}
		return false;
	}
	//是否自己
	public function isMe(){
		global $_W,$_GPC;
		$isLogin = $this->checkLogin();
		if($isLogin){
			if(!empty($_GPC['u'])){
				$u = trim($_GPC['u']);
				$uid = mc_openid2uid($u);
				if($uid == $_W['member']['uid'] && !empty($uid)){
					return true;
				}
			}else{
				return true;
			}
		}
		return false;
	}
	//前台入口，带会员标示
	protected function createMobileUrl($do, $query = array(), $noredirect = true) {
		global $_W;
		$this->checkLogin();
		$query['do'] = $do;
		$query['m'] = strtolower($this->modulename);
		return imurl('entry', $query, $noredirect);
	}
	/*前台插件调用接口*/
	protected function createMobilePluginUrl($do, $query = array(), $noredirect = true) {
		global $_W;
		$this->checkLogin();
		$query['do'] = 'doplugin';
		$query['m'] = strtolower($this->modulename);
		list($plugin, $code, $op) = explode('/', $do);
		$query['plugin'] = $plugin;
		$query['code'] = $code;
		$query['op'] = $op;
		return imurl('entry', $query, $noredirect);
	}
	//前台插件接口接受
	public function doMobileDoplugin(){
		global $_W,$_GPC;
		$plugin = $_GPC['plugin'];
		$code = $_GPC['code'];
		$op = $_GPC['op'];

		$file = MODULE_ROOT . '/plugin/__init.php';
		if(file_exists($file)){
			require $file;
		}

		$file = MODULE_ROOT . '/plugin/'.$plugin.'/__init.php';
		if(file_exists($file)){
			require $file;
		}

		$file = MODULE_ROOT . '/plugin/'.$plugin.'/inc/app/__init.php';
		if(file_exists($file)){
			require $file;
		}

		$file = MODULE_ROOT . '/plugin/'.$plugin.'/inc/app/'.$code.'.php';
		if(file_exists($file)){
			require $file;
		}
	}
	//后台插件接口接收
	public function doWebDoplugin(){
		global $_W,$_GPC;
		$plugin = $_GPC['plugin'];
		$code = $_GPC['code'];
		$op = $_GPC['op'];

		$file = MODULE_ROOT . '/plugin/__init.php';
		if(file_exists($file)){
			require $file;
		}

		$file = MODULE_ROOT . '/plugin/'.$plugin.'/__init.php';
		if(file_exists($file)){
			require $file;
		}

		$file = MODULE_ROOT . '/plugin/'.$plugin.'/inc/web/__init.php';
		if(file_exists($file)){
			require $file;
		}

		$file = MODULE_ROOT . '/plugin/'.$plugin.'/inc/web/'.$code.'.php';
		if(file_exists($file)){
			require $file;
		}
	}
	/*后台插件调用接口*/
	protected function createWebPluginUrl($do, $query = array()) {
		$query['do'] = 'doplugin';
		$query['m'] = strtolower($this->modulename);
		list($plugin, $code, $op) = explode('/', $do);
		$query['plugin'] = $plugin;
		$query['code'] = $code;
		$query['op'] = $op;
		return wurl('site/entry', $query);
	}
	/**
	*	@插件表单设计
	*/
	public function getTpl(){
		global $_W,$_GPC;
		$module = $this->modulename;
		$plugin = trim($_GPC['plugin']);
		$code = trim($_GPC['code']);
	  $table = 'imeepos_plugin_tpl';
		$sql = "SELECT * FROM ".tablename($table)." WHERE module = :module AND code = :code AND plugin = :plugin ";
		$params = array(':module'=>$module,':code'=>$code,':plugin'=>$plugin);

		$list = pdo_fetchall($sql,$params);

		return $list;
	}
	/**
	* @return array('list'=>array(),'pager'=>'')
	*/
	public function getList(){
	  global $_W,$_GPC;
		$module = $this->modulename;
		$plugin = trim($_GPC['plugin']);
		$code = trim($_GPC['code']);
	  $table = 'imeepos_'.$module.'_'.$plugin.'_'.$code;
	  $pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "SELECT * FROM ".tablename($table)." WHERE uniacid = :uniacid LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$params = array(':uniacid'=>$_W['uniacid']);
		$tasks = pdo_fetchall($sql,$params);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($table) . " WHERE uniacid = :uniacid ", $params);
		$pager = pagination($total, $pindex, $psize);
	  return array('list'=>$tasks,'pager'=>$pager);
	}
	/**
	* @return $ths = array(array('width'=>String width,'title'=>String title,'code'=>String code));
	*/
	public function getThs(){
		global $_W,$_GPC;
		$module = $this->modulename;
		$plugin = trim($_GPC['plugin']);
		$code = trim($_GPC['code']);

	  $sql = "SELECT * FROM ".tablename('imeepos_plugin_ths')." WHERE module = :module AND plugin = :plugin AND code = :code ";
	  $params = array(':module'=>$module,':plugin'=>$plugin,':code'=>$code);
	  $result = pdo_fetch($sql,$params);
	  return $result;
	}
	/**
	* @return $header = array('href'=>String href,'title'=>String title,'active'=>Bool active);
	*/
	public function getNavs(){
		global $_W,$_GPC;

		$module = $this->modulename;
		$plugin = trim($_GPC['plugin']);
		$code = trim($_GPC['code']);

		$sql = "SELECT acts FROM ".tablename('imeepos_plugin_navs')." WHERE module = :module AND plugin = :plugin AND code = :code ";
		$params = array(':module'=>$module,':plugin'=>$plugin,':code'=>$code);
		$item = pdo_fetchcolumn($sql,$params);
		$acts = unserialize($item);
		/**
		*	@acts = array(array('title'=>String title,'act'=>String act ,'id'=>Bool id));
		*/
		foreach ($acts as $ac){
	  	if($act == $ac['act']){
	  		if($ac['id'] && $_GPC['id']>0){
	  			$header[] = array('href'=>$this->createWebPluginUrl($plugin.'/'.$code,array('act'=>$ac['act'],'id'=>$_GPC['id'])),'title'=>$ac['title'],'active'=>true);
	  		}else if(!$ac['id'] && empty($_GPC['id'])){
	  			$header[] = array('href'=>$this->createWebPluginUrl($plugin.'/'.$code,array('act'=>$ac['act'])),'title'=>$ac['title'],'active'=>true);
	  		}else if(!$ac['id'] && !empty($_GPC['id'])){
	  			$header[] = array('href'=>$this->createWebPluginUrl($plugin.'/'.$code,array('act'=>$ac['act'])),'title'=>$ac['title'],'active'=>false);
	  		}
	  	}else{
	  		if($ac['id'] && $_GPC['id']>0){
	  			$header[] = array('href'=>$this->createWebPluginUrl($plugin.'/'.$code,array('act'=>$ac['act'],'id'=>$_GPC['id'])),'title'=>$ac['title'],'active'=>false);
	  		}else if(!$ac['id']){
	  			$header[] = array('href'=>$this->createWebPluginUrl($plugin.'/'.$code,array('act'=>$ac['act'])),'title'=>$ac['title'],'active'=>false);
	  		}
	  	}
	  }

		return $header;
	}

	/**
	*	@return 编译后的模板文件
	*/
	public function ptemplate($name){
		global $_W,$_GPC;
		$plugin = trim($_GPC['plugin']);
		$filename = trim($name);

		$name = strtolower($this->modulename);
		$defineDir = dirname($this->__define);
		if(defined('IN_SYS')) {
			$source = IA_ROOT . "/web/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/plugin/{$plugin}/web/{$filename}.tpl.php";
			if(!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$name}/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = $defineDir . "/plugin/".$plugin."/view/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = IA_ROOT . "/web/themes/{$_W['template']}/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$filename}.html";
			}
		} else {
			$source = IA_ROOT . "/app/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/app/{$_W['template']}/{$name}/plugin/{$plugin}/app/{$filename}.tpl.php";
			if(!is_file($source)) {
				$source = IA_ROOT . "/app/themes/default/{$name}/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = $defineDir . "/plugin/".$plugin."/view/mobile/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = IA_ROOT . "/app/themes/{$_W['template']}/{$filename}.html";
			}
			if(!is_file($source)) {
				if (in_array($filename, array('header', 'footer', 'slide', 'toolbar', 'message'))) {
					$source = IA_ROOT . "/app/themes/default/common/{$filename}.html";
				} else {
					$source = IA_ROOT . "/app/themes/default/{$filename}.html";
				}
			}
		}
		if(!is_file($source)) {
			exit("Error: template source '{$filename}' is not exist!");
		}
		$paths = pathinfo($compile);
		$compile = str_replace($paths['filename'], $_W['uniacid'] . '_' . $paths['filename'], $compile);
		if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
			template_compile($source, $compile, true);
		}
		return $compile;
	}

}
