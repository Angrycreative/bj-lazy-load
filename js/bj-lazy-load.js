
var BJLL = {

	threshold: 200,

	check: function () {

		var winH = document.documentElement.clientHeight || body.clientHeight;

		var els = document.getElementsByClassName('lazy-hidden');
		[].forEach.call( els, function( el, index, array ) {

			var elemRect = el.getBoundingClientRect();

			if ( elemRect.top - winH - BJLL.threshold < 0 ) {
				BJLL.show( el );
			}

		} );
	},

	show: function( el ) {

		el.addEventListener( 'load', function() {
			BJLL.customEvent( el, 'lazyloaded' );
		}, false );

		el.setAttribute( 'src', el.getAttribute('data-lazy-src') );
		if ( el.getAttribute('data-srcset') != null ) {
			el.setAttribute( 'srcset', el.getAttribute('data-srcset') );
		}
		el.className = el.className.replace( /(?:^|\s)lazy-hidden(?!\S)/g , '' );

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

document.addEventListener( 'DOMContentLoaded', BJLL.check, false );
window.addEventListener( 'scroll', BJLL.check, false );
window.addEventListener( 'resize', BJLL.check, false );
document.getElementsByTagName( 'body' ).item( 0 ).addEventListener( 'post-load', BJLL.check, false )


