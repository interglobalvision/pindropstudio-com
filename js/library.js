(function(window, factory) {
	var lazySizes = factory(window, window.document);
	window.lazySizes = lazySizes;
	if(typeof module == 'object' && module.exports){
		module.exports = lazySizes;
	}
}(window, function l(window, document) {
	'use strict';
	/*jshint eqnull:true */
	if(!document.getElementsByClassName){return;}

	var lazySizesConfig;

	var docElem = document.documentElement;

	var Date = window.Date;

	var supportPicture = window.HTMLPictureElement;

	var _addEventListener = 'addEventListener';

	var _getAttribute = 'getAttribute';

	var addEventListener = window[_addEventListener];

	var setTimeout = window.setTimeout;

	var requestAnimationFrame = window.requestAnimationFrame || setTimeout;

	var requestIdleCallback = window.requestIdleCallback;

	var regPicture = /^picture$/i;

	var loadEvents = ['load', 'error', 'lazyincluded', '_lazyloaded'];

	var regClassCache = {};

	var forEach = Array.prototype.forEach;

	var hasClass = function(ele, cls) {
		if(!regClassCache[cls]){
			regClassCache[cls] = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		}
		return regClassCache[cls].test(ele[_getAttribute]('class') || '') && regClassCache[cls];
	};

	var addClass = function(ele, cls) {
		if (!hasClass(ele, cls)){
			ele.setAttribute('class', (ele[_getAttribute]('class') || '').trim() + ' ' + cls);
		}
	};

	var removeClass = function(ele, cls) {
		var reg;
		if ((reg = hasClass(ele,cls))) {
			ele.setAttribute('class', (ele[_getAttribute]('class') || '').replace(reg, ' '));
		}
	};

	var addRemoveLoadEvents = function(dom, fn, add){
		var action = add ? _addEventListener : 'removeEventListener';
		if(add){
			addRemoveLoadEvents(dom, fn);
		}
		loadEvents.forEach(function(evt){
			dom[action](evt, fn);
		});
	};

	var triggerEvent = function(elem, name, detail, noBubbles, noCancelable){
		var event = document.createEvent('CustomEvent');

		event.initCustomEvent(name, !noBubbles, !noCancelable, detail || {});

		elem.dispatchEvent(event);
		return event;
	};

	var updatePolyfill = function (el, full){
		var polyfill;
		if( !supportPicture && ( polyfill = (window.picturefill || lazySizesConfig.pf) ) ){
			polyfill({reevaluate: true, elements: [el]});
		} else if(full && full.src){
			el.src = full.src;
		}
	};

	var getCSS = function (elem, style){
		return (getComputedStyle(elem, null) || {})[style];
	};

	var getWidth = function(elem, parent, width){
		width = width || elem.offsetWidth;

		while(width < lazySizesConfig.minSize && parent && !elem._lazysizesWidth){
			width =  parent.offsetWidth;
			parent = parent.parentNode;
		}

		return width;
	};

	var rAF = (function(){
		var running, waiting;
		var fns = [];

		var run = function(){
			var fn;
			running = true;
			waiting = false;
			while(fns.length){
				fn = fns.shift();
				fn[0].apply(fn[1], fn[2]);
			}
			running = false;
		};

		return function(fn){
			if(running){
				fn.apply(this, arguments);
			} else {
				fns.push([fn, this, arguments]);

				if(!waiting){
					waiting = true;
					(document.hidden ? setTimeout : requestAnimationFrame)(run);
				}
			}
		};
	})();

	var rAFIt = function(fn, simple){
		return simple ?
			function() {
				rAF(fn);
			} :
			function(){
				var that = this;
				var args = arguments;
				rAF(function(){
					fn.apply(that, args);
				});
			}
		;
	};

	var throttle = function(fn){
		var running;
		var lastTime = 0;
		var gDelay = 125;
		var RIC_DEFAULT_TIMEOUT = 999;
		var rICTimeout = RIC_DEFAULT_TIMEOUT;
		var run = function(){
			running = false;
			lastTime = Date.now();
			fn();
		};
		var idleCallback = requestIdleCallback ?
			function(){
				requestIdleCallback(run, {timeout: rICTimeout});
				if(rICTimeout !== RIC_DEFAULT_TIMEOUT){
					rICTimeout = RIC_DEFAULT_TIMEOUT;
				}
			}:
			rAFIt(function(){
				setTimeout(run);
			}, true)
		;

		return function(isPriority){
			var delay;
			if((isPriority = isPriority === true)){
				rICTimeout = 66;
			}

			if(running){
				return;
			}

			running =  true;

			delay = gDelay - (Date.now() - lastTime);

			if(delay < 0){
				delay = 0;
			}

			if(isPriority || (delay < 9 && requestIdleCallback)){
				idleCallback();
			} else {
				setTimeout(idleCallback, delay);
			}
		};
	};

	//based on http://modernjavascript.blogspot.de/2013/08/building-better-debounce.html
	var debounce = function(func) {
		var timeout, timestamp;
		var wait = 99;
		var run = function(){
			timeout = null;
			func();
		};
		var later = function() {
			var last = Date.now() - timestamp;

			if (last < wait) {
				setTimeout(later, wait - last);
			} else {
				(requestIdleCallback || run)(run);
			}
		};

		return function() {
			timestamp = Date.now();

			if (!timeout) {
				timeout = setTimeout(later, wait);
			}
		};
	};


	var loader = (function(){
		var lazyloadElems, preloadElems, isCompleted, resetPreloadingTimer, loadMode, started;

		var eLvW, elvH, eLtop, eLleft, eLright, eLbottom;

		var defaultExpand, preloadExpand, hFac;

		var regImg = /^img$/i;
		var regIframe = /^iframe$/i;

		var supportScroll = ('onscroll' in window) && !(/glebot/.test(navigator.userAgent));

		var shrinkExpand = 0;
		var currentExpand = 0;

		var isLoading = 0;
		var lowRuns = -1;

		var resetPreloading = function(e){
			isLoading--;
			if(e && e.target){
				addRemoveLoadEvents(e.target, resetPreloading);
			}

			if(!e || isLoading < 0 || !e.target){
				isLoading = 0;
			}
		};

		var isNestedVisible = function(elem, elemExpand){
			var outerRect;
			var parent = elem;
			var visible = getCSS(document.body, 'visibility') == 'hidden' || getCSS(elem, 'visibility') != 'hidden';

			eLtop -= elemExpand;
			eLbottom += elemExpand;
			eLleft -= elemExpand;
			eLright += elemExpand;

			while(visible && (parent = parent.offsetParent) && parent != document.body && parent != docElem){
				visible = ((getCSS(parent, 'opacity') || 1) > 0);

				if(visible && getCSS(parent, 'overflow') != 'visible'){
					outerRect = parent.getBoundingClientRect();
					visible = eLright > outerRect.left &&
						eLleft < outerRect.right &&
						eLbottom > outerRect.top - 1 &&
						eLtop < outerRect.bottom + 1
					;
				}
			}

			return visible;
		};

		var checkElements = function() {
			var eLlen, i, rect, autoLoadElem, loadedSomething, elemExpand, elemNegativeExpand, elemExpandVal, beforeExpandVal;

			if((loadMode = lazySizesConfig.loadMode) && isLoading < 8 && (eLlen = lazyloadElems.length)){

				i = 0;

				lowRuns++;

				if(preloadExpand == null){
					if(!('expand' in lazySizesConfig)){
						lazySizesConfig.expand = docElem.clientHeight > 500 && docElem.clientWidth > 500 ? 500 : 370;
					}

					defaultExpand = lazySizesConfig.expand;
					preloadExpand = defaultExpand * lazySizesConfig.expFactor;
				}

				if(currentExpand < preloadExpand && isLoading < 1 && lowRuns > 2 && loadMode > 2 && !document.hidden){
					currentExpand = preloadExpand;
					lowRuns = 0;
				} else if(loadMode > 1 && lowRuns > 1 && isLoading < 6){
					currentExpand = defaultExpand;
				} else {
					currentExpand = shrinkExpand;
				}

				for(; i < eLlen; i++){

					if(!lazyloadElems[i] || lazyloadElems[i]._lazyRace){continue;}

					if(!supportScroll){unveilElement(lazyloadElems[i]);continue;}

					if(!(elemExpandVal = lazyloadElems[i][_getAttribute]('data-expand')) || !(elemExpand = elemExpandVal * 1)){
						elemExpand = currentExpand;
					}

					if(beforeExpandVal !== elemExpand){
						eLvW = innerWidth + (elemExpand * hFac);
						elvH = innerHeight + elemExpand;
						elemNegativeExpand = elemExpand * -1;
						beforeExpandVal = elemExpand;
					}

					rect = lazyloadElems[i].getBoundingClientRect();

					if ((eLbottom = rect.bottom) >= elemNegativeExpand &&
						(eLtop = rect.top) <= elvH &&
						(eLright = rect.right) >= elemNegativeExpand * hFac &&
						(eLleft = rect.left) <= eLvW &&
						(eLbottom || eLright || eLleft || eLtop) &&
						((isCompleted && isLoading < 3 && !elemExpandVal && (loadMode < 3 || lowRuns < 4)) || isNestedVisible(lazyloadElems[i], elemExpand))){
						unveilElement(lazyloadElems[i]);
						loadedSomething = true;
						if(isLoading > 9){break;}
					} else if(!loadedSomething && isCompleted && !autoLoadElem &&
						isLoading < 4 && lowRuns < 4 && loadMode > 2 &&
						(preloadElems[0] || lazySizesConfig.preloadAfterLoad) &&
						(preloadElems[0] || (!elemExpandVal && ((eLbottom || eLright || eLleft || eLtop) || lazyloadElems[i][_getAttribute](lazySizesConfig.sizesAttr) != 'auto')))){
						autoLoadElem = preloadElems[0] || lazyloadElems[i];
					}
				}

				if(autoLoadElem && !loadedSomething){
					unveilElement(autoLoadElem);
				}
			}
		};

		var throttledCheckElements = throttle(checkElements);

		var switchLoadingClass = function(e){
			addClass(e.target, lazySizesConfig.loadedClass);
			removeClass(e.target, lazySizesConfig.loadingClass);
			addRemoveLoadEvents(e.target, rafSwitchLoadingClass);
		};
		var rafedSwitchLoadingClass = rAFIt(switchLoadingClass);
		var rafSwitchLoadingClass = function(e){
			rafedSwitchLoadingClass({target: e.target});
		};

		var changeIframeSrc = function(elem, src){
			try {
				elem.contentWindow.location.replace(src);
			} catch(e){
				elem.src = src;
			}
		};

		var handleSources = function(source){
			var customMedia, parent;

			var sourceSrcset = source[_getAttribute](lazySizesConfig.srcsetAttr);

			if( (customMedia = lazySizesConfig.customMedia[source[_getAttribute]('data-media') || source[_getAttribute]('media')]) ){
				source.setAttribute('media', customMedia);
			}

			if(sourceSrcset){
				source.setAttribute('srcset', sourceSrcset);
			}

			//https://bugzilla.mozilla.org/show_bug.cgi?id=1170572
			if(customMedia){
				parent = source.parentNode;
				parent.insertBefore(source.cloneNode(), source);
				parent.removeChild(source);
			}
		};

		var lazyUnveil = rAFIt(function (elem, detail, isAuto, sizes, isImg){
			var src, srcset, parent, isPicture, event, firesLoad;

			if(!(event = triggerEvent(elem, 'lazybeforeunveil', detail)).defaultPrevented){

				if(sizes){
					if(isAuto){
						addClass(elem, lazySizesConfig.autosizesClass);
					} else {
						elem.setAttribute('sizes', sizes);
					}
				}

				srcset = elem[_getAttribute](lazySizesConfig.srcsetAttr);
				src = elem[_getAttribute](lazySizesConfig.srcAttr);

				if(isImg) {
					parent = elem.parentNode;
					isPicture = parent && regPicture.test(parent.nodeName || '');
				}

				firesLoad = detail.firesLoad || (('src' in elem) && (srcset || src || isPicture));

				event = {target: elem};

				if(firesLoad){
					addRemoveLoadEvents(elem, resetPreloading, true);
					clearTimeout(resetPreloadingTimer);
					resetPreloadingTimer = setTimeout(resetPreloading, 2500);

					addClass(elem, lazySizesConfig.loadingClass);
					addRemoveLoadEvents(elem, rafSwitchLoadingClass, true);
				}

				if(isPicture){
					forEach.call(parent.getElementsByTagName('source'), handleSources);
				}

				if(srcset){
					elem.setAttribute('srcset', srcset);
				} else if(src && !isPicture){
					if(regIframe.test(elem.nodeName)){
						changeIframeSrc(elem, src);
					} else {
						elem.src = src;
					}
				}

				if(srcset || isPicture){
					updatePolyfill(elem, {src: src});
				}
			}

			rAF(function(){
				if(elem._lazyRace){
					delete elem._lazyRace;
				}
				removeClass(elem, lazySizesConfig.lazyClass);

				if( !firesLoad || elem.complete ){
					if(firesLoad){
						resetPreloading(event);
					} else {
						isLoading--;
					}
					switchLoadingClass(event);
				}
			});
		});

		var unveilElement = function (elem){
			var detail;

			var isImg = regImg.test(elem.nodeName);

			//allow using sizes="auto", but don't use. it's invalid. Use data-sizes="auto" or a valid value for sizes instead (i.e.: sizes="80vw")
			var sizes = isImg && (elem[_getAttribute](lazySizesConfig.sizesAttr) || elem[_getAttribute]('sizes'));
			var isAuto = sizes == 'auto';

			if( (isAuto || !isCompleted) && isImg && (elem.src || elem.srcset) && !elem.complete && !hasClass(elem, lazySizesConfig.errorClass)){return;}

			detail = triggerEvent(elem, 'lazyunveilread').detail;

			if(isAuto){
				 autoSizer.updateElem(elem, true, elem.offsetWidth);
			}

			elem._lazyRace = true;
			isLoading++;

			lazyUnveil(elem, detail, isAuto, sizes, isImg);
		};

		var onload = function(){
			if(isCompleted){return;}
			if(Date.now() - started < 999){
				setTimeout(onload, 999);
				return;
			}
			var afterScroll = debounce(function(){
				lazySizesConfig.loadMode = 3;
				throttledCheckElements();
			});

			isCompleted = true;

			lazySizesConfig.loadMode = 3;

			throttledCheckElements();

			addEventListener('scroll', function(){
				if(lazySizesConfig.loadMode == 3){
					lazySizesConfig.loadMode = 2;
				}
				afterScroll();
			}, true);
		};

		return {
			_: function(){
				started = Date.now();

				lazyloadElems = document.getElementsByClassName(lazySizesConfig.lazyClass);
				preloadElems = document.getElementsByClassName(lazySizesConfig.lazyClass + ' ' + lazySizesConfig.preloadClass);
				hFac = lazySizesConfig.hFac;

				addEventListener('scroll', throttledCheckElements, true);

				addEventListener('resize', throttledCheckElements, true);

				if(window.MutationObserver){
					new MutationObserver( throttledCheckElements ).observe( docElem, {childList: true, subtree: true, attributes: true} );
				} else {
					docElem[_addEventListener]('DOMNodeInserted', throttledCheckElements, true);
					docElem[_addEventListener]('DOMAttrModified', throttledCheckElements, true);
					setInterval(throttledCheckElements, 999);
				}

				addEventListener('hashchange', throttledCheckElements, true);

				//, 'fullscreenchange'
				['focus', 'mouseover', 'click', 'load', 'transitionend', 'animationend', 'webkitAnimationEnd'].forEach(function(name){
					document[_addEventListener](name, throttledCheckElements, true);
				});

				if((/d$|^c/.test(document.readyState))){
					onload();
				} else {
					addEventListener('load', onload);
					document[_addEventListener]('DOMContentLoaded', throttledCheckElements);
					setTimeout(onload, 20000);
				}

				throttledCheckElements(lazyloadElems.length > 0);
			},
			checkElems: throttledCheckElements,
			unveil: unveilElement
		};
	})();


	var autoSizer = (function(){
		var autosizesElems;

		var sizeElement = rAFIt(function(elem, parent, event, width){
			var sources, i, len;
			elem._lazysizesWidth = width;
			width += 'px';

			elem.setAttribute('sizes', width);

			if(regPicture.test(parent.nodeName || '')){
				sources = parent.getElementsByTagName('source');
				for(i = 0, len = sources.length; i < len; i++){
					sources[i].setAttribute('sizes', width);
				}
			}

			if(!event.detail.dataAttr){
				updatePolyfill(elem, event.detail);
			}
		});
		var getSizeElement = function (elem, dataAttr, width){
			var event;
			var parent = elem.parentNode;

			if(parent){
				width = getWidth(elem, parent, width);
				event = triggerEvent(elem, 'lazybeforesizes', {width: width, dataAttr: !!dataAttr});

				if(!event.defaultPrevented){
					width = event.detail.width;

					if(width && width !== elem._lazysizesWidth){
						sizeElement(elem, parent, event, width);
					}
				}
			}
		};

		var updateElementsSizes = function(){
			var i;
			var len = autosizesElems.length;
			if(len){
				i = 0;

				for(; i < len; i++){
					getSizeElement(autosizesElems[i]);
				}
			}
		};

		var debouncedUpdateElementsSizes = debounce(updateElementsSizes);

		return {
			_: function(){
				autosizesElems = document.getElementsByClassName(lazySizesConfig.autosizesClass);
				addEventListener('resize', debouncedUpdateElementsSizes);
			},
			checkElems: debouncedUpdateElementsSizes,
			updateElem: getSizeElement
		};
	})();

	var init = function(){
		if(!init.i){
			init.i = true;
			autoSizer._();
			loader._();
		}
	};

	(function(){
		var prop;

		var lazySizesDefaults = {
			lazyClass: 'lazyload',
			loadedClass: 'lazyloaded',
			loadingClass: 'lazyloading',
			preloadClass: 'lazypreload',
			errorClass: 'lazyerror',
			//strictClass: 'lazystrict',
			autosizesClass: 'lazyautosizes',
			srcAttr: 'data-src',
			srcsetAttr: 'data-srcset',
			sizesAttr: 'data-sizes',
			//preloadAfterLoad: false,
			minSize: 40,
			customMedia: {},
			init: true,
			expFactor: 1.5,
			hFac: 0.8,
			loadMode: 2
		};

		lazySizesConfig = window.lazySizesConfig || window.lazysizesConfig || {};

		for(prop in lazySizesDefaults){
			if(!(prop in lazySizesConfig)){
				lazySizesConfig[prop] = lazySizesDefaults[prop];
			}
		}

		window.lazySizesConfig = lazySizesConfig;

		setTimeout(function(){
			if(lazySizesConfig.init){
				init();
			}
		});
	})();

	return {
		cfg: lazySizesConfig,
		autoSizer: autoSizer,
		loader: loader,
		init: init,
		uP: updatePolyfill,
		aC: addClass,
		rC: removeClass,
		hC: hasClass,
		fire: triggerEvent,
		gW: getWidth,
		rAF: rAF,
	};
}
));

