function drop_confirm(msg, url){
    if(confirm(msg)){
        window.location = url;
    }
}
function go(url){
    window.location = url;
}
/* 格式化金额 */
function price_format(price){
    if(typeof(PRICE_FORMAT) == 'undefined'){
        PRICE_FORMAT = '&yen;%s';
    }
    price = number_format(price, 2);

    return PRICE_FORMAT.replace('%s', price);
}
function number_format(num, ext){
    if(ext < 0){
        return num;
    }
    num = Number(num);
    if(isNaN(num)){
        num = 0;
    }
    var _str = num.toString();
    var _arr = _str.split('.');
    var _int = _arr[0];
    var _flt = _arr[1];
    if(_str.indexOf('.') == -1){
        /* 找不到小数点，则添加 */
        if(ext == 0){
            return _str;
        }
        var _tmp = '';
        for(var i = 0; i < ext; i++){
            _tmp += '0';
        }
        _str = _str + '.' + _tmp;
    }else{
        if(_flt.length == ext){
            return _str;
        }
        /* 找得到小数点，则截取 */
        if(_flt.length > ext){
            _str = _str.substr(0, _str.length - (_flt.length - ext));
            if(ext == 0){
                _str = _int;
            }
        }else{
            for(var i = 0; i < ext - _flt.length; i++){
                _str += '0';
            }
        }
    }

    return _str;
}
/* 火狐下取本地全路径 */
function getFullPath(obj)
{
    if(obj)
    {
        //ie
        if (window.navigator.userAgent.indexOf("MSIE")>=1)
        {
            obj.select();
            if(window.navigator.userAgent.indexOf("MSIE") == 25){
                obj.blur();
            }
            return document.selection.createRange().text;
        }
        //firefox
        else if(window.navigator.userAgent.indexOf("Firefox")>=1)
        {
            if(obj.files)
            {
                //return obj.files.item(0).getAsDataURL();
                return window.URL.createObjectURL(obj.files.item(0)); 
            }
            return obj.value;
        }
        return obj.value;
    }
}
/* 转化JS跳转中的 ＆ */
function transform_char(str)
{
    if(str.indexOf('&'))
    {
        str = str.replace(/&/g, "%26");
    }
    return str;
}
//图片垂直水平缩放裁切显示
(function($){
    $.fn.VMiddleImg = function(options) {
        var defaults={
            "width":null,
"height":null
        };
        var opts = $.extend({},defaults,options);
        return $(this).each(function() {
            var $this = $(this);
            var objHeight = $this.height(); //图片高度
            var objWidth = $this.width(); //图片宽度
            var parentHeight = opts.height||$this.parent().height(); //图片父容器高度
            var parentWidth = opts.width||$this.parent().width(); //图片父容器宽度
            var ratio = objHeight / objWidth;
            if (objHeight > parentHeight && objWidth > parentWidth) {
                if (objHeight > objWidth) { //赋值宽高
                    $this.width(parentWidth);
                    $this.height(parentWidth * ratio);
                } else {
                    $this.height(parentHeight);
                    $this.width(parentHeight / ratio);
                }
                objHeight = $this.height(); //重新获取宽高
                objWidth = $this.width();
                if (objHeight > objWidth) {
                    $this.css("top", (parentHeight - objHeight) / 2);
                    //定义top属性
                } else {
                    //定义left属性
                    $this.css("left", (parentWidth - objWidth) / 2);
                }
            }
            else {
                if (objWidth > parentWidth) {
                    $this.css("left", (parentWidth - objWidth) / 2);
                }
                $this.css("top", (parentHeight - objHeight) / 2);
            }
        });
    };
})(jQuery);
function DrawImage(ImgD,FitWidth,FitHeight){
    var image=new Image();
    image.src=ImgD.src;
    if(image.width>0 && image.height>0)
    {
        if(image.width/image.height>= FitWidth/FitHeight)
        {
            if(image.width>FitWidth)
            {
                ImgD.width=FitWidth;
                ImgD.height=(image.height*FitWidth)/image.width;
            }
            else
            {
                ImgD.width=image.width;  
                ImgD.height=image.height;  
            }
        }
        else
        {
            if(image.height>FitHeight)
            {
                ImgD.height=FitHeight;
                ImgD.width=(image.width*FitHeight)/image.height;
            }
            else
            {
                ImgD.width=image.width;
                ImgD.height=image.height;
            }
        }  
    }
}

/**
 * 浮动DIV定时显示提示信息,如操作成功, 失败等
 * @param string tips (提示的内容)
 * @param int height 显示的信息距离浏览器顶部的高度
 * @param int time 显示的时间(按秒算), time > 0
 * @sample <a href="javascript:void(0);" onclick="showTips( '操作成功', 100, 3 );">点击</a>
 * @sample 上面代码表示点击后显示操作成功3秒钟, 距离顶部100px
 * @copyright ZhouHr 2010-08-27
 */
