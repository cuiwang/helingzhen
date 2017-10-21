//右边可移动快捷按钮（电话，地址，QQ客服选一）
var mask_kf="";
var move_kf;
var issellermove = 1;
$(document).ready(function () {
    if (issellermove == 1) {
        move_kf = $('body>div:first');
        if (pagecon.sellermove != "" && Zepto) {
            var json1 = JSON.parse(pagecon.sellermove);
            if (json1 != "") {
                var json = JSON.parse(json1.floatbutton);
                var movehtml = "", movevalue = "";
                var oid = 0;
                for (var o in json) {
                    oid = o;
                    movevalue = json[o];
                    if (o == "1") movehtml = "<div id='movetel'><a href='tel:" + json[o] + "'><img src='../addons/weisrc_businesscenter/template/themes/wsj/images/move_tel.png'></a></div>";
                    else if (o == "2") movehtml = "<div id='movetel'><a href='http://wpa.qq.com/msgrd?uin=" + json[o] + "&Site=www.wshangju.com&Menu=yes'><img src='{RES}/themes/wsj/images/move_qq.png'></a></div>";
                    else if (o == "3") movehtml = "<div id='movetel'><a href='#' id='moveadd'><img src='{RES}/themes/wsj/images/move_add.png'></a></div>";
                    else if (o == 4) {
                        movevalue = "0";
                        mask_kf = "<div class='mask_kf' onclick='$(this).remove();'></div>";
                        movehtml = "<div id='movetel'><img src='{RES}/themes/wsj/images/move_kf.png' onclick=\"move_kf.append(mask_kf);\"></div>";
                    } else oid = 0;
                }
                if (oid > 0 && movevalue != "") {
                    move_kf.append(movehtml);
                    (function($) {
                        var ww = $(window).width() - 25;
                        if (isWeixin) ww = $(window).width() - 45;
                        var hh = $(window).height() / 3 * 2;
                        var $floatBtn = $('#movetel');
                        $floatBtn.css({ "left": ww, 'top': hh });
                        var posLeft, posTop, touchX, touchY, nowPosLeft, nowPosTop;

                        $floatBtn.on('touchstart', startFn);
                        $floatBtn.on('touchmove', moveFn);
                        $floatBtn.on('touchend', stopFn);

                        function startFn(e) {
                            posLeft = parseInt($floatBtn.css('left'));
                            posTop = parseInt($floatBtn.css('top'));
                            touchX = e.touches[0].pageX;
                            touchY = e.touches[0].pageY;
                        }

                        function moveFn(e) {
                            nowPosLeft = posLeft + e.touches[0].pageX - touchX;
                            nowPosTop = posTop + e.touches[0].pageY - touchY;
                            $floatBtn.css({ "left": nowPosLeft, 'top': nowPosTop });
                            return false;
                        }

                        function stopFn(e) {
                        }
                    })(Zepto);
                }
                if (o == "3") {
                    if (line_map == "")
                        $.get("/PageSection/GetSystemSectionData", { str: '%map%', sellerid: pagecon.sid }, function(dta1) {
                            if (dta1 != "-1") {
                                var d = JSON.parse(dta1);
                                line_map = getadd(d.string4, d.string3, d.string2);
                                $("#moveadd").attr("href", line_map);
                            }
                        });
                    else {
                        $("#moveadd").attr("href", line_map);
                    }
                }
            }
        }

        //    //显示留言板
        //    if (pagecon.needleaveword == '1') {
        //        
        //    }

        //显示底部导航栏
        if (pagecon.neednav == '1') {

        }

    }

});