/**
 * EvEmitter v1.0.3
 * Lil' event emitter
 * MIT License
 */

/* jshint unused: true, undef: true, strict: true */

( function( global, factory ) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, window */
  if ( typeof define == 'function' && define.amd ) {
    // AMD - RequireJS
    define( factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS - Browserify, Webpack
    module.exports = factory();
  } else {
    // Browser globals
    global.EvEmitter = factory();
  }

}( typeof window != 'undefined' ? window : this, function() {

"use strict";

function EvEmitter() {}

var proto = EvEmitter.prototype;

proto.on = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // set events hash
  var events = this._events = this._events || {};
  // set listeners array
  var listeners = events[ eventName ] = events[ eventName ] || [];
  // only add once
  if ( listeners.indexOf( listener ) == -1 ) {
    listeners.push( listener );
  }

  return this;
};

proto.once = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // add event
  this.on( eventName, listener );
  // set once flag
  // set onceEvents hash
  var onceEvents = this._onceEvents = this._onceEvents || {};
  // set onceListeners object
  var onceListeners = onceEvents[ eventName ] = onceEvents[ eventName ] || {};
  // set flag
  onceListeners[ listener ] = true;

  return this;
};

proto.off = function( eventName, listener ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  var index = listeners.indexOf( listener );
  if ( index != -1 ) {
    listeners.splice( index, 1 );
  }

  return this;
};

proto.emitEvent = function( eventName, args ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  var i = 0;
  var listener = listeners[i];
  args = args || [];
  // once stuff
  var onceListeners = this._onceEvents && this._onceEvents[ eventName ];

  while ( listener ) {
    var isOnce = onceListeners && onceListeners[ listener ];
    if ( isOnce ) {
      // remove listener
      // remove before trigger to prevent recursion
      this.off( eventName, listener );
      // unset once flag
      delete onceListeners[ listener ];
    }
    // trigger listener
    listener.apply( this, args );
    // get next listener
    i += isOnce ? 0 : 1;
    listener = listeners[i];
  }

  return this;
};

return EvEmitter;

}));

