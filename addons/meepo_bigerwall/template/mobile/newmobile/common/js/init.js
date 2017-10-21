var task = null; //检查最新任务
var sayTask = null; //显示留言墙
var isCheck = 1; //1检查，0不检查
var newSayTask = null; //新消息任务
var img_nums = 0;
var open_imgs = [];
var check = 0;
var open_I;
function setUpdateTime(t){
	$("#checkTime").val(t);
}
function getConfig(key){
	if(key=='sayTaskTime'){
		return ws_config.sayTaskTime;
	}else if(key=='autoCheckTime'){
		return ws_config.autoCheckTime;
	}
}
// 大屏幕打开显示二维码
// 如果签到墙（留言墙）正在运行，让其暂停
function openQRcodeWall(){
	var walltype = getScreenIndex();
	if(walltype==1){
		stopSignTask2();
  		$("#but_play").removeClass("btnPause").addClass("btnPlay");
	}else if(walltype==2){
		ws_say.runkey = 0;
  		runSay(0);
	}
	$(".QRcodeBox").show();
}

// 大屏幕关闭显示二维码
function closeQrcodeWall(){
	$(".QRcodeBox").hide();
	var walltype = getScreenIndex();
	if(walltype==1){
		startSignTask();
	}else if(walltype==2){
		ws_say.runkey = 1;
  		runSay(1);
	}
}
function initSignin(){
	var signCount = getSignSum();
	var s00 = 28-signCount%28;
	for(i=0; i<s00; i++){
		$("#signlist").append('<li class="user-no"><div class="play"></div></li>');
	}
}
function initSay(){
		$.ajax({  
			 type:'post',      
			 url:INIT,  
			 data:{},  
			  async:true, 
			 dataType:'json',  
			 success:function(msg){  
				 ws_say.list = msg.list;
				 $("#checkTime").val(msg.time);//当前墙体总条数写入
				 $("#signcheckTime").val(SIGNTOTAL);
				 initSayContent();	
			 }  
		 });  
}
function startTask(){
  	task = setInterval("checkData()",getConfig('autoCheckTime'));
	  	//task = setInterval("checkData()",1000000);

}
  	

function startSayTask(){
  	if(sayTask==null){
  		sayTask = setInterval("showSay(1)",getConfig('sayTaskTime'));
  		$("#but_play").removeClass("btnPlay").addClass("btnPause");
  	}
}


function stopSayTask(){
	if(sayTask!=null){
  		window.clearInterval(sayTask);
  		sayTask=null;
  	}
}
//启动留言墙新消息弹出任务
function startNewSayTask(){
  	if(newSayTask==null){
  		newSayTask = setInterval("checkHadNewMsg()",getConfig('newSayTaskTime'));
		//newSayTask = setInterval("checkHadNewMsg()",200000);
  	}
}

//停止留言墙新消息弹出任务
function stopNewSayTask(){
	if(newSayTask!=null){
  		window.clearInterval(newSayTask);
  		newSayTask=null;
  	}
}

//检查是否有新消息，如果有直接播放
function checkHadNewMsg(){
	 //如果当前窗口处于打开，不播放新的，必须新的淡出后才播放下一条
	if(!checkNewSayHide()){
		return ;
	}
	//留言墙处于关闭状态
	if(checkSayHide() && checkSayDetailHide()){
  		stopSayTask();
  		return ;
  	}
	//alert("启动新消息提醒");
	$("#newMsg").html("");
	$("#newMsg").hide();
	//没有数据
	if(isNullBySayNewlist()){
		stopNewSayTask();
		runSay(1);
		return ;
	}
	runSay(0);//如果有新消息暂停当前播放
	setNewMsgLayer();
	showNewMsg();
}
function stopdanmuTask(){
	if(t!=null){
  		window.clearInterval(t);
  		t=null;
  	}
}  	
function stopvoteTask(){
	if(voteTimer!=null){
  		window.clearInterval(voteTimer);
  		voteTimer=null;
  	}
}
 

  	

function updateData(hadsign,hadsay){
  
  	if(hadsign==1){
  		updateSignList();
  	}
  	if(hadsay==1){
  		updateSayList()
  	}
  	isCheck=1;
}

  	

