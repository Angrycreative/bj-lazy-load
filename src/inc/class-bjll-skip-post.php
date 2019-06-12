<?php
/*
License: GPL2

	Copyright 2011–2013  Bjørn Johansen  (email : post@bjornjohansen.no)

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

if ( ! class_exists( 'BJLL_Skip_Post' ) ) {

	class BJLL_Skip_Post {
		function __construct() {

			add_action( 'add_meta_boxes', array( $this, 'bjll_add_meta_box' ) );
			add_action( 'save_post', array( $this, 'bjll_save_post' ) );

			add_filter( 'bj_lazy_load_run_filter', array( $this, 'run_filter_check' ), 10, 1 );
		}

		public function bjll_add_meta_box() {
			$post_types = get_post_types( array( 'public' => true ), 'names' ); 

			foreach ( $post_types as $post_type ) {
				add_meta_box( 'bj_lazy_load_skip_post', __( 'Lazy Loading', 'bj-lazy-load' ), array( $this, 'bj_lazy_load_skip_post_meta_box' ), $post_type, 'side', 'low' );
			}
		}

		public function bj_lazy_load_skip_post_meta_box( $post ) {
			wp_nonce_field( 'bj_lazy_load_skip_post_meta_box', 'bj_lazy_load_skip_post_meta_box_nonce' );

			$bj_lazy_load_skip_post_value = get_post_meta( $post->ID, '_bj_lazy_load_skip_post', true );

			printf( '<input type="checkbox" id="bj_lazy_load_skip_post_value" name="bj_lazy_load_skip_post_value" value="true" size="25" %s>', checked( $bj_lazy_load_skip_post_value, 'true', false ) );

			printf(
				'<label for="bj_lazy_load_skip_post_value"> %s</label>',
				$post->post_type == 'page' ? __( 'Skip lazy loading for this page', 'bj-lazy-load' ) : __( 'Skip lazy loading for this post', 'bj-lazy-load' )
			);

		}

		public function bjll_save_post( $post_id ) {
			// Check if our nonce is set.
			if ( ! isset( $_POST['bj_lazy_load_skip_post_meta_box_nonce'] ) ) {
				return $post_id;
			}

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_POST['bj_lazy_load_skip_post_meta_box_nonce'], 'bj_lazy_load_skip_post_meta_box' ) ) {
				return $post_id;
			}


			$bj_lazy_load_skip_post_value = 'false';
			if ( isset( $_POST['bj_lazy_load_skip_post_value'] ) && 'true' == $_POST['bj_lazy_load_skip_post_value'] ) {
				$bj_lazy_load_skip_post_value = 'true';
			}

			// Update the meta field in the database.
			update_post_meta( $post_id, '_bj_lazy_load_skip_post', $bj_lazy_load_skip_post_value );

		}

		public function run_filter_check( $content ) {

			$run_filter = true;

			if ( in_the_loop() && 'true' == get_post_meta( get_the_ID(), '_bj_lazy_load_skip_post', true ) ) {
				$run_filter = false;
			}

			return $run_filter;

		}
	}

}

new BJLL_Skip_Post;