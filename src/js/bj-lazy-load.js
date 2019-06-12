"use strict";

var BJLL_options = BJLL_options || {};

var BJLL = ( function() {
	var BJLL = {

		_lastCheckTs: 0,
		_checkDebounceTimeoutRunning: false,

		init: function() {
			BJLL.threshold = BJLL.getOptionIntValue( 'threshold', 200 );
			BJLL.recheck_delay = BJLL.getOptionIntValue( 'recheck_delay', 500 );
			BJLL.debounce = BJLL.getOptionIntValue( 'debounce', 50 );
			BJLL.checkRecurring();
			return BJLL;
		},

		check: function( fromDebounceTimeout ) {
			if ( fromDebounceTimeout === true ) {
				BJLL._checkDebounceTimeoutRunning = false;
			}
			var tstamp = performance.now();
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

			var winH = document.documentElement.clientHeight || body.clientHeight;

			var updated = false;

			var els = document.getElementsByClassName( 'lazy-hidden' );
			[].forEach.call( els, function( el, index, array ) {

				var elemRect = el.getBoundingClientRect();

				// do not lazy-load images that are hidden with display:none or have a width/height of 0
				if ( !elemRect.width || !elemRect.height ) return;

				if ( winH - elemRect.top + BJLL.threshold > 0 ) {
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
			setTimeout( BJLL.checkRecurring, BJLL.recheck_delay );
		},

		show: function( el ) {
			el.className = el.className.replace( /(?:^|\s)lazy-hidden(?!\S)/g, '' );
			el.addEventListener( 'load', function() {
				el.className += " lazy-loaded";
				BJLL.customEvent( el, 'lazyloaded' );
			}, false );

			var type = el.getAttribute( 'data-lazy-type' );

			if ( 'image' == type ) {
				if ( null != el.getAttribute( 'data-lazy-srcset' ) ) {
					el.setAttribute( 'srcset', el.getAttribute( 'data-lazy-srcset' ) );
				}
				if ( null != el.getAttribute( 'data-lazy-sizes' ) ) {
					el.setAttribute( 'sizes', el.getAttribute( 'data-lazy-sizes' ) );
				}
				el.setAttribute( 'src', el.getAttribute( 'data-lazy-src' ) );
			} else if ( 'iframe' == type ) {
				var s = el.getAttribute( 'data-lazy-src' ),
					div = document.createElement( 'div' );

				div.innerHTML = s;
				var iframe = div.firstChild;
				el.parentNode.replaceChild( iframe, el );

			}
		},

		customEvent: function( el, eventName ) {
			var event;

			if ( document.createEvent ) {
				event = document.createEvent( "HTMLEvents" );
				event.initEvent( eventName, true, true );
			} else {
				event = document.createEventObject();
				event.eventType = eventName;
			}

			event.eventName = eventName;

			if ( document.createEvent ) {
				el.dispatchEvent( event );
			} else {
				el.fireEvent( "on" + event.eventType, event );
			}
		},

		getOptionIntValue: function( name, defaultValue ) {
			if ( 'undefined' !== typeof ( BJLL_options[name]) ) {
				return parseInt( BJLL_options[name] );
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


