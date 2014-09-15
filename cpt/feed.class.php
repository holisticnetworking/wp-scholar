<?php
/*
// News Feeds:	Manage RSS and Atom feeds here.
*/

class ScholarFeed {
	
	public function register_type() {
		register_post_type('feed', array(
			'labels'				=> array(
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
			'description'			=> 	'Manages a single RSS or Atom feed',
			'public'				=> true,
			'supports'				=> array('thumbnail'),
			'taxonomies'			=> array(),
			'register_meta_box_cb'	=> 'ScholarNews::add_meta_boxes'
		));
	}
	
	public static function add_meta_boxes() {
		add_meta_box( 'feed-details', 'Feed Details', 'ScholarFeed::details', 'feed', 'main', 'high' );
	}
	
	
	public static function details( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_feed_details_nonce' );
		$feed		= get_post_meta( $post->ID, 'scholar_feed_details', true );
		
		echo '<p><label for="scholar_feed_details_url">Feed URL:</label></p>';
			echo '<p><input type="text" id="scholar_feed_details_url" name="scholar_feed_details[url]" value="'.esc_attr($feed['url']).'" size="10" maxlength="20" /></p>';
		echo '<p><label for="scholar_feed_details_title">Feed Title (overrides what is provided by the feed itself):</label></p>';
			echo '<p><input type="text" id="scholar_feed_details_title" name="scholar_feed_details[title]" value="'.esc_attr($feed['title']).'" size="10" maxlength="200" /></p>';
		echo '<p><label for="scholar_feed_details_description">Feed Description:</label></p>';
			echo '<p><textarea id="scholar_feed_details_description" name="scholar_feed_details[description]">'.esc_attr($feed['description']).'</textarea></p>';
	}
	public static function save_details( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_feed_details_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_feed_details_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$feed	= array();
		foreach( $_POST['scholar_feed_details'] as $key=>$value ) :
			$feed[$key]	= anitize_text_field( $value );
		endforeach;
		if(!empty($feed)) :
			// Save the data:
			add_post_meta($post_id, 'scholar_feed_details', $feed, true) or update_post_meta( $post_id, 'scholar_feed_details', $feed);
		endif;
	}
	
	public function ScholarFeed() {
		add_action( 'save_post', 'ScholarFeed::save_details' );
	}
}
?>
