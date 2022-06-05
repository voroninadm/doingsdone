<?php

require_once 'php/init.php';

$user_id = 1;
$user_name = get_username($conn, $user_id);
$projects = get_projects($conn, $user_id);
$tasks = get_tasks($conn, $user_id);

//page template included
$main_content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);

//layout to main page with main page template
$main_layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'user_name' => $user_name,
    'main_content' => $main_content
]);

print($main_layout);
