//150901
//chech system
var SysIe;
var ua = navigator.userAgent.toLowerCase();
if (window.ActiveXObject){
	SysIe = ua.match(/msie ([\d.]+)/)[1] -0;
	if(SysIe<7){}
}
var SysChrome=0;
var SysSafari=0;
if(ua.indexOf("chrome")!=-1){ var SysChrome=1; }
if(ua.indexOf("chrome")==-1 && ua.indexOf("safari")!=-1 ){ var SysSafari=1; }

var userAgentInfo = navigator.userAgent; 
var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"); 
var SysPC = true; 
for (var i = 0; i < Agents.length; i++) { 
   if (userAgentInfo.indexOf(Agents[i]) > 0) { SysPC = false; break; } 
}
var SysIPad = false;
if (userAgentInfo.indexOf("iPad") > 0) { SysIPad = true; } 

//Checks if element is defined or is string ID of element
function checkElement(obj) {
	if (typeof obj == 'string') {
		return document.getElementById(obj);
	} else {
		return obj;
	}
}

/* DOM Loaded Event Manager */
function addDOMLoadEvent(func) {
	if (!window.__load_events) {
		var init = function () {
		// quit if this function has already been called
		if (arguments.callee.done) return;

		// flag this function so we don't do the same thing twice
		arguments.callee.done = true;

		// kill the timer
		if (window.__load_timer) {
			clearInterval(window.__load_timer);
			window.__load_timer = null;
		}

		// execute each function in the stack in the order they were added
		for (var i=0;i < window.__load_events.length;i++) {
			window.__load_events[i](); //in HTML5 not working
		}
		window.__load_events = null;
	};
	// for Mozilla/Opera9
	if (document.addEventListener) {
		document.addEventListener("DOMContentLoaded", init, false);
	}

	// for Internet Explorer
	/*@cc_on @*/
	/*@if (@_win32)
		document.write("<scr"+"ipt id=__ie_onload defer src=//0><\/scr"+"ipt>");
		var script = document.getElementById("__ie_onload");
		script.onreadystatechange = function() {
			if (this.readyState == "complete") {
				init(); // call the onload handler
			}
		};
	/*@end @*/
 
	// for Safari
	if (/WebKit/i.test(navigator.userAgent)) { // sniff
		window.__load_timer = setInterval(function() {
			if (/loaded|complete/.test(document.readyState)) {
				init(); // call the onload handler
			}
		}, 10);
	}

	// for other browsers
	window.onload = init;

	// create event function stack
	window.__load_events = [];
	}

	// add function to event stack
	window.__load_events.push(func);
}


//Event Handler
addEvent.guid = 1;
function addEvent(element, type, handler) {
	element = checkElement(element);
	if (typeof element == "undefined" || !element) {
		return false;
	}
	if (element.addEventListener) {
		element.addEventListener(type, handler, false);
	} else {
		// assign each event handler a unique ID
		if (!handler.$$guid) handler.$$guid = addEvent.guid++;
		// create a hash table of event types for the element
		if (!element.events) element.events = {};
		// create a hash table of event handlers for each element/event pair
		var handlers = element.events[type];
		if (!handlers) {
			handlers = element.events[type] = {};
			// store the existing event handler (if there is one)
			if (element["on" + type]) {
				handlers[0] = element["on" + type];
			}
		}
		// store the event handler in the hash table
		handlers[handler.$$guid] = handler;
		// assign a global event handler to do all the work
		element["on" + type] = handleEvent;
	}
}
function removeEvent(element, type, handler) {
	if (element.removeEventListener) {
		element.removeEventListener(type, handler, false);
	} else {
		// delete the event handler from the hash table
		if (element.events && element.events[type]) {
			delete element.events[type][handler.$$guid];
		}
	}
};
function handleEvent(event) {
	var returnValue = true;
	// grab the event object (IE uses a global event object)
	event = event || fixEvent(((this.ownerDocument || this.document || this).parentWindow || window).event);
	// get a reference to the hash table of event handlers
	var handlers = this.events[event.type];
	// execute each event handler
	for (var i in handlers) {
		this.$$handleEvent = handlers[i];
		if (this.$$handleEvent(event) === false) {
			returnValue = false;
		}
	}
	return returnValue;
};
function fixEvent(event) {
	// add W3C standard event methods
	event.preventDefault = fixEvent.preventDefault;
	event.stopPropagation = fixEvent.stopPropagation;
	return event;
};
fixEvent.preventDefault = function() {
	this.returnValue = false;
};
fixEvent.stopPropagation = function() {
	this.cancelBubble = true;
};

