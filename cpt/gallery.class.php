<?php
/**
 * Gallery
 *
 * Display a collection of images
 */
namespace WPScholar;

class Gallery
{
    
    public function register_type()
    {
        register_post_type('gallery', array(
            'labels'        => array(
                'name'                  => 'Galleries',
                'singular_name'             => 'Gallery',
                'add_new'               => 'Add New',
                'add_new_item'          => 'Add New Gallery',
                'edit_item'             => 'Edit Gallery',
                'new_item'              => 'New Gallery',
                'all_items'             => 'All Galleries',
                'view_item'             => 'View Gallery',
                'search_items'          => 'Search Galleries',
                'not_found'             => 'No galleries found',
                'not_found_in_trash'    => 'No galleries found in Trash',
                'parent_item_colon'     => '',
                'menu_name'             => 'Galleries'
                ),
            'description'   => 'Used to display media galleries',
            'public'        => true
        ));
    }
    
    public function __construct()
    {
    }
}
