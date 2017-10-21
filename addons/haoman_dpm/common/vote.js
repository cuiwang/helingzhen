define(function (require, exports, module) {
    var selfModuleName = 'vote';
    var moduleCommon = require('common');
    var scrollIndex = 0;
    var voteHeight = 350
    var voteWidth = 120;
    var moduleWidth = 980;
    var percent = 1;
    var isShow = false;
    var guest;
    //定制
    // var currentActivityId = parseInt($('#OpenVote').val().split('/')[1]);
    var activityId = [9927,9923];
    var custom = false;
    // if (activityId.indexOf(currentActivityId) != -1) {
    //     custom = true;
    // }
    exports.init = function () {

                // $('#vote .arrow_left').on('click', function () {
                //     scrollIndex = Math.max(0, scrollIndex - 1);
                //     $('#vote #vote_scroll').stop().animate({'margin-left': Math.min(0, -moduleWidth * scrollIndex)});
                // });
                // $('#vote .arrow_right').on('click', function () {
                //     scrollIndex = Math.min($('#vote .vote_list').length - 1, scrollIndex + 1);
                //     $('#vote #vote_scroll').stop().animate({'margin-left': Math.max(-($('#vote .vote_list').length - 1) * moduleWidth, -moduleWidth * scrollIndex)});
                // });
                //
                // //嘉宾投票
                // $('#vote').on('click', '.guestvote', function () {
                //     var $this = $(this);
                //     var obj = $this.parents('.vote_list');
                //     var id = obj.data('voteid');
                //     var groupId = $this.data('groupid');
                //     $.extendPost(moduleCommon.httpURL + $('#OpenVote').val(), {
                //         'voteId': id,
                //         'groupId': groupId
                //     }, 'json', function (msg) {
                //         moduleCommon.loaded();
                //         if (msg.ResultType != 1) {
                //             moduleCommon.showInfo(msg.Message);
                //             return false;
                //         } else {
                //             moduleCommon.showInfo($this.html() + '已开启', 1);
                //             $this.hide().removeClass('not-vote');
                //             if (obj.find('.not-vote').size() <= 0) {
                //                 obj.find('.stopBtn').show();
                //             }
                //         }
                //     }, function () {
                //         moduleCommon.loaded();
                //         moduleCommon.showInfo('网络繁忙，请稍后重试');
                //     });
                // });
                // //显示粉丝并抽奖
                // $('#vote').on('click', '.vote_list li', function () {
                //     moduleCommon.loading('数据正在准备中，请稍后');
                //     var voteId = $(this).parents('.vote_list').data('voteid');
                //     var voteItemId = $(this).data('itemid');
                //     var voteName = $(this).find('span').html();
                //     var voteNum = $(this).find('font').html();
                //     $.extendGet(moduleCommon.httpURL + $("#GetVoteItemFans").val(), {
                //         'voteId': voteId,
                //         'voteItemId': voteItemId
                //     }, "json", function (data) {
                //         $('#vote #vote_scroll,#vote .arrow_left,#vote .arrow_right').hide();
                //         $('#vote').append('<div class="show-vote-fans"><span class="title">' + voteName + '共获得' + voteNum + '票</span><div class="title-btn"><a class="clickBtn middleBtn graybg">点击返回</a></div><ul class="vote-fans"></ul></div>');
                //         if (data.length > 0) {
                //             $(data).each(function (index, element) {
                //                 var groupName = '';
                //                 if (element.IsGuest) {
                //                     groupName = '<span></span>';
                //                 }
                //                 var str = '<li><div>' + groupName + '<img src="' + element.Head + '"></div><span>' + element.NickName + '</span></li>';
                //                 $('#vote .vote-fans').append(str);
                //             });
                //         }
                //         $('.show-vote-fans .graybg').click(function () {
                //             $('.show-vote-fans').remove();
                //             $('#vote #vote_scroll,#vote .arrow_left,#vote .arrow_right').show();
                //         });
                //         moduleCommon.loaded();
                //     }, function () {
                //         moduleCommon.showInfo("加载失败,请重试!");
                //         moduleCommon.loaded();
                //     });
                // });


               getScreenVote();
    };

    // 获取大屏幕投票
    var getScreenVote = function () {
        moduleCommon.loading('数据正在准备中，请稍后');
        var groupStr = '';
        $.extendGetJSON(go_GetVote, {}, function (data) {
            moduleCommon.loaded();
            isShow = data.isshow;
            guest = data.guest;
            $('#vote #vote_scroll').empty();
            $.each(data.group, function (index, ele) {
                groupStr += '<a class="clickBtn middleBtn guestvote not-vote" data-groupid="' + ele.Id + '">' + ele.Name + '投票</a>';
            });
            if (data.vote.length > 0) {
                $.each(data.vote, function (i, e) {
                    if (e.Type == 1) {
                        var div = '';
                        div += '<div class="vote_list" data-voteid="' + e.Id + '"><span class="title">' + e.Name + '</span>';
                        div += '<div class="num">共<span>0</span>人参与';
                        div += '<a class="clickBtn middleBtn beginVote" data-isvote="0" data-groupid="0" data-voteid="' + e.Id + '">开启投票</a>';
                        div += groupStr;
                        div += '</div><div class="vote-scroll-box"><ul>';
                        if (e.Items && e.Items.length > 0) {
                            $.each(e.Items, function (index, ele) {
                                div += '<li data-itemid="' + ele.Id + '"><span>' + ele.Name + '</span><div class="normal" style="height:0;"></div><div class="guest" style="height:0;"></div><font style="bottom:10px;">0</font></li>';
                            });
                        }
                        div += '</ul></div></div>';
                        $('#vote #vote_scroll').append(div);
                        if (custom) {
                            var lastUl = $('#vote #vote_scroll').find('.vote_list:last ul');
                            lastUl.width(lastUl.find('li').size() * voteWidth);
                            lastUl.css({'left': '50%', 'margin-left': -lastUl.width() / 2});
                        }
                        var obj = $('[data-voteid=' + e.Id + ']');
                    }
                });
                $('#vote #vote_scroll').css('width', $('#vote .vote_list').length * moduleWidth);
                $('#vote .vote_list a.beginVote').each(function (i, e) {
                    $(e).on('click', function () {
                        openVote(e);
                    });
                });

                $('#vote .vote_list').each(function (i, e) {
                    $(e).find('li').each(function (index, ele) {
                        $(ele).css('left', $(ele).width() * index);
                    });
                });

                //加载一次数据
                $.each(data.vote, function (i, e) {
                    getVoteStatistics(e.Id);
                });
            }
        }, function () {
            moduleCommon.loaded();
            moduleCommon.showInfo('网络繁忙，请稍后重试');
        });
    }

//开启 关闭投票
    var openVote = function (v) {
        var id = $(v).data('voteid');
        var groupId = $(v).data('groupid');
        moduleCommon.loading('数据正在准备中，请稍后');
        if ($(v).data('isvote') == '0') {
            $.extendPost(moduleCommon.httpURL + $('#OpenVote').val(), {
                'voteId': id,
                'groupId': groupId
            }, 'json', function (msg) {
                moduleCommon.loaded();
                var obj = $('[data-voteid=' + id + ']');
                if (msg.ResultType != 1) {
                    moduleCommon.showInfo(msg.Message);
                    return false;
                } else {
                    $(v).addClass('stopBtn').html('关闭投票');
                    $(v).data('isvote', '1');
                    if (guest == true) {
                        if (obj.find('.guestvote').size() > 0) {
                            $(v).hide();
                            obj.find('.guestvote').css('display', 'inline-block').addClass('not-vote');
                        }
                    }
                    moduleCommon.showInfo('已开启投票', 1);
                    var getId = setInterval(function () {
                        getVoteStatistics(id);
                    }, 2000);
                    $(v).data('getid', getId);
                }
            }, function () {
                moduleCommon.loaded();
                moduleCommon.showInfo('网络繁忙，请稍后重试');
            });
        } else {
            $.extendPost(moduleCommon.httpURL + $('#CloseVote').val(), {'voteId': id}, 'json', function (msg) {
                moduleCommon.loaded();
                if (msg.ResultType != 1) {
                    moduleCommon.showInfo(msg.Message);
                    return false;
                } else {
                    $(v).data('isvote', '0');
                    $(v).removeClass('stopBtn').html('开启投票');
                    moduleCommon.showInfo('已关闭投票', 1);
                    clearInterval($(v).data('getid'));
                }
            }, function () {
                moduleCommon.loaded();
                moduleCommon.showInfo('网络繁忙，请稍后重试');
            });
        }
    }

//更新投票结果
    var getVoteStatistics = function (voteId) {
        $.getJSON(moduleCommon.httpURL + $('#GetVoteStatistics').val(), {'voteId': voteId}, function (data) {
            $('#vote .vote_list[data-voteid="' + voteId + '"] .num span').html(data.Total);
            if (data.Total > 0) {
                percent = data.Items[0].Total;
                $.each(data.Items, function (index, ele) {
                    var $voteItem = $('#vote .vote_list[data-voteid="' + voteId + '"] [data-itemid="' + ele.Id + '"]');
                    $voteItem.animate({'left': voteWidth * index});
                    var totalVote = ele.Total;
                    var totalHeight = totalVote / percent * voteHeight;
                    var normalHeight = 0;
                    var guestHeight = 0;
                    if (isShow) {
                        normalHeight = ele.UserTotal / totalVote * totalHeight;
                        guestHeight = ele.GuestTotal / totalVote * totalHeight;
                    } else {
                        normalHeight = totalHeight;
                        guestHeight = 0;
                    }
                    $voteItem.find('div.normal').animate({'height': normalHeight});
                    $voteItem.find('div.guest').animate({
                        'height': guestHeight,
                        'bottom': normalHeight
                    });
                    $voteItem.find('font').html(totalVote).animate({'bottom': totalHeight + 10});
                });
            }
        });
    }
});