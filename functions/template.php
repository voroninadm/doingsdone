<?php

//***** SECURITY

/**
 * Замена введенных значений на безопасные, от XSS-атак
 * @param string $string - строка введенных данных
 * @return string
 */
function esc(mixed $string): string
{
    return htmlspecialchars($string);
}

//***** CHECKS

/**
 * Проверка времени таски до дедлайна
 * @param string|null $date - дата дедлайна таски
 * @param boolean $is_complete_task - выполнена таска или нет
 * @return string|null
 */
function check_hours_to_deadline(string|null $date, bool $is_complete_task): string|null
{
    if ($date && !$is_complete_task) {
        $diff = floor((strtotime($date) - time()) / 3600);
        return ($diff < 24);
    }
    return null;
}

/**
 * Проверка даты для добавления задачи.
 * Дата должна быть больше или роавна текущей в формате Y-m-d
 * @param string $date - дата для проверки
 * @return bool
 */
function check_correct_date(string $date): bool
{
    return date('Y-m-d', strtotime($date)) >= date('Y-m-d');
}

/**
 * Проверка пройденности авторизации и открытия сессии
 * @param array $session - из $_SESSION
 * @return array|null
 */
function check_open_session (array $session): array|null
{
    if ($session) {
        return [
            'name' => $session['user'],
            'id' => $session['user']['id'],
        ];
    }
    return null;
}

/** Проверка соответствия количества символов в строке заданному диапазону
 * @param string $string - строка данных
 * @param int $min - минимальное количество символов
 * @param int $max - максимальное количество символов
 * @return bool
 */
function check_str_length(mixed $string, int $min, int $max): bool
{
    if ($string) {
        $len = strlen($string);
        if ($len >= $min and $len <= $max) {
            return true;
        }
    }

    return false;
}

//***** FILTERS

/**
 * Фильтрация невывполненных таск пользователя
 * @param array $tasks - список всех таск пользователя
 * @return array
 */
function get_user_no_completed_tasks(array $tasks): array
{
    $no_completed_tasks = [];

    foreach ($tasks as $task) {
        if (!$task['is_complete']) {
            $no_completed_tasks[] = $task;
        }
    }

    return $no_completed_tasks;
}

/**
 * Фильтрация массива таск по дате дедлайна
 * @param array $tasks - массив таск
 * @param string $filter_date - даты для фильтра => [today, tomorrow, out_of_date]
 * @return array
 */
function get_filtered_tasks(array $tasks, string $filter_date): array
{
    $result = [];
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('tomorrow'));

    foreach ($tasks as $task) {
        $deadline = date('Y-m-d', strtotime($task['deadline']));
        switch ($filter_date) {
            case 'today' :
                $deadline === $today ? $result[] = $task : null;
                break;
            case 'tomorrow' :
                $deadline === $tomorrow ? $result[] = $task : null;
                break;
            case 'out_of_date' :
                $deadline < $today ? $result[] = $task : null;
                break;
        }
    }
    return $result;
}

//***** DO/NOT TASKS

/**
 * Завершить таску
 * @param mysqli $conn
 * @param int $task_id
 */
function complete_task(mysqli $conn, int $task_id)
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
 * @param mysqli $conn
 * @param int $task_id
 * @return void
 */
function remove_complete_task(mysqli $conn, int $task_id)
{
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $sql = "UPDATE task SET is_complete = 0 WHERE id = $task_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        show_error('complete_task ' . mysqli_error($conn));
    }
}

//***** MAKE CONFIG

/**
 * Создаем конфиг для мэйлера
 * @param array $mailer - массив с конфигурацией
 * @return string
 */
function make_dsn(array $mailer): string
{
    return 'smtp://' . $mailer['login']
        . ':' . $mailer['password']
        . '@' . $mailer['host'] . ':'
        . $mailer['port']
        . '?encryption='
        . $mailer['encryption']
        . '&auth_mode=login';
}

//***** OTHER

/**
 * Перенос загружаемого файла из добавляемой таски
 * @param array $file_tmp - файл (из $_FILES)
 * @return string - путь до файла
 */
function upload_task_file(array $file_tmp): string
{
    $file_name = $file_tmp['file']['name'];
    $file_path = UPLOAD_PATH;

    move_uploaded_file($file_tmp['file']['tmp_name'], "$file_path/$file_name");
    return "$file_path/$file_name";
}

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



