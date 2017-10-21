$(document).ready(function(){
	/* ajax添加商品  */
	$('#bundling_add_goods').unbind().ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SITEURL+"/templates/default/images/loading.gif",
		target:'#bundling_add_goods_ajaxContent'
	});

	/* 从图片空间选择图片 */
	$('.albun_demo').unbind().ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SITEURL+"/templates/default/images/loading.gif",
		target:'#albun_demo'
	});
	
	/* 从图片空间选择图片 */
	$('.des_demo').unbind().ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SITEURL+"/templates/default/images/loading.gif",
		target:'#des_demo'
	});

    $('*[nc_type="handle_pic"]').unbind().each(function(){
    	if($(this).unbind().find('img:first').prev().val() != ''){
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
	/* 删除图片 */
	$('div[nctype="drop_image"]').unbind().click(function(){
		$(this).parents('li')
			.find('img:first').attr('src',SITEURL+"/templates/default/images/loading.gif")
			.end().find('*[nc_type="handler"]').hide()
			.end().find('input[type="hidden"]').val('')
			.end().find('img:first').attr('src',DEFAULT_GOODS_IMAGE);
		$.getScript("./resource/js/store_bundling.js");
	});

	$('tbody[nctype="bundling_data"]').sortable({ items: 'tr' });
	$('#goods_images').sortable({ items: 'li' });
});

/* 插入编辑器 */
function insert_editor(file_path){
	KE.appendHtml('goods_body', '<img src="'+ file_path + '">');
}

/* 计算商品原价 */
function count_cost_price_sum(){
	data_price = $('td[nctype="bundling_data_price"]');
	if(typeof(data_price) != 'undefined'){
		S_price = 0;
		data_price.each(function(){
			S_price += parseFloat($(this).html());
		});
		$('span[nctype="cost_price"]').html(S_price.toFixed(2));
	}else{
		$('span[nctype="cost_price"]').html('');
	}
}

/* 添加图片 */
function goods_img_add(){
	O = $('img[nctype="bundling_data_img"]');
	if(typeof(O) != 'undefined'){
		O.each(function(e){
			$('input[nctype="bundling_pic_input"]:eq('+e+')').val($(this).attr('ncname'));
			$('img[nctype="bundling_pic_img"]:eq('+e+')').attr('src',($(this).attr('src')));
		});
		$.getScript('./resource/js/store_bundling.js');
	}
}