<?php
// Load WordPress
require_once('wp-load.php');

// Create the page if it doesn't exist
$page_title = 'Snow Report Output';
$page_slug = 'snow-report-output';

$existing_page = get_page_by_path($page_slug);

if (!$existing_page) {
    $page_data = array(
        'post_title'    => $page_title,
        'post_name'     => $page_slug,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_content'  => '',
        'page_template' => 'page-snow-report-output.php'
    );
    
    $page_id = wp_insert_post($page_data);
    
    if ($page_id) {
        echo "Page created successfully with ID: " . $page_id . "<br>";
        echo "You can now access: http://localhost:8888/SnowTrail/";
    } else {
        echo "Error creating page";
    }
} else {
    echo "Page already exists with ID: " . $existing_page->ID . "<br>";
    echo "You can now access: http://localhost:8888/SnowTrail/";
}
?> 