$(document).ready(function(){
	bind_del_image();
	bind_del_item();
	bind_submit_deal_btn();
});

// 删除已上传的图片
function bind_del_image() {
	$(".image_item").find(".remove_image").live("click",function() {
		del_image($(this));
		hide_imgupload();
	});
}

// 上传4张图片后，隐藏上传图片按钮
function hide_imgupload() {
	var pic_box_num = $("#image_box").find(".image_item").length;
	var $fileupload_box = $(".fileupload_box");
	pic_box_num == 1 ? $fileupload_box.hide() : $fileupload_box.show();
}

function del_image(o) {
	$(o).parent().remove();
}

function bind_del_item() {
	$(".del_item").bind("click",function(){
		var ajaxurl = $(this).attr("href");
		var query = new Object();
		query.ajax = 1;
		$.showConfirm("确定删除该项吗？",function(){
			close_pop();
			$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						if(ajaxobj.info!="")
						{
							$.showSuccess(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									location.href = ajaxobj.jump;
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						}
					}
					else
					{
						if(ajaxobj.info!="")
						{
							$.showErr(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									location.href = ajaxobj.jump;
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						}							
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
		});
		
		return false;
	});
}

function bind_submit_deal_btn() {
	$("#submit_deal_btn").bind("click",function(){
		var ajaxurl = $(this).attr("url");
		var jump = $(this).attr("jump");
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status)
				{
					$.showSuccess(ajaxobj.info,function(){
						 location.href = jump;
					});
				}
				else
				{
					if(ajaxobj.jump!="")
						location.href = ajaxobj.jump;
					else
					$.showErr(ajaxobj.info);
				}
			},
			error:function(ajaxobj)
			{

			}
		});
	});
}