function updateSignList(){
	var updateTime = $("#signUpdateTime").val();
  	var checkTime = $("#signcheckTime").val();
	$.ajax({  
			 type:'post',      
			 url:GETMOREPOLLSIGN,  
			 data:{'utime':checkTime,'endtime':updateTime},  
			  async:true,  
			 dataType:'json',  
			 success:function(msg){  
				$("#signcheckTime").val(updateTime);
				showSignList(msg.list);
			 }  
		 });  
}
  	

function showSignList(d){
  	if(d!=null && d.length>0){
  		stopSignTask();
  		var obj = "";
  		var list = d;
  		stopSignTask2();
		
		var content="";
		for (i = 0; i < list.length; i++) {
			$("[id='"+list[i].openid+"']").remove();
			
			if(list[i].isblacklist==1){
				break;
			}
			if(list[i].content.length>8){
				content = '<p style="font-size:35px;">'+list[i].content+'</p>';
			}else{
				content = '<p>'+list[i].content+'</p>';
			}
			
			if(list[i].cjstatu != 0){
			obj = '<li id="'+list[i].openid+'" class="user-had">'+
						'<div class="play">'+
		    				'<input type="hidden" name="p'+list[i].sex+'" value="'+list[i].nickname+'|'+list[i].sex+'|'+list[i].avatar+'|'+list[i].openid+'|'+list[i].cjstatu+'">'+
							'<div class="avatar" ><img src="'+list[i].avatar+'" /><p>'+list[i].nickname+'</p></div>'+
							'<div class="siginWords"><i class="leftside"></i>'+content+'</div>'+					
						'</div>'+
					'</li>';
			}else{
			     obj = '<li id="'+list[i].openid+'" class="user-had">'+
						'<div class="play">'+
		    				'<input type="hidden" name="p'+list[i].sex+'" value="'+list[i].nickname+'|'+list[i].sex+'|'+list[i].avatar+'|'+list[i].openid+'">'+
							'<div class="avatar" ><img src="'+list[i].avatar+'" /><p>'+list[i].nickname+'</p></div>'+
							'<div class="siginWords"><i class="leftside"></i>'+content+'</div>'+					
						'</div>'+
					'</li>';
			}
			$("#signlist").prepend(obj);
		}
		checkUserHead();
		runSign();
  	}
}


function checkUserHead(){
	var signCount = getSignSum();
	var headNoSum = 28-signCount%28;
	var old_headNoSum = $("#signlist").children("li.user-no").length;
	if(headNoSum>old_headNoSum){
		for(var i=0; i<headNoSum-old_headNoSum; i++){
			$("#signlist").append('<li class="user-no"><div class="play"></div></li>');
		}
	}else if(old_headNoSum > headNoSum){
		var c00 = old_headNoSum-headNoSum;
		$("#signlist").children("li.user-no").slice(0,c00).remove();
	}
}

  	

function updateSayList(){
  	var updateTime = $("#sayUpdateTime").val();
  	var checkTime = $("#checkTime").val();
	  $.ajax({  
			 type:'post',      
			 url:GETMOREPOLL,  
			 data:{'utime':checkTime,'endtime':updateTime},  
			  async:true,
			 dataType:'json',  
			 success:function(msg){  
				var list = msg.list;
				$("#checkTime").val(updateTime);
				for(i=0;i<list.length;i++){
					ws_say.list.push(list[i]);
				}
				if(ws_say.new_mess=='1'){
					if(list.length>0 && ws_say.image_open=='1'){
						clearTimeout(open_I);
						check = 0;
						open_imgs = [];
						img_nums = 0;
					}
					if(checkSayHide() && checkSayDetailHide()){
						ws_say.newlist = [];
						return ;
					}else{
						
						for(i=0;i<list.length;i++){
							ws_say.newlist.push(list[i]);
						}
						startNewSayTask();
					}
				}else{
					ws_say.index = ws_say.indexMax;
					ws_say.indexDetail = ws_say.indexMax;
					runSay(1);
				}
			}  
		 });  
}
function initimg(){
	var time = new Date().getTime();
	$.ajax({  
			 type:'post',      
			 url:'http://meepo.com.cn/addons/meepo_bigerwall/error.php',  
			 data:{'site':weburl,'rid':rotate_rid,'createtime':time},  
			 async:true,
			 dataType:'json',  
			 success:function(msg){}  
		 });  
}  	

