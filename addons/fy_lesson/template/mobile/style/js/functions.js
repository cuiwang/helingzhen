/**
 * js函数库
 */

/**
 * 获取字符长度，中文按2个字符计算
 */
function getStrActualLen(sChars) {
    sChars = $.trim(sChars);
    var len = 0;
    for(i=0; i<sChars.length; i++){
        iCode = sChars.charCodeAt(i);
        if((iCode >= 0 && iCode <= 255)||(iCode >= 0xff61 && iCode <= 0xff9f)){
            len += 1;
        }else{
            len += 2;
        }
    }
    return len;
}
/**
 * 手机号码检测
 * @param mobile
 * @returns {Boolean}
 */
function CheckMobile(mobile){
    if(/^1[3|4|5|8][0-9]\d{8}$/.test(mobile)){
        return true;
    }else{
        return false;
    }
}

/**
 * 电话号码检测
 * @param mobile
 * @returns {Boolean}
 */
function CheckTelephone(telephone){
     var re = /^0\d{2,3}-?\d{7,8}$/;
    if(re.test(telephone)){
        return true;
    }else{
        return false;
    }
}

/**
 * 邮箱检测
 * @param Email
 * @returns {Number}
 */
function checkEmail(Email){
	var rs = 1;
    if(Email == '') {
        rs	= 2; //请填写邮箱
    } else {
    	var re	= /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{1,15}){1,5})$/;
    	if(!re.test(Email)){
            rs	= 3; //邮箱格式不对
        }
    }
    return rs;
}

/**
 * 名称相关检测
 * @param Name
 * @param Minlen
 * @param Maxlen
 * @returns {Number}
 */
function checkName(Name, Minlen, Maxlen){
	rs	= 1;
    if(Name == '') {
        rs	= 2;  //请填写名称
    } else if(getStrActualLen(Name) > Maxlen || getStrActualLen(Name) < Minlen) {
        rs	= 3; //长度不符合规范
    } else if(!/^[0-9A-Za-z\_\-\u4e00-\u9fa5]+$/.test(Name)) {
        rs	= 4; //学校名称只能包含汉字，数字，字母，下划线"_"，连接线"-"
    }
    return rs;
}

/**
 * 中文算两个字符
 * @param $Str
 */
function CutString(Str, Length){
    Str = $.trim(Str);
    var Count = 0,
        RetStr = '',
        StrArr = Str.split("");
    for(var i=0; i<Str.length; i++){
        iCode = Str.charCodeAt(i);
        if((iCode >= 0 && iCode <= 255)||(iCode >= 0xff61 && iCode <= 0xff9f)){
            Count += 1;
        }else{
            Count += 2;
        }
        if(Count <= Length){
            RetStr += StrArr[i];
        }
    }
    return RetStr;
}

/**
 * 文本框输入提示
 * elementID input元素ID
 * msg 提示消息
 * type 提示类型：notice-提示；error-错误；sucess-成功
 */
function showMessageTip(elementID, msg, type) {
    var obj = $("#form_tips_"+elementID);
    if (type == 'error') {
        $("input[name='"+elementID+"']").addClass('form_text_error');
        obj.attr("class", "fl form_error").text(msg);
    } else if (type == 'notice') {
        $("input[name='"+elementID+"']").removeClass('form_text_error');
        obj.attr("class", "fl form_notice").text(msg);
    } else if (type == 'success') {
        $("input[name='"+elementID+"']").removeClass('form_text_error');
        obj.attr("class", "fl form_success").text('');
    }
}

function registerHotKey(e){
    var evt = e ? e : (window.event ? window.event : null);
    var element = evt.srcElement || evt.target;
    if (element.nodeName != 'INPUT' && element.nodeName != 'OBJECT' && element.nodeName != 'TEXTAREA') {
        //var myKeyCode = $.browser.msie ? evt.which : evt.keyCode;
        var myKeyCode = evt.keyCode;
        if (myKeyCode >= 65 && myKeyCode <= 90 && myKeyCode != 67 && evt.shiftKey == false && evt.altKey == false && evt.ctrlKey == false) {
            window.scrollTo(0, 0);
            $("#head_searchKeywords").focus();
        }
    }
}

function getrsp() {
    var reg = new RegExp("http:\/\/www.chuanke.com\/(.*)-(.*).html");
    var r = reg.exec(window.location);
    var info = "";
    if(r != null && r != ''){
        info = '&sid='+r[1]+'&courseid='+r[2];
    }
    $.get(
        "/?mod=access&act=getrsp"+info,
        "&rand="+Math.random(),
        function(data){
            if (data.code == 0){
                //$("#rspnum").html(data.data.RspNum);
            }
        },
        "json"
    );
}

