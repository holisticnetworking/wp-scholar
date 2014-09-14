<?php
/*
// News Feeds:	Manage RSS and Atom feeds here.
*/

class ScholarGradStudy {
	
	public function register_type() {
		register_post_type('grad_study', array(
			'labels'		=> array(
				'name'					=> 'Graduate Studies',
				'singular_name'			=> 'Graduate Study',
				'add_new' 				=> 'Add Graduate Study',
				'add_new_item' 			=> 'Add New Graduate Study',
				'edit_item' 			=> 'Edit Graduate Study',
				'new_item' 				=> 'New Graduate Study',
				'all_items' 			=> 'All Graduate Studies',
				'view_item' 			=> 'View Graduate Study',
				'search_items' 			=> 'Search Graduate Studies',
				'not_found' 			=>  'No Graduate Studies found',
				'not_found_in_trash' 	=> 'No Graduate Studies found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'Graduate Studies'
				),
			'description'	=> 'Display a list of current Graduate Study programs',
			'public'		=> true,
			'supports'		=> array('title', 'thumbnail'),
			'taxonomies'	=> array('category', 'post_tag')
		));
	}
}
?>