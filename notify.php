<?php

//start config
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once 'vendor/autoload.php';
require_once 'php/init.php';

$date = date('Y-m-d');
$users = get_users_for_mailing($conn, $date);
if (!$users) {
    exit();
}

// Конфигурация траспорта
$dsn = 'smtp://18d0959cf2a790:3b0aa4b31afd88@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login';
$transport = Transport::fromDsn($dsn);

// Формирование сообщения
foreach ($users as $user) {
    $message = new Email();
    $message->to($user['email']);
    $message->from("keks@phpdemo.ru");
    $message->subject("Уведомление от сервиса «Дела в порядке»");

    $tasks = [];

    foreach ($user['tasks'] as $task) {
        $date = strtotime($task['deadline']);
        $tasks[]= $task['name'] . ' на ' . date('d', $date) . ' ' . date('M', $date);
    }

    $to_do_template = include_component('notify.php', [
        'user_login' => $user['login'],
        'tasks' => $tasks
    ]);

    $message->html($to_do_template);

    $mailer = new Mailer($transport);
    $mailer->send($message);

    if (!$mailer) {
        echo ('Сообщение на ящик' . $user['email'] . 'не было отправлено!');
    } else {
        echo ('Сообщение успешно отправлено на ящик ' . $user['email']);
    }
}
