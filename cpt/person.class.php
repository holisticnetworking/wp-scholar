<?php
/**
 * Person
 */
namespace WPScholar;

use \EasyInputs\EasyInputs;

class Person
{
    
    public function registerType()
    {
        register_post_type('person', array(
            'labels'                => array(
                'name'                  => 'People',
                'singular_name'         => 'Person',
                'add_new'               => 'Add Person',
                'add_new_item'          => 'Add New Person',
                'edit_item'             => 'Edit Person',
                'new_item'              => 'New Person',
                'all_items'             => 'All People',
                'view_item'             => 'View Person',
                'search_items'          => 'Search People',
                'not_found'             => 'No people found',
                'not_found_in_trash'    => 'Nothing in Trash',
                'parent_item_colon'     => '',
                'menu_name'             => 'People'
                ),
            'description'           => 'Display information about current grad students or interns with which you are working.',
            'public'                => true,
            'supports'              => array('thumbnail'),
            'register_meta_box_cb'  => 'WPScholar\Person::addMetaBoxes',
            'has_archive'           => 'people',
            'rewrite'               => array(
                'with_front'    => false,
                'slug'          => 'person'
            ),
            'can_export'            => true
        ));
    }
    
    public static function addMetaBoxes()
    {
        add_meta_box('name', 'Name', 'WPScholar\Person::name', 'person', 'normal', 'high');
        add_meta_box('title', 'Titles', 'WPScholar\Person::title', 'person', 'normal', 'high');
        add_meta_box('education', 'Education', 'WPScholar\Person::education', 'person', 'normal', 'high');
        add_meta_box('address', 'Address', 'WPScholar\Person::address', 'person', 'normal', 'high');
        add_meta_box('web', 'Web Addresses', 'WPScholar\Person::web', 'person', 'normal', 'high');
        add_meta_box('bio', 'Biography', 'WPScholar\Person::bio', 'person', 'normal', 'high');
        add_meta_box('interests', 'Academic Interests', 'WPScholar\Person::interests', 'person', 'normal', 'high');
        add_meta_box('display_options', 'Display Options', 'WPScholar\Person::displayOptions', 'person', 'side', 'high');
        // Rename the "Featured Image"
        remove_meta_box('postimagediv', 'person', 'side');
        add_meta_box('postimagediv', __('Profile Picture'), 'post_thumbnail_meta_box', 'person', 'side', 'high');
    }
    
    
    
