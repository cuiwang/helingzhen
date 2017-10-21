// JavaScript Document

;/*
	 * HTML5 Shiv v3.6.2pre | @afarkas @jdalton @jon_neal @rem | MIT/GPL2
	 * Licensed Uncompressed source: https://github.com/aFarkas/html5shiv
	 */
(function(l,f){function m(){var a=e.elements;return"string"==typeof a?a.split(" "):a}function i(a){var b=n[a[o]];b||(b={},h++,a[o]=h,n[h]=b);return b}function p(a,b,c){b||(b=f);if(g)return b.createElement(a);c||(c=i(b));b=c.cache[a]?c.cache[a].cloneNode():r.test(a)?(c.cache[a]=c.createElem(a)).cloneNode():c.createElem(a);return b.canHaveChildren&&!s.test(a)?c.frag.appendChild(b):b}function t(a,b){if(!b.cache)b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag();
a.createElement=function(c){return!e.shivMethods?b.createElem(c):p(c,a,b)};a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+m().join().replace(/\w+/g,function(a){b.createElem(a);b.frag.createElement(a);return'c("'+a+'")'})+");return n}")(e,b.frag)}function q(a){a||(a=f);var b=i(a);if(e.shivCSS&&!j&&!b.hasCSS){var c,d=a;c=d.createElement("p");d=d.getElementsByTagName("head")[0]||d.documentElement;c.innerHTML="x<style>article,aside,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}</style>";
c=d.insertBefore(c.lastChild,d.firstChild);b.hasCSS=!!c}g||t(a,b);return a}var k=l.html5||{},s=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,r=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,j,o="_html5shiv",h=0,n={},g;(function(){try{var a=f.createElement("a");a.innerHTML="<xyz></xyz>";j="hidden"in a;var b;if(!(b=1==a.childNodes.length)){f.createElement("a");var c=f.createDocumentFragment();b="undefined"==typeof c.cloneNode||
"undefined"==typeof c.createDocumentFragment||"undefined"==typeof c.createElement}g=b}catch(d){g=j=!0}})();var e={elements:k.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup main mark meter nav output progress section summary time video",version:"3.6.2pre",shivCSS:!1!==k.shivCSS,supportsUnknownElements:g,shivMethods:!1!==k.shivMethods,type:"default",shivDocument:q,createElement:p,createDocumentFragment:function(a,b){a||(a=f);if(g)return a.createDocumentFragment();
for(var b=b||i(a),c=b.frag.cloneNode(),d=0,e=m(),h=e.length;d<h;d++)c.createElement(e[d]);return c}};l.html5=e;q(f)})(this,document);
;/* ! jQuery v1.8.2 jquery.com | jquery.org/license */
(function(a,b){function G(a){var b=F[a]={};return p.each(a.split(s),function(a,c){b[c]=!0}),b}function J(a,c,d){if(d===b&&a.nodeType===1){var e="data-"+c.replace(I,"-$1").toLowerCase();d=a.getAttribute(e);if(typeof d=="string"){try{d=d==="true"?!0:d==="false"?!1:d==="null"?null:+d+""===d?+d:H.test(d)?p.parseJSON(d):d}catch(f){}p.data(a,c,d)}else d=b}return d}function K(a){var b;for(b in a){if(b==="data"&&p.isEmptyObject(a[b]))continue;if(b!=="toJSON")return!1}return!0}function ba(){return!1}function bb(){return!0}function bh(a){return!a||!a.parentNode||a.parentNode.nodeType===11}function bi(a,b){do a=a[b];while(a&&a.nodeType!==1);return a}function bj(a,b,c){b=b||0;if(p.isFunction(b))return p.grep(a,function(a,d){var e=!!b.call(a,d,a);return e===c});if(b.nodeType)return p.grep(a,function(a,d){return a===b===c});if(typeof b=="string"){var d=p.grep(a,function(a){return a.nodeType===1});if(be.test(b))return p.filter(b,d,!c);b=p.filter(b,d)}return p.grep(a,function(a,d){return p.inArray(a,b)>=0===c})}function bk(a){var b=bl.split("|"),c=a.createDocumentFragment();if(c.createElement)while(b.length)c.createElement(b.pop());return c}function bC(a,b){return a.getElementsByTagName(b)[0]||a.appendChild(a.ownerDocument.createElement(b))}function bD(a,b){if(b.nodeType!==1||!p.hasData(a))return;var c,d,e,f=p._data(a),g=p._data(b,f),h=f.events;if(h){delete g.handle,g.events={};for(c in h)for(d=0,e=h[c].length;d<e;d++)p.event.add(b,c,h[c][d])}g.data&&(g.data=p.extend({},g.data))}function bE(a,b){var c;if(b.nodeType!==1)return;b.clearAttributes&&b.clearAttributes(),b.mergeAttributes&&b.mergeAttributes(a),c=b.nodeName.toLowerCase(),c==="object"?(b.parentNode&&(b.outerHTML=a.outerHTML),p.support.html5Clone&&a.innerHTML&&!p.trim(b.innerHTML)&&(b.innerHTML=a.innerHTML)):c==="input"&&bv.test(a.type)?(b.defaultChecked=b.checked=a.checked,b.value!==a.value&&(b.value=a.value)):c==="option"?b.selected=a.defaultSelected:c==="input"||c==="textarea"?b.defaultValue=a.defaultValue:c==="script"&&b.text!==a.text&&(b.text=a.text),b.removeAttribute(p.expando)}function bF(a){return typeof a.getElementsByTagName!="undefined"?a.getElementsByTagName("*"):typeof a.querySelectorAll!="undefined"?a.querySelectorAll("*"):[]}function bG(a){bv.test(a.type)&&(a.defaultChecked=a.checked)}function bY(a,b){if(b in a)return b;var c=b.charAt(0).toUpperCase()+b.slice(1),d=b,e=bW.length;while(e--){b=bW[e]+c;if(b in a)return b}return d}function bZ(a,b){return a=b||a,p.css(a,"display")==="none"||!p.contains(a.ownerDocument,a)}function b$(a,b){var c,d,e=[],f=0,g=a.length;for(;f<g;f++){c=a[f];if(!c.style)continue;e[f]=p._data(c,"olddisplay"),b?(!e[f]&&c.style.display==="none"&&(c.style.display=""),c.style.display===""&&bZ(c)&&(e[f]=p._data(c,"olddisplay",cc(c.nodeName)))):(d=bH(c,"display"),!e[f]&&d!=="none"&&p._data(c,"olddisplay",d))}for(f=0;f<g;f++){c=a[f];if(!c.style)continue;if(!b||c.style.display==="none"||c.style.display==="")c.style.display=b?e[f]||"":"none"}return a}function b_(a,b,c){var d=bP.exec(b);return d?Math.max(0,d[1]-(c||0))+(d[2]||"px"):b}function ca(a,b,c,d){var e=c===(d?"border":"content")?4:b==="width"?1:0,f=0;for(;e<4;e+=2)c==="margin"&&(f+=p.css(a,c+bV[e],!0)),d?(c==="content"&&(f-=parseFloat(bH(a,"padding"+bV[e]))||0),c!=="margin"&&(f-=parseFloat(bH(a,"border"+bV[e]+"Width"))||0)):(f+=parseFloat(bH(a,"padding"+bV[e]))||0,c!=="padding"&&(f+=parseFloat(bH(a,"border"+bV[e]+"Width"))||0));return f}function cb(a,b,c){var d=b==="width"?a.offsetWidth:a.offsetHeight,e=!0,f=p.support.boxSizing&&p.css(a,"boxSizing")==="border-box";if(d<=0||d==null){d=bH(a,b);if(d<0||d==null)d=a.style[b];if(bQ.test(d))return d;e=f&&(p.support.boxSizingReliable||d===a.style[b]),d=parseFloat(d)||0}return d+ca(a,b,c||(f?"border":"content"),e)+"px"}function cc(a){if(bS[a])return bS[a];var b=p("<"+a+">").appendTo(e.body),c=b.css("display");b.remove();if(c==="none"||c===""){bI=e.body.appendChild(bI||p.extend(e.createElement("iframe"),{frameBorder:0,width:0,height:0}));if(!bJ||!bI.createElement)bJ=(bI.contentWindow||bI.contentDocument).document,bJ.write("<!doctype html><html><body>"),bJ.close();b=bJ.body.appendChild(bJ.createElement(a)),c=bH(b,"display"),e.body.removeChild(bI)}return bS[a]=c,c}function ci(a,b,c,d){var e;if(p.isArray(b))p.each(b,function(b,e){c||ce.test(a)?d(a,e):ci(a+"["+(typeof e=="object"?b:"")+"]",e,c,d)});else if(!c&&p.type(b)==="object")for(e in b)ci(a+"["+e+"]",b[e],c,d);else d(a,b)}function cz(a){return function(b,c){typeof b!="string"&&(c=b,b="*");var d,e,f,g=b.toLowerCase().split(s),h=0,i=g.length;if(p.isFunction(c))for(;h<i;h++)d=g[h],f=/^\+/.test(d),f&&(d=d.substr(1)||"*"),e=a[d]=a[d]||[],e[f?"unshift":"push"](c)}}function cA(a,c,d,e,f,g){f=f||c.dataTypes[0],g=g||{},g[f]=!0;var h,i=a[f],j=0,k=i?i.length:0,l=a===cv;for(;j<k&&(l||!h);j++)h=i[j](c,d,e),typeof h=="string"&&(!l||g[h]?h=b:(c.dataTypes.unshift(h),h=cA(a,c,d,e,h,g)));return(l||!h)&&!g["*"]&&(h=cA(a,c,d,e,"*",g)),h}function cB(a,c){var d,e,f=p.ajaxSettings.flatOptions||{};for(d in c)c[d]!==b&&((f[d]?a:e||(e={}))[d]=c[d]);e&&p.extend(!0,a,e)}function cC(a,c,d){var e,f,g,h,i=a.contents,j=a.dataTypes,k=a.responseFields;for(f in k)f in d&&(c[k[f]]=d[f]);while(j[0]==="*")j.shift(),e===b&&(e=a.mimeType||c.getResponseHeader("content-type"));if(e)for(f in i)if(i[f]&&i[f].test(e)){j.unshift(f);break}if(j[0]in d)g=j[0];else{for(f in d){if(!j[0]||a.converters[f+" "+j[0]]){g=f;break}h||(h=f)}g=g||h}if(g)return g!==j[0]&&j.unshift(g),d[g]}function cD(a,b){var c,d,e,f,g=a.dataTypes.slice(),h=g[0],i={},j=0;a.dataFilter&&(b=a.dataFilter(b,a.dataType));if(g[1])for(c in a.converters)i[c.toLowerCase()]=a.converters[c];for(;e=g[++j];)if(e!=="*"){if(h!=="*"&&h!==e){c=i[h+" "+e]||i["* "+e];if(!c)for(d in i){f=d.split(" ");if(f[1]===e){c=i[h+" "+f[0]]||i["* "+f[0]];if(c){c===!0?c=i[d]:i[d]!==!0&&(e=f[0],g.splice(j--,0,e));break}}}if(c!==!0)if(c&&a["throws"])b=c(b);else try{b=c(b)}catch(k){return{state:"parsererror",error:c?k:"No conversion from "+h+" to "+e}}}h=e}return{state:"success",data:b}}function cL(){try{return new a.XMLHttpRequest}catch(b){}}function cM(){try{return new a.ActiveXObject("Microsoft.XMLHTTP")}catch(b){}}function cU(){return setTimeout(function(){cN=b},0),cN=p.now()}function cV(a,b){p.each(b,function(b,c){var d=(cT[b]||[]).concat(cT["*"]),e=0,f=d.length;for(;e<f;e++)if(d[e].call(a,b,c))return})}function cW(a,b,c){var d,e=0,f=0,g=cS.length,h=p.Deferred().always(function(){delete i.elem}),i=function(){var b=cN||cU(),c=Math.max(0,j.startTime+j.duration-b),d=1-(c/j.duration||0),e=0,f=j.tweens.length;for(;e<f;e++)j.tweens[e].run(d);return h.notifyWith(a,[j,d,c]),d<1&&f?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:p.extend({},b),opts:p.extend(!0,{specialEasing:{}},c),originalProperties:b,originalOptions:c,startTime:cN||cU(),duration:c.duration,tweens:[],createTween:function(b,c,d){var e=p.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(e),e},stop:function(b){var c=0,d=b?j.tweens.length:0;for(;c<d;c++)j.tweens[c].run(1);return b?h.resolveWith(a,[j,b]):h.rejectWith(a,[j,b]),this}}),k=j.props;cX(k,j.opts.specialEasing);for(;e<g;e++){d=cS[e].call(j,a,k,j.opts);if(d)return d}return cV(j,k),p.isFunction(j.opts.start)&&j.opts.start.call(a,j),p.fx.timer(p.extend(i,{anim:j,queue:j.opts.queue,elem:a})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}function cX(a,b){var c,d,e,f,g;for(c in a){d=p.camelCase(c),e=b[d],f=a[c],p.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=p.cssHooks[d];if(g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}}function cY(a,b,c){var d,e,f,g,h,i,j,k,l=this,m=a.style,n={},o=[],q=a.nodeType&&bZ(a);c.queue||(j=p._queueHooks(a,"fx"),j.unqueued==null&&(j.unqueued=0,k=j.empty.fire,j.empty.fire=function(){j.unqueued||k()}),j.unqueued++,l.always(function(){l.always(function(){j.unqueued--,p.queue(a,"fx").length||j.empty.fire()})})),a.nodeType===1&&("height"in b||"width"in b)&&(c.overflow=[m.overflow,m.overflowX,m.overflowY],p.css(a,"display")==="inline"&&p.css(a,"float")==="none"&&(!p.support.inlineBlockNeedsLayout||cc(a.nodeName)==="inline"?m.display="inline-block":m.zoom=1)),c.overflow&&(m.overflow="hidden",p.support.shrinkWrapBlocks||l.done(function(){m.overflow=c.overflow[0],m.overflowX=c.overflow[1],m.overflowY=c.overflow[2]}));for(d in b){f=b[d];if(cP.exec(f)){delete b[d];if(f===(q?"hide":"show"))continue;o.push(d)}}g=o.length;if(g){h=p._data(a,"fxshow")||p._data(a,"fxshow",{}),q?p(a).show():l.done(function(){p(a).hide()}),l.done(function(){var b;p.removeData(a,"fxshow",!0);for(b in n)p.style(a,b,n[b])});for(d=0;d<g;d++)e=o[d],i=l.createTween(e,q?h[e]:0),n[e]=h[e]||p.style(a,e),e in h||(h[e]=i.start,q&&(i.end=i.start,i.start=e==="width"||e==="height"?1:0))}}function cZ(a,b,c,d,e){return new cZ.prototype.init(a,b,c,d,e)}function c$(a,b){var c,d={height:a},e=0;b=b?1:0;for(;e<4;e+=2-b)c=bV[e],d["margin"+c]=d["padding"+c]=a;return b&&(d.opacity=d.width=a),d}function da(a){return p.isWindow(a)?a:a.nodeType===9?a.defaultView||a.parentWindow:!1}var c,d,e=a.document,f=a.location,g=a.navigator,h=a.jQuery,i=a.$,j=Array.prototype.push,k=Array.prototype.slice,l=Array.prototype.indexOf,m=Object.prototype.toString,n=Object.prototype.hasOwnProperty,o=String.prototype.trim,p=function(a,b){return new p.fn.init(a,b,c)},q=/[\-+]?(?:\d*\.|)\d+(?:[eE][\-+]?\d+|)/.source,r=/\S/,s=/\s+/,t=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,u=/^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,v=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,w=/^[\],:{}\s]*$/,x=/(?:^|:|,)(?:\s*\[)+/g,y=/\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,z=/"[^"\\\r\n]*"|true|false|null|-?(?:\d\d*\.|)\d+(?:[eE][\-+]?\d+|)/g,A=/^-ms-/,B=/-([\da-z])/gi,C=function(a,b){return(b+"").toUpperCase()},D=function(){e.addEventListener?(e.removeEventListener("DOMContentLoaded",D,!1),p.ready()):e.readyState==="complete"&&(e.detachEvent("onreadystatechange",D),p.ready())},E={};p.fn=p.prototype={constructor:p,init:function(a,c,d){var f,g,h,i;if(!a)return this;if(a.nodeType)return this.context=this[0]=a,this.length=1,this;if(typeof a=="string"){a.charAt(0)==="<"&&a.charAt(a.length-1)===">"&&a.length>=3?f=[null,a,null]:f=u.exec(a);if(f&&(f[1]||!c)){if(f[1])return c=c instanceof p?c[0]:c,i=c&&c.nodeType?c.ownerDocument||c:e,a=p.parseHTML(f[1],i,!0),v.test(f[1])&&p.isPlainObject(c)&&this.attr.call(a,c,!0),p.merge(this,a);g=e.getElementById(f[2]);if(g&&g.parentNode){if(g.id!==f[2])return d.find(a);this.length=1,this[0]=g}return this.context=e,this.selector=a,this}return!c||c.jquery?(c||d).find(a):this.constructor(c).find(a)}return p.isFunction(a)?d.ready(a):(a.selector!==b&&(this.selector=a.selector,this.context=a.context),p.makeArray(a,this))},selector:"",jquery:"1.8.2",length:0,size:function(){return this.length},toArray:function(){return k.call(this)},get:function(a){return a==null?this.toArray():a<0?this[this.length+a]:this[a]},pushStack:function(a,b,c){var d=p.merge(this.constructor(),a);return d.prevObject=this,d.context=this.context,b==="find"?d.selector=this.selector+(this.selector?" ":"")+c:b&&(d.selector=this.selector+"."+b+"("+c+")"),d},each:function(a,b){return p.each(this,a,b)},ready:function(a){return p.ready.promise().done(a),this},eq:function(a){return a=+a,a===-1?this.slice(a):this.slice(a,a+1)},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},slice:function(){return this.pushStack(k.apply(this,arguments),"slice",k.call(arguments).join(","))},map:function(a){return this.pushStack(p.map(this,function(b,c){return a.call(b,c,b)}))},end:function(){return this.prevObject||this.constructor(null)},push:j,sort:[].sort,splice:[].splice},p.fn.init.prototype=p.fn,p.extend=p.fn.extend=function(){var a,c,d,e,f,g,h=arguments[0]||{},i=1,j=arguments.length,k=!1;typeof h=="boolean"&&(k=h,h=arguments[1]||{},i=2),typeof h!="object"&&!p.isFunction(h)&&(h={}),j===i&&(h=this,--i);for(;i<j;i++)if((a=arguments[i])!=null)for(c in a){d=h[c],e=a[c];if(h===e)continue;k&&e&&(p.isPlainObject(e)||(f=p.isArray(e)))?(f?(f=!1,g=d&&p.isArray(d)?d:[]):g=d&&p.isPlainObject(d)?d:{},h[c]=p.extend(k,g,e)):e!==b&&(h[c]=e)}return h},p.extend({noConflict:function(b){return a.$===p&&(a.$=i),b&&a.jQuery===p&&(a.jQuery=h),p},isReady:!1,readyWait:1,holdReady:function(a){a?p.readyWait++:p.ready(!0)},ready:function(a){if(a===!0?--p.readyWait:p.isReady)return;if(!e.body)return setTimeout(p.ready,1);p.isReady=!0;if(a!==!0&&--p.readyWait>0)return;d.resolveWith(e,[p]),p.fn.trigger&&p(e).trigger("ready").off("ready")},isFunction:function(a){return p.type(a)==="function"},isArray:Array.isArray||function(a){return p.type(a)==="array"},isWindow:function(a){return a!=null&&a==a.window},isNumeric:function(a){return!isNaN(parseFloat(a))&&isFinite(a)},type:function(a){return a==null?String(a):E[m.call(a)]||"object"},isPlainObject:function(a){if(!a||p.type(a)!=="object"||a.nodeType||p.isWindow(a))return!1;try{if(a.constructor&&!n.call(a,"constructor")&&!n.call(a.constructor.prototype,"isPrototypeOf"))return!1}catch(c){return!1}var d;for(d in a);return d===b||n.call(a,d)},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},error:function(a){throw new Error(a)},parseHTML:function(a,b,c){var d;return!a||typeof a!="string"?null:(typeof b=="boolean"&&(c=b,b=0),b=b||e,(d=v.exec(a))?[b.createElement(d[1])]:(d=p.buildFragment([a],b,c?null:[]),p.merge([],(d.cacheable?p.clone(d.fragment):d.fragment).childNodes)))},parseJSON:function(b){if(!b||typeof b!="string")return null;b=p.trim(b);if(a.JSON&&a.JSON.parse)return a.JSON.parse(b);if(w.test(b.replace(y,"@").replace(z,"]").replace(x,"")))return(new Function("return "+b))();p.error("Invalid JSON: "+b)},parseXML:function(c){var d,e;if(!c||typeof c!="string")return null;try{a.DOMParser?(e=new DOMParser,d=e.parseFromString(c,"text/xml")):(d=new ActiveXObject("Microsoft.XMLDOM"),d.async="false",d.loadXML(c))}catch(f){d=b}return(!d||!d.documentElement||d.getElementsByTagName("parsererror").length)&&p.error("Invalid XML: "+c),d},noop:function(){},globalEval:function(b){b&&r.test(b)&&(a.execScript||function(b){a.eval.call(a,b)})(b)},camelCase:function(a){return a.replace(A,"ms-").replace(B,C)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,c,d){var e,f=0,g=a.length,h=g===b||p.isFunction(a);if(d){if(h){for(e in a)if(c.apply(a[e],d)===!1)break}else for(;f<g;)if(c.apply(a[f++],d)===!1)break}else if(h){for(e in a)if(c.call(a[e],e,a[e])===!1)break}else for(;f<g;)if(c.call(a[f],f,a[f++])===!1)break;return a},trim:o&&!o.call("? ")?function(a){return a==null?"":o.call(a)}:function(a){return a==null?"":(a+"").replace(t,"")},makeArray:function(a,b){var c,d=b||[];return a!=null&&(c=p.type(a),a.length==null||c==="string"||c==="function"||c==="regexp"||p.isWindow(a)?j.call(d,a):p.merge(d,a)),d},inArray:function(a,b,c){var d;if(b){if(l)return l.call(b,a,c);d=b.length,c=c?c<0?Math.max(0,d+c):c:0;for(;c<d;c++)if(c in b&&b[c]===a)return c}return-1},merge:function(a,c){var d=c.length,e=a.length,f=0;if(typeof d=="number")for(;f<d;f++)a[e++]=c[f];else while(c[f]!==b)a[e++]=c[f++];return a.length=e,a},grep:function(a,b,c){var d,e=[],f=0,g=a.length;c=!!c;for(;f<g;f++)d=!!b(a[f],f),c!==d&&e.push(a[f]);return e},map:function(a,c,d){var e,f,g=[],h=0,i=a.length,j=a instanceof p||i!==b&&typeof i=="number"&&(i>0&&a[0]&&a[i-1]||i===0||p.isArray(a));if(j)for(;h<i;h++)e=c(a[h],h,d),e!=null&&(g[g.length]=e);else for(f in a)e=c(a[f],f,d),e!=null&&(g[g.length]=e);return g.concat.apply([],g)},guid:1,proxy:function(a,c){var d,e,f;return typeof c=="string"&&(d=a[c],c=a,a=d),p.isFunction(a)?(e=k.call(arguments,2),f=function(){return a.apply(c,e.concat(k.call(arguments)))},f.guid=a.guid=a.guid||p.guid++,f):b},access:function(a,c,d,e,f,g,h){var i,j=d==null,k=0,l=a.length;if(d&&typeof d=="object"){for(k in d)p.access(a,c,k,d[k],1,g,e);f=1}else if(e!==b){i=h===b&&p.isFunction(e),j&&(i?(i=c,c=function(a,b,c){return i.call(p(a),c)}):(c.call(a,e),c=null));if(c)for(;k<l;k++)c(a[k],d,i?e.call(a[k],k,c(a[k],d)):e,h);f=1}return f?a:j?c.call(a):l?c(a[0],d):g},now:function(){return(new Date).getTime()}}),p.ready.promise=function(b){if(!d){d=p.Deferred();if(e.readyState==="complete")setTimeout(p.ready,1);else if(e.addEventListener)e.addEventListener("DOMContentLoaded",D,!1),a.addEventListener("load",p.ready,!1);else{e.attachEvent("onreadystatechange",D),a.attachEvent("onload",p.ready);var c=!1;try{c=a.frameElement==null&&e.documentElement}catch(f){}c&&c.doScroll&&function g(){if(!p.isReady){try{c.doScroll("left")}catch(a){return setTimeout(g,50)}p.ready()}}()}}return d.promise(b)},p.each("Boolean Number String Function Array Date RegExp Object".split(" "),function(a,b){E["[object "+b+"]"]=b.toLowerCase()}),c=p(e);var F={};p.Callbacks=function(a){a=typeof a=="string"?F[a]||G(a):p.extend({},a);var c,d,e,f,g,h,i=[],j=!a.once&&[],k=function(b){c=a.memory&&b,d=!0,h=f||0,f=0,g=i.length,e=!0;for(;i&&h<g;h++)if(i[h].apply(b[0],b[1])===!1&&a.stopOnFalse){c=!1;break}e=!1,i&&(j?j.length&&k(j.shift()):c?i=[]:l.disable())},l={add:function(){if(i){var b=i.length;(function d(b){p.each(b,function(b,c){var e=p.type(c);e==="function"&&(!a.unique||!l.has(c))?i.push(c):c&&c.length&&e!=="string"&&d(c)})})(arguments),e?g=i.length:c&&(f=b,k(c))}return this},remove:function(){return i&&p.each(arguments,function(a,b){var c;while((c=p.inArray(b,i,c))>-1)i.splice(c,1),e&&(c<=g&&g--,c<=h&&h--)}),this},has:function(a){return p.inArray(a,i)>-1},empty:function(){return i=[],this},disable:function(){return i=j=c=b,this},disabled:function(){return!i},lock:function(){return j=b,c||l.disable(),this},locked:function(){return!j},fireWith:function(a,b){return b=b||[],b=[a,b.slice?b.slice():b],i&&(!d||j)&&(e?j.push(b):k(b)),this},fire:function(){return l.fireWith(this,arguments),this},fired:function(){return!!d}};return l},p.extend({Deferred:function(a){var b=[["resolve","done",p.Callbacks("once memory"),"resolved"],["reject","fail",p.Callbacks("once memory"),"rejected"],["notify","progress",p.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return p.Deferred(function(c){p.each(b,function(b,d){var f=d[0],g=a[b];e[d[1]](p.isFunction(g)?function(){var a=g.apply(this,arguments);a&&p.isFunction(a.promise)?a.promise().done(c.resolve).fail(c.reject).progress(c.notify):c[f+"With"](this===e?c:this,[a])}:c[f])}),a=null}).promise()},promise:function(a){return a!=null?p.extend(a,d):d}},e={};return d.pipe=d.then,p.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[a^1][2].disable,b[2][2].lock),e[f[0]]=g.fire,e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b=0,c=k.call(arguments),d=c.length,e=d!==1||a&&p.isFunction(a.promise)?d:0,f=e===1?a:p.Deferred(),g=function(a,b,c){return function(d){b[a]=this,c[a]=arguments.length>1?k.call(arguments):d,c===h?f.notifyWith(b,c):--e||f.resolveWith(b,c)}},h,i,j;if(d>1){h=new Array(d),i=new Array(d),j=new Array(d);for(;b<d;b++)c[b]&&p.isFunction(c[b].promise)?c[b].promise().done(g(b,j,c)).fail(f.reject).progress(g(b,i,h)):--e}return e||f.resolveWith(j,c),f.promise()}}),p.support=function(){var b,c,d,f,g,h,i,j,k,l,m,n=e.createElement("div");n.setAttribute("className","t"),n.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",c=n.getElementsByTagName("*"),d=n.getElementsByTagName("a")[0],d.style.cssText="top:1px;float:left;opacity:.5";if(!c||!c.length)return{};f=e.createElement("select"),g=f.appendChild(e.createElement("option")),h=n.getElementsByTagName("input")[0],b={leadingWhitespace:n.firstChild.nodeType===3,tbody:!n.getElementsByTagName("tbody").length,htmlSerialize:!!n.getElementsByTagName("link").length,style:/top/.test(d.getAttribute("style")),hrefNormalized:d.getAttribute("href")==="/a",opacity:/^0.5/.test(d.style.opacity),cssFloat:!!d.style.cssFloat,checkOn:h.value==="on",optSelected:g.selected,getSetAttribute:n.className!=="t",enctype:!!e.createElement("form").enctype,html5Clone:e.createElement("nav").cloneNode(!0).outerHTML!=="<:nav></:nav>",boxModel:e.compatMode==="CSS1Compat",submitBubbles:!0,changeBubbles:!0,focusinBubbles:!1,deleteExpando:!0,noCloneEvent:!0,inlineBlockNeedsLayout:!1,shrinkWrapBlocks:!1,reliableMarginRight:!0,boxSizingReliable:!0,pixelPosition:!1},h.checked=!0,b.noCloneChecked=h.cloneNode(!0).checked,f.disabled=!0,b.optDisabled=!g.disabled;try{delete n.test}catch(o){b.deleteExpando=!1}!n.addEventListener&&n.attachEvent&&n.fireEvent&&(n.attachEvent("onclick",m=function(){b.noCloneEvent=!1}),n.cloneNode(!0).fireEvent("onclick"),n.detachEvent("onclick",m)),h=e.createElement("input"),h.value="t",h.setAttribute("type","radio"),b.radioValue=h.value==="t",h.setAttribute("checked","checked"),h.setAttribute("name","t"),n.appendChild(h),i=e.createDocumentFragment(),i.appendChild(n.lastChild),b.checkClone=i.cloneNode(!0).cloneNode(!0).lastChild.checked,b.appendChecked=h.checked,i.removeChild(h),i.appendChild(n);if(n.attachEvent)for(k in{submit:!0,change:!0,focusin:!0})j="on"+k,l=j in n,l||(n.setAttribute(j,"return;"),l=typeof n[j]=="function"),b[k+"Bubbles"]=l;return p(function(){var c,d,f,g,h="padding:0;margin:0;border:0;display:block;overflow:hidden;",i=e.getElementsByTagName("body")[0];if(!i)return;c=e.createElement("div"),c.style.cssText="visibility:hidden;border:0;width:0;height:0;position:static;top:0;margin-top:1px",i.insertBefore(c,i.firstChild),d=e.createElement("div"),c.appendChild(d),d.innerHTML="<table><tr><td></td><td>t</td></tr></table>",f=d.getElementsByTagName("td"),f[0].style.cssText="padding:0;margin:0;border:0;display:none",l=f[0].offsetHeight===0,f[0].style.display="",f[1].style.display="none",b.reliableHiddenOffsets=l&&f[0].offsetHeight===0,d.innerHTML="",d.style.cssText="box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;",b.boxSizing=d.offsetWidth===4,b.doesNotIncludeMarginInBodyOffset=i.offsetTop!==1,a.getComputedStyle&&(b.pixelPosition=(a.getComputedStyle(d,null)||{}).top!=="1%",b.boxSizingReliable=(a.getComputedStyle(d,null)||{width:"4px"}).width==="4px",g=e.createElement("div"),g.style.cssText=d.style.cssText=h,g.style.marginRight=g.style.width="0",d.style.width="1px",d.appendChild(g),b.reliableMarginRight=!parseFloat((a.getComputedStyle(g,null)||{}).marginRight)),typeof d.style.zoom!="undefined"&&(d.innerHTML="",d.style.cssText=h+"width:1px;padding:1px;display:inline;zoom:1",b.inlineBlockNeedsLayout=d.offsetWidth===3,d.style.display="block",d.style.overflow="visible",d.innerHTML="<div></div>",d.firstChild.style.width="5px",b.shrinkWrapBlocks=d.offsetWidth!==3,c.style.zoom=1),i.removeChild(c),c=d=f=g=null}),i.removeChild(n),c=d=f=g=h=i=n=null,b}();var H=/(?:\{[\s\S]*\}|\[[\s\S]*\])$/,I=/([A-Z])/g;p.extend({cache:{},deletedIds:[],uuid:0,expando:"jQuery"+(p.fn.jquery+Math.random()).replace(/\D/g,""),noData:{embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",applet:!0},hasData:function(a){return a=a.nodeType?p.cache[a[p.expando]]:a[p.expando],!!a&&!K(a)},data:function(a,c,d,e){if(!p.acceptData(a))return;var f,g,h=p.expando,i=typeof c=="string",j=a.nodeType,k=j?p.cache:a,l=j?a[h]:a[h]&&h;if((!l||!k[l]||!e&&!k[l].data)&&i&&d===b)return;l||(j?a[h]=l=p.deletedIds.pop()||p.guid++:l=h),k[l]||(k[l]={},j||(k[l].toJSON=p.noop));if(typeof c=="object"||typeof c=="function")e?k[l]=p.extend(k[l],c):k[l].data=p.extend(k[l].data,c);return f=k[l],e||(f.data||(f.data={}),f=f.data),d!==b&&(f[p.camelCase(c)]=d),i?(g=f[c],g==null&&(g=f[p.camelCase(c)])):g=f,g},removeData:function(a,b,c){if(!p.acceptData(a))return;var d,e,f,g=a.nodeType,h=g?p.cache:a,i=g?a[p.expando]:p.expando;if(!h[i])return;if(b){d=c?h[i]:h[i].data;if(d){p.isArray(b)||(b in d?b=[b]:(b=p.camelCase(b),b in d?b=[b]:b=b.split(" ")));for(e=0,f=b.length;e<f;e++)delete d[b[e]];if(!(c?K:p.isEmptyObject)(d))return}}if(!c){delete h[i].data;if(!K(h[i]))return}g?p.cleanData([a],!0):p.support.deleteExpando||h!=h.window?delete h[i]:h[i]=null},_data:function(a,b,c){return p.data(a,b,c,!0)},acceptData:function(a){var b=a.nodeName&&p.noData[a.nodeName.toLowerCase()];return!b||b!==!0&&a.getAttribute("classid")===b}}),p.fn.extend({data:function(a,c){var d,e,f,g,h,i=this[0],j=0,k=null;if(a===b){if(this.length){k=p.data(i);if(i.nodeType===1&&!p._data(i,"parsedAttrs")){f=i.attributes;for(h=f.length;j<h;j++)g=f[j].name,g.indexOf("data-")||(g=p.camelCase(g.substring(5)),J(i,g,k[g]));p._data(i,"parsedAttrs",!0)}}return k}return typeof a=="object"?this.each(function(){p.data(this,a)}):(d=a.split(".",2),d[1]=d[1]?"."+d[1]:"",e=d[1]+"!",p.access(this,function(c){if(c===b)return k=this.triggerHandler("getData"+e,[d[0]]),k===b&&i&&(k=p.data(i,a),k=J(i,a,k)),k===b&&d[1]?this.data(d[0]):k;d[1]=c,this.each(function(){var b=p(this);b.triggerHandler("setData"+e,d),p.data(this,a,c),b.triggerHandler("changeData"+e,d)})},null,c,arguments.length>1,null,!1))},removeData:function(a){return this.each(function(){p.removeData(this,a)})}}),p.extend({queue:function(a,b,c){var d;if(a)return b=(b||"fx")+"queue",d=p._data(a,b),c&&(!d||p.isArray(c)?d=p._data(a,b,p.makeArray(c)):d.push(c)),d||[]},dequeue:function(a,b){b=b||"fx";var c=p.queue(a,b),d=c.length,e=c.shift(),f=p._queueHooks(a,b),g=function(){p.dequeue(a,b)};e==="inprogress"&&(e=c.shift(),d--),e&&(b==="fx"&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return p._data(a,c)||p._data(a,c,{empty:p.Callbacks("once memory").add(function(){p.removeData(a,b+"queue",!0),p.removeData(a,c,!0)})})}}),p.fn.extend({queue:function(a,c){var d=2;return typeof a!="string"&&(c=a,a="fx",d--),arguments.length<d?p.queue(this[0],a):c===b?this:this.each(function(){var b=p.queue(this,a,c);p._queueHooks(this,a),a==="fx"&&b[0]!=="inprogress"&&p.dequeue(this,a)})},dequeue:function(a){return this.each(function(){p.dequeue(this,a)})},delay:function(a,b){return a=p.fx?p.fx.speeds[a]||a:a,b=b||"fx",this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,c){var d,e=1,f=p.Deferred(),g=this,h=this.length,i=function(){--e||f.resolveWith(g,[g])};typeof a!="string"&&(c=a,a=b),a=a||"fx";while(h--)d=p._data(g[h],a+"queueHooks"),d&&d.empty&&(e++,d.empty.add(i));return i(),f.promise(c)}});var L,M,N,O=/[\t\r\n]/g,P=/\r/g,Q=/^(?:button|input)$/i,R=/^(?:button|input|object|select|textarea)$/i,S=/^a(?:rea|)$/i,T=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,U=p.support.getSetAttribute;p.fn.extend({attr:function(a,b){return p.access(this,p.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){p.removeAttr(this,a)})},prop:function(a,b){return p.access(this,p.prop,a,b,arguments.length>1)},removeProp:function(a){return a=p.propFix[a]||a,this.each(function(){try{this[a]=b,delete this[a]}catch(c){}})},addClass:function(a){var b,c,d,e,f,g,h;if(p.isFunction(a))return this.each(function(b){p(this).addClass(a.call(this,b,this.className))});if(a&&typeof a=="string"){b=a.split(s);for(c=0,d=this.length;c<d;c++){e=this[c];if(e.nodeType===1)if(!e.className&&b.length===1)e.className=a;else{f=" "+e.className+" ";for(g=0,h=b.length;g<h;g++)f.indexOf(" "+b[g]+" ")<0&&(f+=b[g]+" ");e.className=p.trim(f)}}}return this},removeClass:function(a){var c,d,e,f,g,h,i;if(p.isFunction(a))return this.each(function(b){p(this).removeClass(a.call(this,b,this.className))});if(a&&typeof a=="string"||a===b){c=(a||"").split(s);for(h=0,i=this.length;h<i;h++){e=this[h];if(e.nodeType===1&&e.className){d=(" "+e.className+" ").replace(O," ");for(f=0,g=c.length;f<g;f++)while(d.indexOf(" "+c[f]+" ")>=0)d=d.replace(" "+c[f]+" "," ");e.className=a?p.trim(d):""}}}return this},toggleClass:function(a,b){var c=typeof a,d=typeof b=="boolean";return p.isFunction(a)?this.each(function(c){p(this).toggleClass(a.call(this,c,this.className,b),b)}):this.each(function(){if(c==="string"){var e,f=0,g=p(this),h=b,i=a.split(s);while(e=i[f++])h=d?h:!g.hasClass(e),g[h?"addClass":"removeClass"](e)}else if(c==="undefined"||c==="boolean")this.className&&p._data(this,"__className__",this.className),this.className=this.className||a===!1?"":p._data(this,"__className__")||""})},hasClass:function(a){var b=" "+a+" ",c=0,d=this.length;for(;c<d;c++)if(this[c].nodeType===1&&(" "+this[c].className+" ").replace(O," ").indexOf(b)>=0)return!0;return!1},val:function(a){var c,d,e,f=this[0];if(!arguments.length){if(f)return c=p.valHooks[f.type]||p.valHooks[f.nodeName.toLowerCase()],c&&"get"in c&&(d=c.get(f,"value"))!==b?d:(d=f.value,typeof d=="string"?d.replace(P,""):d==null?"":d);return}return e=p.isFunction(a),this.each(function(d){var f,g=p(this);if(this.nodeType!==1)return;e?f=a.call(this,d,g.val()):f=a,f==null?f="":typeof f=="number"?f+="":p.isArray(f)&&(f=p.map(f,function(a){return a==null?"":a+""})),c=p.valHooks[this.type]||p.valHooks[this.nodeName.toLowerCase()];if(!c||!("set"in c)||c.set(this,f,"value")===b)this.value=f})}}),p.extend({valHooks:{option:{get:function(a){var b=a.attributes.value;return!b||b.specified?a.value:a.text}},select:{get:function(a){var b,c,d,e,f=a.selectedIndex,g=[],h=a.options,i=a.type==="select-one";if(f<0)return null;c=i?f:0,d=i?f+1:h.length;for(;c<d;c++){e=h[c];if(e.selected&&(p.support.optDisabled?!e.disabled:e.getAttribute("disabled")===null)&&(!e.parentNode.disabled||!p.nodeName(e.parentNode,"optgroup"))){b=p(e).val();if(i)return b;g.push(b)}}return i&&!g.length&&h.length?p(h[f]).val():g},set:function(a,b){var c=p.makeArray(b);return p(a).find("option").each(function(){this.selected=p.inArray(p(this).val(),c)>=0}),c.length||(a.selectedIndex=-1),c}}},attrFn:{},attr:function(a,c,d,e){var f,g,h,i=a.nodeType;if(!a||i===3||i===8||i===2)return;if(e&&p.isFunction(p.fn[c]))return p(a)[c](d);if(typeof a.getAttribute=="undefined")return p.prop(a,c,d);h=i!==1||!p.isXMLDoc(a),h&&(c=c.toLowerCase(),g=p.attrHooks[c]||(T.test(c)?M:L));if(d!==b){if(d===null){p.removeAttr(a,c);return}return g&&"set"in g&&h&&(f=g.set(a,d,c))!==b?f:(a.setAttribute(c,d+""),d)}return g&&"get"in g&&h&&(f=g.get(a,c))!==null?f:(f=a.getAttribute(c),f===null?b:f)},removeAttr:function(a,b){var c,d,e,f,g=0;if(b&&a.nodeType===1){d=b.split(s);for(;g<d.length;g++)e=d[g],e&&(c=p.propFix[e]||e,f=T.test(e),f||p.attr(a,e,""),a.removeAttribute(U?e:c),f&&c in a&&(a[c]=!1))}},attrHooks:{type:{set:function(a,b){if(Q.test(a.nodeName)&&a.parentNode)p.error("type property can't be changed");else if(!p.support.radioValue&&b==="radio"&&p.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}},value:{get:function(a,b){return L&&p.nodeName(a,"button")?L.get(a,b):b in a?a.value:null},set:function(a,b,c){if(L&&p.nodeName(a,"button"))return L.set(a,b,c);a.value=b}}},propFix:{tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},prop:function(a,c,d){var e,f,g,h=a.nodeType;if(!a||h===3||h===8||h===2)return;return g=h!==1||!p.isXMLDoc(a),g&&(c=p.propFix[c]||c,f=p.propHooks[c]),d!==b?f&&"set"in f&&(e=f.set(a,d,c))!==b?e:a[c]=d:f&&"get"in f&&(e=f.get(a,c))!==null?e:a[c]},propHooks:{tabIndex:{get:function(a){var c=a.getAttributeNode("tabindex");return c&&c.specified?parseInt(c.value,10):R.test(a.nodeName)||S.test(a.nodeName)&&a.href?0:b}}}}),M={get:function(a,c){var d,e=p.prop(a,c);return e===!0||typeof e!="boolean"&&(d=a.getAttributeNode(c))&&d.nodeValue!==!1?c.toLowerCase():b},set:function(a,b,c){var d;return b===!1?p.removeAttr(a,c):(d=p.propFix[c]||c,d in a&&(a[d]=!0),a.setAttribute(c,c.toLowerCase())),c}},U||(N={name:!0,id:!0,coords:!0},L=p.valHooks.button={get:function(a,c){var d;return d=a.getAttributeNode(c),d&&(N[c]?d.value!=="":d.specified)?d.value:b},set:function(a,b,c){var d=a.getAttributeNode(c);return d||(d=e.createAttribute(c),a.setAttributeNode(d)),d.value=b+""}},p.each(["width","height"],function(a,b){p.attrHooks[b]=p.extend(p.attrHooks[b],{set:function(a,c){if(c==="")return a.setAttribute(b,"auto"),c}})}),p.attrHooks.contenteditable={get:L.get,set:function(a,b,c){b===""&&(b="false"),L.set(a,b,c)}}),p.support.hrefNormalized||p.each(["href","src","width","height"],function(a,c){p.attrHooks[c]=p.extend(p.attrHooks[c],{get:function(a){var d=a.getAttribute(c,2);return d===null?b:d}})}),p.support.style||(p.attrHooks.style={get:function(a){return a.style.cssText.toLowerCase()||b},set:function(a,b){return a.style.cssText=b+""}}),p.support.optSelected||(p.propHooks.selected=p.extend(p.propHooks.selected,{get:function(a){var b=a.parentNode;return b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex),null}})),p.support.enctype||(p.propFix.enctype="encoding"),p.support.checkOn||p.each(["radio","checkbox"],function(){p.valHooks[this]={get:function(a){return a.getAttribute("value")===null?"on":a.value}}}),p.each(["radio","checkbox"],function(){p.valHooks[this]=p.extend(p.valHooks[this],{set:function(a,b){if(p.isArray(b))return a.checked=p.inArray(p(a).val(),b)>=0}})});var V=/^(?:textarea|input|select)$/i,W=/^([^\.]*|)(?:\.(.+)|)$/,X=/(?:^|\s)hover(\.\S+|)\b/,Y=/^key/,Z=/^(?:mouse|contextmenu)|click/,$=/^(?:focusinfocus|focusoutblur)$/,_=function(a){return p.event.special.hover?a:a.replace(X,"mouseenter$1 mouseleave$1")};p.event={add:function(a,c,d,e,f){var g,h,i,j,k,l,m,n,o,q,r;if(a.nodeType===3||a.nodeType===8||!c||!d||!(g=p._data(a)))return;d.handler&&(o=d,d=o.handler,f=o.selector),d.guid||(d.guid=p.guid++),i=g.events,i||(g.events=i={}),h=g.handle,h||(g.handle=h=function(a){return typeof p!="undefined"&&(!a||p.event.triggered!==a.type)?p.event.dispatch.apply(h.elem,arguments):b},h.elem=a),c=p.trim(_(c)).split(" ");for(j=0;j<c.length;j++){k=W.exec(c[j])||[],l=k[1],m=(k[2]||"").split(".").sort(),r=p.event.special[l]||{},l=(f?r.delegateType:r.bindType)||l,r=p.event.special[l]||{},n=p.extend({type:l,origType:k[1],data:e,handler:d,guid:d.guid,selector:f,needsContext:f&&p.expr.match.needsContext.test(f),namespace:m.join(".")},o),q=i[l];if(!q){q=i[l]=[],q.delegateCount=0;if(!r.setup||r.setup.call(a,e,m,h)===!1)a.addEventListener?a.addEventListener(l,h,!1):a.attachEvent&&a.attachEvent("on"+l,h)}r.add&&(r.add.call(a,n),n.handler.guid||(n.handler.guid=d.guid)),f?q.splice(q.delegateCount++,0,n):q.push(n),p.event.global[l]=!0}a=null},global:{},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,n,o,q,r=p.hasData(a)&&p._data(a);if(!r||!(m=r.events))return;b=p.trim(_(b||"")).split(" ");for(f=0;f<b.length;f++){g=W.exec(b[f])||[],h=i=g[1],j=g[2];if(!h){for(h in m)p.event.remove(a,h+b[f],c,d,!0);continue}n=p.event.special[h]||{},h=(d?n.delegateType:n.bindType)||h,o=m[h]||[],k=o.length,j=j?new RegExp("(^|\\.)"+j.split(".").sort().join("\\.(?:.*\\.|)")+"(\\.|$)"):null;for(l=0;l<o.length;l++)q=o[l],(e||i===q.origType)&&(!c||c.guid===q.guid)&&(!j||j.test(q.namespace))&&(!d||d===q.selector||d==="**"&&q.selector)&&(o.splice(l--,1),q.selector&&o.delegateCount--,n.remove&&n.remove.call(a,q));o.length===0&&k!==o.length&&((!n.teardown||n.teardown.call(a,j,r.handle)===!1)&&p.removeEvent(a,h,r.handle),delete m[h])}p.isEmptyObject(m)&&(delete r.handle,p.removeData(a,"events",!0))},customEvent:{getData:!0,setData:!0,changeData:!0},trigger:function(c,d,f,g){if(!f||f.nodeType!==3&&f.nodeType!==8){var h,i,j,k,l,m,n,o,q,r,s=c.type||c,t=[];if($.test(s+p.event.triggered))return;s.indexOf("!")>=0&&(s=s.slice(0,-1),i=!0),s.indexOf(".")>=0&&(t=s.split("."),s=t.shift(),t.sort());if((!f||p.event.customEvent[s])&&!p.event.global[s])return;c=typeof c=="object"?c[p.expando]?c:new p.Event(s,c):new p.Event(s),c.type=s,c.isTrigger=!0,c.exclusive=i,c.namespace=t.join("."),c.namespace_re=c.namespace?new RegExp("(^|\\.)"+t.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,m=s.indexOf(":")<0?"on"+s:"";if(!f){h=p.cache;for(j in h)h[j].events&&h[j].events[s]&&p.event.trigger(c,d,h[j].handle.elem,!0);return}c.result=b,c.target||(c.target=f),d=d!=null?p.makeArray(d):[],d.unshift(c),n=p.event.special[s]||{};if(n.trigger&&n.trigger.apply(f,d)===!1)return;q=[[f,n.bindType||s]];if(!g&&!n.noBubble&&!p.isWindow(f)){r=n.delegateType||s,k=$.test(r+s)?f:f.parentNode;for(l=f;k;k=k.parentNode)q.push([k,r]),l=k;l===(f.ownerDocument||e)&&q.push([l.defaultView||l.parentWindow||a,r])}for(j=0;j<q.length&&!c.isPropagationStopped();j++)k=q[j][0],c.type=q[j][1],o=(p._data(k,"events")||{})[c.type]&&p._data(k,"handle"),o&&o.apply(k,d),o=m&&k[m],o&&p.acceptData(k)&&o.apply&&o.apply(k,d)===!1&&c.preventDefault();return c.type=s,!g&&!c.isDefaultPrevented()&&(!n._default||n._default.apply(f.ownerDocument,d)===!1)&&(s!=="click"||!p.nodeName(f,"a"))&&p.acceptData(f)&&m&&f[s]&&(s!=="focus"&&s!=="blur"||c.target.offsetWidth!==0)&&!p.isWindow(f)&&(l=f[m],l&&(f[m]=null),p.event.triggered=s,f[s](),p.event.triggered=b,l&&(f[m]=l)),c.result}return},dispatch:function(c){c=p.event.fix(c||a.event);var d,e,f,g,h,i,j,l,m,n,o=(p._data(this,"events")||{})[c.type]||[],q=o.delegateCount,r=k.call(arguments),s=!c.exclusive&&!c.namespace,t=p.event.special[c.type]||{},u=[];r[0]=c,c.delegateTarget=this;if(t.preDispatch&&t.preDispatch.call(this,c)===!1)return;if(q&&(!c.button||c.type!=="click"))for(f=c.target;f!=this;f=f.parentNode||this)if(f.disabled!==!0||c.type!=="click"){h={},j=[];for(d=0;d<q;d++)l=o[d],m=l.selector,h[m]===b&&(h[m]=l.needsContext?p(m,this).index(f)>=0:p.find(m,this,null,[f]).length),h[m]&&j.push(l);j.length&&u.push({elem:f,matches:j})}o.length>q&&u.push({elem:this,matches:o.slice(q)});for(d=0;d<u.length&&!c.isPropagationStopped();d++){i=u[d],c.currentTarget=i.elem;for(e=0;e<i.matches.length&&!c.isImmediatePropagationStopped();e++){l=i.matches[e];if(s||!c.namespace&&!l.namespace||c.namespace_re&&c.namespace_re.test(l.namespace))c.data=l.data,c.handleObj=l,g=((p.event.special[l.origType]||{}).handle||l.handler).apply(i.elem,r),g!==b&&(c.result=g,g===!1&&(c.preventDefault(),c.stopPropagation()))}}return t.postDispatch&&t.postDispatch.call(this,c),c.result},props:"attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return a.which==null&&(a.which=b.charCode!=null?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,c){var d,f,g,h=c.button,i=c.fromElement;return a.pageX==null&&c.clientX!=null&&(d=a.target.ownerDocument||e,f=d.documentElement,g=d.body,a.pageX=c.clientX+(f&&f.scrollLeft||g&&g.scrollLeft||0)-(f&&f.clientLeft||g&&g.clientLeft||0),a.pageY=c.clientY+(f&&f.scrollTop||g&&g.scrollTop||0)-(f&&f.clientTop||g&&g.clientTop||0)),!a.relatedTarget&&i&&(a.relatedTarget=i===a.target?c.toElement:i),!a.which&&h!==b&&(a.which=h&1?1:h&2?3:h&4?2:0),a}},fix:function(a){if(a[p.expando])return a;var b,c,d=a,f=p.event.fixHooks[a.type]||{},g=f.props?this.props.concat(f.props):this.props;a=p.Event(d);for(b=g.length;b;)c=g[--b],a[c]=d[c];return a.target||(a.target=d.srcElement||e),a.target.nodeType===3&&(a.target=a.target.parentNode),a.metaKey=!!a.metaKey,f.filter?f.filter(a,d):a},special:{load:{noBubble:!0},focus:{delegateType:"focusin"},blur:{delegateType:"focusout"},beforeunload:{setup:function(a,b,c){p.isWindow(this)&&(this.onbeforeunload=c)},teardown:function(a,b){this.onbeforeunload===b&&(this.onbeforeunload=null)}}},simulate:function(a,b,c,d){var e=p.extend(new p.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?p.event.trigger(e,null,b):p.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},p.event.handle=p.event.dispatch,p.removeEvent=e.removeEventListener?function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)}:function(a,b,c){var d="on"+b;a.detachEvent&&(typeof a[d]=="undefined"&&(a[d]=null),a.detachEvent(d,c))},p.Event=function(a,b){if(this instanceof p.Event)a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||a.returnValue===!1||a.getPreventDefault&&a.getPreventDefault()?bb:ba):this.type=a,b&&p.extend(this,b),this.timeStamp=a&&a.timeStamp||p.now(),this[p.expando]=!0;else return new p.Event(a,b)},p.Event.prototype={preventDefault:function(){this.isDefaultPrevented=bb;var a=this.originalEvent;if(!a)return;a.preventDefault?a.preventDefault():a.returnValue=!1},stopPropagation:function(){this.isPropagationStopped=bb;var a=this.originalEvent;if(!a)return;a.stopPropagation&&a.stopPropagation(),a.cancelBubble=!0},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=bb,this.stopPropagation()},isDefaultPrevented:ba,isPropagationStopped:ba,isImmediatePropagationStopped:ba},p.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(a,b){p.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj,g=f.selector;if(!e||e!==d&&!p.contains(d,e))a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b;return c}}}),p.support.submitBubbles||(p.event.special.submit={setup:function(){if(p.nodeName(this,"form"))return!1;p.event.add(this,"click._submit keypress._submit",function(a){var c=a.target,d=p.nodeName(c,"input")||p.nodeName(c,"button")?c.form:b;d&&!p._data(d,"_submit_attached")&&(p.event.add(d,"submit._submit",function(a){a._submit_bubble=!0}),p._data(d,"_submit_attached",!0))})},postDispatch:function(a){a._submit_bubble&&(delete a._submit_bubble,this.parentNode&&!a.isTrigger&&p.event.simulate("submit",this.parentNode,a,!0))},teardown:function(){if(p.nodeName(this,"form"))return!1;p.event.remove(this,"._submit")}}),p.support.changeBubbles||(p.event.special.change={setup:function(){if(V.test(this.nodeName)){if(this.type==="checkbox"||this.type==="radio")p.event.add(this,"propertychange._change",function(a){a.originalEvent.propertyName==="checked"&&(this._just_changed=!0)}),p.event.add(this,"click._change",function(a){this._just_changed&&!a.isTrigger&&(this._just_changed=!1),p.event.simulate("change",this,a,!0)});return!1}p.event.add(this,"beforeactivate._change",function(a){var b=a.target;V.test(b.nodeName)&&!p._data(b,"_change_attached")&&(p.event.add(b,"change._change",function(a){this.parentNode&&!a.isSimulated&&!a.isTrigger&&p.event.simulate("change",this.parentNode,a,!0)}),p._data(b,"_change_attached",!0))})},handle:function(a){var b=a.target;if(this!==b||a.isSimulated||a.isTrigger||b.type!=="radio"&&b.type!=="checkbox")return a.handleObj.handler.apply(this,arguments)},teardown:function(){return p.event.remove(this,"._change"),!V.test(this.nodeName)}}),p.support.focusinBubbles||p.each({focus:"focusin",blur:"focusout"},function(a,b){var c=0,d=function(a){p.event.simulate(b,a.target,p.event.fix(a),!0)};p.event.special[b]={setup:function(){c++===0&&e.addEventListener(a,d,!0)},teardown:function(){--c===0&&e.removeEventListener(a,d,!0)}}}),p.fn.extend({on:function(a,c,d,e,f){var g,h;if(typeof a=="object"){typeof c!="string"&&(d=d||c,c=b);for(h in a)this.on(h,c,d,a[h],f);return this}d==null&&e==null?(e=c,d=c=b):e==null&&(typeof c=="string"?(e=d,d=b):(e=d,d=c,c=b));if(e===!1)e=ba;else if(!e)return this;return f===1&&(g=e,e=function(a){return p().off(a),g.apply(this,arguments)},e.guid=g.guid||(g.guid=p.guid++)),this.each(function(){p.event.add(this,a,e,d,c)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,c,d){var e,f;if(a&&a.preventDefault&&a.handleObj)return e=a.handleObj,p(a.delegateTarget).off(e.namespace?e.origType+"."+e.namespace:e.origType,e.selector,e.handler),this;if(typeof a=="object"){for(f in a)this.off(f,c,a[f]);return this}if(c===!1||typeof c=="function")d=c,c=b;return d===!1&&(d=ba),this.each(function(){p.event.remove(this,a,d,c)})},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},live:function(a,b,c){return p(this.context).on(a,this.selector,b,c),this},die:function(a,b){return p(this.context).off(a,this.selector||"**",b),this},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return arguments.length===1?this.off(a,"**"):this.off(b,a||"**",c)},trigger:function(a,b){return this.each(function(){p.event.trigger(a,b,this)})},triggerHandler:function(a,b){if(this[0])return p.event.trigger(a,b,this[0],!0)},toggle:function(a){var b=arguments,c=a.guid||p.guid++,d=0,e=function(c){var e=(p._data(this,"lastToggle"+a.guid)||0)%d;return p._data(this,"lastToggle"+a.guid,e+1),c.preventDefault(),b[e].apply(this,arguments)||!1};e.guid=c;while(d<b.length)b[d++].guid=c;return this.click(e)},hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)}}),p.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){p.fn[b]=function(a,c){return c==null&&(c=a,a=null),arguments.length>0?this.on(b,null,a,c):this.trigger(b)},Y.test(b)&&(p.event.fixHooks[b]=p.event.keyHooks),Z.test(b)&&(p.event.fixHooks[b]=p.event.mouseHooks)}),function(a,b){function bc(a,b,c,d){c=c||[],b=b||r;var e,f,i,j,k=b.nodeType;if(!a||typeof a!="string")return c;if(k!==1&&k!==9)return[];i=g(b);if(!i&&!d)if(e=P.exec(a))if(j=e[1]){if(k===9){f=b.getElementById(j);if(!f||!f.parentNode)return c;if(f.id===j)return c.push(f),c}else if(b.ownerDocument&&(f=b.ownerDocument.getElementById(j))&&h(b,f)&&f.id===j)return c.push(f),c}else{if(e[2])return w.apply(c,x.call(b.getElementsByTagName(a),0)),c;if((j=e[3])&&_&&b.getElementsByClassName)return w.apply(c,x.call(b.getElementsByClassName(j),0)),c}return bp(a.replace(L,"$1"),b,c,d,i)}function bd(a){return function(b){var c=b.nodeName.toLowerCase();return c==="input"&&b.type===a}}function be(a){return function(b){var c=b.nodeName.toLowerCase();return(c==="input"||c==="button")&&b.type===a}}function bf(a){return z(function(b){return b=+b,z(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function bg(a,b,c){if(a===b)return c;var d=a.nextSibling;while(d){if(d===b)return-1;d=d.nextSibling}return 1}function bh(a,b){var c,d,f,g,h,i,j,k=C[o][a];if(k)return b?0:k.slice(0);h=a,i=[],j=e.preFilter;while(h){if(!c||(d=M.exec(h)))d&&(h=h.slice(d[0].length)),i.push(f=[]);c=!1;if(d=N.exec(h))f.push(c=new q(d.shift())),h=h.slice(c.length),c.type=d[0].replace(L," ");for(g in e.filter)(d=W[g].exec(h))&&(!j[g]||(d=j[g](d,r,!0)))&&(f.push(c=new q(d.shift())),h=h.slice(c.length),c.type=g,c.matches=d);if(!c)break}return b?h.length:h?bc.error(a):C(a,i).slice(0)}function bi(a,b,d){var e=b.dir,f=d&&b.dir==="parentNode",g=u++;return b.first?function(b,c,d){while(b=b[e])if(f||b.nodeType===1)return a(b,c,d)}:function(b,d,h){if(!h){var i,j=t+" "+g+" ",k=j+c;while(b=b[e])if(f||b.nodeType===1){if((i=b[o])===k)return b.sizset;if(typeof i=="string"&&i.indexOf(j)===0){if(b.sizset)return b}else{b[o]=k;if(a(b,d,h))return b.sizset=!0,b;b.sizset=!1}}}else while(b=b[e])if(f||b.nodeType===1)if(a(b,d,h))return b}}function bj(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function bk(a,b,c,d,e){var f,g=[],h=0,i=a.length,j=b!=null;for(;h<i;h++)if(f=a[h])if(!c||c(f,d,e))g.push(f),j&&b.push(h);return g}function bl(a,b,c,d,e,f){return d&&!d[o]&&(d=bl(d)),e&&!e[o]&&(e=bl(e,f)),z(function(f,g,h,i){if(f&&e)return;var j,k,l,m=[],n=[],o=g.length,p=f||bo(b||"*",h.nodeType?[h]:h,[],f),q=a&&(f||!b)?bk(p,m,a,h,i):p,r=c?e||(f?a:o||d)?[]:g:q;c&&c(q,r,h,i);if(d){l=bk(r,n),d(l,[],h,i),j=l.length;while(j--)if(k=l[j])r[n[j]]=!(q[n[j]]=k)}if(f){j=a&&r.length;while(j--)if(k=r[j])f[m[j]]=!(g[m[j]]=k)}else r=bk(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):w.apply(g,r)})}function bm(a){var b,c,d,f=a.length,g=e.relative[a[0].type],h=g||e.relative[" "],i=g?1:0,j=bi(function(a){return a===b},h,!0),k=bi(function(a){return y.call(b,a)>-1},h,!0),m=[function(a,c,d){return!g&&(d||c!==l)||((b=c).nodeType?j(a,c,d):k(a,c,d))}];for(;i<f;i++)if(c=e.relative[a[i].type])m=[bi(bj(m),c)];else{c=e.filter[a[i].type].apply(null,a[i].matches);if(c[o]){d=++i;for(;d<f;d++)if(e.relative[a[d].type])break;return bl(i>1&&bj(m),i>1&&a.slice(0,i-1).join("").replace(L,"$1"),c,i<d&&bm(a.slice(i,d)),d<f&&bm(a=a.slice(d)),d<f&&a.join(""))}m.push(c)}return bj(m)}function bn(a,b){var d=b.length>0,f=a.length>0,g=function(h,i,j,k,m){var n,o,p,q=[],s=0,u="0",x=h&&[],y=m!=null,z=l,A=h||f&&e.find.TAG("*",m&&i.parentNode||i),B=t+=z==null?1:Math.E;y&&(l=i!==r&&i,c=g.el);for(;(n=A[u])!=null;u++){if(f&&n){for(o=0;p=a[o];o++)if(p(n,i,j)){k.push(n);break}y&&(t=B,c=++g.el)}d&&((n=!p&&n)&&s--,h&&x.push(n))}s+=u;if(d&&u!==s){for(o=0;p=b[o];o++)p(x,q,i,j);if(h){if(s>0)while(u--)!x[u]&&!q[u]&&(q[u]=v.call(k));q=bk(q)}w.apply(k,q),y&&!h&&q.length>0&&s+b.length>1&&bc.uniqueSort(k)}return y&&(t=B,l=z),x};return g.el=0,d?z(g):g}function bo(a,b,c,d){var e=0,f=b.length;for(;e<f;e++)bc(a,b[e],c,d);return c}function bp(a,b,c,d,f){var g,h,j,k,l,m=bh(a),n=m.length;if(!d&&m.length===1){h=m[0]=m[0].slice(0);if(h.length>2&&(j=h[0]).type==="ID"&&b.nodeType===9&&!f&&e.relative[h[1].type]){b=e.find.ID(j.matches[0].replace(V,""),b,f)[0];if(!b)return c;a=a.slice(h.shift().length)}for(g=W.POS.test(a)?-1:h.length-1;g>=0;g--){j=h[g];if(e.relative[k=j.type])break;if(l=e.find[k])if(d=l(j.matches[0].replace(V,""),R.test(h[0].type)&&b.parentNode||b,f)){h.splice(g,1),a=d.length&&h.join("");if(!a)return w.apply(c,x.call(d,0)),c;break}}}return i(a,m)(d,b,f,c,R.test(a)),c}function bq(){}var c,d,e,f,g,h,i,j,k,l,m=!0,n="undefined",o=("sizcache"+Math.random()).replace(".",""),q=String,r=a.document,s=r.documentElement,t=0,u=0,v=[].pop,w=[].push,x=[].slice,y=[].indexOf||function(a){var b=0,c=this.length;for(;b<c;b++)if(this[b]===a)return b;return-1},z=function(a,b){return a[o]=b==null||b,a},A=function(){var a={},b=[];return z(function(c,d){return b.push(c)>e.cacheLength&&delete a[b.shift()],a[c]=d},a)},B=A(),C=A(),D=A(),E="[\\x20\\t\\r\\n\\f]",F="(?:\\\\.|[-\\w]|[^\\x00-\\xa0])+",G=F.replace("w","w#"),H="([*^$|!~]?=)",I="\\["+E+"*("+F+")"+E+"*(?:"+H+E+"*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|("+G+")|)|)"+E+"*\\]",J=":("+F+")(?:\\((?:(['\"])((?:\\\\.|[^\\\\])*?)\\2|([^()[\\]]*|(?:(?:"+I+")|[^:]|\\\\.)*|.*))\\)|)",K=":(even|odd|eq|gt|lt|nth|first|last)(?:\\("+E+"*((?:-\\d)?\\d*)"+E+"*\\)|)(?=[^-]|$)",L=new RegExp("^"+E+"+|((?:^|[^\\\\])(?:\\\\.)*)"+E+"+$","g"),M=new RegExp("^"+E+"*,"+E+"*"),N=new RegExp("^"+E+"*([\\x20\\t\\r\\n\\f>+~])"+E+"*"),O=new RegExp(J),P=/^(?:#([\w\-]+)|(\w+)|\.([\w\-]+))$/,Q=/^:not/,R=/[\x20\t\r\n\f]*[+~]/,S=/:not\($/,T=/h\d/i,U=/input|select|textarea|button/i,V=/\\(?!\\)/g,W={ID:new RegExp("^#("+F+")"),CLASS:new RegExp("^\\.("+F+")"),NAME:new RegExp("^\\[name=['\"]?("+F+")['\"]?\\]"),TAG:new RegExp("^("+F.replace("w","w*")+")"),ATTR:new RegExp("^"+I),PSEUDO:new RegExp("^"+J),POS:new RegExp(K,"i"),CHILD:new RegExp("^:(only|nth|first|last)-child(?:\\("+E+"*(even|odd|(([+-]|)(\\d*)n|)"+E+"*(?:([+-]|)"+E+"*(\\d+)|))"+E+"*\\)|)","i"),needsContext:new RegExp("^"+E+"*[>+~]|"+K,"i")},X=function(a){var b=r.createElement("div");try{return a(b)}catch(c){return!1}finally{b=null}},Y=X(function(a){return a.appendChild(r.createComment("")),!a.getElementsByTagName("*").length}),Z=X(function(a){return a.innerHTML="<a href='#'></a>",a.firstChild&&typeof a.firstChild.getAttribute!==n&&a.firstChild.getAttribute("href")==="#"}),$=X(function(a){a.innerHTML="<select></select>";var b=typeof a.lastChild.getAttribute("multiple");return b!=="boolean"&&b!=="string"}),_=X(function(a){return a.innerHTML="<div class='hidden e'></div><div class='hidden'></div>",!a.getElementsByClassName||!a.getElementsByClassName("e").length?!1:(a.lastChild.className="e",a.getElementsByClassName("e").length===2)}),ba=X(function(a){a.id=o+0,a.innerHTML="<a name='"+o+"'></a><div name='"+o+"'></div>",s.insertBefore(a,s.firstChild);var b=r.getElementsByName&&r.getElementsByName(o).length===2+r.getElementsByName(o+0).length;return d=!r.getElementById(o),s.removeChild(a),b});try{x.call(s.childNodes,0)[0].nodeType}catch(bb){x=function(a){var b,c=[];for(;b=this[a];a++)c.push(b);return c}}bc.matches=function(a,b){return bc(a,null,null,b)},bc.matchesSelector=function(a,b){return bc(b,null,null,[a]).length>0},f=bc.getText=function(a){var b,c="",d=0,e=a.nodeType;if(e){if(e===1||e===9||e===11){if(typeof a.textContent=="string")return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=f(a)}else if(e===3||e===4)return a.nodeValue}else for(;b=a[d];d++)c+=f(b);return c},g=bc.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?b.nodeName!=="HTML":!1},h=bc.contains=s.contains?function(a,b){var c=a.nodeType===9?a.documentElement:a,d=b&&b.parentNode;return a===d||!!(d&&d.nodeType===1&&c.contains&&c.contains(d))}:s.compareDocumentPosition?function(a,b){return b&&!!(a.compareDocumentPosition(b)&16)}:function(a,b){while(b=b.parentNode)if(b===a)return!0;return!1},bc.attr=function(a,b){var c,d=g(a);return d||(b=b.toLowerCase()),(c=e.attrHandle[b])?c(a):d||$?a.getAttribute(b):(c=a.getAttributeNode(b),c?typeof a[b]=="boolean"?a[b]?b:null:c.specified?c.value:null:null)},e=bc.selectors={cacheLength:50,createPseudo:z,match:W,attrHandle:Z?{}:{href:function(a){return a.getAttribute("href",2)},type:function(a){return a.getAttribute("type")}},find:{ID:d?function(a,b,c){if(typeof b.getElementById!==n&&!c){var d=b.getElementById(a);return d&&d.parentNode?[d]:[]}}:function(a,c,d){if(typeof c.getElementById!==n&&!d){var e=c.getElementById(a);return e?e.id===a||typeof e.getAttributeNode!==n&&e.getAttributeNode("id").value===a?[e]:b:[]}},TAG:Y?function(a,b){if(typeof b.getElementsByTagName!==n)return b.getElementsByTagName(a)}:function(a,b){var c=b.getElementsByTagName(a);if(a==="*"){var d,e=[],f=0;for(;d=c[f];f++)d.nodeType===1&&e.push(d);return e}return c},NAME:ba&&function(a,b){if(typeof b.getElementsByName!==n)return b.getElementsByName(name)},CLASS:_&&function(a,b,c){if(typeof b.getElementsByClassName!==n&&!c)return b.getElementsByClassName(a)}},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(V,""),a[3]=(a[4]||a[5]||"").replace(V,""),a[2]==="~="&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),a[1]==="nth"?(a[2]||bc.error(a[0]),a[3]=+(a[3]?a[4]+(a[5]||1):2*(a[2]==="even"||a[2]==="odd")),a[4]=+(a[6]+a[7]||a[2]==="odd")):a[2]&&bc.error(a[0]),a},PSEUDO:function(a){var b,c;if(W.CHILD.test(a[0]))return null;if(a[3])a[2]=a[3];else if(b=a[4])O.test(b)&&(c=bh(b,!0))&&(c=b.indexOf(")",b.length-c)-b.length)&&(b=b.slice(0,c),a[0]=a[0].slice(0,c)),a[2]=b;return a.slice(0,3)}},filter:{ID:d?function(a){return a=a.replace(V,""),function(b){return b.getAttribute("id")===a}}:function(a){return a=a.replace(V,""),function(b){var c=typeof b.getAttributeNode!==n&&b.getAttributeNode("id");return c&&c.value===a}},TAG:function(a){return a==="*"?function(){return!0}:(a=a.replace(V,"").toLowerCase(),function(b){return b.nodeName&&b.nodeName.toLowerCase()===a})},CLASS:function(a){var b=B[o][a];return b||(b=B(a,new RegExp("(^|"+E+")"+a+"("+E+"|$)"))),function(a){return b.test(a.className||typeof a.getAttribute!==n&&a.getAttribute("class")||"")}},ATTR:function(a,b,c){return function(d,e){var f=bc.attr(d,a);return f==null?b==="!=":b?(f+="",b==="="?f===c:b==="!="?f!==c:b==="^="?c&&f.indexOf(c)===0:b==="*="?c&&f.indexOf(c)>-1:b==="$="?c&&f.substr(f.length-c.length)===c:b==="~="?(" "+f+" ").indexOf(c)>-1:b==="|="?f===c||f.substr(0,c.length+1)===c+"-":!1):!0}},CHILD:function(a,b,c,d){return a==="nth"?function(a){var b,e,f=a.parentNode;if(c===1&&d===0)return!0;if(f){e=0;for(b=f.firstChild;b;b=b.nextSibling)if(b.nodeType===1){e++;if(a===b)break}}return e-=d,e===c||e%c===0&&e/c>=0}:function(b){var c=b;switch(a){case"only":case"first":while(c=c.previousSibling)if(c.nodeType===1)return!1;if(a==="first")return!0;c=b;case"last":while(c=c.nextSibling)if(c.nodeType===1)return!1;return!0}}},PSEUDO:function(a,b){var c,d=e.pseudos[a]||e.setFilters[a.toLowerCase()]||bc.error("unsupported pseudo: "+a);return d[o]?d(b):d.length>1?(c=[a,a,"",b],e.setFilters.hasOwnProperty(a.toLowerCase())?z(function(a,c){var e,f=d(a,b),g=f.length;while(g--)e=y.call(a,f[g]),a[e]=!(c[e]=f[g])}):function(a){return d(a,0,c)}):d}},pseudos:{not:z(function(a){var b=[],c=[],d=i(a.replace(L,"$1"));return d[o]?z(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)if(f=g[h])a[h]=!(b[h]=f)}):function(a,e,f){return b[0]=a,d(b,null,f,c),!c.pop()}}),has:z(function(a){return function(b){return bc(a,b).length>0}}),contains:z(function(a){return function(b){return(b.textContent||b.innerText||f(b)).indexOf(a)>-1}}),enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&!!a.checked||b==="option"&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},parent:function(a){return!e.pseudos.empty(a)},empty:function(a){var b;a=a.firstChild;while(a){if(a.nodeName>"@"||(b=a.nodeType)===3||b===4)return!1;a=a.nextSibling}return!0},header:function(a){return T.test(a.nodeName)},text:function(a){var b,c;return a.nodeName.toLowerCase()==="input"&&(b=a.type)==="text"&&((c=a.getAttribute("type"))==null||c.toLowerCase()===b)},radio:bd("radio"),checkbox:bd("checkbox"),file:bd("file"),password:bd("password"),image:bd("image"),submit:be("submit"),reset:be("reset"),button:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&a.type==="button"||b==="button"},input:function(a){return U.test(a.nodeName)},focus:function(a){var b=a.ownerDocument;return a===b.activeElement&&(!b.hasFocus||b.hasFocus())&&(!!a.type||!!a.href)},active:function(a){return a===a.ownerDocument.activeElement},first:bf(function(a,b,c){return[0]}),last:bf(function(a,b,c){return[b-1]}),eq:bf(function(a,b,c){return[c<0?c+b:c]}),even:bf(function(a,b,c){for(var d=0;d<b;d+=2)a.push(d);return a}),odd:bf(function(a,b,c){for(var d=1;d<b;d+=2)a.push(d);return a}),lt:bf(function(a,b,c){for(var d=c<0?c+b:c;--d>=0;)a.push(d);return a}),gt:bf(function(a,b,c){for(var d=c<0?c+b:c;++d<b;)a.push(d);return a})}},j=s.compareDocumentPosition?function(a,b){return a===b?(k=!0,0):(!a.compareDocumentPosition||!b.compareDocumentPosition?a.compareDocumentPosition:a.compareDocumentPosition(b)&4)?-1:1}:function(a,b){if(a===b)return k=!0,0;if(a.sourceIndex&&b.sourceIndex)return a.sourceIndex-b.sourceIndex;var c,d,e=[],f=[],g=a.parentNode,h=b.parentNode,i=g;if(g===h)return bg(a,b);if(!g)return-1;if(!h)return 1;while(i)e.unshift(i),i=i.parentNode;i=h;while(i)f.unshift(i),i=i.parentNode;c=e.length,d=f.length;for(var j=0;j<c&&j<d;j++)if(e[j]!==f[j])return bg(e[j],f[j]);return j===c?bg(a,f[j],-1):bg(e[j],b,1)},[0,0].sort(j),m=!k,bc.uniqueSort=function(a){var b,c=1;k=m,a.sort(j);if(k)for(;b=a[c];c++)b===a[c-1]&&a.splice(c--,1);return a},bc.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},i=bc.compile=function(a,b){var c,d=[],e=[],f=D[o][a];if(!f){b||(b=bh(a)),c=b.length;while(c--)f=bm(b[c]),f[o]?d.push(f):e.push(f);f=D(a,bn(e,d))}return f},r.querySelectorAll&&function(){var a,b=bp,c=/'|\\/g,d=/\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,e=[":focus"],f=[":active",":focus"],h=s.matchesSelector||s.mozMatchesSelector||s.webkitMatchesSelector||s.oMatchesSelector||s.msMatchesSelector;X(function(a){a.innerHTML="<select><option selected=''></option></select>",a.querySelectorAll("[selected]").length||e.push("\\["+E+"*(?:checked|disabled|ismap|multiple|readonly|selected|value)"),a.querySelectorAll(":checked").length||e.push(":checked")}),X(function(a){a.innerHTML="<p test=''></p>",a.querySelectorAll("[test^='']").length&&e.push("[*^$]="+E+"*(?:\"\"|'')"),a.innerHTML="<input type='hidden'/>",a.querySelectorAll(":enabled").length||e.push(":enabled",":disabled")}),e=new RegExp(e.join("|")),bp=function(a,d,f,g,h){if(!g&&!h&&(!e||!e.test(a))){var i,j,k=!0,l=o,m=d,n=d.nodeType===9&&a;if(d.nodeType===1&&d.nodeName.toLowerCase()!=="object"){i=bh(a),(k=d.getAttribute("id"))?l=k.replace(c,"\\$&"):d.setAttribute("id",l),l="[id='"+l+"'] ",j=i.length;while(j--)i[j]=l+i[j].join("");m=R.test(a)&&d.parentNode||d,n=i.join(",")}if(n)try{return w.apply(f,x.call(m.querySelectorAll(n),0)),f}catch(p){}finally{k||d.removeAttribute("id")}}return b(a,d,f,g,h)},h&&(X(function(b){a=h.call(b,"div");try{h.call(b,"[test!='']:sizzle"),f.push("!=",J)}catch(c){}}),f=new RegExp(f.join("|")),bc.matchesSelector=function(b,c){c=c.replace(d,"='$1']");if(!g(b)&&!f.test(c)&&(!e||!e.test(c)))try{var i=h.call(b,c);if(i||a||b.document&&b.document.nodeType!==11)return i}catch(j){}return bc(c,null,null,[b]).length>0})}(),e.pseudos.nth=e.pseudos.eq,e.filters=bq.prototype=e.pseudos,e.setFilters=new bq,bc.attr=p.attr,p.find=bc,p.expr=bc.selectors,p.expr[":"]=p.expr.pseudos,p.unique=bc.uniqueSort,p.text=bc.getText,p.isXMLDoc=bc.isXML,p.contains=bc.contains}(a);var bc=/Until$/,bd=/^(?:parents|prev(?:Until|All))/,be=/^.[^:#\[\.,]*$/,bf=p.expr.match.needsContext,bg={children:!0,contents:!0,next:!0,prev:!0};p.fn.extend({find:function(a){var b,c,d,e,f,g,h=this;if(typeof a!="string")return p(a).filter(function(){for(b=0,c=h.length;b<c;b++)if(p.contains(h[b],this))return!0});g=this.pushStack("","find",a);for(b=0,c=this.length;b<c;b++){d=g.length,p.find(a,this[b],g);if(b>0)for(e=d;e<g.length;e++)for(f=0;f<d;f++)if(g[f]===g[e]){g.splice(e--,1);break}}return g},has:function(a){var b,c=p(a,this),d=c.length;return this.filter(function(){for(b=0;b<d;b++)if(p.contains(this,c[b]))return!0})},not:function(a){return this.pushStack(bj(this,a,!1),"not",a)},filter:function(a){return this.pushStack(bj(this,a,!0),"filter",a)},is:function(a){return!!a&&(typeof a=="string"?bf.test(a)?p(a,this.context).index(this[0])>=0:p.filter(a,this).length>0:this.filter(a).length>0)},closest:function(a,b){var c,d=0,e=this.length,f=[],g=bf.test(a)||typeof a!="string"?p(a,b||this.context):0;for(;d<e;d++){c=this[d];while(c&&c.ownerDocument&&c!==b&&c.nodeType!==11){if(g?g.index(c)>-1:p.find.matchesSelector(c,a)){f.push(c);break}c=c.parentNode}}return f=f.length>1?p.unique(f):f,this.pushStack(f,"closest",a)},index:function(a){return a?typeof a=="string"?p.inArray(this[0],p(a)):p.inArray(a.jquery?a[0]:a,this):this[0]&&this[0].parentNode?this.prevAll().length:-1},add:function(a,b){var c=typeof a=="string"?p(a,b):p.makeArray(a&&a.nodeType?[a]:a),d=p.merge(this.get(),c);return this.pushStack(bh(c[0])||bh(d[0])?d:p.unique(d))},addBack:function(a){return this.add(a==null?this.prevObject:this.prevObject.filter(a))}}),p.fn.andSelf=p.fn.addBack,p.each({parent:function(a){var b=a.parentNode;return b&&b.nodeType!==11?b:null},parents:function(a){return p.dir(a,"parentNode")},parentsUntil:function(a,b,c){return p.dir(a,"parentNode",c)},next:function(a){return bi(a,"nextSibling")},prev:function(a){return bi(a,"previousSibling")},nextAll:function(a){return p.dir(a,"nextSibling")},prevAll:function(a){return p.dir(a,"previousSibling")},nextUntil:function(a,b,c){return p.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return p.dir(a,"previousSibling",c)},siblings:function(a){return p.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return p.sibling(a.firstChild)},contents:function(a){return p.nodeName(a,"iframe")?a.contentDocument||a.contentWindow.document:p.merge([],a.childNodes)}},function(a,b){p.fn[a]=function(c,d){var e=p.map(this,b,c);return bc.test(a)||(d=c),d&&typeof d=="string"&&(e=p.filter(d,e)),e=this.length>1&&!bg[a]?p.unique(e):e,this.length>1&&bd.test(a)&&(e=e.reverse()),this.pushStack(e,a,k.call(arguments).join(","))}}),p.extend({filter:function(a,b,c){return c&&(a=":not("+a+")"),b.length===1?p.find.matchesSelector(b[0],a)?[b[0]]:[]:p.find.matches(a,b)},dir:function(a,c,d){var e=[],f=a[c];while(f&&f.nodeType!==9&&(d===b||f.nodeType!==1||!p(f).is(d)))f.nodeType===1&&e.push(f),f=f[c];return e},sibling:function(a,b){var c=[];for(;a;a=a.nextSibling)a.nodeType===1&&a!==b&&c.push(a);return c}});var bl="abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",bm=/ jQuery\d+="(?:null|\d+)"/g,bn=/^\s+/,bo=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,bp=/<([\w:]+)/,bq=/<tbody/i,br=/<|&#?\w+;/,bs=/<(?:script|style|link)/i,bt=/<(?:script|object|embed|option|style)/i,bu=new RegExp("<(?:"+bl+")[\\s/>]","i"),bv=/^(?:checkbox|radio)$/,bw=/checked\s*(?:[^=]|=\s*.checked.)/i,bx=/\/(java|ecma)script/i,by=/^\s*<!(?:\[CDATA\[|\-\-)|[\]\-]{2}>\s*$/g,bz={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],area:[1,"<map>","</map>"],_default:[0,"",""]},bA=bk(e),bB=bA.appendChild(e.createElement("div"));bz.optgroup=bz.option,bz.tbody=bz.tfoot=bz.colgroup=bz.caption=bz.thead,bz.th=bz.td,p.support.htmlSerialize||(bz._default=[1,"X<div>","</div>"]),p.fn.extend({text:function(a){return p.access(this,function(a){return a===b?p.text(this):this.empty().append((this[0]&&this[0].ownerDocument||e).createTextNode(a))},null,a,arguments.length)},wrapAll:function(a){if(p.isFunction(a))return this.each(function(b){p(this).wrapAll(a.call(this,b))});if(this[0]){var b=p(a,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstChild&&a.firstChild.nodeType===1)a=a.firstChild;return a}).append(this)}return this},wrapInner:function(a){return p.isFunction(a)?this.each(function(b){p(this).wrapInner(a.call(this,b))}):this.each(function(){var b=p(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=p.isFunction(a);return this.each(function(c){p(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){p.nodeName(this,"body")||p(this).replaceWith(this.childNodes)}).end()},append:function(){return this.domManip(arguments,!0,function(a){(this.nodeType===1||this.nodeType===11)&&this.appendChild(a)})},prepend:function(){return this.domManip(arguments,!0,function(a){(this.nodeType===1||this.nodeType===11)&&this.insertBefore(a,this.firstChild)})},before:function(){if(!bh(this[0]))return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this)});if(arguments.length){var a=p.clean(arguments);return this.pushStack(p.merge(a,this),"before",this.selector)}},after:function(){if(!bh(this[0]))return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this.nextSibling)});if(arguments.length){var a=p.clean(arguments);return this.pushStack(p.merge(this,a),"after",this.selector)}},remove:function(a,b){var c,d=0;for(;(c=this[d])!=null;d++)if(!a||p.filter(a,[c]).length)!b&&c.nodeType===1&&(p.cleanData(c.getElementsByTagName("*")),p.cleanData([c])),c.parentNode&&c.parentNode.removeChild(c);return this},empty:function(){var a,b=0;for(;(a=this[b])!=null;b++){a.nodeType===1&&p.cleanData(a.getElementsByTagName("*"));while(a.firstChild)a.removeChild(a.firstChild)}return this},clone:function(a,b){return a=a==null?!1:a,b=b==null?a:b,this.map(function(){return p.clone(this,a,b)})},html:function(a){return p.access(this,function(a){var c=this[0]||{},d=0,e=this.length;if(a===b)return c.nodeType===1?c.innerHTML.replace(bm,""):b;if(typeof a=="string"&&!bs.test(a)&&(p.support.htmlSerialize||!bu.test(a))&&(p.support.leadingWhitespace||!bn.test(a))&&!bz[(bp.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(bo,"<$1></$2>");try{for(;d<e;d++)c=this[d]||{},c.nodeType===1&&(p.cleanData(c.getElementsByTagName("*")),c.innerHTML=a);c=0}catch(f){}}c&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(a){return bh(this[0])?this.length?this.pushStack(p(p.isFunction(a)?a():a),"replaceWith",a):this:p.isFunction(a)?this.each(function(b){var c=p(this),d=c.html();c.replaceWith(a.call(this,b,d))}):(typeof a!="string"&&(a=p(a).detach()),this.each(function(){var b=this.nextSibling,c=this.parentNode;p(this).remove(),b?p(b).before(a):p(c).append(a)}))},detach:function(a){return this.remove(a,!0)},domManip:function(a,c,d){a=[].concat.apply([],a);var e,f,g,h,i=0,j=a[0],k=[],l=this.length;if(!p.support.checkClone&&l>1&&typeof j=="string"&&bw.test(j))return this.each(function(){p(this).domManip(a,c,d)});if(p.isFunction(j))return this.each(function(e){var f=p(this);a[0]=j.call(this,e,c?f.html():b),f.domManip(a,c,d)});if(this[0]){e=p.buildFragment(a,this,k),g=e.fragment,f=g.firstChild,g.childNodes.length===1&&(g=f);if(f){c=c&&p.nodeName(f,"tr");for(h=e.cacheable||l-1;i<l;i++)d.call(c&&p.nodeName(this[i],"table")?bC(this[i],"tbody"):this[i],i===h?g:p.clone(g,!0,!0))}g=f=null,k.length&&p.each(k,function(a,b){b.src?p.ajax?p.ajax({url:b.src,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0}):p.error("no ajax"):p.globalEval((b.text||b.textContent||b.innerHTML||"").replace(by,"")),b.parentNode&&b.parentNode.removeChild(b)})}return this}}),p.buildFragment=function(a,c,d){var f,g,h,i=a[0];return c=c||e,c=!c.nodeType&&c[0]||c,c=c.ownerDocument||c,a.length===1&&typeof i=="string"&&i.length<512&&c===e&&i.charAt(0)==="<"&&!bt.test(i)&&(p.support.checkClone||!bw.test(i))&&(p.support.html5Clone||!bu.test(i))&&(g=!0,f=p.fragments[i],h=f!==b),f||(f=c.createDocumentFragment(),p.clean(a,c,f,d),g&&(p.fragments[i]=h&&f)),{fragment:f,cacheable:g}},p.fragments={},p.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){p.fn[a]=function(c){var d,e=0,f=[],g=p(c),h=g.length,i=this.length===1&&this[0].parentNode;if((i==null||i&&i.nodeType===11&&i.childNodes.length===1)&&h===1)return g[b](this[0]),this;for(;e<h;e++)d=(e>0?this.clone(!0):this).get(),p(g[e])[b](d),f=f.concat(d);return this.pushStack(f,a,g.selector)}}),p.extend({clone:function(a,b,c){var d,e,f,g;p.support.html5Clone||p.isXMLDoc(a)||!bu.test("<"+a.nodeName+">")?g=a.cloneNode(!0):(bB.innerHTML=a.outerHTML,bB.removeChild(g=bB.firstChild));if((!p.support.noCloneEvent||!p.support.noCloneChecked)&&(a.nodeType===1||a.nodeType===11)&&!p.isXMLDoc(a)){bE(a,g),d=bF(a),e=bF(g);for(f=0;d[f];++f)e[f]&&bE(d[f],e[f])}if(b){bD(a,g);if(c){d=bF(a),e=bF(g);for(f=0;d[f];++f)bD(d[f],e[f])}}return d=e=null,g},clean:function(a,b,c,d){var f,g,h,i,j,k,l,m,n,o,q,r,s=b===e&&bA,t=[];if(!b||typeof b.createDocumentFragment=="undefined")b=e;for(f=0;(h=a[f])!=null;f++){typeof h=="number"&&(h+="");if(!h)continue;if(typeof h=="string")if(!br.test(h))h=b.createTextNode(h);else{s=s||bk(b),l=b.createElement("div"),s.appendChild(l),h=h.replace(bo,"<$1></$2>"),i=(bp.exec(h)||["",""])[1].toLowerCase(),j=bz[i]||bz._default,k=j[0],l.innerHTML=j[1]+h+j[2];while(k--)l=l.lastChild;if(!p.support.tbody){m=bq.test(h),n=i==="table"&&!m?l.firstChild&&l.firstChild.childNodes:j[1]==="<table>"&&!m?l.childNodes:[];for(g=n.length-1;g>=0;--g)p.nodeName(n[g],"tbody")&&!n[g].childNodes.length&&n[g].parentNode.removeChild(n[g])}!p.support.leadingWhitespace&&bn.test(h)&&l.insertBefore(b.createTextNode(bn.exec(h)[0]),l.firstChild),h=l.childNodes,l.parentNode.removeChild(l)}h.nodeType?t.push(h):p.merge(t,h)}l&&(h=l=s=null);if(!p.support.appendChecked)for(f=0;(h=t[f])!=null;f++)p.nodeName(h,"input")?bG(h):typeof h.getElementsByTagName!="undefined"&&p.grep(h.getElementsByTagName("input"),bG);if(c){q=function(a){if(!a.type||bx.test(a.type))return d?d.push(a.parentNode?a.parentNode.removeChild(a):a):c.appendChild(a)};for(f=0;(h=t[f])!=null;f++)if(!p.nodeName(h,"script")||!q(h))c.appendChild(h),typeof h.getElementsByTagName!="undefined"&&(r=p.grep(p.merge([],h.getElementsByTagName("script")),q),t.splice.apply(t,[f+1,0].concat(r)),f+=r.length)}return t},cleanData:function(a,b){var c,d,e,f,g=0,h=p.expando,i=p.cache,j=p.support.deleteExpando,k=p.event.special;for(;(e=a[g])!=null;g++)if(b||p.acceptData(e)){d=e[h],c=d&&i[d];if(c){if(c.events)for(f in c.events)k[f]?p.event.remove(e,f):p.removeEvent(e,f,c.handle);i[d]&&(delete i[d],j?delete e[h]:e.removeAttribute?e.removeAttribute(h):e[h]=null,p.deletedIds.push(d))}}}}),function(){var a,b;p.uaMatch=function(a){a=a.toLowerCase();var b=/(chrome)[ \/]([\w.]+)/.exec(a)||/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||a.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(a)||[];return{browser:b[1]||"",version:b[2]||"0"}},a=p.uaMatch(g.userAgent),b={},a.browser&&(b[a.browser]=!0,b.version=a.version),b.chrome?b.webkit=!0:b.webkit&&(b.safari=!0),p.browser=b,p.sub=function(){function a(b,c){return new a.fn.init(b,c)}p.extend(!0,a,this),a.superclass=this,a.fn=a.prototype=this(),a.fn.constructor=a,a.sub=this.sub,a.fn.init=function c(c,d){return d&&d instanceof p&&!(d instanceof a)&&(d=a(d)),p.fn.init.call(this,c,d,b)},a.fn.init.prototype=a.fn;var b=a(e);return a}}();var bH,bI,bJ,bK=/alpha\([^)]*\)/i,bL=/opacity=([^)]*)/,bM=/^(top|right|bottom|left)$/,bN=/^(none|table(?!-c[ea]).+)/,bO=/^margin/,bP=new RegExp("^("+q+")(.*)$","i"),bQ=new RegExp("^("+q+")(?!px)[a-z%]+$","i"),bR=new RegExp("^([-+])=("+q+")","i"),bS={},bT={position:"absolute",visibility:"hidden",display:"block"},bU={letterSpacing:0,fontWeight:400},bV=["Top","Right","Bottom","Left"],bW=["Webkit","O","Moz","ms"],bX=p.fn.toggle;p.fn.extend({css:function(a,c){return p.access(this,function(a,c,d){return d!==b?p.style(a,c,d):p.css(a,c)},a,c,arguments.length>1)},show:function(){return b$(this,!0)},hide:function(){return b$(this)},toggle:function(a,b){var c=typeof a=="boolean";return p.isFunction(a)&&p.isFunction(b)?bX.apply(this,arguments):this.each(function(){(c?a:bZ(this))?p(this).show():p(this).hide()})}}),p.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=bH(a,"opacity");return c===""?"1":c}}}},cssNumber:{fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":p.support.cssFloat?"cssFloat":"styleFloat"},style:function(a,c,d,e){if(!a||a.nodeType===3||a.nodeType===8||!a.style)return;var f,g,h,i=p.camelCase(c),j=a.style;c=p.cssProps[i]||(p.cssProps[i]=bY(j,i)),h=p.cssHooks[c]||p.cssHooks[i];if(d===b)return h&&"get"in h&&(f=h.get(a,!1,e))!==b?f:j[c];g=typeof d,g==="string"&&(f=bR.exec(d))&&(d=(f[1]+1)*f[2]+parseFloat(p.css(a,c)),g="number");if(d==null||g==="number"&&isNaN(d))return;g==="number"&&!p.cssNumber[i]&&(d+="px");if(!h||!("set"in h)||(d=h.set(a,d,e))!==b)try{j[c]=d}catch(k){}},css:function(a,c,d,e){var f,g,h,i=p.camelCase(c);return c=p.cssProps[i]||(p.cssProps[i]=bY(a.style,i)),h=p.cssHooks[c]||p.cssHooks[i],h&&"get"in h&&(f=h.get(a,!0,e)),f===b&&(f=bH(a,c)),f==="normal"&&c in bU&&(f=bU[c]),d||e!==b?(g=parseFloat(f),d||p.isNumeric(g)?g||0:f):f},swap:function(a,b,c){var d,e,f={};for(e in b)f[e]=a.style[e],a.style[e]=b[e];d=c.call(a);for(e in b)a.style[e]=f[e];return d}}),a.getComputedStyle?bH=function(b,c){var d,e,f,g,h=a.getComputedStyle(b,null),i=b.style;return h&&(d=h[c],d===""&&!p.contains(b.ownerDocument,b)&&(d=p.style(b,c)),bQ.test(d)&&bO.test(c)&&(e=i.width,f=i.minWidth,g=i.maxWidth,i.minWidth=i.maxWidth=i.width=d,d=h.width,i.width=e,i.minWidth=f,i.maxWidth=g)),d}:e.documentElement.currentStyle&&(bH=function(a,b){var c,d,e=a.currentStyle&&a.currentStyle[b],f=a.style;return e==null&&f&&f[b]&&(e=f[b]),bQ.test(e)&&!bM.test(b)&&(c=f.left,d=a.runtimeStyle&&a.runtimeStyle.left,d&&(a.runtimeStyle.left=a.currentStyle.left),f.left=b==="fontSize"?"1em":e,e=f.pixelLeft+"px",f.left=c,d&&(a.runtimeStyle.left=d)),e===""?"auto":e}),p.each(["height","width"],function(a,b){p.cssHooks[b]={get:function(a,c,d){if(c)return a.offsetWidth===0&&bN.test(bH(a,"display"))?p.swap(a,bT,function(){return cb(a,b,d)}):cb(a,b,d)},set:function(a,c,d){return b_(a,c,d?ca(a,b,d,p.support.boxSizing&&p.css(a,"boxSizing")==="border-box"):0)}}}),p.support.opacity||(p.cssHooks.opacity={get:function(a,b){return bL.test((b&&a.currentStyle?a.currentStyle.filter:a.style.filter)||"")?.01*parseFloat(RegExp.$1)+"":b?"1":""},set:function(a,b){var c=a.style,d=a.currentStyle,e=p.isNumeric(b)?"alpha(opacity="+b*100+")":"",f=d&&d.filter||c.filter||"";c.zoom=1;if(b>=1&&p.trim(f.replace(bK,""))===""&&c.removeAttribute){c.removeAttribute("filter");if(d&&!d.filter)return}c.filter=bK.test(f)?f.replace(bK,e):f+" "+e}}),p(function(){p.support.reliableMarginRight||(p.cssHooks.marginRight={get:function(a,b){return p.swap(a,{display:"inline-block"},function(){if(b)return bH(a,"marginRight")})}}),!p.support.pixelPosition&&p.fn.position&&p.each(["top","left"],function(a,b){p.cssHooks[b]={get:function(a,c){if(c){var d=bH(a,b);return bQ.test(d)?p(a).position()[b]+"px":d}}}})}),p.expr&&p.expr.filters&&(p.expr.filters.hidden=function(a){return a.offsetWidth===0&&a.offsetHeight===0||!p.support.reliableHiddenOffsets&&(a.style&&a.style.display||bH(a,"display"))==="none"},p.expr.filters.visible=function(a){return!p.expr.filters.hidden(a)}),p.each({margin:"",padding:"",border:"Width"},function(a,b){p.cssHooks[a+b]={expand:function(c){var d,e=typeof c=="string"?c.split(" "):[c],f={};for(d=0;d<4;d++)f[a+bV[d]+b]=e[d]||e[d-2]||e[0];return f}},bO.test(a)||(p.cssHooks[a+b].set=b_)});var cd=/%20/g,ce=/\[\]$/,cf=/\r?\n/g,cg=/^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,ch=/^(?:select|textarea)/i;p.fn.extend({serialize:function(){return p.param(this.serializeArray())},serializeArray:function(){return this.map(function(){return this.elements?p.makeArray(this.elements):this}).filter(function(){return this.name&&!this.disabled&&(this.checked||ch.test(this.nodeName)||cg.test(this.type))}).map(function(a,b){var c=p(this).val();return c==null?null:p.isArray(c)?p.map(c,function(a,c){return{name:b.name,value:a.replace(cf,"\r\n")}}):{name:b.name,value:c.replace(cf,"\r\n")}}).get()}}),p.param=function(a,c){var d,e=[],f=function(a,b){b=p.isFunction(b)?b():b==null?"":b,e[e.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};c===b&&(c=p.ajaxSettings&&p.ajaxSettings.traditional);if(p.isArray(a)||a.jquery&&!p.isPlainObject(a))p.each(a,function(){f(this.name,this.value)});else for(d in a)ci(d,a[d],c,f);return e.join("&").replace(cd,"+")};var cj,ck,cl=/#.*$/,cm=/^(.*?):[ \t]*([^\r\n]*)\r?$/mg,cn=/^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/,co=/^(?:GET|HEAD)$/,cp=/^\/\//,cq=/\?/,cr=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,cs=/([?&])_=[^&]*/,ct=/^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,cu=p.fn.load,cv={},cw={},cx=["*/"]+["*"];try{ck=f.href}catch(cy){ck=e.createElement("a"),ck.href="",ck=ck.href}cj=ct.exec(ck.toLowerCase())||[],p.fn.load=function(a,c,d){if(typeof a!="string"&&cu)return cu.apply(this,arguments);if(!this.length)return this;var e,f,g,h=this,i=a.indexOf(" ");return i>=0&&(e=a.slice(i,a.length),a=a.slice(0,i)),p.isFunction(c)?(d=c,c=b):c&&typeof c=="object"&&(f="POST"),p.ajax({url:a,type:f,dataType:"html",data:c,complete:function(a,b){d&&h.each(d,g||[a.responseText,b,a])}}).done(function(a){g=arguments,h.html(e?p("<div>").append(a.replace(cr,"")).find(e):a)}),this},p.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "),function(a,b){p.fn[b]=function(a){return this.on(b,a)}}),p.each(["get","post"],function(a,c){p[c]=function(a,d,e,f){return p.isFunction(d)&&(f=f||e,e=d,d=b),p.ajax({type:c,url:a,data:d,success:e,dataType:f})}}),p.extend({getScript:function(a,c){return p.get(a,b,c,"script")},getJSON:function(a,b,c){return p.get(a,b,c,"json")},ajaxSetup:function(a,b){return b?cB(a,p.ajaxSettings):(b=a,a=p.ajaxSettings),cB(a,b),a},ajaxSettings:{url:ck,isLocal:cn.test(cj[1]),global:!0,type:"GET",contentType:"application/x-www-form-urlencoded; charset=UTF-8",processData:!0,async:!0,accepts:{xml:"application/xml, text/xml",html:"text/html",text:"text/plain",json:"application/json, text/javascript","*":cx},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText"},converters:{"* text":a.String,"text html":!0,"text json":p.parseJSON,"text xml":p.parseXML},flatOptions:{context:!0,url:!0}},ajaxPrefilter:cz(cv),ajaxTransport:cz(cw),ajax:function(a,c){function y(a,c,f,i){var k,s,t,u,w,y=c;if(v===2)return;v=2,h&&clearTimeout(h),g=b,e=i||"",x.readyState=a>0?4:0,f&&(u=cC(l,x,f));if(a>=200&&a<300||a===304)l.ifModified&&(w=x.getResponseHeader("Last-Modified"),w&&(p.lastModified[d]=w),w=x.getResponseHeader("Etag"),w&&(p.etag[d]=w)),a===304?(y="notmodified",k=!0):(k=cD(l,u),y=k.state,s=k.data,t=k.error,k=!t);else{t=y;if(!y||a)y="error",a<0&&(a=0)}x.status=a,x.statusText=(c||y)+"",k?o.resolveWith(m,[s,y,x]):o.rejectWith(m,[x,y,t]),x.statusCode(r),r=b,j&&n.trigger("ajax"+(k?"Success":"Error"),[x,l,k?s:t]),q.fireWith(m,[x,y]),j&&(n.trigger("ajaxComplete",[x,l]),--p.active||p.event.trigger("ajaxStop"))}typeof a=="object"&&(c=a,a=b),c=c||{};var d,e,f,g,h,i,j,k,l=p.ajaxSetup({},c),m=l.context||l,n=m!==l&&(m.nodeType||m instanceof p)?p(m):p.event,o=p.Deferred(),q=p.Callbacks("once memory"),r=l.statusCode||{},t={},u={},v=0,w="canceled",x={readyState:0,setRequestHeader:function(a,b){if(!v){var c=a.toLowerCase();a=u[c]=u[c]||a,t[a]=b}return this},getAllResponseHeaders:function(){return v===2?e:null},getResponseHeader:function(a){var c;if(v===2){if(!f){f={};while(c=cm.exec(e))f[c[1].toLowerCase()]=c[2]}c=f[a.toLowerCase()]}return c===b?null:c},overrideMimeType:function(a){return v||(l.mimeType=a),this},abort:function(a){return a=a||w,g&&g.abort(a),y(0,a),this}};o.promise(x),x.success=x.done,x.error=x.fail,x.complete=q.add,x.statusCode=function(a){if(a){var b;if(v<2)for(b in a)r[b]=[r[b],a[b]];else b=a[x.status],x.always(b)}return this},l.url=((a||l.url)+"").replace(cl,"").replace(cp,cj[1]+"//"),l.dataTypes=p.trim(l.dataType||"*").toLowerCase().split(s),l.crossDomain==null&&(i=ct.exec(l.url.toLowerCase())||!1,l.crossDomain=i&&i.join(":")+(i[3]?"":i[1]==="http:"?80:443)!==cj.join(":")+(cj[3]?"":cj[1]==="http:"?80:443)),l.data&&l.processData&&typeof l.data!="string"&&(l.data=p.param(l.data,l.traditional)),cA(cv,l,c,x);if(v===2)return x;j=l.global,l.type=l.type.toUpperCase(),l.hasContent=!co.test(l.type),j&&p.active++===0&&p.event.trigger("ajaxStart");if(!l.hasContent){l.data&&(l.url+=(cq.test(l.url)?"&":"?")+l.data,delete l.data),d=l.url;if(l.cache===!1){var z=p.now(),A=l.url.replace(cs,"$1_="+z);l.url=A+(A===l.url?(cq.test(l.url)?"&":"?")+"_="+z:"")}}(l.data&&l.hasContent&&l.contentType!==!1||c.contentType)&&x.setRequestHeader("Content-Type",l.contentType),l.ifModified&&(d=d||l.url,p.lastModified[d]&&x.setRequestHeader("If-Modified-Since",p.lastModified[d]),p.etag[d]&&x.setRequestHeader("If-None-Match",p.etag[d])),x.setRequestHeader("Accept",l.dataTypes[0]&&l.accepts[l.dataTypes[0]]?l.accepts[l.dataTypes[0]]+(l.dataTypes[0]!=="*"?", "+cx+"; q=0.01":""):l.accepts["*"]);for(k in l.headers)x.setRequestHeader(k,l.headers[k]);if(!l.beforeSend||l.beforeSend.call(m,x,l)!==!1&&v!==2){w="abort";for(k in{success:1,error:1,complete:1})x[k](l[k]);g=cA(cw,l,c,x);if(!g)y(-1,"No Transport");else{x.readyState=1,j&&n.trigger("ajaxSend",[x,l]),l.async&&l.timeout>0&&(h=setTimeout(function(){x.abort("timeout")},l.timeout));try{v=1,g.send(t,y)}catch(B){if(v<2)y(-1,B);else throw B}}return x}return x.abort()},active:0,lastModified:{},etag:{}});var cE=[],cF=/\?/,cG=/(=)\?(?=&|$)|\?\?/,cH=p.now();p.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=cE.pop()||p.expando+"_"+cH++;return this[a]=!0,a}}),p.ajaxPrefilter("json jsonp",function(c,d,e){var f,g,h,i=c.data,j=c.url,k=c.jsonp!==!1,l=k&&cG.test(j),m=k&&!l&&typeof i=="string"&&!(c.contentType||"").indexOf("application/x-www-form-urlencoded")&&cG.test(i);if(c.dataTypes[0]==="jsonp"||l||m)return f=c.jsonpCallback=p.isFunction(c.jsonpCallback)?c.jsonpCallback():c.jsonpCallback,g=a[f],l?c.url=j.replace(cG,"$1"+f):m?c.data=i.replace(cG,"$1"+f):k&&(c.url+=(cF.test(j)?"&":"?")+c.jsonp+"="+f),c.converters["script json"]=function(){return h||p.error(f+" was not called"),h[0]},c.dataTypes[0]="json",a[f]=function(){h=arguments},e.always(function(){a[f]=g,c[f]&&(c.jsonpCallback=d.jsonpCallback,cE.push(f)),h&&p.isFunction(g)&&g(h[0]),h=g=b}),"script"}),p.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/javascript|ecmascript/},converters:{"text script":function(a){return p.globalEval(a),a}}}),p.ajaxPrefilter("script",function(a){a.cache===b&&(a.cache=!1),a.crossDomain&&(a.type="GET",a.global=!1)}),p.ajaxTransport("script",function(a){if(a.crossDomain){var c,d=e.head||e.getElementsByTagName("head")[0]||e.documentElement;return{send:function(f,g){c=e.createElement("script"),c.async="async",a.scriptCharset&&(c.charset=a.scriptCharset),c.src=a.url,c.onload=c.onreadystatechange=function(a,e){if(e||!c.readyState||/loaded|complete/.test(c.readyState))c.onload=c.onreadystatechange=null,d&&c.parentNode&&d.removeChild(c),c=b,e||g(200,"success")},d.insertBefore(c,d.firstChild)},abort:function(){c&&c.onload(0,1)}}}});var cI,cJ=a.ActiveXObject?function(){for(var a in cI)cI[a](0,1)}:!1,cK=0;p.ajaxSettings.xhr=a.ActiveXObject?function(){return!this.isLocal&&cL()||cM()}:cL,function(a){p.extend(p.support,{ajax:!!a,cors:!!a&&"withCredentials"in a})}(p.ajaxSettings.xhr()),p.support.ajax&&p.ajaxTransport(function(c){if(!c.crossDomain||p.support.cors){var d;return{send:function(e,f){var g,h,i=c.xhr();c.username?i.open(c.type,c.url,c.async,c.username,c.password):i.open(c.type,c.url,c.async);if(c.xhrFields)for(h in c.xhrFields)i[h]=c.xhrFields[h];c.mimeType&&i.overrideMimeType&&i.overrideMimeType(c.mimeType),!c.crossDomain&&!e["X-Requested-With"]&&(e["X-Requested-With"]="XMLHttpRequest");try{for(h in e)i.setRequestHeader(h,e[h])}catch(j){}i.send(c.hasContent&&c.data||null),d=function(a,e){var h,j,k,l,m;try{if(d&&(e||i.readyState===4)){d=b,g&&(i.onreadystatechange=p.noop,cJ&&delete cI[g]);if(e)i.readyState!==4&&i.abort();else{h=i.status,k=i.getAllResponseHeaders(),l={},m=i.responseXML,m&&m.documentElement&&(l.xml=m);try{l.text=i.responseText}catch(a){}try{j=i.statusText}catch(n){j=""}!h&&c.isLocal&&!c.crossDomain?h=l.text?200:404:h===1223&&(h=204)}}}catch(o){e||f(-1,o)}l&&f(h,j,l,k)},c.async?i.readyState===4?setTimeout(d,0):(g=++cK,cJ&&(cI||(cI={},p(a).unload(cJ)),cI[g]=d),i.onreadystatechange=d):d()},abort:function(){d&&d(0,1)}}}});var cN,cO,cP=/^(?:toggle|show|hide)$/,cQ=new RegExp("^(?:([-+])=|)("+q+")([a-z%]*)$","i"),cR=/queueHooks$/,cS=[cY],cT={"*":[function(a,b){var c,d,e=this.createTween(a,b),f=cQ.exec(b),g=e.cur(),h=+g||0,i=1,j=20;if(f){c=+f[2],d=f[3]||(p.cssNumber[a]?"":"px");if(d!=="px"&&h){h=p.css(e.elem,a,!0)||c||1;do i=i||".5",h=h/i,p.style(e.elem,a,h+d);while(i!==(i=e.cur()/g)&&i!==1&&--j)}e.unit=d,e.start=h,e.end=f[1]?h+(f[1]+1)*c:c}return e}]};p.Animation=p.extend(cW,{tweener:function(a,b){p.isFunction(a)?(b=a,a=["*"]):a=a.split(" ");var c,d=0,e=a.length;for(;d<e;d++)c=a[d],cT[c]=cT[c]||[],cT[c].unshift(b)},prefilter:function(a,b){b?cS.unshift(a):cS.push(a)}}),p.Tween=cZ,cZ.prototype={constructor:cZ,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||"swing",this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(p.cssNumber[c]?"":"px")},cur:function(){var a=cZ.propHooks[this.prop];return a&&a.get?a.get(this):cZ.propHooks._default.get(this)},run:function(a){var b,c=cZ.propHooks[this.prop];return this.options.duration?this.pos=b=p.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):this.pos=b=a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):cZ.propHooks._default.set(this),this}},cZ.prototype.init.prototype=cZ.prototype,cZ.propHooks={_default:{get:function(a){var b;return a.elem[a.prop]==null||!!a.elem.style&&a.elem.style[a.prop]!=null?(b=p.css(a.elem,a.prop,!1,""),!b||b==="auto"?0:b):a.elem[a.prop]},set:function(a){p.fx.step[a.prop]?p.fx.step[a.prop](a):a.elem.style&&(a.elem.style[p.cssProps[a.prop]]!=null||p.cssHooks[a.prop])?p.style(a.elem,a.prop,a.now+a.unit):a.elem[a.prop]=a.now}}},cZ.propHooks.scrollTop=cZ.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},p.each(["toggle","show","hide"],function(a,b){var c=p.fn[b];p.fn[b]=function(d,e,f){return d==null||typeof d=="boolean"||!a&&p.isFunction(d)&&p.isFunction(e)?c.apply(this,arguments):this.animate(c$(b,!0),d,e,f)}}),p.fn.extend({fadeTo:function(a,b,c,d){return this.filter(bZ).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=p.isEmptyObject(a),f=p.speed(b,c,d),g=function(){var b=cW(this,p.extend({},a),f);e&&b.stop(!0)};return e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,c,d){var e=function(a){var b=a.stop;delete a.stop,b(d)};return typeof a!="string"&&(d=c,c=a,a=b),c&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,c=a!=null&&a+"queueHooks",f=p.timers,g=p._data(this);if(c)g[c]&&g[c].stop&&e(g[c]);else for(c in g)g[c]&&g[c].stop&&cR.test(c)&&e(g[c]);for(c=f.length;c--;)f[c].elem===this&&(a==null||f[c].queue===a)&&(f[c].anim.stop(d),b=!1,f.splice(c,1));(b||!d)&&p.dequeue(this,a)})}}),p.each({slideDown:c$("show"),slideUp:c$("hide"),slideToggle:c$("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){p.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),p.speed=function(a,b,c){var d=a&&typeof a=="object"?p.extend({},a):{complete:c||!c&&b||p.isFunction(a)&&a,duration:a,easing:c&&b||b&&!p.isFunction(b)&&b};d.duration=p.fx.off?0:typeof d.duration=="number"?d.duration:d.duration in p.fx.speeds?p.fx.speeds[d.duration]:p.fx.speeds._default;if(d.queue==null||d.queue===!0)d.queue="fx";return d.old=d.complete,d.complete=function(){p.isFunction(d.old)&&d.old.call(this),d.queue&&p.dequeue(this,d.queue)},d},p.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2}},p.timers=[],p.fx=cZ.prototype.init,p.fx.tick=function(){var a,b=p.timers,c=0;for(;c<b.length;c++)a=b[c],!a()&&b[c]===a&&b.splice(c--,1);b.length||p.fx.stop()},p.fx.timer=function(a){a()&&p.timers.push(a)&&!cO&&(cO=setInterval(p.fx.tick,p.fx.interval))},p.fx.interval=13,p.fx.stop=function(){clearInterval(cO),cO=null},p.fx.speeds={slow:600,fast:200,_default:400},p.fx.step={},p.expr&&p.expr.filters&&(p.expr.filters.animated=function(a){return p.grep(p.timers,function(b){return a===b.elem}).length});var c_=/^(?:body|html)$/i;p.fn.offset=function(a){if(arguments.length)return a===b?this:this.each(function(b){p.offset.setOffset(this,a,b)});var c,d,e,f,g,h,i,j={top:0,left:0},k=this[0],l=k&&k.ownerDocument;if(!l)return;return(d=l.body)===k?p.offset.bodyOffset(k):(c=l.documentElement,p.contains(c,k)?(typeof k.getBoundingClientRect!="undefined"&&(j=k.getBoundingClientRect()),e=da(l),f=c.clientTop||d.clientTop||0,g=c.clientLeft||d.clientLeft||0,h=e.pageYOffset||c.scrollTop,i=e.pageXOffset||c.scrollLeft,{top:j.top+h-f,left:j.left+i-g}):j)},p.offset={bodyOffset:function(a){var b=a.offsetTop,c=a.offsetLeft;return p.support.doesNotIncludeMarginInBodyOffset&&(b+=parseFloat(p.css(a,"marginTop"))||0,c+=parseFloat(p.css(a,"marginLeft"))||0),{top:b,left:c}},setOffset:function(a,b,c){var d=p.css(a,"position");d==="static"&&(a.style.position="relative");var e=p(a),f=e.offset(),g=p.css(a,"top"),h=p.css(a,"left"),i=(d==="absolute"||d==="fixed")&&p.inArray("auto",[g,h])>-1,j={},k={},l,m;i?(k=e.position(),l=k.top,m=k.left):(l=parseFloat(g)||0,m=parseFloat(h)||0),p.isFunction(b)&&(b=b.call(a,c,f)),b.top!=null&&(j.top=b.top-f.top+l),b.left!=null&&(j.left=b.left-f.left+m),"using"in b?b.using.call(a,j):e.css(j)}},p.fn.extend({position:function(){if(!this[0])return;var a=this[0],b=this.offsetParent(),c=this.offset(),d=c_.test(b[0].nodeName)?{top:0,left:0}:b.offset();return c.top-=parseFloat(p.css(a,"marginTop"))||0,c.left-=parseFloat(p.css(a,"marginLeft"))||0,d.top+=parseFloat(p.css(b[0],"borderTopWidth"))||0,d.left+=parseFloat(p.css(b[0],"borderLeftWidth"))||0,{top:c.top-d.top,left:c.left-d.left}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||e.body;while(a&&!c_.test(a.nodeName)&&p.css(a,"position")==="static")a=a.offsetParent;return a||e.body})}}),p.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(a,c){var d=/Y/.test(c);p.fn[a]=function(e){return p.access(this,function(a,e,f){var g=da(a);if(f===b)return g?c in g?g[c]:g.document.documentElement[e]:a[e];g?g.scrollTo(d?p(g).scrollLeft():f,d?f:p(g).scrollTop()):a[e]=f},a,e,arguments.length,null)}}),p.each({Height:"height",Width:"width"},function(a,c){p.each({padding:"inner"+a,content:c,"":"outer"+a},function(d,e){p.fn[e]=function(e,f){var g=arguments.length&&(d||typeof e!="boolean"),h=d||(e===!0||f===!0?"margin":"border");return p.access(this,function(c,d,e){var f;return p.isWindow(c)?c.document.documentElement["client"+a]:c.nodeType===9?(f=c.documentElement,Math.max(c.body["scroll"+a],f["scroll"+a],c.body["offset"+a],f["offset"+a],f["client"+a])):e===b?p.css(c,d,e,h):p.style(c,d,e,h)},c,g?e:b,g,null)}})}),a.jQuery=a.$=p,typeof define=="function"&&define.amd&&define.amd.jQuery&&define("jquery",[],function(){return p})})(window);
;$.easing['jswing']=$.easing['swing'];$.extend($.easing,{def:'easeOutQuad',swing:function(x,t,b,c,d){return $.easing[$.easing.def](x,t,b,c,d)},easeInQuad:function(x,t,b,c,d){return c*(t/=d)*t+b},easeOutQuad:function(x,t,b,c,d){return-c*(t/=d)*(t-2)+b},easeInOutQuad:function(x,t,b,c,d){if((t/=d/2)<1) return c/2*t*t+b;return-c/2*((--t)*(t-2)-1)+b},easeInCubic:function(x,t,b,c,d){return c*(t/=d)*t*t+b},easeOutCubic:function(x,t,b,c,d){return c*((t=t/d-1)*t*t+1)+b},easeInOutCubic:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t+b;return c/2*((t-=2)*t*t+2)+b},easeInQuart:function(x,t,b,c,d){return c*(t/=d)*t*t*t+b},easeOutQuart:function(x,t,b,c,d){return-c*((t=t/d-1)*t*t*t-1)+b},easeInOutQuart:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t*t+b;return-c/2*((t-=2)*t*t*t-2)+b},easeInQuint:function(x,t,b,c,d){return c*(t/=d)*t*t*t*t+b},easeOutQuint:function(x,t,b,c,d){return c*((t=t/d-1)*t*t*t*t+1)+b},easeInOutQuint:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t*t*t+b;return c/2*((t-=2)*t*t*t*t+2)+b},easeInSine:function(x,t,b,c,d){return-c*Math.cos(t/d*(Math.PI/2))+c+b},easeOutSine:function(x,t,b,c,d){return c*Math.sin(t/d*(Math.PI/2))+b},easeInOutSine:function(x,t,b,c,d){return-c/2*(Math.cos(Math.PI*t/d)-1)+b},easeInExpo:function(x,t,b,c,d){return(t==0)?b:c*Math.pow(2,10*(t/d-1))+b},easeOutExpo:function(x,t,b,c,d){return(t==d)?b+c:c*(-Math.pow(2,-10*t/d)+1)+b},easeInOutExpo:function(x,t,b,c,d){if(t==0)return b;if(t==d)return b+c;if((t/=d/2)<1)return c/2*Math.pow(2,10*(t-1))+b;return c/2*(-Math.pow(2,-10*--t)+2)+b},easeInCirc:function(x,t,b,c,d){return-c*(Math.sqrt(1-(t/=d)*t)-1)+b},easeOutCirc:function(x,t,b,c,d){return c*Math.sqrt(1-(t=t/d-1)*t)+b},easeInOutCirc:function(x,t,b,c,d){if((t/=d/2)<1)return-c/2*(Math.sqrt(1-t*t)-1)+b;return c/2*(Math.sqrt(1-(t-=2)*t)+1)+b},easeInElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0) return b;if((t/=d)==1)return b+c;if(!p)p=d*.3;if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);return-(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b},easeOutElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d)==1)return b+c;if(!p)p=d*.3;if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);return a*Math.pow(2,-10*t)*Math.sin((t*d-s)*(2*Math.PI)/p)+c+b},easeInOutElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d/2)==2)return b+c;if(!p)p=d*(.3*1.5);if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);if(t<1)return-.5*(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;return a*Math.pow(2,-10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p)*.5+c+b},easeInBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;return c*(t/=d)*t*((s+1)*t-s)+b},easeOutBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;return c*((t=t/d-1)*t*((s+1)*t+s)+1)+b},easeInOutBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;if((t/=d/2)<1)return c/2*(t*t*(((s*=(1.525))+1)*t-s))+b;return c/2*((t-=2)*t*(((s*=(1.525))+1)*t+s)+2)+b},easeInBounce:function(x,t,b,c,d){return c-$.easing.easeOutBounce(x,d-t,0,c,d)+b},easeOutBounce:function(x,t,b,c,d){if((t/=d)<(1/2.75)){return c*(7.5625*t*t)+b}else if(t<(2/2.75)){return c*(7.5625*(t-=(1.5/2.75))*t+.75)+b}else if(t<(2.5/2.75)){return c*(7.5625*(t-=(2.25/2.75))*t+.9375)+b}else{return c*(7.5625*(t-=(2.625/2.75))*t+.984375)+b}},easeInOutBounce:function(x,t,b,c,d){if(t<d/2)return $.easing.easeInBounce(x,t*2,0,c,d)*.5+b;return $.easing.easeOutBounce(x,t*2-d,0,c,d)*.5+c*.5+b}})
;/**
	 * ÊÊºÏÓÚÎÄ±¾µÄ¹ö¶¯ date: 2014/2/12 author: zero
	 */
