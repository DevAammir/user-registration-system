<?php

/**
 * Plugin Name: User Registration System by Aamir
 * Description: User Registration System by Aamir
 * Version: 1.0
 * Author: Aammir
 * Author URI: https://127.0.0.1
 * Text Domain: urs
 */


if (!defined('ABSPATH')) {
    die();
}

define('URS_URL', plugin_dir_url(__FILE__)); // Get the plugin URL 
define('URS_DIR', dirname(__FILE__) . '/'); // Get the plugin directory path that is wp-content/plugins/wp-toolkit
define('URS_AJAX', admin_url('admin-ajax.php'));
$current_theme_active = get_stylesheet();
define('THE_CURRENT_THEME', $current_theme_active);

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$wpt_path = 'wp-toolkit/index.php';

if (THE_CURRENT_THEME !== 'wp-lite' || !is_plugin_active($wpt_path)) {
    require_once URS_DIR . 'includes/form-builder.php';
    add_action('admin_enqueue_scripts', 'enqueue_custom_scripts', 10);
    add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

}
require_once URS_DIR . 'includes/config.php';



/* * *
 *  Enqueue scripts and styles
 * * */
function enqueue_custom_scripts()
{
    // Enqueue custom script
    wp_enqueue_script('JS-functions', URS_URL . 'js/functions.js', array('jquery'), '1.0', true);
}



require_once URS_DIR . 'includes/create_necessary_pages.php';
require_once URS_DIR . 'includes/admin.php';
require_once URS_DIR . 'includes/functions.php';




// Activation Hook
register_activation_hook(__FILE__, 'URS_plugin_activation_message');

// Activation Function
function URS_plugin_activation_message() {
    // Display your custom message
    add_option('_plugin_activation_message', '<em>Please set up pages <strong><a href="/wp-admin/admin.php?page=URS-admin" target="_blank">here</a></strong> in orer to use this plugin.</em>');
}

// Display activation message upon plugin activation
function display_activation_message() {
    $message = get_option('_plugin_activation_message');
    
    if ($message) {
        echo '<div class="notice notice-success is-dismissible"><p>' . $message . '</p></div>';
        delete_option('_plugin_activation_message');
    }
}

add_action('admin_notices', 'display_activation_message');