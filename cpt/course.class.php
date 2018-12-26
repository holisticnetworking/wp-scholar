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
    
    
    public function addMetaBoxes()
    {
        add_meta_box('number', 'Course Numbers', [&$this, 'number'], 'course', 'side', 'high');
        add_meta_box('details', 'Course Details', [&$this, 'details'], 'course', 'normal', 'high');
        add_meta_box('description', 'Course Description', [&$this, 'description'], 'course', 'normal', 'high');
        add_meta_box('schedule', 'Course Schedule', [&$this, 'schedule'], 'course', 'normal', 'high');
    }
    
    public function number($post)
    {
        // Use nonce for verification
        echo $this->ei->Form->nonce('scholar_number_nonce');
        $numbers         = get_post_meta($post->ID, 'scholar_course_number', false);
        
        // Form inputs:
        if(!empty($numbers)) :
            foreach($numbers as $number) :
                echo '<div class="scholar_row">';
                echo $this->ei->Form->input( 
                    'course_number',
                    ['value' => $number, 'label' => 'Course Number:', 'multiple' => true]
                );
                echo '</div>';
            endforeach;
        else :
            echo '<div class="scholar_row">';
            echo $this->ei->Form->input( 
                'course_number',
                ['label' => 'Course Number:', 'multiple' => true]
            );
            echo '</div>';
        endif;
        
        echo '<div class="scholar_prototype">';
        echo $this->ei->Form->input( 
            'course_number',
            ['label' => 'Course Number:', 'multiple' => true]
        );
        echo '</div>';
        
        // Create new Course Number:
        echo $this->ei->Form->button(
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
    public function saveNumber($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_number_nonce']) || 
            !$this->ei->Form->verifyNonce('scholar_number_nonce')) {
            return;
        }
        // Remove previous values:
        delete_post_meta($post_id, 'scholar_course_number');
        // Sanitize user input
        foreach($_POST['Course']['course_number'] as $number) :
            $number = sanitize_text_field($number);
            if(!empty($number)) :
                add_post_meta($post_id, 'scholar_course_number', $number, false);
            endif;
        endforeach;
    }
    
    public function details($post) {
        // Use nonce for verification
        echo $this->ei->Form->nonce('scholar_course_details_nonce');
        $career         = get_post_meta($post->ID, 'scholar_course_career', true);
        $credits        = get_post_meta($post->ID, 'scholar_course_credits', true);
        $grading        = get_post_meta($post->ID, 'scholar_course_grading', true);
        $image          = get_post_meta($post->ID, 'scholar_course_image', true);

        // Form inputs:
        echo '<div class="scholar_row">';
            echo $this->ei->Form->input(
                'career',
                ['type' => 'radio', 'options' => $this->career, 'value' => $career]
            );
        echo '</div>';
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
    public function saveDetails($post_id) {
        // Refuse without valid nonce:
        if (!isset($_POST['scholar_course_details_nonce'])
            || !$this->ei->Form->verifyNonce('scholar_course_details_nonce') ) {
            return;
        }
        $career    = isset($_POST['Course']['career'])
            ? sanitize_text_field($_POST['Course']['career'])
            : null;
        $credits    = isset($_POST['Course']['credits']) 
            ? sanitize_text_field($_POST['Course']['credits']) 
            : null;
        $grading    = isset($_POST['Course']['grading']) 
            ? sanitize_text_field($_POST['Course']['grading']) 
            : null;
        update_post_meta($post_id, 'scholar_course_career', $career);
        update_post_meta($post_id, 'scholar_course_credits', $credits);
        update_post_meta($post_id, 'scholar_course_grading', $grading);
    }
    
    public function description($post)
    {
        // Use nonce for verification
        echo $this->ei->Form->nonce('scholar_description_nonce');
        $description    = get_post_meta($post->ID, 'scholar_course_description', true);
        echo $this->ei->Form->input('scholar_course_description', ['type' => 'editor', 'value' => $description]);
    }
    public function saveDescription($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_description_nonce']) || 
            !$this->ei->Form->verifyNonce('scholar_description_nonce')) {
            return;
        }
        
        // Sanitize user input
        $description    = esc_textarea($_POST['scholar_course_description']);
        
        // Save the data:
        update_post_meta($post_id, 'scholar_course_description', $description);
    }
    
    public function schedule($post)
    {
        // Use nonce for verification
        echo $this->ei->Form->nonce('scholar_schedule_nonce');
        $schedules  = get_post_meta($post->ID, 'scholar_course_schedule', false);
        $count      = count($schedules);        
        echo '<table class="course_schedules">';
        if(!empty($schedules)) :
            for($x=1; $x<$count; $x++) :
                $schedule   = $schedules[$x];
                echo sprintf(
                    '<tr>
                        <td width="20%" rowspan="4"><div class="scholar_row">%1$s</div></td>
                        <td width="80%"><div class="scholar_row">%2$s</div></td>
                    </tr>
                    <tr>
                        <td class="days_of_week"><div class="scholar_row">%3$s</div></td>
                    </tr>
                    <tr>
                        <td>
                            <label><div class="scholar_row">%4$s</div></td>
                    </tr>
                    <tr>
                        <td><div class="scholar_row">%5$s</div></td>
                    </tr>',
                    $this->ei->Form->input(
                        'course_type',
                        ['type' => 'radio', 'options' => $this->class_type, 'value' => $schedule['type']]
                    ),
                    $this->ei->Form->input(
                        'instructor'
                    ),
                    $this->ei->Form->input(
                        'weekdays',
                        ['type' => 'checkbox', 'options' => $this->days, 'value' => $schedule['weekdays']]
                    ),
                    $this->ei->Form->input(
                        'time',
                        ['class' => 'time', 'value' => $schedule['time']]
                    ),
                    $this->ei->Form->input(
                        'availability',
                        [
                            'type' => 'radio', 
                            'options' => ['yes' => 'Yes', 'no' => 'No'], 
                            'value' => $schedule['availabiity']
                        ]
                    )
                );
            endfor;
        endif;
        echo sprintf(
            '<tr>
                <td rowspan="4">%1$s</td>
                <td><div class="scholar_row">%2$s</div></td>
            </tr>
            <tr>
                <td class="days_of_week"><div class="scholar_row">%3$s</div></td>
            </tr>
            <tr>
                <td>
                    <label><div class="scholar_row">%4$s</div></td>
            </tr>
            <tr>
                <td><div class="scholar_row">%5$s</div></td>
            </tr>',
            $this->ei->Form->input(
                'course_type',
                ['type' => 'radio', 'options' => $this->class_type]
            ),
            $this->ei->Form->input(
                'instructor'
            ),
            $this->ei->Form->input(
                'weekdays',
                ['type' => 'checkbox', 'options' => $this->days]
            ),
            $this->ei->Form->input(
                'time',
                ['class' => 'time']
            ),
            $this->ei->Form->input(
                'availability',
                ['type' => 'radio', 'options' => ['yes' => 'Yes', 'no' => 'No']]
            )
        );
        echo '</table>';
    }
    public function saveSchedule($post_id)
    {
        // Refuse without valid nonce:
        if (! isset($_POST['scholar_schedule_nonce']) || 
            !$this->ei->Form->verifyNonce('scholar_schedule_nonce')) {
            return;
        }
    }

    public function enqueue_uploader() {
        wp_enqueue_media();
        wp_enqueue_script('uploader', plugins_url('easy-inputs/inc/js/uploader.js'));
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
        
        add_action('save_post', [&$this, 'saveDetails']);
        add_action('save_post', [&$this, 'saveNumber']);
        add_action('save_post', [&$this, 'saveDescription']);
        add_action('save_post', [&$this, 'saveSchedule']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_uploader']);
    }
}
