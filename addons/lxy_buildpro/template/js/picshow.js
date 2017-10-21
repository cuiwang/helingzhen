var FCAPP = FCAPP || {};
FCAPP.HOUSE = FCAPP.HOUSE || {};
FCAPP.HOUSE.PICSHOW = {
    CONFIG: {},
    RUNTIME: {
        opacity: 0
    },
    init: function() {
        var R = PICSHOW.RUNTIME;
        if (!R.binded) {
            R.binded = true;
            PICSHOW.initElements(R);
            PICSHOW.bindEvents(R);
            R.template1 = FCAPP.Common.escTpl($('#template1').html());
            R.template2 = FCAPP.Common.escTpl($('#template2').html());
            R.template3 = FCAPP.Common.escTpl($('#template3').html());
            R.w = document.documentElement.clientWidth;
            R.h = document.documentElement.clientHeight;
        }
        PICSHOW.loadData();
        var id = '';
        if (window.gQuery && gQuery.id) {
            id = gQuery.id;
        }
        window.sTo = PICSHOW.scrollTo;
        FCAPP.Common.loadShareData(id);
        FCAPP.Common.hideToolbar();
    },
    initElements: function(R) {
        R.scroller = $('#scroller');
        R.scrollList = $('#scrollList');
        R.scrollTips = $('#scrollTips');
        R.scroller1 = $('#scroller1');
        R.scrollPic = $('#scrollPic');
        R.scrollPicLi = $('#scrollPic li');
        R.closeBtn = $('#photoClick');
        R.picName = $('#picName');
        R.popMask = $('#popMask');
        R.scrollWidth = [];
        R.scrollTitle = [];
        R.scrollPagesX = [];
        R.picSize = [];
        R.imgDom = [];
        R.picIdx = 0;
        R.thubIdx = 0;
        R.reduceSize = 0;
        R.loadedThub = {};
        R.view = 'thub';
    },
    bindEvents: function(R) {
        $(window).resize(PICSHOW.resizeLayout);
        R.closeBtn.click(PICSHOW.closeSlidePics);
    },
    loadData: function() {
        window.showPics = PICSHOW.showPics;
        var datafile = window.gQuery && gQuery.id ? gQuery.id + '.': '',
        dt = new Date();
        datafile = datafile.replace(/[<>\'\"\/\\&#\?\s\r\n]+/gi, '');
        datafile += 'picshow.js?';
        
        $.ajax({
            url:location.href+'&type=getpic',
            dataType:'json',
            success: function(m) {
            	showPics(m);
            },error:function(){
            	FCAPP.Common.msg(true, {
                    msg: '无效的户型！'
                });	
            }
        });
        
        //showPics([{"title":"\u6e7e\u533a\u4e5d\u91cc","ps1":[{"type":"title","title":"\u6e7e\u533a\u4e5d\u91cc","subTitle":"\u6e7e\u533a\u4e5d\u91cc"},{"type":"img","name":"\u6e7e\u533a\u4e5d\u91cc","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1057\/image\/20131012\/20131012183434_79710.jpg","size":[437,450]},{"type":"img","name":"\u6e7e\u533a\u4e5d\u91cc1","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1057\/image\/20131012\/20131012183434_57293.jpg","size":[424,450]},{"type":"img","name":"\u6e7e\u533a\u4e5d\u91cc2","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1057\/image\/20131012\/20131012183434_21920.jpg","size":[467,450]},{"type":"img","name":"\u6e7e\u533a\u4e5d\u91cc3","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1057\/image\/20131012\/20131012183435_72856.jpg","size":[462,450]}],"ps2":[{"type":"img","name":"\u6e7e\u533a\u4e5d\u91cc4","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1057\/image\/20131012\/20131012183435_35690.jpg","size":[406,450]},{"type":"text","content":"\u4e5d\u91cc\u4e3a\u534e\u6da6\u7f6e\u5730\u6700\u9ad8\u7aef\u7684\u4ea7\u54c1\u7cfb\u5217\uff0c\u201c\u4e5d\u91cc\u201d\u7cfb\u5217\u5168\u90e8\u62e9\u5740\u4e8e\u90fd\u5e02\u4e2d\u7684\u94c2\u91d1\u7ea7\u4f18\u52bf\u533a\u4f4d\u6216\u62e5\u6709\u7a00\u7f3a\u81ea\u7136\u8d44\u6e90\u7684\u7edd\u7f8e\u98ce\u666f\u533a\uff0c\u5728\u4fdd\u62a4\u5f53\u5730\u5386\u53f2\u6587\u8109\uff0c\u81ea\u7136\u73af\u5883\uff0c\u98ce\u571f\u4eba\u60c5\u7684\u540c\u65f6\uff0c\u521b\u5efa\u5168\u65b0\u5c45\u4f4f\u7406\u5ff5\u548c\u751f\u6d3b\u65b9\u5f0f\uff0c\u5b9e\u73b0\u5c45\u4f4f\u4ef7\u503c\u6700\u5927\u5316\u3002"},{"type":"img","name":"\u6e7e\u533a\u4e5d\u91cc5","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1057\/image\/20131012\/20131012183435_30403.jpg","size":[421,450]},{"type":"img","name":"\u6e7e\u533a\u4e5d\u91cc6","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1057\/image\/20131012\/20131012183435_39974.jpg","size":[444,450]},{"type":"img","name":"\u6e7e\u533a\u4e5d\u91cc7","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1057\/image\/20131012\/20131012183435_57847.jpg","size":[415,450]}]},{"title":"\u77f3\u6885\u6e7e\u4e5d\u91cc\u4e00\u671f","ps1":[{"type":"title","title":"\u77f3\u6885\u6e7e\u4e5d\u91cc\u4e00\u671f","subTitle":"\u77f3\u6885\u6e7e\u4e5d\u91cc\u4e00\u671f"},{"type":"img","name":"\u6837\u677f\u623f7","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1055\/image\/20131012\/20131012182907_59867.jpg","size":[480,450]},{"type":"img","name":"\u6837\u677f\u623f1","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1055\/image\/20131012\/20131012182907_29190.jpg","size":[408,450]},{"type":"img","name":"\u6837\u677f\u623f2","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1055\/image\/20131012\/20131012182907_28884.jpg","size":[407,450]},{"type":"img","name":"\u6837\u677f\u623f3","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1055\/image\/20131012\/20131012182908_65036.jpg","size":[488,450]}],"ps2":[{"type":"img","name":"\u6837\u677f\u623f4","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1055\/image\/20131012\/20131012182908_45547.jpg","size":[484,450]},{"type":"text","content":"\u5ba4\u5185\u751f\u6d3b\u7a7a\u95f4\u4e0e\u5927\u81ea\u7136\u7684\u5b8c\u7f8e\u7ed3\u5408\uff0c\u7387\u5148\u91c7\u7528\u5168\u667a\u80fd\u5bb6\u7535\uff0c\u6cf3\u6c60\u5ead\u9662\u3001\u9732\u53f0......\u591a\u5143\u5316\u60c5\u8da3\u8bbe\u8ba1\u7a7a\u95f4\u3002"},{"type":"img","name":"\u6837\u677f\u623f5","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1055\/image\/20131012\/20131012182908_17643.jpg","size":[453,450]},{"type":"img","name":"\u6837\u677f\u623f6","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1055\/image\/20131012\/20131012182908_19360.jpg","size":[499,450]},{"type":"img","name":"\u6837\u677f\u623f","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1059\/image\/20131012\/20131012183554_33031.jpg","size":[402,450]}]},{"title":"\u94f6\u6ee9\u524d\u666f","ps1":[{"type":"title","title":"\u94f6\u6ee9\u524d\u666f","subTitle":"\u94f6\u6ee9\u524d\u666f"},{"type":"img","name":"beach_4","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1056\/image\/20131012\/20131012183243_10897.jpg","size":[486,450]},{"type":"img","name":"beach_1","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1056\/image\/20131012\/20131012183243_72120.jpg","size":[470,450]}],"ps2":[{"type":"img","name":"beach_2","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1056\/image\/20131012\/20131012183243_81851.jpg","size":[408,450]},{"type":"text","content":"\u5929\u7136\u6d01\u51c0\u7684\u6c99\u6ee9\uff0c\u6d77\u6c34\u7eaf\u51c0\u800c\u900f\u660e\uff0c\u62e5\u6709\u6781\u9ad8\u7684\u80fd\u89c1\u5ea6\uff0c\u662f\u4e2d\u56fd\u5357\u65b9\u73af\u5883\u6700\u4f18\u7f8e\u6700\u751f\u6001\u7684\u6d77\u6ee9\u4e4b\u4e00\u3002\u793e\u533a\u5185\u5f15\u5165\u7eff\u9053\uff0c\u7901\u77f3\u516c\u56ed\uff0c\u7ea2\u6811\u516c\u56ed\u7b49\u539f\u751f\u6001\u65c5\u6e38\u8d44\u6e90\u3002"},{"type":"img","name":"beach_3","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1056\/image\/20131012\/20131012183243_83920.jpg","size":[488,450]}]},{"title":"\u6d77\u8bed\u5c71\u6797","ps1":[{"type":"title","title":"\u6d77\u8bed\u5c71\u6797","subTitle":"\u6d77\u8bed\u5c71\u6797"},{"type":"img","name":"\u6837\u677f\u623f17","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183525_63565.jpg","size":[406,450]},{"type":"img","name":"\u6837\u677f\u623f8","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183525_49739.jpg","size":[441,450]},{"type":"img","name":"\u6837\u677f\u623f9","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183525_92339.jpg","size":[480,450]},{"type":"img","name":"\u6837\u677f\u623f10","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183525_42417.jpg","size":[427,450]},{"type":"img","name":"\u6837\u677f\u623f11","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183526_98602.jpg","size":[457,450]}],"ps2":[{"type":"img","name":"\u6837\u677f\u623f12","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183526_13297.jpg","size":[498,450]},{"type":"text","content":"\u5ba4\u5185\u751f\u6d3b\u7a7a\u95f4\u4e0e\u5927\u81ea\u7136\u7f8e\u666f\u7684\u5b8c\u7f8e\u7ed3\u5408\uff0c\u5ba4\u5185\u8bbe\u8ba1\u7684\u8d85\u5927\u5c3a\u5ea6\u4e0e\u8d85\u9ad8\u4f7f\u7528\u7387\uff0c\u8d85\u8fc7\u4e94\u661f\u7ea7\u7684\u9152\u5e97\u7684\u5962\u8c6a\u88c5\u4fee\u3002"},{"type":"img","name":"\u6837\u677f\u623f13","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183526_96862.jpg","size":[442,450]},{"type":"img","name":"\u6837\u677f\u623f14","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183526_24985.jpg","size":[491,450]},{"type":"img","name":"\u6837\u677f\u623f15","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183526_85637.jpg","size":[450,450]},{"type":"img","name":"\u6837\u677f\u623f16","img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1058\/image\/20131012\/20131012183526_56411.jpg","size":[406,450]}]}]);
    },
    resizeLayout: function() {
        var R = PICSHOW.RUNTIME,
        p = R.picSize,
        pages = R.scrollPagesX,
        num = 0;
        R.w = document.documentElement.clientWidth;
        R.h = document.documentElement.clientHeight;
        R.widthTime = window.innerWidth > window.innerHeight && R.h < 350 ? 2 : 1;
        if (R.view == 'thub') {
            if (R.widthTime > 1) {
                R.scroller.addClass('photo_wide');
            } else {
                R.scroller.removeClass('photo_wide');
            }
            num = R.widthTime == 2 ? R.reduceSize: pages[pages.length - 1];
            R.scrollList.css({
                width: num + 'px'
            });
            if (R.scrollerScroll) {
                var t = R.thubIdx,
                el = $('#picshow' + t);
                PICSHOW.showSlideText(Math.max(t - 1, 0));
                R.scrollerScroll.refresh();
                R.isRunning = true;
                if (el.length) {
                    try {
                        R.scrollerScroll.scrollToElement(el[0], 40);
                    } catch(e) {}
                }
                setTimeout(function() {
                    delete R.isRunning;
                },
                50);
            } else {
                PICSHOW.thubScrollCB();
            }
        } else {
            var s1 = R.scroller1Scroll;
            R.scrollPicLi.addClass('noLoading');
            R.scrollPicLi.css({
                width: R.w + 'px'
            });
            R.scrollPic.css({
                width: p.length * R.w + 'px',
                height: R.h + 'px'
            });
            s1.refresh();
            setTimeout(function() {
                s1.scrollToPage(s1.currPageX, 0);
            },
            100);
            $('img[load="false"]').css({
                width: R.w + 'px',
                height: R.h + 'px'
            });
            for (var i = 0,
            il = R.imgDom.length; i < il; i++) {
                if (R.imgDom[i]) {
                    PICSHOW.origImgLoad(R.imgDom[i]);
                }
            }
        }
    },
    slidePics: function(evt, idx) {
        evt = evt || window.event;
        var tar = evt.srcElement || evt.target,
        idx = !!idx ? idx: (tar && (tar.id || tar.idx) ? parseInt((tar.id || tar.idx).replace('thubImg', '')) : 0),
        idx = isNaN(idx) ? 0 : idx,
        R = PICSHOW.RUNTIME,
        img = $('#bImg' + idx),
        s = R.scroller1Scroll,
        p = R.picSize[idx];
        console.log(idx);
        R.view = 'big';
        R.scrollPic.show();
        R.scroller1.show();
        PICSHOW.resizeLayout();
        R.scrollPicLi.addClass('noLoading');
        R.popMask.show();
        s.refresh();
        if (img.length) {
            s.scrollToPage(idx);
            if (!p.loaded) {
                FCAPP.Common.loadImg(p.img, 'bImg' + idx, PICSHOW.origImgLoad);
            }
        }
        R.picName.html((idx + 1) + '/' + R.picSize.length + '  ' + p.name);
    },
    closeSlidePics: function() {
        var R = PICSHOW.RUNTIME,
        s = R.scrollerScroll,
        s1 = R.scroller1Scroll,
        p = R.picSize;
        R.scrollPic.hide();
        R.scroller1.hide();
        R.popMask.hide();
        R.view = 'thub';
        PICSHOW.resizeLayout();
        s.refresh();
        s.scrollToElement($('#thubImg' + p[s1.currPageX].idx)[0], 200);
    },
    showPics: function(data) {
        var R = PICSHOW.RUNTIME;
        var width = 0,
        totalWidth = 0;
        for (var i = 0, il = data.length; i < il; i++) {
            width = PICSHOW.calcWidth(data[i], i);
            R.scrollWidth.push(width);
            R.scrollTitle.push(data[i].title);
            totalWidth += width;
            R.scrollPagesX.push(totalWidth);
        }
        R.lastGroup = data[i - 1];
        PICSHOW.renderPics(data);
        PICSHOW.resizeLayout();
    },
    renderPics: function(data) {
        var R = PICSHOW.RUNTIME;
        PICSHOW.showSlideText(0);
        R.scrollList.html($.template(R.template1, {
            data: data
        }));
        R.scrollPic.html($.template(R.template3, {
            data: R.picSize,
            R: R
        }));
        R.scrollPicLi = $('#scrollPic li');
        setTimeout(function() {
            PICSHOW.initScroll('scroller', PICSHOW.thubScrollCB, false, true);
            FCAPP.Common.hideLoading();
            R.opacityInterval = setInterval(PICSHOW.showThubGroup, 50);
        },
        100);
        setTimeout(function() {
            R.scrollPic.css({
                width: R.w * R.picSize.length + 'px'
            });
            PICSHOW.initScroll('scroller1', PICSHOW.origScrollCB, true, false);
            R.picName.html(R.picSize[0].name);
            PICSHOW.loadThubImg(0);
            PICSHOW.loadThubImg(1);
        },
        500);
    },
    calcWidth: function(part, dataIdx) {
        var R = PICSHOW.RUNTIME,
        width = {},
        titleIdx = -1,
        textIdx = -1,
        textLoc = 0,
        titleLoc = 0,
        str, len, cw, idx = -1;
        for (var i in part) {
            var data = part[i];
            if (! (data instanceof Array) || !('length' in data)) {
                continue;
            }
            width[i] = 0;
            for (var j = 0,
            jl = data.length; j < jl; j++) {
                if (data[j].type == 'img') {
                    cw = Math.floor(data[j].size[0] * (150 / data[j].size[1]));
                    idx = R.picIdx++;
                    part[i][j].idx = idx;
                    R.picSize[idx] = {
                        name: data[j].name,
                        img: data[j].img,
                        idx: idx,
                        group: dataIdx,
                        w: data[j].size[0],
                        h: data[j].size[1]
                    };
                } else if (data[j].type == 'text') {
                    str = data[j].content;
                    len = str.length;
                    cw = Math.ceil(len * 140 / 78) + 22;
                    if (data[j].size) {
                        cw = Math.floor(data[j].size[0] * (150 / data[j].size[1]));
                    }
                    data[j].width = cw - 10;
                    textLoc = j;
                } else if (data[j].type == 'title') {
                    str = data[j].title.replace(/[a-z0-9]+/gi, '');
                    len = str.length + Math.ceil((data[j].title.length - str.length) / 2);
                    cw = 150;
                    data[j].width = cw;
                }
                cw += (j == 0 ? 2 : 10);
                width[i] += cw;
            }
        }
        cw = width.ps2 - width.ps1;
        if (cw > 0) {
            width.ps1 += cw;
            part.ps1[titleLoc].width += cw;
        } else {
            width.ps2 -= cw;
            part.ps2[textLoc].width -= cw;
        }
        width.ps2 += 24;
        width.ps1 += 24;
        R.reduceSize += width.ps1 + width.ps2 - 12;
        part.width = width.ps2;
        return part.width;
    },
    thubScrollCB: function() {
        if (!PICSHOW.RUNTIME.scrollerScroll || PICSHOW.RUNTIME.isRunning) {
            return;
        }
        var R = PICSHOW.RUNTIME,
        scroll = R.scrollerScroll,
        x = Math.abs(scroll.x),
        p = R.scrollPagesX,
        w = R.scrollWidth,
        tmp = 0;
        for (var il = p.length,
        i = il - 1; i > -1; i--) {
            tmp = (p[i] - w[i]) * R.widthTime - R.w / 2;
            if (x > tmp) {
                R.thubIdx = i + 1;
                PICSHOW.loadThubImg(i);
                PICSHOW.showSlideText(i);
                PICSHOW.loadThubImg(i + 1);
                break;
            }
        }
    },
    showThubGroup: function() {
        var R = PICSHOW.RUNTIME;
        if (R.opacity >= 1) {
            clearInterval(R.opacityInterval);
        } else {
            R.opacity += 0.05;
            R.scroller.css('opacity', R.opacity);
        }
    },
    showSlideText: function(i) {
        var R = PICSHOW.RUNTIME,
        t = R.scrollTitle,
        il = R.scrollPagesX.length,
        idx = 0,
        end = 0;
        if (i < 2) {
            idx = i;
            i = 0;
            end = i + 3;
        } else {
            idx = 1;
            if (il - i < 3) {
                end = il;
                if (i == il - 1) {
                    idx = 2;
                }
                i = il - 3;
            } else {
                i -= 1;
                end = i + 3;
            }
        }
        var data = {
            data: t.slice(i, end),
            idx: idx,
            start: (i + 1)
        };
        R.scrollTips.html($.template(R.template2, data));
    },
    loadThubImg: function(idx) {
        var R = PICSHOW.RUNTIME,
        p = R.picSize;
        if (!R.loadedThub[idx] && idx < p.length) {
            R.loadedThub[idx] = true;
            for (var j = 0,
            jl = p.length; j < jl; j++) {
                if (p[j].group == idx) {
                    FCAPP.Common.loadImg(p[j].img, 'thubImg' + p[j].idx, PICSHOW.thubImgLoad);
                }
            }
        }
    },
    thubImgLoad: function(img, i) {
        var R = PICSHOW.RUNTIME,
        idx = (img.idx || img.id).replace(/[^\d]+/g, '');
        img.height = 150;
        img.width = Math.floor(R.picSize[idx].w * (150 / R.picSize[idx].h));
        img.id = img.idx;
        img.onclick = PICSHOW.slidePics;
    },
    origScrollCB: function() {
        var R = PICSHOW.RUNTIME,
        scroll = R.scroller1Scroll,
        idx = scroll.currPageX,
        p = R.picSize[idx];
        $('#bLi' + idx).removeClass('noLoading');
        R.picName.html((idx + 1) + '/' + R.picSize.length + '  ' + p.name);
        if (!p.loaded) {
            FCAPP.Common.loadImg(p.img, 'bImg' + idx, PICSHOW.origImgLoad);
        }
    },
    origImgLoad: function(img) {
        if (!img) {
            return;
        }
        var R = PICSHOW.RUNTIME,
        idx = (img.idx || img.id).replace(/[^\d]+/g, ''),
        p = R.picSize[idx],
        cssText = '',
        mg = 0,
        sw = R.w - 10,
        sh = R.h,
        fw = 0,
        fh = 0;
        if ((p.h / p.w) < (sh / sw)) {
            fw = sw;
            fh = Math.ceil(p.h * sw / p.w);
            mg = Math.ceil((sh - fh) / 2);
            cssText = 'margin:' + mg + "px 0";
        } else {
            fh = sh;
            fw = Math.ceil(p.w * fh / p.h);
            mg = Math.ceil((sw - fw) / 2);
            cssText = 'margin:0 ' + mg + 'px 0 ' + mg + 'px';
        }
        img.id = 'bImg' + idx;
        if (!img.idx) {
            img.idx = img.id;
        } else {
            R.picSize[idx].loaded = true;
            R.imgDom[idx] = img;
        }
        img.width = fw;
        img.height = fh;
        img.style.cssText = cssText;
    },
    initScroll: function(id, cb, snap, momentum) {
        var R = PICSHOW.RUNTIME;
        R[id + 'Scroll'] = new iScroll(id, {
            zoom: false,
            snap: !!snap,
            momentum: !!momentum,
            hScrollbar: false,
            vScrollbar: false,
            fixScrollBar: false,
            hScroll: true,
            onScrollEnd: cb ||
            function() {}
        });
    },
    scrollTo: function(idx) {
        var R = PICSHOW.RUNTIME;
        try {
            R.scrollerScroll.scrollToElement($('#picshow' + idx)[0], 300);
        } catch(e) {}
    }
};
var PICSHOW = FCAPP.HOUSE.PICSHOW;
$(document).ready(PICSHOW.init);