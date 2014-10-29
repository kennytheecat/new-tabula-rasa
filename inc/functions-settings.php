<?php
/*************************************************************
functions-base is for functions that rarely get changed
	-not stuff you switch on and off
functions-options is for functions that are commonly used
	-stuff you switch on and off
functions-site file should be the specific functions for the website

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
SITE SPECIFIC FUNCTIONS
	- set content width
	- tr_site_specific_support()
	- tr_excerpt_more()
		// This removes the annoying […] to a Read More link
	- tr_register_site_specific_sidebars()
	- tr_entry_meta()
COMMENT LAYOUT 
	- tr_comment()
MISC
	 - remove_default_post_formats()
	 - Google Analytics
**********************************************************/

/** Site Specific Functions
**************************************************************/

/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function tr_scripts_and_styles() {
	global $post;
	
	// FONTS
  wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=PT+Serif|Open+Sans:400,700|Open+Sans+Condensed:700' );
  wp_enqueue_style( 'font-awesome',  'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');		
  
	if (!is_admin()) {

    // register main stylesheet
		wp_enqueue_style( 'tabula-rasa-style', get_stylesheet_directory_uri() . '/css/style.css' );
		
    // ie-only style sheet
		global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
		$wp_styles->add_data( 'tabula_rasa-ie-only', 'conditional', 'lte IE 9' ); // add conditional wrapper around ie stylesheet		
    wp_enqueue_style( 'tabula_rasa-ie-only', get_stylesheet_directory_uri() . '/css/ie.css', array(), '' );

    // comment reply script for threaded comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		//adding scripts file in the footer
		wp_enqueue_script( 'tabula_rasa-js', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'jquery' ), '', true );
			
		//wp_enqueue_script( 'tabula-rasa-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

		wp_enqueue_script( 'tabula-rasa-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

		wp_enqueue_script( 'superfish', get_template_directory_uri() . '/js/superfish.min.js', array( 'jquery' ), '20140703', true );
		wp_enqueue_script( 'superfish-settings', get_template_directory_uri() . '/js/superfish-settings.js', array('superfish'), '20140703', true );
		
		wp_enqueue_script( 'prefix-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '1.0.0', true );

		wp_enqueue_script( 'mmenu-js', get_template_directory_uri() . '/js/jquery.mmenu.min.js', array( 'jquery' ), '20140703', true );
		wp_enqueue_script( 'mmenu-settings', get_template_directory_uri() . '/js/mmenu-settings.js', array('mmenu-js'), '20140703', true );
		wp_enqueue_style( 'mmenu-css', get_template_directory_uri() . '/css/jquery.mmenu.css' );
     
		wp_enqueue_script( 'hide-search', get_template_directory_uri() . '/js/hide-search.js', array( 'jquery' ), '20140703', true );
		// modernizr (without media query polyfill)
		//wp_enqueue_script( 'tabula_rasa-modernizr', get_stylesheet_directory_uri() . '/js/modernizr.custom.min.js', array(), '2.5.3', false );
    wp_enqueue_script( 'tabula_rasa-js' );
  }
	//if( is_page('my-page') ) {}
  // if( is_single() )
  // if( is_home() )
  // if( 'cpt-name' == get_post_type() )
	// For shortcodes
	//if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'custom-shortcode') ) {}
	 
	// I recommend using a plugin to call jQuery using the google cdn. That way it stays cached and your site will load faster.
	wp_enqueue_script( 'jquery' );	
}
add_action( 'wp_enqueue_scripts', 'tr_scripts_and_styles' );

/** Set content width **/
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

/**************************************************************
INCLUDES
**************************************************************/
/** Custom Post Types
**************************************************************/
//require_once('custom-post-type.php'); 

/** Widgets
**************************************************************/
//require_once('functions-widgets.php'); 

/** Meta Boxes
**************************************************************/
//require_once('metabox/metabox-functions.php'); 

function post_formats() {
	// adding post format support
	//See http://codex.wordpress.org/Post_Formats
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			// 'audio',             // audio
			// 'chat',               // chat transcript
			// 'gallery',           // gallery of images
			'image',             // an image
			'link',              // quick link to other site
			'quote',             // a quick quote
			//'status',            // a Facebook like status update
			'video'             // video
		)
	);
}
add_action( 'after_setup_theme', 'post_formats' );

