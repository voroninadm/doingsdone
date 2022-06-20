<?php

/**
 * Валидация добавления проекта
 * @param mysqli $conn
 * @param int $user_id
 * @param string $project_name
 * @return array|null
 */
function validate_add_project_form(mysqli $conn, int $user_id, string $project_name): array|null
{
    $errors = [];
    if (!$project_name) {
        $errors['project_name'] = 'Поле не заполнено';
    } elseif (!check_str_length($project_name, 3, 30)) {
        $errors['project_name'] = 'Количество символов от 3 до 120';
    } elseif (check_exist_user_project($conn, $user_id, $project_name)) {
        $errors['project_name'] = 'Проект с таким именем уже существует';
    }

    return $errors;
}

/**
 * Валидация добавления таски
 * @param mysqli $conn
 * @param int $user_id
 * @param string $task_name
 * @param int $project_id
 * @param string|null $deadline
 * @return array|null
 */
function validate_add_task_form(
    mysqli $conn,
    int $user_id,
    string $task_name,
    int $project_id,
    string|null $deadline
): array|null {
    $errors = [];
    if (!$task_name) {
        $errors['task_name'] = "Поле не заполнено";
    } elseif (!check_str_length($task_name, 5, 120)) {
        $errors['task_name'] = 'Количество символов от 5 до 120';
    }

    if (!$project_id) {
        $errors['project_id'] = 'Поле не заполнено';
    } elseif (!check_user_project_id($conn, $project_id, $user_id)) {
        $errors['project_id'] = "Выберите существующий проект";
    }

    if (!$deadline) {
        $deadline = null;
    }

    //is_date_valid - function from helpers.php
    if ($deadline && !is_date_valid($deadline)) {
        $errors['deadline'] = 'Некорректный формат даты';
    } elseif ($deadline && !check_correct_date($deadline)) {
        $errors['deadline'] = 'Не получится добавить задачу в прошлое';
    }

    return $errors;
}

/**
 * Валидация регистрации пользователя
 * @param mysqli $conn
 * @param string $email
 * @param string $password
 * @param string $login
 * @return array|null
 */
function validate_user_registration(mysqli $conn, string $email, string $password, string $login): array|null
{
    $errors = [];
    if (!$email) {
        $errors['email'] = "Поле не заполнено";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "e-mail должен быть корректно заполнен";
    } else {
        if (check_user_email($conn, $email)) {
            $errors['email'] = "Пользователь с тамим e-mail уже зарегистрирован";
        }
    }

    if (!$password) {
        $errors['password'] = 'Поле не заполнено';
    } elseif (!check_str_length($password, 8, 30)) {
        $errors['password'] = "Не менее 8 и не более 30 ";
    }

    if (!$login) {
        $errors['login'] = 'Поле не заполнено';
    } elseif (!check_str_length($login, 2, 30)) {
        $errors['login'] = "Не менее 2 и не более 30 символов";
    }

    return $errors;
}
