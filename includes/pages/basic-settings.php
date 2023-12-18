<p><u><strong>Note:</strong> Please enter pages names and the plugin will create the pages for you.</u></p>
<?php
foreach (AVAILABLE_OPTIONS as $key => $value) {
    FORMBUILDER->field([
        'type' => 'text',
        'label' => $value,
        'name' => $key,
        'id' => $key,
        'dbval' => !empty(WPT_CONFIG[$key]) ? WPT_CONFIG[$key] : '',
    ]);
}
echo '<hr>';

