<?php

get_header();
$pic_size = 100;
?>
	<div id="primary" class="bg-color-pink text-color-pink--darker section">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<div id="primary" class="content-area">
						<main id="main" class="site-main">

							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();
								$s_username = get_post_meta( get_the_ID(), 's_username', true );
								$sp_id = get_the_ID();
								?>
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<header class="entry-header">
										<?php
										the_title( '<h1 class="entry-title">', '</h1>' );
										?>
									</header><!-- .entry-header -->
									<?php
									echo '<div class="gwtd-team-member">';
									echo '<div class="gwtd-team-member-header">';
									echo '<div class="gwtd-team-member-name text-color-blue--darker">';
									if ( ! empty( $s_username ) || '' != $s_username ) {
										echo '<a href="' . get_the_permalink() . '">';
										if ( has_post_thumbnail() ) {
											echo '<img class="alignleft"  style="width:100px;height:100px;" src="' . get_the_post_thumbnail_url() . '">';
										} else {
											echo '<img class="alignleft" src="https://wordpress.org/grav-redirect.php?user=' . $s_username . '&s=' . $pic_size . '">';
										}
										echo '</a>';
										echo '<span><i class="fa fa-wordpress"></i> <a href="https://profiles.wordpress.org/' . $s_username . '" target="_blank">' . $s_username . '</a></span>';
									}
									the_content();
									echo '</div>';
									echo '</div>';
									echo '</div>';
									echo '</div>';
									echo '<div class="gwtd-team-member text-color-pink--darker">';
									$talks = new WP_Query( array(
										'post_type' => 'gwtd_schedule',
										'meta_key' => 't_speaker',
										'meta_query' => array(
											array(
												'key' => 't_speaker',
												'value' => $sp_id,
												'compare' => 'LIKE',
											),
										),
										'orderby' => 'title',
										'order' => 'ASC',
										'posts_per_page' => -1,
									) );
									$talks = $talks->get_posts();
									foreach ( $talks as $talk ) {
										echo '<h4 class="text-color-pink--darker" style="margin-top: 3rem;margin-bottom: 0;">';
										echo $talk->post_title;
										echo '</h4>';
										echo '<div class="text-color-pink--darker">';
										echo $talk->post_content;
										echo '</div>';
									}
									?>
									<div class="viewfulllinks">
										<h4><a href="https://wptranslationday.org/wptd3-schedule/">Go to the schedule</a></h4>
										<h4><a href="https://wptranslationday.org/the-speakers/">Check out all the speakers</a></h4>
									</div>
								</article><!-- #post-<?php the_ID(); ?> -->
								<?php
							endwhile;
							?>

						</main><!-- #main -->
					</div><!-- #primary -->
				</div>
			</div>
		</div>
	</div>

<?php
get_footer();