;(function(window){
	var txtInit = function(id, options){
		var that = this;
		that.obj =  typeof(id)=='object'?id:window.document.getElementById(id);
		that.options = {
			maxHeight:      '200',								  //ÎÄ°¸×î´ó¸ß¶È
			maxWidth:       '300',								  //ÎÄ°¸×î´ó¿í¶È
			node:			'',									  //ÓÃÓÚËã¸ß¶ÈµÄº¢×Ó½Úµã
			
			expand:         false,								  //ÊÇ·ñµã»÷¿ªÁËÎÄ°¸
			allowScroll:    false,								  //ÊÇ·ñÔÊÐí¹ö¶¯
			childHeight:    0,                                    //ÐèÒª¹ö¶¯µÄdom¸ß¶È
			scrollLength:   1000000,                              //¹ö¶¯µÄ³¤¸ß¶È
			tX:             0,                                    //xÖáÆ½ÒÆÁ¿
			tY:             0,									  //yÖáÆ½ÒÆÁ¿
		};
		var k;
		for (k in options){
			that.options[k] = options[k];
		}
		if(that.options.node){
			that.options.node = that.options.node.split(',');
		}
		that._init();
		return that;
	};
	txtInit.prototype = {
		handleEvent: function(e){
			var that = this;
			switch (e.type){
				case 'click': that._start(e);break;
				case 'touchstart' : that._startScroll(e);break;
				case 'touchmove' : that._scroll(e);break;
				case 'touchend' : that._endScroll(e);break;
			}
		},
		_init: function(){
			var that = this;
			that.setBroseType();
			that._end();
			that._bind('click', that.obj , false);
			that._bind('touchstart', that.obj, false);
			that._bind('touchmove', that.obj, false);
			that._bind('touchend', that.obj, false);
			
			that.options.orgHeight = $(that.obj).height();      // Î´Õ¹¿ªÇ°µÄ¸ß¶È
			that.options.orgWidth = $(that.obj).width();		// Î´Õ¹¿ªÇ°µÄ¿í¶È
			
			var	size = that.options.node?that.options.node.length:0,
				j;
			for(j=0; j<size; j++){
				if($(that.obj).find(that.options.node[j]).size()>0){
					that.options.childHeight += $(that.obj).find(that.options.node[j]).height();
				}
			}
			that.options.childHeight = parseInt(that.options.childHeight)*(that.options.orgWidth/that.options.maxWidth)+60;
			if(parseInt(that.options.maxWidth) <= that.options.orgWidth ){
				that.options.childHeight -= 60;
			}
			return that;
		},
		_start: function(e){
			var that = this;
			if(that.options.expand){   								//¹Ø±ÕÕ¹¿ª×´Ì¬
				that.options.expand = false;
				$(that.obj).height(that.options.orgHeight); 
				$(that.obj).width(that.options.orgWidth); 
				that._reset();
			}else{	
				that.options.expand = true;
				
				if(parseInt(that.options.maxWidth) > that.options.orgWidth ){
					$(that.obj).width(that.options.maxWidth);     // Õ¹¿ªÉèÖÃ¿í¶È
				}
				if($(that.obj).children().eq(0).attr('id')!=='auto-transform'){
					$(that.obj).children().wrapAll('<div id="auto-transform"></div>');
				}
				var height = parseInt(that.options.maxHeight);
				// Õ¹¿ªÉèÖÃÐÂ¸ß¶È
				(that.options.childHeight > height)?$(that.obj).height(height):$(that.obj).height(that.options.childHeight);
			}
			return that;
		},
		_startScroll: function(e){
			var that = this;
			if(that.options.childHeight==0 || !that.options.expand)return;
			that.options.pageY = window.event.touches[0].pageY;
			that.options.scrollLength = that.options.childHeight - that.options.maxHeight;
			if(that.options.scrollLength > 0){
				that.options.allowScroll = true;
			}else{
				that.options.allowScroll = false;
			}
			return that;
		},
		_scroll: function(e){
			e.stopPropagation();    // ×èÖ¹Ä¬ÈÏÊÂ¼þ
			e.preventDefault();
			var that = this;
			if(!that.options.allowScroll)return;
			var curPageY = window.event.touches[0].pageY;
			var tran = {};
			if(Math.abs(curPageY - that.options.pageY) > 5){
				tran = {y:curPageY - that.options.pageY};
				that.options.pageY = curPageY;
				that._transform(tran);
			}
			return that;
		},
		_endScroll: function(){
			var that = this;
			that.options.allowScroll = false;
			if(that.options.tY > 0){
				tran = {y:-Math.abs(0-that.options.tY)};
				that._transform(tran, 100,5,0);
			}else if(Math.abs(that.options.tY) > Math.abs(that.options.scrollLength)){
				tran = {y:Math.abs(that.options.scrollLength)-Math.abs(that.options.tY)};
				tran.y = Math.abs(tran.y);
				that._transform(tran, 100,5,0);
			}
			that.options.allowScroll = false;
			return that;
		},
		_end: function(e){
			var that = this;
			that._unbind('click', that.obj , false);
			that._unbind('touchstart', that.obj, false);
			that._unbind('touchmove', that.obj, false);
			that._unbind('touchend', that.obj, false);
			return that;
		},
		_reset: function(e){   //»¹Ô­
			var that = this;
			that.options.expand = false;
			that.options.allowScroll = false;
			var tran = {y:-that.options.tY}
			that._transform(tran);
			$(that.obj).find('#auto-transform').children().unwrap();
			return that;
		},
		_transform: function(tran, time, speed ,n){   //tran ÒÆ¶¯µÄ¾àÀë£¬time ÒÆ¶¯Ê±¼ä£¬speed ÒÆ¶¯ËÙ¶È£¬ n=0;
			var that = this;
			if(typeof(tran.x)=='undefined'){				
				tran.x = 0;
			}
			if(typeof(tran.y)=='undefined'){
				tran.y = 0;
			}			
			if(typeof(time)!='undefined' && typeof(speed)!='undefined' && time>0 && speed>0){
				n++;
				if(n >= parseInt(time/speed)){
					return;
				}
				that.options.tX += tran.x*(speed/time);
				that.options.tY += tran.y*(speed/time);
				that._pos();
				setTimeout(function(){that._transform(tran, time, speed ,n)},speed);
			}else{
				that.options.tX += tran.x;
				that.options.tY += tran.y;
				that._pos();
			}
		},
		_pos: function(){
			var that = this;
			var transform = that.options.kernel?'-'+that.options.kernel+'-transform':'transform',
				transformV = 'translate('+that.options.tX+'px,'+that.options.tY+'px) scale(1)';
			$(that.obj).find('#auto-transform')[0].style[transform] = transformV;
		},
		setBroseType: function(){  //ÅÐ¶Ïä¯ÀÀÆ÷ÄÚºË
			var that = this;
			dummyStyle = that.obj.style;
			var vendors = 't,webkitT,MozT,msT,OT'.split(','),
			t,
			i = 0,
			l = vendors.length;
			for ( ; i < l; i++ ) {
				t = vendors[i] + 'ransform';
				if ( t in dummyStyle ) {
					that.options.kernel = vendors[i].substr(0, vendors[i].length - 1);
					break;
				}
			}
		},
		_bind: function (type, el, bubble) {		
			el.addEventListener(type, this, !!bubble);
		},
		_unbind: function (type, el, bubble) {
			el.removeEventListener(type, this, !!bubble);
		},
	};
	window.txtInit = txtInit;
})(window);
;/**
	 * http://www.yunlai.cn
	 * 
	 * @author alai
	 * @email jpcomputer@163.com
	 */
