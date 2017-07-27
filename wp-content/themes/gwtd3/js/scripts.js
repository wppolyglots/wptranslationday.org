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
				event.preventDefault();
				$( 'html,body' ).animate( {
					scrollTop: $( this.hash ).offset().top
				}, 500 );
				return false;
			}
		} );

	} );
})( jQuery );

//////////////////////
/* Countdown timer */
///////////////////

// Set the date we're counting down to
var countDownDate = new Date("Sep 30, 2017 00:00:00").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

	// Get todays date and time
	var now = new Date().getTime();

	// Find the distance between now an the count down date
	var distance = countDownDate - now;

	// Time calculations for days, hours, minutes and seconds
	var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	// Display the result in the element with id="demo"
	document.getElementById("countdown").innerHTML = "<div>" + days + "<span>d<span>ays</span></span></div><div>" + hours + "<span>h<span>ours</span></span></div><div>" + minutes + "<span>m<span>inutes</span></span></div><div>" + seconds + "<span>s<span>econds</span></span></div>";

	// If the count down is finished, write some text
	if (distance < 0) {
		clearInterval(x);
		document.getElementById("countdown").innerHTML = "It's time for GWTD #3!";
	}
}, 1000);