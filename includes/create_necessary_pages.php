<?php


function urs_pages_creation()
{

    $pages_array = array(
        @URS_CONFIG['login'] => array('content' => '[urs_user_login]', 'template' => ''),
        @URS_CONFIG['register'] => array('content' => '[urs_user_register]', 'template' => ''),
        @URS_CONFIG['user_activation'] => array('content' => '[urs_user_activation_form]', 'template' => ''),
        @URS_CONFIG['forgot_password'] => array('content' => '[urs_forgot_password_form]', 'template' => ''),
        @URS_CONFIG['reset_password'] => array('content' => '[urs_reset_password_form]', 'template' => ''),
        @URS_CONFIG['logout'] => array('content' => '[logout_user]', 'template' => ''),
    );

    // Initialize an array to store messages
    $messages = array();
    $status = 0;
    // Loop through the array and create/update pages
    foreach ($pages_array as $page_title => $page_data) {
        $page_content = $page_data['content'];
        $page_template = $page_data['template'];

        // Check if the page exists
        $existing_page = get_page_by_title($page_title);

        // If the page exists, update its content and template
        if ($existing_page) {
            $page_id = wp_update_post(array(
                'ID'           => $existing_page->ID,
                'post_content' => $page_content,
                'page_template' => $page_template,
            ));
            $messages[] = "Page '$page_title' updated successfully!";
        } else {
            // If the page doesn't exist, create it
            $page_id = wp_insert_post(array(
                'post_title'   => $page_title,
                'post_content' => $page_content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'page_template' => $page_template,
            ));

            // Optionally, you can echo a message or perform other tasks upon success
            if ($page_id) {
                $messages[] = "Page '$page_title' created successfully for user registration system!";
                $status = 1;
            } else {
                $messages[] = "Error creating page '$page_title'.";
                $status = 0;
            }
        }
    }
    // foreach ($messages as $message) {
    //     echo $message . '<br>';
    // }
return $status;
 }