;(function($) {
    $.fn.yl3d = function(options) {
        // Ä¬ÈÏ²ÎÊý
        $.fn.yl3d.defaults = {
            speed	: 'auto',       // ×ª¶¯ËÙ¶È£¬Ô½Ð¡Ô½¿ì¡£Êó±êË®Æ½Ã¿ÒÆ¶¯¶àÉÙÏñËØ£¬·­×ªÒ»ÕÅÍ¼Æ¬¡£autoÎª×Ô¶¯¼ì²â
			stopEle	: null,			// ´¥ÃþÒÆ¶¯ÉÏÏÂÊÇ·ñ´¥·¢
			startFn	: null,			// »ñÈ¡touchstart°ó¶¨º¯Êý
			moveFn	: null,			// »ñÈ¡touchmove°ó¶¨º¯Êý
			endFn	: null,			// »ñÈ¡touchend°ó¶¨º¯Êý
			V_start : null,			// ¸østartº¯Êý¸³Öµ
			
			lazy	: true,			// ÊÇ·ñÊ¹ÓÃÍ¼Æ¬ÑÓÊ±Ô¤¼ÓÔØÄ¬ÈÏÍ¼Æ¬   
        };
        
        /* ³õÊ¼Öµ¼Ì³Ð */
        var param = $.extend({},$.fn.yl3d.defaults, options);
    	
    	return this.each(function(){
    		var box  = $(this);
            var srcVal = box.find('input[type="hidden"]').val();
            var urls = srcVal.split(",");
			var imageNum = urls.length;
			var speed	= param.speed;
			var lazy = param.lazy;

    		var startPoint_x; // mousedown »ò touchstart ¿ªÊ¼Î»ÖÃXÖáµã
    		var startPoint_y; // mousedown »ò touchstart ¿ªÊ¼Î»ÖÃyÖáµã
    		var movePoint_x; // µ±Ç°ÓÐ±ä»»Í¼Æ¬£¬moveµÄÎ»ÖÃXÖáµã
    		var movePoint_y; // µ±Ç°ÓÐ±ä»»Í¼Æ¬£¬moveµÄÎ»ÖÃyÖáµã
			var position_3d = null; // ÅÐ¶Ï·½Ïò
			var moveStart_3d = true; // ÒÆ¶¯¿ªÊ¼
			
    		var currentImage = 1; // µ±Ç°ÏÔÊ¾Í¼Æ¬£¬´Ó0¿ªÊ¼
    		
    		// autoËÙ¶È·½Ê½£¬Í¼Æ¬¶àÔò·­×ªËÙ¶È¿ì£¬´ïµ½×î¼ÑÌåÑéÐ§¹û
    		if(speed == 'auto'){
    			if(imageNum >= 36){
    				speed = 10;
    			}else if(imageNum < 36 && imageNum >= 24){
    				speed = 14;
    			}else if(imageNum < 24){
    				speed = 18;
    			}
    		}

			// °ó¶¨ÊÂ¼þ
			box.on('mousedown touchstart', function(e) {
                 if (e.type == "touchstart") {
                	 startPoint_x = window.event.touches[0].pageX;
                	 startPoint_y = window.event.touches[0].pageY;
                 } else {
                	 startPoint_x = e.pageX||e.x;
                	 startPoint_y = e.pageX||e.y;
                 }
                 movePoint_x = startPoint_x;
                 movePoint_y = startPoint_y;
				
				// È¡ÏûÆäËû´¥Ãþ°ó¶¨ÊÂ¼þ
				if(param.stopEle){
					param.stopEle.off('mousedown touchstart');
					param.stopEle.off('mousemove touchmove');
					param.stopEle.off('mouseup touchend mouseout');
				}

 			});

 			box.on('mousemove touchmove', function(e){
                e.preventDefault();
 				if (startPoint_x) {
					 //»ñÈ¡ÒÆ¶¯µÄx£¬yÖµ
                     var pageX;
					 var pageY;
                     if (e.type == "touchmove") {
                         pageX = window.event.targetTouches[0].pageX;
                         pageY = window.event.targetTouches[0].pageY;
                     } else {
                         pageX = e.pageX||e.x;
                         pageY = e.pageY||e.y;
                     }
					 //ÅÐ¶ÏÊÇÉÏÏÂ»¬»¹ÊÇ×óÓÒ»¬
					 if(moveStart_3d){
						 if(param.stopEle){
							//trueÎª´¹Ö±,falseÎªË®Æ½
 					 		Math.abs(pageY-movePoint_y)>=Math.abs(pageX-movePoint_x) ? position_3d = true : position_3d = false ; 
						 }else{
							position_3d = false; 
						 }
						moveStart_3d = false;
					 }else{
						position_3d = false; 	 
					 }
					 //ÒÆ¶¯´¦Àí
					 if(!position_3d){
						 if(Math.abs(movePoint_x - pageX) >= speed) {
							box.find('img').eq(currentImage-1).css("display","none"); 
							 if (movePoint_x - pageX > 0) {
								 currentImage++;
								 if (currentImage >= imageNum) {
									 currentImage = 1;
								 }
							 } else {
								 currentImage--;
								 if (currentImage < 0) {
									 currentImage = imageNum;
								 }
							 }
							 movePoint_x = pageX;
							box.find('img').eq(currentImage-1).css("display","inline"); 
						 }
					 }else{
						param.V_start(startPoint_y);
						param.stopEle.on('mousedown touchstart',param.startFn);
						param.stopEle.on('mousemove touchmove',param.moveFn);
						param.stopEle.on('mouseup touchend mouseout',param.endFn);
					 }
 				}	
			
			})
			
			box.on('mouseup touchend mouseout', function() {
				//³õÊ¼»¯×´Ì¬Öµ
 				startPoint_x = null;
				startPoint_y = null;
				movePoint_y	= null;
				movePoint_x = null;
				// ÖØÖÃÄ¬ÈÏÒÆ¶¯ÊÇ¿ªÆô×´Ì¬
				moveStart_3d = true;
				position_3d = null;
				// ÖØÖÃÆäËû´¥Ãþ°ó¶¨ÊÂ¼þ
				if(param.stopEle){
					param.stopEle.on('mousedown touchstart',param.startFn);
					param.stopEle.on('mousemove touchmove',param.moveFn);
					param.stopEle.on('mouseup touchend mouseout',param.endFn);
				}
      		});

            // ¼ÓÔØÍ¼Æ¬
            function imginit(){
                for(var i=1; i<imageNum; i++){
                	if(lazy){
                        $('<img class="lazy-bk" />').attr({'data-bk':urls[i],'src':'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC'}).appendTo(box);
                	}else{
                        $('<img class="lazy-bk" />').attr({'src':urls[i]}).appendTo(box);
                	}

                }
                box.find('img').hide();
                box.find('img').eq(0).show();
            }

            // Ò³Ãæ½âÎöÍêºó¼ÓÔØÍ¼Æ¬
            $(function(){
            	imginit();
            })

    	});
    };
    
})(jQuery);
;/*
	 * * yl_map ²å¼þ
	 */