(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["shuffle"] = factory();
	else
		root["shuffle"] = factory();
})(this, function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	module.exports = __webpack_require__(1).default;

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(2);
	
	var _matchesSelector = __webpack_require__(3);
	
	var _matchesSelector2 = _interopRequireDefault(_matchesSelector);
	
	var _arrayUniq = __webpack_require__(4);
	
	var _arrayUniq2 = _interopRequireDefault(_arrayUniq);
	
	var _xtend = __webpack_require__(5);
	
	var _xtend2 = _interopRequireDefault(_xtend);
	
	var _throttleit = __webpack_require__(6);
	
	var _throttleit2 = _interopRequireDefault(_throttleit);
	
	var _arrayParallel = __webpack_require__(7);
	
	var _arrayParallel2 = _interopRequireDefault(_arrayParallel);
	
	var _point = __webpack_require__(8);
	
	var _point2 = _interopRequireDefault(_point);
	
	var _shuffleItem = __webpack_require__(10);
	
	var _shuffleItem2 = _interopRequireDefault(_shuffleItem);
	
	var _classes = __webpack_require__(11);
	
	var _classes2 = _interopRequireDefault(_classes);
	
	var _getNumberStyle = __webpack_require__(12);
	
	var _getNumberStyle2 = _interopRequireDefault(_getNumberStyle);
	
	var _sorter = __webpack_require__(14);
	
	var _sorter2 = _interopRequireDefault(_sorter);
	
	var _onTransitionEnd = __webpack_require__(15);
	
	var _layout2 = __webpack_require__(16);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function toArray(arrayLike) {
	  return Array.prototype.slice.call(arrayLike);
	}
	
	function arrayMax(array) {
	  return Math.max.apply(Math, array);
	}
	
	function arrayIncludes(array, obj) {
	  if (arguments.length === 2) {
	    return arrayIncludes(array)(obj);
	  }
	
	  return function (obj) {
	    return array.indexOf(obj) > -1;
	  };
	}
	
	// Used for unique instance variables
	var id = 0;
	
	var Shuffle = function () {
	
	  /**
	   * Categorize, sort, and filter a responsive grid of items.
	   *
	   * @param {Element} element An element which is the parent container for the grid items.
	   * @param {Object} [options=Shuffle.options] Options object.
	   * @constructor
	   */
	  function Shuffle(element) {
	    var options = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];
	
	    _classCallCheck(this, Shuffle);
	
	    this.options = (0, _xtend2.default)(Shuffle.options, options);
	
	    this.useSizer = false;
	    this.lastSort = {};
	    this.group = this.lastFilter = Shuffle.ALL_ITEMS;
	    this.isEnabled = true;
	    this.isDestroyed = false;
	    this.isInitialized = false;
	    this._transitions = [];
	    this.isTransitioning = false;
	    this._queue = [];
	
	    element = this._getElementOption(element);
	
	    if (!element) {
	      throw new TypeError('Shuffle needs to be initialized with an element.');
	    }
	
	    this.element = element;
	    this.id = 'shuffle_' + id++;
	
	    this._init();
	    this.isInitialized = true;
	  }
	
	  _createClass(Shuffle, [{
	    key: '_init',
	    value: function _init() {
	      this.items = this._getItems();
	
	      this.options.sizer = this._getElementOption(this.options.sizer);
	
	      if (this.options.sizer) {
	        this.useSizer = true;
	      }
	
	      // Add class and invalidate styles
	      this.element.classList.add(Shuffle.Classes.BASE);
	
	      // Set initial css for each item
	      this._initItems();
	
	      // Bind resize events
	      this._onResize = this._getResizeFunction();
	      window.addEventListener('resize', this._onResize);
	
	      // Get container css all in one request. Causes reflow
	      var containerCss = window.getComputedStyle(this.element, null);
	      var containerWidth = Shuffle.getSize(this.element).width;
	
	      // Add styles to the container if it doesn't have them.
	      this._validateStyles(containerCss);
	
	      // We already got the container's width above, no need to cause another
	      // reflow getting it again... Calculate the number of columns there will be
	      this._setColumns(containerWidth);
	
	      // Kick off!
	      this.filter(this.options.group, this.options.initialSort);
	
	      // The shuffle items haven't had transitions set on them yet so the user
	      // doesn't see the first layout. Set them now that the first layout is done.
	      // First, however, a synchronous layout must be caused for the previous
	      // styles to be applied without transitions.
	      this.element.offsetWidth; // jshint ignore: line
	      this._setTransitions();
	      this.element.style.transition = 'height ' + this.options.speed + 'ms ' + this.options.easing;
	    }
	
	    /**
	     * Returns a throttled and proxied function for the resize handler.
	     * @return {Function}
	     * @private
	     */
	
	  }, {
	    key: '_getResizeFunction',
	    value: function _getResizeFunction() {
	      var resizeFunction = this._handleResize.bind(this);
	      return this.options.throttle ? this.options.throttle(resizeFunction, this.options.throttleTime) : resizeFunction;
	    }
	
	    /**
	     * Retrieve an element from an option.
	     * @param {string|jQuery|Element} option The option to check.
	     * @return {?Element} The plain element or null.
	     * @private
	     */
	
	  }, {
	    key: '_getElementOption',
	    value: function _getElementOption(option) {
	      // If column width is a string, treat is as a selector and search for the
	      // sizer element within the outermost container
	      if (typeof option === 'string') {
	        return this.element.querySelector(option);
	
	        // Check for an element
	      } else if (option && option.nodeType && option.nodeType === 1) {
	        return option;
	
	        // Check for jQuery object
	      } else if (option && option.jquery) {
	        return option[0];
	      }
	
	      return null;
	    }
	
	    /**
	     * Ensures the shuffle container has the css styles it needs applied to it.
	     * @param {Object} styles Key value pairs for position and overflow.
	     * @private
	     */
	
	  }, {
	    key: '_validateStyles',
	    value: function _validateStyles(styles) {
	      // Position cannot be static.
	      if (styles.position === 'static') {
	        this.element.style.position = 'relative';
	      }
	
	      // Overflow has to be hidden.
	      if (styles.overflow !== 'hidden') {
	        this.element.style.overflow = 'hidden';
	      }
	    }
	
	    /**
	     * Filter the elements by a category.
	     * @param {string} [category] Category to filter by. If it's given, the last
	     *     category will be used to filter the items.
	     * @param {Array} [collection] Optionally filter a collection. Defaults to
	     *     all the items.
	     * @return {!{visible: Array, hidden: Array}}
	     * @private
	     */
	
	  }, {
	    key: '_filter',
	    value: function _filter() {
	      var category = arguments.length <= 0 || arguments[0] === undefined ? this.lastFilter : arguments[0];
	      var collection = arguments.length <= 1 || arguments[1] === undefined ? this.items : arguments[1];
	
	      var set = this._getFilteredSets(category, collection);
	
	      // Individually add/remove hidden/visible classes
	      this._toggleFilterClasses(set);
	
	      // Save the last filter in case elements are appended.
	      this.lastFilter = category;
	
	      // This is saved mainly because providing a filter function (like searching)
	      // will overwrite the `lastFilter` property every time its called.
	      if (typeof category === 'string') {
	        this.group = category;
	      }
	
	      return set;
	    }
	
	    /**
	     * Returns an object containing the visible and hidden elements.
	     * @param {string|Function} category Category or function to filter by.
	     * @param {Array.<Element>} items A collection of items to filter.
	     * @return {!{visible: Array, hidden: Array}}
	     * @private
	     */
	
	  }, {
	    key: '_getFilteredSets',
	    value: function _getFilteredSets(category, items) {
	      var _this = this;
	
	      var visible = [];
	      var hidden = [];
	
	      // category === 'all', add visible class to everything
	      if (category === Shuffle.ALL_ITEMS) {
	        visible = items;
	
	        // Loop through each item and use provided function to determine
	        // whether to hide it or not.
	      } else {
	        items.forEach(function (item) {
	          if (_this._doesPassFilter(category, item.element)) {
	            visible.push(item);
	          } else {
	            hidden.push(item);
	          }
	        });
	      }
	
	      return {
	        visible: visible,
	        hidden: hidden
	      };
	    }
	
	    /**
	     * Test an item to see if it passes a category.
	     * @param {string|Function} category Category or function to filter by.
	     * @param {Element} element An element to test.
	     * @return {boolean} Whether it passes the category/filter.
	     * @private
	     */
	
	  }, {
	    key: '_doesPassFilter',
	    value: function _doesPassFilter(category, element) {
	
	      if (typeof category === 'function') {
	        return category.call(element, element, this);
	
	        // Check each element's data-groups attribute against the given category.
	      } else {
	        var attr = element.getAttribute('data-' + Shuffle.FILTER_ATTRIBUTE_KEY);
	        var keys = this.options.delimeter ? attr.split(this.options.delimeter) : JSON.parse(attr);
	
	        if (Array.isArray(category)) {
	          return category.some(arrayIncludes(keys));
	        }
	
	        return arrayIncludes(keys, category);
	      }
	    }
	
	    /**
	     * Toggles the visible and hidden class names.
	     * @param {{visible, hidden}} Object with visible and hidden arrays.
	     * @private
	     */
	
	  }, {
	    key: '_toggleFilterClasses',
	    value: function _toggleFilterClasses(_ref) {
	      var visible = _ref.visible;
	      var hidden = _ref.hidden;
	
	      visible.forEach(function (item) {
	        item.show();
	      });
	
	      hidden.forEach(function (item) {
	        item.hide();
	      });
	    }
	
	    /**
	     * Set the initial css for each item
	     * @param {Array.<ShuffleItem>} [items] Optionally specifiy at set to initialize.
	     * @private
	     */
	
	  }, {
	    key: '_initItems',
	    value: function _initItems() {
	      var items = arguments.length <= 0 || arguments[0] === undefined ? this.items : arguments[0];
	
	      items.forEach(function (item) {
	        item.init();
	      });
	    }
	
	    /**
	     * Remove element reference and styles.
	     * @private
	     */
	
	  }, {
	    key: '_disposeItems',
	    value: function _disposeItems() {
	      var items = arguments.length <= 0 || arguments[0] === undefined ? this.items : arguments[0];
	
	      items.forEach(function (item) {
	        item.dispose();
	      });
	    }
	
	    /**
	     * Updates the visible item count.
	     * @private
	     */
	
	  }, {
	    key: '_updateItemCount',
	    value: function _updateItemCount() {
	      this.visibleItems = this._getFilteredItems().length;
	    }
	
	    /**
	     * Sets css transform transition on a group of elements. This is not executed
	     * at the same time as `item.init` so that transitions don't occur upon
	     * initialization of Shuffle.
	     * @param {Array.<ShuffleItem>} items Shuffle items to set transitions on.
	     * @private
	     */
	
	  }, {
	    key: '_setTransitions',
	    value: function _setTransitions() {
	      var items = arguments.length <= 0 || arguments[0] === undefined ? this.items : arguments[0];
	
	      var speed = this.options.speed;
	      var easing = this.options.easing;
	
	      var str;
	      if (this.options.useTransforms) {
	        str = 'transform ' + speed + 'ms ' + easing + ', opacity ' + speed + 'ms ' + easing;
	      } else {
	        str = 'top ' + speed + 'ms ' + easing + ', left ' + speed + 'ms ' + easing + ', opacity ' + speed + 'ms ' + easing;
	      }
	
	      items.forEach(function (item) {
	        item.element.style.transition = str;
	      });
	    }
	  }, {
	    key: '_getItems',
	    value: function _getItems() {
	      var _this2 = this;
	
	      return toArray(this.element.children).filter(function (el) {
	        return (0, _matchesSelector2.default)(el, _this2.options.itemSelector);
	      }).map(function (el) {
	        return new _shuffleItem2.default(el);
	      });
	    }
	
	    /**
	     * When new elements are added to the shuffle container, update the array of
	     * items because that is the order `_layout` calls them.
	     */
	
	  }, {
	    key: '_updateItemsOrder',
	    value: function _updateItemsOrder() {
	      var children = this.element.children;
	      this.items = (0, _sorter2.default)(this.items, {
	        by: function by(element) {
	          return Array.prototype.indexOf.call(children, element);
	        }
	      });
	    }
	  }, {
	    key: '_getFilteredItems',
	    value: function _getFilteredItems() {
	      return this.items.filter(function (item) {
	        return item.isVisible;
	      });
	    }
	  }, {
	    key: '_getConcealedItems',
	    value: function _getConcealedItems() {
	      return this.items.filter(function (item) {
	        return !item.isVisible;
	      });
	    }
	
	    /**
	     * Returns the column size, based on column width and sizer options.
	     * @param {number} containerWidth Size of the parent container.
	     * @param {number} gutterSize Size of the gutters.
	     * @return {number}
	     * @private
	     */
	
	  }, {
	    key: '_getColumnSize',
	    value: function _getColumnSize(containerWidth, gutterSize) {
	      var size;
	
	      // If the columnWidth property is a function, then the grid is fluid
	      if (typeof this.options.columnWidth === 'function') {
	        size = this.options.columnWidth(containerWidth);
	
	        // columnWidth option isn't a function, are they using a sizing element?
	      } else if (this.useSizer) {
	        size = Shuffle.getSize(this.options.sizer).width;
	
	        // if not, how about the explicitly set option?
	      } else if (this.options.columnWidth) {
	        size = this.options.columnWidth;
	
	        // or use the size of the first item
	      } else if (this.items.length > 0) {
	        size = Shuffle.getSize(this.items[0].element, true).width;
	
	        // if there's no items, use size of container
	      } else {
	        size = containerWidth;
	      }
	
	      // Don't let them set a column width of zero.
	      if (size === 0) {
	        size = containerWidth;
	      }
	
	      return size + gutterSize;
	    }
	
	    /**
	     * Returns the gutter size, based on gutter width and sizer options.
	     * @param {number} containerWidth Size of the parent container.
	     * @return {number}
	     * @private
	     */
	
	  }, {
	    key: '_getGutterSize',
	    value: function _getGutterSize(containerWidth) {
	      var size;
	      if (typeof this.options.gutterWidth === 'function') {
	        size = this.options.gutterWidth(containerWidth);
	      } else if (this.useSizer) {
	        size = (0, _getNumberStyle2.default)(this.options.sizer, 'marginLeft');
	      } else {
	        size = this.options.gutterWidth;
	      }
	
	      return size;
	    }
	
	    /**
	     * Calculate the number of columns to be used. Gets css if using sizer element.
	     * @param {number} [containerWidth] Optionally specify a container width if
	     *    it's already available.
	     */
	
	  }, {
	    key: '_setColumns',
	    value: function _setColumns() {
	      var containerWidth = arguments.length <= 0 || arguments[0] === undefined ? Shuffle.getSize(this.element).width : arguments[0];
	
	      var gutter = this._getGutterSize(containerWidth);
	      var columnWidth = this._getColumnSize(containerWidth, gutter);
	      var calculatedColumns = (containerWidth + gutter) / columnWidth;
	
	      // Widths given from getStyles are not precise enough...
	      if (Math.abs(Math.round(calculatedColumns) - calculatedColumns) < this.options.columnThreshold) {
	        // e.g. calculatedColumns = 11.998876
	        calculatedColumns = Math.round(calculatedColumns);
	      }
	
	      this.cols = Math.max(Math.floor(calculatedColumns), 1);
	      this.containerWidth = containerWidth;
	      this.colWidth = columnWidth;
	    }
	
	    /**
	     * Adjust the height of the grid
	     */
	
	  }, {
	    key: '_setContainerSize',
	    value: function _setContainerSize() {
	      this.element.style.height = this._getContainerSize() + 'px';
	    }
	
	    /**
	     * Based on the column heights, it returns the biggest one.
	     * @return {number}
	     * @private
	     */
	
	  }, {
	    key: '_getContainerSize',
	    value: function _getContainerSize() {
	      return arrayMax(this.positions);
	    }
	
	    /**
	     * Get the clamped stagger amount.
	     * @param {number} index Index of the item to be staggered.
	     * @return {number}
	     */
	
	  }, {
	    key: '_getStaggerAmount',
	    value: function _getStaggerAmount(index) {
	      return Math.min(index * this.options.staggerAmount, this.options.staggerAmountMax);
	    }
	
	    /**
	     * @return {boolean} Whether the event was prevented or not.
	     */
	
	  }, {
	    key: '_dispatch',
	    value: function _dispatch(name) {
	      var details = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];
	
	      if (this.isDestroyed) {
	        return;
	      }
	
	      details.shuffle = this;
	      return !this.element.dispatchEvent(new CustomEvent(name, {
	        bubbles: true,
	        cancelable: false,
	        detail: details
	      }));
	    }
	
	    /**
	     * Zeros out the y columns array, which is used to determine item placement.
	     * @private
	     */
	
	  }, {
	    key: '_resetCols',
	    value: function _resetCols() {
	      var i = this.cols;
	      this.positions = [];
	      while (i--) {
	        this.positions.push(0);
	      }
	    }
	
	    /**
	     * Loops through each item that should be shown and calculates the x, y position.
	     * @param {Array.<ShuffleItem>} items Array of items that will be shown/layed
	     *     out in order in their array.
	     */
	
	  }, {
	    key: '_layout',
	    value: function _layout(items) {
	      var _this3 = this;
	
	      var count = 0;
	      items.forEach(function (item) {
	        var currPos = item.point;
	        var currScale = item.scale;
	        var itemSize = Shuffle.getSize(item.element, true);
	        var pos = _this3._getItemPosition(itemSize);
	
	        function callback() {
	          item.element.style.transitionDelay = '';
	          item.applyCss(_shuffleItem2.default.Css.VISIBLE.after);
	        }
	
	        // If the item will not change its position, do not add it to the render
	        // queue. Transitions don't fire when setting a property to the same value.
	        if (_point2.default.equals(currPos, pos) && currScale === _shuffleItem2.default.Scale.VISIBLE) {
	          callback();
	          return;
	        }
	
	        item.point = pos;
	        item.scale = _shuffleItem2.default.Scale.VISIBLE;
	
	        // Use xtend here to clone the object so that the `before` object isn't
	        // modified when the transition delay is added.
	        var styles = (0, _xtend2.default)(_shuffleItem2.default.Css.VISIBLE.before);
	        styles.transitionDelay = _this3._getStaggerAmount(count) + 'ms';
	
	        _this3._queue.push({
	          item: item,
	          styles: styles,
	          callback: callback
	        });
	
	        count++;
	      });
	    }
	
	    /**
	     * Determine the location of the next item, based on its size.
	     * @param {{width: number, height: number}} itemSize Object with width and height.
	     * @return {Point}
	     * @private
	     */
	
	  }, {
	    key: '_getItemPosition',
	    value: function _getItemPosition(itemSize) {
	      return (0, _layout2.getItemPosition)({
	        itemSize: itemSize,
	        positions: this.positions,
	        gridSize: this.colWidth,
	        total: this.cols,
	        threshold: this.options.columnThreshold,
	        buffer: this.options.buffer
	      });
	    }
	
	    /**
	     * Hides the elements that don't match our filter.
	     * @param {Array.<ShuffleItem>} collection Collection to shrink.
	     * @private
	     */
	
	  }, {
	    key: '_shrink',
	    value: function _shrink() {
	      var _this4 = this;
	
	      var collection = arguments.length <= 0 || arguments[0] === undefined ? this._getConcealedItems() : arguments[0];
	
	      var count = 0;
	      collection.forEach(function (item) {
	        function callback() {
	          item.applyCss(_shuffleItem2.default.Css.HIDDEN.after);
	        }
	
	        // Continuing would add a transitionend event listener to the element, but
	        // that listener would not execute because the transform and opacity would
	        // stay the same.
	        // The callback is executed here because it is not guaranteed to be called
	        // after the transitionend event because the transitionend could be
	        // canceled if another animation starts.
	        if (item.scale === _shuffleItem2.default.Scale.HIDDEN) {
	          callback();
	          return;
	        }
	
	        item.scale = _shuffleItem2.default.Scale.HIDDEN;
	
	        var styles = (0, _xtend2.default)(_shuffleItem2.default.Css.HIDDEN.before);
	        styles.transitionDelay = _this4._getStaggerAmount(count) + 'ms';
	
	        _this4._queue.push({
	          item: item,
	          styles: styles,
	          callback: callback
	        });
	
	        count++;
	      });
	    }
	
	    /**
	     * Resize handler.
	     * @private
	     */
	
	  }, {
	    key: '_handleResize',
	    value: function _handleResize() {
	      // If shuffle is disabled, destroyed, don't do anything
	      if (!this.isEnabled || this.isDestroyed) {
	        return;
	      }
	
	      // Will need to check height in the future if it's layed out horizontaly
	      var containerWidth = Shuffle.getSize(this.element).width;
	
	      // containerWidth hasn't changed, don't do anything
	      if (containerWidth === this.containerWidth) {
	        return;
	      }
	
	      this.update();
	    }
	
	    /**
	     * Returns styles which will be applied to the an item for a transition.
	     * @param {Object} obj Transition options.
	     * @return {!Object} Transforms for transitions, left/top for animate.
	     * @private
	     */
	
	  }, {
	    key: '_getStylesForTransition',
	    value: function _getStylesForTransition(_ref2) {
	      var item = _ref2.item;
	      var styles = _ref2.styles;
	
	      if (!styles.transitionDelay) {
	        styles.transitionDelay = '0ms';
	      }
	
	      var x = item.point.x;
	      var y = item.point.y;
	
	      if (this.options.useTransforms) {
	        styles.transform = 'translate(' + x + 'px, ' + y + 'px) scale(' + item.scale + ')';
	      } else {
	        styles.left = x + 'px';
	        styles.top = y + 'px';
	      }
	
	      return styles;
	    }
	
	    /**
	     * Listen for the transition end on an element and execute the itemCallback
	     * when it finishes.
	     * @param {Element} element Element to listen on.
	     * @param {Function} itemCallback Callback for the item.
	     * @param {Function} done Callback to notify `parallel` that this one is done.
	     */
	
	  }, {
	    key: '_whenTransitionDone',
	    value: function _whenTransitionDone(element, itemCallback, done) {
	      var id = (0, _onTransitionEnd.onTransitionEnd)(element, function (evt) {
	        itemCallback();
	        done(null, evt);
	      });
	
	      this._transitions.push(id);
	    }
	
	    /**
	     * Return a function which will set CSS styles and call the `done` function
	     * when (if) the transition finishes.
	     * @param {Object} opts Transition object.
	     * @return {Function} A function to be called with a `done` function.
	     */
	
	  }, {
	    key: '_getTransitionFunction',
	    value: function _getTransitionFunction(opts) {
	      var _this5 = this;
	
	      return function (done) {
	        opts.item.applyCss(_this5._getStylesForTransition(opts));
	        _this5._whenTransitionDone(opts.item.element, opts.callback, done);
	      };
	    }
	
	    /**
	     * Execute the styles gathered in the style queue. This applies styles to elements,
	     * triggering transitions.
	     * @private
	     */
	
	  }, {
	    key: '_processQueue',
	    value: function _processQueue() {
	      if (this.isTransitioning) {
	        this._cancelMovement();
	      }
	
	      var hasSpeed = this.options.speed > 0;
	      var hasQueue = this._queue.length > 0;
	
	      if (hasQueue && hasSpeed && this.isInitialized) {
	        this._startTransitions(this._queue);
	      } else if (hasQueue) {
	        this._styleImmediately(this._queue);
	        this._dispatchLayout();
	
	        // A call to layout happened, but none of the newly visible items will
	        // change position or the transition duration is zero, which will not trigger
	        // the transitionend event.
	      } else {
	        this._dispatchLayout();
	      }
	
	      // Remove everything in the style queue
	      this._queue.length = 0;
	    }
	
	    /**
	     * Wait for each transition to finish, the emit the layout event.
	     * @param {Array.<Object>} transitions Array of transition objects.
	     */
	
	  }, {
	    key: '_startTransitions',
	    value: function _startTransitions(transitions) {
	      var _this6 = this;
	
	      // Set flag that shuffle is currently in motion.
	      this.isTransitioning = true;
	
	      // Create an array of functions to be called.
	      var callbacks = transitions.map(function (obj) {
	        return _this6._getTransitionFunction(obj);
	      });
	
	      (0, _arrayParallel2.default)(callbacks, this._movementFinished.bind(this));
	    }
	  }, {
	    key: '_cancelMovement',
	    value: function _cancelMovement() {
	      // Remove the transition end event for each listener.
	      this._transitions.forEach(_onTransitionEnd.cancelTransitionEnd);
	
	      // Reset the array.
	      this._transitions.length = 0;
	
	      // Show it's no longer active.
	      this.isTransitioning = false;
	    }
	
	    /**
	     * Apply styles without a transition.
	     * @param {Array.<Object>} objects Array of transition objects.
	     * @private
	     */
	
	  }, {
	    key: '_styleImmediately',
	    value: function _styleImmediately(objects) {
	      var _this7 = this;
	
	      if (objects.length) {
	        var elements = objects.map(function (obj) {
	          return obj.item.element;
	        });
	
	        Shuffle._skipTransitions(elements, function () {
	          objects.forEach(function (obj) {
	            obj.item.applyCss(_this7._getStylesForTransition(obj));
	            obj.callback();
	          });
	        });
	      }
	    }
	  }, {
	    key: '_movementFinished',
	    value: function _movementFinished() {
	      this._transitions.length = 0;
	      this.isTransitioning = false;
	      this._dispatchLayout();
	    }
	  }, {
	    key: '_dispatchLayout',
	    value: function _dispatchLayout() {
	      this._dispatch(Shuffle.EventType.LAYOUT);
	    }
	
	    /**
	     * The magic. This is what makes the plugin 'shuffle'
	     * @param {string|Function|Array.<string>} [category] Category to filter by.
	     *     Can be a function, string, or array of strings.
	     * @param {Object} [sortObj] A sort object which can sort the visible set
	     */
	
	  }, {
	    key: 'filter',
	    value: function filter(category, sortObj) {
	      if (!this.isEnabled) {
	        return;
	      }
	
	      if (!category || category && category.length === 0) {
	        category = Shuffle.ALL_ITEMS;
	      }
	
	      this._filter(category);
	
	      // Shrink each hidden item
	      this._shrink();
	
	      // How many visible elements?
	      this._updateItemCount();
	
	      // Update transforms on visible elements so they will animate to their new positions.
	      this.sort(sortObj);
	    }
	
	    /**
	     * Gets the visible elements, sorts them, and passes them to layout.
	     * @param {Object} opts the options object for the sorted plugin
	     */
	
	  }, {
	    key: 'sort',
	    value: function sort() {
	      var opts = arguments.length <= 0 || arguments[0] === undefined ? this.lastSort : arguments[0];
	
	      if (!this.isEnabled) {
	        return;
	      }
	
	      this._resetCols();
	
	      var items = this._getFilteredItems();
	      items = (0, _sorter2.default)(items, opts);
	
	      this._layout(items);
	
	      // `_layout` always happens after `_shrink`, so it's safe to process the style
	      // queue here with styles from the shrink method.
	      this._processQueue();
	
	      // Adjust the height of the container.
	      this._setContainerSize();
	
	      this.lastSort = opts;
	    }
	
	    /**
	     * Reposition everything.
	     * @param {boolean} isOnlyLayout If true, column and gutter widths won't be
	     *     recalculated.
	     */
	
	  }, {
	    key: 'update',
	    value: function update(isOnlyLayout) {
	      if (this.isEnabled) {
	
	        if (!isOnlyLayout) {
	          // Get updated colCount
	          this._setColumns();
	        }
	
	        // Layout items
	        this.sort();
	      }
	    }
	
	    /**
	     * Use this instead of `update()` if you don't need the columns and gutters updated
	     * Maybe an image inside `shuffle` loaded (and now has a height), which means calculations
	     * could be off.
	     */
	
	  }, {
	    key: 'layout',
	    value: function layout() {
	      this.update(true);
	    }
	
	    /**
	     * New items have been appended to shuffle. Mix them in with the current
	     * filter or sort status.
	     * @param {Array.<Element>} newItems Collection of new items.
	     */
	
	  }, {
	    key: 'add',
	    value: function add(newItems) {
	      newItems = (0, _arrayUniq2.default)(newItems).map(function (el) {
	        return new _shuffleItem2.default(el);
	      });
	
	      // Add classes and set initial positions.
	      this._initItems(newItems);
	
	      // Add transition to each item.
	      this._setTransitions(newItems);
	
	      // Update the list of items.
	      this.items = this.items.concat(newItems);
	      this._updateItemsOrder();
	      this.filter(this.lastFilter);
	    }
	
	    /**
	     * Disables shuffle from updating dimensions and layout on resize
	     */
	
	  }, {
	    key: 'disable',
	    value: function disable() {
	      this.isEnabled = false;
	    }
	
	    /**
	     * Enables shuffle again
	     * @param {boolean} [isUpdateLayout=true] if undefined, shuffle will update columns and gutters
	     */
	
	  }, {
	    key: 'enable',
	    value: function enable(isUpdateLayout) {
	      this.isEnabled = true;
	      if (isUpdateLayout !== false) {
	        this.update();
	      }
	    }
	
	    /**
	     * Remove 1 or more shuffle items
	     * @param {Array.<Element>} collection An array containing one or more
	     *     elements in shuffle
	     * @return {Shuffle} The shuffle object
	     */
	
	  }, {
	    key: 'remove',
	    value: function remove(collection) {
	      var _this8 = this;
	
	      if (!collection.length) {
	        return;
	      }
	
	      collection = (0, _arrayUniq2.default)(collection);
	
	      var oldItems = collection.map(function (element) {
	        return _this8.getItemByElement(element);
	      }).filter(function (item) {
	        return !!item;
	      });
	
	      var handleLayout = function handleLayout() {
	        _this8.element.removeEventListener(Shuffle.EventType.LAYOUT, handleLayout);
	        _this8._disposeItems(oldItems);
	
	        // Remove the collection in the callback
	        collection.forEach(function (element) {
	          element.parentNode.removeChild(element);
	        });
	
	        _this8._dispatch(Shuffle.EventType.REMOVED, { collection: collection });
	
	        // Let it get garbage collected
	        collection = null;
	        oldItems = null;
	      };
	
	      // Hide collection first.
	      this._toggleFilterClasses({
	        visible: [],
	        hidden: oldItems
	      });
	
	      this._shrink(oldItems);
	
	      this.sort();
	
	      // Update the list of items here because `remove` could be called again
	      // with an item that is in the process of being removed.
	      this.items = this.items.filter(function (item) {
	        return !arrayIncludes(oldItems, item);
	      });
	      this._updateItemCount();
	
	      this.element.addEventListener(Shuffle.EventType.LAYOUT, handleLayout);
	    }
	
	    /**
	     * Retrieve a shuffle item by its element.
	     * @param {Element} element Element to look for.
	     * @return {?ShuffleItem} A shuffle item or null if it's not found.
	     */
	
	  }, {
	    key: 'getItemByElement',
	    value: function getItemByElement(element) {
	      for (var i = this.items.length - 1; i >= 0; i--) {
	        if (this.items[i].element === element) {
	          return this.items[i];
	        }
	      }
	
	      return null;
	    }
	
	    /**
	     * Destroys shuffle, removes events, styles, and classes
	     */
	
	  }, {
	    key: 'destroy',
	    value: function destroy() {
	      this._cancelMovement();
	      window.removeEventListener('resize', this._onResize);
	
	      // Reset container styles
	      this.element.classList.remove('shuffle');
	      this.element.removeAttribute('style');
	
	      // Reset individual item styles
	      this._disposeItems();
	
	      // Null DOM references
	      this.items = null;
	      this.options.sizer = null;
	      this.element = null;
	      this._transitions = null;
	
	      // Set a flag so if a debounced resize has been triggered,
	      // it can first check if it is actually isDestroyed and not doing anything
	      this.isDestroyed = true;
	    }
	
	    /**
	     * Returns the outer width of an element, optionally including its margins.
	     *
	     * There are a few different methods for getting the width of an element, none of
	     * which work perfectly for all Shuffle's use cases.
	     *
	     * 1. getBoundingClientRect() `left` and `right` properties.
	     *   - Accounts for transform scaled elements, making it useless for Shuffle
	     *   elements which have shrunk.
	     * 2. The `offsetWidth` property.
	     *   - This value stays the same regardless of the elements transform property,
	     *   however, it does not return subpixel values.
	     * 3. getComputedStyle()
	     *   - This works great Chrome, Firefox, Safari, but IE<=11 does not include
	     *   padding and border when box-sizing: border-box is set, requiring a feature
	     *   test and extra work to add the padding back for IE and other browsers which
	     *   follow the W3C spec here.
	     *
	     * @param {Element} element The element.
	     * @param {boolean} [includeMargins] Whether to include margins. Default is false.
	     * @return {{width: number, height: number}} The width and height.
	     */
	
	  }], [{
	    key: 'getSize',
	    value: function getSize(element, includeMargins) {
	      // Store the styles so that they can be used by others without asking for it again.
	      var styles = window.getComputedStyle(element, null);
	      var width = (0, _getNumberStyle2.default)(element, 'width', styles);
	      var height = (0, _getNumberStyle2.default)(element, 'height', styles);
	
	      if (includeMargins) {
	        var marginLeft = (0, _getNumberStyle2.default)(element, 'marginLeft', styles);
	        var marginRight = (0, _getNumberStyle2.default)(element, 'marginRight', styles);
	        var marginTop = (0, _getNumberStyle2.default)(element, 'marginTop', styles);
	        var marginBottom = (0, _getNumberStyle2.default)(element, 'marginBottom', styles);
	        width += marginLeft + marginRight;
	        height += marginTop + marginBottom;
	      }
	
	      return {
	        width: width,
	        height: height
	      };
	    }
	
	    /**
	     * Change a property or execute a function which will not have a transition
	     * @param {Array.<Element>} elements DOM elements that won't be transitioned.
	     * @param {Function} callback A function which will be called while transition
	     *     is set to 0ms.
	     * @private
	     */
	
	  }, {
	    key: '_skipTransitions',
	    value: function _skipTransitions(elements, callback) {
	      var zero = '0ms';
	
	      // Save current duration and delay.
	      var data = elements.map(function (element) {
	        var style = element.style;
	        var duration = style.transitionDuration;
	        var delay = style.transitionDelay;
	
	        // Set the duration to zero so it happens immediately
	        style.transitionDuration = zero;
	        style.transitionDelay = zero;
	
	        return {
	          duration: duration,
	          delay: delay
	        };
	      });
	
	      callback();
	
	      // Cause reflow.
	      elements[0].offsetWidth; // jshint ignore:line
	
	      // Put the duration back
	      elements.forEach(function (element, i) {
	        element.style.transitionDuration = data[i].duration;
	        element.style.transitionDelay = data[i].delay;
	      });
	    }
	  }]);
	
	  return Shuffle;
	}();
	
	Shuffle.ShuffleItem = _shuffleItem2.default;
	
	Shuffle.ALL_ITEMS = 'all';
	Shuffle.FILTER_ATTRIBUTE_KEY = 'groups';
	
	/**
	 * @enum {string}
	 */
	Shuffle.EventType = {
	  LAYOUT: 'shuffle:layout',
	  REMOVED: 'shuffle:removed'
	};
	
	/** @enum {string} */
	Shuffle.Classes = _classes2.default;
	
	// Overrideable options
	Shuffle.options = {
	  // Initial filter group.
	  group: Shuffle.ALL_ITEMS,
	
	  // Transition/animation speed (milliseconds).
	  speed: 250,
	
	  // CSS easing function to use.
	  easing: 'ease',
	
	  // e.g. '.picture-item'.
	  itemSelector: '*',
	
	  // Element or selector string. Use an element to determine the size of columns
	  // and gutters.
	  sizer: null,
	
	  // A static number or function that tells the plugin how wide the gutters
	  // between columns are (in pixels).
	  gutterWidth: 0,
	
	  // A static number or function that returns a number which tells the plugin
	  // how wide the columns are (in pixels).
	  columnWidth: 0,
	
	  // If your group is not json, and is comma delimeted, you could set delimeter
	  // to ','.
	  delimeter: null,
	
	  // Useful for percentage based heights when they might not always be exactly
	  // the same (in pixels).
	  buffer: 0,
	
	  // Reading the width of elements isn't precise enough and can cause columns to
	  // jump between values.
	  columnThreshold: 0.01,
	
	  // Shuffle can be isInitialized with a sort object. It is the same object
	  // given to the sort method.
	  initialSort: null,
	
	  // By default, shuffle will throttle resize events. This can be changed or
	  // removed.
	  throttle: _throttleit2.default,
	
	  // How often shuffle can be called on resize (in milliseconds).
	  throttleTime: 300,
	
	  // Transition delay offset for each item in milliseconds.
	  staggerAmount: 15,
	
	  // Maximum stagger delay in milliseconds.
	  staggerAmountMax: 250,
	
	  // Whether to use transforms or absolute positioning.
	  useTransforms: true
	};
	
	// Expose for testing. Hack at your own risk.
	Shuffle.__Point = _point2.default;
	Shuffle.__sorter = _sorter2.default;
	Shuffle.__getColumnSpan = _layout2.getColumnSpan;
	Shuffle.__getAvailablePositions = _layout2.getAvailablePositions;
	Shuffle.__getShortColumn = _layout2.getShortColumn;
	
	exports.default = Shuffle;

