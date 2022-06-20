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

    //validating
    $task_name = trim(filter_input(INPUT_POST, 'name'));
    $project_id = trim(filter_input(INPUT_POST, 'project'));
    $deadline = filter_input(INPUT_POST, 'date') ?: null;
    $file = $_FILES['file']['name'] ?: null;
    $file_url = $file ? upload_task_file($_FILES) : null;

    $errors = validate_add_task_form ($conn, $user_id, $task_name, $project_id, $deadline);

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
    'page_title' => 'Дела в порядке | Добавление задачи',
    'current_user' => $current_user,
    'content' => $content
]);

print($main_layout);
