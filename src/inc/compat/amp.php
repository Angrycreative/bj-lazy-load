<?php

function bjll_compat_amp() {
	if ( ( function_exists( 'amp_activate' ) && defined( 'AMP__VERSION' ) ) && ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ) {
		add_filter( 'bjll/enabled', '__return_false' );
	}
}

add_action( 'bjll/compat', 'bjll_compat_amp' );
