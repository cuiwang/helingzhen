/*
* @Author: Administrator
* @Date:   2016-09-09 12:18:38
* @Last Modified by:   Administrator
* @Last Modified time: 2016-09-13 00:04:03
*/
function creatmanage(pid){
    if($('#moption_'+pid).length==0){
        var managehtml ='<div class="imui_sheet b_f size_16 tc hidesheet" id="moption_'+pid+'" style="display:none">';
        managehtml +=  '<ul><li><a href="forum.php?mod=post&amp;action=edit&amp;fid=39&amp;tid=5847&amp;pid='+pid+'&amp;mobile=2" class="gettab bo_bl" tab="post">编辑</a></li>';
        managehtml +=  '<li><a href="javascript:opensheet(\'#moption_'+pid+'\')">取消</a></li></ul></div>';
        $('.footerarea').append(managehtml);
    }
}
function sendthreadpost(id){
    var formobj = $('#'+id);
    var post = formobj.serialize();
    popup.open('<img src="{MODULE_URL}public/images/imageloading.gif">');
    $.post("",post,function(s){
        if(s.status == 0){
            setTimeout(function(){
                popup.close();
                showbox('fastpost');
                window.location.href = URL;
            },500);
        }else{
            setTimeout(function(){
                popup.close();
                showbox('fastpost');
                $.toast(s.message,"forbidden",function(){});
            },500);
        }
    },'json');
    return false;
}
function openrecommends(){
    if($('.recommends').hasClass('recommends_open')){
        $('.recommends').removeClass('recommends_open').addClass('recommends_close');
    }else{
        $('.recommends').removeClass('recommends_close').addClass('recommends_open');
    }
}

$(function(){
	/*收藏点赞*/
	$('#click_favorite').click(function(){
	    var _that = $(this);
	    $.post("",{act:'favorite'},function(data){
	        if(data.status == 0){
	            _that.addClass('cc');
	            $('#favs').html(data.total);
	            $.toast('收藏成功',2000,function(){});
	        }else{
	            _that.removeClass('cc');
	            $('#favs').html(data.total);
	            $.toast('取消收藏成功',2000,function(){});
	        }
	    },'json');
	});
	/*收藏点赞*/
	$('#click_recommend').click(function(){
	    var _that = $(this);
	    $.post("",{act:'recommend'},function(data){
	        if(data.status == 0){
	            _that.addClass('cc');
	            $('#adds').html(data.total);
	            $.toast('收藏成功',2000,function(){});
	        }else{
	            _that.removeClass('cc');
	            $('#adds').html(data.total);
	            $.toast('取消点赞成功',2000,function(){});
	        }
	    },'json');
	});
	/*上传图片功能实现*/
	$('#filedata').localResizeIMG({
        width: 120,
        quality: 0.8,
        success: function (result) {
            var src = result.base64;
            var html = '<li><span class="del"><a href="javascript:;"><img src="{MODULE_URL}public/images/icon_del.png"></a></span><span class="p_img"><a href="javascript:;"><img style="height:54px;width:54px;" src="'+src+'"></a></span><input type="hidden" name="attachnew[]" value="'+src+'"></li>';
            $('#imglist').append(html);
            $('.del').click(function(){
                $(this).parent().remove();
            });
        }
    });
    $('.reply_support').click(function(){
        var _that = $(this);
        var fid = _that.data('id');
        $.post("",{act:'reply_support',fid:fid},function(data){
            if(data.status == 0){
                //点赞成功
                _that.addClass('active');
            }else{
                //取消点赞
                _that.removeClass('active');
            }
        },'json');
    });
    var html = "";
    html += "<button data-credit='2' style='margin-right: 5px;font-size: 16px;background-color: #fafafc;border-color: red;border-style: solid;border-width: 1px;color: red;' class='weui_btn weui_btn_mini weui_btn_primary reward_btn'>2元</button>";
    html += "<button data-credit='5'style='margin-right: 5px;font-size: 16px;background-color: #fafafc;border-color: red;border-style: solid;border-width: 1px;color: red;' class='weui_btn weui_btn_mini weui_btn_primary reward_btn'>5元</button>";
    html += "<button data-credit='10'style='margin-right: 5px;font-size: 16px;background-color: #fafafc;border-color: red;border-style: solid;border-width: 1px;color: red;' class='weui_btn weui_btn_mini weui_btn_primary reward_btn'>10元</button>";
    html += '<br><span style="color:red;">任意赏</span><input id="reward_credit" style="padding: 10px;margin-top: 10px;width: 8em;margin-right: 5px;margin-left: 5px;border: none;background-color: #cac8c8;" type="number"><span style="color:red;">元</span>';
    $('#rate').click(function(){
        $.modal({
            title: "给个咖啡钱支持我继续创作吧！<br><span style='color:gray;'>(赞赏收入贴主)</span>",
            text: html,
            buttons: [
                {
                    text: "微信支付",
                    onClick: function(){
                        var credit = $('#reward_credit').val();
                        if(!credit){
                            $.toast('请输入赞赏金额',2000);
                            return ;
                        }
                        $.post("",{act:'reward',credit:credit},function(data){
                            wx.chooseWXPay({
                                timestamp: data.timeStamp,
                                nonceStr: data.nonceStr,
                                package: data.package,
                                signType: data.signType,
                                paySign: data.paySign,
                                success: function (res) {
                                    if(res.errMsg == 'chooseWXPay:ok') {
                                        data.act == 'paySuccess';
                                        $.post("",{act:'reward_success',reward_id:data.reward_id},function(d){
                                            window.location.href = "{php echo $_W['siteurl']}";
                                        },'json');
                                    }else{
                                        window.location.href = "{php echo $_W['siteurl']}";
                                    }
                                }
                            });
                        },'json');
                    }
                },
                {
                    text: "取消",
                    className: "default",
                    onClick: function(){}
                },
            ]
        });
        $('#ntcmsg_popmenu').hide();
    });
    $(document.body).on('click','.reward_btn',function(){
        $('.reward_btn').removeClass('active');
        $(this).addClass('active');
        var credit = $(this).data('credit');
        $('#reward_credit').val(credit);
    });
    $('#follow_1').click(function(){
        //关注与取消关注
        $.post("",{act:'follow',openid:openid},function(data){

        },'json');
    });
    $('.reply_reply').click(function(){
        var _that = $(this);
        var fid = _that.data('id');
        $('input[name="fid"]').val(fid);
        showbox('fastpost');
    });
})