/***/ },
/* 2 */
/***/ function(module, exports) {

	// Polyfill for creating CustomEvents on IE9/10/11
	
	// code pulled from:
	// https://github.com/d4tocchini/customevent-polyfill
	// https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent#Polyfill
	
	try {
	  new window.CustomEvent("test");
	} catch(e) {
	 var CustomEvent = function(event, params) {
	      var evt;
	      params = params || {
	          bubbles: false,
	          cancelable: false,
	          detail: undefined
	      };
	
	      evt = document.createEvent("CustomEvent");
	      evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
	      return evt;
	  };
	
	  CustomEvent.prototype = window.Event.prototype;
	  window.CustomEvent = CustomEvent; // expose definition to window
	}


/***/ },
/* 3 */
/***/ function(module, exports) {

	'use strict';
	
	var proto = Element.prototype;
	var vendor = proto.matches
	  || proto.matchesSelector
	  || proto.webkitMatchesSelector
	  || proto.mozMatchesSelector
	  || proto.msMatchesSelector
	  || proto.oMatchesSelector;
	
	module.exports = match;
	
	/**
	 * Match `el` to `selector`.
	 *
	 * @param {Element} el
	 * @param {String} selector
	 * @return {Boolean}
	 * @api public
	 */
	
	function match(el, selector) {
	  if (vendor) return vendor.call(el, selector);
	  var nodes = el.parentNode.querySelectorAll(selector);
	  for (var i = 0; i < nodes.length; i++) {
	    if (nodes[i] == el) return true;
	  }
	  return false;
	}

/***/ },
/* 4 */
/***/ function(module, exports) {

	/* WEBPACK VAR INJECTION */(function(global) {'use strict';
	
	// there's 3 implementations written in increasing order of efficiency
	
	// 1 - no Set type is defined
	function uniqNoSet(arr) {
		var ret = [];
	
		for (var i = 0; i < arr.length; i++) {
			if (ret.indexOf(arr[i]) === -1) {
				ret.push(arr[i]);
			}
		}
	
		return ret;
	}
	
	// 2 - a simple Set type is defined
	function uniqSet(arr) {
		var seen = new Set();
		return arr.filter(function (el) {
			if (!seen.has(el)) {
				seen.add(el);
				return true;
			}
	
			return false;
		});
	}
	
	// 3 - a standard Set type is defined and it has a forEach method
	function uniqSetWithForEach(arr) {
		var ret = [];
	
		(new Set(arr)).forEach(function (el) {
			ret.push(el);
		});
	
		return ret;
	}
	
	// V8 currently has a broken implementation
	// https://github.com/joyent/node/issues/8449
	function doesForEachActuallyWork() {
		var ret = false;
	
		(new Set([true])).forEach(function (el) {
			ret = el;
		});
	
		return ret === true;
	}
	
	if ('Set' in global) {
		if (typeof Set.prototype.forEach === 'function' && doesForEachActuallyWork()) {
			module.exports = uniqSetWithForEach;
		} else {
			module.exports = uniqSet;
		}
	} else {
		module.exports = uniqNoSet;
	}
	
	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }())))

