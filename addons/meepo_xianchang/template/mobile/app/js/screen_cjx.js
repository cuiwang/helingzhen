var $ResultSeed;
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
    $(".usercount-label").html("加载数据中...");
    $(".control").hide();
	cj_ready();
	progressLuckBar();
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
    $(".lottery-right").scrollTop(0);
    var b = $(".lottery-right").scroll(0).children(".result-line").length - 1;
    var a = $ResultSeed.clone();
	a.addClass('had_luck_user');
    a.find(".result-num").html((b + 1));
    a.prependTo(".lottery-right").slideDown();
    var e = a.offset();
	$(".lottery-run").addClass('moving');
	$(".lottery-run").removeClass('box-moving');
	window.setTimeout(function() { 
		 window.setTimeout(function() { 
		  $(".lottery-run").removeClass('moving');
		 },1000);
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
	},
        3000)
};
var progressLuckBar = function(){
	   $.ajax({
			url:PATH_ACTIVITY + Path_url('cjx_taglist'),
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
var isChange=true;
var num;
var timer;
var numPrizeName;
var luck_num;
var start_lottory = function() {
    var option = $("#tagid option:selected").val();
	if(option==-1){
		//showLayer(1);
		alert('请选择奖项');
		return;
	}
	var alreadyNum = $(".result-line").length-1;
	var tagNum = $("#tag_num").val()==""?0:$("#tag_num").val();
	if(alreadyNum !=0 && alreadyNum==parseInt(tagNum)){
			return alert('亲，当前奖项已经抽满人数，不能再进行抽奖！');
	}   
	var base = $(".usercount-label").html();
	base = base.replace("人",""); 
		if(parseInt(base)==0){
			alert('可抽奖人数为0！不能再进行抽奖');
			return ;
		}
    winer_count = parseInt($("#num option:selected").val());
	//console.log(winer_count);
    if (winer_count <= cj_per.length) {
		$(".lottery-run").removeClass('moving');
		$(".lottery-run").addClass('box-moving');
        $(".control.button-run").hide();
        flgPlaying = true;
        playanimate();
        window.setTimeout(function() {
            $(".control.button-stop").fadeIn()
        },
        500)
    } else {
        return alert("计划选" + winer_count + "人，但是只剩" + cj_per.length + "人可选，请减少选取数！")
    }
};
var stop_lottory = function() {
    $(".control.button-stop").hide();
    if ($.isArray(cj_per)) {
        winer_count = parseInt($("#num option:selected").val());
        if (winer_count <= cj_per.length) {
            getWiner()
        } else {
            alert("计划选" + winer_count + "人，但是只剩" + cj_per.length + "人可选，请减少选取数！")
        }
    } else {
        alert("无法获得游戏数据，与游戏服务器断开，请刷新重试！")
    }
};
var winer_count = 0;
var numPrizeName;
var luck_num;
var getWiner = function() {
	var alreadyNum = $(".result-line").length-1;
					var tagNum = $("#tag_num").val()==""?0:$("#tag_num").val();
					if(alreadyNum !=0 && alreadyNum==parseInt(tagNum)){
							return alert('亲，当前奖项已经抽满人数，不能再进行抽奖！');
					} 
	var base = $(".usercount-label").html();
	base = base.replace("人",""); 
		if(parseInt(base)==0){
			alert('可抽奖人数为0！不能再进行抽奖');
			return ;
		}
    flgPlaying = false;
    window.clearTimeout(tmr_playanimate);
    //var b = Math.floor(Math.random() * cj_per.length);
    //var a = cj_per.splice(b, 1)[0];
	var p_num = cj_per.length - 1;
	var randomVal = Math.round(Math.random() * p_num);
    numPrizeName = cj_per[randomVal];
	luck_num = randomVal;
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
    $(".usercount-label").html(cj_per.length + "人");
    $(".lottery-run .user").css({
        "background-image": "url(" + numPrizeName.avatar + ")"
    });
	
	var thtml = ''
		if(bd_show ==1 && lottory_show.length>0){
			for(var i=0;i<lottory_show.length;i++){
			  if(lottory_show[i]!='mobile'){
				if(i>0){
					thtml += "&nbsp;&nbsp;&nbsp;";
				}
				thtml += numPrizeName.bd_data[lottory_show[i]]||' ';	
			  }
			}	
		}else{
			thtml += numPrizeName.nick_name;
		}
	$(".lottery-run .user .nick-name").html(thtml);
	var mphone = numPrizeName.mobile.replace(/(\d{3})\d{4}(\d{4})/, '$1****$2');
	$(".lottery-run .user .mobile").html(mphone);
    window.setTimeout(function() {
		var comment = $("#comment").val();
	    if(comment==''){
	    	comment = "参与奖";
	    }
		$.ajax({
	    	url:PATH_ACTIVITY + Path_url('lottory_save_user'),
	    	data:{"rid":scene_id,"user_id":numPrizeName.id,"openid":numPrizeName.openid,"award_id":$("#tagid option:selected").val(),"award_name":comment},
	    	type:"post",
			dataType:'json',
	    	async:false,
	    	success:function(d){
	    		if(d.data>0){
					
					getUser(d.data,function() {
						numPrizeName = {};
						cj_per.splice(luck_num,1);
						$(".usercount-label").html(cj_per.length + "人");
						winer_count--;
						if (winer_count > 0) {
							flgPlaying = true;
							
							window.setTimeout(function() {
								 playanimate();
								getWiner();
								
							},
							1000)
						} else {
							$(".control.button-run").fadeIn()
						}
					})
	    		}else{
					alert('error');
					window.location.reload();
					return ;
				}
	    	}
	    });
        
    },
    500)
};
var curr_index = 0;
var flgPlaying = false;
var tmr_playanimate;
var playanimate = function() {
    if (cj_per[curr_index]) {
        var a = cj_per[curr_index];
        $(".lottery-run .user").css({
            "background-image": "url(" + a.avatar + ")"
        });
        $(".lottery-run .user .nick-name").html(a.nick_name);
        curr_index++;
        if (curr_index >= cj_per.length) {
            curr_index = 0
        }
        if (flgPlaying) {
            tmr_playanimate = window.setTimeout(playanimate, 100)
        }
    }
};
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
				 window.setTimeout(function() { 
					 window.setTimeout(function() { 
					  $(".lottery-run").removeClass('moving');
					 },1000);
					getUser(d.data,function(){
						numPrizeName = {};
						cj_per.splice(luck_num,1);
					});
				},
                3000);
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



