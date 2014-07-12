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

/** Set content width **/
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

/**************************************************************
INCLUDES
**************************************************************/
//require_once('inc/custom-post-type.php'); 

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

// This removes the annoying […] to a Read More link
function tr_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __('Read', 'tabula_rasa') . get_the_title($post->ID).'">'. __('Read more &raquo;', 'tabula_rasa') .'</a>';
}	
add_filter('excerpt_more', 'tr_excerpt_more');

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

/** Meta Boxes
**************************************************************/
/** This function is needed by inc/metabox **/
function be_initialize_cmb_meta_boxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( 'metabox/init.php' );
	}
}
//add_action( 'init', 'be_initialize_cmb_meta_boxes', 9999 );
//require_once('inc/metabox/metabox-functions.php'); 

function tr_scripts_and_styles_options() { 
	if (!is_admin()) {}
  wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=PT+Serif|Open+Sans:400,700|Open+Sans+Condensed:700' );
  wp_enqueue_style( 'font-awesome',  'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
}
add_action( 'wp_enqueue_scripts', 'tr_scripts_and_styles_options' );

/* FROM _S
********************************************************************************/
/**
 * Adds custom classes to the array of body classes.
 */
function new_tabula_rasa_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	return $classes;
}
add_filter( 'body_class', 'new_tabula_rasa_body_classes' );

/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package new-tabula-rasa
 */

if ( ! function_exists( 'new_tabula_rasa_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function new_tabula_rasa_paging_nav() {
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
		'prev_text' => __( '? Previous', 'my-simone' ),
		'next_text' => __( 'Next ?', 'my-simone' ),
        'type'      => 'list',
	) );

	if ( $links ) :

	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'my-simone' ); ?></h1>
			<?php echo $links; ?>
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'new_tabula_rasa_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function new_tabula_rasa_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'new-tabula-rasa' ); ?></h1>
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

if ( ! function_exists( 'new_tabula_rasa_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function new_tabula_rasa_posted_on() {
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
		_x( 'Posted on %s', 'post date', 'new-tabula-rasa' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'by %s', 'post author', 'new-tabula-rasa' ),
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
function new_tabula_rasa_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'new_tabula_rasa_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'new_tabula_rasa_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so new_tabula_rasa_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so new_tabula_rasa_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in new_tabula_rasa_categorized_blog.
 */
function new_tabula_rasa_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'new_tabula_rasa_categories' );
}
add_action( 'edit_category', 'new_tabula_rasa_category_transient_flusher' );
add_action( 'save_post',     'new_tabula_rasa_category_transient_flusher' );
?>