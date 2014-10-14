<?php

class BJLL_Admin_Page extends scbAdminPage {

	function setup() {
		$this->args = array(
			'menu_title' => 'BJ Lazy Load',
			'page_title' => __( 'BJ Lazy Load Options', 'bj-lazy-load' ),
		);
	}
	
	function page_content() {
		
		
		$optionfields = array(
			array(
				'title' => __( 'Apply to content', 'bj-lazy-load' ),
				'type' => 'radio',
				'name' => 'filter_content',
				'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			),
			array(
				'title' => __( 'Apply to text widgets', 'bj-lazy-load' ),
				'type' => 'radio',
				'name' => 'filter_widget_text',
				'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			),
			array(
				'title' => __( 'Apply to post thumbnails', 'bj-lazy-load' ),
				'type' => 'radio',
				'name' => 'filter_post_thumbnails',
				'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			),
			array(
				'title' => __( 'Apply to gravatars', 'bj-lazy-load' ),
				'type' => 'radio',
				'name' => 'filter_gravatars',
				'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			),
			array(
				'title' => __( 'Lazy load images', 'bj-lazy-load' ),
				'type' => 'radio',
				'name' => 'lazy_load_images',
				'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			),
			array(
				'title' => __( 'Lazy load iframes', 'bj-lazy-load' ),
				'type' => 'radio',
				'name' => 'lazy_load_iframes',
				'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			),
			array(
				'title' => __( 'Theme loader function', 'bj-lazy-load' ),
				'type' => 'select',
				'name' => 'theme_loader_function',
				'value' => array( 'wp_footer', 'wp_head' ),
			),
			array(
				'title' => __( 'Placeholder Image URL', 'bj-lazy-load' ),
				'type' => 'text',
				'name' => 'placeholder_url',
				'desc' => sprintf( '<p class="description">%s</p>', __( 'Leave blank for default', 'bj-lazy-load' ) ),
			),
			array(
				'title' => __( 'Skip images with classes', 'bj-lazy-load' ),
				'type' => 'text',
				'name' => 'skip_classes',
				'desc' => sprintf( '<p class="description">%s</p>', __( 'Comma separated. Example: "no-lazy, lazy-ignore, image-235"', 'bj-lazy-load' ) ),
			),
			array(
				'title' => __( 'Threshold', 'bj-lazy-load' ),
				'type' => 'text',
				'name' => 'threshold',
				'desc' => sprintf( '<p class="description">%s</p>', __( 'How close to the viewport the element should be when we load it. In pixels. Example: 200', 'bj-lazy-load' ) ),
			)
		);

		$optionfields[] = array(
			'title' => __( 'Infinite scroll', 'bj-lazy-load' ),
			'type' => 'radio',
			'name' => 'infinite_scroll',
			'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			'desc' => sprintf( '<p class="description">%s</p>', __( 'Enable if your theme uses infinite scroll.', 'bj-lazy-load' ) ),
		);

		$optionfields[] = array(
			'title' => __( 'Load hiDPI (retina) images', 'bj-lazy-load' ),
			'type' => 'radio',
			'name' => 'load_hidpi',
			'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			'desc' => sprintf( '<p class="description">%s</p>', __( 'Will load hiDPI version of the images if the current browser/screen supports them. (Experimental feature. Do NOT enable if you are using a CDN)', 'bj-lazy-load' ) ),
		);

		$optionfields[] = array(
			'title' => __( 'Load responsive images', 'bj-lazy-load' ),
			'type' => 'radio',
			'name' => 'load_responsive',
			'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
			'desc' => sprintf( '<p class="description">%s</p>', __( 'Will load scaled down version of the images if the image is scaled down in the theme. (Experimental feature. Do NOT enable if you are using a CDN)', 'bj-lazy-load' ) ),
		);


		if ( BJLL::has_wptouch() ) {
			$optionfields[] = array(
				'title' => __( 'Disable on WPTouch', 'bj-lazy-load' ),
				'type' => 'radio',
				'name' => 'disable_on_wptouch',
				'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
				'desc' => sprintf( '<p class="description">%s</p>', __( 'Disables BJ Lazy Load when the WPTouch mobile theme is used', 'bj-lazy-load' ) ),
			);
		}

		if ( BJLL::has_mobilepress() ) {
			$optionfields[] = array(
				'title' => __( 'Disable on MobilePress', 'bj-lazy-load' ),
				'type' => 'radio',
				'name' => 'disable_on_mobilepress',
				'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
				'desc' => sprintf( '<p class="description">%s</p>', __( 'Disables BJ Lazy Load when the MobilePress mobile theme is used', 'bj-lazy-load' ) ),
			);
		}

		echo $this->form_table( $optionfields );
		
	}

}
