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
				<div class="six columns offset-by-three text-center">
					<img alt="404 - Page not found" src="<?php echo get_template_directory_uri(); ?>/img/404.png"/>
				</div>
			</div>
		</div>
	</div>

<?php
get_footer();
