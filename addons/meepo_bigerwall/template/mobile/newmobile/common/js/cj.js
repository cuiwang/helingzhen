var tagList ="" ;
var numList ="" ;
var buttonurl;
var hasnd;
var cj_per = new Array(); 
//var cj_per2= new Array(); 
function progressLuckBar(){
	cj_ready();
	if($("#tagid option").length==1){
	   jQuery.ajax({
			url:LUCKTAGLIST,
	    	type:"post",
	    	success:function(data){
	    		var data = JSON.parse(data);
	    		var idx=1;
	    		var color;
				
	    		if(data.luckMap.tagList!=''){
					
		    		$.each(data.luckMap.tagList,function(i,val){
		    			$("#tagid").append('<option value='+val.id+'>'+val.tag_name+'</option>');
		    			idx++;
		    		})
	    		}
				
	    		if(data.luckMap.map!=''){
		    		var title=data.luckMap.map.name;
		    		 title=title.length>5?title.substring(0,5):title;
		    		 $("#luckTitle").text(title);
		             $("#luck_img").attr("src",((data.luckMap.map.imgurl)!=''&&(data.luckMap.map.imgurl)!=null)?data.luckMap.map.imgurl:"../addons/meepo_bigerwall/template/mobile/newmobile/common/images/lotteryDefault.png");
		    		 $("#luckid").val(data.luckMap.map.id);
					 $("#tagExclude").val(data.luckMap.map.tag_exclude);
	    	}
	    	}
	    });
	}
 
    var signNum = cj_per.length;
	
    $("#may_num").html(signNum);
    $("#lucknum").text(0);
	
  getCurrentInfo();
  
}
function cj_ready() {	
   cj_per = [];
   $.ajax({
		url:CJREADY,
    	data:{},
    	type:"post",
		async:false,
		dataType:'json',  
    	success:function(json){
			if(json.length>0){
				$.each(json, function(i,v){
					cj_per.push(new Array(v));
				});
			}
    	}
    });
	
}
/*function get_can_join(option) {
	cj_per2= new Array(); 
	$.getJSON(GETCANJOIN,{'option':option},function(json){
	if(json){
		 $.each(json, function(i,v){
			cj_per2.push(new Array(v));
		});
	}	   
   });
}*/
function getCurrentInfo(){
	cj_ready();
	var option = $("#tagid option:selected").val();
	if(option>-1){
	var luckid = $("#luckid").val();
	$.ajax({
    	
		url:LUCKCONTENT,
    	data:{"luckid":luckid,"luckTag.id":option},
    	type:"post",
		async:true,
		dataType:'json',  
    	success:function(data){
			var signNum = cj_per.length;
		    var joinNum = signNum;
		  
		    $("#luckname").val(data.map.luck_name);
		    $("#tagNum").val(data.map.tagNum);
			joinNum = parseInt(signNum)-parseInt(data.map.num==undefined?0:data.map.num);
			$("#may_num").html(joinNum>0?joinNum:0);
    	}
    });

	
	getLuckUser(luckid,option);
  }
}
function tip(msg,autoClose){
	var div = $("#poptip");
	var content =$("#poptip_content");
	if(div.length<=0){
		div = $("<div id='poptip'></div>").appendTo(document.body);
		content =$("<div id='poptip_content'>" + msg + "</div>").appendTo(document.body);
	}else{
		content.html(msg);
		content.show(); div.show();
	}
	if(autoClose) {
		setTimeout(function(){
			content.fadeOut(500);
			div.fadeOut(500);
		},1000);
	}
}
function tip_close(){
	$("#poptip").fadeOut(500);
	$("#poptip_content").fadeOut(500);
}

function changeLuck(){//改变奖项
	tip('切换加载中....');
	cj_ready();
	var option = $("#tagid option:selected").val();
	if(option >0){
	    $("#num_input").show();
		$("#num_flag").show();
		$("#endNum").hide();
		
    }else{
		 $("#num_input").hide();
	}
	if(option>-1){
		var luckid = $("#luckid").val();
		$.ajax({
			url:LUCKCONTENT,
			data:{"luckid":luckid,"luckTag.id":option},
			type:"post",
			async:true,
			dataType:'json',  
			success:function(data){
				var signNum = cj_per.length;
				var joinNum = signNum;
				$("#luckname").val(data.map.luck_name);
				$("#tagNum").val(data.map.tagNum);
				$("#comment").val(data.map.luck_name);
				if(option>0){   
					 joinNum = parseInt(signNum);
					 $("#may_num").html(joinNum>0?joinNum:0);
					 getLuckUser(luckid,option);	    	
				}
			}
		});
  }
  tip_close();
}

