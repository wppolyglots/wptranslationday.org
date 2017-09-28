<?php
/**
 * Template Name: Live data
 */

get_header(); 

the_post();

?>

	<div id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section section-localevents">
		<div class="container">
			<div class="row">
				<div class="twelve columns">

					<header class="page-header">
						<h2><?php the_title(); ?></h2>
						<?php the_content(); ?>
					</header><!-- .page-header -->

					<div class="entry-content">

						<h3>Percentage of translated sites: 
						<?php
						$test = gwtd3_get_translated_sites();
						$value = get_object_vars($test->data);
						echo $value[date('Y-m-d', 1506489775)]->percentage . ' %';
						?>
						</h3>
						
					</div>

				</div>
			</div>
		</div>
	</div>

<?php
get_sidebar();
get_footer();
