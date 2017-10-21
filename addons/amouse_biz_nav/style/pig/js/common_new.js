/**
 * Created by shizhongying on 5/24/16.
 */
/**
 *  提示
 * @param {type} type  提示类型 1 成功   0 警告
 * @param {type} info   提示信息
 * @returns {undefined}
 */
function dogalert(type, info) {
    if (type==1) {
        type = 'white';
    } else {
        type = 'red';
    }
    var alert_html = "<div id='dogalert'><div class='" + type + "'>" + info + "</div></div>";
    $('body').append(alert_html);
    $('#dogalert').fadeIn(500);
    setTimeout("$('#dogalert').fadeOut(500,function(){$('#dogalert').remove()})", 2000);
}

/**
 * 未登录点击发布群，提示注册并跳转
 * @returns {undefined}
 */
function unlogintips() {
    dogalert(1, "来登录吧！");
    setTimeout('location.href=register_url', 2000);

}

function AjaxReturn(url,medthod){
    var bol;
    $.ajax({
        type:medthod,
        async:false,
        url:url,
        dataType: "json",
        success:function(data){
            bol=data;
        }
    });
    return bol;
}


/**
 * 上传产品秀图片
 * @returns {undefined}
 */

var images = new Array();
function uploadQrc() {

    $("#upload-tips").html("图片上传中..");
    $('input[name=upload]').upload({
        action: upload_url,
        oncomplete: function() {
            $("#upload-tips").html("上传完毕！");
        },
        onsuccess: function(json) {
            if (json.status == 1) {
                dogalert(1, "上传成功！");
                $("#upload-tips").html("上传完毕！");

                $('#tmpqrc').attr('src', json.info);
                $("input[name=qrcode]").val(json.info);
                images.push(json.info);
                var img_box = '<div class="xiu_img">'+
                    '<div class="xiu_img_padding">'+
                    '<div class="xiu_dele">X</div>'+
                    '<div class="square_box" style="background:url(\'' + json.info + '\')"></div>'+
                    '</div>'+
                    '</div>';
                $("#uploadImgs").append(img_box);
                $('input[name=upload]').val('');
                var img_length = $(".xiu_img").length;
                if(img_length >= 6){
                    $(".xiu_add").hide();
                }
            } else {
                $("#upload-tips").html("");
                alert(json.info);

                $("input[name=qrcode]").val(json.info);
                $('input[name=upload]').val('');
            }
            console.log(images);
        },
        onerror: function(json) {
            if (json.status == 413) {
                alert('你上传的图片过大');
                $("#upload-tips").html("");
            }
        },
        onprogress: function(json) {
            $("#upload-tips").html("图片上传中.....");
        }
    });
}

/*删除上传错误的图片*/
$("#uploadImgs").on("click",".xiu_img",function(){
    $(this).addClass("cur").siblings().removeClass("cur");
});
$("#uploadImgs").on("click",".xiu_dele",function(){
    var index = $(this).index(".xiu_dele");
    $(this).parents(".xiu_img").remove();
    images.splice(index,1);
    $(".xiu_add").show();
    console.log(images);
});


/**
 * 上传二维码图片并解码
 * @returns {undefined}
 */
function upload2decode() {

    $("#upload2").click();



}


$(document).on('change','input[name=upload2]', function() {
    $("#upload-tips").html("图片上传中..");
    $('input[name=upload2]').upload({
        action: upload2decode_url,
        oncomplete: function() {
            $(".upload-tips").html("上传完毕！");

        },
        onsuccess: function(json) {
            if (json.status == 1) {
                dogalert(1, "上传成功！");
                $("#upload-tips").html("上传完毕！");
                $('#tmpqrc').attr('src', json.info);
                $("input[name=qrcode]").val(json.info['image']);
                $("input[name=data]").val(json.info['data']);
                if (json.info['data'] === '')
                {
                    $("#upload-tips").html("");
                    dogalert(0, '非法二维码，请重新上传');
                }
            } else {
                $("#upload-tips").html("");
                alert(json.info);

            }
            $('input[name=upload2]').val('');

        },
        onerror: function(json) {

            if (json.status == 413) {
                alert('上传的图片过大');
            } else {
                //dogalert(0,json.responseText);
                alert(json.responseText);
            }

        },
        onprogress: function(json) {
            $("#upload-tips").html("图片上传中.....");
        }
    });

});



/*
 * 源生上传图片(手动触发)
 * @param {type} $type
 * @returns {undefined}
 */
