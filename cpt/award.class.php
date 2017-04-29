<?php
/**
 * Award
 *
 * Display an award
 */
namespace WPScholar;

class Award
{
    
    public function register_type()
    {
        register_post_type('award', array(
            'labels'        => array(
                'name'                  => 'Awards and Honors',
                'singular_name'             => 'Award or Honor',
                'add_new'               => 'Add Award or Honor',
                'add_new_item'          => 'Add New Award or Honor',
                'edit_item'             => 'Edit Award or Honor',
                'new_item'              => 'New Award or Honor',
                'all_items'             => 'All Awards and Honors',
                'view_item'             => 'View Award or Honor',
                'search_items'          => 'Search Awards and Honors',
                'not_found'             =>  'No award or honor found',
                'not_found_in_trash'    => 'No award or honor found in Trash',
                'parent_item_colon'     => '',
                'menu_name'             => 'Awards'
                ),
            'description'   =>  'Lauds awards and honors',
            'public'        => true,
            'supports'      => array('thumbnail'),
            'taxonomies'    => array(),
            'register_meta_box_cb'  => [&$this, 'add_meta_boxes']
        ));
    }
    
    public function add_meta_boxes()
    {
        add_meta_box('award-details', 'Award Details', 'WPScholar\Award::details', 'award', 'main', 'high');
    }
    
    
    public static function details($post)
    {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_award_details_nonce');
        $award      = get_post_meta($post->ID, 'scholar_award_details', true);
        
        echo '<p><label for="scholar_award_details_title">Award Title (overrides what is provided by the feed itself):</label></p>';
            echo '<p><input type="text" id="scholar_award_details_title" name="scholar_award_details[title]" value="'.esc_attr($award['title']).'" size="10" maxlength="200" /></p>';
        echo '<p><label for="scholar_award_details_description">Feed Description:</label></p>';
            echo '<p><textarea id="scholar_award_details_description" name="scholar_award_details[description]">'.esc_attr($award['description']).'</textarea></p>';
    }
    public static function save_details($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_award_details_nonce']) || ! wp_verify_nonce($_POST['scholar_award_details_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        //sanitize user input
        $award  = array();
        foreach ($_POST['scholar_award_details'] as $key => $value) :
            $award[$key]    = anitize_text_field($value);
        endforeach;
        if (!empty($award)) :
            // Save the data:
            add_post_meta($post_id, 'scholar_award_details', $award, true) or update_post_meta($post_id, 'scholar_award_details', $award);
        endif;
    }
    
    public function __construct()
    {
        add_action('save_post', 'WPScholar\Award::save_details');
    }
}
