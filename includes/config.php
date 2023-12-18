<?php



$available_options_array = [ 
    'URS_error_reporting' => 'Turn error reporting on',
];


define('AVAILABLE_OPTIONS', $available_options_array);
if (CURRENT_THEME == 'wp-lite') {
}else{
    
    $fb = new FormBuilder();
    define('FORMBUILDER', $fb);
}
$URS_settings = get_option('URS_settings'); 
define('URS_SETTINGS', $URS_settings); 

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
define('WPT_REGISTRATION_FIELDS', $registration_fields);


$wpt_config = [
    'login' => 'Login',
    'register' => 'Register',
    'user_activation' =>  'Activate Account',
    'forgot_password' =>  'Forgot Password',
    'reset_password' =>  'Reset Password',
    'logout' =>  'Logout',
];

update_option('WPT_CONFIG', $wpt_config);


$wpt_config_saved = get_option('WPT_CONFIG');

define('WPT_CONFIG', $wpt_config_saved);
