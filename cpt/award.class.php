<?php
/*
// News Feeds:	Manage RSS and Atom feeds here.
*/

class ScholarAward {
	
	public function register_type() {
		register_post_type('award', array(
			'labels'		=> array(
				'name'					=> 'Awards and Honors',
				'singular_name'			=> 'Award or Honor',
				'add_new' 				=> 'Add Award or Honor',
				'add_new_item' 			=> 'Add New Award or Honor',
				'edit_item' 			=> 'Edit Award or Honor',
				'new_item' 				=> 'New Award or Honor',
				'all_items' 			=> 'All Awards and Honors',
				'view_item' 			=> 'View Award or Honor',
				'search_items' 			=> 'Search Awards and Honors',
				'not_found' 			=>  'No award or honor found',
				'not_found_in_trash' 	=> 'No award or honor found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'Awards'
				),
			'description'	=> 	'Lauds awards and honors',
			'public'		=> true,
			'supports'		=> array('title', 'thumbnail'),
			'taxonomies'	=> array('category', 'post_tag')
		));
	}
}
?>