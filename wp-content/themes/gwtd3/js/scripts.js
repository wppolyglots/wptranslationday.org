(function( $ ) {
	$( document ).ready( function( $ ) {

		$( '#mobile-menu-trigger' ).click( function() {
			toggleNav();
		});

		function toggleNav() {
			if ( $( '#site-wrapper' ).hasClass( 'show-nav' ) ) {
				$( '#site-wrapper' ).removeClass( 'show-nav' );
				$( '#mobile-menu-trigger .dashicons' ).removeClass( 'dashicons-no' );
				$( '#mobile-menu-trigger .dashicons' ).addClass( 'dashicons-menu' );
			} else {
				$( '#site-wrapper' ).addClass( 'show-nav' );
				$( '#mobile-menu-trigger .dashicons' ).removeClass( 'dashicons-menu' );
				$( '#mobile-menu-trigger .dashicons' ).addClass( 'dashicons-no' );
			}
		}

		$( window ).scroll( function() {
			if ( $( this ).scrollTop() < 400 ) {
				$( '#to-top' ).fadeOut();
			} else {
				$( '#to-top' ).fadeIn();
			}
		} );

		$( window ).resize(function() {
			if ( $( '#site-wrapper' ).hasClass( 'show-nav' ) ) {
				$( '#site-wrapper' ).removeClass( 'show-nav' );
				$( '#mobile-menu-trigger .dashicons' ).removeClass( 'dashicons-no' );
				$( '#mobile-menu-trigger .dashicons' ).addClass( 'dashicons-menu' );
			}
		});

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
				// event.preventDefault();
				$( 'html,body' ).animate( {
					scrollTop: $( this.hash ).offset().top
				}, 500 );
				return false;
			}
		} );

		$(".theeventmenu").mouseenter(function(){
			clearTimeout($(document).data('timeoutId'));
			$(document).find(".section.sub-menus").fadeIn("fast");
		}).mouseleave(function(){
			var someElement = $(document),
				timeoutId = setTimeout(function(){
					someElement.find(".section.sub-menus").fadeOut("fast");
				}, 300);
			someElement.data('timeoutId', timeoutId);
		});

		$(".section.sub-menus").mouseenter(function(){
			clearTimeout($(document).data('timeoutId'));
			$(document).find(".section.sub-menus").fadeIn("fast");
		}).mouseleave(function(){
			var someElement = $(document),
				timeoutId = setTimeout(function(){
					someElement.find(".section.sub-menus").fadeOut("fast");
				}, 300);
			someElement.data('timeoutId', timeoutId);
		});

		$(".getinvolvedmenu").mouseenter(function(){
			clearTimeout($(document).data('time2outId'));
			$(document).find(".section.getin-sub-menus").fadeIn("fast");
		}).mouseleave(function(){
			var someElement = $(document),
				time2outId = setTimeout(function(){
					someElement.find(".section.getin-sub-menus").fadeOut("fast");
				}, 300);
			someElement.data('time2outId', time2outId);
		});

		$(".section.getin-sub-menus").mouseenter(function(){
			clearTimeout($(document).data('time2outId'));
			$(document).find(".section.getin-sub-menus").fadeIn("fast");
		}).mouseleave(function(){
			var someElement = $(document),
				time2outId = setTimeout(function(){
					someElement.find(".section.getin-sub-menus").fadeOut("fast");
				}, 300);
			someElement.data('time2outId', time2outId);
		});

	} );
})( jQuery );