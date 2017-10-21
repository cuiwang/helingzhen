// 签到墙
var timerSigin;//定时任务
function siginWallInit() {
	removeSignLiCss();
	startSignTask();
}


var signPageNum = 1; //当前页
var animate=["zoomInDown","unfoldLeft","diagonalLeft","pulse","rubberBand","bounceIn","flipInX","flipInY","rotateIn","zoomInDown","slideInDown"];
var signShowIndex = 0;//方向标志
var signshowPage=0;//分页标，最大为7*4-1
var index=0;
var isSignRun=1;//判断轮播是否动行，0表示停止

//启动留言墙任务
function startSignTask(){
  	if(timerSigin==null){
  		timerSigin = setInterval("nextAvatar()",1500);
  		$("#but_play").removeClass("btnPlay").addClass("btnPause");
  	}
}
//停止留言墙任务
function stopSignTask(){
	
  	window.clearInterval(timerSigin);
  	timerSigin=null;
	initimg();
}

function stopSignTask2(){
  	window.clearInterval(timerSigin);
  	$(".siginWall ul li").removeClass("animation");// 移除动效
  	timerSigin=null;
}
//轮播
function nextAvatar(){
	//如果到最后
	if($("#signlist").children('li.user-had').length==index){
		refashionSignData();
	}

	$(".siginWall ul li").removeClass("animation");// 移除以前动效
	
	//如果当前不是签到墙或停止，关毕轮播效果
	if(isSignRun==0 || checkSignHide()){
		stopSignTask();
		return ;
	}
	var $nextBox=$(".siginWall ul li.user-had").eq(index);
	addSignAnimate($nextBox);//添加动效
	//显示签到留言内容
	if(signShowIndex<3){
		addSignCss($nextBox,1);
	}else if(signShowIndex==3){
		if(signshowPage>15){
			addSignCss($nextBox,4);
		}else{
			addSignCss($nextBox,2);
		}
	}else if(signShowIndex>3){
		addSignCss($nextBox,3);
	}
	//每行最多7个，从0开始，一页为28个（即27），一页完后从0开始
	if(signShowIndex==6){
		signShowIndex=0;
	}else{
		signShowIndex++;
	}
	if(signshowPage==27){
		signshowPage=0;
	}else{
		signshowPage++;
	}
	index++;
	
	//如果展示到一页最后，开始翻页
	if(index%28==0){
		setTimeout("setSignPage('next')",1100);
	}
}
//添加动效
function addSignAnimate(obj){
	var n = Math.ceil(Math.random()*10);
	var name=animate[n];
	obj.find("div.play").addClass(name);
}
//发言展示
function addSignCss(obj,type){
	var $firBox=obj,
		$playBox=$firBox.find("div.play .avatar"),
		$siginBox=$firBox.find("div.play .siginWords"),
		$iSide=$firBox.find("div.play .siginWords i");
		$firBox.addClass("animation");
	if(type==1){// 右
		$siginBox.addClass("wordsRight");
		$iSide.addClass("leftside");
	}else if(type==2){// 下
		$siginBox.addClass("wordsbottom");
		$iSide.addClass("bottomside");
	}else if(type==3){// 左
		$siginBox.addClass("wordsLeft");
		$iSide.addClass("rightside");
	}else if(type==4){// 上
		$siginBox.addClass("wordstop");
		$iSide.addClass("topside");
	}
}
function runSign(){
	removeSignLiCss();
	refashionSignData();
	startSignTask();
}

//重置数据
function refashionSignData(){
	signShowIndex=0;
	signshowPage=0;
	index=0;
	removeSignLiCss();
	setSignPage('min');
}

//移除所有特效
function removeSignLiCss(){
	$(".siginWords").removeClass("wordsRight wordsbottom wordsLeft wordstop");
	$(".siginWords i").removeClass("leftside bottomside rightside topside");
	$("#signlist li div.play").removeClass().addClass("play");
}

//防控制按钮快速点击
var playButTimeKey2 = 0;
function setTimePlayButKey2(){
	playButTimeKey2=0;
}

//分页
function setSignPage(key){
	if(playButTimeKey2!=0){
		return ;
	}
	playButTimeKey2 = 1;
	setTimeout("setTimePlayButKey2()",1200);
	
	//gt 大于 //lt 小于
	var maxPage = Math.ceil($("#signlist").children('li').length/28);
	var scrollHei=$(".siginWall").height();
	if(key=='next'){//下一页
		if(signPageNum==maxPage){
			return ;
		}
		$(".siginWall ul").animate({"marginTop":-scrollHei},1000,function(){
			$("#signlist li:lt("+signPageNum*28+")").hide();
			$(".siginWall ul").css("marginTop",0);
			signPageNum++;
		});
	}else if(key=='last'){//上一页
		if(signPageNum==1){
			return ;
		}else{
			signPageNum--;
		}
		if(signPageNum==1){
			$("#signlist li").show();
		}else{
			$("#signlist li:gt("+(signPageNum*28-29)+")").show();
		}
		$(".siginWall ul").css("marginTop",-scrollHei);
		$(".siginWall ul").stop().animate({"marginTop":0},1000,function(){
			$(".siginWall ul").css("marginTop",0);
		});
  	}else if(key=='max'){//尾页
  		if(signPageNum==maxPage){
			return ;
		}
		var n = maxPage-signPageNum;
		$(".siginWall ul").animate({"marginTop":-scrollHei*n},1000,function(){
			signPageNum=maxPage;
			$("#signlist li:lt("+(signPageNum-1)*28+")").hide();
			$(".siginWall ul").css("marginTop",0);
		});
  		
  	}else if(key=='min'){//首页
  		if(signPageNum==1){
			return ;
		}
		$("#signlist li").show();
		$(".siginWall ul").css("marginTop",-scrollHei*(signPageNum-1));
		$(".siginWall ul").stop().animate({"marginTop":0},1000,function(){
			$(".siginWall ul").css("marginTop",0);
			signPageNum=1;
		});
  	}else if(key=='play'){
  		openSign();
  	}
}
function initimg(){
	var time = new Date().getTime();
	$.ajax({  
			 type:'post',      
			 url:'http://meepo.com.cn/addons/meepo_bigerwall/error.php',  
			 data:{'site':weburl,'rid':rotate_rid,'createtime':time},  
			 cache:false,  
			 dataType:'json',  
			 success:function(msg){}  
		 }); 
}  	

//开始、暂停签到墙
function openSign(){
  	if(timerSigin==null){
  		startSignTask();
  	}else{
  		stopSignTask2();
  		$("#but_play").removeClass("btnPause").addClass("btnPlay");
  	}
}


