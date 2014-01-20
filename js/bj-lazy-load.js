
var BJLL = BJLL || {};

(function($) {

	function bj_lazy_load_init() {
		
		var threshold = 200;

		if ( 'undefined' != typeof ( BJLL.threshold ) ) {
			threshold = parseInt( BJLL.threshold );
		}

		$('.lazy-hidden').not('.data-lazy-ready').one( 'scrollin.bj_lazy_load', { distance: threshold }, function() {

			var $el = $( this ),
				data_lazy_type = $el.attr( 'data-lazy-type' );

			if ( data_lazy_type == 'image' ) {

				var imgurl = $el.attr( 'data-lazy-src' );

				if ( BJLL.load_responsive == 'yes' || BJLL.load_hidpi == 'yes' ) {
					var l = document.createElement( 'a' );
					l.href = $el.attr( 'data-lazy-src' );

					if ( ! l.hostname.length || l.hostname == window.location.hostname ) {
						var loadimgwidth = parseInt( $el.css( 'width' ) );
						if ( window.devicePixelRatio > 1 && BJLL.load_hidpi == 'yes' ) {
							loadimgwidth = Math.ceil( window.devicePixelRatio * loadimgwidth );
						}
						var srcimgurl = $el.attr( 'data-lazy-src' );
						if ( 'undefined' != typeof ( BJLL.site_url ) && 'undefined' != typeof ( BJLL.network_site_url ) ) {
							srcimgurl = srcimgurl.replace( BJLL.site_url, BJLL.network_site_url );
						}
						imgurl = BJLL.thumb_base + encodeURIComponent( srcimgurl ) + '&w=' + loadimgwidth;
					}

				}

				$el.hide()
					.attr( 'src', imgurl )
					.removeClass( 'lazy-hidden' )
					.fadeIn();
			} else if ( data_lazy_type == 'iframe' ) {
				$el.replaceWith(
					bj_lazy_load_base64_decode(
						$el.attr( 'data-lazy-src' )
					)
				);
			}
		}).addClass( 'data-lazy-ready' );
		
	}
	
	function bj_lazy_load_base64_decode (data) {
		// http://kevin.vanzonneveld.net
		// +   original by: Tyler Akins (http://rumkin.com)
		// +   improved by: Thunder.m
		// +      input by: Aman Gupta
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   bugfixed by: Onno Marsman
		// +   bugfixed by: Pellentesque Malesuada
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +      input by: Brett Zamir (http://brett-zamir.me)
		// +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
		// *     returns 1: 'Kevin van Zonneveld'
		// mozilla has this native
		// - but breaks in 2.0.0.12!
		//if (typeof this.window['atob'] == 'function') {
		//    return atob(data);
		//}
		var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
			ac = 0,
			dec = "",
			tmp_arr = [];

		if (!data) {
			return data;
		}

		data += '';

		do { // unpack four hexets into three octets using index points in b64
			h1 = b64.indexOf(data.charAt(i++));
			h2 = b64.indexOf(data.charAt(i++));
			h3 = b64.indexOf(data.charAt(i++));
			h4 = b64.indexOf(data.charAt(i++));

			bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

			o1 = bits >> 16 & 0xff;
			o2 = bits >> 8 & 0xff;
			o3 = bits & 0xff;

			if (h3 == 64) {
				tmp_arr[ac++] = String.fromCharCode(o1);
			} else if (h4 == 64) {
				tmp_arr[ac++] = String.fromCharCode(o1, o2);
			} else {
				tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
			}
		} while (i < data.length);

		dec = tmp_arr.join('');

		return dec;
	}
	
	$( document ).on( 'ready', bj_lazy_load_init );
	if ( 'yes' == BJLL.infinite_scroll ) {
		$( window ).on( 'scroll', bj_lazy_load_init );
	}
	$( window ).on( 'resize', function() { $( document ).trigger( 'scroll' ); } );
	
})(jQuery);