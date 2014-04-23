<?php 
/**
 * Backend functionality.
 *
 * @package    Whistles Styles
 * @since      0.1
 * @author     Frank St. <frank@hostz.at>
 * @copyright  Copyright (c) 2013, Frank St.
 * @link       http://www.hostz.at
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Avoid direct execution of this file */
if( !defined( 'WHISTLES_STYLES' ) )
	exit;

include_once( WHISTLES_STYLES_PLUGIN_DIR . 'inc/whistles-styles-common.php' );

/**
 * Callback function, hooked into core action 'plugins_loaded'.
 *
 * Hijack Whistles' shortcode popup.
 *
 * @since   0.1
 * @return  void
 */
function whistles_styles_shortcode_popup_alter() {

	// Remove original popup of the Whistles plugin.
	remove_action( 'admin_footer-post-new.php', 'whistles_editor_shortcode_popup' );
	remove_action( 'admin_footer-post.php',     'whistles_editor_shortcode_popup' );

	// Load replacement function.
	add_action( 'admin_footer-post-new.php', 'whistles_styles_editor_shortcode_popup' );
	add_action( 'admin_footer-post.php',     'whistles_styles_editor_shortcode_popup' );
}
add_action( 'plugins_loaded', 'whistles_styles_shortcode_popup_alter' );


/**
 * Callback for core hook 'plugins_loaded'.
 *
 * Provide a replaced shortcode configuration popup when the "Add Whistles"
 * media button is clicked.
 * Add the option to choose from provided Whistles Styles.
 *
 * @since   0.1
 * @return  void
 */
