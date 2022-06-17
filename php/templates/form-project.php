<div class="content">

    <?= include_component('projects-nav.php', ['projects' => $projects]); ?>

    <main class="content__main">
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form"  action="" method="POST" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="project_name">Название <sup>*</sup></label>

                <input class="form__input <?= isset($errors['project_name']) ? 'form__input--error' : ''; ?>"
                       type="text" name="project_name" id="project_name"
                       value="<?= $project_name ?>" placeholder="Введите название проекта">

                <?php if (isset($errors['project_name'])) : ?>
                    <p class="form__message"><?= $errors['project_name'] ?></p>
                <?php endif ?>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>
</div>