    public static function name($post)
    {
        $ei = new EasyInputs([
            'name'  => 'Person',
            'group' => 'name',
            'type'  => 'meta'
        ]);
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_name_nonce');
        $prefix     = get_post_meta($post->ID, 'scholar_prefix', true);
        $first      = get_post_meta($post->ID, 'scholar_first_name', true);
        $middle     = get_post_meta($post->ID, 'scholar_middle_name', true);
        $last       = get_post_meta($post->ID, 'scholar_last_name', true);
        $gender     = get_post_meta($post->ID, 'scholar_gender', true);
        $suffix     = get_post_meta($post->ID, 'scholar_suffix', true);
                
        echo sprintf(
            '<div class="scholar_row">
				<div class="scholar_column large-4">%1$s</div>
				<div class="scholar_column large-4">%2$s</div>
				<div class="scholar_column large-4">%3$s</div>
			</div>
			<div class="scholar_row">
			    <div class="scholar_column large-4">%4$s</div>
				<div class="scholar_column large-4">%5$s</div>
				<div class="scholar_column large-4">%6$s</div>
			</div>',
            $ei->Form->input(
                'prefix',
                ['label' => __('Prefix'), 'value' => $prefix]
            ),
            $ei->Form->input(
                'first',
                ['label' => __('First'), 'value' => $first]
            ),
            $ei->Form->input(
                'middle',
                ['label' => __('Middle'), 'value' => $middle]
            ),
            $ei->Form->input(
                'last',
                ['label' => __('Last'), 'value' => $last]
            ),
            $ei->Form->input(
                'suffix',
                ['label' => __('Suffix'), 'value' => $suffix]
            ),
            $ei->Form->input(
                'gender',
                ['label' => __('Gender'), 'value' => $gender]
            )
        );
    }
    public static function saveName($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_name_nonce']) || ! wp_verify_nonce($_POST['scholar_name_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        //sanitize user input
        $prefix     = isset($_POST['Person']['name'])
            ? sanitize_text_field($_POST['Person']['name']['prefix'])
            : null;
        $first      = isset($_POST['Person']['name'])
            ? sanitize_text_field($_POST['Person']['name']['first'])
            : null;
        $middle     = isset($_POST['Person']['name'])
            ? sanitize_text_field($_POST['Person']['name']['middle'])
            : null;
        $last       = isset($_POST['Person']['name'])
            ? sanitize_text_field($_POST['Person']['name']['last'])
            : null;
        $gender     = isset($_POST['Person']['name'])
            ? sanitize_text_field($_POST['Person']['name']['gender'])
            : null;
        $suffix     = isset($_POST['Person']['name'])
            ? sanitize_text_field($_POST['Person']['name']['suffix'])
            : null;
        
        // Save the data:
        add_post_meta($post_id, 'scholar_prefix', $prefix, true) or update_post_meta($post_id, 'scholar_prefix', $prefix);
        add_post_meta($post_id, 'scholar_first_name', $first, true) or update_post_meta($post_id, 'scholar_first_name', $first);
        add_post_meta($post_id, 'scholar_last_name', $last, true) or update_post_meta($post_id, 'scholar_last_name', $last);
        add_post_meta($post_id, 'scholar_middle_name', $middle, true) or update_post_meta($post_id, 'scholar_middle_name', $middle);
        add_post_meta($post_id, 'scholar_gender', $gender, true) or update_post_meta($post_id, 'scholar_gender', $gender);
        add_post_meta($post_id, 'scholar_suffix', $suffix, true) or update_post_meta($post_id, 'scholar_suffix', $suffix);
        
        // Update the post slug:
        // unhook this function to prevent infinite looping
        remove_action('save_post', 'WPScholar\Person::saveName');
        $post_name          = implode(' ', compact('prefix', 'first', 'middle', 'last'));
        if (!empty($suffix)) :
            $post_name      .= ', ' . $suffix;
        endif;
        // update the post slug
        wp_update_post(array(
            'ID' => $post_id,
            'post_name' => sanitize_title($post_name) // do your thing here
        ));
        // re-hook this function
        add_action('save_post', 'WPScholar\Person::saveName');
    }
    
    
    
