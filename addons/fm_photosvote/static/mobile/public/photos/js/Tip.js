 function Tip(tit,str){
	var css = '<style id="tipstyle">*{padding:0;margin:0}\
#tipwrap{width:100%;height:100%;position:fixed;left:0;top:0;z-index:9999999;background:rgba(0,0,0,0.6);font-family:"微软雅黑"}\
#yanzheng{background:#2e0144;width:80%;position:absolute;left:10%; padding-bottom:20px;border-radius:3px;}\
#yanzheng .top{ height:30px; line-height:30px; padding:0 10px; background:url(images/bg_x1.png) repeat-x 0px 0; background-size:auto 100%; color:#fff; margin-bottom:10px;border-radius:3px;}\
#yanzheng .down{ width:90%; margin:0 auto;}\
.yibu{ background:url(images/quxiao.png) no-repeat 0px 5px; background-size:23px auto; padding-left:24px; height:30px; display:inline-block;}\
.quxiao{ width:23px; height:26px; float:right;  background:url(images/quxiao.png) no-repeat 0px -31px; background-size:21px;}\
.tishi{ line-height:24px; text-align:center; color:#fff;}\
.yanzhengma{ width:100%; height:30px; margin-top:10px;}\
.img_yzm{ width:60px; height:30px; float:left; margin-right:10px;}\
.img_yzm img{ max-width:100%;}\
.yanzhengma a{ line-height:33px; color:#fff; text-decoration:underline;}\
.input{ width:100%; height:30px; margin-top:8px;}\
.text{ width:100%; height:30px; background:#fff; border-radius:3px; color:#333; padding-left:5px; box-sizing:border-box;}\
.btns{ width:70%; margin:0 auto; margin-top:15px; overflow:hidden;}\
.queding{ padding:0 15px; height:30px; background:#e2216f; border-radius:3px; float:left; color:#fff;}\
.qx_btn{ padding:0 15px; height:30px; background:#7d7d7d; border-radius:3px; float:right; color:#fff;}\
.down_cg{ width:100%; margin:0 auto;}\
.chenggong{height:50px; margin-left:16%;}\
.duihao{ width:40px; float:left;}\
.duihao img{ max-width:100%;}\
.ganxie{ float:left; padding-left:10px;}\
.ganxie i{ display:block; text-align:center;color:#fff45c; font-size:16px;}\
.guli{ color:#e2216f; line-height:30px;}\
.sel{ width:100%; line-height:30px;}\
.inputs{ width:100%; height:30px;}\
.inputs .txte{ width:80%; height:30px; line-height:30px;color:#333; padding-left:2%; background:#fff; float:left;box-sizing:border-box;}\
.inputs .butn{ width:20%; height:30px; float:left; background:#e2216f;}\
.butn i{width:10px;height:10px;border-top:3px solid #fff;border-right:3px solid #fff;-webkit-transform:rotate(-45deg);-transform:rotate(-45deg);display:block;margin:10px auto;-webkit-transition: all 0.3s ease 0s;transition: all 0.3s ease 0s;}\
i.hide{-webkit-transform:rotate(135deg);transform:rotate(135deg);margin-top:5px;}\
.caidan{ padding:5px 10px; background:#3a0255;overflow:hidden;display:none;}\
.caidan i{ display:block; height:28px; line-height:28px; color:#fff;}\
.btnss{ width:80%; margin:0 auto; margin-top:15px; overflow:hidden;}\
.over{ padding:0 15px; height:30px; background:#e2216f; border-radius:3px; float:left; color:#fff;}\
.thanks{ padding:0 15px; height:30px; background:#7d7d7d; border-radius:3px; float:right; color:#fff;}\</style>';
	var obj = '<div id="tipwrap"><div id="yanzheng"><div class="top"><span class="yibu">'+tit+'</span><span class="quxiao"></span></div><div class="down">'+str+'</div></div></div>';
	$('head').append(css);
	$('body').append(obj);
	$('#yanzheng')[0].style.top = Math.floor($(window).height() / 4)-20+'px';
	$('#yanzheng')[0].style.marginTop = -Math.floor($("#tipbox").height()/2)+'px';
	
	
	$('#tipwrap .quxiao').click(function(){
		$('#tipwrap').remove();
		$('#tipstyle').remove();	
	})	
	$('.qx_btn').click(function(){
		$('#tipwrap').remove();
		$('#tipstyle').remove();	
	})
	
	
	
		
}