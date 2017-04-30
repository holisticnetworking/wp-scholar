<?php
/**
 * Feed
 *
 * RSS news feeds
 */
namespace WPScholar;

class Feed
{
    
    public function registerType()
    {
        register_post_type('feed', array(
            'labels'                => array(
                'name'                  => 'Feeds',
                'singular_name'             => 'Feed',
                'add_new'               => 'Add New',
                'add_new_item'          => 'Add New Feed',
                'edit_item'             => 'Edit Feed',
                'new_item'              => 'New Feed',
                'all_items'             => 'All Feeds',
                'view_item'             => 'View Feed',
                'search_items'          => 'Search Feeds',
                'not_found'             =>  'No feeds found',
                'not_found_in_trash'    => 'No feeds found in Trash',
                'parent_item_colon'     => '',
                'menu_name'             => 'Feeds'
                ),
            'description'           => 'Manages a single RSS or Atom feed',
            'public'                => true,
            'supports'              => array('thumbnail'),
            'taxonomies'            => array(),
            'rewrite'               => array(
                'slug'          => 'scholar_feed',
                'with_front'    => false
            ),
            'register_meta_box_cb'  => 'WPScholar\Feed::addMetaBoxes'
        ));
    }
    
    public static function addMetaBoxes()
    {
        add_meta_box('feed-details', 'Feed Details', 'WPScholar\Feed::details', 'feed', 'normal', 'high');
    }
    
    
    public static function details($post)
    {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_feeds_nonce');
        $feed           = get_post_meta($post->ID, 'scholar_feeds', true);
        $url            = isset($feed['url']) ? esc_attr($feed['url']) : '';
        $title          = isset($feed['title']) ? esc_attr($feed['title']) : '';
        $description    = isset($feed['description']) ? esc_attr($feed['description']) : '';

        echo '<p><label for="scholar_feeds_url">Feed URL:</label></p>';
            echo '<p><input class="widefat" type="text" id="scholar_feeds_url" name="scholar_feeds[url]" value="' . $url . '" size="80" maxlength="200" /></p>';
        echo '<p><label for="scholar_feeds_title">Feed Title (overrides what is provided by the feed itself):</label></p>';
            echo '<p><input type="text" id="scholar_feeds_title" name="scholar_feeds[title]" value="' . $title . '" size="80" maxlength="200" /></p>';
        echo '<p><label for="scholar_feeds_description">Feed Description:</label></p>';
            echo '<p><textarea id="scholar_feeds_description" name="scholar_feeds[description]" style="width: 100%; height: 200px;">' . $description . '</textarea></p>';
    }
    public static function saveDetails($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_feeds_nonce']) || ! wp_verify_nonce($_POST['scholar_feeds_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        //sanitize user input
        $feed   = array();
        foreach ($_POST['scholar_feed_details'] as $key => $value) :
            $feed[$key]     = sanitize_text_field($value);
        endforeach;
        if (!empty($feed)) :
            // Save the data:
            add_post_meta($post_id, 'scholar_feeds', $feed, true) or update_post_meta($post_id, 'scholar_feeds', $feed);
        endif;
    }
    
    
    
    public static function replaceTitle($title, $id)
    {
        global $id, $post;
        if ($id && $post && $post->post_type == 'feed') :
            $feed               = get_post_meta($post->ID, 'scholar_feeds', true);
            if (!empty($feed)) :
                $title          = $feed['title'];
            endif;
        endif;
        return $title;
    }
    
    public static function theTitle($title, $id = 1)
    {
        global $id, $post;
        if ($id && $post && $post->post_type == 'feed') :
            $feed   = get_post_meta($id, 'scholar_feed_details');
            $title  = !empty($feed['title']) ? esc_attr($feed['title']) : '';
        endif;
        return $title;
    }
    
    public static function wpTitle($title, $sep)
    {
        global $paged, $page, $post;
        
        $type   = get_post_type($post);
        if ($type == 'feed') :
            $feed   = get_post_meta($id, 'scholar_feed_details');
            $title  = !empty($feed['title']) ? esc_attr($feed['title']) : '';
            if (is_feed()) :
                return $title;
            endif;

            // Add the site name.
            $title .= get_bloginfo('name');

            // Add the site description for the home/front page.
            $site_description = get_bloginfo('description', 'display');
            if ($site_description && ( is_home() || is_front_page() )) {
                $title = "$title $sep $site_description";
            }

            // Add a page number if necessary.
            if ($paged >= 2 || $page >= 2) {
                $title = "$title $sep " . sprintf(__('Page %s', 'twentytwelve'), max($paged, $page));
            }
        endif;
        return $title;
    }
    
    public function __construct()
    {
        add_action('save_post', 'WPScholar\Feed::saveDetails');
        add_action('the_title', 'WPScholar\Feed::theTitle');
        add_filter('wp_title', 'WPScholar\Feed::wpTitle', 1, 2);
    }
}
