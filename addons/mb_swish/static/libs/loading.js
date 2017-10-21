var M = window.M = {
	/**
	 * loading加载等待方法，虽然loading提供了6中样式，但是内部调用机制中规定：重复调用M.loading(xx,xx)方法时，依赖于base2.css
	 * 只会显示之前已经调用过的形式的loading效果，即一个页面中只存在一种loading样式效果。
	 * @param  {[type]} type    [loading的样式id]
	 * @param  {[type]} bgColor [设置loading样式的颜色]
	 * @param  {[type]} bgType  [背景形式,1：liveApp中背景纯白色 2：其他地方为半透明背景,不传时背景为半透明]
	 */
	loading: function(type, bgType, bgColor) {
		if (!document.getElementById('Mpop')) {
			var mpop = document.createElement('div');
			mpop.className = 'm-pop';
			mpop.id = 'Mpop';
			if (bgType == 1) {
				mpop.style.background = 'white';
			}
			var mpop_box = document.createElement('div');
			mpop_box.className = 'box box-center wh-100';
			var preloader = document.createElement('div');
			switch (type) {
				case 1: //翻转
					preloader.className = 'loading-rotate';
					preloader.style.backgroundColor = bgColor;
					break;
				case 2: //竖条伸缩
					preloader.className = 'loading-stretch';
					var preloader_inner = null;
					for (var i = 1; i <= 5; i++) {
						preloader_inner = document.createElement('div');
						preloader_inner.className = 'rect' + i;
						preloader_inner.style.backgroundColor = bgColor;
						preloader.appendChild(preloader_inner);
						preloader_inner = null;
					}
					break;
				case 3: //两个圆相互切换
					preloader.className = 'loading-bounce';
					var preloader_inner = null;
					for (var i = 1; i <= 2; i++) {
						preloader_inner = document.createElement('div');
						preloader_inner.className = 'double-bounce' + i;
						preloader_inner.style.backgroundColor = bgColor;
						preloader.appendChild(preloader_inner);
						preloader_inner = null;
					}
					break;
				case 4: //两个正方形相互切换
					preloader.className = 'loading-cube';
					var preloader_inner = null;
					for (var i = 1; i <= 2; i++) {
						preloader_inner = document.createElement('div');
						preloader_inner.className = 'cube' + i;
						preloader_inner.style.backgroundColor = bgColor;
						preloader.appendChild(preloader_inner);
						preloader_inner = null;
					}
					break;
				case 5: //三个点相互切换
					preloader.className = 'loading-point';
					var preloader_inner = null;
					for (var i = 1; i <= 3; i++) {
						preloader_inner = document.createElement('div');
						preloader_inner.className = 'bounce' + i;
						preloader_inner.style.backgroundColor = bgColor;
						preloader.appendChild(preloader_inner);
						preloader_inner = null;
					}
					break;
				case 6:
					preloader.className = 'loading-circle';
					var circle_container = null;
					var circle_inner = null;
					for (var i = 1; i <= 3; i++) {
						circle_container = document.createElement('div');
						circle_container.className = 'spinner-container loading-container' + i;
						for (var j = 1; j <= 4; j++) {
							circle_inner = document.createElement('div');
							circle_inner.className = 'circle' + j;
							circle_inner.style.backgroundColor = bgColor;
							circle_container.appendChild(circle_inner);
							circle_inner = null;
						}
						preloader.appendChild(circle_container);
						circle_container = null;
					}
					break;
				default:
					preloader.className = 'loading-rotate';
					preloader.style.backgroundColor = bgColor;
					break;
			}

			mpop_box.appendChild(preloader);
			mpop.appendChild(mpop_box);
			document.body.insertBefore(mpop, document.body.firstChild);
//			document.querySelector('.loading-cube').style.webkitTransform = 'scale(2,2)';
		} else {
			this.loadingShow();
		}
	},
	/**
	 * 显示loading
	 */
	loadingShow: function() {
		// $('#Mpop').show();
		document.getElementById('Mpop').style.display = 'block';
	},
	/**
	 * 隐藏loading
	 */
	loadingHide: function() {
		// $('#Mpop').hide();
		document.getElementById('Mpop').style.display = 'none';
	}
}
define(function() {
	return M;
});