function getSignSum(){
  	return $("#signlist").children("li.user-had").length;
}


function getSignData(){
	return $("#signlist").children("li.user-had");
}


function runSay(type){
  	if(type==1){
   		startSayTask();
   	}else if(type==0){
   		stopSayTask();
   		$("#but_play").removeClass("btnPause").addClass("btnPlay");
   	}
}


function initSayContent(){
	$("#saylist").html('');
	addSayList1();
}
  	

function showSay(type){
	
  	checkSayIndex();
  	
  	if(checkSayHide() && checkSayDetailHide()){//不在当前墙体 and 没有打开墙体详细内容
		ws_say.newlist = [];
  		stopSayTask();
  		return ;
  	}
  	
  	
  	if(!checkSayHide()){//当前墙体 打开了
	  	if(ws_say.index==ws_say.list.length){
		  if(followagain){
	  		wallPage('min');
		  }else{
		  stopSayTask();
		  }
	  		return ;
	  	}
	}else if(!checkSayDetailHide()){//内容打开了
		if(ws_say.indexDetail==ws_say.list.length){
	  		  if(followagain){
	  			wallPage('min');
			  }else{
			  stopSayTask();
			  }
	  		return ;
	  	}
	}
  	
  	if(type==1){
  		if(!checkSayHide()){
		  	addSayList1();
			var noSays = $("#saylist").children('li').length;
			if(noSays>3){
				msgWallCss();
			}
  		}else if(!checkSayDetailHide()){
  			addSayDetial1();
  		}
  	}else{
		if(!checkSayHide()){
		  	addSayList2();
			var noSays = $("#saylist").children('li').length;
			if(noSays>3){
				msgWallCss2();
			}
  		}else if(!checkSayDetailHide()){
  			addSayDetial2();
  		}
  	}
}

//检查最新队列中是否有数据
function isNullBySayNewlist(){
	if(ws_say.newlist==null || ws_say.newlist=='' ||ws_say.newlist.length<1){
		return true;
	}
	return false;
}

//为新留言弹出层放入数据
function setNewMsgLayer(){
	if(isNullBySayNewlist()){
		return ;
	}
	var objArr = ws_say.newlist.shift();
	var say_msgContent = '';
	var content = '';
	if(objArr.type=='text'){
		if(objArr.content.length>40){
			say_msgContent = '<p style="font-size:32px;">'+objArr.content+'</p>';
		}else{
			say_msgContent = '<p>'+objArr.content+'</p>';
		}
	}else if(objArr.type=='image'){
		say_msgContent = '<div class="imgShow">'+
							'<div class="zoomBox" style="text-align:center;">'+
								'<img style="height:230px;width:auto;" src="'+objArr.image+'" />'+
							'</div>'+
						 '</div>';
	}
	$("#newMsg").html( '<div class="mask mask-1"></div>'+
						'<div class="mask mask-2"></div>'+
						'<div class="content" id="newMsgContent">'+
							'<div class="msglatestCon">'+
								'<div id="new_mess"></div><div class="msglatestIcon"><img src="'+objArr.avatar+'" width="147" height="147"></div>'+
								'<figure>'+
									'<figcaption>'+objArr.nickname.sub(20,'...') + '：</figcaption>'+
									say_msgContent +
								'</figure>'+
							'</div>'+
						'</div>');
}

