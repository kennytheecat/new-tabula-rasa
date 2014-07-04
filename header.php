<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package new-tabula-rasa
 */
?>
<!DOCTYPE html>
<!-- language_attributes section replaced with Bones -->
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<!-- Not sure what this does -->
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!-- Not sure if this is needed  -->
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'new-tabula-rasa' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="mobile-menu">
				<i class="fa fa-bars"></i>
				<a href="#menu-container" class="screen-reader-text"><?php _e( 'Menu', 'tabula-rasa' ); ?></a>
		</div>
		<div class="site-branding">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div>
		<div class="search-mobile">
			<i class="fa fa-search"></i>
			<a href="#search-container" class="screen-reader-text"><?php _e( 'Search', 'my-simone' ); ?></a>		
		</div>
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<!-- used to use tr_main_nav() from bones. switched back to _s. unneeded arguments -->
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'mmenu-toggle', 'menu_class' => 'nav-menu') ); ?>
			<div class="search-not-mobile">
			<i class="fa fa-search"></i>
			<a href="#search-container" class="screen-reader-text"><?php _e( 'Search', 'my-simone' ); ?></a>
			</div>				
			<?php //tr_social_menu(); ?>
		</nav><!-- #site-navigation -->
		<div id="search-container" class="search-box-wrapper">
			<div class="search-box">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