function showTips( tips, height, time ){
    var windowWidth = document.documentElement.clientWidth;
    var tipsDiv = '<div class="tipsClass">' + tips + '</div>';

    $( 'body' ).append( tipsDiv );
    $( 'div.tipsClass' ).css({
        'top' : 200 + 'px',
        'left' : ( windowWidth / 2 ) - ( tips.length * 13 / 2 ) + 'px',
        'position' : 'fixed',
        'padding' : '20px 50px',
        'background': '#EAF2FB',
        'font-size' : 14 + 'px',
        'margin' : '0 auto',
        'text-align': 'center',
        'width' : 'auto',
        'color' : '#333',
        'border' : 'solid 1px #A8CAED',
        'opacity' : '0.90',
        'z-index' : '9999'
    }).show();
    setTimeout( function(){$( 'div.tipsClass' ).fadeOut().remove();}, ( time * 1000 ) );
}

function trim(str) {
    return (str + '').replace(/(\s+)$/g, '').replace(/^\s+/g, '');
}
//弹出框登录
function login_dialog(){
    CUR_DIALOG = ajax_form('login','登录',SITEURL+'/index.php?act=login&inajax=1',360,1);
}

/* 显示Ajax表单 */
function ajax_form(id, title, url, width, model)
{
    if (!width)	width = 480;
    if (!model) model = 1;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents('ajax', url);
    d.setWidth(width);
    d.show('center',model);
    return d;
}
//显示一个内容为自定义HTML内容的消息
function html_form(id, title, _html, width, model) {
    if (!width)	width = 480;
    if (!model) model = 0;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents(_html);
    d.setWidth(width);
    d.show('center',0);
    return d;
}
//收藏店铺js
function collect_store(fav_id,jstype,jsobj){
    $.get('index.php?act=index&op=login', function(result){
        if(result=='0'){
            login_dialog();
        }else{
            var url = 'index.php?act=member_favorites&op=favoritesstore';
            $.getJSON(url, {'fid':fav_id}, function(data){
                if (data.done){
                showDialog(data.msg, 'succ','','','','','','','','',2);
                if(jstype == 'count'){
                    $('[nctype="'+jsobj+'"]').each(function(){
                        $(this).html(parseInt($(this).text())+1);
                    });
                }
                if(jstype == 'succ'){
                    $('[nctype="'+jsobj+'"]').each(function(){
                        $(this).html("收藏成功");
                    });
                }
                if(jstype == 'store'){
                    $('[nc_store="'+fav_id+'"]').each(function(){
                        $(this).before('<span class="goods-favorite" title="该店铺已收藏"><i class="have">&nbsp;</i></span>');
                        $(this).remove();
                    });
                }
            }
                else
                {
                    showDialog(data.msg, 'notice');
                }
            });
        }
    });
}
//收藏商品js
function collect_goods(fav_id,jstype,jsobj){
    $.get('index.php?act=index&op=login', function(result){
        if(result=='0'){
            login_dialog();
        }else{
            var url = 'index.php?act=member_favorites&op=favoritesgoods';
            $.getJSON(url, {'fid':fav_id}, function(data){
                if (data.done)
            {
                showDialog(data.msg, 'succ','','','','','','','','',2);
                if(jstype == 'count'){
                    $('[nctype="'+jsobj+'"]').each(function(){
                        $(this).html(parseInt($(this).text())+1);
                    });
                }
                if(jstype == 'succ'){
                    $('[nctype="'+jsobj+'"]').each(function(){
                        $(this).html("收藏成功");
                    });
                }
            }
                else
            {
                showDialog(data.msg, 'notice');
            }
            });
        }
    });
}
//加载购物车信息
function load_cart_information(){
	$.getJSON(SITEURL+'/index.php?act=cart&op=ajaxcart&callback=?', function(result){
	    if(result){
	        var result  = result;
	       	$('.goods_num').html(result.goods_all_num);
	       	var html = '';
	       	if(result.goods_all_num >0){
	       		html+="<div class='order'><table border='0' cellpadding='0' cellspacing='0'>";
	       		var i= 0;
	       		var data = result['goodslist'];
	            for (i = 0; i < data.length; i++)
	            {
	            	html+="<tr id='cart_item_"+data[i]['specid']+"' count='"+data[i]['num']+"'>";
	            	html+="<td class='picture'><span class='thumb size40'><i></i><img src='"+data[i]['images']+"' title='"+data[i]['gname']+"' width='40px' height='40px' ></span></td>";
	            	html+="<td class='name'><a href='"+SITEURL +"/index.php?act=goods&goods_id="+data[i]['goodsid']+"' title='"+data[i]['gname']+"' target='_top'>"+data[i]['gname']+"</a></td>";
		          	html+="<td class='price'><p>&yen;"+data[i]['price']+"×"+data[i]['num']+"</p><p><a href='javascript:void(0)' onClick='drop_topcart_item("+data[i]['storeid']+","+data[i]['specid']+");' style='color: #999;'>删除</a></p></td>";
		          	html+="</tr>";
		        }
	         	html+="<tr><td colspan='3' class='no-border'><span class='all'>共<strong class='goods_num'>"+result.goods_all_num+"</strong>种商品   金额总计：<strong id='cart_amount'>&yen;"+result.goods_all_price+"</strong></span><span class='button' ><a href='"+SITEURL+"/index.php?act=cart' target='_top' title='结算商品' style='color: #FFF;' >结算商品</a></span></td></tr>";
	      }else{
	      	html="<div class='no-order'><span>您的购物车中暂无商品，赶快选择心爱的商品吧！</span><a href='"+SITEURL+"/index.php?act=cart' class='button' target='_top' title='查看购物车' style=' color: #FFF;' >查看购物车</a></div>";
	        }
	        $("#top_cartlist").html(html);
	   }
	});
}

