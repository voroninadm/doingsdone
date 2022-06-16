<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Дела в порядке | <?= $page_title ?></title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <?= $current_user ? '<link rel="stylesheet" href="css/flatpickr.min.css">' : '' ?>
</head>

<body <?= !$current_user ? 'class="body-background"' : '' ?>>
<h1 class="visually-hidden"><?= $page_title ?></h1>

<div class="page-wrapper">
    <div class="container <?= $current_user ? 'container--with-sidebar' : ''?>" >
        <header class="main-header">
            <a href="/">
                <img src="img/logo.png" width="153" height="42" alt="Логотип Дела в порядке">
            </a>

            <div class="main-header__side">
                <?php if (isset($current_user)) :?>
                <a class="main-header__side-item button button--plus open-modal" href="add-task.php">Добавить задачу</a>
                    <div class="main-header__side-item user-menu">
                        <div class="user-menu__data">

                            <p><?= $current_user['login'] ?></p>
                            <a href="logout.php">Выйти</a>
                        </div>
                    </div>
                <?php else : ?>
                <a class="main-header__side-item button button--transparent" href="auth.php">Войти</a>
                <? endif; ?>
            </div>
        </header>

        <?= $content ?>

    </div>
</div>

<footer class="main-footer">
    <div class="container">
        <div class="main-footer__copyright">
            <p>© 2022, «Дела в порядке»</p>

            <p>Веб-приложение для удобного ведения списка дел.</p>
        </div>
        <?php if (isset($current_user)) : ?>
        <a class="main-footer__button button button--plus" href="add-task.php">Добавить задачу</a>'
        <? endif; ?>
        <div class="main-footer__social social">
            <span class="visually-hidden">Мы в соцсетях:</span>
            <a class="social__link social__link--vkontakte" href="https://vk.com/htmlacademy" target="_blank"  rel="noreferrer">
                <span class="visually-hidden">Вконтакте</span>
                <svg width="27" height="27" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg">
                    <circle stroke="#879296" fill="none" cx="13.5" cy="13.5" r="12.666"/>
                    <path fill="#879296"
                          d="M13.92 18.07c.142-.016.278-.074.39-.166.077-.107.118-.237.116-.37 0 0 0-1.13.516-1.296.517-.165 1.208 1.09 1.95 1.58.276.213.624.314.973.28h1.95s.973-.057.525-.837c-.38-.62-.865-1.17-1.432-1.626-1.208-1.1-1.043-.916.41-2.816.886-1.16 1.236-1.86 1.13-2.163-.108-.302-.76-.214-.76-.214h-2.164c-.092-.026-.19-.026-.282 0-.083.058-.15.135-.195.225-.224.57-.49 1.125-.8 1.656-.973 1.61-1.344 1.697-1.51 1.59-.37-.234-.272-.975-.272-1.433 0-1.56.243-2.202-.468-2.377-.32-.075-.647-.108-.974-.098-.604-.052-1.213.01-1.793.186-.243.116-.438.38-.32.4.245.018.474.13.642.31.152.303.225.638.214.975 0 0 .127 1.832-.302 2.056-.43.223-.692-.167-1.55-1.618-.29-.506-.547-1.03-.77-1.57-.038-.09-.098-.17-.174-.233-.1-.065-.214-.108-.332-.128H6.485s-.312 0-.42.137c-.106.135 0 .36 0 .36.87 2 2.022 3.868 3.42 5.543.923.996 2.21 1.573 3.567 1.598z"/>
                </svg>
            </a>
        </div>

        <div class="main-footer__developed-by">
            <span class="visually-hidden">Разработано:</span>

            <a href="https://htmlacademy.ru/intensive/php">
                <img src="img/htmlacademy.svg" alt="HTML Academy" width="118" height="40">
            </a>
        </div>
    </div>
</footer>

<script src="flatpickr.js"></script>
<script src="script.js"></script>
</body>
</html>
