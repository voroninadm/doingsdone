<?php

require_once 'php/helpers.php';
require_once 'php/configs.php';
require_once 'php/functions.php';
require_once 'php/models.php';

//===== start session if auth is complete
session_start();
$current_user = $_SESSION['user'] ?? NULL;

//===== constants
define('UPLOAD_PATH', basename( __DIR__ . '/uploads'));

//===== db_connect
$db_cfg = array_values($db);
$conn = mysqli_connect(...$db_cfg);

if (!$conn) {
    show_error(mysqli_connect_error());
}

//===== settings
date_default_timezone_set($default_time_zone);
mysqli_set_charset($conn, $mysql_default_charset);
