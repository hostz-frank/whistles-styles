<?php
/**
 * Frontend functionality.
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

include_once( WHISTLES_STYLES_PLUGIN_DIR . 'inc/whistles-styles-common.php' );

/**
 * Callback function, hooked into core action 'wp_enqueue_scripts'.
 *
 * Dequeue the Whistles plugin's CSS and JS files to keep all stuff together.
 *
 * @since  0.1
 * @return void
 */
function whistles_styles_dequeue_default_style() { 

	// Read available styles from file system.
	$styles = whistles_styles_read_styles();

	// Let Whistles default styles live, if a custom style directory exists and is empty.
	if( count( $styles ) ) {
		wp_dequeue_style( 'whistles' );
		wp_dequeue_script( 'whistles' );
	}

}
add_action( 'wp_enqueue_scripts', 'whistles_styles_dequeue_default_style', 15 );


/**
 * Callback function, hooked into Whistles' filter 'whistles_object'.
 *
 * Switch output class, e.g. to inject a style specific CSS class.
 * Returning null causes the default Whistles output classes to be used.
 * Class files in a custom directory will override plugin files.
 *
 * @since  0.1
 * @param  array  $args  Shortcode attributes
 * @return object Custom class || Null
 */
function whistles_styles_override_output( $value, $args ) {
	if( isset( $args['style'] ) ) {
		$type = sanitize_html_class( $args['type'] );
		$file = WHISTLES_STYLES_PLUGIN_DIR . 'inc/class-whistles-styles-' . $type . '.php';
		$file_override = WHISTLES_STYLES_CUSTOM_DIR . 'class-whistles-styles-' . $type . '.php';

		if( file_exists( $file_override ) ) {
			require_once( $file_override );
		} elseif( file_exists( $file ) ) {
			require_once( $file );
		}

		$class = 'Whistles_Styles_' . ucfirst( $type );
		if( class_exists( $class ) ) {
			return new $class( $args );
		}

	} // end if: style given by shortcode

	// At this point the original output classes of the Whistles plugin will be used.
	return null;
}
add_filter( 'whistles_object', 'whistles_styles_override_output', 10, 2 );


/**
 * Callback function, hooked into core action 'the_posts'.
 *
 * Enqueue only needed whistles style files. Search for available styles in all posts
 * since it's possible to have several whistles shortcodes and/or styles in one request.
 *
 * @since  0.1
 * @param  array $posts Posts
 * @return array        Posts
 */
function whistles_styles_enqueue_styles( $posts ) {
	if ( empty( $posts ) )
		return $posts;
		
	foreach ($posts as $post) {

		// Do we have whistles in this post?
		if ( strpos( $post->post_content, '[whistles' ) !== false ) {

			// Return if there are no styles (which all belong to a certain type).
			// This may happen if a custom style directory exists and is empty.
			$types = whistles_styles_read_styles();
			if( count( $types ) == 0 )
				return $posts;

			// Array of already enqueued files.
			$processed = array();

			// Take the appropriate styles directory.
			if( is_dir( WHISTLES_STYLES_CUSTOM_DIR ) ) {
				$styles_directory = WHISTLES_STYLES_CUSTOM_URL;
			} else {
				$styles_directory = WHISTLES_STYLES_PLUGIN_URL . 'styles/';
			}

			// Search for available styles in this post.
			foreach( $types as $type => $its_styles ) {
				foreach( $its_styles as $style => $files ) {
					$needle = 'style="' . $style;

					// Enqueue CSS and JS for found styles only.
					if( stripos( $post->post_content, $needle ) !== false ) {
						if( isset( $files['css'] ) && !in_array( $files['css'], $processed ) ) {

							wp_enqueue_style( 
								'whistles-' . $style, // handle
								$styles_directory . $files['css'], // source
								false, // dependencies
								WHISTLES_STYLES_VERSION,
								'all' // media
							);

							$processed[] = $files['css'];
						}
						if( isset( $files['js'] ) && !in_array( $files['js'], $processed ) ) {

							wp_enqueue_script( 
								'whistles-' . $style, // handle
								$styles_directory . $files['js'], // source
								array(), // dependencies
								WHISTLES_STYLES_VERSION,
								true // to footer
							);

							$processed[] = $files['js'];
						}
					} // end if: available style found in this post
				} // end foreach: style (e.g. tabs-bottom)
			} // end foreach: available types (e.g. tabs, accordion ...)
		} // end if: [whistles] shortcode(s) found in this post
	} // end foreach: posts of this request

	return $posts;
}
add_action( 'the_posts', 'whistles_styles_enqueue_styles' );

?>
