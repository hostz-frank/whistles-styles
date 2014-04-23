jQuery( document ).ready(
	function() {
		/* Toggle. */
		jQuery( '.whistles-toggle.toggle-default .whistle-content' ).hide();
		jQuery( '.whistles-toggle.toggle-default .whistle-title' ).click(
			function() {
				jQuery( this ).attr( 'aria-selected', 'true' );
				jQuery( this ).next( '.whistle-content' ).slideToggle(
					'slow',
					function() {
						if ( !jQuery( this ).is( ':visible' ) ) {
							jQuery( this ).prev().attr( 'aria-selected', 'false' );
						}
					}
				);
			}
		);
	}
);