var ylmap = ylmap || {};
ylmap.init = function(){
	var mapBox			= $('.ylMap'),												//µØÍ¼ÈÝÆ÷Jquery¶ÔÏó
		mapVal			= mapBox.next(".mapVal").find('.address').val(),			//»ñÈ¡µ½´æ·ÅÊý¾ÝµÄJquery¶ÔÏó
		latTarget		= Number(mapBox.next(".mapVal").find('.latitude').val()),	//»ñÈ¡Ä¿±êÎ³¶È×ø±ê
		lngTarget		= Number(mapBox.next(".mapVal").find('.longitude').val()),	//»ñÈ¡Ä¿±ê¾­¶È×ø±ê
		
		way				= null,					//µ¼º½µÄ·½Ê½
		transit 		= null,					//¹«½»³µµ¼º½
		driving 		= null,					//×Ô¼Ýµ¼º½
		location_point 	= null;					// Éè±¸µ±Ç°µÄpoint¶ÔÏó

	/*
	 * * µØÍ¼³õÊ¼»¯ * _marker±êÊ¶È«¾Ö±äÁ¿ * _mapµØÍ¼È«¾Ö±äÁ¿
	 */
	var mapInit = function() {
		if(mapBox.size()>0){
			var map = new BMap.Map(mapBox.attr("id")),			//´´½¨MapÊµÀý--ÈÝÆ÷ÊÇµ±Ç°jquery¶ÔÏó
				point = new BMap.Point(lngTarget,latTarget),   //È«¾Ö±äÁ¿
				marker = new BMap.Marker(point);     			// È«¾Ö±äÁ¿µØÍ¼±ê×¢
			window.map = map;									// ½«mapÌáÉýÎªÈ«¾Ö±äÁ¿
			window.point = point;
			window.marker = marker;								// ½«markerÌáÉýÎªÈ«¾Ö±äÁ¿
			map.enableScrollWheelZoom();        		  		// ÆôÓÃ¹öÂÖ·Å´óËõÐ¡
			map.enableInertialDragging();         				// ÆôÓÃµØÍ¼ÍÏÒ·
			map.centerAndZoom(point,15);						// ½«Ä¿±ê¶¨Î»ÔÚµØÍ¼µÄÖÐÐÄ
			map.addOverlay(marker);              				// ½«±ê×¢Ìí¼Óµ½µØÍ¼ÖÐ
			mapOpenInfo();
			marker.addEventListener("click", function(e){    	//markerµã»÷ÖØÐÂ´ò¿ª´°¿Ú
				mapOpenInfo();
			});
			map.addEventListener("click", function(e){    		//µØÍ¼µã»÷´°¿Ú²»¹Ø±Õ
				return false;
			}); 
		}
	},
	
	
	/*
	**µã»÷µØÍ¼¸¡²ãÎÄ±¾µ÷ÓÃÐÅÏ¢´°¿Ú³öÏÖ
	*/
	mapOpenInfo = function(){
		var data = eval('('+mapVal+')');						// json¶ÔÏó£¬´æ·ÅÄ¿±ê¶ÔÏóÐÅÏ¢
		mapAddInfo(marker,data);								// ²¢ÇÒ´ò¿ªinfo´°¿ÚÏÔÊ¾Ä¿±êÐÅÏ¢
	},

	/**
	 **´´½¨Ä¿±êÐÅÏ¢´°¿Ú
	 * @param marker  °Ù¶ÈµØÍ¼marker±ê×¢
	 * @param data    Êý¾Ý±íplugin_store¼ÇÂ¼
	 * @text 		  ×÷ÎªJquery¶ÔÏó´«Èë
	 */
	mapAddInfo = function(marker_,data) {
		/*
		**¶¯Ì¬¼ÓÔØÐÅÏ¢ÔªËØ
		*/
		var content_infoWindow = $('<div class="infoWindow"></div>');
		content_infoWindow.append('<h4>'+data.sign_name+'</h4>')
		content_infoWindow.append('<p class="tel"><a href="tel:'+data.contact_tel+'">'+data.contact_tel+'</a></p>')
		content_infoWindow.append('<p class="address">'+data.address+'</p>')
		content_infoWindow.append('<div class="window_btn"><button class="open_navigate open_bus" onclick="open_navigate(this)">¹«½»</button><button class="open_navigate open_car" onclick="open_navigate(this)">×Ô¼Ý</button><span class="State"></span></div>')

		/*
		 * *´ò¿ªÐÅÏ¢´°¿Ú
		 */
		var opts = {    
			width : 400,    	 // ÐÅÏ¢´°¿Ú¿í¶È 0Îªauto   
			height: 0,  		 // ÐÅÏ¢´°¿Ú¸ß¶È 0Îªauto  
			title:' '
		}  

		var info = new BMap.InfoWindow(content_infoWindow[0],opts);
		marker_.openInfoWindow(info,map.getCenter());

	};

	open_navigate = function(obj){
		$(obj).hasClass("open_bus") ? way = 'bus' : way = 'car';				 // ´ò¿ªµ¼º½´°¿Ú
		navigate();
		$('.infoWindow').find('span.State').html('ÕýÔÚ¶¨Î»ÄúµÄÎ»ÖÃ£¡');	
	
	},


	//»ñÈ¡Éè±¸µ±Ç°µÄ×ø±ê²¢ÇÒ´æ·Åµ½location_pointÖÐ
	/***************************************************************************
	 * Éè±¸¶¨Î»»ñÈ¡location×ø±êÖµ
	 */
	navigate = function(){
		if (window.navigator.geolocation) {
			window.navigator.geolocation.getCurrentPosition(handleSuccess, handleError, {timeout: 10000});
		}else{
			alert('sorry£¡ÄúµÄÉè±¸²»Ö§³Ö¶¨Î»¹¦ÄÜ')
		}
	},

	/**
	 * ¶¨Î»Ê§°Ü
	 */
	handleError = function(error){
		var msg;
		switch(error.code) {
		    case error.TIMEOUT:
		    	msg = "»ñÈ¡³¬Ê±!ÇëÉÔºóÖØÊÔ!";
		        break;
		    case error.POSITION_UNAVAILABLE:
		    	msg = 'ÎÞ·¨»ñÈ¡µ±Ç°Î»ÖÃ!';
		        break;
		    case error.PERMISSION_DENIED:
		    	msg = 'ÄúÒÑ¾Ü¾ø¹²ÏíµØÀíÎ»ÖÃ!';
		        break;
		    case error.UNKNOWN_ERROR:
		    	msg = 'ÎÞ·¨»ñÈ¡µ±Ç°Î»ÖÃ!';
		        break;
		}
		if ($('.infoWindow').find('span.State').length>0) {
			$('.infoWindow').find('span.State').html(msg);
		} else {
			alert(msg);
		}
	},
	
	/**
	 * »ñµÃµ±Ç°ÊÖ»úÎ»ÖÃÐÅÏ¢
	 */
	handleSuccess = function(position){
		// »ñÈ¡µ½µ±Ç°Î»ÖÃ¾­Î³¶È 
		var coords = position.coords;
		var lat = coords.latitude;
		var lng = coords.longitude;
		location_point = new BMap.Point(lng,lat);
		$('.infoWindow').find('span.State').html('»ñÈ¡ÐÅÏ¢³É¹¦£¬ÕýÔÚ¼ÓÔØÖÐ£¡');
		// Ñ¡Ôñµ¼º½·½Ê½
		if(way=="bus")	bus_transit();
		else self_transit();
		// Õ¹¿ªµØÍ¼´°¿Ú
		mapBox.parent().addClass("open");
		// °ó¶¨È¡ÏûÊÂ¼þ
		mapBox.parent().find(".close_map").click(function(){
			mapBox.parent().removeClass("open");

			// Çå³þÂ·Ïß
			if(transit)	transit.clearResults();
			if(driving)	driving.clearResults();
			
			// ·µ»ØÖÐÐÄ¶¨Î»µã
			map.reset();
			map.centerAndZoom(point,15);
			mapBox.parent().find(".close_map").hide();
			// µ¯³öÐÅÏ¢´°¿Ú
			mapOpenInfo();

			// Ò³ÃæÇÐ»»ÊÂ¼þ°ó¶¨
			$(".m-page").on('mousedown touchstart',page_touchstart);
			$(".m-page").on('mousemove touchmove',page_touchmove);
			$(".m-page").on('mouseup touchend mouseout',page_touchend);

			// ÏÔ³öÉùÒô
			$('.fn-audio').show();
			
			// ¹Ø±Õµ¼º½ÎÄ×ÖºÍÇÐ»»°´Å¥
			$('#transit_result').removeClass("open");
			$(".transitBtn").hide();
			
			
		});

		/* È¡Ïû´¥ÃþµØÍ¼£¬Ò³ÃæÇÐ»» */
		$(".m-page").off('mousedown touchstart');
		$(".m-page").off('mousemove touchmove');
		$(".m-page").off('mouseup touchend mouseout');

	};
	
	/*
	 * *´ò¿ªµ¼º½
	 */
	$(".m-map .tit p").click(function(){
		mapBox.parent().toggleClass("open");
		if(mapBox.parent().hasClass("open")){
			//¹Ø±ÕÉùÒô
			$('.fn-audio').hide();
			/* È¡Ïû´¥ÃþµØÍ¼£¬Ò³ÃæÇÐ»» */
			$(".m-page").off('mousedown touchstart');
			$(".m-page").off('mousemove touchmove');
			$(".m-page").off('mouseup touchend mouseout');
		}else{
			//ÏÔ³öÉùÒô
			$('.fn-audio').show();
			// Ò³ÃæÇÐ»»ÊÂ¼þ°ó¶¨
			$(".m-page").on('mousedown touchstart',page_touchstart);
			$(".m-page").on('mousemove touchmove',page_touchmove);
			$(".m-page").on('mouseup touchend mouseout',page_touchend);
		}
	});


	/** ********************************************************************************************************* */
	/**
	 * »­¹«½»Â·Ïß
	 */
	bus_transit = function(){
		//Çå³þÂ·Ïß
		if(transit)	transit.clearResults();
		if(driving)	driving.clearResults();

		
		if(!location_point){
			alert('±§Ç¸£º¶¨Î»Ê§°Ü£¡');
			return;
		}
		$('.fn-audio').hide();
		if(typeof(loadingPageShow)=="function") loadingPageShow();
		$('.infoWindow').find('span.State').html('ÕýÔÚ»æÖÆ³öµ¼º½Â·Ïß');
		var transit_result = $("#transit_result") || $('<div id="transit_result"></div>');
		transit_result.appendTo(mapBox);
		transit = new BMap.TransitRoute(map, {
			renderOptions: {map: map,panel: "transit_result",autoViewport: true },onSearchComplete :searchComplete
		});
		transit.search(location_point, point);
	},

	/**
	 * »­×Ô¼ÝÂ·Ïß
		*/
	self_transit = function(){
		//Çå³þÂ·Ïß
		if(transit)	transit.clearResults();
		if(driving)	driving.clearResults();
		
		if(!location_point){
			alert('±§Ç¸£º¶¨Î»Ê§°Ü£¡');
			return;
		}
		$('.fn-audio').hide();
		if(typeof(loadingPageShow)=="function") loadingPageShow();
		$('.infoWindow').find('span.State').html('ÕýÔÚ»æÖÆ³öµ¼º½Â·Ïß');
		var transit_result = $("#transit_result") || $('<div id="transit_result"></div>');
		transit_result.appendTo(mapBox);
		driving = new BMap.DrivingRoute(map, {
				renderOptions: {map: map,panel: transit_result.attr('id'),autoViewport: true },onSearchComplete :searchComplete
			});
		driving.search(location_point, point);
	},
	/**
	 * @param result
	 * ÏßÂ·ËÑË÷»Øµ÷
	 */
	searchComplete = function(result) {
		if (result.getNumPlans() == 0) {
			
			alert('·Ç³£±§Ç¸,Î´ËÑË÷µ½¿ÉÓÃÂ·Ïß');
			// ÖØÖÃµØÍ¼
			map.reset();
			map.centerAndZoom(point,15);
			mapBox.parent().find(".close_map").hide();
			mapOpenInfo();
			$('#transit_result').removeClass("open").hide();
			$(".transitBtn").hide();
			
		}else{
			$('#transit_result').addClass("open");	
			$('.infoWindow').find('span.State').html('');
			if(!$('.transitBtn').length>0){
				$('#transit_result').after($('<p class="transitBtn close" onclick="transit_result_close()"><a href="javascript:void(0)">¹Ø±Õ</a></p>'));
				$('#transit_result').after($('<p class="transitBtn bus" onclick="bus_transit()"><a href="javascript:void(0)">¹«½»</a></p>'));
				$('#transit_result').after($('<p class="transitBtn car" onclick="self_transit()"><a href="javascript:void(0)">×Ô¼Ý</a></p>'));
			}
			mapBox.parent().find(".close_map").show();
			// ´ò¿ªµ¼º½ÎÄ×ÖºÍÇÐ»»°´Å¥
			$("#transit_result").addClass("open");
			$(".transitBtn").show();
		}
		if(typeof(loadingPageHide)=="function") loadingPageHide();
		
		if($("#transit_result").hasClass("open")){
			$(".close").find("a").html("¹Ø±Õ");
		}
		else{
			$(".close").find("a").html("´ò¿ª");
		}
		
	};
	
	/*
	 * *transit_resultµ¼º½´°¿ÚÇÐ»»
	 */
	transit_result_close = function(){
		if($("#transit_result").hasClass("open")){
			$('#transit_result').removeClass("open");
			$(".close").find("a").html("´ò¿ª");
		}
		else{
			$('#transit_result').addClass("open");
			$(".close").find("a").html("¹Ø±Õ");
		}
	};

	/*
	 * *Òì²½´´½¨¼ÓÔØÒ»¸ö½Å±¾--map
	 */
	window.mapInit = mapInit;	// ½«³õÊ¼»¯º¯ÊýÌáÉýÎªÈ«¾Öº¯Êý
								// ¿ÉÒÔµ÷ÓÃ¶¯Ì¬APIµØÍ¼
	
	function loadfunction() {  
		/*¼ÓÔØ°Ù¶ÈµØÍ¼API*/
		var script = document.createElement("script");  
		script.src = "http://api.map.baidu.com/api?v=1.4&callback=mapInit";
		document.head.appendChild(script);

		/* ¼ÓÔØ°Ù¶ÈµØÍ¼ÑùÊ½--²å¼þÑùÊ½ */
		var Style = document.createElement("style");  
		Style.type = "text/css";
		
		var style_map =
				 "#transit_result{display:none;position:absolute;top:0;left:0;width:100%;height:100%;z-index:1000;overflow-y:scroll;}"+
				 "#transit_result.open{display:block;}"+
				 "#ylMap img{width:auto;height:auto;}"+
				 "#ylMap .transitBtn {display:none;position:absolute;z-index:3000;}"+
				 "#ylMap .transitBtn a{display:block;width:80px;box-shadow:0 0 2px rgba(0,0,0,0.6) inset, 0 0 2px rgba(0,0,0,0.6);height:80px;border-radius:80%;color:#fff;background:rgba(230,45,36,0.8);text-align:center;line-height:80px;font-size:24px; font-weight:bold}"+
				 "#ylMap .transitBtn.close {top:10px;right:10px;}"+
				 "#ylMap .transitBtn.bus {top:10px;right:110px;}"+
				 "#ylMap .transitBtn.car {top:110px;right:10px;}"+
				 "#ylMap .transitBtn.bus a{background:rgba(28,237,235,0.8);}"+
				 "#ylMap .transitBtn.car a{background:rgba(89,237,37,0.8);}"+
				 ".m-map.open{height:92%;width:100%;}"+
				 "#transit_result h1{font-size:26px!important;}"+
				 "#transit_result div[onclick^='Instance']{background:none!important;}"+
				 "#transit_result span{display:inline-block;font-size:20px;padding:0 5px;}"+
				 "#transit_result table {font-size:20px!important;}"+
				 "#transit_result table td{padding:5px 10px!important;line-height:150%!important;}"+
				 ".infoWindow h4{font-size:24px;}"+
				 ".infoWindow p{margin-bottom:10px;font-size:24px;}"+
				 ".infoWindow .window_btn{font-size:24px;}"+
				 ".infoWindow .window_btn .open_navigate{display:inline-block;padding:10px 15px;margin-right:10px;border:1px solid #ccc;border-radius:6px;text-align:center;cursor:pointer;}"+
				 ".anchorBL{display:none!important;}";
		Style.innerHTML = style_map ;
		document.head.appendChild(Style);
	}
	loadfunction();
};



