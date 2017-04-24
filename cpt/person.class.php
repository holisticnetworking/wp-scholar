<?php
/**
 * Person
 */
namespace WPScholar;

class Person {
	
	public function register_type() {
		register_post_type('person', array(
			'labels'				=> array(
				'name'					=> 'People',
				'singular_name'			=> 'Person',
				'add_new' 				=> 'Add Person',
				'add_new_item' 			=> 'Add New Person',
				'edit_item' 			=> 'Edit Person',
				'new_item' 				=> 'New Person',
				'all_items' 			=> 'All People',
				'view_item' 			=> 'View Person',
				'search_items' 			=> 'Search People',
				'not_found' 			=> 'No people found',
				'not_found_in_trash' 	=> 'Nothing in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'People'
				),
			'description'			=> 'Display information about current grad students or interns with which you are working.',
			'public'				=> true,
			'supports'				=> array('thumbnail'),
			'register_meta_box_cb'	=> 'ScholarPerson::add_meta_boxes',
			'has_archive'			=> 'people',
			'rewrite'				=> array(
				'with_front'	=> false,
				'slug'			=> 'person'
			),
			'can_export'			=> true
		));
	}
	
	public static function add_meta_boxes() {
		add_meta_box( 'name', 'Name', 'ScholarPerson::name', 'person', 'normal', 'high' );
		add_meta_box( 'title', 'Titles', 'ScholarPerson::title', 'person', 'normal', 'high' );
		add_meta_box( 'education', 'Education', 'ScholarPerson::education', 'person', 'normal', 'high' );
		add_meta_box( 'address', 'Address', 'ScholarPerson::address', 'person', 'normal', 'high' );
		add_meta_box( 'web', 'Web Addresses', 'ScholarPerson::web', 'person', 'normal', 'high' );
		add_meta_box( 'bio', 'Biography', 'ScholarPerson::bio', 'person', 'normal', 'high' );
		add_meta_box( 'interests', 'Academic Interests', 'ScholarPerson::interests', 'person', 'normal', 'high' );
		add_meta_box( 'display_options', 'Display Options', 'ScholarPerson::display_options', 'person', 'side', 'high' );
		// Rename the "Featured Image"
		remove_meta_box('postimagediv', 'person', 'side');
		add_meta_box('postimagediv', __('Profile Picture'), 'post_thumbnail_meta_box', 'person', 'side', 'high');
	}
	
	
	
