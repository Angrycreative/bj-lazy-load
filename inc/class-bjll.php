<?php
/*
License: GPL2

	Copyright 2011–2015  Bjørn Johansen  (email : post@bjornjohansen.no)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/**
 * The class that handles rewriting of content so we can lazy load it
 */
class BJLL {

	protected static $_options;


	function __construct( $options = null ) {

		if ( is_a( $options, 'BJLL_Options' ) ) {
			self::$_options = $options;
		}

		add_action( 'wp', array( $this, 'init' ), 99 ); // run this as late as possible

	}

	/**
	 * Initialize the setup
	 */
	public function init() {

		/* We do not touch the feeds */
		if ( is_feed() ) {
			return;
		}

		self::_bjll_compat();
		do_action( 'bjll/compat' );

		/**
		 * Filter to let plugins decide whether the plugin should run for this request or not
		 *
		 * Returning false will effectively short-circuit the plugin
		 *
		 * @param bool $enabled Whether the plugin should run for this request
		 */
		$enabled = apply_filters( 'bjll/enabled', true );

		if ( $enabled ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			$this->_setup_filtering();
		}
	}


	/**
	 * Load compat script
	 */
	protected function _bjll_compat() {

		$dirname = trailingslashit( dirname( __FILE__ ) ) . 'compat';
		$d = dir( $dirname );
		if ( $d ) {
			while ( $entry = $d->read() ) {
				if ( '.' != $entry[0] && '.php' == substr( $entry, -4) ) {
					include trailingslashit( $dirname ) . $entry;
				}
			}
		}
	}

	/**
	 * Enqueue styles
	 */
	public function enqueue_styles() {

	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		//$jsver = filemtime( dirname( dirname( __FILE__ ) ) . '/js/bj-lazy-load.js' );
		//wp_enqueue_script( 'BJLL', plugins_url( 'js/bj-lazy-load.js', dirname( __FILE__ ) ), null, $jsver, true );
		//$jsver = filemtime( dirname( dirname( __FILE__ ) ) . '/js/bj-lazy-load.v1.min.js' );
		$jsver = 2;
		wp_enqueue_script( 'BJLL', plugins_url( 'js/bj-lazy-load.min.js', dirname( __FILE__ ) ), null, $jsver, true );

		$bjll_options = array();
		$threshold = intval( self::_get_option('threshold') );
		if ( 200 != $threshold ) {
			$bjll_options['threshold'] = $threshold;
		}
		if ( count( $bjll_options ) ) {
			wp_localize_script( 'BJLL', 'BJLL_options', $bjll_options );
		}
	}

	/**
	 * Set up filtering for certain content
	 */
	protected function _setup_filtering() {

		if ( ! is_admin() ) {

			if ( 'yes' == self::_get_option('lazy_load_images') ) {
				add_filter( 'bjll/filter', array( __CLASS__, 'filter_images' ) );
			}

			if ( 'yes' == self::_get_option('lazy_load_iframes') ) {
				add_filter( 'bjll/filter', array( __CLASS__, 'filter_iframes' ) );
			}

			if ( 'yes' == self::_get_option( 'filter_content' ) ) {
				add_filter( 'the_content', array( __CLASS__, 'filter' ), 200 );
			}

			if ( 'yes' == self::_get_option( 'filter_widget_text' ) ) {
				add_filter( 'widget_text', array( __CLASS__, 'filter' ), 200 );
			}

			if ( 'yes' == self::_get_option( 'filter_post_thumbnails' ) ) {
				add_filter( 'post_thumbnail_html', array( __CLASS__, 'filter' ), 200 );
			}

			if ( 'yes' == self::_get_option( 'filter_gravatars' ) ) {
				add_filter( 'get_avatar', array( __CLASS__, 'filter' ), 200 );
			}

			add_filter( 'bj_lazy_load_html', array( __CLASS__, 'filter' ) );
		}

	}

	/**
	 * Filter HTML content. Replace supported content with placeholders.
	 *
	 * @param string $content The HTML string to filter
	 * @return string The filtered HTML string
	 */
	public static function filter( $content ) {

		// Last chance to bail out before running the filter
		$run_filter = apply_filters( 'bj_lazy_load_run_filter', true );
		if ( ! $run_filter ) {
			return $content;
		}

		/**
		 * Filter the content
		 *
		 * @param string $content The HTML string to filter
		 */
		$content = apply_filters( 'bjll/filter', $content );

		return $content;
	}


