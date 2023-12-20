<?php



$available_options_array = [ 
    'login' => 'Login',
    'register' => 'Register',
    'user_activation' =>  'Activate Account',
    'forgot_password' =>  'Forgot Password',
    'reset_password' =>  'Reset Password',
    'logout' =>  'Logout',
];


define('AVAILABLE_OPTIONS', $available_options_array);
if (THE_CURRENT_THEME == 'wp-lite') {
}else{
    
    $fb = new FormBuilder();
    define('FORMBUILDER', $fb);
}


$registration_fields = [
    'first_name' => 'text',
    'last_name' => 'text',
    'email' => 'email',
    'password' => 'password',
    'confirm_password' => 'password',
    'billing_phone' => 'text',
    'billing_city' => 'text',
    'billing_postcode' => 'text',
    'billing_state' => 'text',
    'billing_country' => 'countries',
    'profile_image' => 'image',
    'terms_agreement' => 'checkbox',
];
define('URS_REGISTRATION_FIELDS', $registration_fields);


$urs_config_saved = get_option('URS_CONFIG');

define('URS_CONFIG', $urs_config_saved);
