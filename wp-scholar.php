<?php
/**
 * WP Scholar
 * 
 * Effective Websites for Academia
 *
 * @link http://holisticnetworking.net/
 * @version 0.1b
 * @author Thomas J Belknap <tbelknap@holisticnetworking.net>
 * @license All rights reserved.
 */
/*
Plugin Name: WP Scholar
Plugin URI: http://holisticnetworking.net/
Description: Provides custom post types for supporting websites for academia
Version: 0.1b
Author: Thomas J Belknap
Author URI: http://holisticnetworking.net
License: All rights reserved.
*/

namespace WPScholar;
use WPScholar\Award;
use WPScholar\Course;
use WPScholar\Feed;
use WPScholar\Gallery;
use WPScholar\GradStudy;
use WPScholar\News;
use WPScholar\Person;
use WPScholar\Publication;
use WPScholar\Research;

class WPScholar {
	
	public static function admin_pages() {
		$types	= get_option('wp_scholar');
		add_menu_page( 'WP-Scholar Configuration Options', 
		    'WP-Scholar', 
		    'activate_plugins', 
		    'wp-scholar', 
		    '\WPScholar\WPScholar::main_admin_page'
		);
		add_submenu_page('wp-scholar', 
		    'Allowed Content Types',
		    'Content Types', 
		    'activate_plugins', 
		    'content_types', 
		    '\WPScholar\WPScholar::content_types'
		);
		if( is_array( $types ) ) :
			if(!in_array('post', $types)) :
				remove_menu_page('edit.php');
			endif;
			if(!in_array('page', $types)) :
				remove_menu_page('edit.php?post_type=page');
			endif;
		endif;
	}
	
	
	public static function main_admin_page() {
		echo '<h2>WP-Scholar: Rich academic websites, simply made</h2>';
	}
	
