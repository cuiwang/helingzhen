String.prototype.len=function(){return this.replace(/[^\x00-\xff]/g,"aa").length;};
String.prototype.trim=function(){return this.replace(/(^ *)|( *$)/g, "");};
String.prototype.htmlencode=function(){return this.replace(/[<>"']+/g,"");};
String.prototype.encode=function(){return escape(encodeURIComponent(this));};
String.prototype.left=function(length){
  if(this.len()>length){
      var _temp=this;
      _temp=_temp.replace(/([^\x00-\xff])/g,"$1>");
      _temp=_temp.substring(0,length-2)+"..";
      return _temp.replace(/>/g,"");
  }else{
      return this.toString();
  }
};
Element.prototype.show=function(){this.style.display="block";};
Element.prototype.hide=function(){this.style.display="none";};
Element.prototype.center=function(top){
   this.style.left=(_system._scroll().x+_system._zero(_system._client().bw-this.offsetWidth)/2)+"px";
   this.style.top=(top?top:(_system._scroll().y+_system._zero(_system._client().bh-this.offsetHeight)/2))+"px";
};
var _system={
   _client:function(){
      return {w:document.documentElement.scrollWidth,h:document.documentElement.scrollHeight,bw:document.documentElement.clientWidth,bh:document.documentElement.clientHeight};
   },
   _scroll:function(){
      return {x:document.documentElement.scrollLeft?document.documentElement.scrollLeft:document.body.scrollLeft,y:document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop};
   },
   _cover:function(show){
      if(show){
	     $("cover").show();
	     $("cover").style.width=(this._client().bw>this._client().w?this._client().bw:this._client().w)+"px";
	     $("cover").style.height=(this._client().bh>this._client().h?this._client().bh:this._client().h)+"px";
	  }else{
	     $("cover").hide();
	  }
   },
   _loading:function(text){
      if(text){
         this._cover(true);
         $("loading").show();
		 $("loading_text").innerHTML=text;
		 $("loading").center();
		 window.onresize=function(){_system._cover(true);$("loading").center();};
	  }else{
         this._cover(false);
         $("loading").hide();
		 window.onresize=null;
	  }
   },
   _toast:function(text,fun){
      $("toast").show();
      $("toast").innerHTML=text;
      $("toast").center();
      setTimeout(function(){
	     $("toast").hide();
		 if(fun){(fun)();}
      },3*1000);
   },
   _ok:function(text,fun){
      $("ok").show();
      $("ok_text").innerHTML=text;
      $("ok").center();
	  window.onresize=function(){$("ok").center();};
      setTimeout(function(){
		 window.onresize=null;
	     $("ok").hide();
         (fun)();
      },2*1000);
   },
   _guide:function(show){
      this._cover(true);
      $("guide").show();
      window.onresize=function(){_system._cover(true);};
	  if(show){
		 $("cover").onclick=_system._guideHide;
		 $("guide_button").hide();$("guide_button2").show();
	  }else{
         $("guide_button").show();$("guide_button2").hide();
	  }
   },
   _guideHide:function(){
      _system._cover();
      $("guide").hide();
      $("cover").onclick=null;
      window.onresize=null;
   },
   _guide2:function(){
	  if(_cookie._get("GUIDE2")!=""){return;}
      this._cover(true);
      $("guide2").show();
      window.onresize=function(){_system._cover(true);};
      setTimeout(function(){
		 _system._cover();
         $("guide2").hide();
		 $("cover").onclick=null;
		 window.onresize=null;
		 _cookie._set("GUIDE2","1",60*60*24*30);
	  },5*1000);
   },
   _zero:function(n){
      return n<0?0:n;
   },
   _forbidden:function(text){
     //return text.match(/(老市长|薄熙来|薄市长|法轮功)/)!=null;
      return false;
   }
};
(function(){
   var onBridgeReady=function(){
   WeixinJSBridge.on('menu:share:appmessage', function(argv){
      WeixinJSBridge.invoke('sendAppMessage',{
         "appid":dataForWeixin.appId,
         "img_url":dataForWeixin.MsgImg,
         "img_width":"120",
         "img_height":"120",
         "link":_fromCode+dataForWeixin.path,
         "desc":dataForWeixin.desc,
         "title":dataForWeixin.title
      }, function(res){dataForWeixin.callback();});
   });
   WeixinJSBridge.on('menu:share:timeline', function(argv){
	  (dataForWeixin.callback)();
	  WeixinJSBridge.invoke('shareTimeline',{
         "img_url":dataForWeixin.TLImg,
         "img_width":"120",
         "img_height":"120",
         "link":_fromCode+dataForWeixin.path,
         "desc":dataForWeixin.desc,
         "title":dataForWeixin.title
      }, function(res){dataForWeixin.callback();});
   });
   WeixinJSBridge.on('menu:share:weibo', function(argv){
	  WeixinJSBridge.invoke('shareWeibo',{
         "content":dataForWeixin.title,
         "url":_fromCode+dataForWeixin.path
      }, function(res){dataForWeixin.callback();});
   });
   WeixinJSBridge.on('menu:share:facebook', function(argv){
	  (dataForWeixin.callback)();
	  WeixinJSBridge.invoke('shareFB',{
         "img_url":dataForWeixin.TLImg,
         "img_width":"120",
         "img_height":"120",
         "link":_fromCode+dataForWeixin.path,
         "desc":dataForWeixin.desc,
         "title":dataForWeixin.title
      }, function(res){});
   });
};
if(document.addEventListener){
   document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
}else if(document.attachEvent){
   document.attachEvent('WeixinJSBridgeReady'   , onBridgeReady);
   document.attachEvent('onWeixinJSBridgeReady' , onBridgeReady);
}
})();
var _cookie={
	_set:function(name,value,expires){
	   if(expires){
		  var _end = new Date();
		  _end.setTime(_end.getTime()+(expires*1000));
	    }
	    document.cookie=name+"="+escape(value)+(expires ? ";expires="+_end.toGMTString() : "")+"; path=/";
    },
	_get:function(name){
       var _cookie=document.cookie;
       var _start=_cookie.indexOf(name+"=");
       if(_start!=-1){
          _start+=name.length+1;
          var _end=_cookie.indexOf(";",_start);
          if(_end==-1){_end = _cookie.length;}
          return unescape(_cookie.substring(_start,_end));
        }
        return "";
    }
};
var _$=function(url,parameters,loadingMessage,functionName){
    var request=new XMLHttpRequest();
    if(loadingMessage!=""){_system._loading(loadingMessage);}
    var method="POST";
    if(parameters==""){method="GET";parameters=null;}
    request.open(method,url,true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.onreadystatechange=function(){
	 if(request.readyState==4){
         if(loadingMessage != ""){_system._loading();}
	     if(request.status==200){
		    if(functionName){
		       try{
			      var json = eval("("+ request.responseText+")");
			      eval(functionName+"(json)");
                }catch(e){}
		    }
	     }else{
	         if(loadingMessage != ""){_system._toast("发生意外错误，请稍候再试");}
	     }
	 }
    };
    request.send(parameters);
};
(function(){
  if(document.referrer!=""){
     _system._guide2();
  }
  if(location.href.toString().indexOf("pre=true")!=-1){
	  setInterval(function(){
	     $("guide3").show();
		 setTimeout(function(){
            $("guide3").hide();
		 },500);
	  },1000);
  }
})();