var FCAPP = FCAPP || {},
myScroll;
FCAPP.HOUSE = FCAPP.HOUSE || {};
FCAPP.HOUSE.Picroll = {
    CONFIG: {
        zoomTime: 200
    },
    RUNTIME: {
        findCategory: false
    },
    init: function() {
        var R = Picroll.RUNTIME;
        if (!R.binded) {
            R.binded = true;
            Picroll.initElements(R);
            Picroll.bindEvents(R);
            R.detailState = 'hide';
            R.zoomed = false;
            R.template = FCAPP.Common.escTpl($('#template').html());
            R.imgDom = [];
            R.w = document.documentElement.clientWidth;
            R.h = document.documentElement.clientHeight;
            window.shareData = window.shareData || {};
            R.isWeixin = /MicroMessenger/.test(navigator.userAgent);
            if (R.isWeixin) {
                R.zoomBtn.hide();
                R.downBtn.hide();
                R.zoomDiv.hide();
            } else {
                R.zoomBtn.show();
                R.downBtn.show();
            }
        }
        Picroll.loadData();
        var id = '';
        window.shareData.linkKeep = '/Webestate/Housedata/pid/'+PID+'/wechatid/'+WECHATID;
        window.shareData.link = window.shareData.linkKeep;
        if (window.gQuery && gQuery.id) {
            id = gQuery.id;
            window.shareData.link += '&id=' + id;
            window.shareData.linkKeep += '&id=' + id;
        }
        if (window.gQuery && gQuery.houseid) {
            window.shareData.link += '&houseid=' + gQuery.houseid;
            window.shareData.linkKeep += '&houseid=' + gQuery.houseid;
        }
        FCAPP.Common.loadShareData(id);
        FCAPP.Common.hideToolbar();
    },
    initElements: function(R) {
        R.picDetail = $('#picDetail');
        R.detailCav = $('#detailContainer');
        R.closeBtn = $('a.btn_show_close');
        R.zoomBtn = $('a.btn_zoom_out');
        R.popTips = $('#popTips');
        R.downBtn = $('a.btn_down');
        R.picTank = $('#picTank');
        R.zoomDiv = $('#zoomDiv');
    },
    bindEvents: function(R) {
        R.closeBtn.click(Picroll.closePage);
        R.zoomBtn.click(Picroll.zoomPic);
        R.downBtn.click(Picroll.downPic);
        $(window).resize(Picroll.resizeLayout);
        FCAPP.Common.resizeLayout(R.popTips);
    },
    resizeLayout: function() {
        var R = Picroll.RUNTIME,
        t = R.loadSize.length;
        R.w = document.documentElement.clientWidth;
        R.h = document.documentElement.clientHeight;
        t *= R.w;
        R.picTank.css({
            width: t + 'px',
            height: R.h + 'px'
        });
        for (var i = 0,
        il = R.imgDom.length; i < il; i++) {
            Picroll.loadedImgProcess(R.imgDom[i]);
        }
        FCAPP.Common.resizeLayout(R.noticeDiv);
        try {
            myScroll.refresh();
        } catch(e) {}
    },
    closePage: function() {
        FCAPP.Common.jumpTo('house.html', {
            '#wechat_webview_type': 1
        },
        true);
    },
    switchDetail: function() {
        var R = Picroll.RUNTIME;
        if (R.detailState == 'hide') {
            R.detailState = 'show';
            R.detailCav.addClass('type_full');
            R.closeBtn.hide();
            R.zoomBtn.hide();
            R.downBtn.hide();
        } else {
            R.detailState = 'hide';
            R.detailCav.removeClass('type_full');
            if (!R.isWeixin) {
                R.closeBtn.show();
                R.zoomBtn.show();
                R.downBtn.show();
            }
        }
    },
    loadData: function() {
        window.showRooms = Picroll.showRooms;
        var datafile = window.gQuery && gQuery.id ? gQuery.id + '.': '',
        dt = new Date();
        datafile = datafile.replace(/[<>\'\"\/\\&#\?\s\r\n]+/gi, '');
        datafile += 'rooms.js?';
        $.ajax({
            url: location.href+'&typ=get',
            dataType: 'json',
            success:function(m){
            	showRooms(m);
            },
            error: function() {
                FCAPP.Common.msg(true, {
                    msg: '数据加载失败！'
                });
            }
        });
        /**
        setTimeout(function(){
        	showRooms({"banner":"http:\/\/www.weimob.com\/templates\/kindeditor\/attached\/48\/ac\/3f\/image\/20131012\/20131012181152_26209.jpg","rooms":[{"id":27,"name":"A1\u534a\u5c71\u89c2\u6d77\u72ec\u680b","desc":"\u77f3\u6885\u6e7e\u4e5d\u91cc\u4e00\u671f","rooms":"4\u623f1\u5385","floor":"\u4e09\u5c42","area":"\u7ea6230\/\u7ea6385\u5e73\u65b9\u7c73","simg":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1052\/image\/20131012\/20131012182622_30281.jpg","bimg":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1052\/image\/20131012\/20131012182622_30281.jpg","width":1600,"height":1600,"dtitle":["\u5efa\u7b51\u9762\u79ef\uff1a\u7ea6230\/\u7ea6385\u5e73\u65b9\u7c73"],"dlist":["\u534a\u5c71\u6258\u6d77\u666f\u89c2\u89c6\u91ce\uff0c\u5c06\u6e7e\u533a6\u516c\u91cc\u56fd\u9645\u6d77\u5cb8\u7ebf\u5c3d\u6536\u773c\u5e95\uff1b\u4e09\u5c42\u72ec\u680b\u8bbe\u8ba1\uff0c\u5bbd\u666f\u9633\u53f0\u642d\u914d\u79c1\u5c5e\u5ead\u9662\u3002\u9610\u8ff0\u6700\u4f18\u7f8e\u6ee8\u6d77\u5ea6\u5047\u98ce\u60c5\u3002"],"pics":[{"img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1052\/image\/20131012\/20131012182622_83394.jpg","width":960,"height":960,"name":"\u7b2c\u4e00\u5c42"},{"img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1052\/image\/20131012\/20131012182622_77041.jpg","width":960,"height":960,"name":"\u7b2c\u4e8c\u5c42"}],"full3d":[{"name":"\u5ba4\u5185","list":[{"name":"\u5ba4\u5185","url":"\/pfid\/22"}],"bimg":"http:\/\/imgcache.gtimg.cn\/lifestyle\/app\/wx_house\/pic\/bg_3.jpg"},{"name":"\u82b1\u56ed","list":[{"name":"\u82b1\u56ed","url":"\/pfid\/21"}],"bimg":"http:\/\/imgcache.gtimg.cn\/lifestyle\/app\/wx_house\/pic\/bg_3.jpg"},{"name":"\u5916\u666f","list":[{"name":"\u5916\u666f","url":"\/pfid\/20"}],"bimg":"http:\/\/imgcache.gtimg.cn\/lifestyle\/app\/wx_house\/pic\/bg_3.jpg"}]},{"id":28,"name":"B1\u5e73\u5730\u72ec\u9662\u522b\u5885","desc":"\u77f3\u6885\u6e7e\u4e5d\u91cc\u4e00\u671f","rooms":"3\u623f3\u5385","floor":"\u4e09\u5c42","area":"\u7ea6155\/\u7ea6245","simg":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1053\/image\/20131012\/20131012182711_28124.jpg","bimg":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1053\/image\/20131012\/20131012182711_28124.jpg","width":1600,"height":1600,"dtitle":["\u5efa\u7b51\u9762\u79ef\uff1a\u7ea6155\/\u7ea6245"],"dlist":["\u72ec\u9662\u522b\u5885\uff0c\u91c7\u7528\u5761\u5730\u5e73\u8861\u8bbe\u8ba1\uff0c\u517c\u987e\u5c45\u4f4f\u7a7a\u95f4\u5bf9\u666f\u89c2\u4f18\u8d8a\u6027\u548c\u73af\u5883\u8212\u9002\u5ea6\u7684\u9700\u6c42\uff0c\u5efa\u7b51\u6574\u4f53\u7ed3\u5408\u5730\u52bf\u7279\u5f81\uff0c\u5b8c\u7f8e\u878d\u5408\u5761\u5730\u5c71\u666f\u4e0e\u81ea\u7136\u73af\u5883\uff0c\u5e26\u6765\u8212\u9002\u5c45\u4f4f\u4f53\u9a8c\u3002"],"pics":[{"img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1053\/image\/20131012\/20131012182711_11781.jpg","width":960,"height":960,"name":"\u7b2c\u4e00\u5c42"},{"img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1053\/image\/20131012\/20131012182711_76039.jpg","width":960,"height":960,"name":"\u7b2c\u4e8c\u5c42"}]},{"id":29,"name":"A2\u534a\u5c71\u89c2\u6d77\u72ec\u680b","desc":"\u77f3\u6885\u6e7e\u4e5d\u91cc\u4e00\u671f","rooms":"4\u623f1\u5385","floor":"\u4e09\u5c42","area":"\u7ea6230\/360\u5e73\u65b9\u7c73","simg":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1054\/image\/20131012\/20131012182822_99313.jpg","bimg":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1054\/image\/20131012\/20131012182822_99313.jpg","width":1600,"height":1600,"dtitle":["\u5efa\u7b51\u9762\u79ef\uff1a\u7ea6230\/360\u5e73\u65b9\u7c73"],"dlist":["\u534a\u5c71\u6258\u6d77\u666f\u89c2\u89c6\u91ce\uff0c\u5c06\u6e7e\u533a6\u516c\u91cc\u56fd\u9645\u6d77\u5cb8\u7ebf\u5c3d\u6536\u773c\u5e95\uff1b\u4e09\u5c42\u72ec\u680b\u8bbe\u8ba1\uff0c\u5bbd\u666f\u9633\u53f0\u642d\u914d\u79c1\u5c5e\u5ead\u9662\u3002\u9610\u8ff0\u6700\u4f18\u7f8e\u6ee8\u6d77\u5ea6\u5047\u98ce\u60c5\u3002"],"pics":[{"img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1054\/image\/20131012\/20131012182823_43977.jpg","width":960,"height":960,"name":"\u82b1\u56ed\u5c42"},{"img":"http:\/\/img.weimob.com\/48\/ac\/3f\/3_1054\/image\/20131012\/20131012182823_32298.jpg","width":960,"height":960,"name":"\u7b2c\u4e00\u5c42"}]}]});
        },1000);
        **/
    },
    showRooms: function(res) {
        var R = Picroll.RUNTIME,
        data, idx = 0,
        find = false;
        FCAPP.Common.hideLoading();
        data = res.rooms;
        
        if (idx == -1) {
            FCAPP.Common.msg(true, {
                msg: '没找到该户型'
            });
        } else {
            find = true;
            Picroll.renderPics(data[idx]);
        }
        R.findCategory = find;
        R.closeBtn.show();
    },
    renderPics: function(room) {
        var R = Picroll.RUNTIME,
        List = [],
        pics = room.pics || [],
        detail = '',
        imgdata = 'src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="';
        R.loadSize = [];
        R.Img = [];
        R.ImgName = [];
        window.shareData.desc = '户型【平面】-' + room.desc.replace(/\.jpe?g$/gi, '') + ' ' + room.name.replace(/\.jpe?g$/gi, '');
        window.shareData.descKeep = '户型【平面】-' + room.desc.replace(/\.jpe?g$/gi, '') + ' ' + room.name.replace(/\.jpe?g$/gi, '');
        room.total = 1 + pics.length;
        detail = $.template(R.template, {
            data: room
        });
        List.push('<img id="pic0" idx="pic0" width="' + R.w + '" border="0" height="' + R.h + '" ' + imgdata + '>');
        R.Img.push(room.bimg);
        R.ImgName.push([1 + '/' + (pics.length + 1), room.name.replace(/\.jpe?g$/gi, ''), room.name.replace(/\.jpe?g$/gi, '')]);
        R.loadSize.push({
            w: room.width,
            h: room.height
        });
        for (var i = 0,
        il = pics.length; i < il; i++) {
            List.push('<img id="pic' + (1 + i) + '" idx="pic' + (1 + i) + '" width="' + R.w + '" border="0" height="' + R.h + '" ' + imgdata + '>');
            R.Img.push(pics[i].img);
            R.ImgName.push([(i + 2) + '/' + (il + 1), pics[i].name.replace(/\.jpe?g$/gi, ''), room.name.replace(/\.jpe?g$/gi, '')]);
            R.loadSize.push({
                w: pics[i].width,
                h: pics[i].height
            });
        }
        R.picTank.html(List.join(''));
        R.picDetail.html(detail);
        FCAPP.Common.loadImg(R.Img[0], 'pic0', Picroll.loadedImgProcess);
        R.picTank.css('width', R.w * R.Img.length + 'px');
        Picroll.initScroll();
    },
    renderMsg: function(idx) {
        var R = Picroll.RUNTIME;
        if (!R.idxCav) {
            R.idxCav = $('#typeNum');
        }
        if (R.ImgName[idx]) {
            R.idxCav.html(R.ImgName[idx].slice(0, 2).join(' '));
        }
    },
    downPic: function() {
        var R = Picroll.RUNTIME;
        if (!R.findCategory) {
            return;
        }
        if (R.pic) {
            window.sendMsgResult = Picroll.sendMsgResult;
            var data = {
                cmd: 'picsend',
                appid: window.gQuery && gQuery.appid ? gQuery.appid: '',
                wticket: window.gQuery && gQuery.wticket ? gQuery.wticket: '',
                picurl: R.pic,
                picname: R.picName,
                callback: 'sendMsgResult'
            };
            /**
            $.ajax({
                url: 'http://cgi.trade.qq.com/cgi-bin/common/weixin_helper.fcg?' + $.param(data),
                dataType: 'jsonp'
            });
            **/
        }
    },
    sendMsgResult: function(res) {
        if (res.ret == 0) {
            FCAPP.Common.msg(true, {
                msg: '已发送这张图片给你的微信'
            });
        } else {
            FCAPP.Common.msg(true, {
                msg: '保存失败，请稍后尝试'
            });
        }
    },
    zoomPic: function() {
        var R = Picroll.RUNTIME,
        C = Picroll.CONFIG;
        if (!R.findCategory) {
            return;
        }
        if (R.zoomed) {
            Picroll.zoomOut(R, C.zoomTime);
        } else {
            Picroll.zoomIn(R, C.zoomTime);
        }
    },
    zoomIn: function(R, timeout) {
        R.zoomed = true;
        var idx = myScroll.currPageX,
        src = R.Img[idx],
        w = R.imgDom[idx].width * 2,
        h = R.imgDom[idx].height * 2;
        cw = Math.max(w, R.w);
        R.zoomIdx = idx;
        R.zoomBtn[0].className = 'btn_zoom_in';
        R.zoomDiv.show();
        R.detailCav.hide();
        FCAPP.Common.loadImg(src, 'zoomImg',
        function(img) {
            if (idx != 0) {
                img.width = w;
                img.height = h;
            } else {
                img.width = img.width;
                img.height = img.height;
            }
            var padding = 'padding-left:0px;';
            if (img.width < cw) {
                padding = 'padding-left:' + (cw - img.width) / 2 + 'px;';
            }
            img.id = 'zoomImg';
            img.style.cssText = 'overflow:hidden;width:' + img.width + 'px;height:' + img.height + 'px;' + padding;
            img.onclick = Picroll.showBtn;
            R.downBtn.hide();
            R.zoomBtn.hide();
            R.zoomDiv.css({
                width: img.width + 'px',
                height: img.height + 'px'
            });
            setTimeout(function() {
                document.body.scrollTop = img.height / 3;
            },
            150);
        },
        true);
    },
    zoomOut: function(R, timeout) {
        R.zoomed = false;
        R.zoomDiv.hide();
        R.detailCav.show();
        R.zoomBtn[0].className = 'btn_zoom_out';
        R.picTank.css('height', R.h + 'px');
        myScroll.refresh();
        setTimeout(function() {
            R.zoomDiv.css({
                width: '100%',
                height: '100%'
            });
            R.downBtn.show();
            R.zoomBtn.show();
            $('#zoomImg').prop('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
        },
        timeout);
    },
    viewImg: function(index) {
        var R = Picroll.RUNTIME,
        idx = myScroll.currPageX,
        src = R.Img[idx],
        content = R.ImgName[idx].slice(1, 2).join(' ');
        if (index) {
            R.imgViewRetry = index;
        } else {
            R.imgViewRetry++;
        }
        try {
            WeixinJSBridge.invoke('imagePreview', {
                content: content,
                urls: [src]
            });
        } catch(e) {
            if (R.imgViewRetry < 10) {
                setTimeout(Picroll.viewImg, 200);
            } else {
                R.isWeixin = false;
                Picroll.showBtn();
            }
        }
    },
    showBtn: function() {
        var R = Picroll.RUNTIME;
        if (R.isWeixin) {
            Picroll.viewImg(1);
        } else {
            if (R.downBtn[0].style.display == 'none') {
                R.downBtn.show();
                R.zoomBtn.show();
            } else {
                R.downBtn.hide();
                R.zoomBtn.hide();
            }
        }
    },
    initScroll: function() {
        var R = Picroll.RUNTIME;
        myScroll = new iScroll('detailContainer', {
            zoom: false,
            snap: true,
            momentum: false,
            hScrollbar: false,
            vScrollbar: false,
            fixScrollBar: true,
            hScroll: true,
            onScrollEnd: function() {
                var idx = myScroll.currPageX;
                FCAPP.Common.loadImg(R.Img[idx], 'pic' + idx, Picroll.loadedImgProcess);
                Picroll.renderMsg(idx);
                R.pic = R.Img[idx];
                R.picName = R.ImgName[idx].slice(1, 3).join(' ');
            }
        });
        R.pic = R.Img[0];
        R.picName = R.ImgName[0].slice(1, 3).join(' ');
    },
    loadedImgProcess: function(img) {
        var R = Picroll.RUNTIME;
        idx = img.idx.replace(/[^\d]+/g, ''),
        size = R.loadSize[idx],
        pw = img.width,
        ph = img.height,
        sw = R.w,
        sh = R.h,
        fw = 0,
        fh = 0,
        style = '';
        img.id = img.idx;
        if (pw == 0) {
            pw = size.w;
        }
        if (ph == 0) {
            ph = size.h;
        }
        if (ph / pw < sh / sw) {
            fw = sw;
            fh = Math.floor(ph * sw / pw);
            style = 'margin:' + Math.floor((sh - fh) / 2) + "px 0;";
        } else {
            fh = sh;
            fw = Math.floor(pw * sh / ph);
            style = 'margin:0 ' + Math.floor((sw - fw) / 2) + "px;";
        }
        R.imgDom[idx] = img;
        img.width = fw;
        img.height = fh;
        img.style.cssText = style;
        if (R.isWeixin) {
            img.onclick = Picroll.showBtn;
        }
    }
};
var Picroll = FCAPP.HOUSE.Picroll;
$(document).ready(Picroll.init);