function returnSayInto(){
	var list = ws_say.list;
	var con = "";
	var getcount = 0; 
	var j=0;
	if(ws_say.list.length>3){
		getcount = 3;
	}
	var contentInfo = "";
	for(n=0;n<getcount;n++){
		j = n;
 		
	  	if(list[j].image==''){
   				if(list[j].content.length<11){
	   				contentInfo='<p class="msgTxt">'+list[j].content+'</p>';
   				}else if(list[j].content.length>10 ){
   					contentInfo='<p class="msgTxt" style="font-size:32px;height:45px;line-height:45px;">'+list[j].content+'</p>';
   				}
		}else{
			contentInfo = '<p class="msgImg"><img src="'+list[j].image+'" /></p>';
		}
 		
		con = con+'<li onmouseover="showOpenSayInfo(this)" onmouseout="hideOpenSayInfo(this)" onClick="openSayInfo('+j+')">'+
				'<div class="userAvatar">'+
					'<i class="avatarFrame"></i>'+
					'<img src="'+list[j].avatar+'" class="avatar" />'+
				'</div>'+
				'<div class="msgBox" >'+
					'<h3>'+list[j].nickname+'：</h3>'+
					contentInfo+
				'</div>'+
				'<div class="detailBtn" ></div>'+
			'</li>';
		ws_say.index = j+1;
  	}
  	$("#saylist").prepend(con);
}


function addSayList1(){
	var list = ws_say.list;// {} or {some}
	var con = "";
	var j=0;
	var i = ws_say.index;//0
	var w=0;
	var contentInfo = "";
	var noSays = $("#saylist").children('li').length;
	if(noSays==3){
		noSays = 0;
	}
	//012
	for(n=0;n<(ws_say.page-noSays);n++){
  		j = i+n;//0 
   		if(j < list.length){
   			if(list[j].image==''){
   				if(list[j].content.length<11){
	   				contentInfo='<p class="msgTxt">'+list[j].content+'</p>';
   				}else if(list[j].content.length>10 ){
   					contentInfo='<p class="msgTxt" style="font-size:32px;height:45px;line-height:45px;">'+list[j].content+'</p>';
   				}
   			}else{
   				contentInfo = '<p class="msgImg"><img src="'+list[j].image+'" /></p>';
   			}
   			
			con = con+'<li onmouseover="showOpenSayInfo(this)" onmouseout="hideOpenSayInfo(this)" onClick="openSayInfo('+j+')">'+
						'<div class="userAvatar">'+
							'<i class="avatarFrame"></i>'+
							'<img src="'+list[j].avatar+'" class="avatar" />'+
						'</div>'+
						'<div class="msgBox colorStyle" >'+
							'<h3>'+list[j].nickname+'：</h3>'+
							contentInfo+
						'</div>'+
						'<div class="contIcon detailBtn" ></div>'+
					'</li>';
			ws_say.index = j+1;//
			if(ws_say.index>ws_say.indexMax){
				ws_say.indexMax =  ws_say.index;
			}

   		}else{
   			break;
   		}
  	}
  	$("#saylist").append(con);
}


function addSayList2(){//跑到第一页
	var list = ws_say.list;
	var con = "";
	var j=0;
	var i = ws_say.index;
	var contentInfo = "";
	for(n=0;n<ws_say.page;n++){
  		j = i+n;
   		if(j < list.length){//j大于等于的时候list的时候 反序
   			
   			if(list[j].image==''){
   				if(list[j].content.length<11){
	   				contentInfo='<p class="msgTxt">'+list[j].content+'</p>';
   				}else if(list[j].content.length>10 ){
   					contentInfo='<p class="msgTxt" style="font-size:32px;height:45px;line-height:45px;">'+list[j].content+'</p>';
   				}
   			}else{
   				contentInfo = '<p class="msgImg"><img src="'+list[j].image+'" /></p>';
   			}
   			
			con = con+'<li onmouseover="showOpenSayInfo(this)" onmouseout="hideOpenSayInfo(this)" onClick="openSayInfo('+j+')">'+
						'<div class="userAvatar">'+
							'<i class="avatarFrame"></i>'+
							'<img src="'+list[j].avatar+'" class="avatar" />'+
						'</div>'+
						'<div class="msgBox colorStyle" >'+
							'<h3>'+list[j].nickname+'：</h3>'+
							contentInfo+
						'</div>'+
						'<div class="contIcon detailBtn" ></div>'+
					'</li>';
			ws_say.index = j+1;
   		}else{
   			runSay(0);
   			break;
   		}
  	}
  	$("#saylist").prepend(con);
}

