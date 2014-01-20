<?php

class BJLL_Image_resizer {

	function __construct() {
		add_action( 'template_redirect', array( $this, 'check_rocknroll' ) );
		add_action( 'query_vars', array( $this, 'add_custom_query_vars' ), 1 );
	}

	function add_custom_query_vars( $vars ) {
		$vars[] = "bjll";
		$vars[] = "w";
		$vars[] = "img";
		return $vars;
	}

	function check_rocknroll() {
		if ( 'image' == get_query_var( 'bjll' ) && 0 < intval( get_query_var( 'w' ) ) && strlen( get_query_var( 'img' ) ) ) {
			$args = array(
				'img' => get_query_var( 'img' ),
				'w'   => get_query_var( 'w' ),
			);
			$this->rocknroll( $args );
			exit;
		}
	}

	function rocknroll( $args ) {

		if ( ! isset( $args['img'] ) ) {
			wp_die( __( 'No image provided', 'bjll' ) );
		} else {
			$img = $args['img'];
		}

		if ( ! isset( $args['w'] ) ) {
			wp_die( __( 'No width provided', 'bjll' ) );
		} elseif ( ! intval( $args['w'] ) ) {
			wp_die( __( 'Invalid width provided. Integer needed.', 'bjll' ) );
		} else {
			$width = intval( $args['w'] );

			// Let's round it up to closest 10 pixels to avoid generating too many images
			$width = ceil ( $width / 10 ) * 10;
		}

		if ( ! preg_match( '/\.(jpe?g|png|gif)$/i', $img ) ) {
			wp_die(  __( 'Disallowed filename extension', 'bjll' ) );
		}

		$filename_extension = strtolower( preg_replace( '/.+(jpe?g|png|gif)$/i', '$1', $img ) );

		$upload_dir = wp_upload_dir();

		$basedir = $upload_dir['basedir'];

		$bjll_cache_dir = trailingslashit( $basedir ) . 'bjll-cache';
		$cachefile = md5( serialize( $args ) ) . '.' . $filename_extension;
		$cachefile_path = implode( '/', array(
			$bjll_cache_dir,
			substr( $cachefile, 0, 2 ),
			substr( $cachefile, 2, 2 ),
			$cachefile
		) );

		if ( file_exists( $cachefile_path ) ) {
			// No idea if this is very inefficient, but it was easy to code ;-)
			$imgsize = getimagesize( $cachefile_path );
			$filesize = filesize( $cachefile_path );
			header( 'X-BJLL-Image: From cache', true, 200 );
			header( 'Content-Type: ' . $imgsize['mime'] );
			header( 'Content-Length: ' . $filesize );
			readfile( $cachefile_path );
			exit;
		}

		$baseurls = apply_filters( 'bjll_baseurls', array( $upload_dir['baseurl'] ) );


		$img_contentpath = null;
		foreach ( $baseurls as $baseurl ) {
			if ( substr( $img, 0, strlen( $baseurl) ) == $baseurl ) {
				$img_contentpath = substr( $img, strlen( $baseurl ) + 1 );
				break;
			}
		}

		// Not a local image. Redirect to original.
		if ( is_null( $img_contentpath ) ) {
			wp_redirect( $img, 301 );
			exit;
		}
		
		// The requested image doesn't exist
		if ( ! is_file( trailingslashit( $basedir ) . $img_contentpath ) ) {
			wp_die( __( 'Image not found', 'bjll' ) );
		}

		// Get aspect ratio of reqested image
		$imgsize = getimagesize( trailingslashit( $basedir ) . $img_contentpath );
		$ratio = $imgsize[0] / $imgsize[1];
		$height = $width * $ratio;

		// See if we can find original
		$img_contentpath_orig = preg_replace( '/\-\d+x\d+(\.(jpe?g|png|gif))$/i', '$1', $img_contentpath );
		if ( is_file( trailingslashit( $basedir ) . $img_contentpath_orig ) ) {
			$img_contentpath = $img_contentpath_orig;
		}

		// Get the editor
		$image_editor = wp_get_image_editor( trailingslashit( $basedir ) . $img_contentpath );

		// … or fail
		if ( is_wp_error( $image_editor ) ) {
			wp_die( $image_editor );
		}

		$image_editor->resize( $width, $height, true );

		if ( ! is_dir( dirname( $cachefile_path ) ) ) {
			mkdir( dirname( $cachefile_path ), 0775, true );
		}

		$image_editor->save( $cachefile_path );

		header( 'X-BJLL-Image: generated', true, 200 );

		$image_editor->stream();
	}

}

new BJLL_Image_resizer;