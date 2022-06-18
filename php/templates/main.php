<div class="content">

    <?= include_component('projects-nav.php', [
        'projects' => $projects,
        'project_id' => $project_id]);
    ?>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="" method="GET" autocomplete="off">
            <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/" class="tasks-switch__item <?= (!$filter) ? 'tasks-switch__item--active' : '' ?>">Все
                    задачи</a>
                <a href="?filter=today"
                   class="tasks-switch__item <?= $filter === 'today' ? 'tasks-switch__item--active' : '' ?>">Повестка
                    дня</a>
                <a href="?filter=tomorrow"
                   class="tasks-switch__item <?= $filter === 'tomorrow' ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
                <a href="?filter=out_of_date"
                   class="tasks-switch__item <?= $filter === 'out_of_date' ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
            </nav>

            <label class="checkbox">
                <input class="checkbox__input visually-hidden show_completed"
                       type="checkbox"
                       name="show_completed" value=""
                    <?= $show_completed_tasks ? 'checked' : '' ?>
                >
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">

            <?php if (!$tasks && !$search) : ?>
                <span>У вас нет актуальных задач</span>
            <?php elseif (!$tasks && $search) : ?>
                <span>Ничего не найдено по вашему запросу</span>
            <?php endif; ?>

            <?php foreach ($tasks as $task) : ?>

                <tr class="tasks__item task <?= $task['is_complete'] ? 'task--completed' : ''; ?> <?= get_hours_to_deadline($task['deadline'],
                    $task['is_complete']) ? 'task--important' : ''; ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox"
                                   value="<?= $task['id'] ?>"
                                <?= ($show_completed_tasks && $task['is_complete']) ? 'checked' : '' ?>>
                            <span class="checkbox__text">
              <?= esc($task['name']) ?>
            </span>
                        </label>
                    </td>

                    <td class="task__file">
                        <a class=" <?= $task['file_url'] ? 'download-link' : '' ?>"
                           href="<?= $task['file_url'] ?>"><?= $task['file_url'] ?></a>
                    </td>

                    <td class="task__date">
                        <?= $task['deadline'] ? date('d.m.Y', strtotime(esc($task['deadline']))) : null ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>

        <?= ($pages_total > 1)
            ? include_component('pagination.php', ['pages_total' => $pages_total, 'page' => $page])
            : null;
        ?>


    </main>
</div>
