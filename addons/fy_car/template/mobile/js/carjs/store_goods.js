$(function(){
    // 添加店铺分类
    $("#add_sgcategory").unbind().click(function(){
        $(".sgcategory:last").after($(".sgcategory:last").clone().val(0));
    });
    
    /* 商品图片ajax上传 */
	var url = SITEURL + '/index.php?act=store_goods&op=image_upload&upload_type=uploadedfile';
	$('.upload-btn').find('input[type="file"]').unbind().change(function(){
		var id = $(this).attr('id');
		ajaxFileUpload(url,id);
	});
    
    /* 图片控制 */
    var handle_pic, handler;

    $('*[nc_type="handle_pic"]').unbind().each(function(){
    	$(this).unbind();
    	if($(this).find('img:first').prev().val() != ''){
    		$(this).hover(function(){
    			var obj = $(this).find('img:first');
    			handler = $(this).find('*[nc_type="handler"]');
    			handler.show();
    			handler.hover(function(){
    				set_zindex($(this), "999");
    			},
    			function(){
    				set_zindex($(this), "0");
    			});
    			set_zindex($(this), '999');
    		},
    		function(){
    			handler.hide();
    			set_zindex($(this), '0');
    		});
    	}
    });
    

	/* 图片左移 */
	$('span[nctype="left"]').unbind().click(function(){
		var obj= $(this).parents('li');
		if(obj.prev().html() != null){
			obj.insertBefore(obj.prev());
			obj.find('*[nc_type="handler"]').hide();
			set_zindex(obj, '0');
		}
	});
	/* 图片右移 */
	$('span[nctype="right"]').unbind().click(function(){
		var obj= $(this).parents('li');
		if(obj.next().html() != null){
			obj.insertAfter(obj.next());
			obj.find('*[nc_type="handler"]').hide();
			set_zindex(obj, '0');
		}
	});
	/* 删除图片 */
	$('span[nctype="drop_image"]').unbind().click(function(){
		$(this).parents('li')
			.find('img:first').attr('src',SITEURL+"/templates/default/images/loading.gif")
			.end().find('*[nc_type="handler"]').hide()
			.end().find('input[type="hidden"]').val('')
			.end().find('img:first').attr('src',DEFAULT_GOODS_IMAGE);
		$.getScript("./resource/js/store_goods.js");
	});
	
	
	/* ajax打开图片空间 */
	$('.goods_demo').unbind().ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SITEURL+"/templates/default/images/loading.gif",
		target:'#demo'
	});
	
	$('.des_demo').unbind().ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SITEURL+"/templates/default/images/loading.gif",
		target:'#des_demo'
	});
	$('#li_1').click(function(){
		$('#li_1').attr('class','active');
		$('#li_2').attr('class','');
		$('#demo').hide();
	});
	$('#goods_demo').click(function(){
		$('#li_1').attr('class','');
		$('#li_2').attr('class','active');
		$('#demo').show();
	});

	$('.des_demo').click(function(){
		if($('#des_demo').css('display') == 'none'){
            $('#des_demo').show();
        }else{
            $('#des_demo').hide();
        }
	});
	/* ajax打开图片空间 end */
});

/* 插入编辑器 */
function insert_editor(file_path){
	KE.appendHtml('goods_body', '<img src="'+ file_path + '">');
}

/* 图片上传ajax */
function ajaxFileUpload(url, id)
{
	$('img[nctype="'+id+'"]').attr('src',SITEURL+"/templates/default/images/loading.gif");

	$.ajaxFileUpload
	(
		{
			url:url,
			secureuri:false,
			fileElementId:id,
			dataType: 'json',
			data:{name:'logan', id:id},
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					alert(data.error);
					$('img[nctype="'+id+'"]').attr('src',DEFAULT_GOODS_IMAGE);
				}else
				{
					//add_uploadedfile(data.file_name,id);
					$('input[nctype="'+id+'"]').val(data.file_name);
					$('img[nctype="'+id+'"]').attr('src',data.image_cover+data.file_name);
				}
				$.getScript("./resource/js/store_goods.js");
			},
			error: function (data, status, e)
			{
				alert(e);
				$.getScript("./resource/js/store_goods.js");
			}
		}
	)
	return false;

}
