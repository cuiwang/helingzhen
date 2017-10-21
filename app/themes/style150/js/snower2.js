var num = Math.round((Math.random(3)+ 1)*10);
var snows = new Array(num);
snows = snows.join(",").split(",");
var Tpl = '<div style="top: {top}; left: {left}; -webkit-animation: fade {t1} {t2}, drop {t1} {t2};">\
			<img src="{url}" style="-webkit-animation: counterclockwiseSpinAndFlip {t5};width:{width};max-width:{maxHeight}; max-height:{maxHeight};">\
			</div>';
var snowsHTML = iTemplate.makeList(Tpl, snows, function(k,v){
	var obj = {
		top: "-100px",
		left: Math.random()*100 +"%",
		t1:Math.random()*(11-5)+5 +"s",
		t2:Math.random()*4 +"s",
		//t3:Math.random()*(11-5)+5 +"s",
		//t4:Math.random()*4 +"s",
		t5:Math.random()*(11-5)+5 +"s",
		url: urls[Math.floor(Math.random()*urls.length)],
		width: "auto",
		maxHeight:"60px"
	}
	return obj;
});
document.write(snowsHTML);