<?php
/**
 * Template Name: Schedule
 */

get_header();

$talks = new WP_Query( array(
	'post_type' => 'gwtd_schedule',
	'order' => 'ASC',
	'posts_per_page' => -1,
	'meta_key' => 't_time',
	'orderby' => 'meta_value',
	'order' => 'ASC',
) );
$pic_size = 100;
?>
	<div id="now" class="section current-talk lp-now-it-is bg-color-pink text-color-pink--darker">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<h2>Look who's talking now!</h2>
				</div>
			</div>
			<div class="row">
				<div class="bgholder nowbgholder"></div>
				<div class="talk-holder">

				</div>
			</div>
		</div>
	</div>
	<div id="primary" class="bg-color-blue--neutral-light the-talk text-color-blue--darker section">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<header class="entry-header">
						<h1>The 24hr timeline!</h1>
						<h4 class="subtitle">If you missed a talk don't worry: after the event you will be able to see them on <a href="https://wordpress.tv/event/global-wordpress-translation-day-3/" target="_blank">WordPress.tv</a></h4>
					</header><!-- .entry-header -->
				</div>
			</div>
			<div class="talk-holder">
				<?php
				while ( $talks->have_posts() ) :
					$talks->the_post();
					$t_speaker = get_post_meta( get_the_ID(), 't_speaker', true );
					$s_name = get_the_title( $t_speaker );
					$s_username = get_post_meta( $t_speaker, 's_username', true );
					$t_time = get_post_meta( get_the_ID(), 't_time', true );
					$t_duration = get_post_meta( get_the_ID(), 't_duration', true );
					$t_type = get_post_meta( get_the_ID(), 't_type', true );
					$t_live = get_post_meta( get_the_ID(), 't_live', true );
					$t_audience = get_post_meta( get_the_ID(), 't_audience', true );
					$t_language = get_post_meta( get_the_ID(), 't_language', true );
					echo '<div class="row" data-when="now" data-time="2017-09-22 ' . $t_time . ':00">';
					echo '<div class="two columns the-time">';
					echo '<h1 class="utctime">' . $t_time . '</h1>';
					echo '<h6>IN YOUR LOCAL TIME</h6>';
					echo '<h1 class="localtime"></h1>';
					echo '</div>';
					echo '<div class="ten columns right-side">';
					echo '<h3 class="talk-title">' . $s_name . ' - ';
					the_title();
					echo '</h3>';
					if ( has_post_thumbnail() ) {
						echo '<img class="alignleft" src="' . get_the_post_thumbnail_url() . '">';
					} else {
						echo '<img class="alignleft" src="https://wordpress.org/grav-redirect.php?user=' . $s_username . '&s=' . $pic_size . '">';
					}
					echo wp_trim_words( get_the_content(), 38, '...' );
					echo '<h4 class="talk-info">';
					echo $t_live . ' | ' . $t_duration . ' minutes | ' . $t_language . ' | audience: ' . $t_audience;
					echo '</h4>';
					echo '</div>';
					echo '</div>';
				endwhile;
				?>
			</div>
		</div>
	</div>

	<script>
		//////////////////////
		/* Countdown timer */
		///////////////////
		(function( $ ) {
			$( 'document' ).ready( function() {
				$('.utctime').each(function(){

					var talkTimeUTC = $(this).parent().parent().attr('data-time');

					var timeLocal  = moment.utc( $(this).parent().parent().attr('data-time') ).toDate();

					var currTimeUTC = moment().utc().format('YYYY-MM-DD HH:mm:ss');

					// TODO: fix between for 'now'

					if ( currTimeUTC > talkTimeUTC ) {
						$(this).parent().parent().attr('data-when', 'past');
					} else {
						$(this).parent().parent().attr('data-when', 'future');
					}

					// insert the local time
					timeLocal = moment(timeLocal).format('HH:mm');
					$(this).parent().children('.localtime').html(timeLocal);
				});

				var currTalk = $('div[data-when=now]').clone();
				$('.current-talk .talk-holder').html(currTalk);
				$('.current-talk .the-time').remove();

				$('.current-talk .ten.columns').addClass('offset-by-two');
			})
		})( jQuery );
	</script>
<?php
get_footer();