/***/ },
/* 5 */
/***/ function(module, exports) {

	module.exports = extend
	
	var hasOwnProperty = Object.prototype.hasOwnProperty;
	
	function extend() {
	    var target = {}
	
	    for (var i = 0; i < arguments.length; i++) {
	        var source = arguments[i]
	
	        for (var key in source) {
	            if (hasOwnProperty.call(source, key)) {
	                target[key] = source[key]
	            }
	        }
	    }
	
	    return target
	}


/***/ },
/* 6 */
/***/ function(module, exports) {

	module.exports = throttle;
	
	/**
	 * Returns a new function that, when invoked, invokes `func` at most once per `wait` milliseconds.
	 *
	 * @param {Function} func Function to wrap.
	 * @param {Number} wait Number of milliseconds that must elapse between `func` invocations.
	 * @return {Function} A new function that wraps the `func` function passed in.
	 */
	
	function throttle (func, wait) {
	  var ctx, args, rtn, timeoutID; // caching
	  var last = 0;
	
	  return function throttled () {
	    ctx = this;
	    args = arguments;
	    var delta = new Date() - last;
	    if (!timeoutID)
	      if (delta >= wait) call();
	      else timeoutID = setTimeout(call, wait - delta);
	    return rtn;
	  };
	
	  function call () {
	    timeoutID = 0;
	    last = +new Date();
	    rtn = func.apply(ctx, args);
	    ctx = null;
	    args = null;
	  }
	}


/***/ },
/* 7 */
/***/ function(module, exports) {

	module.exports = function parallel(fns, context, callback) {
	  if (!callback) {
	    if (typeof context === 'function') {
	      callback = context
	      context = null
	    } else {
	      callback = noop
	    }
	  }
	
	  var pending = fns && fns.length
	  if (!pending) return callback(null, []);
	
	  var finished = false
	  var results = new Array(pending)
	
	  fns.forEach(context ? function (fn, i) {
	    fn.call(context, maybeDone(i))
	  } : function (fn, i) {
	    fn(maybeDone(i))
	  })
	
	  function maybeDone(i) {
	    return function (err, result) {
	      if (finished) return;
	
	      if (err) {
	        callback(err, results)
	        finished = true
	        return
	      }
	
	      results[i] = result
	
	      if (!--pending) callback(null, results);
	    }
	  }
	}
	
	function noop() {}


/***/ },
/* 8 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	
	var _getNumber = __webpack_require__(9);
	
	var _getNumber2 = _interopRequireDefault(_getNumber);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	/**
	 * Represents a coordinate pair.
	 * @param {number} [x=0] X.
	 * @param {number} [y=0] Y.
	 */
	var Point = function Point(x, y) {
	  this.x = (0, _getNumber2.default)(x);
	  this.y = (0, _getNumber2.default)(y);
	};
	
	/**
	 * Whether two points are equal.
	 * @param {Point} a Point A.
	 * @param {Point} b Point B.
	 * @return {boolean}
	 */
	Point.equals = function (a, b) {
	  return a.x === b.x && a.y === b.y;
	};
	
	exports.default = Point;

