<section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>
<nav class="main-navigation">
  <ul class="main-navigation__list">
      <?php if (empty($projects)) : ?>
      <p>У Вас еще нет проектов</p>
      <?php endif; ?>
    <?php foreach ($projects as $project) : ?>
    <li class="main-navigation__list-item <?= $project['id'] === $project_id ? 'main-navigation__list-item--active' : ''; ?>">
      <a class="main-navigation__list-item-link" href="index.php?project_id=<?= $project['id']; ?>">
        <?= esc($project['name']) ?>
      </a>
      <span class="main-navigation__list-item-count">
        <?= esc($project['count_tasks']) ?>
      </span>
    </li>
    <?php endforeach ?>
  </ul>
</nav>
<a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
      </section>
