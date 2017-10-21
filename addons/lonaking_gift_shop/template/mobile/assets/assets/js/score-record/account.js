var account = {
    data: {
        page_index: 1,
        page_size: 10,
        page_count: 10
    },
    init: function () {
        account.functions.loadScoreRecord(function (json) {
            account.data.page_index = account.data.page_index + 1;
            var html = "";
            $.each(json.data.data, function (index,currentData) {
                if(currentData.num>0){
                    html = html + '<li><div><h3>'+currentData.remark+'</h3><p>'+formatDate(new Date(currentData.createtime*1000))+'</p></div><div class="pre_count">'+currentData.num+'</div></li>'
                }else{
                    html = html + '<li><div><h3>'+currentData.remark+'</h3><p>'+formatDate(new Date(currentData.createtime*1000))+'</p></div><div class="pre_count pre_green">'+currentData.num+'</div></li>'
                }

            });
            $("#list section").append(html);
            $("#foo_loading").hide()

        }, function (json) {
            alert('失败');
        });
    },

    event: function () {
        $(window).scroll(function () {
            var height = account.functions.getClientHeight();
            var theight = account.functions.getScrollTop();
            var rheight = account.functions.getScrollHeight();
            var bheight=rheight-theight-height;
            $("#foo_loading").show();
            if(bheight == "0"){
                account.functions.loadScoreRecord(function (json) {
                    account.data.page_index = account.data.page_index + 1;

                    var html = "";
                    $.each(json.data.data, function (index,currentData) {
                        if(currentData.num>0){
                            html = html + '<li><div><h3>'+currentData.remark+'</h3><p>'+formatDate(new Date(currentData.createtime*1000))+'</p></div><div class="pre_count">'+currentData.num+'</div></li>'
                        }else{
                            html = html + '<li><div><h3>'+currentData.remark+'</h3><p>'+formatDate(new Date(currentData.createtime*1000))+'</p></div><div class="pre_count pre_green">'+currentData.num+'</div></li>'
                        }

                    });
                    $("#list section").append(html);
                    $("#foo_loading").hide()

                }, function (json) {
                    alert('失败');
                });
            }
        });
    },

    functions: {

        loadScoreRecord : function (success,error) {
            var url = $("html").data('load-score-record-url');
            $.ajax({
                url: url,
                type: 'POST',
                data:{
                    'page' : account.data.page_index,
                    'size' : account.data.page_size
                },
                dataType: 'html',
                timeout: 1000,
                error: function(result){
                    error(result)
                },
                success: function(result){
                    var json = JSON.parse(result);
                    success(json);
                }

            });
            //$.post(url,function (e) {
            //    var json = e;
            //    if(json.status == 200){
            //        success(e);
            //    }else{
            //        error(e);
            //    }
            //});
        },

        getScrollHeight: function () {
            return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        },

        getScrollTop: function () {
            var scrollTop = 0;
            if (document.documentElement && document.documentElement.scrollTop) {
                scrollTop = document.documentElement.scrollTop;
            } else if (document.body) {
                scrollTop = document.body.scrollTop;
            }
            return scrollTop;
        },
        getClientHeight: function () {
            var clientHeight = 0;
            if (document.body.clientHeight && document.documentElement.clientHeight) {
                var clientHeight = (document.body.clientHeight < document.documentElement.clientHeight) ? document.body.clientHeight : document.documentElement.clientHeight;
            } else {
                var clientHeight = (document.body.clientHeight > document.documentElement.clientHeight) ? document.body.clientHeight : document.documentElement.clientHeight;
            }
            return clientHeight;
        }
    }
}

$(function () {
    account.init();
    account.event();
});

function formatDate(now) {
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var date = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    return year + "-" + month + "-" + date + "   " + hour + ":" + minute + ":" + second;
}