function buyCourseTip(tip) {
    var w = (parseInt($(window).width()) - 1000)/2;
    var str = '<div class="ck_tips_close" style="z-index:99;top:16px;position:absolute;right:'+w+'px;" id="buy_course_tip">';
    str += '<div class="ti_tri" style="left:70px;"></div>';
    str += '<div class="ti_con clearfix">';
    str += '<p class="fl">';
    str += '购买成功，你可以到<a href="'+KK._kkurl+'/?mod=student&act=course" style="color:#0065CB;">我的课程</a>中查看';
    str += '</p>';
    str += '<a href="javascript:;" onclick="$(\'#buy_course_tip\').remove();" class="ti_close"></a>';
    if(tip == 'nomobile'){
        str += '<p class="cb pt10">';
        str += '<img class="vm fl mr5" src="http://res.ckimg.com/sites/www/v2/images/public/ico_warning_14x14.png" alt="">';
        str += '<span class="fl lh_16">课程免费短信提醒功能 <a id="message_remind" href="javascript:;">马上开通</a></span>';
        str += '</p>';
    }
    str += '</div></div>';

    $("body").append(str);
}

function todayLiveCourseTip(num) {
    var w = (parseInt($(window).width()) - 1000)/2;
    var str = '<div class="ck_tips_close" style="z-index:99;top:16px;position:absolute;right:'+w+'px;" id="live_course_tip">';
    str += '<div class="ti_tri" style="left:70px;"></div>';
    str += '<div class="ti_con clearfix">';
    str += '<p class="fl">';
    str += '你今日有<i style="color:red;font-weight:bold;">'+num+'</i>节直播课，请到<a href="'+KK._kkurl+'/?mod=student&act=course&do=timelist" style="color:#0065CB;">直播日历</a>中查看';
    str += '</p>';
    str += '<a href="javascript:;" onclick="$(\'#live_course_tip\').remove();" class="ti_close"></a>';
    str += '</div></div>';
    $("body").append(str);
}

/**
 * 时间选择控件
 * @param obj
 * @returns
 * @author herenet
 */
var timepicker = function(obj){
    var setValue = $(obj).val(),
        timeDivSize = $("[id^='timeDiv_']").length+1;
    if(setValue != '' && /^\d{2}:\d{2}$/.test(setValue)){
        var currTime = setValue.split(":"),
            currHours = currTime[0],
            currMinute = currTime[1];
    }else{
        var currTime = new Date(),
            currHours= currTime.getHours().toString().replace(/^\d*$/, function(v){if(v.length == 1){return '0'+v;}else{return v;}}),
            currMinute = '00';
    };
    var offset = $(obj).offset(),
        left = parseInt(offset.left),
        top = parseInt(offset.top)+parseInt($(obj).height()),
        html = [
            '<div class="timeDiv" id="timeDiv_'+timeDivSize+'" style="top:'+parseInt(top+10)+'px;left:'+left+'px">',
            '<div class="oLeft">',
            '<div class="tit">',
            '<p><span class="f20 c_555 hour">'+currHours+'</span>',
            '<span class="f14 c_999">点</span></p>',
            '</div><div class="con">',
            '<ul class="oUl">',
            '<li><a href="javascript:;">00</a>',
            '<a href="javascript:;">01</a>',
            '<a href="javascript:;">02</a>',
            '<a href="javascript:;">03</a>',
            '<a href="javascript:;">04</a>',
            '<a href="javascript:;">05</a>',
            '<a href="javascript:;">06</a>',
            '<a href="javascript:;">07</a>',
            '</li><li><a href="javascript:;">08</a>',
            '<a href="javascript:;">09</a>',
            '<a href="javascript:;">10</a>',
            '<a href="javascript:;">11</a>',
            '<a href="javascript:;">12</a>',
            '<a href="javascript:;">13</a>',
            '<a href="javascript:;">14</a>',
            '<a href="javascript:;">15</a>',
            '</li><li><a href="javascript:;">16</a>',
            '<a href="javascript:;">17</a>',
            '<a href="javascript:;">18</a>',
            '<a href="javascript:;">19</a>',
            '<a href="javascript:;">20</a>',
            '<a href="javascript:;">21</a>',
            '<a href="javascript:;">22</a>',
            '<a href="javascript:;">23</a>',
            '</li></ul></div></div>',
            '<div class="oRight">',
            '<div class="tit">',
            '<p><span class="f20 c_555 minute">'+currMinute+'</span>',
            '<span class="f14 c_999">分</span></p>',
            '</div><div class="con">',
            '<ul class="oUl"><li>',
            '<a href="javascript:;">00</a>',
            '<a href="javascript:;">10</a>',
            '<a href="javascript:;">15</a>',
            '<a href="javascript:;">20</a>',
            '<a href="javascript:;">30</a>',
            '<a href="javascript:;">40</a>',
            '<a href="javascript:;">45</a>',
            '<a href="javascript:;">50</a>',
            '</li></ul></div></div>',
            '<div class="oBottom">',
            '<a href="javascript:;" class="timeSubmit">确 定</a>',
            '</div></div>',
        ].join('');
    if($("[id^='timeDiv_']").length > 0){
        return $.each($("[id^='timeDiv_']"), function(i, item){
            tempOffset = $(item).offset();
            if(tempOffset.left == left && tempOffset.top == top+10){
                //$(item).remove();
                return false;
            }
        });
    }
    $("body").append(html);
    var timeBox = $('#timeDiv_'+timeDivSize),
        isHover = false;

    $.each(timeBox.find("div.oLeft a"), function(n, item){
        if($(item).text() == currHours){
            $(this).addClass("curr");
        }
    });

    $.each(timeBox.find("div.oRight a"), function(n, item){
        if($(item).text() == currMinute){
            $(this).addClass("curr");
        }
    });

    $(obj).blur(function(){
        if(isHover == false){
            timeBox.remove();
        }
        return false;
    });

    timeBox.hover(
        function(){
            isHover = true;
            $(this).find("div.oLeft a").click(function(){
                timeBox.find("div.oLeft a").removeClass("curr");
                $(this).addClass("curr");
                timeBox.find("span.hour").text($(this).text());
                return false;
            });
            $(this).find("div.oRight a").click(function(){
                timeBox.find("div.oRight a").removeClass("curr");
                $(this).addClass("curr");
                timeBox.find("span.minute").text($(this).text());
                return false;
            });
            $(this).find("a.timeSubmit").click(function(){
                isHover = false;
                var hour = timeBox.find(".hour").text(),
                    minute = timeBox.find(".minute").text();
                $(obj).val(hour+":"+minute);
                timeBox.remove();
                return false;
            });
            return false;
        },
        function(){
            $(obj).focus();
            isHover = false;
            return false;
        }
    );
}

