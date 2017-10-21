$(function(){
	$('.flexslider_normal').flexslider({
		animation: "slide",
		//useCSS: false,
		start: function(slider){
			$('body').removeClass('loading');
		}
	});

	//行为数据统计
	$('.flexslider_normal').bind('click touchstart', function(){
		var activity_id = $('#activity_id').html();
		$.getJSON('/analyseplugin/plugin', {'activity_id': activity_id, 'plugtype':'slidepic_normal'}, function(response){
			if(response.success){
				// 统计成功, do none thing
			}
		});
		
		return true;
	});
});
