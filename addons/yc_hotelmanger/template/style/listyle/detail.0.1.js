!

function(e) {

    function t(o) {

        if (a[o]) return a[o].exports;

        var i = a[o] = {

            exports: {},

            id: o,

            loaded: !1

        };

        return e[o].call(i.exports, i, i.exports, t),

        i.loaded = !0,

        i.exports

    }

    var a = {};

    return t.m = e,

    t.c = a,

    t.p = "",

    t(0)

} ({

    0 : function(e, t, a) {

        e.exports = a(81)

    },

    81 : function(e, t) {

        "use strict";

        function a() {

            $("#pageLoading").css("display", "none")

        }

        function o(e) {

            return e = parseInt(e),

            10 > e && e >= 0 && (e = "0" + e),

            e

        }

        function i(e) {

            if (e) {

                var t;

                t = "string" == typeof e ? new Date(e.replace(/-/g, "/")) : e;

                var a = function(e) {

                    var t = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];

                    return t[e] || ""

                },

                o = a(t.getDay());

                return o

            }

        }

        function s(e, t, a) {

            var o = $(t),

            i = 0,

            s = new TouchSlider(e, {

                auto: a,

                fx: "ease-out",

                direction: "left",

                speed: 600,

                timeout: 3e3,

                mouseDrag: !0,

                mouseWheel: !1,

                before: function(e) {

                    o[i].className = "",

                    o[e].className = "active",

                    i = e

                }

            });

            o.on("click",

            function() {

                return s.slide($(this).index()),

                !1

            })

        }

        function n(e, t) {

            function a(e, t) {

                var e = e[0],

                t = t || 0,

                a = null,

                o = null,

                i = null;

                null !== e && (a = e.querySelectorAll("div"), o = a[0] || document.createElement("div"), i = a[1] || document.createElement("div"), e.setAttribute("percent", t), 50 >= t && t >= 0 ? (o.style.visibility = "hidden", $(i).css("transform", "rotate(-" + 3.6 * t + "deg)")) : t > 50 && (t = t > 100 ? 100 : t, o.style.visibility = "visible", $(o).css("transform", "rotate(-" + 3.6 * (t - 50) + "deg)"), $(i).css("transform", "rotate(-180deg)")), !a[0] && e.appendChild(o), !a[1] && e.appendChild(i))

            }

            function o(e) {

                return i(e / 100 * 5, 1).toFixed(1)

            }

            function i(e, t) {

                var a = Math.pow(10, t);

                return Math.round(e * a) / a

            }

            var t = parseInt(t / 5 * 100),

            s = null,

            n = 0;

            null !== e && ($(".circle", e).length && $(".circle", e).css("display", "block"), s = setInterval(function() {

                n >= t ? clearInterval(s) : (n++, a($(".circle", e), n), $(".percent span", e).html(o(n)))

            },

            17))

        }

        var l, c, r = !!$("#snappingPage").length;

        $(window).on("popstate",

        function() {

            if (r) $(".overlyLoading").css("display", "block"),

            $(".overlyLoading").css("opacity", 1),

            /*$("#pageLoading").css("display", "block"),*/

            setTimeout(function() {

                $(".overlyLoading").css("display", "none"),

                $("#pageLoading").css("display", "none")

            },

            200);

            else {

                var e = window.location.href;

                if (e.match(/hotellist/gi)) return void(window.location.href = window.location.href);

                $(".current").css("display", "none").removeClass("current"),

                $("#mainPage").css("display", "block").addClass("current"),

                setTimeout(function() {

                    $(".overlyLoading").css("display", "none"),

                    $("#pageLoading").css("display", "none")

                },

                200)

            }

        }),

        function() {

            var e = {

                init: function() {

                    e.commentAndDetails(),

                    e.dateopen(),

                    e.getRoomList(),

                    e.goHref(),

                    e.todoFavorite(),

                    this.onlineServer(),

                    e.scorePercent(),

                    e.satisfactionInit()

                },

                dateopen: function() {

                    var t, s, n, l = ["元宵", "清明", "端午", "七夕", "中秋", "重阳", "情人", "五一", "儿童", "国庆", "圣诞"],

                    c = new Date,

                    r = $("#sInDate .choose-time").attr("date-time"),

                    p = $("#sOutDate .choose-back-time").attr("date-time"),

                    d = $.dialog({

                        autoOpen: !1,

                        closeBtn: !1,

                        buttons: {

                            "确定": function() {

                                a(),

                                this.close()

                            }

                        },

                        content: '<p>如果您需要在酒店入住20天以上,请致电<a class="telephone"  href="tel:4007777777">4007777777</a>,我们会竭诚为您服务'

                    });

                    $("#sDate").on("click",

                    function() {

                        var a = c.getFullYear() + "-" + (c.getMonth() + 1) + "-" + c.getDate(),

                        m = a.split("-");

                        m = new Date(m[0], parseInt(m[1]) - 1, parseInt(m[2]) + 58),

                        m = m.getFullYear() + "-" + (m.getMonth() + 1) + "-" + m.getDate(),

                        $.calendar({

                            mode: "range",

                            endDate: m,

                            title: " ",

                            rangeTxt: ["入住", "离店"],

                            currentDate: [r, p],

                            dateVerify: function(e) {

                                for (var t = 0; 2 > t; t++) for (var a = 0; 3 > a; a++) if (0 != a) {

                                    var o = e[t][a];

                                    e[t][a] = ("00" + o).slice(o.length)

                                }

                                return e[1] > e[0]

                            },

                            fn: function(a) {

                                $(".choose-time").attr("date-time", a[0].join("-")),

                                t = new Date(a[0].join("/")),

                                $(".go_week").text(i(t)),

                                $(".choose-time em").html(o(a[0][1]) + "-" + o(a[0][2])),

                                $(".choose-back-time").attr("date-time", a[1].join("-")),

                                s = new Date(a[1].join("/")),

                                $(".leave_week").text(i(s)),

                                $(".choose-back-time em").html(o(a[1][1]) + "-" + o(a[1][2])),

                                r = $("#sInDate .choose-time").attr("date-time"),

                                p = $("#sOutDate .choose-back-time").attr("date-time");

                                var l, c = r.split("-"),

                                m = p.split("-");

                                return c = new Date(c[0], parseInt(c[1]) - 1, parseInt(c[2])),

                                m = new Date(m[0], parseInt(m[1]) - 1, parseInt(m[2])),

                                l = (m - c) / 1e3 / 60 / 60 / 24,

                                console.log(l),

                                0 >= l || l > 20 ? (d.open(), p = new Date(a[0][0], parseInt(a[0][1]) - 1, parseInt(a[0][2]) + 1), n = p.getMonth() + 1 + "-" + p.getDate(), $(".leave_week").text(i(p)), p = p.getFullYear() + "-" + (p.getMonth() + 1) + "-" + p.getDate(), $(".choose-back-time em").html(o(p.split("-")[1]) + "-" + o(p.split("-")[2])), $("#sOutDate .choose-back-time").attr("date-time", p), void $(".hotel_total_night").text("1")) : ($(".hotel_total_night").text(l), $.cookie("comedate", r, {

                                    path: "/"

                                }), $.cookie("leavedate", p, {

                                    path: "/"

                                }), page.close())

                            },

                            rangeStyled: !0,

                            buildContent: function(e, t, a, o) {

                                var i;

                                return t ? i = -1 !== l.indexOf(t) ? "<div>" + t + "节</div>": "<div>" + t + "</div>": "<div>" + e.getDate() + "</div>"

                            }

                        }),

                        page.open("calendar")

                    })

                },

                getRoomList: function() {

                    var t, a = $("#cityId").html(),

                    o = $("#ProvinceId").val(),

                    i = $(".hotel_list_box").attr("data-hotelid"),

                    s = $(".choose-time").attr("date-time"),

                    n = $(".choose-back-time").attr("date-time"),

                    l = ($(".hotel_list_box").attr("data-url"), ""),

                    c = "",

                    r = "",

                    p = !0;

                    "395" !== a && "396" !== a && "35" !== o && (p = !0),

                    t = p ? 1 : 0,

                    $("#pageLoading").hide(),

                   /* $("#pageLoading1").show(),*/

                    $.ajax({

                        url: ajaxSetting.hotelList.url,

                        type: ajaxSetting.hotelList.type,

                        dataType: ajaxSetting.hotelList.dataType,

                        jsonp: "jsoncallback",

                        cache: !1,

                        data: {

                            plant: 7,

                            ResFormat: "json",

                            HotelId: i,

                            nt: 1,

                            comeDate: s,

                            leaveDate: n,

                            IsShowCPayment: !0,

                            IsByPromo: 1,

                            IsShowWF: 1,

                            AllianceRefid: 99295066,

                            Refid: 99295066,

                            AlliancePlatId: 651,

                            CCashBack: t,

                            pic: 1,

                            IsShowAllGuarantee: !0,

                            Random: (new Date).getTime()

                        },

                        success: function(t) {

                            $("#pageLoading1").hide();

                            var a, o;

                            if ("I0000" == t.Error.ErrorCode) {

                                $(".hotel_no").hide(),

                                $(".hotel_yo").show();

                                for (var s, n, p = s = t.HotelInfo.RoomInfo,

                                d = [], m = [], h = 0; h < p.length; h++) if (0 != p[h].PolicyInfo.length) {

                                    for (var f = 1,

                                    v = 0; v < p[h].PolicyInfo.length; v++) {

                                        var u = 0 == p[h].PolicyInfo[v].IsCanYuDing;

                                        u && (f = 0)

                                    }

                                    f ? d.push(p[h]) : m.push(p[h])

                                }

                                a = m.length - 1,

                                p = m.concat(d);

                                for (var h = 0; h < p.length; h++) {

                                    var g = p[h].RoomId;

                                    o = {

                                        hotelId: t.HotelInfo.HotelId,

                                        roomId: g,

                                        comeDate: t.HotelInfo.ComeDate,

                                        liveDate: t.HotelInfo.LeaveDate

                                    };

                                    var _ = e.roomPolicy(p[h].PolicyInfo, p[h].RoomName, o),

                                    y = _.innerHtml,

                                    b = _.price;

                                    y = 0 === h ? '<ul class="hotel_list_detail border-top" roomId=' + g + ">" + y + "</ul>": '<ul class="hotel_list_detail border-top none" roomId=' + g + ">" + y + "</ul>";

                                    var k = "",

                                    x = "";

                                    if (n = p[h].PhotoList, null != n && n.length > 0) if (n[0].indexOf("pic2") > -1) k += '<li><img src="http://img1.40017.cn/cn/sl/dzs-wxhotel/detail/noroompic.png" /></li>',

                                    x = "http://img1.40017.cn/cn/h/m/final/finalSmall.png";

                                    else {

                                        for (var w = 0; w < n.length; w++) k += '<li><img src="' + n[w] + '" /></li>';

                                        x = n[0]

                                    } else k += '<li><img src="http://img1.40017.cn/cn/sl/dzs-wxhotel/detail/noroompic.png" /></li>',

                                    x = "http://img1.40017.cn/cn/h/m/final/finalSmall.png";

                                    r = '<div class="everyRoom_policy none"><div class="policy_wrap" roomid="' + g + '"><div class="roompic_wrap"><div class="room_pic"><ul id="roomSlider' + g + '" class="slider">' + k + '</ul></div><div id="indecator' + g + '" class="table-indicator indicator clear"></div></div><p class="room_type border-bottom">' + p[h].RoomName + '</p><div class="facility_wrapper"><div id="faciWrap' + g + '" class="room_faciWrap"><div id="facility' + g + '" class="room_facility"></div></div></div><div class="room_allwrap"><p class="room_all">查看全部<span>' + p[h].PolicyInfo.length + '</span>个价格</p></div><span class="policy_close"></span></div></div>';

                                    var I = "";

                                    I = "" == p[h].Area ? "": p[h].Area.indexOf("平方米") > -1 ? p[h].Area.replace("平方米", "").trim() + 'm<sup sytle="font-size:10px;">2</sup>': p[h].Area.trim() + 'm<sup sytle="font-size:10px;">2</sup>',

                                    l = '<div class="hotel_list_title clearfix"><span id="noRoomLabel"><img src="http://img1.40017.cn/cn/train/wxhotel/no-room.png?v=1.0"></span><div class="title_main"><div class="title_pic"><img src="' + x + '"></div><div class="sub_main"><p class="first">' + p[h].RoomName + '</p><p class="two">' + ("" == p[h].Bed ? "": p[h].Bed) + '</p><p class="three">' + I + '</p></div></div><div class="title_book"><span><label>¥</label>' + b + '</span>起<em class="icon-hotel icon-bottom-arrow ' + (0 == h ? "up": "") + '"></em></div><div class="show_activebg"></div></div>' + y,

                                    l = '<div class="hotel_list border-top-bottom">' + l + r + "</div>",

                                    c += l

                                }

                                //$(".hotel_yo").html(c);

                                for (var C = $(".guarantee"), h = 0; h < C.length; h++)"到店付" === $(C[h]).text().trim() && $(C[h]).parent().css("background-color", "#ff8400");

                                e.listClicks(s),

                                e.showmore(),

                                e.policyShow(),

                                e.allPolicyShow(),

                                e.noRoomLabel(),

                                e.noteHotelId(i)

                            } else $(".hotel_yo").hide(),

                            $(".hotel_no").show();

                            $(".more-policy").bind("click",

                            function() {

                                $(".more-policy em").hasClass("up") ? ($(".policy-details").addClass("line-clamp"), $(".more-policy em").removeClass("up"), $(".more-policy span").html("展开"), $(".more-policy").addClass("border-top")) : ($(".policy-details").removeClass("line-clamp"), $(".more-policy em").addClass("up"), $(".more-policy span").html("收起"), $(".more-policy").removeClass("border-top"))

                            })

                        },

                        error: function(e) {

                            $("#pageLoading1").hide(),

                            console.log("where is data?")

                        }

                    })

                },

                listClicks: function(t) {

                    $(".room_book a").on("tap",

                    function(e) {

                        if (e.stopPropagation(), e.preventDefault(), $(this).hasClass("yd")) {

                            var t = $(this).attr("ahref");

                            location.href = t

                        }

                    }),

                    $(".hotel_list_detail li").on("tap",

                    function() {

                        $(".detail_overlys").show()/*,

                        $("#pageLoading").show()*/;

                        var a = $(this).parent("ul").attr("roomid"),

                        o = $(this).attr("room-data");

                        setTimeout(function() {

                            $(".tc").empty();

                            for (var i, s, n, c, r, p, d, m, h, f, v, u, g, _ = ($("#cityId").html(), $(".hotel_list_box").attr("data-hotelid")), y = $(".choose-time").attr("date-time"), b = $(".choose-back-time").attr("date-time"), k = "", x = "预订此产品，需预付房费##Money##，如订单不确认将全额退款至您的付款账户。订单一经确认，不可变更/取消。未如约入住，同程将此前扣除您的全额房费##Money##支付酒店。", w = 0, I = t.length; I > w; w++) {

                                s = t[w].PolicyInfo;

                                for (var C = s.length,

                                D = 0; C > D; D++) if (s[D].PolicyId == o) {

                                    i = t[w],

                                    n = s[D],

                                    c = s[D].RoomSub,

                                    r = s[D].RoomBreakfast;

                                    break

                                }

                                if (c) break

                            }

                            p = "" == i.Floor ? "无": i.Floor,

                            d = i.Area;

                            var P = r.split(";");

                            switch (m = "" == r ? "无": P[0], h = 0 == i.IsNoSmoking ? "有无烟房": 1 == i.IsNoSmoking ? "可无烟处理": 2 == i.IsNoSmoking ? "无无烟房": "无烟楼层", i.Adsl) {

                            case 5:

                                f = "免费有线宽带";

                                break;

                            case 6:

                                f = "收费有线宽带";

                                break;

                            case 7:

                                f = "免费无线宽带";

                                break;

                            case 8:

                                f = "收费无线宽带";

                                break;

                            default:

                                f = "无"

                            }

                            v = "" != i.Bed && "" != i.BedWidth ? i.Bed + "(" + i.BedWidth + ")": "" != i.Bed && "" == i.BedWidth ? i.Bed: "暂无信息",

                            c ? (u = 0 == c.AddBed ? "不可加床": "可加床", g = c.HasWindow) : u = 0 == i.AllowAddBed ? "不可加床": "可加床",

                            k = '<div class="tc_tit border-bottom"><p>' + i.RoomName + '&nbsp;<em class="font-14 tc_sub_tit text-ellipsis">' + n.PolicyName + "</em></p><span><s></s></span></div>",

                            k += '<div class="tc_cnt_box"><div class="tc_cnt"><div>',

                            k += '<div class="tc_info_box clearfix">',

                            k += '<p><s class="q-icon-hotel q-icon-floor tc_tb1"></s><span class="tc_info_tit">楼层</span><span class="tc_info_cnt format">' + p + "</span></p>",

                            k += '<p><s class="q-icon-hotel q-icon-area tc_tb2"></s><span class="tc_info_tit">面积</span><span class="tc_info_cnt format">' + (d.indexOf("平方米") > -1 ? d: d + "平方米") + "</span></p>",

                            k += '<p><s class="q-icon-hotel q-icon-rice tc_tb3"></s><span class="tc_info_tit">早餐</span><span class="tc_info_cnt format">' + m + "</span></p>",

                            k += '<p><s class="q-icon-hotel q-icon-nosmoking tc_tb4"></s><span class="tc_info_tit">无烟房</span><span class="tc_info_cnt">' + h + "</span></p>",

                            k += '<p><s class="q-icon-hotel q-icon-wifi tc_tb5"></s><span class="tc_info_tit">宽带</span><span class="tc_info_cnt1 format">' + f + "</span></p>",

                            k += '<p><s class="q-icon-hotel q-icon-bed tc_tb6"></s><span class="tc_info_tit">床型</span><span class="tc_info_cnt1 format">' + v + "</span></p>",

                            k += '<p><s class="q-icon-hotel q-icon-addbed tc_other"></s><span class="tc_info_tit">加床</span><span class="tc_info_cnt1 format">' + u + "</span></p></div>";

                            var S = "";

                            S = 0 == g ? '<p class="room-window">无窗</p>': 1 == g ? '<p class="room-window">部分有窗</p>': "",

                            k += "" == S && "" == n.PolicyRemark ? '<div class="tc_info_other1 border" style="display:none">' + S + '<p class="room-floor">' + n.PolicyRemark + "</p></div>": '<div class="tc_info_other1 border">' + S + '<p class="room-floor">' + n.PolicyRemark + "</p></div>";

                            var R = "到店现付";

                            if ("1" == n.DanbaoType) {

                                var F = "当前房间紧张，需冻结您信用卡金额##Money##用于担保。订单确认后，该酒店房间保留至2016年7月30日12:00。如订单不确认，同程将立即进行解冻。订单一经确认，不可变更/取消。未如约入住，同程将扣除您的担保费用##Money##支付酒店。成功入住及付款离店，同程向酒店确认后立即操作解冻，5-7个工作日恢复额度";

                                R = "担保",

                                null != F && (k += '<div class="flex tc_info_other"><p class="li"><span class="li_tb1">担保</span></p><p class="li_tba flex-1">' + F.replace(/##Money##/g, "") + "</p></div>")

                            } else(0 == (32 & n.FactorMark) && "1" == n.FTP || (32 & n.FactorMark) > 0) && (R = "预付", null != x && (k += '<div class="flex tc_info_other"><p class="li"><span class="li_tb2">预付</span></p><p class="li_tba flex-1">' + x.replace(/##Money##/g, "") + "</p></div>"));

                            switch ("1" == n.IsHaveGifts && (k += '<div class="flex tc_info_other"><p class="li"><span class="li_tb">礼</span></p><p class="li_1 flex-1">从' + n.GiftsBDate + " 到 " + n.GiftsEDate + "<br />" + n.GiftsDescription + "</p></div>"), k += "</div>", n.Currency.split(":")[0]) {

                            case "0":

                                k += '</div></div><div class="yuding flex flex-align-center"><p><span class="pay-type">' + R + '：</span><span class="fh">¥</span>';

                                break;

                            case "1":

                                k += '</div></div><div class="yuding flex flex-align-center"><p><span class="pay-type">' + R + '：</span><span class="fh">¥</span>';

                                break;

                            case "2":

                                k += '</div></div><div class="yuding flex flex-align-center"><p><span class="pay-type">' + R + '：</span><span class="fh">¥</span>';

                                break;

                            case "3":

                                k += '</div></div><div class="yuding flex flex-align-center"><p><span class="pay-type">' + R + '：</span><span class="fh">¥</span>';

                                break;

                            case "4":

                                k += '</div></div><div class="yuding flex flex-align-center"><p><span class="pay-type">' + R + '：</span><span class="fh">¥</span>'

                            }

                            k += '<span class="jg">' + n.AvgPriceCNY + "</span>";

                            var T = "book1.html?hotelid=" + _ + "&roomtypeid=" + a + "&policyid=" + o + "&comedate=" + y + "&leavedate=" + b;

                            k += "1" == n.DanbaoType && "0" == n.FTP ? '<a href="javascript:;" class="yd_no">预订</a>': "0" == n.IsCanYuDing && "3" != n.PolicyType && "2" != n.PolicyType ? '<a class="yd_ok" href="' + T + '">预订</a>': '<a href="javascript:;" class="yd_no">预订</a>',

                            k += "</div>",

                            $(".tc").html(k),

                            e.loadmoreFacility(a),

                            l = new IScroll(".tc_cnt", {

                                mousewheel: !0,

                                click: !0

                            }),

                            $(".tc").show(),

                            setTimeout(function() {

                                l.refresh()

                            },

                            500),

                            $("#pageLoading").hide(),

                            $(".tc_tit span").click(function() {

                                $(".tc").hide(),

                                $(".detail_overlys").hide()

                            }),

                            $(".yd_ok").on("click",

                            function() {

                                event.preventDefault();

                                var e = $(this).attr("href");

                                location.href = e

                            })

                        },

                        200)

                    })

                },

                loadmoreFacility: function(t) {

                    var a = "";

                    $.ajax({

                        type: ajaxSetting.roomFacility.type,

                        url: ajaxSetting.roomFacility.url,

                        dataType: ajaxSetting.roomFacility.dataType,

                        data: {

                            RoomId: t

                        },

                        success: function(t) {

                            if (t.length > 0) {

                                a += '<div class="more_facility_box none">';

                                for (var o = 0; o < t.length; o++) {

                                    for (var i = "",

                                    s = t[o].lstRoomEnvDescription, n = s.length, l = 0; n > l; l++) i += l == n - 1 ? s[l].RoomEnvName: s[l].RoomEnvName + "、";

                                    a += '<p><span class="tc_info_more">' + t[o].RoomEnvType + '</span><span class="tc_info_more1 format">' + i + "</span></p>"

                                }

                                a += "</div>",

                                a += '<p class="more_facility border-top" id="more_facility"><span class="more_facility_tip">查看更多房型设施</span><em class="q-icon-hotel q-icon-bottom-arrow"></em></p>',

                                $(".tc_info_box").append(a),

                                e.roomFacilityShow()

                            }

                        }

                    })

                },

                roomFacilityShow: function() {

                    $(".more_facility").on("click",

                    function() {

                        $(this).children("em").hasClass("up") ? ($(".more_facility_box").addClass("none"), $(".more_facility").addClass("border-top"), $(this).children("em").removeClass("up")) : ($(".more_facility_box").removeClass("none"), $(this).children("em").addClass("up"), $(".more_facility").removeClass("border-top")),

                        l.refresh()

                    })

                },

                showmore: function() {

                    $(".hotel_list_btm").bind("click",

                    function() {

                        $(this).parent().css({

                            height: "auto"

                        }),

                        $(this).siblings().show(),

                        $(this).remove()

                    })

                },

                policyShow: function() {

                    $(".show_activebg").on("click",

                    function() {

                        var e = $(this);

                        if ($(this).siblings(".title_book").children("em").hasClass("up")) $(this).parent().next(".hotel_list_detail").animate({

                            height: 0

                        },

                        200,

                        function() {

                            e.parent().next(".hotel_list_detail").addClass("none"),

                            e.parent().next(".hotel_list_detail").css("height", "auto")

                        }),

                        $(this).siblings(".title_book").children("em").removeClass("up");

                        else {

                            $(this).parent().next(".hotel_list_detail").removeClass("none");

                            var t = e.parent().next(".hotel_list_detail").height();

                            e.parent().next(".hotel_list_detail").height("0"),

                            e.parent().next(".hotel_list_detail").animate({

                                height: t

                            },

                            200),

                            e.siblings(".title_book").children("em").addClass("up")

                        }

                    })

                },

                getExtraPolicy: function(e, t) {

                    $.ajax({

                        type: ajaxSetting.roomFacility.type,

                        url: ajaxSetting.roomFacility.url,

                        dataType: ajaxSetting.roomFacility.dataType,

                        data: {

                            RoomId: e

                        },

                        success: function(e) {

                            var a = "";

                            if (e.length > 0) {

                                for (var o = e.length,

                                i = 0; o > i; i++) {

                                    for (var s = "",

                                    n = e[i].lstRoomEnvDescription, l = n.length, r = 0; l > r; r++) s += r == l - 1 ? n[r].RoomEnvName: n[r].RoomEnvName + "、";

                                    a += '<p><span class="faci_title">' + e[i].RoomEnvType + '</span><span class="faci_detail">' + s + "</span></p>"

                                }

                                a += '<p class="room_tip">(部分设备只有部分此房型才有)</p>'

                            } else a = '<p class="no_facility">暂无更多房型信息介绍</p>';

                            var p = "#" + t;

                            $(p).html(a);

                            var d = "#" + $(p).parent().attr("id");

                            c = new IScroll(d, {

                                mousewheel: !0,

                                click: !0

                            }),

                            setTimeout(function() {

                                c.refresh()

                            },

                            500)

                        }

                    })

                },

                allPolicyShow: function() {

                    /*$(".title_main").on("click",

                    function() {

                        var t = $(this).parent().siblings(".hotel_list_detail").attr("roomid"),

                        a = $(this).parent().siblings(".everyRoom_policy").find(".room_facility").html(),

                        o = $(this).parent().siblings(".everyRoom_policy").find(".room_facility").attr("id");

                        "" == a.trim() && e.getExtraPolicy(t, o),

                        $(this).parent().siblings(".everyRoom_policy").removeClass("none");

                        var i = $(this).parent().siblings(".everyRoom_policy").find(".slider").attr("id"),

                        s = i + "",

                        n = "#" + s,

                        l = $(n).parent().siblings(".table-indicator").attr("id"),

                        c = "#" + l;

                        "" == $(c).html() && e.sliderImage(i)

                    }),*/

                    $(".policy_close").on("click",

                    function() {

                        $(".everyRoom_policy").addClass("none")

                    }),

                    $(".room_allwrap").on("click",

                    function() {

                        var e = $(this).parent().parent().siblings(".hotel_list_detail");

                        if ($(e).hasClass("none")) {

                            $(e).removeClass("none");

                            var t = $(e).height();

                            $(e).height("0"),

                            $(e).animate({

                                height: t

                            },

                            200),

                            $(e).siblings(".hotel_list_title").children(".title_book").children("em").addClass("up")

                        }

                        $(this).parent().parent().addClass("none")

                    })

                },

                noRoomLabel: function() {

                    var e = $(".hotel_yo > div"),

                    t = e.length,

                    a = 0;

                    for (a = 0; t > a; a++) {

                        for (var o = e.eq(a).find(".hotel_list_detail").find("li"), i = o.length, s = 0, n = 0; i > n; n++) s += o.eq(n).find("#no-room").length;

                        i == s && e.eq(a).find("#noRoomLabel").show()

                    }

                },

                noteHotelId: function(e) {

                    var t = $.cookie("ids"),

                    a = [],

                    o = [];

                    t ? (t.indexOf(",") > -1 ? a = t.split(",") : (t = t + "," + e, a = t.split(",")), a.push(e), a = _.union(a), _.size(a) > 20 && a.shift(), $.cookie("ids", a.join(","))) : (o.push(e), $.cookie("ids", o.join(",")))

                },

                roomPolicy: function(e, t, a) {

                    for (var o = e,

                    i = a.hotelId,

                    s = a.roomId,

                    n = a.comeDate,

                    l = a.liveDate,

                    c = "",

                    r = {

                        2 : "预付低价",

                        4 : "今夜特惠",

                        5 : "近期特惠"

                    },

                    p = ["4", "5", "2"], d = [], m = [], h = [], f = [], v = 0, u = o.length; u > v; v++) if ("1" == o[v].DanbaoType) if ("1" == o[v].FTP) o[v].DanbaoType = 3,

                    o[v].BSI = "预订此产品，需预付房费##Money##，如订单不确认将全额退款至您的付款账户。订单一经确认，不可变更/取消。未如约入住，同程将此前扣除您的全额房费##Money##支付酒店。",

                    d.push(o[v]);

                    else if (0 == o[v].GuaranteeList.Guarantee.length) o[v].IsCanYuDing = "",

                    m.push(o[v]);

                    else {

                        var g = o[v].GuaranteeList.Guarantee[0].OverTime;

                        if (null == g || "" == g) o[v].IsCanYuDing = "",

                        m.push(o[v]);

                        else {

                            var _ = o[v].GuaranteeList.Guarantee[0].IsTomorrow,

                            y = new Date(n);

                            null == _ && "" != _ || y.setDate(y.getDay() + parseInt(_)),

                            y.setHours(parseInt(g)),

                            new Date > y ? (o[v].IsCanYuDing = "", m.push(o[v])) : (o[v].DanbaoType = "0", d.push(o[v]))

                        }

                    } else d.push(o[v]);

                    o = d.concat(m);

                    for (var v = 0; v < o.length; v++) {

                        var $ = o[v].PolicyName,

                        b = o[v].PolicyId,

                        k = 0,

                        x = "",

                        w = "",

                        I = "",

                        C = "",

                        D = "",

                        P = "",

                        S = "",

                        R = o[v].SurplusRooms;

                        if ($ || ($ = t), "1" == o[v].IsHaveGifts && (w = '<label class="gift">礼</label>', k++), (134217728 & o[v].FactorMark || (33554432 & o[v].FactorMark) > 0 || (67108864 & o[v].FactorMark) > 0) && (I = '<label class="cu">促</label>', k++), 0 == (32 & o[v].FactorMark) && "1" == o[v].FTP || (32 & o[v].FactorMark) > 0 ? (D = '<span class="guarantee">在线付</span>', k++) : (D = '<span class="guarantee">到店付</span>', k++), o[v].SpResList && o[v].SpResList.length > 0) {

                            var e = o[v].SpResList,

                            F = [];

                            for (var T in e) F.push(e[T].TypeId);

                            var L = arrExistsSameValues(p, F);

                            L.length > 0 && (S = '<label class="specialSale">' + r[L[0]] + "</label>", k++)

                        }

                        var M = "book1.html?hotelid=" + i + "&roomtypeid=" + s + "&policyid=" + b + "&comedate=" + n + "&leavedate=" + l;

                        "0" == o[v].IsCanYuDing && "3" != o[v].PolicyType && "2" != o[v].PolicyType ? (P = '<a class="yd" ahref="' + M + '"><label style="height:30px;line-height:30px;">订</label>' + D + "</a>", 1 == o.length ? h.push(o[v].AvgPriceCNY) : o[v].PolicyName.indexOf("钟点房") < 0 && h.push(o[v].AvgPriceCNY)) : (P = '<a href="javascript:;" id="no-room" class="yd_end">满房</a>', 1 == o.length ? h.push(o[v].AvgPriceCNY) : o[v].PolicyName.indexOf("钟点房") < 0 && f.push(o[v].AvgPriceCNY));

                        var j = (16777216 & o[v].FactorMark) > 0 && (2097152 & o[v].FactorMark) > 0 && "15" == o[v].IsCanYuDing || (16777216 & o[v].FactorMark) > 0 && "0" == o[v].IsCanYuDing;

                        j && (C = '<label class="confirm">立即确认</label>'),

                        c += o.length > 3 && v >= 3 ? '<li class="border-bottom fn-hide" room-data="': '<li class="border-bottom" room-data="';

                        var A, N = "",

                        B = o[v].RoomBreakfast;

                        "" == B ? N = "无": (A = B.split(";"), N = A[0]),

                        c += o[v].PolicyId + '"><div class="flex flex-align-center"><div class="room-policy flex-1"><span class="policy_break">' + N + ( - 1 === N.indexOf("早") ? "早": "") + '<em class="icon-hotel icon-right-arrow"></em></span><span class="policy_title">' + $ + '</span><span class="policy_gift">' + x + I + w + C + S + '</span></div><div class="room_price"><span class="total_price"><label>¥</label>' + o[v].AvgPriceCNY + '</span></div><div class="room_book">' + P + "</div>",

                        c += R > 0 && 5 >= R ? '<span class="room_surplus">紧张</span>': R > 5 ? '<span class="room_surplus1">剩余' + R + "间</span>": "",

                        c += "</div></li>"

                    }

                    o.length > 3 && (c += '<div class="hotel_list_btm"><span>展开全部报价<em class="icon-hotel icon-bottom-arrow"></em></span></div>');

                    var O = 0;

                    O = h.length > 0 ? Math.min.apply(Math, h) : Math.min.apply(Math, f);

                    var q = {

                        innerHtml: c,

                        price: O

                    };

                    return q

                },

                todoFavorite: function() {

                    if ("0" != $("#haslogin").val()) {

                        new Favorite($(".favorite"), {

                            url: {

                                init: ajaxSetting.favoriteInit.url,

                                toggle: ajaxSetting.favoritechange.url

                            },

                            dataType: "json"

                        },

                        function(e) {

                            console.log(e)

                        })

                    }

                },

                hotelTrackShow: function() {

                    setTimeout(function() {

                        $(".hotel_track").addClass("none")

                    },

                    2e3)

                },

                commentAndDetails: function() {

                    $(".com_detail p").on("click",

                    function() {

                        "住客点评" == $(this).text() ? ($(".comment").removeClass("none"), $(".comment_tab").addClass("active"), $(".detail_tab").removeClass("active"), $(".details").addClass("none")) : ($(".comment").addClass("none"), $(".comment_tab").removeClass("active"), $(".detail_tab").addClass("active"), $(".details").removeClass("none"))

                    })

                },

                goHref: function() {

                    var e = "",

                    t = "";

                    $(".more_comment").click(function() {

                        e = $(this).attr("href"),

                        window.location.href = e

                    }),

                    $(".address").click(function() {

                        t = $(this).attr("href"),

                        window.location.href = t

                    }),

                    $(".details_cnt").click(function() {

                        if ($("#introduce_newPage").remove(), r) {

                            var e = $("#introducePage").clone().attr("id", "introduce_newPage");

                            $("body").append(e),

                            page.open("introduce_new"),

                            $("#introduce_newPage .introduce_book a").on("click",

                            function(e) {

                                e.preventDefault(),

                                page.close()

                            })

                        } else page.open("introduce")

                    }),

                    $(".introduce_book a").on("click",

                    function(e) {

                        e.preventDefault(),

                        page.close()

                    }),

                    $(".more-facility").click(function() {

                        $(".more-facility em").hasClass("up") ? ($(".facilities").addClass("height"), $(".more-facility em").removeClass("up"), $(".more-facility span").html("更多设施")) : ($(".facilities").removeClass("height"), $(".more-facility em").addClass("up"), $(".more-facility span").html("收起设施"))

                    })

                },

                sliderImage: function(e) {

                    for (var e = e + "",

                    t = "#" + e,

                    a = $(t).find("li").length, o = $(t).parent().siblings(".table-indicator").attr("id"), i = "#" + o, n = 0; a > n; n++) a > 1 && $(i).append(["<span></span>"].join(""));

                    var l = i + " span";

                    a > 1 && s(e, l, !1)

                },

                onlineServer: function() {

                    var e = this,

                    t = $.dialog({

                        autoOpen: !1,

                        closeBtn: !1,

                        buttons: {

                            "取消": function() {

                                this.close()

                            },

                            "拨打": function() {

                                window.location.href = "tel:4002461781",

                                this.close()

                            }

                        },

                        content: '<p id="call-phone" class="local-dialog-content" style="text-align:center;">4007777777</p>'

                    });

                    $("#call-phone").parent().addClass("border-bottom").css("borderStyle", "solid"),

                    $(".scenic_recommend").on("click",

                    function() {

                        t.open(),

                        console.log(e.getBrowser())

                    })

                },

                getBrowser: function() {

                    var e = navigator.userAgent.toLowerCase(),

                    t = (e.match(/firefox|chrome|safari|opera/g) || "other")[0]; (e.match(/msie|trident/g) || [])[0] && (t = "msie");

                    var a = "",

                    o = "",

                    i = "",

                    s = "ontouchstart" in window || -1 !== e.indexOf("touch") || -1 !== e.indexOf("mobile");

                    switch (a = s ? -1 !== e.indexOf("ipad") ? "pad": -1 !== e.indexOf("mobile") ? "mobile": -1 !== e.indexOf("android") ? "androidPad": "pc": "pc", t) {

                    case "chrome":

                    case "safari":

                    case "mobile":

                        o = "webkit";

                        break;

                    case "msie":

                        o = "ms";

                        break;

                    case "firefox":

                        o = "Moz";

                        break;

                    case "opera":

                        o = "O";

                        break;

                    default:

                        o = "webkit"

                    }

                    return i = e.indexOf("android") > 0 ? "android": navigator.platform.toLowerCase(),

                    {

                        version: (e.match(/[\s\S]+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],

                        plat: i,

                        type: t,

                        pc: a,

                        prefix: o,

                        isMobile: "pc" != a

                    }

                },

                scorePercent: function() {

                    var e = "",

                    t = $(".comment_score").attr("data-score");

                    e = '<div class="circle_percent" data-grade="' + t + '"><div class="percent"><span>' + t + '</span>分</div><div class="circle"></div></div>',

                    $(".comment_score").html(e)

                },

                satisfactionInit: function() {

                    var e = $(".circle_percent"),

                    t = e.attr("data-grade") || 0;

                    return 0 == t ? (e.addClass("circle_percent_0"), void $(".percent", e).html("暂无<span>满意度</span>")) : void n(e, t)

                }

            };

            e.init()

        } ()

    }

});