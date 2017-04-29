<?php
/**
 * Research
 */
namespace WPScholar;

class Research {
	
	public function register_type() {
		register_post_type('research', array(
			'labels'		=> array(
				'name'					=> 'Research',
				'singular_name'			=> 'Research',
				'add_new' 				=> 'Add New Research',
				'add_new_item' 			=> 'Add New Research',
				'edit_item' 			=> 'Edit Research',
				'new_item' 				=> 'New Research',
				'all_items' 			=> 'All Research',
				'view_item' 			=> 'View Research',
				'search_items' 			=> 'Search Research',
 				'not_found' 			=>  'No Research found',
 				'not_found_in_trash' 	=> 'No Research found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'Research'
				),
			'description'	=> 	'Research activities',
			'public'		=> true,
			'supports'		=> array('editor', 'thumbnail'),
			'taxonomies'	=> array(),
			'has_archive'	=> true
		));
	}
	
	/* public function register_taxonomy() {
		// "Pair" taxonomy exists to allow two formulas to be associated with one another.
		register_taxonomy(
			'pub_cat',
			'publication',
			array(
				'labels'		=> array(
					'name'			=> 'Publication Category',
					'add_new_item'	=> 'Add New Publication Category',
					'new_item_name'	=> 'Pair'
				),
				'show_ui'		=> true,
				'show_tagcloud'	=> false,
				'hierarchal'	=> true
			)
		);
	} */
	
	public function __construct() {
		// add_action( 'save_post', 'WPScholar\Research::save_details' );
	}
}
?>
