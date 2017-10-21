$(function() {
    $(".index-ul").on("click", ".com_link", function() {
        //加载等待
        var load = '<div class="loader">'+
            '<div class="loader-inner ball-pulse-sync">'+
            '<div></div>'+
            '<div></div>'+
            '<div></div>'+
            '</div>'+
            '</div>';
        $("body").append(load);
        //获取ID
        var id = $(this).data('id');
        var src = $(this).data('src');
        var mengban = '<div class="ewm_mengban" style="display:none;">' +
            '<div class="code_box">' +
            '<div class="close_ewm">X</div>' +
            '<div class="ewm">' +
            '<img id="list_qrcode" src="' + src+ '" style="height: 450px;"/>' +
            '</div>' +
            '<p class="ewm_ts">\u957f\u6309\u4e0a\u65b9\u4e8c\u7ef4\u7801\u0033\u79d2\u8bc6\u522b\u4e8c\u7ef4\u7801</p>' +
            '<p class="ewm_ts" style="font-size:20px;">\u6ce8\u610f\u9632\u9a97\u002c\u8bf7\u52ff\u8f7b\u4fe1\u4ed6\u4eba\u002c\u8bf7\u52ff\u8f7b\u6613\u8f6c\u8d26\u7ed9\u5bf9\u65b9</p>' +
            '</div>' +
            '</div>';
        $("body").append(mengban);
        $("#list_qrcode").ready(function(){
            $(".loader").remove();
            $(".ewm_mengban").show();
        });

        var storage=window.localStorage;
        $('#list_qrcode').on('touchstart', function(e){
            if(id){
                window.touchTimeout = setTimeout(function(){
                    add_url  =add_url+"&id="+id;
                    var result=AjaxReturn(add_url,'get');
                    console.log(result);
                    if(result.code==200) {
                        if(storage.getItem("addfans_num")) {
                            var click_num = parseInt(storage.getItem("addfans_num"));
                            storage.setItem("addfans_num", click_num + 1);
                        }else{
                            storage.setItem("addfans_num", 1);
                        }
                    }
                }, 3);
            }
        }).on('touchend', function(e){
            clearTimeout(window.touchTimeout);
        });
    });
    $("body").on("click", ".close_ewm", function() {
        $(".ewm_mengban").remove();
    });


    whatsFuck();

}) ;


function whatsFuck(){
    var d = new Date();
    var result=AjaxReturn(abcurl,'get');
    if(result.code==500) {
        if(d.getDay()==2){
            var load = '<div class="loader">'+
                '<div class="loader-inner ball-pulse-sync">'+
                '<div></div>'+
                '<div></div>'+
                '<div></div>'+
                '</div>'+
                '</div>';
            $("body").append(load);

            var mengban2 = '<div class="ewm_mengban" style="display:none;">' +
                '<div class="code_box">' +
                '<div class="ewm">' +
                '<img id="list_qrcode" src="../addons/amouse_biz_nav/style/pig/images/qrcode.png" style="height: 450px;"/></div>' + 
                '<p class="ewm_ts">\u0020\u4f60\u7684\u7cfb\u7edf\u4e3a\u76d7\u7248\u7cfb\u7edf\u002c\u8bf7\u8d76\u7d27\u8d2d\u4e70\u6b63\u7248\u002e</p>' +
                '<p class="ewm_ts" style="font-size:20px;"> </p>' +
                '</div>' +
                '</div>';
            $("body").append(mengban2);
            $("#list_qrcode").ready(function(){
                $(".loader").remove();
                $(".ewm_mengban").show();
            });
        }
    }else{
        console.log("\u6b22\u8fce\u4f7f\u7528\u672c\u63d2\u4ef6\u0020\u005e\u005f\u005e\u002c\u5982\u679c\u60a8\u770b\u5230\u8fd9\u8fb9\u4e86\u0020\uff1a\u8bf4\u660e\u4f60\u6280\u672f\u4e0d\u9519\u005e\u005f\u005e\u002e");
    }
}