var $ResultSeed;
var Players;
var Winers;
var audio_Running,
audio_GetOne;
var tagList ="" ;
var numList ="" ;
var buttonurl;
var hasnd;
var cj_per = []; 
var resizePart = window.WBActivity.resize = function() {};
var start = window.WBActivity.start = function() {
    window.WBActivity.hideLoading();
    /*var b = document.getElementById("Audio_Running");
    if (b.play) {
        audio_Running = b
    }
    var a = document.getElementById("Audio_Result");
    if (a.play) {
        audio_GetOne = a
    }*/
    $(".usercount-label").html("加载数据中...");
    $(".control").hide();
	cj_ready();
	progressLuckBar();
    /*$.getJSON(PATH_ACTIVITY + Path_url('lottory_user'), {
        rid: scene_id
    },
    function(d) {
        if (d && d.ret == 0 && $.isArray(d.data)) {
            Players = d.data;
            var c = Players.length;
            $(".usercount-label").html(c + "人");
            $(".control.button-run").fadeIn()
        } else {
            alert("数据异常，无法进行抽奖，请刷新！")
        }
    }).fail(function() {
        alert("无法连接服务器，请重试")
    });*/
    $(".Panel.Top").css({
        top: 0
    });
    $(".Panel.Bottom").css({
        bottom: 0
    });
    $(".Panel.Lottery").css({
        display: "block",
        opacity: 1
    });
	
    $ResultSeed = $(".lottery-right .result-line");
    $(".control.button-run").on("click", 
    function() {
        start_lottory()
    });
    $(".control.button-stop").on("click", 
    function() {
        stop_lottory()
    });
   
};
var getUser = function(id,f) {
    /*if (audio_GetOne) {
        audio_GetOne.play()
    }*/
    $(".lottery-right").scrollTop(0);
    var b = $(".lottery-right").scroll(0).children(".result-line").length - 1;
    var a = $ResultSeed.clone();
	a.addClass('had_luck_user');
    a.find(".result-num").html((b + 1));
    a.prependTo(".lottery-right").slideDown();
    var e = a.offset();
    var c = $(".lottery-run .user");
    var d = c.clone().appendTo("body").css({
        position: "absolute",
        top: c.offset().top,
        left: c.offset().left,
        width: c.width(),
        height: c.height()
    }).animate({
        width: 60,
        height: 60,
        top: e.top + 5,
        left: e.left + 50
    },
    500, 
    function() {
        var g = d.css("background-image");
        d.appendTo(a).removeAttr("style").css({
            "background-image": g
        });
		a.find("i").attr('onclick','delLuckUser('+id+');');
        if ($.isFunction(f)) {
            f.call(this)
        }
    })
};
/*var start_game = function() {
    console.log(Players);
    winer_count = $(".select-value").text() * 1;
    if (winer_count <= Players.length) {
        $(".control.button-run").hide();
        flgPlaying = true;
        playanimate();
        if (audio_Running) {
            audio_Running.play()
        }
        window.setTimeout(function() {
            $(".control.button-stop").fadeIn()
        },
        500)
    } else {
        alert("计划选" + winer_count + "人，但是只剩" + Players.length + "人可选，请减少选取数！")
    }
};
var stop_game = function() {
    $(".control.button-stop").hide();
    if ($.isArray(Players)) {
        winer_count = $(".select-value").text() * 1;
        if (winer_count <= Players.length) {
            getWiner()
        } else {
            alert("计划选" + winer_count + "人，但是只剩" + Players.length + "人可选，请减少选取数！")
        }
    } else {
        alert("无法获得游戏数据，与游戏服务器断开，请刷新重试！")
    }
};
var winer_count = 0;
var getWiner = function() {
    flgPlaying = false;
    window.clearTimeout(tmr_playanimate);
    var b = Math.floor(Math.random() * Players.length);
    var a = Players.splice(b, 1)[0];
    $(".usercount-label").html(Players.length + "人");
    $(".lottery-run .user").css({
        "background-image": "url(" + a.avatar + ")"
    });
    $(".lottery-run .user .nick-name").html(a.nick_name);
    window.setTimeout(function() {
        getUser(function() {
            winer_count--;
            if (winer_count > 0) {
                flgPlaying = true;
                playanimate();
                window.setTimeout(function() {
                    getWiner()
                },
                1500)
            } else {
                if (audio_Running) {
                    audio_Running.pause()
                }
                $(".control.button-run").fadeIn()
            }
        })
    },
    500)
};
var curr_index = 0;
var flgPlaying = false;
var tmr_playanimate;
var playanimate = function() {
    if (Players[curr_index]) {
        var a = Players[curr_index];
        $(".lottery-run .user").css({
            "background-image": "url(" + a.avatar + ")"
        });
        $(".lottery-run .user .nick-name").html(a.nick_name);
        curr_index++;
        if (curr_index >= Players.length) {
            curr_index = 0
        }
        if (flgPlaying) {
            tmr_playanimate = window.setTimeout(playanimate, 100)
        }
    }
};*/
 
