// 分类选择
function selClass(obj){
	
	$('.wp_category_list').css('background','');
	
	$("#commodityspan").hide();
	$("#commoditydt").show();
	$("#commoditydd").show();
	$(obj).siblings('li').children('a').attr('class','');
	$(obj).children('a').attr('class','classDivClick');
	tonextClass(obj.id);
}
function tonextClass(text){
	var valarray = text.split('|');
	$('#class_id').val(valarray[0]);
	$('#t_id').val(valarray[2]);
	$('#dataLoading').show();
	$.getJSON('index.php?act=store_goods&op=ajax_goods_class',{
			'gc_id':valarray[0]
		},
		function(data){
    		if(data != null){
    			$('#button_next_step').attr('disabled',true);
				var a='';
				var class_div_id = parseInt(valarray[1])+1;
				for (i=0; i<data.length; i++) {
					a+='<li onclick="selClass(this);" id="'+data[i].gc_id+'|'+class_div_id+'|'+data[i].type_id+'"><a href="javascript:void(0)"><span class="has_leaf">'+data[i].gc_name+'</span></a></li>';
				}
				$('#class_div_'+class_div_id).parents('.wp_category_list').removeClass('blank');
				for (j=class_div_id; j<=4; j++) {
					$('#class_div_'+(j+1)).parents('.wp_category_list').addClass('blank');
				}
				$('#class_div_'+class_div_id).children('ul').empty();
				$('#class_div_'+class_div_id).children('ul').append(a);
				$('#class_div_'+class_div_id).nextAll('div').children('ul').empty();
				var str="";
				$.each($('a[class=classDivClick]'),function(i){
					str+=$(this).html()+"&nbsp;&gt;&nbsp;";
				});
				str=str.substring(0,str.length-16);
				$('#commoditydd').html(str);
				$('#commoditya').hide();	//添加到常用分类
				$('#dataLoading').hide();
    		}else {
    			for(var i= parseInt(valarray[1]); i<4; i++){
    				$('#class_div_'+(i+1)).children('ul').empty();
    			}
    			var str="";
    			$.each($('a[class=classDivClick]'),function(i){
    				str+=$(this).html()+"&nbsp;&gt;&nbsp;";
    			});
    			str=str.substring(0,str.length-16);
    			$('#commoditydd').html(str);
    			$('#commoditya').show();	//添加到常用分类
    			disabledButton();
    			$('#dataLoading').hide();
    		}
		}
	);
}
function disabledButton(){
	if($('#class_id').val() != ''){
		$('#button_next_step').attr('disabled',false).css('cursor','pointer');
	}else {
		$('#button_next_step').attr('disabled',true).css('cursor','auto');
	}
}

// 搜索栏文字显示灰色
$('#searchKey').css('color','rgb(153, 153, 153)');

// 分类搜索js
$('#searchKey').unbind().focus(function(){
	if($(this).val() == SEARCHKEY){
		$(this).val('');
	}
})
.blur(function(){
	if($(this).val() == ''){
		$(this).val(SEARCHKEY);
	}
});

// ajax查询分类TAG
$('#searchBtn').unbind().click(function(){
	$('#searchNone').hide();
	$('#searchSome').hide();
	if($('#searchKey').val() != SEARCHKEY && $('#searchKey').val() != ''){
		$('.wp_search_result').show();
		$('#searchLoad').show();
		$('.wp_sort').hide();
	    $.getJSON('index.php?act=store_goods&op=ajax_class_search&column=ok',{value:$('#searchKey').val()}, function(data){
            if (data == false){
            	$('#searchLoad').hide();
            	$('#searchNone').show();
            }else{
                if (data.length > 0){
                	var tag = '';
                    for (i = 0; i < data.length; i++){
                        tag +=('<li nc_type="searchList_name" id="'+data[i].gc_id+'|'+data[i].type_id+'">'+data[i].gc_tag_name+'</li>');
                    }
                    
                }
                $('#searchLoad').hide();
                $('#searchSome').show();
                $('#searchList > ul').html(tag);
                $.getScript(SHOP_RESOURCE_SITE_URL+"/js/jquery.goods_add_step1.js");
            }
	    });
	}else{
		alert(SEARCHKEY);
	}
});

