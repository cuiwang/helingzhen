/**
 * Created by Will on 2015/1/9.
 */
//通过config接口注入权限验证配置
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: params.appid, // 必填，公众号的唯一标识
    timestamp: params.timestamp, // 必填，生成签名的时间戳
    nonceStr: params.nonceStr, // 必填，生成签名的随机串
    signature: params.signature,// 必填，签名，见附录1
    jsApiList: [
                //必填，需要使用的JS接口列表，所有JS接口列表见附录2
                'checkJsApi',
                'getNetworkType',
                'onMenuShareAppMessage',
                'onMenuShareTimeline'
                ] 
});

//通过ready接口处理成功验证
wx.ready(function (){
    // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
        wx.checkJsApi({
            jsApiList: [
                'checkJsApi',
                'getNetworkType',
                'onMenuShareAppMessage',
                'onMenuShareTimeline'
            ],
            success: function (res) {
//            	alert(JSON.stringify(res));
            	if(res.errMsg != "checkJsApi:ok"){
            		alert("请升级您的微信版本！");
            		return;
            	}
            }
        });

    // 2. 分享接口
    // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
        wx.onMenuShareAppMessage({
            title: shareData.title,
            desc: shareData.desc,
            link: shareData.link,
            imgUrl: shareData.imgUrl,
            trigger: function (res) {
//                alert('用户点击发送给朋友');
            },
            success: function (res) {
            //    alert('已分享');
				$.post("shareServlet.action");
            },
            cancel: function (res) {
//                alert('已取消');
            },
            fail: function (res) {
//                alert(JSON.stringify(res));
            }
        });

    // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
        wx.onMenuShareTimeline({
        	title: shareData.title,
        	link: shareData.link,
        	imgUrl: shareData.imgUrl,
            trigger: function (res) {
//                alert('用户点击分享到朋友圈');
            },
            success: function (res) {
//               alert('已分享');
				$.post("shareServlet.action");
            },
            cancel: function (res) {
//                alert('已取消');
            },
            fail: function (res) {
//                alert(JSON.stringify(res));
            }
        });
        
        // 6 设备信息接口
        // 6.1 获取当前网络状态
            wx.getNetworkType({
                success: function (res) {
                    if(res.networkType != "wifi"){
//                    	alert("您当前不在wifi环境！");
                    }
                },
                fail: function (res) {
//                    alert(JSON.stringify(res));
                }
            });
            
        return;
    // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
    document.querySelector('#onMenuShareQQ').onclick = function () {
        wx.onMenuShareQQ({
            title: '',
            desc: '',
            link: '',
            imgUrl: '',
            trigger: function (res) {
//               alert('用户点击分享到QQ');
            },
            complete: function (res) {
//                alert(JSON.stringify(res));
            },
            success: function (res) {
//                alert('已分享');
            },
            cancel: function (res) {
//                alert('已取消');
            },
            fail: function (res) {
//                alert(JSON.stringify(res));
            }
        });
        alert('已注册获取“分享到 QQ”状态事件');
    };

    // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
    document.querySelector('#onMenuShareWeibo').onclick = function () {
        wx.onMenuShareWeibo({
            title: '',
            desc: '',
            link: '',
            imgUrl: '',
            trigger: function (res) {
//                alert('用户点击分享到微博');
            },
            complete: function (res) {
//                alert(JSON.stringify(res));
            },
            success: function (res) {
//                alert('已分享');
            },
            cancel: function (res) {
//                alert('已取消');
            },
            fail: function (res) {
//                alert(JSON.stringify(res));
            }
        });
//       alert('已注册获取“分享到微博”状态事件');
    };


    // 3 智能接口
    var voice = {
        localId: '',
        serverId: ''
    };
    // 3.1 识别音频并返回识别结果
    document.querySelector('#translateVoice').onclick = function () {
        if (voice.localId == '') {
//            alert('请先使用 startRecord 接口录制一段声音');
            return;
        }
        wx.translateVoice({
            localId: voice.localId,
            complete: function (res) {
                if (res.hasOwnProperty('translateResult')) {
                    alert('识别结果：' + res.translateResult);
                } else {
                    alert('无法识别');
                }
            }
        });
    };

    // 4 音频接口
    // 4.2 开始录音
    document.querySelector('#startRecord').onclick = function () {
        wx.startRecord({
            cancel: function () {
                alert('用户拒绝授权录音');
            }
        });
    };

    // 4.3 停止录音
    document.querySelector('#stopRecord').onclick = function () {
        wx.stopRecord({
            success: function (res) {
                voice.localId = res.localId;
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };

    // 4.4 监听录音自动停止
    wx.onVoiceRecordEnd({
        complete: function (res) {
            voice.localId = res.localId;
            alert('录音时间已超过一分钟');
        }
    });

    // 4.5 播放音频
    document.querySelector('#playVoice').onclick = function () {
        if (voice.localId == '') {
            alert('请先使用 startRecord 接口录制一段声音');
            return;
        }
        wx.playVoice({
            localId: voice.localId
        });
    };

    // 4.6 暂停播放音频
    document.querySelector('#pauseVoice').onclick = function () {
        wx.pauseVoice({
            localId: voice.localId
        });
    };

    // 4.7 停止播放音频
    document.querySelector('#stopVoice').onclick = function () {
        wx.stopVoice({
            localId: voice.localId
        });
    };

    // 4.8 监听录音播放停止
    wx.onVoicePlayEnd({
        complete: function (res) {
            alert('录音（' + res.localId + '）播放结束');
        }
    });

    // 4.8 上传语音
    document.querySelector('#uploadVoice').onclick = function () {
        if (voice.localId == '') {
            alert('请先使用 startRecord 接口录制一段声音');
            return;
        }
        wx.uploadVoice({
            localId: voice.localId,
            success: function (res) {
                alert('上传语音成功，serverId 为' + res.serverId);
                voice.serverId = res.serverId;
            }
        });
    };

    // 4.9 下载语音
    document.querySelector('#downloadVoice').onclick = function () {
        if (voice.serverId == '') {
            alert('请先使用 uploadVoice 上传声音');
            return;
        }
        wx.downloadVoice({
            serverId: voice.serverId,
            success: function (res) {
                alert('下载语音成功，localId 为' + res.localId);
                voice.localId = res.localId;
            }
        });
    };

    // 5 图片接口
    // 5.1 拍照、本地选图
    var images = {
        localId: [],
        serverId: []
    };
    document.querySelector('#chooseImage').onclick = function () {
        wx.chooseImage({
            success: function (res) {
                images.localId = res.localIds;
                alert('已选择 ' + res.localIds.length + ' 张图片');
            }
        });
    };

    // 5.2 图片预览
    document.querySelector('#previewImage').onclick = function () {
        wx.previewImage({
            current: 'http://img5.douban.com/view/photo/photo/public/p1353993776.jpg',
            urls: [
                'http://img3.douban.com/view/photo/photo/public/p2152117150.jpg',
                'http://img5.douban.com/view/photo/photo/public/p1353993776.jpg',
                'http://img3.douban.com/view/photo/photo/public/p2152134700.jpg'
            ]
        });
    };

    // 5.3 上传图片
    document.querySelector('#uploadImage').onclick = function () {
        if (images.localId.length == 0) {
            alert('请先使用 chooseImage 接口选择图片');
            return;
        }
        var i = 0, length = images.localId.length;
        images.serverId = [];
        function upload() {
            wx.uploadImage({
                localId: images.localId[i],
                success: function (res) {
                    i++;
                    alert('已上传：' + i + '/' + length);
                    images.serverId.push(res.serverId);
                    if (i < length) {
                        upload();
                    }
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        }
        upload();
    };

    // 5.4 下载图片
    document.querySelector('#downloadImage').onclick = function () {
        if (images.serverId.length === 0) {
            alert('请先使用 uploadImage 上传图片');
            return;
        }
        var i = 0, length = images.serverId.length;
        images.localId = [];
        function download() {
            wx.downloadImage({
                serverId: images.serverId[i],
                success: function (res) {
                    i++;
                    alert('已下载：' + i + '/' + length);
                    images.localId.push(res.localId);
                    if (i < length) {
                        download();
                    }
                }
            });
        }
        download();
    };


    // 8 界面操作接口
    // 8.1 隐藏右上角菜单
    document.querySelector('#hideOptionMenu').onclick = function () {
        wx.hideOptionMenu();
    };

    // 8.2 显示右上角菜单
    document.querySelector('#showOptionMenu').onclick = function () {
        wx.showOptionMenu();
    };

    // 8.3 批量隐藏菜单项
    document.querySelector('#hideMenuItems').onclick = function () {
        wx.hideMenuItems({
            menuList: [
                'menuItem:readMode', // 阅读模式
                'menuItem:share:timeline', // 分享到朋友圈
                'menuItem:copyUrl' // 复制链接
            ],
            success: function (res) {
                alert('已隐藏“阅读模式”，“分享到朋友圈”，“复制链接”等按钮');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };

    // 8.4 批量显示菜单项
    document.querySelector('#showMenuItems').onclick = function () {
        wx.showMenuItems({
            menuList: [
                'menuItem:readMode', // 阅读模式
                'menuItem:share:timeline', // 分享到朋友圈
                'menuItem:copyUrl' // 复制链接
            ],
            success: function (res) {
                alert('已显示“阅读模式”，“分享到朋友圈”，“复制链接”等按钮');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };

    // 8.5 隐藏所有非基本菜单项
    document.querySelector('#hideAllNonBaseMenuItem').onclick = function () {
        wx.hideAllNonBaseMenuItem({
            success: function () {
                alert('已隐藏所有非基本菜单项');
            }
        });
    };

    // 8.6 显示所有被隐藏的非基本菜单项
    document.querySelector('#showAllNonBaseMenuItem').onclick = function () {
        wx.showAllNonBaseMenuItem({
            success: function () {
                alert('已显示所有非基本菜单项');
            }
        });
    };

    // 8.7 关闭当前窗口
    document.querySelector('#closeWindow').onclick = function () {
        wx.closeWindow();
    };

    // 9 微信原生接口
    // 9.1.1 扫描二维码并返回结果
    document.querySelector('#scanQRCode0').onclick = function () {
        wx.scanQRCode({
            desc: 'scanQRCode desc'
        });
    };
    // 9.1.2 扫描二维码并返回结果
    document.querySelector('#scanQRCode1').onclick = function () {
        wx.scanQRCode({
            needResult: 1,
            desc: 'scanQRCode desc',
            success: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };

    // 10 微信支付接口
    // 10.1 发起一个支付请求
    document.querySelector('#chooseWXPay').onclick = function () {
        wx.chooseWXPay({
            timestamp: 1414723227,
            nonceStr: 'noncestr',
            package: 'addition=action_id%3dgaby1234%26limit_pay%3d&bank_type=WX&body=innertest&fee_type=1&input_charset=GBK&notify_url=http%3A%2F%2F120.204.206.246%2Fcgi-bin%2Fmmsupport-bin%2Fnotifypay&out_trade_no=1414723227818375338&partner=1900000109&spbill_create_ip=127.0.0.1&total_fee=1&sign=432B647FE95C7BF73BCD177CEECBEF8D',
            paySign: 'bd5b1933cda6e9548862944836a9b52e8c9a2b69'
        });
    };

    // 11.3  跳转微信商品页
    document.querySelector('#openProductSpecificView').onclick = function () {
        wx.openProductSpecificView({
            productId: 'pDF3iY0ptap-mIIPYnsM5n8VtCR0'
        });
    };

    // 12 微信卡券接口
    // 12.1 添加卡券
    document.querySelector('#addCard').onclick = function () {
        wx.addCard({
            cardList: [
                {
                    cardId: 'pDF3iY9tv9zCGCj4jTXFOo1DxHdo',
                    cardExt: '{"code": "", "openid": "", "timestamp": "1418301401", "signature":"64e6a7cc85c6e84b726f2d1cbef1b36e9b0f9750"}'
                },
                {
                    cardId: 'pDF3iY9tv9zCGCj4jTXFOo1DxHdo',
                    cardExt: '{"code": "", "openid": "", "timestamp": "1418301401", "signature":"64e6a7cc85c6e84b726f2d1cbef1b36e9b0f9750"}'
                }
            ],
            success: function (res) {
                alert('已添加卡券：' + JSON.stringify(res.cardList));
            }
        });
    };

    // 12.2 选择卡券
    document.querySelector('#chooseCard').onclick = function () {
        wx.chooseCard({
            cardSign: '97e9c5e58aab3bdf6fd6150e599d7e5806e5cb91',
            timestamp: 1417504553,
            nonceStr: 'k0hGdSXKZEj3Min5',
            success: function (res) {
                alert('已选择卡券：' + JSON.stringify(res.cardList));
            }
        });
    };

    // 12.3 查看卡券
    document.querySelector('#openCard').onclick = function () {
        alert('您没有该公众号的卡券无法打开卡券。');
        wx.openCard({
            cardList: [
            ]
        });
    };
});

wx.error(function (res) {
    alert(res.errMsg);
});