function upload_img($type){

    $("#upload-tips").html("图片上传中..");
    $('input[name=upload3]').upload({
        action: upload3_url,
        data:{type: $type},
        oncomplete: function() {
            $(".upload-tips").html("上传完毕！");

        },
        onsuccess: function(json) {

            if (json.status == 1) {
                dogalert(1, "上传成功！");

                //互动
                if($type =='interact'){
                    $("#upload-tips").html("");
                    intimgs.push(json.info);
                    //var img_box = '<div class="In_file-img"><img src="' + json.info + '" /><span>X</span></div>';
                    var img_box = '<div class="In_file-img">'+
                        '<div class="In_file-padding">'+
                        '<div class="square_box" style="background:url('+ json.info +')"></div>'+
                        '<span>X</span>'+
                        '</div>'+
                        '</div>';
                    $(".input-group").before(img_box);
                    var img_length = $(".In_file-img").length;
                    if (img_length >= 9) {
                        $(".In_input-file").hide();
                    }
                }
                //微商圈
                else if($type =='show'){

                    $("#upload-tips").html("上传完毕！");
                    $('#tmpqrc').attr('src', json.info);
                    images.push(json.info);
                    var img_box = '<div class="xiu_img">' +
                        '<div class="xiu_img_padding">' +
                        '<div class="xiu_dele">X</div>' +
                        '<div class="square_box" style="background:url(\'' + json.info + '\')"></div>' +
                        '</div>' +
                        '</div>';
                    $("#uploadImgs").append(img_box);
                    var img_length = $(".xiu_img").length;
                    if (img_length >= 6) {
                        $(".xiu_add").hide();
                    }
                }

            } else {
                $("#upload-tips").html("");
                alert(json.info);
            }
            $('input[name=upload3]').val('');

        },
        onerror: function(json) {

            if (json.status == 413) {
                alert('上传的图片过大');
            } else {
                //dogalert(0,json.responseText);
                alert(json.responseText);
            }

        },
        onprogress: function(json) {
            $("#upload-tips").html("图片上传中.....");
        }
    });

}



/**
 * 新建微信群
 */
var isGroup = 1;// 1是群二维码 0个人二维码 2QQ 二维码
var person_chose,group_chose;//选择过的选项储存点
var group_type_options = $('#type').html();//群名片分类内容临时存放点
var psersonal_type_options = '<div class="type_box"><div class="type_padding"><div class="type_classify" value="-1">微信二维码</div></div></div><div class="type_box"><div class="type_padding"><div class="type_classify" value="-2">QQ二维码</div></div></div>';//个人名片分类
var typeid =-1;
$("#type .type_classify").eq(0).addClass("cur");
$(document).on('click','#filter .card_type', function() {
    var type = $(this).attr("value");
    $('#filter .card_type').removeClass("cur")
    $(this).addClass("cur");
    if (type == 'personal') {//个人名片
        $('#group_box_id').show() ;
        $('#type').html(psersonal_type_options);
        $("#type .type_classify").eq(0).addClass("cur");
        if(person_chose!==""&&person_chose!==null){
            $("#type .type_classify[value="+person_chose+"]").click();
        }
        isGroup = 0;
    } else if(type=="group") {
        $('#group_box_id').show() ;
        $('#type').html(group_type_options);
        $("#type .type_box").eq(0).find(".type_classify").addClass("cur");
        if(group_chose!==""&&group_chose!==null){
            $("#type .type_classify[value="+group_chose+"]").click();
        }
        isGroup = 1;
    }else{
        typeid = -3;
        $('#group_box_id').hide() ;
        isGroup = 2;
    }
});

/**
 * 设置个人名片类型过滤
 */
$(document).on('click','#type .type_classify',function(){
    var type = $(this).attr("value");
    $('#type .type_classify').removeClass("cur");
    $(this).addClass("cur");
    if(type=='-1') {
        person_chose = $(this).attr("value");
        isGroup = 0;//微信个人二维码
    }else if(type=='-2'){
        person_chose = $(this).attr("value");
        isGroup = 0;//QQ个人二维码
    }else{
        group_chose = $(this).attr("value");
    }
});

//查看协议
$(".group_protocol_btn").click(function(){
    //内容大约200px高度;
    var height = $(window).height();
    $(".protocol_modal").show();
});
//关闭协议窗
$(".protocol_modal_close").click(function(){
    if ($('input[name=contract]').attr('checked') != 'checked') {
        dialog2('不同意协议要求，不能发布！');
    }
    $(".protocol_modal").hide();
});


