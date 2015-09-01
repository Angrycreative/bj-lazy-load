<?php

/**
 * The class that handles rewriting of content so we can lazy load it
 */
class BJLL {

	protected static $_options;


	function __construct( $options = null ) {

		if ( is_a( $options, 'BJLL_Options' ) ) {
			self::$_options = $options;
		}

		/**
		 * Filter to let plugins decide whether the plugin should run for this request or not
		 *
		 * Returning false will effectively short-circuit the plugin
		 *
		 * @param bool $enabled Whether the plugin should run for this request
		 */
		$enabled = apply_filters( 'bjll/enabled', true );

		if ( $enabled ) {
			add_action( 'wp', array( $this, 'init' ), 99 ); // run this as late as possible
		}

	}

	/**
	 * Initialize the setup
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		$this->_setup_filtering();
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
		$mtime = filemtime( dirname( dirname( __FILE__ ) ) . '/js/bj-lazy-load.js' );
		wp_enqueue_script( 'BJLL', plugins_url( 'js/bj-lazy-load.js', __DIR__ ), null, $mtime, true );
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
			$placeholder_url = 'data:image/gif;base64,R0lGODdhAQABAPAAAP///wAAACwAAAAAAQABAEACAkQBADs=';
		}
		//$placeholder_url = 'https://bjornjohansen.no/wp-content/plugins/bj-lazy-load/thumb.php?src=https%3A%2F%2Fbjornjohansen.no%2Fwp-content%2Fuploads%2F2014%2F11%2Fdigiskull-770x552.jpg&w=700';

		$match_content = self::_get_content_haystack( $content );

		$matches = array();
		preg_match_all( '/<img[\s\r\n]+.*?>/is', $match_content, $matches );
		
		$search = array();
		$replace = array();

		foreach ( $matches[0] as $imgHTML ) {
			
			// don't to the replacement if the image is a data-uri
			if ( ! preg_match( "/src=['\"]data:image/is", $imgHTML ) ) {

				// replace the src and add the data-src attribute
				$replaceHTML = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . esc_url( $placeholder_url ) . '" data-lazy-type="image" data-lazy-src=', $imgHTML );
				
				// also replace the srcset (responsive images)
				$replaceHTML = str_replace( 'srcset', 'data-lazy-srcset', $replaceHTML );
				
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
		$match_content = self::_get_content_haystack( $content );

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
		$regex = '/<(\w+)\s[^>]*(?:class|id)\s*=\s*([\'"]).*?[^\-]\b(?:' . $skip_classes_ORed . ')\b[^\-].*?\2[^>]*>.*<\/\\1>/isU';
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

