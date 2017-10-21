//分享到朋友圈
var title = '{$share_data['share_title']}';
alert(title);
wx.ready(function(){
wx.onMenuShareTimeline({
    title: title, // 分享标题
    link: 'www.baidu.com', // 分享链接
    imgUrl: '', // 分享图标
    success: function () { 
        // 用户确认分享后执行的回调函数
        alert('分享成功');
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
        alert("分享失败");
    }
});

//分享给好友
wx.onMenuShareAppMessage({
    title: '测试', // 分享标题
    desc: 'dhfkjashdfjhsdfhashdfkjsdhfjkasdhfshfkjs', // 分享描述
    link: 'www.baidu.com', // 分享链接
    imgUrl: '', // 分享图标
    type: '', // 分享类型,music、video或link，不填默认为link
    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
    success: function () { 
        // 用户确认分享后执行的回调函数
         alert('分享成功');
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
         alert('分享失败');
    }
});

});