	/**
	 * Replace images with placeholders in the content
	 *
	 * @param string $content The HTML to do the filtering on
	 * @return string The HTML with the images replaced
	 */
	public static function filter_images( $content ) {

		$placeholder_url = self::_get_option( 'placeholder_url' );
		$placeholder_url = apply_filters( 'bjll/placeholder_url', $placeholder_url, 'image' );
		if ( ! strlen( $placeholder_url ) ) {
			$placeholder_url = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
		}

		$match_content = self::_get_content_haystack( $content );

		$matches = array();
		preg_match_all( '/<img[\s\r\n]+.*?>/is', $match_content, $matches );
		
		$search = array();
		$replace = array();

		foreach ( $matches[0] as $imgHTML ) {
			
			// don't do the replacement if the image is a data-uri
			if ( ! preg_match( "/src=['\"]data:image/is", $imgHTML ) ) {
				
				$placeholder_url_used = $placeholder_url;
				// use low res preview image as placeholder if applicable
				if ( 'yes' == self::_get_option('preview') ) {
					if( preg_match( '/class=["\'].*?wp-image-([0-9]*)/is', $imgHTML, $id_matches ) ) {
						$img_id = intval($id_matches[1]);
						$tiny_img_data  = wp_get_attachment_image_src( $img_id, 'tiny-lazy' );
						$tiny_url = $tiny_img_data[0];
						$placeholder_url_used = $tiny_url;
					}
				}

				// replace the src and add the data-src attribute
				$replaceHTML = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . esc_attr( $placeholder_url_used ) . '" data-lazy-type="image" data-lazy-src=', $imgHTML );
				
				// also replace the srcset (responsive images)
				$replaceHTML = str_replace( 'srcset', 'data-lazy-srcset', $replaceHTML );
				// replace sizes to avoid w3c errors for missing srcset
				$replaceHTML = str_replace( 'sizes', 'data-lazy-sizes', $replaceHTML );
				
				// add the lazy class to the img element
				if ( preg_match( '/class=["\']/i', $replaceHTML ) ) {
					$replaceHTML = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1lazy lazy-hidden $2$1', $replaceHTML );
				} else {
					$replaceHTML = preg_replace( '/<img/is', '<img class="lazy lazy-hidden"', $replaceHTML );
				}
				
				$replaceHTML .= '<noscript>' . $imgHTML . '</noscript>';
				
				array_push( $search, $imgHTML );
				array_push( $replace, $replaceHTML );
			}
		}

		$content = str_replace( $search, $replace, $content );

		return $content;

	}

	/**
	 * Replace iframes with placeholders in the content
	 *
	 * @param string $content The HTML to do the filtering on
	 * @return string The HTML with the iframes replaced
	 */
	public static function filter_iframes( $content ) {

		$placeholder_url = self::_get_option( 'placeholder_url' );
		$placeholder_url = apply_filters( 'bjll/placeholder_url', $placeholder_url, 'image' );
		if ( ! strlen( $placeholder_url ) ) {
			$placeholder_url = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
		}

		$match_content = self::_get_content_haystack( $content );

		$matches = array();
		preg_match_all( '|<iframe\s+.*?</iframe>|si', $match_content, $matches );
		
		$search = array();
		$replace = array();
		
		foreach ( $matches[0] as $iframeHTML ) {

			// Don't mess with the Gravity Forms ajax iframe
			if ( strpos( $iframeHTML, 'gform_ajax_frame' ) ) {
				continue;
			}

			$replaceHTML = '<img src="' . esc_attr( $placeholder_url ) . '"  class="lazy lazy-hidden" data-lazy-type="iframe" data-lazy-src="' . esc_attr( $iframeHTML ) . '" alt="">';
			
			$replaceHTML .= '<noscript>' . $iframeHTML . '</noscript>';
			
			array_push( $search, $iframeHTML );
			array_push( $replace, $replaceHTML );
		}
		
		$content = str_replace( $search, $replace, $content );

		return $content;

	}

	/**
	 * Remove elements we don’t want to filter from the HTML string
	 *
	 * We’re reducing the haystack by removing the hay we know we don’t want to look for needles in
	 *
	 * @param string $content The HTML string
	 * @return string The HTML string without the unwanted elements
	 */
	protected static function _get_content_haystack( $content ) {
		$content = self::remove_noscript( $content );
		$content = self::remove_skip_classes_elements( $content );

		return $content;
	}

	/**
	 * Remove <noscript> elements from HTML string
	 *
	 * @author sigginet
	 * @param string $content The HTML string
	 * @return string The HTML string without <noscript> elements
	 */
	public static function remove_noscript( $content ) {
		return preg_replace( '/<noscript.*?(\/noscript>)/i', '', $content );
	}

	/**
	 * Remove HTML elements with certain classnames (or IDs) from HTML string
	 *
	 * @param string $content The HTML string
	 * @return string The HTML string without the unwanted elements
	 */
	public static function remove_skip_classes_elements( $content ) {

		$skip_classes = self::_get_skip_classes( 'html' );

		/*
		http://stackoverflow.com/questions/1732348/regex-match-open-tags-except-xhtml-self-contained-tags/1732454#1732454
		We can’t do this, but we still do it.
		*/
		$skip_classes_quoted = array_map( 'preg_quote', $skip_classes );
		$skip_classes_ORed = implode( '|', $skip_classes_quoted );

		$regex = '/<\s*\w*\s*class\s*=\s*[\'"](|.*\s)' . $skip_classes_ORed . '(|\s.*)[\'"].*>/isU';

		return preg_replace( $regex, '', $content );
	}

	/**
	 * Get an option value
	 *
	 * @param string $option_key The name of the option
	 * @return string The option value
	 */
	protected static function _get_option( $option_key ) {
		return self::$_options->get( $option_key );
	}

	/**
	 * Get the skip classes
	 *
	 * @param string $content_type The content type (image/iframe etc)
	 * @return array An array of strings with the class names
	 */
	protected static function _get_skip_classes( $content_type ) {

		$skip_classes = array();

		$skip_classes_str = self::_get_option( 'skip_classes' );
		
		if ( strlen( trim( $skip_classes_str ) ) ) {
			$skip_classes = array_map( 'trim', explode( ',', $skip_classes_str ) );
		}

		if ( ! in_array( 'lazy', $skip_classes ) ) {
			$skip_classes[] = 'lazy';
		}

		/**
		 * Filter the class names to skip
		 *
		 * @param array $skip_classes The current classes to skip
		 * @param string $content_type The current content type
		 */
		$skip_classes = apply_filters( 'bjll/skip_classes', $skip_classes, $content_type );
		
		return $skip_classes;
	}

}

