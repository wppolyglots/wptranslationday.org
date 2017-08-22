<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gwtd3
 */

get_header(); ?>
	<div id="primary" class="bg-color-blue--neutral-light text-color-gray--dark section">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<div id="primary" class="content-area">
						<main id="main" class="site-main">

							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();
								?>
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<header class="entry-header">
										<?php

										the_title( '<h1 class="entry-title">', '</h1>' );
										?>
										<div class="entry-meta">
												<?php gwtd3_posted_on(); ?>
										</div><!-- .entry-meta -->
									</header><!-- .entry-header -->
									<div class="entry-content">
										<div class="row">
											<div class="twelve columns">
												<?php the_content(); ?>
											</div>
										</div>
									</div><!-- .entry-content -->
								</article><!-- #post-<?php the_ID(); ?> -->
								<?php
							endwhile;
							?>

						</main><!-- #main -->
					</div><!-- #primary -->
				</div>
			</div>
		</div>
	</div>

<?php
get_sidebar();
get_footer();