;/*
	 * *ÒÆ¶¯¶ËÂÖ²¥²å¼þ *Ä£¿é½á¹¹ *ylSlider *****************************
	 * 1.auto²ÎÊýÎª¿ÕÊ±£¬ÊÖ¶¯¼ÓÈëli-imgµÄ±êÇ© <div class="targetBox"> <div
	 * class="imgSlider"> <div> <ul class="imgSlider-img">
	 * 
	 * </ul> <ul class="imgSlider-btn">
	 * 
	 * </ul> </div> </div> </div> ********************************
	 * 2.auto²ÎÊý²»Îª¿ÕÊ±£¬×Ô¶¯Éú³ÉËùÐèÒªµÄli-img±êÇ© <div class="targetBox">
	 * <div class="imgSlider"> <input type="hidden" value="src" /> </div> </div>
	 */
;(function($){
	$.fn.ylSlider = function(options){
		// Ä¬ÈÏ²ÎÊý
		$.fn.ylSlider.defaults = {
			auto 		: "",			//ÊÇ·ñ×Ô¶¯Éú³Éli-img(auto-×Ô¶¯£¬¿ÕÖµÎªÊÖ¶¯)
			pageN 		: 0,			//Ê×¸ö³öÏÖµÄÍ¼Æ¬inde
			autotime 	: 3000, 		//×Ô¶¯ÂÖ²¥Ê±¼ä
			btn_auto	: true,			//ÊÇ²»ÊÇÉú³Ébtn±êÊ¶±êÖ¾
			btn_col 	: '#fff',		//°´Å¥²»ÌáÊ¾µÄÑÕÉ«
			btn_col_on	: '#21bac4',	//°´Å¥ÌáÊ¾µÄÑÕÉ«
			pos_img		: 'left' 		// ÂÖ²¥Í¼·½Ïò£¨×óÓÒ£©
		};
		
		/* ³õÊ¼Öµ¼Ì³Ð */
		var opts = $.extend({},$.fn.ylSlider.defaults, options);
		
		return this.each(function(){
			var targetBox = $(this);			// »ñÈ¡µ½²å¼þÍâ²ã¶ÔÏó£¨div-imgSlider£©
			/*
			 * *×Ô¶¯¸ù¾Ýinput´«ÈëµÄsrcÖµÉú³Éli-img±êÇ©
			 */
			if(opts.auto){
				var srcVal = targetBox.find('input[type="hidden"]').val(),		//ÂÖ²¥Í¼Æ¬µÄµØÖ·Öµ
	            	urls = srcVal.split(","),									//½«Õû¸öµØÖ·Öµ´æ·ÅÒ»¸öÊý×éÖÐ
	            	imageNum = urls.length;										// ¼ÆËãÒª¼ÓÔØµÄ¸öÊý

	            /*
				 * *Éú³ÉÂÖ²¥²å¼þÈÝÆ÷imgSlider *Éú³É2¸öulÈÝÆ÷£¬imgSlider-img ºÍ
				 * imgSlider-btn
				 */
	            var img 	=null ,
	            	imgBox  =null ,
	           		btnBox  =null ;

	            if(targetBox.find(".imgSlider").length<=0)
	            	img = $('<div></div>').addClass("imgSlider");			
	            if(targetBox.find(".imgSlider-img").length<=0)
	           		imgBox = $('<ul></ul>').addClass("imgSlider-img");		
	           	if(targetBox.find(".imgSlider-btn").length<=0&&opts.btn_auto)
	            	btnBox = $('<ul></ul>').addClass("imgSlider-btn");

	            var imgSlider = img||targetBox.find(".imgSlider");
	            // ½«Éú³ÉµÄÈÝÆ÷×·¼Óµ½targetBoxÀï
	            img ? imgSlider.append(imgBox).append(btnBox).appendTo(targetBox) : imgSlider.append(imgBox).append(btnBox);

	            function init_img(){
		            for(var i=0; i<imageNum; i++){
	                    $('<li><img/></li>').appendTo(imgBox).find('img').attr({'src':urls[i]});
	                    $('<li></li>').appendTo(btnBox);
	                    if(imageNum == 1) btnBox.hide();
	                }
                }
                $(window).on('load',init_img)

			}

			/*
			**Éú³Éli-img±êÇ©ºó£¬²úÉúµÄÂÖ²¥Ð§¹û
			*/
			var	Lists		= targetBox.find(".imgSlider .imgSlider-img"),
				btn_list	= targetBox.find(".imgSlider .imgSlider-btn").find("li"), 	//Í¼±ê°´Å¥µÄÏÔÊ¾
				move_W 		= targetBox.width(),							//»ñÈ¡Ò»¸öÍ¼Æ¬ÔªËØµÄ¿í¶È£¬positionÎ»ÒÆµÄ¿í¶È
				list		= Lists.find("li"),							//ÂÖ²¥Í¼Æ¬ÔªËØ
				imgSize 	= imageNum || list.size(),					//ÂÖ²¥Í¼µÄ¸öÊý 
				nextN		= null,										//ÏÂÒ»¸öÍ¼Æ¬³öÏÖµÄindex
				timeM 		= null,										//×Ô¶¯²¥·ÅµÄtimer¿ØÖÆÆ÷
				touchV 		= true,										//´¥Åö¿ØÖÆÖµ(ÊÇ·ñ¿ÉÒÔ´¥¶¯)
				start 		= null, 									//¿ØÖÆ¶¯»­¿ªÊ¼
				position_x	= null,										//ÅÐ¶ÏÒÆ¶¯·½ÏòÊÇ×ö»¹ÊÇÓÒ
				position 	= null,										//ÅÐ¶Ï·½ÏòÎª´¹Ö±»¹ÊÇË®Æ½	
				start_V 	= true,										//ÉèÖÃ¶¯»­¿ªÊ¼µÄ¿ØÖÆÖµ
				mouseD 		= null,										//PC¶ËÅÐ¶ÏÊó±êÊÇ·ñ°´ÏÂ		


				/* ÉèÖÃ´¥ÃþÍ¼Æ¬ÄÜÉÏÏÂ»¬¶¯µÄ²ÎÊý */
				init_x 		= null,										//´¥ÃþstartµãµÄxÖµ	
				init_y		= null,										//´¥ÃþstartµãµÄyÖµ	
				move_x		= null,										//´¥ÃþmoveµãµÄxÖµ	
				move_y		= null,										//´¥ÃþmoveµãµÄyÖµ
				first_x		= null,										
				first_y		= null,

				/*³õÊ¼»¯ÖµÉèÖÃ*/
				startN 		= opts.pageN,								//Ê×¸ö³öÏÖµÄÍ¼Æ¬index
				timeL 		= opts.autotime,							//ÂÖ²¥Ê±¼ä
				btn_col 	= opts.btn_col,								//°´Å¥²»ÌáÊ¾µÄÑÕÉ«
				btn_col_on	= opts.btn_col_on,							//°´Å¥ÌáÊ¾µÄÑÕÉ«
				pos_img 	= opts.pos_img;								// ÂÖ²¥Í¼µÄ·½Ïò£¨×óÓÒ£©
				
			
			// Ã¿Ò»¸öimgSlider-imgÄ£¿éÏÔÊ¾Ò»¸öimgList²¢ÅÐ¶ÏÊÇ²»ÊÇÓÐbtn±êÊ¶Éú³É
			list.css("display","none").eq(startN).css("display","block");			
			if( btn_list.length>=1 ) btn_list.eq(startN).css("background",btn_col_on);	
			
			/*
			 * * ÂÖ²¥Í¼´¥Ãþ¿ªÊ¼¹ö¶¯
			 */
			if(imgSize>1){
				Lists.on('touchstart',touchstart);				// ´¥Åö¿ªÊ¼ÊÂ¼þ°ó¶¨
				Lists.on('touchmove',touchmove);				// ´¥ÅömoveÊÂ¼þ°ó¶¨
				Lists.on('touchend',touchend);					// ´¥Åö½áÊøÊÂ¼þ°ó¶¨
			}
			
			function touchstart(e){					//¿ªÊ¼function
				e.preventDefault();
				 if (e.type == "touchstart") {
                     init_x = window.event.touches[0].pageX;
                     init_y = window.event.touches[0].pageY;
                 } else {
                     init_x = e.pageX||e.x;
                     init_y = e.pageY||e.y;
                     mouseD = true;
                 }
				first_x = init_x;
				first_y = init_y;
				touchV ? (start = true , start_V = false) : (start = false , start_V = true) ;
				clearInterval(timeM);
			}
			
			function touchmove(e){					//move function
				e.preventDefault();
				clearInterval(timeM);
				if (e.type == "touchmove") {
                    move_x = window.event.touches[0].pageX;
                    move_y = window.event.touches[0].pageY;
                } else {
                 	if(mouseD){
	                    move_x = e.pageX||e.x;
	                    move_y = e.pageY||e.y; 
                    }
                }
				if(start_V) touchV ? start = true  : start = false ;
                if(touchV){
 					Math.abs(move_y-init_y)>=Math.abs(move_x-init_x) ? position = true : position = false ; // ttrueÎª´¹Ö±,falseÎªË®Æ½
 				}

				if(touchV || start){
					touchV = false;
					// trueÎªÉÏÏÂÒÆ¶¯£¬falseÎª×óÓÒÒÆ¶¯
					if(position){
						var bdscrollTop = parseInt($(document.body).scrollTop());
						$(document.body).scrollTop(bdscrollTop+init_y-move_y)
					}else{

						position_x = move_x - first_x >= 0 ? true : false;	// trueÏòÓÒ£¬falseÏò×ó
						position_x ? ( startN == 0 ? nextN = imgSize-1 : nextN = startN - 1 ) : ( startN == (imgSize-1) ? nextN = 0 : nextN = startN + 1 ); 	// ÅÐ¶Ï×óÓÒ¸øÏÂÒ»¸öÍ¼Æ¬¸³Öµ
						position_x ? ( list.eq(nextN).css({'left':-move_W,'display':'block'}) ) : ( list.eq(nextN).show().css({'left':move_W,'display':'block'}) )	// ÅÐ¶Ï×óÓÒÈÃÏÂÒ»¸öÍ¼Æ¬³öÏÖ
			
						var leftS = parseInt(list.eq(startN).css("left"));
						var leftN = parseInt(list.eq(nextN).css("left"));
						list.eq(startN).css("left",leftS+move_x-init_x);
						list.eq(nextN).css("left",leftN+move_x-first_x);
						
						init_x = move_x;
					}
				}
				
			}
			
			function touchend(e){					//½áÊø function
				clearInterval(timeM);
				if(!position&&start){
					if( Math.abs(move_x - first_x) > 100 && move_x >5 ){		//move_x>5 ¹æ¶¨Ò»¶¨´¥ÃþÒÆ¶¯ÁËÒ»¶Î¾àÀëºó²Å´¥·¢Õâ¸öÐ§¹û
						list.eq(nextN).animate({'left':0},400,function(){ 
							list.eq(startN).css("display","none");
							btn_list.eq(nextN).css("background",btn_col_on)
							btn_list.eq(startN).css("background",btn_col)
							startN = nextN;
							touchV = true;
							timeM = setInterval(autoslider,timeL);
							
						})
						position_x ? list.eq(startN).animate({'left':move_W},400) : list.eq(startN).animate({'left':-move_W},400);
					}else if (Math.abs(move_x - first_x) <= 300 && move_x >5 ){
						list.eq(startN).animate({'left':0},400,function(){ 
							touchV = true;
							timeM = setInterval(autoslider,timeL);
							
						})
						position_x ? list.eq(nextN).animate({'left':-move_W},400,function(){list.eq(nextN).css("display","none")}) : 
								   list.eq(nextN).animate({'left':move_W},400,function(){list.eq(nextN).css("display","none")});
					}
				}else{
					touchV = true;
				}

				init_x 		= null,
				init_y		= null,
				move_x		= null,
				move_y		= null,
				first_x 	= null,
				first_y 	= null,
				
				mouseD 		= null,
				position 	= null,
				position_x 	= null,
				start 		= null,	//´¥Ãþ»¬¶¯¿ªÊ¼
				start_V 	= true;	// ÉèÖÃ¶¯»­¿ªÊ¼µÄ¿ØÖÆÖµ
				
			}
			
			/* ×Ô¶¯ÂÖ²¥Í¼Æ¬¿ØÖÆº¯Êý timeM¿ØÖÆÖµ */
			function autoslider(){
				touchV = false;
				if(pos_img=="right"){			//ÏòÓÒÒÆ¶¯
					startN == 0 ? nextN = imgSize-1 : nextN = startN - 1;
					list.eq(nextN).css({'left':-move_W,'display':'block'});
					list.eq(nextN).animate({'left':0},500,function(){ });
					list.eq(startN).animate({'left':move_W},500,function(){
						btn_list.eq(nextN).css("background",btn_col_on)
						btn_list.eq(startN).css("background",btn_col)
						startN = nextN; 
					});
				}else if( pos_img=="left" ){	//Ïò×óÒÆ¶¯
					startN == (imgSize-1) ? nextN = 0 : nextN = startN + 1;
					list.eq(nextN).css({'left':move_W,'display':'block'});
					list.eq(nextN).animate({'left':0},500,function(){ });
					list.eq(startN).animate({'left':-move_W},500,function(){
						btn_list.eq(nextN).css("background",btn_col_on)
						btn_list.eq(startN).css("background",btn_col)
						startN = nextN; 
					});
				}
				touchV = true;
			}
			if(imgSize>1){
				timeM = setInterval(autoslider,timeL);
			}
			
			$(window).on('load',loadfunction)
	
			function loadfunction() {  
				/*¼ÓÔØÂÖ²¥Í¼ÑùÊ½--²å¼þÑùÊ½*/
				var Style = document.createElement("style");  
				Style.type = "text/css";
				
				var style_map = 
					".imgSlider { position:relative; width:100%; height:100%; overflow:hidden; }"+
					".imgSlider .imgSlider-img { position:relative; overflow:hidden; height:100%; width:100%; }"+
					".imgSlider .imgSlider-img li { position:absolute; top:0; left:0; width:100%; height:100%; }"+
					".imgSlider .imgSlider-img li p { position:absolute; z-index:10; bottom:0; left:0; padding-left:3%; width:97%; height:2%; line-height:60px; background:rgba(0,0,0,0.7); color:#fff; font-size:25px; }"+
					".imgSlider .imgSlider-btn { position:absolute; z-index:20; bottom:15px; right:20px; }"+
					".imgSlider .imgSlider-btn li { display:inline-block; margin-left:5px; width:20px; height:20px; border-radius:50%; background:#fff; cursor:pointer; }"+
					".imgSlider .imgSlider-btn li.on { background:#21bac4; }";
					
				Style.innerHTML = style_map ;
				document.head.appendChild(Style);
			}
		});
	}
	
})(jQuery);
;
/*
 * ! pickadate.js v3.3.0, 2013/10/13 By Amsul, http://amsul.ca Hosted on
 * http://amsul.github.io/pickadate.js Licensed under MIT
 */

/*
 * jshint debug: true, devel: true, browser: true, asi: true, unused: true,
 * boss: true, eqnull: true
 */

(function ( factory ) {

    // Register as an anonymous module.
    if ( typeof define === 'function' && define.amd )
        define( 'picker', ['jquery'], factory )

    // Or using browser globals.
    else this.Picker = factory( jQuery )

}(function( $ ) {

var $document = $( document )


/**
 * The picker constructor that creates a blank picker.
 */
function PickerConstructor( ELEMENT, NAME, COMPONENT, OPTIONS ) {

    // If there¡¯s no element, return the picker constructor.
    if ( !ELEMENT ) return PickerConstructor


    var
        // The state of the picker.
        STATE = {
            id: Math.abs( ~~( Math.random() * 1e9 ) )
        },


        // Merge the defaults and options passed.
        SETTINGS = COMPONENT ? $.extend( true, {}, COMPONENT.defaults, OPTIONS ) : OPTIONS || {},


        // Merge the default classes with the settings classes.
        CLASSES = $.extend( {}, PickerConstructor.klasses(), SETTINGS.klass ),


        // The element node wrapper into a jQuery object.
        $ELEMENT = $( ELEMENT ),


        // Pseudo picker constructor.
        PickerInstance = function() {
            return this.start()
        },


        // The picker prototype.
        P = PickerInstance.prototype = {

            constructor: PickerInstance,

            $node: $ELEMENT,


            /**
             * Initialize everything
             */
            start: function() {

                // If it¡¯s already started, do nothing.
                if ( STATE && STATE.start ) return P


                // Update the picker states.
                STATE.methods = {}
                STATE.start = true
                STATE.open = false
                STATE.type = ELEMENT.type


                // Confirm focus state, convert into text input to remove UA
				// stylings,
                // and set as readonly to prevent keyboard popup.
                ELEMENT.autofocus = ELEMENT == document.activeElement
                ELEMENT.type = 'text'
                ELEMENT.readOnly = true


                // Create a new picker component with the settings.
                P.component = new COMPONENT( P, SETTINGS )


                // Create the picker root with a new wrapped holder and bind the
				// events.
                P.$root = $( PickerConstructor._.node( 'div', createWrappedComponent(), CLASSES.picker ) ).
                    on({

                        // When something within the root is focused, stop from bubbling
                        // to the doc and remove the ¡°focused¡± state from the
						// root.
                        focusin: function( event ) {
                            P.$root.removeClass( CLASSES.focused )
                            event.stopPropagation()
                        },

                        // If the click is not on the root holder, stop it from bubbling to the doc.
                        'mousedown click': function( event ) {
                            if ( event.target != P.$root.children()[ 0 ] ) {
                                event.stopPropagation()
                            }
                        }
                    }).

                    // If there¡¯s a click on an actionable element, carry out
					// the actions.
                    on( 'click', '[data-pick], [data-nav], [data-clear]', function() {

                        var $target = $( this ),
                            targetData = $target.data(),
                            targetDisabled = $target.hasClass( CLASSES.navDisabled ) || $target.hasClass( CLASSES.disabled ),

                            // * For IE, non-focusable elements can be active elements as well
                            // (http://stackoverflow.com/a/2684561).
                            activeElement = document.activeElement
                            activeElement = activeElement && ( activeElement.type || activeElement.href )

                        // If it¡¯s disabled or nothing inside is actively
						// focused, re-focus the element.
                        if ( targetDisabled || !$.contains( P.$root[0], activeElement ) ) {
                            ELEMENT.focus()
                        }

                        // If something is superficially changed, update the `highlight` based on the `nav`.
                        if ( targetData.nav && !targetDisabled ) {
                            P.set( 'highlight', P.component.item.highlight, { nav: targetData.nav } )
                        }

                        // If something is picked, set `select` then close with focus.
                        else if ( PickerConstructor._.isInteger( targetData.pick ) && !targetDisabled ) {
                            P.set( 'select', targetData.pick ).close( true )
							data_set_value = targetData.pick;
                        }

                        // If a ¡°clear¡± button is pressed, empty the values and close with focus.
                        else if ( targetData.clear ) {
							//ÐÞ¸Äclear°´Å¥²»´¥·¢Çå³ý¹¦ÄÜ
                            // P.clear().close( true )
							// P.close( true )
							P.set( 'select', data_set_value ).close( true )
                        }
                    }) // P.$root


                // If there¡¯s a format for the hidden input element, create the
				// element.
                if ( SETTINGS.formatSubmit ) {

                    P._hidden = $(
                        '<input ' +
                        'type=hidden ' +

                        // Create the name by using the original input plus a
						// prefix and suffix.
                        'name="' + ( typeof SETTINGS.hiddenPrefix == 'string' ? SETTINGS.hiddenPrefix : '' ) +
                            ELEMENT.name +
                            ( typeof SETTINGS.hiddenSuffix == 'string' ? SETTINGS.hiddenSuffix : '_submit' ) +
                        '"' +

                        // If the element has a `data-value`, set the element
						// `value` as well.
                        ( $ELEMENT.data( 'value' ) ?
                            ' value="' + PickerConstructor._.trigger( P.component.formats.toString, P.component, [ SETTINGS.formatSubmit, P.component.item.select ] ) + '"' :
                            ''
                        ) +
                        '>'
                    )[ 0 ]
                }


                // Add the class and bind the events on the element.
                $ELEMENT.addClass( CLASSES.input ).

                    // On focus/click, open the picker and adjust the root
					// ¡°focused¡± state.
                    on( 'focus.P' + STATE.id + ' click.P' + STATE.id, focusToOpen ).

                    // If the value changes, update the hidden input with the
					// correct format.
                    on( 'change.P' + STATE.id, function() {
                        if ( P._hidden ) {
                            P._hidden.value = ELEMENT.value ? PickerConstructor._.trigger( P.component.formats.toString, P.component, [ SETTINGS.formatSubmit, P.component.item.select ] ) : ''
                        }
                    }).

                    // Handle keyboard event based on the picker being opened or
					// not.
                    on( 'keydown.P' + STATE.id, function( event ) {

                        var keycode = event.keyCode,

                            // Check if one of the delete keys was pressed.
                            isKeycodeDelete = /^(8|46)$/.test( keycode )

                        // For some reason IE clears the input value on
						// ¡°escape¡±.
                        if ( keycode == 27 ) {
                            P.close()
                            return false
                        }

                        // Check if `space` or `delete` was pressed or the picker is closed with a key movement.
                        if ( keycode == 32 || isKeycodeDelete || !STATE.open && P.component.key[ keycode ] ) {

                            // Prevent it from moving the page and bubbling to doc.
                            event.preventDefault()
                            event.stopPropagation()

                            // If `delete` was pressed, clear the values and
							// close the picker.
                            // Otherwise open the picker.
                            if ( isKeycodeDelete ) { P.clear().close() }
                            else { P.open() }
                        }
                    }).

                    // If there¡¯s a `data-value`, update the value of the
					// element.
                    val( $ELEMENT.data( 'value' ) ? PickerConstructor._.trigger( P.component.formats.toString, P.component, [ SETTINGS.format, P.component.item.select ] ) : ELEMENT.value ).

                    // Insert the hidden input after the element.
                    after( P._hidden ).

                    // Store the picker data by component name.
                    data( NAME, P )


                // Insert the root as specified in the settings.
                if ( SETTINGS.container ) $( SETTINGS.container ).append( P.$root )
                else $ELEMENT.after( P.$root )


                // Bind the default component and settings events.
                P.on({
                    start: P.component.onStart,
                    render: P.component.onRender,
                    stop: P.component.onStop,
                    open: P.component.onOpen,
                    close: P.component.onClose,
                    set: P.component.onSet
                }).on({
                    start: SETTINGS.onStart,
                    render: SETTINGS.onRender,
                    stop: SETTINGS.onStop,
                    open: SETTINGS.onOpen,
                    close: SETTINGS.onClose,
                    set: SETTINGS.onSet
                })


                // If the element has autofocus, open the picker.
                if ( ELEMENT.autofocus ) {
                    P.open()
                }


                // Trigger queued the ¡°start¡± and ¡°render¡± events.
                return P.trigger( 'start' ).trigger( 'render' )
            }, //start


            /**
			 * Render a new picker
			 */
            render: function( entireComponent ) {

                // Insert a new component holder in the root or box.
                if ( entireComponent ) P.$root.html( createWrappedComponent() )
                else P.$root.find( '.' + CLASSES.box ).html( P.component.nodes( STATE.open ) )

                // Trigger the queued ¡°render¡± events.
                return P.trigger( 'render' )
            }, //render


            /**
			 * Destroy everything
			 */
            stop: function() {

                // If it¡¯s already stopped, do nothing.
                if ( !STATE.start ) return P

                // Then close the picker.
                P.close()

                // Remove the hidden field.
                if ( P._hidden ) {
                    P._hidden.parentNode.removeChild( P._hidden )
                }

                // Remove the root.
                P.$root.remove()

                // Remove the input class, unbind the events, and remove the
				// stored data.
                $ELEMENT.removeClass( CLASSES.input ).off( '.P' + STATE.id ).removeData( NAME )

                // Restore the element state
                ELEMENT.type = STATE.type
                ELEMENT.readOnly = false

                // Trigger the queued ¡°stop¡± events.
                P.trigger( 'stop' )

                // Reset the picker states.
                STATE.methods = {}
                STATE.start = false

                return P
            }, //stop


            /*
			 * Open up the picker
			 */
            open: function( dontGiveFocus ) {

                // If it¡¯s already open, do nothing.
                if ( STATE.open ) return P

                // Add the ¡°active¡± class.
                $ELEMENT.addClass( CLASSES.active )

                // Add the ¡°opened¡± class to the picker root.
                P.$root.addClass( CLASSES.opened )

                // If we have to give focus, bind the element and doc events.
                if ( dontGiveFocus !== false ) {

                    // Set it as open.
                    STATE.open = true

                    // Pass focus to the element¡¯s jQuery object.
                    $ELEMENT.trigger( 'focus' )

                    // Bind the document events.
                    $document.on( 'click.P' + STATE.id + ' focusin.P' + STATE.id, function( event ) {

                        // If the target of the event is not the element, close the picker picker.
                        // * Don¡¯t worry about clicks or focusins on the root
						// because those don¡¯t bubble up.
                        // Also, for Firefox, a click on an `option` element
						// bubbles up directly
                        // to the doc. So make sure the target wasn't the doc.
                        if ( event.target != ELEMENT && event.target != document ) P.close()

                    }).on( 'keydown.P' + STATE.id, function( event ) {

                        var
                            // Get the keycode.
                            keycode = event.keyCode,

                            // Translate that to a selection change.
                            keycodeToMove = P.component.key[ keycode ],

                            // Grab the target.
                            target = event.target


                        // On escape, close the picker and give focus.
                        if ( keycode == 27 ) {
                            P.close( true )
                        }


                        // Check if there is a key movement or ¡°enter¡± keypress on the element.
                        else if ( target == ELEMENT && ( keycodeToMove || keycode == 13 ) ) {

                            // Prevent the default action to stop page movement.
                            event.preventDefault()

                            // Trigger the key movement action.
                            if ( keycodeToMove ) {
                                PickerConstructor._.trigger( P.component.key.go, P, [ PickerConstructor._.trigger( keycodeToMove ) ] )
                            }

                            // On ¡°enter¡±, if the highlighted item isn¡¯t disabled, set the value and close.
                            else if ( !P.$root.find( '.' + CLASSES.highlighted ).hasClass( CLASSES.disabled ) ) {
                                P.set( 'select', P.component.item.highlight ).close()
                            }
                        }


                        // If the target is within the root and ¡°enter¡± is pressed,
                        // prevent the default action and trigger a click on the target instead.
                        else if ( $.contains( P.$root[0], target ) && keycode == 13 ) {
                            event.preventDefault()
                            target.click()
                        }
                    })
                }
				
                // Trigger the queued ¡°open¡± events.
                return P.trigger( 'open' )
            }, //open


            /**
			 * Close the picker
			 */
            close: function( giveFocus ) {

                // If we need to give focus, do it before changing states.
                if ( giveFocus ) {
                    // ....ah yes! It would¡¯ve been incomplete without a crazy workaround for IE :|
                    // The focus is triggered *after* the close has completed -
					// causing it
                    // to open again. So unbind and rebind the event at the next
					// tick.
                    $ELEMENT.off( 'focus.P' + STATE.id ).trigger( 'focus' )
                    setTimeout( function() {
                        $ELEMENT.on( 'focus.P' + STATE.id, focusToOpen )
                    }, 0 )
                }

                // Remove the ¡°active¡± class.
                $ELEMENT.removeClass( CLASSES.active )

                // Remove the ¡°opened¡± and ¡°focused¡± class from the picker
				// root.
                P.$root.removeClass( CLASSES.opened + ' ' + CLASSES.focused )

                // If it¡¯s open, update the state.
                if ( STATE.open ) {

                    // Set it as closed.
                    STATE.open = false

                    // Unbind the document events.
                    $document.off( '.P' + STATE.id )
                }
				
                // Trigger the queued ¡°close¡± events.
                return P.trigger( 'close' )
            }, //close


            /**
			 * Clear the values
			 */
            clear: function() {
                return P.set( 'clear' )
            }, //clear


            /**
			 * Set something
			 */
            set: function( thing, value, options ) {

                var thingItem, thingValue,
                    thingIsObject = PickerConstructor._.isObject( thing ),
                    thingObject = thingIsObject ? thing : {}

                if ( thing ) {

                    // If the thing isn¡¯t an object, make it one.
                    if ( !thingIsObject ) {
                        thingObject[ thing ] = value
                    }

                    // Go through the things of items to set.
                    for ( thingItem in thingObject ) {

                        // Grab the value of the thing.
                        thingValue = thingObject[ thingItem ]

                        // First, if the item exists and there¡¯s a value, set
						// it.
                        if ( P.component.item[ thingItem ] ) {
                            P.component.set( thingItem, thingValue, options || {} )
                        }

                        // Then, check to update the element value and broadcast a change.
                        if ( thingItem == 'select' || thingItem == 'clear' ) {
                            $ELEMENT.val( thingItem == 'clear' ? '' :
                                PickerConstructor._.trigger( P.component.formats.toString, P.component, [ SETTINGS.format, P.component.get( thingItem ) ] )
                            ).trigger( 'change' )
                        }
                    }
					
                    // Render a new picker.
                    P.render()
                }
				
				
                // Trigger queued ¡°set¡± events and pass the `thingObject`.
                return P.trigger( 'set', thingObject )
            }, //set


            /**
			 * Get something
			 */
            get: function( thing, format ) {

                // Make sure there¡¯s something to get.
                thing = thing || 'value'

                // If a picker state exists, return that.
                if ( STATE[ thing ] != null ) {
                    return STATE[ thing ]
                }

                // Return the value, if that.
                if ( thing == 'value' ) {
                    return ELEMENT.value
                }

                // Check if a component item exists, return that.
                if ( P.component.item[ thing ] ) {
                    if ( typeof format == 'string' ) {
                        return PickerConstructor._.trigger( P.component.formats.toString, P.component, [ format, P.component.get( thing ) ] )
                    }
                    return P.component.get( thing )
                }
            }, //get



            /**
			 * Bind events on the things.
			 */
            on: function( thing, method ) {

                var thingName, thingMethod,
                    thingIsObject = PickerConstructor._.isObject( thing ),
                    thingObject = thingIsObject ? thing : {}

                if ( thing ) {

                    // If the thing isn¡¯t an object, make it one.
                    if ( !thingIsObject ) {
                        thingObject[ thing ] = method
                    }

                    // Go through the things to bind to.
                    for ( thingName in thingObject ) {

                        // Grab the method of the thing.
                        thingMethod = thingObject[ thingName ]

                        // Make sure the thing methods collection exists.
                        STATE.methods[ thingName ] = STATE.methods[ thingName ] || []

                        // Add the method to the relative method collection.
                        STATE.methods[ thingName ].push( thingMethod )
                    }
                }

                return P
            }, //on


            /**
			 * Fire off method events.
			 */
            trigger: function( name, data ) {
                var methodList = STATE.methods[ name ]
                if ( methodList ) {
                    methodList.map( function( method ) {
                        PickerConstructor._.trigger( method, P, [ data ] )
                    })
                }
				switch(name){
					case 'open':
						/*
						** APP_car pageChange-fn
						*/
						if(typeof clearPageChange ==="function" ) clearPageChange();
						break;
					case 'close':
                        /*
                        ** APP_car pageChange-fn
                        */
                        if(typeof backPageChange ==="function" ) setTimeout(backPageChange,300);
                        break;
					case 'set':
						/*
						** APP_car pageChange-fn
						*/
						if(typeof backPageChange ==="function" ) setTimeout(backPageChange,300);
						break;
				}
                return P
            } //trigger
        } //PickerInstance.prototype


    /**
	 * Wrap the picker holder components together.
	 */
    function createWrappedComponent() {

        // Create a picker wrapper holder
        return PickerConstructor._.node( 'div',

            // Create a picker wrapper node
            PickerConstructor._.node( 'div',

                // Create a picker frame
                PickerConstructor._.node( 'div',

                    // Create a picker box node
                    PickerConstructor._.node( 'div',

                        // Create the components nodes.
                        P.component.nodes( STATE.open ),

                        // The picker box class
                        CLASSES.box
                    ),

                    // Picker wrap class
                    CLASSES.wrap
                ),

                // Picker frame class
                CLASSES.frame
            ),

            // Picker holder class
            CLASSES.holder
        ) // endreturn
    } //createWrappedComponent


    // Separated for IE
    function focusToOpen( event ) {

        // Stop the event from propagating to the doc.
        event.stopPropagation()

        // If it¡¯s a focus event, add the ¡°focused¡± class to the root.
        if ( event.type == 'focus' ) P.$root.addClass( CLASSES.focused )

        // And then finally open the picker.
        P.open()
    }


    // Return a new picker instance.
    return new PickerInstance()
} //PickerConstructor



/**
 * The default classes and prefix to use for the HTML classes.
 */
PickerConstructor.klasses = function( prefix ) {
    prefix = prefix || 'picker'
    return {

        picker: prefix,
        opened: prefix + '--opened',
        focused: prefix + '--focused',

        input: prefix + '__input',
        active: prefix + '__input--active',

        holder: prefix + '__holder',

        frame: prefix + '__frame',
        wrap: prefix + '__wrap',

        box: prefix + '__box'
    }
} //PickerConstructor.klasses



/**
 * PickerConstructor helper methods.
 */
PickerConstructor._ = {

    /**
     * Create a group of nodes. Expects:
     * `
        {
            min:    {Integer},
            max:    {Integer},
            i:      {Integer},
            node:   {String},
            item:   {Function}
        }
     * `
     */
    group: function( groupObject ) {

        var
            // Scope for the looped object
            loopObjectScope,

            // Create the nodes list
            nodesList = '',

            // The counter starts from the `min`
            counter = PickerConstructor._.trigger( groupObject.min, groupObject )


        // Loop from the `min` to `max`, incrementing by `i`
        for ( ; counter <= PickerConstructor._.trigger( groupObject.max, groupObject, [ counter ] ); counter += groupObject.i ) {

            // Trigger the `item` function within scope of the object
            loopObjectScope = PickerConstructor._.trigger( groupObject.item, groupObject, [ counter ] )

            // Splice the subgroup and create nodes out of the sub nodes
            nodesList += PickerConstructor._.node(
                groupObject.node,
                loopObjectScope[ 0 ],   // the node
                loopObjectScope[ 1 ],   // the classes
                loopObjectScope[ 2 ]    // the attributes
            )
        }

        // Return the list of nodes
        return nodesList
    }, //group


    /**
	 * Create a dom node string
	 */
    node: function( wrapper, item, klass, attribute ) {

        // If the item is false-y, just return an empty string
        if ( !item ) return ''

        // If the item is an array, do a join
        item = $.isArray( item ) ? item.join( '' ) : item

        // Check for the class
        klass = klass ? ' class="' + klass + '"' : ''

        // Check for any attributes
        attribute = attribute ? ' ' + attribute : ''

        // Return the wrapped item
        return '<' + wrapper + klass + attribute + '>' + item + '</' + wrapper + '>'
    }, //node


    /**
	 * Lead numbers below 10 with a zero.
	 */
    lead: function( number ) {
        return ( number < 10 ? '0': '' ) + number
    },


    /**
     * Trigger a function otherwise return the value.
     */
    trigger: function( callback, scope, args ) {
        return typeof callback == 'function' ? callback.apply( scope, args || [] ) : callback
    },


    /**
     * If the second character is a digit, length is 2 otherwise 1.
     */
    digits: function( string ) {
        return ( /\d/ ).test( string[ 1 ] ) ? 2 : 1
    },


    /**
     * Tell if something is an object.
     */
    isObject: function( value ) {
        return {}.toString.call( value ).indexOf( 'Object' ) > -1
    },


    /**
     * Tell if something is a date object.
     */
    isDate: function( value ) {
        return {}.toString.call( value ).indexOf( 'Date' ) > -1 && this.isInteger( value.getDate() )
    },


    /**
     * Tell if something is an integer.
     */
    isInteger: function( value ) {
        return {}.toString.call( value ).indexOf( 'Number' ) > -1 && value % 1 === 0
    }
} //PickerConstructor._



/**
 * Extend the picker with a component and defaults.
 */
PickerConstructor.extend = function( name, Component ) {

    // Extend jQuery.
    $.fn[ name ] = function( options, action ) {

        // Grab the component data.
        var componentData = this.data( name )

        // If the picker is requested, return the data object.
        if ( options == 'picker' ) {
            return componentData
        }

        // If the component data exists and `options` is a string, carry out the action.
        if ( componentData && typeof options == 'string' ) {
            PickerConstructor._.trigger( componentData[ options ], componentData, [ action ] )
            return this
        }

        // Otherwise go through each matched element and if the component
        // doesn¡¯t exist, create a new picker using `this` element
        // and merging the defaults and options with a deep copy.
        return this.each( function() {
            var $this = $( this )
            if ( !$this.data( name ) ) {
                new PickerConstructor( this, name, Component, options )
            }
        })
    }

    // Set the defaults.
    $.fn[ name ].defaults = Component.defaults
} //PickerConstructor.extend



// Expose the picker constructor.
return PickerConstructor


}));

