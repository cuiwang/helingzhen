/*
 * 	调用1:
 * 	alert('msg...');
 *
 * 	调用2:
 * 	confirm('msg...', {
		title: '头部标题', 	//可为空，不显示头部
		isCover: true,		//遮罩层，默认为true
		isMiddle: true,		//是否在WINDOW中上面居中显示。默认为true
		buttons: [{			//按钮内容，及个数,如为'default'就是默认两个按扭[取消，确定]
			type:'button',
			value: '放弃',
			callBack: function() {
				setTimeout(function() {alert(11)},0);
				//...
			}
		},
		{
			type:'submit',
			value: '确定'
		}]
	}, 2000);	//"2000" -- 2秒后自动关闭(可为空，不自动半闭)
 * 
 * by tianyanrong
 */

(function(window) {
var ALERT = null;
Alert = function(options) {
	this.isHasDisplay = false;
	this.callBackName = 0;
	this.isAndroid = navigator.userAgent.indexOf('Android') > 0 ? true : false;
	this.returnVal = false;
}
	
Alert.prototype = {	
	getButtons: function() {
		var args = this.args,
			btn;
		this.buttonsDiv = this.buttonsDiv || document.createElement('div');
		this.buttonsDiv.className = 'ui_alert_button';
		this.buttonsDiv.innerHTML = '';
		if(args.buttons) {
			var defBtn = args.buttons;
			var i, k, buttons = args.buttons;
			buttons = buttons === 'default' ? [{
				type: 'button',
				value: '取消'
			},
			{
				type: 'submit',
				value: '确定'
			}] : buttons;
			for(i = 0, k = buttons.length; i < k; i++) {
				btn = document.createElement('button');
				btn.type = buttons[i].type;
				btn.className = buttons[i].className;
				btn.innerHTML = buttons[i].value;
				this.buttonsDiv.appendChild(btn);
				if(defBtn == 'default'&& i==1 ){
					this.bindCallBack(btn, args.callBack);
				}else{
					this.bindCallBack(btn, buttons[i].callBack);
				}
			}
		}
		else {			
			btn = document.createElement('button');
			btn.className = 'ui_alert_btn';
			btn.type = 'submit';
			btn.innerHTML = '确定';			
			this.bindCallBack(btn, args.callBack);					
			this.buttonsDiv.appendChild(btn);
		}
		return this.buttonsDiv;
	},

	bindCallBack: function(btn, callBack) {
		if(callBack && 'function' === typeof callBack) {
			++this.callBackName;
			this.callBack[this.callBackName] = callBack;
			//this.callBacks[btn] = 
			btn.callBack = this.callBackName;
		}
	},

	getTemplate: function() {
		var args = this.args;
		this.box = this.box || document.createElement('div');
		this.box.className = this.className;
		
		//title
		if(args.title) {
			this.title = this.title || document.createElement('h1');
			this.title.innerHTML = args.title;
			if(!this.isHasDisplay) {
				this.box.appendChild(this.title);
			}
			this.title.style.display = '';
		}
		else {
			if(this.title) {
				this.title.style.display = 'none';
			}
		}
		
		//content
		this.content = this.content || document.createElement('p');
		this.content.innerHTML = this.msg || '';
		if(!this.isHasDisplay) {
			this.box.appendChild(this.content);
		}
		
		this.buttonsDiv = this.getButtons();
		if(!this.isHasDisplay) {
			this.box.appendChild(this.buttonsDiv);
		}
		
		if(!this.isHasDisplay) {
			document.body.appendChild(this.box);
		}
		
		if(false !== this.isCover) {
			this.cover = this.cover || document.createElement('div');
			this.cover.className = this.coverClassName;
			if(!this.isHasDisplay) {
				document.body.appendChild(this.cover);
			}
			this.cover.style.height = window.screen.height >= document.body.clientHeight ? window.screen.height : document.body.clientHeight + 'px';
		}
	},
	
	bindEvents: function() {
		var _this = this;
		this.box.onclick = function(event) {
			var target = event.target;
			if(target.tagName === 'BUTTON') {
				var callBackName = target.callBack;
				if(callBackName && _this.callBack[callBackName]) {
					var callBack = _this.callBack[callBackName](_this);
					if(false === callBack) {
						return;
					}
				}
				_this.close();
			}
		}
	},
	
	close: function() {
		this.removeClass(this.box, this.showClassName);
		this.removeClass(this.cover, this.coverClassName);

		this.cover.style.height = '';
	},
	
	show: function(options) {
		this.msg = options.msg;
		this.args = options.args || {};
		this.showClassName = this.args.showClassName || 'ui_alert_show';
		this.className = this.args.className || 'ui_alert';
		this.coverClassName = this.args.coverClassName || 'ui_alert_cover';
		this.callBack = {};
		this.delay = options.delay;
		this.isCover = this.args.isCover;
		this.isMiddle = this.args.isMiddle;
			
		this.getTemplate();
		if(!this.isHasDisplay) {
			this.bindEvents();
		}
		this.isHasDisplay = true;
		
		this.addClass(this.box, this.showClassName)
		
		if(false !== this.isMiddle) {
			this.box.style['marginTop'] = '-' + this.box.clientHeight/2 + 'px';
			this.box.style['marginBottom'] = '-' + this.box.clientHeight/2 + 'px';
		}
		
		var _this = this;
		clearTimeout(this.timeoutValue);
		this.timeoutValue = null;
		if(this.delay) {
			this.timeoutValue = setTimeout(function() {
				clearTimeout(this.timeoutValue);
				this.timeoutValue = null;
				_this.close();
			}, this.delay);
		}		
		
	},

	addClass: function($el, className) {
		var classNames = $el.className;
		if(classNames === className || classNames.indexOf(' ' + className) >= 0 || classNames.indexOf(className + ' ') >= 0) {
		
		}
		else{
			if(classNames) {
				$el.className = classNames + ' ' + className;
			}
			else {
				$el.className = className;
			}
		}
		
	},

	removeClass: function($el, className) {
		var classNames = $el.className;
		if(classNames === className) {
			$el.className = '';
		}
		else if(classNames.indexOf(' ' + className) >= 0) {
			$el.className = classNames.replace(' ' + className, '');
		}
		else if(classNames.indexOf(className + ' ') >= 0) {
			$el.className = classNames.replace(className + ' ', '');
		}
	}
}

window.alert = function(msg, args, delay) {
	if(!ALERT) {
		ALERT = new Alert();
	};
	ALERT.show({
		msg: msg,
		args: args,
		delay: delay
	});
	return ALERT;
};
window.confirm = function(msg, args, delay) {
	if(!ALERT) {
		ALERT = new Alert();
	};
	return ALERT.show({
		msg: msg,
		args: args,
		delay: delay
	});
	return false;
};
}(window));