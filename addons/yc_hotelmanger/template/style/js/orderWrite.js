/*from tccdn minify at 2014-7-31 15:21:37,file：/cn/h/m/orderWrite.4.js?v=2014072901*/
/*from tccdn minify at 2014-7-4 16:16:49,file：/cn/h/m/orderWrite.2.js*/
/*from tccdn minify at 2014-6-3 13:45:31,file：/cn/h/m/orderWrite.1.js?v=2014042803*/
var isCNFocus = false,
    isTRetn = false,
    add = 0;

if($("#Encstid").html()){
    var url = 'http://www.ly.com/hotels/hotel/ajax/SpecialTopicNew.aspx?action=SpecialTopicStats&stid='+ $("#Encstid").html() +'&hsid='+ $("#hotelid").html()+'&ResType='+$("#resType").html()+'&PageType=2&PlatId=1';
    $.ajax({
        url:url ,
        type:"get",
        dataType : "jsonp"
    })
}
var promoId = $("#PromoId").html(),
    tcmx = "",
    datpwro = 0,
    datpwrotoo = 0,
    sensitiveList = ["法轮功", "发轮功", "张三", "李四", "王五", "SB", "逼", "傻逼", "傻冒", "王八", "王八蛋", "混蛋", "你妈", "你大爷", "操你妈", "你妈逼", "先生", "小姐", "男士", "女士", "夫人", "小沈阳", "丫蛋", "男人", "女人", "骚", "騒", "搔", "傻", "逼", "叉", "瞎", "屄", "屁", "性", "骂", "疯", "臭", "贱", "溅", "猪", "狗", "屎", "粪", "尿", "死", "肏", "骗", "偷", "嫖", "淫", "呆", "蠢", "虐", "疟", "妖", "腚", "蛆", "鳖", "禽", "兽", "屁股", "畸形", "饭桶", "脏话", "可恶", "吭叽", "小怂", "杂种", "娘养", "祖宗", "畜生", "姐卖", "找抽", "卧槽", "携程", "无赖", "废话", "废物", "侮辱", "精虫", "龟头", "残疾", "晕菜", "捣乱", "三八", "破鞋", "崽子", "混蛋", "弱智", "神经", "神精", "妓女", "妓男", "沙比", "恶性", "恶心", "恶意", "恶劣", "笨蛋", "他丫", "她丫", "它丫", "丫的", "给丫", "删丫", "山丫", "扇丫", "栅丫", "抽丫", "丑丫", "手机", "查询", "妈的", "犯人", "垃圾", "死鱼", "智障", "浑蛋", "胆小", "糙蛋", "操蛋", "肛门", "是鸡", "无赖", "赖皮", "磨几", "沙比", "智障", "犯愣", "色狼", "娘们", "疯子", "流氓", "色情", "三陪", "陪聊", "烤鸡", "下流", "骗子", "真贫", "捣乱", "磨牙", "磨积", "甭理", "尸体", "下流", "机巴", "鸡巴", "鸡吧", "机吧", "找日", "婆娘", "娘腔", "恐怖", "穷鬼", "捣乱", "破驴", "破罗", "妈必", "事妈", "神经", "脑积水", "事儿妈", "草泥马", "杀了铅笔", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J8", "s.b", "sb", "sbbt", "Sb", "Sb", "sb", "bt", "bt", "sb", "saorao", "SAORAO", "fuck", "shit", "0", "*", ".", "(", ")", "（", "）", ":", ";", "-", "_", "－", "<", ">", "”", "’", "&", '\\', "：", "="];
//今日对象
function Today() {
    this.now = new Date();
    this.year = this.now.getFullYear();
    this.month = this.now.getMonth();
    this.day = this.now.getDate();
}
var today = new Today(),
    year = today.year,
    month = today.month + 1,
    day = today.day;
tday = year + "-" + month + "-" + day;
$(function() {
    var hotelid = $("#hotelid").html(),
        roomtypeid = $("#roomtypeid").html(),
        policyid = $("#policyid").html(),
        comedate = $("#comedate").html(),
        leavedate = $("#leavedate").html(),
        fullDayFlag = $("#fullDayFlag").html();

    var data = "";
    data += "hotelid=" + hotelid;
    data += "&roomtypeid=" + roomtypeid;
    data += "&policyid=" + policyid;
    data += "&comedate=" + comedate;
    data += "&leavedate=" + leavedate;
    var roomcount = $("#roomnum ul li.at").attr("value");//弹窗中选择的房间数
    if (roomcount == undefined) {
        roomcount = 1
    }
    $(".o-select").html(roomcount + "间");
    var cometime = $("#timeroome ul li.at").attr("value") == undefined ? 29 : $("#timeroome ul li.at").attr("value");
    var comthtml = $("#timeroome ul li.at").html();
    var cityID = $("#cityId").html();
    $(".end_time").html(comthtml);
    $.ajax({
        url: "getTimeNode.html",
        data: data + "&roomcount=" + roomcount + "&cometime=" + cometime + "&fullDayFlag=" + fullDayFlag + "&cityid=" + cityID,
        type: "post",
        dataType: "json",
        success: function(c) {
            if (c.state != 100) {
                $("#submitBtn").addClass("disabled");
                $("#wrong").html(c.Error);
                wrong.open();
                datpwro = 1;
            } else {
                $("#submitBtn").removeClass("disabled");
                //控制最晚到店时间的显隐
                if (c.timeType == 0 || (c.timeType == 1 && c.isDanbao == 5) || (c.timeType == 1 && c.isDanbao == 3)) {
                    $("#timeCount").show();
                } else {
                    $("#timeCount").hide();
                }

                var ret = "",
                    danBaoPrice = '<span style="color:#f60">¥' + c.danBaoPrice + '</span>',
                    orderPrice = "";
                if (c.isReturn == 2) {
                    orderPrice = '<span style="color:#f60">¥' + c.orderPrice + '</span>(已减¥' + c.returnPrice + ')';
                } else {
                    orderPrice = '<span style="color:#f60">¥' + c.orderPrice + '</span>';
                }

                if (c.timeType == 3) {
                    c.danBaoInfo = c.danBaoInfo.replace(/##Money##/g, orderPrice);
                } else {
                    c.danBaoInfo = c.danBaoInfo.replace(/##Money##/g, danBaoPrice);
                }

                //0普通1担保2预付3代收代付
                if (c.timeType == 0) {
                    if (c.danBaoPrice == "0") {
                        ret += '<p class="o-tip1">' + c.danBaoInfo + '</p>';
                        if (parseInt($("#isCu").text())) {
                            ret += '<p class="o-tip3"><span class="o-tipcu">51预售</span>该酒店正在参加51预售，房型满100返30，满200返50，满300返100，限时抢购，售完即止。</p>';
                        }
                    } else {

                        var cstime = $("#cstime").html();
                        var ctime = $("#timeroome ul li.at").html().split("之")[0];
                        if (cstime == 0) {
                            ret += '<p class="o-tip2"><span class="o-tipdb">担保</span>' + c.danBaoInfo + '</p>';
                        } else {
                            ret += '<p class="o-tip2"><span class="o-tipdb">担保</span>' + c.danBaoInfo + '</p>';
                        }
                        if (parseInt($("#isCu").text())) {
                            ret += '<p class="o-tip3"><span class="o-tipcu">51预售</span>该酒店正在参加51预售，房型满100返30，满200返50，满300返100，限时抢购，售完即止。</p>';
                        }
                    }
                } else if (c.timeType == 1) {
                    if (parseInt($("#isCu").text())) {
                        ret += '<p class="o-tip3"><span class="o-tipcu">51预售</span>该酒店正在参加51预售，房型满100返30，满200返50，满300返100，限时抢购，售完即止。</p>';
                    }
                    if (c.isDanbao == 5) {
                        ret += '<p class="o-tip1">' + c.danBaoInfo + '</p>';
                    } else {
                        ret += '<p class="o-tip2"><span class="o-tipdb">担保</span>' + c.danBaoInfo + '</p>';
                    }

                } else if (c.timeType == 2 || c.timeType == 3) {
                    if (parseInt($("#isCu").text())) {
                        ret += '<p class="o-tip3"><span class="o-tipcu">51预售</span>该酒店正在参加51预售，房型满100返30，满200返50，满300返100，限时抢购，售完即止。</p>';
                    }
                    ret += '<p class="o-tip2"><span class="o-tipfuf">预付</span>' + c.danBaoInfo + '</p>';
                }
                //返现、立减
                if (c.isReturn == 1) {
                    if (isRefid()) {
                        ret += '<p class="fan"><span class="f_tb">返</span>确认入住后立返¥' + c.returnPrice + '</p>';
                    } else {
                        ret += '<p class="fan"><span class="f_tb">返</span>点评后返现¥' + c.returnPrice + '</p>';
                    }
                } else if (c.isReturn == 2) {
                    ret += '<p class="jan"><span class="j_tb">减</span>¥' + c.returnPrice + '</p>';
                }
                $("#isDanBao").html(ret);
                //是否担保
                var Currency = $("#Currency").html(); //是否港澳
                tcmx += '<p class="tc_mx_tit">费用明细</p><div class="tc_mx_cnt"><ul id="overid" class="clearfix">'
                for (var i = 0; i < c.pricelist.length; i++) {
                    if (c.timeType == 2 || c.timeType == 3)
                    {
                                   tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>¥' + c.pricelist[i].CNY + 'X1间</p></li>';             
                    }
                else
                {

                    switch (Currency) {
                        case "0":
                            tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>¥' + c.pricelist[i].CNY + 'X1间</p></li>';
                            break;
                        case "1":
                            tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>HKD' + c.pricelist[i].Price + 'X1间</p></li>';
                            break;
                        case "2":
                            tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>NT$' + c.pricelist[i].Price + 'X1间</p></li>';
                            break;
                        case "3":
                            tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>MOP' + c.pricelist[i].Price + 'X1间</p></li>';
                            break;
                        default:
                            break;
                    }
                }
                }
                tcmx += '</ul>';
                if (c.timeType == 0) {
                    if (c.danBaoPrice == "0") {
                        $("#submitBtn").removeClass("order-btn1").addClass("order-btn");
                        $(".dbprice").addClass("none");
                        switch (Currency) {
                            case "0":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款¥' + c.foreignPrice + '</p>';
                                break;
                            case "1":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款HKD' + c.foreignPrice + '</p>';
                                break;
                            case "2":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款NT$' + c.foreignPrice + '</p>';
                                break;
                            case "3":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款MOP' + c.foreignPrice + '</p>';
                                break;
                            default:
                                break;
                        }
                        $(".tjdd").html("提交订单");
                    } else {
                        $("#submitBtn").removeClass("order-btn").addClass("order-btn1");
                        $(".dbprice").removeClass("none");
                        $(".dbprice").html("担保金额：<span id='DBPrice'>¥" + c.danBaoPrice + "</span>")
                        switch (Currency) {
                            case "0":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款¥' + c.foreignPrice + '</p>';
                                break;
                            case "1":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款HKD' + c.foreignPrice + '</p>';
                                break;
                            case "2":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款NT$' + c.foreignPrice + '</p>';
                                break;
                            case "3":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款MOP' + c.foreignPrice + '</p>';
                                break;
                            default:
                                break;
                        }
                        $(".tjdd").html("担保");
                    }
                } else if (c.timeType == 1) {
                    if (c.isDanbao == 5) {
                        $("#submitBtn").removeClass("order-btn1").addClass("order-btn");
                        $(".dbprice").addClass("none");
                        switch (Currency) {
                            case "0":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款¥' + c.foreignPrice + '</p>';
                                break;
                            case "1":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款HKD' + c.foreignPrice + '</p>';
                                break;
                            case "2":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款NT$' + c.foreignPrice + '</p>';
                                break;
                            case "3":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款MOP' + c.foreignPrice + '</p>';
                                break;
                            default:
                                break;
                        }
                        $(".tjdd").html("提交订单");
                    } else {
                        $("#submitBtn").removeClass("order-btn").addClass("order-btn1");
                        $(".dbprice").removeClass("none");
                        $(".dbprice").html("担保金额：<span id='DBPrice'>¥" + c.danBaoPrice + "</span>")
                        switch (Currency) {
                            case "0":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款¥' + c.foreignPrice + '</p>';
                                break;
                            case "1":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款HKD' + c.foreignPrice + '</p>';
                                break;
                            case "2":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款NT$' + c.foreignPrice + '</p>';
                                break;
                            case "3":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款MOP' + c.foreignPrice + '</p>';
                                break;
                            default:
                                break;
                        }
                        $(".tjdd").html("担保");
                    }

                } else if (c.timeType == 2 || c.timeType == 3) {
                    $("#submitBtn").removeClass("order-btn1").addClass("order-btn");
                    $(".dbprice").addClass("none");
                    // switch (Currency) {
                    //     case "0":
                    //         $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                    //         break;
                    //     case "1":
                    //         $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                    //         break;
                    //     case "2":
                    //         $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                    //         break;
                    //     case "3":
                    //         $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                    //         break;
                    //     default:
                    //         break;
                    // }
                     $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.orderPrice + "</span>");
                    $(".tjdd").html("支付");
                }
                $(".tc_mx").html(tcmx);
                new IScroll('.tc_mx_cnt', {
                    mousewheel: true,
                    click: true
                });

            }
        },
        error: function() {
            //HideLoad();
            $("#submitBtn").addClass("disabled");
            $("#wrong").html("请求失败，请重试");
            wrong.open();
            datpwro = 1;
        }
    });
    //后退提示没下单
    $(".page-back").click(function() {
        event.preventDefault();
        event.stopPropagation();
        back1.open();
        datpwro = 1;
    });
    //后退修改日期
    $("#TRetn").click(function(){
        typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent("click", "mbook1_datechange", "修改日期", 10);
        isTRetn = true;
        back1.open();
    })
    //选择入住房间数
    $("#roomCount").click(function() {
        tcTrack(event, "roomchange", "修改房间数", 1);
        add = 1;
        roomCount1.open();
        datpwro = 1;
    });
    $("#roomnum ul li").click(function() {
        $("#roomnum ul li").removeClass("at");
        $(this).addClass("at");
        var a = $(this).html();
        $(".o-select").html(a + "间");
        roomCount1.close();
        datpwro = 0;
        var ahtml = "";
        for (i = 0; i < a; i++) {
            ahtml += '<input type="text" class="contactname1" placeholder="英文姓名，每间填写1人" value="" maxlength="15"/>'
        }
        $(".fn_right").html(ahtml);
        i = i - 1;
        $($(".contactname1")[i]).addClass("lasta");
        clickXZ();
    });
    //选择到店时间
    $("#timeCount").click(function() {
        tcTrack(event, "finaltime", "最晚到店", 2);
        add = 2;
        timeroome1.open();
        datpwro = 1;
    });
    $("#timeroome ul li").click(function() {
        $("#timeroome ul li").removeClass("at");
        $(this).addClass("at");
        var b = $(this).html();
        $(".end_time").html(b);
        timeroome1.close();
        datpwro = 0;
        clickXZ();
    });
    //入住人姓名提示
    $(".nameTip").click(function() {
        typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent("click", "mbook1_name_checkin", "填写入住人提示", 9);
        name1.open() //打开弹框
        datpwro = 1;
        datpwrotoo = 1;
    });
    //手机号输入
    var mobileValue = $("#contactmobile").val();
    $("#contactmobile").focus(function() {
        $("#submitBtn").css("position", "relative");
        //$(this).val("");
    });
    $("#contactmobile").blur(function() {
        $("#submitBtn").css("position", "fixed");
        $(this).val(mobileValue);
    });
    $("#contactmobile").change(function() {
        $("#contactmobile").attr("attr-phone", "");
        mobileValue = $("#contactmobile").val();
    });
    //提交订单
    $("#submitBtnpic").click(function(event) {
        if (datpwrotoo == 1) {
            return;
        }
        tcTrack(event, "submit", "提交按钮", 4);
        if ($(".jt_top").hasClass("jt_down")) {
            setTimeout(function() {
                $(".tc_mx").animate({
                    bottom: '-250px'
                });
                //$(".tc_mx").hide();
                $(".overlys").hide();
            }, 300)
            $(".jt_top").removeClass("jt_down");
        }
        if ($(this).hasClass("yidian")) {
            return;
        }
        event.preventDefault();
        if ($(this).hasClass("disabled")) {
            return false;
        }
        var contactmobile = $("#contactmobile").attr("attr-phone") === "" ? $("#contactmobile").val() : $("#contactmobile").attr("attr-phone");
        roomcount = $("#roomnum ul li.at").attr("value"),
            mfont = false,
            Currency = parseInt($("#Currency").html()),
            cityid = parseInt($("#cityId").html()),
            shenId = parseInt($("#shenId").html());

        if (Currency == 0 && cityid !== 395 && cityid !== 396 && shenId !== 35) {
            canOrder = $("#isCanOrder").html();
            if (canOrder == "1") {
                $("#wrong1").html("抱歉，19点之后，不能预订当天入住的港澳酒店。建议您更改入住日期。");
                wrong1.open();
                datpwro = 1;
                return false;
            }
            var contactname = $("#contactname").val();
            //过滤敏感字符撒
            for (var n = 0; n < sensitiveList.length; n++) {
                if (contactname.indexOf(sensitiveList[n]) > -1) {
                    mfont = true;
                }
            }
            if (contactname == "") {
                $("#wrong1").html("请填写入住人姓名！");
                typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent("click", "mbook1_name_no", "未填姓名弹框", 5);
                wrong1.open();
                datpwro = 1;
                return false;
            } else {
                if (contactname.length >= 2) {
                    if (!/^[\u4e00-\u9fa5]+$/.test(contactname) && !/^[a-zA-Z][a-zA-Z]+$/.test(contactname)) {
                        $("#wrong1").html("请输入正确的入住人姓名，请勿使用符号。");
                        typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent("click", "mbook1_name_wrong", "姓名错误弹框", 7);
                        wrong1.open();
                        datpwro = 1;
                        return false;
                    } else if (mfont) {
                        $("#wrong1").html("请输入合适的入住人姓名，请勿使用敏感字符。");
                        typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent("click", "mbook1_name_wrong", "姓名错误弹框", 7);
                        wrong1.open();
                        datpwro = 1;
                        return false;
                    }
                } else {
                    $("#wrong1").html("入住人姓名必须至少两个字");
                    typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent("click", "mbook1_name_wrong", "姓名错误弹框", 7);
                    isCNFocus = true;
                    wrong1.open();
                    datpwro = 1;
                    return false;
                }
            }
            if ($("#contactmobile").attr("attr-phone") === "") {
                if (contactmobile == "") {
                    $("#wrong1").html("请填写手机号！");
                    typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent("click", "mbook1_number_no", "未填手机号弹框", 6);
                    wrong1.open();
                    datpwro = 1;
                    return false;
                } else if (!/^(13|14|15|18|17)[0-9]{9}$/.test(contactmobile)) {
                    $("#wrong1").html("请正确填写手机号！");
                    typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent("click", "mbook1_number_wrong", "手机号错误弹框", 8);
                    wrong1.open();
                    datpwro = 1;
                    return false;
                }
            }
            var price = $("#totalPrice").html();
            var fullDayFlag = $("#fullDayFlag").html();
            var cometime = $("#timeroome ul li.at").attr("value") == undefined ? 29 : $("#timeroome ul li.at").attr("value");
            var gg = data + "&contactname=" + contactname + "&contactmobile=" + contactmobile + "&roomcount=" + roomcount + "&cometime=" + cometime + "&hn=" + $("#hn").html() + "&orderPrice=" + price + "&cityid=" + cityid;
        } else {
            canOrder = $("#isCanOrder").html();
            if (canOrder == "1") {
                $("#wrong1").html("抱歉，19点之后，不能预订当天入住的港澳酒店。建议您更改入住日期。");
                wrong1.open();
                datpwro = 1;
                return false;
            }
            var j = $(".contactname1").length;
            for (var i = 0; i < j; i++) {
                //过滤敏感字符撒
                var contactname1 = $($(".contactname1")[i]).val();
                for (var n = 0; n < sensitiveList.length; n++) {
                    if (contactname1.indexOf(sensitiveList[n]) > -1) {
                        mfont = true;
                    }
                }
                if (contactname1 == "") {
                    $("#wrong1").html("请填写入住人姓名！");
                    wrong1.open();
                    datpwro = 1;
                    return false;
                } else {
                    if (contactname1.length >= 2) {
                        if (!/^[a-zA-Z]+\/[a-zA-Z]+$/.test(contactname1)) {
                            $("#wrong1").html("请输入正确的入住人姓名，英文姓名格式为zhang/san。");
                            wrong1.open();
                            datpwro = 1;
                            return false;
                        } else if (mfont) {
                            $("#wrong1").html("请输入正确的入住人姓名，请勿使用敏感字符。");
                            wrong1.open();
                            datpwro = 1;
                            return false;
                        }
                    } else {
                        $("#wrong1").html("入住人姓名必须至少两个字");
                        isCNFocus = true;
                        wrong1.open();
                        datpwro = 1;
                        return false;
                    }
                }
            }
            if ($("#contactmobile").attr("attr-phone") === "") {
                if (contactmobile == "") {
                    $("#wrong1").html("请填写手机号！");
                    wrong1.open();
                    datpwro = 1;
                    return false;
                } else if (!/^(13|14|15|18|17)[0-9]{9}$/.test(contactmobile)) {
                    $("#wrong1").html("请正确填写手机号！");
                    wrong1.open();
                    datpwro = 1;
                    return false;
                }
            }

            var contactnameva1 = $($(".contactname1")[0]).val(),
                contactnameva2 = "";
            var price = $("#totalPrice").html();
            var fullDayFlag = $("#fullDayFlag").html();
            var cometime = $("#timeroome ul li.at").attr("value") == undefined ? 29 : $("#timeroome ul li.at").attr("value");
            if (j > 1) {
                for (var k = 1; k < j; k++) {
                    contactnameva2 += $($(".contactname1")[k]).val();
                    contactnameva2 += ","
                }
                contactnameva2 = contactnameva2.substring(0, contactnameva2.length - 1);
                var gg = data + "&contactname=" + contactnameva1 + "&contactmobile=" + contactmobile + "&roomcount=" + roomcount + "&cometime=" + cometime + "&hn=" + $("#hn").html() + "&orderPrice=" + price + "&OtherGuests=" + contactnameva2 + "&cityid=" + cityid;
            } else {
                var gg = data + "&contactname=" + contactnameva1 + "&contactmobile=" + contactmobile + "&roomcount=" + roomcount + "&cometime=" + cometime + "&hn=" + $("#hn").html() + "&orderPrice=" + price + "&cityid=" + cityid;
            }
        }


        var co = $.cookie("findhotel");

        if (co && co == "1") {
            gg += "&tag=1";
        }
        if($("#Encstid").html()){
            gg += '&Encstid='+$("#Encstid").html();
        }

        //360判断
        var refid = getRefid();
        if (refid == 128922994) {
            //s+ 12.07
            gg += '&refid=128922994'
        }

        $("#submitBtn").addClass("yidian");
        loading();
        $.ajax({
            url: "hotelOrder.html?"+gg,
            //data: gg,
            type: "post",
            dataType: "json",
            success: function(c) {
                hiding();
                //HideLoad();
                if (c.state == 200) {
                    $("#wrong1").html(c.Error);
                    wrong1.open();
                    datpwro = 1;
                    $("#submitBtn").removeClass("yidian");
                } else {
                    $("#submitBtn").removeClass("yidian");
                    location.href = c.rurl;


                }
            },
            error: function() {
                $("#wrong1").html("提交失败，请重试！");
                wrong1.open();
                datpwro = 1;
                $("#submitBtn").removeClass("yidian");
            }
        });
    });
    //51返利  token不为空吧和memberid不为空

    var cooka = $.cookie("cnUser");
    if ($.cookie("CNSEInfo")) {
        var cookc = $.cookie("CNSEInfo").split('&')[0].split("=")[1];
        if (!cooka || (cooka && cooka == "")) {
            if (cookc && cookc !== "" && (cookc == "36090387" || cookc == "12027110")) {
                fan51.open();
                datpwro = 1;
            }
        } else {
            var cookb = cooka.split('&'),
                acc = false,
                res = "";
            for (var i = 0; i < cookb.length; i++) {
                res = cookb[i].split('=')[1];
                if (res && res !== "" && (res > 0)) {
                    acc = true;
                    break;
                }
            }
            if ((acc == false) && cookc && cookc !== "" && (cookc == "36090387" || cookc == "12027110")) {
                fan51.open();
                datpwro = 1;
            }
        }
    }
    $(".ui-mask").click(function(){
        switch (add){
            case 1 : 
                roomCount1.close(); 
                break; 
            case 2 : 
                timeroome1.close();
                break; 
            default:break;
        }
        add = 0;
    })
});

//判断入住后返还是点评后返
function isRefid() {
    var refid = getRefid();
    if (refid && refid !== "" && (refid == "30546879" || refid == "31003075" || refid == "30215340")) {
        return true;
    }
    return false;
};
//弹窗组件-姓名提示
var name1 = $.dialog({
    autoOpen: false, //是否自动打开
    closeBtn: false, //是否显示标题上面的关闭按钮
    buttons: {
        '知道了': function() {
            this.close();
            datpwro = 0;
            datpwrotoo = 0;
        },
    },
    title: '温馨提示', //头部内容
    content: $(".namets")
});
//弹窗组件-后退提示没下单
var back1 = $.dialog({
    autoOpen: false, //是否自动打开
    closeBtn: false, //是否显示标题上面的关闭按钮
    buttons: {
        '取消': function() {
            this.close();
            datpwro = 0;
        },
        '确定': function() {
            this.close();
            datpwro = 0;
            //window.location.href = '/hotel/jiudian_'+$("#hotelid").html()+'.html';
            if(isTRetn){
                window.location.href = '/hotel/jiudian_'+$("#hotelid").html()+'.html#hotelcnt';
            }else{
                window.location.href = 'javascript:history.go(-1)';
            }
            

        },
    },
    content: '<p class="tis">您的订单还未完成哦，确定要离开吗?</p>'
});
//弹窗组件-选择入住房间数
var roomCount1 = $.dialog({
    autoOpen: false, //是否自动打开
    closeBtn: false, //是否显示标题上面的关闭按钮
    content: $("#roomnum")
});
//弹窗组件-选择到店时间
var timeroome1 = $.dialog({
    autoOpen: false, //是否自动打开
    closeBtn: false, //是否显示标题上面的关闭按钮
    content: $("#timeroome")
});
//弹窗组件-通用提示
var wrong = $.dialog({
    autoOpen: false, //是否自动打开
    closeBtn: false, //是否显示标题上面的关闭按钮
    buttons: {
        '确定': function() {
            this.close();
            datpwro = 0;
            window.location.href = 'javascript:history.go(-1)';
        },
    },
    content: $("#wrong")
});
//弹窗组件-提示
var wrong1 = $.dialog({
    autoOpen: false, //是否自动打开
    closeBtn: false, //是否显示标题上面的关闭按钮
    buttons: {
        '确定': function() {
            this.close();
            datpwro = 0;
            if (isCNFocus) {
                $("#contactname")[0].focus();
            }
        },
    },
    content: $("#wrong1")
});
//弹窗组件-51返利
var fan51 = $.dialog({
    autoOpen: false, //是否自动打开
    closeBtn: false, //是否显示标题上面的关闭按钮
    buttons: {
        '以后再说': function() {
            this.close();
            datpwro = 0;
        },
        '立即绑定': function() {
            this.close();
            datpwro = 0;
            var rentUrl = encodeURIComponent(location.href);
            window.location.href = "http://m.ly.com/membership/51bind.html?returnUrl=" + rentUrl;
        },
    },
    content: '<p class="tise">尊敬的51返利用户，为确保正常获取返利，请绑定同程帐号。</p>'
});
//选择调用
function clickXZ() {
    loading();
    var hotelid = $("#hotelid").html(),
        roomtypeid = $("#roomtypeid").html(),
        policyid = $("#policyid").html(),
        comedate = $("#comedate").html(),
        leavedate = $("#leavedate").html();
    var fullDayFlag = $("#fullDayFlag").html();
    var data = "";
    data += "hotelid=" + hotelid;
    data += "&roomtypeid=" + roomtypeid;
    data += "&policyid=" + policyid;
    data += "&comedate=" + comedate;
    data += "&leavedate=" + leavedate;
    var roomcount = $("#roomnum ul li.at").attr("value");
    var cometime = $("#timeroome ul li.at").attr("value") == undefined ? 29 : $("#timeroome ul li.at").attr("value");
    var cityID = $("#cityId").html();
    var gg = data + "&roomcount=" + roomcount + "&cometime=" + cometime + "&cityid=" + cityID;

    //ShowLoad();
    $.ajax({
        url: "bookingpolicy.html",
        data: gg,
        type: "post",
        dataType: "json",
        success: function(c) {
            hiding();
            //HideLoad();
            if (c.state != 100) {
                $("#submitBtn").addClass("disabled");
                $("#wrong1").html(c.Error);
                wrong1.open();
                datpwro = 1;
            } else {
                $("#submitBtn").removeClass("disabled");
                //控制最晚到店时间的显隐?
                if (c.timeType == 0 || (c.timeType == 1 && c.isDanbao == 5) || (c.timeType == 1 && c.isDanbao == 3)) {
                    $("#timeCount").show();
                } else {
                    $("#timeCount").hide();
                }

                var ret = "",
                    danBaoPrice = '<span style="color:#f60">¥' + c.danBaoPrice + '</span>',
                    orderPrice = "";
                if (c.isReturn == 2) {
                    orderPrice = '<span style="color:#f60">¥' + c.orderPrice + '</span>(已减¥' + c.returnPrice + ')';
                } else {
                    orderPrice = '<span style="color:#f60">¥' + c.orderPrice + '</span>';
                }
                if (c.timeType == 3) {
                    c.danBaoInfo = c.danBaoInfo.replace(/##Money##/g, orderPrice);
                } else {
                    c.danBaoInfo = c.danBaoInfo.replace(/##Money##/g, danBaoPrice);
                }
                //0普通1担保2预付3代收代付
                if (c.timeType == 0) {
                    if (c.danBaoPrice == "0") {
                        ret += '<p class="o-tip1">' + c.danBaoInfo + '</p>';
                        if (parseInt($("#isCu").text())) {
                            ret += '<p class="o-tip3"><span class="o-tipcu">51预售</span>该酒店正在参加51预售，房型满100返30，满200返50，满300返100，限时抢购，售完即止。</p>';
                        }
                    } else {
                        var cstime = $("#cstime").html();
                        var ctime = $("#timeroome ul li.at").html().split("之")[0];
                        if (cstime == 0) {
                            ret += '<p class="o-tip2"><span class="o-tipdb">担保</span>' + c.danBaoInfo + '</p>';
                        } else {
                            ret += '<p class="o-tip2"><span class="o-tipdb">担保</span>' + c.danBaoInfo + '</p>';
                        }
                        if (parseInt($("#isCu").text())) {
                            ret += '<p class="o-tip3"><span class="o-tipcu">51预售</span>该酒店正在参加51预售，房型满100返30，满200返50，满300返100，限时抢购，售完即止。</p>';
                        }
                    }
                } else if (c.timeType == 1) {
                    if (parseInt($("#isCu").text())) {
                        ret += '<p class="o-tip3"><span class="o-tipcu">51预售</span>该酒店正在参加51预售，房型满100返30，满200返50，满300返100，限时抢购，售完即止。</p>';
                    }
                    if (c.isDanbao == 5) {
                        ret += '<p class="o-tip1">' + c.danBaoInfo + '</p>';
                    } else {
                        ret += '<p class="o-tip2"><span class="o-tipdb">担保</span>' + c.danBaoInfo + '</p>';
                    }
                } else if (c.timeType == 2 || c.timeType == 3) {
                    if (parseInt($("#isCu").text())) {
                        ret += '<p class="o-tip3"><span class="o-tipcu">51预售</span>该酒店正在参加51预售，房型满100返30，满200返50，满300返100，限时抢购，售完即止。</p>';
                    }
                    ret += '<p class="o-tip2"><span class="o-tipfuf">预付</span>' + c.danBaoInfo + '</p>';
                }
                //返现、立减
                if (c.isReturn == "1") {
                    if (isRefid()) {
                        ret += '<p class="fan"><span class="f_tb">返</span>确认入住后立返¥' + c.returnPrice + '</p>';
                    } else {
                        ret += '<p class="fan"><span class="f_tb">返</span>点评后返现¥' + c.returnPrice + '</p>';
                    }
                } else if (c.isReturn == "2") {
                    ret += '<p class="jan"><span class="j_tb">减</span>¥' + c.returnPrice + '</p>';
                }
                $("#isDanBao").html(ret);
                //是否担保
                var Currency = $("#Currency").html();
                tcmx = "";
                tcmx += '<p class="tc_mx_tit">费用明细</p><div class="tc_mx_cnt"><ul id="overid" class="clearfix">';
                for (var i = 0; i < c.pricelist.length; i++) {
                      if (c.timeType == 2 || c.timeType == 3)
                    {
                                   tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>¥' + c.pricelist[i].CNY + 'X' + roomcount + '间</p></li>';           
                    }
                    else{
                    switch (Currency) {
                        case "0":
                            tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>¥' + c.pricelist[i].CNY + 'X' + roomcount + '间</p></li>';
                            break;
                        case "1":
                            tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>HKD' + c.pricelist[i].Price + 'X' + roomcount + '间</p></li>';
                            break;
                        case "2":
                            tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>NT$' + c.pricelist[i].Price + 'X' + roomcount + '间</p></li>';
                            break;
                        case "3":
                            tcmx += '<li class="clearfix"><p>' + c.pricelist[i].Time + '</p><p>' + c.pricelist[i].Breakfast + '</p><p>MOP' + c.pricelist[i].Price + 'X' + roomcount + '间</p></li>';
                            break;
                        default:
                            break;
                    }
                }
                }
                tcmx += '</ul>';
                if (c.timeType == 0) {
                    if (c.danBaoPrice == "0") {
                        $("#submitBtn").removeClass("order-btn1").addClass("order-btn");
                        $(".dbprice").addClass("none");
                        switch (Currency) {
                            case "0":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款¥' + c.foreignPrice + '</p>';
                                break;
                            case "1":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款HKD' + c.foreignPrice + '</p>';
                                break;
                            case "2":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款NT$' + c.foreignPrice + '</p>';
                                break;
                            case "3":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款MOP' + c.foreignPrice + '</p>';
                                break;
                            default:
                                break;
                        }
                        $(".tjdd").html("提交订单");
                    } else {
                        $("#submitBtn").removeClass("order-btn").addClass("order-btn1");
                        $(".dbprice").removeClass("none");
                        $(".dbprice").html("担保金额：<span id='DBPrice'>¥" + c.danBaoPrice + "</span>")
                        switch (Currency) {
                            case "0":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款¥' + c.foreignPrice + '</p>';
                                break;
                            case "1":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款HKD' + c.foreignPrice + '</p>';
                                break;
                            case "2":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款NT$' + c.foreignPrice + '</p>';
                                break;
                            case "3":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款MOP' + c.foreignPrice + '</p>';
                                break;
                            default:
                                break;
                        }
                        $(".tjdd").html("担保");
                    }
                } else if (c.timeType == 1) {
                    if (c.isDanbao == 5) {
                        $("#submitBtn").removeClass("order-btn1").addClass("order-btn");
                        $(".dbprice").addClass("none");
                        switch (Currency) {
                            case "0":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款¥' + c.foreignPrice + '</p>';
                                break;
                            case "1":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款HKD' + c.foreignPrice + '</p>';
                                break;
                            case "2":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款NT$' + c.foreignPrice + '</p>';
                                break;
                            case "3":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款MOP' + c.foreignPrice + '</p>';
                                break;
                            default:
                                break;
                        }
                        $(".tjdd").html("提交订单");
                    } else {
                        $("#submitBtn").removeClass("order-btn").addClass("order-btn1");
                        $(".dbprice").removeClass("none");
                        $(".dbprice").html("担保金额：<span id='DBPrice'>¥" + c.danBaoPrice + "</span>")
                        switch (Currency) {
                            case "0":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款¥' + c.foreignPrice + '</p>';
                                break;
                            case "1":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款HKD' + c.foreignPrice + '</p>';
                                break;
                            case "2":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款NT$' + c.foreignPrice + '</p>';
                                break;
                            case "3":
                                $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                                tcmx += '</div><p class="tc_mx_jg">入住时在酒店前台付款MOP' + c.foreignPrice + '</p>';
                                break;
                            default:
                                break;
                        }
                        $(".tjdd").html("担保");
                    }
                } else if (c.timeType == 2 || c.timeType == 3) {
                    $("#submitBtn").removeClass("order-btn1").addClass("order-btn");
                    $(".dbprice").addClass("none");
                    //switch (Currency) {
                    //    case "0":
                    //        $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.foreignPrice + "</span>");
                    //        break;
                    //    case "1":
                    //        $(".pbprice").html("订单金额：<span id='totalPrice'>HKD" + c.foreignPrice + "</span>");
                    //        break;
                    //    case "2":
                    //        $(".pbprice").html("订单金额：<span id='totalPrice'>NT$" + c.foreignPrice + "</span>");
                    //        break;
                    //    case "3":
                    //        $(".pbprice").html("订单金额：<span id='totalPrice'>MOP" + c.foreignPrice + "</span>");
                    //        break;
                    //    default:
                    //        break;
                    //}
                    $(".pbprice").html("订单金额：<span id='totalPrice'>¥" + c.orderPrice + "</span>");
                    $(".tjdd").html("支付");
                }
                $(".tc_mx").html(tcmx);
                new IScroll('.tc_mx_cnt', {
                    mousewheel: true,
                    click: true
                });
            }
        },
        error: function() {
            $("#submitBtn").addClass("disabled");
            //HideLoad();
            $("#wrong1").html("请求失败，请重试");
            wrong1.open();
            datpwro = 1;
        }
    });
};
//加载层
function loading() {
    $('#pageLoading').css('display', 'block')
}

function hiding() {
    $('#pageLoading').css('display', 'none')
}
//提交按钮浮动底部
/*function floatdown(){
 var selector = '#submitBtn';//提交框
 var noInputViewHeight = $(window).height() - $(selector).height();//无软键盘时div到窗口顶部距离
 var contentHeight = $(document).height() - $(selector).height();//正文内容高度
 var startScrollY = $(window).scrollTop();//获得焦点时滚动条的起始距离
 var inputHeight = $(selector).offset().top - startScrollY;//获得焦点时div到窗口顶部的距离，即到软键盘顶部的起始距离
 var inputTopPos =$(selector).offset().top + inputHeight ;//获得焦点时div的预期位置，即紧贴软键盘时的top值
 contentHeight = contentHeight > noInputViewHeight ? contentHeight : noInputViewHeight;//控制正文内容高度大于一屏的高度
 inputTopPos = inputTopPos > contentHeight ? contentHeight : inputTopPos;//控制div不超出正文范围
 if (inputHeight != noInputViewHeight) {//表示此时有软键盘存在，div浮在页面上了
 $(window).bind('scroll', function(){//给窗口对象绑定滚动事件，保证页面滚动时div能吸附软键盘
 var inputTopPos = inputTopPos + ($(window).scrollTop() - startScrollY);//页面滚动时，div需移动距离
 $(selector).css({'position':'absolute', 'top':inputTopPos });
 });
 }
 }*/
$("#priceInfo").click(function() {
    tcTrack(event, "value", "订单金额详情", 3);
    if (datpwro == 0) {
        if ($(".jt_top").hasClass("jt_down")) {
            setTimeout(function() {
                $(".tc_mx").animate({
                    bottom: '-250px'
                });
            }, 300)
            $(".overlys").hide();
            $(".jt_top").removeClass("jt_down");
        } else {
            $(".tc_mx").show();
            $(".overlys").show();
            setTimeout(function() {
                $(".tc_mx").animate({
                    bottom: '51px'
                });
            }, 300)
            $(".jt_top").addClass("jt_down");
            new IScroll('.tc_mx_cnt', {
                mousewheel: true,
                click: true
            });
        }
    }
})
$(".overlys").click(function() {
    setTimeout(function() {
        $(".tc_mx").animate({
            bottom: '-250px'
        });
        //$(".tc_mx").hide();
        $(".overlys").hide();
    }, 300)

    $(".jt_top").removeClass("jt_down");
})

/*监控*/
function tcTrack(event, name, describe, id){
    var dbType = getDBT(),
        action = "",
        ids = "",
        txt = "";

    if (dbType == 2){

        action = "mbook1_yf_";
        ids = "预付" + id;
        txt = "预付" + describe;

    }else if (dbType == 1){

        action = "mbook1_db_";
        ids = "担保" + id;
        txt = "担保" + describe;

    }else {

        action = "mbook1_xf_";
        ids = "现付" + id;
        txt = "现付" + describe;

    }

    typeof(_tcTraObj) !== "undefined" && _tcTraObj._tcTrackEvent && _tcTraObj._tcTrackEvent(event, action + name, txt, ids);
}
function getDBT() {
    var DanBaoClass = $(".tjdd").text(),
        DanBaoType;
    switch (DanBaoClass){
        case "提交订单":
            DanBaoType = 0;
            break;
        case "担保":
            DanBaoType = 1;
            break;
        case "支付":
            DanBaoType = 2;  
            break; 
        default:break;
    }
    return DanBaoType;
}
