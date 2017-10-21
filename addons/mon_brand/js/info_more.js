$("div[data-type='switch']").click(function(){
	var $this = $(this);
	var parent = $this.parent();
	var content = parent.find('.module-content');
	var upHtml = $this.attr('data-up') ?  $this.attr('data-up') : '收起更多';
	var downHtml = $this.attr('data-down') ?  $this.attr('data-down') : '查看更多';
	
	if (content.height() == 85) {
		content.height('auto');
		if( content.height() <= 85 ){
			content.height(110);
		}
		$this.html(upHtml);
		
		// "查看更多"时，行为数据统计
		var activity_id = $('#activity_id').html();
		$.getJSON('/analyseplugin/plugin', {'activity_id': activity_id, 'plugtype':'info_more'}, function(response){
			if(response.success){
				// 统计成功, do none thing
			}
		});
	} else {
		content.height(85);
		content.css('overflow', 'hidden');
		$this.html(downHtml);
	}
});

$("div[data-type='switch']").each(function(){
	var content = $(this).parent().find('.module-content');
	
	if(content.height() > 90 ){
		content.parent().addClass('box-up');
		content.css({'height':'85px', 'overflow':'hidden'});
	}else{
		$(this).hide();
	}
});