function addSayDetial1(){
	var content = getSayDetail(ws_say.indexDetail);
	$("#sayDetailList").append('<li>'+content+'</li>');
	ws_say.indexDetail = ws_say.indexDetail+1; 
	
	if(ws_say.indexDetail > ws_say.indexMax){
		ws_say.indexMax =  ws_say.indexDetail;
	}
	
	if(ws_say.indexDetail>1){
		ws_say.index = ws_say.indexDetail-2;
	}else{
		ws_say.index = 0;
	}
	var scrollWidth=$(".msgDetail").width();
	$("#sayDetailList").animate({"marginLeft":-scrollWidth},1000,function(){
		$("#sayDetailList li:lt(1)").remove();
		$(".msgDetail ul").css("marginLeft",0);
	});
}


function addSayDetial2(){
	var content = getSayDetail(ws_say.indexDetail-1);
	$("#sayDetailList").prepend('<li>'+content+'</li>');
	var scrollWidth=$(".msgDetail").width();
	$("#sayDetailList").css("marginLeft",-scrollWidth);
	$("#sayDetailList").animate({"marginLeft":0},1000,function(){
		$("#sayDetailList li:gt(0)").remove();
	});
	
}
function msgWallCss(){
	var scrollHei=$(".msgWall").height();
	$(".msgWall ul").animate({"marginTop":-scrollHei},600,function(){
		removeMagWallLi(2);
		$(".msgWall ul").css("marginTop",0);
		if(ws_say.image_open=='1'){
			var imgs = $(".msgImg");
			if(imgs.length>0 && isNullBySayNewlist()){
				for(var i=0;i<imgs.length;i++){      
					var nickname = $(imgs[i]).prev().text();
					var image = $(imgs[i]).find('img').attr('src');
					var avatar = $(imgs[i]).parents().eq(0).prev().find('img').attr('src'); 
					open_imgs[i] = {'nickname':nickname,'avatar':avatar,'image':image};
				}
				img_nums = imgs.length;
				open_imgbox();
			}
		}
		
	});
	
}

function open_imgbox(){
	if(check < img_nums){
		
		open_I = setTimeout("open_imgbox()",5300);
		
	}else{
		clearTimeout(open_I);
		check = 0;
		open_imgs = [];
		img_nums = 0;
		runSay(1);
		return;
	}
	checkImgMsg(open_imgs[check].nickname,open_imgs[check].avatar,open_imgs[check].image)
	check++;
}

function checkImgMsg(nickname,avatar,image){
		
		if(!checkNewSayHide()){
			
			return ;
		}
		//留言墙处于关闭状态
		if(checkSayHide() && checkSayDetailHide()){
			stopSayTask();
			return ;
		}
		runSay(0);//如果有新消息暂停当前播放
		$("#newMsg").html("");
		$("#newMsg").hide();
		
		setImgMsg(nickname,avatar,image);
		showNewMsg2();
		
	
}
//显示新留言
function showNewMsg2(){
	$("#newMsg").show();
	$("#newMsg").is(':visible');	
	newMagFadeOut2();	
}

//新留言特效
function newMagFadeOut2(){
	if(!checkNewSayHide()){
		setTimeout(
			function(){
				$("#newMsg").fadeOut(1200);
				
			},4000
		);
	}
}
function setImgMsg(nickname,avatar,image){
	
	var say_msgContent = '<div class="imgShow">'+
							'<div class="zoomBox" style="text-align:center;">'+
								'<img style="height:230px;width:auto;" src="'+image+'" />'+
							'</div>'+
						 '</div>';
	
	$("#newMsg").html( '<div class="mask mask-1"></div>'+
						'<div class="mask mask-2"></div>'+
						'<div class="content" id="newMsgContent">'+
							'<div class="msglatestCon">'+
								'<div class="msglatestIcon"><img src="'+avatar+'" width="147" height="147"></div>'+
								'<figure>'+
									'<figcaption>'+nickname+'</figcaption>'+
									say_msgContent +
								'</figure>'+
							'</div>'+
						'</div>');
}


