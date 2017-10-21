(function() {
	var setImgPosition = function(img, width, height) {
		var imgWidth = img.width;
		var imgHeight = img.height;
		var isSquareImg = width/height >= imgWidth/imgHeight ? true : false;
		if(!imgWidth || !imgHeight) {
			return;
		}
		if(isSquareImg) {
			img.style.height = 'auto';
			img.style.maxHeight = 'none';
			img.style.width = width + 'px';
			img.style.marginTop = -((img.height - height)/2) + 'px';
		}
		else {
			img.style.width = 'auto';
			img.style.maxWidth = 'none';
			img.style.height = height + 'px';
			img.style.marginLeft = -((img.width - width)/2) + 'px';
		}
	};
	var imgSquare = function() {
		var els = document.querySelectorAll('[data-role="imgSquare"]');
		var i, k;
		for(i = 0, k = els.length; i < k; i++){
			var el = els[i];
			var width = el.clientWidth;
			var img = el.querySelector('img');
			var vw = parseInt(el.getAttribute('data-height-vw'), 10) || 100;
			var height = width/100*vw;
			el.style.height = height + 'px';
			el.style.overflow = 'hidden';
			setImgPosition(img, width, height);
			img.onload = function() {
				setImgPosition(img, width, height);
			}			
		}
	};

	if (window.addEventListener) {
		window.addEventListener( "DOMContentLoaded", function(){
			imgSquare();
		}, false );

		window.addEventListener( "load", function(){
			imgSquare();
		}, false );

		window.addEventListener( "orientationchange", function(){
			setTimeout(function() {
				imgSquare();
			}, 600)
		}, false );
	}
	window.reloadImg = function(){
			imgSquare();
		}
}());

