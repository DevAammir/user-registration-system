<?php
foreach (AVAILABLE_OPTIONS as $key => $value) {
    FORMBUILDER->field([
        'type' => 'checkbox',
        'label' => $value,
        'name' => $key,
        'id' => $key,
        'dbval' => !empty(URS_SETTINGS[$key]) ? URS_SETTINGS[$key] : '',
    ]);
}
echo '<hr>';

