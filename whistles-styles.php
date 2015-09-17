<?php
/**
 * Plugin Name: Whistles Styles
 * Description: Apply styles to tabs, toggles, accordions, created with the great Whistles plugin. Plug in styles and also new types of content collections, like sliders or portfolios.
 * Version: 0.8
 * Author: Frank St&uuml;rzebecher <frank@netzklad.de>
 * Author URI: http://netzklad.de
 * GitHub Plugin URI: https://github.com/medizinmedien/whistles-styles
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   Whistles Styles
 * @version   0.8
 * @author    Frank Stürzebecher <frank@netzklad.de>
 * @copyright Copyright (c) 2013, Frank Stürzebecher
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

	/* Avoid direct execution of this file. */
	if( !defined( 'ABSPATH' ) )
		exit;

	// Solve issues with SSL pages when WP-'siteurl' is defined as non-https.
	$wp_content_url = WP_CONTENT_URL;
	if( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == 'on' ) {
		$wp_content_url = str_replace( 'http://', 'https://', WP_CONTENT_URL );
	}

	/* Path constants. */
	define( 'WHISTLES_STYLES', '1' );
	define( 'WHISTLES_STYLES_VERSION', '0.8' );
	define( 'WHISTLES_STYLES_PLUGIN_DIR', trailingslashit( dirname( __FILE__ ) ) );
	define( 'WHISTLES_STYLES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	defined( 'WHISTLES_STYLES_CUSTOM_DIR' ) || define( 'WHISTLES_STYLES_CUSTOM_DIR', WP_CONTENT_DIR . '/whi-styles/' );
	defined( 'WHISTLES_STYLES_CUSTOM_URL' ) || define( 'WHISTLES_STYLES_CUSTOM_URL', $wp_content_url . '/whi-styles/' );

	/* Load, depending on context. */
	if( is_admin() ) {
		require_once( WHISTLES_STYLES_PLUGIN_DIR . 'inc/whistles-styles-admin.php' );

	} elseif( !( defined('DOING_AJAX') && DOING_AJAX ) ) {

		require_once( WHISTLES_STYLES_PLUGIN_DIR . 'inc/whistles-styles-front.php' );
	}

/**
 * Callback function, hooked into core action 'plugins_loaded'.
 *
 * Check the activated Whistles plugin and deactivate this if it's missing.
  */
function whistles_styles_check_dependency() {

	if( !class_exists( 'Whistles_And_Bells' ) ) {

		// Display an admin error.
		add_action( 'admin_notices', 'whistles_styles_parent_missing' );

		// Deactivate this plugin.
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		deactivate_plugins( __FILE__ );

		// Avoid WP success message for (failed) activation, which would be 
		// displayed otherwise.
		unset( $_GET['activate'] );
	}
}
add_action( 'plugins_loaded', 'whistles_styles_check_dependency' );

/**
 * Callback function, hooked into core action 'admin_notices'.
 *
 * Print an error message about this plugins dependency.
 * Called if the Whistles plugin is missing.
 */ 
function whistles_styles_parent_missing() {
	?>
		<div class="error">
			<p><?php _e( 'Plugin <strong>Whistles Styles</strong> deactivated! It depends on the the <strong>Whistles</strong> plugin, which is missing.', 'whistles-styles' ); ?></p>
		</div>
	<?php
}
?>
