// JavaScript Document
var Player = function() {
	var obj, interval, playing = false, mt, me = this, playbar;
	this.init = function(id) {
		obj = $(id)[0];
		var $obj = $(obj);
		$obj.bind('loadeddata', function() {
			mt = parseInt(obj.duration);
			updateTimer(0);
		});
		$obj.bind('play', function() {
			playing = true;
			interval = setInterval('audioPlaying()', 1000);
		});
		$obj.bind('ended', function(){
			//clearInterval(interval);
			onSoundComplete();
		});


		$obj.bind('progress', function(){
			var range = obj.buffered;
			bt = range.end(0);
			onSoundBuffering(bt);
		});
		$('div.timebar span.tl').click(function() {
			var width = $(this).css('width');
		});
		playbar = $('#playbtn');
		playbar.click(this.playpause);
	}
	this.audioPlaying = function() {
		if(playing){
			var ct = parseInt(obj.currentTime);
			updateTimer(ct);
			if(!playbar.hasClass('pausebtn')) {
				playbar.addClass('pausebtn');
			}
		}
	}
	var onSoundBuffering = function(bt) {
		var mt = parseInt(obj.duration);
		var percent = parseInt(bt * 100 / mt);
		$('#pgbuf').css('width', percent + '%');
	}
	var onSoundComplete = function() {
			if(next_id==0)
			{
				get_song(-1,open_id);
			}
			else
			{
				get_song(next_id,open_id);
			}
        
	}
	var updateTimer = function(ct) {
		mt = parseInt(obj.duration);
		var left = mt - ct;
		var min = parseInt(left / 60);
		var sec = left % 60;
		if(min < 10) {
			min = '0' + min;
		}
		if(sec < 10) {
			sec = '0' + sec;
		}
		$('#timeremain').text('-' + min + ':' + sec);
		var percent = parseInt(ct * 100 / mt);
		$('#pgtime').css('width', percent + '%');
	}
	this.play = function(ct) {
		if(ct > 0) {
			obj.currentTime=ct * obj.duration / 100;
		} else {
			obj.play();
		}
		playing = true;
		$('#playbtn').addClass('pausebtn');
		return false;
	}
	this.start = function() {
		obj.play();
		return false;
	}
	this.pause = function() {
		obj.pause();
		playing = false;
		$('#playbtn').removeClass('pausebtn');
		return false;
	}
	this.playpause = function() {
		if(playing) {
			me.pause();
		} else {
			me.play();
		}
		return false;
	}
}
function audioPlaying() {
	player.audioPlaying();
}

function initsite() {
	dwidth = $('body').width();
	dheight = $('body').height();
	$('#fmlistbox').css('left',0-dwidth);//-dwidth
}
/*$(document).ajaxStart(function() {
	$('#ajaxloader').css('margin-left', (dwidth-16)/2);
	$('#ajaxloader').css('margin-top', (dheight-16)/2);
	$('#loaderdiv').show();
});
$(document).ajaxComplete(function() {
	$('#loaderdiv').hide();
});*/

