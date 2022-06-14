<?php

require_once 'php/init.php';

if (!isset($_SESSION['user'])) {
    header("Location: /guest.php");
    exit();
}

$user_name = $_SESSION['user'];
$user_id = $user_name['id'];
$projects = get_current_projects($conn, $user_id);
$show_completed_tasks = filter_input(INPUT_GET, 'show_completed', FILTER_SANITIZE_NUMBER_INT);


//get project_id
$project_id = filter_input(INPUT_GET, 'project_id');

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

//check filter
$filter = filter_input(INPUT_GET, 'filter') ?? null;

if ($filter === 'today') {
    $tasks = get_filtered_tasks($tasks, $filter);
} elseif ($filter === 'tomorrow') {
    $tasks = get_filtered_tasks($tasks, $filter);
} elseif ($filter === 'out_of_date') {
    $tasks = get_filtered_tasks($tasks, $filter);
}

//tasks
$task_id = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
$task_check = filter_input(INPUT_GET, 'check', FILTER_SANITIZE_NUMBER_INT);

if ($task_id && check_exist_task_id($conn, $task_id, $user_id)) {
    if ($task_check) {
        complete_task($conn, $task_id);
        header('Location: index.php');
    } else {
        remove_complete_task($conn, $task_id);
        header('Location: index.php');
    }
}

if (!$show_completed_tasks) {
    $tasks = get_user_no_completed_tasks($tasks);
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
        'search' => $search ?? null,
        'show_completed_tasks' => $show_completed_tasks,
        'filter' => $filter
    ]);
};

$main_layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'current_user' => $current_user,
    'content' => $content,
]);

print($main_layout);
