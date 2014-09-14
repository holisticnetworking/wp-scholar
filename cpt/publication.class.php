<?php
/*
// Publication:	Bibliographical information.
*/

class ScholarPublication {
	
	public function register_type() {
		register_post_type('publication', array(
			'labels'		=> array(
				'name'					=> 'Publications',
				'singular_name'			=> 'Publication',
				'add_new' 				=> 'Add New',
				'add_new_item' 			=> 'Add New Publication',
				'edit_item' 			=> 'Edit Publication',
				'new_item' 				=> 'New Publication',
				'all_items' 			=> 'All Publications',
				'view_item' 			=> 'View Publication',
				'search_items' 			=> 'Search Publications',
 				'not_found' 			=>  'No publications found',
 				'not_found_in_trash' 	=> 'No publications found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'Publications'
				),
			'description'	=> 	'Bibliographical information',
			'public'		=> true,
			'supports'		=> array('editor', 'thumbnail'),
			'taxonomies'	=> array(),
			'has_archive'	=> true
		));
	}
	
	public function register_taxonomy() {
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
	}
}
?>