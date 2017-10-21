//切换
	function Tabs(thisObj,Num){
		if(thisObj.className == "onarr")return;
		var tabObj = thisObj.parentNode.id;
		var tabList = document.getElementById(tabObj).getElementsByTagName("li");
		for(i=0; i <tabList.length; i++)
		{
		  if (i == Num)
		  {
		   thisObj.className = "onarr"; //onarr 为选中之后显示的样式
			 document.getElementById(tabObj+"_c"+i).style.display = "block";
		  }else{
		   tabList[i].className = "offarr";  //offarr 为没有选中显示的样式
		   document.getElementById(tabObj+"_c"+i).style.display = "none";
		  }
		} 
	}
	
//切换2
	function Tabs(thisObj,Num){
		if(thisObj.className == "onarr1")return;
		var tabObj = thisObj.parentNode.id;
		var tabList = document.getElementById(tabObj).getElementsByTagName("li");
		for(i=0; i <tabList.length; i++)
		{
		  if (i == Num)
		  {
		   thisObj.className = "onarr1"; //onarr 为选中之后显示的样式
			 document.getElementById(tabObj+"_c"+i).style.display = "block";
		  }else{
		   tabList[i].className = "offarr1";  //offarr 为没有选中显示的样式
		   document.getElementById(tabObj+"_c"+i).style.display = "none";
		  }
		} 
	}	
