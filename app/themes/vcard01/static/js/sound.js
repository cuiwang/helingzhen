//音乐播放 
	function playClicked() {

	audio_player=document.getElementById("bg_audio");
	if (audio_player.paused) {
		audio_player.play();
		 document.getElementById("music").className="musics ";
		 document.getElementById("audio_open").style.display = "block";

		
	} else {
		audio_player.pause();
			 document.getElementById("audio_open").style.display = "none";
			  document.getElementById("music").className="musics musics_play";

	}
}


// 在safri on ios里面明确指出等待用户的交互动作后才能播放media，也就是说如果你没有得到用户的action就播放的话就会被safri拦截
// 这里在只有当用户触摸过页面，才会使音乐播放
var isInit = false ;
function init(flag){
		//alert("交互");
		if(!isInit){
			if(flag)
			if (document.getElementById("bg_audio").paused && flag) {
			 document.getElementById("bg_audio").play();
			 document.getElementById("music").style.display = "block";
			}
			isInit = true;
		}
	}