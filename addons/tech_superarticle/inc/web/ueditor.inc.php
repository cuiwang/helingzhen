<?php
/**
 * 校园签到系统模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');

	global $_GPC, $_W;
	$settings = $this->module['config'];
	if ($_GPC['id'] == 0) {
		$is_save = pdo_fetch("SELECT * FROM " . tablename('tech_superarticle_article') . " WHERE eid = '{$_W['uniacid']}' AND is_save = 0");
		if ($is_save) {
			$id = $is_save['id'];
		} else {
			$art_data['title'] = '请填写您的文章标题';
			$art_data['content'] = '请编辑您的文章内容';
			$art_data['eid'] = $_W['uniacid'];
			$art_data['author'] = $settings['mr_author'];
			$art_data['createtime'] = time();
			$art_data['is_save'] = 0;
			$art_data['is_delete'] = 0;
			$categorys = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1  ORDER BY id DESC");
			$art_data['category_id'] = $categorys[0]['id'];
			pdo_insert('tech_superarticle_article', $art_data);
			$id = pdo_insertid();
			$setting_data['ar_id']=$id;
			$setting_data['eid']=$_W['uniacid'];
			$setting_data['dashang']=empty($settings['mr_ds'])?1:0;
			$setting_data['is_own']=empty($settings['mr_dsz'])?1:0;
			$setting_data['original']=1;
			$setting_data['yuanwen']=empty($settings['mr_yc'])?1:0;
			$setting_data['is_yueduliang']=1;
			$setting_data['is_dianzan']=1;
			$setting_data['comment']=empty($settings['mr_pl'])?1:0;
			$setting_data['is_comment']=empty($settings['mr_plg'])?0:1;
			$setting_data['gratuity_money']=$settings['mr_dsm'];
			$setting_data['yueduliang']=rand($settings['mr_ymin'], $settings['mr_ymax']);
			$setting_data['dianzanliang']=rand($settings['mr_zmin'], $settings['mr_zmax']);
			pdo_insert('tech_superarticle_setting',$setting_data);
		}
	} else {
		$id = intval($_GPC['id']);
	}
	if(!empty($id)){
		//message('您访问的文章不存在!',$this->createWebUrl('list'),'info');
		$article_info = pdo_fetch("select a.thumb,a.title,a.desc,a.keyword,a.author,a.content,a.is_delete,a.createtime,a.fx_title,category_id,fx_desc,fx_img,fx_url,y_url,s.* from ".tablename('tech_superarticle_article')." as a left join ".tablename('tech_superarticle_setting')." as s on a.id = s.ar_id where a.eid =:eid and a.id=:id",array(':eid'=>$_W['uniacid'],':id'=>$id));
		
		if($article_info['is_delete']==1){
			message('您访问的文章已经被删除!',$this->createWebUrl('list'),'info');
		}
		$content = $article_info['content'];
		$title = $article_info['title'];
		$author = $article_info['author'];
		$desc = $article_info['desc'];
		$thumb = $article_info['thumb'];
		$z_time = date('Y-m-d',$article_info['createtime']);
		$keyword = $article_info['keyword'];
		$category_id = $article_info['category_id'];
		$fx_title = $article_info['fx_title'];
		$fx_desc = $article_info['fx_desc'];
		$fx_img = $article_info['fx_img'];
		$y_url = $article_info['y_url'];
		$fx_url = !empty($article_info['fx_url'])?$article_info['fx_url']:$_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&m=tech_superarticle&id='.$id;
		$original = $article_info['original'];
		$yuanwen = $article_info['yuanwen'];
		$yuanwen_link = $article_info['yuanwen_link'];
		$dashang = $article_info['dashang'];
		$is_own = $article_info['is_own'];
		$gratuity_money = $article_info['gratuity_money'];
		$comment = $article_info['comment'];
		$is_comment = $article_info['is_comment'];
		$is_yueduliang = $article_info['is_yueduliang'];
		$yueduliang = $article_info['yueduliang'];
		$is_dianzan = $article_info['is_dianzan'];
		$dianzanliang = $article_info['dianzanliang'];
		/*var_dump($article_info);
		die();*/
	}
	$url = $_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id' => $id)), 2);
	$img_url = $this->getcode($url);




$wxstyle = $_W['config']['db']['master']['tablepre'].'tech_superarticle_wxstyle';
$mystyle = $_W['config']['db']['master']['tablepre'].'tech_superarticle_mystyle';
/*require_once '../addons/tech_superarticle/inc/web/conn.php';
mysqli_query($link, 'set names "utf8"');
error_reporting(0);*/
echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>超级图文——你想要的都在这里!</title>
    <meta name="keywords" content="微信编辑器">
    <meta name="Description" content="超级图文 微信图文排版工具">
    <meta name="generator" content="ue.9zhulu.com">
    <meta name="author" content="微信精选">
    <meta name="copyright" content="微信编辑器 http://ue.9zhulu.com">
    <link href="../addons/tech_superarticle/inc/web/style/images/wechat.jpg" rel="SHORTCUT ICON">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <link href="../addons/tech_superarticle/inc/web/style/css/common.css" type="text/css" rel="stylesheet">
    <link href="../addons/tech_superarticle/inc/web/style/css/index.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="../addons/tech_superarticle/inc/web/style/css/editor-min.css" type="text/css">
    <link href="../addons/tech_superarticle/inc/web/style/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="../addons/tech_superarticle/inc/web/style/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../addons/tech_superarticle/inc/web/style/js/ajaxfileupload.js"></script>
	<link rel="stylesheet" type="text/css" href="../addons/tech_superarticle/inc/web/style/css/guoyoo.css">
	<link rel="stylesheet" type="text/css" href="../addons/tech_superarticle/inc/web/style/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../addons/tech_superarticle/inc/web/style/css/jquery.jgrowl.css">
   	<!--[if lt IE 9]>
          <script src="../addons/tech_superarticle/inc/web/style/js/html5.js"></script>
 	  <![endif]-->
	<script type="text/javascript" charset="utf-8" src="../addons/tech_superarticle/inc/web/style/js/bootstrap.min.js"></script>
	<script>
	var BASEURL = "";
	var current_editor;
	var current_active_v3item = null;
	var isout="false";
	var isnew="";</script>
	<style>
		#right-fix-tab{
			width:32px;position:absolute;right:0px;
		}
		#right-fix-tab li{width:30px;background:rgba(58,51,50,0.5);border:0 none;color:#FFF;width:30px;font-size:14px;}

		#color-plan .nav-tabs > li > a{padding:5px;color: #efefef;border: 0 none;}
		#color-plan .nav-tabs > li > a:hover{background:transparent;color:#FFF;}
		#color-plan .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {background-color: #000;color: #FFF;border: 0 none;}
		#more-popover-content .btn-xs{font-size:12px;padding:2px 2px;width:64px;margin:2px;height:20px;margin:1px auto;border: 0 none;background: transparent;color: #FFF;border: 1px solid #FFF;}
		#more-popover-content .btn-xs:hover{ background-color:rgb(213,149,69);color:#FFF;}
		table#xtbn{width:386px}
		table#xtbn tr{line-height:2}
		#hutui-tpl-trigger .span-hot{position: absolute;background-color: #ed603a;color: #ffffff;top: 420px;font-size: 12px;border-radius: 5px;padding: 1px;}
		#mb-tpl-trigger .span-hot2{position: absolute;background-color: #ed603a;color: #ffffff;top: 60px;font-size: 12px;border-radius: 5px;padding: 1px;}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style id="edui-customize-v3BgDialog-style">.edui-default .edui-for-v3BgDialog .edui-dialog-content  {width:600px;height:300px;}</style>
	<style id="edui-customize-v3BdBgDialog-style">.edui-default .edui-for-v3BdBgDialog .edui-dialog-content  {width:800px;height:400px;}</style>
	<link href="../addons/tech_superarticle/inc/web/style/css/ueditor.css" type="text/css" rel="stylesheet">
	<script src="../addons/tech_superarticle/inc/web/style/js/codemirror.js" type="text/javascript" defer="defer"></script>
	<link rel="stylesheet" type="text/css" href="../addons/tech_superarticle/inc/web/style/css/codemirror.css">

    <style type="text/css">
