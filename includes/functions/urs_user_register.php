<?php
function user_register_cb()
{
    ob_start();
    
?>
<form action="#" method="post" id="urs-user-register-form" enctype="multipart/form-data">
    <?php wp_nonce_field('urs_register_nonce', 'urs_register_nonce'); ?>
    <div id="response"></div>
    <?php foreach (URS_REGISTRATION_FIELDS as $key => $value) : ?>
        <?php $label = str_replace('Billing', ' ',str_replace('_', ' ', ucfirst($key))); $label = trim(ucfirst($label)); ?>
    <?php FORMBUILDER->field([
                'type'  => $value,
                'label' => $label,
                'placeholder' =>  $label,
                'name'  => $key,
                'id'    => $key,
                'class' => 'form-control',
            ]); ?>
    <?php endforeach; ?>

    <input type="hidden" name="user_activation" value="<?php echo make_label_to_link(URS_CONFIG['user_activation']); ?>">

    <?php FORMBUILDER->field([
            'type'  => 'submit',
            'label' => 'Register',
            'name'  => 'urs_user_register_button',
            'id'    => 'urs_user_register_button',
            'class' => 'button button-primary btn btn-primary',
        ]); ?>
</form>
<div id="urs-user-register-success" style="display:none;">
    <?php $activation_link = "<a href='" . make_label_to_link(URS_CONFIG['user_activation']) . "'>Here.</a>"; ?>
    <h3>Registration Successful</h3>
    <p>Thank you for registering with us.</p>
    <p>Please check your email for activation code.</p>
    <p>You can activate your account by clicking on the link in the email. <?php echo $activation_link; ?></p>
    <p>You can now log in with your new account.</p>
    <p><br />
    <h4>Email</h4><br /></p>
    <div id="test_email"></div>
</div>
<script>
jQuery(document).ready(function($) {
    $(document).on('click', '#urs_user_register_button', function(event) {
        event.preventDefault();

        // Clear previous error messages
        $('#response').html('');

        // Validate each field
        var valid = true;

        // Validation for first_name
        var first_name = $('#first_name').val().trim();
        if (first_name === '') {
            displayErrorMessage('Please enter your first name.');
            valid = false;
        }

        // Validation for last_name
        var last_name = $('#last_name').val().trim();
        if (last_name === '') {
            displayErrorMessage('Please enter your last name.');
            valid = false;
        }

        // Validation for email
        var email = $('#email').val().trim();
        if (email === '') {
            displayErrorMessage('Please enter your email.');
            valid = false;
        } else if (!isValidEmail(email)) {
            displayErrorMessage('Please enter a valid email address.');
            valid = false;
        }

        // Validation for password
        var password = $('#password').val();
        if (password === '') {
            displayErrorMessage('Please enter your password.');
            valid = false;
        } else if (password.length < 8) {
            displayErrorMessage('Password must be at least 8 characters.');
            valid = false;
        }

        // Validation for confirm_password
        var confirm_password = $('#confirm_password').val();
        if (confirm_password === '') {
            displayErrorMessage('Please confirm your password.');
            valid = false;
        } else if (confirm_password !== password) {
            displayErrorMessage('Passwords do not match.');
            valid = false;
        }

        // Validation for billing_phone
        var billing_phone = $('#billing_phone').val().trim();
        if (billing_phone === '') {
            displayErrorMessage('Please enter your billing phone number.');
            valid = false;
        }

        // Validation for billing_city
        var billing_city = $('#billing_city').val().trim();
        if (billing_city === '') {
            displayErrorMessage('Please enter your billing city.');
            valid = false;
        }

        // Validation for billing_postcode
        var billing_postcode = $('#billing_postcode').val().trim();
        if (billing_postcode === '') {
            displayErrorMessage('Please enter your billing postcode.');
            valid = false;
        }

        // Validation for billing_state
        var billing_state = $('#billing_state').val().trim();
        if (billing_state === '') {
            displayErrorMessage('Please enter your billing state.');
            valid = false;
        }

        // Validation for billing_country
        var billing_country = $('#billing_country').val();
        if (billing_country === '') {
            displayErrorMessage('Please select your billing country.');
            valid = false;
        }

        // Validation for profile_image
        var profile_image = $('#profile_image').val();
        if (profile_image === '') {
            displayErrorMessage('Please upload your profile image.');
            valid = false;
        }

        // Validation for terms_agreement
        var terms_agreement = $('#terms_agreement').prop('checked');
        if (!terms_agreement) {
            displayErrorMessage('Please agree to the terms.');
            valid = false;
        }

        // If all validations pass, proceed with AJAX submission
        if (valid) {
            var formData = new FormData($('#urs-user-register-form')[0]);
            let urs_AJAX = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
            // Add nonce to the data
            formData.append('action', 'urs_register_user');
            formData.append('urs_register_nonce',
                '<?php echo wp_create_nonce("urs_register_nonce"); ?>');

            // Your AJAX code
            $.ajax({
                url: urs_AJAX,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('AJAX success:', response);

                    // Parse the JSON response
                    var jsonResponse = JSON.parse(response);

                    // Check the response and display messages accordingly
                    if (jsonResponse.status === 200) {
                        console.log('Registration successful');
                        $('#response').html('<div class="success">' + jsonResponse.message +'</div>');
                        $('#urs-user-register-form').remove();
                        $('#urs-user-register-success').show();
                        $('#test_email').html(jsonResponse.email_body);
                    } else {
                        console.log('Registration failed:', jsonResponse.message);
                        console.log('!', jsonResponse);
                        displayErrorMessage(jsonResponse.message ||
                            'Registration failed, please try again later.');
                    }
                },

                error: function(xhr, textstatus, error) {
                    console.error('AJAX error:', error);
                    displayErrorMessage('Registration failed, please try again later.');
                },
            });
        }
    });

    // Helper function to display error messages
    function displayErrorMessage(message) {
        $('#response').html('<div class="error">' + message + '</div>');
        // Scroll to and visually highlight the #response div
        $('html, body').animate({
            scrollTop: $('#response').offset().top - 50 // Adjust the offset as needed
        }, 500);


        // Add a border or background color to make it stand out
        $('#response').css({
            'border': '1px dotted red',
            'color': 'red',
            'padding': '6px',
        });
    }

    // Helper function to check if the email is valid
    function isValidEmail(email) {
        // You can implement a more robust email validation if needed
        return /\S+@\S+\.\S+/.test(email);
    }
});
</script>
<?php

    $return = ob_get_clean();
    return $return;
}

