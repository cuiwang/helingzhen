var arr = new Array();

$(function() {
	initTileFn();
	
	if (arr[defaultshow] != undefined) {
		var tag = arr[defaultshow].split("_")[2];
		var title1 = arr[defaultshow].split("_")[1];
		if (tag == 1) {
			$("#countP").html(getCount(2) + "条发言")
		} else if (tag == 2) {
			$("#countP").html(getCount(1) + "人签到")
		} else if (tag == 3) {
			$("#countP").html("");
		}
	}
	
		
		if($.trim(title1).length<1){
			
			title1="扫码关注、点击菜单即可参与";
		}
		splits(title1);
	
	// 初始化气泡提示
	var toolTxt;
	$('.tooltip').mouseover(function() {
		var toolTxt = $(this).attr("title")
	}).tooltipster({
		content : toolTxt,
		theme : 'tooltipster-shadow',
		trigger : 'hover',
		delay : 0,
		speed : 200
	});
$("div.btnMenu a").click(function() {
						var boxClass = $(this).attr("data-class");
						$("." + boxClass).show().siblings().hide();
						initTileFn();
						stopdanmuTask();
						stopvoteTask();
						switch (boxClass) {
						case "skinSelect": {
							skinSelectFn();
							break;
						}
						case "siginWall": {
							// 签到1
							siginWallFn();
							break;
						}
						case "msgWall": {
							// 留言2
							msgWallFn();
							break;
						}
						case "lotteryWall": {
							// 抽奖3
							lotteryWallFn();
							break;
						}
						case "voteWall": {
							// 投票4
							voteWallFn();
							break;
						}
						case "pairWall": {
							// 对对碰5
							pairWallFn();
							break;
						}
						case "onSiteWall":{
							onSiteWllFn();
							break;
						}

						case "fullWall":{
							break;
						}
						//tanmuWall             
                        case "tanmuWall":{
						    tanmuWallFn();
							break;
						}
						case "yaoiyWall":{
							//摇一摇抽奖 yaoiyWall
							shakeluck();
							break;
						}
						case "baheWall":{
							//bahe
							bahe();
							break;
						}
						default:
							break;
						}
					});
	//快捷键
	document.onkeypress=function(event){
			   var e = event || window.event;
		       var keyCode = e.keyCode || e.which;
       			initTileFn();
				stopdanmuTask();
			    stopvoteTask();	
        switch (keyCode) {
           case 102:
               //f
               $(".skinSelect").show().siblings().hide();
               skinSelectFn();
               break;
           case 70:
           	//F
           	$(".skinSelect").show().siblings().hide();
           	skinSelectFn();
          		break;
           case 113:
               //q
                $(".siginWall").show().siblings().hide();
                siginWallFn();
               break;
           case 81:
               //Q
                $(".siginWall").show().siblings().hide();
                siginWallFn();
               break;
           case 109:
           	//m
           	 $(".msgWall").show().siblings().hide();
           	 msgWallFn();
          		break;
          	case 77:
           	//M
           	 $(".msgWall").show().siblings().hide();
           	 msgWallFn();
          		break;
           case 99:
           	//c
           	 $(".lotteryWall").show().siblings().hide();
           	 lotteryWallFn();
           	break;
   	    case 67:
           	//C
           	 $(".lotteryWall").show().siblings().hide();
           	 lotteryWallFn();
           	break;
       	case 116:
       		//t
       		 $(".voteWall").show().siblings().hide();
       		 voteWallFn();
       		break;
   		case 84:
       		//T
       		 $(".voteWall").show().siblings().hide();
       		 voteWallFn();
       		break;
       	case 100:
           	//d
           	 $(".pairWall").show().siblings().hide();
           	 pairWallFn();
           	break;
       	case 68:
           	//d
           	 $(".pairWall").show().siblings().hide();
           	 pairWallFn();
           	break;
       	case 120:
       		//x
       		 $(".onSiteWall").show().siblings().hide();
       		 onSiteWllFn();
       		break;
   		case 80:
       		//x
       		 $(".onSiteWall").show().siblings().hide();
       		 onSiteWllFn();
       		break;
		
		case 110:
			$(".tanmuWall").show().siblings().hide();
		    tanmuWallFn();
			break;
		case 78:
			$(".tanmuWall").show().siblings().hide();
		    tanmuWallFn();
			break;
		case 89://Y
   	    	shakeluck();
   	    	break;
   		case 121://y
	    	shakeluck();
	    	break;
		case 66://Y
   	    	bahe();
   	    	break;
   		case 98://y
	    	bahe();
	    	break;
        default:
        break;
        }	
	}
	function initTileFn(){
		$.ajax({  
			 type:'post',      
			 url:HEADMSG,  
			 data:{},  
			 async:false,
			 dataType:'json',  
			 success:function(data){  
				$.each(data.headmessage, function(i, item) {
						arr[item.wallid] = item.wallid + "_"
						+ item.title + '_' + item.tag;
				});
			}  
		 });  
	}
	//换肤
	function skinSelectFn(){
		if(arr[defaultshow] != undefined) {
			var title1 = arr[defaultshow].split("_")[1];
		}else{
			var title1 = '扫码关注、点击菜单即可参与';
	    }
	   splits(title1);
	}
	//签到
	function siginWallFn(){
		var title1="扫码关注、点击菜单即可参与";
		if (arr[1] != undefined) {
		var tag = arr[1].split("_")[2];
		if($.trim(arr[1].split("_")[1]).length>=1)
		{
			var title1 = arr[1].split("_")[1];
		}
		if (tag == 1) {
		$("#countP").html(getCount(2) + "条发言")
		
		} else if (tag == 2) {
		$("#countP").html(getCount(1) + "人签到")
		}
		if (tag == 3) {
		$("#countP").html("");
		}
		}
		splits(title1);
		siginWallInit();
	}
	//留言
	function msgWallFn(){
		changeWall(2);
		var title="扫码关注、点击菜单即可参与";
		if (arr[2] != undefined) {
			var tag = arr[2].split("_")[2];
			if(arr[2].split("_")[1].length>=1){
				title=arr[2].split("_")[1];
			}
			if (tag == 1) {
				$("#countP").html(getCount(2) + "条发言")

			} else if (tag == 2) {
				$("#countP").html(getCount(1) + "人签到")
			}
			if (tag == 3) {
				$("#countP").html("");
			}

		}
		splits(title);
	}
	//抽奖
	function lotteryWallFn(){
		var title="扫码关注、点击菜单即可参与";
		if (arr[3] != undefined) {
		var tag = arr[3].split("_")[2];
		if($.trim(arr[3].split("_")[1]).length>=1)
		{
			var title = arr[3].split("_")[1];
		}
		if (tag == 1) {
		$("#countP").html(getCount(2) + "条发言")
		
		}
		if (tag == 2) {
		$("#countP").html(getCount(1) + "人签到")
		}
		if (tag == 3) {
		$("#countP").html("");
		}
		}
		
		splits(title);
		progressLuckBar();
	}	//投票
	
	//对对碰
	function pairWallFn(){
		var title="扫码关注、点击菜单即可参与";
		if (arr[4] != undefined) {

			var tag = arr[4].split("_")[2];
			//$("#title").html(title);
			if($.trim(arr[4].split("_")[1]).length>=1)
			{
				var title = arr[4].split("_")[1];
			}
			if (tag == 1) {
				$("#countP").html(getCount(2) + "条发言")
			}
			if (tag == 2) {
				$("#countP").html(getCount(1) + "人签到")
			}
			if (tag == 3) {
				$("#countP").html("");
			}
		}
		splits(title);
		
		progressPairBar();
	}
	
	function onSiteWllFn(){
		var title="扫码关注、点击菜单即可参与";
		progressInteract();
		if (arr[5] != undefined) {
			var tag = arr[5].split("_")[2];
			
			if($.trim(arr[5].split("_")[1]).length<1)
			{
				var title = arr[5].split("_")[1];
			}
			if (tag == 1) {
				$("#countP").html(getCount(2) + "条发言")
			}
			if (tag == 2) {
				$("#countP").html(getCount(1) + "人签到")
			}
		}
		splits(title);
		
	}
					
	function voteWallFn(){
			
					progressBar();
					if(voteTimer==null){
					   voteTimer = setInterval(progressBar, voterefreshtime);
					}
			
			var title="扫码关注、点击菜单即可参与";;
			if (arr[6] != undefined) {
				var tag = arr[6].split("_")[2];
				if($.trim(arr[6].split("_")[1]).length>=1)
				{
				var title = arr[6].split("_")[1];
				}
				if (tag == 1) {
					$("#countP").html(getCount(2) + "条发言")
				}
				if (tag == 2) {
					$("#countP").html(getCount(1) + "人签到")
				}
				if (tag == 3) {
					$("#countP").html("");
				}
			}
			splits(title);
			
		}				
	function tanmuWallFn(){
		    var title="扫码关注、点击菜单即可参与";;
			if (arr[7] != undefined) {
				var tag = arr[7].split("_")[2];
				if($.trim(arr[7].split("_")[1]).length>=1)
				{
					var title = arr[7].split("_")[1];
				}
				if (tag == 1) {
					$("#countP").html(getCount(2) + "条发言")
				}
				if (tag == 2) {
					$("#countP").html(getCount(1) + "人签到")
				}
				if (tag == 3) {
					$("#countP").html("");
				}
			}
			splits(title);
			tanmuQuerywall();
	}
	function shakeluck(){
		window.open(YYY_WALL);
	}
	function bahe(){
		window.open(BAHE_WALL);
	}
	$(".msgDetail").on({
		mouseenter : function() {
			$(this).find(".msgClose").show();
		},
		mouseleave : function() {
			$(this).find(".msgClose").hide();
		}
	})
	$(".msgClose").on("click", function() {
		timerMsg = setInterval(msgWall, 2000);
		$(".msgDetail").hide();
		$(".msgWall").fadeIn();
	})
	function getCount(type){
			if(type==1){
				return $("#signlist").children('li.user-had').length;
			}else if(type==2){
				return ws_say.list.length;
			}
		}
	function splits(title){
		var msgs='';
		 $("#title").siblings().remove();
		 $("#title li").remove();
		for(var i=0; i<=title.length;)
			{
			var msgs1=title.substring(i,i+17);
			var msgs2=title.substring(i+17,i+34);
			  $("#title").append('<li>'+msgs1+'<br>'+msgs2+'</li>');
			  i+=34;
			}
	}
})