String.prototype.innerText=function(){return this.replace(/(<[^>]+>)|(&nbsp;)/ig,"");};
String.prototype.htmlDecode=function(){return this.replace(/&amp;/ig,"&").replace(/&nbsp;/ig," ").replace(/&[a-z]{2,4};/ig,"");};
var _card={
   _post:function(){
      var _title=$("card_title").value.innerText().trim(),_content=$("card_content").value.replace(/(<(br|div|\/div)>)+/ig,"\n").innerText(),_author=$("card_author").value.innerText().trim();
	  if(_title=="" || _title=="收卡人"){_system._toast("没有输入收卡人名字");return;}
      if(_title.len()>20){_system._toast("收卡人名字在20字节以内");return;}
      if(_content.len()<10){_system._toast("祝福语太短了");return;}
      if(_content.len()>200){_system._toast("祝福语太长了");return;}
      if(_author=="" || _author=="署名"){_system._toast("请署上你的大名");return;}
      if(_author.len()>20){_system._toast("署名请在20字节以内");return;}
      _$("/Messagehelper/card_save","wid="+_wid+"&card="+_cardType+"&title="+_title.encode()+"&content="+_content.encode()+"&author="+_author.encode()+"&cardName="+_cardName.encode(),"请稍候","_card._ok");
   },
   _ok:function(json){
      if(json.state=="0"){_system._toast("你填写的内容有问题");return;}
      dataForWeixin.MsgImg=_fromCode+_PUBLIC+"card_msg.png";
      dataForWeixin.TLImg=_fromCode+_PUBLIC+"card.png";
      dataForWeixin.path="/Messagehelper/card?wid="+_wid+"&id="+json.id;
      dataForWeixin.title="收到一张来自"+$("card_author").value.innerText().htmlDecode().trim()+"的"+_cardName;
      dataForWeixin.desc=$("card_content").value.innerText().htmlDecode().trim().left(88);
      dataForWeixin.callback=function(){
         _$("/Messagehelper/share?","wid="+_wid+"&type=card&id="+json.id,"","");
      };
      _system._guide();
   }
};
window.onload=function(){$("card_body").show();$("card_loading").hide();};