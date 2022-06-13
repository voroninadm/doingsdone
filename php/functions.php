<?php

/**
 * Calc project tasks in array
 * @param  array $tasks
 * @param  string $project - value of current project
 * @return int
 */
function get_project_tasks (array $tasks, $project) {
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['category'] == $project) {
            $count ++;
        }
    }
    return $count;
};

/**
 * prevent XSS
 * @param string $string - input from user
 * @return string
 */
function esc ($string) {
    return htmlspecialchars($string);
};


/**
 * check time to project deadline
 * @param int 24 - time in hours to make it sensitive
 * @param int 3600 - convert seconds to hours
 * @return boolean if $date is set +or
 * @return null
 */
function get_hours_to_deadline($date) {
    if ($date) {
        $diff = floor((strtotime($date) - time()) / 3600 );
        return ($diff < 24);
    }

    return null;
}


function count_str_length($string, $min, $max) {
    if ($string) {
        $len = strlen($string);
        if ($len >= $min and $len <= $max) {
            return true;
        }
    }
    return false;
}

/**
 * проверка даты. Дата должна быть больше или роавна текущей в формате Y-m-d
 *
 * @param  string $date
 * @return bool
 */
function check_correct_date($date)
{
    return date('Y-m-d', strtotime($date)) >= date('Y-m-d');
}

function get_user_no_completed_tasks($tasks)
{
    $no_completed_tasks = [];

    foreach($tasks as $task) {
        if (!$task['is_complete']) {
            $no_completed_tasks[] = $task;
        }
    }

    return $no_completed_tasks;
}