/*
 * * Æû³µÄ£°æµ¥¶À¿ØÖÆÒ³ÃæÇÐ»»js
 */
var data_set_value;
function clearPageChange(e){
	$(".m-page").off('mousedown touchstart');
	$(".m-page").off('mousemove touchmove');
	$(".m-page").off('mouseup touchend mouseout');
}
function backPageChange(e){
	DNmove = true;
	$(".m-page").on('mousedown touchstart',page_touchstart);
	$(".m-page").on('mousemove touchmove',page_touchmove);
	$(".m-page").on('mouseup touchend mouseout',page_touchend);
	DNmove = false;
}


;
/*
 * ! Date picker for pickadate.js v3.3.0
 * http://amsul.github.io/pickadate.js/date.htm
 */

/*
 * jshint debug: true, devel: true, browser: true, asi: true, unused: true,
 * boss: true
 */

(function ( factory ) {

    // Register as an anonymous module.
    if ( typeof define === 'function' && define.amd )
        define( ['picker','jquery'], factory )

    // Or using browser globals.
    else factory( Picker, jQuery )

}(function( Picker, $ ) {


/**
 * Globals and constants
 */
var DAYS_IN_WEEK = 7,
    WEEKS_IN_CALENDAR = 6



/**
 * The date picker constructor
 */
function DatePicker( picker, settings ) {

    var calendar = this,
        elementValue = picker.$node[ 0 ].value,
        elementDataValue = picker.$node.data( 'value' ),
        valueString = elementDataValue || elementValue,
        formatString = elementDataValue ? settings.formatSubmit : settings.format,
        isRTL = function() {
            return getComputedStyle( picker.$root[0] ).direction === 'rtl'
        }

    calendar.settings = settings

    // The queue of methods that will be used to build item objects.
    calendar.queue = {
        min: 'measure create',
        max: 'measure create',
        now: 'now create',
        select: 'parse create validate',
        highlight: 'navigate create validate',
        view: 'create validate viewset',
        disable: 'flipItem',
        enable: 'flipItem'
    }

    // The component's item object.
    calendar.item = {}

    calendar.item.disable = ( settings.disable || [] ).slice( 0 )
    calendar.item.enable = -(function( collectionDisabled ) {
        return collectionDisabled[ 0 ] === true ? collectionDisabled.shift() : -1
    })( calendar.item.disable )

    calendar.
        set( 'min', settings.min ).
        set( 'max', settings.max ).
        set( 'now' ).

        // Setting the `select` also sets the `highlight` and `view`.
        set( 'select',

            // Use the value provided or default to selecting ¡°today¡±.
            valueString || calendar.item.now,
            {
                // Use the appropriate format.
                format: formatString,

                // Set user-provided month data as true when there is a
                // ¡°mm¡± or ¡°m¡± used in the relative format string.
                data: (function( formatArray ) {
                    return valueString && ( formatArray.indexOf( 'mm' ) > -1 || formatArray.indexOf( 'm' ) > -1 )
                })( calendar.formats.toArray( formatString ) )
            }
        )


    // The keycode to movement mapping.
    calendar.key = {
        40: 7, // Down
        38: -7, // Up
        39: function() { return isRTL() ? -1 : 1 }, // Right
        37: function() { return isRTL() ? 1 : -1 }, // Left
        go: function( timeChange ) {
            calendar.set( 'highlight', [ calendar.item.highlight.year, calendar.item.highlight.month, calendar.item.highlight.date + timeChange ], { interval: timeChange } )
            this.render()
        }
    }


    // Bind some picker events.
    picker.
        on( 'render', function() {
            picker.$root.find( '.' + settings.klass.selectMonth ).on( 'change', function() {
                picker.set( 'highlight', [ picker.get( 'view' ).year, this.value, picker.get( 'highlight' ).date ] )
                picker.$root.find( '.' + settings.klass.selectMonth ).trigger( 'focus' )
            })
            picker.$root.find( '.' + settings.klass.selectYear ).on( 'change', function() {
                picker.set( 'highlight', [ this.value, picker.get( 'view' ).month, picker.get( 'highlight' ).date ] )
                picker.$root.find( '.' + settings.klass.selectYear ).trigger( 'focus' )
            })
        }).
        on( 'open', function() {
            picker.$root.find( 'button, select' ).attr( 'disabled', false )
        }).
        on( 'close', function() {
            picker.$root.find( 'button, select' ).attr( 'disabled', true )
        })

} //DatePicker


/**
 * Set a datepicker item object.
 */
DatePicker.prototype.set = function( type, value, options ) {

    var calendar = this

    // Go through the queue of methods, and invoke the function. Update this
    // as the time unit, and set the final resultant as this item type.
    // * In the case of `enable`, keep the queue but set `disable` instead.
    // And in the case of `flip`, keep the queue but set `enable` instead.
    calendar.item[ ( type == 'enable' ? 'disable' : type == 'flip' ? 'enable' : type ) ] = calendar.queue[ type ].split( ' ' ).map( function( method ) {
        return value = calendar[ method ]( type, value, options )
    }).pop()

    // Check if we need to cascade through more updates.
    if ( type == 'select' ) {
        calendar.set( 'highlight', calendar.item.select, options )
    }
    else if ( type == 'highlight' ) {
        calendar.set( 'view', calendar.item.highlight, options )
    }
    else if ( ( type == 'flip' || type == 'min' || type == 'max' || type == 'disable' || type == 'enable' ) && calendar.item.select && calendar.item.highlight ) {
        calendar.
            set( 'select', calendar.item.select, options ).
            set( 'highlight', calendar.item.highlight, options )
    }

    return calendar
} //DatePicker.prototype.set


/**
 * Get a datepicker item object.
 */
DatePicker.prototype.get = function( type ) {
    return this.item[ type ]
} //DatePicker.prototype.get


/**
 * Create a picker date object.
 */
DatePicker.prototype.create = function( type, value, options ) {

    var isInfiniteValue,
        calendar = this

    // If there¡¯s no value, use the type as the value.
    value = value === undefined ? type : value


    // If it¡¯s infinity, update the value.
    if ( value == -Infinity || value == Infinity ) {
        isInfiniteValue = value
    }

    // If it¡¯s an object, use the native date object.
    else if ( Picker._.isObject( value ) && Picker._.isInteger( value.pick ) ) {
        value = value.obj
    }

    // If it¡¯s an array, convert it into a date and make sure
    // that it¡¯s a valid date ¨C otherwise default to today.
    else if ( $.isArray( value ) ) {
        value = new Date( value[ 0 ], value[ 1 ], value[ 2 ] )
        value = Picker._.isDate( value ) ? value : calendar.create().obj
    }

    // If it¡¯s a number or date object, make a normalized date.
    else if ( Picker._.isInteger( value ) || Picker._.isDate( value ) ) {
        value = calendar.normalize( new Date( value ), options )
    }

    // If it¡¯s a literal true or any other case, set it to now.
    else /* if ( value === true ) */ {
        value = calendar.now( type, value, options )
    }

    // Return the compiled object.
    return {
        year: isInfiniteValue || value.getFullYear(),
        month: isInfiniteValue || value.getMonth(),
        date: isInfiniteValue || value.getDate(),
        day: isInfiniteValue || value.getDay(),
        obj: isInfiniteValue || value,
        pick: isInfiniteValue || value.getTime()
    }
} //DatePicker.prototype.create


/**
 * Get the date today.
 */
DatePicker.prototype.now = function( type, value, options ) {
    value = new Date()
    if ( options && options.rel ) {
        value.setDate( value.getDate() + options.rel )
    }
    return this.normalize( value, options )
} //DatePicker.prototype.now


/**
 * Navigate to next/prev month.
 */
DatePicker.prototype.navigate = function( type, value, options ) {

    if ( Picker._.isObject( value ) ) {

        var targetDateObject = new Date( value.year, value.month + ( options && options.nav ? options.nav : 0 ), 1 ),
            year = targetDateObject.getFullYear(),
            month = targetDateObject.getMonth(),
            date = value.date

        // Make sure the date is valid and if the month we¡¯re going to doesn¡¯t
		// have enough
        // days, keep decreasing the date until we reach the month¡¯s last date.
        while ( Picker._.isDate( targetDateObject ) && new Date( year, month, date ).getMonth() !== month ) {
            date -= 1
        }

        value = [ year, month, date ]
    }

    return value
} //DatePicker.prototype.navigate


/**
 * Normalize a date by setting the hours to midnight.
 */
DatePicker.prototype.normalize = function( value/* , options */ ) {
    value.setHours( 0, 0, 0, 0 )
    return value
}


/**
 * Measure the range of dates.
 */
DatePicker.prototype.measure = function( type, value/* , options */ ) {

    var calendar = this

    // If it's anything false-y, remove the limits.
    if ( !value ) {
        value = type == 'min' ? -Infinity : Infinity
    }

    // If it's an integer, get a date relative to today.
    else if ( Picker._.isInteger( value ) ) {
        value = calendar.now( type, value, { rel: value } )
    }

    return value
} ///DatePicker.prototype.measure


/**
 * Create a viewset object based on navigation.
 */
DatePicker.prototype.viewset = function( type, dateObject/* , options */ ) {
    return this.create([ dateObject.year, dateObject.month, 1 ])
}


/**
 * Validate a date as enabled and shift if needed.
 */
DatePicker.prototype.validate = function( type, dateObject, options ) {

    var calendar = this,

        // Keep a reference to the original date.
        originalDateObject = dateObject,

        // Make sure we have an interval.
        interval = options && options.interval ? options.interval : 1,

        // Check if the calendar enabled dates are inverted.
        isInverted = calendar.item.enable === -1,

        // Check if we have any enabled dates after/before now.
        hasEnabledBeforeTarget, hasEnabledAfterTarget,

        // The min & max limits.
        minLimitObject = calendar.item.min,
        maxLimitObject = calendar.item.max,

        // Check if we¡¯ve reached the limit during shifting.
        reachedMin, reachedMax,

        // Check if the calendar is inverted and at least one weekday is enabled.
        hasEnabledWeekdays = isInverted && calendar.item.disable.filter( function( value ) {

            // If there¡¯s a date, check where it is relative to the target.
            if ( $.isArray( value ) ) {
                var dateTime = calendar.create( value ).pick
                if ( dateTime < dateObject.pick ) hasEnabledBeforeTarget = true
                else if ( dateTime > dateObject.pick ) hasEnabledAfterTarget = true
            }

            // Return only integers for enabled weekdays.
            return Picker._.isInteger( value )
        }).length



    // Cases to validate for:
    // [1] Not inverted and date disabled.
    // [2] Inverted and some dates enabled.
    // [3] Out of range.
    //
    // Cases to **not** validate for:
    // ? Navigating months.
    // ? Not inverted and date enabled.
    // ? Inverted and all dates disabled.
    // ? ..and anything else.
    if ( !options.nav ) if (
        /* 1 */ ( !isInverted && calendar.disabled( dateObject ) ) ||
        /* 2 */ ( isInverted && calendar.disabled( dateObject ) && ( hasEnabledWeekdays || hasEnabledBeforeTarget || hasEnabledAfterTarget ) ) ||
        /* 3 */ ( dateObject.pick <= minLimitObject.pick || dateObject.pick >= maxLimitObject.pick )
    ) {


        // When inverted, flip the direction if there aren¡¯t any enabled weekdays
        // and there are no enabled dates in the direction of the interval.
        if ( isInverted && !hasEnabledWeekdays && ( ( !hasEnabledAfterTarget && interval > 0 ) || ( !hasEnabledBeforeTarget && interval < 0 ) ) ) {
            interval *= -1
        }


        // Keep looping until we reach an enabled date.
        while ( calendar.disabled( dateObject ) ) {


            // If we¡¯ve looped into the next/prev month, return to the original date and flatten the interval.
            if ( Math.abs( interval ) > 1 && ( dateObject.month < originalDateObject.month || dateObject.month > originalDateObject.month ) ) {
                dateObject = originalDateObject
                interval = Math.abs( interval ) / interval
            }


            // If we¡¯ve reached the min/max limit, reverse the direction and flatten the interval.
            if ( dateObject.pick <= minLimitObject.pick ) {
                reachedMin = true
                interval = 1
            }
            else if ( dateObject.pick >= maxLimitObject.pick ) {
                reachedMax = true
                interval = -1
            }


            // If we¡¯ve reached both limits, just break out of the loop.
            if ( reachedMin && reachedMax ) {
                break
            }


            // Finally, create the shifted date using the interval and keep looping.
            dateObject = calendar.create([ dateObject.year, dateObject.month, dateObject.date + interval ])
        }

    } //endif


    // Return the date object settled on.
    return dateObject
} //DatePicker.prototype.validate


/**
 * Check if a date is disabled.
 */
DatePicker.prototype.disabled = function( dateObject ) {

    var calendar = this,

        // Filter through the disabled dates to check if this is one.
        isDisabledDate = !!calendar.item.disable.filter( function( dateToDisable ) {

            // If the date is a number, match the weekday with 0index and `firstDay` check.
            if ( Picker._.isInteger( dateToDisable ) ) {
                return dateObject.day === ( calendar.settings.firstDay ? dateToDisable : dateToDisable - 1 ) % 7
            }

            // If it¡¯s an array or a native JS date, create and match the exact date.
            if ( $.isArray( dateToDisable ) || Picker._.isDate( dateToDisable ) ) {
                return dateObject.pick === calendar.create( dateToDisable ).pick
            }
        }).length


    // Check the calendar ¡°enabled¡± flag and respectively flip the
    // disabled state. Then also check if it¡¯s beyond the min/max limits.
    return calendar.item.enable === -1 ? !isDisabledDate : isDisabledDate ||
        dateObject.pick < calendar.item.min.pick ||
        dateObject.pick > calendar.item.max.pick

} //DatePicker.prototype.disabled


/**
 * Parse a string into a usable type.
 */
DatePicker.prototype.parse = function( type, value, options ) {

    var calendar = this,
        parsingObject = {}

    if ( !value || Picker._.isInteger( value ) || $.isArray( value ) || Picker._.isDate( value ) || Picker._.isObject( value ) && Picker._.isInteger( value.pick ) ) {
        return value
    }

    // We need a `.format` to parse the value.
    if ( !( options && options.format ) ) {
        // should probably default to the default format.
        throw "Need a formatting option to parse this.."
    }

    // Convert the format into an array and then map through it.
    calendar.formats.toArray( options.format ).map( function( label ) {

        var
            // Grab the formatting label.
            formattingLabel = calendar.formats[ label ],

            // The format length is from the formatting label function or the
            // label length without the escaping exclamation (!) mark.
            formatLength = formattingLabel ? Picker._.trigger( formattingLabel, calendar, [ value, parsingObject ] ) : label.replace( /^!/, '' ).length

        // If there's a format label, split the value up to the format length.
        // Then add it to the parsing object with appropriate label.
        if ( formattingLabel ) {
            parsingObject[ label ] = value.substr( 0, formatLength )
        }

        // Update the value as the substring from format length to end.
        value = value.substr( formatLength )
    })

    // If it¡¯s parsing a user provided month value, compensate for month
	// 0index.
    return [ parsingObject.yyyy || parsingObject.yy, +( parsingObject.mm || parsingObject.m ) - ( options.data ?  1 : 0 ), parsingObject.dd || parsingObject.d ]
} //DatePicker.prototype.parse


/**
 * Various formats to display the object in.
 */
DatePicker.prototype.formats = (function() {

    // Return the length of the first word in a collection.
    function getWordLengthFromCollection( string, collection, dateObject ) {

        // Grab the first word from the string.
        var word = string.match( /\w+/ )[ 0 ]

        // If there's no month index, add it to the date object
        if ( !dateObject.mm && !dateObject.m ) {
            dateObject.m = collection.indexOf( word )
        }

        // Return the length of the word.
        return word.length
    }

    // Get the length of the first word in a string.
    function getFirstWordLength( string ) {
        return string.match( /\w+/ )[ 0 ].length
    }

    return {

        d: function( string, dateObject ) {

            // If there's string, then get the digits length.
            // Otherwise return the selected date.
            return string ? Picker._.digits( string ) : dateObject.date
        },
        dd: function( string, dateObject ) {

            // If there's a string, then the length is always 2.
            // Otherwise return the selected date with a leading zero.
            return string ? 2 : Picker._.lead( dateObject.date )
        },
        ddd: function( string, dateObject ) {

            // If there's a string, then get the length of the first word.
            // Otherwise return the short selected weekday.
            return string ? getFirstWordLength( string ) : this.settings.weekdaysShort[ dateObject.day ]
        },
        dddd: function( string, dateObject ) {

            // If there's a string, then get the length of the first word.
            // Otherwise return the full selected weekday.
            return string ? getFirstWordLength( string ) : this.settings.weekdaysFull[ dateObject.day ]
        },
        m: function( string, dateObject ) {

            // If there's a string, then get the length of the digits
            // Otherwise return the selected month with 0index compensation.
            return string ? Picker._.digits( string ) : dateObject.month + 1
        },
        mm: function( string, dateObject ) {

            // If there's a string, then the length is always 2.
            // Otherwise return the selected month with 0index and leading zero.
            return string ? 2 : Picker._.lead( dateObject.month + 1 )
        },
        mmm: function( string, dateObject ) {

            var collection = this.settings.monthsShort

            // If there's a string, get length of the relevant month from the
			// short
            // months collection. Otherwise return the selected month from that
			// collection.
            return string ? getWordLengthFromCollection( string, collection, dateObject ) : collection[ dateObject.month ]
        },
        mmmm: function( string, dateObject ) {

            var collection = this.settings.monthsFull

            // If there's a string, get length of the relevant month from the
			// full
            // months collection. Otherwise return the selected month from that
			// collection.
            return string ? getWordLengthFromCollection( string, collection, dateObject ) : collection[ dateObject.month ]
        },
        yy: function( string, dateObject ) {

            // If there's a string, then the length is always 2.
            // Otherwise return the selected year by slicing out the first 2
			// digits.
            return string ? 2 : ( '' + dateObject.year ).slice( 2 )
        },
        yyyy: function( string, dateObject ) {

            // If there's a string, then the length is always 4.
            // Otherwise return the selected year.
            return string ? 4 : dateObject.year
        },

        // Create an array by splitting the formatting string passed.
        toArray: function( formatString ) { return formatString.split( /(d{1,4}|m{1,4}|y{4}|yy|!.)/g ) },

        // Format an object into a string using the formatting options.
        toString: function ( formatString, itemObject ) {
            var calendar = this
            return calendar.formats.toArray( formatString ).map( function( label ) {
                return Picker._.trigger( calendar.formats[ label ], calendar, [ 0, itemObject ] ) || label.replace( /^!/, '' )
            }).join( '' )
        }
    }
})() // DatePicker.prototype.formats


/**
 * Flip an item as enabled or disabled.
 */
DatePicker.prototype.flipItem = function( type, value/* , options */ ) {

    var calendar = this,
        collection = calendar.item.disable,
        isInverted = calendar.item.enable === -1

    // Flip the enabled and disabled dates.
    if ( value == 'flip' ) {
        calendar.item.enable = isInverted ? 1 : -1
    }

    // Reset the collection and enable the base state.
    else if ( ( type == 'enable' && value === true ) || ( type == 'disable' && value === false ) ) {
        calendar.item.enable = 1
        collection = []
    }

    // Reset the collection and disable the base state.
    else if ( ( type == 'enable' && value === false ) || ( type == 'disable' && value === true ) ) {
        calendar.item.enable = -1
        collection = []
    }

    // Make sure a collection of things was passed to add/remove.
    else if ( $.isArray( value ) ) {

        // Check if we have to add/remove from collection.
        if ( !isInverted && type == 'enable' || isInverted && type == 'disable' ) {
            collection = calendar.removeDisabled( collection, value )
        }
        else if ( !isInverted && type == 'disable' || isInverted && type == 'enable' ) {
            collection = calendar.addDisabled( collection, value )
        }
    }

    return collection
} //DatePicker.prototype.flipItem


/**
 * Add an item to the disabled collection.
 */
DatePicker.prototype.addDisabled = function( collection, item ) {
    var calendar = this
    item.map( function( timeUnit ) {
        if ( !calendar.filterDisabled( collection, timeUnit ).length ) {
            collection.push( timeUnit )
        }
    })
    return collection
} //DatePicker.prototype.addDisabled


/**
 * Remove an item from the disabled collection.
 */
DatePicker.prototype.removeDisabled = function( collection, item ) {
    var calendar = this
    item.map( function( timeUnit ) {
        collection = calendar.filterDisabled( collection, timeUnit, 1 )
    })
    return collection
} //DatePicker.prototype.removeDisabled


/**
 * Filter through the disabled collection to find a time unit.
 */
DatePicker.prototype.filterDisabled = function( collection, timeUnit, isRemoving ) {

    var calendar = this,

        // Check if the time unit passed is an array or date object.
        timeIsObject = $.isArray( timeUnit ) || Picker._.isDate( timeUnit ),

        // Grab the comparison value if it¡¯s an object.
        timeObjectValue = timeIsObject && calendar.create( timeUnit ).pick

    // Go through the disabled collection and try to match this time unit.
    return collection.filter( function( disabledTimeUnit ) {

        // Check if it¡¯s an object and the collection item is an object,
        // use the comparison values. Otherwise to a direct comparison.
        var isMatch = timeIsObject && ( $.isArray( disabledTimeUnit ) || Picker._.isDate( disabledTimeUnit ) ) ?
                timeObjectValue === calendar.create( disabledTimeUnit ).pick : timeUnit === disabledTimeUnit

        // Invert the match if we¡¯re removing from the collection.
        return isRemoving ? !isMatch : isMatch
    })
} //DatePicker.prototype.filterDisabled


/**
 * Create a string for the nodes in the picker.
 */
DatePicker.prototype.nodes = function( isOpen ) {

    var
        calendar = this,
        settings = calendar.settings,
        nowObject = calendar.item.now,
        selectedObject = calendar.item.select,
        highlightedObject = calendar.item.highlight,
        viewsetObject = calendar.item.view,
        disabledCollection = calendar.item.disable,
        minLimitObject = calendar.item.min,
        maxLimitObject = calendar.item.max,


        // Create the calendar table head using a copy of weekday labels collection.
        // * We do a copy so we don't mutate the original array.
        tableHead = (function( collection ) {

            // If the first day should be Monday, move Sunday to the end.
            if ( settings.firstDay ) {
                collection.push( collection.shift() )
            }

            // Create and return the table head group.
            return Picker._.node(
                'thead',
                Picker._.group({
                    min: 0,
                    max: DAYS_IN_WEEK - 1,
                    i: 1,
                    node: 'th',
                    item: function( counter ) {
                        return [
                            collection[ counter ],
                            settings.klass.weekdays
                        ]
                    }
                })
            ) // endreturn
        })( ( settings.showWeekdaysFull ? settings.weekdaysFull : settings.weekdaysShort ).slice( 0 ) ), //tableHead


        // Create the nav for next/prev month.
        createMonthNav = function( next ) {

            // Otherwise, return the created month tag.
            return Picker._.node(
                'div',
                ' ',
                settings.klass[ 'nav' + ( next ? 'Next' : 'Prev' ) ] + (

                    // If the focused month is outside the range, disabled the button.
                    ( next && viewsetObject.year >= maxLimitObject.year && viewsetObject.month >= maxLimitObject.month ) ||
                    ( !next && viewsetObject.year <= minLimitObject.year && viewsetObject.month <= minLimitObject.month ) ?
                    ' ' + settings.klass.navDisabled : ''
                ),
                'data-nav=' + ( next || -1 )
            ) // endreturn
        }, //createMonthNav


        // Create the month label.
        createMonthLabel = function( monthsCollection ) {

            // If there are months to select, add a dropdown menu.
            if ( settings.selectMonths ) {

                return Picker._.node( 'select', Picker._.group({
                    min: 0,
                    max: 11,
                    i: 1,
                    node: 'option',
                    item: function( loopedMonth ) {

                        return [

                            // The looped month and no classes.
                            monthsCollection[ loopedMonth ], 0,

                            // Set the value and selected index.
                            'value=' + loopedMonth +
                            ( viewsetObject.month == loopedMonth ? ' selected' : '' ) +
                            (
                                (
                                    ( viewsetObject.year == minLimitObject.year && loopedMonth < minLimitObject.month ) ||
                                    ( viewsetObject.year == maxLimitObject.year && loopedMonth > maxLimitObject.month )
                                ) ?
                                ' disabled' : ''
                            )
                        ]
                    }
                }), settings.klass.selectMonth, isOpen ? '' : 'disabled' )
            }

            // If there's a need for a month selector
            return Picker._.node( 'div', monthsCollection[ viewsetObject.month ], settings.klass.month )
        }, //createMonthLabel


        // Create the year label.
        createYearLabel = function() {

            var focusedYear = viewsetObject.year,

            // If years selector is set to a literal "true", set it to 5. Otherwise
            // divide in half to get half before and half after focused year.
            numberYears = settings.selectYears === true ? 5 : ~~( settings.selectYears / 2 )

            // If there are years to select, add a dropdown menu.
            if ( numberYears ) {

                var
                    minYear = minLimitObject.year,
                    maxYear = maxLimitObject.year,
                    lowestYear = focusedYear - numberYears,
                    highestYear = focusedYear + numberYears

                // If the min year is greater than the lowest year, increase the
				// highest year
                // by the difference and set the lowest year to the min year.
                if ( minYear > lowestYear ) {
                    highestYear += minYear - lowestYear
                    lowestYear = minYear
                }

                // If the max year is less than the highest year, decrease the lowest year
                // by the lower of the two: available and needed years. Then set
				// the
                // highest year to the max year.
                if ( maxYear < highestYear ) {

                    var availableYears = lowestYear - minYear,
                        neededYears = highestYear - maxYear

                    lowestYear -= availableYears > neededYears ? neededYears : availableYears
                    highestYear = maxYear
                }

                return Picker._.node( 'select', Picker._.group({
                    min: lowestYear,
                    max: highestYear,
                    i: 1,
                    node: 'option',
                    item: function( loopedYear ) {
                        return [

                            // The looped year and no classes.
                            loopedYear, 0,

                            // Set the value and selected index.
                            'value=' + loopedYear + ( focusedYear == loopedYear ? ' selected' : '' )
                        ]
                    }
                }), settings.klass.selectYear, isOpen ? '' : 'disabled' )
            }

            // Otherwise just return the year focused
            return Picker._.node( 'div', focusedYear, settings.klass.year )
        } //createYearLabel


    // Create and return the entire calendar.
    return Picker._.node(
        'div',
        createMonthNav() + createMonthNav( 1 ) +
        createMonthLabel( settings.showMonthsShort ? settings.monthsShort : settings.monthsFull ) +
        createYearLabel(),
        settings.klass.header
    ) + Picker._.node(
        'table',
        tableHead +
        Picker._.node(
            'tbody',
            Picker._.group({
                min: 0,
                max: WEEKS_IN_CALENDAR - 1,
                i: 1,
                node: 'tr',
                item: function( rowCounter ) {

                    // If Monday is the first day and the month starts on Sunday, shift the date back a week.
                    var shiftDateBy = settings.firstDay && calendar.create([ viewsetObject.year, viewsetObject.month, 1 ]).day === 0 ? -7 : 0

                    return [
                        Picker._.group({
                            min: DAYS_IN_WEEK * rowCounter - viewsetObject.day + shiftDateBy + 1, // Add 1 for weekday 0index
                            max: function() {
                                return this.min + DAYS_IN_WEEK - 1
                            },
                            i: 1,
                            node: 'td',
                            item: function( targetDate ) {

                                // Convert the time date from a relative date to a target date.
                                targetDate = calendar.create([ viewsetObject.year, viewsetObject.month, targetDate + ( settings.firstDay ? 1 : 0 ) ])

                                return [
                                    Picker._.node(
                                        'div',
                                        targetDate.date,
                                        (function( klasses ) {

                                            // Add the `infocus` or `outfocus` classes based on month in view.
                                            klasses.push( viewsetObject.month == targetDate.month ? settings.klass.infocus : settings.klass.outfocus )

                                            // Add the `today` class if needed.
                                            if ( nowObject.pick == targetDate.pick ) {
                                                klasses.push( settings.klass.now )
                                            }

                                            // Add the `selected` class if something's selected and the time matches.
                                            if ( selectedObject && selectedObject.pick == targetDate.pick ) {
                                                klasses.push( settings.klass.selected )
                                            }

                                            // Add the `highlighted` class if something's highlighted and the time matches.
                                            if ( highlightedObject && highlightedObject.pick == targetDate.pick ) {
                                                klasses.push( settings.klass.highlighted )
                                            }

                                            // Add the `disabled` class if something's disabled and the object matches.
                                            if ( disabledCollection && calendar.disabled( targetDate ) || targetDate.pick < minLimitObject.pick || targetDate.pick > maxLimitObject.pick ) {
                                                klasses.push( settings.klass.disabled )
                                            }

                                            return klasses.join( ' ' )
                                        })([ settings.klass.day ]),
                                        'data-pick=' + targetDate.pick
                                    )
                                ] // endreturn
                            }
                        })
                    ] // endreturn
                }
            })
        ),
        settings.klass.table
    ) +

    // * For Firefox forms to submit, make sure to set the buttons¡¯ `type`
	// attributes as ¡°button¡±.
    Picker._.node(
        'div',
        Picker._.node( 'button', settings.today, settings.klass.buttonToday, 'type=button data-pick=' + nowObject.pick + ( isOpen ? '' : ' disabled' ) ) +
        Picker._.node( 'button', settings.clear, settings.klass.buttonClear, 'type=button data-clear=1' + ( isOpen ? '' : ' disabled' ) ),
        settings.klass.footer
    ) // endreturn
} //DatePicker.prototype.nodes




/**
 * The date picker defaults.
 */
DatePicker.defaults = (function( prefix ) {

    return {

        // Months and weekdays
        monthsFull: [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
        monthsShort: [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
        weekdaysFull: [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ],
        weekdaysShort: [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ],

        // Today and clear
        today: 'Today',
        //clear: 'Clear',
		//ÐÞ¸ÄclearÎªÈ·ÈÏ°´Å¥
		clear: 'OK',

        // The format to show on the `input` element
        format: 'd mmmm, yyyy',

        // Classes
        klass: {

            table: prefix + 'table',

            header: prefix + 'header',

            navPrev: prefix + 'nav--prev',
            navNext: prefix + 'nav--next',
            navDisabled: prefix + 'nav--disabled',

            month: prefix + 'month',
            year: prefix + 'year',

            selectMonth: prefix + 'select--month',
            selectYear: prefix + 'select--year',

            weekdays: prefix + 'weekday',

            day: prefix + 'day',
            disabled: prefix + 'day--disabled',
            selected: prefix + 'day--selected',
            highlighted: prefix + 'day--highlighted',
            now: prefix + 'day--today',
            infocus: prefix + 'day--infocus',
            outfocus: prefix + 'day--outfocus',

            footer: prefix + 'footer',

            buttonClear: prefix + 'button--clear',
            buttonToday: prefix + 'button--today'
        }
    }
})( Picker.klasses().picker + '__' )





/**
 * Extend the picker to add the date picker.
 */
Picker.extend( 'pickadate', DatePicker )


}));




;
/*
 * ! Time picker for pickadate.js v3.3.0
 * http://amsul.github.io/pickadate.js/time.htm
 */

/*
 * jshint debug: true, devel: true, browser: true, asi: true, unused: true,
 * boss: true
 */

(function ( factory ) {

    // Register as an anonymous module.
    if ( typeof define === 'function' && define.amd )
        define( ['picker','jquery'], factory )

    // Or using browser globals.
    else factory( Picker, jQuery )

}(function( Picker, $ ) {


/**
 * Globals and constants
 */
var HOURS_IN_DAY = 24,
    MINUTES_IN_HOUR = 60,
    HOURS_TO_NOON = 12,
    MINUTES_IN_DAY = HOURS_IN_DAY * MINUTES_IN_HOUR



/**
 * The time picker constructor
 */
function TimePicker( picker, settings ) {

    var clock = this,
        elementDataValue = picker.$node.data( 'value' )

    clock.settings = settings

    // The queue of methods that will be used to build item objects.
    clock.queue = {
        interval: 'i',
        min: 'measure create',
        max: 'measure create',
        now: 'now create',
        select: 'parse create validate',
        highlight: 'create validate',
        view: 'create validate',
        disable: 'flipItem',
        enable: 'flipItem'
    }

    // The component's item object.
    clock.item = {}

    clock.item.interval = settings.interval || 30
    clock.item.disable = ( settings.disable || [] ).slice( 0 )
    clock.item.enable = -(function( collectionDisabled ) {
        return collectionDisabled[ 0 ] === true ? collectionDisabled.shift() : -1
    })( clock.item.disable )

    clock.
        set( 'min', settings.min ).
        set( 'max', settings.max ).
        set( 'now' ).

        // Setting the `select` also sets the `highlight` and `view`.
        set( 'select',

            // If there's a `value` or `data-value`, use that with formatting.
            // Otherwise default to the minimum selectable time.
            elementDataValue || picker.$node[ 0 ].value || clock.item.min,

            // Use the relevant format.
            { format: elementDataValue ? settings.formatSubmit : settings.format }
        )

    // The keycode to movement mapping.
    clock.key = {
        40: 1, // Down
        38: -1, // Up
        39: 1, // Right
        37: -1, // Left
        go: function( timeChange ) {
            clock.set( 'highlight', clock.item.highlight.pick + timeChange * clock.item.interval, { interval: timeChange * clock.item.interval } )
            this.render()
        }
    }


    // Bind some picker events.
    picker.
        on( 'render', function() {
            var $pickerHolder = picker.$root.children(),
                $viewset = $pickerHolder.find( '.' + settings.klass.viewset )
            if ( $viewset.length ) {
                $pickerHolder[ 0 ].scrollTop += $viewset.position().top - ( $viewset[ 0 ].clientHeight * 2 )
            }
        }).
        on( 'open', function() {
            picker.$root.find( 'button' ).attr( 'disable', false )
        }).
        on( 'close', function() {
            picker.$root.find( 'button' ).attr( 'disable', true )
        })

} //TimePicker


/**
 * Set a timepicker item object.
 */
TimePicker.prototype.set = function( type, value, options ) {

    var clock = this

    // Go through the queue of methods, and invoke the function. Update this
    // as the time unit, and set the final resultant as this item type.
    // * In the case of `enable`, keep the queue but set `disable` instead.
    // And in the case of `flip`, keep the queue but set `enable` instead.
    clock.item[ ( type == 'enable' ? 'disable' : type == 'flip' ? 'enable' : type ) ] = clock.queue[ type ].split( ' ' ).map( function( method ) {
        return value = clock[ method ]( type, value, options )
    }).pop()

    // Check if we need to cascade through more updates.
    if ( type == 'select' ) {
        clock.set( 'highlight', clock.item.select, options )
    }
    else if ( type == 'highlight' ) {
        clock.set( 'view', clock.item.highlight, options )
    }
    else if ( type == 'interval' ) {
        clock.
            set( 'min', clock.item.min, options ).
            set( 'max', clock.item.max, options )
    }
    else if ( ( type == 'flip' || type == 'min' || type == 'max' || type == 'disable' || type == 'enable' ) && clock.item.select && clock.item.highlight ) {
        if ( type == 'min' ) {
            clock.set( 'max', clock.item.max, options )
        }
        clock.
            set( 'select', clock.item.select, options ).
            set( 'highlight', clock.item.highlight, options )
    }

    return clock
} //TimePicker.prototype.set


/**
 * Get a timepicker item object.
 */
TimePicker.prototype.get = function( type ) {
    return this.item[ type ]
} //TimePicker.prototype.get


/**
 * Create a picker time object.
 */
TimePicker.prototype.create = function( type, value, options ) {

    var clock = this

    // If there's no value, use the type as the value.
    value = value === undefined ? type : value

    // If it's an object, use the "pick" value.
    if ( Picker._.isObject( value ) && Picker._.isInteger( value.pick ) ) {
        value = value.pick
    }

    // If it's an array, convert it into minutes.
    else if ( $.isArray( value ) ) {
        value = +value[ 0 ] * MINUTES_IN_HOUR + (+value[ 1 ])
    }

    // If no valid value is passed, set it to "now".
    else if ( !Picker._.isInteger( value ) ) {
        value = clock.now( type, value, options )
    }

    // If we're setting the max, make sure it's greater than the min.
    if ( type == 'max' && value < clock.item.min.pick ) {
        value += MINUTES_IN_DAY
    }

    // Normalize it into a "reachable" interval.
    value = clock.normalize( type, value, options )

    // Return the compiled object.
    return {

        // Divide to get hours from minutes.
        hour: ~~( HOURS_IN_DAY + value / MINUTES_IN_HOUR ) % HOURS_IN_DAY,

        // The remainder is the minutes.
        mins: ( MINUTES_IN_HOUR + value % MINUTES_IN_HOUR ) % MINUTES_IN_HOUR,

        // The time in total minutes.
        time: ( MINUTES_IN_DAY + value ) % MINUTES_IN_DAY,

        // Reference to the "relative" value to pick.
        pick: value
    }
} //TimePicker.prototype.create


/**
 * Get the time relative to now.
 */
TimePicker.prototype.now = function( type, value/* , options */ ) {

    var date = new Date(),
        dateMinutes = date.getHours() * MINUTES_IN_HOUR + date.getMinutes()

    // If the value is a number, adjust by that many intervals because
    // the time has passed. In the case of ¡°midnight¡± and a negative `min`,
    // increase the value by 2. Otherwise increase it by 1.
    if ( Picker._.isInteger( value ) ) {
        value += type == 'min' && value < 0 && dateMinutes === 0 ? 2 : 1
    }

    // If the value isn¡¯t a number, default to 1 passed interval.
    else {
        value = 1
    }

    // Calculate the final relative time.
    return value * this.item.interval + dateMinutes
} //TimePicker.prototype.now


/**
 * Normalize minutes to be ¡°reachable¡± based on the min and interval.
 */
TimePicker.prototype.normalize = function( type, value/* , options */ ) {

    var minObject = this.item.min, interval = this.item.interval,

        // If setting min and it doesn¡¯t exist, don¡¯t shift anything.
        // Otherwise get the value and min difference and then
        // normalize the difference with the interval.
        difference = type == 'min' && !minObject ? 0 : ( value - minObject.pick ) % interval

    // If it¡¯s a negative value, add one interval to keep it as ¡°passed¡±.
    return value - ( difference + ( value < 0 ? interval : 0 ) )
} //TimePicker.prototype.normalize


/**
 * Measure the range of minutes.
 */
TimePicker.prototype.measure = function( type, value, options ) {

    var clock = this

    // If it's anything false-y, set it to the default.
    if ( !value ) {
        value = type == 'min' ? [ 0, 0 ] : [ HOURS_IN_DAY - 1, MINUTES_IN_HOUR - 1 ]
    }

    // If it's a literal true, or an integer, make it relative to now.
    else if ( value === true || Picker._.isInteger( value ) ) {
        value = clock.now( type, value, options )
    }

    // If it's an object already, just normalize it.
    else if ( Picker._.isObject( value ) && Picker._.isInteger( value.pick ) ) {
        value = clock.normalize( type, value.pick, options )
    }

    return value
} ///TimePicker.prototype.measure


/**
 * Validate an object as enabled.
 */
TimePicker.prototype.validate = function( type, timeObject, options ) {

    var clock = this,
        interval = options && options.interval ? options.interval : clock.item.interval

    // Check if the object is disabled.
    if ( clock.disabled( timeObject ) ) {

        // Shift with the interval until we reach an enabled time.
        timeObject = clock.shift( timeObject, interval )
    }

    // Scope the object into range.
    timeObject = clock.scope( timeObject )

    // Do a second check to see if we landed on a disabled min/max.
    // In that case, shift using the opposite interval as before.
    if ( clock.disabled( timeObject ) ) {
        timeObject = clock.shift( timeObject, interval * -1 )
    }

    // Return the final object.
    return timeObject
} //TimePicker.prototype.validate


/**
 * Check if an object is disabled.
 */
TimePicker.prototype.disabled = function( timeObject ) {

    var
        clock = this,

        // Filter through the disabled times to check if this is one.
        isDisabledTime = clock.item.disable.filter( function( timeToDisable ) {

            // If the time is a number, match the hours.
            if ( Picker._.isInteger( timeToDisable ) ) {
                return timeObject.hour == timeToDisable
            }

            // If it's an array, create the object and match the times.
            if ( $.isArray( timeToDisable ) ) {
                return timeObject.pick == clock.create( timeToDisable ).pick
            }
        }).length

    // If the clock is "enabled" flag is flipped, flip the condition.
    return clock.item.enable === -1 ? !isDisabledTime : isDisabledTime
} //TimePicker.prototype.disabled


/**
 * Shift an object by an interval until we reach an enabled object.
 */
TimePicker.prototype.shift = function( timeObject, interval ) {

    var clock = this,
        minLimit = clock.item.min.pick,
        maxLimit = clock.item.max.pick

    interval = interval || clock.item.interval

    // Keep looping as long as the time is disabled.
    while ( clock.disabled( timeObject ) ) {

        // Increase/decrease the time by the interval and keep looping.
        timeObject = clock.create( timeObject.pick += interval )

        // If we've looped beyond the limits, break out of the loop.
        if ( timeObject.pick <= minLimit || timeObject.pick >= maxLimit ) {
            break
        }
    }

    // Return the final object.
    return timeObject
} //TimePicker.prototype.shift


/**
 * Scope an object to be within range of min and max.
 */
TimePicker.prototype.scope = function( timeObject ) {
    var minLimit = this.item.min.pick,
        maxLimit = this.item.max.pick
    return this.create( timeObject.pick > maxLimit ? maxLimit : timeObject.pick < minLimit ? minLimit : timeObject )
} //TimePicker.prototype.scope


/**
 * Parse a string into a usable type.
 */
TimePicker.prototype.parse = function( type, value, options ) {

    var clock = this,
        parsingObject = {}

    if ( !value || Picker._.isInteger( value ) || $.isArray( value ) || Picker._.isDate( value ) || Picker._.isObject( value ) && Picker._.isInteger( value.pick ) ) {
        return value
    }

    // We need a `.format` to parse the value.
    if ( !( options && options.format ) ) {
        throw "Need a formatting option to parse this.."
    }

    // Convert the format into an array and then map through it.
    clock.formats.toArray( options.format ).map( function( label ) {

        var
            // Grab the formatting label.
            formattingLabel = clock.formats[ label ],

            // The format length is from the formatting label function or the
            // label length without the escaping exclamation (!) mark.
            formatLength = formattingLabel ? Picker._.trigger( formattingLabel, clock, [ value, parsingObject ] ) : label.replace( /^!/, '' ).length

        // If there's a format label, split the value up to the format length.
        // Then add it to the parsing object with appropriate label.
        if ( formattingLabel ) {
            parsingObject[ label ] = value.substr( 0, formatLength )
        }

        // Update the time value as the substring from format length to end.
        value = value.substr( formatLength )
    })

    return +parsingObject.i + MINUTES_IN_HOUR * (

        +( parsingObject.H || parsingObject.HH ) ||

        ( +( parsingObject.h || parsingObject.hh ) % 12 + ( /^p/i.test( parsingObject.A || parsingObject.a ) ? 12 : 0 ) )
    )
} //TimePicker.prototype.parse


/**
 * Various formats to display the object in.
 */
TimePicker.prototype.formats = {

    h: function( string, timeObject ) {

        // If there's string, then get the digits length.
        // Otherwise return the selected hour in "standard" format.
        return string ? Picker._.digits( string ) : timeObject.hour % HOURS_TO_NOON || HOURS_TO_NOON
    },
    hh: function( string, timeObject ) {

        // If there's a string, then the length is always 2.
        // Otherwise return the selected hour in "standard" format with a
		// leading zero.
        return string ? 2 : Picker._.lead( timeObject.hour % HOURS_TO_NOON || HOURS_TO_NOON )
    },
    H: function( string, timeObject ) {

        // If there's string, then get the digits length.
        // Otherwise return the selected hour in "military" format as a string.
        return string ? Picker._.digits( string ) : '' + ( timeObject.hour % 24 )
    },
    HH: function( string, timeObject ) {

        // If there's string, then get the digits length.
        // Otherwise return the selected hour in "military" format with a
		// leading zero.
        return string ? Picker._.digits( string ) : Picker._.lead( timeObject.hour % 24 )
    },
    i: function( string, timeObject ) {

        // If there's a string, then the length is always 2.
        // Otherwise return the selected minutes.
        return string ? 2 : Picker._.lead( timeObject.mins )
    },
    a: function( string, timeObject ) {

        // If there's a string, then the length is always 4.
        // Otherwise check if it's more than "noon" and return either am/pm.
        return string ? 4 : MINUTES_IN_DAY / 2 > timeObject.time % MINUTES_IN_DAY ? 'a.m.' : 'p.m.'
    },
    A: function( string, timeObject ) {

        // If there's a string, then the length is always 2.
        // Otherwise check if it's more than "noon" and return either am/pm.
        return string ? 2 : MINUTES_IN_DAY / 2 > timeObject.time % MINUTES_IN_DAY ? 'AM' : 'PM'
    },

    // Create an array by splitting the formatting string passed.
    toArray: function( formatString ) { return formatString.split( /(h{1,2}|H{1,2}|i|a|A|!.)/g ) },

    // Format an object into a string using the formatting options.
    toString: function ( formatString, itemObject ) {
        var clock = this
        return clock.formats.toArray( formatString ).map( function( label ) {
            return Picker._.trigger( clock.formats[ label ], clock, [ 0, itemObject ] ) || label.replace( /^!/, '' )
        }).join( '' )
    }
} //TimePicker.prototype.formats


/**
 * Flip an item as enabled or disabled.
 */
TimePicker.prototype.flipItem = function( type, value/* , options */ ) {

    var clock = this,
        collection = clock.item.disable,
        isFlipped = clock.item.enable === -1

    // Flip the enabled and disabled times.
    if ( value == 'flip' ) {
        clock.item.enable = isFlipped ? 1 : -1
    }

    // Reset the collection and enable the base state.
    else if ( ( type == 'enable' && value === true ) || ( type == 'disable' && value === false ) ) {
        clock.item.enable = 1
        collection = []
    }

    // Reset the collection and disable the base state.
    else if ( ( type == 'enable' && value === false ) || ( type == 'disable' && value === true ) ) {
        clock.item.enable = -1
        collection = []
    }

    // Make sure a collection of things was passed to add/remove.
    else if ( $.isArray( value ) ) {

        // Check if we have to add/remove from collection.
        if ( !isFlipped && type == 'enable' || isFlipped && type == 'disable' ) {
            collection = clock.removeDisabled( collection, value )
        }
        else if ( !isFlipped && type == 'disable' || isFlipped && type == 'enable' ) {
            collection = clock.addDisabled( collection, value )
        }
    }

    return collection
} //TimePicker.prototype.flipItem


/**
 * Add an item to the disabled collection.
 */
TimePicker.prototype.addDisabled = function( collection, item ) {
    var clock = this
    if ( item === false ) collection = []
    else item.map( function( timeUnit ) {
        if ( !clock.filterDisabled( collection, timeUnit ).length ) {
            collection.push( timeUnit )
        }
    })
    return collection
} //TimePicker.prototype.addDisabled


/**
 * Remove an item from the disabled collection.
 */
TimePicker.prototype.removeDisabled = function( collection, item ) {
    var clock = this
    item.map( function( timeUnit ) {
        collection = clock.filterDisabled( collection, timeUnit, 1 )
    })
    return collection
} //TimePicker.prototype.removeDisabled


/**
 * Filter through the disabled collection to find a time unit.
 */
TimePicker.prototype.filterDisabled = function( collection, timeUnit, isRemoving ) {
    var timeIsArray = $.isArray( timeUnit )
    return collection.filter( function( disabledTimeUnit ) {
        var isMatch = !timeIsArray && timeUnit === disabledTimeUnit ||
            timeIsArray && $.isArray( disabledTimeUnit ) && timeUnit.toString() === disabledTimeUnit.toString()
        return isRemoving ? !isMatch : isMatch
    })
} //TimePicker.prototype.filterDisabled


/**
 * The division to use for the range intervals.
 */
TimePicker.prototype.i = function( type, value/* , options */ ) {
    return Picker._.isInteger( value ) && value > 0 ? value : this.item.interval
}


/**
 * Create a string for the nodes in the picker.
 */
TimePicker.prototype.nodes = function( isOpen ) {

    var
        clock = this,
        settings = clock.settings,
        selectedObject = clock.item.select,
        highlightedObject = clock.item.highlight,
        viewsetObject = clock.item.view,
        disabledCollection = clock.item.disable

    return Picker._.node( 'ul', Picker._.group({
        min: clock.item.min.pick,
        max: clock.item.max.pick,
        i: clock.item.interval,
        node: 'li',
        item: function( loopedTime ) {
            loopedTime = clock.create( loopedTime )
            return [
                Picker._.trigger( clock.formats.toString, clock, [ Picker._.trigger( settings.formatLabel, clock, [ loopedTime ] ) || settings.format, loopedTime ] ),
                (function( klasses, timeMinutes ) {

                    if ( selectedObject && selectedObject.pick == timeMinutes ) {
                        klasses.push( settings.klass.selected )
                    }

                    if ( highlightedObject && highlightedObject.pick == timeMinutes ) {
                        klasses.push( settings.klass.highlighted )
                    }

                    if ( viewsetObject && viewsetObject.pick == timeMinutes ) {
                        klasses.push( settings.klass.viewset )
                    }

                    if ( disabledCollection && clock.disabled( loopedTime ) ) {
                        klasses.push( settings.klass.disabled )
                    }

                    return klasses.join( ' ' )
                })( [ settings.klass.listItem ], loopedTime.pick ),
                'data-pick=' + loopedTime.pick
            ]
        }
    }) +

    // * For Firefox forms to submit, make sure to set the button¡¯s `type`
	// attribute as ¡°button¡±.
    Picker._.node( 'li', Picker._.node( 'button', settings.clear, settings.klass.buttonClear, 'type=button data-clear=1' + ( isOpen ? '' : ' disable' ) ) ), settings.klass.list )
} //TimePicker.prototype.nodes







/*
 * ==========================================================================
 * Extend the picker to add the component with the defaults.
 * ==========================================================================
 */

TimePicker.defaults = (function( prefix ) {

    return {

        // Clear
        clear: 'OK',

        // The format to show on the `input` element
        format: 'h:i A',

        // The interval between each time
        interval: 30,

        // Classes
        klass: {

            picker: prefix + ' ' + prefix + '--time',
            holder: prefix + '__holder',

            list: prefix + '__list',
            listItem: prefix + '__list-item',

            disabled: prefix + '__list-item--disabled',
            selected: prefix + '__list-item--selected',
            highlighted: prefix + '__list-item--highlighted',
            viewset: prefix + '__list-item--viewset',
            now: prefix + '__list-item--now',

            buttonClear: prefix + '__button--clear'
        }
    }
})( Picker.klasses().picker )





/**
 * Extend the picker to add the date picker.
 */
Picker.extend( 'pickatime', TimePicker )


}));




;
/*
 * jshint asi: true, unused: true, boss: true, loopfunc: true, eqnull: true
 */


/*
 * ! Legacy browser support
 */


// Map array support
if ( ![].map ) {
    Array.prototype.map = function ( callback, self ) {
        var array = this, len = array.length, newArray = new Array( len )
        for ( var i = 0; i < len; i++ ) {
            if ( i in array ) {
                newArray[ i ] = callback.call( self, array[ i ], i, array )
            }
        }
        return newArray
    }
}


// Filter array support
if ( ![].filter ) {
    Array.prototype.filter = function( callback ) {
        if ( this == null ) throw new TypeError()
        var t = Object( this ), len = t.length >>> 0
        if ( typeof callback != 'function' ) throw new TypeError()
        var newArray = [], thisp = arguments[ 1 ]
        for ( var i = 0; i < len; i++ ) {
          if ( i in t ) {
            var val = t[ i ]
            if ( callback.call( thisp, val, i, t ) ) newArray.push( val )
          }
        }
        return newArray
    }
}


// Index of array support
if ( ![].indexOf ) {
    Array.prototype.indexOf = function( searchElement ) {
        if ( this == null ) throw new TypeError()
        var t = Object( this ), len = t.length >>> 0
        if ( len === 0 ) return -1
        var n = 0
        if ( arguments.length > 1 ) {
            n = Number( arguments[ 1 ] )
            if ( n != n ) {
                n = 0
            }
            else if ( n !== 0 && n != Infinity && n != -Infinity ) {
                n = ( n > 0 || -1 ) * Math.floor( Math.abs( n ) )
            }
        }
        if ( n >= len ) return -1
        var k = n >= 0 ? n : Math.max( len - Math.abs( n ), 0 )
        for ( ; k < len; k++ ) {
            if ( k in t && t[ k ] === searchElement ) return k
        }
        return -1
    }
}


/*!
 * Cross-Browser Split 1.1.1
 * Copyright 2007-2012 Steven Levithan <stevenlevithan.com>
 * Available under the MIT License
 * http://blog.stevenlevithan.com/archives/cross-browser-split
 */
var nativeSplit = String.prototype.split, compliantExecNpcg = /()??/.exec('')[1] === undefined
String.prototype.split = function(separator, limit) {
    var str = this
    if (Object.prototype.toString.call(separator) !== '[object RegExp]') {
        return nativeSplit.call(str, separator, limit)
    }
    var output = [],
        flags = (separator.ignoreCase ? 'i' : '') +
                (separator.multiline  ? 'm' : '') +
                (separator.extended   ? 'x' : '') +
                (separator.sticky     ? 'y' : ''),
        lastLastIndex = 0,
        separator2, match, lastIndex, lastLength
    separator = new RegExp(separator.source, flags + 'g')
    str += ''
    if (!compliantExecNpcg) {
        separator2 = new RegExp('^' + separator.source + '$(?!\\s)', flags)
    }
    limit = limit === undefined ? -1 >>> 0 : limit >>> 0
    while (match = separator.exec(str)) {
        lastIndex = match.index + match[0].length
        if (lastIndex > lastLastIndex) {
            output.push(str.slice(lastLastIndex, match.index))
            if (!compliantExecNpcg && match.length > 1) {
                match[0].replace(separator2, function () {
                    for (var i = 1; i < arguments.length - 2; i++) {
                        if (arguments[i] === undefined) {
                            match[i] = undefined
                        }
                    }
                })
            }
            if (match.length > 1 && match.index < str.length) {
                Array.prototype.push.apply(output, match.slice(1))
            }
            lastLength = match[0].length
            lastLastIndex = lastIndex
            if (output.length >= limit) {
                break
            }
        }
        if (separator.lastIndex === match.index) {
            separator.lastIndex++
        }
    }
    if (lastLastIndex === str.length) {
        if (lastLength || !separator.test('')) {
            output.push('')
        }
    } else {
        output.push(str.slice(lastLastIndex))
    }
    return output.length > limit ? output.slice(0, limit) : output
}

;/*
	 * * ±äÁ¿Öµ
	 */
	/*
	 * * Ò³ÃæÇÐ»»µÄÐ§¹û¿ØÖÆ
	 */
var Msize = $(".m-page").size(), 	//Ò³ÃæµÄÊýÄ¿
	page_n			= 1,			//³õÊ¼Ò³ÃæÎ»ÖÃ
	initP			= null,			//³õÖµ¿ØÖÆÖµ
	moveP			= null,			//Ã¿´Î»ñÈ¡µ½µÄÖµ
	firstP			= null,			//µÚÒ»´Î»ñÈ¡µÄÖµ
	newM			= null,			//ÖØÐÂ¼ÓÔØµÄ¸¡²ã
	p_b				= null,			//·½Ïò¿ØÖÆÖµ
	indexP			= null, 		//¿ØÖÆÊ×Ò³²»ÄÜÖ±½ÓÕÒ×ªµ½×îºóÒ»Ò³
	move			= null,			//´¥ÃþÄÜ»¬¶¯Ò³Ãæ
	start			= true, 		//¿ØÖÆ¶¯»­¿ªÊ¼
	startM			= null,			//¿ªÊ¼ÒÆ¶¯
	position		= null,			//·½ÏòÖµ
	DNmove			= false,		//ÆäËû²Ù×÷²»ÈÃÒ³ÃæÇÐ»»
	mapS			= null,			//µØÍ¼±äÁ¿Öµ
	canmove			= true,		//Ê×Ò³·µ»Ø×îºóÒ»Ò³
	
	textNode		= [],			//ÎÄ±¾¶ÔÏó
	textInt			= 1;			// ÎÄ±¾¶ÔÏóË³Ðò
	
	/*
	 * * ÉùÒô¹¦ÄÜµÄ¿ØÖÆ
	 */
	audio_switch_btn= true,			//ÉùÒô¿ª¹Ø¿ØÖÆÖµ
	audio_btn		= true,			//ÉùÒô²¥·ÅÍê±Ï
	audio_loop		= false,		//ÉùÒôÑ­»·
	audioTime		= null,         //ÉùÒô²¥·ÅÑÓÊ±
	audioTimeT		= null,			//¼ÇÂ¼ÉÏ´Î²¥·ÅÊ±¼ä
	audio_interval	= null,			//ÉùÒôÑ­»·¿ØÖÆÆ÷
	audio_start		= null,			//ÉùÒô¼ÓÔØÍê±Ï
	audio_stop		= null,			//ÉùÒôÊÇ·ñÔÚÍ£Ö¹
	mousedown		= null,			//PCÊó±ê¿ØÖÆÊó±ê°´ÏÂ»ñÈ¡Öµ
	
	/*
	 * * Í³¼Æ¿ØÖÆ
	 */
	plugin_type		= {				//¼ÇÂ¼Ò³ÃæÇÐ»»µÄÊýÁ¿
		'info_pic2':{num:0,id:0},
		'info_nomore':{num:0,id:0},
		'info_more':{num:0,id:0},
		'multi_contact':{num:0,id:0},
		'video':{num:0,id:0},
		'input':{num:0,id:0},
		'dpic':{num:0,id:0}
	};   


/*
 * * µ¥Ò³ÇÐ»» ¸÷¸öÔªËØfixed ¿ØÖÆbody¸ß¶È
 */
	var v_h	= null;		// ¼ÇÂ¼Éè±¸µÄ¸ß¶È
	
	function init_pageH(){
		var fn_h = function() {
			if(document.compatMode == "BackCompat")
				var Node = document.body;
			else
				var Node = document.documentElement;
			 return Math.max(Node.scrollHeight,Node.clientHeight);
		}
		var page_h = fn_h();
		var m_h = $(".m-page").height();
		page_h >= m_h ? v_h = page_h : v_h = m_h ;
		
		// ÉèÖÃ¸÷ÖÖÄ£¿éÒ³ÃæµÄ¸ß¶È£¬À©Õ¹µ½Õû¸öÆÁÄ»¸ß¶È
		$(".m-page").height(v_h); 	
		$(".p-index").height(v_h);
		
	};
	init_pageH();

	/* ´óÍ¼ÎÄÍ¼Æ¬ÑÓ³Ù¼ÓÔØ */
	var lazyNode = $('.lazy-bk');
	lazyNode.each(function(){
		var self = $(this);
		if(self.is('img')){
			self.attr('src','data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC');
		}else{
			self.css({
				'background-image'	: 'url(./img/loading_large.gif)',
				'background-size'	: '120px 120px'

			})
		}
	})

	// Ç°Èý¸öÒ³ÃæµÄÍ¼Æ¬ÑÓ³Ù¼ÓÔØ
	setTimeout(function(){
		for(var i=0;i<3;i++){
			var node = $(".m-page").eq(i);
			if(node.length==0) break;
			if(node.find('.lazy-bk').length!=0){
				lazy_change(node);
			}else continue;
		}
	},200)
	
	// ¼ÓÔØµ±Ç°ºóÃæµÚÈý¸ö
	function lazy_bigP(){
		for(var i=3;i<=5;i++){
			var node = $(".m-page").eq(page_n+i-1);
			if(node.length==0) break;
			if(node.find('.lazy-bk').length!=0){
				lazy_change(node);
			}else continue;
		}
	}

	// Í¼Æ¬ÑÓ³ÙÌæ»»º¯Êý
	function lazy_change(node){
			if(node.attr('data-yuyue')=='true'){
				$('.weixin-share').find('.lazy-bk').attr('src',$('.weixin-share').find('.lazy-bk').attr('data-bk'));
			}
			var lazy = node.find('.lazy-bk');
			lazy.each(function(){
				var self = $(this),
					srcImg = self.attr('data-bk');

				$('<img />')
					.on('load',function(){
						if(self.is('img')){
							self.attr('src',srcImg)
						}else{
							self.css({
								'background-image'	: 'url('+srcImg+')',
								'background-size'	: 'cover'
							})
						}

						// ÅÐ¶ÏÏÂÃæÒ³Ãæ½øÐÐ¼ÓÔØ
						for(var i =0;i<$(".m-page").size();i++){
							var page = $(".m-page").eq(i);
							if($(".m-page").find('.lazy-bk').length==0) continue
							else{
								lazy_change(page);
							}
						}
					})
					.attr("src",srcImg);

				self.removeClass('lazy-bk');
			})	
	}

/*
**Ä£°æÇÐ»»Ò³ÃæµÄÐ§¹û
*/
	// °ó¶¨ÊÂ¼þ
	function changeOpen(e){
		$(".m-page").on('mousedown touchstart',page_touchstart);
		$(".m-page").on('mousemove touchmove',page_touchmove);
		$(".m-page").on('mouseup touchend mouseout',page_touchend);
		
		$('.wct .tableWrap').on('mousedown touchstart',page_touchstart);
		$('.wct .tableWrap').on('mousemove touchmove',page_touchmove);
		$('.wct .tableWrap').on('mouseup touchend mouseout',page_touchend);
	};
	
	// È¡Ïû°ó¶¨ÊÂ¼þ
	function changeClose(e){
		$(".m-page").off('mousedown touchstart');
		$(".m-page").off('mousemove touchmove');
		$(".m-page").off('mouseup touchend mouseout');
		
		$('.wct .tableWrap').off('mousedown touchstart');
		$('.wct .tableWrap').off('mousemove touchmove');
		$('.wct .tableWrap').off('mouseup touchend mouseout');
	};
	
	// ¿ªÆôÊÂ¼þ°ó¶¨»¬¶¯

	changeOpen();
	
	// ´¥Ãþ£¨Êó±ê°´ÏÂ£©¿ªÊ¼º¯Êý
	function page_touchstart(e){
		if (e.type == "touchstart") {
			initP = window.event.touches[0].pageY;
		} else {
			initP = e.y || e.pageY;
			mousedown = true;
		}
		firstP = initP;	
	};
	
	// ²å¼þ»ñÈ¡´¥ÃþµÄÖµ
	function V_start(val){
		initP = val;
		mousedown = true;
		firstP = initP;		
	};
	
	// ´¥ÃþÒÆ¶¯£¨Êó±êÒÆ¶¯£©¿ªÊ¼º¯Êý
	function page_touchmove(e){
		e.preventDefault();
		e.stopPropagation();	

		// ÅÐ¶ÏÊÇ·ñ¿ªÊ¼»òÕßÔÚÒÆ¶¯ÖÐ»ñÈ¡Öµ
		if(start||startM){
			startM = true;
			if (e.type == "touchmove") {
				moveP = window.event.touches[0].pageY;
			} else { 
				if(mousedown) moveP = e.y || e.pageY;
			}
			page_n == 1 ? indexP = false : indexP = true ;	// true Îª²»ÊÇµÚÒ»Ò³
															// falseÎªµÚÒ»Ò³
		}
		
		//ÉèÖÃÒ»¸öÒ³Ãæ¿ªÊ¼ÒÆ¶¯
		if(moveP&&startM){
			
			//ÅÐ¶Ï·½Ïò²¢ÈÃÒ»¸öÒ³Ãæ³öÏÖ¿ªÊ¼ÒÆ¶¯
			if(!p_b){
				p_b = true;
				position = moveP - initP > 0 ? true : false;	// true
																// ÎªÏòÏÂ»¬¶¯
																// false
																// ÎªÏòÉÏ»¬¶¯
				if(position){
				//ÏòÏÂÒÆ¶¯
					if(indexP){								
						newM = page_n - 1 ;
						$(".m-page").eq(newM-1).addClass("active").css("top",-v_h);
						move = true ;
					}else{
						if(canmove){
							move = true;
							newM = Msize;
							$(".m-page").eq(newM-1).addClass("active").css("top",-v_h);
						}
						else move = false;
					}
							
				}else{
				//ÏòÉÏÒÆ¶¯
					if(page_n != Msize){
						if(!indexP) $('.audio_txt').addClass('close');
						newM = page_n + 1 ;
					}else{
						newM = 1 ;
					}
					$(".m-page").eq(newM-1).addClass("active").css("top",v_h);
					move = true ;
				} 
			}
			
			//¸ù¾ÝÒÆ¶¯ÉèÖÃÒ³ÃæµÄÖµ
			if(!DNmove){
				//»¬¶¯´ø¶¯Ò³Ãæ»¬¶¯
				if(move){	
					//¿ªÆôÉùÒô
					if($("#car_audio").length>0&&audio_switch_btn&&Math.abs(moveP - firstP)>100){
						$("#car_audio")[0].play();
						audio_loop = true;
					}
				
					//ÒÆ¶¯ÖÐÉèÖÃÒ³ÃæµÄÖµ£¨top£©
					start = false;
					var topV = parseInt($(".m-page").eq(newM-1).css("top"));
					$(".m-page").eq(newM-1).css({'top':topV+moveP-initP});	
					initP = moveP;
				}else{
					moveP = null;	
				}
			}else{
				console.log('2')
				moveP = null;	
			}
		}
	};

	// ´¥Ãþ½áÊø£¨Êó±êÆðÀ´»òÕßÀë¿ªÔªËØ£©¿ªÊ¼º¯Êý
	function page_touchend(e){	
			
		//½áÊø¿ØÖÆÒ³Ãæ
		startM =null;
		p_b = false;
		
		// ¹Ø±ÕÉùÒô
		 audio_close();
		
		// ÅÐ¶ÏÒÆ¶¯µÄ·½Ïò
		var move_p;	
		position ? move_p = moveP - firstP > 100 : move_p = firstP - moveP > 100 ;
		if(move){
			//ÇÐ»­Ò³Ãæ(ÒÆ¶¯³É¹¦)
			if( move_p && Math.abs(moveP) >5 ){	
				$(".m-page").eq(newM-1).animate({'top':0},300,"easeOutSine",function(){
					/*
					** ÇÐ»»³É¹¦»Øµ÷µÄº¯Êý
					*/
					success();
				})
			// ·µ»ØÒ³Ãæ(ÒÆ¶¯Ê§°Ü)
			}else if (Math.abs(moveP) >=5){	//Ò³ÃæÍË»ØÈ¥
				position ? $(".m-page").eq(newM-1).animate({'top':-v_h},100,"easeOutSine") : $(".m-page").eq(newM-1).animate({'top':v_h},100,"easeOutSine");
				$(".m-page").eq(newM-1).removeClass("active");
				start = true;
			}
		}
		/* ³õÊ¼»¯Öµ */
		initP		= null,			//³õÖµ¿ØÖÆÖµ
		moveP		= null,			//Ã¿´Î»ñÈ¡µ½µÄÖµ
		firstP		= null,			//µÚÒ»´Î»ñÈ¡µÄÖµ
		mousedown	= null;			// È¡ÏûÊó±ê°´ÏÂµÄ¿ØÖÆÖµ
	};
/*
 * * ÇÐ»»³É¹¦µÄº¯Êý
 */
	function success(){
		/*
		** ÇÐ»»³É¹¦»Øµ÷µÄº¯Êý
		*/							
		// ÉèÖÃÒ³ÃæµÄ³öÏÖ
		$(".m-page").eq(page_n-1).removeClass("show active").addClass("hide");
		$(".m-page").eq(newM-1).removeClass("active hide").addClass("show");
		
		// »¬¶¯³É¹¦¼ÓÔØ¶àÃæµÄÍ¼Æ¬
		lazy_bigP();
		
		// ÖØÐÂÉèÖÃÒ³ÃæÒÆ¶¯µÄ¿ØÖÆÖµ
		page_n = newM;
		start = true;
		
		// ÅÐ¶ÏÊÇ²»ÊÇ×îºóÒ»Ò³£¬³öÏÖÌáÊ¾ÎÄ×Ö
		if(page_n == Msize) {
			if($('.popup-txt').attr('data-show')!='show'){
				$('.popup-txt').attr('data-show','show');
				$('.popup-txt').addClass('txtHide');
			}
			canmove = true;
		}else{
			$('.popup-txt').removeClass('txtHide');	
		}
		
		//µØÍ¼ÖØÖÃ
		if($(".m-page").eq(page_n-1).find(".ylMap").length>0&&!mapS){
			if(!mapS) mapS = new ylmap.init;
		}
		
		//txt¸»ÎÄ±¾×ÔÊÊÓ¦ÉìËõ
		if($(".m-page").eq(page_n-1).find('.m-txt').length>0) txtExtand();
	
		// Ò³ÃæÇÐ»»ÊÓÆµ²¥·ÅÍ£Ö¹
		if($('.m-video').find("video")[0]!=undefined){
			$('.m-video').find("video")[0].pause();
			$('.video-warp').show();
		};
		
		// ÎÄ±¾Ëõ»Ø
		$(".m-txt").removeClass("open");	
		$('.m-txt').each(function(){
			if($(this).attr('data-self')!=''){
				$(this).css('height',$(this).attr('data-self'));
			}
		})
	}

/*
** ÉùÒô¹¦ÄÜ
*/
	// ¹Ø±ÕÉùÒô
	function audio_close(){
		if(audio_btn&&audio_loop){
			audio_btn =false;
			audioTime = Number($("#car_audio")[0].duration-$("#car_audio")[0].currentTime)*1000;	
			if(audioTime<0){ audioTime=0; }
			if(audio_start){
				if(isNaN(audioTime)){
					audioTime = audioTimeT;
				}else{
					audioTime > audioTimeT ? audioTime = audioTime: audioTime = audioTimeT;
				}
			};
			if(!isNaN(audioTime)&&audioTime!=0){
				audio_btn =false;		
				setTimeout(
					function(){	
						$("#car_audio")[0].pause();
						$("#car_audio")[0].currentTime = 0;
						audio_btn = true;
						audio_start = true;	
						if(!isNaN(audioTime)&&audioTime>audioTimeT) audioTimeT = audioTime;
					},audioTime);
			}else{
				audio_interval = setInterval(function(){
					if(!isNaN($("#car_audio")[0].duration)){
						if($("#car_audio")[0].currentTime !=0 && $("#car_audio")[0].duration!=0 && $("#car_audio")[0].duration==$("#car_audio")[0].currentTime){
							$("#car_audio")[0].currentTime = 0;	
							$("#car_audio")[0].pause();
							clearInterval(audio_interval);
							audio_btn = true;
							audio_start = true;
							if(!isNaN(audioTime)&&audioTime>audioTimeT) audioTimeT = audioTime;
						}
					}
				},20)	
			}
		}
	}
	
	//Ò³ÃæÉùÒô²¥·Å
	$(function(){
		//»ñÈ¡ÉùÒôÔª¼þ
		var btn_au = $(".fn-audio").find(".btn");
		
		// °ó¶¨µã»÷ÊÂ¼þ
		btn_au.on('click',audio_switch);
		function audio_switch(){
			if($("#car_audio")==undefined){
				return;
			}
			if(audio_switch_btn){
				//¹Ø±ÕÉùÒô
				$("#car_audio")[0].pause();
				audio_switch_btn = false;
				$("#car_audio")[0].currentTime = 0;
				btn_au.find("span").eq(0).css("display","none");
				btn_au.find("span").eq(1).css("display","inline-block");
			}
			//¿ªÆôÉùÒô
			else{
				audio_switch_btn = true;
				btn_au.find("span").eq(1).css("display","none");
				btn_au.find("span").eq(0).css("display","inline-block");
			}
		}
	})

/*
 * *ÎÄ±¾Õ¹¿ªÐ§¹û
 */
	// ÅÐ¶Ï¸»ÎÄ±¾ÊÇ·ñÕ¹¿ª
	function txtExtand(){
		// ¿É·ñÕ¹¿ª
		$(".m-page").eq(page_n-1).find('.m-txt').not('.txt-noclick').each(function(){
			var txt = $(this).attr('data-txt'),
				txtnum;
			if(!txt){
				$(this).attr('data-txt',textInt);
				txtnum = textInt;
				textInt++;
			}else{
				txtnum = txt;
			}
			if(txtnum in textNode){
			}else{
				var maxHeight = '500';
				if($(".m-page").eq(page_n-1).find('.m-txt02').size()>0){
					maxHeight = '300';
				}
				textNode[txtnum] = 	new txtInit(this,{maxHeight:maxHeight,maxWidth:'500',node:'p,h2'});
			}
		});
	};
	// txt¸»ÎÄ±¾×ÔÊÊÓ¦ÉìËõ
	if($(".m-page").eq(page_n-1).find('.m-txt').length>0) txtExtand();

/*
 * *Éè±¸Ðý×ªÌáÊ¾
 */
	$(function(){
		var bd = $(document.body);
		window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', _orientationchange, false);
		function _orientationchange() {
			scrollTo(0, 1);
			switch(window.orientation){
				case 0:		//ºáÆÁ
					bd.addClass("landscape").removeClass("portrait");
					init_pageH();					
					break;
				case 180:	//ºáÆÁ
					bd.addClass("landscape").removeClass("portrait");	
					init_pageH();
					break;

				case -90: 	//ÊúÆÁ

					init_pageH();
					// bd.addClass("portrait").removeClass("landscape");
					if($(".m-video video")[0]!=undefined && $(".m-video video")[0].paused){
							 alert("ÇëÊúÆÁ²é¿´Ò³Ãæ£¬Ð§¹û¸ü¼Ñ");
					}else{
							alert("ÇëÊúÆÁ²é¿´Ò³Ãæ£¬Ð§¹û¸ü¼Ñ");
					}

					break;
				case 90: 	//ÊúÆÁ
					init_pageH();
					bd.addClass("portrait").removeClass("landscape");
					if($(".m-video video")[0].paused)
					alert("ÇëÊúÆÁ²é¿´Ò³Ãæ£¬Ð§¹û¸ü¼Ñ");
					break;
			}
		}
		$(window).on('load',_orientationchange);
	});

/*
 * * ²å¼þµÄ¼ÓÔØ
 */
	$(function(){
		/*
		**Õ¹Ê¾Æ·Ðý×ª
		*/
		$(".imgbox").yl3d({
			stopEle	: $(".m-page"),
			startFn : page_touchstart,
			moveFn	: page_touchmove,
			endFn	: page_touchend,
			V_start : V_start
		});
		
		/*
		 * *ÂÖ²¥Í¼Õ¹Ê¾
		 */
		$('.u-img').ylSlider({
			auto : true 
		});
		
		/*
		 * *ÈÕÆÚ²å¼þÌí¼Ó
		 */
		var opts_data ={
			monthsFull: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			weekdaysFull: ['ÐÇÆÚÒ»', 'ÐÇÆÚ¶þ', 'ÐÇÆÚÈý', 'ÐÇÆÚËÄ', 'ÐÇÆÚÎå', 'ÐÇÆÚÁù', 'ÐÇÆÚÈÕ'],
			weekdaysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			format: 'yyyy-mmmm-d',
			min: new Date()
		};
		$(".picker_data").pickadate(opts_data);
		$(".picker_time").pickatime();
	});

/*
 * * Ò³ÃæÄÚÈÝ¼ÓÔØloadingÏÔÊ¾
 */
	// ÏÔÊ¾
	function loadingPageShow(){
		$('.pageLoading').show();	
	}
	//Òþ²Ø
	function loadingPageHide(){
		$('.pageLoading').hide();	
	}

/*
** Ò³Ãæ¼ÓÔØ³õÊ¼»¯
*/
	var input_focus = false;
	function initPage(){
		//³õÊ¼»¯Ò»¸öÒ³Ãæ
		$(".m-page").addClass("hide").eq(page_n-1).addClass("show").removeClass("hide");
		
		// ³õÊ¼»¯µØÍ¼
		if($(".m-page").eq(page_n-1).find(".ylMap").length>0&&!mapS){
			mapS = new ylmap.init;
		}
		
		//PC¶ËÍ¼Æ¬µã»÷²»²úÉúÍÏ×§
		$(document.body).find("img").on("mousedown",function(e){
			e.preventDefault();
		})	
		
		// °´Å¥µã»÷µÄ±ä»¯
		$('.btn-boder-color').click(function(){
			if(!$(this).hasClass("open"))	$(this).addClass('open');
			setTimeout(function(){
				$('.btn-boder-color').removeClass('open');	
			},600)
			
		})
		
		// //±íµ¥¾Û½¹Ê±£¬È¡ÏûÇÐ»»Ð§¹û
		// $('.wct_form').find('input').on('focus',function(){
		// changeClose();
		// })
		
		// //±íµ¥Ê§È¥¾Û½¹Ê±£¬¿ªÆôÇÐ»»Ð§¹û
		// $('.wct_form').find('input').on('blur',function(){
		// changeOpen();
		// })

		/**
		 * ±íµ¥µÄ²Ù×÷
		 */
		$('.wct_form input').on('focus',function(){
			if($(this).attr('type')!='submit'){
				changeClose();
			}
			setTimeout(function(){input_focus = true;},500)
		})
			
		$('.wct_form input').on('blur',inputBlur);
		function inputBlur(){
			changeOpen();	
			input_focus = false;
		}
		
		window.addEventListener("resize",input_focus_out,false);
		function input_focus_out(){
			if($('.wct_form input[type!="radio"]:focus').length>=1&&input_focus){
				changeOpen();
				input_focus = false;
				$('.wct_form input').off('blur');
				$('.wct_form input').blur();
				$('.wct_form input').on('blur',inputBlur);
			}else return
		};
		
		// ÊÓÆµµã»÷²¥·Å
		$('.video-warp').on('click',function(){
			$('.m-video').find("video")[0].play();	
			$(this).hide();
			$("#car_audio")[0].pause();
		})
		
		// ÊÓÆµ¿ØÖÆÊÂ¼þ
		var video = $('.m-video').find("video");
		video.on('play',function(){
			$('.video-warp').hide();
			$("#car_audio")[0].pause();
		})
		video.on('pause',function(){
			$('.video-warp').show();	
		})
		video.on('ended',function(){
			$('.video-warp').show();
		})

		// µ÷ÊÔÍ¼Æ¬µÄ³ß´ç
		if(RegExp("iPhone").test(navigator.userAgent)||RegExp("iPod").test(navigator.userAgent)||RegExp("iPad").test(navigator.userAgent)) $('.m-page').css('height','101%');
	}(initPage());


/** **************************************************************************************************************************************************** */
// Êý¾ÝÍ³¼Æ
// setTimeout(ajax_analysite,100); //·¢ËÍÕ¾µãÍ³¼ÆÇëÇó
// setInterval(ajax_analyseplugin,15000); //·¢ËÍÒ³ÃæÇëÇó

/**
 * ´æ´¢Ò³ÃæÍ³¼ÆµÄÊý¾Ý
 * 
 * @param object
 *            dom ÐèÒªÍ³¼ÆµÄÒ³Ãæjquery dom¶ÔÏó
 */
function set_static_data(dom){
	var id = dom.attr('data-id');
	var type = dom.attr('data-type');
	if('info_pic2'==type){
		plugin_type.info_pic2.num++;
		plugin_type.info_pic2.id = id;
	}else if('info_nomore'==type){
		plugin_type.info_nomore.num++;
		plugin_type.info_nomore.id = id;
	}else if('info_more'==type){
		plugin_type.info_more.num++;
		plugin_type.info_more.id = id;
	}else if('multi_contact'==type){
		plugin_type.multi_contact.num++;
		plugin_type.multi_contact.id = id;
	}else if('video'==type){
		plugin_type.video.num++;
		plugin_type.video.id = id;
	}else if('input'==type){
		plugin_type.input.num++;
		plugin_type.input.id = id;
	}else if('3dpic'==type){
		plugin_type.dpic.num++;
		plugin_type.dpic.id = id;
	}
}

/**
 * ·¢ËÍÒ³ÃæÍ³¼ÆµÄÊý¾Ý
 */
function ajax_analyseplugin(){
	var activeId = $('#activeId').val();
	var ajax_ = function(type,id,num){
		$.ajax({
			url: '/'+SYS_SITE_ID+'/analyseplugin/plugin/'+activeId,
			cache: false,
			dataType: 'html',
			async: true,
			type:'GET',
			data: "&plugtype="+type+"&id="+id+"&num="+num+"&activity_id="+activeId,
		});
	}
	if(plugin_type.dpic.id!=0 && plugin_type.dpic.num>0){
		ajax_('3dpic',plugin_type.dpic.id,plugin_type.dpic.num);
		plugin_type.dpic.num = 0;
	}
	if(plugin_type.info_more.id!=0 && plugin_type.info_more.num>0){
		ajax_('info_more',plugin_type.info_more.id,plugin_type.info_more.num);
		plugin_type.info_more.num = 0;
	}
	if(plugin_type.info_nomore.id!=0 && plugin_type.info_nomore.num>0){
		ajax_('info_nomore',plugin_type.info_nomore.id,plugin_type.info_nomore.num);
		plugin_type.info_nomore.num = 0;
	}	
	if(plugin_type.info_pic2.id!=0 && plugin_type.info_pic2.num>0){
		ajax_('info_pic2',plugin_type.info_pic2.id,plugin_type.info_pic2.num);
		plugin_type.info_pic2.num = 0;
	}
	if(plugin_type.input.id!=0 && plugin_type.input.num>0){
		ajax_('input',plugin_type.input.id,plugin_type.input.num);
		plugin_type.input.num = 0;
	}	
	if(plugin_type.multi_contact.id!=0 && plugin_type.multi_contact.num>0){
		ajax_('multi_contact',plugin_type.multi_contact.id,plugin_type.multi_contact.num);
		plugin_type.multi_contact.num = 0;
	}	
	if(plugin_type.video.id!=0 && plugin_type.video.num>0){
		ajax_('video',plugin_type.video.id,plugin_type.video.num);
		plugin_type.video.num = 0;
	}
}

/**
 * ·¢ËÍÕ¾µãÍ³¼ÆÇëÇó
 */
function ajax_analysite(){
	var channel_id = location.search.substr(location.search.indexOf("channel=") + 8);
	channel_id= channel_id.match(/^\d+/) ; 
	if (!channel_id || isNaN(channel_id) || channel_id<0) {
		channel_id = 1;
	}
	var activeId = $('#activeId').val();
	$.ajax({
		url: '/'+SYS_SITE_ID+'/site/analyse/'+activeId,
		cache: false,
		dataType: 'html',
		async: false,
		type:'GET',
		data: '&channel='+channel_id+'&type=car',
	});	
}

/**
 * °ó¶¨Ò³Ãæ·ÖÏí¡¢µç»°Í³¼Æ
 */
$(function(){
	$('.share').bind('click',function(){
		one_ajax_analyplugin('share');
		$(".m-page").off('mousedown touchstart');
		$(".m-page").off('mousemove touchmove');
		$(".m-page").off('mouseup touchend mouseout');
		
		var u = navigator.userAgent; // ¿Í»§¶Ë»·¾³ÐÅÏ¢
		if(u.indexOf('MicroMessenger')>-1){
			$('.weixin-share').css('display','block');
			// ½ûÖ¹¹ö¶¯
			$(document.body).css('overflow','hidden');
			$(document.body).on('touchmove',function(e){e.preventDefault();});
		}else{
			$(this).next().addClass('open');
			$(this).next().css('z-index',1);
			$(this).next().children().addClass('open');
		}
	});
	
	$('.tel').bind('click',function(){ one_ajax_analyplugin('tel');});
	var one_ajax_analyplugin = function(type){
		var activeId = $('#activeId').val();
		var id = $(this).attr('data-id');
		$.ajax({
			url: '/'+SYS_SITE_ID+'/analyseplugin/plugin/'+activeId,
			cache: false,
			dataType: 'html',
			async: true,
			type:'GET',
			data: '&activity_id='+activeId+'&id='+id+'&plugtype='+type,
		});
	}	
});

/**
 * ¹Ø±ÕÎ¢ÐÅ·ÖÏí
 */
function cancle_share_weixin(obj){
	$(obj).css('display','none');
	// »¹Ô­¹ö¶¯Ìõ
	$(document.body).css('overflow','auto');
	$(document.body).off('touchmove');

	$(".m-page").on('mousedown touchstart',page_touchstart);
	$(".m-page").on('mousemove touchmove',page_touchmove);
	$(".m-page").on('mouseup touchend mouseout',page_touchend);
}

/**
 * È¡Ïû·ÖÏí
 */
function cancle_share(obj){
	$(".m-page").on('mousedown touchstart',page_touchstart);
	$(".m-page").on('mousemove touchmove',page_touchmove);
	$(".m-page").on('mouseup touchend mouseout',page_touchend);

	$(obj.parentNode).removeClass('open');
	setTimeout(function(){
		$(obj.parentNode.parentNode).removeClass('open');
		$(obj.parentNode.parentNode).css('z-index','-1');
	},'400');
}

/*
**Ô¤Ô¼±íµ¥
*/
$(function(){
	$("p.submit-input").click(function(e){
		e.preventDefault();
		var form = $(this).parents('form');
		var sumbit_val = form_verity(form);
		if(sumbit_val){
			ajax_form_submit(form);
		}else{
			$(".popup_sucess").removeClass("on");
			$(".popup_error").addClass("on");
			setTimeout(function(){
				$(".popup").removeClass("on");
			},3000)
		}
	})
});

/**
 * ±íµ¥ÑéÖ¤ÊÇ·ñÍ¨¹ý object form jquery±íµ¥¶ÔÏó
 */
function form_verity(form){	
	if(form.find("input[name='sex']").length>0 && form.find("input[name='sex']").val()==''){
		$('.popup_error').html('ÏëÏë£¬¸ÃÔõÃ´³ÆºôÄúÄØ£¿');
		return false;	
	}
	
	var name = form.find("input[name='name']").val();
	if(form.find("input[name='name']").length>0 && $.trim(name)==''){
		$('.popup_error').html('²»ÄÜÂäÏÂÐÕÃûÅ¶£¡');
		return false;
	}
	if(!name.match(/^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/)){
		$('.popup_error').html('ÕâÃû×ÖÌ«¹ÖÁË£¡');
		return false;		
	}
	var tel = form.find("input[name='tel']").val();
	if(form.find("input[name='tel']").length>0 && $.trim(tel)==''){
		$('.popup_error').html('ÓÐ¸öÁªÏµ·½Ê½£¬¾Í¸üºÃÁË£¡');
		return false;
	}
	if(!tel.match(/^1[0-9][0-9]\d{8}$/)){
		$('.popup_error').html('ÕâºÅÂë,¿É´ò²»Í¨...');
		return false;
	}
	var date = form.find("input[name='date']").val();
	var time = form.find("input[name='time']").val();
	if(form.find("input[name='date']").length>0 && ($.trim(date)=='' || $.trim(time)=='')){
		$('.popup_error').html('ÄúÄÜ¸ø¸ö¾ßÌåÊ±¼ä£¬¾ÍÍêÃÀÁË£¡');
		return false;		
	}
	return true;
}

/**
 * ajaxÒì²½Ìá½»±íµ¥Êý¾Ý
 * object form ±íµ¥jquery¶ÔÏó
 */
function ajax_form_submit(form){
	var name = form.find("input[name='name']").val();
	var tel = form.find("input[name='tel']").val();
	var date = form.find("input[name='date']").val();
	var time = form.find("input[name='time']").val();
	var sex = form.find("input[name='sex']").val();
	var activeId = $('#activeId').val();
	
	loading('loading');
	
	$.ajax({
		url: '/'+SYS_SITE_ID+'/car/submit/'+activeId,
		cache: false,
		dataType: 'html',
		async: false,
		type:'POST',
		data: '&name='+name+'&tel='+tel+'&date='+date+'&time='+time+'&sex='+sex,
		success: function(msg){
			
			loading('end');
		
			if(msg==1){
				$('.popup_sucess').html('ÄúÒÑ¾­³É¹¦Ô¤Ô¼¹ýÁË!');
			}else if(msg==2){
				$('.popup_sucess').html('Ìá½»³É¹¦£¡Ð»Ð»ÄúµÄÔ¤Ô¼£¡');
			}else{
				$('.popup_sucess').html('±§Ç¸£¡ÏµÍ³·±Ã¦ÖÐ...');
			}
			$(".popup_sucess").addClass("on");
			$(".popup_error").removeClass("on");
			setTimeout(function(){
				$(".popup").removeClass("on");
			},4000)
		}
	});
}

/**
 * ajaxÒì²½Ìá½»±íµ¥Êý¾Ý
 * object form ±íµ¥jquery¶ÔÏó
 */
function ajax_custom_submit(form){
	var success_msg = $.trim($('.widthdesc-form').find('input[name="success_msg"]').val());
	var activity_id = parseInt(document.getElementById('activity_id').innerHTML,10);
	if (success_msg == '') {
		success_msg = '¸ÐÐ»ÄúµÄÖ§³Ö£¡ÎÒÃÇ»á¾¡¿ìÓëÄúÈ¡µÃÁªÏµ£¡';
	}
	
	$.ajax({
		url: '/'+SYS_SITE_ID+'/realty/custom_submit/'+activity_id,
		cache: false,
		dataType: 'json',
		async: false,
		type:'POST',
		data: form.serialize(),
		error: function(msg){
			alert(msg.data);
		},
		success: function(msg){
			if(msg.success){
				dialogShow('', success_msg);
			} 
			$('#nav-dots').css('display','block');
		}
	});
}

/**
 * loadingÍ¼±êÉèÖÃ
 * string type loading:³öÏÖ¼ÓÔØÍ¼Æ¬£»end ½áÊø¼ÓÔØÍ¼Æ¬
 */
function loading(type){
	if('loading'==type){
		$('.loading').css({display:'block'});
	}else if('end'==type){
		$('.loading').css({display:'none'});
	}
}

/**
 * °ó¶¨ÐÔ±ðÑ¡Ôñ
 */
$(function(){
	$('.sex').bind('click',function(){
		var sex = $(this).attr('data-sex');
		$(this.parentNode).find('input').val(sex);
		$(this.parentNode).find('strong').removeClass('on');
		$(this).find('strong').addClass('on');
	});
});

/**
 * °ó¶¨ÐÔ±ðÑ¡Ôñ
 */
$(function(){
	$('.student').bind('click',function(){
		var student = $(this).attr('data-student');
		$(this.parentNode).find('input').val(student);
		$(this.parentNode).find('strong').removeClass('on');
		$(this).find('strong').addClass('on');
	});
});

/*
 * *×Ô¶¨ÒåÔ¤Ô¼±íµ¥
 */
$(function(){
	$("p.submit-custom").click(function(e){
		e.preventDefault();
		var form = $(this).parents('form');
		var valid = check_input(form, 'base');
		if (valid) {
			valid = check_input(form);
			if (!valid && !$('.edit-more-info').hasClass('edited')) {
				$('.edit-more-info').click();
			}
		}
		if(valid){
			
			ajax_custom_submit(form);
		}
	})
})

/**
 * ±íµ¥ÍêÉÆ¸ü¶àÐÅÏ¢
 */
$(function(){
	$('.edit-more-info').click(function(){
		var form = $(this).parents('form');
		if (check_input(form, 'base')) {
			$('.wct_form').addClass('change');
		}else return;
	});
	$('.submit-3').click(function(){
		var form = $(this).parents('form');
		if (check_input(form, 'base')) {
			$('.wct_form').removeClass('change');
		}else return;
	})
});
var if_ajax_custom_submit = false;  // ÊÇ·ñÌá½»¹ý±íµ¥
/**
 * ajaxÒì²½Ìá½»±íµ¥Êý¾Ý object form ±íµ¥jquery¶ÔÏó
 */
function ajax_custom_submit(form){
	var activeId = $('#activeId').val();
	
	var successTips = 'ÄúÒÑ±¨Ãû³É¹¦£¬ÎÒÃÇ»á¾¡¿ìÓëÄúÈ¡µÃÁªÏµ£¡';
	if($('#success-tips').length>0){
		successTips = $('#success-tips').val();
	}
	if(if_ajax_custom_submit){
		$('.popup_sucess').html(successTips);
		$(".popup_sucess").addClass("on");
		$(".popup_error").removeClass("on");
		setTimeout(function(){
			$(".popup").removeClass("on");
		},6000);
		return true;
	}
	loading('loading');
	if_ajax_custom_submit = true;
	$.ajax({
		url: '/'+SYS_SITE_ID+'/car/custom_submit/'+activeId,
		cache: false,
		dataType: 'json',
		async: false,
		type:'POST',
		data: form.serialize(),
		success: function(msg){
			loading('end');
			if(msg.data==1){
				$('.popup_sucess').html(successTips);
				if ($('.wct_form').hasClass('change')) {
					$('.wct_form').removeClass('change');
				}
			}else if(msg.data==2){
				$('.popup_sucess').html(successTips);
				if ($('.wct_form').hasClass('change')) {
					$('.wct_form').removeClass('change');
				}
			}else{
				$('.popup_sucess').html('±§Ç¸£¡ÏµÍ³·±Ã¦ÖÐ...');
			}
			$(".popup_sucess").addClass("on");
			$(".popup_error").removeClass("on");
			setTimeout(function(){
				$(".popup").removeClass("on");
			},6000)
		}
	});
}

/*
** ÍêÕûÐÅÏ¢±íµ¥ÑéÖ¤
*/
function check_input (form, type){
	var valid = true;
	if (type == 'base') {
		var inputs = form.find('.base-info-input:input');
	} else {
		var inputs = form.find(':input');
	}
	inputs.each(function(i, e){
		if(this.name != '' && this.name != 'undefined'){		
			var empty_tip = '';
			var reg_tip = '';
			var reg = '';
			var max = null;
			var mim = null;
			switch (this.name) {
				case 'name':
					reg = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/;
					empty_tip = '²»ÄÜÂäÏÂÐÕÃûÅ¶£¡';
					reg_tip = 'ÕâÃû×ÖÌ«¹ÖÁË£¡';
					break;
				case 'mobile':
					reg = /^1[0-9][0-9]\d{8}$/;
					empty_tip = 'ÓÐ¸öÁªÏµ·½Ê½£¬¾Í¸üºÃÁË£¡';
					reg_tip = 'ÕâºÅÂë,¿É´ò²»Í¨... ';
					break;
				case 'sex':
					empty_tip = 'ÏëÏë£¬¸ÃÔõÃ´³ÆºôÄúÄØ£¿';
					break;
				case 'email':
					reg = /(^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$)/i;
					reg_tip = 'ÓÊÏä¸ñÊ½ÓÐÎÊÌâÅ¶£¡';
					break;
				case 'company':
					reg = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/;
					reg_tip = 'Õâ¸ö¹«Ë¾Ì«Ææ¹ÖÁË£¡';
					break;
				case 'position':
					reg = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/;
					reg_tip = 'Õâ¸öÖ°Î»Ì«Ææ¹ÖÁË£¡';
					break;
				case 'date':
					break;
				case 'time':
					break;
				case 'age':
					reg = /^([3-9])|([1-9][0-9])|([1][0-3][0-9])$/;
					reg_tip = 'ÕâÄêÁä¿É²»¶ÔÅ¶£¡' ;
					break;
			}
			if (empty_tip != undefined && empty_tip != '' && $.trim($(e).val()) == '') {
				showMessage(empty_tip, true);
				// µÚÒ»Ò³µã»÷Ìá½»ÑéÖ¤
				if($(this).hasClass('.submit-1')){
					$('.wct_form').addClass('change');
				}
				//µÚ¶þÒ³µã»÷Ìá½»ÑéÖ¤
				else if($(this).hasClass('.submit-2')){
					$('.wct_form').removeClass('change');	
				}
				valid = false;
				return false;	
			}
			if (reg != undefined && reg != '' && $.trim($(e).val()) != '') {
				if(!$(e).val().match(reg)){
					showMessage(reg_tip, true);
					valid = false;
					return false;		
				}
			}
		}
	});
	if (valid == false) {
		return false;
	}else{
		return true;
	}
}

/*
** ÏÔÊ¾ÑéÖ¤ÐÅÏ¢
*/
function showMessage(msg, error) {
	if (error) {
		$('.popup_error').html(msg);
		$(".popup_error").addClass("on");
		$(".popup_sucess").removeClass("on");
		setTimeout(function(){
			$(".popup").removeClass("on");
		},2000);
	} else {
		$(".popup_sucess").addClass("on");
		$(".popup_error").removeClass("on");
		setTimeout(function(){
			$(".popup").removeClass("on");
		},2000);
	}
}
;function htmlEncode(e){return e.replace(/&/g,"&amp;").replace(/ /g,"&nbsp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/"/g,"&quot;")}function htmlDecode(e){return e.replace(/&#39;/g,"'").replace(/<br\s*(\/)?\s*>/g,"\n").replace(/&nbsp;/g," ").replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/&quot;/g,'"').replace(/&amp;/g,"&")}function parseParams(e){e==undefined&&(e=window.location.search);var t=e.replace(/^\?/,"").split("&"),n=0,r,i={},s,o,u;while((r=t[n++])!==undefined)s=r.match(/^([^=&]*)=(.*)$/),s&&(o=decodeURIComponent(s[1]),u=decodeURIComponent(s[2]),i[o]=u);return i}function parseUrl(e){var t=new RegExp("(http[s]{0,1}|ftp)://([a-zA-Z0-9\\.\\-]+\\.[a-zA-Z]{2,4})(:\\d+)?(/[a-zA-Z0-9\\.\\-~!@#$%^&amp;*+:_/<>]*)?([\\?a-zA-Z0-9\\.\\-~!@$%^&amp;*+?:_/<>=]*)?(#\\w+)?"),n=e.match(t);return n!=null?{protocol:n[1],domain:n[2],port:n[3],path:n[4],query_str:n[5],sharp:n[6]}:null}function replaceEmoji(e){var t={url:"http://res.mail.qq.com/zh_CN/images/mo/DEFAULT2/",data:{0:"Î¢Ð¦",1:"Æ²×ì",2:"É«",3:"·¢´ô",4:"µÃÒâ",5:"Á÷Àá",6:"º¦Ðß",7:"±Õ×ì",8:"Ë¯",9:"´ó¿Þ",10:"ÞÏÞÎ",11:"·¢Å­",12:"µ÷Æ¤",13:"ßÚÑÀ",14:"¾ªÑÈ",15:"ÄÑ¹ý",16:"¿á",17:"Àäº¹",18:"×¥¿ñ",19:"ÍÂ",20:"ÍµÐ¦",21:"¿É°®",22:"°×ÑÛ",23:"°ÁÂý",24:"¼¢¶ö",25:"À§",26:"¾ª¿Ö",27:"Á÷º¹",28:"º©Ð¦",29:"´ó±ø",30:"·Ü¶·",31:"ÖäÂî",32:"ÒÉÎÊ",33:"Ðê",34:"ÔÎ",35:"ÕÛÄ¥",36:"Ë¥",37:"÷¼÷Ã",38:"ÇÃ´ò",39:"ÔÙ¼û",40:"²Áº¹",41:"¿Ù±Ç",42:"¹ÄÕÆ",43:"ôÜ´óÁË",44:"»µÐ¦",45:"×óºßºß",46:"ÓÒºßºß",47:"¹þÇ·",48:"±ÉÊÓ",49:"Î¯Çü",50:"¿ì¿ÞÁË",51:"ÒõÏÕ",52:"Ç×Ç×",53:"ÏÅ",54:"¿ÉÁ¯",55:"²Ëµ¶",56:"Î÷¹Ï",57:"Æ¡¾Æ",58:"ÀºÇò",59:"Æ¹ÅÒ",60:"¿§·È",61:"·¹",62:"ÖíÍ·",63:"Ãµ¹å",64:"µòÐ»",65:"Ê¾°®",66:"°®ÐÄ",67:"ÐÄËé",68:"µ°¸â",69:"ÉÁµç",70:"Õ¨µ¯",71:"µ¶",72:"×ãÇò",73:"Æ°³æ",74:"±ã±ã",75:"ÔÂÁÁ",76:"Ì«Ñô",77:"ÀñÎï",78:"Óµ±§",79:"Ç¿",80:"Èõ",81:"ÎÕÊÖ",82:"Ê¤Àû",83:"±§È­",84:"¹´Òý",85:"È­Í·",86:"²î¾¢",87:"°®Äã",88:"NO",89:"OK",90:"°®Çé",91:"·ÉÎÇ",92:"ÌøÌø",93:"·¢¶¶",94:"âæ»ð",95:"×ªÈ¦",96:"¿ÄÍ·",97:"»ØÍ·",98:"ÌøÉþ",99:"»ÓÊÖ",100:"¼¤¶¯",101:"½ÖÎè",102:"Ï×ÎÇ",103:"×óÌ«¼«",104:"ÓÒÌ«¼«"},ext:".gif"},n,r,i=t.url,s=t.ext,o=t.data;for(n in o)r=new RegExp("/"+o[n],"g"),e=e.replace(r,'<img src="'+i+n+s+'" alt="mo-'+o[n]+'"/>');return e};
;(function(){
        var onBridgeReady = function () {
			var appId  = "",	//$('#txt-wx').data('appid'),
				link   = window.location,	//$('#txt-wx').data('link'),
				title  = htmlDecode($('title').text()),	// htmlDecode($('#txt-wx').data('title')),
				desc   = htmlDecode($('title').text() + "£¬¾´Çë·ÃÎÊ£¡"),	//<br/>¹ÙÍøµØÖ·£º" + window.location.href),	// htmlDecode($('#txt-wx').data('desc')),
				fakeid = "MTA0NjEwNDk0Mw==",
				desc = desc || link,
				imgUrl = "";
			
			var image = $.trim($('link[data-logo]').attr('href'));	// $('#txt-wx').data('img')
			if (image=='' || image==undefined) {
				image = $.trim($('img[data-share-logo]').attr('src'));
			}
			if (image=='' || image==undefined) {
				image = $.trim($('img:first').attr('src'));
			}
			if (image!='' && image!=undefined) {
				imgUrl = "http://" + window.location.host + image;
			}
			
			if ("1" == "0") {
				WeixinJSBridge.call("hideOptionMenu");
			}
			
			jQuery("#post-user").click(function(){
				WeixinJSBridge.invoke('profile',{'username':'gh_0752b2eb5b99','scene':'57'});
			});
			
			// ·¢ËÍ¸øºÃÓÑ;
			WeixinJSBridge.on('menu:share:appmessage', function(argv){
				WeixinJSBridge.invoke('sendAppMessage',{
					"appid"      : appId,
					"img_url"    : imgUrl,
					"img_width"  : "640",
					"img_height" : "640",
					"link"       : window.location.href,
					"desc"       : desc,
					"title"      : title
				},
				function(res) {
				});
			});
			
			// ·ÖÏíµ½ÅóÓÑÈ¦;
			WeixinJSBridge.on('menu:share:timeline', function(argv){
				WeixinJSBridge.invoke('shareTimeline',{
					"img_url"    : imgUrl,
					"img_width"  : "640",
					"img_height" : "640",
					"link"       : window.location.href,
					"desc"       : desc,
					"title"      : title
				}, function(res) {
				});
			});
			
			// ·ÖÏíµ½Î¢²©;
			var weiboContent = '';
			WeixinJSBridge.on('menu:share:weibo', function(argv){
				WeixinJSBridge.invoke('shareWeibo',{
					"content" : title + window.location.href,
					"url"     : window.location.href
				},
				function(res) {
				});
			});
		};

        if(document.addEventListener){
			document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
		} else if(document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady'   , onBridgeReady);
			document.attachEvent('onWeixinJSBridgeReady' , onBridgeReady);
		}
})();