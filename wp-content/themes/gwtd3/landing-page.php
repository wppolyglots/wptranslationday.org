<?php
	/**
	 * Template Name: Landing Page
	 */

	get_header();

?>
	<div id="what" class="section lp-what-it-is bg-color-pink .text-color-pink--darker">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
						$page = get_page_by_title( 'What is GWTD3?' );
						$title = $page->post_title;
						echo '<h2>' . $title . '</h2>';
					?>
				</div>
			</div>
			<div class="row">
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
						$page = get_page_by_title( 'Where is GWTD3?' );
						$title = $page->post_title;
						echo '<h2>' . $title . '</h2>';
					?>
				</div>
			</div>
			<div class="row">
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
					<h2><?php echo 'When is GWTD3?'; ?></h2>
				</div>
			</div>
			<div class="row">
				<div class="eleven columns offset-by-one">
					<p><strong>Global WordPress Translation Day</strong> will be from <strong>00.00 to 24.00 UTC on September 30, 2017.</strong> To help you get ready, here's the countdown to kick off:</p>
					<div id="countdown"></div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<div id="how" class="section lp-how-to-get-involved bg-color-blue--darker text-color-pink--light">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
						$page = get_page_by_title( 'Cool! How do I get involved?' );
						$title = $page->post_title;
						echo '<h2>' . $title . '</h2>';
					?>
				</div>
			</div>
			<div class="row">
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
</script>
<?php
	get_footer();
