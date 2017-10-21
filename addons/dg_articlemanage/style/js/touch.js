// JavaScript Document
function touch(id){
	var oId = document.getElementById(id);
	this.innerWarp = oId.getElementsByTagName('ul')[0];
	this.innerBtns = oId.getElementsByTagName('ul')[1];
	this.dataLi = this.innerWarp.getElementsByTagName('li');
	this.btnsLi = this.innerBtns.getElementsByTagName('li');
	this.current = 0;
	var startPos,moverPos,divStartpos;
	this.timer = null;
	this.auto = false;
	var _this = this;
	
	this.innerWarp.style.width = this.dataLi.length + '00%';
	
	this.innerWarp.addEventListener('touchstart',function(e){
		_this.touchstartFn(e);
	},false);
	
	this.innerWarp.addEventListener('touchmove',function(e){
		_this.touchmoveFn(e);
	},false);
	
	this.innerWarp.addEventListener('touchend',function(e){
		_this.touchendFn(e);
	},false);	
	
}

touch.prototype = {
	
	autoScroll : function(){
		var _this = this;
		this.timer = setInterval(function (){
			_this.show();
		}, 3000);
		this.auto = true;	
	},
	
	touchstartFn : function(e){
		//触摸开始位置
		this.startPos=parseInt(e.touches['0'].pageX)
		this.transform = this.innerWarp.style['-webkit-transform']?this.innerWarp.style['-webkit-transform']:'translate3d(0px,0,0)';
		this.translate3dLeft = this.transform.substring(this.transform.indexOf('(')+1,this.transform.indexOf(',')) ; 
		//将动画效果清除为无;
		this.applyStyle(this.innerWarp, {
			"-webkit-transition-duration": '',
			"-webkit-transition-timing-function": ''
		});
		this.divStartpos =parseInt(this.translate3dLeft) ;
	},
	
	touchmoveFn : function(e){
		//触摸移动位置
		this.moverPos = this.startPos - parseInt(e.touches['0'].pageX);
		if(Math.abs(this.moverPos)<50){
			this.moverPos = 0;	
		}
		this.innerWarp.style['-webkit-transform']='translate3d('+(this.divStartpos - this.moverPos)+'px,0,0)';
		//e.preventDefault();	
	},
	
	touchendFn : function(e){
		if(Math.abs(this.moverPos)>50){
			e.preventDefault();
			this.scroll(this.moverPos>0?'l':'r')
		}else{
			this.animal();
		}	
	},
	
	applyStyle : function(node, properties){
		var p, s = node.style;
		for (p in properties){
			s[p] = properties[p];
		}
	},
	
	scroll : function(dir){
		if(dir=='r'&&Math.abs(this.current)>0){
			this.current++;
		}else if(dir=='l'&&Math.abs(this.current)<this.dataLi.length-1){
			this.current--;
		}
		this.animal();
	},
	
	animal : function(){
		clearInterval(this.timer);	
		this.btnsLiCurrent();	
		this.warpLeft = parseInt(this.current)*320;
		this.applyStyle(this.innerWarp, {
			"-webkit-transition-duration": '400ms',
			"-webkit-transition-timing-function": 'ease-out-in'
		});
		this.innerWarp.style['-webkit-transform']='translate3d('+this.warpLeft+'px,0,0)';
		if(this.auto){
			this.autoScroll();
		}
	},
	
	show : function(){
		if(Math.abs(this.current)==this.dataLi.length-1){
			this.current=0;
			this.btnsLiCurrent();
			this.animal();
		} else {
			this.current--;
			this.animal();
			this.btnsLiCurrent();	
		}			
	},
	
	btnsLiCurrent : function(){
		for(var i=0; i<this.btnsLi.length; i++){
			this.btnsLi[i].className = '';		
		}
		this.btnsLi[Math.abs(this.current)].className = 'current';	
	}
		
}