var activityId=parseInt($('#activity_id').html(),10);

$("div[data-type='switch_all']").click(function(){
	var $this = $(this);
	var parent = $this.parent();
	var content = parent.find('.module-content');
	var upHtml = $this.attr('data-up') ?  $this.attr('data-up') : '收起';
	var downHtml = $this.attr('data-down') ?  $this.attr('data-down') : '查看全部';
	
	if (content.height() == 210) {
		content.height('auto');
		if( content.height() <= 210 ){
			content.height(236);
		}
		$this.html(upHtml);
	} else {
		content.height(210)
		$this.html(downHtml);
	}
});

$("div[data-type='switch_all']").each(function(){
	var father = $(this).parent();
	//内容不多，自动隐藏"查看全部"
	if(father.find('.module-content').height() <= (210)){ // 改成210 刚好两个
		father.find('.module-footer').hide();
	}else{
		father.find('.module-content').css({'overflow':'hidden', 'height':'210px', 'padding-bottom': '0'});
		father.find('.module-footer').css({'margin-top':'-1px'});
	}
});
var needle=0;//初始位置
var winH=$(window).height();
var winW=$(window).width();
var countLi=$('#infolist-box li').length;
//初始化触摸事件
var isMobile = window.navigator.userAgent.indexOf('Mobile') != -1;
var mouseDE = isMobile ? 'touchstart' : 'mousedown';
var mouseME = isMobile ? 'touchmove'  : 'mousemove';
var mouseUE   = isMobile ? 'touchend'   : 'mouseup';
var ul=$('#infolist-temp-imagecontent ul').get(0);//主容器 

function resizeImg(obj){
	var tmpImg=new Image();
	tmpImg.src=obj.attr('src');
        if(tmpImg.width>winW){
            tmpImg.width=winW;
            hzoom=winW/tmpImg.width;
            tmpImg.height=tmpImg.height*hzoom;
        }
	obj.attr('width',parseInt(tmpImg.width),10);
	obj.attr('height',parseInt(tmpImg.height),10);
	obj.css({'margin-top':((winH-tmpImg.height-85)/2),'margin-bottom':((winH-tmpImg.height-85)/2),'margin-left':((winW-tmpImg.width)/2)});
	tmpImg=undefined;
}

var contant=document.getElementById('hidden-full-view');
//时间清除
if(contant != undefined && contant!=null){
	contant.addEventListener(mouseDE,function(evt){
		var evt = evt || event;
		if(evt.preventDefault){
			evt.preventDefault();
		}
			return false;
		},false);
}
var startX=0;
function mouseD(e){
	startX=isMobile ? e.targetTouches[0].pageX : e.clientX;//起始位置
	document.addEventListener(mouseME,mouseM,false);
	document.addEventListener(mouseUE,mouseU,false);
}
function mouseM(e){
	nowX=e.clientX;
}
function mouseU(e){
	endX=isMobile ? e.changedTouches[0].pageX : e.clientX;
	var distance=endX-startX;
	if(Math.abs(distance) <= 50){
		return false;
	}
	if(distance>0){//从左向右移显示上一张
		getLeft();
	}else{
		getRight();
	}
			
	$.ajax({
		   type: "GET",
		   dataType: "JSON",
		   async: false,
		   url: "/show/ajax_getinfo/"+parseInt($('#infolist-box li[data-infolist-index="'+Math.abs(needle)+'"]').attr('data-infolist-uid'),10),
		   success: function(data){
			   
			 //fill data
				$('#hidden-full-view dd h5').html(data.data.title);
				$('#hidden-full-view dd div').html(data.data.content);
				$('#hidden-full-view dd p').html(data.data.content);
				//get first img and resize
				$('#infolist-temp-imagecontent li[data-index="'+Math.abs(needle)+'"]').html('<img src=/'+data.data.pic_url+' />')
				setTimeout(function(){
					resizeImg($('#infolist-temp-imagecontent li[data-index="'+Math.abs(needle)+'"] img'));
				},200);
				$('#temploadingimg').remove();
				//移动位置
				$('#infolist-temp-imagecontent ul').css({transition:'0.6s all ease',transform:'translateX('+(needle*winW)+'px)'});
				$('#infolist-temp-imagecontent ul').css({'-webkit-transition':'0.6s all ease','-webkit-transform':'translateX('+(needle*winW)+'px)'});


		   },
		   beforeSend:function(){
			   $('body').append('<div id="temploadingimg" style="position:fixed;left:'+(winW/2)+'px;top:'+((winH-85)/2)+'px;z-index:1000000"><img src="/images/loading_drawer.gif" /></div>');
		   }
		   
		});
	

	document.removeEventListener(mouseME,mouseM,false);
	document.removeEventListener(mouseUE,mouseU,false);
}

function getLeft(){
	if(needle == 0){
		return false;
	}else{
		needle++;
	}
}

function getRight(){
	if(needle == countLi*-1+1){
		return false;
	}else{
		needle--;
	}
}

//样式添加函数
function setStyle(obj, name, value)
{
	$(obj).css(name,value);
}

//全屏播放返回
$('#infolist-close-fullview').live('click',function(){
	$(this).next('#hidden-full-view').hide();
	$(this).hide();
})

//文本全屏展示
var isFullView=false;
$('a[data-click-type="fullview"]').bind('click touchstart',function(){
	document.removeEventListener(mouseME,mouseM,false);
	document.removeEventListener(mouseUE,mouseU,false);
	if(isFullView === false){
		document.removeEventListener(mouseDE,mouseD,false);
		$(this).parent('dd').css({height:winH,position:'absolute',width:winW,top:0});
		$(this).next('p').hide();
		$(this).next('p').next('div').show();
		$('#infolist-close-fullview').css('z-index','1000');
		isFullView=true;
	}else{
		$(this).parent('dd').removeAttr('style').css({height:'85px',position:'relative',width:winW});
		$(this).next('p').show();
		$(this).next('p').next('div').hide();
		$('#infolist-close-fullview').css('z-index','10001');
		isFullView=false;
		document.addEventListener(mouseDE,mouseD,false);
	}
	
})
