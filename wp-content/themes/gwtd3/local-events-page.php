<?php
/**
 * Template Name: Local Events
 */

get_header(); ?>

	<div id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<header class="page-header">
						<?php
							$page = get_post( 418 );
							echo '<h2>' . $page->post_title . '</h2>';
							echo $page->post_content;
							echo '<br><br>';
						?>
					</header><!-- .page-header -->
		
					<?php
					$query = new WP_Query( array('post_type' => 'local-event', 'posts_per_page' => -1, 'meta_key' => 'full_place', 'orderby' => 'meta_value', 'order' => 'ASC' ) );
					$previous_continent = 'Empty';
					$event_count = $query->post_count;
					while ( $query->have_posts() ) : $query->the_post();
						$continent = get_post_meta($post->ID, 'continent', true);
						if ( $previous_continent != $continent ) {
							if ( $previous_continent != 'Empty' ) {
								echo '<br>';
							}
							$previous_continent = $continent;
							echo '<h3>' . $continent . '</h3>';
						}
						$locales = get_post_meta( $post->ID, 'locale', true );
						if ( strpos($locales, ',') != false ) {
							// plural
							$locales = 'for locales <b>' . $locales . '</b>.';
						} else {
							$locales = 'for locale <b>' . $locales . '</b>.';
						}

						 ?>
						<div class="entry-content">
						<?php echo '<b>' . get_post_meta( $post->ID, 'country', true ) . ' / ' . get_post_meta( $post->ID, 'city', true ) . '</b> ' . $locales; 
						$utc_start = get_post_meta( $post->ID, 'utc_start', true );
						if ($utc_start) {
							echo ' Starting at ' . $utc_start . ' UTC.';
						}
						echo '<br>';
						$w_org = get_post_meta( $post->ID, 'organizer_w_org', true );
						echo 'Organizer: <a href="https://profiles.wordpress.org/' . $w_org . '">' . $w_org . '</a>';
						$url = get_post_meta( $post->ID, 'announcement_url' , true );
						if ($url) {
							echo ', <a href="' . $url . '">announcement url</a>';
						}
						echo '<br>';
						 ?>
						</div>
					<?php
					endwhile;
					echo '<br>' . $event_count . ' local events found.';
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</div>

<?php
get_sidebar();
get_footer();