	public static function admin_scripts() {
		wp_register_style(
			'scholar_admin_stylesheet',
			plugins_url( 'css/admin.css', __FILE__ ),
			'1.0'
		);
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'scholar_admin_stylesheet' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'scholar_scripts', plugins_url( 'js/scripts.js', __FILE__ ), array('jquery','jquery-ui-autocomplete'), null, true );
	}
	
	public static function content_types() {
		// Handle submissions:
		if(!empty($_POST['submit']) && wp_verify_nonce($_POST['content-types-nonce'],'content_types')) :
			$save	= array();
			foreach($_POST['types'] as $type) :
				$save[]	= sanitize_text_field($type);
			endforeach;
			update_option('wp_scholar', $save);
		endif;
		$types	= get_option('wp_scholar', array('post', 'page'));
		?>
		<div class="wrap">
			<div class="icon32 icon32-posts-post" id="icon-edit"><br></div><h2>Administer Allowed Content Types</h2>
			<p>Users of this website are allowed to use the following content types:</p>
			<form id="content-types" method="post" action="">
				<?php wp_nonce_field( 'content_types', 'content-types-nonce' ) ?>
				<ul>
					<li><input type="checkbox" class="type-select" name="types[]" value="post" id="post" <?php if(in_array('post', $types)) : echo 'checked="checked"'; endif; ?>><label for="post">Blog Posts</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="page" id="page" <?php if(in_array('page', $types)) : echo 'checked="checked"'; endif; ?>><label for="page">Pages</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="publication" id="publication" <?php if(in_array('publication', $types)) : echo 'checked="checked"'; endif; ?>><label for="publication">Publications</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="research" id="research" <?php if(in_array('research', $types)) : echo 'checked="checked"'; endif; ?>><label for="research">Research</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="course" id="course" <?php if(in_array('course', $types)) : echo 'checked="checked"'; endif; ?>><label for="course">Courses</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="grad_study" id="grad_study" <?php if(in_array('grad_study', $types)) : echo 'checked="checked"'; endif; ?>><label for="gradstudy">Grad Studies</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="person" id="person" <?php if(in_array('person', $types)) : echo 'checked="checked"'; endif; ?>><label for="person">People</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="award" id="award" <?php if(in_array('award', $types)) : echo 'checked="checked"'; endif; ?>><label for="award">Awards and Honors</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="feed" id="feed" <?php if(in_array('feed', $types)) : echo 'checked="checked"'; endif; ?>><label for="feed">RSS Feeds</label></li>
					<li><input type="checkbox" class="type-select" name="types[]" value="news" id="news" <?php if(in_array('news', $types)) : echo 'checked="checked"'; endif; ?>><label for="news">News Items</label></li>
				</ul>
				<input type="submit" value="Update Allowed Content Types" class="button button-primary" name="submit" id="submit" />
			</form>
		</div>
		<?php
	}
	
	
	/*
	// For each allowed content type, open the corresponding class file and register the new type:
	*/
	public static function register_content_types() {
		$types	= get_option('wp_scholar');
		if( is_array( $types ) ) : 
			foreach($types as $type) :
				if($type != 'post' && $type != 'page') :
					include( plugin_dir_path(__FILE__) . 'cpt/' . $type . '.class.php' );
					// w00t! This means: convert from under_scores to CamelCase:
					$call	= 'WPScholar\\' . preg_replace_callback( 
					    '/(?:^|_)(.?)/', 
					    function( $i ) { return strtoupper( $i[0] ); }, 
					    $type 
					);
					// echo( $call . '<br />' );
					if(class_exists($call)) :
						$$type	= new $call;
						$$type->register_type();
					endif;
				endif;
			endforeach; 
			if(!in_array('post', $types)) :
				\WPScholar\WPScholar::unregister_type('post');
			endif;
			if(!in_array('page', $types)) :
				\WPScholar\WPScholar::unregister_type('page');
			endif;
		endif;
	}
	
	private static function unregister_type($type) {
		global $wp_post_types;
		// die(print_r($wp_post_types));
		if ( isset( $wp_post_types[ $type ] ) ) {
			unset( $wp_post_types[ $type ] );
			return true;
		}
		return false;
	}
	
	/*
	// Providing default templates where templates do not include them:
	*/
	public static function template( $template ) {
		$type		= get_post_type();
		$registered	= \WPScholar\WPScholar::get_post_types();
		$regex		= \WPScholar\WPScholar::get_post_types('regex');
		$views		= array('single', 'archive');
		// We are currently viewing a DFE custom post type:
		$found	= array_search($type, $registered);
		if( $found !== false ) :
			foreach($views as $view) :
				$theview	= 'is_' . $view;
				// The current template does _not_ have an overriding template file, serve the default:
				if($theview() && !preg_match($regex, $template)) :
					$template	= dirname(__FILE__) . '/tpl/' . $view . '-' . $registered[$found] . '.php';
				endif;
			endforeach;
		endif;
		return $template;
	}
	public static function get_post_types( $format=null ) {
		$result	= array();
		$types	= get_option('wp_scholar');
		foreach($types as $key=>$value) :
			if(!in_array($value, array('post', 'page'))) :
				$result[]	= $value;
			endif;
		endforeach;
		if($format == 'regex') :
			$result	= '/' . implode('|', $result) . '/';
		endif;
		return $result;
	}
	
	public static function flush_rewrite() {
		flush_rewrite_rules();
	}
	
	function cpt_flush_rules( $rule ){
		$rules = get_option( 'rewrite_rules' );

		if ( !isset( $rules[$rule] ) ) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}
	
	
	/*
	 * Change the login background and WordPress logo
	 */
	public static function login_logo() {
		
		echo '<style type="text/css">
			html { 
				background: url(' . plugins_url( 'images/scholar-login-background.png', __FILE__ ) . ') no-repeat center center fixed; 
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			}
			body.login {
				background: transparent !important;
			}
			.login h1 a {
				background-image:url(' . plugins_url( 'images/wp-scholar-logo-transparent.png', __FILE__ ) . ') !important;
				background-size: 320px;
				height: 100px;
				width: 320px;
			}
		</style>';
	}
	public static function login_url() {
		return '//holisticnetworking.net/plugin-wp-scholar';
	}
	
	/* Giddyup */
	public function __construct() {
		add_action( 'init', '\WPScholar\WPScholar::register_content_types' );
		add_action( 'admin_menu', '\WPScholar\WPScholar::admin_pages' );
		add_action( 'admin_enqueue_scripts', '\WPScholar\WPScholar::admin_scripts' );
		add_filter( 'template_include', '\WPScholar\WPScholar::template' );
		
		// Flush rewrite rules:
		register_activation_hook( __FILE__, '\WPScholar\WPScholar::flush_rewrite' );
		register_deactivation_hook( __FILE__, '\WPScholar\WPScholar::flush_rewrite' );
		
		// Themed login:
		add_action('login_head', '\WPScholar\WPScholar::login_logo');
		add_action('login_headerurl', '\WPScholar\WPScholar::login_url');
	}
}

$wps	= new WPScholar;
?>
