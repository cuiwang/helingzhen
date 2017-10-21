$(function() {
	$('.select').on("click", function(){
			$(this).find("input").prop("checked", 1);
		});
	$('#closewindow').on("click",function(){
		WeixinJSBridge.invoke('closeWindow',{});
		})
});
		function onBridgeReady(){
		 WeixinJSBridge.call('hideOptionMenu');
		}
		
		if (typeof WeixinJSBridge == "undefined"){
			if( document.addEventListener ){
				document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
			}else if (document.attachEvent){
				document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
				document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
			}
		}else{
			onBridgeReady();
		}