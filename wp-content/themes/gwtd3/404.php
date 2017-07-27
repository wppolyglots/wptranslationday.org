<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package gwtd3
 */

get_header(); ?>

	<div id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section content-area">
		<div class="container">
			<div class="row">
				<div class="twelve columns">

				<section class="error-404 not-found">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'gwtd3' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content" style="height: 500px;">

					</div><!-- .page-content -->
				</section><!-- .error-404 -->

				</div>
			</div>
		</div>
	</div><!-- #primary -->

<?php
get_footer();
