<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package gwtd3
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<link rel="apple-touch-icon" sizes="120x120" href="https://wptranslationday.org/wp-content/themes/gwtd3/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="https://wptranslationday.org/wp-content/themes/gwtd3/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="https://wptranslationday.org/wp-content/themes/gwtd3/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="https://wptranslationday.org/wp-content/themes/gwtd3/img/favicon/manifest.json">
	<link rel="mask-icon" href="https://wptranslationday.org/wp-content/themes/gwtd3/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<link rel="shortcut icon" href="https://wptranslationday.org/wp-content/themes/gwtd3/img/favicon/favicon.ico">
	<meta name="msapplication-config" content="https://wptranslationday.org/wp-content/themes/gwtd3/img/favicon/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-103431177-1', 'auto');
		ga('send', 'pageview');

	</script>
</head>

<body <?php body_class(); ?>>
<div id="site-wrapper">
	<div id="site-canvas">
		<div id="mobile-menu-trigger">
			<button type="submit" value="" aria-label="Open Mobile Menu">
				<span class="dashicons dashicons-menu"></span>
			</button>
		</div>
		<div id="site-menu">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'mobile-menu',
				'menu_id' => 'primary-menu-mobile',
			) );
			?>
		</div>
		<?php if ( is_front_page() ) { ?>
		<div class="section hero bg-color-pink--darker">
			<div class="container">
				<div class="row">
					<div class="twelve columns text-center land-head-img">
						<?php
						if ( get_theme_mod( 'gwtd3_landing_header_img' ) ) {
							echo '<div class="col-sm-12 text-center">';
							echo '<img src="' . esc_url( get_theme_mod( 'gwtd3_landing_header_img' ) ) . '" alt="';
							bloginfo( 'title' );
							echo '"/>';
							echo '</div>';
						}
						?>
					</div>
					<div class="twelve columns text-center land-head-mobile-img">
						<?php
						if ( get_theme_mod( 'gwtd3_landing_mobile_header_img' ) ) {
							echo '<div class="col-sm-12 text-center">';
							echo '<img src="' . esc_url( get_theme_mod( 'gwtd3_landing_mobile_header_img' ) ) . '" alt="';
							bloginfo( 'title' );
							echo '"/>';
							echo '</div>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php } else { ?>
		<div class="section hero header bg-color-pink--darker">
			<div class="container">
				<div class="row">
					<div class="twelve columns text-center int-head-img">
						<a href="<?php echo get_home_url(); ?>" title="WPTranslationDay 3">
							<?php
							if ( get_theme_mod( 'gwtd3_internal_header_img' ) ) {
								echo '<div class="col-sm-12 text-center">';
								echo '<img src="' . esc_url( get_theme_mod( 'gwtd3_internal_header_img' ) ) . '" alt="';
								bloginfo( 'title' );
								echo '"/>';
								echo '</div>';
							}
							?>
						</a>
					</div>
					<div class="twelve columns text-center int-head-mobile-img">
						<a href="<?php echo get_home_url(); ?>" title="WPTranslationDay 3">
							<?php
							if ( get_theme_mod( 'gwtd3_internal_mobile_header_img' ) ) {
								echo '<div class="col-sm-12 text-center">';
								echo '<img src="' . esc_url( get_theme_mod( 'gwtd3_internal_mobile_header_img' ) ) . '" alt="';
								bloginfo( 'title' );
								echo '"/>';
								echo '</div>';
							}
							?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="section primary-menus">
			<div class="container">
				<div class="row">
					<div class="twelve columns text-left">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'gwtd-menu',
							'menu_id' => 'gwtd-menu',
						) );
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="section sub-menus">
			<div class="container">
				<div class="row">
					<div class="twelve columns text-left">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'gwsub-menu',
							'menu_id' => 'gwsub-menu',
						) );
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="section getin-sub-menus">
			<div class="container">
				<div class="row">
					<div class="twelve columns text-left">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'ginvsub-menu',
							'menu_id' => 'ginv-menu',
						) );
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="section thepeople-sub-menus">
			<div class="container">
				<div class="row">
					<div class="twelve columns text-left">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'thepeoplesub-menu',
							'menu_id' => 'thepeople-menu',
						) );
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="section mediakit-sub-menus">
			<div class="container">
				<div class="row">
					<div class="twelve columns text-left">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'medkitsub-menu',
							'menu_id' => 'medkit-menu',
						) );
						?>
					</div>
				</div>
			</div>
		</div>
