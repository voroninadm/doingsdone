<div class="content">

    <!-- include projects-nav component -->
    <?= include_component('projects-nav.php', ['projects' => $projects]); ?>

  <main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="" method="GET" autocomplete="off">
      <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

      <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
      <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
      </nav>

      <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed" type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
      </label>
    </div>

    <table class="tasks">
        <?php if (!$tasks) : ?>
        <span>Ничего не найдено по вашему запросу</span>
        <? endif; ?>
      <?php foreach ($tasks as $task) : ?>
        <?php if ($task['is_complete']) : continue; endif; ?>
            <tr class="tasks__item task <?= $task['is_complete'] ? 'task--completed' : ''; ?> <?= get_hours_to_deadline($task['deadline']) ? 'task--important' : ''; ?>">
        <td class="task__select">
          <label class="checkbox task__checkbox">
            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
            <span class="checkbox__text">
              <?= esc($task['name']) ?>
            </span>
          </label>
        </td>

        <td class="task__file">
          <a class="download-link" href="<?= $task['file_url'] ?>"><?= $task['file_url'] ?></a>
        </td>

        <td class="task__date">
          <?= date('d.m.Y', strtotime(esc($task['deadline']))) ?>
        </td>
      </tr>
      <? endforeach ?>
    </table>
  </main>
</div>
