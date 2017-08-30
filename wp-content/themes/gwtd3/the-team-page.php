<?php
/**
 * Template Name: The Team
 */

get_header(); ?>

	<div id="primary" class="bg-color-blue--neutral-light text-color-blue--darker section">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<header class="page-header">
						<?php
							$page = get_post( 480 );
							echo '<h2>' . $page->post_title . '</h2>';
							echo $page->post_content;
							echo '<br><br>';
						?>
					</header><!-- .page-header -->
		
					<?php
					$pic_size = 200;
					$twitter_icon = 'https://wptranslationday.org/wp-content/uploads/2017/07/icon-twitter.png';
					$wp_icon = 'https://wptranslationday.org/wp-content/uploads/2017/07/icon-wordpress.png';
					$slack_icon = 'https://wptranslationday.org/wp-content/uploads/2017/07/icon-slack.png';
					
					$query = new WP_Query( array('post_type' => 'the-team', 'posts_per_page' => -1, 'meta_key' => 'tt_order', 'orderby' => 'meta_value', 'order' => 'ASC' ) );
					echo '<div class="entry-content">';
					echo '<table id="the-team-table">';
					while ( $query->have_posts() ) : $query->the_post();
						echo '<tr>';
						$twitter_account = get_post_meta($post->ID, 'tt_twitter', true);
						$w_org_account = get_post_meta($post->ID, 'tt_w_org', true);
						$slack_account = get_post_meta($post->ID, 'tt_slack', true);
						echo '<td><img src="https://wordpress.org/grav-redirect.php?user=' . $w_org_account . '&s=' . $pic_size . '"></td>';
						echo '<td>';
						echo '<b>' . get_post_meta($post->ID, 'tt_name', true) . '</b>&nbsp;&nbsp;' .
							'<img src="' . $twitter_icon . '" width="20" height="20"> <a href="https://twitter.com/' . $twitter_account . '">@' . $twitter_account . '</a> | ' .
							'<img src="' . $wp_icon . '" width="20" height="20"> <a href="https://profiles.wordpress.org/' . $w_org_account . '">' . $w_org_account . '</a> | ' . 
							'<img src="' . $slack_icon . '" width="20" height="20"> <a href="https://wordpress.slack.com/team/' . $slack_account . '">' . $slack_account . '</a><br>';
						echo get_post_meta($post->ID, 'tt_title', true) . '<br>';
						echo get_post_meta($post->ID, 'tt_bio', true);
						echo '</td>';
						echo '</tr>';
					endwhile;
					echo '</table>';
					echo '</div>';
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</div>

<?php
get_sidebar();
get_footer();
