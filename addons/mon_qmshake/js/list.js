/**
 * 
 * @authors tianyanrong
 * @date    2014-11-14
 * @version 
 */
;(function($) {
	var List = function(options) {
		window.scrollTo(0, 0);
		this.renderTimes = 0;
		this.maxPage = options.maxPage || 100000;
		this.keyName = options.keyName || 'users';
		this.Render = new options.Render();
		this.$loading = options.$loading;
		this.ScrollLoading = new options.ScrollLoading();		
		this.callback = options.callback || '';
		this.binsEvent();

		var _this = this;
		setTimeout(function() {
			_this.fetch();
		},0)
		
	}
	List.prototype = {
		formatNum: function(strNum) {
			if (strNum.length <= 3) {
				return strNum;
			}

			if (!/^(\+|-)?(\d+)(\.\d+)?$/.test(strNum)) {
				return strNum;
			}

			var a = RegExp.$1, b = RegExp.$2, c = RegExp.$3;
			var re = new RegExp();
			re.compile("(\\d)(\\d{3})(,|$)");
			while (re.test(b)) {
				b = b.replace(re, "$1,$2$3");
			}
			return a + "" + b + "" + c;
		},
		binsEvent: function() {
			var _this = this;
			this.ScrollLoading.bind('event-fetch', function() {
				_this.fetch();
			});
			this.Render.bind('event-fetch-success', function(data) {
				if(_this.getLastData(data)) {
					return;
				}
				_this.ScrollLoading.init();
			});
		},
		getQueryString: function(name) {
			var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
			var r = window.location.search.substr(1).match(reg);
			if (r != null) return r[2]; return null;
		},
		getApi: function() {
			return '';
		},
		fetch: function() {
			var _this = this;	
			this.$loading[0].style.display = '';
			if(this.callback) {

			}
			else {

				$.ajax({
					url:DataUrl,
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						_this.render(data);
					},
					error: function() {
						
					}
				})
			}
		},
		getLastData: function(data) {
			return parseInt(data.nextCursor, 10) === -1 || this.maxPage <= data.cursor || this.maxPage <= (this.renderTimes+1);
		},
		parseData: function(data) {
			return data;
		},
		getDate: function(timeValue) {
			var date = new Date(timeValue*1000);
			return date.getFullYear()+'-'
				+(date.getMonth()+1)+'-'
				+date.getDate()+' '
				+('0' + date.getHours()).slice(-2)+':'
				+('0' + date.getMinutes()).slice(-2);
		},
		render: function(data) {
			if(this.getResultData && 'function' === typeof this.getResultData) {
				data = this.getResultData(data);
			}
			if(data && data.apistatus === 1 && data.result) {
				data = this.parseData(data.result);
				this.Render.render(data);
			}
			this.$loading[0].style.display = 'none';
			this.renderTimes = this.renderTimes  + 1;
		}
	}

	window.List = List;

})(Zepto);