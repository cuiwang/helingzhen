
  window.onload=function(){
	 //音乐开关 
    if($("#audio_btn").attr("url").indexOf("mp3")>1){
	  var url = $("#audio_btn").attr("url");
	  var html = '<audio loop  src="'+url+'"  id="media" autoplay="autoplay" ></audio>';
      $("#audio_btn").html(html);
	}
	$(document).one("touchstart", function(){
       //$("#media").get(0).play();
    });
}  
