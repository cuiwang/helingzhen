const NUMBER_OF_LEAVES = 7;
 
function leafInit(num)
{

    if (num==0||num=="0") 
	   {return;}
    var container = document.getElementById('leafContainer');
	if(num == 19){
    	for (var i = 0; i < 3; i++) 
	    {
	        container.appendChild(createALeaf(191));
	        container.appendChild(createALeaf(192));
	    }
    }else if(num==22){
    	for (var i = 0; i < 2; i++) 
	    {
	        container.appendChild(createALeaf(193));
	        container.appendChild(createALeaf(194));
	    }
    }else{
    	if(num == 18){
    		var crycle = 4;
    	}else if(num == 21){
    		var crycle = 1;
    	}	
    	if(crycle){
    		for (var i = 0; i < crycle; i++) 
		    {
		        container.appendChild(createALeaf(num));
		    }
    	}else{
			for (var i = 0; i < NUMBER_OF_LEAVES; i++) 
		    {
		        container.appendChild(createALeaf(num));
		    }
    	}
	    
    }

}

 function randomInteger(low, high)
{
    return low + Math.floor(Math.random() * (high - low + 1));
}

 
function randomFloat(low, high)
{
    return low + Math.random() * (high - low);
}

 
function pixelValue(value)
{
    return value + 'px';
}
 
function durationValue(value)
{
    return value + 's';
}
 
