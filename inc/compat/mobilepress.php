<?php

function bjll_compat_mobilepress() {
	if ( function_exists( 'mopr_get_option' ) && WP_CONTENT_DIR . mopr_get_option( 'mobile_theme_root', 1 ) == get_theme_root() ) {
		add_filter( 'bjll/enabled', '__return_false' );
	}
}

add_action( 'bjll/compat', 'bjll_compat_mobilepress' );
