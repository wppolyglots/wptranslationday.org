<?php
/**
 * Template Name: The Team
 */

get_header(); ?>

<div id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section">
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<?php
				while ( have_posts() ) : the_post();

					?>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->
					<?php

					the_content();

				endwhile;
				?>
				<div class="entry-content">
				<?php
				$pic_size = 100;
				$twitter_icon = 'https://wptranslationday.org/wp-content/uploads/2017/07/icon-twitter.png';
				$wp_icon = 'https://wptranslationday.org/wp-content/uploads/2017/07/icon-wordpress.png';
				$slack_icon = 'https://wptranslationday.org/wp-content/uploads/2017/07/icon-slack.png';

				$query = new WP_Query(
					array(
						'post_type' => 'the-team',
				        'posts_per_page' => -1,
						'meta_key' => 'tt_order',
						'orderby' => 'meta_value',
						'order' => 'ASC',
					)
				);
				while ( $query->have_posts() ) :
					$query->the_post();
					$twitter_account = get_post_meta( $post->ID, 'tt_twitter', true );
					$w_org_account = get_post_meta( $post->ID, 'tt_w_org', true );
					$slack_account = get_post_meta( $post->ID, 'tt_slack', true );
					$website = get_post_meta( $post->ID, 'tt_website', true );

					echo '<div class="gwtd-team-member">';
					echo '<div class="gwtd-team-member-header">';
					echo '<div class="gwtd-team-member-image">';
					if ( has_post_thumbnail() ) {
						echo '<img src="' . get_the_post_thumbnail_url() . '">';
					} else {
						echo '<img src="https://wordpress.org/grav-redirect.php?user=' . $w_org_account . '&s=' . $pic_size . '">';
					}
					echo '</div>';
					echo '<div class="gwtd-team-member-name">';
					echo '<h4>' . get_post_meta( $post->ID, 'tt_name', true ) . '</h4>';
					echo get_post_meta( $post->ID, 'tt_title', true );
					echo '</br>';
					if ( ! empty( $twitter_account ) ) {
						echo '<span><i class="fa fa-twitter"></i> <a href="https://twitter.com/' . $twitter_account . '">@' . $twitter_account . '</a></span> ';
					}
					if ( ! empty( $w_org_account ) ) {
						echo '<span><i class="fa fa-wordpress"></i> <a href="https://profiles.wordpress.org/' . $w_org_account . '">' . $w_org_account . '</a></span> ';
					}
					if ( ! empty( $slack_account ) ) {
						echo '<span><i class="fa fa-slack"></i> <a href="https://wordpress.slack.com/team/' . $slack_account . '">' . $slack_account . '</a></span> ';
					}
					if ( ! empty( $website ) ) {
						echo '<span><i class="fa fa-globe"></i> <a href="' . $website . '">' . $website . '</a></span>';
					}
					echo '</div>';
					echo '</div>';
					echo '<div class="gwtd-team-member-bio">';
					echo get_post_meta( $post->ID, 'tt_bio', true );
					echo '</div>';
					echo '</div>';

				endwhile;
				wp_reset_postdata();
				?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
