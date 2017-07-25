(function( $ ) {
	$( document ).ready( function( $ ) {

		$( '#mobile-menu-trigger' ).click( function() {
			toggleNav();
		});

		function toggleNav() {
			if ( $( '#site-wrapper' ).hasClass( 'show-nav' ) ) {
				$( '#site-wrapper' ).removeClass( 'show-nav' );
			} else {
				$( '#site-wrapper' ).addClass( 'show-nav' );
			}
		}


		$( window ).scroll( function() {
			if ( $( this ).scrollTop() < 400 ) {
				$( '#to-top' ).fadeOut();
			} else {
				$( '#to-top' ).fadeIn();
			}
		} );

		$( 'a[href*=\\#]' ).on( 'click', function( event ) {
			if ( $( this ).attr( 'id' ) === 'smoothup' ) {
				$( 'html, body' ).animate( {
					scrollTop: 0
				}, 500 );
				return false;
			} else {
				if ( $( this ).parent().parent().parent().parent().attr( 'id' ) === 'site-menu' ) {
					toggleNav();
				}
				event.preventDefault();
				$( 'html,body' ).animate( {
					scrollTop: $( this.hash ).offset().top
				}, 500 );
				return false;
			}
		} );

	} );
})( jQuery );