$(function(){
	
	$(".skinBox li").off().on("click",function(){
		$(this).addClass("check").siblings().removeClass("check")
		var type=parseInt($(this).children().attr("type"));
		var cssfile=$(this).children().attr("cssfile");
		$("#skincss").attr("href","../addons/meepo_bigerwall/template/mobile/newmobile/skin/css/"+cssfile+"?t="+new Date().getTime());
		//保存当前风格信息
//		$("#customerStyle").empty();
//		if(type==2){
			//用户自定义风格 
//			$("#customerStyle").append(".colorStyle{color:"+$(this).children().attr("fontcss")+"!important;}"
//					+".colorStyle{color: "+$(this).children().attr("fontcss")+"!important;}"
//					+".btnStyle{background:"+$(this).children().attr("btncss")+"}");
//			if($(this).children().attr("src")!="/wxscreen/web/skin/images/defaultV1.0.jpg"){
//				$("#customerStyle").append(".wrapBg{background: url("+$(this).children().attr("src")+") no-repeat center;}")
//			}
//		}
		/*$.post("/pc/saveChecked.do",{"type":type,"cssFile":cssfile}, function(
				status) {
		});*/
	})
	
});


function showShinBox(id,_this){
	$("#skinSelect .skinBox" ).hide();
	$("#"+id).show();
	$("#skinSelect .skinMenu li" ).removeClass("cur");
	$(_this).parent().addClass("cur");
}