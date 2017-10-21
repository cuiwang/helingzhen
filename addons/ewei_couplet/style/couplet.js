var couplet = {
    shareMeta: {},
    showLoading: function (show) {
        couplet.showMask(show);
        if (show) {
            $("#loading").show();
        }
        else {
            $("#loading").hide();
        }
    }
    , getApiUrl: function (method, param) {
 
        var url = couplet.baseUrl.replace("method", method) ;
        if (param) {
            for (var key in param) {
                url += "&" + key + "=" + param[key];
            }
        }
       
        return url;
    }
    , showMask: function (show) {
        if (show) {
            $(".dialog_mask").show();
        }
        else {
            $(".dialog_mask").hide();
        }
    }
    , showRule: function (show) {
        couplet.showMask(show);
        if (show) {
            $("#ruleDialog").show();
        }
        else {
            $("#ruleDialog").hide();
        }

    }, 
 
    getEnv:function() {
		var ua = navigator.userAgent.toLowerCase();
		if (/micromessenger(\/[\d\.]+)*/.test(ua)) {
			return "weixin";
		} else if (/qq\/(\/[\d\.]+)*/.test(ua) || /qzone\//.test(ua)) {
			return "qq";
		} else {
			return "h5";
		}
    },
    init: function (options) {
        var self = this;
        this.openid = options.openid;
        this.fromopenid = options.fromopenid;
        this.nickname = options.nickname;
        this.fromnickname = options.fromnickname;
        this.baseUrl = options.baseUrl;
        this.hasOther = options.hasOther;
        this.needHelpers = options.needHelpers;
        this.shareMeta = options.shareMeta;
        if(this.getEnv() != "weixin") {
	this.showDialog("请在微信环境中参与活动！", "确定");
	return ;
         }
      
        $("#toRule").click(function(){
            couplet.showRule(true);
        })
        $(".dialog .close").click(function () {
            self.hideAll();
        });
        if( this.needHelpers){
          
            this.getHelpers();
        }
            $("#metoo").click(function(){
                couplet.getCouplet();
            })
            $("#doHelp").click(function(){
                couplet.help();
         });
             $("#youtoo").click(function(){
                couplet.showShare();
            });
            
            self.bindSubmitInfo();
     
    },
    showShare:function(){
       $("#shareTips").addClass('active').unbind('click').click(function(){
            $(this).removeClass('active');
       });
    }
    ,showDialog:function(msg,btnmsg,callback) {
        couplet.showMask(true);
        btnmsg = btnmsg || "确定";
        $("#dialog").show();
        $("#dialog h3").html( msg );
        $("#dialog .close").hide().unbind('click');
        $("#dialog .btn_submit").html(btnmsg).click(function(){
            callback && callback();
        })
    },
    hideAll:function(){
        $(".dialog_mask").hide();
         $("#dialog").hide();
		$("#coupletDialog").hide();
		$("#ruleDialog").hide();
		$("#loading").hide();
    },
    getCouplet:function(){
    
         var self = this;
         self.showLoading(true);
        $.ajax({
                url: this.getApiUrl("getcouplet",{openid: this.openid}),
                type:"post",
                success:function(d){
                    self.showLoading(false);
                    if(d){
                        var r = JSON.parse(d);
                   
                        if(r.result ==0){
                             couplet.showDialog( r.message,null,function(){ self.hideAll(); });
                        }
                        else if (r.result==1){
                            //抽中对联
                            couplet.showDialog( "恭喜您获得上联: <br/>" + r.uptext,"邀请好友对下联 赢大奖",function(){
                                location.href = self.getApiUrl("index") ;
                            });
                        }
                        else if(r.result==2){
                            //未关注
                            location.href= r.url;
                        }
                    }
                    else{
                          couplet.showDialog("服务器内部错误,请重试!");
                    }
                    

                }

        });
    },
    
    help: function(){
        var self = this;
       setTimeout(function(){
        self.showLoading(true);
        $.ajax({
                url: self.getApiUrl("help",{openid: self.openid,fromopenid:self.fromopenid}),
                type:"post",
                success:function(d){
                    if(d){
                        var r = JSON.parse(d);
                        if(r.result ==0){
                           self.showLoading(false);
                           couplet.showDialog( r.message,null,function(){ self.hideAll(); });
                        }
                        else if (r.result==1){
                          
                            //未抽中
                           
                                   self.showLoading(false);
                                   self.helperPage = 0;self.helperLoaded = false;self.getHelpers();
                                
                                 
                                    couplet.showDialog(r.desc,"我也要赢取大奖",function(){
                                       location.href = couplet.getApiUrl('index');
                                    });
                           
                        }
                        else if(r.result==2){
                            //抽中
                       
                                    self.showLoading(false);
                                    self.helperPage = 0;self.helperLoaded = false;self.getHelpers();
                                    $("#mycouplet .right i:eq(" + r.pos + ")").html( r.char );
                                    self.showLoading(false);
                                   
                                  
                                   if(r.num>=7){
                                       //对方全抽中
                                       couplet.showDialog(r.desc + "<br/> 哈哈，您的朋友凑齐对联了，赶紧告诉 TA 领取大奖吧!","我也要赢取大奖",function(){      
                                           location.href = couplet.getApiUrl('index');
                                       }); 
                                   }
                                   else{
                                    couplet.showDialog(r.desc,"我也要赢取大奖",function(){
                                       location.href = couplet.getApiUrl('index');
                                    });
                                   }
                            
                        }
                    }
                    else{
                          couplet.showDialog("服务器内部错误,请重试!");
                    }
                    

                }

        });
        },1000);
    },
    helperPage: 0,
    helperLoaded: false,
    getHelpers:function(){
            var self =this;
            var openid = self.hasOther?self.fromopenid: self.openid;
            if( this.helperLoaded ){
                return;
            }
            $("#more_helpers").html("正在获取更多好友....");
            self.helperPage++;
            $.ajax({
                url: this.getApiUrl("gethelpers",{page: self.helperPage, openid: openid}),
                cache:false,
                success:function(d){
        
                    if(d!=''){
                        $("#friendMore").show();
                    }
                    if(d.indexOf('item')==-1){
                        self.helperLoaded = true;
                        $("#more_helpers").remove();
                       
                    }
                    else{
                         $("#friendList").append(d);
                         $("#more_helpers").html("获取更多帮助的好友");
                    }
                    $("#more_helpers").unbind('click').click(function(){
                             self.getHelpers();
                    })
                }
         });
    },
    bindSubmitInfo:function(){
        var self = this;
        $("#meaward").click(function(){
            self.showMask(true);
            $("#infoDialog").show();
            $("#infoDialog .close").unbind('click').click(function(){
                 $("#infoDialog").hide();
                 self.hideAll();
            });
            $("#infoDialog .btn_submit").unbind('click').click(function(){
               
               var realname = $("#realname").val();
               var mobile = $("#mobile").val();
               if(realname==''){
                   $("#realname").focus();
                   return;
               }
               if(mobile==''){
                   $("#mobile").focus();
                   return;
               }
               self.showLoading(true);
                $.ajax({
                        url: self.getApiUrl("info"),
                        type:"post",
                        data: {openid: self.openid,realname: realname,mobile:mobile},
                        dataType:"json",
                        success:function(r){
                            self.showLoading(false);
                            if(r.result==1){
                                  $("#infoDialog").hide();
                                  self.hideAll();
                                  self.showDialog("成功提交信息, 我们尽快与您联系!",null,function(){
                                      location.reload();
                                  })
                            }
                            else{
                                self.showDialog("成功提交信息, 我们尽快与您联系!"); 
                            }
                        }
                    });
                
            });
        })
        
        
    }
}