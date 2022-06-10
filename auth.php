<?php

require_once 'php/init.php';


$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //validating
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));

    if (!$email) {
        $errors['email'] = "Поле не заполнено";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "e-mail должен быть корректно заполнен";
    } elseif (!check_registrated_user_email($conn, $email)) {
        $errors['email'] = 'Пользователь с таким e-mail не зарегистрирован';
    }

    if (!$password) {
        $errors['password'] = 'Поле не заполнено';
    }

    array_filter($errors);


    $user = check_exist_user($conn, $email);

    if (empty($errors) && $user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = "Неправильный пароль";
        }
    }

    if (isset($_SESSION['user'])) {
        header('Location: index.php');
        exit();
    }
}

// rendering to page
$content = include_template('authorization.php', [
    'errors' => $errors ?? null,
    'email' => $email ?? null,
    'password' => $password ?? null
 ]);

//layout to main page with main page template
$main_layout = include_template('layout.php', [
    'content' => $content,
    'current_user' => $current_user
]);

print($main_layout);
