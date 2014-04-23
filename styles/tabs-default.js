jQuery( document ).ready(
	function() {

		/* Tabs. */
		jQuery( '.whistles-tabs .whistle-content' ).hide();
		jQuery( '.whistles-tabs .whistle-content:first-child' ).show();
		jQuery( '.whistles-tabs-nav :first-child' ).attr( 'aria-selected', 'true' );

		jQuery( '.whistles-tabs-nav li a' ).click(
			function( j ) {
				j.preventDefault();

				var href = jQuery( this ).attr( 'href' );

				jQuery( this ).parents( '.whistles-tabs' ).find( '.whistle-content' ).hide();

				jQuery( this ).parents( '.whistles-tabs' ).find( href ).show();

				jQuery( this ).parents( '.whistles-tabs' ).find( '.whistle-title' ).attr( 'aria-selected', 'false' );

				jQuery( this ).parent().attr( 'aria-selected', 'true' );
			}
		);
  }
);