function getLuckUser(luckid,option){
    $.ajax({
    	
		url:LUCKUSERLIST,
    	data:{"luckTag.luckid":luckid,"luckTag.id":option},
    	type:"post",
    	async:true,
    	success:function(data){
		    var data = JSON.parse(data);
		    $("#lotteryName").siblings().remove();
		    var length = data.luckMap.luckList.length;
		    if(length>0){
		    $(".lotteryDefault").hide();
            $.each(data.luckMap.luckList,function(i,val){
				
    			$("#lotteryName").after('<li id="'+val.openid+'"><p class="prize">'+val.luckName+'</p><i class="sn">'+parseInt(i+1)+'</i>'+
						'<p class="man"><img src="'+val.imgurl+'" /><div class="luck_username">'+val.name+'<br>'+val.mobile+'</div></p><i class="delLottery"  onclick=confirmLayer("'+val.openid+'",'+val.id+')></i></li>');
				
    			$(".lotteryName li").on({
					mouseenter: function(){
						$(this).addClass("act");
					},
					mouseleave: function(){
						$(this).removeClass("act");
					}
				})
            })
		    }
            $("#lucknum").text(length);
    	}
    });
}

function showLayer(i){
	$("#layer"+i).fadeIn();
	$("body").append("<div class=\"layerBlank\"></div>");
};
function closeLayer(o){
	$(o).parents(".layerStyle").hide();
	$("div").remove(".layerBlank");
};

function confirmLayer(openid,luckid){
	$("#layer2").fadeIn();
	$("body").append("<div class=\"layerBlank\"></div>");
	$("#layer2 :button:eq(0)").off().on("click",function(){
		delLuckUser(openid,luckid);
    })

};
var idx;

function delLuckUser(openid,luckid){
	var option = $("#tagid option:selected").val();
    $.ajax({
    	
		url:REMOVELUCKUSER,
    	data:{"luckUser.openid":openid,"luckUser.id":luckid,"option":option},
    	type:"post",
    	async:true,
    	success:function(data){
    		
    		var alreadyNum = $("#luck_index").children("li").length;
    		$("#lucknum").text(alreadyNum);
    		
    		var tagExclude = $("#tagExclude").val();
    		var numExclude = $("#numExclude").val();

    		var base = $("#may_num").html();
			$("#may_num").html(parseInt(base)+1>0?parseInt(base)+1:0);
   		    $("#luck_img").attr("src",CJIMGURL);
   		    $("#luck_name").empty();
			var luckid = $("#luckid").val();
			//get_can_join(option);
			cj_ready();
			getLuckUser(luckid,option);
    	}
    });
    
}


function reset(){
	var option = $("#tagid option:selected").val();
	if(option>-1){
	var alreadyNum = $("#luck_index").children("li").length;
	var tagExclude = $("#tagExclude").val();
	var numExclude = $("#numExclude").val();
	if(alreadyNum>0){
		$("#layer4").fadeIn();
		$("body").append("<div class=\"layerBlank\"></div>");
	    $("#layer4 :button:eq(0)").off().on("click",function(){
			$.ajax({
				url:RESET,
				data:{"luckTag.id":option},
				type:"post",
				async:true,
				success:function(data){
					$("#lotteryName").siblings().remove();
					$("#lucknum").text(0);
					var base = $("#may_num").html();
					$("#layer4").hide();
					$("div").remove(".layerBlank");
					cj_ready();
					var signNum = cj_per.length;
					$("#may_num").html(signNum);
					$("#luck_img").attr("src",CJIMGURL);
					$("#luck_name").empty();
				}
			});
	    })
   }
 }
}
function changeClick(){
	$("#startNum").attr('onclick','');  
	$("#startNum").unbind('click');
	$("#startNum").click(function(){  
	   alert("正在进行，不能点击");
	});  
		
	$("#endNum").attr('onclick',''); 
	$("#endNum").unbind('click');
	$("#endNum").click(function(){  
	  alert("正在进行，不能点击");
	});  
	
	$("#newLuckButton").attr('onclick','');    
	$("#newLuckButton").unbind('click');
	$("#newLuckButton").click(function(){  
	  alert("正在进行，不能点击");
	});  
	
}

function recoverClick(){
	$("#startNum").attr('onclick',''); 
	$("#endNum").attr('onclick',''); 
	$("#newLuckButton").attr('onclick','');
	
	$("#startNum").unbind('click');
	$("#endNum").unbind('click');
	$("#newLuckButton").unbind('click');
	
	$("#startNum").attr('onclick','start()');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#endNum").attr('onclick','stop()');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#newLuckButton").attr('onclick','javascript:reset();');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
}

