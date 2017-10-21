$(function () {
    if ($().lazyload !== undefined) {
        $("img[original]").lazyload({
            effect: "fadeIn",
            threshold: 200
        });
        
        $("img[data-original]").lazyload({
            effect: "fadeIn",
            threshold: 200
        });
    }


    $("img[data-src]").each(function (i, e) {
        if ($(e).attr("src") == undefined || $(e).attr("src") == '') {
            if ($(e).attr("data-src").indexOf("photo.store.qq.com") < 0) {
                $(e).attr("src", $(e).attr("data-src"));
            }
        }
    });

    if ($('.footer').length > 0) {
        if ($(document.body).height() + 60 < window.innerHeight) {
            $('.footer').css("position", "fixed");
        }

        if ($('.footer-toolbar').length > 0) {
            $(".footer").css("margin-bottom", $(".footer-toolbar").height());
            $(".footer-retain").height($(".footer-retain").height() - $(".footer-toolbar").height());

            if ($('.footer-toolbar .search-toggle-up').length > 0) {
                $('.footer-toolbar .search-toggle-up').bind("click", function () {
                    $('.footer-toolbar .menu-bar').hide();
                    $('.footer-toolbar .search-bar').show();
                });
                $('.footer-toolbar .search-toggle-down').bind("click", function () {
                    $('.footer-toolbar .menu-bar').show();
                    $('.footer-toolbar .search-bar').hide();
                });
            }
        }
    }

    flipsnap();
    getmore();
    scrollEvent();
    touchEvent();
    safariStandalone();
});

function flipsnap() {
    if ($('.flip-snap').length > 0) {
        var span = $(window).width() - $(".flip-snap").width();
        var size = $(".flip-snap").children().size();
        var padding = 20;

        if (span > size && size > 0) {
            padding = padding + span / size;

            $(".flip-snap li").css("padding-left", padding / 2);
            $(".flip-snap li").css("padding-right", padding / 2);
        } else {
            var fs = Flipsnap('.flip-snap', {
                distance: 0,
                padding: padding,
                disableTouch: false,
                movingcallback: tipshide
            });

            var curi = $(".nav-menu-list .ui-btn-active").index() - 2;

            if (curi > 0) {
                fs.moveToPoint(curi);
            }

//            var showed = window.sessionStorage.getItem('navmenutips' + g_uid);

//            if (showed == undefined || showed != "1") {
//                $(".nav-menu-tips").css("background-image", "linear-gradient(to right," + $(".nav-menu").css("background-color") + ",#2e3132");
//                $(".nav-menu-tips").show();
//            }

            function tipshide() {
//                if ($(".nav-menu-tips").length > 0) {
//                    $(".nav-menu-tips").remove();

//                    window.sessionStorage.setItem('navmenutips' + g_uid, '1');
//                }
            }
        }
    }
}

//当前页打开，不跳转safari
function safariStandalone() {
    // Mobile Safari in standalone mode
    if (("standalone" in window.navigator) && window.navigator.standalone) {
        // If you want to prevent remote links in standalone web apps opening Mobile Safari, change 'remotes' to true
        var noddy,
        remotes = false;
        document.addEventListener('click', function (event) {
            noddy = event.target;
            //Bubble up until we hit link or top HTML element. Warning: BODY element is not compulsory so better to stop on HTML
            while (noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
                noddy = noddy.parentNode;
            }
            if ('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes)) {
                event.preventDefault();
                document.location.href = noddy.href;
            }
        }, false);
    }
}

