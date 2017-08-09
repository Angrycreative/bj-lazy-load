<?php
/*
Plugin Name: BJ Lazy Load
Plugin URI: https://wordpress.org/plugins/bj-lazy-load/
Description: Lazy image loading makes your site load faster and saves bandwidth.
Version: 1.0.9
Author: Bjørn Johansen, Aron Tornberg, angrycreative
Author URI: https://angrycreative.se/
Text Domain: bj-lazy-load
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

include 'inc/class-bjll-options.php';
include 'inc/class-bjll-skip-post.php';
include 'inc/class-bjll.php';

function bj_lazy_load() {
    load_plugin_textdomain( 'bj-lazy-load', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

    $bjll_options = new BJLL_Options();
    $bjll = new BJLL( $bjll_options );
    if ( $bjll_options->get('preview') == 'yes' ) {
        add_image_size( 'tiny-lazy', 30, 30 );
    }
}

add_action( 'plugins_loaded', 'bj_lazy_load' );
