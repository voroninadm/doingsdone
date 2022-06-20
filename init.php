<?php
/**
 * @var array $db - db connect configs
 * @var string $default_time_zone - time zone configs
 * @var string $mysql_default_charset - charset configs
 */

require_once 'helpers.php';
require_once 'configs.php';
require_once 'functions/init_functions.php';

//===== start session if auth is complete
session_start();
$user = check_open_session($_SESSION);
$current_user = $user['name'] ?? null;
if ($user) {
    $user_name = $user['name'];
    $user_id = $user['id'];
}

//===== constants
define('UPLOAD_PATH', basename(__DIR__ . '/uploads'));

//===== db_connect
$db_cfg = array_values($db);
$conn = mysqli_connect(...$db_cfg);

if (!$conn) {
    show_error(mysqli_connect_error());
}

//===== settings
date_default_timezone_set($default_time_zone);
mysqli_set_charset($conn, $mysql_default_charset);
