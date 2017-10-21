define(function (require, exports, module) {
    var selfModuleName = 'turntable';
    var moduleCommon = require('common');
    var fireWork = require('firework');
    var moduleID = $('[data-modulename=' + selfModuleName + ']').data('moduleid');
    var turnplate;
    var userArray = [];
    var internalFans = [];
    var scrollTimer;
    var rotationBigImg = 420;
    var prizeImgWidth = 40;
    var prizeImgPositon = -20;
    var scrollRange = 136; //头像滚动距离
    var scrollIng = false;//滚动状态
    var isGetUserFirst = false; //是否先抽人
    var PrizeId = 0; //抽中奖品ID
    var lotteryUser = {'PrizeId': PrizeId, 'FansId': 0, 'NickName': '', 'HeadPath': ''};//清除中奖用户信息
    var lotteryUserId; //内定中奖ID
    var lotteryPrizeId; //内定中奖奖品ID
    var hasInternalFans = false; //是否抽中内定中奖人
    var SubmitFansAarray = []; //中奖
    var canPlay = false;//是否启用回车键
    var allImgLength = 0;
    var firstLoad = true;
    exports.init = function () {
        $('#turntable .pointer').attr('src', '../addons/haoman_dpm/img12/turnplate-pointer.png');

            // if (moduleName == selfModuleName) {
                $('#turntable').show();
                if (firstLoad) {
                    getTurntableAward();
                }
                getTurntableInternalFans();
                getTurntableFans();
                //canPlay = true;
                document.onkeydown = function (event) {
                    if (event.keyCode == 32) {
                        if(canPlay){
                            stopPlay = true;
                            beginRotation();
                        }else{
                            //console.log(11111);
                            alert("请先抽取用户！")
                        }

                    }
                }
            // } else {
            //     canPlay = false;
            //     $('#turntable').hide();
            // }

    };
    //$('body').on('active', function () {
        $('#isLotteryHead').click(function () {
            scrollHead();
        });
        $('.turntable_submit').click(function () {
            if (!$(this).hasClass('disabled')) {
                SubmitTurntableFans();
            }
        });
    $('.reset').click(function () {

        reset_SubmitTurntableFans();

    });

 //   });
    //开始抽奖
    var beginRotation = function () {
        if (!isGetUserFirst) {
            lotteryUser = {'PrizeId': 0, 'FansId': 0, 'NickName': '', 'HeadPath': ''};//清除中奖用户信息
        }
        canPlay = false;
        submitFansState = false;
        var sum = 0; //概率总数
        var counts = 0;//奖品总数
        var rand = 0;
        var item = 0; //中奖编号索引(不是奖品ID)

        for (var i = 0; i < turnplate.probability.length; i++) {
            sum += parseInt(turnplate.probability[i]);
        }

        for (var i = 0; i < turnplate.count.length; i++) {
            counts += parseInt(turnplate.count[i]);
        }

        if (turnplate.bRotate || counts == 0) {
            moduleCommon.showInfo('奖品已经抽完了');
            canPlay = false;
            return;
        }
        //music.play();

        turnplate.bRotate = !turnplate.bRotate;
        for (var i in turnplate.probability) { //中奖概率计算
            rand = Math.random() * sum;
            //rand = rand.toFixed(1); //保留一位小数点
            if (turnplate.probability[i] >= rand && turnplate.count[i] > 0) {
                item = i * 1 + 1;
                break;
            } else {
                sum -= turnplate.probability[i];
            }
        }

        if (item == 0) { //不能满足上面的条件 就从奖品数量大于0的里面找一个
            for (var i in turnplate.count) {
                if (turnplate.count[i] > 0) {
                    item = i * 1 + 1;
                    break;
                }
            }
        }


        PrizeId = turnplate.id[item - 1]; //正常抽奖所中奖品ID
        if (hasInternalFans) { //如果有内定用户
            for (var i = 0; i < turnplate.id.length; i++) {
                if (turnplate.id[i] == lotteryPrizeId && turnplate.count[i] > 0) {
                    item = i * 1 + 1;
                    PrizeId = lotteryPrizeId;
                    break;
                }
            }
        }
        lotteryUser.PrizeId = PrizeId;
        turnplate.count[item - 1] = turnplate.count[item - 1] - 1;//中奖项数量减1
        rotateFn(item, turnplate.restaraunts[item - 1]);
    }

    //旋转转盘 item:奖品位置; txt：提示语;
    var rotateFn = function (item, txt) {
        var angles = item * (360 / turnplate.restaraunts.length) - (360 / (turnplate.restaraunts.length * 2));
        if (angles < 270) {
            angles = 270 - angles;
        } else {
            angles = 360 - angles + 270;
        }
        $('#wheelcanvas').stopRotate();
        $('#wheelcanvas').rotate({
            angle: 0,
            animateTo: angles + 1800,
            duration: 8000,
            callback: function () {
                var img = turnplate.images[item - 1];
                var name = turnplate.restaraunts[item - 1];
                showAnimate(name, img);
                isGetUserFirst = false;
                turnplate.bRotate = !turnplate.bRotate;
            }
        });
    };
    //提交中奖信息
    var SubmitTurntableFans = function () {
        moduleCommon.loading("正在提交，请稍后");
        if (SubmitFansAarray.length > 0) {
            var submitForm = $('<form/>');
            var submitCount = SubmitFansAarray.length;
            for (var i = 0; i < SubmitFansAarray.length; i++) {
                submitForm.append('<input name="' + i + '_AwardId" type="hidden" value="' + SubmitFansAarray[i].PrizeId + '" />');
                submitForm.append('<input name="' + i + '_FansId" type="hidden" value="' + SubmitFansAarray[i].FansId + '" />');
                submitForm.append('<input name="' + i + '_FansNickName" type="hidden" value="' + SubmitFansAarray[i].NickName + '" />');
                submitForm.append('<input name="' + i + '_FansHead" type="hidden" value="' + SubmitFansAarray[i].HeadPath + '" />');
            }
            submitForm.append('<input name="size" type="hidden" value="'+submitCount+'" />');

            $.extendPost(go_SubmitTurntableFans, submitForm.serializeArray(), "json", function (data) {
                moduleCommon.loaded();
                if (data.ResultType == 1) {
                    moduleCommon.showInfo("提交成功", 1);
                    $(".turntable_submit").addClass("disabled");
                    SubmitFansAarray = [];
                } else {
                    moduleCommon.showInfo(data.Message);
                }
            });
        }
    }

    var reset_SubmitTurntableFans = function () {
        var resetAllOk = confirm("您确定要重置所有奖项，之前所有中奖记录将清空！")
        if (resetAllOk == true){

            $.ajax({
                url: go_resetSubmitTurntableFans,
                dataType: "json",
                data: {

                },
                beforeSend: function () {

                },
                success: function (data) {
                    if(data.ret == 1){
                        $("#winUserList").html('');
                        $(".winUserNum").html('0');
                        alert("重置成功，请刷新页面后重新抽奖了。")
                    }else{
                        alert("重置失败，请刷新页面重试。")
                    }
                },
                error: function () {
                    alert("网络太慢了，重置失败！");
                },
                timeout: 15000
            })


        }
    }
    //显示动画
    var showAnimate = function (name, img) {
        fireWork.show();
        var str = '';
        var html = '';
        var t = $(".winUserList li").length;
        var sort = t + 1;

        if (lotteryUser.NickName != '') {
            str = '恭喜&nbsp;&nbsp;&nbsp;' + lotteryUser.NickName + '&nbsp;&nbsp;&nbsp;获得<br/>'
        }

        str = str + name;
        $('body').append('<div class="animage-bg"><div class="light"></div><div id="turntablePrize">' + str + '<img id="rotationBigImg" src="' + img + '" /><a class="clickBtn closeAnimate">确认</a></div></div>');
        //动画进行时的动作
        $('#isLotteryHead').html('');
        $('#rotationUserHead').css('opacity', 0);
        isGetUserFirst = false;
        $("#turntableFlash").css('opacity', 1).show();

        html += '<li class="clearfix" data-id="'+lotteryUser.FansId+'" data-rank="1"><p class="head-part left"> <span class="num-p winUserRankNum"><em>'+sort+'</em></span><a href="javascript:;"><img style="margin-left: 5px;margin-top: 5px;" width="50" height="50" src="'+lotteryUser.HeadPath+'" alt=""></a></p> <a href="javascript:;" class="nick-name left winUserName">'+lotteryUser.NickName+'</a> <a href="javascript:void(0);" class="delfans del delWinUser" data-id="'+lotteryUser.id+'">×</a> </li>';
        $("#winUserList").append(html);
        $(".winUserNum").html(sort);
        SubmitFansAarray.push(lotteryUser);
        $(".turntable_submit").removeClass("disabled");
        $('#rotationBigImg').animate({'height': rotationBigImg + 'px'}, 'fast');
        $('.closeAnimate').click(function () {
            fireWork.hide();
            $('.animage-bg').animate({'opacity': 0}, function () {
                $(this).remove();
            });
            canPlay = false;
        });
        canPlay = false;
        removeUser();
    }

    //获取用户
    var getTurntableFans = function () {
        $('#isLotteryHead').html('');
        $('#rotationUserHead ul').html('').css('top', 0).hide();
        $.extendGetJSON(go_GetTurntableFans, {}, function (data) {
            // console.log(data);
            if (data.length > 0) {
                userArray = eval(data);

                $(data).each(function (index, element) {
                    $('#rotationUserHead ul').append('<li data-headpath="' + element.avatar + '" data-userid="' + element.id + '" data-nickname="' + element.nickname + '" ><img onError="imgError(this)" src="' + element.avatar + '"/><span>' + element.nickname + '</span></li>');

                });
            }
        });
    };
    //获取内定用户
    var getTurntableInternalFans = function () {
        $.extendGetJSON(go_GetTurntableInternalFans, {}, function (data) {
            if (data.length > 0) {
                internalFans = eval(data);

            }
        });
    };
    //获取奖品信息
    var getTurntableAward = function () {
        moduleCommon.loading('数据加载中,请稍后');
        turnplate = {
            restaraunts: [],				//大转盘奖品名称
            colors: [],					//大转盘奖品区块对应背景颜色
            probability: [],             //概率
            count: [],                  //数量
            images: [],                  //图片
            id: [],	                    //奖品ID
            outsideRadius: 282,			//大转盘外圆的半径
            textRadius: 255,				//大转盘奖品位置距离圆心的距离
            insideRadius: 0,			//大转盘内圆的半径
            startAngle: 0,				//开始角度
            bRotate: false,				//false:停止;ture:旋转
            getData: function () {
                $.extendGetJSON(go_GetTurntableAward, {}, function (data) {
                    if (data.length > 0) {
                        if (data.length <= 8) {
                            prizeImgWidth = 80;
                            prizeImgPositon = -40
                        }
                        allImgLength = data.length;
                        var loaded = 0;
                        $(data).each(function (index, element) {
                            turnplate.restaraunts.push(element.prizename);
                            turnplate.id.push(element.id);
                            turnplate.images.push(element.awardsimg);
                            turnplate.probability.push(element.awardspro);
                            turnplate.count.push(element.awardstotal-element.prizedraw);
                            if (index % 2 == 0) {
                                turnplate.colors.push('#ffbf29');
                            } else {
                                turnplate.colors.push('#ffdf3e');
                            }
                            $('#turntable').append('<img onerror="imgError(this)" src="' + element.awardsimg + '" id="img_' + index + '" style="display:none;"/>');
                            var img = document.getElementById('img_' + index);
                            img.onload = function () {
                                loaded++;
                                if (loaded == allImgLength) {
                                    drawRouletteWheel();
                                    firstLoad = false;
                                    moduleCommon.loaded();
                                }
                            };
                        });
                    }
                    else{
                       alert("未获取到奖品，请先添加奖品");

                    }
                    $('.pointer').show();
                });
            }
        };
        turnplate.getData();
    }

    var drawRouletteWheel = function () {
        var canvas = document.getElementById("wheelcanvas");
        if (canvas.getContext) {
            //根据奖品个数计算圆周角度
            var arc = Math.PI / (turnplate.restaraunts.length / 2);
            var ctx = canvas.getContext("2d");
            //在给定矩形内清空一个矩形
            ctx.clearRect(0, 0, 700, 700);
            //strokeStyle 属性设置或返回用于笔触的颜色、渐变或模式
            ctx.strokeStyle = "#000";
            //font 属性设置或返回画布上文本内容的当前字体属性
            ctx.font = '24px Microsoft YaHei';
            for (var i = 0; i < turnplate.restaraunts.length; i++) {
                var angle = turnplate.startAngle + i * arc;
                ctx.fillStyle = turnplate.colors[i];
                ctx.beginPath();
                //arc(x,y,r,起始角,结束角,绘制方向) 方法创建弧/曲线（用于创建圆或部分圆）
                ctx.arc(350, 350, turnplate.outsideRadius, angle, angle + arc, false);
                ctx.arc(350, 350, turnplate.insideRadius, angle + arc, angle, true);
                ctx.stroke();
                ctx.fill();
                //锁画布(为了保存之前的画布状态)
                ctx.save();

                //----绘制奖品开始----
                ctx.fillStyle = "#E5302F";
                var text = turnplate.restaraunts[i];
                var line_height = 25;
                //translate方法重新映射画布上的 (0,0) 位置
                ctx.translate(350 + Math.cos(angle + arc / 2) * turnplate.textRadius, 350 + Math.sin(angle + arc / 2) * turnplate.textRadius);

                //rotate方法旋转当前的绘图
                ctx.rotate(angle + arc / 2 + Math.PI / 2);

                /** 下面代码根据奖品类型、奖品名称长度渲染不同效果，如字体、颜色、图片效果。(具体根据实际情况改变) **/
                ctx.strokeText(text,-36, 110)
                //添加对应图标
                var img = document.getElementById('img_' + i);
                ctx.drawImage(img, prizeImgPositon, -10, prizeImgWidth, prizeImgWidth);
                //把当前画布返回（调整）到上一个save()状态之前
                ctx.restore();
                //----绘制奖品结束----
            }
        }
    }
    //抽人
    var scrollHead = function () {
        if (!scrollIng) {
            $('#rotationUserHead').css('opacity', 1);
            $('#isLotteryHead').html('').hide();
            removeUser();
            scrollIng = true;
            //canPlay = true;
            hasInternalFans = false;
            isGetUserFirst = true;
            if (internalFans.length > 0) {
                var rand = Math.round(Math.random() * (internalFans.length - 1));
                lotteryUserId = internalFans[rand].fansid;	//在应中奖用户中随机抽取一个
                //lotteryPrizeId = internalFans[rand].AwardIds[Math.round(Math.random() * (internalFans[rand].AwardIds.length - 1))]; //随机找一个内定奖品开始抽奖的时候要用
                lotteryPrizeId = internalFans[rand].prizeid //随机找一个内定奖品开始抽奖的时候要用
                internalFans.splice(rand, 1);
                for (var i = 0; i < userArray.length; i++) {
                    if (userArray[i].id == lotteryUserId) {
                        $('#isLotteryHead').html('<img data-headpath="' + userArray[i].avatar + '" data-userid="' + userArray[i].id + '" data-nickname="' + userArray[i].nickname + '" onError="imgError(this)" src="' + userArray[i].avatar + '"/><span>' + userArray[i].nickname + '</span>');
                        hasInternalFans = true;
                        break;
                    }
                }
            }
            var ul = $('#rotationUserHead ul');
            ul.show();
            scrollTimer = setInterval(function () {
                ul.css({top: -scrollRange});
                scrollRange = scrollRange + 136;
                if (scrollRange >= ul.height() + 136) {
                    ul.css("top", 0);
                    scrollRange = 136;
                }
            }, 50);
            setTimeout(function () {
                var isLotteryLi; //中奖用户所在标签
                $('#isLotteryHead').show();
                if (hasInternalFans) {
                    isLotteryLi = $('#isLotteryHead').find('img'); //中奖用户名所在标签
                } else {
                    isLotteryLi = ul.find('li').eq((scrollRange - 136) / 136); //中奖用户名所在标签
                }

                lotteryUser = {
                    'PrizeId': PrizeId,
                    'FansId': isLotteryLi.data('userid'),
                    'NickName': isLotteryLi.data('nickname'),
                    'HeadPath': isLotteryLi.data('headpath')
                };//中奖用户信息

                if (ul.find('li').length <= 0) {
                    canPlay = false;
                    lotteryUser = {'PrizeId': 0, 'FansId': 0, 'NickName': '', 'HeadPath': ''};//清除中奖用户信息
                    alert("未获取到参与粉丝数据！")
                }
                canPlay = true;

                clearInterval(scrollTimer);
                scrollIng = false;
            }, 1000);
        }
    }

    //移除已中奖人
    var removeUser = function () {
        if (lotteryUser != '') {
            $('#rotationUserHead ul li[data-userid=' + lotteryUser.FansId + ']').remove();
            for (var i = 0; i < userArray.length; i++) {
                if (userArray[i].id == lotteryUser.FansId) {
                    userArray.splice(i, 1);
                    break;
                }
            }
            if (internalFans.length > 0) {
                for (var i = 0; i < internalFans.length; i++) {
                    if (internalFans[i].fansid == lotteryUser.FansId) {
                        internalFans.splice(i, 1);
                        break;
                    }
                }
            }
        }
        lotteryUser = {'PrizeId': 0, 'FansId': 0, 'NickName': '', 'HeadPath': ''};//清除中奖用户信息
    }

    imgError = function (v) {
        $(v).attr('src', '../addons/haoman_dpm/img12/noheader.jpg');
    };
});
