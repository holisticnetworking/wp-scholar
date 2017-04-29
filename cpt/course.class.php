<?php
/**
 * Course
 * 
 * Display an course offered by the academic.
 */
namespace WPScholar;

class Course {
	
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
			'taxonomies'	=> array('category', 'post_tag'),
			'register_meta_box_cb'	=> 'WPScholar\Course::add_meta_boxes'
		));
	}
	
	
	public static function add_meta_boxes() {
		add_meta_box( 'description', 'Course Description', 'WPScholar\Course::description', 'course', 'normal', 'high' );
		add_meta_box( 'schedule', 'Course Schedule', 'WPScholar\Course::schedule', 'course', 'normal', 'high' );
	}
	
	public static function description( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_description_nonce' );
		$number			= get_post_meta( $post->ID, 'scholar_course_number', true );
		$description	= get_post_meta( $post->ID, 'scholar_course_description', true );
		
		// Form inputs:
		echo '<p><label for="scholar_course_number">Course Number:</label>';
			echo '<input type="text" id="scholar_course_number" name="scholar_course_number" value="'.esc_attr( $number ).'" size="50" maxlength="50" />';
		wp_editor( $description, 'scholar_course_description', $settings = array() );
	}
	public static function save_description( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_description_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_description_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$number			= sanitize_text_field( $_POST['scholar_course_number'] );
		$description	= esc_textarea( $_POST['scholar_course_description'] );
		
		// Save the data:
		add_post_meta($post_id, 'scholar_course_number', $number, true) or update_post_meta($post_id, 'scholar_course_number', $number);
		add_post_meta($post_id, 'scholar_course_description', $description, true) or update_post_meta($post_id, 'scholar_course_description', $description);
	}
	
	public static function schedule( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_course_schedule_nonce' );
		$schedules			= get_post_meta( $post->ID, 'scholar_course_schedule', false );
		foreach( $schedules as $schedule ) :
			echo '<fieldset><legend></legend>';
				echo '<p>Days of the week</p>';
				echo '<p>Time</p>';
				echo '<p>Length</p>';
			echo '</fieldset>';
		endforeach;
		echo '<label>New:</label>';
		echo '<fieldset><legend></legend>';
			echo Course::daysOfTheWeek();
			echo '<p>Time</p>';
			echo '<p>Length</p>';
		echo '</fieldset>';
	}
	public static function save_schedule( $post_id ) {
		
	}
	
	private static function daysOfTheWeek() {
		$days	= [ 'mon', 'tu', 'wed', 'thur', 'fri', 'sat', 'sun' ];
		$checks	= '';
		foreach( $days as $day ) :
			$capital	= ucfirst( $day );
			$checks		.= sprintf( '<label for="%1$s"><input id="%1$s" type="checkbox" name="days[]" value="%1$s" />%2$s</label>', $day, $capital );
		endforeach;
		return sprintf(
			'<fieldset><legend>Days of the Week</legend>%s</fieldset>',
			$checks
		);
	}
	
	public function __construct() {
		add_action( 'save_post', 'WPScholar\Course::save_description' );
		add_action( 'save_post', 'WPScholar\Course::save_schedule' );
	}
}
?>