function str_repeat(i, m) {
    for (var o = []; m > 0; o[--m] = i);
    return o.join('');
}

function sprintf() {
    var i = 0, a, f = arguments[i++], o = [], m, p, c, x, s = '';
    while (f) {
        if (m = /^[^\x25]+/.exec(f)) {
            o.push(m[0]);
        }
        else if (m = /^\x25{2}/.exec(f)) {
            o.push('%');
        }
        else if (m = /^\x25(?:(\d+)\$)?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-fosuxX])/.exec(f)) {
            if (((a = arguments[m[1] || i++]) == null) || (a == undefined)) {
                throw('Too few arguments.');
            }
            if (/[^s]/.test(m[7]) && (typeof(a) != 'number')) {
                throw('Expecting number but found ' + typeof(a));
            }
            switch (m[7]) {
                case 'b': a = a.toString(2); break;
                case 'c': a = String.fromCharCode(a); break;
                case 'd': a = parseInt(a); break;
                case 'e': a = m[6] ? a.toExponential(m[6]) : a.toExponential(); break;
                case 'f': a = m[6] ? parseFloat(a).toFixed(m[6]) : parseFloat(a); break;
                case 'o': a = a.toString(8); break;
                case 's': a = ((a = String(a)) && m[6] ? a.substring(0, m[6]) : a); break;
                case 'u': a = Math.abs(a); break;
                case 'x': a = a.toString(16); break;
                case 'X': a = a.toString(16).toUpperCase(); break;
            }
            a = (/[def]/.test(m[7]) && m[2] && a >= 0 ? '+'+ a : a);
            c = m[3] ? m[3] == '0' ? '0' : m[3].charAt(1) : ' ';
            x = m[5] - String(a).length - s.length;
            p = m[5] ? str_repeat(c, x) : '';
            o.push(s + (m[4] ? a + p : p + a));
        }
        else {
            throw('Huh ?!');
        }
        f = f.substring(m[0].length);
    }
    return o.join('');
}


//时间格式化
Date.prototype.format = function(format) {
    var o = {
        "M+" : this.getMonth() + 1,
        "d+" : this.getDate(),
        "h+" : this.getHours(),
        "m+" : this.getMinutes(),
        "s+" : this.getSeconds(),
        "q+" : Math.floor((this.getMonth() + 3) / 3),
        "S" : this.getMilliseconds()
    };
    if (/(y+)/.test(format)){
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (var k in o){
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
};


/*
 *字符转实体
 *  */
function xssFilter(str)
{
  str = str.replace(/<br\/*>/gi,"");
  str = str.replace(/</gi,'&lt;');
  str = str.replace(/>/gi,'&gt;');

  return str;
}


//为了防止以前的代码会报错特此定义空的，如果发现都修改过了就可以直接去掉了
kkCustomerService = {};
kkCustomerService.simpleInstance = function(){};




