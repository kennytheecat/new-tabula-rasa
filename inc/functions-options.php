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

// Implement the Custom Header feature
//require get_template_directory() . '/inc/custom-header.php';

//Custom template tags for this theme
require get_template_directory() . '/inc/template-tags.php';

// Custom functions that act independently of the theme templates
require get_template_directory() . '/inc/extras.php';

//Customizer additions
require get_template_directory() . '/inc/customizer.php';

//Load Jetpack compatibility file
require get_template_directory() . '/inc/jetpack.php';

function custom_background() {
	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'new_tabula_rasa_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
add_action( 'after_setup_theme', 'custom_background' );

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
  
	if (!is_admin()) {

  }
  wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=PT+Serif|Open+Sans:400,700|Open+Sans+Condensed:700' );
  wp_enqueue_style( 'font-awesome',  'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
}
add_action( 'wp_enqueue_scripts', 'tr_scripts_and_styles_options' );
?>