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
						<h2>Stay tuned!</h2>
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

		<div class="section bottom sp bg-color-gray--darker">
			<div class="container">
				<div class="flex-row">
					<div class="item siteground">
						<h4 class="title text-color-gray--lighter">Proudly hosted by SiteGround</h4>
						<a href="https://siteground.com/" target="_blank" title="SiteGround">
							<img src="<?php echo get_template_directory_uri(); ?>/img/sp/siteground.png" alt="SiteGround logo" width="400" />
						</a>
					</div>
					<div class="item crowdcast">
						<h4 class="title text-color-gray--lighter">Live sessions powered by crowdcast</h4>
						<a href="https://www.crowdcast.io/" target="_blank" title="crowdcast">
							<img src="<?php echo get_template_directory_uri(); ?>/img/sp/crowdcast.png" alt="crowdcast logo" width="300" />
						</a>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<div id="to-top">
	<a href="#top" id="smoothup" title="Back to top"><img alt="Back to Top" src="<?php echo get_template_directory_uri(); ?>/img/backtotop.png"/></a>
</div>
<?php wp_footer(); ?>
</body>
</html>