//Checks whether element has a specific css class
function hasClass(obj, className) {
	if (typeof obj == 'undefined' || typeof className == 'undefined')
		return false;
	var reClass = ((typeof className == 'object' || typeof className == 'function') && 'exec' in className) ? className : buildAttributeRegEx(className);
	if (obj.className.search(reClass) != -1)
		return true;
	return false;
}
//Adds class to element
function addClass(obj, className) {
	obj = checkElement(obj);
	if (!obj || !className)
		return false;
	//Make sure element doesn't already have class
	if (!hasClass(obj, className))
		obj.className = obj.className + " " + className;
}
//Removes class from element
function removeClass(obj, className) {
	if (!obj || !className)
		return false;
	var objs = new Array();
	if ('length' in obj)//Check if element is an array
		objs = obj;
	else
		objs[0]= obj;
	var reClass = buildAttributeRegEx(className);
	for (var x = 0; x < objs.length; x++) { 
		objs[x].className = objs[x].className.replace(reClass, "");
	}
}
function buildAttributeRegEx(searchString) {
	var re = new RegExp('(?:^|\\b)' + searchString + '(?:$|\\b)');
	return re;
}


//clean the whitespace among the elements
function cleanWhitespace(obj){
	for(var i=0; i<obj.childNodes.length; i++)
	{
	   var node = obj.childNodes[i];
	   if(node.nodeType == 3 && !/\S/.test(node.nodeValue))
	   {
		  node.parentNode.removeChild(node);
	   }
	}
}


//get all the get string
function getGet(){ 
	querystr = window.location.href.split("?")
	if(querystr[1]){
		GETs = querystr[1].split("&")
		var GET =new Array()
		for(i=0;i<GETs.length;i++){
			tmp_arr = GETs[i].split("=")
			key=tmp_arr[0]
			GET[key] = tmp_arr[1]
		}
		return GET;
	} else return false
}


//get site root URL
function getSiteRoot() {
	var url = window.location.protocol + '//' + window.location.host,
		path = '/Site';
	if (window.location.pathname.indexOf(path) == 0) {
		url += path
	}
	return url + '/';
}

//get current style
function getCurrentStyle(obj, prop) {
	if (obj.currentStyle) { //IE
		return obj.currentStyle[prop];
	} else if (window.getComputedStyle) { //W3C
		propprop = prop.replace(/([A-Z])/g, "-$1");
		propprop = prop.toLowerCase();
		return document.defaultView.getComputedStyle(obj, null)[propprop];
	}
	return null;
}


//get the scrolled height
function getScroll(){
	if (document.documentElement) {
		y1 = document.documentElement.scrollTop || 0; x1 = document.documentElement.scrollLeft || 0; }
    if (document.body) {
		y2 = document.body.scrollTop || 0; x2 = document.body.scrollLeft || 0; }
    var y3 = window.scrollY || 0;
	var x3 = window.scrollX || 0;
	return {"x": Math.max(x1, Math.max(x2, x3)), "y": Math.max(y1, Math.max(y2, y3))}
}

