<?php
/*************************************************************
functions-base is for functions that rarely get changed
	-not stuff you switch on and off
functions-site is for functions that are commonly used
	-stuff you switch on and off

At the bottom of the functions-site file should be the specific functions for the website
**************************************************************/

/*************************************************************
ALL CAPS CASE
**************************************************************/

/** Proper Case
**************************************************************/

/** Proper Case **/

/*
Comments Single Line
or Multiple Line
*/

/*************************************************************
>>>TABLE OF CONTENTS
**************************************************************/
/*
LAUNCH TABULA RASA
	- tr_launch()
WP_HEAD GOODNESS	
	- head cleanup (remove rss, uri links, junk css, ect)
	- remove WP version from RSS
	- remove WP version from scripts
	- remove injected CSS for recent comments widget
	- remove injected CSS from recent comments widget
	- remove injected CSS from gallery
SCRIPTS & ENQUEUEING		
	- modernizer
	- main stylesheet
	- IE only stylesheet
	- comment reply script for threaded comments
	- scripts.js
	- mobile menu script
THEME SUPPORT	
	- add_theme_support('post-thumbnails')
	- add_editor_style( get_template_directory_uri() . '/css/editor-style.css' )
	- add_theme_support( 'custom-background')
	- add_theme_support('automatic-feed-links')
	- add_theme_support( 'post-formats') 
	- add_theme_support( 'menus' )
	- register_nav_menus( 'The Main Menu', 'The Secondary Menu', 'Footer Links' )
MENUS & NAVIGATION	
	- tr_main_nav()
	- tr_sec_nav()
	- tr_footer_links()
	- tr_main_nav_fallback()
	- tr_sec_nav_fallback()
	- tr_footer_links_fallback()
	- tr_register_sidebars( 'Main Sidebar', 'Secondary Widget Area' )
	- removing <p> from around images
	- tr_content_nav( $html_id )
		// Displays navigation to next/previous pages when applicable.		
	
MISC
	- Custom Header
	- remove the p from around imgs 
	- tr_get_the_author_posts_link()
		// This is a modified the_author_posts_link() which just returns the link.
	- of_get_option
		// This function is needed by inc/theme-options-inc
	- Meta Boxes
		// This function is needed by inc/metabox
********************************************************************/		

/*************************************************************
LAUNCH TABULA RASA
**************************************************************/

/** tr_setup()
***************************************************************/
if ( ! function_exists( 'tr_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function tr_setup() {
	// launching operation cleanup
	add_action('init', 'tr_head_cleanup');
	// remove WP version from RSS
	add_filter('the_generator', 'tr_rss_version');
	// remove pesky injected css for recent comments widget
	add_filter( 'wp_head', 'tr_remove_wp_widget_recent_comments_style', 1 );
	// clean up comment styles in the head
	add_action('wp_head', 'tr_remove_recent_comments_style', 1);
	// clean up gallery output in wp
	add_filter('gallery_style', 'tr_gallery_style');

	// enqueue base scripts and styles
	add_action('wp_enqueue_scripts', 'tr_scripts_and_styles', 999);
	// launching this stuff after theme setup
	tr_theme_support();

	// cleaning up random code around images
	add_filter('the_content', 'tr_filter_ptags_on_images');

	/*--------------------------------
	The following is from _s theme, functions.php
	-----------------------------------------------*/
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'tabula_rasa_tabularasa', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		//Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'tabula_rasa_tabularasa' ),
		) );

		/* Switch default core markup for search form, comment form, 
		 * and comments to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'tabula_rasa_tabularasa_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );	
	/*--------------------------------
	The end of _s theme, functions.php
	-----------------------------------------------*/		
}
endif; // tr_setup
add_action( 'after_setup_theme', 'tr_setup' );

/*************************************************************
WP_HEAD GOODNESS
The default wordpress head is a mess. Let's clean it up by removing all the junk we don't need.
**************************************************************/

function tr_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
  // remove WP version from css
  add_filter( 'style_loader_src', 'tr_remove_wp_ver_css_js', 9999 );
  // remove Wp version from scripts
  add_filter( 'script_loader_src', 'tr_remove_wp_ver_css_js', 9999 );
} /* end tr head cleanup */

// remove WP version from scripts
function tr_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

// remove WP version from RSS
function tr_rss_version() { return ''; }

// remove injected CSS for recent comments widget
function tr_remove_wp_widget_recent_comments_style() {
   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
      remove_filter('wp_head', 'wp_widget_recent_comments_style' );
   }
}

// remove injected CSS from recent comments widget
function tr_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
  }
}

// remove injected CSS from gallery
function tr_gallery_style($css) {
  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function tr_theme_support() {
	//Enable support for Post Thumbnails on posts and pages.
	//@link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	add_theme_support( 'post-thumbnails' );
	
	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'social' => __( 'Social Menu', 'tabula-rasa'),
			'sec-nav' => __( 'The Secondary Menu', 'tabula-rasa' ),   // secondary nav in header
			'footer-links' => __( 'Footer Links', 'tabula-rasa' ) // secondary nav in footer
		)
	);
	
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style( get_template_directory_uri() . '/css/editor-style.css' );	
	
} /* end tr_theme_support() */

/*************************************************************
MISC
**************************************************************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function tr_filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// Add Rel External To External Links
//http://digitizor.com/2014/07/05/add-nofollow-external-wordpress/
function add_nofollow_content($content) {
	$content = preg_replace_callback(
	'/<a[^>]*href=["|\']([^"|\']*)["|\'][^>]*>([^<]*)<\/a>/i',
	
	function($m) {
		$site_link = get_bloginfo('url');
		if (strpos($m[1], $site_link) === false)
		return '<a href="'.$m[1].'" rel="external" target="_blank">'.$m[2].'</a>';
		else
		return '<a href="'.$m[1].'" target="_blank">'.$m[2].'</a>';
	},
	
	$content);
	
	return $content;
}
add_filter('the_content', 'add_nofollow_content');


/*-----------------------------------
FROM _s THEME
---------------------------------------*/
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function tabula_rasa_tabularasa_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'tabula_rasa_tabularasa' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'tabula_rasa_tabularasa' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'tabula_rasa_tabularasa_widgets_init' );

/*------------------------------------------
PULLING IN _S THEME RELATED FUNCTIONS
-------------------------------------------*/
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/_s/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/_s/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/_s/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/_s/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/_s/jetpack.php';
}
/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */

 // Likely can remove this 
function tabula_rasa_tabularasa_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'tabula_rasa_tabularasa_content_width', 640 );
}
//add_action( 'after_setup_theme', 'tabula_rasa_tabularasa_content_width', 0 );

// adds the default buffy icon, 
// unless overrrided by a new favicon
function add_default_favicon( $url ) {
    $site_icon_id = get_option( 'site_icon' );
    if (!$site_icon_id ) {
        $url = get_template_directory_uri() . '/favicon.ico';
    }
    return $url;
}
add_filter( 'get_site_icon_url', 'add_default_favicon', 1 );
?>