function bybonTools() {
    var tools = this;
    tools.params = {
        // Modals 弹出框
        modalButtonOk: '确定',
        modalButtonCancel: '取消',
        modalTitle: 'Bybon Tools',
        modalCloseByOutside: false,
        actionsCloseByOutside: true,
        popupCloseByOutside: true,
        modalDialogBgColor: 'rgba(0,0,0,.5)',
        modalInnerBgColor: '#fff',
        modalPreloaderTitle: 'Loading... ',
        textColor: "#f00",
        loadProgressBarClass: "loadProgressBar",
        loadProgressBarColor: "#f00",
        loadProgressBarHeight: 3,
        loadProgressBarId: null,
        //Message
        messageOutTime: 3000,
        messageClose: true,
        messageBottom: "100px",
        messageBgColor: "#000",
        messageTextColor: "#fff"
    }

    // Modals Begin
    //模态框
    tools.modal = function (params) {
        params = params || {};

        var buttonsHTML = "";

        if (params.buttons && params.buttons.length > 0) {
            for (var i = 0; i < params.buttons.length; i++) {
                buttonsHTML += '<span class="bybon-modal-button' + (params.buttons[i].bold ? ' bybon-modal-button-bold' : '') + '">' + params.buttons[i].text + '</span>';
            }
            buttonsHTML = '<div class="bybon-modal-buttons bybon-modal-buttons-' + params.buttons.length + '">' + buttonsHTML + '</div>';
        }
        var html
                = '<style>'
                + '.bybon-modal-dialog{position:fixed;top:0;right:0;bottom:0;left:0;background-color:' + (params.modalDialogBgColor ? params.modalDialogBgColor : tools.params.modalDialogBgColor) + ';opacity:0;visibility:hidden;-webkit-transition-duration:200ms;transition-duration:200ms}'
                + '.bybon-modal-content{opacity:0;position:absolute;width:270px;top:50%;left:50%;margin-left:-135px;overflow:hidden;transform:scale(1.85)}'
                + '.bybon-modal-inner{padding:15px;border-radius:7px 7px 0 0;position:relative;background-color:' + (params.modalInnerBgColor ? params.modalInnerBgColor : tools.params.modalInnerBgColor) + '}'
                + '.bybon-modal-title{font-size:18px;text-align:center;line-height:24px;height:24px}'
                + '.bybon-modal-text{text-align:center;font-size:14px;padding-top:10px;line-height:20px}'
                + '.bybon-modal-preloader .bybon-modal-inner{border-radius:7px;}'
                + '.bybon-modal-preloader .bybon-modal-text{padding-bottom:10px;}'
                + '.bybon-modal-buttons{line-height:44px;height:44px;border-top:1px solid ' + (params.textColor ? params.textColor : tools.params.textColor) + ';box-sizing:content-box;border-radius:0 0 7px 7px;background-color:' + (params.modalInnerBgColor ? params.modalInnerBgColor : tools.params.modalInnerBgColor) + ';overflow:hidden}'
                + '.bybon-modal-buttons .bybon-modal-button{border:0;float:left;text-align:center;padding:0;margin:0;font-size:14px;box-sizing:border-box;color:' + (params.textColor ? params.textColor : tools.params.textColor) + ';border-left:1px solid ' + (params.textColor ? params.textColor : tools.params.textColor) + ';background-color:transparent;display:inline-block}'
                + '.bybon-modal-buttons-1 .bybon-modal-button{width:100%;}'
                + '.bybon-modal-buttons-2 .bybon-modal-button{width:50%;}'
                + '.bybon-modal-buttons-3 .bybon-modal-button{width:33.33%;}'
                + '.bybon-modal-buttons-4 .bybon-modal-button{width:25%;}'
                + '.bybon-modal-buttons .bybon-modal-button:first-child{border:0}'
                + '.bybon-modal-button-bold{font-weight: bold;}'
                + '.bybon-modal-dialog.bybon-modal-in{opacity:1;z-index:9999;visibility:visible}'
                + '.bybon-modal-dialog.bybon-modal-in .bybon-modal-content{opacity:1;-webkit-transition-duration:200ms;transition-duration:200ms;-webkit-transform:scale(1);transform:scale(1)}'
                + '.bybon-modal-dialog.bybon-modal-out{opacity:0;z-index:-1;visibility:hidden}'
                + '.bybon-modal-dialog.bybon-modal-out .bybon-modal-content{opacity:0;-webkit-transition-duration:200ms;transition-duration:200ms;-webkit-transform:scale(1.85);transform:scale(1.85)}'
                + '</style>'
                + '<div class="bybon-modal-dialog">'
                + '    <div class="bybon-modal-content ' + (params.cssClass ? params.cssClass : '') + '">'
                + '        <div class="bybon-modal-inner">'
                + (params.title ? '<div class="bybon-modal-title">' + params.title + '</div>' : '')
                + (params.text ? '<div class="bybon-modal-text">' + params.text + '</div>' : "")
                + (params.afterText ? params.afterText : '')
                + '        </div>'
                + buttonsHTML
                + '    </div>'
                + '</div>';


        var modal = $('<div style="position:fixed;background-color:transparent;top:0;right:0;bottom:0;left:0;z-index:999"></div>').html(html).appendTo("body");


        modal.find(".bybon-modal-button").each(function (index, el) {
            $(this).bind("click", function (e) {
                if (params.buttons[index].onClick) params.buttons[index].onClick(modal, e);
                if (params.buttons[index].close !== false) tools.closeModal(modal);
                if (params.onClick) params.onClick(modal, index);
            });
        });

        var modalContent = modal.find(".bybon-modal-content");
        var dialogHeight = Math.floor(modalContent.height() / 2);

        modalContent.css({ "margin-top": -dialogHeight + "px" });

        modal.find(".bybon-modal-dialog").addClass("bybon-modal-in").removeClass("bybon-modal-out");
     
        return modal;
    }
	tools.closeModal = function (m) {
        m.find(".bybon-modal-dialog").addClass("bybon-modal-out").removeClass("bybon-modal-in");
        setTimeout(function () { m.remove(); }, 250);
    }
    //消息框
    tools.alert = function (text, title, callbackOk) {
        if (typeof title === 'function') {
            callbackOk = arguments[1];
            title = undefined;
        }
        return tools.modal({
            text: text || '',
            title: typeof title === 'undefined' ? tools.params.modalTitle : title,
            buttons: [{ text: tools.params.modalButtonOk, bold: true, onClick: callbackOk }]
        });
    }

    //选择框
    tools.confirm = function (text, title, callbackOk, callbackCancel) {
        if (typeof title === 'function') {
            callbackCancel = arguments[2];
            callbackOk = arguments[1];
            title = undefined;
        }
        return tools.modal({
            text: text || '',
            title: typeof title === 'undefined' ? tools.params.modalTitle : title,
            buttons: [
                { text: tools.params.modalButtonCancel, onClick: callbackCancel },
                { text: tools.params.modalButtonOk, bold: true, onClick: callbackOk }
            ]
        });
    };

    /* 预加载框 */
    //显示预加载框
    tools.showPreloader = function (params) {
        params = params || {};
        var random = Math.floor(Math.random() * (100 + 1)),
            loadingId = "loading" + random,
            objectId = "object" + random,
            objectColor = params.objectColor ? params.objectColor : "#000",
            title = params.title;

        //params.effectIndex = params.effectIndex || 10;
        params.effectIndex = 10;
        params.text = params.text || "加载中…";
        
        var effect =
            [
                {   //  1
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:200px;width:200px;margin-top:-100px;margin-left:-100px}#" + objectId + "{width:80px;height:80px;background-color:" + objectColor + ";-webkit-animation:animate 1s infinite ease-in-out;animation:animate 1s infinite ease-in-out;margin-right:auto;margin-left:auto;margin-top:60px}@-webkit-keyframes animate{0%{-webkit-transform:perspective(160px)}50%{-webkit-transform:perspective(160px) rotateY(-180deg)}100%{-webkit-transform:perspective(160px) rotateY(-180deg) rotateX(-180deg)}}@keyframes animate{0%{transform:perspective(160px) rotateX(0) rotateY(0);-webkit-transform:perspective(160px) rotateX(0) rotateY(0)}50%{transform:perspective(160px) rotateX(-180deg) rotateY(0);-webkit-transform:perspective(160px) rotateX(-180deg) rotateY(0)}100%{transform:perspective(160px) rotateX(-180deg) rotateY(-180deg);-webkit-transform:perspective(160px) rotateX(-180deg) rotateY(-180deg)}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div id="' + objectId + '"></div></div>'
                },
                {   //  2
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:150px;width:150px;margin-top:-75px;margin-left:-75px;-ms-transform:rotate(45deg);-webkit-transform:rotate(45deg);transform:rotate(45deg)}.class" + objectId + "{width:20px;height:20px;background-color:" + objectColor + ";position:absolute;left:65px;top:65px}.class" + objectId + ":nth-child(2n+0){margin-right:0}#" + objectId + "_one{-webkit-animation:" + objectId + "one 2s infinite;animation:" + objectId + "one 2s infinite;-webkit-animation-delay:.2s;animation-delay:.2s}#" + objectId + "_two{-webkit-animation:" + objectId + "two 2s infinite;animation:" + objectId + "two 2s infinite;-webkit-animation-delay:.3s;animation-delay:.3s}#" + objectId + "_three{-webkit-animation:" + objectId + "three 2s infinite;animation:" + objectId + "three 2s infinite;-webkit-animation-delay:.4s;animation-delay:.4s}#" + objectId + "_four{-webkit-animation:" + objectId + "four 2s infinite;animation:" + objectId + "four 2s infinite;-webkit-animation-delay:.5s;animation-delay:.5s}#" + objectId + "_five{-webkit-animation:" + objectId + "five 2s infinite;animation:" + objectId + "five 2s infinite;-webkit-animation-delay:.6s;animation-delay:.6s}#" + objectId + "_six{-webkit-animation:" + objectId + "six 2s infinite;animation:" + objectId + "six 2s infinite;-webkit-animation-delay:.7s;animation-delay:.7s}#" + objectId + "_seven{-webkit-animation:" + objectId + "seven 2s infinite;animation:" + objectId + "seven 2s infinite;-webkit-animation-delay:.8s;animation-delay:.8s}#" + objectId + "_eight{-webkit-animation:" + objectId + "eight 2s infinite;animation:" + objectId + "eight 2s infinite;-webkit-animation-delay:.9s;animation-delay:.9s}#" + objectId + "_big{position:absolute;width:50px;height:50px;left:50px;top:50px;-webkit-animation:" + objectId + "big 2s infinite;animation:" + objectId + "big 2s infinite;-webkit-animation-delay:.5s;animation-delay:.5s}@-webkit-keyframes " + objectId + "big{50%{-webkit-transform:scale(.5)}}@keyframes " + objectId + "big{50%{transform:scale(.5);-webkit-transform:scale(.5)}}@-webkit-keyframes " + objectId + "one{50%{-webkit-transform:translate(-65px,-65px)}}@keyframes " + objectId + "one{50%{transform:translate(-65px,-65px);-webkit-transform:translate(-65px,-65px)}}@-webkit-keyframes " + objectId + "two{50%{-webkit-transform:translate(0,-65px)}}@keyframes " + objectId + "two{50%{transform:translate(0,-65px);-webkit-transform:translate(0,-65px)}}@-webkit-keyframes " + objectId + "three{50%{-webkit-transform:translate(65px,-65px)}}@keyframes " + objectId + "three{50%{transform:translate(65px,-65px);-webkit-transform:translate(65px,-65px)}}@-webkit-keyframes " + objectId + "four{50%{-webkit-transform:translate(65px,0)}}@keyframes " + objectId + "four{50%{transform:translate(65px,0);-webkit-transform:translate(65px,0)}}@-webkit-keyframes " + objectId + "five{50%{-webkit-transform:translate(65px,65px)}}@keyframes " + objectId + "five{50%{transform:translate(65px,65px);-webkit-transform:translate(65px,65px)}}@-webkit-keyframes " + objectId + "six{50%{-webkit-transform:translate(0,65px)}}@keyframes " + objectId + "six{50%{transform:translate(0,65px);-webkit-transform:translate(0,65px)}}@-webkit-keyframes " + objectId + "seven{50%{-webkit-transform:translate(-65px,65px)}}@keyframes " + objectId + "seven{50%{transform:translate(-65px,65px);-webkit-transform:translate(-65px,65px)}}@-webkit-keyframes " + objectId + "eight{50%{-webkit-transform:translate(-65px,0)}@keyframes " + objectId + "eight{50%{transform:translate(-65px,0);-webkit-transform:translate(-65px,0)}}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div class="class' + objectId + '" id="' + objectId + '_one"></div><div class="class' + objectId + '" id="' + objectId + '_two"></div><div class="class' + objectId + '" id="' + objectId + '_three"></div><div class="class' + objectId + '" id="' + objectId + '_four"></div><div class="class' + objectId + '" id="' + objectId + '_five"></div><div class="class' + objectId + '" id="' + objectId + '_six"></div><div class="class' + objectId + '" id="' + objectId + '_seven"></div><div class="class' + objectId + '" id="' + objectId + '_eight"></div><div class="class' + objectId + '" id="' + objectId + '_big"></div></div>'
                },
                {   //  3
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:200px;width:200px;margin-top:-100px;margin-left:-100px}.class" + objectId + "{width:50px;height:50px;background-color:rgba(255,255,255,0);margin-right:auto;margin-left:auto;border:4px solid " + objectColor + ";left:73px;top:73px;position:absolute}#first_object{-webkit-animation:first_" + objectId + "animate 1s infinite ease-in-out;animation:first_" + objectId + "animate 1s infinite ease-in-out}#second_object{-webkit-animation:second_object 1s forwards,second_" + objectId + "animate 1s infinite ease-in-out;animation:second_object 1s forwards,second_" + objectId + "animate 1s infinite ease-in-out}#third_object{-webkit-animation:third_object 1s forwards,third_" + objectId + "animate 1s infinite ease-in-out;animation:third_object 1s forwards,third_" + objectId + "animate 1s infinite ease-in-out}@-webkit-keyframes second_object{100%{width:100px;height:100px;left:48px;top:48px}}@keyframes second_object{100%{width:100px;height:100px;left:48px;top:48px}}@-webkit-keyframes third_object{100%{width:150px;height:150px;left:23px;top:23px}}@keyframes third_object{100%{width:150px;height:150px;left:23px;top:23px}}@-webkit-keyframes first_" + objectId + "animate{0%{-webkit-transform:perspective(100px)}50%{-webkit-transform:perspective(100px) rotateY(-180deg)}100%{-webkit-transform:perspective(100px) rotateY(-180deg) rotateX(-180deg)}}@keyframes first_" + objectId + "animate{0%{transform:perspective(100px) rotateX(0) rotateY(0);-webkit-transform:perspective(100px) rotateX(0) rotateY(0)}50%{transform:perspective(100px) rotateX(-180deg) rotateY(0);-webkit-transform:perspective(100px) rotateX(-180deg) rotateY(0)}100%{transform:perspective(100px) rotateX(-180deg) rotateY(-180deg);-webkit-transform:perspective(100px) rotateX(-180deg) rotateY(-180deg)}}@-webkit-keyframes second_" + objectId + "animate{0%{-webkit-transform:perspective(200px)}50%{-webkit-transform:perspective(200px) rotateY(180deg)}100%{-webkit-transform:perspective(200px) rotateY(180deg) rotateX(180deg)}}@keyframes second_" + objectId + "animate{0%{transform:perspective(200px) rotateX(0) rotateY(0);-webkit-transform:perspective(200px) rotateX(0) rotateY(0)}50%{transform:perspective(200px) rotateX(180deg) rotateY(0);-webkit-transform:perspective(200px) rotateX(180deg) rotateY(0)}100%{transform:perspective(200px) rotateX(180deg) rotateY(180deg);-webkit-transform:perspective(200px) rotateX(180deg) rotateY(180deg)}}@-webkit-keyframes third_" + objectId + "animate{0%{-webkit-transform:perspective(300px)}50%{-webkit-transform:perspective(300px) rotateY(-180deg)}100%{-webkit-transform:perspective(300px) rotateY(-180deg) rotateX(-180deg)}}@keyframes third_" + objectId + "animate{0%{transform:perspective(300px) rotateX(0) rotateY(0);-webkit-transform:perspective(300px) rotateX(0) rotateY(0)}50%{transform:perspective(300px) rotateX(-180deg) rotateY(0);-webkit-transform:perspective(300px) rotateX(-180deg) rotateY(0)}100%{transform:perspective(300px) rotateX(-180deg) rotateY(-180deg);-webkit-transform:perspective(300px) rotateX(-180deg) rotateY(-180deg)}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div class="class' + objectId + '" id="first_object"></div><div class="class' + objectId + '" id="second_object"></div><div class="class' + objectId + '" id="third_object"></div></div>'
                },
                {   //  4
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:100px;width:100px;margin-top:-50px;margin-left:-50px}.class" + objectId + "{width:25px;height:25px;background-color:rgba(255,255,255,0);margin-right:auto;margin-left:auto;border:4px solid " + objectColor + ";left:37px;top:37px;position:absolute}#first_object{-webkit-animation:first_object 1s infinite;animation:first_object 1s infinite;-webkit-animation-delay:.5s;animation-delay:.5s}#second_object{-webkit-animation:second_object 1s infinite;animation:second_object 1s infinite}#third_object{-webkit-animation:third_object 1s infinite;animation:third_object 1s infinite;-webkit-animation-delay:.5s;animation-delay:.5s}#forth_object{-webkit-animation:forth_object 1s infinite;animation:forth_object 1s infinite}@-webkit-keyframes first_object{0%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}50%{-ms-transform:translate(150%,150%) scale(2,2);-webkit-transform:translate(150%,150%) scale(2,2);transform:translate(150%,150%) scale(2,2)}100%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}}@keyframes first_object{0%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}50%{-ms-transform:translate(150%,150%) scale(2,2);-webkit-transform:translate(150%,150%) scale(2,2);transform:translate(150%,150%) scale(2,2)}100%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}}@-webkit-keyframes second_object{0%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}50%{-ms-transform:translate(-150%,150%) scale(2,2);-webkit-transform:translate(-150%,150%) scale(2,2);transform:translate(-150%,150%) scale(2,2)}100%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}}@keyframes second_object{0%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}50%{-ms-transform:translate(-150%,150%) scale(2,2);-webkit-transform:translate(-150%,150%) scale(2,2);transform:translate(-150%,150%) scale(2,2)}100%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}}@-webkit-keyframes third_object{0%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}50%{-ms-transform:translate(-150%,-150%) scale(2,2);-webkit-transform:translate(-150%,-150%) scale(2,2);transform:translate(-150%,-150%) scale(2,2)}100%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}}@keyframes third_object{0%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}50%{-ms-transform:translate(-150%,-150%) scale(2,2);-webkit-transform:translate(-150%,-150%) scale(2,2);transform:translate(-150%,-150%) scale(2,2)}100%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}}@-webkit-keyframes forth_object{0%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}50%{-ms-transform:translate(150%,-150%) scale(2,2);-webkit-transform:translate(150%,-150%) scale(2,2);transform:translate(150%,-150%) scale(2,2)}100%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}}@keyframes forth_object{0%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}50%{-ms-transform:translate(150%,-150%) scale(2,2);-webkit-transform:translate(150%,-150%) scale(2,2);transform:translate(150%,-150%) scale(2,2)}100%{-ms-transform:translate(1,1) scale(1,1);-webkit-transform:translate(1,1) scale(1,1);transform:translate(1,1) scale(1,1)}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div class="class' + objectId + '" id="first_object"></div><div class="class' + objectId + '" id="second_object"></div><div class="class' + objectId + '" id="third_object"></div><div class="class' + objectId + '" id="forth_object"></div></div>'
                },
                {   //  5
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:118px;width:118px;margin-top:-59px;margin-left:-59px}.class" + objectId + "{width:20px;height:20px;background-color:" + objectColor + ";margin-right:20px;float:left;margin-bottom:20px}.class" + objectId + ":nth-child(3n+0){margin-right:0}#" + objectId + "_one{-webkit-animation:animate 1s -.9s ease-in-out infinite;animation:animate 1s -.9s ease-in-out infinite}#" + objectId + "_two{-webkit-animation:animate 1s -.8s ease-in-out infinite;animation:animate 1s -.8s ease-in-out infinite}#" + objectId + "_three{-webkit-animation:animate 1s -.7s ease-in-out infinite;animation:animate 1s -.7s ease-in-out infinite}#" + objectId + "_four{-webkit-animation:animate 1s -.6s ease-in-out infinite;animation:animate 1s -.6s ease-in-out infinite}#" + objectId + "_five{-webkit-animation:animate 1s -.5s ease-in-out infinite;animation:animate 1s -.5s ease-in-out infinite}#" + objectId + "_six{-webkit-animation:animate 1s -.4s ease-in-out infinite;animation:animate 1s -.4s ease-in-out infinite}#" + objectId + "_seven{-webkit-animation:animate 1s -.3s ease-in-out infinite;animation:animate 1s -.3s ease-in-out infinite}#" + objectId + "_eight{-webkit-animation:animate 1s -.2s ease-in-out infinite;animation:animate 1s -.2s ease-in-out infinite}#" + objectId + "_nine{-webkit-animation:animate 1s -.1s ease-in-out infinite;animation:animate 1s -.1s ease-in-out infinite}@-webkit-keyframes animate{50%{-ms-transform:scale(1.5,1.5);-webkit-transform:scale(1.5,1.5);transform:scale(1.5,1.5)}100%{-ms-transform:scale(1,1);-webkit-transform:scale(1,1);transform:scale(1,1)}}@keyframes animate{50%{-ms-transform:scale(1.5,1.5);-webkit-transform:scale(1.5,1.5);transform:scale(1.5,1.5)}100%{-ms-transform:scale(1,1);-webkit-transform:scale(1,1);transform:scale(1,1)}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div class="class' + objectId + '" id="' + objectId + '_one"></div><div class="class' + objectId + '" id="' + objectId + '_two"></div><div class="class' + objectId + '" id="' + objectId + '_three"></div><div class="class' + objectId + '" id="' + objectId + '_four"></div><div class="class' + objectId + '" id="' + objectId + '_five"></div><div class="class' + objectId + '" id="' + objectId + '_six"></div><div class="class' + objectId + '" id="' + objectId + '_seven"></div><div class="class' + objectId + '" id="' + objectId + '_eight"></div><div class="class' + objectId + '" id="' + objectId + '_nine"></div></div>'
                },
                {   //  6
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:118px;width:72px;margin-top:-59px;margin-left:-36px}.class" + objectId + "{width:26px;height:26px;background-color:" + objectColor + ";margin-right:20px;float:left;margin-bottom:20px}.class" + objectId + ":nth-child(2n+0){margin-right:0}#" + objectId + "_one{-webkit-animation:" + objectId + "one 1s infinite;animation:" + objectId + "one 1s infinite}#" + objectId + "_two{-webkit-animation:" + objectId + "two 1s infinite;animation:" + objectId + "two 1s infinite}#" + objectId + "_three{-webkit-animation:" + objectId + "three 1s infinite;animation:" + objectId + "three 1s infinite}#" + objectId + "_four{-webkit-animation:" + objectId + "four 1s infinite;animation:" + objectId + "four 1s infinite}#" + objectId + "_five{-webkit-animation:" + objectId + "five 1s infinite;animation:" + objectId + "five 1s infinite}#" + objectId + "_six{-webkit-animation:" + objectId + "six 1s infinite;animation:" + objectId + "six 1s infinite}@-webkit-keyframes " + objectId + "one{50%{-ms-transform:translate(-100px,46px) rotate(-179deg);-webkit-transform:translate(-100px,46px) rotate(-179deg);transform:translate(-100px,46px) rotate(-179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes " + objectId + "one{50%{-ms-transform:translate(-100px,46px) rotate(-179deg);-webkit-transform:translate(-100px,46px) rotate(-179deg);transform:translate(-100px,46px) rotate(-179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes " + objectId + "two{50%{-ms-transform:translate(100px,46px) rotate(179deg);-webkit-transform:translate(100px,46px) rotate(179deg);transform:translate(100px,46px) rotate(179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes " + objectId + "two{50%{-ms-transform:translate(100px,46px) rotate(179deg);-webkit-transform:translate(100px,46px) rotate(179deg);transform:translate(100px,46px) rotate(179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes " + objectId + "three{50%{-ms-transform:translate(-100px,0) rotate(-179deg);-webkit-transform:translate(-100px,0) rotate(-179deg);transform:translate(-100px,0) rotate(-179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes " + objectId + "three{50%{-ms-transform:translate(-100px,0) rotate(-179deg);-webkit-transform:translate(-100px,0) rotate(-179deg);transform:translate(-100px,0) rotate(-179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes " + objectId + "four{50%{-ms-transform:translate(100px,0) rotate(179deg);-webkit-transform:translate(100px,0) rotate(179deg);transform:translate(100px,0) rotate(179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes " + objectId + "four{50%{-ms-transform:translate(100px,0) rotate(179deg);-webkit-transform:translate(100px,0) rotate(179deg);transform:translate(100px,0) rotate(179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes " + objectId + "five{50%{-ms-transform:translate(-100px,-46px) rotate(-179deg);-webkit-transform:translate(-100px,-46px) rotate(-179deg);transform:translate(-100px,-46px) rotate(-179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes " + objectId + "five{50%{-ms-transform:translate(-100px,-46px) rotate(-179deg);-webkit-transform:translate(-100px,-46px) rotate(-179deg);transform:translate(-100px,-46px) rotate(-179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes " + objectId + "six{50%{-ms-transform:translate(100px,-46px) rotate(179deg);-webkit-transform:translate(100px,-46px) rotate(179deg);transform:translate(100px,-46px) rotate(179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes " + objectId + "six{50%{-ms-transform:translate(100px,-46px) rotate(179deg);-webkit-transform:translate(100px,-46px) rotate(179deg);transform:translate(100px,-46px) rotate(179deg)}100%{-ms-transform:translate(0,0);-webkit-transform:translate(0,0);transform:translate(0,0)}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div class="class' + objectId + '" id="' + objectId + '_one"></div><div class="class' + objectId + '" id="' + objectId + '_two"></div><div class="class' + objectId + '" id="' + objectId + '_three"></div><div class="class' + objectId + '" id="' + objectId + '_four"></div><div class="class' + objectId + '" id="' + objectId + '_five"></div><div class="class' + objectId + '" id="' + objectId + '_six"></div></div>'
                },
                {   //  7
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:100px;width:100px;margin-top:-50px;margin-left:-50px}.class" + objectId + "{width:25px;height:25px;background-color:" + objectColor + ";margin-right:50px;float:left;margin-bottom:50px}.class" + objectId + ":nth-child(2n+0){margin-right:0}#" + objectId + "_one{-webkit-animation:" + objectId + "one 2s infinite;animation:" + objectId + "one 2s infinite}#" + objectId + "_two{-webkit-animation:" + objectId + "two 2s infinite;animation:" + objectId + "two 2s infinite}#" + objectId + "_three{-webkit-animation:" + objectId + "three 2s infinite;animation:" + objectId + "three 2s infinite}#" + objectId + "_four{-webkit-animation:" + objectId + "four 2s infinite;animation:" + objectId + "four 2s infinite}@-webkit-keyframes " + objectId + "one{25%{-webkit-transform:translate(75px,0) rotate(-90deg) scale(.5)}50%{-webkit-transform:translate(75px,75px) rotate(-180deg)}75%{-webkit-transform:translate(0,75px) rotate(-270deg) scale(.5)}100%{-webkit-transform:rotate(-360deg)}}@keyframes " + objectId + "one{25%{transform:translate(75px,0) rotate(-90deg) scale(.5);-webkit-transform:translate(75px,0) rotate(-90deg) scale(.5)}50%{transform:translate(75px,75px) rotate(-180deg);-webkit-transform:translate(75px,75px) rotate(-180deg)}75%{transform:translate(0,75px) rotate(-270deg) scale(.5);-webkit-transform:translate(0,75px) rotate(-270deg) scale(.5)}100%{transform:rotate(-360deg);-webkit-transform:rotate(-360deg)}}@-webkit-keyframes " + objectId + "two{25%{-webkit-transform:translate(0,75px) rotate(-90deg) scale(.5)}50%{-webkit-transform:translate(-75px,75px) rotate(-180deg)}75%{-webkit-transform:translate(-75px,0) rotate(-270deg) scale(.5)}100%{-webkit-transform:rotate(-360deg)}}@keyframes " + objectId + "two{25%{transform:translate(0,75px) rotate(-90deg) scale(.5);-webkit-transform:translate(0,75px) rotate(-90deg) scale(.5)}50%{transform:translate(-75px,75px) rotate(-180deg);-webkit-transform:translate(-75px,75px) rotate(-180deg)}75%{transform:translate(-75px,0) rotate(-270deg) scale(.5);-webkit-transform:translate(-75px,0) rotate(-270deg) scale(.5)}100%{transform:rotate(-360deg);-webkit-transform:rotate(-360deg)}}@-webkit-keyframes " + objectId + "three{25%{-webkit-transform:translate(0,-75px) rotate(-90deg) scale(.5)}50%{-webkit-transform:translate(75px,-75px) rotate(-180deg)}75%{-webkit-transform:translate(75px,0) rotate(-270deg) scale(.5)}100%{-webkit-transform:rotate(-360deg)}}@keyframes " + objectId + "three{25%{transform:translate(0,-75px) rotate(-90deg) scale(.5);-webkit-transform:translate(0,-75px) rotate(-90deg) scale(.5)}50%{transform:translate(75px,-75px) rotate(-180deg);-webkit-transform:translate(75px,-75px) rotate(-180deg)}75%{transform:translate(75px,0) rotate(-270deg) scale(.5);-webkit-transform:translate(75px,0) rotate(-270deg) scale(.5)}100%{transform:rotate(-360deg);-webkit-transform:rotate(-360deg)}}@-webkit-keyframes " + objectId + "four{25%{-webkit-transform:translate(-75px,0) rotate(-90deg) scale(.5)}50%{-webkit-transform:translate(-75px,-75px) rotate(-180deg)}75%{-webkit-transform:translate(0,-75px) rotate(-270deg) scale(.5)}100%{-webkit-transform:rotate(-360deg)}}@keyframes " + objectId + "four{25%{transform:translate(-75px,0) rotate(-90deg) scale(.5);-webkit-transform:translate(-75px,0) rotate(-90deg) scale(.5)}50%{transform:translate(-75px,-75px) rotate(-180deg);-webkit-transform:translate(-75px,-75px) rotate(-180deg)}75%{transform:translate(0,-75px) rotate(-270deg) scale(.5);-webkit-transform:translate(0,-75px) rotate(-270deg) scale(.5)}100%{transform:rotate(-360deg);-webkit-transform:rotate(-360deg)}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div class="class' + objectId + '" id="' + objectId + '_one"></div><div class="class' + objectId + '" id="' + objectId + '_two"></div><div class="class' + objectId + '" id="' + objectId + '_three"></div><div class="class' + objectId + '" id="' + objectId + '_four"></div></div>'
                },
                {   //  8
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:50px;width:50px;margin-top:-25px;margin-left:-25px;-ms-transform:rotate(45deg);-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:loading-center-absolute 1.5s infinite;animation:loading-center-absolute 1.5s infinite}.class" + objectId + "{width:25px;height:25px;background-color:" + objectColor + ";float:left}#" + objectId + "_one{-webkit-animation:" + objectId + "one 1.5s infinite;animation:" + objectId + "one 1.5s infinite}#" + objectId + "_two{-webkit-animation:" + objectId + "two 1.5s infinite;animation:" + objectId + "two 1.5s infinite}#" + objectId + "_three{-webkit-animation:" + objectId + "three 1.5s infinite;animation:" + objectId + "three 1.5s infinite}#" + objectId + "_four{-webkit-animation:" + objectId + "four 1.5s infinite;animation:" + objectId + "four 1.5s infinite}@-webkit-keyframes loading-center-absolute{100%{-webkit-transform:rotate(-45deg)}}@keyframes loading-center-absolute{100%{transform:rotate(-45deg);-webkit-transform:rotate(-45deg)}}@-webkit-keyframes " + objectId + "one{25%{-webkit-transform:translate(0,-50px) rotate(-180deg)}100%{-webkit-transform:translate(0,0) rotate(-180deg)}}@keyframes " + objectId + "one{25%{transform:translate(0,-50px) rotate(-180deg);-webkit-transform:translate(0,-50px) rotate(-180deg)}100%{transform:translate(0,0) rotate(-180deg);-webkit-transform:translate(0,0) rotate(-180deg)}}@-webkit-keyframes " + objectId + "two{25%{-webkit-transform:translate(50px,0) rotate(-180deg)}100%{-webkit-transform:translate(0,0) rotate(-180deg)}}@keyframes " + objectId + "two{25%{transform:translate(50px,0) rotate(-180deg);-webkit-transform:translate(50px,0) rotate(-180deg)}100%{transform:translate(0,0) rotate(-180deg);-webkit-transform:translate(0,0) rotate(-180deg)}}@-webkit-keyframes " + objectId + "three{25%{-webkit-transform:translate(-50px,0) rotate(-180deg)}100%{-webkit-transform:translate(0,0) rotate(-180deg)}}@keyframes " + objectId + "three{25%{transform:translate(-50px,0) rotate(-180deg);-webkit-transform:translate(-50px,0) rotate(-180deg)}100%{transform:translate(0,0) rotate(-180deg);-webkit-transform:rtranslate(0,0) rotate(-180deg)}}@-webkit-keyframes " + objectId + "four{25%{-webkit-transform:translate(0,50px) rotate(-180deg)}100%{-webkit-transform:translate(0,0) rotate(-180deg)}}@keyframes " + objectId + "four{25%{transform:translate(0,50px) rotate(-180deg);-webkit-transform:translate(0,50px) rotate(-180deg)}100%{transform:translate(0,0) rotate(-180deg);-webkit-transform:translate(0,0) rotate(-180deg)}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div class="class' + objectId + '" id="' + objectId + '_one"></div><div class="class' + objectId + '" id="' + objectId + '_two"></div><div class="class' + objectId + '" id="' + objectId + '_three"></div><div class="class' + objectId + '" id="' + objectId + '_four"></div></div>'
                },
                {   //  9
                    style: "#" + loadingId + "-center-absolute{position:absolute;left:50%;top:50%;height:150px;width:150px;margin-top:-75px;margin-left:-75px;-ms-transform:rotate(45deg);-webkit-transform:rotate(45deg);transform:rotate(45deg)}.class" + objectId + "{width:20px;height:20px;background-color:" + objectColor + ";margin-right:110px;float:left;margin-bottom:110px}.class" + objectId + ":nth-child(2n+0){margin-right:0}#" + objectId + "_one{-webkit-animation:" + objectId + "one 2s infinite;animation:" + objectId + "one 2s infinite}#" + objectId + "_two{-webkit-animation:" + objectId + "two 2s infinite;animation:" + objectId + "two 2s infinite}#" + objectId + "_three{-webkit-animation:" + objectId + "three 2s infinite;animation:" + objectId + "three 2s infinite}#" + objectId + "_four{-webkit-animation:" + objectId + "four 2s infinite;animation:" + objectId + "four 2s infinite}#" + objectId + "_big{-webkit-animation:" + objectId + "big .5s infinite;animation:" + objectId + "big .5s infinite;position:absolute;width:50px;height:50px;left:50px;top:50px}@-webkit-keyframes " + objectId + "big{25%{-webkit-transform:scale(.5)}}@keyframes " + objectId + "big{25%{transform:scale(.5);-webkit-transform:scale(.5)}}@-webkit-keyframes " + objectId + "one{25%{-webkit-transform:translate(130px,0) rotate(-90deg)}50%{-webkit-transform:translate(130px,130px) rotate(-180deg)}75%{-webkit-transform:translate(0,130px) rotate(-270deg)}100%{-webkit-transform:rotate(-360deg)}}@keyframes " + objectId + "one{25%{transform:translate(130px,0) rotate(-90deg);-webkit-transform:translate(130px,0) rotate(-90deg)}50%{transform:translate(130px,130px) rotate(-180deg);-webkit-transform:translate(130px,130px) rotate(-180deg)}75%{transform:translate(0,130px) rotate(-270deg);-webkit-transform:translate(0,130px) rotate(-270deg)}100%{transform:rotate(-360deg);-webkit-transform:rotate(-360deg)}}@-webkit-keyframes " + objectId + "two{25%{-webkit-transform:translate(0,130px) rotate(-90deg)}50%{-webkit-transform:translate(-130px,130px) rotate(-180deg)}75%{-webkit-transform:translate(-130px,0) rotate(-270deg)}100%{-webkit-transform:rotate(-360deg)}}@keyframes " + objectId + "two{25%{transform:translate(0,130px) rotate(-90deg);-webkit-transform:translate(0,130px) rotate(-90deg)}50%{transform:translate(-130px,130px) rotate(-180deg);-webkit-transform:translate(-130px,130px) rotate(-180deg)}75%{transform:translate(-130px,0) rotate(-270deg);-webkit-transform:translate(-130px,0) rotate(-270deg)}100%{transform:rotate(-360deg);-webkit-transform:rotate(-360deg)}}@-webkit-keyframes " + objectId + "three{25%{-webkit-transform:translate(0,-130px) rotate(-90deg)}50%{-webkit-transform:translate(130px,-130px) rotate(-180deg)}75%{-webkit-transform:translate(130px,0) rotate(-270deg)}100%{-webkit-transform:rotate(-360deg)}}@keyframes " + objectId + "three{25%{transform:translate(0,-130px) rotate(-90deg);-webkit-transform:translate(0,-130px) rotate(-90deg)}50%{transform:translate(130px,-130px) rotate(-180deg);-webkit-transform:translate(130px,-130px) rotate(-180deg)}75%{transform:translate(130px,0) rotate(-270deg);-webkit-transform:translate(130px,0) rotate(-270deg)}100%{transform:rotate(-360deg);-webkit-transform:rotate(-360deg)}}@-webkit-keyframes " + objectId + "four{25%{-webkit-transform:translate(-130px,0) rotate(-90deg)}50%{-webkit-transform:translate(-130px,-130px) rotate(-180deg)}75%{-webkit-transform:translate(0,-130px) rotate(-270deg)}100%{-webkit-transform:rotate(-360deg)}}@keyframes " + objectId + "four{25%{transform:translate(-130px,0) rotate(-90deg);-webkit-transform:translate(-130px,0) rotate(-90deg)}50%{transform:translate(-130px,-130px) rotate(-180deg);-webkit-transform:translate(-130px,-130px) rotate(-180deg)}75%{transform:translate(0,-130px) rotate(-270deg);-webkit-transform:translate(0,-130px) rotate(-270deg)}100%{transform:rotate(-360deg);-webkit-transform:rotate(-360deg)}}",
                    html: '<div id="' + loadingId + '-center-absolute"><div class="class' + objectId + '" id="' + objectId + '_one"></div><div class="class' + objectId + '" id="' + objectId + '_two"></div><div class="class' + objectId + '" id="' + objectId + '_three"></div><div class="class' + objectId + '" id="' + objectId + '_four"></div><div class="class' + objectId + '" id="' + objectId + '_big"></div></div>'
                },
                {   //  10
                    style: "#" + loadingId + "-center-absolute-one{position:absolute;left:50%;top:50%;height:300px;width:50px;margin-top:-150px;margin-left:-25px}#" + loadingId + "-center-absolute-two{position:absolute;left:50%;top:50%;height:300px;width:50px;margin-top:-150px;margin-left:50px}.class" + objectId + "-one{width:18px;height:18px;background-color:" + objectColor + ";float:left;margin-top:15px;margin-right:15px;-ms-transform:rotate(45deg);-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:object-one 1s infinite;animation:object-one 1s infinite}.class" + objectId + "-two{width:18px;height:18px;background-color:" + objectColor + ";float:left;margin-top:15px;margin-right:15px;-ms-transform:rotate(45deg);-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:object-two 1s infinite;animation:object-two 1s infinite}.class" + objectId + "-one:nth-child(6){-webkit-animation-delay:.6s;animation-delay:.6s}.class" + objectId + "-one:nth-child(5){-webkit-animation-delay:.5s;animation-delay:.5s}.class" + objectId + "-one:nth-child(4){-webkit-animation-delay:.4s;animation-delay:.4s}.class" + objectId + "-one:nth-child(3){-webkit-animation-delay:.3s;animation-delay:.3s}.class" + objectId + "-one:nth-child(2){-webkit-animation-delay:.2s;animation-delay:.2s}.class" + objectId + "-two:nth-child(9){-webkit-animation-delay:.9s;animation-delay:.9s}.class" + objectId + "-two:nth-child(8){-webkit-animation-delay:.8s;animation-delay:.8s}.class" + objectId + "-two:nth-child(7){-webkit-animation-delay:.7s;animation-delay:.7s}.class" + objectId + "-two:nth-child(6){-webkit-animation-delay:.6s;animation-delay:.6s}.class" + objectId + "-two:nth-child(5){-webkit-animation-delay:.5s;animation-delay:.5s}.class" + objectId + "-two:nth-child(4){-webkit-animation-delay:.4s;animation-delay:.4s}.class" + objectId + "-two:nth-child(3){-webkit-animation-delay:.3s;animation-delay:.3s}.class" + objectId + "-two:nth-child(2){-webkit-animation-delay:.2s;animation-delay:.2s}@-webkit-keyframes object-one{50%{-ms-transform:translate(100px,0);-webkit-transform:translate(100px,0);transform:translate(100px,0)}}@keyframes object-one{50%{-ms-transform:translate(100px,0);-webkit-transform:translate(100px,0);transform:translate(100px,0)}}@-webkit-keyframes object-two{50%{-ms-transform:translate(-100px,0);-webkit-transform:translate(-100px,0);transform:translate(-100px,0)}}@keyframes object-two{50%{-ms-transform:translate(-100px,0);-webkit-transform:translate(-100px,0);transform:translate(-100px,0)}}",
                    html: '<div id="' + loadingId + '-center-absolute-one"><div class="object-one"></div><div class="object-one"></div><div class="object-one"></div><div class="object-one"></div><div class="object-one"></div><div class="object-one"></div></div><div id="' + loadingId + '-center-absolute-two"><div class="object-two"></div><div class="object-two"></div><div class="object-two"></div><div class="object-two"></div><div class="object-two"></div><div class="object-two"></div></div>'
                },
                {
                    //  11
                    style: "",
                    html: '<div style="text-align: center;">' +
                                '<div><span style="width:50px;height:50px;display:inline-block;background:url(images/option-btn-ico-32.gif) center center no-repeat;background-size:cover;"></span></div>' +
                                '<div>' + params.text + '</div>' +
                            '</div>'
                }
            ];

        var effectHtml = '<style>' + effect[params.effectIndex].style + '.' + loadingId + '-preloader{transform: scale(0.4);height: 50px;}</style><div class="' + loadingId + '-preloader">' + effect[params.effectIndex].html + '</div>';

        if (params.effectIndex == 10) {
            effectHtml = effect[params.effectIndex].html
        }
        
        return tools.modal({
            title: title,
            text: effectHtml,
            cssClass: 'bybon-modal-preloader'
        });
    };
    //关闭预加载框
    tools.hidePreloader = function (modal) {
        modal.find(".bybon-modal-dialog").addClass("bybon-modal-out").removeClass("bybon-modal-in");
        setTimeout(function () { modal.remove(); }, 250);
        return modal;
    };
    // Modals End

    /* 加载进度条 */
    //LoadProgressBar
    //显示加载进度条
    tools.showLoadProgressBar = function (options) {

        var defvalue = { bgColor: "#f00", height: 3, progresBarId: null };
        $.extend(defvalue, options);

        var random = Math.floor(Math.random() * (100 + 1)),
            progresBarId = tools.params.loadProgressBarId ? tools.params.loadProgressBarId : "progres" + random,
            progressBarHtml
                = "<style>"
                + "#" + progresBarId + "Anim{background-color:" + tools.params.loadProgressBarColor + ";position:absolute;width:100%;height:100%;-webkit-transform:translate3d(0,0,0);-moz-transform: translate3d(0,0,0);-ms-transform: translate3d(0,0,0);-o-transform: translate3d(0,0,0);transform: translate3d(0,0,0);-webkit-animation: 2s " + progresBarId + "_anim cubic-bezier(0.4, 0, 1, 1) infinite;-moz-animation: 2s " + progresBarId + "_anim cubic-bezier(0.4, 0, 1, 1) infinite;-ms-animation: 2s " + progresBarId + "_anim cubic-bezier(0.4, 0, 1, 1) infinite;-o-animation: 2s " + progresBarId + "_anim cubic-bezier(0.4, 0, 1, 1) infinite;animation: 2s " + progresBarId + "_anim cubic-bezier(0.4, 0, 1, 1) infinite;}"
                + "@-webkit-keyframes " + progresBarId + "_anim {0% {-webkit-transform:translate3d(-100%,0,0);}100% {-webkit-transform: translate3d(0,0,0);}}"
                + "@-moz-keyframes " + progresBarId + "_anim {0% {-moz-transform:translate3d(-100%,0,0);}100% {-moz-transform: translate3d(0,0,0);}}"
                + "@-ms-keyframes " + progresBarId + "_anim {0% {-ms-transform: translate3d(-100%,0,0);}100% {-ms-transform: translate3d(0,0,0);}}"
                + "@-o-keyframes " + progresBarId + "_anim {0% {-o-transform: translate3d(-100%,0,0);}100% {-o-transform: translate3d(0,0,0);}}"
                + "@keyframes " + progresBarId + "_anim {0% {transform: translate3d(-100%,0,0);}100% {transform: translate3d(0,0,0);}}"
                + "</style>"
                + "<div id=\"" + progresBarId + "Anim\"></div>",
            lpb = $('<div id=' + progresBarId + ' class="' + tools.params.loadProgressBarClass + '" style="z-index: 99999; position: fixed; top: 0; left: 0; width: 100%; height: ' + tools.params.loadProgressBarHeight + 'px; background-color: transparent"></div>');

        if ($("#" + progresBarId).length > 0) {
            $("#" + progresBarId).remove();
        }

        lpb.html(progressBarHtml).appendTo('body');
    }
    //关闭加载进度条
    tools.closeLoadProgressBar = function (progresBarId) {
        (arguments[0] != undefined) ? $("#" + progresBarId).remove() : $("." + tools.params.loadProgressBarClass).remove();
    }

    /* 页面提示消息 */
    //显示提示消息
    tools.showMessage = function (params) {
        params = params || {};
        var html
            = '<style>'
            + '.bybon-message {position: absolute;width: 100%;bottom: -100px;left: 0;background-color: transparent;text-align: center;opacity: 0;z-index: -1;visibility: hidden;}'
            + '.bybon-message-cnt {font-size: 12px;padding: 8px 12px;border-radius: 7px;background-color: ' + (params.messageBgColor ? params.messageBgColor : tools.params.messageBgColor) + ';color: ' + (params.messageTextColor ? params.messageTextColor : tools.params.messageTextColor) + ';line-height: 1.5em;display: inline-block;margin: 0 15px;}'
            + '.bybon-message-in>.bybon-message{opacity: 1;z-index: 9998;visibility: visible;bottom: ' + (params.messageBottom ? params.messageBottom : tools.params.messageBottom) + ';-webkit-transition-duration: 200ms;transition-duration: 200ms;}'
            + '.bybon-message-out>.bybon-message{opacity: 0;z-index: -1;visibility: hidden;bottom: -100px;-webkit-transition-duration: 200ms;transition-duration: 200ms;}'
            + '</style>'
            + '<div class="bybon-message"><span class="bybon-message-cnt">' + params.text + '</span></div>';

        var overlay = $('<div style="position:fixed;background-color:transparent;top:0;right:0;bottom:0;left:0;z-index:999"></div>').html(html).appendTo("body");

        setTimeout(function () { overlay.addClass("bybon-message-in").removeClass("bybon-message-out"); }, 10);

        if (params.messageClose != undefined ? params.messageClose : tools.params.messageClose) {
            setTimeout(function () {
                tools.closeMessage(overlay);
            }, params.messageOutTime ? params.messageOutTime : tools.params.messageOutTime);
        }
        return overlay;
    }
    //加载中
    tools.loading = function (objs) {
        var loadingHtml = '<div style="background: url(../addons/amouse_mobile_recycle/ui/images/loadingnew.gif) center center no-repeat; background-size: auto 100%; height: 36px;"></div>';
        for (var i in objs) {
            $(objs[i]).append(loadingHtml);
        }
    }

    //关闭示消息
    tools.closeMessage = function (m) {
        m.addClass("bybon-message-out").removeClass("bybon-message-in");
        setTimeout(function () { m.remove(); }, 210);
    }
}

