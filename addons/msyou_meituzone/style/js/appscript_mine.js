	var $lists=$('#lists');
	
	$(function () {
		/*瀑布流<<<*/
	
	// layout Masonry again after all images have loaded
		$lists.imagesLoaded(function(){
			$lists.masonry({
				columnWidth: '.grid-sizer',
				itemSelector: '.item'
			});
		});
	
	
	/*
  var $grid = $lists.masonry({
    itemSelector: '.item',
    percentPosition: true,
    columnWidth: '.grid-sizer'
  });
  // layout Isotope after each image loads
//  $grid.imagesLoaded().progress( function() {
 //   $grid.masonry();
 // });  		
	*/	
		
		/*瀑布流>>>*/
	
	
		/*tab切换<<<*/
		window.optionchanged = false;
		$('#userTab a').click(function (e) {
			e.preventDefault();//阻止a链接的跳转行为
			$(this).tab('show');//显示当前选中的链接及关联的content
		})
		/*tab切换>>>*/

		 function state1(){
			 $(".yao").removeClass("yao2"); 
			 $(".yao").addClass("yao1");
			 setTimeout(state2,200);
		 }
		function state2(){	   
		     $(".yao").removeClass("yao1");
		     $(".yao").addClass("yao2"); 
			 setTimeout(state3,200);
		}
		function state3(){
		     $(".yao").removeClass("yao2");
			 setTimeout(state1,200);
		}
		state1();

		if (reply_musicurl.length>=0){
			playbox.init("playbox");
		}
		
		setTimeout(function(){
			$('#coverurl').animate({opacity:"0"},1000,'swing',
				function(){
					$(this).hide();
				}
			);
		},5000);
/*
		setTimeout(function() {
			// IE
			if(document.all) {
				document.getElementById("playbox").click();
			}
			// 其它浏览器
			else {
				var e = document.createEvent("MouseEvents");
				e.initEvent("touchstart", true, true);
				document.getElementById("playbox").dispatchEvent(e);
			}
		}, 2000);
*/
list('');
//	setTimeout(function(){;reloadlists(0,'');},2000);

	});
	
	function reloadlists(val,sstr){
		mytoggle($('#lists'),$('#page'));
		$('#page').hide();$('#page').css('opacity',0);
		$('#lists').css('opacity',1);
		$('#lists').show();
	  //$lists.masonry().append($boxes).masonry( 'appended', $boxes).masonry();
		$lists.masonry('remove', $lists.find('.item')).masonry();
		
//		$lists.masonry('remove',$lists.find('.item')).masonry('reloadItems');
/*
		$lists.append($boxes);
		// 等待新元素中的图片加载完毕
		$boxes.imagesLoaded(function(){
		// 调用瀑布流布局的appended方法
		    $lists.masonry('appended',  $boxes);
		});
*/

		
		//$lists.find('.item').remove();
		if (val==0){
			orderstr=orderstr0;
		}else{
			orderstr=orderstr1;
		}
		$('#lists').attr('scrolltop',0);
		pageIndex = 1;
        reload = true;
		list(sstr);
	}

function mytoggle(showobj,hideobj){
	hidetop=$(document).scrollTop();
	showtop=showobj.attr('scrolltop')===undefined?0:showobj.attr('scrolltop');
	hideobj.animate({opacity:"0"},500,'swing',
		function(){
			hideobj.hide();
			hideobj.attr('scrolltop',hidetop);
			showobj.show();
			$('body,html').scrollTop(showtop);
			showobj.animate({opacity:"1"},100,'swing',
				function(){
					//$('body,html').animate({scrollTop:showtop},100);
				}
			);
			
		});
};

function displayit(n){
	reload = true;
	for(i=0;i<100;i++){
		if(i==n){
			var id='menu_list'+n;
			if(document.getElementById(id).style.display=='none'){
				document.getElementById(id).style.display='';
				reload = false;
			}else{
				document.getElementById(id).style.display='none';
			}
		}else{
			if($('#menu_list'+i)){
				$('#menu_list'+i).css('display','none');
			}
		}
	}
}

function closeall(){
	var count = document.getElementById("top_menu").getElementsByTagName("ul").length;
	for(i=0;i<count;i++){
		document.getElementById("top_menu").getElementsByTagName("ul").item(i).style.display='none';
	}
	reload=true;
}

