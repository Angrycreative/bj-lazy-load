<?php

function bjll_compat_operamini() {
	if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) ) {
		add_filter( 'bjll/enabled', '__return_false' );
	}
}

add_action( 'bjll/compat', 'bjll_compat_operamini' );
