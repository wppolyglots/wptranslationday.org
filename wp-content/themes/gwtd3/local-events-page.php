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
					<?php
					$query = new WP_Query( array('post_type' => 'local-event', 'posts_per_page' => -1, 'meta_key' => 'full_place', 'orderby' => 'meta_value', 'order' => 'ASC' ) );
					$previous_continent = 'Empty';
					$event_count = $query->post_count;
					?>
					<header class="page-header">
						<h2><?php echo $event_count; ?> local events near you... and counting!</h2>
						<?php
						$page = get_post( 418 );
						echo $page->post_content;
						echo '<br><br>';
						?>
					</header><!-- .page-header -->
					<?php
					// Init JS array for map markers
					$map_datas = '<script>var markers = new Array();';
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
						<?php
						echo '<b>' . get_post_meta( $post->ID, 'country', true ) . ' / ' . get_post_meta( $post->ID, 'city', true ) . '</b>';
						$utc_start = get_post_meta( $post->ID, 'utc_start', true );
						if ($utc_start) {
							echo ' - ';
							echo '<i class="fa fa-clock-o" title="Starting at"></i> ' . $utc_start . ' UTC.';
						} else {
							echo '.';
						}
						echo '<br>';
						$w_org = get_post_meta( $post->ID, 'organizer_w_org', true );
						echo 'Organizer: <a href="https://profiles.wordpress.org/' . $w_org . '">' . $w_org . '</a>';
						$url = get_post_meta( $post->ID, 'announcement_url' , true );
						if ($url) {
							echo '</br>Event link: <a title="View the event" href="' . $url . '">View</a>';
						} else {
							$url = '';
						}
						echo '<br>';
													
						// Data storage for the map
						if ( 
							get_post_meta( $post->ID, 'country', true ) &&
							get_post_meta( $post->ID, 'city', true ) &&
							get_post_meta( $post->ID, 'latitude', true) && 
							get_post_meta( $post->ID, 'longitude', true)
						) {
							if ($url != '') { $url_label = '<br /><small>click to visit website</small>'; } else { $url_label = ''; }
							
							$map_datas .= '
								markers.push({
									"id": ' . json_encode( sanitize_title( get_post_meta( $post->ID, 'country', true ) . '-' . get_post_meta( $post->ID, 'city', true ) ) ) . ',
									"title": ' . json_encode(get_post_meta( $post->ID, 'country', true ) . ' / ' . get_post_meta( $post->ID, 'city', true ) . '<br /><small>Starting at ' . $utc_start . ' UTC.</small>' . $url_label) . ',
									"eventURL": ' . json_encode( $url ) . ',
									"selectable": true,
									"latitude": ' . get_post_meta( $post->ID, 'latitude', true) . ',
									"longitude": ' . get_post_meta( $post->ID, 'longitude', true) . ',
									"imageURL": "' . get_template_directory_uri() . '/img/marker.svg",
									"width" : "16",
									"height" : "24"
								});
							';
						}
						?>
						</div>
					<?php
					endwhile;
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
