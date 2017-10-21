/*
* zhaohenghao 2014-10-10
* 打包时引入 /js/2014/base/LAB.min.js
* */
;(function(global){
	var M = global.M = global.M || {};
	
	/**String帮助类
	 * 
	 */
	var StringUtil =  {
			  decodeHtml : function(s){
				  var HTML_DECODE = {
				        "&lt;" : "<",
				        "&gt;" : ">",
				        "&amp;" : "&",
				        "&nbsp;": " ",
				        "&quot;": "\"",
				        "&copy;": "",
				        "&apos;": "'"
				    };
		          return (typeof s != "string") ? s :
		              s.replace(/&\w+;|&#(\d+);/g ,
                        function($0, $1){
                            var c = HTML_DECODE[$0]; // 尝试查表
                            if(c === undefined){
                                // Maybe is Entity Number
                                if(!isNaN($1)){
                                    c = String.fromCharCode(($1 == 160) ? 32 : $1);
                                }else{
                                    // Not Entity Number
                                    c = $0;
                                }
                            }
                            return c;
                        });
			  },
			 
		    //检查是否为正数     
		    isUnsignedNumeric : function (strNumber){
		    	if (ObjectUtil.isEmpty(strNumber)) {
		            return false;
		        }
                var newPar=/^\d+(\.\d+)?$/;
                return newPar.test(strNumber);     
			},
			//检查是否为整数     
            isInteger : function isInteger(strInteger){
            	if (ObjectUtil.isEmpty(phone)) {
		            return false;
		        }
                var newPar=/^(-     |\+)?\d+$/ ;   
                return newPar.test(strInteger);     
			},
			//检查是否为正整数     
			isUnsignedInteger : function (strInteger){     
                var newPar=/^\d+$/ ;
                return newPar.test(strInteger);
            },
            isFloat : function (str) {
		        if (ObjectUtil.isEmpty(str)) {
		            return false;
		        }
		        var newPar = /^[0-9]+\.{0,1}[0-9]{0,2}$/;
		        return newPar.test(str);
		    },
			//手机号码校验
            isPhoneNum : function (phone) {
		        if (ObjectUtil.isEmpty(phone)) {
		            return false;
		        }
		        var newPar = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(14[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
		        return newPar.test(phone);
		    },
		    //邮箱校验
		    isEmail : function(email){
		    	if (ObjectUtil.isEmpty(email)) {
		            return false;
		        }
		    	var newPar = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
		    	return newPar.test(email);
		    },
		  //时候还有空格
		    hasSpace : function(str){
		    	if (val == undefined || val == null || val == 'null' || val == 'undefined') {
		            return true;
		        }
		    	return str.indexOf('') > -1;
			},
			URLencode : function (sStr){ 
			     return escape(sStr).replace(/\+/g, '%2B').replace(/\"/g,'%22').replace(/\'/g, '%27').replace(/\//g,'%2F').replace(/\#/g,'%23'); 
			} 
		};
	
	var ObjectUtil = {
			$mObj : {},
			/**
			 * 将config对象的属性合并到object
			 */
			merge: function(object, config, isDeep) {
				if (!object || !config || typeof config != 'object') {
					return object;
				}
				
				if (!isDeep) {
					for (var p in config) {
						object[p] = config[p];
					}
				}
				else {
					var property, value;
					for (property in config) {
						if (config.hasOwnProperty(property)) {
							value = config[property];
							if (value && value.constructor === Object) {
								if (object[property] && object[property].constructor === Object) {
									ObjectUtil.merge(object[property], value);
								}
								else {
									object[property] = value;
								}
							}
							else {
								object[property] = value;
							}
						}
					}
				}
			    return object;
			},
		
			/**
			 * 克隆object
			 */
			clone: function(object, isDeep){
				return ObjectUtil.merge({}, object, isDeep);
			},
		
			/**
			 * 创建命名空间所需要的对象
			 */
			namespace: function() {
				var root = global, 
					parts, part, i, j, ln, subLn;
		
		        for (i = 0, ln = arguments.length; i < ln; i++) {
					var arg = arguments[i];
					if (ObjectUtil.$mObj.namespace[arg]) {
						continue;
					}
		            parts = arg.split('.');
		            for (j = 0, subLn = parts.length; j < subLn; j++) {
		                part = parts[j];
						if (!root[part]) {
		                    root[part] = {};
		                }
						root = root[part];
		            }
		            ObjectUtil.$mObj.namespace[arg] = true;
		        }
			},
		
			/**
			 * 类继承
			 * @param {Function} 父类
			 * @param {Object} 自定义可覆盖父类的方法
			 * @return {Function} 子类
			 */
			extend: function(){
				var inlineOverrides = function(o){
					for (var m in o) {
						if (!o.hasOwnProperty(m)) {
							continue;
						}
						this[m] = o[m];
					}
				};
				return function(superclass, overrides){
					(typeof superclass == 'function') || (superclass = function(){});
					var subclass = function(){
						superclass.apply(this, arguments);
					};			
					var F = function(){};
					
					F.prototype = superclass.prototype;
					subclass.prototype = new F();
					subclass.prototype.constructor = subclass;
					subclass.superclass = superclass.prototype;
					
					if (superclass.prototype.constructor === Object.prototype.constructor) {
						superclass.prototype.constructor = superclass;
					}
					
					subclass.override = function(overrides){
						if (subclass.prototype && overrides && typeof overrides == 'object') {
							for (var p in overrides) {
								subclass.prototype[p] = overrides[p];
							}
						}
					};
					subclass.prototype.override = inlineOverrides;			
					subclass.override(overrides);
					
					return subclass;
				};
			}(),
			extends:function(target,source){
				for (var name in source) {
					if (source[name] !== undefined) {target[name] = source[name]}
				}
			},
			/**
			 * 对象/数组遍历
			 * @param {Object} values 遍历对象/数组
			 * @param {Object} iterator 将遍历的对象/数组中的每一项元素传给该函数，函数参数为value, key
			 * @param {Object} scope 函数作用域
			 */
			each: function(values, iterator, scope){
				if (ObjectUtil.isEmpty(values) || !iterator) {
					return;
				}
				if (ObjectUtil.isArray(values)) {
					for (var i = 0, l = values.length; i < l; i++) {
						try {
							if (iterator.call(scope, values[i], i, values) === false) {
								return;
							}
						} catch (e) {
							M.log(e, 'error');
						}
				    }
				} else {
					for (var key in values) {
						if (!values.hasOwnProperty(key)) {
							continue;
						}
						try {
							if (iterator.call(scope, values[key], key, values) === false) {
								return;
							}
						} catch (e) {
							M.log(e, 'error');
						}
					}
				}
			},
		    contains: function(obj, item){
				if (ObjectUtil.isArray(obj)) {
					if ('indexOf' in Array.prototype) {
			            return obj.indexOf(item) !== -1;
			        }
			        
			        var i, ln;
			        for (i = 0, ln = obj.length; i < ln; i++) {
			            if (obj[i] === item) {
			                return true;
			            }
			        }
			        
			        return false;
				} 
				else {
					return !ObjectUtil.isEmpty(obj) && item in obj;
				}
		    },
			isEmpty: function(v, allowEmptyString){
				if ((typeof v === 'undefined') || (v === null) || (!allowEmptyString ? v === '' : false) || 
					(ObjectUtil.isArray(v) && v.length === 0)) {
					return true;
				} else if (ObjectUtil.isObject(v)) {
					for (var key in v) {
						if (Object.prototype.hasOwnProperty.call(v, key)) {
							return false;
						}
					}
					return true;
				}
				return false;
		    },
			isBlank: function(v){
				return ObjectUtil.isEmpty(v) ? true : ObjectUtil.isEmpty(String(v).replace(/^\s+|\s+$/g, ''));
			},
			isDefined: function(v){
		        return typeof v === 'undefined';
		    },
			isObject: function(value){
				if (Object.prototype.toString.call(null) === '[object Object]') {
					return value !== null && value !== undefined && 
						Object.prototype.toString.call(value) === '[object Object]' && value.ownerDocument === undefined;
				} else {
					return Object.prototype.toString.call(value) === '[object Object]';
				}
			},
			isFunction: function(v){
		        return Object.prototype.toString.apply(v) === '[object Function]';
		    },
		    isArray: function(v){
		        return Object.prototype.toString.apply(v) === '[object Array]';
		    },
			isDate: function(v){
		        return Object.prototype.toString.apply(v) === '[object Date]';
		    },
		    isNumber: function(v){
		        return typeof v === 'number' && isFinite(v);
		    },
		    isString: function(v){
		        return typeof v === 'string';
		    },
		    isBoolean: function(v){
		        return typeof v === 'boolean';
		    }
		};
	/**date帮助类
	 * 
	 */
	var DateUtil =  {
			toString : function(date, format) {  
			    var strDate = undefined;  
			    var year  = date.getFullYear();  
			    var month = date.getMonth() + 1;  
			    var day   = date.getDate();  
			    var hour  = date.getHours();  
			    var min   = date.getMinutes();  
			    var sec   = date.getSeconds();  
			    month = (parseInt(month) < 10) ? ('0' + month) : (month);  
			    day   = (parseInt(day)   < 10) ? ('0' + day )  : (day);  
			    hour  = (parseInt(hour)  < 10) ? ('0' + hour)  : (hour);  
			    min   = (parseInt(min)   < 10) ? ('0' + min)   : (min);  
			    sec   = (parseInt(sec)   < 10) ? ('0' + sec)   : (sec);  
			    if ('yyyy-MM-dd HH:mm:ss' == format) {  
			        strDate = year + '-' + month + '-' + day + ' ' + hour + ':' + min + ':' + sec;  
			    } else if ('yyyy-MM-dd' == format) {  
			        strDate = year + '-' + month + '-' + day;  
			    } else if ('yyyy-MM' == format) {  
			        strDate = year + '-' + month;     
			    } else if ('yyyy' == format) {  
			        strDate = year;       
			    }
			    return strDate;  
			},
			
			toDate : function(strDate) {  
			    if (strDate.length == 19) { // YYYY-MM-DD HH:MI:SS  
			        var year  = strDate.substring(0, 4);  
			        var month = strDate.substring(5, 7);  
			        var date  = strDate.substring(8, 10);  
			        var hour  = strDate.substring(11, 13);  
			        var min   = strDate.substring(14, 16);  
			        var sec   = strDate.substring(17, 19);  
			        return new Date(year, month - 1, date, hour, min, sec);  
			    } else if (strDate.length == 10) { // 'YYYY-MM-DD'  
			        var year  = strDate.substring(0, 4);  
			        var month = strDate.substring(5, 7);  
			        var date  = strDate.substring(8, 10);  
			        return new Date(year, month - 1, date);  
			    } else if (strDate.length == 7) { // 'YYYY-MM'  
			        var year  = strDate.substring(0, 4);  
			        var month = strDate.substring(5, 7);  
			        return new Date(year, month - 1);  
			    } else if (strDate.length == 4) { // 'YYYY'  
			        var year  = strDate.substring(0, 4);  
			        return new Date(year);        
			    } else {  
			    	return undefined;
			    }  
			} ,
			getMonthDays : function(date,month) {
				var DateUtils_MD = new Array(31,28,31,30,31,30,31,31,30,31,30,31); 
			    var year = date.getFullYear();  
			    if (typeof month == 'undefined') {  
			        month = date.getMonth();  
			    }  
			    if (((0 == (year%4)) && ( (0 != (year%100)) || (0 == (year%400)))) && month == 1) {  
			        return 29;  
			    } else {  
			        return DateUtils_MD[month];  
			    }  
			}, 
			      
			addDays : function(dayOffset, strBaseDate) {  
			    var date = (arguments.length == 1) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strBaseDate);  
			    date = new Date(date.getTime() + parseInt(dayOffset) * 24 * 3600 * 1000);  
			    return DateUtil.toString(new Date(date), 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			addMonths : function(monthOffset, strBaseDate) {  
			    var date = (arguments.length == 1) ? DateUtil.toDate(DateUtil.today()): DateUtil.toDate(strBaseDate);  
			    var month=date.getMonth();  
			    var cd=date.getDate();//DateUtil.getMonthDays(date,month);  
			    var td=DateUtil.getMonthDays(date,date.getMonth() + parseInt(monthOffset));  
			    if(cd > td){date.setDate(td);}  
			    date.setMonth(date.getMonth() + parseInt(monthOffset));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			addMonthsForStart : function(monthOffset, strBaseDate) {  
			    var strDate = (arguments.length == 1) ? DateUtil.today() : strBaseDate;  
			    strDate = DateUtil.addMonths(monthOffset, strDate);  
			    return DateUtil.firstDayOfMonth(strDate);  
			} ,
			  
			addMonthsForEnd : function(monthOffset, strBaseDate) {  
			    var strDate = (arguments.length == 1) ? DateUtil.today() : strBaseDate;  
			    strDate = DateUtil.addMonths(monthOffset, strDate);  
			    return DateUtil.addDays(-1, DateUtil.firstDayOfMonth(strDate));  
			} ,
			  
			addYears : function(yearOffset, strBaseDate) {  
			    var date = (arguments.length == 1) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strBaseDate);  
			    date.setYear(date.getYear() + parseInt(yearOffset));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			addYearsForStart : function(yearOffset, strBaseDate) {  
			    var strDate = (arguments.length == 1) ? DateUtil.today() : strBaseDate;  
			    strDate = DateUtil.addYears(yearOffset, strDate);  
			    return DateUtil.firstDayOfYear(strDate);  
			},  
			  
			addYearsForEnd : function(yearOffset, strBaseDate) {  
			    var strDate = (arguments.length == 1) ? DateUtil.today() : strBaseDate;  
			    strDate = DateUtil.addYears(yearOffset, strDate);  
			    return DateUtil.firstDayOfYear(strDate);  
			},  
			  
			sunOfWeek : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date = new Date(date - (date.getDay()) * (24 * 3600 * 1000));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');    
			} ,
			  
			monOfWeek : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date = new Date(date - (date.getDay() - 1) * (24 * 3600 * 1000));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			tueOfWeek : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date = new Date(date - (date.getDay() - 2) * (24 * 3600 * 1000));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			}, 
			  
			wedOfWeek : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date = new Date(date - (date.getDay() - 3) * (24 * 3600 * 1000));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			turOfWeek : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date = new Date(date - (date.getDay() - 4) * (24 * 3600 * 1000));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			friOfWeek : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date = new Date(date - (date.getDay() - 5) * (24 * 3600 * 1000));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			satOfWeek : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date = new Date(date - (date.getDay() - 6) * (24 * 3600 * 1000));  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			}, 
			  
			firstDayOfMonth : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date.setDate(1);  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			lastDayOfMonth : function(strDate) {  
			    strDate = (arguments.length == 0) ? DateUtil.today() : (strDate);  
			    strDate = DateUtil.addMonths(1, strDate);  
			    strDate = DateUtil.firstDayOfMonth(strDate);  
			    strDate = DateUtil.addDays(-1, strDate);  
			    return strDate;  
			}, 
			  
			firstDayOfYear : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date.setMonth(0);  
			    date.setDate(1);  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			} ,
			  
			lastDayOfYear : function(strDate) {  
			    var date = (arguments.length == 0) ? DateUtil.toDate(DateUtil.today()) : DateUtil.toDate(strDate);  
			    date.setMonth(11);  
			    date.setDate(31);  
			    return DateUtil.toString(date, 'yyyy-MM-dd HH:mm:ss');  
			}  ,
			  
			today : function(format) {  
		        if (arguments.length == 0) {  
		            return DateUtil.toString(new Date(), 'yyyy-MM-dd');  
		        } else {  
		            return DateUtil.toString(new Date(), format);  
		        } 
			}
		};
	/**Cookie工具类
	 * 
	 */
	var CookieUtil = {
			getCookie : function (name) {
				var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
				arr=document.cookie.match(reg)
			    if(arr){
			        return unescape(arr[2]);
			    } else {
			        return null;
			    }
		    },
			
			setCookie : function (name, value, expires, path, domain){ 
		        var str=name+'='+escape(value); 
		        if(expires!=''){ 
		            var date=new Date(); 
		            date.setTime(date.getTime()+expires*24*3600*1000);//expires单位为天 
		            str+=';expires='+date.toGMTString(); 
		        } 
		        if(path!=''){ 
		        	str+=';path='+path;//指定可访问cookie的目录 
		        } 
		        if(domain!=''){ 
		        	str+=';domain='+domain;//指定可访问cookie的域 
		        } 
		        document.cookie=str; 
		    },
		    delCookie : function (cookieName) {
		    	var expires = new Date();
		    	expires.setTime(expires.getTime()-1);//将expires设为一个过去的日期，浏览器会自动删除它
		    	document.cookie = cookieName+'=; expires='+expires.toGMTString();
		    }
		};
		
	var UrlUtil = {
			getParam : function (name) {
				var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)','i');
				var r = window.location.search.substr(1).match(reg);
				if (r != null) {
					return unescape(r[2]);
				}; 
				return null;
			},
			/**
			 * 
			 * @param name String
			 * @param value String
			 * @returns
			 */
			setParams : function (json){
				var url = window.location.search;
	            var str = '';
	            var returnurl = new Array();
	            var paramObj = {};
	            if(ObjectUtil.isObject(json)){
	            	if (url.indexOf('?') != -1){
	 	            	str = url.substr(url.indexOf('?') + 1);
	 	            }
	 	            if (str.length > 0){
	 	                var params = str.split('&');
	 	                for(i in params){
	 	                	var ps = params[i].split('=');
	 	                	if(ps.length > 1){
	 	                		paramObj[ps[0]] = ps[1];
	 	                	}else{
	 	                		paramObj[ps[0]] = '';
	 	                	}
	 	                }
	 	               ObjectUtil.merge(paramObj, json);
	 	            }else{
	 	            	paramObj = json;
	 	            }
	            }else{
	            	throw new Error('arguments is not a jsonobject');
	            }
	            for(key in paramObj){
            		returnurl.push(key);
            		returnurl.push('=');
            		returnurl.push(json[key]);
            		returnurl.push('&');
            	}
	            returnurl.pop();
	            window.location.search = returnurl.jion();
	        },
	        
	        getHash : function(){
	        	var hash = window.location.hash;
	        	if(!hash){
	        		return undefined;
	        	}else{
	        		return hash.replace('#', '');
	        	}
	        },
	        setHash : function(val){
	        	if(val){
	        		window.location.hash = '#' + val;
	        	}else{
	        		window.location.hash = '';
	        	}
	        }
			
		};
	
	
	/**
	 * http请求相关操作
	 */
	var HttpUtil =  {
			/**
			 * 异步请求
			 */
			ajax: function(params) {
				if (!params || !params.url) {
					return false;
				}
				$.ajax({
					url: params.url,
					type: params.type || 'post',
					dataType: params.dataType || 'json',
					async: params.async === false ? false : true,
					data: params.data || {},
					timeout : (params.timeout && params.timeout > 0 ? params.timeout : 0),
					success: function(response) {
						if (!params.success) {
							return;
						}
						params.success.call(params.scope, response);
					},
					error: function(response) {
						if (!params.error) {
							return;
						}
						params.error.call(params.scope, response);
					}
				});
				return true;
			}
		};
	BrowserUtil = function(){
		var os = null;
		var browser = null;
		function init (){
			var ua = navigator.userAgent;
		    os = {}, browser = {};
		    var webkit = ua.match(/Web[kK]it[\/]{0,1}([\d.]+)/),
				android = ua.match(/(Android);?[\s\/]+([\d.]+)?/),
				osx = !!ua.match(/\(Macintosh\; Intel /),
				ipad = ua.match(/(iPad).*OS\s([\d_]+)/),
				ipod = ua.match(/(iPod)(.*OS\s([\d_]+))?/),
				iphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/),
				webos = ua.match(/(webOS|hpwOS)[\s\/]([\d.]+)/),
				touchpad = webos && ua.match(/TouchPad/),
				kindle = ua.match(/Kindle\/([\d.]+)/),
				silk = ua.match(/Silk\/([\d._]+)/),
				blackberry = ua.match(/(BlackBerry).*Version\/([\d.]+)/),
				bb10 = ua.match(/(BB10).*Version\/([\d.]+)/),
				rimtabletos = ua.match(/(RIM\sTablet\sOS)\s([\d.]+)/),
				playbook = ua.match(/PlayBook/),
				chrome = ua.match(/Chrome\/([\d.]+)/) || ua.match(/CriOS\/([\d.]+)/),
				firefox = ua.match(/Firefox\/([\d.]+)/),
				ie = ua.match(/MSIE\s([\d.]+)/) || ua.match(/Trident\/[\d](?=[^\?]+).*rv:([0-9.].)/),
				webview = !chrome && ua.match(/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/),
				safari = webview || ua.match(/Version\/([\d.]+)([^S](Safari)|[^M]*(Mobile)[^S]*(Safari))/),
				weixin = ua.indexOf("MicroMessenger")>=0;
			if(webkit){
				browser['browser'] = 'webkit';
				browser['version'] = webkit[1];
			}
		    if (android){
		    	os['os'] = 'android';
		    	os['version'] = android[2];
			}
		    if (iphone && !ipod){
		    	os['os'] = 'ios';
		    	os['cline'] = 'iphone';
		    }
		    if(ipad){
		    	os['os'] = 'ios';
		    	os['cline'] = 'ipad';
		    }
		    if (ipod) {
		    	os['os'] = 'ios';
		    	os['cline'] = 'ipod';
		    	os['version'] = ipod[3] ? ipod[3].replace(/_/g, '.') : null;
		    }
		    if(webos){
		    	os['os'] = 'webos';
		    	os['version'] = webos[2];
		    }
		    if (touchpad){
		    	os['os'] = 'touchpad';
		    } 
		    if (blackberry){
		    	os['os'] = 'blackberry';
		    	os['version'] = blackberry[2];
		    } 
		    if (bb10) {
		    	os['os'] = 'bb10';
		    	os['version'] = bb10[2];
		    }
		    if (rimtabletos){
		    	os['os'] = 'rimtabletos';
		    	os['version'] = rimtabletos[2];
		    }
		    if (playbook){
		    	os['os'] = 'playbook';
		    }
		    if(kindle){
		    	os['kindle'] = 'kindle';
		    	os['version'] = kindle[1];
		    }
		    if (silk){
		    	os['silk'] = 'silk';
		    	os['version'] = silk[1];
		    }
		    if (!silk && os.android && ua.match(/Kindle Fire/)) {
		    	browser['browser'] = 'silk';
		    }
		    if (chrome) {
		    	browser['browser'] = 'chrome';
		    	browser['version'] = chrome[1];
		    }
		    if (firefox) {
		    	browser['browser'] = 'firefox';
		    	browser['version'] = firefox[1];
		    }
		    if (ie){
		    	browser['browser'] = 'ie';
		    	browser['version'] = ie[1];
		    } 
		    if (safari && (osx || os.ios)) {
		    	browser['browser'] = 'safari';
		    	if (osx){ 
		    		browser.version = safari[1];
		    	}
		    }
		    if (webview){
		    	browser['browser'] = 'webview';
		    } 
		    if (weixin){
		    	browser['browser'] = 'weixin';
		    } 
		    os['tablet'] = !!(ipad || playbook || (android && !ua.match(/Mobile/)) ||
		      (firefox && ua.match(/Tablet/)) || (ie && !ua.match(/Phone/) && ua.match(/Touch/)));
		    os['phone'] = !!(!os.tablet && !os.ipod && (android || iphone || webos || blackberry || bb10 ||
		      (chrome && ua.match(/Android/)) || (chrome && ua.match(/CriOS\/([\d.]+)/)) ||
		      (firefox && ua.match(/Mobile/)) || (ie && ua.match(/Touch/))));
		    os.os = os.os || null;
		    os.version = os.version || null;
		    os.cline = os.cline || null;
		    os.kindle = os.kindle || false;
		    os.tablet = os.tablet || null;
		    os.phone = os.phone || false;
		    os.silk = os.silk || null;
		    browser.browser = browser.browser || null;
		    browser.version = browser.version || null;
		}
		function getOs (){
			if(!os || !browser){
				init();
			}
			return os;
		}
		function getBrowser (){
			if(!os || !browser){
				init();
			}
			return browser;
		}
		return {getOs : getOs, getBrowser : getBrowser};
	};
	
	LocalStorageUtil = function(){
		var hasStorage = window.localStorage ? true : false;
		if(hasStorage){
			try{
				window.localStorage.setItem('M_test',1);
			}catch (e) {
				hasStorage = false;
				M.log('localStorage无法set', 'error');
			}
			try{
				window.localStorage.getItem('M_test');
			}catch (e) {
				hasStorage = false;
				M.log('localStorage无法get', 'error');
			}
			try{
				window.localStorage.removeItem('M_test');
			}catch (e) {
				hasStorage = false;
				M.log('localStorage无法remove', 'error');
			}
		}
		function get(key){
			var rs = null;
			if(hasStorage && key){
				rs = window.localStorage.getItem(key);
			}
			return rs;
		}
		function set(key, value){
			if(hasStorage && key){
				try{
					window.localStorage.setItem(key, value);
				}catch (e) {
					LocalStorageUtil.removeAll();
					window.localStorage.setItem(key, value);
				}
			}
		}
		function remove(key){
			if(hasStorage && key){
				window.localStorage.removeItem(key);
			}
		}
		function removeAll(){
			if(hasStorage){
				window.localStorage.clear();
			}
		}
		return {get : get, set : set, remove : remove , removeAll: removeAll};
	};
	
	/************************ Core *********************************/
	/**
	 * 模块定义及加载状态 { factory: [Function], exports: [Object]}
	 */
	M.modules = {};
	/**
	 * 模块资源配置 	name :{depend: [name]}
		
	 */
	M.runMod = [];
	
	/**
	 * 全局配置
	 */
	M.config = {
		debug: 0
	};
	
	/**
	 * 日志
	 */
	M.log = function(msg, type) {
		M.config && M.config.debug && (typeof console !== 'undefined' && console !== null) && 
			(console[type || (type = 'log')]) && console[type](msg);
	};
	
	var Core = {
		require: function(path, callback){
			!ObjectUtil.isArray(path) && (path = Array(path));
			// 加载JS
			Core.loadJs(path, callback);
		},
		loadJs: function(arrJs, callback) {
			$LAB.setOptions({AlwaysPreserveOrder: true}).script(arrJs).wait(function(){
				if(callback){
					callback.call(null);
				}
		    });
		},
		/**
		 * 获取模块定义
		 */
		exports : function(moduleName) {
			if (M.modules[moduleName] && M.modules[moduleName].exports) {
				return M.modules[moduleName].exports;
			} 
			return null;
		},
		/**定义模块
		 * 
		 */
		define : function(moduleName, fn){
			if (arguments.length == 1) {
				fn = moduleName;
			}
			if (ObjectUtil.isEmpty(moduleName) && M.isFunction(fn)) {
				fn.call(null);
				return;
			}
			!M.modules[moduleName] && (M.modules[moduleName] = {});
			M.modules[moduleName]['factory'] = fn;
		
		},
		/**
		 * 执行模块的定义代码
		 */
		defineModule : function() {
			ObjectUtil.each(M.modules, function(value, moduleName){
				var module = M.modules[moduleName];
				if (!module.exports && module.factory) {
					module.exports = {};
					var exports = module.factory.call(null, module.exports);
					exports && (module.exports = exports);		
				}
			});
		},
		/**
		 * 批量设置模块资源配置
		 */
		setRunMod : function(moduleNames, isCover) {
			if(ObjectUtil.isArray(moduleNames)){
				if(isCover){
					M.runMod = moduleNames;
				}else{
					M.runMod = M.runMod.concat(moduleNames);
				}
			}else if(ObjectUtil.isString(moduleNames) && !ObjectUtil.isBlank(moduleNames)){
				if(isCover){
					M.runMod = [moduleNames];
				}else{
					M.runMod.push(moduleNames);
				}
			}
		},
		/**
		 * 获取配置信息
		 */
		setConfig : function(name, value) {
			Core.setGlobalProp('config', name, value);
		},
		
		/**
		 * 全局配置设置
		 */
		setGlobalProp : function(prop, name, value) {
			var global = M[prop];
			// key-value方式设置单个配置
			if (ObjectUtil.isString(name)) {
				/*if (MC.contains(global, name)) {
					MC.log('全局配置被覆盖：' + '[' + prop + ']' + name);
				}*/
				global[name] = value;
				return;
			}
			
			// 对象方式设置多个配置
			if (ObjectUtil.isObject(name)) {
				var obj = name;
				ObjectUtil.each(obj, function(_value, _name){
					setGlobalProp(prop, _name, _value);
				});
			}
		},
		/**
		 * id生成器
		 */
		idSeed : 0,
		genId : function(prefix) {
			var id = (prefix || 'mGen') + (++Core.idSeed);
	        return id;
	    },
		/**
		 * 
		 * @param opt
		 * @param paramsOpt
		 */
		runner : function(args){
			Core.defineModule();
			var hasArgs = false;
			if(ObjectUtil.isObject(args)){
				hasArgs = true;
			}
			ObjectUtil.each(M.runMod, function(moduleName){
				var moduleNameArgs = hasArgs &&  args[moduleName] ? args[moduleName] : null;
				if(Core.exports(moduleName)){
					var exports = Core.exports(moduleName);
					var run = exports.clazz ? new exports.clazz(moduleNameArgs) : exports;
					if(ObjectUtil.isFunction(run.run)){
						try{
							run.run();
						}catch(e){
							M.log(e, 'error');
						}
					}
				}
			});
		}
	};
	M.require = function(){
		return Core.require.apply(null,arguments);
	};
	M.genId = function(){
		return Core.genId.apply(null,arguments);
	};
	M.define = function(){
		return Core.define.apply(null,arguments);
	};
	M.runner = function(){
		return Core.runner.apply(null,arguments);
	};
	M.setConfig = function(){
		return Core.setConfig.apply(null,arguments);
	};
	M.setRunMod = function(){
		return Core.setRunMod.apply(null,arguments);
	};
	M.exports = function(){
		return Core.exports.apply(null,arguments);
	};
	M.http = HttpUtil;
	M.string = StringUtil;
	M.date = DateUtil;
	M.object = ObjectUtil;
	M.cookie = CookieUtil;
	M.url = UrlUtil;
	M.browser = new BrowserUtil();
	M.localstorage = new LocalStorageUtil();
})(window);