/**
 * Created by shizhongying on 11/3/15.
 */
//图片上传预览    IE是用了滤镜。
function clacImgZoomParam( maxWidth, maxHeight, width, height ){
    var param = { width:width, height:height, top:0, left:0 };

    if( width>maxWidth || height>maxHeight ){
        rateWidth = width / maxWidth;
        rateHeight = height / maxHeight;

        if( rateWidth > rateHeight ){
            param.width = maxWidth;
            param.height = height / rateWidth;
        }else{
            param.width = width / rateHeight;
            param.height = maxHeight;
        }
    }

    param.left = (maxWidth - param.width) / 2;
    param.top = (maxHeight - param.height) / 2;

    return param;
}


function previewImage(file) {
    var MAXWIDTH = 100;
    var MAXHEIGHT = 200;
    var div = document.getElementById('preview');
    if (file.files && file.files[0]) {
        div.innerHTML = '<img id=imghead>';
        var img = document.getElementById('imghead');
        img.onload = function () {
            img.style.height = 'auto';
        };
        var reader = new FileReader();
        reader.onload = function (evt) {
            img.src = evt.target.result;
        };
        reader.readAsDataURL(file.files[0]);
    }else { //兼容IE
        var sFilter = 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
        file.select();
        var src = document.selection.createRange().text;
        div.innerHTML = '<img id=imghead>';
        var img = document.getElementById('imghead');
        img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
        var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
        status = ('rect:' + rect.top + ',' + rect.left + ',' + rect.width + ',' + rect.height);
        div.innerHTML = "<div id=divhead style='width:" + rect.width + "px;height:" + rect.height + "px;margin-top:" + rect.top + "px;" + sFilter + src + "\"'></div>";
    }
}

function show(src){
    $('#qrcode .qrcode-img').attr('src', src);
    $('#qrcode').show();
}

function hide(){
    $('#qrcode').hide();
}

function logAdd(url){
    $.getJSON(url, function(j){
        if(j.code == 0){
            // success
        }
    });
}



function checkGroupForm(){
    if($('#id_name').val().length == 0){
        alert('群名称不能为空。');
        return false;
    }
    if($('#id_category').val() == ''){
        alert('请设置分类。');
        return false;
    }
    if($('#id_create_province').val() == ''){
        alert('请设置省份。');
        return false;
    }
    if($('#id_create_location').val() == ''){
        alert('请设置城市。');
        return false;
    }
    if($('#id_description').val().length > 500){
        alert('描述不能超过500字。');
        return false;
    }
    $('.btn-publish').hide();
    return true;
}

/*
 * url 字符串 请求地址
 * data 对象 请求参数
 * medthod 字符串 get、post请求方式
 */
function getAjaxReturn(url,data,medthod){
    var t = new bybonTools();
    var m = t.showPreloader({ effectIndex: 1 });
    var bol =AjaxReturn(url,data,medthod);
    t.hidePreloader(m);
    return bol;
}

function AjaxReturn(url,medthod){
    var dataStr = '';
    var bol;

    $.ajax({
        type:medthod,
        async:false,
        url:url,
        data:dataStr,
        dataType: "json",
        success:function(data){
            bol=data;
        }
    });
    return bol;
}

function AjaxGetReturn(url){
    var bol;

    $.ajax({
        type:'get',
        async:false,
        url:url,
        dataType: "jsonp",
        success:function(data){
            bol=data;
        }
    });
    return bol;
}

