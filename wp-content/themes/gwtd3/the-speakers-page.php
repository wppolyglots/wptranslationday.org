<?php
get_header();

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
					$sp_id = get_the_ID();
					if ( ! empty( $s_username ) || '' != $s_username ) {
					?>
				<div class="entry-content">
				<?php
					echo '<div class="gwtd-team-member">';
						echo '<div class="gwtd-team-member-header">';
							echo '<h4 class="text-color-pink--darker"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a> - ';
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
							$i = 0;
							$len = count($talks);
							foreach ( $talks as $talk ) {
//								echo '<a href="' . get_the_permalink( $talk->ID ) . '">';
								echo '<a href="https://wptranslationday.org/wptd3-schedule/">';
								echo $talk->post_title;
								echo '</a>';
								if ( $i != $len - 1 ) {
									echo ' | ';
								}
								$i++;
							}
							echo '</h4>';
							echo '<div class="gwtd-team-member-name text-color-blue--darker">';
							echo '<a href="' . get_the_permalink() . '">';
							if ( has_post_thumbnail() ) {
								echo '<img class="alignleft"  style="width:100px;height:100px;" src="' . get_the_post_thumbnail_url() . '">';
							} else {
								echo '<img class="alignleft" src="https://wordpress.org/grav-redirect.php?user=' . $s_username . '&s=' . $pic_size . '">';
							}
							echo '</a>';
							echo '<span><i class="fa fa-wordpress"></i> <a href="https://profiles.wordpress.org/' . $s_username . '">' . $s_username . '</a></span>';
							the_content();
							echo '</div>';
						echo '</div>';
					echo '</div>';
				?>
				</div>
					<?php
					}
				endwhile;
					?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