/***/ },
/* 9 */
/***/ function(module, exports) {

	'use strict';
	
	/**
	 * Always returns a numeric value, given a value. Logic from jQuery's `isNumeric`.
	 * @param {*} value Possibly numeric value.
	 * @return {number} `value` or zero if `value` isn't numeric.
	 */
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.default = getNumber;
	function getNumber(value) {
	  return parseFloat(value) || 0;
	}

/***/ },
/* 10 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _point = __webpack_require__(8);
	
	var _point2 = _interopRequireDefault(_point);
	
	var _classes = __webpack_require__(11);
	
	var _classes2 = _interopRequireDefault(_classes);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	var id = 0;
	
	var ShuffleItem = function () {
	  function ShuffleItem(element) {
	    _classCallCheck(this, ShuffleItem);
	
	    this.id = id++;
	    this.element = element;
	    this.isVisible = true;
	  }
	
	  _createClass(ShuffleItem, [{
	    key: 'show',
	    value: function show() {
	      this.isVisible = true;
	      this.element.classList.remove(_classes2.default.HIDDEN);
	      this.element.classList.add(_classes2.default.VISIBLE);
	    }
	  }, {
	    key: 'hide',
	    value: function hide() {
	      this.isVisible = false;
	      this.element.classList.remove(_classes2.default.VISIBLE);
	      this.element.classList.add(_classes2.default.HIDDEN);
	    }
	  }, {
	    key: 'init',
	    value: function init() {
	      this.addClasses([_classes2.default.SHUFFLE_ITEM, _classes2.default.VISIBLE]);
	      this.applyCss(ShuffleItem.Css.INITIAL);
	      this.scale = ShuffleItem.Scale.VISIBLE;
	      this.point = new _point2.default();
	    }
	  }, {
	    key: 'addClasses',
	    value: function addClasses(classes) {
	      var _this = this;
	
	      classes.forEach(function (className) {
	        _this.element.classList.add(className);
	      });
	    }
	  }, {
	    key: 'removeClasses',
	    value: function removeClasses(classes) {
	      var _this2 = this;
	
	      classes.forEach(function (className) {
	        _this2.element.classList.remove(className);
	      });
	    }
	  }, {
	    key: 'applyCss',
	    value: function applyCss(obj) {
	      for (var key in obj) {
	        this.element.style[key] = obj[key];
	      }
	    }
	  }, {
	    key: 'dispose',
	    value: function dispose() {
	      this.removeClasses([_classes2.default.HIDDEN, _classes2.default.VISIBLE, _classes2.default.SHUFFLE_ITEM]);
	
	      this.element.removeAttribute('style');
	      this.element = null;
	    }
	  }]);
	
	  return ShuffleItem;
	}();
	
	ShuffleItem.Css = {
	  INITIAL: {
	    position: 'absolute',
	    top: 0,
	    left: 0,
	    visibility: 'visible',
	    'will-change': 'transform'
	  },
	  VISIBLE: {
	    before: {
	      opacity: 1,
	      visibility: 'visible'
	    },
	    after: {}
	  },
	  HIDDEN: {
	    before: {
	      opacity: 0
	    },
	    after: {
	      visibility: 'hidden'
	    }
	  }
	};
	
	ShuffleItem.Scale = {
	  VISIBLE: 1,
	  HIDDEN: 0.001
	};
	
	exports.default = ShuffleItem;

/***/ },
/* 11 */
/***/ function(module, exports) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.default = {
	  BASE: 'shuffle',
	  SHUFFLE_ITEM: 'shuffle-item',
	  VISIBLE: 'shuffle-item--visible',
	  HIDDEN: 'shuffle-item--hidden'
	};

