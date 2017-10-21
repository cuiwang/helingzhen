//进行默认的配置操作
require.config({
	baseUrl: mbCore.resourceUrl + '/static/libs/',
	paths: {
		MHJ: ['MHJ.js?v=201505251808'],
		ngapi: ['ngapi.js?v=201505251808'],
		jquery: ['jquery.min'],
		zepto: ['zepto.min'],
		imgpreload: ['imgpreload'],
		auth: ['auth'],
		css: ['css.min'], //css.min.js用于加载css文件,自动在页面上添加link标签，github:https://github.com/guybedford/require-css
		wx: ['http://res.wx.qq.com/open/js/jweixin-1.0.0'],
		wxshare: ['wxshare.js?v=201510091432'],
		loading: ['loading'],
		barcode: ['jquery-barcode.min'],
		swiper: ['idangerous.swiper.min'],
		BreakingNews: ['BreakingNews'],
		MetaFix: ['metaFix'],
		shake : ['shake']
	},
	shim: {
		BreakingNews: {
			deps: ['jquery'],
			exports: 'BreakingNews'
		},
		barcode:{
			deps: ['jquery'],
		}
	}
});