	public static function name( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_name_nonce' );
		$prefix		= get_post_meta( $post->ID, 'scholar_prefix', true );
		$first		= get_post_meta( $post->ID, 'scholar_first_name', true );
		$middle		= get_post_meta( $post->ID, 'scholar_middle_name', true );
		$last		= get_post_meta( $post->ID, 'scholar_last_name', true );
		$gender		= get_post_meta( $post->ID, 'scholar_gender', true );
		$suffix		= get_post_meta( $post->ID, 'scholar_suffix', true );
		
		echo sprintf(
			'<div class="scholar_row">
				<div class="scholar_column large-3">
					<label for="scholar_name_suffix">%1$s:</label>
					<input type="text" id="scholar_name_prefix" name="scholar_name_prefix" value="%2$s" size="5" maxlength="20" />
				</div>
				<div class="scholar_column large-3">
					<label for="scholar_name_suffix">%3$s:</label>
					<input type="text" id="scholar_name_first" name="scholar_name_first" value="%4$s" size="5" maxlength="20" />
				</div>
				<div class="scholar_column large-3">
					<label for="scholar_name_suffix">%5$s:</label>
					<input type="text" id="scholar_name_middle" name="scholar_name_middle" value="%6$s" size="5" maxlength="20" />
				</div>
				<div class="scholar_column large-3">
					<label for="scholar_name_suffix">%7$s:</label>
					<input type="text" id="scholar_name_last" name="scholar_name_last" value="%8$s" size="5" maxlength="20" />
				</div>
			<div class="scholar_row">
				<div class="scholar_column large-3">
					<label for="scholar_name_suffix">%9$s:</label>
					<input type="text" id="scholar_name_gender" name="scholar_name_gender" value="%10$s" size="5" maxlength="20" />
				</div>
				<div class="scholar_column large-3">
					<label for="scholar_name_suffix">%11$s:</label>
					<input type="text" id="scholar_name_suffix" name="scholar_name_suffix" value="%12$s" size="5" maxlength="20" />
				</div>
			</div>',
			__( 'Prefix' ),
			esc_attr( $prefix ),
			__( 'First' ),
			esc_attr( $first ),
			__( 'Middle' ),
			esc_attr( $middle ),
			__( 'Last' ),
			esc_attr( $last ),
			__( 'Gender' ),
			esc_attr( $gender ),
			__( 'Suffix' ),
			esc_attr( $suffix )
		);
	}
	public static function save_name( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_name_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_name_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$prefix	= sanitize_text_field( $_POST['scholar_name_prefix'] );
		$first	= sanitize_text_field( $_POST['scholar_name_first'] );
		$middle	= sanitize_text_field( $_POST['scholar_name_middle'] );
		$last	= sanitize_text_field( $_POST['scholar_name_last'] );
		$gender	= sanitize_text_field( $_POST['scholar_name_gender'] );
		$suffix	= sanitize_text_field( $_POST['scholar_name_suffix'] );
		
		// Save the data:
		add_post_meta($post_id, 'scholar_prefix', $prefix, true) or update_post_meta( $post_id, 'scholar_prefix', $prefix);
		add_post_meta($post_id, 'scholar_first_name', $first, true) or update_post_meta( $post_id, 'scholar_first_name', $first);
		add_post_meta($post_id, 'scholar_last_name', $last, true) or update_post_meta( $post_id, 'scholar_last_name', $last);
		add_post_meta($post_id, 'scholar_middle_name', $middle, true) or update_post_meta( $post_id, 'scholar_middle_name', $middle);
		add_post_meta($post_id, 'scholar_gender', $gender, true) or update_post_meta( $post_id, 'scholar_gender', $gender);
		add_post_meta($post_id, 'scholar_suffix', $suffix, true) or update_post_meta( $post_id, 'scholar_suffix', $suffix);
		
		// Update the post slug:
		// unhook this function to prevent infinite looping
        remove_action( 'save_post', 'ScholarPerson::save_name' );
		$post_name			= implode(' ', compact( 'prefix', 'first', 'middle', 'last' ));
		if(!empty($suffix)) :
			$post_name		.= ', ' . $suffix;
		endif;
        // update the post slug
        wp_update_post( array(
            'ID' => $post_id,
            'post_name' => sanitize_title( $post_name ) // do your thing here
        ));
        // re-hook this function
        add_action( 'save_post', 'ScholarPerson::save_name' );
	}
	
	
	
