$(document).on("click", ":radio", function() {
    $(this).parent().addClass("sel").siblings().removeClass("sel")
});
$("#set-top-table input").prop("disabled", true);
$(document).on("click", "#is_placetop_open", function() {
    var switchval = $(this).prop("checked");
    $("#set-top-table input").prop("disabled", !switchval);
    $("#set-top-table").toggle(switchval);
    if (switchval) {
        $(this).parent().addClass("sel");
        $("#allpay_platepay").hide()
    } else {
        $(this).parent().removeClass("sel");
        var _choosedType = $("#choosedType").val();
        if (!IsNullOrEmpty(_choosedType) && _choosedType != 0 && $("#ty-" + _choosedType).attr("ischarge") == 1) {
            $("#allpay_platepay").show()
        } else {
            $("#allpay_platepay").hide()
        }
    }
});
$(document).on("change", "#sel_top_articletype", function() {
    var sel_typeid = $(this).val();
    if (sel_typeid != "0") {
        selectType(sel_typeid)
    }
});
function changeMode(stype, json) {
    for (var key in json) {
        if (parseFloat(json[key]) && json[key] > 0) {
            switch (key) {
            case "hprice":
                $("#top_" + key).attr("value", json[key]).next().html("时(" + json[key] + "元)");
                break;
            case "dprice":
                $("#top_" + key).attr("value", json[key]).next().html("天(" + json[key] + "元)");
                break;
            case "mprice":
                $("#top_" + key).attr("value", json[key]).next().html("月(" + json[key] + "元)");
                break
            }
        }
    }
}
var tempdata_1 = ""
  , tempdata_2 = "";
$(document).on("click", ":radio[name='zd_mode']", function() {
    var stype = this.value;
    if (stype == 2 && $("#choosedType").val() == 0 && $("#sel_top_articletype").val() == 0) {
        alert("请选择要置顶的板块");
        $(this).parent().removeClass("sel");
        return false
    }
    $(this).parent().addClass("sel").siblings().removeClass("sel");
    if (IsNullOrEmpty(tempdata_1) || IsNullOrEmpty(tempdata_2)) {
        $.post("/msgcenter/get_chargeset_of_site", {
            siteid: $("#hidminisnsId").val(),
            stype: stype
        }, function(data) {
            if (stype == 1) {
                tempdata_1 = data
            }
            if (stype == 2) {
                tempdata_2 = data
            }
            if (data.isok) {
                changeMode(stype, data.data)
            } else {
                alert(data.msg)
            }
        })
    } else {
        switch (stype) {
        case "1":
            changeMode(stype, tempdata_1.data);
            break;
        case "2":
            changeMode(stype, tempdata_2.data);
            break
        }
    }
});
setInterval(function() {
    var choosetype = $("#choosedType").val();
    if (choosetype == "0") {
        $("#allpay_platepay").hide()
    }
    var _all_amount = $("#all_amount").val();
    var _all_fangshi = $(":radio[name='all_time']:checked");
    var _all_unitval = _all_fangshi.val();
    if (parseFloat(_all_amount) && parseFloat(_all_unitval)) {
        var cal_val = _all_amount * _all_unitval;
        $("#all_result").find("em").html(cal_val + "元");
        $("#all_hours").val(_all_amount * _all_fangshi.attr("unit"));
        $("#all_money").val(cal_val)
    } else {
        $("#all_result").find("em").html("");
        $("#all_hours").val("0");
        $("#all_money").val("0")
    }
    if (!IsNullOrEmpty(_all_fangshi)) {
        $("#all_result").find("i").html(_all_fangshi.attr("unitname"))
    }
    var _top_amount = $("#top_amount").val();
    var _top_fangshi = $(":radio[name='top_time']:checked");
    var _top_unitval = _top_fangshi.val();
    if (parseFloat(_top_amount) && parseFloat(_top_unitval)) {
        var cal_val = mul(_top_amount, _top_unitval);
        $("#top_result").find("em").html(cal_val + "元");
        $("#top_hours").val(_top_amount * _top_fangshi.attr("unit"));
        $("#top_money").val(cal_val)
    } else {
        $("#top_result").find("em").html("");
        $("#top_hours").val("0");
        $("#top_money").val("0")
    }
    if (!IsNullOrEmpty(_top_fangshi)) {
        $("#top_result").find("i").html(_top_fangshi.attr("unitname"))
    }
    $("input[type='number']").each(function() {
        this.value = this.value.replace(/\D/g, "0")
    })
}, 300);
function changeprice(obj) {
    if (obj.attr("ischarge") == "1") {
        if (!$("#is_placetop_open").prop("checked")) {
            $("#allpay_platepay").show()
        }
    } else {
        $("#allpay_platepay").hide()
    }
    $("#all_hprices").val(obj.attr("hprices")).next().html("时(" + obj.attr("hprices") + "元)");
    $("#all_dprices").val(obj.attr("dprices")).next().html("天(" + obj.attr("dprices") + "元)");
    $("#all_mprices").val(obj.attr("mprices")).next().html("月(" + obj.attr("mprices") + "元)")
}
function add(a, b) {
    var c, d, e;
    try {
        c = a.toString().split(".")[1].length
    } catch (f) {
        c = 0
    }
    try {
        d = b.toString().split(".")[1].length
    } catch (f) {
        d = 0
    }
    return e = Math.pow(10, Math.max(c, d)),
    (mul(a, e) + mul(b, e)) / e
}
function sub(a, b) {
    var c, d, e;
    try {
        c = a.toString().split(".")[1].length
    } catch (f) {
        c = 0
    }
    try {
        d = b.toString().split(".")[1].length
    } catch (f) {
        d = 0
    }
    return e = Math.pow(10, Math.max(c, d)),
    (mul(a, e) - mul(b, e)) / e
}
function mul(a, b) {
    var c = 0
      , d = a.toString()
      , e = b.toString();
    try {
        c += d.split(".")[1].length
    } catch (f) {}
    try {
        c += e.split(".")[1].length
    } catch (f) {}
    return Number(d.replace(".", "")) * Number(e.replace(".", "")) / Math.pow(10, c)
}
function div(a, b) {
    var c, d, e = 0, f = 0;
    try {
        e = a.toString().split(".")[1].length
    } catch (g) {}
    try {
        f = b.toString().split(".")[1].length
    } catch (g) {}
    return c = Number(a.toString().replace(".", "")),
    d = Number(b.toString().replace(".", "")),
    mul(c / d, Math.pow(10, f - e))
}
;