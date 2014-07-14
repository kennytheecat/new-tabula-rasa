<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_sample_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cmb_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['church-fathers-meta'] = array(
		'id'         => 'church-fathers-meta',
		'title'      => __( 'Church Father Quotes ', 'tabula-rasa' ),
		'pages'      => array( 'post', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name' => __( 'Quote', 'tabula-rasa' ),
				'desc' => __( '', 'tabula-rasa' ),
				'id'   => $prefix . 'church_fathers_quote',
				'type' => 'textarea',
			),
		),
	);

		$meta_boxes['featured-video-meta'] = array(
		'id'         => 'featured-video-meta',
		'title'      => __( 'Featured Video ', 'tabula-rasa' ),
		'pages'      => array( 'post', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name' => __( 'Title', 'tabula-rasa' ),
				'desc' => __( '', 'tabula-rasa' ),
				'id'   => $prefix . 'video_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __( 'Link', 'tabula-rasa' ),
				'desc' => __( '', 'tabula-rasa' ),
				'id'   => $prefix . 'video_link',
				'type' => 'text_medium',
			),			
		),
	);

	/**
	 * Repeatable Field Groups
	 */
	$meta_boxes['amazon-products'] = array(
		'id'         => 'amazon-products',
		'title'      => __( 'Amazon Products', 'tabula-rasa' ),
		'pages'      => array( 'post', ),
		'fields'     => array(
			array(
				'id'          => $prefix . 'amazon-products',
				'type'        => 'group',
				'description' => __( '', 'tabula-rasa' ),
				'options'     => array(
					'group_title'   => __( 'Product # {#}', 'tabula-rasa' ), // {#} gets replaced by row number
					'add_button'    => __( 'Add Another Product', 'tabula-rasa' ),
					'remove_button' => __( 'Remove Product', 'tabula-rasa' ),
					'sortable'      => true, // beta
				),
				// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
				'fields'      => array(
					array(
						'name' => 'Title',
						'id'   => 'amazon_title',
						'type' => 'text',
						// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
					),
					array(
						'name' => 'Link',
						'description' => '',
						'id'   => 'amazon_link',
						'type' => 'text',
					),
				),
			),
		),
	);
	
	$meta_boxes['further_reading'] = array(
		'id'         => 'further-reading',
		'title'      => __( 'Further Reading', 'tabula-rasa' ),
		'pages'      => array( 'post', ),
		'fields'     => array(
			array(
				'id'          => $prefix . 'further-reading',
				'type'        => 'group',
				'description' => __( '', 'tabula-rasa' ),
				'options'     => array(
					'group_title'   => __( 'Link # {#}', 'tabula-rasa' ), // {#} gets replaced by row number
					'add_button'    => __( 'Add Another Link', 'tabula-rasa' ),
					'remove_button' => __( 'Remove Link', 'tabula-rasa' ),
					'sortable'      => true, // beta
				),
				// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
				'fields'      => array(
					array(
						'name' => 'Title',
						'id'   => 'link_title',
						'type' => 'text',
						// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
					),
					array(
						'name' => 'Link',
						'description' => '',
						'id'   => 'link_link',
						'type' => 'text',
					),
				),
			),
		),
	);	

	// Add other metaboxes as needed
	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';
}