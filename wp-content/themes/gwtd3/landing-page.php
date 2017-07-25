<?php
	/**
	 * Template Name: Landing Page
	 */

	get_header();

?>
	<div class="section lp-what-it-is">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
						$page = get_page_by_title( 'What is GWTD3?' );
						$title = $page->post_title;
						echo '<h2 class="value-multiplier">' . $title . '</h2>';
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

	<div class="section lp-where-it-is">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
						$page = get_page_by_title( 'Where is GWTD3?' );
						$title = $page->post_title;
						echo '<h2 class="value-multiplier">' . $title . '</h2>';
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

	<div class="section lp-how-to-get-involved">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
						$page = get_page_by_title( 'Cool! How do I get involved?' );
						$title = $page->post_title;
						echo '<h2 class="value-multiplier">' . $title . '</h2>';
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


<?php
	get_footer();
