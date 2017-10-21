define(function (require, exports, module) {
    var moduleCommon = require('common');
    var selfModuleName = 'pair';
    var pairTimer_left;
    var pairTimer_right;
    var leftUl;
    var rightUl;
    var pairRange_left = 0;
    var pairRange_right = 0;
    var leftIndex;
    var rightIndex;
    var pairNumber;
    var autoPair = false;
    var selectNumber;

    exports.init = function () {

        $('#pair .rightUser img,#pair .leftUser img').attr('src','../addons/haoman_dpm/img12/default_pair.jpg');

      //  $('body').on('active', function () {
        $(".select").click(function (event) {
            event.stopPropagation();
            if (!$(this).parent().hasClass('disabled')) {
                $(".select_option").slideUp();
                if ($(this).next(".select_option").css("display") == "none") {
                    $(this).next(".select_option").css({
                        left: $(this).position().left,
                        top: $(this).position().top + 35
                    });
                    $(this).next(".select_option").slideDown("fast");
                }
                else {
                    $(this).next(".select_option").slideUp();
                }
            }
            $(document).bind("click", function () {
                $(".select_option").slideUp();
            });
        });

            $('#pair .leftUser img').css('border', '3px solid #06407c');
            $('#pariNum a').click(function () {
                $(this).parent().prev(".select").find("a").attr({ "data-pairnum": $(this).data("pairnum") });
                $(this).parent().prev(".select").find("a").html($(this).html());
            });
            $('#beginPair').click(function () {


                isAuto();
            });
            $('#stopPair').click(function () {
                stopPair();
            });
            $('#removePair').click(function () {
                removePair();
            });
            $('#submitPair').click(function () {
                SubmitPairFans();
            });
            $('#pairUl').on('click', 'a', function () {
                deletePair($(this));
            });
        $('#deleteALL').on('click', function () {
                deleteALL_Pair();
            });
     //   });
       // $('body').on('modulechange', function (e, moduleName) {
       //      if (moduleName == selfModuleName) {
       //          $('#pair').show();
                GetPairFans();
                 GetPairs();
            // } else {
            //     $('#pair').hide();
            // }
       // });
    };

    //获取已配对的人
    var GetPairs = function () {
        $('#pairUl').html('');
        $.extendGetJSON(go_GetPairs, {}, function (data) {

            if (data.length > 0) {
                $(data).each(function (index, element) {
                    $("#pairUl").prepend('<li data-pair="1"><span></span><div><img onerror="imgError(this);" src="' + element.one_avatar + '"><font>' + element.one_nickname + '</font></div><div><img onerror="imgError(this);" src="' + element.other_avatar + '"><font>' + element.other_nickname + '</font></div><i class="hasSubmit">已提交</i></li>');
                });
                $("#pairUl li").each(function (index, element) {
                    $(this).find("span").html($("#pairUl li").length - $(this).index());
                });
            }

        }, function () {
            moduleCommon.showInfo("加载失败,请重试!!");
            moduleCommon.loaded();
        });
    }

    //获取对对碰数据
    var GetPairFans = function () {
        moduleCommon.loading("数据加载中,请稍后");
        var leftUser = $(".leftUser ul");
        var rightUser = $(".rightUser ul");
        leftUser.html('').hide();
        rightUser.html('').hide();
        $.extendGetJSON(go_GetPairFans, {}, function (data) {

            if (data.length > 0) {
                $(data).each(function (index, element) {
                    var str = '<li data-userid="' + element.id + '" data-headpath="' + element.avatar + '" data-openid="' + element.from_user + '"><img onerror="imgError(this);" src="' + element.avatar + '"><span>' + element.nickname + '</span></li>';
                    if (element.sex % 2 == 0) {
                        leftUser.append(str);
                    } else {
                        rightUser.append(str);
                    }
                });
                $("#defaultFemale").html("共" + leftUser.find("li").length + "位");
                $("#defaultMale").html("共" + rightUser.find("li").length + "位");
                if (leftUser.find("li").length>0){
                    leftUser.show().next().remove();
                }
                if (rightUser.find("li").length>0){
                    rightUser.show().next().remove();
                }
            }
            moduleCommon.loaded();
        }, function () {
            leftUser.html("");
            rightUser.html("");
            moduleCommon.showInfo("加载失败,请重试!");
            moduleCommon.loaded();
        });
    }

    var isAuto = function () {
        leftUl = $(".leftUser ul");
        rightUl = $(".rightUser ul");
        pairNumber = $("#pair_count").find("a").attr("data-pairnum");
        pairNumber > 1 ? autoPair = true : autoPair = false;
        pairNumber > 1 ? selectNumber = pairNumber : selectNumber = 1;
        if (leftUl.find("li").length < 1 || rightUl.find("li").length < 1) {
            moduleCommon.showInfo("抱歉,人数不够");
            return false;
        }
        if (leftUl.find("li").length < pairNumber || rightUl.find("li").length < pairNumber) {
            moduleCommon.showInfo("抱歉,人数不够!");
            return false;
        }
        beginPair();
    }
    var beginPair = function () {

        $(".leftUser div").remove();
        $(".rightUser div").remove();
        leftUl.css({ "width": $(".leftUser").find("li").length * 200 });
        rightUl.css({ "width": $(".rightUser").find("li").length * 200 });
        leftUl.show();
        rightUl.show();
        if (autoPair) {
            $("#pairIng").css("display", "block");
            $("#pairIng span").html(selectNumber);
            setTimeout(function () {
                stopPair();
            }, Math.floor(Math.random() * 5 + 1) * 1000);
        }

        pairTimer_left = setInterval(function () {
            leftUl.css({ left: -pairRange_left });
            pairRange_left = pairRange_left + 200;
            if (pairRange_left >= $(".leftUser li").length * 200) {
                pairRange_left = 0;
            }
        }, 50);
        pairTimer_right = setInterval(function () {
            rightUl.css({ left: -pairRange_right });
            pairRange_right = pairRange_right + 200;
            if (pairRange_right >= $(".rightUser li").length * 200) {
                pairRange_right = 0;
            }
        }, 50);
        $("#beginPair").hide();
        $("#stopPair").show();
    }

    var stopPair = function () {
        $("#beginPair").show();
        $("#stopPair").hide();
        $("#removePair").removeClass("gray");
        $("#submitPair").removeClass("gray");
        clearInterval(pairTimer_left);
        clearInterval(pairTimer_right);
        leftIndex = parseInt($(".leftUser ul").css("left")) / 200 * -1;
        var OneFansId = $(".leftUser li").eq(leftIndex).data("userid");
        var OneFansHead = $(".leftUser li").eq(leftIndex).find("img").attr("src");
        var OneFansHeadPath = $(".leftUser li").eq(leftIndex).data("headpath");
        var OneFansNickName = $(".leftUser li").eq(leftIndex).find("span").html();
        rightIndex = parseInt($(".rightUser ul").css("left")) / 200 * -1;
        var AnotherFansId = $(".rightUser li").eq(rightIndex).data("userid");
        var AnotherFansHead = $(".rightUser li").eq(rightIndex).find("img").attr("src");
        var AnotherFansHeadPath = $(".rightUser li").eq(rightIndex).data("headpath");
        var AnotherFansNickName = $(".rightUser li").eq(rightIndex).find("span").html();
        //添加配对人
        $("#pairUl").prepend('<li data-onefansid="' + OneFansId + '" data-onefansheadpath="' + OneFansHeadPath + '" data-onefansnickname="' + OneFansNickName + '" data-anotherfansid="' + AnotherFansId + '" data-anotherfansheadpath="' + AnotherFansHeadPath + '" data-anotherfansnickname="' + AnotherFansNickName + '" ><span></span><a>x</a><div><img src="' + OneFansHead + '"><font>' + OneFansNickName + '</font></div><div><img  src="' + AnotherFansHead + '"><font>' + AnotherFansNickName + '</font></div></li>');
        $("#pairNumber").html($("#pairUl li").length);
        $("#pairUl li").each(function (index, element) {
            $(this).find("span").html($("#pairUl li").length - $(this).index());
        });

        showPairAnimate(OneFansHead, OneFansNickName, AnotherFansHead, AnotherFansNickName);
    }

    imgError = function (v) {
        $(v).attr('src', '../addons/haoman_dpm/img12/noheader.jpg');
    };
    //删除对对碰
    var deletePair = function (v) {
        var $this = v;
        $(".leftUser div").remove();
        $(".rightUser div").remove();
        if (!$this.parent().attr("data-pair")) {
            $(".leftUser ul").append('<li data-userid="' + $this.parent().data("onefansid") + '" data-headpath="' + $this.parent().data("onefansheadpath") + '"><img src="' + $this.parent().find("div").eq(0).find("img").attr("src") + '"><br><span>' + $this.parent().find("div").eq(0).find("font").html() + '</span></li>');
            $(".rightUser ul").append('<li data-userid="' + $this.parent().data("anotherfansid") + '" data-headpath="' + $this.parent().data("anotherfansheadpath") + '"><img src="' + $this.parent().find("div").eq(1).find("img").attr("src") + '"><br><span>' + $this.parent().find("div").eq(1).find("font").html() + '</span></li>');
            $this.parent().remove();
        }
        $("#pairUl li").each(function (index, element) {
            $(this).find("span").html($("#pairUl li").length - $(this).index());
        });
        if ($("#pairUl li").length == 0) {
            $("#removePair").addClass("gray");
            $("#submitPair").addClass("gray");
        }
    }

    //删除所有对对碰
    var deleteALL_Pair = function () {
        moduleCommon.loading("正在删除，请稍后");
        if (!$("#removePair").hasClass("gray")) {
            $("#removePair").addClass("gray");
            $("#submitPair").addClass("gray");
            $(".leftUser div").remove();
            $(".rightUser div").remove();
            $("#pairUl li[data-pair!=1]").each(function (index, element) {
                $(".leftUser ul").append('<li data-userid="' + $(this).data("onefansid") + '" data-headpath="' + $(this).data("onefansheadpath") + '"><img src="' + $(this).find("div").eq(0).find("img").attr("src") + '"><br><span>' + $(this).find("div").eq(0).find("font").html() + '</span></li>');
                $(".rightUser ul").append('<li data-userid="' + $(this).data("anotherfansid") + '" data-headpath="' + $(this).data("anotherfansheadpath") + '"><img src="' + $(this).find("div").eq(1).find("img").attr("src") + '"><br><span>' + $(this).find("div").eq(1).find("font").html() + '</span></li>');
            });
            $("#pairUl").find("li").remove();
            // $("#pairUl").mCustomScrollbar("update");
            $("#pairNumber").html(0);
        }
        $.extendPost(go_deleteALL_Pair,{}, "json", function (data) {
            moduleCommon.loaded();

            if (data.ResultType == 1) {
                moduleCommon.showInfo("删除成功", 1);
            } else {
                moduleCommon.showInfo(data.msg);
            }
        });
    }
    //重新配对
    function removePair() {
        if (!$("#removePair").hasClass("gray")) {
            $("#removePair").addClass("gray");
            $("#submitPair").addClass("gray");
            $(".leftUser div").remove();
            $(".rightUser div").remove();
            $("#pairUl li[data-pair!=1]").each(function (index, element) {
                $(".leftUser ul").append('<li data-userid="' + $(this).data("onefansid") + '" data-headpath="' + $(this).data("onefansheadpath") + '"><img src="' + $(this).find("div").eq(0).find("img").attr("src") + '"><br><span>' + $(this).find("div").eq(0).find("font").html() + '</span></li>');
                $(".rightUser ul").append('<li data-userid="' + $(this).data("anotherfansid") + '" data-headpath="' + $(this).data("anotherfansheadpath") + '"><img src="' + $(this).find("div").eq(1).find("img").attr("src") + '"><br><span>' + $(this).find("div").eq(1).find("font").html() + '</span></li>');
            });
            $("#pairUl").find("li").remove();
            // $("#pairUl").mCustomScrollbar("update");
            $("#pairNumber").html(0);
        }

    }

    //提交对对碰名单
    var SubmitPairFans = function (v) {
        var submitCount = $("#pairUl li[data-pair!=1]").size();
        if (!$("#submitPair").hasClass("gray") && submitCount > 0) {
            moduleCommon.loading("正在提交，请稍后");
            var submitForm = $('<form/>');
            $("#pairUl li[data-pair!=1]").each(function (index, element) {
                submitForm.append('<input name="' + index + '_OneFansId" type="hidden" value="' + $(element).data('onefansid') + '" />');
                submitForm.append('<input name="' + index + '_OneNickName" type="hidden" value="' + $(element).data('onefansnickname') + '" />');
                submitForm.append('<input name="' + index + '_OneHead" type="hidden" value="' + $(element).data('onefansheadpath') + '" />');
                submitForm.append('<input name="' + index + '_AnotherFansId" type="hidden" value="' + $(element).data('anotherfansid') + '" />');
                submitForm.append('<input name="' + index + '_AnotherNickName" type="hidden" value="' + $(element).data('anotherfansnickname') + '" />');
                submitForm.append('<input name="' + index + '_AnotherHead" type="hidden" value="' + $(element).data('anotherfansheadpath') + '" />');
            });
            submitForm.append('<input name="isPeng" type="hidden" value="false" />');
            submitForm.append('<input name="size" type="hidden" value="'+submitCount+'" />');
            //console.log(submitForm.serializeArray());
            $.extendPost(go_SubmitPairs,submitForm.serializeArray(), "json", function (data) {
                moduleCommon.loaded();

                if (data.ResultType == 1) {

                    moduleCommon.showInfo("提交成功", 1);
                    $("#removePair").addClass("gray");
                    $("#submitPair").addClass("gray");

                    $("#pairUl li[data-pair!=1]").attr('data-pair', 1);
                } else {
                    moduleCommon.showInfo(data.msg);
                }
            });
        }
    }
    //显示配对动画
    var showPairAnimate = function (OneFansHead, OneFansNickName, AnotherFansHead, AnotherFansNickName) {
        $("body").append('<div class="blackbg"></div><div class="pairLeft"><img src=' + OneFansHead + '></div><div class="pairRight"><img src=' + AnotherFansHead + '></div><div class="pairAnimateBg1"></div>');
        $(".pairAnimateBg1").animate({ "margin-left": "-500px", "margin-top": "-250px",  "opacity": "1" }, function () {
            $(".pairgif").animate({ "opacity": "1" });
            $(".pairLeft").show().animate({ "margin-left": "-700px" }, "slow", function () {
                $(".pairLeft").css("z-index", "99999").animate({ "margin-left": "-185px" }, "fast");
            });
            $(".pairRight").show().animate({ "margin-left": "600px" }, "slow", function () {
                $(".pairRight").css("z-index", "99999").animate({ "margin-left": "45px" }, "fast");
            });
        });
        $("#flash").css("opacity", "1").show();
        setTimeout(function () {
            //移除已配对的人
            leftUl.find("li").eq(leftIndex).remove();
            rightUl.find("li").eq(rightIndex).remove();
            if (leftUl.find('li').size() == 0) {
                leftUl.parent().append('<div><img src="../addons/haoman_dpm/img12/default_pair.jpg"><br><span id="defaultFemale">没人了</span></div>');
            }
            if (rightUl.find('li').size() == 0) {
                rightUl.parent().append('<div><img src="../addons/haoman_dpm/img12/default_pair.jpg"><br><span id="defaultMale">没人了</span></div>');
            }
            if (selectNumber > 1) {
                selectNumber--;
                beginPair(); //重新抽奖
            } else {
                $("#pairIng").css("display", "none");
                leftUl.css({ "width": $(".leftUser").find("li").length * 200, left: Math.min(-pairRange_left + 200, 0) });
                rightUl.css({ "width": $(".rightUser").find("li").length * 200, left: Math.min(-pairRange_right + 200, 0) });
            }
            $(".pairLeft").animate({ "margin-left": "-1080px", "opacity": "0" }, "fast", function () {
                $(".pairLeft").remove();
            });
            $(".pairRight").animate({ "margin-left": "1080px", "opacity": "0" }, "fast", function () {
                $(".pairRight").remove();
            });
            $(".pairgif").animate({ "opacity": "0" }, function () {
                $(".pairgif").remove()
            });
            $(".pairAnimateBg1").animate({ "opacity": "0" }, function () {
                $(".pairAnimateBg1").remove()
            });
            $(".blackbg").animate({ "opacity": "0" }, "slow", function () {
                $(".blackbg").remove();
            });
            $("#flash").animate({ "opacity": "0" }, "slow", function () {
                $("#flash").hide();
            });
        }, 3000);
    }

});