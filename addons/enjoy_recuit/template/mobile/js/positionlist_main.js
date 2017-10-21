/* main.js */

// fast click
// $(function () {
//     window.FastClick && window.FastClick.attach(document.body);
// });

// ------- 列表选择 ------------
/*
var SelectsModule = {
    init: function () {
        var $dropdownWrapper = this.$dropdownWrapper = $("#dropdown");
        var $selectsWrapper = this.$selectsWrapper = $("header > .selects");
        var $selectGroup = this.$selectGroup = $(".select", $selectsWrapper);
        var $dropdownGroup = this.$dropdownGroup = $dropdownWrapper.children(".dropdown");
        var $dropdownCity = this.$dropdownCity = $(".city", $dropdownWrapper);
        // var $dropdownSalary = this.$dropdownSalary = $(".salary", $dropdownWrapper);
        var $dropdownTime = this.$dropdownTime = $(".time", $dropdownWrapper);
        var $dropdownCateogry = this.$dropdownCateogry = $(".occupation", $dropdownWrapper);
        var $body = this.$body = $("body");
        var $cover = this.$cover = $(".cover");
        // set $cover width height to fix short image bug
        var searchBarHeight = $(".search").height();
        $cover.height($body.height() - searchBarHeight).css("top", searchBarHeight);
        var $delimiters = this.$delimiters = $(".delimiter", $selectsWrapper);

        $dropdownCity.hiddenInput = $("input[name='city']", $dropdownWrapper);
        $dropdownCity.select = $(".select-city", $selectsWrapper);
        $dropdownCity.dafaultsText = $dropdownCity.select.text();

        // $dropdownSalary.hiddenInput = $("input[name='salary']", $dropdownWrapper);
        // $dropdownSalary.select = $(".select-salary", $selectsWrapper);
        // $dropdownSalary.dafaultsText = $dropdownSalary.select.text();

        $dropdownTime.hiddenInput = $("input[name='time']", $dropdownWrapper);
        $dropdownTime.select = $(".select-time", $selectsWrapper);
        $dropdownTime.dafaultsText = $dropdownTime.select.text();

        $dropdownCateogry.hiddenInput = $("input[name='occupation']", $dropdownWrapper);
        $dropdownCateogry.select = $(".select-occupation", $selectsWrapper);
        $dropdownCateogry.dafaultsText = $dropdownCateogry.select.text();

        this.eventsBinding();

        this.update();

    },

    eventsBinding: function () {
        var self = this;
        // show dropdown
        this.$selectsWrapper.on("click", ".select", function () {
            var $select = $(this),
                $targetDropdown = $($select.data("target"), self.$dropdownWrapper);
            if ($select.hasClass("selected")) {
                self.hideAll();
                return false;
            }
            $select
                .siblings(".select")
                .removeClass("selected animated flipInX")
                .end()
                .addClass("selected animated flipInX");
            self.updateDelimiter($select);
            self.$dropdownGroup.removeClass("selected");
            $targetDropdown.addClass("selected");
            self.$cover.show();
            return false;
        });

        // 
        this.$body.on("click", $.proxy(this.hideAll, this));
        this.$cover.on("click", $.proxy(this.hideAll, this));

        // choose 
        this.$dropdownGroup.on("click", "li", function () {
            var $this = $(this);
            $this
                .siblings()
                .removeClass("selected")
                .end()
                .addClass("selected");
            self.hideAll();
            self.update();
            // ********** 
            self.submit();
            // **********
            return false;
        });
    },

    submit: function () {
        this.$dropdownWrapper.submit();
    },

    updateDelimiter: function ($select) {
        this.$delimiters.removeClass("merge");
        if ($select) {
            $select.prev(".delimiter").addClass("merge");
            $select.next(".delimiter").addClass("merge");
        }
        return this;
    },

    hideAll: function () {
        this.$cover.hide();
        this.$dropdownGroup.removeClass("selected");
        this.$selectGroup.removeClass("selected animated flipInX")
    },

    update: function () {
        var self = this;
        $.each([this.$dropdownCity, this.$dropdownTime, this.$dropdownCateogry], update);

        function update(idx, $dropdown) {
            var $selected = $dropdown.find(".selected");
            if ($selected.length === 0) {
                $dropdown.find("li:first-child").addClass("selected");
                self.update();
                return;
            }
            $selected = $selected.eq(0);
            // update hidden input value
            $dropdown.hiddenInput.val(
                $selected.data("val"));
            // update text
            var text = $selected.is(":first-child") ? $dropdown.dafaultsText : $selected.text();
            $dropdown.select.find(".text").text(text);
        }
    },

    utility: {
        parseUrl: $.noop
    }

};  */

