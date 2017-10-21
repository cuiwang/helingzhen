//公共的JS方法
$(window).on('load',function(){
	var activity_id = document.getElementById('activity_id').innerHTML;
	//var channel_id = document.getElementById('channel_id').innerHTML;
	var channel_id = location.search.substr(location.search.indexOf("channel=") + 8);
	channel_id= channel_id.match(/^\d+/) ; 
	if (!channel_id || isNaN(channel_id) || channel_id<0) {
		channel_id = 1;
	}
	var url_string = location.href;
	var urlArray = url_string.split('/');
	url_string = urlArray[0] +'//'+ urlArray[1] +urlArray[2] +'/'+ urlArray[3]+"/analyse/" + activity_id + "?channel=" + channel_id;
	
	//统计当前页数据
	$.ajax({
		type : "POST",
		cache : false,
		url : url_string,
		dataType : "json",
		success : function(msg) {
			
			if (msg.result) {
				//alert('操作成功！');
			} else {
				//alert("操作失败，" + msg.msg);
			}
		}
	});
	
	$("#fastcall,#share,#appdownload").click(function(e){
		e = e||window.event;
		var plug_target = e.target||e.srcElement;
		$.ajax({
			   type: "get",
			   url: "/analyseplugin/plugin?activity_id=" + activity_id + "&plugtype="+$(this).attr('id'),
			   dataType: "json",
			   success: function(msg){
				   if(msg.result==1){
					   //alert('操作成功！');
					}else{
						//alert("操作失败，" + msg.msg);
					}
			   }
			});
	});
})