// 返回分类选择
$('a[nc_type="return_choose_sort"]').unbind().click(function(){
	$('#class_id').val('');
	$('#t_id').val('');
	$("#commodityspan").show();
	$("#commoditydt").hide();
	$('#commoditydd').html('');
	$('.wp_search_result').hide();
	$('#commoditya').hide();
	$('.wp_sort').show();
});

// 分类搜索后选择
$('li[nc_type="searchList_name"]').unbind().click(function(){
	valuearray = $(this).attr('id').split('|');
	$('#class_id').val(valuearray[0]);
	$('#t_id').val(valuearray[1]);
	$("#commodityspan").hide();
	$("#commoditydt").show();
	$('#commoditydd').html($(this).html());
	$('#commoditya').show();
	disabledButton();
});

// 常用分类选择 展开与隐藏
$('#commSelect').unbind().click(function(){	
	if($('#commListArea').css('display') == 'none'){
		$('#commListArea').show();
	}else{
		$('#commListArea').hide();
	}
});

// ajax添加常用分类
$('#commoditya > a').unbind().click(function(){
	$.getJSON('index.php?act=store_goods&op=ajax_staple_control&column=add&class_id='+$('#class_id').val(), function(data){
		if (data.done == 'exist'){
            $('#select_list_no').hide();
            showTips(data.msg, 100, 1);			
		}else if(data.done){
            $('#commListArea > ul').prepend('<li nc_type="'+data.gc_id+'|'+data.staple_id+'|'+data.type_id+'"><span class="title">'+data.staple_name+'</span><a class="del_unavailable" href="JavaScript:void(0);"></a></li>');
            $('#select_list_no').hide();
            showTips(data.msg, 100, 1);
        }else{
        	alert(data.msg);
        }
	});
});

// 常用分类选择
$('#commListArea').find('span[class="title"]').die().live('click',function(){
	$('#dataLoading').show();
	$('.wp_category_list').addClass('blank')
	$.getJSON('index.php?act=store_goods&op=ajax_show_comm&name='+$(this).parent().attr('nc_type'), function(data){
		if (data.done){
			$('.category_list').children('ul').empty();
			if(data.one.length > 0){
				$('#class_div_1').children('ul').append(data.one).parents('.wp_category_list').removeClass('blank');
			}
			if(data.two.length > 0){
				$('#class_div_2').children('ul').append(data.two).parents('.wp_category_list').removeClass('blank');
			}
			if(data.three.length > 0){
				$('#class_div_3').children('ul').append(data.three).parents('.wp_category_list').removeClass('blank');
			}
		}else{
			$('.wp_category_list').css('background','#E7E7E7 none');
			$('#commListArea').find('li').css({'background':'','color':''});
			$(this).parent().css({'background':'#3399FD','color':'#FFF'});
		}
	});
	$('#dataLoading').hide();
	valarray = $(this).parent().attr('nc_type').split('|');
	$('#class_id').val(valarray[0]);
	$('#t_id').val(valarray[2]);
	$("#commodityspan").hide();
	$("#commoditydt").show();
	$('#commoditydd').show().html($(this).text());
	$('#commSelect').val($(this).text());
	disabledButton();
	$('#commListArea').hide();
	$('#commoditya').hide();
});

// ajax删除常用分类
$('#commListArea').find('a[class="del_unavailable"]').die().live('click',function(){
	li = $(this).parent();
	valarray = li.attr('nc_type').split('|');
	$.getJSON('index.php?act=store_goods&op=ajax_staple_control&column=del&class_id='+valarray[1], function(data){
		if (data.done){
			li.remove();
		}else{
			alert(data.msg);
		}
	});
});