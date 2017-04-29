<?php
/**
 * News
 * 
 * New and noteable information.
 */
namespace WPScholar;

class News {
	
	public function register_type() {
		register_post_type('news', array(
			'labels'				=> array(
				'name'					=> 'News',
				'singular_name'			=> 'News',
				'add_new' 				=> 'Add News',
				'add_new_item' 			=> 'Add News Item',
				'edit_item' 			=> 'Edit Item',
				'new_item' 				=> 'New Item',
				'all_items' 			=> 'All Items',
				'view_item' 			=> 'View Item',
				'search_items' 			=> 'Search News Items',
				'not_found' 			=>  'No news items found',
				'not_found_in_trash' 	=> 'No news items found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'News Items'
				),
			'description'			=> 	'Managing news items',
			'public'				=> true,
			'supports'				=> array('title', 'editor', 'excerpt', 'thumbnail', 'comments'),
			'taxonomies'			=> array('category', 'post_tag'),
			'register_meta_box_cb'	=> 'WPScholar\News::add_meta_boxes'
		));
	}
	
	
	public static function add_meta_boxes() {
		add_meta_box( 'news-date', 'Date of Event', 'WPScholar\News::date', 'news', 'side', 'high' );
	}
	
	
	public static function date( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_news_date_nonce' );
		$date		= get_post_meta( $post->ID, 'scholar_news_date', true );
		if(!empty($date)) :
			$date		= date('m/d/y', $date);
		endif;
		
		echo '<p><label for="scholar_news_date">This can be different than publish date:</label></p>';
			echo '<p><input type="text" id="scholar_news_date" name="scholar_news_date" value="'.esc_attr($date).'" size="10" maxlength="20" /></p>';
	}
	public static function save_date( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_news_date_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_news_date_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$date	= sanitize_text_field( $_POST['scholar_news_date'] );
		if(!empty($date)) :
			$date	= strtotime($date);
			// Save the data:
			add_post_meta($post_id, 'scholar_news_date', $date, true) or update_post_meta( $post_id, 'scholar_news_date', $date);
		endif;
	}
	
	
	public function __construct() {
		add_action( 'save_post', 'WPScholar\News::save_date' );
	}
}
?>
