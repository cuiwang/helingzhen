$(document).ready(function(){
	$('div[data-markers]').each(function(){
		var $this = $(this);
		
		$this.css('height', '200');
		$this.css('overflow', 'hidden');
		
		if($this.attr('data-markers')){
			var width = $this.width();
			width = width <= 640 ? width : 640;
			var src = 'http://api.map.baidu.com/staticimage?width='+width+'&height=200' + $this.attr('data-center') + '&markers='+$this.attr('data-markers');
			$this.html('<img width="100%" src="'+src+'" />');
		}
		
	});
});