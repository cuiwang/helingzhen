var myStorage = (function(exports) {
	
	exports.PlayerWin = function(score) {

	}

	exports.Storage = {
		_data: {},
			
		_guid: (function(){
			var guid = sessionStorage.getItem('guid') || location.href;
			return guid;
		})(),
		_updateTimeout: 2000,  // 在一秒内不重复提交
		_timeoutId: 0,
		_updateData: {},
		updateData: function(obj){
			
		},
			
		setGUID: function(guid){
			sessionStorage.setItem('guid', guid);
			this._guid = guid;
		},

		setItem: function(key, val) {
			key = this._guid + key;
			localStorage.setItem(key, val);
			this.updateData({
				key: val
			});
		},

		getItem: function(key) {
			key = this._guid + key;
			return localStorage.getItem(key);
		},

		removeItem: function(key) {
			key = this._guid + key;
			localStorage.removeItem(key);
			delete this._data[key];
			this.updateData({});
		},

		clear: function() {
			for(var key in localStorage) {
				if(key.indexOf(this._guid) === 0){
					localStorage.removeItem(key);
				}
			}
		},
		
		keys: function(){
			var k = [];
			for(var key in localStorage) {
				if(key.indexOf(this._guid) === 0){
					k.push(key);
				}
			}
			return k;
		}
	};


	// backToList
	exports.backToList = function() {
		
	}

	return exports;
})(myStorage || {});