<!--
.STYLE2 {color: #FF0000}
-->
    </style>
</head>

<body style="overflow-y: hidden; overflow-x: auto;" onselectstart="return false" oncontextmenu="return false;" class="" cz-shortcut-listen="true">
<div id="full-page" class="bg small-height" style="min-width: 1200px; margin-top: 22px; height: 352px;">
<div class="box p-r" style="margin-top: 20px;"><!--box start-->
      <div class="fl w0 p-r">

    <div class="w1 fl">
      <div class="n1">分类</div>
      <ul class="n1-1" style="height: 280px;">
        <li id="wd-tpl-trigger"><a href="#style-wd" role="tab" data-toggle="tab">我的样式</a></li>
        <li id="guanzhu-tpl-trigger" class="active"><a href="#style-guanzhu" role="tab" data-toggle="tab" aria-expanded="false">引导关注</a></li>
        <li id="title-tpl-trigger" class=""><a href="#style-title" role="tab" data-toggle="tab" aria-expanded="false">文章标题</a></li>
        <li id="body-tpl-trigger" class=""><a href="#style-body" role="tab" data-toggle="tab" aria-expanded="false">文章段落</a></li>
		<li id="pic-tpl-trigger" class=""><a href="#style-pic" role="tab" data-toggle="tab" aria-expanded="false">图文混排</a></li>
        <li id="img-tpl-trigger" class=""><a href="#style-img" role="tab" data-toggle="tab" aria-expanded="false">分割线</a></li>
		<li id="fuhao-tpl-trigger"><a href="#style-fuhao" role="tab" data-toggle="tab"><span style="color:#FF0000">符号表情</span></a></li>
		<li id="sucai-tpl-trigger"><a href="#style-sucai" role="tab" data-toggle="tab">二维码</a></li>
		<li id="scene-tpl-trigger" class=""><a href="#style-scene" role="tab" data-toggle="tab" aria-expanded="false">电商素材</a></li>
        <li id="hutui-tpl-trigger" class=""><a href="#style-hutui" role="tab" data-toggle="tab" aria-expanded="ture">动态插图</a>
		<li id="zan-tpl-trigger" class="" ><a href="#style-zan" role="tab" data-toggle="tab">点赞分享</a></li>
        <li id="yuanwen-tpl-trigger" class=""><a href="#style-yuanwen" role="tab" data-toggle="tab" aria-expanded="false">阅读原文</a></li>
        <li id="backg-tpl-trigger" class=""><a href="#style-backg" role="tab" data-toggle="tab" aria-expanded="false">文章背景</a></li>
        <li id="video-tpl-trigger" class=""><a href="#style-video" role="tab" data-toggle="tab" aria-expanded="false">音乐视频</a></li>
        <li id="jieri-tpl-trigger"><a href="#style-jieri" role="tab" data-toggle="tab"><span style="color:#FF0000">节日素材</span></a></li>
		<li id="mb-tpl-trigger" class=""><a href="#style-mb" role="tab" data-toggle="tab">全文模板</a>



      </ul>
    </div>




    <div class="w2 fl" style="background:#FFF">
        <div class="n2 ttt">
           <a href="http://editor.fzn.cc/" target="_blank"><font color="#000000">超级图文微信编辑器</font></a>
        </div>

        <div id="insert-style-list" class="item tab-content" style="height: 281px;">

<!-- 顶关注 -->
				
<div id="style-guanzhu" class="tab-pane active">
	<div class="alert alert-warning">
      <p>不会排版？点选<a href="#" id="moban" role="tab" data-toggle="tab"><font color="#FF6600">“全文模板”</font></a></li>几分钟成就高逼格文章！</p>
	</div>	
	
	
	
	<div id="guanzhu-list" class="ui-portlet clearfix ">

		<ul id="loader" class="editor-template-list ui-portlet-list">



			';
			
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=1 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '


		</ul>
	</div>			
</div>



<!-- 标题 -->

<div id="style-title" class="tab-pane">  
	<div class="alert alert-warning">
      <p>不会排版？点选<a href="#" role="tab" data-toggle="tab"><font color="#FF6600">“全文模板”</font></a></li>几分钟成就高逼格文章！</p>
	</div>	
	
	
	<div id="title-list" class="ui-portlet clearfix ">

		<ul id="loader" class="editor-template-list ui-portlet-list">

					';		
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=2 order by id desc limit 6");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo '<section class="fzneditor">';
	echo $value['code'];
	echo '</section>';
}
echo '


		</ul>
	</div>			
</div>


<!-- 卡片 -->
<div id="style-body" class="tab-pane">

