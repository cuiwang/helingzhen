/**
 * 简单播放器
 */
MusicBox = function(p_id, p_controlId) {
	var _this = this;
	var media = document.getElementById(p_id);
	var interVal = null;
	
	$('#'+ p_controlId).addClass('play');
	
	this.source = function(p_url) {
		media.setAttribute("src", p_url);
	};
	
	//音乐播放器播放或者暂停
	this.music_play = function ()
	{
		if ($('#'+ p_controlId).hasClass('play') == true) {
			_this.play();
			_this.currentTime();
			interVal = setInterval(_this.currentTime, 1000);
			$('#'+ p_controlId).removeClass('play');
			$('#'+ p_controlId).addClass('pause');
		} else {
			_this.pause();
			clearInterval(interVal);
			$('#'+ p_controlId).removeClass('pause');
			$('#'+ p_controlId).addClass('play');
		}
	};
	//播放
	this.play = function() {
		media.play();
		
	};
	//暂停
	this.pause = function() {
		media.pause();
	};
	//停止
	this.stop = function() {
		media.pause();
	};
	//加载
	this.loadStart = function() {
		if(!isNaN(parseInt(media.duration))){
			$('#'+ p_id +'_time').html(_this.timeFormat(media.duration));
		}
	};
	//播放
	this.playing = function(p_e) {
		$("#sn_status").text("当前正在播放");
	};
	//暂停
	this.pausePaly = function() {
		$("#sn_status").text("暂停");
	};
	//加载出错
	this.loadError = function() {
		$("#sn_status").text("加载失败，可能资源不存在~");
	};
	//获取当前时间
	this.currentTime = function()
	{
		if(media.currentTime == 0){
			$.loading('正在初始化播放');
		}else if(parseInt(media.currentTime) == 1){
			$.remove_loading();
		}
		$('#'+ p_id +'_curtime').html(_this.timeFormat(media.currentTime));
		$('#'+ p_id +'_time').html(_this.timeFormat(media.duration));
	};
	
	this.timeFormat = function(p_time){
		var m = parseInt(p_time/60);
		var s = parseInt(p_time%60);
		if(m < 10){
			m = '0' + m;
		}
		if(s < 10){
			s = '0' + s;
		}
		return m + ':' + s;
	};
};