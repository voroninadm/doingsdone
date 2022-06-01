<?php

require_once 'php/init.php';

//page template included
$main_content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);

//layout to main page with main page template
$main_layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'user_name' => 'Алексей',
    'main_content' => $main_content
]);

print($main_layout);