    public static function title($post)
    {
        $ei = new EasyInputs([
            'name'  => 'Person',
            'type'  => 'meta'
        ]);
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_title_nonce');
        $titles         = get_post_meta($post->ID, 'scholar_title', true);
        $titles         = !empty($titles) ? $titles : [];
        
        foreach ($titles as $key => $title) :
            echo sprintf(
                '<div class="scholar_row">
					<div class="scholar_column large-3">
						%s
					</div>
					<div class="scholar_column large-9">
						%s
					</div>
				</div>',
                $ei->Form->label('title', sprintf('%s #%d', __('Title'), $key+1)),
                $ei->Form->input(
                    'title',
                    ['label' => false, 'value' => esc_attr($title), 'multiple' => true]
                )
            );
        endforeach;
        echo sprintf(
            '<div class="scholar_row">
				<div class="scholar_column large-3">
					%s
				</div>
				<div class="scholar_column large-9">
					%s
				</div>
			</div>',
            $ei->Form->label('Person[title][]', __('Add new title')),
            $ei->Form->input(
                'title',
                ['label' => false, 'multiple' => true]
            )
        );
        echo $ei->Form->button('add_title', [
            'wrapper'  => '<div class="input">%s</div>',
            'label'    => false,
            'value'    => __('Add Title'),
            'attrs'    => [
                'class' => 'button button-primary'
            ]
        ]);
    }
    public static function saveTitle($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_title_nonce']) || ! wp_verify_nonce($_POST['scholar_title_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        $save   = [];
        foreach ($_POST['Person']['title'] as $title) :
            if (!empty($title)) :
                $save[]     = sanitize_text_field($title);
            endif;
        endforeach;
        add_post_meta($post_id, 'scholar_title', $save, true) or update_post_meta($post_id, 'scholar_title', $save);
    }
    
    
    
    
    public static function education($post)
    {
        $ei = new EasyInputs([
            'name'  => 'Person',
            'type'  => 'meta',
            'group' => 'degree'
        ]);
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_education_nonce');
        $degrees    = get_post_meta($post->ID, 'scholar_education', true);
        $degrees    = !empty($degrees) ? $degrees : array();
        $count      = count($degrees);
        
        echo '<fieldset class="degrees">';
        foreach ($degrees as $key => $degree) :
            $ei->Form->setGroup('degree,' . $key);
            echo sprintf(
                '<div class="scholar_row">
                    <div class="scholar_column large-4">%1$s</div>
                    <div class="scholar_column large-4">%2$s</div>
                    <div class="scholar_column large-4">%3$s</div>
                </div>',
                $ei->Form->input(
                    'degree',
                    ['label' => __('Degree'), 'value' => $degree['degree']]
                ),
                $ei->Form->input(
                    'institution',
                    ['label' => __('Institution'), 'value' => $degree['institution']]
                ),
                $ei->Form->input(
                    'year',
                    ['label' => __('Year'), 'value' => $degree['year']]
                )
            );
        endforeach;
        $ei->Form->setGroup('degree,' . count($degrees));
        echo sprintf(
            '<div class="scholar_row">
				<div class="scholar_column large-4">%1$s</div>
				<div class="scholar_column large-4">%2$s</div>
				<div class="scholar_column large-4">%3$s</div>
			</div>',
            $ei->Form->input(
                'degree',
                ['label' => __('Add new degree')]
            ),
            $ei->Form->input(
                'institution',
                ['label' => __('Institution')]
            ),
            $ei->Form->input(
                'year',
                ['label' => __('Year')]
            )
        );
        echo $ei->Form->button('add_degree', [
            'wrapper'  => '<div class="input">%s</div>',
            'label'    => false,
            'value'    => __('Add Degree'),
            'attrs'    => [
                'class' => 'button button-primary'
            ]
        ]);
    }
    public static function saveEducation($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_education_nonce']) || ! wp_verify_nonce($_POST['scholar_education_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        $save   = array();
        foreach ($_POST['Person']['degree'] as $degree) :
            if (!empty($degree['degree'])) :
                $save[]     = array(
                    'degree'        => sanitize_text_field($degree['degree']),
                    'institution'   => sanitize_text_field($degree['institution']),
                    'year'          => sanitize_text_field($degree['year'])
                );
            endif;
        endforeach;
        add_post_meta($post_id, 'scholar_education', $save, true) or update_post_meta($post_id, 'scholar_education', $save);
    }
    
    
    
    
    public static function address($post)
    {
        $ei = new EasyInputs([
            'name'  => 'Person',
            'type'  => 'meta',
            'group' => 'address'
        ]);
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_address_nonce');
        $address    = get_post_meta($post->ID, 'scholar_address', true);
        $defaults   = array(
            'street1'       => '',
            'street2'       => '',
            'city'          => '',
            'state'             => '',
            'zip'           => '',
            'building'      => '',
            'office'        => '',
            'interoffice'   => '',
            'telephone'         => '',
            'fax'           => ''
        );
        if (empty($address)) :
            $address    = $defaults;
        endif;
        
        // Form inputs:
        echo '<div class="scholar_row">';
            echo $ei->Form->inputs(
                [
                'street1'   => ['label' => 'Street 1', 'value' => $address['street1']],
                'street2'   => ['label' => 'Street 2', 'value' => $address['street2']],
                ],
                ['fieldset' => false]
            );
        echo '</div>';
        echo '<div class="scholar_row">';
            echo $ei->Form->inputs(
                [
                'city'   => [
                    'label' => 'City',
                    'wrapper' => '<div class="scholar_column large-4">%1$s</div>',
                    'value' => $address['city']
                ],
                'state'   => [
                    'label' => 'State',
                    'wrapper' => '<div class="scholar_column large-4">%1$s</div>',
                    'value' => $address['state']
                ],
                'zip'   => [
                    'label' => 'Postal Code',
                    'wrapper' => '<div class="scholar_column large-4">%1$s</div>',
                    'value' => $address['zip']
                ]
                ],
                ['fieldset' => false]
            );
        echo '</div>';
        echo '<div class="scholar_row">';
            echo $ei->Form->inputs(
                [
                'building'   => [
                    'label' => 'Building',
                    'wrapper' => '<div class="scholar_column large-4">%1$s</div>',
                    'value' => $address['building']
                ],
                'office'   => [
                    'label' => 'Office',
                    'wrapper' => '<div class="scholar_column large-4">%1$s</div>',
                    'value' => $address['office']
                ],
                'interoffice'   => [
                    'label' => 'Interoffice Address',
                    'wrapper' => '<div class="scholar_column large-4">%1$s</div>',
                    'value' => $address['interoffice']
                ]
                ],
                ['legend' => __('Building Address')]
            );
        echo '</div>';
        echo '<div class="scholar_row">';
            echo $ei->Form->inputs(
                [
                'telephone'   => [
                    'label' => 'Telephone Number',
                    'wrapper' => '<div class="scholar_column large-6">%1$s</div>',
                    'value' => $address['building']
                ],
                'fax'   => [
                    'label' => 'Fax Number',
                    'wrapper' => '<div class="scholar_column large-6">%1$s</div>',
                    'value' => $address['office']
                ]
                ],
                ['legend' => __('Phone and Fax')]
            );
        echo '</div>';
    }
    public static function saveAddress($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_address_nonce']) || ! wp_verify_nonce($_POST['scholar_address_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        //sanitize user input
        $address    = array(
            'street1'       => isset($_POST['Person']['address']['street1'])
                ? sanitize_text_field($_POST['Person']['address']['street1'])
                : null,
            'street2'       =>  isset($_POST['Person']['address']['street2'])
                ? sanitize_text_field($_POST['Person']['address']['street2'])
                : null,
            'city'          =>  isset($_POST['Person']['address']['city'])
                ? sanitize_text_field($_POST['Person']['address']['city'])
                : null,
            'state'         =>  isset($_POST['Person']['address']['state'])
                ? sanitize_text_field($_POST['Person']['address']['state'])
                : null,
            'zip'           =>  isset($_POST['Person']['address']['zip'])
                ? sanitize_text_field($_POST['Person']['address']['zip'])
                : null,
            'building'      =>  isset($_POST['Person']['address']['building'])
                ? sanitize_text_field($_POST['Person']['address']['building'])
                : null,
            'office'        =>  isset($_POST['Person']['address']['office'])
                ? sanitize_text_field($_POST['Person']['address']['office'])
                : null,
            'interoffice'   =>  isset($_POST['Person']['address']['interoffice'])
                ? sanitize_text_field($_POST['Person']['address']['interoffice'])
                : null,
            'telephone'     =>  isset($_POST['Person']['address']['telephone'])
                ? sanitize_text_field($_POST['Person']['address']['telephone'])
                : null,
            'fax'           =>  isset($_POST['Person']['address']['fax'])
                ? sanitize_text_field($_POST['Person']['address']['fax'])
                : null,
        );
        
        // Save the data:
        add_post_meta($post_id, 'scholar_address', $address, true) or update_post_meta($post_id, 'scholar_address', $address);
    }
    
    
    