<div id="body-list" class="ui-portlet clearfix ">

    <ul id="loader" class="editor-template-list ui-portlet-list">


        ';	
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=3 order by id desc limit 6");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo '<section class="fzneditor">';
	echo $value['code'];
	echo '</section>';
}
echo '
    </ul></div>
</div>



<!-- 分隔线 -->

    <div id="style-img" class="tab-pane">

        <div id="img-list" class="ui-portlet clearfix ">

            <ul id="loader" class="editor-template-list ui-portlet-list">


                ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=5 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '
            </ul></div>
    </div>

<!-- 插图 -->

<div id="style-hutui" class="tab-pane">  
<div id="hutui-list" class="ui-portlet clearfix ">
    <ul id="loader" class="editor-template-list ui-portlet-list">


        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=4 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '
    </ul>

</div>
</div>


<!-- 底提示 -->

<div id="style-yuanwen" class="tab-pane">
<div id="yuanwen-list" class="ui-portlet clearfix ">

    <ul id="loader" class="editor-template-list ui-portlet-list">


        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=6 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '

  </ul>
</div>
</div>

            <!-- 背景 -->
<div id="style-backg" class="tab-pane">
<div id="backg-list" class="ui-portlet clearfix ">
    <ul id="loader" class="editor-template-list ui-portlet-list">

        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=7 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '</ul>
</div>
</div>

<!-- 图文图片 -->
<div id="style-pic" class="tab-pane">
<div id="pic-list" class="ui-portlet clearfix ">

    <ul id="loader" class="editor-template-list ui-portlet-list">

        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=8 order by id desc limit 6");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo '<section class="fzneditor">';
	echo $value['code'];
	echo '</section>';
}
echo '
    </ul>

</div>
</div>

<!-- 场景 -->
<div id="style-scene" class="tab-pane">
<div id="scene-list" class="ui-portlet clearfix ">

    <ul id="loader" class="editor-template-list ui-portlet-list">
        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=9 order by id desc limit 6");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo '<section class="fzneditor">';
	echo $value['code'];
	echo '</section>';
}
echo '

    </ul></div>
</div>


<!-- 音视频 -->
<div id="style-video" class="tab-pane">
                <div id="video-list" class="ui-portlet clearfix ">

                    <ul id="loader" class="editor-template-list ui-portlet-list">
                        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=10 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '

                    </ul></div>
            </div>




<!-- 我的样式 -->


            <div id="style-wd" class="tab-pane">
                <div id="wd-list" class="ui-portlet clearfix ">
                    <ul id="loader" class="editor-template-list ui-portlet-list">';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_mystyle') . " order by id desc");
if (empty($res)) {
		echo "<li class='col-xs-12 brush' data-id='111111'>";
		echo '<div data-id="2901" class="editor.fzn.cc"><div class="fzn.cc" style="width:80%;border-style: solid; -webkit-border-image: url(http://weixinhow.com/images/mmbiz_png/p6Vlqvia1Uicw9zm8Viaw9OkgmZ7Rjyv7PxibrFr60hoYeDV2keH1svfykb7Ps8gIBkZKMibibXMmtADgoaxfN0N9GpQ/0.png) 20 70 20 70 fill; border-width:10px 35px 10px 35px; margin: 0px auto;" data-width="80%"><div style="margin:0px 10px;"><div class="fzn.cc" style="font-size: 18px; font-weight: 800; text-align: center; color: rgb(60, 40, 34);">右侧“存样”可自定义样式</div></div></div></div>';
}
if (!empty($res)) {
	foreach ($res as $key => $value) {
		echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
		echo '<div data-id="2901" class="editor.fzn.cc"><div class="fzn.cc" style="width:80%;border-style: solid; -webkit-border-image: url(http://weixinhow.com/images/mmbiz_png/p6Vlqvia1Uicw9zm8Viaw9OkgmZ7Rjyv7PxibrFr60hoYeDV2keH1svfykb7Ps8gIBkZKMibibXMmtADgoaxfN0N9GpQ/0.png) 20 70 20 70 fill; border-width:10px 35px 10px 35px; margin: 0px auto;" data-width="80%"><div style="margin:0px 10px;"><div class="fzn.cc" style="font-size: 18px; font-weight: 800; text-align: center; color: rgb(60, 40, 34);">'.$value['title'].'</div></div></div></div><div class="itembox" id="eeeee" style="display: none;">';
		echo $value['code'];
		echo "</div>";
	}
}
echo '

                    </ul></div>
            </div>

<!-- 模板 -->


            <div id="style-mb" class="tab-pane">
                <div id="mb-list" class="ui-portlet clearfix ">
                    <ul id="loader" class="editor-template-list ui-portlet-list">
                        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=11 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '

                    </ul></div>
            </div>




            <!-- 模板 -->


            <div id="style-zan" class="tab-pane">
                <div id="zan-list" class="ui-portlet clearfix ">

                    <ul id="loader" class="editor-template-list ui-portlet-list">
                        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=12 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '

                    </ul></div>
            </div>



            <!-- 素材图 -->


            <div id="style-sucai" class="tab-pane">
                <div id="sucai-list" class="ui-portlet clearfix ">

                    <ul id="loader" class="editor-template-list ui-portlet-list">
                        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=13 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '

                    </ul></div>
            </div>


<!-- 小符号 -->

				
<div id="style-fuhao" class="tab-pane">
	
<div id="fuhao-list" class="ui-portlet clearfix ">


		<ul id="loader" class="editor-template-list ui-portlet-list">';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=14 order by id desc");
foreach ($res as $key => $value) {
	echo $value['code'];
}
echo '

		</ul>
</div>				
</div>

 <!-- 节日 -->


            <div id="style-jieri" class="tab-pane">
                <div id="jieri-list" class="ui-portlet clearfix ">

                    <ul id="loader" class="editor-template-list ui-portlet-list">
                        ';
$res = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_wxstyle') . " where type=15 order by id desc");
foreach ($res as $key => $value) {
	echo "<li class='col-xs-12 brush' data-id='{$value['id']}'>";
	echo $value['code'];
}
echo '

                    </ul></div>
            </div>
	
				
			


  </div>
 
