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

// запрос для получения списка проектов у текущего пользователя.

function get_projects ($conn, $user_id) {
    $user_id = intval($user_id);
    $sql = "SELECT name,
              (SELECT COUNT(id) FROM task WHERE user_id = $user_id AND project_id = p.id) count_tasks
              FROM project p
              WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error = mysqli_error($conn);
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
};

// запрос для получения списка из всех задач у текущего пользователя.

function get_tasks($conn, $user_id)
{
    $user_id = intval($user_id);
    $sql = "SELECT * FROM task WHERE user_id = $user_id ORDER BY deadline ASC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error = mysqli_error($conn);
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// запрос для получения имени текущего пользователя.

function get_username ($conn, $user_id) {
    $user_id = intval($user_id);
    $sql = "SELECT login FROM user WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error = mysqli_error($conn);
        show_error(mysqli_error($conn));
    }

    return mysqli_fetch_assoc($result);
}
