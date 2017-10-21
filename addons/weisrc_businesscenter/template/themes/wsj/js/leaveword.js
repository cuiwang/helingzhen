//页面底部留言
var li_lw;
var nowdate = new Date();
var nowdateY = nowdate.getFullYear();
var nowdateM = nowdate.getMonth();
var nowdateD = nowdate.getDate();
var leavewordflag = 0; //-1不显示，0根据页面配置，1显示
var lw_allpage = 0; //总页面数
var lw_nextpage = 0; //是否可以加载更多，1 可以
var lw_thispage = 1; //当前页码
var lw_count = 0;//总条数
$(document).ready(function () {
    if ((leavewordflag == 0 && pagecon.needleaveword == "1") || leavewordflag == 1) {
        $(".wrap").append("<div class='free-order'><textarea name='commentContent' id='commentContent'></textarea><span class='free-order-btn'><a href='javascript:void(0)' id='sendguestbook'>留言</a></span><ul class='free-order-list'></ul></div>");
        li_lw = $(".free-order-list");
        $("#sendguestbook").click(function () {
            //console.log("留言");
            var commentContent = $.trim($("#commentContent").val());
            if (commentContent.length < 8) {
                myalert("提示信息", "留言字数不能少于8个！");
                return;
            }
            $.post("/Receive/LeaveWord", { content: commentContent, pid: pagecon.pid, sid: pagecon.sid, mid: pagecon.mid }, function (dta) {
                if (dta == '1') {
                    myalert("提示信息", "留言成功！");
                    $("#commentContent").val("");
                    lw_count = 0;
                    getLeaveWord(1);
                }
                else {
                    myalert("提示信息", "留言失败！");
                    return;
                }
            });
        });
        getLeaveWord(1);
    }
});
function getLeaveWord(page) {
    lw_thispage = page;
    $.get("/PageSection/GetLeaveWord", { sellerid: pagecon.sid, pid: pagecon.pid, page: page, count: lw_count }, function (dta) {
        if (dta != "-1") {
            var json = JSON.parse(dta);
            if (page == 1) {
                lw_count = json.total;
                lw_allpage = parseInt((json.total % 10 == 0) ? (json.total / 10) : (json.total / 10 + 1));
                li_lw.find("li").remove();
            } else {
                li_lw.find("#more").remove();
            }
            $.each(json.content, function (i, o) {
                var pict = o.lwid % 62 + 1;
                if (o.mid > 0) pict = o.mid % 62 + 1;
                var html = "<li class='free-order-list-item'><div class='people'><aside class='left'>" +
                    "<img src='../images/head/head_" + pict + "-01.png' alt=''><span><h3>" + ((o.nickname) ? o.nickname : '游客') + "</h3><time>" + gettime(o.createtime) + "</time></span>" +
                    "</aside><div class='right'><i class='iconfont";
                if (o.plid != "") html += " ok";
                else html += "' lwid='" + o.lwid;
                html += "' data-after='&#xe692;' data-before='&#xe693;'></i><b>赞</b><em>" + o.praisecount + "</em></div>" +
                    "</div><p class='content'>" + o.leaveword + "</p>";
                if (o.reply != "") html += "<p class='replay'><i>商家回复：</i><em>" + o.reply + "</em></p>";
                html += "</li>";
                li_lw.append(html);
            });
            lw_nextpage = 1;

            $('.free-order-list-item .right').click(function () {
                var my = $(this);
                if (my.find('.iconfont').attr("lwid")) {
                    $.get("/PageSection/Praise", { sid: pagecon.sid, lwid: my.find('.iconfont').attr("lwid") }, function (dta1) {
                        if (dta1 == "1") {
                            my.find('.iconfont').addClass('ok');
                            my.find('em').html(parseInt(my.find('em').html()) + 1);
                        } else if (dta1 == "2") {
                            my.find('.iconfont').addClass('ok');
                            myalert("提示信息", "您已经点过赞了！");
                        }
                        else {
                            myalert("提示信息", "哎呀，您不能点赞！");
                        }
                    });
                }
                my.find('.iconfont').removeAttr("lwid");
            });
        }
        else {
            if (page == 1) {
                //$("[name=pagedata9] li").html("暂无留言！");
            }
        }
    });
}
function leavemore() {
    nowdate = new Date();
    nowdateY = nowdate.getFullYear();
    nowdateM = nowdate.getMonth();
    nowdateD = nowdate.getDate();
    getLeaveWord(thispage + 1);
}
function gettime(t) {
    var t1 = new Date(t.replace(/-/g, "/"));
    var y1=t1.getFullYear();
    var m1=t1.getMonth();
    var d1 = t1.getDate();
    if(y1==nowdateY && m1==nowdateM && d1==nowdateD) {
        return "今天 " + t1.getHours() + ":" + t1.getMinutes();
    }
    if (y1 != nowdateY) {
        return y1 + "-" + m1 + "-" + d1 + " " + t1.getHours() + ":" + t1.getMinutes();
    } else {
        return m1 + "-" + d1 + " " + t1.getHours() + ":" + t1.getMinutes();
    }
}

window.onscroll = function () {
    var body = document.body;
    // 检查滚动条是否已达到底部
    //console.log(body.scrollTop+body.clientHeight, body.scrollHeight);
    //console.log(lw_nextpage, lw_thispage, lw_allpage);
    if (lw_nextpage == 1 && (lw_thispage + 1) <= lw_allpage) {
        if (body.scrollTop + body.clientHeight == body.scrollHeight) {
            li_lw.append("<li id='more'><img src='/Template/Common/images/load.gif'/></li>");
            //alert("di bu");
            // 如果达到底部，则使用AJAX请求下一页数据
            lw_nextpage = 0;
            lw_thispage++;
            getLeaveWord(lw_thispage);
        }
    }
}