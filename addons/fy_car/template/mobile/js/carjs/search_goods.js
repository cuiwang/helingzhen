$(function(){
    /* 筛选事件 */
    $("dd[nc_type='dd_filter']").click(function(){
    	Img = $(this).find('img');
    	dropParam(Img.attr('alt'),Img.attr('kid'),'del');
        return false;
    });
    $("#search_by_price").click(function(){
        replaceParam('price', $(this).siblings("input:first").val() + '-' + $(this).siblings("input:last").val());
        return false;
    });

    /* 显示方式 */
    $("[nc_type='display_mode']").click(function(){
        $(this).parent().attr('class', $(this).attr('ecvalue')+'-mode');
        setcookie('goodsDisplayMode', $(this).attr('ecvalue'));
        history.go(0);
    });
    //列表延时加载
    if($("#div_lazyload").length>0){
    	$('#div_lazyload').datalazyload({dataItem: '.item', loadType: 'item', effect: 'fadeIn', effectTime: 1000 });
    }

	// 筛选的下拉展开
	$(".select").hover(function(){
		$(this).addClass("over").next().css("display","block");
	},function(){
		$(this).removeClass("over").next().css("display","none");
	});
	$(".option").hover(function(){
		$(this).css("display","block");
	},function(){
		$(this).css("display","none");
	});
	/* 获得商品多图 */
	$('.list_pic').find('dl').live('mouseover',function(){
		if($(this).find('.slide-show').html() == null){
			var obj = $(this).find('.picture');
			var id 	= $(this).attr('nctype_goods');
			var url = $(this).find('a:first').attr('href');
			goods_name	= $(this).find('img:first').attr('title');
			$.getJSON('index.php?act=search&op=ajax_goods_more_image&goods_id='+id,function(data){

				if(data){
					var param = '<dd class="slide-show"><p></p><b></b><div class="picture"><span class="thumb"><i></i><a href="'+url+'" target="_blank"><img src="'+data[0]['small']+'" alt="'+goods_name+'" title="'+goods_name+'" onload="javascript:DrawImage(this,160,160);" /></a></span></div><ul>';
					for(var i=0;i<data.length;i++){
						param += '<li><a href="javascript:void(0);" class="slide_tiny" nctype="'+data[i]['small']+'"><span class="thumb size30"><i></i><img src="'+data[i]['tiny']+'" onload="javascript:DrawImage(this,30,30);" ></span><b></b></a></li>';
					}
					param += '</ul></dd>';
					obj.after(param);
				}else{
					obj.after('<dd class="slide-show" style="left:-9999px;"><dd>');
				}
			});
		}else{
			$(this).find('.slide-show').show();
		}
	});
	$('.list_pic').find('dl').live('mouseout',function(){
		$(this).find('.slide-show').hide();
	});
	/*  */
	$('.slide_tiny').live('mouseover',function(){
		small_image = $(this).attr('nctype');
		$(this).parents('.slide-show').find('img:first').attr('src',small_image);
	});
	
});

function setcookie(name,value){
    var Days = 30;   
    var exp  = new Date();   
    exp.setTime(exp.getTime() + Days*24*60*60*1000);   
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();   
}

/* 替换参数 */
function replaceParam(key, value, arg)
{
	if(!arguments[2]) arg = 'string';
    var params = location.search.substr(1).split('&');
    var found  = false;
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        // 如果存在分页，跳转到第一页
        if (pKey == 'curpage')
        {
            params[i] = 'curpage=1';
        }
        if(arg == 'string'){
	        if (pKey == key)
	        {
	            params[i] = key + '=' + value;
	            found = true;
	        }
        }else{
        	for(var j = 0; j < key.length; j++){
        		if(pKey ==  key[j]){
        			params[i] = key[j] + '=' + value[j];
    	            found = true;
        		}
        	}
        }
    }
    if (!found)
    {
        if (arg == 'string'){
            value = transform_char(value);
            params.push(key + '=' + value);
        }else{
        	for(var j = 0; j < key.length; j++){
        		params.push(key[j] + '=' + transform_char(value[j]));
        	}
        }
    }
    location.assign(SITEURL + '/index.php?' + params.join('&'));
}

/* 删除参数 */
function dropParam(key, id, arg)
{
	if(!arguments[2]) arg = 'string';
    var params = location.search.substr(1).split('&');
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if(arg == 'string'){

	        if (pKey == key)
	        {
	            params.splice(i, 1);
	        }
        }else if(arg == 'del'){
            pVal = arr[1].split(',')
            for (var j=0; j<pVal.length; j++){
            	if(pKey == key && pVal[j] == id){
            		pVal.splice(j, 1);
            		params.splice(i, 1, pKey+'='+pVal);
            	}
            }
        }else{
        	for(var j = 0; j < key.length; j++){
        		if(pKey == key[j]){
        			params.splice(i, 1);i--;
        		}
        	}
        }
    }
    location.assign(SITEURL + '/index.php?' + params.join('&'));
}