add_shortcode('urs_user_register', 'user_register_cb');




function urs_register_user()
{
    // Verify the nonce
    if (!isset($_POST['urs_register_nonce']) || !wp_verify_nonce($_POST['urs_register_nonce'], 'urs_register_nonce')) {
        $message = '<div class="error">Nonce verification failed.</div>';
        $status = 400;
    } else {
        // Get the form data from the AJAX request
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $billing_phone = sanitize_text_field($_POST['billing_phone']);
        $billing_city = sanitize_text_field($_POST['billing_city']);
        $billing_postcode = sanitize_text_field($_POST['billing_postcode']);
        $billing_state = sanitize_text_field($_POST['billing_state']);
        $billing_country = sanitize_text_field($_POST['billing_country']);
        $profile_image = $_FILES['profile_image'];
        $username = $first_name; // . '-' . $last_name;
        $user_activation = $_POST['user_activation'];
        $status = 400;
        $message = '';
        $redirect = '';

        // Validate the form data (you may want to add more robust validation)
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($billing_phone) || empty($billing_city) || empty($billing_postcode) || empty($billing_state) || empty($billing_country)) {
            $message = '<div class="error">Please fill in all required fields.</div>';
            $status = 400;
        } else {

            if (email_exists($email)) {
                $status = 422;
                $message = 'You already have registerd with us. Try loggin in with your email or username.';
            }

            if (username_exists($username)) {
                $status = 422;
                $message = 'You already have registerd with us. Try loggin in with your email or username.';
            }
            $user_data = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'subscriber',
            ];

            $user_id = urs_create_user($user_data);
            
            // Handle profile image upload
            $IMG_UPL = _urs_upload_user_image([
                'user_identifier' => $user_id,
                'image'           => $profile_image
            ]);

            //  print_r($IMG_UPL);  

            if ($IMG_UPL) {

                // $confirmation_token = md5(uniqid(wp_rand(), true));

                $urs_activation_code = _urs_generate_random_string(5);
                $user_metadata = [
                    'urs_user_status' => 'disabled',
                    'first_name' => $first_name,
                    'billing_first_name' => $first_name,
                    'last_name' => $last_name,
                    'billing_last_name' => $last_name,
                    'billing_phone' => $billing_phone,
                    'billing_city' => $billing_city,
                    'billing_postcode' => $billing_postcode,
                    'billing_state' => $billing_state,
                    'billing_country' => $billing_country,
                    // 'confirmation_token' => $confirmation_token,
                    'urs_activation_code' => $urs_activation_code,
                ];

                foreach ($user_metadata as $key => $value) {
                    update_user_meta($user_id, $key, $value);
                }
                $website = get_bloginfo('name');
                // $activation_link = "<a href='".site_url('/activate')."'>This link</a>";
                $URS_CONFIG = get_option('URS_CONFIG');
                $email_body =  "Hello " . $first_name . " " . $last_name . " and Welcome to  $website  <br /><br />
                You have successfully registered with us.<br />
                 $urs_activation_code is your code for activation.<br />
                You can activate your account <a href='$user_activation'>Here</a> by entering this code.<br />
                <br />
                Regards,
                $website team.";
                // wp_mail($email, 'Welcome to ' . $website, $email_body, 'Content-type: text/html');
                $status = 200;
                $message = 'Registration successful.';
            } else {
                // Handle image upload error
                $message = '<div class="error">Failed to upload profile image.</div>';
                $status = 422;
            }
        }
    }

    echo json_encode(array(
        'status'   => $status,
        'message'  => $message,
        'activation_link' => $activation_link,
        'email_body' => $email_body
    ));
    wp_die();
}

