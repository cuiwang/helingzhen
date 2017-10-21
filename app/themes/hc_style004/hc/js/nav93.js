$(function(){
	var _nav = $('#nav93 ul'),
		_btn = $('#nav93Btn'),
		_container = $('body'),
		speed = 300,
		isShow = false,
		boxSizing = _container.css('-webkit-box-sizing');
	
	_nav.css('height', $(document).height());

	_btn.on('click', function(){
		if(isShow){
			_btn.animate({right: 10}, speed);
			_nav.animate({width: 0}, speed, function(){
				//若其他模板设有box-sizing: border-box的话，会对body有影响
				if(boxSizing == 'border-box'){
					_container.css('-webkit-box-sizing', 'border-box');
				}
				$(this).hide();
			});
			_container.animate({left: 0, paddingRight: 0}, speed);
		}else{
			if(boxSizing == 'border-box'){
				_container.css('-webkit-box-sizing', 'content-box');
			}

			_btn.animate({right: 245}, speed);
			_nav.show().animate({width: 235}, speed);
			_container.animate({left: -235, paddingRight: 235}, speed);
		}
		isShow = !isShow;
	});
});