function whistles_styles_editor_shortcode_popup() {

	if ( !current_user_can( 'edit_whistles' ) )
		return;

	$type = whistles_get_allowed_types();

	$terms = get_terms( 'whistle_group' );

	if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
		$all_terms = $terms;
		$default_term = array_shift( $all_terms );
		$default_term = $default_term->slug;
	} else {
		$default_term = '';
	}

	/* Create an array of order options. */
	$order = array(
		'ASC'  => esc_attr__( 'Ascending',  'whistles' ),
		'DESC' => esc_attr__( 'Descending', 'whistles' )
	);

	/* Create an array of orderby options. */
	$orderby = array( 
		'author' => esc_attr__( 'Author', 'whistles' ),
		'date'   => esc_attr__( 'Date',   'whistles' ),
		'ID'     => esc_attr__( 'ID',     'whistles' ),  
		'rand'   => esc_attr__( 'Random', 'whistles' ),
		'name'   => esc_attr__( 'Slug',   'whistles' ),
		'title'  => esc_attr__( 'Title',  'whistles' ),
	);

	/* Build an array of allowed styles from available ones in the file system.
	   This prevents display of styles without an existing output class. */
	$styles = array();
	$styles_avail = whistles_styles_read_styles();

	foreach( $type as $allowed_type => $key ) {
		if( isset( $styles_avail[ $allowed_type ] ) ) {
			foreach( $styles_avail[ $allowed_type ] as $style_name => $files_array ) {
				$styles[ $style_name ] = _whistles_styles_human_readable_style( $style_name );
			}
		} else {
			// If there is no style for an allowed type then we hide the type.
			unset( $type[ $allowed_type ] );
		}
	}

	?>
	<script>
		jQuery( document ).ready(

			function() {

				/* Init of styles-to apply, depending on type */
				selected_type = jQuery( 'input:radio[name=whistles-type]:checked' ).val();
				jQuery( '#whistles-id-style option[value|="' + selected_type + '"]' ).removeAttr( 'disabled' );
				jQuery( '#whistles-id-style :not(option[value|="' + selected_type + '"])' ).attr( 'disabled', 'disabled' );
				jQuery( '#whistles-id-style :not(option[disabled="disabled"])' ).prop( 'selected', true );

				jQuery( '#whistles-submit' ).attr( 
					'value', 
					'<?php echo esc_js( __( 'Insert', 'whistles' ) ); ?> ' + jQuery( 'input:radio[name=whistles-type]:checked + label' ).text()
				);
				
				style_selects = jQuery( '#whistles-id-style' );
				jQuery( 'input:radio[name=whistles-type]' ).change(
					function() {
						jQuery( '#whistles-submit' ).attr( 
							'value', 
							'<?php echo esc_js( __( 'Insert', 'whistles' ) ); ?> ' + jQuery( this ).next( 'label' ).text() 
						);

						/* Update of styles-to apply, depending on type */
						selected_type = jQuery( 'input:radio[name=whistles-type]:checked' ).val();
						jQuery( '#whistles-id-style option[value|="' + selected_type + '"]' ).removeAttr( 'disabled' );
						jQuery( '#whistles-id-style :not(option[value|="' + selected_type + '"])' ).attr( 'disabled', 'disabled' );
						jQuery( '#whistles-id-style :not(option[disabled="disabled"])' ).prop( 'selected', true );
					}
				);
			}
		);

		function whistles_insert_shortcode(){
			var type    = jQuery( 'input:radio[name=whistles-type]:checked' ).val();
			var group   = jQuery( 'select#whistles-id-group option:selected' ).val();
			var order   = jQuery( 'select#whistles-id-order option:selected' ).val();
			var orderby = jQuery( 'select#whistles-id-orderby option:selected' ).val();
			var limit   = jQuery( 'input#whistles-id-limit' ).val();
			var style   = jQuery( 'select#whistles-id-style option:selected' ).val(); // new option

			window.send_to_editor( 
				'[whistles type="' + type + '" group="' + group + '" order="' + order + '" orderby="' + orderby + '" limit="' + limit + '" style="' +  style + '"]'
			);
		}
	</script>

	<div id="whistles-shortcode-popup" style="display:none;">

		<div class="wrap">

		<?php if ( empty( $terms ) ) { ?>
			<p>
				<?php _e( 'You need at least one whistle group to display whistles.', 'whistles' ); ?> 
				<?php if ( current_user_can( 'manage_whistles' ) ) { ?>
					<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=whistle_group&post_type=whistle' ); ?>"><?php _e( 'Whistle Groups &rarr;', 'whistles' ); ?></a>
				<?php } ?>
			</p>
			<p class="submitbox">
				<a class="button-secondary" href="#" onclick="tb_remove(); return false;"><?php _e( 'Cancel', 'whistles' ); ?></a>
			</p>
		<?php } else { ?>
			<p>
				<?php _e( 'Type', 'whistles' ); ?>
				<?php foreach ( $type as $option_value => $option_label ) { ?>
					<br />
					<input type="radio" name="whistles-type" id="<?php echo esc_attr( 'whistles-id-type-' . $option_value ); ?>" value="<?php echo esc_attr( $option_value ); ?>" <?php checked( 'tabs', $option_value ); ?> /> 
					<label for="<?php echo esc_attr( 'whistles-id-type-' . $option_value ); ?>"><?php echo esc_html( $option_label ); ?></label>
				<?php } ?>
			</p>

			<p>
				<label for="<?php echo esc_attr( 'whistles-id-group' ); ?>"><?php _e( 'Group', 'whistles' ); ?></label> 
				<br />
				<select class="widefat" id="<?php echo esc_attr( 'whistles-id-group' ); ?>" name="<?php echo esc_attr( 'whistles-name-group' ); ?>">
					<?php foreach ( $terms as $term ) { ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $default_term, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( 'whistles-id-limit' ); ?>"><?php _e( 'Number of whistles to display', 'whistles' ); ?></label> 
				<input type="text" maxlength="3" size="3" class="code" id="<?php echo esc_attr( 'whistles-id-limit' ); ?>" name="<?php echo esc_attr( 'whistles-name-limit' ); ?>" value="-1" />
			</p>
			<p>
				<label for="<?php echo esc_attr( 'whistles-id-order' ); ?>"><?php _e( 'Order', 'whistles' ); ?></label> 
				<br />
				<select class="widefat" id="<?php echo esc_attr( 'whistles-id-order' ); ?>" name="<?php echo esc_attr( 'whistles-name-order' ); ?>">
					<?php foreach ( $order as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( 'DESC', $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
					<?php } ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( 'whistles-id-orderby' ); ?>"><?php _e( 'Order By', 'whistles' ); ?></label>
				<br />
				<select class="widefat" id="<?php echo esc_attr( 'whistles-id-orderby' ); ?>" name="<?php echo esc_attr( 'whistles-name-orderby' ); ?>">
					<?php foreach ( $orderby as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( 'date', $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
					<?php } ?>
				</select>
			</p>

			<?php /////////// STYLES OPTION begin /////////// ?>
			<p id="style">
				<label for="whistles-id-style"><?php _e( 'Style', 'whistles-styles' ); ?></label>
				<br />
				<select class="widefat" id="whistles-id-style" name="whistles-name-style">
					<?php foreach ( $styles as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( 'none', $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
					<?php } ?>
				</select>
			</p>
			<?php /////////// STYLES OPTION end /////////// ?>

			<p class="submitbox">
				<input type="submit" id="whistles-submit" value="<?php esc_attr_e( 'Insert Whistles', 'whistles' ); ?>" class="button-primary" onclick="whistles_insert_shortcode();" />
				<a class="button-secondary" href="#" onclick="tb_remove(); return false;"><?php _e( 'Cancel', 'whistles' ); ?></a>
			</p>
		<?php } ?>

		</div>
	</div>
<?php
}

/**
 * Helper function. Try to compose a human readable style by swapping
 * dash separated elements of the style key and uppercasing first letters.
 *
 * @since   0.1
 * @param   string  $key  Select option key, containing dashes
 * @return  string        Human readable, escaped style name for output
 */
function _whistles_styles_human_readable_style( $key ) {
	$key = explode( '-', $key, 2 );
	if( count( $key ) == 1 ) {
		return esc_html( ucfirst( $key[0] ) );
	} else {
		return esc_html( ucwords( str_replace( array( '-', '_' ), ' ', $key[1] ) . ' ' . $key[0] ) );
	}
	return __( 'Strange style name :-)', 'whistles-styles' );
}

?>
