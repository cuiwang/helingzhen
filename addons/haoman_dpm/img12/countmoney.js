define(function (require, exports, module) {
    var selfModuleName = 'countmoney';
    var moduleCommon = require('common');
    var fireWork = require('firework');
    var countId = 0;
    var getReadyCount = 100;
    var perSecondTimes = 10;
    var maxTimes;
    var getCountMoneyReady;
    var startCountMoneyTimer;
    var leftTime;
    var top0Top=270;
    var top1Left = -337;
    var top2Left = 160;
    var moneyHeight=320;
    var moneyWidth=120;

    exports.init = function () {
        $('body').on('active', function () {
            $('#countmoney .money-send').on('click', function () {
                pushMoney();
            });
            $('#countmoney .money-begin').on('click', function () {
                beginMoney();
            });
        });

        $('body').on('modulechange', function (e, moduleName) {
            if (moduleName == selfModuleName) {
                $('#countmoney').show();
            } else {
                $('#countmoney').hide();
            }
        });
    };
    var pushMoney = function () {
        //$('#shake #readyShake').off('click');
        $('.money-index').hide();
        $('.money-join').show().find('ul').empty();
        moduleCommon.loading('数据正在准备中，请稍后');
        $.extendPost(moduleCommon.httpURL + $('#PushCountMoney').val(), {}, 'json', function (data) {
            moduleCommon.loaded();
            if (data.ResultType != 1) {
                moduleCommon.showInfo(data.Message);
                return;
            }
            countId = data.AppendData.Id;
            leftTime = parseInt(data.AppendData.Duration);
            maxTimes = data.AppendData.Duration * perSecondTimes;
            getCountMoneyReady = setInterval(function () {
                $.extendGetJSON(moduleCommon.httpURL + $('#GetCountMoneyJoinFans').val(), {
                    'countId': countId,
                    'count': getReadyCount
                }, function (data) {
                    $(data).each(function (index, element) {
                        if ($('#countmoney .money-join li[data-id="' + element.Id + '"]').size() < 1) {
                            $('#countmoney .money-join ul').append('<li data-id="' + element.Id + '"><img src="' + element.Head + '"><span>' + element.NickName + '</span></li>');
                        }
                    });
                });
            }, 2000);
        }, function () {
            moduleCommon.loaded();
            moduleCommon.showInfo('网络繁忙，请稍后重试');
        });
    }


    var beginMoney = function () {
        $('.money-join').hide();
        $('.money-ing').show();
        clearInterval(getCountMoneyReady);
        var i = 2;
        var CountMoneyTimes = $('#countmoney .shakeTimes');
        CountMoneyTimes.show().text(3).animate({'opacity': 0}, 'slow');
        startCountMoneyTimer = setInterval(function () {
            if (i > 0) {
                CountMoneyTimes.text(i).css('opacity', 1).animate({'opacity': 0}, 'slow');
            }
            else {
                clearInterval(startCountMoneyTimer);
                CountMoneyTimes.text('GO').css('opacity', 1).animate({'opacity': 0}, 1000, function () {
                    $.extendPost(moduleCommon.httpURL + $('#OpenCountMoney').val(), {'countId': countId}, 'json', function (data) {
                        if (data.ResultType != 1) {
                            moduleCommon.showInfo(data.Message);
                            return;
                        }
                        getCountMoney();
                        leftTimer(leftTime);
                    }, function () {
                        moduleCommon.showInfo('网络繁忙，请稍后重试');
                    });
                    CountMoneyTimes.hide();
                });
            }
            i--;
        }, 1000);
    };
    var getCountMoney = function () {
        $.extendGetJSON(moduleCommon.httpURL + $('#GetCountMoneyStatistics').val(), {
            'countId': countId,
            '_r': Math.random()
        }, function (data) {
            $('#countmoney .money-ing li[data-rank!=0]').attr('data-rank', 0);
            $.each(data.Counts, function (index, ele) {
                if ($('#countmoney .money-ing ul li[data-userid=' + ele.FansId + ']').size() < 1) {
                    $('#countmoney .money-ing ul').append('<li data-userid="' + ele.FansId + '" data-header="' + ele.Head + '" data-nickname="' + ele.NickName + '" style="left:1000px;"><div class="number"><span>' + ele.Count + '</span></div><div class="money-bg"></div><i></i><div class="money-user"><img src="' + ele.Head + '"><span>' + ele.NickName + '</span></div></li>');
                }
                var times;
                switch (index) {
                    case 0:
                        times = 200;
                        break;
                    case 1:
                        times = 300;
                        break;
                    case 2:
                        times = 400;
                        break;
                    default :
                        times = Math.random() * 500 + 500;
                }
                if (ele.Count > parseInt($('#countmoney .money-ing ul li[data-userid=' + ele.FansId + '] div.number span').html())) {
                    var downTimer = setInterval(function () {
                        $('#countmoney .money-ing ul li[data-userid=' + ele.FansId + ']').prepend('<img class="downmoney" src="' + $('#config>#FileWebHost').val() + '/ScreenTheme/default/images/money.gif">');
                    }, times);
                    setTimeout(function () {
                        clearInterval(downTimer);
                    }, 1600)
                }
                $('#countmoney .money-ing ul li[data-userid=' + ele.FansId + '] div.number').animate({'bottom': (ele.Count / maxTimes) * moneyHeight + 121 + 'px'}).find('span').html(ele.Count);
                $('#countmoney .money-ing ul li[data-userid=' + ele.FansId + '] .money-bg').animate({'height': (ele.Count / maxTimes) * moneyHeight + 'px'});
                $('#countmoney .money-ing ul li[data-userid=' + ele.FansId + ']').attr('data-rank', index + 1).animate({'left': index * moneyWidth + 'px'});
            });
            $('#countmoney .money-ing ul li[data-rank!=0]').show();
            $('#countmoney .money-ing ul li[data-rank=0]').animate({'left': 9 * moneyWidth + 'px'}, function () {
                $(this).hide();
            });
            if (data.IsOver && data.Counts.length > 0) {
                moduleCommon.showInfo('完了,不用数了');
                var countMoneyStr = '';
                var countMoneyStrLast = '';
                var maxLength = data.Counts.length > 10 ? 10 : data.Counts.length;
                for (i = 0; i < maxLength; i++) {
                    var liFans = $('#countmoney .money-ing ul li[data-userid=' + data.Counts[i].FansId + ']');
                    if (i >= 3) {
                        countMoneyStrLast = countMoneyStrLast + '<li><img onerror="imgError(this);" src="' + liFans.data('header') + '" /><span>' + liFans.data('nickname') + '</span></li>';
                    } else {
                        countMoneyStr = countMoneyStr + '<div class="money-user-top money-user-top' + i + '"><img onerror="imgError(this);" src="' + liFans.data('header') + '" /><span>' + liFans.data('nickname') + '</span></div>';
                    }
                }
                fireWork.show();
                $('body').append('<div class="count-money-animate"><div class="count-money-animate-bg"></div><div class="count-money-animate-content"></div>' + countMoneyStr + '<ul class="money-user-last">' + countMoneyStrLast + '</ul></div><a class="countmoney-submit"></a>');
                $('.countmoney-submit').on('click', function () {
                    submitShake();
                });
                setTimeout(function () {
                    $('.money-user-top2').animate({'margin-left': top2Left + 'px', 'opacity': 1});
                }, 500);
                setTimeout(function () {
                    $('.money-user-top1').animate({'margin-left': top1Left + 'px', 'opacity': 1});
                }, 1000);
                setTimeout(function () {
                    $('.money-user-top0').animate({'top': top0Top + 'px', 'opacity': 1});
                }, 1500);
                setTimeout(function () {
                    $('.money-user-last').animate({'opacity': 1});
                }, 2000);
                return false;
            }
            setTimeout(function () {
                getCountMoney();
            }, 2000);
        }, function () {
            setTimeout(function () {
                getCountMoney();
            }, 5000);
        });
    }

    var submitShake = function () {
        fireWork.hide();
        $('.countmoney-submit').remove();
        $('.count-money-animate').remove();
        $('#countmoneyflash').hide();
        $('#countmoney .money-ing').show().find('ul').empty();
        $('#countmoney .money-index').show();
    };

    var leftTimer = function (leftTime) {
        var timer = setInterval(function () {
            if (leftTime > 0) {
                $('.money-lefttime').html('倒计时：' + leftTime);
                leftTime--;
            } else {
                clearInterval(timer);
            }
        }, 1000);
    };
});