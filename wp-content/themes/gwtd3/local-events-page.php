<?php
/**
 * Template Name: Local Events
 */

get_header(); ?>

	<div id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section section-localevents">
		<div class="gwtd_map_wrapper">
			<div id="gwtd_map" class="gwtd_map"></div>
		</div>
		<div class="container">
		<!-- Map Wrapper -->
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
					// Init JS array for map markers
					$map_datas = '<script>var markers = new Array();';
					$color = '#206480';
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
						} else {
							$url = '';
						}
						echo '<br>';
						
						if ($color == '#206480') : $color = '#471530'; else: $color = '#206480'; endif;
							
						// Data storage for the map
						if ( 
							get_post_meta( $post->ID, 'country', true ) &&
							get_post_meta( $post->ID, 'city', true ) &&
							get_post_meta( $post->ID, 'latitude', true) && 
							get_post_meta( $post->ID, 'longitude', true)
						) {
		
							$map_datas .= '
								markers.push({
									"id": ' . json_encode( sanitize_title( get_post_meta( $post->ID, 'country', true ) . '-' . get_post_meta( $post->ID, 'city', true ) ) ) . ',
									"title": ' . json_encode(get_post_meta( $post->ID, 'country', true ) . ' / ' . get_post_meta( $post->ID, 'city', true ) . '<br /><small>Starting at ' . $utc_start . ' UTC.</small><br /><small>click to visit website</small>') .',
									"eventURL": ' . json_encode( $url ) . ',
									"selectable": true,
									"latitude": ' . get_post_meta( $post->ID, 'latitude', true) . ',
									"longitude": ' . get_post_meta( $post->ID, 'longitude', true) . ',
									"svgPath": "M77.692,32.692C77.692,17.399,65.3,5,50,5C34.707,5,22.308,17.399,22.308,32.692c0,5.05,1.381,9.769,3.739,13.846h-0.035   L50,95l23.999-48.462h-0.038C76.332,42.461,77.692,37.743,77.692,32.692",
									"scale": 0.15,
									"color": "' . $color . '"
								});
							';
						}
						?>
						</div>
					<?php
					endwhile;
					echo '<br>' . $event_count . ' local events found.';
					wp_reset_postdata();
					// Closing map's JS data var and then write them
					$map_datas .= '</script>';
					echo $map_datas;
					?>
					
					
				</div>
			</div>
		</div>
	</div>

<?php
get_sidebar();
get_footer();
