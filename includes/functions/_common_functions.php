<?php

function _URS_set_featured_image_via_image_url($post_id, $image)
{
    // Include necessary files
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';



    // If $image is a URL, try to download and set it as the featured image
    if (filter_var($image, FILTER_VALIDATE_URL)) {
        $response = wp_remote_get($image);

        // Check for errors
        if (is_wp_error($response)) {
            return 'Error downloading image: ' . $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);

        // Extract the file extension from the URL
        $file_extension = pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION);

        // Create a temporary file with a specific extension
        $upload = wp_upload_bits(basename($image), null, $body, $file_extension);

        if (!$upload['error']) {
            $file_array = array(
                'name'     => $upload['file'],
                'tmp_name' => $upload['file'],
            );

            $attachment_id = media_handle_sideload($file_array, $post_id, '', array('test_form' => false));

            // Check for errors
            if (is_wp_error($attachment_id)) {
                return 'Error uploading image: ' . $attachment_id->get_error_message();
            }

            // Set the post thumbnail
            set_post_thumbnail($post_id, $attachment_id);

            return 'Featured image set successfully. Attachment ID: ' . $attachment_id;
        } else {
            return 'Error uploading image: ' . $upload['error'];
        }
    }

    // If $image is an attachment ID, set it as the featured image
    if (is_numeric($image)) {
        set_post_thumbnail($post_id, $image);
        return 'Featured image set successfully. Attachment ID: ' . $image;
    }

    return 'Invalid featured image provided';
}


/**
 * Set featured image for a post.
 *
 * @param int $post_id       Post ID.
 * @param int $attachment_id Attachment ID.
 *
 * @return bool              True on success, false on failure.
 */
function _URS_set_featured_image($post_id, $attachment_id)
{
    // Check if the post ID and attachment ID are valid.
    if (empty($post_id) || empty($attachment_id)) {
        return 0;
    }

    // Set the featured image.
    $result = set_post_thumbnail($post_id, $attachment_id);

    // Return the result.
    return 1;
}






// Add a filter to check user activation status
add_filter('wp_authenticate_user', 'custom_check_user_activation_status', 10, 2);

function custom_check_user_activation_status($user, $password) {
    // Check if the user exists
    if ($user instanceof WP_User) {
        // Check if the user has the activation status meta field
        $activation_status = get_user_meta($user->ID, 'URS_user_status', true);

        // Check if the activation status is 'disabled'
        if ($activation_status === 'disabled' || $activation_status === 0) {
            // Display a message and prevent login
            return new WP_Error('account_disabled', __('Your account is disabled. Please activate your account to log in.<br>Check your inbox for activation code.'));
        }
    }

    // If everything is okay, return the user
    return $user;
}


function _URS_generate_random_string($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}