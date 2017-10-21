require.config({
	baseUrl: 'resource/js/app',
	paths: {
		'css': '../lib/css.min',
		'jquery': '../lib/jquery-1.11.1.min',
		'jquery.hammer': '../lib/jquery.hammer-full.min',
		'angular': '../lib/angular.min',
		'bootstrap': '../lib/bootstrap.min',
		'underscore': '../lib/underscore-min',
		'iscroll': '../lib/iscroll-lite',
		'filestyle': '../lib/bootstrap-filestyle.min',
		'daterangepicker': '../../components/daterangepicker/daterangepicker',
		'WeixinApi': '../lib/WeixinApi'
	},
	shim:{
		'jquery.hammer': {
			exports: "$",
			deps: ['jquery']
		},
		'angular': {
			exports: 'angular',
			deps: ['jquery']
		},
		'bootstrap': {
			exports: "$",
			deps: ['jquery']
		},
		'iscroll': {
			exports: "IScroll",
		},
		'filestyle': {
			exports: '$',
			deps: ['bootstrap']
		},
		'daterangepicker': {
			exports: '$',
			deps: ['bootstrap', 'moment', 'css!../../components/daterangepicker/daterangepicker.css']
		}
	},
});