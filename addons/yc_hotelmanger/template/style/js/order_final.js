$(function (){
    page.init();
    var sensitiveList = ["法轮功","发轮功","张三","李四","王五","SB","逼","傻逼","傻冒","王八","王八蛋","混蛋","你妈","你大爷","操你妈","你妈逼","先生","小姐","男士","女士","夫人",
                    "小沈阳","丫蛋","男人","女人","骚","騒","搔","傻","逼","叉","瞎","屄","屁","性","骂","疯","臭","贱","溅","猪","狗","屎","粪","尿","死","肏","骗","偷","嫖",
                    "淫","呆","蠢","虐","疟","妖","腚","蛆","鳖","禽","兽","屁股","畸形","饭桶","脏话","可恶","吭叽","小怂","杂种","娘养","祖宗","畜生","姐卖","找抽","卧槽",
                    "携程","无赖","废话","废物","侮辱","精虫","龟头","残疾","晕菜","捣乱","三八","破鞋","崽子","混蛋","弱智","神经","神精","妓女","妓男","沙比","恶性","恶心",
                    "恶意","恶劣","笨蛋","他丫","她丫","它丫","丫的","给丫","删丫","山丫","扇丫","栅丫","抽丫","丑丫","手机","查询","妈的","犯人","垃圾","死鱼","智障","浑蛋",
                    "胆小","糙蛋","操蛋","肛门","是鸡","无赖","赖皮","磨几","沙比","智障","犯愣","色狼","娘们","疯子","流氓","色情","三陪","陪聊","烤鸡","下流","骗子","真贫",
                    "捣乱","磨牙","磨积","甭理","尸体","下流","机巴","鸡巴","鸡吧","机吧","找日","婆娘","娘腔","恐怖","穷鬼","捣乱","破驴","破罗","妈必","事妈","神经","脑积水",
                    "事儿妈","草泥马","杀了铅笔","1","2","3","4","5","6","7","8","9","10","J8","s.b","sb","sbbt","Sb","Sb","sb","bt","bt","sb","saorao","SAORAO","fuck","shit",
                    "0","*",".","(",")","（","）",":",";","-","_","－","<",">","”","’","&",'\\',"：","="];
    //催处理
    $("#dispose").click(function (){
        if(!($(this).hasClass("inf0_tb1"))){
            var serialid = $("#sid").html();
            $.ajax({
                url:"/hotel/json/orderremindquery.html?hastenType=1&serialid="+serialid,
                dataType:"json",
                success:function (data){
                    if(data.RspStatus == 1){
                        $("#dispose").html("处理中...").addClass("inf0_tb1");
                        showTips.open();
                    }else{
                        showTips1.open();
                    }
                }
            })
        }
    })
    $("#OFF").click(function(){
        showAll.open();
        $("#Ccloseb").click(function(){
            page.close();  
        })
    })
    $("#verify").click(function(){
        if(!($(this).hasClass("inf0_tb1"))){
            page.open('C');    
            $(".contactname").removeClass("ttpps");
            $(".contactmobile").removeClass("ttpps");
        }
    })

    
    //催核房   mName hotelRoomNo remark
    $(".sure").click(function(event){
        if($(this).hasClass("yidian")){
            return;
        }
        event.preventDefault();
        var mfont = false,
            divlength = $(".warp2").length;
        for (var i=0; i<divlength; i++){
            var contactname = $($(".contactname")[i]).val(),
                rooms = $($(".contactmobile")[i]).val(),
                contactnamebox = $($(".contactname")[i]),
                roomsbox = $($(".contactmobile")[i]);
            for (var n=0;n<sensitiveList.length;n++){
                if (contactname.indexOf(sensitiveList[n])>-1){
                    mfont = true;
                }
            }
            if (contactname == "") {
                $("#tips").html("入住人姓名不能为空");
                contactnamebox.addClass("ttpps");
                tips.open();
                return false;
            } else if (!/^[\u4e00-\u9fa5]+$/.test(contactname) && !/^[a-zA-Z][a-zA-Z]+$/.test(contactname)&& !/^[a-zA-Z]+\/[a-zA-Z]+$/.test(contactname)){
                $("#tips").html("请输入正确的入住人姓名");
                tips.open();
                contactnamebox.addClass("ttpps");
                return false;
            } else if (mfont) {
                $("#tips").html("请输入正确的入住人姓名");
                tips.open();
                contactnamebox.addClass("ttpps");
                return false;
            }
            if(rooms == ""){
                $("#tips").html("入住房间号不能为空");
                roomsbox.addClass("ttpps");
                tips.open();
                return false;
            }
        }

        var mName = "",
            hotelRoomNo ="",
            remark ="",
            serialid = $("#sid").html();

        for(var k=0; k<divlength; k++){
            mName += $($(".contactname")[k]).val();
            mName +=",";
            hotelRoomNo += $($(".contactmobile")[k]).val();
            hotelRoomNo +=",";
        }

        $.ajax({
            url:"/hotel/json/orderremindquery.html?hastenType=0",
            data:"&serialid="+ serialid +"&mName="+ mName +"&hotelRoomNo="+ hotelRoomNo +"&remark=", 
            dataType:"json",
            success:function (data){
                if(data.RspStatus == 1){
                    $("#verify").html("核房中...").addClass("inf0_tb1");
                    showTips.open();
                    page.close();
                }else{
                    showTips1.open();
                }
            }
        })
    })
    
}) 
var tips = $.dialog({
        autoOpen: false,
        closeBtn: false,
        style:'tip',
        mask:false,
        width:240,
        closeTime:1000,
        maskClick:function(){
            console.log(1);
        },
        content: $("#tips")
    }); 

