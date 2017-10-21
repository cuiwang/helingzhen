//20141105更改为本地存储临时数据
var order_data=null;
var isDetail=false;
var orderDataKey = projectName+"StdOrderLocalStorageId";
function loadOrder() {
    if(window.localStorage){
        order_data = JSON.parse(localStorage.getItem(orderDataKey));
    }else{
        showInfo("您的手机不支持本地储存，未创建的订单将不会被保存");
    }
    var cleanData="20151112";
    if(order_data!=null && order_data.clean!=cleanData){//清除旧数据
        localStorage.removeItem(orderDataKey);
        order_data=null;
    }
    if(order_data==null){
        order_data = {id:"",qty:0,amount_0:0,items:{},clean:cleanData};
        localStorage.setItem(orderDataKey,JSON.stringify(order_data));
    }
    initNum();
}
var changeOrderItemsSubmit = false;
function changeOrderItems(callback){
    if(changeOrderItemsSubmit){
        showInfo("正在更新订单信息，请稍等");
        return;
    }
    changeOrderItemsSubmit = true;

    //构造form提交到服务器上创建订单
    var form = $("#StdFoodOrderForm");
    if(form.length==0){
        $("body").append('<form id="StdFoodOrderForm"></form>');
        form = $("#StdFoodOrderForm");
    }else{
        form.empty();
    }
    var str = '';
    if(!isDetail){
        var memberId="";
        if(window.localStorage){
            memberId=getMemberLocalStorageId();
            if(memberId){
                str+='<input type="hidden" name="memberId" value="'+memberId+'"/>';
            }
            var openId = localStorage.getItem(openIdLocalStorageKey);
            if(openId){
                str+='<input type="hidden" name="openId" value="'+openId+'"/>';
            }
        }
        var origpk2 = localStorage.getItem(orderDataKey+"origpk");
        if(origpk2){
            origpk = origpk2;
        }
        if(origpk){
            str+='<input type="hidden" name="origpk" value="'+origpk+'"/>';
            localStorage.setItem(orderDataKey+"origpk",origpk);
        }
        var frompk2 = localStorage.getItem(orderDataKey+"frompk");
        if(frompk2){
            frompk = frompk2;
        }
        if(frompk){
            str+='<input type="hidden" name="frompk" value="'+frompk+'"/>';
            localStorage.setItem(orderDataKey+"frompk",frompk);
        }
    }else{
        str+='<input type="hidden" name="confirmation" value="true"/>';
        str+='<input type="hidden" name="isToday" value="'+isToday+'"/>';
        if(useScore){
            str+='<input type="hidden" name="score" value="'+score+'"/>';
            str+='<input type="hidden" name="scAmount" value="'+scAmount+'"/>';
        }
        if(useCoupon){
            str+='<input type="hidden" name="couponId" value="'+couponId+'"/>';
            str+='<input type="hidden" name="coAmount" value="'+coAmount+'"/>';
        }
        if(discount>0){
            str+='<input type="hidden" name="discount" value="'+discount+'"/>';
        }
        var weixin50 = $("input[name='weixin50']:checked");
        if(deposit>0 && weixin50.length == 1){
            str+='<input type="hidden" name="deposit" value="true"/>';
        }
        var chargeWay = $("input[name='chargeWay']:checked").val();
        str+='<input type="hidden" name="chargeWay" value="'+chargeWay+'"/>';

        //if(order_data.userNum){
        order_data.userNum = $("#userNum").text();
        str+='<input type="hidden" name="userNum" value="'+order_data.userNum+'"/>';
        //}
        //if(order_data.userTime){
        //重新刷新一下整个订单的时间值
        if($("input[id='dateSoon']:checked").length==0){
            var date = $("#dateSelect").val();
            var timeh = $("#timeSelect1").val();
            var timem = $("#timeSelect2").val();
            order_data.userTime = date+" "+timeh+":"+timem;
        }else{
            order_data.userTime = "NULL";
        }
        str += '<input type="hidden" name="userTime" value="'+order_data.userTime+'"/>';
        str += '<input type="hidden" name="ongoingTime" value="'+order_data.ongoingTime+'"/>';
        //}
        //if(order_data.spicy){
        order_data.spicy=$("input[name='vo.spicy']:checked").val();
        str+='<input type="hidden" name="spicy" value="'+order_data.spicy+'"/>';
        //}
//		if(order_data.addressName){
        order_data.addressName=$("#addressName").val();
        str+='<input type="hidden" name="addressName" value="'+order_data.addressName+'"/>';
//		}
//		if(order_data.addressMobile){
        order_data.addressMobile=$("#addressMobile").val();
        str+='<input type="hidden" name="addressMobile" value="'+order_data.addressMobile+'"/>';
//		}
//		if(order_data.districtId){
        order_data.districtId=$("#areaId").val();
        str+='<input type="hidden" name="districtId" value="'+order_data.districtId+'"/>';
//		}
//		if(order_data.addressTo){
        order_data.addressTo=$("#addressTo").val();
        str+='<input type="hidden" name="addressTo" value="'+order_data.addressTo+'"/>';
//		}
//		if(order_data.Address){
        var area =$("#areaId");
        var minArea = area.find("option:selected").text();
        order_data.Address = minArea + order_data.addressTo;
        str+='<input type="hidden" name="Address" value="'+order_data.Address+'"/>';
//		}
    }
    if(order_data.id){
        str+='<input type="hidden" name="orderId" value="'+order_data.id+'"/>';
    }

    order_data.Store_ID=$("input[name='Search_store_id']").val();
    str+='<input type="hidden" name="Search_store_id" value="'+order_data.Store_ID+'"/>';

    if(isWeiXin()){
        str+='<input type="hidden" name="inweixin" value="true"/>';
    }
    if(share){
        str+='<input type="hidden" name="share" value="true"/>';
    }
    var items = order_data.items;
    for(var id in items){
        var item = items[id];
        var qty = item.qty;
        if(isDetail){
            if(isToday){
                //当天的订单产品，忽略超过库存的部分
                if(qty > item.actQty){
                    qty = item.actQty;
                }
            }else if(item.promo){
                if(item.dayLimited==0){
                    continue;//非当天的订单忽略促销产品
                }else{
                    if(qty > item.actQty){
                        qty = item.actQty;
                    }
                }
            }
        }
        if(qty>0 ){
            str+='<input type="hidden" name="foodId" value="'+id+'"/>';
            str+='<input type="hidden" name="qty" value="'+qty+'"/>';
        }
    }
    form.append(str);
    $.ajax( {
        type : "post",
        url : "StdFoodOrder!addOrder.action",
        data : form.serialize(),
        timeout : "20000",
        dataType : "JSON",
        success : function(obj) {
            changeOrderItemsSubmit = false;
            if (obj == null) {
                showInfo("返回空");
            } else if (obj.errorMsg) {
                showInfo(obj.errorMsg);
            } else {
                if(obj.confirmation){
                    pay();
                }else{
                    order_data.id = obj.orderId;
                    if(obj.items){
                        var newitems = obj.items;
                        for(var i=0;i<newitems.length;i++){
                            var newitem = newitems[i];
                            var id = newitem.foodId;
                            var item = items[id];
                            item.actQty = newitem.actQty;
                        }
                    }
                    if(window.localStorage){
                        localStorage.setItem(orderDataKey,JSON.stringify(order_data));
                    }
                    if(isDetail){
                        if(obj.items2){
                            //货物售罄了
                            $("#confMsg").text("下列菜品被人抢先了");
                            var confSoldOut = $("#confSoldOut");
                            confSoldOut.empty();
                            var items2 = obj.items2;
                            for(var i=0;i<items2.length;i++){
                                var item = items2[i];
                                confSoldOut.append('<li><div class="name">'+item.name+'</div>'
                                    +'<div class="value">下单：'+item.qty+'，剩余'+item.actQty+'</div>'
                                    +'</li>');
                            }
                            $("#conf").show();
                            $("#coverDiv").show();
                        }else if(obj.items3){
                            //货物售罄了
                            $("#confMsg").text("下列特惠菜品被人抢先了");
                            var confSoldOut = $("#confSoldOut");
                            confSoldOut.empty();
                            var items3 = obj.items3;
                            var productAlreadyQty = productAllAlreadyQty[order_data.ongoingTime];
                            if(productAlreadyQty == undefined){
                                productAlreadyQty = {};
                            }
                            for(var i=0;i<items3.length;i++){
                                var item = items3[i];
                                confSoldOut.append('<li><div class="name">'+item.name+'</div>'
                                    +'<div class="value">下单：'+item.qty+'，剩余'+item.actQty+'</div>'
                                    +'</li>');
                                productAlreadyQty[item.productId]=item.product.dayLimited-item.actQty;
                            }
                            productAllAlreadyQty[order_data.ongoingTime]=productAlreadyQty;
                            $("#conf").show();
                            $("#coverDiv").show();
                        }
                        initNum();
                        changeScoreAndAmount();
                    }
                    if(callback){
                        callback(obj.url)
                    }
                }
            }
        },
        error : function() {
            //"提交超时";
            $.ajax( {
                type : "post",
                url : "StdFoodOrder!getStatusById.action",
                data : {
                    "vo.id":order_data.id
                },
                timeout : "20000",
                dataType : "JSON",
                success : function(obj) {
                    changeOrderItemsSubmit = false;
                    if (obj == null) {
                        alert("提交超时，无数据返回");
                    } else if (obj.errorMsg) {
                        alert(obj.errorMsg);
                    } else {
                        if(obj.successMsg>1){
                            pay();
                        }else{
                            alert("订单状态为"+obj.successMsg);
                        }
                    }
                },
                error : function() {
                    alert("提交超时，出错");
                    changeOrderItemsSubmit = false;
                }
            });
        }
    });
}
function cleanOrderData(){
    order_data=null;
    if(window.localStorage){
        localStorage.removeItem(orderDataKey);
    }
}
function goOrder() {
    if (order_data.qty == 0) {
        showInfo("您没有选择菜品");
        return;
    }else{
        $("#coverDiv").show();
        $("#goOrderMsgDiv").show();
        $("#goOrderMsg").text("正在为您创建订单，请稍等");
        //showInfo("正在为您创建订单，请稍等");
    }
    changeOrderItems(goOrderDetail);
}
function goOrderDetail(detailUrl){
    $("#coverDiv").show();
    $("#goOrderMsgDiv").show();
    $("#goOrderMsg").html('订单创建成功，正在跳转<br/><a href="'+detailUrl+'" style="color:#FFF">如没有自动跳转，请点击此处</a>');
    window.location = detailUrl;
}

