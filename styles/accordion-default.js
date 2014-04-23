jQuery( document ).ready(
	function() {
		/* Accordion. */
		jQuery( '.whistles-accordion .whistle-content' ).hide();
		jQuery( '.whistles-accordion .whistle-content:first-of-type' ).show();
		jQuery( '.whistles-accordion .whistle-title:first-of-type' ).attr( 'aria-selected', 'true' );
		jQuery( '.whistles-accordion .whistle-title' ).click(
			function() {
				jQuery( this ).parents( '.whistles-accordion' ).find( '.whistle-content' ).not( this ).slideUp( 
					'slow',
					function() {
						if ( !jQuery( this ).is( ':visible' ) ) {
							jQuery( this ).prev().attr( 'aria-selected', 'false' );
						}
					}
				);
				jQuery( this ).next( '.whistle-content:hidden' ).slideDown(
					'slow',
					function() {
						jQuery( this ).parents( '.whistles-accordion' ).find( '.whistle-content' ).not( this ).slideUp( 'slow' );

						if ( !jQuery( this ).is( ':visible' ) ) {
							jQuery( this ).prev().attr( 'aria-selected', 'false' );
						}
					}
				);
				jQuery( this ).attr( 'aria-selected', 'true' );
			}
		);
	}
);