function getSizePage(){
	var pageWidth = 0;
	var pageHeight = 0;
	if (window.scrollHeight){
		pageHeight = window.innerHeight; pageWidth = window.innerWidth; }
	else if ((document.body) && (document.body.scrollHeight)){
		pageHeight = document.body.scrollHeight; pageWidth = document.body.scrollWidth; }
	if (document.documentElement  && document.documentElement.scrollHeight && document.documentElement.scrollWidth){
		pageHeight = document.documentElement.scrollHeight; pageWidth = document.documentElement.scrollWidth;}
	return {"x": pageWidth, "y": pageHeight}
}

//set input field default text
function inputFieldText(obj, txt) {
	var obj=checkElement(obj);
	var txt=txt;
	addEvent(obj, 'blur', function(){
		if (obj.value=="") {obj.value=txt}
		else return false
	} );
	if (obj.value==txt) {obj.value=""}
	else return false
}

//insert after element
function insertAfter(newEl, targetEl) { 
	var parentEl = targetEl.parentNode; 
	if(parentEl.lastChild == targetEl) { 
	parentEl.appendChild(newEl); 
	}else { 
		parentEl.insertBefore(newEl,targetEl.nextSibling); 
	} 
}

//
function getScrollOffsets(_w) {
	_w = _w || window;
	//for all and IE9+
	if (_w.pageXOffset != null) return {
		x: _w.pageXOffset,
		y: _w.pageYOffset
	};
	//for IE678
	var _d = _w.document;
	if (document.compatMode == "CSS1Compat") return { //for IE678
		x: _d.documentElement.scrollLeft,
		y: _d.documentElement.scrollTop
	};
	//for other mode
	return {
		x: _d.body.scrollLeft,
		y: _d.body.scrpllTop
	};
}

function getViewPortSize(_w) {
	_w = _w || window;
	//for all and IE9+
	if (_w.innerWidth != null) return {
		x: _w.innerWidth,
		y: _w.innerHeight
	};
	//for IE678
	var _d = _w.document;
	if (document.compatMode == "CSS1Compat") return { //for IE678
		x: _d.documentElement.clientWidth,
		y: _d.documentElement.clientHeight
	};
	//for other mode
	return {
		x: _d.body.clientWidth,
		y: _d.body.clientHeight
	};

}
//get the window size
function getSizeWindow() {//same as getViewPortSize
	var winWidth = 0;
	var winHeight = 0;
	if (window.innerHeight){
		winHeight = window.innerHeight; winWidth = window.innerWidth; }
	else if ((document.body) && (document.body.clientHeight)){
		winHeight = document.body.clientHeight; winWidth = document.body.clientWidth; }
	if (document.documentElement  && document.documentElement.clientHeight && document.documentElement.clientWidth){
		winHeight = document.documentElement.clientHeight; winWidth = document.documentElement.clientWidth;}

	return {"x": winWidth, "y": winHeight}
}

//remove the blank and undefined elements in an Array
function removeBlankInArray(array) {
	for(var i = 0; i<array.length; i++) {
		if(array[i] == "" || typeof(array[i]) == "undefined")
		{
			array.splice(i,1);
			i= i-1;
		}
	}
	return array;
}

//getElementsByClass
var getElementsByClassName = function(searchClass,node,tag) {
	if(document.getElementsByClassName){
		var nodes = (node || document).getElementsByClassName(searchClass), result = [];
		if (tag && tag !== "*") {
		for(var i=0; i < nodes.length; i++){
		if(nodes[i].tagName === tag.toUpperCase()){
		result.push(nodes[i]);
		}
		}
		} else {
		result = nodes;
		}
		return result;
	}else{
		node = node || document;
		tag = tag || '*';
		var returnElements = []
		var els =  (tag === "*" && node.all)? node.all : node.getElementsByTagName(tag);
		var i = els.length;
		searchClass = searchClass.replace(/\-/g, "\\-");
		var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
		while(--i >= 0){
			if (pattern.test(els[i].className) ) {
				returnElements.push(els[i]);
			}
		}
		return returnElements;
	}
}