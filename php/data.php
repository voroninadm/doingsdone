<?php

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
        'date' => '01.12.2019',
        'category' => $projects['Work'],
        'ready' => false
    ],
    [
        'name' => 'Выполнить тестовое задание',
        'date' => '25.12.2019',
        'category' => $projects['Work'],
        'ready' => false
    ],
    [
        'name' => 'Сделать задание первого раздела',
        'date' => '21.12.2019',
        'category' => $projects['Study'],
        'ready' => true
    ],
    [
        'name' => 'Встреча с другом',
        'date' => '22.12.2019',
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
