//主要处理通用js
/****添加到购物车****/
function addToCart(periodnumber,title){
	$.post("{php echo $this->createMobileUrl('cart',array('op'=>'tocart'))}"
		,{periodnumber:periodnumber,title:title}
		,function(d){
			if(d.result==1){
				alert(JSON.stringify(d));
				$.toast(JSON.stringify(d));
				$('#count').html(d.num);
			}
		}
		,"json"
	);
}