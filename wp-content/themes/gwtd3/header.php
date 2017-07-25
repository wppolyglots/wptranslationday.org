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
</head>

<body <?php body_class(); ?>>
<?php if ( is_front_page() ) { ?>
	<div class="section hero">
		<div class="container">
			<div class="row">
				<div class="twelve columns text-center">
					<?php the_header_image_tag(); ?>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="section hero header">
		<div class="container">
			<div class="row">
				<div class="twelve columns text-center">
					<?php
					$post = get_page_by_title( 'Internal Banner' );
					the_post_thumbnail();
					?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<div class="section primary-menus">
	<div class="container">
		<div class="row">
			<!--	<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">-->
			<?php //esc_html_e( 'Primary Menu', 'gwtd3' ); ?><!--</button>-->
			<div class="five columns text-left">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'gwtd-menu',
					'menu_id' => 'gwtd-menu',
				) );
				?>
			</div>
			<div class="seven columns text-right">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary-menu',
					'menu_id' => 'primary-menu',
				) );
				?>
			</div>
		</div>
	</div>
</div>

