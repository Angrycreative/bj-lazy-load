<?php
/*
Plugin Name: BJ Lazy Load
Plugin URI: http://wordpress.org/extend/plugins/bj-lazy-load/
Description: Lazy image loading makes your site load faster and saves bandwidth.
Version: 0.7.5
Author: Bjørn Johansen
Author URI: http://twitter.com/bjornjohansen
Text Domain: bj-lazy-load
License: GPL2

    Copyright 2011–2014  Bjørn Johansen  (email : post@bjornjohansen.no)

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

require_once( dirname(__FILE__) . '/scb/load.php' );
require_once( dirname(__FILE__) . '/inc/lang.php' );
require_once( dirname(__FILE__) . '/inc/class-bjll-skip-post.php' );

if ( ! class_exists( 'BJLL' ) ) {
	class BJLL {

		const version = '0.7.5';
		protected $_placeholder_url;
		protected $_skip_classes;
		
		protected static $_instance;

		function __construct() {

			// Disable when viewing printable page from WP-Print
			if ( intval( get_query_var( 'print' ) ) == 1 || intval( get_query_var( 'printpage' ) ) == 1 ) {
				return;
			}
			
			// Disable on Opera Mini
			if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false ) {
				return;
			}
			
			$options = self::_get_options();

			if ( 'yes' == $options->get( 'disable_on_wptouch' ) && self::is_wptouch() ) {
				return;
			}

			if ( 'yes' == $options->get( 'disable_on_mobilepress' ) && self::is_mobilepress() ) {
				return;
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_filter( 'bj_lazy_load_html', array( __CLASS__, 'filter' ), 10, 1 );
			
			$skip_classes = $options->get( 'skip_classes' );
			if ( strlen( trim( $skip_classes ) ) ) {
				$this->_skip_classes = array_map( 'trim', explode( ',', $options->get( 'skip_classes' ) ) );
			}

			$this->_placeholder_url = $options->get( 'placeholder_url' );
			if ( ! strlen( $this->_placeholder_url ) ) {
				//$this->_placeholder_url = plugins_url( '/img/placeholder.gif', __FILE__ );
				$this->_placeholder_url = 'data:image/gif;base64,R0lGODdhAQABAPAAAP///wAAACwAAAAAAQABAEACAkQBADs=';
			}
			
			if ( $options->get( 'filter_content' ) == 'yes' ) {
				add_filter( 'the_content', array( $this, 'filter' ), 200 );
			}
			if ( $options->get( 'filter_widget_text' ) == 'yes' ) {
				add_filter( 'widget_text', array( $this, 'filter' ), 200 );
			}
			if ( $options->get( 'filter_post_thumbnails' ) == 'yes' ) {
				add_filter( 'post_thumbnail_html', array( $this, 'filter' ), 200 );
			}
			if ( $options->get( 'filter_gravatars' ) == 'yes' ) {
				add_filter( 'get_avatar', array( $this, 'filter' ), 200 );
			}
		}
		
		static function singleton() {
			if ( ! isset( self::$_instance ) ) {
				$className = __CLASS__;
				self::$_instance = new $className;
			}
			return self::$_instance;
		}
		
		static function enqueue_scripts() {
		
			$in_footer = true;
			
			$options = self::_get_options();
			$theme_loader_function = $options->get( 'theme_loader_function' );
			
			if ( $theme_loader_function == 'wp_head' ) {
				$in_footer = false;
			}

			

			if ( defined( 'SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
				wp_enqueue_script( 'jquery.sonar', plugins_url( '/js/jquery.sonar.js', __FILE__ ), array( 'jquery' ), self::version, $in_footer );
				wp_enqueue_script( 'BJLL', plugins_url( '/js/bj-lazy-load.js', __FILE__ ), array( 'jquery', 'jquery.sonar' ), self::version, $in_footer );
			} else {
				wp_enqueue_script( 'BJLL', plugins_url( '/js/combined.min.js', __FILE__ ), array( 'jquery' ), self::version, $in_footer );
			}

			$bjll_options = array();

			if ( $options->get('load_hidpi') == 'yes' || $options->get('load_responsive') == 'yes' ) {
				$bjll_options['thumb_base'] = plugins_url( '/thumb.php', __FILE__ ) . '?src=';
				$bjll_options['load_hidpi'] = $options->get('load_hidpi');
				$bjll_options['load_responsive'] = $options->get('load_responsive');

				if ( is_multisite() ) {
					$bjll_options['site_url'] = get_site_url();
					$bjll_options['network_site_url'] = network_site_url();
				}
			}

			if ( $options->get('infinite_scroll') == 'yes' ) {
				$bjll_options['infinite_scroll'] = $options->get('infinite_scroll');
			}

			if ( intval( $options->get('threshold') ) != 200 ) {
				$bjll_options['threshold'] = intval( $options->get('threshold') );
			}
			

			if ( count( $bjll_options ) ) {
				wp_localize_script( 'BJLL', 'BJLL', $bjll_options );
			}

		}
		
		static function filter( $content ) {

			$run_filter = true;
			$run_filter = apply_filters( 'bj_lazy_load_run_filter', $content );

			if ( ! $run_filter ) {
				return $content;
			}
		
			$BJLL = BJLL::singleton();
			
			$options = self::_get_options();
			
			if ( $options->get('lazy_load_images') == 'yes' ) {
				$content = $BJLL->_filter_images( $content );
			}
			
			if ( $options->get('lazy_load_iframes') == 'yes' ) {
				$content = $BJLL->_filter_iframes( $content );
			}
		
			return $content;
		}
		
		protected function _filter_images( $content ) {
		
			$matches = array();
			preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );
			
			$search = array();
			$replace = array();

			if ( is_array( $this->_skip_classes ) ) {
				$skip_images_preg_quoted = array_map( 'preg_quote', $this->_skip_classes );
				$skip_images_regex = sprintf( '/class=".*(%s).*"/s', implode( '|', $skip_images_preg_quoted ) );
			}
			
			foreach ( $matches[0] as $imgHTML ) {
				
				// don't to the replacement if a skip class is provided and the image has the class, or if the image is a data-uri
				if ( ! ( is_array( $this->_skip_classes ) && preg_match( $skip_images_regex, $imgHTML ) ) && ! preg_match( "/src=['\"]data:image/is", $imgHTML ) ) {
					// replace the src and add the data-src attribute
					$replaceHTML = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . $this->_placeholder_url . '" data-lazy-type="image" data-lazy-src=', $imgHTML );
					
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
		
		protected function _filter_iframes( $content ) {
		
			$matches = array();
			preg_match_all( '/<iframe\s+.*?>/', $content, $matches );
			
			$search = array();
			$replace = array();
			
			foreach ( $matches[0] as $iframeHTML ) {

				// Don't mess with the Gravity Forms ajax iframe
				if ( strpos( $iframeHTML, 'gform_ajax_frame' ) ) {
					continue;
				}

				$replaceHTML = '<img src="' . $this->_placeholder_url . '"  class="lazy lazy-hidden" data-lazy-type="iframe" data-lazy-src="' . base64_encode($iframeHTML) . '" alt="">';
				
				$replaceHTML .= '<noscript>' . $iframeHTML . '</noscript>';
				
				array_push( $search, $iframeHTML );
				array_push( $replace, $replaceHTML );
			}
		
			$content = str_replace( $search, $replace, $content );
			
			return $content;
		}
		
		protected static function _get_options() {
			return new scbOptions( 'bj_lazy_load_options', __FILE__, array(
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
		}
		
		static function options_init() {
		
			$options = self::_get_options();

			// Creating settings page objects
			if ( is_admin() ) {
				require_once( dirname( __FILE__ ) . '/admin.php' );
				new BJLL_Admin_Page( __FILE__, $options );
			}
		}

		static function is_wptouch() {
			if ( function_exists( 'bnc_wptouch_is_mobile' ) && bnc_wptouch_is_mobile() ) {
				return true;
			}

			global $wptouch_pro;

			if ( defined( 'WPTOUCH_VERSION' ) || is_object( $wptouch_pro ) ) {
				
				if ( $wptouch_pro->showing_mobile_theme ) {
					return true;
				}
			}

			return false;
		}

		static function has_wptouch() {
			if ( function_exists( 'bnc_wptouch_is_mobile' ) || defined( 'WPTOUCH_VERSION' ) ) {
				return true;
			}

			return false;
		}

		static function is_mobilepress() {

			if ( function_exists( 'mopr_get_option' ) && WP_CONTENT_DIR . mopr_get_option( 'mobile_theme_root', 1 ) == get_theme_root() ) {
				return true;
			}

			return false;
		}

		static function has_mobilepress() {
			if ( class_exists( 'Mobilepress_core' ) ) {
				return true;
			}

			return false;
		}
		
	}
}


add_action( 'wp', create_function('', 'if ( ! is_feed() ) { BJLL::singleton(); }'), 10, 0 );

scb_init( array( 'BJLL', 'options_init' ) );