</div>
<div class="w3 fl">
<div class="editor2 p-r fl" style="height: 280px;"><!--editor2 start-->
<div id="editor" class="edui-default" style="width: 498px; height: 264px;"></div>
 <div class="menu">
       <div class="loginbox" id="yulank">预览</div>
	   <div class="copy-editor-htmls" title="复制内容">保存</div>
	   <div class="clear-editor" title="清空编辑器内容">清空</div> 
	   <div id="html-see" title="文章分类">存样</div>
	   <div id="tongbu" title="分享设置">分享</div> 
	   <div id="scon" title="文章基本参数设置">基本</div>
       <div id="kefu" title="自定义参数">虚拟</div>
	   <div id="kefu1" title="是否原创">原创</div>
	   <div id="kefu2" title="文章打赏">打赏</div>
	   <div id="caiji" title="采集微信文章内容">采集</div> 
	   <div id="kefu3" title="文章评论">评论</div>
	   <div id="kefu4"><a href="'.$this->createWebUrl('list').'" title="返回文章管理">返回</a></div>
      </div>
</div><!--editor2 end-->

        </div>
      </div>
    </div><!--box end-->
  </div>


<section id="color-plan" style="width:100px;position:fixed;top:128px;right:-5px;height:320px;text-align: center;">
	<div class="panel panel-primary" style="border:0 none;background: transparent;">
	<ul class="nav nav-tabs" role="tablist" id="right-fix-tab">
	  <li role="presentation"><a data-toggle="#color-choosen" href="#color-choosen" aria-controls="home">配色图</a></li>
	  <li role="presentation"><a href="#features" data-toggle="#features" aria-controls="features">魔法棒</a></li>
	  <li role="presentation"><a data-toggle="#color-choosen" href="#color-choosen" aria-controls="home">点此关闭&amp;打开本面板</a></li>
	</ul>
<div class="tab-content" style="position:absolute;right:32px;padding: 10px 0px !important;width:100px;padding:0;background:rgba(58,51,50,0.5);">
		<div id="features" role="tabpanel" class="tab-pane" style="text-align: left;padding-left:5px;">
			<small id="more-popover-content" style="font-size:12px;">
			<button class="btn btn-default btn-xs" id="set-image-radius"> 图片圆形  </button>
			<button class="btn btn-default btn-xs" id="flat-add-radius"> 加圆角</button>
			<button class="btn btn-default btn-xs" id="flat-strip-radius"> 去圆角</button>
			<button class="btn btn-default btn-xs" id="set-image-border"> 图片边框 </button>
			<button class="btn btn-default btn-xs" id="flat-add-border"> 加边框</button>
			<button class="btn btn-default btn-xs" id="flat-strip-border"> 去边框</button>
			<button class="btn btn-default btn-xs" id="flat-strip-shadow"> 去阴影 </button>
			<button class="btn btn-default btn-xs" id="flat-add-shadow"> 加阴影 </button>
			<button class="btn btn-default btn-xs" id="v3-random-color">随机换色</button>
			<button class="btn btn-default btn-xs" id="v3-random-transform">随机倾斜</button>
			</small>
		</div>
<div role="tabpanel" class="tab-pane active" id="color-choosen">
	 <div class="xiuxiu" style="background:rgba(58,51,50,0.5);border:0 none;color:#fff;"> <a href="#" target="_blank" title="">◢ 快捷面板 ◣</a></div>
	  <div class="panel-body" style="padding:0;background:rgba(58,51,50,0.5);width:100px;">
	  	 <div style="margin:5px 15px;color:#FFF;line-height:32px;text-align: center;position:relative;">
        <input id="custom-color-text" class="colorPicker form-control" style="font-size: 12px; width: 80px; color: rgb(34, 34, 34); padding: 4px 8px; height: 24px; line-height: 16px; background-color: rgb(239, 112, 96);" value="#EF7060">
        </div>
        
        <div style="margin:0 0;color:#dad9d8;line-height:32px;text-align: center;"><label style="cursor:pointer;"><input style="margin-top:8px;" type="checkbox" id="replace-color-all" value="1"> 全文换色</label></div>
	 	<ul id="favor-colors" class="clearfix" style="list-style:none;padding:0 10px 0px;margin:0 0;">
	 		 	</ul>
	 	<hr style="margin:2px 20px;border-color:#ddd;">
	    <ul class="clearfix" style="list-style:none;padding:0 10px 10px;margin:0 0;">
            <li class="color-swatch" style="background-color: #ac1d10"></li>
            <li class="color-swatch" style="background-color: #d82821;"></li>
            <li class="color-swatch active" style="background-color: #ef7060;"></li>
            <li class="color-swatch" style="background-color: #fde2d8;"></li>
            <li class="color-swatch" style="background-color: #d32a63;"></li>
            <li class="color-swatch" style="background-color: #eb6794;"></li>
            <li class="color-swatch" style="background-color: #f5bdd1;"></li>            
            <li class="color-swatch" style="background-color: #ffebf0;"></li>
            <li class="color-swatch" style="clear:left;background-color: #e2561b;"></li>
            <li class="color-swatch" style="background-color: #ff8124;"></li>
            <li class="color-swatch" style="background-color: #fcb42b;"></li>
            <li class="color-swatch" style="background-color: #feecaf;"></li>
            <li class="color-swatch" style="clear:left;background-color: #0c8918;"></li>
            <li class="color-swatch" style="background-color: #80b135;"></li>
            <li class="color-swatch" style="background-color:#c2c92a;"></li>
            <li class="color-swatch" style="background-color:#e5f3d0;"></li>
             <li class="color-swatch" style="clear:left;background-color: #1f877a;"></li>
            <li class="color-swatch" style="background-color: #27abc1;"></li>
            <li class="color-swatch" style="background-color: #5acfe1;"></li>
            <li class="color-swatch" style="background-color: #b6f2ea;"></li> 
            <li class="color-swatch" style="clear:left;background-color:#374aae;"></li>
            <li class="color-swatch" style="background-color:#1e9be8;"></li>
            <li class="color-swatch" style="background-color:#59c3f9;"></li>
            <li class="color-swatch" style="background-color:#b6e4fd;"></li>
            <li class="color-swatch" style="clear:left;background-color:#5b39b4;"></li>
            <li class="color-swatch" style="background-color: #4c6ff3;"></li>
            <li class="color-swatch" style="background-color:#91a8fc;"></li>
            <li class="color-swatch" style="background-color:#d0dafe;"></li>
            
            <!-- 紫色 -->
            <li class="color-swatch" style="clear:left;background-color:#8d4bbb;"></li>
            <li class="color-swatch" style="background-color: rgb(166, 91, 203);"></li>
            <li class="color-swatch" style="background-color:#cca4e3;"></li>
            <li class="color-swatch" style="background-color: rgb(190, 119, 99);"></li>
            
            <li class="color-swatch" data-color="#efefef" style="clear:left;background-color:#3c2822;"></li>
            <li class="color-swatch" style="background-color:#6b4d40;"></li>
            <li class="color-swatch" style="background-color:#9f887f;"></li>
            <li class="color-swatch" style="background-color:#d7ccc8;"></li>
            
        	<li class="color-swatch" style="background-color: #212122;"></li>
        	<li class="color-swatch" style="background-color: #757576;"></li>
        	<li class="color-swatch" style="background-color: #c6c6c7"></li>
        	<li class="color-swatch" style="background-color: #f5f5f4"></li>
        	
        </ul>
		<ul><!-- qq --></ul>
        </div>
	</div> 
        </div>
	</div>        
