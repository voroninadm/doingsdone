<?php
/**
 * @var mysqli $conn - connect to DB
 * @var array $mailer - mailer settings
 */

//start config
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once 'vendor/autoload.php';
require_once 'init.php';

$date = date('Y-m-d');
$users = get_users_for_mailing($conn, $date);
if (!$users) {
    echo "Некому отправлять письма, задачи на $date не найдены" ;
    exit();
}

// transport configs
$dsn = make_dsn($mailer);
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
    try {
        $mailer->send($message);
        echo ('Сообщение успешно отправлено на ящик ' . $user['email']);
    } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
        echo 'Сообщение на ящик ' . $user['email'] . ' не было отправлено! Причина: ',  $e->getMessage(), "\n";
    }
}
