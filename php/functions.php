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
}
