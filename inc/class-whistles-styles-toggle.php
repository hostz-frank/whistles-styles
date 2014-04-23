<?php
/**
 * Whistles_Style_Toggle class.  Extends the Whistles_And_Bells class to format 
 * the whistle posts into a group of toggles.
 *
 * This is an only slightly changed version of Justin Tadlocks Whistles_And_Toggles class 
 * to inject an additional CSS class. The only changes are an added CSS class
 * and the disabled loading of Javascript.
 *
 * @package    Whistles Styles
 * @since      0.1
 * @author     Justin Tadlock <justin@justintadlock.com>, some small changes by Frank Stürzebecher
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/whistles
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class Whistles_Styles_Toggle extends Whistles_And_Bells {

	/**
	 * Custom markup for the ouput of toggles.
	 *
	 * @since  0.1
	 * @param  array   $whistles
	 * @return string
	 */
	public function set_markup( $whistles ) {

		/* Set up an empty string to return. */
		$output = '';

		/* If we have whistles, let's roll! */
		if ( !empty( $whistles ) ) {

			/* Inject the choosen style from the shortcode popup window as CSS class. */
			$style = ( isset( $this->args['style'] ) ) ? ' ' . sanitize_html_class( $this->args['style'] ) : '';

			/* Open the toggle wrapper. */
			$output .= '<div class="whistles whistles-toggle' . $style . '">';

			/* Loop through each of the whistles and format the output. */
			foreach ( $whistles as $whistle ) {

				$output .= '<h3 class="whistle-title">' . $whistle['title'] . '</h3>';

				$output .= '<div class="whistle-content">' . $whistle['content'] . '</div>';
			}

			/* Close the toggle wrapper. */
			$output .= '</div>';
		}

		/* Return the formatted output. */
		return $output;
	}
}

?>
