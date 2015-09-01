<?php

function bjll_compat_wpprint() {
	if ( 1 == intval( get_query_var( 'print' ) ) || 1 == intval( get_query_var( 'printpage' ) ) ) {
		add_filter( 'bjll/enabled', '__return_false' );
	}
}

add_action( 'bjll/compat', 'bjll_compat_wpprint' );
