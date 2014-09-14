<?php
/*
// News Feeds:	Manage RSS and Atom feeds here.
*/

class ScholarCourse {
	
	public function register_type() {
		register_post_type('course', array(
			'labels'		=> array(
				'name'					=> 'Courses',
				'singular_name'			=> 'Course',
				'add_new' 				=> 'Add Course',
				'add_new_item' 			=> 'Add New Course',
				'edit_item' 			=> 'Edit Course',
				'new_item' 				=> 'New Course',
				'all_items' 			=> 'All Courses',
				'view_item' 			=> 'View Course',
				'search_items' 			=> 'Search Courses',
				'not_found' 			=>  'No Courses found',
				'not_found_in_trash' 	=> 'No Courses found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'Courses'
				),
			'description'	=> 'Display a sample of courses you teach.',
			'public'		=> true,
			'supports'		=> array('title', 'thumbnail'),
			'taxonomies'	=> array('category', 'post_tag')
		));
	}
}
?>