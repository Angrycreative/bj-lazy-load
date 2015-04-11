<?php

include 'scb/load.php';

class BJLL_Options {

	protected $_options;

	function __construct() {
		scb_init( array( $this, 'options_init' ) );
	}

	public function options_init() {

		$this->_options = new scbOptions( 'bj_lazy_load_options', __FILE__, array(
			'filter_content'          => 'yes',
			'filter_widget_text'      => 'yes',
			'filter_post_thumbnails'  => 'yes',
			'filter_gravatars'        => 'yes',
			'lazy_load_images'        => 'yes',
			'lazy_load_iframes'       => 'yes',
			'theme_loader_function'   => 'wp_footer',
			'placeholder_url'         => '',
			'skip_classes'            => '',
			'load_hidpi'              => 'no',
			'load_responsive'         => 'no',
			'disable_on_wptouch'      => 'yes',
			'disable_on_mobilepress'  => 'yes',
			'infinite_scroll'         => 'no',
			'threshold'               => '200'
		) );

		if ( is_admin() ) {
			include 'class-bjll-adminpage.php';
			new BJLL_AdminPage( __FILE__, $this->_options );
		}
	}

	public function get( $option_key ) {
		return $this->_options->get( $option_key );
	}

}