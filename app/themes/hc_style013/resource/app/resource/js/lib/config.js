require.config({
	baseUrl: 'resource/js/app',
	urlArgs: "v=" +  (new Date()).getHours(),
	paths: {
		'css': '../lib/css.min',
		'jquery': '../lib/jquery-1.11.1.min',
		'hammer': '../lib/hammer.min',
		'angular': '../lib/angular.min',
		'bootstrap': '../lib/bootstrap.min',
		'underscore': '../lib/underscore-min',
		'iscroll': '../lib/iscroll-lite',
		'moment': '../lib/moment',
		'filestyle': '../lib/bootstrap-filestyle.min',
		'daterangepicker': '../../components/daterangepicker/daterangepicker',
		'datetimepicker': '../../components/datetimepicker/bootstrap-datetimepicker.min',
		'map': 'http://api.map.baidu.com/getscript?v=2.0&ak=F51571495f717ff1194de02366bb8da9&services=&t=20140530104353',
		'webuploader' : '../../components/webuploader/webuploader.min'
	},
	shim:{
		'angular': {
			exports: 'angular',
			deps: ['jquery']
		},
		'bootstrap': {
			exports: "$",
			deps: ['jquery']
		},
		'iscroll': {
			exports: "IScroll"
		},
		'filestyle': {
			exports: '$',
			deps: ['bootstrap']
		},
		'daterangepicker': {
			exports: '$',
			deps: ['bootstrap', 'moment', 'css!../../components/daterangepicker/daterangepicker.css']
		},
		'datetimepicker': {
			exports: '$',
			deps: ['bootstrap', 'css!../../components/datetimepicker/bootstrap-datetimepicker.min.css']
		},
		'map': {
			exports: 'BMap'
		},
		'webuploader': {
			deps: ['jquery', 'css!../../components/webuploader/webuploader.css', 'css!../../components/webuploader/style.css']
		}
	}
});