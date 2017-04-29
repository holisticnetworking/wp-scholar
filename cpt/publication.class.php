<?php
/**
 * Publication
 */

namespace WPScholar;
require_once WP_PLUGIN_DIR . '/easy-inputs/easy-inputs.php';
use EasyInputs\EasyInputs;

class Publication {
	
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
 				'not_found' 			=> 'No publications found',
 				'not_found_in_trash' 	=> 'No publications found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'Publications'
				),
			'description'			=> 	'Bibliographical information',
			'public'				=> true,
			'supports'				=> array('thumbnail', 'title'),
			'register_meta_box_cb'	=> 'WPScholar\Publication::add_meta_boxes',
			'taxonomies'			=> array(),
			'has_archive'			=> 'publications',
			'rewrite'				=> array(
				'with_front'	=> false,
				'slug'			=> 'publication'
			)
		));
	}
	
	public static function add_meta_boxes() {
		add_meta_box( 'citation', 'Citation Information', 'WPScholar\Publication::citation', 'publication', 'normal', 'high' );
	}
	
	public static function register_taxonomy() {
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
	
	public static function citation( $post ) {
        $pub = new EasyInputs(
            [
            'name'  => 'wps-publication',
            'type'  => 'meta'
            ]
        );
		
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_citation_nonce' );
		$format		= get_post_meta( $post->ID, 'scholar_format', true );
		$author		= get_post_meta( $post->ID, 'scholar_author', true );
		$article	= get_post_meta( $post->ID, 'scholar_article', true );
		$title		= get_post_meta( $post->ID, 'scholar_title', true );
		$doi		= get_post_meta( $post->ID, 'scholar_doi', true );
		$translator	= get_post_meta( $post->ID, 'scholar_translator', true );
		$edition	= get_post_meta( $post->ID, 'scholar_edition', true );
		$editor		= get_post_meta( $post->ID, 'scholar_editor', true );
		$pub_city	= get_post_meta( $post->ID, 'scholar_pub_city', true );
		$publisher	= get_post_meta( $post->ID, 'scholar_publisher', true );
		$pub_year	= get_post_meta( $post->ID, 'scholar_pub_year', true );
		$medium		= get_post_meta( $post->ID, 'scholar_medium', true );
		$url		= get_post_meta( $post->ID, 'scholar_url', true );
		
		$formats	= array(
			'book'				=> 'Book',
			'encyclopedia'		=> 'Encyclopaedic Entry',
			'dissertation'		=> 'Dissertation',
			'web'				=> 'Online Article, General',
			'online_journal'	=> 'Online Journal (with DOI)'
		);
		
		/* Form inputs:
		echo '<p><label style="display:block;" for="scholar_format">Format of Cited Work:</label>';
			echo '<select type="text" id="scholar_format" name="scholar_format">';
				echo '<option value="">[[Please Select]]</option>';
				foreach( $formats as $key=>$value ) :
					$selected	= ( $key == $format ) ? ' selected="selected"' : '';
					$option		= sprintf(
						'<option value="%s"%s>%s</option>',
						$key,
						$selected,
						$value
					);
					echo $option;
				endforeach;
			echo '</select></p>';
		*/
		// echo $this->
		echo '<p><label style="display:block;" for="scholar_author">Author(s) of work:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_author" name="scholar_author" value="' . $author . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_article">Article Title:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_article" name="scholar_article" value="' . $article . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_title">Title of Work:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_title" name="scholar_title" value="' . $title . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_doi">DOI:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_doi" name="scholar_doi" value="' . $doi . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_translator">Translator(s):</label>';
			echo '<input style="width: 100%" type="text" id="scholar_translator" name="scholar_translator" value="' . $translator . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_edition">Edition or Reissue Year:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_edition" name="scholar_edition" value="' . $edition . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_editor">Editor(s):</label>';
			echo '<input style="width: 100%" type="text" id="scholar_editor" name="scholar_editor" value="' . $editor . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_pub_city">Publication City:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_pub_city" name="scholar_pub_city" value="' . $pub_city . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_publisher">Publisher:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_publisher" name="scholar_publisher" value="' . $publisher . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_pub_year">Publication Date:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_pub_year" name="scholar_pub_year" value="' . $pub_year . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_medium">Medium of Publication:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_medium" name="scholar_medium" value="' . $medium . '" maxlength="200" /></p>';
		echo '<p><label style="display:block;" for="scholar_url">URL of Online Resource:</label>';
			echo '<input style="width: 100%" type="text" id="scholar_url" name="scholar_url" value="' . $url . '" maxlength="200" /></p>';	
	}
	
	
	public static function save_citation( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_citation_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_citation_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$format		= sanitize_text_field( $_POST['scholar_format'] );
		$author		= sanitize_text_field( $_POST['scholar_author'] );
		$article	= sanitize_text_field( $_POST['scholar_article'] );
		$title		= sanitize_text_field( $_POST['scholar_title'] );
		$doi		= sanitize_text_field( $_POST['scholar_doi'] );
		$translator	= sanitize_text_field( $_POST['scholar_translator'] );
		$edition	= sanitize_text_field( $_POST['scholar_edition'] );
		$editor		= sanitize_text_field( $_POST['scholar_editor'] );
		$pub_city	= sanitize_text_field( $_POST['scholar_pub_city'] );
		$publisher	= sanitize_text_field( $_POST['scholar_publisher'] );
		$pub_year	= sanitize_text_field( $_POST['scholar_pub_year'] );
		$medium		= sanitize_text_field( $_POST['scholar_medium'] );
		$url		= sanitize_text_field( $_POST['scholar_url'] );
		
		add_post_meta($post_id, 'scholar_format', $format, true) or update_post_meta( $post_id, 'scholar_format', $format);
		add_post_meta($post_id, 'scholar_author', $author, true) or update_post_meta( $post_id, 'scholar_author', $author);
		add_post_meta($post_id, 'scholar_article', $article, true) or update_post_meta( $post_id, 'scholar_article', $article);
		add_post_meta($post_id, 'scholar_title', $title, true) or update_post_meta( $post_id, 'scholar_title', $title);
		add_post_meta($post_id, 'scholar_doi', $doi, true) or update_post_meta( $post_id, 'scholar_doi', $doi);
		add_post_meta($post_id, 'scholar_translator', $translator, true) or update_post_meta( $post_id, 'scholar_translator', $translator);
		add_post_meta($post_id, 'scholar_edition', $edition, true) or update_post_meta( $post_id, 'scholar_edition', $edition);
		add_post_meta($post_id, 'scholar_editor', $editor, true) or update_post_meta( $post_id, 'scholar_editor', $editor);
		add_post_meta($post_id, 'scholar_pub_city', $pub_city, true) or update_post_meta( $post_id, 'scholar_pub_city', $pub_city);
		add_post_meta($post_id, 'scholar_publisher', $publisher, true) or update_post_meta( $post_id, 'scholar_publisher', $publisher);
		add_post_meta($post_id, 'scholar_pub_year', $pub_year, true) or update_post_meta( $post_id, 'scholar_pub_year', $pub_year);
		add_post_meta($post_id, 'scholar_medium', $medium, true) or update_post_meta( $post_id, 'scholar_medium', $medium);
		add_post_meta($post_id, 'scholar_url', $url, true) or update_post_meta( $post_id, 'scholar_url', $url);
	}
	
	public function __construct() {
	    // add_action('admin_init', [ $this, 'registerEi' ]);
		add_action('save_post', 'WPScholar\Publication::save_citation');
	}
}
?>
