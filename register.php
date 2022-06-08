<?php

require_once 'php/init.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //validating
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));
    $login = trim(filter_input(INPUT_POST, 'name'));

    if (!$email) {
        $errors['email'] = "Поле не заполнено";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "e-mail должен быть корректно заполнен";
    } else if (check_registrated_user_email($conn, $email)) {
        $errors['email'] = "Пользователь с тамим e-mail уже зарегистрирован";
    }

    if (!$password) {
        $errors['password'] = 'Поле не заполнено';
    } elseif (!count_str_length($password, 3, 50)) {
        $errors['password'] = "Длина пароля не менее 3 символов";
    }

    if (!$login) {
        $errors['login'] = 'Поле не заполнено';
    } elseif (!count_str_length($login, 2, 30)) {
        $errors['login'] = "Не менее 2х и не более 30 символов";
    }

    array_filter($errors);

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        add_new_user($conn, $email, $password, $login);

        header('Location: index.php');
        exit();
    }
}


// rendering to page

//layout to main page with main page template
$main_layout = include_template('registration.php', [
    'errors' => $errors,
    'email' => $email ?? null,
    'password' => $password ?? null,
    'login' => $login ?? null

]);

print($main_layout);