	public static function title( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_title_nonce' );
		$titles		= get_post_meta( $post->ID, 'scholar_title', true);
		$titles		= !empty($titles) ? $titles : array();
		
		foreach($titles as $title) :
			echo sprintf(
				'<div class="scholar_row">
					<div class="scholar_column large-3">
						<label>%s:</label>
					</div>
					<div class="scholar_column large-9">
						<input type="text" id="scholar_title" name="scholar_title[]" value="%s" size="40" maxlength="100" />
					</div>
				</div>',
				__( 'Title' ),
				esc_attr( $title )
			);
		endforeach;
		echo sprintf(
			'<div class="scholar_row">
				<div class="scholar_column large-3">
					<label>%s:</label>
				</div>
				<div class="scholar_column large-9">
					<input type="text" id="scholar_title" name="scholar_title[]" value="" size="40" maxlength="100" />
				</div>
			</div>',
			__( 'Title' )
		); 
		/* 
		echo '<fieldset class="titles">';
		foreach($titles as $title) :
			echo '<p><label for="scholar_title">Title:</label>';
				echo '<input type="text" id="scholar_title" name="scholar_title[]" value="'.esc_attr($title).'" size="40" maxlength="100" /></p>';
		endforeach;
		echo '<p><label for="scholar_name_gender">New Title:</label>';
			echo '<input type="text" id="scholar_title" name="scholar_title[]" value="" size="40" maxlength="100" /></p>';
		echo '</fieldset>';
		*/
		echo '<input type="button" id="new-title" value="Add Title">';
	}
	public static function save_title( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_title_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_title_nonce'], plugin_basename( __FILE__ ) ) ) return;
		$save	= array();
		foreach($_POST['scholar_title'] as $title) :
			if(!empty($title)) :
				$save[]	= sanitize_text_field( $title );
			endif;
		endforeach;
		add_post_meta($post_id, 'scholar_title', $save, true) or update_post_meta($post_id, 'scholar_title', $save );
	}
	
	
	
	
	public static function education( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_education_nonce' );
		$degrees	= get_post_meta( $post->ID, 'scholar_education', true);
		$degrees	= !empty($degrees) ? $degrees : array();
		$count		= count($degrees);
		
		echo '<fieldset class="degrees">';
		foreach($degrees as $key=>$degree) :
			echo '<p><label for="scholar_title">Degree:</label>';
				echo '<input type="text" id="scholar_title" name="scholar_degree[' . $key . '][degree]" value="'.esc_attr($degree['degree']).'" size="20" maxlength="100" />';
			echo '<label for="scholar_title">College:</label>';
				echo '<input type="text" id="scholar_title" name="scholar_degree[' . $key . '][institution]" value="'.esc_attr($degree['institution']).'" size="20" maxlength="100" />';
			echo '<label for="scholar_title">Year:</label>';
				echo '<input type="text" id="scholar_title" name="scholar_degree[' . $key . '][year]" value="'.esc_attr($degree['year']).'" size="5" maxlength="100" />';
			echo '<input type="button" class="delete-degree" name="scholar_degree[' . $key . '][delete]" value="Delete" /></p>';
		endforeach;
		echo '<p><label for="scholar_title">New Degree:</label>';
			echo '<input type="text" id="scholar_title" name="scholar_degree[' . $count . '][degree]" value="" size="20" maxlength="100" />';
		echo '<label for="scholar_title">College:</label>';
			echo '<input type="text" id="scholar_title" name="scholar_degree[' . $count . '][institution]" value="" size="20" maxlength="100" />';
		echo '<label for="scholar_title">Year:</label>';
			echo '<input type="text" id="scholar_title" name="scholar_degree[' . $count . '][year]" value="" size="5" maxlength="100" /></p>';
		echo '</fieldset>';
		echo '<input type="button" id="new-degree" value="Add Degree">';
	}
	public static function save_education( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_education_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_education_nonce'], plugin_basename( __FILE__ ) ) ) return;
		$save	= array();
		foreach($_POST['scholar_degree'] as $degree) :
			if(!empty($degree['degree'])):
				$save[]	= array(
					'degree'		=> sanitize_text_field( $degree['degree'] ),
					'institution'	=> sanitize_text_field( $degree['institution'] ),
					'year'			=> sanitize_text_field( $degree['year'] )
				);
			endif;
		endforeach;
		add_post_meta($post_id, 'scholar_education', $save, true) or update_post_meta( $post_id, 'scholar_education', $save );

	}
	
	
	
	
	public static function address( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_address_nonce' );
		$address	= get_post_meta( $post->ID, 'scholar_address', true );
		$defaults	= array(
			'street1'		=> '',
			'street2'		=> '',
			'city'			=> '',
			'state'			=> '',
			'zip'			=> '',
			'building'		=> '',
			'office'		=> '',	
			'interoffice'	=> '',
			'telephone'		=> '',
			'fax'			=> ''
		);
		if(empty($address)) :	
			$address	= $defaults;
		endif;
		
		// Form inputs:
		echo '<p><label for="scholar_street1">Street Address 1:</label>';
			echo '<input type="text" id="scholar_street1" name="scholar_address[street1]" value="'.esc_attr($address['street1']).'" size="50" maxlength="100" /></p>';
		echo '<p><label for="scholar_street2">Street Address 2:</label>';
			echo '<input type="text" id="scholar_street2" name="scholar_address[street2]" value="'.esc_attr($address['street2']).'" size="50" maxlength="100" />';
		echo '<p><label for="scholar_city">City:</label>';
			echo '<input type="text" id="scholar_city" name="scholar_address[city]" value="'.esc_attr($address['city']).'" size="20" maxlength="100" />';
		echo '<label for="scholar_state">State:</label>';
			echo '<input type="text" id="scholar_state" name="scholar_address[state]" value="'.esc_attr($address['state']).'" size="5" maxlength="5" />';
		echo '<label for="scholar_zip">Zip Code:</label>';
			echo '<input type="text" id="scholar_zip" name="scholar_address[zip]" value="'.esc_attr($address['zip']).'" size="7" maxlength="10" /></p>';
		
		echo '<p><label for="scholar_building">Building:</label>';
			echo '<input type="text" id="scholar_building" name="scholar_address[building]" value="'.esc_attr($address['building']).'" size="20" maxlength="100" />';
		echo '<label for="scholar_office">Office:</label>';
			echo '<input type="text" id="scholar_office" name="scholar_address[office]" value="'.esc_attr($address['office']).'" size="10" maxlength="100" />';
		echo '<label for="scholar_interoffice">Intramural Address Box Number:</label>';
			echo '<input type="text" id="scholar_interoffice" name="scholar_address[interoffice]" value="'.esc_attr($address['interoffice']).'" size="20" maxlength="100" /></p>';
		
		echo '<p><label for="scholar_telephone">Telephone:</label>';
			echo '<input type="text" id="scholar_telephone" name="scholar_address[telephone]" value="'.esc_attr($address['telephone']).'" size="20" maxlength="100" />';
		echo '<label for="scholar_fax">Fax:</label>';
			echo '<input type="text" id="scholar_fax" name="scholar_address[fax]" value="'.esc_attr($address['fax']).'" size="20" maxlength="100" /></p>';
	}
	public static function save_address( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_address_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_address_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$address	= array(
			'street1'		=> sanitize_text_field( $_POST['scholar_address']['street1'] ),
			'street2'		=> sanitize_text_field( $_POST['scholar_address']['street2'] ),
			'city'			=> sanitize_text_field( $_POST['scholar_address']['city'] ),
			'state'			=> sanitize_text_field( $_POST['scholar_address']['state'] ),
			'zip'			=> sanitize_text_field( $_POST['scholar_address']['zip'] ),
			'building'		=> sanitize_text_field( $_POST['scholar_address']['building'] ),
			'office'		=> sanitize_text_field( $_POST['scholar_address']['office'] ),
			'interoffice'	=> sanitize_text_field( $_POST['scholar_address']['interoffice'] ),
			'telephone'		=> sanitize_text_field( $_POST['scholar_address']['telephone'] ),
			'fax'			=> sanitize_text_field( $_POST['scholar_address']['fax'] ),
		);
		
		// Save the data:
		add_post_meta($post_id, 'scholar_address', $address, true) or update_post_meta( $post_id, 'scholar_address', $address);
	}
	
	
	
