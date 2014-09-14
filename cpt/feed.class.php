<?php
/*
// News Feeds:	Manage RSS and Atom feeds here.
*/

class ScholarFeed {
	
	public function register_type() {
		register_post_type('feed', array(
			'labels'		=> array(
				'name'					=> 'Feeds',
				'singular_name'			=> 'Feed',
				'add_new' 				=> 'Add New',
				'add_new_item' 			=> 'Add New Feed',
				'edit_item' 			=> 'Edit Feed',
				'new_item' 				=> 'New Feed',
				'all_items' 			=> 'All Feeds',
				'view_item' 			=> 'View Feed',
				'search_items' 			=> 'Search Feeds',
				'not_found' 			=>  'No feeds found',
				'not_found_in_trash' 	=> 'No feeds found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'Feeds'
				),
			'description'	=> 	'Manages a single RSS or Atom feed',
			'public'		=> true,
			'supports'		=> array('title', 'thumbnail'),
			'taxonomies'	=> array('category', 'post_tag')
		));
	}
}
?>