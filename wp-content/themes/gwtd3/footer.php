<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package gwtd3
 */

?>

<div class="section footer">
	<div class="container">
		<div class="row">
			<div class="six columns">
				<h2 class="value-multiplier">Stay tuned!</h2>
			</div>
			<div class="six columns text-right">
				<?php if ( is_active_sidebar( 'footer-social' ) ) : ?>
					<div id="footer-social">
						<?php dynamic_sidebar( 'footer-social' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