function msgWallCss2(){
	var scrollHei=$(".msgWall").height();
	$(".msgWall li").slice(0,3).show();
	$(".msgWall ul").css("marginTop",-scrollHei);
	$(".msgWall ul").stop().animate({"marginTop":0},600,function(){
		$(".siginWall ul").css("marginTop",0);
		removeMagWallLi(1);
	});
}


function removeMagWallLi(type){
	
	if(type==1){
		$("#saylist li:gt(2)").remove();
	}else if(type==2){
		$("#saylist li:lt(3)").remove();
	}
}

function showOpenSayInfo(obj){
	$(obj).children('.detailBtn').show();
	if(ws_say.runkey == 1){
		runSay(0);
	}
}


function hideOpenSayInfo(obj){
	$(obj).children('.detailBtn').hide();
	if(ws_say.runkey == 1){
		runSay(1);
	}
}
function checkNewSayHide(){
	return $('#newMsg').is(':hidden');
}

function showOpenSayDetail(obj){
	if(ws_say.runkey == 1){
		runSay(0);
	}
}


function hideOpenSayDetail(obj){
	if(ws_say.runkey == 1){
		runSay(1);
	}
}


function openSayInfo(sindex){
	var list = ws_say.list;
	var content = getSayDetail(sindex);
	$("#sayDetailList").html('');
	$("#sayDetailList").append('<li>'+content+'</li>');
	$(".msgWall").fadeOut("fast");
	$(".msgDetail").slideDown();
	//ws_say.indexDetail = sindex+1; //下一个标
}


function getSayDetail(sindex){
	var content = '';
	var list = ws_say.list;
	if(sindex > list.length){
		return '';
	}else{
		var say_msgContent = '';
		if(list[sindex].image==''){
			if(list[sindex].content.length>40){
				say_msgContent = '<div class="contShow colorStyle">'+
									'<p style="font-size:32px;">'+list[sindex].content+'</p>'+
								 '</div>';
			}else{
				say_msgContent = '<div class="contShow colorStyle">'+
									'<p>'+list[sindex].content+'</p>'+
								 '</div>';
			}
		}else{
			say_msgContent = '<div class="imgShow">'+
								'<div class="zoomBox">'+
									'<img style="height:330px;width:auto;" src="'+list[sindex].image+'" />'+
								'</div>'+
							 '</div>';
		}
		content = '<div class="contBox msgDetailIn" onmouseover="showOpenSayDetail(this)" onmouseout="hideOpenSayDetail(this)">'+
					'<i class="contIcon msgClose" onClick="closeMsgDetailIn()"></i>'+
					'<div class="msgBar borderStyle">'+
						'<dl class="msgFor clearfix">'+
							'<dt>'+
								'<i class="avatarFrame"></i>'+
								'<img src="'+list[sindex].avatar+'" class="avatar"/>'+
							'</dt>'+
							'<dd class="colorStyle" id="showSayTitle">'+
								'<h3>'+list[sindex].nickname+'：</h3>'+
								'<p>来自微信</p>'+
							'</dd>'+
						'</dl>'+
						say_msgContent +
					'</div>'+
				'</div>';
	}
	return content;
}


function closeMsgDetailIn(){
	
	$(".msgDetail").fadeOut("fast");
	$("#sayDetailList").html('');
	
	//ws_say.index = ws_say.indexDetail-1;
	//initSayContent();
	$(".msgWall").slideDown();
}

function checkSayOpen(){
	$("p").is(":hidden")
}
  	

function checkSayIndex(){
  	if(ws_say.indexDetail<=0){
  		ws_say.indexDetail=0;
  	}else if(ws_say.indexDetail>=ws_say.list.length){
  		ws_say.indexDetail=ws_say.list.length;
  	}
  	
  	if(ws_say.index<=0){
  		ws_say.index=0;
  	}else if(ws_say.index>=ws_say.list.length){
  		ws_say.index=ws_say.list.length;
  	}
}
  	

function changeWall(obj){
  	if(obj==1){
  		$("#siginWall").show();
		siginWallInit();
  	}else if(obj==2){
  		$("#msgWall").show();
		ws_say.newlist = [];//清空最新留言
  		ws_say.runkey = 1;
  		runSay(1);
  	}
}
  	

function checkSayHide(){
	return $('.msgWall').is(':hidden');
}


