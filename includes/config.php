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
define('URS_REGISTRATION_FIELDS', $registration_fields);


$urs_config = [
    'URS_user_activation_link' => site_url('/actiavate-account'),
    'URS_reset_password_link' => site_url('/reset-password'),
];

define('URS_CONFIG', $urs_config);
update_option('URS_CONFIG', URS_CONFIG);
