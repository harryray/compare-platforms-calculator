<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'cplat_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

//include 'cmb2-autocomplete.php';
include 'cmb2-chosen/cmb2-chosen.php';

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object
 *
 * @return bool             True if metabox should show
 */
function cplat_show_if_front_page( $cmb ) {
	// Don't show this metabox if it's not the front page template
	if ( $cmb->object_id !== get_option( 'page_on_front' ) ) {
		return false;
	}

	return true;
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function cplat_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}

	return true;
}

/**
 * Conditionally displays a message if the $post_id is 2
 *
 * @param  array $field_args Array of field parameters
 * @param  CMB2_Field object $field      Field object
 */
// function cplat_before_row_if_2( $field_args, $field ) {
// 	if ( 2 == $field->object_id ) {
// 		echo '<p>Testing <b>"before_row"</b> parameter (on $post_id 2)</p>';
// 	} else {
// 		echo '<p>Testing <b>"before_row"</b> parameter (<b>NOT</b> on $post_id 2)</p>';
// 	}
// }

// add_action( 'cmb2_admin_init', 'cplat_register_demo_metabox' );
// /**
//  * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
//  */
// function cplat_register_demo_metabox() {

// 	// Start with an underscore to hide fields from custom fields list
// 	$prefix = '_cplat_demo_';

// 	/**
// 	 * Sample metabox to demonstrate each field type included
// 	 */
// 	$cmb_demo = new_cmb2_box( array(
// 		'id'            => $prefix . 'metabox',
// 		'title'         => __( 'Test Metabox', 'cmb2' ),
// 		'object_types'  => array( 'page', ), // Post type
// 		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
// 		// 'context'    => 'normal',
// 		// 'priority'   => 'high',
// 		// 'show_names' => true, // Show field names on the left
// 		// 'cmb_styles' => false, // false to disable the CMB stylesheet
// 		// 'closed'     => true, // true to keep the metabox closed by default
// 	) );

// 	$cmb_demo->add_field( array(
// 	  'name'  => __( 'Author', 'wds-new-wiki-post-form' ),
// 	  'id'    => 'author',
// 	  'desc'  => __( 'Type the name of the strategist and select from the dropdown', 'cmb2' ),
// 	  'type'  => 'user_select_text'
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'       => __( 'Test Text', 'cmb2' ),
// 		'desc'       => __( 'field description (optional)', 'cmb2' ),
// 		'id'         => $prefix . 'text',
// 		'type'       => 'text',
// 		'show_on_cb' => 'cplat_hide_if_no_cats', // function should return a bool value
// 		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
// 		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
// 		// 'on_front'        => false, // Optionally designate a field to wp-admin only
// 		// 'repeatable'      => true,
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Text Small', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'textsmall',
// 		'type' => 'text_small',
// 		// 'repeatable' => true,
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Text Medium', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'textmedium',
// 		'type' => 'text_medium',
// 		// 'repeatable' => true,
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Website URL', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'url',
// 		'type' => 'text_url',
// 		// 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
// 		// 'repeatable' => true,
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Text Email', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'email',
// 		'type' => 'text_email',
// 		// 'repeatable' => true,
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Time', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'time',
// 		'type' => 'text_time',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Time zone', 'cmb2' ),
// 		'desc' => __( 'Time zone', 'cmb2' ),
// 		'id'   => $prefix . 'timezone',
// 		'type' => 'select_timezone',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Date Picker', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'textdate',
// 		'type' => 'text_date',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Date Picker (UNIX timestamp)', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'textdate_timestamp',
// 		'type' => 'text_date_timestamp',
// 		// 'timezone_meta_key' => $prefix . 'timezone', // Optionally make this field honor the timezone selected in the select_timezone specified above
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Date/Time Picker Combo (UNIX timestamp)', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'datetime_timestamp',
// 		'type' => 'text_datetime_timestamp',
// 	) );

