'use strict';

// eslint-disable-next-line camelcase
var BJLL_options = BJLL_options || {};

var BJLL = ( function() {
	var BJLL = {

		_lastCheckTs: 0,
		_checkDebounceTimeoutRunning: false,

		init: function() {
			BJLL.threshold = BJLL.getOptionIntValue( 'threshold', 200 );
			BJLL.recheckDelay = BJLL.getOptionIntValue( 'recheck_delay', 250 );
			BJLL.debounce = BJLL.getOptionIntValue( 'debounce', 50 );
			BJLL.checkRecurring();
			return BJLL;
		},

		check: function( fromDebounceTimeout ) {
			var tstamp, winH, updated, els;
			if ( true === fromDebounceTimeout ) {
				BJLL._checkDebounceTimeoutRunning = false;
			}
			tstamp = performance.now();
			if ( tstamp < BJLL._lastCheckTs + BJLL.debounce ) {
				if ( ! BJLL._checkDebounceTimeoutRunning ) {
					BJLL._checkDebounceTimeoutRunning = true;
					setTimeout( function() {
						BJLL.check( true );
					}, BJLL.debounce );
				}
				return;
			}
			BJLL._lastCheckTs = tstamp;

			winH = document.documentElement.clientHeight || body.clientHeight;
			updated = false;
			els = document.getElementsByClassName( 'lazy-hidden' );

			[].forEach.call( els, function( el, index, array ) {
				var elemRect = el.getBoundingClientRect();

				// do not lazy-load images that are hidden with display:none or have a width/height of 0
				if ( ! elemRect.width || ! elemRect.height ) {
					return;
				}

				if ( 0 < winH - elemRect.top + BJLL.threshold ) {
					BJLL.show( el );
					updated = true;
				}
			});

			if ( updated ) {
				BJLL.check();
			}
		},

		checkRecurring: function() {
			BJLL.check();
			setTimeout( BJLL.checkRecurring, BJLL.recheckDelay );
		},

		show: function( el ) {
			var type, s, div, iframe;
			el.className = el.className.replace( /(?:^|\s)lazy-hidden(?!\S)/g, '' );
			type = el.getAttribute( 'data-lazy-type' );

			if(type=='background'){
				var style = 'background-image: url(' + el.getAttribute('data-lazy-src') + ');';
				if(null != el.getAttribute('data-lazy-style')){
					style += el.getAttribute('data-lazy-style');
				}
				el.setAttribute( 'style', style);
				el.className += " lazy-loaded";
				BJLL.customEvent( el, 'lazyloaded' );
			}else{
				el.addEventListener( 'load', function() {
					el.className += ' lazy-loaded';
					BJLL.customEvent( el, 'lazyloaded' );
				}, false );

				if ( 'image' == type ) {
					if ( null != el.getAttribute( 'data-lazy-srcset' ) ) {
						el.setAttribute( 'srcset', el.getAttribute( 'data-lazy-srcset' ) );
					}
					if ( null != el.getAttribute( 'data-lazy-sizes' ) ) {
						el.setAttribute( 'sizes', el.getAttribute( 'data-lazy-sizes' ) );
					}
					el.setAttribute( 'src', el.getAttribute( 'data-lazy-src' ) );
				} else if ( 'iframe' == type ) {
					s = el.getAttribute( 'data-lazy-src' );
					div = document.createElement( 'div' );

					div.innerHTML = s;
					iframe = div.firstChild;
					el.parentNode.replaceChild( iframe, el );
				}
			}
		},

		customEvent: function( el, eventName ) {
			var event;

			if ( document.createEvent ) {
				event = document.createEvent( 'HTMLEvents' );
				event.initEvent( eventName, true, true );
			} else {
				event = document.createEventObject();
				event.eventType = eventName;
			}

			event.eventName = eventName;

			if ( document.createEvent ) {
				el.dispatchEvent( event );
			} else {
				el.fireEvent( 'on' + event.eventType, event );
			}
		},

		getOptionIntValue: function( name, defaultValue ) {
			// eslint-disable-next-line camelcase
			if ( 'undefined' !== typeof ( BJLL_options[name]) ) {
				// eslint-disable-next-line camelcase
				return parseInt( BJLL_options[name]);
			}
			return defaultValue;
		}
	};
	return BJLL.init();
}() );

window.addEventListener( 'load', BJLL.check, false );
window.addEventListener( 'scroll', BJLL.check, false );
window.addEventListener( 'resize', BJLL.check, false );
document.getElementsByTagName( 'body' ).item( 0 ).addEventListener( 'post-load', BJLL.check, false );
