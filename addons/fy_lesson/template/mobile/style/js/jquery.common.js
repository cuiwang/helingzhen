/**
 *
 * Chuanke Jquery自定义插件集合
 * 
 */
$.fn.extend({//添加滚轮事件
	mousewheel:function(Func){
		return this.each(function(){
			var _self = this;
		    _self.D = 0;//滚动方向
			if($.browser.msie||$.browser.safari){
			   _self.onmousewheel=function(){_self.D = event.wheelDelta;event.returnValue = false;Func && Func.call(_self);};
			}else{
			   _self.addEventListener("DOMMouseScroll",function(e){
					_self.D = e.detail>0?-1:1;
					e.preventDefault();
					Func && Func.call(_self);
			   },false); 
			}
		});
	}
});
$.fn.extend({
	jscroll:function(j){
		return this.each(function(){
			j = j || {}
			j.Bar = j.Bar||{};//2级对象
			j.Btn = j.Btn||{};//2级对象
			j.Bar.Bg = j.Bar.Bg||{};//3级对象
			j.Bar.Bd = j.Bar.Bd||{};//3级对象
			j.Btn.uBg = j.Btn.uBg||{};//3级对象
			j.Btn.dBg = j.Btn.dBg||{};//3级对象
			var jun = { W:"15px"
						,BgUrl:""
						,Bg:"#D0D2D5"
						,Bar:{  Pos:"up"
								,Bd:{Out:"#b5b5b5",Hover:"#ccc"}
								,Bg:{Out:"#A6A8AA",Hover:"#999",Focus:"orange"}}
						,Btn:{  btn:true
								,uBg:{Out:"#ccc",Hover:"#fff",Focus:"orange"}
								,dBg:{Out:"#ccc",Hover:"#fff",Focus:"orange"}}
						,Fn:function(){}}
			j.W = j.W||jun.W;
			j.BgUrl = j.BgUrl||jun.BgUrl;
			j.Bg = j.Bg||jun.Bg;
				j.Bar.Pos = j.Bar.Pos||jun.Bar.Pos;
					j.Bar.Bd.Out = j.Bar.Bd.Out||jun.Bar.Bd.Out;
					j.Bar.Bd.Hover = j.Bar.Bd.Hover||jun.Bar.Bd.Hover;
					j.Bar.Bg.Out = j.Bar.Bg.Out||jun.Bar.Bg.Out;
					j.Bar.Bg.Hover = j.Bar.Bg.Hover||jun.Bar.Bg.Hover;
					j.Bar.Bg.Focus = j.Bar.Bg.Focus||jun.Bar.Bg.Focus;
				j.Btn.btn = j.Btn.btn!=undefined?j.Btn.btn:jun.Btn.btn;
					j.Btn.uBg.Out = j.Btn.uBg.Out||jun.Btn.uBg.Out;
					j.Btn.uBg.Hover = j.Btn.uBg.Hover||jun.Btn.uBg.Hover;
					j.Btn.uBg.Focus = j.Btn.uBg.Focus||jun.Btn.uBg.Focus;
					j.Btn.dBg.Out = j.Btn.dBg.Out||jun.Btn.dBg.Out;
					j.Btn.dBg.Hover = j.Btn.dBg.Hover||jun.Btn.dBg.Hover;
					j.Btn.dBg.Focus = j.Btn.dBg.Focus||jun.Btn.dBg.Focus;
			j.Fn = j.Fn||jun.Fn;
			var _self = this;
			var Stime,Sp=0,Isup=0;
			$(_self).css({overflow:"hidden",position:"relative",padding:"0px"});
			var dw = $(_self).width(), dh = $(_self).height()-1;
			var sw = j.W ? parseInt(j.W) : 21;
			var sl = dw - sw
			var bw = j.Btn.btn==true ? sw : 0;
			if($(_self).children(".jscroll-c").height()==null){//存在性检测
		$(_self).wrapInner("<div class='jscroll-c' style='top:0px;z-index:99;zoom:1;position:relative'></div>");
			$(_self).children(".jscroll-c").prepend("<div style='height:0px;overflow:hidden'></div>");
			$(_self).append("<div class='jscroll-e' unselectable='on' style=' height:100%;top:0px;right:0;-moz-user-select:none;position:absolute;overflow:hidden;z-index:10000;'><div class='jscroll-u' style='position:absolute;top:0px;width:100%;left:0;background:blue;overflow:hidden'></div><div class='jscroll-h'  unselectable='on' style='background:#444;position:absolute;left:0;-moz-user-select:none;border-radius:2px;cursor:pointer;'></div><div class='jscroll-d' style='position:absolute;bottom:0px;width:100%;left:0;background:blue;overflow:hidden'></div></div>");
			}
			var jscrollc = $(_self).children(".jscroll-c");
			var jscrolle = $(_self).children(".jscroll-e");
			var jscrollh = jscrolle.children(".jscroll-h");
			var jscrollu = jscrolle.children(".jscroll-u");
			var jscrolld = jscrolle.children(".jscroll-d");
			if($.browser.msie){document.execCommand("BackgroundImageCache", false, true);}
			jscrollc.css({"padding-right":sw});
			jscrolle.css({width:sw,background:j.Bg,"background-image":j.BgUrl});
			jscrollh.css({top:bw,background:j.Bar.Bg.Out,"background-image":j.BgUrl,width:sw-2});
			jscrollu.css({height:bw,background:j.Btn.uBg.Out,"background-image":j.BgUrl});
			jscrolld.css({height:bw,background:j.Btn.dBg.Out,"background-image":j.BgUrl});
			jscrollh.hover(function(){if(Isup==0)$(this).css({background:j.Bar.Bg.Hover,"background-image":j.BgUrl})},function(){if(Isup==0)$(this).css({background:j.Bar.Bg.Out,"background-image":j.BgUrl})})
			jscrollu.hover(function(){if(Isup==0)$(this).css({background:j.Btn.uBg.Hover,"background-image":j.BgUrl})},function(){if(Isup==0)$(this).css({background:j.Btn.uBg.Out,"background-image":j.BgUrl})})
			jscrolld.hover(function(){if(Isup==0)$(this).css({background:j.Btn.dBg.Hover,"background-image":j.BgUrl})},function(){if(Isup==0)$(this).css({background:j.Btn.dBg.Out,"background-image":j.BgUrl})})
			var sch = jscrollc.height();
			//var sh = Math.pow(dh,2) / sch ;//Math.pow(x,y)x的y次方
			var sh = (dh-2*bw)*dh / sch
			if(sh<10){sh=10}
			var wh = sh/6//滚动时候跳动幅度
		//	sh = parseInt(sh);
			var curT = 0,allowS=false;
			jscrollh.height(sh);
			if(sch<=dh){jscrollc.css({padding:0});jscrolle.css({display:"none"})}else{allowS=true;}
			if(j.Bar.Pos!="up"){
			curT=dh-sh-bw;
			setT();
			}
			jscrollh.bind("mousedown",function(e){
				j['Fn'] && j['Fn'].call(_self);
				Isup=1;
				//jscrollh.css({background:j.Bar.Bg.Focus,"background-image":j.BgUrl})
				var pageY = e.pageY ,t = parseInt($(this).css("top"));
				$(document).mousemove(function(e2){
					 curT =t+ e2.pageY - pageY;//pageY浏览器可视区域鼠标位置，screenY屏幕可视区域鼠标位置
						setT();
				});
				$(document).mouseup(function(){
					Isup=0;
					jscrollh.css({background:j.Bar.Bg.Out,"background-image":j.BgUrl,"border-color":j.Bar.Bd.Out})
					$(document).unbind();
				});
				return false;
			});
			jscrollu.bind("mousedown",function(e){
			j['Fn'] && j['Fn'].call(_self);
				Isup=1;
				jscrollu.css({background:j.Btn.uBg.Focus,"background-image":j.BgUrl})
				_self.timeSetT("u");
				$(document).mouseup(function(){
					Isup=0;
					jscrollu.css({background:j.Btn.uBg.Out,"background-image":j.BgUrl})
					$(document).unbind();
					clearTimeout(Stime);
					Sp=0;
				});
				return false;
			});
			jscrolld.bind("mousedown",function(e){
			j['Fn'] && j['Fn'].call(_self);
				Isup=1;
				jscrolld.css({background:j.Btn.dBg.Focus,"background-image":j.BgUrl})
				_self.timeSetT("d");
				$(document).mouseup(function(){
					Isup=0;
					jscrolld.css({background:j.Btn.dBg.Out,"background-image":j.BgUrl})
					$(document).unbind();
					clearTimeout(Stime);
					Sp=0;
				});
				return false;
			});
			_self.timeSetT = function(d){
				var self=this;
				if(d=="u"){curT-=wh;}else{curT+=wh;}
				setT();
				Sp+=2;
				var t =500 - Sp*50;
				if(t<=0){t=0};
				Stime = setTimeout(function(){self.timeSetT(d);},t);
			}
			jscrolle.bind("mousedown",function(e){
					j['Fn'] && j['Fn'].call(_self);
							curT = curT + e.pageY - jscrollh.offset().top - sh/2;
							asetT();
							return false;
			});
			function asetT(){				
						if(curT<bw){curT=bw;}
						if(curT>dh-sh-bw){curT=dh-sh-bw;}
						jscrollh.stop().animate({top:curT},100);
						var scT = -((curT-bw)*sch/(dh-2*bw));
						jscrollc.stop().animate({top:scT},1000);
			};
			function setT(){				
						if(curT<bw){curT=bw;}
						if(curT>dh-sh-bw){curT=dh-sh-bw;}
						jscrollh.css({top:curT});
						var scT = -((curT-bw)*sch/(dh-2*bw));
						jscrollc.css({top:scT});
			};
			$(_self).mousewheel(function(){
					if(allowS!=true) return;
					j['Fn'] && j['Fn'].call(_self);
						if(this.D>0){curT-=wh;}else{curT+=wh;};
						setT();
			})
		});
	}
});
jQuery.fn.extend({
	countwords:function(errocallback){
        var max = parseInt(this.attr('maxlength'));
        var otips=$("#"+this.attr("id")+"_tip");
        
        var val = this.val();
        var len = $.trim(val).replace(/[^\x00-\xff]/g, '**').length;
        otips.html(len);
        
        if(len > max){
            if(typeof(errocallback) == "function"){
                errocallback();  
            }
        } else{
			otips.css({"color":"#999999"});
        }
    },

	/**
	 * 显示弹出层
	 * selector 选择器元素ID
	 * callback 回调函数，可以为空
	 * eg:$.showMenu("elementID")
	 */
	showMenu : function(selector, callback) {
		var $selector = $(selector);
		if($(this).attr("follow")){ 
            var pos = $(this).offset(); 
            var diffleft = pos.left + 286 - $(window).width();
            var scrleft  = parseInt($(window).scrollLeft())-5;
        	$selector.css({
            	"position":"absolute",
            	"left":(pos.left)+"px",
            	"top":(pos.top+$(this).height())+"px"
        	});
        }
        
        $selector.attr("show", 1).show();
        $selector.mouseenter(function(){
            $(this).attr("show",1);
			return false;
        });
        
        $selector.mouseleave(function(){
            $(this).hide(10, callback); 
			return false;
        });
	},
	
	/**
	 * 隐藏弹出层
	 * selector 选择器元素ID
	 * callback 回调函数，可以为空
	 * eg:$.hideMenu("elementID")
	 */
	hideMenu : function(selector, callback) {
		var $selector = $(selector); 
        $selector.attr("show", 0); 
		setTimeout(
			function(){ 
				if($selector.attr("show") == 0){  
					$selector.hide(10, callback);
				} 
			},
			500
		);
	},
	
	/**
	 * 修改select控件展现形式
	 * e.g:$("#selectID").divSelect()
	 */
	divSelect : function(swidth, sclass) {
		//后面跟一个<div>
        var sId = $(this).attr("id");
        if(!sId){
        	sId="sel_"+parseInt(Math.random() * 1000000);
        	$(this).attr("id",sId);
        }

		$("#dMenu_"+sId).remove();

		var class_list = sclass ? 'imitateSelect '+sclass : 'imitateSelect';
        $(this).after("<div id='dMenu_"+sId+"' class='"+class_list+" fl'><div id='dText_"+sId+"' class='selected' style='cursor:pointer;'><span class='float_left'></span><input type='button' value='' class='float_right' id='float_right'/></div><div id='dList_"+sId+"' class='selectList'></div></div>");
       
        var $Menu  = $("#dMenu_"+sId);
        var $Lable = $("#dText_"+sId+">.float_left");
        var $Title = $("#dText_"+sId);
        var $List  = $("#dList_"+sId);
        var $btnList=$("#float_right");
        var width = swidth ? swidth+'px' : $(this).css("width");
        
        if($("#"+sId).attr('disabled')){
            $Menu.addClass('forbidSelect');
        }

		//显示当前选中的选项在Lable,并且隐藏列表中相应的选项
        $Lable.text($(this).find("option:selected").text());
        
        $Menu.css({"width":width, "z-index":"1"});
        //创建,list
        
        var lstHtml = '<ul>';
        $("#"+sId+" option").each(function(){
			lstHtml += "<li idx='"+$(this).attr('value')+"'>"+$(this).text()+"</li>";
        }); 
        lstHtml += '</ul>';
        $List.hide().css({"z-index":"999", "left":"0", "position":'absolute', "width":width}).html(lstHtml);
        
        $List.find("li").mouseover(function(){
        	$(this).addClass("moveOn");
        }).mouseout(function(){
        	$(this).removeClass("moveOn");
        });
        
        //隐藏select
        $(this).hide();
        
        /*
        //如果宽度为%,则父窗口position为relative
        if(width.indexOf('%')){
           //$(this).parent().css("position","relative");
        } 
        */
        
        //下拉选择
        $Title.click(function(){
            if($("#"+sId).attr('disabled')){
               return false;
            }
            $(this).showMenu("#dList_"+sId); 
            $Menu.css({"z-index":"2", "position":"relative"});
        });
        
        $Title.mouseleave(function(){
            $(this).hideMenu("#dList_"+sId,function(){
                $Menu.css({"z-index":"1", "position":""});
            });
        });
        
        //选中
        $List.find("li").click(function(){ 
            var idx = $(this).attr("idx");
            $("#"+sId+" option[value='"+idx+"']").attr("selected" , true);
			
            $Lable.text($(this).text());
            $List.hide();
            $Menu.css({"z-index":"1", "position":""});
            $("#"+sId).change();
            return false;
        });
	},
	
	/**
	 * input默认提示设置
	 * @desc 用法：<input type="text" prompt="请输入用户名" value="" />
	 * @author limenggang@chuanke.com
	 * @version 1.5 2012/03/20
	 */
	inputTips : function(){
		var box = $(this);
		box.find(":text,:password,textarea").each(function(i, obj){
			var defaultValue = $(obj).attr('prompt');
			if(typeof(defaultValue) != 'undefined'){
				var nodeId =  $("[id^='ck_prompt_']").size()+1;
				var ckLable = '<span class="iptTips" id="ck_prompt_'+nodeId+'">'+defaultValue+'</span>';
				if($(obj).val()==''){
					if($(obj).prev("span.iptTips").length > 0){
						$(obj).prev("span.iptTips").show();
					}else{
						$(ckLable).insertBefore($(obj));
					}
					$("#ck_prompt_"+nodeId).unbind().bind("click", function(){
						$(this).hide().next("input").trigger('focus');
					});
				}else{
					if($(obj).prev("span.iptTips").length > 0){
						$(obj).prev("span.iptTips").hide();
					}else{
						$(ckLable).insertBefore($(obj)).hide();
					}
					$("#ck_prompt_"+nodeId).unbind().bind("click", function(){
						$(this).hide().next("input").trigger('focus');
					});
				}
				$(obj).focus(function(){
					//alert('dsfa');
					if($(obj).prev("span.iptTips").size() > 0){
						$(obj).prev("span.iptTips").hide();
					}
				}).blur(function(){
					if($(obj).val()==''){
						$(obj).prev("span.iptTips").show();
					}
				});
			}
		})
	},
	
	/**
	 * 内容缓加载，滚动到显示位置再加载内容到指定的容器
	 * @author herenet
	 * @since 2013/10/28
	 */
	ScrollLoad : function(){
		var Obj = $(this),
			Offset = Obj.offset(),
			TriggerTag = false,
			Link = Obj.attr('src');
		
		var	Load = function(){
			$.ajax({
				url : Link+"&random="+Math.random(),
				dataType : 'html',
				type : 'get',
				async : false,
				success : function(rs){
					Obj.html(rs);
				}
			});
		}
		
		$(window).bind('scroll', function(){
			var ScrollPostion = parseInt($(this).height() + $(this).scrollTop());
			if(TriggerTag == false){
				if(ScrollPostion >= Offset.top){
					TriggerTag = true;
					Load();
				}
			}else{
				$(this).unbind('scroll');
			}
		});

		$(window).bind('resize', function(){
			var ScrollPostion = parseInt($(this).height() + $(this).scrollTop());
			if(TriggerTag == false){
				if(ScrollPostion >= Offset.top){
					TriggerTag = true;
					Load();
				}
			}else{
				$(this).unbind('resize');
			}
		});
		
		$(window).bind('load', function(){
			var ScrollPostion = parseInt($(this).height() + $(this).scrollTop());
			if(TriggerTag == false){
				if(ScrollPostion >= Offset.top){
					TriggerTag = true;
					Load();
				}
			}else{
				$(this).unbind('load');
			}
		});
	}
});

