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

							// Data storage for the map
							if ( get_post_meta( $post->ID, 'latitude', true) && get_post_meta( $post->ID, 'longitude', true) ) {
								$map_datas .= '
								markers.push({
									"id": ' . json_encode( sanitize_title( get_post_meta( $post->ID, 'country', true ) . '-' . get_post_meta( $post->ID, 'city', true ) ) ) . ',
									"title": ' . json_encode(get_post_meta( $post->ID, 'country', true ) . ' / ' . get_post_meta( $post->ID, 'city', true ) . '<br /><small>Starting at ' . $utc_start . ' UTC.</small><br /><small>Click to visit website</small>') .',
									"eventURL": ' . json_encode('http://google.com') . ',
									"selectable": true,
									"latitude": ' . get_post_meta( $post->ID, 'latitude', true) . ',
									"longitude": ' . get_post_meta( $post->ID, 'longitude', true) . ',
									"svgPath": "M10 2q-1.63 0-3.010 0.805t-2.185 2.185-0.805 3.010q0 1.42 0.7 2.665t1.83 2.225q0.040 0.030 0.235 0.195t0.295 0.255 0.3 0.275 0.345 0.33 0.33 0.355 0.345 0.42q1.33 1.74 1.62 2.71 0.29-0.97 1.62-2.71 0.16-0.21 0.345-0.42t0.33-0.355 0.345-0.33 0.3-0.275 0.295-0.255 0.235-0.195q1.13-0.98 1.83-2.225t0.7-2.665q0-1.63-0.805-3.010t-2.185-2.185-3.010-0.805zM10 4.56q1.42 0 2.43 1.010t1.010 2.43-1.010 2.43-2.43 1.010-2.43-1.010-1.010-2.43 1.010-2.43 2.43-1.010z",
									"scale": 1,
									"color": "#471530"
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
		<!-- Map Wrapper -->
		<div class="gwtd_map_wrapper">
			<div id="gwtd_map" class="gwtd_map"></div>
		</div>
	</div>

<?php
get_footer();