add_action('wp_ajax_urs_register_user', 'urs_register_user');
add_action('wp_ajax_nopriv_urs_register_user', 'urs_register_user');


// FOR ADMIN PANEL

// DISPLAY
function custom_user_profile_columns($columns)
{
    $columns['urs_profile_image'] = 'Profile Image';
    return $columns;
}
add_filter('manage_users_columns', 'custom_user_profile_columns');

/*
// Add custom column data
function custom_user_profile_data($value, $column_name, $user_id) {
    if ($column_name === 'urs_profile_image') {
        $profile_image = get_user_meta($user_id, 'urs_profile_image', true);
        
        // Display the profile image
        if (!empty($profile_image)) {
            $image_html = '<img src="' . esc_url($profile_image) . '" alt="Profile Image" style="max-width: 50px; height: auto;" />';
            return $image_html;
        } else {
            return 'No Image';
        }
    }

    return $value;
}
add_filter('manage_users_custom_column', 'custom_user_profile_data', 10, 3);


//ADD
// Custom form in user profile page
function custom_user_profile_fields($user) {
    ?>
<h3><?php _e('Profile Image', 'your-textdomain'); ?></h3>
<table class="form-table">
    <tr>
        <th><label for="urs_profile_image"><?php _e('Upload Image', 'your-textdomain'); ?></label></th>
        <td>
            <input type="file" name="urs_profile_image" id="urs_profile_image" />
            <span class="description"><?php _e('Upload your profile image.', 'your-textdomain'); ?></span>
        </td>
    </tr>
</table>
<?php
}
add_action('show_user_profile', 'custom_user_profile_fields');
add_action('edit_user_profile', 'custom_user_profile_fields');

// Save custom field
function save_custom_user_profile_fields($user_id) {
    if (isset($_FILES['urs_profile_image'])) {
        $attachment_id = media_handle_upload('urs_profile_image', $user_id);
        if (!is_wp_error($attachment_id)) {
            update_user_meta($user_id, 'urs_profile_image', wp_get_attachment_url($attachment_id));
        }
    }
}
add_action('personal_options_update', 'save_custom_user_profile_fields');
add_action('edit_user_profile_update', 'save_custom_user_profile_fields');
*/