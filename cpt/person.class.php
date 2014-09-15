<?php
/*
// People:		Display information about a specific person.
*/

class ScholarPerson {
	
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
				'not_found' 			=>  'No people found',
				'not_found_in_trash' 	=> 'No people found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'People'
				),
			'description'			=> 'Display information about current grad students or interns with which you are working.',
			'public'				=> true,
			'supports'				=> array('thumbnail'),
			'register_meta_box_cb'	=> 'ScholarPerson::add_meta_boxes'
		));
	}
	
	public static function add_meta_boxes() {
		add_meta_box( 'name', 'Name', 'ScholarPerson::name', 'people', 'normal', 'high' );
		add_meta_box( 'title', 'Titles', 'ScholarPerson::title', 'people', 'normal', 'high' );
		add_meta_box( 'education', 'Education', 'ScholarPerson::education', 'people', 'normal', 'high' );
		add_meta_box( 'address', 'Address', 'ScholarPerson::address', 'people', 'normal', 'high' );
		add_meta_box( 'web', 'Web Addresses', 'ScholarPerson::web', 'people', 'normal', 'high' );
		add_meta_box( 'bio', 'Biography', 'ScholarPerson::bio', 'people', 'normal', 'high' );
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
		
		echo '<p><label for="scholar_name_suffix">Prefix:</label>';
			echo '<input type="text" id="scholar_name_prefix" name="scholar_name_prefix" value="'.esc_attr($prefix).'" size="5" maxlength="20" />';
		echo '<label for="scholar_name_first">First:</label>';
			echo '<input type="text" id="scholar_name_first" name="scholar_name_first" value="'.esc_attr($first).'" size="15" maxlength="100" />';
		echo '<label for="scholar_name_middle">Middle:</label>';
			echo '<input type="text" id="scholar_name_middle" name="scholar_name_middle" value="'.esc_attr($middle).'" size="15" maxlength="100" />';
		echo '<label for="scholar_name_last">Last:</label>';
			echo '<input type="text" id="scholar_name_last" name="scholar_name_last" value="'.esc_attr($last).'" size="15" maxlength="100" />';
		echo '<label for="scholar_name_gender">Gender:</label>';
			echo '<input type="text" id="scholar_name_gender" name="scholar_name_gender" value="'.esc_attr($gender).'" size="2" maxlength="100" />';
		echo '<label for="scholar_name_suffix">Suffix:</label>';
			echo '<input type="text" id="scholar_name_suffix" name="scholar_name_suffix" value="'.esc_attr($suffix).'" size="5" maxlength="20" />';
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
	}
	
	
	
	public static function title( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'scholar_title_nonce' );
		$titles		= get_post_meta( $post->ID, 'scholar_title', true);
		$titles		= !empty($titles) ? $titles : array();
		
		echo '<fieldset class="titles">';
		foreach($titles as $title) :
			echo '<p><label for="scholar_title">Title:</label>';
				echo '<input type="text" id="scholar_title" name="scholar_title[]" value="'.esc_attr($title).'" size="40" maxlength="100" /></p>';
		endforeach;
		echo '<p><label for="scholar_name_gender">New Title:</label>';
			echo '<input type="text" id="scholar_title" name="scholar_title[]" value="" size="40" maxlength="100" /></p>';
		echo '</fieldset>';
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
	
	
	/*
	// Replace Person "titles," which don't exist, with the person's name.
	*/
	public static function replace_title($title, $id) {
		global $id, $post;
		if ( $id && $post && $post->post_type == 'people' ) :
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
		if($type == 'people') :
			$content	= get_post_meta( $post->ID, 'scholar_bio', true );
		endif;
		return $content;
	}
	
	
	
	
	public function ScholarPerson() {
		add_action( 'save_post', 'ScholarPerson::save_name' );
		add_action( 'save_post', 'ScholarPerson::save_address' );
		add_action( 'save_post', 'ScholarPerson::save_web' );
		add_action( 'save_post', 'ScholarPerson::save_bio' );
		add_action( 'save_post', 'ScholarPerson::save_title' );
		add_action( 'save_post', 'ScholarPerson::save_education' );
		
		// Replace WP the_content and the_title with Scholar text:
		add_filter('the_title', 'ScholarPerson::replace_title', 10, 3);
		add_filter('the_content', 'ScholarPerson::replace_content', 10, 3);
	}
	
	
	
}
?>
