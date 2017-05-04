<?php
/**
 * Award
 *
 * Display an award
 */
namespace WPScholar;

use \EasyInputs\EasyInputs;

class Award
{
    
    public function registerType()
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
            'description'           =>  'Lauds awards and honors',
            'public'                => true,
            'supports'              => ['title', 'thumbnail'],
            'taxonomies'            => [],
            'register_meta_box_cb'  => [&$this, 'addMetaBoxes']
        ));
    }
    
    public function addMetaBoxes()
    {
        add_meta_box('details', 'Details', 'WPScholar\Award::details', 'award', 'normal', 'high');
    }
    
    
    public static function details($post)
    {
        $ei = new EasyInputs([
            'name'  => 'Award',
            'type'  => 'meta'
        ]);
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_award_details_nonce');
        $date           = get_post_meta($post->ID, 'scholar_award_date', true);
        $description    = get_post_meta($post->ID, 'scholar_award_description', true);
        
        echo sprintf(
            '<div class="scholar_row"><div class="scholar_column large-12">%s</div>', 
            $ei->Form->input('date', ['label' => 'Award or Honor Date', 'value' => $date])
        );
        echo '<h2>Award Description</h2>';
        echo $ei->Form->input('description', ['type' => 'editor', 'value' => $description]);
    }
    
    public static function saveDetails($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_award_details_nonce']) 
            || ! wp_verify_nonce($_POST['scholar_award_details_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        //sanitize user input
        $award          = [];
        $date           = !empty($_POST['Award']['date'])
            ? sanitize_text_field($_POST['Award']['date'])
            : null;
        $description    = !empty($_POST['description'])
            ? esc_textarea($_POST['description'])
            : null;
        // Save the data:
        update_post_meta($post_id, 'scholar_award_date', $date);
        update_post_meta($post_id, 'scholar_award_description', $description);
    }
    
    public function __construct()
    {
        add_action('save_post', 'WPScholar\Award::saveDetails');
    }
}
