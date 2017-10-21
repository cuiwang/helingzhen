function focusAIM() {
    var htmlObj = [],
        $focusAIM,
        bodyH;
    htmlObj.push('<div id="focusAIM">');
    htmlObj.push('<div class="fc-inner">');
    htmlObj.push('<div class="fc-text"></div>');
    htmlObj.push('<div class="fc-image"></div>');
    htmlObj.push('<div class="fc-method fc-line1"><span class="sub-title"></span><span>点击右上角“查看公众号”，点击关注</span></div>');
    htmlObj.push('<div class="fc-method fc-line2"><span class="sub-title"></span><span>点击下方按钮进入文章，按照提示关注</span></div>');
    htmlObj.push('<div class="btn-focus"><a href="' + params.OneKeyFollow + '">关注公众号</a></div>');

    htmlObj.push('<div class="fc-tips">关注后，记得点击分享链接回来玩游戏哦</div>');
    htmlObj.push('</div></div>');

    $('body').append(htmlObj.join(''));
    $focusAIM = $("#focusAIM");
    // bodyH = Math.max($('body').height(),$(window).height());
    // $focusAIM.height( bodyH );
    $focusAIM.find(".fc-inner").addClass('fadeInDownBig')
        .on("touchmove", function () {
            event.preventDefault();
        });
}