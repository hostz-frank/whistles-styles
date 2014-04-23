<?php
/**
 * Both frontend and backend functionality.
 *
 * @package    Whistles Styles
 * @since      0.1
 * @author     Frank Stürzebecher <frank@netzklad.de>
 * @copyright  Copyright (c) 2013, Frank Stürzebecher
 * @link       http://netzklad.de
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Avoid direct execution of this file */
if( !defined( 'WHISTLES_STYLES' ) )
	exit;

/**
 * Callback function, hooked into core action 'init'.
 *
 * Enhance basic features of whistles to support slider images and
 * the needs of other new display types.
 *
 * @since   0.1
 * @return  void
 */
function whistles_styles_init() {
	add_post_type_support( 'whistle', array( 
		'thumbnail',       // Slider images
		'custom-fields',   // Unforeseen use cases
		'page-attributes'  // Show the attributes select box for whistles (parents and order).
	) );
}
add_action('init', 'whistles_styles_init');

/**
 * Callback function, hooked into core action 'registered_post_type'.
 *
 * Let whistles be hierarchical/ have parents - if needed.
 *
 * @since   0.1
 * @return  void
 */
function whistles_styles_post_type_whistles_alter( $post_type, $args ) {
	if ( 'whistle' === $post_type ) {
		global $wp_post_types;
		$wp_post_types[ 'whistle' ]->hierarchical = true;
	}
}
add_action( 'registered_post_type', 'whistles_styles_post_type_whistles_alter', 10, 2 );


/**
 * Callback function, hooked into core action 'plugins_loaded'.
 *
 * Hijack Whistles' widget.
 *
 * @since   0.1
 * @return  void
 */
function whistles_styles_unregister_whistles_widget() {

	// Remove the original widget of the Whistles plugin.
	remove_action( 
		'widgets_init', 'whistles_register_widgets' );

	// Load replacement.
	//TODO
}
add_action('plugins_loaded', 'whistles_styles_unregister_whistles_widget');





/**
 * Helper function to fetch a list of styles.
 *
 * Compose an array of available styles by reading CSS and Javascript filenames
 * from whether a custom or the plugin's default style folder. Minified file 
 * versions will be prefered if SCRIPT_DEBUG is switched off.
 *
 * @since   0.1
 * @param   bool   $nocache
 * @return  array  List of styles as assoc. arrays || Empty array.
 */
function whistles_styles_read_styles( $nocache = false ) {

	static $styles = array();

	if( !$nocache && count( $styles ) )
		return $styles;

	/* Read a custom styles directory if it exists, otherwise the default styles dir.
	   To disable default styles copy desired style files into the custom directory. */

	if( is_dir( WHISTLES_STYLES_CUSTOM_DIR ) ) {
		$style_path = WHISTLES_STYLES_CUSTOM_DIR;
	} else {
		$style_path = WHISTLES_STYLES_PLUGIN_DIR . 'styles/';
	}

	$style_dir = @opendir( $style_path );
	if ( $style_dir ) {
		while ( ( $file = readdir( $style_dir ) ) !== false ) {
			if ( substr( $file, 0, 1 ) == '.' ) {
				continue;
			}

			$file_parts = explode( '-', $file, 2 ); // Split filename at the first dash.
			if( count( $file_parts ) < 2 ) {
				/* No dash in filenames means for now: we have no style to deal with. */
				continue;
			}
			$type = sanitize_key( $file_parts[ 0 ] );

			/* Prefer minified files. */
			if( substr( $file_parts[ 1 ], -8 ) == '.min.css' ) {
				$style_key = sanitize_key( substr( $file, 0, strlen( $file ) - 8 ) );
				$styles[ $type ][ $style_key ][ 'css' ] = $file;
			} elseif( substr( $file_parts[ 1 ], -4 ) == '.css' ) {
				$style_key = sanitize_key( substr( $file, 0, strlen( $file ) - 4 ) );
				$styles[ $type ][ $style_key ][ 'css' ] = $file;
			} elseif( substr( $file_parts[ 1 ], -7 ) == '.min.js' ) {
				$style_key = sanitize_key( substr( $file, 0, strlen( $file ) - 7 ) );
				$styles[ $type ][ $style_key ][ 'js' ] = $file;
			} elseif( substr( $file_parts[ 1 ], -3 ) == '.js' ) {
				$style_key = sanitize_key( substr( $file, 0, strlen( $file ) - 3 ) );
				$styles[ $type ][ $style_key ][ 'js' ] = $file;
			} else {
				// Ignore other files.
			}
		} // end while: reading files from directory

		@closedir( $style_dir );
	}
//print '<pre>' . print_r($styles,1) . '</pre>'; //TODO
	return $styles;
}

/**
 * Callback function, hooked into Whistles' filter 'whistles_allowed_types'.
 *
 * Auto-extend the list of allowed whistle types, if there is an output class 
 * found to include as a file AND if style files exist.
 */
function whistles_styles_extend_allowed_types( $allowed_types ) {
	$styles = whistles_styles_read_styles();
	foreach( $styles as $type => $style_files ) {
		if( !isset( $allowed_types[ $type ] ) ) {
			if( file_exists( WHISTLES_STYLES_PLUGIN_DIR . 'inc/class-whistles-styles-' . $type . '.php' ) || file_exists( WHISTLES_STYLES_CUSTOM_DIR . 'class-whistles-styles-' . $type . '.php' )) {
				$allowed_types += array( $type => ucfirst( $type ) );
			}
		}
	}
	return $allowed_types;
}
add_filter( 'whistles_allowed_types', 'whistles_styles_extend_allowed_types' );
?>
