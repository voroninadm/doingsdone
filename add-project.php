<?php

/**
 * @var mysqli $conn - connect to DB
 * @var array $user - open user session from init.php
 * @var int $user_id - user id from open session
 * @var string $current_user - user name from session or null for guest
 */

require_once 'init.php';

//check user is auth
if ($user === null) {
    header("Location: /guest.php");
    exit();
}

// start configs
$projects = get_projects($conn, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = trim(filter_input(INPUT_POST, 'project_name'));

    $errors = validate_add_project_form($conn, $user_id, $project_name);

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
    'page_title' => 'Дела в порядке | Добавление проекта',
    'current_user' => $current_user,
    'content' => $content
]);

print($main_layout);