// 	// This text_datetime_timestamp_timezone field type
// 	// is only compatible with PHP versions 5.3 or above.
// 	// Feel free to uncomment and use if your server meets the requirement
// 	// $cmb_demo->add_field( array(
// 	// 	'name' => __( 'Test Date/Time Picker/Time zone Combo (serialized DateTime object)', 'cmb2' ),
// 	// 	'desc' => __( 'field description (optional)', 'cmb2' ),
// 	// 	'id'   => $prefix . 'datetime_timestamp_timezone',
// 	// 	'type' => 'text_datetime_timestamp_timezone',
// 	// ) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Money', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'textmoney',
// 		'type' => 'text_money',
// 		// 'before_field' => '£', // override '$' symbol if needed
// 		// 'repeatable' => true,
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'    => __( 'Test Color Picker', 'cmb2' ),
// 		'desc'    => __( 'field description (optional)', 'cmb2' ),
// 		'id'      => $prefix . 'colorpicker',
// 		'type'    => 'colorpicker',
// 		'default' => '#ffffff',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Text Area', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'textarea',
// 		'type' => 'textarea',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Text Area Small', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'textareasmall',
// 		'type' => 'textarea_small',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Text Area for Code', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'textarea_code',
// 		'type' => 'textarea_code',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Title Weeeee', 'cmb2' ),
// 		'desc' => __( 'This is a title description', 'cmb2' ),
// 		'id'   => $prefix . 'title',
// 		'type' => 'title',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'             => __( 'Test Select', 'cmb2' ),
// 		'desc'             => __( 'field description (optional)', 'cmb2' ),
// 		'id'               => $prefix . 'select',
// 		'type'             => 'select',
// 		'show_option_none' => true,
// 		'options'          => array(
// 			'standard' => __( 'Option One', 'cmb2' ),
// 			'custom'   => __( 'Option Two', 'cmb2' ),
// 			'none'     => __( 'Option Three', 'cmb2' ),
// 		),
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'             => __( 'Test Radio inline', 'cmb2' ),
// 		'desc'             => __( 'field description (optional)', 'cmb2' ),
// 		'id'               => $prefix . 'radio_inline',
// 		'type'             => 'radio_inline',
// 		'show_option_none' => 'No Selection',
// 		'options'          => array(
// 			'standard' => __( 'Option One', 'cmb2' ),
// 			'custom'   => __( 'Option Two', 'cmb2' ),
// 			'none'     => __( 'Option Three', 'cmb2' ),
// 		),
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'    => __( 'Test Radio', 'cmb2' ),
// 		'desc'    => __( 'field description (optional)', 'cmb2' ),
// 		'id'      => $prefix . 'radio',
// 		'type'    => 'radio',
// 		'options' => array(
// 			'option1' => __( 'Option One', 'cmb2' ),
// 			'option2' => __( 'Option Two', 'cmb2' ),
// 			'option3' => __( 'Option Three', 'cmb2' ),
// 		),
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'     => __( 'Test Taxonomy Radio', 'cmb2' ),
// 		'desc'     => __( 'field description (optional)', 'cmb2' ),
// 		'id'       => $prefix . 'text_taxonomy_radio',
// 		'type'     => 'taxonomy_radio',
// 		'taxonomy' => 'category', // Taxonomy Slug
// 		// 'inline'  => true, // Toggles display to inline
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'     => __( 'Test Taxonomy Select', 'cmb2' ),
// 		'desc'     => __( 'field description (optional)', 'cmb2' ),
// 		'id'       => $prefix . 'taxonomy_select',
// 		'type'     => 'taxonomy_select',
// 		'taxonomy' => 'category', // Taxonomy Slug
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'     => __( 'Test Taxonomy Multi Checkbox', 'cmb2' ),
// 		'desc'     => __( 'field description (optional)', 'cmb2' ),
// 		'id'       => $prefix . 'multitaxonomy',
// 		'type'     => 'taxonomy_multicheck',
// 		'taxonomy' => 'post_tag', // Taxonomy Slug
// 		// 'inline'  => true, // Toggles display to inline
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Checkbox', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'checkbox',
// 		'type' => 'checkbox',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'    => __( 'Test Multi Checkbox', 'cmb2' ),
// 		'desc'    => __( 'field description (optional)', 'cmb2' ),
// 		'id'      => $prefix . 'multicheckbox',
// 		'type'    => 'multicheck',
// 		// 'multiple' => true, // Store values in individual rows
// 		'options' => array(
// 			'check1' => __( 'Check One', 'cmb2' ),
// 			'check2' => __( 'Check Two', 'cmb2' ),
// 			'check3' => __( 'Check Three', 'cmb2' ),
// 		),
// 		// 'inline'  => true, // Toggles display to inline
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'    => __( 'Test wysiwyg', 'cmb2' ),
// 		'desc'    => __( 'field description (optional)', 'cmb2' ),
// 		'id'      => $prefix . 'wysiwyg',
// 		'type'    => 'wysiwyg',
// 		'options' => array( 'textarea_rows' => 5, ),
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'Test Image', 'cmb2' ),
// 		'desc' => __( 'Upload an image or enter a URL.', 'cmb2' ),
// 		'id'   => $prefix . 'image',
// 		'type' => 'file',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'         => __( 'Multiple Files', 'cmb2' ),
// 		'desc'         => __( 'Upload or add multiple images/attachments.', 'cmb2' ),
// 		'id'           => $prefix . 'file_list',
// 		'type'         => 'file_list',
// 		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name' => __( 'oEmbed', 'cmb2' ),
// 		'desc' => __( 'Enter a youtube, twitter, or instagram URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'cmb2' ),
// 		'id'   => $prefix . 'embed',
// 		'type' => 'oembed',
// 	) );

// 	$cmb_demo->add_field( array(
// 		'name'         => 'Testing Field Parameters',
// 		'id'           => $prefix . 'parameters',
// 		'type'         => 'text',
// 		'before_row'   => 'cplat_before_row_if_2', // callback
// 		'before'       => '<p>Testing <b>"before"</b> parameter</p>',
// 		'before_field' => '<p>Testing <b>"before_field"</b> parameter</p>',
// 		'after_field'  => '<p>Testing <b>"after_field"</b> parameter</p>',
// 		'after'        => '<p>Testing <b>"after"</b> parameter</p>',
// 		'after_row'    => '<p>Testing <b>"after_row"</b> parameter</p>',
// 	) );

// }

// add_action( 'cmb2_admin_init', 'cplat_register_about_page_metabox' );
// /**
//  * Hook in and add a metabox that only appears on the 'About' page
//  */
// function cplat_register_about_page_metabox() {