	public static function web( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_web_nonce' );
		$url	= get_post_meta( $post->ID, 'scholar_url', true );
		$email	= get_post_meta( $post->ID, 'scholar_email', true );
		
		// Form inputs:
		echo '<p><label for="scholar_email">Email:</label>';
			echo '<input type="text" id="scholar_email" name="scholar_email" value="'.esc_attr($email).'" size="50" maxlength="100" /></p>';
		echo '<p><label for="scholar_url">Web Site:</label>';
			echo '<input type="text" id="scholar_url" name="scholar_url" value="'.esc_attr($url).'" size="50" maxlength="100" />';
	}
	public static function save_web( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_address_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_address_nonce'], plugin_basename( __FILE__ ) ) ) return;
		//sanitize user input
		$url	= sanitize_text_field( $_POST['scholar_url'] );
		$email	= sanitize_text_field( $_POST['scholar_email'] );
		
		// Save the data:
		add_post_meta($post_id, 'scholar_url', $url, true) or update_post_meta( $post_id, 'scholar_url', $url);
		add_post_meta($post_id, 'scholar_email', $email, true) or update_post_meta( $post_id, 'scholar_email', $email);
	}
	
	
	
	
	public static function bio( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_bio_nonce' );
		$bio	= get_post_meta( $post->ID, 'scholar_bio', true );
		
		// Form inputs:
		wp_editor( $bio, 'biotext', $settings = array() );
	}
	public static function save_bio( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_bio_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_bio_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$text	= $_POST['biotext'];
		// die(print_r($data));
		
		// Save the data:
		add_post_meta($post_id, 'scholar_bio', $text, true) or update_post_meta($post_id, 'scholar_bio', $text);
	}
	
	
	
	
	public static function interests( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_interests_nonce' );
		$interests	= get_post_meta( $post->ID, 'scholar_interests', true );
		
		// Form inputs:
		wp_editor( $interests, 'intereststext', $settings = array() );
	}
	public static function save_interests( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_interests_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_interests_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$text	= $_POST['intereststext'];
		// die(print_r($data));
		
		// Save the data:
		add_post_meta($post_id, 'scholar_interests', $text, true) or update_post_meta($post_id, 'scholar_interests', $text);
	}
	
	
	
	
	public static function display_options() {
		global $post;
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_person_display_options_nonce' );
		$display	= get_post_meta( $post->ID, 'scholar_person_display', true );
		$index		= $display['index'] > 0 ? 'checked="checked"' : '';
		$page		= $display['single_page'] > 0 ? 'checked="checked"' : '';
		$contact	= $display['contact'] > 0 ? 'checked="checked"' : '';
		// die( print_r( $display ) );
		
		// Form inputs:
		echo '<p><label for="scholar_person_display_index">Display this Person in the Public Index:&nbsp;</label>';
			echo '<input type="checkbox" id="scholar_person_display_index" name="scholar_person_display[index]" value="1" ' . $index . ' /></p>';
		echo '<p><label for="scholar_person_display_page">This Person Has a Person Page:&nbsp;</label>';
			echo '<input type="checkbox" id="scholar_person_display_page" name="scholar_person_display[single_page]" value="1" ' . $page . ' /></p>';
		echo '<p><label for="scholar_person_display_contact">Display Contact Information on Index:&nbsp;</label>';
			echo '<input type="checkbox" id="scholar_person_display_contact" name="scholar_person_display[contact]" value="1" ' . $contact . ' /></p>';
	}
	public static function save_display_options( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['scholar_person_display_options_nonce'] ) || ! wp_verify_nonce( $_POST['scholar_person_display_options_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		$save['index']			= sanitize_text_field( $_POST['scholar_person_display']['index'] );
		$save['single_page']	= sanitize_text_field( $_POST['scholar_person_display']['single_page'] );
		$save['contact']		= sanitize_text_field( $_POST['scholar_person_display']['contact'] );
		// die( print_r( $post_id ) );
		
		// Save the data:
		add_post_meta( $post_id, 'scholar_person_display', $save, true ) or update_post_meta( $post_id, 'scholar_person_display', $save );
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
	// Replace Person "titles," which don't exist, with the person's name.
	*/
	public static function replace_title($title, $id) {
		global $id, $post;
		if ( $id && $post && $post->post_type == 'person' ) :
			$name['prefix']		= get_post_meta( $post->ID, 'scholar_prefix', true );
			$name['first']		= get_post_meta( $post->ID, 'scholar_first_name', true );
			$name['middle']		= get_post_meta( $post->ID, 'scholar_middle_name', true );
			$name['last']		= get_post_meta( $post->ID, 'scholar_last_name', true );
			$suffix				= get_post_meta( $post->ID, 'scholar_suffix', true );
			$title				= implode(' ', $name);
			if(!empty($suffix)) :
				$title			.= ', ' . $suffix;
			endif;
		endif;
		return $title;
	}
	
	/*
	// Replace Person "titles," which don't exist, with the person's name.
	*/
	public static function replace_content($content) {
		global $post;
		$type	= get_post_type( $post );
		if($type == 'person') :
			// remove_filter( 'ScholarPerson::replace_content' );
			// $content	= apply_filters( 'the_content', get_post_meta( $post->ID, 'scholar_bio', true ) );
			$content	= get_post_meta( $post->ID, 'scholar_bio', true );
			// add_filter( 'ScholarPerson::replace_content' );
		endif;
		return $content;
	}

	static function wp_title( $title, $sep ) {
		global $paged, $page, $post;
		
		$type	= get_post_type( $post );
		if( $type == 'person' ) :
			// Replace WP title:
			$name['prefix']		= get_post_meta( $post->ID, 'scholar_prefix', true );
			$name['first']		= get_post_meta( $post->ID, 'scholar_first_name', true );
			$name['middle']		= get_post_meta( $post->ID, 'scholar_middle_name', true );
			$name['last']		= get_post_meta( $post->ID, 'scholar_last_name', true );
			$suffix				= get_post_meta( $post->ID, 'scholar_suffix', true );
			$title				= implode(' ', $name);
			if(!empty($suffix)) :
				$title			.= ', ' . $suffix;
			endif;
			if ( is_feed() ) :
				return $title;
			endif;

			// Add the site name.
			$title .= get_bloginfo( 'name' );

			// Add the site description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) )
				$title = "$title $sep $site_description";

			// Add a page number if necessary.
			if ( $paged >= 2 || $page >= 2 )
				$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );
		endif;
		return $title;
	}
	
	
	public function widgets() {
		register_sidebar( array(
			'name' => __( 'Persons Pages', 'reactive' ),
			'id' => 'person',
			'description' => __( 'For archives and / or single persons, based on your settings.', 'reactive' ),
			'before_widget' => '<aside id="%1$s" class="person-widgets widget-container %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	}
	
	function the_post_thumbnail_caption() {
		global $post;

		$thumbnail_id    = get_post_thumbnail_id($post->ID);
		$thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

		if ($thumbnail_image && isset($thumbnail_image[0])) {
			echo '<div class="wp-caption alignleft">';
				the_post_thumbnail();
				echo '<p class="post-thumbnail-caption">'.$thumbnail_image[0]->post_excerpt.'</p>';
			echo '</div>';
		} else {
			the_post_thumbnail();
		}
	}
	
	
	
	public function __construct() {
		add_action( 'save_post', 'ScholarPerson::save_name' );
		add_action( 'save_post', 'ScholarPerson::save_address' );
		add_action( 'save_post', 'ScholarPerson::save_web' );
		add_action( 'save_post', 'ScholarPerson::save_bio' );
		add_action( 'save_post', 'ScholarPerson::save_title' );
		add_action( 'save_post', 'ScholarPerson::save_education' );
		add_action( 'save_post', 'ScholarPerson::save_display_options' );
		add_action( 'save_post', 'ScholarPerson::save_interests' );
		
		// Replace WP the_content and the_title with Scholar text:
		add_filter( 'the_title', 'ScholarPerson::replace_title', 10, 3 );
		// add_filter( 'wp_title', 'ScholarPerson::wp_title', 1, 2 );
		// add_filter( 'the_content', 'ScholarPerson::replace_content', 1, 3 );
		
		// Custom Sidebar:
		add_action( 'widgets_init', 'ScholarPerson::widgets' );
	}
}
?>
