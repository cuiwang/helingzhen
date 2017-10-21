var u = navigator.userAgent,isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),videofs=$('#videofs');
$(function(){
	var _gheight=$(window).height()-($('.topimg').height()+$('#wrap').height()+$('.live-qiye-nav').height());
	$('.scrollContentBox').height(_gheight);
	$('.tabcon1').height(_gheight);
	$('.send_msg').click(function(){
		$('#speakBox').scrollTop(0);
	});
});
/*视频*/
	var istimeDispose=true;
	var $play = $('#play');
	var _play = $play[0];
	_play.addEventListener("error", function (err) {
	}, true);
	
if(isiOS){
	$play.attr('controls','controls');
}else{
	if(_play.canPlayType){
		$('#wrap').click(function(){
			if (_play.paused){
				_play.play();
			}
		});
	}
	_play.addEventListener("pause", function () {
		$('#lssRadioPlay').removeClass('lss-pause').addClass('lss-play');
		$('.videoPoster').show();
	}, false);
	_play.addEventListener("playing", function () {
		$('.videoPoster').hide();
		$('#lssRadioPlay').removeClass('lss-play').addClass('lss-pause');
	}, false);
	window.onresize = function(){
        _play.style.width = "100%";
        _play.style.height = window.innerHeight + "px";
		var player_height1 = _play.style.height;
		var titname=$('#titname'),wrap=$('#wrap'),tab=$('#tab'),speakBox=$('#speakBox'),tab1=$('#tab1');
			if(_play.paused){
				_play.style["object-position"]= "0px 0px";
				titname.hide();
				if($(window).height()>=600){
					player_height1 = player_height;
				}
				wrap.height(player_height1);
				tab.css({'height':'auto','overflow-x':'hidden','overflow-y':'hidden'});
				$('.swiper-wrapper').css({'padding-bottom':'1rem'});
				speakBox.css({'padding-bottom':'0'});
				tab1.css({'height':'auto','overflow-y':'hidden'});
			}else{
				if($(window).height()>=600){
					player_height1 = player_height;
				}
				var _height=$('#wrap').height()*1+$('.tabs').height()*1+$('#countdown').height()*1;
				tab.css({'height':$(window).height()*1-_height*1,'overflow-x':'hidden','overflow-y':'auto'});			
				wrap.height(player_height1+50);
				speakBox.css({'padding-bottom':'20rem'});
				tab1.css({'height':'100%','overflow-y':'auto'});
			}
		}
		$('.videoPoster').show();
		$play.click(function(){
			var videocontrol=$('#videocontrol');
			if(videocontrol.css('display')=='block'){
				videocontrol.css('display','none');
			}else{
				videocontrol.css('display','block');
			}
		});
		$('#lssRadioPlay').on('click', function(e){
			if(_play.canPlayType){
				if (_play.paused){
					_play.play();
					$(this).removeClass('lss-play').addClass('lss-pause');
				}else{
					_play.pause();
					$(this).removeClass('lss-pause').addClass('lss-play');
				}
			}
			e.stopPropagation();
		});
	
	_play.addEventListener("x5videoenterfullscreen", function() {
		_play.style["object-position"]= "0px 46px";
		$('#titname').show();
	});
	_play.addEventListener("x5videoexitfullscreen", function(){
		$('#wrap').css('position','relative');
		$('.videoPoster').show();
		$play.removeAttr("x5-video-orientation");
		$('.topimg,.tabs,#tab,toolmenu,.login_box,#titname').show();
		$('#videofs').removeClass('on');
		$('#videocontrol').hide();
	});
	$(document).on("click", "#videofs", function(e) {
		var t = $play.attr("x5-video-orientation");
		if(void 0 == t || "" == t){
			$('#wrap').css('position','static');
			$play.attr("x5-video-orientation", "landscape");
			_play.style["object-position"]="0px 0px";
			$('.topimg,.tabs,#tab,toolmenu,.login_box,#titname').hide();
			$(this).addClass('on');
		}else{
			$('#wrap').css('position','relative');
			$play.removeAttr("x5-video-orientation");
			$('.topimg,.tabs,#tab,toolmenu,.login_box,#titname').show();
			_play.style["object-position"]= "0px 46px";
			$(this).removeClass('on');
		}
		e.stopPropagation();
	});
}

$('.videoPoster').on('click',function(){
	if(_play.canPlayType){
		_play.play();
	}
	 $('#lssRadioPlay').removeClass('lss-play').addClass('lss-pause');
	 $(this).hide();
});

