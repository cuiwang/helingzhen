function VP_TIME_FORMAT(t){
	var s='';
	if(t>86400){
		s+=parseInt(t/86400)+'天';
		t=t%86400;
	}
	if(t>3600){
		s+=parseInt(t/3600)+'小时';
		t=t%3600;
	}
	if(t>60){
		s+=parseInt(t/60)+'分';
		t=t%60;
	}
	s+=t+'秒';
	return s;
};

// $(function(){
// 	FastClick.attach(document.body);
// });