<?php 
add_shortcode('logout_user', 'logout_user_shortcode');

function logout_user_shortcode() {
    ob_start();

    // Check if the user is logged in
    if (is_user_logged_in()) {
        wp_logout();
        wp_redirect(home_url());
        exit();
    } else {
        // Display a message for users who are not logged in
        echo 'You are not logged in.';
        wp_redirect(home_url());
        exit();
    }

    return ob_get_clean();
}
