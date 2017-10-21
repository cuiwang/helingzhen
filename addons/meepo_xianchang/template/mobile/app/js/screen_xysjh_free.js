var $ResultSeed;
var Players;
var Winers;
var resizePart = window.WBActivity.resize = function() {};
var start = window.WBActivity.start = function() {
    window.WBActivity.hideLoading();
    $(".usercount-label").html("加载数据中...");
    $(".control").hide();
    $.getJSON(PATH_ACTIVITY + Path_url('xysjh_user'), {
        rid: scene_id
    },
    function(json) {
		if(json.luck_data.length>0){
			var liHtml = '';
			$.each(json.luck_data,function(i,val){
					liHtml += '<li>' + val.mobile.replace(/(\d{3})\d{4}(\d{4})/, '$1****$2') + '</li>';		  
			})
			$("#jqResultBox").append(liHtml);	
		}
        if(json.ret==0 && json.data.length>0){
            Players = json.data;
            $("#jqStart").fadeIn()
        } else {
			 Players = [];
            
        }
    }).fail(function() {
		 MyTool.Alert("无法连接服务器，请重试");
             return;
    });
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
    $("#jqStart").on("click", 
    function() {
        start_game()
    });
    $("#jqEnd").on("click", 
    function() {
        stop_game()
    });
    $(".control.button-nextround").on("click", 
    function() {
        window.location.reload()
    });
    $(".button-reload").on("click", 
    function() {
        window.location.reload()
    });
};
var getUser = function(num,f) {
			$("#jqAddDeskAni").addClass("table-moving");
            $("#jqDeskText,#jqDeskText1").text(num);
            var liHtml = '<li>' + num + '</li>';
            setTimeout(function () {
                $("#jqAddDeskAni").removeClass("table-moving");
                $("#jqResultBox").prepend(liHtml);
				if ($.isFunction(f)) {
					f.call(this)
				}
            }, 900);
};
var start_game = function() {
    winer_count = $("#sltAwardNum option:selected").val();
	if (winer_count <= 0) {
                MyTool.Alert("请选择抽奖数量");
                return;
     }
    if (winer_count <= Players.length) {
        $("#jqStart").hide();
        flgPlaying = true;
        playanimate();
        window.setTimeout(function() {
            $("#jqEnd").fadeIn()
        },
        500)
    } else {
		MyTool.Alert("计划选" + winer_count + "人，但是只剩" + Players.length + "人可选，请减少选取数！");
        return;
    }
};
var stop_game = function() {
    $("#jqEnd").hide();
    if ($.isArray(Players)) {
        winer_count = $("#sltAwardNum option:selected").val();
        if (winer_count <= Players.length) {
            getWiner()
        } else {
            MyTool.Alert("计划选" + winer_count + "人，但是只剩" + Players.length + "人可选，请减少选取数！");
             return;
        }
    } else {
		MyTool.Alert("无法获得游戏数据，与游戏服务器断开，请刷新重试！");
             return;
    }
};
var winer_count = 0;
var numPrizeName;
var luck_num;
var getWiner = function() {
    flgPlaying = false;
    window.clearTimeout(tmr_playanimate);
	var p_num = Players.length - 1;
	var randomVal = Math.round(Math.random() * p_num);
    numPrizeName = Players[randomVal];
	luck_num = randomVal;
	var mphone = numPrizeName.mobile.replace(/(\d{3})\d{4}(\d{4})/, '$1****$2');
	$("#jqDeskText").text(mphone);
	$.ajax({
	    	url:PATH_ACTIVITY + Path_url('xysjh_save_user'),
	    	data:{"rid":scene_id,"openid":numPrizeName.openid,"mobile":numPrizeName.mobile},
	    	type:"post",
			dataType:'json',
	    	async:false,
	    	success:function(d){
	    		if(d.data>0){
					window.setTimeout(function() {
						getUser(mphone,function() {
							Players.splice(luck_num,1);
							numPrizeName = {};
							winer_count--;
							if (winer_count > 0) {
								flgPlaying = true;
								playanimate();
								window.setTimeout(function() {
									getWiner()
								},
								1500)
							} else {
								$("#jqStart").fadeIn()
							}
						})
					},
					500)
	    		}else{
					alert('error');
					window.location.reload();
					return ;
				}
	    	}
	    });
};
var flgPlaying = false;
var tmr_playanimate;
var playanimate = function() {
	var p_num2 = Players.length - 1;
	var randomVal2 = Math.round(Math.random() * p_num2);
    if (Players[randomVal2]) {
        var a = Players[randomVal2];
		var clmobile = a.mobile.replace(/(\d{3})\d{4}(\d{4})/, '$1****$2');
		 $("#jqDeskText").text(clmobile);
        if (flgPlaying) {
            tmr_playanimate = window.setTimeout(playanimate, 200)
        }
		
		
    }
};