// 	// Start with an underscore to hide fields from custom fields list
// 	$prefix = '_cplat_about_';

// 	/**
// 	 * Metabox to be displayed on a single page ID
// 	 */
// 	$cmb_about_page = new_cmb2_box( array(
// 		'id'           => $prefix . 'metabox',
// 		'title'        => __( 'About Page Metabox', 'cmb2' ),
// 		'object_types' => array( 'page', ), // Post type
// 		'context'      => 'normal',
// 		'priority'     => 'high',
// 		'show_names'   => true, // Show field names on the left
// 		'show_on'      => array( 'id' => array( 2, ) ), // Specific post IDs to display this metabox
// 	) );

// 	$cmb_about_page->add_field( array(
// 		'name' => __( 'Test Text', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'text',
// 		'type' => 'text',
// 	) );

// }

// add_action( 'cmb2_admin_init', 'cplat_register_repeatable_group_field_metabox' );
// /**
//  * Hook in and add a metabox to demonstrate repeatable grouped fields
//  */
// function cplat_register_repeatable_group_field_metabox() {

// 	// Start with an underscore to hide fields from custom fields list
// 	$prefix = '_cplat_group_';

// 	/**
// 	 * Repeatable Field Groups
// 	 */
// 	$cmb_group = new_cmb2_box( array(
// 		'id'           => $prefix . 'metabox',
// 		'title'        => __( 'Repeating Field Group', 'cmb2' ),
// 		'object_types' => array( 'page', ),
// 	) );

// 	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
// 	$group_field_id = $cmb_group->add_field( array(
// 		'id'          => $prefix . 'demo',
// 		'type'        => 'group',
// 		'description' => __( 'Generates reusable form entries', 'cmb2' ),
// 		'options'     => array(
// 			'group_title'   => __( 'Entry {#}', 'cmb2' ), // {#} gets replaced by row number
// 			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
// 			'remove_button' => __( 'Remove Entry', 'cmb2' ),
// 			'sortable'      => true, // beta
// 			// 'closed'     => true, // true to have the groups closed by default
// 		),
// 	) );

// 	/**
// 	 * Group fields works the same, except ids only need
// 	 * to be unique to the group. Prefix is not needed.
// 	 *
// 	 * The parent field's id needs to be passed as the first argument.
// 	 */
// 	$cmb_group->add_group_field( $group_field_id, array(
// 		'name'       => __( 'Entry Title', 'cmb2' ),
// 		'id'         => 'title',
// 		'type'       => 'text',
// 		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
// 	) );

// 	$cmb_group->add_group_field( $group_field_id, array(
// 		'name'        => __( 'Description', 'cmb2' ),
// 		'description' => __( 'Write a short description for this entry', 'cmb2' ),
// 		'id'          => 'description',
// 		'type'        => 'textarea_small',
// 	) );

// 	$cmb_group->add_group_field( $group_field_id, array(
// 		'name' => __( 'Entry Image', 'cmb2' ),
// 		'id'   => 'image',
// 		'type' => 'file',
// 	) );

// 	$cmb_group->add_group_field( $group_field_id, array(
// 		'name' => __( 'Image Caption', 'cmb2' ),
// 		'id'   => 'image_caption',
// 		'type' => 'text',
// 	) );

// }

// add_action( 'cmb2_admin_init', 'cplat_register_user_profile_metabox' );
// /**
//  * Hook in and add a metabox to add fields to the user profile pages
//  */
// function cplat_register_user_profile_metabox() {

// 	// Start with an underscore to hide fields from custom fields list
// 	$prefix = '_cplat_user_';

// 	/**
// 	 * Metabox for the user profile screen
// 	 */
// 	$cmb_user = new_cmb2_box( array(
// 		'id'               => $prefix . 'edit',
// 		'title'            => __( 'User Profile Metabox', 'cmb2' ),
// 		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
// 		'show_names'       => true,
// 		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
// 	) );

// 	$cmb_user->add_field( array(
// 		'name'     => __( 'Extra Info', 'cmb2' ),
// 		'desc'     => __( 'field description (optional)', 'cmb2' ),
// 		'id'       => $prefix . 'extra_info',
// 		'type'     => 'title',
// 		'on_front' => false,
// 	) );

// 	$cmb_user->add_field( array(
// 		'name'    => __( 'Avatar', 'cmb2' ),
// 		'desc'    => __( 'field description (optional)', 'cmb2' ),
// 		'id'      => $prefix . 'avatar',
// 		'type'    => 'file',
// 	) );

// 	$cmb_user->add_field( array(
// 		'name' => __( 'Facebook URL', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'facebookurl',
// 		'type' => 'text_url',
// 	) );

// 	$cmb_user->add_field( array(
// 		'name' => __( 'Twitter URL', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'twitterurl',
// 		'type' => 'text_url',
// 	) );

// 	$cmb_user->add_field( array(
// 		'name' => __( 'Google+ URL', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'googleplusurl',
// 		'type' => 'text_url',
// 	) );

// 	$cmb_user->add_field( array(
// 		'name' => __( 'Linkedin URL', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'linkedinurl',
// 		'type' => 'text_url',
// 	) );

// 	$cmb_user->add_field( array(
// 		'name' => __( 'User Field', 'cmb2' ),
// 		'desc' => __( 'field description (optional)', 'cmb2' ),
// 		'id'   => $prefix . 'user_text_field',
// 		'type' => 'text',
// 	) );

