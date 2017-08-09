<?php
if ( ! class_exists( 'BJLL_AdminPage' ) ) {
	class BJLL_AdminPage extends scbAdminPage {
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
				),
				array(
					'title' => __( 'Use low-res preview image', 'bj-lazy-load' ),
					'type' => 'radio',
					'name' => 'preview',
					'value' => array( 'yes' => __( 'Yes', 'bj-lazy-load' ), 'no' => __( 'No', 'bj-lazy-load' ) ),
					'desc' => sprintf( '<p class="description">%s</p>', __( 'Shows a low resolution preview image before the real image loads. Images uploaded before this setting is activated need that have their image sizes regenerated for the feature to work. This can be done using a plugin such as <a href="https://sv.wordpress.org/plugins/regenerate-thumbnails/">Regenerate Thumbnails</a>.', 'bj-lazy-load' ) ),
				)
			);
			echo $this->form_table( $optionfields );
			
		}
	}
}