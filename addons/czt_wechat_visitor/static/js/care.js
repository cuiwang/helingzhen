$(function() {
    var t = null;
    $(".weui_check_label").on("tap", function() {
        $(".radio-submit").children(".weui_btn").removeClass("weui_btn_default").addClass("weui_btn_primary")
    }), $(".radio-submit").children("button").on("click", function() {
        var t = $("input[name='topic']:checked").val();
        return t ? void 0 : (alert("话题未选择"), !1)
    }), $("#share-btn").on("tap", function() {
        $(".bg-cover").show()
    }), $(".bg-cover").on("tap", function() {
        $(this).hide()
    }), $(".care-friend").on("tap", function() {
        var e = this;
        $(".care-tips").hide(), t && clearTimeout(t), t = setTimeout(function() {
            $(e).children(".care-tips").hide()
        }, 3e3), $(this).children(".care-tips").show()
     }),$("#other-topic").on('change',function(event) {
        var t=$(this).val();
        if (t.length>=2&&t.length<=30) {
            $(this).parents('label').trigger('click');
            $('#other').val(t);
            $(".radio-submit").children(".weui_btn").removeClass("weui_btn_default").addClass("weui_btn_primary");
        }else{
            $('#other').val('');
            alert('话题长度为2-30个字符');
        }
    }),$("#other-topic").keyup(function(event) {
        var t=$(this).val();
        if (t.length>=2&&t.length<=30) {
            $(".radio-submit").children(".weui_btn").removeClass("weui_btn_default").addClass("weui_btn_primary");
        }else{
            $(".radio-submit").children(".weui_btn").removeClass("weui_btn_primary").addClass("weui_btn_default");
        }
    });
});