// }

// add_action( 'cmb2_admin_init', 'cplat_register_theme_options_metabox' );


/*-----------------------------------------------------------------------------------*/
/*	Platform Metaboxes
/*-----------------------------------------------------------------------------------*/

add_action( 'cmb2_admin_init', 'cplat_register_platform_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function cplat_register_platform_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cplat_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Platform Settings', 'cmb2' ),
		'object_types' => array( 'platform', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	// $cmb_demo->add_field( array(
	//   'name'  => __( 'Platform Author', 'cplat' ),
	//   'id'    => 'platform_author',
	//   'desc'  => __( 'Person with access to account', 'cmb2' ),
	//   'type'  => 'user_select_text'
	// ) );

	$cmb_demo->add_field( array(
		'name' => __( 'Website Link', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'platform_link',
		'type' => 'text'
	) );
	$cmb_demo->add_field( array(
		'name'    => __( 'Type of platform', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'inv_management_type',
		'type'    => 'radio',
		'options' => array(
			'advised' => 'Advised',
			'd2c'     => 'D2C'
		)
	) );

	$cmb_demo->add_field( array(
		'name'    => __( 'Recommended Funds List', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'recommended_funds_list',
		'type'    => 'radio',
		'options' => array(
			'yes' => 'Yes',
			'no'  => 'No'
		)
	) );

	$cmb_demo->add_field( array(
		'name'    => __( 'Calculation method', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'calculation_method',
		'type'    => 'radio',
		'options' => array(
			'method1' => 'One rated based on total assets',
			'method2' => 'Combined tiered rate on a product basis (Split)',
			'method3' => 'Combined tiered rate on a product basis (Not split)',
			'method4' => 'Combined tiered rate on a total asset basis (Split)',
			'method5' => 'Combined tiered rate on a total asset basis (Not split)'
		)
	) );
}


add_action( 'cmb2_admin_init', 'cplat_register_user_profile_metabox' );
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function cplat_register_user_profile_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cplat_user_';

	/**
	 * Metabox for the user profile screen
	 */
	$cmb_user = new_cmb2_box( array(
		'id'               => $prefix . 'edit',
		'title'            => __( 'Access to platform data', 'cmb2' ),
		'object_types'     => array( 'user' ),
		// Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user',
		// where form will show on new user page. 'add-existing-user' is only other valid option.
	) );

	$cmb_user->add_field( array(
		'name'     => __( 'Access to platform', 'cmb2' ),
		'desc'     => __( '', 'cmb2' ),
		'id'       => $prefix . 'platform',
		'type'     => 'sfn_chosen_multi',
		'on_front' => false,
		'default'  => '',
		'options'  => cplat_get_platform_posts()
	) );
}

add_action( 'cmb2_admin_init', 'cplat_register_platform_info_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function cplat_register_platform_info_metabox() {
	// Get the post ID //Ticket#307
	$post_id = null;
	if ( isset( $_GET[ 'post' ] ) ) {
		$post_id = $_GET[ 'post' ];
	} else if ( isset( $_POST[ 'post_ID' ] ) ) {
		$post_id = $_POST[ 'post_ID' ];
	}

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cplat_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_av_products = new_cmb2_box( array(
		'id'           => $prefix . 'platform_info_metabox',
		'title'        => __( 'Available products & Wrappers', 'cmb2' ),
		'object_types' => array( 'platform', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$cmb_av_products->add_field( array(
		'name' => __( 'General Investments', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_gia',
		'type' => 'checkbox'
	) );
	$cmb_av_products->add_field( array(
		'name' => __( 'General Investments Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_gia_note',
		'type' => 'text'
	) );

	$cmb_av_products->add_field( array(
		'name' => __( 'ISAS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_isa',
		'type' => 'checkbox'
	) );
	$cmb_av_products->add_field( array(
		'name' => __( 'ISAS Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_isa_note',
		'type' => 'text'
	) );

	$cmb_av_products->add_field( array(
		'name' => __( 'JISAS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_jisa',
		'type' => 'checkbox'
	) );
	$cmb_av_products->add_field( array(
		'name' => __( 'JISAS Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_jisa_note',
		'type' => 'text'
	) );

	$cmb_av_products->add_field( array(
		'name' => __( 'Pensions & SIPPS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_sipp',
		'type' => 'checkbox'
	) );
	$cmb_av_products->add_field( array(
		'name' => __( 'Pensions & SIPPS Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_sipp_note',
		'type' => 'text'
	) );

	$cmb_av_products->add_field( array(
		'name' => __( 'Junior Pensions & SIPPS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_jsipp',
		'type' => 'checkbox'
	) );
	$cmb_av_products->add_field( array(
		'name' => __( 'Junior Pensions & SIPPS Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_jsipp_note',
		'type' => 'text'
	) );
	//Ticket#307 start
	$_cplat_inv_management_type = get_post_meta($post_id,'_cplat_inv_management_type',true);
	if( $_cplat_inv_management_type == 'advised' ){
		$cmb_av_products->add_field( array(
			'name' => __( 'Onshore Bonds', 'cmb2' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'available_products_onshore_bond',
			'type' => 'checkbox'
		) );
		$cmb_av_products->add_field( array(
			'name' => __( 'Onshore Bonds Note', 'cmb2' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'available_products_onshore_bond_note',
			'type' => 'text'
		) );
		$cmb_av_products->add_field( array(
			'name' => __( 'Offshore Bond', 'cmb2' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'available_products_offshore_bond',
			'type' => 'checkbox'
		) );
		$cmb_av_products->add_field( array(
			'name' => __( 'Offshore Bond Note', 'cmb2' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'available_products_offshore_bond_note',
			'type' => 'text'
		) );
	}else{
		$cmb_av_products->add_field( array(
			'name' => __( 'Lifetime ISAs', 'cmb2' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'available_products_lifetime_isa',
			'type' => 'checkbox'
		) );
		$cmb_av_products->add_field( array(
			'name' => __( 'Lifetime ISAs Note', 'cmb2' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'available_products_lifetime_isa_note',
			'type' => 'text'
		) );
	}
	//Ticket#307 start
	$cmb_av_products->add_field( array(
		'name' => __( 'Other', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_other',
		'type' => 'checkbox'
	) );
	$cmb_av_products->add_field( array(
		'name' => __( 'Other Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_products_other_note',
		'type' => 'text'
	) );

	/*-----------------------------------------------------------------------------------*/
	/*	Available Investments
	/*-----------------------------------------------------------------------------------*/

	$cmb_av_investments = new_cmb2_box( array(
		'id'           => $prefix . 'platform_available_inv_metabox',
		'title'        => __( 'Available Investments', 'cmb2' ),
		'object_types' => array( 'platform', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$cmb_av_investments->add_field( array(
		'name' => __( 'Funds UK', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_funds_uk',
		'type' => 'checkbox'
	) );
	$cmb_av_investments->add_field( array(
		'name' => __( 'Funds Uk Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_funds_uk_note',
		'type' => 'text'
	) );

	$cmb_av_investments->add_field( array(
		'name' => __( 'Funds Offshore', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_funds_offshore',
		'type' => 'checkbox'
	) );
	$cmb_av_investments->add_field( array(
		'name' => __( 'Funds Offshore Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_funds_offshore_note',
		'type' => 'text'
	) );

	$cmb_av_investments->add_field( array(
		'name' => __( 'ETFS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_etfs',
		'type' => 'checkbox'
	) );
	$cmb_av_investments->add_field( array(
		'name' => __( 'ETFS Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_etfs_note',
		'type' => 'text'
	) );

	$cmb_av_investments->add_field( array(
		'name' => __( 'ITS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_its',
		'type' => 'checkbox'
	) );
	$cmb_av_investments->add_field( array(
		'name' => __( 'ITS Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_its_note',
		'type' => 'text'
	) );

	$cmb_av_investments->add_field( array(
		'name' => __( 'Equities', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_equities',
		'type' => 'checkbox'
	) );
	$cmb_av_investments->add_field( array(
		'name' => __( 'Equities Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_equities_note',
		'type' => 'text'
	) );

	$cmb_av_investments->add_field( array(
		'name' => __( 'Bonds', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_bonds',
		'type' => 'checkbox'
	) );
	$cmb_av_investments->add_field( array(
		'name' => __( 'Bonds Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_bonds_note',
		'type' => 'text'
	) );

	$cmb_av_investments->add_field( array(
		'name' => __( 'Other', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_other',
		'type' => 'checkbox'
	) );
	$cmb_av_investments->add_field( array(
		'name' => __( 'Other Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'available_inv_other_note',
		'type' => 'text'
	) );


	/*-----------------------------------------------------------------------------------*/
	/*	Research, tools and information
	/*-----------------------------------------------------------------------------------*/

	$cmb_research_tools_investments = new_cmb2_box( array(
		'id'           => $prefix . 'platform_research_tools_metabox',
		'title'        => __( 'Research, Tools and Information', 'cmb2' ),
		'object_types' => array( 'platform', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$group_field_id = $cmb_research_tools_investments->add_field( array(
		'id'          => 'research_tools_items',
		'type'        => 'group',
		'description' => __( 'Items', 'cmb2' ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_research_tools_investments->add_group_field( $group_field_id, array(
		'name' => 'Entry Label',
		'id'   => $prefix . 'research_tools_label',
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb_research_tools_investments->add_group_field( $group_field_id, array(
		'name'        => 'Entry Data',
		'description' => '',
		'id'          => $prefix . 'research_tools_data',
		'type'        => 'textarea_small',
	) );

	/*-----------------------------------------------------------------------------------*/
	/*	Table of charges
	/*-----------------------------------------------------------------------------------*/

	$cmb_charges = new_cmb2_box( array(
		'id'           => $prefix . 'platform_charges_metabox',
		'title'        => __( 'Platform Charges', 'cmb2' ),
		'object_types' => array( 'platform', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$charges_group_field_id = $cmb_charges->add_field( array(
		'id'          => 'charges_items',
		'type'        => 'group',
		'description' => __( 'Charges Items', 'cmb2' ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Row Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_charges->add_group_field( $charges_group_field_id, array(
		'name' => 'Entry Label',
		'id'   => $prefix . 'charges_label',
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_charges->add_group_field( $charges_group_field_id, array(
		'name' => 'Platform Charges',
		'id'   => $prefix . 'platform_charges',
		'type' => 'textarea_small',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_charges->add_group_field( $charges_group_field_id, array(
		'name' => 'Product Charges',
		'id'   => $prefix . 'product_charges',
		'type' => 'textarea_small',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_charges->add_group_field( $charges_group_field_id, array(
		'name' => 'Other Charges',
		'id'   => $prefix . 'other_charges',
		'type' => 'textarea_small',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );


	/*-----------------------------------------------------------------------------------*/
	/*	Compare the platform says
	/*-----------------------------------------------------------------------------------*/
	$rating = new_cmb2_box( array(
		'id'           => $prefix . 'platform_rating_metabox',
		'title'        => __( 'COMPARE THE PLATFORM SAYS', 'cmb2' ),
		'object_types' => array( 'platform', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$rating->add_field( array(
		'name'    => __( 'Products & Wrappers', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'rating_products',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_field( array(
		'name' => __( 'Products & Wrappers Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'rating_products_note',
		'type' => 'text'
	) );

	$rating->add_field( array(
		'name'    => __( 'Investments', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'rating_investments',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_field( array(
		'name' => __( 'Investments Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'rating_investments_note',
		'type' => 'text'
	) );

	$rating->add_field( array(
		'name'    => __( 'Research & Guidance', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'rating_research',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_field( array(
		'name' => __( 'Research & Guidance', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'rating_research_note',
		'type' => 'text'
	) );

	$rating->add_field( array(
		'name'    => __( 'Charges', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'rating_charges',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_field( array(
		'name' => __( 'Charges Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'rating_charges_note',
		'type' => 'text'
	) );


	$rating->add_field( array(
		'name'    => __( 'Overall Service', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'rating_service',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_field( array(
		'name' => __( 'Overall Service Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . 'rating_service_note',
		'type' => 'text'
	) );

	$rating->add_field( array(
		'name' => __( 'CTP Comment', 'cmb2' ),
		'desc' => __( 'CTP Comment', 'cmb2' ),
		'id'   => $prefix . 'ctp_platforms_comment_desc',
		'type' => 'wysiwyg',
	) );

	$rating->add_field( array(
		'name'    => __( 'Average Rating shown on results', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => '_cplat_rating',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		)
	) );

	$rating->add_field( array(
		'name' => __( 'Platform Comment', 'cmb2' ),
		'desc' => __( 'Platform Comment', 'cmb2' ),
		'id'   => $prefix . 'plat_platforms_comment_desc',
		'type' => 'textarea_small',
	) );

	
	

	/*-----------------------------------------------------------------------------------*/
	/*	Question 1 edit
	/*-----------------------------------------------------------------------------------*/

	// $questions = new_cmb2_box( array(
	// 	'id'            => $prefix . 'platform_questions_metabox',
	// 	'title'         => __( 'Questions', 'cmb2' ),
	// 	'object_types'  => array( 'platform', ), // Post type
	// 	// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
	// 	// 'context'    => 'normal',
	// 	// 'priority'   => 'high',
	// 	// 'show_names' => true, // Show field names on the left
	// 	// 'cmb_styles' => false, // false to disable the CMB stylesheet
	// 	// 'closed'     => true, // true to keep the metabox closed by default
	// ) );

	// $rating->add_field( array(
	// 	'name'       => __( 'Question 1 ', 'cmb2' ),
	// 	'desc'       => __( '', 'cmb2' ),
	// 	'id'         => $prefix . 'question1',
	// 	'type'       => 'text'
	// ) );
	// $rating->add_field( array(
	// 	'name'       => __( 'Question 1 help', 'cmb2' ),
	// 	'desc'       => __( '', 'cmb2' ),
	// 	'id'         => $prefix . 'question1help',
	// 	'type'       => 'text'
	// ) );

}


function cplat_get_platform_posts() {

	$args = array(
		'posts_per_page' => - 1,
		'offset'         => 0,
		'category'       => '',
		'category_name'  => '',
		'orderby'        => 'title',
		'order'          => 'DESC',
		'include'        => '',
		'exclude'        => '',
		'meta_key'       => '',
		'meta_value'     => '',
		'post_type'      => 'platform'
	);

	$posts = get_posts( $args );

	$select = array();

	foreach ( $posts as $post ) {
		$select[ $post->ID ] = $post->post_title;
	}

	return $select;
}

function cmb2_render_callback_platform_edit_link( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	global $post_id;
	$url = esc_url( get_permalink( get_page_by_path( 'client-area' ) ) );
	$url = add_query_arg( 'platform_id', $post_id, $url );
	echo '<a target="_blank" href="' . $url . '">' . __( 'Edit platform data', 'cplat' ) . '</a>';
}

add_action( 'cmb2_render_platform_edit_link', 'cmb2_render_callback_platform_edit_link', 10, 5 );

function cmb2_render_callback_platform_version_list( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	global $post_id;
	if ( null === $post_id ) {
		_e( 'You have to save before you can edit platform data', 'cplat' );

		return false;
	}
	$ctp_api = new CTP_API();

	$platform_data = $ctp_api->ctp_api_get_platform( $post_id, false );
	//$platform_data       = get_post_meta( $post_id, 'platform_data', true );
	$version_data        = empty( $platform_data['platform_data'] ) ? null : $platform_data['platform_data'];

	if ( null === $version_data ) :
		$add_version_url = esc_url( get_permalink( get_page_by_path( 'client-area' ) ) );
		$add_version_url = add_query_arg( 'platform_id', $post_id, $add_version_url );
		$add_version_url = add_query_arg( 'version_id', '1', $add_version_url );
		?>

        <div><a target="_blank" href="<?php echo esc_url( $add_version_url ); ?>"
                class="button button-primary"><?php _e( 'Add Data', 'cplat' ); ?></a></div>
        <br>
	<?php endif; ?>

    <table class="wp-list-table widefat fixed posts">
        <thead>
        <tr>
            <th><?php _e( 'Version', 'datbooking' ); ?></th>
            <th><?php _e( 'Date Created', 'datbooking' ); ?></th>
            <th><?php _e( 'Active From', 'datbooking' ); ?></th>
            <th><?php _e( 'Active To', 'datbooking' ); ?></th>
            <th><?php _e( 'Approved', 'datbooking' ); ?></th>
            <th><?php _e( 'Edit', 'datbooking' ); ?></th>
            <th><?php _e( 'Remove', 'datbooking' ) ?></th>
        </tr>
        </thead>
        <tbody>
		<?php
		if ( is_array( $version_data ) ) :
			//$platform_data = array_reverse( $platform_data, true );
			$current_version_id = cplat_get_current_version( $version_data );
			foreach ( $version_data as $key => $platform ) :
				$edit_url = esc_url( get_permalink( get_page_by_path( 'client-area' ) ) );
				$edit_url = add_query_arg( 'platform_id', $post_id, $edit_url );
				$edit_url = add_query_arg( 'version_id', $platform['version'], $edit_url );
				$admin_screen_url = admin_url() . 'post.php?post=' . $post_id . '&action=edit';
				$remove_url = add_query_arg( 'remove_version', $key, $admin_screen_url );
				$status = esc_attr( $platform['rec_status'] );
				if ( $current_version_id == $key ) {
					$class = 'current';
				} else {
					$class = '';
				}
				?>
                <tr class="<?php echo $class; ?>">
                    <td><?php echo intval( $platform['version'] ); ?></td>
                    <td><?php echo date( 'd M Y', strtotime( $platform['date_created'] ) ); ?></td>
                    <td><?php echo date( 'd M Y', strtotime( $platform['active_from'] ) ); ?></td>
                    <td><?php echo date( 'd M Y', strtotime( $platform['active_to'] ) ); ?></td>
                    <td>
                        <select name="version[<?php echo $key; ?>][status]">
                            <option <?php selected( $platform['rec_status'], Calculator_Compare::STATUS_PENDING ); ?>
                                    value="pending"><?php _e( 'Pending', 'cplat' ); ?></option>
                            <option <?php selected( $platform['rec_status'], Calculator_Compare::STATUS_APPROVED ); ?>
                                    value="approved"><?php _e( 'Approved', 'cplat' ); ?></option>
                            <option <?php selected( $platform['rec_status'], Calculator_Compare::STATUS_REJECTED ); ?>
                                    value="rejected"><?php _e( 'Rejected', 'cplat' ); ?></option>
                            <option <?php selected( $platform['rec_status'], Calculator_Compare::STATUS_SWITCH_PENDING ); ?>
                                    value="pending_approved"><?php _e( 'Approved(Switch Pending)', 'cplat' ); ?></option>
                        </select>
                    <td><a target="_blank" href="<?php echo esc_url( $edit_url ); ?>"><?php _e( 'Edit' ) ?></a></td>
                    <td><input type="checkbox" name="version[<?php echo $key; ?>][remove_version]" value="1"></td>
                </tr>
			<?php
			endforeach;
		endif;
		?>
        </tbody>
    </table>
	<?php
}

add_action( 'cmb2_render_platform_version_list', 'cmb2_render_callback_platform_version_list', 10, 5 );

function cmb2_platform_edit_link_metabox() {

	$cmb = new_cmb2_box( array(
		'id'           => 'cmb2_platform_edit_link_metabox',
		'title'        => 'Platform Data',
		'object_types' => array( 'platform' ),
	) );

	// $cmb->add_field( array(
	//     'name' => '',
	//     'id'   => '_cmb2_paltform_edit_link',
	//     'type' => 'platform_edit_link',
	//     'desc' => '',
	// ) );

	$cmb->add_field( array(
		'name' => '',
		'id'   => '_cmb2_paltform_version',
		'type' => 'platform_version_list',
		'desc' => '',
	) );


}

add_action( 'cmb2_admin_init', 'cmb2_platform_edit_link_metabox' );

// function cplat_remove_version() {
//     global $post_id;
//     die(var_dump($post_id));
//     if ( isset( $_GET['remove_version'] ) ) {
//         $version_id = (int) $_GET['remove_version'];
//         $all_versions = get_post_meta($post_id, 'platform_data', true);
//         unset( $all_versions[$version_id] );
//         update_post_meta( $post_id, 'platform_data', $all_versions );
//     }
// }
// add_action('admin_init', 'cplat_remove_version' );

function save_platform_data_metabox() {

	global $post_id, $post;
	if ( $post->post_type !== 'platform' ) {
		return $post_id;
	}
	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_platform_data', $post_id ) ) {
		return $post_id;
	}

	if ( ! isset( $_POST['version'] ) ) {
		return false;
	}
	$all_data = get_post_meta( $post_id, 'platform_data', true );
	if ( empty( $all_data ) ) {
		$all_data = array();
	}
	$version_to_save  = [];
	$data_pending     = [];
	$approved         = false;
	$pending_approved = false;
	foreach ( $_POST['version'] as $version_id => $data ) {

		if ( isset( $data['status'] ) ) {
			$all_data[ $version_id ]['status'] = sanitize_text_field( $data['status'] );
			if ( $data['status'] === 'approved' ) {
				if ( $approved ) {
					return false;
				}
				$data_to_save             = $all_data[ $version_id ];
				$version_to_save          = $version_id;
				$version_pending_approval = $version_id;
				$approved                 = true;
			}
			if ( $data['status'] === 'pending_approved' ) {
				if ( $pending_approved ) {
					return false;
				}
				$data_pending             = $all_data[ $version_id ];
				$version_pending_approval = $version_id;
				$pending_approved         = true;

			}

			if ( isset( $data['remove_version'] ) && '1' === $data['remove_version'] ) {

				$ctp_api                                       = new CTP_API();
				$all_data[ $version_id ]['data']['version_id'] = $version_id;
				$ctp_api->ctp_api_update_platform( $post_id, $all_data[ $version_id ], 'delete' );
				unset( $all_data[ $version_id ] );
			}
		}
	}
	update_post_meta( $post_id, 'platform_data', $all_data );
	if ( isset( $data_to_save ) ) {

		$ctp_api                            = new CTP_API();
		$data_to_save['data']['version_id'] = $version_to_save;
		$ctp_api->ctp_api_update_platform( $post_id, $data_to_save, 'approved' );
	}
	if ( $pending_approved ) {
		$ctp_api                            = new CTP_API();
		$data_pending['data']['version_id'] = $version_pending_approval;
		$ctp_api->ctp_api_update_platform( $post_id, $data_pending, 'pending_approved' );
	}



}

add_action( 'save_post', 'save_platform_data_metabox' );


function cplat_jargon_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cplat_jargon';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Jargon Content', 'cmb2' ),
		'object_types' => array( 'jargon', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Description Excerpt', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => 'jargon_excerpt',
		'type' => 'wysiwyg'
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Full Description', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => 'jargon_full',
		'type' => 'wysiwyg'
	) );

}

add_action( 'cmb2_admin_init', 'cplat_jargon_metabox' );

function cplat_investor_table_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cplat_investor_table';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'CTP', 'cmb2' ),
		'object_types' => array( 'investor', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$group_field_id = $cmb->add_field( array(
		'id'          => 'investor_table',
		'type'        => 'group',
		'description' => __( '', 'cmb2' ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb->add_group_field( $group_field_id, array(
		'name' => 'Platform',
		'id'   => 'platform_title',
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'        => 'Platform Fee',
		'description' => '',
		'id'          => 'platform_fee',
		'type'        => 'text',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Recommended Funds List',
		'id'      => 'recommended_funds',
		'type'    => 'radio',
		'options' => array(
			'yes' => 'Yes',
			'no'  => 'No'
		)
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'CTP Rating',
		'id'      => 'stp_rating',
		'type'    => 'select',
		'options' => array(
			'1' => 1,
			'2' => 2,
			'3' => 3,
			'4' => 4,
			'5' => 5
		)
	) );

}

add_action( 'cmb2_admin_init', 'cplat_investor_table_metabox' );


function cplat_investor_ctp_view_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cplat_investor_ctp_view';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'CTP', 'cmb2' ),
		'object_types' => array( 'investor', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'CTP view', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => 'ctp_view',
		'type' => 'wysiwyg'
	) );

}

add_action( 'cmb2_admin_init', 'cplat_investor_ctp_view_metabox' );


function cplat_investor_secondary_table_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cplat_investor_ctp_secondary_switch';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'CTP', 'cmb2' ),
		'object_types' => array( 'investor', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Activate Secondary Table', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => 'ctp_secondary_table',
		'type' => 'checkbox'
	) );

}

add_action( 'cmb2_admin_init', 'cplat_investor_secondary_table_metabox' );

function cplat_investor_table_secondary_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cplat_investor_table_secondary';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'CTP', 'cmb2' ),
		'object_types' => array( 'investor', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$group_field_id = $cmb->add_field( array(
		'id'          => 'investor_table_secondary',
		'type'        => 'group',
		'description' => __( '', 'cmb2' ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb->add_group_field( $group_field_id, array(
		'name' => 'Platform',
		'id'   => 'platform_title',
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'        => 'Platform Fee',
		'description' => '',
		'id'          => 'platform_fee',
		'type'        => 'text',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Recommended Funds List',
		'id'      => 'recommended_funds',
		'type'    => 'radio',
		'options' => array(
			'yes' => 'Yes',
			'no'  => 'No'
		)
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'CTP Rating',
		'id'      => 'stp_rating',
		'type'    => 'select',
		'options' => array(
			'1' => 1,
			'2' => 2,
			'3' => 3,
			'4' => 4,
			'5' => 5
		)
	) );

}

add_action( 'cmb2_admin_init', 'cplat_investor_table_secondary_metabox' );