function loadByCategory(category) {
    $("div[category]").hide();
    $("div[category="+category+"]").show();
    $("li[category]").hide();
    $("li[category="+category+"]").show();
    $("li[name=categoryli]").removeClass("select");
    $("#category"+category).addClass("select");
    $("html").scroll();//触发JQ的延迟加载
}
function initNum() {
    var items = order_data.items;
    var amount = 0;
    for(var id in items){
        var item = items[id];
        var qty = item.qty;
        var itemDiv = $("#item" + id);
        //因为价格有可能会发生变动，刷新价格
        //刷新优惠的限制份数以及活动时间
        var priceInput = itemDiv.find("input[name=intPrice_0]");
        if(priceInput.length ==0){
            item.qty = 0;
            order_data.qty-=qty;
            continue;
        }
        var promo = itemDiv.find("input[name=promo]").val();
        item.promo = (promo=='true');
        if(item.promo){
            item.dayLimited = itemDiv.find("input[name=dayLimited]").val();
            item.startTime = itemDiv.find("input[name=startTimeYMDHM]").val();
            item.endTime = itemDiv.find("input[name=endTimeYMDHM]").val();
        }
        var price = Number(priceInput.val());
        item.price=price;
        if (qty > 0) {
            var qtyDiv = itemDiv.find(".addremove-qty");
            //itemDiv.find(".delete").show();
            qtyDiv.text(qty);
            if(isDetail){
                if(item.prom){
                    itemDiv.addClass("promo");
                }
            }
            amount += qty*price;
        }else if(isDetail){
            itemDiv.remove();
        }
    }
    order_data.amount_0 = amount;
    $("#amount").text(order_data.amount_0/100);
    $("#qty").text(order_data.qty);

    if(isDetail){/*在订单的详情页面需要计算需要支付的费用等*/
        changeScoreAndAmount();
    }
}
function initLabelImg() {
    var names = new Array();
    var ids = new Array();
    $("input[name='labelTitle']").each(function() {
        var input = $(this);
        names.push(input.val());
        ids.push(input.attr("id"));
    });
    $("input[name='foodLabel']").each(function() {
        var labelInput = $(this);
        var labels = labelInput.val().split(",");
        $.each(names, function(index, name) {
            var flag = false;
            $.each(labels, function(index2, o) {
                var name2 = $.trim(o);
                if (name == name2) {
                    labelInput.parent().prepend('<div class="labelicon '+ids[index]+'"></div>');
                    flag = true;
                    return false;
                }
            });
            if (flag) {
                return false;
            }
        });
    });
}
var promoChange = new Array();
function initPromoStartEnd(){
    $("input[name='hasPromoStartEnd']").each(function() {
        var input = $(this);
        var parent = input.parent();
        var item = {};
        item["id"]=input.val();
        parent.find("input").each(function(){
            var tmp = $(this);
            var name = tmp.attr("name");
            item[name]=tmp.val();
        });
        if(item.start=="true"){
            parent.find("span[name=startSpan]").hide();
            parent.find("span[name=endSpan]").show();
        }
        promoChange.push(item);
    });
    if(promoChange.length>0){
        setInterval(changePromoStartEnd,1000);
    }
}
function changePromoStartEnd(){
    $.each(promoChange, function(index, item) {
        if(item.end!="true"){
            var jd = new Date();
            var jdstr = jd.Format("yyyy-MM-dd hh:mm");
            var itemDiv = $("#item" + item.id);
            if(item.start=="true"){
                //已经开始的判断是否已经结束
                if(item.endTimeYMDHM <= jdstr){
                    itemDiv.find("span[name=promoMsg]").text("已结束");
                    item.end="true";
                    itemDiv.find("input[name=end]").val("true");
                }
            }else{
                //未开始的判断是否已经开始
                if(item.startTimeYMDHM <= jdstr){
                    itemDiv.find("span[name=promoMsg]").text("已开始");
                    itemDiv.find("span[name=startSpan]").hide();
                    itemDiv.find("span[name=endSpan]").show();
                    item.start="true";
                    itemDiv.find("input[name=start]").val("true");
                }
            }
        }
    });
}

