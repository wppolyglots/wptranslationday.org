<?php
/**
 * Template Name: The Speakers
 */

get_header();
$speaker = new WP_Query( array(
	'post_type' => 'gwtd_speakers',
	'orderby' => 'title',
	'order' => 'ASC',
	'posts_per_page' => -1,
) );
$pic_size = 100;
?>

<div id="primary" class="bg-color-pink text-color-pink--darker section">
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<header class="entry-header">
					<h1>The Speakers</h1>
				</header><!-- .entry-header -->
				<?php
				while ( $speaker->have_posts() ) :
							$speaker->the_post();
					$s_username = get_post_meta( get_the_ID(), 's_username', true );
					?>
				<div class="entry-content">
				<?php
					echo '<div class="gwtd-team-member">';
						echo '<div class="gwtd-team-member-header">';
							echo '<h4 class="text-color-pink--darker">' . get_the_title() . '</h4>';
							echo '<div class="gwtd-team-member-name text-color-blue--darker">';
							if ( has_post_thumbnail() ) {
								echo '<img class="alignleft" src="' . get_the_post_thumbnail_url() . '">';
							} else {
								echo '<img class="alignleft" src="https://wordpress.org/grav-redirect.php?user=' . $s_username . '&s=' . $pic_size . '">';
							}
							echo '<span><i class="fa fa-wordpress"></i> <a href="https://profiles.wordpress.org/' . $s_username . '">' . $s_username . '</a></span>';
							the_content();
							echo '</div>';
						echo '</div>';
					echo '</div>';
				?>
				</div>
					<?php
				endwhile;
					?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