jQuery.extend({
	toolTips :function(){
		$('.tipsTrigger').each(function () {
            var hideDelayTimer = null;
            var trigger = $(this);
            var uid = $(this).attr('uid');
            if($('#tipsPopup_'+uid).length == 0){
            	$("body").append('<div class="tipsPopup" id="tipsPopup_'+uid+'" load="0"></div>');
            }

            var info = $('#tipsPopup_'+uid).css('opacity', 0);
            
            $(this).attr("distance",5).attr("time",250).attr("hideDelay",500).attr("beingShown",0).attr("shown",0);

            $([trigger.get(0), info.get(0)]).mouseover(function () {
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                var triggerId = $(trigger).attr("id");
	            var uid = $('#'+triggerId).attr("uid");
	            if($('#tipsPopup_'+uid).attr('load') == '0'){
		            $.ajax({
		            	type:"get",
		            	url:"/?mod=teacher&act=auth&do=tips&tm="+new Date().getTime(),
		            	dataType:"html",
		            	data:"uid="+uid,
		            	success:function(html){
		            		$('#tipsPopup_'+uid).html(html).attr('load','1');
		            	}
		            });            	
	            }
                var beingShown = $("#"+triggerId).attr("beingShown");
                var shown = $("#"+triggerId).attr("shown");
                var distance = $("#"+triggerId).attr("distance");
                var time = $("#"+triggerId).attr("time");
                var tleft = $("#"+triggerId).offset().left;
                var ttop = $("#"+triggerId).offset().top;
                if (beingShown == '1' || shown == '1') {
                    // don't trigger the animation again
                    return;
                } else {
                    // reset position of info box
                    $("#"+triggerId).attr("beingShown",1);

                    info.css({
                        top: ttop-90,
                        left: tleft-33,
                        display: 'block'
                    }).animate({
                        top: '-=' + distance + 'px',
                        opacity: 1
                    }, time, 'swing', function() {
                        $("#"+triggerId).attr("beingShown",0);
                        $("#"+triggerId).attr("shown",1);
                    });
                }

                return false;
            }).mouseout(function () {
                var triggerId = $(trigger).attr("id");
            	var hideDelay = $("#"+triggerId).attr("hideDelay");
            	var distance = $("#"+triggerId).attr("distance");
            	var time = $("#"+triggerId).attr("time");
                var tleft = $("#"+triggerId).offset().left;
                var ttop = $("#"+triggerId).offset().top;            	
            	if (hideDelayTimer) clearTimeout(hideDelayTimer);
                hideDelayTimer = setTimeout(function () {
                    hideDelayTimer = null;
                    info.animate({
                        top: '-=' + distance + 'px',
                        opacity: 0
                    }, time, 'swing', function () {
                        $("#"+triggerId).attr("shown",0);
                        info.css('display', 'none');
                    });

                }, hideDelay);

                return false;
            });
        });			
	},
	
	/**
	 * 目前所有弹出层插件只支持弹一次
	 */
	ckAjaxBoxy : function(options){
		options = jQuery.extend({
			url : false,
			title : false,
			width : false,
			height : false,
			callback : false
		},options);
		var IE6_scollheight = 0;
        if ( $.browser.msie && $.browser.version == '6.0' ){
                IE6_scollheight = document.documentElement.scrollTop;
        }
		var boxy = {
			html : [
			    '<div class="fe_window_mask" id="fe_window_mask" style="display:block;"><iframe class="fe_window_iframe" frameborder="0"></iframe></div>',
			    '<div class="fe_dialogBox" id="fe_dialogBox" style="width:'+options.width+'px;height:'+options.height+'px;margin:-'+(options.height/2)+'px 0 0 -'+(options.width/2)+'px;_margin-top:'+parseInt(IE6_scollheight-options.height/2)+'px">',
			    '<div class="fe_dialog" style="width:'+(options.width-14)+'px;height:'+(options.height-14)+'px;">',
			    '<div class="loading"><img src="'+KK._resurl+'/sites/www/v2/images/public/dialog/loading.gif" width="" height="" /></div>',
			    '<div class="contains">',
			    '<div class="hd">'+options.title+'</div>',
			    '<div class="bd"></div>',
			    '</div><a href="javascript:;" class="close">关闭</a></div></div>'
			].join(''),
			
			init : function(){
				if($("#fe_dialogBox").size() == 0){
					$("body").append(boxy.html);
				}else{
					$("#fe_dialogBox div.bd").html('');
					$("#fe_dialogBox div.loading").show();
				}
				$.ajax({
					url : options.url,
					async : false,
					type : 'get',
					dataType : 'html',
					success : function(rs){
						$("#fe_dialogBox div.loading").hide();
						$("#fe_dialogBox div.bd").html(rs);
						$("#fe_dialogBox a.close").unbind().bind('click', function(){
							boxy.cancel();
						});
						if(options.callback != false){
							options.callback();
						}
					}
				});
			},
			
			cancel : function(callback){
				$("#fe_window_mask").remove();
				$("#fe_dialogBox").hide().remove();
				if(typeof(callback) != 'undefined'){
					callback();
				}
			}
		};
		boxy.init();
		return boxy;
	},

	ckBoxy : function(options){
		options = jQuery.extend({
			content : '',
			title : false,
			width : false,
			height : false,
			callback : false
		},options);
		var IE6_scollheight = 0;
        if ( $.browser.msie && $.browser.version == '6.0' ){
                IE6_scollheight = document.documentElement.scrollTop;
        }
		var boxy = {
			html : [
			    '<div class="fe_window_mask" id="fe_window_mask" style="display:block;"><iframe class="fe_window_iframe" frameborder="0"></iframe></div>',
			    '<div class="fe_dialogBox" id="fe_dialogBox" style="width:'+options.width+'px;height:'+options.height+'px;margin:-'+(options.height/2)+'px 0 0 -'+(options.width/2)+'px;_margin-top:'+parseInt(IE6_scollheight-options.height/2)+'px">',
			    '<div class="fe_dialog" style="width:'+(options.width-14)+'px;height:'+(options.height-14)+'px;">',
			    '<div class="contains">',
			    '<div class="hd">'+options.title+'</div>',
			    '<div class="bd">'+options.content+'</div>',
			    '</div><a href="javascript:;" class="close">关闭</a></div></div>'
			].join(''),
			
			init : function(){
				$("body").append(boxy.html);
				$("#fe_dialogBox a.close").unbind().bind('click', function(){
					boxy.cancel();
					return false;
				});
				if(options.callback != false){
					options.callback();
				}
			},
			
			cancel : function(){
				$("#fe_window_mask").remove();
				$("#fe_dialogBox").hide().remove();
			}
		};
		boxy.init();
		return boxy;
	},

	ckRedirectBoxy : function(options){
		options = jQuery.extend({
			content : '',
			width : false,
			height : false,
			callback : false
		},options);
		var IE6_scollheight = 0;
        if ( $.browser.msie && $.browser.version == '6.0' ){
                IE6_scollheight = document.documentElement.scrollTop;
        }
		var boxy = {
			html : [
			    '<div class="fe_window_mask" id="fe_window_mask" style="display:block;"><iframe class="fe_window_iframe" frameborder="0"></iframe></div>',
			    '<div class="fe_dialogBox" id="fe_dialogBox" style="width:'+options.width+'px;height:'+options.height+'px;margin:-'+(options.height/2)+'px 0 0 -'+(options.width/2)+'px;_margin-top:'+parseInt(IE6_scollheight-options.height/2)+'px">',
			    '<div class="fe_dialog" style="width:'+(options.width-14)+'px;height:'+(options.height-14)+'px;">',
			    '<div class="contains">',
			    '<div class="bd">'+options.content+'</div>',
			    '</div></div></div>'
			].join(''),
			
			init : function(){
				$("body").append(boxy.html);
				if(options.callback != false){
					options.callback();
				}
			},
			
			cancel : function(){
				$("#fe_window_mask").remove();
				$("#fe_dialogBox").hide().remove();
			}
		};
		boxy.init();
		return boxy;
	},
	
	ckAlert : function(options){
		options = jQuery.extend({
			message : "注意",
			title : "提示",
			width : 352,
			height : 192,
			callback : false
		},options);
		var IE6_scollheight = 0;
        if ( $.browser.msie && $.browser.version == '6.0' ){
                IE6_scollheight = document.documentElement.scrollTop;
        }
		var html = [
			    '<div class="fe_window_mask" id="fe_window_mask" style="display:block;"><iframe class="fe_window_iframe" frameborder="0"></iframe></div>',
			    '<div class="fe_dialogBox" id="fe_dialogBox" style="width:'+options.width+'px;height:'+options.height+'px;margin:-'+(options.height/2)+'px 0 0 -'+(options.width/2)+'px;_margin-top:'+parseInt(IE6_scollheight-options.height/2)+'px">',
			    '<div class="fe_dialog" style="width:'+(options.width-14)+'px;height:'+(options.height-14)+'px;">',
			    '<div class="contains">',
			    '<div class="hd">'+options.title+'</div>',
			    '<div class="bd">',
			    '<p class="lh25 mt50 tc vm f14 c_777">',
				'<img alt="" src="'+KK._resurl+'/sites/www/v2/images/public/ico_warning_24x24.png" class="vm mr10">', 
				'<span>'+options.message+'</span>',
				'</p></div>',
			    '</div><a href="javascript:;" class="close">关闭</a></div></div>'
			].join('');
		
		$("body").append(html);
		$("#fe_dialogBox a.close").unbind().bind('click', function(){
			$("#fe_window_mask").remove();
			$("#fe_dialogBox").hide().remove();
			if(options.callback != false){
				options.callback();
			}
		});
	},
	
	ckConfirm : function(options){
		options = jQuery.extend({
			message : "您确定要删除此条记录吗？",
			title : "提示",
			width : 352,
			height : 192,
			ok : function(){},
			cancel : function(){},
			src:"http://res.ckimg.com/sites/www/v2/images/public/ico_warning_24x25.png"
		},options);
		var IE6_scollheight = 0;
        if ( $.browser.msie && $.browser.version == '6.0' ){
                IE6_scollheight = document.documentElement.scrollTop;
        }
		var html = [
			    '<div class="fe_window_mask" id="fe_window_mask" style="display:block;"><iframe class="fe_window_iframe" frameborder="0"></iframe></div>',
			    '<div class="fe_dialogBox" id="fe_dialogBox" style="width:'+options.width+'px;height:'+options.height+'px;margin:-'+(options.height/2)+'px 0 0 -'+(options.width/2)+'px;_margin-top:'+parseInt(IE6_scollheight-options.height/2)+'px">',
			    '<div class="fe_dialog" style="width:'+(options.width-14)+'px;height:'+(options.height-14)+'px;">',
			    '<div class="contains">',
			    '<div class="hd">'+options.title+'</div>',
			    '<div class="bd">',
			    '<p class="lh25 p20 tl vm f14 c_777">',
				'<img alt="" src="http://res.ckimg.com/sites/www/v2/images/public/ico_warning_24x24.png" class="vm mr10">', 
				'<span>'+options.message+'</span>',
				'</p><div class="tc">',
				'<a class="c_btn30 mr20 submit" href="javascript:;"><span>确定</span></a>',
				'<a class="c_btn30 cancel" href="javascript:;"><span>取消</span></a>',
				'</div></div>',
			    '</div><a href="javascript:;" class="close">关闭</a></div></div>'
			].join('');
		
		$("body").append(html);
		$("#fe_dialogBox a.close,a.cancel").unbind().bind('click', function(){
			$("#fe_window_mask").remove();
			$("#fe_dialogBox").hide().remove();
			options.cancel();
		});
		$("#fe_dialogBox a.submit").unbind().bind('click', function(){
			$("#fe_window_mask").remove();
			$("#fe_dialogBox").hide().remove();
			options.ok();
		});
	},
	/**进入教室检测到客户端登陆
	*/
	ckClient : function(options){
		options = jQuery.extend({
			message : "检测到您已经登录KK客户端，您可以",
			message1 : "继续在浏览器中观看",
			message2 : "打开KK客户端观看",
			sid : 1,
			courseid : 1,
			cid : 1,
			title : "进入教室",
			width : 530,
			height : 255,
			ok : function(){},
			client : function(){},
			cancel : function(){}
		},options);
		var IE6_scollheight = 0;
        if ( $.browser.msie && $.browser.version == '6.0' ){
                IE6_scollheight = document.documentElement.scrollTop;
        }
		var html = [
			    '<div class="fe_window_mask" id="fe_window_mask" style="display:block;"><iframe class="fe_window_iframe" frameborder="0"></iframe></div>',
			    '<div class="fe_dialogBox png_bg" id="fe_dialogBox" style="height:'+options.height+'px;width:'+options.width+'px;margin:-'+(options.height-70)+'px 0 0 -'+(options.width/2)+'px;">',
			    '<div class="fe_dialog" style="width:'+(options.width-14)+'px;height:'+(options.height-14)+'px;">',
				'<div class="loading" style="display:none;"><img src="http://res.ckimg.com/sites/www/v2/images/public/dialog/loading.gif" width="" height="" alt="下载中" /></div>',
			    '<div class="contains">',
			    '<div class="hd">'+options.title+'</div>',
			    '<div class="bd">',
			    '<div class="pl10 pr10 c_777 pt15 mb10">',
				'<p class="ml10">'+options.message+'</p><p class="lineDashed mt10"></p></div>', 
				'<div class="tc pt30"><a href="#" class="btn_jrjs_kk">'+options.message2+'</a>',
				'<span  class="ml30 mr30 c_777">或者</span>',
				'<a href="javascript:;" class="btn_jrjs_web" sid="/'+options.sid+'/'+options.courseid+'/'+options.cid+'/">'+options.message1+'</a></div>',
				'<p class="tc mt25 c_777"><span class="c_f60" id="daoji">15</span>秒后打开KK客户端观看</p>',
				'</div></div>',
			    '<a href="javascript:;" class="close">关闭</a></div></div>'
			].join('');
		$("body").append(html);
		$("#fe_dialogBox a.btn_jrjs_web").unbind().bind('click', function(){
			$("#fe_window_mask").remove();
			$("#fe_dialogBox").hide().remove();
			options.ok();
		});
		$("#fe_dialogBox a.close").unbind().bind('click', function(){
			$("#fe_window_mask").remove();
			$("#fe_dialogBox").hide().remove();
			options.cancel();
		});
		$("#fe_dialogBox a.btn_jrjs_kk").unbind().bind('click', function(){
			$("#fe_window_mask").remove();
			$("#fe_dialogBox").hide().remove();
			options.client();
		});
	},
	/**
	 * 如果type=loading,则有返回值，
	 * var loadingBox = $.ckTipsBoxy({type:"loading"});
	 * 需调用loadingBox.cancel();关闭
	 */
	ckTipsBoxy : function(options){
		options = jQuery.extend({
			message : "",
			type : "success",
			time : 1500,
			width : 164,
			height : 68,
			callback : false,
			mask : false
		},options);
		var IE6_scollheight = 0;
        if ( $.browser.msie && $.browser.version == '6.0' ){
                IE6_scollheight = document.documentElement.scrollTop;
        }
		boxy = {
			
			init : function(){
				var tipsImg = '';
		
				switch(options.type){
					case 'success'	: this.tipsImg = '<img src="'+KK._resurl+'/sites/www/v2/images/public/ico_ok_25x25.png" class="vm mr10">';break;
					case 'error'	: this.tipsImg = '<img src="'+KK._resurl+'/sites/www/v2/images/public/ico_error_25x25.png" class="vm mr10">';break;
					case 'notice'	: this.tipsImg = '<img src="'+KK._resurl+'/sites/www/v2/images/public/ico_warning_24x25.png" class="vm mr10">';break;
					case 'mailtip'	: this.tipsImg = '<img src="'+KK._resurl+'/sites/www/v2/images/public/ico_ok_16x16.png"  class="pr mr10" style="top:-2px;*top:0px;">';break;
					case 'loading'	: this.tipsImg = '<img src="'+KK._resurl+'/sites/www/v2/images/public/dialog/loading.gif" class="vm mr10" />';
				}
				
				var html = [
				    options.mask ? '<div class="fe_window_mask" id="fe_window_mask" style="display:block;"><iframe class="fe_window_iframe" frameborder="0"></iframe></div>' : '',
				    '<div class="fe_dialogBox" id="fe_dialogBox" style="width:'+options.width+'px;height:'+options.height+'px;margin:-'+(options.height/2)+'px 0 0 -'+(options.width/2)+'px;_margin-top:'+parseInt(IE6_scollheight-options.height/2)+'px">',
				    '<div class="fe_dialog" style="width:'+(options.width-14)+'px;height:'+(options.height-14)+'px;">',
				    '<div class="contains">',
				    '<div class="bd">',
				    '<p class="lh25 mt15 tc vm f14 c_777">',
				    this.tipsImg,
					'<span>'+options.type == 'loading' ? '' : options.message+'</span>',
					'</p></div>',
				    '</div></div></div>'
				].join('');
				if($("#fe_dialogBox").length > 0){
					$("#fe_dialogBox div.bd").html('<p class="lh25 mt15 tc vm f14 c_777">'+this.tipsImg+'<span>'+options.message+'</span></p>');
				}else{
					$("body").append(html);
				}
			},
		
			cancel : function(){
				$("#fe_dialogBox").hide().remove();
				$("#fe_window_mask").remove();
				if(options.callback != false){
					options.callback();
				}
			}
		}
		
		boxy.init();
		if(options.type != 'loading'){
			setTimeout("boxy.cancel()", options.time);
		}else{
			return boxy;
		}
	},
	
	ckLoginBoxy : function(options){
		options = jQuery.extend({
			success : false,
			error : false
		},options);
		
		var url = location.href;
		url = KK.base64_encode(url);
		url = url.replace(/\//, '_');
		location.href = "http://passport.chuanke.com/login/index/ret/"+url;
		return false;		

		var html = [
			    '<div class="fe_window_mask" id="fe_window_mask" style="display:block;"><iframe class="fe_window_iframe" frameborder="0"></iframe></div>',
			    '<div class="fe_dialogBox" id="fe_dialogBox" style="height:254px;width:600px;margin:-125px 0 0 -300px;">',
			    '<div class="fe_dialog" style="height:240px;width:586px;">',
			    '<div class="contains"><div class="hd">用户登录</div><div class="bd">',
				'<div class="d_d_userLogin clearfix">',
				'<form action="" class="formBox fl" id="userLogin_form"><div class="formItem">',
				'<div class="form_label">用户名：</div><div class="form_field">',
				'<span class="pr fl"><input type="text" name="username" prompt="请输入KK号或邮箱地址" class="form_text"></span></div></div><div class="formItem">',
				'<div class="form_label">密　码：</div><div class="form_field">',
				'<span class="pr fl"><input type="password" prompt="请输入您的密码" name="password" class="form_text fl">',
				'</span></div></div>',
				'<div class="formItem"><div class="form_label">&nbsp;</div>',
				'<div class="form_field"><span id="msgCaptip" style="position:absolute;background-color:#FFDBDB;border-radius:4px;color: #777777;padding-left:3px;padding-right:10px;display:none;">大写锁定已打开</span><p class="c_4b9a03 login_tips"></p>',
				'</div></div><div class="formItem"><div class="form_label">&nbsp;</div>',
				'<div class="form_field"><a href="javascript:;" class="c_btn30_ fl submit"><span>登 录</span></a>',
				'<span class="fl ml20"><a href="'+KK._ucurl+'/passwd/findByEmail/" target="_blank">忘记密码</a><i class="c_999 ml5 mr5">|</i>',
				'<a href="javascript:;" class="pop_sign_chuanke">免费注册</a></span></div></div></form>',
				'<div style="display:none;" class="fl" id="quick-login">',
				'<div class="c_quickLogin">',
				'<h3 class="c_tit">检测到您已经登录KK帐号</h3>',
				'<ul class="c_ul">',
				'<li style="margin-top:20px;"><span class="c_nikename" id="quick-login-user">我是帐号昵称（10099）</span></li>',
				'<li><a href="javascript:;" id="loginBtn" class="c_btn30_" style="cursor:pointer;text-decoration:none;"><span>快速登录</span></a></li>',
				'<li><a href="javascript:;" id="quit-quick-login">使用其他帐号登录</a></li>',
				'<li><a href="'+KK._ucurl+'/passwd/findByEmail/" target="_blank">忘记密码</a><i class="c_999 ml5 mr5">|</i><a href="javascript:;" class="pop_sign_chuanke">免费注册</a></li></ul></div></div>',
				'<dl class="otherBox fr">',
				'<dt>使用合作网站帐号登录传课：</dt><dd><p><img src="http://res.ckimg.com/sites/www/v2/images/public/ico_bd_16.png" alt=""> <a class="SinaLoginURL" href="'+KK._baiduLogin+'">用百度帐号登录</a></p><p><img src="http://res.ckimg.com/sites/www/v2/images/public/ico_sina_16.png" alt=""> ', 
				'<a class="SinaLoginURL" href="'+KK._sinaLogin+'">用微博帐号登录</a></p><p><img src="http://res.ckimg.com/sites/www/v2/images/public/ico_qq_16.png" alt=""> ', 
				'<a class="QQLoginURL" href="'+KK._qqLogin+'">用QQ帐号登录</a></p><p><img src="http://res.ckimg.com/sites/www/v2/images/public/ico_rr_16.png" alt=""> ', 
				'<a class="QQLoginURL" href="'+KK._rrLogin+'">用人人帐号登录</a></p>', 
				'</dd></dl></div></div></div><a href="javascript:;" class="close">关闭</a></div><object classid="clsid:A0D5489F-51B3-4C17-B34B-E53B6162E9A4" id="Dean" name="Dean" width="0" height="0"></object></div>'
			].join('');
		$("body").append(html);
		if (typeof BangFXTag == 'function') {
   	 		BangFXTag('进入登录');
		}

		if($.browser.msie){
			try{
				var u = Dean.GetLoginInfo();
				var k = Dean.GetAuthKey();
				if (parseInt(u) > 0) {
					$.getJSON("/?mod=user&act=info&do=prelogin&uid="+u+"&k="+k+"&r="+Math.random(), function(ret){
						if (ret.code == 0) {
							$("#quick-login-user").html(ret.data.NickName+"（"+u+"）");
							$("#userLogin_form").hide();
							$("#quick-login").show();	
						}	
					});
				} 
			}catch(e){
				$("#userLogin_form").show();
				$("#quick-login").hide();
			}	
		}

		$("#quit-quick-login").click(function(){
			$("#userLogin_form").show();
			$("#quick-login").hide();
			return false;
		});

		$("#loginBtn").click(function(){
			var u = Dean.GetLoginInfo();
			var k = Dean.GetAuthKey();
			if (parseInt(u) > 0) {
				var url = location.href;
				url = KK.base64_encode(url);
				url = url.replace(/\//, '_');
				location.href = "http://passport.chuanke.com/login/quicklogin?uid="+u+"&k="+k+"&returnurl="+url;
			} else {
				alert("您的客户端已离线，请切换至普通登录模式");
			}
			return false;
		});

		$.getScript(KK._resurl+"/lib/jquery/plugin/jquery.md5.js");
		
		var dialogBox = $("#fe_dialogBox"),
			dialogMask = $("#fe_window_mask");
		dialogBox.find("form").inputTips();
		document.domain = 'chuanke.com';
		dialogBox.find("a.close").unbind().bind('click', function(){
			dialogMask.remove();
			dialogBox.hide().remove();
		});
		
		dialogBox.find("a.pop_sign_chuanke").unbind().bind('click', function(){
			dialogMask.remove();
			dialogBox.hide().remove();
			//$.ckBoxy({height:520,width:635,content:'<div id="pop_sign"><div class="pop-box"><iframe id="psptframe" src="http://passport.chuanke.com/reg/webpopreg/tm/'+new Date().getTime()+'" frameborder="0" scrolling="No" width="618" height="471"></iframe></div><a href="javascript:;" class="close" id="sign_close" title="关闭弹窗"></a></div>',title:"注册"});
	        //$("#psptframe").attr("src","http://passport.chuanke.com/reg/webpopreg/tm/"+new Date().getTime()+"/");
			var ckRegBoxy = $.ckAjaxBoxy({
					height:354,
					width:558,
					url:"/?mod=user&act=reg",
					title:"注册",
					callback : function(){
						$("#fe_dialogBox").find("a.close").unbind().click(function(){
							ckRegBoxy.cancel();
						});
					}
				});	        
		});
		
		dialogBox.find("input").keydown(function(event){
			if(event.keyCode == 13){
				dialogBox.find("a.submit").trigger('click');
			}
		});
		dialogBox.find("a.submit").unbind().bind('click', function(){
			var username = dialogBox.find("input[name='username']").val(),
				password = dialogBox.find("input[name='password']").val(),
				loginTips = dialogBox.find("p.login_tips"),
				preg = /^(([\w\d]+[\w\d-.]*@[\w\d-.]+\.[\w\d]{2,10})|(\d{5,10}))$/i;
			
			loginTips.text('');
			$('#fe_dialogBox #msgCaptip').css('display',"none");
			if(!preg.test(username)){
				loginTips.text("请输入正确的KK号或邮箱地址");
				dialogBox.find("input[name='username']").focus();
			}else if(password == ''){
				loginTips.text('请输入密码');
				dialogBox.find("input[name='password']").focus();
			}else{
				var loginUrl =  KK._ucurl+'/login/dologin'+"/username/"+encodeURIComponent(username)+"/password/"+encodeURIComponent($.md5($.trim(password)))+"/autologin/0/ajax/1";
				loginTips.text('登录中...');
				$.getScript(loginUrl, function(){
					if(typeof(retLogData) == 'undefined'){
						loginTips.text('登录超时，请重试');
					}else{
						var data = retLogData;
						if(data.code == 0){
							loginTips.text('登录成功，请稍后');
							$.ajax({
								url:"/?mod=tool&act=tool",
								type:"get",
								success:function(ret){
									if(options.success != false){
										options.success();
									}
									if (typeof BangFXTag == 'function') {
										BangFXTag('登录成功');
									}							
									document.location.reload();
								}
							});						
						}else{
							//未激活
							if(data.code == 0x02010006){
								window.location.href='http://passport.chuanke.com/login/notActive/user/'+encodeURIComponent(username);
							}
							if(data.code == 0x02010004){
								loginTips.text('请输入正确的密码');
								dialogBox.find("input[name='password']").val('').focus();						
							}
							if(data.code == 0x02010003){
								dialogBox.find("input[name='password']").val("");
								dialogBox.find("input[name='username']").focus();
								loginTips.text(data.result);
							}
							loginTips.text(data.result);
							dialogBox.find("input[name='password']").val("");
							dialogBox.find("input[name='username']").val("").focus();
							if(options.error != false){
								options.error();
							}
						}				
					}
				});
			}
			return false;
		});
	},
	
	flashCheck:function(){
		var hasFlash=0;         //是否安装了flash
		var flashVersion=0; //flash版本
		var isIE=$.browser.msie;      //是否IE浏览器
		var isWin8 = navigator.userAgent.indexOf("Windows NT 6.2") > -1 || navigator.userAgent.indexOf("Windows NT 6.3") > -1 || navigator.userAgent.indexOf("Windows 8") > -1;
	
		if(isIE){
			try{
				var swf = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
			}catch(e){
				if(isWin8 && isIE){
					alert("flash插件未正常加载。建议您在IE浏览器中，打开工具 - 安全菜单，取消Active X筛选，并重新打开浏览器尝试");
					return false;
				}
				document.write("您尚未正确安装adobe flash player控件或版本过低,请登陆http://get.adobe.com/cn/flashplayer/重新安装");
			}
			if(swf){
				hasFlash=1;
				VSwf=swf.GetVariable("$version");
				flashVersion=parseFloat(VSwf.split(" ")[1].split(",")[0])+parseFloat(0.1*VSwf.split(" ")[1].split(",")[1]);
			}
		}else{
			if (navigator.plugins && navigator.plugins.length > 0){
				var swf=navigator.plugins["Shockwave Flash"];
			    if (swf){
					hasFlash=1;
			        var words = swf.description.split(" ");
			        for (var i = 0; i < words.length; ++i){
			            if (isNaN(parseFloat(words[i]))) continue;
			            flashVersion = parseFloat(words[i]);
					}
			    }
			}
		}
		return {f:hasFlash,v:flashVersion};
	},	
	
    cookie:{
    	get:function(c_name){
			if(document.cookie.length>0){
				  c_start=document.cookie.indexOf(c_name + "=")
				  if(c_start!=-1){ 
						c_start=c_start + c_name.length+1 
						c_end=document.cookie.indexOf(";",c_start)
						if (c_end==-1) c_end=document.cookie.length
						return unescape(document.cookie.substring(c_start,c_end))
					} 
			 }
			 return "";
    	},
    	set:function(cookieName, cookieValue, seconds, path, domain, secure){
			var expires = new Date();
			expires.setTime(expires.getTime() + seconds);
			document.cookie = escape(cookieName) + '=' + escape(cookieValue)
			+ (expires ? '; expires=' + expires.toGMTString() : '')
			+ (path ? '; path=' + path : '/')
			+ (domain ? '; domain=' + domain : '')
			+ (secure ? '; secure' : '');    		
    	}
    }	
});