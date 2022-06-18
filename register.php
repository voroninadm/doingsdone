<?php

require_once 'php/init.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //validating
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));
    $login = trim(filter_input(INPUT_POST, 'name'));

    $errors = validate_user_registration ($conn, $email, $password, $login);

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        add_new_user($conn, $email, $password, $login);

        header('Location: auth.php');
        exit();
    }
}

// rendering to page
$content = include_template('registration.php', [
    'errors' => $errors,
    'email' => $email ?? null,
    'password' => $password ?? null,
    'login' => $login ?? null
]);

//layout to main page with main page template
$main_layout = include_template('layout.php', [
    'content' => $content,
    'current_user' => $current_user
]);

print($main_layout);
