$(function() {
    'use strict';

    var exRedpack = {
        init: function(page) {
            $('.btn_exchange_redpack', page).click(function () {
                var t = this;
                //未关注不允许兑换
                if ($(t).attr('data-exchange') == '0') {
                    $.modal({
                        title:  '温馨提示',
                        text: $(t).attr('data-subscribe-tips'),
                        buttons: [
                            {
                                text: '点击关注',
                                bold: true,
                                onClick: function(){
                                    $.showIndicator();
                                    window.location.href = $(t).attr('data-subscribe-url');
                                }
                            }
                        ]
                    });
                    return;
                }
                var url = $(t).attr('data-url');
                var token = $(t).attr('data-token');
                var redpack_amount = $(t).attr('data-redpack-amount');
                var buttons1 = [
                    {
                        text: '请确认',
                        label: true
                    },
                    {
                        text: '兑换 '+redpack_amount+'元 红包',
                        onClick: function() {
                            $.showIndicator();
                            $.ajax({
                                type: 'post',
                                url: url,
                                data: 'token='+token,
                                dataType: 'json',
                                success: function(resp) {
                                    var url = '';
                                    try {
                                        url = resp.data.url;
                                    } catch(e) {}
                                    if (resp.errno == 0) {
                                        $.router.loadPage(url, true);
                                    } else if (resp.errno == 4) {
                                        if (resp.errmsg) {
                                            $.toast(resp.errmsg);
                                        }
                                        setTimeout(function(){
                                            $.showIndicator();
                                            window.location.href = window.sysinfo.loginurl;
                                        }, 2000);
                                    } else if (resp.errno == 8000
                                        || resp.errno == 8001
                                        || resp.errno == 8002
                                        || resp.errno == 8003
                                        || resp.errno == 8004) {
                                        $.hideIndicator();
                                        $.modal({
                                            title:  '温馨提示',
                                            text: '<div class="tabs">'+
                                            '<div>您的操作受限，请联系管理员！</div>'+
                                            '<div class="color-gray font7">错误码：'+resp.errno+'</div>'+
                                            '</div>',
                                            buttons: [
                                                {
                                                    text: '关闭',
                                                    bold: true
                                                },
                                            ]
                                        });
                                    } else {
                                        $.hideIndicator();
                                        $.toast(resp.errmsg);
                                        if (url != '') {
                                            setTimeout(function(){
                                                $.router.loadPage(url, true);
                                            }, 2000);
                                        }
                                    }
                                }
                            });
                        }
                    }
                ];
                var buttons2 = [
                    {
                        text: '取消',
                        bg: 'danger'
                    }
                ];
                var groups = [buttons1, buttons2];
                $.actions(groups);
            });
        }
    };

    var wxMenu = {
        show: function() {
            wx.ready(function(){
                if (window.sysinfo.weixin_menu == '1') {
                    wx.showOptionMenu();
                    if (window.sysinfo._local_development == '1') {
                        $.toast('show menu');
                    }
                } else {
                    wx.hideOptionMenu();
                    if (window.sysinfo._local_development == '1') {
                        $.toast('hide menu');
                    }
                }
            });
        },
        hide: function() {
            wx.ready(function(){
                wx.hideOptionMenu();
                if (window.sysinfo._local_development == '1') {
                    $.toast('hide menu');
                }
            });
        }
    };

    var Timer = {
        run: function(starttime) {
            if (!starttime) return;
            var nowtime = parseInt(new Date().getTime() / 1000);
            var dd = 0, hh = 0, mm = 0, ss = 0;
            if (starttime > nowtime) {
                var ts = starttime - nowtime;//毫秒
                dd = parseInt(ts / 60 / 60 / 24, 10);//天
                hh = parseInt(ts / 60 / 60 % 24, 10);//小时
                mm = parseInt(ts / 60 % 60, 10);//分钟
                ss = parseInt(ts % 60, 10);//秒
                dd = this.format(dd);
                hh = this.format(hh);
                mm = this.format(mm);
                ss = this.format(ss);
            } else {
                //console.log('clear before: '+CountDownIds);
                Timer.clear(CountDownIds);
                //console.log('clear after: '+CountDownIds);
                $.showIndicator();
                window.location.reload();
                return false;
            }
            var txt = dd + '天' + hh + '时' + mm + '分' + ss + '秒';
            if (typeof arguments[1] == 'string') {   //id
                $('#' + arguments[1]).html(txt);
                return true;
            } else if (typeof arguments[1] == 'object') {   //element object
                $(arguments[1]).html(txt);
                return true;
            } else {    //默认返回字符串
                return txt;
            }
        },
        format: function(t) {
            return t>0&&t<10?'0'+t:t;
        },
        clear: function(timerId) {
            if ($.type(timerId) == 'number') {
                clearInterval(timerId);
            } else if ($.isArray(timerId)) {
                for (var i=0; i<timerId.length; i++) {
                    clearInterval(timerId[i]);
                }
                timerId.splice(0, timerId.length);
            }
        }
    };

    var CountDownIds = [];
    var loadCountdown = function(classname, page) {
        if ($('.countdown', page).length <= 0) {
            return;
        }
        $('.countdown', page).html('-天-时-分-秒');
        Timer.clear(CountDownIds);
        $('.'+classname, page).each(function(){
            var t = this;
            var id = $(t).attr('data-id');
            var starttime = $(t).attr('data-starttime');
            var timerId = setInterval(function() {
                var ret = Timer.run(starttime, t);
                if (!ret) {
                    Timer.clear($(t).attr('data-timerid'));
                }
            }, 1000);
            $(t).attr('data-timerid', timerId);
            CountDownIds.push(timerId);
        });
    };

    //home
    $(document).on('pageInit', ".superpage_home", function(e, id, page) {
        var t = this;
        wxMenu.show();
        exRedpack.init(page);
        //秒杀倒计时
        loadCountdown('home_product_countdown', page);
        //图片延迟加载
        $('.lazyload', page).picLazyLoad({
            element: $('.content', page),
            threshold: 100,
            placeholder: window.sysinfo.siteroot+'addons/superman_creditmall/template/mobile/images/placeholder.gif'
        });
    });

    $(document).on('pageReinit', ".superpage_home", function(e, id, page) {
        //页面后退重新初始化倒计时
        loadCountdown('home_product_countdown', page);
    });

    //search
    $(document).on('pageInit', ".superpage_search", function(e, id, page) {
        var t = this;
        wxMenu.show();
        exRedpack.init(page);
        //秒杀倒计时
        loadCountdown('home_product_countdown', page);
        //图片延迟加载
        $('.lazyload', page).picLazyLoad({
            element: $('.content', page),
            threshold: 100,
            placeholder: window.sysinfo.siteroot+'addons/superman_creditmall/template/mobile/images/placeholder.gif'
        });

        function addItems(data, params) {
            var html = '', item, item_url;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                item_url = params.item_url+'&id='+item['id'];

                html += '<div class="col-50 item_wrap">';
                html += '<a href="'+item_url+'" class="external">';
                html += '<div class="item_img">';
                html += '<img src="'+item['cover']+'" onerror="this.src=\''+params.img_placeholder+'\'"/>';
                var timestamp = parseInt((new Date).getTime()/1000);
                if (item['end_time'] && item['end_time'] < timestamp) {
                    html += '<div class="product_prompt">';
                    html += '<div>';
                    html += '<span>已结束</span>';
                    html += '</div>';
                    html += '</div>';
                } else if (item['start_time'] && item['start_time'] > timestamp) {
                    html += '<div class="product_prompt">';
                    if (item['type'] == 7) {
                        html += '<span>';
                        html += '<span class="font7">距离开始时间还有</span>';
                        html += '<span class="font7 home_product_countdown countdown" data-id="'+item['id']+'" data-starttime="'+item['start_time']+'">-天-时-分-秒</span>';
                        html += '</span>';
                    } else {
                        html += '<div>';
                        html += '<span>未开始</span>';
                        html += '</div>';
                    }
                    html += '</div>';
                } else if (item['total'] <= 0) {
                    html += '<div class="product_prompt">';
                    html += '<div>';
                    html += '<span>已被抢光</span>';
                    html += '</div>';
                    html += '</div>';
                }
                html += '</div>';
                html += '<div class="text-overflow font7">'+item['title']+'</div>';
                html += '<div class="item_info clearfix font6">';
                if (item['type'] == 1 || item['type'] == 8) {
                    html += '<span class="pull-left">'+item['sales']+'人兑换</span>';
                } else if (item['type'] == 7) {
                    html += '<span class="pull-left">'+item['sales']+'人秒杀</span>';
                } else if (item['type'] == 2) {
                    html += '<span class="pull-left">'+item['joined_total']+'人出价</span>';
                }
                html += '<span class="pull-right">';
                if (item['price'] > 0) {
                    html += '<span class="credit_color">'+item['credit']+'</span>'+item['credit_title']+'+<span class="credit_color">'+item['price']+'</span>元';
                } else {
                    html += '<span class="credit_color">'+item['credit']+'</span>'+item['credit_title'];
                }
                html += '</span>';
                html += '</div>';
                html += '</a>';
                html += '</div>';
            }
            $('.search_list .row', page).append(html);
        }
        var loading = false;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var item_url = $(t).attr('data-item-url');
            var img_placeholder = $(t).attr('data-img-placeholder');
            var pageno = $(t).attr('data-page');
            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var params = {
                            item_url: item_url,
                            img_placeholder: img_placeholder
                        };
                        addItems(response, params);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll', page));
                        $('.infinite-scroll-preloader', page).remove();
                    }
                }
            });
        });
    });

    $(document).on('pageReinit', ".superpage_search", function(e, id, page) {
        //页面后退重新初始化倒计时
        loadCountdown('home_product_countdown', page);
    });

    //list
    $(document).on('pageReinit', ".superpage_list", function(e, id, page) {
        //页面后退重新初始化倒计时
        loadCountdown('list_product_countdown');
    });
    $(document).on('pageInit', ".superpage_list", function(e, id, page) {
        wxMenu.show();
        //秒杀倒计时
        loadCountdown('list_product_countdown', page);
        //图片延迟加载
        $('.lazyload', page).picLazyLoad({
            element: $('.content', page),
            threshold: 100,
            placeholder: window.sysinfo.siteroot+'addons/superman_creditmall/template/mobile/images/placeholder.gif'
        });
        $('.btn_share', page).click(function () {
            $.showIndicator();
        });
        function addItems(data, params) {
            var html = '', item, item_url;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                item_url = params.item_url+'&id='+item['id'];
                html += '<div class="col-50 item_wrap">'+
                '<a href="'+item_url+'">'+
                '<div class="item_img">'+
                '    <img src="'+item['cover']+'" onerror="this.src=\''+params.img_placeholder+'\'"/>'+
                '</div>'+
                '<div class="text-overflow font7">'+item['title']+'</div>'+
                '<div class="item_info clearfix font6">';
                if (item['type'] == 1 || item['type'] == 7) {
                    html += '<span class="pull-left">'+item['sales']+'人已购买</span>';
                } else if (item['type'] == 2) {
                    html += '<span class="pull-left">'+item['joined_total']+'人已参与</span>';
                }
                html += '<span class="pull-right">';
                if (item['price'] > 0) {
                    html += '<span class="credit_color">'+item['credit']+'</span>'+item['credit_title']+'+<span class="credit_color">'+item['price']+'</span>元';
                } else {
                    html += '<span class="credit_color">'+item['credit']+'</span>'+item['credit_title'];
                }
                html += '</span></div></a></div>';
            }
            $('.product_wrap .row', page).append(html);
        }
        var loading = false;
        $(page).on('infinite', '.infinite-scroll',function() {
			if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var item_url = $(t).attr('data-item-url');
            var img_placeholder = $(t).attr('data-img-placeholder');
            var type = $(t).attr('data-type');
            var sort = $(t).attr('data-sort');
            var pageno = $(t).attr('data-page');
            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;

            $.ajax({
                //http://veking_wx.localhost/app/index.php?i=6&c=entry&type=1&do=list&m=superman_creditmall&page=2
                url: url,
                data: 'type='+type+'&sort='+sort,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var params = {
                            item_url: item_url,
                            img_placeholder: img_placeholder
                        };
                        addItems(response, params);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll', page));
                        $('.infinite-scroll-preloader', page).remove();
                    }
                }
            });
        });

        $('.type_link', page).click(function(){
            $('.type_link', page).each(function(){
                $(this).removeClass('button-fill');
            });
            var product_type = $(this).attr('data-type');
            $('.get-type', page).attr('data-type',product_type);
            $(this).addClass('button-fill');
            //$.router.loadPage($(t).attr('data-url'), true);
        });

        $('.sort-link', page).click(function(){
            $('.sort-link', page).each(function(){
                $(this).removeClass('button-fill');
            });
            $(this).addClass('button-fill');
            var type = $('.get-type', page).attr('data-type');
            var url = $(this).attr('data-url');
            url += '&type='+type;
            $.router.loadPage(url, true);
        });
    });

    //list-share
    $(document).on('pageInit', ".superpage_list_share", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        function addItems(data, params) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                var item_url = params.item_url+'&id='+item['id'];
                html += '<li>';
                html += '<div class="item-content">';
                html += '<div class="item-media">';
                html += '<img src="'+item['cover']+'"  onerror="this.src=\''+params.img_placeholder+'\'" style=\'width: 2.2rem;\'>';
                html += '</div>';
                html += '<div class="item-inner">';
                html += '<div class="item-text font7">'+item['title']+'</div>';
                html += '<div class="item-title-row">';
                html += '<div class="item-title font6">分享 <span class="text-strong credit_color">+'+item['share_credit']+'</span> '+item['share_credit_title']+'</div>';
                html += '<div class="item-title">';
                html += '<a href="'+item_url+'" class="button button-dark font6">点击分享</a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</li>';
            }
            $('.share_list ul', page).append(html);
        }
        var loading = false;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var item_url = $(t).attr('data-item-url');
            var img_placeholder = $(t).attr('data-img-placeholder');
            var pageno = $(t).attr('data-page');
            pageno = parseInt(pageno) + 1;
            url += '&page=' + pageno;

            $.ajax({
                url: url,
                dataType: 'json',
                success: function (response) {
                    loading = false;
                    if (response.length > 0) {
                        var params = {
                            item_url: item_url,
                            img_placeholder: img_placeholder
                        };
                        addItems(response, params);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll', page));
                        $('.infinite-scroll-preloader', page).remove();
                    }
                }
            });
        });
    });

    //list-redpack
    $(document).on('pageInit', ".superpage_list_redpack", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        exRedpack.init(page);
        //常见问题
        $('.open_help', page).click(function () {
            $.popup('.popup_help');
        });
    });

    //redpack-detail
    $(document).on('pageInit', ".superpage_detail_redpack", function(e, id, page) {
        wxMenu.show();
    });

    //detail
    $(document).on('pageReinit', ".superpage_detail", function(e, id, page) {
        //页面后退重新初始化倒计时
        loadCountdown('detail_product_countdown', page);
    });
    $(document).on('pageInit', ".superpage_detail", function(e, id, page) {
        var thispage = this;
        loadCountdown('detail_product_countdown', page);
        wxMenu.show();
        //浏览数
        $.ajax({
            url: $(thispage).attr('data-view-url'),
            success: function(response){}
        });

        //分享数
        sharedata.success = function(){
            $.ajax({
                url: $(thispage).attr('data-share-url'),
                success: function(response){}
            });
        };

        $('.btn_exchange', page).click(function(){
            var t = this;
            if ($(t).attr('data-flag') == '1') {
                return;
            }
            $(t).addClass('disabled').attr('data-flag', '1');
            //未关注不允许兑换
            if ($(t).attr('data-exchange') == '0') {
                $.modal({
                    title:  '温馨提示',
                    text: $(t).attr('data-subscribe-tips'),
                    buttons: [
                        {
                            text: '点击关注',
                            bold: true,
                            onClick: function(){
                                $.showIndicator();
                                window.location.href = $(t).attr('data-subscribe-url');
                            }
                        }
                    ]
                });
                return;
            }
            $.showIndicator();
            var url = $(t).attr('data-url');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(resp) {
                    $.hideIndicator();
                    $(t).removeClass('disabled').attr('data-flag', '0');
                    if (resp.errno == 0) {
                        url = url.replace('&check=yes', '');
                        $.router.loadPage(url, true);
                    } else if (resp.errno == 4) {
                        if (resp.errmsg) {
                            $.toast(resp.errmsg);
                        }
                        setTimeout(function(){
                            $.showIndicator();
                            window.location.href = window.sysinfo.loginurl;
                        }, 2000);
                    } else if (resp.errno == 8000
                        || resp.errno == 8001
                        || resp.errno == 8002
                        || resp.errno == 8003
                        || resp.errno == 8004) {
                        $.modal({
                            title:  '温馨提示',
                            text: '<div class="tabs">'+
                            '<div>您的操作受限，请联系管理员！</div>'+
                            '<div class="color-gray font7">错误码：'+resp.errno+'</div>'+
                            '</div>',
                            buttons: [
                                {
                                    text: '关闭',
                                    bold: true
                                },
                            ]
                        });
                    } else {
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url, true);
                            }, 2000);
                        }
                    }
                }
            });
            return true;
        });

        $('.tab-link', page).click(function(){
            var tab = $(this).attr('data-tabs');
            tab = 'tab'+tab;
            $('.content', page).attr('data-tabs',tab);
        });
        function addItems(data, params) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                html += '<li class="item-content">'+
                        '<div class="item-inner font6">'+
                        '<div class="auction_user">'+
                        '<div class="item-media text-left">'+
                        '<img class="avatar" src="'+item['avatar']+
                        '" onerror="this.src=\'../app/resource/images/heading.jpg\'" alt=""/>'+
                        '<span class="text-overflow">'+item['nickname']+'</span>'+
                        '</div></div>'+
                        '<div class="auction_credit text-center text-overflow">'+item['credit']+item['credit_title']+'</div>' +
                        '<div class="auction_time text-right text-overflow">'+item['dateline']+'</div>' +
                        '</div></li>';
            }
            $('.exchange_wrap ul', page).append(html);
        }
        var loading = false;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');
            var tabs = $(t).attr('data-tabs');
            if (tabs=='tab1') {
                return;
            }
            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        addItems(response);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll', page));
                        $('.infinite-scroll-preloader', page).remove();
                        $('.nodata', page).show();
                    }
                }
            });
        });
    });

    //confirm order
    $(document).on('pageInit', ".superpage_confirm", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        //提交订单按钮
        $('#btn_submit_order', page).click(function(){
            $.showIndicator();
            var t = this;
            $(t).addClass('disabled').attr('disabled', true);
            var url = window.location.href;
            var id = $('input[name=id]', page).val();
            var token = $('input[name=token]', page).val();
            var total = $('input[name=total]', page).val();
            var dispatch_id = $('input[name=dispatch_id]', page).val();
            var address_id = $('input[name=address_id]', page).val();
            var remark = $('textarea[name=remark]', page).val();
            var need_address = $('#need_address', page).val();
            var prodcut_credit = $('#prodcut_credit', page).val();
            var mycredit = $('#mycredit', page).val();
            var credit_title = $('#credit_title', page).val();
            var credit = total * prodcut_credit;
            var data = '';
            if (dispatch_id == '') {
                $.hideIndicator();
                $.toast('请选择配送方式');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            if (need_address != '0' && address_id == '') {
                $.hideIndicator();
                $.toast('请添加收货地址');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            if (credit <= 0) {
                $.hideIndicator();
                $.toast('非法参数');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            if (mycredit < credit) {
                $.hideIndicator();
                $.toast('您的'+credit_title+'不足');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            data += 'id='+id;
            data += '&token='+token;
            data += '&total='+total;
            data += '&dispatch_id='+dispatch_id;
            data += '&address_id='+address_id;
            data += '&remark='+remark;
            data += '&submit=yes';
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function(resp){
                    $.hideIndicator();
                    $(t).removeClass('disabled').removeAttr('disabled');
                    if (resp.errno == 0) {
                        if (resp.data.url) {
                            $.router.loadPage(resp.data.url, true);
                        }
                    } else if (resp.errno == 4) {
                        if (resp.errmsg) {
                            $.toast(resp.errmsg);
                        }
                        setTimeout(function(){
                            $.showIndicator();
                            window.location.href = window.sysinfo.loginurl;
                        }, 2000);
                    } else {
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url, true);
                            }, 2000);
                        }
                    }
                }
            });
        });

        //配送方式
        var dispatch_btn = $('.dispatch_wrap a.list-button', page);
        dispatch_btn.click(function(){
            dispatch_btn.each(function(){
                $(this).removeClass('dispatch_active').next().hide();
                $('.icon', this).remove();
            });
            var txt = $(this).html();
            var dispatch_id = $(this).attr('data-dispatch-id');
            var need_address = $(this).attr('data-need-address');
            $(this).addClass('dispatch_active').html(txt+'<span class="icon icon-check pull-right"></span>').next().show();
            $('#dispatch_id', page).val(dispatch_id);
            $('#need_address', page).val(need_address);
            if (need_address != '0') {
                var address_id = $(this).attr('data-address-id');
                $('#address_id', page).val(address_id);
            }
        });

        $('#product_total', page).click(function(){
            return false;
        });

        //加数量
        var product_total = $('#product_total', page);
        $('#btn_plus', page).click(function(){
            var t = this;
            var total = parseInt(product_total.html());
            var max_total = parseInt($(this).attr('data-max-total'));
            if (total > 0) {
                total += 1;
                if (total > max_total) {
                    $.toast($(t).attr('data-total-title'));
                    return;
                }
                var order_buy_num = $(this).attr('data-order-buy-num');
                if (order_buy_num > 0 && total > order_buy_num) {
                    $.toast($(t).attr('data-over-title'));
                    return;
                }
                product_total.html(total);
                $('#total', page).val(total);
            }
        });

        //减数量
        $('#btn_minus', page).click(function(){
            var t = this;
            var total = parseInt(product_total.html());
            var min_total = $(this).attr('data-min-total');
            if (total > 0) {
                total -= 1;
                if (total < min_total) {
                    $.toast($(t).attr('data-title'));
                    return;
                } else {
                    product_total.html(total);
                }
                $('#total', page).val(total);
            }
        });
    });

    //pay
    $(document).on('pageInit', ".superpage_pay", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        //选择支付方式
        $('.pay_type', page).click(function(){
            if (!$(this).hasClass('disabled')) {
                $('.pay_type', page).each(function(){
                    $(this).addClass('button-dark');
                    $('.icon', this).remove();
                });
                var txt = $(this).html();
                $(this).html('<span class="icon icon-check"></span> '+txt).removeClass('button-dark');
                var pay_type = $(this).attr('data-pay-type');
                $('#pay_type', page).val(pay_type);
            }
        });

        //微信支付按钮初始化
        wx.ready(function(){
            wx.checkJsApi({
                jsApiList: ['chooseWXPay'],
                success: function(res) {
                    if (res.checkResult.chooseWXPay) {
                        var price = $('.btn_wechat_pay', page).attr('data-price');
                        $('.btn_wechat_pay', page).removeClass('disabled').html('微信支付 '+price+'元');
                    }
                }
            });
        });

        //确认支付按钮
        $('#btn_submit_pay', page).click(function(){
            $.showIndicator();
            var t = this;
            $(t).addClass('disabled').attr('disabled', true);
            var url = window.location.href;
            var id = $('input[name=id]', page).val();
            var token = $('input[name=token]', page).val();
            var pay_type = $('input[name=pay_type]', page).val();
            var choose_paytype = $('form', page).attr('data-choose-paytype');
            var data = '';
            if (choose_paytype == '1' && pay_type == '') {
                $.hideIndicator();
                $.toast('请选择支付方式');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            data += 'id='+id;
            data += '&token='+token;
            data += '&pay_type='+pay_type;
            data += '&submit=yes';
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function(resp){
                    $.hideIndicator();
                    if (resp.errno == 0) {
                        $.toast(resp.errmsg);
                        setTimeout(function(){
                            if (resp.data.url) {
                                $.router.loadPage(resp.data.url, true);
                            } else if (resp.data.redirect_url) {
                                window.location.href = resp.data.redirect_url;
                            }
                        }, 2000);
                    } else {
                        $(t).removeClass('disabled').removeAttr('disabled');
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url, true);
                            }, 2000);
                        }
                    }
                }
            });
        });
    });

    //address
    $(document).on('pageInit', ".superpage_address", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        //删除地址
        $('.delete_address', page).click(function () {
            var url = window.location.href;
            var t = this;
            var delete_url = $(t).attr('data-url');
            var buttons1 = [
                {
                    text: '确认删除该地址？',
                    label: true
                },
                {
                    text: '确认',
                    bold: true,
                    color: 'danger',
                    onClick: function() {
                        $.showIndicator();
                        $.ajax({
                            type: 'post',
                            url: delete_url,
                            dataType: 'json',
                            success: function (resp) {
                                if (resp.errno == 0) {
                                    $.toast(resp.errmsg);
                                    setTimeout(function(){
                                        window.location.href=url;
                                    }, 2000);
                                } else {
                                    if (resp.errmsg) {
                                        $.toast(resp.errmsg);
                                    }
                                }
                            }
                        });
                    }
                }
            ];
            var buttons2 = [
                {
                    text: '取消',
                    bg: 'danger'
                }
            ];
            var groups = [buttons1, buttons2];
            $.actions(groups);
        });

        //加载地区
        $("#city-picker").cityPicker({
            toolbarTemplate: '<header class="bar bar-nav"><button class="button button-link pull-right close-picker">确定</button><h1 class="title">选择地区</h1></header>'
        });

        $('.btn_submit', page).click(function () {
            $.showIndicator();
            var t = this;
            $(t).addClass('disabled').attr('disabled', true);
            var url = window.location.href;
            var token = $('input[name=token]', page).val();
            var username = $('input[name=username]', page).val();
            var mobile = $('input[name=mobile]', page).val();
            var city = $('input[name=city]', page).val();
            var address = $('textarea[name=address]', page).val();
            var isdefault = $('input[name=isdefault]', page).val();
            var data = '';
            if (username == '') {
                $.hideIndicator();
                $(t).removeClass('disabled').removeAttr('disabled');
                $.toast('请输入您的姓名');
                return false;
            }
            if (!mobile) {
                $.hideIndicator();
                $(t).removeClass('disabled').removeAttr('disabled');
                $.toast('请输入您的手机号');
                return false;
            }
            if (mobile.search(/^([0-9]{11})?$/) == -1) {
                $.hideIndicator();
                $(t).removeClass('disabled').removeAttr('disabled');
                $.toast('请输入正确的手机号码');
                return false;
            }
            if (!city) {
                $.hideIndicator();
                $(t).removeClass('disabled').removeAttr('disabled');
                $.toast('请选择地区');
                return false;
            }
            if (address == '') {
                $.hideIndicator();
                $(t).removeClass('disabled').removeAttr('disabled');
                $.toast('请输入详细地址');
                return false;
            }
            data += 'token='+token;
            data += '&username='+username;
            data += '&mobile='+mobile;
            data += '&city='+city;
            data += '&address='+address;
            data += '&isdefault='+isdefault;
            data += '&submit=yes';
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function (resp) {
                    $.hideIndicator();
                    if (resp.errno == 0) {
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                window.location.href=resp.data.url;
                            }, 2000);
                        }
                    } else {
                        $(t).removeClass('disabled').removeAttr('disabled');
                        if (resp.errmsg) {
                            $.toast(resp.errmsg);
                        }
                    }
                }
            })
        });

        if ($('#wechat_address_flag', page).length > 0) {
            var onBridgeReady = function() {
                var waf = $('#wechat_address_flag', page);
                var appId = waf.attr('data-appid');
                var addrSign = waf.attr('data-addrsign');
                var timeStamp = waf.attr('data-timestamp');
                var nonceStr = waf.attr('data-noncestr');
                var forward = waf.attr('data-forward');
                var token = waf.attr('data-token');
                var url = waf.attr('data-url');
                WeixinJSBridge.invoke('editAddress', {
                        appId:appId,
                        scope:'jsapi_address',
                        signType:'sha1',
                        addrSign:addrSign,
                        timeStamp:timeStamp,
                        nonceStr:nonceStr
                    },
                    function (res) {
                        // res存有地址信息
                        if (res.err_msg == 'edit_address:ok') {  //已选收货地址
                            var data = '';
                            data += 'token='+token;
                            data += '&username='+res.userName;
                            data += '&mobile='+res.telNumber;
                            data += '&city='+res.proviceFirstStageName+' '+res.addressCitySecondStageName+' '+res.addressCountiesThirdStageName;
                            data += '&address='+res.addressDetailInfo;
                            data += '&isdefault=on';
                            data += '&submit=yes';
                            $.ajax({
                                type: 'post',
                                url: url+'&act=post&forward='+forward,
                                data: data,
                                dataType: 'json',
                                success: function (resp) {
                                    if (resp.errno == 0) {
                                        $.toast(resp.errmsg);
                                        if (resp.data.url) {
                                            setTimeout(function(){
                                                window.location.href=resp.data.url;
                                            }, 2000);
                                        }
                                    } else {
                                        if (resp.errmsg) {
                                            $.toast(resp.errmsg);
                                            setTimeout(function(){
                                                window.location.href=url+'&wechat_addr_switch=0&forward='+forward;
                                            }, 2000);
                                        }
                                    }
                                }
                            })
                        } else {                    //未选收货地址
                            window.location.href=url+'&forward='+forward;
                        }
                    }
                );
            };
            if (typeof WeixinJSBridge == "undefined") {
                if (document.addEventListener) {
                    document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                } else if (document.attachEvent) {
                    document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                    document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                }
            } else {
                onBridgeReady();
            }
        }
    });

    //order
    $(document).on('pageInit', ".superpage_order", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        function check_order_pay(t) {
            if ($(t).attr('data-flag') == 1) {
                return;
            }
            $.showIndicator();
            $(t).addClass('disabled');
            var url = $(t).attr('data-url');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(resp){
                    $.hideIndicator();
                    if (resp.errno == 0) {
                        url = url.replace('&check=yes', '');
                        $.router.loadPage(url, true);
                    } else if (resp.errno == 8000
                        || resp.errno == 8001
                        || resp.errno == 8002
                        || resp.errno == 8003
                        || resp.errno == 8004) {
                        $(t).removeClass('disabled');
                        $.modal({
                            title:  '温馨提示',
                            text: '<div class="tabs">'+
                            '<div>您的操作受限，请联系管理员！</div>'+
                            '<div class="color-gray font7">错误码：'+resp.errno+'</div>'+
                            '</div>',
                            buttons: [
                                {
                                    text: '关闭',
                                    bold: true
                                },
                            ]
                        });
                    } else {
                        $(t).removeClass('disabled');
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url, true);
                            }, 2000);
                        }
                    }
                }
            });
        }
        //订单支付
        $('.btn_order_pay', page).click(function(){
            check_order_pay(this);
        });
        //订单操作
        $('.btn_order_operate', page).click(function () {
            var t = this;
            if ($(t).attr('data-flag') == 1) {
                return;
            }
            $(t).attr('data-flag', 1).addClass('disabled');
            var buttons1 = [
                {
                    text: $(t).attr('data-title'),
                    label: true
                },
                {
                    text: '确认',
                    bold: true,
                    color: 'danger',
                    onClick: function() {
                        $.ajax({
                            url: $(t).attr('data-url'),
                            success: function(resp) {
                                if (resp.errno == 0) {
                                    $.toast(resp.errmsg);
                                    if (resp.data.url) {
                                        setTimeout(function(){
                                            window.location.href = resp.data.url;
                                        }, 2000);
                                    }
                                } else {
                                    $(t).attr('data-flag', 0).removeClass('disabled');
                                    $.toast(resp.errmsg);
                                }
                            }
                        });
                    }
                }
            ];
            var buttons2 = [
                {
                    text: '取消',
                    bg: 'danger',
                    onClick: function() {
                        $(t).attr('data-flag', 0);
                        $(t).removeClass('disabled');
                    }
                }
            ];
            var groups = [buttons1, buttons2];
            $.actions(groups);
            return false;
        });

        //评价按钮
        /*$('.btn_comment').click(function(){
            $.toast('评价功能暂未开放！');
            return false;
        });*/

        //我的订单页无限滚动
        function addItems(data,params) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                params.item_url += '&orderid='+item['id'];
                params.detail_url += '&id='+item['product']['id'];
                params.receive_url += '&orderid='+item['id'];
                params.pay_url += '&orderid='+item['id'];
                params.comment_url += '&orderid='+item['id'];
                params.list_url += '&type='+item['product']['type'];
                html += '<div class="card">';
                html += '<a href="'+params.item_url+'">';
                html += '<div class="card-header">';
                html += '<span class="font7">订单号: '+item['ordersn']+'</span>';
                html += '<span class="credit_color font7">'+item['status_title']+'</span>';
                html += '</div></a>';
                html += '<div class="card-content">';
                html += '<div class="list-block media-list">';
                html += '<ul>';
                if (item['isredpack']) {
                    html += '<a href="'+params.list_url+'">';
                } else {
                    html += '<a href="'+params.detail_url+'">';
                }
                html += '<li class="item-content">';
                html += '<div class="item-media">';
                html += '<img class="cover" src="'+item['product']['cover']+'" onerror="this.src=\''+params.img_placeholder+'\'"/>';
                var red_arr = [5, 6];
                if (item['product']['cover'].indexOf('/addons/') && red_arr.indexOf(item['product']['type']) != '-1') {
                    html += '<span>'+item['extend']['redpack_amount']+'元</span>';
                }
                html += '</div>';
                html += '<div class="item-inner text-overflow">';
                html += '<div class="item-title-row">';
                html += '<div class="item-title">'+item['product']['title']+'</div>';
                html += '</div>';
                html += '<div class="item-subtitle clearfix">';
                html += '<div class="pull-left font6 total_wrap">';
                html += 'X'+item['total']+'</div>';
                html += '<div class="pull-left">';
                html += '<button class="button disabled font5 product_type">'+item['product']['type_name']+'</button>';
                html += '</div></div></div></li></a></ul></div></div>';
                html += '<div class="card-footer clearfix">';
                html += '<div class="row no-gutter order_footer_wrap">';
                html += '<div class="col-50 font6 order_footer_left">实付:';
                if (item['price']>0) {
                    html += item['credit']+item['credit_title']+'+'+item['price']+'元';
                } else {
                    html += item['credit']+item['credit_title'];
                }
                html += '</div><div class="col-50 btn_wrap text-right">';
                html += '<a href="'+params.item_url+'" class="button button-dark button-fill">查看</a>&nbsp;';
                if (item['status'] == 0) {
                    html += '<a href="'+params.pay_url+'" class="button button-fill button-success btn_order_pay">立即支付</a>&nbsp;';
                } else if (item['status'] == 2) {
                    html += '<a href="javascript:;" data-url="'+params.receive_url+'" data-flag="0" data-title="确认已收到商品？" class="button button-fill create-actions btn_order_operate">确认收货</a>&nbsp;';
                }
                html += '</div></div></div></div>';
            }
            $('.add-order', page).append(html);
            $('.btn_order_pay', page).click(function(){
                check_order_pay(this);
            });
        }
        var loading = false;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');
            var item_url = $(t).attr('data-item-url');
            var detail_url = $(t).attr('data-detail-url');
            var img_placeholder = $(t).attr('data-img-placeholder');
            var receive_url = $(t).attr('data-receive-url');
            var pay_url = $(t).attr('data-pay-url');
            var comment_url = $(t).attr('data-comment-url');
            var list_url = $(t).attr('data-list-url');
            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var params = {
                            item_url: item_url,
                            detail_url: detail_url,
                            img_placeholder: img_placeholder,
                            receive_url: receive_url,
                            pay_url: pay_url,
                            comment_url: comment_url,
                            list_url: list_url
                        };
                        addItems(response,params);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll', page));
                        $('.infinite-scroll-preloader', page).remove();
                    }
                }
            });
        });

        $('.btn_checkout', page).click(function () {
            var t = $(this);
            if (t.hasClass('disabled')) {
                return;
            }
            var code = $('input[name=code]', page);
            if (code.val() == '') {
                $.toast('请输入验证码');
                code.focus();
                return false;
            }
            var orderid = t.attr('data-orderid');
            var url = t.attr('data-url');
            var data = '';
            data += 'orderid='+orderid;
            data += '&code='+code.val();
            $.showIndicator();
            t.addClass('disabled');
            $.ajax({
                type: 'post',
                url: url +'&act=checkout',
                data: data,
                dataType: 'json',
                success: function (resp) {
                    $.hideIndicator();
                    $.toast(resp.errmsg);
                    if (resp.errno == 0) {
                        setTimeout(function(){
                            window.location.href=url+'&status=no_comment';
                        }, 2000);
                    } else {
                        t.removeClass('disabled');
                    }
                }
            })
        });
        if ($('.btn_checkout', page).length) {
            var checkQrcodeScanResult = function() {
                var btn = $('.btn_checkout', page);
                var orderid = btn.attr('data-orderid');
                var url = btn.attr('data-url');
                $.ajax({
                    type: 'post',
                    data: 'orderid='+orderid,
                    url: url+'&act=status',
                    success: function(resp) {
                        if (resp == 1 || resp == 2) {
                            setTimeout(function(){
                                checkQrcodeScanResult();
                            }, 3000);
                        } else if (resp == 3) {
                            window.location.href = url+'&act=show_checkout&orderid='+orderid;
                        }
                    }
                });
            };
            checkQrcodeScanResult();
        }
    });

    //profile
    $(document).on('pageInit', ".superpage_profile", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();

        //扫码核销
        var onBridgeReady = function () {
            $('.checkout_qrcode', page).click(function () {
                var t = $(this);
                var url = t.attr('data-url');
                wx.scanQRCode({
                    needResult: 1,
                    scanType: ["qrCode", "barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                    success: function (res) {
                        if (res.errMsg == 'scanQRCode:ok') {
                            window.location.href = url+'&orderid='+res.resultStr;
                        }
                    }
                });
            });
        };
        if (typeof WeixinJSBridge == "undefined") {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
            }
        } else {
            onBridgeReady();
        }

        $('.recommend_wrap img', page).click(function(){
            $.alert($('.swiper-slide', page).width());
        });
		var localIds;
        $('.myavatar_wrap', page).click(function () {
            var t = this;
			if (window.sysinfo.container == 'wechat') {
				wx.chooseImage({
					count: 1, // 默认9
					sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
					success: function (res) {
						localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
						$('img', t).attr('src', localIds);
					}
				});
			} else {
				$.toast('请在微信中上传头像');
			}
        });
		$('.myavatar_wrap img', page).click(function(event){
			$.popup('.popup_big_avatar');
			event.stopPropagation();
		});
		$('.popup_big_avatar').click(function(){
			$.closeModal('.popup_big_avatar');
		});
		var saveMemberInfo = function() {
            var serverId = $('#serverId', page).val();
            var mobile = $('#mobile', page).val();
            var nickname = $('#nickname', page).val();
            var email = $('#email', page).val();
            var token = $('input[name=token]', page).val();
            var url = window.location.href;
			$.ajax({
				type: 'post',
				data: 'serverId='+serverId+'&mobile='+mobile+'&email='+email+'&nickname='+nickname+'&token='+token+'&submit=yes',
				dataType: 'json',
				url: url,
				success: function(resp) {
					$.hideIndicator();
					$('input[name=submit]', page).removeClass('disabled');
					if (resp.errno == 0) {
                        $.toast('保存成功，跳转中...');
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url, true);
                            }, 2000);
                        }
					} else {
						$.toast(resp.errmsg);
					}
				}
			});
		};
        $('input[name=submit]', thispage).click(function(){
            $.showIndicator();
            var t = this;
            $(t).addClass('disabled');
            var mobile = $('#mobile', page).val();
            var email = $('#email', page).val();
            if (mobile != '') {
                if (!/^1\d{10}$/.test(mobile)) {
                    $.hideIndicator();
                    $.toast('请输入合法的手机号');
                    $(t).removeClass('disabled');
                    return false;
                }
            }
            if (email != '') {
                if (!/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(email)) {
                    $.hideIndicator();
                    $.toast('请输入合法邮箱');
                    $(t).removeClass('disabled');
                    return false;
                }
            }
			try {
				if (localIds.length > 0) {
					wx.uploadImage({
						localId: localIds[0], // 需要上传的图片的本地ID，由chooseImage接口获得
						isShowProgressTips: 0, // 默认为1，显示进度提示
						success: function (res) {
							var serverId = res.serverId; // 返回图片的服务器端ID
							$('#serverId', page).val(serverId);
							saveMemberInfo();
						},
						fail: function (res) {
							$.alert(JSON.stringify(res));
						}
					});
				} else {
					saveMemberInfo();
				}
			} catch (e){
				saveMemberInfo();
			}
        });

        //积分明细页无限滚动
        function addItems(data,credit_title) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                html += '<li>'+
                '<div class="item-content"><div class="item-inner">'+
                '<div class="item-title-row">'+
                '<div class="font7">'+item['remark']+'</div>'+
                '<div class="credit_time font6">'+item['createtime']+'</div></div>'+
                '<div class="credit_num credit_color text-strong">'+item['num']+'<span class="font6">'+credit_title+'</span></div>'+
                '</div></div></li>';
            }
            $('.log-list ul', page).append(html);
        }
        var loading = false;
        $(page).on('infinite', '.infinite-scroll',function() {
			if (loading) return;
            loading = true;
			var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');

            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var credit_title = $(t).attr('data-credit-type');
                        addItems(response,credit_title);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll', page));
                        $('.infinite-scroll-preloader', page).remove();
                    }
                }
            });
        });
    });

    //mycredit
    $(document).on('pageInit', ".superpage_mycredit", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        //积分明细页无限滚动
        function addItems(data,credit_title) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                html += '<li>'+
                '<div class="item-content">'+
                '<div class="item-inner">'+
                '<div class="row no-gutter creditlog_wrap">'+
                '<div class="col-75">'+
                '<div class="item-title-row">'+
                '<div class="font7 text-overflow">'+item['remark']+'</div>'+
                '<div class="credit_time font6">'+item['createtime']+'</div>'+
                '</div></div>'+
                '<div class="col-25 text-overflow text-right">'+
                '<div class="credit_num credit_color text-strong">'+
                item['num']+'<span class="font6">'+credit_title+'</span>'+
                '</div></div></div></div></div></li>'
            }
            $('.log-list ul', page).append(html);
        }
        var loading = false;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');

            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var credit_title = $(t).attr('data-credit-type');
                        addItems(response,credit_title);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll', page));
                        $('.infinite-scroll-preloader', page).remove();
                    }
                }
            });
        });
    });

    //creditrank
    $(document).on('pageInit', ".superpage_creditrank", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
        if (typeof _creditrank_type_button != undefined) {
            $('.creditrank_type', page).click(function () {
                $.actions(_creditrank_type_button);
                return false;
            });
        }
    });

    //cart
    $(document).on('pageInit', ".superpage_cart", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
    });

    //exchangerank
    $(document).on('pageInit', ".superpage_exchangerank", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
    });

    //exchangelog
    $(document).on('pageInit', ".superpage_exchangelog", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
        function addItems(data) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                html += '<li>';
                html += '<div class="item-content">';
                html += '<div class="item-media">';
                html += '<img src="'+item['avatar']+'" onerror="this.src=\'../app/resource/images/heading.jpg\'" style=\'width: 1.8rem;\'>';
                html += '</div>';
                html += '<div class="item-inner">';
                html += '<div class="row">';
                html +=     '<div class="col-70">';
                html +=         '<div class="item-title-row">';
                html +=             '<div class="item-title font7">'+item['nickname']+'</div>';
                html +=         '</div>';
                html +=         '<div class="item-subtitle font5">'+item['dateline']+'</div>';
                html +=     '</div>';
                html +=     '<div class="col-30 pull-right">';
                html +=         '<a href="#">'+item['credit']+item['credit_title']+'</a>';
                html +=     '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</li>';
            }
            $('.exchangelog_wrap ul', page).append(html);
        }
        var loading = false;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');

            pageno = parseInt(pageno) + 1;
            url += '&page=' + pageno;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function (response) {
                    loading = false;
                    if (response.length > 0) {
                        addItems(response);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll', page));
                        $('.infinite-scroll-preloader', page).remove();
                    }
                }
            });
        })
    });

    //service
    $(document).on('pageInit', ".superpage_service", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
    });

    //task
    $(document).on('pageInit', ".superpage_task", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
        //切换任务类型
        $('.buttons-tab a', page).click(function(){
            $.showIndicator();
        });
        //领取、完成任务
        $('.btn_task', page).click(function(){
            var t = this;
            if ($(t).attr('data-flag') == '1') {
                return;
            }
            $.showIndicator();
            $(t).attr('data-flag', '1');
            var url = $(t).attr('data-url');
            var status = $(t).attr('data-status');
            var type = $(t).attr('data-type');
            var builtin = $(t).attr('data-builtin');
            var name = $(t).attr('data-name');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(resp) {
                    $.hideIndicator();
                    if (resp.errno == '0') {    //成功
                        if (type == 2 || type == 3) {//日常任务 活动任务
                            $(t).unbind('click').attr('href', resp.data.url).html('做任务');
                            $.toast('领取成功，跳转中...');
                            if (resp.data.url) {
                                setTimeout(function(){
                                    if (builtin == '0') {
                                        window.location.href = resp.data.url;
                                    } else {
                                        $.router.loadPage(resp.data.url, true);
                                    }
                                }, 2000);
                            }
                        } else if (type == 1) {//新手任务
                            if (status == '') {  //领取成功
                                var data_url = url.replace('act=get','act=complete');
                                $(t).attr('data-status', '0').attr('data-url', data_url).attr('data-flag', '0').removeClass('disabled button-warning').html('点击领取');
                                $.toast(resp.errmsg);
                                if (resp.data.url) {
                                    setTimeout(function(){
                                        $.router.loadPage(resp.data.url, true);
                                    }, 2000);
                                }
                            }
                            if (status == '0') {        //完成成功
                                $(t).attr('data-flag', '1').addClass('disabled').html('已完成');
                                $.toast(resp.data.award);
                            }
                        }
                    } else {
                        $.toast(resp.errmsg);
                        if (resp.errno == '4') {    //未登录
                            window.location.href = window.sysinfo.loginurl;
                        } else if (resp.errno == '1007') {     //未完成
                            $(t).attr('data-flag', '0');
                            if (resp.data.url) {
                                setTimeout(function(){
                                    if (name =='superman_creditmall_task6') {
                                        window.location.href = resp.data.url;
                                    } else {
                                        $.router.loadPage(resp.data.url, true);
                                    }
                                }, 2000);
                            }
                        }
                    }
                }
            });
        });
    });

    //help
    $(document).on('pageInit', ".superpage_help", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
    });

    //auction
    function superpage_auction_item_list(data, finished) {
        var html = '', item;
        for (var i=0; i<data.length; i++) {
            item = data[i];
            html += '<li class="item-content">';
            if ((item['first'] && !finished) || item['status'] == 1) {
                html += '   <div class="item-inner font6 text-strong color-danger" data-id="'+item['id']+'" data-credit="'+item['credit']+'">';
            } else {
                html += '   <div class="item-inner font6" data-id="'+item['id']+'" data-credit="'+item['credit']+'">';
            }
            html += '       <div class="auction_user">';
            html += '           <div class="item-media text-left">';
            html += '           <img src="'+tomedia(item['member']['avatar'])+'" onerror="this.src=\'resource/images/heading.jpg\'"/>';
            html += '           <span class="text-overflow">'+item['member']['nickname']+'</span>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="auction_credit text-center text-overflow">'+item['credit']+item['credit_title']+'</div>';
            html += '       <div class="auction_time text-right text-overflow">'+item['dateline']+'</div>';
            html += '   </div>';
            html += '</li>';
        }
        return html;
    }
    //下拉刷新
    $(document).on('refresh', '.superpage_auction .pull-to-refresh-content',function(e) {
        var finished = $(e.target).attr('data-finished');
        if (finished) {
            $.pullToRefreshDone('.superpage_auction .pull-to-refresh-content');
            return;
        }
        var firstItem = null;
        $(e.target).find('.item_list li .item-inner').each(function(){
            if ($(this).hasClass('text-strong color-danger')) {
                firstItem = $(this);
                return false;
            }
        });
        var last_id = firstItem?firstItem.attr('data-id'):0;
        var timeout = $(e.target).attr('data-refresh-time')?$(e.target).attr('data-refresh-time'):0;
        $.ajax({
            url: window.location.href+'&load=infinite',
            type: 'post',
            timeout: timeout,
            data: 'last_id='+last_id,
            success: function(response) {
                if (response.errno == '0') {
                    if (response.data.length > 0) {
                        var html = superpage_auction_item_list(response.data, finished);
                        if (html != '') {
                            if (firstItem) {
                                $(firstItem).removeClass('text-strong color-danger');
                            }
                            $(e.target).find('.item_list').prepend(html);
                        }
                    }
                } else if (response.errno == '6001') { //任务已结束
                    $.showIndicator();
                    window.location.reload();
                }
                $.pullToRefreshDone('.superpage_auction .pull-to-refresh-content');
            }
        });
    });
    $(document).on('pageInit', ".superpage_auction", function(e, id, page) {
        wxMenu.hide();
        var displayAuction = {
            init: function(){
                var refreshItems = {
                    timerId: null,
                    init: function(){
                        //自动下拉刷新
                        if ($('.auction_display', page).attr('data-auto-refresh') == '1' &&
                            $('.btn_bid').length > 0) {
                            var timeout = $('.auction_display', page).attr('data-refresh-time');
                            if (!timeout) {
                                timeout = 10000;
                            }
                            refreshItems.timerId = setInterval(function(){
                                refreshItems.run();
                            }, timeout);
                        }
                    },
                    run: function(){
                        $.pullToRefreshTrigger('.superpage_auction .pull-to-refresh-content');
                    },
                    clear: function(){
                        clearInterval(refreshItems.timerId);
                    }
                };
                refreshItems.init();
                //无限滚动
                $(page).on('infinite', '.infinite-scroll',function() {
                    var t = this;
                    if ($(t).attr('data-flag') == '1') {
                        return;
                    }
                    $(t).attr('data-flag', '1');
                    var finished = $(t).attr('data-finished');
                    var pageno = $(t).attr('data-page');
                    pageno = parseInt(pageno) + 1;
                    $.ajax({
                        url: window.location.href+'&load=infinite',
                        data: 'page='+pageno,
                        dataType: 'json',
                        success: function(response) {
                            $(t).attr('data-flag', '0');
                            if (response.data.length > 0) {
                                var html = superpage_auction_item_list(response.data, finished);
                                $('.item_list', page).append(html);
                                $(t).attr('data-page', pageno);
                                $.refreshScroller();
                            } else {
                                $.detachInfiniteScroll($('.infinite-scroll', page));
                                $('.infinite-scroll-preloader', page).remove();
                                $('.nodata', page).show();
                            }
                        }
                    });
                });
                if ($('.btn_bid', page).length > 0) {
                    $('.btn_bid', page).click(function(){
                        var t = this;
                        if ($(t).hasClass('disabled')) {
                            return;
                        }
                        $(t).addClass('disabled');
                        //未关注不允许兑换
                        if ($(t).attr('data-exchange') == '0') {
                            $.modal({
                                title:  '温馨提示',
                                text: $(t).attr('data-subscribe-tips'),
                                buttons: [
                                    {
                                        text: '点击关注',
                                        bold: true,
                                        onClick: function(){
                                            $.showIndicator();
                                            window.location.href = $(t).attr('data-subscribe-url');
                                        }
                                    }
                                ]
                            });
                            return;
                        }
                        var credit = 0;
                        $('.item_list li .item-inner').each(function(){
                            var val = parseFloat($(this).attr('data-credit'));
                            if (val > credit) {
                                credit = val;
                                return false;
                            }
                        });
                        var credit_title = $(t).attr('data-credit-title');
                        var auction_credit = parseFloat($(t).attr('data-auction-credit'));
                        var start_credit = parseFloat($(t).attr('data-start-credit'));
                        if (credit == 0) {
                            credit += start_credit>0?start_credit:1;
                        } else {
                            credit += auction_credit;
                        }
                        credit = credit.toString().replace('.00', '');
                        var text = '<p class="buttons-row">'+
                                    '<a href="#" class="button btn_minus_credit"><span class="iconfont">&#xe61a;</span></a>' +
                                    '<input type="tel" name="bid_credit" value="'+credit+'"/>' +
                                    '<a href="#" class="button btn_plus_credit"><span class="iconfont">&#xe61b;</span></a>&nbsp;'+credit_title+
                                    '</p>';
                        if (auction_credit > 0) {
                            text += '<p>加价幅度'+auction_credit+credit_title+'</p>';
                        }
                        //出价
                        $.modal({
                            title:  '请填写出价',
                            text: text,
                            extraClass:'modal_auction',
                            buttons: [
                                {
                                    text: '取消',
                                    bold: true,
                                    onClick: function(){
                                        $(t).removeClass('disabled');
                                    }
                                },
                                {
                                    text: '确认',
                                    bold: true,
                                    onClick: function() {
                                        var bid_credit = $('input[name=bid_credit]');
                                        if (bid_credit.val() == '' || !/^[0-9]+.?[0-9]*$/.test(bid_credit.val())) {
                                            $.toast('出价不合法');
                                            $(t).removeClass('disabled');
                                            return;
                                        }
                                        $.showIndicator();
                                        $.ajax({
                                            url: $(t).attr('data-bid-url'),
                                            type: 'post',
                                            data: 'credit='+bid_credit.val(),
                                            success: function(response) {
                                                $.hideIndicator();
                                                $(t).removeClass('disabled');
                                                if (response.errno == '0') {
                                                    $.toast(response.errmsg);
                                                    refreshItems.run();
                                                } else if (response.errno == '4') {
                                                    $.toast(response.errmsg);
                                                    window.location.href = window.sysinfo.loginurl;
                                                } else if (response.errno == 8000
                                                    || response.errno == 8001
                                                    || response.errno == 8002
                                                    || response.errno == 8003
                                                    || response.errno == 8004) {
                                                    $.modal({
                                                        title:  '温馨提示',
                                                        text: '<div class="tabs">'+
                                                        '<div>您的操作受限，请联系管理员！</div>'+
                                                        '<div class="color-gray font7">错误码：'+response.errno+'</div>'+
                                                        '</div>',
                                                        buttons: [
                                                            {
                                                                text: '关闭',
                                                                bold: true
                                                            },
                                                        ]
                                                    });
                                                } else {
                                                    $.toast(response.errmsg);
                                                }
                                            }
                                        });
                                    }
                                }
                            ]
                        });
                        if ($('.btn_minus_credit').length > 0) {
                            $('.btn_minus_credit').click(function(){
                                var credit = parseFloat($(this).next().val()) - 1;
                                if (credit <= 0) {
                                    credit = 1;
                                }
                                $(this).next().val(credit);
                            });
                        }
                        if ($('.btn_plus_credit').length > 0) {
                            $('.btn_plus_credit').click(function(){
                                var credit = parseFloat($(this).prev().val()) + 1;
                                $(this).prev().val(credit);
                            });
                        }
                    });
                }
                if ($('.reload', page).length > 0) {
                    $('.reload', page).click(function(){
                        $.showIndicator();
                        window.location.reload();
                    });
                }
            }
        };
        if ($('.auction_display').length > 0) {
            displayAuction.init();
        }
    });

    //checkout
    $(document).on('pageInit', ".superpage_checkout", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        $('.btn_checkout', page).click(function () {
            $.showIndicator();
            var t = $(this);
            t.addClass('disabled').attr('disabled', true);
            var remark = $('textarea[name=remark]', page).val();
            var orderid = t.attr('data-orderid');
            var url = t.attr('data-url');
            var data = '';
            data += 'orderid='+orderid;
            data += '&remark='+remark;
            $.ajax({
                type: 'post',
                url: url +'&act=check',
                data: data,
                dataType: 'json',
                success: function (resp) {
                    $.hideIndicator();
                    $.toast(resp.errmsg);
                    setTimeout(function(){
                        window.location.href=url+'&orderid='+orderid;
                    }, 2000);
                }
            })
        });
    });

    $(document).on('pageInit', '.page', function(e, id, page) {
        if ($('.redirect').length) {
            var href = $('.redirect').attr('href');
            if (href != '') {
                setTimeout(function(){
                    $.showIndicator();
                    window.location.href = href;
                }, 2000);
            }
        }
        //global hook
        /*if (window.sysinfo.global_hook_url) {
            $.ajax({
                type: 'get',
                url: window.sysinfo.global_hook_url,
                success:function(){}
            });
        }*/
    });

    $.init();
});
