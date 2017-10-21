 window.loadJsConfig = {
                modules: {
                    publish: {
                        list: [_url("{MODULE_URL}template/mobile/default/new/js/upload.min.1b38cd17.js"), _url("{MODULE_URL}template/mobile/default/new/js/publish.min.95bff006.js")],
                        check: ["Upload", "Publish"]
                    },
                    publishNative: {
                        list: [_url("{MODULE_URL}template/mobile/default/new/js/publish_native.min.d6ee0c25.js")],
                        check: ["Publish"]
                    },
                    image_view: {
                        list: [_url("{MODULE_URL}template/mobile/default/new/js/image_view.min.331e2ee8.js")],
                        check: ["ImageView"]
                    },
                    image_view_native: {
                        list: [_url("{MODULE_URL}template/mobile/default/new/js/image_view_native.min.315ddb1b.js")],
                        check: ["ImageView"]
                    }
                }
            },
            window.loadCssConfig = {
                wechatCss: _url("{MODULE_URL}template/mobile/default/new/css/detail.wechat.min.42cb2e22.css")
            }