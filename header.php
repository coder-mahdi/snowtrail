<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SnowTrail
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'snowtrail' ); ?></a>

	<header id="masthead" class="site-header">
		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="hamburger-icon">
					<span></span>
					<span></span>
					<span></span>
				</span>
				<span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'snowtrail' ); ?></span>
			</button>
			<div class="menu-container">
				<ul id="primary-menu" class="menu nav-menu">
					<li class="menu-item">
						<a href="<?php echo esc_url( home_url( '/snow-report-output/' ) ); ?>"><?php esc_html_e( 'Home', 'snowtrail' ); ?></a>
					</li>
					<li class="menu-item">
						<a href="<?php echo esc_url( home_url( '/snow-report-form/' ) ); ?>"><?php esc_html_e( 'Snow Report Form', 'snowtrail' ); ?></a>
					</li>
					<li class="menu-item">
						<a href="<?php echo esc_url( home_url( '/snow-report-output/' ) ); ?>"><?php esc_html_e( 'Snow Report Output', 'snowtrail' ); ?></a>
					</li>
				</ul>
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