</section>


';
echo '
 <input type="text" id="testt" name="testt" value="'.$this->createWebUrl('styleajax').'" style="display:none;">
';
echo '

<!--tongbuweixin_start-->
<div id="tongbumodal" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">同步图文内容到微信公众平台</h4>
                <div><em style="color:#ff0000;font-style:normal;font-size:12px;">同步前请确认在后台管理--接口设置---微信同步设置帐号。<a href="admin/login.html" target="_blank">进入</a></em> 
                </div>
            </div>
            <div class="modal-body">
                <form id="form4" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">
                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">标题:</span>
                        <input type="text" class="form-control" id="title" name="title[]">
                    </div>
                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">作者:</span>
                        <input type="text" class="form-control" id="author" name="author[]">
                    </div>
                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">描述</span>
                        <textarea class="form-control" rows="2" id="digest" name="digest[]"  placeholder="指定分享描述（选填）"></textarea>
                    </div>
                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">原文链接</span>
                        <input type="text" class="form-control" id="type" name="link[]" placeholder="原文跳转（选填）">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">内容:</label>
                        <div style="border:1px solid #ccc;padding:20px;">
                            <div id="tbpreview" style="height:120px;overflow-y:scroll;"></div>
                            <textarea style="display:none" id="tbtb" name="content[]"></textarea>
                        </div>
                    </div>
                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">同步账号</span>
                        <select name="tbzhselect" id="tbzhselect" class="form-control">
                            <option value="" selected="selected"></option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>        
            </div>
        </div>
    </div>
</div>
<script>
    $("#tbsc").click(function(){
        $("#form4").submit();
    });
</script>
';
echo '
<!--fenxiangshezhe_sta-->
<div id="fenxiang" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">文章分享设置</h4>   
            </div>
            <div class="modal-body">
                <form id="form_fx" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">分享标题:</span>
                        <input type="text" class="form-control" id="fx_title" name="title[]" value="'.$fx_title.'">
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">分享图片:</span>
                        <input type="text" class="form-control" id="fx_img" name="title[]" placeholder="请填写图片的URL地址" value="'.$fx_img.'">
                    </div>

					<div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">分享描述</span>
                        <textarea class="form-control" rows="2" id="fx_desc" name="digest[]"  placeholder="指定分享描述（选填）">'.$fx_desc.'</textarea>
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">分享链接:</span>
                        <input type="text" class="form-control" id="fx_url" name="title[]" value="'.$fx_url.'">
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
              	<button type="button" class="btn btn-success" id="save_fx" style="height:40px;">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>        
            </div>
        </div>
    </div>
</div>

<script>
    $("#save_fx").click(function(){

	    $.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					fx_title:$("#fx_title").val(),
					fx_desc:$("#fx_desc").val(),
					fx_img:$("#fx_img").val(),
					fx_url:$("#fx_url").val()
				},
				success : function(data){
					alert(data.message);
					$("#fenxiang").modal("hide");	
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});	

    });
</script>
<!--fenxiangshezhe_end-->
';
echo '
<!--yulan_start-->
<div id="yulan" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">文章预览</h4>  
            </div>
            <div class="modal-body">
                <form id="form_yl" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">

                    <div class="input-group" style="margin-top:10px;">';
                        echo $img_url;
                        echo'
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>        
            </div>
        </div>
    </div>
</div>

<script>
    	
	$("#yulank").click(function(){

		var ht1 = current_editor.getContent();
		ht1 = ht1.replace(/<iframe/g, "<video");
		ht1 = ht1.replace(/iframe>/g, "video>");
  	    $.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					content:ht1,
					fx_title:$("#fx_title").val(),
					fx_desc:$("#fx_desc").val(),
					fx_img:$("#fx_img").val(),
					fx_url:$("#fx_url").val(),
					yueduliang:$("#xn_ydl").val(),
					dianzanliang:$("#xn_dzl").val(),
					yuanwen:$("#yc_yw").val(),
					yuanwen_link:$("#yc_ywurl").val(),
					dashang:$("#ds_ds").val(),
					gratuity_money:$("#ds_je").val(),
					is_own:$("#ds_own").val(),
					comment:$("#pl_pl").val(),
					is_comment:$("#pl_gz").val(),
					title:$("#bt_title").val(),
					author:$("#bt_au").val(),
					desc:$("#bt_desc").val(),
					thumb:$("#bt_img").val(),
					category_id:$("#fl_id").val(),
					keyword:$("#bt_kw").val(),
					z_time:$("#z_time").val()
				},
				success : function(data){
					$("#yulan").modal("show");
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});

	});	

</script>
<!--yulan_end-->
';
echo '
<!--xuni_start-->
<div id="xuni" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">文章虚拟设置</h4>   
            </div>
            <div class="modal-body">
                <form id="form_xn" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">自定义阅读量:</span>
                        <input type="text" class="form-control" id="xn_ydl" name="title[]" value="'.$yueduliang.'">
                    </div>


                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">自定义点赞量:</span>
                        <input type="text" class="form-control" id="xn_dzl" name="author[]" value="'.$dianzanliang.'">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
              	<button type="button" class="btn btn-success" id="save_xn" style="height:40px;">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>        
            </div>
        </div>
    </div>
</div>

<script>
    $("#save_xn").click(function(){

	    $.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					yueduliang:$("#xn_ydl").val(),
					dianzanliang:$("#xn_dzl").val()
				},
				success : function(data){
					alert(data.message);
					$("#xuni").modal("hide");	
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});	

    });