    public static function web($post)
    {
        $ei = new EasyInputs([
            'name'  => 'Person',
            'type'  => 'meta',
            'group' => 'web'
        ]);
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_web_nonce');
        $url        = get_post_meta($post->ID, 'scholar_url', true);
        $email      = get_post_meta($post->ID, 'scholar_email', true);
        $twitter    = get_post_meta($post->ID, 'scholar_twitter', true);
        $facebook   = get_post_meta($post->ID, 'scholar_facebook', true);
        $instagram  = get_post_meta($post->ID, 'scholar_instagram', true);
        $linkedin    = get_post_meta($post->ID, 'scholar_linkedin', true);
        
        // Form inputs:
        echo '<div class="scholar_row">';
            echo $ei->Form->inputs(
                [
                'url'       => ['value' => $url, 'label' => __('Web')],
                'email'     => ['value' => $email, 'label' => __('Email')],
                'twitter'   => ['value' => $twitter, 'label' => __('Twitter (just the @username)')],
                'facebook'  => ['value' => $facebook, 'label' => 'Facebook (full URL)'],
                'instagram' => ['value' => $instagram, 'label' => 'Instagram (just the @username)'],
                'linkedin'  => ['value' => $linkedin, 'label' => 'LinkedIn (full URL)']
                ],
                ['legend'   => false]
            );
        echo '</div>';
    }
    public static function saveWeb($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_web_nonce']) || ! wp_verify_nonce($_POST['scholar_web_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        // Sanitize user input
        $url        = isset($_POST['Person']['web']['url'])
            ? sanitize_text_field($_POST['Person']['web']['url'])
            : null;
        $email      = isset($_POST['Person']['web']['email'])
            ? sanitize_text_field($_POST['Person']['web']['email'])
            : null;
        $twitter    = isset($_POST['Person']['web']['twitter'])
            ? sanitize_text_field($_POST['Person']['web']['twitter'])
            : null;
        $facebook   = isset($_POST['Person']['web']['facebook'])
            ? sanitize_text_field($_POST['Person']['web']['facebook'])
            : null;
        $instagram  = isset($_POST['Person']['web']['instagram'])
            ? sanitize_text_field($_POST['Person']['web']['instagram'])
            : null;
        $linkedin   = isset($_POST['Person']['web']['linkedin'])
            ? sanitize_text_field($_POST['Person']['web']['linkedin'])
            : null;
        // die( print_r( compact('url', 'email', 'twitter', 'facebook', 'instagram', 'linkedin') ) );
        // Save the data:
        update_post_meta($post_id, 'scholar_url', $url);
        update_post_meta($post_id, 'scholar_email', $email);
        update_post_meta($post_id, 'scholar_twitter', $twitter);
        update_post_meta($post_id, 'scholar_facebook', $facebook);
        update_post_meta($post_id, 'scholar_instagram', $instagram);
        update_post_meta($post_id, 'scholar_linkedin', $linkedin);
    }
    
    
    
    
    public static function bio($post)
    {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_bio_nonce');
        $bio    = get_post_meta($post->ID, 'scholar_bio', true);
        
        // Form inputs:
        wp_editor($bio, 'biotext', $settings = array());
    }
    public static function saveBio($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_bio_nonce']) || ! wp_verify_nonce($_POST['scholar_bio_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        //sanitize user input
        $text   = $_POST['biotext'];
        // die(print_r($data));
        
        // Save the data:
        add_post_meta($post_id, 'scholar_bio', $text, true) or update_post_meta($post_id, 'scholar_bio', $text);
    }
    
    
    
    
    public static function interests($post)
    {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_interests_nonce');
        $interests  = get_post_meta($post->ID, 'scholar_interests', true);
        
        // Form inputs:
        wp_editor($interests, 'intereststext', $settings = array());
    }
    public static function saveInterests($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_interests_nonce']) || ! wp_verify_nonce($_POST['scholar_interests_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        //sanitize user input
        $text   = $_POST['intereststext'];
        // die(print_r($data));
        
        // Save the data:
        add_post_meta($post_id, 'scholar_interests', $text, true) or update_post_meta($post_id, 'scholar_interests', $text);
    }
    
    
    
    
    public static function displayOptions()
    {
        global $post;
        $ei = new EasyInputs([
            'name'  => 'Person',
            'type'  => 'meta'
        ]);
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_person_display_options_nonce');
        $index      = get_post_meta($post->ID, 'scholar_person_index', true);
        $page       = get_post_meta($post->ID, 'scholar_person_page', true);
        $search     = get_post_meta($post->ID, 'scholar_person_search', true);
        $contact    = get_post_meta($post->ID, 'scholar_person_contact', true);
        // die( print_r( $display ) );
        
        // Form inputs:
        echo $ei->Form->checkbox(
            'display',
            [
                'options' => [
                    ['value' => 'index', 'name' => __('Display in Index'), 'selected' => $index],
                    ['value' => 'page', 'name' => __('Grant a Personal Page'), 'selected' => $page],
                    ['value' => 'search', 'name' => __('Display in Search'), 'selected' => $search],
                    ['value' => 'contact', 'name' => __('Display Contact Information'), 'selected' => $contact]
                ],
                'label' => false
            ]
        );
    }
    public static function saveDisplayOptions($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_person_display_options_nonce']) || ! wp_verify_nonce($_POST['scholar_person_display_options_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        //sanitize user input
        if (isset($_POST['Person']['display'])) :
            $display    = $_POST['Person']['display'];
            $index      = in_array('index', $display) ? 1 : 0;
            $page       = in_array('page', $display) ? 1 : 0;
            $search     = in_array('search', $display) ? 1 : 0;
            $contact    = in_array('contact', $display) ? 1 : 0;
        endif;
        
        // Save the data:
        update_post_meta($post_id, 'scholar_person_index', $index);
        update_post_meta($post_id, 'scholar_person_page', $page);
        update_post_meta($post_id, 'scholar_person_search', $search);
        update_post_meta($post_id, 'scholar_person_contact', $contact);
    }
    
