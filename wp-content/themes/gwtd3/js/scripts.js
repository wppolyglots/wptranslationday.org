(function( $ ) {
	$( document ).ready( function( $ ) {

/*
		// Add dynamic counters to livedata
		$(window).load(function() {
			var keyFigures = new Array();
			$(".keyfigure_bloc_figure").each(function() {
				if ($(this).parent(".keyfigure_bloc").hasClass("keyfigure_bloc_type_number")) {
					' . $textPositionJS_Size . '
					keyFigures.push(0);
					' . $textPositionJS_Order . '
					var counterFinalValue = $(this).text();
					$(this).attr("data-value", counterFinalValue);
					$(this).css("width", $(this).width()+"px");
				}
			});
			
			$(window).scroll(function() {
				var i = 0;
				$(".keyfigure_bloc_figure").each(function() {
					var oTop = jQuery(this).offset().top - window.innerHeight;
					if (keyFigures[i] == 0 && jQuery(window).scrollTop() > oTop) {
						var counter = jQuery(this);
						countTo = counter.children(this).attr("data-value");
						jQuery({
							countNum: 0
						}).animate({
							countNum: parseFloat(counter.text())
						}, {
							duration: ' . $optionFigureAnimationDuration . ',
							easing: "swing",
							step: function() {
								counter.text(Math.floor(this.countNum));
							},
							complete: function() {
								counter.text(this.countNum);
							}
						});
						keyFigures[i] = 1;
					}
					i++;
				});
			});
		});
*/		

		$('input.mailpoet_text').attr("placeholder", "enter your e-mail");

		// main function to show/hide the mobile menu

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

		$( '#mobile-menu-trigger' ).click( function() {
			toggleNav();
		});

		// on window scroll show and hide the back to top button

		$( window ).scroll( function() {
			if ( $( this ).scrollTop() < 400 ) {
				$( '#to-top' ).fadeOut();
			} else {
				$( '#to-top' ).fadeIn();
			}
		} );

		// Checks if there's an # in the link
		// and if not #none
		// scroll to the given anchor
		// if the mobile menu is on then hide it
		// if the id of the click is #smoothup it scrolls to top


		$( 'a[href*=\\#][href!="#none"]' ).on( 'click', function( event ) {
			if ( $( this ).attr( 'id' ) === 'smoothup' ) {
				$( 'html, body' ).animate( {
					scrollTop: 0
				}, 500 );
				return false;
			} else {
				if ( $('#site-wrapper').hasClass('show-nav') ) {
					toggleNav();
				}
				$( 'html,body' ).animate( {
					scrollTop: $( this.hash ).offset().top
				}, 500 );
				return false;
			}
		} );

		// Hide Mobile Menu on Click Outside

		$(document).mouseup(function(event)
		{
			var target = $( event.target );
			if ( !target.is('span.dashicons-no') && $( '#site-wrapper').hasClass( 'show-nav' ) ) {
				toggleNav();
			}
		});

		// Custom Menus -> Sub Menus

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

		$(".thepeoplemenu").mouseenter(function(){
			clearTimeout($(document).data('time3outId'));
			$(document).find(".section.thepeople-sub-menus").fadeIn("fast");
		}).mouseleave(function(){
			var someElement = $(document),
				time3outId = setTimeout(function(){
					someElement.find(".section.thepeople-sub-menus").fadeOut("fast");
				}, 300);
			someElement.data('time3outId', time3outId);
		});

		$(".section.thepeople-sub-menus").mouseenter(function(){
			clearTimeout($(document).data('time3outId'));
			$(document).find(".section.thepeople-sub-menus").fadeIn("fast");
		}).mouseleave(function(){
			var someElement = $(document),
				time3outId = setTimeout(function(){
					someElement.find(".section.thepeople-sub-menus").fadeOut("fast");
				}, 300);
			someElement.data('time3outId', time3outId);
		});

		$(".themediakitmenu").mouseenter(function(){
			clearTimeout($(document).data('time4outId'));
			$(document).find(".section.mediakit-sub-menus").fadeIn("fast");
		}).mouseleave(function(){
			var someElement = $(document),
				time4outId = setTimeout(function(){
					someElement.find(".section.mediakit-sub-menus").fadeOut("fast");
				}, 300);
			someElement.data('time4outId', time4outId);
		});

		$(".section.mediakit-sub-menus").mouseenter(function(){
			clearTimeout($(document).data('time4outId'));
			$(document).find(".section.mediakit-sub-menus").fadeIn("fast");
		}).mouseleave(function(){
			var someElement = $(document),
				time4outId = setTimeout(function(){
					someElement.find(".section.mediakit-sub-menus").fadeOut("fast");
				}, 300);
			someElement.data('time4outId', time4outId);
		});

	} );
})( jQuery );