// ------- 下拉刷新选择 ------------
var PullDownModule = function () {

    var myScroll;
    var pullUpEl, pullUpL;
    var Upcount = 0;
    var loadingStep = 0; //加载状态0默认，1显示加载状态，2执行加载数据，只有当为0时才能再次加载，这是防止过快拉动刷新
    var $positionList = $(".position-list");
    var $header = $(".search");
    var defaults = {
        ajax: {
            url: "",
            type: "GET",
            dataType: "html"
        },
        loadingTime: 1000
    };
    var options = defaults;

    var config = function (configuration) {
        options = $.extend(true, {}, options, configuration);
        return this;
    };

    function atLeast(time /*millisec*/) {
        var $deferred = new $.Deferred();
        setTimeout(function () {
            $deferred.resolve();
        }, time);
        return $deferred.promise();
    }

    function successLoad(data) {
        var html = data[0].trim();
        if (html.length > 0) {
            $(html).hide().appendTo($positionList).fadeIn();
            pullUpL.html('上拉载入更多职位...');
            pullUpEl.removeClass('loading');
            // pullUpEl['class'] = pullUpEl.attr('class');
            pullUpEl.attr('class', '').hide();
            myScroll.refresh();
            loadingStep = 0;
            ++options.ajax.data.count;
        } else {
            pullUpL.html('没有更多职位啦');
            setTimeout(function () {
                pullUpEl.slideUp(function () {
                    pullUpEl.removeClass('loading');
                    myScroll.refresh();
//                    myScroll.destroy();
//                    myScroll = null;
                });
                loadingStep = 0;
            }, 2000);
        }
    }

    function failLoad() {
        //        console.error("ajax error");
        // todo: 处理加载失败
        pullUpL.html('载入失败，请稍后再试');
        setTimeout(function () {
            pullUpEl.slideUp(function () {
                pullUpEl.removeClass('loading');
                pullUpL.html('上拉载入更多职位...');
                myScroll.refresh();
            });
            loadingStep = 0;
        }, 2000);
        // pullUpEl['class'] = pullUpEl.attr('class');

    }

    function pullUpAction() { //上拉事件
        // send ajax
        $.when(request(), atLeast(options.loadingTime))
            .done(successLoad)
            .fail(failLoad);
    }

    function request() {
        return $.ajax(options.ajax);
    }

    function init() {

        // config ajax data
        config({
            ajax: {
                data: {
                    count: 1
                }
            }
        });


        pullUpEl = $('#pullUp');
        pullUpL = pullUpEl.find('.pullUpLabel');
        // pullUpEl['class'] = pullUpEl.attr('class');
        pullUpEl.attr('class', '').hide();

        myScroll = new IScroll('#content', {
            probeType: 2, //probeType：1对性能没有影响。在滚动事件被触发时，滚动轴是不是忙着做它的东西。probeType：2总执行滚动，除了势头，反弹过程中的事件。这类似于原生的onscroll事件。probeType：3发出的滚动事件与到的像素精度。注意，滚动被迫requestAnimationFrame（即：useTransition：假）。
            scrollbars: true, //有滚动条
            mouseWheel: true, //允许滑轮滚动
            fadeScrollbars: true, //滚动时显示滚动条，默认影藏，并且是淡出淡入效果
            bounce: true, //边界反弹
            interactiveScrollbars: true, //滚动条可以拖动
            shrinkScrollbars: 'scale', // 当滚动边界之外的滚动条是由少量的收缩。'clip' or 'scale'.
            click: true, // 允许点击事件
            keyBindings: true, //允许使用按键控制
            momentum: true // 允许有惯性滑动
        });
        //滚动时
        myScroll.on('scroll', function () {

            // $header.css("top", (this.y <= 0 ? this.y : 0));

            if (loadingStep == 0 && !pullUpEl.attr('class').match('flip|loading')) {
                if (this.y < (this.maxScrollY - 5)) {
                    //上拉刷新效果
                    // pullUpEl.attr('class', pullUpEl['class'])
                    if (loadingStep === 0) {
                        pullUpEl.show();
                        myScroll.refresh();
                        pullUpEl.addClass('flip');
                        pullUpL.html('松开载入更多...');
                        loadingStep = 1;
                    }
                }
            }
        });
        //滚动完毕
        myScroll.on('scrollEnd', function () {

            // $header.css("top", (this.y <= 0 ? this.y : 0));

            if (loadingStep == 1) {
                if (pullUpEl.attr('class').match('flip|loading')) {
                    pullUpEl.removeClass('flip').addClass('loading');
                    pullUpL.html('正在载入...');
                    loadingStep = 2;
                    pullUpAction();
                }
            }
        });
    }

    return {
        init: init,
        config: config
    };
}();

var documentTouchMoveController = function () {

    var $document = $("#scroller");

    function banTouchMove (e) {
        e.preventDefault();
    }

    var allow = function () {
        $document.off("touchmove", banTouchMove);
    };

    var prevent = function () {
        $document.on("touchmove", banTouchMove);
    };

    return {
        allow: allow,
        prevent: prevent
    };
}();

// documentTouchMoveController.prevent();

// document.addEventListener('touchmove', function (e) {
//     e.preventDefault();
// }, false);

// $(document).on("touchmove", function(e) {
//     // console.log(e);
//     console.log("document touchmove")
//     return false;
// });

window.onload = function () {
//    SelectsModule.init();
    PullDownModule.config({
        ajax: {
            url: $('#next-page').attr('href'),
            data: {
                "restype": "json"
            },
            type: "GET"
            // other request data
        }
    }).init();
};