//点击发布
$("#addNewGroup").on('click',function() {
    //检验是否同意了协议
    if($("#contract").attr("checked")!="checked"){
        dialog2('不同意协议要求，不能发布！');
        return false;
    }
    var qrcode = $("input[name=qrcode]").val();//二维码图片
    if(!qrcode) {
        dialog2('请必须上传真实有效的二维码。');
        return false;
    }
    if($('#location_p').val() == ''){
        dialog2('请设置省份。');
        return false;
    }
    if($('#location_c').val() == ''){
        dialog2('请设置城市。');
        return false;
    }
    //选取分类内容以及id
    var type_classify = $("#type .type_classify.cur");
    var type = type_classify.text();
    var type_id = type_classify.attr("value");
    if(isGroup==2){
        type_id = typeid ;
    }

    var description = $('#groupdesc').val();
    var name = $('#groupname').val();
    if (type == "分类" && isGroup == 1) {
        dialog2('请选择分类');
        return;
    }
    if(type.length==0) {
        dialog2('分类异常，请重新选择分类');
        return;
    }
    if(!name) {
        dialog2("请填写名片名称");
        $('#groupname').focus();
        return;
    };
    if (name.length < 3) {
        dialog2("名片名称太短");
        $('#groupname').focus();
        return;
    }else if(name.length > 15) {
        dialog2("名片名称不能超过15个字");
        $('#groupname').focus();
        return;
    }
    if (!description) {
        dialog2("描述不能为空");
        //$('#groupdesc').focus();
        return;
    };
    if (description.length < 8) {
        dialog2("描述文字太短");
        $('#groupdesc').focus();
        return;
    }else if(description.length > 250) {
        dialog2("描述文字超过250字");
        $('#groupdesc').focus();
        return;
    }
    //不符合名称和描述描写的不能提交
    if(name.length<3 || name.length>15 || description.length<8 || description.length>200){
        return false;
    };

    //判断积分
    var own_score = parseInt($('#own_score').html());
    var need_score = parseInt($('#need_score').html());
    if(own_score < need_score) {
        dialog2("积分不足，请查看积分规则!");
        window.location.href = rule_url;
        return;
    }
    var options ={
        qrcode: qrcode,
        location_p: $('#location_p').val(),
        location_c: $('#location_c').val(),
        type: type,
        intro: description,
        title: name,
        type_id: type_id,
        isGroup: isGroup
    };
    $.ajax({
        type : "POST",
        url : addgroup_url,
        data : options,
        dataType : "json",
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        beforeSend : function(XMLHttpRequest) {
            loadingToast("提交中...");
        },
        success : function(res){
            if(res.code==200){
                dialog2("发布成功");
                $('#groupdesc').val("");
                $('#groupname').val('');
                $("#upload-tips").html("");
                setTimeout("window.location=meCard_url", 2000);
            }else{
                hidemod("loadingToast");
                dialog2(res.msg);
            }
        },
        error : function(data){
            hidemod("loadingToast");
            dialog2('网络出错');
        }
    });

});

//更新密码
$("#save").click(function() {
    var oldPwd = $('input[name=oldPwd]').val();
    var newPwd = $('input[name=newPwd]').val();
    var rePwd = $('input[name=rePwd]').val();
    $.post(changePwd_url, {"oldPwd": oldPwd, "newPwd": newPwd, "rePwd": rePwd}, function(data) {
        if (data.status != 1) {
            alert(data.info);
        } else {
            alert(data.info);
        }
    });

});

//意见反馈
$("#feedback").click(function() {

    var message = $("#message").val();

    $.post(feedback_url, {message: message}, function(data) {
        alert("感谢你的反馈！");
    });
});


/**
 * 登录
 */
$("#login").click(function() {
    var username = $("input[name=username1]").val();
    var password = $("input[name=password1]").val();


    $.post(loginLogin_url, {username: username, password: password}, function(json) {
        if (json.status == 1) {
            dogalert(1, "登录成功,跳转中...");
            setTimeout('window.location.href=index_url', 2000);

        } else {
            dogalert(2, json.info);
        }
    });
});

/**
 * 注册  检查用户名
 * @param {type} param1
 * @param {type} param2
 */
$("input[name=username]").bind("blur", function() {

    var username = $("input[name=username]").val();
    $.post(registerisAccountExist_url, {username: username}, function(json) {
        if (!json.status) {
            dogalert(0, "用户名已经存在");
            $("input[name=username]").val("");
            $("input[name=username]").focus();
        }
    });
});

/**
 * 注册
 * @param {type} param
 */
$("#register").click(function() {
    var username = $("input[name=username]").val();
    var password = $("input[name=password]").val();
    var repassword = $("input[name=repassword]").val();
    if (username.length != 11)
    {
        dogalert(0, '请输入有效的手机号码！');

        return false;
    }

    $.post(registerRegister_url, {username: username, password: password, repassword: repassword}, function(json) {
        if (json.status == 1) {
            dogalert(1, "注册成功，正在跳转..");
            setTimeout('window.location.href=index_url', 2000);
        } else {

            dogalert(0, json.info);
        }
    });

});



/**
 * 显示每个板块的名称
 * 页面加载的时候执行
 * @param {type} param
 */
$().ready(function() {
    var left = (window.innerWidth - $(".navbar-title").width()) / 2;
    $('.navbar-title').css("left", left);

});



//检查是否手机号
function check_mobile(mobile) {
    var reg = /1[234578]{1}\d{9}$/;
    return reg.test(mobile);
}
//发布群名片
function gofabu(haslogin){
    if(haslogin=='1'){
        location.href= addgroup_url;
    }else{
        alert('亲，先注册个帐号吧。');
        location.href=register_url;
    }
}