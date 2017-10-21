<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php  if(isset($title)) $_W['page']['title'] = $title?><?php  if(!empty($_W['page']['title'])) { ?><?php  echo $_W['page']['title'];?> - <?php  } ?><?php  if(empty($_W['page']['copyright']['sitename'])) { ?><?php  if(IMS_FAMILY != 'x') { ?>微信框架微信公众平台管理系统-功能之强，秒杀一切<?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['sitename'];?><?php  } ?></title>
<meta name="keywords" content="<?php  if(empty($_W['page']['copyright']['keywords'])) { ?><?php  if(IMS_FAMILY != 'x') { ?>微信框架,微信,微信公众平台<?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['keywords'];?><?php  } ?>" />
<meta name="description" content="<?php  if(empty($_W['page']['copyright']['description'])) { ?><?php  if(IMS_FAMILY != 'x') { ?>微信框架微信公众平台，让世界为您的产品点赞，业内功能最强的微信平台，秒杀市场其他微信公众号第三方开发平台。<?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['description'];?><?php  } ?>" />
<link rel="stylesheet" type="text/css" href="./resource/weidongli/css/style_8_common.css" />
<link rel="stylesheet" type="text/css" href="./resource/weidongli/css/style_8_portal_index.css" />
<script src="./resource/weidongli/js/common.js" type="text/javascript"></script>
<script src="./resource/weidongli/js/portal.js" type="text/javascript"></script>
<link href="./resource/weidongli/css/xiaoyustyle.css" rel="stylesheet" type="text/css" />
<script src="./resource/weidongli/js/jquery.min.js" type="text/javascript"></script>
<script src="./resource/weidongli/js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="./resource/weidongli/js/jquery.event.move.js" type="text/javascript" type="text/javascript"></script>
<script src="./resource/weidongli/js/xiaoyu.js" type="text/javascript"></script>
<script type="text/javascript">
	if(navigator.appName == 'Microsoft Internet Explorer'){
		if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
			alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
		}
	}
	
	window.sysinfo = {
<?php  if(!empty($_W['uniacid'])) { ?>
		'uniacid': '<?php  echo $_W['uniacid'];?>',
<?php  } ?>
<?php  if(!empty($_W['acid'])) { ?>
		'acid': '<?php  echo $_W['acid'];?>',
<?php  } ?>
<?php  if(!empty($_W['openid'])) { ?>
		'openid': '<?php  echo $_W['openid'];?>',
<?php  } ?>
<?php  if(!empty($_W['uid'])) { ?>
		'uid': '<?php  echo $_W['uid'];?>',
<?php  } ?>
		'siteroot': '<?php  echo $_W['siteroot'];?>',
		'siteurl': '<?php  echo $_W['siteurl'];?>',
		'attachurl': '<?php  echo $_W['attachurl'];?>',
		'attachurl_local': '<?php  echo $_W['attachurl_local'];?>',
		'attachurl_remote': '<?php  echo $_W['attachurl_remote'];?>',
<?php  if(defined('MODULE_URL')) { ?>
		'MODULE_URL': '<?php echo MODULE_URL;?>',
<?php  } ?>
		'cookie' : {'pre': '<?php  echo $_W['config']['cookie']['pre'];?>'}
	};
	</script>

</head>

<body id="nv_portal" class="pg_index" onkeydown="if(event.keyCode==27) return false;">
<div id="append_parent"></div><div id="ajaxwaitid"></div>

<div class="index" id="index">
<script src="./resource/weidongli/js/index.js" type="text/javascript" type="text/javascript"></script>
<script src="./resource/weidongli/js/slide.js" type="text/javascript" type="text/javascript"></script>		
<div class="headbg">
<div class="head xiaoyu_head cl">
<h1 class="logo"><a href="./" title="<?php  echo $_W['setting']['copyright']['smname'];?>科技团队"><img src="<?php  if(!empty($_W['setting']['copyright']['flogo'])) { ?><?php  echo tomedia($_W['setting']['copyright']['flogo']);?><?php  } else { ?>./resource/weidongli/images/logo.png<?php  } ?>" border="0" /></a></h1>
<div id="nav" class="nav">
<ul>
<li onClick="navmove('#index','0')" class="active"><a href="./">网站首页</a></li>
<li onClick="navmove('#about','3')"><a href="<?php  echo url('article/news-show/list');?>">新闻动态</a></li>
<li onClick="navmove('#project','1')"><a href="<?php  echo url('article/plug-show/list');?>">功能展示</a></li>
<li onClick="navmove('#services','2')"><a href="<?php  echo url('article/case-show/list');?>">客户案例</a></li>
<li onClick="navmove('#services','1')"><a href="<?php  echo url('website/wenda-show/list');?>">产品问答</a></li>
<li onClick="navmove('#services','1')"><a href="<?php  echo url('website/taocan-show');?>">会员套餐</a></li>
<li onClick="navmove('#login','4')"><a href="<?php  echo url('user/login');?>" title="登陆">会员登陆</a></li>
<li onClick="navmove('#login','4')"><a href="<?php  echo url('user/register');?>" title="注册">会员注册</a></li>

</ul>
</div>

</div>
</div>
</div>

</div>

<style id="diy_style" type="text/css">#portal_block_21 {  background-image:none !important;background-color:#f8f8f8 !important;}#framecD6C91 {  background-image:none !important;background-color:#f8f8f8 !important;}#frameJF9ETG {  background-image:none !important;background-color:#f8f8f8 !important;}#frameRcSjL0 {  background-image:none !important;background-color:#f8f8f8 !important;}#portal_block_22 {  background-image:none !important;background-color:#f8f8f8 !important;}#portal_block_23 {  background-image:none !important;background-color:#f8f8f8 !important;}#frameiCJL81 {  background-image:none !important;background-color:#f8f8f8 !important;}#frameH5Zf05 {  background-image:none !important;background-color:#f8f8f8 !important;}#portal_block_24 {  background-image:none !important;background-color:transparent !important;}#portal_block_25 {  background-image:none !important;background-color:#f8f8f8 !important;}#portal_block_26 {  background-image:none !important;background-color:#f8f8f8 !important;}#frameO11z24 {  background-image:none !important;background-color:#f8f8f8 !important;}#portal_block_28 {  background-image:none !important;background-color:transparent !important;}#portal_block_31 {  background-image:none !important;background-color:transparent !important;}#frameA6m8CD {  background-image:none !important;background-color:transparent !important;}</style>
