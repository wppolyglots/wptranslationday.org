<?php
/**
 * Template Name: Static Data
 */

get_header();
?>
	<div id="now" class="section current-talk static-data lp-now-it-is lp-static-stream-it-is bg-color-pink text-color-blue--dark">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<h2>Let's recap</h2>
				</div>
				<div class="bgholder livebgholderstatic"></div>
			</div>
			<div class="row">
				<div class="ten columns offset-by-two static-text">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</div>

	<div id="data" class="section live-data lp-live-data-it-is bg-color-blue text-color-blue--light">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<h2 style="margin:0;" class="text-color-blue--lighter">WPTranslationDay3: the numbers</h2>
				</div>
				<div class="bgholder streambgholder"></div>
			</div>
			<div class="row">
				<div class="eight columns offset-by-four">
<!--					<h5 style="margin:0;">* data is refreshed every hour</h5>-->
				</div>
			</div>

			<div class="jbsdata">

				<div class="row" style="margin-top:2rem;">
					<div class="eight columns minor offset-by-four">
						<h3 style="font-weight:100 !important;" class="text-color-blue--lighter">here are some statistics for the day</h3>
					</div>
				</div>
				<?php
				$sdatas = array(
					0 => array('71', 'Local events worldwide'),
					1 => array('29', 'Countries'),
					2 => array('1.300+', 'Local events RSVPs'),
					3 => array('534', 'Tweets with #WPTranslationDay'),
					4 => array('93.179', 'Translated strings'),
					5 => array('649', 'Logged in users on GlotPress'),
					6 => array('217', 'New Translators'),
					7 => array('19', 'New PTEs'),
					8 => array('8', 'New GTEs'),
					9 => array('60', 'Locales impacted'),
					10 => array('346', 'Language packs created'),
					11 => array('818', 'Total number of projects modified'),
				);

				foreach ( $sdatas as $sdata ) {
					echo '<div class="row"><div class="four columns major"><h1 class="livedata-counter" >';
					echo $sdata[0];
					echo '</h1></div><div class="eight columns minor borderleft"><h3 class="text-color-blue--light">';
					echo $sdata[1];
					echo '</h3></div></div>';
				}
				?>


			</div>
		</div>
	</div>

	<div style="display:none!important;" id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section section-localevents">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php
					$queryMap = new WP_Query(
						array(
							'post_type' => 'local-event',
							'posts_per_page' => -1,
							'meta_key' => 'full_place',
							'orderby' => 'meta_value',
							'order' => 'ASC'
						)
					);

					// Init JS array for map markers
					$map_datas = '<script>var markers = new Array();';

					if ($queryMap->have_posts()) {
						$event_count = $queryMap->post_count;
						while ($queryMap->have_posts()) {
							$queryMap->the_post();
							// Data storage for the map
							if (
								get_post_meta( get_the_ID(), 'country', true ) &&
								get_post_meta( get_the_ID(), 'city', true ) &&
								get_post_meta( get_the_ID(), 'latitude', true) &&
								get_post_meta( get_the_ID(), 'longitude', true)
							) {
								$url_label = '';
								$url = '';
								if ( get_post_meta( get_the_ID(), 'announcement_url' , true ) ) {
									$url_label = '<br /><small>click to visit website</small>';
									$url = get_post_meta( get_the_ID(), 'announcement_url' , true );
								} else {
									$url_label = '';
									$url = '';
								}
								if ( get_post_meta( $post->ID, 'utc_start', true ) ) {
									$utc_start = get_post_meta( $post->ID, 'utc_start', true );
								} else {
									$utc_start = '';
								}

								$map_datas .= '
									markers.push({
										"id": ' . json_encode( sanitize_title( get_post_meta( get_the_ID(), 'country', true ) . '-' . get_post_meta( get_the_ID(), 'city', true ) ) ) . ',
										"title": ' . json_encode(get_post_meta( get_the_ID(), 'country', true ) . ' / ' . get_post_meta( get_the_ID(), 'city', true ) . '<br /><small>Starting at ' . $utc_start . ' UTC.</small>' . $url_label) . ',
										"eventURL": ' . json_encode( $url ) . ',
										"selectable": true,
										"latitude": ' . get_post_meta( get_the_ID(), 'latitude', true) . ',
										"longitude": ' . get_post_meta( get_the_ID(), 'longitude', true) . ',
										"imageURL": "' . get_template_directory_uri() . '/img/marker.svg",
										"width" : "16",
										"height" : "24"
									});
								';
							}
						}
					}
					// Closing map's JS data var and then write them
					$map_datas .= '</script>';
					echo $map_datas;
					wp_reset_postdata();
					?>
					<h2>All global events at a glance</h2>
				</div>
			</div>
		</div>
		<div class="gwtd_map_wrapper">
			<div id="gwtd_map" class="gwtd_map"></div>
		</div>
	</div>
<?php
get_footer();
