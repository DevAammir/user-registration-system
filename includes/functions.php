<?php
include_once 'functions/common_functions.php';
include_once 'functions/create_user.php';
include_once 'functions/update_usermeta.php';
include_once 'functions/_urs_upload_user_image.php';
include_once 'functions/urs_user_login.php';
include_once 'functions/urs_user_register.php'; // 
include_once 'functions/urs_user_activation.php'; //  
include_once 'functions/urs_forgot_password.php'; //  
include_once 'functions/user_logout.php'; //  


add_action('template_redirect', 'redirect_logged_in_users');

/**
 * Redirects logged in users to different pages.
 *
 * @throws None
 * @return void
 */
function redirect_logged_in_users() {
    // Check if the user is logged in
    if (is_user_logged_in()) {
        $login_page = site_url('/login'); // Change this to the URL of your login page
        $register_page = site_url('/register'); // Change this to the URL of your register page

        // If the user is on the login or register pages, redirect them to the home page
        if (is_page('login') || is_page('register') || is_page($login_page) || is_page($register_page)) {
            wp_redirect(home_url());
            exit();
        }
        add_action('wp_head',function(){
            ?>
            <style>.login, .register{display:none !important;}</style>
            <?php
        });

        add_action('wp_footer', function() {
            $login_link = strtolower(str_replace(' ', '-', URS_CONFIG['login']));
            $register_link = strtolower(str_replace(' ', '-', URS_CONFIG['register']));
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var linksToHide = document.querySelectorAll('a[href*="<?php echo $login_link; ?>"], a[href*="<?php echo $register_link; ?>"]');
                    linksToHide.forEach(function(link) {
                        // Check if the link has a parent <li> element
                        var parentLi = link.closest('li');
                        
                        // If it has a parent <li>, hide both the <a> and <li>
                        if (parentLi) {
                            parentLi.style.display = 'none';
                        } else {
                            // If no parent <li>, just hide the <a>
                            link.style.display = 'none';
                        }
                    });
                });
            </script>
            <?php
        });           
        
    }else{
        ?>
        <style>.logout{display:none !important;}</style>
        <?php
          add_action('wp_footer', function() {
            $logout_link = strtolower(str_replace(' ', '-', URS_CONFIG['logout']));
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var linksToHide = document.querySelectorAll('a[href*="<?php echo $logout_link; ?>"]');
                    linksToHide.forEach(function(link) {
                        // Check if the link has a parent <li> element
                        var parentLi = link.closest('li');
                        
                        // If it has a parent <li>, hide both the <a> and <li>
                        if (parentLi) {
                            parentLi.style.display = 'none';
                        } else {
                            // If no parent <li>, just hide the <a>
                            link.style.display = 'none';
                        }
                    });
                });
            </script>
            <?php
        }); 
    }
}

function make_label_to_link($label){
    $path = strtolower(str_replace(' ', '-', $label));
    return site_url('/'.$path);
}