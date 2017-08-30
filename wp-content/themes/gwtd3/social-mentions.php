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
	<style>
		#socment-hashtag-holder {
			word-wrap: break-word !important;
			margin: 10px !important;
		}

		#socment-hashtag-header,
		#socment-hashtag-footer {
			font-weight: 600 !important;
			font-family: 'Changa',sans-serif !important;
		}

		.social-grid-item,
		.social-grid-sizer {
			width: calc( 30% - 20px ) !important;
			height: auto !important;
		}

		@media (max-width: 1024px) {
			.social-grid-item,
			.social-grid-sizer {
				width: calc( 50% - 20px ) !important;
			}
		}

		@media (max-width: 425px) {
			.social-grid-item,
			.social-grid-sizer {
				width: calc( 100% - 20px ) !important;
			}
		}
	</style>
	<script>
		(function($){
			$(document).ready(function(){
				var $socialGrid = $('.social-grid').masonry({
					// set itemSelector so .grid-sizer is not used in layout
					itemSelector: '.social-grid-item'
				});

				$socialGrid.imagesLoaded().progress( function() {
					$socialGrid.masonry('layout');
				});
			})
		})(jQuery)
	</script>
<?php
get_sidebar();
get_footer();