function showAlert(msg){
	var myTools = new bybonTools();	
	myTools.modal({
        title: "温馨提示",
        text: msg,
        buttons: [
            {
                text: "确定",
                bold: false,
                close: true,
                onClick: function () {
                   //	 console.log("确定");
                }
            }
        ],
        verticalButtons: false,
        onClick: function () {
            //console.log("222");
        }
    });
}
function showMsg(msg){
	var myTools = new bybonTools();	
	params = [];
	params['text']=msg;
	myTools.showMessage(params);
}
/*
 * url 字符串 请求地址
 * data 对象 请求参数
 * medthod 字符串 get、post请求方式
 */
var is_scrobj=true;
function srcobj(url,data,medthod){
	var $doc_height,$s_top,$now_height;
	$doc_height = $(document).height();        //这里是document的整个高度
	$s_top = $(this).scrollTop();            //当前滚动条离最顶上多少高度
	$now_height = $(this).height();            //这里的this 也是就是window对象
	if(($doc_height - $s_top - $now_height) < 10){
		if(is_scrobj==true){
			is_scrobj = false;
			var val = getAjaxReturn(url,data,medthod);
			is_scrobj = true;
			return val;
		}else{
			return false;	
		}
	}else{
		return false;
	}
}

//调用实例
/*var _BabonAPP = "__APP__/Wechat/Recycle/";
var data=[];
data['id']=1;
data['name']=2;
$(window).scroll(function(){
	var id=srcobj(_BabonAPP+'test',data,'get');
	if(id){
		alert(id);
	} 
});*/


/*
 * url 字符串 请求地址
 * data 对象 请求参数
 * medthod 字符串 get、post请求方式
 */
function getAjaxReturn(url,data,medthod){ 
	var t = new bybonTools();
	var m = t.showPreloader({ effectIndex: 1 });
	var bol =AjaxReturn(url,data,medthod);
	t.hidePreloader(m);
	return bol; 
} 

function AjaxReturn(url,medthod){
	var bol;
	$.ajax({ 
		type:medthod, 
		async:false, 
		url:url,
		dataType: "json",
		success:function(data){ 
			bol=data; 
		} 
	});
	return bol; 
}