<?php

require_once 'php/helpers.php';
require_once 'php/configs.php';
require_once 'php/functions.php';
require_once 'php/models.php';

$show_complete_tasks = rand(0, 1);

$db_cfg = array_values($db);
$conn = mysqli_connect(...$db_cfg);

if (!$conn) {
    show_error(mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
