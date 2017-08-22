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
										if ( is_singular() ) :
											the_title( '<h1 class="entry-title">', '</h1>' );
										else :
											the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
										endif;

										if ( 'post' === get_post_type() ) : ?>
											<div class="entry-meta">
												<?php gwtd3_posted_on(); ?>
											</div><!-- .entry-meta -->
											<?php
										endif; ?>
									</header><!-- .entry-header -->
									<div class="entry-content">
										<div class="row">
											<div class="six columns">
												<?php the_post_thumbnail( 'full' ); ?>
											</div>
											<div class="six columns">
												<?php the_excerpt(); ?>
											</div>
											<div class="twelve columns">
												<a href="<?php echo the_permalink(); ?>">Read more...</a>
											</div>
										</div>
									</div><!-- .entry-content -->
								</article><!-- #post-<?php the_ID(); ?> -->
								<?php
							endwhile;

							the_posts_navigation();

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
