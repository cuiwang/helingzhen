/* Minification failed. Returning unminified contents.
(19897,33-60): run-time error JS5017: Syntax error in regular expression: /[\xCD\xCE]([^])[\xCD\xCE]/
 */
;(function () {
	'use strict';

	/**
	 * @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.
	 *
	 * @codingstandard ftlabs-jsv2
	 * @copyright The Financial Times Limited [All Rights Reserved]
	 * @license MIT License (see LICENSE.txt)
	 */

	/*jslint browser:true, node:true*/
	/*global define, Event, Node*/


	/**
	 * Instantiate fast-clicking listeners on the specified layer.
	 *
	 * @constructor
	 * @param {Element} layer The layer to listen on
	 * @param {Object} [options={}] The options to override the defaults
	 */
	function FastClick(layer, options) {
		var oldOnClick;

		options = options || {};

		/**
		 * Whether a click is currently being tracked.
		 *
		 * @type boolean
		 */
		this.trackingClick = false;


		/**
		 * Timestamp for when click tracking started.
		 *
		 * @type number
		 */
		this.trackingClickStart = 0;


		/**
		 * The element being tracked for a click.
		 *
		 * @type EventTarget
		 */
		this.targetElement = null;


		/**
		 * X-coordinate of touch start event.
		 *
		 * @type number
		 */
		this.touchStartX = 0;


		/**
		 * Y-coordinate of touch start event.
		 *
		 * @type number
		 */
		this.touchStartY = 0;


		/**
		 * ID of the last touch, retrieved from Touch.identifier.
		 *
		 * @type number
		 */
		this.lastTouchIdentifier = 0;


		/**
		 * Touchmove boundary, beyond which a click will be cancelled.
		 *
		 * @type number
		 */
		this.touchBoundary = options.touchBoundary || 10;


		/**
		 * The FastClick layer.
		 *
		 * @type Element
		 */
		this.layer = layer;

		/**
		 * The minimum time between tap(touchstart and touchend) events
		 *
		 * @type number
		 */
		this.tapDelay = options.tapDelay || 200;

		/**
		 * The maximum time for a tap
		 *
		 * @type number
		 */
		this.tapTimeout = options.tapTimeout || 700;

		if (FastClick.notNeeded(layer)) {
			return;
		}

		// Some old versions of Android don't have Function.prototype.bind
		function bind(method, context) {
			return function() { return method.apply(context, arguments); };
		}


		var methods = ['onMouse', 'onClick', 'onTouchStart', 'onTouchMove', 'onTouchEnd', 'onTouchCancel'];
		var context = this;
		for (var i = 0, l = methods.length; i < l; i++) {
			context[methods[i]] = bind(context[methods[i]], context);
		}

		// Set up event handlers as required
		if (deviceIsAndroid) {
			layer.addEventListener('mouseover', this.onMouse, true);
			layer.addEventListener('mousedown', this.onMouse, true);
			layer.addEventListener('mouseup', this.onMouse, true);
		}

		layer.addEventListener('click', this.onClick, true);
		layer.addEventListener('touchstart', this.onTouchStart, false);
		layer.addEventListener('touchmove', this.onTouchMove, false);
		layer.addEventListener('touchend', this.onTouchEnd, false);
		layer.addEventListener('touchcancel', this.onTouchCancel, false);

		// Hack is required for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
		// which is how FastClick normally stops click events bubbling to callbacks registered on the FastClick
		// layer when they are cancelled.
		if (!Event.prototype.stopImmediatePropagation) {
			layer.removeEventListener = function(type, callback, capture) {
				var rmv = Node.prototype.removeEventListener;
				if (type === 'click') {
					rmv.call(layer, type, callback.hijacked || callback, capture);
				} else {
					rmv.call(layer, type, callback, capture);
				}
			};

			layer.addEventListener = function(type, callback, capture) {
				var adv = Node.prototype.addEventListener;
				if (type === 'click') {
					adv.call(layer, type, callback.hijacked || (callback.hijacked = function(event) {
						if (!event.propagationStopped) {
							callback(event);
						}
					}), capture);
				} else {
					adv.call(layer, type, callback, capture);
				}
			};
		}

		// If a handler is already declared in the element's onclick attribute, it will be fired before
		// FastClick's onClick handler. Fix this by pulling out the user-defined handler function and
		// adding it as listener.
		if (typeof layer.onclick === 'function') {

			// Android browser on at least 3.2 requires a new reference to the function in layer.onclick
			// - the old one won't work if passed to addEventListener directly.
			oldOnClick = layer.onclick;
			layer.addEventListener('click', function(event) {
				oldOnClick(event);
			}, false);
			layer.onclick = null;
		}
	}

	/**
	* Windows Phone 8.1 fakes user agent string to look like Android and iPhone.
	*
	* @type boolean
	*/
	var deviceIsWindowsPhone = navigator.userAgent.indexOf("Windows Phone") >= 0;

	/**
	 * Android requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0 && !deviceIsWindowsPhone;


	/**
	 * iOS requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent) && !deviceIsWindowsPhone;


	/**
	 * iOS 4 requires an exception for select elements.
	 *
	 * @type boolean
	 */
	var deviceIsIOS4 = deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);


	/**
	 * iOS 6.0-7.* requires the target element to be manually derived
	 *
	 * @type boolean
	 */
	var deviceIsIOSWithBadTarget = deviceIsIOS && (/OS [6-7]_\d/).test(navigator.userAgent);

	/**
	 * BlackBerry requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsBlackBerry10 = navigator.userAgent.indexOf('BB10') > 0;

	/**
	 * Determine whether a given element requires a native click.
	 *
	 * @param {EventTarget|Element} target Target DOM element
	 * @returns {boolean} Returns true if the element needs a native click
	 */
	FastClick.prototype.needsClick = function(target) {
		switch (target.nodeName.toLowerCase()) {

		// Don't send a synthetic click to disabled inputs (issue #62)
		case 'button':
		case 'select':
		case 'textarea':
			if (target.disabled) {
				return true;
			}

			break;
		case 'input':

			// File inputs need real clicks on iOS 6 due to a browser bug (issue #68)
			if ((deviceIsIOS && target.type === 'file') || target.disabled) {
				return true;
			}

			break;
		case 'label':
		case 'iframe': // iOS8 homescreen apps can prevent events bubbling into frames
		case 'video':
			return true;
		}

		return (/\bneedsclick\b/).test(target.className);
	};


	/**
	 * Determine whether a given element requires a call to focus to simulate click into element.
	 *
	 * @param {EventTarget|Element} target Target DOM element
	 * @returns {boolean} Returns true if the element requires a call to focus to simulate native click.
	 */
	FastClick.prototype.needsFocus = function(target) {
		switch (target.nodeName.toLowerCase()) {
		case 'textarea':
			return true;
		case 'select':
			return !deviceIsAndroid;
		case 'input':
			switch (target.type) {
			case 'button':
			case 'checkbox':
			case 'file':
			case 'image':
			case 'radio':
			case 'submit':
				return false;
			}

			// No point in attempting to focus disabled inputs
			return !target.disabled && !target.readOnly;
		default:
			return (/\bneedsfocus\b/).test(target.className);
		}
	};


	/**
	 * Send a click event to the specified element.
	 *
	 * @param {EventTarget|Element} targetElement
	 * @param {Event} event
	 */
	FastClick.prototype.sendClick = function(targetElement, event) {
		var clickEvent, touch;

		// On some Android devices activeElement needs to be blurred otherwise the synthetic click will have no effect (#24)
		if (document.activeElement && document.activeElement !== targetElement) {
			document.activeElement.blur();
		}

		touch = event.changedTouches[0];

		// Synthesise a click event, with an extra attribute so it can be tracked
		clickEvent = document.createEvent('MouseEvents');
		clickEvent.initMouseEvent(this.determineEventType(targetElement), true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
		clickEvent.forwardedTouchEvent = true;
		targetElement.dispatchEvent(clickEvent);
	};

	FastClick.prototype.determineEventType = function(targetElement) {

		//Issue #159: Android Chrome Select Box does not open with a synthetic click event
		if (deviceIsAndroid && targetElement.tagName.toLowerCase() === 'select') {
			return 'mousedown';
		}

		return 'click';
	};


	/**
	 * @param {EventTarget|Element} targetElement
	 */
	FastClick.prototype.focus = function(targetElement) {
		var length;

		// Issue #160: on iOS 7, some input elements (e.g. date datetime month) throw a vague TypeError on setSelectionRange. These elements don't have an integer value for the selectionStart and selectionEnd properties, but unfortunately that can't be used for detection because accessing the properties also throws a TypeError. Just check the type instead. Filed as Apple bug #15122724.
		if (deviceIsIOS && targetElement.setSelectionRange && targetElement.type.indexOf('date') !== 0 && targetElement.type !== 'time' && targetElement.type !== 'month') {
			length = targetElement.value.length;
			targetElement.setSelectionRange(length, length);
		} else {
			targetElement.focus();
		}
	};


	/**
	 * Check whether the given target element is a child of a scrollable layer and if so, set a flag on it.
	 *
	 * @param {EventTarget|Element} targetElement
	 */
	FastClick.prototype.updateScrollParent = function(targetElement) {
		var scrollParent, parentElement;

		scrollParent = targetElement.fastClickScrollParent;

		// Attempt to discover whether the target element is contained within a scrollable layer. Re-check if the
		// target element was moved to another parent.
		if (!scrollParent || !scrollParent.contains(targetElement)) {
			parentElement = targetElement;
			do {
				if (parentElement.scrollHeight > parentElement.offsetHeight) {
					scrollParent = parentElement;
					targetElement.fastClickScrollParent = parentElement;
					break;
				}

				parentElement = parentElement.parentElement;
			} while (parentElement);
		}

		// Always update the scroll top tracker if possible.
		if (scrollParent) {
			scrollParent.fastClickLastScrollTop = scrollParent.scrollTop;
		}
	};


	/**
	 * @param {EventTarget} targetElement
	 * @returns {Element|EventTarget}
	 */
	FastClick.prototype.getTargetElementFromEventTarget = function(eventTarget) {

		// On some older browsers (notably Safari on iOS 4.1 - see issue #56) the event target may be a text node.
		if (eventTarget.nodeType === Node.TEXT_NODE) {
			return eventTarget.parentNode;
		}

		return eventTarget;
	};


	/**
	 * On touch start, record the position and scroll offset.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchStart = function(event) {
		var targetElement, touch, selection;

		// Ignore multiple touches, otherwise pinch-to-zoom is prevented if both fingers are on the FastClick element (issue #111).
		if (event.targetTouches.length > 1) {
			return true;
		}

		targetElement = this.getTargetElementFromEventTarget(event.target);
		touch = event.targetTouches[0];

		if (deviceIsIOS) {

			// Only trusted events will deselect text on iOS (issue #49)
			selection = window.getSelection();
			if (selection.rangeCount && !selection.isCollapsed) {
				return true;
			}

			if (!deviceIsIOS4) {

				// Weird things happen on iOS when an alert or confirm dialog is opened from a click event callback (issue #23):
				// when the user next taps anywhere else on the page, new touchstart and touchend events are dispatched
				// with the same identifier as the touch event that previously triggered the click that triggered the alert.
				// Sadly, there is an issue on iOS 4 that causes some normal touch events to have the same identifier as an
				// immediately preceeding touch event (issue #52), so this fix is unavailable on that platform.
				// Issue 120: touch.identifier is 0 when Chrome dev tools 'Emulate touch events' is set with an iOS device UA string,
				// which causes all touch events to be ignored. As this block only applies to iOS, and iOS identifiers are always long,
				// random integers, it's safe to to continue if the identifier is 0 here.
				if (touch.identifier && touch.identifier === this.lastTouchIdentifier) {
					event.preventDefault();
					return false;
				}

				this.lastTouchIdentifier = touch.identifier;

				// If the target element is a child of a scrollable layer (using -webkit-overflow-scrolling: touch) and:
				// 1) the user does a fling scroll on the scrollable layer
				// 2) the user stops the fling scroll with another tap
				// then the event.target of the last 'touchend' event will be the element that was under the user's finger
				// when the fling scroll was started, causing FastClick to send a click event to that layer - unless a check
				// is made to ensure that a parent layer was not scrolled before sending a synthetic click (issue #42).
				this.updateScrollParent(targetElement);
			}
		}

		this.trackingClick = true;
		this.trackingClickStart = event.timeStamp;
		this.targetElement = targetElement;

		this.touchStartX = touch.pageX;
		this.touchStartY = touch.pageY;

		// Prevent phantom clicks on fast double-tap (issue #36)
		if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
			event.preventDefault();
		}

		return true;
	};


	/**
	 * Based on a touchmove event object, check whether the touch has moved past a boundary since it started.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.touchHasMoved = function(event) {
		var touch = event.changedTouches[0], boundary = this.touchBoundary;

		if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
			return true;
		}

		return false;
	};


	/**
	 * Update the last position.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchMove = function(event) {
		if (!this.trackingClick) {
			return true;
		}

		// If the touch has moved, cancel the click tracking
		if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
			this.trackingClick = false;
			this.targetElement = null;
		}

		return true;
	};


	/**
	 * Attempt to find the labelled control for the given label element.
	 *
	 * @param {EventTarget|HTMLLabelElement} labelElement
	 * @returns {Element|null}
	 */
	FastClick.prototype.findControl = function(labelElement) {

		// Fast path for newer browsers supporting the HTML5 control attribute
		if (labelElement.control !== undefined) {
			return labelElement.control;
		}

		// All browsers under test that support touch events also support the HTML5 htmlFor attribute
		if (labelElement.htmlFor) {
			return document.getElementById(labelElement.htmlFor);
		}

		// If no for attribute exists, attempt to retrieve the first labellable descendant element
		// the list of which is defined here: http://www.w3.org/TR/html5/forms.html#category-label
		return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea');
	};


	/**
	 * On touch end, determine whether to send a click event at once.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchEnd = function(event) {
		var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;

		if (!this.trackingClick) {
			return true;
		}

		// Prevent phantom clicks on fast double-tap (issue #36)
		if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
			this.cancelNextClick = true;
			return true;
		}

		if ((event.timeStamp - this.trackingClickStart) > this.tapTimeout) {
			return true;
		}

		// Reset to prevent wrong click cancel on input (issue #156).
		this.cancelNextClick = false;

		this.lastClickTime = event.timeStamp;

		trackingClickStart = this.trackingClickStart;
		this.trackingClick = false;
		this.trackingClickStart = 0;

		// On some iOS devices, the targetElement supplied with the event is invalid if the layer
		// is performing a transition or scroll, and has to be re-detected manually. Note that
		// for this to function correctly, it must be called *after* the event target is checked!
		// See issue #57; also filed as rdar://13048589 .
		if (deviceIsIOSWithBadTarget) {
			touch = event.changedTouches[0];

			// In certain cases arguments of elementFromPoint can be negative, so prevent setting targetElement to null
			targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
			targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent;
		}

		targetTagName = targetElement.tagName.toLowerCase();
		if (targetTagName === 'label') {
			forElement = this.findControl(targetElement);
			if (forElement) {
				this.focus(targetElement);
				if (deviceIsAndroid) {
					return false;
				}

				targetElement = forElement;
			}
		} else if (this.needsFocus(targetElement)) {

			// Case 1: If the touch started a while ago (best guess is 100ms based on tests for issue #36) then focus will be triggered anyway. Return early and unset the target element reference so that the subsequent click will be allowed through.
			// Case 2: Without this exception for input elements tapped when the document is contained in an iframe, then any inputted text won't be visible even though the value attribute is updated as the user types (issue #37).
			if ((event.timeStamp - trackingClickStart) > 100 || (deviceIsIOS && window.top !== window && targetTagName === 'input')) {
				this.targetElement = null;
				return false;
			}

			this.focus(targetElement);
			this.sendClick(targetElement, event);

			// Select elements need the event to go through on iOS 4, otherwise the selector menu won't open.
			// Also this breaks opening selects when VoiceOver is active on iOS6, iOS7 (and possibly others)
			if (!deviceIsIOS || targetTagName !== 'select') {
				this.targetElement = null;
				event.preventDefault();
			}

			return false;
		}

		if (deviceIsIOS && !deviceIsIOS4) {

			// Don't send a synthetic click event if the target element is contained within a parent layer that was scrolled
			// and this tap is being used to stop the scrolling (usually initiated by a fling - issue #42).
			scrollParent = targetElement.fastClickScrollParent;
			if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
				return true;
			}
		}

		// Prevent the actual click from going though - unless the target node is marked as requiring
		// real clicks or if it is in the whitelist in which case only non-programmatic clicks are permitted.
		if (!this.needsClick(targetElement)) {
			event.preventDefault();
			this.sendClick(targetElement, event);
		}

		return false;
	};


	/**
	 * On touch cancel, stop tracking the click.
	 *
	 * @returns {void}
	 */
	FastClick.prototype.onTouchCancel = function() {
		this.trackingClick = false;
		this.targetElement = null;
	};


	/**
	 * Determine mouse events which should be permitted.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onMouse = function(event) {

		// If a target element was never set (because a touch event was never fired) allow the event
		if (!this.targetElement) {
			return true;
		}

		if (event.forwardedTouchEvent || window._zeptoCompatibleTrigger) {
		    window._zeptoCompatibleTrigger = false;
			return true;
		}

		// Programmatically generated events targeting a specific element should be permitted
		if (!event.cancelable) {
			return true;
		}

		// Derive and check the target element to see whether the mouse event needs to be permitted;
		// unless explicitly enabled, prevent non-touch click events from triggering actions,
		// to prevent ghost/doubleclicks.
		if (!this.needsClick(this.targetElement) || this.cancelNextClick) {

			// Prevent any user-added listeners declared on FastClick element from being fired.
			if (event.stopImmediatePropagation) {
				event.stopImmediatePropagation();
			} else {

				// Part of the hack for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
				event.propagationStopped = true;
			}

			// Cancel the event
			event.stopPropagation();
			event.preventDefault();

			return false;
		}

		// If the mouse event is permitted, return true for the action to go through.
		return true;
	};


	/**
	 * On actual clicks, determine whether this is a touch-generated click, a click action occurring
	 * naturally after a delay after a touch (which needs to be cancelled to avoid duplication), or
	 * an actual click which should be permitted.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onClick = function(event) {
		var permitted;

		// It's possible for another FastClick-like library delivered with third-party code to fire a click event before FastClick does (issue #44). In that case, set the click-tracking flag back to false and return early. This will cause onTouchEnd to return early.
		if (this.trackingClick) {
			this.targetElement = null;
			this.trackingClick = false;
			return true;
		}

		// Very odd behaviour on iOS (issue #18): if a submit element is present inside a form and the user hits enter in the iOS simulator or clicks the Go button on the pop-up OS keyboard the a kind of 'fake' click event will be triggered with the submit-type input element as the target.
		if (event.target.type === 'submit' && event.detail === 0) {
			return true;
		}

		permitted = this.onMouse(event);

		// Only unset targetElement if the click is not permitted. This will ensure that the check for !targetElement in onMouse fails and the browser's click doesn't go through.
		if (!permitted) {
			this.targetElement = null;
		}

		// If clicks are permitted, return true for the action to go through.
		return permitted;
	};


	/**
	 * Remove all FastClick's event listeners.
	 *
	 * @returns {void}
	 */
	FastClick.prototype.destroy = function() {
		var layer = this.layer;

		if (deviceIsAndroid) {
			layer.removeEventListener('mouseover', this.onMouse, true);
			layer.removeEventListener('mousedown', this.onMouse, true);
			layer.removeEventListener('mouseup', this.onMouse, true);
		}

		layer.removeEventListener('click', this.onClick, true);
		layer.removeEventListener('touchstart', this.onTouchStart, false);
		layer.removeEventListener('touchmove', this.onTouchMove, false);
		layer.removeEventListener('touchend', this.onTouchEnd, false);
		layer.removeEventListener('touchcancel', this.onTouchCancel, false);
	};


	/**
	 * Check whether FastClick is needed.
	 *
	 * @param {Element} layer The layer to listen on
	 */
	FastClick.notNeeded = function(layer) {
		var metaViewport;
		var chromeVersion;
		var blackberryVersion;
		var firefoxVersion;

		// Devices that don't support touch don't need FastClick
		if (typeof window.ontouchstart === 'undefined') {
			return true;
		}

		// Chrome version - zero for other browsers
		chromeVersion = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

		if (chromeVersion) {

			if (deviceIsAndroid) {
				metaViewport = document.querySelector('meta[name=viewport]');

				if (metaViewport) {
					// Chrome on Android with user-scalable="no" doesn't need FastClick (issue #89)
					if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
						return true;
					}
					// Chrome 32 and above with width=device-width or less don't need FastClick
					if (chromeVersion > 31 && document.documentElement.scrollWidth <= window.outerWidth) {
						return true;
					}
				}

			// Chrome desktop doesn't need FastClick (issue #15)
			} else {
				return true;
			}
		}

		if (deviceIsBlackBerry10) {
			blackberryVersion = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);

			// BlackBerry 10.3+ does not require Fastclick library.
			// https://github.com/ftlabs/fastclick/issues/251
			if (blackberryVersion[1] >= 10 && blackberryVersion[2] >= 3) {
				metaViewport = document.querySelector('meta[name=viewport]');

				if (metaViewport) {
					// user-scalable=no eliminates click delay.
					if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
						return true;
					}
					// width=device-width (or less than device-width) eliminates click delay.
					if (document.documentElement.scrollWidth <= window.outerWidth) {
						return true;
					}
				}
			}
		}

		// IE10 with -ms-touch-action: none or manipulation, which disables double-tap-to-zoom (issue #97)
		if (layer.style.msTouchAction === 'none' || layer.style.touchAction === 'manipulation') {
			return true;
		}

		// Firefox version - zero for other browsers
		firefoxVersion = +(/Firefox\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

		if (firefoxVersion >= 27) {
			// Firefox 27+ does not have tap delay if the content is not zoomable - https://bugzilla.mozilla.org/show_bug.cgi?id=922896

			metaViewport = document.querySelector('meta[name=viewport]');
			if (metaViewport && (metaViewport.content.indexOf('user-scalable=no') !== -1 || document.documentElement.scrollWidth <= window.outerWidth)) {
				return true;
			}
		}

		// IE11: prefixed -ms-touch-action is no longer supported and it's recomended to use non-prefixed version
		// http://msdn.microsoft.com/en-us/library/windows/apps/Hh767313.aspx
		if (layer.style.touchAction === 'none' || layer.style.touchAction === 'manipulation') {
			return true;
		}

		return false;
	};


	/**
	 * Factory method for creating a FastClick object
	 *
	 * @param {Element} layer The layer to listen on
	 * @param {Object} [options={}] The options to override the defaults
	 */
	FastClick.attach = function(layer, options) {
		return new FastClick(layer, options);
	};


	if (typeof define === 'function' && typeof define.amd === 'object' && define.amd) {

		// AMD. Register as an anonymous module.
		define(function() {
			return FastClick;
		});
	} else if (typeof module !== 'undefined' && module.exports) {
		module.exports = FastClick.attach;
		module.exports.FastClick = FastClick;
	} else {
		window.FastClick = FastClick;
	}
}());
;
/* Zepto 1.1.6 - zepto event ajax form ie detect fx - zeptojs.com/license */
var Zepto=function(){function L(t){return null==t?String(t):T[j.call(t)]||"object"}function M(t){return"function"==L(t)}function Z(t){return null!=t&&t==t.window}function $(t){return null!=t&&t.nodeType==t.DOCUMENT_NODE}function _(t){return"object"==L(t)}function D(t){return _(t)&&!Z(t)&&Object.getPrototypeOf(t)==Object.prototype}function F(t){return"number"==typeof t.length}function R(t){return s.call(t,function(t){return null!=t})}function z(t){return t.length>0?n.fn.concat.apply([],t):t}function B(t){return t.replace(/::/g,"/").replace(/([A-Z]+)([A-Z][a-z])/g,"$1_$2").replace(/([a-z\d])([A-Z])/g,"$1_$2").replace(/_/g,"-").toLowerCase()}function q(t){return t in c?c[t]:c[t]=new RegExp("(^|\\s)"+t+"(\\s|$)")}function I(t,e){return"number"!=typeof e||l[B(t)]?e:e+"px"}function V(t){var e,n;return f[t]||(e=u.createElement(t),u.body.appendChild(e),n=getComputedStyle(e,"").getPropertyValue("display"),e.parentNode.removeChild(e),"none"==n&&(n="block"),f[t]=n),f[t]}function H(t){return"children"in t?a.call(t.children):n.map(t.childNodes,function(t){return 1==t.nodeType?t:void 0})}function W(t,e){var n,i=t?t.length:0;for(n=0;i>n;n++)this[n]=t[n];this.length=i,this.selector=e||""}function X(n,i,r){for(e in i)r&&(D(i[e])||k(i[e]))?(D(i[e])&&!D(n[e])&&(n[e]={}),k(i[e])&&!k(n[e])&&(n[e]=[]),X(n[e],i[e],r)):i[e]!==t&&(n[e]=i[e])}function U(t,e){return null==e?n(t):n(t).filter(e)}function J(t,e,n,i){return M(e)?e.call(t,n,i):e}function Y(t,e,n){null==n?t.removeAttribute(e):t.setAttribute(e,n)}function K(e,n){var i=e.className||"",r=i&&i.baseVal!==t;return n===t?r?i.baseVal:i:void(r?i.baseVal=n:e.className=n)}function G(t){try{return t?"true"==t||("false"==t?!1:"null"==t?null:+t+""==t?+t:/^[\[\{]/.test(t)?n.parseJSON(t):t):t}catch(e){return t}}function Q(t,e){e(t);for(var n=0,i=t.childNodes.length;i>n;n++)Q(t.childNodes[n],e)}var t,e,n,i,P,O,r=[],o=r.concat,s=r.filter,a=r.slice,u=window.document,f={},c={},l={"column-count":1,columns:1,"font-weight":1,"line-height":1,opacity:1,"z-index":1,zoom:1},h=/^\s*<(\w+|!)[^>]*>/,p=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,d=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,m=/^(?:body|html)$/i,g=/([A-Z])/g,v=["val","css","html","text","data","width","height","offset"],y=["after","prepend","before","append"],b=u.createElement("table"),x=u.createElement("tr"),w={tr:u.createElement("tbody"),tbody:b,thead:b,tfoot:b,td:x,th:x,"*":u.createElement("div")},E=/complete|loaded|interactive/,S=/^[\w-]*$/,T={},j=T.toString,C={},N=u.createElement("div"),A={tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},k=Array.isArray||function(t){return t instanceof Array};return C.matches=function(t,e){if(!e||!t||1!==t.nodeType)return!1;var n=t.webkitMatchesSelector||t.mozMatchesSelector||t.oMatchesSelector||t.matchesSelector;if(n)return n.call(t,e);var i,r=t.parentNode,o=!r;return o&&(r=N).appendChild(t),i=~C.qsa(r,e).indexOf(t),o&&N.removeChild(t),i},P=function(t){return t.replace(/-+(.)?/g,function(t,e){return e?e.toUpperCase():""})},O=function(t){return s.call(t,function(e,n){return t.indexOf(e)==n})},C.fragment=function(e,i,r){var o,s,f;return p.test(e)&&(o=n(u.createElement(RegExp.$1))),o||(e.replace&&(e=e.replace(d,"<$1></$2>")),i===t&&(i=h.test(e)&&RegExp.$1),i in w||(i="*"),f=w[i],f.innerHTML=""+e,o=n.each(a.call(f.childNodes),function(){f.removeChild(this)})),D(r)&&(s=n(o),n.each(r,function(t,e){v.indexOf(t)>-1?s[t](e):s.attr(t,e)})),o},C.Z=function(t,e){return new W(t,e)},C.isZ=function(t){return t instanceof C.Z},C.init=function(e,i){var r;if(!e)return C.Z();if("string"==typeof e)if(e=e.trim(),"<"==e[0]&&h.test(e))r=C.fragment(e,RegExp.$1,i),e=null;else{if(i!==t)return n(i).find(e);r=C.qsa(u,e)}else{if(M(e))return n(u).ready(e);if(C.isZ(e))return e;if(k(e))r=R(e);else if(_(e))r=[e],e=null;else if(h.test(e))r=C.fragment(e.trim(),RegExp.$1,i),e=null;else{if(i!==t)return n(i).find(e);r=C.qsa(u,e)}}return C.Z(r,e)},n=function(t,e){return C.init(t,e)},n.extend=function(t){var e,n=a.call(arguments,1);return"boolean"==typeof t&&(e=t,t=n.shift()),n.forEach(function(n){X(t,n,e)}),t},C.qsa=function(t,e){var n,i="#"==e[0],r=!i&&"."==e[0],o=i||r?e.slice(1):e,s=S.test(o);return t.getElementById&&s&&i?(n=t.getElementById(o))?[n]:[]:1!==t.nodeType&&9!==t.nodeType&&11!==t.nodeType?[]:a.call(s&&!i&&t.getElementsByClassName?r?t.getElementsByClassName(o):t.getElementsByTagName(e):t.querySelectorAll(e))},n.contains=u.documentElement.contains?function(t,e){return t!==e&&t.contains(e)}:function(t,e){for(;e&&(e=e.parentNode);)if(e===t)return!0;return!1},n.type=L,n.isFunction=M,n.isWindow=Z,n.isArray=k,n.isPlainObject=D,n.isEmptyObject=function(t){var e;for(e in t)return!1;return!0},n.inArray=function(t,e,n){return r.indexOf.call(e,t,n)},n.camelCase=P,n.trim=function(t){return null==t?"":String.prototype.trim.call(t)},n.uuid=0,n.support={},n.expr={},n.noop=function(){},n.map=function(t,e){var n,r,o,i=[];if(F(t))for(r=0;r<t.length;r++)n=e(t[r],r),null!=n&&i.push(n);else for(o in t)n=e(t[o],o),null!=n&&i.push(n);return z(i)},n.each=function(t,e){var n,i;if(F(t)){for(n=0;n<t.length;n++)if(e.call(t[n],n,t[n])===!1)return t}else for(i in t)if(e.call(t[i],i,t[i])===!1)return t;return t},n.grep=function(t,e){return s.call(t,e)},window.JSON&&(n.parseJSON=JSON.parse),n.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(t,e){T["[object "+e+"]"]=e.toLowerCase()}),n.fn={constructor:C.Z,length:0,forEach:r.forEach,reduce:r.reduce,push:r.push,sort:r.sort,splice:r.splice,indexOf:r.indexOf,concat:function(){var t,e,n=[];for(t=0;t<arguments.length;t++)e=arguments[t],n[t]=C.isZ(e)?e.toArray():e;return o.apply(C.isZ(this)?this.toArray():this,n)},map:function(t){return n(n.map(this,function(e,n){return t.call(e,n,e)}))},slice:function(){return n(a.apply(this,arguments))},ready:function(t){return E.test(u.readyState)&&u.body?t(n):u.addEventListener("DOMContentLoaded",function(){t(n)},!1),this},get:function(e){return e===t?a.call(this):this[e>=0?e:e+this.length]},toArray:function(){return this.get()},size:function(){return this.length},remove:function(){return this.each(function(){null!=this.parentNode&&this.parentNode.removeChild(this)})},each:function(t){return r.every.call(this,function(e,n){return t.call(e,n,e)!==!1}),this},filter:function(t){return M(t)?this.not(this.not(t)):n(s.call(this,function(e){return C.matches(e,t)}))},add:function(t,e){return n(O(this.concat(n(t,e))))},is:function(t){return this.length>0&&C.matches(this[0],t)},not:function(e){var i=[];if(M(e)&&e.call!==t)this.each(function(t){e.call(this,t)||i.push(this)});else{var r="string"==typeof e?this.filter(e):F(e)&&M(e.item)?a.call(e):n(e);this.forEach(function(t){r.indexOf(t)<0&&i.push(t)})}return n(i)},has:function(t){return this.filter(function(){return _(t)?n.contains(this,t):n(this).find(t).size()})},eq:function(t){return-1===t?this.slice(t):this.slice(t,+t+1)},first:function(){var t=this[0];return t&&!_(t)?t:n(t)},last:function(){var t=this[this.length-1];return t&&!_(t)?t:n(t)},find:function(t){var e,i=this;return e=t?"object"==typeof t?n(t).filter(function(){var t=this;return r.some.call(i,function(e){return n.contains(e,t)})}):1==this.length?n(C.qsa(this[0],t)):this.map(function(){return C.qsa(this,t)}):n()},closest:function(t,e){var i=this[0],r=!1;for("object"==typeof t&&(r=n(t));i&&!(r?r.indexOf(i)>=0:C.matches(i,t));)i=i!==e&&!$(i)&&i.parentNode;return n(i)},parents:function(t){for(var e=[],i=this;i.length>0;)i=n.map(i,function(t){return(t=t.parentNode)&&!$(t)&&e.indexOf(t)<0?(e.push(t),t):void 0});return U(e,t)},parent:function(t){return U(O(this.pluck("parentNode")),t)},children:function(t){return U(this.map(function(){return H(this)}),t)},contents:function(){return this.map(function(){return this.contentDocument||a.call(this.childNodes)})},siblings:function(t){return U(this.map(function(t,e){return s.call(H(e.parentNode),function(t){return t!==e})}),t)},empty:function(){return this.each(function(){this.innerHTML=""})},pluck:function(t){return n.map(this,function(e){return e[t]})},show:function(){return this.each(function(){"none"==this.style.display&&(this.style.display=""),"none"==getComputedStyle(this,"").getPropertyValue("display")&&(this.style.display=V(this.nodeName))})},replaceWith:function(t){return this.before(t).remove()},wrap:function(t){var e=M(t);if(this[0]&&!e)var i=n(t).get(0),r=i.parentNode||this.length>1;return this.each(function(o){n(this).wrapAll(e?t.call(this,o):r?i.cloneNode(!0):i)})},wrapAll:function(t){if(this[0]){n(this[0]).before(t=n(t));for(var e;(e=t.children()).length;)t=e.first();n(t).append(this)}return this},wrapInner:function(t){var e=M(t);return this.each(function(i){var r=n(this),o=r.contents(),s=e?t.call(this,i):t;o.length?o.wrapAll(s):r.append(s)})},unwrap:function(){return this.parent().each(function(){n(this).replaceWith(n(this).children())}),this},clone:function(){return this.map(function(){return this.cloneNode(!0)})},hide:function(){return this.css("display","none")},toggle:function(e){return this.each(function(){var i=n(this);(e===t?"none"==i.css("display"):e)?i.show():i.hide()})},prev:function(t){return n(this.pluck("previousElementSibling")).filter(t||"*")},next:function(t){return n(this.pluck("nextElementSibling")).filter(t||"*")},html:function(t){return 0 in arguments?this.each(function(e){var i=this.innerHTML;n(this).empty().append(J(this,t,e,i))}):0 in this?this[0].innerHTML:null},text:function(t){return 0 in arguments?this.each(function(e){var n=J(this,t,e,this.textContent);this.textContent=null==n?"":""+n}):0 in this?this[0].textContent:null},attr:function(n,i){var r;return"string"!=typeof n||1 in arguments?this.each(function(t){if(1===this.nodeType)if(_(n))for(e in n)Y(this,e,n[e]);else Y(this,n,J(this,i,t,this.getAttribute(n)))}):this.length&&1===this[0].nodeType?!(r=this[0].getAttribute(n))&&n in this[0]?this[0][n]:r:t},removeAttr:function(t){return this.each(function(){1===this.nodeType&&t.split(" ").forEach(function(t){Y(this,t)},this)})},prop:function(t,e){return t=A[t]||t,1 in arguments?this.each(function(n){this[t]=J(this,e,n,this[t])}):this[0]&&this[0][t]},data:function(e,n){var i="data-"+e.replace(g,"-$1").toLowerCase(),r=1 in arguments?this.attr(i,n):this.attr(i);return null!==r?G(r):t},val:function(t){return 0 in arguments?this.each(function(e){this.value=J(this,t,e,this.value)}):this[0]&&(this[0].multiple?n(this[0]).find("option").filter(function(){return this.selected}).pluck("value"):this[0].value)},offset:function(t){if(t)return this.each(function(e){var i=n(this),r=J(this,t,e,i.offset()),o=i.offsetParent().offset(),s={top:r.top-o.top,left:r.left-o.left};"static"==i.css("position")&&(s.position="relative"),i.css(s)});if(!this.length)return null;if(!n.contains(u.documentElement,this[0]))return{top:0,left:0};var e=this[0].getBoundingClientRect();return{left:e.left+window.pageXOffset,top:e.top+window.pageYOffset,width:Math.round(e.width),height:Math.round(e.height)}},css:function(t,i){if(arguments.length<2){var r,o=this[0];if(!o)return;if(r=getComputedStyle(o,""),"string"==typeof t)return o.style[P(t)]||r.getPropertyValue(t);if(k(t)){var s={};return n.each(t,function(t,e){s[e]=o.style[P(e)]||r.getPropertyValue(e)}),s}}var a="";if("string"==L(t))i||0===i?a=B(t)+":"+I(t,i):this.each(function(){this.style.removeProperty(B(t))});else for(e in t)t[e]||0===t[e]?a+=B(e)+":"+I(e,t[e])+";":this.each(function(){this.style.removeProperty(B(e))});return this.each(function(){this.style.cssText+=";"+a})},index:function(t){return t?this.indexOf(n(t)[0]):this.parent().children().indexOf(this[0])},hasClass:function(t){return t?r.some.call(this,function(t){return this.test(K(t))},q(t)):!1},addClass:function(t){return t?this.each(function(e){if("className"in this){i=[];var r=K(this),o=J(this,t,e,r);o.split(/\s+/g).forEach(function(t){n(this).hasClass(t)||i.push(t)},this),i.length&&K(this,r+(r?" ":"")+i.join(" "))}}):this},removeClass:function(e){return this.each(function(n){if("className"in this){if(e===t)return K(this,"");i=K(this),J(this,e,n,i).split(/\s+/g).forEach(function(t){i=i.replace(q(t)," ")}),K(this,i.trim())}})},toggleClass:function(e,i){return e?this.each(function(r){var o=n(this),s=J(this,e,r,K(this));s.split(/\s+/g).forEach(function(e){(i===t?!o.hasClass(e):i)?o.addClass(e):o.removeClass(e)})}):this},scrollTop:function(e){if(this.length){var n="scrollTop"in this[0];return e===t?n?this[0].scrollTop:this[0].pageYOffset:this.each(n?function(){this.scrollTop=e}:function(){this.scrollTo(this.scrollX,e)})}},scrollLeft:function(e){if(this.length){var n="scrollLeft"in this[0];return e===t?n?this[0].scrollLeft:this[0].pageXOffset:this.each(n?function(){this.scrollLeft=e}:function(){this.scrollTo(e,this.scrollY)})}},position:function(){if(this.length){var t=this[0],e=this.offsetParent(),i=this.offset(),r=m.test(e[0].nodeName)?{top:0,left:0}:e.offset();return i.top-=parseFloat(n(t).css("margin-top"))||0,i.left-=parseFloat(n(t).css("margin-left"))||0,r.top+=parseFloat(n(e[0]).css("border-top-width"))||0,r.left+=parseFloat(n(e[0]).css("border-left-width"))||0,{top:i.top-r.top,left:i.left-r.left}}},offsetParent:function(){return this.map(function(){for(var t=this.offsetParent||u.body;t&&!m.test(t.nodeName)&&"static"==n(t).css("position");)t=t.offsetParent;return t})}},n.fn.detach=n.fn.remove,["width","height"].forEach(function(e){var i=e.replace(/./,function(t){return t[0].toUpperCase()});n.fn[e]=function(r){var o,s=this[0];return r===t?Z(s)?s["inner"+i]:$(s)?s.documentElement["scroll"+i]:(o=this.offset())&&o[e]:this.each(function(t){s=n(this),s.css(e,J(this,r,t,s[e]()))})}}),y.forEach(function(t,e){var i=e%2;n.fn[t]=function(){var t,o,r=n.map(arguments,function(e){return t=L(e),"object"==t||"array"==t||null==e?e:C.fragment(e)}),s=this.length>1;return r.length<1?this:this.each(function(t,a){o=i?a:a.parentNode,a=0==e?a.nextSibling:1==e?a.firstChild:2==e?a:null;var f=n.contains(u.documentElement,o);r.forEach(function(t){if(s)t=t.cloneNode(!0);else if(!o)return n(t).remove();o.insertBefore(t,a),f&&Q(t,function(t){null==t.nodeName||"SCRIPT"!==t.nodeName.toUpperCase()||t.type&&"text/javascript"!==t.type||t.src||window.eval.call(window,t.innerHTML)})})})},n.fn[i?t+"To":"insert"+(e?"Before":"After")]=function(e){return n(e)[t](this),this}}),C.Z.prototype=W.prototype=n.fn,C.uniq=O,C.deserializeValue=G,n.zepto=C,n}();window.Zepto=Zepto,void 0===window.$&&(window.$=Zepto),function(t){function l(t){return t._zid||(t._zid=e++)}function h(t,e,n,i){if(e=p(e),e.ns)var r=d(e.ns);return(s[l(t)]||[]).filter(function(t){return!(!t||e.e&&t.e!=e.e||e.ns&&!r.test(t.ns)||n&&l(t.fn)!==l(n)||i&&t.sel!=i)})}function p(t){var e=(""+t).split(".");return{e:e[0],ns:e.slice(1).sort().join(" ")}}function d(t){return new RegExp("(?:^| )"+t.replace(" "," .* ?")+"(?: |$)")}function m(t,e){return t.del&&!u&&t.e in f||!!e}function g(t){return c[t]||u&&f[t]||t}function v(e,i,r,o,a,u,f){var h=l(e),d=s[h]||(s[h]=[]);i.split(/\s/).forEach(function(i){if("ready"==i)return t(document).ready(r);var s=p(i);s.fn=r,s.sel=a,s.e in c&&(r=function(e){var n=e.relatedTarget;return!n||n!==this&&!t.contains(this,n)?s.fn.apply(this,arguments):void 0}),s.del=u;var l=u||r;s.proxy=function(t){if(t=S(t),!t.isImmediatePropagationStopped()){t.data=o;var i=l.apply(e,t._args==n?[t]:[t].concat(t._args));return i===!1&&(t.preventDefault(),t.stopPropagation()),i}},s.i=d.length,d.push(s),"addEventListener"in e&&e.addEventListener(g(s.e),s.proxy,m(s,f))})}function y(t,e,n,i,r){var o=l(t);(e||"").split(/\s/).forEach(function(e){h(t,e,n,i).forEach(function(e){delete s[o][e.i],"removeEventListener"in t&&t.removeEventListener(g(e.e),e.proxy,m(e,r))})})}function S(e,i){return(i||!e.isDefaultPrevented)&&(i||(i=e),t.each(E,function(t,n){var r=i[t];e[t]=function(){return this[n]=b,r&&r.apply(i,arguments)},e[n]=x}),(i.defaultPrevented!==n?i.defaultPrevented:"returnValue"in i?i.returnValue===!1:i.getPreventDefault&&i.getPreventDefault())&&(e.isDefaultPrevented=b)),e}function T(t){var e,i={originalEvent:t};for(e in t)w.test(e)||t[e]===n||(i[e]=t[e]);return S(i,t)}var n,e=1,i=Array.prototype.slice,r=t.isFunction,o=function(t){return"string"==typeof t},s={},a={},u="onfocusin"in window,f={focus:"focusin",blur:"focusout"},c={mouseenter:"mouseover",mouseleave:"mouseout"};a.click=a.mousedown=a.mouseup=a.mousemove="MouseEvents",t.event={add:v,remove:y},t.proxy=function(e,n){var s=2 in arguments&&i.call(arguments,2);if(r(e)){var a=function(){return e.apply(n,s?s.concat(i.call(arguments)):arguments)};return a._zid=l(e),a}if(o(n))return s?(s.unshift(e[n],e),t.proxy.apply(null,s)):t.proxy(e[n],e);throw new TypeError("expected function")},t.fn.bind=function(t,e,n){return this.on(t,e,n)},t.fn.unbind=function(t,e){return this.off(t,e)},t.fn.one=function(t,e,n,i){return this.on(t,e,n,i,1)};var b=function(){return!0},x=function(){return!1},w=/^([A-Z]|returnValue$|layer[XY]$)/,E={preventDefault:"isDefaultPrevented",stopImmediatePropagation:"isImmediatePropagationStopped",stopPropagation:"isPropagationStopped"};t.fn.delegate=function(t,e,n){return this.on(e,t,n)},t.fn.undelegate=function(t,e,n){return this.off(e,t,n)},t.fn.live=function(e,n){return t(document.body).delegate(this.selector,e,n),this},t.fn.die=function(e,n){return t(document.body).undelegate(this.selector,e,n),this},t.fn.on=function(e,s,a,u,f){var c,l,h=this;return e&&!o(e)?(t.each(e,function(t,e){h.on(t,s,a,e,f)}),h):(o(s)||r(u)||u===!1||(u=a,a=s,s=n),(u===n||a===!1)&&(u=a,a=n),u===!1&&(u=x),h.each(function(n,r){f&&(c=function(t){return y(r,t.type,u),u.apply(this,arguments)}),s&&(l=function(e){var n,o=t(e.target).closest(s,r).get(0);return o&&o!==r?(n=t.extend(T(e),{currentTarget:o,liveFired:r}),(c||u).apply(o,[n].concat(i.call(arguments,1)))):void 0}),v(r,e,u,a,s,l||c)}))},t.fn.off=function(e,i,s){var a=this;return e&&!o(e)?(t.each(e,function(t,e){a.off(t,i,e)}),a):(o(i)||r(s)||s===!1||(s=i,i=n),s===!1&&(s=x),a.each(function(){y(this,e,s,i)}))},t.fn.trigger=function(e,n){return e=o(e)||t.isPlainObject(e)?t.Event(e):S(e),e._args=n,this.each(function(){e.type in f&&"function"==typeof this[e.type]?this[e.type]():"dispatchEvent"in this?this.dispatchEvent(e):t(this).triggerHandler(e,n)})},t.fn.triggerHandler=function(e,n){var i,r;return this.each(function(s,a){i=T(o(e)?t.Event(e):e),i._args=n,i.target=a,t.each(h(a,e.type||e),function(t,e){return r=e.proxy(i),i.isImmediatePropagationStopped()?!1:void 0})}),r},"focusin focusout focus blur load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select keydown keypress keyup error".split(" ").forEach(function(e){t.fn[e]=function(t){return 0 in arguments?this.bind(e,t):this.trigger(e)}}),t.Event=function(t,e){o(t)||(e=t,t=e.type);var n=document.createEvent(a[t]||"Events"),i=!0;if(e)for(var r in e)"bubbles"==r?i=!!e[r]:n[r]=e[r];return n.initEvent(t,i,!0),S(n)}}(Zepto),function(t){function h(e,n,i){var r=t.Event(n);return t(e).trigger(r,i),!r.isDefaultPrevented()}function p(t,e,i,r){return t.global?h(e||n,i,r):void 0}function d(e){e.global&&0===t.active++&&p(e,null,"ajaxStart")}function m(e){e.global&&!--t.active&&p(e,null,"ajaxStop")}function g(t,e){var n=e.context;return e.beforeSend.call(n,t,e)===!1||p(e,n,"ajaxBeforeSend",[t,e])===!1?!1:void p(e,n,"ajaxSend",[t,e])}function v(t,e,n,i){var r=n.context,o="success";n.success.call(r,t,o,e),i&&i.resolveWith(r,[t,o,e]),p(n,r,"ajaxSuccess",[e,n,t]),b(o,e,n)}function y(t,e,n,i,r){var o=i.context;i.error.call(o,n,e,t),r&&r.rejectWith(o,[n,e,t]),p(i,o,"ajaxError",[n,i,t||e]),b(e,n,i)}function b(t,e,n){var i=n.context;n.complete.call(i,e,t),p(n,i,"ajaxComplete",[e,n]),m(n)}function x(){}function w(t){return t&&(t=t.split(";",2)[0]),t&&(t==f?"html":t==u?"json":s.test(t)?"script":a.test(t)&&"xml")||"text"}function E(t,e){return""==e?t:(t+"&"+e).replace(/[&?]{1,2}/,"?")}function S(e){e.processData&&e.data&&"string"!=t.type(e.data)&&(e.data=t.param(e.data,e.traditional)),!e.data||e.type&&"GET"!=e.type.toUpperCase()||(e.url=E(e.url,e.data),e.data=void 0)}function T(e,n,i,r){return t.isFunction(n)&&(r=i,i=n,n=void 0),t.isFunction(i)||(r=i,i=void 0),{url:e,data:n,success:i,dataType:r}}function C(e,n,i,r){var o,s=t.isArray(n),a=t.isPlainObject(n);t.each(n,function(n,u){o=t.type(u),r&&(n=i?r:r+"["+(a||"object"==o||"array"==o?n:"")+"]"),!r&&s?e.add(u.name,u.value):"array"==o||!i&&"object"==o?C(e,u,i,n):e.add(n,u)})}var i,r,e=0,n=window.document,o=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,s=/^(?:text|application)\/javascript/i,a=/^(?:text|application)\/xml/i,u="application/json",f="text/html",c=/^\s*$/,l=n.createElement("a");l.href=window.location.href,t.active=0,t.ajaxJSONP=function(i,r){if(!("type"in i))return t.ajax(i);var f,h,o=i.jsonpCallback,s=(t.isFunction(o)?o():o)||"jsonp"+ ++e,a=n.createElement("script"),u=window[s],c=function(e){t(a).triggerHandler("error",e||"abort")},l={abort:c};return r&&r.promise(l),t(a).on("load error",function(e,n){clearTimeout(h),t(a).off().remove(),"error"!=e.type&&f?v(f[0],l,i,r):y(null,n||"error",l,i,r),window[s]=u,f&&t.isFunction(u)&&u(f[0]),u=f=void 0}),g(l,i)===!1?(c("abort"),l):(window[s]=function(){f=arguments},a.src=i.url.replace(/\?(.+)=\?/,"?$1="+s),n.head.appendChild(a),i.timeout>0&&(h=setTimeout(function(){c("timeout")},i.timeout)),l)},t.ajaxSettings={type:"GET",beforeSend:x,success:x,error:x,complete:x,context:null,global:!0,xhr:function(){return new window.XMLHttpRequest},accepts:{script:"text/javascript, application/javascript, application/x-javascript",json:u,xml:"application/xml, text/xml",html:f,text:"text/plain"},crossDomain:!1,timeout:0,processData:!0,cache:!0},t.ajax=function(e){var a,u,o=t.extend({},e||{}),s=t.Deferred&&t.Deferred();for(i in t.ajaxSettings)void 0===o[i]&&(o[i]=t.ajaxSettings[i]);d(o),o.crossDomain||(a=n.createElement("a"),a.href=o.url,a.href=a.href,o.crossDomain=l.protocol+"//"+l.host!=a.protocol+"//"+a.host),o.url||(o.url=window.location.toString()),(u=o.url.indexOf("#"))>-1&&(o.url=o.url.slice(0,u)),S(o);var f=o.dataType,h=/\?.+=\?/.test(o.url);if(h&&(f="jsonp"),o.cache!==!1&&(e&&e.cache===!0||"script"!=f&&"jsonp"!=f)||(o.url=E(o.url,"_="+Date.now())),"jsonp"==f)return h||(o.url=E(o.url,o.jsonp?o.jsonp+"=?":o.jsonp===!1?"":"callback=?")),t.ajaxJSONP(o,s);var P,p=o.accepts[f],m={},b=function(t,e){m[t.toLowerCase()]=[t,e]},T=/^([\w-]+:)\/\//.test(o.url)?RegExp.$1:window.location.protocol,j=o.xhr(),C=j.setRequestHeader;if(s&&s.promise(j),o.crossDomain||b("X-Requested-With","XMLHttpRequest"),b("Accept",p||"*/*"),(p=o.mimeType||p)&&(p.indexOf(",")>-1&&(p=p.split(",",2)[0]),j.overrideMimeType&&j.overrideMimeType(p)),(o.contentType||o.contentType!==!1&&o.data&&"GET"!=o.type.toUpperCase())&&b("Content-Type",o.contentType||"application/x-www-form-urlencoded"),o.headers)for(r in o.headers)b(r,o.headers[r]);if(j.setRequestHeader=b,j.onreadystatechange=function(){if(4==j.readyState){j.onreadystatechange=x,clearTimeout(P);var e,n=!1;if(j.status>=200&&j.status<300||304==j.status||0==j.status&&"file:"==T){f=f||w(o.mimeType||j.getResponseHeader("content-type")),e=j.responseText;try{"script"==f?(1,eval)(e):"xml"==f?e=j.responseXML:"json"==f&&(e=c.test(e)?null:t.parseJSON(e))}catch(i){n=i}n?y(n,"parsererror",j,o,s):v(e,j,o,s)}else y(j.statusText||null,j.status?"error":"abort",j,o,s)}},g(j,o)===!1)return j.abort(),y(null,"abort",j,o,s),j;if(o.xhrFields)for(r in o.xhrFields)j[r]=o.xhrFields[r];var O="async"in o?o.async:!0;j.open(o.type,o.url,O,o.username,o.password);for(r in m)C.apply(j,m[r]);return o.timeout>0&&(P=setTimeout(function(){j.onreadystatechange=x,j.abort(),y(null,"timeout",j,o,s)},o.timeout)),j.send(o.data?o.data:null),j},t.get=function(){return t.ajax(T.apply(null,arguments))},t.post=function(){var e=T.apply(null,arguments);return e.type="POST",t.ajax(e)},t.getJSON=function(){var e=T.apply(null,arguments);return e.dataType="json",t.ajax(e)},t.fn.load=function(e,n,i){if(!this.length)return this;var a,r=this,s=e.split(/\s/),u=T(e,n,i),f=u.success;return s.length>1&&(u.url=s[0],a=s[1]),u.success=function(e){r.html(a?t("<div>").html(e.replace(o,"")).find(a):e),f&&f.apply(r,arguments)},t.ajax(u),this};var j=encodeURIComponent;t.param=function(e,n){var i=[];return i.add=function(e,n){t.isFunction(n)&&(n=n()),null==n&&(n=""),this.push(j(e)+"="+j(n))},C(i,e,n),i.join("&").replace(/%20/g,"+")}}(Zepto),function(t){t.fn.serializeArray=function(){var e,n,i=[],r=function(t){return t.forEach?t.forEach(r):void i.push({name:e,value:t})};return this[0]&&t.each(this[0].elements,function(i,o){n=o.type,e=o.name,e&&"fieldset"!=o.nodeName.toLowerCase()&&!o.disabled&&"submit"!=n&&"reset"!=n&&"button"!=n&&"file"!=n&&("radio"!=n&&"checkbox"!=n||o.checked)&&r(t(o).val())}),i},t.fn.serialize=function(){var t=[];return this.serializeArray().forEach(function(e){t.push(encodeURIComponent(e.name)+"="+encodeURIComponent(e.value))}),t.join("&")},t.fn.submit=function(e){if(0 in arguments)this.bind("submit",e);else if(this.length){var n=t.Event("submit");this.eq(0).trigger(n),n.isDefaultPrevented()||this.get(0).submit()}return this}}(Zepto),function(){try{getComputedStyle(void 0)}catch(t){var e=getComputedStyle;window.getComputedStyle=function(t){try{return e(t)}catch(n){return null}}}}(),function(t){function e(t,e){var n=this.os={},i=this.browser={},r=t.match(/Web[kK]it[\/]{0,1}([\d.]+)/),o=t.match(/(Android);?[\s\/]+([\d.]+)?/),s=!!t.match(/\(Macintosh\; Intel /),a=t.match(/(iPad).*OS\s([\d_]+)/),u=t.match(/(iPod)(.*OS\s([\d_]+))?/),f=!a&&t.match(/(iPhone\sOS)\s([\d_]+)/),c=t.match(/(webOS|hpwOS)[\s\/]([\d.]+)/),l=/Win\d{2}|Windows/.test(e),h=t.match(/Windows Phone ([\d.]+)/),p=c&&t.match(/TouchPad/),d=t.match(/Kindle\/([\d.]+)/),m=t.match(/Silk\/([\d._]+)/),g=t.match(/(BlackBerry).*Version\/([\d.]+)/),v=t.match(/(BB10).*Version\/([\d.]+)/),y=t.match(/(RIM\sTablet\sOS)\s([\d.]+)/),b=t.match(/PlayBook/),x=t.match(/Chrome\/([\d.]+)/)||t.match(/CriOS\/([\d.]+)/),w=t.match(/Firefox\/([\d.]+)/),E=t.match(/\((?:Mobile|Tablet); rv:([\d.]+)\).*Firefox\/[\d.]+/),S=t.match(/MSIE\s([\d.]+)/)||t.match(/Trident\/[\d](?=[^\?]+).*rv:([0-9.].)/),T=!x&&t.match(/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/),j=T||t.match(/Version\/([\d.]+)([^S](Safari)|[^M]*(Mobile)[^S]*(Safari))/);(i.webkit=!!r)&&(i.version=r[1]),o&&(n.android=!0,n.version=o[2]),f&&!u&&(n.ios=n.iphone=!0,n.version=f[2].replace(/_/g,".")),a&&(n.ios=n.ipad=!0,n.version=a[2].replace(/_/g,".")),u&&(n.ios=n.ipod=!0,n.version=u[3]?u[3].replace(/_/g,"."):null),h&&(n.wp=!0,n.version=h[1]),c&&(n.webos=!0,n.version=c[2]),p&&(n.touchpad=!0),g&&(n.blackberry=!0,n.version=g[2]),v&&(n.bb10=!0,n.version=v[2]),y&&(n.rimtabletos=!0,n.version=y[2]),b&&(i.playbook=!0),d&&(n.kindle=!0,n.version=d[1]),m&&(i.silk=!0,i.version=m[1]),!m&&n.android&&t.match(/Kindle Fire/)&&(i.silk=!0),x&&(i.chrome=!0,i.version=x[1]),w&&(i.firefox=!0,i.version=w[1]),E&&(n.firefoxos=!0,n.version=E[1]),S&&(i.ie=!0,i.version=S[1]),j&&(s||n.ios||l)&&(i.safari=!0,n.ios||(i.version=j[1])),T&&(i.webview=!0),n.tablet=!!(a||b||o&&!t.match(/Mobile/)||w&&t.match(/Tablet/)||S&&!t.match(/Phone/)&&t.match(/Touch/)),n.phone=!(n.tablet||n.ipod||!(o||f||c||g||v||x&&t.match(/Android/)||x&&t.match(/CriOS\/([\d.]+)/)||w&&t.match(/Mobile/)||S&&t.match(/Touch/)))}e.call(t,navigator.userAgent,navigator.platform),t.__detect=e}(Zepto),function(t,e){function v(t){return t.replace(/([a-z])([A-Z])/,"$1-$2").toLowerCase()}function y(t){return i?i+t:t.toLowerCase()}var i,a,u,f,c,l,h,p,d,m,n="",r={Webkit:"webkit",Moz:"",O:"o"},o=document.createElement("div"),s=/^((translate|rotate|scale)(X|Y|Z|3d)?|matrix(3d)?|perspective|skew(X|Y)?)$/i,g={};t.each(r,function(t,r){return o.style[t+"TransitionProperty"]!==e?(n="-"+t.toLowerCase()+"-",i=r,!1):void 0}),a=n+"transform",g[u=n+"transition-property"]=g[f=n+"transition-duration"]=g[l=n+"transition-delay"]=g[c=n+"transition-timing-function"]=g[h=n+"animation-name"]=g[p=n+"animation-duration"]=g[m=n+"animation-delay"]=g[d=n+"animation-timing-function"]="",t.fx={off:i===e&&o.style.transitionProperty===e,speeds:{_default:400,fast:200,slow:600},cssPrefix:n,transitionEnd:y("TransitionEnd"),animationEnd:y("AnimationEnd")},t.fn.animate=function(n,i,r,o,s){return t.isFunction(i)&&(o=i,r=e,i=e),t.isFunction(r)&&(o=r,r=e),t.isPlainObject(i)&&(r=i.easing,o=i.complete,s=i.delay,i=i.duration),i&&(i=("number"==typeof i?i:t.fx.speeds[i]||t.fx.speeds._default)/1e3),s&&(s=parseFloat(s)/1e3),this.anim(n,i,r,o,s)},t.fn.anim=function(n,i,r,o,y){var b,w,T,x={},E="",S=this,j=t.fx.transitionEnd,C=!1;if(i===e&&(i=t.fx.speeds._default/1e3),y===e&&(y=0),t.fx.off&&(i=0),"string"==typeof n)x[h]=n,x[p]=i+"s",x[m]=y+"s",x[d]=r||"linear",j=t.fx.animationEnd;else{w=[];for(b in n)s.test(b)?E+=b+"("+n[b]+") ":(x[b]=n[b],w.push(v(b)));E&&(x[a]=E,w.push(a)),i>0&&"object"==typeof n&&(x[u]=w.join(", "),x[f]=i+"s",x[l]=y+"s",x[c]=r||"linear")}return T=function(e){if("undefined"!=typeof e){if(e.target!==e.currentTarget)return;t(e.target).unbind(j,T)}else t(this).unbind(j,T);C=!0,t(this).css(g),o&&o.call(this)},i>0&&(this.bind(j,T),setTimeout(function(){C||T.call(S)},1e3*(i+y)+25)),this.size()&&this.get(0).clientLeft,this.css(x),0>=i&&setTimeout(function(){S.each(function(){T.call(this)})},0),this},o=null}(Zepto);;
/* Author:
    Max Degterev @suprMax
*/

;(function($) {
  var DEFAULTS = {
    endY: $.os.android ? 1 : 0,
    duration: 200,
    updateRate: 15
  };

  var interpolate = function (source, target, shift) {
    return (source + (target - source) * shift);
  };

  var easing = function (pos) {
    return (-Math.cos(pos * Math.PI) / 2) + .5;
  };

  var scroll = function(settings) {
    var options = $.extend({}, DEFAULTS, settings);

    if (options.duration === 0) {
      window.scrollTo(0, options.endY);
      if (typeof options.callback === 'function') options.callback();
      return;
    }

    var startY = window.pageYOffset,
        startT = Date.now(),
        finishT = startT + options.duration;

    var animate = function() {
      var now = Date.now(),
          shift = (now > finishT) ? 1 : (now - startT) / options.duration;

      window.scrollTo(0, interpolate(startY, options.endY, easing(shift)));

      if (now < finishT) {
        setTimeout(animate, options.updateRate);
      }
      else {
        if (typeof options.callback === 'function') options.callback();
      }
    };

    animate();
  };

  var scrollNode = function(settings) {
    var options = $.extend({}, DEFAULTS, settings);

    if (options.duration === 0) {
      this.scrollTop = options.endY;
      if (typeof options.callback === 'function') options.callback();
      return;
    }

    var startY = this.scrollTop,
        startT = Date.now(),
        finishT = startT + options.duration,
        _this = this;

    var animate = function() {
      var now = Date.now(),
          shift = (now > finishT) ? 1 : (now - startT) / options.duration;

      _this.scrollTop = interpolate(startY, options.endY, easing(shift));

      if (now < finishT) {
        setTimeout(animate, options.updateRate);
      }
      else {
        if (typeof options.callback === 'function') options.callback();
      }
    };

    animate();
  };

  $.scrollTo = scroll;

  $.fn.scrollTo = function() {
    if (this.length) {
      var args = arguments;
      this.forEach(function(elem, index) {
        scrollNode.apply(elem, args);
      });
    }
  };
}(Zepto));
;
// Do.js 2.0 pre online
// http://img3.douban.com/js/core/do/_init_.js
;(function(win, doc){

// 已加载模块
var loaded = {},

// 已加载列表
loadList = window.__external_files_loaded = window.__external_files_loaded || {},

// 加载中的模块
loadingFiles = window.__external_files_loading  = window.__external_files_loading || {},

// 内部配置文件
config = {
    // 是否自动加载核心库
    autoLoad: true,

    // 加载延迟
    timeout: 6000,

    // 核心库
    coreLib: [],

    /* 模块依赖
     * {
     *  moduleName: {
     *      path: 'URL',
     *      type:'js|css',
     *      requires:['moduleName1', 'fileURL']
     *  }
     * }
     */
    mods: {}
},

jsSelf = (function() {
  var files = doc.getElementsByTagName('script');
  return files[files.length - 1];
})(),

// 全局模块
globalList = [],

// 外部参数
extConfig,

// domready回调堆栈
readyList = [],

// DOM Ready
isReady = false,

// 模块间的公共数据
publicData = {},

// 公共数据回调堆栈
publicDataStack = {},

isArray = function(e) {
  return e.constructor === Array;
},

getMod = function(e) {
 var mods = config.mods, mod;
 if (typeof e === 'string') {
   mod = (mods[e])? mods[e] : { path: e };
 } else {
   mod = e;
 }
 return mod;
},

load = function(url, type, charset, cb) {
    var wait, n, t, img,

    done = function() {
      loaded[url] = 1;
      cb && cb(url);
      cb = null;
      win.clearTimeout(wait);
    };

    if (!url) {
        return;
    }

    if (loaded[url]) {
        loadingFiles[url] = false;
        if (cb) {
            cb(url);
        }
        return;
    }

    if (loadingFiles[url]) {
        setTimeout(function() {
            load(url, type, charset, cb);
        }, 10);
        return;
    }

    loadingFiles[url] = true;

    wait = win.setTimeout(function() {
    /* 目前延时回调处理，超时后如果有延时回调，执行回调，然后继续等
     * 延时回调的意义是log延时长的URI，这个处理不属于加载器本身的功能移到外部
     * 没有跳过是为了避免错误。
     */
      if (config.timeoutCallback) {
        try {
          config.timeoutCallback(url);
        } catch(ex) {}
      }
    }, config.timeout);

    t =  type || url.toLowerCase().split(/\./).pop().replace(/[\?#].*/, '');
    if (!type) { return undefined; }

    if (t === 'js') {
      n = doc.createElement('script');
      n.setAttribute('type', 'text/javascript');
      n.setAttribute('src', url);
      n.setAttribute('async', true);
    } else if (t === 'css') {
      n = doc.createElement('link');
      n.setAttribute('type', 'text/css');
      n.setAttribute('rel', 'stylesheet');
      n.setAttribute('href', url);
    }

    if (charset) {
      n.charset = charset;
    }

    if (t === 'css') {
      // 暂不判断css错误
      // img = new Image();
      // img.onerror = function() {
      //   done();
      //   img.onerror = null;
      //   img = null;
      // }
      // img.src = url;
      setTimeout(function(){
        done();
      },0);
    } else {
    //if (t === 'js') {
      // firefox, safari, chrome, ie9下加载失败触发
      // 如果文件是404, 会比timeout早触发onerror。目前不处理404，只处理超时
      n.onerror = function() {
        done();
        n.onerror = null;
      };

      // ie6~8通过创建vbscript可以识别是否加载成功。
      // 但这样需先测试性加载再加载影响性能。即使没成功加载而触发cb，顶多报错，没必要杜绝这种报错

      // ie6~9下加载成功或失败，firefox, safari, opera下加载成功触发
      n.onload = n.onreadystatechange = function() {
        var url;
        if (!this.readyState ||
            this.readyState === 'loaded' ||
            this.readyState === 'complete') {
          setTimeout(function(){
            done();
          },0);
          n.onload = n.onreadystatechange = null;
        }
      };
    }
    if (window.Turbolinks && window.Turbolinks.supported) {
      n.setAttribute('data-turbolinks-skip', 'true');
      document.documentElement.childNodes[0].appendChild(n);
    } else {
      jsSelf.parentNode.insertBefore(n, jsSelf);
    }
},

  // 加载依赖论文件(顺序)
  loadDeps = function(deps, cb) {
    var mods = config.mods,
    id, m, mod, i = 0, len;

    id = deps.join('');
    len = deps.length;

    if (loadList[id]) {
      cb();
      return;
    }

    function callback() {
      if(!--len) {
        loadList[id] = 1;
        cb();
      }
    }

    for (; m = deps[i++]; ) {
      mod = getMod(m);
      if (mod.requires) {
        loadDeps(mod.requires, (function(mod){
          return function(){
            load(mod.path, mod.type, mod.charset, callback);
          };
        })(mod));
      } else {
        load(mod.path, mod.type, mod.charset, callback);
      }
    }
  },

  /*!
   * contentloaded.js
   *
   * Author: Diego Perini (diego.perini at gmail.com)
   * Summary: cross-browser wrapper for DOMContentLoaded
   * Updated: 20101020
   * License: MIT
   * Version: 1.2
   *
   * URL:
   * http://javascript.nwbox.com/ContentLoaded/
   * http://javascript.nwbox.com/ContentLoaded/MIT-LICENSE
   *
   */

  // @win window reference
  // @fn function reference
  contentLoaded = function(fn) {
    var done = false, top = true,
    doc = win.document,
    root = doc.documentElement,
    add = doc.addEventListener ? 'addEventListener' : 'attachEvent',
    rem = doc.addEventListener ? 'removeEventListener' : 'detachEvent',
    pre = doc.addEventListener ? '' : 'on',

    init = function(e) {
      if (e.type == 'readystatechange' && doc.readyState != 'complete') return;
      (e.type == 'load' ? win : doc)[rem](pre + e.type, init, false);
      if (!done && (done = true)) fn.call(win, e.type || e);
    },

    poll = function() {
      try { root.doScroll('left'); } catch(e) { setTimeout(poll, 50); return; }
      init('poll');
    };

    if (doc.readyState == 'complete') fn.call(win, 'lazy');
    else {
      if (doc.createEventObject && root.doScroll) {
        try { top = !win.frameElement; } catch(e) { }
        if (top) {
          poll();
        }
      }
      doc[add](pre + 'DOMContentLoaded', init, false);
      doc[add](pre + 'readystatechange', init, false);
      win[add](pre + 'load', init, false);
    }
  },

  fireReadyList = function() {
    var list;
    while (list = readyList.shift()) {
      d.apply(null, list);
    }
  },

  d = function() {
    var args = [].slice.call(arguments), fn, id;

    // 加载核心库
    if (config.autoLoad &&
        config.coreLib.length &&
        !loadList[config.coreLib.join('')]) {
      loadDeps(config.coreLib, function(){
        d.apply(null, args);
      });
      return;
    }

    // 加载全局库
    if (globalList.length &&
        !loadList[globalList.join('')]) {
      loadDeps(globalList, function(){
        d.apply(null, args);
      });
      return;
    }

    if (typeof args[args.length - 1] === 'function' ) {
      fn = args.pop();
    }

    id = args.join('');

    if ((args.length === 0 || loadList[id]) && fn) {
      fn();
      return;
    }

    loadDeps(args, function() {
      loadList[id] = 1;
      fn && fn();
    });
  };

d.add = d.define = function(sName, oConfig) {
  if (!sName || !oConfig || !oConfig.path) {
    return;
  }
  config.mods[sName] = oConfig;
};

d.delay = function() {
  var args = [].slice.call(arguments), delay = args.shift();
  win.setTimeout(function() {
      d.apply(this, args);
      }, delay);
};

d.global = function() {
  var args = isArray(arguments[0])? arguments[0] : [].slice.call(arguments);
  globalList = globalList.concat(args);
};

d.ready = function() {
  var args = [].slice.call(arguments);
  if (isReady) {
    return d.apply(this, args);
  }
  readyList.push(args);
};

d.css = function(s) {
  var css = doc.getElementById('do-inline-css');
  if (!css) {
    css = doc.createElement('style');
    css.type = 'text/css';
    css.id = 'do-inline-css';
    jsSelf.parentNode.insertBefore(css, jsSelf);
  }

  if (css.styleSheet) {
    css.styleSheet.cssText = css.styleSheet.cssText + s;
  } else {
    css.appendChild(doc.createTextNode(s));
  }
};

d.setData = d.setPublicData = function(prop, value) {
  var cbStack = publicDataStack[prop];

  publicData[prop] = value;

  if (!cbStack) {
    return;
  }

  while (cbStack.length > 0) {
    (cbStack.pop()).call(this, value);
  }
};

d.getData = d.getPublicData = function(prop, cb) {
  if (publicData[prop]) {
    cb(publicData[prop]);
    return;
  }

  if (!publicDataStack[prop]) {
    publicDataStack[prop] = [];
  }

  publicDataStack[prop].push(function(value){
      cb(value);
      });
};

d.setConfig = function(n, v) {
  config[n] = v;
  return d;
};

d.getConfig = function(n) {
  return config[n];
};

d.setLoaded = function(data) {
  var item;
  if (!data) {
    return;
  }
  if (data.constructor === Array) {
    for (var i=0, j=data.length; i<j; i++) {
      item = config.mods[data[i]]
      loaded[item.path] = 1;
    }
  }
  else if (typeof data === 'string') {
    item = config.mods[data]
    loaded[item.path] = 1;
  }
}

// 初始外部配置
extConfig = jsSelf.getAttribute('data-cfg-autoload');
if (extConfig) {
  config.autoLoad = (extConfig.toLowerCase() === 'true') ? true : false;
}

extConfig = jsSelf.getAttribute('data-cfg-corelib');
if (extConfig) {
  config.coreLib = extConfig.split(',');
}

if (typeof Do !== 'undefined') {
  globalList = Do.global.mods;
  config.mods = Do.mods;
  //readyList = Do.actions;
  var act;
  while (act = Do.actions.shift()) {
    d.apply(null, act);
  }
  delete Do;
}

win.Do = d;

contentLoaded(function() {
  isReady = true;
  fireReadyList();
});

})(window, document);
//@import /js/welcome.js
;
/*! iScroll v5.1.1 ~ (c) 2008-2014 Matteo Spinelli ~ http://cubiq.org/license */
(function (window, document, Math) {
var rAF = window.requestAnimationFrame	||
	window.webkitRequestAnimationFrame	||
	window.mozRequestAnimationFrame		||
	window.oRequestAnimationFrame		||
	window.msRequestAnimationFrame		||
	function (callback) { window.setTimeout(callback, 1000 / 60); };

var utils = (function () {
	var me = {};

	var _elementStyle = document.createElement('div').style;
	var _vendor = (function () {
		var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
			transform,
			i = 0,
			l = vendors.length;

		for ( ; i < l; i++ ) {
			transform = vendors[i] + 'ransform';
			if ( transform in _elementStyle ) return vendors[i].substr(0, vendors[i].length-1);
		}

		return false;
	})();

	function _prefixStyle (style) {
		if ( _vendor === false ) return false;
		if ( _vendor === '' ) return style;
		return _vendor + style.charAt(0).toUpperCase() + style.substr(1);
	}

	me.getTime = Date.now || function getTime () { return new Date().getTime(); };

	me.extend = function (target, obj) {
		for ( var i in obj ) {
			target[i] = obj[i];
		}
	};

	me.addEvent = function (el, type, fn, capture) {
		el.addEventListener(type, fn, !!capture);
	};

	me.removeEvent = function (el, type, fn, capture) {
		el.removeEventListener(type, fn, !!capture);
	};

	me.momentum = function (current, start, time, lowerMargin, wrapperSize, deceleration) {
		var distance = current - start,
			speed = Math.abs(distance) / time,
			destination,
			duration;

		deceleration = deceleration === undefined ? 0.0006 : deceleration;

		destination = current + ( speed * speed ) / ( 2 * deceleration ) * ( distance < 0 ? -1 : 1 );
		duration = speed / deceleration;

		if ( destination < lowerMargin ) {
			destination = wrapperSize ? lowerMargin - ( wrapperSize / 2.5 * ( speed / 8 ) ) : lowerMargin;
			distance = Math.abs(destination - current);
			duration = distance / speed;
		} else if ( destination > 0 ) {
			destination = wrapperSize ? wrapperSize / 2.5 * ( speed / 8 ) : 0;
			distance = Math.abs(current) + destination;
			duration = distance / speed;
		}

		return {
			destination: Math.round(destination),
			duration: duration
		};
	};

	var _transform = _prefixStyle('transform');

	me.extend(me, {
		hasTransform: _transform !== false,
		hasPerspective: _prefixStyle('perspective') in _elementStyle,
		hasTouch: 'ontouchstart' in window,
		hasPointer: navigator.msPointerEnabled,
		hasTransition: _prefixStyle('transition') in _elementStyle
	});

	// This should find all Android browsers lower than build 535.19 (both stock browser and webview)
	me.isBadAndroid = /Android /.test(window.navigator.appVersion) && !(/Chrome\/\d/.test(window.navigator.appVersion));

	me.extend(me.style = {}, {
		transform: _transform,
		transitionTimingFunction: _prefixStyle('transitionTimingFunction'),
		transitionDuration: _prefixStyle('transitionDuration'),
		transitionDelay: _prefixStyle('transitionDelay'),
		transformOrigin: _prefixStyle('transformOrigin')
	});

	me.hasClass = function (e, c) {
		var re = new RegExp("(^|\\s)" + c + "(\\s|$)");
		return re.test(e.className);
	};

	me.addClass = function (e, c) {
		if ( me.hasClass(e, c) ) {
			return;
		}

		var newclass = e.className.split(' ');
		newclass.push(c);
		e.className = newclass.join(' ');
	};

	me.removeClass = function (e, c) {
		if ( !me.hasClass(e, c) ) {
			return;
		}

		var re = new RegExp("(^|\\s)" + c + "(\\s|$)", 'g');
		e.className = e.className.replace(re, ' ');
	};

	me.offset = function (el) {
		var left = -el.offsetLeft,
			top = -el.offsetTop;

		// jshint -W084
		while (el = el.offsetParent) {
			left -= el.offsetLeft;
			top -= el.offsetTop;
		}
		// jshint +W084

		return {
			left: left,
			top: top
		};
	};

	me.preventDefaultException = function (el, exceptions) {
		for ( var i in exceptions ) {
			if ( exceptions[i].test(el[i]) ) {
				return true;
			}
		}

		return false;
	};

	me.extend(me.eventType = {}, {
		touchstart: 1,
		touchmove: 1,
		touchend: 1,

		mousedown: 2,
		mousemove: 2,
		mouseup: 2,

		MSPointerDown: 3,
		MSPointerMove: 3,
		MSPointerUp: 3
	});

	me.extend(me.ease = {}, {
		quadratic: {
			style: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
			fn: function (k) {
				return k * ( 2 - k );
			}
		},
		circular: {
			style: 'cubic-bezier(0.1, 0.57, 0.1, 1)',	// Not properly "circular" but this looks better, it should be (0.075, 0.82, 0.165, 1)
			fn: function (k) {
				return Math.sqrt( 1 - ( --k * k ) );
			}
		},
		back: {
			style: 'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
			fn: function (k) {
				var b = 4;
				return ( k = k - 1 ) * k * ( ( b + 1 ) * k + b ) + 1;
			}
		},
		bounce: {
			style: '',
			fn: function (k) {
				if ( ( k /= 1 ) < ( 1 / 2.75 ) ) {
					return 7.5625 * k * k;
				} else if ( k < ( 2 / 2.75 ) ) {
					return 7.5625 * ( k -= ( 1.5 / 2.75 ) ) * k + 0.75;
				} else if ( k < ( 2.5 / 2.75 ) ) {
					return 7.5625 * ( k -= ( 2.25 / 2.75 ) ) * k + 0.9375;
				} else {
					return 7.5625 * ( k -= ( 2.625 / 2.75 ) ) * k + 0.984375;
				}
			}
		},
		elastic: {
			style: '',
			fn: function (k) {
				var f = 0.22,
					e = 0.4;

				if ( k === 0 ) { return 0; }
				if ( k == 1 ) { return 1; }

				return ( e * Math.pow( 2, - 10 * k ) * Math.sin( ( k - f / 4 ) * ( 2 * Math.PI ) / f ) + 1 );
			}
		}
	});

	me.tap = function (e, eventName) {
		var ev = document.createEvent('Event');
		ev.initEvent(eventName, true, true);
		ev.pageX = e.pageX;
		ev.pageY = e.pageY;
		e.target.dispatchEvent(ev);
	};

	me.click = function (e) {
		var target = e.target,
			ev;

		if ( !(/(SELECT|INPUT|TEXTAREA)/i).test(target.tagName) ) {
			ev = document.createEvent('MouseEvents');
			ev.initMouseEvent('click', true, true, e.view, 1,
				target.screenX, target.screenY, target.clientX, target.clientY,
				e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
				0, null);

			ev._constructed = true;
			ev.forwardedTouchEvent = true;
			target.dispatchEvent(ev);
		}
	};

	return me;
})();

function IScroll (el, options) {
	this.wrapper = typeof el == 'string' ? document.querySelector(el) : el;
	this.scroller = this.wrapper.children[0];
	this.scrollerStyle = this.scroller.style;		// cache style for better performance

	this.options = {

		resizeScrollbars: true,

		mouseWheelSpeed: 20,

		snapThreshold: 0.334,

// INSERT POINT: OPTIONS 

		startX: 0,
		startY: 0,
		scrollY: true,
		directionLockThreshold: 5,
		momentum: true,

		bounce: true,
		bounceTime: 600,
		bounceEasing: '',

		preventDefault: true,
		preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT)$/ },

		HWCompositing: true,
		useTransition: true,
		useTransform: true
	};

	for ( var i in options ) {
		this.options[i] = options[i];
	}

	// Normalize options
	this.translateZ = this.options.HWCompositing && utils.hasPerspective ? ' translateZ(0)' : '';

	this.options.useTransition = utils.hasTransition && this.options.useTransition;
	this.options.useTransform = utils.hasTransform && this.options.useTransform;

	this.options.eventPassthrough = this.options.eventPassthrough === true ? 'vertical' : this.options.eventPassthrough;
	this.options.preventDefault = !this.options.eventPassthrough && this.options.preventDefault;

	// If you want eventPassthrough I have to lock one of the axes
	this.options.scrollY = this.options.eventPassthrough == 'vertical' ? false : this.options.scrollY;
	this.options.scrollX = this.options.eventPassthrough == 'horizontal' ? false : this.options.scrollX;

	// With eventPassthrough we also need lockDirection mechanism
	this.options.freeScroll = this.options.freeScroll && !this.options.eventPassthrough;
	this.options.directionLockThreshold = this.options.eventPassthrough ? 0 : this.options.directionLockThreshold;

	this.options.bounceEasing = typeof this.options.bounceEasing == 'string' ? utils.ease[this.options.bounceEasing] || utils.ease.circular : this.options.bounceEasing;

	this.options.resizePolling = this.options.resizePolling === undefined ? 60 : this.options.resizePolling;

	if ( this.options.tap === true ) {
		this.options.tap = 'tap';
	}

	if ( this.options.shrinkScrollbars == 'scale' ) {
		this.options.useTransition = false;
	}

	this.options.invertWheelDirection = this.options.invertWheelDirection ? -1 : 1;

// INSERT POINT: NORMALIZATION

	// Some defaults	
	this.x = 0;
	this.y = 0;
	this.directionX = 0;
	this.directionY = 0;
	this._events = {};

// INSERT POINT: DEFAULTS

	this._init();
	this.refresh();

	this.scrollTo(this.options.startX, this.options.startY);
	this.enable();
}

IScroll.prototype = {
	version: '5.1.1',

	_init: function () {
		this._initEvents();

		if ( this.options.scrollbars || this.options.indicators ) {
			this._initIndicators();
		}

		if ( this.options.mouseWheel ) {
			this._initWheel();
		}

		if ( this.options.snap ) {
			this._initSnap();
		}

		if ( this.options.keyBindings ) {
			this._initKeys();
		}

// INSERT POINT: _init

	},

	destroy: function () {
		this._initEvents(true);

		this._execEvent('destroy');
	},

	_transitionEnd: function (e) {
		if ( e.target != this.scroller || !this.isInTransition ) {
			return;
		}

		this._transitionTime();
		if ( !this.resetPosition(this.options.bounceTime) ) {
			this.isInTransition = false;
			this._execEvent('scrollEnd');
		}
	},

	_start: function (e) {
		// React to left mouse button only
		if ( utils.eventType[e.type] != 1 ) {
			if ( e.button !== 0 ) {
				return;
			}
		}

		if ( !this.enabled || (this.initiated && utils.eventType[e.type] !== this.initiated) ) {
			return;
		}

		if ( this.options.preventDefault && !utils.isBadAndroid && !utils.preventDefaultException(e.target, this.options.preventDefaultException) ) {
			e.preventDefault();
		}

		var point = e.touches ? e.touches[0] : e,
			pos;

		this.initiated	= utils.eventType[e.type];
		this.moved		= false;
		this.distX		= 0;
		this.distY		= 0;
		this.directionX = 0;
		this.directionY = 0;
		this.directionLocked = 0;

		this._transitionTime();

		this.startTime = utils.getTime();

		if ( this.options.useTransition && this.isInTransition ) {
			this.isInTransition = false;
			pos = this.getComputedPosition();
			this._translate(Math.round(pos.x), Math.round(pos.y));
			this._execEvent('scrollEnd');
		} else if ( !this.options.useTransition && this.isAnimating ) {
			this.isAnimating = false;
			this._execEvent('scrollEnd');
		}

		this.startX    = this.x;
		this.startY    = this.y;
		this.absStartX = this.x;
		this.absStartY = this.y;
		this.pointX    = point.pageX;
		this.pointY    = point.pageY;

		this._execEvent('beforeScrollStart');
	},

	_move: function (e) {
		if ( !this.enabled || utils.eventType[e.type] !== this.initiated ) {
			return;
		}

		if ( this.options.preventDefault ) {	// increases performance on Android? TODO: check!
			e.preventDefault();
		}

		var point		= e.touches ? e.touches[0] : e,
			deltaX		= point.pageX - this.pointX,
			deltaY		= point.pageY - this.pointY,
			timestamp	= utils.getTime(),
			newX, newY,
			absDistX, absDistY;

		this.pointX		= point.pageX;
		this.pointY		= point.pageY;

		this.distX		+= deltaX;
		this.distY		+= deltaY;
		absDistX		= Math.abs(this.distX);
		absDistY		= Math.abs(this.distY);

		// We need to move at least 10 pixels for the scrolling to initiate
		if ( timestamp - this.endTime > 300 && (absDistX < 10 && absDistY < 10) ) {
			return;
		}

		// If you are scrolling in one direction lock the other
		if ( !this.directionLocked && !this.options.freeScroll ) {
			if ( absDistX > absDistY + this.options.directionLockThreshold ) {
				this.directionLocked = 'h';		// lock horizontally
			} else if ( absDistY >= absDistX + this.options.directionLockThreshold ) {
				this.directionLocked = 'v';		// lock vertically
			} else {
				this.directionLocked = 'n';		// no lock
			}
		}

		if ( this.directionLocked == 'h' ) {
			if ( this.options.eventPassthrough == 'vertical' ) {
				e.preventDefault();
			} else if ( this.options.eventPassthrough == 'horizontal' ) {
				this.initiated = false;
				return;
			}

			deltaY = 0;
		} else if ( this.directionLocked == 'v' ) {
			if ( this.options.eventPassthrough == 'horizontal' ) {
				e.preventDefault();
			} else if ( this.options.eventPassthrough == 'vertical' ) {
				this.initiated = false;
				return;
			}

			deltaX = 0;
		}

		deltaX = this.hasHorizontalScroll ? deltaX : 0;
		deltaY = this.hasVerticalScroll ? deltaY : 0;

		newX = this.x + deltaX;
		newY = this.y + deltaY;

		// Slow down if outside of the boundaries
		if ( newX > 0 || newX < this.maxScrollX ) {
			newX = this.options.bounce ? this.x + deltaX / 3 : newX > 0 ? 0 : this.maxScrollX;
		}
		if ( newY > 0 || newY < this.maxScrollY ) {
			newY = this.options.bounce ? this.y + deltaY / 3 : newY > 0 ? 0 : this.maxScrollY;
		}

		this.directionX = deltaX > 0 ? -1 : deltaX < 0 ? 1 : 0;
		this.directionY = deltaY > 0 ? -1 : deltaY < 0 ? 1 : 0;

		if ( !this.moved ) {
			this._execEvent('scrollStart');
		}

		this.moved = true;

		this._translate(newX, newY);

/* REPLACE START: _move */

		if ( timestamp - this.startTime > 300 ) {
			this.startTime = timestamp;
			this.startX = this.x;
			this.startY = this.y;
		}

/* REPLACE END: _move */

	},

	_end: function (e) {
		if ( !this.enabled || utils.eventType[e.type] !== this.initiated ) {
			return;
		}

		if ( this.options.preventDefault && !utils.preventDefaultException(e.target, this.options.preventDefaultException) ) {
			e.preventDefault();
		}

		var point = e.changedTouches ? e.changedTouches[0] : e,
			momentumX,
			momentumY,
			duration = utils.getTime() - this.startTime,
			newX = Math.round(this.x),
			newY = Math.round(this.y),
			distanceX = Math.abs(newX - this.startX),
			distanceY = Math.abs(newY - this.startY),
			time = 0,
			easing = '';

		this.isInTransition = 0;
		this.initiated = 0;
		this.endTime = utils.getTime();

		// reset if we are outside of the boundaries
		if ( this.resetPosition(this.options.bounceTime) ) {
			return;
		}

		this.scrollTo(newX, newY);	// ensures that the last position is rounded

		// we scrolled less than 10 pixels
		if ( !this.moved ) {
			if ( this.options.tap ) {
				utils.tap(e, this.options.tap);
			}

			if ( this.options.click ) {
				utils.click(e);
			}

			this._execEvent('scrollCancel');
			return;
		}

		if ( this._events.flick && duration < 200 && distanceX < 100 && distanceY < 100 ) {
			this._execEvent('flick');
			return;
		}

		// start momentum animation if needed
		if ( this.options.momentum && duration < 300 ) {
			momentumX = this.hasHorizontalScroll ? utils.momentum(this.x, this.startX, duration, this.maxScrollX, this.options.bounce ? this.wrapperWidth : 0, this.options.deceleration) : { destination: newX, duration: 0 };
			momentumY = this.hasVerticalScroll ? utils.momentum(this.y, this.startY, duration, this.maxScrollY, this.options.bounce ? this.wrapperHeight : 0, this.options.deceleration) : { destination: newY, duration: 0 };
			newX = momentumX.destination;
			newY = momentumY.destination;
			time = Math.max(momentumX.duration, momentumY.duration);
			this.isInTransition = 1;
		}


		if ( this.options.snap ) {
			var snap = this._nearestSnap(newX, newY);
			this.currentPage = snap;
			time = this.options.snapSpeed || Math.max(
					Math.max(
						Math.min(Math.abs(newX - snap.x), 1000),
						Math.min(Math.abs(newY - snap.y), 1000)
					), 300);
			newX = snap.x;
			newY = snap.y;

			this.directionX = 0;
			this.directionY = 0;
			easing = this.options.bounceEasing;
		}

// INSERT POINT: _end

		if ( newX != this.x || newY != this.y ) {
			// change easing function when scroller goes out of the boundaries
			if ( newX > 0 || newX < this.maxScrollX || newY > 0 || newY < this.maxScrollY ) {
				easing = utils.ease.quadratic;
			}

			this.scrollTo(newX, newY, time, easing);
			return;
		}

		this._execEvent('scrollEnd');
	},

	_resize: function () {
		var that = this;

		clearTimeout(this.resizeTimeout);

		this.resizeTimeout = setTimeout(function () {
			that.refresh();
		}, this.options.resizePolling);
	},

	resetPosition: function (time) {
		var x = this.x,
			y = this.y;

		time = time || 0;

		if ( !this.hasHorizontalScroll || this.x > 0 ) {
			x = 0;
		} else if ( this.x < this.maxScrollX ) {
			x = this.maxScrollX;
		}

		if ( !this.hasVerticalScroll || this.y > 0 ) {
			y = 0;
		} else if ( this.y < this.maxScrollY ) {
			y = this.maxScrollY;
		}

		if ( x == this.x && y == this.y ) {
			return false;
		}

		this.scrollTo(x, y, time, this.options.bounceEasing);

		return true;
	},

	disable: function () {
		this.enabled = false;
	},

	enable: function () {
		this.enabled = true;
	},

	refresh: function () {
		var rf = this.wrapper.offsetHeight;		// Force reflow

		this.wrapperWidth	= this.wrapper.clientWidth;
		this.wrapperHeight	= this.wrapper.clientHeight;

/* REPLACE START: refresh */

		this.scrollerWidth	= this.scroller.offsetWidth;
		this.scrollerHeight	= this.scroller.offsetHeight;

		this.maxScrollX		= this.wrapperWidth - this.scrollerWidth;
		this.maxScrollY		= this.wrapperHeight - this.scrollerHeight;

/* REPLACE END: refresh */

		this.hasHorizontalScroll	= this.options.scrollX && this.maxScrollX < 0;
		this.hasVerticalScroll		= this.options.scrollY && this.maxScrollY < 0;

		if ( !this.hasHorizontalScroll ) {
			this.maxScrollX = 0;
			this.scrollerWidth = this.wrapperWidth;
		}

		if ( !this.hasVerticalScroll ) {
			this.maxScrollY = 0;
			this.scrollerHeight = this.wrapperHeight;
		}

		this.endTime = 0;
		this.directionX = 0;
		this.directionY = 0;

		this.wrapperOffset = utils.offset(this.wrapper);

		this._execEvent('refresh');

		this.resetPosition();

// INSERT POINT: _refresh

	},

	on: function (type, fn) {
		if ( !this._events[type] ) {
			this._events[type] = [];
		}

		this._events[type].push(fn);
	},

	off: function (type, fn) {
		if ( !this._events[type] ) {
			return;
		}

		var index = this._events[type].indexOf(fn);

		if ( index > -1 ) {
			this._events[type].splice(index, 1);
		}
	},

	_execEvent: function (type) {
		if ( !this._events[type] ) {
			return;
		}

		var i = 0,
			l = this._events[type].length;

		if ( !l ) {
			return;
		}

		for ( ; i < l; i++ ) {
			this._events[type][i].apply(this, [].slice.call(arguments, 1));
		}
	},

	scrollBy: function (x, y, time, easing) {
		x = this.x + x;
		y = this.y + y;
		time = time || 0;

		this.scrollTo(x, y, time, easing);
	},

	scrollTo: function (x, y, time, easing) {
		easing = easing || utils.ease.circular;

		this.isInTransition = this.options.useTransition && time > 0;

		if ( !time || (this.options.useTransition && easing.style) ) {
			this._transitionTimingFunction(easing.style);
			this._transitionTime(time);
			this._translate(x, y);
		} else {
			this._animate(x, y, time, easing.fn);
		}
	},

	scrollToElement: function (el, time, offsetX, offsetY, easing) {
		el = el.nodeType ? el : this.scroller.querySelector(el);

		if ( !el ) {
			return;
		}

		var pos = utils.offset(el);

		pos.left -= this.wrapperOffset.left;
		pos.top  -= this.wrapperOffset.top;

		// if offsetX/Y are true we center the element to the screen
		if ( offsetX === true ) {
			offsetX = Math.round(el.offsetWidth / 2 - this.wrapper.offsetWidth / 2);
		}
		if ( offsetY === true ) {
			offsetY = Math.round(el.offsetHeight / 2 - this.wrapper.offsetHeight / 2);
		}

		pos.left -= offsetX || 0;
		pos.top  -= offsetY || 0;

		pos.left = pos.left > 0 ? 0 : pos.left < this.maxScrollX ? this.maxScrollX : pos.left;
		pos.top  = pos.top  > 0 ? 0 : pos.top  < this.maxScrollY ? this.maxScrollY : pos.top;

		time = time === undefined || time === null || time === 'auto' ? Math.max(Math.abs(this.x-pos.left), Math.abs(this.y-pos.top)) : time;

		this.scrollTo(pos.left, pos.top, time, easing);
	},

	_transitionTime: function (time) {
		time = time || 0;

		this.scrollerStyle[utils.style.transitionDuration] = time + 'ms';

		if ( !time && utils.isBadAndroid ) {
			this.scrollerStyle[utils.style.transitionDuration] = '0.001s';
		}


		if ( this.indicators ) {
			for ( var i = this.indicators.length; i--; ) {
				this.indicators[i].transitionTime(time);
			}
		}


// INSERT POINT: _transitionTime

	},

	_transitionTimingFunction: function (easing) {
		this.scrollerStyle[utils.style.transitionTimingFunction] = easing;


		if ( this.indicators ) {
			for ( var i = this.indicators.length; i--; ) {
				this.indicators[i].transitionTimingFunction(easing);
			}
		}


// INSERT POINT: _transitionTimingFunction

	},

	_translate: function (x, y) {
		if ( this.options.useTransform ) {

/* REPLACE START: _translate */

			this.scrollerStyle[utils.style.transform] = 'translate(' + x + 'px,' + y + 'px)' + this.translateZ;

/* REPLACE END: _translate */

		} else {
			x = Math.round(x);
			y = Math.round(y);
			this.scrollerStyle.left = x + 'px';
			this.scrollerStyle.top = y + 'px';
		}

		this.x = x;
		this.y = y;


	if ( this.indicators ) {
		for ( var i = this.indicators.length; i--; ) {
			this.indicators[i].updatePosition();
		}
	}


// INSERT POINT: _translate

	},

	_initEvents: function (remove) {
		var eventType = remove ? utils.removeEvent : utils.addEvent,
			target = this.options.bindToWrapper ? this.wrapper : window;

		eventType(window, 'orientationchange', this);
		eventType(window, 'resize', this);

		if ( this.options.click ) {
			eventType(this.wrapper, 'click', this, true);
		}

		if ( !this.options.disableMouse ) {
			eventType(this.wrapper, 'mousedown', this);
			eventType(target, 'mousemove', this);
			eventType(target, 'mousecancel', this);
			eventType(target, 'mouseup', this);
		}

		if ( utils.hasPointer && !this.options.disablePointer ) {
			eventType(this.wrapper, 'MSPointerDown', this);
			eventType(target, 'MSPointerMove', this);
			eventType(target, 'MSPointerCancel', this);
			eventType(target, 'MSPointerUp', this);
		}

		if ( utils.hasTouch && !this.options.disableTouch ) {
			eventType(this.wrapper, 'touchstart', this);
			eventType(target, 'touchmove', this);
			eventType(target, 'touchcancel', this);
			eventType(target, 'touchend', this);
		}

		eventType(this.scroller, 'transitionend', this);
		eventType(this.scroller, 'webkitTransitionEnd', this);
		eventType(this.scroller, 'oTransitionEnd', this);
		eventType(this.scroller, 'MSTransitionEnd', this);
	},

	getComputedPosition: function () {
		var matrix = window.getComputedStyle(this.scroller, null),
			x, y;

		if ( this.options.useTransform ) {
			matrix = matrix[utils.style.transform].split(')')[0].split(', ');
			x = +(matrix[12] || matrix[4]);
			y = +(matrix[13] || matrix[5]);
		} else {
			x = +matrix.left.replace(/[^-\d.]/g, '');
			y = +matrix.top.replace(/[^-\d.]/g, '');
		}

		return { x: x, y: y };
	},

	_initIndicators: function () {
		var interactive = this.options.interactiveScrollbars,
			customStyle = typeof this.options.scrollbars != 'string',
			indicators = [],
			indicator;

		var that = this;

		this.indicators = [];

		if ( this.options.scrollbars ) {
			// Vertical scrollbar
			if ( this.options.scrollY ) {
				indicator = {
					el: createDefaultScrollbar('v', interactive, this.options.scrollbars),
					interactive: interactive,
					defaultScrollbars: true,
					customStyle: customStyle,
					resize: this.options.resizeScrollbars,
					shrink: this.options.shrinkScrollbars,
					fade: this.options.fadeScrollbars,
					listenX: false
				};

				this.wrapper.appendChild(indicator.el);
				indicators.push(indicator);
			}

			// Horizontal scrollbar
			if ( this.options.scrollX ) {
				indicator = {
					el: createDefaultScrollbar('h', interactive, this.options.scrollbars),
					interactive: interactive,
					defaultScrollbars: true,
					customStyle: customStyle,
					resize: this.options.resizeScrollbars,
					shrink: this.options.shrinkScrollbars,
					fade: this.options.fadeScrollbars,
					listenY: false
				};

				this.wrapper.appendChild(indicator.el);
				indicators.push(indicator);
			}
		}

		if ( this.options.indicators ) {
			// TODO: check concat compatibility
			indicators = indicators.concat(this.options.indicators);
		}

		for ( var i = indicators.length; i--; ) {
			this.indicators.push( new Indicator(this, indicators[i]) );
		}

		// TODO: check if we can use array.map (wide compatibility and performance issues)
		function _indicatorsMap (fn) {
			for ( var i = that.indicators.length; i--; ) {
				fn.call(that.indicators[i]);
			}
		}

		if ( this.options.fadeScrollbars ) {
			this.on('scrollEnd', function () {
				_indicatorsMap(function () {
					this.fade();
				});
			});

			this.on('scrollCancel', function () {
				_indicatorsMap(function () {
					this.fade();
				});
			});

			this.on('scrollStart', function () {
				_indicatorsMap(function () {
					this.fade(1);
				});
			});

			this.on('beforeScrollStart', function () {
				_indicatorsMap(function () {
					this.fade(1, true);
				});
			});
		}


		this.on('refresh', function () {
			_indicatorsMap(function () {
				this.refresh();
			});
		});

		this.on('destroy', function () {
			_indicatorsMap(function () {
				this.destroy();
			});

			delete this.indicators;
		});
	},

	_initWheel: function () {
		utils.addEvent(this.wrapper, 'wheel', this);
		utils.addEvent(this.wrapper, 'mousewheel', this);
		utils.addEvent(this.wrapper, 'DOMMouseScroll', this);

		this.on('destroy', function () {
			utils.removeEvent(this.wrapper, 'wheel', this);
			utils.removeEvent(this.wrapper, 'mousewheel', this);
			utils.removeEvent(this.wrapper, 'DOMMouseScroll', this);
		});
	},

	_wheel: function (e) {
		if ( !this.enabled ) {
			return;
		}

		e.preventDefault();
		e.stopPropagation();

		var wheelDeltaX, wheelDeltaY,
			newX, newY,
			that = this;

		if ( this.wheelTimeout === undefined ) {
			that._execEvent('scrollStart');
		}

		// Execute the scrollEnd event after 400ms the wheel stopped scrolling
		clearTimeout(this.wheelTimeout);
		this.wheelTimeout = setTimeout(function () {
			that._execEvent('scrollEnd');
			that.wheelTimeout = undefined;
		}, 400);

		if ( 'deltaX' in e ) {
			wheelDeltaX = -e.deltaX;
			wheelDeltaY = -e.deltaY;
		} else if ( 'wheelDeltaX' in e ) {
			wheelDeltaX = e.wheelDeltaX / 120 * this.options.mouseWheelSpeed;
			wheelDeltaY = e.wheelDeltaY / 120 * this.options.mouseWheelSpeed;
		} else if ( 'wheelDelta' in e ) {
			wheelDeltaX = wheelDeltaY = e.wheelDelta / 120 * this.options.mouseWheelSpeed;
		} else if ( 'detail' in e ) {
			wheelDeltaX = wheelDeltaY = -e.detail / 3 * this.options.mouseWheelSpeed;
		} else {
			return;
		}

		wheelDeltaX *= this.options.invertWheelDirection;
		wheelDeltaY *= this.options.invertWheelDirection;

		if ( !this.hasVerticalScroll ) {
			wheelDeltaX = wheelDeltaY;
			wheelDeltaY = 0;
		}

		if ( this.options.snap ) {
			newX = this.currentPage.pageX;
			newY = this.currentPage.pageY;

			if ( wheelDeltaX > 0 ) {
				newX--;
			} else if ( wheelDeltaX < 0 ) {
				newX++;
			}

			if ( wheelDeltaY > 0 ) {
				newY--;
			} else if ( wheelDeltaY < 0 ) {
				newY++;
			}

			this.goToPage(newX, newY);

			return;
		}

		newX = this.x + Math.round(this.hasHorizontalScroll ? wheelDeltaX : 0);
		newY = this.y + Math.round(this.hasVerticalScroll ? wheelDeltaY : 0);

		if ( newX > 0 ) {
			newX = 0;
		} else if ( newX < this.maxScrollX ) {
			newX = this.maxScrollX;
		}

		if ( newY > 0 ) {
			newY = 0;
		} else if ( newY < this.maxScrollY ) {
			newY = this.maxScrollY;
		}

		this.scrollTo(newX, newY, 0);

// INSERT POINT: _wheel
	},

	_initSnap: function () {
		this.currentPage = {};

		if ( typeof this.options.snap == 'string' ) {
			this.options.snap = this.scroller.querySelectorAll(this.options.snap);
		}

		this.on('refresh', function () {
			var i = 0, l,
				m = 0, n,
				cx, cy,
				x = 0, y,
				stepX = this.options.snapStepX || this.wrapperWidth,
				stepY = this.options.snapStepY || this.wrapperHeight,
				el;

			this.pages = [];

			if ( !this.wrapperWidth || !this.wrapperHeight || !this.scrollerWidth || !this.scrollerHeight ) {
				return;
			}

			if ( this.options.snap === true ) {
				cx = Math.round( stepX / 2 );
				cy = Math.round( stepY / 2 );

				while ( x > -this.scrollerWidth ) {
					this.pages[i] = [];
					l = 0;
					y = 0;

					while ( y > -this.scrollerHeight ) {
						this.pages[i][l] = {
							x: Math.max(x, this.maxScrollX),
							y: Math.max(y, this.maxScrollY),
							width: stepX,
							height: stepY,
							cx: x - cx,
							cy: y - cy
						};

						y -= stepY;
						l++;
					}

					x -= stepX;
					i++;
				}
			} else {
				el = this.options.snap;
				l = el.length;
				n = -1;

				for ( ; i < l; i++ ) {
					if ( i === 0 || el[i].offsetLeft <= el[i-1].offsetLeft ) {
						m = 0;
						n++;
					}

					if ( !this.pages[m] ) {
						this.pages[m] = [];
					}

					x = Math.max(-el[i].offsetLeft, this.maxScrollX);
					y = Math.max(-el[i].offsetTop, this.maxScrollY);
					cx = x - Math.round(el[i].offsetWidth / 2);
					cy = y - Math.round(el[i].offsetHeight / 2);

					this.pages[m][n] = {
						x: x,
						y: y,
						width: el[i].offsetWidth,
						height: el[i].offsetHeight,
						cx: cx,
						cy: cy
					};

					if ( x > this.maxScrollX ) {
						m++;
					}
				}
			}

			this.goToPage(this.currentPage.pageX || 0, this.currentPage.pageY || 0, 0);

			// Update snap threshold if needed
			if ( this.options.snapThreshold % 1 === 0 ) {
				this.snapThresholdX = this.options.snapThreshold;
				this.snapThresholdY = this.options.snapThreshold;
			} else {
				this.snapThresholdX = Math.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].width * this.options.snapThreshold);
				this.snapThresholdY = Math.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].height * this.options.snapThreshold);
			}
		});

		this.on('flick', function () {
			var time = this.options.snapSpeed || Math.max(
					Math.max(
						Math.min(Math.abs(this.x - this.startX), 1000),
						Math.min(Math.abs(this.y - this.startY), 1000)
					), 300);

			this.goToPage(
				this.currentPage.pageX + this.directionX,
				this.currentPage.pageY + this.directionY,
				time
			);
		});
	},

	_nearestSnap: function (x, y) {
		if ( !this.pages.length ) {
			return { x: 0, y: 0, pageX: 0, pageY: 0 };
		}

		var i = 0,
			l = this.pages.length,
			m = 0;

		// Check if we exceeded the snap threshold
		if ( Math.abs(x - this.absStartX) < this.snapThresholdX &&
			Math.abs(y - this.absStartY) < this.snapThresholdY ) {
			return this.currentPage;
		}

		if ( x > 0 ) {
			x = 0;
		} else if ( x < this.maxScrollX ) {
			x = this.maxScrollX;
		}

		if ( y > 0 ) {
			y = 0;
		} else if ( y < this.maxScrollY ) {
			y = this.maxScrollY;
		}

		for ( ; i < l; i++ ) {
			if ( x >= this.pages[i][0].cx ) {
				x = this.pages[i][0].x;
				break;
			}
		}

		l = this.pages[i].length;

		for ( ; m < l; m++ ) {
			if ( y >= this.pages[0][m].cy ) {
				y = this.pages[0][m].y;
				break;
			}
		}

		if ( i == this.currentPage.pageX ) {
			i += this.directionX;

			if ( i < 0 ) {
				i = 0;
			} else if ( i >= this.pages.length ) {
				i = this.pages.length - 1;
			}

			x = this.pages[i][0].x;
		}

		if ( m == this.currentPage.pageY ) {
			m += this.directionY;

			if ( m < 0 ) {
				m = 0;
			} else if ( m >= this.pages[0].length ) {
				m = this.pages[0].length - 1;
			}

			y = this.pages[0][m].y;
		}

		return {
			x: x,
			y: y,
			pageX: i,
			pageY: m
		};
	},

	goToPage: function (x, y, time, easing) {
		easing = easing || this.options.bounceEasing;

		if ( x >= this.pages.length ) {
			x = this.pages.length - 1;
		} else if ( x < 0 ) {
			x = 0;
		}

		if ( y >= this.pages[x].length ) {
			y = this.pages[x].length - 1;
		} else if ( y < 0 ) {
			y = 0;
		}

		var posX = this.pages[x][y].x,
			posY = this.pages[x][y].y;

		time = time === undefined ? this.options.snapSpeed || Math.max(
			Math.max(
				Math.min(Math.abs(posX - this.x), 1000),
				Math.min(Math.abs(posY - this.y), 1000)
			), 300) : time;

		this.currentPage = {
			x: posX,
			y: posY,
			pageX: x,
			pageY: y
		};

		this.scrollTo(posX, posY, time, easing);
	},

	next: function (time, easing) {
		var x = this.currentPage.pageX,
			y = this.currentPage.pageY;

		x++;

		if ( x >= this.pages.length && this.hasVerticalScroll ) {
			x = 0;
			y++;
		}

		this.goToPage(x, y, time, easing);
	},

	prev: function (time, easing) {
		var x = this.currentPage.pageX,
			y = this.currentPage.pageY;

		x--;

		if ( x < 0 && this.hasVerticalScroll ) {
			x = 0;
			y--;
		}

		this.goToPage(x, y, time, easing);
	},

	_initKeys: function (e) {
		// default key bindings
		var keys = {
			pageUp: 33,
			pageDown: 34,
			end: 35,
			home: 36,
			left: 37,
			up: 38,
			right: 39,
			down: 40
		};
		var i;

		// if you give me characters I give you keycode
		if ( typeof this.options.keyBindings == 'object' ) {
			for ( i in this.options.keyBindings ) {
				if ( typeof this.options.keyBindings[i] == 'string' ) {
					this.options.keyBindings[i] = this.options.keyBindings[i].toUpperCase().charCodeAt(0);
				}
			}
		} else {
			this.options.keyBindings = {};
		}

		for ( i in keys ) {
			this.options.keyBindings[i] = this.options.keyBindings[i] || keys[i];
		}

		utils.addEvent(window, 'keydown', this);

		this.on('destroy', function () {
			utils.removeEvent(window, 'keydown', this);
		});
	},

	_key: function (e) {
		if ( !this.enabled ) {
			return;
		}

		var snap = this.options.snap,	// we are using this alot, better to cache it
			newX = snap ? this.currentPage.pageX : this.x,
			newY = snap ? this.currentPage.pageY : this.y,
			now = utils.getTime(),
			prevTime = this.keyTime || 0,
			acceleration = 0.250,
			pos;

		if ( this.options.useTransition && this.isInTransition ) {
			pos = this.getComputedPosition();

			this._translate(Math.round(pos.x), Math.round(pos.y));
			this.isInTransition = false;
		}

		this.keyAcceleration = now - prevTime < 200 ? Math.min(this.keyAcceleration + acceleration, 50) : 0;

		switch ( e.keyCode ) {
			case this.options.keyBindings.pageUp:
				if ( this.hasHorizontalScroll && !this.hasVerticalScroll ) {
					newX += snap ? 1 : this.wrapperWidth;
				} else {
					newY += snap ? 1 : this.wrapperHeight;
				}
				break;
			case this.options.keyBindings.pageDown:
				if ( this.hasHorizontalScroll && !this.hasVerticalScroll ) {
					newX -= snap ? 1 : this.wrapperWidth;
				} else {
					newY -= snap ? 1 : this.wrapperHeight;
				}
				break;
			case this.options.keyBindings.end:
				newX = snap ? this.pages.length-1 : this.maxScrollX;
				newY = snap ? this.pages[0].length-1 : this.maxScrollY;
				break;
			case this.options.keyBindings.home:
				newX = 0;
				newY = 0;
				break;
			case this.options.keyBindings.left:
				newX += snap ? -1 : 5 + this.keyAcceleration>>0;
				break;
			case this.options.keyBindings.up:
				newY += snap ? 1 : 5 + this.keyAcceleration>>0;
				break;
			case this.options.keyBindings.right:
				newX -= snap ? -1 : 5 + this.keyAcceleration>>0;
				break;
			case this.options.keyBindings.down:
				newY -= snap ? 1 : 5 + this.keyAcceleration>>0;
				break;
			default:
				return;
		}

		if ( snap ) {
			this.goToPage(newX, newY);
			return;
		}

		if ( newX > 0 ) {
			newX = 0;
			this.keyAcceleration = 0;
		} else if ( newX < this.maxScrollX ) {
			newX = this.maxScrollX;
			this.keyAcceleration = 0;
		}

		if ( newY > 0 ) {
			newY = 0;
			this.keyAcceleration = 0;
		} else if ( newY < this.maxScrollY ) {
			newY = this.maxScrollY;
			this.keyAcceleration = 0;
		}

		this.scrollTo(newX, newY, 0);

		this.keyTime = now;
	},

	_animate: function (destX, destY, duration, easingFn) {
		var that = this,
			startX = this.x,
			startY = this.y,
			startTime = utils.getTime(),
			destTime = startTime + duration;

		function step () {
			var now = utils.getTime(),
				newX, newY,
				easing;

			if ( now >= destTime ) {
				that.isAnimating = false;
				that._translate(destX, destY);

				if ( !that.resetPosition(that.options.bounceTime) ) {
					that._execEvent('scrollEnd');
				}

				return;
			}

			now = ( now - startTime ) / duration;
			easing = easingFn(now);
			newX = ( destX - startX ) * easing + startX;
			newY = ( destY - startY ) * easing + startY;
			that._translate(newX, newY);

			if ( that.isAnimating ) {
				rAF(step);
			}
		}

		this.isAnimating = true;
		step();
	},
	handleEvent: function (e) {
		switch ( e.type ) {
			case 'touchstart':
			case 'MSPointerDown':
			case 'mousedown':
				this._start(e);
				break;
			case 'touchmove':
			case 'MSPointerMove':
			case 'mousemove':
				this._move(e);
				break;
			case 'touchend':
			case 'MSPointerUp':
			case 'mouseup':
			case 'touchcancel':
			case 'MSPointerCancel':
			case 'mousecancel':
				this._end(e);
				break;
			case 'orientationchange':
			case 'resize':
				this._resize();
				break;
			case 'transitionend':
			case 'webkitTransitionEnd':
			case 'oTransitionEnd':
			case 'MSTransitionEnd':
				this._transitionEnd(e);
				break;
			case 'wheel':
			case 'DOMMouseScroll':
			case 'mousewheel':
				this._wheel(e);
				break;
			case 'keydown':
				this._key(e);
				break;
			case 'click':
				if ( !e._constructed ) {
					e.preventDefault();
					e.stopPropagation();
				}
				break;
		}
	}
};
function createDefaultScrollbar (direction, interactive, type) {
	var scrollbar = document.createElement('div'),
		indicator = document.createElement('div');

	if ( type === true ) {
		scrollbar.style.cssText = 'position:absolute;z-index:9999';
		indicator.style.cssText = '-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:absolute;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);border-radius:3px';
	}

	indicator.className = 'iScrollIndicator';

	if ( direction == 'h' ) {
		if ( type === true ) {
			scrollbar.style.cssText += ';height:7px;left:2px;right:2px;bottom:0';
			indicator.style.height = '100%';
		}
		scrollbar.className = 'iScrollHorizontalScrollbar';
	} else {
		if ( type === true ) {
			scrollbar.style.cssText += ';width:7px;bottom:2px;top:2px;right:1px';
			indicator.style.width = '100%';
		}
		scrollbar.className = 'iScrollVerticalScrollbar';
	}

	scrollbar.style.cssText += ';overflow:hidden';

	if ( !interactive ) {
		scrollbar.style.pointerEvents = 'none';
	}

	scrollbar.appendChild(indicator);

	return scrollbar;
}

function Indicator (scroller, options) {
	this.wrapper = typeof options.el == 'string' ? document.querySelector(options.el) : options.el;
	this.wrapperStyle = this.wrapper.style;
	this.indicator = this.wrapper.children[0];
	this.indicatorStyle = this.indicator.style;
	this.scroller = scroller;

	this.options = {
		listenX: true,
		listenY: true,
		interactive: false,
		resize: true,
		defaultScrollbars: false,
		shrink: false,
		fade: false,
		speedRatioX: 0,
		speedRatioY: 0
	};

	for ( var i in options ) {
		this.options[i] = options[i];
	}

	this.sizeRatioX = 1;
	this.sizeRatioY = 1;
	this.maxPosX = 0;
	this.maxPosY = 0;

	if ( this.options.interactive ) {
		if ( !this.options.disableTouch ) {
			utils.addEvent(this.indicator, 'touchstart', this);
			utils.addEvent(window, 'touchend', this);
		}
		if ( !this.options.disablePointer ) {
			utils.addEvent(this.indicator, 'MSPointerDown', this);
			utils.addEvent(window, 'MSPointerUp', this);
		}
		if ( !this.options.disableMouse ) {
			utils.addEvent(this.indicator, 'mousedown', this);
			utils.addEvent(window, 'mouseup', this);
		}
	}

	if ( this.options.fade ) {
		this.wrapperStyle[utils.style.transform] = this.scroller.translateZ;
		this.wrapperStyle[utils.style.transitionDuration] = utils.isBadAndroid ? '0.001s' : '0ms';
		this.wrapperStyle.opacity = '0';
	}
}

Indicator.prototype = {
	handleEvent: function (e) {
		switch ( e.type ) {
			case 'touchstart':
			case 'MSPointerDown':
			case 'mousedown':
				this._start(e);
				break;
			case 'touchmove':
			case 'MSPointerMove':
			case 'mousemove':
				this._move(e);
				break;
			case 'touchend':
			case 'MSPointerUp':
			case 'mouseup':
			case 'touchcancel':
			case 'MSPointerCancel':
			case 'mousecancel':
				this._end(e);
				break;
		}
	},

	destroy: function () {
		if ( this.options.interactive ) {
			utils.removeEvent(this.indicator, 'touchstart', this);
			utils.removeEvent(this.indicator, 'MSPointerDown', this);
			utils.removeEvent(this.indicator, 'mousedown', this);

			utils.removeEvent(window, 'touchmove', this);
			utils.removeEvent(window, 'MSPointerMove', this);
			utils.removeEvent(window, 'mousemove', this);

			utils.removeEvent(window, 'touchend', this);
			utils.removeEvent(window, 'MSPointerUp', this);
			utils.removeEvent(window, 'mouseup', this);
		}

		if ( this.options.defaultScrollbars ) {
			this.wrapper.parentNode.removeChild(this.wrapper);
		}
	},

	_start: function (e) {
		var point = e.touches ? e.touches[0] : e;

		e.preventDefault();
		e.stopPropagation();

		this.transitionTime();

		this.initiated = true;
		this.moved = false;
		this.lastPointX	= point.pageX;
		this.lastPointY	= point.pageY;

		this.startTime	= utils.getTime();

		if ( !this.options.disableTouch ) {
			utils.addEvent(window, 'touchmove', this);
		}
		if ( !this.options.disablePointer ) {
			utils.addEvent(window, 'MSPointerMove', this);
		}
		if ( !this.options.disableMouse ) {
			utils.addEvent(window, 'mousemove', this);
		}

		this.scroller._execEvent('beforeScrollStart');
	},

	_move: function (e) {
		var point = e.touches ? e.touches[0] : e,
			deltaX, deltaY,
			newX, newY,
			timestamp = utils.getTime();

		if ( !this.moved ) {
			this.scroller._execEvent('scrollStart');
		}

		this.moved = true;

		deltaX = point.pageX - this.lastPointX;
		this.lastPointX = point.pageX;

		deltaY = point.pageY - this.lastPointY;
		this.lastPointY = point.pageY;

		newX = this.x + deltaX;
		newY = this.y + deltaY;

		this._pos(newX, newY);

// INSERT POINT: indicator._move

		e.preventDefault();
		e.stopPropagation();
	},

	_end: function (e) {
		if ( !this.initiated ) {
			return;
		}

		this.initiated = false;

		e.preventDefault();
		e.stopPropagation();

		utils.removeEvent(window, 'touchmove', this);
		utils.removeEvent(window, 'MSPointerMove', this);
		utils.removeEvent(window, 'mousemove', this);

		if ( this.scroller.options.snap ) {
			var snap = this.scroller._nearestSnap(this.scroller.x, this.scroller.y);

			var time = this.options.snapSpeed || Math.max(
					Math.max(
						Math.min(Math.abs(this.scroller.x - snap.x), 1000),
						Math.min(Math.abs(this.scroller.y - snap.y), 1000)
					), 300);

			if ( this.scroller.x != snap.x || this.scroller.y != snap.y ) {
				this.scroller.directionX = 0;
				this.scroller.directionY = 0;
				this.scroller.currentPage = snap;
				this.scroller.scrollTo(snap.x, snap.y, time, this.scroller.options.bounceEasing);
			}
		}

		if ( this.moved ) {
			this.scroller._execEvent('scrollEnd');
		}
	},

	transitionTime: function (time) {
		time = time || 0;
		this.indicatorStyle[utils.style.transitionDuration] = time + 'ms';

		if ( !time && utils.isBadAndroid ) {
			this.indicatorStyle[utils.style.transitionDuration] = '0.001s';
		}
	},

	transitionTimingFunction: function (easing) {
		this.indicatorStyle[utils.style.transitionTimingFunction] = easing;
	},

	refresh: function () {
		this.transitionTime();

		if ( this.options.listenX && !this.options.listenY ) {
			this.indicatorStyle.display = this.scroller.hasHorizontalScroll ? 'block' : 'none';
		} else if ( this.options.listenY && !this.options.listenX ) {
			this.indicatorStyle.display = this.scroller.hasVerticalScroll ? 'block' : 'none';
		} else {
			this.indicatorStyle.display = this.scroller.hasHorizontalScroll || this.scroller.hasVerticalScroll ? 'block' : 'none';
		}

		if ( this.scroller.hasHorizontalScroll && this.scroller.hasVerticalScroll ) {
			utils.addClass(this.wrapper, 'iScrollBothScrollbars');
			utils.removeClass(this.wrapper, 'iScrollLoneScrollbar');

			if ( this.options.defaultScrollbars && this.options.customStyle ) {
				if ( this.options.listenX ) {
					this.wrapper.style.right = '8px';
				} else {
					this.wrapper.style.bottom = '8px';
				}
			}
		} else {
			utils.removeClass(this.wrapper, 'iScrollBothScrollbars');
			utils.addClass(this.wrapper, 'iScrollLoneScrollbar');

			if ( this.options.defaultScrollbars && this.options.customStyle ) {
				if ( this.options.listenX ) {
					this.wrapper.style.right = '2px';
				} else {
					this.wrapper.style.bottom = '2px';
				}
			}
		}

		var r = this.wrapper.offsetHeight;	// force refresh

		if ( this.options.listenX ) {
			this.wrapperWidth = this.wrapper.clientWidth;
			if ( this.options.resize ) {
				this.indicatorWidth = Math.max(Math.round(this.wrapperWidth * this.wrapperWidth / (this.scroller.scrollerWidth || this.wrapperWidth || 1)), 8);
				this.indicatorStyle.width = this.indicatorWidth + 'px';
			} else {
				this.indicatorWidth = this.indicator.clientWidth;
			}

			this.maxPosX = this.wrapperWidth - this.indicatorWidth;

			if ( this.options.shrink == 'clip' ) {
				this.minBoundaryX = -this.indicatorWidth + 8;
				this.maxBoundaryX = this.wrapperWidth - 8;
			} else {
				this.minBoundaryX = 0;
				this.maxBoundaryX = this.maxPosX;
			}

			this.sizeRatioX = this.options.speedRatioX || (this.scroller.maxScrollX && (this.maxPosX / this.scroller.maxScrollX));	
		}

		if ( this.options.listenY ) {
			this.wrapperHeight = this.wrapper.clientHeight;
			if ( this.options.resize ) {
				this.indicatorHeight = Math.max(Math.round(this.wrapperHeight * this.wrapperHeight / (this.scroller.scrollerHeight || this.wrapperHeight || 1)), 8);
				this.indicatorStyle.height = this.indicatorHeight + 'px';
			} else {
				this.indicatorHeight = this.indicator.clientHeight;
			}

			this.maxPosY = this.wrapperHeight - this.indicatorHeight;

			if ( this.options.shrink == 'clip' ) {
				this.minBoundaryY = -this.indicatorHeight + 8;
				this.maxBoundaryY = this.wrapperHeight - 8;
			} else {
				this.minBoundaryY = 0;
				this.maxBoundaryY = this.maxPosY;
			}

			this.maxPosY = this.wrapperHeight - this.indicatorHeight;
			this.sizeRatioY = this.options.speedRatioY || (this.scroller.maxScrollY && (this.maxPosY / this.scroller.maxScrollY));
		}

		this.updatePosition();
	},

	updatePosition: function () {
		var x = this.options.listenX && Math.round(this.sizeRatioX * this.scroller.x) || 0,
			y = this.options.listenY && Math.round(this.sizeRatioY * this.scroller.y) || 0;

		if ( !this.options.ignoreBoundaries ) {
			if ( x < this.minBoundaryX ) {
				if ( this.options.shrink == 'scale' ) {
					this.width = Math.max(this.indicatorWidth + x, 8);
					this.indicatorStyle.width = this.width + 'px';
				}
				x = this.minBoundaryX;
			} else if ( x > this.maxBoundaryX ) {
				if ( this.options.shrink == 'scale' ) {
					this.width = Math.max(this.indicatorWidth - (x - this.maxPosX), 8);
					this.indicatorStyle.width = this.width + 'px';
					x = this.maxPosX + this.indicatorWidth - this.width;
				} else {
					x = this.maxBoundaryX;
				}
			} else if ( this.options.shrink == 'scale' && this.width != this.indicatorWidth ) {
				this.width = this.indicatorWidth;
				this.indicatorStyle.width = this.width + 'px';
			}

			if ( y < this.minBoundaryY ) {
				if ( this.options.shrink == 'scale' ) {
					this.height = Math.max(this.indicatorHeight + y * 3, 8);
					this.indicatorStyle.height = this.height + 'px';
				}
				y = this.minBoundaryY;
			} else if ( y > this.maxBoundaryY ) {
				if ( this.options.shrink == 'scale' ) {
					this.height = Math.max(this.indicatorHeight - (y - this.maxPosY) * 3, 8);
					this.indicatorStyle.height = this.height + 'px';
					y = this.maxPosY + this.indicatorHeight - this.height;
				} else {
					y = this.maxBoundaryY;
				}
			} else if ( this.options.shrink == 'scale' && this.height != this.indicatorHeight ) {
				this.height = this.indicatorHeight;
				this.indicatorStyle.height = this.height + 'px';
			}
		}

		this.x = x;
		this.y = y;

		if ( this.scroller.options.useTransform ) {
			this.indicatorStyle[utils.style.transform] = 'translate(' + x + 'px,' + y + 'px)' + this.scroller.translateZ;
		} else {
			this.indicatorStyle.left = x + 'px';
			this.indicatorStyle.top = y + 'px';
		}
	},

	_pos: function (x, y) {
		if ( x < 0 ) {
			x = 0;
		} else if ( x > this.maxPosX ) {
			x = this.maxPosX;
		}

		if ( y < 0 ) {
			y = 0;
		} else if ( y > this.maxPosY ) {
			y = this.maxPosY;
		}

		x = this.options.listenX ? Math.round(x / this.sizeRatioX) : this.scroller.x;
		y = this.options.listenY ? Math.round(y / this.sizeRatioY) : this.scroller.y;

		this.scroller.scrollTo(x, y);
	},

	fade: function (val, hold) {
		if ( hold && !this.visible ) {
			return;
		}

		clearTimeout(this.fadeTimeout);
		this.fadeTimeout = null;

		var time = val ? 250 : 500,
			delay = val ? 0 : 300;

		val = val ? '1' : '0';

		this.wrapperStyle[utils.style.transitionDuration] = time + 'ms';

		this.fadeTimeout = setTimeout((function (val) {
			this.wrapperStyle.opacity = val;
			this.visible = +val;
		}).bind(this, val), delay);
	}
};

IScroll.utils = utils;

if ( typeof module != 'undefined' && module.exports ) {
	module.exports = IScroll;
} else {
	window.IScroll = IScroll;
}

})(window, document, Math);;
/* (function ($) {
  $.app = $.app || {};
  var isIphone = /i(Phone|P(o|a)d)/.test(navigator.userAgent) && typeof window.ontouchstart !== 'undefined',
    target, movecount, startX, startY;

  function parentIfText(node) {
    return 'tagName' in node ? node : node.parentNode;
  }

  function clear() {
    movecount = 0;
    startX = 0;
    startY = 0;
  }

  $(document).ready(function () {
    $(document).bind('touchstart', function (e) {
      clear();
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
    }).bind('touchmove', function (e) {
      movecount += 1;
    }).bind('touchend', function (e) {
      var endX = e.changedTouches[0].clientX,
        endY = e.changedTouches[0].clientY,
        delta = Math.sqrt(Math.pow(startX - endX, 2) + Math.pow(startY - endY, 2));

      if (((isIphone && !movecount) || (!isIphone && movecount <= 2 && Math.abs(delta) < 15))) {
        $(parentIfText(e.changedTouches[0].target)).trigger('tap');
      }
      clear();
    }).bind('touchcancel', function () {
      clear();
    });
  });
})(Zepto); */

/*swipe*/
(function ($) {
  var isIphone = /i(Phone|P(o|a)d)/.test(navigator.userAgent) && typeof window.ontouchstart !== 'undefined',
    isAndroid = /Android/.test(navigator.userAgent),
    has3d = isIphone && 'WebKitCSSMatrix' in window && 'm11' in new WebKitCSSMatrix(),
    translateStart = has3d ? 'translate3d(' : 'translate(',
    translateEnd = has3d ? ',0)' : ')';

  function parentIfText(node) {
    return 'tagName' in node ? node : node.parentNode;
  }


  $.fn.swipe = function (opt) {
    if (this.length == 0 || this.get(0).isInit == true) return false;
    opt = $.extend({
      scroll: true,
      loop: true
    }, opt);

    var me = this,
      body = $(document.body),
      items, width = opt.width || me.children()[0].scrollWidth,
      trans = opt.loop ? opt.initPos - width : opt.initPos,
      startPos, startTime, curPos, lastPos, gap = opt.gap,
      last = 0, isPointerType, movecount = 0,
      startY, isX, am, transitionEndEvent;

    function init() {
      var item = me.children()[0];
      opt.loop && me.append(item.cloneNode(true)).append(item.cloneNode(true));
      items = me.children().css({
        'display': 'inline-block',
        'visibility': 'visible'
      });
      transitionEndEvent = 'webkitTransitionEnd'
      if ($.browser.firefox) {
		transitionEndEvent = 'transitionend';
//		transitionEndEvent = 'webkitTransitionEnd';
      }
      else if ($.browser.ie) {
        transitionEndEvent = 'MSTransitionEnd';
      }
      translate();
      setTimeout(function () {
        items.css({
          "-webkit-perspective": 1000,
          "perspective": 1000,
          "-webkit-backface-visibility": 'hidden',
          "backface-visibility": 'hidden'
        })
      }, 100);
      if (!$.browser.ie) {
          me.on('touchstart', ontouchstart).on("touchmove", ontouchmove).on("touchend", ontouchend);
      } else {
          isPointerType = true;
          /* $('html').css('-ms-touch-action', 'none'); */
          me.on('MSPointerDown', ontouchstart).on('MSPointerMove', ontouchmove).on('MSPointerOut', ontouchend);
      }
      automove();
      me.get(0).isInit = true;
    }

    function translate() {
      items.css("-webkit-transform", translateStart + trans + "px,0" + translateEnd);
      items.css("-moz-transform", translateStart + trans + "px,0" + translateEnd);
      items.css("-ms-transform", translateStart + trans + "px,0" + translateEnd);
      items.css("transform", translateStart + trans + "px,0" + translateEnd);
    }

    function onchange() {
      if (opt.onchange) {
        cur = parseInt((trans - opt.initPos) / gap);
        if (cur !== last) {
          opt.onchange.call(me, -cur);
          last = cur;
        }
      }
    }

    function ontouchstart(e) {
      movecount = 0;
      firstTouch = isPointerType ? e : e.touches[0]
      startPos = lastPos = firstTouch.clientX;
      startY = firstTouch.clientY;
      startTime = e.timeStamp;
      clearInterval(am);
    }

    function moveHandler(e) {
      movecount += 1;
      var oriTrans = trans;
      trans = trans + curPos - lastPos;
      lastPos = curPos;
      if (opt.loop) {
        if (-trans < width) {
          trans -= width;
        }
        if (-trans > width * 2) {
          trans += width;
        }
      } else {
        if (-trans < -opt.initPos || -trans > opt.endPos) {
          trans = oriTrans;
        }
      }
      translate();
      onchange();
      
      if (e && e.preventDefault) {
        e.preventDefault();
      }
      else {
      	return false;
      }
    }

    function ontouchmove(e) {
      firstTouch = isPointerType ? e : e.touches[0]
      
      curPos = firstTouch.clientX;
      var deltaX = curPos - lastPos;
      if (movecount == 0) {
        isX = (Math.abs(deltaX) > Math.abs(firstTouch.clientY - startY));
        isX && moveHandler(e);
      } else if (isX) {
        moveHandler(e);
      }
      movecount++;
    }

    function ontouchend(e) {
      var ot = Math.abs((trans - opt.initPos) % gap),
        flag = trans > 0 ? 1 : -1,
        delta = lastPos - startPos,
        oriTrans = trans;

      if ((Math.abs(delta) / (e.timeStamp - startTime)) > 0.1) {
        trans = delta < 0 ? (trans + flag * (gap - ot)) : (trans - flag * ot);
      } else {
        trans = ot >= gap / 2 ? (trans + flag * (gap - ot)) : (trans - flag * ot);
      }

      items.addClass('swipe-trans');
      if (-trans < -opt.initPos || -trans > opt.endPos) {
        trans = oriTrans;
      }
      translate();
      items.one(transitionEndEvent, function () {
        items.removeClass('swipe-trans');
        onchange();
      });

      clearInterval(am);
      automove();
    }

    function move(dir) {
      trans += dir * gap;
      items.addClass('swipe-trans');
      translate();
      items.one(transitionEndEvent, function () {
        items.removeClass('swipe-trans');
        if (-trans < width) {
          trans -= width;
        }
        if (-trans > width * 2) {
          trans += width;
        }
        translate();
        onchange();
      });
    }

    function automove() {
      if (opt.automove) {
        am = setInterval(function () {
          move(-1);
        }, 3000)
      }
    }
    /* $.app.swipe = {
      'start': function () {
        clearInterval(am);
        automove();
      },
      'stop': function () {
        clearInterval(am);
      }
    }; */
    init();
    me.reinitswipe = function (opt) {
        gap = opt.gap || gap;
        width = opt.width || width;
        trans = (opt.initPos || trans) - width;
        translate();
    };
    me.start = function () {
        clearInterval(am);
        automove();
    };
    me.stop = function () {
        clearInterval(am);
    };
    return me;
  }
})(Zepto);

/*lazyload*/
(function ($) {
  /*
   * opt:
   * {
   *     filter: function(container) or string. 用于筛选需要延迟加载的元素
   *     checker: function(item) 检查元素是否符合加载条件
   *     worker: function(item) 用于对元素进行加载
   * }
   */
  $.fn.lazydo = function (opt) {
    var me = this,
      items = null,
      oriItems = [],
      stepStart = false;
    opt = $.extend({
      checker: function (item) {
        var offset = item.getBoundingClientRect();
        return offset.top > 0 && offset.top < window.innerHeight;
      },
      repeat: false,
      live: false,
    }, opt);

    function step() {
      opt.live && (items = ($.isFunction(opt.filter) ? opt.filter(me) : me.find(opt.filter)));
      if (items.size() > 0) {
        var items_new = [];
        items.each(function (idx, item) {
          if (opt.checker(item)) {
            opt.worker($(item));
          } else {
            items_new.push(item);
          }
        });
        !opt.repeat && (items = $(items_new));
      }
    };

    function init() {
      items = ($.isFunction(opt.filter) ? opt.filter(me) : me.find(opt.filter)).not(oriItems).add(items);
      oriItems = items;
      $(window).bind('scroll', step);
      step();
    }

    init();
    me.domore = function () {
      init();
      return me;
    }
    return me;
  }

  $.fn.imglazyload = function (opt) {
    var me = this;
    me.lazydo({
      filter: opt.filter,
      worker: function (item) {
        var src = item.data('src');
        if (src) {
          item.attr('src', src);
        }
        return true;
      },
      checker: opt.checker,
      live: opt.live
    });

    me.redo = function () {
      return me.domore();
    }
    return me;
  }

  $.fn.datalazyload = function (opt) {
    var me = this;

    return me.lazydo({
      filter: function () {
        return me;
      },
      worker: opt.loader,
      repeat: opt.repeat
    });
  }
})(Zepto);
(function ($) {
  /* $.fn.ctap = (function () {
    var has_webkit = navigator.userAgent.match(/webkit/ig)
    if (has_webkit) {
      return function (selector, data, callback) {
        return this.on('click', selector, data, callback)
      }
    } else {
      return function (selector, data, callback) {
        return this.on('click', selector, data, callback)
      }
    }
  })(); */
  $.fn.ctap = function (selector, data, callback) {
    return this.on('click', selector, data, callback);
  }
  $.fn.ctrigger = function (e, args) {
      if (e == 'click' || e == 'tap') {
          window._zeptoCompatibleTrigger = true;
          return this.trigger('click', args).trigger('tap', args);
      }
      else {
          return this.trigger(e, args);
      }
  }
})(Zepto);;
/*!
** Unobtrusive Ajax support library for jQuery
** Copyright (C) Microsoft Corporation. All rights reserved.
*/

/*jslint white: true, browser: true, onevar: true, undef: true, nomen: true, eqeqeq: true, plusplus: true, bitwise: true, regexp: true, newcap: true, immed: true, strict: false */
/*global window: false, jQuery: false */

(function ($) {
    var data_click = "unobtrusiveAjaxClick",
        data_validation = "unobtrusiveValidation";

    function getFunction(code, argNames) {
        var fn = window, parts = (code || "").split(".");
        while (fn && parts.length) {
            fn = fn[parts.shift()];
        }
        if (typeof (fn) === "function") {
            return fn;
        }
        argNames.push(code);
        return Function.constructor.apply(null, argNames);
    }

    function isMethodProxySafe(method) {
        return method === "GET" || method === "POST";
    }

    function asyncOnBeforeSend(xhr, method) {
        if (!isMethodProxySafe(method)) {
            xhr.setRequestHeader("X-HTTP-Method-Override", method);
        }
    }

    function asyncOnSuccess(element, data, contentType) {
        var mode;

        if (contentType.indexOf("application/x-javascript") !== -1) {  // jQuery already executes JavaScript for us
            return;
        }

        mode = (element.getAttribute("data-ajax-mode") || "").toUpperCase();
        $(element.getAttribute("data-ajax-update")).each(function (i, update) {
            var top;

            switch (mode) {
            case "BEFORE":
                top = update.firstChild;
                $("<div />").html(data).contents().each(function () {
                    update.insertBefore(this, top);
                });
                break;
            case "AFTER":
                $("<div />").html(data).contents().each(function () {
                    update.appendChild(this);
                });
                break;
            default:
                $(update).html(data);
                break;
            }
        });
    }

    function asyncRequest(element, options) {
        var confirm, loading, method, duration;

        confirm = element.getAttribute("data-ajax-confirm");
        if (confirm && !window.confirm(confirm)) {
            return;
        }

        loading = $(element.getAttribute("data-ajax-loading"));
        duration = element.getAttribute("data-ajax-loading-duration") || 0;

        $.extend(options, {
            type: element.getAttribute("data-ajax-method") || undefined,
            url: element.getAttribute("data-ajax-url") || undefined,
            beforeSend: function (xhr) {
                var result;
                asyncOnBeforeSend(xhr, method);
                result = getFunction(element.getAttribute("data-ajax-begin"), ["xhr"]).apply(this, arguments);
                if (result !== false) {
                    loading.show(duration);
                }
                return result;
            },
            complete: function () {
                loading.hide(duration);
                getFunction(element.getAttribute("data-ajax-complete"), ["xhr", "status"]).apply(this, arguments);
            },
            success: function (data, status, xhr) {
                asyncOnSuccess(element, data, xhr.getResponseHeader("Content-Type") || "text/html");
                getFunction(element.getAttribute("data-ajax-success"), ["data", "status", "xhr"]).apply(this, arguments);
            },
            error: getFunction(element.getAttribute("data-ajax-failure"), ["xhr", "status", "error"])
        });

        options.data.push({ name: "X-Requested-With", value: "XMLHttpRequest" });

        method = options.type.toUpperCase();
        if (!isMethodProxySafe(method)) {
            options.type = "POST";
            options.data.push({ name: "X-HTTP-Method-Override", value: method });
        }

        $.ajax(options);
    }

    function validate(form) {
        var validationInfo = $(form).data(data_validation);
        return !validationInfo || !validationInfo.validate || validationInfo.validate();
    }

    $(document).on("click", "a[data-ajax=true]", function (evt) {
        evt.preventDefault();
        asyncRequest(this, {
            url: this.href,
            type: "GET",
            data: []
        });
    });

    $(document).on("click", "form[data-ajax=true] input[type=image]", function (evt) {
        var name = evt.target.name,
            $target = $(evt.target),
            form = $target.parents("form")[0],
            offset = $target.offset();

        $(form).data(data_click, [
            { name: name + ".x", value: Math.round(evt.pageX - offset.left) },
            { name: name + ".y", value: Math.round(evt.pageY - offset.top) }
        ]);

        setTimeout(function () {
            $(form).removeData(data_click);
        }, 0);
    });

    $(document).on("click", "form[data-ajax=true] [type=submit]", function (evt) {
        var name = evt.target.name,
            form = $(evt.target).parents("form")[0];

        $(form).data(data_click, name ? [{ name: name, value: evt.target.value }] : []);

        setTimeout(function () {
            $(form).data(data_click, null);
            // $(form).removeData(data_click);
        }, 0);
    });

    $(document).on("submit", "form[data-ajax=true]", function (evt) {
        var clickInfo = $(this).data(data_click) || [];
        evt.preventDefault();
        if (!validate(this)) {
            return;
        }
        asyncRequest(this, {
            url: this.action,
            type: this.method || "GET",
            data: clickInfo.concat($(this).serializeArray())
        });
    });
}(this.jQuery || this.Zepto));;
// Zepto.cookie plugin
// 
// Copyright (c) 2010, 2012 
// @author Klaus Hartl (stilbuero.de)
// @author Daniel Lacy (daniellacy.com)
// 
// Dual licensed under the MIT and GPL licenses:
// http://www.opensource.org/licenses/mit-license.php
// http://www.gnu.org/licenses/gpl.html
(function(a){a.extend(a.fn,{cookie:function(b,c,d){var e,f,g,h;if(arguments.length>1&&String(c)!=="[object Object]"){d=a.extend({},d);if(c===null||c===undefined)d.expires=-1;return typeof d.expires=="number"&&(e=d.expires*24*60*60*1e3,f=d.expires=new Date,f.setTime(f.getTime()+e)),c=String(c),document.cookie=[encodeURIComponent(b),"=",d.raw?c:encodeURIComponent(c),d.expires?"; expires="+d.expires.toUTCString():"",d.path?"; path="+d.path:"",d.domain?"; domain="+d.domain:"",d.secure?"; secure":""].join("")}return d=c||{},h=d.raw?function(a){return a}:decodeURIComponent,(g=(new RegExp("(?:^|; )"+encodeURIComponent(b)+"=([^;]*)")).exec(document.cookie))?h(g[1]):null}})})(Zepto);;

/*
" --------------------------------------------------
"   FileName: ndoo.coffee
"       Desc: ndoo.js主结构文件 for mini
"     Author: chenglifu
"    Version: ndoo.js(v0.1b2)
" LastChange: 11/04/2014 15:48
" --------------------------------------------------
 */

/* Notice: 不要修改本文件，本文件由ndoo.coffee自动生成 */
(function($) {
  "use strict";
  var _func, _n, _stor, _vars;
  _n = this;

  /* require module {{{ */

  /**
   * 依赖加载
   *
   * @method
   * @name require
   * @memberof ndoo
   * @param {array} depend 依赖
   * @param {function} callback 回调函数
   * @param {string} type 加载器类型
   * @example // ndoo alias _n
   * var _n = ndoo;
   * _n.require(['lib/jquery-1.11.1.js', 'lib/jquery-mytest.js'], function(a){
   *   a('body').mytest();
   * }, 'seajs');
   */
  _n.require = function(depend, callback, type) {
    if (type.toLowerCase() === 'do') {
      return Do.apply(null, depend.concat(callback));
    } else if (type.toLowerCase() === 'seajs') {
      return seajs.use(depend, callback);
    }
  };

  /* }}} */
  _n._delayRunHandle = function() {
    var fn, i, len, ref;
    if (this._delayArr[0].length) {
      ref = this._delayArr[0];
      for (i = 0, len = ref.length; i < len; i++) {
        fn = ref[i];
        fn[1]();
      }
      if (this._isDebug) {
        this._delayArr[0].length = 0;
      }
    }
    if (this._delayArr[1].length || this._delayArr[2].length) {
      $(function() {
        var fns, j, k, len1, len2;
        fns = _n._delayArr[1];
        for (j = 0, len1 = fns.length; j < len1; j++) {
          fn = fns[j];
          fn[1]();
        }
        fns = _n._delayArr[2];
        for (k = 0, len2 = fns.length; k < len2; k++) {
          fn = fns[k];
          fn[1]();
        }
        if (_n._isDebug) {
          _n._delayArr[1].length = 0;
          fns.length = 0;
        }
      });
    }
    if (this._delayArr[3].length) {
      $(window).bind('load', function() {
        var fns, j, len1;
        fns = _n._delayArr[3];
        for (j = 0, len1 = fns.length; j < len1; j++) {
          fn = fns[j];
          fn[1]();
        }
        if (_n._isDebug) {
          fns.length = 0;
        }
      });
    }
  };

  /* storage module {{{ */
  _n.storage = function(key, value, force, destroy) {
    var data;
    data = _n.storage._data;
    if (value === void 0) {
      return data[key];
    }
    if (destroy) {
      delete data[key];
      return true;
    }
    if (!force && data.hasOwnProperty(key)) {
      return false;
    }
    return data[key] = value;
  };
  _n.storage._data = {};

  /* }}} */

  /* define app package {{{ */
  _n.app = function(name, app) {
    var base;
    (base = _n.app)[name] || (base[name] = {});
    $.extend(_n.app[name], app);
  };

  /* }}} */
  _vars = _n.vars;
  _func = _n.func;
  _stor = _n.storage;
  $.extend(_n, {

    /*自增量 */
    _pk: +new Date(),
    getPK: function() {
      return ++this._pk;
    },

    /*初始化 */
    init: function() {
      var _entry, _stateChange;
      _stateChange = function(state) {
        var call, callback, globalcall, key, pagecall;
        _n.pageId = $('#scriptArea').data('pageId');
        callback = false;
        switch (state) {
          case 'common':
            callback = _stor('pageCommonCall');
            break;
          case 'fetch':
            callback = _stor('pageFetchCall');
            break;
          case 'beforeUnload':
            callback = _stor('pageBeforeUnloadCall');
            break;
          case 'change':
            callback = _stor('pageChangeCall');
            break;
          case 'load':
            callback = _stor('pageLoadCall');
            break;
          case 'restore':
            callback = _stor('pageRestoreCall');
            break;
        }
        if (!callback) {
          return;
        }
        if (callback && callback['_global']) {
          globalcall = callback['_global'];
          for (key in globalcall) {
            call = globalcall[key];
            if (call) {
              call();
            }
          }
        }
        if (callback && callback[_n.pageId]) {
          pagecall = callback[_n.pageId];
          for (key in pagecall) {
            call = pagecall[key];
            if (call) {
              call();
            }
          }
        }
      };
      _entry = function() {

        /*页面标识 */
        var actionId, actionName, controller, controllerId, pageIdMatched, rawParams;
        _n.pageId = $('#scriptArea').data('pageId');
        if (!_n.commonRun) {
          _n.common();
        }
        if (_n.pageId) {
          if (pageIdMatched = _n.pageId.match(/([^\/]+)(?:\/?)([^?#]*)(.*)/)) {
            controllerId = pageIdMatched[1];
            actionId = pageIdMatched[2];
            rawParams = pageIdMatched[3];
          }
          if (controller = _n.app[controllerId]) {
            if (actionId) {
              actionName = actionId.replace(/(\/.)/, function(char) {
                return char.substring(1, 2).toUpperCase();
              });
            } else {
              actionName = '_empty';
            }
            if (typeof controller.init === "function") {
              controller.init();
            }
          }
          if (actionName) {
            if (controller[actionName + 'Before']) {
              controller[actionName + 'Before'](rawParams);
            }
            if (controller[actionName + 'Action']) {
              controller[actionName + 'Action'](rawParams);
            }
            if (controller[actionName + 'After']) {
              controller[actionName + 'After'](rawParams);
            }
          }
        }
        _stateChange('load');
        _n.hook('hideloading');
      };
      if (_func.isUseTurbolinks()) {
        if (!this.inited) {
          this.inited = true;
          if (typeof Turbolinks.enableProgressBar === "function") {
            Turbolinks.enableProgressBar(false);
          }
          _n.hook('commonCall', function() {
            return _stateChange('common');
          });
          $(document).on('page:fetch', function() {
            return _stateChange('fetch');
          });
          $(document).on('page:before-unload', function() {
            return _stateChange('beforeUnload');
          });
          $(document).on('page:load', function() {
            return _entry();
          }).trigger('page:load');
          $(document).on('page:restore', function() {
            return _stateChange('restore');
          });
        }
      } else {
        _n.hook('commonCall', function() {
          return _stateChange('common');
        });
        this.delayRun(this.DELAY_DOM, _entry);

        /*延迟执行DOMLOAD */
        this._delayRunHandle();
      }
    },

    /*公共调用 */
    common: function() {

      /*init tpl */
      _n.hook('addTrack');
      _n.hook('App:Init');
      _n.hook('commonCall');
      this.commonRun = true;
    },
    commonRun: false,

    /*初始化Dialog模板 initTpl */
    initTpl: function() {
      var $code, e, error, text;
      $code = $('#tplCode');
      if ($code.length) {
        text = $code.get(0).text.replace(/^\s*|\s*$/g, '');
        if (text !== '') {
          try {
            $(text).appendTo('#tplArea');
          } catch (error) {
            e = error;
            return false;
          }
        }
        return true;
      }
      return false;
    },

    /* visit接口 */
    visit: function(url) {
      if (_func.isUseTurbolinks()) {
        Turbolinks.visit(url);
      } else {
        location.href = url;
      }
    }
  });

  /*初始化入口 */
  return _n;
}).call(this.N = this.ndoo = this.ndoo || {}, Zepto);
;
(function () {
    this.ndoo = this.ndoo || {}

    /**
    * 名称空间
    *
    * @namespace
    */
    var ndoo = this.N = this.ndoo;

    /**
    * 工具类名称空间
    *
    * @namespace
    */
    ndoo.util = ndoo.util || {}

    /**
     * map
     *
     * @param container
     * @param option
     * @param type
     */
    var map = function (container, option, type) {
        if (type == 'amap' || type == 'google') {
            this.type = type;
            this.mapTool = map[type];
            this.map = this.mapTool.createMap(container, option);
            return this;
        }
        else {
            return {
                error: true,
                msg: "type is unknown"
            }
        }
    }

    /**
     * addIconMarker
     *
     * @param option
     */
    map.prototype.addIconMarker = function (option) {
        return this.mapTool.addIconMarker(this.map, option);
    }

    /**
     * addMarker
     *
     * @param option
     */
    map.prototype.addMarker = function (option) {
        return this.mapTool.addMarker(this.map, option);
    }

    /**
     * getMapHandle
     *
     * @param option
     */
    map.prototype.getMap = function (option) {
        return this.map
    }

    map.prototype.addTextInfoWindow = function (option) {
        return this.mapTool.addTextInfoWindow(this.map, option);
    }

    /**
    * 高得地图工具类
    *
    * @namespace
    */
    var amap = map.amap = {}

    amap.createMap = function (container, option) {
        var map = new AMap.Map(container, {
            view: new AMap.View2D({
                center: new AMap.LngLat(option.lng, option.lat),
                zoom: option.zoom
            })
        });
        return map;
    }

    amap.addIconMarker = function (map, option) {
        var icon = new AMap.Icon({
            image: option.image,
            imageSize: new AMap.Size(option.imageWidth, option.imageHeight),
            imageOffset: new AMap.Pixel(0 - option.imageOffsetX, 0 - option.imageOffsetY),
            size: new AMap.Size(option.width, option.height),
        });
        var marker = new AMap.Marker({
            map: map,
            icon: icon,
            offset: new AMap.Pixel(0 - option.offsetX, 0 - option.offsetY),
            position: new AMap.LngLat(option.lng, option.lat)
        });
        return marker;
    }

    amap.addMarker = function (map, option) {
        var position = new AMap.LngLat(option.lng, option.lat);
        var marker = new AMap.Marker({
            map: map,
            position: position,
            title: option.title
        });

    }

    amap.addTextInfoWindow = function (map, option) {
        var position = new AMap.LngLat(option.lng, option.lat);
        var offset = new AMap.Pixel(0 - option.offsetX, 0 - option.offsetY);

        var textInfoWindow = new AMap.InfoWindow({
            offset: offset,
            content: option.content
        });
        textInfoWindow.open(map, position);

        return textInfoWindow;
    }

    /**
    * 谷歌地图工具类
    *
    * @namespace
    * {{{
    */

    // @TODO amap和gmap在工具栏的差别处理
    var googleMap = map.google = {}

    googleMap.createMap = function (container, option) {
        var map = new google.maps.Map(document.getElementById(container), {
            center: new google.maps.LatLng(option.lat, option.lng),
            zoom: option.zoom,
            disableDefaultUI: true
        });
        return map;
    }

    googleMap.addIconMarker = function (map, option) {
        var icon = {
            url: option.image,
            scaledSize: new google.maps.Size(option.imageWidth, option.imageHeight),
            origin: new google.maps.Point(option.imageOffsetX, option.imageOffsetY),
            anchor: new google.maps.Point(option.offsetX, option.offsetY),
            size: new google.maps.Size(option.width, option.height)
        };
        var marker = new google.maps.Marker({
            map: map,
            icon: icon,
            position: new google.maps.LatLng(option.lat, option.lng)
        });
        return marker;
    }

    googleMap.addMarker = function (map, option) {
        var position = new google.maps.LatLng(option.lat, option.lng)
        var marker = new google.maps.Marker({
            map: map,
            position: position,
            title: option.title
        });

    }

    googleMap.addTextInfoWindow = function (map, option) {
        var position = new google.maps.LatLng(option.lat, option.lng)
        var offset = new google.maps.Size(0 - option.offsetX, 0 - option.offsetY);
        var textInfoWindow = new google.maps.InfoWindow({
            position: position,
            content: option.content,
            pixelOffset: offset
        });
        textInfoWindow.open(map);
    }
    /* }}} */

    /**
    * 地图名称空间
    *
    * @namespace
    */
    ndoo.util.map = map;

}).call(this);;
(function() {
  var CVS, _n, _vars, jsPathBase, libPathBase, loadedArray;
  this.N = this.ndoo || (this.ndoo = {});
  _n = this.ndoo;
  _vars = _n.vars;
  CVS = '1019';
  jsPathBase = '/Content/js';
  libPathBase = '/Content/lib/';
  Do.setConfig('autoLoad', false);
  loadedArray = [];

  /*[zepto] {{{ */
  Do.define('zepto', {
    type: 'js',
    path: jsPathBase + "/zepto.min.js"
  });
  Do.define('zepto.plugin', {
    type: 'js',
    path: jsPathBase + "/zepto.plugin.js",
    requires: ['zepto']
  });
  Do.define('zepto.cookie', {
    type: 'js',
    path: jsPathBase + "/zepto.cookie.js",
    requires: ['zepto']
  });
  Do.define('zepto.scroll', {
    type: 'js',
    path: jsPathBase + "/zepto.scroll.js",
    requires: ['zepto']
  });
  loadedArray.push('zepto', 'zepto.plugin', 'zepto.cookie', 'zepto.scroll');

  /*}}} */

  /*[riot] {{{ */
  Do.define('riot', {
    type: 'js',
    path: libPathBase + "/riot/riot.min.js"
  });
  Do.define('riot+compiler', {
    type: 'js',
    path: libPathBase + "/riot/riot+compiler.min.js"
  });
  Do.define('quickOrderRoomTypePopup', {
    type: 'js',
    path: jsPathBase + "/riot/quickOrderRoomTypePopup.js",
    requires: ['riot']
  });
  Do.define('quickOrderHotelChoice', {
    type: 'js',
    path: jsPathBase + "/riot/quickOrderHotelChoice.js",
    requires: ['riot']
  });

  /*}}} */
  return Do.setLoaded(loadedArray);
})();
;

/*
" --------------------------------------------------
"   FileName: main.leaf.coffee
"       Desc: main.js webapp逻辑脚本
"     Author: chenglifu
"    Version: v0.1
" LastChange: 11/04/2014 16:51
" --------------------------------------------------
 */

/* Notice: 不要修改本文件，本文件由main.leaf.coffee自动生成 */
(function($) {
  var common, _func, _n, _stor, _vars;
  _n = this;
  _vars = _n.vars;
  _func = _n.func;
  _stor = _n.storage;
  _vars.week = {
    cn: {
      0: '日',
      1: '一',
      2: '二',
      3: '三',
      4: '四',
      5: '五',
      6: '六'
    }
  };
  (common = function() {

    /* turbolinks support {{{ */

    /*是否启用Turbolinks */
    var _ua;
    _func.isUseTurbolinks = function() {
      return _stor('useTurbolinks') && window.Turbolinks && window.Turbolinks.supported;
    };
    _func._stateCallback = function(state, pageid, token, call) {
      var callback, storKey, _ref;
      if (!call && typeof token === 'function') {
        _ref = ["token_" + (_n.getPK()), token], token = _ref[0], call = _ref[1];
      }
      if (_func.isUseTurbolinks() || state === 'common' || state === 'load') {
        storKey = '';
        switch (state) {
          case 'common':
            storKey = 'pageCommonCall';
            break;
          case 'fetch':
            storKey = 'pageFetchCall';
            break;
          case 'beforeUnload':
            storKey = 'pageBeforeUnloadCall';
            break;
          case 'change':
            storKey = 'pageChangeCall';
            break;
          case 'load':
            storKey = 'pageLoadCall';
            break;
          case 'restore':
            storKey = 'pageRestoreCall';
            break;
        }
        callback = _stor(storKey) || {};
        callback[pageid] || (callback[pageid] = {});
        callback[pageid][token] = call;
        return _stor(storKey, callback, true);
      }
    };
    (function() {

      /* state function generate */
      var eventName, item, _i, _len, _ref, _results;
      _ref = ['beforeUnload', 'fetch', 'change', 'load', 'restore', 'common'];
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        item = _ref[_i];
        eventName = item.replace(/^([a-z]{1})/, function(char) {
          return char.toUpperCase();
        });
        _func["addPage" + eventName + "Call"] = new Function('token', 'call', "this._stateCallback('" + item + "', ndoo.pageId, token, call);");
        _results.push(_func["add" + eventName + "Call"] = new Function('token', 'call', "if (call) {\n  this._stateCallback('" + item + "', '_global', token, call);\n}"));
      }
      return _results;

      /*
       * _func.addPageFetchCall        ([token,] call)
       * _func.addPageBeforeUnloadCall ([token,] call)
       * _func.addPageChange           ([token,] call)
       * _func.addPageLoad             ([token,] call)
       * _func.addPageRestoreCall      ([token,] call)
       * _func.addFetchCall            (token, call)
       * _func.addBeforeUnloadCall     (token, call)
       * _func.addChangeCall           (token, call)
       * _func.addLoadCall             (token, call)
       * _func.addRestoreCall          (token, call)
       *
       * _func.addPageCommonCall       ([token,] call)
       * _func.addCommonCall           (token, call)
       */
    })();

    /*处理javascrpt变量保留 {{{ */
    _func.addCommonCall('initStorGaVariable', function() {
      _func.addBeforeUnloadCall('setReferer', function() {
        _vars.referrer = history.state.referer;
      });
      _func.addRestoreCall('storGaVariable', function() {
        var attr, copy, nextSibling, parentNode, script, scripts, track, _i, _j, _len, _len1, _ref, _ref1;
        scripts = Array.prototype.slice.call(document.body.querySelectorAll('script[data-always-eval="true"]'));
        track = document.body.querySelector('script[data-track-script]');
        if (track) {
          scripts.push(track);
        }
        for (_i = 0, _len = scripts.length; _i < _len; _i++) {
          script = scripts[_i];
          if (!((_ref = script.type) === '' || _ref === 'text/javascript')) {
            continue;
          }
          copy = document.createElement('script');
          _ref1 = script.attributes;
          for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
            attr = _ref1[_j];
            copy.setAttribute(attr.name, attr.value);
          }
          if (!script.hasAttribute('async')) {
            copy.async = false;
          }
          copy.appendChild(document.createTextNode(script.innerHTML));
          parentNode = script.parentNode, nextSibling = script.nextSibling;
          parentNode.removeChild(script);
          parentNode.insertBefore(copy, nextSibling);
        }
      });
    });

    /*}}} */

    /*切换td当前状态 */
    _func.tdCurrStyle = function($td, cmd) {
      var $nexttd, $nexttr, index;
      if (cmd == null) {
        cmd = 'del';
      }
      if (!$td) {
        return void 0;
      }
      index = $td.index();
      if (cmd !== 'add') {
        if ($nexttd = $td.next()) {
          $nexttd.removeClass('righttd');
        }
        if ($nexttr = $td.parents('tr').next()) {
          $nexttr.find('td').eq(index).removeClass('bottomtd');
        }
      } else {
        if ($nexttd = $td.next()) {
          $nexttd.addClass('righttd');
        }
        if ($nexttr = $td.parents('tr').next()) {
          $nexttr.find('td').eq(index).addClass('bottomtd');
        }
      }
    };

    /*加载更多 */
    _func.lazyLoadMore = function($list, callback) {
      var page, total, url;
      page = parseInt($list.data('page'));
      total = parseInt($list.data('total'));
      url = $list.data('url');
      if (page < 0) {
        $list.data('lock', 'locked');
        return void 0;
      }
      page += 1;
      if ((page <= total) && ($list.data('lock') !== 'locked')) {
        $list.data('lock', 'locked');
        $list.data('page', page);
        $.get(url, {
          page: page
        }, function(data) {
          data = $.trim(data);
          if (data) {
            $(data).appendTo($list);
            if (page === total) {
              $list.siblings('.loadmore').removeClass('enable');
            } else {
              $list.data('lock', 'unlock');
              if (callback) {
                callback();
              }
            }
          } else {
            $list.siblings('.loadmore').removeClass('enable');
          }
        });
      }
    };

    /*图片延时加载 */
    _func.lazyListImage = function($list) {
      if ($list.data('nolazy') !== 'on') {
        $list.imglazyload({
          filter: '.img>img',
          live: true,
          checker: function(item) {
            var offset;
            offset = item.getBoundingClientRect();
            if (offset.top > 0 && offset.top < window.innerHeight) {
              item = $(item);
              if (item.data('lazyState') !== 'loaded') {
                item.data('lazyState', 'loaded');
                return true;
              }
            }
            return false;
          }
        });
      }
    };

    /*切换滑动插件 */
    _func.initHotelDetailSwipe = function($dialog, automove) {
      var $imgs, $items, imgHeight, imgLen, rate, _swipe, _width;
      if (automove == null) {
        automove = false;
      }
      _width = $dialog.find('.Cbanner').width();
      $items = $dialog.find('.Cbanner .items');
      rate = parseInt($items.data('height')) / parseInt($items.data('width'));
      imgHeight = parseInt(_width * rate);
      $imgs = $dialog.find('.Cbanner .item img');
      if ($imgs.length > 0) {
        $imgs.css({
          width: _width,
          height: imgHeight
        });
      }
      imgLen = $imgs.length;
      if ($imgs.length <= 1) {
        return void 0;
      }
      _swipe = $dialog.find('.Cbanner .inner').swipe({
        initPos: 0,
        gap: _width,
        onchange: function(cur) {
          $dialog.find('.cycle.selected').removeClass('selected');
          $($dialog.find('.cycle')[cur % imgLen]).addClass('selected');
        },
        automove: automove
      });
    };
    _func.initSwipe = function() {
      setTimeout(function() {
        var $imgs, $items, changecall, devicewidth, imgHeight, imgLen, rate, _swipe, _width;
        _width = $('.Cwrap').width();
        devicewidth = _width || 320;
        $items = $('.Cbanner .items');
        rate = parseInt($items.data('height')) / parseInt($items.data('width'));
        imgHeight = parseInt(devicewidth * rate);
        $imgs = $('.Cbanner .item img');
        if (!$imgs.length) {
          return void 0;
        }
        imgLen = $imgs.css({
          width: devicewidth,
          height: imgHeight
        }).length;
        _swipe = $('.Cbanner .inner').swipe({
          initPos: 0,
          gap: devicewidth,
          onchange: function(cur) {
            _stor('swipecurr', cur, true);
            $('#ban-nav .cycle.selected').removeClass('selected');
            $($('#ban-nav .cycle')[cur % imgLen]).addClass('selected');
          },
          automove: true
        });
        changecall = function() {
          var currwidth, imgheight;
          currwidth = $('.Cwrap').width();
          if (devicewidth !== currwidth) {
            devicewidth = currwidth;
            _stor('devicewidth', currwidth, true);
            imgheight = currwidth * rate;
            $('.Cbanner .item img').css({
              width: devicewidth,
              height: imgheight
            });
            _swipe.reinitswipe({
              initPos: (0 - _stor('swipecurr') * currwidth) || 0,
              gap: currwidth,
              width: imgLen * currwidth,
              height: imgheight
            });
          }
        };
        $(window).on('resize', changecall);
        $(window).on('orientationchange', changecall);
        _func.addPageBeforeUnloadCall('stopSwipeChangeListener', function() {
          _swipe.stop();
          $(window).off('resize', changecall);
          $(window).off('orientationchange', changecall);
        });
        _func.addPageRestoreCall('startSwipeChangeListener', function() {
          _swipe.start();
          $(window).on('resize', changecall);
          $(window).on('orientationchange', changecall);
        });
      }, 200);
    };

    /*渲染得分 */
    _func.renderScore = function(score) {
      var half, i, ret, score_int, score_offset, _i, _j;
      score = parseFloat(score);
      score_int = parseInt(score);
      ret = '<span class="Cscore">';
      for (i = _i = 0; 0 <= score_int ? _i < score_int : _i > score_int; i = 0 <= score_int ? ++_i : --_i) {
        ret += '<i class="Cicon score_full"></i>';
      }
      if (score_int < 5) {
        half = score - score_int;
        if (half > 0) {
          ret += '<i class="Cicon score_half"></i>';
        }
        score_offset = 5 - score_int - Math.ceil(half);
        if (score_offset > 0) {
          for (i = _j = 0; 0 <= score_offset ? _j < score_offset : _j > score_offset; i = 0 <= score_offset ? ++_j : --_j) {
            ret += '<i class="Cicon score_empty"></i>';
          }
        }
      }
      return ret += '</span>';
    };

    /*下拉框绑定 */
    _func.bindValue = function(select) {
      var $select;
      $select = select;
      $select.on('change', function() {
        var $self;
        $self = $(this);
        $($self.data('bind')).val($self.val());
      });
    };

    /*select 高度 */
    _func.addCommonCall('fixHeight', function() {
      var _fixheight;
      _fixheight = function() {
        var $select, pheight;
        $select = $('select.fixheight');
        pheight = $select.parent().height();
        if ($select.height() < pheight) {
          return $select.css('font-size', $select.data('height'));
        }
      };
      if (_func.isUseTurbolinks()) {
        _func.addLoadCall('fixHeight', _fixheight);
      } else {
        _fixheight();
      }
    });

    /*返回顶部 */
    (function() {
      _func.addLoadCall('backToTop', function() {
        $('.Cback').on('click', function() {
          var ctop, time, ttop;
          ctop = document.body.scrollTop;
          ttop = 0;
          time = Math.max((ctop - ttop) / 3, 400);
          if (time > 1000) {
            time = 1000;
          }
          setTimeout(function() {
            $.scrollTo({
              endY: 0,
              duration: time
            });
          }, 100);
        });
      });
      $(window).on('scroll', function() {
        var _backTimeToken;
        if ($('#scriptArea').data('pageId') === 'hotel/date') {
          return void 0;
        }
        _backTimeToken = +new Date();
        _stor('backTimeToken', _backTimeToken, true);
        setTimeout(function() {
          var $back, height, top;
          if (_backTimeToken === _stor('backTimeToken')) {
            height = document.documentElement.clientHeight;
            top = document.body.scrollTop;
            $back = $('.Cback');
            if (top >= height) {
              if (!$back.data('show')) {
                $back.data('show', 'show').show();
              }
            } else {
              if ($back.data('show')) {
                $back.data('show', '').hide();
              }
            }
          }
        }, 100);
      });
    })();

    /*搜索动画 */
    _func.searchClean = function() {
      var $searchkey;
      $searchkey = $('.Croundsearch input.searchkey');
      if ($.trim($searchkey.val()).length) {
        $searchkey.parent().siblings('.clean_input').show();
      }
      $searchkey.on('input', function() {
        var $self;
        $self = $(this);
        if ($self.val().length > 0) {
          return $self.parent().siblings('.clean_input').show();
        } else {
          return $self.parent().siblings('.clean_input').hide();
        }
      });
      $('.Croundsearch .clean_input').on('click', function() {
        var $self;
        $self = $(this);
        $self.parents('.Croundsearch').find('input.searchkey').val('').focus().trigger('input');
        $self.hide();
      });
    };

    /*环境检测 */
    try {
      _ua = navigator.userAgent.match(/(\([^)]*\))/g)[0];
    } catch (_error) {
      _ua = '';
    }
    _func.isAndroid = function() {
      return /Android/i.test(_ua);
    };
    _func.isIOS = function() {
      return /i(Phone|P(o|a)d)/i.test(_ua);
    };
    _func.isIPad = function() {
      return /iPad/i.test(_ua);
    };
    _func.isIPhone = function() {
      return /iPhone/i.test(_ua);
    };
    _func.isWP = function() {
      return /Windows Phone/i.test(_ua);
    };
    _func.isQQBrowser = function() {
      return /MQQBrowser/i.test(navigator.userAgent);
    };
    _func.isChrome = function() {
      return /Chrome\/\d/i.test(navigator.userAgent);
    };
    _func.isInQQ = function() {
      return /QQ\/\d/i.test(navigator.userAgent);
    };
    _func.isInWenXin = function() {
      return /MicroMessenger\/\d/i.test(navigator.userAgent);
    };
    _func.isInSafari = function() {
      return /Safari/i.test(navigator.userAgent);
    };
    _func.getTransitionEndEvent = function() {
      var transitionEndEvent;
      transitionEndEvent = 'webkitTransitionEnd';
      if ($.browser.firefox) {
        transitionEndEvent = 'transitionend';
      } else if ($.browser.ie) {
        transitionEndEvent = 'MSTransitionEnd';
      }
      return transitionEndEvent;
    };

    /* 显示app下载框 */
    _func.showAppDialog = function() {
      var $popup;
      _func.testTime = function(tick, offset) {
        var d;
        d = +new Date();
        if ((d - tick - offset) < 100) {
          return 1;
        } else {
          return 0;
        }
      };
      _func.downApp = function(url) {
        var iframe;
        iframe = $('<iframe style="display: none;"></iframe>');
        iframe.attr('src', url).appendTo('body');
      };
      $popup = $('.Cpopup.client');
      if ($popup.length) {
        if (_func.isIPhone() || _func.isAndroid()) {
          $popup.show();
          $popup.find('i.close_large').ctap(function() {
            $.fn.cookie('hide_client_popup', 'hide', {
              expires: 30
            });
            return $popup.hide();
          });
          $popup.find('.app_link').on('click', function(e) {
            var c;
            c = +new Date();
            e.preventDefault();
            if (_func.isAndroid()) {
              _func.downApp(_vars.applink.android);
              setTimeout(function() {
                if (_func.testTime(c, 600)) {
                  return location.href = _vars.applink.androidurl;
                }
              }, 600);
            } else if (_func.isIPhone()) {
              location.href = _vars.applink.ios;
              setTimeout(function() {
                if (_func.testTime(c, 100)) {
                  return location.href = _vars.applink.iosurl;
                }
              }, 100);
            }
          });
          return;
        }
      }
    };

    /* 新版显示下载 */ 
    _func.newDownloadApp = function() {
      var _popupHtml = '<div class="Cpopup newClient">\
                          <div class="Cwrap Lposr">\
                              <div class="inner actived">\
                                  <a href="http://destinations.h-world.com.wscdns.com/a/?s=wechatpopup" class="download_link">\
                                      <i class="hand"></i>\
                                      <span class="text">下载APP 享优惠噢</span>\
                                  </a>\
                                  <span class="show_hand"><i class="arrow"></i></span>\
                              </div>\
                          </div>\
                      </div>';
      $('body').append(_popupHtml);
      var $popup = $('.Cpopup.newClient');
      var $showHand = $('.Cpopup.newClient .show_hand');
      $popup.show();
      $showHand.hide();
      setTimeout(function(){
        $popup.find('.inner').removeClass('actived');
        $popup.find('.hand').removeClass('shakehand');
        $showHand.show();
      }, 2000);
      $popup.find('.show_hand').ctap(function() {
          $showHand.hide();
          $popup.find('.inner').addClass('actived');
          setTimeout(function(){
	          $popup.find('.hand').addClass('shakehand');
          }, 400);
          setTimeout(function(){
            $popup.find('.inner').removeClass('actived');
            $popup.find('.hand').removeClass('shakehand');
            $showHand.show();
          }, 2000);
        });
    }

    /* 显示添加到主屏幕 */
    _func.showAddMainScreenDialog = function() {
      var $popup;
      $popup = $('.Cpopup.add_main_screen');
      if ($popup.length) {
        $popup.show();
        $popup.find('.close').ctap(function() {
          $.fn.cookie('hide_client_popup', 'hide', {
            expires: 30
          });
          return $popup.hide();
        });
      }
    };
    _func.addLoadCall('clientPopup', function() {
      var hide_client_popup, rand;
      hide_client_popup = $.fn.cookie('hide_client_popup');
      if (_func.isInQQ() || hide_client_popup) {
        return void 0;
      }
      rand = 5;
      if (_func.isIOS() && _func.isInSafari() && _n.pageId === 'home/index2016') {
        rand = 6;
      }

      if (rand > 5) {
        // _func.showAddMainScreenDialog();
      }
      
      // 只在购物流程和用户中心显示下载app引导浮层
      // var _testPages = ['Account', 'PersonalCenter', 'hotel', 'reserve'];
		  // for (var _testPageName in _testPages) {
	    //   var _pageName = _n.pageId;
      //   if (_pageName.indexOf(_testPages[_testPageName]) > -1) {
      //     _func.newDownloadApp();
      //   }
	    // }
      // 只在首页和搜索首页显示下载app引导浮层
      
      if (_n.pageId === 'home/index2016' || _n.pageId === 'hotel/city') {
        _func.newDownloadApp();
      }

    });

    /* loading */
    _n.hook('loading', function() {
      $('.Cload').show();
    });
    _n.hook('hideloading', function() {
      $('.Cload').hide();
    });

    /* 用户点击1000ms后如未加载完成，显示loading */
    _func.addCommonCall('showLoading', function() {
      if (_func.isUseTurbolinks()) {
        _func.addFetchCall('showLoading', function() {
          var _call;
          _stor('showLoadingToken', true, true);
          _call = function() {
            if (_stor('showLoadingToken')) {
              _n.hook('loading');
            }
          };
          setTimeout(_call, 600);
        });
        _func.addBeforeUnloadCall('stopShowLoading', function() {
          _n.hook('hideloading');
          _stor('showLoadingToken', false, true);
        });
      }
    });

    /* fix container */
    _func.inputFocusFix = function() {
      if ($.browser.safari && _func.isInQQ()) {
        $('.HO_main .btnbox').css('position', 'static').addClass('Lmt10').siblings('.Lpt40').hide();
        return void 0;
      }
      if (!$.browser.safari && !_func.isQQBrowser() && !_func.isWP()) {
        $('.container input.input1').on('focus', function() {
          var $item;
          $item = $(this).parents('.item');
          setTimeout(function() {
            var errorHeight, fixHeight;
            if (document.body.scrollTop === 0) {
              errorHeight = $('.errorS').height();
              if (errorHeight) {
                errorHeight += 8;
              }
              fixHeight = $item.offset().top - errorHeight;
              document.body.scrollTop = fixHeight;
            }
            if (document.body.scrollTop === 0) {
              _vars.fixScroll = true;
              return $('.container').css('margin-top', 0 - fixHeight + $('.Chead').height());
            }
          }, 600);
        }).on('blur', function() {
          if (_vars.fixScroll) {
            _vars.fixScroll = false;
            $('.container').css('margin-top', 0);
          }
        });
      }
    };

    /* 动态添加脚本文件 */
    _func.addScript = function(url) {
      var script, _scriptArea;
      script = document.createElement('script');
      script.setAttribute('type', 'text/javascript');
      _scriptArea = document.getElementById('scriptArea');
      if (_scriptArea) {
        _scriptArea.appendChild(script);
      } else {
        document.body.appendChild(script);
      }
      script.setAttribute('src', url);
      script = null;
    };

    /* push state */
    _stor('usePushState', true);
    _func.pushState = function(path) {
      var inject;
      if (_stor('initPushState')) {
        if (_func.isUseTurbolinks()) {
          inject = _stor('pushStateInject') || 0;
          _stor('pushStateInject', inject + 1, true);
          history.pushState({
            path: path,
            inject: inject + 1,
            prev: history.state.url
          }, '', '#' + path);
        } else {
          history.pushState({
            path: path,
            prev: location.href
          }, '', '#' + path);
        }
        _n.hook('trackPageView', location.href);
      }
      _n.hook('pushStateHook', path);
    };
    _func.pushStateCallback = function(state) {
      var inject, _hash;
      _hash = location.hash.substr(1);
      if (state && state.inject) {
        inject = history.state.inject + 1;
      } else {
        inject = _stor('pushStateInject') || 0;
      }
      if (inject >= 1) {
        _stor('pushStateInject', inject - 1, true);
      }
      if (_stor('initPushState')) {
        _n.hook('pushStateHook', _hash);
      } else {
        _stor('pushStateHookPath', _hash);
      }
    };
    _func.addPushStateHook = function(call) {
      _func.addPageRestoreCall('pushStateHook', function() {
        return _n.hook('pushStateHook', call, true);
      });
      _n.hook('pushStateHook', call, true);
    };
    _func.initPushState = function(call) {
      this.addPushStateHook(call);
      if (!_stor('initPushState') && history.pushState && _stor('usePushState')) {
        _stor('initPushState', 1);
        if (_func.isUseTurbolinks()) {
          _func.addBeforeUnloadCall('resetInject', function() {
            var _ref;
            if ((_ref = history.state) != null ? _ref.turbolinks : void 0) {
              _stor('pushStateInject', 0, true);
            }
          });
        } else {
          $(window).on('popstate', _func.pushStateCallback);
        }
        if (location.hash.substr(1).length > 1) {
          this.pushStateCallback();
        }
      }
    };

    /*添加统计脚本 */
    _n.hook('addTrack', function() {
      if (ndoo.vars.trackjs) {
        _func.addScript(ndoo.vars.trackjs);
      }
    });

    /*track */
    _n.hook('trackPageView', function(path) {
      _gaq.push(['_trackPageview', path]);
    });
    _n.hook('trackEvent', function(param, type) {
      var _ref;
      if (type == null) {
        type = 'defualt';
      }
      if ((_ref = window.wa_track_event) != null) {
        _ref.apply(null, param);
      }
    });

    /*fix scroll */
    _n.hook('scrollLit', function() {
      var _top;
      _top = document.body.scrollTop;
      if (_top > 0) {
        window.scrollTo(0, 0);
      } else {
        window.scrollTo(0, 1);
      }
    });

    /*阻止事件 */
    _func.preventScroll = function(e) {
      if (typeof e.stopPropagation === "function") {
        e.stopPropagation();
      }
      return typeof e.preventDefault === "function" ? e.preventDefault() : void 0;
    };

    /*回调注册 */
    _func.addCommonCall('WebViewJavascriptBridge', function() {
      document.addEventListener('WebViewJavascriptBridgeReady', function(e) {
        _stor('WebViewJavascriptBridge', e.bridge, true);
        if (_n.pageId === 'hotel/keyword' || _n.pageId === 'customize/index' || _n.pageId.indexOf('fastcheckin') >= 0 || _n.pageId === 'price/order') {
          _func.setIosAppTitle();
        } else {
          e.bridge.send({
            title: "",
            callback: ""
          });
        }
        _n.hook('WebViewJavascriptBridgeReady');
      });
      _func.addPageBeforeUnloadCall('WebViewJavascriptBridgeClean', function() {
        var bridge;
        bridge = N.storage('WebViewJavascriptBridge');
        if (bridge) {
          bridge.send({
            title: "",
            callback: ""
          });
        }

        /* android title */
        if (window.huazhu) {
          window.huazhu.GetMapCheckIn("", "");
        }
      });
    });

    /*魅族浏览器检测 {{{ */
    _func.addCommonCall('meizuDetect', function() {
      var $html;
      if (/MX4 Build\//.test(navigator.userAgent)) {
        $html = $('html');
        if (!$html.hasClass('meizu4_fixed')) {
          return $html.addClass('meizu4_fixed');
        }
      }
    });

    /*优惠券多天选择 {{{ */
    _n.hook('dayctrl', function() {
      var $box, $inner, $next, $prev, $select, callkey, _clickFn, _initCtrl, _scrollItem;
      $select = $('#divCoupon .dayselect');
      $box = $select.find('.clipbox');
      $inner = $select.find('.inner');
      $prev = $select.find('.parrow');
      $next = $select.find('.narrow');
      _initCtrl = function() {
        var _itemWidth, _marginLeft, _width, _wrapwidth;
        $box.addClass('transition');
        _width = parseInt($inner.width());
        _wrapwidth = parseInt($select.width());
        _itemWidth = $select.find('.item').eq(0).width();
        $prev.hide();
        $next.hide();
        _marginLeft = parseInt($box.data('marginLeft')) || 0;
        if (_marginLeft !== 0) {
          if (_wrapwidth > _width) {
            $box.css('marginLeft', 0).data('marginLeft', 0);
          } else {
            if (_width + _marginLeft < _wrapwidth) {
              _marginLeft = _wrapwidth - _width;
              $box.css('marginLeft', _marginLeft).data('marginLeft', _marginLeft);
            } else {
              $next.show();
            }
            $prev.show();
          }
        } else {
          if (_width > _wrapwidth) {
            $next.show();
          }
        }
      };
      _scrollItem = function(cmd) {
        var _change, _itemWidth, _marginLeft, _minLeft, _width, _wrapwidth;
        _change = false;
        _width = parseInt($inner.width());
        _wrapwidth = parseInt($select.width());
        _itemWidth = $select.find('.item').eq(0).width();
        _marginLeft = parseInt($box.data('marginLeft')) || 0;
        if (cmd === 'prev') {
          if (_marginLeft !== 0) {
            _marginLeft += _itemWidth;
            if (_marginLeft >= 0) {
              _marginLeft = 0;
              $prev.hide();
            }
            _change = 'prev';
          }
        } else if (cmd === 'next') {
          _minLeft = _wrapwidth - _width;
          if (_marginLeft > _minLeft) {
            _marginLeft -= _itemWidth;
            if (_marginLeft <= _minLeft) {
              _marginLeft = _minLeft;
              $next.hide();
            }
            _change = 'next';
          }
        }
        if (_change === 'prev') {
          $next.show();
          $box.css('marginLeft', _marginLeft).data('marginLeft', _marginLeft);
        } else if (_change === 'next') {
          $prev.show();
          $box.css('marginLeft', _marginLeft).data('marginLeft', _marginLeft);
        }
      };
      _clickFn = function() {
        var $self;
        $self = $(this);
        if ($self.hasClass('parrow')) {
          _scrollItem('prev');
        } else if ($self.hasClass('narrow')) {
          _scrollItem('next');
        }
      };
      if ($select.data('init') !== 'inited') {
        $select.data('init', 'inited');
        _initCtrl();
        $prev.on('click', _clickFn);
        $next.on('click', _clickFn);
      }
      $(window).on('resize', _initCtrl);
      $(window).on('orientationchange', _initCtrl);
      callkey = 'stopDayCtrlChangeListener';
      if (_n.pageId === 'reverse/cpc') {
        callkey = 'stopCpcDayCtrlChangeListener';
      }
      _func.addPageBeforeUnloadCall(callkey, function() {
        $(window).off('resize', _initCtrl);
        $(window).off('orientationchange', _initCtrl);
      });
    });
    _func.addLoadCall('fastClick', function() {
      var needFastClick;
      needFastClick = false;
      if (_func.isIOS() && typeof window.ontouchstart !== 'undefined') {
        needFastClick = true;
      }
      if (needFastClick && window.FastClick) {
        FastClick.attach(document.body);
      }
    }, false);

    /*checkin {{{ */
    _func.checkinCount = function() {
      var $checkin_people_num, $checkin_room_num, checkin_people, checkin_room, max;
      $('#checkin_count .count_ctrl').on('click', function() {
        var $another, $num, $self, max, num;
        $self = $(this);
        $another = $self.siblings('.count_ctrl');
        $num = $self.siblings('.num');
        if ($self.hasClass('gray')) {
          return;
        }
        if ($num.length) {
          num = parseInt($num.val());
          if ($self.hasClass('plus')) {
            num++;
            max = parseInt($num.data('max'));
            if (max > 0 && num >= max) {
              $self.addClass('gray');
              if (max < num) {
                return;
              }
            }
            $num.val(num);
            $num.trigger('change');
            if ($another.hasClass('gray')) {
              $another.removeClass('gray');
            }
          } else {
            if (num > 1) {
              num--;
              if (num <= 1) {
                $self.addClass('gray');
              }
              $num.val(num);
              $num.trigger('change');
              if ($another.hasClass('gray')) {
                $another.removeClass('gray');
              }
            }
          }
        }
      });
      $('#checkin_count .num').on('change', function() {
        var $self, model, val;
        $self = $(this);
        model = $self.data('model');
        val = parseInt($self.val());
        if (!isNaN(val)) {
          $("#" + model).val(val);
          $("#" + model + "_label").text(val);
        }
      });
      checkin_room = parseInt($('#checkin_room').val());
      checkin_people = parseInt($('#checkin_people').val());
      if (!isNaN(checkin_room)) {
        $checkin_room_num = $('#checkin_count .num').eq(0);
        $checkin_room_num.val(checkin_room);
        $("#" + ($checkin_room_num.data('model')) + "_label").text(checkin_room);
        if (checkin_room === 1) {
          $checkin_room_num.parent().find('.count_ctrl').eq(0).removeClass('gray').addClass('gray');
        }
        max = parseInt($checkin_room_num.data('max'));
        if (max > 0 && checkin_room === max) {
          $checkin_room_num.parent().find('.count_ctrl').eq(1).removeClass('gray').addClass('gray');
        }
      }
      if (!isNaN(checkin_people)) {
        $checkin_people_num = $('#checkin_count .num').eq(1);
        $checkin_people_num.val(checkin_people);
        $("#" + ($checkin_people_num.data('model')) + "_label").text(checkin_people);
        if (checkin_people === 1) {
          $checkin_people_num.parent().find('.count_ctrl').eq(0).removeClass('gray').addClass('gray');
        }
        max = parseInt($checkin_people_num.data('max'));
        if (max > 0 && checkin_people === max) {
          return $checkin_people_num.parent().find('.count_ctrl').eq(1).removeClass('gray').addClass('gray');
        }
      }
    };

    /*}}} */

    /*location {{{ */
    _n.location = {

      /*是否初始化 */
      _init: false,

      /*数据存储 */
      _data: {
        citySearch: [],
        lngLat: [],
        result: {}
      },

      /*地图句柄 */
      map: null,

      /*任务队列 */
      tasks: {},

      /*初始化 */
      init: function() {
        var _self;
        _self = this;
        if (!this._init) {
          if (!this.addScript) {

            /*添加地图 */
            window.wrapMapCallback = function() {
              _self._init = true;
              _self.run();
            };
            _func.addScript(_vars.mapurl + '&callback=wrapMapCallback');
            _self.addScript = true;
          }

          /*脚本加载锁 */
          _func.addBeforeUnloadCall('removeAddScriptToken', function() {
            _self.addScript = false;
          });

          /*清理map实例 */
          _func.addBeforeUnloadCall('cleanMaphandle', function() {
            _self.map = null;
          });
        }
      },
      run: function() {
        if (!this._init) {
          this.init();
        } else {

          /*@TODO 是否需要多重调用锁 */
          if (this.tasks.citySearch) {
            this.citySearch();
          }
          if (this.tasks.lngLat) {
            this.lngLat();
          }
        }
      },

      /*通过ip获取城市，句柄 */
      citySearch: function() {

        /*获取当前城市 */
        var call, _self;
        _self = this;
        if (_self._data.result.citySearch) {
          while (call = _self._data.citySearch.shift()) {
            call('success', _self._data.result.citySearch);
          }
          _self.tasks.citySearch = false;
          return;
        }
        AMap.service(['AMap.CitySearch'], function() {
          var citySearch;
          citySearch = new AMap.CitySearch();
          citySearch.getLocalCity(function(status, result) {
            if (status === 'complete' && result && result.city) {
              while (call = _self._data.citySearch.shift()) {
                call('success', result.city);
              }

              /*暂存当前获取的城市 */
              _self._data.result.citySearch = result.city;
            } else {
              while (call = _self._data.citySearch.shift()) {
                call('failure', result.info || '获取当前城市失改');
              }
            }
            _self.tasks.citySearch = false;
          });
        });
      },

      /*获取当前城市 */
      getCurrCity: function(callback, degree) {
        if (degree == null) {
          degree = 'probably';
        }

        /*初始化并添加到任务队列 */
        if (degree === 'probably') {
          this._data.citySearch.push(callback);
          this.tasks.citySearch = true;
        }
        this.run();
      },

      /*获取经纬度句柄 */
      lngLat: function() {
        var call, mapDiv, _self;
        _self = this;

        /*暂不使用缓存 */
        if (_self._data.result.lngLat && 0) {
          while (call = _self._data.lngLat.shift()) {
            call('success', _self.data.result.lngLat);
            _self.tasks.lngLat = false;
          }
          return;
        }
        if (!this.map) {
          mapDiv = document.createElement('div');
          mapDiv.style = 'width: 1px; height: 1px; display: none;';
          document.body.appendChild(mapDiv);
          this.map = new AMap.Map(mapDiv);
        }
        _func.addPageBeforeUnloadCall('cleanMapStor', function() {
          var listener;
          listener = _stor('hotelCityListener');
          if (listener) {
            AMap.event.removeListener(listener['complete']);
            AMap.event.removeListener(listener['error']);
            _stor('hotelCityListener', null, null, true);
          }
        });
        this.map.plugin('AMap.Geolocation', function() {
          var geocompletelistener, geoerrorlistener, geolocation;
          geolocation = new AMap.Geolocation({
            timeout: 180000,
            showButton: false,
            showMarker: false,
            showCircle: false
          });
          if (geolocation.isSupported()) {
            geocompletelistener = AMap.event.addListener(geolocation, "complete", function(result) {
              var data, dataLngLat, lnglat;
              lnglat = result.position;
              dataLngLat = {
                lng: lnglat.getLng(),
                lat: lnglat.getLat()
              };
              data = {};
              while (call = _self._data.lngLat.shift()) {
                call('success', dataLngLat);
              }
              _self._data.result.lngLat = dataLngLat;
              _self.tasks.lngLat = false;
            });
            geoerrorlistener = AMap.event.addListener(geolocation, "error", function(result) {
              var err;
              err = '定位失败';
              switch (result.info) {
                case 'NOT_SUPPORTED':
                  err = '当前浏览器不支持定位功能';
                  break;
                case 'PERMISSION_DENIED':
                  err = '浏览器拒绝定位';
                  break;
                case 'POSITION_UNAVAILABLE':
                  err = '浏览器无法获取当前位置';
                  break;
                case 'TIMEOUT':
                  err = '定位超时';
                  break;
                default:
                  err = '定位失败，您可以尝试在设置中开启定位服务，开启WIFI以提高定位成功率！';
              }
              while (call = _self._data.lngLat.shift()) {
                call('failure', err);
              }
              _self.tasks.lngLat = false;
            });
            _stor('hotelCityListener', {
              complete: geocompletelistener,
              error: geoerrorlistener
            }, true);
            geolocation.getCurrentPosition();
          } else {
            while (call = _self._data.lngLat.shift()) {
              call('failure', '定位失败，您可以尝试在设置中开启定位服务，开启WIFI以提高定位成功率！');
            }
            _self.tasks.lngLat = false;
          }
        });
      },

      /*获取经纬度 */
      getLngLat: function(callback, degree) {
        if (degree == null) {
          degree = 'high';
        }
        if (degree === 'high') {
          this._data.lngLat.push(callback);
          this.tasks.lngLat = true;
        }
        this.run();
      }
    };

    /*}}} */

    /*checkinDate {{{ */
    _func.checkinDate = function() {

      /*天数相加 */
      var $checkin, $checkinDate, $checkout, checkin, checkout, currDate, dateAddDay, getCheckinDayLabel, getDateLabel, getDayLabel, getDayOffset, maxCheckOutDate, maxCheckinDate, renderCheckinDate, renderCheckoutDate, setBtnState, setCheckin, setCheckout, toDate, toDateString, topCheckinDate, topCheckoutDate;
      dateAddDay = function(dateObj, day) {
        return new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate() + day);
      };

      /*转换日期 */
      toDate = function(year, month, day) {
        year = parseInt(year);
        month = parseInt(month) - 1;
        day = parseInt(day);
        return new Date(year, month, day);
      };
      toDateString = function(dateObj) {
        return "" + (dateObj.getFullYear()) + "/" + (dateObj.getMonth() + 1) + "/" + (dateObj.getDate());
      };

      /*计算天数差 */
      getDayOffset = function(date1, date2) {
        var dayTime;
        dayTime = 1000 * 3600 * 24;
        return Math.floor((date2 - date1) / dayTime);
      };

      /*获取时间文本 */
      getDateLabel = function(date) {
        var dateNum, dateText;
        dateNum = date.getDate();
        if (dateNum > 9) {
          dateText = dateNum;
        } else {
          dateText = "0" + dateNum;
        }
        return "" + (date.getMonth() + 1) + "月" + dateText + "日";
      };

      /*获取入住天数文本 */
      getDayLabel = function(day) {
        return "(" + day + "晚)";
      };

      /*获取入住日期文本 */
      getCheckinDayLabel = function(offset, checkinDate) {
        var dayLabel;
        dayLabel = '';
        switch (offset) {
          case 0:
            dayLabel = '(今天)';
            break;
          case 1:
            dayLabel = '(明天)';
            break;
          case 2:
            dayLabel = '(后天)';
            break;
          default:
            dayLabel = "(" + (checkinDate.getDate()) + "日)";
        }
        return dayLabel;
      };

      /*更新入住日期 */
      $checkinDate = $('#checkinDate');

      /*绑定初始值 */
      checkin = $checkinDate.data('checkin');
      checkout = $checkinDate.data('checkout');
      currDate = dateAddDay(new Date(), 0);

      /*最大入住日期 */
      maxCheckinDate = dateAddDay(currDate, 88);

      /*最大离店日期 */
      maxCheckOutDate = dateAddDay(currDate, 89);
      if (checkin && checkout) {
        $checkin = $("#" + checkin);
        $checkout = $("#" + checkout);
      } else {
        return;
      }

      /*更新按钮状态 */
      setBtnState = function(type, checkinDate, checkoutDate) {
        var checkinOffset, checkoutOffset;
        if (type === 'checkin') {
          checkinOffset = getDayOffset(currDate, checkinDate);

          /*如果是今天，前一晚置灰 */
          if (checkinOffset === 0) {
            $checkinDate.find('.btn.prev').addClass('gray');
            $checkinDate.find('.btn.next').removeClass('gray');
          } else if (checkinOffset === 88) {

            /*如果是89天后一天置灰 */
            $checkinDate.find('.btn.next').addClass('gray');
            $checkinDate.find('.btn.prev').removeClass('gray');
          } else {
            $checkinDate.find('.btn.next, .btn.prev').removeClass('gray');
          }
        } else if (type === 'checkout') {
          checkoutOffset = getDayOffset(checkinDate, checkoutDate);
          if (checkoutOffset === 1) {
            $checkinDate.find('.btn.sub').addClass('gray');
            $checkinDate.find('.btn.add').removeClass('gray');
          } else if (checkoutOffset === 60) {
            $checkinDate.find('.btn.add').addClass('gray');
            $checkinDate.find('.btn.sub').removeClass('gray');
          } else {
            $checkinDate.find('.btn.add, .btn.sub').removeClass('gray');
          }
          if (checkoutDate >= maxCheckOutDate) {
            $checkinDate.find('.btn.add').addClass('gray');
          }
        }
      };

      /*渲染入住日期 */
      renderCheckinDate = function(currDate, checkinDate) {
        var checkinDateLabel, checkinDayLabel, checkinOffset;
        checkinOffset = getDayOffset(currDate, checkinDate);
        checkinDateLabel = getDateLabel(checkinDate);
        checkinDayLabel = getCheckinDayLabel(checkinOffset, checkinDate);
        $checkinDate.find('.checkinDateLabel').text(checkinDateLabel);
        $checkinDate.find('.checkinDayLabel').text(checkinDayLabel);
        setBtnState('checkin', checkinDate);
      };

      /*渲染离店日期 */
      renderCheckoutDate = function(checkinDate, checkoutDate) {
        var checkoutDateLabel, checkoutDayLabel, checkoutOffset;
        checkoutDateLabel = getDateLabel(checkoutDate);
        checkoutOffset = getDayOffset(checkinDate, checkoutDate);
        checkoutDayLabel = getDayLabel(checkoutOffset);
        $checkinDate.find('.checkoutDateLabel').text(checkoutDateLabel);
        $checkinDate.find('.checkoutDayLabel').text(checkoutDayLabel);
        setBtnState('checkout', checkinDate, checkoutDate);
      };

      /*入住日期 */
      topCheckinDate = toDate.apply(null, $checkin.val().split('/'));

      /*离店日期 */
      topCheckoutDate = toDate.apply(null, $checkout.val().split('/'));

      /*渲染日期 */
      renderCheckinDate(currDate, topCheckinDate);
      renderCheckoutDate(topCheckinDate, topCheckoutDate);

      /*绑定事件 */
      setCheckin = function(day) {

        /*入住日期 */
        var checkinDate, checkoutDate, checkoutOffset;
        checkinDate = toDate.apply(null, $checkin.val().split('/'));

        /*入住日期加1 */
        checkinDate = dateAddDay(checkinDate, day);
        if (checkinDate <= maxCheckinDate) {
          renderCheckinDate(currDate, checkinDate);
          $checkin.val(toDateString(checkinDate));

          /*更新离店日期 */
          checkoutDate = toDate.apply(null, $checkout.val().split('/'));
          checkoutOffset = getDayOffset(checkinDate, checkoutDate);
          if (checkoutOffset <= 0) {
            setCheckout(1);
          } else if (checkoutOffset > 60) {
            setCheckout(-1);
          } else {
            setCheckout(0);
          }
        }
      };
      setCheckout = function(day) {

        /*入住日期 */
        var checkinDate, checkoutDate, checkoutOffset;
        checkinDate = toDate.apply(null, $checkin.val().split('/'));

        /*离店日期 */
        checkoutDate = toDate.apply(null, $checkout.val().split('/'));

        /*离店日期+1 */
        checkoutDate = dateAddDay(checkoutDate, day);
        checkoutOffset = getDayOffset(checkinDate, checkoutDate);

        /*判断离店日期 */
        if (checkoutOffset <= 60 && checkoutDate <= maxCheckOutDate) {
          renderCheckoutDate(checkinDate, checkoutDate);
          $checkout.val(toDateString(checkoutDate));
        }
      };
      $('.btn', $checkinDate).on('click', function(e) {
        var $self;
        $self = $(this);
        if (!$self.hasClass('gray')) {
          if ($self.hasClass('prev')) {
            setCheckin(-1);
          } else if ($self.hasClass('next')) {
            setCheckin(1);
          } else if ($self.hasClass('sub')) {
            setCheckout(-1);
          } else if ($self.hasClass('add')) {
            setCheckout(1);
          }
        }
        e.stopPropagation();
      });
    };

    /*}}} */
  })();

  /* 模块定义 */
  _n.app('home', {
    indexSepBefore: function() {
      var _call;
      _call = function() {
        var _height;
        _height = Math.min(window.innerHeight, document.documentElement.clientHeight);
        if (_height > 0) {
          return $('.IN_sep, .IN_sep .frame').css('height', _height);
        }
      };
      _call();
      $('window').on('resize', _call);
      $('.IN_sep .frame .icon_scrolltop').on('click', function() {
        var $self, type;
        $self = $(this);
        type = $self.data('type');
        if (type === 'brand') {
          $('.IN_sep .brand_frame').addClass('show');
        } else {
          $('.IN_sep .brand_frame').removeClass('show');
        }
      });
    },
    init: function() {},
    indexBefore: function() {
      (_func.hotelCountAnimate = function() {
        var _fn;
        _fn = function(call, state) {
          var $hotelinfo, add, count, currCount, currState, region;
          if (state == null) {
            state = null;
          }
          $hotelinfo = $('.topblock .hotelinfo');
          currState = $hotelinfo.data('state');
          if (state && state !== currState) {
            return void 0;
          }
          count = parseInt($hotelinfo.data('count'));
          currCount = parseInt($hotelinfo.data('currCount')) || 0;
          region = $('.switch').data('region');
          if (region === 'local') {
            add = parseInt(Math.random() * 20) + 8;
          } else {
            add = parseInt(Math.random() * 40) + 8;
          }
          if (currCount + add > count) {
            currCount = count;
          } else {
            currCount += add;
          }
          if (region === 'local') {
            $hotelinfo.html("附近共<b>" + currCount + "</b>家酒店");
          } else {
            $hotelinfo.html("全国共<b>" + currCount + "</b>家酒店");
          }
          $hotelinfo.data('currCount', currCount);
          if (currCount < count) {
            if (window.requestAnimationFrame) {
              requestAnimationFrame(function() {
                call(call, currState);
              });
            } else {
              setTimeout(function() {
                call(call, currState);
              }, 16);
            }
          }
        };
        _fn(_fn);
      })();
    },
    indexAfter: function() {
      var load;
      $('.topblock .switch').ctap(function() {
        var $query, $self;
        $self = $(this);
        $query = $("#btnQuery");
        if ($self.data('init') === 'inited') {
          return false;
        }
        $self.data('init', 'inited');
        _n.hook('switchRegion', [
          $self, function() {
            var targetSrc;
            if ($self.data('region') === 'local') {
              $self.find('.local').show();
              $self.find('.flag').hide();
              $self.data('region', 'flag');
              $('#querybtn').html('酒店查询');
            } else {
              $self.find('.local').hide();
              $self.find('.flag').show();
              $self.data('region', 'local');
              $('#querybtn').html('附近<br />酒店查询');
            }
            targetSrc = $query.data('src');
            $query.data('src', $query.attr('href'));
            $query.attr('href', targetSrc);
            return $self.data('init', '');
          }
        ]);
      });
      $('.topblock .switch, .topblock .link').on('touchstart', function() {
        var $self;
        $self = $(this);
        $self.addClass('hover');
        setTimeout(function() {
          $self.removeClass('hover');
        }, 600);
      }).on('touchend', function() {
        $(this).removeClass('hover');
      });
      $('.quicklink .item, .quicklink .query').on('touchstart', function() {
        var $self;
        $self = $(this);
        $self.addClass('hover');
        setTimeout(function() {
          $self.removeClass('hover');
        }, 600);
      }).on('touchend', function() {
        $(this).removeClass('hover');
      });
      (load = function() {
        var $cover, $imgs, index, item, nav, _i, _len;
        return void 0;
        nav = '';
        $imgs = $('.IN_banner .item img');
        for (index = _i = 0, _len = $imgs.length; _i < _len; index = ++_i) {
          item = $imgs[index];
          if (index === 0) {
            nav += "<span class='cycle selected'></span>";
          } else {
            nav += "<span class='cycle'></span>";
          }
        }
        $('.IN_banner #ban-nav').html(nav);
        $('.IN_banner .defaultimg').hide();
        $('.IN_banner .Cbanner').show();
        _func.initSwipe();
        $cover = $('.IN_banner .cover');
        if ($cover.length) {
          $cover.on('click', function() {
            var $self;
            $self = $(this);
            index = $self.data('index');
            $self.siblings('.inner').find('.items').eq(0).find('a').eq(index).ctrigger('click');
          });
        }
      })();
    }
  });
  _n.app('hotel', {
    indexAfter: function() {
      var $checkinDate, url;
      _func.checkinDate();
      $checkinDate = $('#checkinDate');
      url = $checkinDate.data('url');
      $checkinDate.find('.item').on('click', function(e) {
        var CheckInDate, CheckOutDate, urlParam;
        CheckInDate = $('#' + $checkinDate.data('checkin')).val();
        CheckOutDate = $('#' + $checkinDate.data('checkout')).val();
        urlParam = $.param({
          CheckInDate: CheckInDate,
          CheckOutDate: CheckOutDate
        });
        if (url.indexOf('?') >= 0) {
          url += '&' + urlParam;
        } else {
          url += '?' + urlParam;
        }
        _n.visit(url);
      });

      /*checkin count */
      (function() {
        var $checkin;
        $checkin = $('#checkin_count_info');
        $checkin.on('click', function() {
          var $checkin_count;
          $checkin_count = $('#checkin_count');
          if ($checkin_count.hasClass('Ldn')) {
            $checkin_count.removeClass('Ldn');
          } else {
            $checkin_count.addClass('Ldn');
          }
        });
        _func.checkinCount();
      })();
    },
    dateAction: function() {
      var $choice, $month_label, cdate, daylen, offset;
      _func.getMonthDays = function(offset, week, month) {
        var cdate, cday, cdays, clday, cmonth, cyear, eweek, fweek, i, lweek, ndayslen, pdate, pdayslen, ret, _i, _j, _k, _ref, _ref1;
        if (month == null) {
          month = 0;
        }
        cdate = new Date(offset);
        cday = 0;
        if (month === 0) {
          cday = cdate.getDate();
        } else {
          cdate.setDate(1);
          cdate.setMonth(cdate.getMonth() + month);
          offset = cdate.getTime();
        }
        cyear = cdate.getFullYear();
        cmonth = cdate.getMonth();
        cdays = [];
        pdate = new Date(offset);
        pdate.setDate(1);
        fweek = pdate.getDay();
        if (fweek < week) {
          fweek += 7;
        }
        pdayslen = fweek - week;
        pdate.setDate(0);
        if (pdayslen > 0) {
          for (i = _i = _ref = pdate.getDate() - pdayslen + 1, _ref1 = pdate.getDate(); _ref <= _ref1 ? _i <= _ref1 : _i >= _ref1; i = _ref <= _ref1 ? ++_i : --_i) {
            cdays.push(i);
          }
        }
        if (cday) {
          cday += cdays.length;
        }
        cdate.setDate(1);
        cdate.setMonth(cdate.getMonth() + 1);
        cdate.setDate(0);
        clday = cdate.getDate();
        for (i = _j = 1; 1 <= clday ? _j <= clday : _j >= clday; i = 1 <= clday ? ++_j : --_j) {
          cdays.push(i);
        }
        lweek = cdate.getDay();
        eweek = week - 1;
        if (eweek === 0) {
          eweek = 7;
        }
        if (lweek > eweek) {
          lweek -= 7;
        }
        ndayslen = eweek - lweek;
        if (ndayslen > 0) {
          for (i = _k = 1; 1 <= ndayslen ? _k <= ndayslen : _k >= ndayslen; i = 1 <= ndayslen ? ++_k : --_k) {
            cdays.push(i);
          }
        }
        return ret = {
          year: cyear,
          month: cmonth,
          day: cday,
          days: cdays,
          start: pdayslen,
          end: ndayslen
        };
      };
      _func.initCalendar = function(offset, range, lockstart) {
        var calendarhtml, cday, choice, cmonth, currdate, currentday, day, dayText, daycount, disable, i, month, sday, _i, _len, _ref;
        if (range == null) {
          range = 90;
        }
        if (lockstart == null) {
          lockstart = false;
        }
        currentday = new Date(parseInt($('.SD_main').data('date')));
        currdate = new Date(parseInt(_vars.currday));
        daycount = 0;
        month = 0;
        calendarhtml = '';
        cday = 0;
        sday = 0;
        if (!offset) {
          return void 0;
        }
        while (daycount + 1 < range) {
          cmonth = _func.getMonthDays(offset, 7, month);
          month += 1;
          calendarhtml += '<div class="month Ltac Lmt20">' + cmonth.year + '年' + (cmonth.month + 1) + '月</div>';
          calendarhtml += '<div class="calendar Lmt10">';
          calendarhtml += '<div class="head pure-g">\n    <div class="pure-u-1-8">日</div>\n    <div class="pure-u-1-8">一</div>\n    <div class="pure-u-1-8">二</div>\n    <div class="pure-u-1-8">三</div>\n    <div class="pure-u-1-8">四</div>\n    <div class="pure-u-1-8">五</div>\n    <div class="pure-u-1-8">六</div>\n</div>';
          _ref = cmonth.days;
          for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
            day = _ref[i];
            if ((i + 1) % 7 === 1) {
              calendarhtml += '<div class="pure-g">';
            }
            disable = '';
            choice = '';
            dayText = day;
            if (cmonth.day !== 0) {
              if (i < cmonth.day - 1) {
                disable = ' disable';
              }
              if (i + 1 === cmonth.day) {
                disable = ' enable';
                sday = 1;
              }
            }
            if ((i < cmonth.start) || (i > cmonth.days.length - cmonth.end - 1)) {
              disable = ' disable';
              dayText = '&nbsp;';
            } else {
              if (cmonth.year === currentday.getFullYear() && cmonth.month === currentday.getMonth() && day === currentday.getDate()) {
                dayText = '今天';
                if (!lockstart) {
                  disable = ' curr';
                }
                cday = 1;
              }
              if (cday === 2) {
                dayText = '明天';
              }
              if (cday === 3) {
                dayText = '后天';
              }
              if (cmonth.year === currdate.getFullYear() && cmonth.month === currdate.getMonth() && day === currdate.getDate()) {
                choice = ' choice';
                dayText += '<span>入住</span>';
              }
              if (cday) {
                cday += 1;
              }
            }
            if (!disable) {
              daycount += 1;
            }
            if (daycount >= range) {
              disable = ' disable';
              dayText = '&nbsp;';
            }
            calendarhtml += "<div class=\"pure-u-1-8\"><div data-date=\"" + cmonth.year + "-" + (cmonth.month + 1) + "-" + day + "\" class=\n\"day" + disable + choice + (disable.indexOf('disable') < 0 ? ' enable' : '') + "\">" + dayText + "</div></div>";
            if ((i + 1) % 7 === 0) {
              calendarhtml += '</div>';
              if (daycount >= range) {
                break;
              }
            }
          }
          calendarhtml += '</div>';
        }
        $('.SD_main').html(calendarhtml);
      };
      cdate = parseInt($('.SD_main').data('date'));
      if (_vars.lockstart) {
        offset = parseInt(_vars.startday);
        daylen = parseInt(_vars.daylen);
        if (offset < cdate) {
          daylen -= Math.floor((cdate - offset) / 1000 / 3600 / 24);
          if (daylen > 90) {
            daylen = 90;
          }
          offset = cdate;
        }
        if (parseInt(_vars.currday) < offset) {
          _vars.currday = offset;
        }
        _func.initCalendar(offset, daylen, _vars.lockstart);
      } else {
        _func.initCalendar(cdate);
      }
      $('.calendar .day.enable').on('click', function() {
        var $self;
        $self = $(this);
        if (!$self.hasClass('disable')) {
          $.post(_vars.hotelParaSetUrl, {
            checkInDate: $self.data('date')
          }, function() {
            return _n.visit("" + _vars.dateurl);
          });
        } else {
          return false;
        }
      });
      $choice = $('.calendar .day.choice');
      $month_label = $choice.parents('.calendar').prev('.month');
      if ($month_label.length) {
        setTimeout(function() {
          window.scrollTo(0, $month_label.offset().top);
        }, 100);
      }
    },
    calendarAction: function() {
      var $choice, $disableDay, $month_label, cdate, daylen, offset, _hide, _newdate, _show;
      _func.getMonthDays = function(offset, week, month) {
        var cdate, cday, cdays, clday, cmonth, cyear, eweek, fweek, i, lweek, ndayslen, pdate, pdayslen, ret, _i, _j, _k, _ref, _ref1;
        if (month == null) {
          month = 0;
        }
        cdate = new Date(offset);
        cday = 0;
        if (month === 0) {
          cday = cdate.getDate();
        } else {
          cdate.setDate(1);
          cdate.setMonth(cdate.getMonth() + month);
          offset = cdate.getTime();
        }
        cyear = cdate.getFullYear();
        cmonth = cdate.getMonth();
        cdays = [];
        pdate = new Date(offset);
        pdate.setDate(1);
        fweek = pdate.getDay();
        if (fweek < week) {
          fweek += 7;
        }
        pdayslen = fweek - week;
        pdate.setDate(0);
        if (pdayslen > 0) {
          for (i = _i = _ref = pdate.getDate() - pdayslen + 1, _ref1 = pdate.getDate(); _ref <= _ref1 ? _i <= _ref1 : _i >= _ref1; i = _ref <= _ref1 ? ++_i : --_i) {
            cdays.push(i);
          }
        }
        if (cday) {
          cday += cdays.length;
        }
        cdate.setDate(1);
        cdate.setMonth(cdate.getMonth() + 1);
        cdate.setDate(0);
        clday = cdate.getDate();
        for (i = _j = 1; 1 <= clday ? _j <= clday : _j >= clday; i = 1 <= clday ? ++_j : --_j) {
          cdays.push(i);
        }
        lweek = cdate.getDay();
        eweek = week - 1;
        if (eweek === 0) {
          eweek = 7;
        }
        if (lweek > eweek) {
          lweek -= 7;
        }
        ndayslen = eweek - lweek;
        if (ndayslen > 0) {
          for (i = _k = 1; 1 <= ndayslen ? _k <= ndayslen : _k >= ndayslen; i = 1 <= ndayslen ? ++_k : --_k) {
            cdays.push(i);
          }
        }
        return ret = {
          year: cyear,
          month: cmonth,
          day: cday,
          days: cdays,
          start: pdayslen,
          end: ndayslen
        };
      };
      _func.initCalendar = function(offset, range, lockstart) {
        var calendarhtml, cday, checkin, checkincss, checkout, checkoutcss, checkoutdate, checkouttimetoken, choice, cmonth, currdate, currentday, currtimetoken, currtoken, day, dayText, daycount, disable, high, highcss, i, lastDay, month, sday, _i, _len, _ref;
        if (range == null) {
          range = 90;
        }
        if (lockstart == null) {
          lockstart = false;
        }
        currentday = new Date(parseInt($('.CD_main').data('date')));
        currdate = new Date(parseInt(_vars.currday));
        currtimetoken = +new Date(currdate.getFullYear(), currdate.getMonth(), currdate.getDate());
        checkoutdate = new Date(parseInt(_vars.checkoutdate));
        checkouttimetoken = +new Date(checkoutdate.getFullYear(), checkoutdate.getMonth(), checkoutdate.getDate());
        daycount = 0;
        month = 0;
        calendarhtml = '';
        cday = 0;
        sday = 0;
        checkin = '';
        checkout = '';
        high = '';
        if (!offset) {
          return void 0;
        }
        while (daycount + 1 < range) {
          cmonth = _func.getMonthDays(offset, 7, month);
          month += 1;
          calendarhtml += '<div class="month Ltac">' + cmonth.year + '年' + (cmonth.month + 1) + '月</div>';
          calendarhtml += '<div class="calendar">';
          _ref = cmonth.days;
          for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
            day = _ref[i];
            if ((i + 1) % 7 === 1) {
              calendarhtml += '<div class="pure-g">';
            }
            disable = '';
            choice = '';
            dayText = day;
            lastDay = '';
            currtoken = +new Date(cmonth.year, cmonth.month, day);
            if (cmonth.day !== 0) {
              if (i < cmonth.day - 1) {
                disable = ' disable';
              }
              if (i + 1 === cmonth.day) {
                disable = ' enable';
                sday = 1;
              }
            }
            if ((i < cmonth.start) || (i > cmonth.days.length - cmonth.end - 1)) {
              disable = ' disable';
              dayText = '&nbsp;';
            } else {
              if (cmonth.year === currentday.getFullYear() && cmonth.month === currentday.getMonth() && day === currentday.getDate()) {
                dayText = '<i>今天</i>';
                if (!lockstart) {
                  disable = ' curr';
                }
                cday = 1;
              }
              if (cday === 2) {
                dayText = '<i>明天</i>';
              }
              if (cday === 3) {
                dayText = '<i>后天</i>';
              }
              if (currtoken === currtimetoken) {
                choice = ' choice';
                dayText += '<span>入住</span>';
                checkin = ' checkin';
              }
              if (currtoken === checkouttimetoken) {
                choice = ' choice';
                dayText += '<span>离店</span>';
                high = '';
                checkout = ' checkout';
              }
              if (cday) {
                cday += 1;
              }
            }
            if (!disable) {
              daycount += 1;
            }
            if (daycount >= range) {
              disable = ' disable';
              dayText = '&nbsp;';
            }
            if (daycount + 1 === range) {
              lastDay = ' lastday';
            }
            highcss = high;
            checkincss = checkin;
            checkoutcss = checkout;
            if (disable && 0) {
              highcss = '';
              checkincss = '';
              checkoutcss = '';
            }
            calendarhtml += "<div class='pure-u-1-8" + checkincss + highcss + " " + checkoutcss + "'>";
            if (dayText !== '&nbsp;') {
              calendarhtml += "<div data-date=\"" + cmonth.year + "-" + (cmonth.month + 1) + "-" + day + "\"\nclass=\"day" + disable + choice + lastDay + (disable.indexOf('disable') < 0 ? ' enable' : '') + "\"\n>" + dayText + "</div>";
            } else {
              if (daycount === range) {
                calendarhtml += "<div data-date=\"" + cmonth.year + "-" + (cmonth.month + 1) + "-" + day + "\"\nclass='day" + disable + "'>" + day + "</div>";
              } else {
                calendarhtml += "<div class='day" + disable + "'> </div>";
              }
            }
            calendarhtml += "</div>";
            if (checkin) {
              high = ' high';
              checkin = '';
            }
            if (checkout) {
              checkout = '';
            }
            if ((i + 1) % 7 === 0) {
              calendarhtml += '</div>';
              if (daycount >= range) {
                break;
              }
            }
          }
          calendarhtml += '</div>';
        }
        $('.CD_main .inner').html(calendarhtml);
      };
      cdate = parseInt($('.CD_main').data('date'));
      if (_vars.lockstart) {
        offset = parseInt(_vars.startday);
        daylen = parseInt(_vars.daylen);
        if (offset < cdate) {
          daylen -= Math.floor((cdate - offset) / 1000 / 3600 / 24);
          if (daylen > 90) {
            daylen = 90;
          }
          offset = cdate;
        }
        if (parseInt(_vars.currday) < offset) {
          _vars.currday = offset;
        }
        _func.initCalendar(offset, daylen, _vars.lockstart);
      } else {
        _func.initCalendar(cdate);
      }
      $disableDay = $('.CD_main .checkout .disable');
      if ($disableDay.length) {
        $disableDay.parent().removeClass('checkout');
      }
      _func.highCheck = function(start, end, style, type) {
        var $nextweek, curr, currmonth, higharr, item, next, _i, _len;
        if (style == null) {
          style = 'high';
        }
        if (type == null) {
          type = 'parent';
        }
        higharr = [];
        if (start.length && end.length) {
          curr = start;
          while (curr[0] !== end[0]) {
            next = curr.next();
            if (!next.length) {
              $nextweek = curr.parent().next();
              if ($nextweek.length) {
                next = $nextweek.find('.day').eq(0).parent();
              } else {
                currmonth = curr.parent().parent().next();
                if (currmonth.length) {
                  next = currmonth.next().find('.day').eq(0).parent();
                } else {
                  return void 0;
                }
              }
            }
            if (next[0] !== end[0]) {
              if (type === 'day') {
                higharr.push(next.find('.day'));
              } else {
                higharr.push(next);
              }
            }
            curr = next;
          }
          for (_i = 0, _len = higharr.length; _i < _len; _i++) {
            item = higharr[_i];
            item.addClass(style);
          }
          return true;
        }
      };
      _func.spantext = function(obj, text) {
        var span;
        span = obj.find('span');
        if (span.length) {
          span.text(text);
        } else {
          $("<span>" + text + "</span>").appendTo(obj);
        }
        if (text === '入住') {
          _show('请选择离店日期');
        }
      };
      _newdate = function(str) {
        var dateArr;
        dateArr = str.split('-');
        if (dateArr.length >= 3) {
          dateArr[1] = parseInt(dateArr[1]) - 1;
          return new Date(dateArr[0], dateArr[1], dateArr[2]);
        }
      };
      _hide = function(force) {
        var $tips, hidetick;
        if (force == null) {
          force = false;
        }
        $tips = $('.CD_main .inputtips');
        if (!force) {
          $tips.addClass('trans fade');
          hidetick = setTimeout(function() {
            $tips.hide();
            $tips.hide().removeClass('trans fade');
            _stor('inputtips_hide', false, true);
          }, 200);
          return _stor('inputtips_hide', hidetick, true);
        } else {
          return $tips.hide();
        }
      };
      _show = function(text) {
        var $tips, tick, timeToken;
        tick = _stor('inputtips_tick');
        if (tick) {
          clearTimeout(tick);
        }
        timeToken = +new Date();
        $tips = $('.CD_main .inputtips');
        $tips.find('.cbox').text(text);
        $tips.removeClass('trans fade').show();
        tick = setTimeout(function() {
          _hide();
          return _stor('inputtips_tick', false, true);
        }, 2000);
        _stor('inputtips_tick', tick, true);
      };
      _show('请选择入住日期');
      _func.addPageBeforeUnloadCall = function() {
        var hide, tick;
        tick = _stor('inputtips_tick');
        hide = _stor('inputtips_hide');
        if (tick !== null) {
          clearTimeout(tick);
        }
        if (hide !== null) {
          clearTimeout(hide);
        }
        _hide(true);
      };
      _func.addPageRestoreCall = function() {
        _show('请选择入住日期');
        _stor('calendar_date_change', false, true);
        _stor('calendar_date_choice', {
          start: false,
          end: false
        }, true);
      };

      /*用户是否已改变时间 */
      _stor('calendar_date_change', false, true);
      _stor('calendar_date_choice', {
        start: false,
        end: false
      }, true);
      $('.calendar .day.enable').on('click', function() {
        var $checkOutRangeEnd, $checkin, $checkout, $self, checkOutRangeDate, checkinDays, choicedate, currdate, datechange, datechoice, enddate, prevdate;
        $self = $(this);
        if (!$self.hasClass('disable')) {
          choicedate = $self.data('date');
          datechange = _stor('calendar_date_change');
          datechoice = _stor('calendar_date_choice');
          if (!datechange) {
            $('.calendar .choice').removeClass('choice');
            $('.calendar .checkin').removeClass('checkin');
            $('.calendar .checkout').removeClass('checkout');
            $('.calendar .high').removeClass('high');
            $('.calendar .checkoutrange').removeClass('checkoutrange');
            datechange = true;
            datechoice.start = choicedate;
            if ($self.hasClass('lastday')) {
              enddate = new Date(1000 * 3600 * 24 + (+_newdate(choicedate)));
              datechoice.end = "" + (enddate.getFullYear()) + "-" + (enddate.getMonth() + 1) + "-" + (enddate.getDate());
            } else {
              checkOutRangeDate = (1000 * 3600 * 24 * 61) + +_newdate(choicedate);
              checkOutRangeDate = new Date(checkOutRangeDate);
              $checkOutRangeEnd = $(".calendar .day[data-date='" + (checkOutRangeDate.getFullYear()) + "-" + (checkOutRangeDate.getMonth() + 1) + "-" + (checkOutRangeDate.getDate()) + "']");
              _func.highCheck($self.parent(), $checkOutRangeEnd.parent(), 'checkoutrange');
              $self.addClass('choice');
              _func.spantext($self, '入住');
            }
          } else {
            prevdate = +_newdate(datechoice.start);
            currdate = +_newdate(choicedate);
            if (!datechoice.end) {
              if (currdate < prevdate) {
                $('.calendar .choice').removeClass('choice');
                $self.addClass('choice');
                _func.spantext($self, '入住');
                datechoice.start = choicedate;
                $('.calendar .checkoutrange').removeClass('checkoutrange');
                checkOutRangeDate = (1000 * 3600 * 24 * 60) + +_newdate(choicedate);
                checkOutRangeDate = new Date(checkOutRangeDate);
                $checkOutRangeEnd = $(".calendar .day[data-date='" + (checkOutRangeDate.getFullYear()) + "-" + (checkOutRangeDate.getMonth() + 1) + "-" + (checkOutRangeDate.getDate()) + "']");
                _func.highCheck($self.parent(), $checkOutRangeEnd.parent(), 'checkoutrange');
              } else if (currdate > prevdate) {
                checkinDays = (currdate - prevdate) / 1000 / 3600 / 24;
                if (checkinDays > 60) {
                  _show('入住日期超过最大限制');
                  return void 0;
                }
                _func.spantext($self, '离店');
                $checkin = $('.calendar .choice').parent();
                $checkout = $self.addClass('choice').parent();
                if (_func.highCheck($checkin, $checkout)) {
                  $checkin.addClass('checkin');
                  $checkout.addClass('checkout');
                  datechoice.end = choicedate;
                }
              }
            }
          }
          _stor('calendar_date_change', datechange, true);
          _stor('calendar_date_choice', datechoice, true);
          if (datechoice.end) {
            $.post(_vars.hotelParaSetUrl, {
              checkInDate: datechoice.start,
              checkOutDate: datechoice.end
            }, function() {
              return _n.visit("" + _vars.dateurl);
            });
          }
        } else {
          return false;
        }
      });
      $choice = $('.calendar .day.choice');
      $month_label = $choice.parents('.calendar').prev('.month');
      if ($month_label.length) {
        setTimeout(function() {
          window.scrollTo(0, $month_label.offset().top);
        }, 100);
      }
    },
    keywordBefore: function() {
      var $activetab, $items, $rarea, $tablist, active, changecall, height, _fixsubtabheight, _refresh_scroll;
      $rarea = $('.keytab .rarea');
      $items = $rarea.find('.tabitem');

      /*强刷iscroll */
      _refresh_scroll = function() {
        var keyword_scroll, scroll, _i, _len, _results;
        keyword_scroll = _stor('keyword_scroll') || [];
        _results = [];
        for (_i = 0, _len = keyword_scroll.length; _i < _len; _i++) {
          scroll = keyword_scroll[_i];
          _results.push(scroll.refresh());
        }
        return _results;
      };

      /*修改高 */
      _fixsubtabheight = function(index, item) {
        var $expandtab, $item, $subtabs, headheight, height, keyworkdsheight;
        height = document.documentElement.clientHeight - $rarea.offset().top;
        $item = $(item);
        $subtabs = $item.find('.subtab');

        /*修改多tab */
        if ($subtabs.length >= 1) {

          /*init height */
          headheight = $subtabs.find('.tabhead').eq(0).height();
          keyworkdsheight = height - $subtabs.length * headheight;
          $subtabs.find('.inner').css('height', keyworkdsheight);

          /* init switch */
          if (!$item.data('init')) {
            $subtabs.addClass('shrink').find('.down').addClass('reverse');
            $subtabs.find('.tabhead').on('click', function() {
              var $inner, $prevtab, $tab, keyword_scroll;
              $tab = $(this).parent();
              if ($tab.hasClass('shrink')) {
                $prevtab = $tab.siblings('.expand');
                $prevtab.removeClass('expand').addClass('shrink').find('.down').addClass('reverse');
                $tab.removeClass('shrink').addClass('expand').find('.down').removeClass('reverse');
                _refresh_scroll();
                $inner = $tab.find('.inner');
                if (!$inner.data('init') && !$.browser.ie) {
                  $inner.css('overflow-y', 'hidden');
                  keyword_scroll = _stor('keyword_scroll') || [];
                  keyword_scroll.push(new IScroll($inner.get(0), {
                    scrollbar: true,
                    click: true
                  }));
                  $inner.data('init', 'inited');
                  _stor('keyword_scroll', keyword_scroll, true);
                }
              } else {
                $tab.removeClass('expand').addClass('shrink').find('.down').addClass('reverse');
              }
            });
            $expandtab = $subtabs.filter('.defaultexpand');
            if ($expandtab.length < 1) {
              $expandtab = $subtabs.eq(0);
            }
            $expandtab.find('.tabhead').ctrigger('click');
          }
        } else {

          /*修改单tab */
          $item.find('.inner').css('height', height);
        }
        $item.data('init', 'inited');
      };
      height = document.documentElement.clientHeight - $rarea.offset().top;
      $items.css('height', height).each(_fixsubtabheight);
      _func.searchClean();

      /*tab切换 */
      $('.tablist .tab').ctap(function() {
        var $inner, $self, $tabitem, index, keyword_scroll;
        $self = $(this);
        if (!$self.hasClass('curr')) {
          index = $self.index();
          $self.siblings('.curr').removeClass('curr');
          $self.addClass('curr');
          $tabitem = $('.keytab .tabitem').hide().eq(index);
          $tabitem.show();
          $tabitem.each(_fixsubtabheight);
          _refresh_scroll();
          $inner = $tabitem.find('.inner');
          if ($inner.length === 1 && !$inner.data('init')) {
            $inner.css('overflow-y', 'hidden');
            keyword_scroll = _stor('keyword_scroll') || [];
            keyword_scroll.push(new IScroll($inner.get(0), {
              scrollbar: true,
              click: true
            }));
            _stor('keyword_scroll', keyword_scroll, true);
            $inner.data('init', 'inited');
          }
          _stor('keyword_tab_index', index, true);
        }
      });
      $tablist = $('.tablist');
      active = $tablist.data('active');
      if (active) {
        $activetab = $tablist.find("[data-tab-type=" + active + "]");
      } else {
        $activetab = $tablist.find('.tab').eq(0);
      }
      if ($activetab.length) {
        $activetab.ctrigger('click');
      }

      /*页面高改变时 */
      changecall = function() {
        height = document.documentElement.clientHeight - $rarea.offset().top;
        $items.css('height', height).each(_fixsubtabheight);
        return _refresh_scroll();
      };
      $(window).on('resize', changecall);
      window.addEventListener('orientationchange', changecall);
    },
    keywordAfter: function() {
      _func.initSearchSuggest = function() {
        var $input, recordnum, _height;
        $input = $('.searchkey');
        if (!$input.data('initSuggest')) {
          $input.data('initSuggest', true);
          _height = $('.keytab .rarea').height();
          recordnum = Math.ceil(_height / 39);
          AMap.service(['AMap.PlaceSearch'], function() {
            var placeSearch;
            placeSearch = new AMap.PlaceSearch({
              pageSize: recordnum,
              city: _vars.currcity
            });
            $input.on('input', function() {
              var searchkey, _token;
              searchkey = $(this).val();
              _token = +new Date();
              _vars.lasttoken = _token;
              if (!searchkey.length) {
                $('.topbox .suggest').hide();
                return void 0;
              }
              setTimeout(function() {
                if (_token !== _vars.lasttoken) {
                  return void 0;
                }
                placeSearch.search(searchkey, function(status, result) {
                  var $suggest, address, index, item, keyreg, keyword, labeltext, name, param, url, _html, _i, _len, _ref;
                  _html = '';
                  $suggest = $('.topbox .suggest');
                  if (status !== 'complete') {
                    $suggest.hide();
                    return void 0;
                  }
                  if (_token !== _vars.lasttoken) {
                    return void 0;
                  }
                  keyreg = new RegExp("(" + (searchkey.replace(/(\(|\))/g, '\\$1')) + ")");
                  _ref = result.poiList.pois;
                  for (index = _i = 0, _len = _ref.length; _i < _len; index = ++_i) {
                    item = _ref[index];
                    name = item.name.replace(keyreg, "<b>$1<\/b>");
                    if (item.name.indexOf('(') < 0 && item.address) {
                      address = "(" + (item.address.replace(/\(|\)/g, function(char) {
                        if (char === '(') {
                          return ' ';
                        } else {
                          return '';
                        }
                      })) + ")";
                    } else {
                      address = '';
                    }
                    labeltext = "" + name + address;
                    keyword = "" + item.name + address;
                    param = ("keyword=" + (encodeURIComponent(keyword)) + "&LatLng=" + (item.location.getLat()) + "|" + (item.location.getLng())).replace('"', '\'');
                    url = "" + _vars.keywordurl + (_vars.keywordurl.indexOf('?') < 0 ? '?' : '&') + param;
                    _html += '<li class="item"><a class="Ctextover" data-params="' + url + '" href="javascript:;">' + labeltext + '</a></li>';
                  }
                  if (_html.length) {
                    $suggest.show().find('ul').html(_html);
                  }
                });
              }, 200);
            });
          });
        }
      };
      window.wrapMapCallback = function() {
        _func.initSearchSuggest();
      };
      if (!window.AMap) {
        _func.addScript(_vars.mapurl + '&callback=wrapMapCallback');
      } else {
        _func.initSearchSuggest();
      }
    },
    cityBefore: function() {
      (function() {
        return $('td.curr').each(function() {
          _func.tdCurrStyle($(this), 'add');
        });
      })();
      _func.searchClean();
      $('table td').on('click', function() {
        var $self;
        $self = $(this);
        if (!$self.hasClass('curr')) {
          _func.tdCurrStyle($self.parents('table').find('td.curr').removeClass('curr'));
          $self.addClass('curr');
          _func.tdCurrStyle($self, 'add');
        }
      });
      $('table.chartable td').on('click', function() {
        var $a, index;
        index = $(this).data('index');
        if (index) {
          $a = $("a[name=" + index + "]");
          setTimeout(function() {
            var ctop, time, ttop;
            ctop = document.body.scrollTop;
            ttop = $a.offset().top;
            time = Math.max((ttop - ctop) / 3, 400);
            $.scrollTo({
              endY: $a.offset().top,
              duration: time
            });
          }, 100);
        }
      });
    },
    cityAfter: function() {
      var $city, _call;
      $city = $('.SC_main .currcity');
      $city.find('.cityname').text('正在获取...');

      /*获取当前城市 */
      _call = function(ok, cityname, trytrim) {
        var item, _i, _len, _ref;
        if (trytrim == null) {
          trytrim = true;
        }
        if (ok === 'success') {
          _ref = _vars.cities;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            item = _ref[_i];
            if (item.mc.indexOf(cityname) === 0) {
              $('<a href="javascript:;" class="item currcity" data-cityid="' + item.id + '" data-cityname="' + cityname + '">' + cityname + '</a>').appendTo('.citypos');
            }
            break;
          }
        }
        if (trytrim) {
          _call(ok, cityname.replace(/.$/, ''), false);
        }
      };
      _n.location.getCurrCity(_call);

      /*获取经纬度 */
      _n.location.getLngLat(function(ok, result) {
        if (_n.pageId === 'hotel/city') {
          if (ok === 'success') {
            $('.geopos').html('<a href="javascript:;" class="item currlocation" data-latlng="' + result.lat + '|' + result.lng + '">附近酒店</a>');
            $('.locationitem .tips').hide();
          } else {
            $('.geopos').html('<a href="javascript:;" class="item currlocation">获取当前位置失败</a>');
            alert(err);
          }
        }
      });
    },
    listAfter: function() {
      var $list;
      $list = $('.HL_main .hlist');
      if (parseInt($list.data('total')) > 1) {
        $('.HL_hlist .loadmore').datalazyload({
          repeat: true,
          loader: function() {
            _func.lazyLoadMore($('.HL_hlist .hlist'));
          }
        }).addClass('enable');
      }
      _func.lazyListImage($list);
      $('#checkin_edit').on('click', function() {
        var $edit, $info, $self;
        $self = $(this);
        $info = $self.parents('.info');
        $edit = $info.siblings('.edit');
        if ($edit.hasClass('Ldn')) {
          $edit.removeClass('Ldn');
          $info.addClass('Ldn');
        } else {
          $edit.addClass('Ldn');
          $info.removeClass('Ldn');
        }
      });
      _func.checkinCount();
    },
    rentAfter: function() {
      var $list, callback, loadmore;
      $('.notrace_banner .view a').on('click', function() {
        var $popup;
        $popup = $('.Cpopup.notrace_popup');
        if ($popup.data('init') !== 'inited') {
          $popup.data('init', 'inited');
          $popup.on('click', function() {
            return $(this).hide();
          }).on('touchmove', _func.preventScroll).find('.contentbox').on('click', function(e) {
            var $el;
            $el = $(e.target || e.srcElement);
            if (!$el.hasClass('std_large_button')) {
              if (typeof e.stopPropagation === "function") {
                e.stopPropagation();
              }
            }
          });
        }
        $popup.show();
      });
      $list = $('.HL_hlist .hlist');
      if (parseInt($list.data('total')) > 0) {
        callback = function() {
          var $items;
          $items = $list.find('.item');
          if ($items.length < 10) {
            loadmore();
          }
        };
        loadmore = function() {
          _func.lazyLoadMore($('.HL_hlist .hlist'), callback);
        };
        $('.HL_hlist .loadmore').datalazyload({
          repeat: true,
          loader: loadmore
        }).addClass('enable');
      }
      _func.lazyListImage($list);
    },
    nightAfter: function() {
      var $list, _getNowTime, _getTimeLeft, _initTimeCountDown, _prevTimeValue, _setNumValue, _updateHour, _updateMinute, _updateTimeBlock;
      $list = $('.HL_hlist .hlist');
      if (parseInt($list.data('total')) > 0) {
        $('.HL_hlist .loadmore').datalazyload({
          repeat: true,
          loader: function() {
            _func.lazyLoadMore($('.HL_hlist .hlist'));
          }
        }).addClass('enable');
      }
      _func.lazyListImage($list);
      _prevTimeValue = {
        hour: -1,
        minute: -1,
        second: -1
      };
      _getNowTime = function() {
        return +new Date() + _vars.nightServerTimeOffset;
      };
      _getTimeLeft = function() {
        var timeLeft;
        timeLeft = _vars.nightStartTime - _getNowTime();
        if (timeLeft < 0) {
          timeLeft = 0;
        }
        return timeLeft;
      };
      _setNumValue = function($el, num) {
        var $before, $num, ntext;
        $before = $el.find('.before');
        $num = $el.find('.num');
        ntext = num;
        if (num < 10) {
          ntext = "0" + num;
        }
        if (!$before.hasClass('lock')) {
          $before.text(ntext).addClass('trans show lock');
          setTimeout(function() {
            $num.text(ntext);
            return $before.removeClass('trans show lock');
          }, 200);
        }
      };
      _updateMinute = function() {
        var $timeblock, minute, second, timeLeft;
        $timeblock = $('.AN_desc .timeblock');
        if (!$timeblock.length) {
          return void 0;
        }
        timeLeft = Math.floor(_getTimeLeft() / 1000);
        minute = Math.floor(timeLeft / 60);
        second = Math.floor(timeLeft % 60);
        if (timeLeft < 1) {

        } else {
          if (minute !== _prevTimeValue.minute) {
            _setNumValue($('.timeblock .minute'), minute);
            _prevTimeValue.minute = minute;
          }
          if (second !== _prevTimeValue.second) {
            _setNumValue($('.timeblock .second'), second);
            _prevTimeValue.second = second;
          }
          _vars.nightTimeoutToken = setTimeout(function() {
            return _updateMinute();
          }, 1000);
        }
      };
      _updateHour = function() {
        var $timeblock, hour, minute, second, timeLeft;
        $timeblock = $('.AN_desc .timeblock');
        if (!$timeblock.length) {
          return void 0;
        }
        timeLeft = Math.floor(_getTimeLeft() / 1000);
        hour = Math.floor(timeLeft / 3600);
        minute = Math.ceil((timeLeft % 3600) / 60);
        if (hour < 1 || (minute === 0 && hour === 1)) {
          _updateTimeBlock('minute');
        } else {
          if (hour !== _prevTimeValue.hour) {
            _setNumValue($('.timeblock .hour'), hour);
            _prevTimeValue.hour = hour;
          }
          if (minute !== _prevTimeValue.minute) {
            _setNumValue($('.timeblock .minute'), minute);
            _prevTimeValue.minute = minute;
          }
          second = (timeLeft % 60) || 60;
          _vars.nightTimeoutToken = setTimeout(function() {
            return _updateHour();
          }, second * 1000);
        }
      };
      _updateTimeBlock = function(mode) {
        var $timeblock, _html;
        $timeblock = $('.AN_desc .timeblock');
        if ($timeblock.length) {
          if (mode === 'hour') {
            _html = "<span>距离开抢时间</span>\n<span class=\"timecard hour\">\n    <span class=\"card before\"></span>\n    <span class=\"card num\">00</span>\n</span>\n<span>小时</span>\n<span class=\"timecard minute\">\n    <span class=\"card before\"></span>\n    <span class=\"card num\">00</span>\n</span>\n<span>分钟</span>";
            $timeblock.html(_html);
            _updateHour(0, 0);
          } else {
            _html = "<span>距离开抢时间</span>\n<span class=\"timecard minute\">\n    <span class=\"card before\"></span>\n    <span class=\"card num\">00</span>\n</span>\n<span>分钟</span>\n<span class=\"timecard second\">\n    <span class=\"card before\"></span>\n    <span class=\"card num\">00</span>\n</span>\n<span>秒</span>";
            $timeblock.html(_html);
            _updateMinute(0, 0);
          }
        }
      };
      _initTimeCountDown = function() {
        var $timeblock, ctime, mode;
        $timeblock = $('.AN_desc .timeblock');
        _prevTimeValue.hour = -1;
        _prevTimeValue.minute = -1;
        _prevTimeValue.second = -1;
        if ($timeblock.length) {
          mode = 'hour';
          ctime = _getNowTime();
          if ((_vars.nightStartTime - ctime) < 3600 * 1000) {
            mode = 'minute';
          }
          _updateTimeBlock(mode);
        }
      };
      if (_func.isUseTurbolinks()) {
        _func.addRestoreCall('reNightTimeCountDown', function() {
          _initTimeCountDown();
        });
        _func.addBeforeUnloadCall('cleanNightTimeoutCallback', function() {
          clearTimeout(_vars.nightTimeoutToken);
        });
      }
      _initTimeCountDown();
    },
    detailBefore: function() {

      /* 展示图片 */
      $('.hotelinfo .img').on('click', function() {
        _func.pushState('image');
      });

      /* 展示分页 */
      $('.hotelintro>.item, .detailblock>.item').on('click', function() {
        var $self;
        $self = $(this);
        if ($self.data('link') === 'info') {
          _func.pushState('introduce|basic');
        } else if ($self.data('link') === 'address') {
          _func.pushState('map');
        }
      });
      $('.hotelintro .info').on('click', function() {
        var $self;
        $self = $(this);
        if ($self.data('link') === 'service') {
          _func.pushState('introduce|service');
        } else if ($self.data('link') === 'comment') {
          _func.pushState('introduce|comment');
        }
      });

      /* 展示地图 */
      $('.introblock .item[data-link=address]').on('click', function() {
        _func.pushState('map');
      });
      $('.Chead a.back').on('click', function() {
        var _href;
        _href = this.href;
        if (_href.match(/^javascript:/)) {
          if (_stor('initPushState')) {
            history.back();
          } else {
            if (_vars.showIntro && _stor('detailPage') === 'map') {
              _n.hook('pushStateHook', 'introduce');
            } else {
              _n.hook('pushStateHook', '');
            }
          }
        }
      });

      /* 查看详情 {{{ */
      $('.topblock .intro').ctap(function(e) {
        var $intro, ctop, time, ttop;
        $intro = $('.hotelintro');
        ctop = document.body.scrollTop;
        ttop = $intro.offset().top;
        time = Math.max((ctop - ttop) / 3, 400);
        if (time > 1000) {
          time = 1000;
        }
        setTimeout(function() {
          $.scrollTo({
            endY: ttop,
            duration: time
          });
        }, 100);
      });

      /* }}} */
      _func.dayselectchange = function() {
        var $form;
        $form = $('.btn_box form');
        $.post(_vars.dateurl, $form.serialize(), function(res) {});
      };
    },
    detailAfter: function() {

      /*时租房提示弹窗 */
      $('.Cpopup.calltel').on('click', function(e) {
        var $el;
        $el = $(e.target || e.srcElement);
        if ($el.hasClass('calltel')) {
          $el.addClass('Ldn');
        }
      }).find('.back, .link').on('click', function(e) {
        var $self;
        $self = $(this);
        if ($self.hasClass('link')) {
          _n.hook('trackEvent', [["时租房", "过时段_打电话", ndoo.vars.wa.hotel_id + "_" + ndoo.vars.wa.hotel_name]]);
        }
        $('.Cpopup.calltel').addClass('Ldn');
      });

      /*展开详情 */
      $('.priceblock>.item').on('click', function(e) {
        var $el, $self;
        $el = $(e.target || e.srcElement);
        if ($el.hasClass('roomtype') || $el.parents('.roomtype').length || $el.parents('.subinfo').length) {
          return void 0;
        }
        $self = $(this);
        if ($self.hasClass('disable')) {
          return void 0;
        }
        if ($self.hasClass('expand')) {
          $self.removeClass('expand');
          return $self.find('i.down').removeClass('highreverse');
        } else {
          $self.addClass('expand');
          $self.find('i.down').addClass('highreverse');
          $self.find('.subitem .centbox.member').eq(0).ctrigger('click');
        }
      });

      /* 查看房间详情 {{{ */
      (function() {
        _func.getLevelMember = function(data, level) {
          var item, ret, _i, _len;
          ret = false;
          for (_i = 0, _len = data.length; _i < _len; _i++) {
            item = data[_i];
            if (item.MemberLevel === level) {
              ret = item;
              break;
            }
          }
          return ret;
        };
        _func.getRoomImage = function(roomTypeId) {
          var item, ret, _i, _len, _ref;
          ret = [];
          _ref = _vars.hotelRoomTypeImg;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            item = _ref[_i];
            if (item.RoomTypeID && item.RoomTypeID === roomTypeId) {
              ret.push(item.ImageUrl);
            }
          }
          return ret;
        };
        _func.getDetailHtml = function(data, price, m) {
          var baseurl, cheap, cycle, detail, div, img, imgsize, info, member1_price, member2_price, member3_price, member4_price, priceData, roomImage, show_member_price, tpl, _i, _len;
          imgsize = '.640-240.jpg';
          info = data.Info;
          cheap = data.Cheapest;
          detail = data.Details;
          priceData = price.Data.PriceCalender;
          baseurl = _vars.bookurl.replace(/_RoomTypeID_/, cheap.RoomType).replace(/_ActivityID_/, cheap.ActivityID);
          if (priceData.length) {
            show_member_price = true;
            member1_price = _func.getLevelMember(priceData, 'P').DailyRoomPriceOfMemberList[0].Price || 0;
            member2_price = _func.getLevelMember(priceData, 'A').DailyRoomPriceOfMemberList[0].Price || 0;
            member3_price = _func.getLevelMember(priceData, 'B').DailyRoomPriceOfMemberList[0].Price || 0;
            member4_price = _func.getLevelMember(priceData, 'I').DailyRoomPriceOfMemberList[0].Price || 0;
            div = "<div class='pure-u-1-4'> <span class='label lev4'>铂金会员</span> <div class='price'><i>￥</i>" + member4_price + "</div> </div> <div class='pure-u-1-4'> <span class='label lev3'>金会员</span> <div class='price'><i>￥</i>" + member3_price + "</div> </div> <div class='pure-u-1-4'> <span class='label lev2'>银会员</span> <div class='price'><i>￥</i>" + member2_price + "</div> </div> <div class='pure-u-1-4'> <span class='label lev1'>星会员</span> <div class='price'><i>￥</i>" + member1_price + "</div> </div>";
            $(m).html(div);
            $(m).removeClass('Ldn');
          } else {
            show_member_price = false;
          }
          tpl = "<div class='Cmask Cpopup hoteldetail Lvh'>" + "   <div class='inner Cwrap'><div class='detailbox'>" + "     <a class='close'><span class='closecontent'><i class='Cicon close_small'></i></span></a>" + ("     <h3 class='title'>" + data.RoomTypeName + "</h3>") + "     <div class='content'><div class='iscroll_wrapper'>" + "       <div class='pure-g info'>" + ("         " + (info.RoomArea ? "<div class='pure-u-1-3'>面积：<span>" + info.RoomArea + "m</span></div>" : "<div class='pure-u-1-3'>&nbsp;</div>")) + ("         " + (info.Floor ? "<div class='pure-u-2-3'>所在楼层：<span>" + info.Floor + "</span></div>" : "<div class='pure-u-2-3'>&nbsp;</div>")) + "       </div>" + "       <div class='pure-g info'>" + ("         " + (info.Window ? "<div class='pure-u-1-3'>窗户：<span>" + info.Window + "</span></div>" : "<div class='pure-u-1-3'>&nbsp;</div>")) + ("         " + (info.MaxCheckInPeopleNum ? "<div class='pure-u-2-3'>入住人数：<span>" + info.MaxCheckInPeopleNum + "</span></div>" : "<div class='pure-u-2-3'>&nbsp;</div>")) + "       </div>" + "       <div class='pure-g info'>" + ("         " + (info.BedType ? "<div class='pure-u-1-3'>床型：<span>" + info.BedType + "</span></div>" : "<div class='pure-u-1-3'>&nbsp;</div>")) + ("         " + (info.BedSize ? "<div class='pure-u-2-3'>床宽：<span>" + info.BedSize + "米</span></div>" : "<div class='pure-u-2-3'>&nbsp;</div>")) + "       </div>" + ("       " + (info.SupportNetwork ? "<div class='info'>上网方式：<span>" + info.SupportNetwork + "</span></div>" : "")) + ("       " + (info.NoSmokingFloor ? "<div class='info'>无 烟 房：<span>" + info.NoSmokingFloor + "</span></div>" : "")) + ("       " + (info.Summary ? "<div class='info'>备　　注：<span>" + info.Summary + "</span></div>" : ""));
          roomImage = _func.getRoomImage(data.RoomTypeID);
          if (roomImage && roomImage.length) {
            cycle = '';
            tpl += "<div class='Cbanner'><div class='inner'><div class='items'>";
            for (_i = 0, _len = roomImage.length; _i < _len; _i++) {
              img = roomImage[_i];
              tpl += "<div class='item'><a href='#'><img src='" + img + imgsize + "' /></a></div>";
              if (cycle) {
                cycle += '<span class="cycle"></span>';
              } else {
                cycle += '<div class="ban-nav"><span class="cycle selected"></span>';
              }
            }
            cycle += '</div>';
            tpl += "</div></div>" + cycle + "</div>";
          }
          tpl += "     </div></div>";
          return tpl += "   </div></div>" + "</div>";
        };

        /* }}} */
        _n.hook('loadroomTyoeDetail', function(e, f, notShowDialog) {
          var $el, $self, activityId, item, roomData, roomTypeId, _i, _len, _ref;
          if (!_vars.rooms) {
            return void 0;
          }
          $self = $(e.target).parents('.item');
          $el = $(e.target || e.srcElement);
          if ($el.hasClass('Cbtn') || $self.hasClass('rentdetail')) {
            if ($el.hasClass('NotCanRent')) {
              _n.hook('trackEvent', [["时租房", "过时段_预订", ndoo.vars.wa.hotel_id + "_" + ndoo.vars.wa.hotel_name]]);
              $('.Cpopup.calltel').removeClass('Ldn');
              if (typeof e.stopPropagation === "function") {
                e.stopPropagation();
              }
              return false;
            }
            return void 0;
          }
          roomData = null;
          roomTypeId = $self.data('roomTypeId');
          activityId = $self.data('activityId');
          if (!$("#roomDetail" + roomTypeId).length) {
            _n.hook('loading');
            _ref = _vars.rooms;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              if (item.RoomTypeID === roomTypeId) {
                roomData = item;
                break;
              }
            }
            if (roomData) {
              return $.post(_vars.priceurl, {
                roomTypeID: roomTypeId,
                activityID: activityId
              }, function(data) {
                var $content, $dialog, $inner, tpl;
                if (data.ResultType !== 0) {
                  return void 0;
                }
                tpl = _func.getDetailHtml(roomData, data, $self.find('.subitem .memberclass'));
                $dialog = $(tpl);
                $dialog.attr('id', "roomDetail" + roomTypeId).appendTo('body');
                f();
                $dialog.on('click', function() {
                  $dialog.hide();
                }).find('.detailbox').on('click', function(e) {
                  $el = $(e.target || e.srcElement);
                  if (($el.hasClass('Cbtn') && !$el.attr('href').match(/^javascript:/)) || $el.hasClass('close') || $dialog.find('.close').find($el).length) {
                    return void 0;
                  }
                  return typeof e.stopPropagation === "function" ? e.stopPropagation() : void 0;
                });
                if ($dialog.hasClass('Lvh')) {

                  /*初始化滚动组件 */
                  _func.initHotelDetailSwipe($dialog);
                  $inner = $dialog.find('.inner');
                  $content = $dialog.find('.content');
                  if (!_func.isWP()) {
                    $content.css('overflow-y', 'hidden');
                    new IScroll($content.get(0), {
                      scrollbar: true,
                      click: true
                    });
                  }
                  if (notShowDialog) {
                    $dialog.addClass('Ldn');
                  }
                  $dialog.removeClass('Lvh');
                }
                _n.hook('hideloading');
              });
            }
          } else {
            f();
          }
        });
        $('.priceblock .roomtype').on('click', function(e) {
          return _n.hook('loadroomTyoeDetail', [
            e, function() {
              var $dialog, $roomTypeId;
              $roomTypeId = $(e.target).parents('.item');
              $dialog = $("#roomDetail" + $roomTypeId.data('roomTypeId'));
              return $dialog.show();
            }
          ]);
        });
        $('.priceblock .Cbtn').on('click', function(e) {
          var $el;
          $el = $(e.target || e.srcElement);
          if ($el.hasClass('Cbtn')) {
            if ($el.hasClass('NotCanRent')) {
              _n.hook('trackEvent', [["时租房", "过时段_预订", ndoo.vars.wa.hotel_id + "_" + ndoo.vars.wa.hotel_name]]);
              $('.Cpopup.calltel').removeClass('Ldn');
              if (typeof e.stopPropagation === "function") {
                e.stopPropagation();
              }
              return false;
            }
            return void 0;
          }
        });
      })();

      /* 图片异步加载 */
      _func.lazyListImage($('.HI_main>.item'));
      $('.introlab .item').ctap(function() {
        var $prev, $self, index;
        $self = $(this);
        if ($self.hasClass('empty')) {
          return void 0;
        }
        if (!$self.hasClass('curr')) {
          index = $self.index();
          $prev = $self.siblings('.curr');
          $prev.removeClass('curr').find('.text_c_66').removeClass('textFormBlue');
          $('.introblock>.item').eq($prev.index()).hide();
          $self.addClass('curr').find('.text_c_66').addClass('textFormBlue');
          $('.introblock>.item').eq(index).show();
          $('.comEvaluated').addClass('Ldn');
          $('.item_bd').addClass('Ldn');
          if (!!$self.hasClass('score')) {
            $('.comEvaluated').removeClass('Ldn');
            $('.item_bd').removeClass('Ldn');
          }
          _n.hook('scrollLit');
        }
      });

      /* }}} */
      _n.hook('hotelRouteMap', function() {
        setTimeout(function() {
          var $item, $map, $routes, height, item, key, lat, lng, map, markIcons, marker, _i, _len, _ref;
          $map = $('#routemap');
          height = window.innerHeight - $('.HM_main .routes').height() - $('#divMap .Chead').height();
          $map.css('height', height);
          lat = $map.data('lat');
          lng = $map.data('lng');

          /*添加mark图标 */
          markIcons = {};
          _ref = ['default', 'hanting', 'quanji', 'haiyou', 'manxin', 'xingcheng', 'xiyue', 'yilai', 'jingxuan', 'ibis'];
          for (key = _i = 0, _len = _ref.length; _i < _len; key = ++_i) {
            item = _ref[key];
            markIcons[item] = new AMap.Icon({
              size: new AMap.Size(60, 45),
              image: _vars.locationicon,
              imageSize: new AMap.Size(60, 500),
              imageOffset: new AMap.Pixel(0, 0 - key * 50)
            });
          }
          map = new AMap.Map('routemap', {
            dragEnable: true,
            resizeEnable: true,
            view: new AMap.View2D({
              center: new AMap.LngLat(lng, lat),
              zoom: 17
            })
          });
          marker = new AMap.Marker({
            map: map,
            icon: markIcons[_vars.brand],
            offset: new AMap.Pixel(-18, -45),
            position: new AMap.LngLat(lng, lat)
          });
          _stor('hotelRouteMap', {
            map: map,
            center: new AMap.LngLat(lng, lat),
            markIcons: markIcons,
            zoom: map.getZoom()
          });
          $routes = $('.HM_main .routes');
          $item = $routes.find('.inner .item');
          if ($item.length) {
            $item.on('click', function() {
              var $icon;
              $icon = $(this).find('.Cicon');
              if ($icon.hasClass('downarrow_small')) {
                $icon.parents('.addr').siblings('.info').show();
                $icon.removeClass('downarrow_small').addClass('uparrow_small');
              } else if ($icon.hasClass('uparrow_small')) {
                $icon.parents('.addr').siblings('.info').hide();
                $icon.removeClass('uparrow_small').addClass('downarrow_small');
              }
            });
          }
          $map.data('init', 'inited');
        }, 100);
      });

      /*warp callback */
      window.wrapMapCallback = function() {
        _n.hook('hotelRouteMap');
      };
      _func.initHotelRouteMap = function() {
        var $map, hotelmap;
        $map = $('#routemap');
        if (!$map.data('init')) {
          if (!window.AMap) {
            _func.addScript(_vars.mapurl + '&callback=wrapMapCallback');
          } else {
            _n.hook('hotelRouteMap');
          }
        } else {
          hotelmap = _stor('hotelRouteMap');
          hotelmap.map.setCenter(hotelmap.center);
          setTimeout(function() {
            hotelmap.map.setZoom(hotelmap.zoom);
          }, 1);
        }
      };

      /*push state */
      _func.initPushState(function(path) {
        var $items, paths, type;
        if (path.indexOf('introduce') > -1) {
          paths = path.split('|');
          path = paths[0];
          type = paths[1] || 'basic';
        }
        if (path === '') {
          if (_stor('detailPage') === 'introduce') {
            _vars.showIntro = false;
            $('#divIntroduce').hide();
            $('#divDetail').show();
            $('body').removeClass('bgWhite');
          }
          if (_stor('detailPage') === 'image') {
            $('#divImage').hide();
            $('#divDetail').show();
          }
          if (_stor('detailPage') === 'map') {
            if (_vars.showIntro === true) {
              $('#divMap').hide();
              $('#divIntroduce').show();
            } else {
              $('#divMap').hide();
              $('#divDetail').show();
            }
          }
          _stor('detailPage', 'detail', true);
        } else if (path === 'introduce') {
          if (_stor('detailPage') === 'map') {
            $('#divMap').hide();
          } else {
            $('#divDetail').hide();
          }
          $('#divIntroduce').addClass('Lvh').show();
          $items = $('#divIntroduce .introlab .item');
          if (type === 'service') {
            $items.eq(1).ctrigger('click');
          } else if (type === 'comment') {
            $items.eq(2).ctrigger('click');
          } else {
            $items.eq(0).ctrigger('click');
          }
          $('#divIntroduce').removeClass('Lvh');
          $('body').addClass('bgWhite');
          _vars.showIntro = true;
          _func.initComment();
          _stor('detailPage', 'introduce', true);
        } else if (path === 'image') {
          $('#divDetail').hide();
          $('#divImage').show();
          _stor('detailPage', 'image', true);
        } else if (path === 'map') {
          if (_stor('detailPage') === 'introduce') {
            $('#divIntroduce').hide();
          } else {
            $('#divDetail').hide();
          }
          _func.initHotelRouteMap();
          $('#divMap').show();
          _stor('detailPage', 'map', true);
        }
        _n.hook('scrollLit');
      });
    },
    detailInterAfter: function() {

      /* 切换修改入住时间 {{{ */
      $('#checkin_edit').on('click', function() {
        var $edit, $self;
        $self = $(this);
        $edit = $('#checkin_count');
        if ($edit.hasClass('Ldn')) {
          return $edit.removeClass('Ldn');
        } else {
          return $edit.addClass('Ldn');
        }
      });

      /*}}} */

      /*展示详细页{{{ */
      $('.detailblock .item').on('click', function() {
        var $self, link;
        $self = $(this);
        link = $self.data('link');
        if (link === 'info') {
          _func.pushState('introduce|basic');
        } else if (link === 'address') {
          _func.pushState('map');
        }
      });
      $('.HD_intro .addritem').on('click', function() {
        _func.pushState('map');
      });
      $('.HD_main .pic_count').on('click', function() {
        _func.pushState('image');
        return false;
      });

      /* 返回详情页 */
      $('.Chead a.back').on('click', function() {
        var _href;
        _href = this.href;
        if (_href.match(/^javascript:/)) {
          if (_stor('initPushState')) {
            history.back();
          } else {
            if (_vars.showIntro && _stor('detailPage') === 'map') {
              _n.hook('pushStateHook', 'introduce');
            } else {
              _n.hook('pushStateHook', '');
            }
          }
        }
      });

      /*酒店介绍页tab切换 */
      $('.HD_intro .tab').on('click', function() {
        var $self, $tabs, active;
        $self = $(this);
        $tabs = $self.parents('.tabs');
        if (!$self.hasClass('active')) {
          active = $self.data('active');
          $self.siblings('.active').removeClass('active');
          $self.addClass('acitve');
          $tabs.removeClass($tabs.data('current')).addClass(active).data('current', active);
          return $tabs.siblings('div').addClass('Ldn').eq($self.index() - 1).removeClass('Ldn');
        }
      });

      /*}}} */
      (function() {
        var $dialog;
        $dialog = $('.HD_main');
        return _func.initHotelDetailSwipe($dialog, true);
      })();
      _n.hook('interDetailMap', function() {
        var $map, height, map, mapoption, markerOption;
        $map = $('#routemap');
        if ($map.data('init') !== 'inited') {
          $map.data('init', 'inited');
          height = window.innerHeight - $('.HM_main .hotelroute').height() - $('#divMap .Chead').height();
          $map.css('height', height);
          mapoption = {
            lat: $map.data('lat'),
            lng: $map.data('lng'),
            zoom: 17
          };
          map = new _n.util.map('routemap', mapoption, _vars.mapRenderType);
          markerOption = {
            title: '',
            lat: mapoption.lat,
            lng: mapoption.lng
          };
          return map.addMarker(markerOption);
        }
      });

      /*initInterHotelMap {{{ */
      _func.initInterHotelMap = function() {
        var url;
        if (window.google || window.AMap) {
          _n.hook('interDetailMap');
        } else {
          window.initInterHotelMap = function() {
            return _n.hook('interDetailMap');
          };
          url = _vars.mapRenderType === 'google' ? _vars.gmapurl : _vars.mapurl;
          url += (url.indexOf('?') < 0 ? '?' : '&');
          _func.addScript("" + url + "callback=initInterHotelMap");
        }
      };

      /*}}} */

      /*push state {{{ */
      _func.initPushState(function(path) {
        var $items, paths, type;
        if (path.indexOf('introduce') > -1) {
          paths = path.split('|');
          path = paths[0];
          type = paths[1] || 'basic';
        }
        if (path === '') {

          /*换回酒店详情页 */
          if (_stor('detailPage') === 'introduce') {
            _vars.showIntro = false;
            $('#divIntroduce').hide();
            $('#divDetail').show();
          }
          if (_stor('detailPage') === 'image') {
            $('#divImage').hide();
            $('#divDetail').show();
          }
          if (_stor('detailPage') === 'map') {
            if (_vars.showIntro === true) {
              $('#divMap').hide();
              $('#divIntroduce').show();
            } else {
              $('#divMap').hide();
              $('#divDetail').show();
            }
          }
          _stor('detailPage', 'detail', true);
        } else if (path === 'introduce') {

          /*切换到酒店介绍页 */
          if (_stor('detailPage') === 'map') {
            $('#divMap').hide();
          } else {
            $('#divDetail').hide();
          }
          $('#divIntroduce').addClass('Lvh').show();
          $items = $('#divIntroduce .tabs .tab');
          if (type === 'service') {
            $items.eq(1).ctrigger('click');
          } else if (type === 'comment') {
            $items.eq(2).ctrigger('click');
          } else {
            $items.eq(0).ctrigger('click');
          }
          $('#divIntroduce').removeClass('Lvh');
          _vars.showIntro = true;
          _func.initComment();
          _stor('detailPage', 'introduce', true);
        } else if (path === 'image') {
          $('#divDetail').hide();
          $('#divImage').show();
          _stor('detailPage', 'image', true);
        } else if (path === 'map') {

          /*切换到地图页 */
          if (_stor('detailPage') === 'introduce') {
            $('#divIntroduce').hide();
          } else {
            $('#divDetail').hide();
          }
          _func.initInterHotelMap();
          $('#divMap').show();
          _stor('detailPage', 'map', true);
        }
        _n.hook('scrollLit');
      });

      /*}}} */
      $('.international_priceblock').on('click', '.roominfo', function(e) {
        var $el, $popup, $roomitem;
        $el = $(e.target || e.srcElement);
        if ($el.hasClass('down')) {
          return void 0;
        }
        $roomitem = $el.parents('.roomitem');
        $popup = $('#' + $roomitem.data('roomcode'));
        if (!$popup.length) {
          return false;
        }
        if ($popup.data('init') !== 'inited') {
          $popup.on('click', function(e) {
            $popup.addClass('Ldn');
          }).find('.detailbox').on('click', function(e) {
            var $self;
            $self = $(e.target || e.srcElement);
            if ($self.hasClass('close') || $self.parents('.close').length) {
              return void 0;
            }
            if (typeof e.stopPropagation === "function") {
              e.stopPropagation();
            }
            return false;
          });
          $popup.addClass('Lvh').removeClass('Ldn');

          /*初始化酒店详情图片切换 */
          _func.initHotelDetailSwipe($popup);
          $popup.removeClass('Lvh').data('init', 'inited');
        } else {
          $popup.removeClass('Ldn');
        }
      });
      _func.checkinCount();
    },
    surroundAfter: function() {
      _func.initScroll = function() {

        /*滚动 */
        var $mlist, hotelmap, scroll;
        if (_func.isIPhone()) {
          hotelmap = _stor('hotelSurroundMap');
          $mlist = $('.mlist');
          if ($mlist.data('initScroll') !== 'init') {
            $mlist.data('initScroll', 'init');
            $mlist.css('overflow', 'hidden');
            scroll = new IScroll($mlist.get(0), {
              scrollbar: true,
              click: true
            });
            hotelmap.listscroll = scroll;
            _stor('hotelSurroundMap', hotelmap, 1);
          }
        }
      };
      _func.refreshScroll = function() {
        var hotelmap;
        if (_func.isIPhone()) {
          hotelmap = _stor('hotelSurroundMap');
          hotelmap.listscroll.refresh();
        }
        _func.scrollTo(0);
      };
      _func.scrollTo = function(top) {
        var hotelmap, scroll;
        if (_func.isIPhone()) {
          hotelmap = _stor('hotelSurroundMap');
          scroll = hotelmap.listscroll;
          scroll.scrollTo(0, 0 - top);
        } else {
          $('.mlist').get(0).scrollTop = top;
        }
      };

      /*标注切换 */
      _func.markToggle = function(m, active) {
        var $shop, extdata, hotelmap, index, markIcons;
        if (active == null) {
          active = true;
        }
        hotelmap = _stor('hotelSurroundMap');
        markIcons = hotelmap.markIcons;
        extdata = JSON.parse(m.getExtData());
        if (!active) {
          m.setTop(false);
          m.setIcon(markIcons[extdata.icon]);
          $(".cat" + extdata.CategoryID + "_" + extdata.BusinessID).removeClass('active');
        } else {
          m.setTop(true);
          m.setIcon(markIcons[extdata.icon + '_hover']);
          $shop = $(".cat" + extdata.CategoryID + "_" + extdata.BusinessID);
          if ($shop.length) {
            index = $shop.index();
            _func.scrollTo(index * $shop.height());
            $shop.addClass('active');
          }
        }
      };

      /*回调函数 */
      _func.markerClickCallback = function(e) {
        var hotelmap, marker, pmarker;
        hotelmap = _stor('hotelSurroundMap');
        marker = e.target;
        pmarker = hotelmap.marker;
        if (pmarker) {
          _func.markToggle(pmarker, false);
        }
        _func.markToggle(marker);
        hotelmap.marker = marker;
        _stor('hotelSurroundMap', hotelmap, 1);
      };

      /*添加标记 */
      _func.addMarker = function(map, point, icons, icon) {
        var marker;
        marker = new AMap.Marker({
          map: map,
          icon: icons[icon],
          offset: new AMap.Pixel(-14, -35),
          position: new AMap.LngLat(point.Longitude, point.Latitude),
          extData: JSON.stringify({
            BusinessID: point.BusinessID,
            CategoryID: point.CategoryID,
            icon: icon
          })
        });

        /*绑定事件 */
        AMap.event.addListener(marker, 'click', _func.markerClickCallback);
        return marker;
      };

      /*更新标注 */
      _func.updateMarker = function(id, icon, data) {
        var boundPoint1, boundPoint2, center, currMarker, currMarkers, hotelmap, item, latarr, latoffset, lngarr, lnglat, lngoffset, map, markIcons, marker, markerData, markers, maxlat, maxlng, minlat, minlng, _i, _j, _k, _len, _len1, _len2;
        hotelmap = _stor('hotelSurroundMap');
        map = hotelmap.map;
        center = hotelmap.center;
        markerData = hotelmap.markerData;
        currMarker = hotelmap.currMarker;
        markIcons = hotelmap.markIcons;

        /*隐藏原有标注 */
        lngarr = [];
        latarr = [];
        if (currMarker !== -1 && (currMarkers = markerData[currMarker])) {
          for (_i = 0, _len = currMarkers.length; _i < _len; _i++) {
            marker = currMarkers[_i];
            marker.hide();
          }
          if (hotelmap.marker) {
            _func.markToggle(hotelmap.marker, false);
          }
          _func.refreshScroll();
        }
        if (data) {
          markers = [];
          if (data.length) {
            for (_j = 0, _len1 = data.length; _j < _len1; _j++) {
              item = data[_j];
              marker = _func.addMarker(map, item, markIcons, icon);
              markers.push(marker);
              lnglat = marker.getPosition();
              lngarr.push(lnglat.getLng());
              latarr.push(lnglat.getLat());
            }
          } else {
            map.setCenter(hotelmap.center);
          }
          hotelmap.markerData[id] = markers;
        } else {
          markers = markerData[id];
          for (_k = 0, _len2 = markers.length; _k < _len2; _k++) {
            marker = markers[_k];
            lnglat = marker.getPosition();
            lngarr.push(lnglat.getLng());
            latarr.push(lnglat.getLat());
            marker.show();
          }
        }

        /*设置范围 */
        if (lngarr.length) {
          lngarr.push(center.getLng());
          latarr.push(center.getLat());
          minlng = Math.min.apply(Math, lngarr);
          maxlng = Math.max.apply(Math, lngarr);
          minlat = Math.min.apply(Math, latarr);
          maxlat = Math.max.apply(Math, latarr);

          /*设置容差 */
          lngoffset = (maxlng - minlng) * 0.1;
          latoffset = (maxlat - minlat) * 0.1;
          boundPoint1 = new AMap.LngLat(minlng - lngoffset, minlat - latoffset);
          boundPoint2 = new AMap.LngLat(maxlng + lngoffset, maxlat + latoffset);
          map.setBounds(new AMap.Bounds(boundPoint1, boundPoint2));
        }

        /*更新hotelmap数据 */
        hotelmap.currMarker = id;
        hotelmap.zoom = map.getZoom();
        _stor('hotelSurroundMap', hotelmap, 1);
      };
      _n.hook('hotelSurroundMap', function() {
        setTimeout(function() {
          var $map, item, key, lat, lng, map, markIcons, marker, _i, _j, _len, _len1, _ref, _ref1;
          $map = $('#surroundmap');
          lat = $map.data('lat');
          lng = $map.data('lng');

          /*添加mark图标 */
          markIcons = {};
          _ref = ['default', 'hanting', 'quanji', 'haiyou', 'manxin', 'xingcheng', 'xiyue', 'yilai', 'jingxuan', 'ibis'];
          for (key = _i = 0, _len = _ref.length; _i < _len; key = ++_i) {
            item = _ref[key];
            markIcons[item] = new AMap.Icon({
              size: new AMap.Size(60, 45),
              image: _vars.locationicon,
              imageSize: new AMap.Size(60, 500),
              imageOffset: new AMap.Pixel(0, 0 - key * 50)
            });
          }
          _ref1 = ['food', 'drink', 'sing', 'shop', 'lady', 'exercise'];
          for (key = _j = 0, _len1 = _ref1.length; _j < _len1; key = ++_j) {
            item = _ref1[key];
            markIcons[item] = new AMap.Icon({
              size: new AMap.Size(28, 35),
              image: _vars.lbsicon,
              imageSize: new AMap.Size(56, 300),
              imageOffset: new AMap.Pixel(0, 0 - key * 35)
            });
            markIcons[item + "_hover"] = new AMap.Icon({
              size: new AMap.Size(28, 35),
              image: _vars.lbsicon,
              imageSize: new AMap.Size(56, 300),
              imageOffset: new AMap.Pixel(-28, 0 - key * 35)
            });
          }

          /*创建地图和标记 */
          map = new AMap.Map('surroundmap', {
            dragEnable: true,
            resizeEnable: true,
            view: new AMap.View2D({
              center: new AMap.LngLat(lng, lat),
              zoom: 17
            })
          });
          marker = new AMap.Marker({
            map: map,
            icon: markIcons[_vars.brand],
            offset: new AMap.Pixel(-18, -45),
            position: new AMap.LngLat(lng, lat)
          });

          /*存储 */
          _stor('hotelSurroundMap', {
            map: map,
            center: new AMap.LngLat(lng, lat),
            markerData: {},
            currMarker: -1,
            markIcons: markIcons,
            zoom: map.getZoom()
          }, true);
          $map.data('init', 'inited');
          _n.hook('initfirstdata');
          _func.initScroll();
          $map.find('.amap-logo, .amap-copyright').remove();
        }, 100);
      });
      _n.hook('lbschange', function(id, icon, data) {
        _func.updateMarker('marker_' + id, icon, data);
      });
      _n.hook('initfirstdata', function() {
        var catid, curr, data, hotelmap;
        curr = $('.tabs .item.hover');
        data = JSON.parse(_vars.lbslist);
        catid = curr.data('catid');
        if (data.length) {
          return _n.hook('lbschange', [catid, curr.data('icon'), data]);
        } else {
          hotelmap = _stor('hotelSurroundMap');
          hotelmap.currMarker = 'marker_' + catid;
          return _stor('hotelSurroundMap', hotelmap, true);
        }
      });

      /*warp callback */
      window.wrapMapCallback = function() {
        _n.hook('hotelSurroundMap');
      };
      (_func.initHotelSurroundMap = function() {

        /*计算区域高度 */
        var $map, $mlist, height, iheight, innerHeight, lbslayout, offset, sheight, tabheight;
        innerHeight = Math.max(window.innerHeight, window.innerWidth);

        /*地图区域高 */
        offset = 11;
        iheight = 85;
        tabheight = $('.tabs').height();
        height = innerHeight - (iheight * 2) - tabheight - offset;
        sheight = Math.max(parseInt(innerHeight * 0.3), height - (iheight * 3));
        $map = $('#surroundmap');
        $map.css('height', height);

        /*列表区域高 */
        $mlist = $('.mlist');
        $mlist.css('height', iheight * 2);

        /*切换 */
        $('.tabs .circle').on('click', function() {
          var $self, layout;
          $self = $(this);
          layout = _stor('lbslayout');
          innerHeight = layout.innerHeight;
          height = layout.height;
          sheight = layout.sheight;
          if ($self.data('expand') !== 'expanded') {
            $map.css('height', sheight);
            $mlist.css('height', innerHeight - tabheight - offset - sheight);
            $self.data('expand', 'expanded');
            $self.find('.arrow').addClass('reverse');
          } else {
            $map.css('height', height);
            $mlist.css('height', innerHeight - tabheight - offset - height);
            $self.data('expand', '');
            $self.find('.arrow').removeClass('reverse');
          }
          _func.refreshScroll();
        });
        lbslayout = {
          offset: 11,
          iheight: 85,
          tabheight: tabheight,
          height: height,
          sheight: sheight,
          innerHeight: innerHeight
        };
        _stor('lbslayout', lbslayout, 1);

        /*侦听屏幕大小 */
        setTimeout(_func.cycleCheckSize = function(tick) {
          var $circle, cinnerHeight, layout;
          if (tick == null) {
            tick = 0;
          }
          layout = _stor('lbslayout');
          cinnerHeight = Math.max(window.innerHeight, window.innerWidth);
          if (cinnerHeight !== layout.innerHeight) {
            tick += 1;
            if (tick === 3) {
              layout.sheight += cinnerHeight - layout.innerHeight;
              layout.height += cinnerHeight - layout.innerHeight;
              $circle = $('.tabs .circle');
              if ($circle.data('expand') === 'expanded') {
                $map.css('height', layout.sheight);
              } else {
                $map.css('height', layout.height);
              }
              _func.refreshScroll();
              layout.innerHeight = cinnerHeight;
              _stor('lbslayout', layout, 1);
              tick = 0;
            }
          } else {
            tick = 0;
          }
          return setTimeout(function() {
            return _func.cycleCheckSize(tick);
          }, 100);
        }, 2000);

        /*初始化地图 */
        if (!window.AMap) {
          _func.addScript(_vars.mapurl + '&callback=wrapMapCallback');
        } else {
          _n.hook('hotelSurroundMap');
        }
      })();
    }
  });
  _n.app('Account', {
    HistoryOrderAfter: function() {
      var $list;
      $list = $('.Member>.inner');
      if (parseInt($list.data('total')) > 0) {
        $('.Member .loadmore').datalazyload({
          repeat: true,
          loader: function() {
            _func.lazyLoadMore($('.Member>.inner'));
          }
        }).addClass('enable');
      }
    },
    MoreInfoBefore: function() {
      return $('select.fixheight').on('change', function() {
        var $self, option;
        $self = $(this);
        if (this.selectedOptions) {
          option = this.selectedOptions[0];
        } else if (this.options) {
          option = this.options[this.selectedIndex];
        }
        if (option) {
          $self.parent().find('.textForm').text($(option).text());
        }
      });
    }
  });
  _n.app('reserve', {
    indexBefore: function() {
      _func.inputFocusFix();

      /* 收起CsearchList列表---15/6/1  --start */
      $('.contact_list .select1').on('change', function() {
        var $self, name, phone, val;
        $self = $(this);
        val = $self.val().split('|');
        if (val) {
          name = val[0];
          phone = val[1];
          $('#txtName').val(name);
          $('#txtMobile').val(phone);
        }
      });
      _n.hook('dayscroll', function() {
        var $box, $inner, _width;
        $box = $('.dayselect .clipbox');
        $inner = $('.dayselect .inner');
        _width = $inner.width();
        if ($inner.width() < window.innerWidth) {
          _width = '100%';
        }
        $inner.css({
          width: _width + 4
        });
        $box.css({
          width: '100%'
        });
        if ($box.data('init') !== 'inited') {
          if (!$.browser.ie) {
            new IScroll($box.get(0), {
              scrollbar: true,
              scrollX: true,
              scrollY: false
            });
          } else {
            $box.css({
              overflowX: 'auto'
            });
          }
          return $box.data('init', 'inited');
        }
      });
    },
    cpcBefore: function() {
      _func.inputFocusFix();
    },
    quickAfter: function() {
      _n.require(['quickOrderRoomTypePopup', 'quickOrderHotelChoice'], function() {
        var hotel;
        _vars.ReverseIndexDispatch = riot.observable();
        hotel = _vars.quickModel[0];
        _vars.ReverseIndexDispatch.on('hotelchoice', function(hotelId) {
          var _i, _len, _ref;
          _ref = _vars.quickModel;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            hotel = _ref[_i];
            if (hotel.HotelID === hotelId) {
              _vars.ReverseIndexDispatch.trigger('roomchange', hotelId, hotel.Rooms);
              break;
            }
          }
        });
        _vars.ReverseIndexDispatch.on('roomchoice', function(data) {
          var html, payment, room, total;
          room = data.room;
          payment = room.RatePlanCodeList[0].DailyPriceInfo[0].Payment;
          html = "<div class=\"pure-u-7-24\">" + room.RoomDetail.RoomTypeName + "</div>\n<div class=\"pure-u-11-24 text\">" + payment + "</div>\n<div class=\"pure-u-4-24 desctext Ltar\">" + data.count + "间</div>\n<div class=\"pure-u-2-24\"><i class=\"Cicon next Lflr\"></i></div>";
          $('#choiceRoomType').html(html);
          $('#hotelId').val(data.hotelId);
          $('#roomTypeID').val(data.room.RoomDetail.RoomTypeID);
          $('#selBookCount').val(data.count);
          if (data.room.RatePlanCodeList && data.room.RatePlanCodeList[0] && data.room.RatePlanCodeList[0].ActivityID) {
            $('#hidBookingType').val('Activity');
            $('#activityId').val(data.room.RatePlanCodeList[0].ActivityID);
          } else {
            $('#hidBookingType').val('');
            $('#activityId').val('');
          }
          total = 0;
          data.room.RatePlanCodeList[0].DailyPriceInfo.map(function(d) {
            return total = total + d.Payment;
          });
          $('.pricearea .price').html('<i>￥</i>' + total * data.count);
        });
        riot.mount('hotelchoice', {
          hotels: _vars.quickModel,
          dispatch: _vars.ReverseIndexDispatch
        });
        riot.mount('roomtypepopup', {
          title: '选房型',
          rooms: hotel.Rooms,
          hotelId: hotel.HotelID,
          dispatch: _vars.ReverseIndexDispatch
        });
      }, 'do');
      $('#choiceRoomType').on('click', function() {
        _vars.ReverseIndexDispatch.trigger('show');
      });
    }
  });
  _n.app('PersonalCenter', {
    PointBalanceAfter: function() {
      var $list;
      $list = $('.Cloadlist');
      if (parseInt($list.data('total')) > 0) {
        $('.Cloadlist').siblings('.loadmore').datalazyload({
          repeat: true,
          loader: function() {
            _func.lazyLoadMore($list);
          }
        }).addClass('enable');
      }
    },
    ECouponDetailBefore: function() {
      var $searchkey;
      $searchkey = $('.hotelquery .input1');
      if ($.trim($searchkey.val()).length) {
        $searchkey.siblings('.cleaninput').show();
      }
      $searchkey.on('input', function() {
        var $self;
        $self = $(this);
        if ($self.val().length > 0) {
          $self.siblings('.cleaninput').show();
        } else {
          $self.siblings('.cleaninput').hide();
        }
      });
      $searchkey.siblings('.cleaninput').on('click', function() {
        var $self;
        $self = $(this);
        $self.siblings('.input1').val('').focus().trigger('input');
        $self.hide();
      });
    },
    MyPointAfter: function() {
      var $list;
      $list = $('.CDT_list .list');
      if (parseInt($list.data('total')) > 0) {
        $('.CDT_list .loadmore').datalazyload({
          repeat: true,
          loader: function() {
            _func.lazyLoadMore($('.CDT_list .list'));
          }
        }).addClass('enable');
      }
      return _func.lazyListImage($list);
    }
  });
  _n.app('promotion', {
    indexBefore: function() {
      var $list;
      $list = $('.HL_main .hlist');
      if (parseInt($list.data('total')) > 1) {
        $('.HL_main .loadmore').datalazyload({
          repeat: true,
          loader: function() {
            _func.lazyLoadMore($('.HL_main .hlist'));
          }
        }).addClass('enable');
      }
      return _func.lazyListImage($list);
    }
  });
  _n.app('payment', {
    indexAfter: function() {
      var _down, _initCountDown;
      _down = function(call) {
        var count, m, mtext, paytime_timetoken, s, stext;
        if (_n.pageId !== 'payment/index') {
          return;
        }
        count = _stor('paytime_countdown');
        if (count > 0) {
          m = Math.floor(count / 60);
          s = count % 60;
        } else {
          if (_vars.completeurl) {
            _n.visit(_vars.completeurl);
          }
        }
        if (m > 9) {
          mtext = '' + m;
        } else {
          mtext = "0" + m;
        }
        if (s > 9) {
          stext = '' + s;
        } else {
          stext = "0" + s;
        }
        $('.PL_paytime .chead b').text("" + mtext + ":" + stext);
        if ((--count) > 0) {
          _stor('paytime_countdown', count, 1);
          paytime_timetoken = setTimeout(function() {
            call(call);
          }, 1000);
          _stor('paytime_timetoken', paytime_timetoken, 1);
        } else {
          _stor('paytime_countdown', 0, 1);
        }
      };
      _func.addPageBeforeUnloadCall(function() {
        clearTimeout(_stor('paytime_timetoken'));
        return void 0;
      });
      (_initCountDown = function() {
        var $paytime, countdown;
        $paytime = $('.PL_paytime');
        if ($paytime.length) {
          countdown = parseInt($paytime.data('countdown')) || 0;
          if (countdown) {
            _stor('paytime_countdown', countdown, 1);
            _down(_down);
          }
        }
      })();
      _func.addPageRestoreCall('timeCountDown', function() {
        return _down(_down);
      });
    }
  });
  _n.app('customize', {
    indexAfter: function() {
      _func.customizeCartAanimate = function($elem) {
        var $add, bottom, left, offset, tick, top;
        $add = $('.animate_add');
        $add.hide();
        $add.removeClass('trans');
        offset = $elem.find('.Cicon').offset();
        left = offset.left;
        top = offset.top - Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        if (top < 0) {
          top = 0;
        }
        bottom = window.innerHeight - top - 24;
        tick = "tick_" + (+new Date());
        _stor('customizeCartAanimateTick', tick, true);
        $add.css({
          left: left,
          bottom: bottom,
          display: 'block',
          opacity: 1
        });
        setTimeout(function() {
          $add.show();
          $add.addClass('trans');
          return $add.css({
            left: 10,
            bottom: 10,
            opacity: 0.4
          }, 16);
        });
        setTimeout(function() {
          if (_stor('customizeCartAanimateTick') === tick) {
            return $add.hide();
          }
        }, 750);
      };
    }
  });
  return _n;
}).call(this.N = this.ndoo = this.ndoo || {}, Zepto);
;
(function ($) {
    var _func, _n, _stor, _vars;
    _n = this;
    _vars = _n.vars;
    _func = _n.func;
    _stor = _n.storage;
    _vars.exponent = "";
    _vars.modules = "";
    _vars.timestamp = new Date().getTime();
    _vars.rsakey = "";
    _func.isCellPhone = function (e) {
        var t = /(^0{0,1}1[3|4|5|6|7|8][0-9]{9}$)/;
        return t.test(e);
    };
    _func.isIdCardNo = function (code) {
        var city = { 11: "北京", 12: "天津", 13: "河北", 14: "山西", 15: "内蒙古", 21: "辽宁", 22: "吉林", 23: "黑龙江 ", 31: "上海", 32: "江苏", 33: "浙江", 34: "安徽", 35: "福建", 36: "江西", 37: "山东", 41: "河南", 42: "湖北 ", 43: "湖南", 44: "广东", 45: "广西", 46: "海南", 50: "重庆", 51: "四川", 52: "贵州", 53: "云南", 54: "西藏 ", 61: "陕西", 62: "甘肃", 63: "青海", 64: "宁夏", 65: "新疆", 71: "台湾", 81: "香港", 82: "澳门", 91: "国外 " };
        var tip = "";
        var pass = true;
        if (!code || !/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[12])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/i.test(code)) {
            tip = "身份证号格式错误";
            pass = false;
        } else if (!city[code.substr(0, 2)]) {
            tip = "地址编码错误";
            pass = false;
        } else {
            if (code.length == 18) {
                code = code.split('');
                var factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
                var parity = [1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2];
                var sum = 0;
                var ai = 0;
                var wi = 0;
                for (var i = 0; i < 17; i++) {
                    ai = code[i];
                    wi = factor[i];
                    sum += ai * wi;
                }
                var last = parity[sum % 11];
                if (parity[sum % 11] != code[17]) {
                    tip = "校验位错误";
                    pass = false;
                }
            }
        }
        if (!pass) _func.showMessage(tip);
        return pass;
    };
    _func.checkDate = function (INDate) {
        if (INDate == "") {
            return true;
        }
        subYY = INDate.substr(0, 4)
        if (isNaN(subYY) || subYY <= 0) {
            return true;
        }
        //转换月份
        if (INDate.indexOf('-', 0) != -1) {
            separate = "-";
        } else {
            if (INDate.indexOf('/', 0) != -1) {
                separate = "/";
            } else {
                return true;
            }
        }
        area = INDate.indexOf(separate, 0);
        subMM = INDate.substr(area + 1, INDate.indexOf(separate, area + 1) - (area + 1));
        if (isNaN(subMM) || subMM <= 0) {
            return true;
        }
        if (subMM.length < 2) {
            subMM = "0" + subMM;
        }
        //转换日
        area = INDate.lastIndexOf(separate);
        subDD = INDate.substr(area + 1, INDate.length - area - 1);
        if (isNaN(subDD) || subDD <= 0) {
            return true;
        }
        if (eval(subDD) < 10) {
            subDD = "0" + eval(subDD);
        }
        NewDate = subYY + "-" + subMM + "-" + subDD;
        if (NewDate.length != 10) {
            return true;
        }
        if (NewDate.substr(4, 1) != "-") {
            return true;
        }
        if (NewDate.substr(7, 1) != "-") {
            return true;
        }
        var MM = NewDate.substr(5, 2);
        var DD = NewDate.substr(8, 2);
        if ((subYY % 4 == 0 && subYY % 100 != 0) || subYY % 400 == 0) { //判断是否为闰年
            if (parseInt(MM) == 2) {
                if (DD > 29) {
                    return true;
                }
            }
        } else {
            if (parseInt(MM) == 2) {
                if (DD > 28) {
                    return true;
                }
            }
        }
        var mm = new Array(1, 3, 5, 7, 8, 10, 12); //判断每月中的最大天数
        for (i = 0; i < mm.length; i++) {
            if (parseInt(MM) == mm[i]) {
                if (parseInt(DD) > 31) {
                    return true;
                }
            } else {
                if (parseInt(DD) > 30) {
                    return true;
                }
            }
        }
        if (parseInt(MM) > 12) {
            return true;
        }
        return false;
    };
    _func.isPassword = function (e) {
        //        var reg = /(?!^[0-9]+$|^[a-zA-Z]+$|^[~`!@#$%^&*()_+-=|]+$)^[0-9a-zA-Z~`!@#$%^&*()_+-=|]{6,20}/;
        //        return reg.test(e);
        if (e && e.length <= 20 && e.length >= 6) {
            return true;
        }
        return false;
    };
    _func.CheCkEmpty = function (e, name, showMessage) {
        if (!e || e.val().length <= 0) {
            showMessage(name + "不能为空!");
            e.focus();
            return false;
        }
        return true;
    };

    _func.prePost = function () {
        _vars.exponent = "";
        _vars.modules = "";
        _vars.timestamp = new Date().getTime();
        $.ajax({
            type: "POST",
            url: '/Account/GetPublicKey/?date=' + new Date().getTime(),
            async: false,
            dataType: 'json',
            success: function (result) {
                _vars.exponent = result.Exponent;
                _vars.modules = result.Modules;
                _vars.timestamp = result.TimeStamp;
            },
            error: function (result) {
                alert("系统繁忙，请稍后再试");
                return false;
            }
        });
        if (_vars.exponent == '' || _vars.modules == '') {
            return false;
        }
        setMaxDigits(131);
        // Put this statement in your code to create a new RSA key with these parameters
        _vars.rsakey = new RSAKeyPair(_vars.exponent, "", _vars.modules);
        //        _func.postLoginData($("#loginSubmit"), { "LoginName": encryptedString(rsakey, $("#userName").val() + "#" + timestamp), "PassWord": encryptedString(rsakey, $("#passWord").val() + "#" + timestamp), "Captcha": '222' }, '123');
    };
    _func.prePostAsync = function (f) {
        _vars.exponent = "";
        _vars.modules = "";
        _vars.timestamp = new Date().getTime();
        $.ajax({
            type: "POST",
            url: '/Account/GetPublicKey/?date=' + new Date().getTime(),
            dataType: 'json',
            success: function (result) {
                _vars.exponent = result.Exponent;
                _vars.modules = result.Modules;
                _vars.timestamp = result.TimeStamp;
                if (_vars.exponent == '' || _vars.modules == '') {
                    return false;
                }
                setMaxDigits(131);
                _vars.rsakey = new RSAKeyPair(_vars.exponent, "", _vars.modules);
                f();
            },
            error: function (result) {
                alert("网络请求超时,请重试");
                return false;
            }
        });

    };
    _func.postRegisterData = function (e, d, url) {
        e.text("注册中···").attr("loading", "loading");
        $.ajax({
            type: "POST",
            url: _vars.url.register,
            cache: false,
            dataType: 'json',
            data: d,
            success: function (result) {
                var isSuccess = result.isSuccess;
                var oMessage = result.oMessage;
                if (isSuccess != 1) {
                    _func.showMessage(oMessage);
                    $("#captcha").val('');
                    $("#imgCaptcha").attr("src", _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                    e.text("注册").removeAttr("loading");
                } else {
                    if (url.length && url.length > 0) {
                        if (url.indexOf("?") >= 0) {
                            //                            window.location.href = url + "&from=reg_succ";
                            N.visit(url + "&from=reg_succ");
                        } else {
                            //                            window.location.href = url + "?from=reg_succ";
                            N.visit(url + "?from=reg_succ");
                        }

                    } else {
                        //                        window.location.href = _vars.url.default + "?from=reg_succ";
                        N.visit(_vars.url.default + "?from=reg_succ");
                    }
                }
            },
            error: function (result) {
                _func.showMessage(_vars.message.register.error);
                $("#captcha").val('');
                $("#imgCaptcha").attr("src", _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                e.text("注册").removeAttr("loading");
            }
        });
    };
    _func.postLoginData = function (e, d, url) {
        e.text("登录中···").attr("loading", "loading");
        $.ajax({
            type: "POST",
            url: _vars.url.login,
            dataType: 'json',
            headers: { "__HttpVerificationToken": N.vars.__HttpVerificationToken },
            data: d,
            success: function (result) {
                var isSuccess = result.isSuccess;
                var oMessage = result.oMessage;
                if (isSuccess != 1) {
                    _func.showMessage(oMessage);
                    if (d.LoginType == 'SMS') {
                        wa_track_event('动态密码登陆', '失败');
                    } else {
                        wa_track_event('登录页', "登录失败", oMessage);
                    }
                    $("#captcha").val('');
                    $("#imgCaptcha").attr("src", _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                    $("#divValidate").show();
                    e.text("登录").removeAttr("loading");
                } else {
                    if (url.length && url.length > 0) {
                        N.visit(url);
                    } else {
                        N.visit(_vars.url.default);
                    }
                    if (d.LoginType == 'SMS') {
                        wa_track_event('动态密码登陆', '成功');
                    } else {
                        wa_track_event('登录页', "登录成功", "登录成功");
                    }

                }
            },
            error: function () {
                _func.showMessage(_vars.message.login.error);
                wa_track_event('登录页', "登录失败", _vars.message.login.error);
                $("#captcha").val('');
                $("#imgCaptcha").attr("src", _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                e.text("登录").removeAttr("loading");
            }
        });
    };
    _func.postMoreInfoData = function (e, d) {
        e.text("提交中···").attr("loading", "loading");
        $.ajax({
            type: "POST",
            url: _vars.url.moreInfo,
            cache: false,
            dataType: 'json',
            data: d,
            success: function (result) {
                if (result.Result) {
                    var object = eval('(' + result.Result + ')');
                    if (object["ResultType"] == 0) {
                        //                        window.location.href = _vars.url.myCenter;
                        N.visit(_vars.url.myCenter);
                    } else {
                        _func.showMessage(object["ResultType"].length ? "Message" : _vars.message.moreInfo.error);
                        e.text("确认").removeAttr("loading");
                    }
                } else {
                    _func.showMessage(_vars.message.moreInfo.error);
                    e.text("确认").removeAttr("loading");
                }
            },
            error: function () {
                _func.showMessage(_vars.message.moreInfo.error);
                e.text("确认").removeAttr("loading");
            }
        });
    };
    _func.checkCellPhoneAndSMS = function (e, d) {
        e.text("提交中···").attr("loading", "loading");
        $.ajax({
            type: "POST",
            url: _vars.url.checkForPassword,
            cache: false,
            dataType: 'json',
            data: d,
            success: function (result) {
                var isSuccess = result.isSuccess;
                var oMessage = result.oMessage;
                if (isSuccess != 1) {
                    _func.showMessage(oMessage);
                    //                    $("#divValidate").css("display", "");
                    $("#captcha").val('');
                    $("#imgCaptcha").attr("src", _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                    e.text("下一步").removeAttr("loading");
                } else {
                    N.visit(_func.getQueryStringByName("returnUrl") == '' ? _vars.url.resetPassword : _vars.url.resetPassword + '?returnUrl=' + _func.getQueryStringByName("returnUrl"));
                }
            },
            error: function () {
                _func.showMessage(_vars.message.resetPassword.error);
                $("#imgCaptcha").attr("src", _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                e.text("下一步").removeAttr("loading");
            }
        });
    };
    _func.checkIsName = function (e) {
        var reg = new RegExp("[0-9`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
        if (reg.test(e)) {
            return false;
        }
        return true;
    };
    _func.postResetPasswordData = function (d) {
        $.ajax({
            type: "POST",
            url: _vars.url.resetPassword,
            cache: false,
            dataType: 'json',
            data: d,
            success: function (result) {
                var isSuccess = result.isSuccess;
                var oMessage = result.oMessage;
                if (isSuccess != 1) {
                    _func.showMessage(oMessage);
                } else {
                    var url = _func.getQueryStringByName("returnUrl");
                    if (url && url.length > 0) {
                        alert("密码重置成功!");
                        //                        url = decodeURIComponent(url);
                        //                        if (url.toLowerCase().indexOf('h5.huazhu.com') > 0) {
                        ////                            window.location.href = _vars.url.login + '?returnUrl=' + url;
                        //                            N.visit(_vars.url.login + '?returnUrl=' + url);
                        //                        } else {
                        //                            window.location.href = url;
                        //                        }
                        N.visit(_vars.url.login + '?returnUrl=' + url);
                    } else {
                        alert("密码重置成功!");
                        //                        window.location.href = _vars.url.login;
                        N.visit(_vars.url.login);
                    }
                }
            },
            error: function () {
                _func.showMessage(_vars.message.resetPassword.error);
            }
        });
    };
    _func.getSms = function (d, n) {
        _vars.SendSms.init(n, d);
    };
    _func.loginOut = function () {
        $.ajax({
            type: "post",
            url: _vars.url.loginOut,
            data: {},
            success: function (result) {
                if (result.isSuccess === 1) {
                    N.visit(_vars.logoutUrl);
                } else {
                    _func.showMessage(result.oMessage);
                }
            },
            error: function () {
                _func.showMessage(_vars.message.loginOutError);
            }
        });
    };
    _func.postHPay = function (h, r, t, url) {
        if (h) {
            h.html("请求支付...");
            h.addClass("cancel_submit");
        }
        $.ajax({
            url: '/wxpay/HPayment',
            type: 'POST',
            dataType: 'json',
            data: {
                __RequestVerificationToken: t,
                resNo: r,
                ReturnUrl: url
            },
            success: function (data) {
                if (data.Result) {
                    console.log(data.Data);
                    $('body').append(data.Data);
                    $('#huazhuPayForm').submit();
                } else {
                    h.html("立即支付");
                    //h.removeClass("cancel_submit");
                    $(".HpmethodV2").removeClass("cancel_submit");
                    $('.Cload').hide();
                    alert(data.Message);
                }
            },
            error: function (err) {
                alert("网络繁忙，请稍后重试！");
                //h.removeClass("cancel_submit");
                $(".HpmethodV2").removeClass("cancel_submit");
                $('.Cload').hide();
                h.html("立即支付");
            }
        });
    };

    _func.postHPay = function (h, r, t, url) {
        if (h) {
            h.html("请求支付...");
            h.addClass("cancel_submit");
        }
        $.ajax({
            url: '/wxpay/HPayment',
            type: 'POST',
            dataType: 'json',
            data: {
                __RequestVerificationToken: t,
                resNo: r,
                ReturnUrl: url
            },
            success: function (data) {
                if (data.Result) {
                    console.log(data.Data);
                    $('body').append(data.Data);
                    $('#huazhuPayForm').submit();
                } else {
                    h.html("立即支付");
                    //h.removeClass("cancel_submit");
                    $(".HpmethodV2").removeClass("cancel_submit");
                    $('.Cload').hide();
                    alert(data.Message);
                }
            },
            error: function (err) {
                alert("网络繁忙，请稍后重试！");
                //h.removeClass("cancel_submit");
                $(".HpmethodV2").removeClass("cancel_submit");
                $('.Cload').hide();
                h.html("立即支付");
            }
        });
    };
    _vars.SendSms = {
        node: null,
        count: 60,
        text: null,
        start: function (msg) {
            var _msg = msg;
            if (this.count > 0) {
                this.node.text(this.count-- + "秒后再次发送");
                var _this = this;
                setTimeout(function () {
                    _this.start(_msg);
                }, 1000);
                _self = this;
                _func.addBeforeUnloadCall('cleanSendSmsCount', function () {
                    _self.count = _self.count != 60 ? 0 : _self.count;
                });
            } else {
                this.clear(_msg);
            }
        },
        clear: function (msg) {
            this.node.removeAttr("disabled");
            this.node.removeClass('getPaGray');
            this.node.html(msg && msg.clear ? msg.clear : "获取验证码");
            this.count = 60;
        },
        //初始化
        init: function (n, d, init_f, msg) {
            this.node = $(n);
            this.text = this.node.html();
            this.node.addClass('getPaGray');
            this.node.attr("disabled", true);
            this.node.html("正在发送验证码");
            var _this = this,
                _f = init_f,
                _msg = msg;
            $.ajax({
                type: "POST",
                url: _vars.url.sendSms,
                cache: false,
                dataType: 'json',
                data: d,
                success: function (result) {
                    var isSuccess = result.isSuccess;
                    var oMessage = result.oMessage;
                    if (isSuccess != 1) {
                        _func.showMessage(oMessage);
                        _this.clear(_msg);
                    } else {
                        _this.start(_msg);
                        if (_f) {
                            _f();
                        }
                    }
                },
                error: function (result) {
                    _func.showMessage(_vars.message.sms.error);
                    _this.clear();
                }
            });
        }
    };
    _func.transform = function (d) {
        $.ajax({
            type: "POST",
            url: '/PersonalCenter/PointBalance/',
            cache: false,
            dataType: 'json',
            data: { "data": JSON.stringify(d) },
            success: function (data) {
                if (data.IsSuccess == "1") {
                    //                    window.location.href = data.Url;
                    N.visit(data.Url);
                } else {
                    if (data.errorType == 3) {
                        //                        window.location.href = _vars.url.login + "?returnUrl=" + window.location.href;
                        N.visit(_vars.url.login + "?returnUrl=" + window.location.href);
                        return;
                    }
                    alert(data.Message && data.Message.length > 0 ? data.Message : "转赠积分出现错误!");
                }
            },
            error: function (data) {
                alert('转赠积分出现错误!');
            }
        });
    },
    _func.getQueryStringByName = function (name) {
        var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)", "i"));
        if (result == null || result.length < 1) {
            return "";
        }
        return result[1];
    };
    _func.showMessage = function (text) {
        _vars.errorDiv.html(text);
        window.scrollTo(0, 0);
        _vars.errorDiv.removeClass('Ctip_animation').addClass('Ctip_animation');
        _vars.errorDiv.css('display', 'block');
    };
    _func.clearMessage = function () {
        _vars.errorDiv.html('');
        _vars.errorDiv.css('display', 'none');
    };
    _vars.message = {
        register: {
            error: "注册出现错误!",
            errorName: "姓名不正确"
        },
        login: {
            error: "登录出现错误!",
            loginOutError: "退出登录出现错误"
        },
        resetPassword: {
            complete: "重置密码成功!",
            error: "重置密码出现错误"
        },
        moreInfo: {
            error: "完善信息出现错误!"
        },
        sms: {
            error: "验证码发送失败",
            coomplete: "验证码发送成功"
        },
        order: {
            cancelError: "取消订单出现错误",
            cancelComplete: "取消订单成功",
            cancelConfirm: "确定要取消订单？本订单如使用了网络优惠券或早餐券，取消后优惠券或早餐券将立即失效且不可恢复。"
        },
        empty: {
            phoneAndCard: "请输入手机号/会员卡号",
            passWord: "请输入密码",
            captcha: "请输入验证码",
            phone: "请输入手机号码",
            name: "请输入姓名",
            sms: "请输入短信验证码"
        },
        validate: {
            rightPassword: "密码长度必须为6-20位",
            rightPhone: "请输入正确的手机号码"
        }
    };
    _n.hook('App:Init', function () {
        var _vars = ndoo.vars;
        _vars.url = {
            default: _vars.ServerHttp + 'Home/Index/',
            register: _vars.ServerHttps + "Account/Register/",
            login: _vars.ServerHttps + "Account/Login/",
            resetPassword: _vars.ServerHttps + "Account/ResetPassword/",
            //            moreInfo: _vars.ServerHttps + "Account/MoreInfo/",
            myCenter: _vars.ServerHttp + "Account/MyCenter/",
            checkCellPhoneAndSms: _vars.ServerHttps + "Account/CheckCellPhoneAndSms/",
            checkForPassword: _vars.ServerHttps + "Account/CheckCellPhoneAndSmsForPassword/",
            sendSms: _vars.ServerHttps + "Account/SendSms/",
            cancelOrder: _vars.ServerHttp + "Account/CancelOrder/",
            loginOut: _vars.ServerHttp + "Account/LoginOut/"
        };
    });
    /* naxx {{{*/
    var urlParamCRC32 = function () {
        var code, derand, item, j, key, len1, nodes, oText, rand, randtext, text, iname, $input, hookFn;
        code = (function () {
            var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
                a256 = '',
                r64 = [256],
                r256 = [256],
                i = 0;

            var UTF8 = {

                /**
                 * Encode multi-byte Unicode string into utf-8 multiple single-byte characters
                 * (BMP / basic multilingual plane only)
                 *
                 * Chars in range U+0080 - U+07FF are encoded in 2 chars, U+0800 - U+FFFF in 3 chars
                 *
                 * @param {String} strUni Unicode string to be encoded as UTF-8
                 * @returns {String} encoded string
                 */
                encode: function (strUni) {
                    // use regular expressions & String.replace callback function for better efficiency
                    // than procedural approaches
                    var strUtf = strUni.replace(/[\u0080-\u07ff]/g, // U+0080 - U+07FF => 2 bytes 110yyyyy, 10zzzzzz
                    function (c) {
                        var cc = c.charCodeAt(0);
                        return String.fromCharCode(0xc0 | cc >> 6, 0x80 | cc & 0x3f);
                    })
                    .replace(/[\u0800-\uffff]/g, // U+0800 - U+FFFF => 3 bytes 1110xxxx, 10yyyyyy, 10zzzzzz
                    function (c) {
                        var cc = c.charCodeAt(0);
                        return String.fromCharCode(0xe0 | cc >> 12, 0x80 | cc >> 6 & 0x3F, 0x80 | cc & 0x3f);
                    });
                    return strUtf;
                },

                /**
                 * Decode utf-8 encoded string back into multi-byte Unicode characters
                 *
                 * @param {String} strUtf UTF-8 string to be decoded back to Unicode
                 * @returns {String} decoded string
                 */
                decode: function (strUtf) {
                    // note: decode 3-byte chars first as decoded 2-byte strings could appear to be 3-byte char!
                    var strUni = strUtf.replace(/[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g, // 3-byte chars
                    function (c) { // (note parentheses for precence)
                        var cc = ((c.charCodeAt(0) & 0x0f) << 12) | ((c.charCodeAt(1) & 0x3f) << 6) | (c.charCodeAt(2) & 0x3f);
                        return String.fromCharCode(cc);
                    })
                    .replace(/[\u00c0-\u00df][\u0080-\u00bf]/g, // 2-byte chars
                    function (c) { // (note parentheses for precence)
                        var cc = (c.charCodeAt(0) & 0x1f) << 6 | c.charCodeAt(1) & 0x3f;
                        return String.fromCharCode(cc);
                    });
                    return strUni;
                }
            };

            while (i < 256) {
                var c = String.fromCharCode(i);
                a256 += c;
                r256[i] = i;
                r64[i] = b64.indexOf(c);
                ++i;
            }

            function code(s, discard, alpha, beta, w1, w2) {
                s = String(s);
                var buffer = 0,
                    i = 0,
                    length = s.length,
                    result = '',
                    bitsInBuffer = 0;

                while (i < length) {
                    var c = s.charCodeAt(i);
                    c = c < 256 ? alpha[c] : -1;

                    buffer = (buffer << w1) + c;
                    bitsInBuffer += w1;

                    while (bitsInBuffer >= w2) {
                        bitsInBuffer -= w2;
                        var tmp = buffer >> bitsInBuffer;
                        result += beta.charAt(tmp);
                        buffer ^= tmp << bitsInBuffer;
                    }
                    ++i;
                }
                if (!discard && bitsInBuffer > 0) result += beta.charAt(buffer << (w2 - bitsInBuffer));
                return result;
            }
            ret = function (coded, utf8decode) {
                coded = coded.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                coded = String(coded).split('=');
                var i = coded.length;
                do {
                    --i;
                    coded[i] = code(coded[i], true, r64, a256, 6, 8);
                } while (i > 0);
                coded = coded.join('');
                return Plugin.raw === false || Plugin.utf8decode || utf8decode ? UTF8.decode(coded) : coded;
            };
            // return ret;
            var Plugin = function (dir, input, encode) {
                return input ? Plugin[dir](input, encode) : dir ? null : this;
            };

            Plugin.btoa = Plugin.encode = function (plain, utf8encode) {
                plain = Plugin.raw === false || Plugin.utf8encode || utf8encode ? UTF8.encode(plain) : plain;
                plain = code(plain, false, r256, b64, 8, 6);
                return plain + '===='.slice((plain.length % 4) || 4);
            };

            Plugin.atob = Plugin.decode = function (coded, utf8decode) {
                coded = coded.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                coded = String(coded).split('=');
                var i = coded.length;
                do {
                    --i;
                    coded[i] = code(coded[i], true, r64, a256, 6, 8);
                } while (i > 0);
                coded = coded.join('');
                return Plugin.raw === false || Plugin.utf8decode || utf8decode ? UTF8.decode(coded) : coded;
            };
            return Plugin;
        }());;
        rand = function (text) {
            var char, char1, char2, eq, fix, i, len, offset, rand1, rand2, ret, slen;
            ret = '';
            eq = text.match(/\=*$/);
            if (eq) {
                text = text.replace(/\=*$/, '');
                ret += eq[0].length + '';
            }
            text = text.split('');
            len = Math.floor(text.length / 8);
            i = 0;
            offset = '_';
            while (i < len) {
                rand1 = Math.floor(Math.random() * text.length);
                rand2 = Math.floor(Math.random() * text.length);
                if (rand1 && rand2 && rand1 !== rand2) {
                    char1 = text[rand1];
                    char2 = text[rand2];
                    text.splice(rand1, 1, char2);
                    text.splice(rand2, 1, char1);
                    i += 1;
                    offset += "_" + rand1 + "_" + rand2;
                }
            }
            offset += '__';
            fix = '';
            while (!fix) {
                fixRand = Math.floor(Math.random() * text.length);
                if (fixRand) {
                    char = text[fixRand];
                    fix = "__" + fixRand + "_" + char;
                }
            }
            slen = 8;
            while (slen < text.length) {
                text.splice(slen, 0, '_');
                slen += 8;
            }
            ret += offset;
            ret += text.join('');
            ret += fix;
            return ret;
        };
        _n.hook(code.decode('Y2hlY2tQb3N0VXJs'), function () {
            nodes = document.body.childNodes;
            var reg = new RegExp(code.decode('YnVpbGQgdGltZQ=='));
            for (key = j = 0, len1 = nodes.length; j < len1; key = ++j) {
                item = nodes[key];
                if (item.nodeType === 8 && reg.test(item.data)) {
                    oText = item.data;
                    break;
                }
            }
            if (oText.length) {
                oText = oText.split(' ');
                oText = oText[oText.length - 1];
                iname = "dmF4ZmNfdjE="
                $input = $('form input[name=' + code.decode(iname) + ']');
                if ($input.length <= 0) {
                    text = code.encode(oText);
                    randtext = rand(text);
                    $('<input type="hidden" value="' + randtext + '" name="' + code.decode(iname) + '" />').appendTo('form');
                }
            }
        });
    };
    urlParamCRC32();
    /* }}}*/
    /* 模块定义 {{{ */
    _n.app('Account', {
        init: function () {
            _vars.errorDiv = $(".errorS");
        },
        LoginAction: function () {
            $("#imgPassword").on("click", function () {
                _func.clearMessage();
                var $self = $(this),
                      $password = $("#passWord");
                if ($self.hasClass('enable')) {
                    $password.attr("type", "password");
                    $self.removeClass("enable");
                } else {

                    $self.addClass("enable");
                    $password.attr("type", "text");
                }
            });
            $("#loginSubmit").on('click', function () {
                _func.clearMessage();
                _n.hook('checkPostUrl');
                if (!$(this).get(0).hasAttribute('loading')) {
                    var $userName = $("#userName"),
                        $passWord = $("#passWord"),
                        $sms = $("#codeSms"),
                        $captcha = $("#captcha");
                    var smsLogin = $("#passWord").parent().parent().parent('.password').hasClass('Ldn');
                    var url = $("#returnUrl").data("url");
                    //登陆账号 密码 图形验证码不能为空
                    if (!_func.CheCkEmpty($userName, smsLogin ? "手机号码" : "手机号/会员卡号", _func.showMessage)) {
                        return false;
                    }
                    if (smsLogin) {
                        if (!_func.CheCkEmpty($sms, "动态密码", _func.showMessage)) {
                            return false;
                        }
                    } else {
                        if (!_func.CheCkEmpty($passWord, "密码", _func.showMessage)) {
                            return false;
                        }
                    }
                    //显示图形验证码的情况下，验证码不能为空
                    if ($("#divValidate")[0].style.display != "none" && !_func.CheCkEmpty($captcha, "验证码", _func.showMessage)) {
                        return false;
                    }
                    $(this).attr("loading", "loading");
                    var data = {};

                    _func.prePostAsync(function () {
                        if (smsLogin) {
                            data = { "LoginName": encryptedString(_vars.rsakey, $userName.val() + "#" + _vars.timestamp), "SmsCaptcha": encryptedString(_vars.rsakey, $sms.val() + "#" + _vars.timestamp), "Captcha": $.trim($captcha.val()), 'LoginType': 'SMS' };
                        } else {
                            data = { "LoginName": encryptedString(_vars.rsakey, $userName.val() + "#" + _vars.timestamp), "PassWord": encryptedString(_vars.rsakey, $passWord.val() + "#" + _vars.timestamp), "Captcha": $.trim($captcha.val()), 'LoginType': 'Password' };
                        }
                        var formArray = $('#formLogin').serializeArray();
                        var item = null;
                        if (formArray.length) {
                            for (var i = 0, j = formArray.length; i < j; i++) {
                                var item = formArray[i];
                                data[item.name] = item.value;
                            }
                        }
                        // data = $.extend(data, $('form').serializeArray());
                        _func.postLoginData($("#loginSubmit"), data, url);
                    });
                }
            });
            $("#imgCaptcha").on('click', function () {
                _func.clearMessage();
                $(this).attr('src', _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
            });
            $('.tabs .tab').on('click', function () {
                _func.clearMessage();
                var $tab, active, index, prevActive;
                $tab = $(this);
                if (!$tab.hasClass('active')) {
                    prevActive = $tab.siblings('.active').removeClass('active').data('active');
                    active = $tab.addClass('active').data('active');
                    $tab.parent().removeClass(prevActive).addClass(active);
                    index = $tab.index();
                    $("#userName").attr('placeholder', $("#userName").data(String(index)));
                    $('.tabcontent .item.password').addClass('Ldn').eq(index - 1).removeClass('Ldn');
                }
            });
            $("#pGetSms").on('click', function () {
                if ($(this).parent().parent().parent().hasClass('Ldn')) {
                    return false;
                }
                _func.clearMessage();
                var $btnSms = $(this),
                      $cellPhone = $("#userName");
                if (!_func.CheCkEmpty($cellPhone, "手机号", _func.showMessage)) return false;
                if (!_func.isCellPhone($.trim($cellPhone.val()))) {
                    _func.showMessage(_vars.message.validate.rightPhone);
                    return false;
                }
                _func.prePostAsync(function () {
                    _vars.SendSms.init($btnSms, { "CellPhone": encryptedString(_vars.rsakey, $.trim($cellPhone.val()) + "#" + _vars.timestamp), "CaptchaType": "Login" }, undefined, { clear: "获取<br>动态密码" });
                });
            });
        },
        RegiterAction: function () {
            $("#imgPassword").on("click", function () {
                _func.clearMessage();
                var $self = $(this),
                      $password = $("#passWord");
                if ($self.hasClass('enable')) {
                    $password.attr("type", "password");
                    $self.removeClass("enable");
                } else {
                    $self.addClass("enable");
                    $password.attr("type", "text");
                }
            });
            $("#pGetSms").on('click', function () {
                _func.clearMessage();
                var $btnSms = $(this),
                      $cellPhone = $("#cellPhone");
                if (!_func.CheCkEmpty($cellPhone, "手机号", _func.showMessage)) return false;
                if (!_func.isCellPhone($.trim($cellPhone.val()))) {
                    _func.showMessage(_vars.message.validate.rightPhone);
                    return false;
                }
                _func.prePostAsync(function () {
                    _vars.SendSms.init($btnSms, { "CellPhone": encryptedString(_vars.rsakey, $.trim($cellPhone.val()) + "#" + _vars.timestamp), "CaptchaType": "Register" });
                });
            });
            $("#imgCaptcha").on('click', function () {
                _func.clearMessage();
                $(this).attr('src', _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
            });
            $("#registerSubmit").on('click', function () {
                _func.clearMessage();
                if (!$(this).get(0).hasAttribute('loading')) {
                    var $btnRegister = $(this),
                          $cellPhone = $("#cellPhone"),
                          $smsCaptcha = $("#smsCaptcha"),
                          $userName = $("#userName"),
                          $passWord = $("#passWord"),
                          $captcha = $("#captcha");
                    var url = $("#returnUrl").data("url");
                    if (!_func.CheCkEmpty($cellPhone, "手机号", _func.showMessage) || !_func.CheCkEmpty($smsCaptcha, "短信验证码", _func.showMessage) || !_func.CheCkEmpty($userName, "姓名", _func.showMessage) || !_func.CheCkEmpty($passWord, "密码", _func.showMessage)) {
                        return false;
                    }
                    //显示图形验证码的情况下，验证码不能为空
                    if ($("#divValidate")[0].style.display != "none" && !_func.CheCkEmpty($captcha, "验证码", _func.showMessage)) {
                        return false;
                    }
                    if (!_func.isCellPhone($.trim($cellPhone.val()))) {
                        _func.showMessage(_vars.message.validate.rightPhone);
                        return false;
                    }
                    if (!_func.checkIsName($.trim($userName.val()))) {
                        _func.showMessage(_vars.message.register.errorName);
                        return false;
                    }

                    if (!_func.isPassword($.trim($passWord.val()))) {
                        _func.showMessage(_vars.message.validate.rightPassword);
                        return false;
                    }
                    $(this).attr("loading", "loading");
                    _func.prePostAsync(function () {
                        _func.postRegisterData($btnRegister, { "CellPhone": encryptedString(_vars.rsakey, $.trim($cellPhone.val()) + "#" + _vars.timestamp), "SmsCaptcha": $.trim($smsCaptcha.val()), "UserName": encryptedString(_vars.rsakey, $.trim($userName.val()) + "#" + _vars.timestamp), "PassWord": encryptedString(_vars.rsakey, $.trim($passWord.val()) + "#" + _vars.timestamp), "Captcha": $.trim($captcha.val()), "__RequestVerificationToken": $("input[name=__RequestVerificationToken]").val() }, url);
                    });
                }
            });
        },
        ForgetPasswordAction: function () {
            $("#pGetSms").on('click', function () {
                _func.clearMessage();
                var $btnSms = $(this),
                      $cellPhone = $("#cellPhone");
                if (!_func.CheCkEmpty($cellPhone, "手机号", _func.showMessage)) return false;
                if (!_func.isCellPhone($.trim($cellPhone.val()))) {
                    _func.showMessage(_vars.message.validate.rightPhone);
                    return false;
                }
                _func.prePostAsync(function () {
                    _vars.SendSms.init($btnSms, { "CellPhone": encryptedString(_vars.rsakey, $.trim($cellPhone.val()) + "#" + _vars.timestamp), "CaptchaType": "ForgetPassword" });
                });
            });
            $("#imgCaptcha").on('click', function () {
                _func.clearMessage();
                $(this).attr("src", _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
            });
            $("#postData").on('click', function () {
                _func.clearMessage();
                if (!$(this).get(0).hasAttribute('loading')) {
                    var $cellPhone = $("#cellPhone"),
                          $sms = $("#smsCaptcha"),
                          $captcha = $("#captcha"),
                          $btnForgetPass = $(this);
                    if (!_func.CheCkEmpty($cellPhone, "手机号", _func.showMessage) || !_func.CheCkEmpty($sms, "短信验证码", _func.showMessage)) {
                        return false;
                    }
                    if ($("#divValidate")[0].style.display != "none" && !_func.CheCkEmpty($captcha, "图形验证码", _func.showMessage)) {
                        return false;
                    }
                    if (!_func.isCellPhone($.trim($cellPhone.val()))) {
                        _func.showMessage(_vars.message.validate.rightPhone);
                        return false;
                    }
                    $(this).attr("loading", "loading");
                    _func.prePostAsync(function () {
                        _func.checkCellPhoneAndSMS($btnForgetPass, { "CellPhone": encryptedString(_vars.rsakey, $.trim($cellPhone.val()) + "#" + _vars.timestamp), "Captcha": $.trim($sms.val()), "ImgCaptchaCode": $.trim($captcha.val()) });
                    });

                }
            });
        },
        ResetPasswordAction: function () {
            $("#imgPassword").on("click", function () {
                _func.clearMessage();
                var $self = $(this),
                      $password = $("#passWord");
                if ($self.hasClass('enable')) {
                    $password.attr("type", "password");
                    $self.removeClass("enable");
                } else {
                    $self.addClass("enable");
                    $password.attr("type", "text");
                }
            });
            $("#rePasswordSubmit").on('click', function () {
                _func.clearMessage();
                if (!$(this).get(0).hasAttribute('loading')) {
                    var $password = $("#passWord");
                    if (!_func.CheCkEmpty($password, "密码", _func.showMessage)) {
                        return false;
                    }
                    if (!_func.isPassword($.trim($password.val()))) {
                        _func.showMessage(_vars.message.validate.rightPassword);
                        return false;
                    }
                    $(this).attr("loading", "loading");
                    _func.prePostAsync(function () {
                        _func.postResetPasswordData({ "PassWord": encryptedString(_vars.rsakey, $.trim($password.val()) + "#" + _vars.timestamp) });
                    });
                };
            });
        },

        MyCenterAction: function () {
            $("#loginout").on('click', function () {
                _func.clearMessage();
                _func.loginOut();
            });
        },
        DetailAction: function () {
            $("#cancelOrder").on('click', function () {
                var $this = $(this);
                if (!$(this).get(0).hasAttribute('loading')) {
                    _func.clearMessage();
                    var conf = confirm(_vars.message.order.cancelConfirm);
                    if (conf) {
                        _func.cancelOrder({ ResNo: $this.data('resno'), CenterResNo: $this.data('centerresno'), HotelId: $this.data('hotelid') }, $(this));
                    }
                }
            });

            $('a.sendsms.not').on('click', function () {
                var $this;
                $this = $(this);
                if ($this.hasClass('loading') || !$this.hasClass('not')) {
                    return false;
                }
                $this.addClass('loading');
                $this.html('<i class="ssAdQc_icon"></i>正在发送中');
                $.post(N.vars.smsUrl, {}, function (result) {
                    if (result.isSuccess == '1') {
                        $this.html('<i class="ssAdQc_icon"></i>已发送短信').removeClass('not');
                    } else {
                        alert(result.oMessage ? result.oMessage : '系统繁忙,请稍后再试!');
                        $this.html('<i class="ssAdQc_icon"></i>发预订短信');
                    }
                    $this.removeClass('loading');
                });
            });

            $("#delOrder").on('click', function () {
                if (!$(this).get(0).hasAttribute('loading')) {
                    _func.clearMessage();
                    var conf = confirm('确认要删除订单？');
                    var $this = $(this);
                    if (conf) {
                        $.post(_vars.delOrderUrl, { ResNo: $this.data('resno'), CenterResNo: $this.data('centerresno'), HotelId: $this.data('hotelid') }, function (result) {
                            var isSuccess = result.isSuccess;
                            var oMessage = result.oMessage;
                            if (isSuccess != 1) {
                                alert(oMessage);
                                $this.text("删除订单").removeAttr("loading");
                                wa_track_event("删除订单", "失败", $("#hotel").data('name') + $("#hotel").data('id'));
                            } else {
                                alert("删除订单成功!");
                                wa_track_event("删除订单", "成功", $("#hotel").data('name') + $("#hotel").data('id'));
                                N.visit(_vars.orderDelBackUrl);
                                $this.text("删除订单").removeAttr("loading");

                            }
                        });
                    }
                }
            });
            _func.cancelOrder = function (d, e) {
                e.text("取消中···").attr("loading", "loading");
                $.ajax({
                    type: "post",
                    url: _vars.url.cancelOrder,
                    data: d,
                    success: function (result) {
                        var isSuccess = result.isSuccess;
                        var oMessage = result.oMessage;
                        if (isSuccess != 1) {
                            _func.showMessage(oMessage);
                            e.text("取消订单").removeAttr("loading");
                        } else {
                            alert(_vars.message.order.cancelComplete);
                            N.visit(_vars.orderCancelBackUrl);
                            e.text("取消订单").removeAttr("loading");
                        }
                    },
                    error: function () {
                        _func.showMessage(_vars.message.order.cancelError);
                        e.text("取消订单").removeAttr("loading");
                    }
                });
            };

            $(".HpmethodV2").on('click', function () {
                var _this = $(this);
                if (_this.hasClass("cancel_submit")) {
                    return false;//阻止支付
                }
                var _resNo = _this.data('resno');
                var _token = $('[name="__RequestVerificationToken"]').val();
                if (_resNo == "" || _token == "") {
                    alert("参数错误");
                    return false;
                }
                $('.Cload').show();
                _func.postHPay(_this, _resNo, _token);
            });

        },
        MoreInfoAction: function () {
            $("#infoSubmit").on('click', function () {
                _func.clearMessage();
                if (!$(this).get(0).hasAttribute('loading')) {
                    var $key = $("#idType"),
                          $self = $(this),
                          $value = $("#idNo");
                    var value = $.trim($value.val());
                    switch ($key.val()) {
                        case 'C01':
                            value = value.toUpperCase();
                            if (!_func.isIdCardNo(value)) {
                                return;
                            }
                            break;
                        default:
                            break;
                    }
                    $(this).attr("loading", "loading");
                    _func.prePostAsync(function () {
                        _func.postMoreInfoData($self, { 'IdType': $.trim($key.val()), 'IdNo': encryptedString(_vars.rsakey, value + "#" + _vars.timestamp) });
                    });
                }
            });
        },

        MySNSAction: function () {

            $(".SNS_account .list .item .Cswitch.enable").on('click', function () {
                var obj = $.parseJSON(N.vars.BindInfo);
                var $this = $(this);
                var data = [];
                if (!$this.hasClass('enable')) {
                    return false;
                }
                for (var item in obj) {
                    if (obj[item]['ThirdPartySource'] == $this.data('id')) {
                        data = obj[item];
                        break;
                    }
                }
                $.post(_n.vars.UnBindUrl, data, function (result) {
                    if (result.isSuccess == "1") {
                        alert('解绑成功!');
                        $this.removeClass('enable');
                    } else {
                        alert(result.oMessage);
                    }
                });
            });


        },

        MyOrderAfter: function () {
            $('.tabs .tab').on('click', function () {
                var $tabs, active, current, index;
                var $self = $(this);
                if (!$self.hasClass('active')) {
                    $tabs = $self.parents('.tabs');
                    current = $tabs.data('current');
                    active = $self.data('active');
                    $tabs.removeClass(current).addClass(active).data('current', active);
                    $self.siblings('.active').removeClass('active');
                    $self.addClass('active');
                    index = $self.index();
                    $tabs.siblings('.contents').find('.tabcontent').hide().eq(index - 1).show();
                    if ($self.data('active') === 'second') {
                        $('.Member').addClass('Ldn');
                    } else {
                        $('.Member').removeClass('Ldn');
                    }
                    if ($self.data('active') === 'second' && $self.data('loaded') !== 'loaded') {
                        $self.data('loaded', 'loaded');
                        _func.loadOrder();
                    }
                }

            });
            _func.loadOrder = function () {
                $.get(N.vars.orderUrl, { 'type': '2', 'pageIndex': '1' }, function (data) {
                    if (data.indexOf('class="error"') <= 0) {
                        $('#international').html(data);
                    }
                });
            }
            _func.addPageLoadCall(function () {
                if (N.vars.triggerAccor === 1) {
                    $('.tabs .tab').eq(1).ctrigger('click');
                }
            });

            $('.HpmethodV2').on('click', function () {
                $("a").click(function (event) {
                    return false;//阻止链接跳转
                });
                if ($(this).hasClass("cancel_submit")) {
                    return false;//阻止支付
                }
                //$(".HpmethodV2").removeClass("cancel_submit")
                var _this = $(this);
                _this.parents().find(".HpmethodV2").addClass('cancel_submit')

                var _resNo = _this.data('resno');
                var _token = $('[name="__RequestVerificationToken"]').val();
                if (_resNo == "" || _token == "") {
                    alert("参数错误");
                    return false;
                }
                $('.Cload').show();
                _func.postHPay(_this, _resNo, _token);

            });
            $('#btnSwitch').on('click', function () {
                _func.loginOut();
            });

        },
        interdetailAction: function () {
            $("#cancelOrder").on('click', function () {
                var $this = $(this);
                if (!$(this).get(0).hasAttribute('loading')) {
                    var conf = confirm("确定要取消订单？");
                    if (conf) {
                        _func.cancelOrder({ "orderId": $this.data('orderid'), "tas": "true" }, $(this));
                    }
                }
            });

            _func.cancelOrder = function (d, e) {
                e.text("取消中···").attr("loading", "loading");
                $.ajax({
                    type: "post",
                    url: _vars.url.cancelOrder,
                    data: d,
                    success: function (result) {
                        var isSuccess = result.isSuccess;
                        var oMessage = result.oMessage;
                        if (isSuccess != 1) {
                            alert(oMessage);
                            e.text("取消订单").removeAttr("loading");
                        } else {
                            alert(_vars.message.order.cancelComplete);
                            N.visit(_vars.orderCancelBackUrl);
                            e.text("取消订单").removeAttr("loading");
                        }
                    },
                    error: function () {
                        alert(_vars.message.order.cancelError);
                        e.text("取消订单").removeAttr("loading");
                    }
                });
            };
        }

    });
    _n.app('PersonalCenter', {
        init: function () {
            _vars.personMinPoint = 50; //用户留底积分
            _vars.errorDiv = $(".errorS");
            $("#totalTransfer").css('display', 'none');
        },
        PointBalanceAction: function () {
            $('.Cloadlist').ctap('i.Cicon.radio', function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                    _vars.PointBalanceTotalPoint = Number(_vars.PointBalanceTotalPoint) + Number($(this).data("point"));
                } else {
                    $(this).addClass("selected");
                    _vars.PointBalanceTotalPoint = Number(_vars.PointBalanceTotalPoint) - Number($(this).data("point"));
                }
                var $selected = $("div i.selected");
                if ($selected.length > 1) {
                    $("#totalTransfer").css('display', 'block');
                } else {
                    $("#totalTransfer").css('display', 'none');
                }
            });
            $('.Cloadlist').ctap('div.sigleTransfer', function () {
                if ($(this).attr("loading") != "loading") {
                    var $selected = $("div i.selected");
                    var _totalBalance = _vars.PointBalanceTotalPoint;
                    $.map($selected, function (item) {
                        _totalBalance = Number(_totalBalance) + Number($(item).data("point"));
                    });
                    if (Number(_totalBalance) - Number($(this).data("point")) < _vars.personMinPoint) {
                        alert('至少保留' + _vars.personMinPoint + '积分');
                        return false;
                    }
                    if (Number(_vars.PointBalanceLeftTransTimes) <= 0) {
                        alert("每日限转赠20笔积分:还剩" + _vars.PointBalanceLeftTransTimes + "笔可以转赠!");
                        return false;
                    }
                    $(this).attr('loading', 'loading').addClass("gray");
                    _func.transform([{ 'PointID': $(this).data("id"), 'ExpireDate': $(this).data("expiredate"), 'OrgName': $(this).data("name"), 'RemainAmount': $(this).data("point") }]);
                    $(this).removeAttr("loading").removeClass("gray");
                }
            });
            $("#totalTransfer").on('click', function () {
                if ($(this).attr("loading") != "loading") {
                    var $selected = $("div i.selected");
                    if (_vars.PointBalanceTotalPoint < _vars.personMinPoint) {
                        alert("账户至少留存" + _vars.personMinPoint + "积分");
                        return false;
                    }
                    if (Number(_vars.PointBalanceLeftTransTimes) - $selected.length < 0) {
                        alert("每日限转赠20笔积分：您当前还可以转赠" + _vars.PointBalanceLeftTransTimes + "笔积分");
                        return false;
                    }
                    var datas = [];
                    $.map($selected, function (item) {
                        var data = {};
                        data["PointID"] = $(item).data("id");
                        data["OrgName"] = $(item).data("name");
                        data["ExpireDate"] = $(item).data("expiredate");
                        data["RemainAmount"] = $(item).data("point");
                        datas.push(data);
                    });
                    $(this).attr("loading", "loading").addClass("gray");
                    _func.transform(datas);
                    $(this).removeAttr("loading").removeClass("gray");
                }
            });
        },
        BalanceDonateAction: function () {
            $("i.Cicon.radio").ctap(function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                    _vars.PointBalanceTotalPoint = Number(_vars.PointBalanceTotalPoint) - Number($(this).data("point"));
                } else {
                    $(this).addClass("selected");
                    _vars.PointBalanceTotalPoint = Number(_vars.PointBalanceTotalPoint) + Number($(this).data("point"));
                }
                $("#totalBalance").html(_vars.PointBalanceTotalPoint + " 积分");
            });
            $("#txtPhone").on('input', function () {
                $(this).val($(this).val().replace(/[+-\/]/g, ''));
                var reg = new RegExp('^' + '86');
                if (reg.test($(this).val())) {
                    $(this).val($(this).val().substr(2));
                }
            });

            $("#btnDonate").ctap(function () {
                if ($(this).attr("loading") != "loading") {
                    var $btnConfirm = $(this),
                        $phone = $("#txtPhone"),
                        $name = $("#txtName"),
                        $captcha = $("#txtCaptcha");
                    if (_vars.PointBalanceTotalPoint <= 0) {
                        alert("必须选择要转赠的积分");
                        return false;
                    }
                    if (!$phone.val().length || $phone.val().length <= 0) {
                        alert(_vars.message.empty.phone);
                        return false;
                    }
                    if (!$name.val().length || $name.val().length <= 0) {
                        alert(_vars.message.empty.name);
                        return false;
                    }
                    if ($("#captcha")[0].style.display != "none") {
                        if (!$captcha.val().length || $captcha.val().length <= 0) {
                            alert(_vars.message.empty.captcha);
                            return false;
                        }
                    }
                    if (!_func.isCellPhone($.trim($phone.val()))) {
                        alert(_vars.message.validate.rightPhone);
                        return false;
                    }
                    $btnConfirm.attr("loading", "loading").addClass("gray");
                    var $selected = $("div i.selected");
                    var datas = [];
                    $.map($selected, function (item) {
                        var data = {};
                        data["PointID"] = $(item).data("id");
                        data["OrgName"] = $(item).data("name");
                        data["ExpireDate"] = $(item).data("expiredate");
                        data["RemainAmount"] = $(item).data("point");
                        datas.push(data);
                    });
                    _func.prePostAsync(function () {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "/PersonalCenter/BalanceDonate/",
                            data: { "data": JSON.stringify(datas), "phone": encryptedString(_vars.rsakey, $.trim($phone.val()) + "#" + _vars.timestamp), "name": encryptedString(_vars.rsakey, $.trim($name.val()) + "#" + _vars.timestamp), "captcha": $("#txtCaptcha").val() },
                            success: function (data) {
                                if (data.IsSuccess == 1) {
                                    //                                    window.location.href = '/PersonalCenter/BalanceDonateConfirm/';
                                    N.visit('/PersonalCenter/BalanceDonateConfirm/');
                                } else {
                                    if (data.errorType == 3) {
                                        //                                        window.location.href = _vars.url.login + "?returnUrl=" + window.location.href;
                                        N.visit(_vars.url.login + "?returnUrl=" + window.location.href);
                                        return;
                                    }
                                    if (data.errorCount >= 3) {
                                        $("#captcha").css("display", "");
                                        $("#txtCaptcha").val('');
                                        $("#imgCaptcha").attr("src", "/Account/GetImgCaptcha/?datetime=" + Date.now());
                                    }
                                    alert(data.oMessage.length && data.oMessage.length > 0 ? data.oMessage : '转赠积分出现错误');
                                }
                            },
                            error: function (data) {
                                alert('转赠积分出现错误');
                            }
                        });
                    });
                    $btnConfirm.removeAttr("loading").removeClass("gray");
                }
            });
        },
        BalanceDonateConfirmAction: function () {
            $("#btnCaptcha").ctap(function () {
                if (!$(this).attr("disabled")) {
                    var $btnSms = $(this);
                    $("#divSendMsg").css('display', 'none');
                    $(this).attr("disabled", true);
                    $.post('/Account/SendSms/', { "CaptchaType": "BalanceDonate" }, function (data) {
                        if (data.isSuccess == 1) {
                            _vars.sendSms.init($("#btnCaptcha"));
                            $("#divSendMsg").css('display', '');
                        } else {
                            $btnSms.removeAttr("disabled");
                            alert(data.oMessage);
                        }
                    }, 'json');
                }
            });
            $("#donateConfirm").ctap(function () {
                if (!$(this).attr("loading") != "loading") {
                    var $btnDonate = $(this);
                    $btnDonate.attr('loading', 'loading').addClass("gray");
                    if (!$("#txtCaptcha").val() || $.trim($("#txtCaptcha").val()).length <= 0) {
                        alert(_vars.message.empty.captcha);
                        $btnDonate.removeAttr("loading").removeClass("gray");
                        return false;
                    }
                    $.ajax(
                        {
                            type: 'POST',
                            dataType: 'json',
                            url: "/PersonalCenter/BalanceDonateConfirm/",
                            cache: false,
                            data: { "pCaptcha": $.trim($("#txtCaptcha").val()) },
                            success: function (result) {
                                if (result.isSuccess == "1") {
                                    _gaq.push(['_trackEvent', '积分赠送', _vars.PointBalanceTransType, _vars.memberid, Number(_vars.PointBalanceTotalPoint)]);
                                    alert("转赠积分成功!");
                                    $btnDonate.removeAttr("loading").removeClass("gray");
                                    //                                window.location.href = "/PersonalCenter/PointBalance/";
                                    N.visit("/PersonalCenter/PointBalance/");
                                } else {
                                    if (result.errorType == 3) {
                                        //                                    window.location.href = _vars.url.login + "?returnUrl=" + window.location.href;
                                        N.visit(_vars.url.login + "?returnUrl=" + window.location.href);
                                        return;
                                    }
                                    var message = "";
                                    if (result.Points && result.Points.length > 0) {
                                        message = result.Points + " 转赠失败";
                                    }
                                    if (result.oMessage && result.oMessage.length > 0) {
                                        if (message != "") {
                                            message += " 原因:" + result.oMessage;
                                        } else {
                                            message += result.oMessage;
                                        }
                                    }
                                    if (message == "") {
                                        message = "转赠积分出现错误!";
                                    }
                                    $btnDonate.removeAttr("loading").removeClass("gray");
                                    alert(message);
                                }
                            },
                            error: function (data) {
                                $btnDonate.removeAttr("loading").removeClass("gray");
                                alert("转赠积分出现错误!");
                            }
                        });
                }
            });
        },
        ECouponAction: function () {
            $(".bgNight").on('click', function () {
                var $self = $(this),
                    $select = $(".select");
                if (!($self.children().hasClass('textFormBlue'))) {
                    $self.children().addClass('textFormBlue');
                    $self.siblings().children().removeClass('textFormBlue');
                    $select.eq($self.index()).addClass('selectFirst').siblings().removeClass('selectFirst');
                    var s = '.' + $self.data('type');
                    $('a.item').filter(s).removeClass('Ldn');
                    $('a.item').not(s).addClass('Ldn');
                }
            });
        },
        ECouponDetailAction: function () {
            $("#divToggle").on('click', function () {
                var $ulList = $("#ulList");
                var toggle = $(this).children();
                if (toggle.hasClass('icon_arrowDown')) {
                    toggle.removeClass('icon_arrowDown').addClass('icon_arrowUp');
                    $ulList.removeClass("hMin");
                } else {
                    toggle.removeClass('icon_arrowUp').addClass('icon_arrowDown');
                    $ulList.addClass("hMin");
                }
            });

            //查询酒店
            $("#btnCheck").on('click', function () {
                var filter = $("#txtHotel").val();
                var isNoHotel = true;

                $(".hotellist a").each(function () {
                    $(this).toggle($(this).data("hotel").indexOf(filter) >= 0);
                    if ($(this).css('display') !== 'none') {
                        isNoHotel = false;
                    }
                });

                $(".emptynotice").toggle(isNoHotel);
            });
        },
        BindECouponAction: function () {
            $(".btnBind").on('click', function () {
                if ($(this).data("loading") != "loading") {
                    var $self = $(this),
                        $num = $("#txtEoupon");
                    if (!_func.CheCkEmpty($num, "券号", alert)) {
                        return false;
                    }
                    //                    $self.data("loading", "loading");
                    $.post(_vars.ecoupon_bind, { 'ticketNo': $.trim($num.val()) }, function (result) {
                        if (result.isSuccess == 1) {
                            alert('绑定优惠券成功!');
                            //                            window.location.href = _vars.ecoupon_url;
                            N.visit(_vars.ecoupon_url);
                        } else {
                            alert(result.oMessage && result.oMessage.length > 0 ? result.oMessage : '请输入正确的优惠券!');
                        }
                    });
                }
            });
        },
        EPromotionAction: function () {
            $("div.bgNight").on('click', function () {
                var $self = $(this),
                    $select = $(".select");
                if (!($self.children().hasClass('textFormBlue'))) {
                    $self.children().addClass('textFormBlue');
                    $self.siblings().children().removeClass('textFormBlue');
                    $select.eq($self.index()).addClass('selectFirst').siblings().removeClass('selectFirst');
                    var s = '.' + $self.data('type');
                    $('a.item').filter(s).removeClass('Ldn');
                    $('a.item').not(s).addClass('Ldn');
                }
            });
        },
        EPromotionDetailAction: function () {
            $("#divToggle").on('click', function () {
                var $ulList = $("#ulList");
                var toggle = $(this).children();
                if (toggle.hasClass('icon_arrowDown')) {
                    toggle.removeClass('icon_arrowDown').addClass('icon_arrowUp');
                    $ulList.removeClass("hMin");
                } else {
                    toggle.removeClass('icon_arrowUp').addClass('icon_arrowDown');
                    $ulList.addClass("hMin");
                }
            });
        },
        WalletAfter: function () {
            var $list;
            $list = $('.Cloadlist');
            if (parseInt($list.data('total')) > 0) {
                $('.Cloadlist').siblings('.loadmore').datalazyload({
                    repeat: true,
                    loader: function () {
                        _func.lazyLoadMore($list);
                    }
                }).addClass('enable');
            }
        },
        breakfastAction: function () {
            $(".BFC_main .tabs div").on('click', function () {
                var $this = $(this),
                    type = $(this).data('type');
                if ($this.hasClass('curr')) {
                    return;
                }
                $this.addClass('curr').siblings().removeClass('curr');
                $(".couponbox .item").filter("." + type).removeClass('Ldn');
                $(".couponbox .item").not("." + type).addClass('Ldn');
                showEmpty(type);
            });

            var showEmpty = function (typeCss) {
                var filter = "." + typeCss;
                var $empty = $(".couponbox .item").filter(filter);
                if ($empty.length <= 0) {
                    $(".couponbox .empty").removeClass('Ldn');
                } else {
                    $(".couponbox .empty").addClass('Ldn');
                }
            };
            showEmpty('unused');

            $("#btnBind").on('click', function () {
                var $input = $("#txtEoupon");
                if (!$input.val()) {
                    return false;
                }
                $.post(_vars.breakfastbindurl, { 'tickNo': $input.val() }, function (d) {
                    if (d.isSuccess == "1") {
                        alert('绑定早餐券成功!');
                        N.visit(window.location.href);
                    } else {
                        if (d.oMessage) {
                            alert(d.oMessage);
                        } else {
                            alert("绑定优惠券出现错误!");
                        }
                    }
                });
            });
        },
        BreakfastDetailAction: function () {
            $(".Chead .btn").on('click', function () {
                $(".Cpopup.snsshare").removeClass('Ldn');
            });
            $(".Cpopup.snsshare").on('click', function (e) {
                $(this).addClass('Ldn');
            });
            N.func.BreakfastShowSuccess = function (info) {
                var $msg = $(".Cpopup.breakfast");
                var $success = $msg.find('.cbox.success');
                var $error = $msg.find('.cbox.error');
                $('.cbox.success .large_ok').after(info);
                $msg.removeClass('Ldn');
                $success.removeClass('Ldn');
                $error.addClass('Ldn');
                location.href = N.vars.breakfastListUrl;
            }


            N.func.BreakfastShowError = function (msg) {
                var $msg = $(".Cpopup.breakfast");
                var $success = $msg.find('.cbox.success');
                var $error = $msg.find('.cbox.error');
                if (msg) {
                    $('.cbox.error .info').html(msg);
                }
                $msg.removeClass('Ldn');
                $success.addClass('Ldn');
                $error.removeClass('Ldn');

            }
            $(".Cpopup.breakfast").on('click', function (e) {
                $el = $(e.target || e.srcElement);
                if (($el.hasClass('inner') || $el.hasClass('wrap')) && !$(this).find('.cbox.error').hasClass('Ldn')) {
                    $(this).addClass('Ldn');
                }
            });

            $("#breakfastCancel").on('click', function () {
                var $this = $(this);
                if ($this.data('loading') != 'loading') {
                    $this.data('loading', 'loading');
                    $this.find('a').addClass('gray');
                    $.post(N.vars.breakfastCancelUrl, { 'queryOrderNo': $(this).data('queryno') }, function (result) {
                        if (result.isSuccess === "1") {
                            N.func.BreakfastShowSuccess(result.num && result.num > 1 ? "剩余" + result.num + "份早餐券已取消" : "订单取消成功！");
                        } else {
                            N.func.BreakfastShowError(result.msg);
                            $this.data('loading', '');
                            $this.find('a').removeClass('gray');
                        }
                    });
                }
            });
        },
        invoiceAction: function () {
            function display(action, type) {
                $('.errorS').hide();
                $('.INV_info').removeClass('Ldn');
                $('.MO_main').addClass('Ldn');
                $('.INV_info .content .block input[type=text]').not('.disable').not('[readonly=readonly]').removeAttr('readonly');
                $("#InvoiceEdit").hide();
                if (action === 'add') {
                    $('#btnSubmitInvoice').show();
                    $('.tabs').show();

                    $('.INV_info .content').find('.block.first').removeClass('Ldn');
                    $('.INV_info .content').find('.block.second').addClass('Ldn');
                    var block = $('.INV_info .content').find('.block');
                    block.find('.name').val('');
                    block.find('.socialnum').val('');
                    block.find('.taxnum').val('');
                    block.find('.address').val('');
                    block.find('.phone').val('');
                    block.find('.bank').val('');
                    block.find('.banknum').val('');

                }
                if (action === 'edit') {
                    $('#btnSubmitInvoice').hide();
                    $("#InvoiceEdit").css('display', 'block');
                    $('.tabs').hide();
                    if (type === 'common') {
                        $('.INV_info .content').find('.block.first').removeClass('Ldn');
                        $('.INV_info .content').find('.block.second').addClass('Ldn');
                    } else {
                        $('.INV_info .content').find('.block.second').removeClass('Ldn');
                        $('.INV_info .content').find('.block.first').addClass('Ldn');
                    }
                    $('.INV_info .content .block input[type=text]').not('.disable').attr('readonly', 'readonly');
                    
                }
            }

            $('#InvoiceAdd').on('click', function () {
                display('add');
            });

            $("#btnBack").on('click', function () {
                $('.MO_main').removeClass('Ldn');
                $('.INV_info').addClass('Ldn');
            });
            $(".INV_info .LI_main .tabs .tab").on('click', function () {

                var thisType = $(this).data('active'),
                    otherType = $(this).siblings('.tab').data('active'),
                    $this = $(this);

                if ($this.hasClass('active')) {
                    return;
                }


                $this.addClass('active');
                $this.siblings().removeClass('active');
                $this.parent('.tabs').addClass(thisType).removeClass(otherType);


                $('.INV_info .content').find('.block.' + thisType).removeClass('Ldn');
                $('.INV_info .content').find('.block.' + otherType).addClass('Ldn');

            });



            $("#btnSubmitInvoice").on('click', function () {
                $('.errorS').hide();
                var invoiceId = _vars.invoiceid.split('-');
                var type = $(".LI_main .tabs .tab.active").data('active');
                if (_vars.invoiceid !== '') {
                    type = invoiceId[1];
                }
                var block = $('.INV_info .content').find('.block.' + type);

                if (type === 'first') {
                    if (!block.find('.name').val()) {
                        _func.alert("请输入发票抬头！");
                        return;
                    }
                    if (!block.find('.idNo').val()) {
                        _func.alert("请输入证件号！");
                        return;
                    }
                    if (_vars.IdNo === '' && block.find('#idType').val() === 'C01' && !_func.isIdCardNo(block.find('.idNo').val())) {
//                        _func.alert("请输入正确的证件号！");
                        return;
                    }
                    $('#hidInvoiceTitle').val(block.find('.name').val());
                    $('#hidInvoiceIdNo').val(block.find('.idNo').val());
                    $('#hidInvoiceIdType').val(block.find('#idType').val());
                }
                if (type === 'second') {
                    if (!block.find('.name').val()) {
                        _func.alert("请输入发票抬头！");
                        return;
                    }
                    if (!block.find('.idNo').val()) {
                        _func.alert("请输入证件号！");
                        return;
                    }
                    if (_vars.IdNo === '' && block.find('#idType').val() === 'C01' && !_func.isIdCardNo(block.find('.idNo').val())) {
                        //                        _func.alert("请输入正确的证件号！");
                        return;
                    }
                    if (!block.find('.socialnum').val() && !block.find('.taxnum').val()) {
                        _func.alert("请输入纳税人识别号或统一社会信用代码！");
                        return;
                    }
                    var len = block.find('.taxnum').val().length;
                    if (block.find('.taxnum').val() && len !== 15 && len !== 17 && len !== 18 && len !== 20) {
                        _func.alert("纳税人识别号输入长度有误，请检查！");
                        return;
                    }
                    if (!block.find('.address').val()) {
                        _func.alert("请输入注册地址！");
                        return;
                    }
                    if (!block.find('.phone').val()) {
                        _func.alert("请输入联系电话！");
                        return;
                    }
                    if (!block.find('.bank').val()) {
                        _func.alert("请输入开户银行！");
                        return;
                    }
                    if (!block.find('.banknum').val()) {
                        _func.alert("请输入开户账号！");
                        return;
                    }
                    $('#hidInvoiceTitle').val(block.find('.name').val());
                    $('#hidInvoiceSocial').val(block.find('.socialnum').val());
                    $('#hidInvoiceTaxNum').val(block.find('.taxnum').val());
                    $('#hidInvoiceAddress').val(block.find('.address').val());
                    $('#hidInvoicePhone').val(block.find('.phone').val());
                    $('#hidInvoiceBank').val(block.find('.bank').val());
                    $('#hidInvoiceBankNum').val(block.find('.banknum').val());
                    $('#hidInvoiceIdNo').val(block.find('.idNo').val());
                    $('#hidInvoiceIdType').val(block.find('#idType').val());
                }
                if (type) {
                    $('#hidInvoiceType').val(type === 'first' ? 'common' : 'spiclal');
                }

                if (_vars.invoiceid === '') {
                    $.post(_vars.saveUrl, $('#invoice').serialize(), function (data) {
                        if (data.isSuccess === "1") {
                            window.location = N.vars.next;
                        } else {
                            alert("保存发票出现错误!");
                        }
                    });
                } else {
                    $.post(_vars.modifyUrl + '?id=' + invoiceId[0], $('#invoice').serialize(), function (data) {
                        if (data.isSuccess === true) {
                            alert("修改成功!");
                            window.location = N.vars.next;
                        } else {
                            alert("保存发票出现错误!");
                        }
                    });
                }
            });


            $('.quickorder.INV_info_popup .room').on('click', function () {
                var $this = $(this).find('i.small');
                var selcted = $this.hasClass('selected');
                $('.quickorder.INV_info_popup .room i.small').removeClass('selected');
                $('.quickorder.INV_info_popup .room .roomtype').removeClass('selected');
                $('.quickorder.INV_info_popup .room .smallbtn').addClass('Ldn');
                _vars.invoiceid = '';
                if (!selcted) {
                    $this.addClass('selected');
                    $this.siblings('.roomtype').addClass('selected');
                    $("#InvoiceAdd").addClass('Ldn');
                    $("#InvoiceCheck").removeClass('Ldn');
                    $(this).find('.smallbtn').removeClass('Ldn');
                    _vars.invoiceid = $(this).find('.smallbtn').data('id') + '-' + $(this).find('.smallbtn').data('type');
                } else {
                    $("#InvoiceCheck").addClass('Ldn');
                    $("#InvoiceAdd").removeClass('Ldn');
                }
            });

            $('#InvoiceCheck').on('click', function () {
                $('.errorS').hide();
                var i = $('.quickorder.INV_info_popup .room i.small.selected');
                if (i && i.data('id')) {
                    var invoices = JSON.parse(_vars.invoices);
                    invoices.map(function (item) {
                        if (item['Tid'] === i.data('id')) {
                            var block = $('.INV_info .content').find('.block');
                            block.find('.name').val(item['Title']);
                            block.find('.socialnum').val(item['UnifiedSocialCreditCode']);
                            block.find('.taxnum').val(item['TaxpayerCode']);
                            block.find('.address').val(item['CompanyAddress']);
                            block.find('.phone').val(item['PhoneNumber']);
                            block.find('.bank').val(item['CompanyBank']);
                            block.find('.banknum').val(item['CompanyBankAccountNumber']);
                            if (item['TaxpayerCode'] !== '') {
                                block.find('.socialnum').val('');
                            }
                            display('edit', item['IsVat'] === true ? 'special' : 'common');
                        }

                    });
                } else {
                    var block = $('.INV_info .content').find('.block');
                    block.find('.name').val('');
                    block.find('.socialnum').val('');
                    block.find('.taxnum').val('');
                    block.find('.address').val('');
                    block.find('.phone').val('');
                    block.find('.bank').val('');
                    block.find('.banknum').val('');
                }
            });

            $(".quickorder.INV_info_popup .room .smallbtn").on('click', function () {
                var $this = $(this);
                if ($this.data('init') === 'inited') {
                    return;
                }
                $this.data('init', 'inited');
                $.post(_vars.delUrl, { 'id': $this.data('id') }, function (result) {
                    if (result.isSuccess === true) {
                        alert('删除成功');
                        $this.parent().parent('.room').remove();
                        if ($('.quickorder.INV_info_popup .room').length <= 0) {
                            N.visit(N.vars.next)
                        }
                    } else {
                        alert("删除失败,请稍后再试!");
                        $this.data('init', '');

                    }
                });
            });


            $("#InvoiceEdit").on('click', function () {
                $('.INV_info .content .block input[type=text]').not('.disable').not('.idNo').removeAttr('readonly');
                if ($('.taxnum').val() !== '') {
                    $('.socialnum').attr('readonly', 'readonly').css('background-color', '#d1d1d1')
                }
                if ($('.socialnum').val() !== '') {
                    $('.taxnum').attr('readonly', 'readonly').css('background-color', '#d1d1d1')
                }
                $('#btnSubmitInvoice').show();
            });

            $('.socialnum').on('input', function () {
                var $this = $(this),
                    $tax = $('.taxnum');
                if ($this.val().trim() !== '') {
                    $tax.val('').attr('readonly', 'readonly').css('background-color', '#d1d1d1')
                } else {
                    $tax.removeAttr('readonly').css('background-color', '')
                }
            }).on('click', function () {
                var $this = $(this);
                if ($this.attr('readonly') === 'readonly' && $('.taxnum').attr('readonly') !== 'readonly') {
                    alert('删除纳税人识别码后方可填写!');
                }
            });
            $('.taxnum').on('input', function () {
                var $this = $(this),
                    $tax = $('.socialnum');
                if ($this.val().trim() !== '') {
                    $tax.val('').attr('readonly', 'readonly').css('background-color', '#d1d1d1')
                } else {
                    $tax.removeAttr('readonly').css('background-color', '')
                }
            }).on('click', function () {
                var $this = $(this);
                if ($this.attr('readonly') === 'readonly' && $('.socialnum').attr('readonly') !== 'readonly') {
                    alert('删除统一社会信用代码后方可填写!');
                }
            });

            $('.block select').on('change', function () {
                var $this = $(this),
                    val = $this.val(),
                    $show = $this.siblings('.showtime'),
                    $option = $this.children('option').not(function () { return !this.selected; });
                if (val) {
                    $show.text($option.text());
                } else {
                    $show.text($show.data('msg'));
                }

            });

        }
    });
    _n.app('promotion', {
        init: function () {
        },
        indexAction: function () {
            $('#activityselect a').on('click', function () {
                //                window.location.href = '/event?activityId=' + $(this).val();
                var activityId = $(this).data('id');
                $.post(_vars.hotelParaSetUrl, { "activityId": activityId }, function () {
                    N.visit('/event?first=first&activityId=' + activityId);
                });
            });
            $('#dayselect').on('change', function () {
                //                window.location.href = '/event?dayCount=' + $(this).val();
                //                N.visit('/event?dayCount=' + $(this).val());
                $.post(_vars.hotelParaSetUrl, { "dayCount": $(this).val() }, function () {
                    N.visit('/event?isEdit=Y');
                });
            });
        },
        EPromotionDetailAction: function () {
            $("#divToggle").on('click', function () {
                var $ulList = $("#ulList");
                var toggle = $(this).children();
                if (toggle.hasClass('icon_arrowDown')) {
                    toggle.removeClass('icon_arrowDown').addClass('icon_arrowUp');
                    $ulList.removeClass("hMin");
                } else {
                    toggle.removeClass('icon_arrowUp').addClass('icon_arrowDown');
                    $ulList.addClass("hMin");
                }
            });
        },
    });
    _n.app('hotel', {
        init: function () {
        },
        surroundAction: function () {
            $("#divCat div").on("click", function () {
                var $this = $(this),
                    $subling = $(this).siblings('.hover');
                if (!$this.hasClass('hover')) {
                    $subling.removeClass('hover');
                    $subling.children('i').removeClass('hover');
                    $(this).addClass('hover');
                    $this.children('i').addClass('hover');
                }
                var cat = $this.data("catid");
                var catId = '.cat' + cat;
                var icon = $this.data("icon");
                $("#divItem>div").not(catId).addClass('Ldn');
                var $items = $("#divItem>div").filter(catId);
                if ($items.length > 0) {
                    $items.removeClass('Ldn');
                    _n.hook('lbschange', [cat, icon]);
                } else {
                    $.post("/Hotel/MoreSurroundInfo", { 'hotelId': _vars.SurroundHotelId, 'catId': $this.data('catid') }, function (data) {
                        if (typeof data == 'string') {
                            data = $.parseJSON(data);
                        }
                        var html = '', obj;
                        for (var i = 0; i < data.length; i++) {
                            obj = data[i];
                            //                            msg = obj["Type"] == "G" ? "预期最高收益" : "累计送出";
                            html += '<a href=' + obj["BusinessURL"] + ' class="cat' + obj["CategoryID"] + '_' + obj.BusinessID + '"><div class="item pure-g"><div class="pure-u-1-4 hotelimg"><img src="' + obj["PhotoURL"] + '"></div><div class="pure-u-3-4 hotelinfo"><h3 class="hname">' + obj["BusinessName"] + '</h3><div class="pure-g Lmt5"><div class="pure-u-3-5 Ctextover"><span class="address">' + obj["Address"] + '</span></div><div class="pure-u-2-5 price_area disable"><span class="price"><i>均价￥</i>' + obj["AveragePrice"] + '</span></div></div><div class="pure-g"><div class="pure-u-3-5 score_area"><span class="Cscore">';
                            for (var j = 1; j <= 5; j++) {
                                var str = "<i class='Cicon score_";
                                str += Number(obj["AverageRating"]) - j <= -1 ? "empty" : Number(obj["AverageRating"]) - j <= -0.5 ? "half" : "full";
                                str += "'></i>";
                                html += str;
                            }
                            html += '</span><span>' + obj["AverageRating"] + '分</span></div><div class="pure-u-2-5 Ltar distance_area"><span class="distance">' + obj["Distance"] + '米</span></div></div> </div></div></a>';
                        }
                        if (data.length) {
                            html = '<div class="cat' + obj.CategoryID + '">' + html + '</div>';
                        }
                        $("#divItem").append(html);
                        ndoo.hook('lbschange', [cat, icon, data]);
                    });
                }
            });
        }
    });
    /* 模块定义 */
    _n.app('home', {
        init: function () { },
        indexAction: function () {
            var showDashboard = function () {
                $('.Cdashboard').addClass('expand');
                setTimeout(function () {
                    $('.Cdashboard .item').addClass('active');
                }, 1);
            };
            var hideDashboard = function (force) {
                $('.Cdashboard .item').removeClass('active');
                if (!force) {
                    $('.Cdashboard').addClass('shrinking');
                    setTimeout(function () {
                        $('.Cdashboard').removeClass('expand shrinking');
                    }, 300);
                }
                else {
                    $('.Cdashboard').removeClass('expand shrinking');
                }
            };
            _func.addPageBeforeUnloadCall('hideDashBoard', function () {
                hideDashboard('force');
            });

            $('.Cdashboard .inner').on('click', function (e) {
                $el = $(e.target || e.srcElement);
                if ($el.hasClass('inner') || $el.hasClass('wrap')) {
                    hideDashboard();
                }
            }).on('touchmove', _func.preventScroll);

            $('.Cdashboard .main').ctap(function (e) {
                if ($(this).parent()) {
                    showDashboard();
                    wa_track_event("首页", "点击菜单按钮", wa_member_id);
                    // $(this).parent().addClass('expand');
                }
            });
            $('.Cdashboard .dashclose').ctap(function () {
                hideDashboard();
                // $('.Cdashboard').removeClass('expand');
            });
            /* select 功能 */
            var $select;
            _func.dayselectchange = function () {
                var $form;
                $form = $('#queryForm');
                $.post(_vars.hotelParaSetUrl, $form.serialize(), function (res) { });
            };
            $select = $('select.fixheight');
            $select.on('change', function () {
                var $item, cdate, val;
                val = $select.val();
                $item = $select.parent().siblings('a[data-date]');
                if ($item) {
                    cdate = new Date($item.data('date'));
                } else {
                    cdate = new Date();
                }
                cdate.setDate(cdate.getDate() + parseInt(val));
                $('.dayselectInfo').html("<span>住 " + val + "<text> 晚</text></span>" + (cdate.getMonth() + 1) + "月" + (cdate.getDate()) + "日 周" + _vars.week.cn[cdate.getDay()] + " 离店</div>");
                $('#hotelCheckOutDate').val("" + (cdate.getFullYear()) + "-" + (cdate.getMonth() + 1) + "-" + (cdate.getDate()));
                _func.dayselectchange();
            });
            _func.addPageBeforeUnloadCall('cleanMapStor', function () {
                var listener;
                _stor('geomap', null, null, true);
                if (listener = _stor('hotelIndexListener')) {
                    AMap.event.removeListener(listener['complete']);
                    AMap.event.removeListener(listener['error']);
                    _stor('hotelIndexListener', null, null, true);
                }
            });
            _n.hook('switchRegion', function (e, f) {
                _n.hook('loading');
                var $self = $(e);
                var param = { "cityid": $self.data('cityid'), "cityname": (encodeURIComponent($self.data('cityname'))), 'LatLng': '' };
                if (window.AMap) {
                    _n.hook('geolocation');
                } else {
                    _func.addScript(_vars.mapurl + '&callback=wrapMapCallback');
                }
                _func.homeSwitchRegion = function (latLgn, cityName, cityId) {
                    //当前状态为全国
                    if ($self.data('region') === 'flag') {
                        param['cityname'] = encodeURIComponent('附近酒店');
                        param['LatLng'] = latLgn;
                        param['cityid'] = cityId;
                    } else {
                        param['cityname'] = encodeURIComponent(cityName);
                        param['cityid'] = cityId;
                    }
                    $.post(_vars.hotelParaSetUrl, param, function () {
                        var $hotelInfo = $('.hotelinfo');
                        if (!$self.data('hotelCount') && $self.data('region') === 'flag') {
                            $.get(_vars.hotelList, function (data) {
                                $("#HotelCount").html(data);
                                var count = $("#HotelCount").find('.location .info').html().replace('(', '').replace(')', '');
                                $self.data('hotelCount', count);
                                f();
                                $hotelInfo.data('count', count).data('currCount', 0).data('state', $self.data('region'));
                                _func.hotelCountAnimate();
                                _n.hook('hideloading');
                            });
                        } else {
                            f();
                            $hotelInfo.data('count', $self.data('region') === 'local' ? $self.data('hotelCount') : $self.data('hotels')).data('currCount', 0).data('state', $self.data('region'));
                            _func.hotelCountAnimate();
                            _n.hook('hideloading');
                        }

                    });
                };
                return false;
            });

        },
        indexSepAction: function () {
            $('.searchbox input.input1').on('focus', function () {
                N.visit(N.vars.globalSearchUrl);
            });
        },

        index2016Action: function () {

            resetHtmlFontSize = function () {
                var _target = document.getElementById('scriptArea')
                var _targetPageId = _target.getAttribute('data-page-id')
                var width = parseInt(window.screen.width);
                if (width > 414) {
                    width = 414
                }
                var fontsise = width / 3.2;
                if (_targetPageId == 'home/index2016') {
                    if (top.location == self.location) {
                        document.getElementsByTagName('html')[0].setAttribute("style", "font-size:" + fontsise + 'px')
                    }
                } else {
                    document.getElementsByTagName('html')[0].setAttribute("style", "font-size:100%")
                }
            }
            resetHtmlFontSize()
            _func.addPageRestoreCall('resetHtmlFontSize', function () {
                resetHtmlFontSize()
            });
            _func.addRestoreCall('resetHtmlFontSize', function () {
                resetHtmlFontSize()
            })
            /* $('.sub_search input.input_search').on('focus', function () {
                 N.visit(N.vars.cityUrl);
             });*/
            $('#showSelectCity').on('click', function () {
                N.visit(N.vars.cityUrl);
            });

            _func.addPageLoadCall(function () {
                new SwipeSlide($('#dashboard-widgets'), {
                    visibleSlides: 3,
                    directionalNavigation: true,
                    bulletNavigation: true,
                    autoPlayTime: 2500,
                    autoPlay: true
                })
            })

        }
    });


    _n.app('customize', {
        init: function () {
            _func.GetProductStr = function () {
                var str = '';
                $.map(_vars.CustomizeProduct, function (item, name) {
                    str += name + ',' + item.num + '|';
                });
                str = str.length > 0 ? str.substr(0, str.length - 1) : str;
                return str;
            };
            _func.CustomizeCal = function () {
                var $pricearea, num, sum;
                $pricearea = $('div.pricearea');
                num = sum = 0;
                $.map(_vars.CustomizeProduct, function (item) {
                    num += item.num;
                    sum += item.num * item.money;
                });
                if (N.pageId == 'customize/cart') {
                    $('.Chead .title').html('购物车(' + num + ')');
                    $("#divTotalPrice").find('.price').html('<i>￥</i>' + sum);
                }
                if (N.pageId == 'customize/index') {
                    $pricearea.html('<span class="Lfll"><b>' + num + '</b>项升级</span><span>总价：</span><span class="price"><i>￥</i>' + sum + '</span>');
                }
                $.fn.cookie('customizeproduct', _func.GetProductStr(), {
                    expires: 1,
                    path: '/'
                });
            };
        },

        indexAction: function () {
            _func.addPageLoadCall(function () {
                if (JSON.stringify(_vars.CustomizeProduct) != '{}') {
                    var $btnBox;
                    $btnBox = $('.btnbox');
                    $btnBox.removeClass('Ldn');
                }
            });


            $(".list .item .add").on('click', function () {
                var money, pid, $item, $btnBox, $tip;
                $btnBox = $('.btnbox');

                if ($btnBox.hasClass('Ldn')) {
                    $btnBox.removeClass('Ldn');
                }
                $item = $(this).parents('div.item');
                $tip = $item.find('.tip');
                money = Number($item.data('price'));
                pid = $item.data('pid');
                if (_vars.CustomizeProduct[pid]) {
                    if (_vars.CustomizeProduct[pid].num >= 100) {
                        return false;
                    }

                    _vars.CustomizeProduct[pid].num = _vars.CustomizeProduct[pid].num + 1;
                } else {
                    _vars.CustomizeProduct[pid] = { 'money': money, num: 1 };
                }
                $tip.removeClass('Ldn');
                $tip.find('b').html('+' + _vars.CustomizeProduct[pid].num);
                _func.customizeCartAanimate($(this));
                _func.CustomizeCal();

            });
            $(".list .item a").on('click', function () {
                var $this, $item, $mask, query;
                $this = $(this);
                $mask = $(".Cmask");
                $item = $this.parents('.item');
                $mask.removeClass('Ldn');
                query = '#' + $item.data('sid');
                var $dialog = $(query);
                var $content = $dialog.find('.content');
                if ($dialog.data('init') != 'inited') {
                    $dialog.addClass("Lvh").removeClass('Ldn');
                    $dialog.data('init', 'inited');
                    $content.html("<div class='iscroll_wrapper'>" + $content.html() + "</div>");
                    var height = $content.height(), wheight = window.innerHeight;
                    height = Math.min(height, wheight - 200)
                    $content.css('height', height);
                    if ($content.height() + 200 >= window.innerHeight) {
                        if (!_func.isWP()) {
                            $content.css('overflow-y', 'hidden');
                            new IScroll($content.get(0), { scrollbar: true, click: true });
                        }
                        else {
                            $content.css('overflow-y', 'auto');
                        }
                    }
                    $dialog.removeClass('Lvh');
                }
                else {
                    $dialog.removeClass('Ldn');
                }
            });
            $(".Cmask").on('click', function (e) {
                var $target = $(e.target || e.srcElement);
                if ($target.hasClass('close') || $target.parents('.close').length || $target.hasClass('inner')) {
                    $target.parents('.Cmask').addClass('Ldn');
                    $('.detailbox').addClass('Ldn');
                    return;
                }
            });
            $('.list .info').on('click', function () {

            });

            $('#btnSubmit').on('click', function () {
                if (JSON.stringify(_vars.CustomizeProduct) == '{}') {
                    alert('必须添加内容');
                    return false;
                }
                N.visit(_vars.CustomizeCartUrl);
            });
        },
        cartAction: function () {
            $('.trashbin').on('click', function () {
                var $item;
                $item = $(this).parents('div.item');

                if (confirm("确认删除该商品?")) {
                    delete _vars.CustomizeProduct[$item.data('pid')];
                    $.fn.cookie('customizeproduct', _func.GetProductStr(), {
                        expires: 1,
                        path: '/'
                    });
                    $item.remove();
                    _func.CustomizeCal();
                    if (JSON.stringify(_vars.CustomizeProduct) == '{}') {
                        $('.empty').removeClass('Ldn');
                    }
                }
            });
            $('.numctrl i').on('click', function () {
                var $this, $input, $item, num, pid;
                $this = $(this);
                $item = $this.parents('div.item');
                $input = $this.siblings('input');
                num = Number($input.val());
                pid = $item.data('pid');
                if ($this.hasClass('numsub')) {
                    if (num <= 1) {
                        return false;
                    }
                    $input.val(num - 1);
                    if (_vars.CustomizeProduct[pid] && _vars.CustomizeProduct[pid].num > 1) {
                        _vars.CustomizeProduct[pid].num = _vars.CustomizeProduct[pid].num - 1;
                    }
                } else if ($this.hasClass('numadd')) {
                    if (num >= 100) {
                        return false;
                    }
                    $input.val(num + 1);
                    if (_vars.CustomizeProduct[pid]) {
                        _vars.CustomizeProduct[pid].num = _vars.CustomizeProduct[pid].num + 1;
                    }
                }
                _func.CustomizeCal();
            });

            $('#btnSubmit').on('click', function () {
                if (JSON.stringify(_vars.CustomizeProduct) == '{}') {
                    alert('必须添加内容');
                    return false;
                }
                var $this = $(this);
                if ($this.hasClass('gray')) {
                    return false;
                }
                $this.addClass('gray');
                $.post(_vars.CustomizePurchaseUrl, { 'product': _func.GetProductStr() }, function (result) {
                    if (result.isSuccess != '1') {
                        alert(result.oMessage ? result.oMessage : '提交订单出现错误,请稍后再试!');
                        $this.removeClass('gray');
                        return false;
                    }
                    alert('订单提交成功!');
                    N.visit(_vars.CustomizePurchasedUrl);
                });
            });
        },
        purchasedAction: function () {
            $("a.std_small_btn").on('click', function () {
                var $this = $(this);
                if ($this.data('cancel') == 'canceled') {
                    return false;
                }
                if (confirm("确认取消该订单吗?")) {
                    $.post(_vars.CustomizeCancelUrl, {
                        'cResNo': $this.data('cresno'), 'HotelId': $this
                        .data('hotelid')
                    }, function (result) {
                        if (result.isSuccess == "1") {
                            alert("订单取消成功!");
                            $this.html('已取消').data('cancel', 'canceled');
                        } else {
                            if (result.oMessage) {
                                alert(result.oMessage);
                            } else {
                                alert('取消订单出现错误!');
                            }
                        }
                    });
                }
            });
        }
    });

    _n.app('help', {
        init: function () { },
        breakfastBuyAction: function () {
            $(".btnConfirm").on('click', function () {
                var $this = $(this);
                if ($this.hasClass('gray')) {
                    return false;
                }
                $this.addClass('gray');
                var num = $(".listbox .item input.num").first().val();
                //                $.post(payurl, { "num": num }, function (result) {
                //                    $this.removeClass('gray');
                //                });
                location.href = $this.data('url') + "&num=" + num;
            });

            //购买数量更改
            $(".listbox .item i").on('click', function () {
                var $this = $(this),
                    $input = $this.siblings('input'),
                    max = $input.data('max'),
                    min = $input.data('min'),
                    num = $input.val();
                if ($this.hasClass('disable')) {
                    return false;
                }
                if ($this.hasClass('add')) {
                    num++;
                    if (num >= max) {
                        $this.addClass('disable');
                    }
                    if (num > min) {
                        $this.siblings('i').removeClass('disable');
                    }
                } else {
                    num--;
                    if (num <= min) {
                        $this.addClass('disable');
                    }
                    if (num < max) {
                        $this.siblings('i').removeClass('disable');
                    }
                }
                $input.val(num);

            });

            $('.botbox .link').on('click', function () {
                var $this = $(this);
                if ($this.data('status') !== 'expand') {
                    $this.find('a').html('展开链接<i class="Cicon downarrow_small"></i>');
                    $this.siblings('div.content').addClass('Ldn');
                    $this.data('status', 'expand');
                } else {
                    $this.find('a').html('收起链接<i class="Cicon uparrow_small"></i>');
                    $this.data('status', '');
                    $this.siblings('div.content').removeClass('Ldn');
                }
            });

            N.func.BreakfastShowSuccess = function () {
                var $msg = $(".BFC_pay .breakfast");
                var $success = $msg.find('div.success');
                var $error = $msg.find('div.error');
                $msg.removeClass('Ldn');
                $success.removeClass('Ldn');
                $error.addClass('Ldn');
                if (_n.vars.breakfastTicketNo !== '') {
                    location.href = N.vars.breakfastDetailUrl;
                } else {
                    setInterval(function () {
                        location.href = N.vars.breakfastListUrl;
                    }, 2000);
                }
            }


            N.func.BreakfastShowError = function () {
                var $msg = $(".BFC_pay .breakfast");
                var $success = $msg.find('div.success');
                var $error = $msg.find('div.error');
                $msg.removeClass('Ldn');
                $success.addClass('Ldn');
                $error.removeClass('Ldn');
                location.href = N.vars.breakfastListUrl;
            }

            _func.addPageLoadCall(function () {
                if (_n.vars.breakfastOrderNo !== '') {
                    _n.func.BreakfastShowSuccess();
                }
            });
        }
    });

    _n.app('fastcheckin', {
        init: function () {
            _func.addPageLoadCall(function () {
                var page = N.pageId.split('/')[1];
                $('.fcFlowNew li span.' + page).parent('li').addClass('active');
                //var resNo = _vars.fcResNo;
                //var cookie = JSON.parse($.fn.cookie('fc'));
                //if (cookie && cookie[resNo]) {
                //    var c = cookie[resNo];
                //    if (c['pay'] && c['pay']['room']) {
                //        $('.fcFlowNew li span.room.not').addClass('Ldn')
                //        $('.fcFlowNew li span.room.choice').removeClass('Ldn').after('<span class="fcFlowIcon"></span> <span class="fcFlowDone"></span>');
                //        if (_n.pageId === 'fastcheckin/room' && c['pay']['room'] === $("#btnSubmit").data('room')) {
                //            $("#btnSubmit").addClass('gray');
                //        }
                //    }
                //    if (c['invoice'] && c['invoice']['resNo']) {
                //        $('.fcFlowNew li span.invoice').after('<span class="fcFlowIcon"></span> <span class="fcFlowDone"></span>');
                //    }
                //    if (c['Draw'] && c['Draw']['resNo']) {
                //        $('.fcFlowNew li span.sign').after('<span class="fcFlowIcon"></span> <span class="fcFlowDone"></span>');
                //    }
                //}

            });
            $('.fcFlowNew li').on('click', function () {
                if ($(this).parent('ul').data('isapp') === "1") {
                    window.location = $(this).data('url');
                } else {
                    N.visit($(this).data('url'));
                }
            });
        },

        roomAction: function () {
            $("#btnSubmit").on('click', function () {
                if ($(this).hasClass('gray')) {
                    return;
                }
                var room = $(this).data('room'),
                    resNo = _vars.fcResNo;
                if (room === '') {
                    alert('尚未选房!');
                    return false;
                }
                var cookie = JSON.parse($.fn.cookie('fc'));
                if (!cookie) {
                    cookie = {};
                }
                if (!cookie[resNo]) {
                    cookie[resNo] = {};
                }
                cookie[resNo]['pay'] = { 'room': room }
                $.fn.cookie('fc', JSON.stringify(cookie), {
                    expires: 5,
                    path: '/'
                });
                wa_track_event('fast 选房', '就住这间', _vars.fcResNo);
                N.visit($("#jmpBtn").attr('href'));
            });

        },

        payAction: function () {
            $("#btnSubmit").on('click', function () {
                var resNo = _vars.fcResNo;
                var token = $('[name="__RequestVerificationToken"]').val();
                if (resNo === "" || token === "") {
                    alert("参数错误");
                    return false;
                }
                $('.Cload').show();
                _func.postHPay($(this), resNo, token, _vars.completeUrl);
            });
        },

        invoiceAction: function () {
            $("#fcNeedTicketToggle").on('click', function () {
                var $this = $(this),
                    $info = $('.INV_info');
                if ($this.hasClass('enable')) {
                    $this.removeClass('enable');
                    $info.addClass('Ldn');
                    $("#hisinvoice").val("false");
                } else {
                    $this.addClass('enable');
                    $info.removeClass('Ldn');
                    $("#hisinvoice").val("true");
                }
            });
            $('.socialnum').on('input', function () {
                var $this = $(this),
                    $tax = $('.taxnum');
                if ($this.val().trim() !== '') {
                    $tax.val('').attr('readonly', 'readonly').css('background-color', '#d1d1d1')
                } else {
                    $tax.removeAttr('readonly').css('background-color', '')
                }
            }).on('click', function() {
                var $this = $(this);
                if ($this.attr('readonly') === 'readonly' && $('.taxnum').attr('readonly') !== 'readonly') {
                    alert('删除纳税人识别码后方可填写!');
                }
            });
            $('.taxnum').on('input', function () {
                var $this = $(this),
                    $tax = $('.socialnum');
                if ($this.val().trim() !== '') {
                    $tax.val('').attr('readonly', 'readonly').css('background-color', '#d1d1d1')
                } else {
                    $tax.removeAttr('readonly').css('background-color', '')
                }
            }).on('click', function () {
                var $this = $(this);
                if ($this.attr('readonly') === 'readonly' && $('.socialnum').attr('readonly') !== 'readonly') {
                    alert('删除统一社会信用代码后方可填写!');
                }
            });
            $("#btnSave").on('click', function () {
                $('.errorS').addClass('Ldn');

                var enable = $("#fcNeedTicketToggle").hasClass('enable');
                var type = $(".LI_main .tabs .tab.active").data('active');
                var block = $('.INV_info .content').find('.block.' + type);

                if (!enable) {
                    window.location = _vars.next;
                    return;
                }

                
                if (type === 'first' && enable) {
                    if (!block.find('.name').val()) {
                        _func.alert("请输入发票抬头！");
                        return;
                    }
                    $('#hidInvoiceTitle').val(block.find('.name').val());
                }
                if (type === 'second' && enable) {
                    if (!block.find('.name').val()) {
                        _func.alert("请输入发票抬头！");
                        return;
                    }
                    if (!block.find('.socialnum').val() && !block.find('.taxnum').val()) {
                        _func.alert("请输入纳税人识别号或统一社会信用代码！");
                        return;
                    }
                    var len = block.find('.taxnum').val().length;
                    if (block.find('.taxnum').val() && len !== 15 && len !== 17 && len !== 18 && len !== 20) {
                        _func.alert("纳税人识别号输入长度有误，请检查！");
                        return;
                    }
                    if (!block.find('.address').val()) {
                        _func.alert("请输入注册地址！");
                        return;
                    }
                    if (!block.find('.phone').val()) {
                        _func.alert("请输入联系电话！");
                        return;
                    }
                    if (!block.find('.bank').val()) {
                        _func.alert("请输入开户银行！");
                        return;
                    }
                    if (!block.find('.banknum').val()) {
                        _func.alert("请输入开户账号！");
                        return;
                    }
                    $('#hidInvoiceTitle').val(block.find('.name').val());
                    $('#hidInvoiceSocial').val(block.find('.socialnum').val());
                    $('#hidInvoiceTaxNum').val(block.find('.taxnum').val());
                    $('#hidInvoiceAddress').val(block.find('.address').val());
                    $('#hidInvoicePhone').val(block.find('.phone').val());
                    $('#hidInvoiceBank').val(block.find('.bank').val());
                    $('#hidInvoiceBankNum').val(block.find('.banknum').val());
                }
                if (type && enable) {
                    $('#hidInvoiceType').val(type === 'first' ? 'common' : 'spiclal');
                }

                $.post(_vars.saveUrl, $('#invoice').serialize(), function (data) {
                    if (data.isSuccess === "1") {
                        wa_track_event('fast发票', enable ? "不需要" : "需要", _vars.fcResNo);
                        wa_track_event('fast发票', '确认', _vars.fcResNo);
                        var cookie = JSON.parse($.fn.cookie('fc'));
                        if (!cookie) {
                            cookie = {};
                        }
                        if (!cookie[_vars.fcResNo]) {
                            cookie[_vars.fcResNo] = {};
                        }
                        cookie[_vars.fcResNo]['invoice'] = { 'resNo': _vars.fcResNo }
                        $.fn.cookie('fc', JSON.stringify(cookie), {
                            expires: 5,
                            path: '/'
                        });
                        //
                        window.location = data.url;
                    } else {
                        alert("保存发票出现错误!");
                    }
                });


              
            });
            $(".INV_info .LI_main .tabs .tab").on('click', function () {

                var thisType = $(this).data('active'),
                    otherType = $(this).siblings('.tab').data('active'),
                    $this = $(this);

                if ($this.hasClass('active')) {
                    return;
                }


                $this.addClass('active');
                $this.siblings().removeClass('active');
                $this.parent('.tabs').addClass(thisType).removeClass(otherType);


                $('.INV_info .content').find('.block.' + thisType).removeClass('Ldn');
                $('.INV_info .content').find('.block.' + otherType).addClass('Ldn');

                $('.noticebox p.' + thisType).removeClass('Ldn');
                $('.noticebox p.' + otherType).addClass('Ldn');
            });
            $(".INV_info .block i.addNew").on('click', function () {
                $('.quickorder.INV_info_popup').removeClass('Ldn');
                $('.fcWrap').css({
                    height: '200px',
                    overflow: 'hidden'
                });
            });

            $(".INV_info_popup").on('touchstart', '.pop_close', function (e) {
                $('.quickorder.INV_info_popup').addClass('Ldn');
                // e.preventDefault();
                $('.fcWrap').css({
                    height: '100%',
                    overflow: 'auto'
                });
            });
            $(".INV_info_popup").on('touchmove', '.btnbox', function (e) {
                e.preventDefault();
            });
            $("body").on('touchmove', "#INV_info", function (e) {
                e.preventDefault();
            });

            //选择历史中的发票记录
            $('.quickorder.INV_info_popup .content').on('click', '.room', function () {
                var $this = $(this).find('i.small');
                var selcted = $this.hasClass('selected');
                $('.quickorder.INV_info_popup .room i.small').removeClass('selected');
                $('.quickorder.INV_info_popup .room .roomtype').removeClass('selected');
                if (!selcted) {
                    $this.addClass('selected');
                    $this.siblings('.roomtype').addClass('selected');
                }
            });

            //确定选择发票历史
            $(".quickorder.INV_info_popup .std_large_button").on('click', function () {
                $(".quickorder.INV_info_popup").addClass('Ldn');
                $('.fcWrap').css({
                    height: '100%',
                    overflow: 'auto'
                });
                var i = $('.quickorder.INV_info_popup .content .room i.small.selected');
                if (i && i.data('id')) {
                    var invoices = JSON.parse(_vars.invoices);
                    invoices.map(function (item) {
                        if (item['Tid'] === i.data('id')) {
                            var block = $('.INV_info .content').find('.block');
                            block.find('.name').val(item['Title']);
                            block.find('.socialnum').val(item['UnifiedSocialCreditCode']);
                            block.find('.taxnum').val(item['TaxpayerCode']);
                            block.find('.address').val(item['CompanyAddress']);
                            block.find('.phone').val(item['PhoneNumber']);
                            block.find('.bank').val(item['CompanyBank']);
                            block.find('.banknum').val(item['CompanyBankAccountNumber']);
                            if (item['TaxpayerCode'] !== '') {
                                block.find('.socialnum').val('');
                            }
                        }
                    });
                } else {
                    var block = $('#divInvoice .INV_info .content').find('.block');
                    block.find('.name').val('');
                    block.find('.socialnum').val('');
                    block.find('.taxnum').val();
                    block.find('.address').val('');
                    block.find('.phone').val('');
                    block.find('.bank').val('');
                    block.find('.banknum').val('');
                }

            });

            

            _func.addPageLoadCall(function () {
                var $this = $(".INV_info_popup.Cmask");
                if ($this.data('init') !== 'inited') {
                    $.get(_vars.invoiceUrl, '', function (data) {
                        if (data && data !== 'null') {
                            _vars.invoices = data;
                            var invoice = JSON.parse(data);
                            var html = '';
                            if (!invoice.length) {
                                $('.addNew').addClass('Ldn').removeClass('addNew');
                            }
                            invoice.map(function (d) {
                                if (!d['Title'] || d['Title'] === '') return;
                                html += '<div class="pure-g room">' +
                                    '<div class="pure-u-24-24 roomtypebox">' +
                                    '<i class="Cradiobox small" data-id="' + d['Tid'] + '"><i class="Cicon correct_char_small white" >&nbsp;</i></i>' +
                                    '<div class="roomtype">' + d['Title'] + '</div>' +
                                    '</div>' +
                                    '</div>';
                            });
                            $('.quickorder.INV_info_popup .content').html(html);
                            $this.data('init', 'inited');
                        }
                    });
                }


                if (_vars.existInvoice === "1") {
                    $(".INV_info .content input").attr('readonly', 'readonly');
                }
            });
        },

        CommpleteAction: function () {
            N.visit($("#jmpBtn").hide());
        },

        completeAction: function () {
            _func.addPageLoadCall(function () {
                JsBarcode("#barCode", N.vars.fcResNo, {
                    height: 45,
                    displayValue: false,
                    textAlign: "center"
                });
            })
        },

        signAction: function () {
            _func.addPageLoadCall(function () {
                var page = N.pageId.split('/')[1];
                $('.fcFlow li span.' + page).parent('li').addClass('active');
            });

            $('#btnSubmit').click(function () {
                var resNo = $("#hResno").val();
                //alert(resno + "%%%%" + N.vars.data64);
                $.post("SaveDraw", { resNo: resNo, data64: N.vars.data64 }, function (result) {
                    if (result.Code == "21") {
                        alert(result.msg);
                    } else {
                        if (result.data) {
                            var cookie = JSON.parse($.fn.cookie('fc'));
                            if (!cookie) {
                                cookie = {};
                            }
                            if (!cookie[resNo]) {
                                cookie[resNo] = {};
                            }
                            cookie[resNo]['Draw'] = { 'resNo': resNo }
                            $.fn.cookie('fc', JSON.stringify(cookie), {
                                expires: 5,
                                path: '/'
                            });
                            window.location = result.url;
                        }
                    }
                });
            });
        }


    });

    _n.app('price', {
        init: function () {
            _vars.files = [];
        },
        orderAction: function () {
            $('.MO_main .Cradiobox').on('click', function () {
                var $this = $(this),
                    $next = $('.mMargin.bottom');
                if ($this.hasClass('selected')) {
                    $this.removeClass('selected');
                    $next.addClass('Ldn');

                } else {
                    $('.MO_main .Cradiobox').removeClass('selected');
                    $this.addClass('selected');
                    $next.removeClass('Ldn');
                    _vars.complaintRes = $this.data('res');
                }
            });
            $('.mMargin.bottom a').on('click', function () {
                var $this = $(this);
                _n.hook('loading');
                if ($this.data('inited') === 'inited') {
                    return;
                }
                $this.data('inited', 'inited');
                $.post(N.vars.statusCheckUrl + '?resNo=' + _vars.complaintRes, {}, function (data) {
                    if (data.isSuccess === 1) {
                        window.location = N.vars.nextUrl + '?resNo=' + _vars.complaintRes;
                    } else {
                        alert(data.oMessage);
                        _n.hook('hideloading');
                        $this.data('inited', '');
                        return false;
                    }

                });

            });

        },
        infoAction: function () {
            $('.expandbox select').on('change', function () {
                var $this = $(this),
                    val = $this.val(),
                    $option = $('.expandbox select option').not(function () { return !this.selected; });
                if (val) {
                    $('.expand .showtime').text($option.text());
                } else {
                    $('.expand .showtime').text($('.expand .showtime').data('msg'));
                }

            });

            $('.HO_main .upload-list ').on('click', ' .upload-item i', function () {
                var $this = $(this).siblings('img');
                $this.parent().remove();
                $('.HO_main .upload-list .upload-button').removeClass('Ldn');
                $("#" + $this.data('id')).remove();
                _vars.files = _vars.files.filter(function (el) {
                    if (N.func.isIOS()) {
                        return el.size !== $this.data('name');
                    }
                    return el.name !== $this.data('name');
                });
                var $file = $('#logo');
                $file.after($file.clone().val(""));
                $file.remove();

            });
            function guid() {
                function s4() {
                    return Math.floor((1 + Math.random()) * 0x10000)
                      .toString(16)
                      .substring(1);
                }
                return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                  s4() + '-' + s4() + s4() + s4();
            }
            function getBase64Image(img) {
                var canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;

                var ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, img.width, img.height);
                var ext = img.src.substring(img.src.lastIndexOf(".") + 1).toLowerCase();
                var dataURL = canvas.toDataURL("image/" + ext);
                dataURL = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
                console.log(dataURL.length)
                return dataURL;
            }
            function html5Reader(file) {
                var name = guid();
                var $div = $('<div class="upload-item"><img  /><i class="remove-upload-pic"></i></div>'),
                    $img = $div.find('img');
                $img.attr('src', URL.createObjectURL(file));
                $img.data('id', name);
                if (N.func.isIOS()) {
                    $img.data('name', file.size);
                } else {

                    $img.data('name', file.name);
                }
                var $input = $("<input type='hidden' name='Image' value='' id=" + name + " />");

                var img = document.getElementById("tmpImg");
                // URL.createObjectURL  safari不支持
                img.src = URL.createObjectURL(file);


                $('.upload-button').before($div);
                $img[0].onload = function () {
                    var data = getBase64Image(img);
                    $input.attr('src', URL.createObjectURL(file));
                    console.log(data.length)
                    $input.val(data);
                    $('#Price').append($input);
                };



            }



            $('.HO_main .upload-list .upload-button').on('change', '#logo', function () {
                $(".errorS").hide();
                var file = $('#logo')[0],
                    files = file.files;
                var f = _vars.files.filter(function (el) {
                    if (N.func.isIOS() || N.vars.IsAppRequest === true) {
                        return el.size === file.files[0].size;
                    }
                    return el.name === file.files[0].name;
                });
                if (f.length > 0) {
                    return false;
                }
                if (files.length + _vars.files.length > 3) {
                    _func.alert('最多上传三张图片!');
                    return false;
                }

                for (var i = 0; i < files.length; i++) {
                    var ext = files[i].name.substring(files[i].name.lastIndexOf(".") + 1).toLowerCase();
                    // gif在IE浏览器暂时无法显示
                    if (ext !== 'png' && ext !== 'jpg' && ext !== 'jpeg' && ext !== 'gif') {
                        _func.alert("图片的格式必须为png或者jpg或者jpeg格式！");
                        return false;
                    }
                    if (files[i].size > 2097152) {
                        _func.alert("图片不能超过2M,已超出限制!");
                        return false;
                    }
                }

                for (var i = 0; i < files.length; i++) {
                    _vars.files.push(files[i]);
                }


                //                file.files = {};
                if (_vars.files.length >= 3) {
                    $('.HO_main .upload-list .upload-button').addClass('Ldn');
                }
                //                $('.HO_main .upload-list .upload-item').not('.upload-button').remove();

                //                $('#Price [name=Image]').remove();
                for (var i = 0; i < files.length; i++) {
                    html5Reader(files[i]);
                }
                var $file = $('#logo');
                $file.after($file.clone().val(""));
                $file.remove();
            });


            $('#btnNext').on('click', function () {
                $(".errorS").hide();
                if ($('select.dayselect').val() === '') {
                    _func.alert('请选择要投诉的网站!');
                    return false;
                }
                if ($("#price").val() === '') {
                    _func.alert('请填写要投诉的价格!');
                    return false;
                }
                var t = /^[1-9][0-9]{0,5}$/;
                if (!t.test($("#price").val())) {
                    _func.alert('请填写正确的价格!');
                    return false;
                }

                if ($('#Price [name=Image]').length <= 0) {
                    _func.alert("请上传图片!");
                    return false;
                }
                if ($('#Price [name=Image]').length < 2) {
                    _func.alert('最少上传二张图片!');
                    return false;
                }
                $('#Info').addClass('Ldn');
                $('#verify').removeClass('Ldn');
            });


            $('#verify header a.back').on('click', function () {
                $('#Info').removeClass('Ldn');
                $('#verify').addClass('Ldn');
            });


            $("#pGetSms").on('click', function () {
                var $btnSms = $(this);
                //                if (!$(".tabcontent [name=phone]").val() || $(".tabcontent [name=phone]").val().length < 0) {
                //                    alert("手机号不能为空");
                //                    return false;
                //                }
                //                if (!_func.isCellPhone($.trim($(".tabcontent [name=phone]").val()))) {
                //                    alert("请填入正确的手机号!");
                //                    return false;
                //                }
                _vars.url.sendSms = _vars.ServerHttp + "Account/SendSms/";
                _vars.SendSms.init($btnSms, { "CaptchaType": "Common" }, undefined, { clear: "获取短信<br>验证码" });
            });

            $("#btnSubmit").on('click', function () {
                var $this = $(this);
                if ($this.data('inited') === 'inited') {
                    return false;
                }
                if (!$(".tabcontent [name=name]").val() || $(".tabcontent [name=name]").val().length < 0) {
                    alert("联系人不能为空");
                    return false;
                }



                var val = $('#codeSms').val();
                if (!val && val.length <= 0) {
                    alert("验证码不能为空!");
                    return false;
                }
                if (!$('#rules').prop('checked')) {
                    alert('尚未同意《华住最低价规则》')
                    return false;
                }

                $('#Price [name=WebSite]').val($("select[name=ArrivalTime]").val());
                $('#Price [name=Price]').val($("#price").val());

                $('#Price [name=Sms]').val(val);
                $('#Price [name=name]').val($(".tabcontent [name=name]").val());
                $('#Price [name=phone]').val($(".tabcontent [name=phone]").val());
                _n.hook('loading');
                $(this).data('inited', 'inited');
                $.post(_vars.saveUrl, $('#Price').serialize(), function (data) {
                    if (data.isSuccess === 1) {
                        N.visit(_vars.completeUrl);
                    } else {
                        _n.hook('hideloading');
                        $this.data('inited', '');
                        alert(data.oMessage);
                    }
                });
            });
        }

    });


    /* }}} */
    return _n;
}).call(this.N = this.ndoo = this.ndoo || {}, Zepto);
;
/*
" --------------------------------------------------
"   FileName: main.sjx.js
"   Desc: app.js webapp逻辑脚本
"   Author: sujunxuan
"   Version: v0.1
"   LastChange: 03/27/2014 11:58
" --------------------------------------------------
 */
(function ($) {
    var _func, _n, _stor, _vars;
    _n = this;
    _vars = _n.vars;
    _func = _n.func;
    _stor = _n.storage;

    _vars.target = _vars.target || {};
    _vars.sendSms = {
        node: null,
        count: 60,
        start: function () {
            if (this.count > 0) {
                this.node.text(this.count-- + "秒后再发送");
                var _this = this;
                setTimeout(function () {
                    _this.start();
                }, 1000);
            } else {
                this.node.removeAttr("disabled");
                this.node.removeClass("gray");
                this.node.text("获取验证码");
                this.count = 60;
            }
        },
        //初始化
        init: function (node) {
            this.node = node;
            this.node.attr("disabled", true);
            this.node.addClass("gray");
            this.start();
        }
    };
    _func.getLength = function (str) {
        return str.replace(/[^ -~]/g, 'AA').length;
    }

    _func.limitMaxLength = function (str, maxLength) {
        var result = [];
        for (var i = 0; i < maxLength; i++) {
            var char = str[i]
            if (/[^ -~]/.test(char))
                maxLength--;
            result.push(char);
        }
        return result.join('');
    }

    _func.alert = function (msg) {
        $errors = $(".errorS");
        $errors.removeClass('Ctip_animation').addClass('Ctip_animation');
        $errors.text(msg).show();
        window.scrollTo(0, 0);
        //setTimeout('$(".errorS").hide()', 10000);
    };

    _func.isCellPhone = function (e) {
        var t = /(^0{0,1}1[3|4|5|6|7|8][0-9]{9}$)/;
        return t.test(e);
    };


    //验证手机号,用户名
    _func.verifyName = function (name) {
        //验证用户名
        if (!name) {
            _func.alert("请输入联系人姓名!");
            return false;
        }

        if (!_func.checkIsName(name)) {
            _func.alert("请输入正确的联系人姓名!");
            return false;
        }
        return true;
    };

    //验证手机号,用户名
    _func.verify = function (mobile, name) {
        //验证用户名
        if (!name) {
            _func.alert("请输入联系人姓名!");
            return false;
        }

        if (!_func.checkIsName(name)) {
            _func.alert("请输入正确的联系人姓名!");
            return false;
        }

        //验证手机号
        if (!_func.isCellPhone(mobile)) {
            _func.alert("请输入正确的手机号!");
            return false;
        }

        return true;
    };

    _n.hook('geolocation', function () {
        var map;
        map = _stor('geomap');
        if (!map) {
            map = new AMap.Map('HWorld-iCenter');
            _stor('geomap', map);
        }
        map.plugin('AMap.Geolocation', function () {
            var geocompletelistener, geoerrorlistener, geolocation;
            geolocation = new AMap.Geolocation({
                timeout: 180000,
                showButton: false,
                showMarker: false,
                showCircle: false
            });
            if (listener = _stor('hotelIndexListener')) {
                AMap.event.removeListener(listener['complete']);
                AMap.event.removeListener(listener['error']);
                _stor('hotelIndexListener', null, null, true);
            }
            if (geolocation.isSupported()) {
                geocompletelistener = AMap.event.addListener(geolocation, "complete", function (result) {
                    var lnglat;
                    lnglat = result.position;
                    $('#getgeo').data('latlng', "" + (lnglat.getLat()) + "|" + (lnglat.getLng()));
                    _n.hook('citylocation', lnglat);
                });
                geoerrorlistener = AMap.event.addListener(geolocation, "error", function (result) {
                    var err;
                    err = '定位失败';
                    switch (result.info) {
                        case 'NOT_SUPPORTED':
                            err = '当前浏览器不支持定位功能';
                            break;
                        case 'PERMISSION_DENIED':
                            err = '浏览器拒绝定位，您可以尝试在设置中启用定位服务';
                            break;
                        case 'POSITION_UNAVAILABLE':
                            err = '浏览器无法获取当前位置';
                            break;
                        case 'TIMEOUT':
                            err = '定位超时';
                            break;
                        default:
                            err = '定位失败，您可以尝试在设置中开启定位服务，开启WIFI以提高定位成功率！';
                    }
                    //                            $('#getgeo').removeClass('wait').find('span').text('重新定位');
                    //                            if (_n.pageId === 'home/index') {
                    //                                alert(err);
                    //                            }
                });
                _stor('hotelIndexListener', {
                    complete: geocompletelistener,
                    error: geoerrorlistener
                }, true);
                geolocation.getCurrentPosition();
            }
            else {
                var err = '定位失败，您可以尝试在设置中开启定位服务，开启WIFI以提高定位成功率！';
            }
        });
    });
    window.wrapMapCallback = function () {
        _n.hook('geolocation');
    };
    _func.setIndexCurrCity = function (cityname, trytrim, latlng) {
        var item, _i, _len, _ref;
        _ref = _vars.cities;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            item = _ref[_i];
            if (item.mc.indexOf(cityname) === 0) {
                if (_n.pageId == 'home/index') {
                    _func.homeSwitchRegion(latlng.lat + '|' + latlng.lng, item.mc, item.id);
                } else {
                    $.post(_vars.hotelParaSetUrl, { "cityid": item.id, "cityname": (encodeURIComponent('附近酒店')), 'LatLng': $('#getgeo').data('latlng') }, function () {
                        N.func.visitList();
                    });
                }
                break;
            }
        }
        if (trytrim) {
            _func.setIndexCurrCity(cityname.replace(/.$/, ''), false, latlng);
        }
    };

    /*获取城市 */
    _n.hook('citylocation', function (lnglat) {
        AMap.service(["AMap.Geocoder"], function () {
            var geocoder = new AMap.Geocoder({ radius: 0 });
            geocoder.getAddress(lnglat, function (status, result) {
                if (status === 'complete') {
                    address = result.regeocode.addressComponent;
                    city = address.city || address.province;
                    _func.setIndexCurrCity(city, true, lnglat);
                }
            });
        });
    });

    /* 模块定义 {{{ */
    _n.app('hotel', {
        init: function () {
            _vars.target.hotel = {
                txtSearch: "#txtSearch",
                txtKeyword: "#txtKeyword",
                searchResult: "#searchList",
                divBrand: "#divBrand",
                btnFilter: "#btnFilter",
                btnSubmit: "#btnSubmit",
                btnRight: "#btnRight",
                btnFilterSubmit: "#btnFilterSubmit"
            };

            $('#getgeo').on('click', function () {
                var $self;
                $self = $(this);
                if ($self.hasClass('wait') || $self.data('success') === 'yes') {
                    return false;
                }
                if ($self.data('init') !== 'inited') {
                    $self.data('init', 'inited');
                    if (window.AMap) {
                        _n.hook('geolocation');
                    } else {
                        _func.addScript(_vars.mapurl + '&callback=wrapMapCallback');
                    }
                } else {
                    _n.hook('geolocation');
                }
                return false;
            });


            _func.addhistory = function () {
                try {
                    var max, val;
                    max = 3;
                    var cookie = JSON.parse($.fn.cookie('hotelhistory'));
                    val = { id: N.vars.wa.hotel_id, name: ndoo.vars.wa.hotel_name, price: N.vars.historyPrice, url: N.vars.historyUrl }
                    if (!cookie) {
                        cookie = [];
                    }
                    if (!cookie.find(function (item) {
                        return item.id === val.id;
                    })) {
                        if (cookie.length >= max) {
                            cookie.splice(max - 1, 1);
                        }
                        cookie.splice(0, 0, val);
                    }
                    $.fn.cookie('hotelhistory', JSON.stringify(cookie), {
                        expires: 30,
                        path: '/'
                    });
                } catch (e) {

                }
            };
            /* 加载评论 {{{ */
            (function () {
                _func.renderCommentscore = function (data) {
                    var html;
                    html = "<div class='item  pd15_15_5'>" + "    <div class='pure-g'>" + ("        <div class='pure-u-1-4'>" + (_func.renderScore(data.average)) + "</div>") + "        <div class='pure-u-3-4 text_c_66'>" + ("            总分：<strong class='orange'>" + data.average + "分</strong><span class='f99'>/5分</span>") + "        </div>" + "    </div>" + "</div>" + "<div class='item lineBottomEb pd0_15_15 f80 Lfz14'>" + "    <div class='pure-g '>" + "        <div class='pure-u-1-4'>" + ("            设施<span class='f33'>" + data.facility + "分</span>") + "        </div>" + "        <div class='pure-u-1-4'>" + ("            卫生<span class='f33'>" + data.sanitation + "分</span>") + "        </div>" + "        <div class='pure-u-1-4'>" + ("            服务<span class='f33'>" + data.service + "分</span>") + "        </div>" + "        <div class='pure-u-1-4'>" + ("            餐饮<span class='f33'>" + data.dining + "分</span>") + "        </div>" + "    </div>" + "</div>";
                    $(html).appendTo('.commentblock .commentscore');
                };
                _func.commentDate = function (date) {
                    date = new Date(date);
                    return "" + (date.getMonth() + 1) + "-" + (date.getDate()) + " " + (date.getHours()) + ":" + (date.getMinutes() >= 10 ? date.getMinutes() : '0' + date.getMinutes());
                };
                _func.renderComment = function (data) {
                    var html, item, reply, _i, _len, _ref;
                    html = "";
                    _ref = data.CommentList;
                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                        item = _ref[_i];
                        if (item.Reply && item.Reply.ReplyContent) {
                            reply = "   <div class='hotelReply'><h4>酒店回复：</h4><span class='hotelReplaCont'>" + item.Reply.ReplyContent + "</span></div>";
                        } else {
                            reply = "";
                        }
                        html += "<div class='item lineBottomEb pd15_10_15 f33 Lfz14'>" + "    <div class='pure-g Lmb10 f66'>" + "        <div class='pure-u-3-8'>" + ("            " + (_func.renderScore(item.averageScore)) + item.averageScore + "分") + "        </div>" + ("        <div class='pure-u-8-24 Ctextover reColor'>来自:" + item.postsName + "</div>") + ("        <div class='pure-u-1-4 Ltar Ctextover reColor'>" + (_func.commentDate(item.postsTime)) + "</div>") + "    </div>" + ("    <div>" + item.postsContent + "</div>") + reply + "</div>";
                    }
                    $(html).appendTo('.commentblock .commentlist');
                };
                _func.commentLazyLoad = function () {
                    var $list, page, total, type;
                    $list = $('.commentblock .commentlist');
                    page = parseInt($list.data('page'));
                    total = parseInt($list.data('total'));
                    if (page < 0) {
                        $list.data('lock', 'locked');
                        return void 0;
                    }
                    page += 1;
                    if ((page <= total) && ($list.data('lock') !== 'locked')) {
                        $list.data('lock', 'locked');
                        $list.data('page', page);
                        type = $list.data('type');
                        if (type && type === 'all') {
                            _func.getComment(page);
                        } else {
                            _func.getCommentsBySeg($list.data('mapId'), page);
                        }
                    } else {
                        $('.commentblock .loadmore').removeClass('enable');
                    }
                };
                _func.commentCall = function (data) {
                    var $commentblock, $list, init, page, total;
                    $commentblock = $('.commentblock');
                    init = $commentblock.data('initComment');
                    data = JSON.parse(data);
                    if (!init) {
                        if (data.TotalCount < 1) {
                            $('.commentblock .commentempty').show();
                            $('.commentblock .loadmore').removeClass('enable');
                        } else {
                            $('.commentblock .loadmore').addClass('enable');
                            _func.renderCommentscore(data);
                            $('.commentblock .commentlist').data('page', _vars.commentpagestart).data('total', Math.ceil(data.TotalCount / 10)).show();
                            _func.renderComment(data);
                            if (data.TotalCount <= 10) {
                                if (data.TotalCout <= 0) {
                                    $('.commentblock .commentempty').hide()
                                }
                                $('.commentblock .loadmore').removeClass('enable');

                            }
                            $('.commentblock .loadmore').datalazyload({
                                repeat: true,
                                loader: _func.commentLazyLoad
                            });
                        }
                        $commentblock.data('initComment', 'inited');
                    } else {
                        _func.renderComment(data);
                        $list = $('.commentblock .commentlist');
                        page = $list.data('page');
                        total = $list.data('total');
                        if (page === total) {
                            $('.commentblock .loadmore').removeClass('enable');
                        } else {
                            $('.commentblock .commentlist').data('lock', 'unlock');
                        }
                    }
                };
                $('.comEvaluated .item_btm .item_info').on('click', 'span', function () {
                    var $commentblock, $list, $this;
                    $this = $(this);
                    $list = $('.commentblock .commentlist');
                    $commentblock = $('.commentblock');
                    $commentblock.data('initComment', '');
                    $list.empty();
                    $list.data('lock', '')
                    $('.commentblock .commentempty').hide()
                    $this.siblings().removeClass('selectActive');
                    if ($this.hasClass('selectActive')) {
                        $this.removeClass('selectActive');
                        $list.data('type', 'all');
                        _func.getComment();
                    } else {
                        $this.addClass('selectActive');
                        $list.data('type', 'single');
                        $list.data('mapId', $this.data('id'));
                        _func.getCommentsBySeg($this.data('id'));
                    }
                });
                _func.commentSegCall = function (data) {
                    var $commentSegblock, comments, d, init, _i, _len;
                    $commentSegblock = $('.comEvaluated .item_btm .item_info');
                    init = $commentSegblock.data('initComment');
                    data = JSON.parse(data);
                    if (data && data.CommentSegContent) {
                        comments = data.CommentSegContent;
                        for (_i = 0, _len = comments.length; _i < _len; _i++) {
                            d = comments[_i];
                            $commentSegblock.append('<span data-id=' + d.MapNewPropID + ' data-total=' + Math.ceil(d.SegCount / 10) + '>' + d.MapName + '(' + d.SegCount + ')</span>');
                        }
                    }
                };
                _func.getComment = function (index) {
                    if (index == null) {
                        index = _vars.commentpagestart;
                    }
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            hotelId: _vars.hotelid,
                            pageIndex: index,
                            t: +new Date()
                        },
                        url: _vars.commenturl,
                        success: _func.commentCall,
                        error: function () {
                            $('.commentblock .commentempty').show();
                        }
                    });
                };
                _func.getCommentsBySeg = function (mapId, index) {
                    if (index == null) {
                        index = _vars.commentpagestart;
                    }
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            hotelId: _vars.hotelid,
                            mapProId: mapId,
                            pageIndex: index,
                            t: +new Date()
                        },
                        url: _vars.hotelPropReviewUrl,
                        success: _func.commentCall
                    });
                };
            })();
            _func.getCommentSeg = function () {
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        hotelId: _vars.hotelid,
                        t: +new Date()
                    },
                    url: _vars.commentSegUrl,
                    success: _func.commentSegCall
                });
            };
            _func.initComment = function () {
                var $commentSegblock, $commentblock;
                $commentblock = $('.commentblock');
                $commentSegblock = $('.comEvaluated .item_btm .item_info');
                if (!$commentblock.data('init')) {
                    _func.getComment();
                    $commentblock.data('init', 'inited');
                }
                if (!$commentSegblock.data('init')) {
                    _func.getCommentSeg();
                    $commentSegblock.data('init', 'inited');
                }
            };


        },

        indexAction: function () {
            $("#btnSubmit").click(function () {
                _n.hook('loading');
                $.post(_vars.hotelParaSetUrl, $("#queryForm").serialize(), function () {
                    _func.visitList();
                });
            });

            _func.addPageLoadCall(function () {
                var cookie = JSON.parse($.fn.cookie('hotelhistory'));
                var $history = $('.Cvisit');
                var html = '';
                if (cookie && cookie.length > 0) {
                    $history.removeClass('Ldn');
                    cookie.map(function (item) {
                        html += '<li><a href="' + item.url + '">' + item.name + '<span class="price"><i>￥</i>' + item.price + '起</span></a></li>';
                    });
                    $history.find('ul').html(html);
                }
            });

        },

        cityAction: function () {
            $(_vars.target.hotel.txtSearch).on("focus", function () {
                N.visit(_vars.globalSearchUrl);
            });

            $("#globalSearch").on("focus", function () {
                N.visit(_vars.globalSearchUrl);
            });
            //城市列表
            $('.CsearchList').on('click', 'li', function () {
                var $self, cityid, cityname;
                $self = $(this);
                cityid = $self.data('cityid');
                if (cityid) {
                    cityname = $self.text().replace(/^\s|\s$/g, '');
                    $.post(_vars.hotelParaSetUrl, { "cityid": cityid, "cityname": (encodeURIComponent(cityname)) }, function () {
                        _n.visit("" + _vars.cityurl);
                    });
                }
            });

            //自能提示
            _func.cityAutoComplete = function (items) {
                var i;
                var html = "";
                var exist = [];
                var append = [];
                for (i = 0; i < _vars.cities.length; i++) {
                    //城市匹配
                    var add = true;
                    var reg = new RegExp('^' + items + '.*$', 'im');
                    var contains = new RegExp('^' + '.*' + items + '.*$', 'im');
                    if (reg.test(_vars.cities[i].jc) || reg.test(_vars.cities[i].py) || reg.test(_vars.cities[i].mc)) {
                        add = false;
                        exist.push(_vars.cities[i]);
                        html += '<li data-cityid=' + _vars.cities[i].id + '>' + _vars.cities[i].mc + '</li>';
                    }
                    if (add && (contains.test(_vars.cities[i].jc) || contains.test(_vars.cities[i].py) || contains.test(_vars.cities[i].mc))) {
                        append.push(_vars.cities[i]);
                    }
                }
                for (j = 0; j < append.length; j++) {
                    html += '<li data-cityid=' + append[j].id + '>' + append[j].mc + '</li>';
                }
                $(_vars.target.hotel.searchResult).html(html).show();
            };
            //当前城市
            $('.locationitem').on('click', '.currcity', function () {
                var $self, cityid, cityname;
                $self = $(this);
                cityid = $self.data('cityid');
                if (cityid) {
                    cityname = $self.data('cityname');
                    $.post(_vars.hotelParaSetUrl, { "cityid": cityid, "cityname": (encodeURIComponent(cityname)) }, function () {
                        _n.visit("" + _vars.cityurl);
                    });
                }
            });
            $('.locationitem').on('click', '.currlocation', function () {
                var $self = $(this);
                var latlng = $self.data('latlng');
                var $city = $('.currcity');
                var cityid = $city.data('cityid');
                var cityname = $city.data('cityname');
                if (latlng) {
                    $.post(_vars.hotelParaSetUrl, { "LatLng": latlng, "cityid": cityid, "cityname": "附近酒店" }, function () {
                        _n.visit("" + _vars.cityurl);
                    });
                }
            });
            $('table.citytable td').on('click', function () {
                var $self, cityid, cityname, countryId, countryName;
                $self = $(this);
                cityid = $self.data('cityid');
                if (cityid) {
                    cityname = $self.text().replace(/^\s|\s$/g, '');
                    countryId = $self.data('countryid');
                    countryName = $self.data('countryname');
                    var data = { "cityid": cityid, "cityname": (encodeURIComponent(cityname)) };
                    if (countryId) {
                        data.countryid = countryId;
                        data.countryname = encodeURIComponent(countryName);
                    }
                    $.post(_vars.hotelParaSetUrl, data, function () {
                        _n.visit("" + _vars.cityurl);
                    });
                }
            });


            //            //搜索城市
            //            $("#globalSearch").on("input", function () {
            //                var input = $.trim($(this).val());
            //                if (input === "") {
            //                    $(_vars.target.hotel.searchResult).html('').hide();
            //                } else {
            //                    _func.globalAutoComplete(input);
            //                }
            //            });

            _func.globalAutoComplete = function (txt) {
                var i;
                var html = "";
                for (i = 0; i < _vars.globalcities.length; i++) {
                    var g = _vars.globalcities[i];
                    var reg = new RegExp('^' + txt + '.*$', 'im');
                    if (reg.test(g.CountryName) || reg.test(g.CountryEnName) || reg.test(g.DepartmentName) || reg.test(g.DepartmentEnName) || reg.test(g.EnName) || reg.test(g.Name) || reg.test(g.ProvinceEnName) || reg.test(g.ProvinceName) || reg.test(g.AreaName) || reg.test(g.AreaEnName) || reg.test(g.jc)) {
                        html += '<li>' + g.Name + '</li>';
                    }
                }
                $(_vars.target.hotel.searchResult).html(html).show();
            }

        },

        searchAction: function () {

            $(_vars.target.hotel.txtSearch).on("input", function () {
                var input = $.trim($(this).val());
                var result = '';
                if (input === '') {
                    $(_vars.target.hotel.searchResult).html('').hide();
                    return;
                }
                var from = _vars.from;
                if (from === 'city' || from === 'home') {
                    var city = '', globalcity = '';
                    city = _func.cityAutoComplete(input);
                    if (_vars.isOnlyInlandCity !== '1') {
                        globalcity = _func.globalAutoComplete(input);
                    }
                    result = city + globalcity;
                    if (result !== '') {
                        $(_vars.target.hotel.searchResult).html(result).show();
                    } else {
                        $(_vars.target.hotel.searchResult).html('').hide();
                    }
                }
                else if (from === 'keyword') {
                    var names = _func.hotelNameComplete(input);
                    $(_vars.target.hotel.searchResult).html(names).show();
                    if (window.AMap) {
                        _func.locationSearch(input);
                    }
                }

            });
            //自能提示
            _func.cityAutoComplete = function (items) {
                var i;
                var html = "";
                var exist = [];
                var append = [];
                for (i = 0; i < _vars.cities.length; i++) {
                    //城市匹配
                    var add = true;
                    var reg = new RegExp('^' + items + '.*$', 'im');
                    var contains = new RegExp('^' + '.*' + items + '.*$', 'im');
                    if (reg.test(_vars.cities[i].jc) || reg.test(_vars.cities[i].py) || reg.test(_vars.cities[i].mc)) {
                        add = false;
                        exist.push(_vars.cities[i]);
                        html += '<li data-type="city" data-cityid=' + _vars.cities[i].id + ' class="pure-g"><span class="pure-u-20-24">' + _vars.cities[i].mc + '</span><span class="pure-u-4-24 Ltar">城市</span></li>';
                    }
                    if (add && (contains.test(_vars.cities[i].jc) || contains.test(_vars.cities[i].py) || contains.test(_vars.cities[i].mc))) {
                        append.push(_vars.cities[i]);
                    }
                }
                for (j = 0; j < append.length; j++) {
                    html += '<li data-type="city" data-cityid=' + append[j].id + ' class="pure-g"><span class="pure-u-20-24">' + append[j].mc + '</span><span class="pure-u-4-24 Ltar">城市</span></li>';
                }
                return html;
            };
            _func.globalAutoComplete = function (txt) {
                var i;
                var html = "";
                for (i = 0; i < _vars.globalcities.length; i++) {
                    var g = _vars.globalcities[i];
                    var reg = new RegExp('^' + txt + '.*$', 'im');
                    if (reg.test(g.CountryName) || reg.test(g.CountryEnName) || reg.test(g.DepartmentName) || reg.test(g.DepartmentEnName) || reg.test(g.EnName) || reg.test(g.Name) || reg.test(g.ProvinceEnName) || reg.test(g.ProvinceName) || reg.test(g.AreaName) || reg.test(g.AreaEnName) || reg.test(g.jc)) {
                        html += '<li data-type="city" data-countryid=' + g.CountryId + ' data-countryname=' + g.CountryName + ' data-cityid=' + g.Id + ' class="pure-g"><span class="pure-u-20-24">' + g.Name + '</span><span class="pure-u-4-24 Ltar">城市</span></li>';
                    }
                }
                return html;
            };
            _func.hotelNameComplete = function (txt) {
                var html = "";
                $.ajax({
                    type: 'GET',
                    data: { "hotelName": txt },
                    async: false,
                    dataType: 'json',
                    url: _vars.hotelNameUrl,
                    success: function (result) {
                        result.map(function (item) {
                            html += '<li data-type="hotel"  data-hotelId=' + item.HotelID + ' class="pure-g"><span class="pure-u-20-24">' + item.HotelName + '</span><span class="pure-u-4-24 Ltar">酒店</span></li>';
                        });
                    }
                });
                return html;
            };


            $('.CsearchList').on('click', 'li', function () {
                var $self, cityid, cityname, countryId, countryName, type;
                $self = $(this);
                cityid = $self.data('cityid');
                type = $self.data('type');
                if (type === 'city' && cityid) {
                    cityname = $self.find('span').first().text().replace(/^\s|\s$/g, '');
                    countryId = $self.data('countryid');
                    countryName = $self.data('countryname');
                    var data = { "cityid": cityid, "cityname": (encodeURIComponent(cityname)) };
                    if (countryId) {
                        data.countryid = countryId;
                        data.countryname = encodeURIComponent(countryName);
                    }
                    $.post(_vars.hotelParaSetUrl, data, function () {
                        _func.addhistory('city', $self);
                        _n.visit(decodeURIComponent(_vars.returnUrl));
                    });
                }
                if (type === 'hotel') {
                    _func.addhistory('keyword', $self);
                    _func.KeyWordComplete($self.find('span').first().text().replace(/^\s|\s$/g, ''));
                }
                if (type === 'clear') {
                    $.fn.cookie('search', '', { expires: -1 });
                    $(_vars.target.hotel.searchResult).html('').hide();
                }
            });

            //提示内容点击
            $(".CsearchList").on('click', 'li.item a', function () {
                var $self;
                $self = $(this);
                var p = $self.data('params');
                var url = p.split('?')[0];
                var param = p.substr(p.indexOf('?') + 1).split('&');
                var d = {};
                param.map(function (item) {
                    var key, value;
                    key = item.split('=')[0];
                    value = item.split('=')[1];
                    d[key] = value;
                });
                $.post(_vars.hotelParaSetUrl, d, function () {
                    _func.addhistory('keyword', $self.parent().parent('li'));
                    N.visit(url + '?isEdit=Y');
                });
            });

            //提交关键字及区域
            $("#btnRight").on('click', function () {
                _func.KeyWordComplete($('#txtSearch').val().replace(/</g, '').replace(/>/g, '').replace(/script/g, ''));
            });

            _func.KeyWordComplete = function (txt) {
                $.post(_vars.hotelParaSetUrl, { 'areaId': '', 'keyword': txt }, function () {
                    if (txt.length) {
                        var $li = $('<li data-type="hotel"  class="pure-g"><span class="pure-u-20-24">' + txt + '</span><span class="pure-u-4-24 Ltar">关键词</span></li>');
                        _func.addhistory('keyword', $li);
                    }
                    _n.visit(decodeURIComponent(_vars.returnUrl));
                });
            }
            _func.initSearchSuggest = function () {
                var $input, _height, recordnum;
                $input = $('.searchkey');
                if (!$input.data('initSuggest')) {
                    $input.data('initSuggest', true);
                    _height = $('.keytab .rarea').height();
                    recordnum = Math.ceil(_height / 39);
                    AMap.service(['AMap.PlaceSearch'], function () {
                    });
                    _func.locationSearch = function (input) {
                        if (!window.AMap || !AMap.PlaceSearch) {
                            return;
                        }
                        var placeSearch = new AMap.PlaceSearch({
                            pageSize: recordnum,
                            city: _vars.currcity
                        });
                        placeSearch.search(input, function (status, result) {
                            var $suggest, _html, address, index, item, j, keyreg, keyword, labeltext, len, name, param, ref, url;
                            _html = '';
                            $suggest = $(_vars.target.hotel.searchResult);
                            if (status !== 'complete') {
                                $suggest.hide().html('');
                                return void 0;
                            }
                            keyreg = new RegExp("(" + (input.replace(/(\(|\))/g, '\\$1')) + ")");
                            ref = result.poiList.pois;
                            for (index = j = 0, len = ref.length; j < len; index = ++j) {
                                item = ref[index];
                                name = item.name.replace(keyreg, "<b>$1<\/b>");
                                if (item.name.indexOf('(') < 0 && item.address) {
                                    address = "(" + (item.address.replace(/\(|\)/g, function (char) {
                                        if (char === '(') {
                                            return ' ';
                                        } else {
                                            return '';
                                        }
                                    })) + ")";
                                } else {
                                    address = '';
                                }
                                labeltext = "" + name + address;
                                keyword = "" + item.name + address;
                                param = ("keyword=" + (encodeURIComponent(keyword)) + "&LatLng=" + (item.location.getLat()) + "|" + (item.location.getLng())).replace('"', '\'');
                                url = "" + _vars.keywordurl + (_vars.keywordurl.indexOf('?') < 0 ? '?' : '&') + param;
                                _html += '<li data-type="location" class="item pure-g"><span class="pure-u-20-24"><a class="Ctextover" data-params="' + url + '" href="javascript:;">' + labeltext + '</a></span><span class="pure-u-4-24 Ltar">位置</span></li>';

                            }
                            _html = $(_vars.target.hotel.searchResult).html() + _html;
                            $(_vars.target.hotel.searchResult).html(_html).show();
                        });
                    }

                }
            };

            _func.addhistory = function (type, element) {
                var max, val;
                max = 3;
                var wrap = document.createElement('div');
                wrap.appendChild(element[0].cloneNode(true));
                val = wrap.innerHTML
                var cookie = JSON.parse($.fn.cookie('search'));
                if (!cookie) {
                    cookie = {};
                }
                if (!cookie[type]) {
                    cookie[type] = [];
                } else {
                    if (cookie[type].length >= max) {
                        cookie[type].shift();
                    }
                }
                if ($.inArray(val, cookie[type]) < 0) {
                    cookie[type].push(val);
                }
                $.fn.cookie('search', JSON.stringify(cookie), {
                    expires: 30,
                    path: '/'
                });
            },
            _func.loadhistory = function () {
                var history = JSON.parse($.fn.cookie('search'));
                var from = _vars.from;
                var _html = '';
                for (var key in history) {
                    if (key === 'city' && (from === 'city' || from === 'home')) {
                        for (var item in history[key]) {
                            _html += history[key][item];
                        }
                    }
                    if (key === 'keyword' && from === 'keyword') {
                        for (var item in history[key]) {
                            _html += history[key][item];
                        }
                    }
                }
                if (_html.length) {
                    _html += '<li data-type="clear"  class="Ltac"><span>清除搜索历史</span></li>';
                    $(_vars.target.hotel.searchResult).html(_html).show();
                }
            },
            _func.loadhistory();

            window.wrapMapCallback = function () {
                _func.initSearchSuggest();
            };
            if (_vars.isInternational !== 'True') {
                if (!window.AMap) {
                    _func.addScript(_vars.mapurl + '&callback=wrapMapCallback');
                } else {
                    _func.initSearchSuggest();
                }
            } else {
                window.AMap = undefined;
            }

            _func.searchClean();

        },

        keywordAction: function () {
            // 记录最后点击
            var lastClick = 0;

            //区域选择
            $(".keywords li").ctap(function (e) {
                var btn = $(this).find("i.Cradiobox");
                var selected = btn.hasClass("selected");

                // 防止重复点击
                var currClick = +new Date();
                if (currClick - lastClick <= 100) {
                    return;
                }
                lastClick = +new Date();

                if (selected) {
                    btn.removeClass("selected");
                } else {
                    $(this).parents('.tabitem').not('.multiple').find('i.Cradiobox.selected').removeClass('selected');
                    // $(".keywords li").find("i").removeClass("selected");
                    btn.addClass("selected");
                }
            });

            _func.KeyWordComplete = function (f) {
                var id = "", name = "";
                var area = $(".keywords li .selected").parent().parent();
                if (area.length) {
                    id = area.data("areaid");
                    name = encodeURIComponent($.trim(area.text()));
                }

                var $distanceSelected = $("i.distanceSelect.selected"),
                    $areaSelected = $("i.areaSelect.selected"),
                    $keyword = $('#txtKeyword'),
                    $styleSelected = $("i.styleSelect.selected"),
                    $facility = $("i.Facility.selected"),
                    $price = $("i.PriceRange.selected"),
                    $particular = $("i.Particular.selected"),
                    $star = $("i.Star.selected");
                var $activitySelected = $("i.activitySelect.selected");
                if (!$activitySelected.length) {
                    $activitySelected = $("i.Activity.selected");
                }
                if ($activitySelected.length > 0) {
                    var activityId = $activitySelected.eq(0).data('activityid');
                    if (activityId === "pointEx") {
                        _vars.param.IsPointEx = "1";
                    } else {
                        _vars.param.activityFilter = activityId;
                    }
                }
                if ($distanceSelected.length > 0) {
                    _vars.param.distance = $distanceSelected.eq(0).data('distance');
                }
                if ($areaSelected.length > 0) {
                    _vars.param.areaId = $areaSelected.eq(0).data('area');
                    _vars.param.areaName = $areaSelected.eq(0).data('name');
                }
                if ($styleSelected.length > 0) {
                    _vars.param.HotelStyleList = '';
                    $styleSelected.map(function (index) {
                        var $item = $($styleSelected[index]);
                        _vars.param.HotelStyleList += $item.data('id') + '|';
                    });
                    if (_vars.param.HotelStyleList.length > 0) {
                        _vars.param.HotelStyleList = _vars.param.HotelStyleList.substr(0, _vars.param.HotelStyleList.length - 1);
                    }
                }
                if ($facility.length) {
                    _vars.param.facility = $areaSelected.eq(0).data('activityid');
                }
                if ($price.length) {
                    _vars.param.price = $price.eq(0).data('activityid');
                }
                if ($particular.length) {
                    _vars.param.particular = $particular.eq(0).data('activityid');
                }
                if ($star.length) {
                    _vars.param.star = $star.eq(0).data('activityid');
                }
                _vars.param.keyword = $keyword.val();
                if ($keyword.val() === '') {
                    _vars.param.LatLng = '';
                }
                if (f) {
                    $.post(_vars.hotelParaSetUrl, _vars.param, function () {
                    });
                    f();
                } else {
                    $.post(_vars.hotelParaSetUrl, _vars.param, function () {
                        N.visit(_vars.keywordurl);
                    });
                }
            };
            //提交关键字及区域
            $("#btnRight").on('click', function () {
                _func.KeyWordComplete();
            });

            //提示内容点击
            $(".suggest").on('click', 'li.item a', function () {
                var p = $(this).data('params');
                var url = p.split('?')[0];
                var param = p.substr(p.indexOf('?') + 1).split('&');
                var d = {};
                param.map(function (item) {
                    var key, value;
                    key = item.split('=')[0];
                    value = item.split('=')[1];
                    d[key] = value;
                });
                $.post(_vars.hotelParaSetUrl, d, function () {
                    N.visit(url + '?isEdit=Y');
                });
            });

            $("#txtKeyword").on('focus', function () {
                _func.KeyWordComplete(function () {
                    N.visit(_vars.globalSearchUrl);
                });
            });
        },

        listAction: function () {
            $('.HL_filter .inner').on('touchmove', _func.preventScroll);
            $(".filterbar #sort").ctap(function () {
                var $filter = $(".HL_filter"),
                    $sortBox = $("div.sortbox");
                $sortBox.removeClass('Ldn');
                $filter.addClass('mask');
            });
            //酒店排序
            $(".sortbox .item").ctap(function () {
                N.hook('loading');
                $self = $(this);
                $self.siblings('.selected').removeClass('selected');
                $self.addClass('selected');
                $.post(_vars.hotelParaSetUrl, {
                    'sortBy': $(this).data('sortby')
                }, function () {
                    N.visit(_vars.hotelList);

                });
            });
            $(".filterbar #brand").ctap(function () {
                var $filter = $(".HL_filter"),
                    $sortBox = $("div.brandbox");
                $sortBox.removeClass('Ldn');
                $filter.addClass('mask');
            });

            $(".filterbar #filter").ctap(function () {
                N.visit(_vars.keywordUrl);
            });
            $('.HL_filter .inner').ctap(function (e) {
                var $el = $(e.target || e.srcElement);
                if ($el.hasClass('inner')) {
                    $('.HL_filter').removeClass('mask');
                    $(this).find('.brandbox, .sortbox').addClass('Ldn');
                }
            });
            _func.addPageBeforeUnloadCall(function () {
                $('.HL_filter .inner').ctrigger('click');
            });


            $(_vars.target.hotel.btnFilterSubmit).on('click', function () {
                $.post(_vars.hotelParaSetUrl, { 'sortBy': $('#hidSortBy').val(), 'HotelStyleList': $('#brand_value').val() }, function () {
                    N.visit(_vars.hotelList);
                });
            });

            $("#checkinEdit").on('click', function () {
                var $this = $(this),
                    room = $('#checkin_count .item').first().find('.num').val(),
                    person = $('#checkin_count .item').eq(1).find('.num').val();
                $.post(_vars.hotelParaSetUrl, {
                    'room': room, 'person': person
                }, function () {
                    N.visit(_vars.hotelList);

                });
            });

        },

        detailAction: function () {
            $(".subitem .centbox.member").on('click', function (e) {
                //                var $this = $(this);
                //                var $memberPrice = $this.parent().parent().siblings('.memberclass');
                return _n.hook('loadroomTyoeDetail', [e, function () {
                    //                if ($memberPrice.hasClass('Ldn')) {
                    //                    $memberPrice.removeClass('Ldn');
                    //                } else {
                    //                    $memberPrice.addClass('Ldn');
                    //                }
                }, true /* notShowDialog */]);
            });
            $("#Aactivityzunxiang").click(function () {
                $("#divzunxiang").removeClass("Ldn");
            });
            $("#btnzunxianglb").click(function () {
                $("#divzunxiang").addClass("Ldn");
            });
            //收藏酒店
            $("#collect").on('click', function () {
                var $this = $(this);
                if ($this.children('i').hasClass('star_middle')) {
                    return;
                }
                if ($(this).data('loading') != 'loading') {
                    if ($this.data('login').length) {
                        N.visit($this.data('login'));
                        return;
                    }
                    $.post($this.data('collect'), { "hotelId": $this.data('hotelid') }, function (data) {
                        if (data.isSuccess == "1") {
                            $this.children('label').html('已收藏');
                            $this.children('i').removeClass('star_empty_middle').addClass('star_middle');
                            //                            $this.children('i').addClass('star_middle');
                            wa_track_event('酒店详情页', '收藏', $(".topblock .hotelname").text());
                        } else {
                            alert(result.oMessage);
                        }
                    });
                }
            });
            $("#share").on('click', function () {
                $(".Cpopup.snsshare").removeClass('Ldn');
            });

            $(".Cpopup.snsshare").on('click', function (e) {
                $(this).addClass('Ldn');
            });

            (function () {
                var renderStars = {
                    //星级的渲染
                    renderStar: function (data, obj) {
                        var arr = data.split('.');
                        var data1 = parseInt(arr[0])
                        var data2 = parseInt(arr[1])
                        for (var i = 0; i < data1; i++) {
                            obj.find("span .Lposa").eq(i).addClass("icon_star")
                        }
                        //渲染不足一星
                        var _data2 = (data2 % 2)
                        var _Val = 0
                        if (_data2 != 0) {
                            if (data2 != 5 && data2 != 9) {
                                data2 = (data2 + 1)
                            }
                        }
                        _Val = Math.round(((data2 / 10) * 30) + ((48 - 30) / 2))
                        var clipEle = obj.find("span .Lposa").eq(data1)
                        var clipVal = 'rect(0px, ' + _Val + 'px, 48px, 0px)'
                        if (data2 > 0) {
                            clipEle.addClass("icon_clip_star")
                            clipEle.css({
                                clip: clipVal,
                                display: 'inline-block'
                            })
                        }
                    },
                    //初始化
                    init: function () {
                        renderStars.renderStar(_vars.hotelScore, $(".item_mid"))
                    }
                }
                if (_vars.isPromotion !== "1") {
                    renderStars.init();
                }
            })();
            _func.addPageLoadCall(function () {
                $(".subitem .centbox.member").eq(0).ctrigger('click');
                if (_vars.isPromotion !== "1") {
                    _func.addhistory();
                }
                $.get(N.vars.nearbyurl, {}, function (data) {
                    var hotels = JSON.parse(data);
                    var $nearby = $('.Cvisit');
                    if (hotels.length > 0) {
                        $nearby.removeClass('Ldn');
                        var html = '';
                        hotels.map(function (item) {
                            html += '<li><a href="' + item.url + '">' + item.name + '<span class="price"><i>￥</i>' + item.price + '起</span></a></li>';
                        });
                        $nearby.find('ul').html(html);
                    }
                });
            });
        },
        detailInterAction: function () {
            _func.addPageLoadCall('price', function () {
                loadRoomPrice();
            });

            ///加载房型房价信息
            var loadRoomPrice = function () {
                var strParam = $("#checkin_room").val() + '-' + $("#checkin_people").val();
                var $tepmdiv = $("#" + strParam);
                $('.international_priceblock .roomitem').remove();
                if ($tepmdiv.length) {
                    $('.international_priceblock').append($tepmdiv.html());
                    $.get('/hotel/InterDetailCount', { 'roomCount': $('#checkin_room').val(), 'guestPerRoom': $("#checkin_people").val(), 'sj': Math.random() }, function (result) { });
                } else {

                    $.get(N.vars.roomUrl, { 'isEn': _vars.visitIsEn, 'roomCount': $('#checkin_room').val(), 'guestPerRoom': $("#checkin_people").val(), 'sj': Math.random() }, function (result) {
                        if (result.trim() != '') {
                            var $result = $(result);
                            $('.international_priceblock').append($result.find('#roomInfo').html());
                            var $temp = $('<div class="Ldn" id=' + strParam + '></div>');
                            $temp.append($result.find('#roomInfo').html());
                            $("#divDetail").after($temp);
                            $('body').append($result.find('#roomdetail').html());
                        }
                    });
                }
                    
                
            };
            $("#checkin_count").on('click', function () {
                loadRoomPrice();
            });
            $(".currency_label span").on('click', function () {
                var type = $(this).data('type');
                var other = $(this).data('other');
                var $others = $('.pswitch.' + type);
                var $local = $('.pswitch.' + other);
                $others.removeClass('Ldn');
                $local.addClass('Ldn');
            });

            (function () {
                var renderStars = {
                    //星级的渲染
                    renderStar: function (data, obj) {
                        var arr = data.split('.');
                        var data1 = parseInt(arr[0])
                        var data2 = parseInt(arr[1])
                        for (var i = 0; i < data1; i++) {
                            obj.find("span .Lposa").eq(i).addClass("icon_star")
                        }
                        //渲染不足一星
                        var _data2 = (data2 % 2)
                        var _Val = 0
                        if (_data2 != 0) {
                            if (data2 != 5 && data2 != 9) {
                                data2 = (data2 + 1)
                            }
                        }
                        _Val = Math.round(((data2 / 10) * 30) + ((48 - 30) / 2))
                        var clipEle = obj.find("span .Lposa").eq(data1)
                        var clipVal = 'rect(0px, ' + _Val + 'px, 48px, 0px)'
                        if (data2 > 0) {
                            clipEle.addClass("icon_clip_star")
                            clipEle.css({
                                clip: clipVal,
                                display: 'inline-block'
                            })
                        }
                    },
                    //初始化
                    init: function () {
                        renderStars.renderStar(_vars.hotelScore, $(".item_mid"))
                    }
                }
                if (_vars.isPromotion !== "1") {
                    renderStars.init();
                }
            })();
        },
    });

    _n.app('reserve', {
        init: function () {
            _vars.target.reserve = {
                divTotalPrice: "#divTotalPrice",
                divPrice: "#divPrice",
                divOrder: "#divOrder",
                divPreference: "#divPreference",
                divCoupon: "#divCoupon",
                divMinus: "#divMinus",
                divAdd: "#divAdd",
                divRoomCount: "#divRoomCount",
                divCaptcha: "#divCaptcha",
                selBookCount: "#selBookCount",
                txtTicket: "#txtTicket",
                txtName: "#txtName",
                txtMobile: "#txtMobile",
                txtCaptcha: "#txtCaptcha",
                hidCoupon: "#hidCoupon",
                hidBookCount: "#hidBookCount",
                checkForm: "#checkForm",
                btnCoupon: "#btnCoupon",
                btnCaptcha: "#btnCaptcha",
                btnCheck: "#btnCheck",
                btnSubmit: "#btnSubmit",
                btnBack: "#btnBack",
                btnPreference: "#btnPreference",
                firstName: "#firstName",
                lastName:"#lastName"
            };
            _vars.errorDiv = $(".errorS");
            _vars.promotionList = new Array();
            _vars.promotionUseList = {};
            if (_vars.couponAutoUseList && _vars.couponAutoUseList.length > 0) {
                for (var c in _vars.couponAutoUseList) {
                    var coupon = _vars.couponAutoUseList[c];
                    _vars.promotionUseList[coupon["BizDateTime"]] = {
                        pid: coupon["ProjectID"],
                        code: coupon["SelectedTickNo"],
                        value: coupon["DiscountPrice"]
                    };
                }
            }
            //HPMS+
            if (_vars.couponAutoStatus == "1") {
                var promotionArray = [];
                var totalDiscount = 0;
                for (var key in _vars.promotionUseList) {
                    var discount = _vars.promotionUseList[key].value;
                    //promotionArray.push(key + "|" + _vars.promotionUseList[key].code);
                    promotionArray.push(_vars.promotionUseList[key].code + "|" + key);
                    totalDiscount += parseInt(discount);
                }
                if (totalDiscount > 0) {
                    $(_vars.target.reserve.hidCoupon).val(promotionArray.join(','));
                }
            }
            //
            if (_vars.promotionAutoUseList && N.vars.promotionAutoUseList.length > 0) {
                for (var c in _vars.promotionAutoUseList) {
                    var promotion = _vars.promotionAutoUseList[c];
                    _vars.promotionUseList[promotion['d']] = promotion['o'];
                }
            }


            _vars.lastSubmitCouponHtml = "";
            _vars.lastSubmitPromotionList = {};
            _vars.lastSubmitPromotionUseList = {};

            //查看价格明细
            $(_vars.target.reserve.divTotalPrice).ctap(function () {
                var $dialog = $(_vars.target.reserve.divPrice), $inner, $content, height, prevheight, wheight;
                if ($dialog.data('init') !== 'inited') {
                    $dialog.addClass('Lvh').show();
                    $inner = $dialog.find('.inner');
                    $content = $dialog.find('.content');
                    height = $content.find('.iscroll_wrapper').height();
                    wheight = window.innerHeight;
                    height = Math.min(height, wheight - 220);
                    $content.css('height', height);
                    $content.data('height', height);
                    $dialog.data('init', 'inited');
                    $dialog.removeClass('Lvh');

                    // 关闭价格明细
                    $dialog.on('click', function () {
                        $dialog.hide();
                    }).on('touchmove', function (e) {
                        $el = $(e.target || e.srcElement);
                        if ($el.hasClass('iscroll_wrapper') || $el.parents('.iscroll_wrapper').length) {
                            return;
                        }
                        return _func.preventScroll(e);
                    }).find('.cbox').on('click', function (e) {
                        $el = $(e.target || e.srcElement);
                        if ($el.hasClass('close') || $dialog.find('.close').find($el).length) {
                            return;
                        }
                        e.stopPropagation && e.stopPropagation();
                    });

                    if (!$.browser.ie) {
                        $content.css('overflow-y', 'hidden');
                        _vars.reversePopupIscroll = new IScroll($content.get(0), { scrollbar: true, mouseWheel: true });
                    }
                } else {
                    $dialog.addClass('Lvh').show();
                    $inner = $dialog.find('.inner');
                    $content = $dialog.find('.content');
                    height = $content.find('.iscroll_wrapper').height();
                    wheight = window.innerHeight;
                    height = Math.min(height, wheight - 220);
                    prevheight = parseInt($content.data('height'));
                    if (height != prevheight) {
                        $content.css('height', height);
                        $content.data('height', height);
                    }
                    $dialog.removeClass('Lvh');
                    _vars.reversePopupIscroll.refresh();
                    $dialog.show();
                }
            });

            //优惠券返回
            $(_vars.target.reserve.divCoupon).on("click", _vars.target.reserve.btnBack, function () {
                $(_vars.target.reserve.divOrder).show();
                $(_vars.target.reserve.divCoupon).hide();
                $(".errorS").hide();
            });


            $("#divBreakfast").on("click", _vars.target.reserve.btnBack, function () {
                $(_vars.target.reserve.divOrder).show();
                $("#divBreakfast").hide();
                $(".errorS").hide();
            });

            //优惠券选择日期
            $("#divCoupon").ctap(".dayselect .inner .item", function () {
                if ($(".coupons").hasClass('loading')) return;
                $(".coupons").addClass("loading");
                if (!$(this).data('date')) return;
                $(".errorS").hide();
                $(this).siblings().removeClass("curr");
                $(this).addClass("curr");
                $(".coupons tr i").removeClass("selected");
                $("#btnCheck").text("使用");
                $("#txtTicket").val("");
                $("#hidCouponCheckIn").val($(this).data('date'));

                $('#divCouponEmpty').hide();
                if (!$('.coupons tr[data-date="' + $(this).data('date') + '"]').html()) {
                    _n.hook('loading');
                    var $this = $(this);
                    $('.coupons tr').hide();
                    $('#divCouponLoading').show();

                    $.post($('.coupons').data('url'), { "CheckInDate": $('#hidCouponCheckIn').val(), "price": $("#hidCouponPrice").val(), "HotelID": $("#hidCouponHotelId").val() }, function (html, status, xhr) {
                        if (xhr.status === 200) {
                            $(".coupons .table").append(html);

                            for (var key in _vars.promotionCodes) {
                                if (!_vars.promotionList["pid" + _vars.promotionCodes[key].ProjectID]) {
                                    _vars.promotionList["pid" + _vars.promotionCodes[key].ProjectID] = _vars.promotionCodes[key].EcouponTickNo;
                                }
                            };
                            //$(".coupons ").remove('script');

                            var usedPromo = _vars.promotionUseList[$("#hidCouponCheckIn").val()];

                            //更新可用促销券数量
                            for (var key in _vars.promotionList) {
                                var coupon = $('.coupons tr[data-pid="' + key.substr(3) + '"][data-date="' + $("#hidCouponCheckIn").val() + '"]');
                                var num = _vars.promotionList[key].length;

                                if (usedPromo && usedPromo.pid == key.substr(3)) {
                                    num++;
                                    coupon.find("i").addClass("selected");
                                }

                                if (num) {
                                    coupon.find('text').text(num);
                                    coupon.show();
                                } else {
                                    coupon.hide();
                                }
                            }

                            //使用了未在优惠券列表的优惠券
                            if (usedPromo && !_vars.promotionList["pid" + usedPromo.pid]) {
                                $("#txtTicket").val(usedPromo.code);
                                $("#btnCheck").text("取消使用");
                            }
                        } else {
                            _func.alert(html || "优惠券查询错误，请重试！");
                        }
                        $(".coupons").removeClass("loading");
                        _n.hook('hideloading');
                        $('#divCouponLoading').hide();

                        //                        if ($('.coupons tr[data-date="' + $("#hidCouponCheckIn").val() + '"]').not('.Ldn').length < 1) {
                        //                            $('#divCouponEmpty').show();
                        //                        }
                        $('.coupons tr[data-date="' + $this.data('date') + '"]').each(function () {
                            if ($(this).css('display') !== 'none') {
                                isNoCoupon = false;
                            }
                        });
                        $('#divCouponEmpty').toggle(isNoCoupon);
                    });
                } else {
                    $('.coupons tr').hide();
                    $('.coupons tr[data-date="' + $(this).data('date') + '"]').show();

                    var usedPromo = _vars.promotionUseList[$(this).data('date')];

                    //更新可用促销券数量
                    for (var key in _vars.promotionList) {
                        var coupon = $('.coupons tr[data-pid="' + key.substr(3) + '"][data-date="' + $(this).data('date') + '"]');
                        var num = _vars.promotionList[key].length;

                        if (usedPromo && usedPromo.pid == key.substr(3)) {
                            num++;
                            coupon.find("i").addClass("selected");
                        }

                        if (num) {
                            coupon.find('text').text(num);
                            coupon.show();
                        } else {
                            coupon.hide();
                        }
                    }
                    var isNoCoupon = true;
                    $('.coupons tr[data-date="' + $(this).data('date') + '"]').each(function () {
                        if ($(this).css('display') !== 'none') {
                            isNoCoupon = false;
                        }
                    });
                    $('#divCouponEmpty').toggle(isNoCoupon);
                    //使用了未在优惠券列表的优惠券
                    if (usedPromo && !_vars.promotionList["pid" + usedPromo.pid]) {
                        $("#txtTicket").val(usedPromo.code);
                        $("#btnCheck").text("取消使用");
                    }
                    $(".coupons").removeClass("loading");
                }

            });

            //优惠券选择
            $(_vars.target.reserve.divCoupon).ctap(".coupons tr", function () {
                if ($(".coupons").hasClass("loading")) {
                    return;
                }
                $(".errorS").hide();

                var btn = $(this).find("i");
                var day = $(".dayselect .item.curr");

                var usedPromo = _vars.promotionUseList[day.data('date')];
                if (usedPromo) {
                    _vars.promotionList["pid" + usedPromo.pid].push(usedPromo.code);
                    delete _vars.promotionUseList[day.data('date')];
                }

                if (btn.hasClass("selected")) {
                    btn.removeClass("selected");
                    day.find("span").text("");
                } else {
                    _vars.promotionUseList[day.data('date')] = {
                        pid: $(this).data('pid'),
                        code: _vars.promotionList["pid" + $(this).data('pid')].shift(),
                        value: $(this).data("price")
                    };

                    $(this).siblings().find("i").removeClass("selected");
                    btn.addClass("selected");
                    day.find("span").text("已使用" + $(this).data("price") + "元");
                    $("#btnCheck").text("使用");
                    $("#txtTicket").val("");
                }
            });

            //检查输入的券号
            $(_vars.target.reserve.divCoupon).ctap(_vars.target.reserve.btnCheck, function () {
                $(".errorS").hide();
                var day = $(".dayselect .curr");

                var usedPromo = _vars.promotionUseList[day.data('date')];
                if (usedPromo) {
                    _vars.promotionList["pid" + usedPromo.pid].push(usedPromo.code);
                    delete _vars.promotionUseList[day.data('date')];
                }

                if ($(this).text() === "取消使用") {
                    $("#txtTicket").val("");
                    $(this).text("使用");
                    day.find("span").text("");
                    return;
                }

                if (!$("#txtTicket").val()) {
                    _func.alert("请输入有效的券号!");
                    return;
                }

                $.post($("#checkForm").attr("action"), $("#checkForm").serialize(), function (result) {
                    if (result.Check.ResultType === 0) {
                        if (result.Check.Data.CanUse) {
                            if (result.Coupon.ResultType === 0) {
                                //判断是否在已使用列表中
                                for (var i in _vars.promotionUseList) {
                                    if (result.Coupon.Data.ECoupon.TiketNo === _vars.promotionUseList[i].code) {
                                        _func.alert("该券码已经在其他日期使用！");
                                        return;
                                    }
                                }

                                //判断是否在列表中
                                var codes = _vars.promotionList["pid" + result.Coupon.Data.ECoupon.ProjectID];
                                if (codes) {
                                    var index = codes.indexOf(result.Coupon.Data.ECoupon.TiketNo);
                                    codes.splice(index, 1);
                                }

                                _vars.promotionUseList[day.data('date')] = {
                                    pid: result.Coupon.Data.ECoupon.ProjectID,
                                    code: result.Coupon.Data.ECoupon.TiketNo,
                                    value: result.Coupon.Data.ECoupon.DiscountPrice
                                };

                                $(".coupons tr").find("i").removeClass("selected");
                                $("#btnCheck").text("取消使用");
                                day.find("span").text("已使用" + result.Coupon.Data.ECoupon.DiscountPrice.toString() + "元");
                            } else {
                                _func.alert(result.Coupon.Message);
                            }
                        } else {
                            _func.alert(result.Check.Message);
                        }
                    } else {
                        _func.alert(result.Check.Message);
                    }
                });
            });

            //提交优惠券
            $(_vars.target.reserve.divCoupon).on("click", "#btnOk", function () {
                $(".errorS").hide();
                _vars.lastSubmitCouponHtml = $(_vars.target.reserve.divCoupon).html();
                _vars.lastSubmitPromotionUseList = {};
                $.extend(_vars.lastSubmitPromotionUseList, _vars.promotionUseList);
                _vars.lastSubmitPromotionList = _func.syncPromotionList(_vars.promotionList);
                _func.submitCounpon();
            });

            $("#btnCoupon .clear").ctap(function () {
                _vars.promotionUseList = {};
                _func.submitCounpon();
                $("#divCoupon").empty();
                return false;
            });

            //修改房间数后重算价格明显
            _func.recalc = function (room) {
                var breakfast = $("#AllBreakfast").val(),
                    coupon = $("#BreakfastCouponCount").val(),
                    breakfastType = $("#BreakfastType").val(),
                    breakfastMoney = $("#divBreakfast").data('breakfastamount'),
                    breakfastPoint = $("#divBreakfast").data('breakfastpoint'),
                    otherNum = $("#BreakfastCount").val(),
                    $divPrice = $(_vars.target.reserve.divPrice + " .price"),
                    $divTotalPrice = $(_vars.target.reserve.divTotalPrice + " .price"),
                    coupondiscount = $(_vars.target.reserve.btnCoupon).data('discount');
                var totalAmount = parseInt(_vars.totalAmount) * room;
                if (parseInt(coupondiscount) > 0) {
                    totalAmount -= coupondiscount;
                }
                var _ispoint = _vars.isPointEx == "1";
                //                if (!_ispoint) {
                $(_vars.target.reserve.divPrice + " .body").each(function () {
                    var discount = 0;
                    if ($(this).data("discount")) {
                        discount = parseInt($(this).data("discount"));
                    }
                    if (!_ispoint) {
                        $(this).find(".amount").text("￥" + (parseInt($(this).data("amount")) * room - discount).toString());
                    } else {
                        $(this).find(".amount").text((parseInt($(this).data("amount")) * room - discount + "积分").toString());
                    }

                });
                //                }
                if (parseInt(breakfast) > 0) {
                    $(".iscroll_wrapper .breakfast.body").remove();
                    $(".iscroll_wrapper .breakfast").removeClass('Ldn');
                    if (breakfastType === "money" && !_ispoint) {
                        totalAmount = totalAmount + (parseInt(breakfast) - parseInt(coupon)) * breakfastMoney;
                    }
                    var priceHtml, totalPriceHtml;
                    if (!_ispoint) {
                        priceHtml = "<i>￥</i>" + totalAmount.toString();
                        totalPriceHtml = "<i>￥</i>" + totalAmount.toString();
                    } else {
                        var pricepoint = $divPrice.data('point') * room,
                            totalpoint = $divTotalPrice.data('point') * room;
                        if (breakfastType == 'point') {
                            pricepoint = parseInt(pricepoint) + otherNum * breakfastPoint;
                            totalpoint = parseInt(totalpoint) + otherNum * breakfastPoint;
                        }
                        priceHtml = pricepoint + "积分";
                        totalPriceHtml = totalpoint + "积分";
                    }
                    if (breakfastType) {
                        $(".iscroll_wrapper").append('<div class="pure-g body breakfast"><div class="pure-u-1-3">' + (parseInt(breakfast) - parseInt(coupon)) + '</div><div class="pure-u-1-3">' + (breakfastType == "money" ? '金额' : '积分') + '</div><div class="pure-u-1-3">' + (breakfastType == "money" ? '￥' + otherNum * breakfastMoney : otherNum * breakfastPoint + '积分') + '</div></div>');
                    }
                    if (parseInt(coupon) > 0) {
                        $(".iscroll_wrapper").append('<div class="pure-g body breakfast"><div class="pure-u-1-3">' + coupon + '</div><div class="pure-u-1-3">早餐券</div><div class="pure-u-1-3">￥0</div></div>');
                    }
                    $divPrice.html(totalPriceHtml + "(含早)");
                    $divTotalPrice.html(priceHtml + "(含早)");
                } else {
                    $(".iscroll_wrapper .breakfast").addClass('Ldn');
                    if (!_ispoint) {
                        $divTotalPrice.html("<i>￥</i>" + totalAmount.toString());
                        $divPrice.html("<i>￥</i>" + totalAmount.toString());
                    } else {
                        $divTotalPrice.html(($divTotalPrice.data('point')) * room + "积分");
                        $divPrice.html($divPrice.data('point') * room + "积分");
                    }
                }
            };

            _func.syncPromotionList = function (source) {
                var target = {};
                $.extend(target, source);
                for (var key in source) {
                    target[key] = source[key].slice(0);
                }

                return target;
            };

            _func.reserveLogin = function () {
                if (_vars.reserveIsLogin != "1") {
                    N.visit(_vars.reserveLoginUrl);
                    return false;
                }
                return true;
            };


            //未登录情况下 填写完手机号显示验证码
            $("#txtMobile").on('input', function () {
                var $this = $(this),
                    $verifyCode = $(".roominfo .verycode"),
                    $imgCode = $(".roominfo .imgcode");
                if ($this.val().length >= 11) {
                    if ($verifyCode.hasClass('Ldn')) {
                        $verifyCode.removeClass('Ldn');
                    }
                    if ($imgCode.hasClass('Ldn') && $imgCode.hasClass('show')) {
                        $imgCode.removeClass('Ldn');
                    }
                }
            });

            //未登录情况下 更新图形验证码
            $("#imgCaptcha").on('click', function () {
                _func.clearMessage();
                $(this).attr('src', _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
            });

            //发送手机验证码
            $("#btnCheck").on('click', function () {
                var $btnSms = $(this),
                    $lblMessage = $("#smsMessage"),
                    data = {},
                    $inputSMS = $("#txtMobile");
                if ($btnSms.data('loading') === 'loading') {
                    return false;
                }
                $(".errorS").hide();
                $lblMessage.addClass('Ldn');

                if (_vars.reserveIsLogin != "1") {
                    if (!_func.CheCkEmpty($inputSMS, "手机号/会员卡号", _func.showMessage)) {
                        return false;
                    }
                    if (!_func.isCellPhone($.trim($inputSMS.val()))) {
                        _func.showMessage(_vars.message.validate.rightPhone);
                        return false;
                    }
                    data.CaptchaType = "ReseveNotLogin";
                    data.CellPhone = $("#txtMobile").val();
                    $lblMessage.html("已发送至" + $("#txtMobile").val());
                } else {
                    if (_vars.isPointEx == "1") {
                        data = { "CaptchaType": "OrderPointConfirm" };
                    }
                }
                $btnSms.data('loading', 'loading');
                _vars.url.sendSms = _vars.ServerHttp + "Account/SendSms/";
                if (_vars.reserveIsLogin != "1") {
                    _func.prePostAsync(function () {
                        data.CellPhone = encryptedString(_vars.rsakey, data.CellPhone + "#" + _vars.timestamp);
                        _vars.SendSms.init($btnSms, data, function () {
                            $lblMessage.removeClass('Ldn');
                        }, { clear: "获取验证码" });
                    });
                } else {
                    _vars.SendSms.init($btnSms, data, function () {
                        $lblMessage.removeClass('Ldn');
                    }, { clear: "获取验证码" });
                }

                $btnSms.data('loading', '');
            });

        },

        indexAction: function () {
            //积分兑换判断积分是否充足
            if (_vars.notEnoughPoint.toLowerCase() == 'true' && _vars.reserveIsLogin == "1") {
                _func.showMessage('您的积分余额不足,无法兑换!');
            }

            $(_vars.target.reserve.selBookCount).on("change", function () {
                var $self, day, option;
                $self = $(this);
                day = $self.data('day');
                if ($('#hidCoupon').val()) {
                    if (!confirm("已使用优惠券，限订1间房，是否继续？")) {
                        $self.val(day);
                        return;
                    } else {
                        $("#btnCoupon .clear").ctrigger('click');
                    }
                }

                var num = parseInt($('#hiddaycount').val()) * parseInt($(_vars.target.reserve.selBookCount).val());
                $("#btnzunxiang .note").text("可选" + num + "个礼包，已选" + $("#numvlues").val() + "个礼包");
                $("#hidselBookCount").val(num);
                $self.data('day', $self.val());
                if (this.selectedOptions) {
                    option = this.selectedOptions[0];
                } else if (this.options) {
                    option = this.options[this.selectedIndex];
                }
                if (option) {
                    $self.siblings('div.roomtype').find('span').text($(option).text());
                }
                _n.hook('recalc');
                if (!$("#btnClearChamber").parent().hasClass('showclear')) {
                    return;
                }
                var numbers = _vars.activeChamberNumbers.slice(0, $(_vars.target.reserve.selBookCount).val());
                var array = _vars.activeChamberArray.slice(0, $(_vars.target.reserve.selBookCount).val());
                $("#hidselBookCount").val($(_vars.target.reserve.selBookCount).val());
                $("#divChamber").text("已为您选房：" + numbers.join(','));
                $("#hidChambers").val(array.join(','));
            });

            $('.notraceinfo.noRecord .Cswitch').on('click', function () {
                var $self = $(this);
                var $input = $('#NoRecord');
                if ($self.hasClass('enable')) {
                    $self.removeClass('enable');
                    $input.val('0');
                } else {
                    $self.addClass('enable');
                    $input.val('1');
                    wa_track_event('无痕预定', '查看详情', N.vars.memberid);
                }
            });

            //修改房间数后重算价格明显
            _func.selectRecalc = function () {
                var room = parseInt($(_vars.target.reserve.selBookCount).val());

                roomChangeInitBreakfast(room);
                _func.recalc(room);
                _func.reCalContact(room);
                _func.renderUI(room)
            };

            _func.createCalContact = function () {
                return $('<li class="line_middle"><input type="text"  value="" name="ContactName" class="input1"  placeholder="每间填写一位入住人"></li>');
            }
            _func.reCalContact = function (room) {
                var existUl = $('.my-user-list'),
                    exist = existUl.find('li'),
                    ready = [].slice.call(exist.filter(function (ipt) {
                        return $.trim($(this).find('input').val());
                    }), 0);
                if (room > ready.length) {
                    while (ready.length < room) {
                        ready.push(_func.createCalContact())
                    }
                }
                else if (room < ready.length) {
                    while (ready.length > room) {
                        ready.pop();
                    }
                }
                existUl.empty();
                $.each(ready, function (idx, _li) {
                    existUl.append(_li)
                });


            }

            _func.renderUI = function (room) {
                var dayTotal, dayHotelTotal, $existUl, checkTotal,$rmsg;
                $existUl = $('.my-user-list');
                dayTotal = $existUl.data('dayTotal')
                dayHotelTotal = $existUl.data('dayHotelTotal')
                checkTotal = $existUl.data('checkTotal')

                $('.quickorder.INV_info_Contact .selected').removeClass('selected')

                $rmsg=$('.rmsg')
                $('#btnSubmit').html('提交订单')
                $rmsg.html($rmsg.data('msg'))
                $("div.toggleMsg").removeClass('Ldn')
                if (ndoo.vars.reserveIsLogin === '0') {
                    dayTotal = 0
                    dayHotelTotal = 0
                    checkTotal = 0
                }
                if (checkTotal) {
                    if (checkTotal + room > 3 || room > 3) {
                        $('#btnSubmit').html('去支付')
                        $rmsg.html(_vars.isafter12 === "True" ? $rmsg.data('after') : $rmsg.data('before'))
                        $("div.toggleMsg").addClass('Ldn')
                    }
                }
                else {
                    $.post(_vars.reserveroomUrl, { 'checkInDate': ndoo.vars.wa.checkin_date, 'hotelId': _vars.wa.hotel_id }, function (data) {
                        if (data.isSuccess === 1) {
                            var hotelTotal = 0;
                            $.each(data.Data, function (index, item) {
                                if (item['HotelID'] === _vars.wa.hotel_id) {
                                    hotelTotal += item['RoomNum']
                                }
                            })
                            $existUl.data('checkTotal', hotelTotal)
                            if (hotelTotal + room > 3 || room > 3) {
                                $('#btnSubmit').html('去支付')
                                var html = '<span class="Lcc00">' + (_vars.isafter12 === "True" ? $rmsg.data('after') : $rmsg.data('before')) + '</span>';
                                $rmsg.html(html)
                                $("div.toggleMsg").addClass('Ldn')
                            }
                        }
                    })
                }
            }

            $('.my-user-list').on('input', 'li input', function () {
                var maxLength = 12
                if (_func.getLength($(this).val()) > maxLength)
                    $(this).val(_func.limitMaxLength($(this).val(), maxLength));

            })

            //选择历史中的发票记录
            $('.quickorder.INV_info_Contact .content').on('click', '.room', function () {
                var $this = $(this).find('i.small');
                var selcted = $this.hasClass('selected');
                var existUl = $('.my-user-list'),
                    exist = existUl.find('li'),
                    ready = [].slice.call(exist.filter(function (ipt) {
                        return $.trim($(this).find('input').val());
                    }), 0);
                
                //                $('.quickorder.INV_info_Contact .room i.small').removeClass('selected');
                //                $('.quickorder.INV_info_Contact .room .roomtype').removeClass('selected');
                if (!selcted) {
                    if (exist.length - ready.length - $('.quickorder.INV_info_Contact .content .room i.small.selected').length <= 0) {
                        return;
                    }
                    $this.addClass('selected');
                    $this.siblings('.roomtype').addClass('selected');
                } else {
                    $this.removeClass('selected');
                    $this.siblings('.roomtype').removeClass('selected');
                }

            });

            // 入住人提示弹窗
            $('.my-user-info-label').ctap(function () {
                $('body').append('<div class="Cpopup default" id="popup-roomtips">\
                    <div class="inner Cwrap"><div class="cbox"><div class="info">\
                    请正确填写入住人姓名，<br/>当实际入住人与订单入住人不一致时，酒店有权拒绝接待！\
                    </div></div></div></div>');
                //                setTimeout(function() {
                //                    $('#popup-roomtips').remove();
                //                }, 3000);
                $('#popup-roomtips').on('click', function (e) {
                    var $el = $(e.target || e.srcElement);
                    if (!$el.hasClass('info')) {
                        $(this).remove();
                    }
                   
                })
            });

            //            $(".quickorder.INV_info_Contact").on('click', function (e) {
            //                e.stopPropagation();
            //                var $el = $(e.target || e.srcElement);
            //                if (!$el.hasClass('cbox')) {
            //                    $(this).addClass('Ldn');
            //                }
            //            })


            $(".quickorder.INV_info_Contact .std_large_button").on('click', function () {
                $(".quickorder.INV_info_Contact").addClass('Ldn');
                var selected = $('.quickorder.INV_info_Contact .content .room i.small.selected');
                var existUl = $('.my-user-list'),
                    exist = existUl.find('li'),
                    unReady = [].slice.call(exist.filter(function (ipt) {
                        return !$.trim($(this).find('input').val());
                    }), 0),
                    ready = [].slice.call(exist.filter(function (ipt) {
                        return $.trim($(this).find('input').val());
                    }), 0);

                for (var i = 0; i < unReady.length; i++) {
                    if (selected[i]) {
                        $(unReady[i]).find('input').val(_func.limitMaxLength($(selected[i]).data('name'), 12));
                    }

                }
//                if (selected.length > unReady.length) {
//                    var temp = 0;
//                    for (var j = unReady.length; j < selected.length; j++) {
//                        $(ready[temp]).find('input').val(_func.limitMaxLength($(selected[j]).data('name'),12));
//                        temp++;
//                    }
//
//                }
                //                $('.quickorder.INV_info_Contact .selected').removeClass('selected')

            });


            _n.hook('recalc', _func.selectRecalc);


            //选择联系人 带出相关的手机号码
            $('ul.people li').on('click', function () {
                var $this = $(this),
                    $name = $("#txtName"),
                    $mobile = $('#txtMobile');
                $name.val($this.data('name'));
                $mobile.val($this.data('mobile'));

            });

            //未登录情况下 填写完手机号显示验证码
            $("#txtMobile").on('input', function () {
                var $this = $(this),
                    $verifyCode = $(".roominfo .verycode"),
                    $imgCode = $(".roominfo .imgcode");
                if ($this.val().length >= 11) {
                    if ($verifyCode.hasClass('Ldn')) {
                        $verifyCode.removeClass('Ldn');
                    }
                    if ($imgCode.hasClass('Ldn') && $imgCode.hasClass('show')) {
                        $imgCode.removeClass('Ldn');
                    }
                }
            });

            //未登录情况下 更新图形验证码
            $("#imgCaptcha").on('click', function () {
                _func.clearMessage();
                $(this).attr('src', _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
            });

            ///  -----------------更多会员权益------------
            //展开收起会员权益
            $('.moreright .title.block').ctap(function () {
                var $self = $(this);
                var $moreright = $self.parent();
                if ($moreright.hasClass('expand')) {
                    $self.find('.Cicon.down').removeClass('highreverse');
                    $moreright.removeClass('expand').find('.expandbox').hide();
                    if (!$self.data('ga-close')) {
                        wa_track_event("更多会员权益", "收起");
                        $self.data('ga-close', true);
                    }
                } else {
                    $self.find('.Cicon.down').addClass('highreverse');
                    $moreright.addClass('expand').find('.expandbox').show();
                    if (!$self.data('ga-open')) {
                        wa_track_event("更多会员权益", "打开");
                        $self.data('ga-open', true);
                    }
                }
            });
            ////===========================================================////
            ////===========================发票============================////
            ////===========================================================////
            ////===========================================================////
            //打开发票
            $('#btnInvoice').click(function () {
                var $this = $(this);
                if (!_func.reserveLogin()) {
                    return;
                }
                if ($this.data('init') !== 'inited') {
                    $.get(_vars.invoiceUrl, '', function (data) {
                        if (data && data !== 'null') {
                            _vars.invoices = data;
                            var invoice = JSON.parse(data);
                            var html = '';
                            if (!invoice.length) {
                                $("#divInvoice .block i.addNew").addClass('Ldn').removeClass('addNew');
                            }
                            invoice.map(function (d) {
                                if (!d['Title'] || d['Title'] === '') return;
                                html += '<div class="pure-g room">' +
                                                '<div class="pure-u-24-24 roomtypebox">' +
                                                '<i class="Cradiobox small" data-id="' + d['Tid'] + '"><i class="Cicon correct_char_small white" >&nbsp;</i></i>' +
                                      '<div class="roomtype">' + d['Title'] + '</div>' +
                                      '</div>' +
                                      '</div>';
                            });
                            $('.quickorder.INV_info_popup .content').html(html);
                            $this.data('init', 'inited');
                        }
                    });
                }

                $(".errorS").hide();
                $("#divOrder").hide();
                $('#divInvoice').show();
            });

            //发票返回
            $('#btnInvoiceBack').click(function () {
                _func.clearMessage();
                $('#divInvoice').hide();
                $("#divOrder").show();
            });

            //发票选择
            $('#divInvoice .tab div').click(function () {
                $(".errorS").hide();

                $(this).addClass('curr');
                $(this).siblings().removeClass('curr');

                if ($(this).hasClass('person')) {
                    $('.block.person').show();
                    $('.block.company').hide();
                } else {
                    $('.block.company').show();
                    $('.block.person').hide();
                }
            });
            $('.socialnum').on('input', function () {
                var $this = $(this),
                    $tax = $('.taxnum');
                if ($this.val().trim() !== '') {
                    $tax.val('').attr('readonly', 'readonly').css('background-color', '#d1d1d1')
                } else {
                    $tax.removeAttr('readonly').css('background-color', '')
                }
            }).on('click', function () {
                var $this = $(this);
                if ($this.attr('readonly') === 'readonly') {
                    alert('删除纳税人识别码后方可填写!');
                }
            });
            $('.taxnum').on('input', function () {
                var $this = $(this),
                    $tax = $('.socialnum');
                if ($this.val().trim() !== '') {
                    $tax.val('').attr('readonly', 'readonly').css('background-color', '#d1d1d1')
                } else {
                    $tax.removeAttr('readonly').css('background-color', '')
                }
            }).on('click', function () {
                var $this = $(this);
                if ($this.attr('readonly') === 'readonly') {
                    alert('删除统一社会信用代码后方可填写!');
                }
            });
            //发票提交
            $('#btnSubmitInvoice').click(function () {
                _func.clearMessage();
                var type = $("#divInvoice .LI_main .tabs .tab.active").data('active');
                var block = $('#divInvoice .INV_info .content').find('.block.' + type);

                if (type === 'first') {
                    if (!block.find('.name').val()) {
                        _func.alert("请输入发票抬头！");
                        return;
                    }

                    $('#hidInvoiceTitle').val(block.find('.name').val());
                }
                if (type === 'second') {
                    if (!block.find('.name').val()) {
                        _func.alert("请输入发票抬头！");
                        return;
                    }
                    if (!block.find('.socialnum').val() && !block.find('.taxnum').val()) {
                        _func.alert("请输入纳税人识别号或统一社会信用代码！");
                        return;
                    }
                    var len = block.find('.taxnum').val().length;
                    if (block.find('.taxnum').val() && len !== 15 && len !== 17 && len !== 18 && len !== 20) {
                        _func.alert("纳税人识别号输入长度有误，请检查！");
                        return;
                    }
                    if (!block.find('.address').val()) {
                        _func.alert("请输入注册地址！");
                        return;
                    }
                    if (!block.find('.phone').val()) {
                        _func.alert("请输入联系电话！");
                        return;
                    }
                    if (!block.find('.bank').val()) {
                        _func.alert("请输入开户银行！");
                        return;
                    }
                    if (!block.find('.banknum').val()) {
                        _func.alert("请输入开户账号！");
                        return;
                    }

                    $('#hidInvoiceTitle').val(block.find('.name').val());
                    $('#hidInvoiceSocial').val(block.find('.socialnum').val());
                    $('#hidInvoiceTaxNum').val(block.find('.taxnum').val());
                    $('#hidInvoiceAddress').val(block.find('.address').val());
                    $('#hidInvoicePhone').val(block.find('.phone').val());
                    $('#hidInvoiceBank').val(block.find('.bank').val());
                    $('#hidInvoiceBankNum').val(block.find('.banknum').val());
                }
                if (type) {
                    $('#hidInvoiceType').val(type === 'first' ? 'common' : 'spiclal');
                }
                $('#btnInvoice .note').text('发票已填写');
                $('#btnInvoice').parent().addClass('showclear');
                $('#divInvoice').hide();
                $("#divOrder").show();
            });

            //清空发票
            $('#btnClearInvoice').click(function () {
                $('#btnInvoice .note').text($('#btnInvoice .note').data('msg'));
                $('#btnInvoice').parent().removeClass('showclear');
                $('#hidInvoiceType').val('');
                $("#divInvoice .INV_info .content input").not('[readonly=readonly]').val('');

            });

            $("#divInvoice .block i.addNew").on('click', function () {
                _func.clearMessage();
                $('.quickorder.INV_info_popup').removeClass('Ldn');
                $('.fcWrap').css({
                    height: '200px',
                    overflow: 'hidden'
                });
            });

            $(".INV_info_popup").on('touchstart', '.pop_close', function (e) {
                $('.quickorder.INV_info_popup').addClass('Ldn');
                // e.preventDefault();
                $('.fcWrap').css({
                    height: '100%',
                    overflow: 'auto'
                });
            });
            $(".INV_info_popup").on('touchmove', '.btnbox', function (e) {
                e.preventDefault();
            });
            $("body").on('touchmove', "#INV_info", function (e) {
                e.preventDefault();
            });



            //选择历史中的发票记录
            $('.quickorder.INV_info_popup .content').on('click', '.room', function () {
                var $this = $(this).find('i.small');
                var selcted = $this.hasClass('selected');
                $('.quickorder.INV_info_popup .room i.small').removeClass('selected');
                $('.quickorder.INV_info_popup .room .roomtype').removeClass('selected');
                if (!selcted) {
                    $this.addClass('selected');
                    $this.siblings('.roomtype').addClass('selected');
                }
            });


            //确定选择发票历史
            $(".quickorder.INV_info_popup .std_large_button").on('click', function () {
                $(".quickorder.INV_info_popup").addClass('Ldn');
                $('.fcWrap').css({
                    height: '200px',
                    overflow: 'hidden'
                });
                var i = $('.quickorder.INV_info_popup .content .room i.small.selected');
                if (i && i.data('id')) {
                    var invoices = JSON.parse(_vars.invoices);
                    invoices.map(function (item) {
                        if (item['Tid'] === i.data('id')) {
                            //                            var type = $("#divInvoice .LI_main .tabs .tab.active").data('active');
                            var block = $('#divInvoice .INV_info .content').find('.block');
                            block.find('.name').val(item['Title']);
                            block.find('.socialnum').val(item['UnifiedSocialCreditCode']);
                            block.find('.taxnum').val(item['TaxpayerCode']);
                            block.find('.address').val(item['CompanyAddress']);
                            block.find('.phone').val(item['PhoneNumber']);
                            block.find('.bank').val(item['CompanyBank']);
                            block.find('.banknum').val(item['CompanyBankAccountNumber']);
                            if (item['TaxpayerCode'] !== '') {
                                block.find('.socialnum').val('');
                            }
                            exist = true;
                        }
                    });
                } else {
                    var block = $('#divInvoice .INV_info .content').find('.block');
                    block.find('.name').val('');
                    block.find('.socialnum').val('');
                    block.find('.taxnum').val();
                    block.find('.address').val('');
                    block.find('.phone').val('');
                    block.find('.bank').val('');
                    block.find('.banknum').val('');
                }

            });

            $("#divInvoice .LI_main .tabs .tab").on('click', function () {

                var thisType = $(this).data('active'),
                    otherType = $(this).siblings('.tab').data('active'),
                    $this = $(this);

                if ($this.hasClass('active')) {
                    return;
                }
                _func.clearMessage();

                $this.addClass('active');
                $this.siblings().removeClass('active');
                $this.parent('.tabs').addClass(thisType).removeClass(otherType);


                $('#divInvoice .INV_info .content').find('.block.' + thisType).removeClass('Ldn');
                $('#divInvoice .INV_info .content').find('.block.' + otherType).addClass('Ldn');

                $('#divInvoice .noticebox p.' + thisType).removeClass('Ldn');
                $('#divInvoice .noticebox p.' + otherType).addClass('Ldn');
            });


            //取消选择的房间号
            $("#btnClearChamber").click(function () {
                $("#divChamber").text("未自动分配房间，可预订成功后再选房");
                $("#hidChambers").val("");
                $(this).parent().removeClass("showclear");
            });

            //打开促销券
            $("#btnPromotion").click(function () {
                if (!_func.reserveLogin()) {
                    return;
                }

                $(".errorS").hide();
                _n.hook('loading');

                if (!$("#divPromotion").html().length) {
                    var tickNo = '';
                    for (var c in _vars.promotionUseList) {
                        tickNo += _vars.promotionUseList[c].code + '|';
                    }
                    tickNo = tickNo.length > 0 ? tickNo.substr(0, tickNo.length - 1) : tickNo;
                    var param = { "ActivityID": $(this).data("activityid"), "AutoUse": _vars.promotionAutoUseList.length, 'tickNo': tickNo };
                    $("#divPromotion").load(_vars.promotionurl, param, function (result) {
                        if (result.ResultType === 2) {
                            _func.alert(result.Message);
                        } else {
                            $("#divOrder").hide();
                            $("#divPromotion").show();
                            for (var key in _vars.promotionCodes) {
                                _vars.promotionList["pid" + _vars.promotionCodes[key].ProjectID] = _vars.promotionCodes[key].TicketNoArrayString.split(',');
                            };

                            for (var c in _vars.promotionUseList) {
                                var p = _vars.promotionUseList[c];
                                console.log(p);
                                _vars.promotionList["pid" + p["pid"]].shift();
                            }

                        }
                        _n.hook('hideloading');
                        _n.hook('dayscroll');
                    });
                } else {
                    $("#divOrder").hide();
                    $("#divPromotion").show();
                    _n.hook('hideloading');
                }
            });

            //促销券选择日期
            $("#divPromotion").ctap(".prom .dayselect div", function () {
                if (!$(this).data('date')) return;
                $(".errorS").hide();
                $(this).siblings().removeClass("curr");
                $(this).addClass("curr");
                $(".prom .coupons tr i").removeClass("selected");
                $("#btnCheck").text("使用");
                $("#txtTicket").val("");

                var usedPromo = _vars.promotionUseList[$(this).data('date')];

                //更新可用促销券数量
                for (var key in _vars.promotionList) {
                    var coupon = $('.coupons tr[data-pid="' + key.substr(3) + '"]');
                    var num = _vars.promotionList[key].length;
                    if (usedPromo && usedPromo.pid == key.substr(3)) {
                        num++;
                        coupon.find("i").addClass("selected");
                    }
                    if (num) {
                        coupon.find('text').text(num);
                        coupon.show();
                    } else {
                        coupon.hide();
                    }
                }

                //使用了未在促销券列表的促销券
                if (usedPromo && !_vars.promotionList["pid" + usedPromo.pid]) {
                    $("#txtTicket").val(usedPromo.code);
                    $("#btnCheck").text("取消使用");
                }
            });

            //促销券点选
            $("#divPromotion").ctap(".prom .coupons tr", function () {
                $(".errorS").hide();

                var btn = $(this).find("i");
                var day = $(".prom .dayselect .item.curr");

                if (btn.hasClass("selected")) {
                    _vars.promotionList["pid" + _vars.promotionUseList[day.data('date')].pid].push(_vars.promotionUseList[day.data('date')].code);
                    delete _vars.promotionUseList[day.data('date')];

                    btn.removeClass("selected");
                    day.find(".Cicon").removeClass("selected");
                    day.find("text").text("未使用");
                } else {
                    _vars.promotionUseList[day.data('date')] = {
                        pid: btn.data('pid'),
                        code: _vars.promotionList["pid" + btn.data('pid')].shift()
                    };

                    $(this).siblings().find("i").removeClass("selected");
                    btn.addClass("selected");
                    day.find(".Cicon").addClass("selected");
                    day.find("text").text("已使用");
                    $("#btnCheck").text("使用");
                    $("#txtTicket").val("");
                }
            });

            //检查促销券
            $("#divPromotion").ctap("#btnCheck", function () {
                $(".errorS").hide();
                var day = $(".prom .dayselect .curr");

                if ($(this).text() === "取消使用") {
                    _vars.promotionList["pid" + _vars.promotionUseList[day.data('date')].pid].push(_vars.promotionUseList[day.data('date')].code);
                    delete _vars.promotionUseList[day.data('date')];

                    $("#txtTicket").val("");
                    $(this).text("使用");
                    day.find(".Cicon").removeClass("selected");
                    day.find("text").text("未使用");
                    return;
                }

                if (!$("#txtTicket").val()) {
                    _func.alert("请输入有效的券号!");
                    return;
                }

                $.post($("#checkForm").attr("action"), $("#checkForm").serialize(), function (result) {
                    if (result.ResultType === 0) {
                        if (result.Data.IsSuccess) {
                            //判断是否在已使用促销券列表中
                            for (var i in _vars.promotionUseList) {
                                if (result.Data.TicketInfo.TicketNo === _vars.promotionUseList[i].code) {
                                    _func.alert("该券码已经在其他日期使用！");
                                    return;
                                }
                            }

                            //判断是否在促销券列表中
                            var codes = _vars.promotionList["pid" + result.Data.TicketInfo.ProjectID];
                            if (codes) {
                                var index = codes.indexOf(result.Data.TicketInfo.TicketNo);
                                codes.splice(index, 1);
                            }

                            _vars.promotionUseList[day.data('date')] = {
                                pid: result.Data.TicketInfo.ProjectID,
                                code: result.Data.TicketInfo.TicketNo
                            };

                            $(".coupons tr").find("i").removeClass("selected");
                            $("#btnCheck").text("取消使用");
                            day.find(".Cicon").addClass("selected");
                            day.find("text").text("已使用");
                        } else {
                            _func.alert(result.Data.Message);
                        }
                    } else {
                        _func.alert(result.Message);
                    }
                });
            });

            //提交促销券
            $("#divPromotion").on("click", "#btnOk", function () {
                _func.submitPromotion(true);
            });
            //提交促销券
            _func.submitPromotion = function (check) {
                $(".errorS").hide();

                if (check) {
                    //检查是否每天使用促销券
                    if ($(".prom .dayselect .item").length !== $(".prom .dayselect .selected").length) {
                        _func.alert("每晚都需要使用一张促销券！");
                        return;
                    }
                }

                var promotionArray = [];
                for (var key in _vars.promotionUseList) {
                    promotionArray.push(key + "," + _vars.promotionUseList[key].code);
                }

                $("#hidPromotion").val(promotionArray.join('|'));
                $("#btnPromotion").find(".note").text("使用了" + promotionArray.length + "张促销券");
                if (promotionArray.length > 0) {
                    $(_vars.target.reserve.divTotalPrice).append('<p class="favorable">已使用' + promotionArray.length + '张促销券</p>');
                    $(_vars.target.reserve.divTotalPrice).addClass('reset_top');
                } else {
                    $(_vars.target.reserve.divTotalPrice + ' p.favorable').remove();
                    $(_vars.target.reserve.divTotalPrice).removeClass('reset_top');
                }
                $("#divOrder").show();
                $("#divPromotion").hide();
            }


            //促销券返回
            $("#divPromotion").on("click", "#btnBack", function () {
                $("#divOrder").show();
                $("#divPromotion").hide();
                $(".errorS").hide();
            });

            //是否优先选房
            $("#divAllowCheckInPush").ctap(function () {
                if (!_func.reserveLogin()) {
                    return;
                }
                $(this).toggleClass("enable");
                $("#hidAllowCheckInPush").val($(this).hasClass("enable"));
            });

            //早餐券
            var initBreakfast = function () {
                var maxbreakfast = parseInt($(_vars.target.reserve.selBookCount).val()) * parseInt($("#divBreakfast").data("everynight")) * parseInt($("#divBreakfast").data("night"));
                $("#breakfastCount").empty();
                for (var i = 0; i <= maxbreakfast; i++) {
                    if (i == 0) {
                        $("#breakfastCount").append("<option selected='selected' value=" + i + ">请选择</option>");
                    } else if (i == 1) {
                        $("#breakfastCount").append("<option  value=" + i + ">" + i + "份</option>");
                    } else {

                        $("#breakfastCount").append("<option value=" + i + ">" + i + "份</option>");
                    }

                }
                initBreakfastCoupon(maxbreakfast);
            };
            var displayPayByPoint = function (type) {
                $(".item.pay.point .pay").removeClass('.enable');
                if (type === 'show') {
                    $(".item.pay.point").show();
                    $(".item.pay.money div.type").html('');
                } else if (type === 'hide') {
                    $(".item.pay.point").hide();
                    $(".item.pay.money div.type").html('<span>其它支付方式</span>');
                }
            }
            var initBreakfastCoupon = function (breakfast) {
                var maxCoupon = 0,
                    $couponUse = $("#breakfastUse"),
                    $text = $("div.breakfastUse"),
                    old = $text.data('val');
                if ($couponUse) {
                    maxCoupon = $couponUse.data('max');
                }
                maxCoupon = maxCoupon > breakfast ? breakfast : maxCoupon;
                $couponUse.empty();
                for (var j = 0; j <= maxCoupon; j++) {
                    if (j == 0) {
                        $couponUse.append("<option selected='selected' value=" + j + " >不使用早餐券</option>");
                    } else {
                        $couponUse.append("<option value=" + j + ">" + j + "份</option>");
                    }

                }
                if (!old) {
                    old = 0;
                }
                old = breakfast > maxCoupon ? maxCoupon : breakfast;
                if (old == 0) {
                    $text.text("不使用早餐券");
                } else {
                    $text.text(old + "份");
                }
                $text.data('val', old);
                if ($couponUse.length) {
                    $couponUse[0].selectedIndex = old;
                }

            };
            var setPayValue = function () {
                var breakfast, breakfastCoupon, point, money;
                breakfast = $("#breakfastCount").val();
                breakfastCoupon = $("#breakfastUse").val();
                point = $("#divBreakfast").data('breakfastpoint');
                money = $("#divBreakfast").data('breakfastamount');
                if (!breakfastCoupon) {
                    breakfastCoupon = 0;
                }
                var remain = parseInt(breakfast) - parseInt(breakfastCoupon);
                remain = remain < 0 ? 0 : remain;
                $("#payPoint").html(remain * point + " 积分");
                $("#payMoney").html("￥" + remain * money + " 元");
                if (remain <= 0) {
                    $(".item.pay .pay").removeClass('enable').addClass('unable');
                } else {
                    if (remain * point > parseInt(_vars.memberPoint)) {
                        displayPayByPoint('hide');
                    } else {
                        displayPayByPoint('show');
                    }
                    //没有选择支付方式情况下选择现金支付
                    if ($(".item.pay .pay.enable").length <= 0) {
                        $(".item.pay .pay").removeClass('unable');
                        $(".item.pay.money .pay").addClass('enable');
                    }
                }

            };

            var hidePaytype = function () {
                var $text = $("div.breakfastUse"),
                    $couponUse = $("#breakfastUse");
                $couponUse.empty();
                $("#divBreakfast .item.pay .pay").removeClass('enable');
                $text.text("不使用早餐券").data('val', '0');

            };
            var clearBreakfast = function () {
                var $note = $("#btnBreakfast .note");
                $note.html($note.data('msg'));
                $("#BreakfastCount").val('');
                $("#BreakfastCouponCount").val('');
                $("#BreakfastType").val('');
                $("#AllBreakfast").val('');
                $("#divBreakfast .item.pay .pay").removeClass('enable');
                $("#btnBreakfast").removeClass('showclear');
                $("div#divBreakfast").empty().show();

            };
            var changeBreakfastCouponValue = function () {
                var num = $("select#breakfastUse").val(),
                    $text = $("div.breakfastUse");
                if (num == 0) {
                    $text.text("不使用早餐券").data('val', num);
                } else {
                    $text.text(num + "份").data('val', num);
                }

            };
            var roomChangeInitBreakfast = function (room) {
                var oldroom, $roomselect;
                $roomselect = $(_vars.target.reserve.selBookCount);
                oldroom = $roomselect.data('room');
                if (!oldroom) {
                    oldroom = 1;
                }
                if (oldroom == room) {
                    return;
                }
                    //之前房间数大于变更后的房间数 清空所有的数据
                else if (oldroom > room) {
                    clearBreakfast();
                }
                    //之前房间数小于变更后的房间数  只需初始化早餐选择页面数据
                else {
                    if ($("#divBreakfast").html().length) {
                        var $brealfast, $breakfastCoupon, maxCoupon, maxBreakfast;
                        $brealfast = $("#breakfastCount");
                        $breakfastCoupon = $("#breakfastUse");
                        maxCoupon = $breakfastCoupon.data('max');
                        maxBreakfast = parseInt($(_vars.target.reserve.selBookCount).val()) * parseInt($("#divBreakfast").data("everynight")) * parseInt($("#divBreakfast").data("night"));
                        for (var i = $("#breakfastCount option").length; i <= maxBreakfast; i++) {
                            $brealfast.append("<option  value=" + i + ">" + i + "份</option>");
                        }
                        if ($breakfastCoupon.length) {
                            var length = maxBreakfast > maxCoupon ? maxCoupon : maxBreakfast
                            for (var j = $("#breakfastUse option").length; j <= length; j++) {
                                $breakfastCoupon.append("<option value=" + j + ">" + j + "份</option>");;
                            }
                        }
                    }

                }
                $roomselect.data('room', room);


            };
            $("#btnBreakfast").on('click', function () {
                if (!_func.reserveLogin()) {
                    return;
                }
                $(".errorS").hide();
                _n.hook('loading');
                if (!$("#divBreakfast").html().length) {
                    $("#divBreakfast").removeClass("Ldn");
                    var param = { "checkInDate": $(this).data("date"), "hotelId": $(this).data("hotelid") };
                    $("#divBreakfast").load(_vars.breakfastUrl, param, function (result) {
                        if (result.ResultType === 2) {
                            _func.alert(result.Message);
                        } else {
                            $(_vars.target.reserve.divOrder).hide();
                            var point = $("#divBreakfast").data('breakfastpoint'),
                                money = $("#divBreakfast").data('breakfastamount');
                            if (point <= 0) {
                                $(".item.pay.point").addClass('Ldn');
                            }
                            if (money <= 0 || _vars.isPointEx == '1') {
                                $(".item.pay.money").addClass('Ldn');
                            } else {
                                if (point <= 0) {
                                    $(".item.pay.money .type").append("<span>其它支付方式</span>");
                                }
                            }
                            initBreakfast();
                            setPayValue();
                        }
                        _n.hook('hideloading');
                    });
                } else {
                    $(_vars.target.reserve.divOrder).hide();
                    $("#divBreakfast").show();
                    _n.hook('hideloading');
                }
            });
            $("#divBreakfast").on('change', 'select#breakfastCount', function () {
                var num = $(this).val();
                if (num === 0) {
                    $("div.breakfastCount").text("请选择");
                } else {
                    $("div.breakfastCount").text(num + "份");
                }

                if (num > 0) {
                    if ($(".payType").hasClass("Ldn")) {
                        $(".payType").removeClass("Ldn");
                    }
                    initBreakfastCoupon(num);
                } else {
                    hidePaytype();
                    $(".payType").addClass("Ldn");
                }
                setPayValue();
            });
            $("#divBreakfast").on('change', 'select#breakfastUse', function () {
                changeBreakfastCouponValue();
                setPayValue();
            });
            //尊享礼包
            $("#btnzunxiang").click(function () {
                if (!_func.reserveLogin()) {
                    return;
                }
                $(".errorS").hide();
                var num1 = parseInt($("#hidselBookCount").val());
                $("#mxanum").text(num1);
                _n.hook('loading');
                $(_vars.target.reserve.divOrder).hide();
                $("#divzunxiang").removeClass("Ldn");
                _n.hook('hideloading');
            });
            $("#azunxiang").click(function () {

                var texts = $("#hidGift").val();
                texts = texts.substr(0, texts.length - 1);
                var ss = texts.split('|');
                var num = 0;
                $(".CnumberSelect .plus").each(function () {
                    var id = $(this).siblings('.num').data("giftid");
                    var stid = "|";
                    for (var i = 0; i < ss.length; i++) {
                        if (id == ss[i].split(',')[0]) {
                            $(this).siblings('.num').text(ss[i].split(',')[1]);
                            num = num + parseInt(ss[i].split(',')[1]);
                            stid += "" + id + "|";
                        } else {
                            if (stid.indexOf("|" + id + "|") <= -1)
                            {
                                $(this).siblings('.num').text(0);
                            }
                        }
                        
                    }
                });

                $('#numvlues').val(num);
                $("#num").text(num);

                $("#divzunxiang").addClass("Ldn");
                $("#divOrder").show();
            })
            //小减号点击事件-1
            $(".CnumberSelect .minus").click(function () {
                var el = parseInt($(this).siblings('.num').text());
                var num = parseInt($("#hidselBookCount").val()) ;
                var numvlues = parseInt($('#numvlues').val());
                if (el > 0) {
                    el--;
                    numvlues--;
                    $('#numvlues').val(numvlues);
                    $("#num").text(numvlues);
                }
                $(this).siblings('.num').text(el);
            });

            //小加号点击事件+1
            $(".CnumberSelect .plus").click(function () {
                var el = parseInt($(this).siblings('.num').text());
                var num = parseInt($("#hidselBookCount").val()) ;
                var numvlues = parseInt($('#numvlues').val());
                if (el < num && numvlues < num) {
                    el++;
                    numvlues++;
                    $('#numvlues').val(numvlues);
                    $("#num").text(numvlues);
                }
                $(this).siblings('.num').text(el);
            });
            //尊享礼包页面确定按钮提交到订单
            $("#giftsbtn").click(function () {
                var num = 0;
                var texts = "";
                var num1 = parseInt($("#hidselBookCount").val());
                $(".CnumberSelect .plus").each(function () {
                    var el = parseInt($(this).siblings('.num').text());
                    var id = $(this).siblings('.num').data("giftid");
                    num = num + el;
                    if (el > 0) {
                        texts = texts + id + ',' + el + "|";
                    }
                });
                if (num > num1) {

                    alert("选择的礼包不能大于" + num1 + "个！");
                    return;
                    $(".errorS ").show();
                } else {
                    if (num != num1)
                    {
                        alert("你还可以选择" + (num1-num) + "个礼包！");
                        return;
                    }
                    $("#divzunxiang").addClass("Ldn");
                    $("#divOrder").show();

                    $("#btnzunxiang .note").text("可选" + num1 + "个礼包，已选" + num + "个礼包");
                    $("#hidGift").val(texts);
                    $("#numvlues").val(num);
                }
            });
            //早餐  取消
            $("div#divBreakfast").on('click', '.breakfast.cancel', function () {
                $("#divBreakfast div#btnBack").ctrigger('click');
            });
            //早餐 确认
            $("div#divBreakfast").on('click', '.breakfast.confirm', function () {
                var couponNum = $("#breakfastUse").val(),
                    breakfasrNum = $("#breakfastCount").val(),
                    buytype = $("div#divBreakfast .item.pay .pay.enable"),
                    msg = "";
                if (buytype.length) {
                    buytype = $(buytype[0]).data('paytype');
                } else {
                    buytype = '';
                }
                if (!couponNum) {
                    couponNum = 0;
                }
                if (parseInt(breakfasrNum) <= 0) {
                    alert("请选择要购买的早餐份数");
                    return false;
                } else {
                    if (parseInt(couponNum) <= 0 && !buytype) {
                        alert("请选择支付方式");
                        return false;
                    }
                }
                if (parseInt(couponNum) > parseInt(breakfasrNum)) {
                    alert("使用早餐券数量不能大于购买数量！");
                    return false;
                }
                if ((parseInt(breakfasrNum) - parseInt(couponNum)) > 0 && !buytype) {
                    alert("请选择正确的支付方式!");
                    return false;
                }
                $("#AllBreakfast").val(parseInt(breakfasrNum));
                $("#BreakfastCount").val(parseInt(breakfasrNum) - parseInt(couponNum));
                $("#BreakfastCouponCount").val(couponNum);
                $("#BreakfastType").val(buytype);

                if (buytype) {
                    var temp = (parseInt(breakfasrNum) - parseInt(couponNum)) + "份早餐";
                    if (buytype == "money") {
                        msg = "购买" + temp;
                    }
                    if (buytype == "point") {
                        msg = "积分购买" + temp;
                    }
                }

                if (parseInt(couponNum) > 0) {
                    if (buytype) {
                        msg = msg + "+" + couponNum + "份早餐券";
                    } else {
                        msg = couponNum + "份早餐券";
                    }
                }
                if (msg) {
                    $("#btnBreakfast .note").html(msg);
                }
                $("#btnBreakfast").addClass('showclear');
                $(this).data('buytype', '');
                var room = parseInt($(_vars.target.reserve.selBookCount).val());
                _func.recalc(room);

                wa_track_event("购买早餐", breakfasrNum, msg);
                $("#divBreakfast div#btnBack").ctrigger('click');
            });
            //早餐 其他支付方式
            $("div#divBreakfast").on('click', '.item.pay .pay', function () {
                if ($(this).hasClass('unable')) {
                    return false;
                }
                var $this = $(this),
                    $other = $(this).parents('.item.pay').siblings('.item.pay').find('.pay');

                if ($this.hasClass('enable')) {
                    $this.removeClass('enable');
                    $("div#divBreakfast .breakfast.confirm").data('buytype', '');
                } else {
                    $this.addClass('enable');
                    if ($other && $other.hasClass('enable')) {
                        $other.removeClass('enable');
                    }
                    $("div#divBreakfast .breakfast.confirm").data('buytype', $(this).data('paytype'));
                }
            });
            //取消所选早餐券
            $("#btnBreakfast .clear .clean").on('click', function (e) {
                clearBreakfast();
                var room = parseInt($(_vars.target.reserve.selBookCount).val());
                _func.recalc(room);
                e.stopPropagation();
            });

            //优惠券
            $(_vars.target.reserve.btnCoupon).ctap(function () {
                if (!_func.reserveLogin()) {
                    return;
                }


                if ($(_vars.target.reserve.selBookCount).val() > 1) {
                    if (!confirm("使用优惠券，限订1间房，是否继续？")) {
                        return;
                    }
                }

                $(".errorS").hide();
                _n.hook('loading');

                if (!$(_vars.target.reserve.divCoupon).html().length) {
                    var param = { "checkInDate": $(this).data("date"), "price": $(this).data("price"), "hotelId": $(this).data("hotelid") };
                    $(_vars.target.reserve.divCoupon).load(_vars.couponurl, param, function (result, status, xhr) {
                        if (xhr.status !== 200) {
                            _func.alert(result || "优惠券查询错误，请重试！");
                            $(_vars.target.reserve.divCoupon).empty();
                        } else {
                            $(_vars.target.reserve.divOrder).hide();
                            $(_vars.target.reserve.divCoupon).show();
                            for (var key in _vars.promotionCodes) {
                                _vars.promotionList["pid" + _vars.promotionCodes[key].ProjectID] = _vars.promotionCodes[key].EcouponTickNo;
                            };
                            for (var key in _vars.promotionUseList) {
                                var index = _vars.promotionList["pid" + _vars.promotionUseList[key].pid].indexOf(_vars.promotionUseList[key].code);
                                _vars.promotionList["pid" + _vars.promotionUseList[key].pid].splice(index, 1);
                            }

                            _vars.lastSubmitPromotionUseList = {};
                            $.extend(_vars.lastSubmitPromotionUseList, _vars.promotionUseList);
                            _vars.lastSubmitPromotionList = _func.syncPromotionList(_vars.promotionList);
                            var usedPromo = _vars.promotionUseList[$("#hidCouponCheckIn").val()];

                            //更新可用优惠券数量
                            for (var key in _vars.promotionList) {
                                var coupon = $('.coupons tr[data-pid="' + key.substr(3) + '"][data-date="' + $("#hidCouponCheckIn").val() + '"]');
                                var num = _vars.promotionList[key].length;

                                if (usedPromo && usedPromo.pid == key.substr(3)) {
                                    num++;
                                    coupon.find("i").addClass("selected");
                                }

                                if (num) {
                                    coupon.find('text').text(num);
                                    coupon.show();
                                } else {
                                    coupon.hide();
                                }
                            }

                            //使用了未在优惠券列表的优惠券
                            if (usedPromo && !_vars.promotionList["pid" + usedPromo.pid]) {
                                $("#txtTicket").val(usedPromo.code);
                                $("#btnCheck").text("取消使用");
                            }
                            var $days = $(".dayselect .item");
                            $days.each(function () {
                                try {
                                    $(this).find("span").text("已使用" + _vars.promotionUseList[$(this).data('date')].value + "元");
                                } catch (e) {

                                }
                            });

                            _vars.lastSubmitCouponHtml = $(_vars.target.reserve.divCoupon).html();
                        }

                        _n.hook('hideloading');
                        ndoo.hook('dayctrl');
                    });
                } else {
                    $(_vars.target.reserve.divCoupon).html(_vars.lastSubmitCouponHtml);
                    _vars.promotionUseList = new Array();
                    $.extend(_vars.promotionUseList, _vars.lastSubmitPromotionUseList);
                    _vars.promotionList = _func.syncPromotionList(_vars.lastSubmitPromotionList);
                    $(_vars.target.reserve.divOrder).hide();
                    $(_vars.target.reserve.divCoupon).show();
                    _n.hook('hideloading');
                    ndoo.hook('dayctrl');
                }
            });

            $(".Cpopup.confirm .link").on('click', function () {
                $(this).parents('.Cpopup.confirm').addClass('Ldn');
            });

            $(".Cpopup.confirm .back").on('click', function () {
                $(this).parents('.Cpopup.confirm').addClass('Ldn');
                N.vars.needasynname = "0";
                $(_vars.target.reserve.btnSubmit).ctrigger('click');
            });
            //提交订单
            $(_vars.target.reserve.btnSubmit).on("click", function () {
                $(".errorS").hide();

                //防止重复提交
                if ($(this).text() === "提交中...") {
                    return;
                }

                //验证
                _n.hook('checkPostUrl');
                var ActivityID = $("#ActivityID").val();
                var GiftActivityID = $("#hidGiftActivityID").val();
                if (ActivityID == GiftActivityID) {
                    var num1 = parseInt($('#selBookCount').val()) * parseInt($('#hiddaycount').val());
                    var giftnum = parseInt($("#numvlues").val());
                    if (giftnum > num1) {
                        _func.alert("选择的礼包数量不能大于" + num1 + "个！");
                        return false;
                    }
                    if (giftnum < num1) {
                        _func.alert("您还可以选" + (num1 - giftnum) + "个礼包！");
                        return false;
                    }
                }

                var existUl = $('.my-user-list'),
                    exist = existUl.find('li'),
                    ready = [].slice.call(exist.filter(function (ipt) {
                        return $.trim($(this).find('input').val());
                    }), 0)
                room = parseInt($(_vars.target.reserve.selBookCount).val());

                ///正常预订情况
                if (existUl.length > 0) {
                    if (room > ready.length) {
                        _func.alert("每间房必须填写1位入住人!");
                        return false;
                    }
                    for (var i = 0; i < ready.length; i++) {
                        if (!_func.verifyName($.trim($(ready[i]).find('input').val()))) {
                            return false;
                        }
                    }
                }

                if (!_func.isCellPhone($.trim($(_vars.target.reserve.txtMobile).val()))) {
                    _func.alert("请输入正确的手机号!");
                    return;
                }
                //if ($(_vars.target.reserve.txtName).attr('readonly') !== 'readonly') {
                //    if (!_func.verify($.trim($(_vars.target.reserve.txtMobile).val()), $.trim($(_vars.target.reserve.txtName).val()))) {
                //        return;
                //    }
                //}
                if (N.vars.needasynname === "1") {
                    var txt = $(_vars.target.reserve.txtName).val().toLowerCase().trim();
                    var reg = new RegExp("[0-9]+");
                    if (txt === "portal" || reg.test(txt)) {
                        _func.alert("入住人填写有误，请输入真实姓名!");
                        return false;
                    }
                }


                //校验积分换免房的情况下手机验证码是否为空
                var $smsCode = $("input[name=SmsCode]");
                if ($smsCode.length > 0 && $smsCode.val() == '') {
                    _func.showMessage('手机验证码不能为空!');
                    return false;
                }
                var $imgCode = $(".roominfo .imgcode");
                if ($imgCode.hasClass('show') && $imgCode.length > 0 && $imgCode.find('[name=imgCode]').val() == '') {
                    _func.showMessage('图形验证码不能为空!');
                    return false;
                }

                if ($("#AllBreakfast").val() > 0) {
                    var breakfastType = $("#BreakfastType").val(),
                        breakfastPoint = $("#divBreakfast").data('breakfastpoint'),
                        otherNum = $("#BreakfastCount").val(),
                        totalPoint = 0,
                        $divTotalPrice = $(_vars.target.reserve.divTotalPrice + " .price");
                    if (breakfastType == 'point') {
                        totalPoint = otherNum * breakfastPoint;
                    }
                    if (_vars.isPointEx == "1") {
                        totalPoint += $divTotalPrice.data('point');
                    }
                    if (totalPoint > _vars.memberPoint) {
                        _func.showMessage('您的积分余额不足!');
                        return false;
                    }
                }

                if (N.vars.needasynname === "1") {
                    $(".Cpopup.confirm").removeClass('Ldn');
                    return false;
                }
                if ($('[name=ActivePoint]').val() === "1") {
                    if (N.vars.memberPoint < $("#divTotalPrice .price").data('point') * $('#selBookCount').val()) {
                        _func.showMessage('您的积分余额不足，可先通过其他价格预订!');
                        return false;
                    }

                    $('.smsverify').removeClass('Ldn');
                    return false;
                }
                $(this).text("提交中...");
                $(this).addClass("gray");
                var form = $("#reserveForm");
                $.ajax({
                    type: "POST",
                    headers: { "__HttpVerificationToken": N.vars.__HttpVerificationToken },
                    url: form.attr("action"),
                    data: form.serialize(),
                    success: function (result) {
                        if (_vars.reserveIsLogin != "1") {
                            wa_track_event('提交订单', '免登陆订单', _vars.wa.hotel_id + '_' + _vars.wa.hotel_name);
                        }
                        if (result.ResultType === 0) {
                            //ga统计代码
                            var breakfast = $('#AllBreakfast').val(),
                                coupon = $('#BreakfastCouponCount').val(),
                                type = $('#BreakfastType').val();
                            if (breakfast > 0) {
                                var temp = "";
                                if (coupon > 0) {
                                    temp += '+早餐券';
                                }
                                if (type && type.length) {
                                    temp += type == 'money' ? '+现金' : '+积分';
                                }
                                if (temp.length > 0) {
                                    temp = temp.substr(1, temp.length - 1);
                                }
                                wa_track_event("提交早餐订单", $(".hotelinfo h3").html() + "_" + $("#btnBreakfast").data("hotelid"), breakfast + '_' + temp);
                            }
                            try {
                                if ($(".showtime").data('ga')) {
                                    wa_track_event("更多会员权益", "选择到店时间", $('.showtime').text());
                                }
                                if ($("#divAllowCheckInPush").hasClass('enable')) {
                                    wa_track_event("更多会员权益", "自助选房开启", ndoo.vars.wa.hotelId);
                                }
                                if ($("#btnInvoice .note").text() !== "请填写发票") {
                                    wa_track_event("更多会员权益", "填写发票");
                                }
                                if ($("[name=ContactRemark]").val().length > 0) {
                                    wa_track_event("更多会员权益", "其他要求", $("[name=ContactRemark]").val());
                                }
                                if ($("#hidCoupon").val()) {
                                    wa_track_event("填写订单页", "优惠券使用", $("#btnCoupon .note").data('value'));
                                }
                            } catch (e) {
                                console.log(e);
                            }

                            location.href = _vars.completeurl + "?resvNo=" + result.Data.BookingResult.PmsResvNo;
                        } else {
                            if ($('[name=ActivePoint]').val() === '0') {
                                alert(result.Message);
                                $(".smsverify .confirm").removeClass('gray');
                            } else {
                                _func.alert(result.Message);
                            }
                            $imgCode.removeClass('Ldn').removeClass('notshow').addClass('show');
                            if ($("#imgCaptcha")) {
                                $("#imgCaptcha").attr('src', _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                            }
                            //                        }
                            $(_vars.target.reserve.btnSubmit).text("提交订单");
                            $(_vars.target.reserve.btnSubmit).removeClass("gray");
                        }
                    }
                });
            });

            //提交优惠券
            _func.submitCounpon = function () {
                $(_vars.target.reserve.divOrder).show();
                $(_vars.target.reserve.divCoupon).hide();
                //多间情况下清空早餐券
                if ($(_vars.target.reserve.selBookCount).val() > 1) {
                    clearBreakfast();
                    $(".iscroll_wrapper .breakfast.body").remove();
                    $(".iscroll_wrapper .breakfast").addClass('Ldn');
                }
                var totalAmount = parseInt(_vars.totalAmount);
                var breakfast = $("#AllBreakfast").val(),
                    breakfastType = $("#BreakfastType").val(),
                    breakfastMoney = $("#divBreakfast").data('breakfastamount'),
                    otherNum = $("#BreakfastCount").val();

                var tempprice = '';
                if (parseInt(breakfast) > 0) {
                    tempprice += '(含早)';
                    if (parseInt(otherNum) > 0 && breakfastType === 'money') {
                        totalAmount += breakfastMoney * otherNum;
                    }
                }

                var promotionArray = [];
                var totalDiscount = 0;
                for (var key in _vars.promotionUseList) {
                    var discount = _vars.promotionUseList[key].value;

                    promotionArray.push(key + "," + _vars.promotionUseList[key].code);
                    totalDiscount += parseInt(discount);
                }

                $(_vars.target.reserve.divPrice + " .body").each(function () {
                    $(this).find(".amount").text("￥" + $(this).data("amount"));
                    $(this).find(".discount").text("");
                    $(this).data('discount', 0);
                });

                if (totalDiscount > 0) {
                    //设置优惠券
                    //_vars.emptyText = $(_vars.target.reserve.btnCoupon + " .note").text();
                    $(_vars.target.reserve.btnCoupon + " .note").text("已优惠" + totalDiscount.toString() + "元").data('value', totalDiscount);
                    $(_vars.target.reserve.hidCoupon).val(promotionArray.join('|'));
                    //设置优惠券减免价格
                    $(_vars.target.reserve.btnCoupon).data('discount', totalDiscount);
                    //限制房间数
                    $(_vars.target.reserve.divRoomCount).text("1间");
                    $(_vars.target.reserve.selBookCount).val(1);

                    var num = parseInt($('#hiddaycount').val()) * parseInt($(_vars.target.reserve.selBookCount).val());
                    $("#hidselBookCount").val(num);

                    $("#btnzunxiang .note").text("可选" + num + "个礼包，已选" + $("#numvlues").val() + "个礼包");
                    $(_vars.target.reserve.selBookCount).data('day', 1);
                    $(_vars.target.reserve.selBookCount).data('room', 1);
                    _func.reCalContact(1);
                    _func.renderUI(1);
                    //                    $(".roomctrl span").html('1');
                    //房间数设为1
                    //                    $(".roomctrl i.sub").removeClass('numctrl');
                    //                    $(_vars.target.reserve.selBookCount).addClass("Ldn");

                    var html = "<i>￥</i>" + totalAmount.toString() + "-" + totalDiscount.toString() + "=" + (totalAmount - totalDiscount).toString() + tempprice;
                    $(_vars.target.reserve.divPrice + " .body").each(function () {
                        $(this).find(".amount").text("￥" + parseInt($(this).data("amount")).toString());
                    });
                    $(_vars.target.reserve.divPrice + " .price").html(html);
                    $(_vars.target.reserve.divTotalPrice + " .price").html("<i>￥</i>" + (totalAmount - totalDiscount).toString() + tempprice);
                    $(_vars.target.reserve.divTotalPrice + " p.favorable").remove();
                    $(_vars.target.reserve.divTotalPrice).append('<p class="favorable">已优惠' + totalDiscount + '元</p>');
                    $(_vars.target.reserve.divTotalPrice).addClass('reset_top');
                    $(_vars.target.reserve.divPrice + " .count").text("共优惠￥" + totalDiscount.toString());

                    for (var key in _vars.promotionUseList) {

                        var date = $(_vars.target.reserve.divPrice + ' div[data-date="' + key + '"]');
                        var discount = _vars.promotionUseList[key].value;

                        date.find(".amount").text("￥" + (parseInt(date.data("amount")) - parseInt(discount)).toString());
                        date.find(".discount").text("￥" + discount);
                        date.data('discount', discount);
                    }
                    $("#btnCoupon").addClass("showclear");
                } else {
                    $(_vars.target.reserve.btnCoupon + " .note").text('使用优惠券抵扣部分房费').data('value', 0);
                    $(_vars.target.reserve.hidCoupon).val("");
                    //                    $(_vars.target.reserve.selBookCount).removeClass("Ldn");
                    $(_vars.target.reserve.btnCoupon).data('discount', 0);
                    $(_vars.target.reserve.divPrice + " .price").html("<i>￥</i>" + (totalAmount - totalDiscount).toString());
                    $(_vars.target.reserve.divTotalPrice + " .price").html("<i>￥</i>" + totalAmount.toString() + tempprice);
                    $(_vars.target.reserve.divTotalPrice + ' p.favorable').remove();
                    $(_vars.target.reserve.divTotalPrice).removeClass('reset_top');
                    $(_vars.target.reserve.divPrice + " .count").text("共优惠￥0");
                    $("#btnCoupon").removeClass("showclear");
                }
                //                roomChangeInitBreakfast($(_vars.target.reserve.selBookCount).val());
            };


            $("[name=ArrivalTime]").on('change', function () {
                var $showTime = $(".showtime");
                var op = $('[name=ArrivalTime] option').not(function () { return !this.selected; });
                $showTime.html($(op).text());
                if (!$showTime.data('ga')) {
                    $showTime.data('ga', true);
                }
            });


            _func.addPageLoadCall(function () {
                if (_vars.couponAutoUseList && _vars.couponAutoUseList.length > 0) {
                    _func.submitCounpon();
                }
                if (_vars.promotionAutoUseList && N.vars.promotionAutoUseList.length > 0) {
                    _func.submitPromotion();
                }
                $(".Lpt40.prepay").css('background-image', _vars.prepayUrl);
                var $name = $('.my-user-list li input')
                if ($name.length) {
                    $name.val(_func.limitMaxLength($name.val(), 12));
                }
                _func.renderUI(1);
            });



            $(".returnMoney .Cswitch").on('click', function () {
                var $this = $(this),
                    $return = $("#NeedReturn"),
                    $div = $(this).parent().siblings(".text"),
                    $item = $('.roominfo .item.mReturn');

                var $hide = $('.roominfo .item.mReturn.Ldn');
                var $show = $('.roominfo .item.mReturn').not('.Ldn');
                $hide.removeClass('Ldn').find('input').removeAttr('disabled');
                $show.addClass('Ldn').find('input').attr('disabled', 'disabled');

                if ($this.hasClass('enable')) {
                    $div.html($div.data('n'));
                    $this.removeClass('enable');
                    $return.val('0');
                    $div.addClass('fontSizeColor');
                    $(".dateinfo span.return").hide();
                    $("#divTotalPrice span.return").hide();
                    if (N.vars.modifynotreturn === '0') {
                        _vars.needasynname = "0";
                        $("#NeedAsynName").val('false');
                    }
                } else {
                    $this.addClass("enable");
                    $div.html($div.data('y'));
                    $return.val('1');
                    $div.removeClass('fontSizeColor');
                    $(".dateinfo span.return").show();
                    $("#divTotalPrice span.return").show();
                    if (N.vars.modifynotreturn === '0' && $("[name=ContactName]").length !== 2) {
                        _vars.needasynname = "1";
                        $("#NeedAsynName").val('true');
                    }
                }

            });

            $(".roomPreference a").on('click', function () {
                var $this = $(this);
                if ($this.hasClass('roomPreferenceItemActive')) {
                    $this.removeClass('roomPreferenceItemActive');
                } else {
                    $this.addClass('roomPreferenceItemActive');
                }
                var tag = '';
                var tags = $(".roomPreference a.roomPreferenceItemActive");
                for (var i = 0; i < tags.length; i++) {
                    tag += i === tags.length - 1 ? $(tags[i]).text() : $(tags[i]).text() + '|';
                }

                $("#roomtag").val(tag);

            });


            //关闭短信验证码窗口
            $(".smsverify .closecontent").on('click', function () {
                $(".smsverify").addClass('Ldn');
                _vars.errorDiv = $(".HO_main .errorS");
                $(".smsverify .error").html('').hide();
                $('[name=ActivePoint]').val('1');
            });

            //发送手机验证码
            $(".smsverify .send").on('click', function () {
                var $btnSms = $(this);
                _vars.errorDiv = $(".smsverify .error");
                _vars.url.sendSms = _vars.ServerHttp + "Account/SendSms/";
                _vars.SendSms.init($btnSms, { "CaptchaType": "MixPay" }, undefined, { clear: "获取验证码" });
            });


            //提交支付    第三方情况支付不为微信   
            $(".smsverify .confirm").on('click', function () {
                var $this = $(this);
                if ($this.hasClass('gray')) {
                    return false;
                }
                _func.clearMessage();
                var $thirdPay = $("#divPayType a .Cradiobox.selected");
                if ($thirdPay.length) {
                    var $selected = $($thirdPay[0]);
                    if ($selected.parents('a').data('type') === 'wechat') {
                        return false;
                    }
                }
                if (!$("#smsCode").val()) {
                    alert("验证码不能为空!");
                    return false;
                }
                $this.addClass('gray');
                $('[name=ActivePoint]').val('0');
                if ($('[name=SmsCode]').length <= 0) {
                    $("#reserveForm").append($('<input type="hidden" name="SmsCode" value="' + $("#smsCode").val() + '" />'));
                } else {
                    $('[name=SmsCode]').val($("#smsCode").val());
                }

                $(_vars.target.reserve.btnSubmit).ctrigger('click');
            });


            $('.choose-user-info i').on('click', function () {
                $('.Cmask.choose-user').removeClass('Ldn');
                $('.quickorder.INV_info_Contact .selected').removeClass('selected')
            })
        },
        quickAction: function () {
            _func.addPageLoadCall(function () {
                $(".Lpt40.prepay").css('background-image', _vars.prepayUrl);
            });

            //提交订单
            $(_vars.target.reserve.btnSubmit).on("click", function () {
                $(".errorS").hide();

                //防止重复提交
                if ($(this).text() === "提交中...") {
                    return;
                }
                if ($(_vars.target.reserve.txtName).attr('readonly') !== 'readonly') {
                    if (!_func.verify($.trim($(_vars.target.reserve.txtMobile).val()), $.trim($(_vars.target.reserve.txtName).val()))) {
                        return;
                    }
                }

                $(this).text("提交中...");
                $(this).addClass("gray");
                _n.hook('checkPostUrl');
                var form = $("#reserveForm");
                $.ajax({
                    type: "POST",
                    headers: { "__HttpVerificationToken": N.vars.__HttpVerificationToken },
                    url: form.attr("action"),
                    data: form.serialize(),
                    success: function (result) {
                        if (result.ResultType === 0) {
                            location.href = _vars.completeurl + "?resvNo=" + result.Data.BookingResult.PmsResvNo;
                        } else {
                            _func.alert(result.Message);
                            $(_vars.target.reserve.btnSubmit).text("提交订单");
                            $(_vars.target.reserve.btnSubmit).removeClass("gray");
                        }
                    }
                });
            });

        },


        indexInterAction: function () {
            _func.addPageLoadCall(function () {
                if (_vars.couponAutoUseList && _vars.couponAutoUseList.length > 0) {
                    _func.submitCounpon();
                }
                $(".Lpt40.prepay").css('background-image', _vars.prepayUrl);
            });


            $(_vars.target.reserve.btnPreference).on('click', function () {
                $(_vars.target.reserve.divPreference).show();
                $(_vars.target.reserve.divOrder).hide();
                $(_vars.errorDiv).hide();
            });

            $('#btnPreferenceBack,#btnGuaranteeBack').click(function () {
                $(_vars.target.reserve.divPreference).hide();
                $("#divGuarantee").hide();
                $(_vars.target.reserve.divOrder).show();
            });
            $(_vars.target.reserve.divPreference).on('click', '.mbox .item .Cradiobox', function () {
                var $this = $(this);
                if ($this.hasClass('selected')) {
                    $this.removeClass('selected');
                } else {
                    $this.addClass('selected');
                }
            });
            //住客偏好
            $(_vars.target.reserve.divPreference).on("click", "#btnOk", function () {
                $(".errorS").hide();
                var $quiet, $high, $honeymoon, $other;
                $quiet = $('#quiet');
                $high = $('#high');
                $honeymoon = $('#honeymoon');
                $other = $('#other');
                $("#arrangequiet").val($quiet.hasClass('selected') ? '1' : '');

                $("#arrangehigh").val($high.hasClass('selected') ? '1' : '');
                $("#arrangehoneymoon").val($honeymoon.hasClass('selected') ? '1' : '');
                $("#arrangeother").val($other.val());

                if ($quiet.hasClass('selected') || $high.hasClass('selected') || $honeymoon.hasClass('selected') || $other.val().length > 0) {
                    $("#btnPreference .note").text('已选择');
                } else {
                    $("#btnPreference .note").text('请选择偏好');
                }

                $(_vars.target.reserve.divPreference).hide();
                $(_vars.target.reserve.divOrder).show();
            });


            $(".agree .Cicon.checkbox_small").on('click', function () {
                var $this = $(this);
                if ($this.hasClass('checked')) {
                    $this.removeClass('checked');
                } else {
                    $this.addClass('checked');
                }
            });

            $("[name=GuaranteeCardType]").on("change", function () {
                var $self, option;
                $self = $(this);

                if (this.selectedOptions) {
                    option = this.selectedOptions[0];
                } else if (this.options) {
                    option = this.options[this.selectedIndex];
                }
                if (option) {
                    $self.siblings('div.text').text($(option).text());
                }
            });

            $(".GM_card .item i.date.question_small").on('click', function () {
                var $block = $('.showcard');
                if ($block.hasClass('Ldn')) {
                    $block.removeClass('Ldn');
                }
            });

            $('.Cpopup').on('click', function () {
                $('.showcard').addClass('Ldn');
            });


            $("#btnGuaranteeSubmit").on('click', function () {
                var $cardtype, $card, $date, $agree;
                $card = $("[name='GuaranteeCardNo']");
                $date = $("[name='GuaranteeDate']");
                $agree = $(".agree i.Cicon");
                $cardtype = $("[name='GuaranteeCardType']");
                if ($card.val() === '') {
                    _func.showMessage('请填写信用卡号!');
                    return false;
                }
                if (!isValidCard($card.val())) {
                    _func.showMessage('请输入正确的信用卡号!');
                    return false;
                }
                var date = $date.val();
                if (date === '') {
                    _func.showMessage("请填写有效期");
                    return false;
                }
                var reg = new RegExp('^(0?[1-9]|^1[0-2])/[0-9]{1,2}$');;
                if (!reg.test(date)) {
                    _func.showMessage("请输入正确的日期格式");
                    return false;
                }
                if ($agree && $agree.length && !$agree.hasClass('checked')) {
                    _func.showMessage("尚未同意销售及取消策略");
                    return false;
                }
                $("#divGuarantee").hide();
                $(_vars.target.reserve.divOrder).show();

                $(_vars.target.reserve.btnSubmit).data('guarantee', false);
                $(_vars.target.reserve.btnSubmit).ctrigger('click');
            });

            $('.Cpopup.Guarantee a').first().on('click', function () {
                $("[name='GuaranteeCardNo']").val('');
                $("[name='GuaranteeDate']").val('');
                $('#ct').val('');
                $('#cn').val('');
                $('#cd').val('');
                $('.Cpopup.Guarantee').addClass('Ldn')
                $(_vars.target.reserve.btnSubmit).data('guarantee', false);
                $(_vars.target.reserve.btnSubmit).ctrigger('click');
            });
            $('.Cpopup.Guarantee a').last().on('click', function () {
                $(_vars.target.reserve.divOrder).hide();
                $("#divGuarantee").show();
                $('.Cpopup.Guarantee').addClass('Ldn')
            });

            //提交订单
            $(_vars.target.reserve.btnSubmit).on("click", function () {
                $(".errorS").hide();
                var $this = $(this);
                //防止重复提交
                if ($this.text() === "提交中...") {
                    return;
                }
                var name = $.trim($(_vars.target.reserve.txtName).val());
                if (!name) {
                    _func.alert("请输入联系人姓名!");
                    return false;
                }
                var regex = /^[A-Za-z]+(\s+[A-Za-z]+)?$/;
                if (!regex.test(name)) {
                    _func.alert("请填写正确姓名（拼音姓 拼音名），需与所持证件一致");
                    return false;
                }
                var mobile = $.trim($(_vars.target.reserve.txtMobile).val());
                //验证手机号
                if (!_func.isCellPhone(mobile)) {
                    _func.alert("请输入正确的手机号!");
                    return false;
                }

                //校验积分换免房的情况下手机验证码是否为空
                var $smsCode = $("input[name=SmsCode]");
                if ($smsCode.length > 0 && $smsCode.val() == '') {
                    _func.showMessage('手机验证码不能为空!');
                    return false;
                }
                var $imgCode = $(".roominfo .imgcode");
                if ($imgCode.hasClass('show') && $imgCode.length > 0 && $imgCode.find('[name=imgCode]').val() === '') {
                    _func.showMessage('图形验证码不能为空!');
                    return false;
                }
                var emailreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                if (!emailreg.test($('#txtEmail').val())) {
                    _func.alert("请输入正确的Email!");
                    return false;
                }

                if ($this.data('guarantee') === true) {
                    if (_vars.guarantee === '3') {
                        $('.Cpopup.Guarantee').removeClass('Ldn');
                    } else {
                        $(_vars.target.reserve.divOrder).hide();
                        $("#divGuarantee").show();
                    }
                    return false;
                }
                $(this).text("提交中...");
                $(this).addClass("gray");
                _n.hook('checkPostUrl');
                var form = $("#reserveForm");
                var cardtype = $("[name='GuaranteeCardType']").val();
                var card = $("[name='GuaranteeCardNo']").val();
                var date = $("[name='GuaranteeDate']").val();

                var f = function () {
                    if (card && card != '') {
                        $('#ct').val(encryptedString(_vars.rsakey, cardtype + "#" + _vars.timestamp));
                        $('#cn').val(encryptedString(_vars.rsakey, card + "#" + _vars.timestamp));
                        $('#cd').val(encryptedString(_vars.rsakey, date + "#" + _vars.timestamp));
                        //                        $('#cp').val(encryptedString(_vars.rsakey, name + "#" + _vars.timestamp));
                    }
                    $.post(form.attr("action"), form.serialize(), function (result) {
                        if (result.isSuccess === "1") {
                            location.href = result.url;
                        } else {
                            _func.alert(result.message);
                            if ($(_vars.target.reserve.btnSubmit).data('guarantee') != undefined) {
                                $(_vars.target.reserve.btnSubmit).data('guarantee', true);
                            }

                            $imgCode.removeClass('Ldn').removeClass('notshow').addClass('show');
                            if ($("#imgCaptcha")) {
                                $("#imgCaptcha").attr('src', _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                            }
                            //                        }
                            $(_vars.target.reserve.btnSubmit).text("提交订单");
                            $(_vars.target.reserve.btnSubmit).removeClass("gray");
                        }
                    });
                };
                if (card && card != '') {
                    _n.func.prePostAsync(f);
                } else {
                    f();
                }
            });


            var isValidCard = function (sCardNum) {

                var iOddSum = 0;
                var iEvenSum = 0;
                var bIsOdd = true;
                if (sCardNum.length != 16) {
                    return false;
                }
                for (var i = sCardNum.length - 1; i >= 0; i--) {
                    var iNum = parseInt(sCardNum.charAt(i));
                    if (bIsOdd) {
                        iOddSum += iNum;
                    } else {
                        iNum = iNum * 2;
                        if (iNum > 9) {
                            iNum = eval(iNum.toString().split("").join("+"));
                        }
                        iEvenSum += iNum;
                    }
                    bIsOdd = !bIsOdd;
                }

                return ((iEvenSum + iOddSum) % 10 == 0);
            }
        },

        plusindexAction: function () {
           
            //优惠券
            $(_vars.target.reserve.btnCoupon).ctap(function () {
                if (!_func.reserveLogin()) {
                    return;
                }
                if ($(_vars.target.reserve.selBookCount).data("day") > 1) {
                    if (!confirm("使用优惠券，限订1间房，是否继续？")) {
                        return;
                    }
                }
                $(".errorS").hide();
                _n.hook('loading');

                if (!$(_vars.target.reserve.divCoupon).html().length) {
                    var param = { "checkInDate": $(this).data("date"), "price": $(this).data("price"), "hotelId": $(this).data("hotelid") };
                    $(_vars.target.reserve.divCoupon).load(_vars.couponurl, param, function (result, status, xhr) {
                        if (xhr.status !== 200) {
                            _func.alert(result || "优惠券查询错误，请重试！");
                            $(_vars.target.reserve.divCoupon).empty();
                        } else {
                            $(_vars.target.reserve.divOrder).hide();
                            $(_vars.target.reserve.divCoupon).show();
                            for (var key in _vars.promotionCodes) {
                                _vars.promotionList["pid" + _vars.promotionCodes[key].ProjectID] = _vars.promotionCodes[key].EcouponTickNo;
                            };
                            for (var key in _vars.promotionUseList) {
                                var index = _vars.promotionList["pid" + _vars.promotionUseList[key].pid].indexOf(_vars.promotionUseList[key].code);
                                _vars.promotionList["pid" + _vars.promotionUseList[key].pid].splice(index, 1);
                            }

                            _vars.lastSubmitPromotionUseList = {};
                            $.extend(_vars.lastSubmitPromotionUseList, _vars.promotionUseList);
                            _vars.lastSubmitPromotionList = _func.syncPromotionList(_vars.promotionList);
                            var usedPromo = _vars.promotionUseList[$("#hidCouponCheckIn").val()];

                            //更新可用优惠券数量
                            for (var key in _vars.promotionList) {
                                var coupon = $('.coupons tr[data-pid="' + key.substr(3) + '"][data-date="' + $("#hidCouponCheckIn").val() + '"]');
                                var num = _vars.promotionList[key].length;

                                if (usedPromo && usedPromo.pid == key.substr(3)) {
                                    num++;
                                    coupon.find("i").addClass("selected");
                                }

                                if (num) {
                                    coupon.find('text').text(num);
                                    coupon.show();
                                } else {
                                    coupon.hide();
                                }
                            }

                            //使用了未在优惠券列表的优惠券
                            if (usedPromo && !_vars.promotionList["pid" + usedPromo.pid]) {
                                $("#txtTicket").val(usedPromo.code);
                                $("#btnCheck").text("取消使用");
                            }
                            var $days = $(".dayselect .item");
                            $days.each(function () {
                                try {
                                    $(this).find("span").text("已使用" + _vars.promotionUseList[$(this).data('date')].value + "元");
                                } catch (e) {

                                }
                            });

                            _vars.lastSubmitCouponHtml = $(_vars.target.reserve.divCoupon).html();
                        }

                        _n.hook('hideloading');
                        ndoo.hook('dayctrl');
                    });
                } else {
                    $(_vars.target.reserve.divCoupon).html(_vars.lastSubmitCouponHtml);
                    _vars.promotionUseList = new Array();
                    $.extend(_vars.promotionUseList, _vars.lastSubmitPromotionUseList);
                    _vars.promotionList = _func.syncPromotionList(_vars.lastSubmitPromotionList);
                    $(_vars.target.reserve.divOrder).hide();
                    $(_vars.target.reserve.divCoupon).show();
                    _n.hook('hideloading');
                    ndoo.hook('dayctrl');
                }
            });

            $(".Cpopup.confirm .link").on('click', function () {
                $(this).parents('.Cpopup.confirm').addClass('Ldn');
            });

            $(".Cpopup.confirm .back").on('click', function () {
                $(this).parents('.Cpopup.confirm').addClass('Ldn');
                N.vars.needasynname = "0";
                $(_vars.target.reserve.btnSubmit).ctrigger('click');
            });

          

            //提交优惠券
            _func.submitCounpon = function () {
                $(_vars.target.reserve.divOrder).show();
                $(_vars.target.reserve.divCoupon).hide();
                //多间情况下清空早餐券
                if ($(_vars.target.reserve.selBookCount).val() > 1) {
                    clearBreakfast();
                    $(".iscroll_wrapper .breakfast.body").remove();
                    $(".iscroll_wrapper .breakfast").addClass('Ldn');
                }
                var totalAmount = parseFloat(_vars.totalAmount);
                var breakfast = $("#AllBreakfast").val(),
                    breakfastType = $("#BreakfastType").val(),
                    breakfastMoney = $("#divBreakfast").data('breakfastamount'),
                    otherNum = $("#BreakfastCount").val();

                var tempprice = '';
                if (parseFloat(breakfast) > 0) {
                    tempprice += '(含早)';
                    if (parseFloat(otherNum) > 0 && breakfastType === 'money') {
                        totalAmount += breakfastMoney * otherNum;
                    }
                }
                var promotionArray = [];
                var totalDiscount = 0;
                for (var key in _vars.promotionUseList) {
                    var discount = _vars.promotionUseList[key].value;

                    //promotionArray.push(key + "," + _vars.promotionUseList[key].code);
                    //promotionArray.push(key + "|" + _vars.promotionUseList[key].code);
                    promotionArray.push(_vars.promotionUseList[key].code + "|" + key);
                    totalDiscount += parseFloat(discount);
                }

                $(_vars.target.reserve.divPrice + " .body").each(function () {
                    $(this).find(".amount").text(_vars.currencyCode + "" + $(this).data("amount"));
                    //$(this).find(".discount").text("");
                    $(this).data('discount', 0);
                });
                
                if (totalDiscount > 0) {
                    //设置优惠券
                    //_vars.emptyText = $(_vars.target.reserve.btnCoupon + " .note").text();
                    $(_vars.target.reserve.btnCoupon + " .note").text("已优惠" + totalDiscount.toString() + "元").data('value', totalDiscount);
                    $(_vars.target.reserve.hidCoupon).val(promotionArray.join(','));
                    //设置优惠券减免价格
                    $(_vars.target.reserve.btnCoupon).data('discount', totalDiscount);
                    //限制房间数
                    $(_vars.target.reserve.divRoomCount).text("1间");
                    $(_vars.target.reserve.selBookCount).val(1);
                    $(_vars.target.reserve.selBookCount).data('day', 1);
                    $(_vars.target.reserve.selBookCount).data('room', 1);
                    //                    $(".roomctrl span").html('1');
                    //房间数设为1
                    //                    $(".roomctrl i.sub").removeClass('numctrl');
                    //                    $(_vars.target.reserve.selBookCount).addClass("Ldn");

                    var html = "<i>" + _vars.currencyCode + "</i>" + totalAmount.toString() + "-" + totalDiscount.toString() + "=" + _vars.currencyCode + parseFloat(totalAmount - totalDiscount).toFixed(2).toString() + tempprice;
                    $(_vars.target.reserve.divPrice + " .body").each(function () {
                        $(this).find(".amount").text(_vars.currencyCode + parseFloat($(this).data("amount")).toString());
                    });
                    $(_vars.target.reserve.divPrice + " .price").html(html);
                    $(_vars.target.reserve.divTotalPrice + " .price").html("<i>" + _vars.currencyCode + "</i>" + parseFloat(totalAmount - totalDiscount).toFixed(2).toString() + tempprice);
                    $(_vars.target.reserve.divTotalPrice + " p.favorable").remove();
                    $(_vars.target.reserve.divTotalPrice).append('<p class="favorable">已优惠' + totalDiscount + '元</p>');
                    $(_vars.target.reserve.divTotalPrice).addClass('reset_top');
                    $(_vars.target.reserve.divPrice + " .count").text("共优惠" + _vars.currencyCode + totalDiscount.toString());

                    for (var key in _vars.promotionUseList) {

                        var date = $(_vars.target.reserve.divPrice + ' div[data-date="' + key + '"]');
                        var discount = _vars.promotionUseList[key].value;

                        date.find(".amount").text(_vars.currencyCode + "" + (parseFloat(date.data("amount")) - parseFloat(discount)).toFixed(2).toString());
                        date.find(".discount").text(_vars.currencyCode + "" + discount);
                        date.data('discount', discount);
                    }
                    $("#btnCoupon").addClass("showclear");
                } else {
                    $(_vars.target.reserve.btnCoupon + " .note").text('使用优惠券抵扣部分房费').data('value', 0);
                    $(_vars.target.reserve.hidCoupon).val("");
                    //                    $(_vars.target.reserve.selBookCount).removeClass("Ldn");
                    $(_vars.target.reserve.btnCoupon).data('discount', 0);
                    $(_vars.target.reserve.divPrice + " .price").html("<i>" + _vars.currencyCode + "</i>" + parseFloat(totalAmount - totalDiscount).toFixed(2));
                    $(_vars.target.reserve.divTotalPrice + " .price").html("<i>" + _vars.currencyCode + "</i>" + totalAmount.toString() + tempprice);
                    $(_vars.target.reserve.divTotalPrice + ' p.favorable').remove();
                    $(_vars.target.reserve.divTotalPrice).removeClass('reset_top');
                    $(_vars.target.reserve.divPrice + " .count").text("共优惠" + _vars.currencyCode + "0");
                    $("#btnCoupon").removeClass("showclear");
                }
                //                roomChangeInitBreakfast($(_vars.target.reserve.selBookCount).val());
            };
           
            //唤起常住人
            $(".choose-user-info .Cicon").on("click", function () {
                var $this = $(this);
                if (_vars.reserveIsLogin != "1") {
                    N.visit(_vars.reserveLoginUrl);
                } else {
                    $("#divUsualList").show();
                }
            });
            //选择常住人
            $('.quickorder.INV_info_popup .content').on('click', '.room', function () {
                var $this = $(this).find('i.small');
                var selcted = $this.hasClass('selected');
                $('.quickorder.INV_info_popup .room i.small').removeClass('selected');
                $('.quickorder.INV_info_popup .room .roomtype').removeClass('selected');
                if (!selcted) {
                    $this.addClass('selected');
                    $this.siblings('.roomtype').addClass('selected');
                }
            });
            //确定选择常住人
            $(".quickorder.INV_info_popup .std_large_button").on('click', function () {
                $("#divUsualList").hide();
                $('.fcWrap').css({
                    height: '200px',
                    overflow: 'hidden'
                });
                var i = $('.quickorder.INV_info_popup .content .room i.small.selected');
                if (i&&i.data('id')) {
                    $("#txtMobile").val($("i.small.selected").data("mobile"));
                    $("#txtEmail").val($("i.small.selected").data("email"));
                }
            });

            $("[name=GuaranteeCardType]").on("change", function () {
                var $self, option;
                $self = $(this);

                if (this.selectedOptions) {
                    option = this.selectedOptions[0];
                } else if (this.options) {
                    option = this.options[this.selectedIndex];
                }
                if (option) {
                    $self.siblings('div.text').text($(option).text());
                }
            });

            $(".GM_card .item i.date.question_small").on('click', function () {
                var $block = $('.showcard');
                if ($block.hasClass('Ldn')) {
                    $block.removeClass('Ldn');
                }
            });

            $(".agree i.Cicon").on('click', function () {
                if ($(this).hasClass('checked')) {
                    $(this).removeClass('checked');
                } else {
                    $(this).addClass('checked');
                }
            });
            //提交订单
            $(_vars.target.reserve.btnSubmit).on("click", function () {
                $(".errorS").hide();
                var $this = $(this);
                var thistxt = $this.text();
                //防止重复提交
                if ($this.text() === "提交中...") {
                    return;
                }
                //var name = $.trim($(_vars.target.reserve.txtName).val());
                var firstname = $.trim($(_vars.target.reserve.firstName).val());
                var lastname = $.trim($(_vars.target.reserve.lastName).val());
                if (!firstname) {
                    _func.alert("请输入联系人的姓");
                    return false;
                }
                if (!lastname) {
                    _func.alert("请输入联系人的名");
                    return false;
                }
                //if (!name) {
                //    _func.alert("请输入联系人姓名!");
                //    return false;
                //}
                //var regex = /^[A-Za-z]+(\s+[A-Za-z]+)?$/;
                //if (!regex.test(name)) {
                //    _func.alert("请填写正确姓名（拼音姓 拼音名），需与所持证件一致");
                //    return false;
                //}
                var mobile = $.trim($(_vars.target.reserve.txtMobile).val());
                //验证手机号
                if (!_func.isCellPhone(mobile)) {
                    _func.alert("请输入正确的手机号!");
                    return false;
                }

                //校验积分换免房的情况下手机验证码是否为空
                var $smsCode = $("input[name=SmsCode]");
                if ($smsCode.length > 0 && $smsCode.val() == '') {
                    _func.showMessage('手机验证码不能为空!');
                    return false;
                }
                var $imgCode = $(".roominfo .imgcode");
                if ($imgCode.hasClass('show') && $imgCode.length > 0 && $imgCode.find('[name=imgCode]').val() === '') {
                    _func.showMessage('图形验证码不能为空!');
                    return false;
                }
                var emailreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                if (!emailreg.test($('#txtEmail').val())) {
                    _func.alert("请输入正确的Email!");
                    return false;
                }

                //if ($this.data('guarantee') === true) {
                //    if (_vars.guarantee === '3') {
                //        $('.Cpopup.Guarantee').removeClass('Ldn');
                //    } else {
                //        $(_vars.target.reserve.divOrder).hide();
                //        $("#divGuarantee").show();
                //    }
                //    return false;
                //}
                if (_vars.guarantee == '1') {
                    var $cardtype, $card, $date, $agree;
                    $card = $("[name='GuaranteeCardNo']");
                    $date = $("[name='GuaranteeDate']");
                    $agree = $(".agree i.Cicon");
                    $cardtype = $("[name='GuaranteeCardType']");
                    if ($card.val() === '') {
                        _func.showMessage('请填写信用卡号!');
                        return false;
                    }
                    if (!isValidCard($card.val())) {
                        _func.showMessage('请输入正确的信用卡号!');
                        return false;
                    }
                    var date = $date.val();
                    if (date === '') {
                        _func.showMessage("请填写有效期");
                        return false;
                    }
                    var reg = new RegExp('^(0?[1-9]|^1[0-2])/[0-9]{1,2}$');;
                    if (!reg.test(date)) {
                        _func.showMessage("请输入正确的日期格式");
                        return false;
                    }
                }
                if (_vars.guarantee == '3') {
                    var $cardtype, $card, $date, $agree;
                    $card = $("[name='GuaranteeCardNo']");
                    $date = $("[name='GuaranteeDate']");
                    $agree = $(".agree i.Cicon");
                    $cardtype = $("[name='GuaranteeCardType']");
                    if ($card.val()!=''&&!isValidCard($card.val())) {
                        _func.showMessage('请输入正确的信用卡号!');
                        return false;
                    }
                    var date = $date.val();
                    var reg = new RegExp('^(0?[1-9]|^1[0-2])/[0-9]{1,2}$');;
                    if (date!=''&&!reg.test(date)) {
                        _func.showMessage("请输入正确的日期格式");
                        return false;
                    }
                    if (data != '' && $card.val() == '') {
                        _func.showMessage('请填写信用卡号!');
                        return false;
                    }
                    if (data == '' && $card.val() != '') {
                        _func.showMessage('请填写有效期!');
                        return false;
                    }
                }
                var agree = $(".GM_info .agree .checkbox_small");
                if (!agree.hasClass('checked')) {
                        _func.showMessage("尚未同意销售及取消政策");
                        return false;
                }
                $(this).text("提交中...");
                $(this).addClass("gray");
                _n.hook('checkPostUrl');
                var form = $("#reserveForm");
                var cardtype = $("[name='GuaranteeCardType']").val();
                var card = $("[name='GuaranteeCardNo']").val();
                var date = $("[name='GuaranteeDate']").val();

                var f = function () {
                    if (card && card != '') {
                        $('#ct').val(encryptedString(_vars.rsakey, cardtype + "#" + _vars.timestamp));
                        $('#cn').val(encryptedString(_vars.rsakey, card + "#" + _vars.timestamp));
                        $('#cd').val(encryptedString(_vars.rsakey, date + "#" + _vars.timestamp));
                        //                        $('#cp').val(encryptedString(_vars.rsakey, name + "#" + _vars.timestamp));
                    }
                    $.post(form.attr("action"), form.serialize(), function (result) {
                        if (result.isSuccess === "1") {
                            //_func.showMessage(result.message);
                            if (result.url != "") {
                                location.href = result.url;
                            } else {
                                $("body").append(result.form);
                                $('#huazhuPayForm').submit();
                            }
                        } else {
                            _func.alert(result.message);
                            if ($(_vars.target.reserve.btnSubmit).data('guarantee') != undefined) {
                                $(_vars.target.reserve.btnSubmit).data('guarantee', true);
                            }

                            $imgCode.removeClass('Ldn').removeClass('notshow').addClass('show');
                            if ($("#imgCaptcha")) {
                                $("#imgCaptcha").attr('src', _vars.ImgCaptchaCodeSrc + '?date=' + new Date().getTime());
                            }
                            //                        }
                            $(_vars.target.reserve.btnSubmit).text(thistxt);
                            $(_vars.target.reserve.btnSubmit).removeClass("gray");
                        }
                    });
                };
                if (card && card != '') {
                    _n.func.prePostAsync(f);
                } else {
                    f();
                }
            });

            //关闭短信验证码窗口
            $(".smsverify .closecontent").on('click', function () {
                $(".smsverify").addClass('Ldn');
                _vars.errorDiv = $(".HO_main .errorS");
                $(".smsverify .error").html('').hide();
                $('[name=ActivePoint]').val('1');
            });

            //发送手机验证码
            $(".smsverify .send").on('click', function () {
                var $btnSms = $(this);
                _vars.errorDiv = $(".smsverify .error");
                _vars.url.sendSms = _vars.ServerHttp + "Account/SendSms/";
                _vars.SendSms.init($btnSms, { "CaptchaType": "MixPay" }, undefined, { clear: "获取验证码" });
            });


            //提交支付    第三方情况支付不为微信   
            $(".smsverify .confirm").on('click', function () {
                var $this = $(this);
                if ($this.hasClass('gray')) {
                    return false;
                }
                _func.clearMessage();
                var $thirdPay = $("#divPayType a .Cradiobox.selected");
                if ($thirdPay.length) {
                    var $selected = $($thirdPay[0]);
                    if ($selected.parents('a').data('type') === 'wechat') {
                        return false;
                    }
                }
                if (!$("#smsCode").val()) {
                    alert("验证码不能为空!");
                    return false;
                }
                $this.addClass('gray');
                $('[name=ActivePoint]').val('0');
                if ($('[name=SmsCode]').length <= 0) {
                    $("#reserveForm").append($('<input type="hidden" name="SmsCode" value="' + $("#smsCode").val() + '" />'));
                } else {
                    $('[name=SmsCode]').val($("#smsCode").val());
                }

                $(_vars.target.reserve.btnSubmit).ctrigger('click');
            });

            var isValidCard = function (sCardNum) {

                var iOddSum = 0;
                var iEvenSum = 0;
                var bIsOdd = true;
                if (sCardNum.length != 16) {
                    return false;
                }
                for (var i = sCardNum.length - 1; i >= 0; i--) {
                    var iNum = parseInt(sCardNum.charAt(i));
                    if (bIsOdd) {
                        iOddSum += iNum;
                    } else {
                        iNum = iNum * 2;
                        if (iNum > 9) {
                            iNum = eval(iNum.toString().split("").join("+"));
                        }
                        iEvenSum += iNum;
                    }
                    bIsOdd = !bIsOdd;
                }

                return ((iEvenSum + iOddSum) % 10 == 0);
            }
        }
    });

    _n.app('payment', {
        init: function () {
            _vars.target.payment = {
                divWechatPay: "#divWechatPay",
                divSendMsg: "#divSendMsg",
                divCardTotal: "#divCardTotal",
                divCardFirstNight: "#divCardFirstNight",
                divCountdown: "#divCountdown",
                txtCaptcha: "#txtCaptcha",
                btnCaptcha: "#btnCaptcha",
                btnPay: "#btnPay"
            };

            _n.func.updateQueryStringParameter = function (uri, data) {
                $.map(data, function (item, index) {
                    var re = new RegExp("([?&])" + index + "=.*?(&|$)", "i");
                    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
                    if (uri.match(re)) {
                        uri = uri.replace(re, '$1' + index + "=" + item + '$2');
                    } else {
                        uri = uri + separator + index + "=" + item;
                    }

                });
                return uri;
            };
        },

        indexAction: function () {

            //支付方法选择(首夜还是全额)
            $("#divPayWay .bgNight").ctap(function () {
                var arrow = $("#" + $(this).data("arrow"));
                $(this).siblings().removeClass("textFormBlue");
                $(this).addClass("textFormBlue");
                arrow.siblings().removeClass("selectFirst");
                arrow.addClass("selectFirst");

                //储值卡首夜支付全额支付显示状态区分
                if ($(this).data("payway") === "PayFirstNight") {
                    $(_vars.target.payment.divCardFirstNight).show();
                    $(_vars.target.payment.divCardTotal).hide();
                } else {
                    $(_vars.target.payment.divCardFirstNight).hide();
                    $(_vars.target.payment.divCardTotal).show();
                }
            });
            //支付方式选择(储值卡,支付宝)
            $("#divPayType a").on("click", function () {
                var payurl = $(this).data("href").replace("_ResPayWay_", $("div .textFormBlue").data("payway"));
                location.href = payurl;
            });
        },
        indexmixAction: function () {
            _vars.errorDiv = $(".smsverify .error");

            _func.addPageLoadCall(function () {
                if (_n.func.isInWenXin()) {
                    $("#divWechat").find('span.Cradiobox').first().addClass('selected');
                } else {
                    $("#divPayType a span.Cradiobox").first().addClass('selected');
                }
                $(".bgNight").first().ctrigger('click');
            });
            $(".PL_hzmethod .pitem .Cswitch").not('.not').on('click', function () {
                var $this = $(this), type, $payAll, remain;
                type = $this.parents('.pitem').data('type');
                $payAll = $("#divTotalPrice .price");
                remain = Number($payAll.data('money'));
                if ($this.hasClass('enable')) {
                    $this.removeClass('enable');
                    if (type == 'wallet' || type == 'card') {
                        $this.parents('.pitem').find('.msg').html('');
                    }
                } else {
                    if ((type == 'wallet' || type == 'card') && remain <= 0) {
                        return false;
                    }
                    $this.addClass('enable');
                }
                _n.hook('setMoney');
                _func.setMsg();
            });


            //支付方法选择(首夜还是全额)
            $(".bgNight").ctap(function () {
                var arrow = $("#" + $(this).data("arrow"));
                $(this).siblings().removeClass("textFormBlue");
                $(this).addClass("textFormBlue");
                arrow.siblings().removeClass("selectFirst");
                arrow.addClass("selectFirst");

                //储值卡首夜支付全额支付显示状态区分
                if ($(this).data("payway") === "PayFirstNight") {
                    $(_vars.target.payment.divCardFirstNight).show();
                    $(_vars.target.payment.divCardTotal).hide();

                    $(".PL_hzmethod .payfirst").removeClass('Ldn');
                    $(".PL_hzmethod .payall").addClass('Ldn');
                } else {
                    $(_vars.target.payment.divCardFirstNight).hide();
                    $(_vars.target.payment.divCardTotal).show();
                    $(".PL_hzmethod .payall").removeClass('Ldn');
                    $(".PL_hzmethod .payfirst").addClass('Ldn');
                }
                $("#divTotalPrice .price").html("<i>￥</i>" + $(this).data('money')).data('totalmoney', $(this).data('money'));
                _n.hook('setMoney');

            });

            //支付方式选择
            $("#divPayType a").on("click", function () {
                var
                    $this = $(this),
                    remain = Number($("#divTotalPrice .price").data('money'));
                //                location.href = payurl;
                if ($this.find('span.Cradiobox').hasClass('selected')) {
                    cancelThirdPay();
                } else {
                    cancelThirdPay();
                    if (remain > 0) {
                        $this.find('span.Cradiobox').addClass('selected');
                    }

                }
            });

            //关闭短信验证码窗口
            $(".smsverify .closecontent").on('click', function () {
                $(".smsverify").addClass('Ldn');
            });
            //验证码输入文本框 切换提交显示样式
            $(".inputbox input").on('input', function () {
                var $button = $(".smsverify button.confirm");
                if ($(this).val()) {
                    $button.removeClass('gray');
                } else {
                    $button.addClass('gray');
                }
            });

            //发送手机验证码
            $("#btnCheck").on('click', function () {
                var $btnSms = $(this);
                _func.clearMessage();
                _vars.errorDiv = $(".error");
                _vars.url.sendSms = _vars.ServerHttp + "Account/SendSms/";
                _vars.SendSms.init($btnSms, { "CaptchaType": "MixPay" }, undefined, { clear: "获取验证码" });
            });


            //提交支付    第三方情况支付不为微信
            $(".smsverify .confirm").on('click', function () {
                var $this = $(this);
                if ($this.hasClass('gray')) {
                    return false;
                }
                _func.clearMessage();
                var $thirdPay = $("#divPayType a .Cradiobox.selected");
                if ($thirdPay.length) {
                    var $selected = $($thirdPay[0]);
                    if ($selected.parents('a').data('type') === 'wechat') {
                        return false;
                    }
                }
                if (!$("#smsCode").val()) {
                    alert("验证码不能为空!");
                    return false;
                }
                $this.addClass('gray');
                _n.func.prePostAsync(function () {
                    var smsCode = encryptedString(_vars.rsakey, $("#smsCode").val() + "#" + _vars.timestamp);
                    var url = _func.confirmPay(smsCode);
                    $.get(url, null, function (result) {
                        if (result.indexOf('SMSError') >= 0) {
                            _func.showMessage('短信验证码错误!');
                            $this.removeClass('gray');
                            return false;
                        } else {
                            wa_track_event("确认支付", _vars.wa_submit_str, _vars.order_hotel_id + '_' + wa_store_name);
                            location.href = result;
                        }
                    });
                });

            });
            //确认支付
            $("#btnSubmit").on('click', function () {
                var $this = $(this),
                    $thirdPaySelected = $("#divPayType a span.Cradiobox.selected");
                if (_n.func.isInWenXin() && $thirdPaySelected.length && $($thirdPaySelected[0]).parents('a').data('type') == 'wechat') {
                    return false;
                }
                if ($this.hasClass('gray')) {
                    return false;
                }
                var $smsVerify = $(".smsverify"),

                    $hzPaySelected = $(".PL_hzmethod .pitem").not('.Ldn').find(".Cswitch.enable"),
                    remain = Number($("#divTotalPrice .price").data('money'));

                if (!$thirdPaySelected.length && !$hzPaySelected.length) {
                    alert("请选择支付方式!");
                    return false;
                }
                if (!$thirdPaySelected.length && remain > 0) {
                    alert("请选择正确支付方式");
                    return false;
                }
                if (!$hzPaySelected.length) {
                    $this.addClass('gray');
                    $.get(_func.confirmPay(), null, function (result) {
                        wa_track_event("确认支付", _vars.wa_submit_str, _vars.order_hotel_id + '_' + wa_store_name);
                        location.href = result;
                    });
                } else {
                    if ($smsVerify.hasClass('Ldn')) {
                        $smsVerify.removeClass('Ldn');
                        $("#btnCheck").ctrigger('click');
                    }
                }

            });
            var cancelThirdPay = function () {
                $('#divPayType a span.Cradiobox').removeClass('selected');
            };
            _n.func.confirmPay = function (smsCode) {
                var $thirdPay = $("#divPayType a .Cradiobox.selected"),
                    data = {},
                    payurl,
                    remain = Number($("#divTotalPrice .price").data('totalmoney'));
                //GA统计时提交数据
                _vars.wa_submit_str = '';
                if ($thirdPay.length) {
                    var $selected = $($thirdPay[0]);
                    payurl = $selected.parents('a').data("href").replace("_ResPayWay_", $("div .textFormBlue").data("payway"));
                    var thirdMoney = Number($("#divTotalPrice").find('.price').data('money'));
                    if (thirdMoney > 0) {
                        data['thirdPay'] = $selected.parents('a').data('type');
                        data['thirdPayMoney'] = thirdMoney;
                        _vars.wa_submit_str += $selected.parents('a').data('name') + ':' + thirdMoney + '_';
                    }
                } else {
                    payurl = $("#btnSubmit").data("url").replace("_ResPayWay_", $("div .textFormBlue").data("payway"));
                }
                var $point = $('.PL_hzmethod .pitem.point').not('.Ldn').find('.Cswitch.enable');
                if ($point.length) {
                    data['point'] = $($point[0]).data('money');
                    remain = remain - Number(data['point']);
                    _vars.wa_submit_str += '积分' + ':' + data['point'] + '_';
                } else {
                    if (N.vars.freezePoint && N.vars.freezePoint['point']) {
                        data['point'] = N.vars.freezePoint['point'];
                    }
                }
                var $wallet = $('.PL_hzmethod .pitem.wallet').not('.Ldn').find('.Cswitch.enable');
                if ($wallet.length) {
                    var wallet = Number($($wallet[0]).data('money'));
                    data['wallet'] = wallet > remain ? remain : wallet;
                    remain = remain - data['wallet'];
                    _vars.wa_submit_str += '红包' + ':' + data['wallet'] + '_';
                } else {
                    if (N.vars.freezeWallet && N.vars.freezeWallet['wallet']) {
                        data['wallet'] = N.vars.freezeWallet['wallet'];
                    }
                }
                var $card = $('.PL_hzmethod .pitem.card').not('.Ldn').find('.Cswitch.enable');
                if ($card.length) {
                    var money = Number($($card[0]).data('money'));
                    data['card'] = money > remain ? remain : money;
                    _vars.wa_submit_str += '储值卡' + ':' + data['card'] + '_';
                } else {
                    if (N.vars.freezeMoney && N.vars.freezeMoney['money']) {
                        var card = Number(N.vars.freezeMoney['money']);
                        data['card'] = card;
                    }
                }
                if (smsCode) {
                    data['SmsCode'] = smsCode;
                }
                payurl = _n.func.updateQueryStringParameter(payurl, data);
                if (_vars.wa_submit_str.length > 0) {
                    _vars.wa_submit_str = _vars.wa_submit_str.substr(0, _vars.wa_submit_str.length - 1);
                }
                return payurl;
            };
            _n.hook('setMoney', function () {
                var $hzPay = $(".PL_hzmethod .pitem").not('.Ldn'),
                    $hzPayAll = $('.remaintip').not('.Ldn'),
                    $payAll = $("#divTotalPrice .price"),
                    totalPrice = 0,
                    AllPrice = $payAll.data('totalmoney'),
                    remain = 0;
                $.map($hzPay, function (item) {
                    if ($(item).find('.Cswitch').hasClass('enable')) {
                        totalPrice += Number($(item).find('.Cswitch').data('money'));
                    }
                });
                remain = Number(AllPrice) - totalPrice;
                remain = remain < 0 ? 0 : remain;
                $hzPayAll.find('.price').html("<i>￥</i>" + remain);
                $payAll.data('money', remain);
                if (remain <= 0) {
                    $("#divPayType a span.Cradiobox.selected").removeClass('selected');
                }
            });
            _n.func.setMsg = function () {
                var remain;
                remain = Number($("#divTotalPrice .price").data('totalmoney'));
                var $point = $('.PL_hzmethod .pitem.point').not('.Ldn').find('.Cswitch.enable');
                if ($point.length) {
                    remain = remain - Number($($point[0]).data('money'));
                }
                var $wallet = $('.PL_hzmethod .pitem.wallet').not('.Ldn').find('.Cswitch.enable');
                if ($wallet.length) {
                    var wallet = Number($($wallet[0]).data('money'));
                    wallet = wallet > remain ? remain : wallet;
                    remain = remain - wallet;
                    $wallet.parents('.pitem').find('.msg').html('已用' + wallet + '元');
                }

                var $card = $('.PL_hzmethod .pitem.card').not('.Ldn').find('.Cswitch.enable');
                if ($card.length) {
                    var money = Number($($card[0]).data('money'));
                    money = money > remain ? remain : money;
                    $card.parents('.pitem').find('.msg').html('已用' + money + '元');
                }
            };
        },
        indexmixAfter: function () {
            var _down, _initCountDown;
            _down = function (call) {
                var count, m, mtext, paytime_timetoken, s, stext;
                if (_n.pageId !== 'payment/indexmix') {
                    return;
                }
                count = _stor('paytime_countdown');
                if (count > 0) {
                    m = Math.floor(count / 60);
                    s = count % 60;
                } else {
                    if (_vars.completeurl) {
                        _n.visit(_vars.completeurl);
                    }
                }
                if (m > 9) {
                    mtext = '' + m;
                } else {
                    mtext = "0" + m;
                }
                if (s > 9) {
                    stext = '' + s;
                } else {
                    stext = "0" + s;
                }
                $('.PL_paytime .chead b').text("" + mtext + ":" + stext);
                if ((--count) > 0) {
                    _stor('paytime_countdown', count, 1);
                    paytime_timetoken = setTimeout(function () {
                        call(call);
                    }, 1000);
                    _stor('paytime_timetoken', paytime_timetoken, 1);
                } else {
                    _stor('paytime_countdown', 0, 1);
                }
            };
            _func.addPageBeforeUnloadCall(function () {
                clearTimeout(_stor('paytime_timetoken'));
                return void 0;
            });
            (_initCountDown = function () {
                var $paytime, countdown;
                $paytime = $('.PL_paytime');
                if ($paytime.length) {
                    countdown = parseInt($paytime.data('countdown')) || 0;
                    if (countdown) {
                        _stor('paytime_countdown', countdown, 1);
                        _down(_down);
                    }
                }
            })();
            _func.addPageRestoreCall('timeCountDown', function () {
                return _down(_down);
            });
        },
        cardAction: function () {
            _func.addPageLoadCall(function () {
                var str = _vars.chargeRedirectUrl;
                if (str && str != '') {
                    wa_track_event("储值卡充值", str.substr(str.indexOf("amount=") + 7));
                    location.href = str.substr(0, str.indexOf("success=") - 1);
                }
            });

            //获取手机验证码
            $(_vars.target.payment.btnCaptcha).ctap(function () {
                var btn = $(this);
                if (btn.attr("disabled")) {
                    return;
                }

                btn.attr("disabled", true);

                $.post(_vars.getCaptchaUrl, { "captchaType": "UseCard" }, function (result) {
                    if (result.isSuccess === 1) {
                        $(_vars.target.payment.divSendMsg).show();
                        _vars.sendSms.init(btn);
                    } else {
                        _func.alert(result.oMessage);
                        $(_vars.target.payment.btnCaptcha).removeAttr("disabled");
                    }
                });
            });
            //验证手机号并提交支付
            $(_vars.target.payment.btnPay).ctap(function () {
                var btn = $(this);
                if (btn.attr("disabled")) {
                    return false;
                }


                if (!$(_vars.target.payment.txtCaptcha).val()) {
                    _func.alert("请输手机验证码!");
                    return false;
                }
                btn.attr("disabled", true).addClass('gray');
                $.post(_vars.payUrl + "?ResPayType=ValueCard", { "captcha": $(_vars.target.payment.txtCaptcha).val() }, function (result) {
                    if (result.isSuccess === 1) {
                        location.href = result.redirect;
                    } else {
                        _func.alert(result.oMessage);
                    }
                    btn.removeAttr("disabled").removeClass('gray');
                });
            });

            $('.RC_cT_wp a').on('click', function () {
                var $input, $this;
                $this = $(this);
                $input = $('.RC_cont_mid input');
                $input.val($this.data('amount'));
            });

            $(".RC_btm_wp a").on("click", function () {
                var payurl, data, amount;
                amount = $('.RC_cont_mid input').val();
                var re = /^[1-9]+[0-9]*]*$/;
                if (!re.test(amount)) {
                    alert("请输入正确的的充值金额(必须为整数)!");
                    return false;
                }
                payurl = $(this).data("href");
                data = {};
                data['Amount'] = amount;
                data['returnUrl'] = _n.vars.chargeReturnUrl;
                $.post(payurl, data, function (result) {
                    if (result.isSuccess != 1) {
                        alert(result.oMessage);
                        return false;
                    }
                    location.href = result.Url;
                });


            });

        },

        completeAction: function () {
            if ($(_vars.target.payment.divCountdown).text()) {
                var count = parseInt($(_vars.target.payment.divCountdown).text());
                var timer = setInterval(function () {
                    if (count > 0) {
                        $(_vars.target.payment.divCountdown).text(count--);
                    } else {
                        clearInterval(timer);
                        location.reload(true);
                    }
                }, 1000);
            }

            //支付完成分配房间
            if ($("#divAutoAssign").html()) {
                $.post(_vars.autoAssignUrl, function (result) {
                    $("#divAssignLoading").hide();
                    if (result.ResultType === 0 && result.Data.length > 0) {
                        $("#divAutoAssign .text01").text("已经为您分配房间：" + result.Data.join());
                        $("#btnCheckIn").text("已选房：" + result.Data.join());
                    } else {
                        $("#divAutoAssign .text01").text("房量不足，请稍后尝试选房！");
                    }
                });
            }

            $("#btnRight").click(function () {
                location.href = _vars.myCenterUrl;
            });

            _func.addPageLoadCall(function () {
                $.post(_vars.reserveroomUrl, { 'checkInDate': N.vars.chechinDate, 'hotelId': N.vars.hotelId }, function (data) {
                    if (data.isSuccess === 1) {
                        var hotelTotal = 0;
                        $.each(data.Data, function (index, item) {
                            if (item['HotelID'] === N.vars.hotelId) {
                                hotelTotal += item['RoomNum']
                            }
                        })
                        var room = N.vars.room
                        if (hotelTotal > 3 || room > 3) {
                            $('.policy').remove();
                        }
                    }
                })
            });
        }
    });

    _n.app('wechat', {
        init: function () {
            _vars.target.wechat = {
                divMobileError: "#divMobileError",
                divCaptchaError: "#divCaptchaError",
                txtMobile: "#txtMobile",
                txtCaptcha: "#txtCaptcha",
                btnCaptcha: "#btnCaptcha",
                btnSubmit: "#btnSubmit"
            };
        },

        authAction: function () {
            //获取手机验证码
            $(_vars.target.wechat.btnCaptcha).ctap(function () {
                $(_vars.target.wechat.divMobileError).hide();
                $(_vars.target.wechat.divCaptchaError).hide();

                //验证
                if (!_func.isCellPhone($.trim($(_vars.target.wechat.txtMobile).val()))) {
                    $(_vars.target.wechat.divMobileError).show();
                    return;
                }

                var btn = $(this);
                if (btn.attr("disabled")) {
                    return;
                }

                btn.attr("disabled", true);

                //获取公钥
                _func.prePost();

                var data = {
                    "cellPhone": encryptedString(_vars.rsakey, $.trim($(_vars.target.wechat.txtMobile).val()) + "#" + _vars.timestamp),
                    "captchaType": "WXLogin"
                };

                $.post(_vars.getCaptchaUrl, data, function (result) {
                    if (result.isSuccess === 1) {
                        _vars.sendSms.init(btn);
                    } else {
                        alert(result.oMessage);
                        $(_vars.target.wechat.btnCaptcha).removeAttr("disabled");
                    }
                });
            });

            $(_vars.target.wechat.btnSubmit).ctap(function () {
                $(_vars.target.wechat.divMobileError).hide();
                $(_vars.target.wechat.divCaptchaError).hide();

                if ($(this).text() == "关联中...") {
                    return;
                }

                if (!_func.isCellPhone($.trim($(_vars.target.wechat.txtMobile).val()))) {
                    $(_vars.target.wechat.divMobileError).show();
                    return;
                }

                if (!$(_vars.target.wechat.txtCaptcha).val()) {
                    $(_vars.target.wechat.divCaptchaError).show();
                    return;
                }

                $(this).text("关联中...");
                _n.hook('loading');

                //获取公钥
                _func.prePost();

                var data = {
                    "captcha": $.trim($(_vars.target.wechat.txtCaptcha).val()),
                    "mobile": encryptedString(_vars.rsakey, $.trim($(_vars.target.wechat.txtMobile).val()) + "#" + _vars.timestamp)
                };

                $.post(_vars.submitUrl, data, function (result) {
                    if (result.ResultType === 0) {
                        alert("关联成功");
                        if (_vars.RedirectUrl) {
                            location.href = _vars.RedirectUrl;
                        }
                    } else {
                        alert(result.Message);
                        $(_vars.target.wechat.btnSubmit).text("立即关联");
                        _n.hook('hideloading');
                    }
                });
            });
        }

    });

    _n.app('promotion', {
        init: function () {

        },
    });

    _n.app('xiaomi', {
        init: function () {
        },

        authAction: function () {
            //获取手机验证码
            $("#btnCaptcha").click(function () {

                //验证
                if (!_func.isCellPhone($.trim($("#txtMobile").val()))) {
                    alert("您输入的手机号格式不正确!");
                    return;
                }

                var btn = $(this);
                if (btn.attr("disabled")) {
                    return;
                }
                btn.attr("disabled", true);

                //获取公钥
                _func.prePost();

                var data = {
                    "cellPhone": encryptedString(_vars.rsakey, $.trim($("#txtMobile").val()) + "#" + _vars.timestamp),
                    "captchaType": "XMLogin"
                };

                $.post(_vars.getCaptchaUrl, data, function (result) {
                    if (result.isSuccess === 1) {
                        _vars.sendSms.init(btn);
                    } else {
                        alert(result.oMessage);
                        $("#btnCaptcha").removeAttr("disabled");
                    }
                });
            });

            $("#btnSubmit").click(function () {

                if ($(this).text() == "关联中...") {
                    return;
                }

                if (!_func.isCellPhone($.trim($("#txtMobile").val()))) {
                    alert("您输入的手机号格式不正确!");
                    return;
                }

                if (!$("#txtCaptcha").val()) {
                    alert("您输入的验证码不正确!");
                    return;
                }

                $(this).text("关联中...");
                _n.hook('loading');

                //获取公钥
                _func.prePost();

                var data = {
                    "captcha": $.trim($("#txtCaptcha").val()),
                    "mobile": encryptedString(_vars.rsakey, $.trim($("#txtMobile").val()) + "#" + _vars.timestamp)
                };

                $.post(_vars.submitUrl, data, function (result) {
                    if (result.ResultType === 0) {
                        alert("关联成功");
                        if (_vars.RedirectUrl) {
                            location.href = _vars.RedirectUrl;
                        }
                    } else {
                        alert(result.Message);
                        $("#btnSubmit").text("立即关联");
                        _n.hook('hideloading');
                    }
                });
            });
        }

    });

    /* }}} */
    return _n;
}).call(this.N = this.ndoo = this.ndoo || {}, Zepto);
;
/*
" --------------------------------------------------
"   FileName: app.coffee
"       Desc: app.js webapp逻辑脚本
"     Author: luchaoming
"    Version: v1.0
" LastChange: 05/20/2015 17:58
" --------------------------------------------------
*/


(function ($) {
    var _func, _n, _stor, _vars;
    _n = this;
    _vars = _n.vars;
    _func = _n.func;
    _stor = _n.storage;
    var _getcomment = true;
    
    /* 模块定义 {{{ */
    _n.app('PersonalCenter', {
        MyHotelAction: function () {
            var on = false;//on表示不可编辑状态，即取消编辑
            var arrSet = ['编辑', '取消'], count = 0;
            $(".compile").click(function () {
                if (!on) {
                    /*可编辑状态*/
                    $('.my_hotel .small').css({ display: 'inline-block' })
                    $(this).text(arrSet[1]);
                    $('.FA_main .bottom').css({ display: 'block' })
                    on = true;//重复点击开关
                } else {
                    /*不编辑状态*/
                    $('.my_hotel .small').css({ display: 'none' })
                    $(this).text(arrSet[0]);
                    $('.FA_main .bottom').css({ display: 'none' })
                    $('.my_hotel i').each(function () {
                        $(this).removeClass('selected');
                    })
                    on = false;
                    count = 0;
                    $('.delete_select em').text('(' + count + ')');
                }

                /*点击选择删除的酒店*/
                $('.my_hotel > a').each(function () {
                    $(this).click(function () {
                        if (on) {
                            var _this = $(this).children('i');
                            //console.log(_this.hasClass('selected'))
                            if (_this.hasClass('selected')) {
                                _this.removeClass('selected');
                                count--;
                            } else {
                                _this.addClass('selected');
                                count++;
                            }
                            $('.delete_select em').text('(' + count + ')');
                            return false;
                        }
                    })

                })

            })


            //全选功能
            $('.all_select').click(function () {
                var _this = $(this);
                var i = 0;
                $('.my_hotel').each(function () {
                    _this.css({ display: 'inline-block', marginLeft: '16px' });
                    $('.invert_select').css('display', 'inline-block');
                    if ($(this).hasClass('dis_none')) {
                        i++
                    } else {
                        $('.list .pure-u-1-10').addClass('selected');
                    }
                })
                count = $('.my_hotel').length - i;
                $('.delete_select em').text('(' + count + ')');

            })


            //反选功能        
            $('.invert_select').click(function () {
                var j = 0;
                $('.list .pure-u-1-10').each(function () {
                    if (!$(this).hasClass('selected')) {
                        $(this).addClass('selected');

                    } else {
                        $(this).removeClass('selected')

                    }
                })
                $('.list .pure-u-1-10').each(function () {
                    if (!$(this).parents('.my_hotel').hasClass('dis_none')) {
                        if ($(this).hasClass('selected')) {
                            j++
                        }
                    }
                })
                count = j;
                $('.delete_select em').text('(' + count + ')');

            })



            /*删除酒店*/
            $('.delete_select').click(function () {

                var str = '', $this = $(this);
                if ($this.data('loading') == 'loading') {
                    return false;
                }
                $this.addClass('loading');
                $('.my_hotel .pure-u-1-10').each(function () {
                    if ($(this).hasClass('selected')) {
                        str += $(this).data('id') + ',';
                    }
                });
                if (str.length <= 0) {
                    return false;
                }
                str = str.substr(0, str.length - 1);
                $.post(_vars.delhotelUrl, { 'id': str }, function (result) {
                    if (result.isSuccess == '1') {
                        alert('删除成功!');
                        location.reload();
                    } else {
                        alert(result.oMessage);
                    }
                });
            })
        }
    });
    _n.app('Account', {
        OrderCommentAction: function () {
            //加载星级分数方法
            (function () {
                var _benchmarkProcessCallbacks = {};
                var setBenchmarkProcess = function (process, pos, callback) {
                    var $elem = $('#' + process);
                    var currPos, toPos;
                    // 如果未初始化
                    if ($elem.data('init') != 'inited') {
                        $elem.data('init', 'inited');
                        currPos = 0;
                        toPos = pos;
                    }
                    else {
                        currPos = parseInt($elem.data('currPos'));
                        toPos = pos
                    }

                    $elem.data('toPos', toPos);
                    $elem.data('currPos', currPos);
                    // 绑定回调
                    _benchmarkProcessCallbacks['pos_' + pos] = callback;
                    // 如果在进度中,则更新到达，并暂退。
                    if ($elem.data('lock') == 'locked') {
                        $elem.data('toPos', pos);
                        return;
                    }
                    // 动画函数
                    var _animate = function (num, $elem) {
                        var deg, circle_start, circle_end, dot_end;
                        circle_start = $elem.find('.circle_start');
                        circle_end = $elem.find('.circle_end');
                        dot_end = $elem.find('.dot_end');
                        if (num <= 50) {
                            $('.circle_end i').css({ clip: "rect(0, 83px, 83px, 42px)" })
                            deg = parseInt(3.6 * num) + 'deg';
                            circle_start.find('i').css({
                                'transform': 'rotate(' + deg + ')',
                                '-mos-transform': 'rotate(' + deg + ')',
                                '-moz-transform': 'rotate(' + deg + ')',
                                '-webkit-transform': 'rotate(' + deg + ')',
                                '-ms-transform': 'rotate(' + deg + ')',
                                '-o-transform': 'rotate(' + deg + ')'
                            });
                            circle_end.find('i').css({
                                'transform': 'rotate(0deg)',
                                '-mos-transform': 'rotate(0deg)',
                                '-ms-transform': 'rotate(0deg)'
                            });
                            dot_end.css({
                                'transform': 'rotate(' + deg + ')',
                                '-mos-transform': 'rotate(' + deg + ')',
                                '-moz-transform': 'rotate(' + deg + ')',
                                '-webkit-transform': 'rotate(' + deg + ')',
                                '-ms-transform': 'rotate(' + deg + ')',
                                '-o-transform': 'rotate(' + deg + ')'
                            });
                        }
                        else {
                            $('.circle_end i').css({ clip: "rect(0, 83px, 83px, 41px)" })
                            deg = parseInt(3.6 * num - 180) + 'deg';
                            circle_start.find('i').css({
                                'transform': 'rotate(180deg)',
                                '-mos-transform': 'rotate(180deg)',
                                '-ms-transform': 'rotate(180deg)'
                            });
                            circle_end.find('i').css({
                                'transform': 'rotate(' + deg + ')',
                                '-mos-transform': 'rotate(' + deg + ')',
                                '-moz-transform': 'rotate(' + deg + ')',
                                '-webkit-transform': 'rotate(' + deg + ')',
                                '-ms-transform': 'rotate(' + deg + ')',
                                '-o-transform': 'rotate(' + deg + ')'

                            });
                            deg = parseInt(3.6 * num) + 'deg';
                            dot_end.css({
                                'transform': 'rotate(' + deg + ')',
                                '-mos-transform': 'rotate(' + deg + ')',
                                '-moz-transform': 'rotate(' + deg + ')',
                                '-webkit-transform': 'rotate(' + deg + ')',
                                '-ms-transform': 'rotate(' + deg + ')',
                                '-o-transform': 'rotate(' + deg + ')'
                            });

                        }
                    }
                    var _run = function (_call, $elem) {
                        var _timer=null;
                        var currPos = parseInt($elem.data('currPos'));
                        var toPos = parseInt($elem.data('toPos'));
                        var call;
                        if (++currPos < toPos) {
                            _animate(currPos, $elem);
                            $elem.data('currPos', currPos);
                            // 触发回调
                            if (call = _benchmarkProcessCallbacks['pos_' + currPos]) {
                                call();
                                _benchmarkProcessCallbacks['pos_' + currPos] = false;
                            }
                        }

                        if (toPos < currPos) {
                            var _toPos=currPos;
                            var _currPos=toPos;
                            var _i=_currPos;

                            setTimeout(function () {
                                if (_i < --_toPos) {
                                    $elem.data('currPos', _toPos);
                                    _animate(_toPos, $elem);
                                    setTimeout(arguments.callee, 16);
                                }
                            }, 16);
                            return false
                        }
                        if(toPos == currPos){
                            clearInterval(_timer)
                            return false
                        }
                        _timer=setTimeout(function () {
                            _call(_call, $elem);
                        }, 16);
                    }
                    _run(_run, $elem);

                }
                window.setBenchmarkProcess = setBenchmarkProcess;
            })();

            (function () {
                function _eval(){
                  var i=0;
                  var hotelScore=0;
                  var _arr=[];
                  var count=0;
                  $(".dot_start").css({display:'block'})
                  $(".dot_end").css({display:'block'})
                  while(i<4){
                      _tempLength=evaluatedFn.getActiveSart($(".item_mid .item_list").eq(i)) 
                      _arr.push(_tempLength)
                      count+=parseInt(_tempLength)
                      i++
                  }
                  var _count=0;
                  _count=(count*(5/20)).toFixed(1)
                  $(".result").html(_count+'<i>分</i>')
                  hotelScore=(count/4)*20
                  setBenchmarkProcess('process', hotelScore, function () {
                      $('.EV_main .result').show().animate({ opacity: '1' }, 1000);
                  });
                }
                //点击星星进行评论
                var evaluatedFn = {
                    selectedStar: function (obj, index) {
                        var i = 0;
                        setTimeout(function () {
                            obj.parent().find("i").eq(i).addClass("icon_star")
                            if (i < index) {
                                setTimeout(arguments.callee, 230);
                            }
                            i++
                        }, 230)
                    },
                    cancelStar: function (obj, index) {
                        var _length = obj.parent().find('i').length
                        if ((index + 1) == _length) {
                            obj.removeClass('icon_star')
                        } else {
                            setTimeout(function () {
                                obj.parent().find("i").eq(index).removeClass("icon_star")
                                if (index < _length) {
                                    setTimeout(arguments.callee, 230);
                                }
                                index++
                            }, 230)
                        }
                    },
                    clickStar: function (obj) {
                        
                        $ele = obj;
                        $ele.on('click', function (i) {
                            $('.EV_main .errorS').css({display:'none'})
                            _this = $(this);
                            _index = _this.index()
                            if (_this.hasClass("icon_star")) {
                                evaluatedFn.cancelStar(_this, _index)
                            } else {
                                evaluatedFn.selectedStar(_this, _index)
                            }
                            setTimeout(function(){ 
                              _eval();
                            },1500)

                        })
                    },
                    getActiveSart: function (obj) {
                        var _num = 0;
                        _num = obj.find(".icon_star").length
                        return _num;
                    },
                    //敏感词过滤
                    filterSensitiveWords: function (str) {
                        var _true = true
                        var filterArr = ['and', 'exec', 'insert', 'select', 'delete', 'update', 'chr', 'mid', 'master', 'or', 'truncate', 'char', 'declare', 'join', '我艹', '我日', '我草', '我操', '色情', '`', '自由門', '动网通', '自由门下载', '無界瀏覽', '自由之门', '自由门', '反社会', '我日', '黄色', '贼民', '黄片', '反共', '独立联盟', '独立联盟', '一党专政', '一党独裁', '台独', '新疆独立', '独立新疆'];      // 将受限制的词语组成正则表达式  
                        for (var i = 0; i < filterArr.length; i++) {
                            var filter = new RegExp(filterArr[i]);        // 将受限制的词语组成正则表达式
                            if (filter.test(str)) {
                                _true = false
                            }
                        }
                        return _true
                    },
                    submit: function (obj, val) {
                        obj.on('click', function () {
                            var _true = true;
                            var RoomInfo = "RoomInfo";
                            var TopicContent = $('#otherRequirements').val();
                            var ServeScore = evaluatedFn.getActiveSart($('.serveScore'));
                            var SanitationScore = evaluatedFn.getActiveSart($('.sanitation'));
                            var FacilityScore = evaluatedFn.getActiveSart($('.facilities'));
                            var NetWorkScore = evaluatedFn.getActiveSart($('.netWorkScore'));

                            var _serveScoreTrue = true;
                            if (ServeScore <= 0) {
                                ServeScore = 0
                                _serveScoreTrue = false;
                            }


                            if (SanitationScore <= 0) {
                                SanitationScore = 0
                                _serveScoreTrue = false;
                            }

                            if (FacilityScore <= 0) {
                                FacilityScore = 0
                                _serveScoreTrue = false;
                            }

                            if (NetWorkScore <= 0) {
                                NetWorkScore = 0
                                _serveScoreTrue = false;
                            }


                            if (_serveScoreTrue) {
                                if (ServeScore < 4 || SanitationScore < 4 || FacilityScore < 4 || NetWorkScore < 4) {
                                    if ($("#otherRequirements").val().length < 10) {
                                        alert("请填写10个汉字以上的评价！")
                                        _true = false;
                                    } else {

                                        _true = true;
                                    }
                                } else {
                                    if($("#otherRequirements").val().length==''){
                                        _true = true;
                                    }else{
                                        if($("#otherRequirements").val().length < 10){
                                            alert("请填写10个汉字以上的评价！")
                                            _true = false;
                                        }else{
                                            _true = true;
                                        }
                                    }
                                }
                                if (evaluatedFn.filterSensitiveWords(TopicContent)) {
                                    if (_true) {
                                        $.ajax({
                                            url: '/Account/AddNewComment',
                                            type: 'POST',
                                            dataType: 'json',
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                            data: {
                                                RoomInfo: RoomInfo,
                                                OrderID: _vars.resNo,
                                                HotelID: _vars.hotelID,
                                                CheckOutDate: _vars.date,
                                                TopicContent: TopicContent,
                                                ServeScore: ServeScore,
                                                SanitationScore: SanitationScore,
                                                FacilityScore: FacilityScore,
                                                __RequestVerificationToken: val,
                                                NetWorkScore: NetWorkScore
                                            },
                                            success: function (msg) {
                                                if (msg.Result) {
                                                    alert('点评成功！');
                                                    window.location.href = "/Account/MyComment?wa_from=mycenter";
                                                } else {
                                                    
                                                    if(typeof document.body != 'undefined'){ 
                                                        document.body.scrollTop=0+'px';
                                                    }else{
                                                        document.documentElement.scrollTop=0+'px';
                                                    }
                                                    $('.EV_main .errorS').html('点评失败！').css({display:'block'})
                                                }

                                            },
                                            error: function (msg) {
                                                if(typeof document.body != 'undefined'){ 
                                                    document.body.scrollTop=0+'px';
                                                }else{
                                                    document.documentElement.scrollTop=0+'px';
                                                }
                                                $('.EV_main .errorS').html('点评失败！').css({display:'block'})
                                            }
                                        })
                                    }
                                } else {
                                    alert("点评文字中存在敏感词，请重新输入！")
                                }
                            } else {
                                alert("请给所有选项打分！")
                                return false;
                            }
                        })

                    },
                    init: function () {
                        $ele = $(".icon_star_empty")
                        this.clickStar($ele);
                        var _val = $("input[name='__RequestVerificationToken']").val();
                        this.submit($('.evl_btn a'), _val)
                    }
                } 
                evaluatedFn.init();
               
                //限制字数
                var LimitedNumberWords = {
                    //限制输入字体字数

                    checkLength: function (obj) {
                        var maxChars = 200; //
                        if (obj.value.length > maxChars) {
                            obj.value = obj.value.substring(0, maxChars)
                            return false;
                        }
                        else {
                            $("#Limited b").text(obj.value.length + "/");
                        }
                    },
                    fncKeyStop: function (event) {
                        //禁止粘贴文本框
                        if (!window.event) {
                            var keycode = evt.keyCode;
                            var key = String.fromCharCode(keycode).toLowerCase();
                            if (evt.ctrlKey && key == "v") {
                                evt.preventDefault();
                                evt.stopPropagation();
                                return false;
                            }
                            if (evt.ctrlKey && key == "x") {
                                evt.preventDefault();
                                evt.stopPropagation();
                                return false;
                            }
                        }
                    },
                    reTextareaH: function (obj) {
                        obj.style.height = obj.scrollHeight + 'px'
                    },
                    init: function () {
                        _ele = $("#otherRequirements");
                        _ele.on('input', function () {
                            LimitedNumberWords.checkLength(this)
                            $('.EV_main .errorS').css({display:'none'})
                        })
                        _ele.on('keydown', function (event) {
                            LimitedNumberWords.fncKeyStop(event)
                        })
                        _ele.on('input', function () {
                            LimitedNumberWords.reTextareaH(this)
                        })
                        _ele.on('blur', function () {
                            LimitedNumberWords.checkLength(this)
                        })
                    }
                }
                LimitedNumberWords.init();
            })();
        },
        MyCommentAction: function () {

            (function () {
                var _getDatecomment = true;
                var _getCount = 1;
                var tipsArr=[];
                var simulationEllipsis = {
                    //限制现实的字数
                    checkLength: function (obj) {
                        var _h = obj.height();
                        if (_h > 88) {
                            _length = obj.find("span").text().length
                            if (_length > 72) {
                                obj.find('.moreWrap').css({ display: 'block' })
                                return false
                            }
                        }
                    },
                    //查看更多进行显示和隐藏
                    toggleMore: function (_ele, eventEle) {
                        eventEle.on('click', function () {
                            if (eventEle.text() == '收起') {
                                _ele.css({ maxHeight: "90px" })
                                _ele.find('.moreWrap em').css({ display: 'inline-block' })
                                eventEle.text('更多>')
                                return false
                            } else {
                                _ele.css({ maxHeight: "100%" })
                                _ele.find('.moreWrap em').css({ display: 'none' })
                                eventEle.text('收起')
                                return false
                            }
                        })
                    },
                    //星级的渲染
                    renderStar: function (data, obj) {
                        var arr = data.toString().split('.');
                        var data1 = parseInt(arr[0])
                        var data2 = parseInt(arr[1])
                        for (var i = 0; i < data1; i++) {
                            obj.find("span .Lposa").eq(i).addClass("icon_star")
                        }
                        //渲染不足一星
                        var _data2 = (data2 % 2)
                        var _Val = 0
                        if (_data2 != 0) {
                            if (data2 != 5 && data2 != 9) {
                                data2 = (data2 + 1)
                            }
                        }
                        _Val = Math.round(((data2 / 10) * 18) + ((26 - 18) / 2))
                        var clipEle = obj.find("span .Lposa").eq(data1)
                        var clipVal = 'rect(0px, ' + _Val + 'px, 26px, 0px)'
                        if (data2 > 0) {
                            clipEle.addClass("icon_clip_star")
                            clipEle.css({
                                clip: clipVal,
                                display: "inline-block"
                            })
                        }
                    },
                    //初始化
                    init: function (renderStarCount, _index) {
                        var _ele = $(".item_info");
                        simulationEllipsis.checkLength(_ele.eq(_index));
                        simulationEllipsis.toggleMore(_ele.eq(_index), $(".moreWrap").eq(_index).find('.more'));
                        simulationEllipsis.renderStar(renderStarCount, $(".item_mid").eq(_index));
                    }
                };

                var randerComments = {
                    changeTime: function (times) {
                        var reg = /\d+/ig
                        var date = parseInt(reg.exec(times));
                        var d = new Date(date);
                        var year = d.getFullYear()
                        var month = (d.getMonth() + 1)
                        month = (month < 9) ? ("0" + month) : month
                        var day = d.getDate()
                        day = (day < 9) ? ("0" + day) : day
                        var _changeTime = year + '-' + month + '-' + day
                        return _changeTime
                    },
                    ceartEle: function (date) {
                        var appendEle = $('#commentCont');
                        var _date = date.Data;
                        if (_date != '[]' && _date != '' && _date != 'undefined') {
                            for (var i = 0; i < _date.length; i++) {
                                var _length = appendEle.children().length; 
                                var _html = '';
                                _date[i].PostsTime = randerComments.changeTime(_date[i].PostsTime);
                                _date[i].Average = _date[i].Average.toFixed(1)

                                
                                if (_date[i].Reply != '[]' && _date[i].Reply != '' && _date[i].Reply != 'undefined' && _date[i].Reply != 'null' && _date[i].Reply != null) {
                                    _html = '<div class="item"><header class="item_top pure-g"><h2 class="pure-u-18-24 title"><a style="color:#333333;" href="javascript:void(0)">' + _date[i].HotelName + '</a></h2><span class="pure-u-6-24 evalDate">' + _date[i].PostsTime + '</span></header><div class="item_mid"><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lml5">' + _date[i].Average + '分</span></div><article class="item_btm"> <div class="item_info"> <span class="figcaption" style="display:block;width:100%;word-break: normal;word-break:break-all;">' + _date[i].PostsContent + '</span> <aside class="moreWrap"><em> ...</em><a href="" class="more">更多&gt;</a></aside> </div> </article> <article class="hotelReply"> <p class="replyTitle">酒店回复： </p> <p class="replyinfo">' + _date[i].Reply + '</p> </article> </div>';
                                } else {
                                    _html = '<div class="item"><header class="item_top pure-g"><h2 class="pure-u-18-24 title"><a style="color:#333333;" href="javascript:void(0)">' + _date[i].HotelName + '</a></h2><span class="pure-u-6-24 evalDate">' + _date[i].PostsTime + '</span></header><div class="item_mid"><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lposr Ldib"><i class="star icon_star_empty"></i><i class="star displaynone Lposa"></i></span><span class="Lml5">' + _date[i].Average + '分</span></div><article class="item_btm"> <div class="item_info"> <span class="figcaption" style="display:block;width:100%;word-break: normal;word-break:break-all;">' + _date[i].PostsContent + '</span> <aside class="moreWrap"><em> ...</em><a href="" class="more">更多&gt;</a></aside> </div> </article> </div>';
                                }

                                if (_length < 1) {
                                    appendEle.append(_html)
                                } else {
                                    $(_html).insertAfter(appendEle.find('.item').eq(_length - 1))
                                }
                                if (_length < 0) {
                                    simulationEllipsis.init(_date[i].Average, i);
                                } else {
                                    var _l = (_length)
                                    $(".item").eq(_l).css({ border: "0 solid #fff" })
                                    simulationEllipsis.init(_date[i].Average, _l);
                                }
                            }
                        }
                    },
                    getDate: function (_index, _size) {
                        
                        $(".myConmmentLoading").css({ display: 'block' });
                        $(".myConmmentLoading").html("加载中......");
                        if (_vars.Loadtime > 1) {
                            if (_getDatecomment) {
                                tipsArr.push(_index);
                                _getDatecomment = false;
                                $.ajax({
                                    url: '/Account/GetMyComment',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        PageIndex: _index,   //页数
                                        PageSize: _size      //条数
                                    },
                                    async: false,
                                    success: function (date) {
                                        _getDatecomment=date.Result
                                        if (date.Result) {
                                            randerComments.ceartEle(date)
                                            $(".myConmmentLoading").css({ display: 'none' })
                                            //_getcomment = true;
                                        } else {
                                            //_getcomment = false;
                                            if (_index == 1) {
                                                $(".myConmmentLoading").html("暂无评价信息");
                                            } else {
                                                $(".myConmmentLoading").html("");
                                            }
                                        }
                                    },
                                    error: function (error) {
                                        $(".myConmmentLoading").html("您的操作过于频繁！");
                                        _getDatecomment = true;
                                    }
                                })
                            } 

                        } else if (_vars.Loadtime <= 1) {
                            $(".myConmmentLoading").html("您的操作过于频繁！");
                        }
                    },
                    init: function (_getCount) {
                        this.getDate(_getCount, 5);
                       
                    }
                };

                
                randerComments.init(1);


                $(document).scroll(function () {
                    if ($("#scriptArea").attr("data-page-id") == "Account/MyComment") {
                        var top = document.documentElement.scrollTop || document.body.scrollTop;
                        var sreenHeight = $(window).height()
                        var _Height = document.body.offsetHeight
                        var _H = _Height - sreenHeight
                        if (_H <= top) {
                            if (_getDatecomment) {
                                var _onTrue=true;
                                _getCount = _getCount + 1;
                                $("#pages").attr({datepages:_getCount})
                                for(var i=0;i<tipsArr.length;i++){
                                  if(_getCount==tipsArr[i]){
                                    _onTrue=false;
                                  }
                                }
                                if(_onTrue){
                                  var _pages=$("#pages").attr("datepages")
                                  randerComments.init(_pages);
                                }
                            }

                        }
                        if (top > 100) {
                            $(".Cback").css({ display: "block" })
                        } else {
                            $(".Cback").css({ display: "none" })
                        }

                    }
                });


            })();

        }
    });

    /* }}} */
    return _n;
}).call(this.N = this.ndoo = this.ndoo || {}, Zepto);
;
// BarrettMu, a class for performing Barrett modular reduction computations in
// JavaScript.
//
// Requires BigInt.js.
//
// Copyright 2004-2005 David Shapiro.
//
// You may use, re-use, abuse, copy, and modify this code to your liking, but
// please keep this header.
//
// Thanks!
// 
// Dave Shapiro
// dave@ohdave.com 

function BarrettMu(m)
{
	this.modulus = biCopy(m);
	this.k = biHighIndex(this.modulus) + 1;
	var b2k = new BigInt();
	b2k.digits[2 * this.k] = 1; // b2k = b^(2k)
	this.mu = biDivide(b2k, this.modulus);
	this.bkplus1 = new BigInt();
	this.bkplus1.digits[this.k + 1] = 1; // bkplus1 = b^(k+1)
	this.modulo = BarrettMu_modulo;
	this.multiplyMod = BarrettMu_multiplyMod;
	this.powMod = BarrettMu_powMod;
}

function BarrettMu_modulo(x)
{
	var q1 = biDivideByRadixPower(x, this.k - 1);
	var q2 = biMultiply(q1, this.mu);
	var q3 = biDivideByRadixPower(q2, this.k + 1);
	var r1 = biModuloByRadixPower(x, this.k + 1);
	var r2term = biMultiply(q3, this.modulus);
	var r2 = biModuloByRadixPower(r2term, this.k + 1);
	var r = biSubtract(r1, r2);
	if (r.isNeg) {
		r = biAdd(r, this.bkplus1);
	}
	var rgtem = biCompare(r, this.modulus) >= 0;
	while (rgtem) {
		r = biSubtract(r, this.modulus);
		rgtem = biCompare(r, this.modulus) >= 0;
	}
	return r;
}

function BarrettMu_multiplyMod(x, y)
{
	/*
	x = this.modulo(x);
	y = this.modulo(y);
	*/
	var xy = biMultiply(x, y);
	return this.modulo(xy);
}

function BarrettMu_powMod(x, y)
{
	var result = new BigInt();
	result.digits[0] = 1;
	var a = x;
	var k = y;
	while (true) {
		if ((k.digits[0] & 1) != 0) result = this.multiplyMod(result, a);
		k = biShiftRight(k, 1);
		if (k.digits[0] == 0 && biHighIndex(k) == 0) break;
		a = this.multiplyMod(a, a);
	}
	return result;
}

;
// BigInt, a suite of routines for performing multiple-precision arithmetic in
// JavaScript.
//
// Copyright 1998-2005 David Shapiro.
//
// You may use, re-use, abuse,
// copy, and modify this code to your liking, but please keep this header.
// Thanks!
//
// Dave Shapiro
// dave@ohdave.com

// IMPORTANT THING: Be sure to set maxDigits according to your precision
// needs. Use the setMaxDigits() function to do this. See comments below.
//
// Tweaked by Ian Bunning
// Alterations:
// Fix bug in function biFromHex(s) to allow
// parsing of strings of length != 0 (mod 4)

// Changes made by Dave Shapiro as of 12/30/2004:
//
// The BigInt() constructor doesn't take a string anymore. If you want to
// create a BigInt from a string, use biFromDecimal() for base-10
// representations, biFromHex() for base-16 representations, or
// biFromString() for base-2-to-36 representations.
//
// biFromArray() has been removed. Use biCopy() instead, passing a BigInt
// instead of an array.
//
// The BigInt() constructor now only constructs a zeroed-out array.
// Alternatively, if you pass <true>, it won't construct any array. See the
// biCopy() method for an example of this.
//
// Be sure to set maxDigits depending on your precision needs. The default
// zeroed-out array ZERO_ARRAY is constructed inside the setMaxDigits()
// function. So use this function to set the variable. DON'T JUST SET THE
// VALUE. USE THE FUNCTION.
//
// ZERO_ARRAY exists to hopefully speed up construction of BigInts(). By
// precalculating the zero array, we can just use slice(0) to make copies of
// it. Presumably this calls faster native code, as opposed to setting the
// elements one at a time. I have not done any timing tests to verify this
// claim.

// Max number = 10^16 - 2 = 9999999999999998;
//               2^53     = 9007199254740992;

var biRadixBase = 2;
var biRadixBits = 16;
var bitsPerDigit = biRadixBits;
var biRadix = 1 << 16; // = 2^16 = 65536
var biHalfRadix = biRadix >>> 1;
var biRadixSquared = biRadix * biRadix;
var maxDigitVal = biRadix - 1;
var maxInteger = 9999999999999998; 

// maxDigits:
// Change this to accommodate your largest number size. Use setMaxDigits()
// to change it!
//
// In general, if you're working with numbers of size N bits, you'll need 2*N
// bits of storage. Each digit holds 16 bits. So, a 1024-bit key will need
//
// 1024 * 2 / 16 = 128 digits of storage.
//

var maxDigits;
var ZERO_ARRAY;
var bigZero, bigOne;

function setMaxDigits(value)
{
	maxDigits = value;
	ZERO_ARRAY = new Array(maxDigits);
	for (var iza = 0; iza < ZERO_ARRAY.length; iza++) ZERO_ARRAY[iza] = 0;
	bigZero = new BigInt();
	bigOne = new BigInt();
	bigOne.digits[0] = 1;
}

setMaxDigits(20);

// The maximum number of digits in base 10 you can convert to an
// integer without JavaScript throwing up on you.
var dpl10 = 15;
// lr10 = 10 ^ dpl10
var lr10 = biFromNumber(1000000000000000);

function BigInt(flag)
{
	if (typeof flag == "boolean" && flag == true) {
		this.digits = null;
	}
	else {
		this.digits = ZERO_ARRAY.slice(0);
	}
	this.isNeg = false;
}

function biFromDecimal(s)
{
	var isNeg = s.charAt(0) == '-';
	var i = isNeg ? 1 : 0;
	var result;
	// Skip leading zeros.
	while (i < s.length && s.charAt(i) == '0') ++i;
	if (i == s.length) {
		result = new BigInt();
	}
	else {
		var digitCount = s.length - i;
		var fgl = digitCount % dpl10;
		if (fgl == 0) fgl = dpl10;
		result = biFromNumber(Number(s.substr(i, fgl)));
		i += fgl;
		while (i < s.length) {
			result = biAdd(biMultiply(result, lr10),
			               biFromNumber(Number(s.substr(i, dpl10))));
			i += dpl10;
		}
		result.isNeg = isNeg;
	}
	return result;
}

function biCopy(bi)
{
	var result = new BigInt(true);
	result.digits = bi.digits.slice(0);
	result.isNeg = bi.isNeg;
	return result;
}

function biFromNumber(i)
{
	var result = new BigInt();
	result.isNeg = i < 0;
	i = Math.abs(i);
	var j = 0;
	while (i > 0) {
		result.digits[j++] = i & maxDigitVal;
		i = Math.floor(i / biRadix);
	}
	return result;
}

function reverseStr(s)
{
	var result = "";
	for (var i = s.length - 1; i > -1; --i) {
		result += s.charAt(i);
	}
	return result;
}

var hexatrigesimalToChar = new Array(
 '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
 'u', 'v', 'w', 'x', 'y', 'z'
);

function biToString(x, radix)
	// 2 <= radix <= 36
{
	var b = new BigInt();
	b.digits[0] = radix;
	var qr = biDivideModulo(x, b);
	var result = hexatrigesimalToChar[qr[1].digits[0]];
	while (biCompare(qr[0], bigZero) == 1) {
		qr = biDivideModulo(qr[0], b);
		digit = qr[1].digits[0];
		result += hexatrigesimalToChar[qr[1].digits[0]];
	}
	return (x.isNeg ? "-" : "") + reverseStr(result);
}

function biToDecimal(x)
{
	var b = new BigInt();
	b.digits[0] = 10;
	var qr = biDivideModulo(x, b);
	var result = String(qr[1].digits[0]);
	while (biCompare(qr[0], bigZero) == 1) {
		qr = biDivideModulo(qr[0], b);
		result += String(qr[1].digits[0]);
	}
	return (x.isNeg ? "-" : "") + reverseStr(result);
}

var hexToChar = new Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                          'a', 'b', 'c', 'd', 'e', 'f');

function digitToHex(n)
{
	var mask = 0xf;
	var result = "";
	for (i = 0; i < 4; ++i) {
		result += hexToChar[n & mask];
		n >>>= 4;
	}
	return reverseStr(result);
}

function biToHex(x)
{
	var result = "";
	var n = biHighIndex(x);
	for (var i = biHighIndex(x); i > -1; --i) {
		result += digitToHex(x.digits[i]);
	}
	return result;
}

function charToHex(c)
{
	var ZERO = 48;
	var NINE = ZERO + 9;
	var littleA = 97;
	var littleZ = littleA + 25;
	var bigA = 65;
	var bigZ = 65 + 25;
	var result;

	if (c >= ZERO && c <= NINE) {
		result = c - ZERO;
	} else if (c >= bigA && c <= bigZ) {
		result = 10 + c - bigA;
	} else if (c >= littleA && c <= littleZ) {
		result = 10 + c - littleA;
	} else {
		result = 0;
	}
	return result;
}

function hexToDigit(s)
{
	var result = 0;
	var sl = Math.min(s.length, 4);
	for (var i = 0; i < sl; ++i) {
		result <<= 4;
		result |= charToHex(s.charCodeAt(i))
	}
	return result;
}

function biFromHex(s)
{
	var result = new BigInt();
	var sl = s.length;
	for (var i = sl, j = 0; i > 0; i -= 4, ++j) {
		result.digits[j] = hexToDigit(s.substr(Math.max(i - 4, 0), Math.min(i, 4)));
	}
	return result;
}

function biFromString(s, radix)
{
	var isNeg = s.charAt(0) == '-';
	var istop = isNeg ? 1 : 0;
	var result = new BigInt();
	var place = new BigInt();
	place.digits[0] = 1; // radix^0
	for (var i = s.length - 1; i >= istop; i--) {
		var c = s.charCodeAt(i);
		var digit = charToHex(c);
		var biDigit = biMultiplyDigit(place, digit);
		result = biAdd(result, biDigit);
		place = biMultiplyDigit(place, radix);
	}
	result.isNeg = isNeg;
	return result;
}

function biDump(b)
{
	return (b.isNeg ? "-" : "") + b.digits.join(" ");
}

function biAdd(x, y)
{
	var result;

	if (x.isNeg != y.isNeg) {
		y.isNeg = !y.isNeg;
		result = biSubtract(x, y);
		y.isNeg = !y.isNeg;
	}
	else {
		result = new BigInt();
		var c = 0;
		var n;
		for (var i = 0; i < x.digits.length; ++i) {
			n = x.digits[i] + y.digits[i] + c;
			result.digits[i] = n % biRadix;
			c = Number(n >= biRadix);
		}
		result.isNeg = x.isNeg;
	}
	return result;
}

function biSubtract(x, y)
{
	var result;
	if (x.isNeg != y.isNeg) {
		y.isNeg = !y.isNeg;
		result = biAdd(x, y);
		y.isNeg = !y.isNeg;
	} else {
		result = new BigInt();
		var n, c;
		c = 0;
		for (var i = 0; i < x.digits.length; ++i) {
			n = x.digits[i] - y.digits[i] + c;
			result.digits[i] = n % biRadix;
			// Stupid non-conforming modulus operation.
			if (result.digits[i] < 0) result.digits[i] += biRadix;
			c = 0 - Number(n < 0);
		}
		// Fix up the negative sign, if any.
		if (c == -1) {
			c = 0;
			for (var i = 0; i < x.digits.length; ++i) {
				n = 0 - result.digits[i] + c;
				result.digits[i] = n % biRadix;
				// Stupid non-conforming modulus operation.
				if (result.digits[i] < 0) result.digits[i] += biRadix;
				c = 0 - Number(n < 0);
			}
			// Result is opposite sign of arguments.
			result.isNeg = !x.isNeg;
		} else {
			// Result is same sign.
			result.isNeg = x.isNeg;
		}
	}
	return result;
}

function biHighIndex(x)
{
	var result = x.digits.length - 1;
	while (result > 0 && x.digits[result] == 0) --result;
	return result;
}

function biNumBits(x)
{
	var n = biHighIndex(x);
	var d = x.digits[n];
	var m = (n + 1) * bitsPerDigit;
	var result;
	for (result = m; result > m - bitsPerDigit; --result) {
		if ((d & 0x8000) != 0) break;
		d <<= 1;
	}
	return result;
}

function biMultiply(x, y)
{
	var result = new BigInt();
	var c;
	var n = biHighIndex(x);
	var t = biHighIndex(y);
	var u, uv, k;

	for (var i = 0; i <= t; ++i) {
		c = 0;
		k = i;
		for (j = 0; j <= n; ++j, ++k) {
			uv = result.digits[k] + x.digits[j] * y.digits[i] + c;
			result.digits[k] = uv & maxDigitVal;
			c = uv >>> biRadixBits;
			//c = Math.floor(uv / biRadix);
		}
		result.digits[i + n + 1] = c;
	}
	// Someone give me a logical xor, please.
	result.isNeg = x.isNeg != y.isNeg;
	return result;
}

function biMultiplyDigit(x, y)
{
	var n, c, uv;

	result = new BigInt();
	n = biHighIndex(x);
	c = 0;
	for (var j = 0; j <= n; ++j) {
		uv = result.digits[j] + x.digits[j] * y + c;
		result.digits[j] = uv & maxDigitVal;
		c = uv >>> biRadixBits;
		//c = Math.floor(uv / biRadix);
	}
	result.digits[1 + n] = c;
	return result;
}

function arrayCopy(src, srcStart, dest, destStart, n)
{
	var m = Math.min(srcStart + n, src.length);
	for (var i = srcStart, j = destStart; i < m; ++i, ++j) {
		dest[j] = src[i];
	}
}

var highBitMasks = new Array(0x0000, 0x8000, 0xC000, 0xE000, 0xF000, 0xF800,
                             0xFC00, 0xFE00, 0xFF00, 0xFF80, 0xFFC0, 0xFFE0,
                             0xFFF0, 0xFFF8, 0xFFFC, 0xFFFE, 0xFFFF);

function biShiftLeft(x, n)
{
	var digitCount = Math.floor(n / bitsPerDigit);
	var result = new BigInt();
	arrayCopy(x.digits, 0, result.digits, digitCount,
	          result.digits.length - digitCount);
	var bits = n % bitsPerDigit;
	var rightBits = bitsPerDigit - bits;
	for (var i = result.digits.length - 1, i1 = i - 1; i > 0; --i, --i1) {
		result.digits[i] = ((result.digits[i] << bits) & maxDigitVal) |
		                   ((result.digits[i1] & highBitMasks[bits]) >>>
		                    (rightBits));
	}
	result.digits[0] = ((result.digits[i] << bits) & maxDigitVal);
	result.isNeg = x.isNeg;
	return result;
}

var lowBitMasks = new Array(0x0000, 0x0001, 0x0003, 0x0007, 0x000F, 0x001F,
                            0x003F, 0x007F, 0x00FF, 0x01FF, 0x03FF, 0x07FF,
                            0x0FFF, 0x1FFF, 0x3FFF, 0x7FFF, 0xFFFF);

function biShiftRight(x, n)
{
	var digitCount = Math.floor(n / bitsPerDigit);
	var result = new BigInt();
	arrayCopy(x.digits, digitCount, result.digits, 0,
	          x.digits.length - digitCount);
	var bits = n % bitsPerDigit;
	var leftBits = bitsPerDigit - bits;
	for (var i = 0, i1 = i + 1; i < result.digits.length - 1; ++i, ++i1) {
		result.digits[i] = (result.digits[i] >>> bits) |
		                   ((result.digits[i1] & lowBitMasks[bits]) << leftBits);
	}
	result.digits[result.digits.length - 1] >>>= bits;
	result.isNeg = x.isNeg;
	return result;
}

function biMultiplyByRadixPower(x, n)
{
	var result = new BigInt();
	arrayCopy(x.digits, 0, result.digits, n, result.digits.length - n);
	return result;
}

function biDivideByRadixPower(x, n)
{
	var result = new BigInt();
	arrayCopy(x.digits, n, result.digits, 0, result.digits.length - n);
	return result;
}

function biModuloByRadixPower(x, n)
{
	var result = new BigInt();
	arrayCopy(x.digits, 0, result.digits, 0, n);
	return result;
}

function biCompare(x, y)
{
	if (x.isNeg != y.isNeg) {
		return 1 - 2 * Number(x.isNeg);
	}
	for (var i = x.digits.length - 1; i >= 0; --i) {
		if (x.digits[i] != y.digits[i]) {
			if (x.isNeg) {
				return 1 - 2 * Number(x.digits[i] > y.digits[i]);
			} else {
				return 1 - 2 * Number(x.digits[i] < y.digits[i]);
			}
		}
	}
	return 0;
}

function biDivideModulo(x, y)
{
	var nb = biNumBits(x);
	var tb = biNumBits(y);
	var origYIsNeg = y.isNeg;
	var q, r;
	if (nb < tb) {
		// |x| < |y|
		if (x.isNeg) {
			q = biCopy(bigOne);
			q.isNeg = !y.isNeg;
			x.isNeg = false;
			y.isNeg = false;
			r = biSubtract(y, x);
			// Restore signs, 'cause they're references.
			x.isNeg = true;
			y.isNeg = origYIsNeg;
		} else {
			q = new BigInt();
			r = biCopy(x);
		}
		return new Array(q, r);
	}

	q = new BigInt();
	r = x;

	// Normalize Y.
	var t = Math.ceil(tb / bitsPerDigit) - 1;
	var lambda = 0;
	while (y.digits[t] < biHalfRadix) {
		y = biShiftLeft(y, 1);
		++lambda;
		++tb;
		t = Math.ceil(tb / bitsPerDigit) - 1;
	}
	// Shift r over to keep the quotient constant. We'll shift the
	// remainder back at the end.
	r = biShiftLeft(r, lambda);
	nb += lambda; // Update the bit count for x.
	var n = Math.ceil(nb / bitsPerDigit) - 1;

	var b = biMultiplyByRadixPower(y, n - t);
	while (biCompare(r, b) != -1) {
		++q.digits[n - t];
		r = biSubtract(r, b);
	}
	for (var i = n; i > t; --i) {
    var ri = (i >= r.digits.length) ? 0 : r.digits[i];
    var ri1 = (i - 1 >= r.digits.length) ? 0 : r.digits[i - 1];
    var ri2 = (i - 2 >= r.digits.length) ? 0 : r.digits[i - 2];
    var yt = (t >= y.digits.length) ? 0 : y.digits[t];
    var yt1 = (t - 1 >= y.digits.length) ? 0 : y.digits[t - 1];
		if (ri == yt) {
			q.digits[i - t - 1] = maxDigitVal;
		} else {
			q.digits[i - t - 1] = Math.floor((ri * biRadix + ri1) / yt);
		}

		var c1 = q.digits[i - t - 1] * ((yt * biRadix) + yt1);
		var c2 = (ri * biRadixSquared) + ((ri1 * biRadix) + ri2);
		while (c1 > c2) {
			--q.digits[i - t - 1];
			c1 = q.digits[i - t - 1] * ((yt * biRadix) | yt1);
			c2 = (ri * biRadix * biRadix) + ((ri1 * biRadix) + ri2);
		}

		b = biMultiplyByRadixPower(y, i - t - 1);
		r = biSubtract(r, biMultiplyDigit(b, q.digits[i - t - 1]));
		if (r.isNeg) {
			r = biAdd(r, b);
			--q.digits[i - t - 1];
		}
	}
	r = biShiftRight(r, lambda);
	// Fiddle with the signs and stuff to make sure that 0 <= r < y.
	q.isNeg = x.isNeg != origYIsNeg;
	if (x.isNeg) {
		if (origYIsNeg) {
			q = biAdd(q, bigOne);
		} else {
			q = biSubtract(q, bigOne);
		}
		y = biShiftRight(y, lambda);
		r = biSubtract(y, r);
	}
	// Check for the unbelievably stupid degenerate case of r == -0.
	if (r.digits[0] == 0 && biHighIndex(r) == 0) r.isNeg = false;

	return new Array(q, r);
}

function biDivide(x, y)
{
	return biDivideModulo(x, y)[0];
}

function biModulo(x, y)
{
	return biDivideModulo(x, y)[1];
}

function biMultiplyMod(x, y, m)
{
	return biModulo(biMultiply(x, y), m);
}

function biPow(x, y)
{
	var result = bigOne;
	var a = x;
	while (true) {
		if ((y & 1) != 0) result = biMultiply(result, a);
		y >>= 1;
		if (y == 0) break;
		a = biMultiply(a, a);
	}
	return result;
}

function biPowMod(x, y, m)
{
	var result = bigOne;
	var a = x;
	var k = y;
	while (true) {
		if ((k.digits[0] & 1) != 0) result = biMultiplyMod(result, a, m);
		k = biShiftRight(k, 1);
		if (k.digits[0] == 0 && biHighIndex(k) == 0) break;
		a = biMultiplyMod(a, a, m);
	}
	return result;
}

;
var SwipeSlide = function(container, options){
  this.options = $.extend({
    first: 0,                     // the first visible slide on initialization
    visibleSlides: 1,             // number of slides visible at the same time
    vertical: false,              // horizontal or vertical
    tolerance:0.5,                // values between 0 and 1, where 1 means you have to drag to the center of the slide (a value of 1 equals the ios behaviour)
    delay: 0.3,                   // animation speed in seconds,
    easing: 'ease-out',           // the easing function
    autoPlay: false,              // false, or value in seconds to start auto slideshow
    autoPlayTime:1000,
    useTranslate3d: true,
    bulletNavigation: false,     // will insert bullet navigation: false, true or 'link' (event handlers will be attached)
    directionalNavigation: false, // will insert previous and next links
    beforeChange: null,
    afterChange: null                // after slide transition callback
  }, options)

  this.isVertical  = !!this.options.vertical
  this.container   = $(container).addClass('ui-swipeslide').addClass('ui-swipeslide-'+(this.isVertical ? 'vertical' : 'horizontal'))
  this.reel        = this.container.children().first().addClass('ui-swipeslide-reel')
  this.slides      = this.reel.children().addClass('ui-swipeslide-slide')
  this.numPages    = Math.ceil(this.slides.length / this.options.visibleSlides)
  this.currentPage = this.validPage(this.options.first)
  this.touch       = {}
  this.isTouch     = 'ontouchstart' in document.documentElement
  this.events      = {
    start: this.isTouch ? 'touchstart' : 'mousedown',
    move:  this.isTouch ? 'touchmove'  : 'mousemove',
    end:   this.isTouch ? 'touchend touchcancel touchleave' : 'mouseup mouseout mouseleave',
    click: this.isTouch ? 'touchend' : 'click'
  }
  this.setup()
  this.addEventListeners()
  if (this.options.autoPlay) this.autoPlay()
}

SwipeSlide.prototype = {
  // public
  page: function(index) {
    this.stopAutoPlay()
    var newPage = this.validPage(index), callback
    // only set callback function if a slide happend
    if (this.currentPage != newPage) {
      if($.isFunction(this.options.beforeChange)) this.options.beforeChange(this, this.currentPage, newPage)
      this.currentPage = newPage
      callback = $.proxy(this.callback, this)
    } else if (this.options.autoPlay){
      callback = $.proxy(this.autoPlay, this)
    }
    this.move(0, this.options.delay, callback)
  },
  first:     function(){ this.page(0) },
  next:      function(){ this.page(this.currentPage+1) },
  prev:      function(){ this.page(this.currentPage-1) },
  last:      function(){ this.page(this.numPages-1) },
  isFirst:   function(){ return this.currentPage == 0 },
  isLast:    function(){ return this.currentPage == this.numPages-1 },
  validPage: function(num){ return Math.max(Math.min(num, this.numPages-1), 0) },
  autoPlay:  function(){
    if (this.timeout) return false
    var fn = this.isLast() ? this.first : this.next
    this.timeout = setTimeout($.proxy(fn, this), this.options.autoPlay * this.options.autoPlayTime) 
  },
  stopAutoPlay:  function(){ 
    clearTimeout(this.timeout)
    delete this.timeout 
  },
  visibleSlides: function(){
    return this.slides.slice(this.currentPage, this.currentPage+this.options.visibleSlides)
  },
  
  // private
  move: function(distance, delay, callback) {
    this.reel.animate(this.animationProperties(distance), { duration: delay * this.options.autoPlayTime, easing: this.options.easing, complete: callback })
  },

  animationProperties: function(distance) {
    var position = -this.currentPage * this.dimension + distance + 'px', props = {}
    if (this.options.useTranslate3d) {
      props['translate3d'] = (this.isVertical ? '0,'+position : position+',0') + ',0'
    } else {
      props[this.isVertical ? 'translateY' : 'translateX'] = position
    }
    return props
  },
    
  setup: function(){
    var fn = this.isVertical ? 'height' : 'width'
    this.dimension = this.container[fn]()
    this.tolerance = this.options.tolerance * this.dimension / 2
    // set height or width of reel and slides
    this.reel[fn](this.dimension * this.numPages + 'px')
    this.slides[fn](this.dimension / this.options.visibleSlides + 'px')
    // move to first slide without animation
    this.move(0,0)
  },

  addEventListeners: function(){
    // bind listeners for touch movement
    this.reel
      .on(this.events.start, $.proxy(this.touchStart, this))
      .on(this.events.move,  $.proxy(this.touchMove, this))
      .on(this.events.end,   $.proxy(this.touchEnd, this))
      
    // bind listeners to any elments with '.prev', '.next', '.first' or '.last' class 
    this.container
      .on(this.events.click, '.next',  $.proxy(this.next,  this))
      .on(this.events.click, '.pre ',  $.proxy(this.prev,  this))
      .on(this.events.click, '.first', $.proxy(this.first, this))
      .on(this.events.click, '.last',  $.proxy(this.last,  this))

    // recalculate dimension on window resize or orientation change
    $(window).on('resize', $.proxy(this.setup, this))
  },
    
  touchStart: function(e){
    this.touch.start = this.trackTouch(e)
    delete this.isScroll
    if (!this.isTouch) return false
  },
    
  touchMove: function(e){
    if (!this.touch.start) return
    this.touch.end = this.trackTouch(e)
    var distance   = this.distance(this.isVertical)
    if (typeof this.isScroll == 'undefined') {
      this.isScroll = Math.abs(distance) < Math.abs(this.distance(!this.isVertical))
    }
    if (!this.isScroll) {
      this.stopAutoPlay()
      this.move(this.withResistance(distance), 0)
      return false
    }
  },
    
  touchEnd: function(e){
    if(typeof this.isScroll == 'undefined'){
      
      if(!this.options.bulletNavigation){
        return false
      }
    }else{
      
      if (!this.isScroll) {
      var distance = this.distance(this.isVertical), add = 0
      if (Math.abs(distance) > this.tolerance) add = distance < 0 ? 1 : -1
        this.page(this.currentPage + add)
      }
      this.touch = {}
      return false
    } 
  },

  trackTouch: function(e) {
    var o = this.isTouch ? e.touches[0] : e
    return { x: o.pageX, y: o.pageY }
  },
  
  distance: function(vertical) {
    var d = vertical ? 'y' : 'x'
    try { return this.touch.end[d] - this.touch.start[d] } catch(e) {return 0}
  },
  
  withResistance: function(d){
    if (this.isFirst() && d > 0 || this.isLast() && d < 0) d /= (1 + Math.abs(d) / this.dimension)
    return d
  },
  
  callback: function(){
    // call user defined callback function with the currentPage number and an array of visible slides
    if ($.isFunction(this.options.afterChange)) this.options.afterChange(this, this.currentPage)
    if (this.options.autoPlay) this.autoPlay()
  },

}

var SwipeSlide3D = function(container, options) {
  SwipeSlide.call(this, container, options)
  this.container.addClass('ui-swipeslide-3d')
}
SwipeSlide3D.prototype = new SwipeSlide

$.extend(SwipeSlide3D.prototype, {
  setup: function() {
    var fn = this.isVertical ? 'height' : 'width'
    this.dimension = this.container[fn]()
    this.tolerance = this.options.tolerance * this.dimension / 2
    this.alpha     = 360/this.slides.length * (this.isVertical ? -1 : 1)
    this.radius    = Math.round((this.dimension/2) / Math.tan(Math.PI / this.slides.length))
    this.slides.each($.proxy(this.positionSlide, this))
    this.move(0,0)
  },
  validPage: function(num){    
    if (num < 0) num += this.numPages
    else if (num >= this.numPages) num %= this.numPages
    return num
  },
  animationProperties: function(distance) {
    var delta = (this.alpha * distance / this.dimension) - (this.alpha * this.currentPage)
    return { translate3d: '0,0,'+ -this.radius + 'px', rotate3d: this.vectorsWithDeg(delta) }
  },
  positionSlide: function(i, slide){
    $(slide).animate({ rotate3d: this.vectorsWithDeg(i*this.alpha), translate3d: '0,0,'+this.radius+'px' }, {duration: 0})
  },
  vectorsWithDeg: function(degree){
    return (this.isVertical ? '1,0' : '0,1') + ',0,' + degree + 'deg'
  },
  withResistance: function(d) {return d} // no resistance for 3d
})

// zepto plugin
;(function($) {
  $.fn.swipeSlide = function(options) {
    var klass = (options=options||{}).threeD ? SwipeSlide3D : SwipeSlide
    return this.each(function() {
      var s = new klass(this, options);
      if (typeof($(this).data("swipeInstance", s).data("swipeInstance")) === "string") { $(this).removeAttr("data-swipeInstance"); }
      return;
    });
  }
})(window.Zepto);
// RSA, a suite of routines for performing RSA public-key computations in
// JavaScript.
//
// Requires BigInt.js and Barrett.js.
//
// Copyright 1998-2005 David Shapiro.
//
// You may use, re-use, abuse, copy, and modify this code to your liking, but
// please keep this header.
//
// Thanks!
// 
// Dave Shapiro
// dave@ohdave.com 

function RSAKeyPair(encryptionExponent, decryptionExponent, modulus)
{
	this.e = biFromHex(encryptionExponent);
	this.d = biFromHex(decryptionExponent);
	this.m = biFromHex(modulus);
	
	// We can do two bytes per digit, so
	// chunkSize = 2 * (number of digits in modulus - 1).
	// Since biHighIndex returns the high index, not the number of digits, 1 has
	// already been subtracted.
	//this.chunkSize = 2 * biHighIndex(this.m);
	
	////////////////////////////////// TYF
    	this.digitSize = 2 * biHighIndex(this.m) + 2;
	this.chunkSize = this.digitSize - 11; // maximum, anything lower is fine
	////////////////////////////////// TYF

	this.radix = 16;
	this.barrett = new BarrettMu(this.m);
}

function twoDigit(n)
{
	return (n < 10 ? "0" : "") + String(n);
}

function encryptedString(key, s)
// Altered by Rob Saunders (rob@robsaunders.net). New routine pads the
// string after it has been converted to an array. This fixes an
// incompatibility with Flash MX's ActionScript.
// Altered by Tang Yu Feng for interoperability with Microsoft's
// RSACryptoServiceProvider implementation.
{
	////////////////////////////////// TYF
	if (key.chunkSize > key.digitSize - 11)
	{
	    return "Error";
	}
	////////////////////////////////// TYF

    s = encodeURI(s);
	var a = new Array();
	var sl = s.length;
	
	var i = 0;
	while (i < sl) {
		a[i] = s.charCodeAt(i);
		i++;
	}

	//while (a.length % key.chunkSize != 0) {
	//	a[i++] = 0;
	//}

	var al = a.length;
	var result = "";
	var j, k, block;
	for (i = 0; i < al; i += key.chunkSize) {
		block = new BigInt();
		j = 0;
		
		//for (k = i; k < i + key.chunkSize; ++j) {
		//	block.digits[j] = a[k++];
		//	block.digits[j] += a[k++] << 8;
		//}
		
		////////////////////////////////// TYF
		// Add PKCS#1 v1.5 padding
		// 0x00 || 0x02 || PseudoRandomNonZeroBytes || 0x00 || Message
		// Variable a before padding must be of at most digitSize-11
		// That is for 3 marker bytes plus at least 8 random non-zero bytes
		var x;
		var msgLength = (i+key.chunkSize)>al ? al%key.chunkSize : key.chunkSize;
		
		// Variable b with 0x00 || 0x02 at the highest index.
		var b = new Array();
		for (x=0; x<msgLength; x++)
		{
		    b[x] = a[i+msgLength-1-x];
		}
		b[msgLength] = 0; // marker
		var paddedSize = Math.max(8, key.digitSize - 3 - msgLength);
	
		for (x=0; x<paddedSize; x++) {
		    b[msgLength+1+x] = Math.floor(Math.random()*254) + 1; // [1,255]
		}
		// It can be asserted that msgLength+paddedSize == key.digitSize-3
		b[key.digitSize-2] = 2; // marker
		b[key.digitSize-1] = 0; // marker
		
		for (k = 0; k < key.digitSize; ++j) 
		{
		    block.digits[j] = b[k++];
		    block.digits[j] += b[k++] << 8;
		}
		////////////////////////////////// TYF

		var crypt = key.barrett.powMod(block, key.e);
		var text = key.radix == 16 ? biToHex(crypt) : biToString(crypt, key.radix);
		result += text + " ";
	}
	return result.substring(0, result.length - 1); // Remove last space.
}

function decryptedString(key, s)
{
	var blocks = s.split(" ");
	var result = "";
	var i, j, block;
	for (i = 0; i < blocks.length; ++i) {
		var bi;
		if (key.radix == 16) {
			bi = biFromHex(blocks[i]);
		}
		else {
			bi = biFromString(blocks[i], key.radix);
		}
		block = key.barrett.powMod(bi, key.d);
		for (j = 0; j <= biHighIndex(block); ++j) {
			result += String.fromCharCode(block.digits[j] & 255,
			                              block.digits[j] >> 8);
		}
	}
	// Remove trailing null, if any.
	if (result.charCodeAt(result.length - 1) == 0) {
		result = result.substring(0, result.length - 1);
	}
	return result;
}
;
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.l = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var EANencoder = function () {
		function EANencoder() {
			_classCallCheck(this, EANencoder);

			// Standard start end and middle bits
			this.startBin = "101";
			this.endBin = "101";
			this.middleBin = "01010";

			// The L (left) type of encoding
			this.Lbinary = ["0001101", "0011001", "0010011", "0111101", "0100011", "0110001", "0101111", "0111011", "0110111", "0001011"];

			// The G type of encoding
			this.Gbinary = ["0100111", "0110011", "0011011", "0100001", "0011101", "0111001", "0000101", "0010001", "0001001", "0010111"];

			// The R (right) type of encoding
			this.Rbinary = ["1110010", "1100110", "1101100", "1000010", "1011100", "1001110", "1010000", "1000100", "1001000", "1110100"];
		}

		// Convert a numberarray to the representing


		EANencoder.prototype.encode = function encode(number, structure, separator) {
			// Create the variable that should be returned at the end of the function
			var result = "";

			// Make sure that the separator is set
			separator = separator || "";

			// Loop all the numbers
			for (var i = 0; i < number.length; i++) {
				// Using the L, G or R encoding and add it to the returning variable
				if (structure[i] == "L") {
					result += this.Lbinary[number[i]];
				} else if (structure[i] == "G") {
					result += this.Gbinary[number[i]];
				} else if (structure[i] == "R") {
					result += this.Rbinary[number[i]];
				}

				// Add separator in between encodings
				if (i < number.length - 1) {
					result += separator;
				}
			}
			return result;
		};

		return EANencoder;
	}();

	exports.default = EANencoder;

/***/ },
/* 1 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	// Encoding documentation
	// https://en.wikipedia.org/wiki/MSI_Barcode#Character_set_and_binary_lookup

	var MSI = function () {
		function MSI(string) {
			_classCallCheck(this, MSI);

			this.string = string;
		}

		MSI.prototype.encode = function encode() {
			// Start bits
			var ret = "110";

			for (var i = 0; i < this.string.length; i++) {
				// Convert the character to binary (always 4 binary digits)
				var digit = parseInt(this.string[i]);
				var bin = digit.toString(2);
				bin = addZeroes(bin, 4 - bin.length);

				// Add 100 for every zero and 110 for every 1
				for (var b = 0; b < bin.length; b++) {
					ret += bin[b] == "0" ? "100" : "110";
				}
			}

			// End bits
			ret += "1001";

			return {
				data: ret,
				text: this.string
			};
		};

		MSI.prototype.valid = function valid() {
			return this.string.search(/^[0-9]+$/) !== -1;
		};

		return MSI;
	}();

	function addZeroes(number, n) {
		for (var i = 0; i < n; i++) {
			number = "0" + number;
		}
		return number;
	}

	exports.default = MSI;

/***/ },
/* 2 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	// This is the master class, it does require the start code to be
	// included in the string

	var CODE128 = function () {
		function CODE128(string) {
			_classCallCheck(this, CODE128);

			// Fill the bytes variable with the ascii codes of string
			this.bytes = [];
			for (var i = 0; i < string.length; ++i) {
				this.bytes.push(string.charCodeAt(i));
			}

			// First element should be startcode, remove that
			this.string = string.substring(1);

			// Data for each character, the last characters will not be encoded but are used for error correction
			// Numbers encode to (n + 1000) -> binary; 740 -> (740 + 1000).toString(2) -> "11011001100"
			this.encodings = [// + 1000
			740, 644, 638, 176, 164, 100, 224, 220, 124, 608, 604, 572, 436, 244, 230, 484, 260, 254, 650, 628, 614, 764, 652, 902, 868, 836, 830, 892, 844, 842, 752, 734, 590, 304, 112, 94, 416, 128, 122, 672, 576, 570, 464, 422, 134, 496, 478, 142, 910, 678, 582, 768, 762, 774, 880, 862, 814, 896, 890, 818, 914, 602, 930, 328, 292, 200, 158, 68, 62, 424, 412, 232, 218, 76, 74, 554, 616, 978, 556, 146, 340, 212, 182, 508, 268, 266, 956, 940, 938, 758, 782, 974, 400, 310, 118, 512, 506, 960, 954, 502, 518, 886, 966, /* Start codes */668, 680, 692, 5379];
		}

		// The public encoding function


		CODE128.prototype.encode = function encode() {
			var encodingResult;
			var bytes = this.bytes;
			// Remove the startcode from the bytes and set its index
			var startIndex = bytes.shift() - 105;

			// Start encode with the right type
			if (startIndex === 103) {
				encodingResult = this.nextA(bytes, 1);
			} else if (startIndex === 104) {
				encodingResult = this.nextB(bytes, 1);
			} else if (startIndex === 105) {
				encodingResult = this.nextC(bytes, 1);
			}

			return {
				text: this.string.replace(/[^\x20-\x7E]/g, ""),
				data:
				// Add the start bits
				this.getEncoding(startIndex) +
				// Add the encoded bits
				encodingResult.result +
				// Add the checksum
				this.getEncoding((encodingResult.checksum + startIndex) % 103) +
				// Add the end bits
				this.getEncoding(106)
			};
		};

		CODE128.prototype.getEncoding = function getEncoding(n) {
			return this.encodings[n] ? (this.encodings[n] + 1000).toString(2) : '';
		};

		// Use the regexp variable for validation


		CODE128.prototype.valid = function valid() {
			// ASCII value ranges 0-127, 200-211
			return this.string.search(/^[\x00-\x7F\xC8-\xD3]+$/) !== -1;
		};

		CODE128.prototype.nextA = function nextA(bytes, depth) {
			if (bytes.length <= 0) {
				return { "result": "", "checksum": 0 };
			}

			var next, index;

			// Special characters
			if (bytes[0] >= 200) {
				index = bytes[0] - 105;

				// Remove first element
				bytes.shift();

				// Swap to CODE128C
				if (index === 99) {
					next = this.nextC(bytes, depth + 1);
				}
				// Swap to CODE128B
				else if (index === 100) {
						next = this.nextB(bytes, depth + 1);
					}
					// Shift
					else if (index === 98) {
							// Convert the next character so that is encoded correctly
							bytes[0] = bytes[0] > 95 ? bytes[0] - 96 : bytes[0];
							next = this.nextA(bytes, depth + 1);
						}
						// Continue on CODE128A but encode a special character
						else {
								next = this.nextA(bytes, depth + 1);
							}
			}
			// Continue encoding of CODE128A
			else {
					var charCode = bytes[0];
					index = charCode < 32 ? charCode + 64 : charCode - 32;

					// Remove first element
					bytes.shift();

					next = this.nextA(bytes, depth + 1);
				}

			// Get the correct binary encoding and calculate the weight
			var enc = this.getEncoding(index);
			var weight = index * depth;

			return {
				"result": enc + next.result,
				"checksum": weight + next.checksum
			};
		};

		CODE128.prototype.nextB = function nextB(bytes, depth) {
			if (bytes.length <= 0) {
				return { "result": "", "checksum": 0 };
			}

			var next, index;

			// Special characters
			if (bytes[0] >= 200) {
				index = bytes[0] - 105;

				// Remove first element
				bytes.shift();

				// Swap to CODE128C
				if (index === 99) {
					next = this.nextC(bytes, depth + 1);
				}
				// Swap to CODE128A
				else if (index === 101) {
						next = this.nextA(bytes, depth + 1);
					}
					// Shift
					else if (index === 98) {
							// Convert the next character so that is encoded correctly
							bytes[0] = bytes[0] < 32 ? bytes[0] + 96 : bytes[0];
							next = this.nextB(bytes, depth + 1);
						}
						// Continue on CODE128B but encode a special character
						else {
								next = this.nextB(bytes, depth + 1);
							}
			}
			// Continue encoding of CODE128B
			else {
					index = bytes[0] - 32;
					bytes.shift();
					next = this.nextB(bytes, depth + 1);
				}

			// Get the correct binary encoding and calculate the weight
			var enc = this.getEncoding(index);
			var weight = index * depth;

			return { "result": enc + next.result, "checksum": weight + next.checksum };
		};

		CODE128.prototype.nextC = function nextC(bytes, depth) {
			if (bytes.length <= 0) {
				return { "result": "", "checksum": 0 };
			}

			var next, index;

			// Special characters
			if (bytes[0] >= 200) {
				index = bytes[0] - 105;

				// Remove first element
				bytes.shift();

				// Swap to CODE128B
				if (index === 100) {
					next = this.nextB(bytes, depth + 1);
				}
				// Swap to CODE128A
				else if (index === 101) {
						next = this.nextA(bytes, depth + 1);
					}
					// Continue on CODE128C but encode a special character
					else {
							next = this.nextC(bytes, depth + 1);
						}
			}
			// Continue encoding of CODE128C
			else {
					index = (bytes[0] - 48) * 10 + bytes[1] - 48;
					bytes.shift();
					bytes.shift();
					next = this.nextC(bytes, depth + 1);
				}

			// Get the correct binary encoding and calculate the weight
			var enc = this.getEncoding(index);
			var weight = index * depth;

			return { "result": enc + next.result, "checksum": weight + next.checksum };
		};

		return CODE128;
	}();

	exports.default = CODE128;

/***/ },
/* 3 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.mod10 = mod10;
	exports.mod11 = mod11;
	function mod10(number) {
		var sum = 0;
		for (var i = 0; i < number.length; i++) {
			var n = parseInt(number[i]);
			if ((i + number.length) % 2 === 0) {
				sum += n;
			} else {
				sum += n * 2 % 10 + Math.floor(n * 2 / 10);
			}
		}
		return (10 - sum % 10) % 10;
	}

	function mod11(number) {
		var sum = 0;
		var weights = [2, 3, 4, 5, 6, 7];
		for (var i = 0; i < number.length; i++) {
			var n = parseInt(number[number.length - 1 - i]);
			sum += weights[i % weights.length] * n;
		}
		return (11 - sum % 11) % 11;
	}

/***/ },
/* 4 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.default = merge;


	function merge(old, replaceObj) {
	  var newMerge = {};
	  var k;
	  for (k in old) {
	    if (old.hasOwnProperty(k)) {
	      newMerge[k] = old[k];
	    }
	  }
	  for (k in replaceObj) {
	    if (replaceObj.hasOwnProperty(k) && typeof replaceObj[k] !== "undefined") {
	      newMerge[k] = replaceObj[k];
	    }
	  }
	  return newMerge;
	}

/***/ },
/* 5 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});

	var _CODE = __webpack_require__(16);

	var _CODE2 = __webpack_require__(15);

	var _EAN_UPC = __webpack_require__(22);

	var _ITF = __webpack_require__(24);

	var _MSI = __webpack_require__(29);

	var _pharmacode = __webpack_require__(30);

	var _GenericBarcode = __webpack_require__(23);

	exports.default = {
	  CODE39: _CODE.CODE39,
	  CODE128: _CODE2.CODE128, CODE128A: _CODE2.CODE128A, CODE128B: _CODE2.CODE128B, CODE128C: _CODE2.CODE128C,
	  EAN13: _EAN_UPC.EAN13, EAN8: _EAN_UPC.EAN8, EAN5: _EAN_UPC.EAN5, EAN2: _EAN_UPC.EAN2, UPC: _EAN_UPC.UPC,
	  ITF14: _ITF.ITF14,
	  MSI: _MSI.MSI, MSI10: _MSI.MSI10, MSI11: _MSI.MSI11, MSI1010: _MSI.MSI1010, MSI1110: _MSI.MSI1110,
	  pharmacode: _pharmacode.pharmacode,
	  GenericBarcode: _GenericBarcode.GenericBarcode
	};

/***/ },
/* 6 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.default = fixOptions;


	function fixOptions(options) {
		// Fix the margins
		options.marginTop = options.marginTop || options.margin;
		options.marginBottom = options.marginBottom || options.margin;
		options.marginRight = options.marginRight || options.margin;
		options.marginLeft = options.marginLeft || options.margin;

		return options;
	}

/***/ },
/* 7 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.default = linearizeEncodings;

	// Encodings can be nestled like [[1-1, 1-2], 2, [3-1, 3-2]
	// Convert to [1-1, 1-2, 2, 3-1, 3-2]

	function linearizeEncodings(encodings) {
	  var linearEncodings = [];
	  function nextLevel(encoded) {
	    if (Array.isArray(encoded)) {
	      for (var i = 0; i < encoded.length; i++) {
	        nextLevel(encoded[i]);
	      }
	    } else {
	      encoded.text = encoded.text || "";
	      encoded.data = encoded.data || "";
	      linearEncodings.push(encoded);
	    }
	  }
	  nextLevel(encodings);

	  return linearEncodings;
	}

/***/ },
/* 8 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _merge = __webpack_require__(4);

	var _merge2 = _interopRequireDefault(_merge);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = renderCanvas;


	function renderCanvas(canvas, encodings, options) {
		// Abort if the browser does not support HTML5 canvas
		if (!canvas.getContext) {
			throw new Error('The browser does not support canvas.');
		}

		prepareCanvas(canvas, options, encodings);
		for (var i = 0; i < encodings.length; i++) {
			var encodingOptions = (0, _merge2.default)(options, encodings[i].options);

			drawCanvasBarcode(canvas, encodingOptions, encodings[i]);
			drawCanvasText(canvas, encodingOptions, encodings[i]);

			moveCanvasDrawing(canvas, encodings[i]);
		}

		restoreCanvas(canvas);
	}

	function prepareCanvas(canvas, options, encodings) {
		// Get the canvas context
		var ctx = canvas.getContext("2d");

		ctx.save();

		// Calculate total width
		var totalWidth = 0;
		var maxHeight = 0;
		for (var i = 0; i < encodings.length; i++) {
			var _options = (0, _merge2.default)(_options, encodings[i].options);

			// Set font
			ctx.font = _options.fontOptions + " " + _options.fontSize + "px " + _options.font;

			// Calculate the width of the encoding
			var textWidth = ctx.measureText(encodings[i].text).width;
			var barcodeWidth = encodings[i].data.length * _options.width;
			encodings[i].width = Math.ceil(Math.max(textWidth, barcodeWidth));

			// Calculate the height of the encoding
			var height = _options.height + (_options.displayValue && encodings[i].text.length > 0 ? _options.fontSize : 0) + _options.textMargin + _options.marginTop + _options.marginBottom;

			var barcodePadding = 0;
			if (_options.displayValue && barcodeWidth < textWidth) {
				if (_options.textAlign == "center") {
					barcodePadding = Math.floor((textWidth - barcodeWidth) / 2);
				} else if (_options.textAlign == "left") {
					barcodePadding = 0;
				} else if (_options.textAlign == "right") {
					barcodePadding = Math.floor(textWidth - barcodeWidth);
				}
			}
			encodings[i].barcodePadding = barcodePadding;

			if (height > maxHeight) {
				maxHeight = height;
			}

			totalWidth += encodings[i].width;
		}

		canvas.width = totalWidth + options.marginLeft + options.marginRight;

		canvas.height = maxHeight;

		// Paint the canvas
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		if (options.background) {
			ctx.fillStyle = options.background;
			ctx.fillRect(0, 0, canvas.width, canvas.height);
		}

		ctx.translate(options.marginLeft, 0);
	}

	function drawCanvasBarcode(canvas, options, encoding) {
		// Get the canvas context
		var ctx = canvas.getContext("2d");

		var binary = encoding.data;

		// Creates the barcode out of the encoded binary
		var yFrom, yHeight;
		if (options.textPosition == "top") {
			yFrom = options.marginTop + options.fontSize + options.textMargin;
		} else {
			yFrom = options.marginTop;
		}
		yHeight = options.height;

		ctx.fillStyle = options.lineColor;

		for (var b = 0; b < binary.length; b++) {
			var x = b * options.width + encoding.barcodePadding;

			if (binary[b] === "1") {
				ctx.fillRect(x, yFrom, options.width, options.height);
			} else if (binary[b]) {
				ctx.fillRect(x, yFrom, options.width, options.height * binary[b]);
			}
		}
	}

	function drawCanvasText(canvas, options, encoding) {
		// Get the canvas context
		var ctx = canvas.getContext("2d");

		var font = options.fontOptions + " " + options.fontSize + "px " + options.font;

		// Draw the text if displayValue is set
		if (options.displayValue) {
			var x, y;

			if (options.textPosition == "top") {
				y = options.marginTop + options.fontSize - options.textMargin;
			} else {
				y = options.height + options.textMargin + options.marginTop + options.fontSize;
			}

			ctx.font = font;

			// Draw the text in the correct X depending on the textAlign option
			if (options.textAlign == "left" || encoding.barcodePadding > 0) {
				x = 0;
				ctx.textAlign = 'left';
			} else if (options.textAlign == "right") {
				x = encoding.width - 1;
				ctx.textAlign = 'right';
			}
			// In all other cases, center the text
			else {
					x = encoding.width / 2;
					ctx.textAlign = 'center';
				}

			ctx.fillText(encoding.text, x, y);
		}
	}

	function moveCanvasDrawing(canvas, encoding) {
		var ctx = canvas.getContext("2d");

		ctx.translate(encoding.width, 0);
	}

	function restoreCanvas(canvas) {
		// Get the canvas context
		var ctx = canvas.getContext("2d");

		ctx.restore();
	}

/***/ },
/* 9 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});

	var _merge = __webpack_require__(4);

	var _merge2 = _interopRequireDefault(_merge);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = renderSVG;


	var svgns = "http://www.w3.org/2000/svg";

	function renderSVG(svg, encodings, options) {
	  var currentX = options.marginLeft;

	  prepareSVG(svg, options, encodings);
	  for (var i = 0; i < encodings.length; i++) {
	    var encodingOptions = (0, _merge2.default)(options, encodings[i].options);

	    var group = createGroup(currentX, encodingOptions.marginTop, svg);

	    setGroupOptions(group, encodingOptions, encodings[i]);

	    drawSvgBarcode(group, encodingOptions, encodings[i]);
	    drawSVGText(group, encodingOptions, encodings[i]);

	    currentX += encodings[i].width;
	  }
	}

	function prepareSVG(svg, options, encodings) {
	  // Clear the SVG
	  while (svg.firstChild) {
	    svg.removeChild(svg.firstChild);
	  }

	  var totalWidth = 0;
	  var maxHeight = 0;
	  for (var i = 0; i < encodings.length; i++) {
	    var _options = (0, _merge2.default)(_options, encodings[i].options);

	    // Calculate the width of the encoding
	    var textWidth = messureSVGtext(encodings[i].text, svg, _options);
	    var barcodeWidth = encodings[i].data.length * _options.width;
	    encodings[i].width = Math.ceil(Math.max(textWidth, barcodeWidth));

	    // Calculate the height of the encoding
	    var encodingHeight = _options.height + (_options.displayValue && encodings[i].text.length > 0 ? _options.fontSize : 0) + _options.textMargin + _options.marginTop + _options.marginBottom;

	    var barcodePadding = 0;
	    if (_options.displayValue && barcodeWidth < textWidth) {
	      if (_options.textAlign == "center") {
	        barcodePadding = Math.floor((textWidth - barcodeWidth) / 2);
	      } else if (_options.textAlign == "left") {
	        barcodePadding = 0;
	      } else if (_options.textAlign == "right") {
	        barcodePadding = Math.floor(textWidth - barcodeWidth);
	      }
	    }
	    encodings[i].barcodePadding = barcodePadding;

	    if (encodingHeight > maxHeight) {
	      maxHeight = encodingHeight;
	    }

	    totalWidth += encodings[i].width;
	  }

	  var width = totalWidth + options.marginLeft + options.marginRight;
	  var height = maxHeight;

	  svg.setAttribute("width", width + "px");
	  svg.setAttribute("height", height + "px");
	  svg.setAttribute("x", "0px");
	  svg.setAttribute("y", "0px");
	  svg.setAttribute("viewBox", "0 0 " + width + " " + height);

	  svg.style.transform = "translate(0,0)";

	  if (options.background) {
	    svg.style.background = options.background;
	  }
	}

	function drawSvgBarcode(parent, options, encoding) {
	  var binary = encoding.data;

	  // Creates the barcode out of the encoded binary
	  var yFrom, yHeight;
	  if (options.textPosition == "top") {
	    yFrom = options.fontSize + options.textMargin;
	  } else {
	    yFrom = 0;
	  }
	  yHeight = options.height;

	  var barWidth = 0;
	  for (var b = 0; b < binary.length; b++) {
	    var x = b * options.width + encoding.barcodePadding;

	    if (binary[b] === "1") {
	      barWidth++;
	    } else if (barWidth > 0) {
	      drawLine(x - options.width * barWidth, yFrom, options.width * barWidth, options.height, parent);
	      barWidth = 0;
	    }
	  }

	  // Last draw is needed since the barcode ends with 1
	  if (barWidth > 0) {
	    drawLine(x - options.width * (barWidth - 1), yFrom, options.width * barWidth, options.height, parent);
	  }
	}

	function drawSVGText(parent, options, encoding) {
	  var textElem = document.createElementNS(svgns, 'text');

	  // Draw the text if displayValue is set
	  if (options.displayValue) {
	    var x, y;

	    textElem.setAttribute("style", "font:" + options.fontOptions + " " + options.fontSize + "px " + options.font);

	    if (options.textPosition == "top") {
	      y = options.fontSize - options.textMargin;
	    } else {
	      y = options.height + options.textMargin + options.fontSize;
	    }

	    // Draw the text in the correct X depending on the textAlign option
	    if (options.textAlign == "left" || encoding.barcodePadding > 0) {
	      x = 0;
	      textElem.setAttribute("text-anchor", "start");
	    } else if (options.textAlign == "right") {
	      x = encoding.width - 1;
	      textElem.setAttribute("text-anchor", "end");
	    }
	    // In all other cases, center the text
	    else {
	        x = encoding.width / 2;
	        textElem.setAttribute("text-anchor", "middle");
	      }

	    textElem.setAttribute("x", x);
	    textElem.setAttribute("y", y);

	    textElem.appendChild(document.createTextNode(encoding.text));

	    parent.appendChild(textElem);
	  }
	}

	//
	// Help functions
	//
	function messureSVGtext(string, svg, options) {
	  // Create text element
	  /* var text = document.createElementNS(svgns, 'text');
	  text.style.fontFamily = options.font;
	    text.setAttribute("style",
	     "font-family:" + options.font + ";" +
	     "font-size:" + options.fontSize + "px;"
	   );
	  	var textNode = document.createTextNode(string);
	  	text.appendChild(textNode);
	    svg.appendChild(text);
	    var size = text.getComputedTextLength();
	    svg.removeChild(text);
	   */
	  // TODO: Use svg to messure the text width
	  // Set font
	  var ctx = document.createElement("canvas").getContext("2d");
	  ctx.font = options.fontOptions + " " + options.fontSize + "px " + options.font;

	  // Calculate the width of the encoding
	  var size = ctx.measureText(string).width;

	  return size;
	}

	function createGroup(x, y, svg) {
	  var group = document.createElementNS(svgns, 'g');

	  group.setAttribute("transform", "translate(" + x + ", " + y + ")");

	  svg.appendChild(group);

	  return group;
	}

	function setGroupOptions(group, options, encoding) {
	  group.setAttribute("style", "fill:" + options.lineColor + ";");
	}

	function drawLine(x, y, width, height, parent) {
	  var line = document.createElementNS(svgns, 'rect');

	  line.setAttribute("x", x);
	  line.setAttribute("y", y);
	  line.setAttribute("width", width);
	  line.setAttribute("height", height);

	  parent.appendChild(line);
	}

/***/ },
/* 10 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _barcodes = __webpack_require__(5);

	var _barcodes2 = _interopRequireDefault(_barcodes);

	var _canvas = __webpack_require__(8);

	var _canvas2 = _interopRequireDefault(_canvas);

	var _svg = __webpack_require__(9);

	var _svg2 = _interopRequireDefault(_svg);

	var _merge = __webpack_require__(4);

	var _merge2 = _interopRequireDefault(_merge);

	var _linearizeEncodings = __webpack_require__(7);

	var _linearizeEncodings2 = _interopRequireDefault(_linearizeEncodings);

	var _fixOptions = __webpack_require__(6);

	var _fixOptions2 = _interopRequireDefault(_fixOptions);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	// Import the renderers

	var renderers = {
		"canvas": _canvas2.default,
		"svg": _svg2.default
	};

	// Help functions
	// Import all the barcodes


	// The protype of the object returned from the JsBarcode() call
	var API = function API() {};

	// The first call of the library API
	// Will return an object with all barcodes calls and the information needed
	// when the rendering function is called and options the barcodes might need
	var JsBarcode = function JsBarcode(element, text, options) {
		var api = new API();

		if (typeof element === "undefined") {
			throw Error("No element to render on was provided.");
		}

		// Variables that will be pased through the API calls
		api._renderProperties = getRenderProperies(element);
		api._encodings = [];
		api._options = defaults;

		// If text is set, use simple syntax
		if (typeof text !== "undefined") {
			options = options || {};

			if (!options.format) {
				options.format = autoSelectBarcode();
			}

			api.options(options);
			api[options.format](text, options);
			api.render();
		}

		return api;
	};

	// To make tests work TODO: remove
	JsBarcode.getModule = function (name) {
		return _barcodes2.default[name];
	};

	// Register all barcodes
	for (var name in _barcodes2.default) {
		if (_barcodes2.default.hasOwnProperty(name)) {
			// Security check if the propery is a prototype property
			registerBarcode(_barcodes2.default, name);
		}
	}
	function registerBarcode(barcodes, name) {
		API.prototype[name] = API.prototype[name.toUpperCase()] = API.prototype[name.toLowerCase()] = function (text, options) {
			var newOptions = (0, _merge2.default)(this._options, options);

			var Encoder = barcodes[name];
			var encoder = new Encoder(text, newOptions);

			// If the input is not valid for the encoder, throw error.
			// If the valid callback option is set, call it instead of throwing error
			if (!encoder.valid()) {
				if (this._options.valid === defaults.valid) {
					throw new Error('"' + text + '" is not a valid input for ' + name);
				} else {
					this._options.valid(false);
				}
			}

			var encoded = encoder.encode();

			// Encodings can be nestled like [[1-1, 1-2], 2, [3-1, 3-2]
			// Convert to [1-1, 1-2, 2, 3-1, 3-2]
			encoded = (0, _linearizeEncodings2.default)(encoded);

			for (var i = 0; i < encoded.length; i++) {
				encoded[i].options = (0, _merge2.default)(newOptions, encoded[i].options);
			}

			this._encodings.push(encoded);

			return this;
		};
	}

	function autoSelectBarcode() {
		// If CODE128 exists. Use it
		if (_barcodes2.default["CODE128"]) {
			return "CODE128";
		}

		// Else, take the first (probably only) barcode
		return Object.keys(_barcodes2.default)[0];
	}

	// Sets global encoder options
	// Added to the api by the JsBarcode function
	API.prototype.options = function (options) {
		this._options = (0, _merge2.default)(this._options, options);
		return this;
	};

	// Will create a blank space (usually in between barcodes)
	API.prototype.blank = function (size) {
		var zeroes = "0".repeat(size);
		this._encodings.push({ data: zeroes });
		return this;
	};

	// Prepares the encodings and calls the renderer
	// Added to the api by the JsBarcode function
	API.prototype.render = function () {
		var renderer = renderers[this._renderProperties.renderer];

		var encodings = (0, _linearizeEncodings2.default)(this._encodings);

		for (var i = 0; i < encodings.length; i++) {
			encodings[i].options = (0, _merge2.default)(this._options, encodings[i].options);
			(0, _fixOptions2.default)(encodings[i].options);
		}

		(0, _fixOptions2.default)(this._options);

		renderer(this._renderProperties.element, encodings, this._options);

		if (this._renderProperties.afterRender) {
			this._renderProperties.afterRender();
		}

		this._options.valid(true);

		return this;
	};

	// Export to browser
	if (typeof window !== "undefined") {
		window.JsBarcode = JsBarcode;
	}

	// Export to jQuery
	if (typeof jQuery !== 'undefined') {
		jQuery.fn.JsBarcode = function (content, options) {
			return JsBarcode(this.get(0), content, options);
		};
	}

	// Export to commonJS
	module.exports = JsBarcode;

	// Takes an element and returns an object with information about how
	// it should be rendered
	// {
	//   element: The element that the renderer should draw on
	//   renderer: The name of the renderer
	//   afterRender (optional): If something has to done after the renderer
	//     completed, calls afterRender (function)
	// }
	function getRenderProperies(element) {
		// If the element is a string, query select call again
		if (typeof element === "string") {
			element = document.querySelector(element);
			return getRenderProperies(element);
		}
		// If element, render on canvas and set the uri as src
		else if (typeof HTMLCanvasElement !== 'undefined' && element instanceof HTMLImageElement) {
				var canvas = document.createElement('canvas');
				return {
					element: canvas,
					renderer: "canvas",
					afterRender: function afterRender() {
						element.setAttribute("src", canvas.toDataURL());
					}
				};
			}
			// If SVG
			else if (typeof SVGElement !== 'undefined' && element instanceof SVGElement) {
					return {
						element: element,
						renderer: "svg"
					};
				}
				// If canvas
				else if (element.getContext) {
						return {
							element: element,
							renderer: "canvas"
						};
					} else {
						throw new Error("Not supported type to render on.");
					}
	}

	var defaults = {
		width: 2,
		height: 100,
		format: "auto",
		displayValue: true,
		fontOptions: "",
		font: "monospace",
		textAlign: "center",
		textPosition: "bottom",
		textMargin: 2,
		fontSize: 20,
		background: "#ffffff",
		lineColor: "#000000",
		margin: 10,
		marginTop: undefined,
		marginBottom: undefined,
		marginLeft: undefined,
		marginRight: undefined,
		valid: function valid(_valid) {}
	};

/***/ },
/* 11 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _CODE2 = __webpack_require__(2);

	var _CODE3 = _interopRequireDefault(_CODE2);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var CODE128A = function (_CODE) {
		_inherits(CODE128A, _CODE);

		function CODE128A(string) {
			_classCallCheck(this, CODE128A);

			return _possibleConstructorReturn(this, _CODE.call(this, String.fromCharCode(208) + string));
		}

		CODE128A.prototype.valid = function valid() {
			return this.string.search(/^[\x00-\x5F\xC8-\xCF]+$/) !== -1;
		};

		return CODE128A;
	}(_CODE3.default);

	exports.default = CODE128A;

/***/ },
/* 12 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _CODE2 = __webpack_require__(2);

	var _CODE3 = _interopRequireDefault(_CODE2);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var CODE128B = function (_CODE) {
		_inherits(CODE128B, _CODE);

		function CODE128B(string) {
			_classCallCheck(this, CODE128B);

			return _possibleConstructorReturn(this, _CODE.call(this, String.fromCharCode(209) + string));
		}

		CODE128B.prototype.valid = function valid() {
			return this.string.search(/^[\x20-\x7F\xC8-\xCF]+$/) !== -1;
		};

		return CODE128B;
	}(_CODE3.default);

	exports.default = CODE128B;

/***/ },
/* 13 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _CODE2 = __webpack_require__(2);

	var _CODE3 = _interopRequireDefault(_CODE2);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var CODE128C = function (_CODE) {
		_inherits(CODE128C, _CODE);

		function CODE128C(string) {
			_classCallCheck(this, CODE128C);

			return _possibleConstructorReturn(this, _CODE.call(this, String.fromCharCode(210) + string));
		}

		CODE128C.prototype.valid = function valid() {
			return this.string.search(/^(\xCF*[0-9]{2}\xCF*)+$/) !== -1;
		};

		return CODE128C;
	}(_CODE3.default);

	exports.default = CODE128C;

/***/ },
/* 14 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _CODE2 = __webpack_require__(2);

	var _CODE3 = _interopRequireDefault(_CODE2);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var CODE128AUTO = function (_CODE) {
		_inherits(CODE128AUTO, _CODE);

		function CODE128AUTO(string) {
			_classCallCheck(this, CODE128AUTO);

			// ASCII value ranges 0-127, 200-211

			var _this = _possibleConstructorReturn(this, _CODE.call(this, string));

			if (string.search(/^[\x00-\x7F\xC8-\xD3]+$/) !== -1) {
				var _this = _possibleConstructorReturn(this, _CODE.call(this, autoSelectModes(string)));
			} else {
				var _this = _possibleConstructorReturn(this, _CODE.call(this, string));
			}
			return _possibleConstructorReturn(_this);
		}

		return CODE128AUTO;
	}(_CODE3.default);

	function autoSelectModes(string) {
		// ASCII ranges 0-98 and 200-207 (FUNCs and SHIFTs)
		var aLength = string.match(/^[\x00-\x5F\xC8-\xCF]*/)[0].length;
		// ASCII ranges 32-127 and 200-207 (FUNCs and SHIFTs)
		var bLength = string.match(/^[\x20-\x7F\xC8-\xCF]*/)[0].length;
		// Number pairs or [FNC1]
		var cLength = string.match(/^(\xCF*[0-9]{2}\xCF*)*/)[0].length;

		var newString;
		// Select CODE128C if the string start with enough digits
		if (cLength >= 2) {
			newString = String.fromCharCode(210) + autoSelectFromC(string);
		}
		// Select A/C depending on the longest match
		else if (aLength > bLength) {
				newString = String.fromCharCode(208) + autoSelectFromA(string);
			} else {
				newString = String.fromCharCode(209) + autoSelectFromB(string);
			}

		newString = newString.replace(/[\xCD\xCE]([^])[\xCD\xCE]/, function (match, char) {
			return String.fromCharCode(203) + char;
		});

		return newString;
	}

	function autoSelectFromA(string) {
		var untilC = string.match(/^([\x00-\x5F\xC8-\xCF]+?)(([0-9]{2}){2,})([^0-9]|$)/);

		if (untilC) {
			return untilC[1] + String.fromCharCode(204) + autoSelectFromC(string.substring(untilC[1].length));
		}

		var aChars = string.match(/^[\x00-\x5F\xC8-\xCF]+/);
		if (aChars[0].length === string.length) {
			return string;
		}

		return aChars[0] + String.fromCharCode(205) + autoSelectFromB(string.substring(aChars[0].length));
	}

	function autoSelectFromB(string) {
		var untilC = string.match(/^([\x20-\x7F\xC8-\xCF]+?)(([0-9]{2}){2,})([^0-9]|$)/);

		if (untilC) {
			return untilC[1] + String.fromCharCode(204) + autoSelectFromC(string.substring(untilC[1].length));
		}

		var bChars = string.match(/^[\x20-\x7F\xC8-\xCF]+/);
		if (bChars[0].length === string.length) {
			return string;
		}

		return bChars[0] + String.fromCharCode(206) + autoSelectFromA(string.substring(bChars[0].length));
	}

	function autoSelectFromC(string) {
		var cMatch = string.match(/^(\xCF*[0-9]{2}\xCF*)+/)[0];
		var length = cMatch.length;

		if (length === string.length) {
			return string;
		}

		string = string.substring(length);

		// Select A/B depending on the longest match
		var aLength = string.match(/^[\x00-\x5F\xC8-\xCF]*/)[0].length;
		var bLength = string.match(/^[\x20-\x7F\xC8-\xCF]*/)[0].length;
		if (aLength >= bLength) {
			return cMatch + String.fromCharCode(206) + autoSelectFromA(string);
		} else {
			return cMatch + String.fromCharCode(205) + autoSelectFromB(string);
		}
	}

	exports.default = CODE128AUTO;

/***/ },
/* 15 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.CODE128C = exports.CODE128B = exports.CODE128A = exports.CODE128 = undefined;

	var _CODE128_AUTO = __webpack_require__(14);

	var _CODE128_AUTO2 = _interopRequireDefault(_CODE128_AUTO);

	var _CODE128A = __webpack_require__(11);

	var _CODE128A2 = _interopRequireDefault(_CODE128A);

	var _CODE128B = __webpack_require__(12);

	var _CODE128B2 = _interopRequireDefault(_CODE128B);

	var _CODE128C = __webpack_require__(13);

	var _CODE128C2 = _interopRequireDefault(_CODE128C);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.CODE128 = _CODE128_AUTO2.default;
	exports.CODE128A = _CODE128A2.default;
	exports.CODE128B = _CODE128B2.default;
	exports.CODE128C = _CODE128C2.default;

/***/ },
/* 16 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	// Encoding documentation:
	// https://en.wikipedia.org/wiki/Code_39#Encoding

	var CODE39 = function () {
		function CODE39(string, options) {
			_classCallCheck(this, CODE39);

			this.string = string.toUpperCase();

			// Enable mod43 checksum?
			this.mod43Enabled = options.mod43 || false;

			// All characters. The position in the array is the (checksum) value
			this.characters = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "-", ".", " ", "$", "/", "+", "%", "*"];

			// The decimal representation of the characters, is converted to the
			// corresponding binary with the getEncoding function
			this.encodings = [20957, 29783, 23639, 30485, 20951, 29813, 23669, 20855, 29789, 23645, 29975, 23831, 30533, 22295, 30149, 24005, 21623, 29981, 23837, 22301, 30023, 23879, 30545, 22343, 30161, 24017, 21959, 30065, 23921, 22385, 29015, 18263, 29141, 17879, 29045, 18293, 17783, 29021, 18269, 17477, 17489, 17681, 20753, 35770];
		}

		// Get the binary representation of a character by converting the encodings
		// from decimal to binary


		CODE39.prototype.getEncoding = function getEncoding(character) {
			return this.getBinary(this.characterValue(character));
		};

		CODE39.prototype.getBinary = function getBinary(characterValue) {
			return this.encodings[characterValue].toString(2);
		};

		CODE39.prototype.getCharacter = function getCharacter(characterValue) {
			return this.characters[characterValue];
		};

		CODE39.prototype.characterValue = function characterValue(character) {
			return this.characters.indexOf(character);
		};

		CODE39.prototype.encode = function encode() {
			var string = this.string;

			// First character is always a *
			var result = this.getEncoding("*");

			// Take every character and add the binary representation to the result
			for (var i = 0; i < this.string.length; i++) {
				result += this.getEncoding(this.string[i]) + "0";
			}

			// Calculate mod43 checksum if enabled
			if (this.mod43Enabled) {
				var checksum = 0;
				for (var i = 0; i < this.string.length; i++) {
					checksum += this.characterValue(this.string[i]);
				}

				checksum = checksum % 43;

				result += this.getBinary(checksum) + "0";
				string += this.getCharacter(checksum);
			}

			// Last character is always a *
			result += this.getEncoding("*");

			return {
				data: result,
				text: string
			};
		};

		CODE39.prototype.valid = function valid() {
			return this.string.search(/^[0-9A-Z\-\.\ \$\/\+\%]+$/) !== -1;
		};

		return CODE39;
	}();

	exports.CODE39 = CODE39;

/***/ },
/* 17 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _ean_encoder = __webpack_require__(0);

	var _ean_encoder2 = _interopRequireDefault(_ean_encoder);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } } // Encoding documentation:
	// https://en.wikipedia.org/wiki/International_Article_Number_(EAN)#Binary_encoding_of_data_digits_into_EAN-13_barcode

	var EAN13 = function () {
		function EAN13(string, options) {
			_classCallCheck(this, EAN13);

			// Add checksum if it does not exist
			if (string.search(/^[0-9]{12}$/) !== -1) {
				this.string = string + this.checksum(string);
			} else {
				this.string = string;
			}

			// Define the EAN-13 structure
			this.structure = ["LLLLLL", "LLGLGG", "LLGGLG", "LLGGGL", "LGLLGG", "LGGLLG", "LGGGLL", "LGLGLG", "LGLGGL", "LGGLGL"];

			// Make sure the font is not bigger than the space between the guard bars
			if (options.fontSize > options.width * 10) {
				this.fontSize = options.width * 10;
			} else {
				this.fontSize = options.fontSize;
			}

			// Make the guard bars go down half the way of the text
			this.guardHeight = options.height + this.fontSize / 2 + options.textMargin;

			// Adds a last character to the end of the barcode
			this.lastChar = options.lastChar;
		}

		EAN13.prototype.valid = function valid() {
			return this.string.search(/^[0-9]{13}$/) !== -1 && this.string[12] == this.checksum(this.string);
		};

		EAN13.prototype.encode = function encode() {
			var encoder = new _ean_encoder2.default();
			var result = [];

			var structure = this.structure[this.string[0]];

			// Get the string to be encoded on the left side of the EAN code
			var leftSide = this.string.substr(1, 6);

			// Get the string to be encoded on the right side of the EAN code
			var rightSide = this.string.substr(7, 6);

			// Add the first digigt
			result.push({
				data: "000000000000",
				text: this.string[0],
				options: { textAlign: "left", fontSize: this.fontSize }
			});

			// Add the guard bars
			result.push({
				data: "101",
				options: { height: this.guardHeight }
			});

			// Add the left side
			result.push({
				data: encoder.encode(leftSide, structure),
				text: leftSide,
				options: { fontSize: this.fontSize }
			});

			// Add the middle bits
			result.push({
				data: "01010",
				options: { height: this.guardHeight }
			});

			// Add the right side
			result.push({
				data: encoder.encode(rightSide, "RRRRRR"),
				text: rightSide,
				options: { fontSize: this.fontSize }
			});

			// Add the end bits
			result.push({
				data: "101",
				options: { height: this.guardHeight }
			});

			if (this.lastChar) {
				result.push({ data: "00" });

				result.push({
					data: "00000",
					text: this.lastChar,
					options: { fontSize: this.fontSize }
				});
			}

			return result;
		};

		// Calulate the checksum digit
		// https://en.wikipedia.org/wiki/International_Article_Number_(EAN)#Calculation_of_checksum_digit


		EAN13.prototype.checksum = function checksum(number) {
			var result = 0;

			var i;
			for (i = 0; i < 12; i += 2) {
				result += parseInt(number[i]);
			}
			for (i = 1; i < 12; i += 2) {
				result += parseInt(number[i]) * 3;
			}

			return (10 - result % 10) % 10;
		};

		return EAN13;
	}();

	exports.default = EAN13;

/***/ },
/* 18 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _ean_encoder = __webpack_require__(0);

	var _ean_encoder2 = _interopRequireDefault(_ean_encoder);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } } // Encoding documentation:
	// https://en.wikipedia.org/wiki/EAN_2#Encoding

	var EAN2 = function () {
		function EAN2(string) {
			_classCallCheck(this, EAN2);

			this.string = string;

			this.structure = ["LL", "LG", "GL", "GG"];
		}

		EAN2.prototype.valid = function valid() {
			return this.string.search(/^[0-9]{2}$/) !== -1;
		};

		EAN2.prototype.encode = function encode() {
			var encoder = new _ean_encoder2.default();

			// Choose the structure based on the number mod 4
			var structure = this.structure[parseInt(this.string) % 4];

			// Start bits
			var result = "1011";

			// Encode the two digits with 01 in between
			result += encoder.encode(this.string, structure, "01");

			return {
				data: result,
				text: this.string
			};
		};

		return EAN2;
	}();

	exports.default = EAN2;

/***/ },
/* 19 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _ean_encoder = __webpack_require__(0);

	var _ean_encoder2 = _interopRequireDefault(_ean_encoder);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } } // Encoding documentation:
	// https://en.wikipedia.org/wiki/EAN_5#Encoding

	var EAN5 = function () {
		function EAN5(string) {
			_classCallCheck(this, EAN5);

			this.string = string;

			// Define the EAN-13 structure
			this.structure = ["GGLLL", "GLGLL", "GLLGL", "GLLLG", "LGGLL", "LLGGL", "LLLGG", "LGLGL", "LGLLG", "LLGLG"];
		}

		EAN5.prototype.valid = function valid() {
			return this.string.search(/^[0-9]{5}$/) !== -1;
		};

		EAN5.prototype.encode = function encode() {
			var encoder = new _ean_encoder2.default();
			var checksum = this.checksum();

			// Start bits
			var result = "1011";

			// Use normal ean encoding with 01 in between all digits
			result += encoder.encode(this.string, this.structure[checksum], "01");

			return {
				data: result,
				text: this.string
			};
		};

		EAN5.prototype.checksum = function checksum() {
			var result = 0;

			result += parseInt(this.string[0]) * 3;
			result += parseInt(this.string[1]) * 9;
			result += parseInt(this.string[2]) * 3;
			result += parseInt(this.string[3]) * 9;
			result += parseInt(this.string[4]) * 3;

			return result % 10;
		};

		return EAN5;
	}();

	exports.default = EAN5;

/***/ },
/* 20 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _ean_encoder = __webpack_require__(0);

	var _ean_encoder2 = _interopRequireDefault(_ean_encoder);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } } // Encoding documentation:
	// http://www.barcodeisland.com/ean8.phtml

	var EAN8 = function () {
		function EAN8(string) {
			_classCallCheck(this, EAN8);

			// Add checksum if it does not exist
			if (string.search(/^[0-9]{7}$/) !== -1) {
				this.string = string + this.checksum(string);
			} else {
				this.string = string;
			}
		}

		EAN8.prototype.valid = function valid() {
			return this.string.search(/^[0-9]{8}$/) !== -1 && this.string[7] == this.checksum(this.string);
		};

		EAN8.prototype.encode = function encode() {
			var encoder = new _ean_encoder2.default();

			// Create the return variable
			var result = "";

			// Get the number to be encoded on the left side of the EAN code
			var leftSide = this.string.substr(0, 4);

			// Get the number to be encoded on the right side of the EAN code
			var rightSide = this.string.substr(4, 4);

			// Add the start bits
			result += encoder.startBin;

			// Add the left side
			result += encoder.encode(leftSide, "LLLL");

			// Add the middle bits
			result += encoder.middleBin;

			// Add the right side
			result += encoder.encode(rightSide, "RRRR");

			// Add the end bits
			result += encoder.endBin;

			return {
				data: result,
				text: this.string
			};
		};

		// Calulate the checksum digit


		EAN8.prototype.checksum = function checksum(number) {
			var result = 0;

			var i;
			for (i = 0; i < 7; i += 2) {
				result += parseInt(number[i]) * 3;
			}

			for (i = 1; i < 7; i += 2) {
				result += parseInt(number[i]);
			}

			return (10 - result % 10) % 10;
		};

		return EAN8;
	}();

	exports.default = EAN8;

/***/ },
/* 21 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _ean_encoder = __webpack_require__(0);

	var _ean_encoder2 = _interopRequireDefault(_ean_encoder);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } } // Encoding documentation:
	// https://en.wikipedia.org/wiki/Universal_Product_Code#Encoding

	var UPC = function () {
		function UPC(string, options) {
			_classCallCheck(this, UPC);

			// Add checksum if it does not exist
			if (string.search(/^[0-9]{11}$/) !== -1) {
				this.string = string + this.checksum(string);
			} else {
				this.string = string;
			}

			// Make sure the font is not bigger than the space between the guard bars
			if (options.fontSize > options.width * 10) {
				this.fontSize = options.width * 10;
			} else {
				this.fontSize = options.fontSize;
			}

			// Make the guard bars go down half the way of the text
			this.guardHeight = options.height + this.fontSize / 2 + options.textMargin;
		}

		UPC.prototype.valid = function valid() {
			return this.string.search(/^[0-9]{12}$/) !== -1 && this.string[11] == this.checksum(this.string);
		};

		UPC.prototype.encode = function encode() {
			var encoder = new _ean_encoder2.default();
			var result = [];

			// Get the string to be encoded on the left side of the UPC code
			var leftSide = this.string.substr(0, 6);

			// Get the string to be encoded on the right side of the UPC code
			var rightSide = this.string.substr(6, 6);

			// Add the first digigt
			result.push({
				data: "00000000",
				text: this.string[0],
				options: { textAlign: "left", fontSize: this.fontSize }
			});

			// Add the guard bars
			result.push({
				data: "101" + encoder.encode(this.string[0], "L"),
				options: { height: this.guardHeight }
			});

			// Add the left side
			result.push({
				data: encoder.encode(this.string.substr(1, 5), "LLLLL"),
				text: this.string.substr(1, 5),
				options: { fontSize: this.fontSize }
			});

			// Add the middle bits
			result.push({
				data: "01010",
				options: { height: this.guardHeight }
			});

			// Add the right side
			result.push({
				data: encoder.encode(this.string.substr(6, 5), "RRRRR"),
				text: this.string.substr(6, 5),
				options: { fontSize: this.fontSize }
			});

			// Add the end bits
			result.push({
				data: encoder.encode(this.string[11], "R") + "101",
				options: { height: this.guardHeight }
			});

			// Add the last digit
			result.push({
				data: "00000000",
				text: this.string[11],
				options: { textAlign: "right", fontSize: this.fontSize }
			});

			return result;
		};

		// Calulate the checksum digit
		// https://en.wikipedia.org/wiki/International_Article_Number_(EAN)#Calculation_of_checksum_digit


		UPC.prototype.checksum = function checksum(number) {
			var result = 0;

			var i;
			for (i = 1; i < 11; i += 2) {
				result += parseInt(number[i]);
			}
			for (i = 0; i < 11; i += 2) {
				result += parseInt(number[i]) * 3;
			}

			return (10 - result % 10) % 10;
		};

		return UPC;
	}();

	exports.default = UPC;

/***/ },
/* 22 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.UPC = exports.EAN2 = exports.EAN5 = exports.EAN8 = exports.EAN13 = undefined;

	var _EAN = __webpack_require__(17);

	var _EAN2 = _interopRequireDefault(_EAN);

	var _EAN3 = __webpack_require__(20);

	var _EAN4 = _interopRequireDefault(_EAN3);

	var _EAN5 = __webpack_require__(19);

	var _EAN6 = _interopRequireDefault(_EAN5);

	var _EAN7 = __webpack_require__(18);

	var _EAN8 = _interopRequireDefault(_EAN7);

	var _UPC = __webpack_require__(21);

	var _UPC2 = _interopRequireDefault(_UPC);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.EAN13 = _EAN2.default;
	exports.EAN8 = _EAN4.default;
	exports.EAN5 = _EAN6.default;
	exports.EAN2 = _EAN8.default;
	exports.UPC = _UPC2.default;

/***/ },
/* 23 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var GenericBarcode = function () {
		function GenericBarcode(string) {
			_classCallCheck(this, GenericBarcode);

			this.string = string;
		}

		// Return the corresponding binary numbers for the data provided


		GenericBarcode.prototype.encode = function encode() {
			return {
				data: "10101010101010101010101010101010101010101",
				text: this.string
			};
		};

		// Resturn true/false if the string provided is valid for this encoder


		GenericBarcode.prototype.valid = function valid() {
			return true;
		};

		return GenericBarcode;
	}();

	exports.GenericBarcode = GenericBarcode;

/***/ },
/* 24 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var ITF14 = function () {
		function ITF14(string) {
			_classCallCheck(this, ITF14);

			this.string = string;

			// Add checksum if it does not exist
			if (string.search(/^[0-9]{13}$/) !== -1) {
				this.string += this.checksum(string);
			}

			this.binaryRepresentation = {
				"0": "00110",
				"1": "10001",
				"2": "01001",
				"3": "11000",
				"4": "00101",
				"5": "10100",
				"6": "01100",
				"7": "00011",
				"8": "10010",
				"9": "01010"
			};
		}

		ITF14.prototype.valid = function valid() {
			return this.string.search(/^[0-9]{14}$/) !== -1 && this.string[13] == this.checksum();
		};

		ITF14.prototype.encode = function encode() {
			var result = "1010";

			// Calculate all the digit pairs
			for (var i = 0; i < 14; i += 2) {
				result += this.calculatePair(this.string.substr(i, 2));
			}

			// Always add the same end bits
			result += "11101";

			return {
				data: result,
				text: this.string
			};
		};

		// Calculate the data of a number pair


		ITF14.prototype.calculatePair = function calculatePair(numberPair) {
			var result = "";

			var number1Struct = this.binaryRepresentation[numberPair[0]];
			var number2Struct = this.binaryRepresentation[numberPair[1]];

			// Take every second bit and add to the result
			for (var i = 0; i < 5; i++) {
				result += number1Struct[i] == "1" ? "111" : "1";
				result += number2Struct[i] == "1" ? "000" : "0";
			}

			return result;
		};

		// Calulate the checksum digit


		ITF14.prototype.checksum = function checksum() {
			var result = 0;

			for (var i = 0; i < 13; i++) {
				result += parseInt(this.string[i]) * (3 - i % 2 * 2);
			}

			return 10 - result % 10;
		};

		return ITF14;
	}();

	exports.ITF14 = ITF14;

/***/ },
/* 25 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _MSI2 = __webpack_require__(1);

	var _MSI3 = _interopRequireDefault(_MSI2);

	var _checksums = __webpack_require__(3);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var MSI10 = function (_MSI) {
		_inherits(MSI10, _MSI);

		function MSI10(string) {
			_classCallCheck(this, MSI10);

			var _this = _possibleConstructorReturn(this, _MSI.call(this, string));

			_this.string += (0, _checksums.mod10)(_this.string);
			return _this;
		}

		return MSI10;
	}(_MSI3.default);

	exports.default = MSI10;

/***/ },
/* 26 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _MSI2 = __webpack_require__(1);

	var _MSI3 = _interopRequireDefault(_MSI2);

	var _checksums = __webpack_require__(3);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var MSI1010 = function (_MSI) {
		_inherits(MSI1010, _MSI);

		function MSI1010(string) {
			_classCallCheck(this, MSI1010);

			var _this = _possibleConstructorReturn(this, _MSI.call(this, string));

			_this.string += (0, _checksums.mod10)(_this.string);
			_this.string += (0, _checksums.mod10)(_this.string);
			return _this;
		}

		return MSI1010;
	}(_MSI3.default);

	exports.default = MSI1010;

/***/ },
/* 27 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _MSI2 = __webpack_require__(1);

	var _MSI3 = _interopRequireDefault(_MSI2);

	var _checksums = __webpack_require__(3);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var MSI11 = function (_MSI) {
		_inherits(MSI11, _MSI);

		function MSI11(string) {
			_classCallCheck(this, MSI11);

			var _this = _possibleConstructorReturn(this, _MSI.call(this, string));

			_this.string += (0, _checksums.mod11)(_this.string);
			return _this;
		}

		return MSI11;
	}(_MSI3.default);

	exports.default = MSI11;

/***/ },
/* 28 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _MSI2 = __webpack_require__(1);

	var _MSI3 = _interopRequireDefault(_MSI2);

	var _checksums = __webpack_require__(3);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var MSI1110 = function (_MSI) {
		_inherits(MSI1110, _MSI);

		function MSI1110(string) {
			_classCallCheck(this, MSI1110);

			var _this = _possibleConstructorReturn(this, _MSI.call(this, string));

			_this.string += (0, _checksums.mod11)(_this.string);
			_this.string += (0, _checksums.mod10)(_this.string);
			return _this;
		}

		return MSI1110;
	}(_MSI3.default);

	exports.default = MSI1110;

/***/ },
/* 29 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.MSI1110 = exports.MSI1010 = exports.MSI11 = exports.MSI10 = exports.MSI = undefined;

	var _MSI = __webpack_require__(1);

	var _MSI2 = _interopRequireDefault(_MSI);

	var _MSI3 = __webpack_require__(25);

	var _MSI4 = _interopRequireDefault(_MSI3);

	var _MSI5 = __webpack_require__(27);

	var _MSI6 = _interopRequireDefault(_MSI5);

	var _MSI7 = __webpack_require__(26);

	var _MSI8 = _interopRequireDefault(_MSI7);

	var _MSI9 = __webpack_require__(28);

	var _MSI10 = _interopRequireDefault(_MSI9);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.MSI = _MSI2.default;
	exports.MSI10 = _MSI4.default;
	exports.MSI11 = _MSI6.default;
	exports.MSI1010 = _MSI8.default;
	exports.MSI1110 = _MSI10.default;

/***/ },
/* 30 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	// Encoding documentation
	// http://www.gomaro.ch/ftproot/Laetus_PHARMA-CODE.pdf

	var pharmacode = function () {
	  function pharmacode(string) {
	    _classCallCheck(this, pharmacode);

	    this.number = parseInt(string, 10);
	  }

	  pharmacode.prototype.encode = function encode() {
	    var z = this.number;
	    var result = "";

	    // http://i.imgur.com/RMm4UDJ.png
	    // (source: http://www.gomaro.ch/ftproot/Laetus_PHARMA-CODE.pdf, page: 34)
	    while (!isNaN(z) && z != 0) {
	      if (z % 2 === 0) {
	        // Even
	        result = "11100" + result;
	        z = (z - 2) / 2;
	      } else {
	        // Odd
	        result = "100" + result;
	        z = (z - 1) / 2;
	      }
	    }

	    // Remove the two last zeroes
	    result = result.slice(0, -2);

	    return {
	      data: result,
	      text: this.number + ""
	    };
	  };

	  pharmacode.prototype.valid = function valid() {
	    return this.number >= 3 && this.number <= 131070;
	  };

	  return pharmacode;
	}();

	exports.pharmacode = pharmacode;

/***/ }
/******/ ]);;

/*
" --------------------------------------------------
"   FileName: app.coffee
"       Desc: app.js webapp逻辑脚本
"     Author: chenglifu
"    Version: v0.1
" LastChange: 12/17/2014 23:14
" --------------------------------------------------
 */

/* Notice: 不要修改本文件，本文件由app.coffee自动生成 */
(function ($) {
    "use strict";
    var _func, _lstor, _n, _stor, _vars, common;
    _n = this;
    _n.isDebug = 1;
    _vars = _n.vars;
    _func = _n.func;
    _stor = _n.storage;
    (common = function () {
        _func.getpram = function (str) {
            var pram = "";
            var reg = new RegExp("(^|&)" + str + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null)
                pram = unescape(r[2]);
            return pram;
        }
        _func.getstringRemovenull = function (str) {
            if (str == "null" || str == "undefined" || str == null || str == undefined) {
                str = "";
            }
            return str;
        }
        _func.GetUrlpram = function () {
            var resno = _func.getpram("resno");
            var invoicesign = _func.getpram("invoicesign");
            var backurl = _func.getpram("backurl");
            var noncestr = _func.getpram("noncestr");
            $.get(_vars.Urlpram, { resno: resno, invoicesign: invoicesign, noncestr: noncestr, backurl: backurl }, function (data) {
                if (data.isSuccess) {
                    return true;
                } else {
                    alert(data.msg);
                    return false;
                }
            });
        }
    })()

    $('.close').click(function () {
        return $(this).parents('.popup').hide();
    });
    $('.popup').click(function (e) {
        e.stopPropagation();
        return $(this).hide();
    });
    $('.popup .popupcontent').click(function (e) {
        e.stopPropagation();
    });

    /* module home {{{ */
    _n.app('Invoice', {
        InvoiceAction: function () {
            var resno = _func.getpram("resno");
            if (resno.length == 0 || resno == "null" || resno == "undefined") {
                $('.btncomplete').hide();
                $('.invoicetips').hide();
            }
            var ScaleOfTaxpaye = _func.getpram("ScaleOfTaxpaye");
            if (ScaleOfTaxpaye == "2") {
                var str = " <p>普票立取（离店时可立即开具增值税普通发票）</p> <p>专票延时（酒店至当地税务局申请代开后再寄出税率为3%的增值税专用发票）</p> <p>当您此次住宿为出差性质，且出差报销企业是一般纳税人的企业，酒店才可开具增值税专用发票</p>";
                $('.invoicetips').html(str);
                $('.invoicetips').show();
            } else if (ScaleOfTaxpaye == "1" || ScaleOfTaxpaye == "3") {
                var str1 = ScaleOfTaxpaye == "1" ? "6" : "3";
                var str = " <p>普票立取（离店时可立即开具增值税普通发票）</p> <p>专票立取（离店时可立即开具住宿税率为" + str1 + "%的增值税专用发票）</p> <p>当您此次住宿为出差性质，且出差报销企业是一般纳税人的企业，酒店才可开具增值税专用发票</p>";
                $('.invoicetips').html(str);
                $('.invoicetips').show();
            } else {
                $('.invoicetips').hide();
            }
            var backurl = _func.getpram("backurl");
            if (backurl.length == 0 || backurl == "null" || backurl == "undefined") {
                $('.headleft').hide();
            }
            $('.headleft').click(function () {
                var Tid = $('#hidtid').val();
                var backurl = _func.getpram("backurl");
                if (Tid.length > 0) {
                    if (confirm("是否保存本次更改？")) {
                        var resno = _func.getpram("resno");
                        var invoicesign = _func.getpram("invoicesign");
                        var noncestr = _func.getpram("noncestr");

                        if (resno.length > 0 && resno != "null" && resno != "undefined") {
                            $.get(_vars.invoiceforOrder, { resno: resno, invoicesign: invoicesign, noncestr: noncestr, backurl: backurl, tid: Tid }, function (data) {
                                if (data.isSuccess) {
                                    window.location = data.backurl;
                                } else {
                                    alert(data.msg);
                                }
                            });
                        }
                    } else {
                        if (backurl.length > 0) {
                            window.location = backurl;
                        }
                    }
                }
                if (backurl.length > 0) {
                    window.location = backurl;
                }
            });
            $('#divaddinvoice').click(function () {
                N.visit("AddInvoice" + window.location.search);
            });
            $('.icon-switchbox').click(function () {
                if ($(this).hasClass('enable')) {
                    $(this).removeClass('enable');
                    $('.commonuse,.invitetips,.btnaddinvoice').hide();
                } else {
                    $(this).addClass('enable');
                    $('.commonuse,.invoicetips,.btnaddinvoice').show();
                }
            });
            $('.invoicelist').click(function () {
                if ($(this).children('.invoicelistleft').children('.icon-radiobox').hasClass('checked')) {
                    return false;
                } else {
                    $('.invoicelist').children('.invoicelistleft').children('.icon-radiobox.checked').removeClass('checked');
                    $('.invoicelist').children('.invoicelistright').children('.edit').addClass('unable');
                    $('.invoicelist').children('.invoicelistright').children('.del').addClass('unable');
                    $(this).children('.invoicelistleft').children('.icon-radiobox').addClass('checked');
                    $(this).children('.invoicelistright').children('.edit').removeClass('unable');
                    $(this).children('.invoicelistright').children('.del').removeClass('unable');
                }
            });
            $('.invoicelistexpand').click(function () {
                $('.invoicelist').show();
                $('.invoicelistexpand').hide();
            });
            $('.edit').click(function () {

                var Tid = $(this).data("tid");
                var isvat = $(this).data("isvat");
                if (window.location.search.length > 0) {
                    N.visit("AddInvoice" + window.location.search + "&type=edit&tid=" + Tid + "&isvat=" + isvat);
                } else {
                    N.visit("AddInvoice?type=edit&tid=" + Tid + "&isvat=" + isvat);
                }
            });
            $('.del').click(function () {
                if (confirm("确认删除此发票信息吗?")) {
                    var Tid = $(this).data("tid");
                    $.get(_vars.invoiceDel, { tid: Tid }, function (data) {
                        if (data.isSuccess) {
                            window.location = window.location.href + window.location.search;
                        } else {
                            alert("删除发票失败！");
                        }
                    });
                }
            });
            $('.invoicelistleft').click(function () {
                var Tid = $(this).data("tid");
                $('#hidtid').val(Tid);
            });
            $('.btncomplete').click(function () {
                var Tid = $('#hidtid').val()
                if (Tid.length == 0) {
                    alert("请选择发票！");
                    return;
                } else {
                    if (Tid == "null" || Tid == "undefined") {
                        alert("请选择发票！");
                        return;
                    }
                    var resno = _func.getpram("resno");
                    var invoicesign = _func.getpram("invoicesign");
                    var backurl = _func.getpram("backurl");
                    var noncestr = _func.getpram("noncestr");

                    if (resno.length > 0 && resno != "null" && resno != "undefined") {
                        $.get(_vars.invoiceforOrder, { resno: resno, invoicesign: invoicesign, noncestr: noncestr, backurl: backurl, tid: Tid }, function (data) {
                            if (data.isSuccess) {
                                window.location = data.backurl;
                            } else {
                                alert(data.msg);
                            }
                        });
                    }
                }
            });
        },
        AddInvoiceAction: function () {
            var typeurl = _func.getpram("type");

            if (typeurl == "edit") {
                var type = $(".checked").data('active');
                var tid = _func.getpram("tid");
                var isvat = false;
                isvat = _func.getpram("isvat");
                if (isvat == "True") {
                    $('.invoicenormalbox').hide();
                    $('.invoicespecialbox').show();
                    $('.invoiceinfoTip').addClass('show');
                    $('#divZZ').removeClass('checked');
                    $('#divZY').removeClass('checked');
                    $('#divZY').addClass('checked');
                } else {
                    $('.invoicenormalbox').show();
                    $('.invoicespecialbox').hide();
                    $('.invoiceinfoTip').removeClass('show');
                    $('#divZY').removeClass('checked');
                    $('#divZZ').removeClass('checked');
                    $('#divZZ').addClass('checked');
                }
                $.get(_vars.invoiceAllUrl, null, function (data) {
                    if (data.isSuccess && data.result != null) {
                        if (isvat == "True") {
                            for (var i = 0; i < data.result.length; i++) {
                                if (data.result[i].Tid == tid) {
                                    $('#invoicecorpname').val(_func.getstringRemovenull(data.result[i].Title));
                                    $('#invoicecorpID').val(_func.getstringRemovenull(data.result[i].TaxpayerCode));
                                    $('#invoicecorpCreditcode').val(_func.getstringRemovenull(data.result[i].UnifiedSocialCreditCode));
                                    $('#invoicecorpAddress').val(_func.getstringRemovenull(data.result[i].CompanyAddress));
                                    $('#invoicecorpTel').val(_func.getstringRemovenull(data.result[i].PhoneNumber));
                                    $('#invoicecorpBank').val(_func.getstringRemovenull(data.result[i].CompanyBank));
                                    $('#invoicecorpBankcode').val(_func.getstringRemovenull(data.result[i].CompanyBankAccountNumber));
                                    if ($('#invoicecorpCreditcode').val().length > 0) {
                                        $('#invoicecorpID').attr("disabled", true);
                                        $('#invoicecorpID').attr("placeholder", "");
                                    } else {
                                        $('#invoicecorpID').removeAttr("disabled");
                                        $('#invoicecorpID').attr("placeholder", "纳税人识别号");
                                    }
                                    if ($('#invoicecorpID').val().length > 0) {
                                        $('#invoicecorpCreditcode').attr("disabled", true);
                                        $('#invoicecorpCreditcode').attr("placeholder", "");
                                    } else {
                                        $('#invoicecorpCreditcode').removeAttr("disabled");
                                        $('#invoicecorpCreditcode').attr("placeholder", "统一社会信用代码");
                                    }
                                }
                            }
                        } else {
                            for (var i = 0; i < data.result.length; i++) {
                                if (data.result[i].Tid == tid) {
                                    $('#invoicetitle').val(_func.getstringRemovenull(data.result[i].Title));
                                }
                            }
                        }
                    }
                });
            }
            $('.headleft').click(function () {
                var resno = _func.getpram("resno");
                var invoicesign = _func.getpram("invoicesign");
                var backurl = _func.getpram("backurl");
                var noncestr = _func.getpram("noncestr");
                var ScaleOfTaxpaye = _func.getpram("ScaleOfTaxpaye");
                var str = "";
                if (resno.length > 0) {
                    str += "resno=" + resno;
                }
                if (invoicesign.length > 0)
                {
                    if (str.length > 0) {
                        str += "&invoicesign=" + invoicesign;
                    } else {
                        str += "invoicesign=" + invoicesign;
                    }
                }
                if (backurl.length > 0) {
                    if (str.length > 0) {
                        str += "&backurl=" + backurl;
                    } else {
                        str += "backurl=" + backurl;
                    }
                }
                if (noncestr.length > 0) {
                    if (str.length > 0) {
                        str += "&noncestr=" + noncestr;
                    } else {
                        str += "noncestr=" + noncestr;
                    }
                }
                if (ScaleOfTaxpaye.length > 0) {
                    if (str.length > 0) {
                        str += "&ScaleOfTaxpaye=" + ScaleOfTaxpaye;
                    } else {
                        str += "ScaleOfTaxpaye=" + ScaleOfTaxpaye;
                    }
                }
                if (str.length > 0) {
                    str = "?" + str;
                }
                N.visit("Invoice" + str);
            });
            $('.invoicetypeSelect .list').click(function () {
                if ($(this).children('.listright').children('.icon-radiobox').hasClass('checked')) {
                    return false;
                } else {
                    $('.invoicetypeSelect .list .listright').children('.icon-radiobox.checked').removeClass('checked');
                    $(this).children('.listright').children('.icon-radiobox').addClass('checked');
                    if ($(this).index() === 0) {
                        $('.invoicenormalbox').show();
                        $('.invoicespecialbox').hide();
                        $('.invoiceinfoTip').removeClass('show');
                    } else {
                        $('.invoicenormalbox').hide();
                        $('.invoicespecialbox').show();
                        $('.invoiceinfoTip').addClass('show');
                    }
                }
            });
            $('#liselect').click(function () {
                var Tid = $(this).data('tid');
                var json = $('#hidjson').val();
                json = JSON.parse(json);
                var type = $(".checked").data('active');
                for (var i = 0; i < json.length; i++) {
                    if (json[i].Tid == Tid) {
                        if (type === 'first') {
                            $('#invoicetitle').val(_func.getstringRemovenull(json[i].Title));
                        } else {
                            $('#invoicecorpname').val(_func.getstringRemovenull(json[i].Title));
                            $('#invoicecorpID').val(_func.getstringRemovenull(json[i].TaxpayerCode));
                            $('#invoicecorpCreditcode').val(_func.getstringRemovenull(json[i].InvoiceSocial));
                            $('#invoicecorpAddress').val(_func.getstringRemovenull(json[i].CompanyAddress));
                            $('#invoicecorpTel').val(_func.getstringRemovenull(json[i].PhoneNumber));
                            $('#invoicecorpBank').val(_func.getstringRemovenull(json[i].CompanyBank));
                            $('#invoicecorpBankcode').val(_func.getstringRemovenull(json[i].CompanyBankAccountNumber));
                            if ($('#invoicecorpCreditcode').val().length > 0) {
                                $('#invoicecorpID').attr("disabled", true);
                                $('#invoicecorpID').attr("placeholder", "");
                            } else {
                                $('#invoicecorpID').removeAttr("disabled");
                                $('#invoicecorpID').attr("placeholder", "纳税人识别号");
                            }
                            if ($('#invoicecorpCreditcode').val().length == 0) {
                                if ($('#invoicecorpID').val().length > 0) {
                                    $('#invoicecorpCreditcode').attr("disabled", true);
                                    $('#invoicecorpCreditcode').attr("placeholder", "");
                                } else {
                                    $('#invoicecorpCreditcode').removeAttr("disabled");
                                    $('#invoicecorpCreditcode').attr("placeholder", "统一社会信用代码");
                                }
                            }
                        }
                    }
                }
            });
            var timeout = "";
            $('.invoicelistright input').on("input", function () {
                if ($(this).parents('.invoicelist').index() === 0) {
                    if ($(this).val().length >= 2) {
                        var keyword = $(this).val();
                        if ($('#hidtitleSearch').val().trim() != keyword) {
                            $('#hidtitleSearch').val(keyword);
                            var type = $(".checked").data('active');
                            var types = "0";
                            if (timeout != "")
                                clearTimeout(timeout)
                            else
                                timeout = setTimeout(function () {
                                    //$.get(_vars.invoiceUrl, { "keyword": keyword, "invoiceType": types }, function (data) {
                                    //    if (data.isSuccess && data.result != null) {
                                    //        var str = "";
                                    //        for (var i = 0; i < data.result.length; i++) {
                                    //            str += " <li  data-tid=\"" + data.result[i].Tid + "\"><div class=\"corplisttitle\">";
                                    //            str += "<span class=\"matching\">" + _func.getstringRemovenull(data.result[i].Title.substring(0, keyword.length));
                                    //            str += "</span>" + _func.getstringRemovenull(data.result[i].Title.substring(keyword.length, data.result[i].Title.length)) + "</div>";
                                    //            str += "<div class=\"corplistsub\"><div class=\"code\">" + _func.getstringRemovenull(data.result[i].TaxpayerCode);
                                    //            str += "</div><div class=\"usernum\">" + _func.getstringRemovenull(data.result[i].RefrenceCount) + "人选用</div></div></li>";
                                    //        }
                                    //        $('#hidjson').val(JSON.stringify(data.result));
                                    //        $('.corplist').html(str);
                                    //        $('.searchresult').show();
                                    //    }
                                    //})
                                    $.ajax({
                                        url: _vars.invoiceUrl,
                                        data: { "keyword": keyword, "invoiceType": types },
                                        type: "Get",
                                        async: false,
                                        dataType: "json",
                                        success: function (data) {
                                            if (data.isSuccess && data.result != null) {
                                                var str = "";
                                                for (var i = 0; i < data.result.length; i++) {
                                                    str += " <li  data-tid=\"" + data.result[i].Tid + "\"><div class=\"corplisttitle\">";
                                                    str += "<span class=\"matching\">" + _func.getstringRemovenull(data.result[i].Title.substring(0, keyword.length));
                                                    str += "</span>" + _func.getstringRemovenull(data.result[i].Title.substring(keyword.length, data.result[i].Title.length)) + "</div>";
                                                    str += "<div class=\"corplistsub\"><div class=\"code\">" + _func.getstringRemovenull(data.result[i].TaxpayerCode);
                                                    str += "</div><div class=\"usernum\">" + _func.getstringRemovenull(data.result[i].RefrenceCount) + "人选用</div></div></li>";
                                                }
                                                $('#hidjson').val(JSON.stringify(data.result));
                                                $('.corplist').html(str);
                                                $('.searchresult').show();
                                            }
                                        }
                                    });
                                }, 1000)
                            timeout = "";
                        }
                    } else {
                        $('#hidtitleSearch').val("");
                        $('.searchresult').hide();
                    }
                }

                if ($(this).val().length >= 1) {
                    $(this).parent().siblings('.icon-clearbox').addClass('enable');
                } else {
                    $(this).parent().siblings('.icon-clearbox').removeClass('enable');
                }
            });
            var timer11 = null;
            $('#invoicetitle').blur(function () {
                clearTimeout(timer11);
                timer11 = setTimeout(function () {
                    $('.searchresult').hide();
                }, 10)

            });
            $('#invoicecorpname').blur(function () {
                clearTimeout(timer11);
                timer11 = setTimeout(function () {
                    $('.searchresult').hide();
                }, 10)

            });
            $('#invoicecorpID').keyup(function () {
                clearTimeout(timer11);
                timer11 = setTimeout(function () {
                    if ($('#invoicecorpID').val().length > 0) {
                        $('#invoicecorpCreditcode').attr("disabled", true);
                        $('#invoicecorpCreditcode').attr("placeholder", "");
                    } else {
                        $('#invoicecorpCreditcode').removeAttr("disabled");
                        $('#invoicecorpCreditcode').attr("placeholder", "统一社会信用代码");
                    }
                }, 10)

            });
            $('#invoicecorpCreditcode').keyup(function () {
                clearTimeout(timer11);
                timer11 = setTimeout(function () {
                    if ($('#invoicecorpCreditcode').val().length > 0) {
                        $('#invoicecorpID').attr("disabled", true);
                        $('#invoicecorpID').attr("placeholder", "");
                    } else {
                        $('#invoicecorpID').removeAttr("disabled");
                        $('#invoicecorpID').attr("placeholder", "纳税人识别号");
                    }
                }, 10)

            });
            $('.content').on('click', '.corplist li', function () {
                var Tid = $(this).attr('data-tid');
                var json = $('#hidjson').val();
                json = JSON.parse(json);
                var type = $(".checked").data('active');
                for (var i = 0; i < json.length; i++) {
                    if (json[i].Tid == Tid) {
                        if (type === 'first') {
                            $('#invoicetitle').val(getstringRemovenull(json[i].Title));
                        } else {
                            $('#invoicecorpname').val(getstringRemovenull(json[i].Title));
                            $('#invoicecorpID').val(getstringRemovenull(json[i].TaxpayerCode));
                            $('#invoicecorpCreditcode').val(getstringRemovenull(json[i].InvoiceSocial));
                            $('#invoicecorpAddress').val(getstringRemovenull(json[i].CompanyAddress));
                            $('#invoicecorpTel').val(getstringRemovenull(json[i].PhoneNumber));
                            $('#invoicecorpBank').val(getstringRemovenull(json[i].CompanyBank));
                            $('#invoicecorpBankcode').val(getstringRemovenull(json[i].CompanyBankAccountNumber));
                            if ($('#invoicecorpCreditcode').val().length > 0) {
                                $('#invoicecorpID').attr("disabled", true);
                                $('#invoicecorpID').attr("placeholder", "");
                            } else {
                                $('#invoicecorpID').removeAttr("disabled");
                                $('#invoicecorpID').attr("placeholder", "纳税人识别号");
                            }
                            if ($('#invoicecorpID').val().length > 0) {
                                $('#invoicecorpCreditcode').attr("disabled", true);
                                $('#invoicecorpCreditcode').attr("placeholder", "");
                            } else {
                                $('#invoicecorpCreditcode').removeAttr("disabled");
                                $('#invoicecorpCreditcode').attr("placeholder", "统一社会信用代码");
                            }
                        }
                    }
                }
            })
            $('.invoicelistright input').focus(function () {
                if ($(this).val().length >= 1) {
                    $(this).parent().siblings('.icon-clearbox').addClass('enable');
                }
                $('.invoicelist .icon-clearbox').not($(this).parent().siblings('.icon-clearbox')).removeClass('enable');
            });
            $('.icon-clearbox').click(function () {
                $(this).prev().find('input').val('');
                if ($('#invoicecorpCreditcode').val().length > 0) {
                    $('#invoicecorpID').attr("disabled", true);
                    $('#invoicecorpID').attr("placeholder", "");
                } else {
                    $('#invoicecorpID').removeAttr("disabled");
                    $('#invoicecorpID').attr("placeholder", "纳税人识别号");
                }
                if ($('#invoicecorpID').val().length > 0) {
                    $('#invoicecorpCreditcode').attr("disabled", true);
                    $('#invoicecorpCreditcode').attr("placeholder", "");
                } else {
                    $('#invoicecorpCreditcode').removeAttr("disabled");
                    $('#invoicecorpCreditcode').attr("placeholder", "统一社会信用代码");
                }
                return $(this).removeClass('enable');
            });
            //保存发票
            $('.btnsave').click(function () {

                var type = $(".checked").data('active');
                if (type === 'first') {
                    if (!$('#invoicetitle').val()) {
                        alert("请输入发票抬头！");
                        return;
                    }
                    $('#hidInvoiceTitle').val($('#invoicetitle').val());
                }
                if (type === 'second') {
                    if (!$('#invoicecorpname').val()) {
                        alert("请输入发票抬头！");
                        return;
                    }
                    if (!$('#invoicecorpCreditcode').val() && !$('#invoicecorpID').val()) {
                        alert("请输入纳税人识别号或统一社会信用代码！");
                        return;
                    }
                    var len = $('#invoicecorpID').val().length;
                    if ($('#invoicecorpID').val() && len !== 15 && len !== 17 && len !== 18 && len !== 20) {
                        alert("纳税人识别号输入长度有误，请检查！");
                        return;
                    }
                    if ($('#invoicecorpCreditcode').val().length > 0 && $('#invoicecorpID').val().length > 0) {
                        alert("纳税人识别号和统一社会信用代码只能选填一项！");
                        return;
                    }
                    if (!$('#invoicecorpAddress').val()) {
                        alert("请输入注册地址！");
                        return;
                    }
                    if (!$('#invoicecorpTel').val()) {
                        alert("请输入联系电话！");
                        return;
                    }
                    if (!$('#invoicecorpBank').val()) {
                        alert("请输入开户银行！");
                        return;
                    }
                    if (!$('#invoicecorpBankcode').val()) {
                        alert("请输入开户账号！");
                        return;
                    }
                    $('#hidInvoiceTitle').val($('#invoicecorpname').val());
                    $('#hidInvoiceSocial').val($('#invoicecorpCreditcode').val());
                    $('#hidInvoiceTaxNum').val($('#invoicecorpID').val());
                    $('#hidInvoiceAddress').val($('#invoicecorpAddress').val());
                    $('#hidInvoicePhone').val($('#invoicecorpTel').val());
                    $('#hidInvoiceBank').val($('#invoicecorpBank').val());
                    $('#hidInvoiceBankNum').val($('#invoicecorpBankcode').val());
                    $('#hidInvoiceType').val(type);
                    $('#hidTid').val();
                    $('#hidtypeurl').val();

                }
                var typeurl = _func.getpram("type");
                if (typeurl == "edit") {
                    $('#hidtypeurl').val(typeurl);
                    var tid = _func.getpram("tid");;
                    var isvat = false;
                    isvat = _func.getpram("isvat");
                    $('#hidTid').val(tid);
                    //if (isvat == "True") {
                    //    type = "second";
                    //} else {
                    //    type = "first";
                    //}
                }
                $('#hidInvoiceType').val(type === 'first' ? 'common' : 'spiclal');
                $.post(_vars.saveUrl, $('#invoice').serialize(), function (data) {
                    if (data.isSuccess) {
                        var resno = _func.getpram("resno");
                        var invoicesign = _func.getpram("invoicesign");
                        var backurl = _func.getpram("backurl");
                        var noncestr = _func.getpram("noncestr");
                        var ScaleOfTaxpaye = _func.getpram("ScaleOfTaxpaye");
                        var str = "";
                        if (resno.length > 0) {
                            str += "resno=" + resno;
                        }
                        if (invoicesign.length > 0) {
                            if (str.length > 0) {
                                str += "&invoicesign=" + invoicesign;
                            } else {
                                str += "invoicesign=" + invoicesign;
                            }
                        }
                        if (backurl.length > 0) {
                            if (str.length > 0) {
                                str += "&backurl=" + backurl;
                            } else {
                                str += "backurl=" + backurl;
                            }
                        }
                        if (noncestr.length > 0) {
                            if (str.length > 0) {
                                str += "&noncestr=" + noncestr;
                            } else {
                                str += "noncestr=" + noncestr;
                            }
                        }
                        if (ScaleOfTaxpaye.length > 0) {
                            if (str.length > 0) {
                                str += "&ScaleOfTaxpaye=" + ScaleOfTaxpaye;
                            } else {
                                str += "ScaleOfTaxpaye=" + ScaleOfTaxpaye;
                            }
                        }
                        if (str.length > 0) {
                            str = "?" + str;
                        }
                        N.visit("Invoice" + str);
                    } else {
                        alert("保存发票出现错误!");
                    }
                });
            });
        }
    });

    /* }}} */
    return _n;
}).call(this.N = this.ndoo = this.ndoo || {}, Zepto);
;