/***/ },
/* 12 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.default = getNumberStyle;
	
	var _getNumber = __webpack_require__(9);
	
	var _getNumber2 = _interopRequireDefault(_getNumber);
	
	var _computedSize = __webpack_require__(13);
	
	var _computedSize2 = _interopRequireDefault(_computedSize);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	/**
	 * Retrieve the computed style for an element, parsed as a float.
	 * @param {Element} element Element to get style for.
	 * @param {string} style Style property.
	 * @param {CSSStyleDeclaration} [styles] Optionally include clean styles to
	 *     use instead of asking for them again.
	 * @return {number} The parsed computed value or zero if that fails because IE
	 *     will return 'auto' when the element doesn't have margins instead of
	 *     the computed style.
	 */
	function getNumberStyle(element, style) {
	  var styles = arguments.length <= 2 || arguments[2] === undefined ? window.getComputedStyle(element, null) : arguments[2];
	
	  var value = (0, _getNumber2.default)(styles[style]);
	
	  // Support IE<=11 and W3C spec.
	  if (!_computedSize2.default && style === 'width') {
	    value += (0, _getNumber2.default)(styles.paddingLeft) + (0, _getNumber2.default)(styles.paddingRight) + (0, _getNumber2.default)(styles.borderLeftWidth) + (0, _getNumber2.default)(styles.borderRightWidth);
	  } else if (!_computedSize2.default && style === 'height') {
	    value += (0, _getNumber2.default)(styles.paddingTop) + (0, _getNumber2.default)(styles.paddingBottom) + (0, _getNumber2.default)(styles.borderTopWidth) + (0, _getNumber2.default)(styles.borderBottomWidth);
	  }
	
	  return value;
	}

/***/ },
/* 13 */
/***/ function(module, exports) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	
	var element = document.body || document.documentElement;
	var e = document.createElement('div');
	e.style.cssText = 'width:10px;padding:2px;box-sizing:border-box;';
	element.appendChild(e);
	
	var width = window.getComputedStyle(e, null).width;
	var ret = width === '10px';
	
	element.removeChild(e);
	
	exports.default = ret;

/***/ },
/* 14 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.default = sorter;
	
	var _xtend = __webpack_require__(5);
	
	var _xtend2 = _interopRequireDefault(_xtend);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	// http://stackoverflow.com/a/962890/373422
	function randomize(array) {
	  var tmp;
	  var current;
	  var top = array.length;
	
	  if (!top) {
	    return array;
	  }
	
	  while (--top) {
	    current = Math.floor(Math.random() * (top + 1));
	    tmp = array[current];
	    array[current] = array[top];
	    array[top] = tmp;
	  }
	
	  return array;
	}
	
	var defaults = {
	  // Use array.reverse() to reverse the results
	  reverse: false,
	
	  // Sorting function
	  by: null,
	
	  // If true, this will skip the sorting and return a randomized order in the array
	  randomize: false,
	
	  // Determines which property of each item in the array is passed to the
	  // sorting method.
	  key: 'element'
	};
	
	// You can return `undefined` from the `by` function to revert to DOM order.
	function sorter(arr, options) {
	  var opts = (0, _xtend2.default)(defaults, options);
	  var original = [].slice.call(arr);
	  var revert = false;
	
	  if (!arr.length) {
	    return [];
	  }
	
	  if (opts.randomize) {
	    return randomize(arr);
	  }
	
	  // Sort the elements by the opts.by function.
	  // If we don't have opts.by, default to DOM order
	  if (typeof opts.by === 'function') {
	    arr.sort(function (a, b) {
	
	      // Exit early if we already know we want to revert
	      if (revert) {
	        return 0;
	      }
	
	      var valA = opts.by(a[opts.key]);
	      var valB = opts.by(b[opts.key]);
	
	      // If both values are undefined, use the DOM order
	      if (valA === undefined && valB === undefined) {
	        revert = true;
	        return 0;
	      }
	
	      if (valA < valB || valA === 'sortFirst' || valB === 'sortLast') {
	        return -1;
	      }
	
	      if (valA > valB || valA === 'sortLast' || valB === 'sortFirst') {
	        return 1;
	      }
	
	      return 0;
	    });
	  }
	
	  // Revert to the original array if necessary
	  if (revert) {
	    return original;
	  }
	
	  if (opts.reverse) {
	    arr.reverse();
	  }
	
	  return arr;
	}

/***/ },
/* 15 */
/***/ function(module, exports) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.onTransitionEnd = onTransitionEnd;
	exports.cancelTransitionEnd = cancelTransitionEnd;
	var transitions = {};
	var eventName = 'transitionend';
	var count = 0;
	
	function uniqueId() {
	  return eventName + count++;
	}
	
	function onTransitionEnd(element, callback) {
	  var id = uniqueId();
	  var listener = function listener(evt) {
	    if (evt.currentTarget === evt.target) {
	      cancelTransitionEnd(id);
	      callback(evt);
	    }
	  };
	
	  element.addEventListener(eventName, listener);
	
	  transitions[id] = { element: element, listener: listener };
	
	  return id;
	}
	
	function cancelTransitionEnd(id) {
	  if (transitions[id]) {
	    transitions[id].element.removeEventListener(eventName, transitions[id].listener);
	    transitions[id] = null;
	    return true;
	  }
	
	  return false;
	}

/***/ },
/* 16 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.getItemPosition = getItemPosition;
	exports.getColumnSpan = getColumnSpan;
	exports.getAvailablePositions = getAvailablePositions;
	exports.getShortColumn = getShortColumn;
	
	var _point = __webpack_require__(8);
	
	var _point2 = _interopRequireDefault(_point);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function arrayMax(array) {
	  return Math.max.apply(Math, array);
	}
	
	function arrayMin(array) {
	  return Math.min.apply(Math, array);
	}
	
	/**
	 * Determine the location of the next item, based on its size.
	 * @param {Object} itemSize Object with width and height.
	 * @param {Array.<number>} positions Positions of the other current items.
	 * @param {number} gridSize The column width or row height.
	 * @param {number} total The total number of columns or rows.
	 * @param {number} threshold Buffer value for the column to fit.
	 * @param {number} buffer Vertical buffer for the height of items.
	 * @return {Point}
	 */
	function getItemPosition(_ref) {
	  var itemSize = _ref.itemSize;
	  var positions = _ref.positions;
	  var gridSize = _ref.gridSize;
	  var total = _ref.total;
	  var threshold = _ref.threshold;
	  var buffer = _ref.buffer;
	
	  var span = getColumnSpan(itemSize.width, gridSize, total, threshold);
	  var setY = getAvailablePositions(positions, span, total);
	  var shortColumnIndex = getShortColumn(setY, buffer);
	
	  // Position the item
	  var point = new _point2.default(Math.round(gridSize * shortColumnIndex), Math.round(setY[shortColumnIndex]));
	
	  // Update the columns array with the new values for each column.
	  // e.g. before the update the columns could be [250, 0, 0, 0] for an item
	  // which spans 2 columns. After it would be [250, itemHeight, itemHeight, 0].
	  var setHeight = setY[shortColumnIndex] + itemSize.height;
	  for (var i = 0; i < span; i++) {
	    positions[shortColumnIndex + i] = setHeight;
	  }
	
	  return point;
	}
	
	/**
	 * Determine the number of columns an items spans.
	 * @param {number} itemWidth Width of the item.
	 * @param {number} columnWidth Width of the column (includes gutter).
	 * @param {number} columns Total number of columns
	 * @param {number} threshold A buffer value for the size of the column to fit.
	 * @return {number}
	 */
	function getColumnSpan(itemWidth, columnWidth, columns, threshold) {
	  var columnSpan = itemWidth / columnWidth;
	
	  // If the difference between the rounded column span number and the
	  // calculated column span number is really small, round the number to
	  // make it fit.
	  if (Math.abs(Math.round(columnSpan) - columnSpan) < threshold) {
	    // e.g. columnSpan = 4.0089945390298745
	    columnSpan = Math.round(columnSpan);
	  }
	
	  // Ensure the column span is not more than the amount of columns in the whole layout.
	  return Math.min(Math.ceil(columnSpan), columns);
	}
	
	/**
	 * Retrieves the column set to use for placement.
	 * @param {number} columnSpan The number of columns this current item spans.
	 * @param {number} columns The total columns in the grid.
	 * @return {Array.<number>} An array of numbers represeting the column set.
	 */
	function getAvailablePositions(positions, columnSpan, columns) {
	  // The item spans only one column.
	  if (columnSpan === 1) {
	    return positions;
	  }
	
	  // The item spans more than one column, figure out how many different
	  // places it could fit horizontally.
	  // The group count is the number of places within the positions this block
	  // could fit, ignoring the current positions of items.
	  // Imagine a 2 column brick as the second item in a 4 column grid with
	  // 10px height each. Find the places it would fit:
	  // [20, 10, 10, 0]
	  //  |   |   |
	  //  *   *   *
	  //
	  // Then take the places which fit and get the bigger of the two:
	  // max([20, 10]), max([10, 10]), max([10, 0]) = [20, 10, 0]
	  //
	  // Next, find the first smallest number (the short column).
	  // [20, 10, 0]
	  //          |
	  //          *
	  //
	  // And that's where it should be placed!
	  //
	  // Another example where the second column's item extends past the first:
	  // [10, 20, 10, 0] => [20, 20, 10] => 10
	  var available = [];
	
	  // For how many possible positions for this item there are.
	  for (var i = 0; i <= columns - columnSpan; i++) {
	    // Find the bigger value for each place it could fit.
	    available.push(arrayMax(positions.slice(i, i + columnSpan)));
	  }
	
	  return available;
	}
	
	/**
	 * Find index of short column, the first from the left where this item will go.
	 *
	 * @param {Array.<number>} positions The array to search for the smallest number.
	 * @param {number} buffer Optional buffer which is very useful when the height
	 *     is a percentage of the width.
	 * @return {number} Index of the short column.
	 */
	function getShortColumn(positions, buffer) {
	  var minPosition = arrayMin(positions);
	  for (var i = 0, len = positions.length; i < len; i++) {
	    if (positions[i] >= minPosition - buffer && positions[i] <= minPosition + buffer) {
	      return i;
	    }
	  }
	
	  return 0;
	}

