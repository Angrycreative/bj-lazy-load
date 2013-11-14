<?php

function bjll_load_plugin_textdomain() {
	load_plugin_textdomain( 'bj-lazy-load', false, 'bj-lazy-load/lang/' );
}
add_action( 'plugins_loaded', 'bjll_load_plugin_textdomain' );