//头部删除购物车信息
function drop_topcart_item(store_id, spec_id){
    var tr = $('#cart_item_' + spec_id);
    var amount_span = $('#cart_amount');
    var cart_goods_kinds = $('.goods_num');
    $.getJSON(SITEURL+'/index.php?act=cart&op=drop&specid=' + spec_id + '&storeid=' + store_id + '&callback=?', function(result){
        if(result.done){
            //删除成功
            if(result.quantity == 0){
            	$('.goods_num').html('0');
            	var html = '';
            	html="<div class='no-order'><span>您的购物车中暂无商品，赶快选择心爱的商品吧！</span><a href='"+SITEURL+"/index.php?act=cart' class='button' target='_top' title='查看购物车' style=' color: #FFF;' >查看购物车</a></div>";
            	$("#top_cartlist").html(html);
            }
            else{
                tr.remove();        //移除
                amount_span.html(price_format(result.amount));  //刷新总费用
                cart_goods_kinds.html(result.quantity);       //刷新商品种类
            }
        }else{
            alert(result.msg);
        }
    });
}
/*
 * 登录窗口
 *
 * 事件绑定调用范例
 * $("#btn_login").nc_login({
 *     nchash:'<?php echo getNchash();?>',
 *     formhash:'<?php echo Security::getTokenValue();?>',
 *     anchor:'cms_comment_flag'
 * });
 * 
 * 直接调用范例
 * $.show_nc_login({
 *     nchash:'<?php echo getNchash();?>',
 *     formhash:'<?php echo Security::getTokenValue();?>',
 *     anchor:'cms_comment_flag'
 * });

 */
(function($) {
    $.show_nc_login = function(options) {
        var settings = $.extend({}, {action:'/index.php?act=login&op=login&inajax=1', nchash:'', formhash:'' ,anchor:''}, options);
        var login_dialog_html = $('<div class="quick-login"></div>');
        var ref_url = document.location.href;
        login_dialog_html.append('<form class="bg" method="post" id="ajax_login" action="'+APP_SITE_URL+settings.action+'"></form>').find('form')
        	.append('<input type="hidden" value="ok" name="form_submit">')
        	.append('<input type="hidden" value="'+settings.formhash+'" name="formhash">')
        	.append('<input type="hidden" value="'+settings.nchash+'" name="nchash">')
        	.append('<dl style=" margin-top:-10px;"><dt>用户名</dt><dd><input type="text" name="member_name" autocomplete="off" class="text"></dd></dl>')
        	.append('<dl><dt>密&nbsp;&nbsp;&nbsp;码</dt><dd><input type="password" autocomplete="off" name="password" class="text"></dd></dl>')
        	.append('<dl><dt>验证码</dt><dd><input type="text" size="10" maxlength="4" class="text fl w60" name="captcha"><img border="0" onclick="this.src=\''+APP_SITE_URL+'/index.php?act=seccode&amp;op=makecode&amp;nchash='+settings.nchash+'&amp;t=\' + Math.random()" name="codeimage" title="看不清，换一张" src="'+APP_SITE_URL+'/index.php?act=seccode&amp;op=makecode&amp;nchash='+settings.nchash+'" class="fl ml10"><span>不区分大小写</span></dd></dl>')
        	.append('<ul><li>›&nbsp;如果您没有登录帐号，请先<a class="register" href="'+SHOP_SITE_URL+'/index.php?act=login&amp;op=register">注册会员</a>然后登录</li><li>›&nbsp;如果您<a class="forget" href="'+SHOP_SITE_URL+'/index.php?act=login&amp;op=forget_password">忘记密码</a>？，申请找回密码</li></ul>')
        	.append('<div class="enter"><input type="submit" name="Submit" value="&nbsp;" class="submit"></div><input type="hidden" name="ref_url" value="'+ref_url+'">');
        
        login_dialog_html.find('input[type="submit"]').click(function(){
        	ajaxpost('ajax_login', '', '', 'onerror');
        });
        html_form("form_dialog_login", "登录", login_dialog_html, 360);
    };
    $.fn.nc_login = function(options) {
        return this.each(function() {
            $(this).on('click',function(){
                $.show_nc_login(options);
                return false;
            });
        });
    };

})(jQuery);

$(function() {
	$(".quick-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
});


