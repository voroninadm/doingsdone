<?php

function show_error($error)
{
    if (http_response_code(404)) {
        $page_content = include_template('php/templates/error_404.php', ['error' => $error]);
    } elseif (http_response_code(500)) {
        $page_content = include_template('php/templates/error_500.php', ['error' => $error]);
    } else {
        $page_content = include_template('php/templates/error.php', ['error' => $error]);
    }

    exit($page_content);
}

//===== получение данных

/**
 * Получаем все проекты определенного пользователя и считаем актуальные задачи в них
 * @param $conn - установка соединения
 * @param int $user_id - id пользователя
 * @return array
 */
function get_projects($conn, $user_id)
{
    $user_id = intval($user_id);

    $sql = "SELECT id, name,
            (SELECT COUNT(id) FROM task WHERE user_id = $user_id AND is_complete = 0 AND project_id = p.id) count_tasks
            FROM project p
            WHERE user_id = $user_id ORDER BY name";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение списка таск определенного пользователя
 * Если задана переменная $project_id - получение всех таск пользователя по понкретному проекту
 * @param mysqli $conn
 * @param int $user_id - id пользователя
 * @return array
 */
function get_user_tasks($conn, $user_id, $project_id = null)
{
    $project_id = mysqli_real_escape_string($conn, $project_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    if (!$project_id) {
        $sql = "SELECT * FROM task WHERE user_id = $user_id ORDER BY deadline ASC";
    } else {
        $sql = "SELECT * FROM task WHERE user_id = $user_id AND project_id = $project_id ORDER BY deadline ASC";
    }
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

//===== проверки на существование записей по id

/**
 * Проверка на существование проекта по id
 * @param mysqli $conn
 * @param int $project_id
 * @return boolean
 */
function check_user_project_id($conn, $project_id, $user_id)
{
    $project_id = mysqli_real_escape_string($conn, $project_id);
    $sql = "SELECT id FROM project WHERE id = $project_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    $id = mysqli_fetch_assoc($result);
    if ($id) {
        return true;
    } else {
        return false;
    }
}

/**
 * Проверка на существование таски по id
 *
 * @param mysqli $conn
 * @param int $task_id
 * @return boolean
 */
function check_exist_task_id($conn, $task_id, $user_id)
{
    $sql = "SELECT id FROM task WHERE id = $task_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }
    $id = mysqli_fetch_assoc($result);

    if ($id) {
        return true;
    } else {
        return false;
    }
}

//===== добавление данных

/**
 * Добавление новой таски
 * @param mysqli $conn
 * @param string $task_name
 * @param string $file_url - адрес вложенного файла
 * @param date $deadline - дата дедлайна таски в формате (Y-m-d)
 * @param string $project_id
 * @param int $user_id
 */
function add_new_task($conn, $task_name, $file_url, $deadline, $project_id, $user_id)
{
    $sql = "INSERT INTO task (name, file_url, deadline, project_id, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($conn, $sql, [$task_name, $file_url, $deadline, $project_id, $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_new_task ' . mysqli_error($conn));
    }
}

/**
 * Добавление нового проекта
 * @param mysqli $conn
 * @param string $project_name
 * @param int $user_id
 */
function add_new_project($conn, $project_name, $user_id)
{
    $sql = "INSERT INTO project (name, user_id) VALUES (?, ?)";
    $stmt = db_get_prepare_stmt($conn, $sql, [$project_name, $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_new_project ' . mysqli_error($conn));
    }
}

/**
 * Добавление нового пользователя
 * @param mysqli $conn
 * @param string $email
 * @param string $password
 * @param string $login
 */
function add_new_user($conn, $email, $password, $login)
{
    $sql = "INSERT INTO user (email, password, login) VALUES (?, ?, ?)";
    $stmt = db_get_prepare_stmt($conn, $sql, [$email, $password, $login]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_new_user ', mysqli_error($conn));
    }
}

//===== проверочные функции

/**
 * Проверка на наличие e-mail регистрируемого пользователя при регистрации
 * @param mysqli $conn
 * @param string $email
 * @return bool
 */
function check_user_email($conn, $email)
{
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT email FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('check_user_email ' . mysqli_error($conn));
    }

    return (bool)mysqli_fetch_assoc($result);
}

/**
 * Проверка на наличие существующего проекта пользователя при добавлении нового проекта
 * @param mysqli $conn
 * @param int $user_id
 * @param string $project_name
 * @return bool
 */
function check_exist_user_project($conn, $user_id, $project_name)
{
    $project_name = mysqli_real_escape_string($conn, $project_name);
    $sql = "SELECT * FROM project WHERE user_id = '$user_id' AND name = '$project_name'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('check_exist_user_project ' . mysqli_error($conn));
    }
    return (bool)mysqli_fetch_assoc($result);
}

/**
 * Проверка наличия пользователя с уникальным e-mail при авторизации
 * @param mysqli $conn
 * @param string $email
 * @return array - массив с данными пользователя (для сессии)
 */
function check_auth_user($conn, $email)
{
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('check_exist_user ' . mysqli_error($conn));
        exit();
    }
    return mysqli_fetch_assoc($result);
}

//===== поисковые запросы

/**
 * Получение таск по полнотекстовому запросу
 * @param mysqli $conn
 * @param string $search - запрос из поисковой строки
 * @return array - массив таск
 */
function get_search_results($conn, $search)
{
    $sql = "SELECT * FROM task
            WHERE MATCH(name) AGAINST(?)
            ORDER BY date_add";
    $stmt = db_get_prepare_stmt($conn, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        show_error('get_search_results ' . mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

//===== работа с тасками

/**
 * Завершить таску
 * @param $conn
 * @param $task_id
 */
function complete_task($conn, $task_id)
{
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $sql = "UPDATE task SET is_complete = 1 WHERE id = $task_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('complete_task ' . mysqli_error($conn));
    }
}

/**
 * Отменить выполнене таски
 *
 * @param mysqli $conn
 * @param array $project_name
 * @param array $user_id
 * @return void
 */
function remove_complete_task($conn, $task_id)
{
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $sql = "UPDATE task SET is_complete = 0 WHERE id = $task_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('complete_task ' . mysqli_error($conn));
    }
}

//===== отправка e-mail

/**
 * Отправляем письмо с уведомлением
 * @param $conn
 * @param $date - дата завершения таски
 * @return array
 */
function get_users_for_mailing($conn, $date)
{
    $sql = "SELECT u.id, u.email, u.login FROM user u
            JOIN task t ON t.user_id = u.id
            WHERE DATE_FORMAT(t.deadline, '%Y-%m-%d') = '$date'
            AND t.is_complete = 0";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('get_users_for_mailing ' . mysqli_error($conn));
    }

    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $result = [];

    foreach ($users as $user) {
        $user['tasks'] = get_user_tasks($conn, $user['id']);
        $result[] = $user;
    }

    return $result;
}
