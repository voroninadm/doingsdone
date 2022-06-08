<?php

require_once 'php/init.php';

$project_id = filter_input(INPUT_GET, 'project_id');


$user_id = 1;
$user_name = get_username($conn, $user_id);
$projects = get_projects($conn, $user_id);
// $tasks = get_user_tasks($conn, $user_id);

if ($project_id && check_user_project_id($conn, $project_id,$user_id)) {
    $tasks = get_project_user_tasks($conn, $user_id, $project_id);
} else {
    $tasks = get_user_tasks($conn, $user_id);
}


// rendering to page
if ($project_id && !check_user_project_id($conn, $project_id, $user_id)) {
    http_response_code(404);
    $content = include_component('error_404.php', [
        'error' => 'Нет такого проекта'
    ]);
} else {
    $content = include_template('main.php', [
        'projects' => $projects,
        'tasks' => $tasks,
        'show_complete_tasks' => $show_complete_tasks,
        'project_id' => $project_id
    ]);
};

$main_layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'user_name' => $user_name,
    'content' => $content
]);

print($main_layout);