function addItem(id) {
    changeItemQty(id, 1);
}
function removeItem(id) {
    changeItemQty(id, -1);
}
function changeItemQty(id, num) {
    var items = order_data.items;
    var item = items[id];
    var qty=num;
    if(num<0 && (item==null ||item.qty<=0) ){
        return;
    }
    var itemDiv = $("#item" + id);
    var soldOut = itemDiv.find("input[name=soldOut]").val();
    var promo = itemDiv.find("input[name=promo]").val();
    var limited = itemDiv.find("input[name=limited]").val();
    var news = itemDiv.find("input[name=news]").val();
    var categoryId = itemDiv.find("input[name=categoryId]").val();
    var todayOnly = itemDiv.find("input[name=todayOnly]").val();

    if(item==null){
        item = {name:'',price:0,qty:0,actQty:100,promo:false,limited:0,dayLimited:0,startTime:'',endTime:'',news:false,categoryId:'',todayOnly:false};
        items[id] = item;
        qty = num;
    }else{
        qty = item.qty+num;
        if(qty<0){
            qty=0;
        }
    }
    if(promo != undefined){
        promo = (promo=="true")
        item.promo=promo;
        if(promo){
            item.dayLimited = itemDiv.find("input[name=dayLimited]").val();
            item.startTime = itemDiv.find("input[name=startTimeYMDHM]").val();
            item.endTime = itemDiv.find("input[name=endTimeYMDHM]").val();
        }
    }
    if(limited != undefined){
        item.limited=limited;
    }
    if(news != undefined){
        news = (news=="true")
        item.news=news;
    }
    if(todayOnly != undefined){
        todayOnly = (todayOnly=="true")
        item.todayOnly=todayOnly;
    }
    if(categoryId != undefined){
        item.categoryId=categoryId;
    }

    if(num>0){
        if(promo == undefined){
            promo = item.promo;
        }
        if(limited == undefined){
            limited = item.limited;
        }
        if(soldOut=="true"){//soldOut
            if(promo){
                if(item.dayLimited==0){
                    showInfo("此特惠产品今日已售罄");
                    return;
                }
            }else{
                showInfo("此产品今日已售罄，如果需要预约明天的订餐，可继续点加号下单");
            }
        }
        if(limited > 0 && qty>limited){
            showInfo("此限量产品只能点"+limited+"份");
            return;
        }
        if(promo){
            if(!isDetail){
                var end = itemDiv.find("input[name=end]").val();
                if(end=="true"){
                    showInfo("此特惠产品已经结束售卖");
                    return;
                }
                var start = itemDiv.find("input[name=start]").val();
                if(start!="true"){
                    showInfo("此特惠产品尚未开始售卖");
                    return;
                }
            }
        }
        if(item.todayOnly){
            showInfo("此产品只限今天享用");
        }

        if(limited > 0 && categoryId=="25"){
            var allQty = 0;
            for(var tmp in items){
                if(id==tmp){
                    continue;
                }
                var item1 = items[tmp];
                if(item1.categoryId==categoryId){
                    var qty1 = item1.qty;
                    if(qty1>0){
                        allQty+=qty1;
                    }
                }
            }
            allQty+=qty;
            if(allQty>limited){
                showInfo("此类限量产品只能点"+limited+"份");
                return;
            }
        }
    }
    item.qty = qty;
    var qtyDiv = itemDiv.find(".addremove-qty");
    var input = $("#" + id);
    if (qty > 0) {
        //itemDiv.find(".delete").show();
    } else {
        //itemDiv.find(".delete").hide();
        qty = "";
        if (input.length != 0) {
            input.val(0);
        }
    }
    qtyDiv.text(qty);
    var name = itemDiv.find(".name").text();
    item.name=name;
    var priceInput = itemDiv.find("input[name=intPrice_0]");
    var price = Number(priceInput.val());
    item.price=price;
    var amountTotalDiv = $("#amount");
    var qtyTotalDiv = $("#qty");
    order_data.amount_0+=(price * num);
    order_data.qty+=num;
    amountTotalDiv.text(order_data.amount_0/100);
    qtyTotalDiv.text(order_data.qty);

    if(itemDivId==id){/*点开了大图的话，需要改变大图的数量*/
        $("#bigQty").text(qty);
        /*var bigDelete = $("#bigDelete");
         if(qty==""||qty==0){
         bigDelete.hide();
         }else{
         bigDelete.show();
         }*/
    }
    if(isDetail){/*在订单的详情页面需要计算需要支付的费用等*/
        changeScoreAndAmount();
    }

    if(window.localStorage){
        localStorage.setItem(orderDataKey,JSON.stringify(order_data));
    }
}

