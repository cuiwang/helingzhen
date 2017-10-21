jQuery(function(){
//选项卡滑动切换通用
jQuery(function(){jQuery(".hoverTag .chgBtn").hover(function(){jQuery(this).parent().find(".chgBtn").removeClass("chgCutBtn");jQuery(this).addClass("chgCutBtn");var cutNum=jQuery(this).parent().find(".chgBtn").index(this);jQuery(this).parents(".hoverTag").find(".chgCon").hide();jQuery(this).parents(".hoverTag").find(".chgCon").eq(cutNum).show();})})

//选项卡点击切换通用
jQuery(function(){jQuery(".clickTag .chgBtn").click(function(){jQuery(this).parent().find(".chgBtn").removeClass("chgCutBtn");jQuery(this).addClass("chgCutBtn");var cutNum=jQuery(this).parent().find(".chgBtn").index(this);jQuery(this).parents(".clickTag").find(".chgCon").hide();jQuery(this).parents(".clickTag").find(".chgCon").eq(cutNum).show();})})

function autFun(){
	var mW=$(".mBan").width();
	var mBL=900/500;
	$(".mBan .slideBox .bd").css("height",mW/mBL);
	$(".mBan .slideBox .bd img").css("width",mW);
	$(".mBan .slideBox .bd img").css("height",mW/mBL);
}

function autFun2(){
	var mW2=$(".mBan2").width();
	var mBL2=900/500;
	$(".mBan2 .slideBox .bd").css("height",mW2/mBL2);
	$(".mBan2 .slideBox .bd img").css("width",mW2);
	$(".mBan2 .slideBox .bd img").css("height",mW2/mBL2);
}
setInterval(autFun,1);
setInterval(autFun2,1);

$(".mbom_ul li:last").css("border","none");

})
//屏蔽页面错误
jQuery(window).error(function(){
  return true;
});
jQuery("img").error(function(){
  $(this).hide();
});