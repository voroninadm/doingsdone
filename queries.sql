INSERT INTO user (login, email, password) VALUES
  ('user', 'user@test.ru', 'pass123'),
  ('admin', 'admin@test.ru', 'pass');

INSERT INTO project (name, user_id) VALUES
    ('Входящие', 1),
    ('Учеба', 2),
    ('Работа', 1),
    ('Домашние дела', 2),
    ('Авто', 1);

INSERT INTO task (name, is_complete, file_url, deadline, project_id, user_id) VALUES
    ('Собеседование в IT компании', 0, 'home.psd', '2022-06-10 16:00:00', 3, 1),
    ('Выполнить тестовое задание', 0, 'home.psd', '2022-06-13 16:00:00', 3, 2),
    ('Сделать задание первого раздела', 1, 'home.psd', '2022-06-10 16:00:00', 2, 1),
    ('Встреча с другом', 0, '', '2022-06-10 16:00:00', 1, 2),
    ('Купить корм для кота', 0, '', '2022-06-10 16:00:00', 4, 1),
    ('Заказать пиццу', 0, '', '2022-06-10 16:00:00', 4, 2);

--получить список проектов для юзера, по user_id (например, user_id=1)
SELECT * FROM project WHERE user_id = 1;

-- получить список всех задач для проекта, по project_id (например, project_id=1)
SELECT * FROM task WHERE project_id = 1;

-- отметить таску как выполненную, по id таски (например task id = 1)
UPDATE task SET is_complete = 1 WHERE id = 1;

-- обновить название таски, по id таски (например task id = 1)
UPDATE task SET name = 'Пойти в гости к приятелю' WHERE id = 1;