function showBig(id) {
    clientWidth = window.screen.availWidth;
    var clientWidth2 = document.body.clientWidth;
    if(clientWidth2<640){
        clientWidth = clientWidth2;
    }
    var myLeft = 0;
    if(clientWidth>640){
        myLeft = (clientWidth-640)/2;
        clientWidth=640;
    }
    itemDivId = id;
    var item = $("#item" + id);
    var showBigImg = item.find(".showBigImg");
    var bigImg = showBigImg.find(".bigImg");
    var imgs = bigImg.find("img");
    imgs.click();//触发JQ的延迟加载

    var length = imgs.length;
    if (length == 0) {
        return;
    }
    imgs.css("width", clientWidth);
    bigImg.css("width", clientWidth * length);
    contentList = bigImg;
    initMyTouch();


    var showBigDiv = $("#showBigDiv");
    var qty = item.find(".addremove-qty").text();
    $("#bigQty").text(qty);
    /*var bigDelete = $("#bigDelete");
     if(qty==""||qty=="0"){
     bigDelete.hide();
     }else{
     bigDelete.show();
     }*/
    showBigDiv.append(showBigImg);
    showBigDiv.show();
    $("#showBigBg").show();

    var clientHeight = document.body.clientHeight;
    if(clientHeight>480){
        clientHeight = 480;
    }
    var bigHeight=imgs.eq(0).height();
    showBigImg.css("height",bigHeight);
    var top = (clientHeight-bigHeight)/2;
    showBigDiv.css("top",top);
    showBigDiv.css("left",myLeft);
    showBigDiv.css("width",0);
    showBigDiv.css("height",0);


    var showBigNav = $("#showBigNav");
    showBigNav.empty();
    var navI = 0;
    imgs.each(function(){
        showBigNav.append('<li name="'+$(this).attr('name')+'" id="'+navI+'">&nbsp;</li>');
        navI=navI+1;
    });
    contentNavIndex=0;
    showBigNav.find('li').eq(contentNavIndex).addClass('active');
    showBigNav.css("top",top+bigHeight-15);
    showBigNav.css("right",myLeft+10);
    showBigNav.fadeIn(800);

    var closeBigButton = $("#closeBigButton");
    closeBigButton.css("top",top-10);
    closeBigButton.css("right",myLeft+5);
    closeBigButton.fadeIn(800);

    var priceInput = item.find("input[name=intPrice_0]");
    var price = Number(priceInput.val());

    var bigPrice = $("#bigPrice");
    var bigAddDeleted = $("#bigAddDeleted");
    var top2 = top+bigHeight+10;
    var left2 = (clientWidth/2-bigAddDeleted.width())/2+myLeft;
    bigAddDeleted.css("top",top2);
    bigAddDeleted.css("right",left2);
    bigAddDeleted.fadeIn(800);
    bigPrice.css("top",top2);
    bigPrice.css("left",20+myLeft);
    $("#bigPriceFont").text(price/100);
    $("#bigSpecName").text(item.find("input[name=specName]").val());
    bigPrice.fadeIn(800);
    showBigDiv.animate( {width:clientWidth,height:bigHeight},500);
}
var itemDivId="";
var nowL = 0;
var nowT = 0;
var minL = 0;
var minT = 0;
var clientWidth = 0;
var contentList;
var contentNavIndex=0;
function initMyTouch(){
    contentList.css("left",0);
    contentList.css("top",0);
    nowL = 0;
    minL = clientWidth-contentList.width();
}
function closeBig(){
    var showBigDiv = $("#showBigDiv");
    var showBigImg = showBigDiv.find(".showBigImg");
    var item = $("#item" + itemDivId);
    item.append(showBigImg);
    itemDivId="";
    showBigDiv.hide();
    $("#showBigNav").hide();
    $("#closeBigButton").hide();
    $("#bigPrice").hide();
    $("#bigAddDeleted").hide();
    $("#showBigBg").hide();
}

