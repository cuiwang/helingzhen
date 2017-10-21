var blockImage = {
	cutW:4,
	cutH:5,
	delay:5000,
	keeping:false,
	getRandom:function(begin,end){
		return parseInt(Math.random()*((end>begin?end-begin:begin-end)+1)+(end>begin?begin:end));	
	},
	reset:function(img){
		var all = this.parent.find('.block');
		var blockData = this._create(img);
		for(var x=0;x<blockData.length;x++){
			var e = blockData[x];
			this.parent.find(all[x]).css({left:e.left,top:e.top,width:e.width,height:e.height});
			this.parent.find(all[x]).find('img').css({left:e.imgLeft,top:e.imgTop});	
		}
	},
	_create:function(img){
		var parent = img.parentNode;
		var oneWidth = parent.offsetWidth/this.cutW;
		var oneHeight = parent.offsetHeight/this.cutH;
		var offsetLeft = img.offsetLeft;
		var offsetTop = img.offsetTop;	
		var x = 0,y = 0,len = this.cutW*this.cutH;
		var blockData = [],left,top;
		for(var i=0;i<len;i++){
			var left = offsetLeft-(x*oneWidth);
			var top = offsetTop-(y*oneHeight);
			var k = i<4?i:i%4;
			var j = i<4?0:parseInt(i/4);
			blockData.push({
				left:k*oneWidth,top:j*oneHeight,
				width:oneWidth,height:oneHeight,
				imgLeft:left,imgTop:top
			});
			x++;	
			if((i+1)%4==0){
				y++;
				x = 0;
			}
		}
		return blockData;
	},
	create:function(img){
		var parent = img.parentNode;
		var blockData = this._create(img);
		var html = '';
		for(var i=0;i<blockData.length;i++){
			var e = blockData[i];
			html += '<div class="block" style="width:'+e.width+'px; height:'+e.height+'px; left:'+e.left+'px; top:'+e.top+'px; position:absolute; z-index:9; overflow:hidden; visibility:hidden"><img src="'+img.src+'" width="'+img.width+'" height="'+img.height+'" style="position:absolute; left:'+e.imgLeft+'px; top:'+e.imgTop+'px; " /></div>';
		}
		$(html).appendTo($(parent));	
	},
	init:function(e,bool){
		this.close();
		$(window).bind('resize.blockResize',function(){
			var dir = $(e.parentNode).width()/$(e.parentNode).height();
			if($(e).width()/$(e).height()<=dir){
				$(e).css({width:'100%',height:'auto',left:0});
				var mt = ($(e).height()-$(e.parentNode).height())/-2;
				$(e).css({top:mt});			
			}else{
				$(e).css({height:'100%',width:'auto',top:0});
				var ml = ($(e).width()-$(e.parentNode).width())/-2;
				$(e).css({left:ml});				
			}
			blockImage.reset(e);			
		});
		this.parent = $(e.parentNode);
		this.create(e);
		var t = this;
		setTimeout(function(){
			blockImage.broke();
			/*
			var len = t.parent.find('.block').length;
			(function(x){
				var arg = arguments.callee;
				var dir1 = blockImage.getRandom(1,2)==1?-1:1,dir2 = blockImage.getRandom(1,2)==1?-1:1,dir3 = blockImage.getRandom(1,2)==1?-1:1,dir4 = blockImage.getRandom(1,2)==1?-1:1;
				var numX = blockImage.getRandom(50,100)*dir1;
				var numY = blockImage.getRandom(50,100)*dir2;
				var degX = blockImage.getRandom(30,80)*dir3;
				var degY = blockImage.getRandom(30,80)*dir4;
				$(t.parent.find('.block')[x]).css({
					borderColor:'rgba(0,0,0,0.8)',
					boxShadow:'0 0 20px #000',
					transform:'translate('+numX+'px,'+numY+'px) rotateY('+degX+'deg) rotateX('+degY+'deg) scale(0.5)',
					opacity:0.5						
				});
				x++;
				if(x<len){
					arg(x);			
				}else{
					setTimeout(function(){
						blockImage._run();					
					},0);	
				}
			})(0);	
			*/	
		},500);
		bool = bool==null?true:bool;
		if(!bool)
			return;
		this.keeping = true;
		setTimeout(function(){
			var arg = arguments.callee;
			if(blockImage.keeping){
				blockImage.timeout = setTimeout(function(){
					blockImage.broke(arg,blockImage.delay);			
				},blockImage.delay);
			}
		},2500);	
	},
	run:function(fn){
		var t = this;
		if(t.parent.find('.block').length==0)
			return;
		t.parent.find('.block').css({visibility:'visible'});
		var len = t.parent.find('.block').length;
		(function(x){
			if(t.parent.find('.block').length<=x)
				return blockImage.close();
			var arg = arguments.callee;
			$(t.parent.find('.block')[x]).css({
				'transition':'0.5s background, 0.2s border, 0.3s 0.1s box-shadow, 0.9s 0.3s transform, 1s 0.3s opacity',
				borderColor:'none',
				boxShadow:'0 0 0px #000',
				transform:'translate(0,0) rotateY(0deg) rotateX(0deg)  scale(1)',
				opacity:1						
			});
			x++;
			if(x<len){
				setTimeout(function(){
					arg(x);	
				},50)			
			}else{
				setTimeout(function(){
					if(fn)
						fn();
					t.parent.find('.block').css({visibility:'hidden'});
					t.parent.find('.richLeftBg').css({visibility:'visible'});				
				},1200);	
			}
		})(0);						
	},
	disperse:function(fn){
		var t = this;
		if(t.parent.find('.block').length==0)
			return;
		t.parent.find('.richLeftBg').css({visibility:'hidden'});
		t.parent.find('.block').css({visibility:'visible'});
		var len = t.parent.find('.block').length;
		(function(x){
			if(t.parent.find('.block').length<=x)
				return blockImage.close();
			var arg = arguments.callee;
			var dir1 = blockImage.getRandom(1,2)==1?-1:1,dir2 = blockImage.getRandom(1,2)==1?-1:1;
			var numX = blockImage.getRandom(50,100)*dir1;
			var numY = blockImage.getRandom(50,100)*dir2;
			var degX = blockImage.getRandom(240,540);
			var degY = blockImage.getRandom(240,540);
			$(t.parent.find('.block')[x]).css({
				'transition':'0.5s background, 0.2s border, 0.3s 0.1s box-shadow, 1.5s 0.3s transform, 2s 0.3s opacity',
				borderColor:'rgba(0,0,0,0.8)',
				boxShadow:'0 0 20px #000',
				transform:'translate('+numX+'px,'+numY+'px) rotateY('+degX+'deg) rotateX('+degY+'deg) scale(0.1)',
				opacity:0						
			});
			x++;
			if(x<len){
				setTimeout(function(){
					arg(x);	
				},50)			
			}else{
				setTimeout(function(){
					blockImage.run(fn);					
				},2000);	
			}
		})(0);		
	},
	close:function(){
		$(window).unbind('resize.blockResize');
		this.keeping = false;
		try{
			clearTimeout(this.timeout);
		}catch(ex){}
	},
	broke:function(fn){
		var t = this;
		t.parent.find('.block').css({
			visibility:'visible'			
		});
		t.parent.find('.richLeftBg').css({'visibility':'visible'});
		t.parent.find('.richLeftBg').css({
			'transform':'scale(0.5)',
			webkitTransform:'scale(0.5)'
		});
		setTimeout(function(){
			blockImage._broke();
			setTimeout(function(){
				t.parent.find('.richLeftBg').css({
					'transition':'-webkit-transform 0.8s',
					//'-webkit-transition':'-webkit-transform 0.8s',
					//transform:'scale(1)',
					webkitTransform:'scale(1)'					
				});		
			},0);
			setTimeout(function(){
				t.parent.find('.richLeftBg').css({
					'transition':'none'					
				});
				t.parent.find('.block').css({
					'transition':'0s all',
					webkitTransform:'translate(0,0) rotateY(0deg) rotateX(0deg) scale(1)',
					visibility:'hidden',
					borderColor:'none',
					boxShadow:'none',
					opacity:1	
				});	
				if(fn)
					fn();	
			},1500);				
		},50);
	},
	_broke:function(){
		var t = this;
		var all = t.parent.find('.block');
		var half = all.length/2;
		for(var x=0;x<all.length;x++){
			var isLeft = x<2?true:((x%4)<2?true:false);
			var isTop = x<=half?true:false;
			isLeft = isLeft?-1:1;
			isTop = isTop?-1:1;
			var dir1 = blockImage.getRandom(1,2)==1?-1:1,dir2 = blockImage.getRandom(1,2)==1?-1:1;
			var numX = blockImage.getRandom(50,100)*isLeft;
			var numY = blockImage.getRandom(50,100)*isTop;
			var degX = blockImage.getRandom(80,240);
			var degY = blockImage.getRandom(80,240);
			$(t.parent.find('.block')[x]).css({
				//'transition':'0.5s background, 0.2s border, 0.1s box-shadow, 1.5s transform, 1s opacity,1.5s -webkit-transform',
				'transition':'0.5s background, 0.2s border, 0.1s box-shadow, 1s opacity,1.5s -webkit-transform',
				//'-webkit-transition':'0.5s background, 0.2s border, 0.1s box-shadow, 1.5s transform, 1s opacity,1.5s -webkit-transform',
				'transition-timing-function':'ease-in-out',
				borderColor:'rgba(0,0,0,0.8)',
				boxShadow:'0 0 20px #000',
				webkitTransform:'translate('+numX+'px,'+numY+'px) rotateY('+degX+'deg) rotateX('+degY+'deg) scale(0.2)',
				opacity:0.8						
			});		
		}			
	}
}