var Video = {
	getId:function(){
		return ('Video'+Math.random()+new Date().getTime()).replace('.','');
	},
	preload:function(videoList,callback,error){
		if(videoList.length==0)
			return callback([],[]);
		$('#preloadVideoBox').remove();
		var html = '<div id="preloadVideoBox" style="visibility:hidden; position:absolute; width:0; height:0; overflow:hidden;"><video id="preloadVideo"></video></div>';	
		$(html).appendTo($('body'));
		var x = 0,result = [];
		(function(){
			var arg = arguments.callee;
			$('#preloadVideo').unbind('canplaythrough').bind('canplaythrough',function(){
				var dir = Video.getData(this);
				dir.name = videoList[x];
				dir.time = parseInt($('#preloadVideo')[0].duration);
				result.push(dir);
				x++;
				if(x>=videoList.length){
					$('#preloadVideoBox').remove();
					return callback(videoList,result);
				}
				arg();		
			});
			$('#preloadVideo').unbind('stalled').bind('stalled',function(){
				if(error)
					error(this);
				videoList[x] = null;
				result.push(null);
				x++;
				if(x>=videoList.length){
					$('#preloadVideoBox').remove();
					return callback(videoList,result);
				}
				arg();	
			});
			$('#preloadVideo').unbind('error').bind('error',function(){
				if(error)
					error(this);
				videoList[x] = null;
				result.push(null);
				x++;
				if(x>=videoList.length){
					$('#preloadVideoBox').remove();
					return callback(videoList,result);
				}
				arg();
			});
			$('#preloadVideo').attr({src:videoList[x]});		
		})();
	},
	getData:function(video){
		var videoW = $(video).width();
		var videoH = $(video).height();
		var dir = videoW/videoH;
		return {dir:dir,width:videoW,height:videoH}		
	},
	fullScreen:function(video,data,father){
		if(data==null)
			return;
		father = father||window;
		var winW = $(father).width();
		var winH = $(father).height();
		var dir = winW/winH;
		if((data.width/data.height)<=dir){
			$(video).css({width:winW,height:'auto',left:0,top:'50%',marginLeft:0}); 
			var h = winW/data.dir;
			$(video).css({marginTop:h/-2});					
		}else{
			$(video).css({height:winH,width:'auto',top:0,left:'50%',marginTop:0});
			var w = winH*data.dir;
			$(video).css({marginLeft:w/-2});				
		}			
	},
	loop:{
		play:function(videoList,result){
			if(videoList==null||videoList.length==0)
				return;
			var newId = Video.getId();
			var html = '<div id="'+newId+'" class="videoPlayBox" style="width:100%; height:100%; position:absolute; left:0; top:0; overflow:hidden; z-index:-1"></div>';
			$(html).appendTo($('body'));
			$(window).bind('resize',function(){
				var obj_play = $('#'+newId+' video[mode="play"]');
				var obj_wait = $('#'+newId+' video[mode="wait"]');
				if(obj_play.length>0){	
					var index = $(obj_play).attr('index');
					Video.fullScreen(obj_play[0],result[index]);
				}
				if(obj_wait.length>0){
					var index = $(obj_wait).attr('index');
					Video.fullScreen(obj_wait[0],result[index]);	
				}
			});
			if(videoList.length==1){
				$('#'+newId).html('<video id="videoPlay1" index="0" mode="play" loop src="'+videoList[0]+'" style="position:absolute"></video>');
				Video.fullScreen($('#videoPlay1'),result[0]);
				$('#videoPlay1')[0].play();
				return;		
			}
			var index1 = 0,index2 = index1+1>=videoList.length?0:index1+1;
			var html = '<video id="videoPlay1" style="display:none;position:absolute" index="'+index1+'" mode="play"></video><video id="videoPlay2" style="display:none;position:absolute" index="'+index2+'" mode="wait"></video>';
			$('#'+newId).html(html);
			Video.fullScreen($('#videoPlay1'),result[index1]);
			$('#videoPlay1').attr({src:videoList[index1]});
			$('#videoPlay2').attr({src:videoList[index2]});
			$('#videoPlay1').show();
			$('#videoPlay1')[0].play();
			this.countTime(result[index1].time,newId,videoList,result);					
		},
		countTime:function(time,newId,videoList,result){
			var delay = time*1000;
			setTimeout(function(){
				Video.loop.oneEnd(newId,videoList,result);			
			},delay-50);		
		},
		oneEnd:function(newId,videoList,result){
			var obj_play = $('#'+newId+' video[mode="play"]')[0];
			var obj_wait = $('#'+newId+' video[mode="wait"]')[0];	
			var index = _index = $(obj_wait).attr('index');
			_index++;
			_index = _index>=videoList.length?0:_index;
			$(obj_play).hide().attr({mode:'wait',index:_index,src:videoList[_index]});
			Video.fullScreen(obj_wait,result[index]);
			$(obj_wait).show().attr({mode:'play'});
			obj_play.pause();
			obj_wait.play();		
			Video.loop.countTime(result[index].time,newId,videoList,result);			
		}
	}
}

var firstLoadVideo = {
	isClose:false,
	isFail:false,
	after:function(){},
	playOver:false,
	loadOver:false,
	start:function(src,data){
		var html = '<div id="beforeLoad" style="width:100%; height:100%; position:absolute; left:0; top:0; background:#000; z-index:9999; text-align:center;"><video src="'+src+'" style="position:absolute; visibility:hidden"></video></div>';
		$(html).appendTo($('body'));
		setTimeout(function(){
			var video = $('#beforeLoad video')[0];
			Video.fullScreen(video,data);
			$(video).css({visibility:'visible'});
			firstLoadVideo.replay(video,data.time);
			$(window).bind('resize.firstLoadVideo',function(){
				Video.fullScreen(video,data);
			});
			video.play();
		},0);
	},
	replay:function(video){
		setTimeout(function(){
			firstLoadVideo.playOver = true;	
			if(firstLoadVideo.loadOver){
				firstLoadVideo._close();	
			}
		},8000);
	},
	_close:function(){
		if(this.isClose)
			return;
		this.isClose = true;
		if($('#beforeLoad').length>0){
			$('#beforeLoad').fadeOut(function(){
				$(window).unbind('loading.firstLoadVideo');
				$('#beforeLoad').remove();
				firstLoadVideo.after();
			});			
		}else
			firstLoadVideo.after();
	},
	close:function(){
		firstLoadVideo.loadOver = true;
		if(firstLoadVideo.playOver)
			this._close();
	},
	fail:function(){
		this.isFail = true;
		firstLoadVideo.playOver = true;	
	}
}