/************************************************
EXCERPTS
*************************************************/
// This removes the annoying […] to a Read More link
function tr_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __('Read', 'tabula_rasa') . get_the_title($post->ID).'">'. __('Read more &raquo;', 'tabula_rasa') .'</a>';
}	
add_filter('excerpt_more', 'tr_excerpt_more');

//Excerpt length
function tr_excerpt_length( $length ) {
	if ( is_archive() || is_page('recent-articles') ) {
		return 50;	
	} else {
		return 55;
	}
}
add_filter( 'excerpt_length', 'tr_excerpt_length' );

/*************************************************************
POST THUMBNAILS
**************************************************************/
//add_image_size( $name, $width, $height, $crop );

/*************************************************************
MISC
**************************************************************/
/*
 * Social media icon menu as per http://justintadlock.com/archives/2013/08/14/social-nav-menus-part-2
 */

function tr_social_menu() {
  if ( has_nav_menu( 'social' ) ) {
		wp_nav_menu(
			array(
				'theme_location'  => 'social',
				'container'       => 'div',
				'container_id'    => 'menu-social',
				'container_class' => 'menu-social',
				'menu_id'         => 'menu-social-items',
				'menu_class'      => 'menu-items',
				'depth'           => 1,
				'link_before'     => '<span class="screen-reader-text">',
				'link_after'      => '</span>',			
				'fallback_cb'     => '',
			)
		);
  }
}


/** Google Analytics
**************************************************************/
function google_analytics_tracking_code(){ ?>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-2432710-6', 'prescott-az.gov');
		ga('send', 'pageview');
	</script>
<?php }	
add_action('wp_head', 'google_analytics_tracking_code');

/** Theme Options Data
**************************************************************/
/*
This function is needed by inc/theme-options-inc
Helper function to return the theme option value. If no value has been saved, it returns $default.
Needed because options are saved as serialized strings.
------------------------------------------------------------------*/
function theme_options() {
	if ( !function_exists( 'of_get_option' ) ) {
		function of_get_option($name, $default = false) {
			$optionsframework_settings = get_option('optionsframework');
			
			// Gets the unique option id
			$option_name = $optionsframework_settings['id'];
			if ( get_option($option_name) ) {
				$options = get_option($option_name);
			}
			if ( isset($options[$name]) ) {
				return $options[$name];
			} else {
				return $default;
			}
		}
	}
}
add_action( 'after_setup_theme', 'theme_options' );
//require_once('inc/theme-options.php');


/* FROM _S
********************************************************************************/
/**
 * Adds custom classes to the array of body classes.
 */
function tr_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	return $classes;
}
add_filter( 'body_class', 'tr_body_classes' );

/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package tabula-rasa
 */

if ( ! function_exists( 'tr_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function tr_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $GLOBALS['wp_query']->max_num_pages,
		'current'  => $paged,
		'mid_size' => 2,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '? Previous', 'tabula-rasa' ),
		'next_text' => __( 'Next ?', 'tabula-rasa' ),
        'type'      => 'list',
	) );

	if ( $links ) :

	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'tabula-rasa' ); ?></h1>
			<?php echo $links; ?>
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'tr_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function tr_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'tabula-rasa' ); ?></h1>
		<div class="nav-links">
			<?php
			previous_post_link( '<div class="nav-previous"><div class="nav-indicator">' . _x( 'Previous Post:', 'Previous post', 'tabula-rasa' ) . '</div><div class="nav-title">%link</div></div>', '%title' );
			next_post_link( '<div class="nav-next"><div class="nav-indicator">' . _x( 'Next Post:', 'Next post', 'tabula-rasa' ) . '</div><div class="nav-title">%link</div></div>', '%title' );              
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'tr_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function tr_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( 'Posted on %s', 'post date', 'tabula-rasa' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'by %s', 'post author', 'tabula-rasa' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function tr_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'tr_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'tr_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so tr_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so tr_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in tr_categorized_blog.
 */
function tr_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'tr_categories' );
}
add_action( 'edit_category', 'tr_category_transient_flusher' );
add_action( 'save_post',     'tr_category_transient_flusher' );
?>