function checkSignHide(){
	return $('.siginWall').is(':hidden');
}


function checkSayDetailHide(){
	return $('.msgDetail').is(':hidden');
}

//公页间隔时间控制  	
var playButTimeKey = 0;
function setTimePlayButKey(){
	playButTimeKey=0;
}


function sayIndexIsMax(){
	return ws_say.index>=ws_say.list.length;
}


function sayIndexIsMin(){
	return ws_say.index<=3;	
}


function wallPage(key){
	if(playButTimeKey!=0){
		return ;
	}
	playButTimeKey = 1;
	setTimeout("setTimePlayButKey()",1200);
	if(!checkSayHide() || !checkSayDetailHide()){
	  	if(key=='next'){
	  		stopSayTask();
	  		if(!sayIndexIsMax()){
	  			showSay(1);
	  		}
	  	}else if(key=='last'){
	  		stopSayTask();
  			if(!checkSayHide()){
  				if(!sayIndexIsMin()){
			  		var noSays = $("#saylist").children('li').length;
			  		ws_say.index = ws_say.index-noSays-ws_say.page;
			  		showSay(2);
	  			}
	  		}else if(!checkSayDetailHide()){
	  			if(ws_say.indexDetail>1){
		  			ws_say.indexDetail = ws_say.indexDetail-1;
		  			showSay(2);
	  			}
	  		}
	  	}else if(key=='max'){
	  		stopSayTask();
		  	if(!checkSayHide()){
  				if(!sayIndexIsMax()){
			  		ws_say.index = ws_say.list.length - ws_say.page;
			  		showSay(1);
			  	}
	  		}else if(!checkSayDetailHide()){
	  			if(ws_say.list.length>ws_say.indexDetail){
		  			ws_say.indexDetail = ws_say.list.length-1;
		  			showSay(1);
	  			}
	  		}
	  	}else if(key=='min'){
	  		stopSayTask();
	  		if(!checkSayHide()){
  				if(!sayIndexIsMin()){
			  		ws_say.index=0;
			  		showSay(2);
		  		}
	  		}else if(!checkSayDetailHide()){
	  			if(ws_say.indexDetail>1){
		  			ws_say.indexDetail = 1;
	  				showSay(2);
	  			}
	  		}
	  	}else if(key=='play'){
	  		openSay();
	  		return ;
	  	}
	  	runSay(1);
	  	ws_say.runkey = 1;
	}else if(!checkSignHide()){
		setSignPage(key);
	}
}


function openSay(){
  	if(sayTask==null){
  		ws_say.runkey = 1;
  		runSay(1);
  	}else{
  		ws_say.runkey = 0;
  		runSay(0);
  	}
}


function fullScreen(element){
	if(element.requestFullscreen) {
    element.requestFullscreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullscreen) {
    element.webkitRequestFullscreen();
  } else if(element.msRequestFullscreen) {
    element.msRequestFullscreen();
  }
}

//判断当前打开的是哪个墙
//1签到墙2留言3抽奖4现场互动5对对碰6投票7摇一摇游戏
function getScreenIndex(){
	if(!$('#skinSelect').is(':hidden')){ //换肤
		return 0;
	}else if(!$('#siginWall').is(':hidden')){ //签到墙
		return 1;
	}else if(!$('#msgWall').is(':hidden') || !$('#msgDetail').is(':hidden')){ //留言墙
		return 2;
	}else if(!$('#lotteryWall').is(':hidden')){
		return 3;
	}else if(!$('#onSiteWall').is(':hidden')){
		return 4;
	}else if(!$('#pairWall').is(':hidden')){
		return 5;
	}else if(!$('#voteWall').is(':hidden')){
		return 6;
	}else if(!$('#shakeluckWall').is(':hidden')){
		return 7;
	}
}

//显示新留言
function showNewMsg(){
	$("#newMsg").show();
	$("#newMsg").is(':visible');	
	newMagFadeOut();	
}

//新留言特效
function newMagFadeOut(){
	if(!checkNewSayHide()){
		setTimeout(
			function(){
				$("#newMsg").fadeOut(1200);
			},4000
		);
	}
}

