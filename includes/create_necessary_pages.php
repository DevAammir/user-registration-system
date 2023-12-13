<?php
register_activation_hook(URS_FILE, 'URS_pages_upon_plugin_activation');

function URS_pages_upon_plugin_activation()
{

    $pages_array = array(
        'Login' => array('content' => '[URS_user_login]', 'template' => ''),
        'Register' => array('content' => '[URS_user_register]', 'template' => ''),
        'Activate Account' => array('content' => '[URS_user_activation_form]', 'template' => ''),
        'Reset Password' => array('content' => '[URS_forgot_password_form]', 'template' => ''),
    );

    // Initialize an array to store messages
    $messages = array();

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
            } else {
                $messages[] = "Error creating page '$page_title'.";
            }
        }
    }

    update_option('urs_admin_notices', $messages);
}

// Hook the activation function
register_activation_hook(__FILE__, 'URS_pages_upon_plugin_activation');

// Hook the function to display admin notices on admin_init
add_action('admin_init', 'urs_display_admin_notices');

function urs_display_admin_notices()
{
    $messages = get_option('urs_admin_notices', array());

    if (!empty($messages)) {
        foreach ($messages as $message) {
            echo '<div class="notice notice-success is-dismissible"><p><em>' . esc_html($message) . '</em></p></div>';
        }

        // Clear the stored messages
        delete_option('urs_admin_notices');
    }
}
