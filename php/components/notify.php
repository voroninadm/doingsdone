<section>
    <p><b>Уважаемый(ая) <?= $user_login ?>!</b></p>
    <p>У вас запланировано:</p>
    <ul>
        <?php foreach ($tasks as $task) : ?>
        <li><?= $task ?></li>
        <? endforeach; ?>
    </ul>
    <p>Постарайтесь выполнить свои задачи в срок!</p>
</section>
