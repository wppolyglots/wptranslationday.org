<?php
get_header(); ?>

	<div id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section">
		<div class="container">
			<div class="row">
				<div class="twelve columns">

					<?php
					while ( have_posts() ) : the_post();

						?>
						<header class="entry-header">
							<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						</header><!-- .entry-header -->
						<?php

						the_content();

					endwhile;
					?>
				</div>
			</div>
		</div>
	</div>

<?php
get_sidebar();
get_footer();
