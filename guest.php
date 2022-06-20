<?php
/**
 * @var string|null $current_user - user name from session or null for guest
 */

require_once 'init.php';


// rendering to page
$content = include_template('guest.php', []);

$main_layout = include_template('layout.php', [
    'content' => $content,
    'current_user' => $current_user
]);

print($main_layout);
