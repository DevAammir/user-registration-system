<?php


// Add a menu item to the WordPress admin menu
function URS_admin_menu()
{
    add_menu_page(
        'Regsistration System',  // Page title
        'Regsistration System',       // Menu title
        'manage_options',     // Capability required to access
        'URS-admin',  // Menu slug
        'URS_admin_page',  // Callback function to render the page
        'dashicons-admin-users',  // Icon for the menu item (change as needed)
        102  // Menu position
    );
}
add_action('admin_menu', 'URS_admin_menu', 1);

// Render the custom admin page
function URS_admin_page()
{
    // pd(URS_SOCIALMEDIA);
?>
    <div id="admin_page" class="wrap">
        <h2>User Registration System</h2>
        <div class="clearfix">&nbsp;</div>

        <form method="post" action="#" id="URS_save_settings_form">
            <ul class="tabs">
                <li class="tab active" onclick="showContent('tab1', this);">Basic Settings</li>

                <li class="tab" onclick="showContent('tab3', this);">Functions List</li>
                <!-- <li class="tab" onclick="showContent('tab3', this);">Tab 3</li> -->
            </ul>

            <div id="tab1" class="content active">
                <?php
                include_once('pages/basic-settings.php');
                ?>
            </div>

            <div id="tab3" class="content">
                <h2>Available Functions List</h2>
                <?php
                include_once('pages/functions-list.php');
                ?>
            </div>

            <!-- <div id="tab3" class="content">
                <h2>Content for Tab 3</h2>
                <p>This is the content for tab 3.</p>
            </div> -->
            <?php
            FORMBUILDER->field([
                'type' => 'button',
                'label' => 'Save Settings',
                'name' => 'URS_save_settings',
                'id' => 'URS_save_settings',
                'class' => 'button button-primary btn btn-primary',
            ]); ?>
        </form>
        <div class="clearfix" id="target">
            <div id="loader" style="display: none;">&nbsp;</div>
        </div>
    </div>

    <script>
        admin_ajax_url = "<?php echo URS_AJAX; ?>";


        (function($) {
            $(document).ready(function() {
                console.log('Welcome to WordPress Toolkit!');

                <?php //$page = wpl_URL . 'includes/page.php'; 
                ?>

                $(document).on('click', '#URS_save_settings', function(e) {
                    e.preventDefault();
                    let form_data = $('#URS_save_settings_form').serialize();
                    // debugger;
                    _AJAX_function_1('#target', admin_ajax_url, 'URS_save_settings', 'POST', form_data, 'json');
                });


            });
        })(jQuery);
        //TABS
        function showContent(tabId, element) {
            // Hide all content
            var contents = document.getElementsByClassName('content');
            for (var i = 0; i < contents.length; i++) {
                contents[i].classList.remove('active');
            }

            // Show the selected tab's content
            document.getElementById(tabId).classList.add('active');

            // Remove 'active' class from all tabs
            var tabs = document.getElementsByClassName('tab');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }

            // Add 'active' class to the clicked tab
            element.classList.add('active');
        }
    </script>
    <style>
        /**TABS CSS**/
        .tabs {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tab {
            margin-right: 10px;
            padding: 10px;
            background-color: #ddd !important;
            cursor: pointer;
            border: 1px solid #BBB;
            color: #777;
        }

        .tab:hover,
        .tab.active {
            background-color: #CCC !important;
            border-color: #AAA !important;
            color: #000;
        }

        .content {
            display: none;
        }

        .content.active {
            display: block;
            min-height: 500px;
            background: #EDEDED;
            padding: 20px;
            border: 1px solid #DDD;
        }

        /**PAGE CSS**/
        .form_builder_row {
            margin: 20px 0;
        }

        .form_builder_row>label {
            width: 300px !important;
            display: block;
            float: left;
            font-size: 16px;
            font-weight: 400;
        }

        .wrap {
            padding: 10px 20px;
        }

        .wrap h2 {
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .hide {
            display: none !important;
        }

        div#loader {
            display: table-cell;
            position: absolute;
            background: rgba(33, 33, 33, 0.5);
            color: #FFF;
            padding: 20px;
            top: 15%;
            left: 1%;
            font-size: 50px;
            width: 1100px;
            height: 35rem;
            text-align: center;
            border: 2px dotted #EEE;
            line-height: 550px;
        }
    </style>

<?php
}

/***
 * SET VALUES
 * ***/
add_action("wp_ajax_URS_save_settings", "URS_save_settings");
add_action("wp_ajax_nopriv_URS_save_settings", "URS_save_settings");
function URS_save_settings()
{
    ob_start();
    $URS_settings = [];
    $URS_socialmedia = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'sm_') === 0) {
            $URS_socialmedia[$key] = $value;
        } else {
            $URS_settings[$key] = $value;
        }
    }




    // update the options in one go
    update_option('URS_settings', $URS_settings);
    update_option('URS_socialmedia', $URS_socialmedia);
?>
    <!-- <div id="settings_updated" class="notice notice-alternate notice-success notice  is-dismissible"> -->
    <p><strong>Settings updated.</strong></p>
    <!-- </div> -->
<?php

    $result = ob_get_clean();
    $status = 1;
    $message = "success";
    $return = json_encode(array('result' => $result, 'status' => $status, 'message' => $message, 'request' => $_REQUEST, 'args' => $args));
    echo $return;
    exit;
}