</script>
<!--xuni_end-->
';
echo '
<!--yuanchuang_start-->
<div id="yuanchuang" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">文章原创设置</h4>   
            </div>
            <div class="modal-body">
                <form id="form_yc" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">是否原创</span>';
                        if ($yuanwen) {
                        	echo '<select name="tbzhselect" id="yc_yw" class="form-control">
		                            <option value="1" selected="selected">是</option>
		                            <option value="0" >否</option>
		                        </select>';
                        } else {
                        	echo '<select name="tbzhselect" id="yc_yw" class="form-control">
		                            <option value="1">是</option>
		                            <option value="0" selected="selected">否</option>
		                        </select>';
                        }
                        echo'
                    </div>


                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">原文链接:</span>
                        <input type="text" class="form-control" id="yc_ywurl" name="author[]" placeholder="若选择“原创”，该链接无效" value="'.$yuanwen_link.'">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
              	<button type="button" class="btn btn-success" id="save_yc" style="height:40px;">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>        
            </div>
        </div>
    </div>
</div>

<script>
    $("#save_yc").click(function(){

	    $.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					yuanwen:$("#yc_yw").val(),
					yuanwen_link:$("#yc_ywurl").val()
				},
				success : function(data){
					alert(data.message);
					$("#yuanchuang").modal("hide");	
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});

    });
</script>
<!--yuanchuang_end-->
';
echo '
<!--dashang_start-->
<div id="dashang" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">文章打赏设置</h4>   
            </div>
            <div class="modal-body">
                <form id="form_ds" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">开启打赏</span>';
                        if ($dashang) {
                        	echo '<select name="tbzhselect" id="ds_ds" class="form-control">
		                            <option value="1" selected="selected">开启</option>
		                            <option value="0" >关闭</option>
		                        </select>';
                        } else {
                        	echo '<select name="tbzhselect" id="ds_ds" class="form-control">
		                            <option value="1">开启</option>
		                            <option value="0" selected="selected">关闭</option>
		                        </select>';
                        }
                        echo'
                    </div>


                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">打赏金额:</span>
                        <input type="text" class="form-control" id="ds_je" name="author[]" placeholder="逗号请使用英文,例如：100,200,300" value="'.$gratuity_money.'">
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">自定义金额</span>';
                        if ($is_own) {
                        	echo '<select name="tbzhselect" id="ds_own" class="form-control">
		                            <option value="1" selected="selected">开启</option>
		                            <option value="0" >关闭</option>
		                        </select>';
                        } else {
                        	echo '<select name="tbzhselect" id="ds_own" class="form-control">
		                            <option value="1">开启</option>
		                            <option value="0" selected="selected">关闭</option>
		                        </select>';
                        }
                        echo'
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              	<button type="button" class="btn btn-success" id="save_ds" style="height:40px;">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>        
            </div>
        </div>
    </div>
</div>

<script>
    $("#save_ds").click(function(){

 	    $.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					dashang:$("#ds_ds").val(),
					gratuity_money:$("#ds_je").val(),
					is_own:$("#ds_own").val()
				},
				success : function(data){
					alert(data.message);
					$("#dashang").modal("hide");	
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});

    });
</script>
<!--dashang_end-->
';
echo '
<!--pinglun_start-->
<div id="pinglun" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">文章评论设置</h4>   
            </div>
            <div class="modal-body">
                <form id="form_pl" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">开启评论</span>';
                        if ($comment) {
                        	echo '<select name="tbzhselect" id="pl_pl" class="form-control">
		                            <option value="1" selected="selected">开启</option>
		                            <option value="0" >关闭</option>
		                        </select>';
                        } else {
                        	echo '<select name="tbzhselect" id="pl_pl" class="form-control">
		                            <option value="1">开启</option>
		                            <option value="0" selected="selected">关闭</option>
		                        </select>';
                        }
                        echo'
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">关注后评论</span>';
                        if (!$is_comment) {
                        	echo '<select name="tbzhselect" id="pl_gz" class="form-control">
		                            <option value="0" selected="selected">开启</option>
		                            <option value="1" >关闭</option>
		                        </select>';
                        } else {
                        	echo '<select name="tbzhselect" id="pl_gz" class="form-control">
		                            <option value="0">开启</option>
		                            <option value="1" selected="selected">关闭</option>
		                        </select>';
                        }
                        echo'
                    </div>

                </form>
            </div>
            <div class="modal-footer">
              	<button type="button" class="btn btn-success" id="save_pl" style="height:40px;">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>        
            </div>
        </div>
    </div>
</div>

<script>
    $("#save_pl").click(function(){

 	    $.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					comment:$("#pl_pl").val(),
					is_comment:$("#pl_gz").val()
				},
				success : function(data){
					alert(data.message);
					$("#pinglun").modal("hide");	
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});

    });
</script>
<!--pinglun_end-->
';
$categorys = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_category') . " WHERE uniacid = '{$_W['uniacid']}' AND enabled = 1  ORDER BY id DESC");
echo '
<!--biaoti_start-->
<div id="biaoti" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">文章基本参数设置</h4>   
            </div>
            <div class="modal-body">
                <form id="form_bt" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">分类</span>
                        <select name="tbzhselect" id="fl_id" class="form-control">
                        	<option value="0">请选择分类</option>';
                        	$category = '';
                        	foreach ($categorys as $key => $value) {
                        		$category .= '<option value="'.$value['id'].'"';
                        		if ($category_id == $value['id']) {
                        			$category .= ' selected="selected">'.$value['name'].'</option>';
                        		} else {
                        			$category .= '>'.$value['name'].'</option>';
                        		}
                        	}
                        	echo $category;
							echo '
                        </select>
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">标题:</span>
                        <input type="text" class="form-control" id="bt_title" name="title[]" value="'.$title.'">
                    </div>


                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">作者:</span>
                        <input type="text" class="form-control" id="bt_au" name="author[]" value="'.$author.'">
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">简介:</span>
                        <input type="text" class="form-control" id="bt_desc" name="author[]" value="'.$desc.'">
                    </div>

                    <div class="input-group" style="margin-top:10px;" id="fm1">
                        <span class="input-group-addon" id="basic-addon1">封面图片:</span>
                        <input type="file" class="form-control" id="bt_img1" name="bt_img1">
                    </div>

                    <div class="input-group" style="margin-top:10px;" id="fm">
                        <span class="input-group-addon" id="basic-addon1">封面图片:</span>
                        <input type="text" class="form-control" id="bt_img" name="author[]" placeholder="请通过上方“选择文件”上传封面图片" value="'.$thumb.'">
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">虚拟发布时间:</span>
                        <input type="text" class="form-control" id="z_time" name="title[]" placeholder="时间格式：2017-03-24" value="'.$z_time.'">
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">触发关键字:</span>
                        <input type="text" class="form-control" id="bt_kw" name="author[]" placeholder="粉丝发送关键字即可收到本条图文回复" value="'.$keyword.'">
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">直接URL:</span>
                        <input type="text" class="form-control" id="y_url" name="author[]" placeholder="填写直接URL后，将直接跳转到改URL，不再进入文章详情页" value="'.$y_url.'">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
              	<button type="button" class="btn btn-success" id="save_bt" style="height:40px;">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>        
            </div>
        </div>
    </div>
