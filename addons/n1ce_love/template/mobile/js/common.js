$(function(){
	//音乐控制
	$("#musicbtn").click(function(){
		var audio=document.getElementById("media");
		if($(this).hasClass("music_on")){
			//播放音乐中
			$(this).removeClass("music_on");
			audio.pause();
		}else{
			//暂停中
			$(this).addClass("music_on");
			audio.play();
		}
		return false;
	})
	//点击接听
	// $(".answer").click(function(){
	// 	window.location.href="incall.html";
	// });
	//跳转
	// $(".incall").click(function(){
	// 	window.location.href="https://www.hudngtui.com";
	// });
});

function hidePrompt(state,obj){
    switch(state){
        case 1://隐藏提示
            $(obj).parents('.prompt').remove();
            break;
        case 2://隐藏分享
            $('.share').remove();
            break;
        default:
            break;
    }
}