function getmore() {
    $('#get-more-btn').click(function () {
        $('#get-more-btn').text('正在载入...');

        var url = "/interface/data_share/json_article_list.php?uid=" + g_uid + "&cid=" + g_cid + "&mid=" + g_minid + "&page=" + g_page;
        console.log(url);
        if (typeof (g_st) !== "undefined" && g_st !== undefined) {
            url = url + "&st=" + g_st;
        }

        if (typeof (g_cc) !== "undefined" && g_cc !== undefined) {
            url = url + "&cc=" + g_cc;
        }

        $.getJSON(url, function (data) {
            if (data.ret == 200) {
                var html = '';

                if (typeof(g_list) == "undefined" || g_list == undefined || g_list == 0) {
                    $.each(data.list, function(key, val) {
                        html += '<div class="article-item">';
                        html += '<a href="' + val.url + '" title="' + val.title + '">';
                        html += '   <div class="item-cover"><img src="' + val.pic + '"></div>';
                        html += '   <div class="item-summary"><div class="item-title">' + val.title + '</div><div class="item-text">' + val.time + '</div></div>';
                        html += '</a>';
                        html += '</div>';
                    });
                } else {
                    $.each(data.list, function (key, val) {
                        html += '<div class="article-entity">▪&nbsp;&nbsp;<a href="' + val.url + '">' + val.title + '<span class="publishtime">' + val.time + '</span></a></div>';
                    });
                }

                $('.list-container').append(html);

                g_minid = data.minid;
                g_page = g_page + 1;

                if (data.list.length < 20) {
                    $('.get-more').hide();
                } else {
                    $('#get-more-btn').text('显示下20篇');
                }
            } else {
                $('#get-more-btn').text('获取失败，点击重试');
            }
        }).fail(function () {
            $('#get-more-btn').text('获取失败，点击重试');
        });
    });
}

function scrollEvent() {
    $(window).bind("scroll", function () {
        if ($('.nav-menu-container').length > 0) {
            var offsetSide = $(document).scrollTop() - $(".nav-menu-container").position().top;

            if (offsetSide < 0) {
                $(".nav-menu-container>div").css("position", "static");
            } else {
                $(".nav-menu-container>div").css("position", "fixed");
            }
        }

        var offsetFooter = $(document).height() - $(document).scrollTop() - window.innerHeight;

        if (offsetFooter < 200) {
            $('.home-btn').show();
            $('.footer-toolbar').show();
        }
    });
}

function touchEvent() {
    if ($('.home-btn').length <= 0 && $('.footer-toolbar').length <= 0) {
        return;
    }

    var startY = 0;
    var eventTypes = ['touch', 'mouse'];
    var events = {
        start: {
            touch: 'touchstart',
            mouse: 'mousedown'
        },
        move: {
            touch: 'touchmove',
            mouse: 'mousemove'
        },
        end: {
            touch: 'touchend',
            mouse: 'mouseup'
        }
    };

    eventTypes.forEach(function (type) {
        document.addEventListener(events.start[type], handleEvent, false);
    });

    function handleEvent(event) {
        switch (event.type) {
            // start                         
            case events.start.touch:
                touchStart(event, 'touch');
                break;
            case events.start.mouse:
                touchStart(event, 'mouse');
                break;
            // move                         
            case events.move.touch:
                touchMove(event, 'touch');
                break;
            case events.move.mouse:
                touchMove(event, 'mouse');
                break;
            // end                         
            case events.end.touch:
                touchEnd(event, 'touch');
                break;
            case events.end.mouse:
                touchEnd(event, 'mouse');
                break;
        }
    }

    function touchStart(event, type) {
        document.addEventListener(events.move[type], handleEvent, false);
        document.addEventListener(events.end[type], handleEvent, false);

        startY = getPage(event, 'pageY');
    }

    function touchMove(event, type) {
        var spanY = getPage(event, 'pageY') - startY;

        if (spanY > 10) {
            $('.home-btn').show();
            $('.footer-toolbar').show();
        } else if (spanY < -10) {
            var offsetFooter = $(document).height() - $(document).scrollTop() - window.innerHeight;

            if (offsetFooter > 200) {
                $('.home-btn').hide();
                $('.footer-toolbar').hide();
            }
        }
    }

    function touchEnd(event, type) {
        document.removeEventListener(events.move[type], handleEvent, false);
        document.removeEventListener(events.end[type], handleEvent, false);
    }

    function getPage(event, page) {
        return event.changedTouches ? event.changedTouches[0][page] : event[page];
    }
}