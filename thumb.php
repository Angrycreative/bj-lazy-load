<?php

/*

Yeah, we're using TimThumb!

Are you worried about the security because you heard that TimThumb is unsecure?
There was a major security flaw published in August 2011. Since then, TimThumb got rewritten from the ground up with version 2.0.

References:
Mark Maunder who rewrote TimThumb as WordThumb: http://markmaunder.com/2011/08/04/a-secure-rewrite-of-timthumb-php-as-wordthumb/
Matt Mullenweg, creator of WordPress, on the whole issue: http://ma.tt/2011/08/the-timthumb-saga/

*/

define( 'FILE_CACHE_DIRECTORY', dirname( __FILE__ ) . '/../cache' );
define( 'FILE_CACHE_MAX_FILE_AGE', 2592000 );
define( 'FILE_CACHE_SUFFIX', '.img' );
define( 'FILE_CACHE_PREFIX', 'thumb' );
define( 'MAX_FILE_SIZE', 20971520 );
define( 'BROWSER_CACHE_MAX_AGE', 31536000 );
define( 'MAX_WIDTH', 3000 );
define( 'MAX_HEIGHT', 3000 );

require( dirname( __FILE__ ) . '/inc/timthumb.php' );