    /*
	// Replace Person "titles," which don't exist, with the person's name.
	*/
    public static function replaceTitle($title, $id)
    {
        global $id, $post;
        if ($id && $post && $post->post_type == 'person') :
            $name['prefix']         = get_post_meta($post->ID, 'scholar_prefix', true);
            $name['first']      = get_post_meta($post->ID, 'scholar_first_name', true);
            $name['middle']         = get_post_meta($post->ID, 'scholar_middle_name', true);
            $name['last']       = get_post_meta($post->ID, 'scholar_last_name', true);
            $suffix                 = get_post_meta($post->ID, 'scholar_suffix', true);
            $title              = implode(' ', $name);
            if (!empty($suffix)) :
                $title          .= ', ' . $suffix;
            endif;
        endif;
        return $title;
    }
    
    /*
	// Replace Person "titles," which don't exist, with the person's name.
	*/
    public static function replaceContent($content)
    {
        global $post;
        $type   = get_post_type($post);
        if ($type == 'person') :
            // remove_filter( 'WPScholar\Person::replace_content' );
            // $content	= apply_filters( 'the_content', get_post_meta( $post->ID, 'scholar_bio', true ) );
            $content    = get_post_meta($post->ID, 'scholar_bio', true);
            // add_filter( 'WPScholar\Person::replace_content' );
        endif;
        return $content;
    }

