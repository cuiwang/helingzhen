	
	// 幻灯片事件 
$(window).load(function() {
		$('.flexslider').flexslider();
	});
$(function(){
	
	/* 商品图片ajax上传 */
	var url = SITEURL + '/index.php?act=store&op=silde_image_upload';
	$('.upload-btn').find('input[type="file"]').unbind().change(function(){
		var id = $(this).attr('id');
		var file_id = $(this).attr('file_id');
		ajaxFileUpload(url, id, file_id);
	});
	
    /* 图片控制 */
    var handle_pic, handler;

    $('*[nc_type="handle_pic"]').unbind().find('img:first').each(function(){
    	$(this).unbind();
    	if($(this).next().val() != ''){
    		$(this).hover(function(){
    			handle_pic = $(this).parents('*[nc_type="handle_pic"]');
    			handler = handle_pic.find('*[nc_type="handler"]');
    			handler.show();
    			handler.hover(function(){
    				$(this).show();
    				set_zindex(parents, "999");
    			},
    			function(){
    				$(this).hide();
    				set_zindex(parents, "0");
    			});
    			set_zindex(parents, '999');
    		},
    		function(){
    			handler.hide();
    			var parents = handler.parents();
    			set_zindex(parents, '0');
    		});
    	}
    });
    
	/* 删除图片 */
	$('span[nctype="drop_image"]').unbind().click(function(){
		var obj		= $(this).parents('li');
		var file_id	= obj.find('input[type="file"]').attr('file_id');
		var img_src	= obj.find('input[type="hidden"]:first').val();
		obj.find('img:first').attr('src', SITEURL+"/templates/default/images/loading.gif")
			.end().find('*[nc_type="handler"]').hide();
		$.getJSON('index.php?act=store&op=dorp_img',{file_id:file_id,img_src:img_src}, function(data){
			obj.find('img:first').attr('src', SITEURL+'/templates/images/member/default_sildeshow.gif')
				.end().find('input[type="file"]').attr('file_id','')
				.end().find('input[type="hidden"]:first').val('');
		});
		$.getScript("./resource/js/store_slide.js");
	});
});

/* 图片上传ajax */
function ajaxFileUpload(url, id, file_id)
{
	$('img[nctype="'+id+'"]').attr('src',SITEURL+"/templates/images/loading.gif");

	$.ajaxFileUpload
	(
		{
			url:url,
			secureuri:false,
			fileElementId:id,
			dataType: 'json',
			data:{name:'logan', id:id, file_id:file_id},
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					alert(data.error);
					$('img[nctype="'+id+'"]').attr('src','upload/common/default_goods_image.gif');
				}else
				{
					$('input[nctype="'+id+'"]').val(data.file_name);
					$('img[nctype="'+id+'"]').attr('src','upload/store/slide/'+data.file_name);
					$('#'+id).attr('file_id',data.file_id);
				}
				$.getScript("./resource/js/jquery.flexslider-min.js");
				$.getScript("./resource/js/store_slide.js");
			},
			error: function (data, status, e)
			{
				alert(e);
				$.getScript("./resource/js/store_slide.js");
			}
		}
	)
	return false;

}