function usersubmit(){
	var url=index_url+"&usersubmit=1";
    $.ajax({
        url: url,
        data: {"nickname":$('#nickname').val(),"mobile": $('#mobile').val()},
        type: "post",
        dataType:"json",
        success: 
        	function (jsonData) {
	        	//alert(JSON.stringify(jsonData));
	            try {
	                if (!jsonData.error){
	                	showMsg({'msg':'个人信息更新成功！'});
		                closeall();
	                }else{
	                	showMsg({'msg':jsonData.error});
	                }
	            } catch (e) {
					showMsg({'msg':'加载异常！'+e.message});
	            }
            },
            error: function(data, status, e){
				showMsg({"msg":  "<label>"+JSON.stringify(data)+"</label><label>"+JSON.stringify(e)+"</label>"});
		    }
        });
}



    var pageIndex = 1;
	var pageSize = reply_pagesize;
    var orderstr = ' createtime desc ';
    var orderstr0 = ' createtime desc ';
    var orderstr1 = ' sumcount desc ';
    var isload = true; //判断是否存在下一页
    var reload = true; //判断是否重复加载



	if (GPC_pageid){
    	showpage(GPC_pageid)
	}
    
    function list(sstr) {
        if (reload&&$('#lists').is(":visible")) {
            reload = false;
        }else{
        	return;
        }
		var url=index_url+"&showmore=1";
        $.ajax({
            url: url,
            data: {"pageIndex": pageIndex, "pageSize": pageSize, "orderstr": orderstr,"searchstr":sstr},
            type: "post",
            dataType:"json",
            success: function (jsonData) {
            	//alert(JSON.stringify(jsonData));
                try {
                    if (!jsonData.error){
	                    if (jsonData.list.length == 0 || ((jsonData.total <= (pageIndex + 1) * pageSize) && (sstr=""))) {
	                    	showMsg({'msg':'已经全部加载！'});
	                    }else{
	                    	replycount(jsonData.replycount);
	                    
		                    reload = true;
		                    pageIndex += 1;
		                    var html = "";

		                    $.each(jsonData.list, function (i, e) {
		                        //html += getHtml(111, "asdfasdf", e.createtime, e.points, e.consumetime, e.statuss);
		                        html += getHtml(e);
		                    });
		                    
		                    html+='<div class="item" style="min-width:200px;min-height:200px;"> <div class="tc-gridbox" style="min-width:200px;min-height:200px;text-align: center;">'
		                    +'<iframe frameborder="no" scrolling="no" style="height: 200px;width: 200px;overflow:hidden;" src="http://www.msyou.cc/baiduhtml.html"></iframe></div></div>';
		                    var $boxes = $( html );
		                    //$lists.masonry().append($boxes).masonry( 'appended', $boxes).masonry();
		                    
		                    $lists.append($boxes);
							// 等待新元素中的图片加载完毕
							$boxes.imagesLoaded(function(){
							 
							// 调用瀑布流布局的appended方法
							    $lists.masonry('appended',  $boxes);
							});
	                    }
                    }else{
                    	showMsg({'msg':jsonData.error});
                    }
                } catch (e) {
					showMsg({'msg':'加载异常！'+e.message});
                }
            },
            error: function(data, status, e){
				showMsg({"msg":  "<label>"+JSON.stringify(data)+"</label><label>"+JSON.stringify(e)+"</label>"});
				//$(document.body).append("<label>data:"+JSON.stringify(data)+"</label><label>"+json.stringify(status)+"</label><label>e:"+JSON.stringify(e)+"</label>");
		    }
        });
    }

	function replycount(e){
//		alert('replycount:'+JSON.stringify(e));
		if (e==null){
			return;
		}
		$('#replycount').html(''
+'						<td style="width:24%;border-right: 1px solid #ddd;text-align:center;">'+(e.joincount===undefined?0:e.joincount)
+'						</td>'
+'						<td style="width:24%;border-right: 1px solid #ddd;text-align:center;">'+(e.zancount===undefined?0:e.zancount)
+'						</td>'
+'						<td style="width:24%;border-right: 1px solid #ddd;text-align:center;">'+(e.sharecount===undefined?0:e.sharecount)
+'						</td>'
+'						<td style="width:24%;border-right: 1px solid #ddd;text-align:center;">'+(e.viewcount===undefined?0:e.viewcount)
+'						</td>');
	}
	
	function listcount(lid,e){
//		alert('listcount:'+JSON.stringify(lid));
		if (e==null){
			return;
		}
		$('.zan'+lid).html(e.zancount);
		$('.share'+lid).html(e.sharecount);
		$('.view'+lid).html(e.sharecount);
	}
	
    function showpage(lid){
    	reload = false;
//    	alert($('#page>.tc-gridbox').html());
    	var url=index_url;
        $.ajax({
            url: url,
            data: {'lid':lid,'showpage':1},
            type: "post",
            dataType:"json",
            success: function (jsonData) {
            	//alert(JSON.stringify(jsonData));
                if (!jsonData.error){

                   	replycount(jsonData.replycount);
					upd_sharedata(jsonData.p_share);
					
                	e=jsonData.list;
                	content=JSON.parse(e.content);
                	
                	
                    var html = '<div class="header">'
+'                        <div>'
+'	                        <div class="pull-left">'
+'	                        	<small>编号</small>'
+'		                        <h3 style="font-size:18px;">'+e.bh+'</h3>'
+'	                        </div>'
+'	                        <div class="pull-right" id="minepm">'
+'		                        <div class="pull-right" style="padding:0 5px 0 5px;">'
+'		                        	<small>排名</small>'
+'			                        <h3 style="font-size:18px;">'+e.pm+'</h3>'
+'		                        </div>'
+'		                        <div class="pull-right" style="padding:0 5px 0 5px;">'
+'		                        	<small>得分</small>'
+'			                        <h3 style="font-size:18px;">'+e.sumcount+'</h3>'
+'		                        </div>'
+'	                        </div>'
+'	                        <div class="clearfix">'
+'	                        	<small>昵称</small>'
+'		                        <h3 style="font-size:18px;">'+(e.nickname===undefined?'我叫雷锋':e.nickname)+'</h3>'
+'	                        </div>'
+'                        </div>'
+'                        <hr>'
+'					</div>'
+'					<div class="body">'
+content.content
				$.each(content.imglists, function (i, e) {
					if (e){
						html += '<div class="image" style="padding:5px;text-align: center;"><img  src="'+e+'" onerror="this.src=\''+reply_topurl+'\'" class="img-responsive"></div>';
					}
				});
				html+='</div>'
+'                    <div class="footer">'
+'                    	<div class="pull-left" style="padding:5px;">'
+'							<i class="fa fa-thumbs-up yao" style="font-size:32px;color:#428bca;"></i><span class="zan'+e.id+'" style="font-size:22px;color:#fff;line-height:40px;">'+e.zancount+'</span>'
+'						</div>'
+'                        <div class="clearfix" onclick="dianzan('+e.id+');" style="background-color: rgba(72,64,60,0.8);color: #fff;text-align:center;">'
+'                        	<h3 style="color:#fff;font-size:22px;line-height:28px">为我点赞</h3>'
+'                        </div>'
+'                    </div>'
+'					<div class="footer">'
+'						<div class="pull-left">'
+'							<span class="meta">'+e.createtime+'</span>'
+'						</div>'
+'						<div class="pull-right">'
+'							<i class="fa fa-share-alt"></i><span class="count share'+e.id+'">'+e.sharecount+'</span>'
+'							<i class="fa fa-eye"></i><span class="count view'+e.id+'">'+e.viewcount+'</span>'
+'						</div>'
+'						<div class="clearfix"></div>'
+'					</div>'
+'				</div>';
                    $("#page>.tc-gridbox").html(html);
			    	mytoggle($('#page'),$('#lists'));
                }else{
                   	showMsg({"msg":jsonData.error});
                }
            },
            error: function(data, status, e){
				showMsg({"msg":  "<label>"+JSON.stringify(data)+"</label><label>"+JSON.stringify(e)+"</label>"});
				//$(document.body).append("<label>data:"+JSON.stringify(data)+"</label><label>"+json.stringify(status)+"</label><label>e:"+JSON.stringify(e)+"</label>");
		    }
        });
    }
    
	function getHtml(e) {
        var html = '';
//        alert(JSON.stringify(e));
        content=JSON.parse(e.content);
//        alert(JSON.stringify(content));
//		$.each(content.imglists, function (i, e) {
//			if (e){alert(e);}
//		});
        html = '<div class="item" '+(reply_indexshowzan==0?'onclick="showpage('+e.id+')"':'')+'>'
+'						<div class="tc-gridbox">'
+'	                        <div class="header">'
+'								<div class="item-image" '+(reply_indexshowzan==1?'onclick="showpage('+e.id+')"':'')+'>'
+'									<img  src="'+e.imgurl+'" onerror="this.src=\''+reply_topurl+'\'" class="img-responsive" style="width:100%;">'
+'								</div>'
	+(reply_indexshownick==1?'                          <div>'
+'	                        <div class="pull-left">'
+'	                        	<small>编号</small>'
+'		                        <label style="font-size:18px;">'+e.bh+'</label>'
+'	                        </div>'
+'	                        <div class="pull-right" id="minepm">'
+'		                        <div class="pull-right" style="padding:0 5px 0 5px;">'
+'		                        	<small>排名</small>'
+'			                        <label style="font-size:18px;">'+e.pm+'</label>'
+'		                        </div>'
+'	                        </div>'
+'	                        <div class="clearfix">'
+'	                        	<small>昵称</small>'
+'		                        <label>'+(e.nickname===undefined?'我叫雷锋':e.nickname)+'</label>'
+'	                        </div>'
+'                        </div>':'')
	+(reply_indexshowzan==1?'                        <div class="clearfix" onclick="dianzan('+e.id+');" style="background-color: rgba(72,64,60,0.8);color: #fff;text-align:center;">'
+'                        	<h3 style="color:#fff;height: 30px;line-height: 30px;">为我点赞</h3>'
+'                        </div>':'')
+'	                            <hr>'

+'	                        </div>'
+(reply_indexshowcontent==1?'	                        <div class="body">'
+'	                        	'+content.content
+'	                        </div>':'')
+'	                        <div class="footer">'
+'	                        	<div class="pull-left"><span class="meta">'+e.createtime+'</span></div>'
+'	                        	<div class="pull-right">'
+'									<i class="fa fa-thumbs-up"></i><span class="count zan'+e.id+'">'+e.zancount+'</span>'
+'									<i class="fa fa-share-alt"></i><span class="count share'+e.id+'">'+e.sharecount+'</span>'
+'		                        	<i class="fa fa-eye"></i><span class="count view'+e.id+'">'+e.viewcount+'</span>'
+'								</div>'
+'								<div class="clearfix"></div>'
+'							</div>'
+'						</div>'
+'                </div>';
        return html;
    }

    function dianzan(lid) {
		var url=index_url;
        $.ajax({
            url: url,
            data: {'lid':lid,'dianzan':1},
            type: "post",
            dataType:"json",
            success: function (jsonData) {
//            	alert(JSON.stringify(jsonData));
                try {
                    if (!jsonData.error){
                    	replycount(jsonData.replycount);
                    	listcount(jsonData.listid,jsonData.listcount);
	                    showMsg({'msg':'点赞 成功！'});
                    }else{
                    	showMsg({'msg':jsonData.error});
                    }
                } catch (e) {
					showMsg({'msg':'加载异常！'+e.message});
                }
            },
            error: function(data, status, e){
				showMsg({"msg":  "<label>"+JSON.stringify(data)+"</label><label>"+JSON.stringify(e)+"</label>"});
				//$(document.body).append("<label>data:"+JSON.stringify(data)+"</label><label>"+json.stringify(status)+"</label><label>e:"+JSON.stringify(e)+"</label>");
		    }
        });
    }

   
    $(function () {
        $(window).scroll(function () {
            var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
                //showMsg({"msg":"body:"+$(document.body).height()+"<br>document:"+$(document).height()+"<br>window:"+$(window).height()+"<br>$(window).scrollTop():"+$(window).scrollTop()});
                //showMsg({"msg":"totalheight:"+totalheight+"<br>>="+$(document.body).height()});
            if (totalheight>=$(document.body).height()) {
            	//alert("list");
                setTimeout(list(''),200);
            }
        });
    });