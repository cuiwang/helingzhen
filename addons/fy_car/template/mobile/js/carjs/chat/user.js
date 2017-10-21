	var interval = 1;//断开后计时
	var new_interval = 1;//消息提醒计时
	var connect = 0;//连接状态
	var new_msg = 0;//新消息数
	var obj = {};
	var msg_dialog = {};
	var socket = {};
	var user_list = new Array();//所有会员信息
	var msg_list = new Array();//收到消息
	var left_list = new Array();//左侧的会员
	var right_list = new Array();//右侧的会员
	var dialog_show = 0;//对话框是否打开
	var user_show = 0;//当前选择的会员
	var user_max = 3;//会员数
	var msg_max = 20;//消息数
	var time_max = 10;//定时(分钟)刷新防止登录超时退出,为0时关闭
  $(function(){
		msg_dialog = DialogManager.create('new_msg_dialog');//初始化(执行一次)
		msg_dialog.setTitle('实时接收聊天信息中...');
		msg_dialog.setContents('<div id="new_msg_dialog" class="msg-windows"><div class="user-tab-bar"><div onclick="show_left();" class="arrow-l"><a href="Javascript: void(0);"></a></div>'+
								'<ul class="user-list" id="user_list"></ul><div onclick="show_right();" class="arrow-r"><a href="Javascript: void(0);"></a></div></div><div id="msg_list" class="msg-contnet"></div>'+
								'<div class="msg-input-box"><h3>输入聊天信息</h3><form id="msg_form"><textarea name="send_message" id="send_message" class="textarea" onkeyup="send_keyup(event);" onfocus="send_focus();" ></textarea>'+
								'<div class="msg-bottom"><div id="msg_count"></div><a href="JavaScript:void(0);" onclick="send_msg();" class="msg-button"><i></i>发送消息</a><div id="send_alert"></div></div></form></div>'+
								'</div>');
		msg_dialog.setWidth(640);
		obj = $("#new_msg_dialog");
		setTimeout("getconnect()",2000);
		$("#send_message").charCount({//输入字数控制
			allowed: 255,
			warning: 10,
			counterContainerID:'msg_count',
			firstCounterText:'还可以输入',
			endCounterText:'字',
			errorCounterText:'已经超出'
		});
		$('#dialog_manage_screen_locker').live('click', function() {
		  DialogManager.close('new_msg_dialog');
		});
		if(user['u_id'] != '') {
			setInterval( function () {
				$.get(SITEURL+'/api.php?act=get_session&key=member_id');
		  }, time_max*60000);
		}
	});
	window.onerror=function(){return true;}
	DialogManager.close = function(id){
		__DIALOG_WRAPPER__[id].hide();
		dialog_show = 0;
		ScreenLocker.unlock();
  }
	DialogManager.show = function(id){
		if (__DIALOG_WRAPPER__[id]) {
			__DIALOG_WRAPPER__[id].show();
			dialog_show = 1;
			ScreenLocker.lock();
			return true;
		}
		return false;
  }
	function send_state(){//向服务器请求页面中的相关会员的在线状态
		var u_list = {};
		var n = 0;
		if(layout == 'layout/store_layout.php') {
			$("a.message").each(function(){
				n++;
				var url = $(this).attr("href");
				var re = /member_id=(\w+)$/g;
				re.exec(url);
				var u_id = RegExp.$1;
			  if ( u_id > 0 && u_id != user['u_id'] ) u_list[u_id] = 0;
			});
		} else {
			switch (act_op){
				case "act_op"://不显示状态
					break;
				case "member_snsfriend_findlist"://会员中心好友中"查找好友"不显示状态
				case "member_snsfriend_follow"://会员中心好友中"我关注的"不显示状态
				case "member_snsfriend_fan"://会员中心好友中"关注我的"不显示状态
					break;
				case "brand_list":
				case "search_index":
					$(".shop a[member_id]").each(function(){
						n++;
						var u_id = $(this).attr("member_id");
						if ( u_id > 0 && u_id != user['u_id'] ) u_list[u_id] = 0;
					});
					break;
				default:
					$("a.message").each(function(){
						n++;
						var url = $(this).attr("href");
						var re = /member_id=(\w+)$/g;
						re.exec(url);
						var u_id = RegExp.$1;
					  if ( u_id > 0 && u_id != user['u_id'] ) u_list[u_id] = 0;
					});
					break;
			}
		}
		if (n > 0 ) socket.emit('get_state', u_list);
	}
	function get_state(list){//返回会员的状态并在页面显示
		var u_list = list['u_state'];
		set_user_list(list['user']);
		if(layout == 'layout/store_layout.php') {//店铺页面
			$("a.message").each(function(){
				var message_obj = $(".shop-info-details").find(".message");
				var url = message_obj.attr("href");
				var re = /member_id=(\w+)$/g;
				re.exec(url);
				var u_id = RegExp.$1;
				if($(".shop-info-details").find(".chat").size()==0) {//头部
					message_obj.after(get_chat(u_id,u_list[u_id]));
			  }
			  message_obj = $(".shop-card").find(".message");
			  url = message_obj.attr("href");
			  re = /member_id=(\w+)$/g;
				re.exec(url);
				u_id = RegExp.$1;
				if($(".shop-card").find(".chat").size()==0) {//中部店标处
					message_obj.after(get_chat(u_id,u_list[u_id]));
			  }
			});
		} else {
			switch (act_op){
				case "brand_list":
				case "search_index":
					$(".shop a[member_id]").each(function(){
						var u_id = $(this).attr("member_id");
						if($(this).next(".chat").size()==0) {
							$(this).after(get_chat(u_id,u_list[u_id]));
					  }
					});
					break;
				default:
					$("a.message").each(function(){
						var url = $(this).attr("href");
						var re = /member_id=(\w+)$/g;
						re.exec(url);
						var u_id = RegExp.$1;
						if($(this).next(".chat").size()==0) {
							$(this).after(get_chat(u_id,u_list[u_id]));
					  }
					});
					break;
			}
		}
	}
	function show_obj(){//弹出框
		if(user_show < 1) {
	    var d = DialogManager.create('user_alert');
	    d.setTitle('聊天信息');
	    d.setContents('message', {'text':'目前无对话(未选择聊天会员)'});
	    d.setWidth(270);
	    d.show('center');
	    setTimeout("DialogManager.close('user_alert')",3000);
	    return false;
		}
		msg_dialog.show('center',1);
		dialog_show = 1;
		$("#send_message").focus();
	}
	function send_focus(){
		$("#send_alert").html('');
	}
	function send_keyup(event){//回车发消息
		var t_msg = $.trim($("#send_message").val());
		if(event.keyCode == 13 && t_msg.length > 0) {
			send_msg();
		}
	}
	function send_msg(){//发消息
		if(user_show < 1) {
			$("#send_alert").html('未选择聊天会员');
			return false;
		}
		var msg = {};
		msg['f_id'] = user['u_id'];
		msg['f_name'] = user['u_name'];
		msg['t_id'] = user_show;
		msg['t_name'] = user_list[user_show]['u_name'];
		msg['t_msg'] = $.trim($("#send_message").val());
		if(msg['t_msg'].length < 1) {
			$("#send_alert").html('发送内容不能为空');
			return false;
		}
		if(msg['t_msg'].length > 255) {
			$("#send_alert").html('一次最多只能发送255字');
			return false;
		}
		if(connect < 1) {
			$("#send_alert").html('处于离线状态,稍后再试');
			return false;
		}
		$.ajax({
			type: "POST",
			url: SITEURL+'/index.php?act=web_chat&op=send_msg',
			dataType:"jsonp",
			data: msg,
			async: false,
		  success: function(t_msg){
				if(t_msg['error']) {
					$("#send_alert").html(''+t_msg['error']);
					return false;
				} else {
					if ( connect === 1 ) {
						socket.emit('send_msg', t_msg);
						$("#send_message").val('');
						$("#send_message").focus();
						$("#send_alert").html('');
						show_t_msg(t_msg);
						return true;
					} else {
						$("#send_alert").html('由于网络原因未发送成功,稍后再试');
						return false;
					}
				}
		  }
			});
	}
	function get_msg(list){//接收消息
		var msg = {};
		for (var k in list){
			msg = list[k];
			var m_id = msg['m_id'];
			var u_id = msg['f_id'];
			set_user(u_id,msg['f_name']);
			if (typeof msg['user'] === "object" && typeof msg['user']['avatar'] !== "undefined" ) {
				var user_info = msg['user'];
		  	var u_name = user_info['u_name'];
				set_user_info(u_id,"u_name",u_name);
				set_user_info(u_id,"s_id",user_info['s_id']);
				set_user_info(u_id,"s_name",user_info['s_name']);
				set_user_info(u_id,"avatar",user_info['avatar']);
				if ( user_info['online'] > 0 ) set_user_info(u_id,"online",1);
			}
			if ( typeof user_list[u_id]['avatar'] === "undefined" ) {//当没获得会员信息时异步调用一次
				var ajaxurl = SITEURL+'/index.php?act=web_chat&op=get_info&t=member&u_id='+u_id;
				$.ajax({
					type: "GET",
					url: ajaxurl,
					dataType:"jsonp",
					async: true,
				  success: function(member){
				  	var u_name = member['member_name'];
						set_user_info(u_id,"s_id",member['store_id']);
						set_user_info(u_id,"s_name",member['store_name']);
						set_user_info(u_id,"avatar",member['member_avatar']);
				  }
					});
			}
			msg_list[u_id][m_id] = msg;
			if(dialog_show == 0 || obj.find("li[select_u_id='"+u_id+"']").size()==0) {//没有打开对话窗口时计数
				user_list[u_id]['new_msg']++;
				new_msg++;
			} else {
				if ( user_show == u_id) {
					show_msg(u_id);//当前对话的会员消息设为已读
				} else {
					obj.find("li[select_u_id='"+u_id+"']").addClass("new_msg");
				}
			}
		}
		alert_msg();
	}
	function update_msg(u_id){//更新已读
		var u_name = user_list[u_id]['u_name'];
		user_list[u_id]['new_msg'] = 0;
		obj.find("li[select_u_id='"+u_id+"']").removeClass("new_msg");
		new_msg--;
		alert_msg();
	}
	function chat(u_id){//打开对话窗口
		if(user['u_id'] == '') {//未登录时弹出登录窗口
	    $("#chat_login").trigger("click");;
	    return ;
		}
		if(u_id == user['u_id']) return ;
		if ( typeof user_list[u_id] === "undefined" || typeof user_list[u_id]['avatar'] === "undefined" ) {
			var ajaxurl = SITEURL+'/index.php?act=web_chat&op=get_info&t=member&u_id='+u_id;
			$.ajax({
				type: "GET",
				url: ajaxurl,
				dataType:"jsonp",
				async: false,
			  success: function(member){
			  	var u_name = member['member_name'];
					if(u_name == '') return false;
					set_user_info(u_id,"u_name",u_name);
					set_user_info(u_id,"s_id",member['store_id']);
					set_user_info(u_id,"s_name",member['store_name']);
					set_user_info(u_id,"avatar",member['member_avatar']);
			  }
				});
		}
		update_user(u_id);
		show_msg(u_id);
		show_obj();
	}
	function show_dialog(){//显示窗口
		update_dialog();
		show_obj();
	}
	function update_dialog(){//显示会员的对话
		if ( new_msg < 1 ) return true;
		var select_user = 0;
		for (var u_id in user_list){
			if ( user_list[u_id]['new_msg'] > 0 ) {
				update_user(u_id);
				obj.find("li[select_u_id='"+u_id+"']").addClass("new_msg");
			}
		}
		select_user = obj.find(".new_msg").first().attr("select_u_id");
		if ( select_user > 0 ) show_msg(select_user);
	}
	function show_left(){//显示左侧会员
		if ( left_list.length < 1 ) return true;
		var u_id = left_list.pop();
		if(obj.find("li[select_u_id='"+u_id+"']").size() > 0 ) {
			show_left();
			return true;
		}
		if($.inArray(u_id, right_list) >= 0 ) {
			show_left();
			return true;
		}
		if(obj.find("li[select_u_id]").size() > user_max ) {
			var select_user = obj.find("li[select_u_id]").last().attr("select_u_id");
			right_list.push(select_user);
			close_dialog(select_user);
		}
		update_user(u_id,1);
		show_msg(u_id);
	}
	function show_right(){//显示右侧会员
		if ( right_list.length < 1 ) return true;
		var u_id = right_list.pop();
		if(obj.find("li[select_u_id='"+u_id+"']").size() > 0 ) {
			show_right();
			return true;
		}
		if($.inArray(u_id, left_list) >= 0 ) {
			show_right();
			return true;
		}
		if(obj.find("li[select_u_id]").size() > user_max ) {
			var select_user = obj.find("li[select_u_id]").first().attr("select_u_id");
			left_list.push(select_user);
			close_dialog(select_user);
		}
		update_user(u_id);
		show_msg(u_id);
	}
	function show_msg(u_id){//显示会员的消息
		user_show = u_id;
		var u_name = user_list[u_id]['u_name'];
		if(obj.find("div[select_user_msg='"+u_id+"']").size()==0) {
			obj.find("#msg_list").append('<div class="msg_list" select_user_msg="'+u_id+'"></div>');
			obj.find('#msg_list').perfectScrollbar();
		}
		obj.find(".msg_list").hide();
		obj.find("div[select_user_msg='"+u_id+"']").show();
		obj.find("li[select_u_id]").removeClass("select_user");
		obj.find("li[select_u_id='"+u_id+"']").addClass("select_user");
		var max_id = 0;
		for (var m_id in msg_list[u_id]){
			if(obj.find("div[m_id='"+m_id+"']").size()==0) {
				msg = msg_list[u_id][m_id];
				show_f_msg(msg);
				update_msg(u_id);
				delete msg_list[u_id][m_id];//删除消息
				if ( m_id > max_id ) max_id = m_id;
			}
		}
		var obj_msg = obj.find("div[select_user_msg='"+u_id+"']");
		obj.find("#msg_list").scrollTop(obj_msg.height());
		
		if ( max_id > 0 && connect === 1 ) socket.emit('del_msg', {'max_id':max_id,'f_id':u_id});
	}
	function show_f_msg(msg){//显示收到的消息
		var u_id = msg['f_id'];
		var user_info = user_list[u_id];
		var text_append = '';
		var obj_msg = obj.find("div[select_user_msg='"+u_id+"']");
		text_append += '<div class="from_msg" m_id="'+msg['m_id']+'">';
		text_append += '<span class="user-avatar"><img src="'+user_info['avatar']+'"></span>';
		text_append += '<dl><dt class="from-msg-time">';
		text_append += msg['add_time']+'</dt>';
		text_append += '<dd class="from-msg-text">';
		text_append += msg['t_msg']+'</dd>';
		text_append += '<dd class="arrow"></dd>';
		text_append += '</dl>';
		text_append += '</div>';
		obj_msg.append(text_append);
		var n = obj_msg.find("div[m_id]").size();
		if ( n >= msg_max && n % msg_max ==1) {
			obj_msg.append('<div clear_id="'+msg['m_id']+'" onclick="clear_msg('+u_id+','+msg['m_id']+
						');" class="clear_msg"><a href="Javascript: void(0);">清除已上历史消息</a></div>');
		}
		obj.find("#msg_list").scrollTop(obj_msg.height());
	}
	function show_t_msg(msg){//显示发出的消息
		var user_info = user;
		var u_id = msg['t_id'];
		var text_append = '';
		var obj_msg = obj.find("div[select_user_msg='"+u_id+"']");
		text_append += '<div class="to_msg" m_id="'+msg['m_id']+'">';
		text_append += '<span class="user-avatar"><img src="'+user_info['avatar']+'"></span>';
		text_append += '<dl><dt class="to-msg-time">';
		text_append += msg['add_time']+'</dt>';
		text_append += '<dd class="to-msg-text">';
		text_append += msg['t_msg']+'</dd>';
		text_append += '<dd class="arrow"></dd>';
		text_append += '</dl>';
		text_append += '</div>';
		obj_msg.append(text_append);
		var n = obj_msg.find("div[m_id]").size();
		if ( n >= msg_max && n % msg_max ==1) {
			obj_msg.append('<div clear_id="'+msg['m_id']+'" onclick="clear_msg('+u_id+','+msg['m_id']+
						');" class="clear_msg"><a href="Javascript: void(0);">清除已上历史消息</a></div>');
		}
		obj.find("#msg_list").scrollTop(obj_msg.height());
	}
	function del_msg(msg){//已读消息处理
  	var max_id = msg['max_id'];//最大的消息编号
  	var u_id = msg['f_id'];//消息发送人
  	for (var m_id in msg_list[u_id]){
  		if ( max_id >= m_id) {
  			delete msg_list[u_id][m_id];
				if ( user_list[u_id]['new_msg'] > 0 ) user_list[u_id]['new_msg']--;
				if ( new_msg > 0 ) new_msg--;
  		}
  	}
		alert_msg();
	}
	function alert_msg(){
		var new_n = 0;
		clearInterval(new_interval);
		if ( new_msg > 0 ) {//消息提醒
			new_interval = setInterval( function () {
				new_n++;
		    $("#new_msg").html('聊天消息('+new_msg+')');
		    if ( new_n % 3 > 1 ) $("#new_msg").html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		  }, 300);
	  } else {
	  	new_msg = 0;
	  	$("#new_msg").html('聊天消息('+new_msg+')');
	  }
	}
	function get_chat(u_id,online){//显示链接地址
		var add_html = '';
		if (u_id != user['u_id'] &&  u_id > 0 ) {
			var class_html = 'chat_offline';
			var text_html = '离线';
			if ( online > 0 ) {
				class_html = 'chat_online';
				text_html = '在线';
			}
			add_html = '<a class="chat '+class_html+'" title="在线联系" href="JavaScript:chat('+u_id+');">'+text_html+'</a>';
		}
		return add_html;
	}
	function clear_msg(u_id,m_id){//清除消息处理
		var obj_msg = obj.find("div[select_user_msg='"+u_id+"']");
		obj_msg.find("div[clear_id='"+m_id+"']").prevAll().remove();
		obj_msg.find("div[clear_id='"+m_id+"']").remove();
	}
	function set_user_list(list){//初始化会员列表
		for (var k in list){
			var user_info = list[k];
			var u_id = user_info['u_id'];
	  	var u_name = user_info['u_name'];
	  	var online = 0;
			if ( user_info['online'] > 0 ) online = 1;
			set_user_info(u_id,"u_name",u_name);
			set_user_info(u_id,"s_id",user_info['s_id']);
			set_user_info(u_id,"s_name",user_info['s_name']);
			set_user_info(u_id,"avatar",user_info['avatar']);
			set_user_info(u_id,"online",online);
	  }
	}
	function set_user(u_id,u_name){//初始化会员信息
		var user_info = new Array();
		user_info['u_id'] = u_id;
		user_info['u_name'] = u_name;
		user_info['new_msg'] = 0;
		user_info['online'] = 0;
		if ( typeof user_list[u_id] === "undefined" ) user_list[u_id] = user_info;
		if ( typeof msg_list[u_id] === "undefined" ) msg_list[u_id] = new Array();
	}
	function set_user_info(u_id,k,v){//设置会员信息
		if ( typeof user_list[u_id] === "undefined" ) set_user(u_id,'');
		user_list[u_id][k] = v;
	}
	function close_dialog(u_id){
		obj.find("li[select_u_id='"+u_id+"']").remove();
		obj.find("div[select_user_msg='"+u_id+"']").hide();
		user_show = 0;
		if(obj.find("li[select_u_id]").size()==0) DialogManager.close('new_msg_dialog');
	}
	function update_user(u_id,left){
		if(obj.find("li[select_u_id='"+u_id+"']").size()==0) {
			var user_info = user_list[u_id];
			var u_name = user_info['u_name'];
			var text_append = '';
			text_append += '<li class="user" select_u_id="'+u_id+'">';
			text_append += '<span class="user-avatar avatar-'+user_info['online']+'" title="'+u_name+'" onclick="show_msg('+u_id+');"><img alt="'+u_name+'" src="'+user_info['avatar']+'"></span>';
			text_append += '<span class="user-name" title="'+u_name+'" onclick="show_msg('+u_id+');">';
			text_append += u_name+'<em></em></span>';
			if ( user_info['s_name'] != "" ) text_append += '<span class="store-name" title="'+user_info['s_name']+'" onclick="show_msg('+u_id+');">'+user_info['s_name']+'</span>';
			text_append += '<a class="ac-ico" onclick="close_dialog('+u_id+');"></a>';
			text_append += '</li>';
			if(obj.find("li[select_u_id]").size() > user_max ) {
				var select_user = obj.find("li[select_u_id]").first().attr("select_u_id");
				if($.inArray(select_user, left_list) < 0 ) left_list.push(select_user);
				close_dialog(select_user);
			}
			if(left) {
				obj.find("#user_list").prepend(text_append);
			} else {
				obj.find("#user_list").append(text_append);
			}
			obj.find("#user_list").sortable({ items: 'li' });
		}
	}
	function getconnect(){
		$.getScript(connect_url+"/resource/socket.io.js", function(){
			clearInterval(interval);
			if ( typeof io === "object" ) {
			  socket = io.connect(connect_url, { 'resource': 'resource', 'reconnect': false });
			  socket.on('connect', function () {
				  send_state();
			    socket.on('get_state', function (u_list) {
			      get_state(u_list);
			    });
				  if(user['u_id'] == '') return false;//未登录时不取消息
			  	connect = 1;
			  	$("#new_msg").html('聊天消息('+new_msg+')');
			  	$("#float_msg").show();
			    socket.emit('update_user', user);
			    socket.on('get_msg', function (msg_list) {
			      get_msg(msg_list);
			    });
				  socket.on('del_msg', function (msg) {
				  	del_msg(msg);
				  });
				  socket.on('disconnect', function () {
				    connect = 0;
				    $("#float_msg").hide();
				    interval = setInterval( getconnect, 60000);//断开1分钟后重新连接服务器
				  });
			  });
		  }
		});
	}