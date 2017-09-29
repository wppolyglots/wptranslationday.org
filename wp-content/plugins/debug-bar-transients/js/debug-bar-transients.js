( function( $ ) {
	$( document ).ready( function() {
		// Expand the pre
		$( '#debug-menu-target-DS_Debug_Bar_Transients pre' ).click( function() {
			$(this).toggleClass( 'open' );
		});

		// Switch between serialized and unserialized data
		$( '#debug-menu-target-DS_Debug_Bar_Transients .switch-value a' ).click( function( e ) {
			$( this ).parents( 'td' ).next( 'td' ).toggleClass( 'un' );
			e.preventDefault();
		});

		$( '#debug-menu-target-DS_Debug_Bar_Transients a.delete' ).click( function( e ) {
			var _this = $( this );

			// Prepare the data
			var data = {
				'action'         : 'ds_delete_transient',
				'transient-type' : _this.data( 'transient-type' ),
				'transient-name' : _this.data( 'transient-name' ),
				'_ajax_nonce'    : $( '#debug-menu-target-DS_Debug_Bar_Transients #_ds-delete-transient-nonce' ).val()
			};

			// Make the AJAX call
			$.post(
				ajaxurl,
				data,
				function( r ) {
					if ( r == '1' ) {
						_this.parents( 'tr' ).css( 'backgroundColor', '#faa' ).fadeOut( 350, function() {
							$( this ).remove();
						} );
					}
				}
			);

			// Prevent the default action
			e.preventDefault();
		} );
	} );
} )( jQuery );
