<?php
/**
 * Plugin Name: Slick WP Gallery
 * Version: 1.0.0
 * Plugin URI: http://github.com/dominiccarrington/Slick-WP-Gallery
 * Description: Convert the standard WP Gallery into a Slick Carousel
 * Author: Dominic Carrington
 * Author URI: http://dominiccarrington.github.io
 * Requires at least: 5.0
 * Tested up to: 5.4
 *
 * @package WordPress
 * @author Dominic Carrington
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files.
require_once 'includes/class-slick-wp-gallery.php';
require_once 'includes/class-slick-wp-gallery-settings.php';
require_once 'includes/class-slick-wp-gallery-shortcodes.php';

// Load plugin libraries.
require_once 'includes/lib/class-slick-wp-gallery-admin-api.php';
require_once 'includes/lib/class-slick-wp-gallery-post-type.php';
require_once 'includes/lib/class-slick-wp-gallery-taxonomy.php';

/**
 * Returns the main instance of Slick_WP_Gallery to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Slick_WP_Gallery
 */
function slick_wp_gallery() {
	$instance = Slick_WP_Gallery::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Slick_WP_Gallery_Settings::instance( $instance );
	}

	return $instance;
}

slick_wp_gallery();