var isChange=true;
var num;
var timer;
var numPrizeName;
var luck_num;
function start(i){
	
	$(".lotteryDefault").hide();
	var option = $("#tagid option:selected").val();
	if(option==-1){
		showLayer(1);
		return;
	}
	var alreadyNum = $("#luck_index").children("li").length;
		var tagNum = $("#tagNum").val()==""?0:$("#tagNum").val();

		if(alreadyNum !=0 && alreadyNum==parseInt(tagNum)){
			$("#tagid").removeAttr("disabled");
		    $("#startNum").val("开始抽奖");
			clearInterval(timer);
			num = 0;
			isChange = true;
		    recoverClick();
			showLayer(3);
			return;
			
		}   
		if(cj_per.length ==0){
			$("#tagid").removeAttr("disabled");
		    $("#startNum").val("开始抽奖");
			clearInterval(timer);
			num = 0;
			isChange = true;
		    recoverClick();
			
			alert('参与抽奖人数太少没法抽了！！');
			return;
			
		}   
		  
	if(i!=2){
		
		num=parseInt($("#num option:selected").val());
		var base = $("#may_num").html();
		if(parseInt(base)==0){
			showLayer(6);
			return ;
		}
		$("#tagid").attr("disabled","disabled");
		var numExclude = $("#numExclude").val();
		if(numExclude==1){
			if(num>parseInt(base)){
				num = base;
			}
		}
	}
	timer=setInterval(function(){
		changeNum();
	},120)
	$("#startNum").hide();
	$("#endNum").show();
}


function stop(){
	var option = $("#tagid option:selected").val();
	
	for(var i=0;i<cj_per.length;i++){
			var temp2 = new Array();
			temp2 = cj_per[i][0].split("|");
			var index2 = temp2[4];
			index2 = parseInt(index2);
		    if(index2 == option){
					numPrizeName = cj_per[i];
	                luck_num = i;
					var msg = new Array();
					msg = numPrizeName[0].split("|");
					$("#luck_img").attr("src",msg[2]);
					$("#luck_name").text(msg[0]);
					
					break;
			}
			
	}
	var temp3 = numPrizeName[0].split("|");
	if("undefined" != typeof temp3[4] && parseInt(temp3[4])!=option && parseInt(temp3[4]) > 0 ){
			for(var i=0;i<cj_per.length;i++){
				var temp4 = cj_per[i][0].split("|");
				if("undefined" == typeof temp4[4]){
						numPrizeName = cj_per[i];
						luck_num = i;
						var msg = new Array();
						msg = numPrizeName[0].split("|");
						$("#luck_img").attr("src",msg[2]);
						$("#luck_name").text(msg[0]);
						break;
				}
			}
			
	}
	$("#startNum").show();
	$("#endNum").hide();
	clearInterval(timer);//清除定时器 不在滚动
	var base = $("#may_num").html();
	if(num>0 && base!="0"){
    	checked();
	}else{
		return ;
	}
	num--;
	if(num>0){
    	$("#startNum").val("开始抽奖("+num+")");
	}else{
		$("#tagid").removeAttr("disabled");
		$("#startNum").val("开始抽奖");
	}
	if(num){
		if(isChange){
			changeClick();
			isChange = false;
		}
		
		setTimeout(function(){
			start(2);
		},1000);
		setTimeout(stop,2000);
	}else{
		isChange = true;
		recoverClick();
	}
}


function checked(){
	    var comment = $("#comment").val().trim();
	    if(comment==''){
	    	comment = "参与奖";
	    }
		var msg = new Array();
		msg = numPrizeName[0].split("|");
	    $.ajax({
	    	url:SAVELUCKUSER,
	    	data:{"luckUser.openid":msg[3],"luckUser.luckTagId":$("#tagid option:selected").val(),"luckUser.perAward":comment},
	    	type:"post",
	    	async:true,
	    	success:function(data){
			data = JSON.parse(data);
	    	if(parseInt(data.id)>0){
				data.id = parseInt(data.id);
	    		idx = $("#luck_index li").length;
				$("#lotteryName").after('<li id="'+msg[3]+'"><p class="prize">'+comment+'</p><i class="sn">'+parseInt(idx+1)+'</i>'+
						'<p class="man"><img src="'+msg[2]+'" /><div class="luck_username">'+msg[0]+'<br>'+data.mobile+'</div></p><i class="delLottery"  onclick=confirmLayer("'+msg[3]+'",'+data.id+');></i></li>');
				var lucknum = $("#luck_index").children("li").last().index();
				$("#lucknum").text(lucknum);
				idx++;
				$(".lotteryName li").on({
					mouseenter: function(){
						$(this).addClass("act");
					},
					mouseleave: function(){
						$(this).removeClass("act");
					}
				})
	    	  }
	    	}
	    });
		cj_per.splice(luck_num,1);
		var base = $("#may_num").html();
		$("#may_num").html(parseInt(base-1)>=0?parseInt(base-1):0);
		base = $("#may_num").html();
		if(base==0){
			showLayer(7);
			return ;
		}		
}

function changeNum(){
	var alldata = cj_per;
	var num = alldata.length - 1;
	var randomVal = Math.round(Math.random() * num);
    numPrizeName = alldata[randomVal];
	luck_num = randomVal;
	var msg = new Array();
	msg = numPrizeName[0].split("|");
	$("#luck_img").attr("src",msg[2]);
	$("#luck_name").text(msg[0]);
}