</div>

<script>
    $("#save_bt").click(function(){
    	if ($("#bt_img1").val() != 0) {
    		ajaxFileUpload();
    	} else {
        	$("#fx_title").val($("#bt_title").val());
        	$("#fx_desc").val($("#bt_desc").val());
        	$("#fx_img").val($("#bt_img").val());
    		$.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					title:$("#bt_title").val(),
					author:$("#bt_au").val(),
					desc:$("#bt_desc").val(),
					thumb:$("#bt_img").val(),
					category_id:$("#fl_id").val(),
					keyword:$("#bt_kw").val(),
					fx_img:$("#fx_img").val(),
					fx_title:$("#fx_title").val(),
					fx_desc:$("#fx_desc").val(),
					y_url:$("#y_url").val(),
					z_time:$("#z_time").val()
				},
				success : function(data){
					alert(data.message);
					$("#biaoti").modal("hide");	
				},
				error:function(){
					alert("您的网络有点小问题");
				}
			});
    	}
    	
  	    

    });
</script>
<!--biaoti_end-->
';

$mystyles = pdo_fetchall("SELECT * FROM " . tablename('tech_superarticle_mystyle') . " WHERE uniacid = '{$_W['uniacid']}'  ORDER BY id DESC");
echo '

<!--wode_start-->
<div id="wode" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">我的样式管理</h4>   
            </div>
            <div class="modal-body">
                <form id="form_wd" action="admin/tbmsg.php?do=tongbu" enctype="multipart/form-data" method="post">

                   <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">样式名称:</span>
                        <input type="text" class="form-control" id="wd_title" name="title[]" value="" placeholder="请输入样式名称">
                    </div>

                    <div class="input-group" style="margin-top:10px;">
                        <span class="input-group-addon" id="basic-addon1">存储目标</span>
                        <select name="tbzhselect" id="wd_id" class="form-control">
                        	<option value="0">新建样式</option>';
                        	$mystyle = '';
                        	foreach ($mystyles as $key => $value) {
                        		$mystyle .= '<option value="'.$value['id'].'"';
                        			$mystyle .= '>覆盖“<span>'.$value['title'].'</span>”</option>';
                        	}
                        	echo $mystyle;
							echo '
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              	<button type="button" class="btn btn-success" id="save_wd" style="height:40px;">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>        
            </div>
        </div>
    </div>
</div>

<script>
	$("#wd_id").change(function(){
		var wd_tihuan = $("#wd_id").find("option:selected").text();
		wd_tihuan = wd_tihuan.substring(3,wd_tihuan.length-1);
		$("#wd_title").val(wd_tihuan);
	});
    $("#save_wd").click(function(){
		var wd_html = getEditorHtml();
	    $.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					mystyle_id:$("#wd_id").val(),
					mystyle_title:$("#wd_title").val(),
					mystyle_code:wd_html
				},
				success : function(data){
					alert(data.message);
					$("#wode").modal("hide");	
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});	


    });
</script>
<!--fenlei_end-->
';
echo 
'<!--caijistart-->
<div class="modal fade" id="weixincaiji" tabindex="999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        		<h4 class="modal-title" id="myModalLabel">微信热文采集</h4>
     		</div>
      		<div class="modal-body">
	  			<form id="form5" action="url.php?do=add" method="post"> 
					<div class="form-group">
						<label for="exampleInputEmail1">只要是微信文章即可采集</label>

						<input type="text" class="form-control" id="caijiurl" name="caijiurl" placeholder="请输入目标文章的URL地址！">
						<br>
						<label for="exampleInputEmail1">官方网站授权：</label>
						<input type="text" class="form-control" id="sq" name="sq" value="http://mp.weixin.qq.com/" readonly="readonly">
					</div>
				</form>
        	</div>
      		<div class="modal-footer">
	    		<button type="button" class="btn btn-success" id="wxcj" style="height:40px;">采集整篇文章</button>
        		<button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">关闭</button>
     		 </div>
    </div>
  </div>
</div>
<div id="message" class="modal fade" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="exampleModalLabel">消息提醒</h4>   
            </div>
            <div class="modal-body">
                正在本地化文章中的图片，请稍后...
            </div>
            <div style="clear: both;height: 30px;"></div>
        </div>
    </div>
