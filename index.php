<?php

/**
 * Plugin Name: User Registration System by Aamir
 * Description: User Registration System by Aamir
 * Version: 0.2
 * Author: Aammir
 * Author URI: https://127.0.0.1
 * Text Domain: urs
 */


if (!defined('ABSPATH')) {
    die();
}
define ('URS_FILE',__FILE__);
define('URS_URL', plugin_dir_url(__FILE__)); // Get the plugin URL 
define('URS_DIR', dirname(__FILE__) . '/'); // Get the plugin directory path that is wp-content/plugins/wp-toolkit
define('URS_AJAX', admin_url('admin-ajax.php'));
$current_theme = get_stylesheet();
define('CURRENT_THEME', $current_theme);

if (CURRENT_THEME !== 'wp-lite') {
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
