<?php

class BJLL_Admin_Page extends scbAdminPage {

	function setup() {
		$this->args = array(
			'menu_title' => 'BJ Lazy Load',
			'page_title' => __( 'BJ Lazy Load Options', 'bj_lazy_load' ),
		);
	}
	
	function page_content() {
		
		
		$optionfields = array(
			array(
				'title' => __( 'Apply to content', 'bj_lazy_load' ),
				'type' => 'radio',
				'name' => 'filter_content',
				'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
			),
			array(
				'title' => __( 'Apply to post thumbnails', 'bj_lazy_load' ),
				'type' => 'radio',
				'name' => 'filter_post_thumbnails',
				'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
			),
			array(
				'title' => __( 'Apply to gravatars', 'bj_lazy_load' ),
				'type' => 'radio',
				'name' => 'filter_gravatars',
				'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
			),
			array(
				'title' => __( 'Lazy load images', 'bj_lazy_load' ),
				'type' => 'radio',
				'name' => 'lazy_load_images',
				'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
			),
			array(
				'title' => __( 'Lazy load iframes', 'bj_lazy_load' ),
				'type' => 'radio',
				'name' => 'lazy_load_iframes',
				'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
			),
			array(
				'title' => __( 'Theme loader function', 'bj_lazy_load' ),
				'type' => 'select',
				'name' => 'theme_loader_function',
				'value' => array( 'wp_footer', 'wp_head' ),
			),
			array(
				'title' => __( 'Placeholder Image URL', 'bj_lazy_load' ),
				'type' => 'text',
				'name' => 'placeholder_url',
				'desc' => sprintf( '<p class="description">%s</p>', __( 'Leave blank for default', 'bj_lazy_load' ) ),
			),
			array(
				'title' => __( 'Skip images with classes', 'bj_lazy_load' ),
				'type' => 'text',
				'name' => 'skip_classes',
				'desc' => sprintf( '<p class="description">%s</p>', __( 'Comma separated. Example: "no-lazy, lazy-ignore, image-235"', 'bj_lazy_load' ) ),
			)
		);

		$optionfields[] = array(
			'title' => __( 'Infinite scroll', 'bj_lazy_load' ),
			'type' => 'radio',
			'name' => 'infinite_scroll',
			'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
			'desc' => sprintf( '<p class="description">%s</p>', __( 'Enable if your theme uses infinite scroll.', 'bj_lazy_load' ) ),
		);

		$optionfields[] = array(
			'title' => __( 'Load hiDPI (retina) images', 'bj_lazy_load' ),
			'type' => 'radio',
			'name' => 'load_hidpi',
			'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
			'desc' => sprintf( '<p class="description">%s</p>', __( 'Will load hiDPI version of the images if the current browser/screen supports them. (Experimental feature. Do NOT enable if you are using a CDN)', 'bj_lazy_load' ) ),
		);

		$optionfields[] = array(
			'title' => __( 'Load responsive images', 'bj_lazy_load' ),
			'type' => 'radio',
			'name' => 'load_responsive',
			'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
			'desc' => sprintf( '<p class="description">%s</p>', __( 'Will load scaled down version of the images if the image is scaled down in the theme. (Experimental feature. Do NOT enable if you are using a CDN)', 'bj_lazy_load' ) ),
		);


		if ( BJLL::has_wptouch() ) {
			$optionfields[] = array(
				'title' => __( 'Disable on WPTouch', 'bj_lazy_load' ),
				'type' => 'radio',
				'name' => 'disable_on_wptouch',
				'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
				'desc' => sprintf( '<p class="description">%s</p>', __( 'Disables BJ Lazy Load when the WPTouch mobile theme is used', 'bj_lazy_load' ) ),
			);
		}

		if ( BJLL::has_mobilepress() ) {
			$optionfields[] = array(
				'title' => __( 'Disable on MobilePress', 'bj_lazy_load' ),
				'type' => 'radio',
				'name' => 'disable_on_mobilepress',
				'value' => array( 'yes' => __( 'Yes', 'bj_lazy_load' ), 'no' => __( 'No', 'bj_lazy_load' ) ),
				'desc' => sprintf( '<p class="description">%s</p>', __( 'Disables BJ Lazy Load when the MobilePress mobile theme is used', 'bj_lazy_load' ) ),
			);
		}

		echo $this->form_table( $optionfields );
		
	}

}