</div>
<script>
    $("#wxcj").click(function(){
		$("#weixincaiji").modal("hide");
		$("#message").modal("show");
  	    $.ajax({
				url:"'.$this->createWebUrl('collect', array('op' => 'caiji')).'",
				type : "post",
				dataType:"json",  
				data : {
					content_url:$("#caijiurl").val(),
					sp:"1"
				},
				success : function(data){
					console.log(data);
					$("#message").modal("hide");
					$("#bt_title").val(data.art_title);
					$("#bt_img").val(data.art_thumb);
					$("#bt_desc").val(data.art_desc);
					$("#fx_title").val(data.art_title);
					$("#fx_img").val(data.art_thumb);
					$("#fx_desc").val(data.art_desc);
					alert("文章采集成功！");
					current_editor.setContent(data.data);
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});
    });
</script>
 <!-- endcaiji -->

<script type="text/javascript" src="../addons/tech_superarticle/inc/web/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="../addons/tech_superarticle/inc/web/ueditor/ueditor.all.min.js"></script>
<script src="../addons/tech_superarticle/inc/web/style/js/gjs02.js" type="text/javascript"></script>
<script src="../addons/tech_superarticle/inc/web/style/js/jquery.Jcrop.js"></script>
<link rel="stylesheet" href="../addons/tech_superarticle/inc/web/style/css/jquery.Jcrop.css" type="text/css">



   	<!--[if lt IE 9]>
          <script src="../addons/tech_superarticle/inc/web/style/js/html6.js"></script>
 	  <![endif]-->
<script src="../addons/tech_superarticle/inc/web/style/js/gjs01.js" type="text/javascript"></script>
<script type="text/javascript" src="../addons/tech_superarticle/inc/web/style/js/less-1.7.0.min.js"></script>
<script type="text/javascript" src="../addons/tech_superarticle/inc/web/style/js/ZeroClipboard.min.js"></script>
<script>
 //$("#myad").modal("show");
 $("#loginModal").modal("show");</script>
<script type="text/javascript" src="../addons/tech_superarticle/inc/web/style/js/instoo.js"></script><div id="global-zeroclipboard-html-bridge" class="global-zeroclipboard-container" style="position: absolute; left: 0px; top: -9999px; width: 1px; height: 1px; z-index: 999999999;"><object id="global-zeroclipboard-flash-bridge" name="global-zeroclipboard-flash-bridge" width="100%" height="100%" type="application/x-shockwave-flash" data="js/ueditor/third-party/zeroclipboard/ZeroClipboard.swf?noCache=1439172085753"><param name="allowScriptAccess" value="sameDomain"><param name="allowNetworking" value="all"><param name="menu" value="false"><param name="wmode" value="transparent"><div id="global-zeroclipboard-flash-bridge_fallbackContent">&nbsp;</div></object></div>

<div id="success" style="display:none;">
		<div>复制成功</div>
</div>
 <div id="tbsuccess" style="display:none;">
		<div>正在同步微信公众平台，请等待......</div>
</div>
<div id="zeroClipBoard-helper" class="hidden"></div>

<a href="#" id="toTop" style="display: none;"><span id="toTopHover"></span>To Top</a>

<script>
function shifuMouseDownMark(id) {

	var con   = $("#"+id).find("span").html();
	var range = UE.getEditor("editor").selection.getRange();

	range.select();
	
	UE.getEditor("editor").selection.getText();
	
	UE.getEditor("editor").execCommand("insertHtml",con);
}
current_editor = UE.getEditor("editor");
current_editor.ready(function(){
	//current_editor.addListener("ready", resetHandler);
	/*setTimeout(function(){
			current_editor.execCommand( "focus" );
			var editor_document = current_editor.selection.document;
			if( window.localStorage){ // 本地临时存储编辑器内容
				if(typeof window.localStorage._v3content != "undefined"){
					//alert(window.localStorage._v3content);
					//current_editor.getContent()
					if(isnew=="new"){
						setEditorHtml("'.$content1.'");
					}else{
					    setEditorHtml("'.$content1.'");
					}
					$(editor_document).find(".fzneditor").each(function(){
						//$(this).removeAttr("style");
						$(this).css({"border":"0 none","padding":"0"});
					});
				}
				if(typeof window.localStorage._edit_msg_id != "undefined"){
					current_edit_msg_id = window.localStorage._edit_msg_id;
				}
				setInterval(function(){
					window.localStorage._v3content = getEditorHtml();
				},10000);			
			}
	},100);*/
    current_editor.addListener("contentChange", function () {
            $("#preview").html(current_editor.getContent());
			$("#previews").html(current_editor.getContent());
			$("#tbpreview").html(current_editor.getContent());
	        $("#tbtb").html(current_editor.getContent());
			$("#tbcov").html(current_editor.getContent());

		document.getElementById("tbtb").value=document.getElementById("tbpreview").innerHTML;
			isout="true";
			//alert(current_editor.getContent());
        })
  	    $.ajax({
				url:"'.$this->createWebUrl('start', array('id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
				},
				success : function(data){
					current_editor.setContent(data.data.content);
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});
});
	$(".copy-editor-htmls").click(function(){
		var ht = current_editor.getContent();
		ht = ht.replace(/<iframe/g, "<video");
		ht = ht.replace(/iframe>/g, "video>");
  	    $.ajax({
				url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
				type : "post",
				dataType:"json",  
				data : {
					content:ht,
					is_save:1,
					fx_title:$("#fx_title").val(),
					fx_desc:$("#fx_desc").val(),
					fx_img:$("#fx_img").val(),
					fx_url:$("#fx_url").val(),
					yueduliang:$("#xn_ydl").val(),
					dianzanliang:$("#xn_dzl").val(),
					yuanwen:$("#yc_yw").val(),
					yuanwen_link:$("#yc_ywurl").val(),
					dashang:$("#ds_ds").val(),
					gratuity_money:$("#ds_je").val(),
					is_own:$("#ds_own").val(),
					comment:$("#pl_pl").val(),
					is_comment:$("#pl_gz").val(),
					title:$("#bt_title").val(),
					author:$("#bt_au").val(),
					desc:$("#bt_desc").val(),
					thumb:$("#bt_img").val(),
					category_id:$("#fl_id").val(),
					keyword:$("#bt_kw").val(),
					z_time:$("#z_time").val()
				},
				success : function(data){
					alert(data.message);
					window.location.href = "'.$this->createWebUrl('list').'";
				},
				error:function(){
					alert("您的网络有点小问题");
				}
		});

	});
</script>

<script type="text/javascript">
    function ajaxFileUpload() {
        $.ajaxFileUpload({
            url: "'.$this->createWebUrl('getimg').'", 
            type: "post",
            secureuri: false, //一般设置为false
            fileElementId: "bt_img1", // 上传文件的id、name属性名
            dataType: "json", //返回值类型，一般设置为json、application/json
            success: function(data, status){ 
            	if (data.path.length != 0) {
	            	$("#bt_img").val(data.path);
	            	$("#fx_img").val(data.path);
	            	$("#fx_title").val($("#bt_title").val());
	            	$("#fx_desc").val($("#bt_desc").val());
					$.ajax({
						url:"'.$this->createWebUrl('list', array('op' => 'detail', 'id' => $id)).'",
						type : "post",
						dataType:"json",  
						data : {
							title:$("#bt_title").val(),
							author:$("#bt_au").val(),
							desc:$("#bt_desc").val(),
							thumb:$("#bt_img").val(),
							category_id:$("#fl_id").val(),
							keyword:$("#bt_kw").val(),
							fx_img:$("#fx_img").val(),
							fx_title:$("#fx_title").val(),
							fx_desc:$("#fx_desc").val(),
							y_url:$("#y_url").val(),
							z_time:$("#z_time").val()
						},
						success : function(data){
							alert(data.message);
							$("#biaoti").modal("hide");	
						},
						error:function(){
							alert("您的网络有点小问题");
						}
					});
            	}
            },
            error: function(data, status, e){ 
                alert("您的网络有点小问题");
            }
        });
    }
    </script>

</body></html>';