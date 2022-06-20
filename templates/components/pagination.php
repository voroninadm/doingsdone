<ol class="pagination">
    <?php for ($i = 1; $i <= $pages_total; $i = $i + 1): ?>
    <li>
        <a href="index.php?page=<?= $i ?>"
           class="<?= $i === $page ? 'current' : '' ?>">
            <?= $i ?>
        </a>
    </li>
    <?php endfor; ?>
</ol>
