/**
 * 
 * @authors jRx (you@example.org)
 * @date    2015-01-20 16:08:18
 * @version $Id$
 */
(function($){




	var data = {};
	function render (data){
		console.log(999, data, 999)
		//$("#price").text(data.price);
		//$("#product").text(data.goods_name);
		//$("#win_prize .win_banner img").attr("src",data.goods_img);
		//$("#provider_cont").text("提供方:"+data.server_name);
		$('#productImg').attr('src', data.img);
		$("#good_info").html(data.goodname+"价值"+data.price);
	}
	window.addEventListener("message", function( event ) { 
		data = event.data;
		console.log(22222, data)
		render( data ) ;
	}, false ); 
	window.onload = function(){
		if(!data.price){
			data = window.parent.slotData;
			render( data ) ;
		}
	}
})(Zepto);