function createALeaf(num)
{
    var leafDiv = document.createElement('div');
    var image = document.createElement('img');
    var defaultNum = 4;				//默认数量,4张图
    var imagetype = 'png';			//默认格式
    
    if(num==1) 
	{
		flashtype='realLeaf';
	}
	else if (num==2)
	{
		flashtype='snow';
	}
	else if (num==3)
	{
		flashtype='mgui';
		defaultNum = 5;
	}else if(num == 4){
		flashtype='hd';
		defaultNum = 10;
	}else if (num==5)
	{
		flashtype='xiaohua';
		imagetype='gif';
	}else if (num==6)
	{
		 flashtype='xinhua';
	}else if(num == 7){
		flashtype='lw';
		defaultNum = 6;
	}else if (num==8)
	{
		flashtype='hudie';
	}else if (num==9)
	{
		 flashtype='shuidi';
		 defaultNum = 1;
	}else if (num==10)
	{
		 flashtype='shengdanxuehua';
	}else if (num==11)
	{
		 flashtype='xinniandengguang';
	}else if (num==12)
	{
		 flashtype='piaoluodengguang';
	}else if (num==13)
	{
		 flashtype='flower';
		 defaultNum = 7;
	}else if (num==14)
	{
		 flashtype='luoleaf';
	}else if (num==15)
	{
		 flashtype='piaoyu';
		 defaultNum = 6;
		 document.getElementById('leafContainer').style.opacity = "1";
	}else if (num==16)
	{
		 flashtype='shendang';
		 defaultNum = 1;
	}else if (num==17)
	{
		 flashtype='ym';
		 defaultNum = 2;
	}else if(num == 18){
		flashtype='qiqiu';
		defaultNum = 4;
		image.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+ randomInteger(1, defaultNum) + '.'+imagetype; 
        leafDiv.style.bottom = "-50px";
        leafDiv.style.left = pixelValue(randomInteger(0, document.body.clientWidth));
        leafDiv.style.webkitAnimationName = 'up'+randomInteger(1, 3);
        document.getElementById('leafContainer').style.opacity = "1";

	}else if(num == 191){
		flashtype='zhifeiji';
		image.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+ '1.'+imagetype; 
		leafDiv.style.left = "-50px";
		leafDiv.style.top = pixelValue(randomInteger(0, document.body.clientHeight));
		leafDiv.style.webkitAnimationName = 'left_ani'+randomInteger(1, 2);
		document.getElementById('leafContainer').style.opacity = "1";
		
	}else if(num == 192){
		flashtype='zhifeiji';
		image.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+'2.'+imagetype; 
		leafDiv.style.left ="550px";
		leafDiv.style.top = pixelValue(randomInteger(0, document.body.clientHeight));
		leafDiv.style.webkitAnimationName = "right_ani"+randomInteger(1, 2);
		document.getElementById('leafContainer').style.opacity = "1";
	} else if (num == 20) {
		flashtype='meiguihua';
		defaultNum = 2;
		image.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+ randomInteger(1, defaultNum) + '.'+imagetype;
		leafDiv.style.top = "-50px";
		leafDiv.style.left = pixelValue(randomInteger(0, document.body.clientWidth));
		var spinAnimationName = (Math.random() < 0.5) ? 'clockwiseSpin' : 'counterclockwiseSpinAndFlip';
		leafDiv.style.webkitAnimationName = 'fade, drop';
		image.style.webkitAnimationName = spinAnimationName;
		document.getElementById('leafContainer').style.opacity = "1";
	}

    if(num <18){
    	image.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+ randomInteger(1, defaultNum) + '.'+imagetype;
        leafDiv.style.top = "-50px";
        leafDiv.style.left = pixelValue(randomInteger(0, document.body.clientWidth));
        var spinAnimationName = (Math.random() < 0.5) ? 'clockwiseSpin' : 'counterclockwiseSpinAndFlip';
        leafDiv.style.webkitAnimationName = 'fade, drop';
        image.style.webkitAnimationName = spinAnimationName;
        
    }

    var fadeAndDropDuration = durationValue(randomFloat(5, 11));
    
    var spinDuration = durationValue(randomFloat(4, 8));
     
    leafDiv.style.webkitAnimationDuration = fadeAndDropDuration + ', ' + fadeAndDropDuration;

    var leafDelay = durationValue(randomFloat(0, 5));
    leafDiv.style.webkitAnimationDelay = leafDelay + ', ' + leafDelay;

    image.style.webkitAnimationDuration = spinDuration;
    leafDiv.appendChild(image);

	if(num == 21){
		var yhDiv = document.createElement('div');
    	var yhimage = document.createElement('img');
    	var imagetype = 'png';			//默认格式
		flashtype='yhua';
		defaultNum = 1;
		yhimage.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+ randomInteger(1, defaultNum) + '.'+imagetype;
		yhDiv.style.top = pixelValue(randomInteger(0,$(window).height()));
		yhDiv.style.left = pixelValue(randomInteger(0, document.body.clientWidth)); 
		yhDiv.style.webkitAnimationName = "scaleAni";
		yhDiv.style.opacity  = 0;
		yhDiv.style.minHeight = "50px";
		var yh_s = randomFloat(0, 5);
		var yhDelay = durationValue(yh_s);
    	yhDiv.style.webkitAnimationDelay = yhDelay + ', ' + yhDelay;
		yhDiv.style.webkitAnimationDuration = '2s';
		yhimage.style.webkitAnimationDuration = '2s';
		var id_index = randomFloat(100, 1000);
		yhDiv.id = id_index;
		yhDiv.appendChild(yhimage);
		setTimeout(function(){
			var my = document.getElementById(id_index);
			if (my != null)
    		my.parentNode.removeChild(my);
		},(yh_s + 2)*1000);
		document.getElementById('leafContainer').appendChild(yhDiv);
		setInterval(function(){
			var yhDiv = document.createElement('div');
	    	var yhimage = document.createElement('img');
	    	var imagetype = 'png';			//默认格式
			flashtype='yhua';
			defaultNum = 1;
			yhimage.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+ randomInteger(1, defaultNum) + '.'+imagetype;
			yhDiv.style.top = pixelValue(randomInteger(0,$(window).height()));
			yhDiv.style.left = pixelValue(randomInteger(0, document.body.clientWidth)); 
			yhDiv.style.webkitAnimationName = "scaleAni";
			yhDiv.style.opacity  = 0;
			yhDiv.style.minHeight = "50px";
			var yh_s = randomFloat(0, 5);
			var yhDelay = durationValue(yh_s);
	    	yhDiv.style.webkitAnimationDelay = yhDelay + ', ' + yhDelay;
			yhDiv.style.webkitAnimationDuration = '2s';
			yhimage.style.webkitAnimationDuration = '2s';
			var id_index = randomFloat(100, 1000);
			yhDiv.id = id_index;
			yhDiv.appendChild(yhimage);
			setTimeout(function(){
				var my = document.getElementById(id_index);
    			if (my != null)
        		my.parentNode.removeChild(my);
			},(yh_s + 2)*1000);
			document.getElementById('leafContainer').appendChild(yhDiv);
		},1000)
		
	}else if(num == 193){
		flashtype='yunduo';
		image.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+ '1.'+imagetype; 
		leafDiv.style.left = "-50px";
		leafDiv.style.top = pixelValue(randomInteger(0, $(window).height()));
		leafDiv.style.webkitAnimationName = 'left_ani'+randomInteger(1, 2);
		leafDiv.style.minWidth = "50px";
		leafDiv.style.maxWidth = "70px";
		document.getElementById('leafContainer').style.opacity = "1";

		var fadeAndDropDuration = durationValue(randomFloat(9, 11));
	    var spinDuration = durationValue(randomFloat(4, 8));
	    leafDiv.style.webkitAnimationDuration = fadeAndDropDuration + ', ' + fadeAndDropDuration;
	    var leafDelay = durationValue(randomFloat(0, 5));
	    leafDiv.style.webkitAnimationDelay = leafDelay + ', ' + leafDelay;

	    image.style.webkitAnimationDuration = spinDuration;
	    leafDiv.appendChild(image);
	}else if(num == 194){
		flashtype='yunduo';
		image.src = '../addons/junsion_simpledaily/resource/images/down/' +flashtype+'2.'+imagetype; 
		leafDiv.style.left ="550px";
		leafDiv.style.top = pixelValue(randomInteger(0, $(window).height()));
		leafDiv.style.webkitAnimationName = "right_ani"+randomInteger(1, 2);
		leafDiv.style.minWidth = "50px";
		leafDiv.style.maxWidth = "70px";
		document.getElementById('leafContainer').style.opacity = "1";
		var fadeAndDropDuration = durationValue(randomFloat(9, 11));
	    var spinDuration = durationValue(randomFloat(4, 8));
	    leafDiv.style.webkitAnimationDuration = fadeAndDropDuration + ', ' + fadeAndDropDuration;
	    var leafDelay = durationValue(randomFloat(0, 5));
	    leafDiv.style.webkitAnimationDelay = leafDelay + ', ' + leafDelay;

	    image.style.webkitAnimationDuration = spinDuration;
	    leafDiv.appendChild(image);
	}

    return leafDiv;
}
 
