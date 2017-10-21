
/**
$(".page-header01 span").click(function() {
    $(".noResult").hide();
    $(this).addClass("active").siblings().removeClass("active");
    var id = $(this).attr("id");
    if ($(this).index()== 0) {
        $("#pageLoading").show();
        dataLoader.ajaxObj.data.page = 1; //分页从第一个开始
        dataLoader.ajaxObj.data.OrderType = 0;
        dataLoader.isLoaded = false;
        dataLoader.loadData();
    } else if ($(this).index()== 1) {
        $("#pageLoading").show();
        dataLoader.ajaxObj.data.page = 1; //分页从第一个开始*
        dataLoader.ajaxObj.data.OrderType = 1;
        dataLoader.isLoaded = false;
        dataLoader.loadData();
    }
});

//360判断
var refids,refid = getRefid();
if (refid == 128922994) {
    //s+ 12.07
    refids = refid;
}else{
    refids = "";
}

var dataLoader = $("#pageContent").dataLoader({
    contentElem: "ul",
    bottom: 5,
    initLoad: true,
    ajaxObj: {
        url: "/hotel/json/hotelorderlist.html?",
        data: {
            page: 1,
            OrderType: 0,
            refid: refids
        },
        dataType: "json",
        success: function(data, loader) {
            $("#pageLoading").hide();
            var box = $("#pageContent ul");
            if (data.state == 100 && data.hotelOrderList != null && data.hotelOrderList.length > 0) {
                var list = data.hotelOrderList;
                var str ="";
                for(var i= 0,len = list.length;i<len;i++){
                    var payStr = (data.OrderType == "进行中" && (list[i].danBaoType === 2 || list[i].danBaoType === 3) && list[i].isPay === "True")?'<span class="hotel-pay clearfix">'+
                        '<span class="pay-btn"><a href='+ list[i].rurl +'>支付</a></span>'+
                        '<span class="order-tips">请在30分钟内完成支付</span>'+
                        '</span>':"";
                    str += '<li aHref='+ list[i].aurl +'>'+
                        '<div class="order-title clearfix">'+
                        '<span class="hotel-title">'+ list[i].hotelName +'</span>'+
                        '<span class="order-confirm">'+ list[i].orderStatusDesc +'</span>'+
                        '</div>'+
                        '<div class="order-pay clearfix">'+
                            '<span class="order-pay-left">'+
                                '<span class="hotel-price">'+ list[i].price +'<label>'+ ((list[i].danBaoType === 2 || list[i].danBaoType === 3)?"预付":"")+'</label></span>'+
                                '<span class="order-time">下单时间：'+list[i].comeDate +'</span>'+
                            '</span>'+ payStr +
                        '</div>'+
                    '</li>';
                }

                if (dataLoader.ajaxObj.data.page == 1){
                    box.html(str);
                }else{
                    box.append(str);
                }

                $("#pageContent li").on("click",function(){
                    location.href = $(this).attr("aHref");
                });
                $("#pageContent li a").on("click",function(){
                    event.preventDefault();
                    event.stopPropagation();
                    location.href = $(this).attr("href");
                });
                loader.ajaxObj.data.page ++;

            } else {
                loader.isLoaded = true;

                if (loader.ajaxObj.data.page === 1){
                    box.html("");
                    if (data.OrderType == "进行中") {
                        $(".noResult").html(' <span></span><p>您暂时没有进行中的酒店订单，不妨去看看其他酒店~</p><a href="/hotel">去看看</a>');
                    } else {
                        $(".noResult").html(' <span></span><p>您暂时没有已结束的酒店订单，不妨去看看其他酒店~</p><a href="/hotel">去看看</a>');
                    }
                    $(".noResult").show();
                    setTimeout(function(){
                        $(".data-loader").hide();
                    },50)
                }
            }
        }
    }
});
**/
//dataLoader.loadData();