    public static function wpTitle($title, $sep)
    {
        global $paged, $page, $post;
        
        $type   = get_post_type($post);
        if ($type == 'person') :
            // Replace WP title:
            $name['prefix']         = get_post_meta($post->ID, 'scholar_prefix', true);
            $name['first']      = get_post_meta($post->ID, 'scholar_first_name', true);
            $name['middle']         = get_post_meta($post->ID, 'scholar_middle_name', true);
            $name['last']       = get_post_meta($post->ID, 'scholar_last_name', true);
            $suffix                 = get_post_meta($post->ID, 'scholar_suffix', true);
            $title              = implode(' ', $name);
            if (!empty($suffix)) :
                $title          .= ', ' . $suffix;
            endif;
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
    
    
    public function widgets()
    {
        register_sidebar(array(
            'name' => __('Persons Pages', 'reactive'),
            'id' => 'person',
            'description' => __('For archives and / or single persons, based on your settings.', 'reactive'),
            'before_widget' => '<aside id="%1$s" class="person-widgets widget-container %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
    
    public function thePostThumbnailCaption()
    {
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
    
    
    
    public function __construct()
    {
        add_action('save_post', 'WPScholar\Person::saveName');
        add_action('save_post', 'WPScholar\Person::saveAddress');
        add_action('save_post', 'WPScholar\Person::saveWeb');
        add_action('save_post', 'WPScholar\Person::saveBio');
        add_action('save_post', 'WPScholar\Person::saveTitle');
        add_action('save_post', 'WPScholar\Person::saveEducation');
        add_action('save_post', 'WPScholar\Person::saveDisplayOptions');
        add_action('save_post', 'WPScholar\Person::saveInterests');
        
        // Replace WP the_content and the_title with Scholar text:
        add_filter('the_title', 'WPScholar\Person::replaceTitle', 10, 3);
        // add_filter( 'wp_title', 'WPScholar\Person::wpTitle', 1, 2 );
        // add_filter( 'the_content', 'WPScholar\Person::replaceContent', 1, 3 );
        
        // Custom Sidebar:
        add_action('widgets_init', 'WPScholar\Person::widgets');
    }
}
