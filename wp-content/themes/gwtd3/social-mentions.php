<?php
// Template Name: Social Mentions
get_header();
?>

	<div id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section">
		<div class="container">


					<?php
					while ( have_posts() ) :
						the_post();

						?>
					<div class="row">
						<div class="twelve columns">
							<header class="entry-header">
								<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
							</header><!-- .entry-header -->
						</div>
					</div>
					<div class="row social-grid">
						<div class="social-grid-sizer"></div>
						<?php

						the_content();

					endwhile;
					?>
					</div>
			</div>
		</div>
	</div>
<?php
get_footer();