var progressLuckBar = function(){
	   $.ajax({
			url:PATH_ACTIVITY + Path_url('lottory_taglist'),
			data:{rid: scene_id},
	    	type:"post",
			dataType:'json',
	    	success:function(d){
	    		if(d.result == 0 && d.data.length > 0){
		    		$.each(d.data,function(i,val){
		    			$("#tagid").append('<option value='+val.id+'>'+val.tag_name+'</option>');
		    		})
	    		}
				var user = cj_per.length;
				$(".usercount-label").html(user + "人");
				$(".control.button-run").fadeIn();
	    	}
	    });
  
}
var cj_ready = function () {	
   cj_per = [];
   $.ajax({
		url:PATH_ACTIVITY + Path_url('lottory_user'),
    	data:{rid: scene_id},
    	type:"post",
		dataType:'json', 
		async:false,
    	success:function(json){
			if(json.ret==0 && json.data.length>0){
				cj_per = json.data;
			}
    	}
    });
}

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

function changeLuck(obj){//改变奖项
	var option = obj;
	if(option==-1){
		 return;
	}
	$("#loading").show();
	$(".lottery-right .had_luck_user").remove();
	//cj_ready();
	var user = cj_per.length;
	$(".usercount-label").html(user + "人");
	$(".control.button-run").fadeIn();
	
		$.ajax({
			url:PATH_ACTIVITY + Path_url('lottory_one_award'),
			data:{"rid":scene_id,"award_id":option},
			type:"post",
			async:true,
			dataType:'json',  
			success:function(d){
				$(".lottery-run .user").css({
					"background-image": "url(" + d.data.luck_img + ")"
				});
				$(".lottery-run .user .nick-name").html(d.data.luck_name);
				$("#tag_num").val(d.data.tag_num);
				getLuckUser(option);	
				$("#loading").hide();
			}
		});
  
}

