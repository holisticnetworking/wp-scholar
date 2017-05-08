<?php
/**
 * Course
 *
 * Display an course offered by the academic.
 */
namespace WPScholar;

use \EasyInputs\EasyInputs;

class Course
{
    public $days = [];
    
    public $grade_option = [];
    
    public $class_type = [];
    
    public $career = [];
    
    public function registerType()
    {
        register_post_type('course', array(
            'labels'        => array(
                'name'                  => 'Courses',
                'singular_name'             => 'Course',
                'add_new'               => 'Add Course',
                'add_new_item'          => 'Add New Course',
                'edit_item'             => 'Edit Course',
                'new_item'              => 'New Course',
                'all_items'             => 'All Courses',
                'view_item'             => 'View Course',
                'search_items'          => 'Search Courses',
                'not_found'             =>  'No Courses found',
                'not_found_in_trash'    => 'No Courses found in Trash',
                'parent_item_colon'     => '',
                'menu_name'             => 'Courses'
                ),
            'description'   => 'Display a sample of courses you teach.',
            'public'        => true,
            'supports'      => array('title', 'thumbnail'),
            'taxonomies'    => array('category', 'post_tag'),
            'register_meta_box_cb'  => [&$this, 'addMetaBoxes']
        ));
    }
    
    
    public static function addMetaBoxes()
    {
        add_meta_box('number', 'Course Numbers', [&$this, 'number'], 'course', 'normal', 'high');
        add_meta_box('number', 'Course Details', [&$this, 'details'], 'course', 'normal', 'high');
        add_meta_box('description', 'Course Description', [&$this, 'description'], 'course', 'normal', 'high');
        add_meta_box('schedule', 'Course Schedule', [&$this, 'schedule'], 'course', 'normal', 'high');
    }
    
    public static function number($post)
    {
        $ei = new EasyInputs(
            [
            'name'  => 'Course',
            'type'  => 'meta'
            ]
        );
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_number_nonce');
        $numbers         = get_post_meta($post->ID, 'scholar_course_number', false);
        
        // Form inputs:
        if(!empty($numbers)) :
            foreach($numbers as $number) :
                echo '<div class="scholar_row">';
                echo $ei->Form->input( 
                    'course_number',
                    ['value' => $number, 'label' => 'Course Number:', 'multiple' => true]
                );
                echo '</div>';
            endforeach;
        else :
            echo '<div class="scholar_row">';
            echo $ei->Form->input( 
                'course_number',
                ['label' => 'Course Number:', 'multiple' => true]
            );
            echo '</div>';
        endif;
        
        echo '<div class="scholar_prototype">';
        echo $ei->Form->input( 
            'course_number',
            ['label' => 'Course Number:', 'multiple' => true]
        );
        echo '</div>';
        
        // Create new Course Number:
        echo $ei->Form->button(
            'new_number', [
                'label' => false, 
                'wrapper' => false, 
                'value' => "Add New Course Number", 
                "attrs" => [
                    "class" => "button button-primary scholar_new"
                ]
            ]
        );
    }
    public static function saveNumber($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_number_nonce']) || 
            ! wp_verify_nonce($_POST['scholar_number_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        $numbers    = get_post_meta($post_id, 'scholar_course_number', false);
        // Sanitize user input
        foreach($_POST['Course']['course_number'] as $number) :
            $number = sanitize_text_field($number);
            if(!empty($number)) :
                // Save the data:
                if(in_array($number, $numbers)) :
                    update_post_meta($post_id, 'scholar_course_number', $number, $number);
                else :
                    add_post_meta($post_id, 'scholar_course_number', $number, false);
                endif;
            endif;
        endforeach;
    }
    
    public function details($post) {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_details_nonce');
        $credits        = get_post_meta($post->ID, 'scholar_course_credits', true);
        $grading        = get_post_meta($post->ID, 'scholar_course_grading', true);
        // Form inputs:
        echo '<div class="scholar_row">';
            echo sprintf(
                '<div class="scholar_column large-6">%s</div><div class="scholar_column large-6">%s</div>',
                $this->ei->Form->input(
                    'credits',
                    [
                        'label' => __('Credits'),
                        'value' => $credits
                    ]
                ),
                $this->ei->Form->select(
                    'grading',
                    [
                        'label'     => __('Grading Option'),
                        'value'     => $grading,
                        'options'   => $this->grade_option
                    ]
                )
            );
        echo '</div>';
    }
    
    public static function description($post)
    {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_description_nonce');
        $description    = get_post_meta($post->ID, 'scholar_course_description', true);
        echo '<h2>Class Description</h2>';
        echo '<div class="scholar_row">';
            echo $this->ei->Form->input('description', ['type' => 'editor', 'value' => $description]);
        echo '</div>';
    }
    public static function saveDescription($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_description_nonce']) || 
            ! wp_verify_nonce($_POST['scholar_description_nonce'], plugin_basename(__FILE__))) {
            return;
        }
        
        // Sanitize user input
        $description    = esc_textarea($_POST['scholar_course_description']);
        
        // Save the data:
        update_post_meta($post_id, 'scholar_course_description', $description);
    }
    
    public static function schedule($post)
    {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'scholar_course_schedule_nonce');
        $schedules          = get_post_meta($post->ID, 'scholar_course_schedule', false);
        if(!empty($schedules)) :
            foreach($schedules as $schedule) :
            
            endforeach;
        endif;
    }
    public static function saveSchedule($post_id)
    {
    }
    
    public function __construct()
    {
        // Define our L10n compatible variables:
        $this->days   = [ 
            'mon'   => __("Monday"), 
            'tu'    => __("Tuesday"), 
            'wed'   => __("Wednesday"), 
            'thur'  => __("Thursday"), 
            'fri'   => __("Friday"), 
            'sat'   => __("Saturday"), 
            'sun'   => __("Sunday") 
        ];
    
        $this->grade_option    = [
            'graded'        => __("Graded"),
            'stud_opt'      => __("Student Option"),
            'sat_unsat'     => __("Satisfactory/Unsatisfactory"),
            'multi_term'    => __("Multi-term") 
        ];
    
        $this->class_type   = [
            'LEC'   => __("Lecture"),
            'LAB'   => __("Lab"),
            'DIS'   => __("Discussion"),
            'IND'   => __("Independent Study"),
            'SEM'   => __("Seminar"),
            'TA'    => __("Tutor Group"),
        ];
        
        $this->career   = [
            'GRAD'  => __("Undergraduate"),
            'PGRAD' => __("Postgraduate")
        ];
        
        $this->ei = new EasyInputs(
            [
            'name'  => 'Course',
            'type'  => 'meta'
            ]
        );
        
        add_action('save_post', 'WPScholar\Course::saveNumber');
        add_action('save_post', 'WPScholar\Course::saveDescription');
        add_action('save_post', 'WPScholar\Course::saveSchedule');
    }
}
