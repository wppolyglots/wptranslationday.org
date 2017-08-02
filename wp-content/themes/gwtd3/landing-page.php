<?php
	/**
	 * Template Name: Landing Page
	 */

	get_header();

?>
	<div id="what" class="section lp-what-it-is bg-color-pink text-color-pink--darker">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
						$page = get_post( 5 );
						$title = $page->post_title;
						echo '<h2>' . $title . '</h2>';
					?>
				</div>
			</div>
			<div class="row">
				<div class="bgholder whatbgholder"></div>
				<div class="eleven columns offset-by-one">
					<?php echo $page->post_content; ?>
				</div>
			</div>
		</div>
	</div>

	<div id="where" class="section lp-where-it-is bg-color-blue text-color-blue--lighter">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
						$page = get_post( 8 );
						$title = $page->post_title;
						echo '<h2>' . $title . '</h2>';
					?>
				</div>
			</div>
			<div class="row">
				<div class="bgholder wherebgholder"></div>
				<div class="eleven columns offset-by-one">
					<?php echo $page->post_content; ?>
				</div>
			</div>
		</div>
	</div>
	<?php if ( is_front_page() && get_header_image() != null ) { ?>
	<div id="when" class="section lp-when-it-is bg-color-pink--dark text-color-pink--light">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<h2><?php echo 'When is WPTranslationDay 3?'; ?></h2>
				</div>
			</div>
			<div class="row">
				<div class="bgholder whenbgholder"></div>
				<div class="eleven columns offset-by-one">
					<p><strong>WPTranslationDay 3</strong> will be from <strong>00:00 to 23:59 UTC on September 30, 2017.</strong> To help you get ready, here's the countdown to kick off:</p>
				</div>
			</div>
			<div class="row">
				<div id="countdown"></div>
			</div>
		</div>
	</div>
	<?php } ?>
	<div id="how" class="section lp-how-to-get-involved bg-color-blue--darker text-color-pink--light">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
						$page = get_post( 10 );
						$title = $page->post_title;
						echo '<h2>' . $title . '</h2>';
					?>
				</div>
			</div>
			<div class="row">
				<div class="bgholder howbgholder"></div>
				<div class="eleven columns offset-by-one">
					<?php echo $page->post_content; ?>
				</div>
			</div>
		</div>
	</div>

<script>
	//////////////////////
	/* Countdown timer */
	///////////////////
	(function( $ ) {
		$( 'document' ).ready( function() {
			var gwtdTime = moment.tz("2017-09-30 00:00:00", "Etc/UTC");
			$('#countdown').countdown(gwtdTime.toDate(), {elapse: true})
				.on('update.countdown', function(event) {
					var $this = $(this);
					if (event.elapsed) {
						$this.html(event.strftime("<h1>It's time for GWTD #3!</h1>"));
					} else {
						$(this).html( "<div>" +
							event.strftime('%D') +
							"<span>d<span>ays</span></span></div><div>" +
							event.strftime('%H') +
							"<span>h<span>ours</span></span></div><div>" +
							event.strftime('%M') +
							"<span>m<span>inutes</span></span></div><div>" +
							event.strftime('%S') +
							"<span>s<span>econds</span></span></div>"
						);
					}
				});
		})
	})( jQuery );
</script>
<?php
	get_footer();
