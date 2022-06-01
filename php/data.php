<?php

$show_complete_tasks = rand(0, 1);

$projects = [
    'Incoming' => 'Входящие',
    'Study' => 'Учеба',
    'Work' => 'Работа',
    'Home_work' => 'Домашние дела',
    'Auto' => 'Авто'
];
$tasks = [
    [
        'name' => 'Собеседование в IT компании',
        'date' => '02.06.2022',
        'category' => $projects['Work'],
        'ready' => false
    ],
    [
        'name' => 'Выполнить тестовое задание',
        'date' => '25.12.2022',
        'category' => $projects['Work'],
        'ready' => false
    ],
    [
        'name' => 'Сделать задание первого раздела',
        'date' => '21.12.2022',
        'category' => $projects['Study'],
        'ready' => true
    ],
    [
        'name' => 'Встреча с другом',
        'date' => '22.12.2021',
        'category' => $projects['Incoming'],
        'ready' => false
    ],
    [
        'name' => 'Купить корм для кота',
        'date' => null,
        'category' => $projects['Home_work'],
        'ready' => false
    ],
    [
        'name' => 'Заказать пиццу',
        'date' => null,
        'category' => $projects['Home_work'],
        'ready' => false
    ]
    ];
