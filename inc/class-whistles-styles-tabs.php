<?php
/**
 * Whistles_Styles_Tabs class. Extends the Whistles_And_Bells class to format 
 * the whistle posts into a group of tabs.
 *
 * This is an only slightly changed version of Justin Tadlocks Whistles_And_Tabs class 
 * to inject an additional CSS class. The only changes are an added CSS class
 * and the disabled loading of Javascript.
 *
 * @since      0.1
 * @author     Justin Tadlock, some small changes by Frank Stürzebecher
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/whistles
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Avoid direct execution of this file */
if( !defined( 'WHISTLES_STYLES' ) )
	exit;

class Whistles_Styles_Tabs extends Whistles_And_Bells {

	/**
	 * Custom markup for the ouput of tabs.
	 *
	 * @since  0.1
	 * @access public
	 * @param  array   $whistles
	 * @return string
	 */
	public function set_markup( $whistles ) {

		/* Set up an empty string to return. */
		$output = '';

		/* If we have whistles, let's roll! */
		if ( !empty( $whistles ) ) {

			/* Generate random ID. */
			$rand = mt_rand();

			/* Inject the choosen style from the shortcode popup window as CSS class. */
			$style = ( isset( $this->args['style'] ) ) ? ' ' . sanitize_html_class( $this->args['style'] ) : '';

			/* Open tabs wrapper. */
			$output .= '<div class="whistles whistles-tabs' . $style . '">';

			/* Open tabs nav. */
			$output .= '<ul class="whistles-tabs-nav">';

			/* Loop through each whistle title and format it into a list item. */
			foreach ( $whistles as $whistle ) {

				$id = sanitize_html_class( 'whistle-' . $this->args['group'] . '-' . $whistle['id'] . '-' . $rand );

				$output .= '<li class="whistle-title"><a href="#' . $id . '">' . $whistle['title'] . '</a></li>';
			}

			/* Close tabs nav. */
			$output .= '</ul><!-- whistles-tabs-nav -->';

			/* Open tabs content wrapper. */
			$output .= '<div class="whistles-tabs-wrap">';

			/* Loop through each whistle and format its content into a tab content block. */
			foreach ( $whistles as $whistle ) {

				$id = sanitize_html_class( 'whistle-' . $this->args['group'] . '-' . $whistle['id'] . '-' . $rand );

				$output .= '<div id="' . $id . '" class="whistle-content">' . $whistle['content'] . '</div>';
			}

			/* Close tabs and tabs content wrappers. */
			$output .= '</div><!-- .whistles-tabs-wrap -->';
			$output .= '</div><!-- .whistles-tabs -->';
		}

		/* Return the formatted output. */
		return $output;
	}
}

?>
