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
			'placeholder_url'         => '',
			'skip_classes'            => '',
			'threshold'               => '200',
			'preview'                 => 'no',
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