<?php

/**
 * Замена введенных значений на безопасные, от XSS-атак
 * @param string $string - строка введенных данных
 * @return string
 */
function esc($string)
{
    return htmlspecialchars($string);
}

/**
 * Проверка времени таски до дедлайна
 * @param string $date - дата дедлайна таски
 * @param boolean $is_complete_task - выполнена таска или нет
 * @param int 24 - значение в часах для предупреждения
 * @param int 3600 - конвертируем секунды в часы
 * @return boolean - если задана $date
 * @return null
 */
function get_hours_to_deadline($date, $is_complete_task)
{
    if ($date && !$is_complete_task) {
        $diff = floor((strtotime($date) - time()) / 3600);
        return ($diff < 24);
    }

    return null;
}

/** Соответствие количества символов в строке заданному диапазону
 * @param string $string - строка данных
 * @param int $min - минимальное количество символов
 * @param int $max - максимальное количество символов
 * @return bool
 */
function count_str_length($string, $min, $max)
{
    if ($string) {
        $len = strlen($string);
        if ($len >= $min and $len <= $max) {
            return true;
        }
    }

    return false;
}

/**
 * Проверка даты для добавления задачи.
 * Дата должна быть больше или роавна текущей в формате Y-m-d
 * @param string $date - дата для проверки
 * @return bool
 */
function check_correct_date($date)
{
    return date('Y-m-d', strtotime($date)) >= date('Y-m-d');
}

/**
 * Фильтрация невывполненных таск пользователя
 * @param array $tasks - список всех таск пользователя
 * @return array
 */
function get_user_no_completed_tasks($tasks)
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
function get_filtered_tasks($tasks, $filter_date)
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

/**
 * Перенос загружаемого файла из добавляемой таски
 * @param array $file_tmp - файл (из $_FILES)
 * @return string - путь до файла
 */
function upload_task_file($file_tmp)
{
    $file_name = $file_tmp['file']['name'];
    $file_path = UPLOAD_PATH;

    move_uploaded_file($file_tmp['file']['tmp_name'], "$file_path/$file_name");
    return "$file_path/$file_name";
}
