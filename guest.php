<?php

require_once 'php/init.php';


// rendering to page
$content = include_template('guest.php', []);

$main_layout = include_template('layout.php', [
    'content' => $content,
    'current_user' => $current_user
]);

print($main_layout);