document.addEventListener("fullscreenchange", function(event) {
		var w = $('.audio-bar-area').width();
		if(document.webkitIsFullScreen){
			_play.style["object-position"]= "center 0";
			$('#titname').hide();
		}
        if(window.per){
            $('#front-bar').css('width', (window.per * w).toFixed(0) + 'px');
        }
    });
	function IsPC(){
		return navigator.userAgent.indexOf('Mobile') == -1;
	}
	   var isPc = IsPC();
    var isBarDragging = !1;
    var pc_time = null,
        move_time = "",
        move_ok = true,
        timer = null;
    //时间处理，因为时间是以为单位算的，所以这边执行格式处理一下
    function timeDispose(number) {
        var minute = parseInt(number / 60);
        var second = parseInt(number % 60);
        var hour = 0;
        if (minute >= 60) {
            hour = parseInt(number / (60 * 60));
            minute = minute - 60 * hour;
        }

        minute = minute >= 10 ? minute : "0" + minute;
        second = second >= 10 ? second : "0" + second;
        hour = hour >= 10 ? hour : "0" + hour;

        if (hour > 0) {
            return hour + ":" + minute + ":" + second;
        }
        else {
            return minute + ":" + second;
        }

    }
    function TimeAll() {
        var video_duration = _play.duration;
        if (_play.duration == Number.POSITIVE_INFINITY) {
            //video_duration = 1;
        }
        return Number(video_duration).toFixed(2);
    }
    function currentTime() {
        return Number(_play.currentTime).toFixed(2);
    }
    //时间计算
    function TimeSpan() {
        var d = $(".audio-bar-area");
        var g = d.find(".block"),
            l = d.find(".front-bar"),
            k = Number(d.width() - g.width()),//- 47
            G = Number(d.width()),//- 47
            p = g.width();

        var _ctime = timeDispose(currentTime()),
            _alltime = timeDispose(TimeAll());

        if (!isBarDragging) {
            percent = currentTime() / TimeAll();
            var d = (G - p) * percent;
            d > k && (d = k);
            percent = d / k;
            var z = G * percent;
            g.css("left", d + "px");
            l.css("width", z + "px")
            $(".audio-bar-area .block").attr("data-second", TimeAll());
            $(".audio-bar-area .block").attr("data-current", _ctime);
        }
        $("#SongTime_current").html(_ctime);
        $("#SongTime_total").html(_alltime);
    }
    $(document).on(isPc ? "mousedown" : "touchstart", ".audio-bar-area .block", function (d) {
                function h(d) {
                    d.stopPropagation();
                    d = !isPc ? d.originalEvent.touches[0].pageX : d.originalEvent.pageX;
                    d = S + (d - K);
                    0 > d && (d = 0);
                    d > x && (d = x);
                    A = d / (x - 0);
                    var g = (z * A).toFixed(0);
                    p.css("left", d + "px");
                    F.css("width", String(O * A) + "px");
                    0 <= g && g <= z && p.attr("data-current", timeDispose(g));
                    //$("#SongTime_current").text(timeDispose(g));
                }

                function g(d) {
                    d.stopPropagation();
                    p.removeClass("active");
                    $(document).off(isPc ? "mousemove" : "touchmove");
                    d = Number((V * A).toFixed(3));
                    _play.currentTime = d;
                    isBarDragging = !1;
                    $(document).off(isPc ? "mouseup" : "touchend");
                    return !1
                }
                d.stopPropagation();
                isBarDragging = !0;
                var k = $(this).parents(".audio-bar-area"),
                    p = $(this),
                    F = k.find(".front-bar"),
                    z = Number(p.data("second"));

                V = Number(_play.duration)

                var K;
                K = !isPc ? d.originalEvent.touches[0].pageX : d.originalEvent.pageX;
                var x = Number(k.width() - p.width()),
                    S = Number(p.css("left").replace("px", "")),
                    O = Number(k.width());
                p.addClass("active");
                var A;
                $(document).on(isPc ? "mousemove" : "touchmove", h);
                $(document).on(isPc ? "mouseup" : "touchend", g);
                return !1
            });
            $(document).on("click", ".audio-bar-area .block", function (d) {
                d.stopPropagation();
                return !1
            })
            //可播放检测
            _play.addEventListener("loadedmetadata",function(){
                console.log("loadedmetadata" + _play.duration);
				
            });
            //捕捉播放事件
            _play.addEventListener("playing",function(){
                videoplaystatus = 'playing';
                lastevent = 'playing';
                $("#lssRadioPlay").attr("class", "lss-pause");
                $("#lssTips").hide();
                
                var check_video_duration = setInterval(function () {
                    if (_play.duration > 0) {
                        _play.ShowProcess();
                    }
                }, 10);
            });
            _play.addEventListener("pause", function () {
                console.log("pause");
                videoplaystatus = 'pause';
                lastevent = 'pause';
            });
            _play.addEventListener("timeupdate", function () {
                
            })
	 $play.on('timeupdate', function(){
		TimeSpan();
		timeupdate();
		var _timeDispose=Number(_play.duration).toFixed(2);
		if(_timeDispose*1>0&&istimeDispose){
			istimeDispose=false;
			$("#SongTime_total").html(timeDispose(_timeDispose));
		}
    });
	function timeupdate(){
		var time = _play.currentTime.toFixed(1),
            minutes = Math.floor((time / 60) % 60),
            seconds = Math.floor(time % 60);
        if (seconds < 10) {
            seconds = '0' + seconds;
        }
        $('#SongTime_current').text(minutes + ':' + seconds);
        if (_play.ended) {
            $play.removeClass('lss-pause').addClass('lss-play');
        }
	}