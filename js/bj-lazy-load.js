"use strict";

var BJLL_options = BJLL_options || {};

var BJLL = {

	_ticking: false,

	check: function () {

		if ( BJLL._ticking ) {
			return;
		}

		BJLL._ticking = true;

		if ( 'undefined' == typeof ( BJLL.threshold ) ) {
			if ( 'undefined' != typeof ( BJLL_options.threshold ) ) {
				BJLL.threshold = parseInt( BJLL_options.threshold );
			} else {
				BJLL.threshold = 200;
			}
		}

		var winH = document.documentElement.clientHeight || body.clientHeight;

		var updated = false;

		var els = document.getElementsByClassName('lazy-hidden');
		[].forEach.call( els, function( el, index, array ) {

			var elemRect = el.getBoundingClientRect();

			if ( winH - elemRect.top + BJLL.threshold > 0 ) {
				BJLL.show( el );
				updated = true;
			}

		} );

		BJLL._ticking = false;
		if ( updated ) {
			BJLL.check();
		}
	},

	show: function( el ) {
		el.className = el.className.replace( /(?:^|\s)lazy-hidden(?!\S)/g , '' );
		el.addEventListener( 'load', function() {
			el.className += " lazy-loaded";
			BJLL.customEvent( el, 'lazyloaded' );
		}, false );

		var type = el.getAttribute('data-lazy-type');

		if ( 'image' == type ) {
			if ( null != el.getAttribute('data-lazy-srcset') ) {
				el.setAttribute( 'srcset', el.getAttribute('data-lazy-srcset') );
			}
			if ( null != el.getAttribute('data-lazy-sizes') ) {
				el.setAttribute( 'sizes', el.getAttribute('data-lazy-sizes') );
			}
			el.setAttribute( 'src', el.getAttribute('data-lazy-src') );
		} else if ( 'iframe' == type ) {
			var s = el.getAttribute('data-lazy-src'),
				div = document.createElement('div');
			
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
	}

}

window.addEventListener( 'load', BJLL.check, false );
window.addEventListener( 'scroll', BJLL.check, false );
window.addEventListener( 'resize', BJLL.check, false );
document.getElementsByTagName( 'body' ).item( 0 ).addEventListener( 'post-load', BJLL.check, false );


