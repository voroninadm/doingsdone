<?php

//***** GETTERS

/**
 * Получаем все проекты определенного пользователя и считаем актуальные задачи в них
 * @param $conn - установка соединения
 * @param int $user_id - id пользователя
 * @return array
 */
function get_projects(mysqli $conn, int $user_id): array
{
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
 * @param int|null $project_id
 * @param bool|null $is_complete
 * @return array
 */
function get_user_tasks(mysqli $conn, int $user_id, ?int $project_id = null): array
{
    $project_id = mysqli_real_escape_string($conn, $project_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    if (!$project_id) {
        $sql = "SELECT * FROM task WHERE user_id = $user_id ORDER BY deadline ASC";
    } else {
        $sql = "SELECT * FROM task WHERE user_id = $user_id AND project_id = $project_id  ORDER BY deadline ASC";
    }
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение таск по полнотекстовому запросу
 * @param mysqli $conn
 * @param string $search - запрос из поисковой строки
 * @return array - массив таск
 */
function get_search_results(mysqli $conn, string $search): array
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

//===== e-mail уведомления
/**
 * Получение юзеров и таск, отправляем письмо с уведомлением
 * @param mysqli $conn
 * @param $date - дата завершения таски
 * @return array
 */
function get_users_for_mailing(mysqli $conn, string $date)
{
    $sql = "SELECT u.id, u.email, u.login FROM user u
            JOIN task t ON t.user_id = u.id
            WHERE DATE_FORMAT(deadline, '%Y-%m-%d') = '$date'
            AND is_complete = 0";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('get_users_for_mailing ' . mysqli_error($conn));
    }

    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $result = [];

    foreach ($users as $user) {
        $user['tasks'] = get_tasks_for_mailing($conn, $user['id'], $date);
        $result[] = $user;
    }

    return $result;
}

/**
 * Выбираем задачи на день для уведомления по e-mail
 * @param mysqli $conn
 * @param int $user_id
 * @param string $date
 * @return array
 */
function get_tasks_for_mailing(mysqli $conn, int $user_id, string $date): array
{
    $sql = "SELECT * FROM task WHERE user_id = $user_id AND is_complete = 0 AND DATE_FORMAT(deadline, '%Y-%m-%d') = '$date' ORDER BY deadline ASC";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

//***** SETTERS

/**
 * Добавление новой таски
 * @param mysqli $conn
 * @param string $task_name
 * @param string|null $file_url - адрес вложенного файла
 * @param string|null $deadline - дата дедлайна таски в формате (Y-m-d)
 * @param int $project_id
 * @param int $user_id
 */
function add_new_task(
    mysqli $conn,
    string $task_name,
    string|null $file_url,
    string|null $deadline,
    int $project_id,
    int $user_id
) {
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
function add_new_project(mysqli $conn, string $project_name, int $user_id)
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
function add_new_user(mysqli $conn, string $email, string $password, string $login)
{
    $sql = "INSERT INTO user (email, password, login) VALUES (?, ?, ?)";
    $stmt = db_get_prepare_stmt($conn, $sql, [$email, $password, $login]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_new_user ', mysqli_error($conn));
    }
}

//***** CHECK

/**
 * Проверка на существование проекта по id
 * @param mysqli $conn
 * @param int $project_id
 * @param int $user_id
 * @return boolean
 */
function check_user_project_id(mysqli $conn, int $project_id, int $user_id): bool
{
    $project_id = mysqli_real_escape_string($conn, $project_id);
    $sql = "SELECT id FROM project WHERE id = $project_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    return (bool)mysqli_fetch_assoc($result);
}

/**
 * Проверка на существование таски по id
 * @param mysqli $conn
 * @param int $task_id
 * @param int $user_id
 * @return boolean
 */
function check_exist_task_id(mysqli $conn, int $task_id, int $user_id): bool
{
    $sql = "SELECT id FROM task WHERE id = $task_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    return (bool)mysqli_fetch_assoc($result);
}

/**
 * Проверка на наличие e-mail регистрируемого пользователя при регистрации
 * @param mysqli $conn
 * @param string $email
 * @return bool
 */
function check_user_email(mysqli $conn, string $email): bool
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
function check_exist_user_project(mysqli $conn, int $user_id, string $project_name): bool
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
function check_auth_user(mysqli $conn, string $email): array
{
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('check_exist_user ' . mysqli_error($conn));
        exit();
    }
    return mysqli_fetch_assoc($result);
}