var getLuckUser = function (option){
    $.ajax({
		url:PATH_ACTIVITY + Path_url('lottory_luck_user'),
    	data:{"rid":scene_id,"award_id":option},
    	type:"post",
    	dataType:'json',
    	success:function(d){
		    if(d.data.length>0){
					$.each(d.data,function(i,val){
						var list_num = i +1;
						var luck_user = '<div class="result-line had_luck_user" style="display: block;">';
							luck_user += '<div class="result-num">'+list_num+'</div>';
							luck_user += '<i class="delLottery" onclick="delLuckUser('+val.id+')"></i>';
								luck_user += '<div class="user" style="background-image: url('+val.avatar+');">';
							  if(bd_show==0){
								luck_user += '<span class="nick-name">'+val.nick_name+'</span></div></div>';
							  }else{
								var thtml = ''
								if(bd_show ==1 && lottory_show.length>0){
									for(var i=0;i<lottory_show.length;i++){
									  if(lottory_show[i]!='mobile'){
										thtml += "&nbsp;&nbsp;&nbsp;"+val.bd_data[lottory_show[i]]||' ';	
									  }else{
										val.bd_data[lottory_show[i]] = val.bd_data[lottory_show[i]].replace(/(\d{3})\d{4}(\d{4})/, '$1****$2');
										thtml += "&nbsp;&nbsp;&nbsp;"+val.bd_data[lottory_show[i]]||' ';
									  }
									}	
								}else{
									thtml += val.nick_name;
								}
								
								luck_user += '<span class="nick-name">'+thtml+'</span></div></div>';
							  }
							  $(".lottery-right").prepend(luck_user);
					})
					
		    }
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

function delLuckUser(list_id){
	var option = $("#tagid option:selected").val();
    $.ajax({
		url:PATH_ACTIVITY + Path_url('lottory_remove_user'),
    	data:{"rid":scene_id,"award_id":option,"list_id":list_id},
    	type:"post",
    	async:true,
    	success:function(data){
    		var base = $(".usercount-label").html();
			base = base.replace("人","");
			var person_now = parseInt(base)+1>0?parseInt(base)+1:0;
			$(".usercount-label").html(person_now+"人");
   			$(".lottery-right .had_luck_user").remove();
			cj_ready();
			getLuckUser(option);
    	}
    });
    
}


function reset(){
	var option = $("#tagid option:selected").val();
	if($(".lottery-right .had_luck_user").length==0){
		return;
	}
	if(option>-1){
		if(confirm("重新抽奖、数据将无法恢复，确定吗？")){
			$.ajax({
				url:PATH_ACTIVITY + Path_url('lottory_reset_user'),
				data:{"rid":scene_id,"lottory_id":option},
				type:"post",
				async:true,
				success:function(data){
					$(".lottery-right .had_luck_user").remove();
					cj_ready();
					$(".usercount-label").html(cj_per.length+"人");
				}
			});
	    }
   }
}
function changeClick(){
	$(".control.button-run").attr('onclick','');  
	$(".control.button-run").unbind('click');
	$(".control.button-run").click(function(){  
	   alert("正在进行，不能点击");
	});  
		
	$(".control.button-stop").attr('onclick',''); 
	$(".control.button-stop").unbind('click');
	$(".control.button-stop").click(function(){  
	  alert("正在进行，不能点击");
	});  
	
	$("#newLuckButton").attr('onclick','');    
	$("#newLuckButton").unbind('click');
	$("#newLuckButton").click(function(){  
	  alert("正在进行，不能点击");
	});  
	$(".button-reload").attr('onclick','');    
	$(".button-reload").unbind('click');
	$(".button-reload").click(function(){  
	  alert("正在进行，不能点击");
	});  
	
}

function recoverClick(){
	$(".control.button-run").attr('onclick',''); 
	$(".control.button-stop").attr('onclick',''); 
	$("#newLuckButton").attr('onclick','');
	//$(".button-reload").attr('onclick','');

	$(".control.button-run").unbind('click');
	$(".control.button-stop").unbind('click');
	$("#newLuckButton").unbind('click','');
	$(".button-reload").unbind('click');
	
	$(".control.button-run").attr('onclick','start_lottory()');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$(".control.button-stop").attr('onclick','stop_lottory()');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#newLuckButton").attr('onclick','javascript:reset();');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替 
	$(".button-reload").attr('onclick','javascript:window.location.reload();');
}

var isChange=true;
var num;
var timer;
var numPrizeName;
var luck_num;
function start_lottory(i){
	//alert(i);
	//$(".lotteryDefault").hide();
	var option = $("#tagid option:selected").val();
	if(option==-1){
		//showLayer(1);
		alert('请选择奖项');
		return;
	}
	var alreadyNum = $(".result-line").length-1;
	var tagNum = $("#tag_num").val()==""?0:$("#tag_num").val();

		if(alreadyNum !=0 && alreadyNum==parseInt(tagNum)){
			$("#tagid").removeAttr("disabled");
		    $(".control.button-run").html("开始抽奖");
			clearInterval(timer);
			num = 0;
			isChange = true;
		    recoverClick();
			alert('亲，当前奖项已经抽满人数，不能再进行抽奖！');
			return;
			
		}   
		if(cj_per.length ==0){
			$("#tagid").removeAttr("disabled");
		   $(".control.button-run").html("开始抽奖");
			clearInterval(timer);
			num = 0;
			isChange = true;
		    recoverClick();
			
			alert('参与抽奖人数太少没法抽了！！');
			return;
			
		}   
		  
	if(i!=2){
		
		num=parseInt($("#num option:selected").val());
		var base = $(".usercount-label").html();
		base = base.replace("人",""); 
		if(parseInt(base)==0){
			alert('可抽奖人数为0！不能再进行抽奖');
			return ;
		}
		$("#tagid").attr("disabled","disabled");
	}
	timer=setInterval(function(){
		changeNum();
	},120)
	$(".control.button-run").fadeOut()
	$(".control.button-stop").fadeIn()
}


function stop_lottory(){
	var option = $("#tagid option:selected").val();
	for(var i=0;i<cj_per.length;i++){
		    if(cj_per[i].nd_id == option){
					numPrizeName = cj_per[i];
					luck_num = i;
					$(".lottery-run .user").css({
						"background-image": "url(" + numPrizeName.avatar + ")"
					});
					var thtml = ''
						if(bd_show ==1 && lottory_show.length>0){
							for(var i=0;i<lottory_show.length;i++){
							  if(lottory_show[i]!='mobile'){
								thtml += "&nbsp;&nbsp;&nbsp;"+numPrizeName.bd_data[lottory_show[i]]||' ';	
							  }else{
								numPrizeName.bd_data[lottory_show[i]] = numPrizeName.bd_data[lottory_show[i]].replace(/(\d{3})\d{4}(\d{4})/, '$1****$2');
								thtml += "&nbsp;&nbsp;&nbsp;"+numPrizeName.bd_data[lottory_show[i]]||' ';
							  }
							}	
						}else{
							thtml += numPrizeName.nick_name;
						}
						$(".lottery-run .user .nick-name").html(thtml);
					break;
			}
			
	}
	
	if(numPrizeName.nd_id!=option && numPrizeName.nd_id > 0){
			for(var i=0;i<cj_per.length;i++){
				if(cj_per[i].nd_id == 0){
						numPrizeName = cj_per[i];
						luck_num = i;
						$(".lottery-run .user").css({
							"background-image": "url(" + numPrizeName.avatar + ")"
						});
						var thtml = ''
						if(bd_show ==1 && lottory_show.length>0){
							for(var i=0;i<lottory_show.length;i++){
							  if(lottory_show[i]!='mobile'){
								thtml += "&nbsp;&nbsp;&nbsp;"+numPrizeName.bd_data[lottory_show[i]]||' ';	
							  }else{
								numPrizeName.bd_data[lottory_show[i]] = numPrizeName.bd_data[lottory_show[i]].replace(/(\d{3})\d{4}(\d{4})/, '$1****$2');
								thtml += "&nbsp;&nbsp;&nbsp;"+numPrizeName.bd_data[lottory_show[i]]||' ';
							  }
							}	
						}else{
							thtml += numPrizeName.nick_name;
						}
						$(".lottery-run .user .nick-name").html(thtml);
						break;
				}
			}
			
	}
	$(".control.button-run").fadeIn()
	$(".control.button-stop").fadeOut()
	clearInterval(timer);//清除定时器 不在滚动
	var base = $(".usercount-label").html();
	base = base.replace("人",""); 
	if(num>0 && base!="0"){
    	checked();
	}else{
		return ;
	}
	num--;
	if(num>0){
    	$(".control.button-run").html("开始抽奖("+num+")");
	}else{
		$("#tagid").removeAttr("disabled");
		$(".control.button-run").html("开始抽奖");
	}
	if(num){//如果选择的人数没抽完
		if(isChange){
			changeClick();
			isChange = false;
		}
		
		setTimeout(function(){
			start_lottory(2);
		},1000);
		setTimeout(function(){
			stop_lottory();
		},2000);
	}else{
		isChange = true;
		recoverClick();
	}
}


function checked(){
	    var comment = $("#comment").val();
	    if(comment==''){
	    	comment = "参与奖";
	    }
		//var msg = new Array();
		//msg = numPrizeName[0].split("|");
	    $.ajax({
	    	url:PATH_ACTIVITY + Path_url('lottory_save_user'),
	    	data:{"rid":scene_id,"user_id":numPrizeName.id,"openid":numPrizeName.openid,"award_id":$("#tagid option:selected").val(),"award_name":comment},
	    	type:"post",
			dataType:'json',
	    	async:false,
	    	success:function(d){
	    		if(d.data>0){
					getUser(d.data,function(){
						numPrizeName = {};
						cj_per.splice(luck_num,1);
					});
	    		}else{
					alert('error');
					window.location.reload();
					return ;
				}
	    	}
	    });
		
		var base = $(".usercount-label").html();
		base = base.replace("人","");
		var show_people = parseInt(base-1)>=0?parseInt(base-1):0;
		$(".usercount-label").html(show_people+"人");
		if(base==0){
			alert('全部人数已经抽奖完毕！');
			return ;
		}		
}

function changeNum(){
	var p_num = cj_per.length - 1;
	var randomVal = Math.round(Math.random() * p_num);
    numPrizeName = cj_per[randomVal];
	luck_num = randomVal;
	$(".lottery-run .user").css({
        "background-image": "url(" + numPrizeName.avatar + ")"
    });
	var thtml = ''
	if(bd_show ==1 && lottory_show.length>0){
		for(var i=0;i<lottory_show.length;i++){
		  if(lottory_show[i]!='mobile'){
			thtml += "&nbsp;&nbsp;&nbsp;"+numPrizeName.bd_data[lottory_show[i]]||' ';	
		  }else{
			numPrizeName.bd_data[lottory_show[i]] = numPrizeName.bd_data[lottory_show[i]].replace(/(\d{3})\d{4}(\d{4})/, '$1****$2');
			thtml += "&nbsp;&nbsp;&nbsp;"+numPrizeName.bd_data[lottory_show[i]]||' ';
		  }
		}	
	}else{
		thtml += numPrizeName.nick_name;
	}
    $(".lottery-run .user .nick-name").html(thtml);
}
