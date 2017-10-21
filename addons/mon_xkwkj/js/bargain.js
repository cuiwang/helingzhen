define(function(require, exports, module){
    var $ = window.$ = require("../js/zepto"),
    	iTemplate = require("../js/iTemplate"),
    	myDialog = require("../js/myDialog"),
    	iScroll = require("../js/iScroll");

    //判断是否需要原生form元素，若需要，则不改造
    if(!APP.config.needNativeForm){
        var iForm = require("../js/iForm");
    }

    var weapons = {
    	1:"鸣鸿刀",
    	2:"大夏龙雀刀",
    	3:"青龙偃月刀",
		4:"新亭侯刀",
    	5:"毒匕寒月刃",
    	6:"昆吾刀",
    	7:"凤嘴刀",
    	8:"寒月刀",
    	9:"庖丁菜刀"
    }
    var	pseu = {
    	1:"杨过",
		2:"小龙女",
		3:"李莫愁",
		4:"郭靖",
		5:"黄蓉",
		6:"黄药师",
		7:"欧阳峰",
		8:"一灯大师",
		9:"洪七公",
		10:"王重阳"
    }

    var calcTime = function(args){
    	this.hours = 0;
    	this.minutes = 0;
    	this.seconds = 0;
    	this.delta = 1000000;
    	for(var k in args){
    		this[k] = args[k];
    	}
        if(this.currentTime){
            this.currentTimeMs = Date.parse(this.currentTime);
        }
        else{
            this.currentTimeMs = new Date().getTime();
        }
    }
    calcTime.prototype = {
    	calcDelta: function(){
    		var self = this,
    			time = '';
    		if(self.calcWhat == "start" && self.startTime){
    			time = self.startTime;
    		}
    		else if(self.calcWhat == "end" && self.endTime){
    			time = self.endTime;
    		}

    		if(time){
    			var baseTime = Date.parse(time);
                    
    			self.delta = baseTime - self.currentTimeMs;
                
    			if(self.delta>0){
    				var h = Math.floor(self.delta/(1000*60*60)),
						hms = h*60*60*1000,
						m = Math.floor((self.delta-hms)/(1000*60)),
						mms = m*60*1000,
						s = Math.floor((self.delta-hms-mms)/1000);
					self.hours = h;
					self.minutes = m;
					self.seconds = s;
    			}
    			else{
    				self.hours = 0;
    				self.minutes = 0;
    				self.seconds = 0;
    			}
    		}
    		return self;
    	}
    }

    var stopScrol = {
         handleEvent: function(e){
            e.preventDefault();
        }
    }
    
   

    function initPage(){
    	var avatar = $('.avatar').find('i'),
    		iW = $(avatar).css('width'),
    		iH = $(avatar).css('height'),
    		seq = Math.floor(Math.random()*9+1),//随机产生一个[1,9]的整数，用于随机产生武器
    		seq2 = Math.floor(Math.random()*10+1);//随机产生一个[1,10]的整数，用于随机产生昵称

    	var _url = null,
    		myScroll = null;

    	$('#weapon_seq').val(seq);
    	$('#name_seq').val(seq2);
    	$('.sword_name').text(weapons[seq]);
    	$('.money').text(APP.config.cut_amount);
    	// var sword_py = (1-seq)*150;
    	// $('#sword').css('background-position','center '+sword_py+'px');
        $('#sword').css({
            'background': 'url('+basePath+'/images/' + seq + '.png) no-repeat center 0',
            'background-size': '180px auto'
        });

        //能获取用户头像
    	if(APP.urls.avatar_url){
    		$(avatar).css('background', 'url('+APP.urls.avatar_url+') no-repeat center center');
    		$(avatar).css('background-size', iW+' '+iH);
        }
        //不能获取用户头像，但有存储的武器头像
        else if(APP.urls.avatar){
            var delta = -APP.urls.avatar*$(avatar).height();
            $(avatar).css('background', 'url('+basePath+'images/bargain_0.png) no-repeat center ' + delta + 'px');
            $(avatar).css('background-size', iW+' auto');
    	}
        //既不能获取用户头像又不能获取武器头像
    	else{
    		//随机产生一个武器头像
    		var delta = -seq*$(avatar).height();
    		$(avatar).css('background', 'url('+basePath+'images/bargain_0.png) no-repeat center ' + delta + 'px');
    		$(avatar).css('background-size', iW+' auto');
    	}

    	if($('#cut_list')){
    		getList(); //下载砍价高手列表
    	}

    	/***********计时****************/
    	if(APP.config.needTime){
    		var count = new calcTime(APP.config.time);
            count.calcDelta();
            if(count.delta>0){
                var clock = setInterval(function(){
                    count.calcDelta();
                    count.currentTimeMs += 1000;
                    $('.hours').text(addZero(count.hours));
                    $('.minutes').text(addZero(count.minutes));
                    $('.seconds').text(addZero(count.seconds));
                    if(count.delta<=0){
                        clearInterval(clock);
                        location.reload(true);
                    }
                },1000);
            }
    	}

    	/**************绑定事件*********/
    	//找人帮砍
    	$('.cut_7').on('click', function(){
    		$('#mask_help').addClass('on');
    	});
    	$('.help_pic').on('click', function(){
    		$('#mask_help').removeClass('on');
    	});
    	//活动说明
    	$('.instruction').find('label').on('click', function(){
    		$('#mask_instruction').addClass('on');
            window.addEventListener('touchmove', stopScrol, false);
    		if(myScroll == null){
				myScroll = new iScroll('wrapper',{ hScrollbar: false, vScrollbar: false });
			}
         
    	});
    	$('.d_footer').on('click', function(){
    		$('#mask_instruction').removeClass('on');
            window.removeEventListener('touchmove', stopScrol, false);
    	});
    	//手起刀落事件及系列动画
    	$('#wallet').on('webkitAnimationEnd', function(){
    		$('#sword').addClass('animate_sword');
    	});
    	$('#sword').on('webkitAnimationEnd', function(){
    // 		$(this).css({
    // 			"top": "50%",
				// "left": "50%",
				// "margin-top": "-180px",
				// "margin-left": "0px",
				// "-webkit-transform": "rotate(0deg)"
    // 		});
    		var i = 1;
    		var loop = setInterval(function(){
    			if(i>3){
    				clearInterval(loop);
    				console.log($('#praise .money').text());
    				if ($('#praise .money').text() == "0") {
    				    $('#praise .great').addClass("enjoy");
    				    console.log("0000");
    				}
    				$('#praise').addClass('animate_praise');
    			}
    			else{
    				var k = -i*200;
    				$('#wallet').css('background-position', 'center '+k+'px');
    				i++;
    			}
    		},80);
    	});
    	$('.great').on('click', function(){
    		if(_url){
    			location.href = _url;
    		}
    		else{
    			location.reload();
    		}
    	});
        //手起刀落
    	$('.cut_1').on('click', function(){
    		var form = $('#form1')[0];


    		form.callBack = function(res){

				if(1 == res.code){
					//添加动画
					/*
					if(res.Url && res.Url.length){
						_url = res.Url;
					}*/

					_url = window.location.href;
					$('.money').text(res.price);

					$('#mask_animation').addClass('on');
                    window.addEventListener('touchmove', stopScrol, false);
					$('#wallet').addClass('animate_wallet');
				}else{

					if (res.code == 2) {
						showFollowDialog();
					} else {
						alert(res.msg);
					}
				}
			}


			if (collectUserInfo) {
				var uname = $("#uname").val();
				var tel = $("#tel").val();

				if (uname== '') {
					alert("请输入用户名");
					return;
				}

				if (!/1[3-8]+\d{9}/.test(tel)) {
					alert("请输入正确的联系方式");
					return ;
				}

				$("#funame").val(uname);
				$("#futel").val(tel);
			}


			if (!inlimitLocation) {
			     alert("对不起!未获取到您的位置信息，或您不在活动地区限制范围内！无法参与活动！感谢您的参与！");
				  return;
			}
			form.submit();

    	});

        //帮他砍
    	$('.cut_5').on('click', function () {
    	    var form = $('#form1')[0];

			form.callBack = function (res) {
    	        if (1 == res.code) {
    	            $('.money').text(res.price);
					_url = window.location.href;
    	            $('#mask_animation').addClass('on');
    	            window.addEventListener('touchmove', stopScrol, false);
    	            $('#wallet').addClass('animate_wallet');
    	        } else {
					if (res.code == 2) {
						showFollowDialog();
					} else {
						alert(res.msg);
					}
    	        }
    	    }

			if (!inlimitLocation) {
				alert("对不起!未获取到您的位置信息，或您不在活动地区限制范围内！无法帮助好友砍价！感谢您的参与！");
				return;
			}

    	    form.submit();
    	});

        //立即出手
        $('.cut_6').on('click', function () { //立即下手按钮需要使用原生form元素，不是经过iForm改造的
			if (allowKj()) {
				window.location.href = addressUrl;
			}
    	});

		//立即出手
		$('.cut_8').on('click', function () { //订单
			window.location.href = addressUrl;
		});
    }
    //获取砍价高手列表
    function getList(){
    	var TPL='<li>\
					<div>\
						<div class="weapon_pic">\
							<i data-seq={seq_weapon} {has_avatar}></i>\
						</div>\
						<div class="message">\
							<span class="user_name"><font color="white">{nick_name}</font></span>,<font color="white">使用</font>\
							<span class="weapon_name"><font color="white">{weapon_name}</font></span>\
							<span class="action"><font color="white">{action}</font></span>\
							<span class="amount">{amount}</span><font color="white">元</font>\
						</div>\
					</div>\
				</li>';
		$.ajax({
			type: 'POST',
			url: APP.urls.cut_list,
			data: {
			    mid: APP.config.mid,
			    sid: APP.config.sid,
			    is_shared: APP.config.is_shared,
			},
			async: true,
			dataType: "json",
			success: function(res){
				var _html = "",
					_data = [];
				for(var i = 0, j = res.Data.length; i < j; i++){
					_data.push(res.Data[i]);
				}

				var __data = _data.sort(function(a,b){
					return a.time < b.time ? 1 : -1;
				});

				if(res.Status==1){
					_html = iTemplate.makeList(TPL, __data, function(k,v){
						var nick_name = "",
                            has_avatar = "";
						if(v.user_name){
							nick_name = v.user_name;
						}
						else{
							nick_name = pseu[v.seq_name];
						}
                        if(v.avatar){
                            has_avatar = 'data-avatar="'+v.avatar+'"';
                        }
						return {
							weapon_name: weapons[v.seq_weapon],
							nick_name: nick_name,
                            has_avatar: has_avatar
						}
					});
					$('#cut_list').html(_html);
					$('#cut_list').find('i').each(function(){
                        var avatar_url = $(this).data('avatar');
                        if(avatar_url){
                            $(this).css('background', 'url('+avatar_url+') no-repeat center');
                            $(this).css('background-size', '100% auto');
                        }
                        else{
                            var iW = $(this).css('width'),
                                seq = $(this).data('seq'),
                                delta = -seq*$(this).height();
                            $(this).css('background', 'url(./images/bargain_0.png) no-repeat center ' + delta + 'px');
                            $(this).css('background-size', iW+' auto');
                        }
						
					});
				}
			},
			error: function(e){
				alert("下载列表失败");
			}
		});
    }
    //给一位数前面补零，使其符合显示时间的格式，如 8:28:9 变为 08:28:09
    function addZero(num){
    	var a = num.toString().split(""),
    		str = '';
    	if(a.length == 1){
    		str = '0'+ num;
    	}
    	else{
    		str +=num;
    	}
    	return str;
    }

     $(function (){
        initPage();
    });

});