/***/ }
/******/ ])
});
;
//# sourceMappingURL=shuffle.js.map
/*!
 * imagesLoaded v4.1.1
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

( function( window, factory ) { 'use strict';
  // universal module definition

  /*global define: false, module: false, require: false */

  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( [
      'ev-emitter/ev-emitter'
    ], function( EvEmitter ) {
      return factory( window, EvEmitter );
    });
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      window,
      require('ev-emitter')
    );
  } else {
    // browser global
    window.imagesLoaded = factory(
      window,
      window.EvEmitter
    );
  }

})( window,

// --------------------------  factory -------------------------- //

function factory( window, EvEmitter ) {

'use strict';

var $ = window.jQuery;
var console = window.console;

// -------------------------- helpers -------------------------- //

// extend objects
function extend( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
}

// turn element or nodeList into an array
function makeArray( obj ) {
  var ary = [];
  if ( Array.isArray( obj ) ) {
    // use object if already an array
    ary = obj;
  } else if ( typeof obj.length == 'number' ) {
    // convert nodeList to array
    for ( var i=0; i < obj.length; i++ ) {
      ary.push( obj[i] );
    }
  } else {
    // array of single index
    ary.push( obj );
  }
  return ary;
}

// -------------------------- imagesLoaded -------------------------- //

/**
 * @param {Array, Element, NodeList, String} elem
 * @param {Object or Function} options - if function, use as callback
 * @param {Function} onAlways - callback function
 */
function ImagesLoaded( elem, options, onAlways ) {
  // coerce ImagesLoaded() without new, to be new ImagesLoaded()
  if ( !( this instanceof ImagesLoaded ) ) {
    return new ImagesLoaded( elem, options, onAlways );
  }
  // use elem as selector string
  if ( typeof elem == 'string' ) {
    elem = document.querySelectorAll( elem );
  }

  this.elements = makeArray( elem );
  this.options = extend( {}, this.options );

  if ( typeof options == 'function' ) {
    onAlways = options;
  } else {
    extend( this.options, options );
  }

  if ( onAlways ) {
    this.on( 'always', onAlways );
  }

  this.getImages();

  if ( $ ) {
    // add jQuery Deferred object
    this.jqDeferred = new $.Deferred();
  }

  // HACK check async to allow time to bind listeners
  setTimeout( function() {
    this.check();
  }.bind( this ));
}

ImagesLoaded.prototype = Object.create( EvEmitter.prototype );

ImagesLoaded.prototype.options = {};

ImagesLoaded.prototype.getImages = function() {
  this.images = [];

  // filter & find items if we have an item selector
  this.elements.forEach( this.addElementImages, this );
};

/**
 * @param {Node} element
 */
ImagesLoaded.prototype.addElementImages = function( elem ) {
  // filter siblings
  if ( elem.nodeName == 'IMG' ) {
    this.addImage( elem );
  }
  // get background image on element
  if ( this.options.background === true ) {
    this.addElementBackgroundImages( elem );
  }

  // find children
  // no non-element nodes, #143
  var nodeType = elem.nodeType;
  if ( !nodeType || !elementNodeTypes[ nodeType ] ) {
    return;
  }
  var childImgs = elem.querySelectorAll('img');
  // concat childElems to filterFound array
  for ( var i=0; i < childImgs.length; i++ ) {
    var img = childImgs[i];
    this.addImage( img );
  }

  // get child background images
  if ( typeof this.options.background == 'string' ) {
    var children = elem.querySelectorAll( this.options.background );
    for ( i=0; i < children.length; i++ ) {
      var child = children[i];
      this.addElementBackgroundImages( child );
    }
  }
};

var elementNodeTypes = {
  1: true,
  9: true,
  11: true
};

ImagesLoaded.prototype.addElementBackgroundImages = function( elem ) {
  var style = getComputedStyle( elem );
  if ( !style ) {
    // Firefox returns null if in a hidden iframe https://bugzil.la/548397
    return;
  }
  // get url inside url("...")
  var reURL = /url\((['"])?(.*?)\1\)/gi;
  var matches = reURL.exec( style.backgroundImage );
  while ( matches !== null ) {
    var url = matches && matches[2];
    if ( url ) {
      this.addBackground( url, elem );
    }
    matches = reURL.exec( style.backgroundImage );
  }
};

/**
 * @param {Image} img
 */
ImagesLoaded.prototype.addImage = function( img ) {
  var loadingImage = new LoadingImage( img );
  this.images.push( loadingImage );
};

ImagesLoaded.prototype.addBackground = function( url, elem ) {
  var background = new Background( url, elem );
  this.images.push( background );
};

ImagesLoaded.prototype.check = function() {
  var _this = this;
  this.progressedCount = 0;
  this.hasAnyBroken = false;
  // complete if no images
  if ( !this.images.length ) {
    this.complete();
    return;
  }

  function onProgress( image, elem, message ) {
    // HACK - Chrome triggers event before object properties have changed. #83
    setTimeout( function() {
      _this.progress( image, elem, message );
    });
  }

  this.images.forEach( function( loadingImage ) {
    loadingImage.once( 'progress', onProgress );
    loadingImage.check();
  });
};

ImagesLoaded.prototype.progress = function( image, elem, message ) {
  this.progressedCount++;
  this.hasAnyBroken = this.hasAnyBroken || !image.isLoaded;
  // progress event
  this.emitEvent( 'progress', [ this, image, elem ] );
  if ( this.jqDeferred && this.jqDeferred.notify ) {
    this.jqDeferred.notify( this, image );
  }
  // check if completed
  if ( this.progressedCount == this.images.length ) {
    this.complete();
  }

  if ( this.options.debug && console ) {
    console.log( 'progress: ' + message, image, elem );
  }
};

ImagesLoaded.prototype.complete = function() {
  var eventName = this.hasAnyBroken ? 'fail' : 'done';
  this.isComplete = true;
  this.emitEvent( eventName, [ this ] );
  this.emitEvent( 'always', [ this ] );
  if ( this.jqDeferred ) {
    var jqMethod = this.hasAnyBroken ? 'reject' : 'resolve';
    this.jqDeferred[ jqMethod ]( this );
  }
};

// --------------------------  -------------------------- //

function LoadingImage( img ) {
  this.img = img;
}

LoadingImage.prototype = Object.create( EvEmitter.prototype );

LoadingImage.prototype.check = function() {
  // If complete is true and browser supports natural sizes,
  // try to check for image status manually.
  var isComplete = this.getIsImageComplete();
  if ( isComplete ) {
    // report based on naturalWidth
    this.confirm( this.img.naturalWidth !== 0, 'naturalWidth' );
    return;
  }

  // If none of the checks above matched, simulate loading on detached element.
  this.proxyImage = new Image();
  this.proxyImage.addEventListener( 'load', this );
  this.proxyImage.addEventListener( 'error', this );
  // bind to image as well for Firefox. #191
  this.img.addEventListener( 'load', this );
  this.img.addEventListener( 'error', this );
  this.proxyImage.src = this.img.src;
};

LoadingImage.prototype.getIsImageComplete = function() {
  return this.img.complete && this.img.naturalWidth !== undefined;
};

LoadingImage.prototype.confirm = function( isLoaded, message ) {
  this.isLoaded = isLoaded;
  this.emitEvent( 'progress', [ this, this.img, message ] );
};

// ----- events ----- //

// trigger specified handler for event type
LoadingImage.prototype.handleEvent = function( event ) {
  var method = 'on' + event.type;
  if ( this[ method ] ) {
    this[ method ]( event );
  }
};

LoadingImage.prototype.onload = function() {
  this.confirm( true, 'onload' );
  this.unbindEvents();
};

LoadingImage.prototype.onerror = function() {
  this.confirm( false, 'onerror' );
  this.unbindEvents();
};

LoadingImage.prototype.unbindEvents = function() {
  this.proxyImage.removeEventListener( 'load', this );
  this.proxyImage.removeEventListener( 'error', this );
  this.img.removeEventListener( 'load', this );
  this.img.removeEventListener( 'error', this );
};

// -------------------------- Background -------------------------- //

function Background( url, element ) {
  this.url = url;
  this.element = element;
  this.img = new Image();
}

// inherit LoadingImage prototype
Background.prototype = Object.create( LoadingImage.prototype );

Background.prototype.check = function() {
  this.img.addEventListener( 'load', this );
  this.img.addEventListener( 'error', this );
  this.img.src = this.url;
  // check if image is already complete
  var isComplete = this.getIsImageComplete();
  if ( isComplete ) {
    this.confirm( this.img.naturalWidth !== 0, 'naturalWidth' );
    this.unbindEvents();
  }
};

Background.prototype.unbindEvents = function() {
  this.img.removeEventListener( 'load', this );
  this.img.removeEventListener( 'error', this );
};

Background.prototype.confirm = function( isLoaded, message ) {
  this.isLoaded = isLoaded;
  this.emitEvent( 'progress', [ this, this.element, message ] );
};

// -------------------------- jQuery -------------------------- //

ImagesLoaded.makeJQueryPlugin = function( jQuery ) {
  jQuery = jQuery || window.jQuery;
  if ( !jQuery ) {
    return;
  }
  // set local variable
  $ = jQuery;
  // $().imagesLoaded()
  $.fn.imagesLoaded = function( options, callback ) {
    var instance = new ImagesLoaded( this, options, callback );
    return instance.jqDeferred.promise( $(this) );
  };
};
// try making plugin
ImagesLoaded.makeJQueryPlugin();

// --------------------------  -------------------------- //

return ImagesLoaded;

});
