<?php
function user_login_cb()
{
    ob_start();

    // Add nonce field to the form
    wp_nonce_field('urs_login_nonce', 'urs_login_nonce_field');
?>
    <form action="#" method="post" id="urs-user-login-form">
        <div id="response"></div>
        <?php FORMBUILDER->field([
            'type'  => 'text',
            'label' => 'Username',
            'name'  => 'urs_username',
            'id'    => 'urs_username',
            'class' => 'form-control',
        ]); ?>

        <?php FORMBUILDER->field([
            'type'  => 'password',
            'label' => 'Password',
            'name'  => 'urs_password',
            'id'    => 'urs_password',
            'class' => 'form-control',
        ]); ?>

        <?php FORMBUILDER->field([
            'type'  => 'submit',
            'label' => 'Login',
            'name'  => 'urs_user_login_button',
            'id'    => 'urs_user_login_button',
            'class' => 'button button-primary btn btn-primary',
        ]); ?>
    </form>

    <script>
        jQuery(document).ready(function($) {
            let urs_AJAX = '<?php echo admin_url('admin-ajax.php'); ?>';

            // Attach a click event handler to the login button
            $('#urs_user_login_button').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission
// debugger;
                // Get the username and password values
                var username = $('#urs_username').val();
                var password = $('#urs_password').val();

                // Check if username and password are not empty
                if (username === '' || password === '') {
                    $('#response').html('<div class="error">Please enter both username and password.</div>');
                    return;
                }

                // Add nonce to the data
                var data = {
                    action: 'urs_login_user',
                    username: username,
                    password: password,
                    urs_login_nonce: '<?php echo wp_create_nonce("urs_login_nonce"); ?>'
                };

                $.post(urs_AJAX, data, function(response) {
                    console.log(response); // Log the response to the console

                    // Parse the JSON response
                    var result = $.parseJSON(response);

                    // Display the message
                    $('#response').html(result.message);

                    // Redirect if login was successful
                    if (result.status === 200) {
                        window.location.href = result.redirect;
                    } else {
                        jQuery('.error a').attr('href', '<?php echo make_label_to_link(URS_CONFIG['forgot_password']); ?>');
                    }
                });
            });



        });
    </script>
<?php

    $return = ob_get_clean();
    return $return;
}

add_shortcode('urs_user_login', 'user_login_cb');

function urs_login_user()
{
    // Verify the nonce
    if (!isset($_POST['urs_login_nonce']) || !wp_verify_nonce($_POST['urs_login_nonce'], 'urs_login_nonce')) {
        $message = '<div class="error">Nonce verification failed.</div>';
        $status = 400;
    } else {
        // Get the username and password from the AJAX request
        $username = sanitize_user($_POST['username']);
        $password = $_POST['password'];
        $status = 400;
        $message = '';
        $redirect = '';

        // Validate the username and password (you may want to add more robust validation)
        if (empty($username) || empty($password)) {
            $message = '<div class="error">Please enter both username and password.</div>';
            $status = 400;
        } else {
            $user = wp_signon(['user_login' => $username, 'user_password' => $password], false);
            $user_id = $user->ID;
            // Check if the login was successful
            if (is_wp_error($user)) {
                $message = '<div class="error">' . $user->get_error_message() . '</div>';
                $status = 400;
            } else {
                $status = 200;
                $current_time = current_time('mysql');

                update_user_meta($user_id, $meta_key, $current_time);

                $message = 'Successfully logged in. Redirecting...';
                $redirect = home_url();
            }
        }
    }

    // Use the correct variable name here
    echo json_encode(array(
        'status'  => $status,
        'message' => $message,
        'redirect'    => $redirect
    ));
    wp_die();
}

add_action('wp_ajax_urs_login_user', 'urs_login_user');
add_action('wp_ajax_nopriv_urs_login_user', 'urs_login_user'); // For non-logged-in users