; (function(factory) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], factory);
    } else {
        factory(jQuery);
    }
} (function($) {
    $.extend({
        xiaof: {
            parseForm: function() {
                $(document).on("click", ".xiaof-input-radio",
                function() {
                    var radioNode = $(this).find("input:radio");
                    $(".xiaof-input-radio").find("i").remove();
                    if (radioNode.attr("checked") == "checked") {
                        radioNode.attr("checked", false);
                    } else {
                        $(".xiaof-input-radio").find("input:radio").attr("checked", false);
                        radioNode.attr("checked", true);
                        $(this).find(".xiaof-form-control").prepend('<i class="fa fa-check"></i>');
                    }
                });
            },
            getRand: function() {
                var number = (new Date()).valueOf();
                var string = number.toString() + Math.floor(Math.random() * 1000).toString();
                return string;
            },
            setCookie: function(name, value, expiredays) {
                var ExpireDate = new Date();
                ExpireDate.setTime(ExpireDate.getTime() + (expiredays * 24 * 3600 * 1000));
                document.cookie = name + "=" + escape(value) + ((expiredays == null) ? "": "; expires=" + ExpireDate.toGMTString());
            },
            getCookie: function(name) {
                var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
                if (arr != null) return unescape(arr[2]);
                return null;
            },
            inArray: function(search, array) {
                for (var i in array) {
                    if (array[i] == search) {
                        return true;
                    }
                }
                return false;
            },
            loader: function(content, autoclose) {
                var loaderNode;
                if ($(".xiaof-tips-loader").length > 0) {
                    loaderNode = $(".xiaof-tips-loader");
                    loaderNode.find(".xiaof-tips-content").html(content);
                    loaderNode.show();
                } else {
                    var loaderHtml = ['<div class="xiaof-tips-loader">', '	<div class="spinner">', '	  <div class="spinner-container container1">', '		<div class="circle1"></div>', '		<div class="circle2"></div>', '		<div class="circle3"></div>', '		<div class="circle4"></div>', '	  </div>', '	  <div class="spinner-container container2">', '		<div class="circle1"></div>', '		<div class="circle2"></div>', '		<div class="circle3"></div>', '		<div class="circle4"></div>', '	  </div>', '	  <div class="spinner-container container3">', '		<div class="circle1"></div>', '		<div class="circle2"></div>', '		<div class="circle3"></div>', '		<div class="circle4"></div>', '	  </div>', '	</div>', '	<div class="xiaof-tips-content">' + content + '', '	</div>', '</div>'].join("");
                    $("body").append(loaderHtml);
                    loaderNode = $(".xiaof-tips-loader");
                }

                if (autoclose == true) {
                    setTimeout(function() {
                        loaderNode.hide();
                    },
                    3000);
                }
            },
            confirm: function(title, content, callback) {
                var confirmNode;
                if ($("#xiaof-dialog-confirm").length > 0) {
                    confirmNode = $("#xiaof-dialog-confirm");
                    confirmNode.find(".xiaof-dialog-title strong").html(title);
                    confirmNode.find(".xiaof-dialog-content").html(content);
                    confirmNode.show();
                } else {
                    var dialogconfirm = ['<div id="xiaof-dialog-confirm">', '    <div class="xiaof-dialog-mask"></div>', '    <div class="xiaof-dialog">', '        <div class="xiaof-dialog-title"><strong>' + title + '</strong></div>', '        <div class="xiaof-dialog-content">' + content + '</div>', '        <div class="xiaof-dialog-footer xiaof-box">', '            <a href="javascript:;" status="cancel" class="xiaof-box-item default">取消</a>', '            <a href="javascript:;" status="success" class="xiaof-box-item primary">确定</a>', '        </div>', '    </div>', '</div>'].join("");
                    $("body").append(dialogconfirm);
                    confirmNode = $("#xiaof-dialog-confirm");
                }
                confirmNode.find(".xiaof-dialog-footer a").click(function() {
                    confirmNode.hide();
                    if (typeof callback === 'function') {
                        callback($(this).attr("status"), confirmNode);
                    }
                });
            },
            alert: function(title, content, callback) {
                var alertNode;
                if ($("#xiaof-dialog-arlet").length > 0) {
                    alertNode = $("#xiaof-dialog-arlet");
                    alertNode.find(".xiaof-dialog-title strong").html(title);
                    alertNode.find(".xiaof-dialog-content").html(content);
                    alertNode.show();
                } else {
                    var dialogalert = ['<div id="xiaof-dialog-arlet">', '    <div class="xiaof-dialog-mask"></div>', '    <div class="xiaof-dialog">', '        <div class="xiaof-dialog-title"><strong>' + title + '</strong></div>', '        <div class="xiaof-dialog-content">' + content + '</div>', '        <div class="xiaof-dialog-footer xiaof-box">', '            <a href="javascript:;" class="xiaof-box-item primary">确定</a>', '        </div>', '    </div>', '</div>'].join("");
                    $("body").append(dialogalert);
                    alertNode = $("#xiaof-dialog-arlet");
                }
                alertNode.find(".xiaof-dialog-footer a").click(function() {
                    alertNode.hide();
                    if (typeof callback === 'function') {
                        callback(alertNode);
                    }					
                });
            }
        }
    })
}));