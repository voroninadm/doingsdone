<?php

require_once 'php/init.php';

if (!isset($_SESSION['user'])) {
    header("Location: /guest.php");
    exit();
}

//get project_id
$project_id = filter_input(INPUT_GET, 'project_id');

$user_name = $_SESSION['user'];
$user_id = $user_name['id'];
$projects = get_projects($conn, $user_id);
// $tasks = get_user_tasks($conn, $user_id);

if ($project_id && check_user_project_id($conn, $project_id,$user_id)) {
    $tasks = get_project_user_tasks($conn, $user_id, $project_id);
} else {
    $tasks = get_user_tasks($conn, $user_id);
}

//check search form
$search = trim(filter_input(INPUT_GET, 'search')) ?? null;

if ($search) {
    $tasks = get_search_results($conn, $search);
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
        'project_id' => $project_id,
        'search' => $search ?? null
    ]);
};

$main_layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'current_user' => $current_user,
    'content' => $content,
]);

print($main_layout);
