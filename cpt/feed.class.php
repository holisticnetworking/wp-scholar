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
			'description'			=> 'Manages a single RSS or Atom feed',
			'public'				=> true,
			'supports'				=> array('thumbnail'),
			'taxonomies'			=> array(),
			'register_meta_box_cb'	=> 'ScholarFeed::add_meta_boxes'
		));
	}
	
	public static function add_meta_boxes() {
		add_meta_box( 'feed-details', 'Feed Details', 'ScholarFeed::details', 'feed', 'normal', 'high' );
	}
	
	
	public static function details( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_feeds_nonce' );
		$feed			= get_post_meta( $post->ID, 'scholar_feeds', true );
		$url			= isset( $feed['url'] ) ? esc_attr( $feed['url'] ) : '';
		$title			= isset( $feed['title'] ) ? esc_attr( $feed['title'] ) : '';
		$description	= isset( $feed['description'] ) ? esc_attr( $feed['description'] ) : '';

		echo '<p><label for="scholar_feeds_url">Feed URL:</label></p>';
			echo '<p><input class="widefat" type="text" id="scholar_feeds_url" name="scholar_feeds[url]" value="' . $url . '" size="80" maxlength="200" /></p>';
		echo '<p><label for="scholar_feeds_title">Feed Title (overrides what is provided by the feed itself):</label></p>';
			echo '<p><input type="text" id="scholar_feeds_title" name="scholar_feeds[title]" value="' . $title . '" size="80" maxlength="200" /></p>';
		echo '<p><label for="scholar_feeds_description">Feed Description:</label></p>';
			echo '<p><textarea id="scholar_feeds_description" name="scholar_feeds[description]" style="width: 100%; height: 200px;">' . $description . '</textarea></p>';
		
	}
	public static function save_details( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_feeds_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_feeds_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$feed	= array();
		foreach( $_POST['scholar_feeds'] as $key=>$value ) :
			$feed[$key]	= sanitize_text_field( $value );
		endforeach;
		if(!empty($feed)) :
			// Save the data:
			add_post_meta($post_id, 'scholar_feeds', $feed, true) or update_post_meta( $post_id, 'scholar_feeds', $feed);
		endif;
	}
	
	
	
	public static function replace_title($title, $id) {
		global $id, $post;
		if ( $id && $post && $post->post_type == 'feed' ) :
			$feed				= get_post_meta( $post->ID, 'scholar_feeds', true );
			if(!empty($feed)) :
				$title			= $feed['title'];
			endif;
		endif;
		return $title;
	}
	
	public function ScholarFeed() {
		add_action( 'save_post', 'ScholarFeed::save_details' );
		add_filter( 'the_title', 'ScholarFeed::replace_title', 10, 3 );
	}
}
?>
