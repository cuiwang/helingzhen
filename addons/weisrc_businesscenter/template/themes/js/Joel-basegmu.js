//For Memory,For Joel//
//Joel-Wap-前端函数 V1.0//
//Auth:蒋金辰 Joel
//Mail:54006600@qq.com
//(c) Copyright 2014 Joel. All Rights Reserved.

//Joel Alert重置
function Joel_gmuAlert(title,content,cancel,ok){
	var cancelfun=cancel?cancel:function(){this.destroy();};
	var okfun=ok?ok:function(){this.destroy();};
	var opts={
		'title':title,
		'content':content,
		'buttons': {
         	'取消':cancelfun,
         	'确定':okfun
     	}
	};
	var alt=new gmu.Dialog(opts);
}

//2014-07-28 By CC
//单按钮提示框
function Joel_gmuMsg(title,content){
	var opts={
		'title':title,
		'content':content,
		closeBtn: false,
		'buttons': {
         	'确定':function(){this.destroy();}
     	}
	};
	var alt=new gmu.Dialog(opts);
}
