require.config({
	baseUrl: '../addons/meepo_bbs/template/mobile/default/',
	urlArgs: "v=7.1.8",
	paths: {
		'jquery': './lib/jquery.min',
		'swiper': './lib/swiper/js/swiper.jquery',
		'base64upload': './lib/upload_file/base64upload',
		'vue':'./lib/vue.min'
	},
	shim:{
		'swiper':{
			exports: '$',
			deps: ['jquery']
		},
		'base64upload':{
			deps: ['jquery']
		}
		
	}
});