var showTips = $.dialog({
        autoOpen: false,
        closeBtn: false,
        style:'tip',
        mask:false,
        width:240,
        closeTime:1000,
        maskClick:function(){
            console.log(1);
        },
        content: '<p>您的订单正在飞速处理中...</p>'
    }); 

var showTips1 = $.dialog({
        autoOpen: false,
        closeBtn: false,
        style:'tip',
        mask:false,
        width:240,
        closeTime:1000,
        maskClick:function(){
            console.log(1);
        },
        content: '<p style="text-align:center;">处理中失败</p>'
    }); 

var showAll = $.dialog({
        buttons: {
            '是(Y)': function(){
                this.close();
                setTimeout(function(){
                    page.open('B'); 
                },200);
            },
            '否(N)': function(){
                this.close();
            }
        },
        content:'<p style="text-align:center;color:#333;font-size:16px;">您确定要取消订单吗？</p>'
    });

var showTipe = $.dialog({
        autoOpen: false,
        closeBtn: false,
        style:'tip',
        mask:false,
        width:240,
        closeTime:1000,
        maskClick:function(){
            console.log(1);
        },
        content: '<p style="text-align:center">取消成功</p>'
    }); 

var showTipr = $.dialog({
        autoOpen: false,
        closeBtn: false,
        style:'tip',
        mask:false,
        width:240,
        closeTime:1000,
        maskClick:function(){
            console.log(1);
        },
        content: '<p style="text-align:center">取消失败</p>'
    }); 



var romeCount = $("#romeCount").html(),
    romeCountclick = 1;
    if(romeCount==1){
        $("#add").addClass("none");
    }else{
        $("#add").removeClass("none");
    }
$("#add").click(function(){
    if(romeCountclick < romeCount){
        var list ='<section class="warp2"><div class="fn-clear"><span>姓名</span><input type="text" class="contactname" onclick="import1(this)" placeholder="请输入入住人姓名" value="" maxlength="15"></div><div class="fn-clear last_fn"><span>房间号</span><input type="tel" class="contactmobile" onclick="import1(this)" placeholder="请输入入住房间号" value="" maxlength="11"></div><div class="round_grey" onclick="clicks(this)"></div></section>'
        $("#section").append(list); 
        romeCountclick++;
        if(romeCountclick >= romeCount){
            $("#add").addClass("none");
        }
    }else{
        $("#add").addClass("none");
    }
})



function clicks(e){
    $(e.parentNode).remove();
    romeCountclick--;
    $("#add").removeClass("none");
}



function calloff(a){
    $("li", a.parentNode).removeClass("active");
    $(a).addClass("active");
    var reason = $(a).attr("value"),
        orderSerialId = $("#sid").html(),
        hotelName = $("#hn").html();
    $.ajax({
        url:"/hotel/json/hotelcancelorder.html?",
        data:"orderSerialId="+ orderSerialId +"&reason="+ reason +"&hotelName="+ hotelName,
        dataType:"json",
        success: function (data){
            if(data.RspStatus == 1){
                showTipe.open();
                setTimeout(function(){
                    window.location.href="/hotel/orderlist.html";
                },1000)
            }else{
                showTipr.open();
            }
        }
    })
}

function import1(c){
    if($(c).hasClass("ttpps")){
        $(c).removeClass("ttpps");
    }
}

function jump(d){
    var href = $(d).attr("href");
    window.location.href = href;
}