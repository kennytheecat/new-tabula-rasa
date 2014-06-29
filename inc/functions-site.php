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


//require_once('inc/custom-post-type.php'); 
/*------------------------------------------------------------------
CUSTOM POST TYPES
	- Construction Post Type
	- Status Taxonomy for Construction Type
	- Documents Taxonomy
	- Status Metabox
	- Documents Shortcode
Posts 2 Posts
	- my_connection_types() 
		// Associates construction posts to construction post types
------------------------------------------------------------------*/

//require_once('inc/metabox/metabox-functions.php'); 


//require_once('inc/theme-options.php');
/** Creates "Theme Options" page under Appearance Tab

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



/** tr_site_specific_support() **/
function tr_site_specific_support() {
	// This removes the annoying […] to a Read More link
	function tr_excerpt_more($more) {
		global $post;
		// edit here if you like
		return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __('Read', 'tabula_rasa') . get_the_title($post->ID).'">'. __('Read more &raquo;', 'tabula_rasa') .'</a>';
	}	
	add_filter('excerpt_more', 'tr_excerpt_more');	
}	
add_action('after_setup_theme','tr_site_specific_support', 16);

/** tr_register_site_specific_sidebars() **/
function tr_register_site_specific_sidebars() {
	/*register_sidebar( array(
		'name' => __( 'Main Sidebar', 'tabula_rasa' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'tabula_rasa' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. 

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php
	*/	
}
add_action( 'widgets_init', 'tr_register_site_specific_sidebars' );

/** tr_entry_meta()
**************************************************************/
if ( ! function_exists( 'tr_entry_meta' ) ) :
/** Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
Create your own tr_entry_meta() to override in a child theme. **/
function tr_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'tabula-rasa' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'tabula-rasa' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'tabula-rasa' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'tabula-rasa' );
	} elseif ( $categories_list ) {
		//$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'tabula-rasa' );
		$utility_text = __( 'This entry was posted on %3$s.', 'tabula-rasa' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'tabula-rasa' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/*************************************************************
COMMENT LAYOUT 
**************************************************************/

/*------------------------------------------------------------------
Template for comments and pingbacks.

To override this walker in a child theme without modifying the comments template
simply create your own tr_comment(), and that function will be used instead.

Used as a callback by wp_list_comments() for displaying the comments.
------------------------------------------------------------------*/

if ( ! function_exists( 'tr_comment' ) ) :

/** tr_comment()
**************************************************************/
function tr_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'tabula_rasa' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'tabula_rasa' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					/*
						this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
						echo get_avatar($comment,$size='32',$default='<path_to_url>' );
					*/
					?>
					<!-- custom gravatar call -->
					<?php
						// create variable
						$bgauthemail = get_comment_author_email();
					?>
					<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/images/nothing.gif" />
					<!-- end custom gravatar call -->
					<?php printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'tabula_rasa' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'tabula_rasa' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'tabula_rasa' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'tabula_rasa' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'tabula_rasa' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

/*************************************************************
MISC
**************************************************************/

/** remove_default_post_formats()
**************************************************************/
function remove_default_post_formats() {
    remove_theme_support( 'post-formats' );
}
add_action( 'init', 'remove_default_post_formats'); 

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

/** Meta Boxes
**************************************************************/
/** This function is needed by inc/metabox **/
function be_initialize_cmb_meta_boxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( 'metabox/init.php' );
	}
}
//add_action( 'init', 'be_initialize_cmb_meta_boxes', 9999 );
?>