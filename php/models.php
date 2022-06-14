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


// запрос для получения списка проектов у текущего пользователя
// где все таски.

function get_projects($conn, $user_id)
{
    $user_id = intval($user_id);
    $sql = "SELECT id, name,
              (SELECT COUNT(id) FROM task WHERE user_id = $user_id AND project_id = p.id) count_tasks
              FROM project p
              WHERE user_id = $user_id ORDER BY name ";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error = mysqli_error($conn);
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

;

// запрос для получения списка актуальных проектов у текущего пользователя
// где только актуальные таски (is_complete = 0).

function get_current_projects($conn, $user_id)
{
    $user_id = intval($user_id);
    $sql = "SELECT id, name,
              (SELECT COUNT(id) FROM task WHERE user_id = $user_id AND is_complete = 0 AND project_id = p.id) count_tasks
              FROM project p
              WHERE user_id = $user_id ORDER BY name ";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

;

// запрос для получения списка из всех задач у текущего пользователя.

function get_user_tasks($conn, $user_id)
{
    $user_id = intval($user_id);
    $sql = "SELECT * FROM task WHERE user_id = $user_id ORDER BY deadline ASC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// запрос для получения имени текущего пользователя.

//function get_username ($conn, $user_id) {
//    $user_id = intval($user_id);
//    $sql = "SELECT login FROM user WHERE id = $user_id";
//    $result = mysqli_query($conn, $sql);
//
//    if (!$result) {
//        $error = mysqli_error($conn);
//        show_error(mysqli_error($conn));
//    }
//
//    return mysqli_fetch_assoc($result);
//}

/**
 * Проверка на существование проекта по id
 *
 * @param mysqli $conn
 * @param int $project_id
 * @return boolean
 */
function check_user_project_id($conn, $project_id, $user_id)
{
    $project_id = mysqli_real_escape_string($conn, $project_id);
    $sql = "SELECT id FROM project WHERE id = $project_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $id = mysqli_fetch_assoc($result);

        if ($id) {
            return true;
        } else {
            return false;
        }
    }

    show_error(mysqli_error($conn));
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
    $project_id = mysqli_real_escape_string($conn, $task_id);
    $sql = "SELECT id FROM task WHERE id = $task_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $id = mysqli_fetch_assoc($result);

        if ($id) {
            return true;
        } else {
            return false;
        }
    }

    show_error(mysqli_error($conn));
}

/**
 * получение списка из всех задач для одного пользователя
 *
 * @param mysqli $con
 * @param int $user_id
 * @return array
 */
function get_project_user_tasks($conn, $user_id, $project_id)
{
    $project_id = mysqli_real_escape_string($conn, $project_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "SELECT * FROM task WHERE user_id = $user_id AND project_id = $project_id ORDER BY deadline ASC";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($conn));
}


/**
 * Проверка на существование пользователя по id
 *
 * @param mysqli $con
 * @param int $project_id
 * @return boolean
 */
function check_user_id($conn, $user_id)
{
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "SELECT id FROM user WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $id = mysqli_fetch_assoc($result);

        if ($id) {
            return true;
        } else {
            return false;
        }
    }

    show_error(mysqli_error($conn));
}

//===== adding

function add_new_task($conn, $task_name, $file_url, $deadline, $project_id, $user_id)
{
    $sql = "INSERT INTO task (name, file_url, deadline, project_id, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($conn, $sql, [$task_name, $file_url, $deadline, $project_id, $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_new_task ' . mysqli_error($conn));
    }
}

function add_new_project($conn, $project_name, $user_id)
{
    $sql = "INSERT INTO project (name, user_id) VALUES (?, ?)";
    $stmt = db_get_prepare_stmt($conn, $sql, [$project_name, $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_new_project ' . mysqli_error($conn));
    }
}

//===== REG

function check_registrated_user_email($conn, $email)
{
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT email FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        return (bool)mysqli_fetch_assoc($result);
    }

    show_error('check_registrated_user_email ' . mysqli_error($conn));
}

function add_new_user($conn, $email, $password, $login)
{
    $sql = "INSERT INTO user (email, password, login) VALUES (?, ?, ?)";
    $stmt = db_get_prepare_stmt($conn, $sql, [$email, $password, $login]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_new_user ', mysqli_error($conn));
    }
}

//===== auth

function check_exist_user($conn, $email)
{
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('check_exist_user ' . mysqli_error($conn));
        exit();
    }
    return mysqli_fetch_assoc($result);
}

//===== search tasks

function get_search_results($conn, $search)
{
    $sql = "SELECT * FROM task
            WHERE MATCH(name) AGAINST(?)
            ORDER BY date_add";
    $stmt = db_get_prepare_stmt($conn, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_search_results ' . mysqli_error($conn));
}

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

//===== tasks

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
 * Отмечает что задача не выполнена
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

//===== e-mail sending

function get_users_for_mailing($conn, $date)
{
    $sql = "SELECT u.id, u.email, u.login FROM user u
            JOIN task t ON t.user_id = u.id
            WHERE DATE_FORMAT(t.deadline, '%Y-%m-%d') = '$date'
            AND t.is_complete = 0";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $result = [];

        foreach ($users as $user) {
            $user['tasks'] = get_user_tasks($conn, $user['id']);
            $result[] = $user;
        }

        return $result;
    }

    show_error('get_users_for_mailing ' . mysqli_error($conn));
}
