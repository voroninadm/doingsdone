<?php

require_once 'php/init.php';

//check user is auth
if (!isset($_SESSION['user'])) {
    header("Location: /guest.php");
    exit();
}

// start configs
$user_name = $_SESSION['user'];
$user_id = $user_name['id'];
$projects = get_projects($conn, $user_id);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = trim(filter_input(INPUT_POST, 'project_name'));

    if (!$project_name) {
        $errors['project_name'] = 'Поле не заполнено';
    } elseif (!count_str_length($project_name, 3, 30)) {
        $errors['project_name'] = 'Слишком длинное имя проекта';
    } elseif (check_exist_user_project($conn, $user_id, $project_name)) {
        $errors['project_name'] = 'Проект с таким именем уже существует';
    }

    array_filter($errors);

    if (empty($errors)) {
        add_new_project($conn, $project_name, $user_id);
        header('Location: /index.php');
        exit();
    }
}

// rendering to page
$content = include_template('form-project.php', [
    'errors' => $errors ?? null,
    'projects' => $projects,
    'project_name' => $project_name ?? null,
]);

//layout to main page with main page template
$main_layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'current_user' => $current_user,
    'content' => $content
]);

print($main_layout);
