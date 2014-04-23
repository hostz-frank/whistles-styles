jQuery( document ).ready(
	function() {

		/* Tabs. */
		jQuery( '.tabs-red-content .whistle-content' ).hide();
		jQuery( '.tabs-red-content .whistle-content:first-child' ).show();
		jQuery( '.tabs-red-content .whistles-tabs-nav :first-child' ).attr( 'aria-selected', 'true' );

		jQuery( '.whistles-tabs-nav li a' ).click(
			function( j ) {
				j.preventDefault();

				var href = jQuery( this ).attr( 'href' );

				jQuery( this ).parents( '.tabs-red-content' ).find( '.whistle-content' ).hide();

				jQuery( this ).parents( '.tabs-red-content' ).find( href ).show();

				jQuery( this ).parents( '.tabs-red-content' ).find( '.whistle-title' ).attr( 'aria-selected', 'false' );

				jQuery( this ).parent().attr( 'aria-selected', 'true' );
			}
		);
  }
);