function initArrow(){
    $("#showBigDiv").touchwipe( {
        wipeLeft:function() {
            nowL = (contentNavIndex+1) * -clientWidth;
            if(nowL<minL){
                nowL = minL;
            }else{
                contentNavIndex+=1;
            }
            contentList.animate( {left:nowL},500);
            var lis = $("#showBigNav").find('li');
            lis.removeClass('active');
            lis.eq(contentNavIndex).addClass('active');
        },wipeRight:function() {
            nowL = (contentNavIndex-1) * -clientWidth;
            if(nowL>0){
                nowL = 0;
            }else{
                contentNavIndex-=1;
            }
            contentList.animate( {left:nowL},500);
            var lis = $("#showBigNav").find('li');
            lis.removeClass('active');
            lis.eq(contentNavIndex).addClass('active');
        },wipeUp:function() {
        },wipeDown:function() {
        }
    });

    $("#showBigNav").on('click', 'li', function() {
        var li = $(this);
        contentNavIndex = Number(li.attr("id"));
        nowL = contentNavIndex * -clientWidth;
        contentList.animate( {left:nowL},500);
        var lis = $("#showBigNav").find('li');
        lis.removeClass('active');
        lis.eq(contentNavIndex).addClass('active');
    });
}
function addBigItem(){
    addItem(itemDivId);
}
function removeBigItem(){
    removeItem(itemDivId);
}