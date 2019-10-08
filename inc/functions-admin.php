<?php
/*************************************************************
ADMIN MENU
**************************************************************/

function remove_admin_menus () {
	if (!current_user_can('manage_options')){ // Only proceed if user does not have admin role.
		//remove_menu_page('index.php'); 				// Dashboard
		//remove_menu_page('edit.php'); 				// Posts
		//remove_menu_page('upload.php'); 			// Media
		//remove_menu_page('link-manager.php'); 			// Links
		//remove_menu_page('edit.php?post_type=page'); 		// Pages
		//remove_menu_page('edit-comments.php'); 			// Comments
		//remove_menu_page('themes.php'); 			// Appearance
		//remove_menu_page('plugins.php'); 			// Plugins
		//remove_menu_page('users.php'); 				// Users
		//remove_menu_page('tools.php'); 				// Tools
		//remove_menu_page('options-general.php'); 		// Settings
 
		//remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' );	// Remove posts->tags submenu
		//remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' );	// Remove posts->categories submenu
		//remove_submenu_page( 'themes.php', 'themes.php' );
		//remove_submenu_page( 'themes.php', 'widgets.php' );
	}
}
add_action('admin_menu', 'remove_admin_menus');

/*************************************************************
WORDPRESS ADMIN BAR
***********************************************************/
function remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');          // Remove the WordPress logo
	$wp_admin_bar->remove_menu('search');  					// Remove Search Icon	
	
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );


/*************************************************************
DASHBOARD WIDGETS 
**************************************************************/

/** Disable default dashboard widgets
**************************************************************/
function disable_default_dashboard_widgets() {
	// remove_meta_box('dashboard_right_now', 'dashboard', 'core');    // Right Now Widget
	//remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Widget
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  // Incoming Links Widget
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');         // Plugins Widget

	remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  // Quick Press Widget
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');   // Recent Drafts Widget
	remove_meta_box('dashboard_primary', 'dashboard', 'core');         //
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');       //
}
add_action('admin_menu', 'disable_default_dashboard_widgets');

/*************************************************************
CUSTOM LOGIN PAGE
**************************************************************/

// calling your own login css so you can style it
function tr_login_css() {
	wp_enqueue_style( 'login_css', get_template_directory_uri() . '/css/login.css', false );
}

// changing the logo link from wordpress.org to your site
function tr_login_url() {  return home_url(); }

// changing the alt text on the logo to show your site name
function tr_login_title() { return get_option('blogname'); }

// calling it only on the login page
add_action( 'login_enqueue_scripts', 'tr_login_css', 10 );
add_filter('login_headerurl', 'tr_login_url');
add_filter('login_headertext', 'tr_login_title');


/*************************************************************
CUSTOMIZE ADMIN 
**************************************************************/

/** Load admin css
**************************************************************/
function load_custom_wp_admin_style() {
	wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/css/admin.css', false, '1.0.0' );
	wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

function cd_add_editor_styles() {
	add_editor_style( get_stylesheet_directory_uri() . '/css/style.css' );
}
add_action( 'init', 'cd_add_editor_styles' );

/**  Custom Backend Footer
**************************************************************/
function tr_custom_admin_footer( $text) {
	
	$text = sprintf( __( 'Developed by Kenny Scott (<a href="%s">Third Law Web Design</a>) Built using <a href="%s">Tabula Rasa</a>.', 'tabularasa' ),  'http://third-law.com', 'https://github.com/kennytheecat/');
	
	return $text;
}
add_filter('admin_footer_text', 'tr_custom_admin_footer');

/*************************************************************
HELP PAGE 
**************************************************************/
/** Creates Help page for users **/
function my_help_menu() {
	add_menu_page( 'Help', 'Help', 'read', 'help', 'help_options' );
}

function help_options() {
	include('theme-options-inc/help.php');
}
//add_action( 'admin_menu', 'my_help_menu' );
?>