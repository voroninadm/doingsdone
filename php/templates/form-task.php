<div class="content">

    <!-- include projects-nav component -->
    <?= include_component('projects-nav.php', ['projects' => $projects]); ?>

    <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <?php if ($projects) : ?>
        <form class="form" action="" method="POST" autocomplete="off" enctype="multipart/form-data">

            <div class="form__row">
                <label class="form__label" for="name">Название <sup>*</sup></label>

                <input
                    class="form__input <?= isset($errors['task_name']) ? 'form__input--error' : ''; ?>"
                    type="text" name="name"
                    id="name" value="<?= esc($task_name) ?>"
                    placeholder="Введите название">

                <?php if (isset($errors['task_name'])) : ?>
                    <p class="form__message"><?= $errors['task_name'] ?></p>
                <? endif ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="project">Проект <sup>*</sup></label>

                <select class="form__input form__input--select <?= isset($errors['project_id']) ? 'form__input--error' : ''; ?>"
                        name="project" id="project">
                    <?php foreach ($projects as $project) : ?>
                        <option value="<?= esc($project['id']) ?>"><?= esc($project['name']) ?></option>
                    <? endforeach ?>
                </select>

                <?php if (isset($errors['project_id'])) : ?>
                    <p class="form__message"><?= $errors['project_id'] ?></p>
                <? endif ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="date">Дата выполнения</label>

                <input class="form__input form__input--date <?= isset($errors['deadline']) ? 'form__input--error' : ''; ?>"
                       type="text" name="date"
                       id="date" value="<?= $deadline ?>"
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД">

                <?php if (isset($errors['deadline'])) : ?>
                    <p class="form__message"><?= $errors['deadline'] ?></p>
                <? endif ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="file">Файл</label>

                <div class="form__input-file">
                    <input class="visually-hidden" type="file" name="file" id="file" value="">

                    <label class="button button--transparent" for="file">
                        <span>Выберите файл</span>
                    </label>
                </div>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>

        <?php else : ?>
            <p>Сперва добавьте проект, в который будут добавляться задачи. Например "Ремонт квартиры".</p>
            <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
        <?php endif; ?>

    </main>
</div>


