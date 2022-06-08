<?php

require_once 'php/init.php';

// start configs
$user_id = 1;
$user_name = get_username($conn, $user_id);
$projects = get_projects($conn, $user_id);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //validating
    $task_name = trim(filter_input(INPUT_POST, 'name'));
    $project_id = filter_input(INPUT_POST, 'project');
    $deadline = filter_input(INPUT_POST, 'date') ?? null;

    if (!$task_name) {
        $errors['task_name'] = "Поле не заполнено";
    } elseif (!count_str_length($task_name, 5, 120)) {
        $errors['task_name'] = 'Количество символов от 5 до 120';
    }

    if (!$project_id) {
        $errors['project_id'] = 'Поле не заполнено';
    } elseif (!check_user_project_id($conn, $project_id, $user_id)) {
        $errors['project_id'] = "Выберите существующий проект";
    }

    //is_date_valid - function from helpers.php
    if ($deadline && !is_date_valid($deadline)) {
        $errors['deadline'] = 'Неправильный формат даты';
    } elseif ($deadline && !check_correct_date($deadline)) {
        $errors['deadline'] = 'Не получится добавить задачу в прошлое';
    }

    array_filter($errors);

    if ($_FILES['file']['name']) {
        $tmp_name = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
//        $file_name = uniqid() . '_' . $file_name;
        $file_path = __DIR__ . '/uploads/';

        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
        $file_url = 'uploads/' . $file_name;
    } else {
        $file_url = null;
    }

    if (empty($errors)) {
        add_new_task($conn, $task_name, $file_url, $deadline, $project_id, $user_id);

        header('Location: index.php');
        exit();
    }

};


// rendering to page
$content = include_template('form-task.php', [
    'projects' => $projects ?? null,
    'errors' => $errors ?? null,
    'task_name' => $task_name ?? null,
    'project_id' => $project_id ?? null,
    'deadline' => $deadline ?? null
]);

//layout to main page with main page template
$main_layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'user_name' => $user_name,
    'content' => $content
]);

print($main_layout);
