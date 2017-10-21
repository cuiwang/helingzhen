var version = new Date().getTime();
var myconfig =  {
	path: '../addons/weliam_shiftcar/web/resource/js/',
    alias:{ 
		'jquery': 'jquery-1.11.1.min',
        'jquery.contextMenu' : 'contextMenu/jquery.contextMenu',
	}, 
	 map:{
		'js':'.js?v='+version,
		'css':'.css?v='+version
	},
    css: {
        'jquery.contextMenu' : 'contextMenu/jquery.contextMenu',
	},preload:['jquery']
}

var myrequire = function(arr, callback) {
	var newarr = [ ];
	$.each(arr, function(){
		var js = this;
		
		if( myconfig.css[js]){
			var css = myconfig.css[js].split(',');
			$.each(css,function(){
				 newarr.push( "css!" +  myconfig.path + this + myconfig.map['css']);
			});
			 
		         
		}
		
		var jsitem = this; 
		if( myconfig.alias[js]){
		      jsitem = myconfig.alias[js];
		}
		 
		newarr.push(  myconfig.path + jsitem + myconfig.map['js']);
	});
	require(newarr,callback);
}
