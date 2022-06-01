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
 * @param int DEADLINE - time in hours to make it sensitive
 * @param int SECONDS_IN_HOUR - convert seconds to hours
 * @return boolean if $date is set or
 * @return null
 */
function get_hours_to_deadline($date) {
    if ($date) {
        define('DEADLINE', '24');
        define('SECONDS_IN_HOUR', 3600);
        $diff = floor((strtotime($date) - time()) / SECONDS_IN_HOUR );
        return ($diff < DEADLINE);
    }

    return null;
}
